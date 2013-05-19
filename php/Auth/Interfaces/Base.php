<?php
/**
 * \php\Auth\Interfaces\Base.php
 *
 * @package     HomeAI
 * @subpackage  Auth
 * @category    Interface
 */
namespace HomeAI\Auth\Interfaces;

use HomeAI\Auth\Exception;

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
     * Method to make actual login procedures. Basically method initializes a new
     * session and stores necessary user information to specified session.
     *
     * After this, user is redirected to application base url or if request is made
     * via AJAX boolean true value is outputted to client in JSON format.
     *
     * @throws  Exception
     *
     * @return  void
     */
    public function login();

    /**#@-*/

    /**#@-
     * Methods that must be implemented in child classes
     */

    /**
     * Current auth object initializer method.
     *
     * @return  void
     */
    public function initializeAuth();

    /**
     * Actual auth object authenticate method. This will check that given
     * username + password are valid. If not method will throw an exception.
     *
     * @throws \HomeAI\Auth\Exception
     *
     * @return  void
     */
    public function authenticate();
    /**#@-*/
}
