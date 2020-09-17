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

$vote = new Vote();

if(check($_POST['submit'])) {
	try {
		$vote->setUserid($_SESSION['userid']);
		$vote->setIp($_SERVER['REMOTE_ADDR']);
		$vote->setVotesiteId($_POST['voting_site_id']);
		$vote->vote();
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<table class="table table-striped">';
	echo '<thead>';
	echo '<tr>';
		echo '<th class="text-center">'.lang('vfc_txt_1',true).'</th>';
		echo '<th class="text-center">'.lang('vfc_txt_2',true).'</th>';
		echo '<th></th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	$vote_sites = $vote->retrieveVotesites();
	if(is_array($vote_sites)) {
		foreach($vote_sites as $thisVotesite) {
			echo '<form action="" method="post">';
				echo '<input type="hidden" name="voting_site_id" value="'.$thisVotesite['votesite_id'].'"/>';
				echo '<tr>';
					echo '<td class="text-center">'.$thisVotesite['votesite_title'].'</td>';
					echo '<td class="text-center">'.$thisVotesite['votesite_reward'].'</td>';
					echo '<td class="text-right"><button name="submit" value="submit" class="btn btn-primary">'.lang('vfc_txt_3',true).'</button></td>';
				echo '</tr>';
			echo '</form>';
		}
	}
	echo '</tbody>';
echo '</table>';