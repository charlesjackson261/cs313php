<?php
// template engine that loads and then populates the data from the file.

// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

class JsonResponse
{
    protected $data = array();
    
    // $response_code = 1 success, 0 error;
    public function __construct($type, $response_code=0)
    {
        $this->data['type'] = $type;
        $this->data['response_code'] = $response_code;
        $this->data['msgs'] = array();
        $this->data['data'] = array();
    }

    public function setResponseCode($response_code)
    {
        $this->data['response_code'] = $response_code;
    }
    
    public function getResponseCode()
    {
        return $this->data['response_code'];
    }    
    
    public function addMsg($msg)
    {
        array_push($this->data['msgs'], $msg);
    }
    
    public function __get($key)
    {
        return $this->data['data'][$key];
    }

    public function __set($key, $value)
    {
        $this->data['data'][$key] = $value;
    }

    public function dataObj()
    {
        return $this->data;
    }

    public function __toString()
    {
        return json_encode($this->data);
    }
}