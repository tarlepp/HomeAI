<?php
/**
 * \php\Util\Network.php
 *
 * @package     Util
 * @subpackage  Network
 * @category    Network
 */
namespace HomeAI\Util;

use HomeAI\Core\Request;

/**
 * Network -class
 *
 * This class contains different network related helper methods.
 *
 * @package     Util
 * @subpackage  Network
 * @category    Network
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class Network implements Interfaces\Network
{
    /**
     * Method returns user ip -address.
     *
     * @access  public
     * @static
     *
     * @return  string  User ip address
     */
    public static function getIp()
    {
        return Request::getInstance()->getServer('REMOTE_ADDR');
    }

    /**
     * Method returns user ip -address hostname.
     *
     * @access  public
     * @static
     *
     * @return  string  User ip address hostname
     */
    public static function getHost()
    {
        return gethostbyaddr(Network::getIp());
    }

    /**
     * Method returns defined hostname ip -address.
     *
     * @access  public
     * @static
     *
     * @param   string  $host   Hostname
     *
     * @return  string          Hostname ip -address
     */
    public static function getHostIp($host)
    {
        return gethostbyname($host);
    }

    /**
     * Method returns user agent information.
     *
     * @access  public
     * @static
     *
     * @return  string  User agent
     */
    public static function getAgent()
    {
        return Request::getInstance()->getServer('HTTP_USER_AGENT');
    }
}
