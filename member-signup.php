<?php
    // Initialize the session
	session_start();
    
    include("php/connection.php");

    // check if logged in already - if so redirect
    if (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] == "true")){
        header("Location: index.php");
    }

    // handles button presses for the page
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        // Setup relevent variables from forms
        $errorMessage = "";
        $email = $_POST['Email'];
        $username = $_POST['Username'];
        $password = $_POST['Password'];
        $confirmPassword = $_POST['ConfirmPassword'];

        

        
        // Check the email field is not empty
        if (strlen($email) == 0){
            // Set relevant error message
            $errorMessage = "Email address field is empty! Try Again";
        }
        // Check the username field is not empty
        else if (strlen($username) == 0){
            // Set relevant error message
            $errorMessage = "Username field is empty! Try Again";
        }
        // Check the password field is not empty
        else if (strlen($password) == 0){
            // Set relevant error message
            $errorMessage = "Password field is empty! Try Again";
        }
        // Check both passwords match for confirmation
        else if ($password != $confirmPassword){
            // Set relevant error message
            $errorMessage = "Passwords do not match! Try Again";
        }
        else {
            // Setup sql code to find if there are already users with the given username
            $sql = "SELECT * FROM `tblusers` WHERE username='$username'";
            $results = mysqli_query($conn, $sql);

            // If there are accounts with the given username
            if (mysqli_num_rows($results) > 0) {
                // Set relevant error message
                $errorMessage = "Username taken! Try Again";	
            }else{
                // Encrypts the password
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                // Set member account type
                $accountType = "member";

                // Prepare sql code to create account
                $stmt = $conn->prepare("INSERT INTO tblusers (email, username, password, accountType) VALUES ('$email', '$username', '$hashedPassword', '$accountType')");
                $stmt->execute();   // execute the sql code

                $stmt->close();     // close the statement
                $conn->close();     // close the connection

                // redirect the the member login page
                header("Location: member-login.php");
                exit();
            }
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

        <!-- Main Content -->
        <div class="container">
            <div class="row" style="margin-top:40px; padding:20px; background-color:#eff0f2;border-radius:15px;
            -webkit-box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.2);
            -moz-box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.2);
            box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.2);">
            <!-- <div class="col-sm-12" style="text-align:center;">
                Register
            </div> -->

            <div class="col-sm-12">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="register">
                <h1 class="display-5">Member Register</h1>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input formControlName="email" type="email" class="form-control" id="email" aria-describedby="emailHelp" name="Email" placeholder="Enter email...">
                    <small id="emailHelp" class="form-text text-muted"></small>
                </div>
                <div class="form-group">
                    <label for="exampleInputUsername">Username</label>
                    <input formControlName="username" type="text" class="form-control" id="username" name="Username" placeholder="Enter username...">
                    <small id="userHelp" class="form-text text-muted"></small>
                </div>
                <div class="form-group">
                    <label for="passwordInput">Password</label>
                    <input formControlName="password" type="password" class="form-control" id="password" aria-describedby="passHelp" name="Password" placeholder="Enter password...">
                    <small id="passHelp" class="form-text text-muted"></small>
                </div>
                <div class="form-group">
                    <label for="CpasswordInput">Confirm Password</label>
                    <input formControlName="cpass" type="password" class="form-control" id="Cpassword" aria-describedby="CpassHelp" name="ConfirmPassword" placeholder="Enter password again...">
                    <small id="CpassHelp" class="form-text text-muted"></small>
                    </div>
                <div class="form-group" style="text-align:center;">
                    <?php 
                    if ($errorMessage != ""){
                        echo "
                        <div class='mt-2 alert alert-dark'>
                            <h4>";
                            echo $errorMessage;
                        echo "</h4>
                        </div>";
                    }
                    ?>
                    <input class="btn btn-dark mr-2" name="submit" type="submit" value="Register" />
                    <a href="member-login.php"><button type="button" class="btn btn-secondary ml-2">Log in</button></a>
                </div>
                </form>
            </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include("php/footer.php"); ?>
    </body>
</html>