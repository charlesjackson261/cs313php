<?php 

// check to see if there is a user to authenticate


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

    <div class="container">

        <div id="login-form">
            <h3>Login</h3>

            <fieldset>

                <div id="loginMsg">
                    <?php 
if (isset($_SESSION['error']))
{
                    ?>
                    <div id="loginError" class="alert alert-warning"><strong>Warning</strong> <?php echo $_SESSION['error']; ?></div>
                    <?php
    unset($_SESSION['error']);

}
                    ?>
                </div>

                <form id="frmLogin" method="POST" action="index.php">

                    <!-- persist the current action -->
                    <input type="hidden" name="action" value="<?php echo $action; ?>">

                    <input type="text" required id="un" name="un" value="Username" onBlur="if(this.value=='')this.value='Username'" onFocus="if(this.value=='Username')this.value='' "> <!-- JS because of IE support; better: placeholder="Username" -->

                    <input type="password" required id="up" name="up" value="Password" onBlur="if(this.value=='')this.value='Password'" onFocus="if(this.value=='Password')this.value='' "> <!-- JS because of IE support; better: placeholder="Password" -->

                    <input name="submit" type="submit" value="Login">

                    <footer class="clearfix">

                        <p><a href="?action=register"><span class="info">?</span> Need an Account?</a></p>

                    </footer>

                </form>

            </fieldset>

        </div> <!-- end login-form -->

    </div>

</div>