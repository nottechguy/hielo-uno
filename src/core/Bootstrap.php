<?php
/**
 * LVL Industries 
 */

const VERSION = "";

if (file_exists(APPPATH . "config/" . ENVIRONMENT . "/constants.php")) {
    require_once APPPATH . "config/" . ENVIRONMENT . "/constants.php";
}

if (file_exists(APPPATH . "config/constants.php")) {
    require_once APPPATH . "config/constants.php";
}

require_once BASEPATH . "core/Common.php";

set_error_handler("_error_handler");
set_exception_handler("_exception_handler");
register_shutdown_function("_shutdown_handler");

// Is there a composer autoloader?
if ($composer_autoload = config_item("composer_autoload")) {
    if ($composer_autoload === TRUE) {
        file_exists(APPPATH . "vendor/autoload.php") ? 
            require_once(BASEPATH . "vendor/autoload.php") : 0;
    } elseif (file_exists($composer_autoload)) {
        require_once($composer_autoload);
    } else {
        // Log
        log_message('error', 'Could not find the specified $config[\'composer_autoload\'] path: '.$composer_autoload);
    }
}

$CFG =& load_class('Config', 'core');
if (isset($assign_to_config) && is_array($assign_to_config)) {
    foreach ($assign_to_config as $key => $value) {
        $CFG->set_item($key, $value);
    }
}

$charset = strtoupper(config_item('charset'));
ini_set('default_charset', $charset);
if (extension_loaded('mbstring')) {
    define('MB_ENABLED', TRUE);
    // mbstring.internal_encoding is deprecated starting with PHP 5.6
    // and it's usage triggers E_DEPRECATED messages.
    @ini_set('mbstring.internal_encoding', $charset);
    // This is required for mb_convert_encoding() to strip invalid characters.
    // That's utilized by CI_Utf8, but it's also done for consistency with iconv.
    mb_substitute_character('none');
} else {
    define('MB_ENABLED', FALSE);
}

// There's an ICONV_IMPL constant, but the PHP manual says that using
// iconv's predefined constants is "strongly discouraged".
if (extension_loaded('iconv')) {
    define('ICONV_ENABLED', TRUE);
    // iconv.internal_encoding is deprecated starting with PHP 5.6
    // and it's usage triggers E_DEPRECATED messages.
    @ini_set('iconv.internal_encoding', $charset);
} else {
    define('ICONV_ENABLED', FALSE);
}

if (is_php('5.6')) {
    ini_set('php.internal_encoding', $charset);
}

$UNI =& load_class('Utf8', 'core', $charset);
$URI =& load_class('URI', 'core', $CFG);
$RTR =& load_class('Router', 'core', isset($routing) ? $routing : NULL);
$OUT =& load_class('Output', 'core');
$SEC =& load_class('Security', 'core', $charset);
$IN =& load_class('Input', 'core', $SEC);
$LANG =& load_class('Lang', 'core');

// Base controller class
require_once BASEPATH.'core/Controller.php';
function &get_instance() {
    return Controller::get_instance();
}
if (file_exists(APPPATH.'core/'.$CFG->config['subclass_prefix'].'Controller.php')) {
	require_once APPPATH.'core/'.$CFG->config['subclass_prefix'].'Controller.php';
}

$e404 = FALSE;
$class = ucfirst($RTR->class);
$method = $RTR->method;

if (empty($class) OR ! file_exists(APPPATH.'controllers/'.$RTR->directory.$class.'.php')) {
	$e404 = TRUE;
} else {
	require_once(APPPATH.'controllers/'.$RTR->directory.$class.'.php');

    if ( ! class_exists($class, FALSE) OR $method[0] === '_' OR method_exists('CI_Controller', $method)) {
        $e404 = TRUE;
    } elseif (method_exists($class, '_remap')) {
        $params = array($method, array_slice($URI->rsegments, 2));
        $method = '_remap';
    } elseif ( ! method_exists($class, $method)) {
        $e404 = TRUE;
    } else {
        $reflection = new ReflectionMethod($class, $method);
        if ( ! $reflection->isPublic() OR $reflection->isConstructor()) {
            $e404 = TRUE;
        }
    }
}

if ($e404) {
    if ( ! empty($RTR->routes['404_override'])) {
        if (sscanf($RTR->routes['404_override'], '%[^/]/%s', $error_class, $error_method) !== 2) {
            $error_method = 'index';
        }

        $error_class = ucfirst($error_class);

        if ( ! class_exists($error_class, FALSE)) {
            if (file_exists(APPPATH.'controllers/'.$RTR->directory.$error_class.'.php')) {
                require_once(APPPATH.'controllers/'.$RTR->directory.$error_class.'.php');
                $e404 = ! class_exists($error_class, FALSE);
            }
            // Were we in a directory? If so, check for a global override
            elseif ( ! empty($RTR->directory) && file_exists(APPPATH.'controllers/'.$error_class.'.php')) {
                require_once(APPPATH.'controllers/'.$error_class.'.php');
                if (($e404 = ! class_exists($error_class, FALSE)) === FALSE) {
                    $RTR->directory = '';
                }
            }
        } else {
            $e404 = FALSE;
        }
    }

    // Did we reset the $e404 flag? If so, set the rsegments, starting from index 1
    if ( ! $e404) {
        $class = $error_class;
        $method = $error_method;

        $URI->rsegments = array(
            1 => $class,
            2 => $method
        );
    } else {
        show_404($RTR->directory.$class.'/'.$method);
    }
}

if ($method !== '_remap') {
    $params = array_slice($URI->rsegments, 2);
}

$CI = new $class();

// Call request method
call_user_func_array(array(&$CI, $method), $params);
