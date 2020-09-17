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
		
		// enable_session_timeout
		if(!check($_POST['enable_session_timeout'])) throw new Exception('Invalid setting value (enable_session_timeout)');
		if(!in_array($_POST['enable_session_timeout'], array(0, 1))) throw new Exception('Invalid setting value (enable_session_timeout)');
		$setting['enable_session_timeout'] = $_POST['enable_session_timeout'];
		
		// session_timeout
		if(!check($_POST['session_timeout'])) throw new Exception('Invalid setting value (session_timeout)');
		if(!Validator::UnsignedNumber($_POST['session_timeout'])) throw new Exception('Invalid setting value (session_timeout)');
		$setting['session_timeout'] = $_POST['session_timeout'];
		
		// max_login_attempts
		if(!check($_POST['max_login_attempts'])) throw new Exception('Invalid setting value (max_login_attempts)');
		if(!Validator::UnsignedNumber($_POST['max_login_attempts'])) throw new Exception('Invalid setting value (max_login_attempts)');
		$setting['max_login_attempts'] = $_POST['max_login_attempts'];
		
		// failed_login_timeout
		if(!check($_POST['failed_login_timeout'])) throw new Exception('Invalid setting value (failed_login_timeout)');
		if(!Validator::UnsignedNumber($_POST['failed_login_timeout'])) throw new Exception('Invalid setting value (failed_login_timeout)');
		$setting['failed_login_timeout'] = $_POST['failed_login_timeout'];
		
		// max_failed_attempts_notification
		if(!check($_POST['max_failed_attempts_notification'])) throw new Exception('Invalid setting value (max_failed_attempts_notification)');
		if(!in_array($_POST['max_failed_attempts_notification'], array(0, 1))) throw new Exception('Invalid setting value (max_failed_attempts_notification)');
		$setting['max_failed_attempts_notification'] = $_POST['max_failed_attempts_notification'];
		
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
			echo '<div class="header">Login Settings</div>';
			echo '<div class="content table-responsive">';
			
				echo '<form action="" method="post">';
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Enable Session Timeout</strong>';
								echo '<p class="setting-description">If enabled, after the session timeout is reached the account will be automatically logged out from the website.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="enable_session_timeout" value="1" '.($cfg['enable_session_timeout'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="enable_session_timeout" value="0" '.(!$cfg['enable_session_timeout'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Session Timeout</strong>';
								echo '<p class="setting-description">Amount of idle time (in seconds) before the account is automatically logged out.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="session_timeout" value="'.$cfg['session_timeout'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Maximum Failed Login Attempts</strong>';
								echo '<p class="setting-description">Maximum number of allowed failed logins. After reaching the maximum amount the IP address will be blocked from using the login module.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="max_login_attempts" value="'.$cfg['max_login_attempts'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Failed Login Attempts Timeout</strong>';
								echo '<p class="setting-description">Amount of time (in seconds) the IP address is to remain blocked from using the login module after reaching the maximum amount of failed logins.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="failed_login_timeout" value="'.$cfg['failed_login_timeout'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Enable Maximum Failed Attempts Notification</strong>';
								echo '<p class="setting-description">If enabled, when reaching the maximum number of failed attempts, the account owner will receive a notification by email regarding the failed attempts.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="max_failed_attempts_notification" value="1" '.($cfg['max_failed_attempts_notification'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="max_failed_attempts_notification" value="0" '.(!$cfg['max_failed_attempts_notification'] ? 'checked' : null).'>';
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