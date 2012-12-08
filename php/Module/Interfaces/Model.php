<?php
/**
 * \php\Page\Interfaces\iModel.php
 *
 * @package     Core
 * @subpackage  Page
 * @category    Interface
 */
namespace Nettibaari\Page\Interfaces;

defined('NETTIBAARI_INIT') OR die('No direct access allowed.');
/**
 * iModel -interface
 *
 * Interface for \Nettibaari\Page\Model -class.
 *
 * @package     Core
 * @subpackage  Page
 * @category    Controller
 *
 * @date        $Date: 2012-06-02 18:01:42 +0300 (Sat, 02 Jun 2012) $
 * @author      $Author: tle $
 * @revision    $Rev: 4 $
 */
interface iModel extends iCommon
{
    public function getOptionsSubstances($showSelect = false, $showEmpty = false);
    public function getOptionsUnit($showSelect = false, $showEmpty = false);
    public function getOptionsCategories($showSelect = false, $showEmpty = false);
    public function getOptionsProcesses($showSelect = false, $showEmpty = false);
    public function getOptionsGlassTypes($showSelect = true, $showEmpty = false);
}
