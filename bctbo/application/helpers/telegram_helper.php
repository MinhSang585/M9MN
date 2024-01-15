<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function send_amount_telegram($account_id = NULL,$username = NULL, $admin = NULL, $amount = NULL, $type = NULL){
	$obj =& get_instance();
	$message = "";
	if($type == TRANSFER_ADJUST_IN){
		$message = $username." ".$obj->lang->line('lang_telegram_adjust_in')." ".bcdiv($amount,1,0)." (".$admin.")";
	}else if($type == TRANSFER_ADJUST_OUT){
		$message = $username." ".$obj->lang->line('lang_telegram_adjust_out')." ".bcdiv($amount,1,0)." (".$admin.")";
	}else if($type == TRANSFER_WITHDRAWAL){
		$message = $username." ".$obj->lang->line('lang_telegram_adjust_withdrawal')." ".bcdiv($amount,1,0)." (".$admin.")";
	}else if($type == TRANSFER_OFFLINE_DEPOSIT){
		$message = $username." ".$obj->lang->line('lang_telegram_adjust_deposit')." ".bcdiv($amount,1,0)." (".$admin.")";
	}else if($type == TRANSFER_PG_DEPOSIT){
		$message = $username." ".$obj->lang->line('lang_telegram_adjust_deposit')." ".bcdiv($amount,1,0)." (".$admin.")";
	}else if($type == TRANSFER_CREDIT_CARD_DEPOSIT){
		$message = $username." ".$obj->lang->line('lang_telegram_adjust_deposit')." ".bcdiv($amount,1,0)." (".$admin.")";
	}else if($type == TRANSFER_HYPERMART_DEPOSIT){
		$message = $username." ".$obj->lang->line('lang_telegram_adjust_deposit')." ".bcdiv($amount,1,0)." (".$admin.")";
	}
	send_message_telegram($account_id,$message);
}

function send_register_telegram($account_id = NULL,$arr = NULL, $type = NULL){
	$obj =& get_instance();
	$message = "";
	if($type == TELEGRAM_REGISTER_FUNCTION){
		$username = (isset($arr['username']) ? $arr['username'] : "");
		$created_by = (isset($arr['created_by']) ? $arr['created_by'] : "");
		$domain = (isset($arr['domain']) ? $arr['domain'] : "");
		$name = (isset($arr['full_name']) ? $arr['full_name'] : "");
		$line_id = (isset($arr['line_id']) ? $arr['line_id'] : "");
		$created_date = (isset($arr['created_date']) ? $arr['created_date'] : 0);
		if($username == $created_by){
			$message .= $obj->lang->line('lang_telegram_register_domain') . ":" . $domain."\r\n";
			$message .= $obj->lang->line('lang_telegram_register_platform_name') . ":" . $obj->lang->line('lang_telegram_register_platform')."\r\n";
			$message .= $obj->lang->line('lang_telegram_register_account') . ":" . $username."\r\n";
			$message .= $obj->lang->line('lang_telegram_register_time') . ":" . date('Y-m-d H:i',$created_date)."\r\n";
		}else{
			$message .= $obj->lang->line('lang_telegram_register_domain') . ":".$obj->lang->line('lang_telegram_register_admin')."(" . $created_by.")\r\n";
			$message .= $obj->lang->line('lang_telegram_register_platform_name') . ":" . $obj->lang->line('lang_telegram_register_platform')."\r\n";
			$message .= $obj->lang->line('lang_telegram_register_account') . ":" . $username."\r\n";
			$message .= $obj->lang->line('lang_telegram_register_time') . ":" . date('Y-m-d H:i',$created_date)."\r\n";
		}
	}
	send_message_telegram($account_id,$message);
}

function send_feedback_telegram($account_id = NULL,$type = NULL, $message = NULL){
	if($type == TELEGRAM_FEEDBACK_TYPE_CUSTOMER_SUPPORT){

	}else if($type == TELEGRAM_FEEDBACK_TYPE_DEPOSIT_WITHDRAWAL){

	}else if($type == TELEGRAM_FEEDBACK_TYPE_ACCOUNT){

	}else if($type == TELEGRAM_FEEDBACK_TYPE_ERROR_MESSAGE){

	}else{

	}
	send_message_telegram($account_id,$message);
}

function send_logs_telegram($account_id = NULL,$type = NULL, $data = NULL){
	$obj =& get_instance();
	$message = "";
	if($type == TELEGRAM_LOGS_TYPE_CREATE_USER_ACCOUNT){
		$message .= $obj->lang->line('lang_telegram_logs_admin') . ":" . ((isset($data['created_by'])) ? $data['created_by'] : '')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_platform') . ":" . $obj->lang->line('lang_telegram_register_platform')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_menu') . ":" . $obj->lang->line('lang_telegram_logs_create_user_account')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_user_sub_domain') . ":" . ((isset($data['domain_sub'])) ? $data['domain_sub'] : '')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_user_possess') . ":" . ((isset($data['possess'])) ? $data['possess'] : '')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_content') . ":" . $obj->lang->line('lang_telegram_logs_create_account') . " ". ((isset($data['username'])) ? $data['username'] : '')." (".$obj->lang->line('lang_telegram_logs_user_role') . ":" . ((isset($data['role_data']['role_name'])) ? $data['role_data']['role_name'] : '').")"."\r\n";
		send_message_telegram($account_id,$message);
	}else if($type == TELEGRAM_LOGS_TYPE_CREATE_SUB_ACCOUNT){
		$message .= $obj->lang->line('lang_telegram_logs_admin') . ":" . ((isset($data['created_by'])) ? $data['created_by'] : '')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_platform') . ":" . $obj->lang->line('lang_telegram_register_platform')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_menu') . ":" . $obj->lang->line('lang_telegram_logs_create_sub_account')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_content') . ":" . $obj->lang->line('lang_telegram_logs_create_account') . " ". ((isset($data['username'])) ? $data['username'] : '')." (".$obj->lang->line('lang_telegram_logs_user_role') . ":" . ((isset($data['role_data']['role_name'])) ? $data['role_data']['role_name'] : '').")"."\r\n";
		send_message_telegram($account_id,$message);
	}else if($type == TELEGRAM_LOGS_TYPE_UPDATE_USER_CHARACTER){
		$message .= $obj->lang->line('lang_telegram_logs_admin') . ":" . ((isset($data['updated_by'])) ? $data['updated_by'] : '')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_platform') . ":" . $obj->lang->line('lang_telegram_register_platform')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_menu') . ":" . $obj->lang->line('lang_telegram_logs_update_user_account_character')."\r\n";
		if((isset($data['possess'])) && (isset($data['old_data']['possess']))){
			if($data['possess'] != $data['old_data']['possess']){
				$message .= $obj->lang->line('lang_telegram_logs_user_possess') . ":" . ((isset($data['old_data']['possess'])) ? $data['old_data']['possess'] : '').">".((isset($data['possess'])) ? $data['possess'] : '')."\r\n";
			}else{
				$message .= $obj->lang->line('lang_telegram_logs_user_possess') . ":" . ((isset($data['possess'])) ? $data['possess'] : '')."\r\n";
			}
		}
		$message .= $obj->lang->line('lang_telegram_logs_content') . ":" . $obj->lang->line('lang_telegram_logs_account') . " ". ((isset($data['username'])) ? $data['username'] : '')." (".$obj->lang->line('lang_telegram_logs_user_role') . ":" . ((isset($data['old_role_data']['role_name'])) ? $data['old_role_data']['role_name'] : '') .">".((isset($data['new_role_data']['role_name'])) ? $data['new_role_data']['role_name'] : '').")"."\r\n";
		send_message_telegram($account_id,$message);
	}else if($type == TELEGRAM_LOGS_TYPE_UPDATE_SUB_ACCOUNT_CHARACTER){
		$message .= $obj->lang->line('lang_telegram_logs_admin') . ":" . ((isset($data['updated_by'])) ? $data['updated_by'] : '')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_platform') . ":" . $obj->lang->line('lang_telegram_register_platform')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_menu') . ":" . $obj->lang->line('lang_telegram_logs_update_sub_account_character')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_content') . ":" . $obj->lang->line('lang_telegram_logs_account') . " ". ((isset($data['username'])) ? $data['username'] : '')." (".$obj->lang->line('lang_telegram_logs_user_role') . ":" . ((isset($data['old_role_data']['role_name'])) ? $data['old_role_data']['role_name'] : '') .">".((isset($data['new_role_data']['role_name'])) ? $data['new_role_data']['role_name'] : '').")"."\r\n";
		send_message_telegram($account_id,$message);
	}else if($type == TELEGRAM_LOGS_TYPE_UPDATE_CHARACTER_PERMISSION){
		$new_permission = explode(',', $data['permissions']);
		$old_permission = explode(',', $data['old_role_data']['permissions']);

		$condition_capture = array(
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
			PERMISSION_PLAYER_MOBILE,
			PERMISSION_PLAYER_LINE_ID,
			PERMISSION_PLAYER_ACCOUNT_NAME,
		);

		$condition_capture_lang = array(
			PERMISSION_PLAYER_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_player_report_export_excel'),
			PERMISSION_DEPOSIT_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_deposit_report_export_excel'),
			PERMISSION_WITHDRAWAL_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_withdrawal_report_export_excel'),
			PERMISSION_WIN_LOSS_PLAYER_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_win_loss_player_report_export_excel'),
			PERMISSION_YEARLY_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_yearly_report_export_excel'),
			PERMISSION_TRANSACTION_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_transaction_report_export_excel'),
			PERMISSION_POINT_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_point_report_export_excel'),
			PERMISSION_CASH_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_cash_report_export_excel'),
			PERMISSION_REWARD_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_reward_report_export_excel'),
			PERMISSION_VERIFY_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_verify_report_export_excel'),
			PERMISSION_WALLET_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_wallet_report_export_excel'),
			PERMISSION_PLAYER_RISK_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_player_risk_report_export_excel'),
			PERMISSION_PLAYER_LOGIN_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_player_login_report_export_excel'),
			PERMISSION_PLAYER_LIST_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_player_list_export_excel'),
			PERMISSION_PLAYER_PROMOTION_LIST_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_player_promotion_list_export_excel'),
			PERMISSION_WIN_LOSS_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_win_loss_export_excel'),
			PERMISSION_WITHDRAWAL_VERIFY_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_withdrawal_verify_report_export_excel'),
			PERMISSION_REGISTER_DEPOSIT_RATE_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_register_deposit_rate_report_export_excel'),
			PERMISSION_REGISTER_DEPOSIT_RATE_YEARLY_REPORT_EXPORT_EXCEL => $obj->lang->line('lang_telegram_permission_register_deposit_rate_yearly_report_export_excel'),
			PERMISSION_PLAYER_MOBILE => $obj->lang->line('lang_telegram_permission_player_mobile'),
			PERMISSION_PLAYER_LINE_ID => $obj->lang->line('lang_telegram_permission_player_line_id'),
			PERMISSION_PLAYER_ACCOUNT_NAME => $obj->lang->line('lang_telegram_permission_player_account_name'),
		);

		$new_permission_capture = array_flip(array_intersect($new_permission,$condition_capture));
		$old_permission_capture = array_flip(array_intersect($old_permission,$condition_capture));
	

		$difference_array_new = array_diff_key($new_permission_capture,$old_permission_capture);
		$difference_array_old = array_diff_key($old_permission_capture,$new_permission_capture);
		$content = "";
		if(!empty($condition_capture)){
			foreach($condition_capture as $key => $value){
				if(isset($difference_array_new[$value]) || isset($difference_array_old[$value])){
					if(isset($difference_array_new[$value])){
						$content .= $condition_capture_lang[$value]." : ".$obj->lang->line('lang_telegram_permission_character_off').">".$obj->lang->line('lang_telegram_permission_character_on')."\r\n";
					}

					if(isset($difference_array_old[$value])){
						$content .= $condition_capture_lang[$value]." : ".$obj->lang->line('lang_telegram_permission_character_on').">".$obj->lang->line('lang_telegram_permission_character_off')."\r\n";
					}
				}
			}
		}


		$message .= $obj->lang->line('lang_telegram_logs_admin') . ":" . ((isset($data['updated_by'])) ? $data['updated_by'] : '')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_platform') . ":" . $obj->lang->line('lang_telegram_register_platform')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_menu') . ":" . $obj->lang->line('lang_telegram_logs_update_character_permission')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_content') . ":" .((isset($data['role_name'])) ? $data['role_name'] : '') ."\r\n";
		$message .= $content."\r\n";
		if(!empty($content)){
			send_message_telegram($account_id,$message);
		}
	}else if($type == TELEGRAM_LOGS_TYPE_PLAYER_LIST_EXPORT){
		$start_date = ((isset($data['from_date'])) ? ((!empty($data['from_date'])) ? $data['from_date'] : "" ): "");
		$end_date = ((isset($data['to_date'])) ? ((!empty($data['to_date'])) ? $data['to_date'] : "" ): "");
		$content = "";

		if(empty($start_date) && empty($end_date)){
			$content = $obj->lang->line('lang_telegram_logs_all');
		}else{
			if(!empty($start_date)){
				$content .= " (".$obj->lang->line('lang_telegram_logs_from').") ".$start_date;
			}else{
				$content .= " (".$obj->lang->line('lang_telegram_logs_from').") ".$obj->lang->line('lang_telegram_logs_empty');
			}

			if(!empty($end_date)){
				$content .= " ~  (".$obj->lang->line('lang_telegram_logs_to').") ".$end_date;
			}else{
				$content .= " ~  (".$obj->lang->line('lang_telegram_logs_to').") ".$obj->lang->line('lang_telegram_logs_empty');
			}
		}

		$message .= $obj->lang->line('lang_telegram_logs_admin') . ":" . ((isset($data['executed_by'])) ? $data['executed_by'] : '')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_platform') . ":" . $obj->lang->line('lang_telegram_register_platform')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_menu') . ":" . $obj->lang->line('lang_telegram_logs_player_list_export')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_content') . ":" .$content."\r\n";
		send_message_telegram($account_id,$message);
	}else if($type == TELEGRAM_LOGS_TYPE_PLAYER_AGENT_LIST_EXPORT){
		$start_date = ((isset($data['from_date'])) ? ((!empty($data['from_date'])) ? $data['from_date'] : "" ): "");
		$end_date = ((isset($data['to_date'])) ? ((!empty($data['to_date'])) ? $data['to_date'] : "" ): "");
		$content = "";

		if(empty($start_date) && empty($end_date)){
			$content = $obj->lang->line('lang_telegram_logs_all');
		}else{
			if(!empty($start_date)){
				$content .= " (".$obj->lang->line('lang_telegram_logs_from').") ".$start_date;
			}else{
				$content .= " (".$obj->lang->line('lang_telegram_logs_from').") ".$obj->lang->line('lang_telegram_logs_empty');
			}

			if(!empty($end_date)){
				$content .= " ~  (".$obj->lang->line('lang_telegram_logs_to').") ".$end_date;
			}else{
				$content .= " ~  (".$obj->lang->line('lang_telegram_logs_to').") ".$obj->lang->line('lang_telegram_logs_empty');
			}
		}

		$message .= $obj->lang->line('lang_telegram_logs_admin') . ":" . ((isset($data['executed_by'])) ? $data['executed_by'] : '')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_platform') . ":" . $obj->lang->line('lang_telegram_register_platform')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_menu') . ":" . $obj->lang->line('lang_telegram_logs_player_agent_list_export')."\r\n";
		$message .= $obj->lang->line('lang_telegram_logs_content') . ":" .$content."\r\n";
		send_message_telegram($account_id,$message);
	}
}

function send_message_telegram($account_id = NULL,$message = NULL){
	$telegram_token = "";
	$telegram_chat_id = "";
	if($account_id == TELEGRAM_MONEY_FLOW){
		$telegram_token = TELEGRAM_TOKEN_MONEY_FLOW;
		$telegram_chat_id = TELEGRAM_CHAT_ID_MONEY_FLOW;
	}else if($account_id == TELEGRAM_REGISTER){
		$telegram_token = TELEGRAM_TOKEN_REGISTER;
		$telegram_chat_id = TELEGRAM_CHAT_ID_REGISTER;
	}else if($account_id == TELEGRAM_FEEDBACK){
		$telegram_token = TELEGRAM_TOKEN_FEEDBACK;
		$telegram_chat_id = TELEGRAM_CHAT_ID_FEEDBACK;
	}else if($account_id == TELEGRAM_LOGS){
		$telegram_token = TELEGRAM_TOKEN_LOGS;
		$telegram_chat_id = TELEGRAM_CHAT_ID_LOGS;
	}else if($account_id == TELEGRAM_RISK){
		$telegram_token = TELEGRAM_TOKEN_RISK;
		$telegram_chat_id = TELEGRAM_CHAT_ID_RISK;
	}

	$url = 'https://api.telegram.org/bot'.$telegram_token.'/sendMessage?chat_id='.$telegram_chat_id;
	$content = array('chat_id' => $telegram_chat_id, 'text' => $message);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
	
    $test = "success";
   	return $test;
}