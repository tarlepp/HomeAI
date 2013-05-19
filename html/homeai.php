<?php
/**
 * \html\homeai.php
 *
 * All system request are handled via this file. Basicly this file initializes
 * system libraries to use and routes user to desired page.
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Core
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
use \HomeAI\Core\Router;
use \HomeAI\Core\Request;

// We want to show all errors.
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

// Specify HomeAI initialize time
define('TIMESTART', str_replace(',', '.', microtime(true)));

try {
    // Require initialize file
    require_once __DIR__ .'/../php/init.php';

    $request = Request::getInstance();

    // Handle current request via Router
    Router::handleRequest($request);
} catch (\Exception $error) {
    echo "
    <html>
        <head>
        <style type='text/css'>
            body {
                font-size: 12px;
            }

            h1 {
                font-size: 16px;
            }
        </style>
        </head>
        <body>
        <h1>Exception '". get_class($error) ."' was not catched</h1>
        <pre><code>". $error->__toString() ."</code></pre>
        </body>
    </html>
    ";
}

exit(0);
