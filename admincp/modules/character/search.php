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

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-8 col-lg-6">';
	
		echo '<div class="card">';
			echo '<div class="header">Search Characters</div>';
			echo '<div class="content">';
				
				echo '<form action="'.admincp_base('character/search').'" method="post">';
					echo '<div class="row">';
						echo '<div class="col-sm-12 col-md-12 col-lg-12">';
							echo '<div class="form-group">';
								echo '<label for="input_2">Character Name</label>';
								echo '<input type="text" class="form-control" id="input_2" name="search_value" autofocus>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
					echo '<button type="submit" class="btn btn-info" name="search_submit" value="ok">Search</button>';
				echo '</form>';
				
			echo '</div>';
		echo '</div>';
	
	echo '</div>';
echo '</div>';

if(check($_POST['search_submit'])) {
	try {
		echo '<div class="row">';
			echo '<div class="col-sm-12 col-md-8 col-lg-6">';
				echo '<div class="card">';
					echo '<div class="header">Results for \'<span class="text-info">'.$_POST['search_value'].'</span>\'</i></div>';
					echo '<div class="content table-responsive table-full-width">';
						
						$Search = new PlayerSearch();
						$Search->setPlayer($_POST['search_value']);
						$searchResult = $Search->search();
						
						if(is_array($searchResult)) {
							echo '<table class="table table-hover table-striped">';
							echo '<thead>';
								echo '<tr>';
									echo '<th>Name</th>';
									echo '<th class="text-right">Actions</th>';
								echo '</tr>';
							echo '</thead>';
							echo '<tbody>';
							foreach($searchResult as $character) {
								echo '<tr>';
									echo '<td>'.$character['name'].'</td>';
									echo '<td class="td-actions text-right">';
										echo '<a href="'.admincp_base('character/profile/name/'.$character['name']).'" rel="tooltip" title="" class="btn btn-info btn-simple btn-xs" data-original-title="Profile"><i class="fa fa-user"></i></a>';
									echo '</td>';
								echo '</tr>';
							}
							echo '</tbody>';
							echo '</table>';
						} else {
							message('warning', 'No results found');
						}
						
					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}