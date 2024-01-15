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
defined('ERROR_TRANSACTION_CODE_EMPTY')		OR define('ERROR_TRANSACTION_ID_EMPTY', 122);
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
defined('OFFICE_USERNAME')			OR define('OFFICE_USERNAME', json_encode(array("777xc")));
defined('CLEAR_SESSION_INTERVAL')	OR define('CLEAR_SESSION_INTERVAL', 7200);
defined('SYSTEM_API_AGENT_ID')		OR define('SYSTEM_API_AGENT_ID', '777xc');
defined('SYSTEM_API_SECRET_KEY')	OR define('SYSTEM_API_SECRET_KEY', 'iIBplERjt42lGt9tGVt4l1BX0OcXznCG');

defined('WIN_LOSS_ADJUST_SECOND')		OR define('WIN_LOSS_ADJUST_SECOND', 0);
defined('PLAYER_SITE_LANGUAGES')	OR define('PLAYER_SITE_LANGUAGES', json_encode(array(LANG_ZH_HK)));

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
| Message Read Type
|--------------------------------------------------------------------------
|
| These code are used when working with Message Read Type.
|
*/
defined('MESSAGE_UNREAD')			OR define('MESSAGE_UNREAD', 1);
defined('MESSAGE_READ')				OR define('MESSAGE_READ', 2);

/*
|--------------------------------------------------------------------------
| Message Genre
|--------------------------------------------------------------------------
|
| These code are used when working with Message Genre.
|
*/
defined('MESSAGE_GENRE_ALL')			OR define('MESSAGE_GENRE_ALL', 1);
defined('MESSAGE_GENRE_USER_LEVEL')		OR define('MESSAGE_GENRE_USER_LEVEL', 2);
defined('MESSAGE_GENRE_BANK_CHANNEL')	OR define('MESSAGE_GENRE_BANK_CHANNEL', 3);
defined('MESSAGE_GENRE_INDIVIDUAL')		OR define('MESSAGE_GENRE_INDIVIDUAL', 4);
defined('MESSAGE_GENRE_USER_ALL')		OR define('MESSAGE_GENRE_USER_ALL', 5);

defined('SYSTEM_MESSAGE_PLATFORM_EN')		OR define('SYSTEM_MESSAGE_PLATFORM_EN', 'bet98');
defined('SYSTEM_MESSAGE_PLATFORM_CHS') 		OR define('SYSTEM_MESSAGE_PLATFORM_CHS', 'bet98');
defined('SYSTEM_MESSAGE_PLATFORM_CHT')  	OR define('SYSTEM_MESSAGE_PLATFORM_CHT', 'bet98');
defined('SYSTEM_MESSAGE_PLATFORM_ID')  		OR define('SYSTEM_MESSAGE_PLATFORM_ID', 'bet98');
defined('SYSTEM_MESSAGE_PLATFORM_TH')  		OR define('SYSTEM_MESSAGE_PLATFORM_TH', 'bet98');
defined('SYSTEM_MESSAGE_PLATFORM_VI') 		OR define('SYSTEM_MESSAGE_PLATFORM_VI', 'bet98');
defined('SYSTEM_MESSAGE_PLATFORM_KM')   	OR define('SYSTEM_MESSAGE_PLATFORM_KM', 'bet98');
defined('SYSTEM_MESSAGE_PLATFORM_MY')  		OR define('SYSTEM_MESSAGE_PLATFORM_MY', 'bet98');
defined('SYSTEM_MESSAGE_PLATFORM_MS')  		OR define('SYSTEM_MESSAGE_PLATFORM_MS', 'bet98');
defined('SYSTEM_MESSAGE_PLATFORM_JA')  		OR define('SYSTEM_MESSAGE_PLATFORM_JA', 'bet98');
defined('SYSTEM_MESSAGE_PLATFORM_KO')  		OR define('SYSTEM_MESSAGE_PLATFORM_KO', 'bet98');
defined('SYSTEM_MESSAGE_PLATFORM_BN')  		OR define('SYSTEM_MESSAGE_PLATFORM_BN', 'bet98');
defined('SYSTEM_MESSAGE_PLATFORM_HI')  		OR define('SYSTEM_MESSAGE_PLATFORM_HI', 'bet98');
defined('SYSTEM_MESSAGE_PLATFORM_LO')  		OR define('SYSTEM_MESSAGE_PLATFORM_LO', 'bet98');
defined('SYSTEM_MESSAGE_PLATFORM_TR')  		OR define('SYSTEM_MESSAGE_PLATFORM_TR', 'bet98');
defined('SYSTEM_MESSAGE_PLATFORM_NEW_REGISTRATION')  			OR define('SYSTEM_MESSAGE_PLATFORM_NEW_REGISTRATION', 1);
defined('SYSTEM_MESSAGE_PLATFORM_SUCCESS_DEPOSIT')  			OR define('SYSTEM_MESSAGE_PLATFORM_SUCCESS_DEPOSIT', 2);
defined('SYSTEM_MESSAGE_PLATFORM_SUCCESS_WITHDRAWAL')  			OR define('SYSTEM_MESSAGE_PLATFORM_SUCCESS_WITHDRAWAL', 3);
defined('SYSTEM_MESSAGE_PLATFORM_FAILED_WITHDRAWAL')  			OR define('SYSTEM_MESSAGE_PLATFORM_FAILED_WITHDRAWAL', 4);
defined('SYSTEM_MESSAGE_PLATFORM_SUCCESS_PROMOTION')  			OR define('SYSTEM_MESSAGE_PLATFORM_SUCCESS_PROMOTION', 5);
defined('SYSTEM_MESSAGE_PLATFORM_SUCCESS_VERIFY_PLAYER_BANK')  	OR define('SYSTEM_MESSAGE_PLATFORM_SUCCESS_VERIFY_PLAYER_BANK', 6);
defined('SYSTEM_MESSAGE_PLATFORM_FAILED_VERIFY_PLAYER_BANK')  	OR define('SYSTEM_MESSAGE_PLATFORM_FAILED_VERIFY_PLAYER_BANK', 7);
defined('SYSTEM_MESSAGE_PLATFORM_PROMOTION_REBATE')  			OR define('SYSTEM_MESSAGE_PLATFORM_PROMOTION_REBATE', 8);
defined('SYSTEM_MESSAGE_PLATFORM_PROMOTION_LEVEL')  			OR define('SYSTEM_MESSAGE_PLATFORM_PROMOTION_LEVEL', 9);
defined('SYSTEM_MESSAGE_PLATFORM_VIP_ACCOUNT_OPEN')  			OR define('SYSTEM_MESSAGE_PLATFORM_VIP_ACCOUNT_OPEN', 10);
defined('SYSTEM_MESSAGE_PLATFORM_VIP_ACCOUNT_CLOSE')  			OR define('SYSTEM_MESSAGE_PLATFORM_VIP_ACCOUNT_CLOSE', 11);
defined('SYSTEM_MESSAGE_PLATFORM_MAINTENANCE')  				OR define('SYSTEM_MESSAGE_PLATFORM_MAINTENANCE', 12);
defined('SYSTEM_MESSAGE_PLATFORM_PROMOTION')  					OR define('SYSTEM_MESSAGE_PLATFORM_PROMOTION', 13);
defined('SYSTEM_MESSAGE_PLATFORM_ADDITIONAL_DOCUMENT')  		OR define('SYSTEM_MESSAGE_PLATFORM_ADDITIONAL_DOCUMENT', 14);
defined('SYSTEM_MESSAGE_PLATFORM_WM_ACCOUNT')			  		OR define('SYSTEM_MESSAGE_PLATFORM_WM_ACCOUNT', 15);
defined('SYSTEM_MESSAGE_PLATFORM_SUCCESS_VERIFY_PLAYER_CREDIT_CARD')  	OR define('SYSTEM_MESSAGE_PLATFORM_SUCCESS_VERIFY_PLAYER_CREDIT_CARD', 16);
defined('SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME')			  	OR define('SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME', "####USERNAME####");
defined('SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM')			  	OR define('SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM', "####PLATFORM####");
defined('SYSTEM_MESSAGE_PLATFORM_VALUE_AMOUNT')			  		OR define('SYSTEM_MESSAGE_PLATFORM_VALUE_AMOUNT', "####AMOUNT####");
defined('SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_NAME')		 	OR define('SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_NAME', "####PROMOTION_NAME####");
defined('SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_MULTIPLY')		OR define('SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_MULTIPLY', "####PROMOTION_MULTIPLY####");
defined('SYSTEM_MESSAGE_PLATFORM_VALUE_REWARD')			  		OR define('SYSTEM_MESSAGE_PLATFORM_VALUE_REWARD', "####REWARD####");
defined('SYSTEM_MESSAGE_PLATFORM_VALUE_REMARK')			  		OR define('SYSTEM_MESSAGE_PLATFORM_VALUE_REMARK', "####REMARK####");
defined('SYSTEM_MESSAGE_PLATFORM_VALUE_LEVEL')			  		OR define('SYSTEM_MESSAGE_PLATFORM_VALUE_LEVEL', "####LEVEL####");
defined('SYSTEM_MESSAGE_PLATFORM_VALUE_VIP_BANK_ACCOUNT')		OR define('SYSTEM_MESSAGE_PLATFORM_VALUE_VIP_BANK_ACCOUNT', "####VIP_BANK_ACCOUNT####");
defined('SYSTEM_MESSAGE_PLATFORM_VALUE_VIP_BANK_CODE')			OR define('SYSTEM_MESSAGE_PLATFORM_VALUE_VIP_BANK_CODE', "####VIP_BANK_CODE####");
defined('SYSTEM_MESSAGE_PLATFORM_VALUE_WM_ACCOUNT_USERNAME') 	OR define('SYSTEM_MESSAGE_PLATFORM_VALUE_WM_ACCOUNT_USERNAME', "####WM_ACCOUNT_USERNAME####");
defined('SYSTEM_MESSAGE_PLATFORM_VALUE_WM_ACCOUNT_PASSWORD')	OR define('SYSTEM_MESSAGE_PLATFORM_VALUE_WM_ACCOUNT_PASSWORD', "####WM_ACCOUNT_PASSWORD####");

defined('TELEGRAM_STATUS') OR define('TELEGRAM_STATUS', 0);
defined('TELEGRAM_MONEY_FLOW') OR define('TELEGRAM_MONEY_FLOW', 'MFTELE');
defined('TELEGRAM_TOKEN_MONEY_FLOW') OR define('TELEGRAM_TOKEN_MONEY_FLOW', '5689120451:AAEybrI3sREj842iJbMd92fuZ3UawyqUBdo');
defined('TELEGRAM_CHAT_ID_MONEY_FLOW') OR define('TELEGRAM_CHAT_ID_MONEY_FLOW', '-1001188738034');
defined('TELEGRAM_REGISTER') OR define('TELEGRAM_REGISTER', 'RETELE');
defined('TELEGRAM_TOKEN_REGISTER') OR define('TELEGRAM_TOKEN_REGISTER', '5689120451:AAEybrI3sREj842iJbMd92fuZ3UawyqUBdo');
defined('TELEGRAM_CHAT_ID_REGISTER') OR define('TELEGRAM_CHAT_ID_REGISTER', '-1001865080621');
defined('TELEGRAM_REGISTER_FUNCTION') OR define('TELEGRAM_REGISTER_FUNCTION', 500);
defined('TELEGRAM_FEEDBACK') OR define('TELEGRAM_FEEDBACK', 'FEEDTELE');
defined('TELEGRAM_TOKEN_FEEDBACK') OR define('TELEGRAM_TOKEN_FEEDBACK', '5689120451:AAEybrI3sREj842iJbMd92fuZ3UawyqUBdo');
defined('TELEGRAM_CHAT_ID_FEEDBACK') OR define('TELEGRAM_CHAT_ID_FEEDBACK', '-1001557602134');
defined('TELEGRAM_LOGS') OR define('TELEGRAM_LOGS', 'LOGSTELE');
defined('TELEGRAM_TOKEN_LOGS') OR define('TELEGRAM_TOKEN_LOGS', '5689120451:AAEybrI3sREj842iJbMd92fuZ3UawyqUBdo');
defined('TELEGRAM_CHAT_ID_LOGS') 	OR define('TELEGRAM_CHAT_ID_LOGS', '-1001713458454');
defined('TELEGRAM_RISK') OR define('TELEGRAM_RISK', 'RISKTELE');
defined('TELEGRAM_TOKEN_RISK') OR define('TELEGRAM_TOKEN_RISK', '5689120451:AAEybrI3sREj842iJbMd92fuZ3UawyqUBdo');
defined('TELEGRAM_CHAT_ID_RISK') 	OR define('TELEGRAM_CHAT_ID_RISK', '-1001840004084');

defined('TELEGRAM_FEEDBACK_TYPE_CUSTOMER_SUPPORT') OR define('TELEGRAM_FEEDBACK_TYPE_CUSTOMER_SUPPORT', 1);
defined('TELEGRAM_FEEDBACK_TYPE_DEPOSIT_WITHDRAWAL') OR define('TELEGRAM_FEEDBACK_TYPE_DEPOSIT_WITHDRAWAL', 2);
defined('TELEGRAM_FEEDBACK_TYPE_ACCOUNT') OR define('TELEGRAM_FEEDBACK_TYPE_ACCOUNT', 3);
defined('TELEGRAM_FEEDBACK_TYPE_ERROR_MESSAGE') OR define('TELEGRAM_FEEDBACK_TYPE_ERROR_MESSAGE', 4);
defined('TELEGRAM_FEEDBACK_TYPE_OTHERS') OR define('TELEGRAM_FEEDBACK_TYPE_OTHERS', 5);

defined('TELEGRAM_LOGS_TYPE_CREATE_USER_ACCOUNT') 			OR define('TELEGRAM_LOGS_TYPE_CREATE_USER_ACCOUNT', 1);
defined('TELEGRAM_LOGS_TYPE_CREATE_SUB_ACCOUNT') 			OR define('TELEGRAM_LOGS_TYPE_CREATE_SUB_ACCOUNT', 2);
defined('TELEGRAM_LOGS_TYPE_UPDATE_USER_CHARACTER') 		OR define('TELEGRAM_LOGS_TYPE_UPDATE_USER_CHARACTER', 3);
defined('TELEGRAM_LOGS_TYPE_UPDATE_SUB_ACCOUNT_CHARACTER') 	OR define('TELEGRAM_LOGS_TYPE_UPDATE_SUB_ACCOUNT_CHARACTER', 4);
defined('TELEGRAM_LOGS_TYPE_UPDATE_CHARACTER_PERMISSION') 	OR define('TELEGRAM_LOGS_TYPE_UPDATE_CHARACTER_PERMISSION', 5);
defined('TELEGRAM_LOGS_TYPE_PLAYER_LIST_EXPORT') 			OR define('TELEGRAM_LOGS_TYPE_PLAYER_LIST_EXPORT', 6);
defined('TELEGRAM_LOGS_TYPE_PLAYER_AGENT_LIST_EXPORT') 		OR define('TELEGRAM_LOGS_TYPE_PLAYER_AGENT_LIST_EXPORT', 7);