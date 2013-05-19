<?php
/**
 * \php\Core\Exception.php
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Exception
 */
namespace HomeAI\Core;

use HomeAI\Util\Logger as Logger;

/**
 * Exception -class
 *
 * Generic exception class for HomeAI -software. All Exception classes
 * must extend this class.
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Exception
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Exception extends \Exception
{
    /**
     * Write error log or not.
     *
     * @var bool
     */
    protected $writeLog = true;

    /**
     * Construction of main exception class.
     *
     * @param   string      $message    Exception message
     * @param   integer     $code       Error code
     * @param   \Exception  $previous   Previous exception
     *
     * @return  \HomeAI\Core\Exception
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        if ($this->writeLog) {
            Logger::write($this, Logger::TYPE_ERROR);
        }
    }
}
