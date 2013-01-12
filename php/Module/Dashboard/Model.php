<?php
/**
 * \php\Module\Dashboard\Model.php
 *
 * @package     Module
 * @subpackage  Dashboard
 * @category    Model
 */
namespace HomeAI\Module\Dashboard;

use HomeAI\Module\Model as MModel;
use HomeAI\Module\Widget\Controller as WidgetController;

/**
 * Model class for 'Dashboard' -Module.
 *
 * @package     Module
 * @subpackage  Dashboard
 * @category    Model
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Model extends MModel implements Interfaces\Model
{
    /**
     * TODO: make these widgets to be stored to database...
     *
     * @return array
     */
    public function getMyWidgets()
    {
        $url = $this->request->getBaseUrl(false, true);

        return array(
            'result'                    => array(
                'layout'                => 'layout5',
                'data'                  => array(
                    array(
                        'id'            => 'widget1',
                        'title'         => 'Clock',
                        'column'        => 'first',
                        'editurl'       => '',
                        'open'          => true,
                        'url'           => $url . '/Widget/Clock',
                    ),
                    array(
                        'id'            => 'widget2',
                        'title'         => 'Egg Timer',
                        'column'        => 'first',
                        'editurl'       => '',
                        'open'          => true,
                        'url'           => $url . '/Widget/EggTimer',
                    ),
                    array(
                        'id'            => 'widget3',
                        'title'         => 'RSS feed from ksml.fi',
                        'column'        => 'second',
                        'editurl'       => '',
                        'open'          => true,
                        'metadata'      =>  array(
                            'type'      => 'rss',
                            'data'      => array(
                                'url'   => 'http://www.ksml.fi/?service=rss',
                                'limit' => 5,
                            ),
                        ),
                        'refresh'       => 120,
                    ),
                    array(
                        'id'            => 'widget4',
                        'title'         => 'RSS feed from hs.fi',
                        'column'        => 'second',
                        'editurl'       => '',
                        'open'          => true,
                        'metadata'      =>  array(
                            'type'      => 'rss',
                            'data'      => array(
                                'url'   => 'http://www.hs.fi/uutiset/rss/',
                                'limit' => 5,
                            ),
                        ),
                        'refresh'       => 60,
                    ),
                    array(
                        'id'            => 'widget5',
                        'title'         => 'Content fetched via cUrl',
                        'column'        => 'first',
                        'editurl'       => '',
                        'open'          => true,
                        'metadata'      => array(
                            'type'      => 'curl',
                            'data'      => array(
                                'url'   => 'http://wunder.sytes.net/fizzbuzz.php',
                            ),
                        ),
                    ),
                    /*
                    array(
                        'id'            => 'widget6',
                        'title'         => 'Highcharts example 1',
                        'column'        => 'third',
                        'editurl'       => '',
                        'open'          => true,
                        'metadata'      =>  array(
                            'type'      => 'highcharts',
                            'data'      => array(
                                'url'   => $url . 'Highcharts/Example',
                                'id'    => 'HighchartsExample',
                                'class' => 'highchartsExampleClass',
                            ),
                        ),
                    ),

                    array(
                        'id'            => 'widget7',
                        'title'         => 'Highcharts live example',
                        'column'        => 'third',
                        'editurl'       => '',
                        'open'          => true,
                        'metadata'      =>  array(
                            'type'      => 'highcharts',
                            'data'      => array(
                                'url'   => $url . 'Highcharts/ExampleLive',
                                'id'    => 'HighchartsExampleLive',
                                'class' => 'highchartsExampleLiveClass',
                            ),
                        ),
                    ),
                    */
                ),
            ),
        );
    }

    public function getCategories()
    {
        return (new WidgetController($this->request))->getCategories();
    }
}
