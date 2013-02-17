<?php
/**
 * \php\Core\Autoload.php
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Autoload
 */
namespace HomeAI\Core;

/**
 * This class contains HomeAI autoload class, which handles class
 * autoload functionality.
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Autoload
 *
 * @date        $Date$
 * @version     $Rev$
 * @author      $Author$
 */
class Autoload
{
    /**
     * Used base path for classes.
     *
     * @var string
     */
    private $path = '';

    /**
     * Constructor of the class.
     */
    public function __construct()
    {
        $this->path = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
    }

    /**
     * Main autoload functionality for HomeAI system.
     *
     * @param   string  $class  The name of the class to attempt to load.
     *
     * @return  void
     */
    public function load($class)
    {
        // Namespace call
        if (mb_strpos($class, '\\') !== false) {
            $bits = explode('\\', $class);

            $check = array_shift($bits);

            if ($check !== 'HomeAI') {
                return;
            }

            $class = array_pop($bits);
        }

        if (isset($bits) && is_array($bits) && count($bits) > 0) {
            $classDir  = implode(DIRECTORY_SEPARATOR, $bits);
            $classFile = $this->path . $classDir . DIRECTORY_SEPARATOR . $class . ".php";

            if (is_readable($classFile)) {
                require_once $classFile;
            }
        }
    }
}
