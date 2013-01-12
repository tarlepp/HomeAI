<?php
/**
 * \php\Module\Interfaces\Common.php
 *
 * @package     Core
 * @subpackage  Module
 * @category    Interface
 */
namespace HomeAI\Module\Interfaces;

use HomeAI\Core\Request;

/**
 * Common interface for \HomeAI\Module\Model, View and Controller -classes.
 *
 * @package     Core
 * @subpackage  Module
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Common
{
    /**
     * Construction of the class.
     *
     * @param   \HomeAI\Core\Request    $request
     * @param   string                  $module
     * @param   string                  $action
     * @param   array                   $pageData
     *
     * @return  \HomeAI\Module\Interfaces\Common
     */
    public function __construct(Request &$request, &$module = null, &$action = null, &$pageData = array());
}
