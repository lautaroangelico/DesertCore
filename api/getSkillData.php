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

// check id
if(!isset($_GET['id'])) die();
if(!is_numeric($_GET['id'])) die();
if($_GET['id'] < 1) die();

// check key
if(!isset($_GET['key'])) die();
if(!is_numeric($_GET['key'])) die();
if($_GET['key'] != '9845925648') die();

// default database
$bdoDatabase = 'bdocodex';

// databases list
$bdoDatabaseList = array(
	'bdocodex' => array(
		'item' => 'http://bdocodex.com/tip.php?nf=on&l=us&id=skill--',
		'icon' => 'https://bdocodex.com/items/',
	),
	'bddatabase' => array(
		'item' => 'http://bddatabase.net/tip.php?nf=on&l=us&id=skill--',
		'icon' => 'https://bddatabase.net/items/',
	),
);

// custom selected database
if(isset($_GET['database'])) {
	if(!array_key_exists($_GET['database'], $bdoDatabaseList)) die();
	$bdoDatabase = $_GET['database'];
}

// collect data
$database = file_get_contents($bdoDatabaseList[$bdoDatabase]['item'] . $_GET['id']);

// result array
$result = array();
$result['id'] = $_GET['id'];
$result['database'] = $bdoDatabase;

// get skill name
$itemNamePattern = "/<b>(.*?)<\/b>/";
$itemNameSearch = preg_match($itemNamePattern, $database, $itemNameMatches);
if($itemNameSearch == 1) {
	$result['name'] = trim(strip_tags($itemNameMatches[1]));
}
if(!isset($result['name'])) die();

// get skill class
$skillClassPattern = "/<span class=\"tag_required_class\">(.*?)<\/span>/";
$skillClassSearch = preg_match($skillClassPattern, $database, $skillClassMatches);
if($skillClassSearch == 1) {
	$result['class'] = trim(strip_tags($skillClassMatches[1]));
}

// get skill icon
$itemIconPattern = "/items\/(.*?).png/";
$itemIconSearch = preg_match($itemIconPattern, $database, $itemIconMatches);
if($itemIconSearch == 1) {
	$result['icon'] = $itemIconMatches[1];
	$result['icon_full'] = $bdoDatabaseList[$bdoDatabase]['icon'] . $itemIconMatches[1] . '.png';
}

// return
echo json_encode($result);
die();