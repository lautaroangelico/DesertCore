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

final class Handler {
	
	private static $webengine;
	
	private static $defaultModule = 'news';
	private static $loginModule = 'login';
	private static $notFoundModule = '404.html';
	private static $disabledModule = 'disabled.html';
	
	private static $regexPattern = '/[^a-zA-Z0-9\/\-\:\.\_]/';
	
	private static $template;
	private static $templatePath;
	private static $templateBase;
	private static $showSidebar = true;
	
	private static $templateCssDir = 'css/';
	private static $templateImgDir = 'img/';
	private static $templateJsDir = 'js/';
	private static $templateFontsDir = 'fonts/';
	private static $templateIncludesDir = 'includes/';
	private static $templateBlocksDir = 'includes/blocks/';
	private static $templateSidebarCfg = 'sidebar.json';
	private static $templateBlockPrefix = 'block.';
	private static $templateFunctionsFile = 'functions.php';
	
	private static $admincpTemplate = 'default';
	
	private static $moduleFileExt = '.php';
	private static $moduleTitle;
	private static $moduleIsAdmincp = 0;
	
	private static $request;
	private static $loadModule;
	
	private static $enableAltTitle = true;
	private static $defaultWebsiteTitle = 'Black Desert Online';
	
	private static $adminAccessLevel = 0;
	
	private static $loadedModule;
	
	/**
	 * renderTemplate
	 * 
	 */
	public static function renderTemplate() {
		global $lang;
		
		// set request
		if(check($_GET['request'])) self::$request = $_GET['request'];
		
		try {
			
			if(!defined('access')) throw new Exception('Access Forbidden');
			
			// load language
			Language::loadDefaultLanguage();
			
			switch(access) {
				case 'index':
					self::_setTemplate(config('website_template'));
					sessionControl::beginSession();
					if(check($_SESSION['default_language'])) Language::loadLanguage($_SESSION['default_language']);
					self::$defaultModule = config('default_module');
					self::_handleRequest();
					self::_loadTemplate();
					break;
				case 'admincp':
					sessionControl::beginSession();
					if(check($_SESSION['default_language'])) Language::loadLanguage($_SESSION['default_language']);
					if(!sessionControl::isLoggedIn()) throw new Exception('Access forbidden.');
					if($_SESSION['admin'] != true) throw new Exception('Access forbidden.');
					self::$defaultModule = 'dashboard';
					self::_handleAdmincpRequest();
					self::_loadAdmincpTemplateIndex();
					
					break;
				case 'cron':
					
					break;
				case 'api':
					
					break;
				default:
					throw new Exception('Access Forbidden');
			}
			
		} catch(Exception $ex) {
			throw new Exception('[ERROR] ' . $ex->getMessage());
		}
	}
	
	/**
	 * loadModule
	 * 
	 */
	public static function loadModule() {
		try {
			
			$path = self::$loadModule;
			
			# module file
			if(self::$moduleFileExt == '.php') {
				// DYNAMIC MODULE
				if(!@include_once($path)) return;
			} else {
				// STATIC MODULE
				if(!@readfile($path)) return;
			}
			
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	/**
	 * getModuleTitle
	 * 
	 */
	public static function getModuleTitle() {
		if(check(self::$moduleTitle)) return self::$moduleTitle;
	}
	
	/**
	 * loadAdmincpModule
	 * 
	 */
	public static function loadAdmincpModule() {
		try {
			
			$path = self::$loadModule;
			
			# module file
			if(self::$moduleFileExt == '.php') {
				// DYNAMIC MODULE
				if(!@include_once($path)) return;
			} else {
				// STATIC MODULE
				if(!@readfile($path)) return;
			}
			
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	/**
	 * loadDB
	 * 
	 */
	public static function loadDB($database='') {
		switch(strtolower($database)) {
			case 'bdo':
				if(config('MONGO_DB_USE_CONN_STRING')) {
					$client = new MongoDB\Client(config('MONGO_DB_CONN_STRING'));
				} else {
					if(check(config('MONGO_DB_USER'), config('MONGO_DB_PASS'))) {
						$client = new MongoDB\Client("mongodb://".config('MONGO_DB_USER').":".config('MONGO_DB_PASS')."@".config('MONGO_DB_HOST').":".config('MONGO_DB_PORT')."");
					} else {
						$client = new MongoDB\Client("mongodb://".config('MONGO_DB_HOST').":".config('MONGO_DB_PORT')."");
					}
				}
				
				return $client;
			case 'webengine':
				$dbc = new WebEngineDatabase('webengine.db');
				return $dbc;
			case 'bdodata':
				$dbc = new WebEngineDatabase('bdodata.db');
				return $dbc;
			default:
				return;
		}
	}
	
	/**
	 * userIP
	 * 
	 */
	public static function userIP() {
		$var = isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? 'HTTP_CF_CONNECTING_IP' : 'REMOTE_ADDR';
		$ip = filter_input(INPUT_SERVER, $var, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
		if(!$ip) return "0.0.0.0";
		return $ip;
	}
	
	/**
	 * websiteTitle
	 * 
	 */
	public static function websiteTitle() {
		// alt title (modules)
		if(self::$enableAltTitle) {
			if(check(self::$moduleTitle)) {
				$title = check(config('website_title_alt')) ? config('website_title_alt') : self::$defaultWebsiteTitle;
				return str_replace('{MODULE_TITLE}', self::$moduleTitle, $title);
			}
		}
		
		// main title
		$title = check(config('website_title')) ? config('website_title') : self::$defaultWebsiteTitle;
		return $title;
	}
	
	/**
	 * showSidebar
	 * 
	 */
	public static function showSidebar() {
		return self::$showSidebar;
	}
	
	/**
	 * getDesertCoreVersion
	 * 
	 */
	public static function getDesertCoreVersion($includeName=false) {
		if($includeName) return __WEBENGINE_NAME__ . ' ' . __DESERTCORE_VERSION__;
		return __DESERTCORE_VERSION__;
	}
	
	/**
	 * getWebEngineWebsite
	 * 
	 */
	public static function getWebEngineWebsite() {
		return __WEBENGINE_WEBSITE__;
	}
	
	/**
	 * getWebEngineAuthor
	 * 
	 */
	public static function getWebEngineAuthor() {
		return 'Lautaro Angelico';
	}
	
	/**
	 * getWebsiteDescription
	 * 
	 */
	public static function getWebsiteDescription() {
		return config('website_meta_description');
	}
	
	/**
	 * getWebsiteKeywords
	 * 
	 */
	public static function getWebsiteKeywords() {
		return config('website_meta_keywords');
	}
	
	/**
	 * templatePath
	 * 
	 */
	public static function templatePath() {
		return self::$templatePath;
	}
	
	/**
	 * templateBase
	 * 
	 */
	public static function templateBase() {
		return self::$templateBase;
	}
	
	/**
	 * templateCSS
	 * 
	 */
	public static function templateCSS($path='') {
		return self::$templateBase . self::$templateCssDir . $path;
	}
	
	/**
	 * templateJS
	 * 
	 */
	public static function templateJS($path='') {
		return self::$templateBase . self::$templateJsDir . $path;
	}
	
	/**
	 * templateIMG
	 * 
	 */
	public static function templateIMG($path='') {
		return self::$templateBase . self::$templateImgDir . $path;
	}
	
	/**
	 * templateFONT
	 * 
	 */
	public static function templateFONT($path='') {
		return self::$templateBase . self::$templateFontsDir . $path;
	}
	
	/**
	 * websiteLink
	 * 
	 */
	public static function websiteLink($location='') {
		if(!check($location)) return __BASE_URL__;
		return __BASE_URL__ . Filter::RemoveTrailingSlash($location);
	}
	
	/**
	 * loadSidebarBlocks
	 * 
	 */
	public static function loadSidebarBlocks() {
		$sidebarCfg = self::_loadTemplateSidebarConfigurations();
		if(!is_array($sidebarCfg)) return;
		
		foreach($sidebarCfg as $block) {
			try {
				
				// block enabled
				if(!$block['active']) continue;
				
				// block file
				$blockFilePath = self::$templatePath . self::$templateBlocksDir . self::$templateBlockPrefix . $block['block'] . '.php';
				if(!file_exists($blockFilePath)) continue;
				
				// visibility
				if($block['visibility'] == 'user' && !isLoggedIn()) continue;
				if($block['visibility'] == 'guest' && isLoggedIn()) continue;
				
				// load block
				if(!@include_once($blockFilePath)) continue;
				
			} catch(Exception $ex) {
				message('error', $ex->getMessage());
			}
		}
	}
	
	/**
	 * loadTemplateFunctions
	 * 
	 */
	public static function loadTemplateFunctions() {
		if(!@include_once(self::$templatePath . self::$templateIncludesDir . self::$templateFunctionsFile)) return;
	}
	
	/**
	 * admincpTemplateBase
	 * 
	 */
	public static function admincpTemplateBase($path='') {
		return __ADMINCP_TEMPLATES_BASE_URL__ . self::$admincpTemplate . '/' . $path;
	}
	
	/**
	 * getLoadedModule
	 * 
	 */
	public static function getLoadedModule() {
		if(!check(self::$loadedModule)) return;
		$paths = explode('/',self::$loadedModule);
		if(!check($paths[0])) return;
		return $paths[0];
	}
	
	/**
	 * _handleRequest
	 * i was pretty high when coding this
	 */
	private static function _handleRequest() {
		self::$webengine = self::loadDB('WebEngine');
		
		$request = self::$request;
		if(!check($request)) $request = self::$defaultModule;
		
		$request = explode("/", $request);
		$request = array_filter($request);
		
		$paths = array();
		
		// build paths
		foreach($request as $key => $req) {
			$previousRequest = check($paths[$key-1]) ? $paths[$key-1] : '';
			$currentRequest = self::_cleanModuleRequest($req);
			$paths[] = $previousRequest . $currentRequest . '/';
		}
		
		// Session Control
		sessionControl::lastUserLocation(end($paths));
		if(!isLoggedIn()) {
			sessionControl::initSessionControl();
		} else {
			sessionControl::initSessionControl('user');
		}
		
		// loaded module
		self::$loadedModule = $paths[0];
		
		// check paths
		if(count($paths) > 1) {
			
			foreach($paths as $index => $path) {
				if(!file_exists(__PATH_MODULES__ . $path)) break;
			}
			
			$file = Filter::RemoveTrailingSlash($path);
			$filePathArray = explode('/', $file);
			$module = end($filePathArray);
			
			// load module database info
			$parent = Filter::RemoveTrailingSlash(str_replace($module, '', $file));
			
			// additional url data
			$additionalData = null;
			if(count($paths) > $index+1) {
				$additionalData = str_replace($path, '', end($paths));
			}
			
			// no parent -> load single path module
			if(!check($parent)) {
				self::_handleSinglePathModule($file, $additionalData);
				return;
			}
			
			self::_handleMultiPathModule($file, $module, $parent, $additionalData);
			return;
			
		} else {
			
			// single path module
			self::_handleSinglePathModule($paths[0], $additionalData);
			return;
			
		}
		
	}
	
	/**
	 * _handleSinglePathModule
	 * at this point i was so high i almost had a bad trip
	 */
	private static function _handleSinglePathModule($path, $additionalData=null) {
		// single path
		$file = Filter::RemoveTrailingSlash($path);
		
		// load module database info
		$moduleData = self::$webengine->queryFetchSingle("SELECT * FROM `"._WE_MODULES_."` WHERE `parent` IS NULL AND `file` = ?", array($file));
		if(!is_array($moduleData)) {
			
			// 404 (module not in database)
			self::_moduleNotFoundPage();
			return;
			
		}
		
		// module status
		if($moduleData['status'] != 1) {
			self::_moduleDisabledPage();
			return;
		}
		
		// module access
		self::_checkModuleAccess($moduleData['access']);
		
		// module template
		if(check($moduleData['template'])) self::_setTemplate($moduleData['template']);
		
		// module sidebar
		if($moduleData['sidebar'] != 1) self::$showSidebar = false;
		
		// module type
		if($moduleData['type'] == 'static') self::_setStaticModuleType();
		
		// module title phrase
		self::$moduleTitle = lang($moduleData['title']) != 'ERROR' ? lang($moduleData['title']) : $moduleData['title'];
		
		// check if exists
		if(self::_moduleExists($file)) {
			
			// additional request data
			if(check($additionalData)) {
				$additionalData = array_filter(explode('/', $additionalData));
				if(is_array($additionalData)) {
					
					// $_GET variables
					for($i=0; $i<count($additionalData); $i+=2) {
						if(!check($additionalData[$i+1])) continue;
						$_GET[$additionalData[$i]] = $additionalData[$i+1];
					}
					
				}
			}
			
			// load module
			self::$loadModule = __PATH_MODULES__ . $file . self::$moduleFileExt;
			
		} else {
			
			// 404
			self::_moduleNotFoundPage();
			return;
			
		}
	}
	
	/**
	 * _handleMultiPathModule
	 * northern lights indica is the real deal
	 */
	private static function _handleMultiPathModule($file='', $module='', $parent='', $additionalData=null) {
		if(!check($file, $module, $parent)) throw new Exception('There was a problem loading the module.');
		
		$moduleData = self::$webengine->queryFetchSingle("SELECT * FROM `"._WE_MODULES_."` WHERE `parent` = ? AND `file` = ?", array($parent, $module));
		if(!is_array($moduleData)) {
			
			// 404 (module not in database)
			self::_moduleNotFoundPage();
			return;
			
		}
		
		// module status
		if($moduleData['status'] != 1) {
			self::_moduleDisabledPage();
			return;
		}
		
		// module access
		self::_checkModuleAccess($moduleData['access']);
		
		// module template
		if(check($moduleData['template'])) self::_setTemplate($moduleData['template']);
		
		// module sidebar
		if($moduleData['sidebar'] != 1) self::$showSidebar = false;
		
		// module title phrase
		self::$moduleTitle = lang($moduleData['title']) != 'ERROR' ? lang($moduleData['title']) : $moduleData['title'];
		
		// module type
		if($moduleData['type'] == 'static') self::_setStaticModuleType();
		
		// check if module exists
		if(self::_moduleExists($file)) {
			
			// additional request data
			if(check($additionalData)) {
				$additionalData = array_filter(explode('/', $additionalData));
				if(is_array($additionalData)) {
					
					// $_GET variables
					for($i=0; $i<count($additionalData); $i+=2) {
						if(!check($additionalData[$i+1])) continue;
						$_GET[$additionalData[$i]] = $additionalData[$i+1];
					}
					
				}
			}
			
			// load module (multi path)
			self::$loadModule = __PATH_MODULES__ . $file . self::$moduleFileExt;
			
		} else {
			
			// 404
			self::_moduleNotFoundPage();
			return;
			
		}
	}
	
	/**
	 * _moduleExists
	 * 
	 */
	private static function _moduleExists($file) {
		$modulesPath = access == 'admincp' ? __PATH_ADMINCP_MODULES__ : __PATH_MODULES__;
		if(file_exists($modulesPath . $file . self::$moduleFileExt)) return true;
		return;
	}
	
	/**
	 * _moduleNotFoundPage
	 * 
	 */
	private static function _moduleNotFoundPage() {
		$modulesPath = access == 'admincp' ? __PATH_ADMINCP_MODULES__ : __PATH_MODULES__;
		self::_setStaticModuleType();
		self::$showSidebar = false;
		self::$loadModule = $modulesPath . self::$notFoundModule;
	}
	
	/**
	 * _setStaticModuleType
	 * 
	 */
	private static function _setStaticModuleType() {
		self::$moduleFileExt = '.html';
	}
	
	/**
	 * _checkModuleAccess
	 * 
	 */
	private static function _checkModuleAccess($access='user') {
		switch($access) {
			case 'user':
				// only allow logged in
				if(!isLoggedIn()) redirect(self::$loginModule);
				break;
			case 'guest':
				// only allow guests
				if(isLoggedIn()) redirect();
				break;
			default:
				// allow all
		}
	}
	
	/**
	 * _moduleDisabledPage
	 * 
	 */
	private static function _moduleDisabledPage() {
		self::_setStaticModuleType();
		self::$showSidebar = false;
		self::$loadModule = __PATH_MODULES__ . self::$disabledModule;
	}
	
	/**
	 * _loadTemplate
	 * 
	 */
	private static function _loadTemplate() {
		if(!check(self::$templatePath . 'index.php')) throw new Exception('Could not load template.');
		include(self::$templatePath . 'index.php');
	}
	
	/**
	 * _loadAdmincpTemplateIndex
	 * 
	 */
	private static function _loadAdmincpTemplateIndex() {
		if(!file_exists(__PATH_ADMINCP_TEMPLATES__ . self::$admincpTemplate . '/index.php')) throw new Exception('Could not load template.');
		include(__PATH_ADMINCP_TEMPLATES__ . self::$admincpTemplate . '/index.php');
	}
	
	/**
	 * _cleanModuleRequest
	 * 
	 */
	private static function _cleanModuleRequest($input) {
		return preg_replace(self::$regexPattern, '', $input);
	}
	
	/**
	 * _setTemplate
	 * 
	 */
	private static function _setTemplate($template='') {
		// by default load main template
		if(!check($template)) $template = config('website_template');
		
		// template path
		self::$templatePath = __PATH_TEMPLATES__ . $template . '/';
		
		// template base
		self::$templateBase = __TEMPLATES_BASE_URL__ . $template . '/';
		
		// check if template exists
		if(!file_exists(self::$templatePath . 'index.php')) throw new Exception('Could not load template.');
		
		// set template
		self::$template = $template;
	}
	
	/**
	 * _loadTemplateSidebarConfigurations
	 * 
	 */
	private static function _loadTemplateSidebarConfigurations() {
		if(!check(self::$templateSidebarCfg)) return;
		if(!file_exists(self::$templatePath . self::$templateIncludesDir . self::$templateSidebarCfg)) return;
		$config = file_get_contents(self::$templatePath . self::$templateIncludesDir . self::$templateSidebarCfg);
		if(!$config) return;
		$result = json_decode($config, true);
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * _handleAdmincpRequest
	 * 
	 */
	private static function _handleAdmincpRequest() {		
		$request = self::$request;
		if(!check($request)) $request = self::$defaultModule;
		
		$request = explode("/", $request);
		$request = array_filter($request);
		
		$paths = array();
		
		// build paths
		foreach($request as $key => $req) {
			$previousRequest = check($paths[$key-1]) ? $paths[$key-1] : '';
			$currentRequest = self::_cleanModuleRequest($req);
			$paths[] = $previousRequest . $currentRequest . '/';
		}
		
		// Session Control
		sessionControl::lastUserLocation(end($paths));
		if(!isLoggedIn()) {
			sessionControl::initSessionControl();
		} else {
			sessionControl::initSessionControl('user');
		}
		
		// check paths
		if(count($paths) > 1) {
			
			foreach($paths as $index => $path) {
				if(!file_exists(__PATH_ADMINCP_MODULES__ . $path)) break;
			}
			
			$file = Filter::RemoveTrailingSlash($path);
			$filePathArray = explode('/', $file);
			$module = end($filePathArray);
			
			// load module database info
			$parent = Filter::RemoveTrailingSlash(str_replace($module, '', $file));
			
			// additional url data
			$additionalData = null;
			if(count($paths) > $index+1) {
				$additionalData = str_replace($path, '', end($paths));
			}
			
			// no parent -> load single path module
			if(!check($parent)) {
				self::_handleAdmincpSinglePathModule($file, $additionalData);
				return;
			}
			
			self::_handleAdmincpMultiPathModule($file, $module, $parent, $additionalData);
			return;
			
		} else {
			
			// single path module
			self::_handleAdmincpSinglePathModule($paths[0], $additionalData);
			return;
			
		}
		
	}
	
	/**
	 * _handleAdmincpSinglePathModule
	 * 
	 */
	private static function _handleAdmincpSinglePathModule($path, $additionalData=null) {
		// single path
		$file = Filter::RemoveTrailingSlash($path);
		
		// check if exists
		if(self::_moduleExists($file)) {
			
			// additional request data
			if(check($additionalData)) {
				$additionalData = array_filter(explode('/', $additionalData));
				if(is_array($additionalData)) {
					
					// $_GET variables
					for($i=0; $i<count($additionalData); $i+=2) {
						if(!check($additionalData[$i+1])) continue;
						$_GET[$additionalData[$i]] = $additionalData[$i+1];
					}
					
				}
			}
			
			// load module
			self::$loadModule = __PATH_ADMINCP_MODULES__ . $file . '.php';
			
		} else {
			
			// 404
			self::_moduleNotFoundPage();
			return;
			
		}
	}
	
	/**
	 * _handleAdmincpMultiPathModule
	 * 
	 */
	private static function _handleAdmincpMultiPathModule($file='', $module='', $parent='', $additionalData=null) {
		if(!check($file, $module, $parent)) throw new Exception('There was a problem loading the module.');
		
		// check if module exists
		if(self::_moduleExists($file)) {
			
			// additional request data
			if(check($additionalData)) {
				$additionalData = array_filter(explode('/', $additionalData));
				if(is_array($additionalData)) {
					
					// $_GET variables
					for($i=0; $i<count($additionalData); $i+=2) {
						if(!check($additionalData[$i+1])) continue;
						$_GET[$additionalData[$i]] = $additionalData[$i+1];
					}
					
				}
			}
			
			// load module (multi path)
			self::$loadModule = __PATH_ADMINCP_MODULES__ . $file . '.php';
			
		} else {
			
			// 404
			self::_moduleNotFoundPage();
			return;
			
		}
	}
	
}