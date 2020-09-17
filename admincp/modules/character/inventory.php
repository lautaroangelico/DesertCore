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
	
	if(!check($_GET['name'])) throw new Exception('Character name not provided.');
	
	$Player = new Player();
	$Player->setPlayer($_GET['name']);
	$characterData = $Player->getPlayerInformation();
	if(!is_array($characterData)) throw new Exception('Character data could not be loaded.');
	
	$Account = new Account();
	$Account->setUserid($characterData['accountId']);
	$accountData = $Account->getAccountData();
	if(!is_array($accountData)) throw new Exception('Account data could not be loaded.');
	
	echo '<h1 class="text-info"><a href="'.admincp_base('character/profile/name/'.$characterData['name']).'">'.$characterData['name'].'</a></h1>';
	echo '<hr>';
	echo '<div class="row">';
		echo '<div class="col-sm-12 col-md-12 col-lg-6">';
			
			echo '<div class="card">';
				echo '<div class="header">Inventory Items</div>';
				echo '<div class="content table-responsive table-full-width">';
					echo '<table class="table table-striped table-condensed">';
					echo '<thead>';
						echo '<tr>';
							echo '<th></th>';
							echo '<th>Item</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					$inventoryItems = $Player->getPlayerInventoryItemList();
					if(check($inventoryItems)) {
						foreach($inventoryItems as $item) {
							$desertCoreItemData = desertCoreItemDatabase($item->itemId);
							$itemName = check($desertCoreItemData['name']) ? $desertCoreItemData['name'] : 'Unknown';
							$itemIcon = check($desertCoreItemData['icon']) ? $desertCoreItemData['icon'] : '';
							
							echo '<tr>';
								echo '<td class="text-center"><img src="'.$itemIcon.'" width="auto" height="22px"/></td>';
								echo '<td class="align-middle"><a href="'.bdoDatabaseLink($item->itemId).'" target="_blank">'.number_format($item->count).'x '.$itemName.'</a></td>';
							echo '</tr>';
						}
					}
						echo '</tbody>';
					echo '</table>';
				echo '</div>';
			echo '</div>';
			
		echo '</div>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}