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

$Player = new Player();
$Player->setUserid($_SESSION['userid']);
$Player->setPlayer($_GET['name']);
if(!$Player->belongsToAccount()) throw new Exception(lang('error_303'));

$playerData = $Player->getPlayerInformation();
if(!is_array($playerData)) throw new Exception(lang('error_304'));

echo '<div class="row">';
	echo '<div class="col-12 player-profile">';
		
		echo '<div class="row">';
			echo '<div class="col-12 text-center py-3">';
				echo '<h2>'.$playerData['name'].'</h2>';
			echo '</div>';
		echo '</div>';
		
		echo '<div class="row py-3">';
			
			// Player Class Image
			echo '<div class="col-3 text-center align-middle">';
				echo '<div class="player-profile-class-zodiac-icon">';
					echo returnPlayerClass($playerData['classType'], true, true);
				echo '</div>';
			echo '</div>';
			
			// General Player Info
			echo '<div class="col-6 text-center">';
				echo '<table class="table table-borderless table-sm player-profile-table">';
					echo '<tr>';
						echo '<td class="text-right">Class</td>';
						echo '<td class="text-left">'.playerClassName($playerData['classType']).'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td class="text-right">Level</td>';
						echo '<td class="text-left">'.$playerData['level'].'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td class="text-right">Experience</td>';
						echo '<td class="text-left">'.number_format($playerData['exp']).'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td class="text-right">Creation Date</td>';
						echo '<td class="text-left">'.formatMongoDate($playerData['creationDate']).'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td class="text-right">Last Login</td>';
						echo '<td class="text-left">'.formatMongoDate($playerData['lastLogin']).'</td>';
					echo '</tr>';
					
					$playedTimeData = sec_to_dhms(round($playerData['playedTime']/1000));
					if($playedTimeData[2] > 0) $playTime = $playedTimeData[2] . ' ' . lang('rankings_txt_9');
					if($playedTimeData[1] > 0) $playTime = $playedTimeData[1] . ' ' . lang('rankings_txt_8');
					if($playedTimeData[0] > 0) $playTime = $playedTimeData[0] . ' ' . lang('rankings_txt_7');
					
					echo '<tr>';
						echo '<td class="text-right">Play Time</td>';
						echo '<td class="text-left">'.$playTime.'</td>';
					echo '</tr>';
				echo '</table>';
			echo '</div>';
			
			// Zodiac Image
			echo '<div class="col-3 text-center align-middle">';
				echo '<div class="player-profile-class-zodiac-icon">';
					echo returnPlayerZodiac($playerData['zodiac'], true, true);
				echo '</div>';
			echo '</div>';
			
		echo '</div>';
		
	echo '</div>';
echo '</div>';


echo '<div class="row mt-3">';

	// Equipment
	echo '<div class="col-6">';
		echo '<h3>Equipped Items</h3>';
		echo '<table class="table table-dark table-striped table-sm">';
		echo '<thead>';
			echo '<tr>';
				echo '<th></th>';
				echo '<th>Item</th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		$equippedItems = $Player->getPlayerEquippedItemList();
		if(check($equippedItems)) {
			foreach($equippedItems as $item) {
				$desertCoreItemData = desertCoreItemDatabase($item->itemId);
				$itemName = check($desertCoreItemData['name']) ? $desertCoreItemData['name'] : 'Unknown';
				$itemIcon = check($desertCoreItemData['icon']) ? $desertCoreItemData['icon'] : '';
				$itemGrade = check($desertCoreItemData['grade']) ? $desertCoreItemData['grade'] : 0;
				
				echo '<tr>';
					echo '<td class="text-center"><div class="profile-item-icon"><img src="'.$itemIcon.'" /></div></td>';
					echo '<td class="align-middle"><a href="'.bdoDatabaseLink($item->itemId).'" target="_blank" class="profile-item-grade-'.$itemGrade.'">'.number_format($item->count).'x '.$itemName.'</a></td>';
				echo '</tr>';
			}
		}
			echo '</tbody>';
		echo '</table>';
	echo '</div>';

	// Inventory
	echo '<div class="col-6">';
		echo '<h3>Inventory Items</h3>';
		echo '<table class="table table-dark table-striped table-sm">';
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
				$itemGrade = check($desertCoreItemData['grade']) ? $desertCoreItemData['grade'] : 0;
				
				echo '<tr>';
					echo '<td class="text-center"><div class="profile-item-icon"><img src="'.$itemIcon.'" /></div></td>';
					echo '<td class="align-middle"><a href="'.bdoDatabaseLink($item->itemId).'" target="_blank" class="profile-item-grade-'.$itemGrade.'">'.number_format($item->count).'x '.$itemName.'</a></td>';
				echo '</tr>';
			}
		}
			echo '</tbody>';
		echo '</table>';
	echo '</div>';

echo '</div>';