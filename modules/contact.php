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
$cfg = loadModuleConfig('contact');
if(!is_array($cfg)) throw new Exception(lang('error_66'));

if(check($_POST['submit'])) {
	try {
		if(!check($_POST['contact_email'])) throw new Exception(lang('error_41'));
		if(!check($_POST['contact_message'])) throw new Exception(lang('error_41'));
		if(!Validator::Email($_POST['contact_email'])) throw new Exception(lang('error_9'));
		if(!Validator::Length($_POST['contact_message'], $cfg['message_max_length'], $cfg['message_min_length'])) throw new Exception(lang('error_57'));
		
		$email = new Email();
		$email->setSubject($cfg['subject']);
		$email->setFrom($_POST['contact_email'], 'Contact Form');
		$email->setMessage($_POST['contact_message']);
		$email->addAddress($cfg['sendto']);
		$email->send();

		message('success', lang('success_22'));
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<form action="" method="post">';
	echo '<div class="form-group">';
		echo '<label for="contactInput1">'.lang('contactus_txt_1').'</label>';
		echo '<input type="email" class="form-control" id="contactInput1" name="contact_email" required autofocus>';
	echo '</div>';
	echo '<div class="form-group">';
		echo '<label for="contactInput2">'.lang('contactus_txt_2').'</label>';
		echo '<textarea class="form-control" id="contactInput2" style="height:250px;" name="contact_message" required></textarea>';
	echo '</div>';
	echo '<button type="submit" name="submit" value="submit" class="btn btn-primary">'.lang('contactus_txt_3').'</button>';
echo '</form>';