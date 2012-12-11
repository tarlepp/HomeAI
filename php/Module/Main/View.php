<?php
/**
 * \php\Module\Main\View.php
 *
 * @package     Module
 * @subpackage  Main
 * @category    View
 */
namespace HomeAI\Module\Main;

use HomeAI\Module\View as MView;

/**
 * View class for 'Main' -Module.
 *
 * @package     Module
 * @subpackage  Main
 * @category    View
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class View extends MView implements Interfaces\View
{
    /**
     * @var \HomeAI\Module\Main\Model
     */
    protected $model;

    /**
     * Method makes 'Main' HTML content.
     *
     * @access  public
     *
     * @return  string
     */
    public function makeMain()
    {
        $template = $this->smarty->createTemplate('dashboard.tpl', $this->smarty);

        return $template->fetch();
    }
}
