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

// Require initialize file
require_once __DIR__ .'/../php/init.php';

$request = Request::getInstance();

// Handle current request via Router
Router::handleRequest($request);

exit(0);