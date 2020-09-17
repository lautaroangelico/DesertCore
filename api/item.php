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

// Access
define('access', 'api');

// Load WebEngine
if(!@include_once(rtrim(str_replace('\\','/', dirname(__DIR__)), '/') . '/includes/webengine.php')) throw new Exception('Could not load WebEngine.');

try {
	
	// database object
	$db = Handler::loadDB('BDOData');
	
	// stuff
	$imagesFilePath = __PATH_STATIC_CONTENT__;
	$localImgUrl = __STATIC_CONTENT_BASE_URL__;
	$databaseName = 'bdocodex';
	$databaseIconsUrl = 'https://bdocodex.com/items/';
	$databaseItemDetail = 'https://bdocodex.com/us/item/';
	$prettyPrint = false;
	
	// default icons source
	$defaultIconsUrl = $localImgUrl;
	
	// switch database
	if(check($_GET['database'])) {
		if($_GET['database'] == 'bddatabase') {
			$databaseName = 'bddatabase';
			$databaseIconsUrl = 'https://bddatabase.net/items/';
			$defaultIconsUrl = 'https://bddatabase.net/items/';
			$databaseItemDetail = 'https://bddatabase.net/us/item/';
		}
		if($_GET['database'] == 'bdocodex') {
			$databaseName = 'bdocodex';
			$databaseIconsUrl = 'https://bdocodex.com/items/';
			$defaultIconsUrl = 'https://bdocodex.com/items/';
			$databaseItemDetail = 'https://bdocodex.com/us/item/';
		}
	}
	
	// pretty print
	if(check($_GET['pretty'])) {
		if($_GET['pretty'] == 1) {
			$prettyPrint = true;
		}
		if($_GET['pretty'] == 0) {
			$prettyPrint = false;
		}
	}
	
	// check id
	if(!check($_GET['id'])) throw new Exception('missing item id');
	if(!is_numeric($_GET['id'])) throw new Exception('item id must be a numeric value');
	
	// item id
	$itemId = $_GET['id'];
	
	// item detail url
	$databaseItemDetailUrl = $databaseItemDetail . $itemId . '/';
	
	// get item data
	$result = $db->queryFetchSingle("SELECT * FROM `items` WHERE `id` = ?", array($itemId));
	
	// try to get item info
	if(!is_array($result)) {
		
		try {
			$bdoDatabaseResult = file_get_contents(__API_ITEM_DATA_COLLECTOR__.'?key=9845925648&id='.$itemId.'&database=' . $databaseName);
			if(!$bdoDatabaseResult) throw new Exception();
			
			$bdoDatabaseJson = json_decode($bdoDatabaseResult);
			if(!check($bdoDatabaseJson->name)) throw new Exception();
			if(!check($bdoDatabaseJson->grade)) throw new Exception();
			if(!check($bdoDatabaseJson->icon)) throw new Exception();
			
			$addItem = $db->query("INSERT INTO `items` (`id`, `name`, `grade`, `icon`) VALUES (?, ?, ?, ?)", array($itemId, $bdoDatabaseJson->name, $bdoDatabaseJson->grade, $bdoDatabaseJson->icon));
			if(!$addItem) throw new Exception();
			
			$result = array(
				'id' => $itemId,
				'name' => $bdoDatabaseJson->name,
				'grade' => $bdoDatabaseJson->grade,
				'icon' => $bdoDatabaseJson->icon
			);
		} catch(Exception $ex) {
			throw new Exception('item not found');
		}
		
		if(!is_array($result)) throw new Exception('item not found');
	}
	
	if(!check($result['name'])) $result['name'] = 'Unknown';
	if(!check($result['grade'])) $result['grade'] = 0;
	
	if(check($result['icon'])) {
		
		if(!file_exists($imagesFilePath . $result['icon'] . '.png')) {
			$saveImage = saveImage($result['icon'], $databaseIconsUrl);
			if($saveImage) {
				$result['icon'] = $defaultIconsUrl . $result['icon'] . '.png';
			} else {
				$result['icon'] = $databaseIconsUrl . $result['icon'] . '.png';
			}
		} else {
			$result['icon'] = $defaultIconsUrl . $result['icon'] . '.png';
		}
		
	} else {
		$result['icon'] = __STATIC_CONTENT_BASE_URL__ . 'blank.png';
	}
	
	$data = array(
		'id' => $result['id'],
		'name' => trim($result['name']),
		'grade' => $result['grade'],
		'icon' => $result['icon'],
		'url' => $databaseItemDetailUrl
	);
	
	http_response_code(200);
	header('Content-Type: application/json');
	if($prettyPrint) {
		echo json_encode(array('code' => 200, 'message' => '', 'data' => $data), JSON_PRETTY_PRINT);
	} else {
		echo json_encode(array('code' => 200, 'message' => '', 'data' => $data));
	}
	die();
	
} catch(Exception $ex) {
	http_response_code(500);
	header('Content-Type: application/json');
	if($prettyPrint) {
		echo json_encode(array('code' => 500, 'message' => $ex->getMessage()), JSON_PRETTY_PRINT);
	} else {
		echo json_encode(array('code' => 500, 'message' => $ex->getMessage()));
	}
	die();
	
}