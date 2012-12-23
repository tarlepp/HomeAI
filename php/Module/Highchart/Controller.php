<?php
/**
 * \php\Page\Highchart\Controller.php
 *
 * @package     Module
 * @subpackage  Highchart
 * @category    Controller
 */
namespace HomeAI\Module\Highchart;

use HomeAI\Module\Controller as MController;

/**
 * Controller class for 'Highchart' -module.
 *
 * @package     Module
 * @subpackage  Highchart
 * @category    Controller
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Controller extends MController implements Interfaces\Controller
{
    /**
     * @var \HomeAI\Module\Highchart\View
     */
    protected $view = null;

    /**
     * @var \HomeAI\Module\Highchart\Model
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
     * Method handles 'Highchart' -module default action.
     *
     * @access  public
     *
     * @return  void
     */
    public function handleRequestDefault()
    {
        echo "TODO: wut the fuck";
    }

    public function handleRequestTest()
    {
        echo "implement highchart here...";

        exit(0);
    }
}
