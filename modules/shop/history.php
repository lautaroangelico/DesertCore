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
$Shop->setUsername($_SESSION['username']);
$purchaseLogs = $Shop->getAccountPurchaseLogs();

// purchase message
if(check($_GET['success'])) {
	try {
		if(!Validator::UnsignedNumber($_GET['success'])) throw new Exception();
		$Shop->setItemIndex($_GET['success']);
		$itemInfo = $Shop->getItemInfo();
		if(!is_array($itemInfo)) throw new Exception();
		message('success', lang('success_25', array($itemInfo['name'])));
	} catch(Exception $ex) {}
}

// purchase history
if(is_array($purchaseLogs)) {
	echo '<table class="table table-striped table-hover">';
		echo '<thead>';
			echo '<tr>';
				echo '<th>'.lang('shop_items_txt_4').'</th>';
				echo '<th>'.lang('general_currency_name').'</th>';
				echo '<th>'.lang('shop_items_txt_5').'</th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
			foreach($purchaseLogs as $row) {
				$itemEnhacementLevel = displayItemEnhancementLevel($row['enchant'], ': ');
				$itemDisplayName = $itemEnhacementLevel . $row['name'];
				echo '<tr>';
					echo '<td><a href="'.bdoDatabaseLink($row['item_id']).'" target="_blank">'.$itemDisplayName.'</a> ('.number_format($row['count']).'x)</td>';
					echo '<td>'.number_format($row['cash']).'</td>';
					echo '<td>'.databaseTime($row['timestamp']).'</td>';
				echo '</tr>';
			}
		echo '</tbody>';
	echo '</table>';
} else {
	message('warning', lang('error_277'));
}