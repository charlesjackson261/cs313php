<?php
// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

// if we have made it this far the page has made it by the controller.

// check the host to determine which connection information to use
$whitelist = array('127.0.0.1', "::1");

$isProd = false;

if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
    // not valid
    $isProd = true;
}

// check to see if we should be in debug mode
    if (!$isProd) {
        $debug = true;
    }

// configure the database
try
{
    if ($isProd) {
        $dbHost = getenv('OPENSHIFT_MYSQL_DB_HOST');
        $dbPort = getenv('OPENSHIFT_MYSQL_DB_PORT');
        $dbUser = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
        $dbPassword = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
        $dbName = 'php';
    } else {
        $dbUser = "root";
        $dbPassword = "";
        $dbHost = '127.0.0.1';
        $dbPort = 3306;
        $dbName = 'cs313';
    }
    $db = new PDO("mysql:host=$dbHost:$dbPort;dbname=$dbName", $dbUser, $dbPassword);

    unset($dbUser);
    unset($dbPassword);
    unset($dbHost);
    unset($dbname);
}
catch (PDOException $ex)
{
    echo "Error!: " . $ex->getMessage();
    die();
}