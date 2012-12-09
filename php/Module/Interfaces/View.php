<?php
/**
 * \php\Module\Interfaces\View.php
 *
 * @package     Core
 * @subpackage  Module
 * @category    Interface
 */
namespace HomeAI\Module\Interfaces;

use HomeAI\Module\Model;

/**
 * Interface for \HomeAI\Module\View -class.
 *
 * @package     Core
 * @subpackage  Page
 * @category    Controller
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface View extends Common
{
    /**
     * Method sets model object for current view object.
     *
     * @access  public
     *
     * @param   \HomeAI\Module\Model  $model  Model object of the current page
     *
     * @return  void
     */
    public function setModel(Model &$model);

    /**
     * Setter for page title.
     *
     * @access  public
     *
     * @param   string|array    $title  Page title definition, can be string or array.
     *
     * @return  void
     */
    public function setTitle($title);

    /**
     * Setter for page description.
     *
     * @access  public
     *
     * @param   string  $description    Page description
     *
     * @return  void
     */
    public function setDescription($description);

    /**
     * Method returns current smarty object.
     *
     * @access  public
     *
     * @return  \Smarty
     */
    public function getSmarty();

    /**
     * Method adds specified keywords to displayed page.
     *
     * @access  public
     *
     * @param   string|array    $keyword
     *
     * @return  void
     */
    public function addKeyword($keyword);

    /**
     * Javascript library add method.
     *
     * @access  public
     *
     * @param   string  $javascript Javascript library name.
     * @param   boolean $append     Add type.
     *
     * @return  void
     */
    public function addJavascript($javascript, $append = true);

    /**
     * CSS file add method.
     *
     * @access  public
     *
     * @param   string  $css    CSS filename.
     * @param   string  $media  Used media type for CSS.
     * @param   boolean $append Add type.
     * @param   boolean $first  Add CSS file to "first" array or not
     *
     * @return  void
     */
    public function addCss($css, $media = 'screen, projection', $append = true, $first = false);

    /**
     * Method displays defined page.
     *
     * @access  public
     *
     * @param   string  $content    HTML content to overwrite everything else
     * @param   string  $template   Used main template file.
     *
     * @return  void
     */
    public function display($content = null, $template = 'index.tpl');

    /**
     * Method makes exception error page HTML content.
     *
     * @access  public
     *
     * @param   \Exception  $error
     *
     * @return  string
     */
    public function makeExceptionError(\Exception $error);

    /**
     * Method makes 404 page HTML content.
     *
     * @access  public
     *
     * @return  string
     */
    public function make404();
}
