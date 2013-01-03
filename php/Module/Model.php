<?php
/**
 * \php\Module\Model.php
 *
 * @package     Core
 * @subpackage  Module
 * @category    Model
 */
namespace HomeAI\Module;

use HomeAI\Core\Request;
use HomeAI\Database\DB;

/**
 * Generic module model class. All module model classes must extend this base class.
 *
 * @package     Core
 * @subpackage  Module
 * @category    Model
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
abstract class Model implements Interfaces\Model
{
    /**
     * Request object.
     *
     * @access  protected
     * @var     \HomeAI\Core\Request
     */
    protected $request;

    /**
     * Request module.
     *
     * @access  protected
     * @var     string
     */
    protected $module;

    /**
     * Request action of module.
     *
     * @access  protected
     * @var     string
     */
    protected $action;

    /**
     * Page data for current request
     *
     * @access  protected
     * @var     array
     */
    protected $pageData;

    /**
     * Database instance.
     *
     * @access  protected
     * @var     \HomeAI\Database\DB
     */
    protected $db;

    /**
     * Model object main table.
     *
     * @access  protected
     * @var     string
     */
    protected $table = '';

    /**
     * Construction of the class.
     *
     * @param   \HomeAI\Core\Request    $request
     * @param   string                  $module
     * @param   string                  $action
     * @param   array                   $pageData
     *
     * @return  \HomeAI\Module\Model
     */
    public function __construct(Request &$request, &$module = null, &$action = null, &$pageData = array())
    {
        // Store given data
        $this->request  = $request;
        $this->module   = $module;
        $this->action   = $action;
        $this->pageData = $pageData;

        // Get database instance for model
        $this->db = DB::getInstance();
    }

    /**
     * Method formats option list data. Basically method returns key - value
     * array of options.
     *
     * @access  protected
     *
     * @param   array   $data
     * @param   bool    $multidimensional
     * @param   bool    $showSelect
     * @param   bool    $showEmpty
     *
     * @return  array
     */
    protected function formatOptionList($data, $multidimensional = false, $showSelect = false, $showEmpty = false)
    {
        // Initialize output
        $output = array();

        // We want to show 'select' -option
        if ($showSelect) {
            $output['#'] = '--- Select ---';
        }

        // We want to show empty -option
        if ($showEmpty) {
            $output[''] = '';
        }

        // Iterate data.
        foreach ($data as $v) {
            // Multidimensional input/output
            if ($multidimensional) {
                if (!isset($output[$v[0][0]])) {
                    $output[$v[0][0]] = array();
                }

                foreach ($v as $_v) {
                    $output[$_v[0]][$_v[1]] = $_v[2];
                }
            } else {
                $output[$v[0]] = $v[1];
            }
        }

        return $output;
    }
}
