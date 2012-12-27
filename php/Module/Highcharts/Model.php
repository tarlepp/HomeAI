<?php
/**
 * \php\Module\Highcharts\Model.php
 *
 * @package     Module
 * @subpackage  Highcharts
 * @category    Model
 */
namespace HomeAI\Module\Highcharts;

use HomeAI\Module\Model as MModel;

/**
 * Model class for 'Highcharts' -Module.
 *
 * @package     Module
 * @subpackage  Highcharts
 * @category    Model
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Model extends MModel implements Interfaces\Model
{
    /**
     * Method returns time / random number point for live highcharts
     * example.
     *
     * @param   int $points
     *
     * @return  array
     */
    public function getLiveData($points = 1)
    {
        $output = array();

        if ($points > 1) {
            $start = $points * -1;
            $time = time();

            for ($i = $start; $i <= 0; $i++) {
                $output[] = array(
                    'x' => floatval(($time + $i * 5) * 1000),
                    'y' => floatval(rand(0, 10)),
                );
            }

        } elseif ($points === 1) {
            $output = array(
                'x' => time() * 1000,
                'y' => rand(0, 10),
            );
        }

        return $output;
    }
}
