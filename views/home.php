<?php 

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


    </div>

</div>