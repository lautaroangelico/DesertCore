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

if(!check($_GET['package'])) redirect('paypal/packages');

if(check($_POST['package_submit'])) {
	try {
		
		$PayPal = new PayPal();
		$PayPal->setId($_GET['package']);
		$PayPal->setTitle($_POST['title']);
		$PayPal->setCredits($_POST['credits']);
		$PayPal->setCost($_POST['cost']);
		$PayPal->updatePackage();
		
		redirect('paypal/packages');
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

$PayPal = new PayPal();
$PayPal->setId($_GET['package']);
$packageInfo = $PayPal->getPackageInfo();
if(!is_array($packageInfo)) redirect('paypal/packages');

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-8 col-lg-6">';
		echo '<div class="card">';
			echo '<div class="header">Edit PayPal Package</div>';
			echo '<div class="content">';
				
			echo '<form action="" method="post">';
				echo '<div class="form-group">';
					echo '<label for="input_1">Title / Phrase</label>';
					echo '<input type="text" class="form-control" id="input_1" name="title" maxlength="50" value="'.$packageInfo['title'].'" required autofocus>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_2">Cash</label>';
					echo '<input type="text" class="form-control" id="input_2" name="credits" value="'.$packageInfo['credits'].'" required>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="input_3">Cost</label>';
					echo '<input type="text" class="form-control" id="input_3" name="cost" value="'.$packageInfo['cost'].'" required>';
				echo '</div>';
				echo '<button type="submit" class="btn btn-warning" name="package_submit" value="ok">Edit Package</button> ';
				echo '<a href="'.admincp_base('paypal/packages').'" class="btn btn-large btn-danger">Cancel</a>';
			echo '</form>';
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';