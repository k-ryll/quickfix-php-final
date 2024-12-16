<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /quickfix-php/login.php'); // Redirect to login page
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/quickfix-php/config/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/quickfix-php/functions.php'; // Include the functions file

// Get the logged-in user's ID
$user_id = $_SESSION['user_id']; // Replace with actual user ID logic

// Fetch appointments for the logged-in user
$stmt = $conn->prepare("SELECT * FROM appointments WHERE fixer_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Booking List</title>
</head>
<body>

<header class="bg-light text-dark p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="h4">QuickFix</h1>
        <nav>
            <a href="fixerProfile.php" class="btn btn-outline-primary">Home Page</a>
            <a href="certificates.php" class="btn btn-outline-secondary">Gallery</a>
            <a href="appointments.php" class="btn btn-outline-secondary">Appointments</a>
            <a href="bookinglist.php" class="btn btn-outline-secondary">Booking List</a>
            <a href="/quickfix-php/pages/logout.php" class="btn btn-danger">Log Out</a>
        </nav>
    </div>
</header>

<div class="container mt-4">
    <h2 class="mb-4">Your Appointments</h2>
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Customer Name</th>
                <th>Details</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $appointment): ?>
            <tr>
                <td><?php echo htmlspecialchars($appointment['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($appointment['details']); ?></td>
                <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                <td><?php echo ucfirst(htmlspecialchars($appointment['status'])); ?></td>
                <td>
                    <?php if ($appointment['status'] === 'pending'): ?>
                        <button class="btn btn-success confirm-btn" data-id="<?php echo $appointment['id']; ?>">Confirm</button>
                        <button class="btn btn-danger decline-btn" data-id="<?php echo $appointment['id']; ?>">Decline</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.confirm-btn').forEach(button => {
            button.addEventListener('click', function () {
                const appointmentId = this.getAttribute('data-id');
                updateAppointmentStatus(appointmentId, 'confirmed');
            });
        });

        document.querySelectorAll('.decline-btn').forEach(button => {
            button.addEventListener('click', function () {
                const appointmentId = this.getAttribute('data-id');
                updateAppointmentStatus(appointmentId, 'declined');
            });
        });

        async function updateAppointmentStatus(appointmentId, status) {
            try {
                const response = await fetch('/quickfix-php/pages/update_appointment_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: appointmentId, status: status })
                });

                const result = await response.json();
                if (result.status === 'success') {
                    alert(result.message);
                    location.reload();
                } else {
                    alert('Failed to update status: ' + result.message);
                }
            } catch (error) {
                alert('An error occurred: ' + error);
            }
        }
    });
</script>

</body>
</html>