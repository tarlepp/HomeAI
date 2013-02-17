<?php
/**
 * \php\Util\YuiCompressor.php
 *
 * @package     HomeAI
 * @subpackage  Util
 * @category    UI
 */
namespace HomeAI\Util;

/**
 * This class compress Javascript/CSS using the YUI Compressor.
 *
 * @package     HomeAI
 * @subpackage  Util
 * @category    UI
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class YuiCompressor implements Interfaces\YuiCompressor
{
    /**#@+
     * Used type constants.
     *
     * @access  public
     * @type    constant
     * @var     string
     */
    const TYPE_JS  = 'js';
    const TYPE_CSS = 'css';
    /**#@-*/

    /**
     * File path of the YUI Compressor jar file.
     *
     * @access  public
     * @static
     * @var     string
     */
    public static $jarFile = null;

    /**
     * Writable temp directory.
     *
     * @access  public
     * @static
     * @var     string
     */
    public static $tempDir = null;

    /**
     * File path of "java" executable (may be needed if not in shell's PATH)
     *
     * @access  public
     * @static
     * @var     string
     */
    public static $javaExecutable = 'java';

    /**
     * Compress specified javascript string.
     *
     * @access  public
     * @static
     *
     * @param   string  $string     Javascript string to compress
     * @param   array   $options    Used compress options
     *
     * @return  string              Compressed javascript
     */
    public static function compressJavascript($string, $options = array())
    {
        return self::compress(self::TYPE_JS, $string, $options);
    }

    /**
     * Compress specified CSS string.
     *
     * @access  public
     * @static
     *
     * @uses    \HomeAI\Util\YuiCompress::_compress()
     *
     * @param   string  $string     CSS string to compress
     * @param   array   $options    Used compress options
     *
     * @return  string              Compressed CSS
     */
    public static function compressCss($string, $options = array())
    {
        return self::compress(self::TYPE_CSS, $string, $options);
    }


    /**
     * Method makes actual compress for specified type and string.
     *
     * @access  private
     * @static
     *
     * @throws  \HomeAI\Util\Exception
     *
     * @param   string  $type       Compress type, can be one of following:
     *                               - js
     *                               - css
     * @param   string  $content    String content to compress
     * @param   array   $options    Used compress options
     *
     * @return  string              Compressed string
     */
    private static function compress($type, $content, $options)
    {
        // Make preparations for compression
        self::prepare();

        // Cannot create temporary file
        if (!($tmpFile = tempnam(self::$tempDir, 'yuic'))) {
            throw new Exception("Couldn't create required temp file!");
        }

        // Compress data.
        file_put_contents($tmpFile, $content);
        exec(self::getCmd($options, $type, $tmpFile), $output);
        unlink($tmpFile);

        return implode("\n", $output);
    }

    /**
     * Method returns used shell command to make actual compress.
     *
     * @access  private
     * @static
     *
     * @param   array   $options    Used compress options
     * @param   string  $type       Compress type, can be one of following:
     *                               - js
     *                               - css
     * @param   string  $tmpFile    Used temp file
     *
     * @return  string              Used shell command to make compress
     */
    private static function getCmd($options, $type, $tmpFile)
    {
        // Specify used options for compress
        $options = array_merge(
            array(
                'charset'               => '',
                'line-break'            => 5000,
                'type'                  => $type,
                'nomunge'               => false,
                'preserve-semi'         => false,
                'disable-optimizations' => false,
            ),
            $options
        );

        // Specify used compress command
        $cmd = self::$javaExecutable . ' -jar ' . escapeshellarg(self::$jarFile)
            . " --type {$type}"
            . (preg_match('/^[a-zA-Z\\-]+$/', $options['charset'])
                ? " --charset {$options['charset']}"
                : '')
            . (is_numeric($options['line-break']) && $options['line-break'] >= 0
                ? ' --line-break ' . (int)$options['line-break']
                : '');

        // JS compression
        if ($type === self::TYPE_JS) {
            foreach (array('nomunge', 'preserve-semi', 'disable-optimizations') as $opt) {
                $cmd .= $options[$opt] ? " --{$opt}" : '';
            }
        }

        return $cmd . ' ' . escapeshellarg($tmpFile);
    }

    /**
     * Method prepares all necessary files and directories which are
     * used in actual compress function.
     *
     * @access  private static
     *
     * @throws  \HomeAI\Util\Exception
     *
     * @return  void
     */
    private static function prepare()
    {
        // Specify used jar file and temp directory
        self::$jarFile = PATH_BASE . 'libs/yuicompressor/yuicompressor-2.4.7.jar';
        self::$tempDir = PATH_BASE . 'temp';

        // Specified jar file not found
        if (!is_file(self::$jarFile)) {
            $message = sprintf(
                "Specified jar file '%s' is not readable!",
                self::$jarFile
            );
        } elseif (!is_dir(self::$tempDir) || !is_writable(self::$tempDir)) { // Temp directory is not writable
            $message = sprintf(
                "Specified temp directory '%s' doesn't exists or it's not writable!",
                self::$tempDir
            );
        }

        if (!empty($message)) {
            throw new Exception($message);
        }
    }
}
