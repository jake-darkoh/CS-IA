<?php
// Include the database connection file
include 'db-connect.php'; // Ensure this path is correct

// Initialize an empty array to store bookings
$bookings = [];

// SQL query to fetch all approved bookings from the database
$sql = "SELECT first_name, last_name, appointment_name, appointment_desc, appointment_date 
        FROM booking_appointments 
        WHERE status = 'approved'";

// Execute the query
$result = $conn->query($sql);

// Check if there are any results
if ($result->num_rows > 0) {
    // Loop through the results and store them in the $bookings array
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row; // Add the row to the bookings array
    }
}

// Close the database connection
$conn->close();

// Set the correct content type for JSON response
header('Content-Type: application/json');

// Return the bookings as a JSON object
echo json_encode($bookings);
?>
