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
	
	$Account = new Account();
	$totalAccounts = $Account->getTotalAccountCount();
	
	$Player = new Player();
	$totalPlayers = $Player->getTotalPlayerCount();
	$totalPlayersByClass = $Player->getPlayerCountByClassList();
	$totalPlayersByZodiac = $Player->getPlayerCountByZodiacList();
	
	$result = array(
		'total_accounts' => $totalAccounts,
		'total_players' => $totalPlayers,
		'total_players_by_class' => $totalPlayersByClass,
		'total_players_by_zodiac' => $totalPlayersByZodiac
	);
	
	$cacheData = encodeCache($result);
	updateCache('server_info.cache', $cacheData);
	
} catch(Exception $ex) {
	// TODO: logs system
}