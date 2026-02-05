<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// If already logged in, go to dashboard
if (isset($_SESSION["event_organiser"]) && !empty($_SESSION["event_organiser"])) {
    header("Location: ../event_organiser/dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {

    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($email === "" || $password === "") {
        $error = "Please enter email and password.";
    } else {

        // IMPORTANT: role must match DB exactly
        $role = "event_organizer";

        $sql = "SELECT email, password, role FROM users WHERE email=? AND role=? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            $error = "SQL error: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $email, $role);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            mysqli_stmt_close($stmt);

            // ✅ Plain text password check (your current DB setup)
            if (!$user || $password !== $user["password"]) {
                $error = "Invalid email or password.";
            } else {
                // ✅ This is what auth_guard.php expects
                $_SESSION["event_organiser"] = $user["email"];

                header("Location: ../event_organiser/dashboard.php");
                exit;
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login | Event Organiser</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Same style as organiser pages -->
  <link rel="stylesheet" href="../event_organiser/style.css">

  <!-- Icons -->
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="container">

  <div class="top-bar">
    <h1>Event Organiser Login</h1>
  </div>

  <?php if ($error !== ""): ?>
    <div class="msg error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" placeholder="organiser@eco.com" required>
    </div>

    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" placeholder="••••••••" required>
    </div>

    <button class="btn btn-secondary" type="submit" name="login" style="width:100%;">
      <i class="fa-solid fa-right-to-bracket"></i> Login
    </button>
  </form>

</div>

</body>
</html>
