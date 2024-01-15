<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajax extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'player_promotion_model',
			'announcement_model',
			'message_model',
			'api_model',
			'bank_model',
			'deposit_model',
			'general_model',
			'miscellaneous_model',
			'user_model',
			'withdrawal_model',
			'payment_gateway_model',
			'promotion_model',
			'currencies_model'
		]);
	}

	public function cronjob()
	{
		$this->bank_model->clear_bank_account_limit_usage();
	}

	public function change($selection = LANG_EN)
	{
		$lang = get_language($selection);

		$this->session->set_userdata('lang', $lang);

		$url = $this->get_previous_url();

		redirect($url);
	}

	public function captcha()
	{
		$text = rand(1000, 9999);
		$this->session->set_userdata('captcha', $text);

		$this->load->library('simple_captcha');
		$captcha = $this->simple_captcha->CreateImage($text);

		echo $captcha;
	}

	public function check_username()
	{
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);

		$config = array(
			array(
				'field' => 'username',
				'label' => strtolower($this->lang->line('label_username')),
				'rules' => 'trim|required|min_length[6]|max_length[16]|regex_match[/^[a-z0-9]+$/]|is_unique[users.username]|is_unique[sub_accounts.username]|is_unique[players.username]',
				'errors' => array(
					'required' => $this->lang->line('error_username_empty'),
					'min_length' => $this->lang->line('error_username_incorrect'),
					'max_length' => $this->lang->line('error_username_incorrect'),
					'regex_match' => $this->lang->line('error_username_incorrect'),
					'is_unique' => $this->lang->line('error_username_already_exits')
				)
			)
		);

		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('', '');

		//Form validation
		if ($this->form_validation->run() == TRUE) {
			$json['status'] = EXIT_SUCCESS;
			$json['msg'] = $this->lang->line('error_username_available');
		} else {
			$json['msg'] = form_error('username');
		}

		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();
		unset($json);
		exit();
	}

	public function register()
	{
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);
		$_POST['username'] = strtolower($this->input->post('username', TRUE));
		$config = array(
			array(
				'field' => 'username',
				'label' => strtolower($this->lang->line('label_username')),
				'rules' => 'trim|required|min_length[6]|max_length[16]|regex_match[/^[a-z0-9]+$/]|is_unique[users.username]|is_unique[sub_accounts.username]|is_unique[players.username]',
				'errors' => array(
					'required' => $this->lang->line('error_username_empty'),
					'min_length' => $this->lang->line('error_username_incorrect'),
					'max_length' => $this->lang->line('error_username_incorrect'),
					'regex_match' => $this->lang->line('error_username_incorrect'),
					'is_unique' => $this->lang->line('error_username_already_exits')
				)
			),
			array(
				'field' => 'password',
				'label' => strtolower($this->lang->line('label_password')),
				'rules' => 'trim|required|min_length[6]|max_length[15]|regex_match[/^[A-Za-z0-9!#$^*]+$/]',
				'errors' => array(
					'required' => $this->lang->line('error_password_empty'),
					'min_length' => $this->lang->line('error_password_incorrect'),
					'max_length' => $this->lang->line('error_password_incorrect'),
					'regex_match' => $this->lang->line('error_password_incorrect')
				)
			),
			array(
				'field' => 'passconf',
				'label' => strtolower($this->lang->line('label_confirm_password')),
				'rules' => 'trim|required|matches[password]',
				'errors' => array(
					'required' => $this->lang->line('error_confirm_password_empty'),
					'matches' => $this->lang->line('error_confirm_password_not_match')
				)
			),
			array(
				'field' => 'full_name',
				'label' => strtolower($this->lang->line('label_full_name')),
				#'rules' => 'trim|required',
				'errors' => array(
					'required' => $this->lang->line('error_full_name_empty')
				)
			),
			array(
				'field' => 'nickname',
				'label' => strtolower($this->lang->line('label_nickname')),
				#'rules' => 'trim|required|min_length[1]|max_length[32]|regex_match[/^[A-Za-z0-9]+$/]',
				'errors' => array(
					'required' => $this->lang->line('error_nickname_empty'),
					'min_length' => $this->lang->line('error_nickname_incorrect'),
					'max_length' => $this->lang->line('error_nickname_incorrect'),
					'regex_match' => $this->lang->line('error_nickname_incorrect')
				)
			),
			array(
				'field' => 'email',
				'label' => strtolower($this->lang->line('label_email')),
				'rules' => 'trim',
				'errors' => array(
					'required' => $this->lang->line('error_email_empty'),
					'valid_email' => $this->lang->line('error_email_incorrect')
				)
			),
			array(
				'field' => 'mobile',
				'label' => strtolower($this->lang->line('label_mobile')),
				'rules' => 'trim|required|integer|is_unique[players.mobile]',
				'errors' => array(
					'required' => $this->lang->line('error_mobile_empty'),
					'integer' => $this->lang->line('error_mobile_incorrect'),
					'is_unique' => $this->lang->line('error_mobile_already_exits')
				)
			),
			array(
				'field' => 'dob',
				'label' => strtolower($this->lang->line('label_dob')),
				//'rules' => 'trim|required',
				'rules' => 'trim',
				'errors' => array(
					'required' => $this->lang->line('error_dob')
				)
			),
			array(
				'field' => 'captcha',
				'label' => strtolower($this->lang->line('label_captcha')),
				'rules' => 'trim|required|callback_captcha_check',
				'errors' => array(
					'required' => $this->lang->line('error_captcha_empty'),
					'captcha_check' => $this->lang->line('error_captcha_incorrect'),
				)
			)
		);

		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('', '');

		//Form validation
		if ($this->form_validation->run() == TRUE) {
			#$upline = $this->user_model->get_user_data(SYSTEM_API_AGENT_ID);
			$post_referral 	= ($this->input->post('referrer') != '') ? $this->input->post('referrer') : SYSTEM_API_AGENT_ID;
			$upline 		= $this->user_model->get_user_data($post_referral);

			if (!empty($upline)) {
				$device = PLATFORM_WEB;
				if ($this->agent->is_mobile()) {
					$device = PLATFORM_MOBILE_WEB;
				}
				$sys_data = $this->miscellaneous_model->get_miscellaneous();
				$fingerprint = $this->input->post('fingerprint', TRUE);
				if (!empty($sys_data)) {
					if (strtolower(substr($this->input->post('username', TRUE), 0, strlen($sys_data['system_prefix']))) != $sys_data['system_prefix']) {

						//fingerprint
						$fingerprint_status = true;
						if ($sys_data['fingerprint_status'] == STATUS_ACTIVE) {
							$fingerprint_data = $this->player_model->get_fingerprint_data($fingerprint);
							if (!empty($fingerprint_data)) {
								$fingerprint_status = false;
							}
						}

						if ($fingerprint_status) {
							$avatar 	= $this->player_model->get_random_avatar();
							$dob_date 	= $this->input->post('dob', TRUE);
							$dob 		= strtotime($dob_date);
							$data = array(
								'avatar' => (!empty($avatar) ? $avatar['avatar_id'] : '1'),
								'full_name' => $this->input->post('full_name', TRUE),
								'nickname' => $this->input->post('nickname', TRUE),
								'email' => $this->input->post('email', TRUE),
								'dob' => $dob,
								'mobile' => $this->input->post('mobile', TRUE),
								'wechat' => '',
								'referrer' => $this->input->post('referrer', TRUE),
								'username' => $this->input->post('username', TRUE),
								'password' => $this->input->post('password', TRUE)
							);

							$this->load->library('rng');
							$data['referral_code'] = $this->rng->get_token(12);

							#Check for referral code availbility
							$is_loop = TRUE;
							while ($is_loop == TRUE) {
								$rs = $this->player_model->get_player_data_by_referral_code($data['referral_code']);
								if (!empty($rs)) {
									$data['referral_code'] = $this->rng->get_token(12);
								} else {
									$is_loop = FALSE;
								}
							}

							//Database update
							$this->db->trans_start();

							$newData = $this->player_model->add_player($upline, $data);
							$this->player_model->insert_log(LOG_REGISTER, $device, $newData);
							if ($sys_data['fingerprint_status'] == STATUS_ACTIVE) {
								$newFData = $this->player_model->add_fingerprint($fingerprint, $newData);
							}
							$this->db->trans_complete();

							if ($this->db->trans_status() === TRUE) {
								$response = $this->player_model->verify_login($data);
								if (isset($response['is_logged_in'])) {
									$login_status = STATUS_FAIL;

									if ($response['is_logged_in'] == FALSE) {
										$json['msg'] = $this->lang->line('error_invalid_login');
									} else if ($response['active'] == STATUS_ACTIVE) {
										if (isset($sys_data['is_player_change_password']) && $sys_data['is_player_change_password'] == STATUS_INACTIVE) {
											$response['change_password_notice'] = true;
										}
										$this->session->set_userdata($response);

										$login_status = STATUS_SUCCESS;
										$json['status'] = EXIT_SUCCESS;
										$json['msg'] = $this->lang->line('error_register_successful');
										$json['url'] = site_url('home');
									} else {
										$json['msg'] = $this->lang->line('error_account_suspended');
									}

									//Database update
									$this->db->trans_start();

									$this->player_model->insert_login_report($response, $device, $login_status);

									if ($login_status == STATUS_SUCCESS) {
										$this->player_model->update_last_login($response);
										$this->player_model->insert_log(LOG_LOGIN, $device, $response);
									}

									$this->db->trans_complete();
								} else {
									$json['msg'] = $this->lang->line('error_invalid_login');
								}
							} else {
								$json['msg'] = $this->lang->line('error_register_failed');
							}
						} else {
							$json['msg'] = $this->lang->line('error_register_failed_fingerprint');
						}
					} else {
						$json['msg'] = $this->lang->line('error_username_cannot_start_with_system_prefix') . $sys_data['system_prefix'];
					}
				}
			}else{
				$json['msg']="There is no upline";
			}
		} else {
			$error = array(
				'username' => form_error('username'),
				'password' => form_error('password'),
				'passconf' => form_error('passconf'),
				'full_name' => form_error('full_name'),
				'nickname' => form_error('nickname'),
				'email' => form_error('email'),
				'mobile' => form_error('mobile'),
				'dob' => form_error('dob'),
				'captcha' => form_error('captcha')
			);

			if (!empty($error['username'])) {
				$json['msg'] = $error['username'];
			} else if (!empty($error['password'])) {
				$json['msg'] = $error['password'];
			} else if (!empty($error['passconf'])) {
				$json['msg'] = $error['passconf'];
			} else if (!empty($error['full_name'])) {
				$json['msg'] = $error['full_name'];
			} else if (!empty($error['nickname'])) {
				$json['msg'] = $error['nickname'];
			} else if (!empty($error['email'])) {
				$json['msg'] = $error['email'];
			} else if (!empty($error['mobile'])) {
				$json['msg'] = $error['mobile'];
			} else if (!empty($error['dob'])) {
				$json['msg'] = $error['dob'];
			} else if (!empty($error['captcha'])) {
				$json['msg'] = $error['captcha'];
			}
			unset($error);
		}

		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();
		unset($json);
		exit();
	}

	public function login()
	{
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);

		$config = array(
			array(
				'field' => 'username',
				'label' => strtolower($this->lang->line('label_username')),
				'rules' => 'trim|required',
				'errors' => array(
					'required' => $this->lang->line('error_username_empty')
				)
			),
			array(
				'field' => 'password',
				'label' => strtolower($this->lang->line('label_password')),
				'rules' => 'trim|required',
				'errors' => array(
					'required' => $this->lang->line('error_password_empty')
				)
			)
		);

		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('', '');

		//Form validation
		if ($this->form_validation->run() == TRUE) {
			$device = PLATFORM_WEB;
			if ($this->agent->is_mobile()) {
				$device = PLATFORM_MOBILE_WEB;
			}

			$data = array(
				'username' => $this->input->post('username', TRUE),
				'password' => $this->input->post('password', TRUE)
			);
			$response = $this->player_model->verify_login($data);
			$login_status = STATUS_FAIL;
			if (isset($response['is_logged_in'])) {
				if ($response['is_logged_in'] == FALSE) {
					$json['msg'] = $this->lang->line('error_invalid_login');
				} else if ($response['active'] == STATUS_ACTIVE) {
					$this->session->set_userdata($response);
					$login_status = STATUS_SUCCESS;
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('error_login_success');
					$json['url'] = site_url('home');

					$this->db->trans_start();

					$this->player_model->insert_login_report($response, $device, $login_status);

					if ($login_status == STATUS_SUCCESS) {
						$this->player_model->update_last_login($response);
						$this->player_model->insert_log(LOG_LOGIN, $device, $response);
					}

					$this->db->trans_complete();
				} else {
					$json['msg'] = $this->lang->line('error_account_suspended');
				}
			} else {
				$json['msg'] = $this->lang->line('error_invalid_login');
			}

			//$sys_data = $this->miscellaneous_model->get_miscellaneous();
			/*
			if(! empty($sys_data))
			{
				$fingerprint = $this->input->post('fingerprint', TRUE);
				//Check for valid downline
				$response = $this->player_model->verify_login($data);
				if(isset($response['is_logged_in']))
				{
					$login_status = STATUS_FAIL;

					if($response['is_logged_in'] == FALSE)
					{
						$json['msg'] = $this->lang->line('error_invalid_login');
					}
					else if($response['active'] == STATUS_ACTIVE)
					{
						//fingerprint
						$fingerprint_status = true;
						if($sys_data['fingerprint_status'] == STATUS_ACTIVE){
							if($response['is_fingerprint'] == STATUS_ACTIVE){
								$fingerprint_data = $this->player_model->get_fingerprint_data_exclude_member($fingerprint,$response);
								if(! empty($fingerprint_data)){
									$fingerprint_status = false;
								}else{
									$oldFData = $this->player_model->get_fingerprint_data_member($fingerprint,$response);
									if(empty($oldFData)){
										$newFData = $this->player_model->add_fingerprint($fingerprint,$response);
									}
								}
							}else{
								$newFData = $this->player_model->add_fingerprint($fingerprint,$response);
							}
						}
						if($fingerprint_status){
							$this->session->set_userdata($response);
							$login_status = STATUS_SUCCESS;
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('error_login_success');
							$json['url'] = site_url('home');
						}else{
							$json['msg'] = $this->lang->line('error_login_failed_fingerprint');
						}
					}
					else
					{
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
					$json['msg'] = $this->lang->line('error_invalid_login');
				}
			}
			*/
		} else {
			$error = array(
				'username' => form_error('username'),
				'password' => form_error('password')
			);

			if (!empty($error['username'])) {
				$json['msg'] = $error['username'];
			} else if (!empty($error['password'])) {
				$json['msg'] = $error['password'];
			}
		}

		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function forgot_password()
	{
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);

		$config = array(
			array(
				'field' => 'username',
				'label' => strtolower($this->lang->line('label_username')),
				'rules' => 'trim|required',
				'errors' => array(
					'required' => $this->lang->line('error_username_empty'),
				)
			),
			array(
				'field' => 'email',
				'label' => strtolower($this->lang->line('label_email')),
				#'rules' => 'trim|required|valid_email',
				'rules' => 'trim',
				'errors' => array(
					'required' => $this->lang->line('error_email_empty'),
					'valid_email' => $this->lang->line('error_email_incorrect')
				)
			),
			array(
				'field' => 'mobile',
				'label' => strtolower($this->lang->line('label_mobile')),
				'rules' => 'trim|required|integer',
				'errors' => array(
					'required' => $this->lang->line('error_mobile_empty'),
					'integer' => $this->lang->line('error_mobile_incorrect')
				)
			),
		);

		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('', '');

		//Form validation
		if ($this->form_validation->run() == TRUE) {
			$device = PLATFORM_WEB;
			if ($this->agent->is_mobile()) {
				$device = PLATFORM_MOBILE_WEB;
			}

			$post_email = trim($this->input->post('email', TRUE));
			$post_username = trim($this->input->post('username', TRUE));
			$post_mobile = trim($this->input->post('mobile', TRUE));
			//Verify player
			$player_data = $this->player_model->get_player_data($post_username);
			if (!empty($player_data)) {
				#if($player_data['mobile'] == $post_mobile && $player_data['email'] == $post_email){
				if ($player_data['mobile'] == $post_mobile) {
					$data = array(
						'password' => rand(100000, 999999),
						'player_id' => $player_data['player_id'],
						'username' => $player_data['username'],
						'email' => $player_data['email']
					);

					//Database update
					$this->db->trans_start();

					$this->player_model->update_player_password($data);
					$this->player_model->insert_log(LOG_RESET_PASSWORD, $device, $data);

					$this->db->trans_complete();

					if ($this->db->trans_status() === TRUE) {
						//Send mail...

						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('error_reset_password_successful') . " : " . $data['password'];
					} else {
						$json['msg'] = $this->lang->line('error_reset_password_failed');
					}
				} else {
					$json['msg'] = $this->lang->line('error_reset_password_failed');
				}
			} else {
				$json['msg'] = $this->lang->line('error_email_not_found');
			}
		} else {
			$error = array(
				'email' => form_error('email')
			);

			if (!empty($error['email'])) {
				$json['msg'] = $error['email'];
			}
		}

		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();
		unset($json);
		exit();
	}

	public function announcement()
	{
		$data = $this->announcement_model->get_announcement_list(get_language_id(get_language()));

		if (!empty($data)) {
			$current_time = time();
			$html = '';

			for ($i = 0; $i < sizeof($data); $i++) {
				if (($data[$i]['start_date'] == 0 && $data[$i]['end_date'] == 0) or
					($data[$i]['start_date'] == 0 && $data[$i]['end_date'] > 0 && $data[$i]['end_date'] > $current_time) or
					($data[$i]['start_date'] > 0 && $data[$i]['start_date'] < $current_time && $data[$i]['end_date'] == 0) or
					($data[$i]['start_date'] > 0 && $data[$i]['start_date'] < $current_time && $data[$i]['end_date'] > 0 && $data[$i]['end_date'] > $current_time)
				) {
					#$html .= '<a class="action" href="javascript:void(0);" onclick="actionClick(this);">' . $data[$i]['content'] . '</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					$html .= $data[$i]['content'] . '';
				}
			}
			unset($data);
			echo $html;
		}
	}

	public function get_balance()
	{
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'main_wallet' => '0.00',
			'bbin_wallet' => '0.00',
			'dg_wallet' => '0.00',
			'evo_wallet' => '0.00',
			'mg_wallet' => '0.00',
			'sa_wallet' => '0.00',
			'sx_wallet' => '0.00',
			'pp_wallet' => '0.00',
			'sxbg_wallet' => '0.00',
			'og_wallet' => '0.00',
			'ezg_wallet' => '0.00',
			'ab_wallet' => '0.00',
			'pt_wallet' => '0.00',
			'eb_wallet' => '0.00',
			'wm_wallet' => '0.00',
			'thab_wallet' => '0.00',
			'thvv_wallet' => '0.00',
			'yb_wallet' => '0.00',
			'ob_wallet' => '0.00',
			'n2_wallet' => '0.00',
			'sxvn_wallet' => '0.00',
			'cq9_wallet' => '0.00',
			'hb_wallet' => '0.00',
			'icg_wallet' => '0.00',
			'ka_wallet' => '0.00',
			'rtg_wallet' => '0.00',
			'sp_wallet' => '0.00',
			'evop_wallet' => '0.00',
			'spn_wallet' => '0.00',
			'f8_wallet' => '0.00',
			'jdb_wallet' => '0.00',
			'ntd_wallet' => '0.00',
			'sxjl_wallet' => '0.00',
			'sxrt_wallet' => '0.00',
			'pgsf_wallet' => '0.00',
			'rlx_wallet' => '0.00',
			'th_wallet' => '0.00',
			'sbo_wallet' => '0.00',
			'ibc_wallet' => '0.00',
			'cmd_wallet' => '0.00',
			'ug_wallet' => '0.00',
			'm8_wallet' => '0.00',
			'lh_wallet' => '0.00',
			'sxes_wallet' => '0.00',
			'le_wallet' => '0.00',
			'ky_wallet' => '0.00',
			'bl_wallet' => '0.00',
			'sxkm_wallet' => '0.00',
			'ig_wallet' => '0.00',
			'vr_wallet' => '0.00',
			'yl_wallet' => '0.00',
			'total' => '0.00',
		);

		if ($this->session->userdata('is_logged_in') == TRUE) {
			//Verify player
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

							$url = HUB_URL;
							$signature = md5($api_data['agent_id'] . $player_data['last_in_game'] . $account_data['username'] . $api_data['secret_key']);

							$param_array = array(
								"method" => 'GetBalance',
								"agent_id" => $api_data['agent_id'],
								"syslang" => $syslang,
								"device" => $device,
								"provider_code" => $player_data['last_in_game'],
								"player_id" => $account_data['player_id'],
								"game_id" => $account_data['game_id'],
								"username" => $account_data['username'],
								"password" => $account_data['password'],
								"signature" => $signature,
							);

							$response = $this->curl_json($url, $param_array);
							$result_array = json_decode($response, TRUE);

							if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
								$balance = ($balance + $result_array['result']);
							}
						}

						$json[strtolower($player_data['last_in_game']) . '_wallet'] = number_format($balance, 2, '.', ',');
						$total_amount = ($total_amount + $balance);
					}

					//Update player online time
					$this->player_model->update_player_online_time($player_data['player_id']);
				}

				$json['status'] = EXIT_SUCCESS;
				$json['msg'] = $this->lang->line('error_get_the_current_balance');
				$json['main_wallet'] = number_format($player_data['points'], 2, '.', ',');
				$json['total'] = number_format($total_amount, 2, '.', ',');
			} else {
				$json['msg'] = $this->lang->line('error_username_not_found');
			}
		} else {
			$json['msg'] = $this->lang->line('error_please_login_to_continue');
		}

		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function get_all_balance()
	{
		$sys_data = $this->miscellaneous_model->get_miscellaneous();
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'main_wallet' => '0.00',
			'ag_wallet' => '0.00',
			'ab_wallet' => '0.00',
			'bbin_wallet' => '0.00',
			'dg_wallet' => '0.00',
			'eb_wallet' => '0.00',
			'ez_wallet' => '0.00',
			'evo_wallet' => '0.00',
			'evo8_wallet' => '0.00',
			'gd_wallet' => '0.00',
			'gpi_wallet' => '0.00',
			'sa_wallet' => '0.00',
			'sx_wallet' => '0.00',
			'wm_wallet' => '0.00',
			'bs_wallet' => '0.00',
			'btx_wallet' => '0.00',
			'cq9_wallet' => '0.00',
			'dt_wallet' => '0.00',
			'gs_wallet' => '0.00',
			'gti_wallet' => '0.00',
			'hb_wallet' => '0.00',
			'jdb_wallet' => '0.00',
			'jk_wallet' => '0.00',
			'mega_wallet' => '0.00',
			'mg_wallet' => '0.00',
			'pp_wallet' => '0.00',
			'pt_wallet' => '0.00',
			'rtg_wallet' => '0.00',
			'sp_wallet' => '0.00',
			'sg_wallet' => '0.00',
			'afb_wallet' => '0.00',
			'cmd_wallet' => '0.00',
			'ibc_wallet' => '0.00',
			'ig_wallet' => '0.00',
			'vr_wallet' => '0.00',
			'gg_wallet' => '0.00',
			'esb_wallet' => '0.00',
			'lh_wallet' => '0.00',
			's128_wallet' => '0.00',
			'le_wallet' => '0.00',
			'ky_wallet' => '0.00',
			'bl_wallet' => '0.00',
			'icg_wallet' => '0.00',
			'ygg_wallet' => '0.00',
			'bg_wallet' => '0.00',
			'im_wallet' => '0.00',
			'n2_wallet' => '0.00',
			'ug_wallet' => '0.00',
			'sbo_wallet' => '0.00',
			'xadj_wallet' => '0.00',
			'ka_wallet' => '0.00',
			'via_wallet' => '0.00',
			'lv22_wallet' => '0.00',
			'xe_wallet' => '0.00',
			'pus8_wallet' => '0.00',
			'total' => '0.00',
		);

		if ($this->session->userdata('is_logged_in') == TRUE) {
			//Verify player
			$player_data = $this->player_model->get_player_data($this->session->userdata('username'));
			if (!empty($player_data)) {
				$total_amount = $player_data['points'];

				if (!empty($player_data['last_in_game'])) {
					$api_data = $this->api_model->get_api_data(SYSTEM_API_AGENT_ID);

					if (!empty($api_data)) {
						//Get balance
						$balance = 0;
						$account_data_list = $this->player_model->get_player_game_account_data_list($player_data['player_id']);
						if (!empty($account_data_list)) {
							foreach ($account_data_list as $account_data) {
								$device = PLATFORM_WEB;
								if ($this->agent->is_mobile()) {
									$device = PLATFORM_MOBILE_WEB;
								}

								$syslang = ((get_language_id(get_language()) == LANG_ZH_CN or get_language_id(get_language()) == LANG_ZH_HK or get_language_id(get_language()) == LANG_ZH_TW) ? LANG_ZH_CN : LANG_EN);

								$url = HUB_URL;
								$signature = md5($api_data['agent_id'] . $account_data['game_provider_code'] . $account_data['username'] . $api_data['secret_key']);

								$param_array = array(
									"method" => 'GetBalance',
									"agent_id" => $api_data['agent_id'],
									"syslang" => $syslang,
									"device" => $device,
									"provider_code" => $account_data['game_provider_code'],
									"player_id" => $account_data['player_id'],
									"game_id" => $account_data['game_id'],
									"username" => $account_data['username'],
									"password" => $account_data['password'],
									"signature" => $signature,
								);

								$response = $this->curl_json($url, $param_array);
								$result_array = json_decode($response, TRUE);

								if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
									$balance = ($balance + $result_array['result']);
								}
								switch ($account_data['game_provider_code']) {
									case 'AB':
										$json['ab_wallet'] = number_format($balance, 2, '.', ',');
										$total_amount = ($total_amount + $json['ab_wallet']);
										break;
									case 'AG':
										$json['ag_wallet'] = number_format($balance, 2, '.', ',');
										$total_amount = ($total_amount + $json['ag_wallet']);
										break;
									case 'BBIN':
										$json['bbin_wallet'] = number_format($balance, 2, '.', ',');
										$total_amount = ($total_amount + $json['bbin_wallet']);
										break;
									case 'CMD':
										$json['cmd_wallet'] = number_format($balance, 2, '.', ',');
										$total_amount = ($total_amount + $json['cmd_wallet']);
										break;
									case 'EVO':
										$json['evo_wallet'] = number_format($balance, 2, '.', ',');
										$total_amount = ($total_amount + $json['evo_wallet']);
										break;
									case 'GPI':
										$json['gpi_wallet'] = number_format($balance, 2, '.', ',');
										$total_amount = ($total_amount + $json['gpi_wallet']);
										break;
									case 'IBC':
										$json['ibc_wallet'] = number_format($balance, 2, '.', ',');
										$total_amount = ($total_amount + $json['ibc_wallet']);
										break;
									case 'LH':
										$json['lh_wallet'] = number_format($balance, 2, '.', ',');
										$total_amount = ($total_amount + $json['lh_wallet']);
										break;
									case 'MG':
										$json['mg_wallet'] = number_format($balance, 2, '.', ',');
										$total_amount = ($total_amount + $json['mg_wallet']);
										break;
									case 'PT':
										$json['pt_wallet'] = number_format($balance, 2, '.', ',');
										$total_amount = ($total_amount + $json['pt_wallet']);
										break;
									case 'SA':
										$json['sa_wallet'] = number_format($balance, 2, '.', ',');
										$total_amount = ($total_amount + $json['sa_wallet']);
										break;
									case 'SG':
										$json['sg_wallet'] = number_format($balance, 2, '.', ',');
										$total_amount = ($total_amount + $json['sg_wallet']);
										break;
									case 'SP':
										$json['sp_wallet'] = number_format($balance, 2, '.', ',');
										$total_amount = ($total_amount + $json['sp_wallet']);
										break;
									case 'SX':
										$json['sx_wallet'] = number_format($balance, 2, '.', ',');
										$total_amount = ($total_amount + $json['sx_wallet']);
										break;
									case 'WM':
										$json['wm_wallet'] = number_format($balance, 2, '.', ',');
										$total_amount = ($total_amount + $json['wm_wallet']);
										break;
								}
							}
						}
					}

					//Update player online time
					$this->player_model->update_player_online_time($player_data['player_id']);
				}

				$json['status'] = EXIT_SUCCESS;
				$json['msg'] = $this->lang->line('error_get_the_current_balance');
				$json['main_wallet'] = number_format($player_data['points'], 2, '.', ',');
				$json['total'] = number_format($total_amount, 2, '.', ',');
			} else {
				$json['msg'] = $this->lang->line('error_username_not_found');
			}
		} else {
			$json['msg'] = $this->lang->line('error_please_login_to_continue');
		}

		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function transfer_all()
	{
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'bbin_wallet' => '0.00',
			'dg_wallet' => '0.00',
			'evo_wallet' => '0.00',
			'mg_wallet' => '0.00',
			'sa_wallet' => '0.00',
			'sx_wallet' => '0.00',
			'pp_wallet' => '0.00',
			'sxbg_wallet' => '0.00',
			'og_wallet' => '0.00',
			'ezg_wallet' => '0.00',
			'ab_wallet' => '0.00',
			'pt_wallet' => '0.00',
			'eb_wallet' => '0.00',
			'wm_wallet' => '0.00',
			'thab_wallet' => '0.00',
			'thvv_wallet' => '0.00',
			'yb_wallet' => '0.00',
			'ob_wallet' => '0.00',
			'n2_wallet' => '0.00',
			'sxvn_wallet' => '0.00',
			'cq9_wallet' => '0.00',
			'hb_wallet' => '0.00',
			'icg_wallet' => '0.00',
			'ka_wallet' => '0.00',
			'rtg_wallet' => '0.00',
			'sp_wallet' => '0.00',
			'evop_wallet' => '0.00',
			'spn_wallet' => '0.00',
			'f8_wallet' => '0.00',
			'jdb_wallet' => '0.00',
			'ntd_wallet' => '0.00',
			'sxjl_wallet' => '0.00',
			'sxrt_wallet' => '0.00',
			'pgsf_wallet' => '0.00',
			'rlx_wallet' => '0.00',
			'th_wallet' => '0.00',
			'sbo_wallet' => '0.00',
			'ibc_wallet' => '0.00',
			'cmd_wallet' => '0.00',
			'ug_wallet' => '0.00',
			'm8_wallet' => '0.00',
			'lh_wallet' => '0.00',
			'sxes_wallet' => '0.00',
			'le_wallet' => '0.00',
			'ky_wallet' => '0.00',
			'bl_wallet' => '0.00',
			'sxkm_wallet' => '0.00',
			'ig_wallet' => '0.00',
			'vr_wallet' => '0.00',
			'yl_wallet' => '0.00',
			'total' => '0.00',
		);

		if ($this->session->userdata('is_logged_in') == TRUE) {
			//Verify player
			$player_data = $this->player_model->get_player_data($this->session->userdata('username'));
			if (!empty($player_data)) {
				$total_amount = $player_data['points'];

				if (!empty($player_data['last_in_game'])) {
					$api_data = $this->api_model->get_api_data(SYSTEM_API_AGENT_ID);

					if (!empty($api_data)) {
						//Get balance, withdraw and logout from previous game
						$account_data = $this->player_model->get_player_game_account_data($player_data['last_in_game'], $player_data['player_id']);
						if (!empty($account_data)) {
							$device = PLATFORM_WEB;
							if ($this->agent->is_mobile()) {
								$device = PLATFORM_MOBILE_WEB;
							}

							$syslang = ((get_language_id(get_language()) == LANG_ZH_CN or get_language_id(get_language()) == LANG_ZH_HK or get_language_id(get_language()) == LANG_ZH_TW) ? LANG_ZH_CN : LANG_EN);

							$url = HUB_URL;
							$signature = md5($api_data['agent_id'] . $player_data['last_in_game'] . $account_data['username'] . $api_data['secret_key']);

							$param_array = array(
								"agent_id" => $api_data['agent_id'],
								"syslang" => $syslang,
								"device" => $device,
								"provider_code" => $player_data['last_in_game'],
								"player_id" => $account_data['player_id'],
								"game_id" => $account_data['game_id'],
								"username" => $account_data['username'],
								"password" => $account_data['password'],
								"signature" => $signature,
							);

							//Get balance
							$balance = 0;
							$param_array['method'] = 'GetBalance';
							$response = $this->curl_json($url, $param_array);
							$result_array = json_decode($response, TRUE);

							if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
								$balance = $result_array['result'];
							}

							if ($balance > 0) {
								//Withdraw credit
								$param_array['method'] = 'ChangeBalance';
								$param_array['order_id'] = 'OUT' . date("YmdHis") . $account_data['username'];
								$param_array['amount'] = ($balance * -1);
								$response = $this->curl_json($url, $param_array);
								$result_array = json_decode($response, TRUE);

								if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
									//update wallet
									$newData = $this->player_model->point_transfer($player_data, $balance, $player_data['username']);
									$this->player_model->insert_log(LOG_WALLET_TRANSFER, $device, $newData, $player_data);
									$this->general_model->insert_game_transfer_report($player_data['last_in_game'], 'MAIN', $balance, $player_data['points'], $balance, $player_data['player_id'], (isset($result_array['orderID']) ? trim($result_array['orderID']) : ''), (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
								} else if (isset($result_array['errorCode']) && $result_array['errorCode'] == '201') {
									//Overtime
									$newData = $this->general_model->insert_game_transfer_pending_report($player_data['last_in_game'], 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $balance, $player_data['player_id'], (isset($result_array['orderID']) ? trim($result_array['orderID']) : ''), (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
									$this->player_model->insert_log(LOG_WALLET_TRANSFER_PENDING, $device, $newData);
								} else if (isset($result_array['errorCode'])) {
								} else {
									$newData = $this->general_model->insert_game_transfer_pending_report($player_data['last_in_game'], 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $balance, $player_data['player_id'], (isset($result_array['orderID']) ? trim($result_array['orderID']) : ''), (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
									$this->player_model->insert_log(LOG_WALLET_TRANSFER_PENDING, $device, $newData);
									$this->general_model->insert_api_game_api_unnormal_log($provider_code, $player_data['player_id'], TRANSFER_TRANSACTION_OUT, $param_array, $result_array, $response);
								}
							}

							//Logout game
							$param_array['method'] = 'LogoutGame';
							$this->curl_json($url, $param_array);

							$total_amount = ($total_amount + $balance);
						}
					}
				}

				$json['status'] = EXIT_SUCCESS;
				$json['msg'] = $this->lang->line('error_get_the_current_balance');
				$json['main_wallet'] = number_format($total_amount, 2, '.', ',');
				$json['total'] = number_format($total_amount, 2, '.', ',');
			} else {
				$json['msg'] = $this->lang->line('error_username_not_found');
			}
		} else {
			$json['msg'] = $this->lang->line('error_please_login_to_continue');
		}

		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function change_password()
	{
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);

		if ($this->session->userdata('is_logged_in') == TRUE) {
			$config = array(
				array(
					'field' => 'oldpass',
					'label' => strtolower($this->lang->line('label_current_password')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_current_password_empty')
					)
				),
				array(
					'field' => 'password',
					'label' => strtolower($this->lang->line('label_new_password')),
					'rules' => 'trim|required|differs[oldpass]|min_length[6]|max_length[15]|regex_match[/^[A-Za-z0-9!#$^*]+$/]',
					'errors' => array(
						'required' => $this->lang->line('error_new_password_empty'),
						'differs' => $this->lang->line('error_new_password_must_differ'),
						'min_length' => $this->lang->line('error_new_password_incorrect'),
						'max_length' => $this->lang->line('error_new_password_incorrect'),
						'regex_match' => $this->lang->line('error_new_password_incorrect')
					)
				),
				array(
					'field' => 'passconf',
					'label' => strtolower($this->lang->line('label_confirm_new_password')),
					'rules' => 'trim|required|matches[password]',
					'errors' => array(
						'required' => $this->lang->line('error_confirm_new_password_empty'),
						'matches' => $this->lang->line('error_confirm_new_password_not_match')
					)
				)
			);

			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');

			//Form validation
			if ($this->form_validation->run() == TRUE) {
				$device = PLATFORM_WEB;
				if ($this->agent->is_mobile()) {
					$device = PLATFORM_MOBILE_WEB;
				}

				$data = array(
					'oldpass' => $this->input->post('oldpass', TRUE),
					'password' => $this->input->post('password', TRUE),
					'player_id' => $this->session->userdata('player_id'),
					'username' => $this->session->userdata('username')
				);

				//Verify player
				$player_data = $this->player_model->get_player_data($data['username']);
				if (!empty($player_data)) {
					//Verify password
					$response = FALSE;
					$response = $this->player_model->verify_current_password($data);
					if ($response == TRUE) {
						//Database update
						$this->db->trans_start();

						$this->player_model->update_player_password($data);
						$this->player_model->insert_log(LOG_CHANGE_PASSWORD, $device, $data);

						$this->db->trans_complete();

						if ($this->db->trans_status() === TRUE) {
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('error_change_password_successful');
						} else {
							$json['msg'] = $this->lang->line('error_failed_to_update');
						}
					} else {
						$json['msg'] = $this->lang->line('error_current_password_incorrect');
					}
				} else {
					$json['msg'] = $this->lang->line('error_username_not_found');
				}
			} else {
				$error = array(
					'oldpass' => form_error('oldpass'),
					'password' => form_error('password'),
					'passconf' => form_error('passconf')
				);

				if (!empty($error['oldpass'])) {
					$json['msg'] = $error['oldpass'];
				} else if (!empty($error['password'])) {
					$json['msg'] = $error['password'];
				} else if (!empty($error['passconf'])) {
					$json['msg'] = $error['passconf'];
				}
			}
		} else {
			$json['msg'] = $this->lang->line('error_please_login_to_continue');
		}

		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function deposit()
	{
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);

		if ($this->session->userdata('is_logged_in') == TRUE) {
			$sys_data = $this->miscellaneous_model->get_miscellaneous();

			$deposit_type = $this->input->post('deposit_type', TRUE);

			if ($deposit_type == DEPOSIT_OFFLINE_BANKING) {
				//Offline deposit
				$config = array(
					array(
						'field' => 'currency_id',
						'label' => strtolower($this->lang->line('label_currency')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_currency_empty')
						)
					),
					array(
						'field' => 'player_bank_id',
						'label' => strtolower($this->lang->line('label_bank_name')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_player_bank_account_empty')
						)
					),
					array(
						'field' => 'bank_account_id',
						'label' => strtolower($this->lang->line('label_bank_account')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_bank_account_empty')
						)
					),
					array(
						'field' => 'amount',
						'label' => strtolower($this->lang->line('label_amount')),
						'rules' => 'trim|required|greater_than_equal_to[' . $sys_data['min_deposit'] . ']|less_than_equal_to[' . $sys_data['max_deposit'] . ']',
						'errors' => array(
							'required' => $this->lang->line('error_amount_empty'),
							'greater_than_equal_to' => str_replace('%s', $sys_data['min_deposit'], $this->lang->line('error_min_deposit')),
							'less_than_equal_to' => str_replace('%s', $sys_data['max_deposit'], $this->lang->line('error_max_deposit')),
						)
					),
					array(
						'field' => 'bank_in_date',
						'label' => strtolower($this->lang->line('label_bank_in_date')),
						'rules' => 'trim|required|callback_date_check',
						'rules' => 'trim',
						'errors' => array(
							'required' => $this->lang->line('error_bank_in_date_empty'),
							'date_check' => $this->lang->line('error_bank_in_date_incorrect')
						)
					),
					array(
						'field' => 'bank_in_hour',
						'label' => strtolower($this->lang->line('label_bank_in_hour')),
						'rules' => 'trim|required|greater_than_equal_to[0]|less_than_equal_to[23]',
						'rules' => 'trim',
						'errors' => array(
							'required' => $this->lang->line('error_bank_in_hour_empty'),
							'greater_than_equal_to' => $this->lang->line('error_bank_in_hour_incorrect'),
							'less_than_equal_to' => $this->lang->line('error_bank_in_hour_incorrect')
						)
					),
					array(
						'field' => 'bank_in_minute',
						'label' => strtolower($this->lang->line('label_bank_in_minute')),
						'rules' => 'trim|required|greater_than_equal_to[0]|less_than_equal_to[59]',
						'rules' => 'trim',
						'errors' => array(
							'required' => $this->lang->line('error_bank_in_minute_empty'),
							'greater_than_equal_to' => $this->lang->line('error_bank_in_minute_incorrect'),
							'less_than_equal_to' => $this->lang->line('error_bank_in_minute_incorrect')
						)
					),
				);

				$this->form_validation->set_rules($config);
				$this->form_validation->set_error_delimiters('', '');

				//Form validation
				if ($this->form_validation->run() == TRUE) {
					$device = PLATFORM_WEB;
					if ($this->agent->is_mobile()) {
						$device = PLATFORM_MOBILE_WEB;
					}

					$data = array(
						'transaction_code' => (($this->input->post('deposit_code', TRUE)) ? $this->input->post('deposit_code', TRUE) . rand(1000, 9999) : 'D' . $this->session->userdata('player_id') . time() . rand(1000, 9999)),
						'currency_id' => $this->input->post('currency_id', TRUE),
						'bank_account_id' => $this->input->post('bank_account_id', TRUE),
						'amount' => $this->input->post('amount', TRUE),
						'amount_apply' => $this->input->post('amount', TRUE),
						'bank_in_date' => time(),
						'player_id' => $this->session->userdata('player_id'),
						'username' => $this->session->userdata('username'),
						'promotion_id' => $this->input->post('promoId', TRUE),
						'promotion_name' => "",
						'bank_slip' => "",
						'remark' => $this->input->post('remark', TRUE),
					);

					$allow_to_add = TRUE;
					$config['upload_path'] = BANK_RECEIPT_PATH;
					$config['max_size'] = BANK_RECEIPT_FILE_SIZE;
					$config['allowed_types'] = 'jpg|jpeg|png';
					$config['overwrite'] = TRUE;
					$this->load->library('upload', $config);
					if (isset($_FILES['bank_slip']['size']) && $_FILES['bank_slip']['size'] > 0) {
						$new_name = md5($this->session->userdata('player_id') . 'deposit_slip' . $this->config->item('encryption_key') . time()) . "." . pathinfo($_FILES["bank_slip"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if (!$this->upload->do_upload('bank_slip')) {
							$json['msg'] = $this->lang->line('error_invalid_filetype');
							$allow_to_add = FALSE;
						} else {
							$_FILES["bank_slip"]['name'] = $new_name;
							$data['bank_slip'] = $new_name;
						}
					} else {
						$allow_to_add = FALSE;
						$json['msg'] = $this->lang->line('error_receipt_empty');
					}
					if ($allow_to_add) {
						//Verify player
						$player_data = $this->player_model->get_player_data($data['username']);
						if (!empty($player_data)  && $player_data['player_type'] == PLAYER_TYPE_CASH_MARKET) {
							//Verify bank account
							$bank_account_data = $this->bank_model->get_bank_account_data($data['bank_account_id']);
							if (!empty($bank_account_data)) {
								$data['bank_name'] = $bank_account_data['bank_name'];
								$data['bank_account_name'] = $bank_account_data['bank_account_name'];
								$data['bank_account_no'] = $bank_account_data['bank_account_no'];
								$data['player_bank_name'] = "";
								$data['player_bank_account_name'] = "";
								$data['player_bank_account_no'] = "";
								$data['player_bank_account_address'] = "";
								$player_bank = $this->input->post('player_bank_id', TRUE);
								$allow_to_add = TRUE;

								/*Player Bank Verification*/
								if (!empty($player_bank)) {
									$player_bank_data = $this->bank_model->get_player_bank_list($player_bank);
									if (empty($player_bank_data)) {
										$json['msg'] = $this->lang->line('error_bank_unavailable');
										$allow_to_add = FALSE;
									} else {
										$data['player_bank_name'] = $player_bank_data['bank_name'];
										$data['player_bank_account_name'] = $player_bank_data['bank_account_name'];
										$data['player_bank_account_no'] = $player_bank_data['bank_account_no'];
										$data['player_bank_account_address'] = $player_bank_data['bank_account_address'];
									}
								}

								/*Promotion Verification*/
								if (!empty($data['promotion_id'])) {
									$data['dob'] = $player_data['dob'];
									if ($player_data['is_promotion'] == STATUS_ACTIVE) {
										//$get_member_total_wallet  = $this->get_member_total_wallet($player_data);
										$get_member_total_wallet = array(
											'balance_valid' => 1,
											'balance_amount' => 0,
										);
										$promotion_response = NULL;
										if ($player_data['promotion_type'] == PROMOTION_TYPE_STRICT_BASED) {
											$promotion_response = $this->promotion_model->deposit_promotion_strict_apply_decision($data, $get_member_total_wallet);
										} else {
											$promotion_response = $this->promotion_model->deposit_promotion_unstrict_apply_decision($data, $get_member_total_wallet);
										}

										if ($promotion_response['status'] != EXIT_SUCCESS) {
											$allow_to_add = FALSE;
											$json['msg'] = $promotion_response['msg'];
										} else {
											$data['promotion_name'] = $promotion_response['promotion_name'];
										}
									} else {
										$allow_to_add = FALSE;
										$json['msg'] = $this->lang->line('error_promotion_not_allow');
									}
								}

								if ($allow_to_add) {
									$currency_data = $this->general_model->get_currency_data($data['currency_id']);
									if (!empty($currency_data)) {
										$allowed_add = true;
										$data['currency_code'] = $currency_data['currency_code'];
										$data['currency_rate'] = $currency_data['d_currency_rate'];
										$data['amount_rate'] = bcdiv($data['amount'] * $currency_data['d_currency_rate'], 1, 2);
									} else {
										$json['msg'] = $this->lang->line('error_currency_empty');
									}
								}

								//Verify bank
								if ($allow_to_add) {
									//Database update
									$this->db->trans_start();

									$newData = $this->deposit_model->add_offline_deposit($data);
									if (!empty($data['promotion_id'])) {
										$this->promotion_model->add_player_promotion($newData, $get_member_total_wallet);
									}
									$this->bank_model->update_bank_account_limit_usage($data);
									$this->player_model->insert_log(LOG_DEPOSIT, $device, $data);

									$this->db->trans_complete();

									if ($this->db->trans_status() === TRUE) {
										$json['status'] = EXIT_SUCCESS;
										$json['msg'] = $this->lang->line('error_submitted_successful');
									} else {
										$json['msg'] = $this->lang->line('error_failed_to_update');
									}
								}
							} else {
								$json['msg'] = $this->lang->line('error_bank_account_unavailable');
							}
						} else {
							$json['msg'] = $this->lang->line('error_username_not_found');
						}
					}
				} else {
					$error = array(
						'currency_id' => form_error('currency_id'),
						'bank_account_id' => form_error('bank_account_id'),
						'amount' => form_error('amount'),
						'bank_in_date' => form_error('bank_in_date'),
						'bank_in_hour' => form_error('bank_in_hour'),
						'bank_in_minute' => form_error('bank_in_minute')
					);

					if (!empty($error['bank_account_id'])) {
						$json['msg'] = $error['bank_account_id'];
					} else if (!empty($error['currency_id'])) {
						$json['msg'] = $error['currency_id'];
					} else if (!empty($error['amount'])) {
						$json['msg'] = $error['amount'];
					} else if (!empty($error['bank_in_date'])) {
						$json['msg'] = $error['bank_in_date'];
					} else if (!empty($error['bank_in_hour'])) {
						$json['msg'] = $error['bank_in_hour'];
					} else if (!empty($error['bank_in_minute'])) {
						$json['msg'] = $error['bank_in_minute'];
					}
				}
			} else {
				//Online deposit
				$payment_gateway_minmaxAmount_data = $this->payment_gateway_model->get_payment_gateway_data_by_type($this->input->post('payment_gateway_code', TRUE), $deposit_type);
				$config = array(
					array(
						'field' => 'payment_gateway_code',
						'label' => strtolower($this->lang->line('label_payment_gateway')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_payment_gateway_empty')
						)
					),
					array(
						'field' => 'amount',
						'label' => strtolower($this->lang->line('label_amount')),
						'rules' => 'trim|required|greater_than_equal_to[' . floatval(isset($payment_gateway_minmaxAmount_data['payment_gateway_min_amount']) ? $payment_gateway_minmaxAmount_data['payment_gateway_min_amount']: $sys_data['min_deposit']) . ']|less_than_equal_to[' . floatval(isset($payment_gateway_minmaxAmount_data['payment_gateway_max_amount']) ? $payment_gateway_minmaxAmount_data['payment_gateway_max_amount']: $sys_data['max_deposit']) . ']',
						'errors' => array(
							'required' => $this->lang->line('error_amount_empty'),
							'greater_than_equal_to' => str_replace('%s',isset($payment_gateway_minmaxAmount_data['payment_gateway_min_amount']) ? $payment_gateway_minmaxAmount_data['payment_gateway_min_amount']: $sys_data['min_deposit'], $this->lang->line('error_min_deposit')),
							'less_than_equal_to' => str_replace('%s', isset($payment_gateway_minmaxAmount_data['payment_gateway_max_amount']) ? $payment_gateway_minmaxAmount_data['payment_gateway_max_amount']: $sys_data['max_deposit'], $this->lang->line('error_max_deposit')),
						)
					),
					array(
						'field' => 'bank_in_date',
						'label' => strtolower($this->lang->line('label_bank_in_date')),
						'rules' => 'trim',
						//'rules' => 'trim|required|callback_date_check',
						'errors' => array(
							'required' => $this->lang->line('error_bank_in_date_empty'),
							'date_check' => $this->lang->line('error_bank_in_date_incorrect')
						)
					),
					array(
						'field' => 'bank_in_hour',
						'label' => strtolower($this->lang->line('label_bank_in_hour')),
						'rules' => 'trim',
						//'rules' => 'trim|required|greater_than_equal_to[0]|less_than_equal_to[23]',
						'errors' => array(
							'required' => $this->lang->line('error_bank_in_hour_empty'),
							'greater_than_equal_to' => $this->lang->line('error_bank_in_hour_incorrect'),
							'less_than_equal_to' => $this->lang->line('error_bank_in_hour_incorrect')
						)
					),
					array(
						'field' => 'bank_in_minute',
						'label' => strtolower($this->lang->line('label_bank_in_minute')),
						'rules' => 'trim',
						//'rules' => 'trim|required|greater_than_equal_to[0]|less_than_equal_to[59]',
						'errors' => array(
							'required' => $this->lang->line('error_bank_in_minute_empty'),
							'greater_than_equal_to' => $this->lang->line('error_bank_in_minute_incorrect'),
							'less_than_equal_to' => $this->lang->line('error_bank_in_minute_incorrect')
						)
					),
				);

				$this->form_validation->set_rules($config);
				$this->form_validation->set_error_delimiters('', '');

				//Form validation
				if ($this->form_validation->run() == TRUE) {
					$payment_gateway_code = $this->input->post('payment_gateway_code', TRUE);
					$device = PLATFORM_WEB;
					if ($this->agent->is_mobile()) {
						$device = PLATFORM_MOBILE_WEB;
					}

					$data = array(
						'transaction_code' => (($this->input->post('deposit_code', TRUE)) ? $this->input->post('deposit_code', TRUE) . rand(1000, 9999) : 'D' . $this->session->userdata('player_id') . time() . rand(1000, 9999)),
						'amount_apply' => bcdiv($this->input->post('amount', TRUE), 1, 2),
						'deposit_type' => $deposit_type,
						'bank_in_date' => time(),
						'bank_in_date' => strtotime($this->input->post('bank_in_date', TRUE) . ' ' . $this->input->post('bank_in_hour', TRUE) . ':' . $this->input->post('bank_in_minute', TRUE) . ':00'),
						'player_id' => $this->session->userdata('player_id'),
						'username' => $this->session->userdata('username'),
						'promotion_id' => $this->input->post('promoId', TRUE),
						'promotion_name' => "",
						'payment_gateway_id' => "",
						'payment_gateway_bank' => $this->input->post('payment_gateway_bank', TRUE),
						'currency_id' => 0,
						'currency_rate' => 0,
						'currency_code' => "",
						'rate' => 0,
						'rate_amount' => 0,
						'amount' => bcdiv($this->input->post('amount', TRUE), 1, 2),
					);


					//Verify player
					$player_data = $this->player_model->get_player_data($data['username']);

					if (!empty($player_data) && $player_data['player_type'] == PLAYER_TYPE_CASH_MARKET) {
						$payment_gateway_data = $this->payment_gateway_model->get_payment_gateway_data_by_type($payment_gateway_code, $deposit_type);
						if (!empty($payment_gateway_data)) {
							$data['payment_gateway_id'] = $payment_gateway_data['payment_gateway_id'];
							$current_time = time();
							$from_time = strtotime(date('Y-m-d') . ' ' . $payment_gateway_data['fixed_from_time']);
							$to_time = strtotime(date('Y-m-d') . ' ' . $payment_gateway_data['fixed_to_time']);
							if (
								$payment_gateway_data['is_maintenance'] == STATUS_YES or
								($payment_gateway_data['fixed_maintenance'] == STATUS_YES && $payment_gateway_data['fixed_day'] == date('N') && $current_time >= $from_time && $current_time <= $to_time) or
								($payment_gateway_data['urgent_maintenance'] == STATUS_YES && $current_time >= $payment_gateway_data['urgent_date'])
							) {
								$json['msg'] = $this->lang->line('error_payment_gateway_maintenance');
							} else {
								$allow_to_add = TRUE;
								$currency_data = $this->general_model->get_currency_data($payment_gateway_data['payment_gateway_currency_id']);

								if (!empty($currency_data)) {
									$data['currency_id'] = $currency_data['currency_id'];
									$data['currency_rate'] = $currency_data['d_currency_rate'];
									$data['currency_code'] = $currency_data['currency_code'];
									if ($payment_gateway_data['is_select_bank']) {
										if (empty($data['payment_gateway_bank'])) {
											$allow_to_add = FALSE;
											$json['msg'] = $this->lang->line('error_payment_gateway_bank_empty');
										} else {
											$payment_gateway_bank_data = explode(',', $payment_gateway_data['bank_data']);
											$payment_gateway_bank_data = array_values(array_filter($payment_gateway_bank_data));
											if (!in_array($data['payment_gateway_bank'], $payment_gateway_bank_data)) {
												$allow_to_add = FALSE;
												$json['msg'] = $this->lang->line('error_payment_gateway_bank_empty');
											}
										}
									}

									if ($allow_to_add) {
										if (!empty($data['promotion_id'])) {
											$data['dob'] = $player_data['dob'];
											if ($player_data['is_promotion'] == STATUS_ACTIVE) {
												//$get_member_total_wallet  = $this->get_member_total_wallet($player_data);
												$get_member_total_wallet  = array(
													'balance_valid' => 1,
													'balance_amount' => 0,
												);
												$promotion_response = NULL;
												if ($player_data['promotion_type'] == PROMOTION_TYPE_STRICT_BASED) {
													$promotion_response = $this->promotion_model->deposit_promotion_strict_apply_decision($data, $get_member_total_wallet);
												} else {
													$promotion_response = $this->promotion_model->deposit_promotion_unstrict_apply_decision($data, $get_member_total_wallet);
												}

												if ($promotion_response['status'] != EXIT_SUCCESS) {
													$allow_to_add = FALSE;
													$json['msg'] = $promotion_response['msg'];
												} else {
													$data['promotion_name'] = $promotion_response['promotion_name'];
												}
											} else {
												$allow_to_add = FALSE;
												$json['msg'] = $this->lang->line('error_promotion_not_allow');
											}
										}
									}

									if ($allow_to_add) {
										$data['rate'] = $payment_gateway_data['payment_gateway_rate'];
										$data['rate_amount'] = bcdiv((($data['amount_apply'] * $payment_gateway_data['payment_gateway_rate']) / 100), 1, 2);
										$data['amount'] = $data['amount_apply'] - $data['rate_amount'];
										$data['amount_rate'] = bcdiv($data['amount'] * $currency_data['d_currency_rate'], 1, 2);
										if ($allow_to_add) {
											//Database update
											$this->db->trans_start();
											$newData = $this->deposit_model->add_online_deposit($data);
											if (!empty($data['promotion_id'])) {
												$this->promotion_model->add_player_promotion($newData, $get_member_total_wallet);
											}
											$this->session->set_userdata('pg_deposit_id', $newData['deposit_id']);
											$this->player_model->insert_log(LOG_DEPOSIT, $device, $data);
											$this->db->trans_complete();
											if ($this->db->trans_status() === TRUE) {
												$json['status'] = EXIT_CONFIG;
												$json['msg'] = $this->lang->line('error_submitted_successful');
												$json['url'] = base_url('gateway/' . $payment_gateway_data['forward_url']);
											} else {
												$json['msg'] = $this->lang->line('error_failed_to_update');
											}
										}
									}
								} else {
									$json['msg'] = $this->lang->line('error_currency_empty');
								}
							}
						} else {
							$json['msg'] = $this->lang->line('error_payment_gateway_incorrect');
						}
					} else {
						$json['msg'] = $this->lang->line('error_username_not_found');
					}
				} else {
					$error = array(
						'amount' => form_error('amount'),
						'bank_in_date' => form_error('bank_in_date'),
						'bank_in_hour' => form_error('bank_in_hour'),
						'bank_in_minute' => form_error('bank_in_minute'),
						'payment_gateway_code' => form_error('payment_gateway_code'),
					);

					if (!empty($error['amount'])) {
						$json['msg'] = $error['amount'];
					} else if (!empty($error['bank_in_date'])) {
						$json['msg'] = $error['bank_in_date'];
					} else if (!empty($error['bank_in_hour'])) {
						$json['msg'] = $error['bank_in_hour'];
					} else if (!empty($error['bank_in_minute'])) {
						$json['msg'] = $error['bank_in_minute'];
					} else if (!empty($error['payment_gateway_code'])) {
						$json['msg'] = $error['payment_gateway_code'];
					}
				}
			}
		} else {
			$json['msg'] = $this->lang->line('error_please_login_to_continue');
		}

		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function withdrawal()
	{
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);

		if ($this->session->userdata('is_logged_in') == TRUE) {
			$wallet_username = $this->session->userdata('username');
			$wallet_data = $this->player_model->get_player_wallet_lock($wallet_username);
			if (!empty($wallet_data) && $wallet_data['wallet_lock'] == WALLET_UNLOCK) {
				// $this->player_model->update_player_wallet_lock($wallet_username, WALLET_LOCK);
				$sys_data = $this->miscellaneous_model->get_miscellaneous();

				$config = array(
					/*
					array(
							'field' => 'bank_id',
							'label' => strtolower($this->lang->line('label_bank_name')),
							'rules' => 'trim|required',
							'errors' => array(
												'required' => $this->lang->line('error_bank_empty')
										)
					),
					array(
							'field' => 'bank_account_name',
							'label' => strtolower($this->lang->line('label_bank_account_name')),
							'rules' => 'trim|required',
							'errors' => array(
												'required' => $this->lang->line('error_bank_account_name_empty')
										)
					),
					array(
							'field' => 'bank_account_no',
							'label' => strtolower($this->lang->line('label_bank_account_no')),
							'rules' => 'trim|required',
							'errors' => array(
												'required' => $this->lang->line('error_bank_account_no_empty')
										)
					),
					*/
					array(
						'field' => 'currency_id',
						'label' => strtolower($this->lang->line('label_currency')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_currency_empty')
						)
					),
					/*
					array(
							'field' => 'player_bank_id',
							'label' => strtolower($this->lang->line('label_bank_name')),
							'rules' => 'trim|required',
							'errors' => array(
												'required' => $this->lang->line('error_bank_empty')
										)
					),
					*/
					array(
						'field' => 'amount',
						'label' => strtolower($this->lang->line('label_amount')),
						'rules' => 'trim|required|greater_than_equal_to[' . $sys_data['min_withdrawal'] . ']|less_than_equal_to[' . $sys_data['max_withdrawal'] . ']',
						'errors' => array(
							'required' => $this->lang->line('error_amount_empty'),
							'greater_than_equal_to' => str_replace('%s', $sys_data['min_withdrawal'], $this->lang->line('error_min_withdrawal')),
							'less_than_equal_to' => str_replace('%s', $sys_data['max_withdrawal'], $this->lang->line('error_max_withdrawal')),
						)
					),
					array(
						'field' => 'password',
						'label' => strtolower($this->lang->line('label_password')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_password_empty')
						)
					),
				);

				$this->form_validation->set_rules($config);
				$this->form_validation->set_error_delimiters('', '');

				//Form validation
				if ($this->form_validation->run() == TRUE) {
					$device = PLATFORM_APP_ANDROID;
					/*
					$device = PLATFORM_WEB;
					if($this->agent->is_mobile())
					{
						$device = PLATFORM_MOBILE_WEB;
					}
					*/

					$syslang = ((get_language_id(get_language()) == LANG_ZH_CN or get_language_id(get_language()) == LANG_ZH_HK or get_language_id(get_language()) == LANG_ZH_TW) ? LANG_ZH_CN : LANG_EN);

					$player_bank_data = $this->bank_model->get_player_bank_list($this->input->post('player_bank_id', TRUE));

					

					if (!empty($player_bank_data)) {

						// Check user have promotion or not

						// echo "checkWithdrawalByPromotion";die;
						// echo "player_bank_data".$player_bank_data;die;
						// print_r($player_bank_data['player_id']);die;
						$flag = $this->checkWithdrawalByPromotion($player_bank_data);
									
						if($flag == false){

							// echo "flag: ".$flag;die;
		
							$json['msg'] = $this->lang->line('error_failed_to_approve_promotion_before_approving_withdrawal');
							$this->output
								->set_status_header(200)
								->set_content_type('application/json', 'utf-8')
								->set_output(json_encode($json))
								->_display();
							unset($json);
							exit();	
						}

						$data = array(
							'transaction_code' => 'W' . $this->session->userdata('player_id') . time() . rand(1000, 9999),
							'currency_id' => $this->input->post('currency_id', TRUE),
							'bank_id' => $player_bank_data['bank_id'],
							'bank_account_name' => $player_bank_data['bank_account_name'],
							'bank_account_no' => $player_bank_data['bank_account_no'],
							'bank_account_address' => $player_bank_data['bank_account_address'],
							'amount' => $this->input->post('amount', TRUE),
							'oldpass' => $this->input->post('password', TRUE),
							'player_id' => $this->session->userdata('player_id'),
							'username' => $this->session->userdata('username')
						);

						//Verify player
						$player_data = $this->player_model->get_player_data($data['username']);
						if (!empty($player_data) && ($player_data['player_type'] == PLAYER_TYPE_CASH_MARKET || $player_data['player_type'] == PLAYER_TYPE_MG_MARKET)) {
							//Verify password
							$response = FALSE;
							$response = $this->player_model->verify_current_password($data);
							if ($response == TRUE) {
								$allowed_add = false;
								$currency_data = $this->general_model->get_currency_data($data['currency_id']);
								if (!empty($currency_data)) {
									$allowed_add = true;
									$data['currency_code'] = $currency_data['currency_code'];
									$data['currency_rate'] = $currency_data['w_currency_rate'];
									$data['handling_fee'] = $currency_data['w_fee'];
									$data['amount_rate'] = bcdiv(($data['amount'] * $currency_data['w_currency_rate']) - $currency_data['w_fee'], 1, 2);
								} else {
									$json['msg'] = $this->lang->line('error_currency_empty');
								}

								//Verify promotion
								if ($allowed_add) {
									$verify_promotion = $this->promotion_model->check_promotion_allow_withdrawal($data['player_id']);
									if (!$verify_promotion['status']) {
										$allowed_add = FALSE;
										$json['msg'] = $this->lang->line('error_not_reach_turnover');
									} else { 
										if($verify_promotion['method'] == 1){ #NO PROMOTION
											#GET 1x turnover#
											$verify_turnover 	= $this->promotion_model->verify_turnover($data['player_id']);
											if($verify_turnover['status']) {
												$allowed_add = TRUE;
											} else {
												$allowed_add = FALSE;
												$message 		= $this->lang->line('label_turnover').' : '.$verify_turnover['target'].'<br/>';
												$message 		.= $this->lang->line('label_balance').' : '.$verify_turnover['need'];
												$json['msg'] 	= $message;
											}
										}
									}
								}

								//Verify bank
								if ($allowed_add) {
									$bank_data = $this->bank_model->get_bank_data($data['bank_id']);
									if (!empty($bank_data)) {
										$data['bank_name'] = $bank_data['bank_name'];

										$api_data = $this->api_model->get_api_data(SYSTEM_API_AGENT_ID);

										$url = HUB_URL;

										//Get balance and withdraw from previous game
										if (!empty($player_data['last_in_game'])) {
											$account_data = $this->player_model->get_player_game_account_data($player_data['last_in_game'], $player_data['player_id']);
											if (!empty($account_data)) {
												$balance = 0;
												$signature = md5($api_data['agent_id'] . $player_data['last_in_game'] . $account_data['username'] . $api_data['secret_key']);

												$param_array = array(
													"agent_id" => $api_data['agent_id'],
													"syslang" => $syslang,
													"device" => $device,
													"provider_code" => $player_data['last_in_game'],
													"player_id" => $account_data['player_id'],
													"game_id" => $account_data['game_id'],
													"username" => $account_data['username'],
													"password" => $account_data['password'],
													"signature" => $signature,
												);

												//Get balance
												$param_array['method'] = 'GetBalance';
												$response = $this->curl_json($url, $param_array);
												$result_array = json_decode($response, TRUE);

												if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
													$balance = $result_array['result'];
												}

												if ($balance > 0) {
													//Withdraw credit
													$param_array['method'] = 'ChangeBalance';
													$param_array['order_id'] = 'OUT' . date("YmdHis") . $account_data['username'];
													$param_array['amount'] = ($balance * -1);
													$response = $this->curl_json($url, $param_array);
													$result_array = json_decode($response, TRUE);

													if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
														//update wallet
														$newData = $this->player_model->point_transfer($player_data, $balance, $player_data['username']);
														$this->player_model->insert_log(LOG_WALLET_TRANSFER, $device, $newData, $player_data);
														$this->general_model->insert_game_transfer_report($player_data['last_in_game'], 'MAIN', $balance, $player_data['points'], $balance, $player_data['player_id']);
													} else if (isset($result_array['errorCode']) && $result_array['errorCode'] == '201') {
														//Overtime
														$newData = $this->general_model->insert_game_transfer_pending_report($player_data['last_in_game'], 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $balance, $player_data['player_id'], (isset($result_array['orderID']) ? trim($result_array['orderID']) : ''), (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
														$this->player_model->insert_log(LOG_WALLET_TRANSFER_PENDING, $device, $newData);
													} else if (isset($result_array['errorCode'])) {
													} else {
														$newData = $this->general_model->insert_game_transfer_pending_report($player_data['last_in_game'], 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $balance, $player_data['player_id'], (isset($result_array['orderID']) ? trim($result_array['orderID']) : ''), (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
														$this->player_model->insert_log(LOG_WALLET_TRANSFER_PENDING, $device, $newData);
													}
												}
											}
										}

										//Verify player
										$player_data_2 = $this->player_model->get_player_data($data['username']);
										$player_data_2['bank_name'] = $data['bank_name'];
										if (!empty($player_data_2)) {
											if ($player_data_2['points'] >= $data['amount']) {
												$allow_withdrawal = true;

												if ($allow_withdrawal) {
													$this->db->trans_start();

													$newData = $this->player_model->point_transfer($data, ($data['amount'] * -1), $data['username']);
													$this->withdrawal_model->add_withdrawal($data, $player_data_2);
													$this->general_model->insert_cash_transfer_report($player_data_2, $data['amount'], $data['username'], TRANSFER_WITHDRAWAL);
													$this->player_model->insert_log(LOG_WITHDRAWAL, $device, $newData, $player_data_2);

													$this->db->trans_complete();

													if ($this->db->trans_status() === TRUE) {
														$json['status'] = EXIT_SUCCESS;
														$json['msg'] = $this->lang->line('error_submitted_successful');
													} else {
														$json['msg'] = $this->lang->line('error_failed_to_update');
													}
												}
											} else {
												$json['msg'] = $this->lang->line('error_insufficient_balance');
											}
										} else {
											$json['msg'] = $this->lang->line('error_username_not_found');
										}
									} else {
										$json['msg'] = $this->lang->line('error_bank_unavailable');
									}
								}
							} else {
								$json['msg'] = $this->lang->line('error_invalid_password');
							}
						} else {
							$json['msg'] = $this->lang->line('error_username_not_found');
						}
					} else {
						$json['msg'] = $this->lang->line('error_bank_empty');
					}
				} else {
					$error = array(
						'currency_id' => form_error('currency_id'),
						'player_bank_id' => form_error('player_bank_id'),
						'bank_id' => form_error('bank_id'),
						'bank_account_name' => form_error('bank_account_name'),
						'bank_account_no' => form_error('bank_account_no'),
						'amount' => form_error('amount'),
						'password' => form_error('password')
					);

					if (!empty($error['currency_id'])) {
						$json['msg'] = $error['currency_id'];
					} else if (!empty($error['bank_id'])) {
						$json['msg'] = $error['bank_id'];
					} else if (!empty($error['bank_account_name'])) {
						$json['msg'] = $error['bank_account_name'];
					} else if (!empty($error['bank_account_no'])) {
						$json['msg'] = $error['bank_account_no'];
					} else if (!empty($error['player_bank_id'])) {
						$json['msg'] = $error['player_bank_id'];
					} else if (!empty($error['amount'])) {
						$json['msg'] = $error['amount'];
					} else if (!empty($error['password'])) {
						$json['msg'] = $error['password'];
					}
				}
				// $this->player_model->update_player_wallet_lock($wallet_username, WALLET_UNLOCK);
			} else {
				$json['msg'] = $this->lang->line('error_wallet_is_lock');
			}
		} else {
			$json['msg'] = $this->lang->line('error_please_login_to_continue');
		}

		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function checkWithdrawalByPromotion($player_bank_data)
	{
		$flag = false;

		$typePromotion = $this->player_promotion_model->checkTypePromotion($player_bank_data['player_id']);

		// echo "Code genre: ".$typePromotion; die();

		if ($typePromotion)
		{
			$promotionDataList = $this->player_promotion_model->getTypePromotion($player_bank_data['player_id'], $typePromotion);

			// echo "<pre>"; print_r($promotionDataList); die();

			foreach ($promotionDataList as $getTypePromotionItem)
			{
				$playerId = $getTypePromotionItem['player_id'];
				$maxRebate = $getTypePromotionItem['max_rebate'];
				$currentAmount = $getTypePromotionItem['current_amount'];
				$promotionAmount = $getTypePromotionItem['promotion_amount'];
				$achievementAmount = $getTypePromotionItem['achieve_amount'];
				$promotionStartDate = $getTypePromotionItem['start_date'];

				// echo "Max Rebate: ".$maxRebate."<br> Current Amount: ".$currentAmount."<br> Player id: ".$playerId."<br> Achievement amount: ".$achievementAmount."<br> Promotion amount: ".$promotionAmount."<br> Promotion start date: ".$promotionStartDate."<br>";

				$flag = ($currentAmount >= $achievementAmount) ? true : false;
				if ($flag == false) {
					return $flag;
				}
			}

		}
		else
		{
			$flag = true;
		}

		return $flag;
	}

	public function transaction_search()
	{
		if ($this->session->userdata('is_logged_in') == TRUE) {
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR,
				'msg' => $this->lang->line('error_system_error'),
				'csrfTokenName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash()
			);

			//Set form rules
			$config = array(
				array(
					'field' => 'from_date',
					'label' => strtolower($this->lang->line('label_from_date')),
					'rules' => 'trim|required|callback_date_check',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_datetime_format'),
						'date_check' => $this->lang->line('error_invalid_datetime_format')
					)
				),
				array(
					'field' => 'to_date',
					'label' => strtolower($this->lang->line('label_to_date')),
					'rules' => 'trim|required|callback_date_check',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_datetime_format'),
						'date_check' => $this->lang->line('error_invalid_datetime_format')
					)
				)
			);

			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');

			//Form validation
			if ($this->form_validation->run() == TRUE) {
				$from_date = strtotime(trim($this->input->post('from_date', TRUE)));
				$to_date = strtotime(trim($this->input->post('to_date', TRUE)));
				$days = cal_days_in_month(CAL_GREGORIAN, date('n', $from_date), date('Y', $from_date));
				$date_range = ($days * 86400);
				$time_diff = ($to_date - $from_date);

				if ($time_diff < 0 or $time_diff > $date_range) {
					$json['msg'] = $this->lang->line('error_invalid_month_range');
				} else {
					$data = array(
						'transaction_type' => trim($this->input->post('transaction_type', TRUE)),
						'from_date' => trim($this->input->post('from_date', TRUE)),
						'to_date' => trim($this->input->post('to_date', TRUE))
					);

					$this->session->set_userdata('searches', $data);

					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('error_submitted_successful');
				}
			} else {
				$error = array(
					'transaction_type' => form_error('transaction_type'),
					'from_date' => form_error('from_date'),
					'to_date' => form_error('to_date')
				);

				if (!empty($error['transaction_type'])) {
					$json['msg'] = $error['transaction_type'];
				} else if (!empty($error['from_date'])) {
					$json['msg'] = $error['from_date'];
				} else if (!empty($error['to_date'])) {
					$json['msg'] = $error['to_date'];
				}
			}

			//Output
			$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();

			exit();
		}
	}

	public function transaction_listing()
	{
		if ($this->session->userdata('is_logged_in') == TRUE) {
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);

			$arr = $this->session->userdata('searches');

			if (isset($arr['transaction_type']) && $arr['transaction_type'] == TRANSACTION_TYPE_DEPOSIT) {
				//Table Columns
				$columns = array(
					0 => 'created_date',
					1 => 'amount',
					2 => 'deposit_type',
					3 => 'status',
					4 => 'remark',
				);
				$dbtable = 'deposits';
				$where = 'WHERE player_id = ' . $this->session->userdata('player_id');
				if (isset($arr['from_date'])) {
					if (!empty($arr['from_date'])) {
						$where .= ' AND created_date >= ' . strtotime($arr['from_date'] . ' 00:00:00');
					}

					if (!empty($arr['to_date'])) {
						$where .= ' AND created_date <= ' . strtotime($arr['to_date'] . ' 23:59:59');
					}
				}
			} else if (isset($arr['transaction_type']) && $arr['transaction_type'] == TRANSACTION_TYPE_WITHDRAWAL) {
				//Table Columns
				$columns = array(
					0 => 'created_date',
					1 => 'amount',
					2 => 'withdrawal_type',
					3 => 'status',
					4 => 'remark',
				);
				$dbtable = 'withdrawals';
				$where = 'WHERE player_id = ' . $this->session->userdata('player_id');
				if (isset($arr['from_date'])) {
					if (!empty($arr['from_date'])) {
						$where .= ' AND created_date >= ' . strtotime($arr['from_date'] . ' 00:00:00');
					}

					if (!empty($arr['to_date'])) {
						$where .= ' AND created_date <= ' . strtotime($arr['to_date'] . ' 23:59:59');
					}
				}
			} else if (isset($arr['transaction_type']) && $arr['transaction_type'] == TRANSACTION_TYPE_DEPOSIT_POINT) {
				$columns = array(
					0 => 'report_date',
					1 => 'deposit_amount',
					2 => 'remark',
				);
				$dbtable = 'cash_transfer_report';
				$where = 'WHERE username = "' . $this->session->userdata('username') . '"';
				$where .= ' AND transfer_type = ' . TRANSFER_POINT_IN;
				if (isset($arr['from_date'])) {
					if (!empty($arr['from_date'])) {
						$where .= ' AND report_date >= ' . strtotime($arr['from_date'] . ' 00:00:00');
					}

					if (!empty($arr['to_date'])) {
						$where .= ' AND report_date <= ' . strtotime($arr['to_date'] . ' 23:59:59');
					}
				}
			} else if (isset($arr['transaction_type']) && $arr['transaction_type'] == TRANSACTION_TYPE_WITHDRAWAL_POINT) {
				$columns = array(
					0 => 'report_date',
					1 => 'withdrawal_amount',
					2 => 'remark',
				);
				$dbtable = 'cash_transfer_report';
				$where = 'WHERE username = "' . $this->session->userdata('username') . '"';
				$where .= ' AND transfer_type = ' . TRANSFER_POINT_OUT;
				if (isset($arr['from_date'])) {
					if (!empty($arr['from_date'])) {
						$where .= ' AND report_date >= ' . strtotime($arr['from_date'] . ' 00:00:00');
					}

					if (!empty($arr['to_date'])) {
						$where .= ' AND report_date <= ' . strtotime($arr['to_date'] . ' 23:59:59');
					}
				}
			} else if (isset($arr['transaction_type']) && $arr['transaction_type'] == TRANSACTION_TYPE_TRANSFER) {
				$columns = array(
					0 => 'report_date',
					1 => 'from_wallet',
					2 => 'to_wallet',
					3 => 'deposit_amount',
				);
				$dbtable = 'game_transfer_report';
				$where = 'WHERE player_id = ' . $this->session->userdata('player_id');
				if (isset($arr['from_date'])) {
					if (!empty($arr['from_date'])) {
						$where .= ' AND report_date >= ' . strtotime($arr['from_date'] . ' 00:00:00');
					}

					if (!empty($arr['to_date'])) {
						$where .= ' AND report_date <= ' . strtotime($arr['to_date'] . ' 23:59:59');
					}
				}
			} else if (isset($arr['transaction_type']) && $arr['transaction_type'] == TRANSACTION_TYPE_PROMOTION) {
				$columns = array(
					0 => 'report_date',
					1 => 'deposit_amount',
					2 => 'remark',
				);
				$dbtable = 'cash_transfer_report';
				$where = 'WHERE username = "' . $this->session->userdata('username') . '"';
				$where .= 'AND transfer_type = ' . TRANSFER_PROMOTION;
				if (isset($arr['from_date'])) {
					if (!empty($arr['from_date'])) {
						$where .= ' AND report_date >= ' . strtotime($arr['from_date'] . ' 00:00:00');
					}

					if (!empty($arr['to_date'])) {
						$where .= ' AND report_date <= ' . strtotime($arr['to_date'] . ' 23:59:59');
					}
				}
			} else {
				//bet
				$columns = array(
					0 => 'payout_time',
					1 => 'game_provider_code',
					2 => 'game_type_code',
					3 => 'bet_amount',
					4 => 'win_loss',
					5 => 'status',
				);
				$dbtable = 'transaction_report';
				$where = 'WHERE player_id = ' . $this->session->userdata('player_id');
				if (isset($arr['from_date'])) {
					if (!empty($arr['from_date'])) {
						$where .= ' AND payout_time >= ' . strtotime($arr['from_date'] . ' 00:00:00');
					}

					if (!empty($arr['to_date'])) {
						$where .= ' AND payout_time <= ' . strtotime($arr['to_date'] . ' 23:59:59');
					}
				}
			}

			$col = 0;
			$dir = "";

			if (!empty($order)) {
				foreach ($order as $o) {
					$col = $o['column'];
					$dir = $o['dir'];
				}
			}

			if ($dir != "asc" && $dir != "desc") {
				$dir = "desc";
			}

			if (!isset($columns[$col])) {
				$order = $columns[0];
			} else {
				$order = $columns[$col];
			}

			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;
			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}{$dbtable} $where)";
			$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if ($query->num_rows() > 0) {
				$posts = $query->result();
			}

			$query->free_result();

			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();

			$query->free_result();

			//Prepare data
			$data = array();
			if (!empty($posts)) {
				foreach ($posts as $post) {
					$row = array();
					if (isset($arr['transaction_type']) && $arr['transaction_type'] == TRANSACTION_TYPE_DEPOSIT) {
						$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
						$row[] = $post->amount;
						if ($post->deposit_type == DEPOSIT_ONLINE_BANKING) {
							$row[] = $this->lang->line('payment_gateway_paylah88_online');
						} else {
							$row[] = $this->lang->line('label_online_banking_and_atm');
						}
						switch ($post->status) {
							case STATUS_APPROVE:
								$row[] = $this->lang->line('label_approved');
								break;
							case STATUS_CANCEL:
								$row[] = $this->lang->line('label_cancelled');
								break;
							default:
								$row[] = $this->lang->line('label_pending');
								break;
						}

						$row[] = $post->remark;
					} else if (isset($arr['transaction_type']) && $arr['transaction_type'] == TRANSACTION_TYPE_WITHDRAWAL) {
						$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
						$row[] = $post->amount;
						$row[] = $this->lang->line('label_withdrawal');
						switch ($post->status) {
							case STATUS_APPROVE:
								$row[] = $this->lang->line('label_approved');
								break;
							case STATUS_CANCEL:
								$row[] = $this->lang->line('label_cancelled');
								break;
							default:
								$row[] = $this->lang->line('label_pending');
								break;
						}

						$row[] = $post->remark;
					} else if (isset($arr['transaction_type']) && $arr['transaction_type'] == TRANSACTION_TYPE_DEPOSIT_POINT) {
						$row[] = (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-');
						$row[] = $post->deposit_amount;
						$row[] = $post->remark;
					} else if (isset($arr['transaction_type']) && $arr['transaction_type'] == TRANSACTION_TYPE_WITHDRAWAL_POINT) {
						$row[] = (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-');
						$row[] = $post->withdrawal_amount;
						$row[] = $post->remark;
					} else if (isset($arr['transaction_type']) && $arr['transaction_type'] == TRANSACTION_TYPE_TRANSFER) {
						$row[] = (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-');
						if ($post->from_wallet == "MAIN") {
							$row[] = $this->lang->line('label_main_wallet');
							$row[] = $this->lang->line('game_' . strtolower($post->to_wallet));
						} else {
							$row[] = $this->lang->line('game_' . strtolower($post->from_wallet));
							$row[] = $this->lang->line('label_main_wallet');
						}
						$row[] = $post->deposit_amount;
					} else if (isset($arr['transaction_type']) && $arr['transaction_type'] == TRANSACTION_TYPE_PROMOTION) {
						$row[] = (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-');
						$row[] = $post->deposit_amount;
						$row[] = $post->remark;
					} else {
						$row[] = (($post->payout_time > 0) ? date('Y-m-d H:i:s', $post->payout_time) : '-');
						$row[] = $this->lang->line('game_' . strtolower($post->game_provider_code));
						$row[] = $this->lang->line(get_game_type($post->game_type_code));
						$row[] = $post->bet_amount;
						$row[] = $post->win_loss;
						switch ($post->status) {
							case STATUS_COMPLETE:
								$row[] = $this->lang->line('status_completed');
								break;
							case STATUS_CANCEL:
								$row[] = $this->lang->line('status_cancelled');
								break;
							default:
								$row[] = $this->lang->line('status_pending');
								break;
						}
					}
					$data[] = $row;
				}
			}

			//Output
			$json_data = array(
				"draw"            => intval($this->input->post('draw')),
				"recordsFiltered" => intval($totalFiltered),
				"data"            => $data,
				"csrfHash" 		  => $this->security->get_csrf_hash()
			);

			echo json_encode($json_data);
			exit();
		}
	}

	public function sub_game($provider_code = NULL, $game_type_code = NULL) 
	{
		$syslang = ((get_language_id(get_language()) == LANG_ZH_CN OR get_language_id(get_language()) == LANG_ZH_HK OR get_language_id(get_language()) == LANG_ZH_TW) ? LANG_ZH_CN : LANG_EN);
		
		$signature = md5(SYSTEM_API_AGENT_ID . $provider_code . SYSTEM_API_SECRET_KEY);

		$array_param = array(
			"method" 			=> 'GameList',
			"agent_id" 			=> SYSTEM_API_AGENT_ID,
			"signature" 		=> $signature,
			"syslang" 			=> $syslang,
			"device" 			=> PLATFORM_WEB,
			"provider_code" 	=> $provider_code,
			"game_type_code" 	=> $game_type_code
		);
		
		$response = $this->curl_json(HUB_URL, $array_param);		
		unset($array_param);
		#Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output($response)
			->_display();		
		exit();
		
		// $result_array = json_decode($response, TRUE);

		// $html = '';
		// // $j = 0;
		// // $page = 0;

		// if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
		// {
			// for($i=0;$i<sizeof($result_array['result']);$i++)
			// {
				// $html .= '<div class="cursor-pointer aspect-w-1 aspect-h-1 game-name" onclick="open_game(\'' . $provider_code . '\', \'' . $game_type_code . '\', \'' . $result_array['result'][$i]['game_code'] . '\');">';
				// $html .= '<img src="' . $result_array['result'][$i]['game_picture'] . '" alt="' . $result_array['result'][$i]['game_name'] . '" class="hover:drop-shadow-slot rounded lazy" />';
				// $html .= '<span class="hidden">' . $result_array['result'][$i]['game_name'] . '</span>';
				// $html .= '</div>';
				
				// // if($i == 0)
				// // {
					// // $html .= '<div class="slot-game-container tabcontent" style="display: block;">';
				// // }

				// // if($j == 0)
				// // {
					// // $page++;
					// // $html .= '<div class="page-count page-count-' . $page . '" style="display: ' . (($page > 1) ? 'none' : 'flex') . ';">';
				// // }

				// // $html .= '<div class="slot-game-wrap">';
				// // $html .= '<div class="slot-game-img lazy" data-src="' . $result_array['result'][$i]['game_picture'] . '"></div>';
				// // $html .= '<div class="slot-game-title">' . $result_array['result'][$i]['game_name'] . '</div>';
				// // $html .= '<div class="slot-game-btn-hover">';
				// // $html .= '<div class="slot-game-btn-wrap">';
				// // $html .= '<div class="slot-game-btn" onclick="open_game(\'' . $provider_code . '\', \'' . $game_type_code . '\', \'' . $result_array['result'][$i]['game_code'] . '\');">' . $this->lang->line('label_play_now') . '</div>';
				// // $html .= '</div>';
				// // $html .= '</div>';
				// // $html .= '</div>';

				// // $j++;

				// // if($j == 15)
				// // {
					// // $html .= '</div>';
					// // $j = 0;
				// // }
			// }
		// }

		// if( ! empty($html))
		// {
			// // if($j > 0)
			// // {
				// // $html .= '</div>';
			// // }

			// // $html .= '</div>';
			// // $html .= '<ul class="pagination-item-wrap">';

			// // for($i=0;$i<$page;$i++)
			// // {
				// // $pid = ($i + 1);
				// // $html .= '<li class="pagination-item ' . (($i == 0) ? 'active' : '') . '" onclick="customPagination(this, ' . $pid . ')">' . $pid . '</li>';
			// // }

			// // $html .= '</ul>';

			// $html .= '<script type="text/javascript">$(".lazy").lazy();</script>';
		// }

		// echo $html;
	}

	public function open_game($provider_code = NULL, $game_type_code = NULL, $game_code = NULL)
	{
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error')
		);

		if ($this->session->userdata('is_logged_in') == TRUE) {
			$sys_data = $this->miscellaneous_model->get_miscellaneous();
			$is_change_password = true;
			if (($sys_data['player_change_password_type'] == TYPE_FORCE) && ($sys_data['is_player_change_password'] == STATUS_ACTIVE) && ($this->session->userdata('is_player_change_password') == FALSE)) {
				$is_change_password = false;
			}
			if ($is_change_password) {
				$game_maintenance_data = $this->game_model->get_game_maintenance_data($provider_code);
				$is_maintenance = false;
				$current_time = time();
				if (!empty($game_maintenance_data)) {
					foreach ($game_maintenance_data as $game_maintenance_data_row) {
						$from_time = strtotime(date('Y-m-d') . ' ' . $game_maintenance_data_row['fixed_from_time']);
						$to_time = strtotime(date('Y-m-d') . ' ' . $game_maintenance_data_row['fixed_to_time']);

						if ($game_maintenance_data_row['is_maintenance'] == STATUS_YES or ($game_maintenance_data_row['fixed_maintenance'] == STATUS_YES && $game_maintenance_data_row['fixed_day'] == date('N') && $current_time >= $from_time && $current_time <= $to_time) or ($game_maintenance_data_row['urgent_maintenance'] == STATUS_YES && $current_time >= $game_maintenance_data_row['urgent_date'])) {
							$is_maintenance = true;
						}
					}
				}

				if ($is_maintenance) {
					$json['msg'] = $this->lang->line('error_game_maintenance');
				} else {
					$wallet_username = $this->session->userdata('username');
					$wallet_data = $this->player_model->get_player_wallet_lock($wallet_username);
					if (!empty($wallet_data) && $wallet_data['wallet_lock'] == WALLET_UNLOCK) {
						$this->player_model->update_player_wallet_lock($wallet_username, WALLET_LOCK);
						$game_data = $this->game_model->get_game_data($provider_code);
						if (!empty($game_data)) {
							$player_data = $this->player_model->get_player_data($this->session->userdata('username'));
							if (!empty($player_data)) {

								$api_data = $this->api_model->get_api_data(SYSTEM_API_AGENT_ID);

								if (!empty($api_data)) {
									//$device = PLATFORM_APP_ANDROID;

									$device = PLATFORM_WEB;
									if ($this->agent->is_mobile()) {
										$device = PLATFORM_MOBILE_WEB;
									}

									$syslang = ((get_language_id(get_language()) == LANG_ZH_CN or get_language_id(get_language()) == LANG_ZH_HK or get_language_id(get_language()) == LANG_ZH_TW) ? LANG_ZH_CN : LANG_EN);

									$launch_game = FALSE;
									$url = HUB_URL;

									$param_array = array(
										"agent_id" => $api_data['agent_id'],
										"syslang" => $syslang,
										"device" => $device,
										"provider_code" => $provider_code,
										"username" => '',
										"signature" => '',
									);

									//Create member
									$account_data = $this->player_model->get_player_game_account_data($provider_code, $player_data['player_id']);
									if (!empty($account_data)) {
										$launch_game = TRUE;
										$param_array['player_id'] = $account_data['player_id'];
										$param_array['game_id'] = $account_data['game_id'];
										$param_array['username'] = $account_data['username'];
										$param_array['password'] = $account_data['password'];
										$param_array['signature'] = md5($api_data['agent_id'] . $provider_code . $param_array['username'] . $api_data['secret_key']);
									} else {
										$param_array['method'] = 'CreateMember';
										$param_array['player_id'] = $player_data['player_id'];
										$param_array['game_id'] = "";
										$param_array['username'] = $sys_data['system_prefix'] . $player_data['username'];
										$param_array['password'] = rand(10000000, 99999999);
										$param_array['signature'] = md5($api_data['agent_id'] . $provider_code . $param_array['username'] . $api_data['secret_key']);
										$param_array['game_type_code'] = $game_type_code;
										$param_array['game_code'] = $game_code;

										$response = $this->curl_json($url, $param_array);
										$result_array = json_decode($response, TRUE);
										if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
											$launch_game = TRUE;
											$this->player_model->add_player_game_account($provider_code, $player_data['player_id'], $param_array['username'], (isset($result_array['gamePassword']) ? trim($result_array['gamePassword']) : '0'), (isset($result_array['gameID']) ? trim($result_array['gameID']) : '0'));
											$param_array['game_id'] = (isset($result_array['gameID']) ? trim($result_array['gameID']) : '0');
											$param_array['password'] = (isset($result_array['gamePassword']) ? trim($result_array['gamePassword']) : '0');
										}
									}
									if ($launch_game == TRUE) {
										//Get balance, withdraw and logout from previous game
										if (!empty($player_data['last_in_game']) && $player_data['last_in_game'] != $provider_code) {
											$account_data = $this->player_model->get_player_game_account_data($player_data['last_in_game'], $player_data['player_id']);
											if (!empty($account_data)) {
												$balance = 0;
												$signature_2 = md5($api_data['agent_id'] . $player_data['last_in_game'] . $account_data['username'] . $api_data['secret_key']);

												$param_array_2 = array(
													"agent_id" => $api_data['agent_id'],
													"syslang" => $syslang,
													"device" => $device,
													"provider_code" => $player_data['last_in_game'],
													"player_id" => $account_data['player_id'],
													"game_id" => $account_data['game_id'],
													"username" => $account_data['username'],
													"password" => $account_data['password'],
													"signature" => $signature_2,
												);

												//Get balance
												$param_array_2['method'] = 'GetBalance';
												$response = $this->curl_json($url, $param_array_2);
												$result_array = json_decode($response, TRUE);

												if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
													$balance = $result_array['result'];
												}

												if ($balance > 0) {
													//Withdraw credit
													$param_array_2['method'] = 'ChangeBalance';
													$param_array_2['order_id'] = 'OUT' . date("YmdHis") . $account_data['username'];
													$param_array_2['amount'] = ($balance * -1);
													$response = $this->curl_json($url, $param_array_2);
													$result_array = json_decode($response, TRUE);
													if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
														//update wallet
														$newData = $this->player_model->point_transfer($player_data, $balance, $player_data['username']);
														$this->player_model->insert_log(LOG_WALLET_TRANSFER, $device, $newData, $player_data);
														$this->general_model->insert_game_transfer_report($player_data['last_in_game'], 'MAIN', $balance, $player_data['points'], $balance, $player_data['player_id'], (isset($result_array['orderID']) ? trim($result_array['orderID']) : ''), (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
													} else if (isset($result_array['errorCode']) && $result_array['errorCode'] == '201') {
														//Overtime
														$newData = $this->general_model->insert_game_transfer_pending_report($player_data['last_in_game'], 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $balance, $player_data['player_id'], (isset($result_array['orderID']) ? trim($result_array['orderID']) : ''), (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
														$this->player_model->insert_log(LOG_WALLET_TRANSFER_PENDING, $device, $newData);
													} else if (isset($result_array['errorCode'])) {
													} else {
														$newData = $this->general_model->insert_game_transfer_pending_report($player_data['last_in_game'], 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $balance, $player_data['player_id'], (isset($result_array['orderID']) ? trim($result_array['orderID']) : ''), (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
														$this->player_model->insert_log(LOG_WALLET_TRANSFER_PENDING, $device, $newData);
													}
												}

												//Logout game
												$param_array_2['method'] = 'LogoutGame';
												$this->curl_json($url, $param_array_2);
											}
										}
										//Do deposit if have balance
										$player_data_2 = $this->player_model->get_player_data($player_data['username']);

										if (!empty($player_data_2)) {
											if ($player_data_2['points'] > 0) {
												$param_array['method'] = 'ChangeBalance';
												$param_array['order_id'] = 'IN' . date("YmdHis") . $param_array['username'];
												$param_array['amount'] = $player_data_2['points'];

												//update wallet
												$newData_2 = $this->player_model->point_transfer($player_data_2, ($player_data_2['points'] * -1), $player_data_2['username']);
												$this->player_model->insert_log(LOG_WALLET_TRANSFER, $device, $newData_2, $player_data_2);

												//insert table
												$pre_add_game_transfer_report =  $this->general_model->insert_game_transfer_report('MAIN', $provider_code, $player_data_2['points'], 0, $player_data_2['points'], $player_data_2['player_id'], $param_array['order_id'], '');

												//Deposit credit
												$response = $this->curl_json($url, $param_array);
												$result_array = json_decode($response, TRUE);

												if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
													//update last in game
													$this->player_model->update_player_last_in_game($provider_code, $player_data_2['player_id']);
													//update pre add game transfer report
													$this->general_model->update_game_transfer_report_order_id_alias($pre_add_game_transfer_report, (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
												} else {
													if (isset($result_array['errorCode']) && $result_array['errorCode'] == '201') {
														//Overtime
														$newData = $this->general_model->insert_game_transfer_pending_report('MAIN', $provider_code, TRANSFER_TRANSACTION_OUT, $player_data_2['points'], 0, $player_data_2['points'], $player_data['player_id'], (isset($result_array['orderID']) ? trim($result_array['orderID']) : ''), (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
														$this->player_model->insert_log(LOG_WALLET_TRANSFER_PENDING, $device, $newData);
													} else if (isset($result_array['errorCode'])) {
														//update wallet
														$newData_3 = $this->player_model->point_transfer($player_data_2, $player_data_2['points'], $player_data_2['username']);
														$this->player_model->insert_log(LOG_WALLET_TRANSFER, $device, $newData_3, $player_data_2);

														//insert table
														$this->general_model->insert_game_transfer_report($provider_code, 'MAIN', $player_data_2['points'], 0, $player_data_2['points'], $player_data_2['player_id'], (isset($result_array['orderID']) ? trim($result_array['orderID']) : ''), (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
													} else {
														//Unormal
														$newData = $this->general_model->insert_game_transfer_pending_report('MAIN', $provider_code, TRANSFER_TRANSACTION_OUT, $player_data_2['points'], 0, $player_data_2['points'], $player_data['player_id'], $param_array['order_id'], (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
														$this->player_model->insert_log(LOG_WALLET_TRANSFER_PENDING, $device, $newData);
														$this->general_model->insert_api_game_api_unnormal_log($provider_code, $player_data_2['player_id'], TRANSFER_TRANSACTION_OUT, $param_array, $result_array, $response);
													}
												}
											}
										}

										//Login game
										$param_array['method'] = 'LoginGame';
										$param_array['game_type_code'] = $game_type_code;
										$param_array['game_code'] = $game_code;
										$response = $this->curl_json($url, $param_array);
										$result_array = json_decode($response, TRUE);
										if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
											$json['status'] = EXIT_SUCCESS;
											$json['msg'] = $this->lang->line('error_success');
											$json['url'] = $result_array['result'];
											/*
											$game_url_data = array(
												'url' => $result_array['result'],
											);
											$this->session->set_userdata('game_url', $game_url_data);
											*/
										}
									}
								}
							} else {
								$json['msg'] = $this->lang->line('error_please_login_to_continue');
							}
						} else {
							$json['msg'] = $this->lang->line('error_provider_code_incorrect');
						}
						$this->player_model->update_player_wallet_lock($wallet_username, WALLET_UNLOCK);
					} else {
						$json['msg'] = $this->lang->line('error_wallet_is_lock');
					}
				}
			} else {
				$json['msg'] = $this->lang->line('error_please_change_password_to_continue');
			}
		} else {
			$json['msg'] = $this->lang->line('error_please_login_to_continue');
		}

		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function open_game_test($provider_code = NULL, $game_type_code = NULL, $game_code = NULL)
	{
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error')
		);

		if ($this->session->userdata('is_logged_in') == TRUE) {
			$sys_data = $this->miscellaneous_model->get_miscellaneous();
			$is_change_password = true;
			if (($sys_data['player_change_password_type'] == TYPE_FORCE) && ($sys_data['is_player_change_password'] == STATUS_ACTIVE) && ($this->session->userdata('is_player_change_password') == FALSE)) {
				$is_change_password = false;
			}
			if ($is_change_password) {
				$game_maintenance_data = $this->game_model->get_game_maintenance_data($provider_code);
				$is_maintenance = false;
				if (!empty($game_maintenance_data)) {
					foreach ($game_maintenance_data as $game_maintenance_data_row) {
						if ($game_maintenance_data_row['is_maintenance'] == STATUS_YES or ($game_maintenance_data_row['fixed_maintenance'] == STATUS_YES && $game_maintenance_data_row['fixed_day'] == date('N') && $current_time >= $from_time && $current_time <= $to_time) or ($game_maintenance_data_row['urgent_maintenance'] == STATUS_YES && $current_time >= $game_maintenance_data_row['urgent_date'])) {
							$is_maintenance = true;
						}
					}
				}

				if ($is_maintenance) {
					$json['msg'] = $this->lang->line('error_game_maintenance');
				} else {
					$wallet_username = $this->session->userdata('username');
					$wallet_data = $this->player_model->get_player_wallet_lock($wallet_username);
					if (!empty($wallet_data) && $wallet_data['wallet_lock'] == WALLET_UNLOCK) {
						$this->player_model->update_player_wallet_lock($wallet_username, WALLET_LOCK);
						$game_data = $this->game_model->get_game_data($provider_code);
						ad($game_data);
						if (!empty($game_data)) {
							$player_data = $this->player_model->get_player_data($this->session->userdata('username'));
							if (!empty($player_data)) {

								$api_data = $this->api_model->get_api_data(SYSTEM_API_AGENT_ID);

								if (!empty($api_data)) {
									//$device = PLATFORM_APP_ANDROID;

									$device = PLATFORM_WEB;
									if ($this->agent->is_mobile()) {
										$device = PLATFORM_MOBILE_WEB;
									}

									$syslang = ((get_language_id(get_language()) == LANG_ZH_CN or get_language_id(get_language()) == LANG_ZH_HK or get_language_id(get_language()) == LANG_ZH_TW) ? LANG_ZH_CN : LANG_EN);

									$launch_game = FALSE;
									$url = HUB_URL;

									$param_array = array(
										"agent_id" => $api_data['agent_id'],
										"syslang" => $syslang,
										"device" => $device,
										"provider_code" => $provider_code,
										"username" => '',
										"signature" => '',
									);
									#echo "hello";
									//Create member
									$account_data = $this->player_model->get_player_game_account_data($provider_code, $player_data['player_id']);
									if (!empty($account_data)) {
										$launch_game = TRUE;
										$param_array['player_id'] = $account_data['player_id'];
										$param_array['game_id'] = $account_data['game_id'];
										$param_array['username'] = $account_data['username'];
										$param_array['password'] = $account_data['password'];
										$param_array['signature'] = md5($api_data['agent_id'] . $provider_code . $param_array['username'] . $api_data['secret_key']);
									} else {
										$param_array['method'] = 'CreateMember';
										$param_array['player_id'] = $player_data['player_id'];
										$param_array['game_id'] = '';
										$param_array['username'] = $sys_data['system_prefix'] . $player_data['username'];
										$param_array['password'] = rand(10000000, 99999999);
										$param_array['signature'] = md5($api_data['agent_id'] . $provider_code . $param_array['username'] . $api_data['secret_key']);
										$param_array['game_type_code'] = $game_type_code;
										$param_array['game_code'] = $game_code;
										ad($param_array);
										$response = $this->curl_json($url, $param_array);
										ad($response);
										$result_array = json_decode($response, TRUE);
										if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
											$launch_game = TRUE;
											$this->player_model->add_player_game_account($provider_code, $player_data['player_id'], $param_array['username'], (isset($result_array['gamePassword']) ? trim($result_array['gamePassword']) : '0'), (isset($result_array['gameID']) ? trim($result_array['gameID']) : '0'));
											$param_array['game_id'] = (isset($result_array['gameID']) ? trim($result_array['gameID']) : '0');
											$param_array['password'] = (isset($result_array['gamePassword']) ? trim($result_array['gamePassword']) : '0');
										}
									}
									if ($launch_game == TRUE) {
										//Get balance, withdraw and logout from previous game
										if (!empty($player_data['last_in_game']) && $player_data['last_in_game'] != $provider_code) {
											$account_data = $this->player_model->get_player_game_account_data($player_data['last_in_game'], $player_data['player_id']);
											if (!empty($account_data)) {
												$balance = 0;
												$signature_2 = md5($api_data['agent_id'] . $player_data['last_in_game'] . $account_data['username'] . $api_data['secret_key']);

												$param_array_2 = array(
													"agent_id" => $api_data['agent_id'],
													"syslang" => $syslang,
													"device" => $device,
													"provider_code" => $player_data['last_in_game'],
													"player_id" => $account_data['player_id'],
													"username" => $account_data['username'],
													"password" => $account_data['password'],
													"signature" => $signature_2,
												);

												//Get balance
												$param_array_2['method'] = 'GetBalance';
												$response = $this->curl_json($url, $param_array_2);
												$result_array = json_decode($response, TRUE);

												if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
													$balance = $result_array['result'];
												}

												if ($balance > 0) {
													//Withdraw credit
													$param_array_2['method'] = 'ChangeBalance';
													$param_array_2['order_id'] = 'OUT' . date("YmdHis") . $account_data['username'];
													$param_array_2['amount'] = ($balance * -1);
													$response = $this->curl_json($url, $param_array_2);
													$result_array = json_decode($response, TRUE);
													if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
														//update wallet
														$newData = $this->player_model->point_transfer($player_data, $balance, $player_data['username']);
														$this->player_model->insert_log(LOG_WALLET_TRANSFER, $device, $newData, $player_data);
														$this->general_model->insert_game_transfer_report($player_data['last_in_game'], 'MAIN', $balance, $player_data['points'], $balance, $player_data['player_id'], (isset($result_array['orderID']) ? trim($result_array['orderID']) : ''), (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
													} else if (isset($result_array['errorCode']) && $result_array['errorCode'] == '201') {
														//Overtime
														$newData = $this->general_model->insert_game_transfer_pending_report($player_data['last_in_game'], 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $balance, $player_data['player_id'], (isset($result_array['orderID']) ? trim($result_array['orderID']) : ''), (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
														$this->player_model->insert_log(LOG_WALLET_TRANSFER_PENDING, $device, $newData);
													} else if (isset($result_array['errorCode'])) {
													} else {
														$newData = $this->general_model->insert_game_transfer_pending_report($player_data['last_in_game'], 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $balance, $player_data['player_id'], (isset($result_array['orderID']) ? trim($result_array['orderID']) : ''), (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
														$this->player_model->insert_log(LOG_WALLET_TRANSFER_PENDING, $device, $newData);
													}
												}

												//Logout game
												$param_array_2['method'] = 'LogoutGame';
												$this->curl_json($url, $param_array_2);
											}
										}
										//Do deposit if have balance
										$player_data_2 = $this->player_model->get_player_data($player_data['username']);

										if (!empty($player_data_2)) {
											if ($player_data_2['points'] > 0) {
												$param_array['method'] = 'ChangeBalance';
												$param_array['order_id'] = 'IN' . date("YmdHis") . $param_array['username'];
												$param_array['amount'] = $player_data_2['points'];

												//update wallet
												$newData_2 = $this->player_model->point_transfer($player_data_2, ($player_data_2['points'] * -1), $player_data_2['username']);
												$this->player_model->insert_log(LOG_WALLET_TRANSFER, $device, $newData_2, $player_data_2);

												//insert table
												$pre_add_game_transfer_report =  $this->general_model->insert_game_transfer_report('MAIN', $provider_code, $player_data_2['points'], 0, $player_data_2['points'], $player_data_2['player_id'], $param_array['order_id'], '');

												//Deposit credit
												$response = $this->curl_json($url, $param_array);
												$result_array = json_decode($response, TRUE);

												if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
													//update last in game
													$this->player_model->update_player_last_in_game($provider_code, $player_data_2['player_id']);
													//update pre add game transfer report
													$this->general_model->update_game_transfer_report_order_id_alias($pre_add_game_transfer_report, (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
												} else {
													if (isset($result_array['errorCode']) && $result_array['errorCode'] == '201') {
														//Overtime
														$newData = $this->general_model->insert_game_transfer_pending_report('MAIN', $provider_code, TRANSFER_TRANSACTION_OUT, $player_data_2['points'], 0, $player_data_2['points'], $player_data['player_id'], (isset($result_array['orderID']) ? trim($result_array['orderID']) : ''), (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
														$this->player_model->insert_log(LOG_WALLET_TRANSFER_PENDING, $device, $newData);
													} else if (isset($result_array['errorCode'])) {
														//update wallet
														$newData_3 = $this->player_model->point_transfer($player_data_2, $player_data_2['points'], $player_data_2['username']);
														$this->player_model->insert_log(LOG_WALLET_TRANSFER, $device, $newData_3, $player_data_2);

														//insert table
														$this->general_model->insert_game_transfer_report($provider_code, 'MAIN', $player_data_2['points'], 0, $player_data_2['points'], $player_data_2['player_id'], (isset($result_array['orderID']) ? trim($result_array['orderID']) : ''), (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
													} else {
														//Unormal
														$newData = $this->general_model->insert_game_transfer_pending_report('MAIN', $provider_code, TRANSFER_TRANSACTION_OUT, $player_data_2['points'], 0, $player_data_2['points'], $player_data['player_id'], $param_array['order_id'], (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
														$this->player_model->insert_log(LOG_WALLET_TRANSFER_PENDING, $device, $newData);
														$this->general_model->insert_api_game_api_unnormal_log($provider_code, $player_data_2['player_id'], TRANSFER_TRANSACTION_OUT, $param_array, $result_array, $response);
													}
												}
											}
										}

										//Login game
										$param_array['method'] = 'LoginGame';
										$param_array['game_type_code'] = $game_type_code;
										$param_array['game_code'] = $game_code;
										$response = $this->curl_json($url, $param_array);
										$result_array = json_decode($response, TRUE);
										if (isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {
											$json['status'] = EXIT_SUCCESS;
											$json['msg'] = $this->lang->line('error_success');
											$json['url'] = $result_array['result'];
											/*
											$game_url_data = array(
												'url' => $result_array['result'],
											);
											$this->session->set_userdata('game_url', $game_url_data);
											*/
										}
									}
								}
							} else {
								$json['msg'] = $this->lang->line('error_please_login_to_continue');
							}
						} else {
							$json['msg'] = $this->lang->line('error_provider_code_incorrect');
						}
						$this->player_model->update_player_wallet_lock($wallet_username, WALLET_UNLOCK);
					} else {
						$json['msg'] = $this->lang->line('error_wallet_is_lock');
					}
				}
			} else {
				$json['msg'] = $this->lang->line('error_please_change_password_to_continue');
			}
		} else {
			$json['msg'] = $this->lang->line('error_please_login_to_continue');
		}

		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function testing()
	{
		ad($this->session->userdata('get_contact_detail')['im_whatsapp']);
		$result = $this->session->userdata('get_contact_detail');
		ad($result);
	}

	//new function
	public function get_contact_list()
	{
		$result = array();
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);

		$data = $this->contact_model->get_contact_list();
		$json['status'] = ERROR_SUCCESS;
		$json['msg'] = $this->lang->line('error_success');
		$json['list'] = $data;
		if (!empty($data)) {
			foreach ($data as $data_row) {
				$result[$data_row['im_name']] = $data_row['im_value'];
			}
		}
		$this->session->set_userdata('get_contact_detail', $result);
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function set_read_announcement()
	{
		$result = array();
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);

		$json['status'] = ERROR_SUCCESS;

		$this->session->set_userdata('read_notice', 1);
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function get_transaction_notice($type = NULL)
	{
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);
		if ($this->session->userdata('is_logged_in') == TRUE) {
			$sys_data = $this->miscellaneous_model->get_miscellaneous();
			$is_notice = false;
			switch ($type) {
				case TRANSFER_OFFLINE_DEPOSIT:
					((isset($sys_data['is_deposit_notice']) && $sys_data['is_deposit_notice'] == STATUS_ACTIVE) ? $is_notice = true : $is_notice = false);
					break;
				case TRANSFER_PG_DEPOSIT:
					((isset($sys_data['is_online_deposit_notice']) && $sys_data['is_online_deposit_notice'] == STATUS_ACTIVE) ? $is_notice = true : $is_notice = false);
					break;
				case TRANSFER_CREDIT_CARD_DEPOSIT:
					((isset($sys_data['is_credit_card_deposit_notice']) && $sys_data['is_credit_card_deposit_notice'] == STATUS_ACTIVE) ? $is_notice = true : $is_notice = false);
					break;
				case TRANSFER_HYPERMART_DEPOSIT:
					((isset($sys_data['is_hypermart_deposit_notice']) && $sys_data['is_hypermart_deposit_notice'] == STATUS_ACTIVE) ? $is_notice = true : $is_notice = false);
					break;
				case TRANSFER_WITHDRAWAL:
					((isset($sys_data['is_withdrawal_notice']) && $sys_data['is_withdrawal_notice'] == STATUS_ACTIVE) ? $is_notice = true : $is_notice = false);
					break;
				default:
					((isset($sys_data['is_deposit_notice']) && $sys_data['is_deposit_notice'] == STATUS_ACTIVE) ? $is_notice = true : $is_notice = false);
					break;
			}

			$json['status'] = ERROR_SUCCESS;
			$json['msg'] = $this->lang->line('error_success');
			$json['is_notice'] = $is_notice;
			if ($is_notice) {
				$notice_data = $this->miscellaneous_model->get_transaction_notice_lang($type, get_language_id(get_language()));
				if (!empty($notice_data)) {
					$json['notice_title'] = $notice_data['miscellaneous_title'];
					$json['notice_content'] = $notice_data['miscellaneous_content'];
				} else {
					if ($type == TRANSFER_WITHDRAWAL) {
						$json['notice_title'] = $this->lang->line('label_notice_default_title');
						$json['notice_content'] = $this->lang->line('label_notice_default_deposit_content');
					} else {
						$json['notice_title'] = $this->lang->line('label_notice_default_title');
						$json['notice_content'] = $this->lang->line('label_notice_default_deposit_content');
					}
				}

				if ($type == TRANSFER_WITHDRAWAL) {
					if ($this->session->userdata('withdrawal_notice_time_session')) {
						$withdrawal_notice_time_session = $this->session->userdata('withdrawal_notice_time_session');
						$today_date_refresh = strtotime(date('Y-m-d 00:00:00'));
						if ($today_date_refresh > $withdrawal_notice_time_session) {
							$this->session->unset_userdata('withdrawal_notice_time_session');
						} else {
							$json['is_notice'] = false;
						}
					}
				}
			}
		} else {
			$json['msg'] = $this->lang->line('error_please_login_to_continue');
		}

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function get_player_bank_list($player_bank_id = NULL)
	{
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);
		$data = $this->bank_model->get_player_bank_list($player_bank_id);
		if (sizeof($data) > 0) {
			$json['status'] = ERROR_SUCCESS;
			$json['output'] = $data;
		}
		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
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

							$url = HUB_URL;
							$signature = md5($api_data['agent_id'] . $account_data['game_provider_code'] . $account_data['username'] . $api_data['secret_key']);

							$param_array = array(
								"method" => 'GetBalance',
								"agent_id" => $api_data['agent_id'],
								"syslang" => $syslang,
								"device" => $device,
								"provider_code" => $account_data['game_provider_code'],
								"player_id" => $account_data['player_id'],
								"game_id" => $account_data['game_id'],
								"username" => $account_data['username'],
								"password" => $account_data['password'],
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

	public function binding_bank()
	{
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);
		if ($this->session->userdata('is_logged_in') == TRUE) {
			$sys_data = $this->miscellaneous_model->get_miscellaneous();
			$is_change_password = true;
			if (($sys_data['player_change_password_type'] == TYPE_FORCE) && ($sys_data['is_player_change_password'] == STATUS_ACTIVE) && ($this->session->userdata('is_player_change_password') == FALSE)) {
				$is_change_password = false;
			}
			if ($is_change_password) {
				$config = array(
					array(
						'field' => 'bank_id',
						'label' => strtolower($this->lang->line('label_bank_name')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_bank_empty'),
						)
					),
					array(
						'field' => 'bank_account_no',
						'label' => strtolower($this->lang->line('label_bank_account_no')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_bank_account_empty'),
							'regex_match' => $this->lang->line('error_bank_account_unavailable'),
						)
					),
					array(
						'field' => 'bank_account_name',
						'label' => strtolower($this->lang->line('label_bank_account_name')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_bank_account_name_empty')
						)
					),
					/* array(
						'field' => 'bank_account_address',
						'label' => strtolower($this->lang->line('label_bank_account_address')),
						'rules' => 'trim',
						'errors' => array(
							'required' => $this->lang->line('error_bank_account_name_empty')
						)
					), */
				);
				$this->form_validation->set_rules($config);
				$this->form_validation->set_error_delimiters('', '');
				if ($this->form_validation->run() == TRUE) {
					$playerData = $this->player_model->get_player_data($this->session->userdata('username'));
					if (!empty($playerData)) {
						$miscellaneous = $this->miscellaneous_model->get_miscellaneous();
						$player_bank_max = $this->bank_model->get_player_bank_account_quantity($playerData);
						// if ($player_bank_max < $miscellaneous['player_bank_account_max']) {
							$device = PLATFORM_WEB;
							if ($this->agent->is_mobile()) {
								$device = PLATFORM_MOBILE_WEB;
							}
							$data = array(
								'username' => $this->session->userdata('username'),
								'oldpass' => $this->input->post('password', TRUE),
								'bank_id' => $this->input->post('bank_id', TRUE),
								'bank_account_name' => $this->input->post('bank_account_name', TRUE),
								'bank_account_no' => $this->input->post('bank_account_no', TRUE),
								// 'bank_account_address' => $this->input->post('bank_account_address', TRUE),
								'player_id' => $this->session->userdata('player_id'),
							);
							$bank = $this->bank_model->get_bank_data($data['bank_id']);
							if ($bank['bank_type'] == BANK_TYPE_CRYTO) {
								$data['bank_account_address'] = $data['bank_account_no'];
								$data['player_bank_type'] = PLAYER_BANK_TYPE_CRYTO;
								unset($data['bank_account_no']);
							}

							//Verify password
							$response = FALSE;
							$response = $this->player_model->verify_current_password($data);
							if ($response == TRUE) {
								$this->db->trans_start();
								$newData = $this->player_model->add_player_bank($data);
								$this->player_model->insert_log(LOG_BANK_PLAYER_USER_ADD, $device, $newData);
								$this->db->trans_complete();
								if ($this->db->trans_status() === TRUE) {
									$this->session->set_userdata($data);
									$json['status'] = ERROR_SUCCESS;
									$json['msg'] = $this->lang->line('error_binding_bank_successful');
								} else {
									$json['msg'] = $this->lang->line('error_binding_bank_failed');
								}
							} else {
								$json['msg'] = $this->lang->line('error_current_password_incorrect');
							}
						// } else {
							// $json['msg'] = $this->lang->line('error_over_player_bank_account_max');
						// }
					} else {
						$json['msg'] = $this->lang->line('error_username_not_found');
					}
				} else {
					$error = array(
						'bank_id' => form_error('bank_id'),
						'bank_account_no' => form_error('bank_account_no'),
						'bank_account_name' => form_error('bank_account_name')
					);
					if (!empty($error['bank_id'])) {
						$json['msg'] = $error['bank_id'];
					} else if (!empty($error['bank_account_no'])) {
						$json['msg'] = $error['bank_account_no'];
					} else if (!empty($error['bank_account_name'])) {
						$json['msg'] = $error['bank_account_name'];
					}
				}
			} else {
				$json['msg'] = $this->lang->line('error_please_change_password_to_continue');
			}
		} else {
			$json['msg'] = $this->lang->line('error_please_login_to_continue');
		}
		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function binding_bank_usdt()
	{
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);
		if ($this->session->userdata('is_logged_in') == TRUE) {
			$sys_data = $this->miscellaneous_model->get_miscellaneous();
			$is_change_password = true;
			if (($sys_data['player_change_password_type'] == TYPE_FORCE) && ($sys_data['is_player_change_password'] == STATUS_ACTIVE) && ($this->session->userdata('is_player_change_password') == FALSE)) {
				$is_change_password = false;
			}
			if ($is_change_password) {
				$config = array(
					array(
						'field' => 'bank_id',
						'label' => strtolower($this->lang->line('label_bank_name')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_bank_empty'),
						)
					),
					array(
						'field' => 'bank_account_no',
						'label' => strtolower($this->lang->line('label_bank_account_no')),
						'rules' => 'trim',
						'errors' => array()
					),
					array(
						'field' => 'bank_account_name',
						'label' => strtolower($this->lang->line('label_bank_account_name')),
						'rules' => 'trim',
						'errors' => array()
					),
					array(
						'field' => 'bank_account_address',
						'label' => strtolower($this->lang->line('label_bank_account_address')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_bank_account_address_empty')
						)
					),
				);
				$this->form_validation->set_rules($config);
				$this->form_validation->set_error_delimiters('', '');
				if ($this->form_validation->run() == TRUE) {
					$playerData = $this->player_model->get_player_data($this->session->userdata('username'));
					if (!empty($playerData)) {
						$miscellaneous = $this->miscellaneous_model->get_miscellaneous();
						$player_bank_max = $this->bank_model->get_player_bank_account_quantity($playerData);
						if ($player_bank_max < $miscellaneous['player_bank_account_max']) {
							$device = PLATFORM_WEB;
							if ($this->agent->is_mobile()) {
								$device = PLATFORM_MOBILE_WEB;
							}
							$data = array(
								'username' => $this->session->userdata('username'),
								'oldpass' => $this->input->post('password', TRUE),
								'bank_id' => $this->input->post('bank_id', TRUE),
								'bank_account_name' => $this->input->post('bank_account_name', TRUE),
								'bank_account_no' => $this->input->post('bank_account_no', TRUE),
								'bank_account_address' => $this->input->post('bank_account_address', TRUE),
								'player_id' => $this->session->userdata('player_id'),
							);
							$response = $this->player_model->verify_current_password($data);
							if ($response == TRUE) {
								$this->db->trans_start();
								$newData = $this->player_model->add_player_bank($data);
								$this->player_model->insert_log(LOG_BANK_PLAYER_USER_ADD, $device, $newData);
								$this->db->trans_complete();
								if ($this->db->trans_status() === TRUE) {
									$this->session->set_userdata($data);
									$json['status'] = ERROR_SUCCESS;
									$json['msg'] = $this->lang->line('error_binding_bank_successful');
								} else {
									$json['msg'] = $this->lang->line('error_binding_bank_failed');
								}
							} else {
								$json['msg'] = $this->lang->line('error_current_password_incorrect');
							}
						} else {
							$json['msg'] = $this->lang->line('error_over_player_bank_account_max');
						}
					} else {
						$json['msg'] = $this->lang->line('error_username_not_found');
					}
				} else {
					$error = array(
						'bank_id' => form_error('bank_id'),
						'bank_account_no' => form_error('bank_account_no'),
						'bank_account_name' => form_error('bank_account_name')
					);
					if (!empty($error['bank_id'])) {
						$json['msg'] = $error['bank_id'];
					} else if (!empty($error['bank_account_no'])) {
						$json['msg'] = $error['bank_account_no'];
					} else if (!empty($error['bank_account_name'])) {
						$json['msg'] = $error['bank_account_name'];
					}
				}
			} else {
				$json['msg'] = $this->lang->line('error_please_change_password_to_continue');
			}
		} else {
			$json['msg'] = $this->lang->line('error_please_login_to_continue');
		}
		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function get_player_currently_turnover()
	{
		$json = array(
			'status' => EXIT_ERROR,
			'show_turnover' => EXIT_ERROR,
			'target_turnover' => 0,
			'current_turnover' => 0,
			'capital' => 0,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);
		if ($this->session->userdata('is_logged_in') == TRUE) {
			$player_data =  $this->player_model->get_player_data($this->session->userdata('username'));
			if (!empty($player_data)) {
				$json['status'] = ERROR_SUCCESS;
				if ($player_data['player_type'] == PLAYER_TYPE_MG_MARKET) {
					$mg_url = MG_PLAYER_API_URL . "capital/" . $player_data['username'];
					$response_mg = $this->curl_get($mg_url);

					if (isset($response_mg['code']) && $response_mg['code'] == '0') {
						$json['show_turnover'] = ERROR_SUCCESS;
						$json['capital'] = $response_mg['data'];
						$json['target_turnover'] = $response_mg['data'] * 5;
						$json['current_turnover'] = $this->player_model->get_player_currently_turnover($player_data, $json['capital']);
					}
				}
			} else {
				$json['msg'] = $this->lang->line('error_username_not_found');
			}
		} else {
			$json['msg'] = $this->lang->line('error_please_login_to_continue');
		}

		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function calculate_currency_convert($id = NULL, $type = NULL, $amount = 0)
	{
		$json = array(
			'status' => EXIT_ERROR,
			'currency_rate' => 0,
			'actual_amount' => 0,
			'amount' => 0,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);

		$currency_data = $this->general_model->get_currency_data($id);
		if (!empty($currency_data)) {
			$json['status'] = ERROR_SUCCESS;
			$json['amount'] = $amount;
			if ($type == TRANSACTION_TYPE_DEPOSIT) {
				$json['currency_rate'] = bcdiv($currency_data['d_currency_rate'], 1, 4);
				$json['actual_amount'] = bcdiv($amount * $currency_data['d_currency_rate'], 1, 2);
			} else {
				$json['currency_rate'] = bcdiv($currency_data['w_currency_rate'], 1, 4);
				$json['actual_amount'] = bcdiv($amount * $currency_data['w_currency_rate'], 1, 2);
			}
		}

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function get_bank_type_by_currency($id) {
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'type' => '',
			'output' =>	'',
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);

		$currency = $this->currencies_model->get_currencies_data($id);
		if (!empty($currency)) {
			$json['type'] = $currency['type'];
			$player_bank_data = $this->bank_model->get_player_bank_list($player_bank, $currency['type']);
			if (!empty($player_bank_data)) {
				$json['status'] = ERROR_SUCCESS;
				$json['output'] = $player_bank_data;
			}
		}
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	function get_payment_gateway_bank_data($payment_gateway_id = NULL)
	{
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'currency_id' => '',
			'currency_code' => '',
			'output' =>	'',
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);
		$data = array();
		$payment_gateway_data =  $this->payment_gateway_model->get_payment_gateway_data($payment_gateway_id);
		if (!empty($payment_gateway_data)) {
			$json['status'] = ERROR_SUCCESS;
			$json['currency_id'] = $payment_gateway_data['payment_gateway_currency_id'];
			$json['currency_code'] = $payment_gateway_data['payment_gateway_currency_code'];

			$arr = explode(',', $payment_gateway_data['bank_data']);
			$arr = array_values(array_filter($arr));
			foreach ($arr as $arr_row) {
				$PBdata = array(
					'code' => $arr_row,
					'name' => $this->lang->line('bank_name_' . strtolower($arr_row)),
				);
				array_push($data, $PBdata);
			}
			$json['output'] = $data;
		}
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		exit();
	}

	public function check_login()
	{
		$is_logged_in 		= $this->is_logged_in();
		$json['status'] 	= (!empty($is_logged_in)) ? 'false' : 'true';
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();
		unset($json);
		exit();
	}

	public function dob()
	{
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);

		$config = array(
			array(
				'field' => 'dob',
				'label' => strtolower($this->lang->line('label_dob')),
				'rules' => 'trim|required',
				'errors' => array(
					'required' => $this->lang->line('error_dob')
				)
			)
		);

		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('', '');

		#Form validation
		if ($this->form_validation->run() == TRUE) {
			$dob_date 	= $this->input->post('dob', TRUE);
			$dob 		= strtotime($dob_date);
			$data = array('dob' => $dob);

			$this->db->where('player_id', $this->session->userdata('player_id'));
			$this->db->limit(1);
			$this->db->update('players', $data);
			unset($data);
			$json['status'] = EXIT_SUCCESS;
			$json['msg'] 	= $this->lang->line('error_register_successful');
			$json['result'] = $dob_date;
		} else {
			$json['msg'] 	= form_error('dob');
			$json['result'] = null;
		}

		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		unset($json);
		exit;
	}

	public function email()
	{
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);

		$config = array(
			array(
				'field' => 'email',
				'label' => strtolower($this->lang->line('label_email')),
				'rules' => 'trim|required|valid_email',
				'errors' => array(
					'required' => $this->lang->line('error_email_empty'),
					'valid_email' => $this->lang->line('error_email_incorrect')
				)
			)
		);

		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('', '');

		#Form validation
		if ($this->form_validation->run() == TRUE) {
			$email 	= $this->input->post('email', TRUE);
			$data = array('email' => $email);

			$this->db->where('player_id', $this->session->userdata('player_id'));
			$this->db->limit(1);
			$this->db->update('players', $data);
			unset($data);
			$json['status'] = EXIT_SUCCESS;
			$json['msg'] 	= $this->lang->line('error_register_successful');
			$json['result'] = $dob_date;
		} else {
			$json['msg'] 	= form_error('email');
			$json['result'] = null;
		}

		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();

		unset($json);
		exit;
	}

	public function main_banner() {
		$this->load->model('banner_model');
		$banner = $this->banner_model->get_banner_list(get_language_id(get_language()));
		$html	= null;
		
		if(isset($banner) && sizeof($banner) > 0) {			
			$html .= '<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-touch="true">
							<div class="carousel-indicators">';
			$slide = 0;
			foreach($banner as $k) {
				$slide_class = ($slide == 0) ? ' class="active" ' : null;
				$html .= '<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="'.$slide.'"'.$slide_class.'aria-current="true" aria-label="Slide '.($slide+1).'"></button>';
				$slide++;
			}			
			$html .= '	</div>
						<div class="carousel-inner">
			';
			$z=0;
			foreach($banner as $k => $v) {
				$banner 		= ($this->agent->is_mobile()) ? $v['mobile_banner'] : $v['web_banner'];
				$banner_alt 	= ($this->agent->is_mobile()) ? $v['mobile_banner_alt'] : $v['web_banner_alt'];
				
				$banner_active 	= ($z==0) ? ' active' : null;
				$upload_path 	= UPLOAD_PATH;
				
				if(!empty($v['banner_url'])) {
					$html .= '<div class="carousel-item text-center'.$banner_active.'"><a href="' . $v['banner_url'] . '"><img src="' . $upload_path.'banners/' . $banner . '" class="img-fluid" alt="' . $banner_alt . '"></a></div>';
				}
				else {
					$html .= '<div class="carousel-item text-center'.$banner_active.'"><img src="' . $upload_path.'banners/' . $banner . '" class="img-fluid" alt="' . $banner_alt . '"></div>';
				}
				
				$z++;
			}				
			
			$html .= '	</div>						
					</div>';		
		}
		else {
			$html .= '';
		}
		
		unset($banner);
		echo $html;
	}
	
	public function inbox_counter() {
		if($this->session->userdata('is_logged_in') == TRUE) {
			$status = $this->message_model->unread($this->session->userdata('player_id'));
		}
		else {
			$status = 0;
		}
		#Initial output data
		$json = array(
			'status' 	=> EXIT_SUCCESS,
			'flag' 		=> $status
		);

		#Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		unset($json);
		exit();
	}
	
	public function latest_balance(){
		$json = array(
			'status' 	=> EXIT_ERROR,
			'balance' => '0.00',			
			'msg' 		=> $this->lang->line('error_system_error')			
		);
		if($this->session->userdata('is_logged_in') == TRUE) {
			$player_data = $this->player_model->get_player_data($this->session->userdata('username'));
			
			if(!empty($player_data)) {
				$main_balance = $player_data['points'];
				if($player_data['last_in_game'] != null ) {
					$provider_code 	= $player_data['last_in_game'];
					$api_data 		= $this->api_model->get_api_data(SYSTEM_API_AGENT_ID);
					if( ! empty($api_data)) {
						$account_data = $this->player_model->get_player_game_account_data($provider_code,$player_data['player_id']);
						if( ! empty($account_data)) {
							#MAINTENANCE
							$game_maintenance_data 	= $this->game_model->get_game_maintenance_data($provider_code);
							$is_maintenance 		= $this->game_model->maintenance_flag($game_maintenance_data);
							if($is_maintenance) {
								$json['status'] = EXIT_MAINTANANCE;
								$json['msg'] 	= $this->lang->line('label_maintenance').'4';
								$game 			= 0;
							}
							else {
								$device 	= ($this->agent->is_mobile()) ? PLATFORM_MOBILE_WEB : PLATFORM_WEB;	
								$syslang 	= ((get_language_id(get_language()) == LANG_ZH_CN OR get_language_id(get_language()) == LANG_ZH_HK OR get_language_id(get_language()) == LANG_ZH_TW) ? LANG_ZH_CN : LANG_EN);							
								$url 		= HUB_URL;
								$signature 	= md5($api_data['agent_id'] . $account_data['game_provider_code'] . $account_data['username'] . $api_data['secret_key']);

								$param_array = array(
									'method' 		=> 'GetBalance',
									'agent_id' 		=> $api_data['agent_id'],
									'syslang' 		=> $syslang,
									'device' 		=> $device,
									'provider_code' => $account_data['game_provider_code'],
									'player_id' 	=> $account_data['player_id'],
									'game_id' 		=> $account_data['game_id'],
									'username' 		=> $account_data['username'],
									'password' 		=> $account_data['password'],
									'signature' 	=> $signature
								);
								
								$response 		= $this->curl_json($url, $param_array);
								$result_array 	= json_decode($response, TRUE);
								
								if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0') {					
									$game 			= $result_array['result'];
									$json['status']	= EXIT_SUCCESS;
									$json['msg'] = $this->lang->line('error_get_the_current_balance');
									unset($result_array);
								}
								else {
									$json['status'] = EXIT_MAINTANANCE;
									$json['msg'] 	= $this->lang->line('label_maintenance').'5';
									$game 			= 0;
								}
							}
							unset($account_data);
						}
						else {
							$game = 0;
						}
						unset($api_data);
					}
					else {
						$game = 0;
					}
				}
				else {
					$game = 0;
				}
				
				$json['balance'] = val_decimal(($main_balance+$game),2);
				
				unset($player_data);
			}
			else{
				$json['msg'] = $this->lang->line('error_username_not_found');
			}
		}
		else{
			$json['msg']	= $this->lang->line('error_please_login_to_continue');
		}
		
		#Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		unset($json);
		exit();
	}
}
