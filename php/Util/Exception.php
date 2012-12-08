<?php
/**
 * \php\Util\Exception.php
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Exception
 */
namespace HomeAI\Util;

use HomeAI\Core\Exception as CException;

/**
 * Exception -class
 *
 * Generic exception class for HomeAI -software. All Exception classes
 * must extend this class.
 *
 * Note that this class will write all errors to syslog
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Session
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Exception extends CException
{
}
