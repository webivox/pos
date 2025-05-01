<?php
session_start();


error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'log/' . time() . '.txt');
if (version_compare(PHP_VERSION, '8.0.0', '<'))
{
      echo 'Error PHP version should be  8+';
}

$from_homecx_load = true;
require_once("config.php");
require_once(_BASE."class/class.inc.php");
require_once(_BASE."load.php");