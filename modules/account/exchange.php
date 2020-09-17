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
$cfg = loadModuleConfig('account.exchange');
if(!is_array($cfg)) throw new Exception(lang('error_66',true));

$Player = new Player();
$Player->setUserid($_SESSION['userid']);
$accountCharacters = $Player->getAccountPlayerList();
if(!is_array($accountCharacters)) throw new Exception(lang('error_46'));

$ExchangePlayTime = new ExchangePlayTime();
$ExchangePlayTime->setUsername($_SESSION['username']);
$accountPlayTime = sec_to_hms($ExchangePlayTime->getTotalPlayTime());
$accountExchangedHours = $ExchangePlayTime->getTotalExchangedHours();

// Exchange process
if(check($_GET['action']) && $_GET['action'] == 'exchange') {
	try {
		$ExchangePlayTime->exchange();
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// Success message
if(check($_GET['success'])) {
	message('success', lang('success_26', array($_GET['h'], $_GET['c'])));
}

// play time and exchanged hours
echo '<div class="row mb-5">';
	echo '<div class="col-12 py-5 account-exchange-info">';
		echo '<div class="row">';
			echo '<div class="col-4 text-center">';
				echo '<h2>Total Played Time</h2>';
				echo '<h5>'.$accountPlayTime[0].'hrs '.$accountPlayTime[1].'min</h5>';
			echo '</div>';
			echo '<div class="col-4 text-center">';
				echo '<h2>Exchanged Time</h2>';
				echo '<h5>' . $accountExchangedHours . 'hrs</h5>';
			echo '</div>';
			echo '<div class="col-4 text-center align-self-center">';
				echo '<a href="'.Handler::websiteLink('account/exchange/action/exchange/').'" class="btn btn-lg btn-primary">Exchange</a>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';

// characters list
echo '<div class="row mb-5 justify-content-center">';
foreach($accountCharacters as $characterData) {
	$playerName = $characterData['name'];
	$playerAvatar = returnPlayerAvatar($characterData['classType'], false, true, 'rounded-image-corners');
	$playedTime = sec_to_hms(round($characterData['playedTime']/1000));
	
	echo '<div class="col-3 text-center mb-5">';
		echo '<div class="account-exchange-character">';
			echo '<div class="player-name">'.$playerName.'</div>';
			echo '<div class="player-avatar">'.$playerAvatar.'</div>';
			echo '<div class="player-playedtime">';
				echo lang('exchange_txt_1', array($playedTime[0], $playedTime[1]));
			echo '</div>';
		echo '</div>';
	echo '</div>';
}
echo '</div>';

// exchange info
echo '<div class="row justify-content-center">';
	echo '<div class="col-6 text-center">';
		echo '<p>'.lang('exchange_txt_2', array($cfg['min_exchange_limit'])).'</p>';
		echo '<p>'.lang('exchange_txt_3', array($cfg['cash_per_hour'])).'</p>';
	echo '</div>';
echo '</div>';
