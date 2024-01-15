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