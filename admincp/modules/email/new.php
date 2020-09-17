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

if(check($_POST['template_submit'])) {
	try {
		
		if(!check($_POST['template'], $_POST['title'])) throw new Exception(lang('error_4'));
		
		$Email = new Email();
		$Email->addTemplate($_POST['template'], $_POST['title']);
		
		redirect('email/templates');
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-8 col-lg-6">';
		echo '<div class="card">';
			echo '<div class="header">Add New Template</div>';
			echo '<div class="content">';
				
			echo '<form action="" method="post">';
				echo '<div class="form-group">';
					echo '<label for="input_1">Template File Name</label>';
					echo '<input type="text" class="form-control" id="input_1" name="template" maxlength="100" required autofocus>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_2">Title / Phrase</label>';
					echo '<input type="text" class="form-control" id="input_2" name="title" maxlength="50" required>';
				echo '</div>';
				echo '<button type="submit" class="btn btn-primary" name="template_submit" value="ok">Add Template</button>';
			echo '</form>';
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';