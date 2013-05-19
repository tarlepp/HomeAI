<?php
/**
 * \php\Core\Init.php
 *
 * @package     Core
 * @subpackage  System
 * @category    Locale
 */
namespace HomeAI\Core\System;

/**
 * This class contains system/user locale specified setters and getters.
 *
 * @package     Core
 * @subpackage  System
 * @category    Locale
 *
 * @date        $Date$
 * @version     $Rev$
 * @author      $Author$
 */
class Locale extends Component
{
    /**
     * Locale system component load method.
     */
    public function load()
    {
        // Base encode settings
        $this->setMultibyteSettings();

        // User / UI values
        $this->setTimeZone();
        $this->setLocale();
    }

    /**
     * Method sets used default timezone.
     *
     * @param string $timezone
     */
    public function setTimeZone($timezone = 'Europe/Helsinki')
    {
        // TODO: make validations, user timezone settings

        date_default_timezone_set($timezone);
    }

    /**
     * Method sets locale settings.
     *
     * @param   string  $locale Locale to use
     * @param   integer $type   Used locale type see base constants \LC_*
     */
    public function setLocale($locale = 'fi_FI.UTF8', $type = \LC_ALL)
    {
        // TODO: make validations, user locale settings

        setlocale($type, $locale);
    }

    /**
     * Method sets basic multibyte settings, HomeAI supports only UTF-8,
     * so make sure that your system is compatible.
     */
    private function setMultibyteSettings()
    {
        // Set UTF8 to be default
        mb_http_output('UTF-8');
        mb_internal_encoding('UTF-8');
    }
}
