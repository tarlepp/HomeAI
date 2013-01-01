<?php
/**
 * \php\Check\Interfaces\View.php
 *
 * @package     Core
 * @subpackage  Check
 * @category    Interface
 */
namespace HomeAI\Check\Interfaces;

use HomeAI\Check\Model;

/**
 * Interface for \HomeAI\Check\View -class.
 *
 * @package     Core
 * @subpackage  Page
 * @category    Controller
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface View
{
    public function __construct();
    public function makeHeader();
    public function makeFooter();
    public function makeSectionHeader($section);
    public function makeSectionFooter($section);
    public function makeCheck(array $check, $result, $error = '');
}
