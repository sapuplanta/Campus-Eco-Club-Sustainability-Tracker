<?php
session_start();

if (!isset($_SESSION["userID"]) || !isset($_SESSION["role"])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION["role"] !== "event_organizer") {
    // logged in, but wrong role
    header("Location: ../auth/login.php");
    exit;
}
?>