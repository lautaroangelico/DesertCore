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

final class Shop {
	
	private $_itemsPerPage = 10;
	private $_currentPage = 1;
	
	private $_category;
	private $_subCategory;
	private $_shopUrl;
	private $_categories;
	private $_userId;
	private $_username;
	private $_itemIndex;
	
	private $_itemId;
	private $_itemName;
	private $_itemCount = 1;
	private $_itemEnchantment = 0;
	private $_itemCost = 1000;
	private $_itemCategory;
	
	private $_parent = 0;
	private $_title;
	
	private $_disableLimit = false;
	private $_ignoreStatus = false;
	
	private $_enableMenu = true;
	private $_enableBreadcrumb = true;
	private $_mailSenderName = 'DesertCore CMS';
	private $_mailSubject = 'Web Shop Purchase';
	private $_mailMessage = 'Thanks for your purchase.';
	
	private $_logsLimit = 500;
	
	function __construct() {
		
		# paths and urls
		$this->_shopUrl = __BASE_URL__ . 'shop/items/';
		
		# database
		$this->db = Handler::loadDB('WebEngine');
		
		// configs
		$this->_cfg = loadConfig('shop');
		if(!is_array($this->_cfg)) throw new Exception(lang('error_66'));
		$this->_itemsPerPage = check($this->_cfg['items_per_page']) ? $this->_cfg['items_per_page'] : 10;
		if($this->_cfg['default_category'] >= 1) {
			$this->_category = $this->_cfg['default_category'];
		}
		if($this->_cfg['default_subcategory'] >= 1) {
			$this->_subCategory = $this->_cfg['default_subcategory'];
		}
		$this->_mailSenderName = $this->_cfg['mail_sender_name'];
		$this->_mailSubject = $this->_cfg['mail_subject'];
		$this->_mailMessage = $this->_cfg['mail_message'];
		$this->_enableMenu = $this->_cfg['enable_menu'];
		$this->_enableBreadcrumb = $this->_cfg['enable_breadcrumb'];
	}
	
	public function setUserId($userId) {
		if(!Validator::UnsignedNumber($userId)) throw new Exception(lang('error_90'));
		$this->_userId = $userId;
	}
	
	public function setUsername($username) {
		$this->_username = $username;
	}
	
	public function setItemIndex($index) {
		if(!Validator::UnsignedNumber($index)) throw new Exception(lang('error_278'));
		$this->_itemIndex = $index;
	}
	
	public function setCategory($id) {
		if(!Validator::UnsignedNumber($id)) throw new Exception(lang('error_279'));
		if(!$this->_categoryExists($id)) throw new Exception(lang('error_279'));
		$this->_category = $id;
		$this->_subCategory = null;
	}
	
	public function setSubCategory($id) {
		if(!Validator::UnsignedNumber($id)) throw new Exception(lang('error_280'));
		if(!check($this->_category)) throw new Exception(lang('error_279'));
		if(!$this->_categoryExists($id)) throw new Exception(lang('error_280'));
		$this->_subCategory = $id;
	}
	
	public function setItemId($id) {
		if(!Validator::UnsignedNumber($id)) throw new Exception(lang('error_281'));
		$this->_itemId = $id;
	}
	
	public function setItemName($name) {
		if(!Validator::Length($name, 100, 1)) throw new Exception(lang('error_282'));
		$this->_itemName = $name;
	}
	
	public function setItemCount($count) {
		if(!Validator::UnsignedNumber($count)) throw new Exception(lang('error_283'));
		$this->_itemCount = $count;
	}
	
	public function setItemEnchantment($level) {
		if(!Validator::Number($level, 20, 0)) throw new Exception(lang('error_284'));
		$this->_itemEnchantment = $level;
	}
	
	public function setItemCost($cash) {
		if(!Validator::UnsignedNumber($cash)) throw new Exception(lang('error_285'));
		$this->_itemCost = $cash;
	}
	
	public function setItemCategory($id) {
		if(!Validator::UnsignedNumber($id)) throw new Exception(lang('error_286'));
		if(!$this->_categoryExists($id)) throw new Exception(lang('error_286'));
		$this->_itemCategory = $id;
	}
	
	public function setPage($page) {
		if(!Validator::UnsignedNumber($page)) throw new Exception(lang('error_287'));
		$this->_currentPage = $page;
	}
	
	public function setParent($id) {
		if(!Validator::UnsignedNumber($id)) throw new Exception(lang('error_288'));
		$this->_parent = $id;
	}
	
	public function setTitle($title) {
		$this->_title = $title;
	}
	
	public function setLogsLimit($limit) {
		if(!Validator::UnsignedNumber($limit)) return;
		$this->_logsLimit = $limit;
	}
	
	public function getItemsPerPage() {
		return $this->_itemsPerPage;
	}
	
	public function getCurrentPage() {
		return $this->_currentPage;
	}
	
	public function getCategory() {
		return $this->_category;
	}
	
	public function getSubCategory() {
		return $this->_subCategory;
	}
	
	public function getCategoriesList() {
		$this->_loadCategories();
		return $this->_categories;
	}
	
	public function getShopHome() {
		return $this->_shopUrl;
	}
	
	public function disableLimit() {
		$this->_disableLimit = true;
	}
	
	public function ignoreStatus() {
		$this->_ignoreStatus = true;
	}
	
	public function categoryLink($id=0, $sub=0, $page=1) {
		$return = $this->_shopUrl;
		if($id != 0) {
			$return .= 'c/' . $id;
			
			if($sub != 0) {
				$return .= '/s/' . $sub;
			}
		}
		if($page > 1) {
			$return .= '/p/' . $page;
		}
		
		return $return;
	}
		
	public function categoryItemCount($id) {
		if(!$this->_categoryExists($id)) return 0;
		$categoriesArray[] = $id;
		$childs = $this->_getCategoryChilds($id);
		if(is_array($childs)) {
			foreach($childs as $row) {
				$categoriesArray[] = $row['id'];
			}
		}
		$categories = implode(",", $categoriesArray);
		$count = $this->db->queryFetchSingle("SELECT COUNT(*) as `total` FROM `"._DC_SHOPITEMS_."` WHERE `category` IN (".$categories.") AND `status` = ?", array(1));
		if(!is_array($count)) return 0;
		return $count['total'];
	}
	
	public function getItemsList() {
		if(check($this->_subCategory)) {
			// items from single sub-category
			$categoryId = $this->_subCategory;
			$query = "SELECT * FROM `"._DC_SHOPITEMS_."` WHERE `category` = ? AND `status` = ? ORDER BY `order` ASC, `id` ASC";
			if(!$this->_disableLimit) $query .= " LIMIT ".$this->_resultLimitStart().",".$this->_itemsPerPage."";
			$itemList = $this->db->queryFetch($query, array($categoryId , 1));
		} else {
			// items from whole category and sub-categories
			if(!check($this->_category)) return;
			$categoriesArray[] = $this->_category;
			$childs = $this->_getCategoryChilds($this->_category);
			if(is_array($childs)) {
				foreach($childs as $row) {
					$categoriesArray[] = $row['id'];
				}
			}
			$categoryId = implode(",", $categoriesArray);
			$query = "SELECT * FROM `"._DC_SHOPITEMS_."` WHERE `category` IN (".$categoryId.") AND `status` = ? ORDER BY `order` ASC, `id` ASC";
			if(!$this->_disableLimit) $query .= " LIMIT ".$this->_resultLimitStart().",".$this->_itemsPerPage."";
			$itemList = $this->db->queryFetch($query, array(1));
		}
		if(!is_array($itemList)) return;
		return $itemList;
	}
	
	public function purchaseLink($id) {
		$return = $this->_shopUrl;
		if(check($this->_category)) {
			$return .= 'c/'.$this->_category.'/';
			if(check($this->_subCategory)) {
				$return .= 's/'.$this->_subCategory.'/';
			}
		}
		if($this->_currentPage > 1) {
			$return .= 'p/'.$this->_currentPage.'/';
		}
		$return .= 'purchase/';
		$return .= $id;
		return $return;
	}
	
	public function getFullItemList() {
		$result = $this->db->queryFetch("SELECT * FROM `"._DC_SHOPITEMS_."` WHERE `status` = ? ORDER BY `id` DESC", array(1));
		if(!is_array($result)) return;
		return $result;
	}
	
	public function getCategoriesTitles() {
		$result = $this->db->queryFetch("SELECT * FROM `"._DC_SHOPCAT_."` WHERE `status` = ?", array(1));
		if(!is_array($result)) return;
		
		foreach($result as $row) {
			$return[$row['id']] = array('title' => $row['title']);
		}
		
		return $return;
	}
	
	public function deleteItem() {
		if(!check($this->_itemIndex)) return;
		$result = $this->db->query("DELETE FROM `"._DC_SHOPITEMS_."` WHERE `id` = ?", array($this->_itemIndex));
		if(!$result) return;
		return true;
	}
	
	public function deleteCategory($id) {
		if(!$this->_categoryExists($id)) return;
		$childs = $this->_getCategoryChilds($id);
		if(is_array($childs)) {
			foreach($childs as $row) {
				$this->deleteSubCategory($row['id']);
			}
		}
		$this->deleteSubCategory($id);
		return true;
	}
	
	public function deleteSubCategory($id) {
		if(!check($id)) return;
		$result = $this->db->query("DELETE FROM `"._DC_SHOPCAT_."` WHERE `id` = ?", array($id));
		if(!$result) return;
		$deleteItems = $this->db->query("DELETE FROM `"._DC_SHOPITEMS_."` WHERE `category` = ?", array($id));
		if(!$deleteItems) return;
		return true;
	}
	
	public function addItem() {
		if(!check($this->_itemId)) throw new Exception(lang('error_289'));
		if(!check($this->_itemCount)) throw new Exception(lang('error_289'));
		if(!check($this->_itemEnchantment)) throw new Exception(lang('error_289'));
		if(!check($this->_itemCost)) throw new Exception(lang('error_289'));
		if(!check($this->_itemCategory)) throw new Exception(lang('error_289'));
		
		if(!check($this->_itemName)) {
			$itemData = desertCoreItemDatabase($this->_itemId);
			if(!is_array($itemData)) throw new Exception(lang('error_290'));
			if(!check($itemData['name'])) throw new Exception(lang('error_290'));
			$this->_itemName = $itemData['name'];
		}
		
		$data = array(
			$this->_itemId,
			$this->_itemName,
			$this->_itemCount,
			$this->_itemCost,
			$this->_itemCategory,
			$this->_itemEnchantment
		);
		$result = $this->db->query("INSERT INTO `"._DC_SHOPITEMS_."` (`item_id`, `name`, `count`, `cash`, `category`, `enchantment`) VALUES (?, ?, ?, ?, ?, ?)", $data);
		if(!$result) throw new Exception(lang('error_291'));
	}
	
	public function editItem() {
		if(!check($this->_itemIndex)) throw new Exception(lang('error_292'));
		if(!check($this->_itemId)) throw new Exception(lang('error_292'));
		if(!check($this->_itemCount)) throw new Exception(lang('error_292'));
		if(!check($this->_itemEnchantment)) throw new Exception(lang('error_292'));
		if(!check($this->_itemCost)) throw new Exception(lang('error_292'));
		if(!check($this->_itemCategory)) throw new Exception(lang('error_292'));
		
		if(!check($this->_itemName)) {
			$itemData = desertCoreItemDatabase($this->_itemId);
			if(!is_array($itemData)) throw new Exception(lang('error_290'));
			if(!check($itemData['name'])) throw new Exception(lang('error_290'));
			$this->_itemName = $itemData['name'];
		}
		
		$data = array(
			$this->_itemId,
			$this->_itemName,
			$this->_itemCount,
			$this->_itemCost,
			$this->_itemCategory,
			$this->_itemEnchantment,
			$this->_itemIndex
		);
		$result = $this->db->query("UPDATE `"._DC_SHOPITEMS_."` SET `item_id` = ?, `name` = ?, `count` = ?, `cash` = ?, `category` = ?, `enchantment` = ? WHERE `id` = ?", $data);
		if(!$result) throw new Exception(lang('error_293'));
	}
	
	public function getItemInfo() {
		if(!check($this->_itemIndex)) return;
		$result = $this->db->queryFetchSingle("SELECT * FROM `"._DC_SHOPITEMS_."` WHERE `id` = ?", array($this->_itemIndex));
		if(!is_array($result)) return;
		return $result;
	}
	
	public function changeCategoryStatus($id, $status=1) {
		if(!check($id)) return;
		$result = $this->db->query("UPDATE `"._DC_SHOPCAT_."` SET `status` = ? WHERE `id` = ?", array($status, $id));
		if(!$result) return;
		return true;
	}
	
	public function addCategory() {
		if(!check($this->_parent)) throw new Exception(lang('error_294'));
		if(!check($this->_title)) throw new Exception(lang('error_295'));
		if($this->_parent == 0) {
			$result = $this->db->query("INSERT INTO `"._DC_SHOPCAT_."` (`title`) VALUES (?)", array($this->_title));
		} else {
			if(!$this->_categoryExists($this->_parent)) throw new Exception(lang('error_288'));
			$result = $this->db->query("INSERT INTO `"._DC_SHOPCAT_."` (`title`, `parent`) VALUES (?, ?)", array($this->_title, $this->_parent));
		}
		if(!$result) throw new Exception(lang('error_296'));
	}
	
	public function editCategory() {
		if(!check($this->_category)) throw new Exception(lang('error_297'));
		if(!check($this->_title)) throw new Exception(lang('error_298'));
		if($this->_parent == 0) {
			$result = $this->db->query("UPDATE `"._DC_SHOPCAT_."` SET `title` = ? WHERE `id` = ?", array($this->_title, $this->_category));
		} else {
			if(!$this->_categoryExists($this->_parent)) throw new Exception(lang('error_288'));
			$result = $this->db->query("UPDATE `"._DC_SHOPCAT_."` SET `title` = ?, `parent` = ? WHERE `id` = ?", array($this->_title, $this->_parent, $this->_category));
		}
		if(!$result) throw new Exception(lang('error_299'));
	}
	
	public function purchaseItem() {
		// required data
		if(!check($this->_userId)) throw new Exception(lang('error_12'));
		if(!check($this->_itemIndex)) throw new Exception(lang('error_300'));
		
		// account data
		$Account = new Account();
		$Account->setUserId($this->_userId);
		$accountInfo = $Account->getAccountData();
		if(!is_array($accountInfo)) throw new Exception(lang('error_12'));
		$accountCash = $accountInfo['cash'];
		
		// item data
		$itemInfo = $this->getItemInfo();
		if(!is_array($itemInfo)) throw new Exception(lang('error_300'));
		$itemCost = $itemInfo['cash'];
		
		// check price
		if($itemCost > $accountCash) throw new Exception(lang('error_301'));
		
		// subtract cash
		$subtractCash = $Account->subtractCash($itemCost);
		if(!$subtractCash) throw new Exception(lang('error_302'));
		
		// mail item
		try {
			$ItemMail = new ItemMail();
			$ItemMail->setAccountId($this->_userId);
			$ItemMail->setSenderName($this->_mailSenderName);
			$ItemMail->setMailSubject($this->_mailSubject);
			$ItemMail->setMailMessage($this->_mailMessage);
			$ItemMail->setItemId($itemInfo['item_id']);
			$ItemMail->setEnchantLevel($itemInfo['enchantment']);
			$ItemMail->setItemCount($itemInfo['count']);
			$ItemMail->mailItem();
		} catch(Exception $ex) {
			throw new Exception(lang('error_302'));
		}
		
		// add logs
		$this->_savePurchaseLog($accountInfo['accountName'], $itemInfo['item_id'], $itemInfo['name'], $itemInfo['count'], $itemInfo['enchantment'], $itemInfo['cash']);
	}
	
	public function getAccountPurchaseLogs() {
		if(!check($this->_username)) return;
		$result = $this->db->queryFetch("SELECT * FROM `"._DC_SHOPLOGS_."` WHERE `username` = ? ORDER BY `id` DESC", array($this->_username));
		if(!is_array($result)) return;
		return $result;
	}
	
	public function getPurchaseLogs() {
		if(check($this->_username)) {
			return $this->getAccountPurchaseLogs();
		}
		
		if($this->_logsLimit > 0) {
			$result = $this->db->queryFetch("SELECT * FROM `"._DC_SHOPLOGS_."` ORDER BY `id` DESC LIMIT ?", array($this->_logsLimit));
		} else {
			$result = $this->db->queryFetch("SELECT * FROM `"._DC_SHOPLOGS_."` ORDER BY `id` DESC");
		}
		if(!is_array($result)) return;
		return $result;
	}
	
	public function isMenuEnabled() {
		return $this->_enableMenu;
	}
	
	public function isBreadcrumbEnabled() {
		return $this->_enableBreadcrumb;
	}
	
	public function getAccountStats() {
		if(!check($this->_username)) return;
		$purchaseLogs = $this->getAccountPurchaseLogs();
		if(!is_array($purchaseLogs)) return;
		$totalPurchases = count($purchaseLogs);
		$totalSpent = 0;
		foreach($purchaseLogs as $row) {
			$totalSpent += $row['cash'];
		}
		return array(
			'purchases' => number_format($totalPurchases),
			'spent' => number_format($totalSpent)
		);
	}
	
	// PRIVATE FUNCTIONS
	
	private function _loadCategories() {
		if($this->_ignoreStatus) {
			$categories = $this->db->queryFetch("SELECT * FROM `"._DC_SHOPCAT_."` WHERE `parent` IS NULL ORDER BY `order` ASC, `id` ASC");
		} else {
			$categories = $this->db->queryFetch("SELECT * FROM `"._DC_SHOPCAT_."` WHERE `parent` IS NULL AND `status` = 1 ORDER BY `order` ASC, `id` ASC");
		}
		if(!is_array($categories)) return;
		
		foreach($categories as $category) {
			$this->_categories[$category['id']] = $category;
			if($this->_ignoreStatus) {
				$childs = $this->db->queryFetch("SELECT * FROM `"._DC_SHOPCAT_."` WHERE `parent` = ? ORDER BY `order` ASC, `id` ASC", array($category['id']));
			} else {
				$childs = $this->db->queryFetch("SELECT * FROM `"._DC_SHOPCAT_."` WHERE `parent` = ? AND `status` = 1 ORDER BY `order` ASC, `id` ASC", array($category['id']));
			}
			if(is_array($childs)) {
				foreach($childs as $child) {
					$this->_categories[$child['parent']]['childs'][$child['id']] = $child;
				}
			}
			
		}
	}
	
	private function _resultLimitStart() {
		if($this->_currentPage == 1) {
			return 0;
		} else {
			return ($this->_currentPage-1)*$this->_itemsPerPage;
		}
	}
	
	private function _categoryExists($id) {
		$result = $this->db->queryFetchSingle("SELECT * FROM `"._DC_SHOPCAT_."` WHERE `id` = ?", array($id));
		if(!is_array($result)) return;
		return true;
	}
	
	private function _getCategoryInfo($id) {
		$result = $this->db->queryFetchSingle("SELECT * FROM `"._DC_SHOPCAT_."` WHERE `id` = ?", array($id));
		if(!is_array($result)) return;
		return $result;
	}
	
	private function _getCategoryChilds($id) {
		$result = $this->db->queryFetch("SELECT * FROM `"._DC_SHOPCAT_."` WHERE `parent` = ?", array($id));
		if(!is_array($result)) return;
		return $result;
	}
	
	private function _savePurchaseLog($username, $itemId, $itemName='', $itemCount=1, $itemEnchant=0, $itemCost=0) {
		if(!check($username)) return;
		if(!check($itemId)) return;
		if(!check($itemName)) {
			$desertCoreItemData = desertCoreItemDatabase($itemId);
			$itemName = check($desertCoreItemData['name']) ? $desertCoreItemData['name'] : 'Unknown';
		}
		
		$data = array(
			$username,
			$itemId,
			$itemName,
			$itemCount,
			$itemEnchant,
			$itemCost,
			Handler::userIP()
		);
		$result = $this->db->query("INSERT INTO `"._DC_SHOPLOGS_."` (`username`, `item_id`, `name`, `count`, `enchant`, `cash`, `ip_address`, `timestamp`) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)", $data);
		if(!$result) return;
		return true;
	}
}