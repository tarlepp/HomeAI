<?php
/**
 * \php\ORM\Entity.php
 *
 * @package     HomeAI
 * @subpackage  ORM
 * @category    ORM
 */
namespace HomeAI\ORM;

/**
 * Generic ORM helper class. This class contains static method to get specified entity or repository
 * from ORM.
 *
 * @package     HomeAI
 * @subpackage  ORM
 * @category    ORM
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class ORM implements Interfaces\ORM
{
    /**
     * Getter method for entity repository.
     *
     * @throws  Exception
     *
     * @param   string  $name   Name of the entity repository
     *
     * @return  \Doctrine\ORM\EntityRepository
     */
    public static function getRepository($name)
    {
        // Get Doctrine ORM entity manager object
        $entityManager = Manager::getManager();

        // Specify entity class name
        $entityClass = 'HomeAI\\ORM\\Entities\\' . $name;

        // Entity class does not exists => error
        if (!class_exists($entityClass)) {
            $message = sprintf(
                "Cannot find entity class for '%s' entity.",
                $name
            );

            throw new Exception($message);
        }

        // Return entity repository
        return $entityManager->getRepository($entityClass);
    }

    /**
     * Getter method for single entity with specified criteria.
     *
     * @throws  Exception
     *
     * @param   string  $name           Name of the entity
     * @param   array   $criteria       Used search criteria for specified entity
     *
     * @return  \HomeAI\ORM\Base|null   Returns null if entity not found.
     */
    public static function getEntity($name, array $criteria)
    {
        return self::getRepository($name)->findOneBy($criteria);
    }
}
