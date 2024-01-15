<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('api_model', 'general_model'));
	}
	
	public function index()
	{
		if($this->session->userdata('is_logged_in')) {
			$this->_session_destroy();
			
			$data = array(
						'msg_alert' => $this->lang->line('error_logout_success'),
						'msg_icon' => 1,
					);
					
			$this->session->set_userdata($data);
		}
		
		redirect('home');
	}
	
	public function force()
	{
		if($this->session->userdata('is_logged_in')) {
			$this->_session_destroy();
			
			$data = array(
						'msg_alert' => $this->lang->line('error_multiple_login'),
						'msg_icon' => 2,
					);
			
			$this->session->set_userdata($data);
		}
		
		redirect('home');
	}
	
	public function denied()
	{
		redirect('home');
	}
	
	private function _session_destroy()
	{
		if($this->session->userdata('is_logged_in'))
		{
			$device = PLATFORM_WEB;
			if($this->agent->is_mobile()) 
			{
				$device = PLATFORM_MOBILE_WEB;
			}
				
			$username = $this->session->userdata('username');
			
			$player_data = $this->player_model->get_player_data($username);
			if( ! empty($player_data))
			{
				$this->_withdraw_balance();
				
				//Database update
				$this->db->trans_start();
				
				$this->player_model->clear_login_token($username);
				$this->player_model->insert_log(LOG_LOGOUT, $device, $player_data);
				
				$this->db->trans_complete();
			}
		}
		
		$userdata = array(
					'player_id',
					'nickname',
					'username',
					'active',
					'last_login_date',
					'login_token',
					'is_logged_in',
					'read_notice',
				);
		$this->session->unset_userdata($userdata);
		
		//session_destroy();
	}
	
	private function _withdraw_balance()
	{
		if($this->session->userdata('is_logged_in'))
		{
			$username = $this->session->userdata('username');
			
			$player_data = $this->player_model->get_player_data($username);
			if( ! empty($player_data))
			{
				if( ! empty($player_data['last_in_game']))
				{
					$api_data = $this->api_model->get_api_data(SYSTEM_API_AGENT_ID);	
					
					if( ! empty($api_data))
					{
						//Get balance, withdraw and logout from previous game
						$account_data = $this->player_model->get_player_game_account_data($player_data['last_in_game'], $player_data['player_id']);
						if( ! empty($account_data))
						{
							$device = PLATFORM_WEB;
							if($this->agent->is_mobile()) 
							{
								$device = PLATFORM_MOBILE_WEB;
							}
							
							$syslang = ((get_language_id(get_language()) == LANG_ZH_CN OR get_language_id(get_language()) == LANG_ZH_HK OR get_language_id(get_language()) == LANG_ZH_TW) ? LANG_ZH_CN : LANG_EN);
					
							$url = site_url('gameapi/api'); 
							$signature = md5($api_data['agent_id'] . $player_data['last_in_game'] . $account_data['username'] . $api_data['secret_key']);
							
							$param_array = array(
													"agent_id" => $api_data['agent_id'],
													"syslang" => $syslang,
													"device" => $device,
													"provider_code" => $player_data['last_in_game'],
													"username" => $account_data['username'],
													"password" => $account_data['password'],
													"signature" => $signature,
												);
							
							//Get balance
							$balance = 0;
							$param_array['method'] = 'GetBalance';
							$response = $this->curl_json($url, $param_array);
							$result_array = json_decode($response, TRUE);
							
							if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
							{
								$balance = $result_array['result'];
							}
							
							if($balance > 0)
							{
								//Withdraw credit
								$param_array['method'] = 'ChangeBalance';
								$param_array['order_id'] = 'OUT' . date("YmdHis") . $account_data['username'];
								$param_array['amount'] = ($balance * -1);
								$response = $this->curl_json($url, $param_array);
								$result_array = json_decode($response, TRUE);
							
								if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
								{
									//Database update
									$this->db->trans_start();
									
									//update last in game
									$this->player_model->update_player_last_in_game('', $player_data['player_id']);
									
									//update wallet
									$newData = $this->player_model->point_transfer($player_data, $balance, $player_data['username']);
									$this->player_model->insert_log(LOG_WALLET_TRANSFER, $device, $newData, $player_data);
									$this->general_model->insert_game_transfer_report($player_data['last_in_game'], 'MAIN', $balance, $player_data['points'], $balance, $player_data['player_id']);
									
									$this->db->trans_complete();
								}
							}
							
							//Logout game
							$param_array['method'] = 'LogoutGame';
							$this->curl_json($url, $param_array);
						}
					}
				}
			}
		}
	}
}
