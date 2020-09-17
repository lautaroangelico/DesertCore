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

if(check($_POST['submit'])) {
	try {
		
		$Tickets = new Tickets();
		$Tickets->setSubject($_POST['ticket_subject']);
		$Tickets->setMessage($_POST['ticket_message']);
		$Tickets->setUsername($_SESSION['username']);
		$Tickets->submitTicket();
		$Tickets->redirectToTicket();
		
	} catch(Exception $ex) {
		message($ex->getMessage(), 'error');
	}
}

echo '<form action="" method="post">';
	echo '<div class="form-group">';
		echo '<label for="t1">'.lang('tickets_txt_1').'</label>';
		echo '<input type="text" class="form-control" id="t1" name="ticket_subject" required autofocus>';
	echo '</div>';
	echo '<div class="form-group">';
		echo '<label for="t2">'.lang('tickets_txt_2').'</label>';
		echo '<textarea class="form-control" id="t2" style="height:250px;" name="ticket_message" required></textarea>';
	echo '</div>';
	echo '<button type="submit" name="submit" value="submit" class="btn btn-primary">'.lang('tickets_txt_3').'</button>';
echo '</form>';