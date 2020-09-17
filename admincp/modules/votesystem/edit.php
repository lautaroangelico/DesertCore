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
	
	if(!check($_GET['id'])) throw new Exception('The provided voting website id is not valid.');
	
	// save changes
	if(check($_POST['votesite_submit'])) {
		try {
			
			$VoteSystem = new Vote();
			$VoteSystem->setVotesiteId($_GET['id']);
			$VoteSystem->setTitle($_POST['votesite_title']);
			$VoteSystem->setLink($_POST['votesite_link']);
			$VoteSystem->setReward($_POST['votesite_reward']);
			$VoteSystem->setCooldown($_POST['votesite_cooldown']);
			$VoteSystem->editVotingWebsite();
			
			redirect('votesystem/manager');
			
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	$VoteSystem = new Vote();
	$VoteSystem->setVotesiteId($_GET['id']);

	$votesiteData = $VoteSystem->retrieveVotesites($_GET['id']);
	if(!is_array($votesiteData)) throw new Exception('Could not retrieve voting website data.');

	echo '<div class="row">';

		echo '<div class="col-sm-12 col-md-8 col-lg-3">';
			echo '<div class="card">';
				echo '<div class="header">Edit Voting Website</div>';
				echo '<div class="content">';
					
					echo '<form action="" method="post">';
					echo '<div class="form-group">';
						echo '<label for="input_1">Title</label>';
						echo '<input type="text" class="form-control" id="input_1" name="votesite_title" value="'.$votesiteData['votesite_title'].'" required autofocus>';
					echo '</div>';
					echo '<div class="form-group">';
						echo '<label for="input_2">Link</label>';
						echo '<input type="text" class="form-control" id="input_2" name="votesite_link" value="'.$votesiteData['votesite_link'].'" required>';
					echo '</div>';
					echo '<div class="form-group">';
						echo '<label for="input_3">Reward</label>';
						echo '<input type="text" class="form-control" id="input_3" name="votesite_reward" value="'.$votesiteData['votesite_reward'].'" required>';
					echo '</div>';
					echo '<div class="form-group">';
						echo '<label for="input_4">Cooldown (hours)</label>';
						echo '<input type="text" class="form-control" id="input_4" name="votesite_cooldown" value="'.$votesiteData['votesite_cooldown'].'" required>';
					echo '</div>';
					echo '<button type="submit" class="btn btn-warning" name="votesite_submit" value="ok">Save Changes</button> ';
					echo '<a href="'.admincp_base('votesystem/manager').'" class="btn btn-large btn-danger">Cancel</a>';
				echo '</form>';
					
				echo '</div>';
			echo '</div>';
		echo '</div>';

	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}