<?php
/**
 * @package SMF Snow and Garland
 * @file Mod-SnowAndGarland.php
 * @author digger <digger@mysmf.ru> <http://mysmf.ru>
 * @copyright Copyright (c) 2011-2016, digger
 * @license BSD License
 * @version 1.3
 */

if (!defined('SMF')) {
    die('Hacking attempt...');
}


/**
 * Load all needed hooks
 */
function loadSnowAndGarlandHooks()
{
    add_integration_function('integrate_admin_areas', 'addSnowAndGarlandAdminArea', false);
    add_integration_function('integrate_modify_modifications', 'addSnowAndGarlandAdminAction', false);
    add_integration_function('integrate_load_theme', 'loadSnowAndGarlandAssets', false);
}


/**
 * Add mod admin area
 * @param $admin_areas
 */
function addSnowAndGarlandAdminArea(&$admin_areas)
{
    global $txt;
    loadLanguage('SnowAndGarland/');

    $admin_areas['config']['areas']['modsettings']['subsections']['snow_and_garland'] = array($txt['SnowAndGarland']);
}


/**
 * Add mod admin action
 * @param $subActions
 */
function addSnowAndGarlandAdminAction(&$subActions)
{
    $subActions['snow_and_garland'] = 'addSnowAndGarlandAdminSettings';
}


/**
 * Add mod settings area
 * @param bool $return_config
 * @return array
 */
function addSnowAndGarlandAdminSettings($return_config = false)
{
    global $txt, $scripturl, $context;
    loadLanguage('SnowAndGarland/');

    $context['page_title'] = $context['settings_title'] = $txt['SnowAndGarland'];
    $context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=snow_and_garland';

    $config_vars = array(
        array('check', 'SnowAndGarland_garland_enabled'),
        array('check', 'SnowAndGarland_garland_mobile_enabled'),
        array('check', 'SnowAndGarland_garland_sound_enabled'),
        array('int', 'SnowAndGarland_garland_top_offset', 'postinput' => 'px'),
        array(
            'select',
            'SnowAndGarland_garland_garlandSize',
            array('pico' => 'pico', 'tiny' => 'tiny', 'small' => 'small', 'medium' => 'medium', 'large' => 'large')
        ),
        array('check', 'SnowAndGarland_snow_enabled'),
        array('check', 'SnowAndGarland_snow_mobile_enabled'),
        array('int', 'SnowAndGarland_snow_flakesMax'),
        array('int', 'SnowAndGarland_snow_flakesMaxActive'),
        array('check', 'SnowAndGarland_snow_followMouse'),
        array('text', 'SnowAndGarland_snow_snowColor'),
        array('check', 'SnowAndGarland_snow_useMeltEffect'),
        array('check', 'SnowAndGarland_snow_useTwinkleEffect'),
        array('check', 'SnowAndGarland_snow_snowStick'),
    );

    if ($return_config) {
        return $config_vars;
    }

    if (isset($_GET['save'])) {
        checkSession();
        saveDBSettings($config_vars);
        redirectexit('action=admin;area=modsettings;sa=snow_and_garland');
    }

    prepareDBSettingContext($config_vars);
}


/**
 * Load mod assets
 */
function loadSnowAndGarlandAssets()
{
    global $modSettings, $context, $settings;

    if (defined('WIRELESS') && WIRELESS === true) {
        return;
    }

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
                    snowStorm.excludeMobile = ' . (!empty($modSettings['SnowAndGarland_snow_mobile_enabled']) ? 'false' : 'true') . ';
                // ]]></script>';
    }

    if ($modSettings['SnowAndGarland_garland_enabled']) {

        if (empty($modSettings['SnowAndGarland_garland_mobile_enabled']) && (!empty($context['browser']['is_iphone']) || !empty($context['browser']['is_android']))) {
            return;
        }

        $context['html_headers'] .= '
    <link rel="stylesheet" media="screen" href ="' . $settings['default_theme_url'] . '/lights/christmaslights.css" />
    <script type="text/javascript" src ="' . $settings['default_theme_url'] . '/lights/soundmanager2-nodebug-jsmin.js"></script>
    <script type="text/javascript" src ="' . $settings['default_theme_url'] . '/lights/animation-min.js"></script>
    <script type="text/javascript" src ="' . $settings['default_theme_url'] . '/lights/christmaslights-min.js"></script>                                 
    <script type="text/javascript"><!-- // --><![CDATA[ 
        var urlBase="' . $settings['default_theme_url'] . '/lights/";
        var garlandSize="' . $modSettings['SnowAndGarland_garland_garlandSize'] . '";                        
        ' . (!empty($modSettings['SnowAndGarland_garland_sound_enabled']) ? 'soundManager.url="' . $settings['default_theme_url'] . '/lights/";' : '') . '                    
    // ]]></script>';

        if (!empty($modSettings['SnowAndGarland_garland_top_offset'])) {
            $context['html_headers'] .= '
    <style>
        #lights {
            top: ' . (int)$modSettings['SnowAndGarland_garland_top_offset'] . 'px !important; 
        }
    </style>';
        }

        loadTemplate('SnowAndGarland');
        $context['template_layers'][] = 'garland';
    }
}

?>