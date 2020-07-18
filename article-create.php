<?php
    // Initialize the session
	session_start();
    
    include("php/connection.php");

    $articleCreated = false;

    // If there is no user logged in - redirect to login page
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == false){
        header("Location: member-login.php");
    }
    

    // Handles button presses from the page
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        // creates relevent variables based on form input
        $errorMessage = "";
        $authorName = $_SESSION['username'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $date = date("Y-m-d");  // todays date


        // Validation checks before creating the article
        if (strlen($title) == 0){           // If no title has been entered then set the error message
            $errorMessage = "The article must contain a title! Try Again";
        }
        else if (strlen($content) == 0){    // If no content has been entered then set the error message
            $errorMessage = "The article must contain content! Try Again";
        }
        else {                              // Else move onto article creation

            // Create and run the sql code to check for any articles that may already have the title
            $sql = "SELECT * FROM  `tblarticles` WHERE title='$title'";
            $results = mysqli_query($conn, $sql);

            // If there are articles with the given title 
            if (mysqli_num_rows($results) > 0) {
                // set error message and don't create the article
                $errorMessage = "Article title taken! Try Again";	
            }else{  // Else create the article
                // Prepare sql statement
                $stmt = $conn->prepare("INSERT INTO tblarticles (author, title, content, publishDate) VALUES ('$authorName', '$title', '$content', '$date')");
                $stmt->execute();   // execute the sql code

                $stmt->close();     // close the statement
                $conn->close();     // close the connection

                // set boolean to true so the relevent popup can show for successful article creation.
                $articleCreated = true;
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

        <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

        <link href="https://fonts.googleapis.com/css?family=Courgette&display=swap" rel="stylesheet">
    </head>

    <body>
        <!-- Navigation Bar -->
        <?php include("php/navbar.php"); ?>

        <!-- Main Content -->

        <?php
            // check if the article has been created
            if ($articleCreated){
                // Display successful creation box
                echo "
                    <div class='container alert alert-warning'>
                        <h4> Article created successfully!</h4>
                        <h6> Click <a href='article-list.php'>here</a> to view</h6>
                    </div>
                ";
            }
        ?>

        <div class="container">
            <h1>New Article</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="article-create">
                <label for="float-left">Article Title</label>
                <a class="float-right" href="article.php">Back</a>
                
                
                <input name="id" type="hidden" id="">

                <input name="title" type="text" class="form-control" id="articleTitle" name="title">

                <label for="alterEgo">Article Content</label>
                


                <textarea name="content" type="text" class="form-control" id="articleContent" name="content"></textarea> 
                <!-- Replaces basic textarea with more advanced text editor hosted on AWS -->
                <script>
                        CKEDITOR.replace( 'content' );
                </script>


            
                <?php 
                // Check if there is an error message
                if ($errorMessage != ""){
                    // Display the error message to the user
                    echo "
                    <div class='mt-2 alert alert-dark'>
                        <h4>";
                         echo $errorMessage;
                    echo "</h4>
                    </div>";
                }
                ?>
                <button type="submit" name="submit" class="mt-2 btn btn-secondary">Submit</button>

            </form>
        </div>

        <!-- Footer -->
        <?php include("php/footer.php"); ?>
    </body>
</html>