<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slots extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->save_current_url('slots');

		$data['seo'] = $this->seo_model->get_seo_data(PAGE_SLOTS);


		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/slots', $data);
		}
		else
		{
			$this->load->view('web/slots', $data);
		}
	}

	public function game($provider_code = NULL)
	{
		$this->save_current_url('slots/game/' . $provider_code);

		$data['seo'] = $this->seo_model->get_seo_data(PAGE_SLOTS);

		if($this->agent->is_mobile())
		{
			$syslang = ((get_language_id(get_language()) == LANG_ZH_CN OR get_language_id(get_language()) == LANG_ZH_HK OR get_language_id(get_language()) == LANG_ZH_TW) ? LANG_ZH_CN : LANG_EN);

			$signature = md5(SYSTEM_API_AGENT_ID . $provider_code . SYSTEM_API_SECRET_KEY);

			$array_param = array(
				"method" => 'GameList',
				"agent_id" => SYSTEM_API_AGENT_ID,
				"signature" => $signature,
				"syslang" => $syslang,
				"device" => PLATFORM_WEB,
				"provider_code" => $provider_code,
				"game_type_code" => GAME_SLOTS
			);

			$response = $this->curl_json(HUB_URL, $array_param);
			$result_array = json_decode($response, TRUE);

			$data['list'] = '';
			for($i=0;$i<sizeof($result_array['result']);$i++)
			{
				if($this->agent->is_mobile())
				{
					$data['list'] .= '<div class="col-4 text-center mt-4"><div class="slotgame-title pb-1" style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">' . $result_array['result'][$i]['game_name'] . '</div><img src="' . $result_array['result'][$i]['game_picture'] . '" class="img-fluid" alt="' . $result_array['result'][$i]['game_name'] . '" onclick="open_game(\'' . $provider_code . '\', \'' . GAME_SLOTS . '\', \'' . $result_array['result'][$i]['game_code'] . '\')" style="border-radius: 15px;"></div>';
				}
				else {
					$data['list'] .= '<div class="slotgame-box"><div class="slotgame-title">' . $result_array['result'][$i]['game_name'] . '</div><img src="' . $result_array['result'][$i]['game_picture'] . '" class="img-responsive" alt="' . $result_array['result'][$i]['game_name'] . '" onclick="open_game(\'' . $provider_code . '\', \'' . GAME_SLOTS . '\', \'' . $result_array['result'][$i]['game_code'] . '\')" style="width:100%; height:133px"></div>';
				}
			}
			$this->load->view('mobile/slots_listing', $data);
		}
		else
		{
			$this->load->view('web/slots', $data);
		}
	}
}