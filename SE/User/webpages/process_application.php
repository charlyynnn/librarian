<?php
session_start();
include "../db_conn.php";
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $application_id = $_POST['application_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        // Add the approved application to the club
        $query = "INSERT INTO clubs (club_member) VALUES (:id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $application_id);
        $stmt->execute();

        // Remove the approved application from club_membership
        $query = "DELETE FROM club_membership WHERE clubmembership_id = :application_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':application_id', $application_id);
        $stmt->execute();
    } elseif ($action == 'decline') {
        // Remove the declined application from club_membership
        $query = "DELETE FROM club_membership WHERE clubmembership_id = :application_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':application_id', $application_id);
        $stmt->execute();
    }

    // Redirect back to the page where the application list is displayed
    header("Location: application.php");
    exit();
}
?>

