<?php
/**
 * \php\Auth\Password.php
 *
 * @package     HomeAI
 * @subpackage  Auth
 * @category    Password
 */
namespace HomeAI\Auth;

/**
 * Password authentication class.
 *
 * @package     HomeAI
 * @subpackage  Auth
 * @category    Password
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Password extends Base
{
    /**
     * Current auth object initializer method.
     *
     * @return  void
     */
    public function initializeAuth()
    {
        $bits = array(
            dirname(dirname(dirname(__FILE__))),
            'libs',
            'password_compat',
            'lib',
        );

        // Require password_compat -library
        require_once implode(DIRECTORY_SEPARATOR, $bits) . DIRECTORY_SEPARATOR . "password.php";
    }

    /**
     * Actual auth object authenticate method. This will check that given
     * username + password are valid. If not method will throw an exception.
     *
     * @throws  Exception
     *
     * @return  void
     */
    public function authenticate()
    {
        // Given password is valid, user is valid
        if (password_verify($this->password, $this->user->getPassword()) === true) {
            $this->authStatus = true;

            return;
        }

        // Password doesn't match with stored hash.
        $message = sprintf(
            "Invalid username or password...",
            $this->username
        );

        throw new Exception($message);
    }
}
