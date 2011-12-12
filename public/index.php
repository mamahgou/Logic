<?php

//pre define
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', realpath(dirname(__FILE__) . DS . '..'));
define('APP_PATH', ROOT_PATH . DS . 'application');
define('APP_ENV', (getenv('APP_ENV') ? getenv('APP_ENV') : 'production'));
define('LIB_PATH', ROOT_PATH . DS . 'library');
define('DATA_PATH', ROOT_PATH . DS . 'data');
define('MODULES_PATH', APP_PATH . DS . 'modules');
define('CONFIG_PATH', APP_PATH . DS . 'configs');
define('CONFIG_FILE', CONFIG_PATH . DS . 'application.ini');
define('LOG_PATH', DATA_PATH . DS . 'logs');
define('TMP_PATH', DATA_PATH . DS . 'tmp');
define('CACHE_PATH', DATA_PATH . DS . 'cache');
define('SESSION_PATH', DATA_PATH . DS . 'session');
define('FONT_PATH', DATA_PATH . DS . 'font');
define('PUBLIC_PATH', ROOT_PATH . DS . 'public');
define('IMAGE_PATH', PUBLIC_PATH . DS . 'img');
define('PUBLIC_TMP_PATH', PUBLIC_PATH . DS . 'tmp');
define('CAPTCHA_PATH', PUBLIC_PATH . DS . 'captcha');
define('TODAY', date('Y-m-d'));

//set include path
set_include_path(implode(PATH_SEPARATOR, array(
    LIB_PATH,
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APP_ENV,
    CONFIG_FILE
);
$application->bootstrap()->run();