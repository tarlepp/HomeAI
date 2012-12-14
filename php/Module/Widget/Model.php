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
     * @param   string  $url        URL to fetch
     * @param   array   $options    Possible cUrl options, following keys:
     *                               - type     = string, Request type
     *                               - headers  = array, Used request headers
     *                               - data     = array, Post fields
     *
     * @return  string
     */
    public function getCurlResponse($url, $options)
    {
        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, \CURLOPT_URL, $url);
        curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);

        // Request type
        if (isset($options['type'])) {
            curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, $options['type']);
        }

        // Request headers
        if (isset($options['headers'])) {
            curl_setopt($ch, \CURLOPT_HTTPHEADER, $options['headers']);
        }

        // Request post fields
        if (isset($options['data'])) {
            curl_setopt($ch, \CURLOPT_POSTFIELDS, http_build_query($options['data']));
        }

        // Get HTTP status code and actual response from server
        $content = trim(curl_exec($ch));
        $status  = curl_getinfo($ch, \CURLINFO_HTTP_CODE);

        // Error occurred
        if ($status >= 400) {
            $content = "<h1>Error occured</h1><p>HTTP status code: ". $status ."<br />Content:<br />". $content;
        } elseif (empty($content)) {
            $content = "Content not found..";
        }

        return $content;
    }

    /**
     * Method fetches specified amount of items from specified RSS feed URL and
     * returns array of \SimplePie_Item objects or null if feed doesn't contain
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
}
