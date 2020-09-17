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

class PayPal {
	
	private $_titleMinLen = 1;
	private $_titleMaxLen = 50;
	
	protected $_id;
	
	protected $_title;
	protected $_credits;
	protected $_cost;
	
	protected $_cfg;
	
	function __construct() {
		
		// offline mode
		if(config('offline_mode')) throw new Exception(lang('offline_mode_error'));
		
		// database object
		$this->we = Handler::loadDB('WebEngine');
		
		// configs
		$this->_cfg = loadConfig('paypal');
		if(!is_array($this->_cfg)) throw new Exception(lang('error_66'));
		
	}
	
	/**
	 * setId
	 * 
	 */
	public function setId($id) {
		if(!Validator::UnsignedNumber($id)) throw new Exception(lang('error_239'));
		$this->_id = $id;
	}
	
	/**
	 * setTitle
	 * 
	 */
	public function setTitle($title) {
		if(!Validator::Length($title, $this->_titleMaxLen, $this->_titleMinLen)) throw new Exception(lang('error_240'));
		$this->_title = $title;
	}
	
	/**
	 * setCredits
	 * 
	 */
	public function setCredits($credits) {
		if(!Validator::UnsignedNumber($credits)) throw new Exception(lang('error_241'));
		$this->_credits = $credits;
	}
	
	/**
	 * setCost
	 * 
	 */
	public function setCost($cost) {
		if(!Validator::UnsignedNumber($cost)) throw new Exception(lang('error_242'));
		$this->_cost = $cost;
	}
	
	/**
	 * addPackage
	 * 
	 */
	public function addPackage() {
		if(!check($this->_title)) throw new Exception(lang('error_243'));
		if(!check($this->_credits)) throw new Exception(lang('error_243'));
		if(!check($this->_cost)) throw new Exception(lang('error_243'));
		
		$data = array(
			'title' => $this->_title,
			'credits' => $this->_credits,
			'cost' => $this->_cost
		);
		
		$query = "INSERT INTO `"._WE_PAYPALPACKAGES_."` (`title`, `credits`, `cost`) VALUES (:title, :credits, :cost)";
		
		$addPackage = $this->we->query($query, $data);
		if(!$addPackage) throw new Exception(lang('error_244'));
	}
	
	/**
	 * updatePackage
	 * 
	 */
	public function updatePackage() {
		if(!check($this->_id)) throw new Exception(lang('error_245'));
		if(!check($this->_title)) throw new Exception(lang('error_245'));
		if(!check($this->_credits)) throw new Exception(lang('error_245'));
		if(!check($this->_cost)) throw new Exception(lang('error_245'));
		
		$packageInfo = $this->getPackageInfo();
		if(!$packageInfo) throw new Exception(lang('error_246'));
		
		$data = array(
			'id' => $this->_id,
			'title' => $this->_title,
			'credits' => $this->_credits,
			'cost' => $this->_cost
		);
		
		$query = "UPDATE `"._WE_PAYPALPACKAGES_."` SET `title` = :title, `credits` = :credits, `cost` = :cost WHERE `id` = :id";
		
		$updatePackage = $this->we->query($query, $data);
		if(!$updatePackage) throw new Exception(lang('error_247'));
	}
	
	/**
	 * deletePackage
	 * 
	 */
	public function deletePackage() {
		if(!check($this->_id)) throw new Exception(lang('error_248'));
		$deletePackage = $this->we->query("DELETE FROM `"._WE_PAYPALPACKAGES_."` WHERE `id` = ?", array($this->_id));
		if(!$deletePackage) throw new Exception(lang('error_249'));
	}
	
	/**
	 * getPackageInfo
	 * 
	 */
	public function getPackageInfo() {
		if(!check($this->_id)) return;
		$packageInfo = $this->we->queryFetchSingle("SELECT * FROM `"._WE_PAYPALPACKAGES_."` WHERE `id` = ?", array($this->_id));
		if(!is_array($packageInfo)) return;
		return $packageInfo;
	}
	
	/**
	 * getPackagesList
	 * 
	 */
	public function getPackagesList() {
		$packagesList = $this->we->queryFetch("SELECT * FROM `"._WE_PAYPALPACKAGES_."` ORDER BY `id` ASC");
		if(!is_array($packagesList)) return;
		return $packagesList;
	}
	
	/**
	 * processPayment
	 * 
	 */
	public function processPayment($data) {
		if(!is_array($data)) return;
		
		$custom = explode(',', $data['custom']);
		if(!is_array($custom)) throw new Exception('PayPal: invalid custom data.');
		
		$packageid = $custom[0];
		if(!Validator::UnsignedNumber($packageid)) throw new Exception('PayPal: invalid package id.');
		
		$userid = $custom[1];
		if(!Validator::UnsignedNumber($userid)) throw new Exception('PayPal: invalid user id.');
		
		// payment status
		if($data['payment_status'] != 'Completed') {
			$this->_processRefund($data, $userid, $packageid);
			return;
		}
		
		// check package
		$this->setId($packageid);
		$packageInfo = $this->getPackageInfo();
		if(!is_array($packageInfo)) throw new Exception('PayPal: invalid package id.');
		
		$packageCost = number_format($packageInfo['cost'], 2);
		$paymentGross = number_format($data['payment_gross'], 2);
		
		// payment currency
		if(strtolower($data['mc_currency']) != strtolower($this->_cfg['currency'])) throw new Exception('PayPal: payment currency is not valid.');
		
		// package cost
		if($packageCost != $paymentGross) throw new Exception('PayPal: payment gross and package cost don\'t match.');
		
		// account data
		$Account = new Account();
		$Account->setUserid($userid);
		$accountData = $Account->getAccountData();
		if(!is_array($accountData)) throw new Exception(lang('error_12'));
		
		// send credits
		try {
			
			$Account->addCash($packageInfo['credits']);
			
		} catch(Exception $ex) {
			// TODO: log system
			throw new Exception($ex->getMessage());
		}
		
		// save log
		$this->_saveLog($data, $userid, $packageid);
		
	}
	
	/**
	 * getLogs
	 * 
	 */
	public function getLogs() {
		$logs = $this->we->queryFetch("SELECT * FROM `"._WE_PAYPALLOGS_."` ORDER BY `id` DESC");
		if(!is_array($logs)) return;
		return $logs;
	}
	
	/**
	 * _processRefund
	 * 
	 */
	private function _processRefund($data, $userid, $packageid) {
		if(!check($data)) return;
		if(!check($userid)) return;
		if(!check($packageid)) return;
		
		// check package
		$this->setId($packageid);
		$packageInfo = $this->getPackageInfo();
		if(!is_array($packageInfo)) throw new Exception('PayPal: invalid package id.');
		
		$packageCost = number_format($packageInfo['cost'], 2);
		
		// account data
		$Account = new Account();
		$Account->setUserid($userid);
		$accountData = $Account->getAccountData();
		if(!is_array($accountData)) throw new Exception(lang('error_12'));
		
		// subtract credits
		try {
			
			$Account->subtractCash($packageInfo['credits']);
			
		} catch(Exception $ex) {
			// TODO: log system
			throw new Exception($ex->getMessage());
		}
		
		// update log
		$this->_updateLog($data);
	}
	
	/**
	 * _saveLog
	 * 
	 */
	private function _saveLog($data, $userid, $packageid) {
		$logData = array(
			'txnid' => $data['txn_id'],
			'payeremail' => $data['payer_email'],
			'userid' => $userid,
			'packageid' => $packageid,
			'paymentgross' => $data['payment_gross'],
			'paymentdate' => $data['payment_date'],
			'itemname' => $data['item_name'],
			'paymentstatus' => $data['payment_status']
		);
		
		$query = "INSERT INTO `"._WE_PAYPALLOGS_."` (`txn_id`, `payer_email`, `userid`, `packageid`, `payment_gross`, `payment_date`, `item_name`, `payment_status`) VALUES (:txnid, :payeremail, :userid, :packageid, :paymentgross, :paymentdate, :itemname, :paymentstatus)";
		
		$log = $this->we->query($query, $logData);
		if(!$log) return;
	}
	
	/**
	 * _updateLog
	 * 
	 */
	private function _updateLog($data) {
		if(!check($data['parent_txn_id'])) return;
		
		$logData = array(
			'txnid' => $data['parent_txn_id'],
			'paymentstatus' => $data['payment_status']
		);
		
		$query = "UPDATE `"._WE_PAYPALLOGS_."` SET `payment_status` = :paymentstatus WHERE `txn_id` = :txnid";
		
		$log = $this->we->query($query, $logData);
		if(!$log) return;
	}
	
}