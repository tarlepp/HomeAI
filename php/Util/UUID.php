<?php
/**
 * \php\Util\UUID.php
 *
 * @package     HomeAI
 * @subpackage  Util
 * @category    UUID
 */
namespace HomeAI\Util;

/**
 * Generic UUID generator class.
 *
 * @package     HomeAI
 * @subpackage  Util
 * @category    UUID
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class UUID implements Interfaces\UUID
{
    /**
     * Version 3 (MD5 hash)
     *
     * Version 3 UUIDs use a scheme deriving a UUID via MD5 from a URL, a fully qualified
     * domain name, an object identifier, a distinguished name (DN as used in Lightweight
     * Directory Access Protocol), or on names in unspecified namespaces. Version 3 UUIDs
     * have the form xxxxxxxx-xxxx-3xxx-yxxx-xxxxxxxxxxxx where x is any hexadecimal digit
     * and y is one of 8, 9, A, or B.
     *
     * @param   string  $namespace  Namespace to use, this must be valid UUID string
     * @param   string  $name       String where to generate UUID
     *
     * @return  bool|string         UUID version 3 string or false on failure
     */
    public static function v3($namespace, $name)
    {
        if (!self::isValid($namespace)) {
            return false;
        }

        // Get hexadecimal components of namespace
        $nhex = str_replace(array('-', '{', '}'), '', $namespace);

        // Binary Value
        $nstr = '';

        // Convert Namespace UUID to bits
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
        }

        // Calculate hash value
        $hash = md5($nstr . $name);

        return sprintf(
            '%08s-%04s-%04x-%04x-%12s',
            // 32 bits for "time_low"
            substr($hash, 0, 8),
            // 16 bits for "time_mid"
            substr($hash, 8, 4),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 3
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
            // 48 bits for "node"
            substr($hash, 20, 12)
        );
    }

    /**
     * Version 4 (random)
     *
     * Version 4 UUIDs use a scheme relying only on random numbers. This algorithm
     * sets the version number as well as two reserved bits. All other bits are
     * set using a random or pseudorandom data source. Version 4 UUIDs have the form
     * xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx where x is any hexadecimal digit and y
     * is one of 8, 9, A, or B. e.g. f47ac10b-58cc-4372-a567-0e02b2c3d479.
     *
     * @return  string  UUID version 4 string
     */
    public static function v4()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    /**
     * Version 5 (SHA-1 hash)
     *
     * Version 5 UUIDs use a scheme with SHA-1 hashing; otherwise it is the same
     * idea as in version 3. RFC 4122 states that version 5 is preferred over
     * version 3 name based UUIDs, as MD5's security has been compromised. Note
     * that the 160 bit SHA-1 hash is truncated to 128 bits to make the length
     * work out. An erratum addresses the example in appendix B of RFC 4122
     *
     * @param   string  $namespace  Namespace to use, this must be valid UUID string
     * @param   string  $name       String where to generate UUID
     *
     * @return  bool|string         UUID version 5 string or false on failure
     */
    public static function v5($namespace, $name)
    {
        if (!self::isValid($namespace)) {
            return false;
        }

        // Get hexadecimal components of namespace
        $nhex = str_replace(array('-', '{', '}'), '', $namespace);

        // Binary Value
        $nstr = '';

        // Convert Namespace UUID to bits
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
        }

        // Calculate hash value
        $hash = sha1($nstr . $name);

        return sprintf(
            '%08s-%04s-%04x-%04x-%12s',
            // 32 bits for "time_low"
            substr($hash, 0, 8),
            // 16 bits for "time_mid"
            substr($hash, 8, 4),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 5
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
            // 48 bits for "node"
            substr($hash, 20, 12)
        );
    }

    /**
     * Method checks if given string is valid UUID string or not.
     *
     * @param   string  $uuid   UUID string to check
     *
     * @return  boolean
     */
    public static function isValid($uuid)
    {
        return preg_match(
            '/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i',
            $uuid
        ) === 1;
    }
}
