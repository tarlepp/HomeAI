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
    public function __construct(Request &$request, &$module, &$action, &$pageData)
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


    /**
     * Method determines search terms and returns them as an array. Note that
     * method will only return ten (10) first search terms.
     *
     * @access  protected
     *
     * @param   string  $term
     *
     * @return  array
     */
    protected function determineSearchTerms($term)
    {
        $terms = explode(' ', $term);

        $terms = array_filter(array_unique($terms));

        foreach ($terms as $k => $term) {
            if (mb_strlen($term) < 2) {
                unset($terms[$k]);
            }
        }

        return count($terms) > 10 ? array_slice($terms, 0, 10) : $terms;
    }


    /**
     * Method combines search data to one array, this method is used in
     * every search functionality in Nettibaari.
     *
     * @access  protected
     *
     * @param   array   $data   Data to combine
     *
     * @return  array           Combined data
     */
    protected function combineSearchData($data)
    {
        // Initialize output
        $output = array();

        // Iterate data
        foreach ($data as $v) {
            $id = (int)$v['ID'];

            if (!array_key_exists($id, $output)) {
                $output[$id]              = $v;
                $output[$id]['Relevance'] = 1;
            }

            $relevance = '1';
            $factor    = $v['Relevance_Like'];

            $mainFactor = isset($v['Factor']) ? $v['Factor'] : 1;

            $output[$id]['Relevance'] = bcmul(
                bcadd($output[$id]['Relevance'], bcmul($relevance, $factor, 3), 2),
                $mainFactor,
                2
            );
        }

        return $output;
    }
}
