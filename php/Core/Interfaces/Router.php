<?php
/**
 * \php\Core\Interfaces\iRouter.php
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Interface
 */
namespace HomeAI\Core\Interfaces;

use HomeAI\Core\Request;

defined('NETTIBAARI_INIT') OR die('No direct access allowed.');
/**
 * iRouter -interface
 *
 * Interface for \HomeAI\Core\Router -class.
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Router
{
    public static function handleRequest(Request &$request);
}
