<?php
// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

require_once('lib/classes/data/userData.class.php');

// session_destroy();

global $input;
// create a user object
if (isset($input['un']) && isset($input['up']))
{
    $input['un'] = decodeInput($input['un']);
    $input['up'] = decodeInput($input['up']);
    $user = new User($db);
    
    $login_response = $user->login($input['un'], $input['up']);
    
    // echo '<pre>'.print_r($login_response, true).'</pre>';
    // echo '<pre>'.print_r($login_response->userinfo['UserID'], true).'</pre>';
    
    if ($login_response->getResponseCode() == 1)
    {
        // successful login
        $isLoginSuccess = 1;
        
        // Prep the data
        $userinfo = $login_response->userinfo;
        
        // push it into the 
        $_SESSION['user'] = $userinfo;
        
    } else {
        $isLoginSuccess = 0;
        // echo "Login Failure";
    }
    
}


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
