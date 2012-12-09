<?php
/**
 * \php\Page\Main\Controller.php
 *
 * @package     Module
 * @subpackage  Main
 * @category    Controller
 */
namespace HomeAI\Module\Main;

use HomeAI\Module\Controller as MController;

/**
 * Controller class for 'Main' -module.
 *
 * @package     Module
 * @subpackage  Main
 * @category    Controller
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Controller extends MController implements Interfaces\Controller
{
    /**
     * @var \HomeAI\Module\Main\View
     */
    protected $view = null;

    /**
     * @var \HomeAI\Module\Main\Model
     */
    protected $model = null;

    /**
     * Method handles 'Main' -module default action.
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestDefault()
    {
        $this->view->display($this->view->makeMain());
    }
}
