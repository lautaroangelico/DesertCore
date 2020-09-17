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

// module configs
$cfg = loadModuleConfig('news');
if(!is_array($cfg)) throw new Exception(lang('error_66'));

// news object
$News = new News();

// pagination
if(check($_GET['page'])) {
	$News->setPage($_GET['page']);
}

// single news (set id)
if(check($_GET['read'])) {
	$News->setRequestUrl($_GET['read']);
}

// load news list
$newsList = $News->getNewsList();
if(!is_array($newsList)) throw new Exception(lang('error_78'));

// news list
$newsCount = 0;
foreach($newsList as $newsArticle) {
	if($newsCount >= $cfg['news_list_limit']) continue;
	
	$news_id = $newsArticle['news_id'];
	$news_title = $newsArticle['news_title'];
	$news_author = $newsArticle['news_author'];
	$news_date = databaseTime($newsArticle['news_date']);
	$news_url = Handler::websiteLink('news/read/'.$newsArticle['news_id']);
	$news_content = $newsArticle['news_content'];
	$news_list_image = check($newsArticle['news_image']) ? $newsArticle['news_image'] : __STATIC_BASE_URL__ . 'news-default.jpg';
	$news_summary = $newsArticle['news_summary'];
	if(!check($news_content)) continue;
	
	if($News->isSingleNews()) {
		
		// Single News
		echo '<article class="news">';
			echo '<h2 class="news-subject text-center"><a href="'.$news_url.'">'.$news_title.'</a></h2>';
			echo '<div class="newsdate-fullnews">';
				echo lang('news_txt_1', array($news_author, $news_date));
			echo '</div>';
			echo $news_content;
		echo '</article>';
		
	} else {
		
		// News List
		echo '<div class="row news-block">';
			echo '<div class="col-6 news-block-image" style="background-image: url(\''.$news_list_image.'\');">';
				
			echo '</div>';
			echo '<div class="col-6 news-block-content">';
				echo '<article class="news">';
						// news title
						echo '<div class="news-title">';
							echo '<h2><a href="'.$news_url.'">'.$news_title.'</a></h2>';
						echo '</div>';
						
						echo '<div class="news-summary">';
							echo $news_summary;
						echo '</div>';
						
						echo '<div class="newsdate">';
							echo date("d F Y H:00", strtotime($news_date));
						echo '</div>';
							
				echo '</article>';
			echo '</div>';
		echo '</div>';
	
	}
	
	$newsCount++;
}

// News Pagination
if($cfg['enable_pagination'] && !$News->isSingleNews()) {
	echo '<nav aria-label="News Pagination">';
		echo '<ul class="pagination pagination-lg justify-content-center">';
			echo '<li class="page-item"><a class="page-link" href="'.Handler::websiteLink('news/page/'.$News->getPreviousPage()).'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
			echo '<li class="page-item"><a class="page-link" href="'.Handler::websiteLink('news/page/'.$News->getNextPage()).'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
		echo '</ul>';
	echo '</nav>';
}
