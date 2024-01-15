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
				$provider_code = (isset($arr['provider_code']) ? strtoupper(trim($arr['provider_code'])) : '');
				$username = (isset($arr['username']) ? trim($arr['username']) : '');
				$password = (isset($arr['password']) ? trim($arr['password']) : '');
				$amount = (isset($arr['amount']) ? trim($arr['amount']) : '');
				$order_id = (isset($arr['order_id']) ? trim($arr['order_id']) : '');
				$player_id = (isset($arr['player_id']) ? trim($arr['player_id']) : '');
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
						'player_id' => $player_id,
						'game_id' => $game_id,
						'return_url' => $return_url, 
						'game_type_code' => $game_type_code, 
						'game_code' => $game_code,
						'is_demo' => $is_demo,
						'theme' => $theme
				);
						
				$this->lang->load('general', get_language($syslang));
				
				//Verify agent
				if( ! empty($agent_id)) {					
					$data = $this->api_model->get_api_data($agent_id);
					
					if( ! empty($data)) {						
						//Verify IP
						$whitelist_ip = explode(',', $data['game_whitelist_ip']);						
						if(in_array($incoming_ip, $whitelist_ip) OR $data['game_whitelist_ip'] == '*') {						
							//Verify method
							if( ! empty($method)) {								
								if(in_array($method, $whitelist_method)){
									
									//Verify signature
									if( ! empty($signature)) {										
										$verify_sign = md5($data['agent_id'] . $provider_code . $post_data['username'] . $data['secret_key']);
										if($signature == $verify_sign) {											
											//Verify game provider
											if( ! empty($provider_code)) {												
												$game_data = $this->game_model->get_game_data($provider_code);
												if( ! empty($game_data)){													
													//Verify param													
													if($method == 'GameList') {														
														$output = $this->get_sub_game_list($post_data);
													}
													else if(empty($post_data['username'])) {														
														$output['errorCode'] = ERROR_USERNAME_EMPTY;
														$output['errorMessage'] = $this->lang->line('error_username_empty');
													}
													else if( ! preg_match('/^[a-zA-Z0-9]{4,20}$/', $post_data['username'])) {	
														$output['errorCode'] = ERROR_USERNAME_INCORRECT;
														$output['errorMessage'] = $this->lang->line('error_username_incorrect');
													}
													else if($method == 'CreateMember' && empty($post_data['password'])) {	
														$output['errorCode'] = ERROR_PASSWORD_EMPTY;
														$output['errorMessage'] = $this->lang->line('error_password_empty');
													}
													else if($method == 'ChangeBalance' && (empty($post_data['amount']) || $post_data['amount'] == 0)) {		
														$output['errorCode'] = ERROR_AMOUNT_EMPTY;
														$output['errorMessage'] = $this->lang->line('error_amount_empty');
													}
													else if($method == 'ChangeBalance' && is_numeric($post_data['amount']) == FALSE) {	
														$output['errorCode'] = ERROR_AMOUNT_INCORRECT;
														$output['errorMessage'] = $this->lang->line('error_amount_incorrect');
													}
													else if($method == 'ChangeBalance' && empty($post_data['order_id'])) {
														$output['errorCode'] = ERROR_ORDER_ID_EMPTY;
														$output['errorMessage'] = $this->lang->line('error_order_id_empty');
													}
													else if($method == 'GetBalance' && empty($post_data['game_id'])) {	
														$output['errorCode'] = ERROR_USERNAME_EMPTY;
														$output['errorMessage'] = $this->lang->line('error_username_empty');
													}
													else if($method == 'ChangeBalance' && empty($post_data['game_id'])) {
														$output['errorCode'] = ERROR_USERNAME_EMPTY;
														$output['errorMessage'] = $this->lang->line('error_username_empty');
													}
													else if($method == 'LoginGame' && empty($post_data['game_id'])) {
														$output['errorCode'] = ERROR_USERNAME_EMPTY;
														$output['errorMessage'] = $this->lang->line('error_username_empty');
													}
													else if($method == 'LogoutGame' && empty($post_data['game_id']))
													{
														$output['errorCode'] = ERROR_USERNAME_EMPTY;
														$output['errorMessage'] = $this->lang->line('error_username_empty');
													}
													else
													{														
														switch($provider_code)
														{
															case 'AB': $output = $this->ab_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'BL': $output = $this->bl_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'BNG': $output = $this->bng_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'CQ9': $output = $this->cq93_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'DG': $output = $this->dg_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'DGG': $output = $this->dgg_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'DS88': $output = $this->ds88_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'DT': $output = $this->dt_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'EVO': $output = $this->evo_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'EVOP': $output = $this->evop_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'FTG': $output = $this->ftg_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'GFGD': $output = $this->gfgd_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'GR': $output = $this->gr_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															//case 'ICG': $output = $this->icg_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'HB': $output = $this->hb_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'JDB': $output = $this->jdb_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'JILI': $output = $this->jili_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'LH': $output = $this->lh_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'NAGA': $output = $this->naga_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'NK': $output = $this->ninek_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'OBSB': $output = $this->obsb_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'OG': $output = $this->og_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'PNG': $output = $this->png_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'PGSF': $output = $this->pgsf_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'PGS2': $output = $this->pgs2_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'PGS3': $output = $this->pgs2_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'PP': $output = $this->pp_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'RTG': $output = $this->rtg_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'RSG': $output = $this->rsg_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'SA': $output = $this->sa_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'SG': $output = $this->sg_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'SP': $output = $this->sp_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															//case 'SPSB': $output = $this->spsb_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
                                                            case 'SPSB': $output = $this->spsb2_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
                                                            case 'SPLT': $output = $this->splt_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'SXJL': $output = $this->sx_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'SXKM': $output = $this->sx_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'SX': $output = $this->sx_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'T1G': $output = $this->t1g_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'UG': $output = $this->ug_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'WM': $output = $this->wm_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'XG': $output = $this->xg_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'YGG': $output = $this->ygg_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'GX': $output = $this->xg_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'NE': $output = $this->ne_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'PS': $output = $this->ps_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'FC': $output = $this->fc_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'IGK': $output = $this->igk_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'KM': $output = $this->km_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'MG': $output = $this->mg_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'JK': $output = $this->jk_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'CMD': $output = $this->cmd_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'V8': $output = $this->v8_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
															case 'MEGA': $output = $this->mega_connect($method, $sys_data, $game_data['api_data'], $post_data); break;
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
				case GAME_BOARD_GAME: $folder = 'board'; break;
				case GAME_OTHERS: $folder = 'others'; break;
			}
			
			for($i=0;$i<sizeof($slot_data);$i++)
			{
				$game_name = $slot_data[$i]['game_name_en'];
				$game_picture = $slot_data[$i]['game_picture_en'];
				$game_lang_folder = 'en';
					
				if($post_data['syslang'] == LANG_ZH_HK OR $post_data['syslang'] == LANG_ZH_TW)
				{
				    if( ! empty($slot_data[$i]['game_name_cht'])){
				        $game_name = $slot_data[$i]['game_name_cht'];
				        if( ! empty($slot_data[$i]['game_picture_cht']))
					    {
					        $game_picture = $slot_data[$i]['game_picture_cht'];
					        $game_lang_folder = 'zh';
					    }
				    }else  if( ! empty($slot_data[$i]['game_name_chs'])){
				        $game_name = $slot_data[$i]['game_name_chs'];
				        if( ! empty($slot_data[$i]['game_picture_chs']))
					    {
					        $game_picture = $slot_data[$i]['game_picture_chs'];
					        $game_lang_folder = 'cn';
					    }
				    }
				}
				else if($post_data['syslang'] == LANG_ZH_CN)
				{
				    if( ! empty($slot_data[$i]['game_name_chs'])){
				        $game_name = $slot_data[$i]['game_name_chs'];
				        if( ! empty($slot_data[$i]['game_picture_chs']))
					    {
					        $game_picture = $slot_data[$i]['game_picture_chs'];
					        $game_lang_folder = 'cn';
					    }
				    }
				}
				
				if( ! empty($game_picture))
				{
					$game_picture = base_url('assets/img/' . $folder . '/' . strtolower($post_data['provider_code']) . '/' . $game_lang_folder . '/' . $game_picture);
				}
				
				$tmp_arr = array('game_code' => $slot_data[$i]['game_code'], 'game_name' => $game_name, 'game_picture' => $game_picture, 'is_progressive' => $slot_data[$i]['is_progressive'], 'is_hot' => $slot_data[$i]['is_hot'], 'is_new' => $slot_data[$i]['is_new'], 'is_open_game' => $slot_data[$i]['is_open_game'], 'url' => $slot_data[$i]['url']);
				array_push($slot_arr, $tmp_arr);
			}
		}
		
		$output['errorCode'] = ERROR_SUCCESS;
		$output['errorMessage'] = $this->lang->line('error_success');
		$output['result'] = $slot_arr;
		
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
		$curl_array = array();
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
			$loginurl 				= "https://m989.club/slot/mega_login";			
			$output['result'] 		= $loginurl;
		}
		else{
			if($method == 'CreateMember') {
				$url .= "open.mega.user.create";
				$param_array['method'] = "open.mega.user.create";
				$param_array['params']['digest'] = md5($param_array['params']['random'].$arr['SN'].$arr['SecretCode']);
				$param_array['params']['nickname'] = $post_data['username'];
				$param_array['params']['agentLoginId'] = $arr['Account'];
				$this->player_model->add_player_game_token($post_data['provider_code'], $post_data['username']);
			}
			/*else if($method == 'LoginGame'){
				$url .= "open.operator.user.login";
				$param_array['method'] = "open.operator.user.login";
				$param_array['params']['digest'] = md5($param_array['params']['random'].$arr['SN'].$post_data['game_id'].$arr['SecretCode']);
				$param_array['params']['loginId'] = $post_data['game_id'];
				$param_array['params']['password'] = $post_data['password'];
			}*/
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
			$curl_array = $response['curl'];
			if($response['code'] == '0')
			{
				$response_data = $response['data'];
				$result_array = json_decode($response_data, TRUE);
				if($method == 'CreateMember') {
					if(array_key_exists('error',$result_array) && empty($result_array['error'])) {
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['gameID'] = $result_array['result']['loginId'];
						$output['gamePassword'] = $post_data['password'];
					}
					else {						
						$output['errorMessage'] = array_key_exists('message',$result_array['error']) ? $result_array['error']['message'] : 'Login Failed';
					}
				}
				/*else if($method == 'LoginGame') {
					if(array_key_exists('error',$result_array) && empty($result_array['error'])) {
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = $result_array['result']['msg'].':'.$result_array['result']['sessionId'];
						#$output['gamePassword'] = $post_data['password'];
					}
				}*/
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
						$output['result'] = bcdiv($result_array['result'], 1, 2);
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
			else{
				if($response['code'] == '404'){
					$output['errorCode'] = ERROR_OVERTIME;
					$output['errorMessage'] = $this->lang->line('error_overtime');
				}
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}

		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		return $output;
	}
    
    #{"APIUrl":"", "ReportUrl":"", "Agent":"", "Deskey":"", "Md5key":"", "LineCode":""}
	private function v8_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = '';
		$param_array = array();
		$result_array = array();
		$curl_array = array();
		
		$this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
		$orderid = $aes->getOrderId($arr['Agent']);
		$aes->set_key($arr['Deskey']);
		$aes->require_pkcs5();
		$requestOrderIDAlias = $orderid;
		$str = '';
		
		if($method == 'CreateMember')
		{
			$username = $post_data['username'];
		}
		else
		{
			$username = $post_data['game_id'];
		}
		
		if($method == 'CreateMember')
		{
			$str = 's=0&account=' . $username . '&money=0&orderid=' . $orderid . '&ip=' . $this->input->ip_address() . '&lineCode=' . $arr['LineCode'] . '&KindID=0';
		}
		else if($method == 'LoginGame')
		{
			$str = 's=0&account=' . $username . '&money=0&orderid=' . $orderid . '&ip=' . $this->input->ip_address() . '&lineCode=' . $arr['LineCode'] . '&KindID=' . $post_data['game_code'];
		}
		else if($method == 'GetBalance')
		{
			$str = 's=7&account=' . $username;
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$str = 's=2&account=' . $username . '&money=' . $post_data['amount'] . '&orderid=' . $requestOrderIDAlias;
			}
			else
			{
				$str = 's=3&account=' . $username . '&money=' . bcdiv(($post_data['amount'] * -1), 1, 2) . '&orderid=' . $requestOrderIDAlias;
			}
		}
		else if($method == 'LogoutGame')
		{
		    $str = 's=8&account=' . $username;
		}
		
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
		$curl_array = $response['curl'];
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['d']['code']) && $result_array['d']['code'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $post_data['username'];
					$output['gamePassword'] = $post_data['password'];
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['d']['code']) && $result_array['d']['code'] == '0')
				{
					$return_url = '';
					
					if( ! empty($post_data['return_url']))
					{
						$return_url = '&returnUrl=' . $post_data['return_url'] . '&returnType=1';
					}
					else
					{
						$return_url = '&returnType=0';
					}
					
					$language = 'en-us';
		
					switch($post_data['syslang'])
					{
						case LANG_ZH_CN:
						case LANG_ZH_HK:
						case LANG_ZH_TW: $language = 'zh-cn'; break;
						case LANG_TH: $language = 'th'; break;
						case LANG_VI: $language = 'vie'; break;
						case LANG_ID: $language = 'ind'; break;
					}
		
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = str_replace('&lang=th', '&lang=' . $language, $result_array['d']['url']) . $return_url;
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
					$str_2 = 's=4&orderid=' . $requestOrderIDAlias;
				
					$param_2 = urlencode($aes->encrypt($str_2));
			
					$param_array_2 = array(
						"agent" => $arr['Agent'],
						"timestamp" => $timestamp,
						"param" => $param_2,
						"key" => md5($arr['Agent'] . $timestamp . $arr['Md5key'])
					);
					
					$url_2 = $arr['APIUrl'] . '?' . urldecode(http_build_query($param_array_2));
					
					//Get response from curl
					$response_2 = $this->curl_get($url_2);
					if($response_2['code'] == '0')
					{
						$result_array_2 = json_decode($response_2['data'], TRUE);
						
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array_2['d']['money'], 1, 2);
					}
				}
				else if(isset($result_array['d']['code']) && $result_array['d']['code'] == '38')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
				else if(isset($result_array['d']['code']) && $result_array['d']['code'] == '1002')
				{
					 $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['d']['code']) && $result_array['d']['code'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['d']['code']) && $result_array['d']['code'] == '35')
				{
					 $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}
		
		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
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
		$amount = 0;
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();

		$param_array = array(
			"PartnerKey" => $arr['PartnerKey'],
			"UserName" => $post_data['username']
		);
		
		if($method == 'CreateMember')
		{
			$param_array['Method'] = 'createmember';
			$param_array['Currency'] = $arr['CurrencyType'];
		}
		else if($method == 'GetBalance')
		{
			$param_array['Method'] = 'getbalance';
		}
		else if($method == 'ChangeBalance')
		{
			$requestOrderIDAlias = $post_data['order_id'];
			$param_array['Method'] = 'balancetransfer';
			$param_array['TicketNo'] = $requestOrderIDAlias;
			
			if($post_data['amount'] > 0) 
			{
				$param_array['PaymentType'] = 1;
				if($arr['CurrencyType'] == "IDR"){
					$amount = bcdiv($post_data['amount']/1000,1,2);
				}else if($arr['CurrencyType'] == "JPY"){
				    $amount = bcdiv($post_data['amount']/100,1,2);
				}else{
					$amount = $post_data['amount'];
				}
				$param_array['Money'] = $amount;
			}
			else
			{
				$param_array['PaymentType'] = 0;
				if($arr['CurrencyType'] == "IDR"){
					$amount = bcdiv(($post_data['amount'] * -1 / 1000),1,2);
				}else if($arr['CurrencyType'] == "JPY"){
				    $amount = bcdiv(($post_data['amount'] * -1 / 100),1,2);
				}else{
					$amount = bcdiv(($post_data['amount'] * -1), 1, 2);
				}
				$param_array['Money'] = $amount;
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
					$output['result'] = $url . '/auth.aspx?lang=' . $language . '&user=' . $post_data['username'] . '&token=' . $partner_member_token . '&currency=' . $arr['CurrencyType'] . '&templatename=' . $post_data['theme'] . '&view=v1';
					$param_array['url'] = $output['result']; 
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
			$curl_array = $response['curl'];
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['Code']) && $result_array['Code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['gameID'] = $param_array['UserName'];
						$output['gamePassword'] = $post_data['password'];
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
						if($arr['CurrencyType'] == "IDR"){
							$output['result'] = bcdiv($result_array['Data'][0]['BetAmount'] * 1000, 1, 2);
						}else if($arr['CurrencyType'] == "JPY"){
						    $output['result'] = bcdiv($result_array['Data'][0]['BetAmount'] * 100, 1, 2);
						}else{
							$output['result'] = bcdiv($result_array['Data'][0]['BetAmount'], 1, 2);
						}
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
						if($arr['CurrencyType'] == "IDR"){
							$output['result'] = bcdiv($result_array['Data']['BetAmount'] * 1000, 1, 2);
						}else if($arr['CurrencyType'] == "JPY"){
							$output['result'] = bcdiv($result_array['Data']['BetAmount'] * 100, 1, 2);
						}else{
							$output['result'] = bcdiv($result_array['Data']['BetAmount'], 1, 2);
						}
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
			}else{
				if($response['code'] == '404'){
					$output['errorCode'] = ERROR_OVERTIME;
					$output['errorMessage'] = $this->lang->line('error_overtime');
				}
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array,$curl_array);
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
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$amount = 0;
		$curl_array = array();
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
			$amount = $post_data['amount'];
			$requestOrderIDAlias = $post_data['order_id'];
			$param_array['Amount'] = $amount;
			$param_array['Method'] = 'TC';
			$param_array['RequestID'] = $requestOrderIDAlias;
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
		$curl_array = $response['curl'];
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if($result_array['Status'] == 'OK' OR $result_array['Status'] == 'Created')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $post_data['username'];
					$output['gamePassword'] = $post_data['password'];
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
					$output['result'] = bcdiv(($result_array['Credit']), 1, 2);
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
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $url . '?' . http_build_query($param_array), $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	private function jk2_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$amount = 0;
		$curl_array = array();
		$param_array = array();
		
		if($method == 'CreateMember')
		{
			$param_array['Method'] = 'CU';
		}
		else if($method == 'LoginGame')
		{
			$param_array['Method'] = 'PLAY';
		}
		else if($method == 'GetBalance')
		{
			$param_array['Method'] = 'GC';
		}
		else if($method == 'ChangeBalance')
		{
			if($arr['CurrencyType'] == "IDR"){
				$amount = bcdiv(($post_data['amount'] / 1000),1,2);
			}else{
				$amount = $post_data['amount'];
			}
			$requestOrderIDAlias = $post_data['order_id'];
			$param_array['Amount'] = $amount;
			$param_array['Method'] = 'TC';
			$param_array['RequestID'] = $requestOrderIDAlias;
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
		$curl_array = $response['curl'];
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if($result_array['Status'] == 'OK' OR $result_array['Status'] == 'Created')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $post_data['username'];
					$output['gamePassword'] = $post_data['password'];
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['Username']) && ! empty($result_array['Username']))
				{
					$encrypt_data = str_replace('=', '', base64_encode($post_data['provider_code']. '|' . $result_array['Token'] . '|' . $post_data['game_code'] . '|' . (($post_data['device'] == PLATFORM_WEB) ? 0 : 1) . '|' . $post_data['return_url']));
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = base_url('game/jk2/' . $encrypt_data);
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
					if($arr['CurrencyType'] == "IDR"){
						$output['result'] = bcdiv(($result_array['Credit'] * 1000), 1, 2);
					}else{
						$output['result'] = bcdiv(($result_array['Credit']), 1, 2);
					}
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
					if($arr['CurrencyType'] == "IDR"){
						$output['result'] = bcdiv(($result_array['Credit'] * 1000), 1, 2);
					}else{
						$output['result'] = bcdiv($result_array['Credit'], 1, 2);
					}
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
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $url . '?' . http_build_query($param_array), $result_array, $curl_array);
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
		$amount = 0;
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
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
			$requestOrderIDAlias = $post_data['order_id'];
			$url .= '/api/v1/agents/' . $arr['AgentCode'] . '/WalletTransactions';
			$param_array['externalTransactionId'] = $requestOrderIDAlias;
			$param_array['idempotencykey'] = $requestOrderIDAlias;
			
			$amount = $post_data['amount'];
				
			if($arr['CurrencyType'] == "VND2")
			{
				$amount = ($amount * 1000);
			}
			
			if($post_data['amount'] > 0) 
			{
				$param_array['amount'] = $amount;
				$param_array['type'] = 'Deposit';
			}
			else
			{
				$param_array['amount'] = bcdiv(($amount * -1), 1, 2);
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
						$curl_array = $response['curl'];
					}
					else
					{
						#apply bet limit start
						if($method == 'LoginGame'){
							$player_bet_limit_url = $arr['APIUrl'].'/api/v1/agents/' . $arr['AgentCode'] . '/players/' . $post_data['username'] . '/bettingProfiles';
							$param_array['bettingProfileId'] = '901512';
							$this->curl_post($player_bet_limit_url, $param_array, "Authorization: Bearer " . $token_result_array['access_token']);
							sleep(1);
						}
						#apply bet limit end
						
						$response = $this->curl_post($url, $param_array, "Authorization: Bearer " . $token_result_array['access_token']);
						$curl_array = $response['curl'];
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
								$output['gameID'] = $post_data['username'];
								$output['gamePassword'] = $post_data['password'];
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
								
								if($arr['CurrencyType'] == "VND2")
								{
									$output['result'] = bcdiv(($result_array['balance']['total'] / 1000), 1, 2);
								}
								else
								{
									$output['result'] = bcdiv($result_array['balance']['total'], 1, 2);
								}
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
					}else{
						if($response['code'] == '404'){
							$output['errorCode'] = ERROR_OVERTIME;
							$output['errorMessage'] = $this->lang->line('error_overtime');
						}
					}
				}
			}
		}	

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
    #{"APIUrl":"","ForwardUrl":"","GPCode":"","ClientID":"","ClientSecret":"","Currency":"","BetLimitId":}
	private function km_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
	    $arr = json_decode($api_data, TRUE);
		$currency_one = array("VND_1000", "IDR_1000","MMK_1000");
		
		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		
		$header = array(
			'charset=UTF-8',
		    'Content-Type: application/json',
		    'Accept: application/json',
		    'X-QM-ClientId: '.$arr['ClientID'],
		    'X-QM-ClientSecret: '.$arr['ClientSecret'],
		);
		
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		$amount = 0;
		$param_array = array();
		$result_array = array();
		
		if($method == 'CreateMember') {
		    $url .= "/api/player/authorize";
		    
            $lang = 'en-US';
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $lang = 'zh-CN'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $lang = 'zh-TW'; break;
				case LANG_ID: $lang = 'id-ID'; break;
				case LANG_TH: $lang = 'th-TH'; break;
				case LANG_VI: $lang = 'vi-VN'; break;
				case LANG_JA: $lang = 'ja-JP'; break;
				case LANG_KO: $lang = 'ko-KR'; break;
			}
            
            $param_array = array(
                'ipaddress' => $this->input->ip_address(),
                'username' => ((substr($post_data['username'], 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($post_data['username'], strlen($sys_data['system_prefix'])) : $post_data['username']),
                'userid' => $post_data['username'],
                'lang' => $lang,
                'cur' => $arr['Currency'],
                'betlimitid' => $arr['BetLimitId'],
                'istestplayer' => false,
                'platformtype' => (($post_data['device'] == PLATFORM_WEB) ? 0 : 1),
    		);
		}
		else if($method == 'LoginGame')
		{
		    $url .= "/api/player/authorize";
                
            $lang = 'en-US';
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $lang = 'zh-CN'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $lang = 'zh-TW'; break;
				case LANG_ID: $lang = 'id-ID'; break;
				case LANG_TH: $lang = 'th-TH'; break;
				case LANG_VI: $lang = 'vi-VN'; break;
				case LANG_JA: $lang = 'ja-JP'; break;
				case LANG_KO: $lang = 'ko-KR'; break;
			}
            
            $param_array = array(
                'ipaddress' => $this->input->ip_address(),
                'username' => ((substr($post_data['username'], 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($post_data['username'], strlen($sys_data['system_prefix'])) : $post_data['username']),
                'userid' => $post_data['game_id'],
                'lang' => $lang,
                'cur' => $arr['Currency'],
                'betlimitid' => $arr['BetLimitId'],
                'istestplayer' => false,
                'platformtype' => (($post_data['device'] == PLATFORM_WEB) ? 0 : 1),
    		);
		}
		else if($method == 'GetBalance')
		{
		    $url .= "/api/player/balance";
            $param_array = array(
                'userid' => $post_data['game_id'],
                'cur' => $arr['Currency'],
            );
		}
		else if($method == 'ChangeBalance')
		{
		    $requestOrderIDAlias = $post_data['order_id'];
		    if($post_data['amount'] > 0) 
			{
			    $url .= "/api/wallet/credit";
			    if(in_array($arr['Currency'],$currency_one)){
					$amount = bcdiv($post_data['amount'] / 1000,1,2);
			    }else{
				    $amount = bcdiv($post_data['amount'],1,2);
				}
			}
			else
			{
			    $url .= "/api/wallet/debit";
			    if(in_array($arr['Currency'],$currency_one)){
					$amount = bcdiv(($post_data['amount'] * -1 / 1000), 1, 2);
			    }else{
					$amount = bcdiv(($post_data['amount'] * -1), 1, 2);
				}
			}
			
            $param_array = array(
                'userid' => $post_data['game_id'],
                'amt' => $amount,
                'cur' => $arr['Currency'],
                'txid' => $requestOrderIDAlias,
                
            );
		}
		else if($method == 'LogoutGame')
		{
		    $url .= "/api/player/deauthorize";
            $param_array = array(
                'userid' => $post_data['game_id'],
            );
		}
		
		if($method == 'GetBalance'){
		    $response = $this->curl_get_json_km($url."?".http_build_query($param_array),$header);
		}else{
		    $response = $this->curl_json_km($url, $param_array,$header);    
		}
		$curl_array = $response['curl'];
		
		if($response['code'] == '0')
        {    
    		if($method == 'CreateMember')
    		{
    		    if(isset($response['http_code']) && $response['http_code'] == '200'){
                    $result_array = json_decode($response['data'], TRUE);
        		    if(isset($result_array['isnew']) && $result_array['isnew'] == true)
				    {
				        $output['errorCode'] = ERROR_SUCCESS;
    					$output['errorMessage'] = $this->lang->line('error_success');
    					$output['gameID'] = $param_array['userid'];
    					$output['gamePassword'] = $post_data['password'];
				    
					}else{
				        $output['errorCode'] = ERROR_USERNAME_EXITS;
				        $output['errorMessage'] = $this->lang->line('error_username_already_exits');
				    }
                }
				else{
                    $result_array = json_decode($response['data'], TRUE);
                    if(isset($result_array['err']) && $result_array['err'] == '700'){
                        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				        $output['errorMessage'] = $this->lang->line('error_game_maintenance');
				    }
					else {
						$output['errorCode'] = '808';
				        $output['errorMessage'] = json_encode($header);
						$result_array = $response['data'];
					}
                }
    		}
    		else if($method == 'LoginGame')
    		{
    		    if(isset($response['http_code']) && $response['http_code'] == '200'){
                    $result_array = json_decode($response['data'], TRUE);
        		    $token = $result_array['authtoken'];
        		    $lang = 'en-US';
		
        			switch($post_data['syslang'])
        			{
        				case LANG_ZH_CN: $lang = 'zh-CN'; break;
        				case LANG_ZH_HK:
        				case LANG_ZH_TW: $lang = 'zh-TW'; break;
        				case LANG_ID: $lang = 'id-ID'; break;
        				case LANG_TH: $lang = 'th-TH'; break;
        				case LANG_VI: $lang = 'vi-VN'; break;
        				case LANG_JA: $lang = 'ja-JP'; break;
        				case LANG_KO: $lang = 'ko-KR'; break;
        			}
        		    $game_param_array = array(
        		        "gpcode" => $arr['GPCode'],
        		        "gcode" => $post_data['game_code'],
        		        "token" => $token,
        		        "lang" => $lang,
        		    );
        		    
					#$game_url = ($post_data['device'] == PLATFORM_WEB) ? $arr['ForwardUrlWeb'] : $arr['ForwardUrlMobile'];
					#$output['result'] = $game_url."?".http_build_query($game_param_array);
        		    $output['result'] = $arr['ForwardUrl']."?".http_build_query($game_param_array);
        		    $output['errorCode'] = ERROR_SUCCESS;
    				$output['errorMessage'] = $this->lang->line('error_success');
                }else{
                    $result_array = json_decode($response['data'], TRUE);
                    if(isset($result_array['err']) && $result_array['err'] == '700'){
                        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				        $output['errorMessage'] = $this->lang->line('error_game_maintenance');
				    }
                }
    		}
    		else if($method == 'GetBalance')
    		{
    		    if(isset($response['http_code']) && $response['http_code'] == '200'){
                    $result_array = json_decode($response['data'], TRUE);
        		    $output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					if(in_array($arr['Currency'],$currency_one)){
				        $output['result'] = bcdiv($result_array['bal'] * 1000, 1, 2);
				    }else{
				        $output['result'] = bcdiv($result_array['bal'], 1, 2);
				    }
                }else{
                    $result_array = json_decode($response['data'], TRUE);
                    if(isset($result_array['err']) && $result_array['err'] == '600'){
                        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				        $output['errorMessage'] = $this->lang->line('error_username_not_found');
                    }else if(isset($result_array['err']) && $result_array['err'] == '700'){
                        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				        $output['errorMessage'] = $this->lang->line('error_game_maintenance');
				    }
                }
    		}
    		else if($method == 'ChangeBalance')
    		{
    		    if(isset($response['http_code']) && $response['http_code'] == '200'){
                    $result_array = json_decode($response['data'], TRUE);
        		    $output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					if(in_array($arr['Currency'],$currency_one)){
				        $output['result'] = bcdiv($result_array['bal'] * 1000, 1, 2);
				    }else{
				        $output['result'] = bcdiv($result_array['bal'], 1, 2);
				    }
                }else{
                    $result_array = json_decode($response['data'], TRUE);
                    if(isset($result_array['err']) && $result_array['err'] == '600'){
                        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				        $output['errorMessage'] = $this->lang->line('error_username_not_found');
                    }else if(isset($result_array['err']) && $result_array['err'] == '700'){
                        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				        $output['errorMessage'] = $this->lang->line('error_game_maintenance');
                    }else{
                        $output['errorCode'] = ERROR_OVERTIME;
				        $output['errorMessage'] = $this->lang->line('error_overtime');
                    }
                }
    		}
    		else if($method == 'LogoutGame')
    		{
    		    if(isset($response['http_code']) && $response['http_code'] == '200'){
                    $output['errorCode'] = ERROR_SUCCESS;
                    $output['errorMessage'] = $this->lang->line('error_success');
                }else{
                    $result_array = json_decode($response['data'], TRUE);
                    if(isset($result_array['err']) && $result_array['err'] == '600'){
                        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				        $output['errorMessage'] = $this->lang->line('error_username_not_found');
                    }else if(isset($result_array['err']) && $result_array['err'] == '700'){
                        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				        $output['errorMessage'] = $this->lang->line('error_game_maintenance');
                    }
                }
    		}
        }
		else{
            if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
        }
        
        if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "AgentId":"", "PropertyID":"", "DESKey":"", "MD5Key":"", "IdentificationCode":""}
	public function ab_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL){
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$amount = 0;
		$param_array = array();
		$result_array = array();
		$curl_array = array();

		$request_time = gmdate('D, j M Y H:i:s e');
        $path = "";
	
		if($method == 'CreateMember')
		{
			$path = '/CheckOrCreate';
			$url .= '/CheckOrCreate';
			$param_array['agent'] = $arr['AgentId'];
			$param_array['player'] = $post_data['username'].$arr['Suffix'];
		}
		else if($method == 'LoginGame')
		{
			$path = '/Login';
			$url .= '/Login';
			$param_array['agent'] = $arr['AgentId'];
			$param_array['player'] = $post_data['game_id'];
			$param_array['appType'] = (($post_data['device'] == PLATFORM_WEB) ? 6 : 3);
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
			
			if(!empty($post_data['game_code'])){
			    $param_array['tableName'] = $post_data['game_code'];
			}
			
			if( ! empty($post_data['return_url']))
			{
				$param_array['returnUrl'] = $post_data['return_url'];
			}
		}
		else if($method == 'GetBalance')
		{
			$path = '/GetBalances';
			$url .= '/GetBalances';
			$param_array['agent'] = $arr['AgentId'];
			$param_array['players'] = array($post_data['game_id']);
		    $param_array['pageSize'] = 1;
			$param_array['pageIndex'] = 1;
			$param_array['recursion'] = 1;
		}
		else if($method == 'ChangeBalance')
		{
			$requestOrderIDAlias = $arr['PropertyID'] . substr(str_replace(array($post_data['username'], 'IN', 'OUT'), array('', '', ''), $post_data['order_id']), 2).rand(1,9);
			$path = '/Transfer';
			$url .= '/Transfer';
			$param_array['agent'] = $arr['AgentId'];
			$param_array['player'] = $post_data['game_id'];
			$param_array['sn'] = $requestOrderIDAlias;
			$param_array['client'] = $post_data['username'];
			
			if($post_data['amount'] > 0) 
			{
				if($arr['CurrencyType'] == "IDR2" || $arr['CurrencyType'] == "VND2" || $arr['CurrencyType'] == "MMK2" || $arr['CurrencyType'] == "KRW2" || $arr['CurrencyType'] == "KHR2"){
					$amount = ($post_data['amount'] / 1000);
				}else if($arr['CurrencyType'] == "KRW3" || $arr['CurrencyType'] == "JPY2"){
					$amount = ($post_data['amount'] / 100);
				}else{
					$amount = $post_data['amount'];	
				}

				$param_array['type'] = 1;
				$param_array['amount'] = $amount;
			}
			else
			{
				if($arr['CurrencyType'] == "IDR2" || $arr['CurrencyType'] == "VND2" || $arr['CurrencyType'] == "MMK2" || $arr['CurrencyType'] == "KRW2" || $arr['CurrencyType'] == "KHR2"){
					$amount = bcdiv(($post_data['amount'] * -1 / 1000), 1, 2);
				}else if($arr['CurrencyType'] == "KRW3" || $arr['CurrencyType'] == "JPY2"){
				    $amount = bcdiv(($post_data['amount'] * -1 / 100), 1, 2);
				}else{
					$amount = bcdiv(($post_data['amount'] * -1), 1, 2);	
				}

				$param_array['type'] = 0;
				$param_array['amount'] = $amount;
			}
		}
		else if($method == 'LogoutGame')
		{
		    $path = '/Logout';
			$url .= '/Logout';
			$param_array['player'] = $post_data['game_id'];
		}

		$md5_content =  base64_encode(pack('H*', md5(json_encode($param_array,true))));
        $string_sign = "POST" . "\n" . $md5_content . "\n" . "application/json" . "\n" . $request_time . "\n" . $path;
        $des_key = base64_decode($arr['APIKey']);
        $hash_hmac = hash_hmac("sha1", $string_sign, $des_key, true);
        $encrypted = base64_encode($hash_hmac);
        $authorization = "AB" . " " . $arr['PropertyID'] . ":" . $encrypted;
		$header = array(
            "authorization" => $authorization,
            "date" => $request_time,
            "content" => $md5_content,
        );
        
        $response = $this->curl_post_for_allbet($url, $param_array,$header);
		$curl_array = $response['curl'];

		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['resultCode']) && $result_array['resultCode'] == 'OK')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $result_array['data']['player'];
					$output['gamePassword'] = $post_data['password'];
				}
				else if(isset($result_array['resultCode']) && $result_array['resultCode'] == 'PLAYER_EXIST')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
					$output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['resultCode']) && $result_array['resultCode'] == 'OK')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $result_array['data']['gameLoginUrl'];
				}
				else if(isset($result_array['resultCode']) && $result_array['resultCode'] == 'PLAYER_NOT_EXIST')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['resultCode']) && $result_array['resultCode'] == 'OK')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					if($arr['CurrencyType'] == "IDR2" || $arr['CurrencyType'] == "VND2" || $arr['CurrencyType'] == "MMK2" || $arr['CurrencyType'] == "KRW2" || $arr['CurrencyType'] == "KHR2"){
						$output['result'] = bcdiv(($result_array['data']['list'][0]['amount'] * 1000), 1, 2);
					}else if($arr['CurrencyType'] == "KRW3" || $arr['CurrencyType'] == "JPY2"){
					    $output['result'] = bcdiv(($result_array['data']['list'][0]['amount'] * 100), 1, 2);
					}else{
						$output['result'] = bcdiv($result_array['data']['list'][0]['amount'], 1, 2);
					}
				}
				else if(isset($result_array['resultCode']) && $result_array['resultCode'] == 'PLAYER_NOT_EXIST')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
			    $param_array_2 = array(
        			"sn" => $requestOrderIDAlias,
        		);
        		
        		$url2 = $arr['APIUrl'];
        		$request_time = gmdate('D, j M Y H:i:s e');
		        $path2 = '/GetTransferState';
	            $url2 .= '/GetTransferState';
	            
	            $md5_content =  base64_encode(pack('H*', md5(json_encode($param_array_2,true))));
                $string_sign = "POST" . "\n" . $md5_content . "\n" . "application/json" . "\n" . $request_time . "\n" . $path2;
                
                $des_key = base64_decode($arr['APIKey']);
                $hash_hmac = hash_hmac("sha1", $string_sign, $des_key, true);
                $encrypted = base64_encode($hash_hmac);
                $authorization = "AB" . " " . $arr['PropertyID'] . ":" . $encrypted;
        		$header2 = array(
                    "authorization" => $authorization,
                    "date" => $request_time,
                    "content" => $md5_content,
                );
                
				if(isset($result_array['resultCode']) && $result_array['resultCode'] == 'OK')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = 0;
				}
				else if(isset($result_array['resultCode']) && $result_array['resultCode'] == 'PLAYER_NOT_EXIST')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['resultCode']) && $result_array['resultCode'] == 'LACK_OF_MONEY')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}else{
				    if(isset($result_array['resultCode']) && $result_array['resultCode'] == 'INTERNAL_ERROR'){
				        $output['errorCode'] = ERROR_OVERTIME;
			            $output['errorMessage'] = $this->lang->line('error_overtime');
				        //over time
                        $response2 = $this->curl_post_for_allbet($url2, $param_array_2,$header2);
                        $curl_array2 = $response2['curl'];
                        if($response2['code'] == '0')
		                {
		                    $result_array_2 = json_decode($response2['data'], TRUE);
		                    if(isset($result_array_2['resultCode']) && $result_array_2['resultCode'] == 'OK')
    				        {
    				            if(isset($result_array_2['transferState']) && $result_array_2['data']['transferState'] == "1"){
    				                $output['errorCode'] = ERROR_SUCCESS;
                					$output['errorMessage'] = $this->lang->line('error_success');
                					$output['result'] = 0;
    				            }else if(isset($result_array_2['transferState']) && $result_array_2['data']['transferState'] == "2"){
    				                $output['errorCode'] = ERROR_SYSTEM_ERROR;
                					$output['errorMessage'] = $this->lang->line('error_system_error');
                					$output['result'] = 0;
    				            }
    				        }    
    				        $this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array_2, $result_array_2,$curl_array2);
		                }
				    }else{
				        if(isset($result_array['resultCode'])){
				            
				        }else{
				            //over time
				            $output['errorCode'] = ERROR_OVERTIME;
				            $output['errorMessage'] = $this->lang->line('error_overtime');            
				        }
				    }
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['resultCode']) && $result_array['resultCode'] == 'OK')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['resultCode']) && $result_array['resultCode'] == 'PLAYER_NOT_EXIST')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}


		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array,$curl_array);
		$this->db->trans_complete();
		
		return $output;
	}

    private function igk_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
	    $arr = json_decode($api_data, TRUE);
	    
	    //Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		$url = $arr['APIUrl'];
		$url_update = $arr['APIUrl'];
		$param_array = array();
		$curl_array = array();
		$result_array = array();
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$currency_one = array("1000");
		
		if($method == 'CreateMember')
    	{   
    	    $param_array = array(
                'action' => "create",
                'secret' => $arr['Secret'],
                'agent' => $arr['Agent'],
                'username' => "a".str_pad($post_data['player_id'],10,"0",STR_PAD_LEFT),
            );
    	}
		else if($method == 'LoginGame')
		{
		    $language = 'EN-US';
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $language = 'ZH-CN'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $language = 'ZH-CN'; break;
				case LANG_ID: $language = 'ID-ID'; break;
				case LANG_TH: $language = 'TH-TH'; break;
				case LANG_VI: $language = 'VI-VN'; break;
				case LANG_KO: $language = 'KO-KR'; break;
				case LANG_JA: $language = 'JA-JP'; break;
			}
			
			if($post_data['device'] == PLATFORM_WEB){
			    $param_array = array(
    		        'action' => 'login_new',
    		        'secret' => $arr['Secret'],
                    'agent' => $arr['Agent'],
    		        'username' => $post_data['game_id'],
    		        'lang' => $language,
    		        'accType' => $arr['AccType'],
    		        'host' => $arr['Host'],
    		    ); 
			}else{
			    $param_array = array(
    		        'action' => 'login_mobile',
    		        'secret' => $arr['Secret'],
                    'agent' => $arr['Agent'],
    		        'username' => $post_data['game_id'],
    		        'lang' => $language,
    		        'accType' => $arr['AccType'],
    		        'host' => $arr['Host'],
    		        'theme' => $arr['Theme'],
    		    );
    		    
    		    if( ! empty($post_data['return_url']))
				{
				    $param_array['returnUrl'] = $post_data['return_url'];
				}
			}
		}
		else if($method == 'GetBalance')
		{
		    $param_array = array(
                'action' => "balance",
                'secret' => $arr['Secret'],
                'agent' => $arr['Agent'],
                'username' => $post_data['game_id'],
            );
		}
		else if($method == 'ChangeBalance')
		{
		    $requestOrderIDAlias = $post_data['order_id'];
            if($post_data['amount'] > 0){
                $action = "deposit";
                if(in_array($arr['CurrencyType'],$currency_one)){
                    $amount = bcdiv($post_data['amount'] / 1000,1,2);
                }else{
                    $amount = bcdiv($post_data['amount'],1,2);
                }
            }else{
                $action = "withdraw";
                if(in_array($arr['CurrencyType'],$currency_one)){
					$amount = bcdiv(($post_data['amount'] * -1 / 1000), 1, 2);
				}else{
					$amount = bcdiv(($post_data['amount'] * -1), 1, 2);
				}
            }
            
            $param_array = array(
                'action' => $action,
                'secret' => $arr['Secret'],
                'agent' => $arr['Agent'],
                'username' => $post_data['game_id'],
                'serial' => $requestOrderIDAlias,
                'amount' => $amount,
            );
		}
		else if($method == 'LogoutGame')
		{
		    $param_array = array(
                'action' => 'logout',
                'secret' => $arr['Secret'],
                'agent' => $arr['Agent'],
                'username' => $post_data['game_id'],
            );
		}
		
		$url .= "?" . http_build_query($param_array);
        $response = $this->curl_get_json($url);
        if($response['code'] == '0')
        {
            $result_array = json_decode($response['data'], TRUE);
    		if($method == 'CreateMember')
        	{   
        	    if(isset($result_array['errcode']) && $result_array['errcode'] == '0'){
				    $output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $param_array['username'];
				    $output['gamePassword'] = $post_data['password'];
				}else if(isset($result_array['errcode']) && $result_array['errcode'] == '1'){
			        $output['errorCode'] = ERROR_USERNAME_EXITS;
				    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}else if(isset($result_array['errcode']) && $result_array['errcode'] == '-1'){
				    $output['errorCode'] = ERROR_SYSTEM_MAINTENANCE;
			        $output['errorMessage'] = $this->lang->line('error_system_maintenance');
				}
        	}
    		else if($method == 'LoginGame')
    		{
    		    if(isset($result_array['errcode']) && $result_array['errcode'] == '0'){
                    $output['result'] = $result_array['result'];
    		        $param_array_update = array(
                        'action' => 'update',
                        'secret' => $arr['Secret'],
                        'agent' => $arr['Agent'],
                        'username' => $post_data['game_id'],
                        'max1' => $arr['setting']['max1'],
                        'max2' => $arr['setting']['max2'],
                        'max3' => $arr['setting']['max3'],
                        'max4' => $arr['setting']['max4'],
                        'max5' => $arr['setting']['max5'],
                        'lim1' => $arr['setting']['lim1'],
                        'lim2' => $arr['setting']['lim2'],
                        'lim3' => $arr['setting']['lim3'],
                        'lim4' => $arr['setting']['lim4'],
                        'lim5' => $arr['setting']['lim5'],
                        'comtype' => $arr['setting']['comtype'],
                        'com1' => $arr['setting']['com1'],
                        'com2' => $arr['setting']['com2'],
                        'com3' => $arr['setting']['com3'],
                        'com4' => $arr['setting']['com4'],
                        'suspend' => 0,
                    );
                    $url_update .= "?" . http_build_query($param_array_update);
                    $response_update = $this->curl_get_json($url_update);
                    if($response_update['code'] == '0')
                    {
                        $result_update_array = json_decode($response_update['data'], TRUE);
                        if(isset($result_update_array['errcode']) && $result_update_array['errcode'] == '0'){
    					    $output['errorCode'] = ERROR_SUCCESS;
    						$output['errorMessage'] = $this->lang->line('error_success');
    					}else if(isset($result_update_array['errcode']) && $result_update_array['errcode'] == '1'){
    				        $output['errorCode'] = ERROR_USERNAME_EXITS;
    					    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
    					}else if(isset($result_update_array['errcode']) && $result_update_array['errcode'] == '-1'){
    					    $output['errorCode'] = ERROR_SYSTEM_MAINTENANCE;
    				        $output['errorMessage'] = $this->lang->line('error_system_maintenance');
    					}else if(isset($result_update_array['errcode']) && $result_update_array['errcode'] == '-8'){
                            $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    					    $output['errorMessage'] = $this->lang->line('error_username_not_found');
    					}
                    }
    		    }
    		}
    		else if($method == 'GetBalance')
    		{
    		    if(isset($result_array['errcode']) && $result_array['errcode'] == '0'){
                    $output['errorCode'] = ERROR_SUCCESS;
    		        $output['errorMessage'] = $this->lang->line('error_success');
    		        if(in_array($arr['CurrencyType'],$currency_one)){
                        $output['result'] = bcdiv($result_array['result'] * 1000, 1, 2);
                    }else{
                        $output['result'] = bcdiv($result_array['result'], 1, 2);   
                    }
                }else if(isset($result_array['errcode']) && $result_array['errcode'] == '-8'){
                    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
                }
    		}
    		else if($method == 'ChangeBalance')
    		{
    		    if(isset($result_array['errcode']) && $result_array['errcode'] == '0'){
                    $output['errorCode'] = ERROR_SUCCESS;
    		        $output['errorMessage'] = $this->lang->line('error_success');
    		        if(in_array($arr['CurrencyType'],$currency_one)){
                        $output['result'] = bcdiv($result_array['result'] * 1000, 1, 2);
                    }else{
                        $output['result'] = bcdiv($result_array['result'], 1, 2);   
                    }
                }else if(isset($result_array['errcode']) && $result_array['errcode'] == '-8'){
                    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
                }else if(isset($result_array['errcode']) && $result_array['errcode'] == '-1'){
				    $output['errorCode'] = ERROR_SYSTEM_MAINTENANCE;
			        $output['errorMessage'] = $this->lang->line('error_system_maintenance');
                }else{
                    $output['errorCode'] = ERROR_OVERTIME;
				    $output['errorMessage'] = $this->lang->line('error_overtime');
                }
    		}
    		else if($method == 'LogoutGame')
    		{
    		    if(isset($result_array['errcode']) && $result_array['errcode'] == '0'){
                    $output['errorCode'] = ERROR_SUCCESS;
    		        $output['errorMessage'] = $this->lang->line('error_success');
                }else if(isset($result_array['errcode']) && $result_array['errcode'] == '-8'){
                    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
                }else if(isset($result_array['errcode']) && $result_array['errcode'] == '-1'){
				    $output['errorCode'] = ERROR_SYSTEM_MAINTENANCE;
			        $output['errorMessage'] = $this->lang->line('error_system_maintenance');
				}
    		}
        }
        else
    	{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}
		
		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		return $output;
	}
	
	#{"APIUrl":"", "AccessKeyId":"", "AccessKeySecret":""}
	private function bl_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$amount = 0;
		$param_array = array();
		$result_array = array();
		$curl_array = array();
		$this->load->library('rng');
		if($method == 'CreateMember'){
			$param_array = array(
				"player_account" => $post_data['username'],
				"AccessKeyId" => $arr['AccessKeyId'],
				"Timestamp" => time(),
				"Nonce" => $this->rng->get_token(128)
			);
		}else{
			$param_array = array(
				"player_account" => $post_data['game_id'],
				"AccessKeyId" => $arr['AccessKeyId'],
				"Timestamp" => time(),
				"Nonce" => $this->rng->get_token(128)
			);
		}

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
			$requestOrderIDAlias = $post_data['order_id'];
			$param_array['operator_order_id'] = $requestOrderIDAlias;
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/v1/player/logout';
		}

		$param_array['Sign'] = strtolower(sha1($arr['AccessKeySecret'] . $param_array['Nonce'] . $param_array['Timestamp']));

		$response = $this->curl_post($url, $param_array);
		$curl_array = $response['curl'];
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
					if($method == 'CreateMember')
					{
						$output['gameID'] = $param_array['player_account'];
						$output['gamePassword'] = $post_data['password'];
					}
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
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array,$curl_array);
		$this->db->trans_complete();
		
		return $output;
	}

	#{"APIUrl":"","Token":"","Brand":"","CurrencyType":"","Theme":"","TZ":""}
	private function bng_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$game_url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$amount = 0;
		$param_array = array();
		$result_array = array();
		$curl_array = array();

		if($method == 'CreateMember')
		{
		    $url .= '/wallet/transfer/create_player/';
		    $param_array = array(
	            'api_token' => $arr['Token'],
	            'player_id' => $post_data['username'],
	            'currency' => $arr['CurrencyType'],
	            'mode' => "REAL",
	            'is_test' => FALSE,
	        );
	        if(!empty($arr['Brand'])){
	            $param_array['brand'] = $arr['Brand'];
	        }
		}
		else if($method == 'LoginGame')
		{
		    $url .= '/wallet/transfer/get_player_token/';
            $game_url .= "/game.html";
            $param_array = array(
	            'api_token' => $arr['Token'],
	            'player_id' => $post_data['game_id'],
	            'currency' => $arr['CurrencyType'],
	            'mode' => "REAL",
	            'tag' => "OG".$post_data['game_id'].time().rand(100000,999999),
	            'brand' => "",
	        );
		}
		else if($method == 'GetBalance')
		{
		    $url .= '/wallet/transfer/get_player';
            $param_array = array(
	            'api_token' => $arr['Token'],
	            'player_id' => $post_data['game_id'],
	            'currency' => $arr['CurrencyType'],
	            'mode' => "REAL",
	            'brand' => "",
	        );
		}
		else if($method == 'ChangeBalance')
		{
		    $requestOrderIDAlias = $post_data['order_id'];
            if($post_data['amount'] > 0) 
			{
			    $type = "CREDIT";
                $amount = $post_data['amount'];
			}else{
			    $type = "DEBIT";
			    $amount = bcdiv(($post_data['amount'] * -1), 1, 2);
			}
            
            $url .= '/wallet/transfer/transfer_balance';
            $param_array = array(
	            'api_token' => $arr['Token'],
	            'player_id' => $post_data['game_id'],
	            'currency' => $arr['CurrencyType'],
	            'mode' => "REAL",
	            'uid' => $requestOrderIDAlias,
	            'amount' => $amount,
	            'type' => $type,
	            'brand' => "",
	        );
		}
		else if($method == 'LogoutGame')
		{
		    $url .= '/wallet/transfer/logout_player';
            $param_array = array(
	            'api_token' => $arr['Token'],
	            'player_id' => $post_data['game_id'],
	            'currency' => $arr['CurrencyType'],
	            'mode' => "REAL",
	            'brand' => "",
	        );
		}

		$response = $this->curl_json($url, $param_array);
		$curl_array = $response['curl'];
		if($response['code'] == '0')
		{
		    $result_array = json_decode($response['data'], TRUE);
		    if($method == 'CreateMember')
    		{
    		    if(isset($result_array['player_id'])){
			        $output['errorCode'] = ERROR_SUCCESS;
			        $output['errorMessage'] = $this->lang->line('error_success');
				    $output['gameID'] = $param_array['player_id'];
				    $output['gamePassword'] = $post_data['password'];
			    }
    		}
    		else if($method == 'LoginGame')
    		{
    		    if(isset($result_array['player_token'])){
			        $output['errorCode'] = ERROR_SUCCESS;
			        $output['errorMessage'] = $this->lang->line('error_success');
			        $token = $result_array['player_token'];
			        $lang = 'en';
		
					switch($post_data['syslang'])
					{
						case LANG_ZH_CN: $lang = 'zh'; break;
						case LANG_ZH_HK:
						case LANG_ZH_TW: $lang = 'zh-hant'; break;
						case LANG_ID: $lang = 'id'; break;
						case LANG_TH: $lang = 'th'; break;
						case LANG_VI: $lang = 'vi'; break;
						case LANG_JA: $lang = 'ja'; break;
						case LANG_KO: $lang = 'ko'; break;
					}
			        $game_param_array = array(
			            'token' => $token,
			            'game' => $post_data['game_code'],
			            'ts' => time(),
			            'platform' =>  (($post_data['device'] == PLATFORM_WEB) ? 'desktop' : 'mobile'),
			            'wl' => "transfer",
			            'theme' => $arr['Theme'],
			            'lang' => $lang,
			            'tz' => $arr['TZ'],
			        );
			        $game_url .= "?" . http_build_query($game_param_array);
			        $output['result'] = $game_url;
			    }else if(isset($result_array['error']) && $result_array['error'] == "PLAYER_NOT_FOUND"){
			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
			    }
    		}
    		else if($method == 'GetBalance')
    		{
    		    if(isset($result_array['player_id'])){
			        $output['errorCode'] = ERROR_SUCCESS;
			        $output['errorMessage'] = $this->lang->line('error_success');
			        $output['result'] = bcdiv($result_array['balance'], 1, 2);
			    }else if(isset($result_array['error']) && $result_array['error'] == "PLAYER_NOT_FOUND"){
			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
			    }
    		}
    		else if($method == 'ChangeBalance')
    		{
    		    if(isset($result_array['player_id'])){
			        $output['errorCode'] = ERROR_SUCCESS;
			        $output['errorMessage'] = $this->lang->line('error_success');
			        $output['result'] = bcdiv($result_array['balance_after'], 1, 2);
    			}else if(isset($result_array['error']) && $result_array['error'] == "PLAYER_NOT_FOUND"){
			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
    			}else if(isset($result_array['error']) && $result_array['error'] == "INSUFFICIENT_FUNDS"){
			        $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
    			}else if(isset($result_array['error'])){
			    }else{
			        $output['errorCode'] = ERROR_OVERTIME;
				    $output['errorMessage'] = $this->lang->line('error_overtime');
			    }
    		}
    		else if($method == 'LogoutGame')
    		{
    		    if(isset($result_array['player_id'])){
			        $output['errorCode'] = ERROR_SUCCESS;
			        $output['errorMessage'] = $this->lang->line('error_success');
    			}else if(isset($result_array['error']) && $result_array['error'] == "PLAYER_NOT_FOUND"){
			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
    			}
    		}
		}else{
		    if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}
		
		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
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
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$amount = 0;
		$param_array = array();
		$result_array = array();
		$curl_array = array();

		$random = rand(100000, 999999);
		$amount = 0;
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
				'currencyName' => $arr['CurrencyType'],
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
					'username' => $post_data['game_id'],
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
				'username' => $post_data['game_id']
			);
		}
		else if($method == 'ChangeBalance')
		{
			$requestOrderIDAlias = $post_data['order_id'];
			$url .= '/account/transfer/' . $arr['AgentName'];
			$param_array['data'] = $requestOrderIDAlias;

			if($arr['CurrencyType'] == "KRW2" || $arr['CurrencyType'] == "MMK2" || $arr['CurrencyType'] == "VND2" || $arr['CurrencyType'] == "IDR2" || $arr['CurrencyType'] == "LAK2"){
				$amount = bcdiv($post_data['amount']/1000,1,2);
			}else{
				$amount = $post_data['amount'];
			}
			$param_array['member'] = array(
				'username' => $post_data['game_id'],
				'amount' => $amount
			);
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/user/onlineReport/' . $arr['AgentName'];
		}
		
		//Get response from curl
		$response = $this->curl_json($url, $param_array);
		$curl_array = $response['curl'];
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['codeId']) && $result_array['codeId'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $param_array['member']['username'];
					$output['gamePassword'] = $param_array['member']['password'];
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

					if($arr['CurrencyType'] == "KRW2" || $arr['CurrencyType'] == "MMK2" || $arr['CurrencyType'] == "VND2" || $arr['CurrencyType'] == "IDR2" || $arr['CurrencyType'] == "LAK2"){
						$output['result'] = bcdiv($result_array['member']['balance']*1000, 1, 2);
					}else{
						$output['result'] = bcdiv($result_array['member']['balance'], 1, 2);
					}
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
					if($arr['CurrencyType'] == "KRW2" || $arr['CurrencyType'] == "MMK2" || $arr['CurrencyType'] == "VND2" || $arr['CurrencyType'] == "IDR2" || $arr['CurrencyType'] == "LAK2"){
						$output['result'] = bcdiv($result_array['member']['balance']*1000, 1, 2);
					}else{
						$output['result'] = bcdiv($result_array['member']['balance'], 1, 2);
					}
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
							if(strtolower($result_array['list'][$i]['username']) == strtolower($post_data['game_id']))
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
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "APIKey":"", "GameProvider":"", "CurrencyType":"", "CountryCode":""}
	private function dgg_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = $requestOrderID;
		$curl_array = array();
		$param_array = array(
							'api_key' => $arr['APIKey'],
							'username' => $post_data['username'],
							'password' => $post_data['password'],
						);
		
		if($method == 'CreateMember')
		{
			$url .= '/player/create-player/';
			$param_array['player_id'] = $post_data['player_id'];
			$param_array['currency'] = $arr['CurrencyType'];
			$param_array['country'] = $arr['CountryCode'];
		}
		else if($method == 'LoginGame')
		{
			$game_type = 'slots';
			
			switch($post_data['game_type_code'])
			{
				case GAME_BOARD_GAME: $game_type = 'table_games'; break;
				case GAME_OTHERS: $game_type = 'scratch_cards'; break;
			}
			
			$language = 'en';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN:
				case LANG_ZH_HK:
				case LANG_ZH_TW: $language = 'ch'; break;
				case LANG_ID: $language = 'id'; break;
				case LANG_TH: $language = 'th'; break;
				case LANG_VI: $language = 'vn'; break;
				case LANG_JA: $language = 'ja'; break;
				case LANG_KO: $language = 'ko'; break;
			}
			
			$url .= '/games/enter-game/';
			$param_array['provider'] = $arr['GameProvider'];
			$param_array['game_type'] = $game_type;
			$param_array['game_id'] = $post_data['game_code'];
			$param_array['platform'] = (($post_data['device'] == PLATFORM_WEB) ? 'desktop' : 'mobile');
			$param_array['language'] = $language;
			
			if($post_data['is_demo'] == STATUS_YES)
			{
				$param_array['amount_type'] = 'fun';
				$param_array['username'] = 'fun';
				$param_array['password'] = 'fun';
			}
			else
			{
				$param_array['amount_type'] = 'real';
			}
		}
		else if($method == 'GetBalance')
		{
			$url .= '/wallet/wallet-get-balance/';
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$url .= '/wallet/wallet-deposit/';
				$param_array['amount'] = $post_data['amount'];
			}
			else
			{
				$url .= '/wallet/wallet-withdraw/';
				$param_array['amount'] = ($post_data['amount'] * -1);
			}
			
			$param_array['transaction_id'] = $requestOrderID;
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/games/game-exit/';
			unset($param_array['password']);
		}
		
		//Get response from curl
		$response = $this->curl_json($url, $param_array);
		$curl_array = $response['curl'];
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['result']['status']) && $result_array['result']['status'] == 'success')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $post_data['username'];
					$output['gamePassword'] = $post_data['password'];
				}
				else if(isset($result_array['error_detail']['id']) && $result_array['error_detail']['id'] == '2062')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
					$output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['result']) && ! empty($result_array['result']))
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $result_array['result']['launch_url'];
				}
				else if(isset($result_array['error_detail']['id']) && $result_array['error_detail']['id'] == '3002')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['result']['status']) && $result_array['result']['status'] == 'success')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['result']['amount'], 1, 2);
				}
				else if(isset($result_array['error_detail']['id']) && $result_array['error_detail']['id'] == '3002')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['result']['status']) && $result_array['result']['status'] == 'success')
				{
					$url = $arr['APIUrl'] . '/wallet/wallet-transaction-status/';
					
					if($post_data['amount'] > 0) 
					{
						$param_array['transaction_type'] = 'deposit';
					}
					else
					{
						$param_array['transaction_type'] = 'withdraw';
					}
					
					$response_2 = $this->curl_json($url, $param_array);
					if($response_2['code'] == '0')
					{
						$result_array_2 = json_decode($response_2['data'], TRUE);
					
						if(isset($result_array_2['result']['status']) && $result_array_2['result']['status'] == 'success')
						{
							$output['errorCode'] = ERROR_SUCCESS;
							$output['errorMessage'] = $this->lang->line('error_success');
							$output['result'] = bcdiv($result_array['result']['amount'], 1, 2);
						}						
					}
				}
				else if(isset($result_array['error_detail']['id']) && $result_array['error_detail']['id'] == '3002')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['error_detail']['id']) && $result_array['error_detail']['id'] == '2028')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['result']['status']) && $result_array['result']['status'] == 'success')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['error_detail']['id']) && $result_array['error_detail']['id'] == '2024')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "Token":""}
	private function ds88_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = $requestOrderID;
		$curl_array = array();
		
		if($method == 'LoginGame')
		{
			$param_array = array(
								'login' => $post_data['username']
							);
		}
		else
		{
			$param_array = array(
								'account' => $post_data['username']
							);
		}				
		
		if($method == 'CreateMember')
		{
			$url .= '/api/merchant/players';
			$param_array['password'] = $post_data['password'];
			$param_array['name'] = $post_data['username'];
		}
		else if($method == 'LoginGame')
		{
			$url .= '/api/merchant/player/login';
			
			$param_array['password'] = $post_data['password'];
			
			$lang = 'en';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $lang = 'cn'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $lang = 'zh-TW'; break;
				case LANG_ID: $lang = 'id'; break;
				case LANG_TH: $lang = 'th'; break;
				case LANG_VI: $lang = 'vi'; break;
			}
			
			$param_array['lang'] = $lang;
		}
		else if($method == 'GetBalance')
		{
			$url .= '/api/merchant/player/balance';
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$url .= '/api/merchant/player/deposit';
			}
			else
			{
				$url .= '/api/merchant/player/withdraw';
			}
			
			$param_array['amount'] = $post_data['amount'];
			$param_array['merchant_order_num'] = $requestOrderID;
		}
		
		if($method == 'LogoutGame')
		{
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
		}
		else
		{
			//Get response from curl
			if($method == 'GetBalance')
			{
				$response = $this->curl_get($url . '?' . http_build_query($param_array), "Authorization: Bearer " . $arr['Token']);
			}
			else
			{
				$response = $this->curl_json($url, $param_array, "Authorization: Bearer " . $arr['Token']);
			}
			
			$curl_array = $response['curl'];
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['code']) && $result_array['code'] == 'OK')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['gameID'] = $post_data['username'];
						$output['gamePassword'] = $post_data['password'];
					}
					else
					{
						$output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
				}
				else if($method == 'LoginGame')
				{
					if(isset($result_array['code']) && $result_array['code'] == 'OK')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = $result_array['game_link'];
					}
					else
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['code']) && $result_array['code'] == 'OK')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['balance'], 1, 2);
					}
					else
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['code']) && $result_array['code'] == 'OK')
					{
						$url_2 = $arr['APIUrl'] . '/api/merchant/player/check?merchant_order_num=' . $result_array['merchant_order_num'];
						$response_2 = $this->curl_get($url_2, "Authorization: Bearer " . $arr['Token']);
						if($response_2['code'] == '0')
						{
							$result_array_2 = json_decode($response_2['data'], TRUE);
						
							if(isset($result_array_2['code']) && $result_array_2['code'] == 'OK')
							{
								$output['errorCode'] = ERROR_SUCCESS;
								$output['errorMessage'] = $this->lang->line('error_success');
								$output['result'] = bcdiv($result_array['balance'], 1, 2);
							}						
						}
					}
					else if(isset($result_array['message']) && strtolower($result_array['message']) == 'player balance is not enough')
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
					else
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
			}else{
				if($response['code'] == '404'){
					$output['errorCode'] = ERROR_OVERTIME;
					$output['errorMessage'] = $this->lang->line('error_overtime');
				}
			}
		}	

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
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
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$amount = 0;
		$param_array = array();
		$result_array = array();
		$curl_array = array();
		
		if($method == 'CreateMember'){
			$param_array = array(
				'BUSINESS' => $arr['BusinessCode'],
				'PLAYERNAME' => strtoupper($post_data['username'])
			);
		}else{
			$param_array = array(
				'BUSINESS' => $arr['BusinessCode'],
				'PLAYERNAME' => strtoupper($post_data['game_id'])
			);
		}
		
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
			$requestOrderIDAlias = $post_data['order_id'];
			if($post_data['amount'] > 0) 
			{
				$param_array['METHOD'] = 'DEPOSIT';
				$param_array['PRICE'] = $post_data['amount'];
				$param_array['TRANSFER_ID'] = $requestOrderIDAlias;
				$param_array['CURRENCY'] = $arr['Currency'];
				$param_array['SIGNATURE'] = md5($param_array['BUSINESS'] . $param_array['METHOD'] . $param_array['PLAYERNAME'] . $param_array['PRICE'] . $arr['APIKey'] . $param_array['CURRENCY']);
			}
			else
			{
				$param_array['METHOD'] = 'WITHDRAW';
				$param_array['PRICE'] = bcdiv(($post_data['amount'] * -1), 1, 2);
				$param_array['TRANSFER_ID'] = $requestOrderIDAlias;
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
		$param_array['md5'] = $param_array['BUSINESS'] . $param_array['METHOD'] . $param_array['PLAYERNAME'] . $arr['APIKey'];
		$curl_array = $response['curl'];
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['RESPONSECODE']) && $result_array['RESPONSECODE'] == '00000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $param_array['PLAYERNAME'];
					$output['gamePassword'] = $param_array['PLAYERPASSWORD'];
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
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array,$curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	public function evop_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL){
		//Initial output data
		$hash_array = array();
		$arr = json_decode($api_data, TRUE);
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		$result_array = "";

		$param_array =  array(
	        'project' => $arr['Project'],
	        'version' => $arr['Version'],
	        'signature' => $arr['Signature'],
	    );


		if($method == 'LoginGame'){
			$url .= "/Game/getIFrameURLAdvanced?";
			$language = 'en';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $language = 'cn'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $language = 'zh'; break;
				case LANG_ID: $language = 'id'; break;
				case LANG_TH: $language = 'th'; break;
				case LANG_JA: $language = 'ja'; break;
				case LANG_KO: $language = 'ko'; break;
			}
			
			$param_array =  array(
    	        'project' => $arr['Project'],
	            'version' => $arr['Version'],
    	        'user_id' => $post_data['game_id'],
    	        'game' => $post_data['game_code'],
    	        'settings' => array(
    	            'language' => $language,
    	            'https' => $arr['Https'],
    	        ),
    	        'denomination' => $arr['Denomination'],
    	        'return_url_info' => $arr['ReturnUrlInfo'],
    	        'signature' => $arr['Signature'],
    	    );
		}
		else if($method == 'CreateMember')
		{
			$url .= "/User/registerWithName?";
			$param_array =  array(
    	        'project' => $arr['Project'],
	            'version' => $arr['Version'],
    	        'user_name' => $post_data['username'],
    	        'currency' => $arr['CurrencyType'],
    	        'signature' => $arr['Signature'],
    	    );
		}
		else if($method == 'GetBalance')
		{
			$url .= "/User/infoById?";
			$param_array =  array(
    	        'project' => $arr['Project'],
	            'version' => $arr['Version'],
    	        'user_id' => $post_data['game_id'],
    	        'signature' => $arr['Signature'],
    	    );
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$url .= "/Finance/deposit?";
				$amount = $post_data['amount'];
			}else{
			    $url .= "/Finance/withdrawal?";
				$amount = bcdiv(($post_data['amount'] * -1), 1, 2);
			}
			$requestOrderIDAlias = $requestOrderID;
			
			$param_array =  array(
    	        'project' => $arr['Project'],
	            'version' => $arr['Version'],
    	        'wl_transaction_id' => $requestOrderIDAlias,
    	        'user_id' => $post_data['game_id'],
    	        'sum' => $amount,
    	        'currency' => $arr['CurrencyType'],
    	        'signature' => $arr['Signature'],
    	    );
		}
		else if($method == 'LogoutGame')
		{
			$url .= "/Game/getIFrameURLAdvanced?";
			$param_array =  array(
    	        'project' => $arr['Project'],
	            'version' => $arr['Version'],
    	        'user_id' => $post_data['game_id'],
    	        'game' => 5547,
    	        'settings' => array(
    	            'language' => $language,
    	            'https' => $arr['Https'],
    	        ),
    	        'denomination' => $arr['Denomination'],
    	        'return_url_info' => $arr['ReturnUrlInfo'],
    	        'signature' => $arr['Signature'],
    	    );
		}

		foreach ($param_array as $key => $value){
            $hash_array[$key] = is_array($value) ? implode(":", $value) : $value;
	    }	
	    $param_array['signature'] = md5(implode('*', $hash_array));
	    $real_param = http_build_query($param_array);
	    $url .= $real_param;
	    $response = $this->curl_get($url);
	    $curl_array = $response['curl'];

	    if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
		    if($method == 'LoginGame'){
		    	if(isset($result_array['error']))
				{
					if(isset($result_array['error']['message']) && $result_array['error']['message'] = "User doesn't exist"){
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}else if(isset($result_array['link'])){
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $result_array['link'];
				}
			}
			else if($method == 'CreateMember')
			{
				if(isset($result_array['error']))
				{

				}else if(isset($result_array['user_id'])){
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $result_array['user_id'];
					$output['gamePassword'] = $post_data['password'];
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['error']))
				{
					if(isset($result_array['error']['message']) && $result_array['error']['message'] = "User doesn't exist"){
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}else{
				    $output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['balance'], 1, 2);
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['error']))
				{
					if(isset($result_array['error']['message']) && $result_array['error']['message'] = "User doesn't exist"){
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['error']['message']) && $result_array['error']['message'] = "Not enough money"){
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}else{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = 0;
				}
			}
			else if($method == 'LogoutGame')
			{
				$output['errorCode'] = ERROR_SUCCESS;
				$output['errorMessage'] = $this->lang->line('error_success');
			}
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "ClientID":"", "InviteCode":"", "APIKey":"", "CurrencyType":""}
	private function ftg_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = $requestOrderID;
		$curl_array = array();
		$result_array = array();
		$param_array =  array(
			'username' => $post_data['username'],
			'client_id' => $arr['ClientID'],
		);
		
		if($method == 'CreateMember')
		{
			$url .= '/api/user/outside';
			
			$param_array['currency'] = $arr['CurrencyType'];
			$param_array['invite_code'] = $arr['InviteCode'];
		}
		else if($method == 'LoginGame')
		{
			$url .= '/api/game/outside/link';
			
			$lang = 'en';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $lang = 'zh'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $lang = 'zh_tw'; break;
				case LANG_ID: $lang = 'in'; break;
				case LANG_TH: $lang = 'th'; break;
				case LANG_VI: $lang = 'vi'; break;
				case LANG_JA: $lang = 'ja'; break;
				case LANG_KO: $lang = 'ko'; break;
			}
			
			$param_array['game_id'] = $post_data['game_code'];
			$param_array['ip'] = $this->input->ip_address();
			$param_array['lang'] = $lang;
			$param_array['user_agent'] = $this->input->user_agent();
			$param_array['invite_code'] = $arr['InviteCode'];
			
			if( ! empty($post_data['return_url']))
			{
				$game_url_array['back_url'] = $post_data['return_url'];
			}
		}
		else if($method == 'GetBalance')
		{
			$url .= '/api/user/outside/balance?username=' . $param_array['username'] . '&client_id' . $param_array['client_id'];
		}
		else if($method == 'ChangeBalance')
		{
			$param_array['currency'] = $arr['CurrencyType'];
			$param_array['invite_code'] = $arr['InviteCode'];
			
			if($post_data['amount'] > 0)
			{
				$url .= '/api/cash/outside/deposit';
				$param_array['amount'] = $post_data['amount'];
			}
			else
			{
				$url .= '/api/cash/outside/withdraw';
				$param_array['amount'] = ($post_data['amount'] * -1);
			}
			
			$param_array['reference_id'] = $requestOrderID;
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/api/user/outside/kick';
		}
		
		$this->load->helper('jwt');
		$jwt = new JWT();
		$param_array['iat'] = (int)microtime(true);
		$json = json_encode($param_array);
		$jwt_token = $jwt->encode($json, $arr['APIKey'], 'HS256');
		
		//Get response from curl
		if($method == 'GetBalance')
		{
			$response = $this->curl_get($url, "Authorization: Bearer " . $jwt_token);
		}
		else
		{
			$response = $this->curl_json($url, $param_array, "Authorization: Bearer " . $jwt_token);
		}
		
		$curl_array = $response['curl'];
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['user']['username']))
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $post_data['username'];
					$output['gamePassword'] = $post_data['password'];
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == '00-0306-00-05-009')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
					$output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['link']))
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $result_array['link'];
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == '00-1502-00-07-001')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['user']['balance']))
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['user']['balance'], 1, 2);
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == '00-0369-00-07-001')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['cash_entry']['amount']))
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['cash_entry']['balance'], 1, 2);
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == '00-1102-00-05-029')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['result']) && $result_array['result'] == 'success')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}
				else if(isset($result_array['error_code']) && $result_array['error_code'] == '00-0302-00-07-001')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $url, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	private function gfgd_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
	    $currency_one = array("IDR", "VND");
	    
	    $arr = json_decode($api_data, TRUE);
	    
	    //Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		$result_array = array();
		$param_array = array();
		

		if($method == 'LogoutGame')
		{
		    
		}else{
    		if($method == 'CreateMember')
    		{
    		    $url .= '/Player/Create';
                $param_array = array(
    		        'secret_key' => $arr['SecretKey'],
    		        'operator_token' => $arr['OperatorToken'],
    		        'player_name' => $arr['Prefix']."_".((substr($post_data['username'], 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($post_data['username'], strlen($sys_data['system_prefix'])) : $post_data['username']),
    		        'currency' => $arr['Currency'],
    		    );
    		}
    		else if($method == 'LoginGame')
    		{
    		    $url .= '/Launch';
                
                $lang = 'en-US';
			
    			switch($post_data['syslang'])
    			{
    				case LANG_ZH_CN: $lang = 'zh-CN'; break;
    				case LANG_ZH_HK:
    				case LANG_ZH_TW: $lang = 'zh-TW'; break;
    				case LANG_ID: $lang = 'ID'; break;
    				case LANG_TH: $lang = 'TH'; break;
    				case LANG_VI: $lang = 'VI'; break;
    				case LANG_JA: $lang = 'JA'; break;
    				case LANG_KO: $lang = 'KO'; break;
    				case LANG_MY: $lang = 'MY'; break;
    				case LANG_TR: $lang = 'TR'; break;
    			}
			
                $param_array = array(
    		        'secret_key' => $arr['SecretKey'],
    		        'operator_token' => $arr['OperatorToken'],
    		        'player_name' => $post_data['game_id'],
    		        'game_code' => "xgd_lobby",
    		        'language' => $lang,
    		    );
    		    
    		    if( ! empty($post_data['game_code']))
				{
					$param_array['game_code'] = $post_data['game_code'];
				}
				
    		    if( ! empty($arr['Limit']))
				{
					$param_array['limit'] = $arr['Limit'];
				}
    		}
    		else if($method == 'GetBalance')
    		{
    		    $url .= '/GetPlayerBalance';
                $param_array = array(
    		        'secret_key' => $arr['SecretKey'],
    		        'operator_token' => $arr['OperatorToken'],
    		        'player_name' => $post_data['game_id'],
    		        'wallet_code' => $arr['WalletCode'],
    		    );
    		}
    		else if($method == 'ChangeBalance')
    		{
    		    $requestOrderIDAlias = $post_data['order_id'];
                if($post_data['amount'] > 0) 
    			{
    			    $url .= "/TransferIn";
    			    if(in_array($arr['Currency'],$currency_one)){
    					$amount = bcdiv($post_data['amount'] / 1000,1,2);
    				}else{
    					$amount = $post_data['amount'];
    				}
    			}else{
    			    $url .= "/TransferOut";
    			    if(in_array($arr['Currency'],$currency_one)){
    					$amount = bcdiv(($post_data['amount'] * -1 / 1000), 1, 2);
    				}else{
    					$amount = bcdiv(($post_data['amount'] * -1), 1, 2);
    				}
    			}
    			
                $param_array = array(
    		        'secret_key' => $arr['SecretKey'],
    		        'operator_token' => $arr['OperatorToken'],
    		        'player_name' => $post_data['game_id'],
    		        'amount' => $amount,
    		        'traceId' => $requestOrderIDAlias,
    		        'wallet_code' => $arr['WalletCode'],
    		    );
            }
            
            $response = $this->curl_json($url,$param_array);
            $curl_array = $response['curl'];
    		if($response['code'] == '0')
    		{
    		    $result_array = json_decode($response['data'], TRUE);
    		    if($method == 'CreateMember'){
    		        if(isset($result_array['error']) && !empty($result_array['error'])){
        		        if(isset($result_array['error']['code']) && $result_array['error']['code'] == "9400"){
        		            $output['errorCode'] = ERROR_GAME_MAINTENANCE;
					        $output['errorMessage'] = $this->lang->line('error_game_maintenance');
        		        }else if(isset($result_array['error']['code']) && $result_array['error']['code'] == "9411"){
        		            $output['errorCode'] = ERROR_USERNAME_EXITS;
    				        $output['errorMessage'] = $this->lang->line('error_username_already_exits');   
        		        }
        		    }else{
        		        if(isset($result_array['data']['action_result']) && $result_array['data']['action_result'] == "Success"){
        		            $output['errorCode'] = ERROR_SUCCESS;
    					    $output['errorMessage'] = $this->lang->line('error_success');
    					    $output['gameID'] = $param_array['player_name'];
    					    $output['gamePassword'] = $post_data['password'];
        		        }
        		    }
    		    }else if($method == 'LoginGame'){
    		        if(isset($result_array['error']) && !empty($result_array['error'])){
        		        if(isset($result_array['error']['code']) && $result_array['error']['code'] == "9400"){
        		            $output['errorCode'] = ERROR_GAME_MAINTENANCE;
					        $output['errorMessage'] = $this->lang->line('error_game_maintenance');
        		        }else if(isset($result_array['error']['code']) && $result_array['error']['code'] == "3004"){
        		            $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					        $output['errorMessage'] = $this->lang->line('error_username_not_found');
        		        }
        		    }else{
        		        if(isset($result_array['data']['action_result']) && $result_array['data']['action_result'] == "Success"){
    		                $output['errorCode'] = ERROR_SUCCESS;
    					    $output['errorMessage'] = $this->lang->line('error_success');
    					    $output['result'] = $result_array['data']['game_url'];
        		        }
        		    }
    		    }else if($method == 'GetBalance'){
    		        if(isset($result_array['error']) && !empty($result_array['error'])){
        		        if(isset($result_array['error']['code']) && $result_array['error']['code'] == "9400"){
        		            $output['errorCode'] = ERROR_GAME_MAINTENANCE;
					        $output['errorMessage'] = $this->lang->line('error_game_maintenance');
        		        }else if(isset($result_array['error']['code']) && $result_array['error']['code'] == "3004"){
        		            $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					        $output['errorMessage'] = $this->lang->line('error_username_not_found');
        		        }
        		    }else{
        		        if(isset($result_array['data']['action_result']) && $result_array['data']['action_result'] == "Success"){
        		            if($arr['Currency'] == $result_array['data']['currency']){
        		                $output['errorCode'] = ERROR_SUCCESS;
        					    $output['errorMessage'] = $this->lang->line('error_success');
        					    if(in_array($arr['Currency'],$currency_one)){
    							    $output['result'] = bcdiv($result_array['data']['balance']*1000, 1, 2);
        						}else{
        							$output['result'] = bcdiv($result_array['data']['balance'], 1, 2);
        						}   
        		            }
        		        }
        		    }
    		    }else if($method == 'ChangeBalance'){
    		        if(isset($result_array['error']) && !empty($result_array['error'])){
        		        if(isset($result_array['error']['code']) && $result_array['error']['code'] == "9400"){
        		            $output['errorCode'] = ERROR_GAME_MAINTENANCE;
					        $output['errorMessage'] = $this->lang->line('error_game_maintenance');
        		        }else if(isset($result_array['error']['code']) && $result_array['error']['code'] == "3004"){
        		            $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					        $output['errorMessage'] = $this->lang->line('error_username_not_found');
        		        }else{
        		            $output['errorCode'] = ERROR_OVERTIME;
					        $output['errorMessage'] = $this->lang->line('error_overtime');
        		        }
        		    }else{
        		        if(isset($result_array['data']['action_result']) && $result_array['data']['action_result'] == "Success"){
    		                $output['errorCode'] = ERROR_SUCCESS;
    					    $output['errorMessage'] = $this->lang->line('error_success');
    					    if(in_array($arr['Currency'],$currency_one)){
							    $output['result'] = bcdiv($result_array['data']['balance']*1000, 1, 2);
    						}else{
    							$output['result'] = bcdiv($result_array['data']['balance'], 1, 2);
    						}
        		        }else{
        		            $output['errorCode'] = ERROR_OVERTIME;
					        $output['errorMessage'] = $this->lang->line('error_overtime');
        		        }
        		    }
    		    }
    		}else{
    		    if($response['code'] == '404'){
    				$output['errorCode'] = ERROR_OVERTIME;
    				$output['errorMessage'] = $this->lang->line('error_overtime');
    			}
    		}
            
            //Database update
    		$this->db->trans_start();
    		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
    		$this->db->trans_complete();
		}
		return $output;
	}
	
	private function gr_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
	    $currency_one = array("VND1000", "IDR1000");
        $currency_two = array("XNB");
        $currency_three = array("XNB2");
        
	    $arr = json_decode($api_data, TRUE);
	    
	    //Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		$result_array = array();
		$param_array = array();
		
		
		if($method == 'CreateMember')
		{
		    $url .= "/api/platform/reg_user_info";
            $param_array = array(
    			"account" => $post_data['username'],
    			"display_name" => $post_data['username'],
    			"site_code" => $arr['SiteCode'],
    		);
		}
		else if($method == 'LoginGame')
		{
		    $url .= "/api/platform/get_sid_by_account";
		    $param_array = array(
    			"account" => $post_data['game_id'],
    		);
		}
		else if($method == 'GetBalance')
		{
		    $url .= "/api/platform/get_balance";
            $param_array = array(
    			"account" => $post_data['game_id'],
    		);
		}
		else if($method == 'ChangeBalance')
		{
		    $requestOrderIDAlias = $requestOrderID;
		    if($post_data['amount'] > 0) 
			{
			    $url .= "/api/platform/credit_balance_v3";
			    if(in_array($arr['Currency'],$currency_one)){
					$amount = bcdiv($post_data['amount'] / 1000,1,2);
			    }else if(in_array($arr['Currency'],$currency_two)){
			        $amount = bcdiv($post_data['amount'] / 100,1,2);
			    }else if(in_array($arr['Currency'],$currency_three)){
			        $amount = bcdiv($post_data['amount'] / 130,1,2);
				}else{
				    $amount = bcdiv($post_data['amount'],1,2);
				}
				
				$param_array = array(
        			"account" => $post_data['game_id'],
        			"credit_amount" => $amount,
        			"order_id" => $requestOrderIDAlias,
        		);
        		$param_array_string = '{"account":"'.$param_array['account'].'","credit_amount":'.$param_array['credit_amount'].',"order_id":"'.$param_array['order_id'].'"}';
			}else{
			    $url .= "/api/platform/debit_balance_v3";
			    if(in_array($arr['Currency'],$currency_one)){
					$amount = bcdiv($post_data['amount'] * -1 / 1000,1,2);
			    }else if(in_array($arr['Currency'],$currency_two)){
			        $amount = bcdiv($post_data['amount'] * -1 / 100,1,2);
			    }else if(in_array($arr['Currency'],$currency_three)){
			        $amount = bcdiv($post_data['amount'] * -1 / 130,1,2);
				}else{
				    $amount = bcdiv($post_data['amount'] * -1,1,2);
				}
				
				$param_array = array(
        			"account" => $post_data['game_id'],
        			"debit_amount" => $amount,
        			"order_id" => $requestOrderIDAlias,
        		);
        		$param_array_string = '{"account":"'.$param_array['account'].'","debit_amount":'.$param_array['debit_amount'].',"order_id":"'.$param_array['order_id'].'"}';
			}
		}
		else if($method == 'LogoutGame')
		{
		    $url .= "/api/platform/kick_user_by_account";
            $param_array = array(
    			"account" => $post_data['game_id'],
    		);
		}
		
		if($method == 'ChangeBalance'){
		    $response = $this->curl_json($url, $param_array_string,"Cookie:secret_key=".$arr['SecretKey']);
		}else{
		    $response = $this->curl_json($url, $param_array,"Cookie:secret_key=".$arr['SecretKey']);
		}
		$curl_array = $response['curl'];
		if($response['code'] == '0')
        {
            $result_array = json_decode($response['data'], TRUE);
            if($method == 'CreateMember')
		    {
		        if(isset($result_array['status']) && $result_array['status'] == 'Y'){
    		        $output['errorCode'] = ERROR_SUCCESS;
    			    $output['errorMessage'] = $this->lang->line('error_success');
    			    $output['gameID'] = $result_array['data']['account'];
    			    $output['gamePassword'] = $post_data['password'];
    		    }else{
    		        if(isset($result_array['code']) && $result_array['code'] == '112100008'){
        		        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    			        $output['errorMessage'] = $this->lang->line('error_username_not_found');
    		        }
    		    }
		    }
    		else if($method == 'LoginGame')
    		{
    		    if(isset($result_array['status']) && $result_array['status'] == 'Y'){
    		        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    $output['result'] = $result_array['data']['game_url'] . '/?sid=' . $result_array['data']['sid'].'&game_type='.$post_data['game_code'];
    		    }else{
    		        if(isset($result_array['code']) && $result_array['code'] == '111090005'){
        		        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				        $output['errorMessage'] = $this->lang->line('error_username_not_found');
    		        }else if(isset($result_array['code']) && $result_array['code'] == '111090014'){
        		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				        $output['errorMessage'] = $this->lang->line('error_game_maintenance');
    		        }
    		    }
    		}
    		else if($method == 'GetBalance')
    		{
    		    if(isset($result_array['status']) && $result_array['status'] == 'Y'){
    		        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    if(in_array($arr['Currency'],$currency_one)){
				        $output['result'] = bcdiv($result_array['data']['balance'] * 1000,1,2);
				    }else if(in_array($arr['Currency'],$currency_two)){
				        $output['result'] = bcdiv($result_array['data']['balance'] * 100,1,2);
				    }else if(in_array($arr['Currency'],$currency_three)){
				        $output['result'] = bcdiv($result_array['data']['balance'] * 130,1,2);
				    }else{
				        $output['result'] = bcdiv($result_array['data']['balance'],1,2);
				    }
    		    }else{
    		        if(isset($result_array['code']) && $result_array['code'] == '111090005'){
        		        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				        $output['errorMessage'] = $this->lang->line('error_username_not_found');
    		        }else if(isset($result_array['code']) && $result_array['code'] == '111090014'){
        		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				        $output['errorMessage'] = $this->lang->line('error_game_maintenance');
    		        }
    		    }
    		}
    		else if($method == 'ChangeBalance')
    		{
    		    if(isset($result_array['status']) && $result_array['status'] == 'Y'){
    			    $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
    			    if(in_array($arr['Currency'],$currency_one)){
				        $output['result'] = bcdiv($result_array['data']['balance'] * 1000,1,2);
				    }else if(in_array($arr['Currency'],$currency_two)){
				        $output['result'] = bcdiv($result_array['data']['balance'] * 100,1,2);
				    }else if(in_array($arr['Currency'],$currency_three)){
				        $output['result'] = bcdiv($result_array['data']['balance'] * 130,1,2);
				    }else{
				        $output['result'] = bcdiv($result_array['data']['balance'],1,2);
				    }
    			}else{
    			    if(isset($result_array['status']) && $result_array['status'] == 'N'){
        		        if(isset($result_array['code']) && $result_array['code'] == '111090005'){
            		        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					        $output['errorMessage'] = $this->lang->line('error_username_not_found');
        		        }else if(isset($result_array['code']) && $result_array['code'] == '111090014'){
            		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
					        $output['errorMessage'] = $this->lang->line('error_game_maintenance');
        		        }else if(isset($result_array['code']) && $result_array['code'] == '112110001'){
            		        $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
    					    $output['errorMessage'] = $this->lang->line('error_amount_insufficient');
        		        }else if(isset($result_array['code'])){
        		            
        		        }else{
        		            $output['errorCode'] = ERROR_OVERTIME;
					        $output['errorMessage'] = $this->lang->line('error_overtime');
        		        }
    			    }else{
    			        $output['errorCode'] = ERROR_OVERTIME;
					    $output['errorMessage'] = $this->lang->line('error_overtime');
    			    }
    		    }
    		}
    		else if($method == 'LogoutGame')
    		{
    		    if(isset($result_array['status']) && $result_array['status'] == 'Y'){
    		        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
    		    }else{
    		        if(isset($result_array['code']) && $result_array['code'] == '111090005'){
        		        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				        $output['errorMessage'] = $this->lang->line('error_username_not_found');
    		        }else if(isset($result_array['code']) && $result_array['code'] == '111090014'){
        		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				        $output['errorMessage'] = $this->lang->line('error_game_maintenance');
    		        }
    		    }
    		}
        }else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}
		
		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}

		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
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
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$amount = 0;
		$param_array = array();
		$result_array = array();
		$curl_array = array();				
		
		if($method == 'CreateMember')
		{
			$url .= '/api/v1/players';
			
			$param_array['username'] = $post_data['username'];
			$param_array['nickname'] = $post_data['username'];
			//$param_array['currency'] = $arr['Currency'];
		}
		else if($method == 'GetBalance')
		{
			$url .= '/api/v1/players?page=1&player=' . $post_data['game_id'] . '&isChildren=true&parentId=' . $arr['ParentID'];
		}
		else if($method == 'ChangeBalance')
		{
			$requestOrderIDAlias = $post_data['order_id'];
			if($post_data['amount'] > 0) 
			{
				$url .= '/api/v1/players/deposit';
				$param_array['amount'] = bcdiv(($post_data['amount'] * 100),1,2);
			}
			else
			{
				$url .= '/api/v1/players/withdraw';
				$param_array['amount'] = bcdiv((($post_data['amount'] * 100) * -1), 1, 2);
			}
			
			$param_array['transactionId'] = $requestOrderIDAlias;
			$param_array['player'] = $post_data['game_id'];
			$param_array['platformId'] = $arr['ParentID'];
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/api/v1/players/logout';
			$param_array['player'] = $post_data['game_id'];
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
				$player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['game_id']);
				if( ! empty($player_acc_data))
				{
					$this->load->library('rng');
					$partner_member_token = $this->rng->get_token(64);
					$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['game_id'], $partner_member_token);
					
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
						$curl_array = $response['curl'];
					}
					else
					{
						$response = $this->curl_json($url, $param_array, "Authorization: Bearer " . $token_result_array['token']);
						$curl_array = $response['curl'];
					}
					
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						
						if($method == 'CreateMember')
						{
							if(isset($result_array['data']['username']) && $result_array['data']['username'] == $param_array['username'])
							{
								$output['errorCode'] = ERROR_SUCCESS;
								$output['errorMessage'] = $this->lang->line('error_success');
								$output['gameID'] = $param_array['username'];
								$output['gamePassword'] = $post_data['password'];
								$this->player_model->add_player_game_token($post_data['provider_code'], $param_array['username']);
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
							if(isset($result_array['data'][0]['username']) && $result_array['data'][0]['username'] == $post_data['game_id'])
							{
								$output['errorCode'] = ERROR_SUCCESS;
								$output['errorMessage'] = $this->lang->line('error_success');
								$output['result'] = bcdiv(($result_array['data'][0]['balance'] / 100), 1, 2);
							}
						}
						else if($method == 'ChangeBalance')
						{
							if(isset($result_array['data']['username']) && $result_array['data']['username'] == $post_data['game_id'])
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
								$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['game_id'], '');
							}	
							else if(isset($result_array['error']['message']) && strpos($result_array['error']['message'], "exists") > 0)
							{
								$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
								$output['errorMessage'] = $this->lang->line('error_username_not_found');
							}
						}
					}else{
						if($response['code'] == '404'){
							$output['errorCode'] = ERROR_OVERTIME;
							$output['errorMessage'] = $this->lang->line('error_overtime');
						}
					}
				}
			}
		}	
		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
    
    public function naga_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
	    $arr = json_decode($api_data, TRUE);
	    
	    //Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		$url = $arr['APIUrl'];
		$param_array = array();
		$curl_array = array();
		$result_array = array();
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		
		if($method == 'LogoutGame')
		{
		    
		}else{
		    if($method == 'CreateMember')
    		{
    		    $url .= '/operator/transfer/create-player';
    		    $param_array = array(
    		        'nativeId' => $post_data['username'],
    		        'groupCode' => $arr['GroupCode'],
    		        'brandCode' => $arr['BrandCode'],
    		        'currencyCode' => $arr['Currency'],
    		    );
        		$plain_text = '{"nativeId":"'.$param_array['nativeId'].'","groupCode":"'.$param_array['groupCode'].'","brandCode":"'.$param_array['brandCode'].'","currencyCode":"'.$param_array['currencyCode'].'"}';
        		$signature = bin2hex(hash_hmac("sha256",utf8_encode($plain_text) , utf8_encode($arr['SecretKey']), true));
        		$token = 'x-signature: ' . $signature;
    		}
    		else if($method == 'LoginGame')
    		{
    		    $this->load->library('rng');
			    $partner_member_token = $this->rng->get_token(64);
			    $this->player_model->update_player_game_token($post_data['provider_code'], $post_data['game_id'], $partner_member_token);
    		    $url .= '/client/game/enter-lobby-page-get-all-games';
    		    $param_array = array(
    		        'playerToken' => $partner_member_token,
    		        'groupCode' => $arr['GroupCode'],
    		        'brandCode' => $arr['BrandCode'],
    		        'sortBy' => "playCount",
    		        'orderBy' => "DESC",
    		    );
    		    $url .= "?" . http_build_query($param_array);
    		    $plain_text = '{"groupCode":"'.$param_array['groupCode'].'","brandCode":"'.$param_array['brandCode'].'","playerToken":"'.$param_array['playerToken'].'"}';
    			$signature = bin2hex(hash_hmac("sha256",utf8_encode($plain_text) , utf8_encode($arr['SecretKey']), true));
        		$token = 'x-signature: ' . $signature;
    		}
    		else if($method == 'GetBalance')
    		{
    		    $url .= '/operator/transfer/wallet-balance';
    		    $param_array = array(
    		        'groupCode' => $arr['GroupCode'],
    		        'brandCode' => $arr['BrandCode'],
    		        'nativeId' => $post_data['game_id'],
    		    );
    		    $url .= "?" . http_build_query($param_array);
    		    $plain_text = '{"groupCode":"'.$param_array['groupCode'].'","brandCode":"'.$param_array['brandCode'].'","nativeId":"'.$param_array['nativeId'].'"}';
    			$signature = bin2hex(hash_hmac("sha256",utf8_encode($plain_text) , utf8_encode($arr['SecretKey']), true));
		
        		$token = 'x-signature: ' . $signature;
    		}
    		else if($method == 'ChangeBalance')
    		{
    		    $requestOrderIDAlias = $post_data['order_id'];
    		    if($post_data['amount'] > 0) 
    			{
    			    $url .= '/operator/transfer/deposit';
    			    $amount_ori = bcdiv($post_data['amount'],1,2);
    			    $amount_mask_one = bcdiv($post_data['amount'],1,0);
    			    $amount_mask_two = bcdiv($post_data['amount'],1,1);
    			    if($amount_ori == $amount_mask_one){
        			    if($amount_ori == $amount_mask_two){
    			            $amount = $amount_mask_one;
    			        }else{
    			            $amount = $amount_mask_two;   
    			        }
        			}else{
        			    if($amount_ori == $amount_mask_two){
    			            $amount = $amount_mask_two;
    			        }else{
    			            $amount = $amount_ori;   
    			        }
        			}
    			}else{
    			    $url .= '/operator/transfer/withdraw';
    			    $amount_ori = bcdiv(($post_data['amount'] * -1),1,2);
    			    $amount_mask_one = bcdiv($post_data['amount'] * -1,1,0);
    			    $amount_mask_two = bcdiv($post_data['amount'] * -1,1,1);
    			    if($amount_ori == $amount_mask_one){
    			        if($amount_ori == $amount_mask_two){
    			            $amount = $amount_mask_one;
    			        }else{
    			            $amount = $amount_mask_two;   
    			        }
        			}else{
        			    if($amount_ori == $amount_mask_two){
    			            $amount = $amount_mask_two;
    			        }else{
    			            $amount = $amount_ori;   
    			        }
        			}
    			}
    			
    			$param_array = array(
			        'nativeId' => $post_data['game_id'],
                    'brandCode' => $arr['BrandCode'],
                    'groupCode' => $arr['GroupCode'],
                    'currencyCode' => $arr['Currency'],
                    'amount' => $amount,
                    'nativeTransactionId' => $requestOrderIDAlias,
    		    );
    			
    			$plain_text = '{"nativeId":"'.$param_array['nativeId'].'","brandCode":"'.$param_array['brandCode'].'","groupCode":"'.$param_array['groupCode'].'","currencyCode":"'.$param_array['currencyCode'].'","amount":'.$param_array['amount'].',"nativeTransactionId":"'.$param_array['nativeTransactionId'].'"}';
    			$signature = bin2hex(hash_hmac("sha256",utf8_encode($plain_text) , utf8_encode($arr['SecretKey']), true));
    			$token = 'x-signature: ' . $signature;
    		}
    		
    		if($method == 'CreateMember' || $method == 'ChangeBalance'){
    		    $response = $this->curl_json($url,$plain_text,$token);
    		}else{
    		    $response = $this->curl_get_json($url,$token);
    		}
    		
    		$curl_array = $response['curl'];
    		if($response['code'] == '0')
    		{
    		    $result_array = json_decode($response['data'], TRUE);
    		    if($method == 'CreateMember')
    		    {
    		        if(isset($result_array['currencyCode'])){
        		        $output['errorCode'] = ERROR_SUCCESS;
					    $output['errorMessage'] = $this->lang->line('error_success');
					    $output['gameID'] = $param_array['nativeId'];
					    $output['gamePassword'] = $post_data['password'];
					    $this->player_model->add_player_game_token($post_data['provider_code'], $param_array['nativeId']);
        		    }else{
        		        if(isset($result_array['code']) && $result_array['code'] == '1207'){
        		            $output['errorCode'] = ERROR_USERNAME_EXITS;
    				        $output['errorMessage'] = $this->lang->line('error_username_already_exits');   
        		        }
        		    }
    		    }
        		else if($method == 'LoginGame')
        		{
        		    $player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['game_id']);
        		    if( ! empty($player_acc_data))
				    {
				        if(!empty($result_array)){
            		        $game_url = "";
            		        foreach($result_array as $result_array_row){
            		            if($result_array_row['code'] == $post_data['game_code']){
            		                $game_url = $result_array_row['playUrl'];
            		            }
            		        }
            		        if(!empty($game_url)){
            		            $lang = 'en';
                		        switch($post_data['syslang'])
            					{
            						case LANG_ZH_CN:
            						case LANG_ZH_HK:
            						case LANG_ZH_TW: $lang = 'zh'; break;
            						case LANG_ID: $lang = 'id'; break;
            						case LANG_TH: $lang = 'th'; break;
            						case LANG_VI: $lang = 'vi'; break;
            						case LANG_JA: $lang = 'ja'; break;
            						case LANG_KO: $lang = 'ko'; break;
            					}
            					
                		        $game_url_array = array(
                		            'playerToken' => $partner_member_token,
                		            'groupCode' => $arr['GroupCode'],
            		                'brandCode' => $arr['BrandCode'],
            		                'gameCode' => $post_data['game_code'],
            		                'language' => $lang,
                		        );
                		        
                		        if( ! empty($post_data['return_url']))
                    			{
                    				$game_url_array['redirectUrl'] = $post_data['return_url'];
                    			}
                		        
                		        $output['result'] = $game_url."?" . http_build_query($game_url_array);
                		        $output['errorCode'] = ERROR_SUCCESS;
        					    $output['errorMessage'] = $this->lang->line('error_success');   
            		        }
            		    }
				    }else{
				        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					    $output['errorMessage'] = $this->lang->line('error_username_not_found');
				    }
        		}
        		else if($method == 'GetBalance')
        		{
        		    if(isset($result_array['currency'])){
        		        $output['errorCode'] = ERROR_SUCCESS;
					    $output['errorMessage'] = $this->lang->line('error_success');
					    $output['result'] = bcdiv($result_array['balance'], 1, 2);
        		    }else{
        		        if(isset($result_array['code']) && $result_array['code'] == '1206'){
        		            $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					        $output['errorMessage'] = $this->lang->line('error_username_not_found');
        		        }
        		    }
        		}
        		else if($method == 'ChangeBalance')
        		{
        		    if(isset($result_array['transactionId'])){
        		        $output['errorCode'] = ERROR_SUCCESS;
					    $output['errorMessage'] = $this->lang->line('error_success');
					    $output['result'] = bcdiv($result_array['updatedBalance'],1,2);
        		    }else{
        		        if(isset($result_array['code']) && $result_array['code'] == '1206'){
        		            $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					        $output['errorMessage'] = $this->lang->line('error_username_not_found');
        		        }else{
        		            $output['errorCode'] = ERROR_OVERTIME;
    				        $output['errorMessage'] = $this->lang->line('error_overtime');
        		        }
        		    }
        		}
    		}
    		else
    		{
    			if($response['code'] == '404'){
    				$output['errorCode'] = ERROR_OVERTIME;
    				$output['errorMessage'] = $this->lang->line('error_overtime');
    			}
    		}
    		
    		if($method == 'ChangeBalance')
    		{
    			$output['orderID'] = $requestOrderID;
    			$output['orderIDAlias'] = $requestOrderIDAlias;
    		}
    		
    		//Database update
    		$this->db->trans_start();
    		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
    		$this->db->trans_complete();
		}
		return $output;
	}
	
	public function ninek_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
	    $arr = json_decode($api_data, TRUE);
	    
	    //Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		$url = $arr['APIUrl'];
		$param_array = array();
		$curl_array = array();
		$result_array = array();
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		
		if($method == 'CreateMember')
		{
		    $url .= "/api/".$arr['ApiToken']."/RegisterUser";
            $param_array = array(
    			"BossID" => $arr['BossID'],
    			"MemberAccount" => $post_data['username'],
    			"MemberPassword" => $post_data['password'],
    		);
		}
		else if($method == 'LoginGame')
		{
		    $url .= "/api/".$arr['ApiToken']."/UserLogin";
            $param_array = array(
    			"MemberAccount" => $post_data['game_id'],
    			"MemberPassword" => $post_data['password'],
    			"GameCode" => $post_data['game_code'],
    			"Platform" => (($post_data['device'] == PLATFORM_WEB) ? 'desktop' : 'mobile'),
    		);
		}
		else if($method == 'GetBalance')
		{
		    $url .= "/api/".$arr['ApiToken']."/GetUserBalance";
            $param_array = array(
    			"MemberAccount" => $post_data['game_id'],
    		);
		}
		else if($method == 'ChangeBalance')
		{
		    $url .= "/api/".$arr['ApiToken']."/BalanceTransfer";
            $param_array = array(
    			"MemberAccount" => $post_data['game_id'],
    			"Balance" => $post_data['amount'],
    			"TradeNo" => $requestOrderID,
    		);
		}
		else if($method == 'LogoutGame')
		{
		    $url .= "/api/".$arr['ApiToken']."/UserLogin";
            $param_array = array(
    			"MemberAccount" => $post_data['game_id'],
    			"MemberPassword" => $post_data['password'],
    		);
		}
		
		$response = $this->curl_post($url, $param_array);
		$curl_array = $response['curl'];
		if($response['code'] == '0')
		{
		    $result_array = json_decode($response['data'], TRUE);
		    if($method == 'CreateMember')
		    {
		        if(isset($result_array['success']) && $result_array['success'] == '0')
				{
				    $output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $param_array['MemberAccount'];
					$output['gamePassword'] = $param_array['MemberPassword'];
				}else if(isset($result_array['success']) && $result_array['success'] == '-1003'){
				    $output['errorCode'] = ERROR_USERNAME_EXITS;
				    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
		    }
    		else if($method == 'LoginGame')
    		{
    		    if(isset($result_array['success']) && $result_array['success'] == '0')
				{
				    $output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $result_array['data']['UserLogin']['GameUrl'];
				}else if(isset($result_array['success']) && $result_array['success'] == '-1004'){
				    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
    		}
    		else if($method == 'GetBalance')
    		{
    		    if(isset($result_array['success']) && $result_array['success'] == '0')
				{
				    $output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['data']['GetUserBalance']['Balance'], 1, 2);
				}else if(isset($result_array['success']) && $result_array['success'] == '-1004'){
				    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
    		}
    		else if($method == 'ChangeBalance')
    		{
    		    if(isset($result_array['success']) && $result_array['success'] == '0')
				{
				    $requestOrderIDAlias = $result_array['data']['BalanceTransfer']['TransactionID'];
				    $output['orderID'] = $requestOrderID;
			        $output['orderIDAlias'] = $requestOrderIDAlias;
			        if(isset($result_array['success']) && $result_array['success'] == '0'){
			            $output['errorCode'] = ERROR_SUCCESS;
    					$output['errorMessage'] = $this->lang->line('error_success');
    					$output['result'] = bcdiv($result_array['data']['BalanceTransfer']['AfterBalance'], 1, 2);
			        }else{
			            $output['errorCode'] = ERROR_OVERTIME;
					    $output['errorMessage'] = $this->lang->line('error_overtime');
			        }
				}else if(isset($result_array['success']) && $result_array['success'] == '-1004'){
				    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
				}else if(isset($result_array['success']) && $result_array['success'] == '-2003'){
				    $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}else if(isset($result_array['success']) && $result_array['success'] == '-2003'){
				    $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}else if(isset($result_array['success']) && $result_array['success'] == '-9999'){
				    $output['errorCode'] = ERROR_OVERTIME;
					$output['errorMessage'] = $this->lang->line('error_overtime');
				}else if(isset($result_array['success'])){
				    
				}else{
				    $output['errorCode'] = ERROR_OVERTIME;
					$output['errorMessage'] = $this->lang->line('error_overtime');
				}
    		}
    		else if($method == 'LogoutGame')
    		{
    		    if(isset($result_array['success']) && $result_array['success'] == '0')
				{
				    $output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
				}else if(isset($result_array['success']) && $result_array['success'] == '-1004'){
				    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
    		}
		}
		else
		{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}
		
		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}

	private function obsb_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
	    $arr = json_decode($api_data, TRUE);
	    
	    //Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		$url = $arr['APIUrl'];
        $requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		
		if($method == 'CreateMember')
		{
		    $url .= "/api_func.php?request=create_user";
            $param_array = array(
    			"agent_userid" => $arr['AgentID'],
    			"customer_userid" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
    			"customer_name" => $post_data['username'],
    			"customer_status" => 1,
    			"tickets_credits" => 0,
    		);
		}
		else if($method == 'LoginGame')
		{
		    $url .= "/api_func.php?request=get_api_token";
		    $param_array = array(
    			"customer_userid" => $post_data['game_id'],
    		);
		}
		else if($method == 'GetBalance')
		{
		    $url .= "/api_func.php?request=check_user_point";
            $param_array = array(
    			"customer_userid" => $post_data['game_id'],
    		);
		}
		else if($method == 'ChangeBalance')
		{
	        $url .= "/api_func.php?request=modify_user_point";
            $requestOrderIDAlias = $post_data['order_id'];
                
            $param_array = array(
    			"customer_userid" => $post_data['game_id'],
    			"credits_type" => 2,
    			"add_point" => $post_data['amount'],
    		);
		}
		else if($method == 'LogoutGame')
		{
		    $url .= "/api_func.php?request=kick_users";
                
            $param_array = array(
    			"customer_userid_str" => $post_data['game_id'],
    		);
		}
		
		$response = $this->curl_post($url, $param_array);
        $curl_array = $response['curl'];
        
        if($response['code'] == '0')
        {
            $result_array = json_decode($response['data'], TRUE);
            if($method == 'CreateMember')
			{
    			if(isset($result_array['status']) && $result_array['status'] == '1')
			    {
			        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    $output['gameID'] = $result_array['customer_userid'];
				    $output['gamePassword'] = $post_data['password'];
			    }
			    else if(isset($result_array['status']) && $result_array['status'] == '-4'){
			        $output['errorCode'] = ERROR_USERNAME_EXITS;
				    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
			    }
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['status']) && $result_array['status'] == '1')
			    {
			        $lang = 'en';
		
        			switch($post_data['syslang'])
        			{
        				case LANG_ZH_CN: $lang = 'cn'; break;
    					case LANG_ZH_HK:
    					case LANG_ZH_TW: $lang = 'tw'; break;
        			}
			        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    $output['result'] = $arr['ForwardUrl'] . '/api_token.php?api_token=' . $result_array['api_token'] . '&lang=' . $lang;
			    }
			    else if(isset($result_array['status']) && $result_array['status'] == '-3'){
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
				    $output['result'] = bcdiv($result_array['tickets_credits'], 1, 2);
			    }
			    else if(isset($result_array['status']) && $result_array['status'] == '-3'){
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
				    $output['result'] = bcdiv($result_array['tickets_credits'], 1, 2);
			    }
			    else if(isset($result_array['status']) && $result_array['status'] == '-3'){
			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
    		    }
    		    else if(isset($result_array['status']) && $result_array['status'] == '-5'){
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
    		    }
    		    else if(isset($result_array['status']) && $result_array['status'] == '-6'){
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
    		    }
    		    else if(isset($result_array['status']) && $result_array['status'] == '-7'){
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
				else if(isset($result_array['status']) && $result_array['status'] == '-100'){
					$output['errorCode'] = ERROR_OVERTIME;
					$output['errorMessage'] = $this->lang->line('error_overtime');
				}
				else if(isset($result_array['status']))
			    {
			        
			    }else{
			        $output['errorCode'] = ERROR_OVERTIME;
					$output['errorMessage'] = $this->lang->line('error_overtime');
			    }
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['status']) && $result_array['status'] == '1')
			    {
    			    $output['errorCode'] = ERROR_SUCCESS;
		            $output['errorMessage'] = $this->lang->line('error_success');
			    }
			}
        }else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	private function og_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
	    $arr = json_decode($api_data, TRUE);
	    
	    //Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		$url = $arr['APIUrl'];
		$game_url = $arr['APIUrl'];
        $token_url = $arr['APIUrl'].'/token';
        $requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$param_array = array();
		$curl_array = array();
		$result_array = array();
		
		if($method == 'LogoutGame'){
		    $output['errorCode'] = ERROR_SUCCESS;
    		$output['errorMessage'] = $this->lang->line('error_success');
		}else{
		    $token_array = array(
                'X-Operator: '.$arr['Operator'],
                'X-key: '.$arr['Key'],
            );
            $token_response = $this->curl_get($token_url, $token_array);
            if($token_response['code'] == '0')
        	{
        	    $token_result_array = json_decode($token_response['data'],true);
        	    $token = (isset($token_result_array['data']['token'])?$token_result_array['data']['token']:"");
        	    if($method == 'LoginGame')
			    {
			        $game_code = "ogplus";
                    if($post_data['game_type_code'] == GAME_BOARD_GAME){
                        $game_code = "ogplus_tw_";
                    }
                    $game_url .= "/game-providers/".$arr['ProviderID']."/games/".$game_code."/key";
                    $url .= "/game-providers/".$arr['ProviderID']."/play";
			        $game_param_array = array(
			            'username' => $post_data['game_id'],
			        );
			        $game_url .= "?" . http_build_query($game_param_array);
			        $game_response = $this->curl_get($game_url, "X-Token:".$token);
			        if($game_response['code'] == '0')
        			{
        			    $game_result_array = json_decode($game_response['data'], TRUE);
        			    if(isset($game_result_array['status']) && ($game_result_array['status'] == 'success'))
				        {
				            $key = (isset($game_result_array['data']['key'])?$game_result_array['data']['key']:"");
				            $param_array = array(
        			            'key' => $key,
        			            'type' => (($post_data['device'] == PLATFORM_WEB) ? 'desktop' : 'mobile'),
        			        );
        			        $url .= "?" . http_build_query($param_array);
			                $response = $this->curl_get($url);
			                $curl_array = $response['curl'];
			                if($response['code'] == '0')
			                {
			                    $result_array = json_decode($response['data'], TRUE);
			                    if(isset($result_array['status']) && ($result_array['status'] == 'success'))
				                {
				                    $output['errorCode'] = ERROR_SUCCESS;
                					$output['errorMessage'] = $this->lang->line('error_success');
                					$output['result'] = $result_array['data']['url'];
				                }
			                }
				        }
				        else if(isset($game_result_array['status']) && ($game_result_array['status'] == 'error'))
				        {
				            if(isset($game_result_array['data']['code']) && ($game_result_array['data']['code'] == '1'))
				            {
				                $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					            $output['errorMessage'] = $this->lang->line('error_username_not_found');
				            }
				        }
        			}
			    }else{
			        if($method == 'CreateMember')
            		{
            		    $url .= "/register";
            		    $lang = 'en';
            			switch($post_data['syslang'])
            			{
            				case LANG_ZH_CN:
            				case LANG_ZH_HK:
            				case LANG_ZH_TW: $lang = 'cn'; break;
            				case LANG_ID: $lang = 'id'; break;
            				case LANG_TH: $lang = 'th'; break;
            				case LANG_VI: $lang = 'vn'; break;
            				case LANG_JA: $lang = 'jp'; break;
            				case LANG_KO: $lang = 'kr'; break;
            			}
            	        $param_array = array(
            	            'username' => $post_data['username'],
            	            'country' => $sys_data['system_country'],
            	            'fullname' => $post_data['username'],
            	            'email' => $post_data['username']."@".$sys_data['system_prefix'].".com",
            	            'language' => $lang,
            	            'birthdate' => "1980-01-01",
            	        );
            		}
            		else if($method == 'GetBalance')
			        {
			            $url .= "/game-providers/".$arr['ProviderID']."/balance";
            		    $param_array = array(
    			            'username' => $post_data['game_id'],
    			        );
    			        $url .= "?" . http_build_query($param_array);
            		}
            		else if($method == 'ChangeBalance')
			        {
			            $url .= "/game-providers/".$arr['ProviderID']."/balance";
			            $requestOrderIDAlias = $post_data['order_id'];
                        if($post_data['amount'] > 0) 
            			{
            			    $action = "IN";
                            if(in_array($arr['CurrencyType'],$currency_one)){
                                $amount = bcdiv($post_data['amount'] / 1000,1,2);
                            }else{
                                $amount = $post_data['amount'];
                            }
            			}else{
            			    $action = "OUT";
            			    if(in_array($arr['CurrencyType'],$currency_one)){
                                $amount = bcdiv(($post_data['amount'] * -1 / 1000), 1, 2);
                            }else{
                                $amount = bcdiv(($post_data['amount'] * -1), 1, 2);
                            }
            			}
                        
                        $param_array = array(
        		            'username' => $post_data['game_id'],
        		            'balance' => $amount,
        		            'action' => $action,
        		            'transferId' => $requestOrderIDAlias,
        		        );
			        }
			        
			        if($method == 'CreateMember' || $method == 'ChangeBalance' ){
			            $response = $this->curl_post($url, $param_array,"X-Token:".$token);
			        }else{
			            $response = $this->curl_get($url, "X-Token:".$token);    
			        }
			        $curl_array = $response['curl'];
        
                    if($response['code'] == '0')
                    {
                        $result_array = json_decode($response['data'], TRUE);
                        if($method == 'CreateMember')
            		    {
            		        if(isset($result_array['status']) && ($result_array['status'] == 'success'))
    				        {
    				            $output['errorCode'] = ERROR_SUCCESS;
    				            $output['errorMessage'] = $this->lang->line('error_success');
    				            $output['gameID'] = $param_array['username'];
    				            $output['gamePassword'] = $post_data['password'];
    				        }
            		    }
                		else if($method == 'GetBalance')
    			        {
    			            if(isset($result_array['status']) && ($result_array['status'] == 'success'))
    				        {
    				            $output['errorCode'] = ERROR_SUCCESS;
    				            $output['errorMessage'] = $this->lang->line('error_success');
    				            if(in_array($arr['CurrencyType'],$currency_one)){
        							$output['result'] = bcdiv($result_array['data']['balance']*1000, 1, 2);
        						}
        						else{
        							$output['result'] = bcdiv($result_array['data']['balance'], 1, 2);
        						}
    				            $output['result'] = bcdiv($result_array['data']['balance'], 1, 2); 
    				        }
    				        else if(isset($result_array['status']) && ($result_array['status'] == 'error'))
    				        {
    				            if(isset($result_array['data']['code']) && ($result_array['data']['code'] == '1'))
    				            {
    				                $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						            $output['errorMessage'] = $this->lang->line('error_username_not_found');
    				            }
    				        }
    			        }
                		else if($method == 'ChangeBalance')
    			        {
    			            if(isset($result_array['status']) && ($result_array['status'] == 'success'))
    				        {
    				            $output['errorCode'] = ERROR_SUCCESS;
    				            $output['errorMessage'] = $this->lang->line('error_success');
    				            if(in_array($arr['CurrencyType'],$currency_one)){
        							$output['result'] = bcdiv($result_array['data']['balance']*1000, 1, 2);
        						}
        						else{
        							$output['result'] = bcdiv($result_array['data']['balance'], 1, 2);
        						}
    				            $output['result'] = bcdiv($result_array['data']['balance'], 1, 2); 
    				        }
    				        else if(isset($result_array['status']) && ($result_array['status'] == 'error'))
    				        {
    				            if(isset($result_array['data']['code']) && ($result_array['data']['code'] == '1'))
    				            {
    				                $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						            $output['errorMessage'] = $this->lang->line('error_username_not_found');
    				            }
    				            else if(isset($result_array['data']['code']) && ($result_array['data']['code'] == '7'))
    				            {
    				                $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
    							    $output['errorMessage'] = $this->lang->line('error_amount_insufficient');
    				            }
    				            else if(isset($result_array['data']['message']) && ($result_array['data']['message'] == 'InternalServerError'))
    				            {
    				                $output['errorCode'] = ERROR_OVERTIME;
					                $output['errorMessage'] = $this->lang->line('error_overtime');
    				            }
    				            else if(isset($result_array['data']['message']) && ($result_array['data']['message'] == 'Service not available'))
    				            {
    				                $output['errorCode'] = ERROR_OVERTIME;
					                $output['errorMessage'] = $this->lang->line('error_overtime');
    				            }
    				        }
    				        else if(isset($result_array['status'])){
    				            
    				        }else{
    				            $output['errorCode'] = ERROR_OVERTIME;
					            $output['errorMessage'] = $this->lang->line('error_overtime');
    				        }
    			        }
                    }
                    else{
            			if($response['code'] == '404'){
            				$output['errorCode'] = ERROR_OVERTIME;
            				$output['errorMessage'] = $this->lang->line('error_overtime');
            			}
            		}
			    }
        	}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}

	public function png_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
	    $arr = json_decode($api_data, TRUE);
	    
	    //Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		$url = $arr['APIUrl'];
		$game_url = $arr['APIUrl'];
        $token_url = $arr['APIUrl'].'/token';
        $requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$param_array = array();
		$curl_array = array();
		$result_array = array();
		$xml = '';

		$username = $arr['ApiUsername'];
        $password = $arr['ApiPassword'];
        $auth = "Basic ".base64_encode("$username:$password");

		if($method == 'CreateMember')
		{
			$lang = 'en_US';
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $lang = 'zh_CN'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $lang = 'zh_TW'; break;
				case LANG_TH: $lang = 'th_TH'; break;
				case LANG_VI: $lang = 'vi_VN'; break;
				case LANG_JA: $lang = 'ja_JP'; break;
				case LANG_KO: $lang = 'ko_KR'; break;
			}
			
			$xml .= '<soapenv:Envelope xmlns:soapenv="'.$arr['SoapEnv'].'" xmlns:v1="'.$arr['Xmlns'].'">';
			$xml .= '<soapenv:Header/>';
			$xml .= '<soapenv:Body>';
			$xml .= '<v1:RegisterUser>';
			$xml .= '<v1:UserInfo>';
			$xml .= '<v1:ExternalUserId>'.$post_data['username']."_".$arr['UPrefix'].'</v1:ExternalUserId>';
			$xml .= '<v1:Username>'.$post_data['username']."_".$arr['UPrefix'].'</v1:Username>';
			$xml .= '<v1:Nickname>'.$post_data['username']."_".$arr['UPrefix'].'</v1:Nickname>';
			$xml .= '<v1:Currency>'.$arr['CurrencyType'].'</v1:Currency>';
			$xml .= '<v1:Country>'.$arr['Country'].'</v1:Country>';
			$xml .= '<v1:Birthdate>1980-01-01</v1:Birthdate>';
			$xml .= '<v1:Registration>'.date('Y-m-d')."T".date('H:i:s').'</v1:Registration>';
			$xml .= '<v1:BrandId>'.$arr['BrandId'].'</v1:BrandId>';
			$xml .= '<v1:Language>'.$lang.'</v1:Language>';
			$xml .= '</v1:UserInfo>';
			$xml .= '</v1:RegisterUser>';
			$xml .= '</soapenv:Body>';
            $xml .= '</soapenv:Envelope>';

            $header = array(
			    'action' => $arr['RegisterUserAction'],
			    'authorization' => $auth,
			    'content_type' => "text/xml",
			);
		}
		else if($method == 'LoginGame')
		{
			$xml .= '<soapenv:Envelope xmlns:soapenv="'.$arr['SoapEnv'].'" xmlns:v1="'.$arr['Xmlns'].'">';
			$xml .= '<soapenv:Header/>';
			$xml .= '<soapenv:Body>';
			$xml .= '<v1:GetTicket>';
			$xml .= '<v1:ExternalUserId>'.$post_data['game_id'].'</v1:ExternalUserId>';
			$xml .= '</v1:GetTicket>';
			$xml .= '</soapenv:Body>';
            $xml .= '</soapenv:Envelope>';

            $header = array(
			    'action' => $arr['GetTicketAction'],
			    'authorization' => $auth,
			    'content_type' => "text/xml",
			);
		}
		else if($method == 'GetBalance')
		{
			$xml .= '<soapenv:Envelope xmlns:soapenv="'.$arr['SoapEnv'].'" xmlns:v1="'.$arr['Xmlns'].'">';
			$xml .= '<soapenv:Header/>';
			$xml .= '<soapenv:Body>';
			$xml .= '<v1:Balance>';
			$xml .= '<v1:ExternalUserId>'.$post_data['game_id'].'</v1:ExternalUserId>';
			$xml .= '</v1:Balance>';
			$xml .= '</soapenv:Body>';
            $xml .= '</soapenv:Envelope>';
            
            $header = array(
			    'action' => $arr['BalanceAction'],
			    'authorization' => $auth,
			    'content_type' => "text/xml",
			);
		}
		else if($method == 'ChangeBalance')
		{
			$requestOrderIDAlias = $post_data['order_id'];
			if($post_data['amount'] > 0){
                $amount = $post_data['amount'];
                
                $xml .= '<soapenv:Envelope xmlns:soapenv="'.$arr['SoapEnv'].'" xmlns:v1="'.$arr['Xmlns'].'">';
    			$xml .= '<soapenv:Header/>';
    			$xml .= '<soapenv:Body>';
    			$xml .= '<v1:CreditAccount>';
    			$xml .= '<v1:ExternalUserId>'.$post_data['game_id'].'</v1:ExternalUserId>';
    			$xml .= '<v1:Amount>'.$amount.'</v1:Amount>';
    			$xml .= '<v1:Currency>'.$arr['CurrencyType'].'</v1:Currency>';
    			$xml .= '<v1:ExternalTransactionId>'.$requestOrderIDAlias.'</v1:ExternalTransactionId>';
    			$xml .= '</v1:CreditAccount>';
    			$xml .= '</soapenv:Body>';
                $xml .= '</soapenv:Envelope>';
                
                $header = array(
    			    'action' => $arr['CreditAccountAction'],
    			    'authorization' => $auth,
    			    'content_type' => "text/xml",
    			);
            }else{
                $amount = bcdiv(($post_data['amount'] * -1), 1, 2);
                
                $xml .= '<soapenv:Envelope xmlns:soapenv="'.$arr['SoapEnv'].'" xmlns:v1="'.$arr['Xmlns'].'">';
    			$xml .= '<soapenv:Header/>';
    			$xml .= '<soapenv:Body>';
    			$xml .= '<v1:DebitAccount>';
    			$xml .= '<v1:ExternalUserId>'.$post_data['game_id'].'</v1:ExternalUserId>';
    			$xml .= '<v1:Amount>'.$amount.'</v1:Amount>';
    			$xml .= '<v1:Currency>'.$arr['CurrencyType'].'</v1:Currency>';
    			$xml .= '<v1:ExternalTransactionId>'.$requestOrderIDAlias.'</v1:ExternalTransactionId>';
    			$xml .= '</v1:DebitAccount>';
    			$xml .= '</soapenv:Body>';
                $xml .= '</soapenv:Envelope>';
                
                $header = array(
    			    'action' => $arr['DebitAccountAction'],
    			    'authorization' => $auth,
    			    'content_type' => "text/xml",
    			);
            }
		}
		
		if($method == 'LogoutGame')
		{
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
		}else{
			$response = $this->curl_soap_version_one($url, $xml, $header);
			if($response['code'] == '0')
    		{
    			$xml_utf8 = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response['data']);
                $xml_output = simplexml_load_string($xml_utf8);
                $json = json_encode($xml_output);
                $result_array = json_decode($json,true);

                if($method == 'CreateMember')
				{
	                if($response['http_code'] == "200"){
	                    //success
	                    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['gameID'] = $post_data['username']."_".$arr['UPrefix'];
						$output['gamePassword'] = $post_data['password'];
	                }else{
	                    if(isset($result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId']) && $result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId'] == "DuplicateEntry"){
	                        $output['errorCode'] = ERROR_USERNAME_EXITS;
					        $output['errorMessage'] = $this->lang->line('error_username_already_exits');
	                    }
	                }
	            }
				else if($method == 'LoginGame')
				{
					if($response['http_code'] == "200"){
                        if(isset($result_array['sBody']['GetTicketResponse']['Ticket'])){
                            $lang = 'en_US';
                			switch($post_data['syslang'])
                			{
                				case LANG_ZH_CN: $lang = 'zh_CN'; break;
                				case LANG_ZH_HK:
                				case LANG_ZH_TW: $lang = 'zh_TW'; break;
                				case LANG_TH: $lang = 'th_TH'; break;
                				case LANG_VI: $lang = 'vi_VN'; break;
                				case LANG_JA: $lang = 'ja_JP'; break;
                				case LANG_KO: $lang = 'ko_KR'; break;
                			}
                			$ticket = $result_array['sBody']['GetTicketResponse']['Ticket'];
                            
                            $param_array =  array(
                                'pid' => $arr['PID'],
                                'practice' => 0,
                                'gid' => $post_data['game_code'],
                                'channel' => (($post_data['device'] == PLATFORM_WEB) ? 'desktop' : 'mobile'),
                                'lang' => $lang,
                                'brand' => $arr['BrandId'],
                                'ticket' => $ticket,
                            );
                            
                            $output['errorCode'] = ERROR_SUCCESS;
                    	    $output['errorMessage'] = $this->lang->line('error_success');
                            $output['result'] = $arr['ForwardUrl']."?" . http_build_query($param_array);
                        }
                    }else{
                        if(isset($result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId']) && $result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId'] == "UnknownUser"){
                            $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						    $output['errorMessage'] = $this->lang->line('error_username_not_found');
                        }
                    }
				}
				else if($method == 'GetBalance')
				{
					if($response['http_code'] == "200"){
                        if(isset($result_array['sBody']['BalanceResponse']['UserBalance']['Real'])){
                            $output['errorCode'] = ERROR_SUCCESS;
                            $output['errorMessage'] = $this->lang->line('error_success');
						    $output['result'] = bcdiv($result_array['sBody']['BalanceResponse']['UserBalance']['Real'], 1, 2);
                        }
                    }else{
                        if(isset($result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId']) && $result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId'] == "UnknownUser"){
                            $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						    $output['errorMessage'] = $this->lang->line('error_username_not_found');
                        }
                    }
				}
				else if($method == 'ChangeBalance')
				{
					if($response['http_code'] == "200"){
                        if($post_data['amount'] > 0){
                            if(isset($result_array['sBody']['CreditAccountResponse']['UserAccount']['Real'])){
                                $output['errorCode'] = ERROR_SUCCESS;
                                $output['errorMessage'] = $this->lang->line('error_success');
    						    $output['result'] = bcdiv($result_array['sBody']['CreditAccountResponse']['UserAccount']['Real'], 1, 2);
                            }   
                        }else{
                            if(isset($result_array['sBody']['DebitAccountResponse']['UserAccount']['Real'])){
                                $output['errorCode'] = ERROR_SUCCESS;
                                $output['errorMessage'] = $this->lang->line('error_success');
    						    $output['result'] = bcdiv($result_array['sBody']['DebitAccountResponse']['UserAccount']['Real'], 1, 2);
                            }
                        }
                    }else{
                        if(isset($result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId']) && $result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId'] == "UnknownUser"){
                            $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						    $output['errorMessage'] = $this->lang->line('error_username_not_found');
                        }else if(isset($result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId']) && $result_array['sBody']['sFault']['detail']['ServiceFault']['ErrorId'] == "NotEnoughMoney"){
                            $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						    $output['errorMessage'] = $this->lang->line('error_amount_insufficient');
                        }else{
                            $output['errorCode'] = ERROR_OVERTIME;
					        $output['errorMessage'] = $this->lang->line('error_overtime');
                        }
                    }
				}
    		}else{
				if($response['code'] == '404'){
					$output['errorCode'] = ERROR_OVERTIME;
					$output['errorMessage'] = $this->lang->line('error_overtime');
				}
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}

	public function rtg_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);

		//Prepare post data
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$param_array = array();
		$result_array = array();
		$curl_array = array();
		$current_time = date("YmdHis");
		$str = '';

		$token_url = $arr['APIUrl'];
		$token_param_array = array(
	        "username" => $arr['Username'],
	        "password" => $arr['Password'],
	    );
	    $token_url .= "/api/start/token?" . http_build_query($token_param_array);
	    $token_response = $this->curl_get($token_url);
	    if(isset($token_response['http_code']) && $token_response['http_code'] == "200"){
	    	$token_array = json_decode($token_response['data'], TRUE);
	    	if(isset($token_array['token'])){
	    		$token = $token_array['token'];
	    		if($method == 'CreateMember')
				{
					$url .= "/api/player";
					$param_array = array(
				     "agentid" => $arr['agentId'],
			         "username" => $post_data['username'],
			         "firstName" => $post_data['username'],
			         "lastName" => $post_data['username'],
			         "email" => $post_data['username']."@".$sys_data['system_prefix'].".com",
			         "gender" => "MALE",
			         "birthdate" => "1980-01-01T23:00",
			         "currency" => $arr["Currency"]
			        );
				}
				else if($method == 'LoginGame') {
					$lang = 'en-US';
			
					switch($post_data['syslang'])
					{
						case LANG_ZH_CN:
						case LANG_ZH_HK:
						case LANG_ZH_TW: $lang = 'zh-CN'; break;
						case LANG_TH: $lang = 'th-TH'; break;
						case LANG_VI: $lang = 'vi-VN'; break;
						case LANG_JA: $lang = 'ja-JP'; break;
						case LANG_KO: $lang = 'ko-KR'; break;
					}


					$return_url = '';
					if( ! empty($post_data['return_url']))
					{
						$return_url = $post_data['return_url'];
					}

					if($post_data['is_demo'] == STATUS_YES)
					{
						$demo = TRUE;
					}else{
						$demo = FALSE;
						#$demo = TRUE;
					}

					$url .= "/api/GameLauncher";
					$param_array = array(
			            "player" => array(
			                "playerLogin" => $post_data['game_id'],
			                "playerCurrency" => $arr["Currency"],
			            ),
			            "gameId" => $post_data['game_code'],
			            "locale" => $lang,
			            "isDemo" => $demo,
			            "returnUrl" => $return_url,
			            "target" => $return_url,
			        );
				}else if($method == 'GetBalance')
				{
					$url .= "/api/wallet";
					$param_array = array(
				     "agentId" => $arr['agentId'],
			         "playerLogin" => $post_data['game_id'],
			        );
				}else if($method == 'ChangeBalance')
				{
					if($post_data['amount'] > 0) 
					{
						$requestOrderIDAlias = $requestOrderID;
						$url .= "/api/wallet/deposit";
						$param_array_url = array(
				         "trackingOne" => $requestOrderIDAlias,
				        );
				        $url .= "/".$post_data['amount']."?/". http_build_query($param_array_url);
				        $param_array = array(
					     "agentId" => $arr['agentId'],
				         "playerLogin" => $post_data['game_id'],
				         "amount" => $post_data['amount'],
				         "trackingOne" => $requestOrderIDAlias,
				        );
					}else{
						$requestOrderIDAlias = $requestOrderID;
						$url .= "/api/wallet/withdraw";
						$param_array_url = array(
				         "trackingOne" => $requestOrderIDAlias,
				        );
				        $url .= "/".bcdiv(($post_data['amount'] * -1), 1, 2)."?/". http_build_query($param_array_url);
				        $param_array = array(
					     "agentId" => $arr['agentId'],
				         "playerLogin" => $post_data['game_id'],
				         "amount" => bcdiv(($post_data['amount'] * -1), 1, 2),
				         "trackingOne" => $requestOrderIDAlias,
				        );
					}
				}else if($method == 'LogoutGame')
				{
					$url .="/api/player/id/".$post_data['game_id']."?agentId=".$arr['agentId'];
				}

				if($method == 'CreateMember'){
					$response = $this->curl_put_json($url,$param_array,"Authorization: ".$token);
				}
				else if($method == 'LogoutGame'){
					$response = $this->curl_get($url,"Authorization: ".$token);
				}
				else{
					$response = $this->curl_json($url,$param_array,"Authorization: ".$token);	
				}
				$curl_array = $response['curl'];
				$param_array['http_code'] = $response['http_code'];
				if($response['code'] == '0') {
					$result_array = json_decode($response['data'], TRUE);
					if($method == 'CreateMember')
					{
						if($response['http_code'] == "201"){
							$output['errorCode'] = ERROR_SUCCESS;
							$output['errorMessage'] = $this->lang->line('error_success');
							$output['gameID'] = $param_array['username'];
							$output['gamePassword'] = $post_data['password'];
						}else if($response['http_code'] == "201"){
							$output['errorCode'] = ERROR_USERNAME_EXITS;
							$output['errorMessage'] = $this->lang->line('error_username_already_exits');
						}
					}else if($method == 'LoginGame')
					{
						if($response['http_code'] == "200"){
							$output['errorCode'] = ERROR_SUCCESS;
							$output['errorMessage'] = $this->lang->line('error_success');
							$output['result'] = $result_array['instantPlayUrl'];
						}else if($response['http_code'] == "404" || $response['http_code'] == "400"){
							$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
							$output['errorMessage'] = $this->lang->line('error_username_not_found');
						}
					}else if($method == 'GetBalance')
					{
						if($response['http_code'] == "200"){
							$output['errorCode'] = ERROR_SUCCESS;
							$output['errorMessage'] = $this->lang->line('error_success');
							$output['result'] = bcdiv($result_array, 1, 2);
						}else if($response['http_code'] == "400"){
							$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
							$output['errorMessage'] = $this->lang->line('error_username_not_found');
						}
					}else if($method == 'ChangeBalance')
					{
						if($response['http_code'] == "200"){
							if(isset($result_array['errorCode']) && ($result_array['errorCode'] == "False")){
								$output['errorCode'] = ERROR_SUCCESS;
								$output['errorMessage'] = $this->lang->line('error_success');
								$output['result'] = bcdiv($result_array['currentBalance'], 1, 2);
							}
						}else if($response['http_code'] == "404" || $response['http_code'] == "400"){
							$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
							$output['errorMessage'] = $this->lang->line('error_username_not_found');	
						}else if($response['http_code'] == "500"){
							$output['errorCode'] = ERROR_OVERTIME;
							$output['errorMessage'] = $this->lang->line('error_overtime');
						}
					}
					else if($method == 'LogoutGame')
					{
						if($response['http_code'] == "200"){
							$game_id = $result_array['result'];
    					    $url = $arr['APIUrl']."/api/player/logout";
        					$param_array = array(
        				        "id" => $game_id, 
        			        );
        			        $url .= "/".$game_id;
        			        $response = $this->curl_json($url,$param_array,"Authorization: ".$token);
        			        if($response['code'] == '0')
    				        {
    				            $result_array = json_decode($response['data'], TRUE);
    				        }
						}else if($response['http_code'] == "400"){
							$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
							$output['errorMessage'] = $this->lang->line('error_username_not_found');
						}
					}
				}
				else{
					if($response['code'] == '404'){
						$output['errorCode'] = ERROR_OVERTIME;
						$output['errorMessage'] = $this->lang->line('error_overtime');
					}
				}

				if($method == 'ChangeBalance')
				{
					$output['orderID'] = $requestOrderID;
					$output['orderIDAlias'] = $requestOrderIDAlias;
				}

	    		//Database update
				$this->db->trans_start();
				$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
				$this->db->trans_complete();
	    	}			
	    }
		else {
			$method .= 'Token';
			$param_array['token'] = $token_url;
			$result_array = json_encode($token_response);
			$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		}
        
	    return $output;
	}
	
	public function rsg_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
	    $currency_one = array("IDR", "VND");
        $currency_two = array("MYR2");
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$amount = 0;
		$curl_array = array();
		$timestamp = time();
		$str = '';
		
		if($method == 'CreateMember')
		{
			$url .= "/Player/CreatePlayer";
            $param_array = array(
    			"SystemCode" => $arr['SystemCode'],
    			"WebId" => $arr['WebId'],
    			"UserId" => $post_data['username'],
    			"Currency" => $arr['Currency'],
    		);
    		$str = '{"SystemCode":"'.$arr['SystemCode'].'","WebId":"'.$arr['WebId'].'","UserId":"'.$post_data['username'].'","Currency":"'.$arr['Currency'].'"}';
		}
		else if($method == 'LoginGame')
		{
			$url .= "/Player/GetURLToken";
            $language = 'en-US';
		
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $language = 'zh-CN'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $language = 'zh-TW'; break;
				case LANG_ID: $language = 'id-ID'; break;
				case LANG_TH: $language = 'th-TH'; break;
				case LANG_VI: $language = 'vi-VN'; break;
				case LANG_JA: $language = 'ja-JP'; break;
				case LANG_KO: $language = 'ko-KR'; break;
			}
			
			
			$timestamp = time();
            $param_array = array(
    			"SystemCode" => $arr['SystemCode'],
    			"WebId" => $arr['WebId'],
    			"UserId" => $post_data['game_id'],
    			"UserName" => $post_data['username'],
    			"GameId" => $post_data['game_code'],
    			"Currency" => $arr['Currency'],
    			"Language" => $language,
    			"ExitAction" => $post_data['return_url'],
    		);
    		
    		$str = '{"SystemCode":"'.$arr['SystemCode'].'","WebId":"'.$arr['WebId'].'","UserId":"'.$post_data['game_id'].'","UserName":"'.$post_data['username'].'","GameId":'.$post_data['game_code'].',"Currency":"'.$arr['Currency'].'","Language":"'.$language.'","ExitAction":"'.$post_data['return_url'].'"}';
		}
		else if($method == 'GetBalance')
		{
			$timestamp = time();
            $url .= "/Player/GetBalance";
            $param_array = array(
    			"SystemCode" => $arr['SystemCode'],
    			"WebId" => $arr['WebId'],
    			"UserId" => $post_data['game_id'],
    			"Currency" => $arr['Currency'],
    		);
    		
    		$str = '{"SystemCode":"'.$arr['SystemCode'].'","WebId":"'.$arr['WebId'].'","UserId":"'.$post_data['game_id'].'","Currency":"'.$arr['Currency'].'"}';
		}
		else if($method == 'ChangeBalance')
		{
			$requestOrderIDAlias = str_replace(array($post_data['username']), array(''), $post_data['order_id']).rand(100,999);
		    if($post_data['amount'] > 0) 
			{
			    $url .= "/Player/Deposit";
			    if(in_array($arr['Currency'],$currency_one)){
					$amount = bcdiv($post_data['amount'] / 1000,1,2);
			    }else if(in_array($arr['Currency'],$currency_two)){
			        $amount = bcdiv($post_data['amount'] * 100,1,2);
				}else{
				    $amount = bcdiv($post_data['amount'],1,2);
				}
			}
			else
			{
			    $url .= "/Player/Withdraw";
			    if(in_array($arr['Currency'],$currency_one)){
					$amount = bcdiv(($post_data['amount'] * -1 / 1000), 1, 2);
			    }else if(in_array($arr['Currency'],$currency_two)){
			        $amount = bcdiv(($post_data['amount'] * -1 * 100), 1, 2);
				}else{
					$amount = bcdiv(($post_data['amount'] * -1), 1, 2);
				}
			}
			
			$timestamp = time();
            $param_array = array(
    			"SystemCode" => $arr['SystemCode'],
    			"WebId" => $arr['WebId'],
    			"UserId" => $post_data['game_id'],
    			"TransactionID" => $requestOrderIDAlias,
    			"Currency" => $arr['Currency'],
    			"Balance" => $amount,
    		);
    		
    		$str = '{"SystemCode":"'.$arr['SystemCode'].'","WebId":"'.$arr['WebId'].'","UserId":"'.$post_data['game_id'].'","TransactionID":"'.$requestOrderIDAlias.'","Currency":"'.$arr['Currency'].'","Balance":'.$amount.'}';
		}
		else if($method == 'LogoutGame')
		{
			$url .= "/Player/Kickout";
			$timestamp = time();
            $param_array = array(
                "KickType" => 4,
    			"SystemCode" => $arr['SystemCode'],
    			"WebId" => $arr['WebId'],
    			"UserId" => $post_data['game_id'],
    			"GameId" => 0,
    		);
    		
    		$str = '{"KickType":4,"SystemCode":"'.$arr['SystemCode'].'","WebId":"'.$arr['WebId'].'","UserId":"'.$post_data['game_id'].'","GameId":0}';
		}
		
		$encrypt_data = openssl_encrypt($str,'DES-CBC',$arr['Deskey'],OPENSSL_RAW_DATA ,$arr['IVkey']);
        $msg = base64_encode($encrypt_data);
        $signature = md5($arr['ClientID'].$arr['Secret'].$timestamp.$msg);
        $header[]="X-API-ClientID: ".$arr['ClientID'];
        $header[]="X-API-Signature: ".$signature;
        $header[]="X-API-Timestamp: ".$timestamp;
		$param = 'Msg='.$msg;
		$response = $this->curl_post($url, $param,$header);
		$curl_array = $response['curl'];
		if($response['code'] == '0')
		{
			$result_string = openssl_decrypt(base64_decode($response['data']),'DES-CBC',$arr['Deskey'],OPENSSL_RAW_DATA ,$arr['IVkey']);
        	$result_array = json_decode($result_string, TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0'){
    		        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    $output['gameID'] = $result_array['Data']['UserId'];
				    $output['gamePassword'] = $post_data['password']; 
    		    }
    		    else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '1002')
				{
				    $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
				}
    		    else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '3010')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
				    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0'){
    			    $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    $output['result'] = $result_array['Data']['URL'];
    			}
    		    else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '3008')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '1002')
				{
				    $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0'){
    		        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    if(in_array($arr['Currency'],$currency_one)){
				        $output['result'] = bcdiv($result_array['Data']['CurrentPlayerBalance'] * 1000, 1, 2);
				    }else if(in_array($arr['Currency'],$currency_two)){
				        $output['result'] = bcdiv($result_array['Data']['CurrentPlayerBalance'] / 100, 1, 2);
				    }else{
				        $output['result'] = bcdiv($result_array['Data']['CurrentPlayerBalance'], 1, 2);
				    }
    		    }
    		    else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '1002')
				{
				    $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
				}
    		    else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '3008')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0'){
    			    $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    if(in_array($arr['Currency'],$currency_one)){
				        $output['result'] = bcdiv($result_array['Data']['CurrentPlayerBalance'] * 1000, 1, 2);
				    }else if(in_array($arr['Currency'],$currency_two)){
				        $output['result'] = bcdiv($result_array['Data']['CurrentPlayerBalance'] / 100, 1, 2);
				    }else{
				        $output['result'] = bcdiv($result_array['Data']['CurrentPlayerBalance'], 1, 2);
				    }
    			}
    		    else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '3005'){
    		        $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
					$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
    		    }
    		    else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '3008')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '1002')
				{
				    $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
				}
				else if(isset($result_array['ErrorCode']))
				{
				    
				}
				else{
				    $output['errorCode'] = ERROR_OVERTIME;
				    $output['errorMessage'] = $this->lang->line('error_overtime');
				}
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0'){
    			    $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
    			}
    		    else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '3008')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '1002')
				{
				    $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
				}
			}
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
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
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$amount = 0;
		$curl_array = array();
		$current_time = date("YmdHis");
		$str = '';
		
		if($method == 'CreateMember')
		{
			$str = "method=RegUserInfo&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['username']. "&CurrencyType=" . $arr['CurrencyType'];
		}
		else if($method == 'LoginGame')
		{
			$str = "method=LoginRequest&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['game_id']. "&CurrencyType=" . $arr['CurrencyType'];
		}
		else if($method == 'GetBalance')
		{
			$str = "method=GetUserStatusDV&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['game_id'];
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				if($arr['CurrencyType'] == "IDR" || $arr['CurrencyType'] == "KHR" || $arr['CurrencyType'] == "MMK" || $arr['CurrencyType'] == "VND"){
					$amount = bcdiv($post_data['amount']/1000,1,2);
				}else{
					$amount = $post_data['amount'];
				}

				$requestOrderIDAlias = str_replace(array($post_data['username']), array($post_data['game_id']), $post_data['order_id']);
				$str = "method=CreditBalanceDV&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['game_id'] . "&OrderId=" . $requestOrderIDAlias . "&CreditAmount=" . $amount;
			}
			else
			{
				if($arr['CurrencyType'] == "IDR" || $arr['CurrencyType'] == "KHR" || $arr['CurrencyType'] == "MMK" || $arr['CurrencyType'] == "VND"){
					$amount = bcdiv(($post_data['amount'] * -1 / 1000), 1, 2);
				}else{
					$amount = bcdiv(($post_data['amount'] * -1), 1, 2);
				}

				$requestOrderIDAlias = str_replace(array($post_data['username']), array($post_data['game_id']), $post_data['order_id']);
				$str = "method=DebitBalanceDV&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['game_id'] . "&OrderId=" . $requestOrderIDAlias . "&DebitAmount=" . $amount;
			}
		}
		else if($method == 'LogoutGame')
		{
			$str = "method=KickUser&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['game_id'];
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
		$curl_array = $response['curl'];
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
					$output['gameID'] = $post_data['username'];
					$output['gamePassword'] = $post_data['password'];
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
					$output['result'] = $arr['ForwardUrl'] . '?username=' . $post_data['game_id'] . '&token=' . $result_array['Token'] . '&lobby=' . $arr['LobbyCode'] . '&lang=' . $lang . '&returnurl=' . $post_data['return_url'];
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					if($arr['CurrencyType'] == "IDR" || $arr['CurrencyType'] == "KHR" || $arr['CurrencyType'] == "MMK" || $arr['CurrencyType'] == "VND"){
						$output['result'] = bcdiv($result_array['Balance']*1000, 1, 2);
					}else{
						$output['result'] = bcdiv($result_array['Balance'], 1, 2);
					}
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
					if($arr['CurrencyType'] == "IDR" || $arr['CurrencyType'] == "KHR" || $arr['CurrencyType'] == "MMK" || $arr['CurrencyType'] == "VND"){
						$output['result'] = bcdiv($result_array['Balance']*1000, 1, 2);
					}else{
						$output['result'] = bcdiv($result_array['Balance'], 1, 2);
					}
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
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}

	private function sp_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$amount = 0;
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		$current_time = date("YmdHis");
		$str = '';
		
		if($method == 'CreateMember')
		{
			$str = "method=RegUserInfo&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['username']. "&CurrencyType=" . $arr['CurrencyType'];
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
					
			$str = "method=LoginRequest&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['game_id']. "&CurrencyType=" . $arr['CurrencyType'] . '&GameCode=' . $post_data['game_code'] . '&Lang=' . $lang . '&Mobile=' . (($post_data['device'] == PLATFORM_WEB) ? 0 : 1) . '&returnurl=' . $post_data['return_url'] . '&skipintro=1';
		}
		else if($method == 'GetBalance')
		{
			$str = "method=GetUserStatus&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['game_id'];
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				if($arr['CurrencyType'] == "IDR" || $arr['CurrencyType'] == "KHR" || $arr['CurrencyType'] == "MMK" || $arr['CurrencyType'] == "VND"){
					$amount = bcdiv($post_data['amount']/1000,1,2);
				}else{
					$amount = $post_data['amount'];
				}

				$requestOrderIDAlias = str_replace(array($post_data['username']), array($post_data['game_id']), $post_data['order_id']);
				$str = "method=CreditBalance&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['game_id'] . "&OrderId=" . $requestOrderIDAlias . "&CreditAmount=" . $amount;
			}
			else
			{
				if($arr['CurrencyType'] == "IDR" || $arr['CurrencyType'] == "KHR" || $arr['CurrencyType'] == "MMK" || $arr['CurrencyType'] == "VND"){
					$amount = bcdiv(($post_data['amount'] * -1 / 1000), 1, 2);
				}else{
					$amount = bcdiv(($post_data['amount'] * -1), 1, 2);
				}

				$requestOrderIDAlias = str_replace(array($post_data['username']), array($post_data['game_id']), $post_data['order_id']);
				$str = "method=DebitBalance&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['game_id'] . "&OrderId=" . $requestOrderIDAlias . "&DebitAmount=" . $amount;
			}
		}
		else if($method == 'LogoutGame')
		{
			$str = "method=KickUser&Key=" . $arr['SecretKey'] . "&Time=" . $current_time . "&Username=" . $post_data['game_id'];
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
		$curl_array = $response['curl'];
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
					$output['gameID'] = $post_data['username'];
					$output['gamePassword'] = $post_data['password'];
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
					if($arr['CurrencyType'] == "IDR" || $arr['CurrencyType'] == "KHR" || $arr['CurrencyType'] == "MMK" || $arr['CurrencyType'] == "VND"){
						$output['result'] = bcdiv($result_array['Balance'] * 1000, 1, 2);
					}else{
						$output['result'] = bcdiv($result_array['Balance'], 1, 2);
					}
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
					if($arr['CurrencyType'] == "IDR" || $arr['CurrencyType'] == "KHR" || $arr['CurrencyType'] == "MMK" || $arr['CurrencyType'] == "VND"){
						$output['result'] = bcdiv($result_array['Balance'] * 1000, 1, 2);
					}else{
						$output['result'] = bcdiv($result_array['Balance'], 1, 2);
					}
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
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}

	private function spsb_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
	    $arr = json_decode($api_data, TRUE);
	    
	    //Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		$url = $arr['APIUrl'];
        $requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		
		if($method == 'CreateMember')
		{
		    $url .= "api/account";
                
            $param_array = array(
    			"act" => "add",
    			"up_account" => $arr['UpAccount'],
    			"up_passwd" => $arr['UpPassword'],
    			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
    			"passwd" => "a".$post_data['password']."b",
    			"nickname" => $post_data['username'],
    			"level" => 1
    		);
    		
    		if(isset($arr['CopyTarget']) && !empty($arr['CopyTarget'])){
    		    $param_array['act'] = "cpAdd";
    		    $param_array['copy_target'] = $arr['CopyTarget'];
    		}
    		
    		$this->load->library('aes_ecb');
    		$aes = new Aes_ecb();
    		$aes->set_mode(MCRYPT_MODE_CBC);
    		$aes->set_iv($arr['IVkey']);
    		$aes->set_key($arr['Deskey']);
    		$aes->require_pkcs5();
    		$param_array['up_account'] = $aes->encrypt($param_array['up_account']);
    		$param_array['up_passwd'] = $aes->encrypt($param_array['up_passwd']);
    		$param_array['account'] = $aes->encrypt($param_array['account']);
    		$param_array['passwd'] = $aes->encrypt($param_array['passwd']);
		}
		else if($method == 'LoginGame')
		{
		    if($post_data['is_demo'] == STATUS_YES)
		    {
		        
		    }else{
		        $url .= "api/login";
                $language = 'EN-US';
			
    			switch($post_data['syslang'])
    			{
    				case LANG_ZH_CN: $language = 'ZH-CN'; break;
    				case LANG_ZH_HK:
    				case LANG_ZH_TW: $language = 'ZH-TW'; break;
    				case LANG_TH: $language = 'TH'; break;
    				case LANG_VI: $language = 'VI'; break;
    			}
                
                $param_array = array(
        			"account" => $post_data['game_id'],
        			"passwd" => $post_data['password'],
        			"responseFormat" => "json",
        			"lang" => $language,
        		);
        		
        		$this->load->library('aes_ecb');
        		$aes = new Aes_ecb();
        		$aes->set_mode(MCRYPT_MODE_CBC);
        		$aes->set_iv($arr['IVkey']);
        		$aes->set_key($arr['Deskey']);
        		$aes->require_pkcs5();
        		$param_array['account'] = $aes->encrypt($param_array['account']);
        		$param_array['passwd'] = $aes->encrypt($param_array['passwd']);
		    }
		}
		else if($method == 'GetBalance')
		{
		    $url .= "api/points";
                
            $param_array = array(
    			"act" => "search",
    			"up_account" => $arr['UpAccount'],
    			"up_passwd" => $arr['UpPassword'],
    			"account" => $post_data['game_id'],
    		);
    		
    		$this->load->library('aes_ecb');
    		$aes = new Aes_ecb();
    		$aes->set_mode(MCRYPT_MODE_CBC);
    		$aes->set_iv($arr['IVkey']);
    		$aes->set_key($arr['Deskey']);
    		$aes->require_pkcs5();
    		$param_array['up_account'] = $aes->encrypt($param_array['up_account']);
    		$param_array['up_passwd'] = $aes->encrypt($param_array['up_passwd']);
    		$param_array['account'] = $aes->encrypt($param_array['account']);
		}
		else if($method == 'ChangeBalance')
		{
		    $url .= "api/points";
            $requestOrderIDAlias = $arr['UpAccount'].str_replace(array($post_data['username']), array(''), $post_data['order_id']).rand(10000000,99999999);
            
            if($post_data['amount'] > 0) 
			{
				
                $param_array = array(
        			"act" => "add",
        			"up_account" => $arr['UpAccount'],
        			"up_passwd" => $arr['UpPassword'],
        			"account" => $post_data['game_id'],
        			"point" => $post_data['amount'],
        			"track_id" => $requestOrderIDAlias,
        		);
        		$this->load->library('aes_ecb');
        		$aes = new Aes_ecb();
        		$aes->set_mode(MCRYPT_MODE_CBC);
        		$aes->set_iv($arr['IVkey']);
        		$aes->set_key($arr['Deskey']);
        		$aes->require_pkcs5();
        		$param_array['up_account'] = $aes->encrypt($param_array['up_account']);
        		$param_array['up_passwd'] = $aes->encrypt($param_array['up_passwd']);
        		$param_array['account'] = $aes->encrypt($param_array['account']);
			}
			else
			{
				$param_array = array(
        			"act" => "sub",
        			"up_account" => $arr['UpAccount'],
        			"up_passwd" => $arr['UpPassword'],
        			"account" => $post_data['game_id'],
        			"point" => bcdiv(($post_data['amount'] * -1), 1, 2),
        			"track_id" => $requestOrderIDAlias,
        		);
        		$this->load->library('aes_ecb');
        		$aes = new Aes_ecb();
        		$aes->set_mode(MCRYPT_MODE_CBC);
        		$aes->set_iv($arr['IVkey']);
        		$aes->set_key($arr['Deskey']);
        		$aes->require_pkcs5();
        		$param_array['up_account'] = $aes->encrypt($param_array['up_account']);
        		$param_array['up_passwd'] = $aes->encrypt($param_array['up_passwd']);
        		$param_array['account'] = $aes->encrypt($param_array['account']);
			}
		}
		else if($method == 'LogoutGame')
		{
		    $url .= "api/logout";
                
            $param_array = array(
    			"account" => $post_data['game_id'],
    		);
    		
    		$this->load->library('aes_ecb');
    		$aes = new Aes_ecb();
    		$aes->set_mode(MCRYPT_MODE_CBC);
    		$aes->set_iv($arr['IVkey']);
    		$aes->set_key($arr['Deskey']);
    		$aes->require_pkcs5();
    		$param_array['account'] = $aes->encrypt($param_array['account']);
		}
		
		if($method == 'LoginGame' && $post_data['is_demo'] == STATUS_YES)
		{
		    
		}else{
		    $response = $this->curl_post($url, $param_array);
            $curl_array = $response['curl'];   
            if($response['code'] == '0')
            {
                $result_array = json_decode($response['data'], TRUE);
                if($method == 'CreateMember')
    			{
    			    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['gameID'] = $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT);
						$output['gamePassword'] = "a".$post_data['password']."b";
					}
					else if(isset($result_array['code']) && $result_array['code'] == '912')
					{
					    $output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
    			}
    			else if($method == 'LoginGame')
    			{
    			    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = $result_array['data']['login_url'];
					}
					else if(isset($result_array['code']) && $result_array['code'] == '903')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '904')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
    			}
    			else if($method == 'GetBalance')
    			{
    			    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['point'], 1, 2);
					}
					else if(isset($result_array['code']) && $result_array['code'] == '903')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '904')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
    			}
    			else if($method == 'ChangeBalance')
    			{
    			    $is_checking = FALSE;
    			    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['point'], 1, 2);
					}
					else if(isset($result_array['code']) && $result_array['code'] == '903')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '904')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '921')
					{
					    $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '921')
					{
					    $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
					else if(isset($result_array['code']) && $result_array['code'] == 'exception')
					{
					    $is_checking = TRUE;
					}
					else if(isset($result_array['code'])){
					    
					}else{
					    $is_checking = TRUE;
					}
					
					if($is_checking){
					    $output['errorCode'] = ERROR_OVERTIME;
				        $output['errorMessage'] = $this->lang->line('error_overtime');
				        
				        $url_2 = $arr['APIUrl'];
				        $url_2 .= "api/points";
				        
					    $param_array_2 = array(
                			"act" => "checking",
                			"up_account" => $arr['UpAccount'],
                			"up_passwd" => $arr['UpPassword'],
                			"account" => $post_data['game_id'],
                			"track_id" => $requestOrderIDAlias,
                		);
                		$this->load->library('aes_ecb');
                		$aes = new Aes_ecb();
                		$aes->set_mode(MCRYPT_MODE_CBC);
                		$aes->set_iv($arr['IVkey']);
                		$aes->set_key($arr['Deskey']);
                		$aes->require_pkcs5();
                		$param_array_2['up_account'] = $aes->encrypt($param_array_2['up_account']);
                		$param_array_2['up_passwd'] = $aes->encrypt($param_array_2['up_passwd']);
                		$param_array_2['account'] = $aes->encrypt($param_array_2['account']);
                		$response_2 = $this->curl_post($url_2, $param_array_2);
    			        $curl_array_2 = $response_2['curl'];
    			        if($response_2['code'] == '0')
        		        {
        		            $result_array_2 = json_decode($response_2['data'], TRUE);
        		            if(isset($result_array_2['code']) && $result_array_2['code'] == '999')
					        {
					            if(isset($result_array_2['result']) && $result_array_2['result'] == '1')
					            {
					                $output['errorCode'] = ERROR_SUCCESS;
            						$output['errorMessage'] = $this->lang->line('error_success');
            						$output['result'] = "0.00";
					            }
					            else if(isset($result_array_2['result']) && $result_array_2['result'] == '0')
					            {
					                $output['errorCode'] = ERROR_SYSTEM_ERROR;
            						$output['errorMessage'] = $this->lang->line('error_system_error');
					            }
					        }
        		        }
        		        $this->db->trans_start();
                		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array_2, $result_array_2, $curl_array_2);
                		$this->db->trans_complete();
					}
    			}
            }else{
    			if($response['code'] == '404'){
    				$output['errorCode'] = ERROR_OVERTIME;
    				$output['errorMessage'] = $this->lang->line('error_overtime');
    			}
    		}
    		
    		$this->db->trans_start();
    		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
    		$this->db->trans_complete();
		}
		
		return $output;
	}
	
	private function spsb2_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
	    $arr = json_decode($api_data, TRUE);
	    
	    //Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		$url = $arr['APIUrl'];
        $requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		
		if($method == 'CreateMember')
		{
		    $url .= '/api/Sport';
            $param_array = array(
                'Cmd' => "CreateUser",
                'VendorId' => $arr['VendorID'],
                'Signature' => strtoupper(md5($arr['VendorID'].$arr['ApiKey'])),
                'User' => ((substr($post_data['username'], 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($post_data['username'], strlen($sys_data['system_prefix'])) : $post_data['username']).$arr['UPrefix'],
                'Password' => $post_data['password'],
                'Name' => $post_data['username'],
                'UpAccount' =>  $arr['UpAccount'],
            );
            if(isset($arr['CopyTarget']) && !empty($arr['CopyTarget'])){
    		    $param_array['CopyTarget'] = $arr['CopyTarget'];
    		}
		}
		else if($method == 'LoginGame')
		{
		    if($post_data['is_demo'] == STATUS_YES)
		    {
		        
		    }else{
		        $url .= '/api/Sport';
                
                $lang = "tw";
                switch($post_data['syslang'])
    			{
    				case LANG_ZH_CN: $lang = "cn"; break;
    				case LANG_ZH_HK:
    				case LANG_ZH_TW: $lang = "tw"; break;
    			}
                $param_array = array(
                    'Cmd' => "LoginGame",
                    'VendorId' => $arr['VendorID'],
                    'Signature' => strtoupper(md5($arr['VendorID'].$arr['ApiKey'])),
                    'User' => $post_data['game_id'],
                    'Lang' => $lang,
                );
                if( ! empty($post_data['return_url']))
    			{
    				$param_array['ReturnUrl'] = $post_data['return_url'];
    			}
		    }
		}
		else if($method == 'GetBalance')
		{
		    $url .= '/api/Sport';
            $param_array = array(
                'Cmd' => "GetUserBalance",
                'VendorId' => $arr['VendorID'],
                'Signature' => strtoupper(md5($arr['VendorID'].$arr['ApiKey'])),
                'User' => $post_data['game_id'],
            );
		}
		else if($method == 'ChangeBalance')
		{
		    $requestOrderIDAlias = $arr['UpAccount'].str_replace(array($post_data['username']), array(''), $post_data['order_id']).rand(10000000,99999999);
		    if($post_data['amount'] > 0) 
			{
			    $actual_amount = bcdiv($post_data['amount'],1,2);
			    $amount = bcdiv($post_data['amount'],1,0);
			    $ttype = 1;
			}
			else
			{
			    $actual_amount = bcdiv(($post_data['amount'] * -1),1,2);
			    $amount = bcdiv(($post_data['amount'] * -1), 1, 0);
			    $ttype = 0;
			}
			
			$url .= '/api/Sport';
            $param_array = array(
                'Cmd' => "TransferPoint",
                'VendorId' => $arr['VendorID'],
                'Signature' => strtoupper(md5($arr['VendorID'].$arr['ApiKey'])),
                'User' => $post_data['game_id'],
                'Point' => $amount,
                'TType' => $ttype,
                'OrderId' => $requestOrderIDAlias,
            );
		}
		else if($method == 'LogoutGame')
		{
		    $url .= '/api/Sport';
            $param_array = array(
                'Cmd' => "LogoutGame",
                'VendorId' => $arr['VendorID'],
                'Signature' => strtoupper(md5($arr['VendorID'].$arr['ApiKey'])),
                'User' => $post_data['game_id'],
            );
		}
		
		$is_allow = FALSE;
		if($method == 'ChangeBalance')
	    {
	        if($actual_amount == $amount){
	            $is_allow = TRUE;
	        }
	    }else{
	        $is_allow = TRUE;
	    }
		
		if($is_allow == TRUE){
		    if($method == 'LoginGame' && $post_data['is_demo'] == STATUS_YES)
    		{
    		    
    		}else{
    		    $response = $this->curl_post($url, $param_array);
                $curl_array = $response['curl'];   
                if($response['code'] == '0')
                {
                    $result_array = json_decode($response['data'], TRUE);
                    if($method == 'CreateMember')
        			{
        			    if(isset($result_array['Code']) && $result_array['Code'] == '200'){
            		        $output['errorCode'] = ERROR_SUCCESS;
            		        $output['errorMessage'] = $this->lang->line('error_success');
    					    $output['gameID'] = $param_array['User'];
    					    $output['gamePassword'] = $param_array['Password'];
            		    }else if(isset($result_array['Code']) && $result_array['Code'] == '-112'){
            		        $output['errorCode'] = ERROR_USERNAME_EXITS;
        				    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
            		    }
        			}
        			else if($method == 'LoginGame')
        			{
        			    if(isset($result_array['Code']) && $result_array['Code'] == '200'){
        			        if(isset($arr['CopyTarget']) && !empty($arr['CopyTarget'])){
        			            $copy_url = $arr['APIUrl'];
        			            $copy_url .= '/api/Sport';
        			            
        			            $copy_param_array = array(
                                    'Cmd' => "CopyUserSetting",
                                    'VendorId' => $arr['VendorID'],
                                    'Signature' => strtoupper(md5($arr['VendorID'].$arr['ApiKey'])),
                                    'User' => $post_data['game_id'],
                                    'CopyTarget' => $arr['CopyTarget'],
                                );
                                $copy_response = $this->curl_post($copy_url, $copy_param_array);
                                if($copy_response['code'] == '0')
                                {
                                    $copy_result_array = json_decode($copy_response['data'], TRUE);
                                    if(isset($copy_result_array['Code']) && $copy_result_array['Code'] == '200'){
                                        $output['errorCode'] = ERROR_SUCCESS;
                        		        $output['errorMessage'] = $this->lang->line('error_success');
                        		        $output['result'] = (($post_data['device'] == PLATFORM_WEB) ? $result_array['Data']['RedirectUrl'] : $result_array['Data']['MobileRedirectUrl']);   
                                    }
                                }
        			        }else{
        			            $output['errorCode'] = ERROR_SUCCESS;
                		        $output['errorMessage'] = $this->lang->line('error_success');
                		        $output['result'] = (($post_data['device'] == PLATFORM_WEB) ? $result_array['Data']['RedirectUrl'] : $result_array['Data']['MobileRedirectUrl']);   
        			        }
            		    }else if(isset($result_array['Code']) && $result_array['Code'] == '-107'){
            		        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    					    $output['errorMessage'] = $this->lang->line('error_username_not_found');
            		    }
        			}
        			else if($method == 'GetBalance')
        			{
        			    if(isset($result_array['Code']) && $result_array['Code'] == '200'){
            		        $output['errorCode'] = ERROR_SUCCESS;
            		        $output['errorMessage'] = $this->lang->line('error_success');
            		        $output['result'] = bcdiv(bcdiv($result_array['Data']['Balance'], 1, 0),1,2);
            		    }else if(isset($result_array['Code']) && $result_array['Code'] == '-107'){
            		        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    					    $output['errorMessage'] = $this->lang->line('error_username_not_found');
            		    }
        			}
        			else if($method == 'ChangeBalance')
        			{
        			    $is_checking = FALSE;
        			    if(isset($result_array['Code']) && $result_array['Code'] == '200'){
            		        $output['errorCode'] = ERROR_SUCCESS;
            		        $output['errorMessage'] = $this->lang->line('error_success');
            		    }else if(isset($result_array['Code']) && $result_array['Code'] == '-107'){
            		        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    					    $output['errorMessage'] = $this->lang->line('error_username_not_found');
            		    }else if(isset($result_array['Code']) && $result_array['Code'] == '-999'){
            		        $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
    				        $output['errorMessage'] = $this->lang->line('error_amount_insufficient');
            		    }else if(isset($result_array['Code'])){
            		        
            		    }else{
            		        $output['errorCode'] = ERROR_OVERTIME;
        				    $output['errorMessage'] = $this->lang->line('error_overtime');
            		    }
        			}
                }else{
        			if($response['code'] == '404'){
        				$output['errorCode'] = ERROR_OVERTIME;
        				$output['errorMessage'] = $this->lang->line('error_overtime');
        			}
        		}
        		
        		$this->db->trans_start();
        		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
        		$this->db->trans_complete();
    		}
		}
		
		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		return $output;
	}
	
	private function splt_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
	    $arr = json_decode($api_data, TRUE);
	    
	    //Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		$url = $arr['APIUrl'];
        $requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		
		if($method == 'CreateMember')
		{
		    $url .= "api_101/account";
		    
            $param_array = array(
    			"act" => "create",
    			"up_acc" => $arr['UpAccount'],
    			"up_pwd" => $arr['UpPassword'],
    			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
    			"passwd" => "a".$post_data['password']."b",
    			"nickname" => substr(((substr($post_data['username'], 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($post_data['username'], strlen($sys_data['system_prefix'])) : $post_data['username']),0,15),
    		);
    		$this->load->library('aes_ecb');
    		$aes = new Aes_ecb();
    		$aes->set_mode(MCRYPT_MODE_CBC);
    		$aes->set_iv($arr['IVkey']);
    		$aes->set_key($arr['Deskey']);
    		$aes->require_pkcs5();
    		$param_array['up_acc'] = $aes->encrypt($param_array['up_acc']);
    		$param_array['up_pwd'] = $aes->encrypt($param_array['up_pwd']);
    		$param_array['account'] = $aes->encrypt($param_array['account']);
    		$param_array['passwd'] = $aes->encrypt($param_array['passwd']);
		}
		else if($method == 'LoginGame')
		{
		    if($post_data['is_demo'] == STATUS_YES)
		    {
		        
		    }else{
		         $url .= "api_101/login";
                    
                $page = "Lobby";
                if(!empty($post_data['game_code'])){
    			    $page = $post_data['game_code'];
    			}
                
                $param_array = array(
        			"account" => $post_data['game_id'],
        			"passwd" => $post_data['password'],
        			"page" => $page,
        		);
        		
        		$this->load->library('aes_ecb');
        		$aes = new Aes_ecb();
        		$aes->set_mode(MCRYPT_MODE_CBC);
        		$aes->set_iv($arr['IVkey']);
        		$aes->set_key($arr['Deskey']);
        		$aes->require_pkcs5();
        		$param_array['account'] = $aes->encrypt($param_array['account']);
        		$param_array['passwd'] = $aes->encrypt($param_array['passwd']);
		    }
		}
		else if($method == 'GetBalance')
		{
		    $url .= "api_101/points";
                
            $param_array = array(
    			"act" => "read",
    			"up_acc" => $arr['UpAccount'],
    			"up_pwd" => $arr['UpPassword'],
    			"account" => $post_data['game_id'],
    		);
    		$this->load->library('aes_ecb');
    		$aes = new Aes_ecb();
    		$aes->set_mode(MCRYPT_MODE_CBC);
    		$aes->set_iv($arr['IVkey']);
    		$aes->set_key($arr['Deskey']);
    		$aes->require_pkcs5();
    		$param_array['up_acc'] = $aes->encrypt($param_array['up_acc']);
    		$param_array['up_pwd'] = $aes->encrypt($param_array['up_pwd']);
    		$param_array['account'] = $aes->encrypt($param_array['account']);
		}
		else if($method == 'ChangeBalance')
		{
		    $url .= "api_101/points";
            $requestOrderIDAlias = $arr['UpAccount'].str_replace(array($post_data['username']), array(''), $post_data['order_id']).rand(10000000,99999999);
            
            if($post_data['amount'] > 0) 
			{
                $param_array = array(
        			"act" => "add",
        			"up_acc" => $arr['UpAccount'],
        			"up_pwd" => $arr['UpPassword'],
        			"account" => $post_data['game_id'],
        			"Point" => $post_data['amount'],
        			"track_id" => $requestOrderIDAlias,
        		);
        		$this->load->library('aes_ecb');
        		$aes = new Aes_ecb();
        		$aes->set_mode(MCRYPT_MODE_CBC);
        		$aes->set_iv($arr['IVkey']);
        		$aes->set_key($arr['Deskey']);
        		$aes->require_pkcs5();
        		$param_array['up_acc'] = $aes->encrypt($param_array['up_acc']);
        		$param_array['up_pwd'] = $aes->encrypt($param_array['up_pwd']);
        		$param_array['account'] = $aes->encrypt($param_array['account']);
			}
			else
			{
				$param_array = array(
        			"act" => "sub",
        			"up_acc" => $arr['UpAccount'],
        			"up_pwd" => $arr['UpPassword'],
        			"account" => $post_data['game_id'],
        			"Point" => bcdiv(($post_data['amount'] * -1), 1, 2),
        			"track_id" => $requestOrderIDAlias,
        		);
        		$this->load->library('aes_ecb');
        		$aes = new Aes_ecb();
        		$aes->set_mode(MCRYPT_MODE_CBC);
        		$aes->set_iv($arr['IVkey']);
        		$aes->set_key($arr['Deskey']);
        		$aes->require_pkcs5();
        		$param_array['up_acc'] = $aes->encrypt($param_array['up_acc']);
        		$param_array['up_pwd'] = $aes->encrypt($param_array['up_pwd']);
        		$param_array['account'] = $aes->encrypt($param_array['account']);
			}
		}
		else if($method == 'LogoutGame')
		{
		    $url .= "api/logout";
                
            $param_array = array(
    			"account" => $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT),
    		);
    		
    		$this->load->library('aes_ecb');
    		$aes = new Aes_ecb();
    		$aes->set_mode(MCRYPT_MODE_CBC);
    		$aes->set_iv($arr['IVkey']);
    		$aes->set_key($arr['Deskey']);
    		$aes->require_pkcs5();
    		$param_array['account'] = $aes->encrypt($param_array['account']);
		}
		
		if($method == 'LoginGame' && $post_data['is_demo'] == STATUS_YES)
		{
		    
		}else{
		    $response = $this->curl_post($url, $param_array);
            $curl_array = $response['curl'];   
            if($response['code'] == '0')
            {
                $result_array = json_decode($response['data'], TRUE);
                if($method == 'CreateMember')
    			{
    			    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['gameID'] = $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT);
						$output['gamePassword'] = "a".$post_data['password']."b";
					}
					else if(isset($result_array['code']) && $result_array['code'] == '909')
					{
					    $output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
    			}
    			else if($method == 'LoginGame')
    			{
    			    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						if(strtolower(substr($result_array['data']['PostHost'],0,5)) == "https"){
					        $game_url = $result_array['data']['PostHost'];    
					    }else{
					        $game_url = "https".substr($result_array['data']['PostHost'],4);
					    }
					    
						$encrypt_data = str_replace('=', '', base64_encode($post_data['provider_code']. '|' . $game_url . '|' . $result_array['data']['PostData']));
					    $output['result'] = base_url('game/splt/' . $encrypt_data);
					}
					else if(isset($result_array['code']) && $result_array['code'] == '903')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '904')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
    			}
    			else if($method == 'GetBalance')
    			{
    			    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['data']['point'], 1, 2);
					}
					else if(isset($result_array['code']) && $result_array['code'] == '404')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
    			}
    			else if($method == 'ChangeBalance')
    			{
    			    $is_checking = FALSE;
    			    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['data']['point'], 1, 2);
					}
					else if(isset($result_array['code']) && $result_array['code'] == '404')
					{
					    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '422')
					{
					    $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '422')
					{
					    $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
					else if(isset($result_array['code']) && $result_array['code'] == '500')
					{
					    $is_checking = TRUE;
					}
					else if(isset($result_array['code'])){
					    
					}else{
					    $is_checking = TRUE;
					}
					
					if($is_checking){
					    $output['errorCode'] = ERROR_OVERTIME;
				        $output['errorMessage'] = $this->lang->line('error_overtime');
				        
				        $url_2 = $arr['APIUrl'];
				        $url_2 .= "api_101/points";
				        
					    $param_array_2 = array(
                			"act" => "log",
                			"up_acc" => $arr['UpAccount'],
                			"up_pwd" => $arr['UpPassword'],
                			"account" => $post_data['game_id'],
                			"track_id" => $requestOrderIDAlias,
                		);
                		$this->load->library('aes_ecb');
                		$aes = new Aes_ecb();
                		$aes->set_mode(MCRYPT_MODE_CBC);
                		$aes->set_iv($arr['IVkey']);
                		$aes->set_key($arr['Deskey']);
                		$aes->require_pkcs5();
                		$param_array_2['up_acc'] = $aes->encrypt($param_array_2['up_acc']);
                		$param_array_2['up_pwd'] = $aes->encrypt($param_array_2['up_pwd']);
                		$param_array_2['account'] = $aes->encrypt($param_array_2['account']);
                		$response_2 = $this->curl_post($url_2, $param_array_2);
    			        $curl_array_2 = $response_2['curl'];
    			        $result_array_2 = array();
    			        if($response_2['code'] == '0')
        		        {
        		            $result_array_2 = json_decode($response_2['data'], TRUE);
        		            if(isset($result_array_2['code']) && $result_array_2['code'] == '999')
					        {
					            if(isset($result_array_2['result']) && $result_array_2['result'] == '1')
					            {
					                $output['errorCode'] = ERROR_SUCCESS;
            						$output['errorMessage'] = $this->lang->line('error_success');
            						$output['result'] = "0.00";
					            }
					            else if(isset($result_array_2['result']) && $result_array_2['result'] == '0')
					            {
					                $output['errorCode'] = ERROR_SYSTEM_ERROR;
            						$output['errorMessage'] = $this->lang->line('error_system_error');
					            }
					        }
        		        }
        		        $this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array_2, $result_array_2, $curl_array_2);
					}
    			}
    			else if($method == 'LogoutGame')
		        {
    			    if(isset($result_array['code']) && $result_array['code'] == '999')
					{
					    $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}
		        }
            }else{
    			if($response['code'] == '404'){
    				$output['errorCode'] = ERROR_OVERTIME;
    				$output['errorMessage'] = $this->lang->line('error_overtime');
    			}
    		}
    		
    		$this->db->trans_start();
    		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
    		$this->db->trans_complete();
		}
		
		return $output;
	}
	
	#{"APIUrl":"", "MerchantCode":"", "SignKey":"", "APIKey":""}
	private function t1g_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = $requestOrderID;
		$result_array = array();
		$curl_array = array();	
		
		$param_array = array(
			"merchant_code" => $arr['MerchantCode'],
			"username" => $post_data['username']
		);

		if($method == 'CreateMember')
		{
			$url .= '/gameapi/v2/create_player';
		}
		else if($method == 'LoginGame')
		{
			$url .= '/gameapi/v2/chain/query_game_launcher';
			
			$lang = 'en-US';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: 
				case LANG_ZH_HK:
				case LANG_ZH_TW: $lang = 'zh-CN'; break;
				case LANG_ID: $lang = 'id-IN'; break;
				case LANG_TH: $lang = 'th-TH'; break;
				case LANG_VI: $lang = 'vi-VN'; break;
				case LANG_KO: $lang = 'ko-KR'; break;
			}
			
			$param_array['game_code'] = $post_data['game_code'];
			$param_array['language'] = $lang;
			
			if( ! empty($post_data['return_url']))
			{
				$param_array['home_link'] = $post_data['return_url'];
			}
			
			if($post_data['is_demo'] == STATUS_YES)
			{
				$param_array['trial'] = true;
			}
		}
		else if($method == 'GetBalance')
		{
			$url .= '/gameapi/v2/query_player_balance';
		}
		else if($method == 'ChangeBalance')
		{
			$url .= '/gameapi/v2/transfer_player_fund';
			
			if($post_data['amount'] > 0) 
			{
				$param_array['action_type'] = 'deposit';
				$param_array['amount'] = $post_data['amount'];
			}
			else
			{
				$param_array['action_type'] = 'withdraw';
				$param_array['amount'] = ($post_data['amount'] * -1);
			}
			
			$param_array['external_trans_id'] = $requestOrderID;
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/gameapi/v2/kick_player';
		}
		
		$token_url = $arr['APIUrl'] . '/gameapi/v2/generate_token';
		
		$token_param_array = array(
			'merchant_code' => $arr['MerchantCode'],
			'secure_key' => $arr['APIKey'],
		);

		ksort($token_param_array);
		$token_string = '';
		foreach($token_param_array as $k=>$v){
			if($k != '' && ! is_array($k)){
			   $token_string .= $v;
			}
		}
		
		$token_param_array['sign'] = strtolower(sha1($token_string . $arr['SignKey']));
		
		$response_token = $this->curl_json($token_url, $token_param_array);
		
		if($response_token['code'] == '0')
		{
			$result_token_array = json_decode($response_token['data'], TRUE);
			
			if(isset($result_token_array['success']) && $result_token_array['success'] == true)
			{
				$param_array['auth_token'] = $result_token_array['detail']['auth_token'];
				
				ksort($param_array);
				$string = '';
				foreach($param_array as $k=>$v){
					if($k != '' && ! is_array($k)){
					   $string .= $v;
					}
				}
				
				$param_array['sign'] = sha1($string . $arr['SignKey']);
				
				if($method == 'LoginGame' || $method == 'GetBalance')
				{
					$url .= "?" . http_build_query($param_array);
					$response = $this->curl_get($url);
				}
				else
				{
					$response = $this->curl_json($url, $param_array);
				}
				
				$curl_array = $response['curl'];
				
				if($response['code'] == '0')
				{
					$result_array = json_decode($response['data'], TRUE);
					
					if($method == 'CreateMember')
					{
						if(isset($result_array['code']) && $result_array['code'] == '0')
						{
							$output['errorCode'] = ERROR_SUCCESS;
							$output['errorMessage'] = $this->lang->line('error_success');
							$output['gameID'] = $post_data['username'];
							$output['gamePassword'] = $post_data['password'];
						}
						else if(isset($result_array['code']) && $result_array['code'] == '8')
						{
							$output['errorCode'] = ERROR_USERNAME_EXITS;
							$output['errorMessage'] = $this->lang->line('error_username_already_exits');
						}
					}
					else if($method == 'LoginGame')
					{
						if(isset($result_array['code']) && $result_array['code'] == '0')
						{
							$output['errorCode'] = ERROR_SUCCESS;
							$output['errorMessage'] = $this->lang->line('error_success');
							$output['result'] = $result_array['detail']['game_url'];
						}
						else if(isset($result_array['code']) && $result_array['code'] == '29')
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
							$output['result'] = bcdiv($result_array['detail']['game_platform_balance'], 1, 2);
						}
						else if(isset($result_array['code']) && $result_array['code'] == '29')
						{
							$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
							$output['errorMessage'] = $this->lang->line('error_username_not_found');
						}
					}
					else if($method == 'ChangeBalance')
					{
						if(isset($result_array['code']) && $result_array['code'] == '0')
						{
							unset($param_array['action_type']);
							unset($param_array['amount']);
							unset($param_array['username']);
							unset($param_array['sign']);
							$param_array['transaction_id'] = $result_array['detail']['transaction_id'];
							
							ksort($param_array);
							$string = '';
							foreach($param_array as $k=>$v){
								if($k != '' && ! is_array($k)){
								   $string .= $v;
								}
							}
							
							$sign = sha1($string . $arr['SignKey']);
							
							$url_2 = $arr['APIUrl'] . "/gameapi/v2/query_transaction?" . http_build_query($param_array) . '&sign=' . $sign;
							$response_2 = $this->curl_get($url_2);
							
							if($response_2['code'] == '0')
							{
								$result_array_2 = json_decode($response_2['data'], TRUE);
							
								if(isset($result_array_2['code']) && $result_array_2['code'] == '0')
								{
									$output['errorCode'] = ERROR_SUCCESS;
									$output['errorMessage'] = $this->lang->line('error_success');
								}						
							}
						}
						else if(isset($result_array['code']) && $result_array['code'] == '29')
						{
							$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
							$output['errorMessage'] = $this->lang->line('error_username_not_found');
						}
						else if(isset($result_array['code']) && $result_array['code'] == '15')
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
						}
					}
				}else{
					if($response['code'] == '404'){
						$output['errorCode'] = ERROR_OVERTIME;
						$output['errorMessage'] = $this->lang->line('error_overtime');
					}
				}
			}
		}	
		
		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
    
    private function ug_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		$currency_one = array("BIF", "COP", "IDR", "IQD", "IRR", "KHR", "KRW", "LBP", "MMK", "MNT", "PYG", "TZS", "UGX", "VND");
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		
		$param_array = array();
		
		if($method == 'CreateMember')
		{
			$url .= "/api/transfer/register";
            $param_array = array(
                "apiKey" => $arr['ApiKey'],
                "operatorId" => $arr['OperatorID'],
    			"userId" => $post_data['username'],
    			"loginName" => $post_data['username'],
    			"currencyId" => $arr['Currency'],
    		);
		}
		else if($method == 'LoginGame')
		{
			$url .= "/api/transfer/getLoginUrl";
            $param_array = array(
                "apiKey" => $arr['ApiKey'],
                "operatorId" => $arr['OperatorID'],
    			"userId" => $post_data['username'],
    			"oddsExpression" => $arr['OddsExpression'],
    		);
    		
    		if( ! empty($post_data['return_url']))
			{
				$param_array['returnUrl'] = $post_data['return_url'];
			}
			
			$lang = "en";
		
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $lang = "zh_cn"; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $lang = "zh_cn"; break;
				case LANG_ID: $lang = "id"; break;
				case LANG_TH: $lang = "th"; break;
				case LANG_VI: $lang = "vi"; break;
			}
			$param_array['language'] = $lang;
			$param_array['webType'] = (($post_data['device'] == PLATFORM_WEB) ? "pc" : "mobile");
			$param_array['theme'] = $arr['Theme'];
		}
		else if($method == 'GetBalance')
		{
			$url .= "/api/transfer/getBalance";
            $param_array = array(
                "apiKey" => $arr['ApiKey'],
                "operatorId" => $arr['OperatorID'],
    			"userId" => $post_data['username'],
    		);
		}
		else if($method == 'ChangeBalance')
		{
			$timestamp = time();
            $realOperatorID = strtolower(((strlen($arr['OperatorID'])>16)?substr($arr['OperatorID'],-16):str_pad($arr['OperatorID'],16,"0",STR_PAD_LEFT)));
            $md5_content = md5(strtolower($arr['ApiKey'].$realOperatorID)).$timestamp;
            
            $ivKey = $realOperatorID;
            $deskey = $arr['ApiKey'];
            
            $this->load->library('aes_ecb');
    		$aes = new Aes_ecb();
            $aes->set_mode(MCRYPT_MODE_CBC);
    		$aes->set_iv($ivKey);
    		$aes->set_key($deskey);
    		$aes->require_pkcs5();
    		$apiPassword = $aes->encrypt($md5_content);
    		
    		if($post_data['amount'] > 0) 
			{
			    $url .= "/api/transfer/deposit";
			    if(in_array($arr['Currency'],$currency_one)){
					$amount = bcdiv($post_data['amount'] / 1000,1,2);
				}else{
					$amount = $post_data['amount'];
				}
			}
			else
			{
			    $url .= "/api/transfer/withdraw";
			    if(in_array($arr['Currency'],$currency_one)){
					$amount = bcdiv(($post_data['amount'] * -1 / 1000), 1, 2);
				}else{
					$amount = bcdiv(($post_data['amount'] * -1), 1, 2);
				}
			}
			$requestOrderIDAlias = $post_data['order_id'].rand(1000000,9999999);
			$real_amount = bcdiv($amount, 1, 4);
			    
			$param_array = array(
                "apiKey" => $arr['ApiKey'],
                "operatorId" => $arr['OperatorID'],
                'apiPassword' => $apiPassword,
    			"userId" => $post_data['username'],
    			"serialNumber" => $requestOrderIDAlias,
    			"amount" => $real_amount,
    		);
			$key = substr(md5(strtolower($apiPassword.$param_array['userId'].$param_array['amount'])),-6);
			$param_array['key'] = $key;
		}
		else if($method == 'LogoutGame')
		{
			$url .= "/api/transfer/logout";
            $param_array = array(
                "apiKey" => $arr['ApiKey'],
                "operatorId" => $arr['OperatorID'],
    			"userId" => $post_data['username'],
    		);
		}
		
		//Get response from curl
		$response = $this->curl_json($url, $param_array);
		$curl_array = $response['curl'];
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['code']) && $result_array['code'] == '000000')
			    {
			        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    $output['gameID'] = $param_array['userId'];
				    $output['gamePassword'] = $post_data['password'];
			    }
			    else if(isset($result_array['code']) && $result_array['code'] == '100004'){
			        $output['errorCode'] = ERROR_USERNAME_EXITS;
				    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
			    }
			}
			else if($method == 'LoginGame')
			{
				if(isset($result_array['code']) && $result_array['code'] == '000000')
			    {
			        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    $output['result'] = $result_array['data'];
			    }
			    else if(isset($result_array['code']) && $result_array['code'] == '100007'){
			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
			    }
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['code']) && $result_array['code'] == '000000')
			    {
			        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    if(in_array($arr['Currency'],$currency_one)){
						$output['result'] = bcdiv($result_array['data']['balance']*1000, 1, 2);
					}else{
						$output['result'] = bcdiv($result_array['data']['balance'], 1, 2);
					}
			    }
			    else if(isset($result_array['code']) && $result_array['code'] == '100007'){
			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
			    }
			}
			else if($method == 'ChangeBalance')
			{
				if(isset($result_array['code']) && $result_array['code'] == '000000')
			    {
			        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    if(in_array($arr['Currency'],$currency_one)){
						$output['result'] = bcdiv($result_array['data']['balance']*1000, 1, 2);
					}else{
						$output['result'] = bcdiv($result_array['data']['balance'], 1, 2);
					}
			    }
			    else if(isset($result_array['code']) && $result_array['code'] == '100007'){
			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
			    }else if(isset($result_array['code']) && $result_array['code'] == '000001'){
			        $output['errorCode'] = ERROR_OVERTIME;
				    $output['errorMessage'] = $this->lang->line('error_overtime');
			    }else if(isset($result_array['code']) && $result_array['code'] == '000001'){
			        $output['errorCode'] = ERROR_OVERTIME;
				    $output['errorMessage'] = $this->lang->line('error_overtime');
			    }else if(isset($result_array['code'])){
			        
			    }else{
			        $output['errorCode'] = ERROR_OVERTIME;
				    $output['errorMessage'] = $this->lang->line('error_overtime');
			    }
			}
			else if($method == 'LogoutGame')
			{
				if(isset($result_array['code']) && $result_array['code'] == '000000')
			    {
			        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
			    }
			    else if(isset($result_array['code']) && $result_array['code'] == '100007'){
			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
			    }
			}
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
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
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		$syslang = 1;
		$amount = 0;
					
		switch($post_data['syslang'])
		{
			case LANG_ZH_CN:
			case LANG_ZH_HK:
			case LANG_ZH_TW: $syslang = 0; break;
		}
		
		if($method == 'CreateMember'){
			$param_array = array(
				'vendorId' => $arr['VendorId'],
				'signature' => $arr['Signature'],
				'user' => $post_data['username'],
				'syslang' => $syslang
			);
		}else{
			$param_array = array(
				'vendorId' => $arr['VendorId'],
				'signature' => $arr['Signature'],
				'user' => $post_data['game_id'],
				'syslang' => $syslang
			);
		}
		
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
			if($arr['CurrencyType'] == "IDR"){
				$amount = bcdiv($post_data['amount'] / 1000,1,2);
			}else{
				$amount = $post_data['amount'];
			}
			$requestOrderIDAlias = $post_data['order_id'];
			$param_array['cmd'] = 'ChangeBalance';
			$param_array['money'] = $amount;
			$param_array['order'] = $requestOrderIDAlias;
		}
		else if($method == 'LogoutGame')
		{
			$param_array['cmd'] = 'LogoutGame';
		}
		
		//Get response from curl
		$response = $this->curl_post($url, $param_array);
		$curl_array = $response['curl'];
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $post_data['username'];
					$output['gamePassword'] = $post_data['password'];
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
					if(strtolower(substr($result_array['result'],0,4)) == "http"){
					    $output['result'] = $result_array['result'];
					}else{
					    $output['result'] = "https://".$result_array['result'];   
					}
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
					if($arr['CurrencyType'] == "IDR"){
						$output['result'] = bcdiv($result_array['result'] * 1000, 1, 2);
					}else{
						$output['result'] = bcdiv($result_array['result'], 1, 2);
					}
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
					if($arr['CurrencyType'] == "IDR"){
						$output['result'] = bcdiv($result_array['result']['cash'] * 1000, 1, 2);
					}else{
						$output['result'] = bcdiv($result_array['result']['cash'], 1, 2);
					}
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
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}
		
		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
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
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
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
					"currency" => $arr['CurrencyType'],
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
			
			if(isset($arr['BetLimit']) && !empty($arr['BetLimit'])){
			    $param_array["player"]["group"]["id"] = $arr['BetLimit'];
			    $param_array["player"]["group"]["action"] = "assign";
			}
		}
		else if($method == 'GetBalance')
		{
			$url .= "/api/ecashier?cCode=RWA&ecID=" . $arr['CasinoKey'] . "&euID=" . $post_data['username'] . "&output=1"; 
		}
		else if($method == 'ChangeBalance')
		{
			$requestOrderIDAlias = $post_data['order_id'];
			if($post_data['amount'] > 0) 
			{
				$url .= "/api/ecashier?cCode=ECR&ecID=" . $arr['CasinoKey'] . "&euID=" . $post_data['username'] . "&amount=" . $post_data['amount'] . "&eTransID=" . $requestOrderIDAlias . "&createuser=N&output=1"; 
			}
			else
			{
				$url .= "/api/ecashier?cCode=EDB&ecID=" . $arr['CasinoKey'] . "&euID=" . $post_data['username'] . "&amount=" . bcdiv(($post_data['amount'] * -1), 1, 2) . "&eTransID=" . $requestOrderIDAlias . "&output=1";
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
				$curl_array = $response['curl'];
			}
			else
			{
				$response = $this->curl_get($url);
				$curl_array = $response['curl'];
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
						$output['gameID'] = $post_data['username'];
						$output['gamePassword'] = $post_data['password'];
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
			}else{
				if($response['code'] == '404'){
					$output['errorCode'] = ERROR_OVERTIME;
					$output['errorMessage'] = $this->lang->line('error_overtime');
				}
			}
		}	

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	public function xg_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL){
	    $arr = json_decode($api_data, TRUE);
	    
	    //Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		$result_array = array();
		$param_array = array();
		
		if($method == 'CreateMember')
		{
		    $url .= "/xg-casino/CreateMember";
        			
		    $param_array = array(
		        "Account" => $post_data['username'],
		        "Currency" => $arr['Currency'],
		        "LimitStake" => ((!empty($arr['LimitStake'])) ? $arr['LimitStake'] : ''),
		    );
		}
		else if($method == 'LoginGame')
		{
		    $url .= "/xg-casino/Login";
    		    
    		$lang = 'en-US';
		
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $lang = 'zh-CN'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $lang = 'zh-TW'; break;
				case LANG_ID: $lang = 'id'; break;
				case LANG_TH: $lang = 'th'; break;
				case LANG_VI: $lang = 'vn'; break;
				case LANG_MS: $lang = 'ms'; break;
				case LANG_JA: $lang = 'ja'; break;
				case LANG_KO: $lang = 'ko'; break;
			}
    			
		    if(!empty($post_data['game_code'])){
		        $game_code = explode('_', $post_data['game_code']);
				$game_code = array_values(array_filter($arr));
											
		        $game_code = $post_data['game_code'];
		        $param_array = array(
    		        "Account" => $post_data['game_id'],
    		        "GameType" => ((isset($game_code[0]))? $game_code[0] : ""),
    		        "Lang" => $lang,
    		        "TableId" => ((isset($game_code[1]))? $game_code[1] : ""),
    		        "UIVersion" => $arr['UIVersion'],
    		    );
		    }else{
		        $param_array = array(
    		        "Account" => $post_data['game_id'],
    		        "Lang" => $lang,
    		        "UIVersion" => $arr['UIVersion'],
    		    );
		    }
		}
		else if($method == 'GetBalance')
		{
		    $url .= "/xg-casino/Account";
		    
		    $param_array = array(
		        "Account" => $post_data['game_id'],
		    );
		}
		else if($method == 'ChangeBalance')
		{
		    $requestOrderIDAlias = $requestOrderID;
		    $url .= "/xg-casino/Transfer";
		    
		    if($post_data['amount'] > 0) 
			{
			    $type = 2;
			    $amount = bcdiv($post_data['amount'],1,2);
			}else{
			    $type = 1;
			    $amount = bcdiv($post_data['amount']*-1,1,2);
			}
		    
		    $param_array = array(
                "Account" => $post_data['game_id'],
                "Amount" => $amount,
                "TransactionId" => $requestOrderIDAlias,
                "TransferType" => $type,
            );
		}
		else if($method == 'LogoutGame')
		{
		    $url .= "/xg-casino/KickMember";
		    $param_array = array(
		        "Account" => $post_data['game_id'],
		    );
		}
		
		$keyP = urldecode(http_build_query($param_array, '', '&'))."&AgentId=".$arr['AgentID'];
        $keyA = rand(pow(10, $arr['FrontKey']-1), pow(10, $arr['FrontKey'])-1);
        $keyC = rand(pow(10, $arr['BackKey']-1), pow(10, $arr['BackKey'])-1);
        $keyT = date("ymj", strtotime('-12 hours', time()));
        $keyG = md5($keyT.$arr['AgentID'].$arr['AgentKey']);
        $key = $keyA . md5($keyP.$keyG).$keyC;
        $param_array['AgentId'] = $arr['AgentID'];
        $param_array['Key'] = $key;
        
        if($method == 'GetBalance' || $method == 'LoginGame')
		{
		    $url .= "?" . http_build_query($param_array);
            $response = $this->curl_get_json($url);
		}else{
		    $response = $this->curl_json($url, $param_array);
		}
		
		$curl_array = $response['curl'];
		if($response['code'] == '0')
        {
            $result_array = json_decode($response['data'], TRUE);
            if($method == 'CreateMember')
		    {
		        if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0')
			    {
			        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    $output['gameID'] = $param_array['Account'];
				    $output['gamePassword'] = $post_data['password']; 
			    }else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '8')
			    {
			        $output['errorCode'] = ERROR_USERNAME_EXITS;
				    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
			    }
		    }
    		else if($method == 'LoginGame')
    		{
    		    if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0')
			    {
			        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    $output['result'] = $result_array['Data']['LoginUrl'];
			    }else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '8')
			    {
			        $output['errorCode'] = ERROR_USERNAME_EXITS;
				    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
			    }
    		}
    		else if($method == 'GetBalance')
    		{
    		    if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0')
			    {
			        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    $output['result'] = bcdiv($result_array['Data']['Balance'],1,2);
			    }else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '77')
			    {
			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
			    }
    		}
    		else if($method == 'ChangeBalance')
    		{
		        if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0')
			    {
			        if(isset($result_array['Data']['Status']) && $result_array['Data']['Status'] == '1'){
			            $output['errorCode'] = ERROR_SUCCESS;
					    $output['errorMessage'] = $this->lang->line('error_success');
					    $output['result'] = bcdiv($result_array['Data']['Balance'],1,2);   
			        }else{
			            $output['errorCode'] = ERROR_OVERTIME;
					    $output['errorMessage'] = $this->lang->line('error_overtime');
			        }
			    }else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '77')
			    {
			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
			    }else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '19'){
			        $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
    				$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
			    }else{
			        $output['errorCode'] = ERROR_OVERTIME;
					$output['errorMessage'] = $this->lang->line('error_overtime');
			    }
    		}
    		else if($method == 'LogoutGame')
    		{
    		    if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0')
			    {
			        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
			    }else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '77')
			    {
			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
			    }
    		}
        }else{
            $output['errorCode'] = ERROR_OVERTIME;
    		$output['errorMessage'] = $this->lang->line('error_overtime');
        }
        
        if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
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
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$amount = 0;
		$curl_array = array();
		$param_array = array();
		
		if($method == 'CreateMember')
		{
			$url .= '/player/account/create/';
			$param_array['currency'] = $arr['CurrencyType'];
			$param_array['externalPlayerId'] = $post_data['username'];
			
		}
		else if($method == 'LoginGame')
		{
			$url .= '/game/start/';
			$param_array['externalPlayerId'] = $post_data['username'];
			if($post_data['game_type_code'] == GAME_LIVE_CASINO)
			{
				$param_array['gameId'] = "101";
			}else{
				$param_array['gameId'] = $post_data['game_code'];
			}
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
			if($arr['CurrencyType'] == "IDR2"){
				$amount = bcdiv($post_data['amount']/1000,1,2);
			}else{
				$amount = $post_data['amount'];
			}
			$requestOrderIDAlias = $post_data['order_id'];
			$url .= '/balance/transfer/';
			$param_array['amount'] = $amount;
			$param_array['externalPlayerId'] = $post_data['username'];
			$param_array['externalTransactionId'] = $requestOrderIDAlias;
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
		$curl_array = $response['curl'];
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if($result_array['error'] == '0')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $post_data['username'];
					$output['gamePassword'] = $post_data['password'];
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
					if($arr['CurrencyType'] == "IDR2"){
						$output['result'] = bcdiv($result_array['balance']*1000, 1, 2);
					}else{
						$output['result'] = bcdiv($result_array['balance'], 1, 2);
					}
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
					if($arr['CurrencyType'] == "IDR2"){
						$output['result'] = bcdiv($result_array['balance']*1000, 1, 2);
					}else{
						$output['result'] = bcdiv($result_array['balance'], 1, 2);
					}
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
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	public function ygg_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL){
	    $arr = json_decode($api_data, TRUE);
	    
	    //Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		$result_array = array();
		$param_array = array();
		
		if($method == 'CreateMember')
		{
		    $url .= "/att/loginGame";
    		    
		    $lang = 'en';
		
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $lang = 'zh_hans'; break;
				case LANG_ZH_HK: $lang = 'zh_hant'; break;
				case LANG_ZH_TW: $lang = 'zh_hant'; break;
				case LANG_ID: $lang = 'id'; break;
				case LANG_TH: $lang = 'th'; break;
				case LANG_VI: $lang = 'vi'; break;
				case LANG_JA: $lang = 'ja'; break;
				case LANG_KO: $lang = 'ko'; break;
			}
			
		    
		    $param_array = array(
		        'loginname' => $post_data['username'],
		        'topOrg' => $arr['TopOrg'],
		        'org' => $arr['Org'],
		        'gameId' => 7310,
		        'currency' => $arr['Currency'],
		        'language' => $lang,
		        'channel' => (($post_data['device'] == PLATFORM_WEB) ? "pc" : "pc"),
		        'countryCode' => $arr['CountryCode'],
		        'sign' => md5($post_data['username'].$arr['Key'])
		    );
		    
		    if( ! empty($post_data['return_url']))
			{
				$param_array['returnUrl'] = $post_data['return_url'];
			}
		}
		else if($method == 'LoginGame')
		{
		    $url .= "/att/loginGame";
    		    
		    $lang = 'en';
		
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $lang = 'zh_hans'; break;
				case LANG_ZH_HK: $lang = 'zh_hant'; break;
				case LANG_ZH_TW: $lang = 'zh_hant'; break;
				case LANG_ID: $lang = 'id'; break;
				case LANG_TH: $lang = 'th'; break;
				case LANG_VI: $lang = 'vi'; break;
				case LANG_JA: $lang = 'ja'; break;
				case LANG_KO: $lang = 'ko'; break;
			}
			
		    
		    $param_array = array(
		        'loginname' => $post_data['game_id'],
		        'topOrg' => $arr['TopOrg'],
		        'org' => $arr['Org'],
		        'gameId' => $post_data['game_code'],
		        'currency' => $arr['Currency'],
		        'language' => $lang,
		        'channel' => (($post_data['device'] == PLATFORM_WEB) ? "pc" : "mobile"),
		        'countryCode' => $arr['CountryCode'],
		        'sign' => md5($post_data['game_id'].$arr['Key'])
		    );
		    
		    if( ! empty($post_data['return_url']))
			{
				$param_array['returnUrl'] = $post_data['return_url'];
			}
		}
		else if($method == 'GetBalance')
		{
		    $url .= "/att/getBalance";
		    $param_array = array(
		        'loginname' => $post_data['game_id'],
		        'topOrg' => $arr['TopOrg'],
		        'org' => $arr['Org'],
		        'currency' => $arr['Currency'],
		        'sign' => md5($post_data['game_id'].$arr['Key'])
		    );
		}
		else if($method == 'ChangeBalance')
		{
		    $requestOrderIDAlias = $post_data['order_id'];
		    if($post_data['amount'] > 0) 
			{
			    $url .= "/att/credit";
			    $amount = bcdiv($post_data['amount'],1,0);
				
			    $param_array = array(
                    'loginname' => $post_data['game_id'],
    		        'topOrg' => $arr['TopOrg'],
    		        'org' => $arr['Org'],
    		        'amount' => $amount,
    		        'billno' => $requestOrderIDAlias,
    		        'currency' => $arr['Currency'],
    		        'sign' => md5($post_data['game_id'].$arr['Key'])
        		);
			}else{
			    $url .= "/att/withdraw";
			    $amount = bcdiv(($post_data['amount'] * -1),1,0);
			    
			    $param_array = array(
                    'loginname' => $post_data['game_id'],
    		        'topOrg' => $arr['TopOrg'],
    		        'org' => $arr['Org'],
    		        'amount' => $amount,
    		        'billno' => $requestOrderIDAlias,
    		        'currency' => $arr['Currency'],
    		        'sign' => md5($post_data['game_id'].$arr['Key'])
        		);
			}
		}
		else if($method == 'LogoutGame')
		{
		    $url .= "/att/kickPlayer";
		    $param_array = array(
		        'loginname' => $post_data['game_id'],
		        'topOrg' => $arr['TopOrg'],
		        'org' => $arr['Org'],
		        'currency' => $arr['Currency'],
		        'sign' => md5($post_data['game_id'].$arr['Key'])
		    );
		}
		
		$response = $this->curl_post($url, $param_array);
		if($response['code'] == '0')
		{
		    $result_array = json_decode($response['data'], TRUE);
		    if($method == 'CreateMember')
		    {
		        if(isset($result_array['code']) && $result_array['code'] == '0')
			    {
                    $output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $param_array['loginname'];
					$output['gamePassword'] = $post_data['password'];				        
			    }   
		    }
    		else if($method == 'LoginGame')
    		{
    		    if(isset($result_array['code']) && $result_array['code'] == '0')
			    {
                    $output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $result_array['data'];
			    }
    		}
    		else if($method == 'GetBalance')
    		{
    		    if(isset($result_array['code']) && $result_array['code'] == '0')
			    {
                    $output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = bcdiv($result_array['data'],1,2);
			    }
    		}
    		else if($method == 'ChangeBalance')
    		{
    		    if(isset($result_array['code']) && $result_array['code'] == '0')
			    {
                    $output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
			    }
			    else if(isset($result_array['code']) && $result_array['code'] == '3')
			    {
			        $output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
			        $output['errorMessage'] = $this->lang->line('error_amount_insufficient');
			    }
			    else if(isset($result_array['code']) && $result_array['code'] == '12')
			    {
			        $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
			    }else{
			        $output['errorCode'] = ERROR_OVERTIME;
				    $output['errorMessage'] = $this->lang->line('error_overtime');
			    }
    		}
    		else if($method == 'LogoutGame')
    		{
    		    if(isset($result_array['code']) && $result_array['code'] == '0')
			    {
                    $output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
			    }
    		}
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}

		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
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
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
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
			$requestOrderIDAlias = $post_data['order_id'];
			$param_array['currency'] = $arr['CurrencyType'];
			
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
			$curl_array = $response['curl'];
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['code']) && $result_array['code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['gameID'] = $param_array['acctId'];
						$output['gamePassword'] = $post_data['password'];
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
			}else{
				if($response['code'] == '404'){
					$output['errorCode'] = ERROR_OVERTIME;
					$output['errorMessage'] = $this->lang->line('error_overtime');
				}
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}

		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
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
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
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
			$param_array['CurrencyCode'] = $arr['CurrencyType'];
		}
		else if($method == 'GetBalance')
		{
			$url .= '/QueryPlayer';
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$requestOrderIDAlias = $post_data['order_id'];
				$url .= '/DepositPlayerMoney';
				$param_array['CurrencyCode'] = $arr['CurrencyType'];
				$param_array['Amount'] = $post_data['amount'];
				$param_array['RequestId'] = $requestOrderIDAlias;
			}
			else
			{
				$requestOrderIDAlias = $post_data['order_id'];
				$url .= '/WithdrawPlayerMoney';
				$param_array['CurrencyCode'] = $arr['CurrencyType'];
				$param_array['Amount'] = $post_data['amount'];
				$param_array['WithdrawAll'] = FALSE;
				$param_array['RequestId'] = $requestOrderIDAlias;
			}
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/LogOutPlayer';
		}
		
		//Get response from curl
		$response = $this->curl_json($url, $param_array);
		$curl_array = $response['curl'];
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['Token']) && ! empty($result_array['Token']))
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $post_data['username'];
					$output['gamePassword'] = $post_data['password'];
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
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
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
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
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
				$requestOrderIDAlias = $post_data['order_id'];
				$url .= '/api/v2/deposit/';
				$param_array['member'] = $post_data['username'];
				$param_array['operator_id'] = $arr['OperatorID'];
				$param_array['amount'] = $post_data['amount'];
				$param_array['reference_no'] = $requestOrderIDAlias;
			}
			else
			{
				$requestOrderIDAlias = $post_data['order_id'];
				$url .= '/api/v2/withdraw/';
				$param_array['member'] = $post_data['username'];
				$param_array['operator_id'] = $arr['OperatorID'];
				$param_array['amount'] = bcdiv(($post_data['amount'] * -1), 1, 2);
				$param_array['reference_no'] = $requestOrderIDAlias;
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
				$curl_array = $response['curl'];
			}
			else
			{
				$response = $this->curl_post($url, $param_array, "Authorization: Token " . $arr['PrivateToken']);
				$curl_array = $response['curl'];
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
						$output['gameID'] = $post_data['username'];
						$output['gamePassword'] = $post_data['password'];
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
			}else{
				if($response['code'] == '404'){
					$output['errorCode'] = ERROR_OVERTIME;
					$output['errorMessage'] = $this->lang->line('error_overtime');
				}
			}
		}	

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	private function jdb_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL){
		$arr = json_decode($api_data, TRUE);
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);

		$url = $arr['APIUrl'];
		//Prepare post data
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		$param_array = array();
		$amount = 0;



		$param_array = array(
			'uid' => $post_data['username'],
		);

		if($method == 'LoginGame'){

			$language = 'en';
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN:
				case LANG_ZH_HK:
				case LANG_ZH_TW: $language = 'cn'; break;
				case LANG_TH: $language = 'th'; break;
				case LANG_VI: $language = 'vn'; break;
			}

			$gType = 0;
			switch($post_data['game_type_code'])
			{
				case GAME_FISHING: $gType = 7; break;
				case GAME_OTHERS: $gType = 9; break;
				case GAME_LOTTERY: $gType = 12; break;
				case GAME_BOARD_GAME: $gType = 18; break;
			}

			$mobile = true;
			if($post_data['device'] == PLATFORM_WEB)
			{
				$mobile = false;
			}
			$param_array['action'] = 11;
			$param_array['lang'] = $language;
			$param_array['gType'] = $gType;
			$param_array['mType'] = $post_data['game_code'];
			$param_array['windowMode'] = 2;
			$param_array['isAPP'] = $mobile;
			$param_array['lobbyURL'] = $post_data['return_url'];
			$param_array['moreGame'] = $arr['MoreGame'];
			$param_array['mute'] = $arr['Mute'];
			$param_array['isShowDollarSign'] = $arr['IsShowDollarSign'];
		}
		else if($method == 'CreateMember')
		{
			$param_array['action'] = 12;
			$param_array['parent'] = $arr['Parent'];
			$param_array['name'] = $post_data['username'];
		}
		else if($method == 'GetBalance')
		{
			$param_array['action'] = 15;
			$param_array['parent'] = $arr['Parent'];
		}
		else if($method == 'ChangeBalance')
		{
			$requestOrderIDAlias = $post_data['order_id'];
			if($arr['CurrencyType'] == "IDR"){
				$amount = bcdiv(($post_data['amount'] / 1000),1,2);
			}else{
				$amount = $post_data['amount'];
			}

			$param_array['action'] = 19;
			$param_array['parent'] = $arr['Parent'];
			$param_array['serialNo'] = $requestOrderIDAlias;
			$param_array['allCashOutFlag'] = 0;
			$param_array['amount'] = $amount;
		}
		else if($method == 'LogoutGame')
		{
			$param_array['action'] = 17;
			$param_array['parent'] = $arr['Parent'];
		}
		$params['dc'] = $arr['DC'];

		
		$this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$aes->set_mode(MCRYPT_MODE_CBC);
		$aes->set_iv($arr['IVkey']);
		$aes->set_key($arr['Deskey']);
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
		$param_array['ts'] = $timestamp;
		$str = json_encode($param_array);
		$params['x'] = $aes->encrypt($str);
		$param_array['request']['dc'] = $params['dc'];
		$param_array['request']['x'] = $params['x'];
		$response = $this->curl_post($url, $params);
		
		$curl_array = $response['curl'];
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			if($method == 'LoginGame'){
				if(isset($result_array['status']) && $result_array['status'] == '0000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $result_array['path'];
				}
				else if(isset($result_array['status']) && ($result_array['status'] == '7603' || $result_array['status'] == '7501'))
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
			else if($method == 'CreateMember')
			{
				if(isset($result_array['status']) && $result_array['status'] == '0000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $post_data['username'];
					$output['gamePassword'] = $post_data['password'];
				}
				else if(isset($result_array['status']) && $result_array['status'] == '7602')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
					$output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
			}
			else if($method == 'GetBalance')
			{
				if(isset($result_array['status']) && $result_array['status'] == '0000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');

					if($arr['CurrencyType'] == "IDR"){
						$output['result'] = bcdiv(($result_array['data'][0]['balance'] * 1000), 1, 2);
					}else{
						$output['result'] = bcdiv(($result_array['data'][0]['balance']), 1, 2);
					}
				}
				else if(isset($result_array['status']) && ($result_array['status'] == '7603' || $result_array['status'] == '7501'))
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
					if($arr['CurrencyType'] == "IDR"){
						$output['result'] = bcdiv(($result_array['userBalance'] * 1000), 1, 2);
					}else{
						$output['result'] = bcdiv(($result_array['userBalance']), 1, 2);
					}
				}
				else if(isset($result_array['status']) && ($result_array['status'] == '7603' || $result_array['status'] == '7501'))
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['status']) && ($result_array['status'] == '6006'))
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
				else if(isset($result_array['status']) && ($result_array['status'] == '7603' || $result_array['status'] == '7501'))
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}
		else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "Channel":"", "PrivateKey":""}
	private function cq9_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		$nonce_str = date('YmdHis') . rand(100000, 999999);
		$result_array = array();
		
		if($method == 'CreateMember')
		{
			$sign = strtoupper(md5($nonce_str . $post_data['username'] . $arr['PrivateKey']));
			$url .= '/ChannelApi/API/' . $arr['Channel'] . '/CreateUser?username=' . $post_data['username'] . '&nonce_str=' . $nonce_str . '&sign=' . $sign;
		}
		else if($method == 'LoginGame')
		{
			$sign = strtoupper(md5($nonce_str . $post_data['username'] . $arr['PrivateKey']));
			$url .= '/ChannelApi/API/' . $arr['Channel'] . '/LoginWithChannel?username=' . $post_data['username'] . '&nonce_str=' . $nonce_str . '&sign=' . $sign;
		}
		else if($method == 'GetBalance')
		{
			$sign = strtoupper(md5($nonce_str . $post_data['username'] . $arr['PrivateKey']));
			$url .= '/ChannelApi/API/' . $arr['Channel'] . '/GetBalance?username=' . $post_data['username'] . '&nonce_str=' . $nonce_str . '&sign=' . $sign;
		}
		else if($method == 'ChangeBalance')
		{
			$requestOrderIDAlias = substr($post_data['order_id'], 0, 30);
			$serial = $requestOrderIDAlias;
			
			if($post_data['amount'] > 0) 
			{
				$amount = $post_data['amount'];
				$sign = strtoupper(md5($amount . $nonce_str . $serial . $post_data['username'] . $arr['PrivateKey']));
				$function_method = 'Deposit';
			}
			else
			{
				$amount = bcdiv(($post_data['amount'] * -1), 1, 2);
				$sign = strtoupper(md5($amount . $nonce_str . $serial . $post_data['username'] . $arr['PrivateKey']));
				$function_method = 'Withdraw';
			}
			
			$url .= '/ChannelApi/API/' . $arr['Channel'] . '/' . $function_method . '?username=' . $post_data['username'] . '&amount=' . $amount . '&serial=' . $serial . '&nonce_str=' . $nonce_str . '&sign=' . $sign;
		}
		
		if($method == 'LogoutGame')
		{
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
		}
		else
		{
			//Get response from curl
			$response = $this->curl_get($url);
			$curl_array = $response['curl'];
			if($method == 'ChangeBalance')
			{
				$output['orderID'] = $requestOrderID;
				$output['orderIDAlias'] = $requestOrderIDAlias;
			}
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['state']) && $result_array['state'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['gameID'] = $post_data['username'];
						$output['gamePassword'] = $post_data['password'];
					}
					else if(isset($result_array['state']) && $result_array['state'] == '602')
					{
						$output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
				}
				else if($method == 'LoginGame')
				{
					if(isset($result_array['state']) && $result_array['state'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = $result_array['value'];
					}
					else if(isset($result_array['state']) && $result_array['state'] == '100000')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['state']) && $result_array['state'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['value']['balance'], 1, 2);
					}
					else if(isset($result_array['state']) && $result_array['state'] == '100000')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['state']) && $result_array['state'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['value']['balance'], 1, 2);
					}
					else if(isset($result_array['state']) && $result_array['state'] == '100000')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['state']) && $result_array['state'] == '624')
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
			}else{
				if($response['code'] == '404'){
					$output['errorCode'] = ERROR_OVERTIME;
					$output['errorMessage'] = $this->lang->line('error_overtime');
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $url, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	private function cq92_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
					'errorCode' => ERROR_SYSTEM_ERROR, 
					'errorMessage' => $this->lang->line('error_system_error'),
				);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		$nonce_str = date('YmdHis') . rand(100000, 999999);
		$result_array = array();
		
		if($method == 'CreateMember')
		{
			$sign = strtoupper(md5($nonce_str . $post_data['username'] . $arr['PrivateKey']));
			$url .= '/ChannelApi/API/' . $arr['Channel'] . '/CreateUser?username=' . $post_data['username'];
		}
		else if($method == 'LoginGame')
		{
			$sign = strtoupper(md5($nonce_str . $post_data['username'] . $arr['PrivateKey']));
			$url .= '/ChannelApi/API/' . $arr['Channel'] . '/LoginWithChannel?username=' . $post_data['username'];
		}
		else if($method == 'GetBalance')
		{
			$sign = strtoupper(md5($nonce_str . $post_data['username'] . $arr['PrivateKey']));
			$url .= '/ChannelApi/API/' . $arr['Channel'] . '/GetBalance?username=' . $post_data['username'];
		}
		else if($method == 'ChangeBalance')
		{
			$requestOrderIDAlias = substr($post_data['order_id'], 0, 30);
			$serial = $requestOrderIDAlias;
			
			if($post_data['amount'] > 0) 
			{
				$amount = $post_data['amount'];
				$sign = strtoupper(md5($amount . $nonce_str . $serial . $post_data['username'] . $arr['PrivateKey']));
				$function_method = 'Deposit';
			}
			else
			{
				$amount = bcdiv(($post_data['amount'] * -1), 1, 2);
				$sign = strtoupper(md5($amount . $nonce_str . $serial . $post_data['username'] . $arr['PrivateKey']));
				$function_method = 'Withdraw';
			}
			
			$url .= '/ChannelApi/API/' . $arr['Channel'] . '/' . $function_method . '?username=' . $post_data['username'] . '&amount=' . $amount . '&serial=' . $serial;
		}
		
		if($method == 'LogoutGame')
		{
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
		}
		else
		{
			//Get response from curl
			$response = $this->curl_get($url,"Authorization: Bearer ".$arr['Token']);
			$curl_array = $response['curl'];
			if($method == 'ChangeBalance')
			{
				$output['orderID'] = $requestOrderID;
				$output['orderIDAlias'] = $requestOrderIDAlias;
			}
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				
				if($method == 'CreateMember')
				{
					if(isset($result_array['state']) && $result_array['state'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['gameID'] = $post_data['username'];
						$output['gamePassword'] = $post_data['password'];
					}
					else if(isset($result_array['state']) && $result_array['state'] == '602')
					{
						$output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
				}
				else if($method == 'LoginGame')
				{
					if(isset($result_array['state']) && $result_array['state'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = $result_array['value'];
					}
					else if(isset($result_array['state']) && $result_array['state'] == '100000')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['state']) && $result_array['state'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['value']['balance'], 1, 2);
					}
					else if(isset($result_array['state']) && $result_array['state'] == '100000')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['state']) && $result_array['state'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['result'] = bcdiv($result_array['value']['balance'], 1, 2);
					}
					else if(isset($result_array['state']) && $result_array['state'] == '100000')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['state']) && $result_array['state'] == '624')
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
				}
			}else{
				if($response['code'] == '404'){
					$output['errorCode'] = ERROR_OVERTIME;
					$output['errorMessage'] = $this->lang->line('error_overtime');
				}
			}
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $url, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	private function cq93_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
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
			'account' => $post_data['username'],
		);
		$curl_array = array();
		$response = array();
		$token = "";
		if($method == 'CreateMember')
		{
			$url .= '/gameboy/player';
			
			$param_array['password'] = $post_data['password'];
			$param_array['nickname'] = $post_data['username'];
		}
		else if($method == 'LoginGame')
		{
			$url .= '/gameboy/player/gamelink';

			$gameplat = "MOBILE";
			if($post_data['device'] == PLATFORM_WEB)
			{
				$gameplat = "WEB";
			}

			$language = 'en';
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN:
				case LANG_ZH_HK:
				case LANG_ZH_TW: $language = 'zh-cn'; break;
				case LANG_TH: $language = 'th'; break;
				case LANG_KO: $language = 'ko'; break;
				case LANG_ID: $language = 'id'; break;
				case LANG_VI: $language = 'vn'; break;
				case LANG_JA: $language = 'ja'; break;
			}

			$param_array['gamehall'] = $arr['GameHall'];
			$param_array['gamecode'] = $post_data['game_code'];
			$param_array['gameplat'] = $gameplat;
			$param_array['lang'] = $language;
			$param_array['password'] = $post_data['password'];
			$param_array['detect'] = $arr['Detect'];

		}
		else if($method == 'GetBalance')
		{
			$url .= '/gameboy/player/balance/'.$post_data['username'];
		}
		else if($method == 'ChangeBalance')
		{
			$requestOrderIDAlias = $post_data['order_id'];
			$param_array['mtcode'] = $requestOrderIDAlias;
			if($post_data['amount'] > 0) 
			{
				$url .= '/gameboy/player/deposit';
				if($arr['CurrencyType'] == "K"){
					$amount = ($post_data['amount'] / 1000);
				}else{
					$amount = $post_data['amount'];
				}

				$param_array['amount'] = $amount;
			}
			else
			{
				$url .= '/gameboy/player/withdraw';
				if($arr['CurrencyType'] == "K"){
					$amount = bcdiv(($post_data['amount'] * -1 / 1000), 1, 2);
				}else{
					$amount = bcdiv(($post_data['amount'] * -1), 1, 2);
				}

				$param_array['amount'] = $amount;
			}
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/gameboy/player/logout';
		}
		if($method == 'LoginGame'){
			$token_url = $arr['APIUrl'] . '/gameboy/player/login';
			$token_param_array = array(
				'account' => $post_data['username'],
				'password' => $post_data['password'],
			);
			$token_response = $this->curl_post($token_url, $token_param_array,"Authorization: ".$arr['Key']);
			if($token_response['code'] == '0')
			{
				$token_result_array = json_decode($token_response['data'], TRUE);
				$token_curl_array = $response['curl'];
				$this->game_model->insert_api_log($post_data['provider_code'], "GetToken", $output, $token_param_array, $token_result_array,$token_curl_array);
				if(isset($token_result_array['status']['code']) && $token_result_array['status']['code'] == '0')
				{
					$param_array['usertoken'] = $token_result_array['data']['usertoken'];
					$response = $this->curl_post($url, $param_array,"Authorization: ".$arr['Key']);
					$curl_array = $response['curl'];
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(isset($result_array['status']['code']) && $result_array['status']['code'] == '0')
						{
							$output['errorCode'] = ERROR_SUCCESS;
							$output['errorMessage'] = $this->lang->line('error_success');
							$output['result'] = $result_array['data']['url'];
							$output['gameID'] = $param_array['account'];
							$output['gamePassword'] = $param_array['password'];
						}
						else if(isset($result_array['status']['code']) && $result_array['status']['code'] == '23')
						{
							$output['errorCode'] = ERROR_USERNAME_EXITS;
							$output['errorMessage'] = $this->lang->line('error_username_already_exits');
						}				
					}
				}
				else if(isset($token_result_array['status']['code']) && $token_result_array['status']['code'] == '23')
				{
					$output['errorCode'] = ERROR_GAME_MAINTENANCE;
					$output['errorMessage'] = $this->lang->line('error_game_maintenance');
				}
			}
		}else{
			if($method == 'GetBalance'){
				$response = $this->curl_get($url,"Authorization: ".$arr['Key']);
				$param_array['url'] = $url;
			    $curl_array = $response['curl'];
			}else{
				$response = $this->curl_post($url, $param_array,"Authorization: ".$arr['Key']);
				$param_array['url'] = $url;
				$curl_array = $response['curl'];
			}
			if($response['code'] == '0')
			{
				$result_array = json_decode($response['data'], TRUE);
				if($method == 'CreateMember')
				{
					if(isset($result_array['status']['code']) && $result_array['status']['code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$output['gameID'] = $param_array['account'];
						$output['gamePassword'] = $param_array['password'];
					}
					else if(isset($result_array['status']['code']) && $result_array['status']['code'] == '6')
					{
						$output['errorCode'] = ERROR_USERNAME_EXITS;
						$output['errorMessage'] = $this->lang->line('error_username_already_exits');
					}
					else if(isset($result_array['status']['code']) && $result_array['status']['code'] == '23')
					{
						$output['errorCode'] = ERROR_GAME_MAINTENANCE;
						$output['errorMessage'] = $this->lang->line('error_game_maintenance');
					}
				}
				else if($method == 'GetBalance')
				{
					if(isset($result_array['status']['code']) && $result_array['status']['code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						if($arr['CurrencyType'] == "K"){
							$output['result'] = bcdiv($result_array['data']['balance'] * 1000, 1, 2);
						}else{
							$output['result'] = bcdiv($result_array['data']['balance'], 1, 2);
						}
					}
					else if(isset($result_array['status']['code']) && $result_array['status']['code'] == '2')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['status']['code']) && $result_array['status']['code'] == '23')
					{
						$output['errorCode'] = ERROR_GAME_MAINTENANCE;
						$output['errorMessage'] = $this->lang->line('error_game_maintenance');
					}
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['status']['code']) && $result_array['status']['code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						if($arr['CurrencyType'] == "K"){
							$output['result'] = bcdiv($result_array['data']['balance'] * 1000, 1, 2);
						}else{
							$output['result'] = bcdiv($result_array['data']['balance'], 1, 2);
						}
					}
					else if(isset($result_array['status']['code']) && $result_array['status']['code'] == '1')
					{
						$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
						$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
					}
					else if(isset($result_array['status']['code']) && $result_array['status']['code'] == '2')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['status']['code']) && $result_array['status']['code'] == '23')
					{
						$output['errorCode'] = ERROR_GAME_MAINTENANCE;
						$output['errorMessage'] = $this->lang->line('error_game_maintenance');
					}
				}
				else if($method == 'LogoutGame')
				{
					if(isset($result_array['status']['code']) && $result_array['status']['code'] == '0')
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}
					else if(isset($result_array['status']['code']) && $result_array['status']['code'] == '2')
					{
						$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
						$output['errorMessage'] = $this->lang->line('error_username_not_found');
					}
					else if(isset($result_array['status']['code']) && $result_array['status']['code'] == '23')
					{
						$output['errorCode'] = ERROR_GAME_MAINTENANCE;
						$output['errorMessage'] = $this->lang->line('error_game_maintenance');
					}
				}
			}
			else{
				if($response['code'] == '404'){
					$output['errorCode'] = ERROR_OVERTIME;
					$output['errorMessage'] = $this->lang->line('error_overtime');
				}
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array,$curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	private function pgs2_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
		$arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);

		$currency_one = array("BIF", "COP", "IDR", "IQD", "IRR", "KHR", "KRW", "LBP", "MMK", "MNT", "PYG", "TZS", "UGX", "VND");
		
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$amount = 0;
		$curl_array = array();
		$param_array = array();	
		$this->load->library('guid');
        $guid = $this->guid->get_token();

        if($method == 'CreateMember')
		{
			$url .= 'Player/v1/Create?trace_id='.$guid;
			$param_array = array(
	            "operator_token" => $arr['OperatorToken'],
	            "secret_key" => $arr['SecretKey'],
	            "player_name" => $post_data['username'],
	            "nickname" => $post_data['username'],
	            "currency" => $arr['Currency'],
	        );
		}
		else if($method == 'GetBalance')
		{
			$url .= 'Cash/v3/GetPlayerWallet?trace_id='.$guid;
			$param_array = array(
	            "operator_token" => $arr['OperatorToken'],
	            "secret_key" => $arr['SecretKey'],
	            "player_name" => $post_data['username'],
	        );
		}
		else if($method == 'ChangeBalance')
		{
			$requestOrderIDAlias = $post_data['order_id'];

			if($post_data['amount'] > 0) 
			{
			    if(in_array($arr['Currency'],$currency_one)){
					$amount = bcdiv($post_data['amount'] / 1000,1,2);
				}else{
					$amount = $post_data['amount'];
				}
			}
			else
			{
				if(in_array($arr['Currency'],$currency_one)){
					$amount = bcdiv(($post_data['amount'] * -1 / 1000), 1, 2);
				}else{
					$amount = bcdiv(($post_data['amount'] * -1), 1, 2);
				}
			}
			
			if($post_data['amount'] > 0) 
			{
				$url .= 'Cash/v3/TransferIn?trace_id='.$guid;
				$param_array = array(
		            "operator_token" => $arr['OperatorToken'],
		            "secret_key" => $arr['SecretKey'],
		            "player_name" => $post_data['username'],
		            'amount' => $amount,
		            "transfer_reference" => $requestOrderIDAlias,
		            "currency" => $arr['Currency'],
		        );
			}
			else
			{
				$url .= 'Cash/v3/TransferOut?trace_id='.$guid;
				$param_array = array(
		            "operator_token" => $arr['OperatorToken'],
		            "secret_key" => $arr['SecretKey'],
		            "player_name" => $post_data['username'],
		            'amount' => $amount,
		            "transfer_reference" => $requestOrderIDAlias,
		            "currency" => $arr['Currency'],
		        );
			}
		}
		else if($method == 'LogoutGame')
		{
			$url .= 'Player/v1/Kick?trace_id='.$guid;
			$param_array = array(
	            "operator_token" => $arr['OperatorToken'],
	            "secret_key" => $arr['SecretKey'],
	            "player_name" => $post_data['username'],
	        );
		}

		if($method == 'LoginGame')
		{
			$url = $arr['ForwardUrl']."/".$post_data['game_code']."/index.html";
			
			$lang = 'en';
			
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $lang = 'zh'; break;
				case LANG_ZH_HK: $lang = 'zh'; break;
				case LANG_ZH_TW: $lang = 'zh'; break;
				case LANG_ID: $lang = 'id'; break;
				case LANG_TH: $lang = 'th'; break;
				case LANG_VI: $lang = 'vi'; break;
				case LANG_JA: $lang = 'ja'; break;
				case LANG_KO: $lang = 'ko'; break;
			}
	        if( ! empty($post_data['return_url']))
			{
				$param_array['f'] = $post_data['return_url'];
			}


			if($post_data['is_demo'] == STATUS_YES)
			{
				
			}
			else
			{
				$player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['username']);
				if( ! empty($player_acc_data))
				{
					$btt = "1";
					$partner_member_token = UrlEncode(md5($post_data['username'].time().rand(100,999)));
					$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['username'], $partner_member_token);
					
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['result'] = $arr['ForwardUrl'].$post_data['game_code']."/index.html?ot=".$arr['OperatorToken']."&ops=".$partner_member_token."&btt=".$btt."&l=".$lang;
					if( ! empty($post_data['return_url']))
					{
						$output['result'] .= '&f='.$post_data['return_url'];
					}
				}
				else
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
					$output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
			}
		}else{
			$response = $this->curl_post($url, $param_array);
			$param_array['url'] = $url;
			$curl_array = $response['curl'];
			if($response['code'] == '0')
			{
			    $result_array = json_decode($response['data'], TRUE);
			   	if($method == 'CreateMember')
				{
					if(isset($result_array['data']['action_result']) && $result_array['data']['action_result'] == '1'){
				        $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						$this->player_model->add_player_game_token($post_data['provider_code'], $post_data['username']);
						$output['gameID'] = $param_array['player_name'];
						$output['gamePassword'] = $post_data['password'];
				    }else{
				    	if(isset($result_array['error']['code']) && $result_array['error']['code'] == '1305'){
				    		$output['errorCode'] = ERROR_USERNAME_EXITS;
							$output['errorMessage'] = $this->lang->line('error_username_already_exits');
				    	}
				    }
				}

				else if($method == 'GetBalance')
				{
					if(isset($result_array['data']['currencyCode'])){
				        $output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						if(in_array($arr['Currency'],$currency_one)){
							$output['result'] = bcdiv($result_array['data']['cashBalance']*1000, 1, 2);
						}else{
							$output['result'] = bcdiv($result_array['data']['cashBalance'], 1, 2);
						}
				    }else{
				    	if(isset($result_array['error']['code']) && $result_array['error']['code'] == '1305'){
				    		$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
							$output['errorMessage'] = $this->lang->line('error_username_not_found');
				    	}
				    }
				}
				else if($method == 'ChangeBalance')
				{
					if(isset($result_array['data']['transactionId']))
					{
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
						if(in_array($arr['Currency'],$currency_one))
						{
							$output['result'] = bcdiv($result_array['data']['balanceAmount'] * 1000, 1, 2);
						}else{
							$output['result'] = bcdiv($result_array['data']['balanceAmount'], 1, 2);
						}
					}else{
						$retrigger_api = FALSE;
						if(isset($result_array['error']['code']))
						{
							if($result_array['error']['code'] == '1305')
							{
								$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
								$output['errorMessage'] = $this->lang->line('error_username_not_found');
							}
							else if($result_array['error']['code'] == '3013')
							{
								$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
								$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
							}
							else if($result_array['error']['code'] == '3001')
							{

							}
							else if($result_array['error']['code'] == '3005')
							{
								
							}
							else if($result_array['error']['code'] == '3100')
							{
								$response = $this->curl_post($url, $param_array);

							}else{
								$retrigger_api = TRUE;	
							}
						}else{
							$retrigger_api = TRUE;
						}
					}

					if($retrigger_api){
						$guid = $this->guid->get_token();
						$verify_url = $arr['APIUrl'];
						$verify_url .= 'Cash/v3/GetSingleTransaction?trace_id='.$guid;
						
						$verify_param_array = array(
				            "operator_token" => $arr['OperatorToken'],
				            "secret_key" => $arr['SecretKey'],
				            "player_name" => $post_data['username'],
				            "transfer_reference" => $requestOrderIDAlias,
				        );
						$verify_result_array = array();
				        $verify_response = $this->curl_post($verify_url, $verify_param_array);
						$verify_curl_array = $verify_response['curl'];

						if($verify_response['code'] == '0')
						{
						    $verify_result_array = json_decode($verify_response['data'], TRUE);
						    if(isset($verify_result_array['data']['transactionId']))
							{
								$output['errorCode'] = ERROR_SUCCESS;
								$output['errorMessage'] = $this->lang->line('error_success');
								if(in_array($arr['Currency'],$currency_one))
								{
									$output['result'] = bcdiv($verify_result_array['data']['transactionTo'] * 1000, 1, 2);
								}else{
									$output['result'] = bcdiv($verify_result_array['data']['transactionTo'], 1, 2);
								}
							}else{
								$retrigger_api = FALSE;
								if(isset($verify_result_array['error']['code']))
								{
									if($verify_result_array['error']['code'] == '1305')
									{
										$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
										$output['errorMessage'] = $this->lang->line('error_username_not_found');
									}
									else if($verify_result_array['error']['code'] == '3040')
									{
										
									}
									else{
										$retrigger_api = TRUE;	
									}
								}else{
									$retrigger_api = TRUE;
								}
							}
							if($retrigger_api){
								$output['errorCode'] = ERROR_OVERTIME;
								$output['errorMessage'] = $this->lang->line('error_overtime');
							}
						}
						$this->db->trans_start();
						$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $verify_param_array, $verify_result_array, $verify_curl_array);
						$this->db->trans_complete();
					}
				}
				else if($method == 'LogoutGame')
				{
					if(isset($result_array['data']['action_result']) && $result_array['data']['action_result'] == '1'){
						$output['errorCode'] = ERROR_SUCCESS;
						$output['errorMessage'] = $this->lang->line('error_success');
					}else{
						if(isset($result_array['error']['code']) && $result_array['error']['code'] == '1305'){
				    		$output['errorCode'] = ERROR_USERNAME_EXITS;
							$output['errorMessage'] = $this->lang->line('error_username_already_exits');
				    	}
					}
				}
			}else{
				if($response['code'] == '404'){
					$output['errorCode'] = ERROR_OVERTIME;
					$output['errorMessage'] = $this->lang->line('error_overtime');
				}
			}
		}
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
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
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		$param_array = array(
							'cert' => $arr['Cert'],
							'agentId' => $arr['agentId']
						);
		
		if($method == 'CreateMember')
		{
			$url .= '/wallet/createMember';
			$param_array['userId'] = $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT);
			$param_array['userName'] = $post_data['username'];
			$param_array['currency'] = $arr['CurrencyType'];
			$param_array['betLimit'] = json_encode($arr['BetLimit'],true);
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
			$param_array['userId'] = $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT);
			if(isset($arr['BetLimit'][$arr['Platform']])){
			    $bet_limit_array = array(
		            $arr['Platform'] => $arr['BetLimit'][$arr['Platform']],
		        );
		        $param_array['betLimit'] = json_encode($bet_limit_array,true);
		    }
			$gameType = 'LIVE';
			switch($post_data['game_type_code'])
			{
				case GAME_SLOTS: $gameType = 'SLOT'; break;
				case GAME_FISHING: $gameType = 'FH'; break;
				case GAME_BOARD_GAME: $gameType = 'TABLE'; break;
				case GAME_ESPORTS: $gameType = 'ESPORTS'; break;
				case GAME_VIRTUAL_SPORTS: $gameType = 'VIRTUAL'; break;
				case GAME_LOTTERY: $gameType = 'LOTTO'; break;
				case GAME_OTHERS: $gameType = 'EGAME'; break;
			}

			$param_array['gameType'] = $gameType;
			$param_array['platform'] = $arr['Platform'];
			if(!empty($post_data['game_code'])){
				$param_array['gameCode'] = $post_data['game_code'];
			}
			$param_array['isMobileLogin'] = (($post_data['device'] == PLATFORM_WEB) ? 'false' : 'true');
			
			if( ! empty($post_data['return_url']))
			{
				$param_array['externalURL'] = $post_data['return_url'];
			}	
		}
		else if($method == 'GetBalance')
		{
			$url .= '/wallet/getBalance';
			$param_array['userIds'] = $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT);
		}
		else if($method == 'ChangeBalance')
		{
			if($post_data['amount'] > 0) 
			{
				$requestOrderIDAlias = $post_data['order_id'];
				$url .= '/wallet/deposit';
				$param_array['userId'] = $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT);
				$param_array['transferAmount'] = $post_data['amount'];
				$param_array['txCode'] = $requestOrderIDAlias;
			}
			else
			{
				$requestOrderIDAlias = $post_data['order_id'];
				$url .= '/wallet/withdraw';
				$param_array['userId'] = $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT);
				$param_array['txCode'] = $requestOrderIDAlias;
				$param_array['withdrawType'] = 0; //1: All, 0: Partial;
				$param_array['transferAmount'] = bcdiv(($post_data['amount'] * -1), 1, 2);
			}
		}
		else if($method == 'LogoutGame')
		{
			$url .= '/wallet/logout';
			$param_array['userIds'] = $arr['UPrefix'] . str_pad($post_data['player_id'],11,"0",STR_PAD_LEFT);
		}
		
		//Get response from curl
		$response = $this->curl_post($url, $param_array);
		$curl_array = $response['curl'];
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			
			if($method == 'CreateMember')
			{
				if(isset($result_array['status']) && $result_array['status'] == '0000')
				{
					$output['errorCode'] = ERROR_SUCCESS;
					$output['errorMessage'] = $this->lang->line('error_success');
					$output['gameID'] = $param_array['userId'];
					$output['gamePassword'] = $post_data['password'];
				}
				else if(isset($result_array['status']) && $result_array['status'] == '1001')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
					$output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
				
				else {
                  $output['errorCode'] = '801';
                  $output['errorMessage'] = $url;
                  $result_array = json_encode($response);
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
					if(isset($result_array['results'][0]['balance'])){
					    $output['result'] = bcdiv($result_array['results'][0]['balance'], 1, 2);
					}else{
					    $output['result'] = bcdiv($result_array['results'][$post_data['username']], 1, 2);   
					}
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
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"","AgentID":"","AgentKey":"","FrontKey":,"BackKey":,"CurrencyRate":}
	private function jili_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL){
	    $arr = json_decode($api_data, TRUE);
		
		//Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		$amount = 0;
		$param_array = array();
		$result_array = array();
		
		
	    if($method == 'CreateMember')
		{
		    $url .= "/CreateMember";
            $param_array = array(
                'Account' => $post_data['username'],
            );
		}
		else if($method == 'LoginGame')
		{
		    $url .= "/LoginWithoutRedirect";
                
            $lang = 'en-US';
		
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN: $lang = 'zh-CN'; break;
				case LANG_ZH_HK:
				case LANG_ZH_TW: $lang = 'zh-TW'; break;
				case LANG_ID: $lang = 'id-ID'; break;
				case LANG_TH: $lang = 'th-TH'; break;
				case LANG_VI: $lang = 'vi-VN'; break;
				case LANG_MY: $lang = 'my-MM'; break;
				case LANG_JA: $lang = 'ja-JP'; break;
			}
			
            $param_array = array(
                'Account' => $post_data['game_id'],
                'GameId' => $post_data['game_code'],
                'Lang' => $lang,
            );
		}
		else if($method == 'GetBalance')
        {
            $url .= "/GetMemberInfo";
            $param_array = array(
                'Accounts' => $post_data['game_id'],
            );
        }
		else if($method == 'ChangeBalance')
		{
		    $requestOrderIDAlias = $requestOrderID;
            $url .= "/ExchangeTransferByAgentId";
            
            if($post_data['amount'] > 0) 
			{
			    $param_array = array(
                    'Account' => $post_data['game_id'],
                    'TransactionId' => $requestOrderIDAlias,
                    'Amount' => bcdiv($post_data['amount'] / $arr['CurrencyRate'],1,2),
                    'TransferType' => 2,
                );
			}else{
                $param_array = array(
                    'Account' => $post_data['game_id'],
                    'TransactionId' => $requestOrderIDAlias,
                    'Amount' => bcdiv($post_data['amount'] * -1 / $arr['CurrencyRate'],1,2),
                    'TransferType' => 3,
                );
			}
		}
		else if($method == 'LogoutGame')
    	{
    	    $url .= "/KickMember";
            $param_array = array(
                'Account' => $post_data['game_id'],
            );   
    	}
		
		$keyP = urldecode(http_build_query($param_array, '', '&'))."&AgentId=".$arr['AgentID'];
        $keyA = rand(pow(10, $arr['FrontKey']-1), pow(10, $arr['FrontKey'])-1);
        $keyC = rand(pow(10, $arr['BackKey']-1), pow(10, $arr['BackKey'])-1);
        $keyT = date("ymj", strtotime('-12 hours', time()));
        $keyG = md5($keyT.$arr['AgentID'].$arr['AgentKey']);

        $key = $keyA . md5($keyP.$keyG).$keyC;
        $param_array['AgentId'] = $arr['AgentID'];
        $param_array['Key'] = $key;
        $response = $this->curl_post($url, $param_array);
		$curl_array = $response['curl'];
		if($response['code'] == '0')
    	{
    	    $result_array = json_decode($response['data'], TRUE);
    	    if($method == 'CreateMember')
		    {
		        if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0')
			    {
			        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    $output['gameID'] = $param_array['Account'];
				    $output['gamePassword'] = $post_data['password']; 
			    }
			    else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '101')
				{
					$output['errorCode'] = ERROR_USERNAME_EXITS;
				    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
		    }
    		else if($method == 'LoginGame')
    		{
    		    $result_array = json_decode($response['data'], TRUE);
    		    if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0')
			    {
			        $output['errorCode'] = ERROR_SUCCESS;
        		    $output['errorMessage'] = $this->lang->line('error_success');
        		    $output['result'] = $result_array['Data'];
    	    	}
				else if(isset($token_result_array['ErrorCode']) && $token_result_array['ErrorCode'] == '14')
				{
				    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
    		}
    		else if($method == 'GetBalance')
            {
                if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0')
			    {
			        if(isset($result_array['Data'][0]['Status']) && $result_array['Data'][0]['Status'] == "3"){
			            $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				        $output['errorMessage'] = $this->lang->line('error_username_not_found');
			        }else{
			            $output['errorCode'] = ERROR_SUCCESS;
				        $output['errorMessage'] = $this->lang->line('error_success');
				        $output['result'] = bcdiv($result_array['Data'][0]['Balance'] * $arr['CurrencyRate'], 1, 2);
			        }
			    }
            }
    		else if($method == 'ChangeBalance')
    		{
    		    if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0')
			    {
			        if(isset($result_array['Data'][0]['Status']) && $result_array['Data'][0]['Status'] == "3"){
			            $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				        $output['errorMessage'] = $this->lang->line('error_username_not_found');
			        }else{
			            $output['errorCode'] = ERROR_SUCCESS;
				        $output['errorMessage'] = $this->lang->line('error_success');
				        $output['result'] = bcdiv($result_array['Data']['CoinAfter'] * $arr['CurrencyRate'], 1, 2);
			        } 
			    }
			    else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '103')
				{
					$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
    				$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
				}
			    else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '101')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
				else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '10')
				{
    		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
			        $output['errorMessage'] = $this->lang->line('error_game_maintenance');
		        }
		        else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '3')
		        {
		            $output['errorCode'] = ERROR_OVERTIME;
				    $output['errorMessage'] = $this->lang->line('error_overtime');
		        }
		        else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '105')
		        {
		            $output['errorCode'] = ERROR_OVERTIME;
				    $output['errorMessage'] = $this->lang->line('error_overtime');
		        }
		        else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '106')
		        {
		            $output['errorCode'] = ERROR_OVERTIME;
				    $output['errorMessage'] = $this->lang->line('error_overtime');
		        }
                else if(isset($result_array['ErrorCode']))
                {
                    
                }
		        else{
		            $output['errorCode'] = ERROR_OVERTIME;
				    $output['errorMessage'] = $this->lang->line('error_overtime');
		        }
    		}
    		else if($method == 'LogoutGame')
    		{
    		    if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0')
			    {
			        $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
			    }
			    else if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '101')
				{
					$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				    $output['errorMessage'] = $this->lang->line('error_username_not_found');
				}
    		}
    	}else{
    	    if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
    	}
    	
    	if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array,$curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"", "CasinoKey":"", "APIToken":"", "CurrencyType":""}
	private function ne_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL) {
		$arr = json_decode($api_data, TRUE);
		
		#Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		$param_array = array();	
		$result_array = array();
		
		if($method == 'CreateMember' || $method == 'LoginGame') {
			$this->load->library('rng');			
			$url .= "/ua/v1/" . $arr['CasinoKey'] . "/" . $arr['APIToken'];
			
			$uuid = md5($arr['CasinoKey'] . $arr['APIToken'] . $post_data['username'] . time());
			$language = 'en';
			
			switch($post_data['syslang']) {
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
					"currency" => $arr['CurrencyType'],
					"session" => array (
						"id" => $this->rng->get_token(32),
						"ip" => $this->input->ip_address()
					)
				),
				"config" => array (
					"game" => array (
						"category" => "slots",
						"interface" => "view1",
						"table" => array (
							"id" => $post_data['game_code']
						)
					),
					"channel" => array (
						"wrapped" => false,
						"mobile" => (($post_data['device'] == PLATFORM_WEB) ? false : true)
					)
				)
			);
		}
		else if($method == 'GetBalance')
		{
			$url .= "/api/ecashier?cCode=RWA&ecID=" . $arr['CasinoKey'] . "&euID=" . $post_data['username'] . "&output=1"; 
		}
		else if($method == 'ChangeBalance')
		{
			$requestOrderIDAlias = $post_data['order_id'];
			if($post_data['amount'] > 0) 
			{
				$url .= "/api/ecashier?cCode=ECR&ecID=" . $arr['CasinoKey'] . "&euID=" . $post_data['username'] . "&amount=" . $post_data['amount'] . "&eTransID=" . $requestOrderIDAlias . "&createuser=N&output=1"; 
			}
			else
			{
				$url .= "/api/ecashier?cCode=EDB&ecID=" . $arr['CasinoKey'] . "&euID=" . $post_data['username'] . "&amount=" . bcdiv(($post_data['amount'] * -1), 1, 2) . "&eTransID=" . $requestOrderIDAlias . "&output=1";
			}
		}
		else if($method == 'LogoutGame')
		{
			$url .= "/api/external/kickPlayer/v1/" . $arr['CasinoKey'] . "/" . $arr['APIToken'];
			
			$uuid = md5($arr['CasinoKey'] . $arr['APIToken'] . $post_data['username'] . time());
			
			$param_array = array (
				"uuid" => $uuid,
				"playerLogin" => $post_data['game_id']
			);
		}
		
		//Get response from curl
		if($method == 'CreateMember' || $method == 'LoginGame' || $method == 'LogoutGame')
		{
			$response = $this->curl_json($url, $param_array);
			$curl_array = $response['curl'];
		}
		else
		{
			$response = $this->curl_get($url);
			$curl_array = $response['curl'];
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
					$output['gameID'] = $post_data['username'];
					$output['gamePassword'] = $post_data['password'];
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
			else if($method == 'LogoutGame')
			{
				$output['errorCode'] = ERROR_SUCCESS;
				$output['errorMessage'] = $this->lang->line('error_success');
			}
		}else{
			if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
		}	

		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array, $curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
	
	#{"APIUrl":"","HostID":""}
	private function ps_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL)
	{
	    //Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		$arr = json_decode($api_data, TRUE);
		//Prepare post data
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		$amount = 0;
		$param_array = array();
		$result_array = array();
		
		if($method != 'LogoutGame'){
    		if($method == 'LoginGame')
    		{
    		    $player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['game_id']);
    		    if(!empty($player_acc_data))
    			{
    			    $this->load->library('rng');
    				$partner_member_token = $post_data['provider_code'].$post_data['game_id'];//$this->rng->get_token(64);
    				$this->player_model->update_player_game_token($post_data['provider_code'], $post_data['game_id'], $partner_member_token);
    				
    				$lang = 'en-US';
    		
        			switch($post_data['syslang'])
        			{
        				case LANG_ZH_CN: $lang = 'zh-CN'; break;
        				case LANG_ZH_HK:
        				case LANG_ZH_TW: $lang = 'zh-TW'; break;
        				case LANG_TH: $lang = 'th-TH'; break;
        				case LANG_MS: $lang = 'ms-MY'; break;
        				case LANG_KO: $lang = 'ko-KR'; break;
        				case LANG_VI: $lang = 'vi-VN'; break;
        				case LANG_ID: $lang = 'id-ID'; break;
        				case LANG_JA: $lang = 'ja-JP'; break;
        			}
    				
    			    $param_array = array(
        		        'host_id' =>  $arr['HostID'],
        		        'game_id' => $post_data['game_code'],
        		        'lang' => $lang,
        		        'access_token' => $partner_member_token,
        		        'return_url' => "",
        		    );
    			    if( ! empty($post_data['return_url']))
        			{
        				$param_array['return_url'] = $post_data['return_url'];
        			}
        			$output['errorCode'] = ERROR_SUCCESS;
    			    $output['errorMessage'] = $this->lang->line('error_success');
    			    $output['result'] = $url .= "/launch/?".urldecode(http_build_query($param_array));
    			}else{
    			    $output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
    				$output['errorMessage'] = $this->lang->line('error_username_not_found');
    			}
    		}
    		else if($method == 'CreateMember')
    		{
    		    $url .= '/funds/createplayer/';
    		    $param_array = array(
    		        'host_id' =>  $arr['HostID'],
    		        'member_id' => $post_data['username'],
    		        'purpose' => 0,
    		    );
    		    
    		    $player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $param_array['member_id']);
    		    if(empty($player_acc_data))
				{
        		    $url .= "?".urldecode(http_build_query($param_array));
        		    $response = $this->curl_get($url);
            		$curl_array = $response['curl'];
            		if($response['code'] == '0')
            		{
            			$result_array = json_decode($response['data'], TRUE);
            			if(isset($result_array['status_code']) && $result_array['status_code'] == '0')
    				    {
    				        $this->player_model->add_player_game_token($post_data['provider_code'], $param_array['member_id']);
    				        $output['errorCode'] = ERROR_SUCCESS;
    					    $output['errorMessage'] = $this->lang->line('error_success');
    					    $output['gameID'] = $param_array['member_id'];
    					    $output['gamePassword'] = $post_data['password'];
    				    }else if(isset($result_array['status_code']) && $result_array['status_code'] == '11'){
            		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
    					    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
            		    }
            		}
				}else{
				    $output['errorCode'] = ERROR_USERNAME_EXITS;
				    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
				}
    		}
    		else
    		{
        		if($method == 'GetBalance')
                {
                    $url .= '/funds/getbalance/';
        		    $param_array = array(
        		        'host_id' =>  $arr['HostID'],
        		        'member_id' => $post_data['game_id'],
        		        'purpose' => 130,
        		    );
                }
        		else if($method == 'ChangeBalance')
        		{
        		    $requestOrderIDAlias = str_replace(array($post_data['username'], 'IN', 'OUT'), array('', '', ''), $post_data['order_id']).$post_data['player_id'];
        		    if($post_data['amount'] > 0) 
        			{
        			    $url .= "/funds/deposit/";
        			    $param_array = array(
        			        'host_id' =>  $arr['HostID'],
        			        'member_id' => $post_data['game_id'],
        			        'txn_id' => $requestOrderIDAlias,
        			        'amount' => bcdiv($post_data['amount'] * 100,1,0),
        			        'purpose' => 0,
        			    );
        			}else{
        			    $url .= "/funds/withdraw/";
        			    $param_array = array(
        			        'host_id' =>  $arr['HostID'],
        			        'member_id' => $post_data['game_id'],
        			        'txn_id' => $requestOrderIDAlias,
        			        'amount' => bcdiv($post_data['amount'] * -100,1,0),
        			        'purpose' => 0,
        			    );
        			}
        		}
        		
        		$url .= "?".urldecode(http_build_query($param_array));
    		    $response = $this->curl_get($url);
        		$curl_array = $response['curl'];
        		if($response['code'] == '0')
        		{
        			$result_array = json_decode($response['data'], TRUE);
        			if($method == 'GetBalance')
                    {
                        if(isset($result_array['status_code']) && $result_array['status_code'] == '0')
    				    {
    				        $output['errorCode'] = ERROR_SUCCESS;
    					    $output['errorMessage'] = $this->lang->line('error_success');
    					    $output['result'] = bcdiv($result_array['balance'] / 100,1,2);
    				    }else if(isset($result_array['status_code']) && $result_array['status_code'] == '11'){
            		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
    					    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
            		    }
                    }
        		    else if($method == 'ChangeBalance')
        		    {
        		        if(isset($result_array['status_code']) && $result_array['status_code'] == '0')
				        {
				            $output['errorCode'] = ERROR_SUCCESS;
    					    $output['errorMessage'] = $this->lang->line('error_success');
    					    $output['result'] = bcdiv($result_array['balance'] / 100,1,2);
    				    }else if(isset($result_array['status_code']) && $result_array['status_code'] == '11'){
            		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
    					    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
            		    }else if(isset($result_array['status_code']) && $result_array['status_code'] == '5'){
            		        $output['errorCode'] = ERROR_OVERTIME;
        					$output['errorMessage'] = $this->lang->line('error_overtime');
            		    }else if(isset($result_array['status_code'])){
            		    }else{
            		        $output['errorCode'] = ERROR_OVERTIME;
        					$output['errorMessage'] = $this->lang->line('error_overtime');
            		    }
        		    }
        		}else{
        		    if($response['code'] == '404'){
        				$output['errorCode'] = ERROR_OVERTIME;
        				$output['errorMessage'] = $this->lang->line('error_overtime');
        			}
        		}
    		}
    		
    		if($method == 'ChangeBalance')
    		{
    			$output['orderID'] = $requestOrderID;
    			$output['orderIDAlias'] = $requestOrderIDAlias;
    		}
    		
    		//Database update
    		$this->db->trans_start();
    		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array,$curl_array);
    		$this->db->trans_complete();
		}
		
		return $output;
	}
	
	public function fc_connect($method = NULL, $sys_data = NULL, $api_data = NULL, $post_data = NULL){
	    $arr = json_decode($api_data, TRUE);
	    
	    $currency_one = array("IDR", "VND","MMKK","KHR");
        $currency_two = array("MMK");
        $currency_three = array("MYRR");
        $currency_four = array("THBB");
        
        //Initial output data
		$output = array(
			'errorCode' => ERROR_SYSTEM_ERROR, 
			'errorMessage' => $this->lang->line('error_system_error'),
		);
		
		//Prepare post data
		$url = $arr['APIUrl'];
		$requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
		$curl_array = array();
		$amount = 0;
		$param_array = array();
		$result_array = array();
		
		if($method == 'CreateMember')
		{
		    $url .= '/AddMember';
		    $param_array = array(
		        'AgentCode' => $arr['AgentCode'],
		        'Currency' => $arr['Currency'],
		        'Params' => "",
		        'Sign' => "",
		    );

		    $content_array = array(
		        'MemberAccount' => $post_data['username'],
		    );
		}
		else if($method == 'LoginGame')
		{
		    $url .= '/Login';
		    $param_array = array(
		        'AgentCode' => $arr['AgentCode'],
		        'Currency' => $arr['Currency'],
		        'Params' => "",
		        'Sign' => "",
		    );
		    
		    $lang = '1';
		
			switch($post_data['syslang'])
			{
				case LANG_ZH_CN:
				case LANG_ZH_HK:
				case LANG_ZH_TW: $lang = '2'; break;
				case LANG_ID: $lang = '5'; break;
				case LANG_TH: $lang = '4'; break;
				case LANG_VI: $lang = '3'; break;
				case LANG_JA: $lang = '7'; break;
				case LANG_KO: $lang = '8'; break;
				case LANG_MY: $lang = '6'; break;
			}
		    
		    $content_array = array(
		        'MemberAccount' => $post_data['game_id'],
		        'GameID' => "",
		        'LanguageID' => $lang,
		        'HomeUrl' => $post_data['return_url'],
		        'JackpotStatus' => $arr['JackpotStatus'],
		        'LoginGameHall' => true,
		        'GameHallGameType' => $arr['GameHallGameType'],
		    );
		    
		    if( ! empty($post_data['game_code']))
			{
		        $content_array['GameID'] = $post_data['game_code'];
		        $content_array['LoginGameHall'] = false;
			}
		}
		else if($method == 'GetBalance')
        {
            $url .= '/SearchMember';
		    $param_array = array(
		        'AgentCode' => $arr['AgentCode'],
		        'Currency' => $arr['Currency'],
		        'Params' => "",
		        'Sign' => "",
		    );

		    $content_array = array(
		        'MemberAccount' => $post_data['game_id'],
		    );
        }
		else if($method == 'ChangeBalance')
		{
		    $requestOrderIDAlias = $requestOrderID;
		    if(in_array($arr['Currency'],$currency_one)){
				$amount = bcdiv($post_data['amount'] / 1000,1,2);
		    }else if(in_array($arr['Currency'],$currency_two)){
		        $amount = bcdiv($post_data['amount'] / 100,1,2);
		    }else if(in_array($arr['Currency'],$currency_three)){
		        $amount = bcdiv($post_data['amount'] * 100,1,2);
			}else if(in_array($arr['Currency'],$currency_four)){
		        $amount = bcdiv($post_data['amount'] * 10,1,2);
			}else{
			    $amount = bcdiv($post_data['amount'],1,2);
			}
			
			$url .= '/SetPoints';
		    $param_array = array(
		        'AgentCode' => $arr['AgentCode'],
		        'Currency' => $arr['Currency'],
		        'Params' => "",
		        'Sign' => "",
		    );

		    $content_array = array(
		        'MemberAccount' => $post_data['game_id'],
		        'TrsID' => $requestOrderIDAlias,
		        'AllOut' => 0,
		        'Points' => $amount,
		    );
		}
		else if($method == 'LogoutGame')
		{
		    $url .= '/KickOut';
		    $param_array = array(
		        'AgentCode' => $arr['AgentCode'],
		        'Currency' => $arr['Currency'],
		        'Params' => "",
		        'Sign' => "",
		    );

		    $content_array = array(
		        'MemberAccount' => $post_data['game_id'],
		    );
		}
		
		$sign = md5(json_encode($content_array,true));
	    $param_array['Sign'] = $sign;
	    
	    $aes = openssl_encrypt(json_encode($content_array,true), 'AES-128-ECB', $arr['AgentKey'], OPENSSL_RAW_DATA);
        $params = base64_encode($aes);
        $param_array['Params'] = $params;
        $response = $this->curl_json($url, $param_array);
        $curl_array = $response['curl'];
		if($response['code'] == '0')
		{
			$result_array = json_decode($response['data'], TRUE);
			if($method == 'CreateMember')
		    {
		        if(isset($result_array['Result']) && $result_array['Result'] == '0'){
    			    $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    $output['gameID'] = $content_array['MemberAccount'];
				    $output['gamePassword'] = $post_data['password'];
    			}else if(isset($result_array['Result']) && $result_array['Result'] == '502'){
    		        $output['errorCode'] = ERROR_USERNAME_EXITS;
				    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
    		    }else if(isset($result_array['Result']) && $result_array['Result'] == '408'){
    		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
    		    }else if(isset($result_array['Result']) && $result_array['Result'] == '411'){
    		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
    		    }
		    }
    		else if($method == 'LoginGame')
    		{
    		    if(isset($result_array['Result']) && $result_array['Result'] == '0'){
    			    $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    $output['result'] = $result_array['Url'];
    			}else if(isset($result_array['Result']) && $result_array['Result'] == '408'){
    		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
    		    }else if(isset($result_array['Result']) && $result_array['Result'] == '411'){
    		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
    		    }
    		}
    		else if($method == 'GetBalance')
            {
                if(isset($result_array['Result']) && $result_array['Result'] == '0'){
    			    $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    if(in_array($arr['Currency'],$currency_one)){
				        $output['result'] = bcdiv($result_array['Points'] * 1000,1,2);
				    }else if(in_array($arr['Currency'],$currency_two)){
				        $output['result'] = bcdiv($result_array['Points'] * 100,1,2);
				    }else if(in_array($arr['Currency'],$currency_three)){
				        $output['result'] = bcdiv($result_array['Points'] / 100,1,2);
				    }else if(in_array($arr['Currency'],$currency_four)){
				        $output['result'] = bcdiv($result_array['Points'] / 10,1,2);
				    }else{
				        $output['result'] = bcdiv($result_array['Points'],1,2);
				    }
    			}else if(isset($result_array['Result']) && $result_array['Result'] == '502'){
    		        $output['errorCode'] = ERROR_USERNAME_EXITS;
				    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
    		    }else if(isset($result_array['Result']) && $result_array['Result'] == '408'){
    		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
    		    }else if(isset($result_array['Result']) && $result_array['Result'] == '411'){
    		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
    		    }
            }
    		else if($method == 'ChangeBalance')
    		{
    		    if(isset($result_array['Result']) && $result_array['Result'] == '0'){
        		    $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
				    if(in_array($arr['Currency'],$currency_one)){
				        $output['result'] = bcdiv($result_array['Points'] * 1000,1,2);
				    }else if(in_array($arr['Currency'],$currency_two)){
				        $output['result'] = bcdiv($result_array['Points'] * 100,1,2);
				    }else if(in_array($arr['Currency'],$currency_three)){
				        $output['result'] = bcdiv($result_array['Points'] / 100,1,2);
				    }else if(in_array($arr['Currency'],$currency_four)){
				        $output['result'] = bcdiv($result_array['Points'] / 10,1,2);
				    }else{
				        $output['result'] = bcdiv($result_array['Points'],1,2);
				    }
    			}else if(isset($result_array['Result']) && $result_array['Result'] == '502'){
    		        $output['errorCode'] = ERROR_USERNAME_EXITS;
				    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
    		    }else if(isset($result_array['Result']) && $result_array['Result'] == '408'){
    		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
    		    }else if(isset($result_array['Result']) && $result_array['Result'] == '411'){
    		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
    		    }else if(isset($result_array['Result']) && $result_array['Result'] == '702'){
    		        $output['errorCode'] = ERROR_OVERTIME;
				    $output['errorMessage'] = $this->lang->line('error_overtime');
    		    }else if(isset($result_array['Result']) && $result_array['Result'] == '703'){
    		        $output['errorCode'] = ERROR_OVERTIME;
				    $output['errorMessage'] = $this->lang->line('error_overtime');
    		    }else if(isset($result_array['Result'])){
    		        
    		    }else{
    		        $output['errorCode'] = ERROR_OVERTIME;
				    $output['errorMessage'] = $this->lang->line('error_overtime');
    		    }
    		}
    		else if($method == 'LogoutGame')
    		{
    		    if(isset($result_array['Result']) && $result_array['Result'] == '0'){
    			    $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
    			}else if(isset($result_array['Result']) && $result_array['Result'] == '504'){
    			    $output['errorCode'] = ERROR_SUCCESS;
				    $output['errorMessage'] = $this->lang->line('error_success');
    			}else if(isset($result_array['Result']) && $result_array['Result'] == '502'){
    		        $output['errorCode'] = ERROR_USERNAME_EXITS;
				    $output['errorMessage'] = $this->lang->line('error_username_already_exits');
    		    }else if(isset($result_array['Result']) && $result_array['Result'] == '408'){
    		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
    		    }else if(isset($result_array['Result']) && $result_array['Result'] == '411'){
    		        $output['errorCode'] = ERROR_GAME_MAINTENANCE;
				    $output['errorMessage'] = $this->lang->line('error_game_maintenance');
    		    }
    		}
		}else{
    	    if($response['code'] == '404'){
				$output['errorCode'] = ERROR_OVERTIME;
				$output['errorMessage'] = $this->lang->line('error_overtime');
			}
    	}
        	
		
		if($method == 'ChangeBalance')
		{
			$output['orderID'] = $requestOrderID;
			$output['orderIDAlias'] = $requestOrderIDAlias;
		}
		//Database update
		$this->db->trans_start();
		$this->game_model->insert_api_log($post_data['provider_code'], $method, $output, $param_array, $result_array,$curl_array);
		$this->db->trans_complete();
		
		return $output;
	}
}