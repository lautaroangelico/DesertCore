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

if(check($_POST['cron_submit'])) {
	try {
		
		if(!check($_POST['cron_name'], $_POST['cron_file'], $_POST['cron_repeat'])) throw new Exception('Please complete all the fields.');
		
		$Cron = new Cron();
		$Cron->setName($_POST['cron_name']);
		if(check($_POST['cron_crescription'])) $Cron->setDescription($_POST['cron_crescription']);
		$Cron->setFile($_POST['cron_file']);
		$Cron->setRepeat($_POST['cron_repeat']);
		$Cron->addCron();
		
		redirect('cron/manager');
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

$Cron = new Cron();
$cronFileList = $Cron->getCronFileList();
$commonCronTimes = $Cron->getCommonCronTimes();

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-8 col-lg-6">';
		echo '<div class="card">';
			echo '<div class="header">Add New Cron</div>';
			echo '<div class="content">';
				
			echo '<form action="" method="post">';
				echo '<div class="form-group">';
					echo '<label for="input_1">Name</label>';
					echo '<input type="text" class="form-control" id="input_1" name="cron_name" maxlength="100" '.(check($_POST['cron_name']) ? 'value="'.$_POST['cron_name'].'"' : '').' required autofocus>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_2">Description</label>';
					echo '<input type="text" class="form-control" id="input_2" name="cron_crescription" maxlength="100" '.(check($_POST['cron_crescription']) ? 'value="'.$_POST['cron_crescription'].'"' : '').'>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_3">File</label>';
					echo '<select class="form-control" id="input_3" name="cron_file" required>';
						if(is_array($cronFileList)) {
							foreach($cronFileList as $cronFile) {
								if(check($_POST['cron_file'])) {
									$selectedFile = $_POST['cron_file'] == $cronFile ? 'selected' : '';
								}
								echo '<option value="'.$cronFile.'" '.$selectedFile.'>'.$cronFile.'</option>';
							}
						} else {
							echo '<option value="none">none</option>';
						}
					echo '</select>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_4">Repeat Every</label>';
					echo '<select class="form-control" id="input_4" name="cron_repeat" required>';
						if(is_array($commonCronTimes)) {
							foreach($commonCronTimes as $cronRepeatSec => $cronRepeatDesc) {
								if(check($_POST['cron_repeat'])) {
									$selectedRepeat = $_POST['cron_repeat'] == $cronRepeatSec ? 'selected' : '';
								}
								echo '<option value="'.$cronRepeatSec.'" '.$selectedRepeat.'>'.$cronRepeatDesc.'</option>';
							}
						}
					echo '</select>';
				echo '</div>';
				echo '<button type="submit" class="btn btn-info" name="cron_submit" value="ok">Add Cron</button>';
			echo '</form>';
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';