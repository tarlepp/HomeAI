<?php
/**
 * \php\Module\Dashboard\Interfaces\Controller.php
 *
 * @package     Module
 * @subpackage  Dashboard
 * @category    Interface
 */
namespace HomeAI\Module\Dashboard\Interfaces;

/**
 * Interface for \HomeAI\Module\Dashboard\Controller -class.
 *
 * @package     Module
 * @subpackage  Dashboard
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Controller
{
    public function handleRequestGetTemplates();
    public function handleRequestGetWidgets();
    public function handleRequestUpdate();
    public function handleRequestReset();
}
