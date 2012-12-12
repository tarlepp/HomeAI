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
        return array(
            'result'            => array(
                'layout'        => 'layout5',
                'data'          => array(
                    array(
                        'id'        => 'widget1',
                        'title'     => 'widget 1 tooltip',
                        'column'    => 'first',
                        'editurl'   => '',
                        'open'      => true,
                        'url'       => '',
                    ),
                    array(
                        'id'        => 'widget2',
                        'title'     => 'widget 2 tooltip',
                        'column'    => 'third',
                        'editurl'   => '',
                        'open'      => true,
                        'url'       => '',
                    ),
                    array(
                        'id'        => 'widget3',
                        'title'     => 'widget 3 tooltip',
                        'column'    => 'second',
                        'editurl'   => '',
                        'open'      => true,
                        'url'       => '',
                    ),
                ),
            ),
        );

        /**
         * {
        "result" :
        {
        "layout": "layout2",
        "data" : [
        {
        "title" : "Documentation",
        "id" : "widget1",
        "column" : "first",
        "editurl" : "widgets/editwidget1.html",
        "open" : true,
        "url" : "widgets/widget1.html"
        },
        {
        "title" : "Download plugin",
        "column" : "second",
        "id" : "widget3",
        "url" : "widgets/widget3.html",
        "editurl" : "widgets/editwidget3.html",
        "open" : true
        },
        {
        "title" : "Examples",
        "id" : "widget2",
        "column" : "second",
        "url" : "widgets/widget2.html",
        "editurl" : "widgets/editwidget2.html",
        "open" : true
        }
        ]
        }
        }
         */


        die('asdfasdf');
    }
}
