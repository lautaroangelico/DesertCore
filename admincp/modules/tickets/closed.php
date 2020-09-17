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
	$ticketsList = $Tickets->getClosedTickets();
	if(!is_array($ticketsList)) throw new Exception('There are no closed tickets.');
	
	echo '<div class="card">';
		echo '<div class="header">Closed Tickets: <span class="text-info">'.number_format(count($ticketsList)).'</span></div>';
		echo '<div class="content table-responsive table-full-width">';
			echo '<table class="table table-striped table-hover">';
				echo '<thead>';
					echo '<tr>';
						echo '<th>'.lang('tickets_txt_5').'</th>';
						echo '<th>'.lang('tickets_txt_1').'</th>';
						echo '<th>'.lang('tickets_txt_15').'</th>';
						echo '<th>'.lang('tickets_txt_14').'</th>';
						echo '<th>'.lang('tickets_txt_6').'</th>';
						echo '<th>'.lang('tickets_txt_7').'</th>';
						echo '<th></th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
					foreach($ticketsList as $ticketData) {
						
						echo '<tr>';
							echo '<td>#'.$ticketData['id'].'</td>';
							echo '<td>'.$ticketData['subject'].'</td>';
							echo '<td>'.$ticketData['username'].'</td>';
							echo '<td>'.databaseTime($ticketData['create_date']).'</td>';
							echo '<td>'.$ticketData['last_reply_by'].'</td>';
							echo '<td>'.databaseTime($ticketData['last_reply_date']).'</td>';
							echo '<td class="text-right">';
								echo '<a href="'.admincp_base('tickets/view/id/' . $ticketData['id']).'" class="btn btn-sm btn-primary">'.lang('tickets_txt_11').'</a>';
							echo '</td>';
						echo '</tr>';
					}
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}