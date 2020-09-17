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

$configurationFile = 'account.tickets';

if(check($_POST['settings_submit'])) {
	try {
		
		// subject_min_len
		if(!check($_POST['subject_min_len'])) throw new Exception('Invalid setting value (subject_min_len)');
		if(!Validator::UnsignedNumber($_POST['subject_min_len'])) throw new Exception('Invalid setting value (subject_min_len)');
		$setting['subject_min_len'] = $_POST['subject_min_len'];
		
		// subject_max_len
		if(!check($_POST['subject_max_len'])) throw new Exception('Invalid setting value (subject_max_len)');
		if(!Validator::UnsignedNumber($_POST['subject_max_len'])) throw new Exception('Invalid setting value (subject_max_len)');
		$setting['subject_max_len'] = $_POST['subject_max_len'];
		
		// message_min_len
		if(!check($_POST['message_min_len'])) throw new Exception('Invalid setting value (message_min_len)');
		if(!Validator::UnsignedNumber($_POST['message_min_len'])) throw new Exception('Invalid setting value (message_min_len)');
		$setting['message_min_len'] = $_POST['message_min_len'];
		
		// message_max_len
		if(!check($_POST['message_max_len'])) throw new Exception('Invalid setting value (message_max_len)');
		if(!Validator::UnsignedNumber($_POST['message_max_len'])) throw new Exception('Invalid setting value (message_max_len)');
		$setting['message_max_len'] = $_POST['message_max_len'];
		
		// message_order
		if(!check($_POST['message_order'])) throw new Exception('Invalid setting value (message_order)');
		if(!in_array($_POST['message_order'], array('ASC','DESC'))) throw new Exception('Invalid setting value (message_order)');
		$setting['message_order'] = $_POST['message_order'];
		
		// staff_reply_name
		if(!check($_POST['staff_reply_name'])) throw new Exception('Invalid setting value (staff_reply_name)');
		$setting['staff_reply_name'] = $_POST['staff_reply_name'];
		
		// open_email_notification
		if(!check($_POST['open_email_notification'])) throw new Exception('Invalid setting value (open_email_notification)');
		if(!in_array($_POST['open_email_notification'], array(0, 1))) throw new Exception('Invalid setting value (open_email_notification)');
		$setting['open_email_notification'] = ($_POST['open_email_notification'] == 1 ? true : false);
		
		// reply_email_notification
		if(!check($_POST['reply_email_notification'])) throw new Exception('Invalid setting value (reply_email_notification)');
		if(!in_array($_POST['reply_email_notification'], array(0, 1))) throw new Exception('Invalid setting value (reply_email_notification)');
		$setting['reply_email_notification'] = ($_POST['reply_email_notification'] == 1 ? true : false);
		
		// close_email_notification
		if(!check($_POST['close_email_notification'])) throw new Exception('Invalid setting value (close_email_notification)');
		if(!in_array($_POST['close_email_notification'], array(0, 1))) throw new Exception('Invalid setting value (close_email_notification)');
		$setting['close_email_notification'] = ($_POST['close_email_notification'] == 1 ? true : false);
		
		// staff_new_notification
		if(!check($_POST['staff_new_notification'])) throw new Exception('Invalid setting value (staff_new_notification)');
		if(!in_array($_POST['staff_new_notification'], array(0, 1))) throw new Exception('Invalid setting value (staff_new_notification)');
		$setting['staff_new_notification'] = ($_POST['staff_new_notification'] == 1 ? true : false);
		
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
			echo '<div class="header">Tickets Settings</div>';
			echo '<div class="content table-responsive">';
			
				echo '<form action="" method="post">';
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Subject Minimum Length</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="subject_min_len" value="'.$cfg['subject_min_len'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Subject Maximum Length</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="subject_max_len" value="'.$cfg['subject_max_len'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Message Minimum Length</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="message_min_len" value="'.$cfg['message_min_len'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Message Maximum Length</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="message_max_len" value="'.$cfg['message_max_len'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Message Display Order</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="message_order" value="ASC" '.($cfg['message_order'] == 'ASC' ? 'checked' : null).'>';
										echo 'Ascending';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="message_order" value="DESC" '.(!$cfg['message_order'] == 'DESC' ? 'checked' : null).'>';
										echo 'Descending';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Staff Reply Name</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="staff_reply_name" value="'.$cfg['staff_reply_name'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>New Ticket Email Notification</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="open_email_notification" value="1" '.($cfg['open_email_notification'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="open_email_notification" value="0" '.(!$cfg['open_email_notification'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Ticket Reply Email Notification</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="reply_email_notification" value="1" '.($cfg['reply_email_notification'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="reply_email_notification" value="0" '.(!$cfg['reply_email_notification'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Ticket Close Email Notification</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="close_email_notification" value="1" '.($cfg['close_email_notification'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="close_email_notification" value="0" '.(!$cfg['close_email_notification'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Ticket Open Staff Email Notification</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="staff_new_notification" value="1" '.($cfg['staff_new_notification'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="staff_new_notification" value="0" '.(!$cfg['staff_new_notification'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
					echo '</table>';
					echo '<br />';
					echo '<button type="submit" name="settings_submit" value="ok" class="btn btn-primary">Save Settings</button> ';
				echo '</form>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';