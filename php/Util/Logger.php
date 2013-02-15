<?php
/**
 * \php\Util\Logger.php
 *
 * @package     HomeAI
 * @subpackage  Util
 * @category    Logger
 */
namespace HomeAI\Util;

/**
 * Logger -class
 *
 * General logger class to log different types of messages to syslog.
 *
 * @package     HomeAI
 * @subpackage  Util
 * @category    Logger
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Logger implements Interfaces\Logger
{
    /**
     * Used message variable
     *
     * @access  private
     * @var     string
     */
    private $message = '';
    private $logFile = '';

    /**
     * Array for used log files
     *
     * @access  private
     * @var     array
     */
    private $logFiles = array(
        Logger::TYPE_ERROR  => 'error.log',
        Logger::TYPE_DEBUG  => 'debug.log',
        Logger::TYPE_QUERY  => 'query.log',
        Logger::TYPE_AUTH   => 'auth.log',
        Logger::TYPE_INFO   => 'info.log',
    );

    /**#@-
     * Used logger types, these are used to specify title for debug message.
     *
     * @access  public
     * @type    constant
     * @var     string
     */
    const TYPE_ERROR = 'Error';
    const TYPE_DEBUG = 'Debug';
    const TYPE_QUERY = 'Query';
    const TYPE_AUTH  = 'Auth';
    const TYPE_INFO  = 'Info';
    /**#@-*/

    /**
     * Method formats defined log message from passed exception object.
     *
     * @access  public
     * @static
     *
     * @param   \Exception|string|array $message    Log message
     * @param   null|string             $type       Type, see Logger::TYPE_* constants
     *
     * @return  void
     */
    public static function write($message, $type = null)
    {
        if (defined('BASE_PATH') && constant('BASE_PATH')) {
            new self($message, $type);
        }
    }

    /**
     * Construction of the class.
     *
     * @access  protected
     *
     * @param   \Exception|string|array $message    Log message
     * @param   null|string             $type       Type, see Logger::TYPE_* constants
     *
     * @return  \HomeAI\Util\Logger
     */
    protected function __construct($message, $type)
    {
        // Invalid log type
        if (!is_null($type) && !array_key_exists($type, $this->logFiles)) {
            return;
        }

        // Format message
        $this->format($message, $type);

        if (!is_readable($this->logFile)) {
            touch($this->logFile);
            chmod($this->logFile, 0777);
        }

        // Write error log
        if (is_writable($this->logFile)) {
            error_log($this->message, 3, $this->logFile);
        } else {
            echo "Specified LOG file: '". $this->logFile ."' is not writable.";
        }
    }

    /**
     * Method formats defined log message from passed exception object.
     *
     * access   protected
     *
     * @param   \Exception|string|array $message    Log message
     * @param   null|string             $type       Type, see Logger::TYPE_* constants
     *
     * @return  void
     */
    protected function format($message, $type)
    {
        $debugMessage = '';
        $backtrace = debug_backtrace();

        // Message is an exception
        if ($message instanceof \Exception) {
            $debugMessage = get_class($message)  .": ". $message->getMessage();
            $backtrace = $message->getTrace();
        } elseif (is_array($message)) {
            $debugMessage = "Array:\n". var_export($message, true);
        }

        // Make debug message string
        $this->message = sprintf(
            "%s '%s'\n%s\nBacktrace:\n%s",
            $this->getStamp(),
            is_null($type) ? self::TYPE_ERROR : $type,
            $debugMessage,
            $this->parseTrace($backtrace)
        );

        $file = is_null($type) ? $this->logFiles[self::TYPE_ERROR] : $this->logFiles[$type];

        $this->logFile = PATH_BASE ."logs/". $file;
    }

    /**
     * Method parses exception backtrace and returns it as string.
     *
     * @access  protected
     *
     * @param   array   $trace  Debug backtrace
     *
     * @return  string
     */
    protected function parseTrace($trace)
    {
        // Initialize output
        $output = '';

        $i = 1;

        // Iterate trace
        foreach ($trace as $v) {
            // We are only interest if file and line are defined
            if (!isset($v['file']) || empty($v['line'])) {
                continue;
            }

            $output .= sprintf(
                "   %d. %s:%d\n",
                ($i),
                $v['file'],
                $v['line']
            );

            $i++;
        }

        return $output;
    }

    /**
     * Method get current timestamp with microseconds for debug messages.
     *
     * @access  protected
     *
     * @return  string      Current stamp for log entry
     */
    protected function getStamp()
    {
        list($usec, $sec) = explode(" ", microtime());

        return date('Y-m-d H:i:s', $sec) . str_pad(substr($usec, 1, 7), 7, '0', STR_PAD_LEFT);
    }
}
