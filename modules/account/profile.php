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
$cfg = loadModuleConfig('account.profile');
if(!is_array($cfg)) throw new Exception(lang('error_66'));

// social configs
$socialCfg = loadConfig('social');
$socialLoginStatus = is_array($socialCfg) ? $socialCfg['enabled'] : 0;

// account information
$Account = new Account();
$Account->setUserid($_SESSION['userid']);
$accountInfo = $Account->getAccountData();
if(!is_array($accountInfo)) throw new Exception(lang('error_12'));

// gameserver account information
$accountInfoGameserver = $Account->getGameserverAccountData();

// account preferences
$AccountPreferences = new AccountPreferences();
$AccountPreferences->setUsername($accountInfo['accountName']);
$AccountPreferences->createAccountPreferences();
$accountPreferencesData = $AccountPreferences->getAccountPreferencesFromUsername();

// account information
if($cfg['show_account_info']) {
	echo '<h4>'.lang('account_txt_1').'</h4>';
	echo '<table class="table account-table">';
		echo '<tr>';
			echo '<th>'.lang('account_txt_2').'</th>';
			echo '<td>'.$accountInfo['accountName'].'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<th>'.lang('account_txt_3').'</th>';
			echo '<td>'.$accountInfo['email'].'<a href="'.Handler::websiteLink('account/email').'" class="btn btn-sm btn-primary float-right">'.lang('account_txt_6').'</a></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<th>'.lang('account_txt_4').'</th>';
			echo '<td>******<a href="'.Handler::websiteLink('account/password').'" class="btn btn-sm btn-primary float-right">'.lang('account_txt_6').'</a></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<th>'.lang('account_txt_27').'</th>';
			echo '<td>'.formatMongoDate($accountInfo['registrationDate']).'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<th>'.lang('account_txt_28').'</th>';
			echo '<td>'.$accountInfo['family'].'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<th>'.lang('general_currency_name').'</th>';
			echo '<td>'.number_format($accountInfo['cash']).'<a href="'.Handler::websiteLink('shop/cash').'" class="btn btn-sm btn-primary float-right">'.lang('account_txt_8').'</a></td>';
		echo '</tr>';
		
		if(check($accountInfoGameserver['playedTime'])) {
			$playedTime = sec_to_hms(round($accountInfoGameserver['playedTime']/1000));
			echo '<tr>';
				echo '<th>'.lang('account_txt_7').'</th>';
				echo '<td>'.lang('account_txt_33', array($playedTime[0], $playedTime[1])).'</td>';
			echo '</tr>';
		}
		
		if(check($accountInfoGameserver['lastLogin'])) {
			echo '<tr>';
				echo '<th>'.lang('account_txt_34').'</th>';
				echo '<td>'.formatMongoDate($accountInfoGameserver['lastLogin']).'</td>';
			echo '</tr>';
		}
	echo '</table>';
}

// social accounts linked
if($cfg['show_social_info'] && $socialLoginStatus) {
	$facebookAccount = check($accountPreferencesData['facebook_id']) ? '<a href="'.facebookProfile($accountPreferencesData['facebook_id']).'" target="_blank">'.$accountPreferencesData['facebook_name'].'</a>' : '<span class="text-alt">'.lang('account_txt_24').'</span>';
	$facebookButton = check($accountPreferencesData['facebook_id']) ? '<a href="'.Handler::websiteLink('account/social/facebook/unlink').'" class="btn btn-sm btn-primary float-right">'.lang('account_txt_26').'</a>' : '<a href="'.Handler::websiteLink('account/social/facebook/link').'" class="btn btn-sm btn-primary float-right">'.lang('account_txt_25').'</a>';
	$googleAccount = check($accountPreferencesData['google_id']) ? '<a href="'.googleProfile($accountPreferencesData['google_id']).'" target="_blank">'.$accountPreferencesData['google_name'].'</a>' : '<span class="text-alt">'.lang('account_txt_24').'</span>';
	$googleButton = check($accountPreferencesData['google_id']) ? '<a href="'.Handler::websiteLink('account/social/google/unlink').'" class="btn btn-sm btn-primary float-right">'.lang('account_txt_26').'</a>' : '<a href="'.Handler::websiteLink('account/social/google/link').'" class="btn btn-sm btn-primary float-right">'.lang('account_txt_25').'</a>';
	
	echo '<h4>'.lang('account_txt_21').'</h4>';
	echo '<table class="table account-table">';
	if($socialCfg['provider']['facebook']['enabled']) {
		echo '<tr>';
			echo '<th>'.lang('account_txt_22').'</th>';
			echo '<td>'.$facebookAccount.' '.$facebookButton.'</td>';
		echo '</tr>';
	}
	if($socialCfg['provider']['google']['enabled']) {
		echo '<tr>';
			echo '<th>'.lang('account_txt_23').'</th>';
			echo '<td>'.$googleAccount.' '.$googleButton.'</td>';
		echo '</tr>';
	}
	echo '</table>';
}

// redeemed codes
$RedeemCode = new RedeemCode();
$RedeemCode->setUser($_SESSION['username']);
$redeemedCodes = $RedeemCode->getUserLogs();

if($cfg['show_redeemed_codes'] && is_array($redeemedCodes)) {
	
	echo '<h4>'.lang('account_txt_29').'</h4>';
	echo '<table class="table account-table">';
		echo '<tr>';
			echo '<th>'.lang('account_txt_30').'</th>';
			echo '<th>'.lang('account_txt_31').'</th>';
			echo '<th>'.lang('account_txt_32').'</th>';
		echo '</tr>';
		foreach($redeemedCodes as $redeemedCodeInfo) {
		echo '<tr>';
			echo '<td>'.$redeemedCodeInfo['redeem_title'].'</td>';
			echo '<td>'.number_format($redeemedCodeInfo['redeem_cash']).'</td>';
			echo '<td>'.databaseTime($redeemedCodeInfo['date_redeemed']).'</td>';
		echo '</tr>';
		}
	echo '</table>';
}