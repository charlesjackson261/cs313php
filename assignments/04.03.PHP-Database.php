<?php

// protect internal pages from being viewed, this gains access to those pages.
define('CS313', true);

require_once('../lib/config.php');

if ($debug)
{
    echo '<pre>' . print_r($db, true) . '</pre>';
}




