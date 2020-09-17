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

$configurationFile = 'recaptcha';

$allowedSettings = array(
	'settings_submit', // the submit button
	'registration',
	'login',
	'password_recovery',
	'username_recovery',
	'redeem_code',
	'site_key',
	'secret_key',
);

if(check($_POST['settings_submit'])) {
	try {
		
		// registration
		if(!check($_POST['registration'])) throw new Exception('Invalid setting value (registration)');
		if(!in_array($_POST['registration'], array(0, 1))) throw new Exception('Invalid setting value (registration)');
		$setting['registration'] = ($_POST['registration'] == 1 ? true : false);
		
		// login
		if(!check($_POST['login'])) throw new Exception('Invalid setting value (login)');
		if(!in_array($_POST['login'], array(0, 1))) throw new Exception('Invalid setting value (login)');
		$setting['login'] = ($_POST['login'] == 1 ? true : false);
		
		// password_recovery
		if(!check($_POST['password_recovery'])) throw new Exception('Invalid setting value (password_recovery)');
		if(!in_array($_POST['password_recovery'], array(0, 1))) throw new Exception('Invalid setting value (password_recovery)');
		$setting['password_recovery'] = ($_POST['password_recovery'] == 1 ? true : false);
		
		// username_recovery
		if(!check($_POST['username_recovery'])) throw new Exception('Invalid setting value (username_recovery)');
		if(!in_array($_POST['username_recovery'], array(0, 1))) throw new Exception('Invalid setting value (username_recovery)');
		$setting['username_recovery'] = ($_POST['username_recovery'] == 1 ? true : false);
		
		// redeem_code
		if(!check($_POST['redeem_code'])) throw new Exception('Invalid setting value (redeem_code)');
		if(!in_array($_POST['redeem_code'], array(0, 1))) throw new Exception('Invalid setting value (redeem_code)');
		$setting['redeem_code'] = ($_POST['redeem_code'] == 1 ? true : false);
		
		// site_key
		$setting['site_key'] = $_POST['site_key'];
		if(!check($_POST['site_key'])) $setting['site_key'] = '';
		
		// secret_key
		$setting['secret_key'] = $_POST['secret_key'];
		if(!check($_POST['secret_key'])) $setting['secret_key'] = '';
		
		// configs
		$configurations = loadConfig($configurationFile);
		
		// make sure the settings are in the allow list
		foreach(array_keys($setting) as $settingName) {
			if(!in_array($settingName, $allowedSettings)) throw new Exception('One or more submitted setting is not editable.');
			
			$configurations[$settingName] = $setting[$settingName];
		}
		
		$newConfigurations = json_encode($configurations, JSON_PRETTY_PRINT);
		$cfgFile = fopen(__PATH_CONFIGS__.$configurationFile.'.json', 'w');
		if(!$cfgFile) throw new Exception('There was a problem saving the configurations.');
		if(!fwrite($cfgFile, $newConfigurations)) throw new Exception('There was a problem saving the configurations.');
		if(!fclose($cfgFile)) throw new Exception('There was a problem saving the configurations.');
		
		message('success', 'Settings successfully saved!');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

$cfg = loadConfig($configurationFile);

echo '<div class="row">';
	echo '<div class="col-sm-12">';
		echo '<div class="card">';
			echo '<div class="header">Google Recaptcha Settings</div>';
			echo '<div class="content table-responsive">';
			
				echo '<form action="" method="post">';
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Account Registration</strong>';
								echo '<p class="setting-description">Enables Recaptcha in the account registration form.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="registration" value="1" '.($cfg['registration'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="registration" value="0" '.(!$cfg['registration'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Account Login</strong>';
								echo '<p class="setting-description">Enables Recaptcha in the account login form.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="login" value="1" '.($cfg['login'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="login" value="0" '.(!$cfg['login'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Password Recovery</strong>';
								echo '<p class="setting-description">Enables Recaptcha in the password recovery form.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="password_recovery" value="1" '.($cfg['password_recovery'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="password_recovery" value="0" '.(!$cfg['password_recovery'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Username Recovery</strong>';
								echo '<p class="setting-description">Enables Recaptcha in the username recovery form.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="username_recovery" value="1" '.($cfg['username_recovery'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="username_recovery" value="0" '.(!$cfg['username_recovery'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Shop Redeem Code</strong>';
								echo '<p class="setting-description">Enables Recaptcha in the shop\'s redeem code form.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="redeem_code" value="1" '.($cfg['redeem_code'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="redeem_code" value="0" '.(!$cfg['redeem_code'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Recaptcha Site Key</strong>';
								echo '<p class="setting-description"><a href="https://www.google.com/recaptcha" target="_blank">https://www.google.com/recaptcha</a></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="site_key" value="'.$cfg['site_key'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Recaptcha Secret Key</strong>';
								echo '<p class="setting-description"><a href="https://www.google.com/recaptcha" target="_blank">https://www.google.com/recaptcha</a></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="secret_key" value="'.$cfg['secret_key'].'">';
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