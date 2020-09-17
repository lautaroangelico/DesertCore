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
	
	// rankings config
	$cfg = loadModuleConfig('rankings');
	if(!is_array($cfg)) throw new Exception(lang('error_66'));
	
	$Player = new Player();
	$Player->setLimit($cfg['rankings_results']);
	
	if($cfg['rankings_level_experience_based'] == 1) {
		$result = $Player->getTopExperiencePlayers();
	} else {
		$result = $Player->getTopLevelPlayers();
	}
	
	$cacheData = encodeCache($result);
	updateCache('rankings_level.cache', $cacheData);
	
} catch(Exception $ex) {
	// TODO: logs system
}