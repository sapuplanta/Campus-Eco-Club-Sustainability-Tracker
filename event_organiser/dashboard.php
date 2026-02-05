<?php
session_start();
if (!isset($_SESSION['event_organiser'])) {
    header("Location:../auth/login.php");
}
?>

<h2>Event Organiser Dashboard</h2>
<p>Welcome, <?php echo htmlspecialchars($_SESSION['event_organiser']); ?>!</p>
<ul>
    <li><a href="create_event.php">Create Event</a></li>
    <li><a href="manage_registration.php">Manage Registrations</a></li>
    <li><a href="announcement.php">Post Announcement</a></li>
    <li><a href="assign_task.php">Assign Task</a></li>
</ul>