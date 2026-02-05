<?php
session_start();
include ("../Campus-Eco-Club-Sustainability-Tracker/config/db.php");

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email' AND role='event_organizer'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['event_organiser'] = $email;
        header("Location: dashboard.php");
    } else {
        echo "Invalid login.";
    }
}
?>

<form methods="POST">
    Email: <input type="email" name="email" required><br>
    <button name="login">Login</button>
</form>