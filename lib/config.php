<?php
// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

// if we have made it this far the page has made it by the controller.
require_once('sharedUtilities.php');
require_once('classes/templateEngine.class.php');
require_once('classes/log.class.php');

// check the host to determine which connection information to use
$whitelist = array('127.0.0.1', "::1");

$isProd = false;
$debug = false;

if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
    // not valid
    $isProd = true;
}

// check to see if we should be in debug mode
    if (!$isProd) {
        $debug = true;
    }

// site configuration
$pageTitle = 'Charles Jackson - CS313 Project';
$log = new Log("Main Log");

// connect to the database
require_once('dbConnector.php');