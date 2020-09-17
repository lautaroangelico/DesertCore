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
		
		// rankings_results
		if(!check($_POST['rankings_results'])) throw new Exception('Invalid setting value (rankings_results)');
		if(!Validator::UnsignedNumber($_POST['rankings_results'])) throw new Exception('Invalid setting value (rankings_results)');
		$setting['rankings_results'] = $_POST['rankings_results'];
		
		// rankings_show_rank_number
		if(!check($_POST['rankings_show_rank_number'])) throw new Exception('Invalid setting value (rankings_show_rank_number)');
		if(!in_array($_POST['rankings_show_rank_number'], array(0, 1))) throw new Exception('Invalid setting value (rankings_show_rank_number)');
		$setting['rankings_show_rank_number'] = $_POST['rankings_show_rank_number'];
		
		// rankings_show_rank_laurels
		if(!check($_POST['rankings_show_rank_laurels'])) throw new Exception('Invalid setting value (rankings_show_rank_laurels)');
		if(!in_array($_POST['rankings_show_rank_laurels'], array(0, 1))) throw new Exception('Invalid setting value (rankings_show_rank_laurels)');
		$setting['rankings_show_rank_laurels'] = $_POST['rankings_show_rank_laurels'];
		
		// rankings_rank_laurels_limit
		if(!check($_POST['rankings_rank_laurels_limit'])) throw new Exception('Invalid setting value (rankings_rank_laurels_limit)');
		if(!Validator::UnsignedNumber($_POST['rankings_rank_laurels_limit'])) throw new Exception('Invalid setting value (rankings_rank_laurels_limit)');
		$setting['rankings_rank_laurels_limit'] = $_POST['rankings_rank_laurels_limit'];
		
		// rankings_rank_laurels_base_size
		if(!check($_POST['rankings_rank_laurels_base_size'])) throw new Exception('Invalid setting value (rankings_rank_laurels_base_size)');
		if(!Validator::UnsignedNumber($_POST['rankings_rank_laurels_base_size'])) throw new Exception('Invalid setting value (rankings_rank_laurels_base_size)');
		$setting['rankings_rank_laurels_base_size'] = $_POST['rankings_rank_laurels_base_size'];
		
		// rankings_rank_laurels_decrease_by
		if(!check($_POST['rankings_rank_laurels_decrease_by'])) throw new Exception('Invalid setting value (rankings_rank_laurels_decrease_by)');
		if(!Validator::UnsignedNumber($_POST['rankings_rank_laurels_decrease_by'])) throw new Exception('Invalid setting value (rankings_rank_laurels_decrease_by)');
		$setting['rankings_rank_laurels_decrease_by'] = $_POST['rankings_rank_laurels_decrease_by'];
		
		// rankings_enable_level
		if(!check($_POST['rankings_enable_level'])) throw new Exception('Invalid setting value (rankings_enable_level)');
		if(!in_array($_POST['rankings_enable_level'], array(0, 1))) throw new Exception('Invalid setting value (rankings_enable_level)');
		$setting['rankings_enable_level'] = $_POST['rankings_enable_level'];
		
		// rankings_level_experience_based
		if(!check($_POST['rankings_level_experience_based'])) throw new Exception('Invalid setting value (rankings_level_experience_based)');
		if(!in_array($_POST['rankings_level_experience_based'], array(0, 1))) throw new Exception('Invalid setting value (rankings_level_experience_based)');
		$setting['rankings_level_experience_based'] = $_POST['rankings_level_experience_based'];
		
		// rankings_enable_online
		if(!check($_POST['rankings_enable_online'])) throw new Exception('Invalid setting value (rankings_enable_online)');
		if(!in_array($_POST['rankings_enable_online'], array(0, 1))) throw new Exception('Invalid setting value (rankings_enable_online)');
		$setting['rankings_enable_online'] = $_POST['rankings_enable_online'];
		
		// rankings_excluded_characters
		$setting['rankings_excluded_characters'] = $_POST['rankings_excluded_characters'];
		if(!check($_POST['rankings_excluded_characters'])) $setting['rankings_excluded_characters'] = '';
		
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
			echo '<div class="header">Rankings Settings</div>';
			echo '<div class="content table-responsive">';
			
				echo '<form action="" method="post">';
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Results</strong>';
								echo '<p class="setting-description">Amount of results to cache and show in the the rankings.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="rankings_results" value="'.$cfg['rankings_results'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Show Rank Number</strong>';
								echo '<p class="setting-description">If enabled, rank numbers will be displayed in the rankings table.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="rankings_show_rank_number" value="1" '.($cfg['rankings_show_rank_number'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="rankings_show_rank_number" value="0" '.(!$cfg['rankings_show_rank_number'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Show Rank Laurels</strong>';
								echo '<p class="setting-description">If enabled, the top players ranks will be displayed with laurel\'s.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="rankings_show_rank_laurels" value="1" '.($cfg['rankings_show_rank_laurels'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="rankings_show_rank_laurels" value="0" '.(!$cfg['rankings_show_rank_laurels'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Laurel\'s Rank Limit</strong>';
								echo '<p class="setting-description">Amount of laurel ranks to display. The laurel images must be in your template\'s image folder.<br /><br />Laurel\'s image naming:<br />rank_<span style="color:red;">x</span>.png</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="rankings_rank_laurels_limit" value="'.$cfg['rankings_rank_laurels_limit'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Laurel\'s Base Size (px)</strong>';
								echo '<p class="setting-description">Base size in pixels of the laurel\'s image display in the rankings table.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="rankings_rank_laurels_base_size" value="'.$cfg['rankings_rank_laurels_base_size'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Laurel\'s Size Decrease By (px)</strong>';
								echo '<p class="setting-description">Amount of pixels the laurel\'s image will decrease after each rank. Set to 0 for all laurel\'s to remain the same (base) size.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="rankings_rank_laurels_decrease_by" value="'.$cfg['rankings_rank_laurels_decrease_by'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Enable Level Rankings</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="rankings_enable_level" value="1" '.($cfg['rankings_enable_level'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="rankings_enable_level" value="0" '.(!$cfg['rankings_enable_level'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Level Rankings Based on Player Experience</strong>';
								echo '<p class="setting-description">Set this configuration to <span style="color:red;">No</span> if you would like the ranking to be ordered based on the player\'s <i>Level</i>.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="rankings_level_experience_based" value="1" '.($cfg['rankings_level_experience_based'] ? 'checked' : null).'>';
										echo 'Yes';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="rankings_level_experience_based" value="0" '.(!$cfg['rankings_level_experience_based'] ? 'checked' : null).'>';
										echo 'No';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Enable Online Rankings</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="rankings_enable_online" value="1" '.($cfg['rankings_enable_online'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="rankings_enable_online" value="0" '.(!$cfg['rankings_enable_online'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Excluded Characters</strong>';
								echo '<p class="setting-description">List of excluded characters from the rankings (separated by commas).</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="rankings_excluded_characters" value="'.$cfg['rankings_excluded_characters'].'">';
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