<?php
require_once __DIR__ . "/auth_guard.php";
require_once __DIR__ . "/../config/db.php";

$status = "";
$isError = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["assign"])) {
    $volunteerID = trim($_POST["volunteer"]);
    $taskName = trim($_POST["task"]);

    if ($volunteerID === "" || $taskName === "") {
        $status = "All fields are required.";
        $isError = true;
    } elseif (!ctype_digit($volunteerID)) {
        $status = "Volunteer ID must be a number.";
        $isError = true;
    } else {
        $sql = "INSERT INTO task (task_name, volunteerID) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $taskName, $volunteerID);

        if (mysqli_stmt_execute($stmt)) $status = "Task assigned successfully.";
        else { $status = "Error assigning task."; $isError = true; }

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

  <?php if ($status !== ""): ?>
    <div class="msg <?php echo $isError ? 'error' : ''; ?>">
      <?php echo htmlspecialchars($status); ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <div class="form-group">
      <label>Volunteer ID</label>
      <input type="text" name="volunteer" required>
    </div>

    <div class="form-group">
      <label>Task Name</label>
      <input type="text" name="task" required>
    </div>

    <button class="btn btn-secondary" type="submit" name="assign">
      <i class="fa-solid fa-user-check"></i> Assign
    </button>
  </form>

</div>
</body>
</html>
