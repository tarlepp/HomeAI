<?php
/**
 * \php\Module\Widget\Interfaces\View.php
 *
 * @package     Module
 * @subpackage  Widget
 * @category    Interface
 */
namespace HomeAI\Module\Widget\Interfaces;

/**
 * Interface for \HomeAI\Module\Widget\View -class.
 *
 * @package     Module
 * @subpackage  Widget
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface View
{
    public function setWidgetData(array $widgetData);
    public function makeClock();
    public function makeEggTimer();
    public function makeRssFeed(array $items);
    public function makeHighcharts($id, $config, array $options);
    public function makeSetupMethodNotFound($widgetName, $methodName, $className, array $widget, array $data);
    public function makeSetupCurl(array $widget, array $data);
    public function makeSetupRss(array $widget, array $data);
}
