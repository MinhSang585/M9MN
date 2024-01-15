<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		if ($this->is_logged_in() != '') {

			if ($this->agent->is_mobile()) {
				redirect('login');
			} else {
				redirect('home');
			}
		}

		$this->load->model(array('bank_model', 'miscellaneous_model', 'player_model', 'payment_gateway_model', 'promotion_model', 'api_model', 'general_model'));
	}

	public function index()
	{

		$this->save_current_url('account');

		$data['seo'] = $this->seo_model->get_seo_data(PAGE_HOME);
		$data['player'] = $this->player_model->get_player_data($this->session->userdata('username'));
		$data['bank'] = $this->bank_model->get_bank_list(BANK_TYPE_CASH);
		$data['player_cash_bank'] = $this->bank_model->get_player_bank_list(NULL, BANK_TYPE_CASH);
		$data['player_cryto_bank'] = $this->bank_model->get_player_bank_list(NULL, BANK_TYPE_CRYTO);
		if ($this->agent->is_mobile()) {
			$this->load->view('mobile/account_details', $data);
		} else {
			$this->load->view('web/account_details', $data);
		}
	}
	public function profile()
	{
		$this->save_current_url('account');

		$data['seo'] = $this->seo_model->get_seo_data(PAGE_HOME);
		$data['player'] = $this->player_model->get_player_data($this->session->userdata('username'));

		$bank = $this->bank_model->get_bank_list(BANK_TYPE_CASH);
		$bank_crypto = $this->bank_model->get_bank_list(BANK_TYPE_CRYTO);
		$data['bank'] = array_merge($bank, $bank_crypto);
		$data['player_cash_bank'] = $this->bank_model->get_player_bank_list(NULL, BANK_TYPE_CASH);
		$data['player_cryto_bank'] = $this->bank_model->get_player_bank_list(NULL, BANK_TYPE_CRYTO);
		if ($this->agent->is_mobile()) {
			$this->load->view('mobile/account_profile', $data);
		} else {
			$this->load->view('web/account_profile', $data);
		}
	}
	public function badding_bank()
	{
		$this->save_current_url('account');

		$data['seo'] = $this->seo_model->get_seo_data(PAGE_HOME);
		$data['player'] = $this->player_model->get_player_data($this->session->userdata('username'));

		$bank = $this->bank_model->get_bank_list(BANK_TYPE_CASH);
		$bank_crypto = $this->bank_model->get_bank_list(BANK_TYPE_CRYTO);
		$data['bank'] = array_merge($bank ?? [], $bank_crypto ?? []);
		
		$player_cash_bank = $this->bank_model->get_player_bank_list(NULL, BANK_TYPE_CASH);
		$player_crypto_bank = $this->bank_model->get_player_bank_list(NULL, BANK_TYPE_CRYTO);
		$data['player_cash_bank'] = array_merge($player_cash_bank ?? [], $player_crypto_bank ?? []);
		if ($this->agent->is_mobile()) {
			$this->load->view('mobile/account_bank', $data);
		} else {
			$this->load->view('web/account_bank', $data);
		}
	}
	public function test(){
		$test = $this->bank_model->get_player_bank_list(NULL, BANK_TYPE_CASH);
		ad($test);
	}
	public function deposit($type = DEPOSIT_OFFLINE_BANKING)
	{
		$this->save_current_url('account/deposit');

		$data['seo'] = $this->seo_model->get_seo_data(PAGE_HOME);
		$data['player'] = $this->player_model->get_player_data($this->session->userdata('username'));
		if ($data['player']['player_type'] == PLAYER_TYPE_CASH_MARKET) {
			$data['player_bank'] = $this->bank_model->get_player_bank_account_list($data['player']);
			$data['setting'] = $this->miscellaneous_model->get_miscellaneous();
			$data['miscellaneous'] = $this->miscellaneous_model->get_miscellaneous();
			$data['transaction_code'] = 'D' . $this->session->userdata('player_id') . time();
			$data['currencies'] =  $this->general_model->get_currency_list($data['player']['player_id']);
			if ($this->session->userdata('username') != 'linhdang99') {
				unset($data['currencies'][1]);
			}
			$data['online_gateway'] = array();
			$data['credit_card_gateway'] = array();
			$data['hypermart_gateway'] = array();
			$data['vip_gateway'] = array();
			$data['promotion'] = array();
			if ($data['player']['is_online_deposit']) {
				$data['online_gateway'] =  $this->payment_gateway_model->get_payment_gateway_list_by_type(DEPOSIT_ONLINE_BANKING);
			}
			if ($data['player']['is_credit_card_deposit']) {
				$data['credit_card_gateway'] =  $this->payment_gateway_model->get_payment_gateway_list_by_type(DEPOSIT_CREDIT_CARD);
			}
			if ($data['player']['is_hypermart_deposit']) {
				$data['hypermart_gateway'] =  $this->payment_gateway_model->get_payment_gateway_list_by_type(DEPOSIT_HYPERMART);
			}
			if ($data['player']['is_offline_deposit']) {
				if (!empty($data['player']['bank_group_id'])) {
					// $data['vip_gateway'] = $this->bank_model->get_bank_account_list_specific($data['player']['bank_group_id']);
					$data['vip_gateway'] = null;
				}
			}
			if ($data['player']['is_promotion'] == STATUS_ACTIVE) {
				$get_member_total_wallet  = $this->get_member_latest_wallet($data['player']);
				if ($data['player']['promotion_type'] == PROMOTION_TYPE_STRICT_BASED) {
					$data['promotion'] = $this->promotion_model->get_player_deposit_promotion_available_strict_with_detail($data['player'], get_language_id($this->session->userdata('lang')), $get_member_total_wallet);
				} else {
					$data['promotion'] = $this->promotion_model->get_player_deposit_promotion_available_unstrict_with_detail($data['player'], get_language_id($this->session->userdata('lang')), $get_member_total_wallet);
				}
			}

			// set is_offline to show default, can delete if it online
			if (empty($data['vip_gateway'])) {
				$data['isOffline'] = 1;
				$data['default_gateway'] = $this->bank_model->get_bank_account_list_default_deposit($data['player']['bank_group_id']);
				$data['bank'] = $this->bank_model->get_bank_list_info();
			}

			if ($this->agent->is_mobile()) {
				$this->load->view('mobile/account_deposit', $data);
			} else {
				$this->load->view('web/account_deposit', $data);
			}
		} else {
			redirect('home');
		}
	}

	public function deposit_test($type = DEPOSIT_OFFLINE_BANKING)
	{
		$this->save_current_url('account/deposit');

		$data['seo'] = $this->seo_model->get_seo_data(PAGE_HOME);
		$data['player'] = $this->player_model->get_player_data($this->session->userdata('username'));
		$data['player_bank'] = $this->bank_model->get_player_bank_account_list($data['player']);
		$data['setting'] = $this->miscellaneous_model->get_miscellaneous();
		$data['miscellaneous'] = $this->miscellaneous_model->get_miscellaneous();
		$data['transaction_code'] = 'D' . $this->session->userdata('player_id') . time();
		$data['currencies'] =  $this->general_model->get_currency_list();
		$data['online_gateway'] = array();
		$data['credit_card_gateway'] = array();
		$data['hypermart_gateway'] = array();
		$data['vip_gateway'] = array();
		$data['promotion'] = array();
		if ($data['player']['is_online_deposit']) {
			$data['online_gateway'] =  $this->payment_gateway_model->get_payment_gateway_list_by_type(DEPOSIT_ONLINE_BANKING);
		}
		if ($data['player']['is_credit_card_deposit']) {
			$data['credit_card_gateway'] =  $this->payment_gateway_model->get_payment_gateway_list_by_type(DEPOSIT_CREDIT_CARD);
		}
		if ($data['player']['is_hypermart_deposit']) {
			$data['hypermart_gateway'] =  $this->payment_gateway_model->get_payment_gateway_list_by_type(DEPOSIT_HYPERMART);
		}
		if ($data['player']['is_offline_deposit']) {
			if (!empty($data['player']['bank_group_id'])) {
				$data['vip_gateway'] = $this->bank_model->get_bank_account_list_specific($data['player']['bank_group_id']);
			}
		}
		if ($data['player']['is_promotion'] == STATUS_ACTIVE) {
			$get_member_total_wallet  = $this->get_member_latest_wallet($data['player']);
			if ($data['player']['promotion_type'] == PROMOTION_TYPE_STRICT_BASED) {
				$data['promotion'] = $this->promotion_model->get_player_deposit_promotion_available_strict_with_detail($data['player'], get_language_id($this->session->userdata('lang')), $get_member_total_wallet);
			} else {
				$data['promotion'] = $this->promotion_model->get_player_deposit_promotion_available_unstrict_with_detail($data['player'], get_language_id($this->session->userdata('lang')), $get_member_total_wallet);
			}
		}
		ad($data);
	}

	public function withdrawal()
	{
		$this->save_current_url('account/withdrawal');

		$data['seo'] = $this->seo_model->get_seo_data(PAGE_HOME);
		$data['player'] = $this->player_model->get_player_data($this->session->userdata('username'));
		if ($data['player']['player_type'] == PLAYER_TYPE_CASH_MARKET || $data['player']['player_type'] == PLAYER_TYPE_MG_MARKET) {
			// $player_bank = $this->bank_model->get_player_bank_account_list();
			// $player_bank_crypto = $this->bank_model->get_bank_list(BANK_TYPE_CRYTO);
			// $data['player_bank'] = array_merge($player_bank, $player_bank_crypto);
			$data['setting'] = $this->miscellaneous_model->get_miscellaneous();
			$data['bank'] = $this->bank_model->get_bank_list();
			$data['currencies'] =  $this->general_model->get_currency_list();
			if ($this->session->userdata('username') != 'linhdang99') {
				unset($data['currencies'][1]);
			}
			if ($this->agent->is_mobile()) {
				$this->load->view('mobile/account_withdrawal', $data);
			} else {
				$this->load->view('web/account_withdrawal', $data);
			}
		} else {
			redirect('home');
		}
	}

	public function rebate()
	{
		$this->save_current_url('account/rebate');

		$data['seo'] = $this->seo_model->get_seo_data(PAGE_HOME);
		$data['player'] = $this->player_model->get_player_data($this->session->userdata('username'));
		if ($data['player']['player_type'] == PLAYER_TYPE_CASH_MARKET || $data['player']['player_type'] == PLAYER_TYPE_MG_MARKET) {
			$data['player_bank'] = $this->bank_model->get_player_bank_account_list();
			$data['setting'] = $this->miscellaneous_model->get_miscellaneous();
			$data['bank'] = $this->bank_model->get_bank_list();
			$data['currencies'] =  $this->general_model->get_currency_list();
			$data['seo'] = $this->seo_model->get_seo_data(PAGE_PROMOTION);

			$dataPromotion['promotion'] = $this->promotion_model->get_promotion_banner_list(1);
			

			if($this->agent->is_mobile())
			{
				$this->load->view('mobile/account_rebate', $dataPromotion);
			}
			else
			{
				$this->load->view('web/account_rebate', $dataPromotion);
			}
		} else {
			redirect('home');
		}
	}

	public function change_password()
	{
		$this->save_current_url('account/change_password');

		$data['seo'] = $this->seo_model->get_seo_data(PAGE_HOME);

		if ($this->agent->is_mobile()) {
			$this->load->view('mobile/account_change_password', $data);
		} else {
			$this->load->view('web/account_change_password', $data);
		}
	}

	public function transaction_history()
	{
		$this->save_current_url('account/transaction_history');

		$data['seo'] = $this->seo_model->get_seo_data(PAGE_HOME);
		$data['player'] = $this->player_model->get_player_data($this->session->userdata('username'));

		$this->session->unset_userdata('searches');

		if ($this->agent->is_mobile()) {
			$this->load->view('mobile/account_transaction_history', $data);
		} else {
			$this->load->view('web/account_transaction_history', $data);
		}
	}

	public function wallet()
	{
		$this->save_current_url('account');

		$data['seo'] = $this->seo_model->get_seo_data(PAGE_HOME);
		$data['player'] = $this->player_model->get_player_data($this->session->userdata('username'));

		if ($this->agent->is_mobile()) {
			$this->load->view('mobile/account_wallet', $data);
		} else {
			$this->load->view('web/account_wallet', $data);
		}
	}

	public function get_member_latest_wallet($player_data = NULL)
	{
		$is_balance_valid = TRUE;
		$player_data = $this->player_model->get_player_data($this->session->userdata('username'));
		if (!empty($player_data)) {
			$total_amount = $player_data['points'];

			if (!empty($player_data['last_in_game'])) {
				$api_data = $this->api_model->get_api_data(SYSTEM_API_AGENT_ID);

				if (!empty($api_data)) {
					//Get balance
					$balance = 0;
					$account_data = $this->player_model->get_player_game_account_data($player_data['last_in_game'], $player_data['player_id']);
					if (!empty($account_data)) {
						$device = PLATFORM_WEB;
						if ($this->agent->is_mobile()) {
							$device = PLATFORM_MOBILE_WEB;
						}

						$syslang = ((get_language_id($this->get_selected_language()) == LANG_ZH_CN or get_language_id($this->get_selected_language()) == LANG_ZH_HK or get_language_id($this->get_selected_language()) == LANG_ZH_TW) ? LANG_ZH_CN : LANG_EN);

						$url = base_url('gameapi/api');
						$signature = md5($api_data['agent_id'] . $player_data['last_in_game'] . $account_data['username'] . $api_data['secret_key']);

						$param_array = array(
							"method" => 'GetBalance',
							"agent_id" => $api_data['agent_id'],
							"syslang" => $syslang,
							"device" => $device,
							"provider_code" => $player_data['last_in_game'],
							"username" => $account_data['username'],
							"game_id" => $account_data['game_id'],
							"password" => $account_data['password'],
							"signature" => $signature,
						);

						$response = $this->curl_json($url, $param_array);
						$result_array = json_decode($response, TRUE);

						if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
							$balance = ($balance + $result_array['result']);
						} else {
							$is_balance_valid = FALSE;
						}
					}

					$total_amount = ($total_amount + $balance);
				}
			}
		}
		$result = array(
			'balance_valid' => $is_balance_valid,
			'balance_amount' => $total_amount,
		);
		return $result;
	}

	public function get_member_total_wallet($player_data = NULL)
	{
		$is_balance_valid = TRUE;
		$total_amount = 0;
		if (!empty($player_data)) {
			if (!empty($player_data['last_in_game'])) {
				$api_data = $this->api_model->get_api_data(SYSTEM_API_AGENT_ID);
				if (!empty($api_data)) {
					$total_amount = $player_data['points'];
					$account_data_list = $this->player_model->get_player_game_account_data_list($player_data['player_id']);
					if (!empty($account_data_list)) {
						foreach ($account_data_list as $account_data) {
							$device = PLATFORM_WEB;
							if ($this->agent->is_mobile()) {
								$device = PLATFORM_MOBILE_WEB;
							}

							$syslang = ((get_language_id(get_language()) == LANG_ZH_CN or get_language_id(get_language()) == LANG_ZH_HK or get_language_id(get_language()) == LANG_ZH_TW) ? LANG_ZH_CN : LANG_EN);

							$url = base_url('gameapi/api');
							$signature = md5($api_data['agent_id'] . $account_data['game_provider_code'] . $account_data['username'] . $api_data['secret_key']);

							$param_array = array(
								"method" => 'GetBalance',
								"agent_id" => $api_data['agent_id'],
								"syslang" => $syslang,
								"device" => $device,
								"provider_code" => $account_data['game_provider_code'],
								"username" => $account_data['username'],
								"password" => $account_data['password'],
								"game_id" => $account_data['game_id'],
								"signature" => $signature,
							);
							$response = $this->curl_json($url, $param_array);
							$result_array = json_decode($response, TRUE);

							if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
								$total_amount = ($total_amount + $result_array['result']);
							} else {
								$is_balance_valid = FALSE;
							}
						}
					}
				} else {
					$is_balance_valid = FALSE;
				}
			} else {
				$total_amount = $player_data['points'];
			}
		} else {
			$is_balance_valid = FALSE;
		}
		$result = array(
			'balance_valid' => $is_balance_valid,
			'balance_amount' => $total_amount,
		);
		return $result;
	}
}
