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

// check to see if we need to perform a data level function
if (isset($input['delete_display']))
{
    if (is_numeric($input['delete_display']))
    {
        // perfom the deletion
        $display = new Display($db);
        $jr_result = $display->remove_from_group($_SESSION['user']['UserID'], $input['delete_display']);

        if ($jr_result->dataObj()['response_code'] == 1)
        {
            $_SESSION['warning'] = "Display has been deleted.";
            header( 'Location: index.php?action=dashboard' ) ;
            die;

        } else {
            $_SESSION['error'] = "Display could not be deleted.";
            header( 'Location: index.php?action=dashboard' ) ;
            die;

        }

    } else {
        $_SESSION['error'] = "Delete function has gone arie.";
        header( 'Location: index.php?action=dashboard' ) ;
        die;
    };
} else if (isset($input['update_description'])) {

} else if (isset($input['edit_display'])) {
    // not imp[lemented]
}

// dashboard
$display = new Display($db);
$display->column_list = "d.displayid, display, license, ClientAddress";
$displays_response = $display->getListByUser($_SESSION['user']['UserID']);

// echo '<pre>'.print_r($displays_response, true).'</pre>';
// echo '<pre>'.print_r($displays_response->displays, true).'</pre>';

$displays = $displays_response->displays;

/*
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
            </ul>
        </div>
EOT;
*/

$display_action = <<<EOT
            <button type="button" class="btn btn-default btnDeleteDisplay">
                <span class="glyphicon glyphicon-minus-sign"></span>  Delete Display</a>
            </button>
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
