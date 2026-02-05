<?php
require_once __DIR__ . "/auth_guard.php";
require_once __DIR__ . "/../config/db.php";

// Handle approve/reject actions safely
if (isset($_GET["approve"]) || isset($_GET["reject"])) {

    $action = isset($_GET["approve"]) ? "approve" : "reject";
    $id = ($action === "approve") ? $_GET["approve"] : $_GET["reject"];

    // validate id as integer
    if (filter_var($id, FILTER_VALIDATE_INT) === false) {
        header("Location: manage_registration.php");
        exit;
    }

    $newStatus = ($action === "approve") ? "Approved" : "Rejected";

    $sql = "UPDATE registrations SET status=? WHERE registrationID=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $newStatus, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // redirect to avoid resubmitting action on refresh
    header("Location: manage_registration.php");
    exit;
}

// Load registrations
$result = mysqli_query($conn, "SELECT * FROM registrations ORDER BY registrationID DESC");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Manage Registrations</title>
</head>
<body>

<p>
  Logged in as <?php echo htmlspecialchars($_SESSION['event_organiser']); ?>
  | <a href="../auth/logout.php">Logout</a>
  | <a href="dashboard.php">Back to Dashboard</a>
</p>

<h3>Manage Event Registrations</h3>

<?php if (!$result): ?>
  <p style="color:red;">Error loading registrations.</p>
<?php else: ?>

  <?php if (mysqli_num_rows($result) === 0): ?>
    <p>No registrations found.</p>
  <?php endif; ?>

  <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <div style="border:1px solid #ddd; padding:10px; margin:10px 0; border-radius:8px;">
      <p><b>Registration ID:</b> <?php echo htmlspecialchars($row['registrationID']); ?></p>
      <p><b>Status:</b> <?php echo htmlspecialchars($row['status']); ?></p>

      <a href="?approve=<?php echo urlencode($row['registrationID']); ?>">Approve</a>
      |
      <a href="?reject=<?php echo urlencode($row['registrationID']); ?>">Reject</a>
    </div>
  <?php } ?>

<?php endif; ?>

</body>
</html>
