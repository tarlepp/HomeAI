<?php
/**
 * \html\check.php
 *
 * This script is used to check current system that it can run HomeAI program.
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Core
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */

use HomeAI\Check\Controller as Check;

/**
 * We do not want to initialize whole system at this point
 */
define('INIT_NO_CONSTANT', true);
define('INIT_NO_DATABASE', true);
define('INIT_NO_SESSION', true);

try {
    // Require initialize file
    require_once __DIR__ .'/../php/init.php';

    $checker = new Check();
    $checker->doChecks();
} catch (\Exception $error) {
    echo "<h1>Error</h1>";
    echo "<p>". $error->getMessage() ."</p>";
}

exit(0);
