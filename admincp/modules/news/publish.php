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

// Publish news submit
if(check($_POST['news_submit'])) {
	try {
		
		$News = new News();
		$News->setTitle($_POST['news_title']);
		$News->setContent($_POST['news_content']);
		$News->setAuthor($_POST['news_author']);
		if(check($_POST['news_summary'])) $News->setSummary($_POST['news_summary']);
		if(check($_POST['news_image'])) $News->setImage($_POST['news_image']);
		
		$News->publishNews();
		message('success', 'News successfully published!');
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-10 col-lg-8">';
		echo '<div class="card">';
			echo '<div class="header">Publish News</div>';
			echo '<div class="content">';
				echo '<form role="form" method="post">';
					echo '<div class="form-group">';
						echo '<label>Title</label>';
						echo '<input type="text" name="news_title" class="form-control">';
					echo '</div>';
					echo '<div class="form-group">';
						echo '<label>Summary</label>';
						echo '<input type="text" maxlen="255" name="news_summary" class="form-control">';
					echo '</div>';
					echo '<div class="form-group">';
						echo '<label>Image Url</label>';
						echo '<input type="text" maxlen="255" name="news_image" class="form-control">';
					echo '</div>';
					echo '<div class="form-group">';
						echo '<label>Content</label>';
						echo '<textarea name="news_content" id="news_content"></textarea>';
					echo '</div>';
					echo '<div class="form-group">';
						echo '<label>Author</label>';
						echo '<input type="text" name="news_author" value="Administrator" class="form-control">';
					echo '</div>';
					echo '<button type="submit" class="btn btn-large btn-info" name="news_submit" value="ok">Publish</button>';
				echo '</form>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';