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
	
	if(!check($_GET['name'])) throw new Exception('Character name not provided.');
	
	$Player = new Player();
	$Player->setPlayer($_GET['name']);
	$characterData = $Player->getPlayerInformation();
	if(!is_array($characterData)) throw new Exception('Character data could not be loaded.');
	
	$Account = new Account();
	$Account->setUserid($characterData['accountId']);
	$accountData = $Account->getAccountData();
	if(!is_array($accountData)) throw new Exception('Account data could not be loaded.');
	
	echo '<h1 class="text-info">'.$characterData['name'].'</h1>';
	echo '<hr>';
	echo '<div class="row">';
		echo '<div class="col-sm-12 col-md-12 col-lg-6">';
		
			// general info
			echo '<div class="card">';
				echo '<div class="header">General Information</div>';
				echo '<div class="content table-responsive table-full-width">';
				
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Data</th>';
							echo '<th>Value</th>';
							echo '<th class="text-right">Action</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
						echo '<tr>';
							echo '<td>Name</td>';
							echo '<td>'.$characterData['name'].'</td>';
							echo '<td class="text-right"></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Account</td>';
							echo '<td>'.$accountData['accountName'].'</td>';
							echo '<td class="text-right"><a href="'.admincp_base('account/profile/username/'.$accountData['accountName']).'" rel="tooltip" title="" class="btn btn-info btn-simple btn-xs" data-original-title="Profile"><i class="fa fa-user"></i></a></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Zodiac Sign</td>';
							echo '<td>'.zodiacSignName($characterData['zodiac']).'</td>';
							echo '<td class="text-right"></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Class</td>';
							echo '<td>'.playerClassName($characterData['classType']).'</td>';
							echo '<td class="text-right"></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Level</td>';
							echo '<td>'.$characterData['level'].'</td>';
							echo '<td class="text-right"></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Experience</td>';
							echo '<td>'.number_format($characterData['exp']).'</td>';
							echo '<td class="text-right"></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Creation Date</td>';
							echo '<td>'.formatMongoDate($characterData['creationDate']).'</td>';
							echo '<td class="text-right"></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Last Login</td>';
							echo '<td>'.formatMongoDate($characterData['lastLogin']).'</td>';
							echo '<td class="text-right"></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Last Logout</td>';
							echo '<td>'.formatMongoDate($characterData['lastLogout']).'</td>';
							echo '<td class="text-right"></td>';
						echo '</tr>';
						
						$playedTime = sec_to_dhms(round($characterData['playedTime']/1000));
						echo '<tr>';
							echo '<td>Played Time</td>';
							echo '<td>'.$playedTime[0].'d '.$playedTime[1].'h '.$playedTime[2].'m '.$playedTime[3].'s</td>';
							echo '<td class="text-right"></td>';
						echo '</tr>';
					echo '</tbody>';
					echo '</table>';
					
				echo '</div>';
			echo '</div>';
			
			
		echo '</div>';
		
		// MORE OPTIONS
		echo '<div class="col-sm-12 col-md-12 col-lg-6">';
			echo '<a href="'.admincp_base('character/equipment/name/'.$characterData['name']).'" class="btn btn-primary">Equipped Items</a> ';
			echo '<a href="'.admincp_base('character/inventory/name/'.$characterData['name']).'" class="btn btn-primary">Inventory Items</a> ';
			echo '<a href="'.admincp_base('character/skills/name/'.$characterData['name']).'" class="btn btn-primary">Player Skills</a>';
		echo '</div>';
		
	echo '</div>';
	
	echo '<a href="'.admincp_base('character/edit/name/'.$characterData['name']).'" class="btn btn-warning">Edit</a>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}