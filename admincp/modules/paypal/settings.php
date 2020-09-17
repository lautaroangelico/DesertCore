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

$configurationFile = 'paypal';

if(check($_POST['settings_submit'])) {
	try {
		
		// sandbox
		if(!check($_POST['sandbox'])) throw new Exception('Invalid setting (sandbox).');
		if(!in_array($_POST['sandbox'], array(0, 1))) throw new Exception('Invalid setting (sandbox).');
		$setting['sandbox'] = ($_POST['sandbox'] == 1 ? true : false);
		
		// seller_email
		if(!check($_POST['seller_email'])) throw new Exception('Invalid setting (seller_email)');
		$setting['seller_email'] = $_POST['seller_email'];
		
		// ban_on_refund
		//if(!check($_POST['ban_on_refund'])) throw new Exception('Invalid setting (ban_on_refund).');
		//if(!in_array($_POST['ban_on_refund'], array(0, 1))) throw new Exception('Invalid setting (ban_on_refund).');
		//$setting['ban_on_refund'] = ($_POST['ban_on_refund'] == 1 ? true : false);
		$setting['ban_on_refund'] = false;
		
		// currency
		if(!check($_POST['currency'])) throw new Exception('Invalid setting (currency)');
		$setting['currency'] = $_POST['currency'];
		
		// button_image_url
		if(!check($_POST['button_image_url'])) throw new Exception('Invalid setting (button_image_url)');
		$setting['button_image_url'] = $_POST['button_image_url'];
		
		// Update Configurations
		if(!updateConfig($configurationFile, $setting)) throw new Exception('There was an error updating the configuration file.');
		
		message('success', 'Settings successfully saved!');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

$cfg = loadConfig('paypal');
if(!is_array($cfg)) throw new Exception('Could not load configuration file.');

$currencyList = array_keys(getCurrencies());
if(!is_array($currencyList)) throw new Exception('Could not load currency list.');

$ipnApi = __BASE_URL__ . 'api/paypal.php';
message('info', '<strong>Your PayPal IPN API is:</strong><br />' . $ipnApi);

echo '<div class="row">';
	echo '<div class="col-sm-12">';
		echo '<div class="card">';
			echo '<div class="header">PayPal Settings</div>';
			echo '<div class="content table-responsive">';
			
				echo '<form action="" method="post">';
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Sandbox Mode</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="sandbox" value="1" '.($cfg['sandbox'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="sandbox" value="0" '.(!$cfg['sandbox'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Receiver Email</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="seller_email" value="'.$cfg['seller_email'].'" required>';
							echo '</td>';
						echo '</tr>';
						
						/*
						echo '<tr>';
							echo '<td>';
								echo '<strong>Ban Account on Refund/Charge-back</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="ban_on_refund" value="1" '.($cfg['ban_on_refund'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="ban_on_refund" value="0" '.(!$cfg['ban_on_refund'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						*/
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Currency</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<select class="form-control" name="currency" required>';
									foreach($currencyList as $currencyCode) {
										if($cfg['currency'] == $currencyCode) {
											echo '<option value="'.$currencyCode.'" selected>'.$currencyCode.'</option>';
										} else {
											echo '<option value="'.$currencyCode.'">'.$currencyCode.'</option>';
										}
									}
								echo '</select>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>PayPal Checkout Button Image URL</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="button_image_url" value="'.$cfg['button_image_url'].'" required>';
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