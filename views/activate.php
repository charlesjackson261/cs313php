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
    <div id="activate-form">
        <h1>Activate a Display</h1>

        <?php 
if (isset($_SESSION['error']))
{
        ?>
        <div class="alert alert-error"><strong>Error </strong><?php echo $_SESSION['error']; ?></div>
        <?php

}
        ?>

        <fieldset>
            <div id="activateMsg">
            </div>

            <form id="frmActivate" method="POST" action="index.php">
                <!-- persist the current action -->
                <input type="hidden" name="action" value="<?php echo $action; ?>">

                <label>Display ID</label><br>
                <input type="text" required id="did" name="did" value="<?php 
if(isset($input['did'])) {
    echo $input['did']; 
} else {
    echo ''; 
}
                                                                       ?>" placeholder="Display ID"><br>

                <select required name="displayOptions">
                    <option value="" disabled selected>Please Select a hosting package</option>
                    <option value="Basic">Basic Hosting</option>
                    <option value="c-Advantage">Content Advantage + Hosting</option>
                </select>

                <input name="submit" type="submit" value="Register">
                <footer class="clearfix">
                    <p><!-- span class="info">?</span><a href="#">Forgot Password</a --></p>
                </footer>
            </form>
        </fieldset>
    </div> <!-- end login-form -->
</div>
