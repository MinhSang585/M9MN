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
 * Select language id
 *
 * @access	public
 * @return	string
 */	
function get_language_id($type = NULL)
{
	$obj =& get_instance();
	
	$code = 'en';
	
	$selection = $obj->session->userdata('lang');
	switch($selection)
	{
		case 'chinese_simplified': $code = LANG_ZH_CN; break;
		case 'chinese_traditional': $code = LANG_ZH_HK; break;
		case 'indonesian': $code = LANG_ID; break;
		case 'thai': $code = LANG_TH; break;
		case 'vietnamese': $code = LANG_VI; break;
		case 'khmer': $code = LANG_KM; break;
		case 'myanmar': $code = LANG_MY; break;
		case 'malay': $code = LANG_MS; break;
		case 'japanese': $code = LANG_JA; break;
		case 'korean': $code = LANG_KO; break;
		case 'bengali': $code = LANG_BN; break;
		case 'hindi': $code = LANG_HI; break;
		case 'lao': $code = LANG_LO; break;
		case 'turkish': $code = LANG_TR; break;
		default: $code = LANG_EN; break;
	}
	
	return $code;
}

// ------------------------------------------------------------------------

/**
 * Select language
 *
 * @access	public
 * @return	string
 */	
function get_language($id = NULL)
{
	$lang = '';
	
	switch($id)
	{
		case LANG_ZH_CN: $lang = 'chinese_simplified'; break;
		case LANG_ZH_HK: $lang = 'chinese_traditional'; break;
		case LANG_ZH_TW: $lang = 'chinese_traditional'; break;
		case LANG_ID: $lang = 'indonesian'; break;
		case LANG_TH: $lang = 'thai'; break;
		case LANG_VI: $lang = 'vietnamese'; break;
		case LANG_KM: $lang = 'khmer'; break;
		case LANG_MY: $lang = 'myanmar'; break;
		case LANG_MS: $lang = 'malay'; break;
		case LANG_JA: $lang = 'japanese'; break;
		case LANG_KO: $lang = 'korean'; break;
		case LANG_BN: $lang = 'bengali'; break;
		case LANG_HI: $lang = 'hindi'; break;
		case LANG_LO: $lang = 'lao'; break;
		case LANG_TR: $lang = 'turkish'; break;
		default: $lang = 'english'; break;
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

// ------------------------------------------------------------------------

/**
 * Get language folder
 *
 * @access	public
 * @return	string
 */	
function get_language_folder()
{
	$obj =& get_instance();
	
	$folder = 'english';
	
	$selection = $obj->session->userdata('lang');
	switch($selection)
	{
		case 'chinese_simplified':
		case 'chinese_traditional': 
			$folder = 'simplified'; 
			break;
		default: 
			$folder = 'english';
	}
	
	return $folder;
}
// ------------------------------------------------------------------------

/**
 * Get contact list
 *
 * @access	public
 * @return	string
 */	
function get_contact_list()
{
	$obj =& get_instance();
	
	$arr = array();
	$data = $obj->contact_model->get_contact_list();
	foreach($data as $k => $v)
	{
		$arr[$v['contact_id']] = array('name' => $obj->lang->line($v['im_name']), 'value' => $v['im_value']);
	}
	
	return $arr;
}
// ------------------------------------------------------------------------

/**
 * Get mask emial
 *
 * @access	public
 * @return	string
 */	
function mask_email($email, $char_shown_front = 1, $char_shown_back = 1)
{
    $mail_parts = explode('@', $email);
    $username = $mail_parts[0];
    $len = strlen($username);

    if ($len < $char_shown_front or $len < $char_shown_back) {
        return implode('@', $mail_parts);
    }

    //Logic: show asterisk in middle, but also show the last character before @
    $mail_parts[0] = substr($username, 0, $char_shown_front)
        . str_repeat('*', $len - $char_shown_front - $char_shown_back)
        . substr($username, $len - $char_shown_back, $char_shown_back);

    return implode('@', $mail_parts);
}


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
		GAME_OTHERS => 'game_type_ot'
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
 * Load Platform
 *
 * @access	public
 * @return	string
 */	
function get_platform()
{
	$obj =& get_instance();
	$obj->load->library('session');
	
	if($obj->session->userdata('platform')) {
		$platform = $obj->session->userdata('platform');
	}
	else {
		$agt =& get_instance();
		$agt->load->library('user_agent');
		if($agt->agent->is_mobile()) {
			$obj->session->set_userdata('platform', 'mobile');
			$platform = 'mobile';
		}
		else {
			$obj->session->set_userdata('platform', 'desktop');
			$platform = 'desktop';
		}
	}
	return $platform;
}

function val_decimal($val=null,$decimal=null) {	
	return bcdiv($val,1,$decimal);
}

function payment_gateway_code($payment_gateway = NULL, $bank_code = NULL){
    $payment_gateway_code = "";
    if($payment_gateway == "TRUEPAY"){
		switch($bank_code) {
			case "MBB": $payment_gateway_code = "Maybank"; break;
			case "CIMB": $payment_gateway_code = "CIMB Bank"; break;
			case "RHB": $payment_gateway_code = "RHB Bank"; break;
			case "PBB": $payment_gateway_code = "Public Bank"; break;
			case "HLB": $payment_gateway_code = "Hong Leong Bank"; break;
			case "AFFIN": $payment_gateway_code = "Affin Bank"; break;
			case "ALLIANCE": $payment_gateway_code = "Alliance Bank Malaysia"; break;
			case "AMBANK": $payment_gateway_code = "AmBank Group"; break;
			case "ISLAM": $payment_gateway_code = "Bank Islam"; break;
			case "BSN": $payment_gateway_code = "BSN"; break;
			case "CITIM": $payment_gateway_code = "Citibank (Malaysia)"; break;
			case "HSBCM": $payment_gateway_code = "HSBC Bank (Malaysia)"; break;
			case "OCBCM": $payment_gateway_code = "OCBC Bank (Malaysia)"; break;
			case "UOBM": $payment_gateway_code = "United Overseas Bank (Malaysia)"; break;
			case "STCM": $payment_gateway_code = "Standard Chartered Bank (Malaysia)"; break;
			case "AGRO": $payment_gateway_code = "AGRO Bank"; break;
			case "RAKYAT": $payment_gateway_code = "Bank Rakyat"; break;
			case "TNGQR": $payment_gateway_code = "TnG QR"; break;
			case "DUITNOW": $payment_gateway_code = "DuitNow QR"; break;
			case "ACB": $payment_gateway_code = "Asia Commercial Bank"; break;
			case "SAC": $payment_gateway_code = "Sacom Bank"; break;
			case "TCB": $payment_gateway_code = "Techcom Bank"; break;
			case "VCB": $payment_gateway_code = "Vietcom Bank"; break;
			case "VTB": $payment_gateway_code = "Vietin Bank"; break;
			case "DAB": $payment_gateway_code = "DongA Bank"; break;
			case "BIDV": $payment_gateway_code = "BIDV Bank"; break;
			case "EXIM": $payment_gateway_code = "EXIM Bank"; break;
			case "AGB": $payment_gateway_code = "Agribank"; break;
			case "TPB": $payment_gateway_code = "TPBank"; break;
			case "MB": $payment_gateway_code = "MBBank"; break;
			case "VPB": $payment_gateway_code = "VPBank"; break;
			case "OCB": $payment_gateway_code = "Orient Commercial Joint Stock Bank"; break;
			case "SCB": $payment_gateway_code = "Sai Gon Joint Stock Commercial Bank"; break;
			case "SHB": $payment_gateway_code = "Hanoi Commercial Joint Stock Bank"; break;
			case "ABB": $payment_gateway_code = "An Binh Commercial Joint Stock Bank"; break;
			case "NASB": $payment_gateway_code = "BAC A Bank"; break;
			case "KLB": $payment_gateway_code = "Kien Long Bank"; break;
			case "BVB": $payment_gateway_code = "BaoViet Bank"; break;
			case "HDB": $payment_gateway_code = "HDBank"; break;
			case "LPB": $payment_gateway_code = "LienViet Post Bank"; break;
			case "MSB": $payment_gateway_code = "Maritime Bank"; break;
			case "NAMA": $payment_gateway_code = "Nam A Bank"; break;
			case "NCB": $payment_gateway_code = "National Citizen Bank"; break;
			case "OCEAN": $payment_gateway_code = "OCEAN Bank"; break;
			case "PVCOM": $payment_gateway_code = "PVcomBank"; break;
			case "SAIGON": $payment_gateway_code = "SaigonBank"; break;
			case "SEA": $payment_gateway_code = "SeABank"; break;
			case "SHBVN": $payment_gateway_code = "Shinhan Bank"; break;
			case "VIB": $payment_gateway_code = "Vietnam International Commercial Joint Stock Bank"; break;
			case "VRB": $payment_gateway_code = "Vietnam-Russia Bank"; break;
			case "VAB": $payment_gateway_code = "Viet A Bank"; break;
			case "MOQR": $payment_gateway_code = "MoMo eWallet"; break;
			case "VCBQR": $payment_gateway_code = "Vietcom Bank QR"; break;
			case "BIDVQR": $payment_gateway_code = "BIDV Bank QR"; break;
			case "VTBQR": $payment_gateway_code = "Vietin Bank QR"; break;
			case "AGBQR": $payment_gateway_code = "AgriBank QR"; break;
			case "ACBQR": $payment_gateway_code = "Asia Commercial Bank QR"; break;
			case "VPBQR": $payment_gateway_code = "VPBank QR"; break;
			case "TPBQR": $payment_gateway_code = "TPBank QR"; break;
			case "MBVQR": $payment_gateway_code = "MBBank QR"; break;
			case "VIBQR": $payment_gateway_code = "Vietnam International Commercial Joint Stock Bank QR"; break;
			case "SINGAPORE": $payment_gateway_code = "SINGAPORE"; break;
			case "CIMBS": $payment_gateway_code = "CIMB Bank"; break;
			case "MBBS": $payment_gateway_code = "Maybank"; break;
			case "UOBS": $payment_gateway_code = "UOB"; break;
			case "CITIS": $payment_gateway_code = "Citibank"; break;
			case "DBS": $payment_gateway_code = "DBS"; break;
			case "HSBCS": $payment_gateway_code = "HSBC"; break;
			case "OCBCS": $payment_gateway_code = "OCBC"; break;
			case "POSB": $payment_gateway_code = "POSB"; break;
			case "PAYNOW": $payment_gateway_code = "PAYNOW"; break;
			case "PAYNOWQR": $payment_gateway_code = "PAYNOW QR"; break;
			default: $payment_gateway_code = ""; break;
		}
	}
	else{
        $payment_gateway_code = "";
    }
    return $payment_gateway_code;
}
// ------------------------------------------------------------------------
