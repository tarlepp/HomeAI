<?php
/**
 * \php\Core\Request.php
 *
 * @package    HomeAI
 * @subpackage Core
 * @category   Request
 */
namespace HomeAI\Core;

defined('HOMEAI_INIT') OR die('No direct access allowed.');
/**
 * Request -class
 *
 * Generic request helper class which encapsulate request data into the one
 * object providing a single channel for request data access and manipulation.
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
     * Default page request.
     *
     * @access  private static
     * @var     string
     */
    private static $defaultPage = 'Main';

    /**
     * Default page action request.
     *
     * @access  private static
     * @var     string
     */
    private static $defaultAction = 'Default';


    /**
     * This method handles _all_ request for HomeAI -system. Basically method determines
     * what page and action user want to process.
     *
     * Actual response is made in specified page controller class.
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

        // Extract page data from current path
        $page = array_shift($pageData);
        $page = empty($page) ? self::$defaultPage : $page;

        // Extract page action data from current path
        $action = array_shift($pageData);
        $action = empty($action) ? self::$defaultAction : $action;

        // Specify used controller for current request
        $controller = "\\HomeAI\\Page\\" . $page . "\\Controller";

        // Check that asked controller exists
        if (!class_exists($controller)) {
            // Set default page
            $page = self::$defaultPage;

            // Specify default controller
            $controller = "\\HomeAI\\Page\\" . $page . "\\Controller";
            $action     = '404';
        }

        /**
         * Create page controller and handle defined request.
         *
         * @var     $pageController     \HomeAI\Page\Controller     This is for the 'smart' IDE
         */
        $pageController = new $controller($request, $page, $action, $pageData);
        $pageController->handleRequest();

        unset($pageController);
    }
}
