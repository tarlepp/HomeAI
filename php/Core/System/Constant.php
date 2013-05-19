<?php
/**
 * \php\Core\System\Constant.php
 *
 * @package     Core
 * @subpackage  System
 * @category    Constant
 */
namespace HomeAI\Core\System;

use HomeAI\Util\Config;

/**
 * This class initializes used system constants.
 *
 * @package     Core
 * @subpackage  System
 * @category    Constant
 *
 * @date        $Date$
 * @version     $Rev$
 * @author      $Author$
 */
class Constant extends Component
{
    /**
     * Loading of the actual constants.
     *
     * @return  void
     */
    public function load()
    {
        // Define system base path
        define('PATH_BASE', dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR);

        if (!(defined('INIT_NO_CONSTANT') && constant('INIT_NO_CONSTANT'))) {
            foreach (Config::readIni('constants.ini') as $section => $data) {
                foreach ($data as $constant => $value) {
                    define(mb_strtoupper($section) .'_'. mb_strtoupper($constant), $value);
                }
            }
        }
    }
}
