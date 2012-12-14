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
                        'title'         => 'Content fetched via cUrl',
                        'column'        => 'third',
                        'editurl'       => '',
                        'open'          => true,
                        'metadata'      => array(
                            'type'      => 'curl',
                            'data'      => array(
                                'url'   => 'http://wunder.sytes.net/fizzbuzz.php',
                            ),
                        ),
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
                    ),
                ),
            ),
        );
    }
}
