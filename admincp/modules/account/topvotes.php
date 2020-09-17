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
	$Vote = new Vote();
	if(check($_GET['year'])) $Vote->setTopVotesYear($_GET['year']);
	if(check($_GET['month'])) $Vote->setTopVotesMonth($_GET['month']);
	$topVotes = $Vote->getTopVoters();

	if(check($_POST['date_submit'])) {
		if(check($_POST['year'], $_POST['month'])) {
			redirect('account/topvotes/year/'.$_POST['year'].'/month/'.$_POST['month']);
		}
	}
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-8 col-lg-6">';
		
		echo '<div class="card">';
			echo '<div class="header">Date</div>';
			echo '<div class="content">';
				
			echo '<form class="form-inline" action="'.admincp_base('account/topvotes').'" method="post">';
				echo '<div class="form-group">';
					echo '<label class="sr-only" for="input_1">Year</label>';
					echo '<select class="form-control" id="input_1" name="year">';
						$yearList = $Vote->getTopVotesYearList();
						$selectedYear = (check($_GET['year']) ? $_GET['year'] : date("Y"));
						foreach($yearList as $year) {
							echo '<option value="'.$year.'"'.($selectedYear==$year ? ' selected' : '').'>'.$year.'</option>';
						}
					echo '</select>';
				echo '</div> ';
				echo '<div class="form-group">';
					echo '<label class="sr-only" for="input_1">Year</label>';
					echo '<select class="form-control" id="input_1" name="month">';
						$selectedMonth = (check($_GET['month']) ? $_GET['month'] : date("m"));
						for($m=1;$m<=12;$m++) {
							echo '<option value="'.$m.'"'.($selectedMonth==$m ? ' selected' : '').'>'.date("F", strtotime(date("Y").'-'.$m.'-01')).'</option>';
						}
					echo '</select>';
				echo '</div> ';

				echo '<button type="submit" class="btn btn-info" name="date_submit" value="ok">Submit</button>';
			echo '</form>';
				
			echo '</div>';
		echo '</div>';
		
		echo '<div class="card">';
			echo '<div class="header">Top Voters</div>';
			echo '<div class="content table-responsive table-full-width">';
				
				if(is_array($topVotes)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Username</th>';
							echo '<th>Votes</th>';
							echo '<th class="text-right">Actions</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($topVotes as $accountData) {
						echo '<tr>';
							echo '<td>'.$accountData['username'].'</td>';
							echo '<td>'.$accountData['total_votes'].'</td>';
							echo '<td class="td-actions text-right">';
								echo '<a href="'.admincp_base('account/profile/username/'.$accountData['username']).'" rel="tooltip" title="" class="btn btn-info btn-simple btn-xs" data-original-title="Profile"><i class="fa fa-user"></i></a>';
							echo '</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'There are no votes in the database.');
				}
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';