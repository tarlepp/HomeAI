<?php
/**
 * \scripts\doctrine.php
 *
 * Doctrine Command Line Interface. Usage from cli:
 * 
 *  php -q doctrine.php
 *
 * @package     HomeAI
 * @subpackage  Scripts
 * @category    Scripts
 */
namespace HomeAI\Scripts;

// Import used Doctrine classes
use Doctrine\ORM\Tools\Setup;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use HomeAI\ORM\Manager;
use Symfony\Component\Console\Helper\HelperSet;

// Specify application base path
$basePath = dirname(dirname(__FILE__));

// Required application init
require_once $basePath . '/php/init.php';

// Specify Doctrine base path
$lib = $basePath . '/libs/Doctrine';

// Require Doctrine Setup class
require $lib . '/Doctrine/ORM/Tools/Setup.php';

// Register Doctrine autoload
Setup::registerAutoloadDirectory($lib);

// Get entity manager
$entityManager = Manager::getManager();

// Create helper set for console command
$helperSet = new HelperSet(
    array(
        'db' => new ConnectionHelper($entityManager->getConnection()),
        'em' => new EntityManagerHelper($entityManager)
    )
);

// Run console command
ConsoleRunner::run($helperSet);
