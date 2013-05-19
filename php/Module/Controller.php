<?php
/**
 * \php\Module\Controller.php
 *
 * @package     Core
 * @subpackage  Module
 * @category    Controller
 */
namespace HomeAI\Module;

use HomeAI\Auth;
use HomeAI\Core\Request;
use HomeAI\Util\JSON;

/**
 * Generic module controller class. All module controller classes must extend
 * this base class.
 *
 * @package     Core
 * @subpackage  Module
 * @category    Controller
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
abstract class Controller implements Interfaces\Controller
{
    /**#@+
     * View and model objects, these are created automatically if corresponding
     * classes are founded.
     *
     * @access  protected
     */

    /**
     * @var \HomeAI\Module\View
     */
    protected $view = null;

    /**
     * @var \HomeAI\Module\Model
     */
    protected $model = null;
    /**#@-*/

    /**
     * Request object.
     *
     * @access  protected
     * @var     \HomeAI\Core\Request
     */
    protected $request;

    /**
     * Request action module.
     *
     * @access  protected
     * @var     string
     */
    protected $module;

    /**
     * Request action of page.
     *
     * @access  protected
     * @var     string
     */
    protected $action;

    /**
     * Page data for current request
     *
     * @access  protected
     * @var     array
     */
    protected $pageData;

    protected $onlyAjax = false;

    /**
     * Construction of the class.
     *
     * @param   \HomeAI\Core\Request    $request
     * @param   string                  $module
     * @param   string                  $action
     * @param   array                   $pageData
     *
     * @return  \HomeAI\Module\Controller
     */
    public function __construct(Request &$request, &$module = null, &$action = null, &$pageData = array())
    {
        // Store request object and page
        $this->request  = $request;
        $this->module   = $module;
        $this->action   = $action;
        $this->pageData = $pageData;

        if (is_null($this->module)) {
            $bits = explode('\\', get_called_class());

            $this->module = $bits[2];
        }

        // Create login object
        new Auth\Login(false);

        // Define page View and Model object names
        $classes = array(
            'view'  => '\\HomeAI\\Module\\' . $this->module . '\\View',
            'model' => '\\HomeAI\\Module\\' . $this->module . '\\Model',
        );

        // Iterate classes and create objects
        foreach ($classes as $attribute => $class) {
            $this->{$attribute} = new $class($this->request, $this->module, $this->action, $this->pageData);
        }

        // Set model object to view
        $this->view->setModel($this->model);

        // Specify used initialize objects
        $initializes = array(
            $this,
            $this->view,
            $this->model,
        );

        // Iterate objects and check if 'initialize' -method exists.
        foreach ($initializes as $object) {
            // Method exists so run initialize
            if (method_exists($object, 'initialize')) {
                call_user_func(array($object, 'initialize'));
            }
        }
    }

    /**
     * Method returns current View object.
     *
     * @return \HomeAI\Module\View
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Method returns current Model object.
     *
     * @return \HomeAI\Module\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Common request handler for pages. All requests are routed via
     * this method.
     *
     * @access  public
     *
     * @param   array $pageData
     *
     * @return  void
     */
    public function handleRequest(array $pageData)
    {
        // Specify init methods to check
        $init = array(
            'initializeRequest',
            'initializeRequest'. $this->action,
        );

        // Iterate initialize methods and call them if founded
        foreach ($init as $method) {
            if (method_exists($this, $method)) {
                call_user_func(array($this, $method));
            }
        }

        // Create dynamic method name.
        $method = 'handleRequest' . $this->action;

        try {
            // Handler defined and founded
            if (!is_null($this->action) && method_exists($this, $method)) {
                // TODO: add check for $pageData and parameters for actual method to call

                call_user_func_array(array($this, $method), $pageData);
            } else { // Otherwise fallback to default request handler.
                $this->handleRequestDefault($pageData);
            }
        } catch (\Exception $error) {
            if ($this->request->isAjax()) {
                JSON::makeExceptionError($error);
            } else {
                $this->view->display($this->view->makeExceptionError($error));
            }
        }
    }

    /**
     * Common request for 404 pages.
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequest404()
    {
        $this->view->display($this->view->make404());
    }

    /**
     * Generic redirect method.
     *
     * @access  public
     *
     * @param   null|string $action Action where to redirect
     * @param   null|string $module Module where to redirect
     * @param   array       $params Used query parameters
     *
     * @return  void
     */
    public function redirect($action = null, $module = null, array $params = array())
    {
        if (is_null($action)) {
            $action = 'Default';
        }

        if (is_null($module)) {
            $module = $this->module;
        }

        $url = $this->request->getBaseUrl(false, true) . $module .'/'. $action;

        if (!empty($params)) {
            $url .= '?'. http_build_query($params);
        }

        header('Location: '. $url);
        exit(0);
    }
}
