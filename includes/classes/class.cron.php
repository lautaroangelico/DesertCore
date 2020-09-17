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

class Cron {
	
	private $_cronPath;
	
	private $_nameMaxLen = 100;
	private $_descMaxLen = 100;
	private $_fileMaxLen = 50;
	private $_repeatMinValue = 60;
	private $_repeatMaxValue = 99999999999;
	private $_forbiddenFileNames = array('cron.php', '.', '..', 'error_log');
	private $_commonCronTimes = array(
		60 => '1 minute (60 sec)',
		300 => '5 minutes (300 sec)',
		600 => '10 minutes (600 sec)',
		900 => '15 minutes (900 sec)',
		1800 => '30 minutes (1,800 sec)',
		3600 => '1 hour (3,600 sec)',
		21600 => '6 hours (21,600 sec)',
		43200 => '12 hours (43,200 sec)',
		86400 => '1 day (86,400 sec)',
		604800 => '7 days (604,800 sec)',
		1296000 => '15 days (1,296,000 sec)',
		2592000 => '1 month (2,592,000 sec)',
		7776000 => '3 months (7,776,000 sec)',
		15552000 => '6 months (15,552,000 sec)',
		31104000 => '1 year (31,104,000 sec)',
	);
	
	private $_id;
	private $_name;
	private $_description;
	private $_file;
	private $_repeat;
	private $_lastRun;
	private $_status;
	
	private $_checkConectionStatus = false;
	
	function __construct() {
		
		// cron path
		$this->_cronPath = __PATH_CRON__;
		
		// database object
		$this->db = Handler::loadDB('WebEngine');
		
	}
	
	/**
	 * setId
	 * sets the cron id
	 */
	public function setId($id) {
		if(!Validator::UnsignedNumber($id)) throw new Exception(lang('error_147'));
		$this->_id = $id;
	}
	
	/**
	 * setName
	 * sets the cron name
	 */
	public function setName($name) {
		if(!Validator::Length($name, $this->_nameMaxLen, 0)) throw new Exception(lang('error_148'));
		$this->_name = $name;
	}
	
	/**
	 * setDescription
	 * sets the cron description
	 */
	public function setDescription($description) {
		if(!Validator::Length($description, $this->_descMaxLen, 0)) throw new Exception(lang('error_149'));
		$this->_description = $description;
	}
	
	/**
	 * setFile
	 * sets the cron file to execute
	 */
	public function setFile($file) {
		if(!Validator::Length($description, $this->_fileMaxLen, 0)) throw new Exception(lang('error_150'));
		if(!$this->_cronFileExists($file)) throw new Exception(lang('error_151'));
		if(in_array($file, $this->_forbiddenFileNames)) throw new Exception(lang('error_152'));
		$this->_file = $file;
	}
	
	/**
	 * setRepeat
	 * sets the repeat time (in seconds) of a cron
	 */
	public function setRepeat($repeat) {
		if(!Validator::UnsignedNumber($repeat)) throw new Exception(lang('error_153'));
		if(!Validator::Number($repeat, $this->_repeatMaxValue, $this->_repeatMinValue)) throw new Exception(lang('error_154'));
		$this->_repeat = $repeat;
	}
	
	/**
	 * getCronData
	 * gets the cron data from the database using the id
	 */
	public function getCronData() {
		if(!check($this->_id)) throw new Exception(lang('error_155'));
		return $this->_loadCronData();
	}
	
	/**
	 * addCron
	 * adds a new cron task to the database
	 */
	public function addCron() {
		if(!check($this->_name)) throw new Exception(lang('error_4'));
		if(!check($this->_file)) throw new Exception(lang('error_4'));
		if(!check($this->_repeat)) throw new Exception(lang('error_4'));
		
		if($this->_cronExists($this->_file)) throw new Exception(lang('error_156'));
		if(!$this->_cronFileExists($this->_file)) throw new Exception(lang('error_151'));
		
		$description = check($this->_description) ? $this->_description : '';
		
		$insertData = array(
			'name' => $this->_name,
			'desc' => $description,
			'file' => $this->_file,
			'repeat' => $this->_repeat
		);
		
		$query = "INSERT INTO "._WE_CRON_." (`cron_name`, `cron_description`, `cron_file`, `cron_repeat`) VALUES (:name, :desc, :file, :repeat)";
		
		$result = $this->db->query($query, $insertData);
		if(!$result) throw new Exception(lang('error_157'));
	}
	
	/**
	 * editCron
	 * edits a cron task to the database
	 */
	public function editCron() {
		if(!check($this->_id)) throw new Exception(lang('error_4'));
		if(!check($this->_name)) throw new Exception(lang('error_4'));
		if(!check($this->_file)) throw new Exception(lang('error_4'));
		if(!check($this->_repeat)) throw new Exception(lang('error_4'));
		
		if(!$this->_cronExists($this->_file)) throw new Exception(lang('error_158'));
		if(!$this->_cronFileExists($this->_file)) throw new Exception(lang('error_151'));
		
		$description = check($this->_description) ? $this->_description : '';
		
		$insertData = array(
			'name' => $this->_name,
			'desc' => $description,
			'file' => $this->_file,
			'repeat' => $this->_repeat,
			'id' => $this->_id
		);
		
		$query = "UPDATE "._WE_CRON_." SET `cron_name` = :name, `cron_description` = :desc, `cron_file` = :file, `cron_repeat` = :repeat WHERE `cron_id` = :id";
		
		$result = $this->db->query($query, $insertData);
		if(!$result) throw new Exception(lang('error_159'));
	}
	
	/**
	 * removeCron
	 * removes a cron task from the database
	 */
	public function removeCron() {
		if(!check($this->_id)) throw new Exception(lang('error_160'));
		if(!is_array($this->_loadCronData())) throw new Exception(lang('error_161'));
		if(!$this->_deleteCron()) throw new Exception(lang('error_162'));
	}
	
	/**
	 * toggleCronStatus
	 * enables or disables a cron
	 */
	public function toggleCronStatus() {
		if(!check($this->_id)) throw new Exception(lang('error_163'));
		if(!is_array($this->_loadCronData())) throw new Exception(lang('error_164'));
		if(!$this->_toggleStatus()) throw new Exception(lang('error_165'));
	}
	
	/**
	 * executeCrons
	 * checks which crons need to be executed and runs them
	 */
	public function executeCrons($showExecutionMessages=true) {
		
		$cronList = $this->_loadActiveCronData();
		if(!is_array($cronList)) throw new Exception(lang('error_166'));
		
		foreach($cronList as $cronData) {
			if(check($cronData['cron_last_run'])) {
				$lastRunTimestamp = strtotime(databaseTime($cronData['cron_last_run']));
				$nextRunTimestamp = $lastRunTimestamp + $cronData['cron_repeat'];
				if($nextRunTimestamp > time()) continue;
			}
			try {
				
				if($showExecutionMessages) debug('[Cron] Execution Begins ('.$cronData['cron_file'].')');
				$this->_loadCronFile($cronData['cron_file']);
				$this->_updateCronLastRun($cronData['cron_id']);
				if($showExecutionMessages) debug('[Cron] Execution End');
				
			} catch(Exception $ex) {
				// TODO: log system
			}
		}
		
		
	}
	
	/**
	 * getCronFileList
	 * gets the cron file list from the crons path
	 */
	public function getCronFileList() {
		$dir = opendir(__PATH_CRON__);
		while(($file = readdir($dir)) !== false) {
			if(filetype(__PATH_CRON__ . $file) == "file" && $file != ".htaccess" && $file != "cron.php") {
				$result[] = $file;
			}
		}
		closedir($dir);
		return $result;
	}
	
	/**
	 * getCronList
	 * gets the full list of crons from the database
	 */
	public function getCronList() {
		$result = $this->db->queryFetch("SELECT * FROM "._WE_CRON_." ORDER BY `cron_id` ASC");
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * getCommonCronTimes
	 * returns the common cron repeat times list
	 */
	public function getCommonCronTimes() {
		return $this->_commonCronTimes;
	}
	
	/**
	 * _loadCronData
	 * returns the cron data from the database using the id
	 */
	private function _loadCronData() {
		if(!check($this->_id)) return;
		$result = $this->db->queryFetchSingle("SELECT * FROM "._WE_CRON_." WHERE `cron_id` = ?", array($this->_id));
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * _cronFileExists
	 * checks if the cron file exists in the filesystem
	 */
	private function _cronFileExists($file) {
		if(file_exists($this->_cronPath . $file)) return true;
		return;
	}
	
	/**
	 * _cronExists
	 * checks if a cron already exists in the database
	 */
	private function _cronExists($file) {
		$result = $this->db->queryFetchSingle("SELECT * FROM "._WE_CRON_." WHERE `cron_file` = ?", array($file));
		if(is_array($result)) return true;
		return;
	}
	
	/**
	 * _deleteCron
	 * deletes cron from the database
	 */
	private function _deleteCron() {
		if(!check($this->_id)) return;
		$result = $this->db->query("DELETE FROM "._WE_CRON_." WHERE `cron_id` = ?", array($this->_id));
		if($result) return true;
		return;
	}
	
	/**
	 * _toggleStatus
	 * toggles the status of a cron
	 */
	private function _toggleStatus() {
		if(!check($this->_id)) return;
		$cronData = $this->_loadCronData();
		if(!is_array($cronData)) return;
		$newStatus = $cronData['cron_status'] == 1 ? 0 : 1;
		$result = $this->db->query("UPDATE "._WE_CRON_." SET `cron_status` = ? WHERE `cron_id` = ?", array($newStatus, $this->_id));
		if($result) return true;
		return;
	}
	
	/**
	 * _loadActiveCronData
	 * returns data of all active crons from the database
	 */
	private function _loadActiveCronData() {
		$result = $this->db->queryFetch("SELECT * FROM "._WE_CRON_." WHERE `cron_status` = 1 ORDER BY `cron_id` ASC");
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * _loadCronFile
	 * loads a cron file
	 */
	private function _loadCronFile($file) {
		if(!$this->_cronFileExists($file)) return;
		include($this->_cronPath . $file);
	}
	
	/**
	 * _updateCronLastRun
	 * updates the last run time of a cron
	 */
	private function _updateCronLastRun($id) {
		if(!check($id)) return;
		$result = $this->db->query("UPDATE "._WE_CRON_." SET `cron_last_run` = CURRENT_TIMESTAMP WHERE `cron_id` = ?", array($id));
		if($result) return true;
		return;
	}
	
	/**
	 * _checkDatabaseConnection
	 * checks the connection status to the databases and sets the website in online/offline mode
	 */
	private function _checkDatabaseConnection() {
		
	}
	
}