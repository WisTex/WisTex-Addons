<?php

/**
 * * Extra Theme Navigation Bar Variables
 * This is a module that is part of the "Neuhub_navbar_var" addon.
 * This module's URL is example.com/neuhub_navbar_var
*/

namespace Zotlabs\Module;

use App;
use Zotlabs\Lib\Apps;
use Zotlabs\Web\Controller;
use Zotlabs\Render\Theme;

// Neuhub_navbar_var class "controller" logic for the plugin's "neuhub_navbar_var" route
class Neuhub_navbar_var extends Controller {

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
		if (isset($_POST['custom_var_name'], $_POST['custom_var_value'], $_SESSION[$this->_pluginName . '.theme']) && !empty($_POST['custom_var_name']) && !empty($_POST['custom_var_value']) && !empty($_SESSION[$this->_pluginName . '.theme']))
		{
			set_config($_SESSION[$this->_pluginName . '.theme'], $_POST['custom_var_name'], $_POST['custom_var_value']);
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

		$_SESSION[$this->_pluginName . '.theme'] = $selectedTheme = $_GET['theme'] ?? $_SESSION[$this->_pluginName . '.theme'] ?? current(Theme::current());
		
		// Reload the "config" (database table) settings in the "testplugin2" category, because they not available yet
		unset(App::$config[$selectedTheme]);
		load_config($selectedTheme);		

		// Set variables that indicate the kind of request (i.e., Add, Edit, or Delete) as well as the current custom variable's name and value. 
		// "Add" is the default request type
		$isDeleteAction = isset($_GET['action']) && $_GET['action'] == 'delete';
		$isEditAction = isset($_GET['action']) && $_GET['action'] == 'edit';
		$updateName = ($isDeleteAction || $isEditAction) ? $_GET['var'] : '';
		$updateValue = App::$config[$selectedTheme][$updateName] ?? '';

		// If this is a Delete request, remove the variable from the "config" database table and 
		// Redirect to default plugin page to remove GET query string from URL
		if ($isDeleteAction && !empty($updateValue)) {
			del_config($selectedTheme, $updateName);
			header('Location: /' . $this->_pluginName . '/');
		}

		// If this is an Add request, load "config" (database table) settings in the "testplugin2" category into an array
		if (!$isEditAction) {
			$variableNames = [];
			$themeNavTplFiles = glob(dirname(dirname(__DIR__)) . '/view/theme/' . $selectedTheme . '/tpl/navbar_*.tpl');
			if ($themeNavTplFiles !== false && !empty($themeNavTplFiles)) {
				foreach ($themeNavTplFiles as $tplFile) {
					$themeContent = file_get_contents($tplFile);
					if ($themeContent !== false && !empty($themeContent)) {
						if ((int)preg_match_all('/\{\{([^\{\}]+)\}\}/', $themeContent, $smartySyntax) > 0) {
							foreach ($smartySyntax[0] as $syntax) {
								if ((int)preg_match_all('/\$userinfo(\.|\[")' . preg_quote($selectedTheme, '/') . '("\])?\.([\.a-zA-Z0-9_-]+)/', $syntax, $smartyVars) > 0) {
									$variableNames = array_merge($variableNames, $smartyVars[3]);
								}
							}
						}
					}
				}
			}
			//die(print_r($variableNames));

			$variables = [];
			if (!empty(App::$config[$selectedTheme])) {
				foreach (App::$config[$selectedTheme] as $varName => $varValue) {
					if ($varName != 'config_loaded') {
						$variables[] = [
							'name' => $varName, 
							'value' => htmlspecialchars($varValue, ENT_QUOTES)
						];
					}
				}
			}

			if (!empty($variableNames)) {
				$variableNames = array_unique($variableNames);
				foreach ($variableNames as $varName)
				{
					if (!isset(App::$config[$selectedTheme][$varName]))
					{
						$variables[] = [
							'name' => $varName, 
							'value' => ''
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
				t('A custom variable name'),
				'',
				'readonly'
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
			$allowedThemes = explode(",", get_config('system', 'allowed_themes'));
			$customUpdate = replace_macros(get_markup_template('update_custom_vars.tpl', 'addon/' . $this->_pluginName), [
				'$field' => [
					'select_theme', 
					t('Theme'), 
					$selectedTheme, 
					t('Select theme to edit its varables'),
					array_combine($allowedThemes, $allowedThemes)
				],				
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
		return ($isEditAction) ? $addVar : $customUpdate;
	}

}




