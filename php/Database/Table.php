<?php
/**
 * \php\Database\Table.php
 *
 * @package     Database
 * @subpackage  Table
 * @category    Database
 */
namespace HomeAI\Database;

defined('HomeAI_INIT') OR die('No direct access allowed.');
/**
 * Database table class.
 *
 * @package     Database
 * @subpackage  Table
 * @category    Database
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Table implements Interfaces\Table
{
    /**
     * Singleton class variable. Basicly this is contains all asked table
     * objects.
     *
     * @access  protected
     * @var     array
     */
    protected static $instance = array();

    /**
     * Used table definition
     *
     * @access     protected
     * @var        string
     */
    protected $table = '';

    /**
     * Construction of class.
     *
     * @access  protected
     *
     * @param   string  $table  Name of the table
     *
     * @return  \HomeAI\Database\Table
     */
    protected function __construct($table)
    {
        $this->table = $table;
    }

    /**
     * Method return defined table object. Note that table objects are returned
     * from cache if it's already initialized.
     *
     * @access  public
     * @static
     *
     * @param   string  $table  Name of the table.
     *
     * @return  \Doctrine\DBAL\Schema\Table
     */
    public static function getInstance($table)
    {
        // Asked table is not in cache
        if (!isset(self::$instance[$table]) || !self::$instance[$table] instanceof Table) {
            $object = new self($table);

            self::$instance[$table] = $object->getTableObject();

            unset($object);
        }

        return self::$instance[$table];
    }

    /**
     * Method returns actual Doctrine table object.
     *
     * @access  public
     *
     * @return  \Doctrine\DBAL\Schema\Table
     */
    public function getTableObject()
    {
        return Schema::getInstance()->listTableDetails($this->table);
    }
}
