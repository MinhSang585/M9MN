<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vip extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index() 
	{
		$this->save_current_url('vip');
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_VIP);
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/vip', $data);
		}
		else
		{
			$this->load->view('web/vip', $data);
		}
	}
}