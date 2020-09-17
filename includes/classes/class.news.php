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

class News {
	
	private $_titleMinLen = 1;
	private $_titleMaxLen = 100;
	private $_authorMinLen = 1;
	private $_authorMaxLen = 50;
	private $_summaryMaxLen = 255;
	private $_imageMaxLen = 255;
	
	private $_id;
	private $_title;
	private $_content;
	private $_author;
	private $_date;
	private $_requestUrl;
	private $_isSingleNews = false;
	private $_summary = null;
	private $_imageUrl = null;
	
	private $_enablePagination = true;
	private $_page = 1;
	private $_newsPerPage = 3;
	
	private $_newsData;
	
	function __construct() {
		
		// configs
		$cfg = loadModuleConfig('news');
		if(!is_array($cfg)) throw new Exception(lang('error_66'));
		$this->_enablePagination = $cfg['enable_pagination'];
		$this->_newsPerPage = $cfg['news_per_page'];
		
		// database object
		$this->we = Handler::loadDB('WebEngine');
		
	}
	
	/**
	 * setId
	 * 
	 */
	public function setId($value) {
		if(!Validator::UnsignedNumber($value)) throw new Exception(lang('error_206'));
		$this->_id = $value;
	}
	
	/**
	 * setTitle
	 * 
	 */
	public function setTitle($value) {
		if(!Validator::Length($value, $this->_titleMaxLen, $this->_titleMinLen)) throw new Exception(lang('error_207'));
		$this->_title = $value;
	}
	
	/**
	 * setContent
	 * 
	 */
	public function setContent($value) {
		$this->_content = $value;
	}
	
	/**
	 * setAuthor
	 * 
	 */
	public function setAuthor($value) {
		if(!Validator::Length($value, $this->_authorMaxLen, $this->_authorMinLen)) throw new Exception(lang('error_208'));
		$this->_author = $value;
	}
	
	/**
	 * setDate
	 * 
	 */
	public function setDate($value) {
		if(!Validator::Date($value)) throw new Exception(lang('error_305'));
		$this->_date = date("Y-m-d H:i:s", strtotime($value));
	}
	
	/**
	 * setRequestUrl
	 * 
	 */
	public function setRequestUrl($request) {
		if(!Validator::UnsignedNumber($request)) return;
		$this->_requestUrl = $request;
		$this->_isSingleNews = true;
	}
	
	/**
	 * publishNews
	 * 
	 */
	public function publishNews() {
		if(!check($this->_title)) throw new Exception(lang('error_4'));
		if(!check($this->_content)) throw new Exception(lang('error_4'));
		if(!check($this->_author)) throw new Exception(lang('error_4'));
		
		$data = array(
			'title' => $this->_title,
			'content' => $this->_content,
			'author' => $this->_author,
			'summary' => $this->_summary,
			'image' => $this->_imageUrl
		);
		
		$query = "INSERT INTO "._WE_NEWS_." (news_title, news_content, news_author, news_date, news_summary, news_image) VALUES (:title, :content, :author, CURRENT_TIMESTAMP, :summary, :image)";
		
		$result = $this->we->query($query, $data);
		if(!$result) throw new Exception(lang('error_209'));
	}
	
	/**
	 * editNews
	 * 
	 */
	public function editNews() {
		if(!check($this->_id)) throw new Exception(lang('error_4'));
		if(!check($this->_title)) throw new Exception(lang('error_4'));
		if(!check($this->_content)) throw new Exception(lang('error_4'));
		if(!check($this->_author)) throw new Exception(lang('error_4'));
		if(!check($this->_date)) throw new Exception(lang('error_4'));
		
		$data = array(
			'id' => $this->_id,
			'title' => $this->_title,
			'content' => $this->_content,
			'author' => $this->_author,
			'date' => $this->_date,
			'summary' => $this->_summary,
			'image' => $this->_imageUrl
		);
		
		$query = "UPDATE "._WE_NEWS_." SET news_title = :title, news_content = :content, news_author = :author, news_date = :date, news_summary = :summary, news_image = :image WHERE news_id = :id";
		
		$result = $this->we->query($query, $data);
		if(!$result) throw new Exception(lang('error_211'));
	}
	
	/**
	 * deleteNews
	 * 
	 */
	public function deleteNews() {
		if(!check($this->_id)) throw new Exception(lang('error_4'));
		$result = $this->we->query("DELETE FROM "._WE_NEWS_." WHERE news_id = ?", array($this->_id));
		if(!$result) throw new Exception(lang('error_213'));
	}
	
	/**
	 * getNewsList
	 * 
	 */
	public function getNewsList() {
		if($this->_isSingleNews) {
			$result = $this->we->queryFetch("SELECT * FROM "._WE_NEWS_." WHERE `news_id` = ? ORDER BY `news_date` DESC", array($this->_requestUrl));
		} else {
			if($this->_enablePagination) {
				
				$limitStart = ($this->_page-1)*$this->_newsPerPage;
				$limitEnd = $this->_newsPerPage;
				
				$result = $this->we->queryFetch("SELECT * FROM "._WE_NEWS_." ORDER BY `news_date` DESC LIMIT ?, ?", array($limitStart, $limitEnd));
			} else {
				$result = $this->we->queryFetch("SELECT * FROM "._WE_NEWS_." ORDER BY `news_date` DESC");
			}
		}
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * getUncachedNewsList
	 * 
	 */
	public function getUncachedNewsList() {
		$this->_loadNews();
		return $this->_newsData;
	}
	
	/**
	 * loadSingleNewsById
	 * 
	 */
	public function loadSingleNewsById() {
		if(!check($this->_id)) return;
		$this->_loadNews($this->_id);
		return $this->_newsData;
	}
	
	/**
	 * isSingleNews
	 * 
	 */
	public function isSingleNews() {
		return $this->_isSingleNews;
	}
	
	/**
	 * setPage
	 * 
	 */
	public function setPage($page) {
		if(!Validator::UnsignedNumber($page)) return;
		$newsCount = $this->_getNewsCount();
		$maximumPages = ceil($newsCount/$this->_newsPerPage);
		if($page > $maximumPages) {
			$this->_page = $maximumPages;
		} else {
			$this->_page = $page;
		}
	}
	
	/**
	 * getNextPage
	 * 
	 */
	public function getNextPage() {
		$next = $this->_page+1;
		$newsCount = $this->_getNewsCount();
		$maximumPages = ceil($newsCount/$this->_newsPerPage);
		return $next > $maximumPages ? $maximumPages : $next;
	}
	
	/**
	 * getPreviousPage
	 * 
	 */
	public function getPreviousPage() {
		if($this->_page == 1) return 1;
		return $this->_page-1;
	}
	
	/**
	 * setSummary
	 * 
	 */
	public function setSummary($summary) {
		if(!Validator::Length($summary, $this->_summaryMaxLen, 1)) throw new Exception(lang('error_203'));
		$this->_summary = $summary;
	}
	
	/**
	 * setImage
	 * 
	 */
	public function setImage($imageUrl) {
		if(!Validator::Length($imageUrl, $this->_imageMaxLen, 1)) throw new Exception(lang('error_204'));
		$this->_imageUrl = $imageUrl;
	}
	
	/**
	 * _loadNews
	 * 
	 */
	private function _loadNews($id="") {
		if(check($id)) {
			$result = $this->we->queryFetchSingle("SELECT * FROM "._WE_NEWS_." WHERE news_id = ?", array($id));
		} else {
			$result = $this->we->queryFetch("SELECT * FROM "._WE_NEWS_." ORDER BY news_date DESC");
		}
		if(!is_array($result)) return;
		
		$this->_newsData = $result;
	}
	
	/**
	 * _getNewsCount
	 * 
	 */
	private function _getNewsCount() {
		$result = $this->we->queryFetchSingle("SELECT COUNT(*) as `newsCount` FROM "._WE_NEWS_."");
		if(!is_array($result)) return 0;
		return $result['newsCount'];
	}
	
}