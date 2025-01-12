<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="booking.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <nav class="navbar">
        <div class="navdiv">
            <div class="logo"><a href="#">NameOfWebsite</a></div>
            <ul class="nav-links">
                <li><a href="#">Booking Appointments</a></li>
                <li><a href="#">Shopping</a></li>
                <li><a href="user-account.php">Account Profile</a></li>
            </ul>
            <div class="buttons">
                <button class="nav-btn"><a href="register.html">Log Out</a></button>
            </div>
        </div>
    </nav>
    <div class="booking-form">
        <div class="form-left">
            <h2>Looking for the best mechanic in town?</h2>
            <h3>Schedule a Booking!</h3>
            <p>Get in touch with us now and book your appointment or get a free consultation for the issue you are facing.</p>
        </div>
        <div class="form-right">
            <h2>Send Us A Message To Book Below</h2>

            <form action="booking-submit.php" method="post">
                <input type="text" name="first-name" placeholder="First Name" required>
                <input type="text" name="last-name" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="text" name="appointment-name" placeholder="Appointment Name" required>
                <textarea name="appointment-desc" placeholder="Describe your issue" required></textarea>
                <input type="date" name="appointment-date" required>
                <button type="submit" onclick="trial()">Submit Form</button>
            </form>
        </div>
    </div>

    <!-- SweetAlert based on session message -->
    <script>
        function trial(){
            alert("booking added")
        }
</script>
</body>
</html>