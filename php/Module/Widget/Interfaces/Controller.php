<?php
/**
 * \php\Module\Widget\Interfaces\Controller.php
 *
 * @package     Module
 * @subpackage  Widget
 * @category    Interface
 */
namespace HomeAI\Module\Widget\Interfaces;

/**
 * Interface for \HomeAI\Module\Widget\Controller -class.
 *
 * @package     Module
 * @subpackage  Widget
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Controller
{
    public function handleRequestClock();
    public function handleRequestEggTimer();
    public function handleRequestCurl();
    public function handleRequestRss();
    public function handleRequestHighcharts();
    public function handleRequestGetCategories();
    public function handleRequestGetCategoryWidgets($category);
    public function handleRequestSetup($widgetName);
    public function widgetSetupCurl(array $widget, array $data);
    public function widgetSetupRss(array $widget, array $data);
}
