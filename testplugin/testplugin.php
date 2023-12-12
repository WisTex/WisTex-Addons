<?php
/**
 * Name: Test Plugin
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
 * The testplugin_nav() hook handler is registered for the "nav" hook
 * The "testplugin" route is created for Mod_Testplugin module 
*/
function testplugin_load() {
	Hook::register('nav', 'addon/testplugin/testplugin.php', 'testplugin_nav');
	Route::register('addon/testplugin/Mod_Testplugin.php', 'testplugin');
}

// * This function unregisters (removes) the hook handler and admin route.
function testplugin_unload() {
	Hook::unregister('nav', 'addon/testplugin/testplugin.php', 'testplugin_nav');
	Route::unregister('addon/testplugin/Mod_Testplugin.php', 'testplugin');
}

/** 
 * * This function runs when the hook handler is executed.
 * @param $templateObj: A reference to the corresponding template object in the global scope
*/
function testplugin_nav(&$templateObj) {
	// Reload the "config" (database table) settings in the "testplugin" category, because they not available yet
	unset(App::$config['testplugin']);
	load_config('testplugin');

	// Load "testplugin" settings (custom variables) into the template object
	if (!empty(App::$config['testplugin'])) {
		foreach (App::$config['testplugin'] as $varName => $varValue) {
			if ($varName != 'config_loaded') {
				$templateObj['usermenu']['testplugin'][$varName] = $varValue;
			}
		} 
	}
}
