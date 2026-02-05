<?php
require_once __DIR__ . "/auth_guard.php";
require_once __DIR__ . "/../config/db.php";

$status = "";
$isError = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["post"])) {
    $msg = trim($_POST["message"]);
    if ($msg === "") { $status = "Announcement cannot be empty."; $isError = true; }
    else {
        $sql = "INSERT INTO announcement (message) VALUES (?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $msg);

        if (mysqli_stmt_execute($stmt)) $status = "Announcement posted successfully.";
        else { $status = "Error posting announcement."; $isError = true; }

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
      <label>Announcement</label>
      <textarea name="message" placeholder="Enter announcement here..." required></textarea>
    </div>

    <button class="btn btn-secondary" type="submit" name="post">
      <i class="fa-solid fa-paper-plane"></i> Post
    </button>
  </form>

</div>
</body>
</html>
