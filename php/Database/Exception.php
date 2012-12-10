<?php
/**
 * \php\Database\Exception.php
 *
 * @package     HomeAI
 * @subpackage  Database
 * @category    Exception
 */
namespace HomeAI\Database;

use HomeAI\Core\Exception as CException;
use HomeAI\Util\Logger;

/**
 * Exception class for \HomeAI\Database -classes.
 *
 * @package     HomeAI
 * @subpackage  Database
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
     * @return  \HomeAI\Database\Exception
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        // Write query error log
        Logger::write($this, Logger::TYPE_QUERY);
    }
}
