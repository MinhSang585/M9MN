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
defined('EXIT_ON_LOCK')        OR define('EXIT_ON_LOCK', 2); // generic error
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
defined('STATUS_PENDING')		OR define('STATUS_PENDING', 0);
defined('STATUS_COMPLETE')		OR define('STATUS_COMPLETE', 1);
defined('STATUS_CANCEL')		OR define('STATUS_CANCEL', 2);
defined('STATUS_NO')			OR define('STATUS_NO', 0);
defined('STATUS_YES')			OR define('STATUS_YES', 1);
defined('STATUS_SUSPEND')		OR define('STATUS_SUSPEND', 2);
defined('STATUS_WIN')		    OR define('STATUS_WIN', 3);
defined('STATUS_LOSS')		    OR define('STATUS_LOSS', 4);
defined('STATUS_TIE')		    OR define('STATUS_TIE', 5);
defined('STATUS_UNKNOWN')		OR define('STATUS_UNKNOWN', 1);
defined('STATUS_ON_PENDING')		OR define('STATUS_ON_PENDING', 3);
defined('STATUS_SATTLEMENT')		OR define('STATUS_SATTLEMENT', 1);
defined('STATUS_ENTITLEMENT')		OR define('STATUS_ENTITLEMENT', 3);
defined('STATUS_VOID')				OR define('STATUS_VOID', 4);
defined('STATUS_ACCOMPLISH')		OR define('STATUS_ACCOMPLISH', 5);

/*
|--------------------------------------------------------------------------
| Sync Code
|--------------------------------------------------------------------------
|
| These code are used when working with whole system.
|
*/
defined('SYNC_DEFAULT')			OR define('SYNC_DEFAULT', 0);
defined('SYNC_BACKUP')			OR define('SYNC_BACKUP', 1);
defined('SYNC_MANUAL')			OR define('SYNC_MANUAL', 2);
defined('SYNC_BACKUP_SECOND')	OR define('SYNC_BACKUP_SECOND', 2);

/*
|--------------------------------------------------------------------------
| Time Code
|--------------------------------------------------------------------------
|
| These code are used when working with whole system.
|
*/
defined('TIME_PAYOUT')			OR define('TIME_PAYOUT', 0);
defined('TIME_BET')				OR define('TIME_BET', 1);
defined('TIME_GAME')			OR define('TIME_GAME', 2);
defined('TIME_REPORT')			OR define('TIME_REPORT', 3);


/*
|--------------------------------------------------------------------------
| UPDATE TYPE
|--------------------------------------------------------------------------
|
| These code are used when working with whole system.
|
*/
defined('UPDATE_TYPE_DEFAULT')		OR define('UPDATE_TYPE_DEFAULT', 0);
defined('UPDATE_TYPE_PAYOUT_TIME')	OR define('UPDATE_TYPE_PAYOUT_TIME', 1);
defined('UPDATE_TYPE_CMD')			OR define('UPDATE_TYPE_CMD', 2);
defined('UPDATE_TYPE_REPORT_TIME')	OR define('UPDATE_TYPE_REPORT_TIME', 3);
defined('UPDATE_TYPE_GAME_TIME')	OR define('UPDATE_TYPE_GAME_TIME', 4);
defined('UPDATE_TYPE_BET_TIME')		OR define('UPDATE_TYPE_BET_TIME', 5);

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

defined('SYNC_TYPE_ALL')			OR define('SYNC_TYPE_ALL', 0);
defined('SYNC_TYPE_INSERT')			OR define('SYNC_TYPE_INSERT', 1);
defined('SYNC_TYPE_UPDATE')			OR define('SYNC_TYPE_UPDATE', 2);
defined('SYNC_TYPE_MODIFIED')		OR define('SYNC_TYPE_MODIFIED', 3);
/*
|--------------------------------------------------------------------------
| Game Type Codes
|--------------------------------------------------------------------------
|
| These code are used when working with game.
|
*/
defined('GAME_ALL') 		 	OR define('GAME_ALL', '0');
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
| Game Round Type Codes
|--------------------------------------------------------------------------
|
| These code are used when working with game.
|
*/
defined('GAME_ROUND_TYPE_GAME_ROUND')  		OR define('GAME_ROUND_TYPE_GAME_ROUND', 0);
defined('GAME_ROUND_TYPE_FREE_SPIN')		OR define('GAME_ROUND_TYPE_FREE_SPIN', 1);
defined('GAME_ROUND_TYPE_JACKPOT')  		OR define('GAME_ROUND_TYPE_JACKPOT', 2);
defined('GAME_ROUND_TYPE_TIP')  			OR define('GAME_ROUND_TYPE_TIP', 3);
defined('GAME_ROUND_TYPE_GAME_ACTIVITY')  	OR define('GAME_ROUND_TYPE_GAME_ACTIVITY', 4);
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
defined('ERROR_AGENT_ID_EMPTY')				OR define('ERROR_AGENT_ID_EMPTY', 102);
defined('ERROR_AGENT_ID_NOT_FOUND')			OR define('ERROR_AGENT_ID_NOT_FOUND', 103);
defined('ERROR_API_ACCESS_DENIED')			OR define('ERROR_API_ACCESS_DENIED', 104);
defined('ERROR_METHOD_EMPTY')				OR define('ERROR_METHOD_EMPTY', 105);
defined('ERROR_METHOD_INCORRECT')			OR define('ERROR_METHOD_INCORRECT', 106);
defined('ERROR_SIGNATURE_EMPTY')			OR define('ERROR_SIGNATURE_EMPTY', 107);
defined('ERROR_SIGNATURE_INCORRECT')		OR define('ERROR_SIGNATURE_INCORRECT', 108);
defined('ERROR_PROVIDER_CODE_EMPTY')		OR define('ERROR_PROVIDER_CODE_EMPTY', 109);
defined('ERROR_PROVIDER_CODE_INCORRECT')	OR define('ERROR_PROVIDER_CODE_INCORRECT', 110);
defined('ERROR_USERNAME_EMPTY')				OR define('ERROR_USERNAME_EMPTY', 111);
defined('ERROR_USERNAME_INCORRECT')			OR define('ERROR_USERNAME_INCORRECT', 112);
defined('ERROR_USERNAME_EXITS')				OR define('ERROR_USERNAME_EXITS', 113);
defined('ERROR_USERNAME_NOT_FOUND')			OR define('ERROR_USERNAME_NOT_FOUND', 114);
defined('ERROR_PASSWORD_EMPTY')				OR define('ERROR_PASSWORD_EMPTY', 115);
defined('ERROR_AMOUNT_EMPTY')				OR define('ERROR_AMOUNT_EMPTY', 116);
defined('ERROR_AMOUNT_INCORRECT')			OR define('ERROR_AMOUNT_INCORRECT', 117);
defined('ERROR_AMOUNT_INSUFFICIENT')		OR define('ERROR_AMOUNT_INSUFFICIENT', 118);
defined('ERROR_PARAMETER_INCORRECT')		OR define('ERROR_PARAMETER_INCORRECT', 119);
defined('ERROR_ORDER_ID_EMPTY')				OR define('ERROR_ORDER_ID_EMPTY', 120);
defined('ERROR_PLAYER_ID_EMPTY')			OR define('ERROR_PLAYER_ID_EMPTY', 121);
defined('ERROR_SYSTEM_ERROR')				OR define('ERROR_SYSTEM_ERROR', 200);
defined('ERROR_OVERTIME')					OR define('ERROR_OVERTIME', 201);
defined('ERROR_GAME_MAINTENANCE')			OR define('ERROR_GAME_MAINTENANCE', 222);

/*
|--------------------------------------------------------------------------
| System setting
|--------------------------------------------------------------------------
|
| These setting are used when working with system.
|
*/
defined('OFFICE_USERNAME')			OR define('OFFICE_USERNAME', json_encode(array("m9mbet")));
defined('CLEAR_SESSION_INTERVAL')	OR define('CLEAR_SESSION_INTERVAL', 7200);
defined('SYSTEM_API_URL')		    OR define('SYSTEM_API_URL', 'https://m9mbet.com/gameapi/api');
defined('SYSTEM_API_AGENT_ID')		OR define('SYSTEM_API_AGENT_ID', 'm9mbet');
defined('SYSTEM_API_SECRET_KEY')	OR define('SYSTEM_API_SECRET_KEY', 'M9sWhBVau8MWcREkmkAKfrKqh1FPKdZd');

defined('WIN_LOSS_ADJUST_SECOND')		OR define('WIN_LOSS_ADJUST_SECOND', 0);


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

defined('DEPOSIT_OFFLINE_BANKING')	OR define('DEPOSIT_OFFLINE_BANKING', 1);
defined('DEPOSIT_ONLINE_BANKING')	OR define('DEPOSIT_ONLINE_BANKING', 2);
defined('DEPOSIT_CREDIT_CARD')	OR define('DEPOSIT_CREDIT_CARD', 3);
defined('DEPOSIT_HYPERMART')	OR define('DEPOSIT_HYPERMART', 4);

defined('WITHDRAWAL_OFFLINE_BANKING')	OR define('WITHDRAWAL_OFFLINE_BANKING', 1);
defined('WITHDRAWAL_ONLINE_BANKING')	OR define('WITHDRAWAL_ONLINE_BANKING', 2);


defined('GAME_CODE_TYPE_LIVE_CASINO_BACCARAT') 							OR define('GAME_CODE_TYPE_LIVE_CASINO_BACCARAT', 'BAC-C');
defined('GAME_CODE_TYPE_LIVE_CASINO_BID_BACCARAT') 						OR define('GAME_CODE_TYPE_LIVE_CASINO_BID_BACCARAT', 'BAC-B');
defined('GAME_CODE_TYPE_LIVE_CASINO_INSURANCE_BACCARAT') 				OR define('GAME_CODE_TYPE_LIVE_CASINO_INSURANCE_BACCARAT', 'BAC-I');
defined('GAME_CODE_TYPE_LIVE_CASINO_NO_COMMISSION_BACCARAT') 			OR define('GAME_CODE_TYPE_LIVE_CASINO_NO_COMMISSION_BACCARAT', 'BAC-NC');
defined('GAME_CODE_TYPE_LIVE_CASINO_VIP_BACCARAT') 						OR define('GAME_CODE_TYPE_LIVE_CASINO_VIP_BACCARAT', 'BAC-V');
defined('GAME_CODE_TYPE_LIVE_CASINO_SPEED_BACCARAT') 					OR define('GAME_CODE_TYPE_LIVE_CASINO_SPEED_BACCARAT', 'BAC-S');
defined('GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_BACCARAT') 				OR define('GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_BACCARAT', 'BAC-BC');
defined('GAME_CODE_TYPE_LIVE_CASINO_LIVE_BACCARAT') 					OR define('GAME_CODE_TYPE_LIVE_CASINO_LIVE_BACCARAT', 'BAC-L');
defined('GAME_CODE_TYPE_LIVE_CASINO_DRAGON_TIGER') 						OR define('GAME_CODE_TYPE_LIVE_CASINO_DRAGON_TIGER', 'DT-C');
defined('GAME_CODE_TYPE_LIVE_CASINO_NEW_DRAGON_TIGER') 					OR define('GAME_CODE_TYPE_LIVE_CASINO_NEW_DRAGON_TIGER', 'DT-N');
defined('GAME_CODE_TYPE_LIVE_CASINO_LIVE_DRAGON_TIGER') 				OR define('GAME_CODE_TYPE_LIVE_CASINO_LIVE_DRAGON_TIGER', 'DT-L');
defined('GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_DRAGON_TIGER') 			OR define('GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_DRAGON_TIGER', 'DT-BC');
defined('GAME_CODE_TYPE_LIVE_CASINO_BULL_BULL') 						OR define('GAME_CODE_TYPE_LIVE_CASINO_BULL_BULL', 'OX-C');
defined('GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_BULL_BULL') 				OR define('GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_BULL_BULL', 'OX-BC');
defined('GAME_CODE_TYPE_LIVE_CASINO_ZHA_JIN_HUA') 						OR define('GAME_CODE_TYPE_LIVE_CASINO_ZHA_JIN_HUA', 'ZJH-C');
defined('GAME_CODE_TYPE_LIVE_CASINO_LUCKY_ZHA_JIN_HUA') 				OR define('GAME_CODE_TYPE_LIVE_CASINO_LUCKY_ZHA_JIN_HUA', 'ZJH-LCK');
defined('GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_ZHA_JIN_HUA') 			OR define('GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_ZHA_JIN_HUA', 'ZJH-BC');
defined('GAME_CODE_TYPE_LIVE_CASINO_THREE_FACE_POKER') 					OR define('GAME_CODE_TYPE_LIVE_CASINO_THREE_FACE_POKER', 'TFP-C');
defined('GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_THREE_FACE_POKER') 		OR define('GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_THREE_FACE_POKER', 'TFP-BC');
defined('GAME_CODE_TYPE_LIVE_CASINO_ROULETTE') 							OR define('GAME_CODE_TYPE_LIVE_CASINO_ROULETTE', 'RO-C');
defined('GAME_CODE_TYPE_LIVE_CASINO_SICBO') 							OR define('GAME_CODE_TYPE_LIVE_CASINO_SICBO', 'DI-C');
defined('GAME_CODE_TYPE_LIVE_CASINO_FAN_TAN') 							OR define('GAME_CODE_TYPE_LIVE_CASINO_FAN_TAN', 'FT-C');
defined('GAME_CODE_TYPE_LIVE_CASINO_SEDIE') 							OR define('GAME_CODE_TYPE_LIVE_CASINO_SEDIE', 'SEDIE-C');
defined('GAME_CODE_TYPE_LIVE_CASINO_POK_DENG') 							OR define('GAME_CODE_TYPE_LIVE_CASINO_POK_DENG', 'PD-C');
defined('GAME_CODE_TYPE_LIVE_CASINO_ROCK_PAPER_SCISSORS') 				OR define('GAME_CODE_TYPE_LIVE_CASINO_ROCK_PAPER_SCISSORS', 'RPS-C');
defined('GAME_CODE_TYPE_LIVE_CASINO_ANDAR_BAHAR') 						OR define('GAME_CODE_TYPE_LIVE_CASINO_ANDAR_BAHAR', 'ADBH-C');
defined('GAME_CODE_TYPE_LIVE_CASINO_FISH_PRAWN_CRAB') 					OR define('GAME_CODE_TYPE_LIVE_CASINO_FISH_PRAWN_CRAB', 'FPC-C');
defined('GAME_CODE_TYPE_LIVE_CASINO_MONEYWHEEL') 						OR define('GAME_CODE_TYPE_LIVE_CASINO_MONEYWHEEL', 'MW-C');
defined('GAME_CODE_TYPE_LIVE_CASINO_ULTIMATE_TEXAS_HOLDEM') 			OR define('GAME_CODE_TYPE_LIVE_CASINO_ULTIMATE_TEXAS_HOLDEM', 'UTH-C');

defined('GAME_CODE_TYPE_MEMBER_SEND_GIFT') 								OR define('GAME_CODE_TYPE_MEMBER_SEND_GIFT', 'TIPS-MSG');
defined('GAME_CODE_TYPE_MEMBER_GET_GIFT') 								OR define('GAME_CODE_TYPE_MEMBER_GET_GIFT', 'TIPS-MGG');
defined('GAME_CODE_TYPE_ANCHOR_SEND_TIPS') 								OR define('GAME_CODE_TYPE_ANCHOR_SEND_TIPS', 'TIPS-AST');
defined('GAME_CODE_TYPE_COMPANY_SEND_GIFT') 							OR define('GAME_CODE_TYPE_COMPANY_SEND_GIFT', 'TIPS-CSG');
defined('GAME_CODE_TYPE_BO_BING') 										OR define('GAME_CODE_TYPE_BO_BING', 'TIPS-BB');
defined('GAME_CODE_TYPE_CROUPIER_SEND_TIPS') 							OR define('GAME_CODE_TYPE_CROUPIER_SEND_TIPS', 'TIPS-CST');

defined('GAME_CODE_TYPE_SPORTBOOK_BASEBALL') 							OR define('GAME_CODE_TYPE_SPORTBOOK_BASEBALL', 'BASEBALL-OT');
defined('GAME_CODE_TYPE_SPORTBOOK_BASEBALL_MLB') 						OR define('GAME_CODE_TYPE_SPORTBOOK_BASEBALL_MLB', 'BASEBALL-MLB');
defined('GAME_CODE_TYPE_SPORTBOOK_BASEBALL_NPB') 						OR define('GAME_CODE_TYPE_SPORTBOOK_BASEBALL_NPB', 'BASEBALL-NPB');
defined('GAME_CODE_TYPE_SPORTBOOK_BASEBALL_CPBL') 						OR define('GAME_CODE_TYPE_SPORTBOOK_BASEBALL_CPBL', 'BASEBALL-CPBL');
defined('GAME_CODE_TYPE_SPORTBOOK_BASEBALL_KBO') 						OR define('GAME_CODE_TYPE_SPORTBOOK_BASEBALL_KBO', 'BASEBALL-KBO');
defined('GAME_CODE_TYPE_SPORTBOOK_SOCCER') 								OR define('GAME_CODE_TYPE_SPORTBOOK_SOCCER', 'SOCCER-OT');
defined('GAME_CODE_TYPE_SPORTBOOK_SOCCER_TOP') 							OR define('GAME_CODE_TYPE_SPORTBOOK_SOCCER_TOP', 'SOCCER-TOP');
defined('GAME_CODE_TYPE_SPORTBOOK_SOCCER_UEFA') 						OR define('GAME_CODE_TYPE_SPORTBOOK_SOCCER_UEFA', 'SOCCER-UEFA');
defined('GAME_CODE_TYPE_SPORTBOOK_SOCCER_FIFA') 						OR define('GAME_CODE_TYPE_SPORTBOOK_SOCCER_FIFA', 'SOCCER-FIFA');
defined('GAME_CODE_TYPE_SPORTBOOK_BASKETBALL') 							OR define('GAME_CODE_TYPE_SPORTBOOK_BASKETBALL', 'BASKETBALL-OT');
defined('GAME_CODE_TYPE_SPORTBOOK_BASKETBALL_NBA') 						OR define('GAME_CODE_TYPE_SPORTBOOK_BASKETBALL_NBA', 'BASKETBALL-NBA');
defined('GAME_CODE_TYPE_SPORTBOOK_ICE_HOCKEY') 							OR define('GAME_CODE_TYPE_SPORTBOOK_ICE_HOCKEY', 'ICE_HOCKEY-OT');
defined('GAME_CODE_TYPE_SPORTBOOK_ICE_HOCKEY_NHL') 						OR define('GAME_CODE_TYPE_SPORTBOOK_ICE_HOCKEY_NHL', 'ICE_HOCKEY-NHL');
defined('GAME_CODE_TYPE_SPORTBOOK_LOTTERY') 							OR define('GAME_CODE_TYPE_SPORTBOOK_LOTTERY', 'LOTTERY-OT');
defined('GAME_CODE_TYPE_SPORTBOOK_FOOTBALL') 							OR define('GAME_CODE_TYPE_SPORTBOOK_FOOTBALL', 'FOOTBALL-OT');
defined('GAME_CODE_TYPE_SPORTBOOK_INDEX') 								OR define('GAME_CODE_TYPE_SPORTBOOK_INDEX', 'INDEX-OT');
defined('GAME_CODE_TYPE_SPORTBOOK_GREYHOUND_RACE') 						OR define('GAME_CODE_TYPE_SPORTBOOK_GREYHOUND_RACE', 'GREYHOUND_RACE-OT');
defined('GAME_CODE_TYPE_SPORTBOOK_ESPORT') 								OR define('GAME_CODE_TYPE_SPORTBOOK_ESPORT', 'ESPORT-OT');
defined('GAME_CODE_TYPE_SPORTBOOK_TENNIS') 								OR define('GAME_CODE_TYPE_SPORTBOOK_TENNIS', 'TENNIS-OT');
defined('GAME_CODE_TYPE_SPORTBOOK_OTHER') 								OR define('GAME_CODE_TYPE_SPORTBOOK_OTHER', 'OTHER-OT');
defined('GAME_CODE_TYPE_SPORTBOOK_PINGPONG') 							OR define('GAME_CODE_TYPE_SPORTBOOK_PINGPONG', 'PINGPONG-OT');
defined('GAME_CODE_TYPE_SPORTBOOK_BADMINTON') 							OR define('GAME_CODE_TYPE_SPORTBOOK_BADMINTON', 'BADMINTON-OT');
defined('GAME_CODE_TYPE_SPORTBOOK_VOLLEYBALL') 							OR define('GAME_CODE_TYPE_SPORTBOOK_VOLLEYBALL', 'VOLLEYBALL-OT');
defined('GAME_CODE_TYPE_SPORTBOOK_SNOOKER') 							OR define('GAME_CODE_TYPE_SPORTBOOK_SNOOKER', 'SNOOKER-OT');
defined('GAME_CODE_TYPE_UNKNOWN') 										OR define('GAME_CODE_TYPE_UNKNOWN', 'UNKNOWN');