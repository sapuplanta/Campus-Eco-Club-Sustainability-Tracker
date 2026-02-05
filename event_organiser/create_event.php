<?php
require_once __DIR__ . "/auth_guard.php";
require_once __DIR__ . "/../config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $title = trim($_POST["title"]);
    $date = $_POST["date"];
    $location = trim($_POST["location"]);

    if ($title === "" || $date === "" || $location === "") {
        $message = "All fields are required.";
    } else {
        $sql = "INSERT INTO event (title, date, location) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $title, $date, $location);

        if (mysqli_stmt_execute($stmt)) {
            $message = "Event created successfully.";
        } else {
            $message = "Error creating event.";
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
</head>
<body>

<p>
  Logged in as <?php echo htmlspecialchars($_SESSION['event_organiser']); ?>
  | <a href="../auth/logout.php">Logout</a>
  | <a href="dashboard.php">Back to Dashboard</a>
</p>

<h2>Create Event</h2>

<?php if ($message !== ""): ?>
  <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="POST">
    <label>Title</label><br>
    <input type="text" name="title" required><br><br>

    <label>Date</label><br>
    <input type="date" name="date" required><br><br>

    <label>Location</label><br>
    <input type="text" name="location" required><br><br>

    <button type="submit" name="submit">Create Event</button>
</form>

</body>
</html>
