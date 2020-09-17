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
	
	if(!check($_GET['package'])) throw new Exception('Package id not provided.');
	
	$PayPal = new PayPal();
	$PayPal->setId($_GET['package']);
	$PayPal->deletePackage();
	
	redirect('paypal/packages');
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}