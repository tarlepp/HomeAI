<?php
/**
 * \php\ORM\Manager.php
 *
 * @package     HomeAI
 * @subpackage  ORM
 * @category    ORM
 */
namespace HomeAI\ORM;

use HomeAI\Util\Config;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * Base ORM manager class.
 *
 * @package     HomeAI
 * @subpackage  ORM
 * @category    ORM
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Manager implements Interfaces\Manager
{
    /**
     * Singleton class variable.
     *
     * @access  protected
     * @static
     *
     * @var \HomeAI\ORM\Manager
     */
    protected static $instance;

    /**
     * Doctrine ORM Entity Manager object.
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $manager;

    /**
     * Construction of the class.
     *
     * @return  Manager
     */
    protected function __construct()
    {
        $this->initialize();
    }

    /**
     * Getter method for class instance.
     *
     * @return  Manager
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof Manager) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Static getter method for Doctrine ORM Entity manager.
     *
     * @return  EntityManager
     */
    public static function getManager()
    {
        return self::getInstance()->getEntityManager();
    }

    /**
     * Normal getter method for Doctrine ORM Entity manager.
     *
     * @return  EntityManager
     */
    public function getEntityManager()
    {
        return $this->manager;
    }

    /**
     * Method initializes Doctrine ORM to use. Basically method creates necessary
     * Entity Manager object with specified configurations and stores it to class
     * attribute for later usage.
     *
     * @return  void
     */
    private function initialize()
    {
        $isDevMode = (bool)DEVELOPMENT_DEBUG;
        $entityDirectory = PATH_BASE . 'php' . DIRECTORY_SEPARATOR . 'ORM' . DIRECTORY_SEPARATOR . 'Entities';

        // Create annotation meta data configuration for entities
        $config = Setup::createAnnotationMetadataConfiguration(array($entityDirectory), $isDevMode);

        // Try to read used connection parameters
        $parameters = Config::readIni('database.ini');

        // obtaining the entity manager
        $this->manager = EntityManager::create($parameters, $config);

        // Create annotation driver and set it to current entity manager
        $driver = new AnnotationDriver(new AnnotationReader(), $entityDirectory);
        $this->manager->getConfiguration()->setMetadataDriverImpl($driver);

        AnnotationRegistry::registerAutoloadNamespace('ORM', $entityDirectory);
    }
}
