<?php
/**
 * \php\Database\Connection.php
 *
 * @package     Database
 * @subpackage  Connection
 * @category    Database
 */
namespace HomeAI\Database;

use Doctrine\DBAL as DBAL;
use HomeAI\Util\Config;
use HomeAI\Util\Logger;

/**
 * Database connection class. Basicly this class makes database connection.
 *
 * @package     Database
 * @subpackage  Connection
 * @category    Database
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Connection implements Interfaces\Connection
{
    /**
     * Singleton class variable.
     *
     * @access  protected
     * @var     \HomeAI\Database\Connection
     */
    protected static $instance = null;

    /**
     * Instance of Doctrine\DBAL\Connection
     *
     * @access  protected
     * @var     \Doctrine\DBAL\Connection
     */
    protected $connection = null;

    /**
     * Construction of class.
     *
     * @access  protected
     *
     * @uses    \HomeAI\Database\Connection::connect()
     *
     * @return  \HomeAI\Database\Connection
     */
    protected function __construct()
    {
        try {
            $this->connect();
        } catch (\Exception $error) {
            // TODO: Write error and redirect user
            echo $error->getMessage();
            die();
        }
    }

    /**
     * Instance getter method.
     *
     * @access  public
     * @static
     *
     * @return  \HomeAI\Database\Connection
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof Connection) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Instance getter method for \Doctrine\DBAL\Connection.
     *
     * @access  public
     * @static
     *
     * @return  \Doctrine\DBAL\Connection
     */
    public static function getConnection()
    {
        return self::getInstance()->getDbalConnection();
    }

    /**
     * Method return current doctrine dbal connection.
     *
     * @access  public
     *
     * @return  \Doctrine\DBAL\Connection
     */
    public function getDbalConnection()
    {
        return $this->connection;
    }

    /**
     * Method makes actual database connection to specified database using
     * Doctrine components.
     *
     * @access     protected
     *
     * @return     void
     */
    protected function connect()
    {
        // Create DBAL configuration object
        $config = new DBAL\Configuration();

        // Try to read used connection parameters
        $parameters = Config::readIni('database.ini');

        // Create actual connection to database
        $this->connection = DBAL\DriverManager::getConnection($parameters, $config);

        // Set used character set
        //$this->connection->setCharset('UTF8');

        // TODO: Add some connection checks here...
    }
}
