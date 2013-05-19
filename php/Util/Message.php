<?php
/**
 * \php\Uti\Message.php
 *
 * @package     HomeAI
 * @subpackage  Util
 * @category    Message
 */
namespace HomeAI\Util;

/**
 * Class to set different messages to message queue to be shown to users.
 *
 * @package     HomeAI
 * @subpackage  Util
 * @category    Message
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Message implements Interfaces\Message
{
    /**#@+
     *  Message type constants
     *
     * @access     public
     * @type       constant
     * @var        string
     */
    const TYPE_OK      = 'success';
    const TYPE_ERROR   = 'error';
    const TYPE_WARNING = 'warning';
    /**#@-*/

    /**
     * Method sets an error message which will be displayed in current page.
     *
     * @access  public
     * @static
     *
     * @param   string  $message    Error message
     * @param   string  $title      Error message title, not required
     *
     * @return  void
     */
    public static function setError($message, $title = null)
    {
        Message::setMessage(Message::TYPE_ERROR, $message, $title);
    }

    /**
     * Method sets an warning message which will be displayed in current page.
     *
     * @access  public
     * @static
     *
     * @param   string  $message    Warning message
     * @param   string  $title      Warning message title, not required
     *
     * @return  void
     */
    public static function setWarning($message, $title = null)
    {
        Message::setMessage(Message::TYPE_WARNING, $message, $title);
    }

    /**
     * Method sets an ok message which will be displayed in current page.
     *
     * @access  public
     * @static
     *
     * @param   string  $message    Ok message
     * @param   string  $title      Ok message title, not required
     *
     * @return  void
     */
    public static function setOk($message, $title = null)
    {
        Message::setMessage(Message::TYPE_OK, $message, $title);
    }

    /**
     * Method saves message to session.
     *
     * @access  private
     * @static
     *
     * @param   string  $type       Message type, see \Util\Message::TYPE_* -constants.
     * @param   string  $message    Message
     * @param   string  $title      Message title
     *
     * @return  void
     */
    private static function setMessage($type, &$message, &$title)
    {
        // Messages are not yet initialized
        if (!isset($_SESSION['Message'][$type])) {
            $_SESSION['Message'][$type] = array();
        }

        // Add message data to session
        $_SESSION['Message'][$type][] = array(
            'message' => $message,
            'title'   => $title,
        );
    }
}
