<?php
/**
 * \php\Auth\Base.php
 *
 * @package     HomeAI
 * @subpackage  Auth
 * @category    Auth
 */
namespace HomeAI\Auth;

/**
 * Base authentication class. All authentication classes must extend
 * this abstract base class.
 *
 * @package     HomeAI
 * @subpackage  Auth
 * @category    Auth
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
abstract class Base implements Interfaces\Base
{
    /**#@+
     * Username and password from submitted login form.
     *
     * @access  public
     * @var     string
     */
    public $username = null;
    public $password = null;
    /**#@-*/

    /**
     * User data from database as an array.
     *
     * @access  protected
     * @var     array
     */
    protected $userData = array();

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
     * @return  \HomeAI\Auth\Base
     */
    public function __construct($username = null, $password = null)
    {
        // Store username and password
        $this->username = $username;
        $this->password = $password;

        // Initialize current authentication type.
        $this->initializeAuth();
    }

    /**
     * Setter for user data.
     *
     * @access  public
     *
     * @param   array   $data   User data.
     *
     * @return  void
     */
    public function setUserData(&$data)
    {
        $this->userData = $data;
    }

    /**
     * Getter for user data.
     *
     * @access  public
     *
     * @return  array
     */
    public function getUserData()
    {
        return $this->userData;
    }
}
