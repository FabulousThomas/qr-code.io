<?php 
// Load config file
require_once 'config/config.php';
require_once 'config/error_config.php';
// Load helpers
require_once 'helpers/helpers.php';

// Auto Load Classes
spl_autoload_register(function($className) {
   require_once 'libraries/' . $className . '.php';
});