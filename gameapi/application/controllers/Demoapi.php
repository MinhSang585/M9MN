<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Demoapi extends MY_Controller {

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
				$provider_code = (isset($arr['provider_code']) ? strtoupper(trim($arr['provider_code'])) : '');
				$username = (isset($arr['username']) ? trim($arr['username']) : '');
				$password = (isset($arr['password']) ? trim($arr['password']) : '');
				$amount = (isset($arr['amount']) ? trim($arr['amount']) : '');
				$order_id = (isset($arr['order_id']) ? trim($arr['order_id']) : '');
				$game_id = (isset($arr['game_id']) ? trim($arr['game_id']) : '');
				$game_type_code = (isset($arr['game_type_code']) ? trim($arr['game_type_code']) : '');
				$game_code = (isset($arr['game_code']) ? trim($arr['game_code']) : '');
				$return_url = (isset($arr['return_url']) ? trim($arr['return_url']) : '');
				$is_demo = ((isset($arr['is_demo']) && $arr['is_demo'] == STATUS_YES) ? STATUS_YES : STATUS_NO);
				$theme = (isset($arr['theme']) ? trim($arr['theme']) : '');
				$incoming_ip = $this->input->ip_address();
				$whitelist_method = array('CreateMember', 'GetBalance', 'ChangeBalance', 'LoginGame', 'LogoutGame', 'GameList');
				$post_data = array(
									'syslang' => $syslang, 
									'device' => $device, 
									'provider_code' => $provider_code, 
									'username' => $username, 
									'password' => $password, 
									'amount' => bcdiv($amount, 1, 2), 
									'order_id' => $order_id,
									'game_id' => $game_id,
									'return_url' => $return_url, 
									'game_type_code' => $game_type_code, 
									'game_code' => $game_code,
									'is_demo' => $is_demo,
									'theme' => $theme
							);
				
				$this->lang->load('general', get_language($syslang));
				
				//Verify agent
				if( ! empty($agent_id))
				{
					$data = $this->api_model->get_api_data($agent_id);
					
					if( ! empty($data))
					{
						//Verify IP
						$whitelist_ip = explode(',', $data['game_whitelist_ip']);
						
						if(in_array($incoming_ip, $whitelist_ip) OR $data['game_whitelist_ip'] == '*')
						{
							//Verify method
							if( ! empty($method))
							{
								if(in_array($method, $whitelist_method))
								{
									//Verify signature
									if( ! empty($signature))
									{
										$verify_sign = md5($data['agent_id'] . $provider_code . $post_data['username'] . $data['secret_key']);
											
										if($signature == $verify_sign)
										{
											//Verify game provider
											if( ! empty($provider_code))
											{
												$game_data = NULL;
		
												$query = $this
														->db
														->select('api_data')
														->where('game_code', $provider_code)
														->limit(1)
														->get('games');
												
												if($query->num_rows() > 0)
												{
													$game_data = $query->row_array();  
												}
												
												$query->free_result();
												
												if( ! empty($game_data))
												{
													//Verify param
													if($method == 'GameList')
													{
														$output = $this->get_sub_game_list($post_data);
													}
													else if(empty($post_data['username']))
													{
														$output['errorCode'] = ERROR_USERNAME_EMPTY;
														$output['errorMessage'] = $this->lang->line('error_username_empty');
													}
													else if( ! preg_match('/^[a-z0-9]{4,20}$/', $post_data['username']))
													{
														$output['errorCode'] = ERROR_USERNAME_INCORRECT;
														$output['errorMessage'] = $this->lang->line('error_username_incorrect');
													}
													else if($method == 'CreateMember' && empty($post_data['password']))
													{
														$output['errorCode'] = ERROR_PASSWORD_EMPTY;
														$output['errorMessage'] = $this->lang->line('error_password_empty');
													}
													else if($method == 'ChangeBalance' && (empty($post_data['amount']) || $post_data['amount'] == 0))
													{
														$output['errorCode'] = ERROR_AMOUNT_EMPTY;
														$output['errorMessage'] = $this->lang->line('error_amount_empty');
													}
													else if($method == 'ChangeBalance' && is_numeric($post_data['amount']) == FALSE)
													{
														$output['errorCode'] = ERROR_AMOUNT_INCORRECT;
														$output['errorMessage'] = $this->lang->line('error_amount_incorrect');
													}
													else if($method == 'ChangeBalance' && empty($post_data['order_id']))
													{
														$output['errorCode'] = ERROR_ORDER_ID_EMPTY;
														$output['errorMessage'] = $this->lang->line('error_order_id_empty');
													}
													else
													{
														switch($provider_code)
														{
															case 'AB': $output = $this->ab_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															//case 'AG': $output = $this->ag_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'AG': $output = $this->ag2_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'BBIN': $output = $this->bbin_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'BL': $output = $this->bl_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'CMD': $output = $this->cmd_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'DG': $output = $this->dg_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'DT': $output = $this->dt_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'EB': $output = $this->eb_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'EVO': $output = $this->evo_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'EVO8': $output = $this->evo8_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'GD': $output = $this->gd_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															//case 'GPI': $output = $this->gpi_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'HB': $output = $this->hb_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'IBC': $output = $this->ibc_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'ICG': $output = $this->icg_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'JK': $output = $this->jk_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'KA': $output = $this->ka_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															//case 'KY': $output = $this->ky_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'LE': $output = $this->le_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'LH': $output = $this->lh_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'MEGA': $output = $this->mega_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'MG': $output = $this->mg_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'N2': $output = $this->n2_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'PP': $output = $this->pp_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'PT': $output = $this->pt_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'PUS8': $output = $this->pus8_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'SA': $output = $this->sa_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'SBO': $output = $this->sbo_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'SG': $output = $this->sg_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'SP': $output = $this->sp_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'SX': $output = $this->sx_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'UG': $output = $this->ug_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'WM': $output = $this->wm_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'XADJ': $output = $this->xadj_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'XE': $output = $this->xe_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
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
	
	private function get_sub_game_list($post_data)
	{
		$slot_arr = array();
		$slot_data = $this->game_model->get_sub_game_list($post_data['provider_code'], $post_data['game_type_code']);
		if( ! empty($slot_data))
		{
			$folder = 'slots';
			switch($post_data['game_type_code'])
			{
				case GAME_FISHING: $folder = 'fishing'; break;
				case GAME_OTHERS: $folder = 'others'; break;
			}
			
			for($i=0;$i<sizeof($slot_data);$i++)
			{
				$game_name = $slot_data[$i]['game_name_en'];
				$game_picture = $slot_data[$i]['game_picture_en'];
				$game_lang_folder = 'en';
					
				if($post_data['syslang'] == LANG_ZH_HK OR $post_data['syslang'] == LANG_ZH_TW)
				{
					$game_name = $slot_data[$i]['game_name_cht'];
					$game_picture = $slot_data[$i]['game_picture_cht'];
					$game_lang_folder = 'zh';
					
					if( ! empty($slot_data[$i]['game_picture_cht']))
					{
						$game_picture = $slot_data[$i]['game_picture_chs'];
						$game_lang_folder = 'cn';
					}	
				}
				else if($post_data['syslang'] == LANG_ZH_CN)
				{
					$game_name = $slot_data[$i]['game_name_chs'];
					$game_picture = $slot_data[$i]['game_picture_chs'];
					$game_lang_folder = 'cn';
				}
				
				if( ! empty($game_picture))
				{
					$game_picture = base_url('assets/img/' . $folder . '/' . strtolower($post_data['provider_code']) . '/' . $game_lang_folder . '/' . $game_picture);
				}
				
				$tmp_arr = array('game_code' => $slot_data[$i]['game_code'], 'game_name' => $game_name, 'game_picture' => $game_picture, 'is_progressive' => $slot_data[$i]['is_progressive'], 'is_hot' => $slot_data[$i]['is_hot'], 'is_new' => $slot_data[$i]['is_new']);
				array_push($slot_arr, $tmp_arr);
			}
		}
		
		$output['errorCode'] = ERROR_SUCCESS;
		$output['errorMessage'] = $this->lang->line('error_success');
		$output['result'] = $slot_arr;
		
		return $output;
	}
	
	#{"APIUrl":"", "AgentId":"", "PropertyID":"", "DESKey":"", "MD5Key":"", "IdentificationCode":""}
	private function ab_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		
		$param_array = array(
			"random" => mt_rand()
		);
		
		if($method == 'CreateMember')
		{
			$url .= '/check_or_create';
			$param_array['agent'] = $arr['AgentId'];
			$param_array['client'] = $post_data['username'];
			$param_array['password'] = $post_data['password'];
			$param_array['orHallRebate'] = 0;
		}
		else if($method == 'LoginGame')
		{
			$url .= '/forward_game';
			$param_array['agent'] = $arr['AgentId'];
			$param_array['client'] = $post_data['username'];
			$param_array['password'] = $post_data['password'];
			$param_array['language'] = 'en';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $param_array['language'] = 'zh_CN'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $param_array['language'] = 'zh_TW'; break;
				case LANG_ID: $param_array['language'] = 'id'; break;
				case LANG_TH: $param_array['language'] = 'th'; break;
				case LANG_VI: $param_array['language'] = 'vi'; break;
				case LANG_JA: $param_array['language'] = 'ja'; break;
				case LANG_KO: $param_array['language'] = 'ko'; break;
			}
			
			if($post_data['game_type_code'] == GAME_FISHING)
			{
				$url .= '/forward_egame';
				$param_array['egameType'] = 'af';
				$param_array['gameType'] = '1100';
			}
			
			if( ! empty($post_data['return_url']))
			{
				$param_array['returnUrl'] = $post_data['return_url'];
			}
		}
		else if($method == 'GetBalance')
		{
			$url .= '/get_balance';
			$param_array['client'] = $post_data['username'];
			$param_array['password'] = $post_data['password'];
		}
		else if($method == 'ChangeBalance')
		{
			$url .= '/agent_client_transfer';
			$param_array['agent'] = $arr['AgentId'];
			$param_array['sn'] = $arr['PropertyID'] . substr(str_replace(array($post_data['username'], 'IN', 'OUT'), array('', '', ''), $post_data['order_id']), 0, -1);
			$param_array['client'] = $post_data['username'];
			
			if($post_data['amount'] > 0) 
			{
				$param_array['operFlag'] = 1;
				$param_array['credit'] = $post_data['amount'];
			}
			else
			{
				$param_array['operFlag'] = 0;
				$param_array['credit'] = bcdiv(($post_data['amount'] * -1), 1, 2);
			}
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/logout_game';
			$param_array['client'] = $post_data['username'];
		}
		
		$real_param = http_build_query($param_array);
		
		$this->load->library('triple_des');
		$encrypt_data = $this->triple_des->encrypt_text($this->triple_des->pkcs5_pad($real_param, 8), $arr['DESKey']);
		$to_sign = $encrypt_data . $arr['MD5Key'];
		
		$param_array_2 = array(
			"data" => $encrypt_data,
			"sign" => base64_encode(md5($to_sign, TRUE)),
			"propertyId" => $arr['PropertyID']
		);
		
		//Get response from curl
		$response = $this->curl_post($url, $param_array_2);
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['error_code']) && $result_array['error_code'] == 'OK')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == 'CLIENT_EXIST')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
					$output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['error_code']) && $result_array['error_code'] == 'OK')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $result_array['gameLoginUrl'];
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == 'CLIENT_NOT_EXIST')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['error_code']) && $result_array['error_code'] == 'OK')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['balance'], 1, 2);
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == 'CLIENT_NOT_EXIST')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['error_code']) && $result_array['error_code'] == 'OK')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = 0;
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == 'CLIENT_NOT_EXIST')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == 'LACK_OF_MONEY')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['error_code']) && $result_array['error_code'] == 'OK')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == 'CLIENT_NOT_EXIST')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "MerchantCode":"", "DESKey":"", "SHA256Key":"", "ProductType":"", "ProductName":"", "Prefix":""}
	private function ag_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		
		$param_array = array(
			"username" => $post_data['username']
		);
		
		if($method == 'CreateMember')
		{
			$param_array['method'] = 'cm';
			$param_array['password'] = $post_data['password'];
			$param_array['currency'] = $sys_data['system_currency'];
		}
		else if($method == 'LoginGame')
		{
			$param_array['method'] = 'lg';
			$param_array['product_type'] = $arr['ProductType'];
			$param_array['platform'] = 'html5';
			$param_array['game_mode'] = 1;
			$param_array['game_code'] = $post_data['game_code'];
			
			if($post_data['game_type_code'] == GAME_LIVE_CASINO)
			{
				$param_array['game_code'] = 'A00234';
			}
			
			$language = 'EN';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN:
				case LANG_ZH_HK:
				case LANG_ZH_TW: $language = 'ZH_CN'; break;
				case LANG_ID: $language = 'ID'; break;
				case LANG_TH: $language = 'TH'; break;
				case LANG_VI: $language = 'VN'; break;
				case LANG_JA: $language = 'JA'; break;
			}
		
			$param_array['language'] = $language;
		}
		else if($method == 'GetBalance')
		{
			$param_array['method'] = 'gb';
			$param_array['product_type'] = $arr['ProductType'];
		}
		else if($method == 'ChangeBalance')
		{
			$param_array['method'] = 'ft';
			$param_array['product_type'] = $arr['ProductType'];
			$param_array['reference_no'] = $post_data['order_id'];
			
			if($post_data['amount'] > 0) 
			{
				$param_array['fund_type'] = 1;
				$param_array['amount'] = $post_data['amount'];
			}
			else
			{
				$param_array['fund_type'] = 2;
				$param_array['amount'] = bcdiv(($post_data['amount'] * -1), 1, 2);
			}
		}
		
		if($method == 'LogoutGame')
		{
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
		}
		else
		{
			$this->load->library('des_ecb');
			$params = $this->des_ecb->encrypt_text($this->des_ecb->pkcs5_pad(json_encode($param_array), 8), $arr['DESKey']);
			$sign = hash('sha256', $params . $arr['SHA256Key']);
			
			$param_array_2 = array(
								'merchant_code' => $arr['MerchantCode'],
								'params' => $params,
								'sign' => $sign
							);
			
			//Get response from curl
			$response = $this->curl_post($url, $param_array_2);
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['status']) && $result_array['status'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}
				}
				else if($method == 'LoginGame')
				{
					if(isset($result_array['status']) && $result_array['status'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = $result_array['game_url'];
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['status']) && $result_array['status'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['Balance'], 1, 2);
					}
					else if(isset($result_array['status']) && $result_array['status'] == '15')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['status']) && $result_array['status'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = 0;
					}
					else if(isset($result_array['status']) && $result_array['status'] == '15')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['status']) && ($result_array['status'] == '11' OR $result_array['status'] == '16'))
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
			}
		}	
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	#{"APIUrl":"", "ForwardUrl":"", "CAgent":"", "EncryptKey":"", "MD5Key":"","OddType":""}
	private function ag2_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL){
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);

		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$result_array = array();
		$param_array = array(
			'cagent' => $arr['CAgent'],
			'loginname' => $post_data['username'],
			'password' => $post_data['password'],
			'actype' => 1,
			'cur' => $sys_data['system_currency'],
		);

		if($method == 'CreateMember')
		{
			$param_array['method'] = "lg";
			$param_array['oddtype'] = $arr['OddType'];
		}	
		else if($method == 'LoginGame')
		{
			$language = 3;
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $language = 1; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $language = 2; break;
				case LANG_ID: $language = 11; break;
				case LANG_TH: $language = 6; break;
				case LANG_VI: $language = 8; break;
				case LANG_JA: $language = 4; break;
				case LANG_KO: $language = 5; break;
				case LANG_KM: $language = 9; break;
			}

			$param_array['dm'] = base_url(array('.', '..', ''));
			$param_array['sid'] = $arr['CAgent'].str_replace(array($post_data['username'], 'IN', 'OUT'), array('', '', ''), $post_data['order_id']).rand(10, 99);
			$param_array['lang'] = $language;
			$param_array['oddtype'] = $arr['OddType'];
			$param_array['gameType'] = $post_data['game_code'];
			if($post_data['game_type_code'] == GAME_LIVE_CASINO)
			{
				$param_array['gameType'] = 0;
			}
		}
		else if($method == 'GetBalance')
		{
			$param_array['method'] = "gb";
		}
		else if($method == 'ChangeBalance')
		{
			$requestOrderIDAlias = $arr['CAgent'].str_replace(array($post_data['username'], 'IN', 'OUT'), array('', '', ''), $post_data['order_id']);
			$param_array['method'] = "tc";
			$param_array['billno'] = $requestOrderIDAlias;

			if($post_data['amount'] > 0) 
			{
				$param_array['type'] = "IN";
				$param_array['credit'] = $post_data['amount'];
			}
			else
			{
				$param_array['type'] = "OUT";
				$param_array['credit'] = bcdiv(($post_data['amount'] * -1), 1, 2);
			}
		}

		if($method == 'LogoutGame')
		{
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
		}else if($method == 'LoginGame'){
			$game_url = $arr['ForwardUrl'];
			$this->load->library('des_ecb');
			$param_string = http_build_query($param_array, '', '/\\\\\\\\/');
			$params = $this->des_ecb->encrypt_text($this->des_ecb->pkcs5_pad($param_string, 8), $arr['EncryptKey']);
			$key = md5($params.$arr['MD5Key']);
			$game_url .= "?params=".$params."&key=".$key;

			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
			$output['result'] = $game_url;
		}else{
			$this->load->library('des_ecb');
			$param_string = http_build_query($param_array, '', '/\\\\\\\\/');
			$params = $this->des_ecb->encrypt_text($this->des_ecb->pkcs5_pad($param_string, 8), $arr['EncryptKey']);
			$key = md5($params.$arr['MD5Key']);
			$url .= "?params=".$params."&key=".$key;
			$response = $this->curl_get($url);
			if($response['code'] == '0')
			{
				$xml = simplexml_load_string($response['data']);
				$json = json_encode($xml);
				$result_array = json_decode($json, TRUE);

				if($method == 'CreateMember')
				{
					if(array_key_exists('@attributes',$result_array))
					{
						if(array_key_exists('info',$result_array['@attributes']) && $result_array['@attributes']['info'] == "0")
						{		
							$output['errorCode'] = ERROR_SUCCESS;
							$output['errorMessage'] = $this->lang->line('error_success');
						}
					}
				}
				else if($method == 'GetBalance')
				{
					if(array_key_exists('@attributes',$result_array))
					{
						if(array_key_exists('info',$result_array['@attributes']) && $result_array['@attributes']['info'] == "key_error")
						{
							$output['errorCode'] = ERROR_SYSTEM_ERROR;
							$output['errorMessage'] = $this->lang->line('error_system_error');
						}
						else if(array_key_exists('info',$result_array['@attributes']) && $result_array['@attributes']['info'] == "network_error")
						{		
							$output['errorCode'] = ERROR_SYSTEM_ERROR;
							$output['errorMessage'] = $this->lang->line('error_system_error');
						}
						else if(array_key_exists('info',$result_array['@attributes']) && $result_array['@attributes']['info'] == "error")
						{		
							$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
							$output['errorMessage'] = $this->lang->line('error_username_not_found');
						}
						else if(array_key_exists('info',$result_array['@attributes']) && $result_array['@attributes']['info'] == "0")
						{		
							$output['errorCode'] = ERROR_SUCCESS;
							$output['errorMessage'] = $this->lang->line('error_success');
							$output['result'] = bcdiv(0, 1, 2);
						}
						else{
							$output['errorCode'] = ERROR_SUCCESS;
							$output['errorMessage'] = $this->lang->line('error_success');
							$output['result'] = bcdiv($result_array['@attributes']['info'], 1, 2);
						}
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(array_key_exists('@attributes',$result_array))
					{
						if(array_key_exists('info',$result_array['@attributes']) && $result_array['@attributes']['info'] == "0")
						{
							$url = $arr['APIUrl'];
							$param_array['method'] = 'tcc';
							$param_array['flag'] = 1;
							$param_string = http_build_query($param_array, '', '/\\\\\\\\/');
							$params = $this->des_ecb->encrypt_text($this->des_ecb->pkcs5_pad($param_string, 8), $arr['EncryptKey']);
							$key = md5($params.$arr['MD5Key']);
							$url .= "?params=".$params."&key=".$key;
							$response2 = $this->curl_get($url);
							if($response2['code'] == '0')
							{
								$xml2 = simplexml_load_string($response2['data']);
								$json2 = json_encode($xml2);
								$result_array2 = json_decode($json, TRUE);
								if(array_key_exists('@attributes',$result_array2))
								{
									if(array_key_exists('info',$result_array2['@attributes']) && $result_array2['@attributes']['info'] == "0")
									{
										$output['errorCode'] = ERROR_SUCCESS;
										$output['errorMessage'] = $this->lang->line('error_success');
										$output['result'] = 0;
									}
									else if(array_key_exists('info',$result_array2['@attributes']) && $result_array2['@attributes']['info'] == "1")
									{
										$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
										$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
									}
									else if(array_key_exists('info',$result_array2['@attributes']) && $result_array2['@attributes']['info'] == "2")
									{
										$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
										$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
									}
								}
							}
						}
						else if(array_key_exists('info',$result_array['@attributes']) && $result_array['@attributes']['info'] == "not_enough_credit")
						{
							$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
							$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
						}
						else if(array_key_exists('info',$result_array['@attributes']) && $result_array['@attributes']['info'] == "error")
						{
							$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
							$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
						}
					}
				}
			}
		}

		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		return $output;
	}
	
	#{"APIUrl":"", "APIUrl2":"", "ForwardUrl":"", "Website":"", "Account":"", "Suffix":""}
	private function bbin_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		
		$param_array = array(
			"website" => $arr['Website'],
			"username" => $post_data['username'] . $arr['Suffix']
		);
		
		$current_date = date('Ymd', strtotime('-12 hours', time()));
		
		if($method == 'CreateMember')
		{
			$url .= '/app/WebService/JSON/display.php/CreateMember';
			$param_array['uppername'] = $arr['Account'];
			$param_array['ingress'] = (($post_data['device'] == PLATFORM_WEB) ? 1 : 2);
			$param_array['key'] = rand(100000000, 999999999) . md5($arr['Website'] . $param_array['username'] . 'wMGF34' . $current_date) . rand(10000, 99999);
		}
		else if($method == 'LoginGame' && $post_data['game_type_code'] != GAME_LIVE_CASINO)
		{
			$url = $arr['APIUrl2'] . '/app/WebService/JSON/display.php/Login2';
			$param_array['uppername'] = $arr['Account'];
			$param_array['lang'] = 'en-us';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $param_array['lang'] = 'zh-cn'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $param_array['lang'] = 'zh-tw'; break;
				case LANG_TH: $param_array['lang'] = 'th'; break;
				case LANG_VI: $param_array['lang'] = 'vi'; break;
				case LANG_JA: $param_array['lang'] = 'euc-jp'; break;
				case LANG_KO: $param_array['lang'] = 'ko'; break;
			}
			
			$param_array['ingress'] = (($post_data['device'] == PLATFORM_WEB) ? 1 : 2);
			$param_array['key'] = rand(100000, 999999) . md5($arr['Website'] . $param_array['username'] . 'i23Pr' . $current_date) . rand(10, 99);
		}
		else if($method == 'GetBalance')
		{
			$url .= '/app/WebService/JSON/display.php/CheckUsrBalance';
			$param_array['uppername'] = $arr['Account'];
			$param_array['key'] = rand(100000000, 999999999) . md5($arr['Website'] . $param_array['username'] . 'xgM46W6' . $current_date) . rand(100000000, 999999999);
		}
		else if($method == 'ChangeBalance')
		{
			$url .= '/app/WebService/JSON/display.php/Transfer';
			$param_array['uppername'] = $arr['Account'];
			$param_array['remitno'] = str_replace(array($post_data['username'], 'IN', 'OUT'), array('', '', ''), $post_data['order_id']);
			$param_array['key'] = rand(10000, 99999) . md5($arr['Website'] . $param_array['username'] . $param_array['remitno'] . 'l2o2a0' . $current_date) . rand(100000000, 999999999);
			
			if($post_data['amount'] > 0) 
			{
				$param_array['action'] = 'IN';
				$param_array['remit'] = (int)$post_data['amount'];
			}
			else
			{
				$param_array['action'] = 'OUT';
				$param_array['remit'] = (int)bcdiv(($post_data['amount'] * -1), 1, 2);
			}
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/app/WebService/JSON/display.php/Logout';
			$param_array['key'] = rand(1, 9) . md5($arr['Website'] . $param_array['username'] . '7Rb6XfE' . $current_date) . rand(1000, 9999);
		}
		
		if($method == 'LoginGame' && $post_data['game_type_code'] == GAME_LIVE_CASINO)
		{
			$uppername = $arr['Account'];
			$lang = 'en-us';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $lang = 'zh-cn'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $lang = 'zh-tw'; break;
				case LANG_TH: $lang = 'th'; break;
				case LANG_VI: $lang = 'vi'; break;
				case LANG_JA: $lang = 'euc-jp'; break;
				case LANG_KO: $lang = 'ko'; break;
			}
			
			$ingress = (($post_data['device'] == PLATFORM_WEB) ? 1 : 2);
			
			$key = rand(100000, 999999) . md5($arr['Website'] . $param_array['username'] . 'i23Pr' . $current_date) . rand(10, 99);
			$game_url = $arr['APIUrl2'] . '/app/WebService/JSON/display.php/Login?website=' . $param_array['website'] . '&username=' . $param_array['username'] . '&uppername=' . $uppername . '&lang=' . $lang . '&page_site=live&page_present=live&ingress=' . $ingress . '&key=' . $key;
			
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
			$output['result'] = $game_url ;
		}
		else
		{
			//Get response from curl
			$response = $this->curl_post($url, $param_array);
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['data']['Code']) && $result_array['data']['Code'] == '21100')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}
					else if(isset($result_array['data']['Code']) && $result_array['data']['Code'] == '21001')
					{
						$output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
				}
				else if($method == 'LoginGame')
				{
					if(isset($result_array['data']['Code']) && $result_array['data']['Code'] == '99999')
					{
						$num = substr($post_data['game_code'], 0, 2);
						switch($num)
						{
							case 30: $gamekind = 30; break;
							case 38: $gamekind = 38; break;
							default: $gamekind = 5; break;
						}
			
						$key = rand(10000, 99999) . md5($arr['Website'] . $param_array['username'] . 'DGp5636' . $current_date) . rand(1000000, 9999999);
						$game_url = $arr['APIUrl2'] . '/app/WebService/JSON/display.php/PlayGameByH5?website=' . $param_array['website'] . '&username=' . $param_array['username'] . '&gamekind=' . $gamekind . '&gametype=' . $post_data['game_code'] . '&gamecode=&lang=' . $param_array['lang'] . '&key=' . $key;
						
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = $game_url ;
					}
					else if(isset($result_array['error_code']) && $result_array['error_code'] == 'CLIENT_NOT_EXIST')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['data'][0]['LoginName']))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['data'][0]['Balance'], 1, 2);
					}
					else if(isset($result_array['data']['Code']) && $result_array['data']['Code'] == '22002')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['data']['Code']) && $result_array['data']['Code'] == '11100')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = 0;
					}
					else if(isset($result_array['data']['Code']) && $result_array['data']['Code'] == '10002')
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
				else if($method == 'LogoutGame')
				{
					if(isset($result_array['data']['Code']) && ($result_array['data']['Code'] == '22000' OR $result_array['data']['Code'] == '22001'))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}
					else if(isset($result_array['data']['Code']) && $result_array['data']['Code'] == '22002')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
			}
		}	
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "AccessKeyId":"", "AccessKeySecret":""}
	private function bl_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$this->load->library('rng');
		
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		
		$param_array = array(
			"player_account" => $post_data['username'],
			"AccessKeyId" => $arr['AccessKeyId'],
			"Timestamp" => time(),
			"Nonce" => $this->rng->get_token(128)
		);
		
		if($method == 'CreateMember' || $method == 'LoginGame')
		{
			$url .= '/v1/player/login';
			
			if($post_data['game_type_code'] == GAME_SLOTS && ! empty($post_data['game_code']))
			{
				$param_array['game_code'] = 'slot';
				$param_array['scene'] = $post_data['game_code'];
			}
			else if($post_data['game_type_code'] == GAME_BOARD_GAME && ! empty($post_data['game_code']))
			{
				$param_array['game_code'] = $post_data['game_code'];
			}
			
			$param_array['line_code'] = $sys_data['system_prefix'];
			$param_array['country'] = $sys_data['system_country'];
			$param_array['ip'] = $this->input->ip_address();
			
			if( ! empty($post_data['return_url']))
			{
				$param_array['op_return_type'] = 2;
				$param_array['op_home_url'] = $post_data['return_url'];
			}
		}
		else if($method == 'GetBalance')
		{
			$url .= '/v1/player/get_info';
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$url .= '/v1/order/coin_in';
				$param_array['amount'] = $post_data['amount'];
			}
			else
			{
				$url .= '/v1/order/coin_out';
				$param_array['amount'] = bcdiv(($post_data['amount'] * -1), 1, 2);
			}
			
			$param_array['operator_order_id'] = $post_data['order_id'];
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/v1/player/logout';
		}
		
		$param_array['Sign'] = strtolower(sha1($arr['AccessKeySecret'] . $param_array['Nonce'] . $param_array['Timestamp']));
		
		//Get response from curl
		$response = $this->curl_post($url, $param_array);
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if(isset($result_array['resp_msg']['code']) && $result_array['resp_msg']['code'] == '200')
			{
				$output['errorCode'] = ERROR_SUCCESS;
				$output['errorMessage'] = $this->lang->line('error_success');
				
				if($method == 'CreateMember' || $method == 'LoginGame')
				{
					$output['result'] = $result_array['resp_data']['url'];
				}
				else if($method == 'GetBalance')
				{
					$output['result'] = bcdiv($result_array['resp_data']['gold'], 1, 2);
				}
				else if($method == 'ChangeBalance')
				{
					$output['result'] = bcdiv($result_array['resp_data']['player_own_gold'], 1, 2);
				}
			}
			else if(isset($result_array['resp_msg']['code']) && $result_array['resp_msg']['code'] == '43101')
			{
				$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				$output['errorMessage'] = $this->lang->line('error_username_not_found');
			}
			else if(isset($result_array['resp_msg']['code']) && $result_array['resp_msg']['code'] == '43802')
			{
				$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
				$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "PartnerKey":"", "WebRoot":"", "MobileRoot":"", "MobileNewRoot":""}
	private function cmd_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$param_array = array(
			"PartnerKey" => $arr['PartnerKey'],
			"UserName" => $post_data['username']
		);
		
		if($method == 'CreateMember')
		{
			$param_array['Method'] = 'createmember';
			$param_array['Currency'] = $sys_data['system_currency'];
		}
		else if($method == 'GetBalance')
		{
			$param_array['Method'] = 'getbalance';
		}
		else if($method == 'ChangeBalance')
		{
			$param_array['Method'] = 'balancetransfer';
			$param_array['TicketNo'] = $post_data['order_id'];
			
			if($post_data['amount'] > 0) 
			{
				$param_array['PaymentType'] = 1;
				$param_array['Money'] = $post_data['amount'];
			}
			else
			{
				$param_array['PaymentType'] = 0;
				$param_array['Money'] = bcdiv(($post_data['amount'] * -1), 1, 2);
			}
		}
		else if($method == 'LogoutGame')
		{
			$param_array['Method'] = 'kickuser';
		}
		
		//Login Game
		if($method == 'LoginGame')
		{
			$url = (($post_data['device'] == PLATFORM_WEB) ? $arr['WebRoot'] : $arr['MobileRoot']);
			
			$language = 'en-US';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $language = 'zh-CN'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $language = 'zh-TW'; break;
				case LANG_ID: $language = 'id-ID'; break;
				case LANG_TH: $language = 'th-TH'; break;
				case LANG_VI: $language = 'vi-VN'; break;
				case LANG_KO: $language = 'ko-KR'; break;
			}
			
			if($post_data['is_demo'] == STATUS_YES)
			{
				$output['errorCode'] = ERROR_SUCCESS;
				$output['errorMessage'] = $this->lang->line('error_success');
				$output['result'] = $url . '/auth.aspx?lang=' . $language . '&templatename=' . $post_data['theme'];
			}
			else
			{
				$player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['username']);
				if( ! empty($player_acc_data))
				{
					$this->load->library('rng');
					$partner_member_token = $this->rng->get_token(50);
					$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], $partner_member_token);
					
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $url . '/auth.aspx?lang=' . $language . '&user=' . $post_data['username'] . '&token=' . $partner_member_token . '&currency=' . $sys_data['system_currency'] . '&templatename=' . $post_data['theme'] . '&view=v1';
				}
				else
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		else
		{
			$url = $arr['APIUrl'] . '/SportsApi.aspx?' . http_build_query($param_array);
			
			//Get response from curl
			$response = $this->curl_get($url);
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['Code']) && $result_array['Code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$this->player_model->add_player_game_token($post_data['provider_code'], $post_data['username']);
					}
					else if(isset($result_array['Code']) && $result_array['Code'] == '-98')
					{
						$output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['Code']) && $result_array['Code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['Data'][0]['BetAmount'], 1, 2);
					}
					else if(isset($result_array['Code']) && $result_array['Code'] == '-97')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['Code']) && $result_array['Code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['Data']['BetAmount'], 1, 2);
					}
					else if(isset($result_array['Code']) && $result_array['Code'] == '-97')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['Code']) && $result_array['Code'] == '-8037')
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
				else if($method == 'LogoutGame')
				{
					if(isset($result_array['Code']) && $result_array['Code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], '');
					}
					else if(isset($result_array['Code']) && $result_array['Code'] == '-97')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "AgentName":"", "APIKey":"", "MobilePostfix":"", "LimitGroup":""}
	private function dg_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$random = rand(100000, 999999);
		$key = md5($arr['AgentName'] . $arr['APIKey'] . $random);
		
		$param_array = array(
							'token' => $key,
							'random' => $random,
						);
		
		if($method == 'CreateMember')
		{
			$url .= '/user/signup/' . $arr['AgentName'];
			$param_array['data'] = $arr['LimitGroup'];
			$param_array['member'] = array(
										'username' => $post_data['username'],
										'password' => md5($post_data['password']),
										'currencyName' => $sys_data['system_currency'],
										'winLimit' => 0,
									);
		}
		else if($method == 'LoginGame')
		{
			if($post_data['is_demo'] == STATUS_YES)
			{
				$url .= '/user/free/' . $arr['AgentName'];
			}
			else
			{
				$url .= '/user/login/' . $arr['AgentName'];
				
				$param_array['member'] = array(
										'username' => $post_data['username'],
										'password' => $post_data['password'],
									);
			}
			
			$param_array['domains'] = 1;
			$param_array['lang'] = 'en';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $param_array['lang'] = 'cn'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $param_array['lang'] = 'tw'; break;
				case LANG_TH: $param_array['lang'] = 'th'; break;
				case LANG_VI: $param_array['lang'] = 'vi'; break;
				case LANG_MY: $param_array['lang'] = 'my'; break;
				case LANG_KO: $param_array['lang'] = 'kr'; break;
			}
		}
		else if($method == 'GetBalance')
		{
			$url .= '/user/getBalance/' . $arr['AgentName'];
			$param_array['member'] = array(
										'username' => $post_data['username']
									);
		}
		else if($method == 'ChangeBalance')
		{
			$url .= '/account/transfer/' . $arr['AgentName'];
			$param_array['data'] = $post_data['order_id'];
			$param_array['member'] = array(
										'username' => $post_data['username'],
										'amount' => $post_data['amount']
									);
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/user/onlineReport/' . $arr['AgentName'];
		}
		
		//Get response from curl
		$response = $this->curl_json($url, $param_array);
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['codeId']) && $result_array['codeId'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['codeId']) && $result_array['codeId'] == '116')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
					$output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['codeId']) && $result_array['codeId'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = (($post_data['device'] == PLATFORM_WEB) ? $result_array['list'][0] : $result_array['list'][1]) . $result_array['token'] . '&language=' . $param_array['lang'];
				}
				else if(isset($result_array['codeId']) && $result_array['codeId'] == '102')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['codeId']) && $result_array['codeId'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['member']['balance'], 1, 2);
				}
				else if(isset($result_array['codeId']) && $result_array['codeId'] == '114')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['codeId']) && $result_array['codeId'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['member']['balance'], 1, 2);
				}
				else if(isset($result_array['codeId']) && $result_array['codeId'] == '114')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['codeId']) && $result_array['codeId'] == '120')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['codeId']) && $result_array['codeId'] == '0')
				{
					if(isset($result_array['list']))
					{
						$member_id = 0;
						for($i=0;$i<sizeof($result_array['list']);$i++)
						{
							if(strtolower($result_array['list'][$i]['username']) == strtolower($post_data['username']))
							{
								$member_id = $result_array['list'][$i]['memberId'];
								break;
							}
						}
						
						if($member_id > 0)
						{
							$url = $arr['APIUrl'] . '/user/offline/' . $arr['AgentName'];
							$random = rand(100000, 999999);
							$key = md5($arr['AgentName'] . $arr['APIKey'] . $random);
							
							$param_array = array(
												'token' => $key,
												'random' => $random,
												'list' => array($member_id),
											);
							
							$this->curl_json($url, $param_array);
						}
					}
					
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "BusinessCode":"", "APIKey":"", "Currency":""}
	private function dt_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$param_array = array(
							'BUSINESS' => $arr['BusinessCode'],
							'PLAYERNAME' => strtoupper($post_data['username'])
						);
		
		if($method == 'CreateMember')
		{
			$param_array['METHOD'] = 'CREATE';
			$param_array['PLAYERPASSWORD'] = $post_data['password'];
			$param_array['CURRENCY'] = $arr['Currency'];
			$param_array['SIGNATURE'] = md5($param_array['BUSINESS'] . $param_array['METHOD'] . $param_array['PLAYERNAME'] . $param_array['PLAYERPASSWORD'] . $arr['APIKey'] . $param_array['CURRENCY']);
		}
		else if($method == 'LoginGame')
		{
			$param_array['METHOD'] = 'LOGIN';
			$param_array['PLAYERPASSWORD'] = $post_data['password'];
			$param_array['SIGNATURE'] = md5($param_array['BUSINESS'] . $param_array['METHOD'] . $param_array['PLAYERNAME'] . $param_array['PLAYERPASSWORD'] . $arr['APIKey']);
		}
		else if($method == 'GetBalance')
		{
			$param_array['METHOD'] = 'GETAMOUNT';
			$param_array['SIGNATURE'] = md5($param_array['BUSINESS'] . $param_array['METHOD'] . $param_array['PLAYERNAME'] . $arr['APIKey']);
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$param_array['METHOD'] = 'DEPOSIT';
				$param_array['PRICE'] = $post_data['amount'];
				$param_array['TRANSFER_ID'] = $post_data['order_id'];
				$param_array['CURRENCY'] = $arr['Currency'];
				$param_array['SIGNATURE'] = md5($param_array['BUSINESS'] . $param_array['METHOD'] . $param_array['PLAYERNAME'] . $param_array['PRICE'] . $arr['APIKey'] . $param_array['CURRENCY']);
			}
			else
			{
				$param_array['METHOD'] = 'WITHDRAW';
				$param_array['PRICE'] = bcdiv(($post_data['amount'] * -1), 1, 2);
				$param_array['TRANSFER_ID'] = $post_data['order_id'];
				$param_array['CURRENCY'] = $arr['Currency'];
				$param_array['SIGNATURE'] = md5($param_array['BUSINESS'] . $param_array['METHOD'] . $param_array['PLAYERNAME'] . $param_array['PRICE'] . $arr['APIKey'] . $param_array['CURRENCY']);
			}
		}
		else if($method == 'LogoutGame')
		{
			$param_array['METHOD'] = 'LOGINOUT';
			$param_array['SIGNATURE'] = md5($param_array['BUSINESS'] . $param_array['METHOD'] . $param_array['PLAYERNAME'] . $arr['APIKey']);
		}
		
		//Get response from curl
		$response = $this->curl_post($url, $param_array);
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['RESPONSECODE']) && $result_array['RESPONSECODE'] == '00000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['RESPONSECODE']) && $result_array['RESPONSECODE'] == '000011')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
					$output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['RESPONSECODE']) && $result_array['RESPONSECODE'] == '00000')
				{
					$language = 'en_US';
			
					switch($post_data['syslang'])
					{
						case LANG_ZH_CN:
						case LANG_ZH_HK:
						case LANG_ZH_TW: $language = 'zh_CN'; break;
						case LANG_TH: $language = 'th_TH'; break;
					}
					
					$client_type = 0;
					$return_url = 'null';
					if( ! empty($post_data['return_url']))
					{
						$client_type = 1;
						$return_url = $post_data['return_url'];
					}
					
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					
					if($post_data['is_demo'] == STATUS_YES)
					{
						$output['result'] = 'https://nplay.dreamtech8.com/playSlot.aspx?gameCode=' . $post_data['game_code'] . '&isfun=1&type=dt&language=' . $language . '&clientType=' . $client_type . '&closeUrl=' . $return_url;
					}
					else
					{
						$output['result'] = $result_array['gameurl'] .= '?slotKey=' . $result_array['slotKey'] . '&language=' . $language . '&gameCode=' . $post_data['game_code'] . '&isfun=0&clientType=' . $client_type . '&closeUrl=' . $return_url;
					}
				}
				else if(isset($result_array['RESPONSECODE']) && $result_array['RESPONSECODE'] == '000012')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['RESPONSECODE']) && $result_array['RESPONSECODE'] == '00000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['AMOUNT'], 1, 2);
				}
				else if(isset($result_array['RESPONSECODE']) && $result_array['RESPONSECODE'] == '000012')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['RESPONSECODE']) && $result_array['RESPONSECODE'] == '00000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = 0;
				}
				else if(isset($result_array['RESPONSECODE']) && $result_array['RESPONSECODE'] == '000012')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['RESPONSECODE']) && $result_array['RESPONSECODE'] == '000019')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['RESPONSECODE']) && $result_array['RESPONSECODE'] == '00000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['RESPONSECODE']) && $result_array['RESPONSECODE'] == '000012')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "ForwardUrl":"", "PublicKey":"", "PrivateKey":"", "ChannelID":"", "Currency":""}
	private function eb_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
				
		include_once(APPPATH . 'third_party/phpseclib/Crypt/RSA.php');
		$rsa = new Crypt_RSA(); 
		$rsa->loadKey($arr['PrivateKey']);
		$rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1); 
		$rsa->setHash("md5");		
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$param_array = array(
							'username' => strtolower($post_data['username']),
							'channelId' => $arr['ChannelID']
						);
		
		if($method == 'CreateMember')
		{
			$url .= 'syncuser';
			
			$signature = $rsa->sign($param_array['username']);
			$param_array['signature'] = base64_encode($signature);
			$param_array['subChannelId'] = 0;
			$param_array['currency'] = $arr['Currency'];
		}
		else if($method == 'GetBalance')
		{
			$url .= 'userinfo';
			
			$timestamp = time();
			$signature = $rsa->sign($param_array['username'] . $timestamp);
			$param_array['signature'] = base64_encode($signature);
			$param_array['timestamp'] = $timestamp;
			$param_array['currency'] = $arr['Currency'];
		}
		else if($method == 'ChangeBalance')
		{
			$url .= 'recharge';
			
			$timestamp = time();
			$signature = $rsa->sign($param_array['username'] . $timestamp);
			$param_array['money'] = $post_data['amount'];
			$param_array['rechargeReqId'] = $post_data['order_id'];
			$param_array['signature'] = base64_encode($signature);
			$param_array['timestamp'] = $timestamp;
			$param_array['typeId'] = 0;
			$param_array['currency'] = $arr['Currency'];
		}
		else if($method == 'LogoutGame')
		{
			$url .= 'logout';
			
			$timestamp = time();
			$signature = $rsa->sign($param_array['username'] . $param_array['channelId'] . $timestamp);
			$param_array['signature'] = base64_encode($signature);
			$param_array['timestamp'] = $timestamp;
			$param_array['currency'] = $arr['Currency'];
		}
		
		//Login game
		if($method == 'LoginGame')
		{
			$lang = 'en_us';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $lang = 'zh_cn'; break;
				case LANG_ZH_HK: $lang = 'zh_hk'; break;
				case LANG_ZH_TW: $lang = 'zh_tw'; break;
				case LANG_ID: $lang = 'in_id'; break;
				case LANG_TH: $lang = 'th_th'; break;
				case LANG_VI: $lang = 'vi_vn'; break;
				case LANG_KM: $lang = 'km_kh'; break;
				case LANG_MY: $lang = 'my_mm'; break;
				case LANG_MS: $lang = 'ms_my'; break;
				case LANG_JA: $lang = 'ja_jp'; break;
				case LANG_KO: $lang = 'ko_kr'; break;
				case LANG_HI: $lang = 'hi_in'; break;
			}
			
			$game_type = '';
			if($post_data['game_type_code'] == GAME_SLOTS && ! empty($post_data['game_code']))
			{
				$game_type = '&gameType=5&tableCode=' . $post_data['game_code'];
			}
			
			$return_url = '';
			if( ! empty($post_data['return_url']))
			{
				$return_url = '&exitUrl=' . urlencode($post_data['return_url']);
			}
			
			if($post_data['is_demo'] == STATUS_YES)
			{
				$output['errorCode'] = ERROR_SUCCESS;
				$output['errorMessage'] = $this->lang->line('error_success');
				$output['result'] = $arr['ForwardUrl'] . '?language=' . $lang . '&mode=trial' . $game_type . $return_url;
			}
			else
			{
				$player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['username']);
				if( ! empty($player_acc_data))
				{
					$this->load->library('rng');
					$partner_member_token = $this->rng->get_token(64);
					$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], $partner_member_token);
					
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $arr['ForwardUrl'] . '?username=' . $post_data['username'] . '&accessToken=' . $partner_member_token . '&language=' . $lang . $game_type . $return_url;
				}
				else
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		else
		{
			//Get response from curl
			$response = $this->curl_json($url, $param_array);
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['status']) && $result_array['status'] == 200)
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$this->player_model->add_player_game_token($post_data['provider_code'], $post_data['username']);
					}
					else if(isset($result_array['status']) && $result_array['status'] == 401)
					{
						$output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['status']) && $result_array['status'] == 200)
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['money'], 1, 2);
					}
					else if(isset($result_array['status']) && $result_array['status'] == 4037)
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['status']) && $result_array['status'] == 200)
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['money'], 1, 2);
					}
					else if(isset($result_array['status']) && $result_array['status'] == 4037)
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['status']) && $result_array['status'] == 5002)
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
				else if($method == 'LogoutGame')
				{
					if(isset($result_array['status']) && $result_array['status'] == 200)
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], '');
					}
					else if(isset($result_array['status']) && $result_array['status'] == 4037)
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
			}
		}	
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "CasinoKey":"", "APIToken":""}
	private function evo_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
				
		$url = $arr['APIUrl'];	
		$param_array = array();	
		$result_array = array();
		
		if($method == 'CreateMember' || $method == 'LoginGame')
		{
			$this->load->library('rng');
			
			$url .= "/ua/v1/" . $arr['CasinoKey'] . "/" . $arr['APIToken'];
			
			$uuid = md5($arr['CasinoKey'] . $arr['APIToken'] . $post_data['username'] . time());
			$language = 'en';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN:
				case LANG_ZH_HK:
				case LANG_ZH_TW: $language = 'zh'; break;
				case LANG_TH: $language = 'th'; break;
				case LANG_VI: $language = 'vi'; break;
				case LANG_JA: $language = 'ja'; break;
				case LANG_TR: $language = 'tr'; break;
			}
			
			$param_array = array (
									"uuid" => $uuid,
									"player" => array (
										"id" => $post_data['username'],
										"update" => true,
										"firstName" => $post_data['username'],
										"lastName" => $sys_data['system_prefix'],
										"nickname" => '',
										"country" => $sys_data['system_country'],
										"language" => $language,
										"currency" => $sys_data['system_currency'],
										"session" => array (
											"id" => $this->rng->get_token(32),
											"ip" => $this->input->ip_address()
										)
									),
									"config" => array (
										"brand" => array (
											"id" => 1,
											"skin" => 1
										)
									),
									"channel" => array (
										"wrapped" => false,
										"mobile" => (($post_data['device'] == PLATFORM_WEB) ? false : true)
									),
									"urls" => array (
										"lobby" => $post_data['return_url']
									)
								);
		}
		else if($method == 'GetBalance')
		{
			$url .= "/api/ecashier?cCode=RWA&ecID=" . $arr['CasinoKey'] . "&euID=" . $post_data['username'] . "&output=1"; 
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$url .= "/api/ecashier?cCode=ECR&ecID=" . $arr['CasinoKey'] . "&euID=" . $post_data['username'] . "&amount=" . $post_data['amount'] . "&eTransID=" . $post_data['order_id'] . "&createuser=N&output=1"; 
			}
			else
			{
				$url .= "/api/ecashier?cCode=EDB&ecID=" . $arr['CasinoKey'] . "&euID=" . $post_data['username'] . "&amount=" . bcdiv(($post_data['amount'] * -1), 1, 2) . "&eTransID=" . $post_data['order_id'] . "&output=1";
			}
		}
		
		if($method == 'LogoutGame')
		{
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
		}
		else
		{
			//Get response from curl
			if($method == 'CreateMember' || $method == 'LoginGame')
			{
				$response = $this->curl_json($url, $param_array);
			}
			else
			{
				$response = $this->curl_get($url);
			}
			
			if($response['code'] == '0')
			{
				if($method == 'CreateMember' || $method == 'LoginGame')
				{
					$result_array = json_decode($response['data'], TRUE);
				}
				else
				{
					$xml = simplexml_load_string($response['data']);
					$json = json_encode($xml);
					$result_array = json_decode($json, TRUE);
				}
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['entryEmbedded']) && ! empty($result_array['entryEmbedded']))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}
				}
				else if($method == 'LoginGame')
				{
					if(isset($result_array['entryEmbedded']) && ! empty($result_array['entryEmbedded']))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = $result_array['entryEmbedded'];
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['result']) && $result_array['result'] == 'Y')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['abalance'], 1, 2);
					}
					else if(isset($result_array['errormsg']) && strpos($result_array['errormsg'], "user") > 0)
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['result']) && $result_array['result'] == 'Y')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['balance'], 1, 2);
					}
					else if(isset($result_array['errormsg']) && strpos($result_array['errormsg'], "user") > 0)
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['errormsg']) && strpos($result_array['errormsg'], "funds") > 0)
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
			}
		}	
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}

	public function evo8_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL){
		$this->load->library('rng');
		$arr = json_decode($api_data, TRUE);
		$this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
		
		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);

		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$result_array = "";

		if($method == 'LoginGame'){
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
			$output['result'] = "";
		}else{
			if($method == 'CreateMember')
			{
				$output['gamePassword'] = "A".$post_data['password']."a";
				$url .= '/api?action=addUser&name=' . $post_data['username'] . '&passwd=' . $output['gamePassword']. '&time=' . $timestamp . '&tel=' . time() . '&type=0' . '&desc='.$post_data['username']. '&areaid='.$arr['AreaID'].'&authcode=' . $arr['Authcode'] . '&sign=' . md5(strtolower($arr['Authcode'] . $timestamp . $arr['SecretKey']));
			}
			else if($method == 'GetBalance')
			{
				$url .= '/api?action=searchUser&username=' . $post_data['game_id'] . '&time=' . $timestamp . '&type=0&authcode=' . $arr['Authcode'] . '&sign=' . md5(strtolower($arr['Authcode'] .$post_data['game_id'] . $timestamp . $arr['SecretKey']));
			}
			else if($method == 'ChangeBalance')
			{
				$url .= '/api?action=setScore&username=' . $post_data['game_id'] . '&time=' . $timestamp . '&type=0'. '&score=' . $post_data['amount']. '&authcode=' . $arr['Authcode'] . '&sign=' . md5(strtolower($arr['Authcode'] .$post_data['game_id'] . $timestamp . $arr['SecretKey']));
			}
			else if($method == 'LogoutGame')
			{
				$url .= '/api?action=quitgameuser&username=' . $post_data['game_id'] . '&time=' . $timestamp . '&authcode=' . $arr['Authcode'] . '&sign=' . md5(strtolower($arr['Authcode'] .$post_data['game_id'] . $timestamp . $arr['SecretKey']));
			}

			if($method == 'CreateMember'){
				$player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['username']);
				if( ! empty($player_acc_data))
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
				    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}else{
					$param_array['url'] = $url;
					$response = $this->curl_get($url);
					$response_data = $response['data'];
					$result_array = json_decode($response_data, TRUE);
					if(array_key_exists('code',$result_array) && $result_array['code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['gameID'] = $result_array['results']['username'];
						$this->player_model->add_player_game_token($post_data['provider_code'], $post_data['username']);
					}
				}
			}else{
				$param_array['url'] = $url;
				$response = $this->curl_get($url);
				if($response['code'] == '0')
				{
					$response_data = $response['data'];
					$result_array = json_decode($response_data, TRUE);
					if($method == 'GetBalance')
					{
						if(array_key_exists('code',$result_array) && $result_array['code'] == '0')
						{
							$output['errorCode'] = ERROR_SUCCESS;
							$output['errorMessage'] = $this->lang->line('error_success');
							$output['result'] = bcdiv($result_array['results']['balance'], 1, 2);
						}else if(array_key_exists('msg',$result_array) && $result_array['msg'] == 'user does not exist'){
							$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
							$output['errorMessage'] = $this->lang->line('error_username_not_found');
						}
					}
					else if($method == 'ChangeBalance')
					{
						if(array_key_exists('code',$result_array) && $result_array['code'] == '0')
						{
							$output['errorCode'] = ERROR_SUCCESS;
							$output['errorMessage'] = $this->lang->line('error_success');
						}else if(array_key_exists('msg',$result_array) && $result_array['msg'] == 'user does not exist'){
							$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
							$output['errorMessage'] = $this->lang->line('error_username_not_found');
						}else if(array_key_exists('msg',$result_array) && $result_array['msg'] == 'score value error'){
							$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
							$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
						}
					}
					else if($method == 'LogoutGame')
					{
						if(array_key_exists('code',$result_array) && $result_array['code'] == '0')
						{
							$output['errorCode'] = ERROR_SUCCESS;
							$output['errorMessage'] = $this->lang->line('error_success');
						}else{
							$player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['username']);
							if(empty($player_acc_data))
							{
								$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
								$output['errorMessage'] = $this->lang->line('error_username_not_found');
							}
						}
					}
				}
			}
		}
		

		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		return $output;
	}
	
	#{"APIUrl":"", "ReportUrl":"", "MerchantID":"", "ForwardUrl":"", "AccessKey":"", "Currency":""}
	private function gd_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
				
		$this->load->library('rng');		
				
		$url = $arr['APIUrl'];	
		$param_array = array();	
		$result_array = array();
		
		if($method == 'CreateMember')
		{
			$param_array = array (
									"Header" => array (
										"Method" => 'cCreateMember',
										"MerchantID" => $arr['MerchantID'],
										"MessageID" => 'M' . date('ymdHis') . $this->rng->get_token(5)
									),
									"Param" => array (
										"UserID" => $post_data['username'],
										"CurrencyCode" => $arr['Currency'],
										"BetGroup" => 'default',
									)
								);
		}
		else if($method == 'GetBalance')
		{
			$param_array = array (
									"Header" => array (
										"Method" => 'cCheckClient',
										"MerchantID" => $arr['MerchantID'],
										"MessageID" => 'C' . date('ymdHis') . $this->rng->get_token(5)
									),
									"Param" => array (
										"UserID" => $post_data['username'],
										"CurrencyCode" => $arr['Currency'],
										"RequestBetLimit" => 1
									)
								);
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$param_array = array (
									"Header" => array (
										"Method" => 'cDeposit',
										"MerchantID" => $arr['MerchantID'],
										"MessageID" => 'D' . date('ymdHis') . $this->rng->get_token(5)
									),
									"Param" => array (
										"UserID" => $post_data['username'],
										"CurrencyCode" => $arr['Currency'],
										"Amount" => $post_data['amount'],
										"EnableInGameTransfer" => 1,
										"GetEndBalance" => 1
									)
								);
			}
			else
			{
				$param_array = array (
									"Header" => array (
										"Method" => 'cWithdrawal',
										"MerchantID" => $arr['MerchantID'],
										"MessageID" => 'W' . date('ymdHis') . $this->rng->get_token(5)
									),
									"Param" => array (
										"UserID" => $post_data['username'],
										"CurrencyCode" => $arr['Currency'],
										"Amount" => bcdiv(($post_data['amount'] * -1), 1, 2),
										"EnableInGameTransfer" => 1,
										"GetEndBalance" => 1
									)
								);
			}
		}
		else if($method == 'LogoutGame')
		{
			$param_array = array (
									"Header" => array (
										"Method" => 'cLogoutPlayer',
										"MerchantID" => $arr['MerchantID'],
										"MessageID" => 'L' . date('ymdHis') . $this->rng->get_token(5)
									),
									"Param" => array (
										"UserID" => $post_data['username']
									)
								);
		}
		
		if($method == 'LoginGame')
		{
			$language = 'en';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN:
				case LANG_ZH_HK:
				case LANG_ZH_TW: $language = 'zh-cn'; break;
				case LANG_ID: $language = 'id'; break;
				case LANG_TH: $language = 'th'; break;
				case LANG_VI: $language = 'vi'; break;
				case LANG_JA: $language = 'ja'; break;
				case LANG_KO: $language = 'ko'; break;
			}
			
			$mode = 'real';
			if($post_data['is_demo'] == STATUS_YES)
			{
				$mode = 'fun';
			}
			
			$mobile = 1;
			if($post_data['device'] == PLATFORM_WEB)
			{
				$mobile = 0;
			}
			
			$return_url = '';
			if( ! empty($post_data['return_url']))
			{
				$hexstr = unpack('H*', $post_data['return_url']);
				$return_url = '&url=' . array_shift($hexstr);
			}
			
			$login_token = $this->rng->get_token(6);
			$key = hash('sha256', $arr['MerchantID'] . $login_token . $arr['AccessKey'] . $post_data['username'] . $arr['Currency'] . $post_data['return_url']);
			
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
			$output['result'] = $arr['ForwardUrl'] . '?OperatorCode=' . $arr['MerchantID'] . '&lang=' . $language . '&playerid=' . $post_data['username'] . '&LoginTokenID=' . $login_token . '&Currency=' . $arr['Currency'] . '&Key=' . $key . '&view=table&mode=' . $mode . '&nickname=' . $post_data['username'] . '&mobile=' . $mobile . $return_url;
		}
		else
		{
			//Get response from curl
			$response = $this->curl_json($url, $param_array);
			
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['Header']['ErrorCode']) && $result_array['Header']['ErrorCode'] == 0)
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}
					else if(isset($result_array['Header']['ErrorCode']) && $result_array['Header']['ErrorCode'] == 207)
					{
						$output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['Header']['ErrorCode']) && $result_array['Header']['ErrorCode'] == 0)
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['Param']['Balance'], 1, 2);
					}
					else if(isset($result_array['Header']['ErrorCode']) && $result_array['Header']['ErrorCode'] == 201)
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['Header']['ErrorCode']) && $result_array['Header']['ErrorCode'] == 0)
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['Param']['Balance'], 1, 2);
					}
					else if(isset($result_array['Header']['ErrorCode']) && $result_array['Header']['ErrorCode'] == 203)
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
				else if($method == 'LogoutGame')
				{
					if(isset($result_array['Header']['ErrorCode']) && $result_array['Header']['ErrorCode'] == 0)
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}
					else if(isset($result_array['Header']['ErrorCode']) && $result_array['Header']['ErrorCode'] == 201)
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
			}
		}	
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "CasinoUrl":"", "SlotsWebUrl":"", "SlotsMobileUrl":"", "P2PUrl":"", "MerchantID":"", "MerchantPass":""}
	private function gpi_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		
		$param_array = array(
			"merch_id" => $arr['MerchantID'],
			"merch_pwd" => $arr['MerchantPass'],
			"cust_id" => $post_data['username'],
			"currency" => $sys_data['system_currency'],
			"test_user" => (($post_data['is_demo'] == STATUS_YES) ? true : false)
		);
		
		if($method == 'CreateMember')
		{
			$url .= '/op/createuser';
			$param_array['cust_name'] = $post_data['username'];
		}
		else if($method == 'GetBalance')
		{
			$url .= '/op/getbalance';
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$url .= '/op/credit';
				$param_array['amount'] = $post_data['amount'];
			}
			else
			{
				$url .= '/op/debit';
				$param_array['amount'] = bcdiv(($post_data['amount'] * -1), 1, 2);
			}
			
			$param_array['trx_id'] = $post_data['order_id'];
		}
		
		//Login Game
		if($method == 'LoginGame')
		{
			$url = $arr['CasinoUrl'] . (($post_data['device'] == PLATFORM_WEB) ? '/html5/casino?' : '/html5/mobile?');
			
			if($post_data['game_type_code'] == GAME_SLOTS)
			{
				$url = (($post_data['device'] == PLATFORM_WEB) ? $arr['SlotsWebUrl'] : $arr['SlotsMobileUrl']) . '/' . $post_data['game_code'] . '?fun=' . (($post_data['is_demo'] == STATUS_YES) ? 1 : 0) . '&';
			}
			else if($post_data['game_type_code'] == GAME_OTHERS)
			{
				if($post_data['is_demo'] == STATUS_YES)
				{
					$url = $arr['P2PUrl'] . '/' . $post_data['game_code'] . '?fun=1&autojoinRoom=1&';
				}
				else
				{
					$url = $arr['P2PUrl'] . '/' . $post_data['game_code'] . '?fun=0&';
				}	
			}
			
			$lang = 'en-us';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN:
				case LANG_ZH_HK:
				case LANG_ZH_TW: $lang = 'zh-cn'; break;
				case LANG_ID: $lang = 'id-id'; break;
				case LANG_TH: $lang = 'th-th'; break;
				case LANG_VI: $lang = 'vi-vn'; break;
				case LANG_KM: $lang = 'km-kh'; break;
				case LANG_JA: $lang = 'ja-jp'; break;
				case LANG_KO: $lang = 'ko-kr'; break;
			}
			
			$player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['username']);
			if( ! empty($player_acc_data))
			{
				$this->load->library('rng');
				$partner_member_token = $this->rng->get_token(50);
				$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], $partner_member_token, $lang, $post_data['is_demo'], $this->input->ip_address());
				
				$output['errorCode'] = ERROR_SUCCESS;
				$output['errorMessage'] = $this->lang->line('error_success');
				$output['result'] = $url . 'token=' . $partner_member_token . '&op=' . $arr['MerchantID'] . '&lang=' . $lang . '&homeURL=' . $post_data['return_url'];
			}
			else
			{
				$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				$output['errorMessage'] = $this->lang->line('error_username_not_found');
			}
		}
		else if($method == 'LogoutGame')
		{
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
		}
		else
		{
			$url = $url . '?' . http_build_query($param_array);
			
			//Get response from curl
			$response = $this->curl_get($url);
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['error_code']) && $result_array['error_code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$this->player_model->add_player_game_token($post_data['provider_code'], $post_data['username']);
					}
					else if(isset($result_array['Code']) && $result_array['Code'] == '-98')
					{
						$output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['error_code']) && $result_array['error_code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['balance'], 1, 2);
					}
					else if(isset($result_array['Code']) && $result_array['Code'] == '-97')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['error_code']) && $result_array['error_code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['after'], 1, 2);
					}
					else if(isset($result_array['Code']) && $result_array['Code'] == '-97')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['Code']) && $result_array['Code'] == '-8037')
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "ForwardUrl":"", "BrandId":"", "APIKey":""}
	private function hb_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		
		$param_array = array(
							'BrandId' => $arr['BrandId'],
							'APIKey' => $arr['APIKey'],
							'Username' => $post_data['username'],
							'Password' => $post_data['password'],
						);
		
		if($method == 'CreateMember' OR $method == 'LoginGame')
		{
			$url .= '/LoginOrCreatePlayer';
			$param_array['PlayerHostAddress'] = $this->input->ip_address();
			$param_array['UserAgent'] = $this->input->user_agent();
			$param_array['KeepExistingToken'] = TRUE;
			$param_array['CurrencyCode'] = $sys_data['system_currency'];
		}
		else if($method == 'GetBalance')
		{
			$url .= '/QueryPlayer';
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$url .= '/DepositPlayerMoney';
				$param_array['CurrencyCode'] = $sys_data['system_currency'];
				$param_array['Amount'] = $post_data['amount'];
				$param_array['RequestId'] = $post_data['order_id'];
			}
			else
			{
				$url .= '/WithdrawPlayerMoney';
				$param_array['CurrencyCode'] = $sys_data['system_currency'];
				$param_array['Amount'] = $post_data['amount'];
				$param_array['WithdrawAll'] = FALSE;
				$param_array['RequestId'] = $post_data['order_id'];
			}
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/LogOutPlayer';
		}
		
		//Get response from curl
		$response = $this->curl_json($url, $param_array);
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['Token']) && ! empty($result_array['Token']))
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['Token']) && ! empty($result_array['Token']))
				{
					$locale = 'en';
					
					switch($post_data['syslang'])
					{
						case LANG_ZH_CN: $locale = 'zh-CN'; break;
						case LANG_ZH_HK:
						case LANG_ZH_TW: $locale = 'zh-TW'; break;
						case LANG_ID: $locale = 'id'; break;
						case LANG_TH: $locale = 'th'; break;
						case LANG_VI: $locale = 'vi'; break;
						case LANG_MY: $locale = 'my'; break;
						case LANG_MS: $locale = 'ms'; break;
						case LANG_JA: $locale = 'ja'; break;
						case LANG_KO: $locale = 'ko'; break;
						case LANG_TR: $locale = 'tr'; break;
					}
					
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $arr['ForwardUrl'] . '?brandid=' . $arr['BrandId'] . '&keyname=' . $post_data['game_code'] . '&token=' . $result_array['Token'] . '&mode=real&locale=' . $locale . '&lobbyurl=' . $post_data['return_url'];
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['Found']) && $result_array['Found'] == TRUE)
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['RealBalance'], 1, 2);
				}
				else if(isset($result_array['Found']) && $result_array['Found'] == FALSE)
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['Success']) && $result_array['Success'] == TRUE)
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['RealBalance'], 1, 2);
				}
				else if(isset($result_array['Success']) && $result_array['Success'] == FALSE)
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['Success']) && $result_array['Success'] == TRUE)
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['Success']) && $result_array['Success'] == FALSE)
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "WebRoot":"", "MobileRoot":"", "SecretKey":"", "OperatorID":"", "VendorID":"", "OddsType":"", "CurrencyId":"", "MinTransfer":"", "MaxTransfer":""}
	private function ibc_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		
		$param_array = array(
							'vendor_id' => $arr['VendorID']
						);
		
		if($method == 'CreateMember')
		{
			$url .= '/CreateMember';
			$param_array['vendor_member_id'] = $arr['OperatorID'] . '_' . $post_data['username'];
			$param_array['operatorId'] = $arr['OperatorID'];
			$param_array['username'] = $arr['OperatorID'] . '_' . $post_data['username'];
			$param_array['oddstype'] = $arr['OddsType'];
			$param_array['currency'] = $arr['CurrencyId'];
			$param_array['maxtransfer'] = $arr['MaxTransfer'];
			$param_array['mintransfer'] = $arr['MinTransfer'];
		}
		else if($method == 'LoginGame')
		{
			$url .= '/LogIn';
			$param_array['vendor_member_id'] = $arr['OperatorID'] . '_' . $post_data['username'];
		}
		else if($method == 'GetBalance')
		{
			$url .= '/CheckUserBalance';
			$param_array['vendor_member_ids'] = $arr['OperatorID'] . '_' . $post_data['username'];
			$param_array['wallet_id'] = 1;
		}
		else if($method == 'ChangeBalance')
		{
			$url .= '/FundTransfer';
			$param_array['vendor_member_id'] = $arr['OperatorID'] . '_' . $post_data['username'];
			$param_array['vendor_trans_id'] = $arr['OperatorID'] . '_' . $post_data['order_id'];
			$param_array['currency'] = $arr['CurrencyId'];
			$param_array['wallet_id'] = 1;
			
			if($post_data['amount'] > 0) 
			{
				$param_array['direction'] = 1;
				$param_array['amount'] = $post_data['amount'];
			}
			else
			{
				$param_array['direction'] = 0;
				$param_array['amount'] = bcdiv(($post_data['amount'] * -1), 1, 2);
			}
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/KickUser';
			$param_array['vendor_member_id'] = $arr['OperatorID'] . '_' . $post_data['username'];
		}
		
		if($method == 'LoginGame' && $post_data['is_demo'] == STATUS_YES)
		{
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
			$output['result'] = $arr['WebRoot'] . '/NewIndex';
		}
		else
		{
			//Get response from curl
			$response = $this->curl_post($url, $param_array);
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['error_code']) && $result_array['error_code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}
					else if(isset($result_array['error_code']) && $result_array['error_code'] == '6')
					{
						$output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
				}
				else if($method == 'LoginGame')
				{
					if(isset($result_array['error_code']) && $result_array['error_code'] == '0')
					{
						$lang = 'en';
						
						switch($post_data['syslang'])
						{
							case LANG_ZH_CN: $lang = 'cs'; break;
							case LANG_ZH_HK:
							case LANG_ZH_TW: $lang = 'ch'; break;
							case LANG_ID: $lang = 'id'; break;
							case LANG_TH: $lang = 'th'; break;
							case LANG_VI: $lang = 'vn'; break;
							case LANG_JA: $lang = 'jp'; break;
							case LANG_KO: $lang = 'ko'; break;
							case LANG_HI: $lang = 'hi'; break;
						}
						
						$url = (($post_data['device'] == PLATFORM_WEB) ? $arr['WebRoot'] : $arr['MobileRoot']);
						
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = $url . '/deposit_processlogin.aspx?token=' . $result_array['Data'] . '&lang=' . $lang;
					}
					else if(isset($result_array['error_code']) && $result_array['error_code'] == '2')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['error_code']) && $result_array['error_code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['Data'][0]['balance'], 1, 2);
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['error_code']) && $result_array['error_code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['Data']['after_amount'], 1, 2);
					}
					else if(isset($result_array['error_code']) && $result_array['error_code'] == '2')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['error_code']) && $result_array['error_code'] == '3')
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
				else if($method == 'LogoutGame')
				{
					if(isset($result_array['error_code']) && ($result_array['error_code'] == '0' OR $result_array['error_code'] == '3'))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}
					else if(isset($result_array['error_code']) && $result_array['error_code'] == '2')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
			}
		}	
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "ForwardUrl":"", "Username":"", "Password":"", "ParentID":"", "Currency":""}
	private function icg_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		
		$param_array = array();
		$result_array = array();				
		
		if($method == 'CreateMember')
		{
			$url .= '/api/v1/players';
			
			$param_array['username'] = $post_data['username'];
			$param_array['nickname'] = $post_data['username'];
			$param_array['currency'] = $arr['Currency'];
		}
		else if($method == 'GetBalance')
		{
			$url .= '/api/v1/players?page=1&player=' . $post_data['username'] . '&isChildren=true&parentId=' . $arr['ParentID'];
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$url .= '/api/v1/players/deposit';
				$param_array['amount'] = ($post_data['amount'] * 100);
			}
			else
			{
				$url .= '/api/v1/players/withdraw';
				$param_array['amount'] = bcdiv((($post_data['amount'] * 100) * -1), 1, 2);
			}
			
			$param_array['transactionId'] = $post_data['order_id'];
			$param_array['player'] = $post_data['username'];
			$param_array['platformId'] = $arr['ParentID'];
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/api/v1/players/logout';
			$param_array['player'] = $post_data['username'];
			$param_array['platformId'] = $arr['ParentID'];
		}
		
		//Login game
		if($method == 'LoginGame')
		{
			$lang = 'en';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN:
				case LANG_ZH_HK:
				case LANG_ZH_TW: $lang = 'zh'; break;
				case LANG_ID: $lang = 'id'; break;
				case LANG_TH: $lang = 'th'; break;
				case LANG_VI: $lang = 'vi'; break;
			}
			
			$return_url = '';
			if( ! empty($post_data['return_url']))
			{
				$return_url = '&home_URL=' . urlencode($post_data['return_url']);
			}
			
			if($post_data['is_demo'] == STATUS_YES)
			{
				$output['errorCode'] = ERROR_SUCCESS;
				$output['errorMessage'] = $this->lang->line('error_success');
				$output['result'] = $arr['ForwardUrl'] . $post_data['game_code'] . '?lang=' . $lang . $return_url;
			}
			else
			{
				$player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['username']);
				if( ! empty($player_acc_data))
				{
					$this->load->library('rng');
					$partner_member_token = $this->rng->get_token(64);
					$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], $partner_member_token);
					
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $arr['ForwardUrl'] . $post_data['game_code'] . '?platform=' . $arr['ParentID'] . '&token=' . $partner_member_token . '&lang=' . $lang . $return_url;
				}
				else
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		else
		{
			$token_url = $arr['APIUrl'] . '/login';
			$token_param_array = array(
										'username' => $arr['Username'],
										'password' => $arr['Password']
									);
			$token_response = $this->curl_json($token_url, $token_param_array);
			if($token_response['code'] == '0')
			{
				$token_result_array = json_decode($token_response['data'], TRUE);
				if(isset($token_result_array['token']))
				{
					//Get response from curl
					if($method == 'GetBalance')
					{
						$response = $this->curl_get($url, "Authorization: Bearer " . $token_result_array['token']);
					}
					else
					{
						$response = $this->curl_json($url, $param_array, "Authorization: Bearer " . $token_result_array['token']);
					}
					
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						
						if($method == 'CreateMember')
						{
							if(isset($result_array['data']['username']) && $result_array['data']['username'] == $post_data['username'])
							{
								$output['errorCode'] = ERROR_SUCCESS;
								$output['errorMessage'] = $this->lang->line('error_success');
								$this->player_model->add_player_game_token($post_data['provider_code'], $post_data['username']);
							}
							else {
								$output['errorCode'] = ERROR_USERNAME_EXITS;
								$output['errorMessage'] = $this->lang->line('error_username_already_exits');
							}
						}
						else if($method == 'LoginGame')
						{
							if(isset($result_array['url']))
							{
								$output['errorCode'] = ERROR_SUCCESS;
								$output['errorMessage'] = $this->lang->line('error_success');
								$output['result'] = $result_array['url'];
							}
						}
						else if($method == 'GetBalance')
						{
							if(isset($result_array['data'][0]['username']) && $result_array['data'][0]['username'] == $post_data['username'])
							{
								$output['errorCode'] = ERROR_SUCCESS;
								$output['errorMessage'] = $this->lang->line('error_success');
								$output['result'] = bcdiv(($result_array['data'][0]['balance'] / 100), 1, 2);
							}
						}
						else if($method == 'ChangeBalance')
						{
							if(isset($result_array['data']['username']) && $result_array['data']['username'] == $post_data['username'])
							{
								$output['errorCode'] = ERROR_SUCCESS;
								$output['errorMessage'] = $this->lang->line('error_success');
								$output['result'] = bcdiv(($result_array['data']['balance'] / 100), 1, 2);
							}
							else if(isset($result_array['error']['message']) && strpos($result_array['error']['message'], "exists") > 0)
							{
								$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
								$output['errorMessage'] = $this->lang->line('error_username_not_found');
							}
							else if(isset($result_array['error']['message']) && strpos($result_array['error']['message'], "Insufficient") > 0)
							{
								$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
								$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
							}
						}
						else if($method == 'LogoutGame')
						{
							if(isset($result_array['data']) && $result_array['data'] == 'ok')
							{
								$output['errorCode'] = ERROR_SUCCESS;
								$output['errorMessage'] = $this->lang->line('error_success');
								$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], '');
							}	
							else if(isset($result_array['error']['message']) && strpos($result_array['error']['message'], "exists") > 0)
							{
								$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
								$output['errorMessage'] = $this->lang->line('error_username_not_found');
							}
						}
					}
				}
			}
		}	
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "ForwardUrl":"", "AppID":"", "Secret":""}
	private function jk_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$param_array = array();
		
		if($method == 'CreateMember')
		{
			$param_array['Method'] = 'CU';
		}
		else if($method == 'LoginGame')
		{
			$param_array['Method'] = 'RT';
		}
		else if($method == 'GetBalance')
		{
			$param_array['Method'] = 'GC';
		}
		else if($method == 'ChangeBalance')
		{
			$param_array['Amount'] = $post_data['amount'];
			$param_array['Method'] = 'TC';
			$param_array['RequestID'] = $post_data['order_id'];
		}
		else if($method == 'LogoutGame')
		{
			$param_array['Method'] = 'SO';
		}
		
		$param_array['Timestamp'] = time();
		$param_array['Username'] = $post_data['username'];
		
		$signature = base64_encode(hash_hmac("sha1", urldecode(http_build_query($param_array, '', '&')), $arr['Secret'], true));
		$signature = urlencode($signature);
		$url = $arr['APIUrl'] . '?AppID=' . $arr['AppID'] . '&Signature=' . $signature;

		//Get response from curl
		$response = $this->curl_post($url, $param_array);
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if($result_array['Status'] == 'OK' OR $result_array['Status'] == 'Created')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['Username']) && ! empty($result_array['Username']))
				{
					$encrypt_data = str_replace('=', '', base64_encode($post_data['provider_code']. '|' . $result_array['Token'] . '|' . $post_data['game_code'] . '|' . (($post_data['device'] == PLATFORM_WEB) ? 0 : 1) . '|' . $post_data['return_url']));
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = base_url('game/jk/' . $encrypt_data);
				}
				else if(isset($result_array['Message']) && strpos($result_array['Message'], "not found") > 0)
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['Username']) && ! empty($result_array['Username']))
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['Credit'], 1, 2);
				}
				else if(isset($result_array['Message']) && strpos($result_array['Message'], "not found") > 0)
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['Username']) && ! empty($result_array['Username']))
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['Credit'], 1, 2);
				}
				else if(isset($result_array['Message']) && strpos($result_array['Message'], "not found") > 0)
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['Message']) && strpos($result_array['Message'], "Withdrawal amount cannot more than") > 0)
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
			}
			else if($method == 'LogoutGame')
			{
				if($result_array['Status'] == 'OK')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['Message']) && strpos($result_array['Message'], "not found") > 0)
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $url . '?' . http_build_query($param_array), $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "ForwardUrl":"", "AccessKey":"", "SecretKey":"", "PartnerName":""}
	private function ka_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
				
		include_once(APPPATH . 'third_party/phpseclib/Crypt/RSA.php');
		$rsa = new Crypt_RSA(); 
		$rsa->loadKey($arr['PrivateKey']);
		$rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1); 
		$rsa->setHash("md5");		
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$param_array = array(
							'username' => strtolower($post_data['username']),
							'channelId' => $arr['ChannelID']
						);
		
		if($method == 'CreateMember')
		{
			$url .= 'syncuser';
			
			$signature = $rsa->sign($param_array['username']);
			$param_array['signature'] = base64_encode($signature);
			$param_array['subChannelId'] = 0;
			$param_array['currency'] = $arr['Currency'];
		}
		else if($method == 'GetBalance')
		{
			$url .= 'userinfo';
			
			$timestamp = time();
			$signature = $rsa->sign($param_array['username'] . $timestamp);
			$param_array['signature'] = base64_encode($signature);
			$param_array['timestamp'] = $timestamp;
			$param_array['currency'] = $arr['Currency'];
		}
		else if($method == 'ChangeBalance')
		{
			$url .= 'recharge';
			
			$timestamp = time();
			$signature = $rsa->sign($param_array['username'] . $timestamp);
			$param_array['money'] = $post_data['amount'];
			$param_array['rechargeReqId'] = $post_data['order_id'];
			$param_array['signature'] = base64_encode($signature);
			$param_array['timestamp'] = $timestamp;
			$param_array['typeId'] = 0;
			$param_array['currency'] = $arr['Currency'];
		}
		else if($method == 'LogoutGame')
		{
			$url .= 'logout';
			
			$timestamp = time();
			$signature = $rsa->sign($param_array['username'] . $param_array['channelId'] . $timestamp);
			$param_array['signature'] = base64_encode($signature);
			$param_array['timestamp'] = $timestamp;
			$param_array['currency'] = $arr['Currency'];
		}
		
		//Login game
		if($method == 'LoginGame')
		{
			$lang = 'en_us';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $lang = 'zh_cn'; break;
				case LANG_ZH_HK: $lang = 'zh_hk'; break;
				case LANG_ZH_TW: $lang = 'zh_tw'; break;
				case LANG_ID: $lang = 'in_id'; break;
				case LANG_TH: $lang = 'th_th'; break;
				case LANG_VI: $lang = 'vi_vn'; break;
				case LANG_KM: $lang = 'km_kh'; break;
				case LANG_MY: $lang = 'my_mm'; break;
				case LANG_MS: $lang = 'ms_my'; break;
				case LANG_JA: $lang = 'ja_jp'; break;
				case LANG_KO: $lang = 'ko_kr'; break;
				case LANG_HI: $lang = 'hi_in'; break;
			}
			
			$game_type = '';
			if($post_data['game_type_code'] == GAME_SLOTS && ! empty($post_data['game_code']))
			{
				$game_type = '&gameType=5&tableCode=' . $post_data['game_code'];
			}
			
			$return_url = '';
			if( ! empty($post_data['return_url']))
			{
				$return_url = '&exitUrl=' . urlencode($post_data['return_url']);
			}
			
			if($post_data['is_demo'] == STATUS_YES)
			{
				$output['errorCode'] = ERROR_SUCCESS;
				$output['errorMessage'] = $this->lang->line('error_success');
				$output['result'] = $arr['ForwardUrl'] . '?language=' . $lang . '&mode=trial' . $game_type . $return_url;
			}
			else
			{
				$player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['username']);
				if( ! empty($player_acc_data))
				{
					$this->load->library('rng');
					$partner_member_token = $this->rng->get_token(64);
					$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], $partner_member_token);
					
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $arr['ForwardUrl'] . '?username=' . $post_data['username'] . '&accessToken=' . $partner_member_token . '&language=' . $lang . $game_type . $return_url;
				}
				else
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		else
		{
			//Get response from curl
			$response = $this->curl_json($url, $param_array);
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['status']) && $result_array['status'] == 200)
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$this->player_model->add_player_game_token($post_data['provider_code'], $post_data['username']);
					}
					else if(isset($result_array['status']) && $result_array['status'] == 401)
					{
						$output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['status']) && $result_array['status'] == 200)
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['money'], 1, 2);
					}
					else if(isset($result_array['status']) && $result_array['status'] == 4037)
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['status']) && $result_array['status'] == 200)
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['money'], 1, 2);
					}
					else if(isset($result_array['status']) && $result_array['status'] == 4037)
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['status']) && $result_array['status'] == 5002)
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
				else if($method == 'LogoutGame')
				{
					if(isset($result_array['status']) && $result_array['status'] == 200)
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], '');
					}
					else if(isset($result_array['status']) && $result_array['status'] == 4037)
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
			}
		}	
		
		//Database update
		$this->db->trans_start();
		ad($param_array);
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "ReportUrl":"", "Agent":"", "Deskey":"", "Md5key":""}
	private function le_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare data
		$this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
		$orderid = $aes->getOrderId($arr['Agent']);
		$aes->set_key($arr['Deskey']);
		$aes->require_pkcs5();
		
		$str = '';
		
		if($method == 'CreateMember')
		{
			$str = 's=0&account=' . $post_data['username'] . '&money=0&orderid=' . $orderid . '&ip=' . $this->input->ip_address() . '&lineCode=' . $sys_data['system_prefix'] . '&KindID=0';
		}
		else if($method == 'LoginGame')
		{
			$str = 's=0&account=' . $post_data['username'] . '&money=0&orderid=' . $orderid . '&ip=' . $this->input->ip_address() . '&lineCode=' . $sys_data['system_prefix'] . '&KindID=' . $post_data['game_code'];
		}
		else if($method == 'GetBalance')
		{
			$str = 's=7&account=' . $post_data['username'];
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$str = 's=2&account=' . $post_data['username'] . '&money=' . $post_data['amount'] . '&orderid=' . $orderid;
			}
			else
			{
				$str = 's=3&account=' . $post_data['username'] . '&money=' . bcdiv(($post_data['amount'] * -1), 1, 2) . '&orderid=' . $orderid;
			}
		}
		
		if($method == 'LogoutGame')
		{
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
		}
		else
		{
			$param = urlencode($aes->encrypt($str));
			
			$param_array = array(
				"agent" => $arr['Agent'],
				"timestamp" => $timestamp,
				"param" => $param,
				"key" => md5($arr['Agent'] . $timestamp . $arr['Md5key'])
			);
			
			$url = $arr['APIUrl'] . '?' . urldecode(http_build_query($param_array));
			
			//Get response from curl
			$response = $this->curl_get($url);
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['d']['code']) && $result_array['d']['code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}
				}
				else if($method == 'LoginGame')
				{
					if(isset($result_array['d']['code']) && $result_array['d']['code'] == '0')
					{
						$language = 'en_us';
			
						switch($post_data['syslang'])
						{
							case LANG_ZH_CN:
							case LANG_ZH_HK:
							case LANG_ZH_TW: $language = 'zh_cn'; break;
						}
			
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = $result_array['d']['url'] . '&backUrl=' . $post_data['return_url'] . '&jumpType=3&ly_lang=' . $language;
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['d']['code']) && $result_array['d']['code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['d']['totalMoney'], 1, 2);
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['d']['code']) && $result_array['d']['code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['d']['money'], 1, 2);
					}
					else if(isset($result_array['d']['code']) && $result_array['d']['code'] == '38')
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
			}
		}	
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "ForwardUrl":"", "PartnerMemberToken":"", "OperatorID":"", "PrivateToken":""}
	private function lh_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$param_array = array();
		
		if($method == 'CreateMember')
		{
			$url .= '/api/v2/members/';
			$param_array['member_code'] = $post_data['username'];
		}
		else if($method == 'GetBalance')
		{
			$url .= '/api/v2/balance/?LoginName=' . $post_data['username'];
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$url .= '/api/v2/deposit/';
				$param_array['member'] = $post_data['username'];
				$param_array['operator_id'] = $arr['OperatorID'];
				$param_array['amount'] = $post_data['amount'];
				$param_array['reference_no'] = $post_data['order_id'];
			}
			else
			{
				$url .= '/api/v2/withdraw/';
				$param_array['member'] = $post_data['username'];
				$param_array['operator_id'] = $arr['OperatorID'];
				$param_array['amount'] = bcdiv(($post_data['amount'] * -1), 1, 2);
				$param_array['reference_no'] = $post_data['order_id'];
			}
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/api/v2/partner-account-logout/';
			$param_array['member'] = $post_data['username'];
			$param_array['operator_id'] = $arr['OperatorID'];
		}
		
		//Login game
		if($method == 'LoginGame')
		{
			if($post_data['is_demo'] == STATUS_YES)
			{
				$output['errorCode'] = ERROR_SUCCESS;
				$output['errorMessage'] = $this->lang->line('error_success');
				$output['result'] = $arr['ForwardUrl'] . $arr['PartnerMemberToken'];
			}
			else
			{
				$player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['username']);
				if( ! empty($player_acc_data))
				{
					$this->load->library('rng');
					$partner_member_token = $this->rng->get_token(64);
					$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], $partner_member_token);
					
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $arr['ForwardUrl'] . $partner_member_token;
				}
				else
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		else
		{
			//Get response from curl
			if($method == 'GetBalance')
			{
				$response = $this->curl_get($url, "Authorization: Token " . $arr['PrivateToken']);
			}
			else
			{
				$response = $this->curl_post($url, $param_array, "Authorization: Token " . $arr['PrivateToken']);
			}
			
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['member_code']))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$this->player_model->add_player_game_token($post_data['provider_code'], $post_data['username']);
					}
					else if(isset($result_array['code']) && $result_array['code'] == '1')
					{
						$output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['results'][0]['balance']))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['results'][0]['balance'], 1, 2);
					}
					else if(isset($result_array['count']) && $result_array['count'] == '0')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['reference_no']))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['balance_amount'], 1, 2);
					}
					else if(isset($result_array['code']) && $result_array['code'] == '5')
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
				else if($method == 'LogoutGame')
				{
					if(isset($result_array['member']))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], '');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '3')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
			}
		}	
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}

	public function mega_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL){
		$this->load->library('rng');
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);

		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$param_array = array(
			"id" => $this->rng->get_token(50),
			"params" => array(
				"random" => $this->rng->get_token(50),
				"sn" => $arr['SN'],
			),
			"jsonrpc" => $arr['JsonRPC'],
		);
		$result_array = "";
		if($method == 'LoginGame'){
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
			$output['result'] = "";
		}else{
			if($method == 'CreateMember')
			{
				$url .= "open.mega.user.create";
				$param_array['method'] = "open.mega.user.create";
				$param_array['params']['digest'] = md5($param_array['params']['random'].$arr['SN'].$arr['SecretCode']);
				$param_array['params']['nickname'] = $post_data['username'];
				$param_array['params']['agentLoginId'] = $arr['Account'];
				$this->player_model->add_player_game_token($post_data['provider_code'], $post_data['username']);
			}
			else if($method == 'GetBalance')
			{
				$url .= "open.mega.balance.get";
				$param_array['method'] = "open.mega.balance.get";
				$param_array['params']['digest'] = md5($param_array['params']['random'].$arr['SN'].$post_data['game_id'].$arr['SecretCode']);
				$param_array['params']['loginId'] = $post_data['game_id'];
			}
			else if($method == 'ChangeBalance')
			{
				$requestOrderIDAlias = $requestOrderID;
				$url .= "open.mega.balance.transfer";
				$param_array['method'] = "open.mega.balance.transfer";
				$param_array['params']['digest'] = md5($param_array['params']['random'].$arr['SN'].$post_data['game_id'].$post_data['amount'].$arr['SecretCode']);
				$param_array['params']['loginId'] = $post_data['game_id'];
				$param_array['params']['amount'] = $post_data['amount'];
				//$param_array['params']['bizId'] = $requestOrderIDAlias;
			}
			else if($method == 'LogoutGame')
			{
				$url .= "open.mega.user.logout";
				$param_array['method'] = "open.mega.user.logout";
				$param_array['params']['digest'] = md5($param_array['params']['random'].$arr['SN'].$post_data['game_id'].$arr['SecretCode']);
				$param_array['params']['loginId'] = $post_data['game_id'];
			}

			$response = $this->curl_post_xe($url, $param_array);
			if($response['code'] == '0')
			{
				$response_data = $response['data'];
				$result_array = json_decode($response_data, TRUE);
				if($method == 'CreateMember')
				{
					if(array_key_exists('error',$result_array) && empty($result_array['error']))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['gameID'] = $result_array['result']['loginId'];
					}
				}
				else if($method == 'GetBalance')
				{
					if(array_key_exists('error',$result_array) && empty($result_array['error']))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['result'], 1, 2);
					}else if(array_key_exists('error',$result_array) && $result_array['error']['code'] == '37111')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(array_key_exists('error',$result_array) && empty($result_array['error']))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}else if(array_key_exists('error',$result_array) && $result_array['error']['code'] == '37111')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}else if(array_key_exists('error',$result_array) && $result_array['error']['code'] == '118')
					{
						$output['errorCode'] = ERROR_OVERTIME;
						$output['errorMessage'] = $this->lang->line('error_overtime');
					}else if(array_key_exists('error',$result_array) && $result_array['error']['code'] == '37123')
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
				else if($method == 'LogoutGame')
				{
					if(array_key_exists('error',$result_array) && empty($result_array['error']))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}else if(array_key_exists('error',$result_array) && $result_array['error']['code'] == '37111')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
			}
		}

		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		return $output;
	}
	
	#{"APIUrl":"", "STSUrl":"", "AgentCode":"", "SecretKey":""}
	private function mg_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		
		$param_array = array(
							'agentCode' => $arr['AgentCode'],
							'playerId' => $post_data['username']
						);
						
		$result_array = array();				
		
		if($method == 'CreateMember')
		{
			$url .= '/api/v1/agents/' . $arr['AgentCode'] . '/players';
		}
		else if($method == 'LoginGame')
		{
			$url .= '/api/v1/agents/' . $arr['AgentCode'] . '/players/' . $post_data['username'] . '/sessions';
			$param_array['contentCode'] = $post_data['game_code'];
			
			if($post_data['game_type_code'] == GAME_LIVE_CASINO)
			{
				$param_array['contentCode'] = 'SMG_titaniumLiveGames_Baccarat';
			}
			
			$param_array['contentType'] = 'Game';
			
			if( ! empty($post_data['return_url']))
			{
				$param_array['homeUrl'] = $post_data['return_url'];
			}
			
			$param_array['langCode'] = 'en';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN:
				case LANG_ZH_HK:
				case LANG_ZH_TW: $param_array['langCode'] = 'zh'; break;
				case LANG_TH: $param_array['langCode'] = 'th'; break;
				case LANG_VI: $param_array['langCode'] = 'vi'; break;
				case LANG_JA: $param_array['langCode'] = 'ja'; break;
				case LANG_TR: $param_array['langCode'] = 'tr'; break;
			}
			
			$param_array['platform'] = (($post_data['device'] == PLATFORM_WEB) ? 'Desktop' : 'Mobile');
		}
		else if($method == 'GetBalance')
		{
			$url .= '/api/v1/agents/' . $arr['AgentCode'] . '/players/' . $post_data['username'] . '?properties=balance';
		}
		else if($method == 'ChangeBalance')
		{
			$url .= '/api/v1/agents/' . $arr['AgentCode'] . '/WalletTransactions';
			$param_array['externalTransactionId'] = $post_data['order_id'];
			$param_array['idempotencykey'] = $post_data['order_id'];
			
			if($post_data['amount'] > 0) 
			{
				$param_array['amount'] = $post_data['amount'];
				$param_array['type'] = 'Deposit';
			}
			else
			{
				$param_array['amount'] = bcdiv(($post_data['amount'] * -1), 1, 2);
				$param_array['type'] = 'Withdraw';
			}
		}
		
		if($method == 'LogoutGame')
		{
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
		}
		else
		{
			$token_url = $arr['STSUrl'] . '/connect/token';
			$token_param_array = array(
										'client_id' => $arr['AgentCode'],
										'client_secret' => $arr['SecretKey'],
										'grant_type' => 'client_credentials'
									);
			$token_response = $this->curl_post($token_url, $token_param_array);
			if($token_response['code'] == '0')
			{
				$token_result_array = json_decode($token_response['data'], TRUE);
				if(isset($token_result_array['access_token']))
				{
					//Get response from curl
					if($method == 'GetBalance')
					{
						$response = $this->curl_get($url, "Authorization: Bearer " . $token_result_array['access_token']);
					}
					else
					{
						$response = $this->curl_post($url, $param_array, "Authorization: Bearer " . $token_result_array['access_token']);
					}
					
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						
						if($method == 'CreateMember')
						{
							if(isset($result_array['playerId']))
							{
								$output['errorCode'] = ERROR_SUCCESS;
								$output['errorMessage'] = $this->lang->line('error_success');
							}
						}
						else if($method == 'LoginGame')
						{
							if(isset($result_array['url']))
							{
								$output['errorCode'] = ERROR_SUCCESS;
								$output['errorMessage'] = $this->lang->line('error_success');
								$output['result'] = $result_array['url'];
							}
						}
						else if($method == 'GetBalance')
						{
							if(isset($result_array['balance']['total']))
							{
								$output['errorCode'] = ERROR_SUCCESS;
								$output['errorMessage'] = $this->lang->line('error_success');
								$output['result'] = bcdiv($result_array['balance']['total'], 1, 2);
							}
							else if(isset($result_array['error']['code']) && $result_array['error']['code'] == 'PlayerDoesNotExist')
							{
								$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
								$output['errorMessage'] = $this->lang->line('error_username_not_found');
							}
						}
						else if($method == 'ChangeBalance')
						{
							if(isset($result_array['idempotencyKey']))
							{
								$output['errorCode'] = ERROR_SUCCESS;
								$output['errorMessage'] = $this->lang->line('error_success');
								$output['result'] = 0;
							}
							else if(isset($result_array['error']['code']) && $result_array['error']['code'] == 'PlayerDoesNotExist')
							{
								$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
								$output['errorMessage'] = $this->lang->line('error_username_not_found');
							}
							else if(isset($result_array['error']['code']) && $result_array['error']['code'] == 'InsufficientFunds')
							{
								$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
								$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
							}
						}
					}
				}
			}
		}	
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"WebRoot":"", "MobileRoot":"", "LoginURL":"", "DepositUrl":"", "WithdrawalUrl":"", "CheckClientUrl":"", "GameInfoUrl":"", "VendorId":"", "MerchantCode":"", "MerchantPassword":"", "CurrencyId":""}
	private function n2_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = '';
		$xml = '';
		$xml_2 = '';
		$xml_utf8 = '';
		$xml_utf8_2 = '';
		
		if($method == 'GetBalance')
		{
			$url = $arr['CheckClientUrl'];
			$xml .= '<?xml version="1.0" encoding="utf-16"?>';
			$xml .= '<request action="ccheckclient">';
			$xml .= '<element id="C' . time() . rand(10000, 99999) . '">';
			$xml .= '<properties name="userid">' . $post_data['username'] . '</properties>';
			$xml .= '<properties name="vendorid">' . $arr['VendorId'] . '</properties>';
			$xml .= '<properties name="merchantpasscode">' . $arr['MerchantPassword'] . '</properties>';
			$xml .= '<properties name="currencyid">' . $arr['CurrencyId'] . '</properties>';
			$xml .= '</element>';
			$xml .= '</request>';
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$url = $arr['DepositUrl'];
				$xml .= '<?xml version="1.0" encoding="utf-16"?>';
				$xml .= '<request action="cdeposit">';
				$xml .= '<element id="D' . time() . rand(10000, 99999) . '">';
				$xml .= '<properties name="userid">' . $post_data['username'] . '</properties>';
				$xml .= '<properties name="acode"></properties>';
				$xml .= '<properties name="vendorid">' . $arr['VendorId'] . '</properties>';
				$xml .= '<properties name="merchantpasscode">' . $arr['MerchantPassword'] . '</properties>';
				$xml .= '<properties name="currencyid">' . $arr['CurrencyId'] . '</properties>';
				$xml .= '<properties name="amount">' . $post_data['amount'] . '</properties>';
				$xml .= '<properties name="refno">' . $post_data['order_id'] . '</properties>';
				$xml .= '</element>';
				$xml .= '</request>';
			}
			else
			{
				$url = $arr['WithdrawalUrl'];
				$xml .= '<?xml version="1.0" encoding="utf-16"?>';
				$xml .= '<request action="cwithdrawal">';
				$xml .= '<element id="W' . time() . rand(10000, 99999) . '">';
				$xml .= '<properties name="userid">' . $post_data['username'] . '</properties>';
				$xml .= '<properties name="vendorid">' . $arr['VendorId'] . '</properties>';
				$xml .= '<properties name="merchantpasscode">' . $arr['MerchantPassword'] . '</properties>';
				$xml .= '<properties name="amount">' . bcdiv(($post_data['amount'] * -1), 1, 2) . '</properties>';
				$xml .= '<properties name="currencyid">' . $arr['CurrencyId'] . '</properties>';
				$xml .= '<properties name="refno">' . $post_data['order_id'] . '</properties>';
				$xml .= '<properties name="requestplayerbalance">true</properties>';
				$xml .= '</element>';
				$xml .= '</request>';
			}
		}
		
		//Create Member & Login Game
		if($method == 'CreateMember')
		{
			$player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['username']);
			if(empty($player_acc_data))
			{
				$output['errorCode'] = ERROR_SUCCESS;
				$output['errorMessage'] = $this->lang->line('error_success');
				$this->player_model->add_player_game_token($post_data['provider_code'], $post_data['username']);
			}
			else
			{
				$output['errorCode'] = ERROR_USERNAME_EXITS;
				$output['errorMessage'] = $this->lang->line('error_username_already_exits');
			}
		}
		else if($method == 'LoginGame')
		{
			$url = (($post_data['device'] == PLATFORM_WEB) ? $arr['WebRoot'] : $arr['MobileRoot']);
			
			$language = 'en';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $language = 'zh-CN'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $language = 'zh-TW'; break;
				case LANG_ID: $language = 'id'; break;
				case LANG_TH: $language = 'th'; break;
				case LANG_VI: $language = 'vi'; break;
				case LANG_JA: $lang = 'ja'; break;
				case LANG_TR: $language = 'tr'; break;
			}
			
			$return_url = '';
			if( ! empty($post_data['return_url']))
			{
				$return_url = '&redirectURL=' . $post_data['return_url'];
			}
			
			if($post_data['is_demo'] == STATUS_YES)
			{
				$output['errorCode'] = ERROR_SUCCESS;
				$output['errorMessage'] = $this->lang->line('error_success');
				$output['result'] = $url . '/SingleLogin?merchantcode=' . $arr['MerchantCode'] . '&lang=' . $language . $return_url;
			}
			else
			{
				$player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['username']);
				if( ! empty($player_acc_data))
				{
					$this->load->library('rng');
					$partner_member_token = $this->rng->get_token(50);
					$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], $partner_member_token, $lang, $post_data['is_demo'], $this->input->ip_address());
					
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $url . '/SingleLogin?merchantcode=' . $arr['MerchantCode'] . '&lang=' . $language . '&userId=' . $post_data['username'] . '&uuId=' . $partner_member_token . $return_url;
				}
				else
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		else if($method == 'LogoutGame')
		{
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
		}
		else
		{
			//Get response from curl
			$response = $this->curl_xml($url, $xml);
			
			if($response['code'] == '0')
			{
				$xml_utf8 = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $response['data']);
				$xml_output = simplexml_load_string($xml_utf8);
				$json = json_encode($xml_output);
				$result_array = json_decode($json, TRUE);
				
				if($method == 'GetBalance')
				{
					if(isset($result_array['status']) && $result_array['status'] == 'success')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['result']['element']['properties'][1], 1, 2);
					}
					else if(isset($result_array['result']['element']['properties'][0]) && $result_array['result']['element']['properties'][0] == '203')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if($post_data['amount'] > 0) 
					{
						if(isset($result_array['status']) && $result_array['status'] == 'success')
						{
							$url_2 = $arr['DepositUrl'];
							$xml_2 = '<?xml version="1.0" encoding="utf-16"?>';
							$xml_2 .= '<request action="cdeposit-confirm">';
							$xml_2 .= '<element id="' . $result_array['result']['element']['@attributes']['id'] . '">';
							$xml_2 .= '<properties name="acode"></properties>';
							$xml_2 .= '<properties name="status">0</properties>';
							$xml_2 .= '<properties name="paymentid">' . $result_array['result']['element']['properties'][0] . '</properties>';
							$xml_2 .= '<properties name="vendorid">' . $arr['VendorId'] . '</properties>';
							$xml_2 .= '<properties name="merchantpasscode">' . $arr['MerchantPassword'] . '</properties>';
							$xml_2 .= '<properties name="errdesc"></properties>';
							$xml_2 .= '<properties name="requestplayerbalance">true</properties>';
							$xml_2 .= '</element>';
							$xml_2 .= '</request>';
							
							//Get response from curl
							$response_2 = $this->curl_xml($url_2, $xml_2);
							
							if($response_2['code'] == '0')
							{
								$xml_utf8_2 = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $response_2['data']);
								$xml_output_2 = simplexml_load_string($xml_utf8_2);
								$json_2 = json_encode($xml_output_2);
								$result_array_2 = json_decode($json_2, TRUE);
								
								if(isset($result_array_2['status']) && $result_array_2['status'] == 'success')
								{
									$output['errorCode'] = ERROR_SUCCESS;
									$output['errorMessage'] = $this->lang->line('error_success');
									$output['result'] = bcdiv($result_array_2['result']['element']['properties'][2], 1, 2);
								}
							}	
						}
					}
					else
					{
						if(isset($result_array['status']) && $result_array['status'] == 'success')
						{
							$output['errorCode'] = ERROR_SUCCESS;
							$output['errorMessage'] = $this->lang->line('error_success');
							$output['result'] = bcdiv($result_array['result']['element']['properties'][4], 1, 2);
						}
						else if(isset($result_array['result']['element']['properties'][1]) && $result_array['result']['element']['properties'][1] == '203')
						{
							$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
							$output['errorMessage'] = $this->lang->line('error_username_not_found');
						}
						else if(isset($result_array['result']['element']['properties'][1]) && $result_array['result']['element']['properties'][1] == '204')
						{
							$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
							$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
						}
					}	
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $xml . '|' . $xml_2, $xml_utf8 . '|' . $xml_utf8_2);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "secureLogin":"", "hash":""}
	private function pp_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'] . '/IntegrationService/v3/http/CasinoGameAPI';
		$param_array = array();
		
		if($method == 'CreateMember')
		{
			$url .= '/player/account/create/';
			$param_array['currency'] = $sys_data['system_currency'];
			$param_array['externalPlayerId'] = $post_data['username'];
			
		}
		else if($method == 'LoginGame')
		{
			$url .= '/game/start/';
			$param_array['externalPlayerId'] = $post_data['username'];
			$param_array['gameId'] = $post_data['game_code'];
			$param_array['language'] = 'en';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN:
				case LANG_ZH_HK:
				case LANG_ZH_TW: $param_array['language'] = 'zh'; break;
				case LANG_TH: $param_array['language'] = 'th'; break;
				case LANG_VI: $param_array['language'] = 'vi'; break;
				case LANG_JA: $param_array['language'] = 'ja'; break;
				case LANG_TR: $param_array['language'] = 'tr'; break;
			}
			
			if( ! empty($post_data['return_url']))
			{
				$param_array['lobbyURL'] = $post_data['return_url'];
			}
			
			$param_array['platform'] = (($post_data['device'] == PLATFORM_WEB) ? 'WEB' : 'MOBILE');
		}
		else if($method == 'GetBalance')
		{
			$url .= '/balance/current/';
			$param_array['externalPlayerId'] = $post_data['username'];
		}
		else if($method == 'ChangeBalance')
		{
			$url .= '/balance/transfer/';
			$param_array['amount'] = $post_data['amount'];
			$param_array['externalPlayerId'] = $post_data['username'];
			$param_array['externalTransactionId'] = $post_data['order_id'];
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/game/session/terminate/';
			$param_array['externalPlayerId'] = $post_data['username'];
		}
		
		$param_array['secureLogin'] = $arr['secureLogin'];
		$hash = md5(urldecode(http_build_query($param_array)) . $arr['hash']); 
		$param_array['hash'] = $hash;
		
		//Get response from curl
		$response = $this->curl_post($url, $param_array);
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if($result_array['error'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
			}
			else if($method == 'LoginGame')
			{
				if($result_array['error'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $result_array['gameURL'];
				}
				else if($result_array['error'] == '17')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'GetBalance')
			{
				if($result_array['error'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['balance'], 1, 2);
				}
				else if($result_array['error'] == '17')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if($result_array['error'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['balance'], 1, 2);
				}
				else if($result_array['error'] == '17')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if($result_array['error'] == '1')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
			}
			else if($method == 'LogoutGame')
			{
				if($result_array['error'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if($result_array['error'] == '17')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "EntityKeys":"", "KioskName":"", "AdminName":"", "BrandCode":"", "VirtualDatabase":"", "MobileHub":"", "SystemID":""}
	private function pt_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		
		$languagecode = 'EN';
					
		switch($post_data['syslang'])
		{
			case LANG_ZH_CN: $languagecode = 'ZH-CN'; break;
			case LANG_ZH_HK:
			case LANG_ZH_TW: $languagecode = 'CH'; break;
			case LANG_TH: $languagecode = 'TH'; break;
			case LANG_JA: $languagecode = 'JA'; break;
			case LANG_KO: $languagecode = 'KO'; break;
			case LANG_TR: $languagecode = 'TR'; break;
		}
		
		if($method == 'CreateMember')
		{
			$url .= '/player/create/playername/' . $arr['BrandCode'] . '-' . strtoupper($post_data['username']) . '/adminname/' . $arr['AdminName'] . '/kioskname/' . $arr['KioskName'] . '/countrycode/' . $sys_data['system_country'] . '/languagecode/' . $languagecode . '/password/' . $post_data['password'];
		}
		else if($method == 'GetBalance')
		{
			$url .= '/player/balance/playername/' . $arr['BrandCode'] . '-' . strtoupper($post_data['username']);
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$url .= '/player/deposit/playername/' . $arr['BrandCode'] . '-' . strtoupper($post_data['username']) . '/amount/' . $post_data['amount'] . '/adminname/' . $arr['AdminName'] . '/externaltranid/' . $post_data['order_id'];
			}
			else
			{
				$url .= '/player/withdraw/playername/' . $arr['BrandCode'] . '-' . strtoupper($post_data['username']) . '/amount/' . bcdiv(($post_data['amount'] * -1), 1, 2) . '/adminname/' . $arr['AdminName'] . '/externaltranid/' . $post_data['order_id'] . '/isForce/1';
			}
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/player/logout/playername/' . $arr['BrandCode'] . '-' . strtoupper($post_data['username']);
		}
		
		if($method == 'LoginGame')
		{
			$game_code = $post_data['game_code'];
			if($post_data['game_type_code'] == GAME_LIVE_CASINO)
			{
				$game_code = 'bal';
			}
			
			$encrypt_data = str_replace('=', '', base64_encode($post_data['provider_code']. '|' . $arr['BrandCode'] . '-' . strtoupper($post_data['username']) . '|' . $post_data['password'] . '|' . $game_code . '|' . (($post_data['device'] == PLATFORM_WEB) ? 0 : 1) . '|' . $post_data['is_demo'] . '|' . $languagecode . '|' . $post_data['return_url']));
			
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
			$output['result'] = base_url('game/pt/' . $encrypt_data);
		}
		else
		{
			//Get response from curl
			$response = $this->curl_post_for_pt($url, $arr['EntityKeys']);
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['result']))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}
					else if(isset($result_array['errorcode']) && $result_array['errorcode'] == '19')
					{
						$output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['result']))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['result']['balance'], 1, 2);
					}
					else if(isset($result_array['errorcode']) && $result_array['errorcode'] == '41')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['result']))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['result']['currentplayerbalance'], 1, 2);
					}
					else if(isset($result_array['errorcode']) && $result_array['errorcode'] == '72')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['errorcode']) && ($result_array['errorcode'] == '97' OR $result_array['errorcode'] == '98'))
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
				else if($method == 'LogoutGame')
				{
					if(isset($result_array['result']))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}
					else if(isset($result_array['errorcode']) && $result_array['errorcode'] == '41')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
			}
		}	
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "Agent":"", "Authcode":"", "SecretKey":"", "ActionIP":""}
	public function pus8_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$this->load->library('rng');
		$arr = json_decode($api_data, TRUE);
		$this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);

		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);

		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$result_array = array();
		$result_array2 = array();

		if($method == 'LoginGame'){
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
			$output['result'] = "";
		}else if($method == 'LogoutGame')
		{
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
		}else{
			if($method == 'CreateMember')
			{
				$url .= 'ashx/account/account.ashx?action=RandomUserName&userName='.$arr['Agent'].'&UserAreaId='.'&time='.$timestamp.'&authcode='.$arr['Authcode'] . '&sign=' . strtoupper(md5(strtolower($arr['Authcode'] . $arr['Agent'] .$timestamp . $arr['SecretKey'])));
			}
			else if($method == 'GetBalance')
			{
				$url .= 'ashx/account/account.ashx?action=getUserInfo&userName='.$post_data['game_id'].'&time='.$timestamp.'&authcode=' . $arr['Authcode'] . '&sign=' . strtoupper(md5(strtolower($arr['Authcode'] . $post_data['game_id'] .$timestamp . $arr['SecretKey'])));
			}
			else if($method == 'ChangeBalance')
			{
				$url .= 'ashx/account/setScore.ashx?action=setServerScore&scoreNum='.$post_data['amount'].'&userName='.$post_data['game_id'].'&ActionUser='.$arr['Agent'].'&ActionIp='.$arr['ActionIP'].'&time='.$timestamp.'&authcode=' . $arr['Authcode'] . '&sign=' . strtoupper(md5(strtolower($arr['Authcode'] . $post_data['game_id'] .$timestamp . $arr['SecretKey'])));
			}
			$param_array['url'] = $url;
			$response = $this->curl_get($url);
			if($response['code'] == '0')
			{
				$response_data = $response['data'];
				$result_array = json_decode($response_data, TRUE);
				if($method == 'CreateMember')
				{
					if(array_key_exists('code',$result_array) && $result_array['code'] == '0')
					{
						$output['gamePassword'] = "A".$post_data['password']."a";
						$output['gameID'] = $result_array['account'];
						$timestamp = str_pad($aes->getMillisecond(), 13, 0);
						$url2 = $arr['APIUrl'];
						$url2 .= 'ashx/account/account.ashx?action=addUser&agent='.$arr['Agent']."&PassWd=".$output['gamePassword']."&userName=".$output['gameID']."&Name=".$post_data['username']."&Tel=".time()."&Memo=newplayer&UserType=1&pwdtype=1&time=".$timestamp.'&authcode=' . $arr['Authcode'] . '&sign=' . strtoupper(md5(strtolower($arr['Authcode'] . $output['gameID'] .$timestamp . $arr['SecretKey'])));
						$param_array['url2'] = $url2;
						$response2 = $this->curl_get($url2);
						if($response2['code'] == '0')
						{
							$response_data2 = $response['data'];
							$result_array2 = json_decode($response_data, TRUE);
							if(array_key_exists('code',$result_array2) && $result_array2['code'] == '0')
							{
								$output['errorCode'] = ERROR_SUCCESS;
								$output['errorMessage'] = $this->lang->line('error_success');
							}else if(array_key_exists('code',$result_array2) && $result_array2['code'] == '-1'){
								$output['errorCode'] = ERROR_USERNAME_EXITS;
								$output['errorMessage'] = $this->lang->line('error_username_already_exits');
							}
						}
					}
				}
				else if($method == 'GetBalance')
				{
					if(array_key_exists('success',$result_array) && $result_array['success'] == true)
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['MoneyNum'], 1, 2);
					}else if(array_key_exists('code',$result_array) && $result_array['code'] == '0'){
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['MoneyNum'], 1, 2);
					}else if(array_key_exists('code',$result_array) && $result_array['code'] == '-9'){
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(array_key_exists('code',$result_array) && $result_array['code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['money'], 1, 2);
					}else if(array_key_exists('code',$result_array) && $result_array['code'] == '-9'){
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}else if(array_key_exists('code',$result_array) && $result_array['code'] == '-8'){
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
			}
		}

		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		if(!empty($result_array2)){
			$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array2);
		}
		$this->db->trans_complete();
		return $output;
	}
	
	#{"APIUrl":"", "ForwardUrl":"", "ReportUrl":"", "SecretKey":"", "MD5Key":"", "EncryptKey":"", "AppEncryptKey":"", "LobbyCode":""}
	private function sa_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$current_time = date("YmdHis");
		$str = '';
		
		if($method == 'CreateMember')
		{
			$str = "method=RegUserInfo&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['username']. "&CurrencyType=" . $sys_data['system_currency'];
		}
		else if($method == 'LoginGame')
		{
			$str = "method=LoginRequest&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['username']. "&CurrencyType=" . $sys_data['system_currency'];
		}
		else if($method == 'GetBalance')
		{
			$str = "method=GetUserStatusDV&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['username'];
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$str = "method=CreditBalanceDV&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['username'] . "&OrderId=" . $post_data['order_id'] . "&CreditAmount=" . $post_data['amount'];
			}
			else
			{
				$str = "method=DebitBalanceDV&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['username'] . "&OrderId=" . $post_data['order_id'] . "&DebitAmount=" . bcdiv(($post_data['amount'] * -1), 1, 2);
			}
		}
		else if($method == 'LogoutGame')
		{
			$str = "method=KickUser&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['username'];
		}
		
		$this->load->library('des');
		$crypt = new DES($arr['EncryptKey']);
		$mstr = $crypt->encrypt($str);
		$q0 = urlencode($mstr);
		$q = preg_replace_callback('/%[0-9A-F]{2}/', function(array $matches) { return strtolower($matches[0]); }, $q0);
		$premd5str = $str . $arr['MD5Key'] . $current_time . $arr['SecretKey'];
		$s = md5($premd5str);
		
		$param_array = array(
							'q' => $q,
							's' => $s
						);
		
		//Get response from curl
		$response = $this->curl_post_for_sa($url, $param_array);
		if($response['code'] == '0')
		{
			$xml = simplexml_load_string($response['data']);
			$json = json_encode($xml);
			$result_array = json_decode($json, TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '113')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
					$output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '0')
				{
					$lang = 'en_US';
					
					switch($post_data['syslang'])
					{
						case LANG_ZH_CN: $lang = 'zh_CN'; break;
						case LANG_ZH_HK:
						case LANG_ZH_TW: $lang = 'zh_TW'; break;
						case LANG_ID: $lang = 'id'; break;
						case LANG_TH: $lang = 'th'; break;
						case LANG_VI: $lang = 'vn'; break;
						case LANG_MS: $lang = 'ms'; break;
						case LANG_JA: $lang = 'jp'; break;
					}
					
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $arr['ForwardUrl'] . '?username=' . $post_data['username'] . '&token=' . $result_array['Token'] . '&lobby=' . $arr['LobbyCode'] . '&lang=' . $lang . '&returnurl=' . $post_data['return_url'];
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['Balance'], 1, 2);
				}
				else if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '116')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['Balance'], 1, 2);
				}
				else if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '116')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '121')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '116')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "CompanyKey":"", "ServerId":"", "Agent":""}
	private function sbo_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		
		$param_array = array(
							'CompanyKey' => $arr['CompanyKey'],
							'ServerId' => $arr['ServerId'],
							'Username' => $post_data['username'],
						);
		
		if($method == 'CreateMember')
		{
			$url .= '/web-root/restricted/player/register-player.aspx';
			$param_array['Agent'] = $arr['Agent'];
		}
		else if($method == 'LoginGame')
		{
			$url .= '/web-root/restricted/player/login.aspx';
			$param_array['Portfolio'] = 'SportsBook';
			$param_array['IsWapSports'] = FALSE;
		}
		else if($method == 'GetBalance')
		{
			$url .= '/web-root/restricted/player/get-player-balance.aspx';
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$url .= '/web-root/restricted/player/deposit.aspx';
				$param_array['Amount'] = $post_data['amount'];
				$param_array['TxnId'] = str_replace(array($post_data['username']), array(''), $post_data['order_id']);
			}
			else
			{
				$url .= '/web-root/restricted/player/withdraw.aspx';
				$param_array['Amount'] = bcdiv(($post_data['amount'] * -1), 1, 2);
				$param_array['TxnId'] = str_replace(array($post_data['username']), array(''), $post_data['order_id']);
				$param_array['IsFullAmount'] = FALSE;
			}
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/web-root/restricted/player/logout.aspx';
		}
		
		//Get response from curl
		$response = $this->curl_json($url, $param_array);
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['error']['id']) && $result_array['error']['id'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['error']['id']) && $result_array['error']['id'] == '4103')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
					$output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['error']['id']) && $result_array['error']['id'] == '0')
				{
					$lang = 'en';
					
					switch($post_data['syslang'])
					{
						case LANG_ZH_CN: $lang = 'zh-cn'; break;
						case LANG_ZH_HK:
						case LANG_ZH_TW: $lang = 'zh-tw'; break;
						case LANG_ID: $lang = 'id-id'; break;
						case LANG_TH: $lang = 'th-th'; break;
						case LANG_VI: $lang = 'vi-vn'; break;
						case LANG_MY: $lang = 'my-mm'; break;
						case LANG_JA: $lang = 'ja-jp'; break;
						case LANG_KO: $lang = 'ko-kr'; break;
					}
					
					$oddstyle = 'HK';
					$theme = 'sbo';
					$oddsmode = 'double';
					$device = (($post_data['device'] == PLATFORM_WEB) ? 'd' : 'm');
			
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = 'https:/' . str_replace('//', '/', $result_array['url']) . '&lang=' . $lang . '&oddstyle=' . $oddstyle . '&theme=' . $theme . '&oddsmode=' . $oddsmode . '&device=' . $device;
				}
				else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '010001')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['error']['id']) && $result_array['error']['id'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv(($result_array['balance'] - $result_array['outstanding']), 1, 2);
				}
				else if(isset($result_array['error']['id']) && $result_array['error']['id'] == '3303')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['error']['id']) && $result_array['error']['id'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv(($result_array['balance'] - $result_array['outstanding']), 1, 2);
				}
				else if(isset($result_array['error']['id']) && $result_array['error']['id'] == '3303')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['error']['id']) && $result_array['error']['id'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['error']['id']) && $result_array['error']['id'] == '4201')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "ForwardUrl":"", "MerchantCode":""}
	private function sg_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$token = '';
		
		$param_array = array(
			"acctId" => $post_data['username'],
			"serialNo" => date('YmdHis') . rand(100000, 999999),
			"merchantCode" => $arr['MerchantCode']
		);
		
		if($method == 'CreateMember')
		{
			$param_array['pageIndex'] = 0;
			$token = 'API: getAcctInfo';
		}
		else if($method == 'GetBalance')
		{
			$param_array['pageIndex'] = 0;
			$token = 'API: getAcctInfo';
		}
		else if($method == 'ChangeBalance')
		{
			$param_array['currency'] = $arr['system_currency'];
			
			if($post_data['amount'] > 0) 
			{
				$param_array['amount'] = $post_data['amount'];
				$token = 'API: deposit';
			}
			else
			{
				$param_array['amount'] = bcdiv(($post_data['amount'] * -1), 1, 2);
				$token = 'API: withdraw';
			}
		}
		else if($method == 'LogoutGame')
		{
			$token = 'API: kickAcct';
		}
		
		//Login Game
		if($method == 'LoginGame')
		{
			$language = 'en_US';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN:
				case LANG_ZH_HK:
				case LANG_ZH_TW: $language = 'zh_CN'; break;
				case LANG_ID: $language = 'id_ID'; break;
				case LANG_TH: $language = 'th_TH'; break;
				case LANG_VI: $language = 'vi_VN'; break;
				case LANG_JA: $language = 'ja_JP'; break;
				case LANG_KO: $language = 'ko_KR'; break;
			}
			
			$player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['username']);
			if( ! empty($player_acc_data))
			{
				$this->load->library('rng');
				$partner_member_token = $this->rng->get_token(64);
				$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], $partner_member_token);
				
				$output['errorCode'] = ERROR_SUCCESS;
				$output['errorMessage'] = $this->lang->line('error_success');
				$output['result'] = $url = $arr['ForwardUrl'] . '?acctId=' . $param_array['acctId'] . '&token=' . $partner_member_token . '&language=' . $language . '&game=' . $post_data['game_code'] . (($post_data['is_demo'] == STATUS_YES) ? '&fun=true' : '') . '&minigame=true&mobile=' . (($post_data['device'] == PLATFORM_WEB) ? 'false' : 'true') . '&menumode=On';
			}
			else
			{
				$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				$output['errorMessage'] = $this->lang->line('error_username_not_found');
			}
		}
		else
		{
			$response = $this->curl_json($url, $param_array, $token);
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['code']) && $result_array['code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$this->player_model->add_player_game_token($post_data['provider_code'], $post_data['username']);
					}
					else if(isset($result_array['Code']) && $result_array['Code'] == '-98')
					{
						$output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['code']) && $result_array['code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['list'][0]['balance'], 1, 2);
					}
					else if(isset($result_array['Code']) && $result_array['Code'] == '-97')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['code']) && $result_array['code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['afterBalance'], 1, 2);
					}
					else if(isset($result_array['Code']) && $result_array['Code'] == '-97')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['Code']) && $result_array['Code'] == '-8037')
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
				else if($method == 'LogoutGame')
				{
					if(isset($result_array['code']) && $result_array['code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], '');
					}
					else if(isset($result_array['Code']) && $result_array['Code'] == '-97')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "SecretKey":"", "MD5Key":"", "EncryptKey":"", "LobbyCode":""}
	private function sp_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$current_time = date("YmdHis");
		$str = '';
		
		if($method == 'CreateMember')
		{
			$str = "method=RegUserInfo&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['username']. "&CurrencyType=" . $sys_data['system_currency'];
		}
		else if($method == 'LoginGame')
		{
			$lang = 'en_US';
					
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $lang = 'zh_CN'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $lang = 'zh_TW'; break;
				case LANG_ID: $lang = 'id'; break;
				case LANG_TH: $lang = 'th'; break;
				case LANG_VI: $lang = 'vn'; break;
				case LANG_JA: $lang = 'jp'; break;
			}
					
			$str = "method=LoginRequest&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['username']. "&CurrencyType=" . $sys_data['system_currency'] . '&GameCode=' . $post_data['game_code'] . '&Lang=' . $lang . '&Mobile=' . (($post_data['device'] == PLATFORM_WEB) ? 0 : 1) . '&returnurl=' . $post_data['return_url'] . '&skipintro=1';
		}
		else if($method == 'GetBalance')
		{
			$str = "method=GetUserStatus&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['username'];
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$str = "method=CreditBalance&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['username'] . "&OrderId=" . $post_data['order_id'] . "&CreditAmount=" . $post_data['amount'];
			}
			else
			{
				$str = "method=DebitBalance&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['username'] . "&OrderId=" . $post_data['order_id'] . "&DebitAmount=" . bcdiv(($post_data['amount'] * -1), 1, 2);
			}
		}
		else if($method == 'LogoutGame')
		{
			$str = "method=KickUser&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['username'];
		}
		
		$this->load->library('des');
		$crypt = new DES($arr['EncryptKey']);
		$mstr = $crypt->encrypt($str);
		$q0 = urlencode($mstr);
		$q = preg_replace_callback('/%[0-9A-F]{2}/', function(array $matches) { return strtolower($matches[0]); }, $q0);
		$premd5str = $str . $arr['MD5Key'] . $current_time . $arr['SecretKey'];
		$s = md5($premd5str);
		
		$param_array = array(
							'q' => $q,
							's' => $s
						);
		
		//Get response from curl
		$response = $this->curl_post_for_sa($url, $param_array);
		if($response['code'] == '0')
		{
			$xml = simplexml_load_string($response['data']);
			$json = json_encode($xml);
			$result_array = json_decode($json, TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '113')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
					$output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $result_array['GameURL'];
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['Balance'], 1, 2);
				}
				else if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '116')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['Balance'], 1, 2);
				}
				else if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '116')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '121')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '116')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "Cert":"", "agentId":""}
	private function sx_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		
		$param_array = array(
							'cert' => $arr['Cert'],
							'agentId' => $arr['agentId']
						);
		
		if($method == 'CreateMember')
		{
			$url .= '/wallet/createMember';
			$param_array['userId'] = $post_data['username'];
			$param_array['currency'] = $sys_data['system_currency'];
			$param_array['betLimit'] = '{"SEXYBCRT": {"LIVE": {"limitId": [260103, 260104, 260106]}}}';
			$param_array['language'] = 'en';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN:
				case LANG_ZH_HK:
				case LANG_ZH_TW: $param_array['language'] = 'cn'; break;
				case LANG_TH: $param_array['language'] = 'th'; break;
				case LANG_JA: $param_array['language'] = 'jp'; break;
			}
		}
		else if($method == 'LoginGame')
		{
			$url .= '/wallet/doLoginAndLaunchGame';
			$param_array['userId'] = $post_data['username'];
			$param_array['gameType'] = 'LIVE';
			$param_array['platform'] = 'SEXYBCRT';
			$param_array['isMobileLogin'] = (($post_data['device'] == PLATFORM_WEB) ? 'false' : 'true');
			
			if( ! empty($post_data['return_url']))
			{
				$param_array['externalURL'] = $post_data['return_url'];
			}	
		}
		else if($method == 'GetBalance')
		{
			$url .= '/wallet/getBalance';
			$param_array['userIds'] = $post_data['username'];
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$url .= '/wallet/deposit';
				$param_array['userId'] = $post_data['username'];
				$param_array['transferAmount'] = $post_data['amount'];
				$param_array['txCode'] = $post_data['order_id'];
			}
			else
			{
				$url .= '/wallet/withdraw';
				$param_array['userId'] = $post_data['username'];
				$param_array['txCode'] = $post_data['order_id'];
				$param_array['withdrawType'] = 0; //1: All, 0: Partial;
				$param_array['transferAmount'] = bcdiv(($post_data['amount'] * -1), 1, 2);
			}
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/wallet/logout';
			$param_array['userIds'] = $post_data['username'];
		}
		
		//Get response from curl
		$response = $this->curl_post($url, $param_array);
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['status']) && $result_array['status'] == '0000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['status']) && $result_array['status'] == '1001')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
					$output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['status']) && $result_array['status'] == '0000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $result_array['url'];
				}
				else if(isset($result_array['status']) && $result_array['status'] == '1002')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['status']) && $result_array['status'] == '0000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['results'][$post_data['username']], 1, 2);
				}
				else if(isset($result_array['status']) && $result_array['status'] == '1000')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['status']) && $result_array['status'] == '0000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['currentBalance'], 1, 2);
				}
				else if(isset($result_array['status']) && $result_array['status'] == '1000')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['status']) && $result_array['status'] == '9999')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['status']) && $result_array['status'] == '0000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['status']) && $result_array['status'] == '1000')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "CompanyKey":"", "APIPassword":"", "Currency":""}
	private function ug_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		
		$param_array = array(
							'CompanyKey' => $arr['CompanyKey'],
							'APIPassword' => $arr['APIPassword'],
							'MemberAccount' => $post_data['username'],
						);
		
		if($method == 'CreateMember')
		{
			$url .= 'SportApi/Register';
			$param_array['NickName'] = $post_data['username'];
			$param_array['Currency'] = $arr['Currency'];
		}
		else if($method == 'LoginGame')
		{
			$url .= 'SportApi/Login';
			$param_array['WebType'] = (($post_data['device'] == PLATFORM_WEB) ? 'PC' : 'Smart');
			$param_array['LoginIP'] = $this->input->ip_address();
			$param_array['Language'] = 'en';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $param_array['Language'] = 'ch'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $param_array['Language'] = 'tw'; break;
				case LANG_ID: $param_array['Language'] = 'in'; break;
				case LANG_TH: $param_array['Language'] = 'th'; break;
				case LANG_VI: $param_array['Language'] = 'vn'; break;
				case LANG_KM: $param_array['Language'] = 'kh'; break;
				case LANG_JA: $param_array['Language'] = 'jp'; break;
				case LANG_KO: $param_array['Language'] = 'ko'; break;
			}
			
			$param_array['PageStyle'] = 'SP1';
			$param_array['OddsStyle'] = 'HK';
		}
		else if($method == 'GetBalance')
		{
			$url .= 'SportApi/GetBalance';
		}
		else if($method == 'ChangeBalance')
		{
			$url .= 'SportApi/Transfer';
			$param_array['SerialNumber'] = $post_data['order_id'];
			
			if($post_data['amount'] > 0) 
			{
				$param_array['Amount'] = $post_data['amount'];
				$param_array['TransferType'] = 0;
			}
			else
			{
				$param_array['Amount'] = bcdiv(($post_data['amount'] * -1), 1, 2);
				$param_array['TransferType'] = 1;
			}
			
			$param_array['Key'] = substr(md5(strtolower($param_array['APIPassword'] . $param_array['MemberAccount'] . number_format($param_array['Amount'], 4, '.', ''))), -6);
		}
		else if($method == 'LogoutGame')
		{
			$url .= 'SportApi/Logout';
		}
		
		//Get response from curl
		$response = $this->curl_json($url, $param_array);
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '000000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '100003')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
					$output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '000000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = str_replace('//', 'https://', $result_array['Data']);
				}
				else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '010001')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '000000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['Data']['Balance'], 1, 2);
				}
				else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '010001')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '000000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['Data']['Balance'], 1, 2);
				}
				else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '010001')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '300004')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '000000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "VendorId":" ", "Signature":""}
	private function wm_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		
		$syslang = 1;
					
		switch($post_data['syslang'])
		{
			case LANG_ZH_CN:
			case LANG_ZH_HK:
			case LANG_ZH_TW: $syslang = 0; break;
		}
		
		$param_array = array(
							'vendorId' => $arr['VendorId'],
							'signature' => $arr['Signature'],
							'user' => $post_data['username'],
							'syslang' => $syslang
						);
		
		if($method == 'CreateMember')
		{
			$param_array['cmd'] = 'MemberRegister';
			$param_array['password'] = $post_data['password'];
			$param_array['username'] = $post_data['username'];
		}
		else if($method == 'LoginGame')
		{
			$param_array['cmd'] = 'SigninGame';
			$param_array['password'] = $post_data['password'];
			
			$lang = 1;
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $lang = 0; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $lang = 9; break;
				case LANG_ID: $lang = 8; break;
				case LANG_MS: $lang = 7; break;
				case LANG_TH: $lang = 2; break;
				case LANG_VI: $lang = 3; break;
				case LANG_JA: $lang = 4; break;
				case LANG_KO: $lang = 5; break;
				case LANG_HI: $lang = 6; break;
			}
		
			$param_array['lang'] = $lang;
			
			if( ! empty($post_data['return_url']))
			{
				$param_array['returnurl'] = $post_data['return_url'];
			}
			
			if($post_data['is_demo'] == STATUS_YES)
			{
				$param_array['isTest'] = 1;
			}
		}
		else if($method == 'GetBalance')
		{
			$param_array['cmd'] = 'GetBalance';
		}
		else if($method == 'ChangeBalance')
		{
			$param_array['cmd'] = 'ChangeBalance';
			$param_array['money'] = $post_data['amount'];
			$param_array['order'] = $post_data['order_id'];
		}
		else if($method == 'LogoutGame')
		{
			$param_array['cmd'] = 'LogoutGame';
		}
		
		//Get response from curl
		$response = $this->curl_post($url, $param_array);
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['errorCode']) && $result_array['errorCode'] == '104')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $result_array['result'];
				}
				else if(isset($result_array['errorCode']) && $result_array['errorCode'] == '10501')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['result'], 1, 2);
				}
				else if(isset($result_array['errorCode']) && $result_array['errorCode'] == '10501')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['result']['cash'], 1, 2);
				}
				else if(isset($result_array['errorCode']) && $result_array['errorCode'] == '10501')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['errorCode']) && $result_array['errorCode'] == '10805')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['errorCode']) && $result_array['errorCode'] == '10501')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "PCH":"", "IVkey":"", "Deskey":""}
	private function xadj_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$param_array = array();
		
		if($method == 'CreateMember')
		{
			$url .= '/user/register';
			$param_array['register_username'] = $post_data['username'];
		}
		else if($method == 'LoginGame')
		{
			if($post_data['is_demo'] == STATUS_YES)
			{
				$url .= '/user/anonymousLunch';
			}
			else
			{
				$url .= '/user/lunch';
				$param_array['username'] = $post_data['username'];
			}
			
			$param_array['client'] = (($post_data['device'] == PLATFORM_WEB) ? 1 : 2);
			$param_array['lang'] = 2;
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $param_array['lang'] = 1; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $param_array['lang'] = 3; break;
				case LANG_ID: $param_array['lang'] = 6; break;
				case LANG_TH: $param_array['lang'] = 4; break;
				case LANG_VI: $param_array['lang'] = 5; break;
				case LANG_KO: $param_array['lang'] = 8; break;
				case LANG_HI: $param_array['lang'] = 7; break;
			}
		}
		else if($method == 'GetBalance')
		{
			$url .= '/user/balance';
			$param_array['username'] = $post_data['username'];
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$url .= '/user/deposit';
				$param_array['amount'] = $post_data['amount'];
			}
			else
			{
				$url .= '/user/withdraw';
				$param_array['amount'] = bcdiv(($post_data['amount'] * -1), 1, 2);
			}
			
			$param_array['order_id'] = $post_data['order_id'];
			$param_array['username'] = $post_data['username'];
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/user/logout';
			$param_array['username'] = $post_data['username'];
		}
		
		ksort($param_array);
        $atr_arr = array();
        foreach($param_array as $key => $val)
        {
			array_push($atr_arr, $key . '=' . $val);
        }
		
		$param_array['auth_key'] = md5(implode(',', $atr_arr));
		$str = json_encode($param_array);
		
		$this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$aes->set_mode(MCRYPT_MODE_CBC);
		$aes->set_iv($arr['IVkey']);
		$aes->set_key($arr['Deskey']);
		$aes->require_pkcs5();
		$params = $aes->encrypt($str);
		
		$token = "pch:" . $arr['PCH'];
        
		//Get response from curl
		$response = $this->curl_post($url, $params, $token);
		if($response['code'] == '0')
		{
			$response_data = $aes->decrypt($response['data']);
            $result_array = json_decode($response_data, TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['status']) && $result_array['status'] == '1')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == '1001')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
					$output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['status']) && $result_array['status'] == '1')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $result_array['data']['url'];
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == '1501')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['status']) && $result_array['status'] == '1')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['data'], 1, 2);
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == '1501')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['status']) && $result_array['status'] == '1')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = 0;
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == '1501')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == '1202')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['status']) && $result_array['status'] == '1')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], '');
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == '1501')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	public function xe_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL){
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);

		//Prepare post data
		$url = $arr['APIUrl'];
		$username = $arr['UPrefix'] ."_". ((substr($post_data['username'], 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($post_data['username'], strlen($sys_data['system_prefix'])) : $post_data['username']);
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$result_array = array();

		$param_array = array(
			"agentid" => $arr['Username'],
		);

		if($method == 'CreateMember')
		{
			$url .= '/player/create';
			$param_array['account'] = $username;
			$param_array['password'] = $post_data['password'];
		}
		else if($method == 'LoginGame')
		{
			$url .= '/player/info';
			$param_array['account'] = $username;
		}
		else if($method == 'GetBalance')
		{
			$url .= '/player/info';
			$param_array['account'] = $username;
		}
		else if($method == 'ChangeBalance')
		{
			$requestOrderIDAlias = $requestOrderID;
			$param_array['account'] = $username;
			$param_array['trackingid'] = $requestOrderIDAlias;
			if($post_data['amount'] > 0) 
			{
			    $url .= '/player/deposit';
				$param_array['amount'] = $post_data['amount'];
			}else{
			    $url .= '/player/withdraw';
				$param_array['amount'] = bcdiv(($post_data['amount'] * -1), 1, 2);
			}
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/player/logout';
			$param_array['account'] = $username;
		}


	    $hashinput = json_encode($param_array,true);
		$hashdata = hash_hmac("sha256",$hashinput,$arr['SignatureKey'], true);
		$hash = base64_encode($hashdata);
		$token = 'hashkey: ' . $hash;
		$response = $this->curl_post($url, $hashinput,$token);
		$param_array['hashinput'] = $hashinput;
		$param_array['hashdata'] = $hashdata;
		$param_array['token'] = $token;
		if($response['code'] == '0')
		{
			$response_data = $response['data'];
			$result_array = json_decode($response_data, TRUE);
			if($method == 'CreateMember')
			{
				if(isset($result_array['code']) && $result_array['code'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}else if(isset($result_array['code']) && $result_array['code'] == '31')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
				    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['code']) && $result_array['code'] == '0')
				{
					$language = 'En';
					switch($post_data['syslang'])
					{
						case LANG_ZH_CN:
						case LANG_ZH_HK:
						case LANG_ZH_TW: $language = 'CN'; break;
						case LANG_TH: $language = 'Thai'; break;
					}

					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $arr['ForwardUrl']."?language=".$language."&gameid=".$post_data['game_code']."&userid=".$username."&userpwd=".md5($post_data['password']);
				}else if(isset($result_array['code']) && $result_array['code'] == '41')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['code']) && $result_array['code'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['result']['balance'], 1, 2);
				}else if(isset($result_array['code']) && $result_array['code'] == '41')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['code']) && $result_array['code'] == '0')
				{

				}else if(isset($result_array['code']) && $result_array['code'] == '41')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['code']) && $result_array['code'] == '38')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
				else if(isset($result_array['code']) && $result_array['code'] == '39')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['code']) && $result_array['code'] == '1')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['code']) && $result_array['code'] == '41')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array);
		$this->db->trans_complete();
		
		return $output;
	}
}