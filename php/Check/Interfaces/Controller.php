<?php
/**
 * \php\Check\Interfaces\Controller.php
 *
 * @package     Core
 * @subpackage  Check
 * @category    Interface
 */
namespace HomeAI\Check\Interfaces;

/**
 * Interface for \HomeAI\Check\Controller -class.
 *
 * @package     Core
 * @subpackage  Page
 * @category    Controller
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Controller
{
    public function __construct();
    public function doChecks();
}
