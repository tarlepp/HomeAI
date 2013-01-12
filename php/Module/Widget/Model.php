<?php
/**
 * \php\Module\Widget\Model.php
 *
 * @package     Module
 * @subpackage  Widget
 * @category    Model
 */
namespace HomeAI\Module\Widget;

use HomeAI\Module\Model as MModel;
use HomeAI\Util\Network as Network;

/**
 * Model class for 'Widget' -Module.
 *
 * @package     Module
 * @subpackage  Widget
 * @category    Model
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Model extends MModel implements Interfaces\Model
{
    /**
     * Method makes cUrl request to specified url using specified headers and
     * post data. Method will return actual http response from specified url.
     *
     * This response is shown in the cUrl widget content.
     *
     * @param   string  $url        Url to fetch
     * @param   string  $type       Request type
     * @param   array   $headers    Used headers
     * @param   array   $postData   Used post data
     *
     * @return  string
     */
    public function getCurlResponse($url, $type, array $headers, array $postData)
    {
        // Filter headers and post data
        $headers = array_filter($headers);
        $postData = array_filter($postData);

        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, \CURLOPT_URL, $url);
        curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, $type);                    // Request type
        curl_setopt($ch, \CURLOPT_HTTPHEADER, $headers);                    // Request headers
        curl_setopt($ch, \CURLOPT_POSTFIELDS, http_build_query($postData)); // Request post fields

        // Get HTTP status code and actual response from server
        $content = trim(curl_exec($ch));
        $status  = curl_getinfo($ch, \CURLINFO_HTTP_CODE);

        // Error occurred
        if ($status >= 400) {
            $content = "<h1>Error occurred</h1><p>"
                    . Network::getStatusCodeString($status)
                    . ".<br />Content:<br />"
                    . $content;
        } elseif (empty($content)) {
            $content = "Content not found...";
        }

        curl_close($ch);

        return $content;
    }

    /**
     * Method fetches specified amount of items from specified RSS feed URL and
     * returns array of \SimplePie_Item objects or null if feed does not contain
     * any items.
     *
     * @param   string  $url    RSS feed url
     * @param   integer $limit  Item limit
     *
     * @return  \SimplePie_Item[]|null
     */
    public function getRssItems($url, $limit)
    {
        $feed = new \SimplePie();
        $feed->set_feed_url($url);
        $feed->enable_cache(false);
        $feed->enable_exceptions(true);
        $feed->init();

        return $feed->get_items(0, $limit);
    }

    /**
     * Method fetches used config JSON string for Highcharts.
     *
     * @throws  Exception
     *
     * @param   string  $url        URL for the highcharts config
     * @param   array   $postData   Used post data for request
     *
     * @return  string
     */
    public function getHighchartsConfig($url, array $postData)
    {
        // Initialize cURL
        $ch = curl_init();

        // Set custom headers to "fake" that request is made via AJAX
        $headers = array(
            'X-REQUESTED-WITH: XMLHttpRequest',
        );

        // Set cURL options
        curl_setopt($ch, \CURLOPT_URL, $url);
        curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, \CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, \CURLOPT_POSTFIELDS, http_build_query($postData));

        // Get HTTP content and status
        $content = trim(curl_exec($ch));
        $status  = curl_getinfo($ch, \CURLINFO_HTTP_CODE);

        curl_close($ch);

        // Error occurred
        if ($status >= 400) {
            throw new Exception(Network::getStatusCodeString($status), $status);
        }

        return $content;
    }
}
