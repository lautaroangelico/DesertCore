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

// module configs
$cfg = loadModuleConfig('downloads');
if(!is_array($cfg)) throw new Exception(lang('error_66',true));

// CLIENT DOWNLOADS
if($cfg['show_client_downloads']) {
	$clientDownloads = Downloads::getDownloads('client');
	if(is_array($clientDownloads)) {
		
		echo '<table class="table downloads-table">';
		echo '<thead>';
			echo '<tr>';
				echo '<th>'.lang('downloads_txt_1').'</th>';
				echo '<th>'.lang('downloads_txt_4').'</th>';
				echo '<th></th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
			foreach($clientDownloads as $client) {
				echo '<tr>';
					echo '<td>'.(lang($client['title']) == 'ERROR' ? $client['title'] : lang($client['title'])).'</td>';
					echo '<td>'.readableFileSize($client['size']).'</td>';
					echo '<td class="text-right"><a href="'.$client['link'].'" class="btn btn-primary" target="_blank">'.lang('downloads_txt_5').'</a></td>';
				echo '</tr>';
			}
		echo '</tbody>';
		echo '</table>';
	}
}

// PATCH DOWNLOADS
if($cfg['show_patch_downloads']) {
	$patchDownloads = Downloads::getDownloads('patch');
	if(is_array($patchDownloads)) {
		
		echo '<table class="table downloads-table">';
		echo '<thead>';
			echo '<tr>';
				echo '<th>'.lang('downloads_txt_2').'</th>';
				echo '<th>'.lang('downloads_txt_4').'</th>';
				echo '<th></th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
			foreach($patchDownloads as $patch) {
				echo '<tr>';
					echo '<td>'.(lang($patch['title']) == 'ERROR' ? $patch['title'] : lang($patch['title'])).'</td>';
					echo '<td>'.readableFileSize($patch['size']).'</td>';
					echo '<td class="text-right"><a href="'.$patch['link'].'" class="btn btn-primary" target="_blank">'.lang('downloads_txt_5').'</a></td>';
				echo '</tr>';
			}
		echo '</tbody>';
		echo '</table>';
	}
}

// PATCH DOWNLOADS
if($cfg['show_other_downloads']) {
	$otherDownloads = Downloads::getDownloads('other');
	if(is_array($otherDownloads)) {
		
		echo '<table class="table downloads-table">';
		echo '<thead>';
			echo '<tr>';
				echo '<th>'.lang('downloads_txt_3').'</th>';
				echo '<th>'.lang('downloads_txt_4').'</th>';
				echo '<th></th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
			foreach($otherDownloads as $other) {
				echo '<tr>';
					echo '<td>'.(lang($other['title']) == 'ERROR' ? $other['title'] : lang($other['title'])).'</td>';
					echo '<td>'.readableFileSize($other['size']).'</td>';
					echo '<td class="text-right"><a href="'.$other['link'].'" class="btn btn-primary" target="_blank">'.lang('downloads_txt_5').'</a></td>';
				echo '</tr>';
			}
		echo '</tbody>';
		echo '</table>';
	}
}

if($cfg['show_system_requirements']) {
	echo '<h3>'.lang('downloads_sysreq_txt_1').'</h3>';
	echo '<table class="table table-bordered table-striped requirements-table">';
		echo '<thead>';
			echo '<tr>';
				echo '<th>'.lang('downloads_sysreq_txt_2').'</th>';
				echo '<th>'.lang('downloads_sysreq_txt_3').'</th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
			echo '<tr>';
				echo '<td>'.lang('downloads_sysreq_txt_4').'</td>';
				echo '<td>'.lang('downloads_sysreq_txt_5').'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>'.lang('downloads_sysreq_txt_6').'</td>';
				echo '<td>'.lang('downloads_sysreq_txt_7').'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>'.lang('downloads_sysreq_txt_8').'</td>';
				echo '<td>'.lang('downloads_sysreq_txt_9').'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>'.lang('downloads_sysreq_txt_10').'</td>';
				echo '<td>'.lang('downloads_sysreq_txt_11').'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>'.lang('downloads_sysreq_txt_12').'</td>';
				echo '<td>'.lang('downloads_sysreq_txt_13').'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>'.lang('downloads_sysreq_txt_14').'</td>';
				echo '<td>'.lang('downloads_sysreq_txt_15').'</td>';
			echo '</tr>';
		echo '</tbody>';
	echo '</table>';
}

if($cfg['show_driver_downloads']) {
	echo '<h3>'.lang('downloads_txt_6').'</h3>';
	echo '<div class="row">';
		echo '<div class="col-6 col-md-4">';
			echo '<a href="'.$cfg['driver_link_nvidia'].'" target="_blank"><img src="'.Handler::templateIMG().$cfg['driver_img_nvidia'].'" class="img-thumbnail"/></a>';
		echo '</div>';
		echo '<div class="col-6 col-md-4">';
			echo '<a href="'.$cfg['driver_link_amd'].'" target="_blank"><img src="'.Handler::templateIMG().$cfg['driver_img_amd'].'" class="img-thumbnail"/></a>';
		echo '</div>';
		echo '<div class="col-6 col-md-4">';
			echo '<a href="'.$cfg['driver_link_intel'].'" target="_blank"><img src="'.Handler::templateIMG().$cfg['driver_img_intel'].'" class="img-thumbnail"/></a>';
		echo '</div>';
	echo '</div>';
}