<?php
include("../Campus-Eco-Club-Sustainability-Tracker/config/db.php");

if(isset($_GET['approve'])) {
    $id= $_GET['approve'];
    mysqli_query($conn, "UPDATE registrations SET status='Approved' WHERE registrationID=$id");

}

if (isset($_GET['reject'])) {
    $id = $_GET['reject'];
    mysqli_query($conn, "UPDATE registrations SET status='Rejected' WHERE registrationID=$id");
}

$result = mysqli_query($conn, "SELECT * FROM registrations");
?>

<h3>Manage Event Registrations</h3r
 <p>
    Registration ID: <?= $row['registrationID']; ?><br>
    Status: <?= $row['status']; ?><br>
    <a href="?approve=<?= $row['registrationID']; ?>">Approve</a> |
    <a href="?reject=<?= $row['registrationID']; ?>">Reject</a>
</p>

<?php } ?>