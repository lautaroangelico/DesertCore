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

$allowedSettings = array(
	'settings_submit', // the submit button
	'active',
	'send_from',
	'send_name',
	'smtp_active',
	'smtp_debug',
	'smtp_host',
	'smtp_ipv6',
	'smtp_port',
	'smtp_secure',
	'smtp_auth',
	'smtp_user',
	'smtp_pass',
	'email_header_image',
);

if(check($_POST['settings_submit'])) {
	try {
		
		// Status
		if(!check($_POST['active'])) throw new Exception('Invalid status setting.');
		if(!in_array($_POST['active'], array(0, 1))) throw new Exception('Invalid status setting.');
		$setting['active'] = ($_POST['active'] == 1 ? true : false);
		
		// Send Email From
		if(!check($_POST['send_from'])) throw new Exception('Please complete all required fields.');
		$setting['send_from'] = $_POST['send_from'];
		
		// Send Email As
		if(!check($_POST['send_name'])) throw new Exception('Please complete all required fields.');
		$setting['send_name'] = $_POST['send_name'];
		
		// SMTP Status
		if(!check($_POST['smtp_active'])) throw new Exception('Invalid SMTP status setting.');
		if(!in_array($_POST['smtp_active'], array(0, 1))) throw new Exception('Invalid SMTP status setting.');
		$setting['smtp_active'] = ($_POST['smtp_active'] == 1 ? true : false);
		
		// SMTP Debug
		if(!check($_POST['smtp_debug'])) throw new Exception('Invalid SMTP debug setting.');
		if(!in_array($_POST['smtp_debug'], array(0, 1, 2))) throw new Exception('Invalid SMTP debug setting.');
		$setting['smtp_debug'] = $_POST['smtp_debug'];
		
		// SMTP Host
		if($_POST['smtp_active']) if(!check($_POST['smtp_host'])) throw new Exception('Invalid SMTP host.');
		$setting['smtp_host'] = $_POST['smtp_host'];
		
		// SMTP IPV6
		if(!check($_POST['smtp_ipv6'])) throw new Exception('Invalid SMTP ipv6 support setting.');
		if(!in_array($_POST['smtp_ipv6'], array(0, 1))) throw new Exception('Invalid SMTP ipv6 support setting.');
		$setting['smtp_ipv6'] = ($_POST['smtp_ipv6'] == 1 ? true : false);
		
		// SMTP Port
		if(check($_POST['smtp_port'])) {
			if(!Validator::UnsignedNumber($_POST['smtp_port'])) throw new Exception('SMTP port must be a numeric value.');
			$setting['smtp_port'] = $_POST['smtp_port'];
		}
		
		// SMTP Secure
		if(!check($_POST['smtp_secure'])) throw new Exception('Invalid SMTP secure setting.');
		if(!in_array($_POST['smtp_secure'], array('tls', 'ssl'))) throw new Exception('Invalid SMTP secure setting.');
		$setting['smtp_secure'] = $_POST['smtp_secure'];
		
		// SMTP Auth
		if(!check($_POST['smtp_auth'])) throw new Exception('Invalid SMTP authentication setting.');
		if(!in_array($_POST['smtp_auth'], array(0, 1))) throw new Exception('Invalid SMTP authentication setting.');
		$setting['smtp_auth'] = ($_POST['smtp_auth'] == 1 ? true : false);
		
		// SMTP User
		if($_POST['smtp_auth'] == 1 && !check($_POST['smtp_user'])) throw new Exception('When SMTP authentication is enabled, usename is required.');
		if(check($_POST['smtp_user'])) {
			$setting['smtp_user'] = $_POST['smtp_user'];
		} else {
			$setting['smtp_user'] = "";
		}
		
		// SMTP Password
		if($_POST['smtp_auth'] == 1 && !check($_POST['smtp_pass'])) throw new Exception('When SMTP authentication is enabled, password is required.');
		if(check($_POST['smtp_pass'])) {
			$setting['smtp_pass'] = $_POST['smtp_pass'];
		} else {
			$setting['smtp_pass'] = "";
		}
		
		// email_header_image
		if(!check($_POST['email_header_image'])) throw new Exception('Invalid setting (email_header_image)');
		$setting['email_header_image'] = $_POST['email_header_image'];
		
		// configs
		$emailConfigurations = loadConfig('email');
		
		// make sure the settings are in the allow list
		foreach(array_keys($setting) as $settingName) {
			if(!in_array($settingName, $allowedSettings)) throw new Exception('One or more submitted setting is not editable.');
			
			$emailConfigurations[$settingName] = $setting[$settingName];
		}
		
		$newEmailConfig = json_encode($emailConfigurations, JSON_PRETTY_PRINT);
		$cfgFile = fopen(__PATH_CONFIGS__.'email.json', 'w');
		if(!$cfgFile) throw new Exception('There was a problem opening the configuration file.');
		
		fwrite($cfgFile, $newEmailConfig);
		fclose($cfgFile);
		
		message('success', 'Settings successfully saved!');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

$cfg = loadConfig('email');

echo '<div class="row">';
	echo '<div class="col-sm-12">';
		echo '<div class="card">';
			echo '<div class="header">Email Settings</div>';
			echo '<div class="content table-responsive">';
			
				echo '<form action="" method="post">';
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Status</strong>';
								echo '<p class="setting-description">Sets the email system on/off.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="active" value="1" '.($cfg['active'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="active" value="0" '.(!$cfg['active'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Send Email From</strong>';
								echo '<p class="setting-description">Address to use for outbound emails.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="send_from" value="'.$cfg['send_from'].'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Send Email As</strong>';
								echo '<p class="setting-description">Usually the server name.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="send_name" value="'.$cfg['send_name'].'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>SMTP Status</strong>';
								echo '<p class="setting-description">Sets the use of SMTP on/off.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="smtp_active" value="1" '.($cfg['smtp_active'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="smtp_active" value="0" '.(!$cfg['smtp_active'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>SMTP Debug</strong>';
								echo '<p class="setting-description">Sets the SMTP debugging debugging mode. It is recommended to turn debugging off for production use.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="smtp_debug" value="0" '.($cfg['smtp_debug'] == 0 ? 'checked' : null).'>';
										echo 'Off';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="smtp_debug" value="1" '.($cfg['smtp_debug'] == 1 ? 'checked' : null).'>';
										echo 'Client Messages';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="smtp_debug" value="2" '.($cfg['smtp_debug'] == 2 ? 'checked' : null).'>';
										echo 'Client and Server Messages';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>SMTP Host</strong>';
								echo '<p class="setting-description">Sets the hostname of the mail server.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="smtp_host" value="'.$cfg['smtp_host'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>SMTP over IPv6 Support</strong>';
								echo '<p class="setting-description">Disable this option if your network does not support SMTP over IPv6. By default this option is enabled.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="smtp_ipv6" value="1" '.($cfg['smtp_ipv6'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="smtp_ipv6" value="0" '.(!$cfg['smtp_ipv6'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>SMTP Port</strong>';
								echo '<p class="setting-description">Sets the SMTP port number. Common SMTP ports are 25, 2525, 587, 465, 2526.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="smtp_port" value="'.$cfg['smtp_port'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>SMTP Secure</strong>';
								echo '<p class="setting-description">Sets the encryption system to use. Default: tls.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="smtp_secure" value="tls" '.($cfg['smtp_secure'] == 'tls' ? 'checked' : null).'>';
										echo 'TLS';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="smtp_secure" value="ssl" '.($cfg['smtp_secure'] == 'ssl' ? 'checked' : null).'>';
										echo 'SSL';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>SMTP Authentication</strong>';
								echo '<p class="setting-description">Sets the SMTP authentication on/off.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="smtp_auth" value="1" '.($cfg['smtp_auth'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="smtp_auth" value="0" '.(!$cfg['smtp_auth'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>SMTP Username</strong>';
								echo '<p class="setting-description">Username to use for SMTP authentication (use full email address for gmail).</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="smtp_user" value="'.$cfg['smtp_user'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>SMTP Password</strong>';
								echo '<p class="setting-description">Password to use for SMTP authentication.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="smtp_pass" value="'.$cfg['smtp_pass'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Emails Header Image</strong>';
								echo '<p class="setting-description">Link to the header image used in the website\'s emails.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="email_header_image" value="'.$cfg['email_header_image'].'" required>';
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