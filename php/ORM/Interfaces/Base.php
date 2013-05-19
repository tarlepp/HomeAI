<?php
/**
 * \php\ORM\Interfaces\Base.php
 *
 * @package     HomeAI
 * @subpackage  ORM
 * @category    Interface
 */
namespace HomeAI\ORM\Interfaces;

use HomeAI\ORM\Exception;

/**
 * Interface for \HomeAI\ORM\Base -class.
 *
 * @package     HomeAI
 * @subpackage  ORM
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Base
{
    /**
     * Construction of the class.
     */
    public function __construct();

    /**
     * Magic method which is triggered when invoking inaccessible methods in an object context.
     *
     * @throws  Exception
     *
     * @param   string  $method     Name of called method
     * @param   array   $parameters Possible method parameters as an array
     *
     * @return  Base|string|integer|\DateTime|boolean
     */
    public function __call($method, array $parameters);

    /**
     * Method return all the entity properties as an assoc array where
     * key present entity property name.
     *
     * @return  array
     */
    public function getAllProperties();
}
