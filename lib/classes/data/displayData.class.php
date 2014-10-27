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
            
            $sql = "SELECT ";
            
            // check to see if the user has supplier a column list;
            if (isset($this->data['data']['column_list']))
                $sql .= $this->data['data']['column_list'];
            else
                $sql .= ' * ';

            $sql .= " FROM `display`";
            
            
            if (!$displays = $this->db->query($sql)->fetchAll()) {
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