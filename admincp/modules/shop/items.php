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
$categoriesList = $Shop->getCategoriesTitles();
if(check($_GET['c'])) $Shop->setCategory($_GET['c']);
if(check($_GET['s'])) $Shop->setSubCategory($_GET['s']);

if(check($_GET['c'])) {
	$Shop->disableLimit();
	$itemList = $Shop->getItemsList();
	$title = $categoriesList[$_GET['c']]['title'];
	$cat = $_GET['c'];
	$returnLocation = '/c/' . $_GET['c'];
	if(check($_GET['s'])) {
		$title .= ' : ' . $categoriesList[$_GET['s']]['title'];
		$cat = $_GET['s'];
		$returnLocation .= '/s/' . $_GET['s'];
	}
	echo '<h1>'.$title.' <a href="'.admincp_base('shop/add'.$returnLocation).'" class="btn btn-lg btn-primary">Add Item</a></h1>';
} else {
	$itemList = $Shop->getFullItemList();
}

echo '<div class="card">';
	echo '<div class="header">Item List</div>';
	echo '<div class="content table-responsive table-full-width">';
		if(is_array($itemList)) {
			echo '<table class="table table-striped">';
				echo '<thead>';
					echo '<tr>';
						echo '<th>Item Id</th>';
						echo '<th>Count</th>';
						echo '<th>Item Name</th>';
						echo '<th>Category</th>';
						echo '<th>Price</th>';
						echo '<th class="text-right">Actions</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				foreach($itemList as $item) {
					
					if(!check($item['name'])) {
						$itemData = desertCoreItemDatabase($item['item_id']);
						$itemName = check($itemData['name']) ? $itemData['name'] : 'Unknown';
					} else {
						$itemName = $item['name'];
					}
					
					$itemCategory = check($categoriesList[$item['category']]['title']) ? $categoriesList[$item['category']]['title'] : 'Unknown';
					$itemEnhacementLevel = displayItemEnhancementLevel($item['enchantment'], ': ');
					
					echo '<tr>';
						echo '<td>'.$item['item_id'].'</td>';
						echo '<td>'.number_format($item['count']).'</td>';
						echo '<td>'.$itemEnhacementLevel.$itemName.'</td>';
						echo '<td>'.$itemCategory.' ('.$item['category'].')</td>';
						echo '<td>'.number_format($item['cash']).'</td>';
						echo '<td class="text-right">';
							echo '<a href="'.admincp_base('shop/edit/id/'.$item['id'].$returnLocation).'" class="btn btn-default btn-xs">Edit</a> ';
							echo '<a href="#" onclick="confirmationMessage(\''.admincp_base('shop/delete/item/'.$item['id'].$returnLocation).'\', \'Are you sure?\', \'This action will permanently delete the item from the shop.\', \'Confirm\', \'Cancel\')" class="btn btn-danger btn-xs">Delete</a>';
						echo '</td>';
					echo '</tr>';
				}
				echo '</tbody>';
			echo '</table>';
		} else {
			message('warning', 'There are no items in the shop.');
		}
	echo '</div>';
echo '</div>';