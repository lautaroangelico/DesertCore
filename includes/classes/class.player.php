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

class Player {
	
	protected $_rankingsCfg;
	
	protected $_userid;
	protected $_username;
	protected $_playerid;
	protected $_player;
	
	protected $_playerInformation;
	
	protected $_limit = 100;
	
	public $_editList;
	
	function __construct() {
		
		// offline mode
		if(config('offline_mode')) throw new Exception(lang('offline_mode_error'));
		
		// database object
		$this->db = Handler::loadDB('BDO');
		
		// rankings config
		$rcfg = loadModuleConfig('rankings');
		if(!is_array($rcfg)) throw new Exception(lang('error_66'));
		$this->_rankingsCfg = $rcfg;
		
	}
	
	/**
	 * setUserid
	 * sets the user id
	 */
	public function setUserid($value) {
		if(!check($value)) throw new Exception(lang('error_90'));
		if(!Validator::AccountId($value)) throw new Exception(lang('error_90'));
		
		$this->_userid = $value;
	}
	
	/**
	 * setUsername
	 * 
	 */
	public function setUsername($value) {
		if(!check($value)) throw new Exception(lang('error_91'));
		if(!Validator::AccountUsername($value)) throw new Exception(lang('error_91'));
		
		$this->_username = $value;
	}
	
	/**
	 * setPlayer
	 * 
	 */
	public function setPlayer($value) {
		$this->_player = $value;
	}
	
	/**
	 * setPlayerId
	 * 
	 */
	public function setPlayerId($value) {
		$this->_playerid = $value;
	}
	
	/**
	 * setLimit
	 * 
	 */
	public function setLimit($value) {
		$this->_limit = $value;
	}
	
	/**
	 * getAccountPlayerList
	 * 
	 */
	public function getAccountPlayerList() {
		if(!check($this->_userid)) throw new Exception(lang('error_90'));
		
		$collection = $this->db->gameserver->players;
		$result = $collection->find(
			[
				'accountId' => (int) $this->_userid,
			]
		);
		
		foreach($result as $characterData) {
			$accountCharacters[] = [
				'_id' => $characterData->_id,
				'name' => $characterData->name,
				'slot' => $characterData->slot,
				'zodiac' => $characterData->zodiac,
				'classType' => $characterData->classType,
				'level' => $characterData->level,
				'exp' => $characterData->exp,
				'tendency' => $characterData->tendency,
				'lastLogin' => $characterData->lastLogin,
				'lastLogout' => $characterData->lastLogout,
				'creationDate' => $characterData->creationDate,
				'deletionDate' => $characterData->deletionDate,
				'blockDate' => $characterData->blockDate,
				'playedTime' => $characterData->playedTime,
				'rescueCoolTime' => $characterData->rescueCoolTime,
				'currentWp' => $characterData->currentWp,
				'enchantFailCount' => $characterData->enchantFailCount,
				'enchantSuccessCount' => $characterData->enchantSuccessCount,
				'equipSlotCacheCount' => $characterData->equipSlotCacheCount,
				'basicCacheCount' => $characterData->basicCacheCount,
				'pcNonSavedCacheCount' => $characterData->pcNonSavedCacheCount,
				'pcCustomizationCacheCount' => $characterData->pcCustomizationCacheCount,
				'localWarPoints' => $characterData->localWarPoints,
				'creationIndex' => $characterData->creationIndex,
				'accountId' => $characterData->accountId
			];
		}
		
		if(!is_array($accountCharacters)) return;
		return $accountCharacters;
	}
	
	/**
	 * belongsToAccount
	 * 
	 */
	public function belongsToAccount() {
		if(!check($this->_userid)) throw new Exception(lang('error_90'));
		if(!check($this->_player)) throw new Exception(lang('error_24'));
		
		$collection = $this->db->gameserver->players;
		$result = $collection->findOne(
			[
				'name' => $this->_player,
				'accountId' => (int) $this->_userid,
			]
		);
		
		if($result->accountId != $this->_userid) return;
		return true;
	}
	
	/**
	 * getPlayerInformation
	 * 
	 */
	public function getPlayerInformation() {
		if(!check($this->_player)) throw new Exception(lang('error_24'));
		
		$this->_loadPlayerInformation();
		if(!is_array($this->_playerInformation)) return;
		return $this->_playerInformation;
	}
	
	/**
	 * getTotalPlayerCount
	 * 
	 */
	public function getTotalPlayerCount() {
		$result = $this->db->gameserver->players->count();
		if(!check($result)) return 0;
		return $result;
	}
	
	/**
	 * getTotalGuildCount
	 * 
	 */
	public function getTotalGuildCount() {
		
		return 0;
	}
	
	/**
	 * getTopExperiencePlayers
	 * 
	 */
	public function getTopExperiencePlayers() {
		return $this->_loadTopExperiencePlayers();
	}
	
	/**
	 * getTopLevelPlayers
	 * 
	 */
	public function getTopLevelPlayers() {
		return $this->_loadTopLevelPlayers();
	}
	
	/**
	 * getTopOnlinePlayers
	 * 
	 */
	public function getTopOnlinePlayers() {
		return $this->_loadTopOnlinePlayers();
	}
	
	/**
	 * editColumn
	 * 
	 */
	public function editColumn($column, $value) {
		if(!check($column, $value)) return;
		$this->_editList[$column] = $value;
	}
	
	/**
	 * saveEdits
	 * 
	 */
	public function saveEdits() {
		if(!is_array($this->_playerInformation)) return;
		if(!is_array($this->_editList)) return;
		
		try {
			
			$collection = $this->db->gameserver->players;
			$updateResult = $collection->updateOne(
				['_id' => (int) $this->_playerInformation['_id']],
				['$set' => $this->_editList]
			);
			
			if($updateResult->getMatchedCount() == 0) return;
			if($updateResult->getModifiedCount() == 0) return;
			
			return true;
			
		} catch(Exception $ex) {
			if(config('debug')) {
				throw new Exception($ex->getMessage());
			} else {
				return;
			}
		}
	}
	
	/**
	 * playerNameExists
	 * 
	 */
	public function playerNameExists($name) {
		if(!check($name)) return;
		try {
			$collection = $this->db->gameserver->players;
			$result = $collection->findOne(
				[
					'name' => $name,
				]
			);
			
			if(!check($result)) return;
			if(!check($result->name)) return;
			return true;
		} catch(Exception $ex) {
			if(config('debug')) {
				throw new Exception($ex->getMessage());
			} else {
				return;
			}
		}
	}
	
	/**
	 * getPlayerCountByClass
	 * 
	 */
	public function getPlayerCountByClass($class) {
		try {
			$result = $this->db->gameserver->players->count(
				[
					'classType' => $class,
				]
			);
			if(!check($result)) return 0;
			return $result;
		} catch(Exception $ex) {
			if(config('debug')) {
				throw new Exception($ex->getMessage());
			} else {
				return 0;
			}
		}
	}
	
	/**
	 * getPlayerCountByClassList
	 * 
	 */
	public function getPlayerCountByClassList() {
		$classData = custom('classType');
		$result = array();
		foreach($classData as $class => $data) {
			$count = $this->getPlayerCountByClass($class);
			$result[$class] = $count >= 1 ? $count : 0;
		}
		return $result;
	}
	
	/**
	 * getPlayerCountByZodiac
	 * 
	 */
	public function getPlayerCountByZodiac($zodiac) {
		try {
			$result = $this->db->gameserver->players->count(
				[
					'zodiac' => $zodiac,
				]
			);
			if(!check($result)) return 0;
			return $result;
		} catch(Exception $ex) {
			if(config('debug')) {
				throw new Exception($ex->getMessage());
			} else {
				return 0;
			}
		}
	}
	
	/**
	 * getPlayerCountByZodiacList
	 * 
	 */
	public function getPlayerCountByZodiacList() {
		$zodiacData = custom('zodiacSign');
		$result = array();
		foreach($zodiacData as $zodiac => $data) {
			$count = $this->getPlayerCountByZodiac($zodiac);
			$result[$zodiac] = $count >= 1 ? $count : 0;
		}
		return $result;
	}
	
	/**
	 * _loadPlayerInformation
	 * 
	 */
	protected function _loadPlayerInformation() {
		if(!check($this->_player)) return;
		
		$collection = $this->db->gameserver->players;
		$result = $collection->findOne(
			[
				'name' => $this->_player,
			]
		);
		
		if(!check($result)) return;
		if(!check($result->name)) return;
		$this->_playerInformation = (array) $result;
	}
	
	/**
	 * _loadTopExperiencePlayers
	 * 
	 */
	protected function _loadTopExperiencePlayers() {
		try {
			$collection = $this->db->gameserver->players;
			$result = $collection->find(
				[
					'name' => [
						'$nin' => $this->_getExcludedRankingsCharactersList(),
					],
				],
				[
					'projection' => [
						'name' => 1,
						'zodiac' => 1,
						'classType' => 1,
						'level' => 1,
						'exp' => 1
					],
					'limit' => (int) $this->_limit,
					'sort' => ['exp' => -1],
				]
			);
			
			if(!check($result)) return;
			foreach($result as $player) {
				$playerList[] = (array) $player;
			}
			
			return $playerList;
		} catch(Exception $ex) {
			if(config('debug')) {
				throw new Exception($ex->getMessage());
			} else {
				return;
			}
		}
	}
	
	/**
	 * _loadTopLevelPlayers
	 * 
	 */
	protected function _loadTopLevelPlayers() {
		try {
			$collection = $this->db->gameserver->players;
			$result = $collection->find(
				[
					'name' => [
						'$nin' => $this->_getExcludedRankingsCharactersList(),
					],
				],
				[
					'projection' => [
						'name' => 1,
						'zodiac' => 1,
						'classType' => 1,
						'level' => 1,
						'exp' => 1
					],
					'limit' => (int) $this->_limit,
					'sort' => ['level' => -1],
				]
			);
			
			if(!check($result)) return;
			foreach($result as $player) {
				$playerList[] = (array) $player;
			}
			
			return $playerList;
		} catch(Exception $ex) {
			if(config('debug')) {
				throw new Exception($ex->getMessage());
			} else {
				return;
			}
		}
	}
	
	/**
	 * _loadTopOnlinePlayers
	 * 
	 */
	protected function _loadTopOnlinePlayers() {
		try {
			$collection = $this->db->gameserver->players;
			$result = $collection->find(
				[
					'name' => [
						'$nin' => $this->_getExcludedRankingsCharactersList(),
					],
				],
				[
					'projection' => [
						'name' => 1,
						'zodiac' => 1,
						'classType' => 1,
						'level' => 1,
						'playedTime' => 1
					],
					'limit' => (int) $this->_limit,
					'sort' => ['playedTime' => -1],
				]
			);
			
			if(!check($result)) return;
			foreach($result as $player) {
				$playerList[] = (array) $player;
			}
			
			return $playerList;
		} catch(Exception $ex) {
			if(config('debug')) {
				throw new Exception($ex->getMessage());
			} else {
				return;
			}
		}
	}
	
	/**
	 * _getExcludedRankingsCharactersList
	 * 
	 */
	protected function _getExcludedRankingsCharactersList() {
		if(!check($this->_rankingsCfg['rankings_excluded_characters'])) return array();
		
		$excludedCharacters = explode(",", Filter::RemoveAllSpaces($this->_rankingsCfg['rankings_excluded_characters']));
		if(!is_array($excludedCharacters)) return array();
		
		return $excludedCharacters;
	}
	
	public function getPlayerInventoryItemList() {
		if(!check($this->_player)) return;
		
		$collection = $this->db->gameserver->players;
		$result = $collection->findOne(
			[
				'name' => $this->_player,
			],
			[
					'projection' => [
						'playerBag.Inventory.items' => 1,
					]
				]
		);
		
		if(!check($result)) return;
		if(!check($result->playerBag->Inventory->items)) return;
		return $result->playerBag->Inventory->items;
	}
	
	public function getPlayerEquippedItemList() {
		if(!check($this->_player)) return;
		
		$collection = $this->db->gameserver->players;
		$result = $collection->findOne(
			[
				'name' => $this->_player,
			],
			[
					'projection' => [
						'playerBag.Equipments.items' => 1,
					]
				]
		);
		
		if(!check($result)) return;
		if(!check($result->playerBag->Equipments->items)) return;
		return $result->playerBag->Equipments->items;
	}
	
	public function getPlayerSkillList() {
		if(!check($this->_player)) return;
		
		$collection = $this->db->gameserver->players;
		$result = $collection->findOne(
			[
				'name' => $this->_player,
			],
			[
					'projection' => [
						'skillList.skills' => 1,
					]
				]
		);
		
		if(!check($result)) return;
		if(!check($result->skillList->skills)) return;
		return $result->skillList->skills;
	}
	
}