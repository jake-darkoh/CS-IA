<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

require 'dbconnect.php'; // Include database connection

$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$query = "SELECT first_name, last_name, email FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

$stmt->close();

// Handle form submission for updating details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_first_name = htmlspecialchars($_POST['first_name']);
    $new_last_name = htmlspecialchars($_POST['last_name']);
    $new_password = htmlspecialchars($_POST['password']);

    // Password validation (optional)
    if (!empty($new_password) && (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_password))) {
        echo "<script>alert('Password must be at least 8 characters long, contain one letter, one number, and one special character.');</script>";
    } else {
        $password_hashed = password_hash($new_password, PASSWORD_BCRYPT);
        
        // Update user details in the database
        $update_query = "UPDATE users SET first_name = ?, last_name = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssi", $new_first_name, $new_last_name, $password_hashed, $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('Account details updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating account details. Please try again.');</script>";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Profile</title>
    <link rel="stylesheet" href="user-account.css"> <!-- Your custom styles -->
    <link rel="stylesheet" href="navbar.css"> <!-- Include the navbar styles here -->
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navdiv">
            <div class="logo"><a href="#">NameOfWebsite</a></div>
            <ul class="nav-links">
                <li><a href="booking-appoint.html">Booking Appointments</a></li>
                <li><a href="#">Shopping</a></li>
                <li><a href="user-account.php">Account Profile</a></li>
                <li><a href="#">About Site</a></li>
            </ul>
            <div class="buttons">
                <button class="nav-btn"><a href="register.html">Log Out</a></button>
            </div>
        </div>
    </nav>

    <!-- Account Details Section -->
    <div class="account-container">
        <h1 class="account-header">Account Profile</h1>

        <form action="account.php" method="POST" class="account-form">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" value="<?php echo $user['first_name']; ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" value="<?php echo $user['last_name']; ?>" required>

            <label for="password">New Password (leave blank to keep current):</label>
            <input type="password" name="password" id="password">

            <button type="submit" class="submit-button">Update Details</button>
        </form>

        <form action="logout.php" method="POST" class="logout-form">
            <button type="submit" class="logout-button">Switch Account</button>
        </form>
    </div>
</body>
</html>
