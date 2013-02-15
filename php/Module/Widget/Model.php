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
use HomeAI\Module\Dashboard\Model as DModel;

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
        curl_setopt($ch, \CURLOPT_HEADER, 1);
        curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, $type);                    // Request type
        curl_setopt($ch, \CURLOPT_HTTPHEADER, $headers);                    // Request headers
        curl_setopt($ch, \CURLOPT_POSTFIELDS, http_build_query($postData)); // Request post fields

        // Get HTTP status code and actual response from server
        $response = trim(curl_exec($ch));
        $status = curl_getinfo($ch, \CURLINFO_HTTP_CODE);
        $info  = curl_getinfo($ch);

        // Error occurred
        if ($status >= 400) {
            $content = "<h1>Error occurred</h1><p>"
                    . Network::getStatusCodeString($status)
                    . ".<br />Content:<br />"
                    . $response;
        } elseif (empty($response)) {
            $content = "Content not found...";
        }

        curl_close($ch);

        $headers = substr($response, 0, $info['header_size']);
        $content = substr($response, -$info['download_content_length']);

        return array(
            $content,
            $status,
            $headers,
        );
        /*
        $content, $statusCode, $headers

        return $content;
        */
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

    /**
     * Widget store method. Basically this method calls either update or insert
     * method depending specified store type.
     *
     * @throws  Exception
     *
     * @param   string  $type   Store type
     * @param   array   $data   Widget content data
     * @param   array   $widget Widget data
     *
     * @return  array|void
     */
    public function store($type, array $data, array $widget)
    {
        if (strcmp($type, 'update') === 0) {
            $output = $this->update($data, $widget);
        } elseif (strcmp($type, 'insert') === 0) {
            $output = $this->insert($data, $widget);
        } else {
            throw new Exception("What the fuck, how did this happen...");
        }

        return $output;
    }

    /**
     * Widget update method.
     *
     * @throws  Exception
     *
     * @param   array   $data   Widget content data
     * @param   array   $widget Widget data
     *
     * @return  array
     */
    private function update(array $data, array $widget)
    {
        // Fetch Dashboard model object
        $model = new DModel($this->request);

        // Get current user widgets
        $widgets = $model->getWidgets();

        // Check that user widgets are "valid"
        if (!isset($widgets['result']['data']) || !is_array($widgets['result']['data'])) {
            throw new Exception("Couldn't determine user widgets...");
        }

        // Initialize used data variables
        $widgetData = $output = array();

        // Iterate current widgets.
        foreach ($widgets['result']['data'] as $currentWidget) {

            if (strcmp($currentWidget['id'], $widget['id']) === 0) {
                $widgetData[] = $output = array_merge($currentWidget, $data);
            }

            $widgetData[] = $currentWidget;
        }

        $widgets['result']['data'] = $widgetData;

        $model->setWidgets($widgets);

        return $output;
    }

    /**
     * Widget insert method.
     *
     * @throws  Exception
     *
     * @param   array   $data   Widget content data
     * @param   array   $widget Widget data
     *
     * @return  array           Inserted widget data
     */
    private function insert(array $data, array $widget)
    {
        // Fetch Dashboard model object
        $model = new DModel($this->request);

        // Determine 'real' widget data
        $widgetData = $this->checkWidgetData(array_merge($widget, $data));

        // Add new widget
        $model->addWidget($widgetData);

        return $widgetData;
    }

    /**
     * Method checks specified data array contents to match widget content.
     *
     * @param   array   $data   Widget data to be checked
     *
     * @return  array           Valid widget data
     */
    private function checkWidgetData(array $data)
    {
        // Specify needed widget properties and default values
        $properties = array(
            'id'        => '',
            'title'     => '',
            'column'    => '',
            'editurl'   => '',
            'open'      => true,
            'metadata'  => '',
            'method'    => '',
            'refresh'   => 0,
        );

        // Filter out not wanted widget properties
        foreach ($data as $key => $value) {
            if (!array_key_exists($key, $properties)) {
                unset($data[$key]);
            }
        }

        // Add missing widget properties
        foreach ($properties as $property => $defaultValue) {
            if (!array_key_exists($property, $data)) {
                $data[$property] = $defaultValue;
            }
        }

        return $data;
    }
}
