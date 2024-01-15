<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Casino extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index() 
	{
		$this->save_current_url('casino');
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_LIVE_CASINO);
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/casino', $data);
		}
		else
		{
			$this->load->view('web/casino', $data);
		}
	}
}