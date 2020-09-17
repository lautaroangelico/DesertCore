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

$Account = new Account();
$adminAccounts = $Account->getAccountsByAccessLevel(4);
$gmAccounts = $Account->getAccountsByAccessLevel(3);
$modAccounts = $Account->getAccountsByAccessLevel(2);
$testerAccounts = $Account->getAccountsByAccessLevel(1);

echo '<div class="row">';
	
	// ADMIN
	echo '<div class="col-sm-12 col-md-6 col-lg-6">';
		echo '<div class="card">';
			echo '<div class="header">Admin Accounts</div>';
			echo '<div class="content table-responsive table-full-width">';
				
				if(is_array($adminAccounts)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Username</th>';
							echo '<th class="text-right">Actions</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($adminAccounts as $accountData) {
						echo '<tr>';
							echo '<td>'.$accountData['accountName'].'</td>';
							echo '<td class="td-actions text-right">';
								echo '<a href="'.admincp_base('account/profile/username/'.$accountData['accountName']).'" rel="tooltip" title="" class="btn btn-info btn-simple btn-xs" data-original-title="Profile"><i class="fa fa-user"></i></a>';
							echo '</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'No accounts found with admin access level.');
				}
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
		
	// GM
	echo '<div class="col-sm-12 col-md-6 col-lg-6">';
		echo '<div class="card">';
			echo '<div class="header">Game Master Accounts</div>';
			echo '<div class="content table-responsive table-full-width">';
				
				if(is_array($gmAccounts)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Username</th>';
							echo '<th class="text-right">Actions</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($gmAccounts as $accountData) {
						echo '<tr>';
							echo '<td>'.$accountData['accountName'].'</td>';
							echo '<td class="td-actions text-right">';
								echo '<a href="'.admincp_base('account/profile/username/'.$accountData['accountName']).'" rel="tooltip" title="" class="btn btn-info btn-simple btn-xs" data-original-title="Profile"><i class="fa fa-user"></i></a>';
							echo '</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'No accounts found with game master access level.');
				}
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
		
	// MOD
	echo '<div class="col-sm-12 col-md-6 col-lg-6">';
		echo '<div class="card">';
			echo '<div class="header">Moderator Accounts</div>';
			echo '<div class="content table-responsive table-full-width">';
				
				if(is_array($modAccounts)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Username</th>';
							echo '<th class="text-right">Actions</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($modAccounts as $accountData) {
						echo '<tr>';
							echo '<td>'.$accountData['accountName'].'</td>';
							echo '<td class="td-actions text-right">';
								echo '<a href="'.admincp_base('account/profile/username/'.$accountData['accountName']).'" rel="tooltip" title="" class="btn btn-info btn-simple btn-xs" data-original-title="Profile"><i class="fa fa-user"></i></a>';
							echo '</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'No accounts found with moderator access level.');
				}
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
		
	// TESTERS
	echo '<div class="col-sm-12 col-md-6 col-lg-6">';
		echo '<div class="card">';
			echo '<div class="header">Tester Accounts</div>';
			echo '<div class="content table-responsive table-full-width">';
				
				if(is_array($testerAccounts)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Username</th>';
							echo '<th class="text-right">Actions</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($testerAccounts as $accountData) {
						echo '<tr>';
							echo '<td>'.$accountData['accountName'].'</td>';
							echo '<td class="td-actions text-right">';
								echo '<a href="'.admincp_base('account/profile/username/'.$accountData['accountName']).'" rel="tooltip" title="" class="btn btn-info btn-simple btn-xs" data-original-title="Profile"><i class="fa fa-user"></i></a>';
							echo '</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'No accounts found with tester access level.');
				}
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
echo '</div>';