<?php
/**
 * \php\Util\JSON.php
 *
 * @package     HomeAI
 * @subpackage  Util
 * @category    JSON
 */
namespace HomeAI\Util;

/**
 * This class contains JSON helper methods.
 *
 * @package     HomeAI
 * @subpackage  Util
 * @category    JSON
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class JSON implements Interfaces\JSON
{
    /**#@-
     * Used check types.
     *
     * @access  public
     * @type    constant
     * @var     int
     */
    const TYPE_ENCODE = 1;
    const TYPE_DECODE = 2;
    /**#@-*/

    /**#@-
     * Used header types.
     *
     * @access  public
     * @type    constant
     * @var     int
     */
    const HEADER_JSON = 1;
    const HEADER_JSONP = 1;
    /**#@-*/

    /**
     * Method makes JSON headers.
     *
     * @access  public
     * @static
     *
     * @param   integer $type   Used header type
     *
     * @return  void
     */
    public static function makeHeaders($type = self::HEADER_JSON)
    {
        switch ($type) {
            case self::HEADER_JSON:
                header('Content-Type: application/json');
                break;
            case self::HEADER_JSONP:
                header('Content-Type: application/javascript');
                break;
        }
    }

    /**
     * Method checks if JSON string is valid or not. Method will throw
     * an exception if string is not valid otherwise method returns
     * boolean true.
     *
     * @access  public
     * @static
     *
     * @param   string  $string JSON string to be checked
     *
     * @return  bool
     */
    public static function check($string)
    {
        JSON::decode($string);

        return true;
    }

    /**
     * Method encodes given data to JSON string with specified options. Note that
     * method throws an exception if encode operations fails for some reason.
     *
     * @access  public
     * @static
     *
     * @throws  Exception
     *
     * @param   mixed   $data       Data to be encoded
     * @param   int     $options    Used encode options
     *
     * @return  string              Encoded JSON string
     */
    public static function encode($data, $options = 0)
    {
        // Try to encode given data
        $output = json_encode($data, $options);

        JSON::checkJsonErrors(JSON::TYPE_ENCODE);

        return $output;
    }

    /**
     * Method decodes JSON string. Note that method throws an exception if
     * decode operations fails for some reason.
     *
     * @access  public
     * @static
     *
     * @throws  Exception
     *
     * @param   string  $string JSON string to be decoded
     * @param   bool    $assoc  Return as an assoc array or object
     *
     * @return  mixed           Decoded JSON data
     */
    public static function decode($string, $assoc = false)
    {
        // Try to decode given json data
        $output = json_decode($string, $assoc);

        JSON::checkJsonErrors(JSON::TYPE_DECODE);

        return $output;
    }

    /**
     * Method checks if JSON en/decode operations has failed. If so
     * method will throw an exception about that.
     *
     * @access  public
     * @static
     *
     * @throws  Exception
     *
     * @param   int $type
     *
     * @return  void
     */
    protected static function checkJsonErrors($type = JSON::TYPE_ENCODE)
    {
        // Check for any decoding errors.
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $message = "";
                break;
            case JSON_ERROR_DEPTH:
                $message = "The maximum stack depth has been exceeded.";
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $message = "Invalid or malformed JSON.";
                break;
            case JSON_ERROR_CTRL_CHAR:
                $message = "Control character error, possibly incorrectly encoded.";
                break;
            case JSON_ERROR_SYNTAX:
                $message = "Syntax error.";
                break;
            case JSON_ERROR_UTF8:
                $message = "Malformed UTF-8 characters, possibly incorrectly encoded.";
                break;
            default:
                $message = "Unknown error.";
                break;
        }

        // Error occurred
        if (!empty($message)) {
            if ($type === JSON::TYPE_ENCODE) {
                $title = "JSON data encoding error: ";
            } elseif ($type == JSON::TYPE_DECODE) {
                $title = "JSON data decoding error: ";
            } else {
                $title = "";
            }

            throw new Exception($title . $message);
        }
    }
}
