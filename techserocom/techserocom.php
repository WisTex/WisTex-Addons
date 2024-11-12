<?php
/**
 * Name: TechSero.com Website
 * Description: Custom pages addon for TechSero.com
 * Version: 1.0
 * Depends: Core
 * Recommends: None
 * Category: CustomPage
 * Author: Randall Jaffe, Scott M. Stolz
*/

/**
 * * CustomPage Addon
 * This is the primary file defining the addon.
 * It defines the name of the addon and gives information about the addon to other components of Hubzilla.
*/

use Zotlabs\Lib\Apps;
use Zotlabs\Extend\Hook;
use Zotlabs\Extend\Widget;
use Zotlabs\Extend\Route;

class TechSeroCom {
    const _CUSTOM_PAGES = ['webdesign', 'hubzilla'];
}

/**
 * * This function registers (adds) the hook handler and route.
 * The custompage_customize_header() hook handler is registered for the "page_header" hook
 * The custompage_customize_footer() hook handler is registered for the "page_end" hook
 * The "webdesign" route is created for Mod_Webdesign module 
*/
function techserocom_load() {
    Hook::register('page_header', 'addon/techserocom/techserocom.php', 'techserocom_customize_header');
    Hook::register('page_end', 'addon/techserocom/techserocom.php', 'techserocom_customize_footer');
	Route::register('addon/techserocom/modules/Mod_Webdesign.php', 'webdesign');
    Route::register('addon/techserocom/modules/Mod_Hubzilla.php', 'hubzilla');
}

// * This function unregisters (removes) the hook handler and route.
function techserocom_unload() {
	Hook::unregister('page_header', 'addon/techserocom/techserocom.php', 'techserocom_customize_header');
    Hook::unregister('page_end', 'addon/techserocom/techserocom.php', 'techserocom_customize_footer');
	Route::unregister('addon/techserocom/modules/Mod_Webdesign.php', 'webdesign');
    Route::unregister('addon/techserocom/modules/Mod_Hubzilla.php', 'hubzilla');
}

/** 
 * * This function runs when the hook handler is executed.
 * @param $content: A reference to page header content
*/
function techserocom_customize_header(&$content) {
    // Replace Neuhub page header with a custom header
    if (in_array(App::$module, TechSeroCom::_CUSTOM_PAGES)) {
        $content = replace_macros(get_markup_template('header_custom.tpl', 'addon/techserocom'), []);
    }
}

/** 
 * * This function runs when the hook handler is executed.
 * @param $content: A reference to page footer content
*/
function techserocom_customize_footer(&$content) {
    // Replace Neuhub page header with a custom header
    if (in_array(App::$module, TechSeroCom::_CUSTOM_PAGES)) {
        //$content .= replace_macros(get_markup_template('footer_custom.tpl', 'addon/techserocom'), []);
    }
}

