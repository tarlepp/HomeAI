<?php
/**
 * \php\Module\Interfaces\Controller.php
 *
 * @package     Core
 * @subpackage  Module
 * @category    Interface
 */
namespace HomeAI\Module\Interfaces;

/**
 * Interface for \HomeAI\Module\Controller -class.
 *
 * @package     Core
 * @subpackage  Page
 * @category    Controller
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Controller extends Common
{
    /**#@-
     * Basic methods
     */

    /**
     * Method returns current View object.
     *
     * @return \HomeAI\Module\View
     */
    public function getView();

    /**
     * Method returns current Model object.
     *
     * @return \HomeAI\Module\Model
     */
    public function getModel();

    /**
     * Common request handler for pages. All requests are routed via
     * this method.
     *
     * @access  public
     *
     * @param   array   $pageData
     *
     * @return  void
     */
    public function handleRequest(array $pageData);

    /**
     * Common request for 404 pages.
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequest404();

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
    public function redirect($action = null, $module = null, array $params = array());
    /**#@-*/

    /**#@-
     * Methods that must be implemented in child classes
     */

    /**
     * Method handles default action for current module.
     *
     * @return void
     */
    public function handleRequestDefault();
    /**#@-*/
}
