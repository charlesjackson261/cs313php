<?php
// template engine that loads and then populates the data from the file.

// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

class SysMsg
{
    protected $msg = array();

    public function __construct()
    {
    }

    public function __get($key)
    {
        return $this->msg[$key];
    }

    public function __set($key, $value)
    {
        $this->msg[$key] = $value;
    }

    public function __toString()
    {
        // chdir(dirname($this->template));
        ob_start();

        // output msgs
        
        return ob_get_clean();
    }
}