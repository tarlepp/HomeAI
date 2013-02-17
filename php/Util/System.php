<?php
/**
 * \php\Util\System.php
 *
 * @package     HomeAI
 * @subpackage  Util
 * @category    System
 */
namespace HomeAI\Util;

/**
 * System specified class which contains method to get different
 * information about current system.
 *
 * @package     HomeAI
 * @subpackage  Util
 * @category    System
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class System implements Interfaces\System
{
    /**
     * Method returns current memory usage.
     *
     * @param   bool    $format Return memory usage as human readable format
     *
     * @return  integer|string
     */
    public static function getMemoryUsage($format = true)
    {
        $size = memory_get_usage();

        if ($format) {
            $unit = array('b','kb','mb','gb','tb','pb');

            $size = number_format(round($size / pow(1024, ($i = (int)floor(log($size, 1024)))), 2), 2);
            $size .= $unit[$i];
        }

        return $size;
    }

    /**
     * Method returns current process time from the HomeAI start.
     *
     * @param   bool $format
     *
     * @return  string
     */
    public static function getProcessTime($format = true)
    {
        $time = bcsub(str_replace(',', '.', microtime(true)), TIMESTART, 4);

        if ($format === true) {
            $time = bcmul($time, 1000);
            $time = self::toHumanReadable($time);
        }

        return $time;
    }

    /**
     * Method converts given millisecond time to human readable format.
     *
     * @param   integer $ms
     *
     * @return  string
     */
    protected static function toHumanReadable($ms)
    {
        $ms = abs($ms);
        $ss = floor($ms / 1000);
        $ms = $ms % 1000;
        $mm = floor($ss / 60);
        $ss = $ss % 60;
        $hh = floor($mm / 60);
        $mm = $mm % 60;
        $dd = floor($hh / 24);
        $hh = $hh % 24;

        $data = array(
            'dd' => 'd',
            'hh' => 'h',
            'mm' => 'm',
            'ss' => 's',
            'ms' => 'ms',
        );

        $output = '';

        foreach ($data as $variable => $unit) {
            if (isset(${$variable}) && ${$variable} > 0) {
                $output .= ${$variable} . $unit ." ";
            }
        }

        return $output;
    }
}
