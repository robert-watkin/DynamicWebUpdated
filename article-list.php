<?php
    // Initialise session
    session_start();

    // logout code
    include("php/logout.php");

    // If there is no user logged in - redirect to login page
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == false)
        header("Location: member-login.php");

    // Connect to database
    include("php/connection.php");

    
    
    // Retrieve articles
    $query = "SELECT * FROM `tblarticles`";
    $result = mysqli_query($conn, $query);
    

    // runs when post request is sent
    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        // checks which button has been pressed
        if (isset($_POST['submit'])){

            // gathers search criteria from user input
            // title
            $search_value = $_POST['Search'];

            // date
            $dateFrom = date('Y-m-d', strtotime($_POST['dateFrom']));
            $dateTo = date('Y-m-d', strtotime($_POST['dateTo']));

            // checks if date is at it's default value - user not searching date
            if ($dateFrom == "1970-01-01" ||
                $dateTo == "1970-01-01"){

                    // search by title
                    $query = "SELECT * FROM `tblarticles`WHERE title LIKE '%".$search_value."%'" ;
                    $result = mysqli_query($conn, $query);  
            }
            else {
                // search by title and date
                $query = "SELECT * FROM `tblarticles`WHERE title LIKE '%".$search_value."%' and publishDate BETWEEN '".$dateFrom."' and '".$dateTo. "'";
                $result = mysqli_query($conn, $query); 
            }
        } else if (isset($_POST['id'])){

            // view article button has been pressed
            // the articles ID is stored in the session
            // the article ID is gained from the button value
            $articleID = $_POST['id'];
            $_SESSION['articleID'] = $articleID;

            // opens the single article view page
            header("Location: article.php");
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
        <h1>Articles</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="search">
            <input class="width-50 mb-3" type="text" name="Search" placeholder="Search..">
            From : <input class="form-group" type='date' class='dateFilter' name='dateFrom' value='<?php if(isset($_POST['dateFrom'])) echo $_POST['dateFrom']; ?>'>
            To : <input class="form-group" type='date' class='dateFilter' name='dateTo' value='<?php if(isset($_POST['dateTo'])) echo $_POST['dateTo']; ?>'>
            <input id="submit" type="submit" name="submit" value="Search">
        </form>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_array($result)){
                echo "
                    <div class='card'>
                        <div class='card-header'>
                            <h3>";
                echo        $row["title"];
                echo "      </h3>
                            <p class='float-right'>";
                echo        $row["author"];
                echo        " - ";
                echo        $row["publishDate"];
                echo"       </p>
                        <form action='".$_SERVER['PHP_SELF']."' method='post' name='openArticle'>
                            <button class='btn btn-secondary' name='id' type='submit' value='".$row["id"]."'>View</button>
                        </form>
                        </div>";
                echo"</div>
                    <br>";
            }   
            echo "<br><br><br><br><br><br><br><br>";
        }
        else {
            echo "
        <div>
            <h1 class='display-3'>There are currently no articles</h1>
        </div>
        <h2 class='display-5 mb-6'>To create a new article, click <a href='article-create.php'>here</a></h2>
        ";
        }
                
        
        
        ?>
        </div>

        <!-- Footer -->
        <?php include("php/footer.php"); ?>
    </body>
</html>