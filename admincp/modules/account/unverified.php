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

// Actions
if(check($_GET['action'])) {
	try {
		switch($_GET['action']) {
			case 'verify':
				if(!check($_GET['username'])) throw new Exception('The requested action is missing information.');
				$AccountRegister = new AccountRegister();
				$AccountRegister->setUsername($_GET['username']);
				$AccountRegister->verifySavedRegistration();
				message('success', 'Registration manually verified!');
				break;
			case 'remove':
				if(!check($_GET['username'])) throw new Exception('The requested action is missing information.');
				$AccountRegister = new AccountRegister();
				$AccountRegister->setUsername($_GET['username']);
				$AccountRegister->removeSavedRegistration();
				message('success', 'Registration manually removed!');
				break;
			default:
				throw new Exception('The requested action is not valid.');
		}
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

$Account = new AccountRegister();
$accountList = $Account->getUnverifiedAccountsList();
$accountListCount = count($accountList);

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-10 col-lg-8">';
		echo '<div class="card">';
			echo '<div class="header">Unverified Accounts: <span class="text-info">'.number_format($accountListCount).'</span></div>';
			echo '<div class="content table-responsive table-full-width">';
				
				if(is_array($accountList)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Username</th>';
							echo '<th>Email Address</th>';
							echo '<th>Date</th>';
							echo '<th>Key</th>';
							echo '<th class="text-right">Actions</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($accountList as $accountData) {
						echo '<tr>';
							echo '<td>'.$accountData['registration_username'].'</td>';
							echo '<td>'.$accountData['registration_email'].'</td>';
							echo '<td>'.databaseTime($accountData['registration_date']).'</td>';
							echo '<td>'.$accountData['registration_key'].'</td>';
							echo '<td class="td-actions text-right">';
								echo '<a href="'.admincp_base('account/unverified/action/verify/username/'.$accountData['registration_username']).'" rel="tooltip" title="" class="btn btn-success btn-simple btn-xs" data-original-title="Verify"><i class="fa fa-check"></i></a>';
								echo '<a href="#" onclick="confirmationMessage(\''.admincp_base('account/unverified/action/remove/username/'.$accountData['registration_username']).'\', \'Are you sure?\', \'This account has not been created yet, deleting the unverified registration will allow the player to register again.\', \'Confirm\', \'Cancel\')" rel="tooltip" title="" class="btn btn-danger btn-simple btn-xs" data-original-title="Remove"><i class="fa fa-ban"></i></a>';
							echo '</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'There are no unverified accounts in the database.');
				}
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';