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
	
	$Tickets = new Tickets();
	$Tickets->setUsername($_SESSION['username']);
	
	echo '<a href="'.Handler::websiteLink('account/tickets/new').'" class="btn btn-sm btn-info">'.lang('tickets_txt_13').'</a>';
	echo '<hr>';
	
	$ticketsList = $Tickets->getAccountTickets();
	if(!is_array($ticketsList)) throw new Exception('You don\'t have any tickets.');
	
	echo '<table class="table">';
		echo '<thead>';
			echo '<tr>';
				echo '<th>'.lang('tickets_txt_5').'</th>';
				echo '<th>'.lang('tickets_txt_1').'</th>';
				echo '<th>'.lang('tickets_txt_6').'</th>';
				echo '<th>'.lang('tickets_txt_7').'</th>';
				echo '<th>'.lang('tickets_txt_8').'</th>';
				echo '<th></th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
			foreach($ticketsList as $ticketData) {
				$status = $ticketData['closed'] == 1 ? '<span class="badge badge-secondary">'.lang('tickets_txt_10').'</span>' : '<span class="badge badge-success">'.lang('tickets_txt_9').'</span>';
				
				echo '<tr>';
					echo '<td>#'.$ticketData['id'].'</td>';
					echo '<td>'.$ticketData['subject'].'</td>';
					echo '<td>'.$ticketData['last_reply_by'].'</td>';
					echo '<td>'.databaseTime($ticketData['last_reply_date']).'</td>';
					echo '<td>'.$status.'</td>';
					echo '<td class="text-right">';
						echo '<a href="'.Handler::websiteLink('account/tickets/view/id/' . $ticketData['id']).'" class="btn btn-sm btn-primary">'.lang('tickets_txt_11').'</a>';
					echo '</td>';
				echo '</tr>';
			}
		echo '</tbody>';
	echo '</table>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}