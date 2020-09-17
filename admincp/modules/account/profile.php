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
	
	if(!check($_GET['username'])) throw new Exception('Username not provided.');
	
	$Account = new Account();
	$Account->setUsername($_GET['username']);
	$accountData = $Account->getAccountData();
	if(!is_array($accountData)) throw new Exception('Account data could not be loaded.');
	$Account->setUserid($accountData['_id']);
	$accountInfoGameserver = $Account->getGameserverAccountData();
	
	$Player = new Player();
	$Player->setUserid($accountData['_id']);
	$characterList = $Player->getAccountPlayerList();
	
	$ipAddressList = $Account->getAccountIpList();
	
	echo '<h1 class="text-info">'.$accountData['accountName'].'</h1>';
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
							echo '<td>Id</td>';
							echo '<td>'.$accountData['_id'].'</td>';
							echo '<td class="text-right"></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Username</td>';
							echo '<td>'.$accountData['accountName'].'</td>';
							echo '<td class="text-right"></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Password</td>';
							echo '<td>******</td>';
							echo '<td class="text-right"><a href="'.admincp_base('account/password/username/'.$accountData['accountName']).'" class="btn btn-xs btn-default">Change</a></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Email Address</td>';
							echo '<td>'.$accountData['email'].'</td>';
							echo '<td class="text-right"><a href="'.admincp_base('account/email/username/'.$accountData['accountName']).'" class="btn btn-xs btn-default">Change</a></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Family</td>';
							echo '<td>'.$accountData['family'].'</td>';
							echo '<td class="text-right"></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Cash</td>';
							echo '<td>'.$accountData['cash'].'</td>';
							echo '<td class="text-right"><a href="'.admincp_base('account/cash/action/add/username/'.$accountData['accountName']).'" class="btn btn-xs btn-success">Add</a> <a href="'.admincp_base('account/cash/action/subtract/username/'.$accountData['accountName']).'" class="btn btn-xs btn-danger">Subtract</a></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Registration Date</td>';
							echo '<td>'.formatMongoDate($accountData['registrationDate']).'</td>';
							echo '<td class="text-right"></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Access Level</td>';
							echo '<td>'.$accountData['accessLvl'].'</td>';
							echo '<td class="text-right"><a href="'.admincp_base('account/accesslevel/username/'.$accountData['accountName']).'" class="btn btn-xs btn-default">Change</a></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Last IP</td>';
							echo '<td>'.$accountData['host'].'</td>';
							echo '<td class="text-right"></td>';
						echo '</tr>';
						
						if(check($accountInfoGameserver['playedTime'])) {
							$playedTime = sec_to_hms(round($accountInfoGameserver['playedTime']/1000));
							echo '<tr>';
								echo '<td>Total Played Time</td>';
								echo '<td>'.$playedTime[0].'hrs '.$playedTime[1].'min</td>';
								echo '<td class="text-right"><a href="'.admincp_base('account/exchangelogs/username/'.$accountData['accountName']).'" class="btn btn-xs btn-default">Exchange Logs</a></td>';
							echo '</tr>';
						}
						
					echo '</tbody>';
					echo '</table>';
				echo '</div>';
			echo '</div>';
			
			// web shop info
			$Shop = new Shop();
			$Shop->setUsername($accountData['accountName']);
			$shopStats = $Shop->getAccountStats();
			echo '<div class="card">';
				echo '<div class="header">Web Shop Information</div>';
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
							echo '<td>Total Purchases</td>';
							echo '<td>'.$shopStats['purchases'].'</td>';
							echo '<td class="text-right"><a href="'.admincp_base('shop/logs/username/'.$accountData['accountName']).'" class="btn btn-xs btn-default">Purchase Logs</a></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Total Cash Spent</td>';
							echo '<td>'.$shopStats['spent'].'</td>';
							echo '<td class="text-right"></td>';
						echo '</tr>';
					echo '</tbody>';
					echo '</table>';
				echo '</div>';
			echo '</div>';
			
		echo '</div>';
		
		echo '<div class="col-sm-12 col-md-12 col-lg-6">';
			
			// characters
			echo '<div class="card">';
				echo '<div class="header">Characters</div>';
				echo '<div class="content table-responsive table-full-width">';
					if(is_array($characterList)) {
						echo '<table class="table table-hover table-striped">';
						echo '<thead>';
							echo '<tr>';
								echo '<th>Name</th>';
								echo '<th class="text-right">Action</th>';
							echo '</tr>';
						echo '</thead>';
						echo '<tbody>';
						foreach($characterList as $characterData) {
							if(!is_array($characterData)) continue;
							echo '<tr>';
								echo '<td>'.$characterData['name'].'</td>';
								echo '<td class="td-actions text-right">';
									echo '<a href="'.admincp_base('character/profile/name/'.$characterData['name']).'" rel="tooltip" title="" class="btn btn-info btn-simple btn-xs" data-original-title="Profile"><i class="fa fa-user"></i></a>';
								echo '</td>';
							echo '</tr>';
						}
						echo '</tbody>';
						echo '</table>';
					} else {
						message('warning', 'No characters found in account.');
					}
				echo '</div>';
			echo '</div>';
			
			// ip list
			echo '<div class="card">';
				echo '<div class="header">IP Addresses</div>';
				echo '<div class="content table-responsive table-full-width">';
					if(is_array($ipAddressList)) {
						echo '<table class="table table-hover table-striped">';
						echo '<tbody>';
						foreach($ipAddressList as $ipAddress) {
							if(!is_array($ipAddress)) continue;
							echo '<tr>';
								echo '<td>'.$ipAddress[0].'</td>';
							echo '</tr>';
						}
						echo '</tbody>';
						echo '</table>';
					} else {
						message('warning', 'No ip addresses found for this account.');
					}
				echo '</div>';
			echo '</div>';
			
		echo '</div>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}