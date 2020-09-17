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

if(!check($_GET['c'])) throw new Exception('You must provide a category id.');
$category = check($_GET['s']) ? $_GET['s'] : $_GET['c'];
$returnLocation = '';
if(check($_GET['c'])) $returnLocation .= '/c/'.$_GET['c'];
if(check($_GET['s'])) $returnLocation .= '/s/'.$_GET['s'];

$Shop = new Shop();
$categoriesList = $Shop->getCategoriesTitles();
if(!is_array($categoriesList)) redirect('shop/categories');
if(!array_key_exists($category, $categoriesList)) throw new Exception('The provided category id is not valid.');

if(check($_POST['item_add'])) {
	try {
		
		$Shop->setItemCategory($category);
		$Shop->setItemId($_POST['item_id']);
		if(check($_POST['item_name'])) $Shop->setItemName($_POST['item_name']);
		$Shop->setItemCount($_POST['item_count']);
		$Shop->setItemEnchantment($_POST['item_enchantment']);
		$Shop->setItemCost($_POST['item_cost']);
		$Shop->addItem();
		
		redirect('shop/items' . $returnLocation);
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="row">';
	
	# RIGHT
	echo '<div class="col-md-4">';
		echo '<div class="card">';
			echo '<div class="header">Add Item</div>';
			echo '<div class="content">';
				
				echo '<form action="'.admincp_base('shop/add'.$returnLocation).'" method="post">';
						echo '<div class="form-group">';
							echo '<label>Category / Sub-category</label><br />';
							echo $categoriesList[$category]['title'];
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label>Item Id</label>';
							echo '<input type="text" name="item_id" class="form-control" placeholder="Item id">';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label>Item Name (optional)</label>';
							echo '<input type="text" name="item_name" class="form-control" placeholder="Item name">';
							echo '<span id="helpBlock" class="help-block">Leave empty to get item name from BDO database.</span>';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label>Item Count</label>';
							echo '<input type="text" name="item_count" class="form-control" value="1">';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label>Enhancement Level</label>';
							echo '<select name="item_enchantment" class="form-control">';
								echo '<option value="0" selected>+0</option>';
								echo '<option value="1">+1</option>';
								echo '<option value="2">+2</option>';
								echo '<option value="3">+3</option>';
								echo '<option value="4">+4</option>';
								echo '<option value="5">+5</option>';
								echo '<option value="6">+6</option>';
								echo '<option value="7">+7</option>';
								echo '<option value="8">+8</option>';
								echo '<option value="9">+9</option>';
								echo '<option value="10">+10</option>';
								echo '<option value="11">+11</option>';
								echo '<option value="12">+12</option>';
								echo '<option value="13">+13</option>';
								echo '<option value="14">+14</option>';
								echo '<option value="15">+15</option>';
								echo '<option value="16">PRI</option>';
								echo '<option value="17">DUO</option>';
								echo '<option value="18">TRI</option>';
								echo '<option value="19">TET</option>';
								echo '<option value="20">PEN</option>';
							echo '</select>';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label>Price (cash)</label>';
							echo '<input type="text" name="item_cost" class="form-control" placeholder="Item price">';
						echo '</div>';
						echo '<button type="submit" class="btn btn-primary" name="item_add" value="ok">Add Item</button>';
					echo '</form><br />';
					
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
echo '</div>';