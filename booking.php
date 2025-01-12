
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Simulate a session message for demonstration
        const bookingMessage = "success"; // Replace with PHP session value

        if (bookingMessage === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Booking Successful',
                text: 'Your appointment has been booked successfully!',
                confirmButtonText: 'OK'
            });
        } else if (bookingMessage === 'error') {
            Swal.fire({
                icon: 'error',
                title: 'Booking Failed',
                text: 'There was an issue with your booking. Please try again later.',
                confirmButtonText: 'OK'
            });
        }
    });
</script>

</html>