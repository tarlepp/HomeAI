<?php
/**
 * \php\init.php
 *
 * This file contains all necessary actions for HomeAI core initialization. Basically
 * file contains different init function calls so that system will work as designed.
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Core
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
use \HomeAI\Core\System;

// HomeAI autoload classes
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR .'Core/Interfaces/Autoload.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR .'Core/Autoload.php';

// Register HomeAI autoload functionality
spl_autoload_register(array(new \HomeAI\Core\Autoload(), 'load'));

// Initialize HomeAI
System::initialize();
