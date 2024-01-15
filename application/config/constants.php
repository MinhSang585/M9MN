<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| Language Codes
|--------------------------------------------------------------------------
|
| These code are used when working with announcement and group.
|
*/
defined('LANG_EN')		OR define('LANG_EN', 1);
defined('LANG_ZH_CN') 	OR define('LANG_ZH_CN', 2);
defined('LANG_ZH_HK')  	OR define('LANG_ZH_HK', 3);
defined('LANG_ZH_TW')  	OR define('LANG_ZH_TW', 4);
defined('LANG_ID')  	OR define('LANG_ID', 5);
defined('LANG_TH')  	OR define('LANG_TH', 6);
defined('LANG_VI') 		OR define('LANG_VI', 7);
defined('LANG_KM')   	OR define('LANG_KM', 8);
defined('LANG_MY')  	OR define('LANG_MY', 9);
defined('LANG_MS')  	OR define('LANG_MS', 10);
defined('LANG_JA')  	OR define('LANG_JA', 11);
defined('LANG_KO')  	OR define('LANG_KO', 12);
defined('LANG_BN')  	OR define('LANG_BN', 13);
defined('LANG_HI')  	OR define('LANG_HI', 14);
defined('LANG_LO')  	OR define('LANG_LO', 15);
defined('LANG_TR')  	OR define('LANG_TR', 16);

/*
|--------------------------------------------------------------------------
| Status Codes
|--------------------------------------------------------------------------
|
| These code are used when working with whole system.
|
*/
defined('STATUS_INACTIVE')		OR define('STATUS_INACTIVE', 0);
defined('STATUS_ACTIVE')		OR define('STATUS_ACTIVE', 1);
defined('STATUS_SUSPEND')		OR define('STATUS_SUSPEND', 2);
defined('STATUS_PENDING')		OR define('STATUS_PENDING', 0);
defined('STATUS_ON_PENDING')	OR define('STATUS_ON_PENDING', 3);
defined('STATUS_APPROVE')		OR define('STATUS_APPROVE', 1);
defined('STATUS_COMPLETE')		OR define('STATUS_COMPLETE', 1);
defined('STATUS_CANCEL')		OR define('STATUS_CANCEL', 2);
defined('STATUS_NO')			OR define('STATUS_NO', 0);
defined('STATUS_YES')			OR define('STATUS_YES', 1);
defined('STATUS_FAIL')			OR define('STATUS_FAIL', 0);
defined('STATUS_SUCCESS')		OR define('STATUS_SUCCESS', 1);
defined('STATUS_LOGOUT')		OR define('STATUS_LOGOUT', 0);
defined('STATUS_LOGIN')			OR define('STATUS_LOGIN', 1);
defined('STATUS_DOUBLE_LOGIN')	OR define('STATUS_DOUBLE_LOGIN', 2);
defined('STATUS_ENTITLEMENT')	OR define('STATUS_ENTITLEMENT', 3);
defined('STATUS_VOID')			OR define('STATUS_VOID', 4);
defined('STATUS_ACCOMPLISH')	OR define('STATUS_ACCOMPLISH', 5);

/*
|--------------------------------------------------------------------------
| Promotion Setting
|--------------------------------------------------------------------------
|
| These code are used when working with Message Genre.
|
*/
//Promotion Calculate Type
defined('PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL')			OR define('PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL', 1);
defined('PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS')		OR define('PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS', 2);
defined('PROMOTION_CALCULATE_TYPE_VALID_BET_WIN')			OR define('PROMOTION_CALCULATE_TYPE_VALID_BET_WIN', 3);
defined('PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS')			OR define('PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS', 4);
defined('PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN')			OR define('PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN', 5);
defined('PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS')			OR define('PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS', 6);
defined('PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL')		OR define('PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL', 7);
defined('PROMOTION_CALCULATE_TYPE_WALLET_AMOUNT')			OR define('PROMOTION_CALCULATE_TYPE_WALLET_AMOUNT', 8);
//Promotion Bonus Type
defined('PROMOTION_BONUS_TYPE_PERCENTAGE')					OR define('PROMOTION_BONUS_TYPE_PERCENTAGE', 1);
defined('PROMOTION_BONUS_TYPE_FIX_AMOUNT')					OR define('PROMOTION_BONUS_TYPE_FIX_AMOUNT', 2);
//Promotion Date Type
defined('PROMOTION_DATE_TYPE_START_TO_END')					OR define('PROMOTION_DATE_TYPE_START_TO_END', 1);
defined('PROMOTION_DATE_TYPE_START_NO_LIMIT')				OR define('PROMOTION_DATE_TYPE_START_NO_LIMIT', 2);
defined('PROMOTION_DATE_TYPE_SPECIFIC_DAY_WEEK')			OR define('PROMOTION_DATE_TYPE_SPECIFIC_DAY_WEEK', 3);
defined('PROMOTION_DATE_TYPE_SPECIFIC_DAY_DAY')				OR define('PROMOTION_DATE_TYPE_SPECIFIC_DAY_DAY', 4);

//APPLY TYPE
defined('PROMOTION_USER_TYPE_SYSTEM')						OR define('PROMOTION_USER_TYPE_SYSTEM', 1);
defined('PROMOTION_USER_TYPE_ADMIN')						OR define('PROMOTION_USER_TYPE_ADMIN', 2);
defined('PROMOTION_USER_TYPE_PLAYER')						OR define('PROMOTION_USER_TYPE_PLAYER', 3);

//APPLY TYPE
defined('PROMOTION_APPLY_TYPE_SYSTEM')						OR define('PROMOTION_APPLY_TYPE_SYSTEM', 1);
defined('PROMOTION_APPLY_TYPE_ADMIN')						OR define('PROMOTION_APPLY_TYPE_ADMIN', 2);
defined('PROMOTION_APPLY_TYPE_PLAYER')						OR define('PROMOTION_APPLY_TYPE_PLAYER', 3);

//Times Limit Type
defined('PROMOTION_TIMES_LIMIT_TYPE_NO_LIMIT')				OR define('PROMOTION_TIMES_LIMIT_TYPE_NO_LIMIT', 1);
defined('PROMOTION_TIMES_LIMIT_TYPE_EVERY_DAY_ONCE')		OR define('PROMOTION_TIMES_LIMIT_TYPE_EVERY_DAY_ONCE', 2);
defined('PROMOTION_TIMES_LIMIT_TYPE_EVERY_MONTH_ONCE')		OR define('PROMOTION_TIMES_LIMIT_TYPE_EVERY_MONTH_ONCE', 3);
defined('PROMOTION_TIMES_LIMIT_TYPE_EVERY_YEARS_ONCE')		OR define('PROMOTION_TIMES_LIMIT_TYPE_EVERY_YEARS_ONCE', 4);
defined('PROMOTION_TIMES_LIMIT_TYPE_ONCE')					OR define('PROMOTION_TIMES_LIMIT_TYPE_ONCE', 5);
defined('PROMOTION_TIMES_LIMIT_TYPE_EVERY_WEEK_ONCE')		OR define('PROMOTION_TIMES_LIMIT_TYPE_EVERY_WEEK_ONCE', 6);
//bonus Range Type
defined('PROMOTION_BONUS_RANGE_TYPE_GENERAL')				OR define('PROMOTION_BONUS_RANGE_TYPE_GENERAL', 1);
defined('PROMOTION_BONUS_RANGE_TYPE_LEVEL')					OR define('PROMOTION_BONUS_RANGE_TYPE_LEVEL', 2);

defined('LIVE_CASINO_BACCARAT')								OR define('LIVE_CASINO_BACCARAT', 1);
defined('LIVE_CASINO_NON_BACCARAT')							OR define('LIVE_CASINO_NON_BACCARAT', 2);

defined('PROMOTION_DAY_TYPE_MONDAY')						OR define('PROMOTION_DAY_TYPE_MONDAY', 1);
defined('PROMOTION_DAY_TYPE_TUEDAY')						OR define('PROMOTION_DAY_TYPE_TUEDAY', 2);
defined('PROMOTION_DAY_TYPE_WEDNESDAY')						OR define('PROMOTION_DAY_TYPE_WEDNESDAY', 3);
defined('PROMOTION_DAY_TYPE_THURSDAY')						OR define('PROMOTION_DAY_TYPE_THURSDAY', 4);
defined('PROMOTION_DAY_TYPE_FRIDAY')						OR define('PROMOTION_DAY_TYPE_FRIDAY', 5);
defined('PROMOTION_DAY_TYPE_SATURDAY')						OR define('PROMOTION_DAY_TYPE_SATURDAY', 6);
defined('PROMOTION_DAY_TYPE_SUNDAY')						OR define('PROMOTION_DAY_TYPE_SUNDAY', 7);
defined('PROMOTION_DAY_TYPE_EVERYDAY')						OR define('PROMOTION_DAY_TYPE_EVERYDAY', 8);
defined('PROMOTION_DAY_TYPE_EVERYTIME')						OR define('PROMOTION_DAY_TYPE_EVERYTIME', 9);


defined('PROMOTION_TYPE_DE')								OR define('PROMOTION_TYPE_DE', "DE");
defined('PROMOTION_TYPE_FD')								OR define('PROMOTION_TYPE_FD', "FD");
defined('PROMOTION_TYPE_SD')								OR define('PROMOTION_TYPE_SD', "SD");
defined('PROMOTION_TYPE_LE')								OR define('PROMOTION_TYPE_LE', "LE");
defined('PROMOTION_TYPE_CR')								OR define('PROMOTION_TYPE_CR', "CR");
defined('PROMOTION_TYPE_RP')								OR define('PROMOTION_TYPE_RP', "RP");
defined('PROMOTION_TYPE_BN')								OR define('PROMOTION_TYPE_BN', "BN");
defined('PROMOTION_TYPE_DPR')								OR define('PROMOTION_TYPE_DPR', "DPR");
defined('PROMOTION_TYPE_RF')								OR define('PROMOTION_TYPE_RF', "RF");
defined('PROMOTION_TYPE_UDB')								OR define('PROMOTION_TYPE_UDB', "UDB");
defined('PROMOTION_TYPE_WRLV')								OR define('PROMOTION_TYPE_WRLV', "WRLV");
defined('PROMOTION_TYPE_MEGA_SL')							OR define('PROMOTION_TYPE_MEGA_SL', "MEGA_SL");
defined('PROMOTION_TYPE_PUS8')								OR define('PROMOTION_TYPE_PUS8', "PUS8");
defined('PROMOTION_TYPE_BIRTH')								OR define('PROMOTION_TYPE_BIRTH', "BIRTH");
defined('BONUS_RANGE_NUMBER')								OR define('BONUS_RANGE_NUMBER', 5);

defined('PROMOTION_REMARK_REACH_TARGET')								OR define('PROMOTION_REMARK_REACH_TARGET', 1);
defined('PROMOTION_REMARK_LOSS_ALL')									OR define('PROMOTION_REMARK_LOSS_ALL', 2);


defined('DEPOSIT_PROMOTION_SUCCESSS')									OR define('DEPOSIT_PROMOTION_SUCCESSS', 1);
defined('DEPOSIT_PROMOTION_UNKNOWN_ERROR')								OR define('DEPOSIT_PROMOTION_UNKNOWN_ERROR', 2);
defined('DEPOSIT_PROMOTION_PROMOTION_NOT_AVAILABLE')					OR define('DEPOSIT_PROMOTION_PROMOTION_NOT_AVAILABLE', 3);
defined('DEPOSIT_PROMOTION_PROMOTION_NOT_REACH_EXPIRATE_DATE')			OR define('DEPOSIT_PROMOTION_PROMOTION_NOT_REACH_EXPIRATE_DATE', 4);
defined('DEPOSIT_PROMOTION_PROMOTION_REACH_CLAIM_LIMIT')				OR define('DEPOSIT_PROMOTION_PROMOTION_REACH_CLAIM_LIMIT', 5);
defined('DEPOSIT_PROMOTION_FIRST_DEPOSIT')								OR define('DEPOSIT_PROMOTION_FIRST_DEPOSIT', 6);
defined('DEPOSIT_PROMOTION_DAILY_FIRST_DEPOSIT')						OR define('DEPOSIT_PROMOTION_DAILY_FIRST_DEPOSIT', 7);
defined('DEPOSIT_PROMOTION_AMOUNT_NOT_REACH_MINIMUM')					OR define('DEPOSIT_PROMOTION_AMOUNT_NOT_REACH_MINIMUM', 8);
defined('DEPOSIT_PROMOTION_PROMOTION_PENDING_EXITS')					OR define('DEPOSIT_PROMOTION_PROMOTION_PENDING_EXITS', 9);
defined('DEPOSIT_PROMOTION_PROMOTION_NOT_ALLOW_DATE')					OR define('DEPOSIT_PROMOTION_PROMOTION_NOT_ALLOW_DATE', 10);
defined('DEPOSIT_PROMOTION_PROMOTION_NOT_BIRTH_DATE')					OR define('DEPOSIT_PROMOTION_PROMOTION_NOT_BIRTH_DATE', 11);

defined('MAINTAIN_MEMBERSHIP_TYPE_WEEKLY')								OR define('MAINTAIN_MEMBERSHIP_TYPE_WEEKLY', 1);
defined('MAINTAIN_MEMBERSHIP_TYPE_MONTHLY')								OR define('MAINTAIN_MEMBERSHIP_TYPE_MONTHLY', 2);

defined('LEVEL_MOVEMENT_UP')								OR define('LEVEL_MOVEMENT_UP', 1);
defined('LEVEL_MOVEMENT_DOWN')								OR define('LEVEL_MOVEMENT_DOWN', 2);
defined('LEVEL_MOVEMENT_NONE')								OR define('LEVEL_MOVEMENT_NONE', 3);


defined('DEPOSIT_PROMOTION_SUCCESSS')									OR define('DEPOSIT_PROMOTION_SUCCESSS', 1);
defined('DEPOSIT_PROMOTION_UNKNOWN_ERROR')								OR define('DEPOSIT_PROMOTION_UNKNOWN_ERROR', 2);
defined('DEPOSIT_PROMOTION_PROMOTION_NOT_AVAILABLE')					OR define('DEPOSIT_PROMOTION_PROMOTION_NOT_AVAILABLE', 3);
defined('DEPOSIT_PROMOTION_PROMOTION_NOT_REACH_EXPIRATE_DATE')			OR define('DEPOSIT_PROMOTION_PROMOTION_NOT_REACH_EXPIRATE_DATE', 4);
defined('DEPOSIT_PROMOTION_PROMOTION_REACH_CLAIM_LIMIT')				OR define('DEPOSIT_PROMOTION_PROMOTION_REACH_CLAIM_LIMIT', 5);
defined('DEPOSIT_PROMOTION_FIRST_DEPOSIT')								OR define('DEPOSIT_PROMOTION_FIRST_DEPOSIT', 6);
defined('DEPOSIT_PROMOTION_DAILY_FIRST_DEPOSIT')						OR define('DEPOSIT_PROMOTION_DAILY_FIRST_DEPOSIT', 7);
defined('DEPOSIT_PROMOTION_AMOUNT_NOT_REACH_MINIMUM')					OR define('DEPOSIT_PROMOTION_AMOUNT_NOT_REACH_MINIMUM', 8);
defined('DEPOSIT_PROMOTION_PROMOTION_PENDING_EXITS')					OR define('DEPOSIT_PROMOTION_PROMOTION_PENDING_EXITS', 9);
defined('DEPOSIT_PROMOTION_BALANCE_MUST_LESS')							OR define('DEPOSIT_PROMOTION_BALANCE_MUST_LESS', 10);
defined('DEPOSIT_PROMOTION_FIXED_DEPOSIT')								OR define('DEPOSIT_PROMOTION_FIXED_DEPOSIT', 11);
/*
|--------------------------------------------------------------------------
| Force Codes
|--------------------------------------------------------------------------
|
| These code are used when working with player.
|
*/
defined('TYPE_UNFORCE')		OR define('TYPE_UNFORCE', 0);
defined('TYPE_FORCE')		OR define('TYPE_FORCE', 1);

/*
|--------------------------------------------------------------------------
| System Type
|--------------------------------------------------------------------------
|
| These code are used when working with Message Type.
|
*/
defined('PROMOTION_TYPE_STRICT_BASED')			OR define('PROMOTION_TYPE_STRICT_BASED', 1);
defined('PROMOTION_TYPE_PLAYER_BASED')			OR define('PROMOTION_TYPE_PLAYER_BASED', 2);

/*
|--------------------------------------------------------------------------
| Gender Codes
|--------------------------------------------------------------------------
|
| These code are used when working with player.
|
*/
defined('GENDER_MALE')		OR define('GENDER_MALE', 1);
defined('GENDER_FEMALE')	OR define('GENDER_FEMALE', 2);

/*
|--------------------------------------------------------------------------
| Platform Codes
|--------------------------------------------------------------------------
|
| These code are used when working with platform.
|
*/
defined('PLATFORM_WEB')				OR define('PLATFORM_WEB', 1);
defined('PLATFORM_MOBILE_WEB')		OR define('PLATFORM_MOBILE_WEB', 2);
defined('PLATFORM_APP_ANDROID')		OR define('PLATFORM_APP_ANDROID', 3);
defined('PLATFORM_APP_IOS')			OR define('PLATFORM_APP_IOS', 4);

/*
|--------------------------------------------------------------------------
| Deposit Type Codes
|--------------------------------------------------------------------------
|
| These code are used when working with deposit.
|
*/
defined('DEPOSIT_OFFLINE_BANKING')	OR define('DEPOSIT_OFFLINE_BANKING', 1);
defined('DEPOSIT_ONLINE_BANKING')	OR define('DEPOSIT_ONLINE_BANKING', 2);
defined('DEPOSIT_CREDIT_CARD')	OR define('DEPOSIT_CREDIT_CARD', 3);
defined('DEPOSIT_HYPERMART')	OR define('DEPOSIT_HYPERMART', 4);
/*
|--------------------------------------------------------------------------
| Withdrawal Type Codes
|--------------------------------------------------------------------------
|
| These code are used when working with withdrawal.
|
*/
defined('WITHDRAWAL_OFFLINE_BANKING')	OR define('WITHDRAWAL_OFFLINE_BANKING', 1);

/*
|--------------------------------------------------------------------------
| Wallet Status
|--------------------------------------------------------------------------
|
| These code are used when working with user.
|
*/
defined('WALLET_UNLOCK')					OR define('WALLET_UNLOCK', 0);
defined('WALLET_LOCK')						OR define('WALLET_LOCK', 1);

/*
|--------------------------------------------------------------------------
| Player Type
|--------------------------------------------------------------------------
|
| These code are used when working with user.
|
*/
defined('PLAYER_TYPE_CASH_MARKET')			OR define('PLAYER_TYPE_CASH_MARKET', 1);
defined('PLAYER_TYPE_CREDIT_MARKET')		OR define('PLAYER_TYPE_CREDIT_MARKET', 2);
defined('PLAYER_TYPE_MG_MARKET')		    OR define('PLAYER_TYPE_MG_MARKET', 3);
defined('MG_PLAYER_API_URL')		        OR define('MG_PLAYER_API_URL', "");
defined('MG_PLAYER_API_MERCHANT_ID')       	OR define('MG_PLAYER_API_MERCHANT_ID', "m9mbet");
defined('MG_PLAYER_API_SECRET_KEY')       	OR define('MG_PLAYER_API_SECRET_KEY', "M9sWhBVau8MWcREkmkAKfrKqh1FPKdZd");

defined('BANK_TYPE_CASH')       			OR define('BANK_TYPE_CASH', 0);
defined('BANK_TYPE_CRYTO')		            OR define('BANK_TYPE_CRYTO', 1);
defined('PLAYER_BANK_TYPE_CASH')       		OR define('PLAYER_BANK_TYPE_CASH', 1);
defined('PLAYER_BANK_TYPE_CRYTO')		    OR define('PLAYER_BANK_TYPE_CRYTO', 3);
/*
|--------------------------------------------------------------------------
| System Type
|--------------------------------------------------------------------------
|
| These code are used when working with user.
|
*/
defined('SYSTEM_TYPE_SINGLE_WALLET')		OR define('SYSTEM_TYPE_SINGLE_WALLET', 1);
defined('SYSTEM_TYPE_TRANSFER_WALLET')		OR define('SYSTEM_TYPE_TRANSFER_WALLET', 2);
/*
|--------------------------------------------------------------------------
| Cash Transfer Codes
|--------------------------------------------------------------------------
|
| These code are used when working with report.
|
*/
defined('TRANSFER_POINT_IN')			OR define('TRANSFER_POINT_IN', 1);
defined('TRANSFER_POINT_OUT')			OR define('TRANSFER_POINT_OUT', 2);
defined('TRANSFER_ADJUST_IN')			OR define('TRANSFER_ADJUST_IN', 3);
defined('TRANSFER_ADJUST_OUT')			OR define('TRANSFER_ADJUST_OUT', 4);
defined('TRANSFER_OFFLINE_DEPOSIT')		OR define('TRANSFER_OFFLINE_DEPOSIT', 5);
defined('TRANSFER_PG_DEPOSIT')			OR define('TRANSFER_PG_DEPOSIT', 6);
defined('TRANSFER_WITHDRAWAL')			OR define('TRANSFER_WITHDRAWAL', 7);
defined('TRANSFER_WITHDRAWAL_REFUND')	OR define('TRANSFER_WITHDRAWAL_REFUND', 8);
defined('TRANSFER_PROMOTION')			OR define('TRANSFER_PROMOTION', 9);
defined('TRANSFER_BONUS')				OR define('TRANSFER_BONUS', 10);
defined('TRANSFER_COMMISSION')			OR define('TRANSFER_COMMISSION', 11);
defined('TRANSFER_TRANSACTION_IN')		OR define('TRANSFER_TRANSACTION_IN', 12);
defined('TRANSFER_TRANSACTION_OUT')		OR define('TRANSFER_TRANSACTION_OUT', 13);
defined('TRANSFER_REWARD_IN')			OR define('TRANSFER_REWARD_IN', 14);
defined('TRANSFER_REWARD_OUT')			OR define('TRANSFER_REWARD_OUT', 15);
defined('TRANSFER_CREDIT_CARD_DEPOSIT')	OR define('TRANSFER_CREDIT_CARD_DEPOSIT', 16);
defined('TRANSFER_HYPERMART_DEPOSIT')	OR define('TRANSFER_HYPERMART_DEPOSIT', 17);

/*
|--------------------------------------------------------------------------
| Transaction Type
|--------------------------------------------------------------------------
|
| These code are used when working with report.
|
*/
defined('TRANSACTION_TYPE_DEPOSIT')				OR define('TRANSACTION_TYPE_DEPOSIT', 1);
defined('TRANSACTION_TYPE_WITHDRAWAL')			OR define('TRANSACTION_TYPE_WITHDRAWAL', 2);
defined('TRANSACTION_TYPE_DEPOSIT_POINT')		OR define('TRANSACTION_TYPE_DEPOSIT_POINT', 3);
defined('TRANSACTION_TYPE_WITHDRAWAL_POINT')	OR define('TRANSACTION_TYPE_WITHDRAWAL_POINT', 4);
defined('TRANSACTION_TYPE_TRANSFER')			OR define('TRANSACTION_TYPE_TRANSFER', 5);
defined('TRANSACTION_TYPE_PROMOTION')			OR define('TRANSACTION_TYPE_PROMOTION', 6);
defined('TRANSACTION_TYPE_BET')					OR define('TRANSACTION_TYPE_BET', 7);
/*
|--------------------------------------------------------------------------
| Page Codes
|--------------------------------------------------------------------------
|
| These code are used when working with page.
|
*/
defined('PAGE_HOME')				OR define('PAGE_HOME', 1);
defined('PAGE_SPORTSBOOK')			OR define('PAGE_SPORTSBOOK', 2);
defined('PAGE_ESPORTS')				OR define('PAGE_ESPORTS', 3);
defined('PAGE_LIVE_CASINO')			OR define('PAGE_LIVE_CASINO', 4);
defined('PAGE_SLOTS')				OR define('PAGE_SLOTS', 5);
defined('PAGE_FISHING')				OR define('PAGE_FISHING', 6);
defined('PAGE_ARCADE')				OR define('PAGE_ARCADE', 7);
defined('PAGE_BOARD_GAME')			OR define('PAGE_BOARD_GAME', 8);
defined('PAGE_LOTTERY')				OR define('PAGE_LOTTERY', 9);
defined('PAGE_POKER')				OR define('PAGE_POKER', 10);
defined('PAGE_PROMOTION')			OR define('PAGE_PROMOTION', 11);
defined('PAGE_ABOUT_US')			OR define('PAGE_ABOUT_US', 12);
defined('PAGE_FAQ')					OR define('PAGE_FAQ', 13);
defined('PAGE_CONTACT_US')			OR define('PAGE_CONTACT_US', 14);
defined('PAGE_TNC')					OR define('PAGE_TNC', 15);
defined('PAGE_RG')					OR define('PAGE_RG', 16);
defined('PAGE_VIP')					OR define('PAGE_VIP', 17);
defined('PAGE_MOVIE')				OR define('PAGE_MOVIE', 18);
defined('PAGE_LOGIN')				OR define('PAGE_LOGIN', 19);
defined('PAGE_REGISTER')			OR define('PAGE_REGISTER', 20);
defined('PAGE_FORGOT_PASSWORD')		OR define('PAGE_FORGOT_PASSWORD', 21);
defined('PAGE_HORSE_RACE')			OR define('PAGE_HORSE_RACE', 22);
defined('PAGE_DEPOSIT')				OR define('PAGE_DEPOSIT', 23);
defined('PAGE_WITHDRAWAL')			OR define('PAGE_WITHDRAWAL', 24);
/*
|--------------------------------------------------------------------------
| Game Type Codes
|--------------------------------------------------------------------------
|
| These code are used when working with game.
|
*/
defined('GAME_ALL')  			OR define('GAME_ALL', '0');
defined('GAME_SPORTSBOOK')  	OR define('GAME_SPORTSBOOK', 'SB');
defined('GAME_LIVE_CASINO')		OR define('GAME_LIVE_CASINO', 'LC');
defined('GAME_SLOTS')  			OR define('GAME_SLOTS', 'SL');
defined('GAME_FISHING')  		OR define('GAME_FISHING', 'FH');
defined('GAME_ESPORTS')  		OR define('GAME_ESPORTS', "ES");
defined('GAME_BOARD_GAME')  	OR define('GAME_BOARD_GAME', 'BG');
defined('GAME_LOTTERY')  		OR define('GAME_LOTTERY', 'LT');
defined('GAME_KENO')  			OR define('GAME_KENO', 'KN');
defined('GAME_VIRTUAL_SPORTS')  OR define('GAME_VIRTUAL_SPORTS', 'VS');
defined('GAME_POKER')  			OR define('GAME_POKER', 'PK');
defined('GAME_COCKFIGHTING')  	OR define('GAME_COCKFIGHTING', 'CF');
defined('GAME_OTHERS')  		OR define('GAME_OTHERS', 'OT');

/*
|--------------------------------------------------------------------------
| User Group Type Codes
|--------------------------------------------------------------------------
|
| These code are used when working with log.
|
*/
defined('USER_GROUP_USER')			OR define('USER_GROUP_USER', 1);
defined('USER_GROUP_SUB_ACCOUNT')	OR define('USER_GROUP_SUB_ACCOUNT', 2);
defined('USER_GROUP_PLAYER')		OR define('USER_GROUP_PLAYER', 3);
defined('USER_GROUP_DOMAIN')		OR define('USER_GROUP_DOMAIN', 4);
/*
|--------------------------------------------------------------------------
| Log Codes
|--------------------------------------------------------------------------
|
| These code are used when working with log.
|
*/
defined('LOG_REGISTER')					OR define('LOG_REGISTER', 1);
defined('LOG_RESET_PASSWORD')			OR define('LOG_RESET_PASSWORD', 2);
defined('LOG_LOGIN')					OR define('LOG_LOGIN', 3);
defined('LOG_LOGOUT')					OR define('LOG_LOGOUT', 4);
defined('LOG_PROFILE_UPDATE')			OR define('LOG_PROFILE_UPDATE', 5);
defined('LOG_CHANGE_PASSWORD')			OR define('LOG_CHANGE_PASSWORD', 6);
defined('LOG_DEPOSIT')					OR define('LOG_DEPOSIT', 7);
defined('LOG_WITHDRAWAL')				OR define('LOG_WITHDRAWAL', 8);
defined('LOG_WALLET_TRANSFER')			OR define('LOG_WALLET_TRANSFER', 9);
defined('LOG_PLAYER_DEPOSIT_POINT')		OR define('LOG_PLAYER_DEPOSIT_POINT', 10);
defined('LOG_PLAYER_WITHDRAW_POINT')	OR define('LOG_PLAYER_WITHDRAW_POINT', 11);
defined('LOG_USER_DEPOSIT_POINT')		OR define('LOG_USER_DEPOSIT_POINT', 12);
defined('LOG_USER_WITHDRAW_POINT')		OR define('LOG_USER_WITHDRAW_POINT', 13);
defined('LOG_PROMOTION')				OR define('LOG_PROMOTION', 14);
defined('LOG_WALLET_TRANSFER_PENDING')	OR define('LOG_WALLET_TRANSFER_PENDING', 15);
defined('LOG_BANK_PLAYER_USER_ADD')		OR define('LOG_BANK_PLAYER_USER_ADD', 16);
defined('LOG_BANK_PLAYER_USER_UPDATE')	OR define('LOG_BANK_PLAYER_USER_UPDATE', 17);
/*
|--------------------------------------------------------------------------
| Error Codes
|--------------------------------------------------------------------------
|
| These code are used when working with API.
|
*/
defined('ERROR_SUCCESS')					OR define('ERROR_SUCCESS', 0);
defined('ERROR_SYSTEM_MAINTENANCE')			OR define('ERROR_SYSTEM_MAINTENANCE', 100);
defined('ERROR_SYSTEM_BUSY')				OR define('ERROR_SYSTEM_BUSY', 101);
defined('ERROR_METHOD_INCORRECT')			OR define('ERROR_METHOD_INCORRECT', 102);
defined('ERROR_AGENT_ID_EMPTY')				OR define('ERROR_AGENT_ID_EMPTY', 103);
defined('ERROR_AGENT_ID_NOT_FOUND')			OR define('ERROR_AGENT_ID_NOT_FOUND', 104);
defined('ERROR_SIGNATURE_EMPTY')			OR define('ERROR_SIGNATURE_EMPTY', 105);
defined('ERROR_SIGNATURE_INCORRECT')		OR define('ERROR_SIGNATURE_INCORRECT', 106);
defined('ERROR_USERNAME_EMPTY')				OR define('ERROR_USERNAME_EMPTY', 107);
defined('ERROR_USERNAME_INCORRECT')			OR define('ERROR_USERNAME_INCORRECT', 108);
defined('ERROR_USERNAME_EXITS')				OR define('ERROR_USERNAME_EXITS', 109);
defined('ERROR_PASSWORD_EMPTY')				OR define('ERROR_PASSWORD_EMPTY', 110);
defined('ERROR_PASSWORD_INCORRECT')			OR define('ERROR_PASSWORD_INCORRECT', 111);
defined('ERROR_PASSCONF_NOT_MATCH')			OR define('ERROR_PASSCONF_NOT_MATCH', 112);
defined('ERROR_NICKNAME_EMPTY')				OR define('ERROR_NICKNAME_EMPTY', 113);
defined('ERROR_NICKNAME_INCORRECT')			OR define('ERROR_NICKNAME_INCORRECT', 114);
defined('ERROR_MOBILE_INCORRECT')			OR define('ERROR_MOBILE_INCORRECT', 115);
defined('ERROR_EMAIL_INCORRECT')			OR define('ERROR_EMAIL_INCORRECT', 116);
defined('ERROR_CREATE_USER_FAILED')			OR define('ERROR_CREATE_USER_FAILED', 117);
defined('ERROR_AMOUNT_EMPTY')				OR define('ERROR_AMOUNT_EMPTY', 118);
defined('ERROR_AMOUNT_INCORRECT')			OR define('ERROR_AMOUNT_INCORRECT', 119);
defined('ERROR_USERNAME_NOT_FOUND')			OR define('ERROR_USERNAME_NOT_FOUND', 120);
defined('ERROR_POINT_INSUFFICIENT')			OR define('ERROR_POINT_INSUFFICIENT', 121);
defined('ERROR_TRANSFER_FAILED')			OR define('ERROR_TRANSFER_FAILED', 122);
defined('ERROR_INVALID_LOGIN')				OR define('ERROR_INVALID_LOGIN', 123);
defined('ERROR_ACCOUNT_SUSPENDED')			OR define('ERROR_ACCOUNT_SUSPENDED', 124);
defined('ERROR_API_ACCESS_DENIED')			OR define('ERROR_API_ACCESS_DENIED', 125);
defined('ERROR_METHOD_EMPTY')				OR define('ERROR_METHOD_EMPTY', 126);
defined('ERROR_TOKEN_EMPTY')				OR define('ERROR_TOKEN_EMPTY', 127);
defined('ERROR_INVALID_TOKEN')				OR define('ERROR_INVALID_TOKEN', 128);
defined('ERROR_PARAMETER_INCORRECT')		OR define('ERROR_PARAMETER_INCORRECT', 129);
defined('ERROR_GAME_MAINTENANCE')			OR define('ERROR_GAME_MAINTENANCE', 130);
defined('ERROR_PROVIDER_CODE_INCORRECT')	OR define('ERROR_PROVIDER_CODE_INCORRECT', 131);
defined('ERROR_INVALID_CURRENT_PASSWORD')	OR define('ERROR_INVALID_CURRENT_PASSWORD', 132);
defined('ERROR_UPDATE_PROFILE_FAILED')		OR define('ERROR_UPDATE_PROFILE_FAILED', 133);
defined('ERROR_CHANGE_PASSWORD_FAILED')		OR define('ERROR_CHANGE_PASSWORD_FAILED', 134);
defined('ERROR_BANK_ID_EMPTY')				OR define('ERROR_BANK_ID_EMPTY', 135);
defined('ERROR_BANK_ID_NOT_FOUND')			OR define('ERROR_BANK_ID_NOT_FOUND', 136);
defined('ERROR_BANK_ACC_NAME_EMPTY')		OR define('ERROR_BANK_ACC_NAME_EMPTY', 137);
defined('ERROR_BANK_ACC_NO_EMPTY')			OR define('ERROR_BANK_ACC_NO_EMPTY', 138);
defined('ERROR_BANK_ACC_NO_INCORRECT')		OR define('ERROR_BANK_ACC_NO_INCORRECT', 139);
defined('ERROR_BINDING_BANK_FAILED')		OR define('ERROR_BINDING_BANK_FAILED', 140);
defined('ERROR_MIN_WITHDAWAL')				OR define('ERROR_MIN_WITHDAWAL', 141);
defined('ERROR_MAX_WITHDAWAL')				OR define('ERROR_MAX_WITHDAWAL', 142);
defined('ERROR_WITHDRAWAL_FAILED')			OR define('ERROR_WITHDRAWAL_FAILED', 143);
defined('ERROR_INVALID_MONTH_RANGE')		OR define('ERROR_INVALID_MONTH_RANGE', 144);
defined('ERROR_REFERRAL_CODE_INCORRECT')	OR define('ERROR_REFERRAL_CODE_INCORRECT', 145);
defined('ERROR_WALLET_LOCK')				OR define('ERROR_WALLET_LOCK', 146);
defined('ERROR_MOBILE_EMPTY')				OR define('ERROR_MOBILE_EMPTY', 147);
defined('ERROR_MOBILE_INCORRECT')			OR define('ERROR_MOBILE_INCORRECT', 148);
defined('ERROR_SYSTEM_ERROR')				OR define('ERROR_SYSTEM_ERROR', 200);

/*
|--------------------------------------------------------------------------
| System setting
|--------------------------------------------------------------------------
|
| These setting are used when working with system.
|
*/
defined('BANK_RECEIPT_PATH')		OR define('BANK_RECEIPT_PATH', './uploads/receipt/');
defined('BANK_RECEIPT_SOURCE_PATH')	OR define('BANK_RECEIPT_SOURCE_PATH', './../../uploads/receipt/');
defined('BANK_RECEIPT_FILE_SIZE')	OR define('BANK_RECEIPT_FILE_SIZE', 10240);
defined('SYSTEM_TIMEZONE')			OR define('SYSTEM_TIMEZONE', 8);
defined('SYSTEM_LANGUAGES')			OR define('SYSTEM_LANGUAGES', json_encode(array(LANG_EN, LANG_ZH_HK, LANG_ZH_CN)));
defined('SYSTEM_API_AGENT_ID')		OR define('SYSTEM_API_AGENT_ID', 'm9mbet');
defined('SYSTEM_API_SECRET_KEY')	OR define('SYSTEM_API_SECRET_KEY', 'M9sWhBVau8MWcREkmkAKfrKqh1FPKdZd');

/*
|--------------------------------------------------------------------------
| Payment Gateway setting
|--------------------------------------------------------------------------
|
| These setting are used when working with system.
|
*/
defined('PG_PE_MERCHANT_CODE')		OR define('PG_PE_MERCHANT_CODE', 'Suncity668');
defined('PG_PE_SECRET_KEY')			OR define('PG_PE_SECRET_KEY', 'cb489535-f892-4b9f-ba3f-a75002a0f163');
defined('PG_PE_API_URL')			OR define('PG_PE_API_URL', 'https://api-demo.mxpay.asia/WebForm/Deposit.aspx');

#--------------------------------------------------------------------------
# GAME HUB URL
#--------------------------------------------------------------------------
if(ENVIRONMENT == 'production') {
	defined('DOMAIN') OR define('DOMAIN', 'https://m9mbet.com/');
	defined('HUB_URL') OR define('HUB_URL', 'https://m9m.bctapi.com/api');
	defined('HUB_ROOT') OR define('HUB_ROOT', 'https://m9m.bctapi.com/');
}
else if(ENVIRONMENT == 'testing') {
	defined('DOMAIN') OR define('DOMAIN', 'https://iwk88.com/dev/m9mbet/');
	defined('HUB_URL') OR define('HUB_URL', 'https://api.m9mbet.com/api');
	defined('HUB_ROOT') OR define('HUB_ROOT', 'https://api.m9mbet.com/');
}
else {
	defined('DOMAIN') OR define('DOMAIN', 'http://localhost/dev/m9mbet');
	defined('HUB_URL') OR define('HUB_URL', DOMAIN.'/gameapi/api');
	defined('HUB_ROOT') OR define('HUB_ROOT', DOMAIN.'/gameapi/');
}
defined('UPLOAD_PATH') OR define('UPLOAD_PATH', DOMAIN.'/uploads/');
defined('BLOG_PATH') OR define('BLOG_PATH', 'https://m9mbet/uploads/blog/');
