<?php
// Initialize the session
    session_start();

    // logout code
    include("php/logout.php");
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
            
            <div class="rounded pl-md-3 pr-md-3 bg-white mt-md-4 mb-md-4">

                <div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center mt-auto">
                    <h1 class="display-4">Welcome to The New News</h1>

                    <p class="lead">Keep up to date with the latest in the world news</p>
                    <a href="article-list.php"><button type="button" class="btn btn-lg btn-block btn-outline-primary">View Articles</button></a>
                </div>
                <div class="card mb-md-4">
                    <div class="card-header">
                        <h1 class="card-title">Join the New News</h1>
                    </div>
                    <div class=""></div>
                    <div class="card-body">
                        <div class="row text-center">
                            <ul class="w-100 list-unstyled mt-3 mb-4">
                                <li>Create Your Own Articles</li>
                                <li>Edit or Remove Your Articles</li>
                            </ul>
                            <div class="col-md-6">
                                <a href="member-login.php"><button type="button" class="btn btn-lg btn-block btn-outline-primary">Log In</button></a>
                            </div>
                            <div class="col-md-6">
                                <a href="member-signup.php"><button type="button" class="btn btn-lg btn-block btn-outline-primary">Sign Up</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include("php/footer.php"); ?>
    </body>
</html>