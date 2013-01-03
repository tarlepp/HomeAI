<?php
/**
 * \php\Core\Router.php
 *
 * @package    HomeAI
 * @subpackage Core
 * @category   Request
 */
namespace HomeAI\Core;

/**
 * Router -class
 *
 * General router class. Basically all request are routed via this class.
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Request
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Router implements Interfaces\Router
{
    /**
     * Default module definition
     *
     * @access  private static
     * @var     string
     */
    private static $defaultModule = 'Dashboard';

    /**
     * Default module action definition
     *
     * @access  private static
     * @var     string
     */
    private static $defaultAction = 'Default';

    /**
     * This method handles _all_ request for HomeAI -system. Basically method determines
     * what module and action user want to process.
     *
     * Actual response is made in specified module controller class.
     *
     * @access  public static
     *
     * @param   \HomeAI\Core\Request    $request
     *
     * @return  void
     */
    public static function handleRequest(Request &$request)
    {
        // Get path info from current url and remove extra '/' characters from it
        $pageData = array_filter(explode('/', preg_replace('#/+#', '/', $request->getPathInfo())));

        if (basename($request->getBaseUrl()) === 'homeai.php') {
            $url = $request->getBaseUrl(false, true);

            header("Location: ". mb_substr($url, 0, mb_strpos($url, 'homeai.php')));
            exit(0);
        }

        // Extract page data from current path
        $module = array_shift($pageData);
        $_module = $request->get('module', null);



        if (empty($module) && !is_null($_module)) {
            $module = $_module;
        } elseif (empty($module)) {
            $module =  self::$defaultModule;
        }

        // Extract page action data from current path
        $action = array_shift($pageData);
        $_action = $request->get('action', null);

        if (empty($action) && !is_null($_action)) {
            $action = $_action;
        } elseif (empty($action)) {
            $action =  self::$defaultAction;
        }

        // Specify used controller for current request
        $controller = "\\HomeAI\\Module\\" . $module . "\\Controller";

        // Check that asked controller exists
        if (!class_exists($controller)) {
            // Set default page
            $module = self::$defaultModule;

            // Specify default controller
            $controller = "\\HomeAI\\Module\\" . $module . "\\Controller";
            $action     = "404";
        }

        /**
         * Create page controller and handle defined request.
         *
         * @var     $moduleController     \HomeAI\Module\Controller     This is for the 'smart' IDE
         */
        $moduleController = new $controller($request, $module, $action, $pageData);
        $moduleController->handleRequest($pageData);

        unset($moduleController);
    }
}
