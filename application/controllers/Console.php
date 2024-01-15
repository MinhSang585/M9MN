<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Console extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function reimage()
	{
		$query = $this->db->query("SELECT * FROM bctp_sub_games WHERE game_provider_code = 'PP'");
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$this->db->query("UPDATE bctp_sub_games SET game_picture_en = ? WHERE sub_game_id = ? LIMIT 1", array($row->game_code . '.png', $row->sub_game_id));
			}
		}
		
		$query = $this->db->query("SELECT * FROM bctp_sub_games WHERE game_provider_code = 'JK'");
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$this->db->query("UPDATE bctp_sub_games SET game_picture_en = ? WHERE sub_game_id = ? LIMIT 1", array($row->game_code . '.png', $row->sub_game_id));
			}
		}
	}
	
	public function session()
	{
		ad($this->session->userdata);	
	}
	
	public function register() 
	{
		$arr = array(
			"username" => 'ply113',
			"password" => 'aaa111',
			"passconf" => 'aaa111',
			"nickname" => 'player112',
			"mobile" => '0123456789',
			"email" => 'ply112@ply112.com',
			"wechat" => 'absddd',
			"referrer" => 'ply111',
		);
		
		$signature = md5(SYSTEM_API_AGENT_ID . implode('', $arr) . SYSTEM_API_SECRET_KEY);
		
		$array_param = array(
			"method" => 'MemberRegister',
			"agentId" => SYSTEM_API_AGENT_ID,
			"signature" => $signature,
			"syslang" => LANG_EN,
			"device" => PLATFORM_APP_ANDROID,
			"player" => array(
				"username" => $arr['username'],
				"password" => $arr['password'],
				"passconf" => $arr['passconf'],
				"nickname" => $arr['nickname'],
				"mobile" => $arr['mobile'],
				"email" => $arr['email'],
				"wechat" => $arr['wechat'],
				"referrer" => $arr['referrer'],
			)
		);
		
		$url = site_url('api');
		$response = $this->curl_json($url, $array_param);
		
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response))
				->_display();
				
		exit();
	}
	
	public function get_balance() 
	{
		$arr = array(
			"username" => 'ply112',
		);
		
		$signature = md5(SYSTEM_API_AGENT_ID . implode('', $arr) . SYSTEM_API_SECRET_KEY);
		
		$array_param = array(
			"method" => 'GetBalance',
			"agentId" => SYSTEM_API_AGENT_ID,
			"signature" => $signature,
			"syslang" => LANG_EN,
			"device" => PLATFORM_APP_ANDROID,
			"username" => $arr['username'],
		);
		
		$url = site_url('api');
		$response = $this->curl_json($url, $array_param);
		
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response))
				->_display();
				
		exit();
	}
	
	public function credit_point() 
	{
		$arr = array(
			"username" => 'ply112',
			"amount" => '200',
		);
		
		$signature = md5(SYSTEM_API_AGENT_ID . implode('', $arr) . SYSTEM_API_SECRET_KEY);
		
		$array_param = array(
			"method" => 'CreditBalance',
			"agentId" => SYSTEM_API_AGENT_ID,
			"signature" => $signature,
			"syslang" => LANG_EN,
			"device" => PLATFORM_APP_ANDROID,
			"username" => $arr['username'],
			"amount" => $arr['amount'],
		);
		
		$url = site_url('api');
		$response = $this->curl_json($url, $array_param);
		
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response))
				->_display();
				
		exit();
	}
	
	public function debit_point() 
	{
		$arr = array(
			"username" => 'ply112',
			"amount" => '100',
		);
		
		$signature = md5(SYSTEM_API_AGENT_ID . implode('', $arr) . SYSTEM_API_SECRET_KEY);
		
		$array_param = array(
			"method" => 'DebitBalance',
			"agentId" => SYSTEM_API_AGENT_ID,
			"signature" => $signature,
			"syslang" => LANG_EN,
			"device" => PLATFORM_APP_ANDROID,
			"username" => $arr['username'],
			"amount" => $arr['amount'],
		);
		
		$url = site_url('api');
		$response = $this->curl_json($url, $array_param);
		
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response))
				->_display();
				
		exit();
	}
	
	public function login() 
	{
		$arr = array(
			"username" => 'ply112',
			"password" => 'aaa111',
		);
		
		$signature = md5(SYSTEM_API_AGENT_ID . implode('', $arr) . SYSTEM_API_SECRET_KEY);
		
		$array_param = array(
			"method" => 'MemberLogin',
			"agentId" => SYSTEM_API_AGENT_ID,
			"signature" => $signature,
			"syslang" => LANG_EN,
			"device" => PLATFORM_APP_ANDROID,
			"username" => $arr['username'],
			"password" => $arr['password'],
		);
		
		$url = site_url('api');
		$response = $this->curl_json($url, $array_param);
		
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response))
				->_display();
			
		exit();
	}
	
	public function logout() 
	{
		$arr = array(
			"username" => 'ply112',
		);
		
		$signature = md5(SYSTEM_API_AGENT_ID . implode('', $arr) . SYSTEM_API_SECRET_KEY);
		
		$array_param = array(
			"method" => 'MemberLogout',
			"agentId" => SYSTEM_API_AGENT_ID,
			"signature" => $signature,
			"syslang" => LANG_EN,
			"device" => PLATFORM_APP_ANDROID,
			"username" => $arr['username'],
		);
		
		$url = site_url('api');
		$response = $this->curl_json($url, $array_param);
		
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response))
				->_display();
			
		exit();
	}
	
	public function verify_token() 
	{
		$arr = array(
			"username" => 'ply112',
			"token" => 'ply112',
		);
		
		$signature = md5(SYSTEM_API_AGENT_ID . implode('', $arr) . SYSTEM_API_SECRET_KEY);
		
		$array_param = array(
			"method" => 'VerifyToken',
			"agentId" => SYSTEM_API_AGENT_ID,
			"signature" => $signature,
			"syslang" => LANG_EN,
			"device" => PLATFORM_APP_ANDROID,
			"username" => $arr['username'],
			"token" => $arr['token'],
		);
		
		$url = site_url('api');
		$response = $this->curl_json($url, $array_param);
		
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response))
				->_display();
			
		exit();
	}
	
	public function bank_list() 
	{
		$signature = md5(SYSTEM_API_AGENT_ID . SYSTEM_API_SECRET_KEY);
		
		$array_param = array(
			"method" => 'GetBankList',
			"agentId" => SYSTEM_API_AGENT_ID,
			"signature" => $signature,
			"syslang" => LANG_EN,
			"device" => PLATFORM_APP_ANDROID,
		);
		
		$url = site_url('api');
		$response = $this->curl_json($url, $array_param);
		
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response))
				->_display();
			
		exit();
	}
	
	public function game_list() 
	{
		$arr = array(
			"game_type_code" => 'SL',
		);
		
		$signature = md5(SYSTEM_API_AGENT_ID . implode('', $arr) . SYSTEM_API_SECRET_KEY);
		
		$array_param = array(
			"method" => 'GetGameList',
			"agentId" => SYSTEM_API_AGENT_ID,
			"signature" => $signature,
			"syslang" => LANG_EN,
			"device" => PLATFORM_APP_ANDROID,
			"game_type_code" => $arr['game_type_code'],
		);
		
		$url = site_url('api');
		$response = $this->curl_json($url, $array_param);
		
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response))
				->_display();
			
		exit();
	}
	
	public function sub_game_list() 
	{
		$arr = array(
			"provider_code" => 'PP',
			"game_type_code" => 'SL',
		);
		
		$signature = md5(SYSTEM_API_AGENT_ID . implode('', $arr) . SYSTEM_API_SECRET_KEY);
		
		$array_param = array(
			"method" => 'GetSubGameList',
			"agentId" => SYSTEM_API_AGENT_ID,
			"signature" => $signature,
			"syslang" => LANG_EN,
			"device" => PLATFORM_APP_ANDROID,
			"provider_code" => $arr['provider_code'],
			"game_type_code" => $arr['game_type_code'],
		);
		
		$url = site_url('api');
		$response = $this->curl_json($url, $array_param);
		
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response))
				->_display();
			
		exit();
	}
	
	public function get_balance_dv() 
	{
		$arr = array(
			"username" => 'ply112',
		);
		
		$signature = md5(SYSTEM_API_AGENT_ID . implode('', $arr) . SYSTEM_API_SECRET_KEY);
		
		$array_param = array(
			"method" => 'GetBalanceDV',
			"agentId" => SYSTEM_API_AGENT_ID,
			"signature" => $signature,
			"syslang" => LANG_EN,
			"device" => PLATFORM_APP_ANDROID,
			"username" => $arr['username'],
		);
		
		$url = site_url('api');
		$response = $this->curl_json($url, $array_param);
		
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response))
				->_display();
				
		exit();
	}
	
	public function login_game() 
	{
		$arr = array(
			"username" => 'tu1589181391399',
			"provider_code" => 'HB',
			"game_type_code" => 'SL',
			"game_code" => 'SGLuckyFortuneCat',
		);
		
		$signature = md5(SYSTEM_API_AGENT_ID . implode('', $arr) . SYSTEM_API_SECRET_KEY);
		
		$array_param = array(
			"method" => 'LoginGame',
			"agentId" => SYSTEM_API_AGENT_ID,
			"signature" => $signature,
			"syslang" => LANG_EN,
			"device" => PLATFORM_APP_ANDROID,
			"username" => $arr['username'],
			"provider_code" => $arr['provider_code'],
			"game_type_code" => $arr['game_type_code'],
			"game_code" => $arr['game_code'],
		);
		
		$url = site_url('api');
		$response = $this->curl_json($url, $array_param);
		
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response))
				->_display();
				
		exit();
	}
}