<?php
// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

require_once('lib/classes/data/userData.class.php');

global $input;
// Check to see if we have input from the user
if (isset($input['un']) && isset($input['up']))
{

    $input['un'] = strtolower($input['un']); // usernames should be all lower case
    $input['up'] = $input['up'];
    $input['up2'] = $input['up2'];

    // verify the information

    // check to see if the passwords match
    if ($input['up'] != $input['up2'])
    {
        $_SESSION['error'] = "Your passwords do not match, please correct them.";
        header( 'Location: index.php?action=register' ) ;
        die;
    }

    // check to see if the username has been already taken.
    $sql = "Select UserName from user where UserName = :un";
    $q = $db->prepare($sql);
    $q->execute(array(':un'=>$input['un'] ));
    $row = $q->fetch();

    if(isset($row['UserName']))
    {
        $_SESSION['error'] = "Username \"".$row['UserName']."\" taken. Please choose another, or login with your account.";
        header( 'Location: index.php?action=register' ) ;
        die;
    }

    // success the user does not exist and the passwords are generic
    $user = new User($db);

    $jr = $user->create($input['un'], $input['up'], $input['em']);

    echo '<pre>Result: '.print_r($jr, true).'</pre>';
    echo '<pre>response_code: '.print_r($jr->dataObj()['response_code'], true).'</pre>';

    if ($jr->dataObj()['response_code'] == 1)
    {
        // everything went well
        $login_response = $user->login($input['un'], $input['up']);
        // Prep the data
        $userinfo = $login_response->userinfo;
        // push it into the session
        $_SESSION['user'] = $userinfo;

        header( 'Location: index.php?action=dashboard' ) ;


    } else {
        // disaster
        $_SESSION['error'] = "User \"".$row['UserName']."\" could not be created. Please try again later.";
        header( 'Location: index.php?action=register' ) ;
        die;

    }

}


// prepare output
if ($isHtml)
{
    // display the webpage
    $view = new Template("views/register.php");
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

// clear old errors
unset($_SESSION['error']);

