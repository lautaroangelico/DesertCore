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
define('access', 'index');

try {
	
	// Load DesertCore CMS
	if(!@include_once(rtrim(str_replace('\\','/', __DIR__), '/') . '/includes/webengine.php')) throw new Exception('Could not load DesertCore CMS.');
	
} catch (Exception $ex) {
	
	$errorPage = file_get_contents(rtrim(str_replace('\\','/', __DIR__), '/') . '/includes/error.html');
	echo str_replace("{ERROR_MESSAGE}", $ex->getMessage(), $errorPage);
	
}
