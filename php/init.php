<?php
/**
 * \php\init.php
 *
 * This file contains all necessary actions for HomeAI core initialization. Basically
 * file contains different init function calls so that system will work as designed.
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Core
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
use HomeAI\Util\Config;

// Default timezone setting
date_default_timezone_set('Europe/Helsinki');

// Set all locale settings to UTF8
setlocale(LC_ALL, 'fi_FI.UTF8');

// Set UTF8 to be default
mb_http_output('UTF-8');
mb_internal_encoding('UTF-8');

// We want to show all errors.
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

// Define system basepath
define('PATH_BASE', dirname(__DIR__) . '/');

// Change current directory to system root
chdir(constant('PATH_BASE'));

// Register class autoloader
spl_autoload_register('homeAiAutoload');

/**
 * Function handles HomeAi system class autoload functionality.
 *
 * @access  public
 *
 * @param   string  $class
 *
 * @return  void
 */
function homeAiAutoload($class)
{
    // Namespace call
    if (mb_strpos($class, '\\') !== false) {
        $bits = explode('\\', $class);

        $check = array_shift($bits);

        if ($check != 'HomeAI') {
            return;
        }

        $class = array_pop($bits);
    }

    if (isset($bits) && is_array($bits) && count($bits) > 0) {
        while (count($bits) > 0) {
            $classDir  = implode('/', $bits);
            $classFile = constant('PATH_BASE') . "php/" . $classDir . "/" . $class . ".php";

            if (is_readable($classFile)) {
                require_once $classFile;

                break 1;
            } else {
                array_pop($bits);
            }
        }
    }
}

// Define used constants
foreach (Config::readIni('constants.ini') as $section => $data) {
    foreach ($data as $constant => $value) {
        define($section . '_' . $constant, $value);
    }
}

// Require database library
require_once 'database.php';
