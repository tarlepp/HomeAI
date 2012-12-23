<?php
/**
 * \php\Page\Widget\Controller.php
 *
 * @package     Module
 * @subpackage  Widget
 * @category    Controller
 */
namespace HomeAI\Module\Widget;

use HomeAI\Module\Controller as MController;

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
    protected $view = null;

    /**
     * @var \HomeAI\Module\Widget\Model
     */
    protected $model = null;

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
        if (!$this->request->isAjax()) {
            header('HTTP/1.1 400 Bad Request');
            exit(0);
        }
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
        echo "TODO: make list of all widgets";
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
        $setter = (bool)$this->request->get('setter', false);

        if ($setter) {
            echo $this->view->makeEggTimerSetup();
        } else {
            echo $this->view->makeEggTimer();
        }

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
        $limit = $this->request->get('limit', array());

        if (is_null($url)) {
            echo "RSS feed URL not defined.";

        } else {
            require_once PATH_BASE .'libs/simplepie/autoloader.php';

            echo $this->view->makeRssFeed($this->model->getRssItems($url, $limit));
        }

        exit(0);
    }

    public function handleRequestHighchart()
    {
        echo "<pre>";
        print_r($this->request->get());
        echo "</pre>";
        echo "implement highchart here...";

        exit(0);
    }
}
