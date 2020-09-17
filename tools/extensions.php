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
		
		$extensions = get_loaded_extensions();
		if(!is_array($extensions)) die('Failed to load extension list.');
		
		echo '<h1>Loaded Extensions</h1>';
		echo '<ul>';
		foreach($extensions as $extension) {
			echo '<li>';
				echo $extension;
			echo '</li>';
		}
		echo '</ul>';
		
	} catch(Exception $ex) {
		die($ex->getMessage());
	}