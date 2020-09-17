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

// Load PayPal Configurations
$cfg = loadConfig('paypal');
if(!is_array($cfg)) {
	header("HTTP/1.1 500 Internal Server Error");
	die();
}

// PayPal Sandbox
$enable_sandbox = $cfg['sandbox'];

// PayPal Seller Email
$seller_email = $cfg['seller_email'];

$ipn = new PaypalIPN();
if ($enable_sandbox) {
    $ipn->useSandbox();
}
$verified = $ipn->verifyIPN();

// IPN
$paypal_ipn_status = "VERIFICATION FAILED";
if ($verified) {
	try {
		
		// Check receiver email
		if(strtolower($_POST["receiver_email"]) != strtolower($seller_email)) throw new Exception('RECEIVER EMAIL MISMATCH');
		
		// TODO: log system
		$paypal_ipn_status = "Completed Successfully";
		
		// Process payment
		try {
			
			$PayPal = new PayPal();
			$PayPal->processPayment($_POST);
			
		} catch(Exception $ex) {
			$paypal_ipn_status = $ex->getMessage();
		}
		
	} catch(Exception $ex) {
		// TODO: log system
		$paypal_ipn_status = $ex->getMessage();
	}
} elseif ($enable_sandbox) {
    if ($_POST["test_ipn"] != 1) {
        $paypal_ipn_status = "RECEIVED FROM LIVE WHILE SANDBOXED";
		// TODO: log system
    }
} elseif ($_POST["test_ipn"] == 1) {
    $paypal_ipn_status = "RECEIVED FROM SANDBOX WHILE LIVE";
	// TODO: log system
}

// Reply with an empty 200 response to indicate to paypal the IPN was received correctly
header("HTTP/1.1 200 OK");