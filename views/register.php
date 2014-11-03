<?php 

// check to see if there is a user to authenticate

global $input;

$path = '';

if (!defined('CS313'))
{
    // create test data
    $name = "test widget";
    $description = "This widget is a test widget and all it will do is demonistrate the details of this box.";
    $width = 360;
    $height = 360;
    $image = 'default_icon.png';
    $background_image = 'default_image.jpg';
    $html = 'Hello World';
    $javascript = '';
    $labels = json_encode(array('money', 'sauce'));
    $path = '../../';

    // include the header
    include('temp_header.php');
}

?>

<div class="attract"> <!-- attract block -->
    <div class="container paralax_spacer"></div>
</div>

<div class="container paralax_page">
    <div id="registration-form">
        <h1>Register</h1>

        <?php 
if (isset($_SESSION['error']))
{
        ?>
        <div class="alert alert-error"><strong>Error </strong><?php echo $_SESSION['error']; ?></div>
        <?php

}
        ?>

        <fieldset>
            <div id="registerMsg">
            </div>

            <form id="frmRegister" method="POST" action="index.php">
                <!-- persist the current action -->
                <input type="hidden" name="action" value="<?php echo $action; ?>">

                <label>Username</label><br>
                <input type="text" required id="un" name="un" value="<?php 
if(isset($input['un'])) {
   echo $input['un']; 
} else {
   echo ''; 
}
                                                                     ?>" placeholder="Username"><br>

                <label>Password</label><br>
                <input type="password" required id="up" name="up" value=""placeholder="Password"><br>

                <label>Confirm your Password</label><br>
                <input type="password" required id="up2" name="up2" value=""placeholder="Confirm Password"><br>

                <label>Email Address</label><br>
                <input type="email" required id="em" name="em" value=""placeholder="Email Address"><br>

                <input name="submit" type="submit" value="Register">
                <footer class="clearfix">
                    <p><!-- span class="info">?</span><a href="#">Forgot Password</a --></p>
                </footer>
            </form>
        </fieldset>
    </div> <!-- end login-form -->
</div>
