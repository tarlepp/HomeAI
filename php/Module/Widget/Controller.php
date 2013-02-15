<?php
/**
 * \php\Module\Widget\Controller.php
 *
 * @package     Module
 * @subpackage  Widget
 * @category    Controller
 */
namespace HomeAI\Module\Widget;

use HomeAI\Module\Controller as MController;
use HomeAI\Core\Exception as ExceptionCore;
use HomeAI\Util\String as String;
use HomeAI\Util\JSON as JSON;
use HomeAI\Util\UUID as UUID;
use HomeAI\Util\Network as Network;

/**
 * Controller class for 'Widget' -module.
 *
 * @package     Module
 * @subpackage  Widget
 * @category    Controller
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Controller extends MController implements Interfaces\Controller
{
    /**
     * @var \HomeAI\Module\Widget\View
     */
    protected $view;

    /**
     * @var \HomeAI\Module\Widget\Model
     */
    protected $model;

    /**
     * Current widget data from Dashboard.js
     *
     * @var array
     */
    protected $widgetData = array();

    /**
     * General request initializer. This is method is called before any
     * actual handleRequest* - method calls.
     *
     * In this module we accept only ajax request.
     *
     * @return  void
     */
    protected function initializeRequest()
    {
        // Only allow AJAX requests
        if (!$this->request->isAjax()) {
            header('HTTP/1.1 400 Bad Request');
            exit(0);
        }

        // Store current request widget data if any
        $this->widgetData = $this->request->get('widgetData', array());
    }

    /**
     * Method handles 'Widget' -module default action.
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestDefault()
    {
        // TODO
        echo "Make list of all available widgets or something?";
        exit(__FILE__ .":". __LINE__);
    }

    /**
     * Method makes widget clock HTML content and echoes it to client.
     *
     * @id          Clock
     * @title       Clock
     * @description This is a simple widget which will shown a clock with time and current date. Everybody needs a <em>clock</em>, right?
     * @category    Common
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestClock()
    {
        echo $this->view->makeClock();
        exit(0);
    }

    /**
     * Method makes egg timer widget HTML content and echoes it to client.
     *
     * @id          EggTimer
     * @title       Egg Timer
     * @description This widget is used to generate all-purpose egg timer. You can specify desired timer value and let the widget notify you when the <em>eggs are ready</em>... Everyone needs this!
     * @category    Common
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestEggTimer()
    {
        echo $this->view->makeEggTimer();
        exit(0);
    }

    /**
     * Method handles basic cUrl request to specified url.
     *
     * @title       cURL
     * @description With this widget you can fetch specified url contents to be shown in widget content. You can specify used parameters for actual cURL request if those are needed.
     * @category    Network
     * @refreshable true
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestCurl()
    {
        // Get used parameters
        $url = $this->request->get('url', null);
        $type = $this->request->get('type', 'GET');
        $headers = (array)$this->request->get('headers', array());
        $postData = (array)$this->request->get('postData', array());

        if (is_null($url)) {
            $output = array(
                'content'   => 'URL not defined.',
                'headers'   => '',
                'stats'     => '',
            );
        } else {
            list($content, $status, $headers) = $this->model->getCurlResponse($url, $type, $headers, $postData);

$time = 0;

            $size = memory_get_usage();

            $unit=array('b','kb','mb','gb','tb','pb');
            $size = @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];

            $output = array(
                'content'   => trim($content),
                'headers'   => trim($headers),
                'stats'     => "Status ". Network::getStatusCodeString($status) ."\nRequest time: ". $time ."s\nMemory: ". $size ,
            );

            //echo $this->model->getCurlResponse($url, $type, $headers, $postData);
        }

        echo JSON::encode($output);

        exit(0);
    }

    /**
     * Method handles RSS feed request. Basically method will fetch
     * RSS feed items from specified URL and shows them in RSS widget
     * template.
     *
     * @title       RSS Reader
     * @description Generic RSS feed reader widget. With this you can specify desired RSS url where to fetch items to be shown in widget.
     * @category    Network
     * @refreshable true
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestRss()
    {
        // Get used parameters
        $url = $this->request->get('url', null);
        $limit = $this->request->get('limit', 5);

        if (is_null($url)) {
            echo "RSS feed URL not defined.";
        } else {
            require_once PATH_BASE .'libs/simplepie/autoloader.php';

            echo $this->view->makeRssFeed($this->model->getRssItems($url, $limit));
        }

        exit(0);
    }

    /**
     * Method handles highcharts widget content request. Basically method
     * will fetch highcharts content from specified URL and shows it in widget
     * template.
     *
     * Note that used highcharts config (JSON data) is fetched in separated
     * method and used in actual view method.
     *
     * @title       Highcharts
     * @description Generic Highcharts widget.
     * @category    Charts
     * @refreshable true
     *
     * @access  public
     *
     * @throws  \HomeAI\Module\Widget\Exception
     *
     * @return  void
     */
    public function handleRequestHighcharts()
    {
        // Get used parameters
        $id = $this->request->get('id', null);
        $url = $this->request->get('url', null);
        $class = $this->request->get('class', 'widgetHighcharts');

        try {
            if (is_null($url)) {
                throw new Exception("Highcharts data url not defined.");
            } else {
                $data = array(
                    'renderTo'  => $id,
                    'widget'    => $this->widgetData,
                );

                /**
                 * Fetch used Highcharts config, This is a JSON
                 * encoded string which contains all needed and
                 * desired options for Highcharts.
                 *
                 * See more at: http://api.highcharts.com/highcharts
                 */
                $config = $this->model->getHighchartsConfig($url, $data);

                // Used extra options for smarty template
                $options = array(
                    'class' => $class,
                );

                echo $this->view->makeHighcharts($id, $config, $options);
            }
        } catch (ExceptionCore $error) {
            $error->makeJsonResponse();
        }

        exit(0);
    }

    /**
     * Method determines widget categories and echoes JSON data about them.
     * These categories are used to navigate between actual widgets.
     *
     * Widget categories are determined via method comments.
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestGetCategories()
    {
        // Initialize categories array
        $categories = array();

        // Store HomeAI base url
        $url = $this->request->getBaseUrl(false, true);

        // Create reflection about self
        $reflection = new \ReflectionObject($this);

        /**
         * @var $method \ReflectionMethod
         */
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            // Get method comments
            $comments = String::parseDocBlock($method->getDocComment());

            // Method is not a widget so continue to next one
            if (!$this->isWidget($comments, $method->getName())) {
                continue;
            }

            // Category not yet initialized
            if (!isset($categories[$comments['category']])) {
                $categories[$comments['category']] = array(
                    'id'        => UUID::v4(),
                    'title'     => $comments['category'],
                    'url'       => $url ."Widget/GetCategoryWidgets/". $comments['category'],
                    'amount'    => 1,
                );
            } else {
                $categories[$comments['category']]['amount']++;
            }
        }

        // Sort categories
        ksort($categories);

        // Create output array
        $output = array(
            'categories' => array(
                'category' => array_values($categories),
            ),
        );

        // Generate used JSON headers
        JSON::makeHeaders();

        // Output data
        echo JSON::encode($output);
        exit(0);
    }

    /**
     * Method fetches specified category widgets and echoes JSON data about them.
     * This data is shown below each widget category.
     *
     * @access  public
     *
     * @param   string  $category
     *
     * @return  void
     */
    public function handleRequestGetCategoryWidgets($category)
    {
        // Initialize widgets array
        $widgets = array();

        // Create reflection about self
        $reflection = new \ReflectionObject($this);

        /**
         * @var $method \ReflectionMethod
         */
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            // Parse method comments
            $comments = String::parseDocBlock($method->getDocComment());

            // Method is not a widget method or category differs from specified
            if (!$this->isWidget($comments, $method->getName()) || $comments['category'] !== $category) {
                continue;
            }

            // Store widget data
            $widgets[$comments['title']] = $comments;
        }

        // Sort categories
        ksort($widgets);

        $output = array(
            'result' => array(
                'data' => array_values($widgets),
            ),
        );

        // Generate used JSON headers
        JSON::makeHeaders();

        // Output data
        echo JSON::encode($output);
        exit(0);
    }

    /**
     * Method handles widget save request. Method handles both 'insert' and 'update'
     * actions for widgets. Method echoes widget data as a JSON string if save action
     * was made successfully. Otherwise method echoes error JSON string which is
     * processed in javascript.
     *
     * @throws  Exception
     *
     * @return  void
     */
    public function handleRequestSave()
    {
        try {
            $type = (string)$this->request->get('type');
            $data = (array)$this->request->get('data', array());
            $widget = (array)$this->request->get('widget', array());

            if (!(strcmp($type, 'update') === 0 || strcmp($type, 'insert') === 0)) {
                throw new Exception("Unknown type.");
            }

            if (empty($data)) {
                throw new Exception("Missing widget content data.");
            }

            if (empty($widget)) {
                throw new Exception("Missing widget data.");
            }

            $output = $this->model->store($type, $data, $widget);
        } catch (\Exception $error) {
            $output = array(
                'error' => $error->getMessage(),
            );
        }

        echo JSON::encode($output);
        exit(0);
    }

    /**
     * Generic widget setup handler.
     *
     * @access  public
     *
     * @param   string  $widgetName Name of the widget
     *
     * @return  void
     */
    public function handleRequestSetup($widgetName)
    {
        // Fetch widget data and actual content data if any
        $data = (array)$this->request->get('data', array());
        $widget = (array)$this->request->get('widget', array());

        // Determine setup method name
        $method = 'widgetSetup'. $widgetName;

        if (empty($widget)) {
            $widget = $this->getWidgetData($widgetName);

            if (!is_array($widget)) {
                die('TODO '. __FILE__ .":". __LINE__);
            }
        }

        // Call actual widget setup method if it exists, otherwise show error
        if (method_exists($this, $method)) {
            call_user_func_array(array($this, $method), array($widget, $data));
        } else {
            echo $this->view->makeSetupMethodNotFound($widgetName, $method, __CLASS__, $widget, $data);
        }

        exit(0);
    }

    /**
     * Method makes setup for 'Curl' -widget.
     *
     * @access  public
     *
     * @param   array   $widget Widget data
     * @param   array   $data   Widget content data
     *
     * @return  void
     */
    public function widgetSetupCurl(array $widget, array $data)
    {
        echo $this->view->makeSetupCurl($widget, $data);
    }

    /**
     * Method makes setup for 'RSS' -widget.
     *
     * @access  public
     *
     * @param   array   $widget Widget data
     * @param   array   $data   Widget content data
     *
     * @return  void
     */
    public function widgetSetupRss(array $widget, array $data)
    {
        echo $this->view->makeSetupRss($widget, $data);
    }

    /**
     * Method determines specified widget data. Widget data is determined
     * via it's metadata which is defined to actual widget -method comments.
     *
     * @param   string  $widgetName
     *
     * @return  array|null
     */
    private function getWidgetData($widgetName)
    {
        // Specify widget method name
        $method = 'handleRequest'. $widgetName;

        // Get widget comments and parse it
        $method = new \ReflectionMethod($this, $method);
        $comments = String::parseDocBlock($method->getDocComment());

        // No valid widget
        if (!$this->isWidget($comments, $method->getName())) {
            return null;
        }

        return $comments;
    }

    /**
     * Method checks if specified comment block contains all necessary widget
     * metadata information or not.
     *
     * Note that method will modify given comment block data array.
     *
     * @todo    Check if widget image exists on fs.
     *
     * @param   array   $comments   Method comments
     * @param   string  $methodName Name of the method
     *
     * @return  bool
     */
    private function isWidget(&$comments, $methodName)
    {
        // Store HomeAI base url
        $url = $this->request->getBaseUrl(false, true);

        // Widget metadata properties
        $properties = array(
            'id'            => array(
                'required'  => false,
                'default'   => UUID::v4(),
            ),
            'title'         => array(
                'required'  => true,
            ),
            'description'   => array(
                'required'  => true,
            ),
            'category'      => array(
                'required'  => true,
            ),
            'creator'       => array(
                'required'  => false,
                'default'   => '<em>author not defined</em>',
            ),
            'url'           => array(
                'required'  => false,
                'default'   => $url .'Widget/'. str_replace('handleRequest', '', $methodName),
            ),
            'image'         => array(
                'required'  => false,
                'default'   => $url .'images/widgets/'. str_replace('handleRequest', '', $methodName) .'.jpg',
            ),
            'refreshable'   => array(
                'required'  => false,
                'default'   => false,
                'convert'   => 'boolean'
            )
        );

        // Iterate "default" properties
        foreach ($properties as $property => $values) {

            // Property is required, but it doesn't exists
            if ($values['required'] && !isset($comments[$property])) {
                return false;
            } elseif (!isset($comments[$property])) { // Add default value to property
                $comments[$property] = $values['default'];
            }

            // Type cast comment values
            if (isset($values['convert'])) {
                switch ($values['convert']) {
                    case 'boolean':
                        $comments[$property] = $comments[$property] == 'true' ? true : false;
                        break;
                }
            }
        }

        $comments['method'] = str_replace('handleRequest', '', $methodName);

        return true;
    }
}
