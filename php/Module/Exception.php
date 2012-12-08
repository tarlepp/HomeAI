<?php
/**
 * \php\Module\Exception.php
 *
 * @package     HomeAI
 * @subpackage  Module
 * @category    Exception
 */
namespace HomeAI\Module;

use HomeAI\Core\Exception as CException;

/**
 * Generic Exception class for \HomeAI\Module -classes. All individual module
 * Exception classes must extend this.
 *
 * @package     HomeAI
 * @subpackage  Module
 * @category    Exception
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Exception extends CException
{
}
