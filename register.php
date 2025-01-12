<?php
require 'dbconnect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Validate password
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        echo "<script>alert('Password must be at least 8 characters long and include a symbol, a number, and an uppercase letter.'); window.location.href='register.html';</script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Determine the role
    $role = ($email === "admin@gmail.com" && $password === "Admin*123") ? 'admin' : 'user';

    // Check if email already exists
    $check_email = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already registered. Please log in.'); window.location.href='login.html';</script>";
        exit();
    }

    // Insert user into the database
    $sql = "INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Redirecting to login page.'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Error during registration. Please try again.'); window.location.href='register.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
