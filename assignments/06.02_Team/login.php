<?php

// protect internal pages from being viewed, this gains access to those pages.
define('CS313', true);

session_start();

if(isset($_SESSION['username']))
{
    header( 'Location: index.php' ) ;
} else {

    if (isset($_POST['login']))
    {

        // clear old error
        unset($_SESSION['error']);

        // connect the db
        require_once('dbconnect.php');
        require_once('password.php');

        $un = $_POST['un'];
        $up = $_POST['up'];


        // validate the login against the database

        $sql = "Select id, username, password from user where username = :un";
        $q = $db->prepare($sql);
        $q->execute(array(':un'=>$un ));
        $row = $q->fetch();

        if(isset($row['id']))
        {
            if (password_verify($up, $row['password'])) {
                /* Valid */
                $_SESSION['username'] = $row['username'];
                header( 'Location: index.php' ) ;
            } else {
                /* Invalid */
                $_SESSION['error'] = "Your username and password could not be verified";
                header( 'Location: login.php' ) ;
            }

        } else {
            $_SESSION['error'] = "Your username and password could not be verified";
            header( 'Location: login.php' ) ;
        }

    } else {
        // display the login form
?>

<form id="login_form" method="post">
    <?php if (isset($_SESSION['error']) ) echo $_SESSION['error'] . '<br>'; ?>
    Username<br>
    <input type="text" name="un" id="un" required placeholder="Username" 

           <?php if (isset($_POST['un'])) echo 'value=\"' . $_POST['un'] . '"'; ?>

           ><br>

    Password<br>
    <input type="password" name="up" id="up" required placeholder=""

           <?php if (isset($_POST['up'])) echo 'value="' . $_POST['up'] . '"'; ?>

           ><br>
    <input type="submit" name="login" value="login">

    <a href="signup.php">Signup</a>

</form>

<?php
    }
    // user not logged in


}
