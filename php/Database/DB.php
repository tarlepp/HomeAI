<?php
/**
 * \php\Database\DB.php
 *
 * @package     Database
 * @subpackage  DB
 * @category    Database
 */
namespace HomeAI\Database;

use HomeAI\Util\Logger;

/**
 * Database handler class. This class is used to make SQL queries
 * to database.
 *
 * @package     Database
 * @subpackage  DB
 * @category    Database
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class DB implements Interfaces\DB
{
    /**
     * Singleton class variable.
     *
     * @access  protected
     * @static
     *
     * @var     \HomeAI\Database\DB
     */
    protected static $instance = null;

    /**
     * Actual Doctrine connection object.
     *
     * @access  protected
     * @var     \Doctrine\DBAL\Connection
     */
    protected $connection = null;

    /**#@+
     * Used select query types
     *
     * @access  public
     * @type    constant
     * @var     integer
     */
    const SELECT_ROW     = 1;
    const SELECT_ROWS    = 2;
    const SELECT_COLUMN  = 3;
    const SELECT_COLUMNS = 4;
    /**#@-*/

    /**
     * Construction of class.
     *
     * @access  protected
     *
     * @uses    \HomeAI\Database\Connection::getConnection()
     *
     * @return  \HomeAI\Database\DB
     */
    protected function __construct()
    {
        // Create database connection
        $this->connection = Connection::getConnection();
    }


    /**#@+
     * Start of static -methods.
     *
     * @access  public
     */

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
    public static function getInstance()
    {
        if (!self::$instance instanceof DB) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * End of static -methods
     */
    /**#@-*/


    /**#@+
     * Start of public -methods.
     *
     * @access     public
     */

    /**
     * Method returns Doctrine schema object.
     *
     * @access  public
     *
     * @return  \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    public function getSchema()
    {
        return Schema::getInstance();
    }

    /**
     * Method returns Doctrine table object.
     *
     * @access  public
     *
     * @param   string  $table  Name of the database table.
     *
     * @return  \Doctrine\DBAL\Schema\Table
     */
    public function getTable($table)
    {
        return Table::getInstance($table);
    }

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
    public function getTablePrimaryKey($table)
    {
        // Determine table primary key columns
        $columns = Table::getInstance($table)->getPrimaryKey()->getColumns();

        return (count($columns) === 1) ? current($columns) : $columns;
    }

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
    public function query($query, $bindings = array())
    {
        // Determine used query bindings and binding types
        list($_bindings, $_types) = $this->determineQueryBindings($bindings);

        // Execute actual query.
        return $this->connection->executeQuery($query, $_bindings, $_types);
    }

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
    public function select($table, $columns = null, $conditions = array(), $type = DB::SELECT_ROWS)
    {
        $output = null;

        // Generate actual select clause, used bindings and types.
        list($_query, $_bindings, $_types) = $this->makeQuerySelect($table, $columns, $conditions);

        // Execute actual query
        $stmt = $this->connection->executeQuery($_query, $_bindings, $_types);

        // Determine desired output format.
        switch ($type) {
            case DB::SELECT_ROW:
                $output = $stmt->fetch(\PDO::FETCH_ASSOC);
                break;
            case DB::SELECT_ROWS:
                $output = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                break;
            case DB::SELECT_COLUMN:
                $output = $stmt->fetchColumn();
                break;
            case DB::SELECT_COLUMNS:
                $output = $stmt->fetchAll(\PDO::FETCH_COLUMN);
                break;
            default:
                throw new Exception("Unknown query type '" . $type . "'.");
                break;
        }

        $stmt->closeCursor();
        $stmt = null;

        return $output;
    }

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
    public function insert($table, $data)
    {
        if (!isset($data['created'])) {
            $data['created'] = new \DateTime(null, new \DateTimeZone('UTC'));
        }

        // Generate actual insert clause, used bindings and types.
        list($_query, $_bindings, $_types) = $this->makeQueryInsert($table, $data);

        // Insert were not successfully
        if ($this->connection->executeUpdate($_query, $_bindings, $_types) !== 1) {
            $message = "Couldn't insert data to database, please exam logs.";

            throw new Exception($message);
        }

        // Get last inserted id value
        return (int)$this->connection->lastInsertId();
    }

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
    public function update($table, $data, $conditions)
    {
        // Generate actual update clause, used bindings and types.
        list($_query, $_bindings, $_types) = $this->makeQueryUpdate($table, $data, $conditions);

        // Make actual update query to database
        return (int)$this->connection->executeUpdate($_query, $_bindings, $_types);
    }

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
    public function delete($table, $conditions)
    {
        // Generate actual delete clause, used bindings and types.
        list($_query, $_bindings, $_types) = $this->makeQueryDelete($table, $conditions);

        // Make actual DELETE to database
        return $this->connection->executeUpdate($_query, $_bindings, $_types);
    }

    /**
     * End of public -methods
     */
    /**#@-*/


    /**#@+
     * Start of protected -methods.
     *
     * @access     protected
     */

    /**
     * Method generates INSERT query, bindings and used binding types to
     * specified database table and data.
     *
     * @access  protected
     *
     * @throws  \HomeAI\Database\Exception
     *
     * @param   string  $table  Name of the table
     * @param   array   $data   Insert data
     *
     * @return  array           Array which contains following data:
     *                           - Insert query, string
     *                           - Used query bindings, array
     *                           - Binding types, array
     */
    protected function makeQueryInsert(&$table, &$data)
    {
        // Filter table columns
        $this->filterTableColumns($table, $data);

        // No column data for insert
        if (empty($data)) {
            $message = sprintf(
                "No valid column data for insert clause, defined data is not valid against '%1\$s' -table",
                $table
            );

            throw new Exception($message);
        }

        // Specify used columns
        $columns = array_keys($data);

        // Create actual insert query
        $query = sprintf(
            "
            INSERT INTO
                `%1\$s`
                (`%2\$s`)
            VALUES
                (:%3\$s)
            ",
            $table,
            implode('`, `', $columns),
            implode(', :', $columns)
        );

        // Determine used binding values and types
        list($bindings, $types) = $this->determineQueryBindings($data);

        return array($query, $bindings, $types);
    }

    /**
     * Method generates UPDATE query, bindings and used binding types to
     * specified database table, update data and conditions.
     *
     * @access  protected
     *
     * @throws  \HomeAI\Database\Exception
     *
     * @param   string  $table      Name of the table
     * @param   array   $data       Update data
     * @param   array   $conditions Used update conditions
     *
     * @return  array               Array which contains following data:
     *                               - Update query, string
     *                               - Used query bindings, array
     *                               - Binding types, array
     */
    protected function makeQueryUpdate(&$table, &$data, &$conditions)
    {
        // Make update SQL clause and bindings
        list($clauseUpdate, $bindingsUpdate) = $this->determineUpdateClause($table, $data);

        // Make where SQL clause and bindings
        list($clauseWhere, $bindingsWhere) = $this->determineWhereClause($table, $conditions, true);

        // Create actual update query
        $query = sprintf(
            "
            UPDATE
                `%1\$s`
            SET
                %2\$s
            WHERE
                (1 = 1)
                %3\$s
            ",
            $table,
            $clauseUpdate,
            $clauseWhere
        );

        $bindings = $bindingsUpdate + $bindingsWhere;

        // Determine used binding values and types
        list($bindings, $types) = $this->determineQueryBindings($bindings);

        return array($query, $bindings, $types);
    }

    /**
     * Method generates DELETE query, bindings and used binding types to
     * specified database table and conditions.
     *
     * @access  protected
     *
     * @throws  \HomeAI\Database\Exception
     *
     * @param   string  $table      Name of the table
     * @param   array   $conditions Used delete conditions
     *
     * @return  array               Array which contains following data:
     *                               - Delete query, string
     *                               - Used query bindings, array
     *                               - Binding types, array
     */
    protected function makeQueryDelete(&$table, &$conditions)
    {
        // Make where SQL clause and bindings
        list($_where, $_bindings) = $this->determineWhereClause($table, $conditions, true);

        // Create actual update query
        $query = sprintf(
            "
            DELETE FROM
                `%1\$s`
            WHERE
                (1 = 1)
                %2\$s
            ",
            $table,
            $_where
        );

        // Determine used binding values and types
        list($bindings, $types) = $this->determineQueryBindings($_bindings);

        return array($query, $bindings, $types);
    }

    /**
     * Method generates generic SELECT query, bindings and used binding types
     * to specified database table, columns and conditions.
     *
     * @access  protected
     *
     * @throws  \HomeAI\Database\Exception
     *
     * @param   string  $table      Name of the table
     * @param   array   $columns    Select columns
     * @param   array   $conditions Used select conditions
     *
     * @return  array               Array which contains following data:
     *                               - Select query, string
     *                               - Used query bindings, array
     *                               - Binding types, array
     */
    protected function makeQuerySelect(&$table, &$columns, &$conditions)
    {
        // Make where SQL clause and bindings
        list($_where, $_bindings) = $this->determineWhereClause($table, $conditions, false);

        // Specify select columns
        $_columns = $this->determineSelectColumns($table, $columns);

        // Make actual query
        $query = sprintf(
            "
            SELECT
                %1\$s
            FROM
                `%2\$s`
            WHERE
                (1 = 1)
                %3\$s
            ",
            $_columns,
            $table,
            $_where
        );

        // Determine used binding values and types
        list($bindings, $types) = $this->determineQueryBindings($_bindings);

        return array($query, $bindings, $types);
    }

    /**
     * End of protected -methods
     */
    /**#@-*/


    /**#@+
     * Start of private -methods.
     *
     * @access     private
     */

    /**
     * Method makes arrays for query bindings and used binding types and
     * returns them as an array. Method is widely used in this class.
     *
     * TODO: This method needs a lots of work...
     *
     * @access  private
     *
     * @throws  \HomeAI\Database\Exception
     *
     * @param   array   $bindings   Query bindings
     *
     * @return  array               Array which contains following data:
     *                               - Used query bindings, array
     *                               - Binding types, array
     */
    private function determineQueryBindings(&$bindings)
    {
        // Reset used variables
        $_bindings = $_types = array();

        // Iterate specified bindings
        foreach ($bindings as $_binding => $_value) {
            // Default binding type is NULL
            $_type = null;

            /**
             * Binding value is defined as an object this means that this
             * value has special binding.
             */
            if (is_object($_value)) {
                // DateTime object, Doctrine will handle conversion to db format
                if ($_value instanceof \DateTime) {
                    $_type = \Doctrine\DBAL\Types\Type::DATETIME;
                } else {
                    // TODO: Implement other object handling here

                    throw new Exception("Couldn't determine binding type of this object.");
                }
            } elseif (is_array($_value)) {
                /**
                 * Bindings are defined as an array, in this case we must
                 * do some specified stuff...
                 *
                 * TODO: Do we need anything else here?
                 */

                // Assoc array, assign value and type from it
                if (!is_int(key($_value))) {
                    $data = $_value;

                    $_value = isset($data['value']) ? $data['value'] : null;
                    $_type  = isset($data['type']) ? $data['type'] : null;
                } // Value and Type are defined as single dimensional array
                elseif (count($_value) === 2) {
                    list($_value, $_type) = $_value;
                } else {
                    throw new Exception("Couldn't determine binding from array.");
                }
            }

            // Add bind value and type to return values
            $_bindings[$_binding] = $_value;
            $_types[$_binding]    = $_type;
        }

        return array($_bindings, $_types);
    }


    /**
     * Method determines columns for generic SELECT clause.
     *
     * @access  private
     *
     * @throws  \HomeAI\Database\Exception
     *
     * @param   string  $table      Table definition
     * @param   array   $columns    Select columns
     *
     * @return  string              Select columns as string for the query
     */
    private function determineSelectColumns(&$table, &$columns)
    {
        if (empty($columns)) {
            return '*';
        } elseif (!is_array($columns)) {
            $columns = array((string)$columns);
        }

        // Filter select columns
        $this->filterTableColumns($table, $columns);

        if (empty($columns)) {
            $message = sprintf(
                "No valid select columns for '%1\$s' -table.",
                $table
            );

            throw new Exception($message);
        }

        return "`" . implode("`, `", $columns) . "`";
    }

    /**
     * Method determines update clause and used bindings for it.
     *
     * @access  private
     *
     * @throws  \HomeAI\Database\Exception
     *
     * @param   string  $table  Table definition
     * @param   array   $data   Update data
     *
     * @return  array           Array which contains following data
     *                           - Update clause, string
     *                           - Update bindings, array
     */
    private function determineUpdateClause(&$table, &$data)
    {
        // Filter table data columns
        $this->filterTableColumns($table, $data);

        // No column data for update clause
        if (empty($data)) {
            $message = sprintf(
                "No valid column data for update clause, defined data is not valid against '%1\$s' -table",
                $table
            );

            throw new Exception($message);
        }

        // Reset used variables.
        $bindings = $clause = array();

        // Iterate update fields
        foreach ($data as $column => $value) {
            $binding = 'SET_' . $column;

            $clause[] = "`" . $column . "` = :" . $binding;

            $bindings[$binding] = $value;
        }

        return array(
            implode(', ', $clause),
            $bindings
        );
    }

    /**
     * Method determines SQL query where clause and used bindings for it.
     *
     * TODO: Refactor this monster method!
     *
     * @access  private
     *
     * @throws  \HomeAI\Database\Exception
     *
     * @param   string  $table      Table definition
     * @param   array   $conditions Where conditions
     * @param   boolean $required   Is conditions required or not
     *
     * @return  array               Array which contains following data
     *                               - Update clause, string
     *                               - Update bindings, array
     */
    private function determineWhereClause(&$table, &$conditions, $required = true)
    {
        // No conditions at all
        if (empty($conditions)) {
            // Conditions are required in update() and delete() -methods
            if ($required) {
                throw new Exception("No WHERE conditions specified, cannot continue.");
            }

            // Otherwise return empty values.
            return array(
                '',
                array()
            );
        }

        /**
         * Conditions aren't array. In this case we can assume that given
         * condition must be targeted to table primary key(s).
         */
        if (!is_array($conditions)) {
            // Get table primary key(s)
            $primaryKey = $this->getTablePrimaryKey($table);

            // Primary key not defined => error
            if (empty($primaryKey)) {
                $message = sprintf(
                    "Cannot define WHERE clause '%1\$s' -table doesn't contain Primary Key (PK) column.",
                    $table
                );

                throw new Exception($message);
            } elseif (is_array($primaryKey)) { // Multiple primary keys
                // Condition string doesn't contain separator
                if (mb_strpos($conditions, '-') === false) {
                    $message = sprintf(
                        "Cannot create WHERE clause for '%1\$s' -table. Multiple primary keys and given value is not valid. Given value was '%s'.",
                        $table,
                        $conditions
                    );

                    throw new Exception($message);
                }

                // Determine used values for primary keys
                $pkValues = explode('-', $conditions);

                // Check that value count matches with primary key count
                if (count($pkValues) !== count($primaryKey)) {
                    $message = sprintf(
                        "Cannot create WHERE clause for '%1\$s' -table. Multiple primary keys and value count doesn't match with it. Primary keys: %d, values: %d",
                        $table,
                        count($pkValues),
                        count($primaryKey)
                    );

                    throw new Exception($message);
                }

                // Reset conditions array
                $conditions = array();

                // Iterate primary keys and generate actual conditions array
                foreach ($primaryKey as $index => $column) {
                    $conditions[$column] = $pkValues[$index];
                }
            } else { // Single primary key
                $conditions = array(
                    $primaryKey => $conditions,
                );
            }
        }

        // Filter determined conditions
        $this->filterTableColumns($table, $conditions);

        // No valid column data for where clause
        if (empty($conditions)) {
            $message = sprintf(
                "No valid column data for where clause, defined data is not valid against '%1\$s' -table.",
                $table
            );

            throw new Exception($message);
        }

        // Reset used variables.
        $bindings = $clause = array();

        // Iterate where conditions
        foreach ($conditions as $column => $value) {
            $binding = 'WHERE' . str_replace(array('-', '_'), array('', ''), $column);

            $clause[] = "AND (`" . $column . "` = :" . $binding . ")";

            $bindings[$binding] = $value;
        }

        return array(
            implode(' ', $clause),
            $bindings
        );
    }

    /**
     * Method filters out columns that doesn't exists in specified database table.
     *
     * @access  private
     *
     * @param   string  $table      Table definition
     * @param   array   $columns    Reference to columns array
     *
     * @return  void
     */
    private function filterTableColumns(&$table, &$columns)
    {
        // Fetch table object, from cache or create new one if first run
        $tableObject = Table::getInstance($table);

        // Iterate specified input columns
        foreach ($columns as $_column => $column) {
            if ((is_int($_column) && $tableObject->hasColumn($column)) || $tableObject->hasColumn($_column)) {
                continue;
            }

            // Determine actual column name
            $_column = is_int($_column) ? $column : $_column;

            // Table doesn't have this column, so remove it
            unset($columns[$_column]);
        }
    }

    /**
     * End of private -methods
     */
    /**#@-*/
}
