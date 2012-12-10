<?php
/**
 * \php\Database\Interfaces\Schema.php
 *
 * @package     Database
 * @subpackage  Schema
 * @category    Interface
 */
namespace HomeAI\Database\Interfaces;

/**
 * Interface for \HomeAI\Database\Schema -class.
 *
 * @package     Database
 * @subpackage  Schema
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Schema
{
    /**
     * Instance getter method.
     *
     * @access  public
     * @static
     *
     * @return  \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    public static function getInstance();

    /**
     * Method returns schema object.
     *
     * @access  public
     *
     * @return  \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    public function getSchemaObject();
}
