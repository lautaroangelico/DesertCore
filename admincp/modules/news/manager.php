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

$News = new News();

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-10 col-lg-8">';
		echo '<div class="card">';
			echo '<div class="header">News List</div>';
			echo '<div class="content table-responsive table-full-width">';
				
				$newsList = $News->getUncachedNewsList();
				if(is_array($newsList)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th class="text-center">#</th>';
							echo '<th>Date</th>';
							echo '<th>Title</th>';
							echo '<th>Author</th>';
							echo '<th class="text-right">Actions</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($newsList as $news) {
						echo '<tr>';
							echo '<td class="text-center">'.$news['news_id'].'</td>';
							echo '<td>'.databaseTime($news['news_date']).'</td>';
							echo '<td>'.$news['news_title'].'</td>';
							echo '<td>'.$news['news_author'].'</td>';
							echo '<td class="td-actions text-right">';
								echo '<a href="'.admincp_base('news/edit/id/'.$news['news_id']).'" rel="tooltip" title="" class="btn btn-warning btn-simple btn-xs" data-original-title="Edit"><i class="fa fa-edit"></i></a>';
								echo '<a href="#" onclick="confirmationMessage(\''.admincp_base('news/delete/id/'.$news['news_id']).'\', \'Are you sure?\', \'This action will permanently delete the news article.\', \'Confirm\', \'Cancel\')" rel="tooltip" title="" class="btn btn-danger btn-simple btn-xs" data-original-title="Delete"><i class="fa fa-times"></i></a>';
							echo '</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'There are no news in the database.');
				}
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';