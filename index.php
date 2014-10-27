<?php
/*
 * This is the main controller
 */

// protect internal pages from being viewed, this gains access to those pages.
define('CS313', true);

require_once('lib/config.php');

// check the post or get for an action

// set the method for the request
$isJson = false;
$isHtml = false;

if (!isset($input['method']))
{
    // the input did not define a display method. Setting it to HTML
    $input['method'] = 'html';
}

switch(strtolower($input['method']) ) {

    case 'json':
        $isJson = true;
        break;
    default:
        $isHtml = true;
        break;

};

// add statments to the log for debug
if (isset($input))
{
    // pop the input into variables
    // extract($input); // dangerous code
    $log->post_val = count($_POST) . ' ' . print_r($_POST, true);
    $log->get_val = count($_GET) . ' ' . print_r($_GET, true);
    $log->input = count($input) . ' ' . print_r($input, true);
    $log->javascript = count($javascript) . ' ' . print_r($javascript, true);
} 

// check the action and set to home if not defined.
if (!isset($input['action']))
{
    $action = 'default';
} else {
    $action = $input['action'];
}

// buffer the output of the pages
ob_start();

$log->action = $action;

if ($action == 'default')
{
    // load the default homepage
    $view = new Template("views/home.php");
    echo $view;

} else if ($action == 'login') {

    // check the integrity of the app
    verifyApp();
    
    // run the login controller
    include('lib/controllers/login.controller.php');
    
} else if ($action == 'logout') {

    // run the logout controller
    include('lib/controllers/logout.controller.php');
    
} else if ($action == 'dashboard') {

    // check the integrity of the app
    verifyApp();
    
    // run the dashboard controller
    include('lib/controllers/dashboard.controller.php');
    
    
} else if ($action == 'php-database') {
    echo 'php-database';
}

$content = ob_get_clean();

// add the session to the debug script
$log->session = count($_SESSION) . ' ' . print_r($_SESSION, true);

// create the output
if ($isHtml)
{
    $view = new Template("views/header.php");
    $view->pageTitle = $pageTitle;
    echo $view;
}

echo $content;

if ($isHtml)
{
    $view = new Template("views/footer.php");
    echo $view;
}
