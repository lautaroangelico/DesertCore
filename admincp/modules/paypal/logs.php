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

$configurationFile = 'paypal';
$cfg = loadConfig($configurationFile);
if(!is_array($cfg)) throw new Exception('Could not load configuration file.');

$PayPal = new PayPal();
$logs = $PayPal->getLogs();

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-12 col-lg-12">';
		
		echo '<div class="card">';
			echo '<div class="header">PayPal Logs</div>';
			echo '<div class="content table-responsive table-full-width">';
				if(is_array($logs)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Id</th>';
							echo '<th>Transaction Id</th>';
							echo '<th>Payer Email</th>';
							echo '<th>User</th>';
							echo '<th>Package</th>';
							echo '<th>Amount</th>';
							echo '<th>Payment Date</th>';
							echo '<th>Status</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($logs as $data) {
						
						$Account = new Account();
						$Account->setUserid($data['userid']);
						$accountData = $Account->getAccountData();
						if(is_array($accountData)) {
							$username = $accountData['accountName'];
						} else {
							$username = '<span style="color:red;font-style: italic;">Unknown ('.$data['userid'].')</span>';
						}
						
						echo '<tr>';
							echo '<td>'.$data['id'].'</td>';
							echo '<td>'.$data['txn_id'].'</td>';
							echo '<td>'.$data['payer_email'].'</td>';
							echo '<td>'.$username.'</td>';
							echo '<td>'.$data['item_name'].' (pid: '.$data['packageid'].')</td>';
							echo '<td>'.number_format($data['payment_gross']).' ('.$cfg['currency'].')</td>';
							echo '<td>'.databaseTime($data['payment_date']).'</td>';
							echo '<td>'.$data['payment_status'].'</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('success', 'No logs found on the database.');
				}
				
			echo '</div>';
		echo '</div>';
		
	echo '</div>';
	
echo '</div>';