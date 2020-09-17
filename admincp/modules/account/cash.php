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
	
	if(!check($_GET['username'])) throw new Exception('Username not provided.');
	if(!check($_GET['action'])) throw new Exception('Action not provided.');
	if(!in_array($_GET['action'], array('add', 'subtract'))) throw new Exception('Invalid action.');
	
	$Account = new Account();
	$Account->setUsername($_GET['username']);
	$accountData = $Account->getAccountData();
	if(!is_array($accountData)) throw new Exception('Account data could not be loaded.');
	$Account->setUserid($accountData['_id']);
	
	if(check($_POST['cash_submit'])) {
		try {
			
			if(!check($_POST['cash_value'])) throw new Exception('Invalid cash value.');
			if(!Validator::UnsignedNumber($_POST['cash_value'])) throw new Exception('Invalid cash value.');
			if(!check($_POST['cash_action'])) throw new Exception('Invalid action.');
			if(!in_array($_POST['cash_action'], array('add', 'subtract'))) throw new Exception('Invalid action.');
			
			if($_POST['cash_action'] == 'add') {
				$result = $Account->addCash($_POST['cash_value']);
			} else {
				$result = $Account->subtractCash($_POST['cash_value']);
			}
			
			if(!$result) throw new Exception('There was an error completing the action.');
			redirect('account/profile/username/'.$accountData['accountName']);
			
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<h1 class="text-info">'.$accountData['accountName'].' ('.number_format($accountData['cash']).' cash)</h1>';
	echo '<hr>';
	echo '<div class="row">';
		echo '<div class="col-sm-12 col-md-8 col-lg-6">';
		
			echo '<div class="card">';
				echo '<div class="header">Manage Account Cash</div>';
				echo '<div class="content">';
					
					echo '<form action="" method="post">';
						echo '<div class="row">';
							echo '<div class="col-sm-12 col-md-4 col-lg-3">';
								echo '<div class="form-group">';
									echo '<label for="input_1">Action</label>';
									echo '<select class="form-control" id="input_1" name="cash_action">';
										echo '<option value="add" '.($_GET['action'] == 'add' ? 'selected' : null).'>Add</option>';
										echo '<option value="subtract" '.($_GET['action'] == 'subtract' ? 'selected' : null).'>Subtract</option>';
									echo '</select>';
								echo '</div>';
							echo '</div>';
							echo '<div class="col-sm-12 col-md-8 col-lg-9">';
								echo '<div class="form-group">';
									echo '<label for="input_2">Cash</label>';
									echo '<input type="text" class="form-control" id="input_2" name="cash_value" autofocus>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
						echo '<button type="submit" class="btn btn-info" name="cash_submit" value="ok">Complete</button> ';
						echo '<a href="'.admincp_base('account/profile/username/'.$accountData['accountName']).'" class="btn btn-danger">Cancel</a>';
					echo '</form>';
					
				echo '</div>';
			echo '</div>';
		
		echo '</div>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}