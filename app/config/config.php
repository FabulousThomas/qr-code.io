<?php
// Secure session cookies and start session
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? '80') == 443);
if (session_status() === PHP_SESSION_NONE) {
   session_set_cookie_params([
      'lifetime' => 0,
      'path' => '/',
      'domain' => '',
      'secure' => $isHttps,
      'httponly' => true,
      'samesite' => 'Lax',
   ]);
   session_start();
}

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'qrcode');

// SITE PARAMS
// App root directory
define("APPROOT", dirname(dirname(__FILE__)));
// Url root
define("URLROOT", getenv('APP_URL') ?: "http://localhost/qr-code.io");
// Site Name
define("SITENAME", "QRCode Generator");
// App version
define('APPVERSION', '1.0.0');

// Optional local auto-migration (guarded)
$isLocal = in_array($_SERVER['HTTP_HOST'] ?? 'localhost', ['localhost', '127.0.0.1'], true);
$autoMigrate = getenv('AUTO_MIGRATE') !== false ? (bool) intval(getenv('AUTO_MIGRATE')) : $isLocal;
if ($autoMigrate) {
   if (!$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS)) {
      die('CONNECTION FAILED');
   }

   if ($conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME)) {
      $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

      // Create codes table for storing barcodes and QR codes
      $conn->query("CREATE TABLE IF NOT EXISTS `codes` (
         `id` INT NOT NULL AUTO_INCREMENT,
         `type` ENUM('barcode', 'qrcode') NOT NULL DEFAULT 'barcode',
         `value` TEXT NOT NULL,
         `format` VARCHAR(50) DEFAULT NULL,
         `user_id` INT DEFAULT NULL,
         `email` VARCHAR(255) DEFAULT NULL,
         `session_id` VARCHAR(255) DEFAULT NULL,
         `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
         `customization_options` TEXT DEFAULT NULL,
         `image_data` TEXT DEFAULT NULL,
         `image_path` VARCHAR(500) DEFAULT NULL,
         PRIMARY KEY (`id`),
         KEY `idx_session` (`session_id`),
         KEY `idx_user` (`user_id`),
         KEY `idx_type` (`type`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;");

      // Create users table for persisting customers by email
      $conn->query("CREATE TABLE IF NOT EXISTS `users` (
         `id` INT NOT NULL AUTO_INCREMENT,
         `name` VARCHAR(255) NOT NULL,
         `username` VARCHAR(255) NOT NULL,
         `email` VARCHAR(255) NOT NULL,
         `password` VARCHAR(255) NOT NULL,
         `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
         PRIMARY KEY (`id`),
         UNIQUE KEY `uniq_email` (`email`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;");
   }
}
