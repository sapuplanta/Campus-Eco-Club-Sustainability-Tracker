<?php
session_start();

if (!isset($_SESSION["event_organiser"]) || empty($_SESSION["event_organiser"])) {
    header("Location: ../auth/login.php");
    exit;
}
