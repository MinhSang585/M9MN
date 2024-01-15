<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lottery extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index() 
	{
		$this->save_current_url('lottery');
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_LOTTERY);
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/lottery', $data);
		}
		else
		{
			$this->load->view('web/lottery', $data);
		}
	}
}