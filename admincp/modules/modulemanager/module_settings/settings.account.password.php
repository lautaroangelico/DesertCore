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

$configurationFile = $_GET['id'];

if(check($_POST['settings_submit'])) {
	try {
		
		// Require Verification
		if(!check($_POST['require_verification'])) throw new Exception('Invalid setting value (require_verification)');
		if(!in_array($_POST['require_verification'], array(0, 1))) throw new Exception('Invalid setting value (require_verification)');
		$setting['require_verification'] = $_POST['require_verification'];
		
		// Verification Timeout
		if(!check($_POST['request_timeout'])) throw new Exception('Invalid setting value (request_timeout)');
		if(!Validator::UnsignedNumber($_POST['request_timeout'])) throw new Exception('Invalid setting value (request_timeout)');
		$setting['request_timeout'] = $_POST['request_timeout'];
		
		// Send Password Change Notification Email
		if(!check($_POST['send_new_password_email'])) throw new Exception('Invalid setting value (send_new_password_email)');
		if(!in_array($_POST['send_new_password_email'], array(0, 1))) throw new Exception('Invalid setting value (send_new_password_email)');
		$setting['send_new_password_email'] = $_POST['send_new_password_email'];
		
		// Update Configurations
		if(!updateModuleConfig($configurationFile, $setting)) throw new Exception('There was an error updating the configuration file.');
		
		message('success', 'Settings successfully saved!');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

$cfg = loadModuleConfig($configurationFile);
if(!is_array($cfg)) throw new Exception('Could not load configuration file.');

echo '<div class="row">';
	echo '<div class="col-sm-12">';
		echo '<div class="card">';
			echo '<div class="header">Change Password Settings</div>';
			echo '<div class="content table-responsive">';
			
				echo '<form action="" method="post">';
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Require Verification</strong>';
								echo '<p class="setting-description">Enable / Disable the email verification when changing the account\'s password.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="require_verification" value="1" '.($cfg['require_verification'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="require_verification" value="0" '.(!$cfg['require_verification'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Verification Timeout</strong>';
								echo '<p class="setting-description">Defines the amount of time (in seconds) the verification link is to remain valid.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="request_timeout" value="'.$cfg['request_timeout'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Send Password Change Notification Email</strong>';
								echo '<p class="setting-description">Enable / Disable sending the user an email notification about the password change.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="send_new_password_email" value="1" '.($cfg['send_new_password_email'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="send_new_password_email" value="0" '.(!$cfg['send_new_password_email'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						
					echo '</table>';
					echo '<br />';
					echo '<button type="submit" name="settings_submit" value="ok" class="btn btn-primary">Save Settings</button> ';
					echo '<a href="'.admincp_base('modulemanager/list').'" class="btn btn-danger">Cancel</a>';
				echo '</form>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';