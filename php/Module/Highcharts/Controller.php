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
        $renderTo = $this->request->get('renderTo');

        echo $this->view->makeJsonExample($renderTo);
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
            $renderTo = $this->request->get('renderTo');

            $output = $this->view->makeJsonExampleLive($renderTo);
        }

        echo $output;
        exit(0);
    }
}
