<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot extends MY_Controller {

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
		$this->save_current_url('forgot');
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_FORGOT_PASSWORD);
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/forgot', $data);
		}
		else
		{
			$this->load->view('web/forgot', $data);
		}
	}
}