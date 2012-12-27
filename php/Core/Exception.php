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
use HomeAI\Util\JSON as JSON;

/**
 * Exception -class
 *
 * Generic exception class for HomeAI -software. All Exception classes
 * must extend this class.
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Session
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

    /**
     * Common method to convert HomeAI exception to "standard" JSON
     * error which is easily be usable in javascript.
     *
     * @return  void
     */
    public function makeJsonResponse()
    {
        $data = array(
            'message'   => $this->getMessage(),
            'code'      => $this->getCode(),
        );

        // If debug mode is on, writ some extra info, do we need a trace?
        if (defined('DEVELOPMENT_DEBUG') && constant('DEVELOPMENT_DEBUG')) {
            $data['file'] = $this->getFile();
            $data['line'] = $this->getLine();
        }

        header('Content-Type: application/json');
        echo JSON::encode($data);

        exit(0);
    }
}
