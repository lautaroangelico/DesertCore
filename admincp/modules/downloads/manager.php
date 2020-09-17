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

if(check($_POST['download_submit'])) {
	try {
		
		Downloads::addDownload($_POST['download_title'], $_POST['download_link'], $_POST['download_size'], $_POST['download_category']);
		redirect('downloads/manager');
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="row">';

	echo '<div class="col-sm-12 col-md-8 col-lg-3">';
		echo '<div class="card">';
			echo '<div class="header">Add Download</div>';
			echo '<div class="content">';
				
				echo '<form action="" method="post">';
				echo '<div class="form-group">';
					echo '<label for="input_1">Title</label>';
					echo '<input type="text" class="form-control" id="input_1" name="download_title" required autofocus>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_2">Link</label>';
					echo '<input type="text" class="form-control" id="input_2" name="download_link" required>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_3">Size (bytes)</label>';
					echo '<input type="text" class="form-control" id="input_3" name="download_size" value="0" required>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_4">File</label>';
					echo '<select class="form-control" id="input_4" name="download_category" required>';
						echo '<option value="client">Client</option>';
						echo '<option value="patch">Patch</option>';
						echo '<option value="other">Other</option>';
					echo '</select>';
				echo '</div>';
				echo '<button type="submit" class="btn btn-info" name="download_submit" value="ok">Add Download</button>';
			echo '</form>';
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
	echo '<div class="col-sm-12 col-md-12 col-lg-9">';
		echo '<div class="card">';
			echo '<div class="header">Downloads List</div>';
			echo '<div class="content table-responsive table-full-width">';
				
				$downloadsList = Downloads::getDownloadsList();
				if(is_array($downloadsList)) {
					echo '<table class="table table-striped table-hover">';
						echo '<thead>';
							echo '<tr>';
								echo '<th>Title</th>';
								echo '<th>Link</th>';
								echo '<th>Size</th>';
								echo '<th>Category</th>';
								echo '<th class="text-right">Actions</th>';
							echo '</tr>';
						echo '</thead>';
						echo '<tbody>';
						foreach($downloadsList as $download) {
							echo '<tr>';
								echo '<td>'.$download['title'].'</td>';
								echo '<td>'.$download['link'].'</td>';
								echo '<td>'.readableFileSize($download['size']).'</td>';
								echo '<td>'.$download['category'].'</td>';
								echo '<td class="td-actions text-right">';
									echo '<a href="#" onclick="confirmationMessage(\''.admincp_base('downloads/delete/id/'.$download['id']).'\', \'Are you sure?\', \'This action will permanently delete the download link.\', \'Confirm\', \'Cancel\')" rel="tooltip" title="" class="btn btn-danger btn-simple btn-xs" data-original-title="Delete"><i class="fa fa-times"></i></a>';
								echo '</td>';
							echo '</tr>';
						}
						echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'You have not added any downloads.');
				}
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';