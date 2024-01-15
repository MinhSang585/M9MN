<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('role_model'));
		if($this->is_logged_in() == '') 
		{
			redirect('player');
		}else{
			$this->load->helper('cookie');
		}
	}
		
	public function index()
	{
		$data['page_title'] = $this->lang->line('system_name');
		$this->load->view('login_page', $data);
	}
	
	public function submit()
	{
		//Initial output data
		$json = array(
					'status' => EXIT_ERROR, 
					'msg' => array(
									'username_error' => '',
									'password_error' => '',
									'general_error' => ''
								), 
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
		
		//Set form rules
		$config = array(
						array(
								'field' => 'username',
								'label' => strtolower($this->lang->line('label_username')),
								'rules' => 'trim|required',
								'errors' => array(
													'required' => $this->lang->line('error_enter_username')
											)
						),
						array(
								'field' => 'password',
								'label' => strtolower($this->lang->line('label_password')),
								'rules' => 'trim|required',
								'errors' => array(
													'required' => $this->lang->line('error_enter_password'),
											)
						)
					);		
					
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('', '');
		
		//Form validation
		if ($this->form_validation->run() == TRUE)
		{
			$response = $this->user_model->verify_login();											// get table users
			if( ! isset($response['is_logged_in'])) 
			{
				$response = $this->account_model->verify_login();									// get table sup_account
			}
			
			if(isset($response['is_logged_in'])) 
			{
				$permissionUser = $response['permissions'];											// get permissions of table users
				$response['permissions'] = "";

				$flag = $response['flag'];

				if($flag == 0)
				{
					if(!empty($response['user_role'])){
						$role_data = $this->role_model->get_role_data($response['user_role']);		// get table user_role
						if(!empty($role_data) && $role_data['active'] == STATUS_ACTIVE){
							$response['permissions'] = $role_data['permissions'];					// if flag == 0, get permissions of table user_role (print_r($role_data['permissions']);)
						}
					}
				}

				else {
					if(!empty($response['user_role'])){
						$role_data = $this->role_model->get_role_data($response['user_role']);
						if(!empty($role_data) && $role_data['active'] == STATUS_ACTIVE){
							$response['permissions'] = $permissionUser;								// if flag == 1, get permissions of table users (print_r($permissionUser);)
						}
					}
				}

				$login_status = STATUS_FAIL;
				
				if($response['is_logged_in'] == FALSE)
				{
					$json['msg']['general_error'] = $this->lang->line('error_invalid_login');
				}
				else if($response['active'] == STATUS_ACTIVE)
				{
					$ip_allow = false;
					if(empty($response['white_list_ip'])){
						$ip_allow = true;
					}else{
						$white_list_ip = array_filter(explode(',', $response['white_list_ip']));
						if(in_array($this->input->ip_address(), $white_list_ip)){
							$ip_allow = true;	
						}
					}
					if($ip_allow){
						if($response['user_group'] == USER_GROUP_USER) 
						{
							$response['root_user_id'] = $response['user_id'];
							$response['root_username'] = $response['username'];
						}
						else
						{
							$upline = $this->user_model->get_user_data_by_username($response['upline']);
							$response['root_user_id'] = $upline['user_id'];
							$response['root_username'] = $upline['username'];
						}
						
						$this->session->set_userdata($response);
						#Remember me
						$username = $this->input->post('username', TRUE);
						$password = $this->input->post('password', TRUE);
						if($this->input->post('remember')){
							$this->input->set_cookie('username', $username, 86500);
							$this->input->set_cookie('password', $password, 86500);
						}
						else {
							delete_cookie('username');
							delete_cookie('password');
						}
						$login_status = STATUS_SUCCESS;
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = site_url('player');
					}else{
						$json['msg']['general_error'] = $this->lang->line('error_ip_address_not_allow');
					}
				}
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_account_suspended');
				}
				
				//Database update
				$this->db->trans_start();
				
				$this->general_model->insert_login_report($response, $login_status);
				
				if($login_status == STATUS_SUCCESS) 
				{
					if($response['user_group'] == USER_GROUP_USER) 
					{
						$this->user_model->update_last_login($response);
						$this->user_model->insert_log(LOG_LOGIN, $response);
					}
					else
					{
						$this->account_model->update_last_login($response);
						$this->account_model->insert_log(LOG_LOGIN, $response);
					}
				}
				
				$this->db->trans_complete();
			}
			else
			{
				$json['msg']['general_error'] = $this->lang->line('error_invalid_login');
			}
		}
		else 
		{
			$json['msg']['username_error'] = form_error('username');
			$json['msg']['password_error'] = form_error('password');
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
