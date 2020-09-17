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

$defaultLanguage = config('language_default');
$phraseList = Language::getLanguagePhraseList();

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-12 col-lg-8">';
		echo '<div class="card">';
			echo '<div class="header">Language Phrases: '.$defaultLanguage.'</div>';
			echo '<div class="content table-responsive table-full-width">';
				
				if(is_array($phraseList)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Phrase</th>';
							echo '<th>Value</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($phraseList as $phrase => $value) {
						
						echo '<tr>';
							echo '<td>'.$phrase.'</td>';
							echo '<td>'.$value.'</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'There are no language phrases.');
				}
				
			echo '</div>';
		echo '</div>';
		
	echo '</div>';
echo '</div>';