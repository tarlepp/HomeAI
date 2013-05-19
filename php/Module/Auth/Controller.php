<?php
/**
 * \php\Module\Auth\Controller.php
 *
 * @package     Module
 * @subpackage  Auth
 * @category    Controller
 */
namespace HomeAI\Module\Auth;

use HomeAI\Module\Controller as MController;
use HomeAI\Core\Exception as CException;
use HomeAI\Util\JSON;
use HomeAI\Auth\Login;
use HomeAI\Auth\Password;

/**
 * Controller class for 'Auth' -module.
 *
 * @package     Module
 * @subpackage  Auth
 * @category    Controller
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Controller extends MController implements Interfaces\Controller
{
    /**
     * @var \HomeAI\Module\Auth\View
     */
    protected $view;

    /**
     * @var \HomeAI\Module\Auth\Model
     */
    protected $model;

    /**
     * Method handles 'Auth' -module default action.
     *
     * @access  public
     *
     * @throws  Exception
     *
     * @return  void
     */
    public function handleRequestDefault()
    {
        if (!Login::$auth) {
            $content = $this->view->makeLogin();

            if ($this->request->isAjax()) {
                echo $content;
            } else {
                $this->view->display($content);
            }
        } else {
            if ($this->request->isAjax()) {
                throw new Exception('You are already logged in...');
            } else {
                header("Location: " . $this->request->getBaseUrl(false, true));
            }
        }

        exit(0);
    }

    /**
     * Method handles 'Auth' -module 'Login' action.
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestLogin()
    {
        // Get request username and password
        $username = (string)$this->request->get('username', '');
        $password = (string)$this->request->get('password', '');

        // Create auth object and try to authenticate user
        $auth = new Password($username, $password);
        $auth->authenticate();
        $auth->login();
    }

    /**
     * Method handles 'Auth' -module 'Logout' action.
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestLogout()
    {
        \HomeAI\Auth\Base::logout();

        die(__FILE__ . ":" . __LINE__);
    }
}
