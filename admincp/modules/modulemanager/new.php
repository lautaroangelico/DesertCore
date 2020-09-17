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

// create new module
if(check($_POST['new_submit'])) {
	try {
		
		$ModuleManager = new ModuleManager();
		if(check($_POST['parent'])) $ModuleManager->setParent($_POST['parent']);
		if(check($_POST['file'])) $ModuleManager->setFile($_POST['file']);
		if(check($_POST['title'])) $ModuleManager->setTitle($_POST['title']);
		if(check($_POST['access'])) $ModuleManager->setAccess($_POST['access']);
		if(check($_POST['type'])) $ModuleManager->setType($_POST['type']);
		if(check($_POST['template'])) $ModuleManager->setTemplate($_POST['template']);
		if(check($_POST['sidebar'])) $ModuleManager->setSidebar($_POST['sidebar']);
		if(check($_POST['status'])) $ModuleManager->setStatus($_POST['status']);
		$ModuleManager->createModule();
		
		redirect('modulemanager/list');
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-12 col-lg-12">';
		echo '<div class="card">';
			echo '<div class="header">New Module</div>';
			echo '<div class="content">';
				
			echo '<form action="" method="post">';
				echo '<div class="row">';
					echo '<div class="col-sm-12 col-md-6 col-lg-6">';
						echo '<div class="form-group">';
							echo '<label for="input_1">Parent / Path</label>';
							echo '<input type="text" class="form-control" id="input_1" name="parent" autofocus>';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label for="input_2">File</label>';
							echo '<input type="text" class="form-control" id="input_2" name="file" required>';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label for="input_3">Title / Phrase</label>';
							echo '<input type="text" class="form-control" id="input_3" name="title">';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label for="input_4">Access</label>';
							echo '<select class="form-control" id="input_4" name="access">';
								echo '<option value="all">All</option>';
								echo '<option value="user">User</option>';
								echo '<option value="guest">Guest</option>';
							echo '</select>';
						echo '</div>';
					echo '</div>';
					
					echo '<div class="col-sm-12 col-md-6 col-lg-6">';
						echo '<div class="form-group">';
							echo '<label for="input_5">Type</label>';
							echo '<select class="form-control" id="input_5" name="type">';
								echo '<option value="static">Static (HTML)</option>';
								echo '<option value="dynamic">Dynamic (PHP)</option>';
							echo '</select>';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label for="input_6">Template</label>';
							echo '<input type="text" class="form-control" id="input_6" name="template">';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label for="input_7">Sidebar</label>';
							echo '<select class="form-control" id="input_7" name="sidebar">';
								echo '<option value="1">Enabled</option>';
								echo '<option value="0">Disabled</option>';
							echo '</select>';
						echo '</div>';
					echo '</div>';
					
				echo '</div>';
				
				echo '<button type="submit" class="btn btn-info" name="new_submit" value="ok">Create Module</button>';
				echo ' <a href="'.admincp_base('modulemanager/list').'" class="btn btn-danger">Cancel</a>';
				
			echo '</form>';
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';