<?php
session_start();
require 'dbconnect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Check if the user exists
    $sql = "SELECT id, first_name, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // User authenticated, create a login history record
            $login_id = bin2hex(random_bytes(16)); // Generate a unique ID
            $user_id = $user['id'];

            $insert_login = $conn->prepare("INSERT INTO login_history (login_id, user_id) VALUES (?, ?)");
            $insert_login->bind_param("si", $login_id, $user_id);

            if ($insert_login->execute()) {
                // Store session data
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    // Redirect to admin dashboard
                    echo "<script>alert('You are successfully logged in as Admin.'); window.location.href='admin-dashboard.html';</script>";
                } else {
                    // Redirect to user dashboard
                    echo "<script>alert('You are successfully logged in as a user.'); window.location.href='user-dashboard.html';</script>";
                }
            } else {
                echo "<script>alert('Failed to record login history.'); window.location.href='login.html';</script>";
            }
        } else {
            echo "<script>alert('Incorrect password.'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('No account found with that email.'); window.location.href='register.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
