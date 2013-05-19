<?php
/**
 * \php\ORM\Interfaces\Manager.php
 *
 * @package     HomeAI
 * @subpackage  ORM
 * @category    Interface
 */
namespace HomeAI\ORM\Interfaces;

use Doctrine\ORM\EntityManager;

/**
 * Interface for \HomeAI\ORM\Manager -class.
 *
 * @package     HomeAI
 * @subpackage  ORM
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Manager
{
    /**
     * Normal getter method for Doctrine ORM Entity manager.
     *
     * @return  EntityManager
     */
    public function getEntityManager();
}
