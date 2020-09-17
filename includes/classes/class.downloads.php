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

class Downloads {
	
	private static $_downloadCacheFile = 'downloads.cache';
	private static $_titleMaxLen = 50;
	private static $_linkMaxLen = 200;
	private static $_categoryMaxLen = 10;
	private static $_categories = array('client', 'patch', 'other');
	
	/**
	 * getDownloads
	 * 
	 */
	public static function getDownloads($category='client') {
		$downloadsCache = self::_loadCache();
		if(!is_array($downloadsCache)) return;
		if(!is_array($downloadsCache[$category])) return;
		return $downloadsCache[$category];
	}
	
	/**
	 * getDownloadsList
	 * 
	 */
	public static function getDownloadsList() {
		return self::_loadDownloads();
	}
	
	/**
	 * addDownload
	 * 
	 */
	public static function addDownload($title, $link, $size=0, $category='client') {
		// value check
		if(!check($title)) throw new Exception(lang('error_4'));
		if(!check($link)) throw new Exception(lang('error_4'));
		if(!check($size)) throw new Exception(lang('error_4'));
		if(!check($category)) throw new Exception(lang('error_4'));
		
		// category check
		if(!in_array($category, self::$_categories)) throw new Exception(lang('error_167'));
		
		// length check
		if(!Validator::Length($title, self::$_titleMaxLen, 1)) throw new Exception(lang('error_168'));
		if(!Validator::Length($link, self::$_linkMaxLen, 1)) throw new Exception(lang('error_169'));
		if(!Validator::Length($category, self::$_categoryMaxLen, 1)) throw new Exception(lang('error_170'));
		
		// size check
		if(!Validator::UnsignedNumber($size)) throw new Exception(lang('error_231'));
		
		// database object
		$we = Handler::loadDB('WebEngine');
		
		// add download
		$result = $we->query("INSERT INTO "._WE_DOWNLOADS_." (`title`, `link`, `size`, `category`) VALUES (?, ?, ?, ?)", array($title, $link, $size, $category));
		if(!$result) throw new Exception(lang('error_171'));
		
		// update cache
		self::_updateCache();
	}
	
	/**
	 * removeDownload
	 * 
	 */
	public static function removeDownload($id) {
		if(!$id) return;
		
		// database object
		$we = Handler::loadDB('WebEngine');
		
		// remove download
		$result = $we->query("DELETE FROM "._WE_DOWNLOADS_." WHERE `id` = ?", array($id));
		if(!$result) return;
		
		// update cache
		self::_updateCache();
		
		// result
		return true;
	}
	
	/**
	 * _loadDownloads
	 * 
	 */
	private static function _loadDownloads() {
		// database object
		$we = Handler::loadDB('WebEngine');
		
		// result
		$result = $we->queryFetch("SELECT * FROM "._WE_DOWNLOADS_." ORDER BY `id` ASC");
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * _loadCache
	 * 
	 */
	private static function _loadCache() {
		return loadCache(self::$_downloadCacheFile);
	}
	
	/**
	 * _updateCache
	 * 
	 */
	private static function _updateCache() {
		$downloadsList = self::_loadDownloads();
		if(!is_array($downloadsList)) return;
		
		$cacheData = array();
		
		foreach($downloadsList as $download) {
			$cacheData[$download['category']][] = array(
				'title' => $download['title'],
				'link' => $download['link'],
				'size' => $download['size']
			);
		}
		
		if(!updateCache(self::$_downloadCacheFile, encodeCache($cacheData))) return;
		return true;
	}
	
}