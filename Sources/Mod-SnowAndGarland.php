<?php
/**
 * @package SMF Snow and Garland
 * @author digger http://mysmf.ru
 * @copyright 2011-2016
 * @license BSD License
 * @version 1.3
 */

if (!defined('SMF')) {
    die('Hacking attempt...');
}

// TODO: JQuery check compatibility
// TODO: On/Off button

function settingsSnowAndGarland(&$config_vars)
{

    loadLanguage('SnowAndGarland/');

    $config_vars = array_merge($config_vars, array(
        '',
        array('check', 'SnowAndGarland_garland_enabled'),
        array('check', 'SnowAndGarland_garland_sound_enabled'),
        array('int', 'SnowAndGarland_garland_top_offset', 'postinput' => 'px'),
        array(
            'select',
            'SnowAndGarland_garland_garlandSize',
            array('pico' => 'pico', 'tiny' => 'tiny', 'small' => 'small', 'medium' => 'medium', 'large' => 'large')
        ),
        array('check', 'SnowAndGarland_snow_enabled'),
        array('int', 'SnowAndGarland_snow_flakesMax'),
        array('int', 'SnowAndGarland_snow_flakesMaxActive'),
        array('check', 'SnowAndGarland_snow_followMouse'),
        array('text', 'SnowAndGarland_snow_snowColor'),
        array('check', 'SnowAndGarland_snow_useMeltEffect'),
        array('check', 'SnowAndGarland_snow_useTwinkleEffect'),
        array('check', 'SnowAndGarland_snow_snowStick'),
    ));
}

function loadSnowAndGarland()
{
    global $modSettings, $context, $settings;

    if ($modSettings['SnowAndGarland_snow_enabled']) {
        $context['insert_after_template'] .= '         
                <script type="text/javascript" src="' . $settings['default_theme_url'] . '/lights/snowstorm-min.js"></script>
                <script type="text/javascript"><!-- // --><![CDATA[     
                    snowStorm.autoStart = true;
                    snowStorm.animationInterval = 33;
                    snowStorm.flakeBottom = null;
                    snowStorm.flakesMax = ' . $modSettings['SnowAndGarland_snow_flakesMax'] . ';
                    snowStorm.flakesMaxActive = ' . $modSettings['SnowAndGarland_snow_flakesMaxActive'] . ';
                    snowStorm.followMouse = ' . $modSettings['SnowAndGarland_snow_followMouse'] . ';
                    snowStorm.freezeOnBlur = true;
                    snowStorm.snowColor = "' . $modSettings['SnowAndGarland_snow_snowColor'] . '";
                    snowStorm.snowStick = ' . $modSettings['SnowAndGarland_snow_snowStick'] . ';
                    snowStorm.targetElement = null;
                    snowStorm.useMeltEffect = ' . $modSettings['SnowAndGarland_snow_useMeltEffect'] . ';
                    snowStorm.useTwinkleEffect = ' . $modSettings['SnowAndGarland_snow_useTwinkleEffect'] . ';
                    snowStorm.usePositionFixed = false;
                    snowStorm.vMaxX = 8;
                    snowStorm.vMaxY = 5;
                    snowStorm.excludeMobile = true;
                // ]]></script>';
    }

    if ($modSettings['SnowAndGarland_garland_enabled']) {
        $context['html_headers'] .= '
    <link rel="stylesheet" media="screen" href ="' . $settings['default_theme_url'] . '/lights/christmaslights.css" />
    <script type="text/javascript" src ="' . $settings['default_theme_url'] . '/lights/soundmanager2-nodebug-jsmin.js"></script>
    <script type="text/javascript" src ="' . $settings['default_theme_url'] . '/lights/animation-min.js"></script>
    <script type="text/javascript" src ="' . $settings['default_theme_url'] . '/lights/christmaslights-min.js"></script>                                 
    <script type="text/javascript"><!-- // --><![CDATA[ 
        var urlBase="' . $settings['default_theme_url'] . '/lights/";
        var garlandSize="' . $modSettings['SnowAndGarland_garland_garlandSize'] . '";                        
        ' . (!empty($modSettings['SnowAndGarland_garland_sound_enabled']) ? 'soundManager.url="' . $settings['default_theme_url'] . '/lights/";' : '') . '                    
    // ]]></script>
    <div id="lights">
        <!-- lights go here -->
    </div>';

        if (!empty($modSettings['SnowAndGarland_garland_top_offset'])) {
            $context['html_headers'] .= '
    <style>
        #lights {
            top: ' . (int)$modSettings['SnowAndGarland_garland_top_offset'] . 'px !important; 
        }
    </style>';
        }
    }
}

?>