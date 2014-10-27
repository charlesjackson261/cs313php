<?php
// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

// clear the current session
session_destroy();

// send the header tot he 
header( 'Location: index.php' );
