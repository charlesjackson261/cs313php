<?php
// template engine that loads and then populates the data from the file.

// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

class Log
{
    protected $logName;
    protected $logItems = array();

    public function __construct($logName)
    {
        $this->logName = $logName;
    }

    public function __get($key)
    {
        return $this->logItems[$key];
    }

    public function __set($key, $value)
    {
        $this->logItems[$key] = $value;
    }

    public function __toString()
    {
        // chdir(dirname($this->template));
        ob_start();

        echo 'LogName: ' . $this->logName;
        // use the template engine to display the error logs.
        foreach($this->logItems as $key=>$logItem)
        {
            echo '<pre>' . $key . ': ' . print_r($logItem, true) . '</pre>';

        }
        return ob_get_clean();
    }
}