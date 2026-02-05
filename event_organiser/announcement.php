<?php
include("../Campus-Eco-Club-Sustainability-Tracker/config/db.php");

if (isset($_POST['post'])) {
    $message = $_POST ['message'];
    mysqli_query($conn, "INSERT INTO announcements (message) VALUES ('$message')");
    echo "Announcement posted successfully.";

}
?>

<form method ="POST">
    <textarea name="message" placeholder="Enter announcement here..." required></textarea><br>
    <input type="submit" name="post" value="Post Announcement">
</form>
