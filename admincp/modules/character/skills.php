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
	
	if(!check($_GET['name'])) throw new Exception('Character name not provided.');
	
	$Player = new Player();
	$Player->setPlayer($_GET['name']);
	$characterData = $Player->getPlayerInformation();
	if(!is_array($characterData)) throw new Exception('Character data could not be loaded.');
	
	$Account = new Account();
	$Account->setUserid($characterData['accountId']);
	$accountData = $Account->getAccountData();
	if(!is_array($accountData)) throw new Exception('Account data could not be loaded.');
	
	echo '<h1 class="text-info"><a href="'.admincp_base('character/profile/name/'.$characterData['name']).'">'.$characterData['name'].'</a></h1>';
	echo '<hr>';
	echo '<div class="row">';
		echo '<div class="col-sm-12 col-md-12 col-lg-6">';
			
			echo '<div class="card">';
				echo '<div class="header">Player Skills</div>';
				echo '<div class="content table-responsive table-full-width">';
					echo '<table class="table table-striped table-condensed">';
					echo '<thead>';
						echo '<tr>';
							echo '<th></th>';
							echo '<th>Skill</th>';
							echo '<th>Level</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					$skillList = $Player->getPlayerSkillList();
					if(check($skillList)) {
						foreach($skillList as $skill) {
							$desertCoreSkillData = desertCoreSkillDatabase($skill->skillId);
							$skillName = check($desertCoreSkillData['name']) ? $desertCoreSkillData['name'] : 'Unknown';
							$skillIcon = check($desertCoreSkillData['icon']) ? $desertCoreSkillData['icon'] : '';
							$skillClass = check($desertCoreSkillData['class']) ? $desertCoreSkillData['class'] : '';
							
							echo '<tr>';
								echo '<td class="text-center"><div class="profile-item-icon"><img src="'.$skillIcon.'" width="auto" height="22px" /></div></td>';
								echo '<td class="align-middle"><a href="'.bdoSkillDatabaseLink($skill->skillId).'" target="_blank">'.$skillName.'</a></td>';
								echo '<td class="align-middle">'.$skill->skillLevel.'</td>';
							echo '</tr>';
						}
					}
						echo '</tbody>';
					echo '</table>';
				echo '</div>';
			echo '</div>';
			
		echo '</div>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}