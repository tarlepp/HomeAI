<?php
/**
 * \php\Page\Interfaces\iView.php
 *
 * @package     Core
 * @subpackage  Page
 * @category    Interface
 */
namespace Nettibaari\Page\Interfaces;
use \Nettibaari\Page\Model;

defined('NETTIBAARI_INIT') OR die('No direct access allowed.');
/**
 * iView -interface
 *
 * Interface for \Nettibaari\Page\View -class.
 *
 * @package     Core
 * @subpackage  Page
 * @category    Controller
 *
 * @date        $Date: 2012-07-15 18:39:49 +0300 (Sun, 15 Jul 2012) $
 * @author      $Author: tle $
 * @revision    $Rev: 126 $
 */
interface iView extends iCommon
{
    public function setModel(Model &$model);
    public function setTitle($title);
    public function getSmarty();
    public function setPageImage($image);
    public function addKeyword($keyword);
    public function addJavascript($javascript, $append = true);
    public function addCss($css, $media = 'screen, projection', $append = true, $first = false);
    public function display($content = NULL, $template = 'index.tpl');
    public function makeExceptionError(\Exception $error);
    public function make404();
}
