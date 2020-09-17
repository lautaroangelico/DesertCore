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

$languagePacks = Language::getInstalledLanguagePacks();
$defaultLanguage = config('language_default');

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-12 col-lg-8">';
		echo '<div class="card">';
			echo '<div class="header">Installed Language Packs</div>';
			echo '<div class="content table-responsive table-full-width">';
				
				if(is_array($languagePacks)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Directory</th>';
							echo '<th>Locale</th>';
							echo '<th>Author</th>';
							echo '<th>Version</th>';
							echo '<th>Default</th>';
							echo '<th class="text-right">Status</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($languagePacks as $languageDir => $languageData) {
						
						$default = $languageDir == $defaultLanguage ? '<span class="label label-success">Default</span>' : '';
						$status = $languageData['active'] == 1 ? '<a href="'.admincp_base('language/status/toggle/' . $languageDir).'" rel="tooltip" title="" class="btn btn-success btn-simple btn-xs" data-original-title="Disable"><i class="fa fa-check"></i></a>' : '<a href="'.admincp_base('language/status/toggle/' . $languageDir).'" rel="tooltip" title="" class="btn btn-default btn-simple btn-xs" data-original-title="Enable"><i class="fa fa-check"></i></a>';
						
						echo '<tr>';
							echo '<td>'.$languageDir.'</td>';
							echo '<td>'.Language::getLocaleTitle($languageData['locale']).'</td>';
							echo '<td><a href="'.$languageData['website'].'" target="_blank">'.$languageData['author'].'</a></td>';
							echo '<td>'.$languageData['version'].'</td>';
							echo '<td>'.$default.'</td>';
							echo '<td class="td-actions text-right">'.$status.'</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'There are no installed language packs.');
				}
				
			echo '</div>';
		echo '</div>';
		
	echo '</div>';
echo '</div>';