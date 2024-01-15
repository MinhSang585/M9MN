<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Profile extends MY_Controller {



	public function __construct()

	{

		parent::__construct();

		$this->load->model(array('user_model'));

		$is_logged_in = $this->is_logged_in();

		if( ! empty($is_logged_in)) 

		{

			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';

		}

	}

		

	public function index()

	{

		$this->save_current_url('profile');

		$data['page_title'] = $this->lang->line('title_profile');

		$data['user_data'] = $this->user_model->get_user_data_by_username($this->session->userdata('username'));
		
		$data['username'] = $this->session->userdata('username');
		
		$this->load->view('profile_update', $data);

	}

	

	public function submit()

	{

		//Initial output data

		$json = array(

					'status' => EXIT_ERROR, 

					'msg' => array(

									'oldpass_error' => '',

									'password_error' => '',

									'passconf_error' => '',

									'general_error' => ''

								), 

					'csrfTokenName' => $this->security->get_csrf_token_name(), 

					'csrfHash' => $this->security->get_csrf_hash()

				);

		

		//Set form rules

		$config = array(

						array(

								'field' => 'oldpass',

								'label' => strtolower($this->lang->line('label_current_password')),

								'rules' => 'trim|required',

								'errors' => array(

													'required' => $this->lang->line('error_enter_current_password')

											)

						),

						array(

								'field' => 'password',

								'label' => strtolower($this->lang->line('label_new_password')),

								'rules' => 'trim|required|differs[oldpass]|min_length[6]|max_length[15]|regex_match[/^[A-Za-z0-9!#$^*]+$/]',

								'errors' => array(

													'required' => $this->lang->line('error_enter_new_password'),

													'differs' => $this->lang->line('error_new_password_must_differ'),

													'min_length' => $this->lang->line('error_invalid_new_password'),

													'max_length' => $this->lang->line('error_invalid_new_password'),

													'regex_match' => $this->lang->line('error_invalid_new_password')

											)

						),

						array(

								'field' => 'passconf',

								'label' => strtolower($this->lang->line('label_confirm_new_password')),

								'rules' => 'trim|required|matches[password]',

								'errors' => array(

													'required' => $this->lang->line('error_enter_confirm_new_password'),

													'matches' => $this->lang->line('error_confirm_new_password_not_match')

											)

						)

					);		

					

		$this->form_validation->set_rules($config);

		$this->form_validation->set_error_delimiters('', '');

		

		//Form validation

		if ($this->form_validation->run() == TRUE)

		{

			$response = FALSE;

			if($this->session->userdata('user_group') == USER_GROUP_USER)

			{

				$response = $this->user_model->verify_current_password();

			}

			else

			{

				$response = $this->account_model->verify_current_password();

			}

			

			if($response == TRUE) 

			{

				//Database update

				$this->db->trans_start();

				

				if($this->session->userdata('user_group') == USER_GROUP_USER)

				{

					$this->user_model->update_password();

					$this->user_model->insert_log(LOG_CHANGE_PASSWORD);

				}

				else

				{

					$this->account_model->update_password();

					$this->account_model->insert_log(LOG_CHANGE_PASSWORD);

				}

				

				$this->db->trans_complete();

				

				if ($this->db->trans_status() === TRUE)

				{

					$json['status'] = EXIT_SUCCESS;

					$json['msg'] = $this->lang->line('success_change_password');

				}

				else

				{

					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');

				}

			}

			else

			{

				$json['msg']['oldpass_error'] = $this->lang->line('error_invalid_current_password');

			}

		}

		else 

		{

			$json['msg']['oldpass_error'] = form_error('oldpass');

			$json['msg']['password_error'] = form_error('password');

			$json['msg']['passconf_error'] = form_error('passconf');

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

