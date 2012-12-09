<?php
/**
 * \php\Module\View.php
 *
 * @package     Core
 * @subpackage  Module
 * @category    View
 */
namespace HomeAI\Module;

use HomeAI\Auth\Login;
use HomeAI\Core\Request;
use HomeAI\Util\Config;
use HomeAI\Module\Model;

/**
 * Generic module view class. All module view classes must extend this base class.
 *
 * @package     Core
 * @subpackage  Module
 * @category    View
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
abstract class View implements Interfaces\View
{
    /**#@+
     * Used check constants.
     *
     * @access  public
     * @type    constant
     * @var     string
     */
    const CHECK_TYPE_CSS        = 'CSS';
    const CHECK_TYPE_JAVASCRIPT = 'JS';
    /**#@-*/

    /**
     * Request object.
     *
     * @access  protected
     * @var     \HomeAI\Core\Request
     */
    protected $request;

    /**
     * Model object for current view.
     *
     * @access  protected
     * @var     \HomeAI\Module\Model
     */
    protected $model;

    /**
     * Request module
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
     * Is page cache cleared all the time or not.
     *
     * @access  protected
     * @var     boolean
     */
    protected $pageCache = true;

    /**
     * Used default definitions.
     *
     * @access  protected
     * @var     array
     */
    protected $pageJavascript = array(
        'jQuery/',
        'jQuery-UI/',
        'bootstrap/',
        'qTip2/',
    );

    /**
     * Used css definitions.
     *
     * @access  protected
     * @var     array
     */
    protected $pageCss = array(
        'homeai.css'    => 'screen, projection',
        'print.css'     => 'print',
    );

    /**
     * Used css definitions. This contains all javascript libraries CSS
     * files. These CSS files must append first in the source.
     *
     * @access  protected
     * @var     array
     */
    protected $pageCssFirst = array();

    /**
     * Title of the current page. This must be system text tag because of
     * localization reasons. Basically this value is text tag key value.
     *
     * @access  protected
     * @var     string
     */
    protected $pageTitle = '';

    /**
     * Used default keywords array for _all_ the pages.
     *
     * @access  protected
     * @var     array
     */
    protected $keywords = array();

    /**
     * Page extra description string.
     *
     * @access  protected
     * @var     string
     */
    protected $description = '';

    /**
     * Smarty object.
     *
     * @access  protected
     * @var     \Smarty
     */
    protected $smarty = '';

    /**
     * Construction of the class.
     *
     * @param   \HomeAI\Core\Request    $request
     * @param   string                  $module
     * @param   string                  $action
     * @param   array                   $pageData
     *
     * @return  \HomeAI\Module\View
     */
    public function __construct(Request &$request, &$module, &$action, &$pageData)
    {
        // Store given data
        $this->request  = $request;
        $this->module   = $module;
        $this->action   = $action;
        $this->pageData = $pageData;

        // Initialize smarty
        $this->initializeSmarty();
    }

    /**
     * Method sets model object for current view object.
     *
     * @access  public
     *
     * @param   \HomeAI\Module\Model  $model  Model object of the current page
     *
     * @return  void
     */
    public function setModel(Model &$model)
    {
        $this->model = $model;
    }

    /**
     * Setter for page title.
     *
     * @access  public
     *
     * @param   string|array    $title  Page title definition, can be string or array.
     *
     * @return  void
     */
    public function setTitle($title)
    {
        $this->pageTitle = $title;
    }

    /**
     * Setter for page description.
     *
     * @access  public
     *
     * @param   string  $description    Page description
     *
     * @return  void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Method returns current smarty object.
     *
     * @access  public
     *
     * @return  \Smarty
     */
    public function getSmarty()
    {
        return $this->smarty;
    }

    /**
     * Method adds specified keywords to displayed page.
     *
     * @access  public
     *
     * @param   string|array    $keyword
     *
     * @return  void
     */
    public function addKeyword($keyword)
    {
        // Empty keyword
        if (empty($keyword)) {
            return;
        } // Single keyword as string
        elseif (!is_array($keyword)) {
            $keyword = array($keyword);
        }

        // Sanitize keywords
        $keyword = array_filter(array_unique($keyword));

        if (empty($keyword)) {
            return;
        }

        /**
         * Add keywords
         *
         * NOTE: Do not use array merge, it will regenerate page loading for
         *       some fucking unknown reason.
         */
        $this->keywords = (array)$keyword + (array)$this->keywords;
    }


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
    public function addJavascript($javascript, $append = true)
    {
        ($append === true) ? $this->pageJavascript[] = $javascript : $this->pageJavascript = array($javascript);
    }

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
    public function addCss($css, $media = 'screen, projection', $append = true, $first = false)
    {
        if ($append === false) {
            $this->pageCss = array($css => $media);
        } elseif ($first === true) {
            $this->pageCssFirst[$css] = $media;
        } else {
            $this->pageCss[$css] = $media;
        }
    }

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
    public function display($content = null, $template = 'index.tpl')
    {
        // Initialize current page
        $this->initializePage();

        // Content is set, so overwrite everything else
        if (!is_null($content)) {
            $this->smarty->assign('pageContent', $content);
        }

        // Display page.
        $this->smarty->display($template);

        exit(0);
    }

    /**
     * Method makes exception error page HTML content.
     *
     * @access  public
     *
     * @param   \Exception  $error
     *
     * @return  string
     */
    public function makeExceptionError(\Exception $error)
    {
        $template = $this->smarty->createTemplate('error_exception.tpl', $this->smarty);
        $template->assign('error', $error);

        return $template->fetch();
    }

    /**
     * Method makes 404 page HTML content.
     *
     * @access  public
     *
     * @return  string
     */
    public function make404()
    {
        // Create template
        $template = $this->smarty->createTemplate('404.tpl', $this->smarty);

        return $template->fetch();
    }

    /**
     * Method initialized displayed page.
     *
     * @access  private
     *
     * @return  void
     */
    private function initializePage()
    {
        // Check page specified javascript files
        $this->checkPageJavascript();

        // Check page specified css files
        $this->checkPageCss();

        // Page specified initializing.
        if (method_exists($this, 'preInitializePage')) {
            $this->preInitializePage();
        }

        // Create page keywords
        $this->makeKeywords();

        // Create page description.
        $this->makeDescription();

        // Create messages
        $this->makeMessage();

        // Create page JS section
        $this->makeJavascript();

        // Create page CSS section
        $this->makeCss();

        // Create page title
        $this->makeTitle();

        // Create header data
        $this->makeHeader();

        // Create navigation
        $this->makeNavigation();

        // Create footer data
        $this->makeFooter();
    }

    /**
     * Method makes message boxes to page if any UI messages are present.
     *
     * @access  private
     *
     * @return  void
     */
    private function makeMessage()
    {
        // No messages
        if (!isset($_SESSION['Message']) || !is_array($_SESSION['Message'])) {
            return;
        }

        // Add requires JS library to show actual messages
        $this->addJavascript('jQuery-ctNotify/');

        // Iterate messages.
        foreach ($_SESSION['Message'] as $type => $data) {
            if (empty($data)) {
                continue;
            }

            // Create message template and assign message data to it.
            $message = $this->smarty->createTemplate('js_notify_message.tpl');
            $message->assign('data', $data);
            $message->assign('type', $type);

            // Fetch parsed message template and append it to current page.
            $this->smarty->append('pageScript', $message->fetch());

            unset($message);
        }

        // Reset messages
        unset($_SESSION['Message']);
    }

    /**
     * Method creates necessary <script src=".."></script> definitions for
     * current displayed page.
     *
     * Note that method also includes magically necessary css files for the
     * included javascript libraries.
     *
     * @access  private
     *
     * @return  void
     */
    private function makeJavascript()
    {
        // Reset used temp variables
        $_javascript = array();
        $_content    = '';

        // Iterate javascript definitions
        foreach ($this->pageJavascript as $javascript) {
            // Specify javascript library
            $library = PATH_BASE . 'html/js/' . $javascript;

            // Javascript definition is directory, so fetch all .js files from it.
            if (is_dir($library)) {
                // Iterate founded .js files from directory
                foreach (glob($library . '*.js') as $_filename) {
                    // Add file content to temp variable
                    $_content .= file_get_contents($_filename);

                    // Add javascript file to used template variable
                    $_javascript[] = $_filename;
                }

                // Add possible CSS files for this javascript library
                $this->addCss('js/' . $javascript, 'screen, projection', true, true);
            } elseif (is_readable($library)) { // Javascript file, this is normal situation
                // Add file content to temp variable
                $_content .= file_get_contents($library);

                // Add javascript file to used template variable
                $_javascript[] = $library;
            }
        }

        // Check if production environment, if true we must use compressed CSS files
        if ($this->request->isProduction() === true) {
            $_javascript = $this->getCompressedJavascript($_content);
        }

        unset($_content);

        // Lambda function to cleanup javascript data array
        $cleanUp = function ($library) {
            return str_replace(PATH_BASE . 'html/js/', '', $library);
        };

        // Create javascript template and assign javascript data to it.
        $template = $this->smarty->createTemplate('page_javascript.tpl', $this->smarty);
        $template->assign('data', array_map($cleanUp, $_javascript));

        // Fetch parsed javascript template and assign it to current page.
        $this->smarty->assign('pageJavascript', $template->fetch());

        unset($template);
    }

    /**
     * Method creates necessary <link rel="stylesheet" href=".." /> definitions for
     * current displayed page.
     *
     * @access  private
     *
     * @return  void
     */
    private function makeCss()
    {
        // Reset used temp variables
        $css     = array();
        $content = array();

        // Iterate CSS files
        foreach (array_merge($this->pageCssFirst, $this->pageCss) as $_css => $_media) {
            // Specify CSS library
            $library = PATH_BASE . 'html/css/' . $_css;

            // Initialize content variable for this media definition
            if (!isset($content[$_media])) {
                $content[$_media] = '';
            }

            // CSS directory, so fetch all .css files from it.
            if (is_dir($library)) {
                // Iterate founded CSS files
                foreach (glob($library . '*.css') as $_filename) {
                    // Add file content to temp variable
                    $content[$_media] .= file_get_contents($_filename);

                    // Add CSS file to used template variable
                    $css[str_replace(PATH_BASE . 'html/css/', '', $_filename)] = $_media;
                }
            } elseif (is_readable($library)) { // CSS file, this is normal situation
                // Add file content to temp variable
                $content[$_media] .= file_get_contents($library);

                // Add CSS file to used template variable
                $css[str_replace(PATH_BASE . 'html/css/', '', $library)] = $_media;
            }
        }

        // Check if production environment, if true we must use compressed CSS files
        if ($this->request->isProduction() === true) {
            $css = $this->getCompressedCss($content);
        }

        unset($content);

        // Create css template and assign css data to it.
        $template = $this->smarty->createTemplate('page_css.tpl', $this->smarty);
        $template->assign('data', $css);

        // Fetch parsed css template and assign it to current page.
        $this->smarty->assign('pageCss', $template->fetch());

        unset($template);
    }

    /**
     * Method makes current page keywords and assign it to smarty object.
     *
     * @access  private
     *
     * @return  void
     */
    private function makeKeywords()
    {
        $this->smarty->assign('pageKeywords', $this->keywords);
    }

    /**
     * Method makes current page description and assign it to smarty object.
     *
     * @access  private
     *
     * @return  void
     */
    private function makeDescription()
    {
        $this->smarty->assign('pageDescription', $this->description);
    }

    /**
     * Method makes current page title and assign it to smarty object.
     *
     * @access  private
     *
     * @return  void
     */
    private function makeTitle()
    {
        // Initialize title
        $title = array();

        // Specify used title variables
        $_titles = array(
            'pageTitleAction',
            'pageTitle',
        );

        // Iterate variables
        foreach ($_titles as $_title) {
            if (isset($this->{$_title}) && !empty($this->{$_title})) {
                if (is_array($this->{$_title})) {
                    $title = array_merge($title, $this->{$_title});
                } else {
                    $title[] = $this->{$_title};
                }
            }
        }

        // Set system name to title
        $title[] = SYSTEM_NAME;

        // Create unique not empty title array
        $title = array_unique(array_filter($title));

        // Assign title to smarty object
        $this->smarty->assign('pageTitle', implode(' | ', $title));
    }

    /**
     * Method makes current page header section and assign it to smarty object.
     *
     * @access  private
     *
     * @return  void
     */
    private function makeHeader()
    {
        // Create css template and assign css data to it.
        $template = $this->smarty->createTemplate('header.tpl', $this->smarty);

        $this->smarty->assign('pageHeader', $template->fetch());
    }

    /**
     * Method makes current page footer section and assign it to smarty object.
     *
     * @access  private
     *
     * @return  void
     */
    private function makeFooter()
    {
        // Create css template and assign css data to it.
        $template = $this->smarty->createTemplate('footer.tpl', $this->smarty);

        $this->smarty->assign('pageFooter', $template->fetch());
    }

    /**
     * Method makes page navigation section and assign it to smarty object.
     *
     * @access  private
     *
     * @return  void
     */
    private function makeNavigation()
    {
        // Create css template and assign css data to it.
        $template = $this->smarty->createTemplate('navigation.tpl', $this->smarty);

        $this->smarty->assign('pageNavigation', $template->fetch());
    }

    /**
     * Method makes compressed CSS files for every media type. Method uses YUI
     * Compressor library to make actual content compressing.
     *
     * @access  private
     *
     * @uses    YuiCompressor::compressCss()
     *
     * @param   array   $cssContent CSS content to compress. Key present
     *                              desired media type and actual value is
     *                              string content of CSS.
     *
     * @return  array               CSS files for makeCss -method.
     */
    private function getCompressedCss(&$cssContent)
    {
        // Initialize output array
        $output = array();

        // Iterate media specified CSS content
        foreach ($cssContent as $media => $cssString) {
            // Search strings
            $search = array(
                "url('../",
                "url(img/",
                "url('img/",
                "url(\"img/",
                "url(images",
            );

            // Replacement strings
            $replace = array(
                "url('" . $this->request->getBaseUrl(false, true),
                "url(" . $this->request->getBaseUrl(false, true) . "css/js/bootstrap/img/",
                "url('" . $this->request->getBaseUrl(false, true) . "css/js/bootstrap/img/",
                "url(\"" . $this->request->getBaseUrl(false, true) . "css/js/bootstrap/img/",
                "url(" . $this->request->getBaseUrl(false, true) . "css/js/jQuery-UI/images",
            );

            // Make necessary url replaces
            $cssString = str_replace($search, $replace, $cssString);

            // Specify used cache file for CSS
            $cacheFile = PATH_BASE . 'html/css/cache/' . preg_replace('/[^a-z0-9]+/i', '_', $media) . '_' . sha1(
                $cssString
            ) . '.css';

            // Cache file doesn't exists so we must create new one
            if (!is_readable($cacheFile)) {
                // Get compressed CSS content
                $compressedCss = YuiCompressor::compressCss($cssString);

                // Create new cache file
                file_put_contents($cacheFile, $compressedCss);
            }

            $output[str_replace(PATH_BASE . 'html/css/', '', $cacheFile)] = $media;
        }

        return $output;
    }

    /**
     * Method makes compressed Javascript files. Method uses YUI
     * Compressor library to make actual content compressing.
     *
     * @access  private
     *
     * @param   array   $javascriptContent  Javascript content to compress.
     *
     * @return  array                       Javascript file for makeJavascript -method.
     */
    private function getCompressedJavascript($javascriptContent)
    {
        // Specify used cache file for Javascript
        $cacheFile = PATH_BASE . 'html/js/cache/' . sha1($javascriptContent) . '.js';

        // Cache file doesn't exists so we must create new one
        if (!is_readable($cacheFile)) {
            // Get compressed CSS content
            $compressedJavascript = YuiCompressor::compressJavascript($javascriptContent);

            // Create new cache file
            file_put_contents($cacheFile, $compressedJavascript);
        }

        return array($cacheFile);
    }

    /**
     * Method checks if current page has defined javascript files which must
     * be appended to current view.
     *
     * @access  private
     *
     * @uses    \HomeAI\Page\View::checkPageData()
     *
     * @return  void
     */
    private function checkPageJavascript()
    {
        // Define module javascript dir
        $directory = PATH_BASE . "html/js/Module/" . $this->module . "/";

        $this->checkPageData(View::CHECK_TYPE_JAVASCRIPT, $directory);
    }


    /**
     * Method checks if current page has defined css files which must be
     * appended to current view.
     *
     * @access  private
     *
     * @uses    \HomeAI\Page\View::checkPageData()
     *
     * @return  void
     */
    private function checkPageCss()
    {
        $directory = PATH_BASE . "html/css/Module/" . $this->module . "/";

        $this->checkPageData(View::CHECK_TYPE_CSS, $directory);
    }

    /**
     * Method check css / javascript files from specified directory and adds
     * them to current view if it's necessary.
     *
     * @access  private
     *
     * @throws  \HomeAI\Module\Exception
     *
     * @param   string  $type       See CHECK_TYPE_* -constants
     * @param   string  $directory  Directory specification
     *
     * @return  void
     */
    private function checkPageData($type, $directory)
    {
        // Directory doesn't exists so do not continue
        if (!is_dir($directory)) {
            return;
        }

        switch ($type) {
            case View::CHECK_TYPE_CSS:
                $extension = '.css';
                $method    = 'addCss';
                break;
            case View::CHECK_TYPE_JAVASCRIPT:
                $extension = '.js';
                $method    = 'addJavascript';
                break;
            default:
                $message = sprintf(
                    "Unknown type: '%s",
                    $type
                );

                throw new Exception($message);
                break;
        }

        // Define searched files
        $files = array(
            $this->module,
            $this->action,
        );

        foreach ($files as $filename) {
            // File is readable, so add it to current view
            if (is_readable($directory . $filename . $extension)) {
                call_user_func(array($this, $method), "Module/" . $this->module . "/" . $filename . $extension);
            }
        }
    }

    /**
     * Method initializes Smarty library to use.
     *
     * @access  private
     *
     * @return  void
     */
    private function initializeSmarty()
    {
        // Require smarty base class
        require_once PATH_BASE . 'libs/Smarty/libs/Smarty.class.php';

        // Create smarty object
        $this->smarty = new \Smarty;

        // Define smarty variables
        $this->smarty->setTemplateDir(
            array(
                PATH_BASE . 'templates/' . $this->module . '/',
                PATH_BASE . 'templates/',
            )
        );

        // Set some smarty settings
        $this->smarty->setCompileDir(PATH_BASE . 'templates_cache/');
        $this->smarty->setConfigDir(PATH_BASE . 'config/');
        $this->smarty->setCacheDir(PATH_BASE . 'cache/');
        $this->smarty->setPluginsDir(array_merge($this->smarty->getPluginsDir(), array(PATH_BASE . 'php/Smarty/')));

        // No page cache so clear all
        if ($this->pageCache === false) {
            $this->smarty->clearAllCache();
        }

        // Initialize global page template variables
        $this->initializeDefaultTemplateVariables();
    }

    /**
     * Method initializes used global smarty template variables.
     *
     * @access  private
     *
     * @return  void
     */
    private function initializeDefaultTemplateVariables()
    {
        // Global template variables.
        $templateVars = array(
            // Generic template variables
            'pageTitle'           => '',
            'pageKeywords'        => '',
            'pageDescription'     => '',
            'pageImage'           => '',
            'pageBaseHref'        => $this->request->getBaseUrl(false, true),
            'pageName'            => $this->module,
            'pageAction'          => $this->action,

            // Javascript and css variables
            'pageScript'          => '',
            'pageJavascript'      => '',
            'pageCss'             => '',

            // Actual content variables
            'pageHeader'          => '',
            'pageContent'         => '',
            'pageFooter'          => '',
            'pageNavigation'      => '',

            // Is user logged on or not
            'admin'               => Login::$auth,

            // System variables
            'systemVersion'       => SYSTEM_VERSION,
            'systemEmail'         => Config::readIni('config.ini', 'System', 'Email'),

            // Google specified variables
            'googleVerifyCode'    => Config::readIni('config.ini', 'System', 'GoogleV1'),
            'googleAnalyticsCode' => Config::readIni('config.ini', 'System', 'Analytics'),
        );

        // Iterate template variables and assign values to them
        foreach ($templateVars as $variable => $value) {
            $this->smarty->assign($variable, $value);
        }
    }
}
