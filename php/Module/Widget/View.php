<?php
/**
 * \php\Module\Widget\View.php
 *
 * @package     Module
 * @subpackage  Widget
 * @category    View
 */
namespace HomeAI\Module\Widget;

use HomeAI\Module\View as MView;

/**
 * View class for 'Widget' -Module.
 *
 * @package     Module
 * @subpackage  Widget
 * @category    View
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class View extends MView implements Interfaces\View
{
    /**
     * @var \HomeAI\Module\Widget\Model
     */
    protected $model;

    /**
     * Current widget data from Dashboard.js
     *
     * @var array
     */
    protected $widgetData;

    /**
     * Setter for widget data.
     *
     * @param   array   $widgetData
     *
     * @return  void
     */
    public function setWidgetData(array $widgetData)
    {
        $this->widgetData = $widgetData;
    }

    /**
     * Method makes 'Clock' widget HTML content.
     *
     * @access  public
     *
     * @return  string  HTML content for "Clock" widget
     */
    public function makeClock()
    {
        $template = $this->smarty->createTemplate('widget_clock.tpl', $this->smarty);

        return $template->fetch();
    }

    /**
     * Method makes 'Egg Timer' widget HTML content.
     *
     * @access  public
     *
     * @return  string  HTML content for "Egg Timer" widget
     */
    public function makeEggTimer()
    {
        $template = $this->smarty->createTemplate('widget_eggtimer.tpl', $this->smarty);

        return $template->fetch();
    }

    /**
     * Method makes 'RSS' widget HTML content.
     *
     * @param   array   $items  RSS items to shown
     *
     * @return  string          HTML content for RSS widget
     */
    public function makeRssFeed(array $items)
    {
        // Create template and assign data to it
        $template = $this->smarty->createTemplate('widget_rss.tpl', $this->smarty);
        $template->assign('items', $items);

        return $template->fetch();
    }

    /**
     * Method makes 'Highcharts' widget HTML content.
     *
     * @access  public
     *
     * @param   string  $id         Used highcharts id
     * @param   string  $config     Highcharts config JSON string
     * @param   array   $options    Used extra options/parameters for template
     *
     * @return  string              HTML content for highcharts widget
     */
    public function makeHighcharts($id, $config, array $options)
    {
        // Create template and assign data to it
        $template = $this->smarty->createTemplate('widget_highcharts.tpl', $this->smarty);
        $template->assign('id', $id);
        $template->assign('config', $config);
        $template->assign('options', $options);
        $template->assign('widget', $this->widgetData);

        return $template->fetch();
    }
}
