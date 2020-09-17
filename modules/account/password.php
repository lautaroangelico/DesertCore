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

// module configs
$cfg = loadModuleConfig('account.password');
if(!is_array($cfg)) throw new Exception(lang('error_66',true));

// form submit
if(check($_POST['webenginePassword_submit'])) {
	try {
		if($_POST['webenginePassword_new'] != $_POST['webenginePassword_newconfirm']) throw new Exception(lang('error_8',true));
		
		$AccountPassword = new AccountPassword();
		$AccountPassword->setUserid($_SESSION['userid']);
		$AccountPassword->setUsername($_SESSION['username']);
		$AccountPassword->setPassword($_POST['webenginePassword_current']);
		$AccountPassword->setNewPassword($_POST['webenginePassword_new']);
		$AccountPassword->changePassword();
		
		if($cfg['require_verification']) {
			// verification required
			message('success', lang('success_3',true));
		} else {
			// password changed
			message('success', lang('success_2',true));
		}
		
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// form
echo '<div class="col-8 offset-2" style="margin-top:30px;">';
	echo '<form class="form-horizontal" action="" method="post">';
		echo '<div class="form-group">';
			echo '<label for="webenginePassword" class="col-12 control-label">'.lang('changepassword_txt_1',true).'</label>';
			echo '<div class="col-12">';
				echo '<input type="password" class="form-control" id="webenginePassword" name="webenginePassword_current">';
			echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
			echo '<label for="webenginePassword" class="col-12 control-label">'.lang('changepassword_txt_2',true).'</label>';
			echo '<div class="col-12">';
				echo '<input type="password" class="form-control" id="webenginePassword" name="webenginePassword_new">';
			echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
			echo '<label for="webenginePassword" class="col-12 control-label">'.lang('changepassword_txt_3',true).'</label>';
			echo '<div class="col-12">';
				echo '<input type="password" class="form-control" id="webenginePassword" name="webenginePassword_newconfirm">';
			echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
			echo '<div class="col-12">';
				echo '<button type="submit" name="webenginePassword_submit" value="submit" class="btn btn-primary">'.lang('changepassword_txt_4',true).'</button>';
			echo '</div>';
		echo '</div>';
	echo '</form>';
echo '</div>';