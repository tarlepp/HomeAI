<?php
/**
 * \php\Core\System\Component.php
 *
 * @package     Core
 * @subpackage  System
 * @category    Initializer
 */
namespace HomeAI\Core\System;

/**
 * Base system component class.
 *
 * @package     Core
 * @subpackage  System
 * @category    Initializer
 *
 * @date        $Date$
 * @version     $Rev$
 * @author      $Author$
 */
abstract class Component implements Interfaces\Component
{
    /**
     * Construction of the class.
     */
    public function __construct()
    {
        $this->load();
    }
}
