<?php
/**
 * \php\Database\Interfaces\DB.php
 *
 * @package     Database
 * @subpackage  DB
 * @category    Interface
 */
namespace HomeAI\Database\Interfaces;

/**
 * Interface for \HomeAI\Database\DB -class.
 *
 * @package     Database
 * @subpackage  DB
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface DB
{
    /**
     * Instance getter method.
     *
     * @access  public
     * @static
     *
     * @uses    \HomeAI\Database\DB::__construct()
     *
     * @return  \HomeAI\Database\DB
     */
    public static function getInstance();

    /**
     * Method returns Doctrine schema object.
     *
     * @access  public
     *
     * @return  \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    public function getSchema();

    /**
     * Method returns Doctrine table object.
     *
     * @access  public
     *
     * @param   string  $table  Name of the database table.
     *
     * @return  \Doctrine\DBAL\Schema\Table
     */
    public function getTable($table);

    /**
     * Method returns specified table primary key columns. Note that return
     * value varies if table contains more than one (1) primary key columns.
     *
     * @access  public
     *
     * @param   string  $table  Table specification.
     *
     * @return  string|array    String or array which contains table
     *                          primary key column names.
     */
    public function getTablePrimaryKey($table);

    /**
     * Method makes custom SQL query with defined bindings to database. This
     * will always return PDO statement IF the specified query was executed
     * successfully. If query fails method will return boolean false.
     *
     * @access  public
     *
     * @throws  \HomeAI\Database\Exception
     *
     * @param   string  $query      SQL query to execute
     * @param   array   $bindings   Used query bindings
     *
     * @return  \PDOStatement
     */
    public function query($query, $bindings = array());

    /**
     * Method makes generic SELECT sql query with specified data. This method
     * is very usefully when you need to fetch data from single table.
     *
     * @access   public
     *
     * @throws  \HomeAI\Database\Exception
     *
     * @param   string      $table          Name of the database table
     * @param   mixed       $columns        Columns to select this may be:
     *                                       NULL   = all columns
     *                                       string = single column
     *                                       array  = specified columns
     * @param   mixed       $conditions     Used query conditions
     * @param   int|string  $type           Select return type, one the DB::SELECT_* -constants
     *
     * @return  mixed                       Return value depends on specified $type -argument.
     */
    public function select($table, $columns = null, $conditions = array(), $type = \HomeAI\Database\DB::SELECT_ROWS);

    /**
     * Generic INSERT method. With this you can easily insert specified data
     * to the desired database table.
     *
     * @access  public
     *
     * @throws  \HomeAI\Database\Exception
     *
     * @param   string  $table  Database table name
     * @param   array   $data   Data to insert
     *
     * @return  integer         Inserted id if all was ok.
     */
    public function insert($table, $data);

    /**
     * Generic UPDATE method. With this you can easily update specified data
     * to the desired database table with specified conditions.
     *
     * @access  public
     *
     * @throws  \HomeAI\Database\Exception
     *
     * @param   string          $table      Database table name
     * @param   array           $data       Data to update
     * @param   array|integer   $conditions Conditions for update
     *
     * @return  integer                     Updated row count if all was ok.
     */
    public function update($table, $data, $conditions);

    /**
     * Generic DELETE method. With this you can easily delete specified table
     * rows from database with desired conditions.
     *
     * @access  public
     *
     * @throws  \HomeAI\Database\Exception
     *
     * @param   string          $table      Database table name
     * @param   integer|array   $conditions Conditions for delete
     *
     * @return  integer                     Deleted row count if all was ok.
     */
    public function delete($table, $conditions);
}
