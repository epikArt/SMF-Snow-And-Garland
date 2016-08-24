<?php
/**
 * @package SMF Snow and Garland
 * @author digger http://mysmf.ru
 * @copyright 2011-2016
 * @license BSD License
 * @version 1.3
 */

// If we have found SSI.php and we are outside of SMF, then we are running standalone.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF')) {
    require_once(dirname(__FILE__) . '/SSI.php');
} elseif (!defined('SMF')) // If we are outside SMF and can't find SSI.php, then throw an error
{
    die('<b>Error:</b> Cannot install - please verify you put this file in the same place as SMF\'s SSI.php.');
}

// List settings here in the format: setting_key => default_value.  Escape any "s. (" => \")
$mod_settings = array(
    'SnowAndGarland_garland_enabled' => 1,
    'SnowAndGarland_garland_garlandSize' => 'tiny',
    'SnowAndGarland_snow_enabled' => 1,
    'SnowAndGarland_snow_flakesMax' => 128,
    'SnowAndGarland_snow_flakesMaxActive' => 64,
    'SnowAndGarland_snow_followMouse' => 1,
    'SnowAndGarland_snow_snowColor' => '#fff',
    'SnowAndGarland_snow_useMeltEffect' => 1,
    'SnowAndGarland_snow_useTwinkleEffect' => 1,
    'SnowAndGarland_snow_snowStick' => 1,
);

// Update mod settings if applicable
foreach ($mod_settings as $new_setting => $new_value) {
    if (!isset($modSettings[$new_setting])) {
        updateSettings(array($new_setting => $new_value));
    }
}
?>