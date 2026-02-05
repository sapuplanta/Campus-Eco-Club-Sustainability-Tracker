<?php
session_start();
require_once __DIR__ . "/../config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = "event_organizer"; 

    if ($email === "" || $password === "") {
        $error = "Please enter email and password.";
    } else {
        $sql = "SELECT userID, email, password, role FROM users WHERE email=? AND role=? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $role);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if (!$user) {
            $error = "Invalid email/password or not an event organiser account.";
        } else {
            // If hashed passwords:
            $ok = password_verify($password, $user["password"]);

            // If plain text passwords (temporary):
            // $ok = ($password === $user["password"]);

            if (!$ok) {
                $error = "Invalid email or password.";
            } else {
                $_SESSION["userID"] = $user["userID"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"]  = $user["role"];

                header("Location: ../event_organiser/dashboard.php");
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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Event Organiser Login</title>
</head>
<body>
  <h2>Login (Event Organiser)</h2>

  <?php if ($error !== ""): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>

  <form method="POST" action="">
    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit" name="login">Login</button>
  </form>
</body>
</html>
