<?php
/**
 * \php\Util\Config.php
 *
 * @package     Util
 * @subpackage  Config
 * @category    Config
 */
namespace HomeAI\Util;

/**
 * Config -class
 *
 * Common purpose config class.
 *
 * @package     Util
 * @subpackage  Config
 * @category    Config
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Config implements Interfaces\Config
{
    /**
     * Used config cache.
     *
     * @access  private
     * @static
     * @var     array
     */
    private static $cache = array();

    /**
     * Method tries to read specified ini file and returns it's contents
     * as an array.
     *
     * @access  public
     * @static
     *
     * @throws  \HomeAI\Util\Exception
     *
     * @param   string  $iniFile    Name of the ini file.
     * @param   string  $section    Ini file section
     * @param   string  $variable   Ini file section variable
     *
     * @return  array|string
     */
    public static function readIni($iniFile, $section = '', $variable = '')
    {
        // Specified ini file is not readable
        if (!is_readable(PATH_BASE . "config/" . $iniFile)) {
            throw new Exception("Defined ini file '" . $iniFile . "' not found!");
        }

        // Fetch requested data from cache
        $cachedValue = self::getCacheValue($iniFile, $section, $variable);

        // Cache value founded, so return that.
        if (!is_null($cachedValue)) {
            return $cachedValue;
        }

        // Parse specified ini file
        $data = parse_ini_file(PATH_BASE . "config/" . $iniFile, true);

        // Section and variable set
        if (!empty($section) && !empty($variable)) {
            // Specified section and variable not present
            if (!isset($data[$section][$variable])) {
                $message = sprintf(
                    "Cannot find requested config value '%s:%s' from '%s' -ini file.",
                    $section,
                    $variable,
                    $iniFile
                );

                throw new Exception($message);
            }

            $output = & $data[$section][$variable];
        } elseif (!empty($section)) { // Section set
            // Specified section not present
            if (!isset($data[$section])) {
                $message = sprintf(
                    "Cannot find requested config section '%s' from '%s' -ini file.",
                    $section,
                    $iniFile
                );

                throw new Exception($message);
            }

            $output = &$data[$section];
        } else { // Otherwise return full config
            $output = &$data;
        }

        // Set output to cache
        self::setCacheValue($iniFile, $section, $variable, $output);

        return $output;
    }

    /**
     * Method returns cached config value if it is present.
     *
     * @access  private
     * @static
     *
     * @param   string  $iniFile    Name of the ini file.
     * @param   string  $section    Ini file section
     * @param   string  $variable   Ini file section variable
     *
     * @return  string|void
     */
    private static function getCacheValue(&$iniFile, &$section, &$variable)
    {
        // Get cache key
        $key = self::getCacheKey($iniFile, $section, $variable);

        return (isset(self::$cache[$key])) ? self::$cache[$key] : null;
    }

    /**
     * Method sets cache value.
     *
     * @access   private
     * @static
     *
     * @param   string  $iniFile    Name of the ini file.
     * @param   string  $section    Ini file section
     * @param   string  $variable   Ini file section variable
     * @param   string  $data       Value to set cache
     *
     * @return  void
     */
    private static function setCacheValue(&$iniFile, &$section, &$variable, &$data)
    {
        // Set cache key
        $key = self::getCacheKey($iniFile, $section, $variable);

        self::$cache[$key] = $data;
    }

    /**
     * Method returns cache key value.
     *
     * @access  private
     * @static
     *
     * @param   string  $iniFile    Name of the ini file.
     * @param   string  $section    Ini file section
     * @param   string  $variable   Ini file section variable
     *
     * @return  string              Cache key
     */
    private static function getCacheKey(&$iniFile, &$section, &$variable)
    {
        return trim((string)$iniFile . (string)$section . (string)$variable);
    }
}
