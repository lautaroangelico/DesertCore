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

// Load DesertCore
if(!@include_once(rtrim(str_replace('\\','/', dirname(__DIR__)), '/') . '/includes/webengine.php')) throw new Exception('Could not load DesertCore.');

try {
	if(!config('login_api_enabled')) throw new Exception(lang('error_274'));
	if(!isset($_REQUEST['key'])) throw new Exception(lang('error_272'));
	if($_REQUEST['key'] != config('login_api_private_key')) throw new Exception(lang('error_272'));
	$Login = new AccountLogin();
	if(isset($_REQUEST['username'])) $Login->setUsername($_REQUEST['username']);
	if(isset($_REQUEST['email'])) $Login->setEmail($_REQUEST['email']);
	if(isset($_REQUEST['password'])) $Login->setPassword($_REQUEST['password']);
	$Login->loginApi();
} catch(Exception $ex) {
	http_response_code(500); 
    echo json_encode(array('code' => 500, 'error' => $ex->getMessage()));
}