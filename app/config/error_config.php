<?php
// ERROR REPORTING LOGS | CHECK "PUBLIC" FOLDER FOR ERROR LOGS
error_reporting(E_ALL);
ini_set("display_errors", 0);

function errorHandler($errno, $errstr, $errfile, $errline)
{
   $message = "[" . date("Y-m-d H:ia") . "] Error: [$errno] $errstr ->> $errfile on line $errline" . PHP_EOL . '================================================' . PHP_EOL;
   $logPath = APPROOT . '/../error_log.txt';
   error_log($message, 3, $logPath);
}

set_error_handler("errorHandler");