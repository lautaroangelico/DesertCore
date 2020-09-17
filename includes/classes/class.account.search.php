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

class AccountSearch extends Account {
	
	private $_searchTypes = array(
		'username',
		'email',
		'ip'
	);
	
	private $_type = 'username';
	private $_value;
	
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * setSearchType
	 * sets the search type
	 */
	public function setSearchType($type) {
		if(!in_array($type, $this->_searchTypes)) throw new Exception(lang('error_116'));
		$this->_type = $type;
	}
	
	/**
	 * setSearchValue
	 * sets the search value
	 */
	public function setSearchValue($value) {
		if(!check($this->_type)) throw new Exception(lang('error_117'));
		$this->_value = $value;
	}
	
	/**
	 * search
	 * searches for accounts based on the search type and value
	 */
	public function search() {
		if(!check($this->_type)) throw new Exception(lang('error_117'));
		if(!check($this->_value)) throw new Exception(lang('error_118'));
		
		switch($this->_type) {
			case 'username':
				$collection = $this->db->loginserver->accounts;
				$search = $collection->find(
					[
						'accountName' => new MongoDB\BSON\Regex($this->_value, 'i'),
					]
				);
				foreach($search as $row) {
					if(!check($row->accountName)) continue;
					
					$result[] = array('username' => $row->accountName);
				}
				break;
			case 'email':
				$collection = $this->db->loginserver->accounts;
				$search = $collection->find(
					[
						'email' => new MongoDB\BSON\Regex($this->_value, 'i'),
					]
				);
				foreach($search as $row) {
					if(!check($row->accountName)) continue;
					if(!check($row->email)) continue;
					
					$result[] = array('username' => $row->accountName, 'email' => $row->email);
				}
				break;
			case 'ip':
				$collection = $this->db->loginserver->accounts;
				$search = $collection->find(
					[
						'host' => new MongoDB\BSON\Regex($this->_value, 'i'),
					]
				);
				foreach($search as $row) {
					if(!check($row->accountName)) continue;
					if(!check($row->host)) continue;
					
					$result[] = array('username' => $row->accountName, 'host' => $row->host);
				}
				break;
			default:
				throw new Exception('Invalid search type.');
		}
		
		if(!is_array($result)) return;
		return $result;
	}
	
}