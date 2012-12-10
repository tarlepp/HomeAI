<?php
/**
 * \php\Database\Interfaces\Connection.php
 *
 * @package     Database
 * @subpackage  Connection
 * @category    Interface
 */
namespace HomeAI\Database\Interfaces;

/**
 * Interface for \HomeAI\Database\Connection -class.
 *
 * @package     Database
 * @subpackage  Connection
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Connection
{
    /**
     * Instance getter method.
     *
     * @access  public
     * @static
     *
     * @uses    \HomeAI\Database\Connection::__construct()
     *
     * @return  \HomeAI\Database\Connection
     */
    public static function getInstance();

    /**
     * Instance getter method for \Doctrine\DBAL\Connection.
     *
     * @access  public
     * @static
     *
     * @return  \Doctrine\DBAL\Connection
     */
    public static function getConnection();

    /**
     * Method return current doctrine dbal connection.
     *
     * @access  public
     *
     * @return  \Doctrine\DBAL\Connection
     */
    public function getDbalConnection();
}
