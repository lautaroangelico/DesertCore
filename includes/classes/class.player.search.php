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

class PlayerSearch extends Player {
	
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * search
	 * searches for characters based on the player name
	 */
	public function search() {
		if(!check($this->_player)) throw new Exception(lang('error_144'));
		
		$collection = $this->db->gameserver->players;
		$result = $collection->find(
			[
				'name' => new MongoDB\BSON\Regex($this->_player, 'i'),
			]
		);
		
		foreach($result as $characterData) {
			$characterList[] = [
				'name' => $characterData->name
			];
		}
		
		if(!is_array($characterList)) return;
		return $characterList;
	}
	
}