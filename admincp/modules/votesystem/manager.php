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

if(check($_POST['votesite_submit'])) {
	try {
		
		$VoteSystem = new Vote();
		$VoteSystem->setTitle($_POST['votesite_title']);
		$VoteSystem->setLink($_POST['votesite_link']);
		$VoteSystem->setReward($_POST['votesite_reward']);
		$VoteSystem->setCooldown($_POST['votesite_cooldown']);
		$VoteSystem->addVotingWebsite();
		
		redirect('votesystem/manager');
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

$VoteSystem = new Vote();

echo '<div class="row">';

	echo '<div class="col-sm-12 col-md-8 col-lg-3">';
		echo '<div class="card">';
			echo '<div class="header">Add Voting Website</div>';
			echo '<div class="content">';
				
				echo '<form action="" method="post">';
				echo '<div class="form-group">';
					echo '<label for="input_1">Title</label>';
					echo '<input type="text" class="form-control" id="input_1" name="votesite_title" required autofocus>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_2">Link</label>';
					echo '<input type="text" class="form-control" id="input_2" name="votesite_link" required>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_3">Reward</label>';
					echo '<input type="text" class="form-control" id="input_3" name="votesite_reward" required>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_4">Cooldown (hours)</label>';
					echo '<input type="text" class="form-control" id="input_4" name="votesite_cooldown" value="12" required>';
				echo '</div>';
				echo '<button type="submit" class="btn btn-info" name="votesite_submit" value="ok">Add Voting Website</button>';
			echo '</form>';
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
	echo '<div class="col-sm-12 col-md-12 col-lg-9">';
		echo '<div class="card">';
			echo '<div class="header">Voting Websites List</div>';
			echo '<div class="content table-responsive table-full-width">';
				
				$votingWebsitesList = $VoteSystem->retrieveVotesites();
				if(is_array($votingWebsitesList)) {
					echo '<table class="table table-striped table-hover">';
						echo '<thead>';
							echo '<tr>';
								echo '<th>Title</th>';
								echo '<th>Link</th>';
								echo '<th>Reward</th>';
								echo '<th>Cooldown</th>';
								echo '<th class="text-right">Actions</th>';
							echo '</tr>';
						echo '</thead>';
						echo '<tbody>';
						foreach($votingWebsitesList as $votesite) {
							echo '<tr>';
								echo '<td>'.$votesite['votesite_title'].'</td>';
								echo '<td>'.$votesite['votesite_link'].'</td>';
								echo '<td>'.$votesite['votesite_reward'].'</td>';
								echo '<td>'.$votesite['votesite_cooldown'].' hrs</td>';
								echo '<td class="td-actions text-right">';
									echo '<a href="'.admincp_base('votesystem/edit/id/'.$votesite['votesite_id']).'" rel="tooltip" title="" class="btn btn-warning btn-simple btn-xs" data-original-title="Edit"><i class="fa fa-edit"></i></a>';
									echo '<a href="#" onclick="confirmationMessage(\''.admincp_base('votesystem/delete/id/'.$votesite['votesite_id']).'\', \'Are you sure?\', \'This action will permanently delete the voting website.\', \'Confirm\', \'Cancel\')" rel="tooltip" title="" class="btn btn-danger btn-simple btn-xs" data-original-title="Delete"><i class="fa fa-times"></i></a>';
								echo '</td>';
							echo '</tr>';
						}
						echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'You have not added any voting websites.');
				}
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';