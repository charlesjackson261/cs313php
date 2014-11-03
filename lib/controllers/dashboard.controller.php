<?php
// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

require_once('lib/classes/data/displayData.class.php');

global $input;

// check to see if there are any displays that need to be added
if (isset($_SESSION['new_display']))
{
    // new display found
    header( 'Location: index.php?action=activate' ) ;
    die;

}

// dashboard
$display = new Display($db);
$display->column_list = "display, license, ClientAddress";
$displays_response = $display->getListByUser($_SESSION['user']['UserID']);

// echo '<pre>'.print_r($displays_response, true).'</pre>';
// echo '<pre>'.print_r($displays_response->displays, true).'</pre>';

$displays = $displays_response->displays;

$display_action = <<<EOT

        <!-- Single button -->
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Action <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a class="btnUpdateSubscription"><span class="glyphicon glyphicon-pencil"></span>  Update Subscription</a></li>
                <li><a class="btnEditDisplay"><span class="glyphicon glyphicon-pencil"></span>  Edit Display</a></li>
                <li><a  class="btnDeleteDisplay"><span class="glyphicon glyphicon-minus-sign"></span>  Delete Display</a></li>
                <li class="divider"></li>
                <li><a href="#">Separated link</a></li>
            </ul>
        </div>
EOT;

// echo '<pre>Data: '.print_r($data, true).'</pre>';

// echo '<br><br><br><br>';

// $content = new Template("views/displayTable.php");
// $content->action = $action;


// get a list of displays for this account. it will be based on their permissions


// prepare output
if ($isHtml)
{
    // display the webpage
    $view = new Template("views/dashboard.php");
    $view->action = $action;
    echo $view;
} else {
    echo "Transport error";
}
