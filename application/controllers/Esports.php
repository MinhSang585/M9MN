<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Esports extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index() 
	{
		$this->save_current_url('esports');
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_ESPORTS);
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/esports', $data);
		}
		else
		{
			$this->load->view('web/esports', $data);
		}
	}
}