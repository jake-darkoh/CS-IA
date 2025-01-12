
<!DOCTYPE html>
<html lang="en">
<?php
session_start(); // Start the session

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car-shop";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate POST input
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = isset($_POST['first-name']) ? htmlspecialchars(trim($_POST['first-name'])) : '';
    $last_name = isset($_POST['last-name']) ? htmlspecialchars(trim($_POST['last-name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $appointment_name = isset($_POST['appointment-name']) ? htmlspecialchars(trim($_POST['appointment-name'])) : '';
    $appointment_desc = isset($_POST['appointment-desc']) ? htmlspecialchars(trim($_POST['appointment-desc'])) : '';
    $appointment_date = isset($_POST['appointment-date']) ? htmlspecialchars(trim($_POST['appointment-date'])) : '';

    // Check for empty fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($appointment_name) || empty($appointment_desc) || empty($appointment_date)) {
        $_SESSION['booking_message'] = 'error';
        header("Location: booking.php");
        exit();
    }

    // Prepare and bind (targeting the 'bookings' table)
    $stmt = $conn->prepare("INSERT INTO booking_appointments     (first_name, last_name, email, appointment_name, appointment_desc, appointment_date, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $appointment_name, $appointment_desc, $appointment_date);

    if ($stmt->execute()) {
        // Booking was successful, set session for success message
        $_SESSION['booking_message'] = 'success';
    } else {
        // Booking failed, set session for error message
        $_SESSION['booking_message'] = 'error';
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();

// Redirect back to the booking form to show the message
header("Location: booking-appoint.php");
exit();
?>
