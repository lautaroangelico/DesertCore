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
		
		// show_account_info
		if(!check($_POST['show_account_info'])) throw new Exception('Invalid setting value (show_account_info)');
		if(!in_array($_POST['show_account_info'], array(0, 1))) throw new Exception('Invalid setting value (show_account_info)');
		$setting['show_account_info'] = $_POST['show_account_info'];
		
		// show_social_info
		if(!check($_POST['show_social_info'])) throw new Exception('Invalid setting value (show_social_info)');
		if(!in_array($_POST['show_social_info'], array(0, 1))) throw new Exception('Invalid setting value (show_social_info)');
		$setting['show_social_info'] = $_POST['show_social_info'];
		
		// show_credits_info
		if(!check($_POST['show_credits_info'])) throw new Exception('Invalid setting value (show_credits_info)');
		if(!in_array($_POST['show_credits_info'], array(0, 1))) throw new Exception('Invalid setting value (show_credits_info)');
		$setting['show_credits_info'] = $_POST['show_credits_info'];
		
		// show_ban_info
		if(!check($_POST['show_ban_info'])) throw new Exception('Invalid setting value (show_ban_info)');
		if(!in_array($_POST['show_ban_info'], array(0, 1))) throw new Exception('Invalid setting value (show_ban_info)');
		$setting['show_ban_info'] = $_POST['show_ban_info'];
		
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
			echo '<div class="header">Account Profile Settings</div>';
			echo '<div class="content table-responsive">';
			
				echo '<form action="" method="post">';
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Show Account Information</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_account_info" value="1" '.($cfg['show_account_info'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_account_info" value="0" '.(!$cfg['show_account_info'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Show Linked Social Accounts</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_social_info" value="1" '.($cfg['show_social_info'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_social_info" value="0" '.(!$cfg['show_social_info'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Show Credits Information</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_credits_info" value="1" '.($cfg['show_credits_info'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_credits_info" value="0" '.(!$cfg['show_credits_info'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Show Ban History</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_ban_info" value="1" '.($cfg['show_ban_info'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_ban_info" value="0" '.(!$cfg['show_ban_info'] ? 'checked' : null).'>';
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