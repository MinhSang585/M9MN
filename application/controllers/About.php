<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index() 
	{
		$this->save_current_url('about');
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_ABOUT_US);
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/about', $data);
		}
		else
		{
			$this->load->view('web/about', $data);
		}
	}
	
	public function testing(){
	    $_POST['username'] = strtolower("Lemon88");
	    ad($this->input->post('username', TRUE));
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
								'rules' => 'trim|required|integer',
								'errors' => array(
													'required' => $this->lang->line('error_mobile_empty'),
													'integer' => $this->lang->line('error_mobile_incorrect')
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
		if ($this->form_validation->run() == TRUE)
		{
		    echo "success";
		}else{
		    echo $this->form_validation->error_array();
		    $error = array(
				'username' => form_error('username'), 
				'password' => form_error('password'), 
				'passconf' => form_error('passconf'),
				'full_name' => form_error('full_name'), 
				'nickname' => form_error('nickname'), 
				'email' => form_error('email'), 
				'mobile' => form_error('mobile'), 
				'captcha' => form_error('captcha')
			);
			ad($error);
		}
	}
}