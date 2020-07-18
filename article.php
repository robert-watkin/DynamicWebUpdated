<?php
    // Initialise session
    session_start();

    // Connect to database
    include("php/connection.php");

    // logout code
    include("php/logout.php");

    // If there is no user logged in - redirect to login page
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == false){
        header("Location: member-login.php");
    }
    else if (!isset($_SESSION["articleID"]) || $_SESSION["articleID"] == ""){
        header("Location: article-list.php");
    }

    
    $articleID = $_SESSION['articleID'];

    // Retrieve article
    $query = "SELECT * FROM `tblarticles` WHERE id='$articleID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);

    // Retrieve comments
    $query = "SELECT * FROM `tblcomments` WHERE articleID='$articleID'";
    $commentResults = mysqli_query($conn, $query);
    

    // Handles button presses from the page
    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        // Handle article deletion
        if (isset($_POST['delete'])) {
            
            // Prepare sql code to delete the article
            $sql = "DELETE FROM tblarticles WHERE id='".$_SESSION['articleID']."'";

            // Runs the sql code and check for errors
            if ($conn->query($sql) === TRUE) {
                // Removes the article ID form the session
                $_SESSION['articleID'] = "";
                // Redirects user to the article list page
                header("Location: article-list.php");

              } else {
                // Catches errors
                echo "Error deleting record: " . $conn->error;
              }
        }

        // Handle adding comments
        else if (isset($_POST["addComment"])){
            // Set comment variable
            $comment = $_POST["commentBox"];

            // Checks if the comment box was empty
            if (!isset($comment) || $comment == ""){
                // If empty then do not add the comment and display the error message
                $errorMessage = "Cannot add an empty comment! Try again";
            } else {
                // Set the date to today
                $date = date("Y-m-d");

                // Prepare sql statement
                $stmt = $conn->prepare("INSERT INTO tblcomments (username, comment, date, articleID) VALUES ('".$_SESSION['username']."', '$comment', '$date', '$articleID')");
                $stmt->execute();   // execute sql statement

                $stmt->close();     // close the statement
                $conn->close();     // close the connection

                // Redirect to the article page
                header("Location: article.php");
            }
        }

        // Handle comment deletion
        else if (isset($_POST["deleteComment"])){
            // Store the ID of the comment being deleted
            $commentID = $_POST["deleteComment"];
            // Prepare sql
            $sql = "DELETE FROM tblcomments WHERE id='$commentID'";

            // Run the sql and check for errors
            if ($conn->query($sql) === TRUE) {
                // redirect to the article page
                header("Location: article.php");
              } else {
                // Catch errors
                echo "Error deleting record: " . $conn->error;
              }
        }

        // Handle article editing
        else {
            // redirect to article edit page.
            header("Location: article-edit.php");
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
        <div class="mt-3 container">
            <div class='card'>
                <div class='card-header'>
                    <div class='float-left'>
                        <h1 ><?php echo $row["title"]; ?></h1>
                        <p >Author : <?php echo $row["author"]." - Published : ".$row["publishDate"];?> </p> 
                    </div>
                    <div class=' w-20 float-right'>
                        <?php
                        if ($_SESSION["accountType"] == "journalist" && $row["author"] == $_SESSION["username"] || $_SESSION["accountType"] == "editor"){
                            /*  Display the article edit/delet buttons if the user is an 
                                editor, or a journalist and they created the articles
                             */
                            echo "
                            <form class='float-right' action='".$_SERVER['PHP_SELF']."' method='post' name='edit'>
                                <button class='btn btn-secondary' name='edit' type='submit' value='edit'>Edit Article</button>
                                <button class='btn btn-danger' name='delete' type='submit' value='delete' onclick=\"return confirm('Are you sure?');\">Delete Article</button>
                            </form>";
                        }
                        ?>  
                    </div>
                </div>

                <div class='card-body'>
                    <p><?php echo $row["content"]?>
                </div>

                <div class="card-footer">
                    <h5>Comments</h5>
                    <?php
                        if (mysqli_num_rows($commentResults) > 0) {
                            // While loop to display all comments related to this article
                            while($row = mysqli_fetch_array($commentResults)){
                                // relevant html code to display the comments
                                echo "
                                <div class='card'>
                                    <div class='pb-0 card-header'>
                                        <div class='float-left'>
                                        <p class='mb-0'><b>User : ".$row['username']."</b></p>
                                        <p>Date ".$row['date']."</p>
                                        </div>";
                                    // If the comment belongs to the current user then display 
                                    // the comment deletion button
                                    if ($row["username"] == $_SESSION["username"] || $_SESSION["accountType"] == "editor"){
                                echo "
                                <form class='float-right' action='".$_SERVER['PHP_SELF']."' method='post' name='deleteComment'>
                                    <button class='float-right btn btn-danger' name='deleteComment' type='submit' value='".$row["id"]." onclick=\"return confirm('Are you sure?');\">Delete</button>
                                </form>";
                                }

                                echo"</div>
                                    <div class='card-body'>
                                        <p>".$row['comment']."</p>
                                    </div>
                                </div>
                                    ";
                            }
                        } else {
                            // If there are no comments display this message.
                            echo "<h6>This article has no comments - Add one below</h6>";
                        }
                    ?>

                    <hr>

                    <h6 class='mt-2'>Add a comment</h6>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="article-create">
                        <textarea rows="2" name="commentBox" type="text" class="form-control float-left" id="commentBox" placeholder="Your comment..."></textarea>
                        <button type="submit" class="float-right mt-2 btn btn-secondary" name="addComment">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include("php/footer.php"); ?>
    </body>
</html>