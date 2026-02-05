<?php
session_start();
include("../Campus-Eco-Club-Sustainability-Tracker/config/db.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $error = "Please enter email and password.";
    } else {

        // Secure query (prevents SQL injection)
        $sql = "SELECT userID, email, password, role FROM users WHERE email=? AND role=? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);

        $role = "event_organizer"; // keep consistent in DB
        mysqli_stmt_bind_param($stmt, "ss", $email, $role);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if (!$user) {
            $error = "Invalid email/password or not an event organiser account.";
        } else {
            // If your DB stores hashed passwords:
            $ok = password_verify($password, $user["password"]);

            // If your DB stores plain text passwords (NOT recommended), use this instead:
            // $ok = ($password === $user["password"]);

            if (!$ok) {
                $error = "Invalid email or password.";
            } else {
                // Success
                $_SESSION["userID"] = $user["userID"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];

                // organiser dashboard
                header("Location: dashboard.php");
                exit;
            }
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Event Organiser Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:Arial,sans-serif;background:#f6f7fb;margin:0}
    .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
    .card{width:100%;max-width:420px;background:#fff;border-radius:14px;box-shadow:0 10px 30px rgba(0,0,0,.08);padding:22px}
    h1{margin:0 0 10px;font-size:20px}
    label{display:block;margin:12px 0 6px;font-size:13px}
    input{width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:10px;font-size:14px}
    button{width:100%;margin-top:16px;padding:11px 12px;border:0;border-radius:10px;background:#111;color:#fff;font-weight:600;cursor:pointer}
    .error{background:#ffe9e9;border:1px solid #ffb3b3;padding:10px 12px;border-radius:10px;color:#9a1b1b;font-size:14px;margin-bottom:12px}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h1>Login (Event Organiser)</h1>

      <?php if ($error !== ""): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="login">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
