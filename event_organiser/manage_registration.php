<?php
require_once __DIR__ . "/auth_guard.php";
require_once __DIR__ . "/../config/db.php";

$msg = "";
$isError = false;

// Handle approve / reject
if (isset($_GET["approve"]) || isset($_GET["reject"])) {

    $action = isset($_GET["approve"]) ? "approve" : "reject";
    $id = ($action === "approve") ? $_GET["approve"] : $_GET["reject"];

    if (!is_string($id) || trim($id) === "") {
        $msg = "Invalid registration ID.";
        $isError = true;
    } else {
        $newStatus = ($action === "approve") ? "Approved" : "Rejected";

        $sql = "UPDATE registration SET status=? WHERE registrationID=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $newStatus, $id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $msg = "Registration $id updated to $newStatus.";
        } else {
            $msg = "No changes made. (Already updated or ID not found)";
            $isError = true;
        }

        mysqli_stmt_close($stmt);

        // Redirect to prevent re-trigger on refresh
        header("Location: manage_registration.php?msg=" . urlencode($msg) . "&err=" . ($isError ? 1 : 0));
        exit;
    }
}

// Message after redirect
if (isset($_GET["msg"])) {
    $msg = $_GET["msg"];
    $isError = (($_GET["err"] ?? "0") === "1");
}

// Load registrations
$result = mysqli_query($conn, "SELECT * FROM registration ORDER BY registrationID DESC");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Manage Registrations</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="container">

  <div class="top-bar">
    <h1>Manage Registrations</h1>
    <div class="nav-actions">
      <a class="btn btn-secondary" href="dashboard.php">
        <i class="fa-solid fa-house"></i> Dashboard
      </a>
      <a class="btn btn-danger" href="../auth/logout.php">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </a>
    </div>
  </div>

  <?php if ($msg !== ""): ?>
    <div class="msg <?php echo $isError ? 'error' : ''; ?>">
      <?php echo htmlspecialchars($msg); ?>
    </div>
  <?php endif; ?>

  <?php if (!$result): ?>
    <div class="msg error">Database error.</div>

  <?php elseif (mysqli_num_rows($result) === 0): ?>
    <div class="msg">No registrations found.</div>

  <?php else: ?>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
      <div style="border:1px solid #eee; padding:16px; border-radius:12px; margin-bottom:12px;">
        <div><b>Registration ID:</b> <?php echo htmlspecialchars($row["registrationID"]); ?></div>
        <div><b>Status:</b> <?php echo htmlspecialchars($row["status"] ?? "Pending"); ?></div>

        <div style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
          <a class="btn btn-secondary"
             href="?approve=<?php echo urlencode($row["registrationID"]); ?>">
            <i class="fa-solid fa-circle-check"></i> Approve
          </a>

          <a class="btn btn-danger"
             href="?reject=<?php echo urlencode($row["registrationID"]); ?>">
            <i class="fa-solid fa-circle-xmark"></i> Reject
          </a>
        </div>
      </div>
    <?php } ?>

  <?php endif; ?>

</div>
</body>
</html>
