<?php
require_once __DIR__ . "/auth_guard.php";
require_once __DIR__ . "/../config/db.php";

$statusMsg = "";
$isError = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["post"])) {
    $message = trim($_POST["message"] ?? "");

    if ($message === "") {
        $statusMsg = "Announcement message cannot be empty.";
        $isError = true;
    } else {
        $announcementID = uniqid("AN");   // UNIQUE PRIMARY KEY
        $status = "Active";

        $sql = "INSERT INTO announcement (announcementID, message, status)
                VALUES (?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $announcementID, $message, $status);

        if (mysqli_stmt_execute($stmt)) {
            $statusMsg = "Announcement posted successfully. ID: $announcementID";
        } else {
            $statusMsg = "Error posting announcement: " . mysqli_error($conn);
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
  <title>Post Announcement</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="container">

  <div class="top-bar">
    <h1>Post Announcement</h1>
    <div class="nav-actions">
      <a class="btn btn-secondary" href="dashboard.php">
        <i class="fa-solid fa-house"></i> Dashboard
      </a>
      <a class="btn btn-danger" href="../auth/logout.php">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </a>
    </div>
  </div>

  <?php if ($statusMsg !== ""): ?>
    <div class="msg <?php echo $isError ? 'error' : ''; ?>">
      <?php echo htmlspecialchars($statusMsg); ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <div class="form-group">
      <label>Announcement Message</label>
      <textarea name="message" placeholder="Enter announcement here..." required></textarea>
    </div>

    <button class="btn btn-secondary" type="submit" name="post">
      <i class="fa-solid fa-paper-plane"></i> Post Announcement
    </button>
  </form>

</div>

</body>
</html>
