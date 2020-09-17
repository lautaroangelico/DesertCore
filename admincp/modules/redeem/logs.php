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

$RedeemCode = new RedeemCode();
if(check($_GET['id'])) $RedeemCode->setId($_GET['id']);
$logs = $RedeemCode->getLogs();

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-12 col-lg-12">';
		
		echo '<div class="card">';
			echo '<div class="header">Redeem Codes Logs: <span class="text-info">'.number_format(count($logs)).'</span></div>';
			echo '<div class="content table-responsive table-full-width">';
				if(is_array($logs)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Redeemed Code</th>';
							echo '<th>Rewarded Cash</th>';
							echo '<th>Date Redeemed</th>';
							echo '<th>Account</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($logs as $data) {
						echo '<tr>';
							echo '<td>'.$data['redeem_title'].'</td>';
							echo '<td>'.number_format($data['redeem_cash']).'</td>';
							echo '<td>'.databaseTime($data['date_redeemed']).'</td>';
							echo '<td>';
								echo '<a href="'.admincp_base('account/profile/username/'.$data['account_username']).'">'.$data['account_username'].'</a>';
							echo '</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'No logs found on the database.');
				}
				
			echo '</div>';
		echo '</div>';
		
	echo '</div>';
	
echo '</div>';