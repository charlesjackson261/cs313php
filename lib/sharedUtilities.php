<?php

// check for input and put it in the generic input variable.
if (count($_POST) > 0)
{
    $input =& $_POST;
} else if (count($_GET) > 0) 
{
    $input =& $_GET;
}

// decode information
function decodeInput($encodedVal)
{
    // currently the transkey is just being added to the end of the string
    // TODO: correct this to reflect the upgraded encoder code.

    $decodedVal = str_replace($_SESSION['transkey'], '', $encodedVal);

    /*
    echo '<pre>' . print_r(array(
        'transkey'=>$_SESSION['transkey'],
        'encodedVal'=>$encodedVal,
        'decodedVal'=>$decodedVal
    ), true) . '</pre>';
    */
    return $decodedVal;
}

// check to make sure that the transkey matches the transkey that we have stored in thesession.
function verifyApp()
{
    if (isset($input['transkey']))
    {
        if (!($input['transkey'] == $_SESSION['transkey']))
        {
            // log an error if this is not a match

        }
    }
}