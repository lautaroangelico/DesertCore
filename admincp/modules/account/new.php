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

if(check($_POST['account_submit'])) {
	try {
		
		if(!check($_POST['account_username'], $_POST['account_password'], $_POST['account_email'])) throw new Exception('Please complete all the fields.');
		
		$Registration = new AccountRegister();
		
		if(check($_POST['account_disable_verification'])) {
			$Registration->disableVerification();
		}
		
		if(!check($_POST['account_welcome_email'])) {
			$Registration->disableWelcomeEmail();
		}
		
		$Registration->setUsername($_POST['account_username']);
		$Registration->setPassword($_POST['account_password']);
		$Registration->setEmail($_POST['account_email']);
		$Registration->registerAccount();
		
		message('success', 'Account successfully created!');
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-8 col-lg-6">';
		echo '<div class="card">';
			echo '<div class="header">Create New Account</div>';
			echo '<div class="content">';
				
			echo '<form action="" method="post">';
				echo '<div class="form-group">';
					echo '<label for="input_1">Username</label>';
					echo '<input type="text" class="form-control" id="input_1" name="account_username" required>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_2">Password</label>';
					echo '<input type="password" class="form-control" id="input_2" name="account_password" required>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_3">Email</label>';
					echo '<input type="email" class="form-control" id="input_3" name="account_email" required>';
				echo '</div>';
				echo '<div class="checkbox">';
					echo '<label>';
						echo '<input type="checkbox" name="account_disable_verification" value="1" checked> Disable email address verification';
					echo '</label>';
				echo '</div>';
				echo '<div class="checkbox">';
					echo '<label>';
						echo '<input type="checkbox" name="account_welcome_email" value="1" checked> Send welcome email';
					echo '</label>';
				echo '</div>';
				echo '<button type="submit" class="btn btn-info" name="account_submit" value="ok">Create</button>';
			echo '</form>';
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';