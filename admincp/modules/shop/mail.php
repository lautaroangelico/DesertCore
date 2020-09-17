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

// mail item
if(check($_POST['mail_submit'])) {
	try {
		
		$Account = new Account();
		$Account->setUsername($_POST['mail_username']);
		$accountData = $Account->getAccountData();
		if(!is_array($accountData)) throw new Exception('Account data could not be loaded.');
		
		if(!check($_POST['mail_bypass_item_check'])) {
			$itemData = desertCoreItemDatabase($_POST['mail_item']);
			if(!is_array($itemData)) throw new Exception('The provided item id is not valid.');
		}
		
		$ItemMail = new ItemMail();
		$ItemMail->setAccountId($accountData['_id']);
		if(check($_POST['mail_sender'])) $ItemMail->setSenderName($_POST['mail_sender']);
		if(check($_POST['mail_subject'])) $ItemMail->setMailSubject($_POST['mail_subject']);
		if(check($_POST['mail_message'])) $ItemMail->setMailMessage($_POST['mail_message']);
		$ItemMail->setItemId($_POST['mail_item']);
		$ItemMail->setEnchantLevel($_POST['mail_enchantment']);
		$ItemMail->setItemCount($_POST['mail_count']);
		$ItemMail->mailItem();
		
		message('success', 'Item has been successfully mailed!');
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

$cfg = loadConfig('shop');
if(!is_array($cfg)) throw new Exception('Could not load configuration file.');

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-6 col-lg-6">';
		echo '<div class="card">';
			echo '<div class="header">Mail Items</div>';
			echo '<div class="content">';
				
			echo '<form action="" method="post">';
				echo '<div class="form-group">';
					echo '<label for="input_0">Username</label>';
					echo '<input type="text" class="form-control" id="input_0" name="mail_username" required autofocus>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_1">Item Id</label>';
					echo '<input type="text" class="form-control" id="input_1" name="mail_item" required>';
					echo '<div class="checkbox">';
						echo '<label><input type="checkbox" name="mail_bypass_item_check" value="1"> Bypass item id validation (not recommended)</label>';
					echo '</div>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_2">Item Count</label>';
					echo '<input type="text" class="form-control" id="input_2" name="mail_count" value="1" required>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_3">Enhancement Level</label>';
					echo '<select name="mail_enchantment" class="form-control">';
								echo '<option value="0" selected>+0</option>';
								echo '<option value="1">+1</option>';
								echo '<option value="2">+2</option>';
								echo '<option value="3">+3</option>';
								echo '<option value="4">+4</option>';
								echo '<option value="5">+5</option>';
								echo '<option value="6">+6</option>';
								echo '<option value="7">+7</option>';
								echo '<option value="8">+8</option>';
								echo '<option value="9">+9</option>';
								echo '<option value="10">+10</option>';
								echo '<option value="11">+11</option>';
								echo '<option value="12">+12</option>';
								echo '<option value="13">+13</option>';
								echo '<option value="14">+14</option>';
								echo '<option value="15">+15</option>';
								echo '<option value="16">PRI</option>';
								echo '<option value="17">DUO</option>';
								echo '<option value="18">TRI</option>';
								echo '<option value="19">TET</option>';
								echo '<option value="20">PEN</option>';
							echo '</select>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_4">Mail Sender</label>';
					echo '<input type="text" class="form-control" id="input_4" name="mail_sender" value="'.$cfg['mail_sender_name'].'">';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_5">Mail Subject</label>';
					echo '<input type="text" class="form-control" id="input_5" name="mail_subject" value="'.$cfg['mail_subject'].'">';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_6">Mail Message</label>';
					echo '<input type="text" class="form-control" id="input_6" name="mail_message" value="'.$cfg['mail_message'].'">';
				echo '</div>';
				
				echo '<button type="submit" class="btn btn-info" name="mail_submit" value="ok">Mail Item</button>';
				
			echo '</form>';
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';