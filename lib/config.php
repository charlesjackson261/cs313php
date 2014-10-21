<?php
// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

// start the session
session_start();

// generate a salt for this session to use.
$length = 25;

if (!isset($_SESSION['transkey']))
    $_SESSION['transkey'] = base64_encode(mcrypt_create_iv(ceil(0.75*$length), MCRYPT_DEV_URANDOM));

// if we have made it this far the page has made it by the controller.
require_once('sharedUtilities.php');
require_once('classes/templateEngine.class.php');
require_once('classes/log.class.php');
require_once('classes/jsonResponse.class.php');

// check the host to determine which connection information to use
$whitelist = array('127.0.0.1', "::1");

// Application Variables
$isProd = false;
$debug = false;
$javascript = array();

// set if the app is in poduction or development
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
array_push($javascript, "var transkey=\"".$_SESSION['transkey']."\";\r\n");

// connect to the database
require_once('dbConnector.php');