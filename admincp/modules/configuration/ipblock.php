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

// ip blocking system status
if(!config('ip_block_system_enable')) {
	message('warning', 'The ip blocking system is not enabled.');
}

// block ip
if(check($_POST['block_submit'])) {
	try {
		if(!check($_POST['block_ip'])) throw new Exception('Please complete all the required fields.');
		if($_POST['block_ip'] == Handler::userIP()) throw new Exception('You cannot block your own ip.');
		
		sessionControl::blockIp($_POST['block_ip']);
		redirect('configuration/ipblock');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// unblock ip
if(check($_GET['unblock'])) {
	try {
		sessionControl::unblockIp($_GET['unblock']);
		redirect('configuration/ipblock');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// blocked ip list
$blockedIpList = sessionControl::getBlockedIpList();

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-12 col-lg-8">';
		
		// block ip
		echo '<div class="card">';
			echo '<div class="header">Blocked Ip Addresses</div>';
			echo '<div class="content">';
				echo '<form action="" method="post">';
					echo '<div class="form-group">';
						echo '<label for="input_1">Ip Address</label>';
						echo '<input type="text" class="form-control" id="input_1" name="block_ip" required autofocus>';
					echo '</div>';
					echo '<button type="submit" class="btn btn-info" name="block_submit" value="ok">Block</button>';
			echo '</form>';
			echo '</div>';
		echo '</div>';
		
		// list
		echo '<div class="card">';
			echo '<div class="header">Blocked Ip Addresses</div>';
			echo '<div class="content table-responsive table-full-width">';
				
				if(is_array($blockedIpList)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Ip Address</th>';
							echo '<th>Blocked By</th>';
							echo '<th>Date Blocked</th>';
							echo '<th class="text-right">Actions</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($blockedIpList as $blockedIpData) {
						echo '<tr>';
							echo '<td>'.$blockedIpData['ip_address'].'</td>';
							echo '<td>'.$blockedIpData['blocked_by'].'</td>';
							echo '<td>'.databaseTime($blockedIpData['blocked_date']).'</td>';
							echo '<td class="td-actions text-right">';
								echo '<a href="#" onclick="confirmationMessage(\''.admincp_base('configuration/ipblock/unblock/'.$blockedIpData['ip_address']).'\', \'Are you sure?\', \'This action will immediately unblock this ip address from the website.\', \'Confirm\', \'Cancel\')" rel="tooltip" title="" class="btn btn-danger btn-simple btn-xs" data-original-title="Delete"><i class="fa fa-times"></i></a>';
							echo '</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'There are no blocked ip addresses in the database.');
				}
				
			echo '</div>';
		echo '</div>';
		
	echo '</div>';
echo '</div>';