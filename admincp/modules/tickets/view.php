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
	
	$cfg = loadModuleConfig('account.tickets');
	if(!is_array($cfg)) throw new Exception('Could not load configuration file.');
	
	// ticket object
	$Tickets = new Tickets();
	$Tickets->setId($_GET['id']);
	
	// load ticket data
	$ticketData = $Tickets->getTicketData();
	if(!is_array($ticketData)) throw new Exception('We could not find the ticket you requested.');
	
	// ticket action
	if(check($_GET['action'])) {
		try {
			switch($_GET['action']) {
				case 'open':
					$Tickets->openTicket();
					redirect('tickets/view/id/' . $ticketData['id']);
					break;
				case 'close':
					$Tickets->closeTicket();
					redirect('tickets/view/id/' . $ticketData['id']);
					break;
				default:
					throw new Exception('Invalid action.');
			}
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	// ticket reply
	if(check($_POST['reply_submit'])) {
		try {
			$TicketReply = new Tickets();
			$TicketReply->setId($ticketData['id']);
			$TicketReply->setUsername($cfg['staff_reply_name']);
			$TicketReply->setMessage($_POST['reply_message']);
			$TicketReply->setStaffReply();
			$TicketReply->submitMessage();
			redirect('tickets/view/id/' . $ticketData['id']);
			
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	// load messages
	$ticketMessages = $Tickets->getTicketMessages();
	
	// title
	echo '<h2>#'.$ticketData['id'].' '.$ticketData['subject'].'</h2>';
	echo '<hr>';
	
	echo '<div class="row">';
		
		// messages
		echo '<div class="col-sm-12 col-md-8 col-lg-8">';
			
			if(is_array($ticketMessages)) {
				foreach($ticketMessages as $ticketMessage) {
					echo '<div class="card">';
						echo '<div class="header">'.$ticketMessage['username'].':</div>';
						echo '<div class="content table-responsive">';
							echo nl2br(htmlspecialchars($ticketMessage['message']));
						echo '</div>';
						echo '<div class="footer text-right text-muted">'.databaseTime($ticketMessage['create_date']).'</div>';
					echo '</div>';
				}
			} else {
				message('warning', 'No messages.');
			}
			
			echo '<hr>';
			echo '<form action="" method="post">';
				echo '<div class="form-group">';
					echo '<label for="t2">'.lang('tickets_txt_2').'</label>';
					echo '<textarea class="form-control" id="t2" style="height:150px;" name="reply_message" required></textarea>';
				echo '</div>';
				echo '<button type="submit" name="reply_submit" value="submit" class="btn btn-primary">'.lang('tickets_txt_3').'</button>';
			echo '</form>';
			
		echo '</div>';
		
		// ticket info
		echo '<div class="col-sm-12 col-md-4 col-lg-4">';
			
			// info
			echo '<div class="card">';
				echo '<div class="header">Ticket Information</div>';
				echo '<div class="content table-responsive table-full-width">';
					echo '<table class="table table-striped">';
						echo '<tr>';
							echo '<td>Ticket Id</td>';
							echo '<td>#'.$ticketData['id'].'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Opened By</td>';
							echo '<td>'.$ticketData['username'].'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Date Created</td>';
							echo '<td>'.databaseTime($ticketData['create_date']).'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Last Message By</td>';
							echo '<td>'.$ticketData['last_reply_by'].'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Last Message Date</td>';
							echo '<td>'.databaseTime($ticketData['last_reply_date']).'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Status</td>';
							echo '<td>'.($ticketData['closed'] == 1 ? '<span class="label label-danger">Closed</span>' : '<span class="label label-success">Open</span>').'</td>';
						echo '</tr>';
					echo '</table>';
					
					echo '<hr>';
					
					echo '<div class="text-center">';
						if($ticketData['closed'] == 1) {
							echo '<a href="'.admincp_base('tickets/view/id/' . $ticketData['id'] . '/action/open').'" class="btn btn-success">Re-open Ticket</a>';
						} else {
							echo '<a href="'.admincp_base('tickets/view/id/' . $ticketData['id'] . '/action/close').'" class="btn btn-danger">Close Ticket</a>';
						}
					echo '</div>';
					
				echo '</div>';
			echo '</div>';
			
		echo '</div>';
		
	echo '</div>';

} catch(Exception $ex) {
	message('error', $ex->getMessage());
}