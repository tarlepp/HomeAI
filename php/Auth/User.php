<?php
/**
 * \php\Auth\User.php
 *
 * @package     HomeAI
 * @subpackage  Auth
 * @category    User
 */
namespace HomeAI\Auth;

use HomeAI\Database\DB;

/**
 * Generic user model class.
 *
 * @package     HomeAI
 * @subpackage  Auth
 * @category    User
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class User implements Interfaces\User
{
    /**#@-
     * User user properties types.
     *
     * @var integer
     */
    const TYPE_STRING = 1;
    const TYPE_BOOLEAN = 2;
    const TYPE_INTEGER = 3;
    const TYPE_DATETIME = 4;
    /**#@-*/

    /**
     * Actual user data array. This contains key / value pairs of
     * user data. Keys are same as in $this->properties and values
     * are type casted to class TYPE_ -constants.
     *
     * @var array
     */
    protected $data = array();

    /**
     * User model property names and types. Note that this class
     * does not accept any other properties that these.
     *
     * @var array
     */
    protected $properties = array(
        'ID'        => User::TYPE_INTEGER,
        'Username'  => User::TYPE_STRING,
        'Firstname' => User::TYPE_STRING,
        'Surname'   => User::TYPE_STRING,
        'Email'     => User::TYPE_STRING,
        'Password'  => User::TYPE_STRING,
        'Created'   => User::TYPE_DATETIME,
        'Modified'  => User::TYPE_DATETIME,
        'Status'    => User::TYPE_BOOLEAN,
    );

    /**
     * Construction of the class.
     */
    public function __construct()
    {
    }

    /**
     * Magic method to set class properties. These properties are
     * basically actual user data.
     *
     * @throws  \HomeAI\Auth\Exception
     *
     * @param   string  $name   Name of the property.
     * @param   mixed   $value  Value of the property
     *
     * @return  void
     */
    public function __set($name, $value)
    {
        // Unknown user property
        if (!array_key_exists($name, $this->properties)) {
            $message = sprintf(
                "Unknown '%s' -property for class '%s'.",
                $name,
                __CLASS__
            );

            throw new Exception($message);
        }

        // Format and set data to object
        $this->data[$name] = call_user_func_array(array($this, 'formatData'), array($name, $value));
    }

    /**
     * Magic method to get class properties. These properties are
     * basically actual user data.
     *
     * @throws  \HomeAI\Auth\Exception
     *
     * @param   string  $name   Name of the property.
     *
     * @return  mixed
     */
    public function __get($name)
    {
        // Unknown user property
        if (!array_key_exists($name, $this->data)) {
            $message = sprintf(
                "Property '%s' not defined.",
                $name
            );

            throw new Exception($message);
        }

        return $this->data[$name];
    }

    /**
     * Magic method to check if certain user property isset.
     *
     * @param   string  $name   Name of the property.
     *
     * @return  mixed
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * Method returns current user role.
     *
     * @return  integer One of the \HomeAI\Auth\Roles -class constants.
     */
    public function getRole()
    {
        // TODO: Implement getRole() method.
    }

    /**
     * Method return boolean true if current user is admin.
     *
     * @return  boolean
     */
    public function isAdmin()
    {
        // TODO: Implement isAdmin() method.
    }


    /**
     * Method formats given value according to type of it.
     *
     * @throws  Exception
     *
     * @param   string  $name   Name of the property.
     * @param   mixed   $value  Actual property value.
     *
     * @return  bool|\DateTime|int|string   Formatted property value.
     */
    protected function formatData($name, $value)
    {
        switch ($this->properties[$name]) {
            case self::TYPE_STRING:
                $output = (string)$value;
                break;
            case self::TYPE_BOOLEAN:
                $output = (bool)$value;
                break;
            case self::TYPE_INTEGER:
                $output = (int)$value;
                break;
            case self::TYPE_DATETIME:
                $output = new \DateTime($value);
                break;
            default:
                $message = sprintf(
                    "Value format for '%s' not yet defined.",
                    $name
                );

                throw new Exception($message);
                break;
        }

        return $output;
    }
}
