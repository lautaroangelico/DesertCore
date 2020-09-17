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
		
		// send_welcome_email
		if(!check($_POST['send_welcome_email'])) throw new Exception('Invalid setting value (send_welcome_email)');
		if(!in_array($_POST['send_welcome_email'], array(0, 1))) throw new Exception('Invalid setting value (send_welcome_email)');
		$setting['send_welcome_email'] = $_POST['send_welcome_email'];
		
		// verify_email
		if(!check($_POST['verify_email'])) throw new Exception('Invalid setting value (verify_email)');
		if(!in_array($_POST['verify_email'], array(0, 1))) throw new Exception('Invalid setting value (verify_email)');
		$setting['verify_email'] = $_POST['verify_email'];
		
		// verification_timelimit
		if(!check($_POST['verification_timelimit'])) throw new Exception('Invalid setting value (verification_timelimit)');
		if(!Validator::UnsignedNumber($_POST['verification_timelimit'])) throw new Exception('Invalid setting value (verification_timelimit)');
		$setting['verification_timelimit'] = $_POST['verification_timelimit'];
		
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
			echo '<div class="header">Register Settings</div>';
			echo '<div class="content table-responsive">';
			
				echo '<form action="" method="post">';
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Send Welcome Email</strong>';
								echo '<p class="setting-description">If enabled, the user will receive a welcome email after registering a new account.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="send_welcome_email" value="1" '.($cfg['send_welcome_email'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="send_welcome_email" value="0" '.(!$cfg['send_welcome_email'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Email Verification</strong>';
								echo '<p class="setting-description">If enabled, the user will need to verify the email address used before the account is created.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="verify_email" value="1" '.($cfg['verify_email'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="verify_email" value="0" '.(!$cfg['verify_email'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Email Verification Time Limit</strong>';
								echo '<p class="setting-description">Amount of time (in seconds) the verification link will remain valid. After the verification time limit is reached the registration details are automatically deleted.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="verification_timelimit" value="'.$cfg['verification_timelimit'].'">';
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