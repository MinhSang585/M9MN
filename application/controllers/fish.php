<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fish extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index() 
	{
		$this->save_current_url('fish');
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_FISHING);
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/fish', $data);
		}
		else
		{
			$this->load->view('web/fish', $data);
		}
	}
	
	public function game($provider_code = NULL) 
	{
		$this->save_current_url('fish/game/' . $provider_code);
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_FISHING);
		
		$syslang = ((get_language_id(get_language()) == LANG_ZH_CN OR get_language_id(get_language()) == LANG_ZH_HK OR get_language_id(get_language()) == LANG_ZH_TW) ? LANG_ZH_CN : LANG_EN);
		
		$signature = md5(SYSTEM_API_AGENT_ID . $provider_code . SYSTEM_API_SECRET_KEY);
		
		$array_param = array(
			"method" => 'GameList',
			"agent_id" => SYSTEM_API_AGENT_ID,
			"signature" => $signature,
			"syslang" => $syslang,
			"device" => PLATFORM_WEB,
			"provider_code" => $provider_code,
			"game_type_code" => GAME_FISHING
		);
		
		$response = $this->curl_json(base_url('gameapi/api'), $array_param);
		$result_array = json_decode($response, TRUE);
		
		$data['list'] = '';
		for($i=0;$i<sizeof($result_array['result']);$i++)
		{
			$data['list'] .= '<div class="slotgame-box"><div class="slotgame-title">' . $result_array['result'][$i]['game_name'] . '</div><img src="' . $result_array['result'][$i]['game_picture'] . '" class="img-responsive" alt="' . $result_array['result'][$i]['game_name'] . '" onclick="open_game(\'' . $provider_code . '\', \'' . GAME_FISHING . '\', \'' . $result_array['result'][$i]['game_code'] . '\')" style="width:100%; height:133px"></div>';
		}
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/fish_listing', $data);
		}
		else
		{
			$this->load->view('web/fish', $data);
		}
	}
}