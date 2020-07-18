<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand text-dark" href="#" id="title">The New News</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse sticky-top navbar-collapse width-100" id="navbarSupportedContent">
    <ul class="navbar-nav">
        <li class="nav-item w-sm-100 active">
        <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item w-sm-100">
        <a class="nav-link" href="article-list.php">Articles</a>
        </li>
        <?php
        if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]){
            echo "
            <li class='nav-item w-sm-100'>
                <a class='nav-link' href='member-signup.php'>Create Account</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='member-login.php'>Login</a>
            </li>
            ";
        } else {
            if ($_SESSION["accountType"] == "journalist"){
                echo "
                <li class='nav-item'>
                    <a class='nav-link' href='article-create.php'>Create Article</a>
                </li>
                ";
            }
            echo "
            <form method='post'>
                <li class=' mt-1'>
                    <input class'nav-link' type='submit' name='Logout' value='logout' onclick='logout()' />
                </li>
            </form>
            <li>
            <p class='mt-2 ml-2 float-right'>Logged in as - "; 
            echo $_SESSION["username"];
            if ($_SESSION["accountType"] == "journalist"){
                echo " (JOURNALIST ACCOUNT) ";
            }else if ($_SESSION["accountType"] == "editor"){
                echo " (EDITOR ACCOUNT) ";
            }
            echo "</p>
            </li>";  
        }
        ?>
    </ul>
    </div>
</nav>