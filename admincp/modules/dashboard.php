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

// check tools folder
if(file_exists(__ROOT_DIR__ . 'tools/')) {
	message('warning', 'It is recommended to delete the "tools" folder once your website\'s initial configuration is done.');
}

message('info', '<strong>Enjoying DesertCore CMS?</strong> consider donating to the project at <a href="https://desertcore.com/donate" target="_blank">https://desertcore.com/donate</a>, lots of hours of work have been spent developing this awesome cms!');

// check webengine version
@checkVersion();

// STATS
$serverInfoCache = loadCache('server_info.cache');
if(is_array($serverInfoCache)) {
	
	$Account = new AccountRegister();
	$unverifiedAccountCount = count($Account->getUnverifiedAccountsList());
	
	$Tickets = new Tickets();
	$ticketsList = $Tickets->getOpenTickets();
	$openTicketsCount = is_array($ticketsList) ? count($ticketsList) : 0;
	
	echo '<div class="row">';
		echo '<div class="col-sm-4 col-md-4 col-lg-4">';
			echo '<div class="card">';
				echo '<div class="header"><strong>'.number_format($serverInfoCache['total_accounts']).'</strong></div>';
				echo '<div class="content">';
					echo 'ACCOUNTS';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		echo '<div class="col-sm-4 col-md-4 col-lg-4">';
			echo '<div class="card">';
				echo '<div class="header"><strong>'.number_format($serverInfoCache['total_players']).'</strong></div>';
				echo '<div class="content">';
					echo 'CHARACTERS';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		echo '<div class="col-sm-4 col-md-4 col-lg-4">';
			echo '<div class="card">';
				echo '<div class="header"><strong>'.number_format($openTicketsCount).'</strong></div>';
				echo '<div class="content">';
					echo 'OPEN TICKETS';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}
// <- STATS

// IFO
echo '<div class="row">';
	
	// WebEngine Info
	echo '<div class="col-sm-12 col-md-4 col-lg-4">';
		echo '<div class="card">';
			echo '<div class="header">CMS Information</div>';
			echo '<div class="content table-responsive table-full-width">';
				
				echo '<table class="table table-condensed table-striped table-hover">';
					echo '<tbody>';
						echo '<tr>';
							echo '<td>'.__WEBENGINE_NAME__.'</td>';
							echo '<td>'.__DESERTCORE_VERSION__.'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>CMS Database Size</td>';
							echo '<td>'.readableFileSize(filesize(__PATH_INCLUDES__.'webengine.db')).'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>PHP Version</td>';
							echo '<td>'.phpversion().'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Operating System</td>';
							echo '<td>'.PHP_OS.'</td>';
						echo '</tr>';
					echo '</tbody>';
				echo '</table>';
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
	// Stats
	echo '<div class="col-sm-12 col-md-4 col-lg-4">';
		echo '<div class="card">';
			echo '<div class="header">Characters by Class</div>';
			echo '<div class="content table-responsive table-full-width">';
				if(is_array($serverInfoCache['total_players_by_class'])) {
					echo '<table class="table table-condensed table-striped table-hover">';
						echo '<tbody>';
							foreach($serverInfoCache['total_players_by_class'] as $classType => $count) {
								if($count < 1) continue;
								echo '<tr>';
									echo '<td>'.playerClassName($classType).'</td>';
									echo '<td>'.number_format($count).'</td>';
								echo '</tr>';
							}
						echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'No data found in cache, make sure to setup your master cron job.');
				}
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
	// Stats
	echo '<div class="col-sm-12 col-md-4 col-lg-4">';
		echo '<div class="card">';
			echo '<div class="header">Characters by Zodiac Sign</div>';
			echo '<div class="content table-responsive table-full-width">';
				if(is_array($serverInfoCache['total_players_by_zodiac'])) {
					echo '<table class="table table-condensed table-striped table-hover">';
						echo '<tbody>';
							foreach($serverInfoCache['total_players_by_zodiac'] as $zodiacType => $count) {
								if($count < 1) continue;
								echo '<tr>';
									echo '<td>'.zodiacSignName($zodiacType).'</td>';
									echo '<td>'.number_format($count).'</td>';
								echo '</tr>';
							}
						echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'No data found in cache, make sure to setup your master cron job.');
				}
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
echo '</div>';