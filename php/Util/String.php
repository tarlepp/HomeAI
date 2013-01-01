<?php
/**
 * \php\Util\String.php
 *
 * @package     Util
 * @subpackage  String
 * @category    String
 */
namespace HomeAI\Util;

/**
 * This class contains common string modifiers methods.
 *
 * @package     Util
 * @subpackage  String
 * @category    String
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class String implements Interfaces\String
{
    /**
     * Method cleans given string from extra whitespaces, html tags and possible
     * other non alphabetic and numeric characters.
     *
     * @access  public
     * @static
     *
     * @param   string  $string         String to clean
     * @param   bool    $removeAll      Remove all non-alphabetic and numeric characters
     * @param   string  $allowedTags    String of allowed HTML tags
     *
     * @return  string                  Cleaned string
     */
    public static function clean($string, $removeAll = false, $allowedTags = '')
    {
        // Remove extra whitespaces and not allowed tags
        $string = String::removeWhiteSpace(strip_tags($string, $allowedTags), false);

        // Remove all non alphabetic and numeric characters
        if ($removeAll === true) {
            $string = str_replace(array('ä', 'Ä', 'ö', 'Ö', 'å', 'Å'), array('a', 'A', 'o', 'O', 'a', 'A'), $string);
            $string = preg_replace('/[^a-z0-9_\-]/i', '_', $string);
        }

        return $string;
    }

    /**
     * Method removes whitespaces from specified string.
     *
     * @access  public
     * @static
     *
     * @param   string  $string         String to clean
     * @param   string  $replacement    Replacement string
     * @param   bool    $onlyDuplicates Only duplicate whitespaces
     *
     * @return  string                  Cleaned string
     */
    public static function removeWhiteSpace($string, $replacement = '', $onlyDuplicates = false)
    {
        return preg_replace(($onlyDuplicates ? '#\s\s+#' : '#\s+#'), $replacement, trim($string));
    }

    /**
     * Method parses standard doc block to key / value array.
     *
     * @access  public
     * @static
     *
     * @param   string  $string String to parse
     *
     * @return  array
     */
    public static function parseDocBlock($string)
    {
        $output = array();

        if (preg_match_all('/@(\w+)\s+(.*)\r?\n/m', $string, $matches)) {
            foreach ($matches[1] as $index => $name) {
                if (!isset($output[$name])) {
                    $output[$name] = $matches[2][$index];
                } elseif (!is_array($output[$name])) {
                    $output[$name] = array(
                        $output[$name],
                        $matches[2][$index],
                    );
                } else {
                    $output[$name][] = $matches[2][$index];
                }
            }
        }

        return $output;
    }
}
