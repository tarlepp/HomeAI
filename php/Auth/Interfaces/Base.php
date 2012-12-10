<?php
/**
 * \php\Auth\Interfaces\Base.php
 *
 * @package     HomeAI
 * @subpackage  Auth
 * @category    Interface
 */
namespace HomeAI\Auth\Interfaces;

/**
 * Interface for \HomeAI\Auth\Base -class.
 *
 * @package     HomeAI
 * @subpackage  Auth
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Base
{
    /**#@-
     * Basic methods
     */

    /**
     * Construction of the class.
     *
     * @access  public
     *
     * @uses    \Auth\{AuthClass}::initializeAuth()
     *
     * @param   string  $username
     * @param   string  $password
     *
     * @return  \HomeAI\Auth\Interfaces\Base
     */
    public function __construct($username = null, $password = null);

    /**
     * Setter for user data.
     *
     * @access  public
     *
     * @param   array   $data   User data.
     *
     * @return  void
     */
    public function setUserData(&$data);

    /**
     * Getter for user data.
     *
     * @access  public
     *
     * @return  array
     */
    public function getUserData();
    /**#@-*/

    /**#@-
     * Methods that must be implemented in child classes
     */

    public function initializeAuth();

    public function authenticate();
    /**#@-*/
}
