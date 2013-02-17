<?php
/**
 * \php\Core\Interfaces\Autoload.php
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Interface
 */
namespace HomeAI\Core\Interfaces;

/**
 * Interface for \HomeAI\Core\Autoload -class.
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Autoload
{
    public function __construct();
    public function load($class);
}
