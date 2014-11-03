<?php

// protect internal pages from being viewed, this gains access to those pages.
define('CS313', true);

session_start();

if(isset($_SESSION['username']))
{
    header( 'Location: index.php' ) ;
} else {
    if(isset($_POST['signup']))
    {
        // clear previous error
        unset($_SESSION['error']);

        // connect the db
        require_once('dbconnect.php');
        require_once('password.php');

        $un = $_POST['un'];
        $up = $_POST['up'];

        // form submitted

        echo '<pre>$_POST: ' . print_r($_POST, true) . '</pre>';


        // validate the login against the database
        $sql = "Select id, username from user where username = :un";
        $q = $db->prepare($sql);
        $q->execute(array(':un'=>$un ));
        $row = $q->fetch();

        if(isset($row['id']))
        {
            $_SESSION['error'] = "Username taken. Please choose another";
            header( 'Location: signup.php' ) ;
            die;
        }

        $sql = "INSERT INTO user (username,password) VALUES (:un,:uph)";
        $insert_stmt = $db->prepare($sql);
        $insert_stmt->execute(array(':un'=>$un, ':uph'=>password_hash($up, PASSWORD_BCRYPT) ));

        $new_script_id = $db->lastInsertId();

        $_SESSION['username'] = $un;
        header( 'Location: index.php' ) ;

    } else {
        // display the form
?>

<form id="signup_form" method="post">
    <strong>Create New Account</strong><br>
    <?php if (isset($_SESSION['error']) ) echo $_SESSION['error'] . '<br>'; ?>
    Username<br>
    <input type="text" name="un" id="un" required placeholder="Username" 

           <?php if (isset($_POST['un'])) echo 'value=\"' . $_POST['un'] . '"'; ?>

           ><br>

    Password<br>
    <input type="password" name="up" id="up" required placeholder=""

           <?php if (isset($_POST['up'])) echo 'value="' . $_POST['up'] . '"'; ?>

           ><br>

    <input type="submit" name="signup" value="Create New Account">

    <a href="login.php">Login</a>

</form>

<?php

    }
}