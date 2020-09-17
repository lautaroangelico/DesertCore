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
	
	$RedeemCode = new RedeemCode();
	$codesList = $RedeemCode->getRedeemCodesList();
	if(!is_array($codesList)) throw new Exception('There are no redeem codes created.');
	
	echo '<div class="card">';
		echo '<div class="header">Redeem Codes: <span class="text-info">'.number_format(count($codesList)).'</span></div>';
		echo '<div class="content table-responsive table-full-width">';
			echo '<table class="table table-striped table-hover">';
				echo '<thead>';
					echo '<tr>';
						echo '<th>Id</th>';
						echo '<th>Title</th>';
						echo '<th>Code</th>';
						echo '<th>Type</th>';
						echo '<th>Limit</th>';
						echo '<th>Expiration</th>';
						echo '<th>User</th>';
						echo '<th>Reward</th>';
						echo '<th>Status</th>';
						echo '<th></th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
					foreach($codesList as $codeData) {
						
						$status = $codeData['active'] == 1 ? '<span class="label label-success">active</span>' : '<span class="label label-danger">expired</span>';
						$expiration = check($codeData['redeem_expiration']) ? databaseTime($codeData['redeem_expiration']) : '<i>never</i>';
						$account = check($codeData['redeem_account']) ? '<a href="'.admincp_base('account/profile/username/'.$codeData['redeem_account']).'">'.$codeData['redeem_account'].'</a>' : '';
						
						echo '<tr>';
							echo '<td>'.$codeData['id'].'</td>';
							echo '<td>'.$codeData['redeem_title'].'</td>';
							echo '<td>'.$codeData['redeem_code'].'</td>';
							echo '<td>'.$codeData['redeem_type'].'</td>';
							echo '<td>'.$codeData['redeem_limit'].'</td>';
							echo '<td>'.$expiration.'</td>';
							echo '<td>'.$account.'</td>';
							echo '<td>'.number_format($codeData['redeem_cash']).'</td>';
							echo '<td>'.$status.'</td>';
							echo '<td class="pull-right">';
								if($codeData['active'] == 1) echo '<a href="'.admincp_base('redeem/disable/id/'.$codeData['id']).'" class="btn btn-default btn-xs">disable</a> ';
								echo '<a href="'.admincp_base('redeem/logs/id/'.$codeData['id']).'" class="btn btn-default btn-xs">logs</a>';
							echo '</td>';
						echo '</tr>';
					}
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}