<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'qrcode');

// SITE PARAMS
// App root directory
define("APPROOT", dirname(dirname(__FILE__)));
// Url root
define("URLROOT", "http://localhost/qr-code.io");
// Site Name
define("SITENAME", "QRCode Generator");
// App version
define('APPVERSION', '1.0.0');

// CHECK CONNECTION
if (!$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS)) {
   die('CONNECTION FAILED');
}

// CREATE DATABASE AND TABLES
if ($conn->query("CREATE DATABASE IF NOT EXISTS qrcode")) {
   $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

   $conn->query("CREATE TABLE IF NOT EXISTS `qr-codes` (`id` INT NOT NULL AUTO_INCREMENT , `qrtext` TEXT NOT NULL , `qrimage` VARCHAR(250) NOT NULL , `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
}
