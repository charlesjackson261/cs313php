<?php
// template engine that loads and then populates the data from the file.

// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

class JsonResponse
{
    protected $data = array();
    
    public function __construct($type)
    {
        $this->data['type'] = $type;
        $this->data['messges'] = array();
        $this->data['data'] = array();
    }

    public function __get($key)
    {
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