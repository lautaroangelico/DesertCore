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
		
		// min_exchange_limit
		if(!check($_POST['min_exchange_limit'])) throw new Exception('Invalid setting value (min_exchange_limit)');
		if(!Validator::UnsignedNumber($_POST['min_exchange_limit'])) throw new Exception('Invalid setting value (min_exchange_limit)');
		$setting['min_exchange_limit'] = $_POST['min_exchange_limit'];
		
		// cash_per_hour
		if(!check($_POST['cash_per_hour'])) throw new Exception('Invalid setting value (cash_per_hour)');
		if(!Validator::UnsignedNumber($_POST['cash_per_hour'])) throw new Exception('Invalid setting value (cash_per_hour)');
		$setting['cash_per_hour'] = $_POST['cash_per_hour'];
		
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
			echo '<div class="header">Account Exchange Play Time Settings</div>';
			echo '<div class="content table-responsive">';
			
				echo '<form action="" method="post">';
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Minimum Exchange Limit (hours)</strong>';
								echo '<p class="setting-description">Minimum amount of hours a player is allowed to exchange to cash.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="min_exchange_limit" value="'.$cfg['min_exchange_limit'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Cash per Hour</strong>';
								echo '<p class="setting-description">Exchange rate of cash for each hour exchanged.</p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="cash_per_hour" value="'.$cfg['cash_per_hour'].'">';
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