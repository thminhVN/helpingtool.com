<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
define('REQUEST_MICROTIME', microtime(true)); //for php < 5.4.* in Zend Developer Tool

//define some value we need for all functions
define('_ST_VERSION', 1);
defined('_ST_APP_ENV') || define('_ST_APP_ENV', getenv('APP_ENV'));
define('_ST_SITE_NAME', 'Blank Project');
define('_ST_TIME_NOW', time());
define('_ST_ROOT_URL', "//$_SERVER[SERVER_NAME]/");
define('_ST_DATE_FORMAT', 'm-d-Y');
define('_ST_TIME_FORMAT', 'H:i:s');
define('_ST_PUBLIC_DIR', getcwd()."/");
define('_ST_DATE_TIME_FORMAT', _ST_DATE_FORMAT . " " . _ST_TIME_FORMAT);
defined('_ST_SERVER_STATUS') || define('SERVER_STATUS', getenv('SERVER_STATUS'));
date_default_timezone_set('Asia/SaiGon');

chdir(dirname(__DIR__));
if(_ST_APP_ENV == 'development'){
    error_reporting(E_ALL);
    ini_set('display_errors', true);
} else {
    error_reporting(0);
    ini_set('display_errors', false);
}

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
