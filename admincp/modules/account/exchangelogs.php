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

$logsLimit = 100;
$ExchangePlayTime = new ExchangePlayTime();
if(check($_GET['username'])) {
	$ExchangePlayTime->setUsername($_GET['username']);
}
$exchangeLogs = $ExchangePlayTime->getLogs($logsLimit);

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-10 col-lg-8">';
		echo '<div class="card">';
			if(check($_GET['username'])) {
				echo '<div class="header"><strong>'.$_GET['username'].'</strong> Exchange Logs</span></div>';
			} else {
				echo '<div class="header">Last '.$logsLimit.' Exchange Logs</span></div>';
			}
			echo '<div class="content table-responsive table-full-width">';
				
				if(is_array($exchangeLogs)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Id</th>';
							echo '<th>Username</th>';
							echo '<th>Exchanged Hours</th>';
							echo '<th>Received Cash</th>';
							echo '<th>Date</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($exchangeLogs as $row) {
						echo '<tr>';
							echo '<td>'.$row['id'].'</td>';
							echo '<td>'.$row['username'].'</td>';
							echo '<td>'.$row['exchanged_hours'].'</td>';
							echo '<td>'.$row['received_cash'].'</td>';
							echo '<td>'.databaseTime($row['exchange_datetime']).'</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'There are no exchange logs in the database.');
				}
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';