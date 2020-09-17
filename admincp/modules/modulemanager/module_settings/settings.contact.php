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
		
		// Sendto
		if(!check($_POST['sendto'])) throw new Exception('Invalid setting value (sendto)');
		if(!Validator::Email($_POST['sendto'])) throw new Exception('Invalid setting value (sendto)');
		$setting['sendto'] = $_POST['sendto'];
		
		// Subject
		if(!check($_POST['subject'])) throw new Exception('Invalid setting value (subject)');
		$setting['subject'] = $_POST['subject'];
		
		// Message Min Length
		if(!check($_POST['message_min_length'])) throw new Exception('Invalid setting value (message_min_length)');
		if(!Validator::UnsignedNumber($_POST['message_min_length'])) throw new Exception('Invalid setting value (message_min_length)');
		$setting['message_min_length'] = $_POST['message_min_length'];
		
		// Message Max Length
		if(!check($_POST['message_max_length'])) throw new Exception('Invalid setting value (message_max_length)');
		if(!Validator::UnsignedNumber($_POST['message_max_length'])) throw new Exception('Invalid setting value (message_max_length)');
		$setting['message_max_length'] = $_POST['message_max_length'];
		
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
			echo '<div class="header">Contact Form Settings</div>';
			echo '<div class="content table-responsive">';
			
				echo '<form action="" method="post">';
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Send To</strong>';
								echo '<p class="setting-description">Email address where the contact form messages should be sent to.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="sendto" value="'.$cfg['sendto'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Subject</strong>';
								echo '<p class="setting-description">Subject of the contact form messages.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="subject" value="'.$cfg['subject'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Message Minimum Length</strong>';
								echo '<p class="setting-description">Minimum amount of characters the message can contain.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="message_min_length" value="'.$cfg['message_min_length'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Message Maximum Length</strong>';
								echo '<p class="setting-description">Maximum amount of characters the message can contain.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="message_max_length" value="'.$cfg['message_max_length'].'">';
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