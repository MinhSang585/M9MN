<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Terms extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index() 
	{
		$this->save_current_url('terms');
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_TNC);
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/terms', $data);
		}
		else
		{
			$this->load->view('web/terms', $data);
		}
	}
}