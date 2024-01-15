<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	public function index() 
	{
		
		$post = file_get_contents('php://input');
		
		if( ! empty($post))
		{
			$output = array(
				'errorCode' => ERROR_SYSTEM_ERROR, 
				'errorMessage' => '',
			);
				
			$arr = json_decode($post, TRUE);
			
			//Database update
			$this->db->trans_start();
			$this->api_model->insert_api_log($arr);
			$this->db->trans_complete();
			
			$sys_data = $this->miscellaneous_model->get_miscellaneous();
			
			if($sys_data['is_maintenance'] == STATUS_NO)
			{
				//Initial data
				$method = (isset($arr['method']) ? trim($arr['method']) : '');
				$agent_id = (isset($arr['agent_id']) ? trim($arr['agent_id']) : '');
				$signature = (isset($arr['signature']) ? trim($arr['signature']) : '');
				$syslang = (isset($arr['syslang']) ? trim($arr['syslang']) : LANG_EN);
				$device = (isset($arr['device']) ? trim($arr['device']) : PLATFORM_WEB);
				$payment_gateway_code = (isset($arr['payment_gateway_code']) ? strtoupper(trim($arr['payment_gateway_code'])) : '');
				$payment_gateway_type_code = (isset($arr['payment_gateway_type_code']) ? trim($arr['payment_gateway_type_code']) : '');
				$payment_gateway_bank = (isset($arr['payment_gateway_bank']) ? trim($arr['payment_gateway_bank']) : '');
				$player_id = (isset($arr['player_id']) ? trim($arr['player_id']) : '');
				$username = (isset($arr['username']) ? trim($arr['username']) : '');
				$full_name = (isset($arr['full_name']) ? trim($arr['full_name']) : '');
				$email = (isset($arr['email']) ? trim($arr['email']) : '');
				$mobile = (isset($arr['mobile']) ? trim($arr['mobile']) : '');
				$order_id = (isset($arr['order_id']) ? trim($arr['order_id']) : '');
				$transaction_id = (isset($arr['transaction_id']) ? trim($arr['transaction_id']) : '');
				$bank_account_name = (isset($arr['bank_account_name']) ? trim($arr['bank_account_name']) : '');
				$bank_account_number = (isset($arr['bank_account_number']) ? trim($arr['bank_account_number']) : '');
				$currency = (isset($arr['currency']) ? trim($arr['currency']) : '');
				$amount = (isset($arr['amount']) ? trim($arr['amount']) : '');
				
				$incoming_ip = $this->input->ip_address();
				$whitelist_method = array('Payout');
				
				$post_data = array(
					'syslang' => $syslang, 
					'device' => $device, 
					'payment_gateway_code' => $payment_gateway_code, 
					'payment_gateway_type_code' => $payment_gateway_type_code, 
					'payment_gateway_bank' => $payment_gateway_bank,
					'player_id' => $player_id,
					'username' => $username, 
					'full_name' => $full_name, 
					'email' => $email, 
					'mobile' => $mobile, 
					'order_id' => $order_id,
					'transaction_id' => $transaction_id,
					'bank_account_name' => $bank_account_name,
					'bank_account_number' => $bank_account_number,
					'currency' => $currency,
					'amount' => bcdiv($amount, 1, 2),
				);
				
				$this->lang->load('general', get_language($syslang));
				
				//Verify agent
				if( ! empty($agent_id))
				{
					$data = $this->api_model->get_api_data($agent_id);
					
					if( ! empty($data))
					{
						//Verify IP
						$whitelist_ip = explode(',', $data['gateway_whitelist_ip']);
						
						if(in_array($incoming_ip, $whitelist_ip) OR $data['gateway_whitelist_ip'] == '*')
						{
							//Verify method
							if( ! empty($method))
							{
								if(in_array($method, $whitelist_method))
								{
									//Verify signature
									if( ! empty($signature))
									{
										$verify_sign = md5($data['agent_id'] . $payment_gateway_code . $post_data['username'] . $data['secret_key']);
										
										if($signature == $verify_sign)
										{
											//Verify game provider
											if( ! empty($payment_gateway_code))
											{
												$gateway_data = $this->gateway_model->get_gateway_data($payment_gateway_code, $payment_gateway_type_code);
												
												if( ! empty($gateway_data))
												{
													//Verify param
													if(empty($post_data['username']))
													{
														$output['errorCode'] = ERROR_USERNAME_EMPTY;
														$output['errorMessage'] = $this->lang->line('error_username_empty');
													}
													else if( ! preg_match('/^[a-z0-9]{4,20}$/', $post_data['username']))
													{
														$output['errorCode'] = ERROR_USERNAME_INCORRECT;
														$output['errorMessage'] = $this->lang->line('error_username_incorrect');
													}
													else if($method == 'Payout' && (empty($post_data['amount']) || $post_data['amount'] == 0))
													{
														$output['errorCode'] = ERROR_AMOUNT_EMPTY;
														$output['errorMessage'] = $this->lang->line('error_amount_empty');
													}
													else if($method == 'Payout' && is_numeric($post_data['amount']) == FALSE)
													{
														$output['errorCode'] = ERROR_AMOUNT_INCORRECT;
														$output['errorMessage'] = $this->lang->line('error_amount_incorrect');
													}
													else if($method == 'Payout' && empty($post_data['order_id']))
													{
														$output['errorCode'] = ERROR_ORDER_ID_EMPTY;
														$output['errorMessage'] = $this->lang->line('error_order_id_empty');
													}
													else if($method == 'Payout' && empty($post_data['transaction_id']))
													{
														$output['errorCode'] = ERROR_TRANSACTION_ID_EMPTY;
														$output['errorMessage'] = $this->lang->line('error_transaction_id_empty');
													}
													else
													{
														switch($payment_gateway_code)
														{
															case 'FASTSPAY': $output = $this->fastspay_connect($method, $sys_data, $gateway_data['api_data'], $gateway_data['bank_data'], $post_data); break;
															case 'GSPAY2': $output = $this->gspay2_connect($method, $sys_data, $gateway_data['api_data'], $gateway_data['bank_data'], $post_data); break;
															case 'FUZEPAY': $output = $this->fuzepay_connect($method, $sys_data, $gateway_data['api_data'], $gateway_data['bank_data'], $post_data); break;
															case 'WRAMPAY': $output = $this->wrampay_connect($method, $sys_data, $gateway_data['api_data'], $gateway_data['bank_data'], $post_data); break;
															case 'TRUEPAY': $output = $this->truepay_connect($method, $sys_data, $gateway_data['api_data'], $gateway_data['bank_data'], $post_data); break;
														}
													}	
												}
												else
												{
													$output['errorCode'] = ERROR_PROVIDER_CODE_INCORRECT;
													$output['errorMessage'] = $this->lang->line('error_provider_code_incorrect');
												}
											}
											else
											{
												$output['errorCode'] = ERROR_PROVIDER_CODE_EMPTY;
												$output['errorMessage'] = $this->lang->line('error_provider_code_empty');
											}
										}
										else
										{
											$output['errorCode'] = ERROR_SIGNATURE_INCORRECT;
											$output['errorMessage'] = $this->lang->line('error_signature_incorrect');
										}
									}
									else
									{
										$output['errorCode'] = ERROR_SIGNATURE_EMPTY;
										$output['errorMessage'] = $this->lang->line('error_signature_empty');
									}
								}
								else
								{
									$output['errorCode'] = ERROR_METHOD_INCORRECT;
									$output['errorMessage'] = $this->lang->line('error_method_incorrect');
								}
							}
							else
							{
								$output['errorCode'] = ERROR_METHOD_EMPTY;
								$output['errorMessage'] = $this->lang->line('error_method_empty');
							}
						}
						else
						{
							$output['errorCode'] = ERROR_API_ACCESS_DENIED;
							$output['errorMessage'] = $this->lang->line('error_api_access_denied');
						}
					}
					else
					{
						$output['errorCode'] = ERROR_AGENT_ID_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_agent_id_not_found');
					}
				}
				else
				{
					$output['errorCode'] = ERROR_AGENT_ID_EMPTY;
					$output['errorMessage'] = $this->lang->line('error_agent_id_empty');
				}	
			}
			else
			{
				$output['errorCode'] = ERROR_SYSTEM_MAINTENANCE;
				$output['errorMessage'] = $this->lang->line('error_system_maintenance');
			}
			
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($output))
					->_display();
					
			exit();
		}
		else
		{
			echo show_404();
		}
	}

	private function fastspay_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $bank_data = NULL, $post_data = NULL){
		$arr = json_decode($api_data, TRUE);
		$arr_bank = json_decode($bank_data, TRUE);
		
		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);

		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = $post_data['order_id'];
		$paymentID = "";
		$curl_array = array();
		$param_array = array();
		$result_array = array();

		if($method == 'Payout'){
			$param_array = array(
				'service_version' => $arr['service_version'],
				'partner_code' => $arr['partner_code'],
				'partner_orderid' => $requestOrderID,
				'member_id' => $post_data['player_id'],
				'currency' => $post_data['currency'],
				'amount' => str_replace('.','',(string)$post_data['amount']),
				'account_name' => $post_data['bank_account_name'],
				'account_number' => $post_data['bank_account_number'],
				'bank_province' => "HCM",
				'bank_city' => "HCM",
				'bank_branch' => "HCM",
				'bank_code' =>  $post_data['payment_gateway_bank'].".".$arr['BankCode'],
				'notify_url' => base_url('callback/fastspay/payout'),
			);
			$plain_string = urldecode(http_build_query($param_array))."&key=".$arr['key'];
			$param_array['sign'] = strtoupper(sha1($plain_string));
			$response = $this->curl_post($url,$param_array);
			$curl_array = $response['curl'];
			if($response['code'] == '0')
			{
				$xml = simplexml_load_string($response['data']);
				$json = json_encode($xml);
				$result_array = json_decode($json, TRUE);
				if(isset($result_array['status']) && $result_array['status'] == '001')
				{
					$paymentID = $result_array['billno'];
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
			}
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
			$output['paymentID'] = $paymentID;
		}


		//Database update
		$this->db->trans_start();
		$this->gateway_model->insert_api_log($post_data['payment_gateway_code'], $method, $output, $param_array, $result_array,$curl_array);
		$this->db->trans_complete();
		return $output;
	}

	private function gspay2_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $bank_data = NULL, $post_data = NULL){
		$arr = json_decode($api_data, TRUE);
		$arr_bank = json_decode($bank_data, TRUE);
		
		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);

		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "W".str_pad($post_data['transaction_id'], 14, '0', STR_PAD_LEFT);
		$curl_array = array();
		$param_array = array();
		$result_array = array();
		$paymentID = "";
		if($method == 'Payout'){
			$plain_text = $arr['OperatorSecretKey'].$requestOrderIDAlias.$post_data['amount'].$post_data['bank_account_number'].$arr['OperatorSecretKey'];
			$hash = md5($plain_text);

			$param_array = array(
				'transaction_id' => $requestOrderIDAlias,
				'account_name' => $post_data['bank_account_name'],
				'account_number' => $post_data['bank_account_number'],
				'amount' => $post_data['amount'],
				'trx_description' => $requestOrderID,
				'bank_target' => $post_data['payment_gateway_bank'],
				'signature' => $hash,
			);

			$response = $this->curl_json($url,$param_array);
			$curl_array = $response['curl'];
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				if(isset($result_array['code']) && $result_array['code'] == '200')
				{
					$paymentID = "";
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}else{
					$output['errorMessage'] = $result_array['message'];
				}
			}
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
			$output['paymentID'] = $paymentID;
		}

		//Database update
		$this->db->trans_start();
		$this->gateway_model->insert_api_log($post_data['payment_gateway_code'], $method, $output, $param_array, $result_array,$curl_array);
		$this->db->trans_complete();
		return $output;
	}
	
	public function fuzepay_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $bank_data = NULL, $post_data = NULL){
		//echo"check";exit;
	    $arr = json_decode($api_data, TRUE);
		$arr_bank = json_decode($bank_data, TRUE);
		
		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);

		$url = $arr['APIUrl']."/Applying";
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = $post_data['order_id'];
		
		$curl_array = array();
		$param_array = array();
		$result_array = array();
		$paymentID = "";
		
		
		if($method == 'Payout'){
			$param_array = array(
				'HashKey' => $arr['HashKey'],
				'HashIV' => $arr['HashIV'],
				'Password' => $arr['Password'],
				'BankName'  => $post_data['payment_gateway_bank'],
				'BankAccount' => $post_data['bank_account_number'],
				'AccountName' => $post_data['bank_account_name'],
				'Withdraw' => bcdiv($post_data['amount'],1,0),
				'Remark' => $requestOrderIDAlias,
			);
			
			$concate = $param_array['HashKey']."&".$arr['ValidateKey']."&".$param_array['BankAccount'];
    		$hash = md5($concate);
            $param_array['Sign'] = $hash;
			$response = $this->curl_post($url,$param_array);
			$curl_array = $response['curl'];
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0')
				{ 
					$paymentID = $result_array['TradingNumber'];
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}else{
					$output['errorMessage'] = $result_array['Message'];
				}
			}
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
			$output['paymentID'] = $paymentID;
		}

		//Database update
		$this->db->trans_start();
		$this->gateway_model->insert_api_log($post_data['payment_gateway_code'], $method, $output, $param_array, $result_array,$curl_array);
		$this->db->trans_complete();
		return $output;
	}		
	
	public function wrampay_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $bank_data = NULL, $post_data = NULL){
		
		$arr = json_decode($api_data, TRUE);
		$arr_bank = json_decode($bank_data, TRUE);
		
		#Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		$url = $arr['APIUrl']."/Applying";
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = $post_data['order_id'];
		
		$curl_array = array();
		$param_array = array();
		$result_array = array();

		$paymentID = "";
		
		if($method == 'Payout'){
			$param_array = array(
				'HashKey' => $arr['HashKey'],
				'HashIV' => $arr['HashIV'],
				'Password' => $arr['Password'],
				'MerchantNumber' => $requestOrderIDAlias,
				'BankName'  => $post_data['payment_gateway_bank'],
				'BankAccount' => $post_data['bank_account_number'],
				'AccountName' => $post_data['bank_account_name'],
				'Withdraw' => bcdiv($post_data['amount'],1,0),
				'Remark' => $requestOrderIDAlias,
			);

			$concate = $param_array['HashKey']."&".$arr['ValidateKey']."&".$param_array['BankAccount'];

    		$hash = md5($concate);

            $param_array['Sign'] = $hash;

			$response = $this->curl_post($url,$param_array);

			$curl_array = $response['curl'];

			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);

				if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0')
				{ 
					$paymentID = $result_array['TradingNumber'];
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}else{
					$output['errorMessage'] = $result_array['Message'];
				}
			}
			$output['orderID'] = $requestOrderID;

			$output['orderIDAlias'] = $requestOrderIDAlias;

			$output['paymentID'] = $paymentID;
		}
		
		//Database update
		$this->db->trans_start();
		$this->gateway_model->insert_api_log($post_data['payment_gateway_code'], $method, $output, $param_array, $result_array,$curl_array);
		$this->db->trans_complete();
		return $output;
	}
	
	public function truepay_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $bank_data = NULL, $post_data = NULL){
		
		$arr = json_decode($api_data, TRUE);
		$arr_bank = json_decode($bank_data, TRUE);
		#Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = $post_data['order_id'];
		$curl_array = array();
		$param_array = array();
		$result_array = array();

		$paymentID = "";
		
		if($method == 'Payout'){
			$merchant_code = $arr['merchant_code'];
			$secret_key = $arr['secret_key'];
			$action_name = 'sPayout';
			$url .= '/a/' . $action_name;
			$ts = time();
			$param_array = array(
				'mCode' => $merchant_code,
				'token' => md5($merchant_code . '-' . $secret_key . '-'  . $action_name . '-' . $ts),
				'ts' => $ts,
				'bCode' => $post_data['payment_gateway_bank'],
				'trxid' => $requestOrderID,
				'user' => $post_data['username'],
				'amt' => $post_data['amount'],
				'bAccNo' => $post_data['bank_account_number'],
				'bAccName' => $post_data['bank_account_name'],
				'notifyUrl' => $arr['notify_url'],
			);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $param_array);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			$curl_array = ['error_no' => curl_errno($ch), 'error_desc' => curl_error($ch), 'info' => curl_getinfo($ch)];
			$result_array = json_decode($response, TRUE);

			if ($curl_array['info']['http_code'] == 200 && !empty($result_array)) {
				if ($result_array['status'] == 0) {
					$paymentID = $result_array['d']['trxid'];
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				} else {
					$output['errorMessage'] = $result_array['msg'];
				}
			}
			$output['orderID'] = $requestOrderID;

			$output['orderIDAlias'] = $requestOrderIDAlias;

			$output['paymentID'] = $paymentID;
		}
		
		//Database update
		$this->db->trans_start();
		$this->gateway_model->insert_api_log($post_data['payment_gateway_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		return $output;
	}
}
