<?php
/**
 * Name: AI Agent Runtime
 * Description: Foundation for Hubzilla-hosted AI agent execution.
 * Version: 0.1.0
 * Depends: Core
 * Recommends: None
 * Category: Productivity
 * Author: Chad G. Prescott
 *
 * Initial milestone: addon registration and an authenticated status page.
 * No AI provider calls, credentials, background jobs, or database writes.
 */

use Zotlabs\Extend\Route;

/**
 * Hubzilla invokes this function when loading the addon.
 */
function aiagent_load() {
    Route::register('addon/aiagent/Mod_Aiagent.php', 'aiagent');
}

/**
 * Remove the route when the addon is unloaded.
 */
function aiagent_unload() {
    Route::unregister('addon/aiagent/Mod_Aiagent.php', 'aiagent');
}
