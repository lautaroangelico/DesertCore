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
	
	if(!check($_GET['name'])) throw new Exception('Character name not provided.');
	
	$Player = new Player();
	$Player->setPlayer($_GET['name']);
	$characterData = $Player->getPlayerInformation();
	if(!is_array($characterData)) throw new Exception('Character data could not be loaded.');
	
	$Account = new Account();
	$Account->setUserid($characterData['accountId']);
	$accountData = $Account->getAccountData();
	if(!is_array($accountData)) throw new Exception('Account data could not be loaded.');
	
	$playerClassData = custom('classType');
	$playerZodiacData = custom('zodiacSign');
	
	echo '<h1 class="text-info">'.$characterData['name'].'</h1>';
	echo '<hr>';
	
	if(check($_POST['submit_edit'])) {
		try {
			
			// filters
			if(!check($_POST['name'])) throw new Exception(lang('error_4'));
			if(!check($_POST['accountId'])) throw new Exception(lang('error_4'));
			if(!check($_POST['level'])) throw new Exception(lang('error_4'));
			if(!check($_POST['exp'])) throw new Exception(lang('error_4'));
			if(!check($_POST['classType'])) throw new Exception(lang('error_4'));
			if(!check($_POST['zodiac'])) throw new Exception(lang('error_4'));

			if(!Validator::AlphaNumeric($_POST['name'])) throw new Exception('The character\'s name must be alphanumeric.');
			if(!Validator::UnsignedNumber($_POST['accountId'])) throw new Exception('The character\'s account id must be numeric.');
			if(!Validator::UnsignedNumber($_POST['level'])) throw new Exception('The character\'s level must be numeric.');
			if(!Validator::UnsignedNumber($_POST['exp'])) throw new Exception('The character\'s exp must be numeric.');
			if(!Validator::UnsignedNumber($_POST['classType'])) throw new Exception('The character\'s class must be numeric.');
			if(!Validator::UnsignedNumber($_POST['zodiac'])) throw new Exception('The character\'s zodiac must be numeric.');
			
			$PlayerEdit = new Player();
			$PlayerEdit->setPlayer($_GET['name']);
			$PlayerEditData = $PlayerEdit->getPlayerInformation();
			if(!is_array($PlayerEditData)) throw new Exception('Character data could not be loaded.');
			
			if($PlayerEditData['name'] != $_POST['name']) {
				if($Player->playerNameExists($_POST['name'])) throw new Exception('A character already exists with the same name, please choose a new one.');
				$PlayerEdit->editColumn('name', $_POST['name']);
			}
			
			if($PlayerEditData['accountId'] != $_POST['accountId']) $PlayerEdit->editColumn('accountId', (int) $_POST['accountId']);
			if($PlayerEditData['level'] != $_POST['level']) $PlayerEdit->editColumn('level', (int) $_POST['level']);
			if($PlayerEditData['exp'] != $_POST['exp']) $PlayerEdit->editColumn('exp', (int) $_POST['exp']);
			if($PlayerEditData['classType'] != $_POST['classType']) $PlayerEdit->editColumn('classType', (int) $_POST['classType']);
			if($PlayerEditData['zodiac'] != $_POST['zodiac']) $PlayerEdit->editColumn('zodiac', (int) $_POST['zodiac']);
			
			$PlayerEdit->saveEdits();
			redirect('character/profile/name/'.$characterData['name']);
			
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<form action="" method="post">';
		echo '<div class="row">';
			// general
			echo '<div class="col-sm-6 col-md-6 col-lg-6">';
			
				echo '<div class="card">';
					echo '<div class="header">Edit Character</div>';
					echo '<div class="content table-responsive">';
						
						echo '<div class="row">';
							echo '<div class="col-sm-6 col-md-6 col-lg-6">';
								echo '<div class="form-group">';
									echo '<label for="input_3">Name</label>';
									echo '<input type="text" class="form-control" id="input_3" name="name" value="'.$characterData['name'].'">';
								echo '</div>';
							echo '</div>';
							echo '<div class="col-sm-6 col-md-6 col-lg-6">';
								echo '<div class="form-group">';
									echo '<label for="input_4">Account Id ('.$accountData['accountName'].')</label>';
									echo '<input type="text" class="form-control" id="input_4" name="accountId" value="'.$characterData['accountId'].'">';
								echo '</div>';
							echo '</div>';
						echo '</div>';
						
						echo '<div class="row">';
							echo '<div class="col-sm-6 col-md-6 col-lg-6">';
								echo '<div class="form-group">';
									echo '<label for="input_1">Level</label>';
									echo '<input type="text" class="form-control" id="input_1" name="level" value="'.$characterData['level'].'">';
								echo '</div>';
							echo '</div>';
							echo '<div class="col-sm-6 col-md-6 col-lg-6">';
								echo '<div class="form-group">';
									echo '<label for="input_1">Experience</label>';
									echo '<input type="text" class="form-control" id="input_1" name="exp" value="'.$characterData['exp'].'">';
								echo '</div>';
							echo '</div>';
						echo '</div>';
						
						echo '<div class="row">';
							echo '<div class="col-sm-6 col-md-6 col-lg-6">';
								echo '<div class="form-group">';
									echo '<label for="input_2">Class</label>';
									echo '<select class="form-control" id="input_2" name="classType">';
										foreach($playerClassData as $classCode => $classData) {
											if($characterData['classType'] == $classCode) {
												echo '<option value="'.$classCode.'" selected>'.$classData['name'].'</option>';
											} else {
												echo '<option value="'.$classCode.'">'.$classData['name'].'</option>';
											}
										}
									echo '</select>';
								echo '</div>';
							echo '</div>';
							echo '<div class="col-sm-6 col-md-6 col-lg-6">';
								echo '<div class="form-group">';
									echo '<label for="input_2">Zodiac</label>';
									echo '<select class="form-control" id="input_2" name="zodiac">';
										foreach($playerZodiacData as $zodiacCode => $zodiacData) {
											if($characterData['zodiac'] == $zodiacCode) {
												echo '<option value="'.$zodiacCode.'" selected>'.$zodiacData['name'].'</option>';
											} else {
												echo '<option value="'.$zodiacCode.'">'.$zodiacData['name'].'</option>';
											}
										}
									echo '</select>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
						
						
					echo '</div>';
				echo '</div>';
			
			echo '</div>';
			
		echo '</div>';
		
		echo '<button type="submit" name="submit_edit" value="ok" class="btn btn-primary">Save Changes</button> ';
		echo '<a href="'.admincp_base('character/profile/name/'.$characterData['name']).'" class="btn btn-danger">Cancel</a>';
	
	echo '</form>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}