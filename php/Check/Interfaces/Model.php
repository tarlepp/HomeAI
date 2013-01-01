<?php
/**
 * \php\Check\Interfaces\Model.php
 *
 * @package     Core
 * @subpackage  Check
 * @category    Interface
 */
namespace HomeAI\Check\Interfaces;

/**
 * Interface for \HomeAI\Check\Model -class.
 *
 * @package     Core
 * @subpackage  Check
 * @category    Controller
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Model
{
    public function checkPhpVersion();
    public function checkPhpExtensionPdo();
    public function checkPhpExtensionPdoDrivers();
    public function checkPhpExtensionPdoDriverMySql();
    public function checkPhpExtensionPdoDriverPostgreSql();
    public function checkPhpExtensionBcmath();
    public function checkPhpExtensionIconv();
    public function checkPhpExtensionJson();
    public function checkPhpExtensionMbString();
    public function checkPhpExtensionSession();
    public function checkPhpExtensionPcre();
    public function checkPhpExtensionCurl();
    public function checkEnvironmentPathData();
    public function checkEnvironmentPathDataIsWritable();
    public function checkEnvironmentPathLogs();
    public function checkEnvironmentPathLogsIsWritable();
    public function checkEnvironmentPathTemplatesCompiled();
    public function checkEnvironmentPathTemplatesCompiledIsWritable();
    public function checkEnvironmentPathCacheJs();
    public function checkEnvironmentPathCacheJsIsWritable();
    public function checkEnvironmentPathCacheCss();
    public function checkEnvironmentPathCacheCssIsWritable();
    public function checkEnvironmentIniConfig();
    public function checkEnvironmentIniConstants();
    public function checkEnvironmentIniDatabase();
    public function checkDatabaseConfig();
    public function checkDatabaseConnection();
}
