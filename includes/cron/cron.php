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
	
	// Access
	define('access', 'cron');
	
	// Load WebEngine
	include_once(str_replace('\\','/',dirname(dirname(__FILE__))).'/' . 'webengine.php');

	// Cron System
	$Cron = new Cron();
	$Cron->executeCrons();
	
} catch(Exception $ex) {
	// TODO: logs system
	die($ex->getMessage());
}