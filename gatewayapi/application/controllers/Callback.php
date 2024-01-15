<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Callback extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('message_model'));
	}
	public function fastspay($method = NULL){
		if($method == "payout"){
			$payment_gateway_code = "FASTSPAY";
			$prove_data['payment_gateway_code'] = $payment_gateway_code;
			$prove_data['input_get'] = json_encode($_GET);
			$prove_data['input_post'] = json_encode($_POST);
			$prove_data['input_request'] = json_encode($_REQUEST);
			$prove_data['input_json'] = file_get_contents("php://input");
			$prove_data['response_time'] = time();
			$prove_data['response_time'] = time();
			$prove_data['ip_address'] = $this->input->ip_address();
			$prove_data['input_type'] = 4;
			$this->db->insert('payment_gateway_log',$prove_data);
			$service_version = trim($_POST['service_version']);
			$sign = trim($_POST['sign']);
			$billno  = trim($_POST['billno']);
			$transaction_code  = trim($_POST['partner_orderid']);
			$currency = trim($_POST['currency']);
			$amount = trim($_POST['amount']);
			$account_name  = trim($_POST['account_name']);
			$account_number = trim($_POST['account_number']);
			$bank_code  = trim($_POST['bank_code']);
			$result = trim($_POST['status']);
			$fee  = trim($_POST['fee']);
			$bank_charge  = trim($_POST['bank_charge']);
			$error_code  = trim($_POST['error_code']);
			$error_description = trim($_POST['error_description']);
			$withdrawal_data = $this->withdrawal_model->get_withdrawal_data_by_transaction_code($transaction_code);
			if(!empty($withdrawal_data) && $withdrawal_data['status'] == STATUS_ON_PENDING)
			{
				$paymend_gateway = $this->gateway_model->get_gateway_data($payment_gateway_code,"11");
				if(!empty($paymend_gateway)){
					$paymend_gateway_data =  json_decode($paymend_gateway['api_data'],true);
					$signature_string = "service_version=".$service_version."&billno=".$billno."&partner_orderid=".$transaction_code."&currency=".$currency."&amount=".$amount."&account_name=".$account_name."&account_number=".$account_number."&bank_code=".$bank_code."&status=".$result."&fee=".$fee."&bank_charge=".$bank_charge."&key=".$paymend_gateway_data['key'];
					$hash = sha1($signature_string);
					$hash_upper = strtoupper($hash);
					if($hash_upper == $sign)
					{
						$player_data = $this->player_model->get_player_data_by_player_id($withdrawal_data['player_id']);
						if( ! empty($player_data))
						{
							if($result == "000")
							{
								$status_change = STATUS_APPROVE;
								$pData = array(
									'transaction_code' => $transaction_code,
									'order_no' => $billno,
									'status' => $status_change,
									'updated_by' => $player_data['username'],
								);						
							}
							else
							{
								$status_change = STATUS_CANCEL;
								$pData = array(
									'transaction_code' => $transaction_code,
									'status' => $status_change,
									'updated_by' => $player_data['username'],
								);
								if($status_change == STATUS_CANCEL){
									$this->player_model->point_transfer($player_data, $withdrawal_data['amount'], $player_data['username']);
									$this->player_model->insert_cash_transfer_report($player_data, $withdrawal_data['amount'], $player_data['username'], TRANSFER_WITHDRAWAL_REFUND);	
								}
							}
							$this->withdrawal_model->update_payment_gateway_withdrawal_status($pData);
							echo '<xml><billno>'.$billno.'</billno><status>OK</status></xml>';
						}
					}
				}
			}
		}
	}
	public function gspay2($method = NULL){
		if($method == "payout"){
			$payment_gateway_code = "GSPAY2";
			$prove_data['payment_gateway_code'] = $payment_gateway_code;
			$prove_data['input_get'] = json_encode($_GET);
			$prove_data['input_post'] = json_encode($_POST);
			$prove_data['input_request'] = json_encode($_REQUEST);
			$prove_data['input_json'] = file_get_contents("php://input");
			$prove_data['response_time'] = time();
			$prove_data['response_time'] = time();
			$prove_data['ip_address'] = $this->input->ip_address();
			$prove_data['input_type'] = 4;
			$this->db->insert('payment_gateway_log',$prove_data);
			$post = file_get_contents('php://input');
			if( ! empty($post)){
				$arr = json_decode($post, TRUE);
				$success = $arr['withdrawal_success'];
				$completed = $arr['completed'];
				$payment_id = $arr['withdrawalplus_id'];
                $transaction_code_alias = $arr['transaction_id'];
                $account_name = $arr['account_name'];
                $account_number = $arr['account_number'];
                $amount = $arr['amount'];
                $remark = $arr['remark'];
                $sign = $arr['signature'];
                $withdrawal_data = $this->withdrawal_model->get_withdrawal_data_by_transaction_alias_code($transaction_code_alias);
                if(!empty($withdrawal_data) && $withdrawal_data['status'] == STATUS_ON_PENDING)
				{
					$paymend_gateway = $this->gateway_model->get_gateway_data($payment_gateway_code,"11");
					if(!empty($paymend_gateway)){
						$paymend_gateway_data =  json_decode($paymend_gateway['api_data'],true);
					    $signature_string = $payment_id.$account_number.bcdiv($amount,1,2).$transaction_code_alias.$paymend_gateway_data['OperatorSecretKey'];
					    $hash = md5($signature_string);
					    if($hash == $sign)
					    {
					    	$player_data = $this->player_model->get_player_data_by_player_id($withdrawal_data['player_id']);
					    	if(!empty($player_data))
							{
							    if($completed)
    							{
    							    if($success){
    							        $status_change = STATUS_APPROVE;
    									$pData = array(
    										'transaction_code' => $withdrawal_data['transaction_code'],
    										'order_no' => $payment_id,
    										'status' => $status_change,
    										'updated_by' => $player_data['username'],
    									);	
    							    }else{
    							        $status_change = STATUS_CANCEL;
    									$pData = array(
    										'transaction_code' => $withdrawal_data['transaction_code'],
    										'status' => $status_change,
    										'updated_by' => $player_data['username'],
    									);
    							    }
    							}else{
    							    $status_change = STATUS_CANCEL;
									$pData = array(
										'transaction_code' => $withdrawal_data['transaction_code'],
										'status' => $status_change,
										'updated_by' => $player_data['username'],
									);
    							}
    							if($status_change == STATUS_CANCEL){
									$this->player_model->point_transfer($player_data, $withdrawal_data['amount'], $player_data['username']);
									$this->player_model->insert_cash_transfer_report($player_data, $withdrawal_data['amount'], $player_data['username'], TRANSFER_WITHDRAWAL_REFUND);	
								}
    							$this->withdrawal_model->update_payment_gateway_withdrawal_status($pData);
							}
					    }
					}
				}
			}
		}
	}
	public function fuzepay($method = NULL){
		if($method == "payout"){
			$payment_gateway_code = "FUZEPAY";
			$prove_data['payment_gateway_code'] = $payment_gateway_code;
			$prove_data['input_get'] = json_encode($_GET);
			$prove_data['input_post'] = json_encode($_POST);
			$prove_data['input_request'] = json_encode($_REQUEST);
			$prove_data['input_json'] = file_get_contents("php://input");
			$prove_data['response_time'] = time();
			$prove_data['response_time'] = time();
			$prove_data['ip_address'] = $this->input->ip_address();
			$prove_data['input_type'] = 4;
			$this->db->insert('payment_gateway_log',$prove_data);
			$post = file_get_contents("php://input");
			if( ! empty($post))
		    {
		        $arr = json_decode($post, TRUE);
		        $transaction_code = $arr['Remark'];
		        $trading_number = $arr['TradingNumber'];
		        $sign = $arr['Sing'];
		        $result = $arr['Status'];
		        $withdrawal_data = $this->withdrawal_model->get_withdrawal_data_by_transaction_code($transaction_code);
		        if(!empty($withdrawal_data) && $withdrawal_data['status'] == STATUS_ON_PENDING)
			    {
			        $paymend_gateway = $this->gateway_model->get_gateway_data($payment_gateway_code,"11");
			        if(!empty($paymend_gateway)){
					    $paymend_gateway_data =  json_decode($paymend_gateway['api_data'],true);
					    $signature_string = $paymend_gateway_data['HashKey']."&".$paymend_gateway_data['ValidateKey']."&".$trading_number;
					    $hash = md5($signature_string);
					    if($hash == $sign)
					    {
					        $player_data = $this->player_model->get_player_data_by_player_id($withdrawal_data['player_id']);
					        if( ! empty($player_data))
						    {
						        $payout_date = time();
						        $status_change = STATUS_PENDING;
						        if($result == "1")
							    {
							        $status_change = STATUS_APPROVE;
							        $payout_date = strtotime($arr['WithdrawalTime']);
							    }
							    else if($result == "3")
							    {
							        $status_change = STATUS_CANCEL;
							    }
							    $pData = array(
									'transaction_code' => $transaction_code,
									'status' => $status_change,
									'updated_by' => $player_data['username'],
									'payout_date' => $payout_date,
								);
								if($status_change != STATUS_PENDING){
    								if($status_change == STATUS_CANCEL){
    									$this->player_model->point_transfer($player_data, $withdrawal_data['amount'], $player_data['username']);
    									$this->player_model->insert_cash_transfer_report($player_data, $withdrawal_data['amount'], $player_data['username'], TRANSFER_WITHDRAWAL_REFUND);	
    								}else{
    								    $this->withdrawal_model->update_bank_withdrawal_count($withdrawal_data);
    								}
    								$this->withdrawal_model->update_payment_gateway_withdrawal_status($pData);
    								if($status_change == STATUS_APPROVE){
										if(TELEGRAM_STATUS == STATUS_ACTIVE){
											send_amount_telegram(TELEGRAM_MONEY_FLOW,$player_data['username'],$payment_gateway_code,$withdrawal_data['withdrawal_fee_amount'],TRANSFER_WITHDRAWAL);
										}
									}
									if($status_change == STATUS_APPROVE){
    									$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_SUCCESS_WITHDRAWAL);
    									if(!empty($system_message_data)){
    										$system_message_id = $system_message_data['system_message_id']; 
    										$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
    										$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
    										$create_time = time();
    										$username = $player_data['username'];
    										$array_key = array(
    											'system_message_id' => $system_message_data['system_message_id'],
    											'system_message_genre' => $system_message_data['system_message_genre'],
    											'player_level' => "",
    											'bank_channel' => "",
    											'username' => $username,
    										);
    										$Bdatalang = array();
    										$Bdata = array();
    										$player_message_list = $this->message_model->get_player_all_data_by_message_genre($array_key);
    										if(!empty($player_message_list)){
    											if(sizeof($player_message_list)>0){
    												foreach($player_message_list as $row){
    													$PBdata = array(
    														'system_message_id'	=> $system_message_id,
    														'player_id'			=> $row['player_id'],
    														'username'			=> $row['username'],
    														'active' 			=> STATUS_ACTIVE,
    														'is_read'			=> MESSAGE_UNREAD,
    														'created_by'		=> '',
    														'created_date'		=> $create_time,
    													);
    													array_push($Bdata, $PBdata);
    												}
    											}
    											if( ! empty($Bdata))
    											{
    												$this->db->insert_batch('system_message_user', $Bdata);
    											}
    											$success_message_data = $this->message_model->get_message_bluk_data($system_message_id,$create_time);
    											if(sizeof($lang)>0){
    												if(!empty($player_message_list) && sizeof($player_message_list)>0){
    													foreach($player_message_list as $player_message_list_row){
    														if(isset($success_message_data[$player_message_list_row['player_id']])){
    															foreach($lang as $k => $v){
    																$replace_string_array = array(
    																	SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $username,
    																	SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),
    																);
    																$PBdataLang = array(
    																	'system_message_user_id'	=> $success_message_data[$player_message_list_row['player_id']],
    																	'system_message_title'		=> $oldLangData[$v]['system_message_title'],
    																	'system_message_content'	=> get_system_message_content($oldLangData[$v]['system_message_content'],$replace_string_array),
    																	'language_id' 				=> $v
    																);
    																array_push($Bdatalang, $PBdataLang);
    															}
    														}
    													}	
    												}
    											}
    											$this->db->insert_batch('system_message_user_lang', $Bdatalang);
    										}
    									}
    								}
    								if($status_change == STATUS_CANCEL){
    									$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_FAILED_WITHDRAWAL);
    									if(!empty($system_message_data)){
    										$system_message_id = $system_message_data['system_message_id']; 
    										$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
    										$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
    										$create_time = time();
    										$username = $player_data['username'];
    										$array_key = array(
    											'system_message_id' => $system_message_data['system_message_id'],
    											'system_message_genre' => $system_message_data['system_message_genre'],
    											'player_level' => "",
    											'bank_channel' => "",
    											'username' => $username,
    										);
    										$Bdatalang = array();
    										$Bdata = array();
    										$player_message_list = $this->message_model->get_player_all_data_by_message_genre($array_key);
    										if(!empty($player_message_list)){
    											if(sizeof($player_message_list)>0){
    												foreach($player_message_list as $row){
    													$PBdata = array(
    														'system_message_id'	=> $system_message_id,
    														'player_id'			=> $row['player_id'],
    														'username'			=> $row['username'],
    														'active' 			=> STATUS_ACTIVE,
    														'is_read'			=> MESSAGE_UNREAD,
    														'created_by'		=> '',
    														'created_date'		=> $create_time,
    													);
    													array_push($Bdata, $PBdata);
    												}
    											}
    											if( ! empty($Bdata))
    											{
    												$this->db->insert_batch('system_message_user', $Bdata);
    											}
    											$success_message_data = $this->message_model->get_message_bluk_data($system_message_id,$create_time);
    											if(sizeof($lang)>0){
    												if(!empty($player_message_list) && sizeof($player_message_list)>0){
    													foreach($player_message_list as $player_message_list_row){
    														if(isset($success_message_data[$player_message_list_row['player_id']])){
    															foreach($lang as $k => $v){
    																$replace_string_array = array(
    																	SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $username,
    																	SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),
    																	SYSTEM_MESSAGE_PLATFORM_VALUE_REMARK => $this->input->post('remark', TRUE),
    																);
    																$PBdataLang = array(
    																	'system_message_user_id'	=> $success_message_data[$player_message_list_row['player_id']],
    																	'system_message_title'		=> $oldLangData[$v]['system_message_title'],
    																	'system_message_content'	=> get_system_message_content($oldLangData[$v]['system_message_content'],$replace_string_array),
    																	'language_id' 				=> $v
    																);
    																array_push($Bdatalang, $PBdataLang);
    															}
    														}
    													}	
    												}
    											}
    											$this->db->insert_batch('system_message_user_lang', $Bdatalang);
    										}
    									}
    								}
								}
						    }
					    }
			        }
			    }
		    }
		}
	}		
	public function wrampay($method = NULL)
	{
		if ($method == "payout") {
			$payment_gateway_code = "WRAMPAY";
			$prove_data['payment_gateway_code'] = $payment_gateway_code;
			$prove_data['input_get'] 			= json_encode($_GET);
			$prove_data['input_post'] 			= json_encode($_POST);
			$prove_data['input_request'] 		= json_encode($_REQUEST);
			$prove_data['input_json'] 			= file_get_contents("php://input");
			$prove_data['response_time'] 		= time();			
			$prove_data['ip_address'] 			= $this->input->ip_address();
			$prove_data['input_type'] 			= 4;
			$this->db->insert('payment_gateway_log', $prove_data);
			$post = file_get_contents("php://input");
			if (!empty($post)) {
				$arr = json_decode($post, TRUE);
				$transaction_code = $arr['Remark'];
				$trading_number = $arr['TradingNumber'];
				$sign = $arr['Sing'];
				$result = $arr['Status'];
				$withdrawal_data = $this->withdrawal_model->get_withdrawal_data_by_transaction_code($transaction_code);
				if (!empty($withdrawal_data) && $withdrawal_data['status'] == STATUS_ON_PENDING) {
					$paymend_gateway = $this->gateway_model->get_gateway_data($payment_gateway_code, "11");
					if (!empty($paymend_gateway)) {
						$paymend_gateway_data =  json_decode($paymend_gateway['api_data'], true);
						$signature_string = $paymend_gateway_data['HashKey'] . "&" . $paymend_gateway_data['ValidateKey'] . "&" . $trading_number;
						$hash = md5($signature_string);
						if ($hash == $sign) {
							$player_data = $this->player_model->get_player_data_by_player_id($withdrawal_data['player_id']);
							if (!empty($player_data)) {
								$payout_date = time();
								$status_change = STATUS_PENDING;
								if ($result == "1") {
									$status_change = STATUS_APPROVE;
									$payout_date = strtotime($arr['WithdrawalTime']);
								} else if ($result == "3") {
									$status_change = STATUS_CANCEL;
								}
								$pData = array(
									'transaction_code' => $transaction_code,
									'status' => $status_change,
									'updated_by' => $player_data['username'],
									'payout_date' => $payout_date,
								);
								if ($status_change != STATUS_PENDING) {
									if ($status_change == STATUS_CANCEL) {
										$this->player_model->point_transfer($player_data, $withdrawal_data['amount'], $player_data['username']);
										$this->player_model->insert_cash_transfer_report($player_data, $withdrawal_data['amount'], $player_data['username'], TRANSFER_WITHDRAWAL_REFUND);
									} else {
										$this->withdrawal_model->update_bank_withdrawal_count($withdrawal_data);
									}
									$this->withdrawal_model->update_payment_gateway_withdrawal_status($pData);
									if ($status_change == STATUS_APPROVE) {
										if (TELEGRAM_STATUS == STATUS_ACTIVE) {
											send_amount_telegram(TELEGRAM_MONEY_FLOW, $player_data['username'], $payment_gateway_code, $withdrawal_data['withdrawal_fee_amount'], TRANSFER_WITHDRAWAL);
										}
									}
									if ($status_change == STATUS_APPROVE) {
										$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_SUCCESS_WITHDRAWAL);
										if (!empty($system_message_data)) {
											$system_message_id = $system_message_data['system_message_id'];
											$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
											$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
											$create_time = time();
											$username = $player_data['username'];
											$array_key = array(
												'system_message_id' => $system_message_data['system_message_id'],
												'system_message_genre' => $system_message_data['system_message_genre'],
												'player_level' => "",
												'bank_channel' => "",
												'username' => $username,
											);
											$Bdatalang = array();
											$Bdata = array();
											$player_message_list = $this->message_model->get_player_all_data_by_message_genre($array_key);
											if (!empty($player_message_list)) {
												if (sizeof($player_message_list) > 0) {
													foreach ($player_message_list as $row) {
														$PBdata = array(
															'system_message_id'	=> $system_message_id,
															'player_id'			=> $row['player_id'],
															'username'			=> $row['username'],
															'active' 			=> STATUS_ACTIVE,
															'is_read'			=> MESSAGE_UNREAD,
															'created_by'		=> '',
															'created_date'		=> $create_time,
														);
														array_push($Bdata, $PBdata);
													}
												}
												if (!empty($Bdata)) {
													$this->db->insert_batch('system_message_user', $Bdata);
												}
												$success_message_data = $this->message_model->get_message_bluk_data($system_message_id, $create_time);
												if (sizeof($lang) > 0) {
													if (!empty($player_message_list) && sizeof($player_message_list) > 0) {
														foreach ($player_message_list as $player_message_list_row) {
															if (isset($success_message_data[$player_message_list_row['player_id']])) {
																$replace_string_array = array(
																	SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $username,
																	SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),
																);
																$PBdataLang = array(
																	'system_message_user_id'	=> $success_message_data[$player_message_list_row['player_id']],
																	'system_message_title'		=> $oldLangData[$v]['system_message_title'],
																	'system_message_content'	=> get_system_message_content($oldLangData[$v]['system_message_content'], $replace_string_array),
																	'language_id' 				=> $v
																);
																array_push($Bdatalang, $PBdataLang);
															}
														}
													}
												}
											}
											$this->db->insert_batch('system_message_user_lang', $Bdatalang);
										}
									}
								}
								if ($status_change == STATUS_CANCEL) {
									$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_FAILED_WITHDRAWAL);
									if (!empty($system_message_data)) {
										$system_message_id = $system_message_data['system_message_id'];
										$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
										$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
										$create_time = time();
										$username = $player_data['username'];
										$array_key = array(
											'system_message_id' => $system_message_data['system_message_id'],
											'system_message_genre' => $system_message_data['system_message_genre'],
											'player_level' => "",
											'bank_channel' => "",
											'username' => $username,
										);
										$Bdatalang = array();
										$Bdata = array();
										$player_message_list = $this->message_model->get_player_all_data_by_message_genre($array_key);
										if (!empty($player_message_list)) {
											if (sizeof($player_message_list) > 0) {
												foreach ($player_message_list as $row) {
													$PBdata = array(
														'system_message_id'	=> $system_message_id,
														'player_id'			=> $row['player_id'],
														'username'			=> $row['username'],
														'active' 			=> STATUS_ACTIVE,
														'is_read'			=> MESSAGE_UNREAD,
														'created_by'		=> '',
														'created_date'		=> $create_time,
													);
													array_push($Bdata, $PBdata);
												}
											}
											if (!empty($Bdata)) {
												$this->db->insert_batch('system_message_user', $Bdata);
											}
											$success_message_data = $this->message_model->get_message_bluk_data($system_message_id, $create_time);
											if (sizeof($lang) > 0) {
												if (!empty($player_message_list) && sizeof($player_message_list) > 0) {
													foreach ($player_message_list as $player_message_list_row) {
														if (isset($success_message_data[$player_message_list_row['player_id']])) {
															foreach ($lang as $k => $v) {
																$replace_string_array = array(
																	SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $username,
																	SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),
																	SYSTEM_MESSAGE_PLATFORM_VALUE_REMARK => $this->input->post('remark', TRUE),
																);
																$PBdataLang = array(
																	'system_message_user_id'	=> $success_message_data[$player_message_list_row['player_id']],
																	'system_message_title'		=> $oldLangData[$v]['system_message_title'],
																	'system_message_content'	=> get_system_message_content($oldLangData[$v]['system_message_content'], $replace_string_array),
																	'language_id' 				=> $v
																);
																array_push($Bdatalang, $PBdataLang);
															}
														}
													}
												}
											}
											$this->db->insert_batch('system_message_user_lang', $Bdatalang);
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	
	public function truepay($method = NULL)
	{
		if ($method == "payout") {
			$payment_gateway_code = "TRUEPAY";
			$prove_data['payment_gateway_code'] = $payment_gateway_code;
			$prove_data['input_get'] 			= json_encode($_GET);
			$prove_data['input_post'] 			= json_encode($_POST);
			$prove_data['input_request'] 		= json_encode($_REQUEST);
			$prove_data['input_json'] 			= file_get_contents("php://input");
			$prove_data['response_time'] 		= time();			
			$prove_data['ip_address'] 			= $this->input->ip_address();
			$prove_data['input_type'] 			= 4;
			$this->db->insert('payment_gateway_log', $prove_data);
			$post = file_get_contents("php://input");
			if (!empty($post)) {
				$arr = json_decode($post, TRUE);
				$transaction_code = $arr['id'];
				$amount = $arr['amount'];
				$ts = $arr['ts'];
				$sign = $arr['token'];
				$result = $arr['status'];
				$withdrawal_data = $this->withdrawal_model->get_withdrawal_data_by_transaction_code($transaction_code);
				if (!empty($withdrawal_data) && $withdrawal_data['status'] == STATUS_ON_PENDING) {
					$paymend_gateway = $this->gateway_model->get_gateway_data($payment_gateway_code, "11");
					if (!empty($paymend_gateway)) {
						$paymend_gateway_data =  json_decode($paymend_gateway['api_data'], true);
						$token = [
							$paymend_gateway_data['merchant_code'],
							$paymend_gateway_data['secret_key'],
							$transaction_code,
							bcdiv($amount,1,2), $ts
						];
						$hash = md5(implode('-', $token));
						if ($hash == $sign) {
							$player_data = $this->player_model->get_player_data_by_player_id($withdrawal_data['player_id']);
							if (!empty($player_data)) {
								$payout_date = time();
								$status_change = STATUS_PENDING;
								if ($result == "1") {
									$status_change = STATUS_APPROVE;
									$payout_date = $ts;
								} else {
									$status_change = STATUS_CANCEL;
								}
								$pData = array(
									'transaction_code' => $transaction_code,
									'status' => $status_change,
									'updated_by' => $player_data['username'],
									'payout_date' => $payout_date,
								);
								if ($status_change != STATUS_PENDING) {
									if ($status_change == STATUS_CANCEL) {
										$this->player_model->point_transfer($player_data, $withdrawal_data['amount'], $player_data['username']);
										$this->player_model->insert_cash_transfer_report($player_data, $withdrawal_data['amount'], $player_data['username'], TRANSFER_WITHDRAWAL_REFUND);
									} else {
										$this->withdrawal_model->update_bank_withdrawal_count($withdrawal_data);
									}
									$this->withdrawal_model->update_payment_gateway_withdrawal_status($pData);
									if ($status_change == STATUS_APPROVE) {
										if (TELEGRAM_STATUS == STATUS_ACTIVE) {
											send_amount_telegram(TELEGRAM_MONEY_FLOW, $player_data['username'], $payment_gateway_code, $withdrawal_data['withdrawal_fee_amount'], TRANSFER_WITHDRAWAL);
										}
									}
									if ($status_change == STATUS_APPROVE) {
										$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_SUCCESS_WITHDRAWAL);
										if (!empty($system_message_data)) {
											$system_message_id = $system_message_data['system_message_id'];
											$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
											$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
											$create_time = time();
											$username = $player_data['username'];
											$array_key = array(
												'system_message_id' => $system_message_data['system_message_id'],
												'system_message_genre' => $system_message_data['system_message_genre'],
												'player_level' => "",
												'bank_channel' => "",
												'username' => $username,
											);
											$Bdatalang = array();
											$Bdata = array();
											$player_message_list = $this->message_model->get_player_all_data_by_message_genre($array_key);
											if (!empty($player_message_list)) {
												if (sizeof($player_message_list) > 0) {
													foreach ($player_message_list as $row) {
														$PBdata = array(
															'system_message_id'	=> $system_message_id,
															'player_id'			=> $row['player_id'],
															'username'			=> $row['username'],
															'active' 			=> STATUS_ACTIVE,
															'is_read'			=> MESSAGE_UNREAD,
															'created_by'		=> '',
															'created_date'		=> $create_time,
														);
														array_push($Bdata, $PBdata);
													}
												}
												if (!empty($Bdata)) {
													$this->db->insert_batch('system_message_user', $Bdata);
												}
												$success_message_data = $this->message_model->get_message_bluk_data($system_message_id, $create_time);
												if (sizeof($lang) > 0) {
													if (!empty($player_message_list) && sizeof($player_message_list) > 0) {
														foreach ($player_message_list as $player_message_list_row) {
															if (isset($success_message_data[$player_message_list_row['player_id']])) {
																foreach ($lang as $k => $v) {
																	$replace_string_array = array(
																		SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $username,
																		SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),
																	);
																	$PBdataLang = array(
																		'system_message_user_id'	=> $success_message_data[$player_message_list_row['player_id']],
																		'system_message_title'		=> $oldLangData[$v]['system_message_title'],
																		'system_message_content'	=> get_system_message_content($oldLangData[$v]['system_message_content'], $replace_string_array),
																		'language_id' 				=> $v
																	);
																	array_push($Bdatalang, $PBdataLang);
																}
															}
														}
													}
												}
											}
											$this->db->insert_batch('system_message_user_lang', $Bdatalang);
										}
									}
								}
								if ($status_change == STATUS_CANCEL) {
									$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_FAILED_WITHDRAWAL);
									if (!empty($system_message_data)) {
										$system_message_id = $system_message_data['system_message_id'];
										$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
										$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
										$create_time = time();
										$username = $player_data['username'];
										$array_key = array(
											'system_message_id' => $system_message_data['system_message_id'],
											'system_message_genre' => $system_message_data['system_message_genre'],
											'player_level' => "",
											'bank_channel' => "",
											'username' => $username,
										);
										$Bdatalang = array();
										$Bdata = array();
										$player_message_list = $this->message_model->get_player_all_data_by_message_genre($array_key);
										if (!empty($player_message_list)) {
											if (sizeof($player_message_list) > 0) {
												foreach ($player_message_list as $row) {
													$PBdata = array(
														'system_message_id'	=> $system_message_id,
														'player_id'			=> $row['player_id'],
														'username'			=> $row['username'],
														'active' 			=> STATUS_ACTIVE,
														'is_read'			=> MESSAGE_UNREAD,
														'created_by'		=> '',
														'created_date'		=> $create_time,
													);
													array_push($Bdata, $PBdata);
												}
											}
											if (!empty($Bdata)) {
												$this->db->insert_batch('system_message_user', $Bdata);
											}
											$success_message_data = $this->message_model->get_message_bluk_data($system_message_id, $create_time);
											if (sizeof($lang) > 0) {
												if (!empty($player_message_list) && sizeof($player_message_list) > 0) {
													foreach ($player_message_list as $player_message_list_row) {
														if (isset($success_message_data[$player_message_list_row['player_id']])) {
															foreach ($lang as $k => $v) {
																$replace_string_array = array(
																	SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $username,
																	SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),
																	SYSTEM_MESSAGE_PLATFORM_VALUE_REMARK => $this->input->post('remark', TRUE),
																);
																$PBdataLang = array(
																	'system_message_user_id'	=> $success_message_data[$player_message_list_row['player_id']],
																	'system_message_title'		=> $oldLangData[$v]['system_message_title'],
																	'system_message_content'	=> get_system_message_content($oldLangData[$v]['system_message_content'], $replace_string_array),
																	'language_id' 				=> $v
																);
																array_push($Bdatalang, $PBdataLang);
															}
														}
													}
												}
											}
											$this->db->insert_batch('system_message_user_lang', $Bdatalang);
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
}
