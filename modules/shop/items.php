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
	
	$Shop = new Shop();
	if(check($_GET['c'])) $Shop->setCategory($_GET['c']);
	if(check($_GET['s'])) $Shop->setSubCategory($_GET['s']);
	if(check($_GET['p'])) $Shop->setPage($_GET['p']);
	
	$category = $Shop->getCategory();
	$subcategory = $Shop->getSubCategory();
	$categoriesList = $Shop->getCategoriesList();
	$itemList = $Shop->getItemsList();
	
	// purchase item
	if(check($_GET['purchase'])) {
		try {
			if(!isLoggedIn()) throw new Exception(lang('error_276'));
			$PurchaseItem = new Shop();
			$PurchaseItem->setUserId($_SESSION['userid']);
			$PurchaseItem->setItemIndex($_GET['purchase']);
			$PurchaseItem->purchaseItem();
			redirect('shop/history/success/'.$_GET['purchase']);
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<div class="row h-100">';
		
		// Categories Menu
		if($Shop->isMenuEnabled()) {
			echo '<div class="col-3 collapse d-md-flex bg-light pt-3 pb-3 mt-2 h-100" id="sidebar">';
				echo '<ul class="nav flex-column">';
				if(is_array($categoriesList)) {
					foreach($categoriesList as $navCategoryId => $navCategory) {
						if(check($navCategory['childs'])) {
							$subMenuName = 'c' . $navCategoryId;
							echo '<li class="nav-item" style="width:100%;">';
								echo '<a class="nav-link collapsed" href="#'.$subMenuName.'" data-toggle="collapse" data-target="#'.$subMenuName.'"><i class="fas fa-angle-right"></i> '.ucfirst($navCategory['title']).'</a>';
								echo '<div class="collapse" id="'.$subMenuName.'" aria-expanded="false">';
								echo '<ul class="flex-column pl-4 nav">';
									foreach($navCategory['childs'] as $child) {
										echo '<li class="nav-item pt-2 pb-2"><a class="nav-link py-0" href="'.$Shop->categoryLink($navCategory['id'], $child['id']).'">'.ucfirst($child['title']).'</a></li>';
									}
								echo '</ul>';
							echo '</li>';
							
						} else {
							# no sub-categories
							echo '<li class="nav-item"><a class="nav-link" href="'.$Shop->categoryLink($navCategory['id']).'"><i class="fas fa-angle-right"></i> '.ucfirst($navCategory['title']).'</a></li>';
						}
					}
				}
				echo '</ul>';
			echo '</div>';
		}
		
		echo '<div class="col pt-2">';
			
			// Breadcrumb
			if($Shop->isBreadcrumbEnabled()) {
				if(check($category) || check($subcategory)) {
					if(check($categoriesList[$category]['title'])) {
						echo '<nav aria-label="breadcrumb">';
							echo '<ol class="breadcrumb">';
								if(check($category)) echo '<li class="breadcrumb-item"><a href="'.$Shop->categoryLink($category).'">'.ucfirst($categoriesList[$category]['title']).'</a></li>';
								if(check($subcategory) && check($categoriesList[$category]['childs'][$subcategory]['title'])) echo '<li class="breadcrumb-item"><a href="'.$Shop->categoryLink($category, $subcategory).'">'.ucfirst($categoriesList[$category]['childs'][$subcategory]['title']).'</a></li>';
							echo '</ol>';
						echo '</nav>';
					}
				}
			}
			
			// Shop Items
			if(is_array($itemList)) {
				
				// Items
				echo '<table class="table table-dark table-striped shop-items-list">';
					foreach($itemList as $item) {
						$itemEnhacementLevel = displayItemEnhancementLevel($item['enchantment'], ': ');
						$desertCoreItemData = desertCoreItemDatabase($item['item_id']);
						$itemName = check($item['name']) ? $item['name'] : $desertCoreItemData['name'];
						$itemGrade = check($desertCoreItemData['grade']) ? $desertCoreItemData['grade'] : 0;
						$itemIcon = check($desertCoreItemData['icon']) ? $desertCoreItemData['icon'] : '';
						$itemDisplayName = $itemEnhacementLevel . trim($itemName);
						echo '<tr>';
							echo '<td class="text-center align-middle shop-items-list-image">';
								echo '<div class="shop-item-image"><img src="'.$itemIcon.'"></div>';
							echo '</td>';
							echo '<td class="align-middle">';
								echo '<span class="shop-items-list-count">' . $item['count'] . 'x</span><br />';
								echo '<a href="'.bdoDatabaseLink($item['item_id']).'" target="_blank" class="shop-items-list-itemname grade_'.$itemGrade.'">' . $itemDisplayName . '</a><br />';
							echo '</td>';
							echo '<td class="text-right align-middle shop-items-list-price">';
								echo number_format($item['cash']) . '<img src="'.Handler::templateIMG('cash.png').'" title="'.lang('general_currency_name').'" alt="'.lang('general_currency_name').'"/>';
							echo '</td>';
							echo '<td class="text-center align-middle shop-items-list-button">';
								echo '<button class="btn btn-sm btn-primary" onclick="confirmationMessage(\''.$Shop->purchaseLink($item['id']).'\', \''.addslashes($itemDisplayName).'\', \''.lang('shop_items_txt_1', array(number_format($item['cash']))).'\', \''.lang('shop_items_txt_2').'\', \''.lang('shop_items_txt_3').'\')">'.lang('shop_items_txt_2').'</button>';
							echo '</td>';
						echo '</tr>';
					}
				echo '</table>';
				
				// Pagination
				$paginationCatId = check($subcategory) ? $subcategory : $category;
				$paginationPages = ceil($Shop->categoryItemCount($paginationCatId)/$Shop->getItemsPerPage());
				$paginationPrevious = ($Shop->getCurrentPage() > 1 ? ($Shop->getCurrentPage()-1) : 1);
				$paginationNext = ($Shop->getCurrentPage() < $paginationPages ? ($Shop->getCurrentPage()+1) : $paginationPages);
				if($paginationPages > 1) {
					echo '<nav aria-label="Page navigation">';
						echo '<ul class="pagination pagination-lg justify-content-center">';
							echo '<li class="page-item">';
								echo '<a href="'.$Shop->categoryLink($category, $subcategory, $paginationPrevious).'" class="page-link shop-pagination">';
									echo '<span aria-hidden="true"><i class="fas fa-angle-left"></i></span>';
								echo '</a>';
							echo '</li>';
							for($i=1; $i<=$paginationPages; $i++) {
								if($i == $Shop->getCurrentPage()) {
									echo '<li class="page-item active shop-pagination"><a href="'.$Shop->categoryLink($category, $subcategory, $i).'" class="page-link shop-pagination">'.$i.'</a></li>';
								} else {
									echo '<li class="page-item shop-pagination"><a href="'.$Shop->categoryLink($category, $subcategory, $i).'" class="page-link shop-pagination">'.$i.'</a></li>';
								}
							}
							echo '<li class="page-item">';
								echo '<a href="'.$Shop->categoryLink($category, $subcategory, $paginationNext).'" class="page-link shop-pagination">';
									echo '<span aria-hidden="true"><i class="fas fa-angle-right"></i></span>';
								echo '</a>';
							echo '</li>';
						echo '</ul>';
					echo '</nav>';
				}
				
			} else {
				// no items in category
				message('info', lang('error_275'));
			}
			
		echo '</div>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}