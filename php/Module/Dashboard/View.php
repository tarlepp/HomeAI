<?php
/**
 * \php\Module\Dashboard\View.php
 *
 * @package     Module
 * @subpackage  Dashboard
 * @category    View
 */
namespace HomeAI\Module\Dashboard;

use HomeAI\Module\View as MView;

/**
 * View class for 'Dashboard' -Module.
 *
 * @package     Module
 * @subpackage  Dashboard
 * @category    View
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class View extends MView implements Interfaces\View
{
    /**
     * @var \HomeAI\Module\Dashboard\Model
     */
    protected $model;

    /**
     * Pre initialize of current module.
     *
     * @return  void
     */
    public function preInitializePage()
    {
        // TODO: determine what JS libraries user needs...

        $this->addJavascript('jQuery-dashboard/');
        $this->addJavascript('jQuery-jCounter/');
        $this->addJavascript('Highcharts/');

        $this->addCss('Widget.css');
    }

    /**
     * Method makes 'Dashboard' HTML content.
     *
     * @access  public
     *
     * @return  string
     */
    public function makeDashboard()
    {
        $template = $this->smarty->createTemplate('dashboard.tpl', $this->smarty);

        return $template->fetch();
    }

    /**
     * Method makes Dashboard template selection HTML content.
     *
     * @access  public
     *
     * @return  string
     */
    public function makeTemplates()
    {
        $template = $this->smarty->createTemplate('templates.tpl', $this->smarty);

        return $template->fetch();
    }
}
