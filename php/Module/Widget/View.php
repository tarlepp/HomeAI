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
     * Method makes 'Clock' widget HTML content.
     *
     * @access  public
     *
     * @return  string
     */
    public function makeClock()
    {
        $template = $this->smarty->createTemplate('widget_clock.tpl', $this->smarty);

        return $template->fetch();
    }

    public function makeEggTimer()
    {
        $template = $this->smarty->createTemplate('widget_eggtimer.tpl', $this->smarty);

        return $template->fetch();
    }

    /**
     * Method makes 'RSS' widget HTML content.
     *
     * @param   array   $items
     *
     * @return  string
     */
    public function makeRssFeed(array $items)
    {
        // Create template and assign data to it
        $template = $this->smarty->createTemplate('widget_rss.tpl', $this->smarty);
        $template->assign('items', $items);

        return $template->fetch();
    }
}
