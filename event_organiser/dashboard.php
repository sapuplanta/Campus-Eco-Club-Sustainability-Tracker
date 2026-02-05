<?php
require_once __DIR__ . "/auth_guard.php";
$organiserEmail = $_SESSION['event_organiser'] ?? 'Event Organiser';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Event Organiser Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Shared styling -->
  <link rel="stylesheet" href="style.css">

  <!-- Icons (Font Awesome CDN) -->
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="container">
  <div class="top-bar">
    <h1>Event Organiser Dashboard</h1>
    <div class="nav-actions">
      <a class="btn btn-danger" href="../auth/logout.php">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </a>
    </div>
  </div>

  <p class="welcome">
    Welcome, <b><?php echo htmlspecialchars($organiserEmail); ?></b>
  </p>

  <div class="grid">
    <a href="create_event.php" class="card create">
      <i class="fa-solid fa-calendar-plus"></i> Create Event
    </a>

    <a href="manage_registration.php" class="card manage">
      <i class="fa-solid fa-clipboard-list"></i> Manage Registrations
    </a>

    <a href="announcement.php" class="card announce">
      <i class="fa-solid fa-bullhorn"></i> Post Announcement
    </a>

    <a href="assign_task.php" class="card assign">
      <i class="fa-solid fa-user-check"></i> Assign Task
    </a>
  </div>
</div>

</body>
</html>
