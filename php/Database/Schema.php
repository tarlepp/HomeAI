<?php
/**
 * \php\Database\DB.php
 *
 * @package     Database
 * @subpackage  Schema
 * @category    Database
 */
namespace HomeAI\Database;

/**
 * Database schema class.
 *
 * @package     Database
 * @subpackage  Schema
 * @category    Database
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Schema implements Interfaces\Schema
{
    /**
     * Singleton class variable.
     *
     * @access  protected
     * @var     \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    protected static $instance = null;

    /**
     * Construction of class.
     *
     * @access  protected
     *
     * @return  \HomeAI\Database\Schema
     */
    protected function __construct()
    {
    }

    /**
     * Instance getter method.
     *
     * @access  public
     * @static
     *
     * @return  \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof \Doctrine\DBAL\Schema\AbstractSchemaManager) {
            $object = new self();

            self::$instance = $object->getSchemaObject();

            unset($object);
        }

        return self::$instance;
    }

    /**
     * Method returns schema object.
     *
     * @access  public
     *
     * @return  \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    public function getSchemaObject()
    {
        return Connection::getConnection()->getSchemaManager();
    }
}
