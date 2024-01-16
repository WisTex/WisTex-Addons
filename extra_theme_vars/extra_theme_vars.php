<?php
/**
 * Name: Extra variables for Themes
 * Description: A test addon for testing.
 * Version: 1.0
 * Depends: Core
 * Recommends: None
 * Category: Test
 * Author: Randall Jaffe
*/

/**
 * * Test Plugin Addon
 * This is the primary file defining the addon.
 * It defines the name of the addon and gives information about the addon to other components of Hubzilla.
*/

use Zotlabs\Lib\Apps;
use Zotlabs\Extend\Hook;
use Zotlabs\Extend\Route;

/**
 * * This function registers (adds) the hook handler and admin route.
 * The extra_theme_vars_nav() hook handler is registered for the "nav" hook
 * The "extra_theme_vars" route is created for Mod_Extra_theme_vars module 
*/
function extra_theme_vars_load() {
	Hook::register('nav', 'addon/extra_theme_vars/extra_theme_vars.php', 'extra_theme_vars_nav');
	Route::register('addon/extra_theme_vars/Mod_Extra_theme_vars.php', 'extra_theme_vars');
}

// * This function unregisters (removes) the hook handler and admin route.
function extra_theme_vars_unload() {
	Hook::unregister('nav', 'addon/extra_theme_vars/extra_theme_vars.php', 'extra_theme_vars_nav');
	Route::unregister('addon/extra_theme_vars/Mod_Extra_theme_vars.php', 'extra_theme_vars');
}

/** 
 * * This function runs when the hook handler is executed.
 * @param $templateObj: A reference to the corresponding template object in the global scope
*/
function extra_theme_vars_nav(&$templateObj) {
	// Reload the "config" (database table) settings in the "extra_theme_vars" category, because they not available yet
	unset(App::$config['extra_theme_vars']);
	load_config('extra_theme_vars');

	// Load "extra_theme_vars" settings (custom variables) into the template object
	if (!empty(App::$config['extra_theme_vars'])) {
		foreach (App::$config['extra_theme_vars'] as $varName => $varValue) {
			if ($varName != 'config_loaded') {
				$templateObj['usermenu']['extra_theme_vars'][$varName] = $varValue;
			}
		} 
	}
}
