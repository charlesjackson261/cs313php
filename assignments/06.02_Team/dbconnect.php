<?php

defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

// connect to the database
try
{ 
    $user = "root";
    $password = "";
    $db = new PDO("mysql:host=127.0.0.1;dbname=cs313", $user, $password);
}
catch (PDOException $ex)
{
    echo "Error!: " . $ex->getMessage();
    die();
}

