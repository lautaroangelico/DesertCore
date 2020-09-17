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

/**
 * admincp_base
 * 
 */
function admincp_base($path='') {
	if(check($path)) return __ADMINCP_BASE_URL__ . ltrim($path, '/');
	return __ADMINCP_BASE_URL__;
}

/**
 * buildAdmincpSidebar
 * 
 */
function buildAdmincpSidebar() {
	$sidebar = config('admincp_sidebar');
	if(!is_array($sidebar)) return;
	
	$request = explode('/', $_GET['request']);
	$activeCategory = check($request[0]) ? $request[0] : null;
	$activeModule = check($request[1]) ? $request[1] : null;
	
	foreach($sidebar as $category => $categoryInfo) {
		
		$active = check($activeCategory) ? ($activeCategory == $category ? true : false) : false;
		$categoryTitle = check(lang($categoryInfo['title'])) ? lang($categoryInfo['title']) : $categoryInfo['title'];
		
		echo $active ? '<li class="active">' : '<li>';
			if(is_array($categoryInfo['modules'])) {
				echo '<a data-toggle="collapse" href="#'.$category.'"'.($active == true ? ' aria-expanded="true"' : null).'>';
					echo '<i class="'.$categoryInfo['icon'].'"></i>';
					echo '<p>' . $categoryTitle;
						echo '<b class="caret"></b>';
					echo '</p>';
				echo '</a>';
				
				echo '<div class="collapse'.($active == true ? ' in' : null).'" id="'.$category.'"'.($active == true ? ' aria-expanded="true"' : null).'>';
					echo '<ul class="nav nav-submenu">';
					foreach($categoryInfo['modules'] as $module => $moduleTitlePhrase) {
						if(!check($moduleTitlePhrase)) continue;
						$moduleTitle = check(lang($moduleTitlePhrase)) ? lang($moduleTitlePhrase) : $moduleTitlePhrase;
						echo '<li><a href="'.admincp_base($category.'/'.$module).'">'.$moduleTitle.'</a></li>';
					}
					echo '</ul>';
				echo '</div>';
			} else {
				if($category == 'home') $category = '';
				echo '<a href="'.admincp_base($category).'">';
					echo '<i class="'.$categoryInfo['icon'].'"></i>';
					echo '<p>'.$categoryTitle.'</p>';
				echo '</a>';
			}
		echo '</li>';
	}
}

/**
 * updateModuleConfig
 * 
 */
function updateModuleConfig($configFileName, $newSettings) {
	if(!check($configFileName, $newSettings)) return;
	if(!is_array($newSettings)) return;
	
	$filePath = __PATH_MODULE_CONFIGS__.$configFileName.'.json';
	
	// module configurations
	$moduleConfig = loadModuleConfig($configFileName);
	if(!is_array($moduleConfig)) return;
	
	// allowed settings
	foreach(array_keys($newSettings) as $settingName) {
		if(!array_key_exists($settingName, $moduleConfig)) return;
		$moduleConfig[$settingName] = $newSettings[$settingName];
	}
	
	// prepare configurations
	$newModuleConfig = json_encode($moduleConfig, JSON_PRETTY_PRINT);
	
	// check if configuration file exists
	if(!file_exists($filePath)) return;
	
	// check if configuration file is writable
	if(!is_writable($filePath)) return;
	
	// open configurations file
	$file = fopen($filePath, 'w');
	if(!$file) return;
	
	// update configurations
	if(fwrite($file, $newModuleConfig) === false) return;
	if(fclose($file) === false) return;
	
	return true;
}

/**
 * updateConfig
 * 
 */
function updateConfig($configFileName, $newSettings) {
	if(!check($configFileName, $newSettings)) return;
	if(!is_array($newSettings)) return;
	
	$filePath = __PATH_CONFIGS__.$configFileName.'.json';
	
	// configurations
	$configurations = loadConfig($configFileName);
	if(!is_array($configurations)) return;
	
	// allowed settings
	foreach(array_keys($newSettings) as $settingName) {
		if(!array_key_exists($settingName, $configurations)) return;
		$configurations[$settingName] = $newSettings[$settingName];
	}
	
	// prepare configurations
	$newModuleConfig = json_encode($configurations, JSON_PRETTY_PRINT);
	
	// check if configuration file exists
	if(!file_exists($filePath)) return;
	
	// check if configuration file is writable
	if(!is_writable($filePath)) return;
	
	// open configurations file
	$file = fopen($filePath, 'w');
	if(!$file) return;
	
	// update configurations
	if(fwrite($file, $newModuleConfig) === false) return;
	if(fclose($file) === false) return;
	
	return true;
}

/**
 * checkVersion
 * 
 */
function checkVersion() {
	$url = 'https://version.webenginecms.org/3.0/index.php';
	
	$fields = array(
		'version' => urlencode(__DESERTCORE_VERSION__),
		'baseurl' => urlencode(__BASE_URL__),
	);
	
	foreach($fields as $key => $value) {
		$fieldsArray[] = $key . '=' . $value;
	}
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&", $fieldsArray));
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, 'WebEngine');
	curl_setopt($ch, CURLOPT_HEADER, false);

	$result = curl_exec($ch);
	curl_close($ch);
	
	if(!$result) return;
	$resultArray = json_decode($result, true);
	if($resultArray['update']) return true;
	return;
}