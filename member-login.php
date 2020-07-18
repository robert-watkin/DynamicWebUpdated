<?php
    // Initialize the session
	session_start();
    
    include("php/connection.php");

    // check if logged in already - if so redirect
    if (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] == "true"))
        header("Location: index.php");

    // login code
    $errorMessage = "";

    // Handles button presses for the site
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        // Sets up relevent variables
        $email = $_POST['Email'];
        $password = $_POST['Password'];

        // create sql code to search for users with the given email
        $query = "SELECT * FROM `tblusers` WHERE email='$email'";
        $result = mysqli_query($conn, $query);

        // verify password if accounts with email have been found
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_array($result)){
                if (password_verify($password, $row["password"])){  // checks against encrypted passwords

                    // SUCCESSFUL LOGIN
                    // Set all session variables
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $row["id"];
                    $_SESSION["username"] = $row["username"];
                    $_SESSION["accountType"] = $row["accountType"]; 

                    // Redirect to the home page
                    header("Location: index.php");
                }
            }   
        }
        else {
            // Display incrorrect login error message
            $errorMessage = "Email or Password is incorrect! Please try again.";
        }
    }

?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Newssite</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="favicon.ico">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="stylesheets/mainStylesheet.css">
        
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

        <link href="https://fonts.googleapis.com/css?family=Courgette&display=swap" rel="stylesheet">
    </head>

    <body>
        <!-- Navigation Bar -->
        <?php include("php/navbar.php"); ?>

        <br>

        <!-- Main Content -->
        <div class="container">
            <div class="row" style="margin-top:40px; padding:20px; background-color:#eff0f2;border-radius:15px;
            -webkit-box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.2);
            -moz-box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.2);
            box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.2);">

            <div class="col-sm-12">
                <h1 class="display-5">Member Login</h1>
                <form <?php echo $_SERVER['PHP_SELF']; ?> method="post" name="login">
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input formControlName="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email..." name="Email">
                    <small id="emailHelp" class="form-text text-muted"></small>
                </div>
                <div class="form-group">
                    <label for="passwordInput">Password</label>
                    <input formControlName="password" type="password" class="form-control" id="passwordInput" aria-describedby="passHelp" placeholder="Enter password..." name="Password">
                    <small id="passHelp" class="form-text text-muted"></small>
                </div>

                <div class="form-group" style="text-align:center;">
                    
                    <?php echo "<p>$errorMessage</p>"; ?>
                    <input class="btn btn-dark mr-2" name="submit" type="submit" value="Login" />
                    <button type="button" class="btn btn-secondary ml-2" [routerLink]="['/sign_up']">Register</button>
                </div>
                </form>
            </div>
            </div>
        </div>

        <br><br><br>

        <!-- Footer -->
        <?php include("php/footer.php"); ?>
    </body>
</html>