<?php
/**
 * 
 */

/**
 * Application enviroment
 * 
 */

define('ENVIRONMENT', 'development');

switch (ENVIRONMENT) {
    case 'development':
        error_reporting(-1);
        ini_set("display_errors", 1);
    break;
    case 'testing':
    case 'production':
        ini_set("display_errors", 0);
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        break;
    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1);
}

$app_folder     = "app";
$sys_folder     = "src";
$log_folder     = "log";
$config_doler   = "config";
$view_folder    = "views";

// Set the current directory correctly for CLI requests
if (defined('STDIN')) {
    chdir(dirname(__FILE__));
}

// log_folder
if (($tmp = realpath($log_folder)) !== FALSE) {
    $log_folder = $tmp;
} else {
    $log_folder = strtr(
        rtrim($log_folder, '/\\'),
        '/\\',
        DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
    );
}

// sys_folder
if (($tmp = realpath($sys_folder)) !== FALSE) {
    $sys_folder = $tmp . DIRECTORY_SEPARATOR;
} else {
    $sys_folder = strtr(
        rtrim($sys_folder, '/\\'),
        '/\\',
        DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
    ).DIRECTORY_SEPARATOR;
}

define("SELF", pathinfo(__FILE__, PATHINFO_BASENAME));
define("BASEPATH", $sys_folder);
define("SYSDIR", basename(BASEPATH));

if (is_dir($app_folder)) {
    if (($tmp = realpath($app_folder)) !== FALSE) {
        $app_folder = $tmp;
    } else {
        $app_folder = strtr(rtrim($app_folder, "/\\"), "/\\", DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR);
    }
} else if (is_dir(BASEPATH . $app_folder . DIRECTORY_SEPARATOR)) {
    $app_folder = BASEPATH . strstr(trim($app_folder, "/\\"), "/\\", DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR);
} else {
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Your application folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
    exit(3); // EXIT_CONFIG
}

define("APPPATH", $app_folder . DIRECTORY_SEPARATOR);
define("LOGPATH", $log_folder . DIRECTORY_SEPARATOR);

if (!isset($view_folder[0]) && is_dir(APPPATH . 'views' . DIRECTORY_SEPARATOR)) {
    $view_folder = APPPATH . "views";
} else if (is_dir($view_folder)) {
    if (($tmp = realpath($view_folder)) !== FALSE) {
        $view_folder = $tmp;
    } else {
        $view_folder = strtr(
            rtrim($view_folder, "/\\"),
            "/\\",
            DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
        );
    }
} else if (is_dir(APPPATH . $view_folder . DIRECTORY_SEPARATOR)) {
    $view_folder = APPPATH.strtr(
        trim($view_folder, '/\\'),
        '/\\',
        DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
    );
} else {
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Your view folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
    exit(3); // EXIT_CONFIG
}
define('VIEWPATH', $view_folder . DIRECTORY_SEPARATOR);

require_once BASEPATH . "core/Bootstrap.php";