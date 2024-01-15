<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('contact_model');
	}
	
	public function index() 
	{
		$this->save_current_url('contact');
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_CONTACT_US);
		$arr = $this->contact_model->get_contact_list();
		
		$data['contact'] = array();
		foreach($arr as $k => $v)
		{
			$data['contact'][$v['contact_id']] = array('name' => $this->lang->line($v['im_name']), 'value' => $v['im_value']);
		}
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/contact', $data);
		}
		else
		{
			$this->load->view('web/contact', $data);
		}
	}
}