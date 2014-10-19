<?php
/*
 * This is the main controller
 */

// protect internal pages from being viewed, this gains access to those pages.
define('CS313', true);

require_once('lib/config.php');

// check the post or get for an action

if (isset($input))
{
    // pop the input into variables
    // extract($input); // dangerous code
    $log->input = print_r($input, true);
} 

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
    echo 'default item<br>';
    echo 'default item<br>';
    echo 'default item<br>';
    echo 'default item<br>';
    echo 'default item<br>';
    echo $action;

} else if ($action == 'php-database') {
    echo 'php-database';
}
$content = ob_get_clean();

// create the output
$view = new Template("views/header.php");
$view->pageTitle = $pageTitle;
echo $view;

echo $content;

$view = new Template("views/footer.php");
echo $view;

