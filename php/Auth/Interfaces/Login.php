<?php
/**
 * \php\Auth\Interfaces\Password.php
 *
 * @package     Auth
 * @subpackage  Login
 * @category    Interface
 */
namespace HomeAI\Auth\Interfaces;

/**
 * Interface for \HomeAI\Auth\Login -class.
 *
 * @package     Auth
 * @subpackage  Login
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Login
{
    /**
     * Construction of the class.
     *
     * @access  public
     *
     * @param   boolean     $logout
     *
     * @return  \HomeAI\Auth\Interfaces\Login
     */
    public function __construct($logout = true);
    public static function isAuthenticated();
}
