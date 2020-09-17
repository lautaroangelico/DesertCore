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
		
		// show_client_downloads
		if(!check($_POST['show_client_downloads'])) throw new Exception('Invalid setting value (show_client_downloads)');
		if(!in_array($_POST['show_client_downloads'], array(0, 1))) throw new Exception('Invalid setting value (show_client_downloads)');
		$setting['show_client_downloads'] = $_POST['show_client_downloads'];
		
		// show_patch_downloads
		if(!check($_POST['show_patch_downloads'])) throw new Exception('Invalid setting value (show_patch_downloads)');
		if(!in_array($_POST['show_patch_downloads'], array(0, 1))) throw new Exception('Invalid setting value (show_patch_downloads)');
		$setting['show_patch_downloads'] = $_POST['show_patch_downloads'];
		
		// show_other_downloads
		if(!check($_POST['show_other_downloads'])) throw new Exception('Invalid setting value (show_other_downloads)');
		if(!in_array($_POST['show_other_downloads'], array(0, 1))) throw new Exception('Invalid setting value (show_other_downloads)');
		$setting['show_other_downloads'] = $_POST['show_other_downloads'];
		
		// show_system_requirements
		if(!check($_POST['show_system_requirements'])) throw new Exception('Invalid setting value (show_system_requirements)');
		if(!in_array($_POST['show_system_requirements'], array(0, 1))) throw new Exception('Invalid setting value (show_system_requirements)');
		$setting['show_system_requirements'] = $_POST['show_system_requirements'];
		
		// show_driver_downloads
		if(!check($_POST['show_driver_downloads'])) throw new Exception('Invalid setting value (show_driver_downloads)');
		if(!in_array($_POST['show_driver_downloads'], array(0, 1))) throw new Exception('Invalid setting value (show_driver_downloads)');
		$setting['show_driver_downloads'] = $_POST['show_driver_downloads'];
		
		// driver_link_nvidia
		if(!check($_POST['driver_link_nvidia'])) throw new Exception('Invalid setting value (driver_link_nvidia)');
		$setting['driver_link_nvidia'] = $_POST['driver_link_nvidia'];
		
		// driver_img_nvidia
		if(!check($_POST['driver_img_nvidia'])) throw new Exception('Invalid setting value (driver_img_nvidia)');
		$setting['driver_img_nvidia'] = $_POST['driver_img_nvidia'];
		
		// driver_link_amd
		if(!check($_POST['driver_link_amd'])) throw new Exception('Invalid setting value (driver_link_amd)');
		$setting['driver_link_amd'] = $_POST['driver_link_amd'];
		
		// driver_img_amd
		if(!check($_POST['driver_img_amd'])) throw new Exception('Invalid setting value (driver_img_amd)');
		$setting['driver_img_amd'] = $_POST['driver_img_amd'];
		
		// driver_link_intel
		if(!check($_POST['driver_link_intel'])) throw new Exception('Invalid setting value (driver_link_intel)');
		$setting['driver_link_intel'] = $_POST['driver_link_intel'];
		
		// driver_img_intel
		if(!check($_POST['driver_img_intel'])) throw new Exception('Invalid setting value (driver_img_intel)');
		$setting['driver_img_intel'] = $_POST['driver_img_intel'];
		
		
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
			echo '<div class="header">Downloads Settings</div>';
			echo '<div class="content table-responsive">';
			
				echo '<form action="" method="post">';
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Show Client Downloads</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_client_downloads" value="1" '.($cfg['show_client_downloads'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_client_downloads" value="0" '.(!$cfg['show_client_downloads'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Show Patch Downloads</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_patch_downloads" value="1" '.($cfg['show_patch_downloads'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_patch_downloads" value="0" '.(!$cfg['show_patch_downloads'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Show Other Downloads</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_other_downloads" value="1" '.($cfg['show_other_downloads'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_other_downloads" value="0" '.(!$cfg['show_other_downloads'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Show System Requirements</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_system_requirements" value="1" '.($cfg['show_system_requirements'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_system_requirements" value="0" '.(!$cfg['show_system_requirements'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Show Driver Downloads</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_driver_downloads" value="1" '.($cfg['show_driver_downloads'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="show_driver_downloads" value="0" '.(!$cfg['show_driver_downloads'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>NVIDIA Drivers Link</strong>';
								echo '<p class="setting-description">Link to NVIDIA\'s driver downloads page.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="driver_link_nvidia" value="'.$cfg['driver_link_nvidia'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>NVIDIA Drivers Logo Image</strong>';
								echo '<p class="setting-description">File name for NVIDIA\'s driver logo image (located in your template\'s image folder).</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="driver_img_nvidia" value="'.$cfg['driver_img_nvidia'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>AMD Drivers Link</strong>';
								echo '<p class="setting-description">Link to AMD\'s driver downloads page.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="driver_link_amd" value="'.$cfg['driver_link_amd'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>AMD Drivers Logo Image</strong>';
								echo '<p class="setting-description">File name for AMD\'s driver logo image (located in your template\'s image folder).</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="driver_img_amd" value="'.$cfg['driver_img_amd'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>INTEL Drivers Link</strong>';
								echo '<p class="setting-description">Link to INTEL\'s driver downloads page.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="driver_link_intel" value="'.$cfg['driver_link_intel'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>INTEL Drivers Logo Image</strong>';
								echo '<p class="setting-description">File name for INTEL\'s driver logo image (located in your template\'s image folder).</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="driver_img_intel" value="'.$cfg['driver_img_intel'].'">';
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