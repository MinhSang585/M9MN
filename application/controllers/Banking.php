<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banking extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index() 
	{
		$this->save_current_url('banking');
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_FAQ);
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/banking', $data);
		}
		else
		{
			$this->load->view('web/banking', $data);
		}
	}
}