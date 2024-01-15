<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Miscellaneous extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('miscellaneous_model');
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}
		
	public function index()
	{
		if(permission_validation(PERMISSION_MISCELLANEOUS_UPDATE) == TRUE)
		{
			$this->save_current_url('miscellaneous');
			
			$data = $this->miscellaneous_model->get_miscellaneous();
			$data['page_title'] = $this->lang->line('title_miscellaneous');
			$miscellaneous = json_decode(MISCELLANEOUS_LANGUAGES, TRUE);
			$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
			if(sizeof($miscellaneous) > 0){
				foreach($miscellaneous as $k => $v){
					if(sizeof($lang)>0){
						$dataLang[$v] = $this->miscellaneous_model->get_miscellaneous_lang_data($v);
					}
				}
			}
			$data['miscellaneous_lang'] = $dataLang;
			$this->load->view('miscellaneous_update', $data);
		}
		else
		{
			redirect('home');
		}
	}
	
	public function submit()
	{
		if(permission_validation(PERMISSION_MISCELLANEOUS_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'system_type_error' => '',
					'min_deposit_error' => '',
					'max_deposit_error' => '',
					'min_withdrawal_error' => '',
					'max_withdrawal_error' => '',
					'win_loss_suspend_limit_error' => '',
					'player_bank_account_max_error' => '',
					'general_error' => ''
				), 
				'deposit_file_name' => '',
				'withdrawal_file_name' => '',
				'online_deposit_sound_file_name' => '',
				'credit_card_deposit_sound_file_name' => '',
				'hypermart_deposit_sound_file_name' => '',						
				'risk_sound_file_name' => '',
				'risk_frozen_sound_file_name' => '',
				'blacklist_sound_file_name' => '',
				'player_bank_image_sound_file_name' => '',
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			//Set form rules
			$config = array(
				array(
						'field' => 'min_deposit',
						'label' => strtolower($this->lang->line('label_min_deposit')),
						'rules' => 'trim|required|integer',
						'errors' => array(
											'required' => $this->lang->line('error_enter_min_deposit'),
											'integer' => $this->lang->line('error_only_digits_allowed')
									)
				),
				array(
						'field' => 'max_deposit',
						'label' => strtolower($this->lang->line('label_max_deposit')),
						'rules' => 'trim|required|integer',
						'errors' => array(
											'required' => $this->lang->line('error_enter_max_deposit'),
											'integer' => $this->lang->line('error_only_digits_allowed')
									)
				),
				array(
						'field' => 'min_withdrawal',
						'label' => strtolower($this->lang->line('label_min_withdrawal')),
						'rules' => 'trim|required|integer',
						'errors' => array(
											'required' => $this->lang->line('error_enter_min_withdrawal'),
											'integer' => $this->lang->line('error_only_digits_allowed')
									)
				),
				array(
						'field' => 'max_withdrawal',
						'label' => strtolower($this->lang->line('label_max_withdrawal')),
						'rules' => 'trim|required|integer',
						'errors' => array(
											'required' => $this->lang->line('error_enter_max_withdrawal'),
											'integer' => $this->lang->line('error_only_digits_allowed')
									)
				),
				array(
						'field' => 'win_loss_suspend_limit',
						'label' => strtolower($this->lang->line('label_win_loss_suspend_limit')),
						'rules' => 'trim|required|integer',
						'errors' => array(
											'required' => $this->lang->line('error_enter_win_loss_suspend_limit'),
											'integer' => $this->lang->line('error_only_digits_allowed')
									)
				),
				array(
						'field' => 'player_bank_account_max',
						'label' => strtolower($this->lang->line('label_player_bank_account_max')),
						'rules' => 'trim|required|integer',
						'errors' => array(
											'required' => $this->lang->line('error_enter_player_bank_account_max'),
											'integer' => $this->lang->line('error_only_digits_allowed')
									)
				),
				array(
						'field' => 'player_credit_card_max',
						'label' => strtolower($this->lang->line('label_player_credit_card_max')),
						'rules' => 'trim|integer',
						'errors' => array(
								'integer' => $this->lang->line('error_only_digits_allowed')
						)
				),
				array(
						'field' => 'system_type',
						'label' => strtolower($this->lang->line('label_type')),
						'rules' => 'trim|required',
						'errors' => array(
								'required' => $this->lang->line('error_select_type')
						)
				),
			);		
			
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$allow_to_update = TRUE;
				
				$config['upload_path'] = SOUND_PATH;
				$config['max_size'] = SOUND_FILE_SIZE;
				$config['allowed_types'] = 'mp3';
				$config['overwrite'] = TRUE;
				
				$this->load->library('upload', $config);
				
				if(isset($_FILES['deposit_sound']['size']) && $_FILES['deposit_sound']['size'] > 0)
				{
					$new_name = time().rand(1000,9999).".".pathinfo($_FILES["deposit_sound"]['name'], PATHINFO_EXTENSION);
					$config['file_name']  = $new_name;
					$this->upload->initialize($config);
					if( ! $this->upload->do_upload('deposit_sound')) 
					{
						$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
						$allow_to_update = FALSE;
					}else{
						$_FILES["deposit_sound"]['name'] = $new_name;
					}
				}

				if($allow_to_update == TRUE)
				{
					if(isset($_FILES['online_deposit_sound']['size']) && $_FILES['online_deposit_sound']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["online_deposit_sound"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('online_deposit_sound')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["online_deposit_sound"]['name'] = $new_name;
						}
					}
				}

				if($allow_to_update == TRUE)
				{
					if(isset($_FILES['credit_card_deposit_sound']['size']) && $_FILES['credit_card_deposit_sound']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["credit_card_deposit_sound"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('credit_card_deposit_sound')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["credit_card_deposit_sound"]['name'] = $new_name;
						}
					}
				}

				if($allow_to_update == TRUE)
				{
					if(isset($_FILES['hypermart_deposit_sound']['size']) && $_FILES['hypermart_deposit_sound']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["hypermart_deposit_sound"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('hypermart_deposit_sound')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["hypermart_deposit_sound"]['name'] = $new_name;
						}
					}
				}

				if($allow_to_update == TRUE)
				{
					if(isset($_FILES['withdrawal_sound']['size']) && $_FILES['withdrawal_sound']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["withdrawal_sound"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('withdrawal_sound')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["withdrawal_sound"]['name'] = $new_name;
						}
					}
				}
				
				if($allow_to_update == TRUE)
				{
					if(isset($_FILES['risk_sound']['size']) && $_FILES['risk_sound']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["risk_sound"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('risk_sound')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["risk_sound"]['name'] = $new_name;
						}
					}
				}

				if($allow_to_update == TRUE)
				{
					if(isset($_FILES['risk_frozen_sound']['size']) && $_FILES['risk_frozen_sound']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["risk_frozen_sound"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('risk_frozen_sound')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["risk_frozen_sound"]['name'] = $new_name;
						}
					}
				}

				if($allow_to_update == TRUE)
				{
					if(isset($_FILES['blacklist_sound']['size']) && $_FILES['blacklist_sound']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["blacklist_sound"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('blacklist_sound')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["blacklist_sound"]['name'] = $new_name;
						}
					}
				}

				if($allow_to_update == TRUE)
				{
					if(isset($_FILES['player_bank_image_sound']['size']) && $_FILES['player_bank_image_sound']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["player_bank_image_sound"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('player_bank_image_sound')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["player_bank_image_sound"]['name'] = $new_name;
						}
					}
				}
				
				if($allow_to_update == TRUE)
				{
					//Database update
					$this->db->trans_start();
					$oldData = $this->miscellaneous_model->get_miscellaneous();
					$miscellaneous = json_decode(MISCELLANEOUS_LANGUAGES, TRUE);
					$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
					if(sizeof($miscellaneous) > 0){
						foreach($miscellaneous as $k => $v){
							if(sizeof($lang)>0){
								$oldDataLang[$v] = $this->miscellaneous_model->get_miscellaneous_lang_data($v);
								foreach($lang as $l => $w){
									if(isset($oldDataLang[$v][$w])){
										$this->miscellaneous_model->update_miscellaneous_content($v,$w);
									}else{
										$this->miscellaneous_model->add_miscellaneous_content($v,$w);
									}
								}
							}
						}
					}


					$newData = $this->miscellaneous_model->update_miscellaneous();
					if(sizeof($miscellaneous) > 0){
						foreach($miscellaneous as $k => $v){
							if(sizeof($lang)>0){
								$newDataLang[$v] = $this->miscellaneous_model->get_miscellaneous_lang_data($v);
							}
						}
					}
					$oldData['lang'] = $oldDataLang;
					$newData['lang'] = $newDataLang;

					
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_MISCELLANEOUS_UPDATE, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_MISCELLANEOUS_UPDATE, $newData, $oldData);
					}
			
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						//Delete old banner
						if(isset($_FILES['deposit_sound']['size']) && $_FILES['deposit_sound']['size'] > 0 && !empty($oldData['deposit_sound']))
						{
							unlink(FCPATH . SOUND_SOURCE_PATH . $oldData['deposit_sound']);
						}

						if(isset($_FILES['online_deposit_sound']['size']) && $_FILES['online_deposit_sound']['size'] > 0 && !empty($oldData['online_deposit_sound']))
						{
							unlink(FCPATH . SOUND_SOURCE_PATH . $oldData['online_deposit_sound']);
						}

						if(isset($_FILES['credit_card_deposit_sound']['size']) && $_FILES['credit_card_deposit_sound']['size'] > 0 && !empty($oldData['credit_card_deposit_sound']))
						{
							unlink(FCPATH . SOUND_SOURCE_PATH . $oldData['credit_card_deposit_sound']);
						}

						if(isset($_FILES['hypermart_deposit_sound']['size']) && $_FILES['hypermart_deposit_sound']['size'] > 0 && !empty($oldData['hypermart_deposit_sound']))
						{
							unlink(FCPATH . SOUND_SOURCE_PATH . $oldData['hypermart_deposit_sound']);
						}
						
						if(isset($_FILES['withdrawal_sound']['size']) && $_FILES['withdrawal_sound']['size'] > 0 && !empty($oldData['withdrawal_sound']))
						{
							unlink(FCPATH . SOUND_SOURCE_PATH . $oldData['withdrawal_sound']);
						}

						if(isset($_FILES['risk_sound']['size']) && $_FILES['risk_sound']['size'] > 0 && !empty($oldData['risk_sound']))
						{
							unlink(FCPATH . SOUND_SOURCE_PATH . $oldData['risk_sound']);
						}

						if(isset($_FILES['risk_frozen_sound']['size']) && $_FILES['risk_frozen_sound']['size'] > 0 && !empty($oldData['risk_frozen_sound']))
						{
							unlink(FCPATH . SOUND_SOURCE_PATH . $oldData['risk_frozen_sound']);
						}

						if(isset($_FILES['blacklist_sound']['size']) && $_FILES['blacklist_sound']['size'] > 0 && !empty($oldData['blacklist_sound']))
						{
							unlink(FCPATH . SOUND_SOURCE_PATH . $oldData['blacklist_sound']);
						}

						if(isset($_FILES['player_bank_image_sound']['size']) && $_FILES['player_bank_image_sound']['size'] > 0 && !empty($oldData['player_bank_image_sound']))
						{
							unlink(FCPATH . SOUND_SOURCE_PATH . $oldData['player_bank_image_sound']);
						}
						
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_updated');
						$json['deposit_file_name'] = $_FILES['deposit_sound']['name'];
						$json['online_deposit_sound_file_name'] = $_FILES['online_deposit_sound']['name'];
						$json['credit_card_deposit_sound_file_name'] = $_FILES['credit_card_deposit_sound']['name'];
						$json['hypermart_deposit_sound_file_name'] = $_FILES['hypermart_deposit_sound']['name'];
						$json['withdrawal_file_name'] = $_FILES['withdrawal_sound']['name'];
						$json['risk_file_name'] = $_FILES['risk_sound']['name'];
						$json['risk_frozen_sound_file_name'] = $_FILES['risk_frozen_sound']['name'];
						$json['blacklist_sound_file_name'] = $_FILES['blacklist_sound']['name'];
						$json['player_bank_image_sound_file_name'] = $_FILES['player_bank_image_sound']['name'];
					}
					else
					{
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
					}
				}	
			}
			else 
			{
				$json['msg']['min_deposit_error'] = form_error('min_deposit');
				$json['msg']['max_deposit_error'] = form_error('max_deposit');
				$json['msg']['min_withdrawal_error'] = form_error('min_withdrawal');
				$json['msg']['max_withdrawal_error'] = form_error('max_withdrawal');
				$json['msg']['win_loss_suspend_limit_error'] = form_error('win_loss_suspend_limit');
				$json['msg']['system_type_error'] = form_error('system_type');
				$json['msg']['player_bank_account_max_error'] = form_error('player_bank_account_max');
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
}
