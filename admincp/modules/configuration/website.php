<?php
/**
 * DesertCore CMS
 * https://desertcore.com/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2018-2020 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 * 
 * Donate to the Project:
 * https://desertcore.com/donate
 * 
 * Contribute:
 * https://github.com/lautaroangelico/DesertCore
 */

$languagePacks = Language::getInstalledLanguagePacks();

$allowedSettings = array(
	'settings_submit', // the submit button
	'system_active',
	'debug',
	'website_template',
	'maintenance_page',
	'server_name',
	'website_title',
	'website_title_alt',
	'website_meta_description',
	'website_meta_keywords',
	'website_forum_link',
	'discord_link',
	'language_switch_active',
	'language_default',
	'language_debug',
	//'plugins_system_enable',
	'ip_block_system_enable',
	'username_alphanumeric_check',
	'username_min_length',
	'username_max_length',
	'password_min_length',
	'password_max_length',
	'email_max_length',
	'admincp_sidebar_color',
	'cron_api_enabled',
	'cron_api_private_key',
	'login_api_enabled',
	'login_api_private_key',
	'bdo_database',
);

if(check($_POST['settings_submit'])) {
	try {
		
		// website status
		if(!check($_POST['system_active'])) throw new Exception('Invalid Website Status setting.');
		if(!in_array($_POST['system_active'], array(0, 1))) throw new Exception('Invalid Website Status setting.');
		$setting['system_active'] = ($_POST['system_active'] == 1 ? true : false);
		
		// debug mode
		if(!check($_POST['debug'])) throw new Exception('Invalid Debug Mode setting.');
		if(!in_array($_POST['debug'], array(0, 1))) throw new Exception('Invalid Debug Mode setting.');
		$setting['debug'] = ($_POST['debug'] == 1 ? true : false);
		
		// default template
		if(!check($_POST['website_template'])) throw new Exception('Invalid Default Template setting.');
		if(!file_exists(__PATH_TEMPLATES__.$_POST['website_template'].'/index.php')) throw new Exception('The selected template doesn\'t exist.');
		$setting['website_template'] = $_POST['website_template'];
		
		// maintenance page
		if(!check($_POST['maintenance_page'])) throw new Exception('Invalid Maintenance Page setting.');
		if(!Validator::Url($_POST['maintenance_page'])) throw new Exception('The maintenance page setting is not a valid URL.');
		$setting['maintenance_page'] = $_POST['maintenance_page'];
		
		// server name
		if(!check($_POST['server_name'])) throw new Exception('Invalid Server Name setting.');
		$setting['server_name'] = $_POST['server_name'];
		
		// website title
		if(!check($_POST['website_title'])) throw new Exception('Invalid Website Title setting.');
		$setting['website_title'] = $_POST['website_title'];
		
		// website title alt
		if(!check($_POST['website_title_alt'])) throw new Exception('Invalid Website Modules Title setting.');
		$setting['website_title_alt'] = $_POST['website_title_alt'];
		
		// meta description
		if(!check($_POST['website_meta_description'])) throw new Exception('Invalid Meta Description setting.');
		$setting['website_meta_description'] = $_POST['website_meta_description'];
		
		// meta keywords
		if(!check($_POST['website_meta_keywords'])) throw new Exception('Invalid Meta Keywords setting.');
		$setting['website_meta_keywords'] = $_POST['website_meta_keywords'];
		
		// forum link
		if(!check($_POST['website_forum_link'])) throw new Exception('Invalid Forum Link setting.');
		if(!Validator::Url($_POST['website_forum_link'])) throw new Exception('The forum link setting is not a valid URL.');
		$setting['website_forum_link'] = $_POST['website_forum_link'];
		
		// discord link
		if(check($_POST['discord_link'])) {
			$setting['discord_link'] = $_POST['discord_link'];
		}
		
		// language switch
		if(!check($_POST['language_switch_active'])) throw new Exception('Invalid Language Switch setting.');
		if(!in_array($_POST['language_switch_active'], array(0, 1))) throw new Exception('Invalid Language Switch setting.');
		$setting['language_switch_active'] = ($_POST['language_switch_active'] == 1 ? true : false);
		
		// language default
		if(is_array($languagePacks)) {
			if(!check($_POST['language_default'])) throw new Exception('Invalid Default Language setting.');
			if(!file_exists(__PATH_LANGUAGES__.$_POST['language_default'].'/language.php')) throw new Exception('The default language doesn\'t exist.');
			$setting['language_default'] = $_POST['language_default'];
		}
		
		// language debug
		if(!check($_POST['language_debug'])) throw new Exception('Invalid Language Debug setting.');
		if(!in_array($_POST['language_debug'], array(0, 1))) throw new Exception('Invalid Language Debug setting.');
		$setting['language_debug'] = ($_POST['language_debug'] == 1 ? true : false);
		
		// plugin system
		//if(!check($_POST['plugins_system_enable'])) throw new Exception('Invalid Plugin System setting.');
		//if(!in_array($_POST['plugins_system_enable'], array(0, 1))) throw new Exception('Invalid Plugin System setting.');
		//$setting['plugins_system_enable'] = ($_POST['plugins_system_enable'] == 1 ? true : false);
		
		// ip block system
		if(!check($_POST['ip_block_system_enable'])) throw new Exception('Invalid IP Block System setting.');
		if(!in_array($_POST['ip_block_system_enable'], array(0, 1))) throw new Exception('Invalid IP Block System setting.');
		$setting['ip_block_system_enable'] = ($_POST['ip_block_system_enable'] == 1 ? true : false);
		
		// username_alphanumeric_check
		if(!check($_POST['username_alphanumeric_check'])) throw new Exception('Invalid setting (username_alphanumeric_check)');
		if(!in_array($_POST['username_alphanumeric_check'], array(0, 1))) throw new Exception('Invalid setting (username_alphanumeric_check)');
		$setting['username_alphanumeric_check'] = ($_POST['username_alphanumeric_check'] == 1 ? true : false);
		
		// username_min_length
		if(!check($_POST['username_min_length'])) throw new Exception('Invalid setting (username_min_length)');
		if(!Validator::UnsignedNumber($_POST['username_min_length'])) throw new Exception('Invalid setting (username_min_length)');
		$setting['username_min_length'] = $_POST['username_min_length'];
		
		// username_max_length
		if(!check($_POST['username_max_length'])) throw new Exception('Invalid setting (username_max_length)');
		if(!Validator::UnsignedNumber($_POST['username_max_length'])) throw new Exception('Invalid setting (username_max_length)');
		$setting['username_max_length'] = $_POST['username_max_length'];
		
		// password_min_length
		if(!check($_POST['password_min_length'])) throw new Exception('Invalid setting (password_min_length)');
		if(!Validator::UnsignedNumber($_POST['password_min_length'])) throw new Exception('Invalid setting (password_min_length)');
		$setting['password_min_length'] = $_POST['password_min_length'];
		
		// password_max_length
		if(!check($_POST['password_max_length'])) throw new Exception('Invalid setting (password_max_length)');
		if(!Validator::UnsignedNumber($_POST['password_max_length'])) throw new Exception('Invalid setting (password_max_length)');
		$setting['password_max_length'] = $_POST['password_max_length'];
		
		// email_max_length
		if(!check($_POST['email_max_length'])) throw new Exception('Invalid setting (email_max_length)');
		if(!Validator::UnsignedNumber($_POST['email_max_length'])) throw new Exception('Invalid setting (email_max_length)');
		$setting['email_max_length'] = $_POST['email_max_length'];
		
		// admincp_sidebar_color
		if(!check($_POST['admincp_sidebar_color'])) throw new Exception('Invalid setting (admincp_sidebar_color)');
		if(!in_array($_POST['admincp_sidebar_color'], array('blue','azure','green','orange','red','purple'))) throw new Exception('Invalid setting (admincp_sidebar_color)');
		$setting['admincp_sidebar_color'] = $_POST['admincp_sidebar_color'];
		
		// cron_api_enabled
		if(!check($_POST['cron_api_enabled'])) throw new Exception('Invalid setting (cron_api_enabled)');
		if(!in_array($_POST['cron_api_enabled'], array(0, 1))) throw new Exception('Invalid setting (cron_api_enabled)');
		$setting['cron_api_enabled'] = ($_POST['cron_api_enabled'] == 1 ? true : false);
		
		// cron_api_private_key
		if(!check($_POST['cron_api_private_key'])) throw new Exception('Invalid setting (cron_api_private_key)');
		if(!Validator::AlphaNumeric($_POST['cron_api_private_key'])) throw new Exception('Invalid setting (cron_api_private_key)');
		$setting['cron_api_private_key'] = $_POST['cron_api_private_key'];
		
		// login_api_enabled
		if(!check($_POST['login_api_enabled'])) throw new Exception('Invalid setting (login_api_enabled)');
		if(!in_array($_POST['login_api_enabled'], array(0, 1))) throw new Exception('Invalid setting (login_api_enabled)');
		$setting['login_api_enabled'] = ($_POST['login_api_enabled'] == 1 ? true : false);
		
		// login_api_private_key
		if(!check($_POST['login_api_private_key'])) throw new Exception('Invalid setting (login_api_private_key)');
		if(!Validator::AlphaNumeric($_POST['login_api_private_key'])) throw new Exception('Invalid setting (login_api_private_key)');
		$setting['login_api_private_key'] = $_POST['login_api_private_key'];
		
		// bdo_database
		if(!check($_POST['bdo_database'])) throw new Exception('Invalid setting (bdo_database)');
		$setting['bdo_database'] = $_POST['bdo_database'];
		
		// webengine configs
		$webengineConfigurations = webengineConfigs();
		
		// make sure the settings are in the allow list
		foreach(array_keys($setting) as $settingName) {
			if(!in_array($settingName, $allowedSettings)) throw new Exception('One or more submitted setting is not editable.');
			
			$webengineConfigurations[$settingName] = $setting[$settingName];
		}
		
		$newWebEngineConfig = json_encode($webengineConfigurations, JSON_PRETTY_PRINT);
		$cfgFile = fopen(__PATH_CONFIGS__.'webengine.json', 'w');
		if(!$cfgFile) throw new Exception('There was a problem opening the configuration file.');
		
		fwrite($cfgFile, $newWebEngineConfig);
		fclose($cfgFile);
		
		message('success', 'Settings successfully saved!');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="row">';
	echo '<div class="col-sm-12">';
		echo '<div class="card">';
			echo '<div class="header">Website Settings</div>';
			echo '<div class="content table-responsive">';
			
				echo '<form action="" method="post">';
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Website Status</strong>';
								echo '<p class="setting-description">If disabled, all traffic will be redirected to the maintenance page.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="system_active" value="1" '.(config('system_active') ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="system_active" value="0" '.(!config('system_active') ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Debug Mode</strong>';
								echo '<p class="setting-description">Debugging mode, enable this setting only if you want the website to display errors.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="debug" value="1" '.(config('debug') ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="debug" value="0" '.(!config('debug') ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Default Template</strong>';
								echo '<p class="setting-description">Your website\'s default template.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="website_template" value="'.config('website_template').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Maintenance Page Url</strong>';
								echo '<p class="setting-description">Full URL to your website\'s maintenance page. Traffic is redirected to your maintenance page when the website is disabled.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="maintenance_page" value="'.config('maintenance_page').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Server Name</strong>';
								echo '<p class="setting-description">Your Mu Online server name.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="server_name" value="'.config('server_name').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Website Title</strong>';
								echo '<p class="setting-description">Your website\'s title.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="website_title" value="'.config('website_title').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Website Modules Title</strong>';
								echo '<p class="setting-description">Your website\'s title for modules with titles.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="website_title_alt" value="'.config('website_title_alt').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Meta Description</strong>';
								echo '<p class="setting-description">Define a description of your server.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="website_meta_description" value="'.config('website_meta_description').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Meta Keywords</strong>';
								echo '<p class="setting-description">Define keywords for search engines.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="website_meta_keywords" value="'.config('website_meta_keywords').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Forum Link</strong>';
								echo '<p class="setting-description">Full URL to your forum.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="website_forum_link" value="'.config('website_forum_link').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Discord Invitation Link</strong>';
								echo '<p class="setting-description">Full URL to your discord invitation.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="discord_link" value="'.config('discord_link').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Language System Status</strong>';
								echo '<p class="setting-description">Enables/disables the language switching system.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="language_switch_active" value="1" '.(config('language_switch_active') ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="language_switch_active" value="0" '.(!config('language_switch_active') ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						if(is_array($languagePacks)) {
							echo '<tr>';
								echo '<td>';
									echo '<strong>Default Language</strong>';
									echo '<p class="setting-description">Default language that WebEngine will use.</p>';
								echo '</td>';
								echo '<td>';
									echo '<select class="form-control" name="language_default">';
										foreach($languagePacks as $languageDir => $languageData) {
											if(config('language_default') == $languageDir) {
												echo '<option value="'.$languageDir.'" selected>'.Language::getLocaleTitle($languageData['locale']).' ['.$languageDir.']</option>';
											} else {
												echo '<option value="'.$languageDir.'">'.Language::getLocaleTitle($languageData['locale']).' ['.$languageDir.']</option>';
											}
										}
									echo '</select>';
								echo '</td>';
							echo '</tr>';
						}
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Language Debug</strong>';
								echo '<p class="setting-description">If enabled, language phrases will not be parsed.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="language_debug" value="1" '.(config('language_debug') ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="language_debug" value="0" '.(!config('language_debug') ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						/*
						echo '<tr>';
							echo '<td>';
								echo '<strong>Plugin System Status</strong>';
								echo '<p class="setting-description">Enables/disables the plugin system.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="plugins_system_enable" value="1" '.(config('plugins_system_enable') ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="plugins_system_enable" value="0" '.(!config('plugins_system_enable') ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						*/
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>IP Block System Status</strong>';
								echo '<p class="setting-description">Enables/disables the IP blocking system.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="ip_block_system_enable" value="1" '.(config('ip_block_system_enable') ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="ip_block_system_enable" value="0" '.(!config('ip_block_system_enable') ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Username Alpha-Numeric Check</strong>';
								echo '<p class="setting-description">If enabled, all account usernames will only be allowed to have alpha-numeric characters.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="username_alphanumeric_check" value="1" '.(config('username_alphanumeric_check') ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="username_alphanumeric_check" value="0" '.(!config('username_alphanumeric_check') ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Username Minimum Length</strong>';
								echo '<p class="setting-description">Minimum allowed length of an account username.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="username_min_length" value="'.config('username_min_length').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Username Maximum Length</strong>';
								echo '<p class="setting-description">Maximum allowed length of an account username.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="username_max_length" value="'.config('username_max_length').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Password Minimum Length</strong>';
								echo '<p class="setting-description">Minimum allowed length of an account password.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="password_min_length" value="'.config('password_min_length').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Password Maximum Length</strong>';
								echo '<p class="setting-description">Maximum allowed length of an account password.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="password_max_length" value="'.config('password_max_length').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Email Maximum Length</strong>';
								echo '<p class="setting-description">Maximum allowed length of an account email address.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="email_max_length" value="'.config('email_max_length').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>AdminCP Sidebar Color</strong>';
								echo '<p class="setting-description">blue, azure, green, orange, red or purple.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="admincp_sidebar_color" value="'.config('admincp_sidebar_color').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Cron Jobs API</strong>';
								echo '<p class="setting-description">If enabled, you will be able to use thrid-party websites to manage the execution of your cron jobs.<br /><br />By default, your cron jobs api is located at:<br /><span style="color:red;">'.__BASE_URL__.'api/cron.php</span></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="cron_api_enabled" value="1" '.(config('cron_api_enabled') ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="cron_api_enabled" value="0" '.(!config('cron_api_enabled') ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Cron Jobs API Private Key</strong>';
								echo '<p class="setting-description">Private key to secure your cron jobs api from public execution.<br /><br />Used by sending the key through a <strong>$_GET</strong> variable:<br /><span style="color:red;">'.__BASE_URL__.'api/cron.php?key=private_key</span></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="cron_api_private_key" value="'.config('cron_api_private_key').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Login API</strong>';
								echo '<p class="setting-description">If enabled, thrid-party software can use the login api to validate account credentials.<br /><br />By default, your login api is located at:<br /><span style="color:red;">'.__BASE_URL__.'api/login.php</span><br /><br />Request variables:<br />key<br />username<br />email (can replace username)<br />password</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="login_api_enabled" value="1" '.(config('login_api_enabled') ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="login_api_enabled" value="0" '.(!config('login_api_enabled') ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Login API Private Key</strong>';
								echo '<p class="setting-description">Private key to secure your login api from public execution.<br /><br />Used by sending the key through a <strong>$_GET</strong> variable:<br /><span style="color:red;">'.__BASE_URL__.'api/login.php?key=private_key</span></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="login_api_private_key" value="'.config('login_api_private_key').'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>BDO Database Items</strong>';
								echo '<p class="setting-description">Link to BDO database item profiles.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="bdo_database" value="'.config('bdo_database').'" required>';
							echo '</td>';
						echo '</tr>';
						
					echo '</table>';
					echo '<br />';
					echo '<button type="submit" name="settings_submit" value="ok" class="btn btn-primary">Save Settings</button>';
				echo '</form>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';