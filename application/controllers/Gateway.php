<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gateway extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('deposit_model', 'general_model', 'miscellaneous_model','payment_gateway_model','promotion_model','api_model'));
	}

	public function payessence($method = NULL) {
		if($method == 'redirect')
		{
			$result = trim($_GET['Result']);
			$transaction_code = trim($_GET['TransNum']);
			$check_string = trim($_GET['CheckString']);
			
			$concate = PG_PE_MERCHANT_CODE . PG_PE_SECRET_KEY . $transaction_code;
			$hash = hash('sha256', $concate, true);
			$hex = bin2hex($hash);
			
			if($hex == $check_string)
			{
				$deposit_data = $this->deposit_model->get_deposit_data_by_transaction_code($transaction_code);
				if( ! empty($deposit_data))
				{
					$player_data = $this->player_model->get_player_data_by_player_id($deposit_data['player_id']);
					if( ! empty($player_data))
					{
						if($result == 10001)
						{
							$pData = array(
								'transaction_code' => $transaction_code,
								'order_no' => trim($_GET['DepositId']),
								'bank_name' => trim($_GET['ToBank']),
								'bank_account_no' => trim($_GET['BankAccNum']),
								'status' => STATUS_APPROVE,
								'updated_by' => $player_data['username'],
							);
							
							$data = array(
								'msg_alert' => $this->lang->line('error_deposit_successful'),
								'msg_icon' => 1,
							);
								
							$this->player_model->point_transfer($player_data, $deposit_data['amount'], $player_data['username']);
							$this->general_model->insert_cash_transfer_report($player_data, $deposit_data['amount'], $player_data['username'], TRANSFER_PG_DEPOSIT);							
						}
						else
						{
							$pData = array(
								'transaction_code' => $transaction_code,
								'status' => STATUS_CANCEL,
								'updated_by' => $player_data['username'],
							);
							
							$data = array(
								'msg_alert' => $this->lang->line('error_deposit_failed'),
								'msg_icon' => 2,
							);
						}
						
						$this->deposit_model->update_deposit_status($pData);
						
						$this->session->set_userdata($data);
					}	
				}	
			}
			
			redirect('home');
		}
		else if($method == 'callback')
		{
			$post = file_get_contents('php://input');
			$arr = json_decode($post, TRUE);
			
			$result = (isset($arr['Result']) ? trim($arr['Result']) : '');
			$transaction_code = (isset($arr['TransNum']) ? trim($arr['TransNum']) : '');
			$check_string = (isset($arr['CheckString']) ? trim($arr['CheckString']) : '');
			
			$concate = PG_PE_MERCHANT_CODE . PG_PE_SECRET_KEY . $transaction_code;
			$hash = hash('sha256', $concate, true);
			$hex = bin2hex($hash);
			
			if($hex == $check_string)
			{
				$deposit_data = $this->deposit_model->get_deposit_data_by_transaction_code($transaction_code);
				if( ! empty($deposit_data))
				{
					$player_data = $this->player_model->get_player_data_by_player_id($deposit_data['player_id']);
					if( ! empty($player_data))
					{
						if($result == 10001)
						{
							$pData = array(
								'transaction_code' => $transaction_code,
								'order_no' => (isset($arr['DepositId']) ? trim($arr['DepositId']) : ''),
								'bank_name' => (isset($arr['ToBank']) ? trim($arr['ToBank']) : ''),
								'bank_account_no' => (isset($arr['BankAccNum']) ? trim($arr['BankAccNum']) : ''),
								'status' => STATUS_APPROVE,
								'updated_by' => $player_data['username'],
							);
							
							$data = array(
								'msg_alert' => $this->lang->line('error_deposit_successful'),
								'msg_icon' => 1,
							);
							
							$this->player_model->point_transfer($player_data, $deposit_data['amount'], $player_data['username']);
							$this->general_model->insert_cash_transfer_report($player_data, $deposit_data['amount'], $player_data['username'], TRANSFER_PG_DEPOSIT);	
						}
						else
						{
							$pData = array(
								'transaction_code' => $transaction_code,
								'status' => STATUS_CANCEL,
								'updated_by' => $player_data['username'],
							);
							
							$data = array(
								'msg_alert' => $this->lang->line('error_deposit_failed'),
								'msg_icon' => 2,
							);
						}
						
						$this->deposit_model->update_deposit_status($pData);
					}
				}	
			}
		}
		else
		{
			if($this->session->userdata('pg_deposit_id'))
			{
				$data['player'] = $this->player_model->get_player_data($this->session->userdata('username'));
				$data['setting'] = $this->miscellaneous_model->get_miscellaneous();
				$data['deposit'] = $this->deposit_model->get_pending_deposit_data($this->session->userdata('pg_deposit_id'));
				$this->session->unset_userdata('pg_deposit_id');
				
				$this->load->view('gateway/payessence_pg_view', $data);
			}
			else
			{
				redirect('home');
			}
		}
	}
	
	public function paylah88($method = NULL){
		if($method == 'redirect'){
			//insert log
			$payment_gateway_code = "PAYLAH88";
			$prove_data['payment_gateway_code'] = $payment_gateway_code;
			$prove_data['input_get'] = json_encode($_GET);
			$prove_data['input_post'] = json_encode($_POST);
			$prove_data['input_request'] = json_encode($_REQUEST);
			$prove_data['input_json'] = file_get_contents("php://input");
			$prove_data['response_time'] = time();
			$prove_data['response_time'] = time();
			$prove_data['ip_address'] = $this->input->ip_address();
			$prove_data['input_type'] = 1;
			$this->db->insert('payment_gateway_log',$prove_data);

			$ID = trim($_POST['ID']);
			$Amount = trim($_POST['Amount']);
			$Currency  = trim($_POST['Currency']);
			$Customer  = trim($_POST['Customer']);
			$Language = trim($_POST['Language']);
			$Merchant  = trim($_POST['Merchant']);
			$Reference = trim($_POST['Reference']);
			$Datetime  = trim($_POST['Datetime']);
			$Status = trim($_POST['Status']);
			$Key = trim($_POST['Key']);
			$Note = trim($_POST['Note']);
			$transaction_code = trim($_POST['Reference']);
			$sign = trim($_POST['Key']);
			$result = trim($_POST['Status']);

			$deposit_data = $this->deposit_model->get_deposit_data_by_transaction_code($transaction_code);
			if(!empty($deposit_data))
			{
			    $paymend_gateway = $this->payment_gateway_model->get_payment_gateway_data($deposit_data['payment_gateway_id']);
				if(!empty($paymend_gateway)){
					$paymend_gateway_data =  json_decode($paymend_gateway['api_data'],true);
					$signature_string = $Merchant.$Reference.$Customer.$Amount.$Currency.$Status.$paymend_gateway_data['key'];
					$hash = md5($signature_string);
					$hash_upper = strtoupper($hash);
					if($hash_upper == $sign)
					{
						$player_data = $this->player_model->get_player_data_by_player_id($deposit_data['player_id']);
						if( ! empty($player_data))
						{
							if($result == "000" || $result == "006")
							{
							   $data = array(
									'msg_alert' => $this->lang->line('error_deposit_successful'),
									'msg_icon' => 1,
								);
							}
							else
							{
								$pData = array(
									'transaction_code' => $transaction_code,
									'status' => STATUS_CANCEL,
									'updated_by' => $player_data['username'],
								);
								
								$data = array(
									'msg_alert' => $this->lang->line('error_deposit_failed'),
									'msg_icon' => 2,
								);
							}
							
							$this->session->set_userdata($data);
						}		
					}
				}
			}
			
			redirect('home');
		}else if($method == 'callback'){
			//insert log
			$payment_gateway_code = "PAYLAH88";
			$prove_data['payment_gateway_code'] = $payment_gateway_code;
			$prove_data['input_get'] = json_encode($_GET);
			$prove_data['input_post'] = json_encode($_POST);
			$prove_data['input_request'] = json_encode($_REQUEST);
			$prove_data['input_json'] = file_get_contents("php://input");
			$prove_data['response_time'] = time();
			$prove_data['response_time'] = time();
			$prove_data['ip_address'] = $this->input->ip_address();
			$prove_data['input_type'] = 2;
			$this->db->insert('payment_gateway_log',$prove_data);

			$ID = trim($_POST['ID']);
			$Amount = trim($_POST['Amount']);
			$Currency  = trim($_POST['Currency']);
			$Customer  = trim($_POST['Customer']);
			$Language = trim($_POST['Language']);
			$Merchant  = trim($_POST['Merchant']);
			$Reference = trim($_POST['Reference']);
			$Datetime  = trim($_POST['Datetime']);
			$Status = trim($_POST['Status']);
			$Key = trim($_POST['Key']);
			$Note = trim($_POST['Note']);
			$StatementDate = trim($_POST['StatementDate']);
			$DepositFee = trim($_POST['DepositFee']);
			$transaction_code = trim($_POST['Reference']);
			$sign = trim($_POST['Key']);
			$result = trim($_POST['Status']);

			$deposit_data = $this->deposit_model->get_deposit_data_by_transaction_code($transaction_code);
			if(!empty($deposit_data) && $deposit_data['status'] == STATUS_ON_PENDING)
			{
				$paymend_gateway = $this->payment_gateway_model->get_payment_gateway_data($deposit_data['payment_gateway_id']);
				if(!empty($paymend_gateway)){
					$paymend_gateway_data =  json_decode($paymend_gateway['api_data'],true);
					$signature_string = $Merchant.$Reference.$Customer.$Amount.$Currency.$Status.$paymend_gateway_data['key'];
					$hash = md5($signature_string);
					$hash_upper = strtoupper($hash);
					if($hash_upper == $sign)
					{
						$player_data = $this->player_model->get_player_data_by_player_id($deposit_data['player_id']);
						if( ! empty($player_data))
						{
							if($result == "000" || $result == "006")
							{
								if($paymend_gateway['payment_gateway_admin_verification'] == STATUS_INACTIVE){
									$status_change = STATUS_APPROVE;
								}else{
									$status_change = STATUS_PENDING;
								}

								$pData = array(
									'transaction_code' => $transaction_code,
									'order_no' => $ID,
									'status' => $status_change,
									'handling_fee' => $DepositFee,
									'updated_by' => $player_data['username'],
								);
								
								$data = array(
									'msg_alert' => $this->lang->line('error_deposit_successful'),
									'msg_icon' => 1,
								);

								if($status_change == STATUS_APPROVE){
									$this->player_model->point_transfer($player_data, $deposit_data['amount'], $player_data['username']);
									switch($deposit_data['deposit_type'])
									{
										case DEPOSIT_CREDIT_CARD: $transfer_type = TRANSFER_CREDIT_CARD_DEPOSIT; break;
										case DEPOSIT_HYPERMART: $transfer_type = TRANSFER_HYPERMART_DEPOSIT; break;
										default: $transfer_type = TRANSFER_PG_DEPOSIT; break;
									}
									$this->general_model->insert_cash_transfer_report($player_data, $deposit_data['amount'], $player_data['username'], $transfer_type);	
								}
							}
							else
							{
								$pData = array(
									'transaction_code' => $transaction_code,
									'status' => STATUS_CANCEL,
									'updated_by' => $player_data['username'],
								);
								
								$data = array(
									'msg_alert' => $this->lang->line('error_deposit_failed'),
									'msg_icon' => 2,
								);
							}
							
							$this->deposit_model->update_payment_gateway_deposit_status($pData);
						}		
					}
				}
			}
		}else{
			if($this->session->userdata('pg_deposit_id'))
			{
				if($this->session->userdata('pg_deposit_id'))
				{
					$data['player'] = $this->player_model->get_player_data($this->session->userdata('username'));
					$data['setting'] = $this->miscellaneous_model->get_miscellaneous();
					$data['deposit'] = $this->deposit_model->get_payment_gateway_pending_deposit_data($this->session->userdata('pg_deposit_id'));
					$paymend_gateway = $this->payment_gateway_model->get_payment_gateway_data($data['deposit']['payment_gateway_id']);
					$data['paymend_gateway'] =  json_decode($paymend_gateway['api_data'],true);
					$this->session->unset_userdata('pg_deposit_id');
					$this->load->view('gateway/paylah88_pg_view', $data);
				}
				else
				{
					redirect('home');
				}
			}
		}
	}
	
	public function gspay2($method = NULL){
		if($method == 'redirect'){
			//insert log
			$payment_gateway_code = "GSPAY2";
			$prove_data['payment_gateway_code'] = $payment_gateway_code;
			$prove_data['input_get'] = json_encode($_GET);
			$prove_data['input_post'] = json_encode($_POST);
			$prove_data['input_request'] = json_encode($_REQUEST);
			$prove_data['input_json'] = file_get_contents("php://input");
			$prove_data['response_time'] = time();
			$prove_data['response_time'] = time();
			$prove_data['ip_address'] = $this->input->ip_address();
			$prove_data['input_type'] = 1;
			$this->db->insert('payment_gateway_log',$prove_data);
            redirect('home');
		}else if($method == 'callback'){
			//insert log
			$payment_gateway_code = "GSPAY2";
			$prove_data['payment_gateway_code'] = $payment_gateway_code;
			$prove_data['input_get'] = json_encode($_GET);
			$prove_data['input_post'] = json_encode($_POST);
			$prove_data['input_request'] = json_encode($_REQUEST);
			$prove_data['input_json'] = file_get_contents("php://input");
			$prove_data['response_time'] = time();
			$prove_data['response_time'] = time();
			$prove_data['ip_address'] = $this->input->ip_address();
			$prove_data['input_type'] = 2;
			$this->db->insert('payment_gateway_log',$prove_data);
            
            $post = file_get_contents('php://input');
            if( ! empty($post)){
                $arr = json_decode($post, TRUE);
                $payment_id = $arr['payment_id'];
                $transaction_code_alias = $arr['transaction_id'];
                $amount = $arr['amount'];
                $remark = $arr['remark'];
                $completed = $arr['completed'];
                $success = $arr['success'];
                $sign = $arr['signature'];
                
                $deposit_data = $this->deposit_model->get_deposit_data_by_transaction_alias_code($transaction_code_alias);
                if(!empty($deposit_data) && $deposit_data['status'] == STATUS_ON_PENDING)
			    {
			        $paymend_gateway = $this->payment_gateway_model->get_payment_gateway_data($deposit_data['payment_gateway_id']);
			        if(!empty($paymend_gateway)){
			            $paymend_gateway_data =  json_decode($paymend_gateway['api_data'],true);
					    $signature_string = $payment_id.bcdiv($amount,1,2).$transaction_code_alias.$paymend_gateway_data['OperatorSecretKey'];
					    $hash = md5($signature_string);
					    if($hash == $sign)
					    {
					        $player_data = $this->player_model->get_player_data_by_player_id($deposit_data['player_id']);
					        if( ! empty($player_data))
    						{
    							if($completed)
    							{
    							    if($success){
    							        if($paymend_gateway['payment_gateway_admin_verification'] == STATUS_INACTIVE){
        									$status_change = STATUS_APPROVE;
        								}else{
        									$status_change = STATUS_PENDING;
        								}
        								
        								
    							        $pData = array(
        									'transaction_code' => $deposit_data['transaction_code'],
        									'order_no' => $payment_id,
        									'status' => $status_change,
        									'updated_by' => $player_data['username'],
        								);
        								
        								$data = array(
        									'msg_alert' => $this->lang->line('error_deposit_successful'),
        									'msg_icon' => 1,
        								);
        								
        								if($status_change == STATUS_APPROVE){
        								    if(!empty($deposit_data['promotion_id'])){
												$PromotionDepositPending = $this->promotion_model->deposit_promotion_on_pending($deposit_data);
												if(!empty($PromotionDepositPending)){
													$member_total_wallet = $this->get_member_latest_wallet($player_data);
													$promotion_response = $this->promotion_model->deposit_promotion_approve_decision($PromotionDepositPending,$member_total_wallet);
													$this->promotion_model->update_deposit_promotion_status($deposit_data, $promotion_response['code']);
													if($promotion_response['status'] == EXIT_SUCCESS){
														$this->promotion_model->update_player_promotion_after_deposit($PromotionDepositPending,$member_total_wallet,$PromotionDepositPending['deposit_amount'],1);
														if($PromotionDepositPending['reward_on_apply'] == STATUS_ACTIVE){
															$this->player_model->point_transfer($player_data, $PromotionDepositPending['reward_amount'], $player_data['username']);
															$this->general_model->insert_cash_transfer_report($player_data, $PromotionDepositPending['reward_amount'], $player_data['username'], TRANSFER_PROMOTION);
															$this->promotion_model->update_player_promotion_reward_claim($PromotionDepositPending,$member_total_wallet);
														}
													}
												}
											}
        									$this->player_model->point_transfer($player_data, $deposit_data['amount'], $player_data['username']);
        									switch($deposit_data['deposit_type'])
        									{
        										case DEPOSIT_CREDIT_CARD: $transfer_type = TRANSFER_CREDIT_CARD_DEPOSIT; break;
        										case DEPOSIT_HYPERMART: $transfer_type = TRANSFER_HYPERMART_DEPOSIT; break;
        										default: $transfer_type = TRANSFER_PG_DEPOSIT; break;
        									}
        									$this->general_model->insert_cash_transfer_report($player_data, $deposit_data['amount'], $player_data['username'], $transfer_type);	
        								}
    							    }else{
    							        $pData = array(
        									'transaction_code' => $deposit_data['transaction_code'],
        									'status' => STATUS_CANCEL,
        									'updated_by' => $player_data['username'],
        								);
        								
        								$data = array(
        									'msg_alert' => $this->lang->line('error_deposit_failed'),
        									'msg_icon' => 2,
        								);
    							    }
    							    $this->deposit_model->update_payment_gateway_deposit_status($pData);
    							}
    						}	
					    }
			        }
			    }
            }
		}else{
			if($this->session->userdata('pg_deposit_id'))
			{
				if($this->session->userdata('pg_deposit_id'))
				{
					$data['player'] = $this->player_model->get_player_data($this->session->userdata('username'));
					$data['setting'] = $this->miscellaneous_model->get_miscellaneous();
					$data['deposit'] = $this->deposit_model->get_payment_gateway_pending_deposit_data($this->session->userdata('pg_deposit_id'));
					$paymend_gateway = $this->payment_gateway_model->get_payment_gateway_data($data['deposit']['payment_gateway_id']);
					$data['paymend_gateway'] =  json_decode($paymend_gateway['api_data'],true);
					$data['paymend_gateway_auth_key'] = "";
					$data['paymend_gateway_secret_key'] = "";
					
					if(isset($data['paymend_gateway']['BankData'][$data['deposit']['payment_gateway_bank']])){
					    $data['paymend_gateway_auth_key'] = $data['paymend_gateway']['BankData'][$data['deposit']['payment_gateway_bank']]['AuthKey'];
					    $data['paymend_gateway_secret_key'] = $data['paymend_gateway']['BankData'][$data['deposit']['payment_gateway_bank']]['SecretKey'];
					}
					
					$update_deposit_amount = array(
    					'transaction_code_alias' => "D".str_pad($data['deposit']['deposit_id'], 14, '0', STR_PAD_LEFT),
    				);
    
    
    				$this->db->where('deposit_id', $data['deposit']['deposit_id']);
    				$this->db->limit(1);
    				$this->db->update('deposits', $update_deposit_amount);
    
    				//Update Value
    				$data['deposit']['transaction_code_alias'] = $update_deposit_amount['transaction_code_alias'];
				    
					$this->session->unset_userdata('pg_deposit_id');
					$plain_text = $data['paymend_gateway_secret_key'].$data['deposit']['transaction_code_alias'].bcdiv($data['deposit']['amount_rate'],1,2).$data['paymend_gateway_secret_key'];
					$hash = md5($plain_text);
					$url = $data['paymend_gateway']['APIUrl']."/".$data['paymend_gateway_auth_key']."/gateway/interbank";
					$param_array = array(
					    'transaction_id' => $data['deposit']['transaction_code_alias'],
					    'amount' => $data['deposit']['amount_rate'],
					    'payment_desc' => $data['deposit']['transaction_code'],
					    'signature' => $hash,
					);
					$response = $this->curl_json($url,$param_array);
					$result_array = json_decode($response, TRUE);
					if($result_array['code'] == "200"){
					    $result_array_data = json_decode($result_array['data'],true);
					    redirect($result_array_data[0]['payment_url']."&return=".base_url('gateway/'.$data['paymend_gateway']['ShowURL']));
					}else{
					    $pData = array(
    						'transaction_code' => $data['deposit']['transaction_code'],
    						'status' => STATUS_CANCEL,
    						'updated_by' => $this->session->userdata('username'),
    					);
					    $data = array(
							'msg_alert' => $this->lang->line('error_deposit_failed'),
							'msg_icon' => 2,
						);
						$this->session->set_userdata($data);
						$this->deposit_model->update_payment_gateway_deposit_status($pData);
					    redirect('home');
					}
				}
				else
				{
					redirect('home');
				}
			}
		}
	}
	
	public function fastspay($method = NULL){
		if($method == 'redirect'){
			//insert log
			$payment_gateway_code = "FASTSPAY";
			$prove_data['payment_gateway_code'] = $payment_gateway_code;
			$prove_data['input_get'] = json_encode($_GET);
			$prove_data['input_post'] = json_encode($_POST);
			$prove_data['input_request'] = json_encode($_REQUEST);
			$prove_data['input_json'] = file_get_contents("php://input");
			$prove_data['response_time'] = time();
			$prove_data['response_time'] = time();
			$prove_data['ip_address'] = $this->input->ip_address();
			$prove_data['input_type'] = 1;
			$this->db->insert('payment_gateway_log',$prove_data);


			$service_version = trim($_POST['service_version']);
			$sign = trim($_POST['sign']);
			$billno  = trim($_POST['billno']);
			$transaction_code  = trim($_POST['partner_orderid']);
			$currency = trim($_POST['currency']);
			$request_amount  = trim($_POST['request_amount']);
			$receive_amount = trim($_POST['receive_amount']);
			$fee  = trim($_POST['fee']);
			$result = trim($_POST['status']);

			$deposit_data = $this->deposit_model->get_deposit_data_by_transaction_code($transaction_code);
			if(!empty($deposit_data))
			{
				$paymend_gateway = $this->payment_gateway_model->get_payment_gateway_data($deposit_data['payment_gateway_id']);
				if(!empty($paymend_gateway)){
					$paymend_gateway_data =  json_decode($paymend_gateway['api_data'],true);
					$signature_string = "service_version=".$service_version."&billno=".$billno."&partner_orderid=".$transaction_code."&currency=".$currency."&request_amount=".$request_amount."&receive_amount=".$receive_amount."&fee=".$fee."&status=".$result."&key=".$paymend_gateway_data['key'];
					$hash = sha1($signature_string);
					$hash_upper = strtoupper($hash);
					if($hash_upper == $sign)
					{
						$player_data = $this->player_model->get_player_data_by_player_id($deposit_data['player_id']);
						if( ! empty($player_data))
						{
							if($result == "000" || $result == "002")
							{
								$data = array(
									'msg_alert' => $this->lang->line('error_deposit_successful'),
									'msg_icon' => 1,
								);	
							}
							else
							{	
								$data = array(
									'msg_alert' => $this->lang->line('error_deposit_failed'),
									'msg_icon' => 2,
								);
							}
							
							$this->session->set_userdata($data);
						}		
					}
					redirect('home');
				}
			}
		}else if($method == 'callback'){
			//insert log
			$payment_gateway_code = "FASTSPAY";
			$prove_data['payment_gateway_code'] = $payment_gateway_code;
			$prove_data['input_get'] = json_encode($_GET);
			$prove_data['input_post'] = json_encode($_POST);
			$prove_data['input_request'] = json_encode($_REQUEST);
			$prove_data['input_json'] = file_get_contents("php://input");
			$prove_data['response_time'] = time();
			$prove_data['response_time'] = time();
			$prove_data['ip_address'] = $this->input->ip_address();
			$prove_data['input_type'] = 2;
			$this->db->insert('payment_gateway_log',$prove_data);

			$service_version = trim($_POST['service_version']);
			$sign = trim($_POST['sign']);
			$billno  = trim($_POST['billno']);
			$transaction_code  = trim($_POST['partner_orderid']);
			$currency = trim($_POST['currency']);
			$request_amount  = trim($_POST['request_amount']);
			$receive_amount = trim($_POST['receive_amount']);
			$fee  = trim($_POST['fee']);
			$result = trim($_POST['status']);


			$deposit_data = $this->deposit_model->get_deposit_data_by_transaction_code($transaction_code);
			if(!empty($deposit_data) && $deposit_data['status'] == STATUS_ON_PENDING)
			{
				$paymend_gateway = $this->payment_gateway_model->get_payment_gateway_data($deposit_data['payment_gateway_id']);
				if(!empty($paymend_gateway)){
					$paymend_gateway_data =  json_decode($paymend_gateway['api_data'],true);
					$signature_string = "service_version=".$service_version."&billno=".$billno."&partner_orderid=".$transaction_code."&currency=".$currency."&request_amount=".$request_amount."&receive_amount=".$receive_amount."&fee=".$fee."&status=".$result."&key=".$paymend_gateway_data['key'];
					$hash = sha1($signature_string);
					$hash_upper = strtoupper($hash);
					
					if($hash_upper == $sign)
					{
						$player_data = $this->player_model->get_player_data_by_player_id($deposit_data['player_id']);
						if( ! empty($player_data))
						{
							if($result == "000" || $result == "002")
							{
								if($paymend_gateway['payment_gateway_admin_verification'] == STATUS_INACTIVE){
									$status_change = STATUS_APPROVE;
								}else{
									$status_change = STATUS_PENDING;
								}

								$pData = array(
									'transaction_code' => $transaction_code,
									'order_no' => $billno,
									'status' => $status_change,
									'updated_by' => $player_data['username'],
								);
								
								$data = array(
									'msg_alert' => $this->lang->line('error_deposit_successful'),
									'msg_icon' => 1,
								);
								if($status_change == STATUS_APPROVE){
								    if(!empty($deposit_data['promotion_id'])){
										$PromotionDepositPending = $this->promotion_model->deposit_promotion_on_pending($deposit_data);
										if(!empty($PromotionDepositPending)){
											$member_total_wallet = $this->get_member_latest_wallet($player_data);
											$promotion_response = $this->promotion_model->deposit_promotion_approve_decision($PromotionDepositPending,$member_total_wallet);
											$this->promotion_model->update_deposit_promotion_status($deposit_data, $promotion_response['code']);
											if($promotion_response['status'] == EXIT_SUCCESS){
												$this->promotion_model->update_player_promotion_after_deposit($PromotionDepositPending,$member_total_wallet,$PromotionDepositPending['deposit_amount'],1);
												if($PromotionDepositPending['reward_on_apply'] == STATUS_ACTIVE){
													$this->player_model->point_transfer($player_data, $PromotionDepositPending['reward_amount'], $player_data['username']);
													$this->general_model->insert_cash_transfer_report($player_data, $PromotionDepositPending['reward_amount'], $player_data['username'], TRANSFER_PROMOTION);
													$this->promotion_model->update_player_promotion_reward_claim($PromotionDepositPending,$member_total_wallet);
												}
											}
										}
									}
									$this->player_model->point_transfer($player_data, $deposit_data['amount'], $player_data['username']);
									switch($deposit_data['deposit_type'])
									{
										case DEPOSIT_CREDIT_CARD: $transfer_type = TRANSFER_CREDIT_CARD_DEPOSIT; break;
										case DEPOSIT_HYPERMART: $transfer_type = TRANSFER_HYPERMART_DEPOSIT; break;
										default: $transfer_type = TRANSFER_PG_DEPOSIT; break;
									}
									$this->general_model->insert_cash_transfer_report($player_data, $deposit_data['amount'], $player_data['username'], $transfer_type);	
								}						
							}
							else
							{
								$pData = array(
									'transaction_code' => $transaction_code,
									'status' => STATUS_CANCEL,
									'updated_by' => $player_data['username'],
								);
								
								$data = array(
									'msg_alert' => $this->lang->line('error_deposit_failed'),
									'msg_icon' => 2,
								);
							}
							$this->deposit_model->update_payment_gateway_deposit_status($pData);
							echo '<xml><billno>'.$billno.'</billno><status>OK</status></xml>';
						}
					}
				}
			}
		}else{
			if($this->session->userdata('pg_deposit_id'))
			{
				if($this->session->userdata('pg_deposit_id'))
				{
					$data['player'] = $this->player_model->get_player_data($this->session->userdata('username'));
					$data['setting'] = $this->miscellaneous_model->get_miscellaneous();
					$data['deposit'] = $this->deposit_model->get_payment_gateway_pending_deposit_data($this->session->userdata('pg_deposit_id'));
					$paymend_gateway = $this->payment_gateway_model->get_payment_gateway_data($data['deposit']['payment_gateway_id']);
					$data['paymend_gateway'] =  json_decode($paymend_gateway['api_data'],true);
					$this->session->unset_userdata('pg_deposit_id');
					$this->load->view('gateway/fastspay_pg_view', $data);
				}
				else
				{
					redirect('home');
				}
			}
		}
	}
	
	public function eeziepay($method = NULL){
		if($method == 'redirect'){
			//insert log
			$payment_gateway_code = "EEZIEPAY";
			$prove_data['payment_gateway_code'] = $payment_gateway_code;
			$prove_data['input_get'] = json_encode($_GET);
			$prove_data['input_post'] = json_encode($_POST);
			$prove_data['input_request'] = json_encode($_REQUEST);
			$prove_data['input_json'] = file_get_contents("php://input");
			$prove_data['response_time'] = time();
			$prove_data['response_time'] = time();
			$prove_data['ip_address'] = $this->input->ip_address();
			$prove_data['input_type'] = 1;
			$this->db->insert('payment_gateway_log',$prove_data);


			$service_version = trim($_POST['service_version']);
			$sign = trim($_POST['sign']);
			$billno  = trim($_POST['billno']);
			$transaction_code  = trim($_POST['partner_orderid']);
			$currency = trim($_POST['currency']);
			$request_amount  = trim($_POST['request_amount']);
			$receive_amount = trim($_POST['receive_amount']);
			$fee  = trim($_POST['fee']);
			$result = trim($_POST['status']);

			$deposit_data = $this->deposit_model->get_deposit_data_by_transaction_code($transaction_code);
			if(!empty($deposit_data))
			{
				$paymend_gateway = $this->payment_gateway_model->get_payment_gateway_data($deposit_data['payment_gateway_id']);
				if(!empty($paymend_gateway)){
					$paymend_gateway_data =  json_decode($paymend_gateway['api_data'],true);
					$signature_string = "service_version=".$service_version."&billno=".$billno."&partner_orderid=".$transaction_code."&currency=".$currency."&request_amount=".$request_amount."&receive_amount=".$receive_amount."&fee=".$fee."&status=".$result."&key=".$paymend_gateway_data['key'];
					$hash = sha1($signature_string);
					$hash_upper = strtoupper($hash);
					if($hash_upper == $sign)
					{
						$player_data = $this->player_model->get_player_data_by_player_id($deposit_data['player_id']);
						if( ! empty($player_data))
						{
							if($result == "000" || $result == "002")
							{
								$data = array(
									'msg_alert' => $this->lang->line('error_deposit_successful'),
									'msg_icon' => 1,
								);	
							}
							else
							{	
								$data = array(
									'msg_alert' => $this->lang->line('error_deposit_failed'),
									'msg_icon' => 2,
								);
							}
							
							$this->session->set_userdata($data);
						}		
					}
					redirect('home');
				}
			}
		}else if($method == 'callback'){
			//insert log
			$payment_gateway_code = "EEZIEPAY";
			$prove_data['payment_gateway_code'] = $payment_gateway_code;
			$prove_data['input_get'] = json_encode($_GET);
			$prove_data['input_post'] = json_encode($_POST);
			$prove_data['input_request'] = json_encode($_REQUEST);
			$prove_data['input_json'] = file_get_contents("php://input");
			$prove_data['response_time'] = time();
			$prove_data['response_time'] = time();
			$prove_data['ip_address'] = $this->input->ip_address();
			$prove_data['input_type'] = 2;
			$this->db->insert('payment_gateway_log',$prove_data);

			$service_version = trim($_POST['service_version']);
			$sign = trim($_POST['sign']);
			$billno  = trim($_POST['billno']);
			$transaction_code  = trim($_POST['partner_orderid']);
			$currency = trim($_POST['currency']);
			$request_amount  = trim($_POST['request_amount']);
			$receive_amount = trim($_POST['receive_amount']);
			$fee  = trim($_POST['fee']);
			$result = trim($_POST['status']);


			$deposit_data = $this->deposit_model->get_deposit_data_by_transaction_code($transaction_code);
			if(!empty($deposit_data) && $deposit_data['status'] == STATUS_ON_PENDING)
			{
				$paymend_gateway = $this->payment_gateway_model->get_payment_gateway_data($deposit_data['payment_gateway_id']);
				if(!empty($paymend_gateway)){
					$paymend_gateway_data =  json_decode($paymend_gateway['api_data'],true);
					$signature_string = "service_version=".$service_version."&billno=".$billno."&partner_orderid=".$transaction_code."&currency=".$currency."&request_amount=".$request_amount."&receive_amount=".$receive_amount."&fee=".$fee."&status=".$result."&key=".$paymend_gateway_data['key'];
					$hash = sha1($signature_string);
					$hash_upper = strtoupper($hash);
					
					if($hash_upper == $sign)
					{
						$player_data = $this->player_model->get_player_data_by_player_id($deposit_data['player_id']);
						if( ! empty($player_data))
						{
							if($result == "000" || $result == "002")
							{
								if($paymend_gateway['payment_gateway_admin_verification'] == STATUS_INACTIVE){
									$status_change = STATUS_APPROVE;
								}else{
									$status_change = STATUS_PENDING;
								}

								$pData = array(
									'transaction_code' => $transaction_code,
									'order_no' => $billno,
									'status' => $status_change,
									'updated_by' => $player_data['username'],
								);
								
								$data = array(
									'msg_alert' => $this->lang->line('error_deposit_successful'),
									'msg_icon' => 1,
								);
								if($status_change == STATUS_APPROVE){
									$this->player_model->point_transfer($player_data, $deposit_data['amount'], $player_data['username']);
									switch($deposit_data['deposit_type'])
									{
										case DEPOSIT_CREDIT_CARD: $transfer_type = TRANSFER_CREDIT_CARD_DEPOSIT; break;
										case DEPOSIT_HYPERMART: $transfer_type = TRANSFER_HYPERMART_DEPOSIT; break;
										default: $transfer_type = TRANSFER_PG_DEPOSIT; break;
									}
									$this->general_model->insert_cash_transfer_report($player_data, $deposit_data['amount'], $player_data['username'], $transfer_type);	
								}						
							}
							else
							{
								$pData = array(
									'transaction_code' => $transaction_code,
									'status' => STATUS_CANCEL,
									'updated_by' => $player_data['username'],
								);
								
								$data = array(
									'msg_alert' => $this->lang->line('error_deposit_failed'),
									'msg_icon' => 2,
								);
							}
							$this->deposit_model->update_payment_gateway_deposit_status($pData);
							echo '<xml><billno>'.$billno.'</billno><status>OK</status></xml>';
						}
					}
				}
			}
		}else{
			if($this->session->userdata('pg_deposit_id'))
			{
				if($this->session->userdata('pg_deposit_id'))
				{
					$data['player'] = $this->player_model->get_player_data($this->session->userdata('username'));
					$data['setting'] = $this->miscellaneous_model->get_miscellaneous();
					$data['deposit'] = $this->deposit_model->get_payment_gateway_pending_deposit_data($this->session->userdata('pg_deposit_id'));
					$paymend_gateway = $this->payment_gateway_model->get_payment_gateway_data($data['deposit']['payment_gateway_id']);
					$data['paymend_gateway'] =  json_decode($paymend_gateway['api_data'],true);
					$this->session->unset_userdata('pg_deposit_id');

					//insert log
					$log = array(
						'payment_gateway_code' 	=> 'EEZIEPAY',
						'method' 				=> 'Deposit',
						'error_code' 			=> 0,
						'error_msg' 			=> 'Success',
						'log_date' 				=> time(),
						'input' 				=> json_encode($data),
						'output' 				=> ''
					);
					$this->db->insert('payment_gateway_api_logs',$log);
					
					$this->load->view('gateway/eeziepay_pg_view', $data);
				}
				else
				{
					redirect('home');
				}
			}
		}
	}
	
	public function get_member_latest_wallet($player_data = NULL){
		$is_balance_valid = TRUE;
		$total_amount = 0;
		if( ! empty($player_data))
		{
			if( ! empty($player_data['last_in_game']))
			{
				$api_data = $this->api_model->get_api_data(SYSTEM_API_AGENT_ID);
				if(!empty($api_data))
				{
					$total_amount = $player_data['points'];
					$account_data = $this->player_model->get_player_game_account_data($player_data['last_in_game'], $player_data['player_id']);
					if( ! empty($account_data))
					{
						$device = PLATFORM_WEB;
						if($this->agent->is_mobile()) 
						{
							$device = PLATFORM_MOBILE_WEB;
						}
						
						$syslang = ((get_language_id($this->get_selected_language()) == LANG_ZH_CN OR get_language_id($this->get_selected_language()) == LANG_ZH_HK OR get_language_id($this->get_selected_language()) == LANG_ZH_TW) ? LANG_ZH_CN : LANG_EN);
				
						$url = HUB_URL; 
						$signature = md5($api_data['agent_id'] . $player_data['last_in_game'] . $account_data['username'] . $api_data['secret_key']);
						
						$param_array = array(
							"method" => 'GetBalance',
							"agent_id" => $api_data['agent_id'],
							"syslang" => $syslang,
							"device" => $device,
							"provider_code" => $player_data['last_in_game'],
							"player_id" => $account_data['player_id'], 
							"game_id" => $account_data['game_id'],
							"username" => $account_data['username'],
							"password" => $account_data['password'],
							"signature" => $signature,
						);
										
						$response = $this->curl_json($url, $param_array);
						$result_array = json_decode($response, TRUE);
						
						if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
						{
							$total_amount = ($total_amount + $result_array['result']);
						}else{
							$is_balance_valid = FALSE;
						}
					}
				}else{
					$is_balance_valid = FALSE;
				}
			}else{
				$total_amount = $player_data['points'];
			}
		}else{
			$is_balance_valid = FALSE;
		}
		$result = array(
			'balance_valid' => $is_balance_valid,
			'balance_amount' => $total_amount,
		);
		return $result;
	}

	public function truePay($method = null, $id = null){
		
		if($method == 'deposit'){
			if($id == null) {
				$data['msg'] = 'Transaction Time Out, Please try again.';
				$this->load->view('gateway/error',$data);
			} else {
				$deposit_data = $this->deposit_model->get_payment_gateway_pending_deposit_data($id);
				if(!empty($deposit_data)) {
					$player = $this->player_model->get_player_data_by_player_id($deposit_data['player_id']);
					if(!empty($player)) {
						$paymend_gateway = $this->payment_gateway_model->get_payment_gateway_data($deposit_data['payment_gateway_id']);
						if(!empty($paymend_gateway)){
							$gateway = json_decode($paymend_gateway['api_data'],true);
							$time = time();
							
							$form = array(
								'mCode' => $gateway['mCode'],
								'token' => md5($gateway['mCode']-$gateway['secretKey']-$gateway['getDPLink']-$time),
								'ts' => $time,
								'bCode' => $deposit_data['payment_gateway_bank'],
								'amt' => (isset($deposit_data['amount_rate'])) ? str_replace('.00','',(string)$deposit_data['amount_rate']) : '',
								'user' => (isset($player['username'])) ? $player['username'] : '',
								'trxid' => (isset($deposit_data['transaction_code'])) ? $deposit_data['transaction_code'] : '',
								'notifyUrl' => $gateway['notify_url'],
								'webUrl' => (isset($gateway['return_url'])) ? $gateway['return_url'] : ''								
							);
							
							$data = array(
								'mCode' => $form['mCode'],
								'token' => $form['token'],
								'ts' => $form['ts'],
								'bCode' => $form['bCode'],
								'amt' => $form['amt'],
								'user' => $form['user'],
								'trxid' => $form['trxid'],
								'notifyUrl' => $form['notify_url'],
								'webUrl' => $form['return_url'],
							);
							$jsonQuery = json_encode($data,JSON_UNESCAPED_SLASHES);
							$curlHandle = curl_init($gateway['APIUrl']);
							curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'POST');
							curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $jsonQuery);
							curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($curlHandle, CURLOPT_HTTPHEADER,
							array(
									'Content-Type: application/x-www-form-urlencoded'
								)
							);

							$response 	= curl_exec($curlHandle);
							// $info 		= curl_getinfo($curlHandle);
							curl_close($curlHandle);
							$arrResponse = json_decode($response,true);
							
							###insert log###
							$data['url'] = $gateway['APIUrl'];
							$data['key'] = $form['key'];
							$payment_gateway_code = 'TRUEPAY';
							$log = array(
								'payment_gateway_code' 	=> $payment_gateway_code,
								'method' 				=> 'Deposit',
								'error_code' 			=> 0,
								'error_msg' 			=> 'Success',
								'log_date' 				=> time(),
								'input' 				=> json_encode($data),
								'output' 				=> $query
							);
							$this->db->insert('payment_gateway_api_logs',$log);
							unset($paymend_gateway,$gateway,$form,$log);
							################
							unset($data);
							$data['url'] = $arrResponse['url'];
							$this->load->view('gateway/true_pay_view', $data);
						} else {
							$data['msg'] = 'Service Temporarily Unavailable.';
							$this->load->view('gateway/error',$data);
						}
						unset_array($player);
					} else {
						$data['msg'] = 'Transaction Time Out, Please try again.';
						$this->load->view('gateway/error',$data);
					}
					unset_array($deposit_data);
				} else {
					$data['msg'] = 'Transaction Time Out, Please try again.';
					$this->load->view('gateway/error',$data);
				}
			}
		} elseif ($method == 'callback'){
			$strReceive = file_get_contents("php://input");
			//$strReceive = '{"client_id":"20231005211213676","amount":"500.00","bill_number":"D7316980344998142","status":"\u5df2\u5b8c\u6210","timestamp":"2023-10-23 09:54:02","sign":"4f8ffcbc8314c5a5b91522a1d9f1b4ee"}';
			###insert log###
			$payment_gateway_code = 'TRUEPAY';
			$prove_data['payment_gateway_code'] = $payment_gateway_code;
			$prove_data['input_get'] = json_encode($_GET);
			$prove_data['input_post'] = json_encode($_POST);
			$prove_data['input_request'] = json_encode($_REQUEST);
			$prove_data['input_json'] = file_get_contents("php://input");
			$prove_data['response_time'] = time();
			$prove_data['ip_address'] = $this->input->ip_address();
			$prove_data['input_type'] = 2;
			$this->db->insert('payment_gateway_log',$prove_data);
			unset_array($prove_data);
			################
			
			$arrReceive = json_decode($strReceive,true);
			$client_id = trim($arrReceive['id']);
			$status = trim($arrReceive['status']);
			$amount = trim($arrReceive['amount']);
			$fee = trim($arrReceive['fee']);
			$message = trim($arrReceive['message']);
			$token = trim($arrReceive['token']);
			$timestamp = trim($arrReceive['ts']);
						
			$deposit_data = $this->deposit_model->get_deposit_data_by_transaction_code($client_id);
			
			if(!empty($deposit_data) && ($deposit_data['status'] == STATUS_ON_PENDING || $deposit_data['status'] == STATUS_CANCEL)){
				$paymend_gateway = $this->payment_gateway_model->get_payment_gateway_data($deposit_data['payment_gateway_id']);
				if(!empty($paymend_gateway)){
					$paymend_gateway_data =  json_decode($paymend_gateway['api_data'],true);
					
					$player_data = $this->player_model->get_player_data_by_player_id($deposit_data['player_id']);
					if( ! empty($player_data)){
						if($status == STATUS_APPROVE)
						{
							if($paymend_gateway['payment_gateway_admin_verification'] == STATUS_INACTIVE){
								$status_change = STATUS_APPROVE;
							}
							else{
								$status_change = STATUS_ON_PENDING;
							}
							
							$pData = array(
								'transaction_code' => $client_id,
								'status' => $status_change,
								'updated_by' => $player_data['username']
							);
							
							$data = array(
								'msg_alert' => $this->lang->line('error_deposit_successful'),
								'msg_icon' => 1,
							);
							if($status_change == STATUS_APPROVE){
								$this->player_model->point_transfer($player_data, $deposit_data['amount'], $player_data['username']);
								$transfer_type = TRANSFER_PG_DEPOSIT;
								$this->general_model->insert_cash_transfer_report($player_data, $deposit_data['amount'], $player_data['username'], $transfer_type);
								###PROMOTION###
								if(!empty($deposit_data['promotion_id'])){
									$promotionDepositPending = $this->promotion_model->deposit_promotion_on_pending($deposit_data);
									if(!empty($promotionDepositPending)){
										$member_total_wallet 	= $this->get_member_latest_wallet($player_data);
										$promotion_response 	= $this->promotion_model->deposit_promotion_approve_decision($promotionDepositPending,$member_total_wallet);
										$this->promotion_model->update_deposit_promotion_status($deposit_data, $promotion_response['code']);
										if($promotion_response['status'] == EXIT_SUCCESS){
											$this->promotion_model->update_player_promotion_after_deposit($promotionDepositPending,$member_total_wallet,$promotionDepositPending['deposit_amount'],1);
											if($promotionDepositPending['reward_on_apply'] == STATUS_ACTIVE){
												$this->player_model->point_transfer($player_data, $promotionDepositPending['reward_amount'], $player_data['username']);
												$this->general_model->insert_cash_transfer_report($player_data, $promotionDepositPending['reward_amount'], $player_data['username'], TRANSFER_PROMOTION);
												$this->promotion_model->update_player_promotion_reward_claim($promotionDepositPending,$member_total_wallet);
											}
										}
										unset($promotionDepositPending,$member_total_wallet);
									}
								}
								###############
							}
						} 
						else 
						{
							$pData = array(
								'transaction_code' 	=> $client_id,
								'status' 			=> STATUS_CANCEL,
								'updated_by' 		=> $player_data['username']
							);
							$data = array(
								'msg_alert' 		=> $this->lang->line('error_deposit_failed'),
								'msg_icon' 			=> 2
							);
						}
						$this->deposit_model->update_payment_gateway_deposit_status($pData);
						echo 'OK';
						unset($player_data,$pData,$data);
					}
					unset($paymend_gateway_data,$paymend_gateway);
				}
				unset_array($deposit_data);
			} else {
				echo 'No Post';
			}
			
		} else { # redirect
			redirect('home');
		}
	}
}