<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Array debug
 *
 * @access	public
 * @param	string
 * @return	string
 */	
function ad($data)
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}
#DECIMAL FUNCTION
function val_decimal($val=null,$float=null)  {	
	return bcdiv($val,1,$float);
}
#OUTPUT JSON 
function json_output($json=array()) 
{	
	$obj =& get_instance();
	$obj->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();
	free_array($json);
}
#EMPTY ARRAY 
function free_array($arr=array()) 
{		
	unset($arr);			
}
// ------------------------------------------------------------------------
/**
 * Get Country
 *
 * @access	public
 * @param	string
 * @return	string
 */	
function get_country_name($country_code = NULL) 
{
	$list = array(
		'AF' => 'country_af',
		'AX' => 'country_ax',
		'AL' => 'country_al',
		'DZ' => 'country_dz',
		'AS' => 'country_as',
		'AD' => 'country_ad',
		'AO' => 'country_ao',
		'AI' => 'country_ai',
		'AQ' => 'country_aq',
		'AG' => 'country_ag',
		'AR' => 'country_ar',
		'AM' => 'country_am',
		'AW' => 'country_aw',
		'AU' => 'country_au',
		'AT' => 'country_at',
		'AZ' => 'country_az',
		'BS' => 'country_bs',
		'BH' => 'country_bh',
		'BD' => 'country_bd',
		'BB' => 'country_bb',
		'BY' => 'country_by',
		'BE' => 'country_be',
		'BZ' => 'country_bz',
		'BJ' => 'country_bj',
		'BM' => 'country_bm',
		'BT' => 'country_bt',
		'BO' => 'country_bo',
		'BA' => 'country_ba',
		'BW' => 'country_bw',
		'BV' => 'country_bv',
		'BR' => 'country_br',
		'IO' => 'country_io',
		'BN' => 'country_bn',
		'BG' => 'country_bg',
		'BF' => 'country_bf',
		'BI' => 'country_bi',
		'KH' => 'country_kh',
		'CM' => 'country_cm',
		'CA' => 'country_ca',
		'CV' => 'country_cv',
		'KY' => 'country_ky',
		'CF' => 'country_cf',
		'TD' => 'country_td',
		'CL' => 'country_cl',
		'CN' => 'country_cn',
		'CX' => 'country_cx',
		'CC' => 'country_cc',
		'CO' => 'country_co',
		'KM' => 'country_km',
		'CG' => 'country_cg',
		'CD' => 'country_cd',
		'CK' => 'country_ck',
		'CR' => 'country_cr',
		'CI' => 'country_ci',
		'HR' => 'country_hr',
		'CU' => 'country_cu',
		'CY' => 'country_cy',
		'CZ' => 'country_cz',
		'DK' => 'country_dk',
		'DJ' => 'country_dj',
		'DM' => 'country_dm',
		'DO' => 'country_do',
		'EC' => 'country_ec',
		'EG' => 'country_eg',
		'SV' => 'country_sv',
		'GQ' => 'country_gq',
		'ER' => 'country_er',
		'EE' => 'country_ee',
		'ET' => 'country_et',
		'FK' => 'country_fk',
		'FO' => 'country_fo',
		'FJ' => 'country_fj',
		'FI' => 'country_fi',
		'FR' => 'country_fr',
		'GF' => 'country_gf',
		'PF' => 'country_pf',
		'TF' => 'country_tf',
		'GA' => 'country_ga',
		'GM' => 'country_gm',
		'GE' => 'country_ge',
		'DE' => 'country_de',
		'GH' => 'country_gh',
		'GI' => 'country_gi',
		'GR' => 'country_gr',
		'GL' => 'country_gl',
		'GD' => 'country_gd',
		'GP' => 'country_gp',
		'GU' => 'country_gu',
		'GT' => 'country_gt',
		'GG' => 'country_gg',
		'GN' => 'country_gn',
		'GW' => 'country_gw',
		'GY' => 'country_gy',
		'HT' => 'country_ht',
		'HM' => 'country_hm',
		'HN' => 'country_hn',
		'HK' => 'country_hk',
		'HU' => 'country_hu',
		'IS' => 'country_is',
		'IN' => 'country_in',
		'ID' => 'country_id',
		'IR' => 'country_ir',
		'IQ' => 'country_iq',
		'IE' => 'country_ie',
		'IM' => 'country_im',
		'IL' => 'country_il',
		'IT' => 'country_it',
		'JM' => 'country_jm',
		'JP' => 'country_jp',
		'JE' => 'country_je',
		'JO' => 'country_jo',
		'KZ' => 'country_kz',
		'KE' => 'country_ke',
		'KI' => 'country_ki',
		'KW' => 'country_kw',
		'KG' => 'country_kg',
		'LA' => 'country_la',
		'LV' => 'country_lv',
		'LB' => 'country_lb',
		'LS' => 'country_ls',
		'LR' => 'country_lr',
		'LY' => 'country_ly',
		'LI' => 'country_li',
		'LT' => 'country_lt',
		'LU' => 'country_lu',
		'MO' => 'country_mo',
		'MK' => 'country_mk',
		'MG' => 'country_mg',
		'MW' => 'country_mw',
		'MY' => 'country_my',
		'MV' => 'country_mv',
		'ML' => 'country_ml',
		'MT' => 'country_mt',
		'MH' => 'country_mh',
		'MQ' => 'country_mq',
		'MR' => 'country_mr',
		'MU' => 'country_mu',
		'YT' => 'country_yt',
		'MX' => 'country_mx',
		'FM' => 'country_fm',
		'MD' => 'country_md',
		'MC' => 'country_mc',
		'MN' => 'country_mn',
		'ME' => 'country_me',
		'MS' => 'country_ms',
		'MA' => 'country_ma',
		'MZ' => 'country_mz',
		'MM' => 'country_mm',
		'NA' => 'country_na',
		'NR' => 'country_nr',
		'NP' => 'country_np',
		'NL' => 'country_nl',
		'AN' => 'country_an',
		'NC' => 'country_nc',
		'NZ' => 'country_nz',
		'NI' => 'country_ni',
		'NE' => 'country_ne',
		'NG' => 'country_ng',
		'NU' => 'country_nu',
		'NF' => 'country_nf',
		'MP' => 'country_mp',
		'KP' => 'country_kp',
		'NO' => 'country_no',
		'OM' => 'country_om',
		'PK' => 'country_pk',
		'PW' => 'country_pw',
		'PS' => 'country_ps',
		'PA' => 'country_pa',
		'PG' => 'country_pg',
		'PY' => 'country_py',
		'PE' => 'country_pe',
		'PH' => 'country_ph',
		'PN' => 'country_pn',
		'PL' => 'country_pl',
		'PT' => 'country_pt',
		'PR' => 'country_pr',
		'QA' => 'country_qa',
		'RE' => 'country_re',
		'RO' => 'country_ro',
		'RU' => 'country_ru',
		'RW' => 'country_rw',
		'SH' => 'country_sh',
		'KN' => 'country_kn',
		'LC' => 'country_lc',
		'PM' => 'country_pm',
		'VC' => 'country_vc',
		'WS' => 'country_ws',
		'SM' => 'country_sm',
		'ST' => 'country_st',
		'SA' => 'country_sa',
		'SN' => 'country_sn',
		'RS' => 'country_rs',
		'CS' => 'country_cs',
		'SC' => 'country_sc',
		'SL' => 'country_sl',
		'SG' => 'country_sg',
		'SK' => 'country_sk',
		'SI' => 'country_si',
		'SB' => 'country_sb',
		'SO' => 'country_so',
		'ZA' => 'country_za',
		'GS' => 'country_gs',
		'KR' => 'country_kr',
		'ES' => 'country_es',
		'LK' => 'country_lk',
		'SD' => 'country_sd',
		'SR' => 'country_sr',
		'SJ' => 'country_sj',
		'SZ' => 'country_sz',
		'SE' => 'country_se',
		'CH' => 'country_ch',
		'SY' => 'country_sy',
		'TW' => 'country_tw',
		'TJ' => 'country_tj',
		'TZ' => 'country_tz',
		'TH' => 'country_th',
		'TL' => 'country_tl',
		'TG' => 'country_tg',
		'TK' => 'country_tk',
		'TO' => 'country_to',
		'TT' => 'country_tt',
		'TN' => 'country_tn',
		'TR' => 'country_tr',
		'TM' => 'country_tm',
		'TC' => 'country_tc',
		'TV' => 'country_tv',
		'UG' => 'country_ug',
		'UA' => 'country_ua',
		'AE' => 'country_ae',
		'GB' => 'country_gb',
		'US' => 'country_us',
		'UM' => 'country_um',
		'UY' => 'country_uy',
		'UZ' => 'country_uz',
		'VU' => 'country_vu',
		'VA' => 'country_va',
		'VE' => 'country_ve',
		'VN' => 'country_vn',
		'VG' => 'country_vg',
		'VI' => 'country_vi',
		'WF' => 'country_wf',
		'EH' => 'country_eh',
		'YE' => 'country_ye',
		'ZM' => 'country_zm',
		'ZW' => 'country_zw'
	);
	if( ! empty($country_code)) 
	{
		return $list[$country_code];
	}	
	else 
	{
		return $list;	
	}	
}
// ------------------------------------------------------------------------
/**
 * Get Gender
 *
 * @access	public
 * @param	numeric
 * @return	string
 */	
function get_gender($type = NULL) 
{
	$list = array(
		GENDER_MALE => 'gender_male',
		GENDER_FEMALE => 'gender_female'
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}	
}
// ------------------------------------------------------------------------
/**
 * Get Bank Method
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_message_type($type = NULL) 
{
	$list = array(
		MESSAGE_SYSTEM				=> 'message_system',
		MESSAGE_CUSTOMER_SERVICE	=> 'message_customer_service',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
/**
 * Get Bank Method
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_system_type($type = NULL) 
{
	$list = array(
		SYSTEM_TYPE_SINGLE_WALLET		=> 'system_type_single_wallet',
		SYSTEM_TYPE_TRANSFER_WALLET		=> 'system_type_transfer_wallet',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
/**
 * Get Promotion Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_promotion_type($type = NULL) 
{
	$list = array(
		PROMOTION_TYPE_STRICT_BASED			=> 'promotion_type_strict_based',
		PROMOTION_TYPE_PLAYER_BASED			=> 'promotion_type_player_based',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Get Group Type
 *
 * @access	public
 * @param	numeric
 * @return	string
 */	
function get_group_type($type = NULL) 
{
	$list = array(
		GROUP_BANK => 'group_bank',
		GROUP_PLAYER => 'group_player'
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}	
}
/**
 * Message Read Status
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_message_read_status($type = NULL) 
{
	$list = array(
		MESSAGE_UNREAD			=> 'label_unread',
		MESSAGE_READ			=> 'label_isread',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Get Bank Method
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_message_genre($type = NULL) 
{
	$list = array(
		MESSAGE_GENRE_ALL			=> 'message_genre_all',
		//MESSAGE_GENRE_USER_LEVEL	=> 'message_genre_user_level',
		//MESSAGE_GENRE_BANK_CHANNEL	=> 'message_genre_bank_channel',
		MESSAGE_GENRE_INDIVIDUAL	=> 'message_genre_individual',
		MESSAGE_GENRE_USER_ALL	=> 'message_genre_user_all',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Get Promotion Method
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_promotion_genre($type = NULL) 
{
	$list = array(
		MESSAGE_GENRE_ALL			=> 'message_genre_all',
		//MESSAGE_GENRE_USER_LEVEL	=> 'message_genre_user_level',
		//MESSAGE_GENRE_BANK_CHANNEL	=> 'message_genre_bank_channel',
		MESSAGE_GENRE_INDIVIDUAL	=> 'message_genre_individual',
		MESSAGE_GENRE_USER_ALL	=> 'message_genre_user_all',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Get Player Type
 *
 * @access	public
 * @param	numeric
 * @return	string
 */	
function get_player_type($type = NULL) 
{
	$list = array(
		PLAYER_TYPE_CASH_MARKET 	=> 'player_type_cash_market',
		#PLAYER_TYPE_CREDIT_MARKET 	=> 'player_type_credit_market',
		#PLAYER_TYPE_MG_MARKET 		=> 'player_type_mg_market',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}	
}
function get_fingerprint_status($type = NULL) 
{
	$list = array(
		FINGERPRINT_STATUS_INACTIVE => 'fingerprint_status_inactive',
		FINGERPRINT_STATUS_ACTIVE_NOT_FORCE => 'fingerprint_status_active_not_force',
		FINGERPRINT_STATUS_ACTIVE_FORCE => 'fingerprint_status_active_force',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}	
}
// ------------------------------------------------------------------------
/**
 * Get Day
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_day($day = NULL) 
{
	$list = array(
		1 => 'day_monday',
		2 => 'day_tuesday',
		3 => 'day_wednesday',
		4 => 'day_thursday',
		5 => 'day_friday',
		6 => 'day_saturday',
		7 => 'day_sunday'
	);
	if( ! empty($day))
	{
		return $list[$day];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Get Month
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_month($month = NULL) 
{
	$list = array(
		1 => 'month_jan',
		2 => 'month_feb',
		3 => 'month_mar',
		4 => 'month_apr',
		5 => 'month_may',
		6 => 'month_jun',
		7 => 'month_jul',
		8 => 'month_aug',
		9 => 'month_sep',
		10 => 'month_oct',
		11 => 'month_nov',
		12 => 'month_dec'
	);
	if( ! empty($month))
	{
		return $list[$month];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Select language code
 *
 * @access	public
 * @return	string
 */	
function get_language_code($type = NULL)
{
	$obj =& get_instance();
	$code = 'en';
	$selection = $obj->session->userdata('lang');
	switch($selection)
	{
		case 'chinese_simplified': $code = (($type == 'iso') ? 'zh-Hans' : 'chs'); break;
		case 'chinese_traditional': $code = (($type == 'iso') ? 'zh-Hant' : 'cht'); break;
		case 'indonesian': $code = 'id'; break;
		case 'thai': $code = 'th'; break;
		case 'vietnamese': $code = 'vi'; break;
		case 'khmer': $code = 'km'; break;
		case 'myanmar': $code = 'my'; break;
		case 'malay': $code = 'ms'; break;
		case 'japanese': $code = 'ja'; break;
		case 'korean': $code = 'ko'; break;
		case 'bengali': $code = 'bn'; break;
		case 'hindi': $code = 'hi'; break;
		case 'lao': $code = 'lo'; break;
		case 'turkish': $code = 'tr'; break;
		default: $code = 'en'; break;
	}
	return $code;
}
// ------------------------------------------------------------------------
/**
 * Select language name
 *
 * @access	public
 * @return	string
 */	
function get_language_name($code = NULL)
{
	$lang = '';
	switch($code)
	{
		case 'chs': $lang = 'chinese_simplified'; break;
		case 'cht': $lang = 'chinese_traditional'; break;
		case 'id': $lang = 'indonesian'; break;
		case 'th': $lang = 'thai'; break;
		case 'vi': $lang = 'vietnamese'; break;
		case 'km': $lang = 'khmer'; break;
		case 'my': $lang = 'myanmar'; break;
		case 'ms': $lang = 'malay'; break;
		case 'ja': $lang = 'japanese'; break;
		case 'ko': $lang = 'korean'; break;
		case 'bn': $lang = 'bengali'; break;
		case 'hi': $lang = 'hindi'; break;
		case 'lo': $lang = 'lao'; break;
		case 'tr': $lang = 'turkish'; break;
		default: $lang = 'english'; break;
	}
	return $lang;
}
function get_flag_name($code = NULL)
{
	$lang = '';
	switch($code)
	{
		case 'chs': $lang = 'cn'; break;
		case 'cht': $lang = 'tw'; break;
		case 'id': $lang = 'id'; break;
		case 'th': $lang = 'th'; break;
		case 'vi': $lang = 'vn'; break;
		case 'km': $lang = 'kh'; break;
		case 'my': $lang = 'mm'; break;
		case 'ms': $lang = 'my'; break;
		case 'ja': $lang = 'jp'; break;
		case 'ko': $lang = 'kr'; break;
		case 'bn': $lang = 'bd'; break;
		case 'hi': $lang = 'in'; break;
		case 'lo': $lang = 'la'; break;
		case 'tr': $lang = 'tr'; break;
		default: $lang = 'us'; break;
	}
	return $lang;
}
// ------------------------------------------------------------------------
/**
 * Select language name
 *
 * @access	public
 * @return	string
 */	
function get_site_language_name($id = NULL)
{
	$lang = '';
	switch($id)
	{
		case LANG_ZH_CN: $lang = 'lang_zh_cn'; break;
		case LANG_ZH_HK: $lang = 'lang_zh_hk'; break;
		case LANG_ZH_TW: $lang = 'lang_zh_tw'; break;
		case LANG_ID: $lang = 'lang_id'; break;
		case LANG_TH: $lang = 'lang_th'; break;
		case LANG_VI: $lang = 'lang_vi'; break;
		case LANG_KM: $lang = 'lang_km'; break;
		case LANG_MY: $lang = 'lang_my'; break;
		case LANG_MS: $lang = 'lang_ms'; break;
		case LANG_JA: $lang = 'lang_ja'; break;
		case LANG_KO: $lang = 'lang_ko'; break;
		case LANG_BN: $lang = 'lang_bn'; break;
		case LANG_HI: $lang = 'lang_hi'; break;
		case LANG_LO: $lang = 'lang_lo'; break;
		case LANG_TR: $lang = 'lang_tr'; break;
		default: $lang = 'lang_en'; break;
	}
	return $lang;
}
/**
 * Select platform name
 *
 * @access	public
 * @return	string
 */	
function get_platform_language_name($id = NULL)
{
	$lang = '';
	switch($id)
	{
		case LANG_ZH_CN: $lang = SYSTEM_MESSAGE_PLATFORM_CHS; break;
		case LANG_ZH_HK:
		case LANG_ZH_TW: $lang = SYSTEM_MESSAGE_PLATFORM_CHT; break;
		case LANG_ID: $lang = SYSTEM_MESSAGE_PLATFORM_ID; break;
		case LANG_TH: $lang = SYSTEM_MESSAGE_PLATFORM_TH; break;
		case LANG_VI: $lang = SYSTEM_MESSAGE_PLATFORM_VI; break;
		case LANG_KM: $lang = SYSTEM_MESSAGE_PLATFORM_KM; break;
		case LANG_MY: $lang = SYSTEM_MESSAGE_PLATFORM_MY; break;
		case LANG_MS: $lang = SYSTEM_MESSAGE_PLATFORM_MS; break;
		case LANG_JA: $lang = SYSTEM_MESSAGE_PLATFORM_JA; break;
		case LANG_KO: $lang = SYSTEM_MESSAGE_PLATFORM_KO; break;
		case LANG_BN: $lang = SYSTEM_MESSAGE_PLATFORM_BN; break;
		case LANG_HI: $lang = SYSTEM_MESSAGE_PLATFORM_HI; break;
		case LANG_LO: $lang = SYSTEM_MESSAGE_PLATFORM_LO; break;
		case LANG_TR: $lang = SYSTEM_MESSAGE_PLATFORM_TR; break;
		default: $lang = SYSTEM_MESSAGE_PLATFORM_EN; break;
	}
	return $lang;
}
// ------------------------------------------------------------------------
/**
 * Get Timezone
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_timezone($timezone = NULL) 
{
	$list = array(
		'-11' => 'UTC -11:00',
		'-10' => 'UTC -10:00',
		'-9' => 'UTC -09:00',
		'-8' => 'UTC -08:00',
		'-7' => 'UTC -07:00',
		'-6' => 'UTC -06:00',
		'-5' => 'UTC -05:00',
		'-4' => 'UTC -04:00',
		'-3' => 'UTC -03:00',
		'-2' => 'UTC -02:00',
		'-1' => 'UTC -01:00',
		'0' => 'UTC +00:00',
		'1' => 'UTC +01:00',
		'2' => 'UTC +02:00',
		'3' => 'UTC +03:00',
		'4' => 'UTC +04:00',
		'5' => 'UTC +05:00',
		'6' => 'UTC +06:00',
		'7' => 'UTC +07:00',
		'8' => 'UTC +08:00',
		'9' => 'UTC +09:00',
		'10' => 'UTC +10:00',
		'11' => 'UTC +11:00',
		'12' => 'UTC +12:00',
		'13' => 'UTC +13:00',
		'14' => 'UTC +14:00',
	);
	if( ! empty($timezone))
	{
		return $list[$timezone];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
function get_platform_full_permission() 
{
	$full_permission_list = array(
		PERMISSION_MISCELLANEOUS_UPDATE,
		PERMISSION_CONTACT_UPDATE,
		PERMISSION_CONTACT_VIEW,
		PERMISSION_SEO_UPDATE,
		PERMISSION_SEO_VIEW,
		PERMISSION_GAME_UPDATE,
		PERMISSION_GAME_VIEW,
		PERMISSION_BANNER_ADD,
		PERMISSION_BANNER_UPDATE,
		PERMISSION_BANNER_DELETE,
		PERMISSION_BANNER_VIEW,
		PERMISSION_ANNOUNCEMENT_ADD,
		PERMISSION_ANNOUNCEMENT_UPDATE,
		PERMISSION_ANNOUNCEMENT_DELETE,
		PERMISSION_ANNOUNCEMENT_VIEW,
		PERMISSION_GROUP_ADD,
		PERMISSION_GROUP_UPDATE,
		PERMISSION_GROUP_DELETE,
		PERMISSION_GROUP_VIEW,
		PERMISSION_BANK_ADD,
		PERMISSION_BANK_UPDATE,
		PERMISSION_BANK_DELETE,
		PERMISSION_BANK_VIEW,
		PERMISSION_BANK_ACCOUNT_ADD,
		PERMISSION_BANK_ACCOUNT_UPDATE,
		PERMISSION_BANK_ACCOUNT_DELETE,
		PERMISSION_BANK_ACCOUNT_VIEW,
		PERMISSION_PROMOTION_ADD,
		PERMISSION_PROMOTION_UPDATE,
		PERMISSION_PROMOTION_DELETE,
		PERMISSION_PROMOTION_VIEW,
		PERMISSION_SUB_ACCOUNT_ADD,
		PERMISSION_SUB_ACCOUNT_UPDATE,
		PERMISSION_SUB_ACCOUNT_VIEW,
		PERMISSION_USER_ADD,
		PERMISSION_USER_UPDATE,
		PERMISSION_USER_VIEW,
		PERMISSION_PLAYER_ADD,
		PERMISSION_PLAYER_UPDATE,
		PERMISSION_PLAYER_VIEW,		
		PERMISSION_CHANGE_PASSWORD,
		PERMISSION_DEPOSIT_POINT_TO_DOWNLINE,
		PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE,
		PERMISSION_VIEW_PLAYER_CONTACT,
		PERMISSION_VIEW_PLAYER_WALLET,
		PERMISSION_PLAYER_WALLET_TRANSFER,
		PERMISSION_PLAYER_POINT_ADJUSTMENT,
		PERMISSION_KICK_PLAYER,
		PERMISSION_DEPOSIT_UPDATE,
		PERMISSION_DEPOSIT_VIEW,
		PERMISSION_WITHDRAWAL_UPDATE,
		PERMISSION_WITHDRAWAL_VIEW,
		PERMISSION_PLAYER_PROMOTION_ADD,
		PERMISSION_PLAYER_PROMOTION_UPDATE,
		PERMISSION_PLAYER_PROMOTION_VIEW,
		PERMISSION_PLAYER_BONUS_ADD,
		PERMISSION_PLAYER_BONUS_UPDATE,
		PERMISSION_PLAYER_BONUS_VIEW,
		PERMISSION_WIN_LOSS_REPORT,
		PERMISSION_TRANSACTION_REPORT,
		PERMISSION_POINT_TRANSACTION_REPORT,
		PERMISSION_CASH_TRANSACTION_REPORT,
		PERMISSION_WALLET_TRANSACTION_REPORT,
		PERMISSION_LOGIN_REPORT,
		PERMISSION_BANK_CHANNEL_ADD,
		PERMISSION_BANK_CHANNEL_UPDATE,
		PERMISSION_BANK_CHANNEL_DELETE,
		PERMISSION_BANK_CHANNEL_VIEW,
		PERMISSION_SYSTEM_MESSAGE_ADD,
		PERMISSION_SYSTEM_MESSAGE_UPDATE,
		PERMISSION_SYSTEM_MESSAGE_DELETE,
		PERMISSION_SYSTEM_MESSAGE_VIEW,
		PERMISSION_SYSTEM_MESSAGE_USER_ADD,
		PERMISSION_SYSTEM_MESSAGE_USER_UPDATE,
		PERMISSION_SYSTEM_MESSAGE_USER_DELETE,
		PERMISSION_SYSTEM_MESSAGE_USER_VIEW,
		PERMISSION_BANK_PLAYER_ADD,
		PERMISSION_BANK_PLAYER_UPDATE,
		PERMISSION_BANK_PLAYER_DELETE,
		PERMISSION_BANK_PLAYER_VIEW,
		PERMISSION_BANK_PLAYER_USER_ADD,
		PERMISSION_BANK_PLAYER_USER_UPDATE,
		PERMISSION_BANK_PLAYER_USER_DELETE,
		PERMISSION_BANK_PLAYER_USER_VIEW,
		PERMISSION_LEVEL_ADD,
		PERMISSION_LEVEL_UPDATE,
		PERMISSION_LEVEL_DELETE,
		PERMISSION_LEVEL_VIEW,
		PERMISSION_DEPOSIT_ONLINE_VIEW,
		PERMISSION_DEPOSIT_OFFLINE_VIEW,
		PERMISSION_DEPOSIT_PENDING_VIEW,
		PERMISSION_DEPOSIT_HOLDING_VIEW,
		PERMISSION_DEPOSIT_REVIEWING_VIEW,
		PERMISSION_DEPOSIT_PENDING_DOC_VIEW,
		PERMISSION_DEPOSIT_APPROVE_VIEW,
		PERMISSION_DEPOSIT_CANCEL_VIEW,
		PERMISSION_DEPOSIT_PENDING_ACTION,
		PERMISSION_DEPOSIT_HOLDING_ACTION,
		PERMISSION_DEPOSIT_REVIEWING_ACTION,
		PERMISSION_DEPOSIT_PENDING_DOC_ACTION,
		PERMISSION_DEPOSIT_APPROVE_ACTION,
		PERMISSION_DEPOSIT_CANCEL_ACTION,
		PERMISSION_WITHDRAW_PENDING_VIEW,
		PERMISSION_WITHDRAW_HOLDING_VIEW,
		PERMISSION_WITHDRAW_REVIEWING_VIEW,
		PERMISSION_WITHDRAW_PENDING_DOC_VIEW,
		PERMISSION_WITHDRAW_APPROVE_VIEW,
		PERMISSION_WITHDRAW_CANCEL_VIEW,
		PERMISSION_WITHDRAW_PENDING_ACTION,
		PERMISSION_WITHDRAW_HOLDING_ACTION,
		PERMISSION_WITHDRAW_REVIEWING_ACTION,
		PERMISSION_WITHDRAW_PENDING_DOC_ACTION,
		PERMISSION_WITHDRAW_APPROVE_ACTION,
		PERMISSION_WITHDRAW_CANCEL_ACTION,
		PERMISSION_DEPOSIT_ADD,
		PERMISSION_WITHDRAWAL_ADD,
		PERMISSION_AVATAR_ADD,
		PERMISSION_AVATAR_UPDATE,
		PERMISSION_AVATAR_DELETE,
		PERMISSION_AVATAR_VIEW,
		PERMISSION_FINGERPRINT_VIEW,
		PERMISSION_BONUS_ADD,
		PERMISSION_BONUS_UPDATE,
		PERMISSION_BONUS_VIEW,
		PERMISSION_BONUS_DELETE,
		PERMISSION_MATCH_ADD,
		PERMISSION_MATCH_UPDATE,
		PERMISSION_MATCH_VIEW,
		PERMISSION_MATCH_DELETE,
		PERMISSION_LEVEL_EXECUTE_ADD,
		PERMISSION_LEVEL_EXECUTE_UPDATE,
		PERMISSION_LEVEL_EXECUTE_DELETE,
		PERMISSION_LEVEL_EXECUTE_VIEW,
		PERMISSION_LEVEL_LOG_VIEW,
		PERMISSION_LEVEL_LOG_UPDATE,
		PERMISSION_REWARD_TRANSACTION_REPORT,
		PERMISSION_REWARD_VIEW,
		PERMISSION_REWARD_UPDATE,
		PERMISSION_REWARD_DEDUCT,
		PERMISSION_VERIFY_CODE_REPORT,
		PERMISSION_PLAYER_DAILY_REPORT,
		PERMISSION_REPORT_EXPORT_EXCEL,
		PERMISSION_PLAYER_RISK_REPORT,
		PERMISSION_PAYMENT_GATEWAY_VIEW,
		PERMISSION_PAYMENT_GATEWAY_UPDATE,
		PERMISSION_ADMIN_LOG_VIEW,
		PERMISSION_ADMIN_PLAYER_LOG_VIEW,
		PERMISSION_SUB_ACCOUNT_LOG_VIEW,
		PERMISSION_SUB_ACCOUNT_PLAYER_LOG_VIEW,
		PERMISSION_GAME_MAINTENANCE_VIEW,
		PERMISSION_GAME_MAINTENANCE_ADD,
		PERMISSION_GAME_MAINTENANCE_UPDATE,
		PERMISSION_POSSESS_ADJUSTMENT,
		PERMISSION_WALLET_TRANSACTION_PENDING_VIEW,
		PERMISSION_WALLET_TRANSACTION_PENDING_UPDATE,
		PERMISSION_PLAYER_PROMOTION_BET_DETAIL,
		PERMISSION_PLAYER_PROMOTION_ON_RUNING,
		PERMISSION_DEPOSIT_OFFLINE_NOTICE,
		PERMISSION_DEPOSIT_ONLINE_NOTICE,
		PERMISSION_DEPOSIT_CREDIT_CARD_NOTICE,
		PERMISSION_DEPOSIT_HYPERMARKET_NOTICE,
		PERMISSION_WITHDRAWAL_OFFLINE_NOTICE,
		PERMISSION_RISK_MANAGEMENT,
		PERMISSION_FINGERPRINT_MANAGEMENT,
		PERMISSION_LEVEL_MANAGEMENT,
		PERMISSION_DEPOSIT_OFFLINE_ANNOUNCEMENT,
		PERMISSION_DEPOSIT_ONLINE_ANNOUNCEMENT,
		PERMISSION_DEPOSIT_CREDIT_CARD_ANNOUNCEMENT,
		PERMISSION_DEPOSIT_HYPERMARKET_ANNOUNCEMENT,
		PERMISSION_WITHDRAWAL_OFFLINE_ANNOUNCEMENT,
		PERMISSION_CURRENCIES_ADD,
		PERMISSION_CURRENCIES_UPDATE,
		PERMISSION_CURRENCIES_VIEW,
		PERMISSION_CURRENCIES_DELETE,
		PERMISSION_DEPOSIT_APPROVE_ADD,
		PERMISSION_WITHDRAWAL_APPROVE_ADD,
		PERMISSION_SYSTEM_EMAIL,
		PERMISSION_DEPOSIT_RECEIPT,
		PERMISSION_ADJUST_PLAYER_TURNOVER,
		PERMISSION_VIEW_PLAYER_TURNOVER,
		PERMISSION_VIEW_LUCKY_4D_ADD,
		PERMISSION_VIEW_LUCKY_4D_UPDATE,
		PERMISSION_VIEW_LUCKY_4D_VIEW,
		PERMISSION_VIEW_LUCKY_4D_DELETE,
		PERMISSION_VIEW_LUCKY_4D_TIME_ADD,
		PERMISSION_VIEW_LUCKY_4D_TIME_UPDATE,
		PERMISSION_VIEW_LUCKY_4D_TIME_VIEW,
		PERMISSION_VIEW_LUCKY_4D_TIME_DELETE,
		PERMISSION_VIEW_SCOREBOARD_ADD,
		PERMISSION_VIEW_SCOREBOARD_UPDATE,
		PERMISSION_VIEW_SCOREBOARD_VIEW,
		PERMISSION_VIEW_SCOREBOARD_DELETE,
		PERMISSION_PROMOTION_TURNOBER_REPORT,
		PERMISSION_BLACKLIST_ADD,
		PERMISSION_BLACKLIST_UPDATE,
		PERMISSION_BLACKLIST_VIEW,
		PERMISSION_BLACKLIST_DELETE,
		PERMISSION_BLACKLIST_REPORT,
		PERMISSION_PLAYER_WALLET_LOCK,
		PERMISSION_PLAYER_REMARK_VIEW,
		PERMISSION_PLAYER_REMARK_UPDATE,
		PERMISSION_AFFILIATE_SUMMARY_VIEW,
		PERMISSION_AFFILIATE_SUMMARY_RECORD_VIEW,
		PERMISSION_AFFILIATE_BONUS_VIEW,
		PERMISSION_AFFILIATE_BONUS_UPDATE,
		PERMISSION_MISCELLANEOUS_REBATE_UPDATE,
		PERMISSION_AFFILIATE_SETTING_UPDATE,
		PERMISSION_USER_ADD_PLAYER,
		PERMISSION_AGENT_SUMMARY_VIEW,
		PERMISSION_AGENT_SUMMARY_RECORD_VIEW,
		PERMISSION_AGENT_BONUS_VIEW,
		PERMISSION_AGENT_BONUS_UPDATE,
		PERMISSION_AGENT_SETTING_UPDATE,
		PERMISSION_GAME_TRANSACTION_REPORT,
		PERMISSION_REBATE_TRANSACTION_REPORT,
		PERMISSION_REBATE_VIEW,
		PERMISSION_REBATE_UPDATE,
		PERMISSION_PLAYER_VOUCHER_ADJUSTMENT,
		PERMISSION_4D_SUMMARY_VIEW,
		PERMISSION_4D_SUMMARY_RECORD_VIEW,
		PERMISSION_4D_BONUS_VIEW,
		PERMISSION_4D_BONUS_UPDATE,
		PERMISSION_4D_SETTING_UPDATE,
		PERMISSION_PLAYER_GAME_POINT_ADJUSTMENT,
		PERMISSION_BLACKLIST_IMPORT_VIEW,
		PERMISSION_BLACKLIST_IMPORT_ADD,
		PERMISSION_BLACKLIST_IMPORT_UPDATE,
		PERMISSION_BLACKLIST_IMPORT_DELETE,
		PERMISSION_DEPOSIT_UPDATE_REMARK,
		PERMISSION_WITHDRAWAL_UPDATE_REMARK,
		PERMISSION_WHITELIST_ADD,
		PERMISSION_WHITELIST_UPDATE,
		PERMISSION_WHITELIST_VIEW,
		PERMISSION_WHITELIST_DELETE,
		PERMISSION_TRANSACTION_PENDING_ANNOUNCEMENT,
		PERMISSION_BLACKLIST_ANNOUNCEMENT,
		PERMISSION_BLOG_ADD,
		PERMISSION_BLOG_UPDATE,
		PERMISSION_BLOG_VIEW,
		PERMISSION_BLOG_DELETE,
		PERMISSION_USER_ROLE_ADD,
		PERMISSION_USER_ROLE_UPDATE,
		PERMISSION_USER_ROLE_VIEW,
		PERMISSION_USER_ROLE_DELETE,
		PERMISSION_BLOG_CATEGORY_ADD,
		PERMISSION_BLOG_CATEGORY_UPDATE,
		PERMISSION_BLOG_CATEGORY_VIEW,
		PERMISSION_BLOG_CATEGORY_DELETE,
		PERMISSION_BLOG_FRONTEND_VIEW,
		PERMISSION_PLAYER_UPDATE_ADDITIONAL_INFO,
		PERMISSION_PLAYER_BANK_IMAGE,
		PERMISSION_WITHDRAWAL_FEE_RATE_ADD,
		PERMISSION_WITHDRAWAL_FEE_RATE_UPDATE,
		PERMISSION_WITHDRAWAL_FEE_RATE_DELETE,
		PERMISSION_WITHDRAWAL_FEE_RATE_VIEW,
		PERMISSION_BLACKLIST_REPORT_UPDATE,
		PERMISSION_WIN_LOSS_REPORT_PLAYER,
		PERMISSION_YEARLY_REPORT,
		PERMISSION_HOME,
		PERMISSION_PLAYER_MOBILE,
		PERMISSION_PLAYER_LINE_ID,
		PERMISSION_PLAYER_NICKNAME,
		PERMISSION_PLAYER_EMAIL,
		PERMISSION_VIEW_PLAYER_CONTACT_V2,
		PERMISSION_PLAYER_GAME_TRANSFER,
		PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_VIEW,
		PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_ADD,
		PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_UPDATE,
		PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_DELETE,
		PERMISSION_PLAYER_PROMOTION_TURNOVER_CALCULATE,
		PERMISSION_PLAYER_AGENT_VIEW,
		PERMISSION_PLAYER_AGENT_UPDATE,
		PERMISSION_PLAYER_AGENT_ADD,
		PERMISSION_GAME_MAINTENANCE_DELETE,
		PERMISSION_SYSTEM_MESSAGE_USER_VIEW_CONTENT,
		PERMISSION_PLAYER_UPDATE_ADDITIONAL_DETAIL_INFO,
		PERMISSION_PLAYER_ACCOUNT_NAME,
		PERMISSION_PLAYER_WITHDRAWAL_VERIFY_REPORT,
		PERMISSION_REGISTER_DEPOSIT_RATE_REPORT,
		PERMISSION_REGISTER_DEPOSIT_RATE_YEARLY_REPORT,
		PERMISSION_TAG_ADD,
		PERMISSION_TAG_UPDATE,
		PERMISSION_TAG_VIEW,
		PERMISSION_TAG_DELETE,
		PERMISSION_TAG_PLAYER_ADD,
		PERMISSION_TAG_PLAYER_UPDATE,
		PERMISSION_TAG_PLAYER_VIEW,
		PERMISSION_TAG_PLAYER_DELETE,
		PERMISSION_TAG_PLAYER_MODIFY,
		PERMISSION_TAG_MODIFY,
		PERMISSION_TAG_PROCESS_REPORT,
		PERMISSION_PLAYER_BANK_IMAGE_DELETE,
		PERMISSION_PLAYER_BANK_IMAGE_ANNOUNCEMENT,
		PERMISSION_BANK_AGENT_PLAYER_USER_VIEW,
		PERMISSION_AGENT_PLAYER_DEPOSIT_VIEW,
		PERMISSION_SEO_ADD,
		PERMISSION_SEO_DELETE,
		PERMISSION_PLAYER_PROMOTION_BULK_ADD,
		PERMISSION_PLAYER_PROMOTION_BULK_UPDATE,
		PERMISSION_CONTENT_ADD,
		PERMISSION_CONTENT_UPDATE,
		PERMISSION_CONTENT_VIEW,
		PERMISSION_CONTENT_DELETE,
		PERMISSION_CONTENT_FRONTEND_VIEW,
		PERMISSION_DEPOSIT_VIEW_ALL,
		PERMISSION_WITHDRAWAL_VIEW_ALL,
        PERMISSION_PAYMENT_GATEWAY_LIMITED_ADD,
		PERMISSION_PAYMENT_GATEWAY_LIMITED_UPDATE,
		PERMISSION_PAYMENT_GATEWAY_LIMITED_VIEW,
		PERMISSION_PAYMENT_GATEWAY_LIMITED_DELETE,
		PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_ADD,
		PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_UPDATE,
		PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_VIEW,
		PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_DELETE,
		PERMISSION_TAG_PLAYER_BULK_MODIFY,
		PERMISSION_BANK_VERIFY_SUBMIT,
		PERMISSION_PLAYER_BANK_WITHDRAWAL_COUNT_UDPATE,
		PERMISSION_PLAYER_REPORT_EXPORT_EXCEL,
		PERMISSION_DEPOSIT_REPORT_EXPORT_EXCEL,
		PERMISSION_WITHDRAWAL_REPORT_EXPORT_EXCEL,
		PERMISSION_WIN_LOSS_PLAYER_REPORT_EXPORT_EXCEL,
		PERMISSION_YEARLY_REPORT_EXPORT_EXCEL,
		PERMISSION_TRANSACTION_REPORT_EXPORT_EXCEL,
		PERMISSION_POINT_REPORT_EXPORT_EXCEL,
		PERMISSION_CASH_REPORT_EXPORT_EXCEL,
		PERMISSION_REWARD_REPORT_EXPORT_EXCEL,
		PERMISSION_VERIFY_REPORT_EXPORT_EXCEL,
		PERMISSION_WALLET_REPORT_EXPORT_EXCEL,
		PERMISSION_PLAYER_RISK_REPORT_EXPORT_EXCEL,
		PERMISSION_PLAYER_LOGIN_REPORT_EXPORT_EXCEL,
		PERMISSION_PLAYER_LIST_EXPORT_EXCEL,
		PERMISSION_PLAYER_PROMOTION_LIST_EXPORT_EXCEL,
		PERMISSION_WIN_LOSS_REPORT_EXPORT_EXCEL,
		PERMISSION_WITHDRAWAL_VERIFY_REPORT_EXPORT_EXCEL,
		PERMISSION_REGISTER_DEPOSIT_RATE_REPORT_EXPORT_EXCEL,
		PERMISSION_REGISTER_DEPOSIT_RATE_YEARLY_REPORT_EXPORT_EXCEL,
		PERMISSION_REPORT_ALL,	
		PERMISSION_AGENT_VIEW,
		PERMISSION_AGENT_UPDATE,
		PERMISSION_AGENT_REPORT_EXPORT_EXCEL,
		PERMISSION_VIP_REWARD_VIEW,
		PERMISSION_VIP_REWARD_UPDATE,
		PERMISSION_VIP_REWARD_REPORT_EXPORT_EXCEL,
		PERMISSION_BANK_REPORT_VIEW,
		PERMISSION_BANK_REPORT_EXPORT_EXCEL,
		PERMISSION_PLAYER_LOG_VIEW,
		PERMISSION_KYC_VIEW,
		PERMISSION_KYC_ADD,
		PERMISSION_KYC_UPDATE,
		PERMISSION_MARQUEE_ADD,
		PERMISSION_MARQUEE_UPDATE,
		PERMISSION_MARQUEE_VIEW,
		PERMISSION_MARQUEE_DELETE
	);
	$exclude_permission_list = array(
		PERMISSION_PERMISSION_SETUP,
		PERMISSION_DEPOSIT_APPROVE_ADD,
		PERMISSION_WITHDRAWAL_APPROVE_ADD,
		PERMISSION_REWARD_DEDUCT,
	);
	$list = array_values(array_diff($full_permission_list,$exclude_permission_list));
	return $list;
}
/**
 * Admin Full Permission
 *
 * @access	public
 * @return	array
 */
function get_admin_full_permission() 
{
	$list = array(
		0 => PERMISSION_MISCELLANEOUS_UPDATE,
		1 => PERMISSION_CONTACT_UPDATE,
		2 => PERMISSION_CONTACT_VIEW,
		3 => PERMISSION_SEO_UPDATE,
		4 => PERMISSION_SEO_VIEW,
		5 => PERMISSION_GAME_UPDATE,
		6 => PERMISSION_GAME_VIEW,
		7 => PERMISSION_BANNER_ADD,
		8 => PERMISSION_BANNER_UPDATE,
		9 => PERMISSION_BANNER_DELETE,
		10 => PERMISSION_BANNER_VIEW,
		11 => PERMISSION_ANNOUNCEMENT_ADD,
		12 => PERMISSION_ANNOUNCEMENT_UPDATE,
		13 => PERMISSION_ANNOUNCEMENT_DELETE,
		14 => PERMISSION_ANNOUNCEMENT_VIEW,
		15 => PERMISSION_GROUP_ADD,
		16 => PERMISSION_GROUP_UPDATE,
		17 => PERMISSION_GROUP_DELETE,
		18 => PERMISSION_GROUP_VIEW,
		19 => PERMISSION_BANK_ADD,
		20 => PERMISSION_BANK_UPDATE,
		21 => PERMISSION_BANK_DELETE,
		22 => PERMISSION_BANK_VIEW,
		23 => PERMISSION_BANK_ACCOUNT_ADD,
		24 => PERMISSION_BANK_ACCOUNT_UPDATE,
		25 => PERMISSION_BANK_ACCOUNT_DELETE,
		26 => PERMISSION_BANK_ACCOUNT_VIEW,
		27 => PERMISSION_PROMOTION_ADD,
		28 => PERMISSION_PROMOTION_UPDATE,
		29 => PERMISSION_PROMOTION_DELETE,
		30 => PERMISSION_PROMOTION_VIEW,
		31 => PERMISSION_SUB_ACCOUNT_ADD,
		32 => PERMISSION_SUB_ACCOUNT_UPDATE,
		33 => PERMISSION_SUB_ACCOUNT_VIEW,
		34 => PERMISSION_USER_ADD,
		35 => PERMISSION_USER_UPDATE,
		36 => PERMISSION_USER_VIEW,
		37 => PERMISSION_PLAYER_ADD,
		38 => PERMISSION_PLAYER_UPDATE,
		39 => PERMISSION_PLAYER_VIEW,
		40 => PERMISSION_PERMISSION_SETUP,
		41 => PERMISSION_CHANGE_PASSWORD,
		42 => PERMISSION_DEPOSIT_POINT_TO_DOWNLINE,
		43 => PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE,
		44 => PERMISSION_VIEW_PLAYER_CONTACT,
		45 => PERMISSION_VIEW_PLAYER_WALLET,
		46 => PERMISSION_PLAYER_WALLET_TRANSFER,
		47 => PERMISSION_PLAYER_POINT_ADJUSTMENT,
		48 => PERMISSION_KICK_PLAYER,
		49 => PERMISSION_DEPOSIT_UPDATE,
		50 => PERMISSION_DEPOSIT_VIEW,
		51 => PERMISSION_WITHDRAWAL_UPDATE,
		52 => PERMISSION_WITHDRAWAL_VIEW,
		53 => PERMISSION_PLAYER_PROMOTION_ADD,
		54 => PERMISSION_PLAYER_PROMOTION_UPDATE,
		55 => PERMISSION_PLAYER_PROMOTION_VIEW,
		56 => PERMISSION_PLAYER_BONUS_ADD,
		57 => PERMISSION_PLAYER_BONUS_UPDATE,
		58 => PERMISSION_PLAYER_BONUS_VIEW,
		59 => PERMISSION_WIN_LOSS_REPORT,
		60 => PERMISSION_TRANSACTION_REPORT,
		61 => PERMISSION_POINT_TRANSACTION_REPORT,
		62 => PERMISSION_CASH_TRANSACTION_REPORT,
		63 => PERMISSION_WALLET_TRANSACTION_REPORT,
		64 => PERMISSION_LOGIN_REPORT,
		65 => PERMISSION_BANK_CHANNEL_ADD,
		66 => PERMISSION_BANK_CHANNEL_UPDATE,
		67 => PERMISSION_BANK_CHANNEL_DELETE,
		68 => PERMISSION_BANK_CHANNEL_VIEW,
		69 => PERMISSION_SYSTEM_MESSAGE_ADD,
		70 => PERMISSION_SYSTEM_MESSAGE_UPDATE,
		71 => PERMISSION_SYSTEM_MESSAGE_DELETE,
		72 => PERMISSION_SYSTEM_MESSAGE_VIEW,
		73 => PERMISSION_SYSTEM_MESSAGE_USER_ADD,
		74 => PERMISSION_SYSTEM_MESSAGE_USER_UPDATE,
		75 => PERMISSION_SYSTEM_MESSAGE_USER_DELETE,
		76 => PERMISSION_SYSTEM_MESSAGE_USER_VIEW,
		77 => PERMISSION_BANK_PLAYER_ADD,
		78 => PERMISSION_BANK_PLAYER_UPDATE,
		79 => PERMISSION_BANK_PLAYER_DELETE,
		80 => PERMISSION_BANK_PLAYER_VIEW,
		81 => PERMISSION_BANK_PLAYER_USER_ADD,
		82 => PERMISSION_BANK_PLAYER_USER_UPDATE,
		83 => PERMISSION_BANK_PLAYER_USER_DELETE,
		84 => PERMISSION_BANK_PLAYER_USER_VIEW,
		85 => PERMISSION_LEVEL_ADD,
		86 => PERMISSION_LEVEL_UPDATE,
		87 => PERMISSION_LEVEL_DELETE,
		88 => PERMISSION_LEVEL_VIEW,
		89 => PERMISSION_DEPOSIT_ONLINE_VIEW,
		90 => PERMISSION_DEPOSIT_OFFLINE_VIEW,
		91 => PERMISSION_DEPOSIT_PENDING_VIEW,
		92 => PERMISSION_DEPOSIT_HOLDING_VIEW,
		93 => PERMISSION_DEPOSIT_REVIEWING_VIEW,
		94 => PERMISSION_DEPOSIT_PENDING_DOC_VIEW,
		95 => PERMISSION_DEPOSIT_APPROVE_VIEW,
		96 => PERMISSION_DEPOSIT_CANCEL_VIEW,
		97 => PERMISSION_DEPOSIT_PENDING_ACTION,
		98 => PERMISSION_DEPOSIT_HOLDING_ACTION,
		99 => PERMISSION_DEPOSIT_REVIEWING_ACTION,
		100 => PERMISSION_DEPOSIT_PENDING_DOC_ACTION,
		101 => PERMISSION_DEPOSIT_APPROVE_ACTION,
		102 => PERMISSION_DEPOSIT_CANCEL_ACTION,
		103 => PERMISSION_WITHDRAW_PENDING_VIEW,
		104 => PERMISSION_WITHDRAW_HOLDING_VIEW,
		105 => PERMISSION_WITHDRAW_REVIEWING_VIEW,
		106 => PERMISSION_WITHDRAW_PENDING_DOC_VIEW,
		107 => PERMISSION_WITHDRAW_APPROVE_VIEW,
		108 => PERMISSION_WITHDRAW_CANCEL_VIEW,
		109 => PERMISSION_WITHDRAW_PENDING_ACTION,
		110 => PERMISSION_WITHDRAW_HOLDING_ACTION,
		111 => PERMISSION_WITHDRAW_REVIEWING_ACTION,
		112 => PERMISSION_WITHDRAW_PENDING_DOC_ACTION,
		113 => PERMISSION_WITHDRAW_APPROVE_ACTION,
		114 => PERMISSION_WITHDRAW_CANCEL_ACTION,
		115 => PERMISSION_DEPOSIT_ADD,
		116 => PERMISSION_WITHDRAWAL_ADD,
		117 => PERMISSION_AVATAR_ADD,
		118 => PERMISSION_AVATAR_UPDATE,
		119 => PERMISSION_AVATAR_DELETE,
		120 => PERMISSION_AVATAR_VIEW,
		121 => PERMISSION_FINGERPRINT_VIEW,
		122 => PERMISSION_BONUS_ADD,
		123 => PERMISSION_BONUS_UPDATE,
		124 => PERMISSION_BONUS_VIEW,
		125 => PERMISSION_BONUS_DELETE,
		126 => PERMISSION_MATCH_ADD,
		127 => PERMISSION_MATCH_UPDATE,
		128 => PERMISSION_MATCH_DELETE,
		129 => PERMISSION_MATCH_VIEW,
		130 => PERMISSION_LEVEL_EXECUTE_ADD,
		131 => PERMISSION_LEVEL_EXECUTE_UPDATE,
		132 => PERMISSION_LEVEL_EXECUTE_DELETE,
		133 => PERMISSION_LEVEL_EXECUTE_VIEW,
		134 => PERMISSION_LEVEL_LOG_VIEW,
		135 => PERMISSION_LEVEL_LOG_UPDATE,
		136 => PERMISSION_REWARD_TRANSACTION_REPORT,
		137 => PERMISSION_REWARD_VIEW,
		138 => PERMISSION_REWARD_UPDATE,
		139 => PERMISSION_REWARD_DEDUCT,
		140 => PERMISSION_VERIFY_CODE_REPORT,
		141 => PERMISSION_PLAYER_DAILY_REPORT,
		142 => PERMISSION_REPORT_EXPORT_EXCEL,
		143 => PERMISSION_PLAYER_RISK_REPORT,
		144 => PERMISSION_PAYMENT_GATEWAY_VIEW,
		145 => PERMISSION_PAYMENT_GATEWAY_UPDATE,
		146 => PERMISSION_ADMIN_LOG_VIEW,
		147 => PERMISSION_ADMIN_PLAYER_LOG_VIEW,
		148 => PERMISSION_SUB_ACCOUNT_LOG_VIEW,
		149 => PERMISSION_SUB_ACCOUNT_PLAYER_LOG_VIEW,
		150 => PERMISSION_GAME_MAINTENANCE_VIEW,
		151 => PERMISSION_GAME_MAINTENANCE_ADD,
		152 => PERMISSION_GAME_MAINTENANCE_UPDATE,
		153 => PERMISSION_POSSESS_ADJUSTMENT,
		154 => PERMISSION_WALLET_TRANSACTION_PENDING_VIEW,
		155 => PERMISSION_WALLET_TRANSACTION_PENDING_UPDATE,
		156 => PERMISSION_PLAYER_PROMOTION_BET_DETAIL,
		157 => PERMISSION_PLAYER_PROMOTION_ON_RUNING,
		158 => PERMISSION_DEPOSIT_OFFLINE_NOTICE,
		159 => PERMISSION_DEPOSIT_ONLINE_NOTICE,
		160 => PERMISSION_DEPOSIT_CREDIT_CARD_NOTICE,
		161 => PERMISSION_DEPOSIT_HYPERMARKET_NOTICE,
		162 => PERMISSION_WITHDRAWAL_OFFLINE_NOTICE,
		163 => PERMISSION_RISK_MANAGEMENT,
		164 => PERMISSION_FINGERPRINT_MANAGEMENT,
		165 => PERMISSION_LEVEL_MANAGEMENT,
		166 => PERMISSION_DEPOSIT_OFFLINE_ANNOUNCEMENT,
		167 => PERMISSION_DEPOSIT_ONLINE_ANNOUNCEMENT,
		168 => PERMISSION_DEPOSIT_CREDIT_CARD_ANNOUNCEMENT,
		169 => PERMISSION_DEPOSIT_HYPERMARKET_ANNOUNCEMENT,
		170 => PERMISSION_WITHDRAWAL_OFFLINE_ANNOUNCEMENT,
		171 => PERMISSION_CURRENCIES_ADD,
		172 => PERMISSION_CURRENCIES_UPDATE,
		173 => PERMISSION_CURRENCIES_VIEW,
		174 => PERMISSION_CURRENCIES_DELETE,
		175 => PERMISSION_DEPOSIT_APPROVE_ADD,
		176 => PERMISSION_WITHDRAWAL_APPROVE_ADD,
		177 => PERMISSION_WITHDRAWAL_APPROVE_ADD,
		178 => PERMISSION_SYSTEM_EMAIL,
		179 => PERMISSION_WIN_LOSS_SUM_REPORT,
		180 => PERMISSION_DEPOSIT_UPDATE_REMARK,
		181 => PERMISSION_WITHDRAWAL_UPDATE_REMARK,
		182 => PERMISSION_BLACKLIST_ADD,
		183 => PERMISSION_BLACKLIST_UPDATE,
		184 => PERMISSION_BLACKLIST_VIEW,
		185 => PERMISSION_BLACKLIST_DELETE,
		186 => PERMISSION_BLACKLIST_REPORT,
		187 => PERMISSION_WHITELIST_ADD,
		188 => PERMISSION_WHITELIST_UPDATE,
		189 => PERMISSION_WHITELIST_VIEW,
		190 => PERMISSION_WHITELIST_DELETE,
		191 => PERMISSION_TRANSACTION_PENDING_ANNOUNCEMENT,
		192 => PERMISSION_BLACKLIST_ANNOUNCEMENT,
		193 => PERMISSION_BLOG_ADD,
		194 => PERMISSION_BLOG_UPDATE,
		195 => PERMISSION_BLOG_VIEW,
		196 => PERMISSION_BLOG_DELETE,
		197 => PERMISSION_USER_ROLE_ADD,
		198 => PERMISSION_USER_ROLE_UPDATE,
		199 => PERMISSION_USER_ROLE_VIEW,
		200 => PERMISSION_USER_ROLE_DELETE,
		201 => PERMISSION_BLOG_CATEGORY_ADD,
		202 => PERMISSION_BLOG_CATEGORY_UPDATE,
		203 => PERMISSION_BLOG_CATEGORY_VIEW,
		204 => PERMISSION_BLOG_CATEGORY_DELETE,
		205 => PERMISSION_BLOG_FRONTEND_VIEW,
		206 => PERMISSION_PLAYER_UPDATE_ADDITIONAL_INFO,
		207 => PERMISSION_PLAYER_BANK_IMAGE,
		208 => PERMISSION_WITHDRAWAL_FEE_RATE_ADD,
		209 => PERMISSION_WITHDRAWAL_FEE_RATE_UPDATE,
		210 => PERMISSION_WITHDRAWAL_FEE_RATE_DELETE,
		211 => PERMISSION_WITHDRAWAL_FEE_RATE_VIEW,
		212 => PERMISSION_BLACKLIST_REPORT_UPDATE,
		213 => PERMISSION_WIN_LOSS_REPORT_PLAYER,
		214 => PERMISSION_YEARLY_REPORT,
		215 => PERMISSION_HOME,
		216 => PERMISSION_PLAYER_MOBILE,
		217 => PERMISSION_PLAYER_LINE_ID,
		218 => PERMISSION_PLAYER_NICKNAME,
		219 => PERMISSION_PLAYER_EMAIL,
		220 => PERMISSION_VIEW_PLAYER_CONTACT_V2,
		221 => PERMISSION_AGENT_VIEW,
		222 => PERMISSION_AGENT_UPDATE,
		223 => PERMISSION_AGENT_REPORT_EXPORT_EXCEL,
		224 => PERMISSION_VIP_REWARD_VIEW,
		225 => PERMISSION_VIP_REWARD_UPDATE,
		226 => PERMISSION_VIP_REWARD_REPORT_EXPORT_EXCEL,
	);
	return $list;
}
// ------------------------------------------------------------------------
/**
 * Admin Basic Permission
 *
 * @access	public
 * @return	array
 */
function get_admin_basic_permission() 
{
	$list = array(
		0 => PERMISSION_PLAYER_VIEW,
	);
	return $list;
}
// ------------------------------------------------------------------------
/**
 * Agent Full Permission
 *
 * @access	public
 * @return	array
 */
function get_agent_full_permission() 
{
	$list = array(
		0 => PERMISSION_USER_VIEW,
		1 => PERMISSION_WIN_LOSS_REPORT,
		2 => PERMISSION_TRANSACTION_REPORT,
		3 => PERMISSION_WIN_LOSS_REPORT_PLAYER,
		4 => PERMISSION_PLAYER_AGENT_VIEW,
		5 => PERMISSION_LOGIN_REPORT,
		6 => PERMISSION_PLAYER_AGENT_UPDATE,
		7 => PERMISSION_PLAYER_AGENT_ADD,
		8 => PERMISSION_CHANGE_PASSWORD,
	);
	return $list;
}
// ------------------------------------------------------------------------
/**
 * Agent Basic Permission
 *
 * @access	public
 * @return	array
 */
function get_agent_basic_permission() 
{
	$list = array(
		0 => PERMISSION_USER_VIEW,
		1 => PERMISSION_WIN_LOSS_REPORT,
		2 => PERMISSION_TRANSACTION_REPORT,
		3 => PERMISSION_WIN_LOSS_REPORT_PLAYER,
		4 => PERMISSION_PLAYER_AGENT_VIEW,
		5 => PERMISSION_LOGIN_REPORT,
		6 => PERMISSION_PLAYER_AGENT_UPDATE,
		7 => PERMISSION_PLAYER_AGENT_ADD,
		8 => PERMISSION_CHANGE_PASSWORD,
	);
	return $list;
}
// ------------------------------------------------------------------------
/**
 * Permission Validation
 *
 * @access	public
 * @param	string
 * @return	boolean
 */
function permission_validation($module = NULL) 
{
	$obj =& get_instance();
	$permissions = $obj->session->userdata('permissions');
	$arr = explode(',', $permissions);
	$result = FALSE;
	/*
	if($obj->session->userdata('user_group') == USER_GROUP_SUB_ACCOUNT && ($module == PERMISSION_SUB_ACCOUNT_ADD OR $module == PERMISSION_SUB_ACCOUNT_UPDATE OR $module == PERMISSION_SUB_ACCOUNT_VIEW))
	{
		$result = FALSE;
	}
	else{
		if(in_array($module, $arr))
		{
			$result = TRUE;
		}
	}
	*/
	if(in_array($module, $arr))
	{
		$result = TRUE;
	}	
	return $result;
}
// ------------------------------------------------------------------------
/**
 * Get Upline Permission
 *
 * @access	public
 * @param	string
 * @return	string
 */
function get_upline_permission($user_group = NULL, $user_type = NULL, $permissions = NULL) 
{
	$result = '';
	$permission_arr = array();
	$arr = explode(',', $permissions);
	if($user_group == USER_GROUP_USER)
	{
		$permission_arr = get_agent_full_permission();
	}
	else
	{
		if($user_type == USER_SA)
		{
			$permission_arr = get_admin_basic_permission();
		}
		else
		{
			$permission_arr = get_agent_basic_permission();
		}	
	}
	for($i=0;$i<sizeof($arr);$i++)
	{
		if(in_array($arr[$i], $permission_arr))
		{
			$result .= $arr[$i] . ',';
		}
	}
	if(substr($result, -1) == ',')
	{
		$result = substr($result, 0, -1);
	}
	return $result;
}
// ------------------------------------------------------------------------
/**
 * Get User Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_user_type($type = NULL) 
{
	$list = array(
		USER_SA => 'level_sa',
		USER_SMA => 'level_sma',
		USER_MA => 'level_ma',
		USER_AG => 'level_ag'
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Get Downline User Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_downline_user_type($type = NULL) 
{
	$list = array(
		1 => USER_SMA,
		2 => USER_MA,
		3 => USER_AG,
		4 => USER_AG
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Get Deposit Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_deposit_type($type = NULL) 
{
	$list = array(
		DEPOSIT_OFFLINE_BANKING => 'deposit_offline_banking',
		DEPOSIT_ONLINE_BANKING => 'deposit_online_banking',
		DEPOSIT_CREDIT_CARD => 'deposit_credit_card',
		DEPOSIT_HYPERMART => 'deposit_hypermart',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_withdrawal_type($type = NULL) 
{
	$list = array(
		WITHDRAWAL_OFFLINE_BANKING => 'withdrawal_offline_banking',
		WITHDRAWAL_ONLINE_BANKING => 'withdrawal_online_banking',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Get Transfer Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_avatar_type($type = NULL) 
{
	$list = array(
		AVATAR_ALL => 'avatar_all',
		AVATAR_INDIVIDUAL => 'avatar_individual',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Get Transfer Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_apply_type($type = NULL) 
{
	$list = array(
		PROMOTION_USER_TYPE_SYSTEM => "promotion_user_type_system",
		PROMOTION_USER_TYPE_ADMIN => "promotion_user_type_admin",
		PROMOTION_USER_TYPE_PLAYER => "promotion_user_type_player",
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Get Transfer Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_calculated_type($type = NULL) 
{
	$list = array(
		PROMOTION_USER_TYPE_SYSTEM => "promotion_user_type_system",
		PROMOTION_USER_TYPE_ADMIN => "promotion_user_type_admin",
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Get Transfer Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_rejected_type($type = NULL) 
{
	$list = array(
		PROMOTION_USER_TYPE_SYSTEM => "promotion_user_type_system",
		PROMOTION_USER_TYPE_ADMIN => "promotion_user_type_admin",
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Get Transfer Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_claim_type($type = NULL) 
{
	$list = array(
		PROMOTION_USER_TYPE_SYSTEM => "promotion_user_type_system",
		PROMOTION_USER_TYPE_ADMIN => "promotion_user_type_admin",
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Get Transfer Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_transfer_type($type = NULL) 
{
	$list = array(
		TRANSFER_POINT_IN => 'transfer_point_in',
		TRANSFER_POINT_OUT => 'transfer_point_out',
		TRANSFER_ADJUST_IN => 'transfer_adjust_in',
		TRANSFER_ADJUST_OUT => 'transfer_adjust_out',
		TRANSFER_OFFLINE_DEPOSIT => 'transfer_offline_deposit',
		TRANSFER_PG_DEPOSIT => 'transfer_pg_deposit',
		TRANSFER_WITHDRAWAL => 'transfer_withdrawal',
		TRANSFER_WITHDRAWAL_REFUND => 'transfer_withdrawal_refund',
		TRANSFER_PROMOTION => 'transfer_promotion',
		TRANSFER_BONUS => 'transfer_bonus',
		TRANSFER_COMMISSION => 'transfer_comission',
		TRANSFER_TRANSACTION_IN => 'transfer_transaction_in',
		TRANSFER_TRANSACTION_OUT => 'transfer_transaction_out',
		TRANSFER_CREDIT_CARD_DEPOSIT => 'transfer_credit_card_deposit',
		TRANSFER_HYPERMART_DEPOSIT => 'transfer_hypermart_deposit',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_transfer_reward_type($type = NULL) 
{
	$list = array(
		TRANSFER_REWARD_IN => 'transfer_reward_in',
		TRANSFER_REWARD_OUT => 'transfer_reward_out',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Get Game Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_game_type($type = NULL) 
{
	$list = array(
		GAME_SPORTSBOOK => 'game_type_sb',
		GAME_LIVE_CASINO => 'game_type_lc',
		GAME_SLOTS => 'game_type_sl',
		GAME_FISHING => 'game_type_fh',
		GAME_ESPORTS => 'game_type_es',
		GAME_BOARD_GAME => 'game_type_bg',
		GAME_LOTTERY => 'game_type_lt',
		GAME_KENO => 'game_type_kn',
		GAME_VIRTUAL_SPORTS => 'game_type_vs',
		GAME_POKER => 'game_type_pk',
		GAME_COCKFIGHTING => 'game_type_cf',
		GAME_OTHERS => 'game_type_ot',
		GAME_L => 'game_type_sl'
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_game_type_all() 
{
	$list = array(
		GAME_SPORTSBOOK,
		GAME_LIVE_CASINO,
		GAME_SLOTS,
		GAME_FISHING,
		GAME_ESPORTS,
		GAME_BOARD_GAME,
		GAME_LOTTERY,
		GAME_KENO,
		GAME_VIRTUAL_SPORTS,
		GAME_POKER,
		GAME_COCKFIGHTING,
		GAME_OTHERS,
	);
	return $list;
}
// ------------------------------------------------------------------------
/**
 * Promotion Date Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_promotion_date_type($type = NULL) 
{
	$list = array(
		PROMOTION_DATE_TYPE_START_TO_END => 'promotion_date_type_start_to_end',
		PROMOTION_DATE_TYPE_START_NO_LIMIT => 'promotion_date_type_start_no_limit',
		//PROMOTION_DATE_TYPE_SPECIFIC_DAY_WEEK => 'promotion_date_type_specific_day_week',
		//PROMOTION_DATE_TYPE_SPECIFIC_DAY_DAY => 'promotion_date_type_specific_day_day',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function live_casino_type($type=NULL){
	$list = array(
		LIVE_CASINO_BACCARAT => "live_casino_baccarat",
		LIVE_CASINO_NON_BACCARAT => "live_casino_non_baccarat",
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Promotion Date Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_specific_day_day($type = NULL) 
{
	$list = array(
		1 => '1',
		2 => '2',
		3 => '3',
		4 => '4',
		5 => '5',
		6 => '6',
		7 => '7',
		8 => '8',
		9 => '9',
		10 => '10',
		11 => '11',
		12 => '12',
		13 => '13',
		14 => '14',
		15 => '15',
		16 => '16',
		17 => '17',
		18 => '18',
		19 => '19',
		20 => '20',
		21 => '21',
		22 => '22',
		23 => '23',
		24 => '24',
		25 => '25',
		26 => '26',
		27 => '27',
		28 => '28',
		29 => '29',
		30 => '30',
		31 => '31',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
// ------------------------------------------------------------------------
/**
 * Promotion Calculate Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_promotion_calculate_type($type = NULL) 
{
	$list = array(
		PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL => 'promotion_calculate_type_valid_bet_total',
		PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS => 'promotion_calculate_type_valid_bet_win_loss',
		PROMOTION_CALCULATE_TYPE_VALID_BET_WIN => 'promotion_calculate_type_valid_bet_win',
		PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS => 'promotion_calculate_type_valid_bet_loss',
		PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN => 'promotion_calculate_type_win_loss_win',
		PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS => 'promotion_calculate_type_win_loss_loss',
		PROMOTION_CALCULATE_TYPE_TOTAL_WINLOSS => 'promotion_calculate_type_total_winloss',
		PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL => 'promotion_calculate_type_promotion_bet_total',
		PROMOTION_CALCULATE_TYPE_WALLET_AMOUNT => 'promotion_calculate_type_wallet_amount',
		
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
/**
 * Promotion Calculate Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_promotion_bonus_range_type($type = NULL) 
{
	$list = array(
		PROMOTION_BONUS_RANGE_TYPE_GENERAL => 'promotion_bonus_range_type_general',
		PROMOTION_BONUS_RANGE_TYPE_LEVEL => 'promotion_bonus_range_type_level',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
/**
 * Promotion Calculate Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_promotion_bonus_type($type = NULL) 
{
	$list = array(
		PROMOTION_BONUS_TYPE_PERCENTAGE => 'promotion_bonus_type_percentage',
		PROMOTION_BONUS_TYPE_FIX_AMOUNT => 'promotion_bonus_type_fix_amount',
		PROMOTION_BONUS_TYPE_FIX_AMOUNT_FROM => 'promotion_bonus_type_fix_amount_from',
		PROMOTION_BONUS_TYPE_PERCENTAGE_TURNOVER => 'promotion_bonus_type_percentage_turnover',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
/**
 * Promotion Calculate Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_times_limit_type($type = NULL) 
{
	$list = array(
		PROMOTION_TIMES_LIMIT_TYPE_NO_LIMIT => 'promotion_times_limit_type_no_limit',
		PROMOTION_TIMES_LIMIT_TYPE_EVERY_DAY_ONCE => 'promotion_times_limit_type_every_day_once',
		PROMOTION_TIMES_LIMIT_TYPE_EVERY_WEEK_ONCE => 'promotion_times_limit_type_every_week_once',
		PROMOTION_TIMES_LIMIT_TYPE_EVERY_MONTH_ONCE => 'promotion_times_limit_type_every_month_once',
		PROMOTION_TIMES_LIMIT_TYPE_EVERY_YEARS_ONCE => 'promotion_times_limit_type_every_years_once',
		PROMOTION_TIMES_LIMIT_TYPE_ONCE => 'promotion_times_limit_type_once',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
/**
 * Promotion Calculate Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_promotion_day_type($type = NULL) 
{
	$list = array(
		PROMOTION_DAY_TYPE_EVERYDAY => 'promotion_day_type_everyday',
		PROMOTION_DAY_TYPE_MONDAY => 'promotion_day_type_monday',
		PROMOTION_DAY_TYPE_TUEDAY => 'promotion_day_type_tueday',
		PROMOTION_DAY_TYPE_WEDNESDAY => 'promotion_day_type_wednesday',
		PROMOTION_DAY_TYPE_THURSDAY => 'promotion_day_type_thursday',
		PROMOTION_DAY_TYPE_FRIDAY => 'promotion_day_type_friday',
		PROMOTION_DAY_TYPE_SATURDAY => 'promotion_day_type_saturday',
		PROMOTION_DAY_TYPE_SUNDAY => 'promotion_day_type_sunday',
		PROMOTION_DAY_TYPE_EVERYTIME => 'promotion_day_type_everytime',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_promotion_apply_date($type = NULL) 
{
	$list = array(
		PROMOTION_DAY_TYPE_MONDAY => 'promotion_day_type_monday',
		PROMOTION_DAY_TYPE_TUEDAY => 'promotion_day_type_tueday',
		PROMOTION_DAY_TYPE_WEDNESDAY => 'promotion_day_type_wednesday',
		PROMOTION_DAY_TYPE_THURSDAY => 'promotion_day_type_thursday',
		PROMOTION_DAY_TYPE_FRIDAY => 'promotion_day_type_friday',
		PROMOTION_DAY_TYPE_SATURDAY => 'promotion_day_type_saturday',
		PROMOTION_DAY_TYPE_SUNDAY => 'promotion_day_type_sunday',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function quick_search(){
	$data['date_last_month_from'] 	= date('Y-m-d 00:00:00', strtotime('first day of previous month'));
	$data['date_last_month_to'] 	= date('Y-m-d 23:59:59', strtotime('last day of previous month'));
	$data['date_last_week_from'] 	= date('Y-m-d 00:00:00', strtotime('monday last week'));
	$data['date_last_week_to'] 		= date('Y-m-d 23:59:59', strtotime('sunday last week'));
	$data['date_yesterday_from'] 	= date("Y-m-d 00:00:00", strtotime('-1 days'));
	$data['date_yesterday_to']	 	= date("Y-m-d 23:59:59", strtotime('-1 days'));
	$data['date_today_from'] 		= date("Y-m-d 00:00:00");
	$data['date_today_to'] 			= date("Y-m-d 23:59:59");
	$data['date_current_week_from'] = date("Y-m-d 00:00:00", strtotime('monday this week'));
	$data['date_current_week_to'] 	= date("Y-m-d 23:59:59", strtotime('sunday this week'));
	$data['date_current_month_from'] = date('Y-m-d 00:00:00', strtotime('first day of this month'));
	$data['date_current_month_to'] 	= date('Y-m-d 23:59:59', strtotime('last day of this month'));
	$data['date_thirty_days_from'] 	= date("Y-m-d 00:00:00", strtotime('-30 days'));
	$data['date_thirty_days_to']	 	= date("Y-m-d 23:59:59", strtotime('-0 days'));
	$data['date_last_month_from_date'] 	= date('Y-m-d', strtotime('first day of previous month'));
	$data['date_last_month_to_date'] 	= date('Y-m-d', strtotime('last day of previous month'));
	$data['date_last_week_from_date'] 	= date('Y-m-d', strtotime('monday last week'));
	$data['date_last_week_to_date'] = date('Y-m-d', strtotime('sunday last week'));
	$data['date_yesterday_from_date'] = date("Y-m-d", strtotime('-1 days'));
	$data['date_yesterday_to_date'] =  date("Y-m-d", strtotime('-1 days'));
	$data['date_today_from_date'] 		= date("Y-m-d");
	$data['date_today_to_date'] 		= date("Y-m-d");
	$data['date_current_week_from_date'] = date("Y-m-d", strtotime('monday this week'));
	$data['date_current_week_to_date'] 	= date("Y-m-d", strtotime('sunday this week'));
	$data['date_current_month_from_date'] = date('Y-m-d', strtotime('first day of this month'));
	$data['date_current_month_to_date'] 	= date('Y-m-d', strtotime('last day of this month'));
	$data['date_last_two_week_from_date'] 	= date('Y-m-d', strtotime('monday last week')-(7*24*60*60));
	$data['date_last_two_week_to_date'] = date('Y-m-d', strtotime('sunday last week')-(7*24*60*60));
	$data['date_clear_from'] 		= '';
	$data['date_clear_to'] 			= '';
	return $data;
}
function get_promotion_level($type = NULL)
{
	$list = array(
		1 => '1',
		2 => '2',
		3 => '3',
		4 => '4',
		5 => '5',
		6 => '6',
		7 => '7',
		8 => '8',
		9 => '9',
		10 => '10',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_expirate_type($type = NULL) 
{
	$list = array(
		1 => '1',
		2 => '2',
		3 => '3',
		4 => '4',
		5 => '5',
		6 => '6',
		7 => '7',
		8 => '8',
		9 => '9',
		10 => '10',
		11 => '11',
		12 => '12',
		13 => '13',
		14 => '14',
		15 => '15',
		16 => '16',
		17 => '17',
		18 => '18',
		19 => '19',
		20 => '20',
		21 => '21',
		22 => '22',
		23 => '23',
		24 => '24',
		25 => '25',
		26 => '26',
		27 => '27',
		28 => '28',
		29 => '29',
		30 => '30',
		31 => '31',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function maintain_membership_type($type = NULL) 
{
	$list = array(
		MAINTAIN_MEMBERSHIP_TYPE_WEEKLY => 'maintain_membership_type_weekly',
		MAINTAIN_MEMBERSHIP_TYPE_MONTHLY => 'maintain_membership_type_monthly',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_excel_color_status($type = NULL){
	$list = array(
		EXPORT_COLOR_BLUE => array('font'  => array('color' => array('rgb' => '0000FF'),)),
		EXPORT_COLOR_RED => array('font'  => array('color' => array('rgb' => 'FF0000'),)),
		EXPORT_COLOR_BLACK => array('font'  => array('color' => array('rgb' => '000000'),)),
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}	
}
function announcement_type($type = NULL){
	$list = array(
		ANNOUNCEMENT_DEPOSIT_OFFLINE => 'announcement_deposit_offline',
		ANNOUNCEMENT_DEPOSIT_ONLINE => 'announcement_deposit_online',
		ANNOUNCEMENT_DEPOSIT_CREDIT_CARD => 'announcement_deposit_credit_card',
		ANNOUNCEMENT_DEPOSIT_HYPERMART => 'announcement_deposit_hypermart',
		ANNOUNCEMENT_WITHDRAWAL => 'announcement_withdrawal',
		ANNOUNCEMENT_PROMOTION => 'announcement_promotion',
		ANNOUNCEMENT_RISK => 'announcement_risk',
		ANNOUNCEMENT_RISK_FROZEN => 'announcement_risk_frozen',
		ANNOUNCEMENT_BLACKLIST => 'announcement_blacklist',
		ANNOUNCEMENT_PLAYER_BANK_IMAGE => 'announcement_player_bank_image',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}	
}
function risk_period_type($type = NULL){
	$list = array(
		RISK_DAILY => 'risk_daily',
		RISK_MONTHLY => 'risk_monthly',
		RISK_YEARLY => 'risk_yearly',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}	
}
function suspended_type($type = NULL){
	$list = array(
		STATUS_SUSPEND => 'status_suspend',
		STATUS_UNSUSPEND => 'status_unsuspend',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}	
}
function time_different_gaps($time_current = NULL, $time_target = NULL)
{
    $time = $time_current - $time_target; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'time_different_year',
        2592000 => 'time_different_month',
        604800 => 'time_different_week',
        86400 => 'time_different_day',
        3600 => 'time_different_hour',
        60 => 'time_different_minute',
        1 => 'time_different_second'
    );
    foreach ($tokens as $unit => $text) {
    	$obj =& get_instance();
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$obj->lang->line($text);
    }
}
function level_upgrade_type($type = NULL){
	$list = array(
		LEVEL_UPGRADE_DEPOSIT => 'level_upgrade_deposit',
		LEVEL_UPGRADE_TARGET => 'level_upgrade_target',
		LEVEL_UPGRADE_DEPOSIT_TARGET => 'level_upgrade_deposit_target',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}	
}
function level_downgrade_type($type = NULL){
	$list = array(
		LEVEL_DOWNGRADE_DEPOSIT => 'level_downgrade_deposit',
		LEVEL_DOWNGRADE_TARGET => 'level_downgrade_target',
		LEVEL_DOWNGRADE_DEPOSIT_TARGET => 'level_downgrade_deposit_target',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}	
}
function get_level_target($type  = NULL){
	$list = array(
		LEVEL_TARGET_ALL => 'level_target_all',
		LEVEL_TARGET_SAME_PROVIDER => 'level_target_same_provider',
		LEVEL_TARGET_SAME_GAME => 'level_target_same_game',
		LEVEL_TARGET_SAME_PROVIDER_SAME_GAME => 'level_target_same_provider_same_game',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}	
}
function get_player_log(){
	$list = array(
		0 => LOG_PLAYER_ADD,
		1 => LOG_PLAYER_UPDATE,
		2 => LOG_PLAYER_PASSWORD,
		3 => LOG_PLAYER_DEPOSIT_POINT,
		4 => LOG_PLAYER_WITHDRAW_POINT,
		5 => LOG_PLAYER_WALLET_TRANSFER,
		6 => LOG_PLAYER_POINT_ADJUSTMENT,
		7 => LOG_KICK_PLAYER,
		8 => LOG_DEPOSIT_UPDATE,
		9 => LOG_WITHDRAWAL_UPDATE,
		10 => LOG_PLAYER_PROMOTION_ADD,
		11 => LOG_PLAYER_PROMOTION_UPDATE,
		12 => LOG_BANK_PLAYER_USER_ADD,
		13 => LOG_BANK_PLAYER_USER_UPDATE,
		14 => LOG_BANK_PLAYER_USER_DELETE,
		15 => LOG_PLAYER_BONUS_ADD,
		16 => LOG_PLAYER_BONUS_UPDATE,
		17 => LOG_PLAYER_BONUS_DELETE,
		18 => LOG_REWARD_ADD,
		19 => LOG_REWARD_UPDATE,
		20 => LOG_REWARD_DEDUCT,
	);
	return $list;	
}
function get_admin_log(){
	$list = array(
		0 => LOG_LOGIN,
		1 => LOG_LOGOUT,
		2 => LOG_CHANGE_PASSWORD,
		3 => LOG_MISCELLANEOUS_UPDATE,
		4 => LOG_CONTACT_UPDATE,
		5 => LOG_SEO_UPDATE,
		6 => LOG_GAME_UPDATE,
		7 => LOG_BANNER_ADD,
		8 => LOG_BANNER_UPDATE,
		9 => LOG_BANNER_DELETE,
		10 => LOG_ANNOUNCEMENT_ADD,
		11 => LOG_ANNOUNCEMENT_UPDATE,
		12 => LOG_ANNOUNCEMENT_DELETE,
		13 => LOG_GROUP_ADD,
		14 => LOG_GROUP_UPDATE,
		15 => LOG_GROUP_DELETE,
		16 => LOG_BANK_ADD,
		17 => LOG_BANK_UPDATE,
		18 => LOG_BANK_DELETE,
		19 => LOG_BANK_ACCOUNT_ADD,
		20 => LOG_BANK_ACCOUNT_UPDATE,
		21 => LOG_BANK_ACCOUNT_DELETE,
		22 => LOG_PROMOTION_ADD,
		23 => LOG_PROMOTION_UPDATE,
		24 => LOG_PROMOTION_DELETE,
		25 => LOG_SUB_ACCOUNT_ADD,
		26 => LOG_SUB_ACCOUNT_UPDATE,
		27 => LOG_SUB_ACCOUNT_PERMISSION,
		28 => LOG_SUB_ACCOUNT_PASSWORD,
		29 => LOG_USER_ADD,
		30 => LOG_USER_UPDATE,
		31 => LOG_USER_PERMISSION,
		32 => LOG_USER_PASSWORD,
		33 => LOG_USER_DEPOSIT_POINT,
		34 => LOG_USER_WITHDRAW_POINT,
		35 => LOG_PLAYER_ADD,
		36 => LOG_PLAYER_UPDATE,
		37 => LOG_PLAYER_PASSWORD,
		38 => LOG_PLAYER_DEPOSIT_POINT,
		39 => LOG_PLAYER_WITHDRAW_POINT,
		40 => LOG_PLAYER_WALLET_TRANSFER,
		41 => LOG_PLAYER_POINT_ADJUSTMENT,
		42 => LOG_KICK_PLAYER,
		43 => LOG_DEPOSIT_UPDATE,
		44 => LOG_WITHDRAWAL_UPDATE,
		45 => LOG_PLAYER_PROMOTION_ADD,
		46 => LOG_PLAYER_PROMOTION_UPDATE,
		47 => LOG_BANK_CHANNEL_ADD,
		48 => LOG_BANK_CHANNEL_UPDATE,
		49 => LOG_BANK_CHANNEL_DELETE,
		50 => LOG_SYSTEM_MESSAGE_ADD,
		51 => LOG_SYSTEM_MESSAGE_UPDATE,
		52 => LOG_SYSTEM_MESSAGE_DELETE,
		53 => LOG_SYSTEM_MESSAGE_USER_ADD,
		54 => LOG_SYSTEM_MESSAGE_USER_UPDATE,
		55 => LOG_SYSTEM_MESSAGE_USER_DELETE,
		56 => LOG_BANK_PLAYER_ADD,
		57 => LOG_BANK_PLAYER_UPDATE,
		58 => LOG_BANK_PLAYER_DELETE,
		59 => LOG_BANK_PLAYER_USER_ADD,
		60 => LOG_BANK_PLAYER_USER_UPDATE,
		61 => LOG_BANK_PLAYER_USER_DELETE,
		62 => LOG_LEVEL_ADD,
		63 => LOG_LEVEL_UPDATE,
		64 => LOG_LEVEL_DELETE,
		65 => LOG_AVATAR_ADD,
		66 => LOG_AVATAR_UPDATE,
		67 => LOG_AVATAR_DELETE,
		68 => LOG_BONUS_ADD,
		69 => LOG_BONUS_UPDATE,
		70 => LOG_BONUS_DELETE,
		71 => LOG_PLAYER_BONUS_ADD,
		72 => LOG_PLAYER_BONUS_UPDATE,
		73 => LOG_PLAYER_BONUS_DELETE,
		74 => LOG_MATCH_ADD,
		75 => LOG_MATCH_UPDATE,
		76 => LOG_MATCH_DELETE,
		77 => LOG_LEVEL_BATCH_APPROVE,
		78 => LOG_LEVEL_SINGLE_APPROVE,
		79 => LOG_LEVEL_SINGLE_REJECT,
		80 => LOG_REWARD_ADD,
		81 => LOG_REWARD_UPDATE,
		82 => LOG_REWARD_DEDUCT,
		83 => LOG_LEVEL_EXECUTE_ADD,
		84 => LOG_PAYMENT_GATEWAY_UPDATE,
	);
	return $list;
}
function log_type($type = NULL){
	$list = array(
		LOG_LOGIN => 'log_login',
		LOG_LOGOUT => 'log_logout',
		LOG_CHANGE_PASSWORD => 'log_change_password',
		LOG_MISCELLANEOUS_UPDATE => 'log_miscellaneous_update',
		LOG_CONTACT_UPDATE => 'log_contact_update',
		LOG_SEO_UPDATE => 'log_seo_update',
		LOG_GAME_UPDATE => 'log_game_update',
		LOG_BANNER_ADD => 'log_banner_add',
		LOG_BANNER_UPDATE => 'log_banner_update',
		LOG_BANNER_DELETE => 'log_banner_delete',
		LOG_ANNOUNCEMENT_ADD => 'log_announcement_add',
		LOG_ANNOUNCEMENT_UPDATE => 'log_announcement_update',
		LOG_ANNOUNCEMENT_DELETE => 'log_announcement_delete',
		LOG_GROUP_ADD => 'log_group_add',
		LOG_GROUP_UPDATE => 'log_group_update',
		LOG_GROUP_DELETE => 'log_group_delete',
		LOG_BANK_ADD => 'log_bank_add',
		LOG_BANK_UPDATE => 'log_bank_update',
		LOG_BANK_DELETE => 'log_bank_delete',
		LOG_BANK_ACCOUNT_ADD => 'log_bank_account_add',
		LOG_BANK_ACCOUNT_UPDATE => 'log_bank_account_update',
		LOG_BANK_ACCOUNT_DELETE => 'log_bank_account_delete',
		LOG_PROMOTION_ADD => 'log_promotion_add',
		LOG_PROMOTION_UPDATE => 'log_promotion_update',
		LOG_PROMOTION_DELETE => 'log_promotion_delete',
		LOG_SUB_ACCOUNT_ADD => 'log_sub_account_add',
		LOG_SUB_ACCOUNT_UPDATE => 'log_sub_account_update',
		LOG_SUB_ACCOUNT_PERMISSION => 'log_sub_account_permission',
		LOG_SUB_ACCOUNT_PASSWORD => 'log_sub_account_password',
		LOG_USER_ADD => 'log_user_add',
		LOG_USER_UPDATE => 'log_user_update',
		LOG_USER_PERMISSION => 'log_user_permission',
		LOG_USER_PASSWORD => 'log_user_password',
		LOG_USER_DEPOSIT_POINT => 'log_user_deposit_point',
		LOG_USER_WITHDRAW_POINT => 'log_user_withdraw_point',
		LOG_PLAYER_ADD => 'log_player_add',
		LOG_PLAYER_UPDATE => 'log_player_update',
		LOG_PLAYER_PASSWORD => 'log_player_password',
		LOG_PLAYER_DEPOSIT_POINT => 'log_player_deposit_point',
		LOG_PLAYER_WITHDRAW_POINT => 'log_player_withdraw_point',
		LOG_PLAYER_WALLET_TRANSFER => 'log_player_wallet_transfer',
		LOG_PLAYER_POINT_ADJUSTMENT => 'log_player_point_adjustment',
		LOG_KICK_PLAYER => 'log_kick_player',
		LOG_DEPOSIT_UPDATE => 'log_deposit_update',
		LOG_WITHDRAWAL_UPDATE => 'log_withdrawal_update',
		LOG_PLAYER_PROMOTION_ADD => 'log_player_promotion_add',
		LOG_PLAYER_PROMOTION_UPDATE => 'log_player_promotion_update',
		LOG_BANK_CHANNEL_ADD => 'log_bank_channel_add',
		LOG_BANK_CHANNEL_UPDATE => 'log_bank_channel_update',
		LOG_BANK_CHANNEL_DELETE => 'log_bank_channel_delete',
		LOG_SYSTEM_MESSAGE_ADD => 'log_system_message_add',
		LOG_SYSTEM_MESSAGE_UPDATE => 'log_system_message_update',
		LOG_SYSTEM_MESSAGE_DELETE => 'log_system_message_delete',
		LOG_SYSTEM_MESSAGE_USER_ADD => 'log_system_message_user_add',
		LOG_SYSTEM_MESSAGE_USER_UPDATE => 'log_system_message_user_update',
		LOG_SYSTEM_MESSAGE_USER_DELETE => 'log_system_message_user_delete',
		LOG_BANK_PLAYER_ADD => 'log_bank_player_add',
		LOG_BANK_PLAYER_UPDATE => 'log_bank_player_update',
		LOG_BANK_PLAYER_DELETE => 'log_bank_player_delete',
		LOG_BANK_PLAYER_USER_ADD => 'log_bank_player_user_add',
		LOG_BANK_PLAYER_USER_UPDATE => 'log_bank_player_user_update',
		LOG_BANK_PLAYER_USER_DELETE => 'log_bank_player_user_delete',
		LOG_LEVEL_ADD => 'log_level_add',
		LOG_LEVEL_UPDATE => 'log_level_update',
		LOG_LEVEL_DELETE => 'log_level_delete',
		LOG_AVATAR_ADD => 'log_avatar_add',
		LOG_AVATAR_UPDATE => 'log_avatar_update',
		LOG_AVATAR_DELETE => 'log_avatar_delete',
		LOG_BONUS_ADD => 'log_bonus_add',
		LOG_BONUS_UPDATE => 'log_bonus_update',
		LOG_BONUS_DELETE => 'log_bonus_delete',
		LOG_PLAYER_BONUS_ADD => 'log_player_bonus_add',
		LOG_PLAYER_BONUS_UPDATE => 'log_player_bonus_update',
		LOG_PLAYER_BONUS_DELETE => 'log_player_bonus_delete',
		LOG_MATCH_ADD => 'log_match_add',
		LOG_MATCH_UPDATE => 'log_match_update',
		LOG_MATCH_DELETE => 'log_match_delete',
		LOG_LEVEL_BATCH_APPROVE => 'log_level_batch_approve',
		LOG_LEVEL_SINGLE_APPROVE => 'log_level_single_approve',
		LOG_LEVEL_SINGLE_REJECT => 'log_level_single_reject',
		LOG_REWARD_ADD => 'log_reward_add',
		LOG_REWARD_UPDATE => 'log_reward_update',
		LOG_REWARD_DEDUCT => 'log_reward_deduct',
		LOG_LEVEL_EXECUTE_ADD => 'log_level_execute_add',
		LOG_PAYMENT_GATEWAY_UPDATE => 'log_payment_gateway_update',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}	
}
function get_mark_type($type = NULL) 
{
	$list = array(
		1 => 'light',
		2 => 'info',
		3 => 'primary',
		4 => 'warning',
		5 => 'orange',
		6 => 'pink',
		7 => 'danger',
		8 => 'teal',
		9 => 'success',
		10 => 'indigo',
		11 => 'purple',
		12 => 'navy',
		13 => 'secondary',
		14 => 'black',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_payment_gateway($type = NULL){
    $list = array(
		1 => 'payment_gateway_gspay_online',
		2 => 'payment_gateway_gspay_hypermart',
		3 => 'payment_gateway_mycash_online',
		4 => 'payment_gateway_mycash_credit_card',
		5 => 'payment_gateway_mycash_hypermart',
		6 => 'payment_gateway_paychain_online',
		7 => 'payment_gateway_paychain_hypermart',
		8 => 'payment_gateway_coolpay_online',
		9 => 'payment_gateway_coolpay_credit_card',
		10 => 'payment_gateway_coolpay_hypermart',
		11 => 'payment_gateway_fastspay_online',
		12 => 'payment_gateway_paylah88_online',
		13 => 'payment_gateway_gspay_online',
		14 => 'payment_gateway_eeziepay_online',
		15 => 'payment_gateway_payflow_online',
		16 => 'payment_gateway_payflow_hypermart',
		17 => 'payment_gateway_payflow_credit_card',
		18 => 'payment_gateway_fuzepay_credit_card',
		19 => 'payment_gateway_fuzepay_hypermart',
		20 => 'payment_gateway_fuzepay_online',
		21 => 'payment_gateway_xinwang_credit_card',
	);
	if( ! empty($type))
	{
	    if(isset($list[$type])){
	        return $list[$type];    
	    }else{
	        return 'deposit_online_banking';
	    }
	}	
	else
	{
		return $list;
	}
}
function payment_gateway_code($payment_gateway = NULL, $bank_code = NULL){
    $payment_gateway_code = "";
    if($payment_gateway == "TRUEPAY"){
		switch($bank_code) {
			case "MBB": $payment_gateway_code = "MBB"; break;
			case "CIMB": $payment_gateway_code = "CIMB"; break;
			case "RHB": $payment_gateway_code = "RHB"; break;
			case "PBB": $payment_gateway_code = "PBB"; break;
			case "HLB": $payment_gateway_code = "HLB"; break;
			case "AFFIN": $payment_gateway_code = "AFFIN"; break;
			case "ALLIANCE": $payment_gateway_code = "ALLIANCE"; break;
			case "AMBANK": $payment_gateway_code = "AMBANK"; break;
			case "ISLAM": $payment_gateway_code = "ISLAM"; break;
			case "BSN": $payment_gateway_code = "BSN"; break;
			case "CITIM": $payment_gateway_code = "CITIM"; break;
			case "HSBCM": $payment_gateway_code = "HSBCM"; break;
			case "OCBCM": $payment_gateway_code = "OCBCM"; break;
			case "UOBM": $payment_gateway_code = "UOBM"; break;
			case "STCM": $payment_gateway_code = "STCM"; break;
			case "AGRO": $payment_gateway_code = "AGRO"; break;
			case "RAKYAT": $payment_gateway_code = "RAKYAT"; break;
			case "TNGQR": $payment_gateway_code = "TNGQR"; break;
			case "DUITNOW": $payment_gateway_code = "DUITNOW"; break;
			case "ACB": $payment_gateway_code = "ACB"; break;
			case "SAC": $payment_gateway_code = "SAC"; break;
			case "TCB": $payment_gateway_code = "TCB"; break;
			case "VCB": $payment_gateway_code = "VCB"; break;
			case "VTB": $payment_gateway_code = "VTB"; break;
			case "DAB": $payment_gateway_code = "DAB"; break;
			case "BIDV": $payment_gateway_code = "BIDV"; break;
			case "EXIM": $payment_gateway_code = "EXIM"; break;
			case "AGB": $payment_gateway_code = "AGB"; break;
			case "TPB": $payment_gateway_code = "TPB"; break;
			case "MB": $payment_gateway_code = "MB"; break;
			case "VPB": $payment_gateway_code = "VPB"; break;
			case "OCB": $payment_gateway_code = "OCB"; break;
			case "SCB": $payment_gateway_code = "SCB"; break;
			case "SHB": $payment_gateway_code = "SHB"; break;
			case "ABB": $payment_gateway_code = "ABB"; break;
			case "NASB": $payment_gateway_code = "NASB"; break;
			case "KLB": $payment_gateway_code = "KLB"; break;
			case "BVB": $payment_gateway_code = "BVB"; break;
			case "HDB": $payment_gateway_code = "HDB"; break;
			case "LPB": $payment_gateway_code = "LPB"; break;
			case "MSB": $payment_gateway_code = "MSB"; break;
			case "NAMA": $payment_gateway_code = "NAMA"; break;
			case "NCB": $payment_gateway_code = "NCB"; break;
			case "OCEAN": $payment_gateway_code = "OCEAN"; break;
			case "PVCOM": $payment_gateway_code = "PVCOM"; break;
			case "SAIGON": $payment_gateway_code = "SAIGON"; break;
			case "SEA": $payment_gateway_code = "SEA"; break;
			case "SHBVN": $payment_gateway_code = "SHBVN"; break;
			case "VIB": $payment_gateway_code = "VIB"; break;
			case "VRB": $payment_gateway_code = "VRB"; break;
			case "VAB": $payment_gateway_code = "VAB"; break;
			case "MOQR": $payment_gateway_code = "MOQR"; break;
			case "VCBQR": $payment_gateway_code = "VCBQR"; break;
			case "BIDVQR": $payment_gateway_code = "BIDVQR"; break;
			case "VTBQR": $payment_gateway_code = "VTBQR"; break;
			case "AGBQR": $payment_gateway_code = "AGBQR"; break;
			case "ACBQR": $payment_gateway_code = "ACBQR"; break;
			case "VPBQR": $payment_gateway_code = "VPBQR"; break;
			case "TPBQR": $payment_gateway_code = "TPBQR"; break;
			case "MBVQR": $payment_gateway_code = "MBVQR"; break;
			case "VIBQR": $payment_gateway_code = "VIBQR"; break;
			case "SINGAPORE": $payment_gateway_code = "SINGAPORE"; break;
			case "CIMBS": $payment_gateway_code = "CIMBS"; break;
			case "MBBS": $payment_gateway_code = "MBBS"; break;
			case "UOBS": $payment_gateway_code = "UOBS"; break;
			case "CITIS": $payment_gateway_code = "CITIS"; break;
			case "DBS": $payment_gateway_code = "DBS"; break;
			case "HSBCS": $payment_gateway_code = "HSBCS"; break;
			case "OCBCS": $payment_gateway_code = "OCBCS"; break;
			case "POSB": $payment_gateway_code = "POSB"; break;
			case "PAYNOW": $payment_gateway_code = "PAYNOW"; break;
			case "PAYNOWQR": $payment_gateway_code = "PAYNOWQR"; break;
			default: $payment_gateway_code = ""; break;
		}
	}
	else if($payment_gateway == "FASTSPAY"){
        switch($bank_code)
		{
			case "ALLB": $payment_gateway_code = "ALB"; break;
			case "AMB": $payment_gateway_code = "ARB"; break;
			case "BIM": $payment_gateway_code = "BIMB"; break;
			case "BKR": $payment_gateway_code = "BKR"; break;
			case "BSN": $payment_gateway_code = "BSN"; break;
			case "CIMB": $payment_gateway_code = "CIMB"; break;
			case "CITB": $payment_gateway_code = "CITI"; break;
			case "HLB": $payment_gateway_code = "HLB"; break;
			case "HSBC": $payment_gateway_code = "HSBC"; break;
			case "MBB": $payment_gateway_code = "MBB"; break;
			case "OCBC": $payment_gateway_code = "OCBC"; break;
			case "PBB": $payment_gateway_code = "PBB"; break;
			case "RHB": $payment_gateway_code = "RHB"; break;
			case "SCB": $payment_gateway_code = "SCB"; break;
			case "UOB": $payment_gateway_code = "UOB"; break;
			default: $payment_gateway_code = ""; break;
		}
    }else if($payment_gateway == "GSPAY2"){
        switch($bank_code)
		{
			case "AFFB": $payment_gateway_code = "AFFB"; break;
			case "AGRO": $payment_gateway_code = "AGRO"; break;
			case "ALLB": $payment_gateway_code = "ALLB"; break;
			case "AMB": $payment_gateway_code = "AMB"; break;
			case "BIM": $payment_gateway_code = "BIM"; break;
			case "BMML": $payment_gateway_code = "BMML"; break;
			case "BKR": $payment_gateway_code = "BKR"; break;
			case "BSN": $payment_gateway_code = "BSN"; break;
			case "CIMB": $payment_gateway_code = "CIMB"; break;
			case "CITB": $payment_gateway_code = "CITB"; break;
			case "HLB": $payment_gateway_code = "HLB"; break;
			case "HSBC": $payment_gateway_code = "HSBC"; break;
			case "MBB": $payment_gateway_code = "MBB"; break;
			case "OCBC": $payment_gateway_code = "OCBC"; break;
			case "PBB": $payment_gateway_code = "PBB"; break;
			case "RHB": $payment_gateway_code = "RHB"; break;
			case "SCB": $payment_gateway_code = "SCB"; break;
			case "UOB": $payment_gateway_code = "UOB"; break;
			default: $payment_gateway_code = ""; break;
		}
		
    }else{
        $payment_gateway_code = "";
    }
    return $payment_gateway_code;
}
function get_game_round_type($type = NULL){
    $list = array(
		GAME_ROUND_TYPE_GAME_ROUND => 'game_round_type_game_round',
		GAME_ROUND_TYPE_FREE_SPIN => 'game_round_type_free_spin',
		GAME_ROUND_TYPE_JACKPOT => 'game_round_type_jackpot',
		GAME_ROUND_TYPE_TIP => 'game_round_type_tip',
		GAME_ROUND_TYPE_GAME_ACTIVITY => 'game_round_type_game_activity',
	);
	if($type !== NULL)
	{
	    return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_blacklist_type($type = NULL){
	$list = array(
		BLACKLIST_BANK_ACCOUNT => 'blacklist_bank_account',
		BLACKLIST_BANK_NAME => 'blacklist_bank_name',
		BLACKLIST_IP => 'blacklist_ip',
		BLACKLIST_PHONE_NUMBER => 'blacklist_phone_number',
		BLACKLIST_LINE_NUMBER => 'blacklist_line_number',
	);
	if($type !== NULL)
	{
	    return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_affiliate_bonus_type($type = NULL){
	$list = array(
		AFFILIATE_BONUS_TYPE_LEADER_BONUS => 'affiliate_bonus_type_leader_bonus',
		AFFILIATE_BONUS_TYPE_INTRO_BONUS => 'affiliate_bonus_type_intro_bonus',
		AFFILIATE_BONUS_TYPE_DAILY_BONUS => 'affiliate_bonus_type_daily_bonus',
	);
	if($type !== NULL)
	{
	    return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_timezone_name($timezone = NULL) 
{
	$list = array(
		'-11' => 'Pacific/Niue',
		'-10' => 'Pacific/Rarotonga',
		'-9' => 'Pacific/Gambier',
		'-8' => 'Pacific/Pitcairn',
		'-7' => 'America/Creston',
		'-6' => 'America/Belize',
		'-5' => 'America/Bogota',
		'-4' => 'America/Anguilla',
		'-3' => 'America/Araguaina',
		'-2' => 'America/Noronha',
		'-1' => 'Atlantic/Cape Verde',
		'0' => 'UTC',
		'1' => 'Africa/Algiers',
		'2' => 'Africa/Windhoek',
		'3' => 'Africa/Asmara',
		'4' => 'Asia/Dubai',
		'5' => 'Asia/Atyrau',
		'6' => 'Asia/Dhaka',
		'7' => 'Asia/Bangkok',
		'8' => 'Asia/Brunei',
		'9' => 'Asia/Seoul',
		'10' => 'Asia/Vladivostok',
		'11' => 'Asia/Magadan',
		'12' => 'Asia/Anadyr',
		'13' => 'Pacific/Kanton',
		'14' => 'Pacific/Kiritimati',
	);
	if( ! empty($timezone))
	{
		return $list[$timezone];
	}	
	else
	{
		return $list;
	}
}
/**
 * Get Deposit Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_whitelist_type($type = NULL) 
{
	$list = array(
		DEPOSIT_ONLINE_BANKING => 'deposit_online_banking',
		DEPOSIT_CREDIT_CARD => 'deposit_credit_card',
		DEPOSIT_HYPERMART => 'deposit_hypermart',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
/**
 * Get Deposit Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_payment_gateway_rate_type($type = NULL) 
{
	$list = array(
		PAYMENT_GATEWAY_RATE_TYPE_AMOUNT => 'payment_gateway_rate_type_amount',
		PAYMENT_GATEWAY_RATE_TYPE_AMOUNT_RATE => 'payment_gateway_rate_type_amount_rate',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_blog_display($type = NULL){
	$list = array(
		BLOG_DISPLAY_BLOG => 'blog_display_blog',
		BLOG_DISPLAY_PAGE => 'blog_display_page',
		BLOG_DISPLAY_PRODUCT => 'blog_display_product',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_blog_page($type = NULL){
	$list = array(
		PAGE_HOME => 'blog_display_page_home',
		PAGE_LIVE_CASINO => 'blog_display_page_baccarat',
		PAGE_SPORTSBOOK => 'blog_display_page_sportbook',
		PAGE_LOTTERY => 'blog_display_page_bingo',
		PAGE_SLOTS => 'blog_display_page_slot',
		PAGE_BOARD_GAME => 'blog_display_page_mahjong',
		PAGE_FISHING => 'blog_display_page_fishing',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}	
}
/**
 * Get Withdrawal Rate Type
 *
 * @access	public
 * @param	string
 * @return	string or array
 */
function get_withdrawal_rate_type($type = NULL) 
{
	$list = array(
		WITHDRAWAL_RATE_TYPE_FIXED_AMOUNT => 'withdrawal_rate_type_fixed_amount',
		WITHDRAWAL_RATE_TYPE_PERCENT => 'withdrawal_rate_type_percent',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_system_message_content($message = NULL, $arr = NULL){
	if(!empty($arr) && sizeof($arr)>0){
		foreach($arr as $key => $value){
			$message = str_replace($key,$value,$message);
		}
	}
	return $message;
}
function get_yearly_report_setting($type = NULL){
	$list = array(
		YEARLY_REPORT_SETTING_WIN_LOSS => 'yearly_report_setting_win_loss',
		YEARLY_REPORT_SETTING_TURNOVER => 'yearly_report_setting_turnover',
		YEARLY_REPORT_SETTING_DEPOSIT => 'yearly_report_setting_deposit',
		YEARLY_REPORT_SETTING_WITHDRAWAL => 'yearly_report_setting_withdrawal',
		YEARLY_REPORT_SETTING_PROMOTION => 'yearly_report_setting_promotion',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_payment_gateway_type($type = NULL){
	$list = array(
		DEPOSIT_ONLINE_BANKING => 'deposit_online_banking',
		DEPOSIT_CREDIT_CARD => 'deposit_credit_card',
		DEPOSIT_HYPERMART => 'deposit_hypermart',
		WITHDRAWAL_ONLINE => 'withdrawal_online_banking',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_player_user_bank_type($type = NULL){
	$list = array(
		BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK => 'bank_player_bank',
		BANKS_PLAYER_USER_IMAGE_BANK_TYPE_CREDIT_CARD => 'bank_player_credit_card',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_player_bank_type($type = NULL){
	$list = array(
		BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK => 'bank_player_bank',
		BANKS_PLAYER_USER_IMAGE_BANK_TYPE_CREDIT_CARD => 'bank_player_credit_card',
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_blog_product($type = NULL){
	$list = array(
		PAGE_PRODUCT_KALI_BACARRAT => "blog_page_product_kali_bacarrat",
		PAGE_PRODUCT_AB_BACARRAT => "blog_page_product_ab_bacarrat",
		PAGE_PRODUCT_DG_BACARRAT => "blog_page_product_dg_bacarrat",
		PAGE_PRODUCT_WM_BACARRAT => "blog_page_product_wm_bacarrat",
		PAGE_PRODUCT_SA_BACARRAT => "blog_page_product_sa_bacarrat",
		PAGE_PRODUCT_OG_BACARRAT => "blog_page_product_og_bacarrat",
		PAGE_PRODUCT_OB_BACARRAT => "blog_page_product_ob_bacarrat",
		PAGE_PRODUCT_BTT_SPORTBOOK => "blog_page_product_btt_sportbook",
		PAGE_PRODUCT_SP_SPORTBOOK => "blog_page_product_sp_sportbook",
		PAGE_PRODUCT_9K_LOTTERY => "blog_page_product_9k_lottery",
		PAGE_PRODUCT_SP_LOTTERY => "blog_page_product_sp_lottery",
		PAGE_PRODUCT_RTG_SLOT => "blog_page_product_rtg_slot",
		PAGE_PRODUCT_DT_SLOT => "blog_page_product_dt_slot",
		PAGE_PRODUCT_SP_SLOT => "blog_page_product_sp_slot",
		PAGE_PRODUCT_ICG_SLOT => "blog_page_product_icg_slot",
		PAGE_PRODUCT_BNG_SLOT => "blog_page_product_bng_slot",
		PAGE_PRODUCT_SUPREME_MAHJONG => "blog_page_product_supreme_mahjong",
		PAGE_PRODUCT_SP_FISHING => "blog_page_product_sp_fishing",
		PAGE_PRODUCT_RTG_FISHING => "blog_page_product_rtg_fishing",
		PAGE_PRODUCT_GR_SLOT => "blog_page_product_gr_slot",
		PAGE_PRODUCT_RSG_SLOT => "blog_page_product_rsg_slot",
		PAGE_PRODUCT_BL_BOARD_GAME => "blog_page_product_bl_board_game",
		PAGE_PRODUCT_GR_BOARD_GAME => "blog_page_product_gr_board_game",
		PAGE_PRODUCT_ICG_FISHING => "blog_page_product_icg_fishing",
		PAGE_PRODUCT_GR_FISHING => "blog_page_product_gr_fishing",
		PAGE_PRODUCT_PNG_SLOT => "blog_page_product_png_slot",
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}	
}
function get_seo_page($type = NULL){
	$list = array(
		SEO_PAGE_HOME => strtolower('PAGE_HOME'),
		SEO_PAGE_SPORTSBOOK => strtolower('PAGE_SPORTSBOOK'),
		SEO_PAGE_ESPORTS => strtolower('PAGE_ESPORTS'),
		SEO_PAGE_LIVE_CASINO => strtolower('PAGE_LIVE_CASINO'),
		SEO_PAGE_SLOTS => strtolower('PAGE_SLOTS'),
		SEO_PAGE_FISHING => strtolower('PAGE_FISHING'),
		SEO_PAGE_ARCADE => strtolower('PAGE_ARCADE'),
		SEO_PAGE_BOARD_GAME => strtolower('PAGE_BOARD_GAME'),
		SEO_PAGE_LOTTERY => strtolower('PAGE_LOTTERY'),
		SEO_PAGE_POKER => strtolower('PAGE_POKER'),
		SEO_PAGE_PROMOTION => strtolower('PAGE_PROMOTION'),
		SEO_PAGE_ABOUT_US => strtolower('PAGE_ABOUT_US'),
		SEO_PAGE_FAQ => strtolower('PAGE_FAQ'),
		SEO_PAGE_CONTACT_US => strtolower('PAGE_CONTACT_US'),
		SEO_PAGE_TNC => strtolower('PAGE_TNC'),
		SEO_PAGE_RG => strtolower('PAGE_RG'),
		SEO_PAGE_VIP => strtolower('PAGE_VIP'),
		SEO_PAGE_MOVIE => strtolower('PAGE_MOVIE'),
		SEO_PAGE_LOGIN => strtolower('PAGE_LOGIN'),
		SEO_PAGE_REGISTER => strtolower('PAGE_REGISTER'),
		SEO_PAGE_FORGOT_PASSWORD => strtolower('PAGE_FORGOT_PASSWORD'),
		SEO_PAGE_PRODUCTS => strtolower('PAGE_PRODUCTS'),
		SEO_PAGE_PRODUCTS_CALI_BACCARAT => strtolower('PAGE_PRODUCTS_CALI_BACCARAT'),
		SEO_PAGE_PRODUCTS_ALLBET_BACCARAT => strtolower('PAGE_PRODUCTS_ALLBET_BACCARAT'),
		SEO_PAGE_PRODUCTS_SA_BACCARAT => strtolower('PAGE_PRODUCTS_SA_BACCARAT'),
		SEO_PAGE_PRODUCTS_DG_BACCARAT => strtolower('PAGE_PRODUCTS_DG_BACCARAT'),
		SEO_PAGE_PRODUCTS_WM_BACCARAT => strtolower('PAGE_PRODUCTS_WM_BACCARAT'),
		SEO_PAGE_PRODUCTS_CALI_SPORTS => strtolower('PAGE_PRODUCTS_CALI_SPORTS'),
		SEO_PAGE_PRODUCTS_SUPER_SPORTS => strtolower('PAGE_PRODUCTS_SUPER_SPORTS'),
		SEO_PAGE_PRODUCTS_9K_LOTTERY => strtolower('PAGE_PRODUCTS_9K_LOTTERY'),
		SEO_PAGE_PRODUCTS_SUPER_LOTTERY => strtolower('PAGE_PRODUCTS_SUPER_LOTTERY'),
		SEO_PAGE_PRODUCTS_RTG_ELECTRONICS => strtolower('PAGE_PRODUCTS_RTG_ELECTRONICS'),
		SEO_PAGE_PRODUCTS_DREAMTECH_ELECTRONICS => strtolower('PAGE_PRODUCTS_DREAMTECH_ELECTRONICS'),
		SEO_PAGE_PRODUCTS_SIMPLEPLAY_ELECTRONICS => strtolower('PAGE_PRODUCTS_SIMPLEPLAY_ELECTRONICS'),
		SEO_PAGE_PRODUCTS_BWIN_ELECTRONICS => strtolower('PAGE_PRODUCTS_BWIN_ELECTRONICS'),
		SEO_PAGE_PRODUCTS_BNG => strtolower('PAGE_PRODUCTS_BNG'),
		SEO_PAGE_PRODUCTS_OG => strtolower('PAGE_PRODUCTS_OG'),
		SEO_PAGE_PRODUCTS_SUPREME_GAMING => strtolower('PAGE_PRODUCTS_SUPREME_GAMING'),
		SEO_PAGE_PRODUCTS_RTG_FISH => strtolower('PAGE_PRODUCTS_RTG_FISH'),
		SEO_PAGE_PRODUCTS_SIMPLEPLAY_FISH => strtolower('PAGE_PRODUCTS_SIMPLEPLAY_FISH'),
		SEO_PAGE_PRODUCTS_OB_BACCARAT => strtolower('PAGE_PRODUCTS_OB_BACCARAT'),
		SEO_PAGE_BLOG => strtolower('PAGE_BLOG'),
		SEO_PAGE_USER_EVALUATION => strtolower('PAGE_USER_EVALUATION'),
		SEO_PAGE_ACCESS_PROCESS => strtolower('PAGE_ACCESS_PROCESS'),
		SEO_PAGE_APP => strtolower('PAGE_APP'),
		SEO_PAGE_FRANCHISE => strtolower('PAGE_FRANCHISE'),
		SEO_PAGE_NEWS => strtolower('PAGE_NEWS'),
		SEO_PAGE_GAME_MAINTENANCE => strtolower('PAGE_GAME_MAINTENANCE'),
		SEO_PAGE_MESSAGE => strtolower('PAGE_MESSAGE'),
		SEO_PAGE_ACCOUNT_TURNOVER => strtolower('PAGE_ACCOUNT_TURNOVER'),
		SEO_PAGE_ACCOUNT_TRANSACTION_HISTORY => strtolower('PAGE_ACCOUNT_TRANSACTION_HISTORY'),
		SEO_PAGE_ACCOUNT_CHANGE_PASSWORD => strtolower('PAGE_ACCOUNT_CHANGE_PASSWORD'),
		SEO_PAGE_ACCOUNT => strtolower('PAGE_ACCOUNT'),
		SEO_PAGE_DEPOSIT => strtolower('PAGE_DEPOSIT'),
		SEO_PAGE_WITHDRAWAL => strtolower('PAGE_WITHDRAWAL'),
		SEO_PAGE_BINDING_BANK => strtolower('PAGE_BINDING_BANK'),
		SEO_PAGE_TRANSFER => strtolower('PAGE_TRANSFER'),
		SEO_PAGE_BLOGS => strtolower('PAGE_BLOGS'),
		SEO_PAGE_PRODUCTS_GR => strtolower('PAGE_PRODUCTS_GR'),
		SEO_PAGE_PRODUCTS_RSG => strtolower('PAGE_PRODUCTS_RSG'),
		SEO_PAGE_PRODUCTS_BL => strtolower('PAGE_PRODUCTS_BL'),
		SEO_PAGE_PRODUCTS_GR_BOARD_GAME => strtolower('PAGE_PRODUCTS_GR_BOARD_GAME'),
		SEO_PAGE_PRODUCTS_ICG_FISH => strtolower('PAGE_PRODUCTS_ICG_FISH'),
		SEO_PAGE_PRODUCTS_GR_FISH => strtolower('PAGE_PRODUCTS_GR_FISH'),
		SEO_PAGE_PRODUCTS_PNG => strtolower('PAGE_PRODUCTS_PNG'),
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_content_page($type = NULL){
	$list = array(
		PAGE_PRODUCT_HOME => "blog_display_page_home",
		PAGE_PRODUCT_BACARRAT => "blog_display_page_baccarat",
		PAGE_PRODUCT_SPORTSBOOK => "blog_display_page_sportbook",
		PAGE_PRODUCT_LOTTERY => "blog_display_page_bingo",
		PAGE_PRODUCT_BOARD_GAME => "blog_display_page_slot",
		PAGE_PRODUCT_SLOTS => "blog_display_page_mahjong",
		PAGE_PRODUCT_FISHING => "blog_display_page_fishing",
		PAGE_PRODUCT_KALI_BACARRAT => "blog_page_product_kali_bacarrat",
		PAGE_PRODUCT_AB_BACARRAT => "blog_page_product_ab_bacarrat",
		PAGE_PRODUCT_DG_BACARRAT => "blog_page_product_dg_bacarrat",
		PAGE_PRODUCT_WM_BACARRAT => "blog_page_product_wm_bacarrat",
		PAGE_PRODUCT_SA_BACARRAT => "blog_page_product_sa_bacarrat",
		PAGE_PRODUCT_OG_BACARRAT => "blog_page_product_og_bacarrat",
		PAGE_PRODUCT_OB_BACARRAT => "blog_page_product_ob_bacarrat",
		PAGE_PRODUCT_BTT_SPORTBOOK => "blog_page_product_btt_sportbook",
		PAGE_PRODUCT_SP_SPORTBOOK => "blog_page_product_sp_sportbook",
		PAGE_PRODUCT_9K_LOTTERY => "blog_page_product_9k_lottery",
		PAGE_PRODUCT_SP_LOTTERY => "blog_page_product_sp_lottery",
		PAGE_PRODUCT_RTG_SLOT => "blog_page_product_rtg_slot",
		PAGE_PRODUCT_DT_SLOT => "blog_page_product_dt_slot",
		PAGE_PRODUCT_SP_SLOT => "blog_page_product_sp_slot",
		PAGE_PRODUCT_ICG_SLOT => "blog_page_product_icg_slot",
		PAGE_PRODUCT_BNG_SLOT => "blog_page_product_bng_slot",
		PAGE_PRODUCT_SUPREME_MAHJONG => "blog_page_product_supreme_mahjong",
		PAGE_PRODUCT_SP_FISHING => "blog_page_product_sp_fishing",
		PAGE_PRODUCT_RTG_FISHING => "blog_page_product_rtg_fishing",
		PAGE_PRODUCT_GR_SLOT => "blog_page_product_gr_slot",
		PAGE_PRODUCT_RSG_SLOT => "blog_page_product_rsg_slot",
		PAGE_PRODUCT_BL_BOARD_GAME => "blog_page_product_bl_board_game",
		PAGE_PRODUCT_GR_BOARD_GAME => "blog_page_product_gr_board_game",
		PAGE_PRODUCT_ICG_FISHING => "blog_page_product_icg_fishing",
		PAGE_PRODUCT_GR_FISHING => "blog_page_product_gr_fishing",
		PAGE_PRODUCT_PNG_SLOT => "blog_page_product_png_slot",
		PAGE_PRODUCT_FOOTER => "blog_page_product_footer",
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_seo_page_link($type = NULL){
	$list = array(
		SEO_PAGE_HOME => SEO_PAGE_HOME_LINK,
		SEO_PAGE_SPORTSBOOK => SEO_PAGE_SPORTSBOOK_LINK,
		SEO_PAGE_ESPORTS => SEO_PAGE_ESPORTS_LINK,
		SEO_PAGE_LIVE_CASINO => SEO_PAGE_LIVE_CASINO_LINK,
		SEO_PAGE_SLOTS => SEO_PAGE_SLOTS_LINK,
		SEO_PAGE_FISHING => SEO_PAGE_FISHING_LINK,
		SEO_PAGE_ARCADE => SEO_PAGE_ARCADE_LINK,
		SEO_PAGE_BOARD_GAME => SEO_PAGE_BOARD_GAME_LINK,
		SEO_PAGE_LOTTERY => SEO_PAGE_LOTTERY_LINK,
		SEO_PAGE_POKER => SEO_PAGE_POKER_LINK,
		SEO_PAGE_PROMOTION => SEO_PAGE_PROMOTION_LINK,
		SEO_PAGE_ABOUT_US => SEO_PAGE_ABOUT_US_LINK,
		SEO_PAGE_FAQ => SEO_PAGE_FAQ_LINK,
		SEO_PAGE_CONTACT_US => SEO_PAGE_CONTACT_US_LINK,
		SEO_PAGE_TNC => SEO_PAGE_TNC_LINK,
		SEO_PAGE_RG => SEO_PAGE_RG_LINK,
		SEO_PAGE_VIP => SEO_PAGE_VIP_LINK,
		SEO_PAGE_MOVIE => SEO_PAGE_MOVIE_LINK,
		SEO_PAGE_LOGIN => SEO_PAGE_LOGIN_LINK,
		SEO_PAGE_REGISTER => SEO_PAGE_REGISTER_LINK,
		SEO_PAGE_FORGOT_PASSWORD => SEO_PAGE_FORGOT_PASSWORD_LINK,
		SEO_PAGE_PRODUCTS => SEO_PAGE_PRODUCTS_LINK,
		SEO_PAGE_PRODUCTS_CALI_BACCARAT => SEO_PAGE_PRODUCTS_CALI_BACCARAT_LINK,
		SEO_PAGE_PRODUCTS_ALLBET_BACCARAT => SEO_PAGE_PRODUCTS_ALLBET_BACCARAT_LINK,
		SEO_PAGE_PRODUCTS_SA_BACCARAT => SEO_PAGE_PRODUCTS_SA_BACCARAT_LINK,
		SEO_PAGE_PRODUCTS_DG_BACCARAT => SEO_PAGE_PRODUCTS_DG_BACCARAT_LINK,
		SEO_PAGE_PRODUCTS_WM_BACCARAT => SEO_PAGE_PRODUCTS_WM_BACCARAT_LINK,
		SEO_PAGE_PRODUCTS_CALI_SPORTS => SEO_PAGE_PRODUCTS_CALI_SPORTS_LINK,
		SEO_PAGE_PRODUCTS_SUPER_SPORTS => SEO_PAGE_PRODUCTS_SUPER_SPORTS_LINK,
		SEO_PAGE_PRODUCTS_9K_LOTTERY => SEO_PAGE_PRODUCTS_9K_LOTTERY_LINK,
		SEO_PAGE_PRODUCTS_SUPER_LOTTERY => SEO_PAGE_PRODUCTS_SUPER_LOTTERY_LINK,
		SEO_PAGE_PRODUCTS_RTG_ELECTRONICS => SEO_PAGE_PRODUCTS_RTG_ELECTRONICS_LINK,
		SEO_PAGE_PRODUCTS_DREAMTECH_ELECTRONICS => SEO_PAGE_PRODUCTS_DREAMTECH_ELECTRONICS_LINK,
		SEO_PAGE_PRODUCTS_SIMPLEPLAY_ELECTRONICS => SEO_PAGE_PRODUCTS_SIMPLEPLAY_ELECTRONICS_LINK,
		SEO_PAGE_PRODUCTS_BWIN_ELECTRONICS => SEO_PAGE_PRODUCTS_BWIN_ELECTRONICS_LINK,
		SEO_PAGE_PRODUCTS_BNG => SEO_PAGE_PRODUCTS_BNG_LINK,
		SEO_PAGE_PRODUCTS_OG => SEO_PAGE_PRODUCTS_OG_LINK,
		SEO_PAGE_PRODUCTS_SUPREME_GAMING => SEO_PAGE_PRODUCTS_SUPREME_GAMING_LINK,
		SEO_PAGE_PRODUCTS_RTG_FISH => SEO_PAGE_PRODUCTS_RTG_FISH_LINK,
		SEO_PAGE_PRODUCTS_SIMPLEPLAY_FISH => SEO_PAGE_PRODUCTS_SIMPLEPLAY_FISH_LINK,
		SEO_PAGE_PRODUCTS_OB_BACCARAT => SEO_PAGE_PRODUCTS_OB_BACCARAT_LINK,
		SEO_PAGE_BLOG => SEO_PAGE_BLOG_LINK,
		SEO_PAGE_USER_EVALUATION => SEO_PAGE_USER_EVALUATION_LINK,
		SEO_PAGE_ACCESS_PROCESS => SEO_PAGE_ACCESS_PROCESS_LINK,
		SEO_PAGE_APP => SEO_PAGE_APP_LINK,
		SEO_PAGE_FRANCHISE => SEO_PAGE_FRANCHISE_LINK,
		SEO_PAGE_NEWS => SEO_PAGE_NEWS_LINK,
		SEO_PAGE_GAME_MAINTENANCE => SEO_PAGE_GAME_MAINTENANCE_LINK,
		SEO_PAGE_MESSAGE => SEO_PAGE_MESSAGE_LINK,
		SEO_PAGE_ACCOUNT_TURNOVER => SEO_PAGE_ACCOUNT_TURNOVER_LINK,
		SEO_PAGE_ACCOUNT_TRANSACTION_HISTORY => SEO_PAGE_ACCOUNT_TRANSACTION_HISTORY_LINK,
		SEO_PAGE_ACCOUNT_CHANGE_PASSWORD => SEO_PAGE_ACCOUNT_CHANGE_PASSWORD_LINK,
		SEO_PAGE_ACCOUNT => SEO_PAGE_ACCOUNT_LINK,
		SEO_PAGE_DEPOSIT => SEO_PAGE_DEPOSIT_LINK,
		SEO_PAGE_WITHDRAWAL => SEO_PAGE_WITHDRAWAL_LINK,
		SEO_PAGE_BINDING_BANK => SEO_PAGE_BINDING_BANK_LINK,
		SEO_PAGE_TRANSFER => SEO_PAGE_TRANSFER_LINK,
		SEO_PAGE_BLOGS => SEO_PAGE_BLOGS_LINK,
		SEO_PAGE_PRODUCTS_GR => SEO_PAGE_PRODUCTS_GR_LINK,
		SEO_PAGE_PRODUCTS_RSG => SEO_PAGE_PRODUCTS_RSG_LINK,
		SEO_PAGE_PRODUCTS_BL => SEO_PAGE_PRODUCTS_BL_LINK,
		SEO_PAGE_PRODUCTS_GR_BOARD_GAME => SEO_PAGE_PRODUCTS_GR_BOARD_GAME_LINK,
		SEO_PAGE_PRODUCTS_ICG_FISH => SEO_PAGE_PRODUCTS_ICG_FISH_LINK,
		SEO_PAGE_PRODUCTS_GR_FISH => SEO_PAGE_PRODUCTS_GR_FISH_LINK,
		SEO_PAGE_PRODUCTS_PNG => SEO_PAGE_PRODUCTS_PNG,
	);
	if( ! empty($type))
	{
		return $list[$type];
	}	
	else
	{
		return $list;
	}
}
function get_payment_gateway_code($type = NULL){
    $list = array(
		'GSPAY' => 'payment_gateway_code_gspay',
		'PAYFLOW' => 'payment_gateway_code_payflow',
		'FUZEPAY' => 'payment_gateway_code_fuzepay',
		'XINWANG' => 'payment_gateway_code_xinwang',
		'COOLPAY' => 'payment_gateway_code_coolpay',
		'PAYCHAIN' => 'payment_gateway_code_paychain',
		'FASTSPAY' => 'payment_gateway_code_fastspay',
		'PAYLAH88' => 'payment_gateway_code_paylah88',
		'EEZIEPAY' => 'payment_gateway_code_eeziepay',
		'HUANYU' => 'payment_gateway_code_huan_yu',
		'MYCASH' => 'payment_gateway_code_mycash',
	);
	if( ! empty($type))
	{
	    if(isset($list[$type])){
	        return $list[$type];    
	    }else{
	        return 'deposit_online_banking';
	    }
	}	
	else
	{
		return $list;
	}
}
function get_payment_gateway_code_by_channel($channel = NULL,$type = NULL){
	if($channel == DEPOSIT_ONLINE_BANKING){
		$list = array(
			'GSPAY' => 'payment_gateway_code_gspay',
			'PAYFLOW' => 'payment_gateway_code_payflow',
			'FUZEPAY' => 'payment_gateway_code_fuzepay',
			'XINWANG' => 'payment_gateway_code_xinwang',
			'COOLPAY' => 'payment_gateway_code_coolpay',
			'PAYCHAIN' => 'payment_gateway_code_paychain',
			'FASTSPAY' => 'payment_gateway_code_fastspay',
			'PAYLAH88' => 'payment_gateway_code_paylah88',
			'EEZIEPAY' => 'payment_gateway_code_eeziepay',
			'HUANYU' => 'payment_gateway_code_huan_yu',
			'MYCASH' => 'payment_gateway_code_mycash',
		);
	}else if($channel == DEPOSIT_CREDIT_CARD){
		$list = array(
			'GSPAY' => 'payment_gateway_code_gspay',
			'PAYFLOW' => 'payment_gateway_code_payflow',
			'FUZEPAY' => 'payment_gateway_code_fuzepay',
			'XINWANG' => 'payment_gateway_code_xinwang',
			'COOLPAY' => 'payment_gateway_code_coolpay',
			'PAYCHAIN' => 'payment_gateway_code_paychain',
			'FASTSPAY' => 'payment_gateway_code_fastspay',
			'EEZIEPAY' => 'payment_gateway_code_eeziepay',
			'HUANYU' => 'payment_gateway_code_huan_yu',
			'MYCASH' => 'payment_gateway_code_mycash',
		);
	}else if($channel == DEPOSIT_HYPERMART){
		$list = array(
			'GSPAY' => 'payment_gateway_code_gspay',
			'PAYFLOW' => 'payment_gateway_code_payflow',
			'FUZEPAY' => 'payment_gateway_code_fuzepay',
			'XINWANG' => 'payment_gateway_code_xinwang',
			'COOLPAY' => 'payment_gateway_code_coolpay',
			'PAYCHAIN' => 'payment_gateway_code_paychain',
			'FASTSPAY' => 'payment_gateway_code_fastspay',
			'PAYLAH88' => 'payment_gateway_code_paylah88',
			'HUANYU' => 'payment_gateway_code_huan_yu',
			'MYCASH' => 'payment_gateway_code_mycash',
		);
	}else{
		$list = array(
		);
	}
	if( ! empty($type))
	{
	    if(isset($list[$type])){
	        return $list[$type];    
	    }else{
	        return 'deposit_online_banking';
	    }
	}	
	else
	{
		return $list;
	}
}
function get_payment_gateway_code_bank_withdrawal_verify($type = NULL){
	$list = array(
		'GSPAY' => 'payment_gateway_code_gspay',
		'FUZEPAY' => 'payment_gateway_code_fuzepay',
	);
	if( ! empty($type))
	{
	    if(isset($list[$type])){
	        return $list[$type];    
	    }else{
	        return 'deposit_online_banking';
	    }
	}	
	else
	{
		return $list;
	}
}

function sub_game_image_location($type = NULL){
	$list = array(
		GAME_SPORTSBOOK 	=> "sb",
		GAME_LIVE_CASINO 	=> "lc",
		GAME_SLOTS 			=> "slots",
		GAME_FISHING 		=> "fishing",
		GAME_ESPORTS 		=> "es",
		GAME_BOARD_GAME 	=> "board",
		GAME_LOTTERY 		=> "lt",
		GAME_KENO 			=> "kn",
		GAME_VIRTUAL_SPORTS => "vs",
		GAME_POKER 			=> "pk",
		GAME_COCKFIGHTING 	=> "cf",
		GAME_OTHERS 		=> "others"
	);
	if( ! empty($type)) {
		return $list[$type];
	}	
	else {
		return $list;
	}
}