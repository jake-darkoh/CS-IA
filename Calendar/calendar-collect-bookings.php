<?php
include '../db-connect.php'; // Ensure this path is correct

// Fetch all approved bookings from the database
$sql = "SELECT first_name, last_name, appointment_name, appointment_desc, appointment_date 
        FROM booking_appointments 
        WHERE status = 'approved'";
$result = $conn->query($sql);

$bookings = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

$conn->close();
echo json_encode($bookings);
?>
