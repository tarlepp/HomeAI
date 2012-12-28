<?php
/**
 * \php\Core\Session.php
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Session
 */
namespace HomeAI\Core;

use HomeAI\Database\DB as DB;

/**
 * This class process all session handling.
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Session
 *
 * @date        $Date$
 * @version     $Rev$
 * @author      $Author$
 */
class Session implements Interfaces\Session
{
    /**
     * Singleton class variable.
     *
     * @access  protected
     * @var     \HomeAI\Core\Session
     */
    protected static $instance = null;

    /**
     * Database handling class
     *
     * @access  protected
     * @var     \HomeAI\Database\DB
     */
    protected $db = null;

    /**#@+
     * Used private class variables
     *
     * @access  private
     */

    /**
     * Session time as in seconds
     *
     * @var     integer
     */
    private $time;

    /**
     * Session path an name strings.
     *
     * @var     string
     */
    private $path;
    private $name;
    /**#@-*/

    /**
     * Construction of the class.
     *
     * @access  protected
     *
     * @see     PHP: session_set_save_handler <http://php.net/manual/function.session-set-save-handler.php>
     * @see     PHP: register_shutdown_function <http://php.net/manual/function.register-shutdown-function.php>
     * @see     PHP: session_start <http://php.net/manual/function.session-start.php>
     *
     * @return  \HomeAI\Core\Session
     */
    protected function __construct()
    {
        // Fetch database instance.
        $this->db = DB::getInstance();

        // Set default session time, this will be changed after this.
        $this->time = 3600;

        $handler = session_set_save_handler(
            array($this, 'open'),
            array($this, 'close'),
            array($this, 'read'),
            array($this, 'write'),
            array($this, 'destroy'),
            array($this, 'gc')
        );

        if ((bool)$handler !== true) {
            trigger_error('Cannot set session save handler...', E_ERROR);
        }

        register_shutdown_function('session_write_close');

        session_start();

        return true;
    }

    /**
     * Destruction of the class.
     *
     * @access  public
     *
     * @return  boolean
     */
    public function __destruct()
    {
        $this->close();

        return true;
    }

    /**
     * Method initialize Session -class to use.
     *
     * @access  public
     * @static
     *
     * @return  \HomeAI\Core\Session
     */
    public static function initialize()
    {
        if (is_null(Session::$instance)) {
            Session::$instance = new Session;
        }

        return Session::$instance;
    }

    /**
     * Generic session open method.
     *
     * @access  public
     *
     * @param   string  $path
     * @param   string  $name
     *
     * @return  boolean
     */
    public function open($path, $name)
    {
        $this->path = $path;
        $this->name = $name;

        return true;
    }

    /**
     * Generic session close method.
     *
     * @access  public
     *
     * @return  boolean
     */
    public function close()
    {
        $this->gc();

        return true;
    }

    /**
     * Session read method.
     *
     * @access  public
     *
     * @param   string  $sessionId
     *
     * @return  string
     */
    public function read($sessionId)
    {
        // Specify bindings for query
        $bindings = array(
            'sessionId' => $sessionId,
            'expire'    => array(
                new \DateTime('now'),
                'datetime',
            ),
        );

        // Specify used query
        $query = "
            SELECT
                Data
            FROM
                Session
            WHERE
                (Session = :sessionId)
                AND (Expire > :expire)
        ";

        // Make query and fetch data
        $stmt = $this->db->query($query, $bindings);

        $data = '';

        if ($stmt !== false) {
            $data = (string)$stmt->fetchColumn();
            $stmt->closeCursor();
            $stmt = null;
        }

        return $data;
    }

    /**
     * Session write method.
     *
     * @access  public
     *
     * @param   string  $sessionId
     * @param   string  $sessionData
     *
     * @return  boolean
     */
    public function write($sessionId, $sessionData)
    {
        $output = false;

        if ($this->isValid($sessionId)) {
            $output = $this->update($sessionId, $sessionData);
        } elseif ($this->insert($sessionId, $sessionData)) {
            $output = true;
        } else {
        }

        return $output;
    }

    /**
     * Session destroy method.
     *
     * @access  public
     *
     * @param   string  $sessionId  Session id
     *
     * @return  boolean
     */
    public function destroy($sessionId)
    {
        // Specify data for insert
        $conditions = array(
            'Session' => $sessionId,
        );

        // Make actual update
        $rows = $this->db->delete('Session', $conditions);

        return ($rows === 1) ? true : false;
    }

    /**
     * Session cleanup method.
     *
     * @access  public
     *
     * @return  boolean
     */
    public function gc()
    {
        // Specify bindings for query
        $binds = array(
            'expire' => array(
                new \DateTime('now'),
                'datetime',
            ),
        );

        // Specify used query
        $query = "
            DELETE FROM
                Session
            WHERE
                (Expire < :expire)
        ";

        // Make query and fetch data
        $stmt = $this->db->query($query, $binds);

        if ($stmt === false) {
            return false;
        }

        $stmt->closeCursor();
        $stmt = null;

        return true;
    }

    /**
     * Method determines if session exists already.
     *
     * @access  public
     *
     * @param   string  $sessionId  Session id
     *
     * @return  boolean
     */
    public function isValid($sessionId)
    {
        // Used query bindings
        $bindings = array(
            'sessionId' => $sessionId,
        );

        // Specify used query.
        $query = "
            SELECT
                ID
            FROM
                Session
            WHERE
                (Session = :sessionId)
        ";

        $stmt = $this->db->query($query, $bindings);

        if ($stmt === false) {
            return false;
        }

        $output = (count($stmt->fetchAll()) === 1) ? true : false;
        $stmt->closeCursor();
        $stmt = null;

        return $output;
    }

    /**
     * Session update method.
     *
     * @access  protected
     *
     * @param   string  $sessionId
     * @param   string  $sessionData
     *
     * @return  boolean
     */
    protected function update($sessionId, $sessionData)
    {
        // Create required datetime object
        $dateTimeExpire = new \DateTime('now');

        // Specify update data
        $data = array(
            'Data'   => $sessionData,
            'Expire' => array(
                $dateTimeExpire->add(new \DateInterval('PT' . $this->time . 'S')),
                'datetime',
            ),
        );

        // Specify used update conditions
        $conditions = array(
            'Session' => $sessionId,
        );

        // Make actual update
        $rows = $this->db->update('Session', $data, $conditions);

        return ($rows === 1) ? true : false;
    }

    /**
     * Session insert method.
     *
     * @access  protected
     *
     * @param   string  $sessionId      Session id
     * @param   string  $sessionData    Session data
     *
     * @return  boolean
     */
    protected function insert($sessionId, $sessionData)
    {
        // Create required datetime objects
        $dateTimeCreate = new \DateTime('now');
        $dateTimeExpire = new \DateTime('now');

        // Specify data for insert
        $data = array(
            'Session'   => $sessionId,
            'Data'      => $sessionData,
            'Create'    => $dateTimeCreate,
            'Expire'    => $dateTimeExpire->add(new \DateInterval('PT' . $this->time . 'S')),
        );

        // Make actual insert
        $id = $this->db->insert('Session', $data);

        return ($id > 0) ? true : false;
    }
}
