<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
		if($this->is_logged_in() == '') 
		{
			redirect('home');
		}
	}
	
	public function index() 
	{
		$this->save_current_url('login');
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_LOGIN);
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/login', $data);
		}
		else
		{
			$this->load->view('web/login', $data);
		}
	}
}