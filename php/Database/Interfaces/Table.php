<?php
/**
 * \php\Database\Interfaces\Table.php
 *
 * @package     Database
 * @subpackage  Table
 * @category    Interface
 */
namespace HomeAI\Database\Interfaces;

/**
 * Interface for \HomeAI\Database\Table -class.
 *
 * @package     Database
 * @subpackage  Table
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Table
{
    /**
     * Method return defined table object. Note that table objects are returned
     * from cache if it's already initialized.
     *
     * @access  public
     * @static
     *
     * @param   string  $table  Name of the table.
     *
     * @return  \Doctrine\DBAL\Schema\Table
     */
    public static function getInstance($table);

    /**
     * Method returns actual Doctrine table object.
     *
     * @access  public
     *
     * @return  \Doctrine\DBAL\Schema\Table
     */
    public function getTableObject();
}
