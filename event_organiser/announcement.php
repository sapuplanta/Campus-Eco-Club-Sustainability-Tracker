<?php
require_once __DIR__ . "/auth_guard.php";
require_once __DIR__ . "/../config/db.php";

$messageStatus = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["post"])) {
    $message = trim($_POST["message"]);

    if ($message === "") {
        $messageStatus = "Announcement message cannot be empty.";
    } else {
        $sql = "INSERT INTO announcement (message) VALUES (?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $message);

        if (mysqli_stmt_execute($stmt)) {
            $messageStatus = "Announcement posted successfully.";
        } else {
            $messageStatus = "Error posting announcement.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Post Announcement</title>
</head>
<body>

<p>
  Logged in as <?php echo htmlspecialchars($_SESSION['event_organiser']); ?>
  | <a href="../auth/logout.php">Logout</a>
  | <a href="dashboard.php">Back to Dashboard</a>
</p>

<h3>Post Announcement</h3>

<?php if ($messageStatus !== ""): ?>
  <p style="color:green;"><?php echo htmlspecialchars($messageStatus); ?></p>
<?php endif; ?>

<form method="POST">
    <textarea name="message" rows="5" cols="50" placeholder="Enter announcement here..." required></textarea><br><br>
    <button type="submit" name="post">Post Announcement</button>
</form>

</body>
</html>
