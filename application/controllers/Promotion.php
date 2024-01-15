<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promotion extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('promotion_model'));
	}
	
	public function index() 
	{
		$this->save_current_url('promotion');
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_PROMOTION);
		$data['promotion'] = $this->promotion_model->get_promotion_banner_list(get_language_id($this->session->userdata('lang')));

		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/promotion', $data);
		}
		else
		{
			$this->load->view('web/promotion', $data);
		}
	}

	public function showModalInfo($id) {

		$this->save_current_url('promotion');
		$data['promotion_lang'] = $this->promotion_model->get_promotion_lang_data_by_id($id);
		echo json_encode($data['promotion_lang']);
		exit;
    }
}