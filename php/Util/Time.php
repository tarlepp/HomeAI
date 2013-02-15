<?php
/**
 * \php\Util\Time.php
 *
 * @package     Util
 * @subpackage  Time
 * @category    Time
 */
namespace HomeAI\Util;

/**
 * Generic Time class.
 *
 * @package     Util
 * @subpackage  Time
 * @category    Time
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Time implements Interfaces\Time
{
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
    public static function toHumanReadable($ms)
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
