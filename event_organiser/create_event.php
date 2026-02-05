<?php
require_once __DIR__ . "/auth_guard.php";
require_once __DIR__ . "/../config/db.php";

$message = "";
$isError = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $name = trim($_POST["name"] ?? "");
    $date = $_POST["date"] ?? "";
    $location = trim($_POST["location"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($name === "" || $date === "" || $location === "") {
        $message = "Name, Date and Location are required.";
        $isError = true;
    } else {
        $eventID = uniqid("EV");     // generates EVxxxx unique id
        $status  = "Active";

        $sql = "INSERT INTO event (eventID, name, date, location, description, status)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $eventID, $name, $date, $location, $description, $status);

        if (mysqli_stmt_execute($stmt)) {
            $message = "Event created successfully. Event ID: $eventID";
        } else {
            $message = "Error creating event: " . mysqli_error($conn);
            $isError = true;
        }

        mysqli_stmt_close($stmt);
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Create Event</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="container">

  <div class="top-bar">
    <h1>Create Event</h1>
    <div class="nav-actions">
      <a class="btn btn-secondary" href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a>
      <a class="btn btn-danger" href="../auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
  </div>

  <?php if ($message !== ""): ?>
    <div class="msg <?php echo $isError ? 'error' : ''; ?>">
      <?php echo htmlspecialchars($message); ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <div class="form-group">
      <label>Event Name</label>
      <input type="text" name="name" required>
    </div>

    <div class="form-group">
      <label>Date</label>
      <input type="date" name="date" required>
    </div>

    <div class="form-group">
      <label>Location</label>
      <input type="text" name="location" required>
    </div>

    <div class="form-group">
      <label>Description (optional)</label>
      <textarea name="description" placeholder="Enter description..."></textarea>
    </div>

    <button class="btn btn-secondary" type="submit" name="submit">
      <i class="fa-solid fa-calendar-plus"></i> Create Event
    </button>
  </form>

</div>
</body>
</html>
