<?php
/**
 * \php\Check\Controller.php
 *
 * @package     Core
 * @subpackage  Check
 * @category    Controller
 */
namespace HomeAI\Check;

use HomeAI\Util\String as String;

/**
 * Check controller class.
 *
 * @package     Core
 * @subpackage  Check
 * @category    Controller
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Controller implements Interfaces\Controller
{
    /**#@+
     * View and model objects, these are created automatically if corresponding
     * classes are founded.
     *
     * @access  protected
     */

    /**
     * @var \HomeAI\Check\View
     */
    protected $view;

    /**
     * @var \HomeAI\Check\Model
     */
    protected $model;
    /**#@-*/

    /**
     * Construction of the class.
     *
     * @return  \HomeAI\Check\Controller
     */
    public function __construct()
    {
        // Define page View and Model object names
        $classes = array(
            'view'  => '\\HomeAI\\Check\\View',
            'model' => '\\HomeAI\\Check\\Model',
        );

        // Iterate classes and create objects
        foreach ($classes as $attribute => $class) {
            $this->{$attribute} = new $class();
        }
    }

    /**
     * Method runs all HomeAI environment checks.
     *
     * @return  void
     */
    public function doChecks()
    {
        // Determine all checks
        $checks = $this->determineChecks();

        // Make page header
        $this->view->makeHeader();

        // Process check sections
        array_walk($checks, array($this, 'processSection'));

        // Make page footer
        $this->view->makeFooter();
    }

    /**
     * Method process check section.
     *
     * @param   array   $checks     Array of checks on this section
     * @param   string  $section    Check section name
     *
     * @return  void
     */
    protected function processSection(array $checks, $section)
    {
        // Make section header
        $this->view->makeSectionHeader($section);

        // Process check sections
        array_walk($checks, array($this, 'processCheck'));

        // Make section footer
        $this->view->makeSectionFooter($section);
    }

    /**
     * Method process single check method from Model -class.
     *
     * @todo    make check dependency handling here
     *
     * @param   array   $check
     *
     * @return  void
     */
    protected function processCheck(array $check)
    {
        $information = '';

        try {
            $result = call_user_func(array($this->model, $check['method']));
        } catch (\Exception $error) {
            $result = false;

            $information = $error->getMessage();
        }

        // Make check output
        $this->view->makeCheck($check, $result, $information);
    }

    /**
     * Method determines used checks.
     *
     * @return array
     */
    protected function determineChecks()
    {
        // Create reflection about model class
        $reflection = new \ReflectionObject($this->model);

        $output = array();

        /**
         * @var \ReflectionMethod $method
         */
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PROTECTED) as $method) {
            if (mb_substr($method->name, 0, 12) !== 'checkSection') {
                continue;
            }

            $comments = String::parseDocBlock($method->getDocComment());

            if (!isset($comments['title']) || !isset($comments['prefix'])) {
                continue;
            }

            $output[$comments['title']] = $this->determineCheckMethods($comments['prefix'], $reflection);
        }

        unset($reflection);

        return $output;
    }

    /**
     * Method determines used check methods by specified prefix.
     *
     * @param   string              $prefix     Method prefix
     * @param   \ReflectionObject   $reflection Reflection of model object
     *
     * @return  array                           Array of check methods
     */
    private function determineCheckMethods($prefix, \ReflectionObject $reflection)
    {
        $output = array();

        $length = mb_strlen($prefix);

        /**
         * @var \ReflectionMethod $method
         */
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if (mb_substr($method->name, 0, $length) !== $prefix) {
                continue;
            }

            // Parse method comments
            $comments = String::parseDocBlock($method->getDocComment());

            // Specify output
            $output[] = array(
                'method'        => $method->getName(),
                'title'         => isset($comments['title']) ? $comments['title'] : '',
                'description'   => isset($comments['description']) ? $comments['description'] : '',
                'link'          => isset($comments['link']) ? $comments['link'] : '',
            );
        }

        return $output;
    }
}
