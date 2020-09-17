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
			echo '<div class="header">Search Accounts</div>';
			echo '<div class="content">';
				
				echo '<form action="'.admincp_base('account/search').'" method="post">';
					echo '<div class="row">';
						echo '<div class="col-sm-12 col-md-4 col-lg-3">';
							echo '<div class="form-group">';
								echo '<label for="input_1">Type</label>';
								echo '<select class="form-control" id="input_1" name="search_type">';
									echo '<option value="username">Username</option>';
									echo '<option value="email">Email Address</option>';
									echo '<option value="ip">IP Address</option>';
								echo '</select>';
							echo '</div>';
						echo '</div>';
						echo '<div class="col-sm-12 col-md-8 col-lg-9">';
							echo '<div class="form-group">';
								echo '<label for="input_2">Value</label>';
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
					echo '<div class="header">Results for \'<span class="text-info">'.$_POST['search_value'].'</span>\' on <i>'.$_POST['search_type'].'</i></div>';
					echo '<div class="content table-responsive table-full-width">';
						
						$Search = new AccountSearch();
						$Search->setSearchType($_POST['search_type']);
						$Search->setSearchValue($_POST['search_value']);
						$searchResult = $Search->search();
						
						if(is_array($searchResult)) {
							echo '<table class="table table-hover table-striped">';
							echo '<thead>';
								echo '<tr>';
									echo '<th>Username</th>';
									if($_POST['search_type'] == 'email') echo '<th>Email</th>';
									if($_POST['search_type'] == 'ip') echo '<th>Ip</th>';
									echo '<th class="text-right">Actions</th>';
								echo '</tr>';
							echo '</thead>';
							echo '<tbody>';
							foreach($searchResult as $account) {
								echo '<tr>';
									echo '<td>'.$account['username'].'</td>';
									if($_POST['search_type'] == 'email') echo '<td>'.$account['email'].'</td>';
									if($_POST['search_type'] == 'ip') echo '<td>'.$account['host'].'</td>';
									echo '<td class="td-actions text-right">';
										echo '<a href="'.admincp_base('account/profile/username/'.$account['username']).'" rel="tooltip" title="" class="btn btn-info btn-simple btn-xs" data-original-title="Profile"><i class="fa fa-user"></i></a>';
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