<?php
/**
 * \php\Module\Highcharts\Controller.php
 *
 * @package     Module
 * @subpackage  Highcharts
 * @category    Controller
 */
namespace HomeAI\Module\Highcharts;

use HomeAI\Module\Controller as MController;
use HomeAI\Util\JSON as JSON;

/**
 * Controller class for 'Highcharts' -module.
 *
 * @package     Module
 * @subpackage  Highcharts
 * @category    Controller
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Controller extends MController implements Interfaces\Controller
{
    /**
     * @var \HomeAI\Module\Highcharts\View
     */
    protected $view;

    /**
     * @var \HomeAI\Module\Highcharts\Model
     */
    protected $model;

    /**
     * Highcharts container id where to render it
     *
     * @var string
     */
    protected $renderTo = '';

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
        // Only accept AJAX requests
        if (!$this->request->isAjax()) {
            header('HTTP/1.1 400 Bad Request');
            exit(0);
        }

        // Store Highcharts container id where to render to if any
        $this->renderTo = $this->request->get('renderTo', '');

        // Store current request widget data if any
        $this->widgetData = $this->request->get('widget', array());
    }

    /**
     * Method handles 'Highcharts' -module default action.
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestDefault()
    {
        // TODO
        echo "Do we need to show some default examples or something?";
    }

    /**
     * Method handles example request for highcharts.
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestExample()
    {
        echo $this->view->makeJsonExample($this->renderTo);
        exit(0);
    }

    /**
     * Method handles example live request for highcharts.
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestExampleLive()
    {
        $type = $this->request->get('type', null);

        if ($type == 'data') {
            $points = (int)$this->request->get('points', 20);

            $output = JSON::encode($this->model->getLiveData($points));
        } else {
            $output = $this->view->makeJsonExampleLive($this->renderTo, $this->widgetData);
        }

        echo $output;
        exit(0);
    }
}
