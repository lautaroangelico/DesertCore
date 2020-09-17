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

$config['admincp_sidebar'] = array(
	'home' => array(
		'title' => 'admincp_sidebar_1',
		'icon' => 'pe-7s-home',
	),
	'news' => array(
		'title' => 'admincp_sidebar_2',
		'icon' => 'pe-7s-news-paper',
		'modules' => array(
			'publish' => 'admincp_sidebar_3',
			'manager' => 'admincp_sidebar_4',
		),
	),
	'account' => array(
		'title' => 'admincp_sidebar_5',
		'icon' => 'pe-7s-id',
		'modules' => array(
			'search' => 'admincp_sidebar_6',
			'list' => 'admincp_sidebar_7',
			'new' => 'admincp_sidebar_9',
			'topvotes' => 'admincp_sidebar_10',
			'topcash' => 'admincp_sidebar_8',
			'unverified' => 'admincp_sidebar_45',
			'staff' => 'admincp_sidebar_32',
			'exchangelogs' => 'admincp_sidebar_40',
		),
	),
	'character' => array(
		'title' => 'admincp_sidebar_14',
		'icon' => 'pe-7s-users',
		'modules' => array(
			'search' => 'admincp_sidebar_6',
			'toplevel' => 'admincp_sidebar_16',
			'toponline' => 'admincp_sidebar_20',
		),
	),
	'tickets' => array(
		'title' => 'admincp_sidebar_11',
		'icon' => 'pe-7s-chat',
		'modules' => array(
			'settings' => 'admincp_sidebar_58',
			'pending' => 'admincp_sidebar_17',
			'open' => 'admincp_sidebar_18',
			'closed' => 'admincp_sidebar_19',
		),
	),
	'shop' => array(
		'title' => 'admincp_sidebar_33',
		'icon' => 'pe-7s-shopbag',
		'modules' => array(
			'settings' => 'admincp_sidebar_58',
			'categories' => 'admincp_sidebar_34',
			'items' => 'admincp_sidebar_35',
			'logs' => 'admincp_sidebar_57',
			'mail' => 'admincp_sidebar_39',
		),
	),
	'redeem' => array(
		'title' => 'admincp_sidebar_21',
		'icon' => 'pe-7s-key',
		'modules' => array(
			'settings' => 'admincp_sidebar_58',
			'list' => 'admincp_sidebar_22',
			'logs' => 'admincp_sidebar_57',
			'new' => 'admincp_sidebar_23',
		),
	),
	'paypal' => array(
		'title' => 'admincp_sidebar_55',
		'icon' => 'pe-7s-angle-right',
		'modules' => array(
			'settings' => 'admincp_sidebar_58',
			'packages' => 'admincp_sidebar_56',
			'logs' => 'admincp_sidebar_57',
		),
	),
	'configuration' => array(
		'title' => 'admincp_sidebar_27',
		'icon' => 'pe-7s-config',
		'modules' => array(
			'website' => 'admincp_sidebar_29',
			'ipblock' => 'admincp_sidebar_31',
			'downloads' => 'admincp_sidebar_26',
			'votesystem' => 'admincp_sidebar_25',
			'recaptcha' => 'admincp_sidebar_51',
			'social' => 'admincp_sidebar_52',
		),
	),
	'modulemanager' => array(
		'title' => 'admincp_sidebar_46',
		'icon' => 'pe-7s-photo-gallery',
		'modules' => array(
			'settingsmanager' => 'admincp_sidebar_24',
			'list' => 'admincp_sidebar_47',
			'new' => 'admincp_sidebar_50',
		),
	),
	'language' => array(
		'title' => 'admincp_sidebar_36',
		'icon' => 'pe-7s-world',
		'modules' => array(
			'list' => 'admincp_sidebar_37',
			'phrases' => 'admincp_sidebar_38',
		),
	),
	'email' => array(
		'title' => 'admincp_sidebar_53',
		'icon' => 'pe-7s-mail',
		'modules' => array(
			'settings' => 'admincp_sidebar_30',
			'templates' => 'admincp_sidebar_54',
		),
	),
	'cron' => array(
		'title' => 'admincp_sidebar_42',
		'icon' => 'pe-7s-timer',
		'modules' => array(
			'new' => 'admincp_sidebar_43',
			'manager' => 'admincp_sidebar_44',
		),
	),
);