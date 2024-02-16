<?php
session_start();
include "../db_conn.php";
if (!isset($_SESSION['USER_ID'])) {
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
                  $sql = "SELECT notif_message, notif_datetime FROM notification";
                  $result = $conn->query($sql);
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $notification_text = $row["notif_message"];
                      $notification_date = $row["notif_datetime"];
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
                        $id = $_SESSION['USER_ID']; 
                        $sql_user = "SELECT u.*, l.designation, l.position 
                        FROM user u 
                        INNER JOIN librarian l ON u.USER_ID = l.librarian_id 
                        WHERE u.USER_ID = ?";
                        $stmt_user = $conn->prepare($sql_user);
                        $stmt_user->bind_param("i", $id);
                        $stmt_user->execute();
                        $result_user = $stmt_user->get_result();
                        $user_data = $result_user->fetch_assoc();
                        $stmt_user->close();
                        ?>
                      <?php
                      include "../db_conn.php";
                      $sql = "SELECT username, PROFILE_pic FROM user WHERE USER_ID = ?";
                      $stmt = $conn->prepare($sql);
                      $id = $_SESSION['USER_ID']; 
                      $stmt->bind_param("i", $id);
                      $stmt->execute();
                      $stmt->bind_result($user_name, $pic);
                      $stmt->fetch();
                      $stmt->close();
                      $conn->close();
                      ?>
                      <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      <?php
                        if (!empty($user_data['PROFILE_PIC'])) {
                            echo '<img src="' . $user_data['PROFILE_PIC'] . '" alt="User Image" class="user-image" style="width: 50px; height: 50px; border-radius: 50%;">';
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

            <div class="content-feed col-md-8 col-lg-8">
                <div class="row align-items-center mb-2">
                    <div class="col-12">
                        <h1 class="d-flex align-items-center">DASHBOARD</h1>
                    </div>
                </div>

                <div class="container">
                    <div class="row justify-content-evenly">
                      <div class="col-lg-4 col-md-6">
                        <div class="card3">Users<br>
                        <?php
                          include "../db_conn.php";
                        $sql = "SELECT * from user";
                        if ($result = mysqli_query($conn, $sql)) {
                          $rowcount = mysqli_num_rows( $result );
                          printf($rowcount);
                        }?>
                        <img src="../images/user.png" alt="User Image" class="user-image"></div>
                      </div>
                  
                      <div class="col-lg-4 col-md-6">
                        <div class="card3">Clubs<br>
                        <?php
                           include "../db_conn.php";
                           $sql = "SELECT * from clubs";
                           if ($result = mysqli_query($conn, $sql)) {
                             $rowcount = mysqli_num_rows( $result );
                             printf($rowcount);
                           }?>
                        <img src="../images/clubs.png" alt="User Image" class="user-image"></div></div>
                  
                      <div class="col-lg-4 col-md-6">
                        <div class="card3">Upcoming Events<br>
                        <?php
                         include "../db_conn.php";
                         $id = $_SESSION['id'];
                         $sql_user = "SELECT username FROM user WHERE user_id = ?";
                         $stmt_user = $conn->prepare($sql_user);
                         $stmt_user->bind_param("i", $id);
                         $stmt_user->execute();
                         $stmt_user->bind_result($username);
                         $stmt_user->fetch();
                         $stmt_user->close();
                         $current_date = date('Y-m-d'); 
                         $sql_events = "SELECT COUNT(*) AS event_count FROM events WHERE event_startdate > ?";
                         $stmt_events = $conn->prepare($sql_events);
                         $stmt_events->bind_param("s", $current_date);
                         $stmt_events->execute();
                         $stmt_events->bind_result($event_count);
                         
                         $stmt_events->fetch();
                         printf($event_count);
                         $stmt_events->close();
                         $conn->close();?>
                        <img src="../images/upcoming_events.png" alt="User Image" class="user-image"></div>
                        </div>
                        
                      </div>
                      
                    </div>
                    <div class="card  mt-5">
                    <div class="card-body">
                        <table class="table mt-3">
                        <?php
                        include "../db_conn.php";
                        $sql_user = "SELECT COUNT(*) AS user_count FROM user";
                        $stmt_user = $conn->prepare($sql_user);
                        $stmt_user->execute();
                        $stmt_user->bind_result($user_count);
                        $stmt_user->fetch();
                        $stmt_user->close();
                        $sql_clubs = "SELECT COUNT(*) AS club_count FROM club_membership";
                        $stmt_clubs = $conn->prepare($sql_clubs);
                        $stmt_clubs->execute();
                        $stmt_clubs->bind_result($club_count);
                        $stmt_clubs->fetch();
                        $stmt_clubs->close();
                        $current_date = date('Y-m-d'); 
                        $sql_events = "SELECT COUNT(*) AS event_count FROM events WHERE event_startdate > ?";
                        $stmt_events = $conn->prepare($sql_events);
                        $stmt_events->bind_param("s", $current_date);
                        $stmt_events->execute();
                        $stmt_events->bind_result($event_count);
                        $stmt_events->fetch();
                        $stmt_events->close();
                        $sql_event_reg = "SELECT COUNT(*) AS event_reg_count FROM event_registration";
                        $stmt_event_reg = $conn->prepare($sql_event_reg);
                        $stmt_event_reg->execute();
                        $stmt_event_reg->bind_result($event_reg_count);
                        $stmt_event_reg->fetch();
                        $stmt_event_reg->close();
                        $conn->close();
                        ?>
                        <div class="card  mt-5">
                          <div class="card-body">
                            <h5 class="card-title">Daily Activities</h5>
                            <canvas id="userChart"></canvas>
                          </div>
                        </div>
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                            var ctx = document.getElementById('userChart').getContext('2d');
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ['User', 'Club Registration', 'Upcoming Events', 'Event Registration'],
                                    datasets: [{
                                        label: '',
                                        data: [<?php echo $user_count; ?>, <?php echo $club_count; ?>, <?php echo $event_count; ?>, <?php echo $event_reg_count; ?>],
                                        backgroundColor: [
                                            'rgba(255, 99, 132, 0.2)',
                                            'rgba(54, 162, 235, 0.2)',
                                            'rgba(255, 206, 86, 0.2)',
                                            'rgba(75, 192, 192, 0.2)',
                                        ],
                                        borderColor: [
                                            'rgba(255, 99, 132, 1)',
                                            'rgba(54, 162, 235, 1)',
                                            'rgba(255, 206, 86, 1)',
                                            'rgba(75, 192, 192, 1)',
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        </script>
                        </table>
                    </div>
                </div>
                  </div>
        </div>
      </div>
    </div>
  </div>

 

    <script src="../../vendor/bootstrap-5.0.2/js/bootstrap.bundle.min.js"></script>

    
</body>
</html>
