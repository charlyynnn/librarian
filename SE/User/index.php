<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../vendor/bootstrap-5.0.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your custome css goes here -->
    <link rel="stylesheet" href="css/stylesheet.css">
    <link rel="icon" href="images/zc_lib_seal.png" type="image/x-icon">
    <title>Librarian Login</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light sticky-top navigation">
        <div class="container-fluid">
          <a class="navbar-brand" href="index.php" id="logo-style">
            <img src="./images/zc_lib_seal.png" alt="Logo Text" id="seal">
            <!-- Add your logo text below this line -->
            <span class="logo-text">Zamboanga City Library</span>
          </a></nav>
    <div class="main signup-container vh-100 py-5">
        <div class="row justify-content-evenly h-100">
            <div class="col-md-6 bg-theme-dblue">
                <h1>Read,<br>Discover,<br>Learn More.</h1>
                </div>
                <div class="col-md-6 bg-theme-whitest d-flex align-items-center justify-content-center">
                    <div class="text-center">
                        <h1 class="font-weight-bold mb-12">Librarian</h1>
                        <?php if (isset($_GET['error'])) { ?>
                          <p class="error"><?php echo $_GET['error']; ?></p>
                          <?php } ?>
                        <form action="login.php" method="post">
                            <div class="row mx-2"> <img src="./images/username.png" alt="Logo Text" id="seal">Username
                                <div class="form__group field col-6 mb-3" >
                                    <input type="text" class="form__field" name="uname" placeholder="Username">
                                    
                                </div>
                            </div>

                            <div class="row mx-2"> <img src="./images/password.png" alt="Logo Text" id="seal">Password
                                <!-- Password Input -->
                                <div class="form__group field col-6 mb-3">
                                    <input type="password" placeholder="Username"  name="password" class="form__field">
                                   
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Log in</button>
                                </div>
                            </div>
                        </form>
                       
                    <div>
                    
</div>
                    </div>
                </div>
                
        </div>
    </div>
    <script src="../vendor/bootstrap-5.0.2/js/bootstrap.min.js"></script>
</body>
</html>