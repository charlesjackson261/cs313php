<?php
// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

require_once('lib/classes/data/displayData.class.php');

global $input;

// dashboard

$display = new Display($db);
$display->column_list = "display, license, ClientAddress";
$displays_response = $display->getListByUser(2);

// echo '<pre>'.print_r($displays_response, true).'</pre>';
// echo '<pre>'.print_r($displays_response->displays, true).'</pre>';

$data = $displays_response->displays;

// echo '<pre>Data: '.print_r($data, true).'</pre>';

echo '<br><br><br><br>';

$view = new Template("views/displayTable.php");
$view->action = $action;
echo $view;


// get a list of displays for this account. it will be based on their permissions


/*
// prepare output
if ($isHtml)
{
    // display the webpage
    $view = new Template("views/login.php");
    $view->action = $action;
    echo $view;
} else if ($isJson) {
    $jr = new JsonResponse("LoginResponse", $isLoginSuccess);
    $jr->input_data = $input;
    $jr->login_response = $login_response;

    // echo $jr;
    echo $login_response;

} else {
    echo "Transport error";
}
*/
