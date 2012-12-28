<?php
/**
 * \php\Core\Interfaces\Session.php
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Interface
 */
namespace HomeAI\Core\Interfaces;

/**
 * Interface for \HomeAI\Core\Session -class.
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Session
{
    /**
     * Generic session open method.
     *
     * @access  public
     *
     * @param   string  $path
     * @param   string  $name
     *
     * @return  boolean
     */
    public function open($path, $name);

    /**
     * Generic session close method.
     *
     * @access  public
     *
     * @return  boolean
     */
    public function close();

    /**
     * Session read method.
     *
     * @access  public
     *
     * @param   string  $sessionId
     *
     * @return  string
     */
    public function read($sessionId);

    /**
     * Session write method.
     *
     * @access  public
     *
     * @param   string  $sessionId
     * @param   string  $sessionData
     *
     * @return  boolean
     */
    public function write($sessionId, $sessionData);

    /**
     * Session destroy method.
     *
     * @access  public
     *
     * @param   string  $sessionId  Session id
     *
     * @return  boolean
     */
    public function destroy($sessionId);

    /**
     * Session cleanup method.
     *
     * @access  public
     *
     * @return  boolean
     */
    public function gc();

    /**
     * Method determines if session exists already.
     *
     * @access  public
     *
     * @param   string  $sessionId  Session id
     *
     * @return  boolean
     */
    public function isValid($sessionId);
}
