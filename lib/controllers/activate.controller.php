<?php
// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

require_once('lib/classes/data/userData.class.php');

global $input, $log;

// Check to see if we have input from the user
if ( 
    (isset($input['did']) && isset($input['displayOptions'])) ||
    (isset($_SESSION['new_display']))
   )
{
    // pull the display out of the session
    if (isset($_SESSION['new_display']))
    {
        $input['did'] = $_SESSION['new_display']['did'];
        $input['displayOptions'] = $_SESSION['new_display']['displayOptions'];
        unset($_SESSION['new_display']);
    }
    
    // check to see if the user is logged in. If not send them to the login page
    if (!isset($_SESSION['user']))
    {
        // save the current display to the session for addition to the system
        $_SESSION['new_display'] = array();   
        $_SESSION['new_display']['did'] = $input['did'];
        $_SESSION['new_display']['displayOptions'] = $input['displayOptions'];

        $_SESSION['error'] = "You must be have an account to activate a display. Please login in.";
        header( 'Location: index.php?action=login' ) ;
        die;
    }

    // user has submitted the form
    $input['did'] = strtoupper($input['did']); 

    // check to see if the username has been already taken.
    // $sql = "Select displayid, license from display where license = :did";
    $sql = "SELECT * 
FROM display as d
left join lkdisplaydg as lkdg on lkdg.DisplayID = d.DisplayID
left join displaygroup as dg on lkdg.DisplayGroupID = dg.DisplayGroupID
where DisplayGroup = \"unassigned\" and license = :did";
    $q = $db->prepare($sql);
    $q->execute(array(':did'=>$input['did'] ));
    $row = $q->fetch();

    if(isset($row['displayid']))
    {
        // display found, now check to see if it is assigned to the unassigned group
        // found the display
        $log->display_found = count($row) . ' ' . print_r($row, true);
        // echo count($row) . ' ' . print_r($row, true);

        // create a new group for the user
        $sql = "SELECT * FROM displaygroup as dg where dg.DisplayGroup = :userdg";
        $q = $db->prepare($sql);
        $q->execute(array(':userdg'=>'user-'.$_SESSION['user']['UserID'] ));
        $grouprow = $q->fetch();

        if (isset($grouprow['DisplayGroupID']))
        {
            // we have a group to add it to
            $GroupID = $grouprow['DisplayGroupID'];
        } else {
            // create a new group
            $sql = "INSERT INTO displaygroup (DisplayGroup,Description,IsDisplaySpecific) VALUES (:DisplayGroup,:Description,0)";
            $insert_stmt = $db->prepare($sql);
            $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $insert_stmt->execute(array(':DisplayGroup'=>'user-'.$_SESSION['user']['UserID'], ':Description'=>'Group for activated displays for user '.$_SESSION['user']['UserID']));

            $GroupID = $db->lastInsertId();

        }

        // get the id of the display specific group
        $sql = "SELECT * FROM displaygroup as dg where dg.DisplayGroup = :did";
        $q = $db->prepare($sql);
        $q->execute(array(':did'=>$input['did'] ));
        $display_specific_group = $q->fetch();
        
        // remove it from the unassigned group
        $sql = "DELETE FROM lkdisplaydg where DisplayID = :DisplayID and DisplayGroupID != :display_specific_group";
        $delete_stmt = $db->prepare($sql);
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $delete_stmt->execute(array(':DisplayID'=>$row['displayid'], ':display_specific_group'=>$$display_specific_group['DisplayGroupID']));

        // register it to a new group for the current user
        $sql = "INSERT INTO lkdisplaydg (DisplayGroupID,DisplayID) VALUES (:DisplayGroupID,:DisplayID)";
        $insert_stmt = $db->prepare($sql);
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $insert_stmt->execute(array(':DisplayGroupID'=>$GroupID, ':DisplayID'=>$row['displayid']));

        header( 'Location: index.php?action=dashboard' ) ;



    } else {
        $_SESSION['error'] = "Display ID \"".$input['did']."\" could not be located. Please check your ID and try again.";
        header( 'Location: index.php?action=activate' ) ;
        die;
    }

}


// prepare output
if ($isHtml)
{
    // display the webpage
    $view = new Template("views/activate.php");
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

