<?php
$conn = mysqli_connect("localhost", "root", "", "eco_tracker");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>