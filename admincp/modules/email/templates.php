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

$Email = new Email();

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-10 col-lg-8">';
		echo '<div class="card">';
			echo '<div class="header">Email Templates List</div>';
			echo '<div class="content table-responsive table-full-width">';
				
				$templateList = $Email->loadTemplateList();
				if(is_array($templateList)) {
					echo '<table class="table table-hover table-striped">';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Template</th>';
							echo '<th>Title</th>';
							echo '<th>Phrase</th>';
							echo '<th class="text-right">Actions</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($templateList as $template) {
						echo '<tr>';
							echo '<td>'.$template['template'].'</td>';
							echo '<td>'.$template['title'].'</td>';
							echo '<td>'.(lang($template['title']) != 'ERROR' ? lang($template['title']) : '').'</td>';
							echo '<td class="td-actions text-right">';
								echo '<a href="#" onclick="confirmationMessage(\''.admincp_base('email/delete/template/'.$template['template']).'\', \'Are you sure?\', \'This action will permanently delete the email template from the database.\', \'Confirm\', \'Cancel\')" rel="tooltip" title="" class="btn btn-danger btn-simple btn-xs" data-original-title="Delete"><i class="fa fa-times"></i></a>';
							echo '</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'There are no email templates in the database.');
				}
				
			echo '</div>';
		echo '</div>';
		
		echo '<a href="'.admincp_base('email/new').'" class="btn btn-primary">New Template</a>';
		
	echo '</div>';
echo '</div>';