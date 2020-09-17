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

if(!check($_GET['id'])) {
	redirect('cron/manager');
}

if(check($_POST['cron_submit'])) {
	try {
		
		if(!check($_POST['cron_name'], $_POST['cron_file'], $_POST['cron_repeat'])) throw new Exception('Please complete all the fields.');
		
		$Cron = new Cron();
		$Cron->setId($_POST['cron_id']);
		$Cron->setName($_POST['cron_name']);
		if(check($_POST['cron_crescription'])) $Cron->setDescription($_POST['cron_crescription']);
		$Cron->setFile($_POST['cron_file']);
		$Cron->setRepeat($_POST['cron_repeat']);
		$Cron->editCron();
		
		message('success', 'The cron task has been successfully updated!');
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

$Cron = new Cron();
$Cron->setId($_GET['id']);
$cronData = $Cron->getCronData();
if(!is_array($cronData)) redirect('cron/manager');
$cronFileList = $Cron->getCronFileList();
$commonCronTimes = $Cron->getCommonCronTimes();

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-8 col-lg-6">';
		echo '<div class="card">';
			echo '<div class="header">Edit Cron</div>';
			echo '<div class="content">';
				
			echo '<form action="" method="post">';
				echo '<input type="hidden" name="cron_id" value="'.$cronData['cron_id'].'"/>';
				echo '<div class="form-group">';
					echo '<label for="input_1">Name</label>';
					echo '<input type="text" class="form-control" id="input_1" name="cron_name" maxlength="100" value="'.$cronData['cron_name'].'" required autofocus>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_2">Description</label>';
					echo '<input type="text" class="form-control" id="input_2" name="cron_crescription" maxlength="100" value="'.$cronData['cron_description'].'">';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_3">File</label>';
					echo '<select class="form-control" id="input_3" name="cron_file" required>';
						if(is_array($cronFileList)) {
							foreach($cronFileList as $cronFile) {
								if(check($cronData['cron_file'])) {
									$selectedFile = $cronData['cron_file'] == $cronFile ? 'selected' : '';
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
								if(check($cronData['cron_repeat'])) {
									$selectedRepeat = $cronData['cron_repeat'] == $cronRepeatSec ? 'selected' : '';
								}
								echo '<option value="'.$cronRepeatSec.'" '.$selectedRepeat.'>'.$cronRepeatDesc.'</option>';
							}
						}
					echo '</select>';
				echo '</div>';
				echo '<button type="submit" class="btn btn-warning" name="cron_submit" value="ok">Edit Cron</button> ';
				echo '<a href="'.admincp_base('cron/manager').'" class="btn btn-danger">Cancel</a>';
			echo '</form>';
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';