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
	
	if(!check($_GET['username'])) throw new Exception('The provided username is not valid.');
	
	$Account = new Account();
	$Account->setUsername($_GET['username']);
	$accountData = $Account->getAccountData();
	if(!is_array($accountData)) throw new Exception('Account data could not be loaded.');
	
	echo '<h1 class="text-info">'.$accountData['accountName'].'</h1>';
	echo '<hr>';
	echo '<div class="row">';
		echo '<div class="col-sm-12 col-md-8 col-lg-6">';
			
			// change password process
			if(check($_POST['account_submit'])) {
				try {
					
					if(!check($_POST['account_password'], $_POST['account_username'], $_POST['account_userid'])) throw new Exception('Please complete all fields.');
					
					$AccountPassword = new AccountPassword();
					
					if(!check($_POST['account_password_email'])) {
						$AccountPassword->disableNewPasswordChangeEmail();
					}
					
					$AccountPassword->setUserid($_POST['account_userid']);
					$AccountPassword->setUsername($_POST['account_username']);
					$AccountPassword->setNewPassword($_POST['account_password']);
					$AccountPassword->manualPasswordChange();
					
					redirect('account/profile/username/'.$_POST['account_username']);
					
				} catch(Exception $ex) {
					message('error', $ex->getMessage());
				}
			}
			
			echo '<div class="card">';
				echo '<div class="header">Change Password</div>';
				echo '<div class="content">';
					
					echo '<form action="'.admincp_base('account/password/username/'.$accountData['accountName']).'" method="post">';
						echo '<input type="hidden" name="account_username" value="'.$accountData['accountName'].'">';
						echo '<input type="hidden" name="account_userid" value="'.$accountData['_id'].'">';
						echo '<div class="form-group">';
							echo '<label for="input_1">New Password</label>';
							echo '<input type="password" class="form-control" id="input_1" name="account_password" required autofocus>';
						echo '</div>';
						echo '<div class="checkbox">';
							echo '<label>';
								echo '<input type="checkbox" name="account_password_email" value="1" checked> Send password change email';
							echo '</label>';
						echo '</div>';
						echo '<button type="submit" class="btn btn-info" name="account_submit" value="ok">Change</button> ';
						echo '<a href="'.admincp_base('account/profile/username/'.$accountData['accountName']).'" class="btn btn-danger">Cancel</a>';
					echo '</form>';
					
				echo '</div>';
			echo '</div>';
		
		echo '</div>';
	echo '</div>';
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}