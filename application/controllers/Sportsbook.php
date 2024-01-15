<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sportsbook extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index() 
	{
		$this->save_current_url('sportsbook');
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_SPORTSBOOK);
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/sportsbook', $data);
		}
		else
		{
			$this->load->view('web/sportsbook', $data);
		}
	}
}