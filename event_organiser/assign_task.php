<?php
require_once __DIR__ . "/auth_guard.php";
require_once __DIR__ . "/../config/db.php";

$statusMsg = "";
$isError = false;

// Load events for dropdown
$eventsResult = mysqli_query($conn, "SELECT eventID, name FROM event ORDER BY date DESC");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["assign"])) {
    $userID = trim($_POST["userID"] ?? "");
    $eventID = trim($_POST["eventID"] ?? "");
    $taskName = trim($_POST["taskName"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($userID === "" || $eventID === "" || $taskName === "") {
        $statusMsg = "User ID, Event, and Task Name are required.";
        $isError = true;
    } elseif (!ctype_digit($userID)) {
        $statusMsg = "User ID must be a number.";
        $isError = true;
    } else {
        $taskID = uniqid("TS");
        $taskStatus = "Assigned";

        $sql = "INSERT INTO task (taskID, name, description, status, eventID, userID)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssi", $taskID, $taskName, $description, $taskStatus, $eventID, $userID);

        if (mysqli_stmt_execute($stmt)) {
            $statusMsg = "Task assigned successfully. Task ID: $taskID";
        } else {
            $statusMsg = "Error assigning task: " . mysqli_error($conn);
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
  <title>Assign Task</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="container">

  <div class="top-bar">
    <h1>Assign Task</h1>
    <div class="nav-actions">
      <a class="btn btn-secondary" href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a>
      <a class="btn btn-danger" href="../auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
  </div>

  <?php if ($statusMsg !== ""): ?>
    <div class="msg <?php echo $isError ? 'error' : ''; ?>">
      <?php echo htmlspecialchars($statusMsg); ?>
    </div>
  <?php endif; ?>

  <form method="POST">

    <div class="form-group">
      <label>Volunteer User ID</label>
      <input type="text" name="userID" placeholder="Example: 3" required>
    </div>

    <div class="form-group">
      <label>Event</label>
      <select name="eventID" required style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:10px;">
        <option value="">-- Select Event --</option>
        <?php if ($eventsResult && mysqli_num_rows($eventsResult) > 0): ?>
          <?php while ($ev = mysqli_fetch_assoc($eventsResult)) { ?>
            <option value="<?php echo htmlspecialchars($ev["eventID"]); ?>">
              <?php echo htmlspecialchars($ev["eventID"] . " - " . $ev["name"]); ?>
            </option>
          <?php } ?>
        <?php endif; ?>
      </select>
    </div>

    <div class="form-group">
      <label>Task Name</label>
      <input type="text" name="taskName" required>
    </div>

    <div class="form-group">
      <label>Description (optional)</label>
      <textarea name="description" placeholder="Extra details..."></textarea>
    </div>

    <button class="btn btn-secondary" type="submit" name="assign">
      <i class="fa-solid fa-user-check"></i> Assign Task
    </button>
  </form>

</div>
</body>
</html>
