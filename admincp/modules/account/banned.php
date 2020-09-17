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
$accountList = $Account->getBannedAccountsList();
$accountListCount = count($accountList);

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-10 col-lg-8">';
		echo '<div class="card">';
			echo '<div class="header">Banned Accounts: <span class="text-info">'.number_format($accountListCount).'</span></div>';
			echo '<div class="content table-responsive table-full-width">';
				
				if(is_array($accountList)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th class="text-center">#</th>';
							echo '<th>Username</th>';
							echo '<th>Email Address</th>';
							echo '<th class="text-right">Actions</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($accountList as $accountData) {
						echo '<tr>';
							echo '<td class="text-center">'.$accountData[_CLMN_MEMBID_].'</td>';
							echo '<td>'.$accountData[_CLMN_USERNM_].'</td>';
							echo '<td>'.$accountData[_CLMN_EMAIL_].'</td>';
							echo '<td class="td-actions text-right">';
								echo '<a href="'.admincp_base('account/profile/username/'.$accountData[_CLMN_USERNM_]).'" rel="tooltip" title="" class="btn btn-info btn-simple btn-xs" data-original-title="Profile"><i class="fa fa-user"></i></a>';
							echo '</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'There are no banned accounts in the database.');
				}
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';