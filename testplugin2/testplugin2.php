<?php
/**
 * Name: Test Plugin 2
 * Description: Another test addon for testing.
 * Version: 1.0
 * Depends: Core
 * Recommends: None
 * Category: Test
 * Author: Randall Jaffe
*/

/**
 * * Test Plugin 2 Addon
 * This is the primary file defining the addon.
 * It defines the name of the addon and gives information about the addon to other components of Hubzilla.
*/

use Zotlabs\Lib\Apps;
use Zotlabs\Extend\Hook;
use Zotlabs\Extend\Route;

/**
 * * This function registers (adds) the hook handler and admin route.
 * The testplugin2_nav() hook handler is registered for the "nav" hook
 * The "testplugin2" route is created for Mod_Testplugin2 module 
*/
function testplugin2_load() {
	Hook::register('nav', 'addon/testplugin2/testplugin2.php', 'testplugin2_nav');
	Route::register('addon/testplugin2/Mod_Testplugin2.php', 'testplugin2');
}

// * This function unregisters (removes) the hook handler and admin route.
function testplugin2_unload() {
	Hook::unregister('nav', 'addon/testplugin2/testplugin2.php', 'testplugin2_nav');
	Route::unregister('addon/testplugin2/Mod_Testplugin2.php', 'testplugin2');
}

/** 
 * * This function runs when the hook handler is executed.
 * @param $templateObj: A reference to the corresponding template object in the global scope
*/
function testplugin2_nav(&$templateObj) {
	$current_theme = current(Zotlabs\Render\Theme::current());
	
	// Reload the "config" (database table) settings in the "testplugin2" category, because they not available yet
	unset(App::$config[$current_theme]);
	load_config($current_theme);

	// Load "testplugin2" settings (custom variables) into the template object
	if (!empty(App::$config[$current_theme])) {
		foreach (App::$config[$current_theme] as $varName => $varValue) {
			if ($varName != 'config_loaded') {
				$templateObj['usermenu'][$current_theme][$varName] = $varValue;
			}
		} 
	}
}
