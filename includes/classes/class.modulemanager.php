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

class ModuleManager {
	
	private $_moduleConfigPath;
	private $_modulesPath;
	
	private $_parentAllowedChars = array('a-z', 'A-Z', '0-9', '\/', '-');
	private $_fileAllowedChars = array('a-z', 'A-Z', '0-9', '-');
	private $_configFileAllowedChars = array('a-z', 'A-Z', '0-9', '.');
	private $_configModuleAllowedChars = array('a-z', 'A-Z', '0-9', '.');
	private $_allowedAccess = array('guest', 'user', 'all');
	private $_allowedTypes = array('dynamic', 'static');
	
	private $_parentMaxLen = 200;
	private $_fileMaxLen = 50;
	private $_titleMaxLen = 50;
	private $_templateMaxLen = 50;
	private $_pluginMaxLen = 50;
	private $_configFileMaxLen = 50;
	private $_configModuleMaxLen = 50;
	
	private $_configFileExtension = '.json';
	private $_configModulePrefix = 'settings.';
	private $_configModuleExtension = '.php';
	
	private $_id;
	private $_parent = null;
	private $_file;
	private $_title = null;
	private $_access;
	private $_type;
	private $_template = null;
	private $_sidebar = 1;
	private $_plugin = null;
	private $_configFile = null;
	private $_configModule = null;
	private $_status = 1;
	
	function __construct() {
		
		// module settings path
		$this->_moduleConfigPath = __PATH_ADMINCP_MODULE_SETTINGS__;
		
		// modules path
		$this->_modulesPath = __PATH_MODULES__;
		
		// database object
		$this->we = Handler::loadDB('WebEngine');
		
	}
	
	/**
	 * setId
	 * 
	 */
	public function setId($id) {
		if(!Validator::UnsignedNumber($id)) throw new Exception(lang('error_178'));
		$this->_id = $id;
	}
	
	/**
	 * setParent
	 * 
	 */
	public function setParent($parent) {
		if(!Validator::Chars($parent, $this->_parentAllowedChars)) throw new Exception(lang('error_179'));
		if(!Validator::Length($parent, $this->_parentMaxLen, 1)) throw new Exception(lang('error_180'));
		$this->_parent = Filter::RemoveTrailingSlash(strtolower($parent));
	}
	
	/**
	 * setFile
	 * 
	 */
	public function setFile($file) {
		if(!Validator::Chars($file, $this->_fileAllowedChars)) throw new Exception(lang('error_181'));
		if(!Validator::Length($file, $this->_fileMaxLen, 1)) throw new Exception(lang('error_182'));
		$this->_file = strtolower($file);
	}
	
	/**
	 * setTitle
	 * 
	 */
	public function setTitle($phrase) {
		if(!Validator::Length($phrase, $this->_titleMaxLen, 1)) throw new Exception(lang('error_183'));
		$this->_title = $phrase;
	}
	
	/**
	 * setAccess
	 * 
	 */
	public function setAccess($access) {
		if(!in_array($access, $this->_allowedAccess)) throw new Exception(lang('error_184'));
		$this->_access = strtolower($access);
	}
	
	/**
	 * setType
	 * 
	 */
	public function setType($type) {
		if(!in_array($type, $this->_allowedTypes)) throw new Exception(lang('error_185'));
		$this->_type = strtolower($type);
	}
	
	/**
	 * setTemplate
	 * 
	 */
	public function setTemplate($template) {
		if(!Validator::Length($template, $this->_templateMaxLen, 1)) throw new Exception(lang('error_186'));
		$this->_template = $template;
	}
	
	/**
	 * setSidebar
	 * 
	 */
	public function setSidebar($status) {
		if(!in_array($status, array(0, 1))) throw new Exception(lang('error_187'));
		$this->_sidebar = $status;
	}
	
	/**
	 * setPlugin
	 * 
	 */
	public function setPlugin($plugin) {
		if(!Validator::Length($plugin, $this->_pluginMaxLen, 1)) throw new Exception(lang('error_188'));
		$this->_plugin;
	}
	
	/**
	 * setConfigFile
	 * 
	 */
	public function setConfigFile($file) {
		if(!Validator::Chars($file, $this->_configFileAllowedChars)) throw new Exception(lang('error_189'));
		if(!Validator::Length($file, $this->_configFileMaxLen, 1)) throw new Exception(lang('error_190'));
		$this->_configFile = $file;
	}
	
	/**
	 * setConfigModule
	 * 
	 */
	public function setConfigModule($file) {
		if(!Validator::Chars($file, $this->_configModuleAllowedChars)) throw new Exception(lang('error_191'));
		if(!Validator::Length($file, $this->_configModuleMaxLen, 1)) throw new Exception(lang('error_192'));
		$this->_configModule = $file;
	}
	
	/**
	 * setStatus
	 * 
	 */
	public function setStatus($status) {
		if(!in_array($status, array(0, 1))) throw new Exception(lang('error_193'));
		$this->_status = $status;
	}
	
	/**
	 * getModuleList
	 * 
	 */
	public function getModuleList() {
		$result = $this->we->queryFetch("SELECT * FROM "._WE_MODULES_." ORDER BY `parent` ASC, `id` ASC");
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * loadModuleData
	 * 
	 */
	public function loadModuleData() {
		if(!check($this->_id)) throw new Exception(lang('error_194'));
		$result = $this->we->queryFetchSingle("SELECT * FROM "._WE_MODULES_." WHERE `id` = ?", array($this->_id));
		if(!is_array($result)) throw new Exception(lang('error_195'));
		return $result;
	}
	
	/**
	 * loadConfigModule
	 * 
	 */
	public function loadConfigModule() {
		if(!check($this->_configModule)) throw new Exception(lang('error_196'));
		if(!$this->_configModuleExists()) throw new Exception(lang('error_196'));
		if(!@include_once($this->_moduleConfigPath . $this->_configModulePrefix . $this->_configModule . $this->_configModuleExtension)) throw new Exception(lang('error_196'));
	}
	
	/**
	 * deleteModule
	 * 
	 */
	public function deleteModule() {
		if(!check($this->_id)) throw new Exception(lang('error_197'));
		
		$moduleData = $this->loadModuleData();
		if(!is_array($moduleData)) throw new Exception(lang('error_197'));
		
		$deleteModule = $this->we->query("DELETE FROM "._WE_MODULES_." WHERE `id` = ?", array($this->_id));
		if(!$deleteModule) throw new Exception(lang('error_197'));
	}
	
	/**
	 * editModule
	 * 
	 */
	public function editModule() {
		if(!check($this->_id)) throw new Exception(lang('error_198'));
		
		$moduleData = $this->loadModuleData();
		if(!is_array($moduleData)) throw new Exception(lang('error_195'));
		
		if(!check($this->_file)) throw new exception(lang('error_198'));
		if(!check($this->_access)) throw new exception(lang('error_198'));
		if(!check($this->_type)) throw new exception(lang('error_198'));
		if(!check($this->_sidebar)) throw new exception(lang('error_198'));
		if(!check($this->_status)) throw new exception(lang('error_198'));
		
		$data = array(
			'id' => $this->_id,
			'parent' => $this->_parent,
			'file' => $this->_file,
			'title' => $this->_title,
			'access' => $this->_access,
			'type' => $this->_type,
			'template' => $this->_template,
			'sidebar' => $this->_sidebar,
			'plugin' => $this->_plugin,
			'cfgfile' => $this->_configFile,
			'cfgmodule' => $this->_configModule,
			'status' => $this->_status
		);
		
		$query = "UPDATE "._WE_MODULES_." SET ";
			$query .= "`parent` = :parent, ";
			$query .= "`file` = :file, ";
			$query .= "`title` = :title, ";
			$query .= "`access` = :access, ";
			$query .= "`type` = :type, ";
			$query .= "`template` = :template, ";
			$query .= "`sidebar` = :sidebar, ";
			$query .= "`plugin` = :plugin, ";
			$query .= "`config_file` = :cfgfile, ";
			$query .= "`config_module` = :cfgmodule, ";
			$query .= "`status` = :status ";
			$query .= "WHERE `id` = :id";
		
		$editModule = $this->we->query($query, $data);
		if(!$editModule) throw new Exception(lang('error_198'));
	}
	
	/**
	 * createModule
	 * 
	 */
	public function createModule() {
		if(!check($this->_file)) throw new exception(lang('error_199'));
		if(!check($this->_access)) throw new exception(lang('error_199'));
		if(!check($this->_type)) throw new exception(lang('error_199'));
		if(!check($this->_sidebar)) throw new exception(lang('error_199'));
		if(!check($this->_status)) throw new exception(lang('error_199'));
		
		$checkDuplicateQuery = check($this->_parent) ? "SELECT * FROM "._WE_MODULES_." WHERE `parent` = ? AND `file` = ?" : "SELECT * FROM "._WE_MODULES_." WHERE `parent` IS NULL AND `file` = ?";
		$checkDuplicateData = check($this->_parent) ? array($this->_parent, $this->_file) : array($this->_file);
		$checkDuplicate = $this->we->queryFetchSingle($checkDuplicateQuery, $checkDuplicateData);
		if(is_array($checkDuplicate)) throw new Exception(lang('error_199'));
		
		$data = array(
			'parent' => $this->_parent,
			'file' => $this->_file,
			'title' => $this->_title,
			'access' => $this->_access,
			'type' => $this->_type,
			'template' => $this->_template,
			'sidebar' => $this->_sidebar,
			'status' => $this->_status
		);
		
		$query = "INSERT INTO "._WE_MODULES_." (`parent`, `file`, `title`, `access`, `type`, `template`, `sidebar`, `status`) VALUES (:parent, :file, :title, :access, :type, :template, :sidebar, :status)";
		
		$createModule = $this->we->query($query, $data);
		if(!$createModule) throw new Exception(lang('error_199'));
		
		if(!$this->_moduleFileExists()) {
			$moduleFileExt = $this->_type == 'static' ? '.html' : '.php';
			$moduleFileName = $this->_file . $moduleFileExt;
			if(check($this->_parent)) {
				
				$directories = explode('/', $this->_parent);
				if(is_array($directories)) {
					foreach($directories as $directory) {
						if(is_array($directoryPath)) {
							$dirPath = $this->_modulesPath . implode('/', $directoryPath) . '/' . $directory;
							if(!file_exists($dirPath)) {
								if(!@mkdir($dirPath, 0755)) throw new Exception(lang('error_200'));
							}
						} else {
							// create first directory
							$dirPath = $this->_modulesPath . $directory;
							if(!file_exists($dirPath)) {
								if(!@mkdir($dirPath, 0755)) throw new Exception(lang('error_200'));
							}
						}
						$directoryPath[] = $directory;
					}
					
					if(!file_exists($this->_modulesPath . $this->_parent)) throw new Exception(lang('error_201'));
					
					// create module file
					if(@file_put_contents($this->_modulesPath . $this->_parent . '/' . $moduleFileName, '') === false) throw new Exception(lang('error_202'));
					
				}
				
			} else {
				// create module file (modules root dir)
				if(@file_put_contents($this->_modulesPath . $moduleFileName, '') === false) throw new Exception(lang('error_202'));
			}
		}
	}
	
	/**
	 * _configModuleExists
	 * 
	 */
	private function _configModuleExists() {
		if(!check($this->_configModule)) return;
		if(!file_exists($this->_moduleConfigPath . $this->_configModulePrefix . $this->_configModule . $this->_configModuleExtension)) return;
		return true;
	}
	
	/**
	 * _moduleFileExists
	 * 
	 */
	private function _moduleFileExists() {
		if(!check($this->_file)) return;
		if(!check($this->_type)) return;
		$fileExt = $this->_type == 'static' ? '.html' : '.php';
		if(check($this->_parent)) {
			$filePath = $this->_modulesPath . $this->_parent . '/' . $this->_file . $fileExt;
		} else {
			$filePath = $this->_modulesPath . $this->_file . $fileExt;
		}
		if(!file_exists($filePath)) return;
		return true;
	}
	
}