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

class Vote {
	
	private $_userid;
	private $_username;
	private $_email;
	private $_votesideId;
	private $_accountInfo;
	private $_ip;
	
	private $_saveLogs = true;
	
	private $_topVotersStartDate;
	private $_topVotersEndDate;
	private $_topVotesCurrentYear;
	private $_topVotesYear;
	private $_topVotesMonth;
	
	private $_titleMaxLen = 50;
	private $_linkMaxLen = 200;
	
	private $_title;
	private $_link;
	private $_reward;
	private $_cooldown;
	
	function __construct() {
		
		// offline mode
		if(config('offline_mode')) throw new Exception(lang('offline_mode_error'));
		
		// database object
		$this->we = Handler::loadDB('WebEngine');
		
		// vars
		$this->_topVotersStartDate = date("Y-m-01 00:00");
		$this->_topVotersEndDate = date("Y-m-t 23:59");
		$this->_topVotesCurrentYear = date("Y");
	}
	
	/**
	 * setTitle
	 * 
	 */
	public function setTitle($title) {
		if(!check($title)) throw new Exception(lang('error_4'));
		if(!Validator::Length($title, $this->_titleMaxLen, 1)) throw new Exception(lang('error_232'));
		$this->_title = $title;
	}
	
	/**
	 * setLink
	 * 
	 */
	public function setLink($link) {
		if(!check($link)) throw new Exception(lang('error_4'));
		if(!Validator::Length($link, $this->_linkMaxLen, 1)) throw new Exception(lang('error_233'));
		$this->_link = $link;
	}
	
	/**
	 * setReward
	 * 
	 */
	public function setReward($credits) {
		if(!check($credits)) throw new Exception(lang('error_4'));
		if(!Validator::UnsignedNumber($credits)) throw new Exception(lang('error_234'));
		$this->_reward = $credits;
	}
	
	/**
	 * setCooldown
	 * 
	 */
	public function setCooldown($seconds) {
		if(!check($seconds)) throw new Exception(lang('error_4'));
		if(!Validator::UnsignedNumber($seconds)) throw new Exception(lang('error_235'));
		$this->_cooldown = $seconds;
	}
	
	/**
	 * setUserid
	 * 
	 */
	public function setUserid($userid) {
		$Account = new Account();
		$Account->setUserid($userid);
		
		$this->_accountInfo = $Account->getAccountData();
		if(!is_array($this->_accountInfo)) throw new Exception(lang('error_12'));
		
		$this->_userid = $userid;
		$this->_username = $this->_accountInfo[_CLMN_USERNM_];
		$this->_email = $this->_accountInfo[_CLMN_EMAIL_];
	}
	
	/**
	 * setVotesiteId
	 * 
	 */
	public function setVotesiteId($votesiteid) {
		if(!check($votesiteid)) throw new Exception(lang('error_23'));
		if(!Validator::UnsignedNumber($votesiteid)) throw new Exception(lang('error_23'));
		if(!$this->_siteExists($votesiteid)) throw new Exception(lang('error_23'));
		$this->_votesideId = $votesiteid;
	}
	
	/**
	 * setIp
	 * 
	 */
	public function setIp($ip) {
		if(!check($ip)) throw new Exception(lang('error_65'));
		if(!Validator::Ip($ip)) throw new Exception(lang('error_65'));
		
		$this->_ip = $ip;
	}
	
	/**
	 * vote
	 * 
	 */
	public function vote() {
		if(!check($this->_userid)) throw new Exception(lang('error_23'));
		if(!check($this->_ip)) throw new Exception(lang('error_23'));
		if(!check($this->_votesideId)) throw new Exception(lang('error_23'));
		
		// check if user can vote
		if(!$this->_canUserVote()) throw new Exception(lang('error_15'));
		
		// check if ip can vote
		if(!$this->_canIPVote()) throw new Exception(lang('error_16'));
		
		// retrieve votesite data
		$voteSite = $this->retrieveVotesites($this->_votesideId);
		if(!is_array($voteSite)) throw new Exception(lang('error_23'));
		
		$voteLink = $voteSite['votesite_link'];
		$creditsReward = $voteSite['votesite_reward'];
		
		// reward user
		$Account = new Account();
		$Account->setUserid($this->_userid);
		if(!$Account->addCash($creditsReward)) throw new Exception(lang('error_23'));
		
		// add vote record
		$this->_addRecord();
		
		// add vote log
		if($this->_saveLogs) {
			$this->_logVote();
		}
		
		// redirect
		redirect($voteLink);
	}
	
	/**
	 * setTopVotesYear
	 * 
	 */
	public function setTopVotesYear($year) {
		if(!Validator::UnsignedNumber($year)) return;
		if(!Validator::Number($year, $this->_topVotesCurrentYear, $this->_getMinimumTopVotesYear())) return;
		$this->_topVotesYear = $year;
	}
	
	/**
	 * setTopVotesMonth
	 * 
	 */
	public function setTopVotesMonth($month) {
		if(!Validator::UnsignedNumber($month)) return;
		if(!Validator::Number($month, 12, 1)) return;
		if(strlen($month) == 1) $month = 0 . $month;
		$this->_topVotesMonth = $month;
	}
	
	/**
	 * getTopVotesYearList
	 * 
	 */
	public function getTopVotesYearList() {
		$minYear = $this->_getMinimumTopVotesYear();
		$maxYear = $this->_topVotesCurrentYear;
		$offset = $maxYear-$minYear;
		if($offset >= 1) {
			for($i=0; $i<=$offset; $i++) {
				$return[] = $minYear+$i;
			}
			return $return;
		}
		return array($minYear);
	}
	
	/**
	 * getTopVoters
	 * 
	 */
	public function getTopVoters() {
		if(check($this->_topVotesYear, $this->_topVotesMonth)) {
			$this->_topVotersStartDate = $this->_topVotesYear.'-'.$this->_topVotesMonth.'-01 00:00';
			$this->_topVotersEndDate = date("Y-m-t 23:59", strtotime($this->_topVotersStartDate));
		}
		
		if(strtotime($this->_topVotersStartDate) > strtotime($this->_topVotersEndDate)) throw new Exception(lang('error_225'));
		if(strtotime($this->_topVotersStartDate) > strtotime(date("Y-m-01 00:00"))) throw new Exception(lang('error_225'));
		
		$result = $this->we->queryFetch("SELECT user_id, COUNT(user_id) as total_votes FROM "._WE_VOTELOGS_." WHERE timestamp BETWEEN ? AND ? GROUP BY user_id ORDER BY total_votes DESC", array($this->_topVotersStartDate, $this->_topVotersEndDate));
		if(!is_array($result)) return;
		
		foreach($result as $account) {
			$Account = new Account();
			$Account->setUserid($account['user_id']);
			
			$this->_accountInfo = $Account->getAccountData();
			if(!is_array($this->_accountInfo)) continue;
			
			$return[] = array(
				'username' => $this->_accountInfo['accountName'],
				'total_votes' => $account['total_votes']
			);
		}
		
		if(!is_array($return)) return;
		return $return;
	}
	
	/**
	 * addVotingWebsite
	 * 
	 */
	public function addVotingWebsite() {
		if(!check($this->_title)) throw new Exception(lang('error_4'));
		if(!check($this->_link)) throw new Exception(lang('error_4'));
		if(!check($this->_reward)) throw new Exception(lang('error_4'));
		if(!check($this->_cooldown)) throw new Exception(lang('error_4'));
		
		$result = $this->we->query("INSERT INTO "._WE_VOTESITES_." (`votesite_title`, `votesite_link`, `votesite_reward`, `votesite_cooldown`) VALUES (?, ?, ?, ?)", array($this->_title, $this->_link, $this->_reward, $this->_cooldown));
		if(!$result) return;
		return true;
	}
	
	/**
	 * deleteVotingWebsite
	 * 
	 */
	public function deleteVotingWebsite() {
		if(!check($this->_votesideId)) throw new Exception(lang('error_236'));
		
		$result = $this->we->query("DELETE FROM "._WE_VOTESITES_." WHERE `votesite_id` = ?", array($this->_votesideId));
		if(!$result) return;
		return true;
	}
	
	/**
	 * editVotingWebsite
	 * 
	 */
	public function editVotingWebsite() {
		if(!check($this->_title)) throw new Exception(lang('error_4'));
		if(!check($this->_link)) throw new Exception(lang('error_4'));
		if(!check($this->_reward)) throw new Exception(lang('error_4'));
		if(!check($this->_cooldown)) throw new Exception(lang('error_4'));
		if(!check($this->_votesideId)) throw new Exception(lang('error_4'));
		
		$result = $this->we->query("UPDATE "._WE_VOTESITES_." SET `votesite_title` = ?, `votesite_link` = ?, `votesite_reward` = ?, `votesite_cooldown` = ? WHERE `votesite_id` = ?", array($this->_title, $this->_link, $this->_reward, $this->_cooldown, $this->_votesideId));
		if(!$result) return;
		return true;
	}
	
	/**
	 * retrieveVotesites
	 * 
	 */
	public function retrieveVotesites($id=null) {
		if(check($id)) return $this->we->queryFetchSingle("SELECT * FROM "._WE_VOTESITES_." WHERE `votesite_id` = ?", array($id));
		return $this->we->queryFetch("SELECT * FROM "._WE_VOTESITES_." ORDER BY `votesite_id` ASC");
	}
	
	/**
	 * _canUserVote
	 * 
	 */
	private function _canUserVote() {
		if(!check($this->_userid)) throw new Exception(lang('error_23'));
		if(!check($this->_votesideId)) throw new Exception(lang('error_23'));
		
		$query = "SELECT * FROM "._WE_VOTES_." WHERE user_id = ? AND site_id = ?";
		$check = $this->we->queryFetchSingle($query, array($this->_userid, $this->_votesideId));
		
		if(!is_array($check)) return true;
		if($this->_timePassed($check['timestamp'])) {
			if($this->_removeRecord($check['id'])) return true;
		}
	}
	
	/**
	 * _canIPVote
	 * 
	 */
	private function _canIPVote() {
		if(!check($this->_ip)) throw new Exception(lang('error_23'));
		if(!check($this->_votesideId)) throw new Exception(lang('error_23'));
		
		$query = "SELECT * FROM "._WE_VOTES_." WHERE ip_address = ? AND site_id = ?";
		$check = $this->we->queryFetchSingle($query, array($this->_ip, $this->_votesideId));
		
		if(!is_array($check)) return true;
		if($this->_timePassed($check['timestamp'])) {
			if($this->_removeRecord($check['id'])) return true;
		}
	}
	
	/**
	 * _addRecord
	 * 
	 */
	private function _addRecord() {
		if(!check($this->_userid)) throw new Exception(lang('error_23'));
		if(!check($this->_ip)) throw new Exception(lang('error_23'));
		if(!check($this->_votesideId)) throw new Exception(lang('error_23'));
		
		$voteSiteInfo = $this->retrieveVotesites($this->_votesideId);
		if(!is_array($voteSiteInfo)) throw new Exception(lang('error_23'));
		
		$timestamp = date("Y-m-d H:i:s", strtotime(sprintf("+%d hours", $voteSiteInfo['votesite_cooldown'])));
		$data = array(
			$this->_userid,
			$this->_ip,
			$this->_votesideId,
			$timestamp
		);
		
		$add = $this->we->query("INSERT INTO "._WE_VOTES_." (user_id, ip_address, site_id, timestamp) VALUES (?, ?, ?, ?)", $data);
		if(!$add) throw new Exception(lang('error_23'));
	}
	
	/**
	 * _removeRecord
	 * 
	 */
	private function _removeRecord($id) {
		$remove = $this->we->query("DELETE FROM "._WE_VOTES_." WHERE id = ?", array($id));
		if($remove) return true;
		return false;
	}
	
	/**
	 * _timePassed
	 * 
	 */
	private function _timePassed($datetime) {
		$timestamp = strtotime(databaseTime($datetime));
		if(time() > $timestamp) return true;
		return false;
	}
	
	/**
	 * _siteExists
	 * 
	 */
	private function _siteExists($id) {
		if(!check($id)) return;
		$check = $this->we->queryFetchSingle("SELECT * FROM "._WE_VOTESITES_." WHERE votesite_id = ?", array($id));
		if(is_array($check)) return true;
		return false;
	}
	
	/**
	 * _logVote
	 * 
	 */
	private function _logVote() {
		if(!check($this->_userid)) throw new Exception(lang('error_23'));
		if(!check($this->_votesideId)) throw new Exception(lang('error_23'));
		
		$add_data = array(
			$this->_userid,
			$this->_votesideId
		);
		
		$add_log = $this->we->query("INSERT INTO "._WE_VOTELOGS_." (user_id,site_id,timestamp) VALUES (?, ?, CURRENT_TIMESTAMP)", $add_data);
		if(!$add_log) return false;
		return true;
	}
	
	/**
	 * _getMinimumTopVotesYear
	 * 
	 */
	private function _getMinimumTopVotesYear() {
		$result = $this->we->queryFetchSingle("SELECT MIN(timestamp) as result FROM "._WE_VOTELOGS_."");
		if(!is_array($result)) return date("Y");
		
		return date("Y", strtotime(databaseTime($result['result'])));
	}

}