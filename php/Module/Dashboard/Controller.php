<?php
/**
 * \php\Module\Dashboard\Controller.php
 *
 * @package     Module
 * @subpackage  Dashboard
 * @category    Controller
 */
namespace HomeAI\Module\Dashboard;

use HomeAI\Module\Controller as MController;

/**
 * Controller class for 'Dashboard' -module.
 *
 * @package     Module
 * @subpackage  Dashboard
 * @category    Controller
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Controller extends MController implements Interfaces\Controller
{
    /**
     * @var \HomeAI\Module\Dashboard\View
     */
    protected $view;

    /**
     * @var \HomeAI\Module\Dashboard\Model
     */
    protected $model;

    /**
     * Method handles 'Dashboard' -module default action.
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestDefault()
    {
        $this->view->display($this->view->makeDashboard());
    }

    public function handleRequestGetTemplates()
    {
        echo $this->view->makeTemplates();
        exit(0);
    }

    public function handleRequestGetMyWidgets()
    {
        echo json_encode($this->model->getMyWidgets());
    }
}
