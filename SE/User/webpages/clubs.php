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
    <title>Clubs</title>
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
                      <li class="nav-item" id="nav-main"><a class="nav-link active-bold" href="dashboard.php">Dashboard</a></li>
                      <li class="nav-item" id="nav-main"><a class="nav-link active-bold" href="clubs.php">Your Clubs</a></li>
                      <li class="nav-item" id="nav-main"><a class="nav-link active-bold" href="events.php">Events</a></li>
                      <li class="nav-item" id="nav-main"><a class="nav-link active-bold" href="settings.php">Settings</a></li>
                    </ul>
                </div>
            </nav>

            <div class="content-feed col-md-9 col-lg-9">
    <div class="container">
        <h2 class="h2 brand-color pt-3 pb-2">Clubs</h2>
        <div class="row">
            <div class="row">
                <div class="col-md-1">
                    <a href="application.php" class="btn btn-primary mb-1" style="width: 120px;">Application</a>
                </div>
                <form method="get">
                    <div class="input-group mb-3">
                        <div class="col-md-2 offset-md-5">
                            <select id="sort" class="form-select mb-1" name="sort" onchange="this.form.submit()">
                                <option value="def" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'def') echo 'selected'; ?>>Default</option>
                                <option value="asc" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'asc') echo 'selected'; ?>>Ascending</option>
                                <option value="desc" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'desc') echo 'selected'; ?>>Descending</option>
                            </select>
                        </div>
                        <div class="col-md-4" style="margin-left: 30px;width: 300px;">
                            <input type="text" class="form-control rounded-pill" placeholder="Search" name="search" value="<?php if(isset($_GET['search'])) echo $_GET['search']; ?>" oninput="this.form.submit()">
                        </div>
                    </div>
                </form>
                <table class="table">
    <thead>
        <tr>
            <th scope="col">Club Name</th>
            <th scope="col">Club Manager</th>
            <th scope="col">Age</th>
            <th scope="col">Members</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'def';
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $sql_sort = '';
        $sql_search = '';

        if($sort != 'def'){
            $sql_sort = 'ORDER BY c.club_name ' . ($sort == 'asc' ? 'ASC' : 'DESC');
        }

        if(!empty($search)){
            $sql_search = "WHERE c.club_name LIKE '%$search%' OR c.club_manager LIKE '%$search%'  OR c.club_agelimit LIKE '%$search%' OR u.user_firstname LIKE '%$search%' OR u.user_lastname LIKE '%$search%'";
        }

        $sql = "SELECT c.club_id, c.club_name, c.club_manager, u.user_firstname, u.user_lastname, c.club_agelimit, COUNT(c.club_member) AS members
                FROM clubs c

                JOIN user u ON c.club_member = u.id
                $sql_search
                GROUP BY c.club_id
                $sql_sort";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr class='table-row' onclick=\"location.href='view_club.php?id=" . $row["club_id"] . "';\" style=\"cursor:pointer;\">";
                echo "<td>" . $row["club_name"] . "</td>";
                echo "<td>" . $row["club_manager"] . "</td>";
                echo "<td>" . $row["club_agelimit"] . "</td>";
                echo "<td>" . $row["user_firstname"] . " " . $row["user_lastname"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No clubs found.</td></tr>";
        }
        ?>
    </tbody>
</table>

        </div>
    </div>
        
<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>
        
</body>
</html>