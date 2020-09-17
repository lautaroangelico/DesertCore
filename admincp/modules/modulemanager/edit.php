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
	
	// save changes
	if(check($_POST['edit_submit'])) {
		try {
			
			$ModuleManager = new ModuleManager();
			$ModuleManager->setId($_GET['id']);
			if(check($_POST['parent'])) $ModuleManager->setParent($_POST['parent']);
			if(check($_POST['file'])) $ModuleManager->setFile($_POST['file']);
			if(check($_POST['title'])) $ModuleManager->setTitle($_POST['title']);
			if(check($_POST['access'])) $ModuleManager->setAccess($_POST['access']);
			if(check($_POST['type'])) $ModuleManager->setType($_POST['type']);
			if(check($_POST['template'])) $ModuleManager->setTemplate($_POST['template']);
			if(check($_POST['sidebar'])) $ModuleManager->setSidebar($_POST['sidebar']);
			if(check($_POST['plugin'])) $ModuleManager->setPlugin($_POST['plugin']);
			if(check($_POST['config_file'])) $ModuleManager->setConfigFile($_POST['config_file']);
			if(check($_POST['config_module'])) $ModuleManager->setConfigModule($_POST['config_module']);
			if(check($_POST['status'])) $ModuleManager->setStatus($_POST['status']);
			$ModuleManager->editModule();
			
			redirect('modulemanager/edit/id/'.$_GET['id']);
			
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	$ModuleManager = new ModuleManager();
	$ModuleManager->setId($_GET['id']);
	
	// module data
	$moduleData = $ModuleManager->loadModuleData();
	
	$moduleExt = $moduleData['type'] == 'dynamic' ? '.php' : '.html';
	$modulePath = $moduleData['parent'] . '/' . $moduleData['file'] . $moduleExt;
	
	echo '<div class="row">';
		echo '<div class="col-sm-12 col-md-12 col-lg-12">';
			echo '<div class="card">';
				echo '<div class="header">Edit Module Data: <span class="text-info">'.$modulePath.'</span><a href="'.admincp_base('modulemanager/editor/id/'.$moduleData['id']).'" class="btn btn-warning pull-right">Edit File</a></div>';
				echo '<div class="content">';
					
				echo '<form action="" method="post">';
					echo '<div class="row">';
						echo '<div class="col-sm-12 col-md-6 col-lg-6">';
							echo '<div class="form-group">';
								echo '<label for="input_0">Id</label>';
								echo '<input type="text" class="form-control" id="input_0" name="id" value="'.$moduleData['id'].'" disabled="disabled">';
							echo '</div>';
							echo '<div class="form-group">';
								echo '<label for="input_1">Parent / Path</label>';
								echo '<input type="text" class="form-control" id="input_1" name="parent" value="'.$moduleData['parent'].'" autofocus>';
							echo '</div>';
							echo '<div class="form-group">';
								echo '<label for="input_2">File</label>';
								echo '<input type="text" class="form-control" id="input_2" name="file" value="'.$moduleData['file'].'" required>';
							echo '</div>';
							echo '<div class="form-group">';
								echo '<label for="input_3">Title / Phrase</label>';
								echo '<input type="text" class="form-control" id="input_3" name="title" value="'.$moduleData['title'].'">';
							echo '</div>';
							echo '<div class="form-group">';
								echo '<label for="input_4">Access</label>';
								echo '<select class="form-control" id="input_4" name="access">';
									echo '<option value="all" '.($moduleData['access'] == 'all' ? 'selected' : null).'>All</option>';
									echo '<option value="user" '.($moduleData['access'] == 'user' ? 'selected' : null).'>User</option>';
									echo '<option value="guest" '.($moduleData['access'] == 'guest' ? 'selected' : null).'>Guest</option>';
								echo '</select>';
							echo '</div>';
							echo '<div class="form-group">';
								echo '<label for="input_5">Type</label>';
								echo '<select class="form-control" id="input_5" name="type">';
									echo '<option value="static" '.($moduleData['type'] == 'static' ? 'selected' : null).'>Static (HTML)</option>';
									echo '<option value="dynamic" '.($moduleData['type'] == 'dynamic' ? 'selected' : null).'>Dynamic (PHP)</option>';
								echo '</select>';
							echo '</div>';
						echo '</div>';
						
						echo '<div class="col-sm-12 col-md-6 col-lg-6">';
							echo '<div class="form-group">';
								echo '<label for="input_6">Template</label>';
								echo '<input type="text" class="form-control" id="input_6" name="template" value="'.$moduleData['template'].'">';
							echo '</div>';
							echo '<div class="form-group">';
								echo '<label for="input_7">Sidebar</label>';
								echo '<select class="form-control" id="input_7" name="sidebar">';
									echo '<option value="1" '.($moduleData['sidebar'] == 1 ? 'selected' : null).'>Enabled</option>';
									echo '<option value="0" '.($moduleData['sidebar'] == 0 ? 'selected' : null).'>Disabled</option>';
								echo '</select>';
							echo '</div>';
							echo '<div class="form-group">';
								echo '<label for="input_8">Plugin</label>';
								echo '<input type="text" class="form-control" id="input_8" name="plugin" value="'.$moduleData['plugin'].'" disabled="disabled">';
							echo '</div>';
							echo '<div class="form-group">';
								echo '<label for="input_9">Config File</label>';
								echo '<input type="text" class="form-control" id="input_9" name="config_file" value="'.$moduleData['config_file'].'">';
							echo '</div>';
							echo '<div class="form-group">';
								echo '<label for="input_10">Config Module</label>';
								echo '<input type="text" class="form-control" id="input_10" name="config_module" value="'.$moduleData['config_module'].'">';
							echo '</div>';
							echo '<div class="form-group">';
								echo '<label for="input_11">Status</label>';
								echo '<select class="form-control" id="input_11" name="status">';
									echo '<option value="1" '.($moduleData['status'] == 1 ? 'selected' : null).'>Enabled</option>';
									echo '<option value="0" '.($moduleData['status'] == 0 ? 'selected' : null).'>Disabled</option>';
								echo '</select>';
							echo '</div>';
						echo '</div>';
						
					echo '</div>';
					
					echo '<button type="submit" class="btn btn-info" name="edit_submit" value="ok">Save Changes</button>';
					echo ' <a href="'.admincp_base('modulemanager/list').'" class="btn btn-danger">Cancel</a>';
					
				echo '</form>';
					
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}