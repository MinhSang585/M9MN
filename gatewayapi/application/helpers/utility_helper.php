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
function get_system_message_content($message = NULL, $arr = NULL){
	if(!empty($arr) && sizeof($arr)>0){
		foreach($arr as $key => $value){
			$message = str_replace($key,$value,$message);
		}
	}
	return $message;
}

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