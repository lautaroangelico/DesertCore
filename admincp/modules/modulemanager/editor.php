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

try {
	
	if(!check($_GET['id'])) redirect('modulemanager/list');
	
	$ModuleManager = new ModuleManager();
	$ModuleManager->setId($_GET['id']);
	
	// module data
	$moduleData = $ModuleManager->loadModuleData();
	
	// dynamic module editing
	if(!config('enable_dynamic_module_editor')) {
		if($moduleData['type'] == 'dynamic') throw new Exception('Editing dynamic (php) modules is disabled by default, to enable you must manually edit WebEngine\'s configuration file.');
	}
	
	// file
	$moduleExt = $moduleData['type'] == 'dynamic' ? '.php' : '.html';
	$modulePath = $moduleData['parent'] . '/' . $moduleData['file'] . $moduleExt;
	$filePath = __PATH_MODULES__ . $modulePath;
	$fileContent = file_get_contents($filePath);
	
	// check file permissions
	if(!is_writable($filePath)) throw new Exception('This file is not writable, please check the file permissions.');
	
	// editor mode
	//$editorMode = $moduleData['type'] == 'dynamic' ? '"application/x-httpd-php"' : 'mixedMode';

	// save changes
	if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['modulemanager_file_content'])) {
		$fileContent = $_POST['modulemanager_file_content'];
		
		$fp = fopen($filePath, 'w');
		fwrite($fp, $fileContent);
		fclose($fp);
	}

	echo '<div class="row">';
		echo '<div class="col-sm-12">';
			echo '<div class="card">';
				echo '<div class="header">Editing File: <span class="text-info">'.$modulePath.'</span></div>';
				echo '<div class="content">';
					
				echo '<form action="" method="post">';
					echo '<div class="form-group">';
						echo '<label for="modulemanager_file_content">File Contents</label>';
						echo '<textarea class="codemirror-textarea" name="modulemanager_file_content" id="modulemanager_file_content">'.$fileContent.'</textarea>';
					echo '</div>';
					echo '<button type="submit" class="btn btn-info" name="modulemanager_file_submit" value="ok">Save Changes</button>';
					echo ' <a href="'.admincp_base('modulemanager/list').'" class="btn btn-danger">Cancel</a>';
				echo '</form>';
					
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}