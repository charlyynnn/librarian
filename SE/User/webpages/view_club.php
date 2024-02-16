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
    <!-- Your custome css goes here -->
    <link rel="stylesheet" href="../css/stylesheet.css">
    <link rel="icon" href="../images/zc_lib_seal.png" type="image/x-icon">

    <title>Profile</title>
    
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light sticky-top navigation">
        <div class="container-fluid">
          <a class="navbar-brand" href="../webpages/dashboard.php" id="logo-style">
            <img src="../images/zc_lib_seal.png" alt="Logo Text" id="seal">
            <!-- Add your logo text below this line -->
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

    <div class="main">
      <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-transparent sidebar sticky-sidebar">
                <div class="position-sticky sticky-nav">
                  <ul class="nav flex-column">
                    <li class="nav-item" id="nav-main">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item" id="nav-main">
                        <a class="nav-link" href="clubs.php">Your Clubs</a>
                    </li>
                    <li class="nav-item" id="nav-main">
                        <a class="nav-link" href="events.php">Events</a>
                    </li>
                    <li class="nav-item" id="nav-main">
                        <a class="nav-link" href="settings.php">Settings</a>
                    </li>
                </ul>
                </div>
            </nav>

            <div class="content-feed col-md-9 col-lg-9">
    <div class="container-fluid">
        <h2 class="h2 brand-color pt-3 pb-2">Friends of the Library</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Role</th>
                    <th scope="col">Email address</th>
                    <th scope="col">Contact no</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include "../db_conn.php";

                $sql = "SELECT c.club_id, CONCAT(u.user_firstname, ' ', u.user_middlename, ' ', u.user_lastname) AS name, 
                                c.club_manager, u.email, u.contactno, u.user_gender
                        FROM clubs c
                        
                        JOIN user u ON c.club_id = u.id
                        WHERE c.club_name = 'Friends of the Library'
                        ORDER BY c.club_manager DESC, c.club_id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $count = 1;
                    while ($row = $result->fetch_assoc()) {
                        $club_id = $row["club_id"];
                        $name = $row["name"];
                        $role = $row["club_manager"] == 1 ? "Club Manager" : "Member";
                        $email = $row["email"];
                        $contact = $row["contactno"];
                        $gender = $row["user_gender"];

                        echo "<tr>";
                        echo "<th scope='row'>" . $count . "</th>";
                        echo "<td>" . $name . "</td>";
                        echo "<td>" . $role . "</td>";
                        echo "<td>" . $email . "</td>";
                        echo "<td>" . $contact . "</td>";
                        echo "<td>" . $gender . "</td>";
                        echo "<td>";
                        echo "<a href='view_club.php?id=$club_id' class='btn btn-primary'>View</a>";
                        echo "</td>";
                        echo "</tr>";

                        $count++;
                    }
                } else {
                    echo "<tr><td colspan='7'>No members found.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>
        
</body>
</html>