<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tv extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index() 
	{
		$this->save_current_url('tv');
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_MOVIE);
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/tv', $data);
		}
		else
		{
			$this->load->view('web/tv', $data);
		}
	}
}