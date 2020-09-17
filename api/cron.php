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
	define('access', 'api');
	
	// Load WebEngine
	if(!@include_once(rtrim(str_replace('\\','/', dirname(__DIR__)), '/') . '/includes/webengine.php')) throw new Exception('Could not load WebEngine.');
	
	// Cron API Status
	if(!config('cron_api_enabled')) throw new Exception(lang('error_226'));
	
	// Private Key
	if(!check($_GET['key'])) throw new Exception(lang('error_227'));
	if(config('cron_api_private_key') != $_GET['key']) throw new Exception(lang('error_227'));
	
	// Cron System
	$Cron = new Cron();
	$Cron->executeCrons(false);
	
	http_response_code(200);
	echo json_encode(array('code' => 200, 'message' => lang('error_228')));
	
} catch(Exception $ex) {
	http_response_code(500);
	echo json_encode(array('code' => 500, 'error' => $ex->getMessage()));
}