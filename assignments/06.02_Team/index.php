<?php

// protect internal pages from being viewed, this gains access to those pages.
define('CS313', true);

session_start();

if(isset($_SESSION['username']))
{
    // connect the db
    require_once('dbconnect.php');
    
    echo 'user logged in';

            echo '<pre>$_SESSION: ' . print_r($_SESSION, true) . '</pre>';
    
    ?>
<a href="logout.php">Logout</a>
<?php

    // If a user is found, display, “Welcome [username]” (where [username] is the current user’s username.)

} else {
    // If no user is logged in, redirect to the sign-in page 
    header( 'Location: login.php' ) ;

}
