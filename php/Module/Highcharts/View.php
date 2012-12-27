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

    public function makeJsonExample($renderTo)
    {
        // Create template and assign data to it
        $template = $this->smarty->createTemplate('example.json', $this->smarty);
        $template->assign('renderTo', $renderTo);

        return $template->fetch();
    }

    public function makeJsonNetworkIo($renderTo)
    {
        // Create template and assign data to it
        $template = $this->smarty->createTemplate('networkio.json', $this->smarty);
        $template->assign('renderTo', $renderTo);

        return $template->fetch();
    }

    /**
     * @param $renderTo
     * @return mixed
     */
    public function makeJsonCpu($renderTo)
    {
        // Create template and assign data to it
        $template = $this->smarty->createTemplate('cpu.json', $this->smarty);
        $template->assign('renderTo', $renderTo);

        return $template->fetch();
    }
}
