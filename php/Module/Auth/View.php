<?php
/**
 * \php\Module\Auth\View.php
 *
 * @package     Module
 * @subpackage  Auth
 * @category    View
 */
namespace HomeAI\Module\Auth;

use HomeAI\Module\View as MView;

/**
 * View class for 'Auth' -Module.
 *
 * @package     Module
 * @subpackage  Auth
 * @category    View
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class View extends MView implements Interfaces\View
{
    /**
     * @var \HomeAI\Module\Auth\Model
     */
    protected $model;

    /**
     * Method makes login form and returns it.
     *
     * @return  string
     */
    public function makeLogin()
    {
        $template = $this->smarty->createTemplate('login.tpl', $this->smarty);

        return $template->fetch();
    }
}
