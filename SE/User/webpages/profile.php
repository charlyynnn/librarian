<?php
session_start();
include "../db_conn.php";
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../vendor/bootstrap-5.0.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/stylesheet.css">
    <link rel="icon" href="../images/zc_lib_seal.png" type="image/x-icon">

    <title>Profile</title>
    
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top navigation">
        <div class="container-fluid">
          <a class="navbar-brand" href="../webpages/dashboard.php" id="logo-style">
            <img src="../images/zc_lib_seal.png" alt="Logo Text" id="seal">
            <span class="logo-text">Zamboanga City Library</span>
          </a>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <div class="nav-item dropdown">
                <a class="nav-link" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span style="font-size: 1.5rem;">🔔</span> <!-- Bell icon using Unicode character -->
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                  <p class="mx-3 pt-2 custom-notification">Notifications</p>
                  <li><hr class="dropdown-divider"></li>
                  <?php 
                  include "../db_conn.php";
                  $sql = "SELECT NOTIF_MESSAGE, NOTIF_DATETIME FROM notification";
                  $result = $conn->query($sql);
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $notification_text = $row["NOTIF_MESSAGE"];
                      $notification_date = $row["NOTIF_DATETIME"];
                      ?>
                      <li>
                        <div class="dropdown-item">
                          <div class="d-flex justify-content-between">
                            <div>
                              <p class="mb-1"><?php echo $notification_text; ?></p>
                            </div>
                            <div class="text-end">
                              <br><small><?php echo $notification_date; ?></small>
                            </div>
                          </div>
                        </div>
                      </li>
                      <?php
                      }} else {
                        echo "<li><div class='dropdown-item'>No notifications found.</div></li>";
                      }
                      ?>
                      </ul>
                    </div>
                    <li class="nav-item dropdown d-flex align-items-center">
                      <?php
                      include "../db_conn.php";
                      $sql = "SELECT user_name, pic FROM user WHERE id = ?";
                      $stmt = $conn->prepare($sql);
                      $id = $_SESSION['id']; 
                      $stmt->bind_param("i", $id);
                      $stmt->execute();
                      $stmt->bind_result($user_name, $pic);
                      $stmt->fetch();
                      $stmt->close();
                      $conn->close();
                      ?>
                        <?php
                        include "../db_conn.php";
                        $id = $_SESSION['id']; 
                        $sql_user = "SELECT u.*, l.librarian_designation, l.position 
                        FROM user u 
                        INNER JOIN librarian l ON u.id = l.librarian_id 
                        WHERE u.id = ?";
                        $stmt_user = $conn->prepare($sql_user);
                        $stmt_user->bind_param("i", $id);
                        $stmt_user->execute();
                        $result_user = $stmt_user->get_result();
                        $user_data = $result_user->fetch_assoc();
                        $stmt_user->close();
                        ?>
                        <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                       <?php
                        if (!empty($user_data['pic'])) {
                            echo '<img src="' . $user_data['pic'] . '" alt="User Image" class="user-image" style="width: 50px; height: 50px; border-radius: 50%;">';
                        } else {
                            echo '<img src="" alt="User Image" class="user-image" style="width: 100px; height: 100px; border-radius: 50%;">';
                        }
                        ?><?php echo $user_name; ?></a>
                      
                      <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                          <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                          <li><a class="dropdown-item text-danger" href="../logout.php">Logout</a></li>
                      </ul>
                  </li>          
                    </ul>
                    </div>
                </div>
            </nav>  
            <body>
                <div class="container mt-5">
                    <div class="row mb-3">
                        <div class="col-sm-12 offset-sm-0 d-flex justify-content-between">
                            <div>
                                <a href="dashboard.php" class="btn btn-secondary">Back</a>
                            </div>
                            <div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-4"style="text-align:center;">
                    <?php
                    if (!empty($user_data['pic'])) {
                        echo '<img src="' . $user_data['pic'] . '" alt="User Image" class="user-image" style="width: 100px; height: 100px; border-radius: 50%;">';
                    } else {
                        echo '<img src="" alt="User Image" class="user-image" style="width: 100px; height: 100px; border-radius: 50%;">';
                    }
                    ?>
                <h4><?php echo $user_data['user_firstname'] . ' ' . $user_data['user_lastname']; ?></h4>
                <p><?php echo $user_data['position']; ?></p>
            </div>

            <div class="col-md-8">
                <div class="row mb-3">
                    <label for="username" class="col-sm-3 col-form-label">Username</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="username" name="username" readonly value="<?php echo $user_data['user_name']; ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" id="email" name="email" readonly value="<?php echo $user_data['email']; ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="contactNo" class="col-sm-3 col-form-label">Contact No</label>
                    <div class="col-sm-9">
                        <input type="tel" class="form-control" id="contactNo" name="contactNo" readonly value="<?php echo $user_data['contactno']; ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="birthdate" class="col-sm-3 col-form-label">Birth Date</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" id="birthdate" name="birthdate" readonly value="<?php echo $user_data['user_dateofbirth']; ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="gender" class="col-sm-3 col-form-label">Gender</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="gender" name="gender" readonly value="<?php echo $user_data['user_gender']; ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="address" class="col-sm-3 col-form-label">Address</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" id="address" name="address" rows="3" readonly><?php echo $user_data['user_street']. ', ' . $user_data['barangay']. ', ' . $user_data['province']. ', ' . $user_data['city']. ', ' . $user_data['zip_code']; ?></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="designation" class="col-sm-3 col-form-label">Designation</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="designation" name="designation" readonly value="<?php echo $user_data['librarian_designation']; ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="position" class="col-sm-3 col-form-label">Position</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="position" name="position" readonly value="<?php echo $user_data['position']; ?>">
                    </div>
                </div>

                <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <div class="row mb-3">
                                    <div class="col-md-9">
                                    <?php
                                    if (!empty($user_data['pic'])) {
                                        echo '<img src="' . $user_data['pic'] . '" alt="User Image" class="user-image" style="width: 100px; height: 100px; border-radius: 50%;">';
                                    } else {
                                        echo '<img src="" alt="User Image" class="user-image" style="width: 100px; height: 100px; border-radius: 50%;">';
                                    }
                                    ?>
                                    <input type="file" class="form-control mb-3" id="pic">
                                </div>
                        <div class="col-md-9">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="user_firstname" value="<?php echo $user_data['user_firstname']; ?>"required />
                                </div>
                                <div class="col-md-4">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="user_lastname" value="<?php echo $user_data['user_lastname']; ?>"required />
                                </div>
                                <div class="col-md-4">
                                    <label for="StreetName" class="form-label">Street Name</label>
                                    <input type="text" class="form-control" id="user_street" value="<?php echo $user_data['user_street']; ?>"required />
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="user_name" value="<?php echo $user_data['user_name']; ?>"required />
                                </div>
                                <div class="col-md-4">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="user_gender">
                                        <option value="Male" <?php if ($user_data['user_gender'] == "Male") echo "selected"; ?>>Male</option>
                                        <option value="Female" <?php if ($user_data['user_gender'] == "Female") echo "selected"; ?>>Female</option>
                                        </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="barangay" class="form-label">Barangay</label>
                                    <input type="text" class="form-control" id="barangay" value="<?php echo $user_data['barangay']; ?>"required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="<?php echo $user_data['email']; ?>"required />
                                </div>
                                <div class="col-md-4">
                                    <label for="oldPassword" class="form-label">Old Password</label>
                                    <input type="password" class="form-control" id="oldPassword"value="<?php echo $user_data['password']; ?>"required />
                                </div>
                                <div class="col-md-4">
                                    <label for="province" class="form-label">Province</label>
                                    <input type="text" class="form-control" id="province" value="<?php echo $user_data['province']; ?>"required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="contactNo" class="form-label">Contact No</label>
                                    <input type="tel" class="form-control" id="contactno" value="<?php echo $user_data['contactno']; ?>"required />
                                </div>
                                <div class="col-md-4">
                                    <label for="newPassword" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="newPassword"required />
                                </div>
                                <div class="col-md-4">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" value="<?php echo $user_data['city']; ?>"required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="birthdate" class="form-label">Birthdate</label>
                                    <input type="date" class="form-control" id="user_dateofbirth" value="<?php echo $user_data['user_dateofbirth']; ?>"required />
                                </div>
                                <div class="col-md-4">
                                    <label for="position" class="form-label">Position</label>
                                    <input type="text" class="form-control" id="position" value="<?php echo $user_data['position']; ?>"required />
                                </div>
                                <div class="col-md-4">
                                    <label for="zip_code" class="form-label">Zip Code</label>
                                    <input type="number" class="form-control" id="zip_code" value="<?php echo $user_data['zip_code']; ?>"required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary"id="saveChangesBtn">Save Changes</button>
            </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>
    
   
<script>
    $(document).ready(function() {
        $("#saveChangesBtn").click(function() {
            var formData = new FormData();
            formData.append('user_firstname', $("#user_firstname").val());
            formData.append('user_lastname', $("#user_lastname").val());
            formData.append('user_street', $("#user_street").val());
            formData.append('user_name', $("#user_name").val());
            formData.append('user_gender', $("#user_gender").val());
            formData.append('barangay', $("#barangay").val());
            formData.append('email', $("#email").val());
            formData.append('oldPassword', $("#oldPassword").val());
            formData.append('province', $("#province").val());
            formData.append('contactno', $("#contactno").val());
            formData.append('newPassword', $("#newPassword").val());
            formData.append('city', $("#city").val());
            formData.append('user_dateofbirth', $("#user_dateofbirth").val());
            formData.append('position', $("#position").val());
            formData.append('zip_code', $("#zip_code").val());
            var fileInput = $('#pic')[0].files[0];
            if (fileInput) {
                formData.append('pic', fileInput);
            }
            $.ajax({
                url: "update_profile.php",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log("Profile updated successfully");
                    $("#editProfileModal").modal("hide");
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error("Error updating profile:", error);
                }
            });
        });
    });
</script>
</body>
</html>