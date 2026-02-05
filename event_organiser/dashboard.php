<?php
require_once __DIR__ . "/auth_guard.php";
?>

<h2>Event Organiser Dashboard</h2>

<p>
  Welcome, <?php echo htmlspecialchars($_SESSION['event_organiser']); ?>!
  | <a href="../auth/logout.php">Logout</a>
</p>

<ul>
    <li><a href="create_event.php">Create Event</a></li>
    <li><a href="manage_registration.php">Manage Registrations</a></li>
    <li><a href="announcement.php">Post Announcement</a></li>
    <li><a href="assign_task.php">Assign Task</a></li>
</ul>
