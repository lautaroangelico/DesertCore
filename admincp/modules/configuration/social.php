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

$configurationFile = 'social';

if(check($_POST['settings_submit'])) {
	try {
		
		// xxxxxx
		if(!check($_POST['enabled'])) throw new Exception('Invalid setting value (enabled)');
		if(!in_array($_POST['enabled'], array(0, 1))) throw new Exception('Invalid setting value (enabled)');
		$setting['enabled'] = ($_POST['enabled'] == 1 ? true : false);
		
		// facebook_enabled
		if(!check($_POST['facebook_enabled'])) throw new Exception('Invalid setting value (facebook_enabled)');
		if(!in_array($_POST['facebook_enabled'], array(0, 1))) throw new Exception('Invalid setting value (facebook_enabled)');
		$setting['facebook_enabled'] = ($_POST['facebook_enabled'] == 1 ? true : false);
		
		// facebook_id
		$setting['facebook_id'] = $_POST['facebook_id'];
		if(!check($_POST['facebook_id'])) $setting['facebook_id'] = '';
		
		// facebook_secret
		$setting['facebook_secret'] = $_POST['facebook_secret'];
		if(!check($_POST['facebook_secret'])) $setting['facebook_secret'] = '';
		
		// facebook_scope
		$setting['facebook_scope'] = $_POST['facebook_scope'];
		if(!check($_POST['facebook_scope'])) $setting['facebook_scope'] = '';
		
		// google_enabled
		if(!check($_POST['google_enabled'])) throw new Exception('Invalid setting value (google_enabled)');
		if(!in_array($_POST['google_enabled'], array(0, 1))) throw new Exception('Invalid setting value (google_enabled)');
		$setting['google_enabled'] = ($_POST['google_enabled'] == 1 ? true : false);
		
		// google_id
		$setting['google_id'] = $_POST['google_id'];
		if(!check($_POST['google_id'])) $setting['google_id'] = '';
		
		// google_secret
		$setting['google_secret'] = $_POST['google_secret'];
		if(!check($_POST['google_secret'])) $setting['google_secret'] = '';
		
		// google_scope
		$setting['google_scope'] = $_POST['google_scope'];
		if(!check($_POST['google_scope'])) $setting['google_scope'] = '';
		
		// configs
		$configurations = loadConfig($configurationFile);
		
		$configurations['enabled'] = $setting['enabled'];
		$configurations['provider']['facebook']['enabled'] = $setting['facebook_enabled'];
		$configurations['provider']['facebook']['id'] = $setting['facebook_id'];
		$configurations['provider']['facebook']['secret'] = $setting['facebook_secret'];
		$configurations['provider']['facebook']['scope'] = $setting['facebook_scope'];
		$configurations['provider']['google']['enabled'] = $setting['google_enabled'];
		$configurations['provider']['google']['id'] = $setting['google_id'];
		$configurations['provider']['google']['secret'] = $setting['google_secret'];
		$configurations['provider']['google']['scope'] = $setting['google_scope'];
		
		$newConfigurations = json_encode($configurations, JSON_PRETTY_PRINT);
		$cfgFile = fopen(__PATH_CONFIGS__.$configurationFile.'.json', 'w');
		if(!$cfgFile) throw new Exception('There was a problem saving the configurations.');
		if(!fwrite($cfgFile, $newConfigurations)) throw new Exception('There was a problem saving the configurations.');
		if(!fclose($cfgFile)) throw new Exception('There was a problem saving the configurations.');
		
		message('success', 'Settings successfully saved!');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

$cfg = loadConfig($configurationFile);

echo '<div class="row">';
	echo '<div class="col-sm-12">';
		echo '<div class="card">';
			echo '<div class="header">Social Plugins Settings</div>';
			echo '<div class="content table-responsive">';
			
				echo '<form action="" method="post">';
					
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Social Plugins</strong>';
								echo '<p class="setting-description">Enables / disables the social plugins.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="enabled" value="1" '.($cfg['enabled'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="enabled" value="0" '.(!$cfg['enabled'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
					echo '</table>';
					
					// FACEBOOK
					echo '<h4>Facebook</h4>';
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Facebook Register / Login</strong>';
								echo '<p class="setting-description">Enables / disables the Facebook registration / login social plugin.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="facebook_enabled" value="1" '.($cfg['provider']['facebook']['enabled'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="facebook_enabled" value="0" '.(!$cfg['provider']['facebook']['enabled'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Facebook App Id</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="facebook_id" value="'.$cfg['provider']['facebook']['id'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Facebook App Secret</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="facebook_secret" value="'.$cfg['provider']['facebook']['secret'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Facebook App Scope</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="facebook_scope" value="'.$cfg['provider']['facebook']['scope'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Setup Information</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
							
								echo '<p>Add Domain:</p>';
								echo '<ul>';
									echo '<li>'.__BASE_URL__.' (Match Prefix, Prefetch None)</li>';
								echo '</ul>';
								
							echo '</td>';
						echo '</tr>';
						
					echo '</table>';
					
					// GOOGLE
					echo '<h4>Google</h4>';
					echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Google Register / Login</strong>';
								echo '<p class="setting-description">Enables / disables the Google registration / login social plugin.</p>';
							echo '</td>';
							echo '<td>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="google_enabled" value="1" '.($cfg['provider']['google']['enabled'] ? 'checked' : null).'>';
										echo 'Enabled';
									echo '</label>';
								echo '</div>';
								echo '<div class="radio">';
									echo '<label>';
										echo '<input type="radio" name="google_enabled" value="0" '.(!$cfg['provider']['google']['enabled'] ? 'checked' : null).'>';
										echo 'Disabled';
									echo '</label>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Google App Id</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="google_id" value="'.$cfg['provider']['google']['id'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Google App Secret</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="google_secret" value="'.$cfg['provider']['google']['secret'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Google App Scope</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" class="form-control" name="google_scope" value="'.$cfg['provider']['google']['scope'].'">';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo '<strong>Setup Information</strong>';
								echo '<p class="setting-description"></p>';
							echo '</td>';
							echo '<td>';
							
								echo '<p>Authorized JavaScript origins:</p>';
								echo '<ul>';
									echo '<li>'.__BASE_URL__.'</li>';
								echo '</ul>';
								
								echo '<p>Authorized redirect URIs:</p>';
								echo '<ul>';
									echo '<li>'.__BASE_URL__.'login/google</li>';
									echo '<li>'.__BASE_URL__.'account/social/google/link</li>';
									echo '<li>'.__BASE_URL__.'social/login/google</li>';
									echo '<li>'.__BASE_URL__.'social/register/google</li>';
								echo '</ul>';
								
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