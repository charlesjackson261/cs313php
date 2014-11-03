<?php 

// check to see if there is a user to authenticate

global $input, $content, $displays, $display_action;

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
    <h1>Dashboard</h1>
    <?php 
if (isset($_SESSION['error']))
{
    ?>
    <div id="loginError" class="alert alert-error"><strong>Error</strong> <?php echo $_SESSION['error']; ?></div>
    <?php
    unset($_SESSION['error']);

} else if (isset($_SESSION['warning']))
{
    ?>
    <div id="loginError" class="alert alert-warning"><strong>Warning</strong> <?php echo $_SESSION['warning']; ?></div>
    <?php
    unset($_SESSION['warning']);

}

    ?>

    <!-- this is where we will put the buttons to mange your displays -->
    <div class="btn-group">
        <button id="btnActivateDisplay" type="button" class="btn btn-default"><span class="glyphicon glyphicon-file"></span> Activate a Display</button>
    </div><br><br>

    <?php echo $content; ?>

    <?php  
if (isset($displays) && is_array($displays))
{
    // generate a table for the data
    $firstRun = true;

    echo '<table class="table table-hover" border="1px" data-toggle="table">';

    foreach($displays as $index=>$dataRow)
    {

        if ($firstRun)
        {
            // generate a set of headers
            echo '<tr>';

            foreach($dataRow as $colName=>$colVal)
            {
                echo '<th>' . $colName . '</th>';
            }
            echo '<th>Action</th>';
            echo '</tr>';
            $firstRun = false;
        }

        echo '<tr data_row=\''.json_encode($dataRow).'\'>';

        foreach($dataRow as $colName=>$colVal)
        {
            echo '<td>' . $colVal . '</td>';
        }
    ?>
    <td>
        <?php echo $display_action; ?>
    </td>

    <?php
        echo '</tr>';
    }

    echo '</table>';


} else {
    // no data to display
    ?>
    <table>
        <tr><td>Sorry, No Data to display.</td></tr>
    </table>

    <?php
}
    ?>
</div>