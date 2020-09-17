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

// module configs
$cfg = loadModuleConfig('rankings');
if(!is_array($cfg)) throw new Exception(lang('error_66'));

// module status
if(!$cfg['rankings_enable_online']) throw new Exception(lang('error_47'));

// cache data
$rankingCache = loadCache('rankings_online.cache');
if(!is_array($rankingCache)) throw new Exception(lang('error_58'));

// display
echo '<table class="table table-striped">';
	echo '<thead>';
	echo '<tr>';
		if($cfg['rankings_show_rank_number']) echo '<th class="text-center" style="width: 80px;">'.lang('rankings_txt_1').'</th>';
		echo '<th class="text-center">'.lang('rankings_txt_2').'</th>';
		echo '<th>'.lang('rankings_txt_4').'</th>';
		echo '<th>'.lang('rankings_txt_6').'</th>';
		echo '<th class="text-center">'.lang('rankings_txt_3').'</th>';
		echo '<th>'.lang('rankings_txt_5').'</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	$i = 1;
	foreach($rankingCache as $row) {
		
		$playedTimeData = sec_to_dhms(round($row['playedTime']/1000));
		if($playedTimeData[2] > 0) $playedTime = $playedTimeData[2] . ' ' . lang('rankings_txt_9');
		if($playedTimeData[1] > 0) $playedTime = $playedTimeData[1] . ' ' . lang('rankings_txt_8');
		if($playedTimeData[0] > 0) $playedTime = $playedTimeData[0] . ' ' . lang('rankings_txt_7');
		if(!check($playedTime)) continue;
		
		echo '<tr>';
			if($cfg['rankings_show_rank_number']) {
				if($cfg['rankings_show_rank_laurels']) {
					if($i <= $cfg['rankings_rank_laurels_limit']) {
						$size = $cfg['rankings_rank_laurels_base_size']-($cfg['rankings_rank_laurels_decrease_by']*$i);
						echo '<td class="text-center"><img src="'.Handler::templateIMG().'rank_'.$i.'.png" width="'.$size.'px" height="'.$size.'px"/></td>';
					} else {
						echo '<td class="text-center">'.$i.'</td>';
					}
				} else {
					echo '<td class="text-center">'.$i.'</td>';
				}
			}
			echo '<td class="text-center">'.returnPlayerAvatar($row['classType'], true, true, 'rankings-player-class-img rounded-image-corners').'</td>';
			echo '<td>'.playerProfile($row['name']).'</td>';
			echo '<td>'.$playedTime.'</td>';
			echo '<td class="text-center">'.returnPlayerZodiac($row['zodiac'], true, true, 'rankings-player-class-img').'</td>';
			echo '<td>'.$row['level'].'</td>';
		echo '</tr>';
		$i++;
	}
	echo '</tbody>';
echo '</table>';