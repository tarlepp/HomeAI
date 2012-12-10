<?php
/**
 * \php\Auth\Login.php
 *
 * @package     Auth
 * @subpackage  Login
 * @category    Auth
 */
namespace HomeAI\Auth;

use HomeAI\Core\Request;
use HomeAI\Util\Logger;
use HomeAI\Util\Network;

/**
 * Login -class
 *
 * Class to validate login data. Basically this class just checks if defined user session
 * data exists or not. If all required session data are present user is logged in to system.
 *
 * Note that class object is created in \HomeAI\Page\Controller -class automatically in
 * every page load.
 *
 * Login status check is very simple with following example:
 *
 *  if (\HomeAI\Auth\Login::isAuthenticated()) {
 *      \\ user is logged in.
 *  }
 *
 * @package     Auth
 * @subpackage  Login
 * @category    Auth
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Login implements Interfaces\Login
{
    /**
     * Current user authenticate status.
     *
     * @access  public static
     * @var     boolean
     */
    public static $auth = false;

    /**#@+
     * Meta fields types
     *
     * @access  public
     * @type    constant
     * @var     integer
     */
    const TYPE_INTEGER = 1;
    const TYPE_STRING  = 2;
    /**#@-*/

    /**
     * Required session meta data.
     *
     * @access  protected
     * @var     array
     */
    protected $meta = array(
        'ID'        => Login::TYPE_INTEGER,
        'Name'      => Login::TYPE_STRING,
        'Email'     => Login::TYPE_STRING,
        'SessionId' => Login::TYPE_STRING,
        'IP'        => Login::TYPE_STRING,
        'Agent'     => Login::TYPE_STRING,
    );

    /**
     * Request object.
     *
     * @access  protected
     * @var     \HomeAI\Core\Request
     */
    protected $request = null;

    /**
     * Construction of the class.
     *
     * @access  public
     *
     * @param   boolean     $logout
     *
     * @return  \HomeAI\Auth\Login
     */
    public function __construct($logout = true)
    {
        // Try to validate current user
        try {
            // Get request object
            $this->request = Request::getInstance();

            // Do necessary checks
            $this->checkSessionData();
            $this->checkSiteInfo();

            Login::$auth = true;
        } catch (Exception $error) {
            // User not logged in
            Login::$auth = false;

            if ($logout === true) {
                $this->logout($error);
            }
        }
    }

    /**
     * Method returns boolean true if current user is logged on the system.
     *
     * @access  public static
     *
     * @return  boolean
     */
    public static function isAuthenticated()
    {
        return self::$auth;
    }

    /**
     * Method checks that all necessary user session data are se and those
     * are "right" type.
     *
     * @access  protected
     *
     * @throws  \HomeAI\Auth\Exception
     *
     * @uses    \HomeAI\Core\Request::getSession()
     *
     * @return  void
     */
    protected function checkSessionData()
    {
        // Get user session data.
        $sessionData = $this->request->getSession('User');

        // User session data is not present.
        if (!is_array($sessionData)) {
            throw new Exception("Required User session is not present.", 1);
        }

        $message = array();

        // Check session meta data
        foreach ($this->meta as $field => $type) {
            if (!isset($sessionData[$field])) {
                $message[] = "Required User session data '" . $field . "' is not present.";

                continue;
            }

            $data = $sessionData[$field];

            switch ($type) {
                case Login::TYPE_INTEGER:
                    if ((int)$data < 1) {
                        $message[] = "User session data '" . $field . "' must be greater than zero.";
                    }
                    break;
                case Login::TYPE_STRING:
                    if (is_null($data)) {
                        $message[] = "User session data '" . $field . "' cannot be NULL.";
                    } elseif (empty($data)) {
                        $message[] = "User session data '" . $field . "' cannot be empty.";
                    }
                    break;
                default:
                    $message[] = "Unknown meta type '" . $type . "'.";
                    break;
            }

            unset($data);
        }

        unset($sessionData);

        if (count($message) > 0) {
            throw new Exception(implode("\n", $message), 1);
        }
    }

    /**
     * Method checks logged user site info against current situation.
     *
     * @access     protected
     *
     * @throws     \HomeAI\Auth\Exception
     *
     * @return     void
     */
    protected function checkSiteInfo()
    {
        $message = array();

        // Define site data to check
        $data = array(
            'ip'    => Network::getIp(),
            'agent' => Network::getAgent(),
        );

        // Iterate data and compare them to user session data
        foreach ($data as $key => $value) {
            if (strcmp($this->request->getSession(array('User', $key)), $value) !== 0) {
                $message[] = sprintf("Mismatch in '%s' -data.", $key);
            }
        }

        if (count($message) > 0) {
            throw new Exception(implode("\n", $message));
        }
    }

    /**
     * Method performs user logout procedure. This is basically just a
     * redirection to /Admin/Logout -action
     *
     * @access  protected
     *
     * @return  void
     */
    protected function logout()
    {
        // Redirect user to logout action
        header("Location: " . $this->request->getBaseUrl(false, true) . "Admin/Logout");
        exit(0);
    }
}
