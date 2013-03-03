<?php
/**
 * \php\Auth\Interfaces\User.php
 *
 * @package     HomeAI
 * @subpackage  Auth
 * @category    Interface
 */
namespace HomeAI\Auth\Interfaces;

/**
 * Interface for \HomeAI\Auth\User -class.
 *
 * @package     HomeAI
 * @subpackage  Auth
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface User
{
    /**
     * Construction of the class.
     */
    public function __construct();

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
    public function __set($name, $value);

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
    public function __get($name);

    /**
     * Magic method to check if certain user property isset.
     *
     * @param   string  $name   Name of the property.
     *
     * @return  mixed
     */
    public function __isset($name);

    /**
     * Method returns current user role.
     *
     * @return  integer One of the \HomeAI\Auth\Roles -class constants.
     */
    public function getRole();

    /**
     * Method return boolean true if current user is admin.
     *
     * @return  boolean
     */
    public function isAdmin();
}
