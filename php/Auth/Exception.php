<?php
/**
 * \php\Auth\Exception.php
 *
 * @package     HomeAI
 * @subpackage  Auth
 * @category    Exception
 */
namespace HomeAI\Auth;

use HomeAI\Core\Exception as CException;
use HomeAI\Util\Logger;

/**
 * Generic exception class for \HomeAI\Auth -classes.
 *
 * @package     HomeAI
 * @subpackage  Auth
 * @category    Exception
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Exception extends CException
{
    /**
     * Write error log or not.
     *
     * @var bool
     */
    protected $writeLog = false;

    /**
     * Construction of main exception class.
     *
     * @param   string      $message    Exception message
     * @param   integer     $code       Error code
     * @param   \Exception  $previous   Previous exception
     *
     * @return  \HomeAI\Auth\Exception
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        // Write query error log
        if ($code === 0) {
            Logger::write($this, Logger::TYPE_AUTH);
        }
    }
}
