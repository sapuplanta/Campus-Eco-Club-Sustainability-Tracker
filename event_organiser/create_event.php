<?php
include("../Campus-Eco-Club-Sustainability-Tracker/config/db.php");

if (isset($_POST['submit'])){
    $title = $_POST['title'];
    $date = $_POST['date'];
    $location = $_POST['location'];

    $query = "INSERT INTO events (title, date, location) VALUES ('$title', '$date', '$location')";
    mysqli_query($conn, $query);

    echo "Event Created Successfully";
}
?>

<form method="POST">
    Title: <input type="text" name="title" required><br>
    Date: <input type="date" name="date" required><br>
    Location: <input type="text" name="location" required><br>
    <button name="submit" type="submit">Create Event</button>
</form>