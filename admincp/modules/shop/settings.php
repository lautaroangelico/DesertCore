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

$configurationFile = 'shop';

if(check($_POST['settings_submit'])) {
	try {
		
		// items_per_page
		if(!check($_POST['items_per_page'])) throw new Exception('Invalid setting (items_per_page)');
		$setting['items_per_page'] = $_POST['items_per_page'];
		
		// default_category
		if(!check($_POST['default_category'])) throw new Exception('Invalid setting (default_category)');
		$setting['default_category'] = $_POST['default_category'];
		
		// default_subcategory
		$setting['default_subcategory'] = $_POST['default_subcategory'];
		
		// enable_menu
		if(!check($_POST['enable_menu'])) throw new Exception('Invalid setting value (enable_menu)');
		if(!in_array($_POST['enable_menu'], array(0, 1))) throw new Exception('Invalid setting value (enable_menu)');
		$setting['enable_menu'] = ($_POST['enable_menu'] == 1 ? true : false);
		
		// enable_breadcrumb
		if(!check($_POST['enable_breadcrumb'])) throw new Exception('Invalid setting value (enable_breadcrumb)');
		if(!in_array($_POST['enable_breadcrumb'], array(0, 1))) throw new Exception('Invalid setting value (enable_breadcrumb)');
		$setting['enable_breadcrumb'] = ($_POST['enable_breadcrumb'] == 1 ? true : false);
		
		// mail_sender_name
		if(!check($_POST['mail_sender_name'])) throw new Exception('Invalid setting (mail_sender_name)');
		$setting['mail_sender_name'] = $_POST['mail_sender_name'];
		
		// mail_subject
		if(!check($_POST['mail_subject'])) throw new Exception('Invalid setting (mail_subject)');
		$setting['mail_subject'] = $_POST['mail_subject'];
		
		// mail_message
		if(!check($_POST['mail_message'])) throw new Exception('Invalid setting (mail_message)');
		$setting['mail_message'] = $_POST['mail_message'];
		
		// Update Configurations
		if(!updateConfig($configurationFile, $setting)) throw new Exception('There was an error updating the configuration file.');
		
		message('success', 'Settings successfully saved!');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

$cfg = loadConfig($configurationFile);
if(!is_array($cfg)) throw new Exception('Could not load configuration file.');

echo '<div class="row">';
	echo '<div class="col-sm-12">';
		echo '<div class="card">';
			echo '<div class="header">Web Shop Settings</div>';
			echo '<div class="content table-responsive">';
			
				echo '<form action="" method="post">';
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Items Per Page</strong>';
								echo '<p class="setting-description">Amount of items to be displayed in each web shop page.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="items_per_page" value="'.$cfg['items_per_page'].'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Default Category Id</strong>';
								echo '<p class="setting-description">Default category to load when visiting the web shop.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="default_category" value="'.$cfg['default_category'].'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Default Sub-Category Id (optional)</strong>';
								echo '<p class="setting-description">Default sub-category to load when visiting the web shop.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="default_subcategory" value="'.$cfg['default_subcategory'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Enable Web Shop Menu</strong>';
								echo '<p class="setting-description">Enable / disable the web shop categories menu.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="enable_menu" value="1" '.($cfg['enable_menu'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="enable_menu" value="0" '.(!$cfg['enable_menu'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Enable Web Shop Breadcrumb</strong>';
								echo '<p class="setting-description">Enable / disable the web shop navigation breadcrumb.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="enable_breadcrumb" value="1" '.($cfg['enable_breadcrumb'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="enable_breadcrumb" value="0" '.(!$cfg['enable_breadcrumb'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Item Mail Sender Name</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="mail_sender_name" value="'.$cfg['mail_sender_name'].'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Item Mail Subject</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="mail_subject" value="'.$cfg['mail_subject'].'" required>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Item Mail Message</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="mail_message" value="'.$cfg['mail_message'].'" required>';
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