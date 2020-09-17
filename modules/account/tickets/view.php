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
	
	// ticket object
	$Tickets = new Tickets();
	$Tickets->setId($_GET['id']);
	
	// load ticket data
	$ticketData = $Tickets->getTicketData();
	if(!is_array($ticketData)) throw new Exception('We could not find the ticket you requested.');
	if($ticketData['username'] != $_SESSION['username']) throw new Exception('We could not find the ticket you requested.');
	
	// ticket reply
	if($ticketData['closed'] != 1) {
		if(check($_POST['reply_submit'])) {
			try {
				$TicketReply = new Tickets();
				$TicketReply->setId($ticketData['id']);
				$TicketReply->setUsername($ticketData['username']);
				$TicketReply->setMessage($_POST['reply_message']);
				$TicketReply->submitMessage();
				redirect('account/tickets/view/id/' . $ticketData['id']);
				
			} catch(Exception $ex) {
				message('error', $ex->getMessage());
			}
		}
	}
	
	// load messages
	$ticketMessages = $Tickets->getTicketMessages();
	if(!is_array($ticketMessages)) throw new Exception('We could not load your ticket messages, try again later.');
	
	// ticket status
	if($ticketData['closed'] == 1) {
		message('warning', lang('tickets_txt_12'));
	}
	
	// title
	echo '<h2>#'.$ticketData['id'].' '.$ticketData['subject'].'</h2>';
	echo '<hr>';
	
	// messages
	foreach($ticketMessages as $ticketMessage) {
		echo '<div class="card '.($ticketMessage['username'] != $_SESSION['username'] ? 'border border-danger' : null).'">';
			echo '<div class="card-body">';
				echo '<h5 class="card-title">'.$ticketMessage['username'].':</h5>';
				echo nl2br(htmlspecialchars($ticketMessage['message']));
			echo '</div>';
			echo '<div class="card-footer text-muted">';
				echo databaseTime($ticketMessage['create_date']);
			echo '</div>';
		echo '</div>';
		echo '<br />';
	}
	
	// reply form
	if($ticketData['closed'] != 1) {
		echo '<hr>';
		echo '<form action="" method="post">';
			echo '<div class="form-group">';
				echo '<label for="t2">'.lang('tickets_txt_2').'</label>';
				echo '<textarea class="form-control" id="t2" style="height:150px;" name="reply_message" required></textarea>';
			echo '</div>';
			echo '<button type="submit" name="reply_submit" value="submit" class="btn btn-primary">'.lang('tickets_txt_3').'</button> ';
			echo '<a href="'.Handler::websiteLink('account/tickets/list').'" class="btn btn-secondary">'.lang('tickets_txt_4').'</a>';
		echo '</form>';
	}
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}