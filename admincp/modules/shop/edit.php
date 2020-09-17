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

if(!check($_GET['id'])) throw new Exception('You must provide an item index id.');
$returnLocation = '';
if(check($_GET['c'])) $returnLocation .= '/c/'.$_GET['c'];
if(check($_GET['s'])) $returnLocation .= '/s/'.$_GET['s'];

$Shop = new Shop();
$Shop->setItemIndex($_GET['id']);

$categoriesList = $Shop->getCategoriesTitles();
if(!is_array($categoriesList)) redirect('shop/categories');

$itemInfo = $Shop->getItemInfo();
if(!is_array($itemInfo)) throw new Exception('Could not load item info.');

if(check($_POST['item_edit'])) {
	try {
		
		$Shop->setItemCategory($itemInfo['category']);
		$Shop->setItemId($_POST['item_id']);
		if(check($_POST['item_name'])) $Shop->setItemName($_POST['item_name']);
		$Shop->setItemCount($_POST['item_count']);
		$Shop->setItemEnchantment($_POST['item_enchantment']);
		$Shop->setItemCost($_POST['item_cost']);
		$Shop->editItem();
		
		redirect('shop/items' . $returnLocation);
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="row">';
	
	# RIGHT
	echo '<div class="col-md-4">';
		echo '<div class="card">';
			echo '<div class="header">Edit Item</div>';
			echo '<div class="content">';
				
				echo '<form action="'.admincp_base('shop/edit/id/'.$_GET['id'].$returnLocation).'" method="post">';
						echo '<div class="form-group">';
							echo '<label>Category / Sub-category</label><br />';
							echo $categoriesList[$itemInfo['category']]['title'];
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label>Item Id</label>';
							echo '<input type="text" name="item_id" class="form-control" value="'.$itemInfo['item_id'].'">';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label>Item Name (optional)</label>';
							echo '<input type="text" name="item_name" class="form-control" value="'.$itemInfo['name'].'">';
							echo '<span id="helpBlock" class="help-block">Leave empty to get item name from BDO database.</span>';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label>Item Count</label>';
							echo '<input type="text" name="item_count" class="form-control" value="'.$itemInfo['count'].'">';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label>Enhancement Level</label>';
							echo '<select name="item_enchantment" class="form-control">';
								echo '<option value="0"'.($itemInfo['enchantment'] == 0 ? ' selected' : '').'>+0</option>';
								echo '<option value="1"'.($itemInfo['enchantment'] == 1 ? ' selected' : '').'>+1</option>';
								echo '<option value="2"'.($itemInfo['enchantment'] == 2 ? ' selected' : '').'>+2</option>';
								echo '<option value="3"'.($itemInfo['enchantment'] == 3 ? ' selected' : '').'>+3</option>';
								echo '<option value="4"'.($itemInfo['enchantment'] == 4 ? ' selected' : '').'>+4</option>';
								echo '<option value="5"'.($itemInfo['enchantment'] == 5 ? ' selected' : '').'>+5</option>';
								echo '<option value="6"'.($itemInfo['enchantment'] == 6 ? ' selected' : '').'>+6</option>';
								echo '<option value="7"'.($itemInfo['enchantment'] == 7 ? ' selected' : '').'>+7</option>';
								echo '<option value="8"'.($itemInfo['enchantment'] == 8 ? ' selected' : '').'>+8</option>';
								echo '<option value="9"'.($itemInfo['enchantment'] == 9 ? ' selected' : '').'>+9</option>';
								echo '<option value="10"'.($itemInfo['enchantment'] == 10 ? ' selected' : '').'>+10</option>';
								echo '<option value="11"'.($itemInfo['enchantment'] == 11 ? ' selected' : '').'>+11</option>';
								echo '<option value="12"'.($itemInfo['enchantment'] == 12 ? ' selected' : '').'>+12</option>';
								echo '<option value="13"'.($itemInfo['enchantment'] == 13 ? ' selected' : '').'>+13</option>';
								echo '<option value="14"'.($itemInfo['enchantment'] == 14 ? ' selected' : '').'>+14</option>';
								echo '<option value="15"'.($itemInfo['enchantment'] == 15 ? ' selected' : '').'>+15</option>';
								echo '<option value="16"'.($itemInfo['enchantment'] == 16 ? ' selected' : '').'>PRI</option>';
								echo '<option value="17"'.($itemInfo['enchantment'] == 17 ? ' selected' : '').'>DUO</option>';
								echo '<option value="18"'.($itemInfo['enchantment'] == 18 ? ' selected' : '').'>TRI</option>';
								echo '<option value="19"'.($itemInfo['enchantment'] == 19 ? ' selected' : '').'>TET</option>';
								echo '<option value="20"'.($itemInfo['enchantment'] == 20 ? ' selected' : '').'>PEN</option>';
							echo '</select>';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label>Price (cash)</label>';
							echo '<input type="text" name="item_cost" class="form-control" value="'.$itemInfo['cash'].'">';
						echo '</div>';
						echo '<button type="submit" class="btn btn-warning" name="item_edit" value="ok">Edit Item</button>';
					echo '</form><br />';
					
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
echo '</div>';