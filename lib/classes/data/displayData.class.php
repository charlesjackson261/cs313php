<?php
// template engine that loads and then populates the data from the file.

// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

class Display
{
    // TODO: Add in the code to create, retrive, update, delete entries from this table
    protected $db;
    protected $data = array();

    public function __construct($db)
    {
        $this->db =& $db;
        $this->data['data'] = array();
    }

    public function getListByUser($userID)
    {
        $jr = new JsonResponse("DisplayResponse", 1);

        try {

            // Get the display list

            $sql = "SELECT distinct ";

            // check to see if the user has supplier a column list;
            if (isset($this->data['data']['column_list']))
                $sql .= $this->data['data']['column_list'];
            else
                $sql .= ' * ';

            $sql .= " FROM display as d ";

            $sql .= " left join lkdisplaydg as lkdg on lkdg.DisplayID = d.DisplayID
left join displaygroup as dg on lkdg.DisplayGroupID = dg.DisplayGroupID
where dg.DisplayGroup = 'user-" . $userID . "' 
";
            // echo $sql;
            $displays = $this->db->query($sql)->fetchAll();

            if (!isset($displays))  {
                $jr->setResponseCode(0);
                $jr->addMsg('A display could not be found. (code 38)');
                return $jr;
            }

            // echo '<pre>'.print_r($displays, true).'</pre>';


            // remove the index values and keep the named values
            foreach($displays as $key1=>$display)
            {
                foreach($display as $key=>$value)
                {
                    if (is_int($key))
                        unset($displays[$key1][$key]);
                }
            }

            $jr->displays = $displays;

            return $jr;

        } catch (Exception $e) {
            $jr->setResponseCode(0);
            $jr->addMsg('<pre>Exception: '.print_r($e, true).'</pre>');
            return $jr;
        }

        return $jr;

    }

    public function remove_from_group($userID, $displayID)
    {
        $jr = new JsonResponse("DisplayResponse", 1);

        try {

            // fertch the group id
            $sql = "SELECT * FROM displaygroup as dg where dg.DisplayGroup = :userdg";
            $q = $this->db->prepare($sql);
            $q->execute(array(':userdg'=>'user-'.$userID ));
            $user_group = $q->fetch();

            if(!isset($user_group['DisplayGroupID']))
            {
                $jr->setResponseCode(0);
                $jr->addMsg('Group for user cannot be found, terminating the process.');
                return $jr;
            }

            $DisplayGroupID = $user_group['DisplayGroupID'];

            // fertch the unassigned group id
            $sql = "SELECT * FROM displaygroup as dg where dg.DisplayGroup = 'unassigned'";
            $q = $this->db->prepare($sql);
            $q->execute();
            $unassigned_group = $q->fetch();

            if(!isset($unassigned_group['DisplayGroupID']))
            {
                $jr->setResponseCode(0);
                $jr->addMsg('Unassigned Group for user cannot be found, terminating the process.');
                return $jr;
            }

            $UnassDisplayGroupID = $unassigned_group['DisplayGroupID'];

            // we have a display group id
            $sql = "DELETE FROM lkdisplaydg where DisplayID = :DisplayID and DisplayGroupID = :display_group";
            $delete_stmt = $this->db->prepare($sql);
            $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $delete_stmt->execute(array(':DisplayID'=>$displayID, ':display_group'=>$DisplayGroupID));

            // put them back into the unassigned list
            $sql = "INSERT INTO lkdisplaydg (DisplayGroupID,DisplayID) VALUES (:DisplayGroupID,:DisplayID)";
            $insert_stmt = $this->db->prepare($sql);
            $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $insert_stmt->execute(array(':DisplayGroupID'=>$UnassDisplayGroupID, ':DisplayID'=>$displayID));

            return $jr;

        } catch (Exception $e) {
            $jr->setResponseCode(0);
            $jr->addMsg('<pre>Exception: '.print_r($e, true).'</pre>');
            return $jr;
        }

        return $jr;

    }

    public function __get($data)
    {
        // this runs a simple query against the database to lookup a user.
        return $this->data['data'][$key];
    }

    public function __set($key, $value)
    {
        $this->data['data'][$key] = $value;
    }

    public function __toString()
    {
        return json_encode($this->data);
    }

}