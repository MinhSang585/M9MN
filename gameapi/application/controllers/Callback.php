<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Callback extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function mg8(){
		$this->load->library('rng');
		$provider_code = 'MEGA';
		$data_capture_plain = file_get_contents("php://input");
		$data_capture = ((substr($data_capture_plain, 0, strlen("json=")) == "json=") ? substr($data_capture_plain, strlen("json=")) : $data_capture_plain);
		$data_json = json_decode($data_capture,true);
		//Prepare Data
		$id = (isset($data_json['id']) ? trim($data_json['id']) : '');
		$method = (isset($data_json['method']) ? trim($data_json['method']) : '');
		$jsonrpc = (isset($data_json['jsonrpc']) ? trim($data_json['jsonrpc']) : '');
		$game_id = (isset($data_json['params']['loginId']) ? trim($data_json['params']['loginId']) : '');
		$password = (isset($data_json['params']['password']) ? trim($data_json['params']['password']) : '');
		$random = (isset($data_json['params']['random']) ? trim($data_json['params']['random']) : '');
		$sn = (isset($data_json['params']['sn']) ? trim($data_json['params']['sn']) : '');
		$token = $this->rng->get_token(50);

		$output = array(
			"id" => $id,
			"result" => array(
				"success" => "0",
				"sessionId" => $token,
				"msg" => "登录失败",
			),
			"jsonrpc" => $jsonrpc,
			"error" => null,
		);

		$game_data = $this->game_model->get_game_data($provider_code);
		if(!empty($game_data)){
			$arr = json_decode($game_data['api_data'],true);
			if($arr['SN'] == $sn){
				$playerData = $this->player_model->get_player_detail_by_game_id_data($provider_code,$game_id);
				if(!empty($playerData)){
					if($playerData['password'] == $password){
						$this->player_model->update_player_game_token($provider_code, $playerData['username'], $token);
						$output['result']['success'] = "1";
						$output['result']['msg'] = "登录成功";
					}else{
						$output['result']['msg'] = "玩家密码不正确";
					}
				}else{
					$output['result']['msg'] = "玩家不存在";
				}
			}else{
				$output['result']['msg'] = "代理不存在";
			}
		}else{
			$output['result']['msg'] = "游戏不存在";
		}
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($output))
				->_display();
				
		exit();
	}
}