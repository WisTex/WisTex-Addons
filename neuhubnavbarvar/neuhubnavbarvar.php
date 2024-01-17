<?php
/**
 * Name: Extra Theme Navigation Bar Variables
 * Description: Allows admins to set navigation bar variables defined by a theme. Used in Neuhub themes and potentially some other themes.
 * Version: 1.0
 * MinVersion: 8.0
 * Depends: Core
 * Recommends: None
 * Category: Neuhub
 * Author: Randall Jaffe
 * Maintainer: Scott M. Stolz
 * Maintainer: Federated Works \ Neuhub
 * License: MIT License (Expat Version)
 * Copyright: 2023-2024 WisTex TechSero Ltd. Co.
*/

/**
 * * Extra Theme Navigation Bar Variables Addon
 * This is the primary file defining the addon.
 * It defines the name of the addon and gives information about the addon to other components of Hubzilla.
*/

use Zotlabs\Lib\Apps;
use Zotlabs\Extend\Hook;
use Zotlabs\Extend\Route;

/**
 * * This function registers (adds) the hook handler and admin route.
 * The neuhub_navbar_var_nav() hook handler is registered for the "nav" hook
 * The "neuhub_navbar_var" route is created for Mod_Neuhub_navbar_var module 
*/
function neuhubnavbarvar_load() {
	Hook::register('nav', 'addon/neuhubnavbarvar/neuhubnavbarvar.php', 'neuhubnavbarvar_nav');
	Route::register('addon/neuhubnavbarvar/Mod_Neuhubnavbarvar.php', 'neuhubnavbarvar');
}

// * This function unregisters (removes) the hook handler and admin route.
function neuhubnavbarvar_unload() {
	Hook::unregister('nav', 'addon/neuhubnavbarvar/neuhubnavbarvar.php', 'neuhubnavbarvar_nav');
	Route::unregister('addon/neuhubnavbarvar/Mod_Neuhubnavbarvar.php', 'neuhubnavbarvar');
}

/** 
 * * This function runs when the hook handler is executed.
 * @param $templateObj: A reference to the corresponding template object in the global scope
*/
function neuhubnavbarvar_nav(&$templateObj) {
	$current_theme = current(Zotlabs\Render\Theme::current());
	
	// Reload the "config" (database table) settings in the "neuhub_navbar_var" category, because they not available yet
	unset(App::$config[$current_theme]);
	load_config($current_theme);

	// Load "tneuhub_navbar_var" settings (custom variables) into the template object
	if (!empty(App::$config[$current_theme])) {
		foreach (App::$config[$current_theme] as $varName => $varValue) {
			if ($varName != 'config_loaded') {
				$templateObj['userinfo'][$current_theme][$varName] = $varValue;
			}
		} 
	}
}
