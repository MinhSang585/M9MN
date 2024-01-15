<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('account_model', 'api_model', 'bank_model', 'general_model', 'miscellaneous_model', 'user_model'));
	}
	
	public function index() 
	{
		$post = file_get_contents('php://input');
		
		if( ! empty($post))
		{
			$output = array(
					'status' => ERROR_SYSTEM_ERROR, 
					'msg' => $this->lang->line('error_system_error'),
				);
				
			$arr = json_decode($post, TRUE);
			
			$this->api_model->insert_api_log($arr);
			
			$sys_data = $this->miscellaneous_model->get_miscellaneous();
			
			if($sys_data['is_maintenance'] == STATUS_NO)
			{
				//Initial data
				$method = (isset($arr['method']) ? trim($arr['method']) : '');
				$agentId = (isset($arr['agentId']) ? trim($arr['agentId']) : '');
				$signature = (isset($arr['signature']) ? trim($arr['signature']) : '');
				$syslang = (isset($arr['syslang']) ? trim($arr['syslang']) : LANG_EN);
				$device = (isset($arr['device']) ? trim($arr['device']) : '');
				$incoming_ip = $this->input->ip_address();
				$verify_method = FALSE;
				$verify_sign = '';
				$DBdata = array();
				
				switch($device)
				{
					case PLATFORM_WEB:
					case PLATFORM_MOBILE_WEB:
					case PLATFORM_APP_ANDROID:
					case PLATFORM_APP_IOS: break;
					default: $device = ''; break;
				}
				
				$this->lang->load('general', get_language($syslang));
				
				if( ! empty($agentId))
				{
					$data = $this->api_model->get_api_data($agentId);
					
					if( ! empty($data))
					{
						$whitelist_ip = explode(',', $data['fe_whitelist_ip']);
						
						if(in_array($incoming_ip, $whitelist_ip) OR $data['fe_whitelist_ip'] == '*')
						{
							if( ! empty($signature))
							{
								//Get signature
								if($method == 'MemberRegister')
								{
									$DBdata = array(
														'username' => (isset($arr['player']['username']) ? trim($arr['player']['username']) : ''),
														'password' => (isset($arr['player']['password']) ? trim($arr['player']['password']) : ''),
														'nickname' => (isset($arr['player']['nickname']) ? trim($arr['player']['nickname']) : ''),
														'mobile' => (isset($arr['player']['mobile']) ? trim($arr['player']['mobile']) : ''),
														'email' => (isset($arr['player']['email']) ? trim($arr['player']['email']) : ''),
														'wechat' => (isset($arr['player']['wechat']) ? trim($arr['player']['wechat']) : ''),
														'referrer' => (isset($arr['player']['referrer']) ? trim($arr['player']['referrer']) : '')
													);
													
									$verify_method = TRUE;				
								}
								else if($method == 'MemberRegisterDV')
								{
									$DBdata = array(
														'username' => (isset($arr['player']['username']) ? trim($arr['player']['username']) : ''),
														'password' => (isset($arr['player']['password']) ? trim($arr['player']['password']) : ''),
														'nickname' => (isset($arr['player']['nickname']) ? trim($arr['player']['nickname']) : '')
													);
													
									$verify_method = TRUE;				
								}
								else if($method == 'GetBalance' OR $method == 'GetBalanceDV' OR $method == 'MemberLogout' OR $method == 'UpdatePlayerType')
								{
									$DBdata = array(
														'username' => (isset($arr['username']) ? trim($arr['username']) : '')
													);
													
									$verify_method = TRUE;		
								}
								else if($method == 'CreditBalance' OR $method == 'DebitBalance')
								{
									$DBdata = array(
														'username' => (isset($arr['username']) ? trim($arr['username']) : ''),
														'amount' => (isset($arr['amount']) ? trim($arr['amount']) : '')
													);
													
									$verify_method = TRUE;				
								}
								else if($method == 'MemberLogin')
								{
									$DBdata = array(
														'username' => (isset($arr['username']) ? trim($arr['username']) : ''),
														'password' => (isset($arr['password']) ? trim($arr['password']) : '')
													);
													
									$verify_method = TRUE;
								}
								else if($method == 'VerifyToken')
								{
									$DBdata = array(
														'username' => (isset($arr['username']) ? trim($arr['username']) : ''),
														'token' => (isset($arr['token']) ? trim($arr['token']) : '')
													);
													
									$verify_method = TRUE;
								}
								else if($method == 'GetBankList')
								{
									$verify_method = TRUE;		
								}
								else if($method == 'GetGameList')
								{
									$DBdata = array(
														'game_type_code' => (isset($arr['game_type_code']) ? trim($arr['game_type_code']) : '')
													);
													
									$verify_method = TRUE;
								}
								else if($method == 'GetSubGameList')
								{
									$DBdata = array(
														'provider_code' => (isset($arr['provider_code']) ? strtoupper(trim($arr['provider_code'])) : ''),
														'game_type_code' => (isset($arr['game_type_code']) ? trim($arr['game_type_code']) : '')
													);
													
									$verify_method = TRUE;
								}
								else if($method == 'LoginGame')
								{
									$DBdata = array(
														'username' => (isset($arr['username']) ? trim($arr['username']) : ''),
														'provider_code' => (isset($arr['provider_code']) ? strtoupper(trim($arr['provider_code'])) : ''),
														'game_type_code' => (isset($arr['game_type_code']) ? trim($arr['game_type_code']) : ''),
														'game_code' => (isset($arr['game_code']) ? trim($arr['game_code']) : '')
													);
													
									$verify_method = TRUE;
								}
								
								if($verify_method == TRUE)
								{
									//Verify signature
									$verify_sign = md5($data['agent_id'] . implode('', $DBdata) . $data['secret_key']);
									
									if($signature == $verify_sign)
									{
										switch($method)
										{
											case 'MemberRegister': $output = $this->create_player($DBdata, $data['agent_id'], $device); break;
											case 'GetBalance': $output = $this->get_balance($DBdata, $data['agent_id']); break;
											case 'CreditBalance': $output = $this->credit_balance($DBdata, $data['agent_id'], $device); break;
											case 'DebitBalance': $output = $this->debit_balance($DBdata, $data['agent_id'], $device); break;
											case 'MemberLogin': $output = $this->player_login($DBdata, $data['agent_id'], $device); break;
											case 'GetBalanceDV': $output = $this->player_balance($DBdata, $data['agent_id'], $device, $syslang); break;
											case 'MemberLogout': $output = $this->player_logout($DBdata, $data['agent_id'], $device); break;
											case 'VerifyToken': $output = $this->verify_token($DBdata); break;
											case 'GetBankList': $output = $this->get_bank_list(); break;
											case 'GetGameList': $output = $this->get_game_list($DBdata); break;
											case 'GetSubGameList': $output = $this->get_sub_game_list($DBdata); break;
											case 'LoginGame': $output = $this->player_login_game($DBdata, $data['agent_id'], $device, $syslang); break;
											case 'MemberRegisterDV': $output = $this->create_player_mg($DBdata, $data['agent_id'], $device); break;
											case 'UpdatePlayerType': $output = $this->update_player_type($DBdata, $data['agent_id'], $device, $syslang); break;
										}
									}
									else
									{
										$output['status'] = ERROR_SIGNATURE_INCORRECT;
										$output['msg'] = $this->lang->line('error_signature_incorrect');
									}
								}
								else
								{
									$output['status'] = ERROR_METHOD_INCORRECT;
									$output['msg'] = $this->lang->line('error_method_incorrect');
								}
							}
							else
							{
								$output['status'] = ERROR_SIGNATURE_EMPTY;
								$output['msg'] = $this->lang->line('error_signature_empty');
							}
						}
						else
						{
							$output['status'] = ERROR_API_ACCESS_DENIED;
							$output['msg'] = $this->lang->line('error_api_access_denied');
						}
					}
					else
					{
						$output['status'] = ERROR_AGENT_ID_NOT_FOUND;
						$output['msg'] = $this->lang->line('error_agent_id_not_found');
					}
				}
				else
				{
					$output['status'] = ERROR_AGENT_ID_EMPTY;
					$output['msg'] = $this->lang->line('error_agent_id_empty');
				}	
			}
			else
			{
				$output['status'] = ERROR_SYSTEM_MAINTENANCE;
				$output['msg'] = $this->lang->line('error_system_maintenance');
			}
			
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($output))
					->_display();
					
			exit();
		}	
	}
	
	private function create_player($data = NULL, $agentId = NULL, $device = NULL)
	{
		$this->load->helper('email');
		
		//Initial output data
		$json = array(
					'status' => ERROR_SYSTEM_ERROR, 
					'msg' => $this->lang->line('error_system_error'),
				);
		
		//Data validation
		if(empty($data['username']))
		{
			$json['status'] = ERROR_USERNAME_EMPTY;
			$json['msg'] = $this->lang->line('error_username_empty');
		}
		else if( ! preg_match('/^[a-z0-9]{6,16}$/', $data['username']))
		{
			$json['status'] = ERROR_USERNAME_INCORRECT;
			$json['msg'] = $this->lang->line('error_username_incorrect');
		}
		else if(empty($data['password']))
		{
			$json['status'] = ERROR_PASSWORD_EMPTY;
			$json['msg'] = $this->lang->line('error_password_empty');
		}
		else if( ! preg_match('/^[A-Za-z0-9!#$^*]{6,15}$/', $data['password']))
		{
			$json['status'] = ERROR_PASSWORD_INCORRECT;
			$json['msg'] = $this->lang->line('error_password_incorrect');
		}
		else if(empty($data['nickname']))
		{
			$json['status'] = ERROR_NICKNAME_EMPTY;
			$json['msg'] = $this->lang->line('error_nickname_empty');
		}
		else if( ! preg_match('/^[A-Za-z0-9]{1,32}$/', $data['nickname']))
		{
			$json['status'] = ERROR_NICKNAME_INCORRECT;
			$json['msg'] = $this->lang->line('error_nickname_incorrect');
		}
		else if( ! empty($data['mobile']) && ! is_numeric($data['mobile']))
		{
			$json['status'] = ERROR_MOBILE_INCORRECT;
			$json['msg'] = $this->lang->line('error_mobile_incorrect');
		}
		else if( ! empty($data['email']) && valid_email($data['email']) == FALSE)
		{
			$json['status'] = ERROR_EMAIL_INCORRECT;
			$json['msg'] = $this->lang->line('error_email_incorrect');
		}
		else
		{
			$upline = $this->user_model->get_user_data($agentId);
			if( ! empty($upline))
			{
				//Check available username
				$username_exits = FALSE;
				
				$userdata = $this->user_model->check_username_exits($data['username']);
				if($userdata == TRUE)
				{
					$username_exits = TRUE;
				}
				
				if($username_exits == FALSE)
				{
					$accountdata = $this->account_model->check_username_exits($data['username']);
					if($accountdata == TRUE)
					{
						$username_exits = TRUE;
					}
				}	
				
				if($username_exits == FALSE)
				{
					$player_data = $this->player_model->check_username_exits($data['username']);
					if($player_data == TRUE)
					{
						$username_exits = TRUE;
					}
				}
				
				if($username_exits == FALSE)
				{
					$data['referrer'] = '';
					
					//Database update
					$this->db->trans_start();
					
					$newData = $this->player_model->add_player($upline, $data);
					$this->player_model->insert_log(LOG_REGISTER, $device, $newData);
					
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = ERROR_SUCCESS;
						$json['msg'] = $this->lang->line('error_create_user_successful');
					}
					else
					{
						$json['status'] = ERROR_CREATE_USER_FAILED;
						$json['msg'] = $this->lang->line('error_create_user_failed');
					}
				}
				else
				{
					$json['status'] = ERROR_USERNAME_EXITS;
					$json['msg'] = $this->lang->line('error_username_already_exits');
				}
			}
			else
			{
				$json['status'] = ERROR_AGENT_ID_NOT_FOUND;
				$json['msg'] = $this->lang->line('error_agent_id_not_found');
			}
		}
		
		return $json;
	}
	
	private function get_balance($data = NULL, $agentId = NULL)
	{
		//Initial output data
		$json = array(
					'status' => ERROR_SYSTEM_ERROR, 
					'msg' => $this->lang->line('error_system_error'),
				);
		
		//Data validation
		if(empty($data['username']))
		{
			$json['status'] = ERROR_USERNAME_EMPTY;
			$json['msg'] = $this->lang->line('error_username_empty');
		}
		else
		{
			//Check for valid downline
			$player_data = $this->player_model->get_downline_data($agentId, $data['username']);
			if( ! empty($player_data))
			{
				$json['status'] = ERROR_SUCCESS;
				$json['msg'] = $this->lang->line('error_get_the_current_balance');
				$json['balance'] = $player_data['points'];
			}
			else
			{
				$json['status'] = ERROR_USERNAME_NOT_FOUND;
				$json['msg'] = $this->lang->line('error_username_not_found');
			}
		}
		
		return $json;
	}
	
	private function credit_balance($data = NULL, $agentId = NULL, $device = NULL)
	{
		//Initial output data
		$json = array(
					'status' => ERROR_SYSTEM_ERROR, 
					'msg' => $this->lang->line('error_system_error'),
				);
		
		//Data validation
		if(empty($data['username']))
		{
			$json['status'] = ERROR_USERNAME_EMPTY;
			$json['msg'] = $this->lang->line('error_username_empty');
		}
		else if(empty($data['amount']))
		{
			$json['status'] = ERROR_AMOUNT_EMPTY;
			$json['msg'] = $this->lang->line('error_amount_empty');
		}
		else if( ! is_numeric($data['amount']) OR $data['amount'] <= 0)
		{
			$json['status'] = ERROR_AMOUNT_INCORRECT;
			$json['msg'] = $this->lang->line('error_amount_incorrect');
		}
		else
		{
			//Check for valid downline
			$player_data = $this->player_model->get_downline_data($agentId, $data['username']);
			if( ! empty($player_data))
			{
				$upline = $this->user_model->get_user_data($agentId);
				
				if(isset($upline) && $upline['points'] >= $data['amount'])
				{
					//Database update
					$this->db->trans_start();
					
					$newData = $this->player_model->point_transfer($player_data, $data['amount'], $agentId);
					$newData2 = $this->user_model->point_transfer($upline, ($data['amount'] * -1));
					$this->general_model->insert_point_transfer_report($upline, $player_data, $data['amount']);
					$this->general_model->insert_cash_transfer_report($player_data, $data['amount'], $agentId, TRANSFER_POINT_IN);
					$this->player_model->insert_log(LOG_PLAYER_DEPOSIT_POINT, $device, $newData, $player_data);
					$this->user_model->insert_log(LOG_USER_WITHDRAW_POINT, $device, $newData2, $upline);
					
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = ERROR_SUCCESS;
						$json['msg'] = $this->lang->line('error_transfer_successful');
						$json['balance'] = ($player_data['points'] + $data['amount']);
					}
					else
					{
						$json['status'] = ERROR_TRANSFER_FAILED;
						$json['msg'] = $this->lang->line('error_transfer_failed');
					}
				}
				else
				{
					$json['status'] = ERROR_POINT_INSUFFICIENT;
					$json['msg'] = $this->lang->line('error_point_insufficient');
				}
			}
			else
			{
				$json['status'] = ERROR_USERNAME_NOT_FOUND;
				$json['msg'] = $this->lang->line('error_username_not_found');
			}
		}
		
		return $json;
	}
	
	private function debit_balance($data = NULL, $agentId = NULL, $device = NULL)
	{
		//Initial output data
		$json = array(
					'status' => ERROR_SYSTEM_ERROR, 
					'msg' => $this->lang->line('error_system_error'),
				);
		
		//Data validation
		if(empty($data['username']))
		{
			$json['status'] = ERROR_USERNAME_EMPTY;
			$json['msg'] = $this->lang->line('error_username_empty');
		}
		else if(empty($data['amount']))
		{
			$json['status'] = ERROR_AMOUNT_EMPTY;
			$json['msg'] = $this->lang->line('error_amount_empty');
		}
		else if( ! is_numeric($data['amount']) OR $data['amount'] <= 0)
		{
			$json['status'] = ERROR_AMOUNT_INCORRECT;
			$json['msg'] = $this->lang->line('error_amount_incorrect');
		}
		else
		{
			//Check for valid downline
			$player_data = $this->player_model->get_downline_data($agentId, $data['username']);
			if( ! empty($player_data))
			{
				if($player_data['points'] >= $data['amount'])
				{
					$upline = $this->user_model->get_user_data($agentId);
					
					//Database update
					$this->db->trans_start();
					
					$newData = $this->player_model->point_transfer($player_data, ($data['amount'] * -1), $agentId);
					$newData2 = $this->user_model->point_transfer($upline, $data['amount']);
					$this->general_model->insert_point_transfer_report($player_data, $upline, $data['amount']);
					$this->general_model->insert_cash_transfer_report($player_data, $data['amount'], $agentId, TRANSFER_POINT_OUT);
					$this->player_model->insert_log(LOG_PLAYER_WITHDRAW_POINT, $device, $newData, $player_data);
					$this->user_model->insert_log(LOG_USER_DEPOSIT_POINT, $device, $newData2, $upline);
					
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = ERROR_SUCCESS;
						$json['msg'] = $this->lang->line('error_transfer_successful');
						$json['balance'] = ($player_data['points'] - $data['amount']);
					}
					else
					{
						$json['status'] = ERROR_TRANSFER_FAILED;
						$json['msg'] = $this->lang->line('error_transfer_failed');
					}
				}
				else
				{
					$json['status'] = ERROR_POINT_INSUFFICIENT;
					$json['msg'] = $this->lang->line('error_point_insufficient');
				}
			}
			else
			{
				$json['status'] = ERROR_USERNAME_NOT_FOUND;
				$json['msg'] = $this->lang->line('error_username_not_found');
			}
		}
		
		return $json;
	}
	
	private function player_login($data = NULL, $agentId = NULL, $device = NULL)
	{
		//Initial output data
		$json = array(
					'status' => ERROR_SYSTEM_ERROR, 
					'msg' => $this->lang->line('error_system_error'),
				);
		
		//Data validation
		if(empty($data['username']))
		{
			$json['status'] = ERROR_USERNAME_EMPTY;
			$json['msg'] = $this->lang->line('error_username_empty');
		}
		else if(empty($data['password']))
		{
			$json['status'] = ERROR_PASSWORD_EMPTY;
			$json['msg'] = $this->lang->line('error_password_empty');
		}
		else
		{
			//Check for valid downline
			$response = $this->player_model->verify_login($data);
			if(isset($response['is_logged_in'])) 
			{
				$login_status = STATUS_FAIL;
				
				if($response['is_logged_in'] == FALSE)
				{
					$json['status'] = ERROR_INVALID_LOGIN;
					$json['msg'] = $this->lang->line('error_invalid_login');
				}
				else if($response['active'] == STATUS_ACTIVE)
				{
					$login_status = STATUS_SUCCESS;
					$json['status'] = ERROR_SUCCESS;
					$json['msg'] = $this->lang->line('error_login_success');
					$json['token'] = $response['login_token'];
				}
				else
				{
					$json['status'] = ERROR_ACCOUNT_SUSPENDED;
					$json['msg'] = $this->lang->line('error_account_suspended');
				}
				
				//Database update
				$this->db->trans_start();
				
				$this->player_model->insert_login_report($response, $device, $login_status);
				
				if($login_status == STATUS_SUCCESS) 
				{
					$this->player_model->update_last_login($response);
					$this->player_model->insert_log(LOG_LOGIN, $device, $response);
				}
				
				$this->db->trans_complete();
			}
			else
			{
				$json['status'] = ERROR_INVALID_LOGIN;
				$json['msg'] = $this->lang->line('error_invalid_login');
			}
		}
		
		return $json;
	}
	
	private function player_balance($data = NULL, $agent_id = NULL, $device = NULL, $syslang = NULL)
	{
		//Initial output data
		$json = array(
					'status' => ERROR_SYSTEM_ERROR, 
					'msg' => $this->lang->line('error_system_error'),
				);
				
		$player_data = $this->player_model->get_player_data($data['username']);
		if( ! empty($player_data))
		{
			if( ! empty($player_data['last_in_game']))
			{
				//Get balance
				$balance = $player_data['points'];
				$account_data = $this->player_model->get_player_game_account_data($player_data['last_in_game'], $player_data['player_id']);
				if( ! empty($account_data))
				{
					$api_data = $this->api_model->get_api_data($agent_id);	
				
					$url = base_url('gameapi/api'); 
					$signature = md5($api_data['agent_id'] . $player_data['last_in_game'] . $account_data['username'] . $api_data['secret_key']);
					
					$param_array = array(
											"method" => 'GetBalance',
											"agent_id" => $api_data['agent_id'],
											"syslang" => $syslang,
											"device" => $device,
											"provider_code" => $player_data['last_in_game'],
											"username" => $account_data['username'],
											"password" => $account_data['password'],
											"signature" => $signature,
										);
									
					$response = $this->curl_json($url, $param_array);
					$result_array = json_decode($response, TRUE);
					
					if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
					{
						$balance = ($balance + $result_array['result']);
					}
				}
				
				$json['status'] = ERROR_SUCCESS;
				$json['msg'] = $this->lang->line('error_get_the_current_balance');
				$json['balance'] = $balance;
			}
			else
			{
				$json['status'] = ERROR_SUCCESS;
				$json['msg'] = $this->lang->line('error_get_the_current_balance');
				$json['balance'] = $player_data['points'];
			}
		}
		else
		{
			$json['status'] = ERROR_USERNAME_NOT_FOUND;
			$json['msg'] = $this->lang->line('error_username_not_found');
		}
		
		return $json;
	}
	
	private function player_logout($data = NULL, $agentId = NULL, $device = NULL)
	{
		//Initial output data
		$json = array(
					'status' => ERROR_SYSTEM_ERROR, 
					'msg' => $this->lang->line('error_system_error'),
				);
		
		//Data validation
		if(empty($data['username']))
		{
			$json['status'] = ERROR_USERNAME_EMPTY;
			$json['msg'] = $this->lang->line('error_username_empty');
		}
		else
		{
			$player_data = $this->player_model->get_player_data($data['username']);
			if( ! empty($player_data))
			{
				//Database update
				$this->db->trans_start();
				
				$this->player_model->clear_login_token($data['username']);
				$this->player_model->insert_log(LOG_LOGOUT, $device, $player_data);
				
				$this->db->trans_complete();
				
				$json['status'] = ERROR_SUCCESS;
				$json['msg'] = $this->lang->line('error_logout_success');
			}
			else
			{
				$json['status'] = ERROR_USERNAME_NOT_FOUND;
				$json['msg'] = $this->lang->line('error_username_not_found');
			}
		}
		
		return $json;
	}
	
	private function verify_token($data = NULL)
	{
		//Initial output data
		$json = array(
					'status' => ERROR_SYSTEM_ERROR, 
					'msg' => $this->lang->line('error_system_error'),
				);
		
		//Data validation
		if(empty($data['username']))
		{
			$json['status'] = ERROR_USERNAME_EMPTY;
			$json['msg'] = $this->lang->line('error_username_empty');
		}
		else if(empty($data['token']))
		{
			$json['status'] = ERROR_TOKEN_EMPTY;
			$json['msg'] = $this->lang->line('error_token_empty');
		}
		else
		{
			$player_data = $this->player_model->get_player_data($data['username']);
			if( ! empty($player_data))
			{
				if($player_data['login_token'] == $data['token'])
				{
					$json['status'] = ERROR_SUCCESS;
					$json['msg'] = $this->lang->line('error_valid_token');
				}
				else
				{
					$json['status'] = ERROR_INVALID_TOKEN;
					$json['msg'] = $this->lang->line('error_invalid_token');
				}
			}
			else
			{
				$json['status'] = ERROR_USERNAME_NOT_FOUND;
				$json['msg'] = $this->lang->line('error_username_not_found');
			}
		}
		
		return $json;
	}
	
	private function get_bank_list()
	{
		//Initial output data
		$json = array(
					'status' => ERROR_SYSTEM_ERROR, 
					'msg' => $this->lang->line('error_system_error'),
				);
		
		$bank_data = $this->bank_model->get_bank_list();
		if( ! empty($bank_data))
		{
			$json['status'] = ERROR_SUCCESS;
			$json['msg'] = $this->lang->line('error_success');
			$json['data'] = $bank_data;
		}
		
		return $json;
	}
	
	private function get_game_list($data = NULL)
	{
		//Initial output data
		$json = array(
					'status' => ERROR_SYSTEM_ERROR, 
					'msg' => $this->lang->line('error_system_error'),
				);
		
		$game_data = $this->game_model->get_game_list();
		if( ! empty($game_data))
		{
			$game_list = array();
			if( ! empty($data['game_type_code']))
			{
				for($i=0;$i<sizeof($game_data);$i++)
				{
					$category_arr = explode(',', $game_data[$i]['game_type_code']);
					if(in_array($data['game_type_code'], $category_arr))
					{
						array_push($game_list, array(
														'provider_code' => $game_data[$i]['game_code'], 
														'provider_name' => $this->lang->line($game_data[$i]['game_name'])
													)
						);
					}
				}
			}
			else
			{
				for($i=0;$i<sizeof($game_data);$i++)
				{
					array_push($game_list, array(
													'provider_code' => $game_data[$i]['game_code'], 
													'provider_name' => $this->lang->line($game_data[$i]['game_name']),
													'game_type_code' => $game_data[$i]['game_type_code'], 
												)
					);
				}
			}
			
			$json['status'] = ERROR_SUCCESS;
			$json['msg'] = $this->lang->line('error_success');
			$json['data'] = $game_list;
		}
		
		return $json;
	}
	
	private function get_sub_game_list($data = NULL)
	{
		//Initial output data
		$json = array(
					'status' => ERROR_SYSTEM_ERROR, 
					'msg' => $this->lang->line('error_system_error'),
				);
		
		$sub_game_data = $this->game_model->get_sub_game_list($data);
		
		if( ! empty($sub_game_data))
		{
			$json['status'] = ERROR_SUCCESS;
			$json['msg'] = $this->lang->line('error_success');
			$json['data'] = $sub_game_data;
		}
		else
		{
			$json['status'] = ERROR_PARAMETER_INCORRECT;
			$json['msg'] = $this->lang->line('error_parameter_incorrect');
		}
		
		return $json;
	}
	
	private function player_login_game($data = NULL, $agent_id = NULL, $device = NULL, $syslang = NULL)
	{
		//Initial output data
		$json = array(
					'status' => ERROR_SYSTEM_ERROR, 
					'msg' => $this->lang->line('error_system_error'),
				);
		
		$game_data = $this->game_model->get_game_data($data['provider_code']);
		if( ! empty($game_data))
		{
			$current_time = time();
			$from_time = strtotime(date('Y-m-d') . ' ' . $game_data['fixed_from_time']);
			$to_time = strtotime(date('Y-m-d') . ' ' . $game_data['fixed_to_time']);
			
			if($game_data['is_maintenance'] == STATUS_YES OR 
				($game_data['fixed_maintenance'] == STATUS_YES && $game_data['fixed_day'] == date('N') && $current_time >= $from_time && $current_time <= $to_time) OR 
				($game_data['urgent_maintenance'] == STATUS_YES && $current_time >= $game_data['urgent_date']))
			{
				$json['status'] = ERROR_GAME_MAINTENANCE;
				$json['msg'] = $this->lang->line('error_game_maintenance');
			}
			else
			{
				$player_data = $this->player_model->get_player_data($data['username']);
				if( ! empty($player_data))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$api_data = $this->api_model->get_api_data($agent_id);	
					
					$launch_game = FALSE;
					$url = base_url('gameapi/api'); 
					
					$param_array = array(
											"agent_id" => $api_data['agent_id'],
											"syslang" => $syslang,
											"device" => $device,
											"provider_code" => $data['provider_code'],
											"username" => '',
											"signature" => '',
										);
					
					//Create member
					$account_data = $this->player_model->get_player_game_account_data($data['provider_code'], $player_data['player_id']);
					if( ! empty($account_data))
					{
						$launch_game = TRUE;
						$param_array['username'] = $account_data['username'];
						$param_array['password'] = $account_data['password'];
						$param_array['signature'] = md5($api_data['agent_id'] . $data['provider_code'] . $param_array['username'] . $api_data['secret_key']);
					}
					else
					{
						$param_array['method'] = 'CreateMember';
						$param_array['username'] = $sys_data['system_prefix'] . $player_data['username'];
						$param_array['password'] = rand(10000000, 99999999);
						$param_array['signature'] = md5($api_data['agent_id'] . $data['provider_code'] . $param_array['username'] . $api_data['secret_key']);
						
						$response = $this->curl_json($url, $param_array);
						$result_array = json_decode($response, TRUE);
						
						if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
						{
							$launch_game = TRUE;
							$this->player_model->add_player_game_account($data['provider_code'], $player_data['player_id'], $param_array['username'], $param_array['password']);
						}
					}
					
					if($launch_game == TRUE)
					{
						//Get balance, withdraw and logout from previous game
						if( ! empty($player_data['last_in_game']) && $player_data['last_in_game'] != $data['provider_code'])
						{
							$account_data = $this->player_model->get_player_game_account_data($player_data['last_in_game'], $player_data['player_id']);
							if( ! empty($account_data))
							{
								$balance = 0;
								$signature_2 = md5($api_data['agent_id'] . $player_data['last_in_game'] . $account_data['username'] . $api_data['secret_key']);
								
								$param_array_2 = array(
											"agent_id" => $api_data['agent_id'],
											"syslang" => $syslang,
											"device" => $device,
											"provider_code" => $player_data['last_in_game'],
											"username" => $account_data['username'],
											"password" => $account_data['password'],
											"signature" => $signature_2,
										);
								
								//Get balance
								$param_array_2['method'] = 'GetBalance';
								$response = $this->curl_json($url, $param_array_2);
								$result_array = json_decode($response, TRUE);
								
								if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
								{
									$balance = $result_array['result'];
								}
								
								if($balance > 0)
								{
									//Withdraw credit
									$param_array_2['method'] = 'ChangeBalance';
									$param_array_2['order_id'] = 'OUT' . date("YmdHis") . $account_data['username'];
									$param_array_2['amount'] = ($balance * -1);
									$response = $this->curl_json($url, $param_array_2);
									$result_array = json_decode($response, TRUE);
								
									if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
									{
										//update wallet
										$newData = $this->player_model->point_transfer($player_data, $balance, $player_data['username']);
										$this->player_model->insert_log(LOG_WALLET_TRANSFER, $device, $newData, $player_data);
										$this->general_model->insert_game_transfer_report($player_data['last_in_game'], 'MAIN', $balance, $player_data['points'], $balance, $player_data['player_id']);
									}
								}
								
								//Logout game
								$param_array_2['method'] = 'LogoutGame';
								$this->curl_json($url, $param_array_2);
							}	
						}
						
						//Do deposit if have balance
						$player_data_2 = $this->player_model->get_player_data($data['username']);
						if( ! empty($player_data_2))
						{
							if($player_data_2['points'] > 0)
							{
								//update wallet
								$newData_2 = $this->player_model->point_transfer($player_data_2, ($player_data_2['points'] * -1), $player_data_2['username']);
								$this->player_model->insert_log(LOG_WALLET_TRANSFER, $device, $newData_2, $player_data_2);
								
								//insert table
								$this->general_model->insert_game_transfer_report('MAIN', $data['provider_code'], $player_data_2['points'], 0, $player_data_2['points'], $player_data_2['player_id']);
									
								//Deposit credit
								$param_array['method'] = 'ChangeBalance';
								$param_array['order_id'] = 'IN' . date("YmdHis") . $param_array['username'];
								$param_array['amount'] = $player_data_2['points'];
								$response = $this->curl_json($url, $param_array);
								$result_array = json_decode($response, TRUE);
							
								if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
								{
									//update last in game
									$this->player_model->update_player_last_in_game($data['provider_code'], $player_data_2['player_id']);
								}
								else
								{
									//update wallet
									$newData_3 = $this->player_model->point_transfer($player_data_2, $player_data_2['points'], $player_data_2['username']);
									$this->player_model->insert_log(LOG_WALLET_TRANSFER, $device, $newData_3, $player_data_2);
									
									//insert table
									$this->general_model->insert_game_transfer_report($data['provider_code'], 'MAIN', $player_data_2['points'], 0, $player_data_2['points'], $player_data_2['player_id']);
								}
							}
						}
						
						//Login game
						$param_array['method'] = 'LoginGame';		
						$param_array['game_type_code'] = $data['game_type_code'];
						$param_array['game_code'] = $data['game_code'];				
						$response = $this->curl_json($url, $param_array);
						$result_array = json_decode($response, TRUE);
						
						if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
						{
							$json['status'] = ERROR_SUCCESS;
							$json['msg'] = $this->lang->line('error_success');
							$json['url'] = $result_array['result'];
						}				
					}
				}
				else
				{
					$json['status'] = ERROR_USERNAME_NOT_FOUND;
					$json['msg'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		else
		{
			$json['status'] = ERROR_PROVIDER_CODE_INCORRECT;
			$json['msg'] = $this->lang->line('error_provider_code_incorrect');
		}
		
		return $json;
	}
	
	private function create_player_mg($data = NULL, $agentId = NULL, $device = NULL)
	{
		$this->load->helper('email');
		
		//Initial output data
		$json = array(
					'status' => ERROR_SYSTEM_ERROR, 
					'msg' => $this->lang->line('error_system_error'),
				);
		
		//Data validation
		if(empty($data['username']))
		{
			$json['status'] = ERROR_USERNAME_EMPTY;
			$json['msg'] = $this->lang->line('error_username_empty');
		}
		else if( ! preg_match('/^[a-z0-9]{6,16}$/', $data['username']))
		{
			$json['status'] = ERROR_USERNAME_INCORRECT;
			$json['msg'] = $this->lang->line('error_username_incorrect');
		}
		else if(empty($data['password']))
		{
			$json['status'] = ERROR_PASSWORD_EMPTY;
			$json['msg'] = $this->lang->line('error_password_empty');
		}
		else if( ! preg_match('/^[A-Za-z0-9!#$^*]{6,15}$/', $data['password']))
		{
			$json['status'] = ERROR_PASSWORD_INCORRECT;
			$json['msg'] = $this->lang->line('error_password_incorrect');
		}
		else if(empty($data['nickname']))
		{
			$json['status'] = ERROR_NICKNAME_EMPTY;
			$json['msg'] = $this->lang->line('error_nickname_empty');
		}
		else if( ! preg_match('/^[A-Za-z0-9]{1,32}$/', $data['nickname']))
		{
			$json['status'] = ERROR_NICKNAME_INCORRECT;
			$json['msg'] = $this->lang->line('error_nickname_incorrect');
		}
		else
		{
			$upline = $this->user_model->get_user_data($agentId);
			if( ! empty($upline))
			{
				//Check available username
				$username_exits = FALSE;
				
				$userdata = $this->user_model->check_username_exits($data['username']);
				if($userdata == TRUE)
				{
					$username_exits = TRUE;
				}
				
				if($username_exits == FALSE)
				{
					$accountdata = $this->account_model->check_username_exits($data['username']);
					if($accountdata == TRUE)
					{
						$username_exits = TRUE;
					}
				}	
				
				if($username_exits == FALSE)
				{
					$player_data = $this->player_model->check_username_exits($data['username']);
					if($player_data == TRUE)
					{
						$username_exits = TRUE;
					}
				}
				
				if($username_exits == FALSE)
				{
					$data['mobile'] = '';
					$data['email'] = '';
					$data['wechat'] = '';
					$data['referrer'] = '';
					$data['referral_code'] = '';
					
					//Database update
					$this->db->trans_start();
					
					$newData = $this->player_model->add_player($upline, $data);
					$this->player_model->insert_log(LOG_REGISTER, $device, $newData);
					
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = ERROR_SUCCESS;
						$json['msg'] = $this->lang->line('error_create_user_successful');
						$json['player_id'] = $newData['player_id'];
					}
					else
					{
						$json['status'] = ERROR_CREATE_USER_FAILED;
						$json['msg'] = $this->lang->line('error_create_user_failed');
					}
				}
				else
				{
					$json['status'] = ERROR_USERNAME_EXITS;
					$json['msg'] = $this->lang->line('error_username_already_exits');
				}
			}
			else
			{
				$json['status'] = ERROR_AGENT_ID_NOT_FOUND;
				$json['msg'] = $this->lang->line('error_agent_id_not_found');
			}
		}
		
		return $json;
	}
	
	private function update_player_type($data = NULL, $agent_id = NULL, $device = NULL, $syslang = NULL)
	{
		//Initial output data
		$json = array(
					'status' => ERROR_SYSTEM_ERROR, 
					'msg' => $this->lang->line('error_system_error'),
				);
		
		$player_data = $this->player_model->get_player_data($data['username']);
		if( ! empty($player_data))
		{
			$DBdata = array(
				'player_type' => 3
			);
			
			$this->db->where('player_id', $player_data['player_id']);
			$this->db->limit(1);
			$this->db->update('players', $DBdata);
		
			$json['status'] = ERROR_SUCCESS;
			$json['msg'] = $this->lang->line('error_success');
		}
		else
		{
			$json['status'] = ERROR_USERNAME_NOT_FOUND;
			$json['msg'] = $this->lang->line('error_username_not_found');
		}
		
		return $json;
	}
}