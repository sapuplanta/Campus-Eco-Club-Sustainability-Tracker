<?php
require_once __DIR__ . "/auth_guard.php";
require_once __DIR__ . "/../config/db.php";

$statusMsg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["assign"])) {
    $volunteerID = trim($_POST["volunteer"]);
    $taskName = trim($_POST["task"]);

    if ($volunteerID === "" || $taskName === "") {
        $statusMsg = "All fields are required.";
    } elseif (!ctype_digit($volunteerID)) {
        $statusMsg = "Volunteer ID must be a number.";
    } else {
        $sql = "INSERT INTO task (task_name, volunteerID) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $taskName, $volunteerID);

        if (mysqli_stmt_execute($stmt)) {
            $statusMsg = "Task assigned successfully.";
        } else {
            $statusMsg = "Error assigning task.";
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
</head>
<body>

<p>
  Logged in as <?php echo htmlspecialchars($_SESSION['event_organiser']); ?>
  | <a href="../auth/logout.php">Logout</a>
  | <a href="dashboard.php">Back to Dashboard</a>
</p>

<h3>Assign Task to Volunteer</h3>

<?php if ($statusMsg !== ""): ?>
  <p style="color:green;"><?php echo htmlspecialchars($statusMsg); ?></p>
<?php endif; ?>

<form method="POST">
    <label>Volunteer ID</label><br>
    <input type="text" name="volunteer" required><br><br>

    <label>Task Name</label><br>
    <input type="text" name="task" required><br><br>

    <button type="submit" name="assign">Assign Task</button>
</form>

</body>
</html>
