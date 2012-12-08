<?php
/**
 * \php\Core\Interfaces\Request.php
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Interface
 */
namespace HomeAI\Core\Interfaces;

defined('HOMEAI_INIT') OR die('No direct access allowed.');
/**
 * iRequest -interface
 *
 * Interface for \HomeAI\Core\Request -class.
 *
 * @package     HomeAI
 * @subpackage  Core
 * @category    Interface
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
interface Request
{
    /**
     * Sets a single parameter. A $value of null will unset the $key if it exists.
     *
     * @param   string  $key
     * @param   mixed   $value
     *
     * @return  void
     */
    public function set($key, $value);

    /**
     * Method sets a 'live' cookie.
     *
     * @access  public
     *
     * @param   string  $name
     * @param   string  $value
     * @param   int     $expire
     * @param   string  $path
     * @param   string  $domain
     * @param   bool    $secure
     * @param   bool    $httpOnly
     *
     * @return  bool
     */
    public function setCookie(
        $name,
        $value = '',
        $expire = 0,
        $path = '/',
        $domain = '',
        $secure = false,
        $httpOnly = false
    );

    /**
     * Set action parameters for masses; does not overwrite. Null values will
     * unset the associated key.
     *
     * @access  public
     *
     * @param   array   $array
     *
     * @return  void
     */
    public function setParams(array $array);

    /**
     * Set GET values.
     *
     * @access  public
     *
     * @throws  \HomeAI\Core\Exception
     *
     * @param   mixed   $key
     * @param   mixed   $value
     *
     * @return  void
     */
    public function setQuery($key, $value = null);

    /**
     * Set POST values.
     *
     * @access  public
     *
     * @throws  \HomeAI\Core\Exception
     *
     * @param   mixed   $key
     * @param   mixed   $value
     *
     * @return  void
     */
    public function setPost($key, $value = null);

    /**
     * Set SESSION values.
     *
     * @access  public
     *
     * @throws  \HomeAI\Core\Exception
     *
     * @param   mixed   $key
     * @param   mixed   $value
     *
     * @return  void
     */
    public function setSession($key, $value = null);

    /**
     * Set the REQUEST_URI on which the instance operates
     *
     * If no request URI is passed, uses the value in $_SERVER['REQUEST_URI'],
     * $_SERVER['HTTP_X_REWRITE_URL'], or $_SERVER['ORIG_PATH_INFO'] + $_SERVER['QUERY_STRING'].
     *
     * @access  public
     *
     * @throws  \HomeAI\Core\Exception
     *
     * @param   string      $requestUri
     *
     * @return  void
     */
    public function setRequestUri($requestUri = null);

    /**
     * Set the base URL of the request; i.e., the segment leading to the script name
     *
     * Examples:
     *  - /admin
     *  - /myapp
     *  - /subdir/index.php
     *
     * Do not use the full URI when providing the base. The following are
     * examples of what not to use:
     *  - http://example.com/admin (should be just /admin)
     *  - http://example.com/subdir/index.php (should be just /subdir/index.php)
     *
     * If no $baseUrl is provided, attempts to determine the base URL from the
     * environment, using SCRIPT_FILENAME, SCRIPT_NAME, PHP_SELF, and ORIG_SCRIPT_NAME
     * in its determination.
     *
     * @access  public
     *
     * @throws  \HomeAI\Core\Exception
     *
     * @param   mixed   $baseUrl
     *
     * @return  void
     */
    public function setBaseUrl($baseUrl = null);

    /**
     * Set the base path for the URL
     *
     * @access  public
     *
     * @param   mixed   $basePath
     *
     * @return  void
     */
    public function setBasePath($basePath = null);

    /**
     * Set the path info string
     *
     * @access  public
     *
     * @param   mixed   $pathInfo
     *
     * @return  void
     */
    public function setPathInfo($pathInfo = null);

    /**
     * Read stored "request data" by referencing a key.
     *
     * @access  public
     *
     * @param   mixed   $key
     * @param   mixed   $default    Default value to use if key not found
     *
     * @return  mixed               Returns null if key does not exist
     */
    public function get($key = null, $default = null);

    /**
     * Returns current REQUEST_URI.
     *
     * @access  public
     *
     * @return  string
     */
    public function getRequestUri();

    /**
     * Everything in REQUEST_URI before PATH_INFO.
     *
     * @access  public
     *
     * @param   boolean $raw
     * @param   boolean $withHost
     *
     * @return  string
     */
    public function getBaseUrl($raw = false, $withHost = false);

    /**
     * Everything in REQUEST_URI before PATH_INFO not including the filename.
     *
     * @access  public
     *
     * @return  string
     */
    public function getBasePath();

    /**
     * Returns everything between the BaseUrl and QueryString.
     * This value is calculated instead of reading PATH_INFO
     * directly from $_SERVER due to cross-platform differences.
     *
     * @access  public
     *
     * @return  string
     */
    public function getPathInfo();

    /**
     * Method returns current URL address.
     *
     * @access  public
     *
     * @param   boolean     $raw
     *
     * @return  string
     */
    public function getCurrentUrl($raw = false);

    /**
     * Retrieve a member of the $_GET superglobal. If no $key is passed,
     * returns the entire $_GET array.
     *
     * @access  public
     *
     * @param   mixed   $key
     * @param   mixed   $default    Default value to use if key not found
     *
     * @return  array|mixed|null    Returns null if key does not exist
     */
    public function getQuery($key = null, $default = null);

    /**
     * Retrieve a member of the $_POST superglobal. If no $key is passed,
     * returns the entire $_POST array.
     *
     * @access  public
     *
     * @param   mixed   $key
     * @param   mixed   $default    Default value to use if key not found
     *
     * @return  mixed               Returns null if key does not exist
     */
    public function getPost($key = null, $default = null);

    /**
     * Retrieve a member of the $_SESSION superglobal. If no $key is passed,
     * returns the entire $_SESSION array.
     *
     * @access  public
     *
     * @param   mixed   $key
     * @param   mixed   $default    Default value to use if key not found
     *
     * @return  mixed               Returns null if key does not exist
     */
    public function getSession($key = null, $default = null);

    /**
     * Retrieve a member of the $_COOKIE superglobal. If no $key is passed,
     * returns the entire $_COOKIE array.
     *
     * @access  public
     *
     * @param   mixed   $key
     * @param   mixed   $default    Default value to use if key not found
     *
     * @return  mixed               Returns null if key does not exist
     */
    public function getCookie($key = null, $default = null);

    /**
     * Retrieve a member of the $_SERVER superglobal. If no $key is passed,
     * returns the entire $_SERVER array.
     *
     * @access  public
     *
     * @param   mixed   $key
     * @param   mixed   $default    Default value to use if key not found
     *
     * @return  mixed               Returns null if key does not exist
     */
    public function getServer($key = null, $default = null);

    /**
     * Retrieve a member of the $_ENV superglobal. If no $key is passed,
     * returns the entire $_ENV array.
     *
     * @access  public
     *
     * @param   mixed   $key
     * @param   mixed   $default    Default value to use if key not found
     *
     * @return  mixed               Returns null if key does not exist
     */
    public function getEnv($key = null, $default = null);

    /**
     * Return the method by which the request was made
     *
     * @access  public
     *
     * @return  string
     */
    public function getMethod();

    /**
     * Return the raw body of the request if present.
     *
     * @access  public
     *
     * @param   boolean $force  Force to use php://input
     *
     * @return  mixed           Raw body as string or false if not present.
     */
    public function getRawBody($force = false);

    /**
     * Return the value of the given HTTP header. Pass the header name as the
     * plain, HTTP-specified header name. Ex.: Ask for 'Accept' to get the
     * Accept header, 'Accept-Encoding' to get the Accept-Encoding header.
     *
     * @access  public
     *
     * @param   string  $header HTTP header name
     *
     * @return  mixed           HTTP header value, or false if not found
     */
    public function getHeader($header);

    /**
     * Get the request URI scheme
     *
     * @access  public
     *
     * @return  string
     */
    public function getScheme();

    /**
     * Get the HTTP host. "Host" ":" host [ ":" port ] ; Section 3.2.2
     * Note the HTTP Host header is not the same as the URI host. It includes
     * the port while the URI host doesn't.
     *
     * @access  public
     *
     * @return  string
     */
    public function getHttpHost();

    /**
     * Get the client's IP address
     *
     * @access  public
     *
     * @param   boolean     $checkProxy
     *
     * @return  string
     */
    public function getClientIp($checkProxy = true);

    /**
     * Was the request made by POST?
     *
     * @access  public
     *
     * @return  boolean
     */
    public function isPost();

    /**
     * Was the request made by GET?
     *
     * @access  public
     *
     * @return  boolean
     */
    public function isGet();

    /**
     * Was the request made by PUT?
     *
     * @access  public
     *
     * @return  boolean
     */
    public function isPut();

    /**
     * Was the request made by DELETE?
     *
     * @access  public
     *
     * @return  boolean
     */
    public function isDelete();

    /**
     * Was the request made by HEAD?
     *
     * @access  public
     *
     * @return  boolean
     */
    public function isHead();

    /**
     * Was the request made by OPTIONS?
     *
     * @access  public
     *
     * @return  boolean
     */
    public function isOptions();

    /**
     * Is the request a Javascript XMLHttpRequest or not? Should work with
     * Prototype/Script.aculo.us, jQuery, etc.
     *
     * @access  public
     *
     * @return  boolean
     */
    public function isAjax();

    /**
     * Is https secure request
     *
     * @access  public
     *
     * @return  boolean
     */
    public function isSecure();

    /**
     * Method determines if current request is from bot or not.
     *
     * @access  public
     *
     * @return  bool
     */
    public function isBot();
}
