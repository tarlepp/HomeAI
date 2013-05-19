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
use HomeAI\Util\JSON;

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

    /**
     * Method handles 'Dashboard' -module 'GetTemplates' -action.
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestGetTemplates()
    {
        // Only allow AJAX request.
        if (!$this->request->isAjax()) {
            $this->redirect('');
        }

        echo $this->view->makeTemplates();
        exit(0);
    }

    /**
     * Method handles 'Dashboard' -module 'GetWidgets' -action.
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestGetWidgets()
    {
        // Only allow AJAX request.
        if (!$this->request->isAjax()) {
            $this->redirect('');
        }

        echo JSON::encode($this->model->getWidgets());
        exit(0);
    }

    /**
     * Method handles 'Dashboard' -module 'Update' -action.
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestUpdate()
    {
        // Only allow AJAX request.
        if (!$this->request->isAjax()) {
            $this->redirect('');
        }

        // Get dashboard settings
        $settings = $this->request->get('settings');

        $output = true;

        // Store current settings
        try {
            $this->model->setWidgets(array('result' => $settings));
        } catch (\Exception $error) {
            $output = array(
                'error'     => true,
                'message'   => $error->getMessage(),
            );
        }

        echo JSON::encode($output);
        exit(0);
    }

    /**
     * Method handles 'Dashboard' -module 'Reset' -action.
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestReset()
    {
        $this->model->resetWidgets();

        $this->redirect('');
        exit(0);
    }
}
