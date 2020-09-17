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

$Player = new Player();
$Player->setLimit(100);
$playerList = $Player->getTopOnlinePlayers();

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-10 col-lg-8">';
		echo '<div class="card">';
			echo '<div class="header">Top 100 Online Characters</div>';
			echo '<div class="content table-responsive table-full-width">';
				
				if(is_array($playerList)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Name</th>';
							echo '<th>Level</th>';
							echo '<th>Played Time</th>';
							echo '<th class="text-right">Actions</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($playerList as $playerData) {
						
						$playedTimeData = sec_to_dhms(round($playerData['playedTime']/1000));
						$playedTime = '';
						if($playedTimeData[2] > 0) $playedTime = $playedTimeData[2] . ' ' . lang('rankings_txt_9');
						if($playedTimeData[1] > 0) $playedTime = $playedTimeData[1] . ' ' . lang('rankings_txt_8');
						if($playedTimeData[0] > 0) $playedTime = $playedTimeData[0] . ' ' . lang('rankings_txt_7');
						
						echo '<tr>';
							echo '<td>'.$playerData['name'].'</td>';
							echo '<td>'.$playerData['level'].'</td>';
							echo '<td>'.$playedTime.'</td>';
							echo '<td class="td-actions text-right">';
								echo '<a href="'.admincp_base('character/profile/name/'.$playerData['name']).'" rel="tooltip" title="" class="btn btn-info btn-simple btn-xs" data-original-title="Profile"><i class="fa fa-user"></i></a>';
							echo '</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'There are no characters in the database.');
				}
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';