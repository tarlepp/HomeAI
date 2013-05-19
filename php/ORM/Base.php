<?php
/**
 * \php\ORM\Base.php
 *
 * @package     HomeAI
 * @subpackage  ORM
 * @category    ORM
 */
namespace HomeAI\ORM;

use Doctrine\ORM\Mapping\Column;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;

/**
 * Abstract base class for all the ORM entity classes. All the ORM entity classes _must_ extend
 * this base class.
 *
 * @package     HomeAI
 * @subpackage  ORM
 * @category    ORM
 */
abstract class Base implements Interfaces\Base
{
    /**
     * Entity columns as an assoc array. Key presents entity property name
     * and value has entity ORM annotation information
     *
     * @var \Doctrine\ORM\Mapping\Column[]
     */
    protected $columns = array();

    /**
     * Is init done or not.
     *
     * @var bool
     */
    protected $initDone = false;

    /**
     * Construction of the class.
     */
    public function __construct()
    {
        $this->initialize();
    }

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
    public function __call($method, array $parameters)
    {
        // Initialize ORM
        $this->initialize();

        $prefix = mb_substr($method, 0, 3);

        // We are only interest with set/get method calls.
        if (strcmp($prefix, 'get') === 0 || strcmp($prefix, 'set') === 0) {
            // Specify property name
            $property = lcfirst(mb_substr($method, 3));

            // Check that property is valid.
            $this->checkProperty($property);

            if (strcmp($prefix, 'get') === 0) {
                return $this->{$property};
            } else {
                $this->{$property} = current($parameters);

                return $this;
            }

        } else {
            $message = sprintf(
                "Class '%s' doesn't have method called '%s'.",
                get_called_class(),
                $method
            );

            throw new Exception($message);
        }
    }

    /**
     * Method return all the entity properties as an assoc array where
     * key present entity property name.
     *
     * @return  array
     */
    public function getAllProperties()
    {
        // Initialize output
        $output = array();

        // Iterate entity columns
        foreach ($this->columns as $column => $properties) {
            // Determine getter method for column
            $method = 'get' . ucfirst($column);

            // Call getter method and store value to output
            $output[$column] = call_user_func(array($this, $method));
        }

        return $output;
    }

    /**
     * Method initializes doctrine ORM entity to use. Basically we want to store
     * all entity properties (column attributes) for later usage.
     *
     * @return  void
     */
    protected function initialize()
    {
        // Make init if not done.
        if ($this->initDone) {
            return;
        }

        // Create reflection about current object
        $reflection = new \ReflectionClass($this);

        $reader = new AnnotationReader();

        // Get all private properties, these are basically table columns
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PROTECTED) as $property) {
            $annotation = $reader->getPropertyAnnotation($property, 'Doctrine\ORM\Mapping\Column');

            if ($annotation instanceof Column) {
                $this->columns[$property->getName()] = $annotation;
            }
        };

        unset($reader, $reflection);

        $this->initDone = true;
    }

    /**
     * Method checks if specified entity property is valid or not.
     *
     * @throws  Exception
     *
     * @param   string  $propertyName   Name of the property
     *
     * @return  bool
     */
    private function checkProperty($propertyName)
    {
        if (array_key_exists($propertyName, $this->columns)) {
            return true;
        }

        // Otherwise throw an exception about invalid property
        $message = sprintf(
            "Entity class '%s' doesn't have '%s' property.",
            get_called_class(),
            $propertyName
        );

        throw new Exception($message);
    }
}
