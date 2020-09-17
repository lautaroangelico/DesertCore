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

$Cron = new Cron();
$cronList = $Cron->getCronList();

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-12 col-lg-12">';
		echo '<div class="card">';
			echo '<div class="header">Cron List</div>';
			echo '<div class="content table-responsive table-full-width">';
				
				if(is_array($cronList)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th class="text-center">#</th>';
							echo '<th>Name</th>';
							echo '<th>File</th>';
							echo '<th>Repeat Every</th>';
							echo '<th>Last Run</th>';
							echo '<th class="text-right">Actions</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($cronList as $cronData) {
						$repeatTime = sec_to_dhms($cronData['cron_repeat']);
						$repeat = '';
						if($repeatTime[0] > 0) $repeat .= $repeatTime[0] . ' day(s)';
						if($repeatTime[1] > 0) $repeat .= $repeatTime[1] . ' hour(s)';
						if($repeatTime[2] > 0) $repeat .= $repeatTime[2] . ' minute(s)';
						if($repeatTime[3] > 0) $repeat .= $repeatTime[3] . ' second(s)';
						
						$lastRun = check($cronData['cron_last_run']) ? databaseTime($cronData['cron_last_run']) : '<i>never</i>';
						$description = check($cronData['cron_description']) ? '<i>'.$cronData['cron_description'].'</i>' : '';
						
						echo '<tr>';
							echo '<td class="text-center">'.$cronData['cron_id'].'</td>';
							echo '<td>'.$cronData['cron_name'].'<br />'.$description.'</td>';
							echo '<td>'.$cronData['cron_file'].'</td>';
							echo '<td>'.$repeat.'</td>';
							echo '<td>'.$lastRun.'</td>';
							echo '<td class="td-actions text-right">';
								echo '<a href="'.admincp_base('cron/edit/id/'.$cronData['cron_id']).'" rel="tooltip" title="" class="btn btn-warning btn-simple btn-xs" data-original-title="Edit"><i class="fa fa-edit"></i></a>';
								echo '<a href="#" onclick="confirmationMessage(\''.admincp_base('cron/delete/id/'.$cronData['cron_id']).'\', \'Are you sure?\', \'This action will permanently delete this cron task from the database.\', \'Confirm\', \'Cancel\')" rel="tooltip" title="" class="btn btn-danger btn-simple btn-xs" data-original-title="Delete"><i class="fa fa-times"></i></a>';
							echo '</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'There are no crons in the database.');
				}
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';