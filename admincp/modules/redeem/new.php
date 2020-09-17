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

// create new redeem code
if(check($_POST['new_submit'])) {
	try {
		
		$RedeemCode = new RedeemCode();
		$RedeemCode->setTitle($_POST['title']);
		$RedeemCode->setCode($_POST['code']);
		$RedeemCode->setCodeType($_POST['type']);
		$RedeemCode->setLimit($_POST['limit']);
		$RedeemCode->setExpiration($_POST['expiration']);
		$RedeemCode->setUser($_POST['account']);
		$RedeemCode->setReward($_POST['reward']);
		$RedeemCode->addRewardCode();
		
		redirect('redeem/list');
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-6 col-lg-6">';
		echo '<div class="card">';
			echo '<div class="header">New Redeem Code</div>';
			echo '<div class="content">';
				
			echo '<form action="" method="post">';
				echo '<div class="form-group">';
					echo '<label for="input_1">Title</label>';
					echo '<input type="text" class="form-control" id="input_1" name="title" required autofocus>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_2">Code</label>';
					echo '<input type="text" class="form-control" id="input_2" name="code" required>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_3">Type</label>';
					echo '<select class="form-control" id="input_3" name="type">';
						echo '<option value="regular">Regular (redeemable by everyone, once)</option>';
						echo '<option value="limited">Limited (redeemable by everyone, once, until limit reached)</option>';
						echo '<option value="account">Account (redeemable by a specific account, once)</option>';
					echo '</select>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_4">Limit</label>';
					echo '<input type="text" class="form-control" id="input_4" name="limit">';
					echo '<small class="form-text text-muted">* Only required when creating a limited type code.</small>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_5">Expiration Date</label>';
					echo '<input type="text" class="form-control" id="input_5" name="expiration" placeholder="yyyy-mm-dd hh-mm-ss">';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_6">Account Username</label>';
					echo '<input type="text" class="form-control" id="input_6" name="account">';
					echo '<small class="form-text text-muted">* Only required when creating an account type code.</small>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_7">Cash Reward</label>';
					echo '<input type="text" class="form-control" id="input_7" name="reward" required>';
				echo '</div>';
				
				echo '<button type="submit" class="btn btn-info" name="new_submit" value="ok">Create</button>';
				echo ' <a href="'.admincp_base('redeem/list').'" class="btn btn-danger">Cancel</a>';
				
			echo '</form>';
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';