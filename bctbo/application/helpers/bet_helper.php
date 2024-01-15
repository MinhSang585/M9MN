<?php
defined('BASEPATH') OR exit('No direct script access allowed');
function game_code_decision($game_provider_code = NULL,$game_type_code = NULL,$result_info = NULL) {
	/*switch($game_provider_code) {
		case "PGS2": (!empty($result_info) ? $result = pgs_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "AB": (!empty($result_info) ? $result = ab_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "BL": (!empty($result_info) ? $result = bl_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "BNG": (!empty($result_info) ? $result = bng_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "DG": (!empty($result_info) ? $result = dg_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "DT": (!empty($result_info) ? $result = dt_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "EVOP": (!empty($result_info) ? $result = evop_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "GFGD": (!empty($result_info) ? $result = gfgd_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "GR": (!empty($result_info) ? $result = gr_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "ICG": (!empty($result_info) ? $result = icg_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "NAGA": (!empty($result_info) ? $result = naga_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "NK": (!empty($result_info) ? $result = nk_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "OBSB": (!empty($result_info) ? $result = obsb_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "OG": (!empty($result_info) ? $result = og_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "PNG": (!empty($result_info) ? $result = png_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "RTG": (!empty($result_info) ? $result = rtg_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "RSG": (!empty($result_info) ? $result = rsg_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "SA": (!empty($result_info) ? $result = sa_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "SP": (!empty($result_info) ? $result = sp_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "SPLT": (!empty($result_info) ? $result = splt_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "SPSB": (!empty($result_info) ? $result = spsb_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "WM": (!empty($result_info) ? $result = wm_game_code_decision($game_type_code,$result_info) : $result = "-");break;
		default:$result = $result_info;break;
	}
	*/
	$result = sub_game_code_decision($game_provider_code,$game_type_code,$result_info);
	return $result;
}
function bet_code_decision($game_provider_code = NULL,$game_type_code = NULL,$result_info = NULL)
{
	switch($game_provider_code)
	{
		case "AB": (!empty($result_info) ? $result = ab_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "BL": (!empty($result_info) ? $result = bl_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "BNG": (!empty($result_info) ? $result = bng_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "DG":(!empty($result_info) ? $result = dg_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "DT":(!empty($result_info) ? $result = dt_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "EVOP": (!empty($result_info) ? $result = evop_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "GFGD": (!empty($result_info) ? $result = gfgd_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "GR": (!empty($result_info) ? $result = gr_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "ICG":(!empty($result_info) ? $result = icg_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "NAGA": (!empty($result_info) ? $result = naga_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "NK": (!empty($result_info) ? $result = nk_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "OBSB": (!empty($result_info) ? $result = obsb_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "PNG": (!empty($result_info) ? $result = png_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "RTG": (!empty($result_info) ? $result = rtg_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "RSG": (!empty($result_info) ? $result = rsg_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "SA": (!empty($result_info) ? $result = sa_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "SP": (!empty($result_info) ? $result = sp_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "SPLT": (!empty($result_info) ? $result = splt_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "SPSB": (!empty($result_info) ? $result = spsb_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		case "WM": (!empty($result_info) ? $result = wm_bet_code_decision($game_type_code,$result_info) : $result = "-");break;
		default:$result = "-";break;
	}
	return $result;
}
function game_result_decision($game_provider_code = NULL,$game_type_code = NULL,$result_info = NULL)
{
	switch($game_provider_code)
	{
		case "AB": (!empty($result_info) ? $result = ab_game_result_decision($game_type_code,$result_info) : $result = "-");break;
		case "DG": (!empty($result_info) ? $result = dg_game_result_decision($game_type_code,$result_info) : $result = "-");break;
		case "GR": (!empty($result_info) ? $result = gr_game_result_decision($game_type_code,$result_info) : $result = "-");break;
		case "NK": (!empty($result_info) ? $result = nk_game_result_decision($game_type_code,$result_info) : $result = "-");break;
		case "OBSB": (!empty($result_info) ? $result = obsb_game_result_decision($game_type_code,$result_info) : $result = "-");break;
		case "SA": (!empty($result_info) ? $result = sa_game_result_decision($game_type_code,$result_info) : $result = "-");break;
		case "SPLT": (!empty($result_info) ? $result = splt_game_result_decision($game_type_code,$result_info) : $result = "-");break;
		case "SPSB": (!empty($result_info) ? $result = spsb_game_result_decision($game_type_code,$result_info) : $result = "-");break;
		case "WM": (!empty($result_info) ? $result = wm_game_result_decision($game_type_code,$result_info) : $result = "-");break;
		default:$result = "-";break;
	}
	return $result;
}
function ab_game_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_real_code = (isset($result_info_array['gameType']) ? $result_info_array['gameType'] : "");
	switch($game_real_code)
	{
		case 101: $result_code = $obj->lang->line('all_game_code_normal_baccarat'); break;
		case 102: $result_code = $obj->lang->line('all_game_code_vip_baccarat'); break;
		case 103: $result_code = $obj->lang->line('all_game_code_quick_baccarat'); break;
		case 104: $result_code = $obj->lang->line('all_game_code_show_baccarat'); break;
		case 110: $result_code = $obj->lang->line('all_game_code_insurance_baccarat'); break;
		case 201: $result_code = $obj->lang->line('all_game_code_sicbo_hilo'); break;
		case 301: $result_code = $obj->lang->line('all_game_code_dragon_tiger'); break;
		case 401: $result_code = $obj->lang->line('all_game_code_roulette'); break;
		case 501: $result_code = $obj->lang->line('all_game_code_pokdeng'); break;
		case 601: $result_code = $obj->lang->line('all_game_code_rock_paper_scissors'); break;
		case 801: $result_code = $obj->lang->line('all_game_code_bull_bull'); break;
		case 901: $result_code = $obj->lang->line('all_game_code_three_card')." / ".$obj->lang->line('all_game_code_sam_gong'); break;
		default: $result_code = "-"; break;
	}
	return $result_code;
}
function ab_bet_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_real_code = (isset($result_info_array['gameType']) ? $result_info_array['gameType'] : "");
	$bet_code = (isset($result_info_array['betType']) ? $result_info_array['betType'] : "");
	switch($game_real_code)
	{
		case 101:
		case 102:
		case 103:
		case 104:
		case 110:
			switch($bet_code){
					case 1001: $result = $obj->lang->line('all_bet_code_banker'); break;
					case 1002: $result = $obj->lang->line('all_bet_code_player'); break;
					case 1003: $result = $obj->lang->line('all_bet_code_tie'); break;
					case 1006: $result = $obj->lang->line('all_bet_code_banker_pair'); break;
					case 1007: $result = $obj->lang->line('all_bet_code_player_pair'); break;
					case 1100: $result = $obj->lang->line('all_bet_code_lucky_six'); break;
					case 1211: $result = $obj->lang->line('all_bet_code_banker_natural'); break;
					case 1212: $result = $obj->lang->line('all_bet_code_player_natural'); break;
					case 1223: $result = $obj->lang->line('all_bet_code_any_pair'); break;
					case 1224: $result = $obj->lang->line('all_bet_code_perfect_pair'); break;
					case 1231: $result = $obj->lang->line('all_bet_code_banker_dragon_bonus'); break;
					case 1232: $result = $obj->lang->line('all_bet_code_player_dragon_bonus'); break;
					case 1301: $result = $obj->lang->line('all_bet_code_commission_banker_first'); break;
					case 1302: $result = $obj->lang->line('all_bet_code_commission_banker_second'); break;
					case 1303: $result = $obj->lang->line('all_bet_code_commission_player_first'); break;
					case 1304: $result = $obj->lang->line('all_bet_code_commission_player_second'); break;
					case 1401: $result = $obj->lang->line('all_bet_code_tiger'); break;
					case 1402: $result = $obj->lang->line('all_bet_code_tiger_small'); break;
					case 1403: $result = $obj->lang->line('all_bet_code_tiger_big'); break;
					case 1404: $result = $obj->lang->line('all_bet_code_tiger_pair'); break;
					case 1405: $result = $obj->lang->line('all_bet_code_tiger_tie'); break;
					case 1501: $result = $obj->lang->line('all_bet_code_banker_fabulous_four'); break;
					case 1502: $result = $obj->lang->line('all_bet_code_player_fabulous_four'); break;
					case 1503: $result = $obj->lang->line('all_bet_code_banker_precious_pair'); break;
					case 1504: $result = $obj->lang->line('all_bet_code_player_precious_pair'); break;
					case 1601: $result = $obj->lang->line('all_bet_code_banker_black'); break;
					case 1602: $result = $obj->lang->line('all_bet_code_banker_red'); break;
					case 1603: $result = $obj->lang->line('all_bet_code_player_black'); break;
					case 1604: $result = $obj->lang->line('all_bet_code_player_red'); break;
					case 1605: $result = $obj->lang->line('all_bet_code_any_six'); break;
					default: $result = "-"; break;
				}break;
		case 201:
			switch($bet_code){
					case 3001: $result = $obj->lang->line('all_bet_code_small'); break;
					case 3002: $result = $obj->lang->line('all_bet_code_odd'); break;
					case 3003: $result = $obj->lang->line('all_bet_code_even'); break;
					case 3004: $result = $obj->lang->line('all_bet_code_big'); break;
					case 3005: $result = $obj->lang->line('all_bet_code_specific_triples'). " : 1"; break;
					case 3006: $result = $obj->lang->line('all_bet_code_specific_triples'). " : 2"; break;
					case 3007: $result = $obj->lang->line('all_bet_code_specific_triples'). " : 3"; break;
					case 3008: $result = $obj->lang->line('all_bet_code_specific_triples'). " : 4"; break;
					case 3009: $result = $obj->lang->line('all_bet_code_specific_triples'). " : 5"; break;
					case 3010: $result = $obj->lang->line('all_bet_code_specific_triples'). " : 6"; break;
					case 3011: $result = $obj->lang->line('all_bet_code_any_specific_triples'); break;
					case 3012: $result = $obj->lang->line('all_bet_code_double_dice'). " : 1"; break;
					case 3013: $result = $obj->lang->line('all_bet_code_double_dice'). " : 2"; break;
					case 3014: $result = $obj->lang->line('all_bet_code_double_dice'). " : 3"; break;
					case 3015: $result = $obj->lang->line('all_bet_code_double_dice'). " : 4"; break;
					case 3016: $result = $obj->lang->line('all_bet_code_double_dice'). " : 5"; break;
					case 3017: $result = $obj->lang->line('all_bet_code_double_dice'). " : 6"; break;
					case 3018: $result = $obj->lang->line('all_bet_code_sum_number')." : 4"; break;
					case 3019: $result = $obj->lang->line('all_bet_code_sum_number')." : 5"; break;
					case 3020: $result = $obj->lang->line('all_bet_code_sum_number')." : 6"; break;
					case 3021: $result = $obj->lang->line('all_bet_code_sum_number')." : 7"; break;
					case 3022: $result = $obj->lang->line('all_bet_code_sum_number')." : 8"; break;
					case 3023: $result = $obj->lang->line('all_bet_code_sum_number')." : 9"; break;
					case 3024: $result = $obj->lang->line('all_bet_code_sum_number')." : 10"; break;
					case 3025: $result = $obj->lang->line('all_bet_code_sum_number')." : 11"; break;
					case 3026: $result = $obj->lang->line('all_bet_code_sum_number')." : 12"; break;
					case 3027: $result = $obj->lang->line('all_bet_code_sum_number')." : 13"; break;
					case 3028: $result = $obj->lang->line('all_bet_code_sum_number')." : 14"; break;
					case 3029: $result = $obj->lang->line('all_bet_code_sum_number')." : 15"; break;
					case 3030: $result = $obj->lang->line('all_bet_code_sum_number')." : 16"; break;
					case 3031: $result = $obj->lang->line('all_bet_code_sum_number')." : 17"; break;
					case 3033: $result = $obj->lang->line('all_bet_code_specific_double')." : 1, 2"; break;
					case 3034: $result = $obj->lang->line('all_bet_code_specific_double')." : 1, 3"; break;
					case 3035: $result = $obj->lang->line('all_bet_code_specific_double')." : 1, 4"; break;
					case 3036: $result = $obj->lang->line('all_bet_code_specific_double')." : 1, 5"; break;
					case 3037: $result = $obj->lang->line('all_bet_code_specific_double')." : 1, 6"; break;
					case 3038: $result = $obj->lang->line('all_bet_code_specific_double')." : 2, 3"; break;
					case 3039: $result = $obj->lang->line('all_bet_code_specific_double')." : 2, 4"; break;
					case 3040: $result = $obj->lang->line('all_bet_code_specific_double')." : 2, 5"; break;
					case 3041: $result = $obj->lang->line('all_bet_code_specific_double')." : 2, 6"; break;
					case 3042: $result = $obj->lang->line('all_bet_code_specific_double')." : 3, 4"; break;
					case 3043: $result = $obj->lang->line('all_bet_code_specific_double')." : 3, 5"; break;
					case 3044: $result = $obj->lang->line('all_bet_code_specific_double')." : 3, 6"; break;
					case 3045: $result = $obj->lang->line('all_bet_code_specific_double')." : 4, 5"; break;
					case 3046: $result = $obj->lang->line('all_bet_code_specific_double')." : 4, 6"; break;
					case 3047: $result = $obj->lang->line('all_bet_code_specific_double')." : 5, 6"; break;
					case 3048: $result = $obj->lang->line('all_bet_code_one_dice')." : 1"; break;
					case 3049: $result = $obj->lang->line('all_bet_code_one_dice')." : 2"; break;
					case 3050: $result = $obj->lang->line('all_bet_code_one_dice')." : 3"; break;
					case 3051: $result = $obj->lang->line('all_bet_code_one_dice')." : 4"; break;
					case 3052: $result = $obj->lang->line('all_bet_code_one_dice')." : 5"; break;
					case 3053: $result = $obj->lang->line('all_bet_code_one_dice')." : 6"; break;
					case 3200: $result = $obj->lang->line('all_bet_code_hi'); break;
					case 3201: $result = $obj->lang->line('all_bet_code_lo'); break;
					case 3202: $result = $obj->lang->line('all_bet_eleven_hi_lo'); break;
					case 3203: $result = $obj->lang->line('all_bet_code_dices')." 1"; break;
					case 3204: $result = $obj->lang->line('all_bet_code_dices')." 2"; break;
					case 3205: $result = $obj->lang->line('all_bet_code_dices')." 3"; break;
					case 3206: $result = $obj->lang->line('all_bet_code_dices')." 4"; break;
					case 3207: $result = $obj->lang->line('all_bet_code_dices')." 5"; break;
					case 3208: $result = $obj->lang->line('all_bet_code_dices')." 6"; break;
					case 3209: $result = "1-2"; break;
					case 3210: $result = "1-3"; break;
					case 3211: $result = "1-4"; break;
					case 3212: $result = "1-5"; break;
					case 3213: $result = "1-6"; break;
					case 3214: $result = "2-3"; break;
					case 3215: $result = "2-4"; break;
					case 3216: $result = "2-5"; break;
					case 3217: $result = "2-6"; break;
					case 3218: $result = "3-4"; break;
					case 3219: $result = "3-5"; break;
					case 3220: $result = "3-6"; break;
					case 3221: $result = "4-5"; break;
					case 3222: $result = "4-6"; break;
					case 3223: $result = "5-6"; break;
					case 3224: $result = "1-".$obj->lang->line('all_bet_code_lo'); break;
					case 3225: $result = "2-".$obj->lang->line('all_bet_code_lo'); break;
					case 3226: $result = "3-".$obj->lang->line('all_bet_code_lo'); break;
					case 3227: $result = "4-".$obj->lang->line('all_bet_code_lo'); break;
					case 3228: $result = "5-".$obj->lang->line('all_bet_code_lo'); break;
					case 3229: $result = "6-".$obj->lang->line('all_bet_code_lo'); break;
					case 3230: $result = "3-".$obj->lang->line('all_bet_code_hi'); break;
					case 3231: $result = "4-".$obj->lang->line('all_bet_code_hi'); break;
					case 3232: $result = "5-".$obj->lang->line('all_bet_code_hi'); break;
					case 3233: $result = "6-".$obj->lang->line('all_bet_code_hi'); break;
					case 3234: $result = "1,2,3"; break;
					case 3235: $result = "2,3,4"; break;
					case 3236: $result = "3,4,5"; break;
					case 3237: $result = "4,5,6"; break;
					default: $result = "-"; break;
				}break;
		case 301:
			switch($bet_code){
				case 2001: $result = $obj->lang->line('all_bet_code_dragon'); break;
				case 2002: $result = $obj->lang->line('all_bet_code_tiger'); break;
				case 2003: $result = $obj->lang->line('all_bet_code_tie'); break;
				default: $result = "-"; break;
			}break;
		case 401:
			switch($bet_code){
				case 4001: $result = $obj->lang->line('all_bet_code_small'); break;
				case 4002: $result = $obj->lang->line('all_bet_code_even'); break;
				case 4003: $result = $obj->lang->line('all_bet_code_red'); break;
				case 4004: $result = $obj->lang->line('all_bet_code_black'); break;
				case 4005: $result = $obj->lang->line('all_bet_code_odd'); break;
				case 4006: $result = $obj->lang->line('all_bet_code_big'); break;
				case 4007: $result = $obj->lang->line('all_bet_code_first_row'); break;
				case 4008: $result = $obj->lang->line('all_bet_code_second_row'); break;
				case 4009: $result = $obj->lang->line('all_bet_code_third_row'); break;
				case 4010: $result = $obj->lang->line('all_bet_code_first_twelve'); break;
				case 4011: $result = $obj->lang->line('all_bet_code_second_twelve'); break;
				case 4012: $result = $obj->lang->line('all_bet_code_third_twelve'); break;
				case 4013: $result = $obj->lang->line('all_bet_code_straight_number')." : 0"; break;
				case 4014: $result = $obj->lang->line('all_bet_code_straight_number')." : 1"; break;
				case 4015: $result = $obj->lang->line('all_bet_code_straight_number')." : 2"; break;
				case 4016: $result = $obj->lang->line('all_bet_code_straight_number')." : 3"; break;
				case 4017: $result = $obj->lang->line('all_bet_code_straight_number')." : 4"; break;
				case 4018: $result = $obj->lang->line('all_bet_code_straight_number')." : 5"; break;
				case 4019: $result = $obj->lang->line('all_bet_code_straight_number')." : 6"; break;
				case 4020: $result = $obj->lang->line('all_bet_code_straight_number')." : 7"; break;
				case 4021: $result = $obj->lang->line('all_bet_code_straight_number')." : 8"; break;
				case 4022: $result = $obj->lang->line('all_bet_code_straight_number')." : 9"; break;
				case 4023: $result = $obj->lang->line('all_bet_code_straight_number')." : 10"; break;
				case 4024: $result = $obj->lang->line('all_bet_code_straight_number')." : 11"; break;
				case 4025: $result = $obj->lang->line('all_bet_code_straight_number')." : 12"; break;
				case 4026: $result = $obj->lang->line('all_bet_code_straight_number')." : 13"; break;
				case 4027: $result = $obj->lang->line('all_bet_code_straight_number')." : 14"; break;
				case 4028: $result = $obj->lang->line('all_bet_code_straight_number')." : 15"; break;
				case 4029: $result = $obj->lang->line('all_bet_code_straight_number')." : 16"; break;
				case 4030: $result = $obj->lang->line('all_bet_code_straight_number')." : 17"; break;
				case 4031: $result = $obj->lang->line('all_bet_code_straight_number')." : 18"; break;
				case 4032: $result = $obj->lang->line('all_bet_code_straight_number')." : 19"; break;
				case 4033: $result = $obj->lang->line('all_bet_code_straight_number')." : 20"; break;
				case 4034: $result = $obj->lang->line('all_bet_code_straight_number')." : 21"; break;
				case 4035: $result = $obj->lang->line('all_bet_code_straight_number')." : 22"; break;
				case 4036: $result = $obj->lang->line('all_bet_code_straight_number')." : 23"; break;
				case 4037: $result = $obj->lang->line('all_bet_code_straight_number')." : 24"; break;
				case 4038: $result = $obj->lang->line('all_bet_code_straight_number')." : 25"; break;
				case 4039: $result = $obj->lang->line('all_bet_code_straight_number')." : 26"; break;
				case 4040: $result = $obj->lang->line('all_bet_code_straight_number')." : 27"; break;
				case 4041: $result = $obj->lang->line('all_bet_code_straight_number')." : 28"; break;
				case 4042: $result = $obj->lang->line('all_bet_code_straight_number')." : 29"; break;
				case 4043: $result = $obj->lang->line('all_bet_code_straight_number')." : 30"; break;
				case 4044: $result = $obj->lang->line('all_bet_code_straight_number')." : 31"; break;
				case 4045: $result = $obj->lang->line('all_bet_code_straight_number')." : 32"; break;
				case 4046: $result = $obj->lang->line('all_bet_code_straight_number')." : 33"; break;
				case 4047: $result = $obj->lang->line('all_bet_code_straight_number')." : 34"; break;
				case 4048: $result = $obj->lang->line('all_bet_code_straight_number')." : 35"; break;
				case 4049: $result = $obj->lang->line('all_bet_code_straight_number')." : 36"; break;
				case 4050: $result = $obj->lang->line('all_bet_three_number')." 0,1,2"; break;
				case 4051: $result = $obj->lang->line('all_bet_three_number')." 0,2,3"; break;
				case 4052: $result = $obj->lang->line('all_bet_four_number')." 0,1,2,3"; break;
				case 4053: $result = $obj->lang->line('all_bet_code_separate')." 0,1"; break;
				case 4054: $result = $obj->lang->line('all_bet_code_separate')." 0,2"; break;
				case 4055: $result = $obj->lang->line('all_bet_code_separate')." 0,3"; break;
				case 4056: $result = $obj->lang->line('all_bet_code_separate')." 1,2"; break;
				case 4057: $result = $obj->lang->line('all_bet_code_separate')." 2,3"; break;
				case 4058: $result = $obj->lang->line('all_bet_code_separate')." 4,5"; break;
				case 4059: $result = $obj->lang->line('all_bet_code_separate')." 5,6"; break;
				case 4060: $result = $obj->lang->line('all_bet_code_separate')." 7,8"; break;
				case 4061: $result = $obj->lang->line('all_bet_code_separate')." 8,9"; break;
				case 4062: $result = $obj->lang->line('all_bet_code_separate')." 10,11"; break;
				case 4063: $result = $obj->lang->line('all_bet_code_separate')." 11,12"; break;
				case 4064: $result = $obj->lang->line('all_bet_code_separate')." 13,14"; break;
				case 4065: $result = $obj->lang->line('all_bet_code_separate')." 14,15"; break;
				case 4066: $result = $obj->lang->line('all_bet_code_separate')." 16,17"; break;
				case 4067: $result = $obj->lang->line('all_bet_code_separate')." 17,18"; break;
				case 4068: $result = $obj->lang->line('all_bet_code_separate')." 19,20"; break;
				case 4069: $result = $obj->lang->line('all_bet_code_separate')." 20,21"; break;
				case 4070: $result = $obj->lang->line('all_bet_code_separate')." 22,23"; break;
				case 4071: $result = $obj->lang->line('all_bet_code_separate')." 23,24"; break;
				case 4072: $result = $obj->lang->line('all_bet_code_separate')." 25,26"; break;
				case 4073: $result = $obj->lang->line('all_bet_code_separate')." 26,27"; break;
				case 4074: $result = $obj->lang->line('all_bet_code_separate')." 28,29"; break;
				case 4075: $result = $obj->lang->line('all_bet_code_separate')." 29,30"; break;
				case 4076: $result = $obj->lang->line('all_bet_code_separate')." 31,32"; break;
				case 4077: $result = $obj->lang->line('all_bet_code_separate')." 32,33"; break;
				case 4078: $result = $obj->lang->line('all_bet_code_separate')." 34,35"; break;
				case 4079: $result = $obj->lang->line('all_bet_code_separate')." 35,36"; break;
				case 4080: $result = $obj->lang->line('all_bet_code_separate')." 1,4"; break;
				case 4081: $result = $obj->lang->line('all_bet_code_separate')." 4,7"; break;
				case 4082: $result = $obj->lang->line('all_bet_code_separate')." 7,10"; break;
				case 4083: $result = $obj->lang->line('all_bet_code_separate')." 10,13"; break;
				case 4084: $result = $obj->lang->line('all_bet_code_separate')." 13,16"; break;
				case 4085: $result = $obj->lang->line('all_bet_code_separate')." 16,19"; break;
				case 4086: $result = $obj->lang->line('all_bet_code_separate')." 19,22"; break;
				case 4087: $result = $obj->lang->line('all_bet_code_separate')." 22,25"; break;
				case 4088: $result = $obj->lang->line('all_bet_code_separate')." 25,28"; break;
				case 4089: $result = $obj->lang->line('all_bet_code_separate')." 28,31"; break;
				case 4090: $result = $obj->lang->line('all_bet_code_separate')." 31,34"; break;
				case 4091: $result = $obj->lang->line('all_bet_code_separate')." 2,5"; break;
				case 4092: $result = $obj->lang->line('all_bet_code_separate')." 5,8"; break;
				case 4093: $result = $obj->lang->line('all_bet_code_separate')." 8,11"; break;
				case 4094: $result = $obj->lang->line('all_bet_code_separate')." 11,14"; break;
				case 4095: $result = $obj->lang->line('all_bet_code_separate')." 14,17"; break;
				case 4096: $result = $obj->lang->line('all_bet_code_separate')." 17,20"; break;
				case 4097: $result = $obj->lang->line('all_bet_code_separate')." 20,23"; break;
				case 4098: $result = $obj->lang->line('all_bet_code_separate')." 23,26"; break;
				case 4099: $result = $obj->lang->line('all_bet_code_separate')." 26,29"; break;
				case 4100: $result = $obj->lang->line('all_bet_code_separate')." 29,32"; break;
				case 4101: $result = $obj->lang->line('all_bet_code_separate')." 32,35"; break;
				case 4102: $result = $obj->lang->line('all_bet_code_separate')." 3,6"; break;
				case 4103: $result = $obj->lang->line('all_bet_code_separate')." 6,9"; break;
				case 4104: $result = $obj->lang->line('all_bet_code_separate')." 9,12"; break;
				case 4105: $result = $obj->lang->line('all_bet_code_separate')." 12,15"; break;
				case 4106: $result = $obj->lang->line('all_bet_code_separate')." 15,18"; break;
				case 4107: $result = $obj->lang->line('all_bet_code_separate')." 18,21"; break;
				case 4108: $result = $obj->lang->line('all_bet_code_separate')." 21,24"; break;
				case 4109: $result = $obj->lang->line('all_bet_code_separate')." 24,27"; break;
				case 4110: $result = $obj->lang->line('all_bet_code_separate')." 27,30"; break;
				case 4111: $result = $obj->lang->line('all_bet_code_separate')." 30,33"; break;
				case 4112: $result = $obj->lang->line('all_bet_code_separate')." 33,36"; break;
				case 4113: $result = $obj->lang->line('all_bet_code_angle')." 1,5"; break;
				case 4114: $result = $obj->lang->line('all_bet_code_angle')." 2,6"; break;
				case 4115: $result = $obj->lang->line('all_bet_code_angle')." 4,8"; break;
				case 4116: $result = $obj->lang->line('all_bet_code_angle')." 5,9"; break;
				case 4117: $result = $obj->lang->line('all_bet_code_angle')." 7,11"; break;
				case 4118: $result = $obj->lang->line('all_bet_code_angle')." 8,12"; break;
				case 4119: $result = $obj->lang->line('all_bet_code_angle')." 10,14"; break;
				case 4120: $result = $obj->lang->line('all_bet_code_angle')." 11,15"; break;
				case 4121: $result = $obj->lang->line('all_bet_code_angle')." 13,17"; break;
				case 4122: $result = $obj->lang->line('all_bet_code_angle')." 14,18"; break;
				case 4123: $result = $obj->lang->line('all_bet_code_angle')." 16,20"; break;
				case 4124: $result = $obj->lang->line('all_bet_code_angle')." 17,21"; break;
				case 4125: $result = $obj->lang->line('all_bet_code_angle')." 19,23"; break;
				case 4126: $result = $obj->lang->line('all_bet_code_angle')." 20,24"; break;
				case 4127: $result = $obj->lang->line('all_bet_code_angle')." 22,26"; break;
				case 4128: $result = $obj->lang->line('all_bet_code_angle')." 23,27"; break;
				case 4129: $result = $obj->lang->line('all_bet_code_angle')." 25,29"; break;
				case 4130: $result = $obj->lang->line('all_bet_code_angle')." 26,30"; break;
				case 4131: $result = $obj->lang->line('all_bet_code_angle')." 28,32"; break;
				case 4132: $result = $obj->lang->line('all_bet_code_angle')." 29,33"; break;
				case 4133: $result = $obj->lang->line('all_bet_code_angle')." 31,35"; break;
				case 4134: $result = $obj->lang->line('all_bet_code_angle')." 32,36"; break;
				case 4135: $result = $obj->lang->line('all_bet_code_street')." 1~3"; break;
				case 4136: $result = $obj->lang->line('all_bet_code_street')." 4~6"; break;
				case 4137: $result = $obj->lang->line('all_bet_code_street')." 7~9"; break;
				case 4138: $result = $obj->lang->line('all_bet_code_street')." 10,12"; break;
				case 4139: $result = $obj->lang->line('all_bet_code_street')." 13,15"; break;
				case 4140: $result = $obj->lang->line('all_bet_code_street')." 16~18"; break;
				case 4141: $result = $obj->lang->line('all_bet_code_street')." 19~21"; break;
				case 4142: $result = $obj->lang->line('all_bet_code_street')." 22~24"; break;
				case 4143: $result = $obj->lang->line('all_bet_code_street')." 25~27"; break;
				case 4144: $result = $obj->lang->line('all_bet_code_street')." 28~30"; break;
				case 4145: $result = $obj->lang->line('all_bet_code_street')." 31~33"; break;
				case 4146: $result = $obj->lang->line('all_bet_code_street')." 34~36"; break;
				case 4147: $result = $obj->lang->line('all_bet_code_line')." 1~6"; break;
				case 4148: $result = $obj->lang->line('all_bet_code_line')." 4~9"; break;
				case 4149: $result = $obj->lang->line('all_bet_code_line')." 7~12"; break;
				case 4150: $result = $obj->lang->line('all_bet_code_line')." 10~15"; break;
				case 4151: $result = $obj->lang->line('all_bet_code_line')." 13~18"; break;
				case 4152: $result = $obj->lang->line('all_bet_code_line')." 16~21"; break;
				case 4153: $result = $obj->lang->line('all_bet_code_line')." 19~24"; break;
				case 4154: $result = $obj->lang->line('all_bet_code_line')." 22~27"; break;
				case 4155: $result = $obj->lang->line('all_bet_code_line')." 25~30"; break;
				case 4156: $result = $obj->lang->line('all_bet_code_line')." 28~33"; break;
				case 4157: $result = $obj->lang->line('all_bet_code_line')." 31~36"; break;
				default: $result = "-"; break;
			}break;
		case 501:
			switch($bet_code)
			{
				case 5001: $result = $obj->lang->line('all_bet_code_player_one'); break;
				case 5002: $result = $obj->lang->line('all_bet_code_player_two'); break;
				case 5003: $result = $obj->lang->line('all_bet_code_player_three'); break;
				case 5004: $result = $obj->lang->line('all_bet_code_player_four'); break;
				case 5005: $result = $obj->lang->line('all_bet_code_player_five'); break;
				case 5011: $result = $obj->lang->line('all_bet_code_player_one_pair'); break;
				case 5012: $result = $obj->lang->line('all_bet_code_player_two_pair'); break;
				case 5013: $result = $obj->lang->line('all_bet_code_player_three_pair'); break;
				case 5014: $result = $obj->lang->line('all_bet_code_player_four_pair'); break;
				case 5015: $result = $obj->lang->line('all_bet_code_player_five_pair'); break;
				default: $result = ""; break;
			}break;
		case 601:
			switch($bet_code)
			{
				case 6001: $result = $obj->lang->line('all_game_code_gold_rock'); break;
				case 6002: $result = $obj->lang->line('all_game_code_gold_paper'); break;
				case 6003: $result = $obj->lang->line('all_game_code_gold_scissors'); break;
				case 6004: $result = $obj->lang->line('all_game_code_silver_rock'); break;
				case 6005: $result = $obj->lang->line('all_game_code_silver_paper'); break;
				case 6006: $result = $obj->lang->line('all_game_code_silver_scissors'); break;
				case 6007: $result = $obj->lang->line('all_game_code_bronze_rock'); break;
				case 6008: $result = $obj->lang->line('all_game_code_bronze_paper'); break;
				case 6009: $result = $obj->lang->line('all_game_code_bronze_scissors'); break;
				default: $result = ""; break;
			}break;
		case 801:
			switch($bet_code)
			{
				case 8001: $result = $obj->lang->line('all_bet_code_banker_one_equal'); break;
				case 8011: $result = $obj->lang->line('all_bet_code_banker_one_double'); break;
				case 8101: $result = $obj->lang->line('all_bet_code_player_one_equal'); break;
				case 8111: $result = $obj->lang->line('all_bet_code_player_one_double'); break;
				case 8002: $result = $obj->lang->line('all_bet_code_banker_two_equal'); break;
				case 8012: $result = $obj->lang->line('all_bet_code_banker_two_double'); break;
				case 8102: $result = $obj->lang->line('all_bet_code_player_two_equal'); break;
				case 8112: $result = $obj->lang->line('all_bet_code_player_two_double'); break;
				case 8003: $result = $obj->lang->line('all_bet_code_banker_three_equal'); break;
				case 8013: $result = $obj->lang->line('all_bet_code_banker_three_double'); break;
				case 8103: $result = $obj->lang->line('all_bet_code_player_three_equal'); break;
				case 8113: $result = $obj->lang->line('all_bet_code_player_three_double'); break;
				case 8021: $result = $obj->lang->line('all_bet_code_banker_one_super_bull'); break;
				case 8121: $result = $obj->lang->line('all_bet_code_player_one_super_bull'); break;
				case 8022: $result = $obj->lang->line('all_bet_code_banker_two_super_bull'); break;
				case 8122: $result = $obj->lang->line('all_bet_code_player_two_super_bull'); break;
				case 8023: $result = $obj->lang->line('all_bet_code_banker_three_super_bull'); break;
				case 8123: $result = $obj->lang->line('all_bet_code_player_three_super_bull'); break;
				default: $result = ""; break;
			}break;
		case 901:
			switch($bet_code)
			{
				case 9001: $result = $obj->lang->line('all_bet_code_dragon'); break;
				case 9002: $result = $obj->lang->line('all_bet_code_pheonix'); break;
				case 9003: $result = $obj->lang->line('all_bet_pair_eight_plus'); break;
				case 9004: $result = $obj->lang->line('all_bet_straight'); break;
				case 9005: $result = $obj->lang->line('all_bet_flush'); break;
				case 9006: $result = $obj->lang->line('all_bet_straight_flush'); break;
				case 9007: $result = $obj->lang->line('all_bet_three_face_leopard'); break;
				case 9101: $result = $obj->lang->line('all_bet_three_face_dragon'); break;
				case 9102: $result = $obj->lang->line('all_bet_three_face_pheonix'); break;
				case 9103: $result = $obj->lang->line('all_bet_code_tie'); break;
				case 9114: $result = $obj->lang->line('all_bet_dragon_three_face'); break;
				case 9124: $result = $obj->lang->line('all_bet_pheonix_three_face'); break;
				default: $result = ""; break;
			}break;
		default: $result = ""; break;
	}
	if(isset($result_info_array['tableName'])){
		if(!empty($result)){
			$result .= "<br>";
		}
		$result .= $obj->lang->line('all_result_table_id')." : ".$result_info_array['tableName'];
	}
	if(isset($result_info_array['gameRoundId'])){
		if(!empty($result)){
			$result .= "<br>";
		}
		$result .= $obj->lang->line('all_result_round')." : ".$result_info_array['gameRoundId'];
	}
	if(isset($result_info_array['betNum'])){
		if(!empty($result)){
			$result .= "<br>";
		}
		$result .= $obj->lang->line('all_result_bet_id')." : ".$result_info_array['betNum'];
	}
	return $result;
}
function ab_game_result_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_real_code = (isset($result_info_array['betType']) ? $result_info_array['gameType'] : "");
	$game_result = (isset($result_info_array['gameResult']) ? $result_info_array['gameResult'] : "");
	$result = "";
	$result_temp = "";
	if($game_real_code == 101 || $game_real_code == 102 || $game_real_code == 103 || $game_real_code == 104 || $game_real_code == 301 || $game_real_code == 901 || $game_real_code == 110){
		if(!empty($game_result)){
			$game_result_array = explode("},{",$game_result);
			if(!empty($game_result_array) && sizeof($game_result_array)==2){
				$player_array_data = explode(",",str_replace("{","",$game_result_array[0]));
				$bank_array_data = explode(",",str_replace("}","",$game_result_array[1]));
				$banker_result = "";
				$player_result = "";
				$suit = "";
				$rank = "";
				if(!empty($player_array_data)){
					foreach($player_array_data as $player_array_data_row){
						$suit = "";
						$rank = "";
						if($player_array_data_row > 0){
							switch(substr($player_array_data_row,0,1)){
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch(substr($player_array_data_row,-2)){
								case "01": $rank = "A"; break;
								case "02": $rank = "2"; break;
								case "03": $rank = "3"; break;
								case "04": $rank = "4"; break;
								case "05": $rank = "5"; break;
								case "06": $rank = "6"; break;
								case "07": $rank = "7"; break;
								case "08": $rank = "8"; break;
								case "09": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							$player_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$player_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$player_result .= '<span class="rank">'.$rank.'</span>';
									$player_result .= '<span class="suit">&'.$suit.';</span>';
								$player_result .= '</div>';
							$player_result .= '</div>';
						}
					}
				}
				if(!empty($bank_array_data)){
					foreach($bank_array_data as $bank_array_data_row){
						$suit = "";
						$rank = "";
						if($bank_array_data_row > 0){
							switch(substr($bank_array_data_row,0,1)){
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch(substr($bank_array_data_row,-2)){
								case "01": $rank = "A"; break;
								case "02": $rank = "2"; break;
								case "03": $rank = "3"; break;
								case "04": $rank = "4"; break;
								case "05": $rank = "5"; break;
								case "06": $rank = "6"; break;
								case "07": $rank = "7"; break;
								case "08": $rank = "8"; break;
								case "09": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							$banker_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$banker_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$banker_result .= '<span class="rank">'.$rank.'</span>';
									$banker_result .= '<span class="suit">&'.$suit.';</span>';
								$banker_result .= '</div>';
							$banker_result .= '</div>';
						}
					}
				}
				if($game_real_code == 301){
					$result_temp .= $obj->lang->line('all_bet_code_tiger')." : ". $banker_result;
					if(!empty($player_result)){
						if(!empty($result_temp)){
							$result_temp .= "<br/>";
						}
						$result_temp .= $obj->lang->line('all_bet_code_dragon')." : ".$player_result;
					}
				}else if($game_real_code == 901){
					$result_temp .= $obj->lang->line('all_bet_code_dragon')." : ". $banker_result;
					if(!empty($player_result)){
						if(!empty($result_temp)){
							$result_temp .= "<br/>";
						}
						$result_temp .= $obj->lang->line('all_bet_code_pheonix')." : ".$player_result;
					}
				}else{
					$result_temp .= $obj->lang->line('all_bet_code_banker')." : ". $banker_result;
					if(!empty($player_result)){
						if(!empty($result_temp)){
							$result_temp .= "<br/>";
						}
						$result_temp .= $obj->lang->line('all_bet_code_player')." : ".$player_result;
					}
				}
			}	
		}
	}else if($game_real_code == 201){
		$game_result_data = substr($game_result,1,-1);
		if(!empty($game_result_data)){
			$game_result_array = explode(",",str_replace(" ","",$game_result_data));
			if(!empty($game_result_array) && sizeof($game_result_array)>0){
				foreach($game_result_array as $game_result_array_row){
					$result_temp .= '<span class="dice dice-'.$game_result_array_row.'" title="'.$obj->lang->line('all_bet_code_dice').' : '.$game_result_array_row.'"></span>';
				}
			}
		}
	}else if($game_real_code == 401){
		$result_temp = $obj->lang->line('all_bet_code_direct')." : ".$game_result;
	}else if($game_real_code == 501){
		if(!empty($game_result)){
			$game_result_array = explode("},{",$game_result);
			if(!empty($game_result_array) && sizeof($game_result_array)==6){
				$bank_array_data = explode(",",str_replace("{","",$game_result_array[0]));
				$player_one_array_data = explode(",",str_replace("","",$game_result_array[1]));
				$player_two_array_data = explode(",",str_replace("","",$game_result_array[2]));
				$player_three_array_data = explode(",",str_replace("","",$game_result_array[3]));
				$player_four_array_data = explode(",",str_replace("","",$game_result_array[4]));
				$player_five_array_data = explode(",",str_replace("}","",$game_result_array[5]));
				$banker_result = "";
				$player_one_result = "";
				$player_two_result = "";
				$player_three_result = "";
				$player_four_result = "";
				$player_five_result = "";
				$suit = "";
				$rank = "";
				if(!empty($bank_array_data)){
					foreach($bank_array_data as $bank_array_data_row){
						$suit = "";
						$rank = "";
						if($bank_array_data_row > 0){
							switch(substr($bank_array_data_row,0,1)){
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch(substr($bank_array_data_row,-2)){
								case "01": $rank = "A"; break;
								case "02": $rank = "2"; break;
								case "03": $rank = "3"; break;
								case "04": $rank = "4"; break;
								case "05": $rank = "5"; break;
								case "06": $rank = "6"; break;
								case "07": $rank = "7"; break;
								case "08": $rank = "8"; break;
								case "09": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							$banker_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$banker_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$banker_result .= '<span class="rank">'.$rank.'</span>';
									$banker_result .= '<span class="suit">&'.$suit.';</span>';
								$banker_result .= '</div>';
							$banker_result .= '</div>';
						}
					}
				}
				if(!empty($player_one_array_data)){
					foreach($player_one_array_data as $player_one_array_data_row){
						$suit = "";
						$rank = "";
						if($player_one_array_data_row > 0){
							switch(substr($player_one_array_data_row,0,1)){
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch(substr($player_one_array_data_row,-2)){
								case "01": $rank = "A"; break;
								case "02": $rank = "2"; break;
								case "03": $rank = "3"; break;
								case "04": $rank = "4"; break;
								case "05": $rank = "5"; break;
								case "06": $rank = "6"; break;
								case "07": $rank = "7"; break;
								case "08": $rank = "8"; break;
								case "09": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							$player_one_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$player_one_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$player_one_result .= '<span class="rank">'.$rank.'</span>';
									$player_one_result .= '<span class="suit">&'.$suit.';</span>';
								$player_one_result .= '</div>';
							$player_one_result .= '</div>';
						}
					}
				}
				if(!empty($player_two_array_data)){
					foreach($player_two_array_data as $player_two_array_data_row){
						$suit = "";
						$rank = "";
						if($player_two_array_data_row > 0){
							switch(substr($player_two_array_data_row,0,1)){
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch(substr($player_two_array_data_row,-2)){
								case "01": $rank = "A"; break;
								case "02": $rank = "2"; break;
								case "03": $rank = "3"; break;
								case "04": $rank = "4"; break;
								case "05": $rank = "5"; break;
								case "06": $rank = "6"; break;
								case "07": $rank = "7"; break;
								case "08": $rank = "8"; break;
								case "09": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							$player_two_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$player_two_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$player_two_result .= '<span class="rank">'.$rank.'</span>';
									$player_two_result .= '<span class="suit">&'.$suit.';</span>';
								$player_two_result .= '</div>';
							$player_two_result .= '</div>';
						}
					}
				}
				if(!empty($player_three_array_data)){
					foreach($player_three_array_data as $player_three_array_data_row){
						$suit = "";
						$rank = "";
						if($player_three_array_data_row > 0){
							switch(substr($player_three_array_data_row,0,1)){
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch(substr($player_three_array_data_row,-2)){
								case "01": $rank = "A"; break;
								case "02": $rank = "2"; break;
								case "03": $rank = "3"; break;
								case "04": $rank = "4"; break;
								case "05": $rank = "5"; break;
								case "06": $rank = "6"; break;
								case "07": $rank = "7"; break;
								case "08": $rank = "8"; break;
								case "09": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							$player_three_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$player_three_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$player_three_result .= '<span class="rank">'.$rank.'</span>';
									$player_three_result .= '<span class="suit">&'.$suit.';</span>';
								$player_three_result .= '</div>';
							$player_three_result .= '</div>';
						}
					}
				}
				if(!empty($player_four_array_data)){
					foreach($player_four_array_data as $player_four_array_data_row){
						$suit = "";
						$rank = "";
						if($player_four_array_data_row > 0){
							switch(substr($player_four_array_data_row,0,1)){
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch(substr($player_four_array_data_row,-2)){
								case "01": $rank = "A"; break;
								case "02": $rank = "2"; break;
								case "03": $rank = "3"; break;
								case "04": $rank = "4"; break;
								case "05": $rank = "5"; break;
								case "06": $rank = "6"; break;
								case "07": $rank = "7"; break;
								case "08": $rank = "8"; break;
								case "09": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							$player_four_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$player_four_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$player_four_result .= '<span class="rank">'.$rank.'</span>';
									$player_four_result .= '<span class="suit">&'.$suit.';</span>';
								$player_four_result .= '</div>';
							$player_four_result .= '</div>';
						}
					}
				}
				if(!empty($player_five_array_data)){
					foreach($player_five_array_data as $player_five_array_data_row){
						$suit = "";
						$rank = "";
						if($player_five_array_data_row > 0){
							switch(substr($player_five_array_data_row,0,1)){
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch(substr($player_five_array_data_row,-2)){
								case "01": $rank = "A"; break;
								case "02": $rank = "2"; break;
								case "03": $rank = "3"; break;
								case "04": $rank = "4"; break;
								case "05": $rank = "5"; break;
								case "06": $rank = "6"; break;
								case "07": $rank = "7"; break;
								case "08": $rank = "8"; break;
								case "09": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							$player_five_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$player_five_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$player_five_result .= '<span class="rank">'.$rank.'</span>';
									$player_five_result .= '<span class="suit">&'.$suit.';</span>';
								$player_five_result .= '</div>';
							$player_five_result .= '</div>';
						}
					}
				}
				$result_temp .= $obj->lang->line('all_bet_code_banker')." : ". $banker_result;
				if(!empty($player_one_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $obj->lang->line('all_bet_code_player_one')." : ".$player_one_result;
				}
				if(!empty($player_two_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $obj->lang->line('all_bet_code_player_two')." : ".$player_two_result;
				}
				if(!empty($player_three_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $obj->lang->line('all_bet_code_player_three')." : ".$player_three_result;
				}
				if(!empty($player_four_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $obj->lang->line('all_bet_code_player_four')." : ".$player_four_result;
				}
				if(!empty($player_five_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $obj->lang->line('all_bet_code_player_five')." : ".$player_five_result;
				}
			}	
		}
	}else if($game_real_code == 601){
		$game_result_data = substr($game_result,1,-1);
		if(!empty($game_result_data)){
			$game_result_array = explode(",",str_replace(" ","",$game_result_data));
			if(!empty($game_result_array) && sizeof($game_result_array)>0){
				for($i=0;$i<sizeof($game_result_array);$i++){
					if($i==0){
						switch($game_result_array[$i]){
							case 1: $result_temp .= $obj->lang->line('all_game_code_gold_paper')."<br>"; break;
							case 2: $result_temp .= $obj->lang->line('all_game_code_gold_scissors')."<br>"; break;
							case 3: $result_temp .= $obj->lang->line('all_game_code_gold_rock')."<br>"; break;
							default: $result_temp .= ""; break;
						}
					}else if($i==1){
						switch($game_result_array[$i]){
							case 1: $result_temp .= $obj->lang->line('all_game_code_silver_paper')."<br>"; break;
							case 2: $result_temp .= $obj->lang->line('all_game_code_silver_scissors')."<br>"; break;
							case 3: $result_temp .= $obj->lang->line('all_game_code_silver_rock')."<br>"; break;
							default: $result_temp .= ""; break;
						}
					}else{
						switch($game_result_array[$i]){
							case 1: $result_temp .= $obj->lang->line('all_game_code_bronze_paper')."<br>"; break;
							case 2: $result_temp .= $obj->lang->line('all_game_code_bronze_scissors')."<br>"; break;
							case 3: $result_temp .= $obj->lang->line('all_game_code_bronze_rock')."<br>"; break;
							default: $result_temp .= ""; break;
						}
					}
				}
			}
		}
	}else if($game_real_code == 801){
		if(!empty($game_result)){
			$game_result_array = explode("},{",$game_result);
			if(!empty($game_result_array) && sizeof($game_result_array)==5){
				$first_array_data = explode(",",str_replace("{","",$game_result_array[0]));
				$bank_array_data = explode(",",str_replace("","",$game_result_array[1]));
				$player_one_array_data = explode(",",str_replace("","",$game_result_array[2]));
				$player_two_array_data = explode(",",str_replace("","",$game_result_array[3]));
				$player_three_array_data = explode(",",str_replace("}","",$game_result_array[4]));
				$first_result = "";
				$banker_result = "";
				$player_one_result = "";
				$player_two_result = "";
				$player_three_result = "";
				$suit = "";
				$rank = "";
				if(!empty($bank_array_data)){
					foreach($bank_array_data as $bank_array_data_row){
						$suit = "";
						$rank = "";
						if($bank_array_data_row > 0){
							switch(substr($bank_array_data_row,0,1)){
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch(substr($bank_array_data_row,-2)){
								case "01": $rank = "A"; break;
								case "02": $rank = "2"; break;
								case "03": $rank = "3"; break;
								case "04": $rank = "4"; break;
								case "05": $rank = "5"; break;
								case "06": $rank = "6"; break;
								case "07": $rank = "7"; break;
								case "08": $rank = "8"; break;
								case "09": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							$banker_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$banker_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$banker_result .= '<span class="rank">'.$rank.'</span>';
									$banker_result .= '<span class="suit">&'.$suit.';</span>';
								$banker_result .= '</div>';
							$banker_result .= '</div>';
						}
					}
				}
				if(!empty($player_one_array_data)){
					foreach($player_one_array_data as $player_one_array_data_row){
						$suit = "";
						$rank = "";
						if($player_one_array_data_row > 0){
							switch(substr($player_one_array_data_row,0,1)){
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch(substr($player_one_array_data_row,-2)){
								case "01": $rank = "A"; break;
								case "02": $rank = "2"; break;
								case "03": $rank = "3"; break;
								case "04": $rank = "4"; break;
								case "05": $rank = "5"; break;
								case "06": $rank = "6"; break;
								case "07": $rank = "7"; break;
								case "08": $rank = "8"; break;
								case "09": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							$player_one_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$player_one_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$player_one_result .= '<span class="rank">'.$rank.'</span>';
									$player_one_result .= '<span class="suit">&'.$suit.';</span>';
								$player_one_result .= '</div>';
							$player_one_result .= '</div>';
						}
					}
				}
				if(!empty($player_two_array_data)){
					foreach($player_two_array_data as $player_two_array_data_row){
						$suit = "";
						$rank = "";
						if($player_two_array_data_row > 0){
							switch(substr($player_two_array_data_row,0,1)){
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch(substr($player_two_array_data_row,-2)){
								case "01": $rank = "A"; break;
								case "02": $rank = "2"; break;
								case "03": $rank = "3"; break;
								case "04": $rank = "4"; break;
								case "05": $rank = "5"; break;
								case "06": $rank = "6"; break;
								case "07": $rank = "7"; break;
								case "08": $rank = "8"; break;
								case "09": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							$player_two_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$player_two_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$player_two_result .= '<span class="rank">'.$rank.'</span>';
									$player_two_result .= '<span class="suit">&'.$suit.';</span>';
								$player_two_result .= '</div>';
							$player_two_result .= '</div>';
						}
					}
				}
				if(!empty($player_three_array_data)){
					foreach($player_three_array_data as $player_three_array_data_row){
						$suit = "";
						$rank = "";
						if($player_three_array_data_row > 0){
							switch(substr($player_three_array_data_row,0,1)){
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch(substr($player_three_array_data_row,-2)){
								case "01": $rank = "A"; break;
								case "02": $rank = "2"; break;
								case "03": $rank = "3"; break;
								case "04": $rank = "4"; break;
								case "05": $rank = "5"; break;
								case "06": $rank = "6"; break;
								case "07": $rank = "7"; break;
								case "08": $rank = "8"; break;
								case "09": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							$player_three_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$player_three_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$player_three_result .= '<span class="rank">'.$rank.'</span>';
									$player_three_result .= '<span class="suit">&'.$suit.';</span>';
								$player_three_result .= '</div>';
							$player_three_result .= '</div>';
						}
					}
				}
				if(!empty($first_array_data)){
					foreach($first_array_data as $first_array_data_row){
						$suit = "";
						$rank = "";
						if($first_array_data_row > 0){
							switch(substr($first_array_data_row,0,1)){
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch(substr($first_array_data_row,-2)){
								case "01": $rank = "A"; break;
								case "02": $rank = "2"; break;
								case "03": $rank = "3"; break;
								case "04": $rank = "4"; break;
								case "05": $rank = "5"; break;
								case "06": $rank = "6"; break;
								case "07": $rank = "7"; break;
								case "08": $rank = "8"; break;
								case "09": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							$first_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$first_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$first_result .= '<span class="rank">'.$rank.'</span>';
									$first_result .= '<span class="suit">&'.$suit.';</span>';
								$first_result .= '</div>';
							$first_result .= '</div>';
						}
					}
				}
				$result_temp .= $obj->lang->line('all_result_first_card')." : ". $first_result;
				if(!empty($banker_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $obj->lang->line('all_bet_code_banker')." : ".$banker_result;
				}
				if(!empty($player_one_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $obj->lang->line('all_bet_code_player_one')." : ".$player_one_result;
				}
				if(!empty($player_two_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $obj->lang->line('all_bet_code_player_two')." : ".$player_two_result;
				}
				if(!empty($player_three_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $obj->lang->line('all_bet_code_player_three')." : ".$player_three_result;
				}
			}	
		}
	}
	if(!empty($result_temp)){
		$result .= $result_temp;
	}else{
		$result = "-";
	}
	return $result;
}
function bl_game_code_decision($game_type_code = NULL,$result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_id = (isset($result_info_array['game_code']) ? $result_info_array['game_code'] : "");
	$scene_id = (isset($result_info_array['scene_id']) ? $result_info_array['scene_id'] : "");
	$scene_name = "";
	$result = $obj->lang->line('slot_game_bl_'.$game_id);
	if($scene_id == 1100){
		$scene_name = $obj->lang->line('all_game_bet_code_boardgame_club');
	}else if($scene_id == 1200){
		$scene_name = $obj->lang->line('all_game_bet_code_boardgame_competition');
	}else{
		if($game_id == "blnn" || $game_id == "tbnn" || $game_id == "kpnn"){
			switch($scene_id){
				case "1001":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_novice');break;
				case "1002":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_junior');break;
				case "1003":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_intermediate');break;
				case "1004":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_advanced');break;
				case "1005":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_supreme');break;
				case "1006":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_king');break;
				default:$scene_name = "";break;
			}
		}else if($game_id == "sangong"){
			switch($scene_id){
				case "1001":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_novice');break;
				case "1002":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_junior');break;
				case "1003":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_intermediate');break;
				case "1004":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_advanced');break;
				case "1005":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_king');break;
				default:$scene_name = "";break;
			}
		}else if($game_id == "mjxlch" || $game_id == "mjxzdd" || $game_id == "dzmj" || $game_id == "ermj" || $game_id == "blackjack" || $game_id == "zjh" || $game_id == "gyzjmj" || $game_id == "hbmj" || $game_id == "hnmj" || $game_id == "hzmj" || $game_id == "gdmj" || $game_id == "qydz" || $game_id == "s13"){
			switch($scene_id){
				case "1001":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_junior');break;
				case "1002":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_intermediate');break;
				case "1003":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_advanced');break;
				case "1004":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_tyrant');break;
				default:$scene_name = "";break;
			}
		}else if($game_id == "rbwar"){
			switch($scene_id){
				case "1001":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_junior');break;
				case "1002":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_intermediate');break;
				case "1003":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_advanced');break;
				default:$scene_name = "";break;
			}
		}else if($game_id == "baccarat"){
			switch($scene_id){
				case "1001":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_junior');break;
				case "1002":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_intermediate');break;
				case "1003":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_advanced');break;
				case "1004":$scene_name = $obj->lang->line('all_game_bet_code_boardgame_tyrant');break;
				default:$scene_name = "";break;
			}
		}else{
			$scene_name = "";
		}
	}
	if(!empty($scene_name)){
		$result .= " (".$scene_name.")";
	}
	return $result;
}
function bl_bet_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$transaction_id = (isset($result_info_array['room_id']) ? $result_info_array['room_id'] : "");
	$room_id = (isset($result_info_array['sn']) ? $result_info_array['sn'] : "");
	$type = (isset($result_info_array['type']) ? $result_info_array['type'] : "");
	$result = $obj->lang->line('all_result_room_id').": ".$room_id."<br>";
	switch($type){
		case "contest": $result .= $obj->lang->line('all_game_bet_code_type').": ".$obj->lang->line('all_game_bet_code_boardgame_versus')."<br>";break;
		case "multi": $result .= $obj->lang->line('all_game_bet_code_type').": ".$obj->lang->line('all_game_bet_code_boardgame_hundred_boardgame')."<br>";break;
		case "slot": $result .= $obj->lang->line('all_game_bet_code_type').": ".$obj->lang->line('all_game_bet_code_boardgame_slot')."<br>";break;
		default: $result .= "";break;
	}
	$result .= $obj->lang->line('all_result_bet_id').": ".$transaction_id."<br>";
	return $result;
}
function bng_game_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_id = (isset($result_info_array['game_id']) ? $result_info_array['game_id'] : "");
	$result = $obj->lang->line('slot_game_bng_'.$game_id);
	return $result;
}
function bng_bet_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$transaction_id = (isset($result_info_array['transaction_id']) ? $result_info_array['transaction_id'] : "");
	$result = $obj->lang->line('all_result_bet_id').": ".$transaction_id."<br>";
	return $result;
}
function dg_game_code_decision($game_type_code = NULL,$result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_real_code = (isset($result_info_array['gameId']) ? $result_info_array['gameId'] : "");
	$table_id = (isset($result_info_array['tableId']) ? $result_info_array['tableId'] : "");
	$lobby_id = (isset($result_info_array['lobbyId']) ? $result_info_array['lobbyId'] : "");
	$result = "";
	$result_table = "";
	$result_code = "";
	$result_lobby = "";
	if($game_type_code == GAME_LIVE_CASINO){
		$obj =& get_instance();
		switch($game_real_code)
		{
			case 1: $result_code = $obj->lang->line('all_game_code_baccarat'); break;
			case 2: $result_code = $obj->lang->line('all_game_code_insurance_baccarat'); break;
			case 3: $result_code = $obj->lang->line('all_game_code_dragon_tiger'); break;
			case 4: $result_code = $obj->lang->line('all_game_code_roulette'); break;
			case 5: $result_code = $obj->lang->line('all_game_code_sicbo'); break;
			case 6: $result_code = $obj->lang->line('all_game_code_fan_tan'); break;
			case 7: $result_code = $obj->lang->line('all_game_code_bull_bull'); break;
			case 8: $result_code = $obj->lang->line('all_game_code_baccarat'); break;
			case 11: $result_code = $obj->lang->line('all_game_code_three_card'); break;
			case 12: $result_code = $obj->lang->line('all_game_code_sicbo'); break;
			case 14: $result_code = $obj->lang->line('all_game_code_se_die'); break;
			case 15: $result_code = $obj->lang->line('all_game_code_fish_prawn_crab'); break;
			case 16: $result_code = $obj->lang->line('all_game_code_sam_gong'); break;
			case 41: $result_code = $obj->lang->line('all_game_code_blockchain_bacarrat'); break;
			case 42: $result_code = $obj->lang->line('all_game_code_blockchain_dragon_tiger'); break;
			case 43: $result_code = $obj->lang->line('all_game_code_blockchain_three_card'); break;
			case 51: $result_code = $obj->lang->line('all_game_code_live_lucky_five'); break;
			case 52: $result_code = $obj->lang->line('all_game_code_live_lucky_ten'); break;
			default: $result_code = ""; break;
		}
		switch($game_real_code)
		{
			case 1:
				switch($table_id){
					case 10101: $result_table = "DG01"; break;
					case 10102: $result_table = "DG02"; break;
					case 10103: $result_table = "DG03"; break;
					case 10104: $result_table = "DG05"; break;
					case 10105: $result_table = "DG06"; break;
					case 10106: $result_table = "DG07"; break;
					case 10107: $result_table = "DG08"; break;
					case 10108: $result_table = "DG09"; break;
					case 10109: $result_table = "DG10"; break;
					case 30101: $result_table = "CT01"; break;
					case 30102: $result_table = "CT02"; break;
					case 30103: $result_table = "CT03"; break;
					case 30105: $result_table = "CT05"; break;
					case 50101: $result_table = "E1"; break;
					case 50102: $result_table = "E3"; break;
					case 50103: $result_table = "E7"; break;
					case 70101: $result_table = "GC01"; break;
					case 70102: $result_table = "GC02"; break;
					case 70103: $result_table = "GC03"; break;
					case 70105: $result_table = "GC05"; break;
					case 70106: $result_table = "GC06"; break;
					default: $result_table = ""; break;
				}break;
			case 2:
				switch($table_id){
					case 10201: $result_table = "DG13"; break;
					case 10202: $result_table = "DG15"; break;
					default: $result_table = ""; break;
				}break;
			case 3:
				switch($table_id){
					case 10301: $result_table = "DG16"; break;
					case 10302: $result_table = "DG17"; break;
					case 30301: $result_table = "CT06"; break;
					default: $result_table = ""; break;
				}break;
			case 4: 
				switch($table_id){
					case 10401: $result_table = "DG21"; break;
					case 30401: $result_table = "CT08"; break;
					case 50401: $result_table = "R1"; break;
					default: $result_table = ""; break;
				}break;
			case 5: 
				switch($table_id){
					case 10501: $result_table = "DG22"; break;
					case 70501: $result_table = "GC09"; break;
					default: $result_table = ""; break;
				}break;
			case 6: 
				switch($table_id){
					case 30601: $result_table = "CT10"; break;
					default: $result_table = ""; break;
				}break;
			case 7: 
				switch($table_id){
					case 10802: $result_table = "DG11"; break;
					case 10803: $result_table = "DG12"; break;
					default: $result_table = ""; break;
				}break;
			case 8: 
				switch($table_id){
					case 10701: $result_table = "DG18"; break;
					default: $result_table = ""; break;
				}break;
			case 11: 
				switch($table_id){
					case 11101: $result_table = "DG19"; break;
					default: $result_table = ""; break;
				}break;
			case 16: 
				switch($table_id){
					case 11101: $result_table = "DG20"; break;
					default: $result_table = ""; break;
				}break;
			case 41: 
				switch($table_id){
					case 84101: $result_table = "Q1"; break;
					case 84102: $result_table = "Q2"; break;
					case 84103: $result_table = "Q3"; break;
					case 84104: $result_table = "Q5"; break;
					case 84105: $result_table = "Q6"; break;
					case 84106: $result_table = "Q7"; break;
					default: $result_table = ""; break;
				}break;
			case 42: 
				switch($table_id){
					case 84201: $result_table = "Q8"; break;
					case 84202: $result_table = "Q9"; break;
					default: $result_table = ""; break;
				}break;
			case 43: 
				switch($table_id){
					case 84301: $result_table = "Q10"; break;
					default: $result_table = ""; break;
				}break;
			default: $result_table = ""; break;
		}
		switch($lobby_id)
		{
			case 1: $result_lobby = $obj->lang->line('dg_lobby_code_one'); break;
			case 2: $result_lobby = ""; break;
			case 3: $result_lobby = $obj->lang->line('dg_lobby_code_three'); break;
			case 4: $result_lobby = $obj->lang->line('dg_lobby_code_four'); break;
			case 5: $result_lobby = $obj->lang->line('dg_lobby_code_five'); break;
			case 6: $result_lobby = ""; break;
			case 7: $result_lobby = $obj->lang->line('dg_lobby_code_seven'); break;
			case 8: $result_lobby = $obj->lang->line('dg_lobby_code_eight'); break;
			default: $result_lobby = ""; break;
		}
		if(!empty($result_code)){
			$result .= $result_code."<br>";
		}
		if(!empty($result_table)){
			$result .= $result_table."<br>";
		}
		if(!empty($result_lobby)){
			$result .= $result_lobby."<br>";
		}
		if(isset($result_info_array['ext'])){
			$result .= $result_info_array['ext']."<br>";
		}
	}else if($game_type_code == GAME_OTHERS){
		$obj =& get_instance();
		switch($game_real_code)
		{
			case 1: $result = $obj->lang->line('all_game_code_member_send_gift'); break;
			case 2: $result = $obj->lang->line('all_game_code_member_get_gift'); break;
			case 3: $result = $obj->lang->line('all_game_code_anchor_send_tips'); break;
			case 4: $result = $obj->lang->line('all_game_code_company_send_gift'); break;
			case 5: $result = $obj->lang->line('all_game_code_bo_bing'); break;
			case 6: $result = $obj->lang->line('all_game_code_croupier_send_tips'); break;
			default: $result = "-"; break;
		}					
	}
	return $result;
}
function dg_bet_code_decision($game_type_code = NULL,$result_info = NULL){
	$obj =& get_instance();
	$result = "-";
	$result_temp = "";
	$temp_string = "";
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_real_code = (isset($result_info_array['gameId']) ? $result_info_array['gameId'] : "");
	$bet_code = (isset($result_info_array['betDetail']) ? $result_info_array['betDetail'] : "");
	if($game_type_code == GAME_LIVE_CASINO){
		$bet_code_array = json_decode($bet_code,true, 512, JSON_BIGINT_AS_STRING);
		if(!empty($bet_code_array)){
			if($game_real_code == "1"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					switch($bet_code_row_key)
					{
						case "banker": $result_temp .= $obj->lang->line('all_bet_code_banker')." : ".$bet_code_row_val."<br/>"; break;
						case "banker6": $result_temp .= $obj->lang->line('all_bet_code_no_commission_banker')." : ".$bet_code_row_val."<br/>"; break;
						case "player": $result_temp .= $obj->lang->line('all_bet_code_player')." : ".$bet_code_row_val."<br/>"; break;
						case "tie": $result_temp .= $obj->lang->line('all_bet_code_tie')." : ".$bet_code_row_val."<br/>"; break;
						case "bPair": $result_temp .= $obj->lang->line('all_bet_code_banker_pair')." : ".$bet_code_row_val."<br/>"; break;
						case "pPair": $result_temp .= $obj->lang->line('all_bet_code_player_pair')." : ".$bet_code_row_val."<br/>"; break;
						case "big": $result_temp .= $obj->lang->line('all_bet_code_big')." : ".$bet_code_row_val."<br/>"; break;
						case "small": $result_temp .= $obj->lang->line('all_bet_code_small')." : ".$bet_code_row_val."<br/>"; break;
						case "bBX": $result_temp .= $obj->lang->line('all_bet_code_commission_banker')." : ".$bet_code_row_val."<br/>"; break;
						case "pBX": $result_temp .= $obj->lang->line('all_bet_code_commission_player')." : ".$bet_code_row_val."<br/>"; break;
						case "super6": $result_temp .= $obj->lang->line('all_bet_code_commission_lucky_six')." : ".$bet_code_row_val."<br/>"; break;
						case "anyPair": $result_temp .= $obj->lang->line('all_bet_code_pair')." : ".$bet_code_row_val."<br/>"; break;
						case "perfectPair": $result_temp .= $obj->lang->line('all_bet_code_perfect_pair')." : ".$bet_code_row_val."<br/>"; break;
						case "bBonus": $result_temp .= $obj->lang->line('all_bet_code_banker_dragon_bonus')." : ".$bet_code_row_val."<br/>"; break;
						case "pBonus": $result_temp .= $obj->lang->line('all_bet_code_player_dragon_bonus')." : ".$bet_code_row_val."<br/>"; break;
						default: $result_temp .= ""; break;
					}
				}
			}
			else if($game_real_code == "41"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					switch($bet_code_row_key)
					{
						case "banker": $result_temp .= $obj->lang->line('all_bet_code_banker')." : ".$bet_code_row_val."<br/>"; break;
						case "banker6": $result_temp .= $obj->lang->line('all_bet_code_no_commission_banker')." : ".$bet_code_row_val."<br/>"; break;
						case "player": $result_temp .= $obj->lang->line('all_bet_code_player')." : ".$bet_code_row_val."<br/>"; break;
						case "tie": $result_temp .= $obj->lang->line('all_bet_code_tie')." : ".$bet_code_row_val."<br/>"; break;
						case "bPair": $result_temp .= $obj->lang->line('all_bet_code_banker_pair')." : ".$bet_code_row_val."<br/>"; break;
						case "pPair": $result_temp .= $obj->lang->line('all_bet_code_player_pair')." : ".$bet_code_row_val."<br/>"; break;
						case "big": $result_temp .= $obj->lang->line('all_bet_code_big')." : ".$bet_code_row_val."<br/>"; break;
						case "small": $result_temp .= $obj->lang->line('all_bet_code_small')." : ".$bet_code_row_val."<br/>"; break;
						case "bBX": $result_temp .= $obj->lang->line('all_bet_code_commission_banker')." : ".$bet_code_row_val."<br/>"; break;
						case "pBX": $result_temp .= $obj->lang->line('all_bet_code_commission_player')." : ".$bet_code_row_val."<br/>"; break;
						case "super6": $result_temp .= $obj->lang->line('all_bet_code_commission_lucky_six')." : ".$bet_code_row_val."<br/>"; break;
						case "anyPair": $result_temp .= $obj->lang->line('all_bet_code_pair')." : ".$bet_code_row_val."<br/>"; break;
						case "perfectPair": $result_temp .= $obj->lang->line('all_bet_code_perfect_pair')." : ".$bet_code_row_val."<br/>"; break;
						case "bBonus": $result_temp .= $obj->lang->line('all_bet_code_banker_dragon_bonus')." : ".$bet_code_row_val."<br/>"; break;
						case "pBonus": $result_temp .= $obj->lang->line('all_bet_code_player_dragon_bonus')." : ".$bet_code_row_val."<br/>"; break;
						default: $result_temp .= ""; break;
					}
				}
			}
			else if($game_real_code == "2"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					switch($bet_code_row_key)
					{
						case "banker": $result_temp .= $obj->lang->line('all_bet_code_banker')." : ".$bet_code_row_val."<br/>"; break;
						case "player": $result_temp .= $obj->lang->line('all_bet_code_player')." : ".$bet_code_row_val."<br/>"; break;
						case "tie": $result_temp .= $obj->lang->line('all_bet_code_tie')." : ".$bet_code_row_val."<br/>"; break;
						case "bPair": $result_temp .= $obj->lang->line('all_bet_code_banker_pair')." : ".$bet_code_row_val."<br/>"; break;
						case "pPair": $result_temp .= $obj->lang->line('all_bet_code_player_pair')." : ".$bet_code_row_val."<br/>"; break;
						case "bBX": $result_temp .= $obj->lang->line('all_bet_code_commission_banker')." : ".$bet_code_row_val."<br/>"; break;
						case "pBX": $result_temp .= $obj->lang->line('all_bet_code_commission_player')." : ".$bet_code_row_val."<br/>"; break;
						default: $result_temp .= ""; break;
					}
				}
			}else if($game_real_code == "3"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					switch($bet_code_row_key)
					{
						case "dragon": $result_temp .= $obj->lang->line('all_bet_code_dragon')." : ".$bet_code_row_val."<br/>"; break;
						case "tiger": $result_temp .= $obj->lang->line('all_bet_code_tiger')." : ".$bet_code_row_val."<br/>"; break;
						case "tie": $result_temp .= $obj->lang->line('all_bet_code_tie')." : ".$bet_code_row_val."<br/>"; break;
						case "dragonRed": $result_temp .= $obj->lang->line('all_bet_code_dragon_red')." : ".$bet_code_row_val."<br/>"; break;
						case "dragonBlack": $result_temp .= $obj->lang->line('all_bet_code_dragon_black')." : ".$bet_code_row_val."<br/>"; break;
						case "tigerRed": $result_temp .= $obj->lang->line('all_bet_code_tiger_red')." : ".$bet_code_row_val."<br/>"; break;
						case "tigerBlack": $result_temp .= $obj->lang->line('all_bet_code_tiger_black')." : ".$bet_code_row_val."<br/>"; break;
						case "dragonOdd": $result_temp .= $obj->lang->line('all_bet_code_dragon_odd')." : ".$bet_code_row_val."<br/>"; break;
						case "tigerOdd": $result_temp .= $obj->lang->line('all_bet_code_tiger_odd')." : ".$bet_code_row_val."<br/>"; break;
						case "dragonEven": $result_temp .= $obj->lang->line('all_bet_code_dragon_even')." : ".$bet_code_row_val."<br/>"; break;
						case "tigerEven": $result_temp .= $obj->lang->line('all_bet_code_tiger_even')." : ".$bet_code_row_val."<br/>"; break;
						default: $result_temp .= ""; break;
					}
				}
			}else if($game_real_code == "42"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					switch($bet_code_row_key)
					{
						case "dragon": $result_temp .= $obj->lang->line('all_bet_code_dragon')." : ".$bet_code_row_val."<br/>"; break;
						case "tiger": $result_temp .= $obj->lang->line('all_bet_code_tiger')." : ".$bet_code_row_val."<br/>"; break;
						case "tie": $result_temp .= $obj->lang->line('all_bet_code_tie')." : ".$bet_code_row_val."<br/>"; break;
						case "dragonRed": $result_temp .= $obj->lang->line('all_bet_code_dragon_red')." : ".$bet_code_row_val."<br/>"; break;
						case "dragonBlack": $result_temp .= $obj->lang->line('all_bet_code_dragon_black')." : ".$bet_code_row_val."<br/>"; break;
						case "tigerRed": $result_temp .= $obj->lang->line('all_bet_code_tiger_red')." : ".$bet_code_row_val."<br/>"; break;
						case "tigerBlack": $result_temp .= $obj->lang->line('all_bet_code_tiger_black')." : ".$bet_code_row_val."<br/>"; break;
						case "dragonOdd": $result_temp .= $obj->lang->line('all_bet_code_dragon_odd')." : ".$bet_code_row_val."<br/>"; break;
						case "tigerOdd": $result_temp .= $obj->lang->line('all_bet_code_tiger_odd')." : ".$bet_code_row_val."<br/>"; break;
						case "dragonEven": $result_temp .= $obj->lang->line('all_bet_code_dragon_even')." : ".$bet_code_row_val."<br/>"; break;
						case "tigerEven": $result_temp .= $obj->lang->line('all_bet_code_tiger_even')." : ".$bet_code_row_val."<br/>"; break;
						default: $result_temp .= ""; break;
					}
				}
			}else if($game_real_code == "4"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					if($bet_code_row_key == "direct"){
						$result_temp .= $obj->lang->line('all_bet_code_direct')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= $bet_code_row_val_key . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "separate"){
						$result_temp .= $obj->lang->line('all_bet_code_separate')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= substr($bet_code_row_val_key,0,2).",".substr($bet_code_row_val_key,2) . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "street"){
						$result_temp .= $obj->lang->line('all_bet_code_street')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= substr($bet_code_row_val_key,0,2).",".substr($bet_code_row_val_key,2,2).",".substr($bet_code_row_val_key,4,2) . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "angle"){
						$result_temp .= $obj->lang->line('all_bet_code_angle')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= substr($bet_code_row_val_key,0,2).",".substr($bet_code_row_val_key,2,2).",".substr($bet_code_row_val_key,4,2).",".substr($bet_code_row_val_key,6,2) . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "line"){
						$result_temp .= $obj->lang->line('all_bet_code_angle')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= substr($bet_code_row_val_key,0,2).",".substr($bet_code_row_val_key,2,2).",".substr($bet_code_row_val_key,4,2).",".substr($bet_code_row_val_key,6,2).",".substr($bet_code_row_val_key,8,2).",".substr($bet_code_row_val_key,10,2) . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "three"){
						$result_temp .= $obj->lang->line('all_bet_code_street')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= substr($bet_code_row_val_key,0,2).",".substr($bet_code_row_val_key,2,2).",".substr($bet_code_row_val_key,4,2) . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "four"){
						$result_temp .= $obj->lang->line('all_bet_code_four_number')."<br />";
						$result_temp .= "0,1,2,3 : ".$bet_code_row_val."<br /><br />";
					}else{
						switch($bet_code_row_key)
						{
							case "firstRow": $result_temp .= $obj->lang->line('all_bet_code_first_row')." : ".$bet_code_row_val."<br/>"; break;
							case "sndRow": $result_temp .= $obj->lang->line('all_bet_code_second_row')." : ".$bet_code_row_val."<br/>"; break;
							case "thrRow": $result_temp .= $obj->lang->line('all_bet_code_third_row')." : ".$bet_code_row_val."<br/>"; break;
							case "firstCol": $result_temp .= $obj->lang->line('all_bet_code_first_twelve')." : ".$bet_code_row_val."<br/>"; break;
							case "sndCol": $result_temp .= $obj->lang->line('all_bet_code_second_twelve')." : ".$bet_code_row_val."<br/>"; break;
							case "thrCol": $result_temp .= $obj->lang->line('all_bet_code_third_twelve')." : ".$bet_code_row_val."<br/>"; break;
							case "red": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$bet_code_row_val."<br/>"; break;
							case "black": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$bet_code_row_val."<br/>"; break;
							case "odd": $result_temp .= $obj->lang->line('all_bet_code_odd')." : ".$bet_code_row_val."<br/>"; break;
							case "even": $result_temp .= $obj->lang->line('all_bet_code_even')." : ".$bet_code_row_val."<br/>"; break;
							case "low": $result_temp .= $obj->lang->line('all_bet_code_small_eighteen')." : ".$bet_code_row_val."<br/>"; break;
							case "high": $result_temp .= $obj->lang->line('all_bet_code_big_eighteen')." : ".$bet_code_row_val."<br/>"; break;
							default: $result_temp .= ""; break;
						}
					}
				}
			}else if($game_real_code == "5"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					if($bet_code_row_key == "threeForces"){
						$result_temp .= $obj->lang->line('all_bet_code_one_of_a_kind')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= $bet_code_row_val_key . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "nineWayGards"){
						$result_temp .= $obj->lang->line('all_bet_code_pair')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= substr($bet_code_row_val_key,0,1).",".substr($bet_code_row_val_key,1,1) . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "pairs"){
						$result_temp .= $obj->lang->line('all_bet_code_specific_double')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= substr($bet_code_row_val_key,0,1) . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "surroundDices"){
						$result_temp .= $obj->lang->line('all_bet_code_specific_triples')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= substr($bet_code_row_val_key,0,1) . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "points"){
						$result_temp .= $obj->lang->line('all_bet_code_sum_number')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= $bet_code_row_val_key . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else{
						switch($bet_code_row_key)
						{
							case "big": $result_temp .= $obj->lang->line('all_bet_code_big')." : ".$bet_code_row_val."<br/>"; break;
							case "small": $result_temp .= $obj->lang->line('all_bet_code_small')." : ".$bet_code_row_val."<br/>"; break;
							case "odd": $result_temp .= $obj->lang->line('all_bet_code_odd')." : ".$bet_code_row_val."<br/>"; break;
							case "even": $result_temp .= $obj->lang->line('all_bet_code_even')." : ".$bet_code_row_val."<br/>"; break;
							case "allDices": $result_temp .= $obj->lang->line('all_bet_code_any_specific_triples')." : ".$bet_code_row_val."<br/>"; break;
							default: $result_temp .= ""; break;
						}
					}
				}
			}else if($game_real_code == "6"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					if($bet_code_row_key == "fan"){
						$result_temp .= $obj->lang->line('all_bet_code_fan')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= $bet_code_row_val_key.$obj->lang->line('all_bet_code_fan') . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "jiao"){
						$result_temp .= $obj->lang->line('all_bet_code_kwok_number')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= $bet_code_row_val_key.$obj->lang->line('all_bet_code_kwok_number') . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "nian"){
						$result_temp .= $obj->lang->line('all_bet_code_nim_number')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= substr($bet_code_row_val_key,0,1).$obj->lang->line('all_bet_code_nim_number').substr($bet_code_row_val_key,1,1). " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "tong"){
						$result_temp .= $obj->lang->line('all_bet_code_nga_number')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								switch($bet_code_row_val_key){
									case "123": $result_temp .= "12 ".$obj->lang->line('all_bet_code_four').$obj->lang->line('all_bet_code_nga_number')." : ".$bet_code_row_val_val."<br/>"; break;
									case "124": $result_temp .= "12 ".$obj->lang->line('all_bet_code_three').$obj->lang->line('all_bet_code_nga_number')." : ".$bet_code_row_val_val."<br/>"; break;
									case "132": $result_temp .= "13 ".$obj->lang->line('all_bet_code_four').$obj->lang->line('all_bet_code_nga_number')." : ".$bet_code_row_val_val."<br/>"; break;
									case "134": $result_temp .= "13 ".$obj->lang->line('all_bet_code_two').$obj->lang->line('all_bet_code_nga_number')." : ".$bet_code_row_val_val."<br/>"; break;
									case "142": $result_temp .= "14 ".$obj->lang->line('all_bet_code_three').$obj->lang->line('all_bet_code_nga_number')." : ".$bet_code_row_val_val."<br/>"; break;
									case "143": $result_temp .= "14 ".$obj->lang->line('all_bet_code_two').$obj->lang->line('all_bet_code_nga_number')." : ".$bet_code_row_val_val."<br/>"; break;
									case "231": $result_temp .= "23 ".$obj->lang->line('all_bet_code_four').$obj->lang->line('all_bet_code_nga_number')." : ".$bet_code_row_val_val."<br/>"; break;
									case "234": $result_temp .= "23 ".$obj->lang->line('all_bet_code_one').$obj->lang->line('all_bet_code_nga_number')." : ".$bet_code_row_val_val."<br/>"; break;
									case "341": $result_temp .= "34 ".$obj->lang->line('all_bet_code_two').$obj->lang->line('all_bet_code_nga_number')." : ".$bet_code_row_val_val."<br/>"; break;
									case "342": $result_temp .= "34 ".$obj->lang->line('all_bet_code_one').$obj->lang->line('all_bet_code_nga_number')." : ".$bet_code_row_val_val."<br/>"; break;
									case "241": $result_temp .= "24 ".$obj->lang->line('all_bet_code_three').$obj->lang->line('all_bet_code_nga_number')." : ".$bet_code_row_val_val."<br/>"; break;
									case "243": $result_temp .= "24 ".$obj->lang->line('all_bet_code_one').$obj->lang->line('all_bet_code_nga_number')." : ".$bet_code_row_val_val."<br/>"; break;
									default: $result_temp .= "-"; break;
								}
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "sanmen"){
						$result_temp .= $obj->lang->line('all_bet_code_sanmen')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= $bet_code_row_val_key . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else{
						switch($bet_code_row_key)
						{
							case "odd": $result_temp .= $obj->lang->line('all_bet_code_odd')." : ".$bet_code_row_val."<br/>"; break;
							case "even": $result_temp .= $obj->lang->line('all_bet_code_even')." : ".$bet_code_row_val."<br/>"; break;
							default: $result_temp .= ""; break;
						}
					}
				}
			}else if($game_real_code == "7"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					switch($bet_code_row_key)
					{
						case "player1Double": $result_temp .= $obj->lang->line('all_bet_code_player_one_double')." : ".$bet_code_row_val."<br/>"; break;
						case "player2Double": $result_temp .= $obj->lang->line('all_bet_code_player_two_double')." : ".$bet_code_row_val."<br/>"; break;
						case "player3Double": $result_temp .= $obj->lang->line('all_bet_code_player_three_double')." : ".$bet_code_row_val."<br/>"; break;
						case "player1Equal": $result_temp .= $obj->lang->line('all_bet_code_player_one_equal')." : ".$bet_code_row_val."<br/>"; break;
						case "player2Equal": $result_temp .= $obj->lang->line('all_bet_code_player_two_equal')." : ".$bet_code_row_val."<br/>"; break;
						case "player3Equal": $result_temp .= $obj->lang->line('all_bet_code_player_three_equal')." : ".$bet_code_row_val."<br/>"; break;
						case "player1Many": $result_temp .= $obj->lang->line('all_bet_code_player_one_many')." : ".$bet_code_row_val."<br/>"; break;
						case "player2Many": $result_temp .= $obj->lang->line('all_bet_code_player_two_many')." : ".$bet_code_row_val."<br/>"; break;
						case "player3Many": $result_temp .= $obj->lang->line('all_bet_code_player_three_many')." : ".$bet_code_row_val."<br/>"; break;
						case "banker1Double": $result_temp .= $obj->lang->line('all_bet_code_banker_one_double')." : ".$bet_code_row_val."<br/>"; break;
						case "banker2Double": $result_temp .= $obj->lang->line('all_bet_code_banker_two_double')." : ".$bet_code_row_val."<br/>"; break;
						case "banker3Double": $result_temp .= $obj->lang->line('all_bet_code_banker_three_double')." : ".$bet_code_row_val."<br/>"; break;
						case "banker1Equal": $result_temp .= $obj->lang->line('all_bet_code_banker_one_equal')." : ".$bet_code_row_val."<br/>"; break;
						case "banker2Equal": $result_temp .= $obj->lang->line('all_bet_code_banker_two_equal')." : ".$bet_code_row_val."<br/>"; break;
						case "banker3Equal": $result_temp .= $obj->lang->line('all_bet_code_banker_three_equal')." : ".$bet_code_row_val."<br/>"; break;
						case "banker1Many": $result_temp .= $obj->lang->line('all_bet_code_banker_one_many')." : ".$bet_code_row_val."<br/>"; break;
						case "banker2Many": $result_temp .= $obj->lang->line('all_bet_code_banker_two_many')." : ".$bet_code_row_val."<br/>"; break;
						case "banker3Many": $result_temp .= $obj->lang->line('all_bet_code_banker_three_many')." : ".$bet_code_row_val."<br/>"; break;
						default: $result_temp .= ""; break;
					}
				}
			}else if($game_real_code == "8"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					switch($bet_code_row_key)
					{
						case "banker": $result_temp .= $obj->lang->line('all_bet_code_banker')." : ".$bet_code_row_val."<br/>"; break;
						case "banker6": $result_temp .= $obj->lang->line('all_bet_code_no_commission_banker')." : ".$bet_code_row_val."<br/>"; break;
						case "player": $result_temp .= $obj->lang->line('all_bet_code_player')." : ".$bet_code_row_val."<br/>"; break;
						case "tie": $result_temp .= $obj->lang->line('all_bet_code_tie')." : ".$bet_code_row_val."<br/>"; break;
						case "bPair": $result_temp .= $obj->lang->line('all_bet_code_banker_pair')." : ".$bet_code_row_val."<br/>"; break;
						case "pPair": $result_temp .= $obj->lang->line('all_bet_code_player_pair')." : ".$bet_code_row_val."<br/>"; break;
						case "big": $result_temp .= $obj->lang->line('all_bet_code_big')." : ".$bet_code_row_val."<br/>"; break;
						case "small": $result_temp .= $obj->lang->line('all_bet_code_small')." : ".$bet_code_row_val."<br/>"; break;
						case "bBX": $result_temp .= $obj->lang->line('all_bet_code_commission_banker')." : ".$bet_code_row_val."<br/>"; break;
						case "pBX": $result_temp .= $obj->lang->line('all_bet_code_commission_player')." : ".$bet_code_row_val."<br/>"; break;
						case "super6": $result_temp .= $obj->lang->line('all_bet_code_commission_lucky_six')." : ".$bet_code_row_val."<br/>"; break;
						case "anyPair": $result_temp .= $obj->lang->line('all_bet_code_pair')." : ".$bet_code_row_val."<br/>"; break;
						case "perfectPair": $result_temp .= $obj->lang->line('all_bet_code_perfect_pair')." : ".$bet_code_row_val."<br/>"; break;
						case "bBonus": $result_temp .= $obj->lang->line('all_bet_code_banker_dragon_bonus')." : ".$bet_code_row_val."<br/>"; break;
						case "pBonus": $result_temp .= $obj->lang->line('all_bet_code_player_dragon_bonus')." : ".$bet_code_row_val."<br/>"; break;
						default: $result_temp .= ""; break;
					}
				}
			}else if($game_real_code == "11"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					switch($bet_code_row_key)
					{
						case "red": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$bet_code_row_val."<br/>"; break;
						case "black": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$bet_code_row_val."<br/>"; break;
						case "luck": $result_temp .= $obj->lang->line('all_bet_code_luck')." : ".$bet_code_row_val."<br/>"; break;
						default: $result_temp .= ""; break;
					}
				}
			}else if($game_real_code == "43"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					switch($bet_code_row_key)
					{
						case "red": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$bet_code_row_val."<br/>"; break;
						case "black": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$bet_code_row_val."<br/>"; break;
						case "luck": $result_temp .= $obj->lang->line('all_bet_code_luck')." : ".$bet_code_row_val."<br/>"; break;
						default: $result_temp .= ""; break;
					}
				}
			}else if($game_real_code == "12"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					if($bet_code_row_key == "threeForces"){
						$result_temp .= $obj->lang->line('all_bet_code_one_of_a_kind')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= $bet_code_row_val_key . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "nineWayGards"){
						$result_temp .= $obj->lang->line('all_bet_code_pair')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= substr($bet_code_row_val_key,0,1).",".substr($bet_code_row_val_key,1,1) . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "pairs"){
						$result_temp .= $obj->lang->line('all_bet_code_specific_double')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= substr($bet_code_row_val_key,0,1) . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "surroundDices"){
						$result_temp .= $obj->lang->line('all_bet_code_specific_triples')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= substr($bet_code_row_val_key,0,1) . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else if($bet_code_row_key == "points"){
						$result_temp .= $obj->lang->line('all_bet_code_sum_number')."<br />";
						if(!empty($bet_code_row_val)){
							foreach($bet_code_row_val as $bet_code_row_val_key => $bet_code_row_val_val){
								$result_temp .= $bet_code_row_val_key . " : ".$bet_code_row_val_val."<br />";
							}
						}
						$result_temp .= "<br />";
					}else{
						switch($bet_code_row_key)
						{
							case "big": $result_temp .= $obj->lang->line('all_bet_code_big')." : ".$bet_code_row_val."<br/>"; break;
							case "small": $result_temp .= $obj->lang->line('all_bet_code_small')." : ".$bet_code_row_val."<br/>"; break;
							case "odd": $result_temp .= $obj->lang->line('all_bet_code_odd')." : ".$bet_code_row_val."<br/>"; break;
							case "even": $result_temp .= $obj->lang->line('all_bet_code_even')." : ".$bet_code_row_val."<br/>"; break;
							case "allDices": $result_temp .= $obj->lang->line('all_bet_code_any_specific_triples')." : ".$bet_code_row_val."<br/>"; break;
							default: $result_temp .= ""; break;
						}
					}
				}
			}else if($game_real_code == "14"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					switch($bet_code_row_key)
					{
						case "zero": $result_temp .= $obj->lang->line('all_bet_code_four_white')." : ".$bet_code_row_val."<br/>"; break;
						case "one": $result_temp .= $obj->lang->line('all_bet_code_three_white_one_red')." : ".$bet_code_row_val."<br/>"; break;
						case "three": $result_temp .= $obj->lang->line('all_bet_code_three_red_one_white')." : ".$bet_code_row_val."<br/>"; break;
						case "four": $result_temp .= $obj->lang->line('all_bet_code_four_red')." : ".$bet_code_row_val."<br/>"; break;
						case "odd": $result_temp .= $obj->lang->line('all_bet_code_odd')." : ".$bet_code_row_val."<br/>"; break;
						case "even": $result_temp .= $obj->lang->line('all_bet_code_even')." : ".$bet_code_row_val."<br/>"; break;
						default: $result_temp .= ""; break;
					}
				}
			}else if($game_real_code == "15"){
			}else if($game_real_code == "16"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					switch($bet_code_row_key)
					{
						case "banker1": $result_temp .= $obj->lang->line('all_bet_code_player_one_lose')." : ".$bet_code_row_val."<br/>"; break;
						case "banker2": $result_temp .= $obj->lang->line('all_bet_code_player_two_lose')." : ".$bet_code_row_val."<br/>"; break;
						case "banker3": $result_temp .= $obj->lang->line('all_bet_code_player_three_lose')." : ".$bet_code_row_val."<br/>"; break;
						case "player1": $result_temp .= $obj->lang->line('all_bet_code_player_one_win')." : ".$bet_code_row_val."<br/>"; break;
						case "player2": $result_temp .= $obj->lang->line('all_bet_code_player_two_win')." : ".$bet_code_row_val."<br/>"; break;
						case "player3": $result_temp .= $obj->lang->line('all_bet_code_player_three_win')." : ".$bet_code_row_val."<br/>"; break;
						case "tie1": $result_temp .= $obj->lang->line('all_bet_code_player_one_tie')." : ".$bet_code_row_val."<br/>"; break;
						case "tie2": $result_temp .= $obj->lang->line('all_bet_code_player_two_tie')." : ".$bet_code_row_val."<br/>"; break;
						case "tie3": $result_temp .= $obj->lang->line('all_bet_code_player_three_tie')." : ".$bet_code_row_val."<br/>"; break;
						case "tp1": $result_temp .= $obj->lang->line('all_bet_code_player_one_three_face')." : ".$bet_code_row_val."<br/>"; break;
						case "tp2": $result_temp .= $obj->lang->line('all_bet_code_player_two_three_face')." : ".$bet_code_row_val."<br/>"; break;
						case "tp3": $result_temp .= $obj->lang->line('all_bet_code_player_three_three_face')." : ".$bet_code_row_val."<br/>"; break;
						case "pair1": $result_temp .= $obj->lang->line('all_bet_code_player_one_pair_plus')." : ".$bet_code_row_val."<br/>"; break;
						case "pair2": $result_temp .= $obj->lang->line('all_bet_code_player_two_pair_plus')." : ".$bet_code_row_val."<br/>"; break;
						case "pair3": $result_temp .= $obj->lang->line('all_bet_code_player_three_pair_plus')." : ".$bet_code_row_val."<br/>"; break;
						case "bankerPair": $result_temp .= $obj->lang->line('all_bet_code_banker_pair_all')." : ".$bet_code_row_val."<br/>"; break;
						case "bankerTp": $result_temp .= $obj->lang->line('all_bet_code_banker_pair_plus')." : ".$bet_code_row_val."<br/>"; break;
						default: $result_temp .= ""; break;
					}
				}
			}else if($game_real_code == "51"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					switch($bet_code_row_key)
					{
						case "big": $result_temp .= $obj->lang->line('all_bet_code_big')." : ".$bet_code_row_val."<br/>"; break;
						case "small": $result_temp .= $obj->lang->line('all_bet_code_small')." : ".$bet_code_row_val."<br/>"; break;
						case "odd": $result_temp .= $obj->lang->line('all_bet_code_odd')." : ".$bet_code_row_val."<br/>"; break;
						case "even": $result_temp .= $obj->lang->line('all_bet_code_even')." : ".$bet_code_row_val."<br/>"; break;
						case "dragon": $result_temp .= $obj->lang->line('all_bet_code_dragon')." : ".$bet_code_row_val."<br/>"; break;
						case "tiger": $result_temp .= $obj->lang->line('all_bet_code_tiger')." : ".$bet_code_row_val."<br/>"; break;
						case "tie": $result_temp .= $obj->lang->line('all_bet_code_tie')." : ".$bet_code_row_val."<br/>"; break;
						default: $result_temp .= ""; break;
					}
				}
			}else if($game_real_code == "52"){
				foreach($bet_code_array as $bet_code_row_key => $bet_code_row_val){
					switch($bet_code_row_key)
					{
						case "big": $result_temp .= $obj->lang->line('all_bet_code_big')." : ".$bet_code_row_val."<br/>"; break;
						case "small": $result_temp .= $obj->lang->line('all_bet_code_small')." : ".$bet_code_row_val."<br/>"; break;
						case "odd": $result_temp .= $obj->lang->line('all_bet_code_odd')." : ".$bet_code_row_val."<br/>"; break;
						case "even": $result_temp .= $obj->lang->line('all_bet_code_even')." : ".$bet_code_row_val."<br/>"; break;
						default: $result_temp .= ""; break;
					}
				}
			}else{
				$result_temp = "";
			}
		}
	}else if($game_type_code == GAME_OTHERS){
	}
	if(!empty($result_temp)){
		$result = $result_temp;
	}
	if(isset($result_info_array['id'])){
		$result .= $result_info_array['id'];
	}
	return $result;
}
function dg_game_result_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_real_code = (isset($result_info_array['gameId']) ? $result_info_array['gameId'] : "");
	$game_result = (isset($result_info_array['result']) ? $result_info_array['result'] : "");
	$result = "-";
	$result_temp = "";
	$banker_result = "";
	$player_result = "";
	$player2_result = "";
	$player3_result = "";
	$winning_seperator_result_arary = array();
	$winning_result_arary = array();
	$banker_result_arary = array();
	$player_result_arary = array();
	$suit = "";
	$rank = "";
	$temp_string = "";
	if($game_type_code == GAME_LIVE_CASINO){
		$game_result_array = json_decode($game_result,true, 512, JSON_BIGINT_AS_STRING);
		if(!empty($game_result_array)){
			if($game_real_code == "1"){
				if(!empty($game_result_array['result'])){
					$winning_result_arary = explode(',',$game_result_array['result']);
					if(!empty($winning_result_arary)){
						switch($winning_result_arary[0]){
							case "1": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_banker_win')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_banker_win_banker_pair')."<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_banker_win_player_pair')."<br />"; break;
							case "4": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_banker_win_banker_pair_player_pair')."<br />"; break;
							case "5": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_player_win')."<br />"; break;
							case "6": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_player_win_banker_pair')."<br />"; break;
							case "7": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_player_win_player_pair')."<br />"; break;
							case "8": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_player_win_banker_pair_player_pair')."<br />"; break;
							case "9": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tie_win')."<br />"; break;
							case "10": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tie_win_banker_pair')."<br />"; break;
							case "11": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tie_win_player_pair')."<br />"; break;
							case "12": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tie_win_banker_pair_player_pair')."<br />"; break;
							default: $result_temp .= ""; break;
						}
						switch($winning_result_arary[1]){
							case "-1": $result_temp .= $obj->lang->line('all_winning_card')." : ".$obj->lang->line('all_result_no_card')."<br />"; break;
							case "1": $result_temp .= $obj->lang->line('all_winning_card')." : ".$obj->lang->line('all_bet_code_big')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_winning_card')." : ".$obj->lang->line('all_bet_code_small')."<br />"; break;
							default: $result_temp .= ""; break;
						}
						$result_temp .= $obj->lang->line('all_winning_point')." : ".$winning_result_arary[2]."<br />";
					}
				}
				if(!empty($result_temp)){
					$result_temp .= "<br/>";
				}
				if(!empty($game_result_array['poker']['banker'])){
					$banker_result_arary = explode('-',$game_result_array['poker']['banker']);
					if(!empty($banker_result_arary)){
						$banker_result .= $obj->lang->line('all_bet_code_banker')." : ";
						foreach($banker_result_arary as $banker_result_arary_row){
							if($banker_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($banker_result_arary_row >= 1 && $banker_result_arary_row <= 13){
									$suit = "spades";
								}else if($banker_result_arary_row >= 14 && $banker_result_arary_row <= 26){
									$suit = "hearts";
								}else if($banker_result_arary_row >= 27 && $banker_result_arary_row <= 39){
									$suit = "clubs";
								}else if($banker_result_arary_row >= 40 && $banker_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($banker_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$banker_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$banker_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$banker_result .= '<span class="rank">'.$rank.'</span>';
											$banker_result .= '<span class="suit">&'.$suit.';</span>';
										$banker_result .= '</div>';
									$banker_result .= '</div>';
								}
							}
						}
					}
				}
				if(!empty($game_result_array['poker']['player'])){
					$player_result_arary = explode('-',$game_result_array['poker']['player']);
					if(!empty($player_result_arary)){
						$player_result .= $obj->lang->line('all_bet_code_player')." : ";
						foreach($player_result_arary as $player_result_arary_row){
							if($player_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($player_result_arary_row >= 1 && $player_result_arary_row <= 13){
									$suit = "spades";
								}else if($player_result_arary_row >= 14 && $player_result_arary_row <= 26){
									$suit = "hearts";
								}else if($player_result_arary_row >= 27 && $player_result_arary_row <= 39){
									$suit = "clubs";
								}else if($player_result_arary_row >= 40 && $player_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($player_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$player_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$player_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$player_result .= '<span class="rank">'.$rank.'</span>';
											$player_result .= '<span class="suit">&'.$suit.';</span>';
										$player_result .= '</div>';
									$player_result .= '</div>';
								}
							}
						}
					}
				}
				$result_temp .= $banker_result;
				if(!empty($player_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $player_result;
				}
			}else if($game_real_code == "41"){
				if(!empty($game_result_array['result'])){
					$winning_result_arary = explode(',',$game_result_array['result']);
					if(!empty($winning_result_arary)){
						switch($winning_result_arary[0]){
							case "1": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_banker_win')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_banker_win_banker_pair')."<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_banker_win_player_pair')."<br />"; break;
							case "4": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_banker_win_banker_pair_player_pair')."<br />"; break;
							case "5": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_player_win')."<br />"; break;
							case "6": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_player_win_banker_pair')."<br />"; break;
							case "7": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_player_win_player_pair')."<br />"; break;
							case "8": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_player_win_banker_pair_player_pair')."<br />"; break;
							case "9": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tie_win')."<br />"; break;
							case "10": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tie_win_banker_pair')."<br />"; break;
							case "11": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tie_win_player_pair')."<br />"; break;
							case "12": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tie_win_banker_pair_player_pair')."<br />"; break;
							default: $result_temp .= ""; break;
						}
						switch($winning_result_arary[1]){
							case "-1": $result_temp .= $obj->lang->line('all_winning_card')." : ".$obj->lang->line('all_result_no_card')."<br />"; break;
							case "1": $result_temp .= $obj->lang->line('all_winning_card')." : ".$obj->lang->line('all_bet_code_big')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_winning_card')." : ".$obj->lang->line('all_bet_code_small')."<br />"; break;
							default: $result_temp .= ""; break;
						}
						$result_temp .= $obj->lang->line('all_winning_point')." : ".$winning_result_arary[2]."<br />";
					}
				}
				if(!empty($result_temp)){
					$result_temp .= "<br/>";
				}
				if(!empty($game_result_array['poker']['banker'])){
					$banker_result_arary = explode('-',$game_result_array['poker']['banker']);
					if(!empty($banker_result_arary)){
						$banker_result .= $obj->lang->line('all_bet_code_banker')." : ";
						foreach($banker_result_arary as $banker_result_arary_row){
							if($banker_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($banker_result_arary_row >= 1 && $banker_result_arary_row <= 13){
									$suit = "spades";
								}else if($banker_result_arary_row >= 14 && $banker_result_arary_row <= 26){
									$suit = "hearts";
								}else if($banker_result_arary_row >= 27 && $banker_result_arary_row <= 39){
									$suit = "clubs";
								}else if($banker_result_arary_row >= 40 && $banker_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($banker_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$banker_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$banker_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$banker_result .= '<span class="rank">'.$rank.'</span>';
											$banker_result .= '<span class="suit">&'.$suit.';</span>';
										$banker_result .= '</div>';
									$banker_result .= '</div>';
								}
							}
						}
					}
				}
				if(!empty($game_result_array['poker']['player'])){
					$player_result_arary = explode('-',$game_result_array['poker']['player']);
					if(!empty($player_result_arary)){
						$player_result .= $obj->lang->line('all_bet_code_player')." : ";
						foreach($player_result_arary as $player_result_arary_row){
							if($player_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($player_result_arary_row >= 1 && $player_result_arary_row <= 13){
									$suit = "spades";
								}else if($player_result_arary_row >= 14 && $player_result_arary_row <= 26){
									$suit = "hearts";
								}else if($player_result_arary_row >= 27 && $player_result_arary_row <= 39){
									$suit = "clubs";
								}else if($player_result_arary_row >= 40 && $player_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($player_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$player_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$player_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$player_result .= '<span class="rank">'.$rank.'</span>';
											$player_result .= '<span class="suit">&'.$suit.';</span>';
										$player_result .= '</div>';
									$player_result .= '</div>';
								}
							}
						}
					}
				}
				$result_temp .= $banker_result;
				if(!empty($player_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $player_result;
				}
			}else if($game_real_code == "2"){
				if(!empty($game_result_array['result'])){
					$winning_result_arary = explode(',',$game_result_array['result']);
					if(!empty($winning_result_arary)){
						switch($winning_result_arary[0]){
							case "1": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_banker_win')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_banker_win_banker_pair')."<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_banker_win_player_pair')."<br />"; break;
							case "4": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_banker_win_banker_pair_player_pair')."<br />"; break;
							case "5": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_player_win')."<br />"; break;
							case "6": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_player_win_banker_pair')."<br />"; break;
							case "7": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_player_win_player_pair')."<br />"; break;
							case "8": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_player_win_banker_pair_player_pair')."<br />"; break;
							case "9": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tie_win')."<br />"; break;
							case "10": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tie_win_banker_pair')."<br />"; break;
							case "11": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tie_win_player_pair')."<br />"; break;
							case "12": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tie_win_banker_pair_player_pair')."<br />"; break;
							default: $result_temp .= ""; break;
						}
						switch($winning_result_arary[1]){
							case "-1": $result_temp .= $obj->lang->line('all_winning_card')." : ".$obj->lang->line('all_result_no_card')."<br />"; break;
							case "1": $result_temp .= $obj->lang->line('all_winning_card')." : ".$obj->lang->line('all_bet_code_big')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_winning_card')." : ".$obj->lang->line('all_bet_code_small')."<br />"; break;
							default: $result_temp .= ""; break;
						}
						$result_temp .= $obj->lang->line('all_winning_point')." : ".$winning_result_arary[2]."<br />";
					}
				}
				if(!empty($result_temp)){
					$result_temp .= "<br/>";
				}
				if(!empty($game_result_array['poker']['banker'])){
					$banker_result_arary = explode('-',$game_result_array['poker']['banker']);
					if(!empty($banker_result_arary)){
						$banker_result .= $obj->lang->line('all_bet_code_banker')." : ";
						foreach($banker_result_arary as $banker_result_arary_row){
							if($banker_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($banker_result_arary_row >= 1 && $banker_result_arary_row <= 13){
									$suit = "spades";
								}else if($banker_result_arary_row >= 14 && $banker_result_arary_row <= 26){
									$suit = "hearts";
								}else if($banker_result_arary_row >= 27 && $banker_result_arary_row <= 39){
									$suit = "clubs";
								}else if($banker_result_arary_row >= 40 && $banker_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($banker_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$banker_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$banker_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$banker_result .= '<span class="rank">'.$rank.'</span>';
											$banker_result .= '<span class="suit">&'.$suit.';</span>';
										$banker_result .= '</div>';
									$banker_result .= '</div>';
								}
							}
						}
					}
				}
				if(!empty($game_result_array['poker']['player'])){
					$player_result_arary = explode('-',$game_result_array['poker']['player']);
					if(!empty($player_result_arary)){
						$player_result .= $obj->lang->line('all_bet_code_player')." : ";
						foreach($player_result_arary as $player_result_arary_row){
							if($player_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($player_result_arary_row >= 1 && $player_result_arary_row <= 13){
									$suit = "spades";
								}else if($player_result_arary_row >= 14 && $player_result_arary_row <= 26){
									$suit = "hearts";
								}else if($player_result_arary_row >= 27 && $player_result_arary_row <= 39){
									$suit = "clubs";
								}else if($player_result_arary_row >= 40 && $player_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($player_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$player_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$player_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$player_result .= '<span class="rank">'.$rank.'</span>';
											$player_result .= '<span class="suit">&'.$suit.';</span>';
										$player_result .= '</div>';
									$player_result .= '</div>';
								}
							}
						}
					}
				}
				$result_temp .= $banker_result;
				if(!empty($player_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $player_result;
				}
			}else if($game_real_code == "3"){
				if(!empty($game_result_array['result'])){
					$winning_result_arary = explode(',',$game_result_array['result']);
					if(!empty($winning_result_arary)){
						switch($winning_result_arary[0]){
							case "-1": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_no_card')."<br />"; break;
							case "1": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_dragon_win')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tiger_win')."<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_draw_win')."<br />"; break;
							default: $result_temp .= ""; break;
						}
						$result_temp .= $obj->lang->line('all_winning_point')." : ".$winning_result_arary[1]."<br />";
					}
				}
				if(!empty($result_temp)){
					$result_temp .= "<br/>";
				}
				if(!empty($game_result_array['poker']['dragon'])){
					$banker_result_arary = explode('-',$game_result_array['poker']['dragon']);
					if(!empty($banker_result_arary)){
						$banker_result .= $obj->lang->line('all_bet_code_dragon')." : ";
						foreach($banker_result_arary as $banker_result_arary_row){
							if($banker_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($banker_result_arary_row >= 1 && $banker_result_arary_row <= 13){
									$suit = "spades";
								}else if($banker_result_arary_row >= 14 && $banker_result_arary_row <= 26){
									$suit = "hearts";
								}else if($banker_result_arary_row >= 27 && $banker_result_arary_row <= 39){
									$suit = "clubs";
								}else if($banker_result_arary_row >= 40 && $banker_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($banker_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$banker_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$banker_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$banker_result .= '<span class="rank">'.$rank.'</span>';
											$banker_result .= '<span class="suit">&'.$suit.';</span>';
										$banker_result .= '</div>';
									$banker_result .= '</div>';
								}
							}
						}
					}
				}
				if(!empty($game_result_array['poker']['tiger'])){
					$player_result_arary = explode('-',$game_result_array['poker']['tiger']);
					if(!empty($player_result_arary)){
						$player_result .= $obj->lang->line('all_bet_code_tiger')." : ";
						foreach($player_result_arary as $player_result_arary_row){
							if($player_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($player_result_arary_row >= 1 && $player_result_arary_row <= 13){
									$suit = "spades";
								}else if($player_result_arary_row >= 14 && $player_result_arary_row <= 26){
									$suit = "hearts";
								}else if($player_result_arary_row >= 27 && $player_result_arary_row <= 39){
									$suit = "clubs";
								}else if($player_result_arary_row >= 40 && $player_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($player_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$player_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$player_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$player_result .= '<span class="rank">'.$rank.'</span>';
											$player_result .= '<span class="suit">&'.$suit.';</span>';
										$player_result .= '</div>';
									$player_result .= '</div>';
								}
							}
						}
					}
				}
				$result_temp .= $banker_result;
				if(!empty($player_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $player_result;
				}
			}else if($game_real_code == "42"){
				if(!empty($game_result_array['result'])){
					$winning_result_arary = explode(',',$game_result_array['result']);
					if(!empty($winning_result_arary)){
						switch($winning_result_arary[0]){
							case "-1": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_no_card')."<br />"; break;
							case "1": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_dragon_win')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tiger_win')."<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_draw_win')."<br />"; break;
							default: $result_temp .= ""; break;
						}
						$result_temp .= $obj->lang->line('all_winning_point')." : ".$winning_result_arary[1]."<br />";
					}
				}
				if(!empty($result_temp)){
					$result_temp .= "<br/>";
				}
				if(!empty($game_result_array['poker']['dragon'])){
					$banker_result_arary = explode('-',$game_result_array['poker']['dragon']);
					if(!empty($banker_result_arary)){
						$banker_result .= $obj->lang->line('all_bet_code_dragon')." : ";
						foreach($banker_result_arary as $banker_result_arary_row){
							if($banker_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($banker_result_arary_row >= 1 && $banker_result_arary_row <= 13){
									$suit = "spades";
								}else if($banker_result_arary_row >= 14 && $banker_result_arary_row <= 26){
									$suit = "hearts";
								}else if($banker_result_arary_row >= 27 && $banker_result_arary_row <= 39){
									$suit = "clubs";
								}else if($banker_result_arary_row >= 40 && $banker_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($banker_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$banker_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$banker_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$banker_result .= '<span class="rank">'.$rank.'</span>';
											$banker_result .= '<span class="suit">&'.$suit.';</span>';
										$banker_result .= '</div>';
									$banker_result .= '</div>';
								}
							}
						}
					}
				}
				if(!empty($game_result_array['poker']['tiger'])){
					$player_result_arary = explode('-',$game_result_array['poker']['tiger']);
					if(!empty($player_result_arary)){
						$player_result .= $obj->lang->line('all_bet_code_tiger')." : ";
						foreach($player_result_arary as $player_result_arary_row){
							if($player_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($player_result_arary_row >= 1 && $player_result_arary_row <= 13){
									$suit = "spades";
								}else if($player_result_arary_row >= 14 && $player_result_arary_row <= 26){
									$suit = "hearts";
								}else if($player_result_arary_row >= 27 && $player_result_arary_row <= 39){
									$suit = "clubs";
								}else if($player_result_arary_row >= 40 && $player_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($player_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$player_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$player_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$player_result .= '<span class="rank">'.$rank.'</span>';
											$player_result .= '<span class="suit">&'.$suit.';</span>';
										$player_result .= '</div>';
									$player_result .= '</div>';
								}
							}
						}
					}
				}
				$result_temp .= $banker_result;
				if(!empty($player_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $player_result;
				}
			}else if($game_real_code == "4"){
				if(isset($game_result_array['result'])){
					$result_temp = $game_result_array['result'];
				}
			}else if($game_real_code == "5"){
				if(isset($game_result_array['result'])){
					$dice_one = substr($game_result_array['result'],0,1);
					$dice_two = substr($game_result_array['result'],1,1);
					$dice_three = substr($game_result_array['result'],2,1);
					$result_temp .= '<span class="dice dice-'.$dice_one.'" title="'.$obj->lang->line('all_bet_code_dice').' : '.$dice_one.'"></span>';
					$result_temp .= '<span class="dice dice-'.$dice_two.'" title="'.$obj->lang->line('all_bet_code_dice').' : '.$dice_two.'"></span>';
					$result_temp .= '<span class="dice dice-'.$dice_three.'" title="'.$obj->lang->line('all_bet_code_dice').' : '.$dice_three.'"></span>';
				}
			}else if($game_real_code == "6"){
				if(isset($game_result_array['result'])){
					$result_temp = $game_result_array['result'];
				}
			}else if($game_real_code == "7"){
				if(!empty($game_result_array['result'])){
					$winning_seperator_result_arary = explode('|',$game_result_array['result']);
					if(!empty($winning_seperator_result_arary)){
						$winning_result_arary = explode(',',$winning_seperator_result_arary[0]);
						if(!empty($winning_result_arary)){
							$result_temp .= $obj->lang->line('all_result_banker_point')." : ".$winning_result_arary[0]."<br />";
							$result_temp .= $obj->lang->line('all_result_player_one_point')." : ".$winning_result_arary[1]."<br />";
							$result_temp .= $obj->lang->line('all_result_player_two_point')." : ".$winning_result_arary[2]."<br />";
							$result_temp .= $obj->lang->line('all_result_player_three_point')." : ".$winning_result_arary[3]."<br />";
						}
						if(!empty($result_temp)){
							$result_temp .= "<br/>";
						}
						$winning_result_arary = explode(',',$winning_seperator_result_arary[1]);
						if($winning_result_arary[0]){
							$result_temp .= $obj->lang->line('all_bet_code_player_one_win')."<br />";
						}else{
							$result_temp .= $obj->lang->line('all_bet_code_player_one_lose')."<br />";
						}
						if($winning_result_arary[1]){
							$result_temp .= $obj->lang->line('all_bet_code_player_two_win')."<br />";
						}else{
							$result_temp .= $obj->lang->line('all_bet_code_player_two_lose')."<br />";
						}
						if($winning_result_arary[2]){
							$result_temp .= $obj->lang->line('all_bet_code_player_three_win')."<br />";
						}else{
							$result_temp .= $obj->lang->line('all_bet_code_player_three_lose')."<br />";
						}
						if(!empty($result_temp)){
							$result_temp .= "<br/>";
						}
						if(!empty($game_result_array['poker']['firstcard'])){
							$result_temp .= $obj->lang->line('all_result_first_card')." : ";
							if($game_result_array['poker']['firstcard'] >= 1 && $game_result_array['poker']['firstcard'] <= 13){
								$suit = "spades";
							}else if($game_result_array['poker']['firstcard'] >= 14 && $game_result_array['poker']['firstcard'] <= 26){
								$suit = "hearts";
							}else if($game_result_array['poker']['firstcard'] >= 27 && $game_result_array['poker']['firstcard'] <= 39){
								$suit = "clubs";
							}else if($game_result_array['poker']['firstcard'] >= 40 && $game_result_array['poker']['firstcard'] <= 52){
								$suit = "diams";
							}else{
								$suit = "";
							}
							if(!empty($suit)){
								switch($game_result_array['poker']['firstcard'] % 13)
								{
									case "1": $rank = "A"; break;
									case "2": $rank = "2"; break;
									case "3": $rank = "3"; break;
									case "4": $rank = "4"; break;
									case "5": $rank = "5"; break;
									case "6": $rank = "6"; break;
									case "7": $rank = "7"; break;
									case "8": $rank = "8"; break;
									case "9": $rank = "9"; break;
									case "10": $rank = "10"; break;
									case "11": $rank = "J"; break;
									case "12": $rank = "Q"; break;
									case "0": $rank = "K"; break;
									default: $rank = ""; break;
								}
								$result_temp .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
									$result_temp .= '<div class="card rank-'.$rank.' '.$suit.'">';
										$result_temp .= '<span class="rank">'.$rank.'</span>';
										$result_temp .= '<span class="suit">&'.$suit.';</span>';
									$result_temp .= '</div>';
								$result_temp .= '</div>';
							}
						}
					}
				}
				if(!empty($result_temp)){
					$result_temp .= "<br/>";
				}
				if(!empty($game_result_array['poker']['banker'])){
					$banker_result_arary = explode('-',$game_result_array['poker']['banker']);
					if(!empty($banker_result_arary)){
						$banker_result .= $obj->lang->line('all_bet_code_banker')." : ";
						foreach($banker_result_arary as $banker_result_arary_row){
							if($banker_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($banker_result_arary_row >= 1 && $banker_result_arary_row <= 13){
									$suit = "spades";
								}else if($banker_result_arary_row >= 14 && $banker_result_arary_row <= 26){
									$suit = "hearts";
								}else if($banker_result_arary_row >= 27 && $banker_result_arary_row <= 39){
									$suit = "clubs";
								}else if($banker_result_arary_row >= 40 && $banker_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($banker_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$banker_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$banker_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$banker_result .= '<span class="rank">'.$rank.'</span>';
											$banker_result .= '<span class="suit">&'.$suit.';</span>';
										$banker_result .= '</div>';
									$banker_result .= '</div>';
								}
							}
						}
					}
				}
				if(!empty($game_result_array['poker']['player1'])){
					$player_result_arary = explode('-',$game_result_array['poker']['player1']);
					if(!empty($player_result_arary)){
						$player_result .= $obj->lang->line('all_bet_code_player_one')." : ";
						foreach($player_result_arary as $player_result_arary_row){
							if($player_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($player_result_arary_row >= 1 && $player_result_arary_row <= 13){
									$suit = "spades";
								}else if($player_result_arary_row >= 14 && $player_result_arary_row <= 26){
									$suit = "hearts";
								}else if($player_result_arary_row >= 27 && $player_result_arary_row <= 39){
									$suit = "clubs";
								}else if($player_result_arary_row >= 40 && $player_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($player_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$player_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$player_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$player_result .= '<span class="rank">'.$rank.'</span>';
											$player_result .= '<span class="suit">&'.$suit.';</span>';
										$player_result .= '</div>';
									$player_result .= '</div>';
								}
							}
						}
					}
				}
				if(!empty($game_result_array['poker']['player2'])){
					$player_result_arary = explode('-',$game_result_array['poker']['player2']);
					if(!empty($player_result_arary)){
						$player2_result .= $obj->lang->line('all_bet_code_player_two')." : ";
						foreach($player_result_arary as $player_result_arary_row){
							if($player_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($player_result_arary_row >= 1 && $player_result_arary_row <= 13){
									$suit = "spades";
								}else if($player_result_arary_row >= 14 && $player_result_arary_row <= 26){
									$suit = "hearts";
								}else if($player_result_arary_row >= 27 && $player_result_arary_row <= 39){
									$suit = "clubs";
								}else if($player_result_arary_row >= 40 && $player_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($player_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$player2_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$player2_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$player2_result .= '<span class="rank">'.$rank.'</span>';
											$player2_result .= '<span class="suit">&'.$suit.';</span>';
										$player2_result .= '</div>';
									$player2_result .= '</div>';
								}
							}
						}
					}
				}
				if(!empty($game_result_array['poker']['player3'])){
					$player_result_arary = explode('-',$game_result_array['poker']['player3']);
					if(!empty($player_result_arary)){
						$player3_result .= $obj->lang->line('all_bet_code_player_three')." : ";
						foreach($player_result_arary as $player_result_arary_row){
							if($player_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($player_result_arary_row >= 1 && $player_result_arary_row <= 13){
									$suit = "spades";
								}else if($player_result_arary_row >= 14 && $player_result_arary_row <= 26){
									$suit = "hearts";
								}else if($player_result_arary_row >= 27 && $player_result_arary_row <= 39){
									$suit = "clubs";
								}else if($player_result_arary_row >= 40 && $player_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($player_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$player3_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$player3_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$player3_result .= '<span class="rank">'.$rank.'</span>';
											$player3_result .= '<span class="suit">&'.$suit.';</span>';
										$player3_result .= '</div>';
									$player3_result .= '</div>';
								}
							}
						}
					}
				}
				$result_temp .= $banker_result;
				if(!empty($player_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $player_result;
				}
				if(!empty($player2_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $player2_result;
				}
				if(!empty($player3_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $player3_result;
				}
			}else if($game_real_code == "8"){
				if(!empty($game_result_array['result'])){
					$winning_result_arary = explode(',',$game_result_array['result']);
					if(!empty($winning_result_arary)){
						switch($winning_result_arary[0]){
							case "1": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_banker_win')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_banker_win_banker_pair')."<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_banker_win_player_pair')."<br />"; break;
							case "4": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_banker_win_banker_pair_player_pair')."<br />"; break;
							case "5": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_player_win')."<br />"; break;
							case "6": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_player_win_banker_pair')."<br />"; break;
							case "7": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_player_win_player_pair')."<br />"; break;
							case "8": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_player_win_banker_pair_player_pair')."<br />"; break;
							case "9": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tie_win')."<br />"; break;
							case "10": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tie_win_banker_pair')."<br />"; break;
							case "11": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tie_win_player_pair')."<br />"; break;
							case "12": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_tie_win_banker_pair_player_pair')."<br />"; break;
							default: $result_temp .= ""; break;
						}
						switch($winning_result_arary[1]){
							case "-1": $result_temp .= $obj->lang->line('all_winning_card')." : ".$obj->lang->line('all_result_no_card')."<br />"; break;
							case "1": $result_temp .= $obj->lang->line('all_winning_card')." : ".$obj->lang->line('all_bet_code_big')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_winning_card')." : ".$obj->lang->line('all_bet_code_small')."<br />"; break;
							default: $result_temp .= ""; break;
						}
						$result_temp .= $obj->lang->line('all_winning_point')." : ".$winning_result_arary[2]."<br />";
					}
				}
				if(!empty($result_temp)){
					$result_temp .= "<br/>";
				}
				if(!empty($game_result_array['poker']['banker'])){
					$banker_result_arary = explode('-',$game_result_array['poker']['banker']);
					if(!empty($banker_result_arary)){
						$banker_result .= $obj->lang->line('all_bet_code_banker')." : ";
						foreach($banker_result_arary as $banker_result_arary_row){
							if($banker_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($banker_result_arary_row >= 1 && $banker_result_arary_row <= 13){
									$suit = "spades";
								}else if($banker_result_arary_row >= 14 && $banker_result_arary_row <= 26){
									$suit = "hearts";
								}else if($banker_result_arary_row >= 27 && $banker_result_arary_row <= 39){
									$suit = "clubs";
								}else if($banker_result_arary_row >= 40 && $banker_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($banker_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$banker_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$banker_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$banker_result .= '<span class="rank">'.$rank.'</span>';
											$banker_result .= '<span class="suit">&'.$suit.';</span>';
										$banker_result .= '</div>';
									$banker_result .= '</div>';
								}
							}
						}
					}
				}
				if(!empty($game_result_array['poker']['player'])){
					$player_result_arary = explode('-',$game_result_array['poker']['player']);
					if(!empty($player_result_arary)){
						$player_result .= $obj->lang->line('all_bet_code_player')." : ";
						foreach($player_result_arary as $player_result_arary_row){
							if($player_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($player_result_arary_row >= 1 && $player_result_arary_row <= 13){
									$suit = "spades";
								}else if($player_result_arary_row >= 14 && $player_result_arary_row <= 26){
									$suit = "hearts";
								}else if($player_result_arary_row >= 27 && $player_result_arary_row <= 39){
									$suit = "clubs";
								}else if($player_result_arary_row >= 40 && $player_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($player_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$player_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$player_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$player_result .= '<span class="rank">'.$rank.'</span>';
											$player_result .= '<span class="suit">&'.$suit.';</span>';
										$player_result .= '</div>';
									$player_result .= '</div>';
								}
							}
						}
					}
				}
				$result_temp .= $banker_result;
				if(!empty($player_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $player_result;
				}
			}else if($game_real_code == "11"){
				if(!empty($game_result_array['result'])){
					$winning_result_arary = explode(',',$game_result_array['result']);
					if(!empty($winning_result_arary)){
						switch($winning_result_arary[0]){
							case "1": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_black_win')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_red_win')."<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_bet_code_tie')."<br />"; break;
							default: $result_temp .= ""; break;
						}
						switch($winning_result_arary[1]){
							case "0": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$obj->lang->line('all_result_three_face_tie')."<br />"; break;
							case "1": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$obj->lang->line('all_result_three_face_high_card')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$obj->lang->line('all_result_three_face_pair')."<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$obj->lang->line('all_result_three_face_straight')."<br />"; break;
							case "4": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$obj->lang->line('all_result_three_face_flush')."<br />"; break;
							case "5": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$obj->lang->line('all_result_three_face_straight_flush')."<br />"; break;
							case "6": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$obj->lang->line('all_result_three_face_leopard')."<br />"; break;
							case "7": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$obj->lang->line('all_result_three_face_leopard_killer')."<br />"; break;
							default: $result_temp .= ""; break;
						}
						switch($winning_result_arary[2]){
							case "0": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$obj->lang->line('all_result_three_face_tie')."<br />"; break;
							case "1": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$obj->lang->line('all_result_three_face_high_card')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$obj->lang->line('all_result_three_face_pair')."<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$obj->lang->line('all_result_three_face_straight')."<br />"; break;
							case "4": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$obj->lang->line('all_result_three_face_flush')."<br />"; break;
							case "5": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$obj->lang->line('all_result_three_face_straight_flush')."<br />"; break;
							case "6": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$obj->lang->line('all_result_three_face_leopard')."<br />"; break;
							case "7": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$obj->lang->line('all_result_three_face_leopard_killer')."<br />"; break;
							default: $result_temp .= ""; break;
						}
						switch($winning_result_arary[3]){
							case "2": $result_temp .= $obj->lang->line('all_result_black_max')." : 2<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_result_black_max')." : 3<br />"; break;
							case "4": $result_temp .= $obj->lang->line('all_result_black_max')." : 4<br />"; break;
							case "5": $result_temp .= $obj->lang->line('all_result_black_max')." : 5<br />"; break;
							case "6": $result_temp .= $obj->lang->line('all_result_black_max')." : 6<br />"; break;
							case "7": $result_temp .= $obj->lang->line('all_result_black_max')." : 7<br />"; break;
							case "8": $result_temp .= $obj->lang->line('all_result_black_max')." : 8<br />"; break;
							case "9": $result_temp .= $obj->lang->line('all_result_black_max')." : 9<br />"; break;
							case "10": $result_temp .= $obj->lang->line('all_result_black_max')." : 10<br />"; break;
							case "11": $result_temp .= $obj->lang->line('all_result_black_max')." : J<br />"; break;
							case "12": $result_temp .= $obj->lang->line('all_result_black_max')." : Q<br />"; break;
							case "13": $result_temp .= $obj->lang->line('all_result_black_max')." : K<br />"; break;
							case "14": $result_temp .= $obj->lang->line('all_result_black_max')." : A<br />"; break;
							default: $result_temp .= ""; break;
						}
						switch($winning_result_arary[4]){
							case "2": $result_temp .= $obj->lang->line('all_result_red_max')." : 2<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_result_red_max')." : 3<br />"; break;
							case "4": $result_temp .= $obj->lang->line('all_result_red_max')." : 4<br />"; break;
							case "5": $result_temp .= $obj->lang->line('all_result_red_max')." : 5<br />"; break;
							case "6": $result_temp .= $obj->lang->line('all_result_red_max')." : 6<br />"; break;
							case "7": $result_temp .= $obj->lang->line('all_result_red_max')." : 7<br />"; break;
							case "8": $result_temp .= $obj->lang->line('all_result_red_max')." : 8<br />"; break;
							case "9": $result_temp .= $obj->lang->line('all_result_red_max')." : 9<br />"; break;
							case "10": $result_temp .= $obj->lang->line('all_result_red_max')." : 10<br />"; break;
							case "11": $result_temp .= $obj->lang->line('all_result_red_max')." : J<br />"; break;
							case "12": $result_temp .= $obj->lang->line('all_result_red_max')." : Q<br />"; break;
							case "13": $result_temp .= $obj->lang->line('all_result_red_max')." : K<br />"; break;
							case "14": $result_temp .= $obj->lang->line('all_result_red_max')." : A<br />"; break;
							default: $result_temp .= ""; break;
						}
						switch($winning_result_arary[5]){
							case "0": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_three_face_tie')."<br />"; break;
							case "1": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_three_face_high_card')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_three_face_pair')."<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_three_face_straight')."<br />"; break;
							case "4": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_three_face_flush')."<br />"; break;
							case "5": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_three_face_straight_flush')."<br />"; break;
							case "6": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_three_face_leopard')."<br />"; break;
							case "7": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_three_face_leopard_killer')."<br />"; break;
							default: $result_temp .= ""; break;
						}
					}
				}
				if(!empty($result_temp)){
					$result_temp .= "<br/>";
				}
				if(!empty($game_result_array['poker']['black'])){
					$banker_result_arary = explode('-',$game_result_array['poker']['black']);
					if(!empty($banker_result_arary)){
						$banker_result .= $obj->lang->line('all_bet_code_black')." : ";
						foreach($banker_result_arary as $banker_result_arary_row){
							if($banker_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($banker_result_arary_row >= 1 && $banker_result_arary_row <= 13){
									$suit = "spades";
								}else if($banker_result_arary_row >= 14 && $banker_result_arary_row <= 26){
									$suit = "hearts";
								}else if($banker_result_arary_row >= 27 && $banker_result_arary_row <= 39){
									$suit = "clubs";
								}else if($banker_result_arary_row >= 40 && $banker_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($banker_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$banker_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$banker_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$banker_result .= '<span class="rank">'.$rank.'</span>';
											$banker_result .= '<span class="suit">&'.$suit.';</span>';
										$banker_result .= '</div>';
									$banker_result .= '</div>';
								}
							}
						}
					}
				}
				if(!empty($game_result_array['poker']['red'])){
					$player_result_arary = explode('-',$game_result_array['poker']['red']);
					if(!empty($player_result_arary)){
						$player_result .= $obj->lang->line('all_bet_code_red')." : ";
						foreach($player_result_arary as $player_result_arary_row){
							if($player_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($player_result_arary_row >= 1 && $player_result_arary_row <= 13){
									$suit = "spades";
								}else if($player_result_arary_row >= 14 && $player_result_arary_row <= 26){
									$suit = "hearts";
								}else if($player_result_arary_row >= 27 && $player_result_arary_row <= 39){
									$suit = "clubs";
								}else if($player_result_arary_row >= 40 && $player_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($player_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$player_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$player_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$player_result .= '<span class="rank">'.$rank.'</span>';
											$player_result .= '<span class="suit">&'.$suit.';</span>';
										$player_result .= '</div>';
									$player_result .= '</div>';
								}
							}
						}
					}
				}
				$result_temp .= $banker_result;
				if(!empty($player_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $player_result;
				}
			}else if($game_real_code == "43"){
				if(!empty($game_result_array['result'])){
					$winning_result_arary = explode(',',$game_result_array['result']);
					if(!empty($winning_result_arary)){
						switch($winning_result_arary[0]){
							case "1": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_black_win')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_red_win')."<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_bet_code_tie')."<br />"; break;
							default: $result_temp .= ""; break;
						}
						switch($winning_result_arary[1]){
							case "0": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$obj->lang->line('all_result_three_face_tie')."<br />"; break;
							case "1": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$obj->lang->line('all_result_three_face_high_card')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$obj->lang->line('all_result_three_face_pair')."<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$obj->lang->line('all_result_three_face_straight')."<br />"; break;
							case "4": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$obj->lang->line('all_result_three_face_flush')."<br />"; break;
							case "5": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$obj->lang->line('all_result_three_face_straight_flush')."<br />"; break;
							case "6": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$obj->lang->line('all_result_three_face_leopard')."<br />"; break;
							case "7": $result_temp .= $obj->lang->line('all_bet_code_black')." : ".$obj->lang->line('all_result_three_face_leopard_killer')."<br />"; break;
							default: $result_temp .= ""; break;
						}
						switch($winning_result_arary[2]){
							case "0": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$obj->lang->line('all_result_three_face_tie')."<br />"; break;
							case "1": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$obj->lang->line('all_result_three_face_high_card')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$obj->lang->line('all_result_three_face_pair')."<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$obj->lang->line('all_result_three_face_straight')."<br />"; break;
							case "4": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$obj->lang->line('all_result_three_face_flush')."<br />"; break;
							case "5": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$obj->lang->line('all_result_three_face_straight_flush')."<br />"; break;
							case "6": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$obj->lang->line('all_result_three_face_leopard')."<br />"; break;
							case "7": $result_temp .= $obj->lang->line('all_bet_code_red')." : ".$obj->lang->line('all_result_three_face_leopard_killer')."<br />"; break;
							default: $result_temp .= ""; break;
						}
						switch($winning_result_arary[3]){
							case "2": $result_temp .= $obj->lang->line('all_result_black_max')." : 2<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_result_black_max')." : 3<br />"; break;
							case "4": $result_temp .= $obj->lang->line('all_result_black_max')." : 4<br />"; break;
							case "5": $result_temp .= $obj->lang->line('all_result_black_max')." : 5<br />"; break;
							case "6": $result_temp .= $obj->lang->line('all_result_black_max')." : 6<br />"; break;
							case "7": $result_temp .= $obj->lang->line('all_result_black_max')." : 7<br />"; break;
							case "8": $result_temp .= $obj->lang->line('all_result_black_max')." : 8<br />"; break;
							case "9": $result_temp .= $obj->lang->line('all_result_black_max')." : 9<br />"; break;
							case "10": $result_temp .= $obj->lang->line('all_result_black_max')." : 10<br />"; break;
							case "11": $result_temp .= $obj->lang->line('all_result_black_max')." : J<br />"; break;
							case "12": $result_temp .= $obj->lang->line('all_result_black_max')." : Q<br />"; break;
							case "13": $result_temp .= $obj->lang->line('all_result_black_max')." : K<br />"; break;
							case "14": $result_temp .= $obj->lang->line('all_result_black_max')." : A<br />"; break;
							default: $result_temp .= ""; break;
						}
						switch($winning_result_arary[4]){
							case "2": $result_temp .= $obj->lang->line('all_result_red_max')." : 2<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_result_red_max')." : 3<br />"; break;
							case "4": $result_temp .= $obj->lang->line('all_result_red_max')." : 4<br />"; break;
							case "5": $result_temp .= $obj->lang->line('all_result_red_max')." : 5<br />"; break;
							case "6": $result_temp .= $obj->lang->line('all_result_red_max')." : 6<br />"; break;
							case "7": $result_temp .= $obj->lang->line('all_result_red_max')." : 7<br />"; break;
							case "8": $result_temp .= $obj->lang->line('all_result_red_max')." : 8<br />"; break;
							case "9": $result_temp .= $obj->lang->line('all_result_red_max')." : 9<br />"; break;
							case "10": $result_temp .= $obj->lang->line('all_result_red_max')." : 10<br />"; break;
							case "11": $result_temp .= $obj->lang->line('all_result_red_max')." : J<br />"; break;
							case "12": $result_temp .= $obj->lang->line('all_result_red_max')." : Q<br />"; break;
							case "13": $result_temp .= $obj->lang->line('all_result_red_max')." : K<br />"; break;
							case "14": $result_temp .= $obj->lang->line('all_result_red_max')." : A<br />"; break;
							default: $result_temp .= ""; break;
						}
						switch($winning_result_arary[5]){
							case "0": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_three_face_tie')."<br />"; break;
							case "1": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_three_face_high_card')."<br />"; break;
							case "2": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_three_face_pair')."<br />"; break;
							case "3": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_three_face_straight')."<br />"; break;
							case "4": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_three_face_flush')."<br />"; break;
							case "5": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_three_face_straight_flush')."<br />"; break;
							case "6": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_three_face_leopard')."<br />"; break;
							case "7": $result_temp .= $obj->lang->line('all_winning_result')." : ".$obj->lang->line('all_result_three_face_leopard_killer')."<br />"; break;
							default: $result_temp .= ""; break;
						}
					}
				}
				if(!empty($result_temp)){
					$result_temp .= "<br/>";
				}
				if(!empty($game_result_array['poker']['black'])){
					$banker_result_arary = explode('-',$game_result_array['poker']['black']);
					if(!empty($banker_result_arary)){
						$banker_result .= $obj->lang->line('all_bet_code_black')." : ";
						foreach($banker_result_arary as $banker_result_arary_row){
							if($banker_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($banker_result_arary_row >= 1 && $banker_result_arary_row <= 13){
									$suit = "spades";
								}else if($banker_result_arary_row >= 14 && $banker_result_arary_row <= 26){
									$suit = "hearts";
								}else if($banker_result_arary_row >= 27 && $banker_result_arary_row <= 39){
									$suit = "clubs";
								}else if($banker_result_arary_row >= 40 && $banker_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($banker_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$banker_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$banker_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$banker_result .= '<span class="rank">'.$rank.'</span>';
											$banker_result .= '<span class="suit">&'.$suit.';</span>';
										$banker_result .= '</div>';
									$banker_result .= '</div>';
								}
							}
						}
					}
				}
				if(!empty($game_result_array['poker']['red'])){
					$player_result_arary = explode('-',$game_result_array['poker']['red']);
					if(!empty($player_result_arary)){
						$player_result .= $obj->lang->line('all_bet_code_red')." : ";
						foreach($player_result_arary as $player_result_arary_row){
							if($player_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($player_result_arary_row >= 1 && $player_result_arary_row <= 13){
									$suit = "spades";
								}else if($player_result_arary_row >= 14 && $player_result_arary_row <= 26){
									$suit = "hearts";
								}else if($player_result_arary_row >= 27 && $player_result_arary_row <= 39){
									$suit = "clubs";
								}else if($player_result_arary_row >= 40 && $player_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($player_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$player_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$player_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$player_result .= '<span class="rank">'.$rank.'</span>';
											$player_result .= '<span class="suit">&'.$suit.';</span>';
										$player_result .= '</div>';
									$player_result .= '</div>';
								}
							}
						}
					}
				}
				$result_temp .= $banker_result;
				if(!empty($player_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $player_result;
				}
			}else if($game_real_code == "12"){
				if(isset($game_result_array['result'])){
					$result_temp = $game_result_array['result'];
				}
			}else if($game_real_code == "14"){
				if(isset($game_result_array['result'])){
					switch($game_result_array['result'])
					{
						case "0": $result_temp .= $obj->lang->line('all_bet_code_four_white')." : ".$bet_code_row_val."<br/>"; break;
						case "1": $result_temp .= $obj->lang->line('all_bet_code_three_white_one_red')." : ".$bet_code_row_val."<br/>"; break;
						case "2": $result_temp .= $obj->lang->line('all_bet_code_two_red_two_white')." : ".$bet_code_row_val."<br/>"; break;
						case "3": $result_temp .= $obj->lang->line('all_bet_code_three_red_one_white')." : ".$bet_code_row_val."<br/>"; break;
						case "4": $result_temp .= $obj->lang->line('all_bet_code_four_red')." : ".$bet_code_row_val."<br/>"; break;
						case "single": $result_temp .= $obj->lang->line('all_bet_code_odd')." : ".$bet_code_row_val."<br/>"; break;
						case "double": $result_temp .= $obj->lang->line('all_bet_code_even')." : ".$bet_code_row_val."<br/>"; break;
						default: $result_temp .= ""; break;
					}
				}
			}else if($game_real_code == "15"){
				$result_temp .= "";
			}else if($game_real_code == "16"){
				if(!empty($game_result_array['result'])){
					$winning_seperator_result_arary = explode('|',$game_result_array['result']);
					if(!empty($winning_seperator_result_arary)){
						$winning_result_arary = explode(',',$winning_seperator_result_arary[0]);
						if(!empty($winning_result_arary)){
							switch($winning_result_arary[0])
							{
								case "1P1": $result_temp .= $obj->lang->line('all_result_banker_point')." : ".$obj->lang->line('all_result_single_face')."<br />"; break;
								case "3P0": $result_temp .= $obj->lang->line('all_result_banker_point')." : ".$obj->lang->line('all_result_three_face')."<br />"; break;
								default: $result_temp .= $obj->lang->line('all_result_banker_point')." : ".$winning_result_arary[0]."<br />"; break;
							}
							switch($winning_result_arary[1])
							{
								case "1P1": $result_temp .= $obj->lang->line('all_result_player_one_point')." : ".$obj->lang->line('all_result_single_face')."<br />"; break;
								case "3P0": $result_temp .= $obj->lang->line('all_result_player_one_point')." : ".$obj->lang->line('all_result_three_face')."<br />"; break;
								default: $result_temp .= $obj->lang->line('all_result_player_one_point')." : ".$winning_result_arary[1]."<br />"; break;
							}
							switch($winning_result_arary[2])
							{
								case "1P1": $result_temp .= $obj->lang->line('all_result_player_two_point')." : ".$obj->lang->line('all_result_single_face')."<br />"; break;
								case "3P0": $result_temp .= $obj->lang->line('all_result_player_two_point')." : ".$obj->lang->line('all_result_three_face')."<br />"; break;
								default: $result_temp .= $obj->lang->line('all_result_player_two_point')." : ".$winning_result_arary[2]."<br />"; break;
							}
							switch($winning_result_arary[3])
							{
								case "1P1": $result_temp .= $obj->lang->line('all_result_player_three_point')." : ".$obj->lang->line('all_result_single_face')."<br />"; break;
								case "3P0": $result_temp .= $obj->lang->line('all_result_player_three_point')." : ".$obj->lang->line('all_result_three_face')."<br />"; break;
								default: $result_temp .= $obj->lang->line('all_result_player_three_point')." : ".$winning_result_arary[3]."<br />"; break;
							}
						}
						if(!empty($result_temp)){
							$result_temp .= "<br/>";
						}
						$winning_result_arary = explode(',',$winning_seperator_result_arary[1]);
						if($winning_result_arary[0]){
							$result_temp .= $obj->lang->line('all_bet_code_player_one_win')."<br />";
						}else{
							$result_temp .= $obj->lang->line('all_bet_code_player_one_lose')."<br />";
						}
						if($winning_result_arary[1]){
							$result_temp .= $obj->lang->line('all_bet_code_player_two_win')."<br />";
						}else{
							$result_temp .= $obj->lang->line('all_bet_code_player_two_lose')."<br />";
						}
						if($winning_result_arary[2]){
							$result_temp .= $obj->lang->line('all_bet_code_player_three_win')."<br />";
						}else{
							$result_temp .= $obj->lang->line('all_bet_code_player_three_lose')."<br />";
						}
						if(!empty($result_temp)){
							$result_temp .= "<br/>";
						}
						if(!empty($game_result_array['poker']['firstcard'])){
							$result_temp .= $obj->lang->line('all_result_first_card')." : ";
							if($game_result_array['poker']['firstcard'] >= 1 && $game_result_array['poker']['firstcard'] <= 13){
								$suit = "spades";
							}else if($game_result_array['poker']['firstcard'] >= 14 && $game_result_array['poker']['firstcard'] <= 26){
								$suit = "hearts";
							}else if($game_result_array['poker']['firstcard'] >= 27 && $game_result_array['poker']['firstcard'] <= 39){
								$suit = "clubs";
							}else if($game_result_array['poker']['firstcard'] >= 40 && $game_result_array['poker']['firstcard'] <= 52){
								$suit = "diams";
							}else{
								$suit = "";
							}
							if(!empty($suit)){
								switch($game_result_array['poker']['firstcard'] % 13)
								{
									case "1": $rank = "A"; break;
									case "2": $rank = "2"; break;
									case "3": $rank = "3"; break;
									case "4": $rank = "4"; break;
									case "5": $rank = "5"; break;
									case "6": $rank = "6"; break;
									case "7": $rank = "7"; break;
									case "8": $rank = "8"; break;
									case "9": $rank = "9"; break;
									case "10": $rank = "10"; break;
									case "11": $rank = "J"; break;
									case "12": $rank = "Q"; break;
									case "0": $rank = "K"; break;
									default: $rank = ""; break;
								}
								$result_temp .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
									$result_temp .= '<div class="card rank-'.$rank.' '.$suit.'">';
										$result_temp .= '<span class="rank">'.$rank.'</span>';
										$result_temp .= '<span class="suit">&'.$suit.';</span>';
									$result_temp .= '</div>';
								$result_temp .= '</div>';
							}
						}
					}
				}
				if(!empty($result_temp)){
					$result_temp .= "<br/>";
				}
				if(!empty($game_result_array['poker']['banker'])){
					$banker_result_arary = explode('-',$game_result_array['poker']['banker']);
					if(!empty($banker_result_arary)){
						$banker_result .= $obj->lang->line('all_bet_code_banker')." : ";
						foreach($banker_result_arary as $banker_result_arary_row){
							if($banker_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($banker_result_arary_row >= 1 && $banker_result_arary_row <= 13){
									$suit = "spades";
								}else if($banker_result_arary_row >= 14 && $banker_result_arary_row <= 26){
									$suit = "hearts";
								}else if($banker_result_arary_row >= 27 && $banker_result_arary_row <= 39){
									$suit = "clubs";
								}else if($banker_result_arary_row >= 40 && $banker_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($banker_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$banker_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$banker_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$banker_result .= '<span class="rank">'.$rank.'</span>';
											$banker_result .= '<span class="suit">&'.$suit.';</span>';
										$banker_result .= '</div>';
									$banker_result .= '</div>';
								}
							}
						}
					}
				}
				if(!empty($game_result_array['poker']['player1'])){
					$player_result_arary = explode('-',$game_result_array['poker']['player1']);
					if(!empty($player_result_arary)){
						$player_result .= $obj->lang->line('all_bet_code_player_one')." : ";
						foreach($player_result_arary as $player_result_arary_row){
							if($player_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($player_result_arary_row >= 1 && $player_result_arary_row <= 13){
									$suit = "spades";
								}else if($player_result_arary_row >= 14 && $player_result_arary_row <= 26){
									$suit = "hearts";
								}else if($player_result_arary_row >= 27 && $player_result_arary_row <= 39){
									$suit = "clubs";
								}else if($player_result_arary_row >= 40 && $player_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($player_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$player_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$player_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$player_result .= '<span class="rank">'.$rank.'</span>';
											$player_result .= '<span class="suit">&'.$suit.';</span>';
										$player_result .= '</div>';
									$player_result .= '</div>';
								}
							}
						}
					}
				}
				if(!empty($game_result_array['poker']['player2'])){
					$player_result_arary = explode('-',$game_result_array['poker']['player2']);
					if(!empty($player_result_arary)){
						$player2_result .= $obj->lang->line('all_bet_code_player_two')." : ";
						foreach($player_result_arary as $player_result_arary_row){
							if($player_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($player_result_arary_row >= 1 && $player_result_arary_row <= 13){
									$suit = "spades";
								}else if($player_result_arary_row >= 14 && $player_result_arary_row <= 26){
									$suit = "hearts";
								}else if($player_result_arary_row >= 27 && $player_result_arary_row <= 39){
									$suit = "clubs";
								}else if($player_result_arary_row >= 40 && $player_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($player_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$player2_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$player2_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$player2_result .= '<span class="rank">'.$rank.'</span>';
											$player2_result .= '<span class="suit">&'.$suit.';</span>';
										$player2_result .= '</div>';
									$player2_result .= '</div>';
								}
							}
						}
					}
				}
				if(!empty($game_result_array['poker']['player3'])){
					$player_result_arary = explode('-',$game_result_array['poker']['player3']);
					if(!empty($player_result_arary)){
						$player3_result .= $obj->lang->line('all_bet_code_player_three')." : ";
						foreach($player_result_arary as $player_result_arary_row){
							if($player_result_arary_row > 0){
								$suit = "";
								$rank = "";
								if($player_result_arary_row >= 1 && $player_result_arary_row <= 13){
									$suit = "spades";
								}else if($player_result_arary_row >= 14 && $player_result_arary_row <= 26){
									$suit = "hearts";
								}else if($player_result_arary_row >= 27 && $player_result_arary_row <= 39){
									$suit = "clubs";
								}else if($player_result_arary_row >= 40 && $player_result_arary_row <= 52){
									$suit = "diams";
								}else{
									$suit = "";
								}
								if(!empty($suit)){
									switch($player_result_arary_row % 13)
									{
										case "1": $rank = "A"; break;
										case "2": $rank = "2"; break;
										case "3": $rank = "3"; break;
										case "4": $rank = "4"; break;
										case "5": $rank = "5"; break;
										case "6": $rank = "6"; break;
										case "7": $rank = "7"; break;
										case "8": $rank = "8"; break;
										case "9": $rank = "9"; break;
										case "10": $rank = "10"; break;
										case "11": $rank = "J"; break;
										case "12": $rank = "Q"; break;
										case "0": $rank = "K"; break;
										default: $rank = ""; break;
									}
									$player3_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
										$player3_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
											$player3_result .= '<span class="rank">'.$rank.'</span>';
											$player3_result .= '<span class="suit">&'.$suit.';</span>';
										$player3_result .= '</div>';
									$player3_result .= '</div>';
								}
							}
						}
					}
				}
				$result_temp .= $banker_result;
				if(!empty($player_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $player_result;
				}
				if(!empty($player2_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $player2_result;
				}
				if(!empty($player3_result)){
					if(!empty($result_temp)){
						$result_temp .= "<br/>";
					}
					$result_temp .= $player3_result;
				}
			}else if($game_real_code == "51"){
				if(isset($game_result_array['result'])){
					$result_temp .= $game_result_array['result'];
				}
			}else if($game_real_code == "52"){
				if(isset($game_result_array['result'])){
					$result_temp .= $game_result_array['result'];
				}
			}else{
				$result_temp = "";
			}
		}
	}else if($game_type_code == GAME_OTHERS){
	}
	if(!empty($result_temp)){
		$result = $result_temp;
	}
	return $result;
}
function dt_game_code_decision($game_type_code = NULL,$result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_id = (isset($result_info_array['gameCode']) ? $result_info_array['gameCode'] : "");
	$result = $obj->lang->line('slot_game_dt_'.$game_id);
	return $result;
}
function dt_bet_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$transaction_id = (isset($result_info_array['id']) ? $result_info_array['id'] : "");
	$result = $obj->lang->line('all_result_bet_id').": ".$transaction_id."<br>";
	return $result;
}
function evop_game_code_decision($game_type_code = NULL,$result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_id = (isset($result_info_array['game_id']) ? $result_info_array['game_id'] : "");
	$result = $obj->lang->line('slot_game_evop_'.$game_id);
	return $result;
}
function evop_bet_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$transaction_id = (isset($result_info_array['round_id']) ? $result_info_array['round_id'] : "");
	$result = $obj->lang->line('all_result_bet_id').": ".$transaction_id."<br>";
	return $result;
}
function gfgd_game_code_decision($game_type_code = NULL,$result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_id = (isset($result_info_array[0]['game_code']) ? $result_info_array[0]['game_code'] : "");
	$result = $obj->lang->line('slot_game_gfgd_'.$game_id);
	return $result;
}
function gfgd_bet_code_decision($game_type_code = NULL,$result_info = NULL){
    $obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$transaction_id = (isset($result_info_array[0]['parent_bet_id']) ? $result_info_array[0]['parent_bet_id'] : "");
	$result = $obj->lang->line('all_result_bet_id').": ".$transaction_id."<br>";
	return $result;
}
function gr_game_code_decision($game_type_code = NULL,$result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_id = (isset($result_info_array['game_type']) ? $result_info_array['game_type'] : "");
	$result = $obj->lang->line('slot_game_gr_'.$game_id);
	return $result;
}
function gr_bet_code_decision($game_type_code = NULL,$result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_module_type = (isset($result_info_array['game_module_type']) ? $result_info_array['game_module_type'] : "");
	$result = "-";
	if($game_module_type == "1"){
		$room_id = (isset($result_info_array['room_id']) ? $result_info_array['room_id'] : "");
		$table_id = (isset($result_info_array['table_id']) ? $result_info_array['table_id'] : "");
		$round_id = (isset($result_info_array['game_round']) ? $result_info_array['game_round'] : "");
		$transaction_id = (isset($result_info_array['id_str']) ? $result_info_array['id_str'] : "");
		$order_id = (isset($result_info_array['order_id']) ? $result_info_array['order_id'] : "");
		$result = $obj->lang->line('all_result_round').":".$room_id."-".$table_id."-".$round_id."<br>";
		$result .= $obj->lang->line('all_result_bet_id').": ".$transaction_id."<br>";
		$result .= $obj->lang->line('all_game_bet_code_type').": ".$obj->lang->line('all_game_bet_code_boardgame_hundred_boardgame')."<br>";
		//$result .= "Order ID".": ".$order_id."<br>";
		if(!empty($room_id)){
			//$result .= $obj->lang->line('all_result_room_id').": ".$room_id."<br>";
		}
		if(!empty($table_id)){
			//$result .= $obj->lang->line('all_result_table_id').": ".$table_id."<br>";
		}
	}else if($game_module_type == "2"){
		$room_id = (isset($result_info_array['room_id']) ? $result_info_array['room_id'] : "");
		$table_id = (isset($result_info_array['table_id']) ? $result_info_array['table_id'] : "");
		$round_id = (isset($result_info_array['game_round']) ? $result_info_array['game_round'] : "");
		$transaction_id = (isset($result_info_array['id_str']) ? $result_info_array['id_str'] : "");
		$order_id = (isset($result_info_array['order_id']) ? $result_info_array['order_id'] : "");
		$result = $obj->lang->line('all_result_round').":".$room_id."-".$table_id."-".$round_id."<br>";
		$result .= $obj->lang->line('all_result_bet_id').": ".$transaction_id."<br>";
		$result .= $obj->lang->line('all_game_bet_code_type').": ".$obj->lang->line('all_game_bet_code_boardgame_versus')."<br>";
		//$result .= "Order ID".": ".$order_id."<br>";
		if(!empty($room_id)){
			//$result .= $obj->lang->line('all_result_room_id').": ".$room_id."<br>";
		}
		if(!empty($table_id)){
			//$result .= $obj->lang->line('all_result_table_id').": ".$table_id."<br>";
		}
	}else if($game_module_type == "3"){
		$room_id = (isset($result_info_array['room_id']) ? $result_info_array['room_id'] : "");
		$table_id = (isset($result_info_array['table_id']) ? $result_info_array['table_id'] : "");
		$round_id = (isset($result_info_array['game_round']) ? $result_info_array['game_round'] : "");
		$transaction_id = (isset($result_info_array['id_str']) ? $result_info_array['id_str'] : "");
		$order_id = (isset($result_info_array['order_id']) ? $result_info_array['order_id'] : "");
		$result = $obj->lang->line('all_result_round').":".$round_id."<br>";
		$result .= $obj->lang->line('all_result_bet_id').": ".$transaction_id."<br>";
		//$result .= "Order ID".": ".$order_id."<br>";
		if(!empty($room_id)){
			//$result .= $obj->lang->line('all_result_room_id').": ".$room_id."<br>";
		}
		if(!empty($table_id)){
			//$result .= $obj->lang->line('all_result_table_id').": ".$table_id."<br>";
		}
	}else if($game_module_type == "4"){
		$room_id = (isset($result_info_array['room_id']) ? $result_info_array['room_id'] : "");
		$table_id = (isset($result_info_array['table_id']) ? $result_info_array['table_id'] : "");
		$round_id = (isset($result_info_array['game_round']) ? $result_info_array['game_round'] : "");
		$transaction_id = (isset($result_info_array['id_str']) ? $result_info_array['id_str'] : "");
		$order_id = (isset($result_info_array['order_id']) ? $result_info_array['order_id'] : "");
		$result = $obj->lang->line('all_result_round').":".$round_id."<br>";
		$result .= $obj->lang->line('all_result_bet_id').": ".$transaction_id."<br>";
		//$result .= "Order ID".": ".$order_id."<br>";
		if(!empty($room_id)){
			//$result .= $obj->lang->line('all_result_room_id').": ".$room_id."<br>";
		}
		if(!empty($table_id)){
			//$result .= $obj->lang->line('all_result_table_id').": ".$table_id."<br>";
		}
	}else{
	}
	return $result;
}
function gr_game_result_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$order_id = (isset($result_info_array['order_id']) ? $result_info_array['order_id'] : "");
	$result .= "Order ID".": ".$order_id."<br>";
	return $result;
}
function icg_game_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_id = (isset($result_info_array['productId']) ? $result_info_array['productId'] : "");
	$result = $obj->lang->line('slot_game_icg_'.$game_id);
	return $result;
}
function icg_bet_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$transaction_id = (isset($result_info_array['id']) ? $result_info_array['id'] : "");
	$result = $obj->lang->line('all_result_bet_id').": ".$transaction_id."<br>";
	return $result;
}
function nk_game_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_code = (isset($result_info_array['TypeCode']) ? $result_info_array['TypeCode'] : "");
	$result = "";
	switch($game_code)
	{
		case "BingoBingo": $result .= $obj->lang->line('nk_lottery_game_code_bingobingo'); break;
		case "BJPK10": $result .= $obj->lang->line('nk_lottery_game_code_bjpk10'); break;
		case "XYFT": $result .= $obj->lang->line('nk_lottery_game_code_xyft'); break;
		case "JSPK10": $result .= $obj->lang->line('nk_lottery_game_code_jspk10'); break;
		case "KPPK10": $result .= $obj->lang->line('nk_lottery_game_code_kppk10'); break;
		case "BJKENO8": $result .= $obj->lang->line('nk_lottery_game_code_bjkeno8'); break;
		case "SLFK": $result .= $obj->lang->line('nk_lottery_game_code_slfk'); break;
		case "CQSSC": $result .= $obj->lang->line('nk_lottery_game_code_cqssc'); break;
		case "TJSSC": $result .= $obj->lang->line('nk_lottery_game_code_tjssc'); break;
		case "XJSSC": $result .= $obj->lang->line('nk_lottery_game_code_xjssc'); break;
		case "TXSSC": $result .= $obj->lang->line('nk_lottery_game_code_txssc'); break;
		case "QQSSC": $result .= $obj->lang->line('nk_lottery_game_code_qqssc'); break;
		case "TKKENO": $result .= $obj->lang->line('nk_lottery_game_code_tkkeno'); break;
		default: $result .= "-"; break;
	}
	return $result;
}
function naga_game_code_decision($game_type_code = NULL,$result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_id = (isset($result_info_array['game']['code']) ? $result_info_array['game']['code'] : "");
	$result = $obj->lang->line('slot_game_naga_'.$game_id);
	return $result;
}
function naga_bet_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$transaction_id = (isset($result_info_array['id']) ? $result_info_array['id'] : "");
	$result = $obj->lang->line('all_result_bet_id').": ".$transaction_id."<br>";
	return $result;
}
function nk_bet_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$bet_item = (isset($result_info_array['BetItem']) ? $result_info_array['BetItem'] : "");
	$game_num = (isset($result_info_array['GameNum']) ? $result_info_array['GameNum'] : "");
	$wager_id = (isset($result_info_array['WagerID']) ? $result_info_array['WagerID'] : "");
	$result = "";
	$result .= $bet_item."<br>";
	$result .= $obj->lang->line('all_result_round').":".$game_num."<br>";
	$result .= $obj->lang->line('all_result_bet_id').":".$wager_id."<br>";
	return $result;	
}
function nk_game_result_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_result = (isset($result_info_array['Result']) ? $result_info_array['Result'] : "");
	$result = "";
	switch($game_result)
	{
		case "W": $result .= $obj->lang->line('all_sport_result_win'); break;
		case "L": $result .= $obj->lang->line('all_sport_result_lose'); break;
		case "X": $result .= $obj->lang->line('all_sport_result_pending'); break;
		case "C": $result .= $obj->lang->line('all_sport_result_cancel'); break;
		default: $result .= "-"; break;
	}
	return $result;	
}
function obsb_game_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$result = "";
	$game_type = (isset($result_info_array['game_type']) ? $result_info_array['game_type'] : "");
	if($game_type == 7){
		if(isset($result_info_array['cross_order_detail']) && sizeof($result_info_array['cross_order_detail'])>0){
			foreach($result_info_array['cross_order_detail'] as $result_info_array_row){
				if(!empty($result)){
					$result .= "<br><hr><br>";
				}else{
					$result .= "<br>";
				}
				switch($result_info_array_row['game_category'])
				{
					case 1: $result .= $obj->lang->line('all_sportbook_bet_america_baseball')."<br>"; break;
					case 2: $result .= $obj->lang->line('all_sportbook_bet_lottery')."<br>"; break;
					case 3: $result .= $obj->lang->line('all_sportbook_bet_china_baseball')."<br>"; break;
					case 4: $result .= $obj->lang->line('all_sportbook_bet_japan_baseball')."<br>"; break;
					case 5: $result .= $obj->lang->line('all_sportbook_bet_korea_baseball')."<br>"; break;
					case 6: $result .= $obj->lang->line('all_sportbook_bet_ice_hockey')."<br>"; break;
					case 7: $result .= $obj->lang->line('all_sportbook_bet_basketball')."<br>"; break;
					case 8: $result .= $obj->lang->line('all_sportbook_bet_horse_race')."/".$obj->lang->line('all_sportbook_bet_dog_race')."<br>"; break;
					case 9: $result .= $obj->lang->line('all_sportbook_bet_others_baseball')."<br>"; break;
					case 10: $result .= $obj->lang->line('all_sportbook_bet_others_ice_hockey')."<br>"; break;
					case 11: $result .= $obj->lang->line('all_sportbook_bet_others_basketball')."<br>"; break;
					case 13: $result .= $obj->lang->line('all_sportbook_bet_others_scoccer')."<br>"; break;
					case 14: $result .= $obj->lang->line('all_sportbook_bet_top_football')."<br>"; break;
					case 15: $result .= $obj->lang->line('all_sportbook_bet_america_scoccer')."<br>"; break;
					case 16: $result .= $obj->lang->line('all_sportbook_bet_esport')."<br>"; break;
					default: $result .= $obj->lang->line('all_sportbook_bet_others')."<br>"; break;
				}
				if(isset($result_info_array_row['league_name'])){
					$result .= $result_info_array_row['league_name'] . '<br>';
				}
				$result .= $result_info_array_row['rank2'] ."(".$obj->lang->line('all_sportbook_bet_main').")". " -vs- " . $result_info_array_row['rank1'] ."(".$obj->lang->line('all_sportbook_bet_visit').")". '<br>';
			}
		}
	}else{
		switch($result_info_array['game_category'])
		{
			case 1: $result .= $obj->lang->line('all_sportbook_bet_america_baseball')."<br>"; break;
			case 2: $result .= $obj->lang->line('all_sportbook_bet_lottery')."<br>"; break;
			case 3: $result .= $obj->lang->line('all_sportbook_bet_china_baseball')."<br>"; break;
			case 4: $result .= $obj->lang->line('all_sportbook_bet_japan_baseball')."<br>"; break;
			case 5: $result .= $obj->lang->line('all_sportbook_bet_korea_baseball')."<br>"; break;
			case 6: $result .= $obj->lang->line('all_sportbook_bet_ice_hockey')."<br>"; break;
			case 7: $result .= $obj->lang->line('all_sportbook_bet_basketball')."<br>"; break;
			case 8: $result .= $obj->lang->line('all_sportbook_bet_horse_race')."/".$obj->lang->line('all_sportbook_bet_dog_race')."<br>"; break;
			case 9: $result .= $obj->lang->line('all_sportbook_bet_others_baseball')."<br>"; break;
			case 10: $result .= $obj->lang->line('all_sportbook_bet_others_ice_hockey')."<br>"; break;
			case 11: $result .= $obj->lang->line('all_sportbook_bet_others_basketball')."<br>"; break;
			case 13: $result .= $obj->lang->line('all_sportbook_bet_others_scoccer')."<br>"; break;
			case 14: $result .= $obj->lang->line('all_sportbook_bet_top_football')."<br>"; break;
			case 15: $result .= $obj->lang->line('all_sportbook_bet_america_scoccer')."<br>"; break;
			case 16: $result .= $obj->lang->line('all_sportbook_bet_esport')."<br>"; break;
			default: $result .= $obj->lang->line('all_sportbook_bet_others')."<br>"; break;
		}
		if(isset($result_info_array['league_name'])){
			$result .= $result_info_array['league_name'] . '<br>';
		}
		$result .= $result_info_array['rank2'] ."(".$obj->lang->line('all_sportbook_bet_main').")". " -vs- " . $result_info_array['rank1'] ."(".$obj->lang->line('all_sportbook_bet_visit').")". '<br>';
	}
	return $result;
}
function obsb_bet_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$result = "";
	$game_type = (isset($result_info_array['game_type']) ? $result_info_array['game_type'] : "");
	$bet_type = (isset($result_info_array['bet_type']) ? $result_info_array['bet_type'] : "");
	$bet_index = (isset($result_info_array['bet_index']) ? $result_info_array['bet_index'] : "");
	$odd_rate = 1;
	if($game_type == 7){
		if(isset($result_info_array['cross_order_detail']) && sizeof($result_info_array['cross_order_detail'])>0){
			foreach($result_info_array['cross_order_detail'] as $result_info_array_row){
				$odd_rate *= ((isset($result_info_array_row['bet_odds']) ? $result_info_array_row['bet_odds'] : 0) + 1);
				$game_type = (isset($result_info_array_row['game_type']) ? $result_info_array_row['game_type'] : "");
				$bet_type = (isset($result_info_array_row['bet_type']) ? $result_info_array_row['bet_type'] : "");
				$bet_index = (isset($result_info_array_row['bet_index']) ? $result_info_array_row['bet_index'] : "");
				if(!empty($result)){
					$result .= "<br><hr><br>";
				}else{
					$result .= "<br>";
				}
				$game_type_name = "";
				switch($game_type)
				{
					case 1: $game_type_name = $obj->lang->line('all_sportbook_bet_type_full_time'); break;
					case 2: $game_type_name = $obj->lang->line('all_sportbook_bet_type_first_half'); break;
					case 3: $game_type_name = $obj->lang->line('all_sportbook_bet_type_second_half'); break;
					case 4: $game_type_name = $obj->lang->line('all_sportbook_bet_type_full_time_roll_ball'); break;
					case 5: $game_type_name = $obj->lang->line('all_sportbook_bet_type_first_time_roll_ball'); break;
					case 7: $game_type_name = $obj->lang->line('all_sportbook_bet_type_parlay'); break;
					case 8: $game_type_name = $obj->lang->line('all_sportbook_bet_type_section_one'); break;
					case 9: $game_type_name = $obj->lang->line('all_sportbook_bet_type_section_two'); break;
					case 10: $game_type_name = $obj->lang->line('all_sportbook_bet_type_section_three'); break;
					case 11: $game_type_name = $obj->lang->line('all_sportbook_bet_type_section_four'); break;
					case 15: $game_type_name = $obj->lang->line('all_sportbook_bet_type_first_goal'); break;
					case 16: $game_type_name = $obj->lang->line('all_sportbook_bet_type_last_goal'); break;
					case 17: $game_type_name = $obj->lang->line('all_sportbook_bet_type_three_innings'); break;
					case 18: $game_type_name = $obj->lang->line('all_sportbook_bet_type_seven_innings'); break;
					case 21: $game_type_name = $obj->lang->line('all_sportbook_bet_type_three_way_sum'); break;
					case 22: $game_type_name = $obj->lang->line('all_sportbook_bet_type_main_visit_score'); break;
					case 23: $game_type_name = $obj->lang->line('all_sportbook_bet_type_first_round'); break;
					case 24: $game_type_name = $obj->lang->line('all_sportbook_bet_type_second_round'); break;
					case 25: $game_type_name = $obj->lang->line('all_sportbook_bet_type_third_round'); break;
					case 26: $game_type_name = $obj->lang->line('all_sportbook_bet_type_fourth_round'); break;
					case 27: $game_type_name = $obj->lang->line('all_sportbook_bet_type_fifth_round'); break;
					case 28: $game_type_name = $obj->lang->line('all_sportbook_bet_type_sixth_round'); break;
					case 29: $game_type_name = $obj->lang->line('all_sportbook_bet_type_seventh_round'); break;
					case 30: $game_type_name = $obj->lang->line('all_sportbook_bet_type_first_round_roll_ball'); break;
					case 31: $game_type_name = $obj->lang->line('all_sportbook_bet_type_second_round_roll_ball'); break;
					case 32: $game_type_name = $obj->lang->line('all_sportbook_bet_type_third_round_roll_ball'); break;
					case 33: $game_type_name = $obj->lang->line('all_sportbook_bet_type_fourth_round_roll_ball'); break;
					case 34: $game_type_name = $obj->lang->line('all_sportbook_bet_type_fifth_round_roll_ball'); break;
					case 35: $game_type_name = $obj->lang->line('all_sportbook_bet_type_sixth_round_roll_ball'); break;
					case 36: $game_type_name = $obj->lang->line('all_sportbook_bet_type_seventh_round_roll_ball'); break;
					default: $game_type_name = $obj->lang->line('all_sportbook_bet_others'); break;
				}
				$result .= $game_type_name." ";
				switch($bet_type)
				{
					case 1: 
						switch($bet_index){
							case 1: $result .= $obj->lang->line('all_sportbook_bet_type_handicap')." 【".$obj->lang->line('all_sportbook_bet_visit_team')."】<br>"; break;
							case 2: $result .= $obj->lang->line('all_sportbook_bet_type_handicap')." 【".$obj->lang->line('all_sportbook_bet_main_team')."】<br>"; break;
						}break;
					case 2: 
						switch($bet_index){
							case 1: $result .= $obj->lang->line('all_sportbook_bet_type_over_under')." 【".$obj->lang->line('all_bet_code_big')."】<br>"; break;
							case 2: $result .= $obj->lang->line('all_sportbook_bet_type_over_under')." 【".$obj->lang->line('all_bet_code_small')."】<br>"; break;
						}break;
					case 3: 
						switch($bet_index){
							case 1: $result .= $obj->lang->line('all_sportbook_bet_type_money_line')." 【".$obj->lang->line('all_sportbook_bet_visit_team')."】<br>"; break;
							case 2: $result .= $obj->lang->line('all_sportbook_bet_type_money_line')." 【".$obj->lang->line('all_sportbook_bet_main_team')."】<br>"; break;
							case 3: $result .= $obj->lang->line('all_sportbook_bet_type_money_line')." 【".$obj->lang->line('all_sportbook_bet_draw')."】<br>"; break;
						}break;
					case 4: 
						switch($bet_index){
							case 1: $result .= $obj->lang->line('all_sportbook_bet_type_odd_even')." 【".$obj->lang->line('all_bet_code_odd')."】<br>"; break;
							case 2: $result .= $obj->lang->line('all_sportbook_bet_type_odd_even')." 【".$obj->lang->line('all_bet_code_even')."】<br>"; break;
						}break;
					default: $result .= "<br>"; break;
				}
				$result .= $obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['bet_hscore']) ? $result_info_array_row['bet_hscore'] : "")." ".(isset($result_info_array_row['bet_hpercent']) ? ($result_info_array_row['bet_hpercent'] > 0) ? "+".$result_info_array_row['bet_hpercent'] : $result_info_array_row['bet_hpercent'] : "")."<br>";
				$result .= $obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['bet_odds']) ? bcdiv($result_info_array_row['bet_odds'],1,3) : "")."<br>";
			}
			$odd_rate = bcdiv(($odd_rate - 1),1,3);
			$result .= "<br><hr><br>";
			$result .= $obj->lang->line('all_sportbook_bet_type_parlay')."<br>";
			$result .= $obj->lang->line('all_sportbook_odds_rate').":".$odd_rate."<br>";
			$result .= $obj->lang->line('all_result_bet_id').":".(isset($result_info_array['order_id']) ? $result_info_array['order_id'] : "")."<br>";
			$result .= $obj->lang->line('all_result_bet_id').":".(isset($result_info_array['order_no']) ? $result_info_array['order_no'] : "")."<br>";
		}
	}else{
		$game_type_name = "";
		switch($game_type)
		{
			case 1: $game_type_name = $obj->lang->line('all_sportbook_bet_type_full_time'); break;
			case 2: $game_type_name = $obj->lang->line('all_sportbook_bet_type_first_half'); break;
			case 3: $game_type_name = $obj->lang->line('all_sportbook_bet_type_second_half'); break;
			case 4: $game_type_name = $obj->lang->line('all_sportbook_bet_type_full_time_roll_ball'); break;
			case 5: $game_type_name = $obj->lang->line('all_sportbook_bet_type_first_time_roll_ball'); break;
			case 7: $game_type_name = $obj->lang->line('all_sportbook_bet_type_parlay'); break;
			case 8: $game_type_name = $obj->lang->line('all_sportbook_bet_type_section_one'); break;
			case 9: $game_type_name = $obj->lang->line('all_sportbook_bet_type_section_two'); break;
			case 10: $game_type_name = $obj->lang->line('all_sportbook_bet_type_section_three'); break;
			case 11: $game_type_name = $obj->lang->line('all_sportbook_bet_type_section_four'); break;
			case 15: $game_type_name = $obj->lang->line('all_sportbook_bet_type_first_goal'); break;
			case 16: $game_type_name = $obj->lang->line('all_sportbook_bet_type_last_goal'); break;
			case 17: $game_type_name = $obj->lang->line('all_sportbook_bet_type_three_innings'); break;
			case 18: $game_type_name = $obj->lang->line('all_sportbook_bet_type_seven_innings'); break;
			case 21: $game_type_name = $obj->lang->line('all_sportbook_bet_type_three_way_sum'); break;
			case 22: $game_type_name = $obj->lang->line('all_sportbook_bet_type_main_visit_score'); break;
			case 23: $game_type_name = $obj->lang->line('all_sportbook_bet_type_first_round'); break;
			case 24: $game_type_name = $obj->lang->line('all_sportbook_bet_type_second_round'); break;
			case 25: $game_type_name = $obj->lang->line('all_sportbook_bet_type_third_round'); break;
			case 26: $game_type_name = $obj->lang->line('all_sportbook_bet_type_fourth_round'); break;
			case 27: $game_type_name = $obj->lang->line('all_sportbook_bet_type_fifth_round'); break;
			case 28: $game_type_name = $obj->lang->line('all_sportbook_bet_type_sixth_round'); break;
			case 29: $game_type_name = $obj->lang->line('all_sportbook_bet_type_seventh_round'); break;
			case 30: $game_type_name = $obj->lang->line('all_sportbook_bet_type_first_round_roll_ball'); break;
			case 31: $game_type_name = $obj->lang->line('all_sportbook_bet_type_second_round_roll_ball'); break;
			case 32: $game_type_name = $obj->lang->line('all_sportbook_bet_type_third_round_roll_ball'); break;
			case 33: $game_type_name = $obj->lang->line('all_sportbook_bet_type_fourth_round_roll_ball'); break;
			case 34: $game_type_name = $obj->lang->line('all_sportbook_bet_type_fifth_round_roll_ball'); break;
			case 35: $game_type_name = $obj->lang->line('all_sportbook_bet_type_sixth_round_roll_ball'); break;
			case 36: $game_type_name = $obj->lang->line('all_sportbook_bet_type_seventh_round_roll_ball'); break;
			default: $game_type_name = $obj->lang->line('all_sportbook_bet_others'); break;
		}
		$result .= $game_type_name." ";
		switch($bet_type)
		{
			case 1: 
				switch($bet_index){
					case 1: $result .= $obj->lang->line('all_sportbook_bet_type_handicap')." 【".$obj->lang->line('all_sportbook_bet_visit_team')."】<br>"; break;
					case 2: $result .= $obj->lang->line('all_sportbook_bet_type_handicap')." 【".$obj->lang->line('all_sportbook_bet_main_team')."】<br>"; break;
				}break;
			case 2: 
				switch($bet_index){
					case 1: $result .= $obj->lang->line('all_sportbook_bet_type_over_under')." 【".$obj->lang->line('all_bet_code_big')."】<br>"; break;
					case 2: $result .= $obj->lang->line('all_sportbook_bet_type_over_under')." 【".$obj->lang->line('all_bet_code_small')."】<br>"; break;
				}break;
			case 3: 
				switch($bet_index){
					case 1: $result .= $obj->lang->line('all_sportbook_bet_type_money_line')." 【".$obj->lang->line('all_sportbook_bet_visit_team')."】<br>"; break;
					case 2: $result .= $obj->lang->line('all_sportbook_bet_type_money_line')." 【".$obj->lang->line('all_sportbook_bet_main_team')."】<br>"; break;
					case 3: $result .= $obj->lang->line('all_sportbook_bet_type_money_line')." 【".$obj->lang->line('all_sportbook_bet_draw')."】<br>"; break;
				}break;
			case 4: 
				switch($bet_index){
					case 1: $result .= $obj->lang->line('all_sportbook_bet_type_odd_even')." 【".$obj->lang->line('all_bet_code_odd')."】<br>"; break;
					case 2: $result .= $obj->lang->line('all_sportbook_bet_type_odd_even')." 【".$obj->lang->line('all_bet_code_even')."】<br>"; break;
				}break;
			default: $result .= "<br>"; break;
		}
		$result .= $obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['bet_hscore']) ? $result_info_array['bet_hscore'] : "")." ".(isset($result_info_array['bet_hpercent']) ? ($result_info_array['bet_hpercent'] > 0) ? "+".$result_info_array['bet_hpercent'] : $result_info_array['bet_hpercent'] : "")."<br>";
		$result .= $obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['bet_odds']) ? bcdiv($result_info_array['bet_odds'],1,3) : "")."<br>";
		$result .= $obj->lang->line('all_result_bet_id').":".(isset($result_info_array['order_id']) ? $result_info_array['order_id'] : "")."<br>";
		$result .= $obj->lang->line('all_result_bet_id').":".(isset($result_info_array['order_no']) ? $result_info_array['order_no'] : "")."<br>";
	}
	return $result;
}
function obsb_game_result_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$result = "";
	$game_type = (isset($result_info_array['game_type']) ? $result_info_array['game_type'] : "");
	if($game_type == 7){
		if(isset($result_info_array['cross_order_detail']) && sizeof($result_info_array['cross_order_detail'])>0){
			foreach($result_info_array['cross_order_detail'] as $result_info_array_row){
				if(!empty($result)){
					$result .= "<br><hr><br>";
				}else{
					$result .= "<br>";
				}
				$result .= $result_info_array_row['rank2']." : ".$result_info_array_row['rank2_score']. '<br>';
				$result .= $result_info_array_row['rank1']." : ".$result_info_array_row['rank1_score']. '<br>';
			}
		}
	}else{
		$result .= $result_info_array['rank2']." : ".$result_info_array['rank2_score']. '<br>';
		$result .= $result_info_array['rank1']." : ".$result_info_array['rank1_score']. '<br>';
	}
	return $result;
}
function og_game_code_decision($game_type_code = NULL, $result_info = NULL){
	/*
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$result = "";
	$game_type = (isset($result_info_array['gamename']) ? $result_info_array['gamename'] : "");
	$game_code_data = array(
					    'SPEED BACCARAT' => "Baccarat",
					    'BACCARAT' => "Baccarat",
					    'BIDDING BACCARAT' => "Baccarat",
					    'NO COMMISSION BACCARAT' => "Baccarat",
					    'NEW DT' => "Dragon Tiger",
					    'CLASSIC DT' => "Dragon Tiger",
					    'MoneyWheel' => "Money Wheel",
					    'ROULETTE' => "Roulette",
					    'BULL BULL' => "Bull Bull",
					    'Three Card' => "Win Three Cards",
					    'SICBO' => "Sicbo",
    				);
    */
}
function png_game_code_decision($game_type_code = NULL,$result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_id = (isset($result_info_array['GameId']) ? $result_info_array['GameId'] : "");
	if($game_id >= 100000){
		$number = $game_id - 100000;
		$result = $obj->lang->line('slot_game_png_'.$number);
	}else{
		$result = $obj->lang->line('slot_game_png_'.$game_id);
	}
	return $result;
}
function png_bet_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$transaction_id = (isset($result_info_array['TransactionId']) ? $result_info_array['TransactionId'] : "");
	$round_id = (isset($result_info_array['RoundId']) ? $result_info_array['RoundId'] : "");
	$result = $obj->lang->line('all_result_bet_id').": ".$transaction_id."<br>";
	$result .= $obj->lang->line('all_result_round').": ".$round_id."<br>";
	return $result;
}
function rtg_game_code_decision($game_type_code = NULL,$result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_id = (isset($result_info_array['gameId']) ? $result_info_array['gameId'] : "");
	$result = $obj->lang->line('slot_game_rtg_'.$game_id);
	return $result;
}
function rtg_bet_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$transaction_id = (isset($result_info_array['id']) ? $result_info_array['id'] : "");
	$result = $obj->lang->line('all_result_bet_id').": ".$transaction_id."<br>";
	return $result;
}
function rsg_game_code_decision($game_type_code = NULL,$result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_id = (isset($result_info_array['GameId']) ? $result_info_array['GameId'] : "");
	$result = $obj->lang->line('slot_game_rsg_'.$game_id);
	return $result;
}
function rsg_bet_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$transaction_id = (isset($result_info_array['SequenNumber']) ? $result_info_array['SequenNumber'] : "");
	$result = $obj->lang->line('all_result_bet_id').": ".$transaction_id."<br>";
	return $result;
}
function sp_game_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_id = (isset($result_info_array['Detail']) ? $result_info_array['Detail'] : "");
	$result .= $obj->lang->line('slot_game_sp_'.$game_id)."<br>";
	return $result;
}
function sp_bet_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$bet_id = (isset($result_info_array['BetID']) ? $result_info_array['BetID'] : "");
	$game_id = (isset($result_info_array['Detail']) ? $result_info_array['Detail'] : "");
	$result = $obj->lang->line('all_result_bet_id').": ".$bet_id."<br>";
	return $result;
}
function sa_game_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$result = "-";
	if(isset($result_info_array['GameType'])){
		switch($result_info_array['GameType'])
		{
			case "bac": $result = $obj->lang->line('all_game_code_baccarat'); break;
			case "dtx": $result = $obj->lang->line('all_game_code_dragon_tiger'); break;
			case "sicbo": $result = $obj->lang->line('all_game_code_sicbo'); break;
			case "ftan": $result = $obj->lang->line('all_game_code_fan_tan'); break;
			case "rot": $result = $obj->lang->line('all_game_code_roulette'); break;
			case "moneywheel": $result = $obj->lang->line('all_game_code_moneywheel'); break;
			case "tip": $result = $obj->lang->line('all_game_code_tip'); break;
			case "pokdeng": $result = $obj->lang->line('all_game_code_pokdeng'); break;
			default: $result = "-"; break;
		}
	}
	return $result;
}
function sa_bet_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$host_id = (isset($result_info_array['HostID']) ? $result_info_array['HostID'] : "");
	$game_real_code = (isset($result_info_array['GameType']) ? $result_info_array['GameType'] : "");
	$bet_code = (isset($result_info_array['BetType']) ? $result_info_array['BetType'] : "");
	$bet_id = (isset($result_info_array['BetID']) ? $result_info_array['BetID'] : "");
	switch($host_id)
	{
		case 830: $result_host = $obj->lang->line('sa_host_id_830'); break;
		case 831: $result_host = $obj->lang->line('sa_host_id_831'); break;
		case 832: $result_host = $obj->lang->line('sa_host_id_832'); break;
		case 833: $result_host = $obj->lang->line('sa_host_id_833'); break;
		case 834: $result_host = $obj->lang->line('sa_host_id_834'); break;
		case 835: $result_host = $obj->lang->line('sa_host_id_835'); break;
		case 836: $result_host = $obj->lang->line('sa_host_id_836'); break;
		case 837: $result_host = $obj->lang->line('sa_host_id_837'); break;
		case 838: $result_host = $obj->lang->line('sa_host_id_838'); break;
		case 839: $result_host = $obj->lang->line('sa_host_id_839'); break;
		case 841: $result_host = $obj->lang->line('sa_host_id_841'); break;
		case 842: $result_host = $obj->lang->line('sa_host_id_842'); break;
		case 843: $result_host = $obj->lang->line('sa_host_id_843'); break;
		case 844: $result_host = $obj->lang->line('sa_host_id_844'); break;
		case 845: $result_host = $obj->lang->line('sa_host_id_845'); break;
		case 846: $result_host = $obj->lang->line('sa_host_id_846'); break;
		default: $result_host = ""; break;
	}
	if($game_real_code == "bac"){
		switch($bet_code)
		{
			case 0: $result = $obj->lang->line('all_bet_code_tie'); break;
			case 1: $result = $obj->lang->line('all_bet_code_player'); break;
			case 2: $result = $obj->lang->line('all_bet_code_banker'); break;
			case 3: $result = $obj->lang->line('all_bet_code_player_pair'); break;
			case 4: $result = $obj->lang->line('all_bet_code_banker_pair'); break;
			case 54: $result = $obj->lang->line('all_bet_code_lucky_six'); break;
			case 25: $result = $obj->lang->line('all_bet_code_no_commission_tie'); break;
			case 26: $result = $obj->lang->line('all_bet_code_no_commission_player'); break;
			case 27: $result = $obj->lang->line('all_bet_code_no_commission_banker'); break;
			case 28: $result = $obj->lang->line('all_bet_code_no_commission_player_pair'); break;
			case 29: $result = $obj->lang->line('all_bet_code_no_commission_banker_pair'); break;
			case 53: $result = $obj->lang->line('all_bet_code_no_commission_lucky_six'); break;
			case 30: $result = $obj->lang->line('all_bet_code_super_six'); break;
			case 36: $result = $obj->lang->line('all_bet_code_player_natural'); break;
			case 37: $result = $obj->lang->line('all_bet_code_banker_natural'); break;
			case 40: $result = $obj->lang->line('all_bet_code_no_commission_player_natural'); break;
			case 41: $result = $obj->lang->line('all_bet_code_no_commission_banker_natural'); break;
			case 42: $result = $obj->lang->line('all_bet_code_bull_bull_player'); break;
			case 43: $result = $obj->lang->line('all_bet_code_bull_bull_banker'); break;
			case 44: $result = $obj->lang->line('all_bet_code_bull_bull_tie'); break;
			default: $result = ""; break;
		}
	}else if($game_real_code == "dtx"){
		switch($bet_code)
		{
			case 0: $result = $obj->lang->line('all_bet_code_tie'); break;
			case 1: $result = $obj->lang->line('all_bet_code_dragon'); break;
			case 2: $result = $obj->lang->line('all_bet_code_tiger'); break;
			default: $result = ""; break;
		}
	}else if($game_real_code == "sicbo"){
		switch($bet_code)
		{
			case 0: $result = $obj->lang->line('all_bet_code_small'); break;
			case 1: $result = $obj->lang->line('all_bet_code_big'); break;
			case 2: $result = $obj->lang->line('all_bet_code_odd'); break;
			case 3: $result = $obj->lang->line('all_bet_code_even'); break;
			case 4: $result = $obj->lang->line('all_bet_code_one_of_a_kind')." : 1"; break;
			case 5: $result = $obj->lang->line('all_bet_code_one_of_a_kind')." : 2"; break;
			case 6: $result = $obj->lang->line('all_bet_code_one_of_a_kind')." : 3"; break;
			case 7: $result = $obj->lang->line('all_bet_code_one_of_a_kind')." : 4"; break;
			case 8: $result = $obj->lang->line('all_bet_code_one_of_a_kind')." : 5"; break;
			case 9: $result = $obj->lang->line('all_bet_code_one_of_a_kind')." : 6"; break;
			case 10: $result = $obj->lang->line('all_bet_code_specific_triples')." : 1"; break;
			case 11: $result = $obj->lang->line('all_bet_code_specific_triples')." : 2"; break;
			case 12: $result = $obj->lang->line('all_bet_code_specific_triples')." : 3"; break;
			case 13: $result = $obj->lang->line('all_bet_code_specific_triples')." : 4"; break;
			case 14: $result = $obj->lang->line('all_bet_code_specific_triples')." : 5"; break;
			case 15: $result = $obj->lang->line('all_bet_code_specific_triples')." : 6"; break;
			case 16: $result = $obj->lang->line('all_bet_code_any_specific_triples'); break;
			case 17: $result = $obj->lang->line('all_bet_code_sum_number')." : 4"; break;
			case 18: $result = $obj->lang->line('all_bet_code_sum_number')." : 5"; break;
			case 19: $result = $obj->lang->line('all_bet_code_sum_number')." : 6"; break;
			case 20: $result = $obj->lang->line('all_bet_code_sum_number')." : 7"; break;
			case 21: $result = $obj->lang->line('all_bet_code_sum_number')." : 8"; break;
			case 22: $result = $obj->lang->line('all_bet_code_sum_number')." : 9"; break;
			case 23: $result = $obj->lang->line('all_bet_code_sum_number')." : 10"; break;
			case 24: $result = $obj->lang->line('all_bet_code_sum_number')." : 11"; break;
			case 25: $result = $obj->lang->line('all_bet_code_sum_number')." : 12"; break;
			case 26: $result = $obj->lang->line('all_bet_code_sum_number')." : 13"; break;
			case 27: $result = $obj->lang->line('all_bet_code_sum_number')." : 14"; break;
			case 28: $result = $obj->lang->line('all_bet_code_sum_number')." : 15"; break;
			case 29: $result = $obj->lang->line('all_bet_code_sum_number')." : 16"; break;
			case 30: $result = $obj->lang->line('all_bet_code_sum_number')." : 17"; break;
			case 31: $result = $obj->lang->line('all_bet_code_specific_double')." : 1, 2"; break;
			case 32: $result = $obj->lang->line('all_bet_code_specific_double')." : 1, 3"; break;
			case 33: $result = $obj->lang->line('all_bet_code_specific_double')." : 1, 4"; break;
			case 34: $result = $obj->lang->line('all_bet_code_specific_double')." : 1, 5"; break;
			case 35: $result = $obj->lang->line('all_bet_code_specific_double')." : 1, 6"; break;
			case 36: $result = $obj->lang->line('all_bet_code_specific_double')." : 2, 3"; break;
			case 37: $result = $obj->lang->line('all_bet_code_specific_double')." : 2, 4"; break;
			case 38: $result = $obj->lang->line('all_bet_code_specific_double')." : 2, 5"; break;
			case 39: $result = $obj->lang->line('all_bet_code_specific_double')." : 2, 6"; break;
			case 40: $result = $obj->lang->line('all_bet_code_specific_double')." : 3, 4"; break;
			case 41: $result = $obj->lang->line('all_bet_code_specific_double')." : 3, 5"; break;
			case 42: $result = $obj->lang->line('all_bet_code_specific_double')." : 3, 6"; break;
			case 43: $result = $obj->lang->line('all_bet_code_specific_double')." : 4, 5"; break;
			case 44: $result = $obj->lang->line('all_bet_code_specific_double')." : 4, 6"; break;
			case 45: $result = $obj->lang->line('all_bet_code_specific_double')." : 5, 6"; break;
			case 46: $result = $obj->lang->line('all_bet_code_pair')." : 1"; break;
			case 47: $result = $obj->lang->line('all_bet_code_pair')." : 2"; break;
			case 48: $result = $obj->lang->line('all_bet_code_pair')." : 3"; break;
			case 49: $result = $obj->lang->line('all_bet_code_pair')." : 4"; break;
			case 50: $result = $obj->lang->line('all_bet_code_pair')." : 5"; break;
			case 51: $result = $obj->lang->line('all_bet_code_pair')." : 6"; break;
			case 52: $result = $obj->lang->line('all_bet_code_three_odd'); break;
			case 53: $result = $obj->lang->line('all_bet_code_two_odd_one_even'); break;
			case 54: $result = $obj->lang->line('all_bet_code_two_even_one_odd'); break;
			case 55: $result = $obj->lang->line('all_bet_code_three_even'); break;
			case 56: $result = $obj->lang->line('all_bet_code_dice') . " : 1, 2, 3, 4"; break;
			case 57: $result = $obj->lang->line('all_bet_code_dice') . " : 2, 3, 4, 5"; break;
			case 58: $result = $obj->lang->line('all_bet_code_dice') . " : 2, 3, 5 ,6"; break;
			case 59: $result = $obj->lang->line('all_bet_code_dice') . " : 3, 4, 5 ,6"; break;
			case 60: $result = $obj->lang->line('all_bet_code_dice') . " : 1, 1, 2"; break;
			case 61: $result = $obj->lang->line('all_bet_code_dice') . " : 1, 1, 3"; break;
			case 62: $result = $obj->lang->line('all_bet_code_dice') . " : 1, 1, 4"; break;
			case 63: $result = $obj->lang->line('all_bet_code_dice') . " : 1, 1, 5"; break;
			case 64: $result = $obj->lang->line('all_bet_code_dice') . " : 1, 1, 6"; break;
			case 65: $result = $obj->lang->line('all_bet_code_dice') . " : 2, 2, 1"; break;
			case 66: $result = $obj->lang->line('all_bet_code_dice') . " : 2, 2, 3"; break;
			case 67: $result = $obj->lang->line('all_bet_code_dice') . " : 2, 2, 4"; break;
			case 68: $result = $obj->lang->line('all_bet_code_dice') . " : 2, 2, 5"; break;
			case 69: $result = $obj->lang->line('all_bet_code_dice') . " : 2, 2, 6"; break;
			case 70: $result = $obj->lang->line('all_bet_code_dice') . " : 3, 3, 1"; break;
			case 71: $result = $obj->lang->line('all_bet_code_dice') . " : 3, 3, 2"; break;
			case 72: $result = $obj->lang->line('all_bet_code_dice') . " : 3, 3, 4"; break;
			case 73: $result = $obj->lang->line('all_bet_code_dice') . " : 3, 3, 5"; break;
			case 74: $result = $obj->lang->line('all_bet_code_dice') . " : 3, 3, 6"; break;
			case 75: $result = $obj->lang->line('all_bet_code_dice') . " : 4, 4, 1"; break;
			case 76: $result = $obj->lang->line('all_bet_code_dice') . " : 4, 4, 2"; break;
			case 77: $result = $obj->lang->line('all_bet_code_dice') . " : 4, 4, 3"; break;
			case 78: $result = $obj->lang->line('all_bet_code_dice') . " : 4, 4, 5"; break;
			case 79: $result = $obj->lang->line('all_bet_code_dice') . " : 4, 4, 6"; break;
			case 80: $result = $obj->lang->line('all_bet_code_dice') . " : 5, 5, 1"; break;
			case 81: $result = $obj->lang->line('all_bet_code_dice') . " : 5, 5, 2"; break;
			case 82: $result = $obj->lang->line('all_bet_code_dice') . " : 5, 5, 3"; break;
			case 83: $result = $obj->lang->line('all_bet_code_dice') . " : 5, 5, 4"; break;
			case 84: $result = $obj->lang->line('all_bet_code_dice') . " : 5, 5, 6"; break;
			case 85: $result = $obj->lang->line('all_bet_code_dice') . " : 6, 6, 1"; break;
			case 86: $result = $obj->lang->line('all_bet_code_dice') . " : 6, 6, 2"; break;
			case 87: $result = $obj->lang->line('all_bet_code_dice') . " : 6, 6, 3"; break;
			case 88: $result = $obj->lang->line('all_bet_code_dice') . " : 6, 6, 4"; break;
			case 89: $result = $obj->lang->line('all_bet_code_dice') . " : 6, 6, 5"; break;
			case 90: $result = $obj->lang->line('all_bet_code_dice') . " : 1, 2, 6"; break;
			case 91: $result = $obj->lang->line('all_bet_code_dice') . " : 1, 3, 5"; break;
			case 92: $result = $obj->lang->line('all_bet_code_dice') . " : 2, 3, 4"; break;
			case 93: $result = $obj->lang->line('all_bet_code_dice') . " : 2, 5, 6"; break;
			case 94: $result = $obj->lang->line('all_bet_code_dice') . " : 3, 4, 6"; break;
			case 95: $result = $obj->lang->line('all_bet_code_dice') . " : 1, 2, 3"; break;
			case 96: $result = $obj->lang->line('all_bet_code_dice') . " : 1, 3, 6"; break;
			case 97: $result = $obj->lang->line('all_bet_code_dice') . " : 1, 4, 5"; break;
			case 98: $result = $obj->lang->line('all_bet_code_dice') . " : 2, 3, 5"; break;
			case 99: $result = $obj->lang->line('all_bet_code_dice') . " : 3, 5, 6"; break;
			case 100: $result = $obj->lang->line('all_bet_code_dice') . " : 1, 2, 4"; break;
			default: $result = ""; break;
		}
	}else if($game_real_code == "ftan"){
		switch($bet_code)
		{
			case 0: $result = $obj->lang->line('all_bet_code_odd'); break;
			case 1: $result = $obj->lang->line('all_bet_code_even'); break;
			case 2: $result = '1 '.$obj->lang->line('all_bet_code_zheng'); break;
			case 3: $result = '2 '.$obj->lang->line('all_bet_code_zheng'); break;
			case 4: $result = '3 '.$obj->lang->line('all_bet_code_zheng'); break;
			case 5: $result = '4 '.$obj->lang->line('all_bet_code_zheng'); break;
			case 6: $result = '1 '.$obj->lang->line('all_bet_code_fan'); break;
			case 7: $result = '2 '.$obj->lang->line('all_bet_code_fan'); break;
			case 8: $result = '3 '.$obj->lang->line('all_bet_code_fan'); break;
			case 9: $result = '4 '.$obj->lang->line('all_bet_code_fan'); break;
			case 10: $result = '1 '.$obj->lang->line('all_bet_code_nim_number')." 2"; break;
			case 11: $result = '1 '.$obj->lang->line('all_bet_code_nim_number')." 3"; break;
			case 12: $result = '1 '.$obj->lang->line('all_bet_code_nim_number')." 4"; break;
			case 13: $result = '2 '.$obj->lang->line('all_bet_code_nim_number')." 1"; break;
			case 14: $result = '2 '.$obj->lang->line('all_bet_code_nim_number')." 3"; break;
			case 15: $result = '2 '.$obj->lang->line('all_bet_code_nim_number')." 4"; break;
			case 16: $result = '3 '.$obj->lang->line('all_bet_code_nim_number')." 1"; break;
			case 17: $result = '3 '.$obj->lang->line('all_bet_code_nim_number')." 2"; break;
			case 18: $result = '3 '.$obj->lang->line('all_bet_code_nim_number')." 4"; break;
			case 19: $result = '4 '.$obj->lang->line('all_bet_code_nim_number')." 1"; break;
			case 20: $result = '4 '.$obj->lang->line('all_bet_code_nim_number')." 2"; break;
			case 21: $result = '4 '.$obj->lang->line('all_bet_code_nim_number')." 3"; break;
			case 22: $result = '12 '.$obj->lang->line('all_bet_code_kwok_number'); break;
			case 23: $result = '41 '.$obj->lang->line('all_bet_code_kwok_number'); break;
			case 24: $result = '23 '.$obj->lang->line('all_bet_code_kwok_number'); break;
			case 25: $result = '34 '.$obj->lang->line('all_bet_code_kwok_number'); break;
			case 26: $result = '23 '.$obj->lang->line('all_bet_code_one').$obj->lang->line('all_bet_code_nga_number'); break;
			case 27: $result = '24 '.$obj->lang->line('all_bet_code_one').$obj->lang->line('all_bet_code_nga_number'); break;
			case 28: $result = '34 '.$obj->lang->line('all_bet_code_one').$obj->lang->line('all_bet_code_nga_number'); break;
			case 29: $result = '13 '.$obj->lang->line('all_bet_code_two').$obj->lang->line('all_bet_code_nga_number'); break;
			case 30: $result = '14 '.$obj->lang->line('all_bet_code_two').$obj->lang->line('all_bet_code_nga_number'); break;
			case 31: $result = '34 '.$obj->lang->line('all_bet_code_two').$obj->lang->line('all_bet_code_nga_number'); break;
			case 32: $result = '12 '.$obj->lang->line('all_bet_code_three').$obj->lang->line('all_bet_code_nga_number'); break;
			case 33: $result = '14 '.$obj->lang->line('all_bet_code_three').$obj->lang->line('all_bet_code_nga_number'); break;
			case 34: $result = '24 '.$obj->lang->line('all_bet_code_three').$obj->lang->line('all_bet_code_nga_number'); break;
			case 35: $result = '12 '.$obj->lang->line('all_bet_code_four').$obj->lang->line('all_bet_code_nga_number'); break;
			case 36: $result = '13 '.$obj->lang->line('all_bet_code_four').$obj->lang->line('all_bet_code_nga_number'); break;
			case 37: $result = '23 '.$obj->lang->line('all_bet_code_four').$obj->lang->line('all_bet_code_nga_number'); break;
			case 38: $result = '123 '.$obj->lang->line('all_bet_code_chun'); break;
			case 39: $result = '124 '.$obj->lang->line('all_bet_code_chun'); break;
			case 40: $result = '134 '.$obj->lang->line('all_bet_code_chun'); break;
			case 41: $result = '234 '.$obj->lang->line('all_bet_code_chun'); break;
			default: $result = ""; break;
		}
	}else if($game_real_code == "rot"){
		switch($bet_code)
		{
			case 0: $result = $obj->lang->line('all_bet_code_straight_number')." : 0"; break;
			case 1: $result = $obj->lang->line('all_bet_code_straight_number')." : 1"; break;
			case 2: $result = $obj->lang->line('all_bet_code_straight_number')." : 2"; break;
			case 3: $result = $obj->lang->line('all_bet_code_straight_number')." : 3"; break;
			case 4: $result = $obj->lang->line('all_bet_code_straight_number')." : 4"; break;
			case 5: $result = $obj->lang->line('all_bet_code_straight_number')." : 5"; break;
			case 6: $result = $obj->lang->line('all_bet_code_straight_number')." : 6"; break;
			case 7: $result = $obj->lang->line('all_bet_code_straight_number')." : 7"; break;
			case 8: $result = $obj->lang->line('all_bet_code_straight_number')." : 8"; break;
			case 9: $result = $obj->lang->line('all_bet_code_straight_number')." : 9"; break;
			case 10: $result = $obj->lang->line('all_bet_code_straight_number')." : 10"; break;
			case 11: $result = $obj->lang->line('all_bet_code_straight_number')." : 11"; break;
			case 12: $result = $obj->lang->line('all_bet_code_straight_number')." : 12"; break;
			case 13: $result = $obj->lang->line('all_bet_code_straight_number')." : 13"; break;
			case 14: $result = $obj->lang->line('all_bet_code_straight_number')." : 14"; break;
			case 15: $result = $obj->lang->line('all_bet_code_straight_number')." : 15"; break;
			case 16: $result = $obj->lang->line('all_bet_code_straight_number')." : 16"; break;
			case 17: $result = $obj->lang->line('all_bet_code_straight_number')." : 17"; break;
			case 18: $result = $obj->lang->line('all_bet_code_straight_number')." : 18"; break;
			case 19: $result = $obj->lang->line('all_bet_code_straight_number')." : 19"; break;
			case 20: $result = $obj->lang->line('all_bet_code_straight_number')." : 20"; break;
			case 21: $result = $obj->lang->line('all_bet_code_straight_number')." : 21"; break;
			case 22: $result = $obj->lang->line('all_bet_code_straight_number')." : 22"; break;
			case 23: $result = $obj->lang->line('all_bet_code_straight_number')." : 23"; break;
			case 24: $result = $obj->lang->line('all_bet_code_straight_number')." : 24"; break;
			case 25: $result = $obj->lang->line('all_bet_code_straight_number')." : 25"; break;
			case 26: $result = $obj->lang->line('all_bet_code_straight_number')." : 26"; break;
			case 27: $result = $obj->lang->line('all_bet_code_straight_number')." : 27"; break;
			case 28: $result = $obj->lang->line('all_bet_code_straight_number')." : 28"; break;
			case 29: $result = $obj->lang->line('all_bet_code_straight_number')." : 29"; break;
			case 30: $result = $obj->lang->line('all_bet_code_straight_number')." : 30"; break;
			case 31: $result = $obj->lang->line('all_bet_code_straight_number')." : 31"; break;
			case 32: $result = $obj->lang->line('all_bet_code_straight_number')." : 32"; break;
			case 33: $result = $obj->lang->line('all_bet_code_straight_number')." : 33"; break;
			case 34: $result = $obj->lang->line('all_bet_code_straight_number')." : 34"; break;
			case 35: $result = $obj->lang->line('all_bet_code_straight_number')." : 35"; break;
			case 36: $result = $obj->lang->line('all_bet_code_straight_number')." : 36"; break;
			case 37: $result = $obj->lang->line('all_bet_code_multiple_number')." : 0, 1"; break;
			case 38: $result = $obj->lang->line('all_bet_code_multiple_number')." : 0, 2"; break;
			case 39: $result = $obj->lang->line('all_bet_code_multiple_number')." : 0, 3"; break;
			case 40: $result = $obj->lang->line('all_bet_code_multiple_number')." : 1, 2"; break;
			case 41: $result = $obj->lang->line('all_bet_code_multiple_number')." : 1, 4"; break;
			case 42: $result = $obj->lang->line('all_bet_code_multiple_number')." : 2, 3"; break;
			case 43: $result = $obj->lang->line('all_bet_code_multiple_number')." : 2, 5"; break;
			case 44: $result = $obj->lang->line('all_bet_code_multiple_number')." : 3, 6"; break;
			case 45: $result = $obj->lang->line('all_bet_code_multiple_number')." : 4, 5"; break;
			case 46: $result = $obj->lang->line('all_bet_code_multiple_number')." : 4, 7"; break;
			case 47: $result = $obj->lang->line('all_bet_code_multiple_number')." : 5, 6"; break;
			case 48: $result = $obj->lang->line('all_bet_code_multiple_number')." : 5, 8"; break;
			case 49: $result = $obj->lang->line('all_bet_code_multiple_number')." : 6, 9"; break;
			case 50: $result = $obj->lang->line('all_bet_code_multiple_number')." : 7, 8"; break;
			case 51: $result = $obj->lang->line('all_bet_code_multiple_number')." : 7, 10"; break;
			case 52: $result = $obj->lang->line('all_bet_code_multiple_number')." : 8, 9"; break;
			case 53: $result = $obj->lang->line('all_bet_code_multiple_number')." : 8, 11"; break;
			case 54: $result = $obj->lang->line('all_bet_code_multiple_number')." : 9, 12"; break;
			case 55: $result = $obj->lang->line('all_bet_code_multiple_number')." : 10, 11"; break;
			case 56: $result = $obj->lang->line('all_bet_code_multiple_number')." : 10, 13"; break;
			case 57: $result = $obj->lang->line('all_bet_code_multiple_number')." : 11, 12"; break;
			case 58: $result = $obj->lang->line('all_bet_code_multiple_number')." : 11, 14"; break;
			case 59: $result = $obj->lang->line('all_bet_code_multiple_number')." : 12, 15"; break;
			case 60: $result = $obj->lang->line('all_bet_code_multiple_number')." : 13, 14"; break;
			case 61: $result = $obj->lang->line('all_bet_code_multiple_number')." : 13, 16"; break;
			case 62: $result = $obj->lang->line('all_bet_code_multiple_number')." : 14, 15"; break;
			case 63: $result = $obj->lang->line('all_bet_code_multiple_number')." : 14, 17"; break;
			case 64: $result = $obj->lang->line('all_bet_code_multiple_number')." : 15, 18"; break;
			case 65: $result = $obj->lang->line('all_bet_code_multiple_number')." : 16, 17"; break;
			case 66: $result = $obj->lang->line('all_bet_code_multiple_number')." : 16, 19"; break;
			case 67: $result = $obj->lang->line('all_bet_code_multiple_number')." : 17, 18"; break;
			case 68: $result = $obj->lang->line('all_bet_code_multiple_number')." : 17, 20"; break;
			case 69: $result = $obj->lang->line('all_bet_code_multiple_number')." : 18, 21"; break;
			case 70: $result = $obj->lang->line('all_bet_code_multiple_number')." : 19, 20"; break;
			case 71: $result = $obj->lang->line('all_bet_code_multiple_number')." : 19, 22"; break;
			case 72: $result = $obj->lang->line('all_bet_code_multiple_number')." : 20, 21"; break;
			case 73: $result = $obj->lang->line('all_bet_code_multiple_number')." : 20, 23"; break;
			case 74: $result = $obj->lang->line('all_bet_code_multiple_number')." : 21, 24"; break;
			case 75: $result = $obj->lang->line('all_bet_code_multiple_number')." : 22, 23"; break;
			case 76: $result = $obj->lang->line('all_bet_code_multiple_number')." : 22, 25"; break;
			case 77: $result = $obj->lang->line('all_bet_code_multiple_number')." : 23, 24"; break;
			case 78: $result = $obj->lang->line('all_bet_code_multiple_number')." : 23, 26"; break;
			case 79: $result = $obj->lang->line('all_bet_code_multiple_number')." : 24, 27"; break;
			case 80: $result = $obj->lang->line('all_bet_code_multiple_number')." : 25, 26"; break;
			case 81: $result = $obj->lang->line('all_bet_code_multiple_number')." : 25, 28"; break;
			case 82: $result = $obj->lang->line('all_bet_code_multiple_number')." : 26, 27"; break;
			case 83: $result = $obj->lang->line('all_bet_code_multiple_number')." : 26, 29"; break;
			case 84: $result = $obj->lang->line('all_bet_code_multiple_number')." : 27, 30"; break;
			case 85: $result = $obj->lang->line('all_bet_code_multiple_number')." : 28, 29"; break;
			case 86: $result = $obj->lang->line('all_bet_code_multiple_number')." : 28, 31"; break;
			case 87: $result = $obj->lang->line('all_bet_code_multiple_number')." : 29, 30"; break;
			case 88: $result = $obj->lang->line('all_bet_code_multiple_number')." : 29, 32"; break;
			case 89: $result = $obj->lang->line('all_bet_code_multiple_number')." : 30, 33"; break;
			case 90: $result = $obj->lang->line('all_bet_code_multiple_number')." : 31, 32"; break;
			case 91: $result = $obj->lang->line('all_bet_code_multiple_number')." : 31, 34"; break;
			case 92: $result = $obj->lang->line('all_bet_code_multiple_number')." : 32, 33"; break;
			case 93: $result = $obj->lang->line('all_bet_code_multiple_number')." : 32, 35"; break;
			case 94: $result = $obj->lang->line('all_bet_code_multiple_number')." : 33, 36"; break;
			case 95: $result = $obj->lang->line('all_bet_code_multiple_number')." : 34, 35"; break;
			case 96: $result = $obj->lang->line('all_bet_code_multiple_number')." : 35, 36"; break;
			case 97: $result = $obj->lang->line('all_bet_code_multiple_number')." : 0, 1, 2"; break;
			case 98: $result = $obj->lang->line('all_bet_code_multiple_number')." : 0, 2, 3"; break;
			case 99: $result = $obj->lang->line('all_bet_code_multiple_number')." : 1, 2, 3"; break;
			case 100: $result = $obj->lang->line('all_bet_code_multiple_number')." : 4, 5, 6"; break;
			case 101: $result = $obj->lang->line('all_bet_code_multiple_number')." : 7, 8, 9"; break;
			case 102: $result = $obj->lang->line('all_bet_code_multiple_number')." : 10, 11, 121"; break;
			case 103: $result = $obj->lang->line('all_bet_code_multiple_number')." : 13, 14, 15"; break;
			case 104: $result = $obj->lang->line('all_bet_code_multiple_number')." : 16, 17, 18"; break;
			case 105: $result = $obj->lang->line('all_bet_code_multiple_number')." : 19, 20, 21"; break;
			case 106: $result = $obj->lang->line('all_bet_code_multiple_number')." : 22, 23, 24"; break;
			case 107: $result = $obj->lang->line('all_bet_code_multiple_number')." : 25, 26, 27"; break;
			case 108: $result = $obj->lang->line('all_bet_code_multiple_number')." : 28, 29, 30"; break;
			case 109: $result = $obj->lang->line('all_bet_code_multiple_number')." : 31, 32, 33"; break;
			case 110: $result = $obj->lang->line('all_bet_code_multiple_number')." : 34, 35, 36"; break;
			case 111: $result = $obj->lang->line('all_bet_code_multiple_number')." : 1, 2, 4, 5"; break;
			case 112: $result = $obj->lang->line('all_bet_code_multiple_number')." : 2, 3, 5, 6"; break;
			case 113: $result = $obj->lang->line('all_bet_code_multiple_number')." : 4, 5, 7, 8"; break;
			case 114: $result = $obj->lang->line('all_bet_code_multiple_number')." : 5, 6, 8, 9"; break;
			case 115: $result = $obj->lang->line('all_bet_code_multiple_number')." : 7, 8, 10, 11"; break;
			case 116: $result = $obj->lang->line('all_bet_code_multiple_number')." : 8, 9, 11, 12"; break;
			case 117: $result = $obj->lang->line('all_bet_code_multiple_number')." : 10, 11, 13, 14"; break;
			case 118: $result = $obj->lang->line('all_bet_code_multiple_number')." : 11, 12, 14, 15"; break;
			case 119: $result = $obj->lang->line('all_bet_code_multiple_number')." : 13, 14, 16, 17"; break;
			case 120: $result = $obj->lang->line('all_bet_code_multiple_number')." : 14, 15, 17, 18"; break;
			case 121: $result = $obj->lang->line('all_bet_code_multiple_number')." : 16, 17, 19, 20"; break;
			case 122: $result = $obj->lang->line('all_bet_code_multiple_number')." : 17, 18, 20, 21"; break;
			case 123: $result = $obj->lang->line('all_bet_code_multiple_number')." : 19, 20, 22, 23"; break;
			case 124: $result = $obj->lang->line('all_bet_code_multiple_number')." : 20, 21, 23, 24"; break;
			case 125: $result = $obj->lang->line('all_bet_code_multiple_number')." : 22, 23, 25, 26"; break;
			case 126: $result = $obj->lang->line('all_bet_code_multiple_number')." : 23, 24, 26, 27"; break;
			case 127: $result = $obj->lang->line('all_bet_code_multiple_number')." : 25, 26, 28, 29"; break;
			case 128: $result = $obj->lang->line('all_bet_code_multiple_number')." : 26, 27, 29, 30"; break;
			case 129: $result = $obj->lang->line('all_bet_code_multiple_number')." : 28, 29, 31, 32"; break;
			case 130: $result = $obj->lang->line('all_bet_code_multiple_number')." : 29, 30, 32, 33"; break;
			case 131: $result = $obj->lang->line('all_bet_code_multiple_number')." : 31, 32, 34, 35"; break;
			case 132: $result = $obj->lang->line('all_bet_code_multiple_number')." : 32, 33, 35, 36"; break;
			case 133: $result = $obj->lang->line('all_bet_code_multiple_number')." : 1, 2, 3, 4, 5, 6"; break;
			case 134: $result = $obj->lang->line('all_bet_code_multiple_number')." : 4, 5, 6, 7, 8, 9"; break;
			case 135: $result = $obj->lang->line('all_bet_code_multiple_number')." : 7, 8, 9, 10, 11, 12"; break;
			case 136: $result = $obj->lang->line('all_bet_code_multiple_number')." : 10, 11, 12, 13, 14, 15"; break;
			case 137: $result = $obj->lang->line('all_bet_code_multiple_number')." : 13, 14, 15, 16, 17, 18"; break;
			case 138: $result = $obj->lang->line('all_bet_code_multiple_number')." : 16, 17, 18, 19, 20, 21"; break;
			case 139: $result = $obj->lang->line('all_bet_code_multiple_number')." : 19, 20, 21, 22, 23, 24"; break;
			case 140: $result = $obj->lang->line('all_bet_code_multiple_number')." : 22, 23, 24, 25, 26, 27"; break;
			case 141: $result = $obj->lang->line('all_bet_code_multiple_number')." : 25, 26, 27, 28, 29, 30"; break;
			case 142: $result = $obj->lang->line('all_bet_code_multiple_number')." : 28, 29, 30, 31, 32, 33"; break;
			case 143: $result = $obj->lang->line('all_bet_code_multiple_number')." : 31, 32, 33, 34, 35, 36"; break;
			case 144: $result = $obj->lang->line('all_bet_code_first_twelve'); break;
			case 145: $result = $obj->lang->line('all_bet_code_second_twelve'); break;
			case 146: $result = $obj->lang->line('all_bet_code_third_twelve'); break;
			case 147: $result = $obj->lang->line('all_bet_code_first_row'); break;
			case 148: $result = $obj->lang->line('all_bet_code_second_row'); break;
			case 149: $result = $obj->lang->line('all_bet_code_third_row'); break;
			case 150: $result = $obj->lang->line('all_bet_code_small_eighteen'); break;
			case 151: $result = $obj->lang->line('all_bet_code_big_eighteen'); break;
			case 152: $result = $obj->lang->line('all_bet_code_odd'); break;
			case 153: $result = $obj->lang->line('all_bet_code_even'); break;
			case 154: $result = $obj->lang->line('all_bet_code_red'); break;
			case 155: $result = $obj->lang->line('all_bet_code_black'); break;
			case 156: $result = $obj->lang->line('all_bet_code_multiple_number')." : 0, 1, 2, 3"; break;
			default: $result = ""; break;
		}
	}else if($game_real_code == "moneywheel"){
		switch($bet_code)
		{
			case 0: $result = "1"; break;
			case 1: $result = "2"; break;
			case 2: $result = "9"; break;
			case 3: $result = "16"; break;
			case 4: $result = "24"; break;
			case 5: $result = $obj->lang->line('all_bet_code_fifty_gold'); break;
			case 6: $result = $obj->lang->line('all_bet_code_fifty_black'); break;
			case 7: $result = $obj->lang->line('all_bet_code_fish'); break;
			case 8: $result = $obj->lang->line('all_bet_code_prawn'); break;
			case 9: $result = $obj->lang->line('all_bet_code_crab'); break;
			case 10: $result = $obj->lang->line('all_bet_code_coin'); break;
			case 11: $result = $obj->lang->line('all_bet_code_calabash'); break;
			case 12: $result = $obj->lang->line('all_bet_code_chicken'); break;
			default: $result = ""; break;
		}
	}else if($game_real_code == "tip"){
		switch($bet_code)
		{
			case 830: $result = $obj->lang->line('all_bet_code_e_roulette'); break;
			case 831: $result = $obj->lang->line('all_bet_code_baccarat_e01'); break;
			case 832: $result = $obj->lang->line('all_bet_code_baccarat_e02'); break;
			case 833: $result = $obj->lang->line('all_bet_code_baccarat_e03'); break;
			case 834: $result = $obj->lang->line('all_bet_code_baccarat_e04'); break;
			case 835: $result = $obj->lang->line('all_bet_code_baccarat_e05'); break;
			case 836: $result = $obj->lang->line('all_bet_code_baccarat_e06'); break;
			case 837: $result = $obj->lang->line('all_bet_code_baccarat_e07'); break;
			case 838: $result = $obj->lang->line('all_bet_code_baccarat_e08'); break;
			case 839: $result = $obj->lang->line('all_bet_code_baccarat_e09'); break;
			case 841: $result = $obj->lang->line('all_bet_code_baccarat_e10'); break;
			case 842: $result = $obj->lang->line('all_bet_code_e_sicbo'); break;
			case 843: $result = $obj->lang->line('all_bet_code_e_dragon_tiger'); break;
			case 850: $result = $obj->lang->line('all_bet_code_p_dragon_tiger'); break;
			case 851: $result = $obj->lang->line('all_bet_code_baccarat_p01'); break;
			case 852: $result = $obj->lang->line('all_bet_code_baccarat_p02'); break;
			case 853: $result = $obj->lang->line('all_bet_code_baccarat_p03'); break;
			case 854: $result = $obj->lang->line('all_bet_code_baccarat_p04'); break;
			case 855: $result = $obj->lang->line('all_bet_code_baccarat_p05'); break;
			case 856: $result = $obj->lang->line('all_bet_code_baccarat_p06'); break;
			case 857: $result = $obj->lang->line('all_bet_code_baccarat_p07'); break;
			case 858: $result = $obj->lang->line('all_bet_code_baccarat_p08'); break;
			default: $result = ""; break;
		}
	}else if($game_real_code == "pokdeng"){
		switch($bet_code)
		{
			case 0: $result = $obj->lang->line('all_bet_code_player_one'); break;
			case 1: $result = $obj->lang->line('all_bet_code_player_two'); break;
			case 2: $result = $obj->lang->line('all_bet_code_player_three'); break;
			case 3: $result = $obj->lang->line('all_bet_code_player_four'); break;
			case 4: $result = $obj->lang->line('all_bet_code_player_five'); break;
			case 5: $result = $obj->lang->line('all_bet_code_player_one_pair'); break;
			case 6: $result = $obj->lang->line('all_bet_code_player_two_pair'); break;
			case 7: $result = $obj->lang->line('all_bet_code_player_three_pair'); break;
			case 8: $result = $obj->lang->line('all_bet_code_player_four_pair'); break;
			case 9: $result = $obj->lang->line('all_bet_code_player_five_pair'); break;
			default: $result = ""; break;
		}
	}else{
		$result = "";
	}
	$total_result = "";
	if(!empty($result_host)){
		$total_result .= $result_host."<br/>";
	}
	if(!empty($result)){
		$total_result .= $result."<br/>";
	}
	if(!empty($bet_id)){
		$total_result .= $obj->lang->line('all_result_bet_id')." : ".$bet_id."<br/>";
	}
	return $total_result;
}
function sa_game_result_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_real_code = (isset($result_info_array['GameType']) ? $result_info_array['GameType'] : "");
	$game_result = json_encode((isset($result_info_array['GameResult']) ? $result_info_array['GameResult'] : ""));
	$result = "-";
	if($game_real_code == "bac"){
		$game_result_array = json_decode($game_result,true, 512, JSON_BIGINT_AS_STRING);
		if(!empty($game_result_array)){
			if(isset($game_result_array['BaccaratResult']) && !empty($game_result_array['BaccaratResult'])){
				$result = "";
				$player_result = "";
				$banker_result = "";
				$player_point = 0;
				$banker_point = 0;
				foreach($game_result_array['BaccaratResult'] as $key => $value) {
					$suit = "";
					$rank = "";
					if(strpos($key, 'PlayerCard') !== false){
						if(isset($value['Suit']) && isset($value['Rank'])){
							switch($value['Suit'])
							{
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch($value['Rank'])
							{
								case "1": $rank = "A"; break;
								case "2": $rank = "2"; break;
								case "3": $rank = "3"; break;
								case "4": $rank = "4"; break;
								case "5": $rank = "5"; break;
								case "6": $rank = "6"; break;
								case "7": $rank = "7"; break;
								case "8": $rank = "8"; break;
								case "9": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							if($value['Rank'] < 10){
								$player_point += $value['Rank'];
							}
							if(!empty($suit) && !empty($rank)){
								if(empty($player_result)){
									$player_result .= $obj->lang->line('all_bet_code_player')." : ";
								}
								$player_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
									$player_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
										$player_result .= '<span class="rank">'.$rank.'</span>';
										$player_result .= '<span class="suit">&'.$suit.';</span>';
									$player_result .= '</div>';
								$player_result .= '</div>';
							}
						}
					}else if(strpos($key, 'BankerCard') !== false){
						if(isset($value['Suit']) && isset($value['Rank'])){
							switch($value['Suit'])
							{
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch($value['Rank'])
							{
								case "1": $rank = "A"; break;
								case "2": $rank = "2"; break;
								case "3": $rank = "3"; break;
								case "4": $rank = "4"; break;
								case "5": $rank = "5"; break;
								case "6": $rank = "6"; break;
								case "7": $rank = "7"; break;
								case "8": $rank = "8"; break;
								case "9": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							if($value['Rank'] < 10){
								$banker_point += $value['Rank'];
							}
							if(!empty($suit) && !empty($rank)){
								if(empty($banker_result)){
									$banker_result .= $obj->lang->line('all_bet_code_banker')." : ";
								}
								$banker_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
									$banker_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
										$banker_result .= '<span class="rank">'.$rank.'</span>';
										$banker_result .= '<span class="suit">&'.$suit.';</span>';
									$banker_result .= '</div>';
								$banker_result .= '</div>';
							}
						}
					}else{
					}
				}
				$player_point_divider = $player_point % 10;
				$banker_point_divider = $banker_point % 10;
				if($player_point_divider > $banker_point_divider){
					$result .= $obj->lang->line('all_bet_code_player').'<br>';
				}else if($player_point_divider == $banker_point_divider){
					$result .= $obj->lang->line('all_bet_code_tie').'<br>';
				}else{
					$result .= $obj->lang->line('all_bet_code_banker').'<br>';
				}
				$result .= $banker_result;
				if(!empty($player_result)){
					if(!empty($result)){
						$result .= "<br/>";
					}
					$result .= $player_result;
				}
			}
		} 
	}else if($game_real_code == "dtx"){
		$game_result_array = json_decode($game_result,true, 512, JSON_BIGINT_AS_STRING);
		if(!empty($game_result_array)){
			if(isset($game_result_array['DragonTigerResult']) && !empty($game_result_array['DragonTigerResult'])){
				$result = "";
				$dragon_result = "";
				$tiger_result = "";
				foreach($game_result_array['DragonTigerResult'] as $key => $value) {
					$suit = "";
					$rank = "";
					$dragon_point = 0;
					$tiger_point = 0;
					if(strpos($key, 'DragonCard') !== false){
						if(isset($value['Suit']) && isset($value['Rank'])){
							switch($value['Suit'])
							{
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch($value['Rank'])
							{
								case "1": $rank = "A"; break;
								case "2": $rank = "2"; break;
								case "3": $rank = "3"; break;
								case "4": $rank = "4"; break;
								case "5": $rank = "5"; break;
								case "6": $rank = "6"; break;
								case "7": $rank = "7"; break;
								case "8": $rank = "8"; break;
								case "9": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							if($value['Rank'] < 10){
								$dragon_point += $value['Rank'];
							}
							if(!empty($suit) && !empty($rank)){
								if(empty($dragon_result)){
									$dragon_result .= $obj->lang->line('all_bet_code_dragon')." : ";
								}
								$dragon_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
									$dragon_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
										$dragon_result .= '<span class="rank">'.$rank.'</span>';
										$dragon_result .= '<span class="suit">&'.$suit.';</span>';
									$dragon_result .= '</div>';
								$dragon_result .= '</div>';
							}
						}
					}else if(strpos($key, 'TigerCard') !== false){
						if(isset($value['Suit']) && isset($value['Rank'])){
							switch($value['Suit'])
							{
								case "1": $suit = "spades"; break;
								case "2": $suit = "hearts"; break;
								case "3": $suit = "clubs"; break;
								case "4": $suit = "diams"; break;
								default: $suit = ""; break;
							}
							switch($value['Rank'])
							{
								case "1": $rank = "A"; break;
								case "2": $rank = "2"; break;
								case "3": $rank = "3"; break;
								case "4": $rank = "4"; break;
								case "5": $rank = "5"; break;
								case "6": $rank = "6"; break;
								case "7": $rank = "7"; break;
								case "8": $rank = "8"; break;
								case "9": $rank = "9"; break;
								case "10": $rank = "10"; break;
								case "11": $rank = "J"; break;
								case "12": $rank = "Q"; break;
								case "13": $rank = "K"; break;
								default: $rank = ""; break;
							}
							if($value['Rank'] < 10){
								$tiger_point += $value['Rank'];
							}
							if(!empty($suit) && !empty($rank)){
								if(empty($tiger_result)){
									$tiger_result .= $obj->lang->line('all_bet_code_tiger')." : ";
								}
								$tiger_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
									$tiger_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
										$tiger_result .= '<span class="rank">'.$rank.'</span>';
										$tiger_result .= '<span class="suit">&'.$suit.';</span>';
									$tiger_result .= '</div>';
								$tiger_result .= '</div>';
							}
						}
					}else{
					}
				}
				$dragon_point_divider = $dragon_point % 10;
				$tiger_point_divider = $tiger_point % 10;
				if($dragon_point_divider > $tiger_point_divider){
					$result .= $obj->lang->line('all_bet_code_dragon').'<br>';
				}else if($dragon_point_divider == $tiger_point_divider){
					$result .= $obj->lang->line('all_bet_code_tie').'<br>';
				}else{
					$result .= $obj->lang->line('all_bet_code_tiger').'<br>';
				}
				$result .= $dragon_result;
				if(!empty($tiger_result)){
					if(!empty($result)){
						$result .= "<br/>";
					}
					$result .= $tiger_result;
				}
			}
		}
	}else if($game_real_code == "sicbo"){
		$game_result_array = json_decode($game_result,true, 512, JSON_BIGINT_AS_STRING);
		if(!empty($game_result_array)){
			if(isset($game_result_array['SicboResult']) && !empty($game_result_array['SicboResult'])){
				$result = "";
				foreach($game_result_array['SicboResult'] as $key => $value) {
					if(strpos($key, 'Dice') !== false){
						$result .= '<span class="dice dice-'.$value.'" title="'.$obj->lang->line('all_bet_code_dice').' : '.$value.'"></span>';
					}
				}
			}
		}
	}else if($game_real_code == "ftan"){
		$game_result_array = json_decode($game_result,true, 512, JSON_BIGINT_AS_STRING);
		if(!empty($game_result_array)){
			if(isset($game_result_array['FantanResult']) && !empty($game_result_array['FantanResult'])){
				if(isset($game_result_array['FantanResult']['Point'])){
					$result = $game_result_array['FantanResult']['Point'];
				}
			}
		}
	}else if($game_real_code == "rot"){
		$game_result_array = json_decode($game_result,true, 512, JSON_BIGINT_AS_STRING);
		if(!empty($game_result_array)){
			if(isset($game_result_array['RouletteResult']) && !empty($game_result_array['RouletteResult'])){
				if(isset($game_result_array['RouletteResult']['Point'])){
					$result = $game_result_array['RouletteResult']['Point'];
				}
			}
		}
	}else if($game_real_code == "moneywheel"){
		$game_result_array = json_decode($game_result,true, 512, JSON_BIGINT_AS_STRING);
		if(!empty($game_result_array)){
			if(isset($game_result_array['MoneyWheelResult']) && !empty($game_result_array['MoneyWheelResult'])){
				$result = "";
				$main_result = "";
				$side_result = "";
				$dice_type = "";
				$dice_name = "";
				if(isset($game_result_array['MoneyWheelResult']['Main'])){
					switch($game_result_array['MoneyWheelResult']['Main'])
					{
						case 0: $main_result = "1"; break;
						case 1: $main_result = "2"; break;
						case 2: $main_result = "9"; break;
						case 3: $main_result = "16"; break;
						case 4: $main_result = "24"; break;
						case 5: $main_result = $obj->lang->line('all_bet_code_fifty_gold'); break;
						case 6: $main_result = $obj->lang->line('all_bet_code_fifty_black'); break;
						default: $main_result = ""; break;
					}
				}
				if(!empty($main_result)){
					$result .= $obj->lang->line('all_bet_code_multiplier').' : '.$main_result;
				}
				if(isset($game_result_array['MoneyWheelResult']['Side'])){
					switch($game_result_array['MoneyWheelResult']['Side'])
					{
						case 7: $dice_type = "fish"; $dice_name = $obj->lang->line('all_bet_code_fish'); break;
						case 8: $dice_type = "prawn"; $dice_name = $obj->lang->line('all_bet_code_prawn'); break;
						case 9: $dice_type = "crab"; $dice_name = $obj->lang->line('all_bet_code_crab'); break;
						case 10: $dice_type = "money"; $dice_name = $obj->lang->line('all_bet_code_coin'); break;
						case 11: $dice_type = "calabash"; $dice_name = $obj->lang->line('all_bet_code_calabash'); break;
						case 12: $dice_type = "chicken"; $dice_name = $obj->lang->line('all_bet_code_chicken'); break;
						default: $dice_type = ""; break;
					}
					if(!empty($dice_type)){
						$side_result .= $obj->lang->line('all_bet_code_side_bet').' : ';
						$side_result .= '<span class="dice dice-'.$dice_type.'" title="'.$obj->lang->line('all_bet_code_dice').' : '.$dice_name.'"></span>';
					}
				}
				if(!empty($side_result)){	
					if(!empty($result)){
						$result .= "<br/>";
					}
					$result .= $side_result;
				}
			}
		}
	}else if($game_real_code == "tip"){
		$result = "-";
	}else if($game_real_code == "pokdeng"){
		$game_result_array = json_decode($game_result,true, 512, JSON_BIGINT_AS_STRING);
		if(isset($game_result_array['PokDengResult']) && !empty($game_result_array['PokDengResult'])){
			$result = "";
			$player_one_result = "";
			$player_two_result = "";
			$player_three_result = "";
			$player_four_result = "";
			$player_five_result = "";
			$banker_result = "";
			foreach($game_result_array['PokDengResult'] as $key => $value) {
				$suit = "";
				$rank = "";
				$player_one_point = 0;
				$player_two_point = 0;
				$player_three_point = 0;
				$player_four_point = 0;
				$player_five_point = 0;
				$banker_point = 0;
				if(strpos($key, 'Player1Card') !== false){
					if(isset($value['Suit']) && isset($value['Rank'])){
						switch($value['Suit'])
						{
							case "1": $suit = "spades"; break;
							case "2": $suit = "hearts"; break;
							case "3": $suit = "clubs"; break;
							case "4": $suit = "diams"; break;
							default: $suit = ""; break;
						}
						switch($value['Rank'])
						{
							case "1": $rank = "A"; break;
							case "2": $rank = "2"; break;
							case "3": $rank = "3"; break;
							case "4": $rank = "4"; break;
							case "5": $rank = "5"; break;
							case "6": $rank = "6"; break;
							case "7": $rank = "7"; break;
							case "8": $rank = "8"; break;
							case "9": $rank = "9"; break;
							case "10": $rank = "10"; break;
							case "11": $rank = "J"; break;
							case "12": $rank = "Q"; break;
							case "13": $rank = "K"; break;
							default: $rank = ""; break;
						}
						if($value['Rank'] < 10){
							$player_one_point += $value['Rank'];
						}
						if(!empty($suit) && !empty($rank)){
							if(empty($player_one_result)){
								$player_one_result .= $obj->lang->line('all_bet_code_player_one')." : ";
							}
							$player_one_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$player_one_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$player_one_result .= '<span class="rank">'.$rank.'</span>';
									$player_one_result .= '<span class="suit">&'.$suit.';</span>';
								$player_one_result .= '</div>';
							$player_one_result .= '</div>';
						}
					}
				}else if(strpos($key, 'Player2Card') !== false){
					if(isset($value['Suit']) && isset($value['Rank'])){
						switch($value['Suit'])
						{
							case "1": $suit = "spades"; break;
							case "2": $suit = "hearts"; break;
							case "3": $suit = "clubs"; break;
							case "4": $suit = "diams"; break;
							default: $suit = ""; break;
						}
						switch($value['Rank'])
						{
							case "1": $rank = "A"; break;
							case "2": $rank = "2"; break;
							case "3": $rank = "3"; break;
							case "4": $rank = "4"; break;
							case "5": $rank = "5"; break;
							case "6": $rank = "6"; break;
							case "7": $rank = "7"; break;
							case "8": $rank = "8"; break;
							case "9": $rank = "9"; break;
							case "10": $rank = "10"; break;
							case "11": $rank = "J"; break;
							case "12": $rank = "Q"; break;
							case "13": $rank = "K"; break;
							default: $rank = ""; break;
						}
						if($value['Rank'] < 10){
							$player_two_point += $value['Rank'];
						}
						if(!empty($suit) && !empty($rank)){
							if(empty($player_two_result)){
								$player_two_result .= $obj->lang->line('all_bet_code_player_two')." : ";
							}
							$player_two_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$player_two_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$player_two_result .= '<span class="rank">'.$rank.'</span>';
									$player_two_result .= '<span class="suit">&'.$suit.';</span>';
								$player_two_result .= '</div>';
							$player_two_result .= '</div>';
						}
					}
				}else if(strpos($key, 'Player3Card') !== false){
					if(isset($value['Suit']) && isset($value['Rank'])){
						switch($value['Suit'])
						{
							case "1": $suit = "spades"; break;
							case "2": $suit = "hearts"; break;
							case "3": $suit = "clubs"; break;
							case "4": $suit = "diams"; break;
							default: $suit = ""; break;
						}
						switch($value['Rank'])
						{
							case "1": $rank = "A"; break;
							case "2": $rank = "2"; break;
							case "3": $rank = "3"; break;
							case "4": $rank = "4"; break;
							case "5": $rank = "5"; break;
							case "6": $rank = "6"; break;
							case "7": $rank = "7"; break;
							case "8": $rank = "8"; break;
							case "9": $rank = "9"; break;
							case "10": $rank = "10"; break;
							case "11": $rank = "J"; break;
							case "12": $rank = "Q"; break;
							case "13": $rank = "K"; break;
							default: $rank = ""; break;
						}
						if($value['Rank'] < 10){
							$player_three_point += $value['Rank'];
						}
						if(!empty($suit) && !empty($rank)){
							if(empty($player_three_result)){
								$player_three_result .= $obj->lang->line('all_bet_code_player_three')." : ";
							}
							$player_three_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$player_three_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$player_three_result .= '<span class="rank">'.$rank.'</span>';
									$player_three_result .= '<span class="suit">&'.$suit.';</span>';
								$player_three_result .= '</div>';
							$player_three_result .= '</div>';
						}
					}
				}else if(strpos($key, 'Player4Card') !== false){
					if(isset($value['Suit']) && isset($value['Rank'])){
						switch($value['Suit'])
						{
							case "1": $suit = "spades"; break;
							case "2": $suit = "hearts"; break;
							case "3": $suit = "clubs"; break;
							case "4": $suit = "diams"; break;
							default: $suit = ""; break;
						}
						switch($value['Rank'])
						{
							case "1": $rank = "A"; break;
							case "2": $rank = "2"; break;
							case "3": $rank = "3"; break;
							case "4": $rank = "4"; break;
							case "5": $rank = "5"; break;
							case "6": $rank = "6"; break;
							case "7": $rank = "7"; break;
							case "8": $rank = "8"; break;
							case "9": $rank = "9"; break;
							case "10": $rank = "10"; break;
							case "11": $rank = "J"; break;
							case "12": $rank = "Q"; break;
							case "13": $rank = "K"; break;
							default: $rank = ""; break;
						}
						if($value['Rank'] < 10){
							$player_four_point += $value['Rank'];
						}
						if(!empty($suit) && !empty($rank)){
							if(empty($player_four_result)){
								$player_four_result .= $obj->lang->line('all_bet_code_player_four')." : ";
							}
							$player_four_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$player_four_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$player_four_result .= '<span class="rank">'.$rank.'</span>';
									$player_four_result .= '<span class="suit">&'.$suit.';</span>';
								$player_four_result .= '</div>';
							$player_four_result .= '</div>';
						}
					}
				}else if(strpos($key, 'Player5Card') !== false){
					if(isset($value['Suit']) && isset($value['Rank'])){
						switch($value['Suit'])
						{
							case "1": $suit = "spades"; break;
							case "2": $suit = "hearts"; break;
							case "3": $suit = "clubs"; break;
							case "4": $suit = "diams"; break;
							default: $suit = ""; break;
						}
						switch($value['Rank'])
						{
							case "1": $rank = "A"; break;
							case "2": $rank = "2"; break;
							case "3": $rank = "3"; break;
							case "4": $rank = "4"; break;
							case "5": $rank = "5"; break;
							case "6": $rank = "6"; break;
							case "7": $rank = "7"; break;
							case "8": $rank = "8"; break;
							case "9": $rank = "9"; break;
							case "10": $rank = "10"; break;
							case "11": $rank = "J"; break;
							case "12": $rank = "Q"; break;
							case "13": $rank = "K"; break;
							default: $rank = ""; break;
						}
						if($value['Rank'] < 10){
							$player_five_point += $value['Rank'];
						}
						if(!empty($suit) && !empty($rank)){
							if(empty($player_five_result)){
								$player_five_result .= $obj->lang->line('all_bet_code_player_five')." : ";
							}
							$player_five_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$player_five_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$player_five_result .= '<span class="rank">'.$rank.'</span>';
									$player_five_result .= '<span class="suit">&'.$suit.';</span>';
								$player_five_result .= '</div>';
							$player_five_result .= '</div>';
						}
					}
				}else if(strpos($key, 'BankerCard') !== false){
					if(isset($value['Suit']) && isset($value['Rank'])){
						switch($value['Suit'])
						{
							case "1": $suit = "spades"; break;
							case "2": $suit = "hearts"; break;
							case "3": $suit = "clubs"; break;
							case "4": $suit = "diams"; break;
							default: $suit = ""; break;
						}
						switch($value['Rank'])
						{
							case "1": $rank = "A"; break;
							case "2": $rank = "2"; break;
							case "3": $rank = "3"; break;
							case "4": $rank = "4"; break;
							case "5": $rank = "5"; break;
							case "6": $rank = "6"; break;
							case "7": $rank = "7"; break;
							case "8": $rank = "8"; break;
							case "9": $rank = "9"; break;
							case "10": $rank = "10"; break;
							case "11": $rank = "J"; break;
							case "12": $rank = "Q"; break;
							case "13": $rank = "K"; break;
							default: $rank = ""; break;
						}
						if($value['Rank'] < 10){
							$banker_point += $value['Rank'];
						}
						if(!empty($suit) && !empty($rank)){
							if(empty($banker_result)){
								$banker_result .= $obj->lang->line('all_bet_code_banker')." : ";
							}
							$banker_result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
								$banker_result .= '<div class="card rank-'.$rank.' '.$suit.'">';
									$banker_result .= '<span class="rank">'.$rank.'</span>';
									$banker_result .= '<span class="suit">&'.$suit.';</span>';
								$banker_result .= '</div>';
							$banker_result .= '</div>';
						}
					}
				}else{
				}
			}
			/*
			$player_one_point = $player_one_point % 10;
			$player_two_point = $player_two_point % 10;
			$player_three_point = $player_three_point % 10;
			$player_four_point = $player_four_point % 10;
			$player_five_point = $player_five_point % 10;
			$banker_point = $banker_point % 10;
			if($banker_point > $player_one_point){
				$result .= $obj->lang->line('all_bet_code_dragon').'<br>';
			}else if($banker_point == $player_one_point){
			}else{
			}
			if($dragon_point_divider > $tiger_point_divider){
				$result .= $obj->lang->line('all_bet_code_player_one') ." : " . $obj->lang->line('all_bet_code_player_one') .'<br>';
			}else if($dragon_point_divider == $tiger_point_divider){
				$result .= $obj->lang->line('all_bet_code_player_one').'<br>';
			}else{
				$result .= $obj->lang->line('all_bet_code_player_one').'<br>';
			}
			*/
			$result .= $player_one_result;
			if(!empty($player_two_result)){
				if(!empty($result)){
					$result .= "<br/>";
				}
				$result .= $player_two_result;
			}
			if(!empty($player_three_result)){
				if(!empty($result)){
					$result .= "<br/>";
				}
				$result .= $player_three_result;
			}
			if(!empty($player_four_result)){
				if(!empty($result)){
					$result .= "<br/>";
				}
				$result .= $player_four_result;
			}
			if(!empty($player_five_result)){
				if(!empty($result)){
					$result .= "<br/>";
				}
				$result .= $player_five_result;
			}
			if(!empty($banker_result)){
				if(!empty($result)){
					$result .= "<br/>";
				}
				$result .= $banker_result;
			}
		}
	}else{
		$result = "-";
	}
	return $result;
}
function spsb_game_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$result = "";
	$TicketID = (isset($result_info_array['TicketID']) ? $result_info_array['TicketID'] : "");
	if(empty($TicketID)){
		//old spsb
		$fashion = (isset($result_info_array['fashion']) ? $result_info_array['fashion'] : "");
		$team_no = (isset($result_info_array['team_no']) ? $result_info_array['team_no'] : "");
		if($fashion == 20){
			if(isset($result_info_array['detail']) && !empty($result_info_array['detail']) && sizeof($result_info_array['detail'])>0){
				foreach($result_info_array['detail'] as $result_info_array_row){
					if(!empty($result)){
						$result .= "<br><hr><br>";
					}else{
						$result .= "<br>";
					}
					switch($result_info_array_row['team_no'])
					{
						case 1: $result .= $obj->lang->line('all_sportbook_bet_america_baseball')."<br>"; break;
						case 2: $result .= $obj->lang->line('all_sportbook_bet_japan_baseball')."<br>"; break;
						case 3: $result .= $obj->lang->line('all_sportbook_bet_taiwan_baseball')."<br>"; break;
						case 4: $result .= $obj->lang->line('all_sportbook_bet_korea_baseball')."<br>"; break;
						case 5: $result .= $obj->lang->line('all_sportbook_bet_ice_hockey')."<br>"; break;
						case 6: $result .= $obj->lang->line('all_sportbook_bet_basketball')."<br>"; break;
						case 7: $result .= $obj->lang->line('all_sportbook_bet_lottery')."<br>"; break;
						case 8: $result .= $obj->lang->line('all_sportbook_bet_america_scoccer')."<br>"; break;
						case 9: $result .= $obj->lang->line('all_sportbook_bet_tennis')."<br>"; break;
						case 10: $result .= $obj->lang->line('all_sportbook_bet_scoccer')."<br>"; break;
						case 11: $result .= $obj->lang->line('all_sportbook_bet_index')."<br>"; break;
						case 12: $result .= $obj->lang->line('all_sportbook_bet_horse_race')."<br>"; break;
						case 13: $result .= $obj->lang->line('all_sportbook_bet_esport')."<br>"; break;
						case 14: $result .= $obj->lang->line('all_sportbook_bet_others')."<br>"; break;
						case 15: $result .= $obj->lang->line('all_sportbook_bet_fifa_world_cup')."<br>"; break;
						case 20: $result .= $obj->lang->line('all_sportbook_bet_fifa_world_cup')."<br>"; break;
						default: $result .= $obj->lang->line('all_sportbook_bet_others')."<br>"; break;
					}
					if(isset($result_info_array_row['league'])){
						$result .= $result_info_array_row['league'] . '<br>';
					}
					$result .= $result_info_array_row['main_team'] ."(".$obj->lang->line('all_sportbook_bet_main').")". " -vs- " . $result_info_array_row['visit_team'] ."(".$obj->lang->line('all_sportbook_bet_visit').")". '<br>';
				}
			}
		}else{
			switch($team_no)
			{
				case 1: $result .= $obj->lang->line('all_sportbook_bet_america_baseball')."<br>"; break;
				case 2: $result .= $obj->lang->line('all_sportbook_bet_japan_baseball')."<br>"; break;
				case 3: $result .= $obj->lang->line('all_sportbook_bet_taiwan_baseball')."<br>"; break;
				case 4: $result .= $obj->lang->line('all_sportbook_bet_korea_baseball')."<br>"; break;
				case 5: $result .= $obj->lang->line('all_sportbook_bet_ice_hockey')."<br>"; break;
				case 6: $result .= $obj->lang->line('all_sportbook_bet_basketball')."<br>"; break;
				case 7: $result .= $obj->lang->line('all_sportbook_bet_lottery')."<br>"; break;
				case 8: $result .= $obj->lang->line('all_sportbook_bet_america_scoccer')."<br>"; break;
				case 9: $result .= $obj->lang->line('all_sportbook_bet_tennis')."<br>"; break;
				case 10: $result .= $obj->lang->line('all_sportbook_bet_scoccer')."<br>"; break;
				case 11: $result .= $obj->lang->line('all_sportbook_bet_index')."<br>"; break;
				case 12: $result .= $obj->lang->line('all_sportbook_bet_horse_race')."<br>"; break;
				case 13: $result .= $obj->lang->line('all_sportbook_bet_esport')."<br>"; break;
				case 14: $result .= $obj->lang->line('all_sportbook_bet_others')."<br>"; break;
				case 15: $result .= $obj->lang->line('all_sportbook_bet_fifa_world_cup')."<br>"; break;
				case 20: $result .= $obj->lang->line('all_sportbook_bet_fifa_world_cup')."<br>"; break;
				default: $result .= $obj->lang->line('all_sportbook_bet_others')."<br>"; break;
			}
			if(isset($result_info_array['league'])){
				$result .= $result_info_array['league'] . '<br>';
			}
			$result .= $result_info_array['main_team'] ."(".$obj->lang->line('all_sportbook_bet_main').")". " -vs- " . $result_info_array['visit_team'] ."(".$obj->lang->line('all_sportbook_bet_visit').")". '<br>';
		}
	}else{
		//new sportbook
		$BetType = (isset($result_info_array['BetType']) ? $result_info_array['BetType'] : "");
		if($BetType == "1"){
			if(isset($result_info_array['dataBet'][0]['CatName'])){
				$result .= $result_info_array['dataBet'][0]['CatName'] . '<br>';
			}
			if(isset($result_info_array['dataBet'][0]['LeagueName'])){
				$result .= $result_info_array['dataBet'][0]['LeagueName'] . '<br>';
			}
			if(isset($result_info_array['dataBet'][0]['CatID']) && $result_info_array['dataBet'][0]['CatID'] == "83"){
			    $result .= $result_info_array['dataBet'][0]['AwayTeam'] ." - " . $result_info_array['dataBet'][0]['HomeTeam'] .'['.$result_info_array['dataBet'][0]['HomeScore'].']<br>';
			}else{
			    $result .= $result_info_array['dataBet'][0]['HomeTeam'] ."(".$obj->lang->line('all_sportbook_bet_main').")".'['.$result_info_array['dataBet'][0]['HomeScore'].']'. " -vs- " . $result_info_array['dataBet'][0]['AwayTeam'] ."(".$obj->lang->line('all_sportbook_bet_visit').")" .'['.$result_info_array['dataBet'][0]['AwayScore'].']<br>';
			}
		}else{
            foreach($result_info_array['dataBet'] as $result_row){
                if(!empty($result)){
					$result .= "<br><hr><br>";
				}else{
					$result .= "<br>";
				}
                if(isset($result_row['CatName'])){
    				$result .= $result_row['CatName'] . '<br>';
    			}
    			if(isset($result_row['LeagueName'])){
    				$result .= $result_row['LeagueName'] . '<br>';
    			}
    			if(isset($result_row['CatID']) && $result_row['CatID'] == "83"){
    			    $result .= $result_row['AwayTeam'] ." - " . $result_row['HomeTeam'] .'['.$result_row['HomeScore'].']<br>';
    			}else{
    			    $result .= $result_row['HomeTeam'] ."(".$obj->lang->line('all_sportbook_bet_main').")".'['.$result_row['HomeScore'].']'. " -vs- " . $result_row['AwayTeam'] ."(".$obj->lang->line('all_sportbook_bet_visit').")" .'['.$result_row['AwayScore'].']<br>';
    			}
            }
		}
	}
	return $result;
}
function spsb_bet_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$result = "";
	$TicketID = (isset($result_info_array['TicketID']) ? $result_info_array['TicketID'] : "");
	if(empty($TicketID)){
    	$fashion = (isset($result_info_array['fashion']) ? $result_info_array['fashion'] : "");
    	$mv_set = (isset($result_info_array['mv_set']) ? $result_info_array['mv_set'] : "");
    	if($fashion == 20){
    		$odd_rate = 1;
    		if(isset($result_info_array['detail']) && !empty($result_info_array['detail']) && sizeof($result_info_array['detail'])>0){
    			foreach($result_info_array['detail'] as $result_info_array_row){
    				$odd_rate *= ($result_info_array_row['compensate'] + 1);
    				if(!empty($result)){
    					$result .= "<br><hr><br>";
    				}else{
    					$result .= "<br>";
    				}
    				switch((isset($result_info_array_row['fashion']) ? $result_info_array_row['fashion'] : ""))
    				{
    					case 1: 
    						switch((isset($result_info_array_row['mv_set']) ? $result_info_array_row['mv_set'] : "")){
    							case 0: $result .= $obj->lang->line('all_sportbook_bet_type_handicap')."[".$obj->lang->line('all_sportbook_bet_visit_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							case 1: $result .= $obj->lang->line('all_sportbook_bet_type_handicap')."[".$obj->lang->line('all_sportbook_bet_main_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							case 2: $result .= $obj->lang->line('all_sportbook_bet_type_handicap')."[".$obj->lang->line('all_sportbook_bet_draw')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							default: $result .= ""; break;
    						}break;
    					case 2: 
    						switch((isset($result_info_array_row['mv_set']) ? $result_info_array_row['mv_set'] : "")){
    							case 0: $result .= $obj->lang->line('all_sportbook_bet_type_over_under')."[".$obj->lang->line('all_bet_code_big')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							case 1: $result .= $obj->lang->line('all_sportbook_bet_type_over_under')."[".$obj->lang->line('all_bet_code_small')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    						}break;
    					case 3: 
    						switch((isset($result_info_array_row['mv_set']) ? $result_info_array_row['mv_set'] : "")){
    							case 0: $result .= $obj->lang->line('all_sportbook_bet_type_money_line')."[".$obj->lang->line('all_sportbook_bet_visit_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							case 1: $result .= $obj->lang->line('all_sportbook_bet_type_money_line')."[".$obj->lang->line('all_sportbook_bet_main_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							case 2: $result .= $obj->lang->line('all_sportbook_bet_type_money_line')."[".$obj->lang->line('all_sportbook_bet_draw')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							default: $result .= ""; break;
    						}break;
    					case 4: 
    						switch((isset($result_info_array_row['mv_set']) ? $result_info_array_row['mv_set'] : "")){
    							case 0: $result .= $obj->lang->line('all_sportbook_bet_type_odd_even')."[".$obj->lang->line('all_bet_code_odd')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							case 1: $result .= $obj->lang->line('all_sportbook_bet_type_odd_even')."[".$obj->lang->line('all_bet_code_even')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    						}break;
    					case 5: 
    						switch((isset($result_info_array_row['mv_set']) ? $result_info_array_row['mv_set'] : "")){
    							case 0: $result .= $obj->lang->line('all_sportbook_bet_type_one_lose_two_win')."[".$obj->lang->line('all_sportbook_bet_visit_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							case 1: $result .= $obj->lang->line('all_sportbook_bet_type_one_lose_two_win')."[".$obj->lang->line('all_sportbook_bet_main_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							case 2: $result .= $obj->lang->line('all_sportbook_bet_type_one_lose_two_win')."[".$obj->lang->line('all_sportbook_bet_draw')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							default: $result .= ""; break;
    						}break;
    					case 10: 
    						switch((isset($result_info_array_row['mv_set']) ? $result_info_array_row['mv_set'] : "")){
    							case 0: $result .= $obj->lang->line('all_sportbook_bet_type_first_goal')."[".$obj->lang->line('all_sportbook_bet_visit_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							case 1: $result .= $obj->lang->line('all_sportbook_bet_type_first_goal')."[".$obj->lang->line('all_sportbook_bet_main_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							case 2: $result .= $obj->lang->line('all_sportbook_bet_type_first_goal')."[".$obj->lang->line('all_sportbook_bet_draw')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							default: $result .= ""; break;
    						}break;
    					case 11: 
    						switch((isset($result_info_array_row['mv_set']) ? $result_info_array_row['mv_set'] : "")){
    							case 0: $result .= $obj->lang->line('all_sportbook_bet_type_last_goal')."[".$obj->lang->line('all_sportbook_bet_visit_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							case 1: $result .= $obj->lang->line('all_sportbook_bet_type_last_goal')."[".$obj->lang->line('all_sportbook_bet_main_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							case 2: $result .= $obj->lang->line('all_sportbook_bet_type_last_goal')."[".$obj->lang->line('all_sportbook_bet_draw')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							default: $result .= ""; break;
    						}break;
    					case 13: 
    						switch((isset($result_info_array_row['mv_set']) ? $result_info_array_row['mv_set'] : "")){
    							case 0: $result .= $obj->lang->line('all_sportbook_bet_type_single_period_higest_score')."[".$obj->lang->line('all_sportbook_bet_visit_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							case 1: $result .= $obj->lang->line('all_sportbook_bet_type_single_period_higest_score')."[".$obj->lang->line('all_sportbook_bet_main_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							case 2: $result .= $obj->lang->line('all_sportbook_bet_type_single_period_higest_score')."[".$obj->lang->line('all_sportbook_bet_draw')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							default: $result .= ""; break;
    						}break;
    					case 14: 
    						switch((isset($result_info_array_row['mv_set']) ? $result_info_array_row['mv_set'] : "")){
    							case 0: $result .= $obj->lang->line('all_sportbook_bet_type_correct_score')."[".$obj->lang->line('all_sportbook_bet_type_main_more_visit')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							case 1: $result .= $obj->lang->line('all_sportbook_bet_type_correct_score')."[".$obj->lang->line('all_sportbook_bet_type_visit_more_main')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							case 2: $result .= $obj->lang->line('all_sportbook_bet_type_correct_score')."[".$obj->lang->line('all_sportbook_bet_type_visit_same_main')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    							default: $result .= ""; break;
    						}break;
    					case 15: $result .= $obj->lang->line('all_sportbook_bet_type_half_or_full_time')."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    					case 16: $result .= $obj->lang->line('all_sportbook_bet_type_goal_count')."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array_row['chum_num']) ? $result_info_array_row['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array_row['compensate']) ? $result_info_array_row['compensate'] : "")."<br>"; break;
    					default: $result .= ""; break;
    				}
    			}
    			$odd_rate = bcdiv(($odd_rate - 1),1,2);
    			$result .= "<br><hr><br>";
    			$result .= $obj->lang->line('all_sportbook_bet_type_parlay')."<br>";
    			$result .= $obj->lang->line('all_sportbook_odds_rate').":".$odd_rate."<br>";
    			$result .= $obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>";
    		}
    	}else{
    		switch($fashion)
    		{
    			case 1: 
    				switch($mv_set){
    					case 0: $result .= $obj->lang->line('all_sportbook_bet_type_handicap')."[".$obj->lang->line('all_sportbook_bet_visit_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					case 1: $result .= $obj->lang->line('all_sportbook_bet_type_handicap')."[".$obj->lang->line('all_sportbook_bet_main_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					case 2: $result .= $obj->lang->line('all_sportbook_bet_type_handicap')."[".$obj->lang->line('all_sportbook_bet_draw')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					default: $result .= ""; break;
    				}break;
    			case 2: 
    				switch($mv_set){
    					case 0: $result .= $obj->lang->line('all_sportbook_bet_type_over_under')."[".$obj->lang->line('all_bet_code_big')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					case 1: $result .= $obj->lang->line('all_sportbook_bet_type_over_under')."[".$obj->lang->line('all_bet_code_small')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    				}break;
    			case 3: 
    				switch($mv_set){
    					case 0: $result .= $obj->lang->line('all_sportbook_bet_type_money_line')."[".$obj->lang->line('all_sportbook_bet_visit_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					case 1: $result .= $obj->lang->line('all_sportbook_bet_type_money_line')."[".$obj->lang->line('all_sportbook_bet_main_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					case 2: $result .= $obj->lang->line('all_sportbook_bet_type_money_line')."[".$obj->lang->line('all_sportbook_bet_draw')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					default: $result .= ""; break;
    				}break;
    			case 4: 
    				switch($mv_set){
    					case 0: $result .= $obj->lang->line('all_sportbook_bet_type_odd_even')."[".$obj->lang->line('all_bet_code_odd')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					case 1: $result .= $obj->lang->line('all_sportbook_bet_type_odd_even')."[".$obj->lang->line('all_bet_code_even')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    				}break;
    			case 5: 
    				switch($mv_set){
    					case 0: $result .= $obj->lang->line('all_sportbook_bet_type_one_lose_two_win')."[".$obj->lang->line('all_sportbook_bet_visit_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					case 1: $result .= $obj->lang->line('all_sportbook_bet_type_one_lose_two_win')."[".$obj->lang->line('all_sportbook_bet_main_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					case 2: $result .= $obj->lang->line('all_sportbook_bet_type_one_lose_two_win')."[".$obj->lang->line('all_sportbook_bet_draw')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					default: $result .= ""; break;
    				}break;
    			case 10: 
    				switch($mv_set){
    					case 0: $result .= $obj->lang->line('all_sportbook_bet_type_first_goal')."[".$obj->lang->line('all_sportbook_bet_visit_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					case 1: $result .= $obj->lang->line('all_sportbook_bet_type_first_goal')."[".$obj->lang->line('all_sportbook_bet_main_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					case 2: $result .= $obj->lang->line('all_sportbook_bet_type_first_goal')."[".$obj->lang->line('all_sportbook_bet_draw')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					default: $result .= ""; break;
    				}break;
    			case 11: 
    				switch($mv_set){
    					case 0: $result .= $obj->lang->line('all_sportbook_bet_type_last_goal')."[".$obj->lang->line('all_sportbook_bet_visit_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					case 1: $result .= $obj->lang->line('all_sportbook_bet_type_last_goal')."[".$obj->lang->line('all_sportbook_bet_main_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					case 2: $result .= $obj->lang->line('all_sportbook_bet_type_last_goal')."[".$obj->lang->line('all_sportbook_bet_draw')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					default: $result .= ""; break;
    				}break;
    			case 13: 
    				switch($mv_set){
    					case 0: $result .= $obj->lang->line('all_sportbook_bet_type_single_period_higest_score')."[".$obj->lang->line('all_sportbook_bet_visit_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					case 1: $result .= $obj->lang->line('all_sportbook_bet_type_single_period_higest_score')."[".$obj->lang->line('all_sportbook_bet_main_team')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					case 2: $result .= $obj->lang->line('all_sportbook_bet_type_single_period_higest_score')."[".$obj->lang->line('all_sportbook_bet_draw')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					default: $result .= ""; break;
    				}break;
    			case 14: 
    				switch($mv_set){
    					case 0: $result .= $obj->lang->line('all_sportbook_bet_type_correct_score')."[".$obj->lang->line('all_sportbook_bet_type_main_more_visit')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					case 1: $result .= $obj->lang->line('all_sportbook_bet_type_correct_score')."[".$obj->lang->line('all_sportbook_bet_type_visit_more_main')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					case 2: $result .= $obj->lang->line('all_sportbook_bet_type_correct_score')."[".$obj->lang->line('all_sportbook_bet_type_visit_same_main')."]"."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    					default: $result .= ""; break;
    				}break;
    			case 15: $result .= $obj->lang->line('all_sportbook_bet_type_half_or_full_time')."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    			case 16: $result .= $obj->lang->line('all_sportbook_bet_type_goal_count')."<br>".$obj->lang->line('all_sportbook_handicap').":".(isset($result_info_array['chum_num']) ? $result_info_array['chum_num'] : "")."<br>".$obj->lang->line('all_sportbook_odds_rate').":".(isset($result_info_array['compensate']) ? $result_info_array['compensate'] : "")."<br>".$obj->lang->line('all_result_bet_id').":".(isset($result_info_array['sn']) ? $result_info_array['sn'] : "")."<br>"; break;
    			default: $result .= ""; break;
    		}
    	}
	}else{
	    //new sportbook
		$BetType = (isset($result_info_array['BetType']) ? $result_info_array['BetType'] : "");
		if($BetType == "1"){
		    if(isset($result_info_array['dataBet'][0]['WagerGrpName'])){
				$result .= $result_info_array['dataBet'][0]['WagerGrpName'] . '<br>';
			}
			if(isset($result_info_array['dataBet'][0]['WagerTypeName'])){
			    if(isset($result_info_array['dataBet'][0]['WagerPosName']) && !empty($result_info_array['dataBet'][0]['WagerPosName'])){
			        $result .= $result_info_array['dataBet'][0]['WagerTypeName'] . '['.$result_info_array['dataBet'][0]['WagerPosName'].']<br>';
			    }else{
    				$result .= $result_info_array['dataBet'][0]['WagerTypeName'] . '<br>';
			    }
			}
			if(isset($result_info_array['dataBet'][0]['CutLine']) && !empty($result_info_array['dataBet'][0]['CutLine'])){
			    $result .= $obj->lang->line('all_sportbook_handicap')." : ".$result_info_array['dataBet'][0]['CutLine'] . '<br>';
			}
			if(isset($result_info_array['dataBet'][0]['PayoutOddsStr']) && !empty($result_info_array['dataBet'][0]['PayoutOddsStr'])){
			    $result .= $obj->lang->line('all_sportbook_odds_rate')." : ".$result_info_array['dataBet'][0]['PayoutOddsStr'] . '<br>';
			}
			$result .= $obj->lang->line('all_result_bet_id').":".$TicketID."<br>";
		}else{
		    $odd_rate = 1;
		    foreach($result_info_array['dataBet'] as $result_row){
		        $odd_rate *= ($result_row['PayoutOddsStr'] + 1);
		        if(!empty($result)){
					$result .= "<br><hr><br>";
				}else{
					$result .= "<br>";
				}
                if(isset($result_row['WagerGrpName'])){
    				$result .= $result_row['WagerGrpName'] . '<br>';
    			}
    			if(isset($result_row['WagerTypeName'])){
    			    if(isset($result_row['WagerPosName']) && !empty($result_row['WagerPosName'])){
    			        $result .= $result_row['WagerTypeName'] . '['.$result_row['WagerPosName'].']<br>';
    			    }else{
        				$result .= $result_row['WagerTypeName'] . '<br>';
    			    }
    			}
    			if(isset($result_row['CutLine']) && !empty($result_row['CutLine'])){
    			    $result .= $obj->lang->line('all_sportbook_handicap')." : ".$result_row['CutLine'] . '<br>';
    			}
    			if(isset($result_row['PayoutOddsStr']) && !empty($result_row['PayoutOddsStr'])){
    			    $result .= $obj->lang->line('all_sportbook_odds_rate')." : ".$result_row['PayoutOddsStr'] . '<br>';
    			}
            }
            $result .= "<br><hr><br>";
            $result .= $obj->lang->line('all_sportbook_bet_type_parlay')."<br>";
			$result .= $obj->lang->line('all_sportbook_odds_rate').":".$odd_rate."<br>";
			$result .= $obj->lang->line('all_result_bet_id').":".$TicketID."<br>";
		}
	}
	return $result;
}
function spsb_game_result_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$result = "";
	$TicketID = (isset($result_info_array['TicketID']) ? $result_info_array['TicketID'] : "");
	if(empty($TicketID)){
    	$fashion = (isset($result_info_array['fashion']) ? $result_info_array['fashion'] : "");
    	if($fashion == 20){
    		if(isset($result_info_array['detail']) && !empty($result_info_array['detail']) && sizeof($result_info_array['detail'])>0){
    			foreach($result_info_array['detail'] as $result_info_array_row){
    				if(!empty($result)){
    					$result .= "<br><hr><br>";
    				}else{
    					$result .= "<br>";
    				}
    				$result .= $result_info_array_row['main_team']." : ".$result_info_array_row['score1']. '<br>';
    				$result .= $result_info_array_row['visit_team']." : ".$result_info_array_row['score2']. '<br>';
    			}
    		}
    	}else{
    		$result .= $result_info_array['main_team']." : ".$result_info_array['score1']. '<br>';
    		$result .= $result_info_array['visit_team']." : ".$result_info_array['score2']. '<br>';
    	}
	}else{
	    $BetType = (isset($result_info_array['BetType']) ? $result_info_array['BetType'] : "");
		if($BetType == "1"){
		    $result_row = $result_info_array['dataBet'][0];
		    if(isset($result_row['CatID']) && $result_row['CatID'] == "83"){
		        $result .= $result_row['AwayTeam']." : ".$result_row['HomeScore']. '<br>';
			}else{
			    $result .= $result_row['HomeTeam']." : ".$result_row['HomeScore']. '<br>';
		        $result .= $result_row['AwayTeam']." : ".$result_row['AwayScore']. '<br>';
			}
		}else{
            foreach($result_info_array['dataBet'] as $result_row){
                if(!empty($result)){
					$result .= "<br><hr><br>";
				}else{
					$result .= "<br>";
				}
				if(isset($result_row['CatID']) && $result_row['CatID'] == "83"){
    		        $result .= $result_row['AwayTeam']." : ".$result_row['HomeScore']. '<br>';
				}else{
				    $result .= $result_row['HomeTeam']." : ".$result_row['HomeScore']. '<br>';
    		        $result .= $result_row['AwayTeam']." : ".$result_row['AwayScore']. '<br>';
				}
            }
		}
	}
	return $result;
}
function splt_game_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$result = "";
	$game_id = (isset($result_info_array['Game']) ? $result_info_array['Game'] : "");
	$period = (isset($result_info_array['Name']) ? $result_info_array['Name'] : "");
	switch($game_id){
		case 11: $result = "六合<br>"; break;
		case 12: $result = "大樂<br>"; break;
		case 13: $result = "539<br>"; break;
		case 22: $result = "天天樂<br>"; break;
		default: $result = ""; break;
	}
	$result .= $obj->lang->line('all_bet_code_period').": ".$period;
	return $result;
}
function splt_bet_code_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$result = "";
	$bet_content = (isset($result_info_array['Data'][5][0]) ? $result_info_array['Data'][5][0] : "");
	$bet_odds = (isset($result_info_array['Data'][5][3]) ? $result_info_array['Data'][5][3] : "");
	$game_type = (isset($result_info_array['Data'][3]) ? $result_info_array['Data'][3] : "");
	$game_style = (isset($result_info_array['Data'][4]) ? $result_info_array['Data'][4] : "");
	$transaction_id = (isset($result_info_array['Data'][4]) ? $result_info_array['Data'][0] : "");
	switch($game_type){
		case 1: $result .= $obj->lang->line('all_game_bet_code_type').": "."一定位<br>";break;
		case 2: $result .= $obj->lang->line('all_game_bet_code_type').": "."二定位<br>";break;
		case 3: $result .= $obj->lang->line('all_game_bet_code_type').": "."三定位<br>";break;
		case 4: $result .= $obj->lang->line('all_game_bet_code_type').": "."四定位<br>";break;
		case 5: $result .= $obj->lang->line('all_game_bet_code_type').": "."一字現<br>";break;
		case 6: $result .= $obj->lang->line('all_game_bet_code_type').": "."二字現<br>";break;
		case 7: $result .= $obj->lang->line('all_game_bet_code_type').": "."三字現<br>";break;
		case 8: $result .= $obj->lang->line('all_game_bet_code_type').": "."四字現<br>";break;
		case 9: $result .= $obj->lang->line('all_game_bet_code_type').": "."合單雙<br>";break;
		case 10: $result .= $obj->lang->line('all_game_bet_code_type').": "."1-5球<br>";break;
		case 11: $result .= $obj->lang->line('all_game_bet_code_type').": "."雙面<br>";break;
		case 12: $result .= $obj->lang->line('all_game_bet_code_type').": "."豹子<br>";break;
		case 13: $result .= $obj->lang->line('all_game_bet_code_type').": "."順子<br>";break;
		case 14: $result .= $obj->lang->line('all_game_bet_code_type').": "."對子<br>";break;
		case 15: $result .= $obj->lang->line('all_game_bet_code_type').": "."半順<br>";break;
		case 16: $result .= $obj->lang->line('all_game_bet_code_type').": "."雜六<br>";break;
		case 17: $result .= $obj->lang->line('all_game_bet_code_type').": "."總合雙面<br>";break;
		case 18: $result .= $obj->lang->line('all_game_bet_code_type').": "."龍虎和<br>";break;
		case 19: $result .= $obj->lang->line('all_game_bet_code_type').": "."龍虎<br>";break;
		case 20: $result .= $obj->lang->line('all_game_bet_code_type').": "."第1-10名<br>";break;
		case 21: $result .= $obj->lang->line('all_game_bet_code_type').": "."冠亞和值<br>";break;
		case 101: $result .= $obj->lang->line('all_game_bet_code_type').": "."全車<br>";break;
		case 102: $result .= $obj->lang->line('all_game_bet_code_type').": "."特碼<br>";break;
		case 103: $result .= $obj->lang->line('all_game_bet_code_type').": "."台號<br>";break;
		case 104: $result .= $obj->lang->line('all_game_bet_code_type').": "."特尾三<br>";break;
		case 105: $result .= $obj->lang->line('all_game_bet_code_type').": "."二星<br>";break;
		case 106: $result .= $obj->lang->line('all_game_bet_code_type').": "."三星<br>";break;
		case 107: $result .= $obj->lang->line('all_game_bet_code_type').": "."四星<br>";break;
		case 108: $result .= $obj->lang->line('all_game_bet_code_type').": "."天碰二<br>";break;
		case 109: $result .= $obj->lang->line('all_game_bet_code_type').": "."天碰三<br>";break;
		case 110: $result .= $obj->lang->line('all_game_bet_code_type').": "."正碼特<br>";break;
		case 111: $result .= $obj->lang->line('all_game_bet_code_type').": "."三全中<br>";break;
		case 112: $result .= $obj->lang->line('all_game_bet_code_type').": "."三中二<br>";break;
		case 113: $result .= $obj->lang->line('all_game_bet_code_type').": "."二全中<br>";break;
		case 114: $result .= $obj->lang->line('all_game_bet_code_type').": "."二中特<br>";break;
		case 115: $result .= $obj->lang->line('all_game_bet_code_type').": "."特串<br>";break;
		case 116: $result .= $obj->lang->line('all_game_bet_code_type').": "."四全中<br>";break;
		case 117: $result .= $obj->lang->line('all_game_bet_code_type').": "."天碰四<br>";break;
		case 118: $result .= $obj->lang->line('all_game_bet_code_type').": "."五星<br>";break;
		case 130: $result .= $obj->lang->line('all_game_bet_code_type').": "."特單雙<br>";break;
		case 159: $result .= $obj->lang->line('all_game_bet_code_type').": "."十一不中<br>";break;
		case 160: $result .= $obj->lang->line('all_game_bet_code_type').": "."十二不中<br>";break;
	}
	switch($game_style){
		case 1: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."仟XXX<br>";break;
		case 2: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."X佰XX<br>";break;
		case 3: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."XX拾X<br>";break;
		case 4: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."XXX個<br>";break;
		case 5: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."仟佰XX<br>";break;
		case 6: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."仟X拾X<br>";break;
		case 7: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."仟XX個<br>";break;
		case 8: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."X佰拾X<br>";break;
		case 9: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."X佰X個<br>";break;
		case 10: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."1-XX拾個<br>";break;
		case 11: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."仟佰拾X<br>";break;
		case 12: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."仟佰X個<br>";break;
		case 13: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."仟X拾個<br>";break;
		case 14: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."X佰拾個<br>";break;
		case 16: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."仟佰XX<br>";break;
		case 17: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."仟X拾X<br>";break;
		case 18: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."仟XX個<br>";break;
		case 19: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."X佰拾X<br>";break;
		case 20: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."X佰X個<br>";break;
		case 21: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."XX拾個<br>";break;
		case 22: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."佰XX<br>";break;
		case 23: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."X拾X<br>";break;
		case 24: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."XX個<br>";break;
		case 25: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."佰拾X<br>";break;
		case 26: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."佰X個<br>";break;
		case 27: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."X拾個<br>";break;
		case 28: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."選1碼<br>";break;
		case 29: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."選2碼<br>";break;
		case 30: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."選3碼<br>";break;
		case 31: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."選4碼<br>";break;
		case 32: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."選5碼<br>";break;
		case 33: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."選6碼<br>";break;
		case 34: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."選7碼<br>";break;
		case 35: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."選8碼<br>";break;
		case 36: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."選9碼<br>";break;
		case 37: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."全包<br>";break;
		case 38: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."第一球<br>";break;
		case 39: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."第二球<br>";break;
		case 40: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."第三球<br>";break;
		case 41: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."第四球<br>";break;
		case 42: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."第五球<br>";break;
		case 43: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."第六球<br>";break;
		case 44: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."第七球<br>";break;
		case 45: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."第八球<br>";break;
		case 46: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."單雙<br>";break;
		case 47: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."大小<br>";break;
		case 48: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."龍虎<br>";break;
		case 49: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."和<br>";break;
		case 50: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."總<br>";break;
		case 51: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."單碰<br>";break;
		case 52: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."連碰<br>";break;
		case 53: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."柱碰<br>";break;
		case 54: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."一比四<br>";break;
		case 55: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."連柱碰<br>";break;
		case 56: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."全車<br>";break;
		case 57: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."雙星連碰柱<br>";break;
		case 58: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."三星連碰柱<br>";break;
		case 59: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."雙連碰<br>";break;
		case 60: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."魔電<br>";break;
		case 61: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."配比包牌<br>";break;
		case 62: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."雙面包牌<br>";break;
		case 63: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."套餐<br>";break;
		case 64: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."正碼一<br>";break;
		case 65: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."正碼二<br>";break;
		case 66: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."正碼三<br>";break;
		case 67: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."正碼四<br>";break;
		case 68: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."正碼五<br>";break;
		case 69: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."正碼六<br>";break;
		case 70: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."１肖中<br>";break;
		case 71: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."２肖中<br>";break;
		case 72: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."３肖中<br>";break;
		case 73: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."４肖中<br>";break;
		case 74: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."５肖中<br>";break;
		case 75: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."６肖中<br>";break;
		case 76: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."７肖中<br>";break;
		case 77: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."８肖中<br>";break;
		case 78: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."９肖中<br>";break;
		case 79: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."10肖中<br>";break;
		case 80: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."11肖中<br>";break;
		case 81: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."中<br>";break;
		case 82: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."不中<br>";break;
		case 83: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."膽拖<br>";break;
		case 84: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."尾數對碰<br>";break;
		case 85: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."混合對碰<br>";break;
		case 86: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."混合對碰<br>";break;
		case 87: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."１肖不中<br>";break;
		case 88: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."２肖不中<br>";break;
		case 89: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."３肖不中<br>";break;
		case 90: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."４肖不中<br>";break;
		case 91: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."５肖不中<br>";break;
		case 92: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."６肖不中<br>";break;
		case 93: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."７肖不中<br>";break;
		case 94: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."８肖不中<br>";break;
		case 95: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."９肖不中<br>";break;
		case 96: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."10肖不中<br>";break;
		case 97: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."11肖不中<br>";break;
		case 98: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."看漲<br>";break;
		case 99: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."看跌<br>";break;
		case 100: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."向上觸及<br>";break;
		case 101: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."向下觸及<br>";break;
		case 102: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."向上不觸及<br>";break;
		case 103: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."向下不觸及<br>";break;
		case 104: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."範圍內<br>";break;
		case 105: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."範圍外<br>";break;
		case 106: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."前三<br>";break;
		case 107: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."中三<br>";break;
		case 108: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."後三<br>";break;
		case 109: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."XXXOO(4，5位)<br>";break;
		case 110: $result .= $obj->lang->line('all_game_bet_code_play_style').": "."OXXXO(1，5位)<br>";break;
	}
	$result .= $obj->lang->line('all_bet_content').": ".$bet_content."<br>";
	$result .= $obj->lang->line('all_sportbook_odds_rate').": ".$bet_odds."<br>";
	$result .= $obj->lang->line('all_result_bet_id')." : ".$transaction_id."<br/>";
	return $result; 
}
function splt_game_result_decision($game_type_code = NULL, $result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$result = "";
	$game_result = (isset($result_info_array['Lottery']) ? $result_info_array['Lottery'] : "");
	if(!empty($game_result)){
		$result .= $game_result;
	}
	return $result;
}
function wm_game_code_decision($game_type_code = NULL,$result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$result = "";
	$game_real_code = (isset($result_info_array['gid']) ? $result_info_array['gid'] : "");
	switch($game_real_code)
	{
		case 101: $result = $obj->lang->line('all_game_code_baccarat'); break;
		case 102: $result = $obj->lang->line('all_game_code_dragon_tiger'); break;
		case 103: $result = $obj->lang->line('all_game_code_roulette'); break;
		case 104: $result = $obj->lang->line('all_game_code_sicbo'); break;
		case 105: $result = $obj->lang->line('all_game_code_bull_bull'); break;
		case 106: $result = $obj->lang->line('all_game_code_sam_gong'); break;
		case 107: $result = $obj->lang->line('all_game_code_fan_tan'); break;
		case 108: $result = $obj->lang->line('all_game_code_se_die'); break;
		case 110: $result = $obj->lang->line('all_game_code_fish_prawn_crab'); break;
		case 128: $result = $obj->lang->line('all_game_code_andar_bahar'); break;
		default: $result = "-"; break;
	}
	return $result;
}
function wm_bet_code_decision($game_type_code = NULL,$result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_real_code = (isset($result_info_array['gid']) ? $result_info_array['gid'] : "");
	$bet_code = (isset($result_info_array['betCode']) ? $result_info_array['betCode'] : "");
	$result = "";
	if($game_real_code == 101){
		switch($bet_code)
		{
			case "Banker": $result = $obj->lang->line('all_bet_code_banker'); break;
			case "Player": $result = $obj->lang->line('all_bet_code_player'); break;
			case "Tie": $result = $obj->lang->line('all_bet_code_tie'); break;
			case "BPair": $result = $obj->lang->line('all_bet_code_banker_pair'); break;
			case "PPair": $result = $obj->lang->line('all_bet_code_player_pair'); break;
			default: $result = ""; break;
		}
	}else if($game_real_code == 102){
		switch($bet_code)
		{
			case "Dragon": $result = $obj->lang->line('all_bet_code_dragon'); break;
			case "Tiger": $result = $obj->lang->line('all_bet_code_tiger'); break;
			case "Tie": $result = $obj->lang->line('all_bet_code_tie'); break;
			default: $result = ""; break;
		}
	}else if($game_real_code == 103){
		if(strpos($bet_code, 'Num') !== false){
			//number
			$number = str_replace("Num","",$bet_code);
			if(strpos($number, '_') !== false){
				$result = $obj->lang->line('all_bet_code_multiple_number')." : ".str_replace("_",",",$number);
			}else{
				$result = $obj->lang->line('all_bet_code_straight_number')." : ".$number;
			}
		}else if(strpos($bet_code, 'Column') !== false){
			$number = str_replace("Column","",$bet_code);
			$result = $obj->lang->line('all_bet_code_column')." : ".$number;
		}else if(strpos($bet_code, 'Dozen') !== false){
			$number = str_replace("Dozen","",$bet_code);
			$result = $obj->lang->line('all_bet_code_dozen')." : ".str_replace("_"," ".$obj->lang->line('all_bet_code_until')." ",$number);
		}else{
			switch($bet_code)
			{
				case "Red": $result = $obj->lang->line('all_bet_code_red'); break;
				case "Black": $result = $obj->lang->line('all_bet_code_black'); break;
				case "Odd": $result = $obj->lang->line('all_bet_code_odd'); break;
				case "Even": $result = $obj->lang->line('all_bet_code_even'); break;
				case "Big": $result = $obj->lang->line('all_bet_code_big'); break;
				case "Small": $result = $obj->lang->line('all_bet_code_small'); break;
				default: $result = ""; break;
			}
		}
	}else if($game_real_code == 104){
		if(strpos($bet_code, 'Sum') !== false){
			//number
			$number = str_replace("Sum","",$bet_code);
			$result = $obj->lang->line('all_bet_code_sum_number')." : ".$number;
		}else if(strpos($bet_code, 'OneDice') !== false){
			//number
			$number = str_replace("OneDice","",$bet_code);
			$result = $obj->lang->line('all_bet_code_one_dice')." : ".$number;
		}else if(strpos($bet_code, 'TwoDice') !== false){
			//number
			$number = str_replace("TwoDice","",$bet_code);
			$result = $obj->lang->line('all_bet_code_two_dice')." : ".implode(',',str_split($number));
		}else if(strpos($bet_code, 'DoubleDice') !== false){
			//number
			$number = str_replace("DoubleDice","",$bet_code);
			$result = $obj->lang->line('all_bet_code_double_dice')." : ".$number;
		}else if(strpos($bet_code, 'AllLeopard') !== false){
			//number
			$result = $obj->lang->line('all_bet_code_any_triples');
		}else if(strpos($bet_code, 'Leopard') !== false){
			//number
			$number = str_replace("Leopard","",$bet_code);
			$result = $obj->lang->line('all_bet_code_specific_triples')." : ".$number;
		}else if(strpos($bet_code, 'Dice') !== false){
			//number
			$number = str_replace("Dice","",$bet_code);
			$result = $obj->lang->line('all_bet_code_dice')." : ".implode(',',str_split($number));
		}else{
			switch($bet_code)
			{
				case "Odd": $result = $obj->lang->line('all_bet_code_odd'); break;
				case "Even": $result = $obj->lang->line('all_bet_code_even'); break;
				case "Big": $result = $obj->lang->line('all_bet_code_big'); break;
				case "Small": $result = $obj->lang->line('all_bet_code_small'); break;
				default: $result = ""; break;
			}
		}
	}else if($game_real_code == 105){
		switch($bet_code)
		{
			case "Player1Equal": $result = $obj->lang->line('all_bet_code_player_one_equal'); break;
			case "Player1Double": $result = $obj->lang->line('all_bet_code_player_one_double'); break;
			case "Player2Equal": $result = $obj->lang->line('all_bet_code_player_two_equal'); break;
			case "Player2Double": $result = $obj->lang->line('all_bet_code_player_two_double'); break;
			case "Player3Equal": $result = $obj->lang->line('all_bet_code_player_three_equal'); break;
			case "Player3Double": $result = $obj->lang->line('all_bet_code_player_three_double'); break;
			default: $result = ""; break;
		}
	}else if($game_real_code == 106){
		switch($bet_code)
		{
			case "BankerPairPlus": $result = $obj->lang->line('all_bet_code_banker_pair_plus'); break;
			case "Player1Win": $result = $obj->lang->line('all_bet_code_player_one_win'); break;
			case "Player1Lose": $result = $obj->lang->line('all_bet_code_player_one_lose'); break;
			case "Player1Tie": $result = $obj->lang->line('all_bet_code_player_one_tie'); break;
			case "Player1ThreeFace": $result = $obj->lang->line('all_bet_code_player_one_three_face'); break;
			case "Player1PairPlus": $result = $obj->lang->line('all_bet_code_player_one_pair_plus'); break;
			case "Player2Win": $result = $obj->lang->line('all_bet_code_player_two_win'); break;
			case "Player2Lose": $result = $obj->lang->line('all_bet_code_player_two_lose'); break;
			case "Player2Tie": $result = $obj->lang->line('all_bet_code_player_two_tie'); break;
			case "Player2ThreeFace": $result = $obj->lang->line('all_bet_code_player_two_three_face'); break;
			case "Player2PairPlus": $result = $obj->lang->line('all_bet_code_player_two_pair_plus'); break;
			case "Player3Win": $result = $obj->lang->line('all_bet_code_player_three_win'); break;
			case "Player3Lose": $result = $obj->lang->line('all_bet_code_player_three_lose'); break;
			case "Player3Tie": $result = $obj->lang->line('all_bet_code_player_three_tie'); break;
			case "Player3ThreeFace": $result = $obj->lang->line('all_bet_code_player_three_three_face'); break;
			case "Player3PairPlus": $result = $obj->lang->line('all_bet_code_player_three_pair_plus'); break;
			default: $result = ""; break;
		}
	}else if($game_real_code == 107){
		if(strpos($bet_code, 'Nim') !== false){
			//number
			$number = str_replace("Nim","",$bet_code);
			$number_array = str_split($number);
			$result = $number_array[0] ." ". $obj->lang->line('all_bet_code_nim_number') . " " . $number_array[1];
		}else if(strpos($bet_code, 'Kwok') !== false){
			//number
			$number = str_replace("Kwok","",$bet_code);
			$result = $number.$obj->lang->line('all_bet_code_kwok_number');
		}else if(strpos($bet_code, 'Nga') !== false){
			//number
			$number = str_replace("Nga","",$bet_code);
			$number_array = str_split($number);
			switch($number_array[2])
			{
				case 2: $temporary_number = $obj->lang->line('all_bet_code_two'); break;
				case 3: $temporary_number = $obj->lang->line('all_bet_code_three'); break;
				case 4: $temporary_number = $obj->lang->line('all_bet_code_four'); break;
				default: $temporary_number = $obj->lang->line('all_bet_code_one'); break;
			}
			$result = $number_array[0].$number_array[1]." ".$temporary_number.$obj->lang->line('all_bet_code_nga_number');
		}else if(strpos($bet_code, 'Ssh') !== false){
			//number
			$number = str_replace("Ssh","",$bet_code);
			$result = $obj->lang->line('all_bet_code_ssh_number').$number;
		}else{
			switch($bet_code)
			{
				case "Odd": $result = $obj->lang->line('all_bet_code_odd'); break;
				case "Even": $result = $obj->lang->line('all_bet_code_even'); break;
				case "Big": $result = $obj->lang->line('all_bet_code_big'); break;
				case "Small": $result = $obj->lang->line('all_bet_code_small'); break;
				case "Fan1": $result = $obj->lang->line('all_bet_code_fan_one'); break;
				case "Fan2": $result = $obj->lang->line('all_bet_code_fan_two'); break;
				case "Fan3": $result = $obj->lang->line('all_bet_code_fan_three'); break;
				case "Fan4": $result = $obj->lang->line('all_bet_code_fan_four'); break;
				default: $result = ""; break;
			}
		}
	}else if($game_real_code == 108){
		switch($bet_code)
		{
			case "Odd": $result = $obj->lang->line('all_bet_code_odd'); break;
			case "Even": $result = $obj->lang->line('all_bet_code_even'); break;
			case "Big": $result = $obj->lang->line('all_bet_code_big'); break;
			case "Small": $result = $obj->lang->line('all_bet_code_small'); break;
			case "R4": $result = $obj->lang->line('all_bet_code_four_red'); break;
			case "W4": $result = $obj->lang->line('all_bet_code_four_white'); break;
			case "R3W1": $result = $obj->lang->line('all_bet_code_three_red_one_white'); break;
			case "W3R1": $result = $obj->lang->line('all_bet_code_three_white_one_red'); break;
			default: $result = ""; break;
		}
	}else if($game_real_code == 110){
		if(strpos($bet_code, 'Sum') !== false){
			//number
			$number = str_replace("Sum","",$bet_code);
			$result = $obj->lang->line('all_bet_code_sum_number')." : ".$number;
		}else{
			switch($bet_code)
			{
				case "Odd": $result = $obj->lang->line('all_bet_code_odd'); break;
				case "Even": $result = $obj->lang->line('all_bet_code_even'); break;
				case "Big": $result = $obj->lang->line('all_bet_code_big'); break;
				case "Small": $result = $obj->lang->line('all_bet_code_small'); break;
				case "Triples1": $result = $obj->lang->line('all_bet_code_specific_triples_fish'); break;
				case "Triples2": $result = $obj->lang->line('all_bet_code_specific_triples_prawn'); break;
				case "Triples3": $result = $obj->lang->line('all_bet_code_specific_triples_calabash'); break;
				case "Triples4": $result = $obj->lang->line('all_bet_code_specific_triples_coin'); break;
				case "Triples5": $result = $obj->lang->line('all_bet_code_specific_triples_crab'); break;
				case "Triples6": $result = $obj->lang->line('all_bet_code_specific_triples_chicken'); break;
				case "Anytriples": $result = $obj->lang->line('all_bet_code_any_specific_triples'); break;
				case "Dice1": $result = $obj->lang->line('all_bet_code_one_of_a_kind_fish'); break;
				case "Dice2": $result = $obj->lang->line('all_bet_code_one_of_a_kind_prawn'); break;
				case "Dice3": $result = $obj->lang->line('all_bet_code_one_of_a_kind_calabash'); break;
				case "Dice4": $result = $obj->lang->line('all_bet_code_one_of_a_kind_coin'); break;
				case "Dice5": $result = $obj->lang->line('all_bet_code_one_of_a_kind_crab'); break;
				case "Dice6": $result = $obj->lang->line('all_bet_code_one_of_a_kind_chicken'); break;
				default: $result = ""; break;
			}
		}
	}else if($game_real_code == 128){
		switch($bet_code)
		{
			case "Andar": $result = $obj->lang->line('all_bet_code_andar'); break;
			case "Bahar": $result = $obj->lang->line('all_bet_code_bahar'); break;
			default: $result = ""; break;
		}
	}else{
		$result = "";
	}
	if(!empty($result)){
		$result .= "<br>";
	}
	if(isset($result_info_array['tableId'])){
		$result .= $obj->lang->line('all_result_table_id') ." : ". $result_info_array['tableId']."<br>";
	}
	if(isset($result_info_array['round'])){
		$result .= $obj->lang->line('all_result_round') ." : ". $result_info_array['round']."<br>";
	}else if(isset($result_info_array['event'])){
		$result .= $obj->lang->line('all_result_event') ." : ". $result_info_array['event']."<br>";
	}
	if(isset($result_info_array['betId'])){
		$result .= $obj->lang->line('all_result_bet_id')." : ".$result_info_array['betId']."<br/>";
	}
	return $result;
}
function wm_game_result_decision($game_type_code = NULL,$result_info = NULL){
	$obj =& get_instance();
	$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	$game_real_code = (isset($result_info_array['gid']) ? $result_info_array['gid'] : "");
	$game_result = (isset($result_info_array['gameResult']) ? $result_info_array['gameResult'] : "");
	$result = "";
	if($game_real_code == 101){
		$game_result_data = explode(' ', $game_result);
		if(sizeof($game_result_data)>0){
			foreach($game_result_data as $game_result_data_row){
				if(!empty($result)){
					$result .= "<br/>";
				}
				if(strpos($game_result_data_row, 'Banker:') !== false || strpos($game_result_data_row, '庄:') !== false){
					if(strpos($game_result_data_row, 'Banker:') !== false){
						$number_result = str_replace("Banker:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_banker')." : ";
					}else if(strpos($game_result_data_row, '庄:') !== false){
						$number_result = str_replace("庄:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_banker')." : ";
					}else{
						$number_result = "";	
					}
				}else if(strpos($game_result_data_row, 'Player:') !== false || strpos($game_result_data_row, '闲:') !== false){
					if(strpos($game_result_data_row, 'Player:') !== false){
						$number_result = str_replace("Player:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_player')." : ";
					}else if(strpos($game_result_data_row, '闲:') !== false){
						$number_result = str_replace("闲:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_player')." : ";
					}else{
						$number_result = "";	
					}
				}else{
					$number_result = "";
				}
				if(!empty($number_result)){
					$number_result_text = json_encode($number_result,true);
					$number_result_array = array_values(array_filter(explode('\u', str_replace('"','',$number_result_text))));
					if(sizeof($number_result_array)>0){
						foreach($number_result_array as $number_result_row){
							if(strpos($number_result_row, '2666') !== false){
								$suit = "spades";
								$rank = strtoupper(str_replace("2666","",$number_result_row));
							}else if(strpos($number_result_row, '2665') !== false){
								$suit = "hearts";
								$rank = strtoupper(str_replace("2665","",$number_result_row));
							}else if(strpos($number_result_row, '2663') !== false){
								$suit = "clubs";
								$rank = strtoupper(str_replace("2663","",$number_result_row));
							}else if(strpos($number_result_row, '2660') !== false){
								$suit = "diams";
								$rank = strtoupper(str_replace("2660","",$number_result_row));
							}else{
								$suit = "";
							}
							if(!empty($suit)){
								$result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
									$result .= '<div class="card rank-'.$rank.' '.$suit.'">';
										$result .= '<span class="rank">'.$rank.'</span>';
										$result .= '<span class="suit">&'.$suit.';</span>';
									$result .= '</div>';
								$result .= '</div>';
							}
						}
					}
				}
			}
		}
	}else if($game_real_code == 102){
		$game_result_data = explode(' ', $game_result);
		if(sizeof($game_result_data)>0){
			foreach($game_result_data as $game_result_data_row){
				if(!empty($result)){
					$result .= "<br/>";
				}
				if(strpos($game_result_data_row, 'Dragon:') !== false || strpos($game_result_data_row, '龙:') !== false){
					if(strpos($game_result_data_row, 'Dragon:') !== false){
						$number_result = str_replace("Dragon:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_dragon')." : ";
					}else if(strpos($game_result_data_row, '龙:') !== false){
						$number_result = str_replace("龙:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_dragon')." : ";
					}else{
						$number_result = "";
					}				
				}else if(strpos($game_result_data_row, 'Tiger:') !== false || strpos($game_result_data_row, '虎:') !== false){
					if(strpos($game_result_data_row, 'Tiger:') !== false){
						$number_result = str_replace("Tiger:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_tiger')." : ";
					}else if(strpos($game_result_data_row, '虎:') !== false){
						$number_result = str_replace("虎:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_tiger')." : ";
					}else{
						$number_result = "";
					}				
				}else{
					$number_result = "";
				}
				if(!empty($number_result)){
					$number_result_text = json_encode($number_result,true);
					$number_result_array = array_values(array_filter(explode('\u', str_replace('"','',$number_result_text))));
					if(sizeof($number_result_array)>0){
						foreach($number_result_array as $number_result_row){
							if(strpos($number_result_row, '2666') !== false){
								$suit = "spades";
								$rank = strtoupper(str_replace("2666","",$number_result_row));
							}else if(strpos($number_result_row, '2665') !== false){
								$suit = "hearts";
								$rank = strtoupper(str_replace("2665","",$number_result_row));
							}else if(strpos($number_result_row, '2663') !== false){
								$suit = "clubs";
								$rank = strtoupper(str_replace("2663","",$number_result_row));
							}else if(strpos($number_result_row, '2660') !== false){
								$suit = "diams";
								$rank = strtoupper(str_replace("2660","",$number_result_row));
							}else{
								$suit = "";
							}
							if(!empty($suit)){
								$result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
									$result .= '<div class="card rank-'.$rank.' '.$suit.'">';
										$result .= '<span class="rank">'.$rank.'</span>';
										$result .= '<span class="suit">&'.$suit.';</span>';
									$result .= '</div>';
								$result .= '</div>';
							}
						}
					}
				}
			}
		}
	}else if($game_real_code == 103){
		$result = $game_result;
	}else if($game_real_code == 104){
		$game_result_data = explode(',', $game_result);
		if(sizeof($game_result_data)>0){
			foreach($game_result_data as $game_result_data_row){
				$result .= '<span class="dice dice-'.$game_result_data_row.'" title="'.$obj->lang->line('all_bet_code_dice').' : '.$game_result_data_row.'"></span>';
			}
		}
	}else if($game_real_code == 105){
		$game_result_data = explode(' ', $game_result);
		if(sizeof($game_result_data)>0){
			foreach($game_result_data as $game_result_data_row){
				if(!empty($result)){
					$result .= "<br/>";
				}
				if(strpos($game_result_data_row, 'Banker:') !== false || strpos($game_result_data_row, '庄:') !== false){
					if(strpos($game_result_data_row, 'Banker:') !== false){
						$number_result = str_replace("Banker:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_banker')." : ";
					}else if(strpos($game_result_data_row, '庄:') !== false){
						$number_result = str_replace("庄:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_banker')." : ";
					}else{
						$number_result = "";	
					}
				}else if(strpos($game_result_data_row, 'Player1:') !== false || strpos($game_result_data_row, '闲一:') !== false){
					if(strpos($game_result_data_row, 'Player1:') !== false){
						$number_result = str_replace("Player1:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_player_one')." : ";
					}else if(strpos($game_result_data_row, '闲一:') !== false){
						$number_result = str_replace("闲一:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_player_one')." : ";
					}else{
						$number_result = "";
					}
				}else if(strpos($game_result_data_row, 'Player2:') !== false || strpos($game_result_data_row, '闲二:') !== false){
					if(strpos($game_result_data_row, 'Player2:') !== false){
						$number_result = str_replace("Player2:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_player_two')." : ";
					}else if(strpos($game_result_data_row, '闲二:') !== false){
						$number_result = str_replace("闲二:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_player_two')." : ";
					}else{
						$number_result = "";	
					}
				}else if(strpos($game_result_data_row, 'Player3:') !== false || strpos($game_result_data_row, '闲三:') !== false){
					if(strpos($game_result_data_row, 'Player3:') !== false){
						$number_result = str_replace("Player3:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_player_three')." : ";
					}else if(strpos($game_result_data_row, '闲三:') !== false){
						$number_result = str_replace("闲三:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_player_three')." : ";
					}
				}else{
					$number_result = "";
				}
				if(!empty($number_result)){
					$number_result_text = json_encode($number_result,true);
					$number_result_array = array_values(array_filter(explode('\u', str_replace('"','',str_replace(',','',$number_result_text)))));
					if(sizeof($number_result_array)>0){
						foreach($number_result_array as $number_result_row){
							if(strpos($number_result_row, '2666') !== false){
								$suit = "spades";
								$rank = strtoupper(str_replace("2666","",$number_result_row));
							}else if(strpos($number_result_row, '2665') !== false){
								$suit = "hearts";
								$rank = strtoupper(str_replace("2665","",$number_result_row));
							}else if(strpos($number_result_row, '2663') !== false){
								$suit = "clubs";
								$rank = strtoupper(str_replace("2663","",$number_result_row));
							}else if(strpos($number_result_row, '2660') !== false){
								$suit = "diams";
								$rank = strtoupper(str_replace("2660","",$number_result_row));
							}else{
								$suit = "";
							}
							if(!empty($suit)){
								$result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
									$result .= '<div class="card rank-'.$rank.' '.$suit.'">';
										$result .= '<span class="rank">'.$rank.'</span>';
										$result .= '<span class="suit">&'.$suit.';</span>';
									$result .= '</div>';
								$result .= '</div>';
							}
						}
					}
				}
			}
		}
	}else if($game_real_code == 106){
		$game_result_data = explode(' ', $game_result);
		if(sizeof($game_result_data)>0){
			foreach($game_result_data as $game_result_data_row){
				if(!empty($result)){
					$result .= "<br/>";
				}
				if(strpos($game_result_data_row, 'Banker:') !== false || strpos($game_result_data_row, '庄:') !== false){
					if(strpos($game_result_data_row, 'Banker:') !== false){
						$number_result = str_replace("Banker:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_banker')." : ";
					}else if(strpos($game_result_data_row, '庄:') !== false){
						$number_result = str_replace("庄:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_banker')." : ";
					}else{
						$number_result = "";	
					}
				}else if(strpos($game_result_data_row, 'Player1:') !== false || strpos($game_result_data_row, '闲一:') !== false){
					if(strpos($game_result_data_row, 'Player1:') !== false){
						$number_result = str_replace("Player1:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_player_one')." : ";
					}else if(strpos($game_result_data_row, '闲一:') !== false){
						$number_result = str_replace("闲一:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_player_one')." : ";
					}else{
						$number_result = "";
					}
				}else if(strpos($game_result_data_row, 'Player2:') !== false || strpos($game_result_data_row, '闲二:') !== false){
					if(strpos($game_result_data_row, 'Player2:') !== false){
						$number_result = str_replace("Player2:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_player_two')." : ";
					}else if(strpos($game_result_data_row, '闲二:') !== false){
						$number_result = str_replace("闲二:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_player_two')." : ";
					}else{
						$number_result = "";	
					}
				}else if(strpos($game_result_data_row, 'Player3:') !== false || strpos($game_result_data_row, '闲三:') !== false){
					if(strpos($game_result_data_row, 'Player3:') !== false){
						$number_result = str_replace("Player3:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_player_three')." : ";
					}else if(strpos($game_result_data_row, '闲三:') !== false){
						$number_result = str_replace("闲三:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_player_three')." : ";
					}
				}else{
					$number_result = "";
				}
				if(!empty($number_result)){
					$number_result_text = json_encode($number_result,true);
					$number_result_array = array_values(array_filter(explode('\u', str_replace('"','',$number_result_text))));
					if(sizeof($number_result_array)>0){
						foreach($number_result_array as $number_result_row){
							if(strpos($number_result_row, '2666') !== false){
								$suit = "spades";
								$rank = strtoupper(str_replace("2666","",$number_result_row));
							}else if(strpos($number_result_row, '2665') !== false){
								$suit = "hearts";
								$rank = strtoupper(str_replace("2665","",$number_result_row));
							}else if(strpos($number_result_row, '2663') !== false){
								$suit = "clubs";
								$rank = strtoupper(str_replace("2663","",$number_result_row));
							}else if(strpos($number_result_row, '2660') !== false){
								$suit = "diams";
								$rank = strtoupper(str_replace("2660","",$number_result_row));
							}else{
								$suit = "";
							}
							if(!empty($suit)){
								$result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
									$result .= '<div class="card rank-'.$rank.' '.$suit.'">';
										$result .= '<span class="rank">'.$rank.'</span>';
										$result .= '<span class="suit">&'.$suit.';</span>';
									$result .= '</div>';
								$result .= '</div>';
							}
						}
					}
				}
			}
		}
	}else if($game_real_code == 107){
		$result = $game_result;
	}else if($game_real_code == 108){
		switch($game_result)
		{
			case "4Red": $result = $obj->lang->line('all_bet_code_four_red'); break;
			case "四红": $result = $obj->lang->line('all_bet_code_four_red'); break;
			case "4White": $result = $obj->lang->line('all_bet_code_four_white'); break;
			case "四白": $result = $obj->lang->line('all_bet_code_four_white'); break;
			case "3White1Red": $result = $obj->lang->line('all_bet_code_three_red_one_white'); break;
			case "三白一红": $result = $obj->lang->line('all_bet_code_three_red_one_white'); break;
			case "3Red1White": $result = $obj->lang->line('all_bet_code_three_white_one_red'); break;
			case "三红一白": $result = $obj->lang->line('all_bet_code_three_white_one_red'); break;
			case "2White2Red": $result = $obj->lang->line('all_bet_code_two_white_two_red'); break;
			case "二白二红": $result = $obj->lang->line('all_bet_code_two_white_two_red'); break;
			case "2Red2White": $result = $obj->lang->line('all_bet_code_two_red_two_white'); break;
			case "二红二白": $result = $obj->lang->line('all_bet_code_two_red_two_white'); break;
			default: $result = "-"; break;
		}
	}else if($game_real_code == 110){
		$game_result_data = explode(',', $game_result);
		if(sizeof($game_result_data)>0){
			foreach($game_result_data as $game_result_data_row){
				switch($game_result_data_row)
				{
					case 1: $dice_type = "fish"; $dice_name = $obj->lang->line('all_bet_code_fish'); break;
					case 2: $dice_type = "prawn"; $dice_name = $obj->lang->line('all_bet_code_prawn'); break;
					case 3: $dice_type = "calabash"; $dice_name = $obj->lang->line('all_bet_code_calabash'); break;
					case 4: $dice_type = "money"; $dice_name = $obj->lang->line('all_bet_code_coin'); break;
					case 5: $dice_type = "crab"; $dice_name = $obj->lang->line('all_bet_code_crab'); break;
					case 6: $dice_type = "chicken"; $dice_name = $obj->lang->line('all_bet_code_chicken'); break;
					default: $dice_type = ""; $dice_name = ""; break;
				}
				if(!empty($dice_type)){
					$result .= '<span class="dice dice-'.$dice_type.'" title="'.$obj->lang->line('all_bet_code_dice').' : '.$dice_name.'"></span>';
				}
			}
		}
	}else if($game_real_code == 128){
		$game_result_data = explode(' ', $game_result);
		if(sizeof($game_result_data)>0){
			foreach($game_result_data as $game_result_data_row){
				if(!empty($result)){
					$result .= "<br/>";
				}
				if(strpos($game_result_data_row, 'Joker:') !== false || strpos($game_result_data_row, '小丑:') !== false){
					if(strpos($game_result_data_row, 'Joker:') !== false){
						$number_result = str_replace("Joker:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_joker')." : ";
					}else if(strpos($game_result_data_row, '小丑:') !== false){
						$number_result = str_replace("小丑:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_joker')." : ";
					}else{
						$number_result = "";	
					}
				}else if(strpos($game_result_data_row, 'Andar:') !== false || strpos($game_result_data_row, '安达:') !== false){
					if(strpos($game_result_data_row, 'Andar:') !== false){
						$number_result = str_replace("Andar:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_andar')." : ";
					}else if(strpos($game_result_data_row, '安达:') !== false){
						$number_result = str_replace("安达:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_andar')." : ";
					}else{
						$number_result = "";	
					}
				}else if(strpos($game_result_data_row, 'Bahar:') !== false || strpos($game_result_data_row, '巴哈:') !== false){
					if(strpos($game_result_data_row, 'Bahar:') !== false){
						$number_result = str_replace("Bahar:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_bahar')." : ";
					}else if(strpos($game_result_data_row, '巴哈:') !== false){
						$number_result = str_replace("巴哈:","",$game_result_data_row);
						$result .= $obj->lang->line('all_bet_code_bahar')." : ";
					}else{
						$number_result = "";	
					}
				}else{
					$number_result = "";
				}
				if(!empty($number_result)){
					$number_result_text = json_encode($number_result,true);
					$number_result_array = array_values(array_filter(explode('\u', str_replace('"','',$number_result_text))));
					if(sizeof($number_result_array)>0){
						foreach($number_result_array as $number_result_row){
							if(strpos($number_result_row, '2666') !== false){
								$suit = "spades";
								$rank = strtoupper(str_replace("2666","",$number_result_row));
							}else if(strpos($number_result_row, '2665') !== false){
								$suit = "hearts";
								$rank = strtoupper(str_replace("2665","",$number_result_row));
							}else if(strpos($number_result_row, '2663') !== false){
								$suit = "clubs";
								$rank = strtoupper(str_replace("2663","",$number_result_row));
							}else if(strpos($number_result_row, '2660') !== false){
								$suit = "diams";
								$rank = strtoupper(str_replace("2660","",$number_result_row));
							}else{
								$suit = "";
							}
							if(!empty($suit)){
								$result .= '<div class="playingCards fourColours inText" style="display:inline-block;">';
									$result .= '<div class="card rank-'.$rank.' '.$suit.'">';
										$result .= '<span class="rank">'.$rank.'</span>';
										$result .= '<span class="suit">&'.$suit.';</span>';
									$result .= '</div>';
								$result .= '</div>';
							}
						}
					}
				}
			}
		}
	}else{
		$result = "-";
	}
	return $result;
}

function sub_game_code_decision($provider = NULL, $game_type_code = NULL, $gcode = NULL){
	$obj =& get_instance();
	#$result_info_array = json_decode($result_info,true, 512, JSON_BIGINT_AS_STRING);
	#$game_real_code = (isset($result_info_array['gameType']) ? $result_info_array['gameType'] : "");
	
	$list = $obj->game_model->subgame($provider);
	$game_name = (array_key_exists($gcode,$list)) ? $list[$gcode] : $gcode;
	/*
	switch($game_real_code)
	{
		case 101: $result_code = $obj->lang->line('all_game_code_normal_baccarat'); break;
		case 102: $result_code = $obj->lang->line('all_game_code_vip_baccarat'); break;
		case 103: $result_code = $obj->lang->line('all_game_code_quick_baccarat'); break;
		case 104: $result_code = $obj->lang->line('all_game_code_show_baccarat'); break;
		case 110: $result_code = $obj->lang->line('all_game_code_insurance_baccarat'); break;
		case 201: $result_code = $obj->lang->line('all_game_code_sicbo_hilo'); break;
		case 301: $result_code = $obj->lang->line('all_game_code_dragon_tiger'); break;
		case 401: $result_code = $obj->lang->line('all_game_code_roulette'); break;
		case 501: $result_code = $obj->lang->line('all_game_code_pokdeng'); break;
		case 601: $result_code = $obj->lang->line('all_game_code_rock_paper_scissors'); break;
		case 801: $result_code = $obj->lang->line('all_game_code_bull_bull'); break;
		case 901: $result_code = $obj->lang->line('all_game_code_three_card')." / ".$obj->lang->line('all_game_code_sam_gong'); break;
		default: $result_code = "-"; break;
	}
	return $result_code;
	*/
	return $game_name;
}