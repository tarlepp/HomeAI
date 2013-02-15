<?php
/**
 * \php\Core\Init.php
 *
 * @package     HomeAI
 * @subpackage  Init
 * @category    Initializer
 */
namespace HomeAI\Core;

use Doctrine\Common\ClassLoader;

/**
 * This class process all session handling.
 *
 * @package     HomeAI
 * @subpackage  Init
 * @category    Initializer
 *
 * @date        $Date$
 * @version     $Rev$
 * @author      $Author$
 */
class System
{
    /*
    **
    * Singleton class variable.
    *
    * @access  protected
    * @var     \HomeAI\Core\System
    */
    protected static $instance = null;

    /**
     * @var \HomeAI\Core\System\Locale
     */
    protected $locale;

    /**
     * Used system components and properties.
     *
     * @var     array
     */
    private $components = array(
        'constant'      => array(
            'property'  => false,
            'class'     => 'Constant'
        ),
        'database'      => array(
            'property'  => false,
            'method'    => 'initDatabase'
        ),
        'session'       => array(
            'property'  => false,
            'method'    => 'initSession'
        ),
        'locale'        => array(
            'property'  => true,
            'class'     => 'Locale'
        ),
    );

    /**
     * Construction of the class.
     */
    protected function __construct()
    {
        $this->initializeComponents();
    }

    /**
     * Method initialize Session -class to use.
     *
     * @access  public
     * @static
     *
     * @return  \HomeAI\Core\Session
     */
    public static function initialize()
    {
        if (is_null(System::$instance)) {
            System::$instance = new System;
        }

        return System::$instance;
    }

    /**
     * Method initializes all HomeAI specified components.
     */
    protected function initializeComponents()
    {
        array_walk($this->components, array($this, 'initializeComponent'));
    }

    /**
     * Method initializes single HomeAI component to use. Note that All
     * system components has fixed definitions.
     *
     * @throws  Exception
     *
     * @param   array   $data
     * @param   string  $name
     */
    protected function initializeComponent(array $data, $name)
    {
        $class = $this->getClassName($data, $name);
        $method = $this->getMethodName($data, $name);

        if (isset($data['property']) && $data['property'] === true) {
            if (is_null($class)) {
                $message = sprintf(
                    "Invalid configuration for system component '%s', no class specified.",
                    $name
                );

                throw new Exception($message);
            }

            $this->{$name} = new $class();
        } else {
            if (!is_null($class)) {
                new $class();
            } elseif (!is_null($method)) {
                call_user_func(array($this, $method));
            } else {
                $message = sprintf(
                    "Invalid configuration for system component '%s', no class or method specified.",
                    $name
                );

                throw new Exception($message);
            }
        }
    }

    /**
     * Method returns component class name.
     *
     * @throws  Exception
     *
     * @param   array   $data   Component data
     * @param   string  $name   Name of the component
     *
     * @return  null|string
     */
    protected function getClassName(array $data, $name)
    {
        if (!isset($data['class'])) {
            return null;
        } else {
            // Specify name of the used component class.
            $class = "\\HomeAI\\Core\\System\\". $data['class'];

            // Class doesn't exist => fatal error
            if (!class_exists($class)) {
                $message = sprintf(
                    "Specified system component '%s' class '%s' not found",
                    $name,
                    $class
                );

                throw new Exception($message);
            }
        }

        return $class;
    }

    /**
     * Method returns component method name. No that this method must be in
     * this class scope.
     *
     * @throws Exception
     *
     * @param   array   $data   Component data
     * @param   string  $name   Name of the component
     *
     * @return  string|null
     */
    protected function getMethodName(array $data, $name)
    {
        $method = (isset($data['method'])) ? $data['method'] : null;

        if (is_null($method)) {
            return null;
        }

        if (!method_exists($this, $method)) {
            $message = sprintf(
                "Invalid configuration for system component '%s', method '%s' doesn't exists.",
                $name,
                $method
            );

            throw new Exception($message);
        }

        return $method;
    }

    /**
     * Method initializes HomeAI database connection. Note that method also
     * disables HomeAI default session handling if database is not initialized.
     */
    protected function initDataBase()
    {
        if (!(defined('INIT_NO_DATABASE') && constant('INIT_NO_DATABASE'))) {
            // Require doctrine class loader
            require_once PATH_BASE .'libs/DoctrineDBAL/Doctrine/Common/ClassLoader.php';

            // Register doctrine class loader
            $classLoader = new ClassLoader('Doctrine', PATH_BASE .'libs/DoctrineDBAL/');
            $classLoader->register();
        } elseif (!(defined('INIT_NO_SESSION') && constant('INIT_NO_SESSION'))) {
            define('INIT_NO_SESSION', true);
        }
    }

    /**
     * Method initializes HomeAI sessions.
     */
    protected function initSession()
    {
        if (!(defined('INIT_NO_SESSION') && constant('INIT_NO_SESSION'))) {
            Session::initialize();
        }
    }
}
