<?php
// template engine that loads and then populates the data from the file.

// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

class User
{
    // TODO: Add in the code to create, retrive, update, delete entries from this table
    protected $db;
    
    public function __construct($db)
    {
        $this->db =& $db;
        
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