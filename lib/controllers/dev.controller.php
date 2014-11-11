<?php
// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

require_once('lib/classes/data/genData.class.php');

global $input, $log;

$gen_data = new GenData($db);

//$result = $gen_data->create2([
//    'table'=>"log", 
//    'data'=>[
//        'logdate'=>'2014-09-12 23:47:17',
//        'type'=>'error',
//        'page'=>'test_injection',
//        'message'=>'This is a test message',
//        'userID'=>'1'
//    ]
//]);

$result = $gen_data->create2 = 'cheese';

echo '<pre>'.print_r($result, true).'</pre>';



