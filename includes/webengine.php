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
 
// WebEngine CMS Version
define('__WEBENGINE_VERSION__', '2.0.0');

// DesertCore CMS Version
define('__DESERTCORE_VERSION__', '1.2.0');
define('__DESERTCORE_IS_SHOP_ENABLED__', true);
define('__DESERTCORE_IS_REDEEM_ENABLED__', true);

// CloudFlare IP Workaround
if(isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

// Encoding
@ini_set('default_charset', 'utf-8');
@ini_set('detect_unicode', '0');
@mb_internal_encoding("UTF-8");

// System Check
if(extension_loaded("mongodb") === false) throw new Exception('Missing required PHP extension "mongodb"');
if(extension_loaded("openssl") === false) throw new Exception('Missing required PHP extension "openssl"');
if(extension_loaded("PDO") === false) throw new Exception('Missing required PHP extension "PDO"');
if(extension_loaded("pdo_sqlite") === false) throw new Exception('Missing required PHP extension "pdo_sqlite"');
if(extension_loaded("curl") === false) throw new Exception('Missing required PHP extension "curl"');
if(extension_loaded("json") === false) throw new Exception('Missing required PHP extension "json"');
if(isset($_SERVER['HTTP_HOST'])) {
	if(in_array($_SERVER['HTTP_HOST'], array('localhost', '127.0.0.1'))) {
		throw new Exception('Please use a public domain to run DesertCore CMS.');
	}
}
if(!file_exists(rtrim(str_replace('\\','/', __DIR__), '/') . '/config.php')) throw new Exception('You must configure your database connection settings.');

// Server Time
if(!@include_once(rtrim(str_replace('\\','/', __DIR__), '/') . '/timezone.php')) throw new Exception('Could not load timezone setting.');

// Server Variables (CLI)
if(!isset($_SERVER['SCRIPT_NAME'])) $_SERVER['SCRIPT_NAME'] = '';
if(!isset($_SERVER['SCRIPT_FILENAME'])) $_SERVER['SCRIPT_FILENAME'] = '';

// Global Paths
define('HTTP_HOST', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'CLI');
define('SERVER_PROTOCOL', (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://');
define('__ROOT_DIR__', rtrim(str_replace('\\','/', dirname(__DIR__)), '/') . '/');
define('__RELATIVE_ROOT__', rtrim((access == 'admincp' ? dirname(dirname($_SERVER['SCRIPT_NAME'])) : dirname($_SERVER['SCRIPT_NAME'])), '\/') . '/');
define('__BASE_URL__', SERVER_PROTOCOL.HTTP_HOST.__RELATIVE_ROOT__);

// Private Paths
define('__PATH_INCLUDES__', __ROOT_DIR__.'includes/');
define('__PATH_TEMPLATES__', __ROOT_DIR__.'templates/');
define('__PATH_LANGUAGES__', __PATH_INCLUDES__ . 'languages/');
define('__PATH_CLASSES__', __PATH_INCLUDES__.'classes/');
define('__PATH_FUNCTIONS__', __PATH_INCLUDES__.'functions/');
define('__PATH_MODULES__', __ROOT_DIR__.'modules/');
define('__PATH_MODULES_USERCP__', __PATH_MODULES__.'usercp/');
define('__PATH_EMAILS__', __PATH_INCLUDES__.'emails/');
define('__PATH_CACHE__', __PATH_INCLUDES__.'cache/');
define('__PATH_ADMINCP__', __ROOT_DIR__.'admincp/');
define('__PATH_ADMINCP_INCLUDES__', __PATH_ADMINCP__.'includes/');
define('__PATH_ADMINCP_MODULES__', __PATH_ADMINCP__.'modules/');
define('__PATH_ADMINCP_TEMPLATES__', __PATH_ADMINCP__.'templates/');
define('__PATH_ADMINCP_MODULE_SETTINGS__', __PATH_ADMINCP_MODULES__.'modulemanager/module_settings/');
define('__PATH_NEWS_CACHE__', __PATH_CACHE__.'news/');
define('__PATH_PLUGINS__', __PATH_INCLUDES__.'plugins/');
define('__PATH_CONFIGS__', __PATH_INCLUDES__.'config/');
define('__PATH_MODULE_CONFIGS__', __PATH_CONFIGS__.'modules/');
define('__PATH_CRON__', __PATH_INCLUDES__.'cron/');
define('__PATH_STATIC_CONTENT__', __ROOT_DIR__.'static/');

// Public Paths
define('__ADMINCP_BASE_URL__', __BASE_URL__ . 'admincp/');
define('__ADMINCP_TEMPLATES_BASE_URL__', __ADMINCP_BASE_URL__.'templates/');
define('__TEMPLATES_BASE_URL__', __BASE_URL__ . 'templates/');
define('__API_ITEM_DATA__', __BASE_URL__ . 'api/item.php');
define('__API_SKILL_DATA__', __BASE_URL__ . 'api/skill.php');
define('__STATIC_BASE_URL__', __BASE_URL__ . 'static/');

// BDOData System (these constants should only be used within the apis)
define('__STATIC_CONTENT_BASE_URL__', dirname(__BASE_URL__) . '/static/');
define('__API_ITEM_DATA_COLLECTOR__', dirname(__BASE_URL__) . '/api/getItemData.php');
define('__API_SKILL_DATA_COLLECTOR__', dirname(__BASE_URL__) . '/api/getSkillData.php');

// Libraries
if(!@include_once(__PATH_CLASSES__ . 'class.database.php')) throw new Exception('Could not load class (database).');
if(!@include_once(__PATH_CLASSES__ . 'class.sqlite.php')) throw new Exception('Could not load class (sqlite).');
if(!@include_once(__PATH_CLASSES__ . 'mongodb/autoload.php')) throw new Exception('Could not load class (mongodb).');
if(!@include_once(__PATH_CLASSES__ . 'mongodb/functions.php')) throw new Exception('Could not load class (mongodb.functions).');
if(!@include_once(__PATH_CLASSES__ . 'class.handler.php')) throw new Exception('Could not load class (handler).');
if(!@include_once(__PATH_CLASSES__ . 'class.validator.php')) throw new Exception('Could not load class (validator).');
if(!@include_once(__PATH_CLASSES__ . 'class.filter.php')) throw new Exception('Could not load class (filter).');
if(!@include_once(__PATH_CLASSES__ . 'class.vote.php')) throw new Exception('Could not load class (vote).');
if(!@include_once(__PATH_CLASSES__ . 'class.player.php')) throw new Exception('Could not load class (player).');
if(!@include_once(__PATH_CLASSES__ . 'class.player.search.php')) throw new Exception('Could not load class (player.search).');
if(!@include_once(__PATH_CLASSES__ . 'phpmailer/autoload.php')) throw new Exception('Could not load class (phpmailer).');
if(!@include_once(__PATH_CLASSES__ . 'class.news.php')) throw new Exception('Could not load class (news).');
if(!@include_once(__PATH_CLASSES__ . 'class.email.php')) throw new Exception('Could not load class (email).');
if(!@include_once(__PATH_CLASSES__ . 'class.account.php')) throw new Exception('Could not load class (account).');
if(!@include_once(__PATH_CLASSES__ . 'class.account.login.php')) throw new Exception('Could not load class (account.login).');
if(!@include_once(__PATH_CLASSES__ . 'class.account.register.php')) throw new Exception('Could not load class (account.register).');
if(!@include_once(__PATH_CLASSES__ . 'class.account.password.php')) throw new Exception('Could not load class (account.password).');
if(!@include_once(__PATH_CLASSES__ . 'class.account.email.php')) throw new Exception('Could not load class (account.email).');
if(!@include_once(__PATH_CLASSES__ . 'class.account.search.php')) throw new Exception('Could not load class (account.search).');
if(!@include_once(__PATH_CLASSES__ . 'class.account.preferences.php')) throw new Exception('Could not load class (account.preferences).');
if(!@include_once(__PATH_CLASSES__ . 'class.session.php')) throw new Exception('Could not load class (session).');
if(!@include_once(__PATH_CLASSES__ . 'class.cron.php')) throw new Exception('Could not load class (cron).');
if(!@include_once(__PATH_CLASSES__ . 'class.language.php')) throw new Exception('Could not load class (language).');
if(!@include_once(__PATH_CLASSES__ . 'class.modulemanager.php')) throw new Exception('Could not load class (modulemanager).');
if(!@include_once(__PATH_CLASSES__ . 'class.downloads.php')) throw new Exception('Could not load class (downloads).');
if(!@include_once(__PATH_CLASSES__ . 'class.paypal.php')) throw new Exception('Could not load class (paypal).');
if(!@include_once(__PATH_CLASSES__ . 'class.tickets.php')) throw new Exception('Could not load class (tickets).');
if(!@include_once(__PATH_CLASSES__ . 'class.redeem.php')) throw new Exception('Could not load class (redeem).');
if(!@include_once(__PATH_CLASSES__ . 'class.shop.php')) throw new Exception('Could not load class (shop).');
if(!@include_once(__PATH_CLASSES__ . 'class.mail.php')) throw new Exception('Could not load class (mail).');
if(!@include_once(__PATH_CLASSES__ . 'class.exchangeplaytime.php')) throw new Exception('Could not load class (exchangeplaytime).');

// Functions
if(!@include_once(__PATH_INCLUDES__ . 'functions.php')) throw new Exception('Could not load functions.');

// Recaptcha
if(!@include_once(__PATH_CLASSES__ . 'recaptcha/autoload.php')) throw new Exception('Could not load class (recaptcha).');

// HybridAuth
if(!@include_once(__PATH_CLASSES__ . 'hybridauth/autoload.php')) throw new Exception('Could not load class (hybridauth).');

// PayPal SDK
if(!@include_once(__PATH_CLASSES__ . 'paypal/PaypalIPN.php')) throw new Exception('Could not load class (paypal).');

// Admincp Functions
if(access == 'admincp') if(!@include_once(__PATH_ADMINCP_INCLUDES__ . 'functions.php')) throw new Exception('Could not load admincp functions.');

// Configurations
$config = webengineConfigs();

// Database Configurations
if(!@include_once(rtrim(str_replace('\\','/', __DIR__), '/') . '/config.php')) throw new Exception('Could not load database configurations.');

// Check Database Configurations
if($config['MONGO_DB_USE_CONN_STRING'] === true) {
	if(!check($config['MONGO_DB_CONN_STRING'])) {
		$config['offline_mode'] = true;
	}
} else {
	if(!check($config['MONGO_DB_HOST'])) $config['offline_mode'] = true;
	if(!check($config['MONGO_DB_PORT'])) $config['offline_mode'] = true;
}

// System Status
if(!$config['system_active']) {
	header('Location: ' . $config['maintenance_page']);
	die();
}

// Debug Mode
if($config['debug']) {
	ini_set('display_errors', true);
	error_reporting(E_ALL & ~E_NOTICE);
} else {
	ini_set('display_errors', false);
	error_reporting(0);
}

// Admincp Configurations
if(access == 'admincp') {
	if(!@include_once(__PATH_ADMINCP_INCLUDES__ . 'config.php')) throw new Exception('Could not load admincp configuration file.');
}

// Table Definitions
define('_WE_BANS_', 'WebEngine_BanSystem');
define('_WE_CREDITSYS_', 'WebEngine_CreditSystem');
define('_WE_CREDITSYSLOG_', 'WebEngine_CreditSystemLogs');
define('_WE_CRON_', 'WebEngine_Cron');
define('_WE_NEWS_', 'WebEngine_News');
define('_WE_VOTES_', 'WebEngine_Votes');
define('_WE_VOTELOGS_', 'WebEngine_VoteLogs');
define('_WE_VOTESITES_', 'WebEngine_VoteSites');
define('_WE_FAILEDLOGIN_', 'WebEngine_FailedLogins');
define('_WE_SESSION_', 'WebEngine_SessionControl');
define('_WE_REGISTER_', 'WebEngine_AccountRegistration');
define('_WE_MODULES_', 'WebEngine_Modules');
define('_WE_CHANGEREQ_', 'WebEngine_ChangeRequest');
define('_WE_DOWNLOADS_', 'WebEngine_Downloads');
define('_WE_IPBLOCK_', 'WebEngine_BlockedIp');
define('_WE_ACCPREF_', 'WebEngine_AccountPreferences');
define('_WE_EMAILTEMPLATES_', 'WebEngine_EmailTemplates');
define('_WE_PAYPALPACKAGES_', 'WebEngine_PaypalPackages');
define('_WE_PAYPALLOGS_', 'WebEngine_PaypalLogs');
define('_WE_TICKETS_', 'WebEngine_Tickets');
define('_WE_TICKETMESSAGES_', 'WebEngine_TicketMessages');
define('_DC_REDEEMCODES_', 'WebEngine_RedeemCodes');
define('_DC_REDEEMLOGS_', 'WebEngine_RedeemCodesLogs');
define('_DC_SHOPCAT_', 'WebEngine_ShopCategories');
define('_DC_SHOPITEMS_', 'WebEngine_ShopItems');
define('_DC_SHOPLOGS_', 'WebEngine_ShopPurchaseLogs');
define('_DC_EXCHANGELOGS_', 'WebEngine_PlayTimeExchangeLogs');

// BDO Data
if(!@include_once(__PATH_CONFIGS__ . 'bdo.data.php')) throw new Exception('Could not load bdo data file.');

// Social
define('__FACEBOOK_PROFILE_LINK__', 'https://www.facebook.com/app_scoped_user_id/');
define('__GOOGLE_PROFILE_LINK__', 'https://plus.google.com/');

// WebEngine CMS Official
define('__WEBENGINE_WEBSITE__', 'https://desertcore.com/');
define('__WEBENGINE_NAME__', 'DesertCore CMS');

// Template
Handler::renderTemplate();