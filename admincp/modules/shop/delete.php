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
	
	// delete item
	if(check($_GET['item'])) {
		$Shop = new Shop();
		$Shop->setItemIndex($_GET['item']);
		$Shop->deleteItem();
		
		$returnLocation = 'shop/items';
		if(check($_GET['c'])) $returnLocation .= '/c/'.$_GET['c'];
		if(check($_GET['s'])) $returnLocation .= '/s/'.$_GET['s'];
		
		redirect($returnLocation);
	}
	
	// delete sub category
	if(check($_GET['subcategory'])) {
		$Shop = new Shop();
		$Shop->deleteSubCategory($_GET['subcategory']);
		redirect('shop/categories');
	}
	
	// delete category
	if(check($_GET['category'])) {
		$Shop = new Shop();
		$Shop->deleteCategory($_GET['category']);
		redirect('shop/categories');
	}
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}