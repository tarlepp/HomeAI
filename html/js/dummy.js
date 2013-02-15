/**
 * \html\js\dummy.js
 *
 * This file is just for the IDE, basically we need to define all 'dynamic' javascript
 * variables in this file otherwise smart IDE does not recognize these.
 *
 * Note that we can also define JSON structures, so IDE can auto complete them as well.
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Core
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */


var pageBaseHref = '';  // Home AI base URL
var pageModule = '';    // Current module name
var pageAction = '';    // Current action name

function getWidgetValidationRules() {}
function getWidgetData() {}

var Widget = {
    'Curl': {
        'Data': function () {
            return {
                content: 'cUrl response',
                headers: 'Response headers',
                stats: 'Request stats'
            }
        }
    }
};
