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

function check($value) {
	if((@count($value)>0 and !@empty($value) and @isset($value)) || $value=='0') {
		return true;
	}
}

function redirect($location="") {
	$baseUrl = access == 'admincp' ? __ADMINCP_BASE_URL__ : __BASE_URL__;
	if(!check($location)) {
		header('Location: ' . $baseUrl);
		return;
	}
	if(Validator::Url($location)) {
		header('Location: ' . $location);
		return;
	}
	header('Location: ' . $baseUrl . $location);
	return;
}

function htmlRedirect($location="", $delay=5) {
	$baseUrl = access == 'admincp' ? __ADMINCP_BASE_URL__ : __BASE_URL__;
	if(!check($location)) {
		echo '<meta http-equiv="refresh" content="'.$delay.'; URL=\''.$baseUrl.'\'" />';
		return;
	}
	if(Validator::Url($location)) {
		echo '<meta http-equiv="refresh" content="'.$delay.'; URL=\''.$location.'\'" />';
		return;
	}
	echo '<meta http-equiv="refresh" content="'.$delay.'; URL=\''.$baseUrl.$location.'\'" />';
	return;
}

function isLoggedIn() {
	if(sessionControl::isLoggedIn()) return true;
	return;
}

function logOutUser() {
	sessionControl::logout();
}

function message($type='info', $message="", $title="") {
	switch($type) {
		case 'error':
			$class = ' alert-danger';
		break;
		case 'success':
			$class = ' alert-success';
		break;
		case 'warning':
			$class = ' alert-warning';
		break;
		default:
			$class = ' alert-info';
		break;
	}
	
	if(check($title)) {
		echo '<div class="alert'.$class.'" role="alert"><strong>'.$title.'</strong><br />'.$message.'</div>';
	} else {
		echo '<div class="alert'.$class.'" role="alert">'.$message.'</div>';
	}
}

function lang($phrase, $args=null, $return=true) {
	if(is_array($args)) {
		return Language::Phrase($phrase, $args);
	}
	return Language::Phrase($phrase);
}

function langf($phrase, $args=array(), $print=false) {
	return Language::Phrase($phrase, $args);
}

function debug($value) {
	echo '<pre>',print_r($value,1),'</pre>';
}

function sec_to_hms($input_seconds=0) {
	$result = sec_to_dhms($input_seconds);
	if(!is_array($result)) return array(0,0,0);
	return array((($result[0]*24)+$result[1]), $result[2], $result[3]);
}

function sec_to_dhms($input_seconds=0) {
	if($input_seconds < 1) return array(0,0,0,0);
	$days_module = $input_seconds % 86400;
	$days = ($input_seconds-$days_module)/86400;
	$hours_module = $days_module % 3600;
	$hours = ($days_module-$hours_module)/3600;
	$minutes_module = $hours_module % 60;
	$minutes = ($hours_module-$minutes_module)/60;
	$seconds = $minutes_module;
	return array($days,$hours,$minutes,$seconds);
}

function webengineConfigs() {
	if(!file_exists(__PATH_CONFIGS__ . 'webengine.json')) throw new Exception('WebEngine\'s configuration file doesn\'t exist, please reupload the website files.');
	
	$webengineConfigs = file_get_contents(__PATH_CONFIGS__ . 'webengine.json');
	if(!check($webengineConfigs)) throw new Exception('WebEngine\'s configuration file is empty, please run the installation script.');
	
	return json_decode($webengineConfigs, true);
}

function config($config_name, $return = true) {
	global $config;
	return $config[$config_name];
}

function loadModuleConfig($filename) {
	if(!check($filename)) return;
	if(!file_exists(__PATH_MODULE_CONFIGS__ . $filename . '.json')) return;
	$cfg = file_get_contents(__PATH_MODULE_CONFIGS__ . $filename . '.json');
	if(!check($cfg)) return;
	return json_decode($cfg, true);
}

function loadConfig($name="webengine") {
	if(!check($name)) return;
	if(!file_exists(__PATH_CONFIGS__ . $name . '.json')) return;
	$cfg = file_get_contents(__PATH_CONFIGS__ . $name . '.json');
	if(!check($cfg)) return;
	return json_decode($cfg, true);
}

function returnPlayerAvatar($code=0, $alt=true, $imgTags=true, $css=null, $width=null, $height=null) {
	$playerClass = custom('classType');
	if(!is_array($playerClass)) return;
	$fileName = (array_key_exists($code, $playerClass) ? $playerClass[$code]['image'] : 'avatar.jpg');
	$image = Handler::templateIMG() . 'character-avatars/' . $fileName;
	$name = $playerClass[$code]['name'];
	
	if($imgTags) {
		$buildTag = '<img';
		$buildTag .= ' src="'.$image.'"';
		if(check($css)) {
			$buildTag .= ' class="'.$css.'"';
		}
		if($alt) {
			$buildTag .= ' data-toggle="tooltip"';
			$buildTag .= ' data-placement="top"';
			$buildTag .= ' title="'.$name.'"';
			$buildTag .= ' alt="'.$name.'"';
		}
		if(check($width)) {
			$buildTag .= ' width="'.$width.'"';
		}
		if(check($height)) {
			$buildTag .= ' height="'.$height.'"';
		}
		$buildTag .= '/>';
		return $buildTag;
	}
	return $image;
}

function returnPlayerZodiac($code=0, $alt=true, $imgTags=true, $css=null, $width=null, $height=null) {
	$zodiacSign = custom('zodiacSign');
	if(!is_array($zodiacSign)) return;
	$fileName = (array_key_exists($code, $zodiacSign) ? $zodiacSign[$code]['image'] : 'blackstone.png');
	$image = Handler::templateIMG() . 'zodiac/' . $fileName;
	$name = $zodiacSign[$code]['name'];
	
	if($imgTags) {
		$buildTag = '<img';
		$buildTag .= ' src="'.$image.'"';
		if(check($css)) {
			$buildTag .= ' class="'.$css.'"';
		}
		if($alt) {
			$buildTag .= ' data-toggle="tooltip"';
			$buildTag .= ' data-placement="top"';
			$buildTag .= ' title="'.$name.'"';
			$buildTag .= ' alt="'.$name.'"';
		}
		if(check($width)) {
			$buildTag .= ' width="'.$width.'"';
		}
		if(check($height)) {
			$buildTag .= ' height="'.$height.'"';
		}
		$buildTag .= '/>';
		return $buildTag;
	}
	return $image;
}

function custom($config) {
	global $custom;
	
	if(!is_array($custom)) return;
	if(!array_key_exists($config, $custom)) return;
	return $custom[$config];
}

function playerClassName($class=0) {
	$playerClassData = custom('classType');
	if(!is_array($playerClassData)) return;
	if(!array_key_exists($class, $playerClassData)) return 'Unknown';
	return $playerClassData[$class]['name'];
}

function zodiacSignName($sign=0) {
	$zodiacSignData = custom('zodiacSign');
	if(!is_array($zodiacSignData)) return;
	if(!array_key_exists($sign, $zodiacSignData)) return 'Unknown';
	return $zodiacSignData[$sign]['name'];
}

function databaseTime($datetime) {
	return date("Y-m-d H:i:s", strtotime($datetime) + date("Z"));
}

function encodeCache($data, $pretty=false) {
	if($pretty) return json_encode($data, JSON_PRETTY_PRINT);
	return json_encode($data);
}

function decodeCache($data) {
	return json_decode($data, true);
}

function updateCache($fileName, $data) {
	$file = __PATH_CACHE__ . $fileName;
	if(!file_exists($file)) return;
	if(!is_writable($file)) return;
	
	$fp = fopen($file, 'w');
	fwrite($fp, $data);
	fclose($fp);
	return true;
}

function loadCache($fileName) {
	$file = __PATH_CACHE__ . $fileName;
	if(!file_exists($file)) return;
	if(!is_readable($file)) return;
	
	$cacheDataRaw = file_get_contents($file);
	if(!check($cacheDataRaw)) return;
	
	$cacheData = decodeCache($cacheDataRaw);
	if(!is_array($cacheData)) return;
	
	return $cacheData;
}

function playerProfile($name, $css=null) {
	return $name;
}

function guildProfile($name, $css=null) {
	return $name;
}

function facebookProfile($id) {
	return __FACEBOOK_PROFILE_LINK__ . $id;
}

function googleProfile($id) {
	return __GOOGLE_PROFILE_LINK__ . $id;
}

function adapterConfig($provider, $callback) {
	if(!check($provider)) return;
	if(!check($callback)) return;
	
	$cfg = loadConfig('social');
	if(!is_array($cfg)) return;
	if(!array_key_exists($provider, $cfg['provider'])) return;
	if(!check($cfg['provider'][$provider]['id'])) return;
	if(!check($cfg['provider'][$provider]['secret'])) return;
	
	$adapterConfig = [
		'callback' => Handler::websiteLink($callback),
		'keys' => [
			'id'		=> $cfg['provider'][$provider]['id'],
			'secret'	=> $cfg['provider'][$provider]['secret'],
		]
	];
	
	if(check($cfg['provider'][$provider]['scope'])) {
		$adapterConfig['scope'] = $cfg['provider'][$provider]['scope'];
	}
	
	return $adapterConfig;
}

function isAdmin() {
	if(!check($_SESSION['admin'])) return;
	if($_SESSION['admin'] != true) return;
	return true;
}

function readableFileSize($size) {
	if($size == 0) return '0B';
	$base = log($size) / log(1024);
	$suffix = array("", "KB", "MB", "GB", "TB");
	$f_base = floor($base);
	return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
}

function setOfflineMode($status=false) {
	$webengineConfigurations = webengineConfigs();
	$webengineConfigurations['offline_mode'] = $status;
	$newWebEngineConfig = json_encode($webengineConfigurations, JSON_PRETTY_PRINT);
	$cfgFile = fopen(__PATH_CONFIGS__.'webengine.json', 'w');
	if(!$cfgFile) return;
	if(!fwrite($cfgFile, $newWebEngineConfig)) return;
	if(!fclose($cfgFile)) return;
	return true;
}

function formatMongoDate($date) {
	return date("Y-m-d H:i A", round($date/1000));
}

function getCurrencies($currency='') {
	$currencies = loadConfig('currencies');
	if(!is_array($currencies)) return;
	if(!check($currency)) return $currencies;
	if(!array_key_exists($currency, $currencies)) return;
	return $currencies[$currency];
}

function displayItemEnhancementLevel($level=0, $suffix='') {
	$enhancementLevels = custom('itemEnhancement');
	if(!is_array($enhancementLevels)) return;
	if($level < 1 || $level > 20) return;
	if(!array_key_exists($level, $enhancementLevels)) return;
	return $enhancementLevels[$level] . $suffix;
	
}

function desertCoreItemDatabase($itemId) {
	if(!Validator::UnsignedNumber($itemId)) return;
	$db = Handler::loadDB('BDOData');
	$result = $db->queryFetchSingle("SELECT * FROM `items` WHERE `id` = ?", array($itemId));
	if(is_array($result)) {
		// get item data from database
		$iconPath = __PATH_STATIC_CONTENT__ . $result['icon'] . '.png';
		if(filesize($iconPath) == 0) {
			@unlink($iconPath);
			if(!file_exists($iconPath)) {
				$db->query("DELETE FROM `items` WHERE `id` = ?", array($itemId));
			}
		}
		$icon = __STATIC_BASE_URL__ . $result['icon'] . '.png';
		return array('name' => $result['name'], 'grade' => $result['grade'], 'icon' => $icon);
	} else {
		// get item data using api
		$url = __API_ITEM_DATA__ . '?id=' . $itemId;
		$json = file_get_contents($url);
		if(!$json) return;
		$result = json_decode($json);
		if($result->code == 200) {
			return array('name' => $result->data->name, 'grade' => $result->data->grade, 'icon' => $result->data->icon);
		}
	}
	return;
}

function desertCoreSkillDatabase($skillId) {
	if(!Validator::UnsignedNumber($skillId)) return;
	$db = Handler::loadDB('BDOData');
	$result = $db->queryFetchSingle("SELECT * FROM `skills` WHERE `id` = ?", array($skillId));
	if(is_array($result)) {
		// get skill data from database
		if(!check($result['icon'])) {
			$icon = __STATIC_BASE_URL__ . 'blank.png';
		} else {
			$iconPath = __PATH_STATIC_CONTENT__ . $result['icon'] . '.png';
			if(filesize($iconPath) == 0) {
				@unlink($iconPath);
				if(!file_exists($iconPath)) {
					$db->query("DELETE FROM `skills` WHERE `id` = ?", array($itemId));
				}
			}
			$icon = __STATIC_BASE_URL__ . $result['icon'] . '.png';
		}
		return array('name' => $result['name'], 'class' => $result['class'], 'icon' => $icon);
	} else {
		// get skill data using api
		$url = __API_SKILL_DATA__ . '?id=' . $skillId;
		$json = file_get_contents($url);
		if(!$json) return;
		$result = json_decode($json);
		if($result->code == 200) {
			return array('name' => $result->data->name, 'class' => $result->data->class, 'icon' => $result->data->icon);
		}
	}
	return;
}

function bdoDatabaseLink($itemId) {
	$bdoDatabase = config('bdo_database');
	if(!check($bdoDatabase)) return '#';
	return $bdoDatabase . $itemId . '/';
}

function bdoSkillDatabaseLink($skillId) {
	$bdoDatabase = config('bdo_database_skills');
	if(!check($bdoDatabase)) return '#';
	return $bdoDatabase . $skillId . '/';
}

function returnPlayerClass($code=0, $alt=true, $imgTags=true, $css=null, $width=null, $height=null) {
	$classType = custom('classType');
	if(!is_array($classType)) return;
	$fileName = (array_key_exists($code, $classType) ? $classType[$code]['icon'] : 'default.png');
	$image = Handler::templateIMG() . 'classes/' . $fileName;
	$name = $classType[$code]['name'];
	
	if($imgTags) {
		$buildTag = '<img';
		$buildTag .= ' src="'.$image.'"';
		if(check($css)) {
			$buildTag .= ' class="'.$css.'"';
		}
		if($alt) {
			$buildTag .= ' data-toggle="tooltip"';
			$buildTag .= ' data-placement="top"';
			$buildTag .= ' title="'.$name.'"';
			$buildTag .= ' alt="'.$name.'"';
		}
		if(check($width)) {
			$buildTag .= ' width="'.$width.'"';
		}
		if(check($height)) {
			$buildTag .= ' height="'.$height.'"';
		}
		$buildTag .= '/>';
		return $buildTag;
	}
	return $image;
}

function saveImage($path, $database) {
	// image data
	$imagePath = $path . '.png';
	$imageDir = __PATH_STATIC_CONTENT__ . $imagePath;
	$imageLink = $database . $imagePath;
	$split_image = pathinfo($imageLink);

	// get image
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL , $imageLink);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$response= curl_exec($ch);
	curl_close($ch);

	// check directory
	$dirname = dirname($imageDir);
	if(!is_dir($dirname)) {
		if(!mkdir($dirname, 0755, true)) return;
	}

	// save image
	$file = fopen($imageDir, 'w');
	if(!$file) return;
	fwrite($file, $response);
	fclose($file);
	
	return true;
}