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
use HomeAI\Core\ExceptionJson as ExceptionJson;
use HomeAI\Core\Exception as ExceptionCore;

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
    }

    /**
     * Method makes widget clock HTML content and echoes it to client.
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
     * @access  public
     *
     * @return  void
     */
    public function handleRequestCurl()
    {
        // Get used parameters
        $url = $this->request->get('url', null);
        $options = $this->request->get('options', array());

        if (is_null($url)) {
            echo "URL not defined.";
        } else {
            echo $this->model->getCurlResponse($url, $options);
        }

        exit(0);
    }

    /**
     * Method handles RSS feed request. Basically method will fetch
     * RSS feed items from specified URL and shows them in RSS widget
     * template.
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
     * @access  public
     *
     * @throws  \HomeAI\Core\ExceptionJson
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
                throw new ExceptionJson("Highcharts data url not defined.");
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
}
