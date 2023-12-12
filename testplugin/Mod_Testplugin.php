<?php

/**
 * * Test Plugin Module
 * This is a module that is part of the "Testplugin" addon.
 * This module's URL is example.com/testplugin
*/

namespace Zotlabs\Module;

use App;
use Zotlabs\Lib\Apps;
use Zotlabs\Web\Controller;

// Testplugin class "controller" logic for the plugin's "testplugin" route
class Testplugin extends Controller {

	// Class Fields
	private string $_pluginName = '';
	
	// Method executed during page initialization
	public function init(): void {
		// Set pluginName string to this class's name 
		$this->_pluginName = strtolower(trim(strrchr(__CLASS__, '\\'), '\\'));
	}
	
	// Generic handler for a HTTP POST request (e.g., a form submission)
	public function post(): void {

		// If the user is NOT logged in as an Admin, then do nothing
		if (!is_site_admin()) {
			return;
		}

		// Presumably, check for a valid CSRF form token
		check_form_security_token_redirectOnErr('/' . $this->_pluginName, $this->_pluginName);

		// Logic triggered by the "Add/Edit custom variable" operations of the plugin
		// If the 'custom_var_name' and 'custom_var_value' POST vars are set and not empty, then
		// Add the custom variable's key-value pair to the "config" database table
		if (isset($_POST['custom_var_name'], $_POST['custom_var_value']) && !empty($_POST['custom_var_name']) && !empty($_POST['custom_var_value']))
		{
			set_config($this->_pluginName, $_POST['custom_var_name'], $_POST['custom_var_value']);
		}

		// Trigger the get() function in this class to render content
		$this->get();
	}

	// Generic handler for a HTTP GET request (e.g., viewing the page normally)
	public function get(): string {

		// If the user is NOT logged in as an Admin, then do nothing.
		if (!is_site_admin()) {
			return '';
		}

		// Reload the "config" (database table) settings in the "testplugin" category, because they not available yet
		unset(App::$config[$this->_pluginName]);
		load_config($this->_pluginName);

		// Set variables that indicate the kind of request (i.e., Add, Edit, or Delete) as well as the current custom variable's name and value. 
		// "Add" is the default request type
		$isDeleteAction = isset($_GET['action']) && $_GET['action'] == 'delete';
		$isEditAction = isset($_GET['action']) && $_GET['action'] == 'edit';
		$updateName = ($isDeleteAction || $isEditAction) ? $_GET['var'] : '';
		$updateValue = App::$config[$this->_pluginName][$updateName] ?? '';

		// If this is a Delete request, remove the variable from the "config" database table and 
		// Redirect to default plugin page to remove GET query string from URL
		if ($isDeleteAction && !empty($updateValue)) {
			del_config($this->_pluginName, $updateName);
			header('Location: /' . $this->_pluginName . '/');
		}

		// If this is an Add request, load "config" (database table) settings in the "testplugin" category into an array
		if (!$isEditAction) {
			$variables = [];
			if (!empty(App::$config[$this->_pluginName])) {
				foreach (App::$config[$this->_pluginName] as $varName => $varValue) {
					if ($varName != "config_loaded") {
						$variables[] = [
							'name' => $varName, 
							'value' => htmlspecialchars($varValue, ENT_QUOTES)
						];
					}
				}
			}
		}

		// Create "Name" field markup in Add/Edit form and insert template vars
		$customVarName = replace_macros(get_markup_template('field_input.tpl'), [
			'$field' => [
				'custom_var_name', 
				t('Name'), 
				$updateName, 
				t('A custom variable name')
			]
		]);

		// Create "Value" field markup in Add/Edit form and insert template vars
		$customVarValue = replace_macros(get_markup_template('field_textarea.tpl'), [
			'$field' => [
				'custom_var_value', 
				t('Value'), 
				$updateValue, 
				t('A custom variable value')
			]
		]);

		// If this is an Add request, create additional page section and table markup that contains existing customer variables list
		if (!$isEditAction) {
			$customUpdate = replace_macros(get_markup_template('update_custom_vars.tpl', 'addon/' . $this->_pluginName), [
				'$title' => t('Update Custom Variables'),
				'$desc' => t('Edit or Delete Custom Variables'),
				'$variables' => $variables,
				'$urlRoot' => '/' . $this->_pluginName . '/'
			]);	
		}
		
		// Create page section and Add/Edit form markup, inserting form fields and other template vars
		$addVar = replace_macros(get_markup_template("settings_addon.tpl"), [
			'$action_url' => $this->_pluginName,
			'$form_security_token' => get_form_security_token($this->_pluginName),
			'$title' => (($isEditAction) ? t('Edit Custom Variable') . ' <small style="float:right"><a href="/' . $this->_pluginName . '/"><i>' . t('Go Back') . ' &#187;</i></a></small>' : t('Add Custom Variable')),
			'$content' => $customVarName . $customVarValue,
			'$submit' => t('Submit')
		]);

		// Return/Render content in the plugin template's "content" region depending on whether this is an Add or Edit request
		return ($isEditAction) ? $addVar : $addVar . $customUpdate;
	}

}




