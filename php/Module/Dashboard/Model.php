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
     * Getter method for current user widgets.
     *
     * @access  public
     *
     * @return  array
     */
    public function getWidgets()
    {
        $output = $this->request->getSession('widgets', null);

        if (is_null($output)) {
            $output = $this->getDefaultWidgets();

            $this->request->setSession('widgets', $output);
        }

        return $output;
    }

    /**
     * Setter method for current user widgets.
     *
     * @access  public
     *
     * @param   array   $widgets
     *
     * @return  void
     */
    public function setWidgets(array $widgets)
    {
        $this->request->setSession('widgets', $widgets);
    }

    /**
     * Method reset user widgets.
     *
     * @access  public
     *
     * @return  void
     */
    public function resetWidgets()
    {
        $this->request->removeSession('widgets');
    }

    /**
     * Getter method for default widgets.
     *
     * TODO: Specify default widgets later...
     *
     * @access  protected
     *
     * @return  array
     */
    protected function getDefaultWidgets()
    {
        $url = $this->request->getBaseUrl(false, true);

        return array(
            'result'                    => array(
                'layout'                => 'layout5',
                'data'                  => array(
                    array(
                        'id'            => 'Clock',
                        'title'         => 'Clock',
                        'column'        => 'first',
                        'open'          => true,
                        'url'           => $url . '/Widget/Clock',
                        'method'        => 'Clock',
                    ),
                    array(
                        'id'            => 'EggTimer',
                        'title'         => 'Egg Timer',
                        'column'        => 'first',
                        'open'          => true,
                        'url'           => $url . '/Widget/EggTimer',
                        'method'        => 'EggTimer',
                    ),
                    array(
                        'id'            => 'widget3',
                        'title'         => 'RSS feed from ksml.fi',
                        'column'        => 'second',
                        'open'          => true,
                        'metadata'      => array(
                            'type'      => 'rss',
                            'data'      => array(
                                'url'   => 'http://www.ksml.fi/?service=rss',
                                'limit' => 5,
                            ),
                        ),
                        'refresh'       => 120,
                        'method'        => 'Rss',
                    ),
                    array(
                        'id'            => 'widget4',
                        'title'         => 'RSS feed from hs.fi',
                        'column'        => 'second',
                        'open'          => true,
                        'metadata'      => array(
                            'type'      => 'rss',
                            'data'      => array(
                                'url'   => 'http://www.hs.fi/uutiset/rss/',
                                'limit' => 5,
                            ),
                        ),
                        'refresh'       => 120,
                        'method'        => 'Rss',
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
                        'method'        => 'Curl',
                    ),
                    array(
                        'id'            => 'widgetAmpparit',
                        'title'         => 'Uusimmat uutiset (ampparit.com)',
                        'column'        => 'third',
                        'open'          => true,
                        'metadata'      => array(
                            'type'      => 'rss',
                            'data'      => array(
                                'url'   => 'http://feeds.feedburner.com/ampparit-uutiset',
                                'limit' => 10,
                            ),
                        ),
                        'refresh'       => 60,
                        'method'        => 'Rss',
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
}
