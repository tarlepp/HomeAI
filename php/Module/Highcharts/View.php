<?php
/**
 * \php\Module\Highcharts\View.php
 *
 * @package     Module
 * @subpackage  Highcharts
 * @category    View
 */
namespace HomeAI\Module\Highcharts;

use HomeAI\Module\View as MView;

/**
 * View class for 'Highcharts' -Module.
 *
 * @package     Module
 * @subpackage  Highcharts
 * @category    View
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class View extends MView implements Interfaces\View
{
    /**
     * @var \HomeAI\Module\Highcharts\Model
     */
    protected $model;

    /**
     * Method makes Highcharts config JSON data string for example chart.
     *
     * @param   string  $renderTo   Where to draw chart
     *
     * @return  string
     */
    public function makeJsonExample($renderTo)
    {
        // Create template and assign data to it
        $template = $this->smarty->createTemplate('example.json', $this->smarty);
        $template->assign('renderTo', $renderTo);

        return $template->fetch();
    }

    /**
     * Method makes Highcharts config JSON data string for example live chart.
     *
     * @param   string  $renderTo   Where to draw chart
     *
     * @return  string
     */
    public function makeJsonExampleLive($renderTo)
    {
        // Create template and assign data to it
        $template = $this->smarty->createTemplate('example_live.json', $this->smarty);
        $template->assign('renderTo', $renderTo);

        return $template->fetch();
    }
}
