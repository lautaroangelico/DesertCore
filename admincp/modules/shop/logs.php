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

$Shop = new Shop();
if(check($_GET['limit'])) {
	$Shop->setLogsLimit($_GET['limit']);
} else {
	$Shop->setLogsLimit(100);
}
if(check($_GET['username'])) {
	$Shop->setUsername($_GET['username']);
}
$purchaseLogs = $Shop->getPurchaseLogs();

echo '<div class="card">';
	echo '<div class="header">Purchase Logs</div>';
	echo '<div class="content table-responsive table-full-width">';
		if(is_array($purchaseLogs)) {
			echo '<table class="table table-striped">';
				echo '<thead>';
					echo '<tr>';
						echo '<th>Account</th>';
						echo '<th>Item Id</th>';
						echo '<th>Item</th>';
						echo '<th>Cash</th>';
						echo '<th>IP Address</th>';
						echo '<th>Date</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				foreach($purchaseLogs as $row) {
					
					$itemCount = number_format($row['count']) . 'x';
					$itemEnchantment = displayItemEnhancementLevel($row['enchant'], ': ');
					$itemDisplayName = $itemEnchantment.$row['name'].' ('.$itemCount.')';
					
					echo '<tr>';
						echo '<td><a href="'.admincp_base('account/profile/username/'.$row['username']).'">'.$row['username'].'</a></td>';
						echo '<td><a href="'.bdoDatabaseLink($row['item_id']).'">'.$row['item_id'].'</a></td>';
						echo '<td>'.$itemDisplayName.'</td>';
						echo '<td>'.number_format($row['cash']).'</td>';
						echo '<td>'.$row['ip_address'].'</td>';
						echo '<td>'.databaseTime($row['timestamp']).'</td>';
					echo '</tr>';
				}
				echo '</tbody>';
			echo '</table>';
		} else {
			message('warning', 'There are no purchase logs to display.');
		}
	echo '</div>';
echo '</div>';