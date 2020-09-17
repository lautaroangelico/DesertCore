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

$ModuleManager = new ModuleManager();
$modulesList = $ModuleManager->getModuleList();

echo '<div class="row">';
	echo '<div class="col-sm-12 col-md-12 col-lg-10">';
		echo '<div class="card">';
			echo '<div class="header">Modules List</span></div>';
			echo '<div class="content table-responsive">';
				
				echo '<table class="table table-striped table-hover">';
					echo '<thead>';
						echo '<tr>';
							echo '<th>Parent / Path</th>';
							echo '<th>File</th>';
							echo '<th>Title</th>';
							echo '<th>Access</th>';
							echo '<th>Type</th>';
							echo '<th>Status</th>';
							echo '<th class="text-right">Actions</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach($modulesList as $module) {
						
						$title = lang($module['title']) != 'ERROR' ? '<span rel="tooltip" title="" data-original-title="'.$module['title'].'">'.lang($module['title']).'</span>' : '<span style="font-style:italic;color:#ccc">'.$module['title'].'</span>';
						$status = $module['status'] == 1 ? '<span class="label label-info">Enabled</span>' : '<span class="label label-default">Disabled</span>';
						
						echo '<tr>';
							echo '<td>'.$module['parent'].'</td>';
							echo '<td>'.$module['file'].'</td>';
							echo '<td>'.$title.'</td>';
							echo '<td>'.$module['access'].'</td>';
							echo '<td>'.$module['type'].'</td>';
							echo '<td>'.$status.'</td>';
							echo '<td class="text-right">';
								echo '<a href="'.admincp_base('modulemanager/edit/id/'.$module['id']).'" rel="tooltip" title="" class="btn btn-warning btn-simple btn-xs" data-original-title="Edit"><i class="fa fa-pencil"></i></a>';
								if($module['type'] == 'dynamic') {
									if(check($module['config_file'], $module['config_module'])) {
										echo '<a href="'.admincp_base('modulemanager/settings/id/'.$module['config_module']).'" rel="tooltip" title="" class="btn btn-default btn-simple btn-xs" data-original-title="Settings"><i class="fa fa-cog"></i></a>';
									}
								}
								echo '<a href="#" onclick="confirmationMessage(\''.admincp_base('modulemanager/delete/id/'.$module['id']).'\', \'Are you sure?\', \'This action will remove this module from the database. The module file must be deleted manually.\', \'Confirm\', \'Cancel\')" rel="tooltip" title="" class="btn btn-danger btn-simple btn-xs" data-original-title="Delete"><i class="fa fa-times"></i></a>';
							echo '</td>';
						echo '</tr>';
					}
					echo '</tbody>';
				echo '</table>';
				
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';