<?php
/**
 * \php\Module\Auth\Model.php
 *
 * @package     Module
 * @subpackage  Auth
 * @category    Model
 */
namespace HomeAI\Module\Auth;

use HomeAI\Module\Model as MModel;
use HomeAI\ORM\Manager;

/**
 * Model class for 'Auth' -Module.
 *
 * @package     Module
 * @subpackage  Auth
 * @category    Model
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Model extends MModel implements Interfaces\Model
{
    public function getEntity($entityName, array $criteria)
    {
        // Get Doctrine ORM entity manager object
        $entityManager = Manager::getManager();

        // Specify entity class name
        $entityClass = 'HomeAI\\Entities\\' . $entityName;

        // Entity class does not exists => error
        if (!class_exists($entityClass)) {
            $message = sprintf(
                "Cannot find entity class for '%s' entity.",
                $entityName
            );

            throw new Exception($message);
        }

        // Get entity repository
        $repository = $entityManager->getRepository($entityClass);

        // Find single entity by given criteria
        $entity = $repository->findOneBy($criteria);

        if (is_null($entity)) {
            $message = sprintf(
                "Cannot find valid '%s' entity by given criteria(s): '%s'",
                $entityName,
                implode("', '", $this->parseCriteria($criteria))
            );

            throw new Exception($message);
        }

        return $entity;
    }

    private function parseCriteria(array $criteria)
    {
        $output = array();

        foreach ($criteria as $key => $value) {
            $output[] = $key . ": " . $value;
        }

        return $output;
    }
}
