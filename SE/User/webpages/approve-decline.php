<?php
include "../db_conn.php";

if (isset($_POST['approve'])) {
    $clubmembership_id = $_POST['clubmembership_id'];
    $sql = "INSERT INTO clubs (club_member) VALUES ($clubmembership_id)";
    $result = $conn->query($sql);

    if ($result) {
        $sql = "DELETE FROM notification WHERE notification_id = $clubmembership_id";
        $result = $conn->query($sql);

        if ($result) {
           
            $sql = "DELETE FROM club_membership WHERE clubmembership_id = $clubmembership_id";
            $result = $conn->query($sql);

            if ($result) {
                echo '<script>alert("Application approved!");</script>';
                echo '<script>document.querySelector("#approve-decline-buttons-' . $clubmembership_id . ' .btn-success").innerHTML = "Approved";</script>';
                echo '<script>document.querySelector("#approve-decline-buttons-' . $clubmembership_id . ' .btn-success").disabled = true;</script>';
                echo '<script>document.querySelector("#approve-decline-buttons-' . $clubmembership_id . ' .btn-danger").innerHTML = "Declined";</script>';
                echo '<script>document.querySelector("#approve-decline-buttons-' . $clubmembership_id . ' .btn-danger").disabled = true;</script>';
            } else {
                echo '<script>alert("Error approving application!");</script>';
            }
        } else {
            echo '<script>alert("Error approving application!");</script>';
        }
    } else {
        echo '<script>alert("Error approving application!");</script>';
    }header("Location: application.php");
    exit();
}

if (isset($_POST['decline'])) {
    $clubmembership_id = $_POST['clubmembership_id'];
    $sql = "DELETE FROM club_membership WHERE clubmembership_id = $clubmembership_id";
    $result = $conn->query($sql);

    if ($result) {
        $sql = "DELETE FROM notification WHERE notification_id = $clubmembership_id";
        $result = $conn->query($sql);

        if ($result) {
            echo '<script>alert("Application declined!");</script>';
            echo '<script>document.querySelector("#approve-decline-buttons-' . $clubmembership_id . ' .btn-success").innerHTML = "Approved";</script>';
            echo '<script>document.querySelector("#approve-decline-buttons-' . $clubmembership_id . ' .btn-success").disabled = true;</script>';
            echo '<script>document.querySelector("#approve-decline-buttons-' . $clubmembership_id . ' .btn-danger").innerHTML = "Declined";</script>';
            echo '<script>document.querySelector("#approve-decline-buttons-' . $clubmembership_id . ' .btn-danger").disabled = true;</script>';
        } else {
            echo '<script>alert("Error declining application!");</script>';
        }
    } else {
        echo '<script>alert("Error declining application!");</script>';
    }
    header("Location: application.php");
exit();
}

?>