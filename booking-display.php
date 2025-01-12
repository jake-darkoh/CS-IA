    <?php
    session_start(); // Start the session at the beginning

    // Database connection configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "car-shop";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handling approval or decline actions
    if (isset($_GET['action']) && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $action = $_GET['action'];

        // Fetch the booking details
        $sql = "SELECT status FROM booking_appointments WHERE booking_id = $id";
        $result = $conn->query($sql);
        $booking = $result->fetch_assoc();

        if ($booking) {
            // Determine new status based on action
            $current_status = strtolower($booking['status']);
            
            // Only set the session message for approve or decline actions
            if ($action == 'approve') {
                if ($current_status == 'approved') {
                    $status = 'pending';
                } else {
                    $status = 'approved';
                }
                // Set success message after approval
                $_SESSION['status_message'] = "<script>alert('Booking approved successfully!')</script>";
            } elseif ($action == 'decline') {
                if ($current_status == 'declined') {
                    $status = 'pending';
                } else {
                    $status = 'declined';
                }
                // Set success message after decline
                $_SESSION['status_message'] = "<script>alert('Booking declined successfully!')</script>";
            } else {
                // Do not set session message for toggle action
                $status = 'pending'; // Default to pending if action is not recognized
            }

            // Update the status in the database
            $update_sql = "UPDATE  booking_appointments SET status = '$status' WHERE booking_id = $id";
            if ($conn->query($update_sql) === TRUE) {
                // Redirect to display updated status, no session message for toggle
                if ($action == 'approve' || $action == 'decline') {
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                }
            } else {
                echo "Error updating status: " . $conn->error;
            }
        }
    }

    // Fetch all bookings
    $sql = "SELECT * FROM  booking_appointments";
    $result = $conn->query($sql);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Booking Appointment Requests</title>
        <link rel="stylesheet" href="booking-display.css">
    </head>
    <body>
        <h1>Booking Appointment Requests</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Appointment Name</th>
                    <th>Appointment Description</th>
                    <th>Appointment Date</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check and display session message if set
                if (isset($_SESSION['status_message'])) {
                    echo $_SESSION['status_message']; // Display the alert message
                    unset($_SESSION['status_message']); // Clear the message after displaying it
                }

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["booking_id"] . "</td>";
                        echo "<td>" . $row["first_name"] . "</td>";
                        echo "<td>" . $row["last_name"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["appointment_name"] . "</td>";
                        echo "<td>" . $row["appointment_desc"] . "</td>";
                        echo "<td>" . $row["appointment_date"] . "</td>";
                        echo "<td>" . $row["created_at"] . "</td>";
                        
                        // Safely access the status key
                        $status = isset($row["status"]) ? strtolower($row["status"]) : 'pending';
                        echo "<td class='" . $status . "'>" . ucfirst($status) . "</td>";
                        
                        echo "<td>";
                        // Display approve/decline actions based on status
                        if ($status == 'pending') {
                            echo "<a href='?action=approve&id=" . $row["booking_id"] . "' class='action-btn approve'>Approve</a>";
                            echo "<a href='?action=decline&id=" . $row["booking_id"] . "' class='action-btn decline'>Decline</a>";
                        } else {
                            echo "<a href='?action=approve&id=" . $row["booking_id"] . "' class='action-btn approve'>Toggle Status</a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No bookings found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
        // Close the connection
        $conn->close();
        ?>
    </body>
    </html>
