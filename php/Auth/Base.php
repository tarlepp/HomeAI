<?php
/**
 * \php\Auth\Base.php
 *
 * @package     HomeAI
 * @subpackage  Auth
 * @category    Auth
 */
namespace HomeAI\Auth;

use HomeAI\Core\Request;
use HomeAI\ORM\ORM;
use HomeAI\Util\JSON;
use HomeAI\Util\Network;

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
     * @access  protected
     * @var     string
     */
    protected $username = null;
    protected $password = null;
    /**#@-*/

    /**
     * User object.
     *
     * @access  protected
     * @var     \HomeAI\ORM\Entities\User
     */
    protected $user;

    /**
     * Current auth status.
     *
     * @var bool
     */
    protected $authStatus = false;

    /**
     * Request object to use.
     *
     * @var Request
     */
    protected $request;

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

        // Create request object
        $this->request = Request::getInstance();

        // Determine user entity
        $this->determineUser();

        // Initialize current authentication type.
        $this->initializeAuth();
    }

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
    public function login()
    {
        if (!$this->authStatus) {
            throw new Exception("Invalid auth status cannot make login...");
        }

        // Reset session array
        $_SESSION = array();

        // Regenerate session id, this is for security reasons!
        session_regenerate_id(true);

        // Define session data to store current user
        $sessionData = array_merge(
            $this->user->getAllProperties(),
            array(
                'sessionId' => session_id(),
                'ip'        => Network::getIp(),
                'agent'     => Network::getAgent(),
            )
        );

        // Save user data to session
        $this->request->setSession('User', $sessionData);

        if ($this->request->isAjax()) {
            JSON::makeHeaders();

            echo JSON::encode(true);
        } else {
            // Redirect user
            header('Location: ' . $this->request->getBaseUrl(false, true));
        }

        exit(0);
    }

    /**
     * Static method to make user logout process. Basically method reset session data,
     * removes used session cookie and finally destroys current session.
     *
     * After this, user is redirected to application base url or if request is made
     * via AJAX boolean true value is outputted to client in JSON format.
     *
     * @return  void
     */
    public static function logout()
    {
        // Reset session data.
        $_SESSION = array();

        // If session cookie is present we must set it to past time.
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }

        // Destroy current session
        session_destroy();

        if (Request::getInstance()->isAjax()) {
            JSON::makeHeaders();

            echo JSON::encode(true);
        } else {
            // Redirect user
            header('Location: ' . Request::getInstance()->getBaseUrl(false, true));
        }

        exit(0);
    }

    /**
     * Method fetches user entity object by specified username. If user is not found
     * method will thrown an exception about that.
     *
     * @throws  Exception
     *
     * @return  void
     */
    private function determineUser()
    {
        $this->user = ORM::getEntity('User', array('username' => $this->username));

        if (is_null($this->user)) {
            $message = "Invalid username...";

            throw new Exception($message);
        }
    }
}
