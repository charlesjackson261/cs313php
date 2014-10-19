<?php
// template engine that loads and then populates the data from the file.

// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

class Template
{
    protected $template;
    protected $variables = array();

    public function __construct($template)
    {
        $this->template = $template;
    }

    public function __get($key)
    {
        return $this->variables[$key];
    }

    public function __set($key, $value)
    {
        $this->variables[$key] = $value;
    }

    public function render()
    {
        return $this->__toString();
    }

    
    public function __toString()
    {
        extract($this->variables);
        // chdir(dirname($this->template));
        ob_start();

        // include basename($this->template);
        include(getcwd() . '/' . $this->template);
        /*
        echo $this->template . "\r\n";
        echo getcwd() . "\r\n";
        echo 'awesome - something';
        */

        return ob_get_clean();
    }
}