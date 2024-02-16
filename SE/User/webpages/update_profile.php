<?php
session_start();
include "../db_conn.php";
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_SESSION["id"]; 
    $user_firstname = $_POST["user_firstname"];
    $user_lastname = $_POST["user_lastname"];
    $user_street = $_POST["user_street"];
    $user_name = $_POST["user_name"];
    $user_gender = $_POST["user_gender"];
    $barangay = $_POST["barangay"];
    $email = $_POST["email"];
    $oldPassword = $_POST["oldPassword"];
    $province = $_POST["province"];
    $contactno = $_POST["contactno"];
    $newPassword = $_POST["newPassword"];
    $city = $_POST["city"];
    $user_dateofbirth = $_POST["user_dateofbirth"];
    $position = $_POST["position"];
    $zip_code = $_POST["zip_code"];

    if (isset($_FILES['pic']) && $_FILES['pic']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['pic']['tmp_name'];
        $file_name = $_FILES['pic']['name'];
        $file_size = $_FILES['pic']['size'];
        $file_type = $_FILES['pic']['type'];
        $uploads_dir = '../images/';
        $file_path = $uploads_dir . $file_name;
        move_uploaded_file($file_tmp_name, $file_path);
        $sql_update_picture = "UPDATE user SET pic = '$file_path' WHERE id = $id";
        mysqli_query($conn, $sql_update_picture);
    }

    $sql_user = "UPDATE user SET user_firstname='$user_firstname', user_lastname='$user_lastname', user_street='$user_street', user_name='$user_name', user_gender='$user_gender', barangay='$barangay', email='$email', province='$province', contactno='$contactno', password='$newPassword', city='$city', user_dateofbirth='$user_dateofbirth', zip_code='$zip_code' WHERE id = $id"; 
    $sql_librarian = "UPDATE librarian 
    SET position = '$position' 
    WHERE librarian_id = (SELECT librarian_id FROM user WHERE id = $id)";

    if (mysqli_query($conn, $sql_user) && mysqli_query($conn, $sql_librarian)) {
        echo json_encode(array("success" => true, "message" => "Profile updated successfully"));
    } else {
        echo json_encode(array("success" => false, "message" => "Error updating profile: " . mysqli_error($conn)));
    }
    mysqli_close($conn);
} else {
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}
?>
