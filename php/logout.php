<?php
    // start the session
    if(array_key_exists('Logout', $_POST)) {
        logout();
    }
    function logout(){

        // Unset all of the session variables
        $_SESSION = array();

        // Destroy the session.
        session_destroy();

        // Redirect to login page
        header("location: index.php");
        exit;
    }
?>