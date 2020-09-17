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
			$databaseItemDetail = 'https://bddatabase.net/us/skill/';
		}
		if($_GET['database'] == 'bdocodex') {
			$databaseName = 'bdocodex';
			$databaseIconsUrl = 'https://bdocodex.com/items/';
			$defaultIconsUrl = 'https://bdocodex.com/items/';
			$databaseItemDetail = 'https://bdocodex.com/us/skill/';
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
	if(!check($_GET['id'])) throw new Exception('missing skill id');
	if(!is_numeric($_GET['id'])) throw new Exception('skill id must be a numeric value');
	
	// item id
	$itemId = $_GET['id'];
	
	// item detail url
	$databaseItemDetailUrl = $databaseItemDetail . $itemId . '/';
	
	// get item data
	$result = $db->queryFetchSingle("SELECT * FROM `skills` WHERE `id` = ?", array($itemId));
	
	// try to get item info
	if(!is_array($result)) {
		
		try {
			$bdoDatabaseResult = file_get_contents(__API_SKILL_DATA_COLLECTOR__.'?key=9845925648&id='.$itemId.'&database=' . $databaseName);
			if(!$bdoDatabaseResult) throw new Exception();
			
			$bdoDatabaseJson = json_decode($bdoDatabaseResult);
			if(!check($bdoDatabaseJson->name)) throw new Exception();
			
			$addItem = $db->query("INSERT INTO `skills` (`id`, `name`, `class`, `icon`) VALUES (?, ?, ?, ?)", array($itemId, $bdoDatabaseJson->name, $bdoDatabaseJson->class, $bdoDatabaseJson->icon));
			if(!$addItem) throw new Exception();
			
			$result = array(
				'id' => $itemId,
				'name' => $bdoDatabaseJson->name,
				'class' => $bdoDatabaseJson->class,
				'icon' => $bdoDatabaseJson->icon
			);
		} catch(Exception $ex) {
			throw new Exception('skill not found');
		}
		
		if(!is_array($result)) throw new Exception('skill not found');
	}
	
	if(!check($result['name'])) $result['name'] = 'Unknown';
	if(!check($result['class'])) $result['class'] = 'Unknown';
	
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
		'class' => $result['class'],
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