<?php
/**
 * \php\Core\System\Interfaces\Component.php
 *
 * @package     Core
 * @subpackage  System
 * @category    Interface
 */
namespace HomeAI\Core\System\Interfaces;

/**
 * Common interface for all \HomeAI\Core\System\* -classes.
 *
 * @package     Core
 * @subpackage  System
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Component
{
    public function __construct();
    public function load();
}
