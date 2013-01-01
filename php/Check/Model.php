<?php
/**
 * \php\Check\Model.php
 *
 * @package     Core
 * @subpackage  Check
 * @category    Model
 */
namespace HomeAI\Check;

use HomeAI\Util\Config as Config;
use HomeAI\Database\DB as DB;

/**
 * Check model class.
 *
 * @package     Core
 * @subpackage  Check
 * @category    Model
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Model implements Interfaces\Model
{
    /**
     * @title       Check for PHP version
     * @description HomeAI needs at least PHP 5.4.0
     * @link        http://php.net/
     *
     * @throws \Exception
     *
     * @return  bool
     */
    public function checkPhpVersion()
    {
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            return true;
        }

        $message = "Your PHP version is not compatible with HomeAI";

        throw new \Exception($message);
    }

    /**
     * @title   Check for PHP Data Objects (PDO)
     * @link    http://php.net/manual/en/pdo.setup.php
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkPhpExtensionPdo()
    {
        if (extension_loaded('PDO')) {
            return true;
        }

        $message = "Required PDO extension not loaded.";

        throw new \Exception($message);
    }

    /**
     * @title   Check for PHP PDO drivers
     * @depend  checkPhpExtensionPdo
     * @link    http://php.net/manual/en/pdo.drivers.php
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkPhpExtensionPdoDrivers()
    {
        $drivers = array(
            'pdo_mysql',
            'pdo_pgsql',
        );

        foreach ($drivers as $driver) {
            if (extension_loaded($driver)) {
                return true;
            }
        }

        $message = "No PDO drivers founded, one of these are required: '". implode("', '", $drivers) ."'";

        throw new \Exception($message);
    }

    /**
     * @title   Check for PDO MySQL driver
     * @depend  checkPhpExtensionPdoDrivers
     * @link    http://php.net/manual/en/ref.pdo-mysql.php
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkPhpExtensionPdoDriverMySql()
    {
        if (extension_loaded('pdo_mysql')) {
            return true;
        }

        $message = "Required 'pdo_mysql' extension not loaded.";

        throw new \Exception($message);
    }

    /**
     * @title   Check for PDO PostgreSQL driver
     * @depend  checkPhpExtensionPdoDrivers
     * @link    http://php.net/manual/en/ref.pdo-pgsql.php
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkPhpExtensionPdoDriverPostgreSql()
    {
        if (extension_loaded('pdo_pgsql')) {
            return true;
        }

        $message = "Required 'pdo_pgsql' extension not loaded.";

        throw new \Exception($message);
    }

    /**
     * @title   Check for BCMatch extension
     * @link    http://php.net/manual/en/book.bc.php
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkPhpExtensionBcmath()
    {
        if (extension_loaded('bcmath')) {
            return true;
        }

        $message = "Required 'bcmath' extension not loaded.";

        throw new \Exception($message);
    }

    /**
     * @title   Check for iconv extension
     * @link    http://php.net/manual/en/book.iconv.php
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkPhpExtensionIconv()
    {
        if (extension_loaded('iconv')) {
            return true;
        }

        $message = "Required 'iconv' extension not loaded.";

        throw new \Exception($message);
    }

    /**
     * @title   Check for JavaScript Object Notation (json) extension
     * @link    http://php.net/manual/en/book.json.php
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkPhpExtensionJson()
    {
        if (extension_loaded('json')) {
            return true;
        }

        $message = "Required 'json' extension not loaded.";

        throw new \Exception($message);
    }

    /**
     * @title   Check for Multibyte String (mbstring) extension
     * @link    http://php.net/manual/en/book.mbstring.php
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkPhpExtensionMbString()
    {
        if (extension_loaded('mbstring')) {
            return true;
        }

        $message = "Required 'mbstring' extension not loaded.";

        throw new \Exception($message);
    }

    /**
     * @title   Check for Session Handling (session) extension
     * @link    http://php.net/manual/en/book.session.php
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkPhpExtensionSession()
    {
        if (extension_loaded('session')) {
            return true;
        }

        $message = "Required 'session' extension not loaded.";

        throw new \Exception($message);
    }

    /**
     * @title   Check for Regular Expressions (PCRE) extension
     * @link    http://php.net/manual/en/book.session.php
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkPhpExtensionPcre()
    {
        if (extension_loaded('pcre')) {
            return true;
        }

        $message = "Required 'pcre' extension not loaded.";

        throw new \Exception($message);
    }

    /**
     * @title   Check for Client URL Library (cURL) extension
     * @link    http://php.net/manual/en/book.session.php
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkPhpExtensionCurl()
    {
        if (extension_loaded('curl')) {
            return true;
        }

        $message = "Required 'curl' extension not loaded.";

        throw new \Exception($message);
    }

    /**
     * @title   Check that '{basedir]/data' -path exists.
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkEnvironmentPathData()
    {
        $directory = PATH_BASE ."data/";

        if (is_dir($directory)) {
            return true;
        }

        return $this->createDirectory($directory);
    }

    /**
     * @title   Check that '{basedir]/data' -path is writable.
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkEnvironmentPathDataIsWritable()
    {
        $directory = PATH_BASE ."data/";

        return $this->createFile($directory);
    }

    /**
     * @title   Check that '{basedir]/logs' -path exists.
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkEnvironmentPathLogs()
    {
        $directory = PATH_BASE ."logs/";

        if (is_dir($directory)) {
            return true;
        }

        return $this->createDirectory($directory);
    }

    /**
     * @title   Check that '{basedir]/logs' -path is writable.
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkEnvironmentPathLogsIsWritable()
    {
        $directory = PATH_BASE ."logs/";

        return $this->createFile($directory);
    }

    /**
     * @title   Check that '{basedir]/templates_compiled' -path exists.
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkEnvironmentPathTemplatesCompiled()
    {
        $directory = PATH_BASE ."templates_compiled/";

        if (is_dir($directory)) {
            return true;
        }

        return $this->createDirectory($directory);
    }

    /**
     * @title   Check that '{basedir]/templates_compiled' -path is writable.
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkEnvironmentPathTemplatesCompiledIsWritable()
    {
        $directory = PATH_BASE ."templates_compiled/";

        return $this->createFile($directory);
    }

    /**
     * @title   Check that '{basedir]/html/js/cache' -path exists.
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkEnvironmentPathCacheJs()
    {
        $directory = PATH_BASE ."html/js/cache/";

        if (is_dir($directory)) {
            return true;
        }

        return $this->createDirectory($directory);
    }

    /**
     * @title   Check that '{basedir]/html/js/cache/' -path is writable.
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkEnvironmentPathCacheJsIsWritable()
    {
        $directory = PATH_BASE ."html/js/cache/";

        return $this->createFile($directory);
    }

    /**
     * @title   Check that '{basedir]/html/css/cache' -path exists.
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkEnvironmentPathCacheCss()
    {
        $directory = PATH_BASE ."html/css/cache/";

        if (is_dir($directory)) {
            return true;
        }

        return $this->createDirectory($directory);
    }

    /**
     * @title   Check that '{basedir]/html/css/cache/' -path is writable.
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkEnvironmentPathCacheCssIsWritable()
    {
        $directory = PATH_BASE ."html/css/cache/";

        return $this->createFile($directory);
    }

    /**
     * @title   Check that '{basedir]/config/config.ini' exists.
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkEnvironmentIniConfig()
    {
        $config = PATH_BASE ."config/config.ini";

        if (is_readable($config)) {
            return true;
        }

        $message = "Required config file '". $config ."' not found or it's not readable.";

        throw new \Exception($message);
    }

    /**
     * @title   Check that '{basedir]/config/constants.ini' exists.
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkEnvironmentIniConstants()
    {
        $config = PATH_BASE ."config/constants.ini";

        if (is_readable($config)) {
            return true;
        }

        $message = "Required config file '". $config ."' not found or it's not readable.";

        throw new \Exception($message);
    }

    /**
     * @title   Check that '{basedir]/config/database.ini' exists.
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkEnvironmentIniDatabase()
    {
        $config = PATH_BASE ."config/database.ini";

        if (is_readable($config)) {
            return true;
        }

        $message = "Required config file '". $config ."' not found or it's not readable.";

        throw new \Exception($message);
    }

    /**
     * @title   Check that database.ini contains necessary data.
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkDatabaseConfig()
    {
        // Read config
        $config = Config::readIni('database.ini');

        // Specify required config values
        $required = array(
            'driver',
            'user',
            'password',
            'host',
            'port',
            'dbname',
        );

        foreach ($required as $require) {
            if (!isset($config[$require]) || empty($config[$require])) {
                $message = "database.ini doesn't contain required '". $require ."' config value.";

                throw new \Exception($message);
            }
        }

        return true;
    }

    /**
     * @title   Check that database connection is made successfully.
     *
     * @throws  \Exception
     *
     * @return  bool
     */
    public function checkDatabaseConnection()
    {
        require_once PATH_BASE ."php/database.php";

        $db = DB::getInstance();

        $stmt = $db->query('SELECT NOW()');
        $stmt->closeCursor();
        $stmt = null;

        unset ($db);

        return true;
    }

    /**
     * @title   PHP
     *
     * @prefix  checkPhp
     */
    protected function checkSectionPhp()
    {
    }

    /**
     * @title   Environment
     *
     * @prefix  checkEnvironment
     */
    protected function checkSectionEnvironment()
    {
    }

    /**
     * @title   Database
     *
     * @prefix  checkDatabase
     */
    protected function checkSectionDatabase()
    {
    }

    /**
     * Method tries to create new directory.
     *
     * @throws  \Exception
     *
     * @param   string  $directory
     *
     * @return  bool
     */
    private function createDirectory($directory)
    {
        if (@mkdir($directory, 0777, true)) {
            if (@chmod($directory, 0777)) {
                return true;
            } else {
                $message = "Cannot change directory '". $directory ."' rights.";

                throw new \Exception($message);
            }
        }

        $message = "Cannot create directory '". $directory ."'.";

        throw new \Exception($message);
    }

    /**
     * Method tries to write temp file to specified path to
     * check that directory is writable.
     *
     * @throws  \Exception
     *
     * @param   string  $directory
     *
     * @return  bool
     */
    private function createFile($directory)
    {
        $filename = $directory ."test.tmp";

        if (@file_put_contents($filename, 'test') === false) {
            $message = "Cannot write file '". $filename ."'. Please check folder permissions.";

            throw new \Exception($message);
        }

        unlink($filename);

        return true;
    }
}
