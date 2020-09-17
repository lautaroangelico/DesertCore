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
	
	// Edit news submit
	if(check($_POST['news_submit'])) {
		try {
			
			$NewsEdit = new News();
			$NewsEdit->setId($_POST['news_id']);
			$NewsEdit->setTitle($_POST['news_title']);
			$NewsEdit->setContent($_POST['news_content']);
			$NewsEdit->setAuthor($_POST['news_author']);
			$NewsEdit->setDate($_POST['news_date']);
			if(check($_POST['news_summary'])) $NewsEdit->setSummary($_POST['news_summary']);
			if(check($_POST['news_image'])) $NewsEdit->setImage($_POST['news_image']);
			
			$NewsEdit->editNews();
			message('success', 'News successfully edited!');
			
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	if(!check($_GET['id'])) throw new Exception('News id not provided.');
	
	$News = new News();
	$News->setId($_GET['id']);
	$newsData = $News->loadSingleNewsById();
	if(!is_array($newsData)) throw new Exception('News id is not valid.');
	
	echo '<div class="row">';
		echo '<div class="col-sm-12 col-md-10 col-lg-8">';
			echo '<div class="card">';
				echo '<div class="header">Edit News</div>';
				echo '<div class="content">';
					echo '<form role="form" method="post">';
						echo '<input type="hidden" name="news_id" value="'.$newsData['news_id'].'"/>';
						echo '<div class="form-group">';
							echo '<label>Title</label>';
							echo '<input type="text" name="news_title" class="form-control" value="'.$newsData['news_title'].'">';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label>Summary</label>';
							echo '<input type="text" maxlen="255" name="news_summary" class="form-control" value="'.$newsData['news_summary'].'">';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label>Image Url</label>';
							echo '<input type="text" maxlen="255" name="news_image" class="form-control" value="'.$newsData['news_image'].'">';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label>Content</label>';
							echo '<textarea name="news_content" id="news_content">'.$newsData['news_content'].'</textarea>';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label>Author</label>';
							echo '<input type="text" name="news_author" value="'.$newsData['news_author'].'" class="form-control">';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label>Date</label>';
							echo '<input type="text" name="news_date" value="'.$newsData['news_date'].'" class="form-control">';
						echo '</div>';
						echo '<button type="submit" class="btn btn-large btn-warning" name="news_submit" value="ok">Save Changes</button> ';
						echo '<a href="'.admincp_base('news/manager').'" class="btn btn-large btn-danger">Cancel</a>';
					echo '</form>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
} catch(Exception $ex) {
	if(config('debug')) {
		message('error', $ex->getMessage());
	} else {
		redirect('news/manager');
	}
}