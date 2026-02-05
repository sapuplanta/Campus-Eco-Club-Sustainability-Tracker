<?php
include("../Campus-Eco-Club-Sustainability-Tracker/config/db.php");

if (isset($_POST['assign'])) {
    $volunteer = $_POST['volunteer'];
    $task = $_POST['task'];
    mysqli_query($conn, "INSERT INTO tasks (task_name, volunteerID) VALUES ('$task', '$volunteer')");
    echo "Task assigned successfully.";
}
?>

<form method= "POST">
    Volunteer ID: <input type="text" name="volunteer" required><br>
    Task Name: <input type="text" name="task" required><br>
    <button name="assign">Assign Task</button>
</form>