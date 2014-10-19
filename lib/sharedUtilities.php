<?php


// check for input and put it in the generic input variable.
if (count($_POST) > 0)
{
    $input =& $_POST;
} else if (count($_GET) > 0) 
{
    $input =& $_GET;
}
