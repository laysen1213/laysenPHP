<?php
session_start();
define('PATH','./');
define('CONTROLLER' , './controller');
define('VIEW' , './view');
include './system/laysen.php';
$laysen = new \laysen\main;
$laysen->run();
echo "string";
