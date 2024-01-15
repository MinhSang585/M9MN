<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('banner_model');
		#get_platform();
	}
	public function index()
	{
		$code = $this->input->get('code');
		if ($code) {
			redirect('register');
		}
		$this->save_current_url('home');
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_HOME);
		$data['banner'] = $this->banner_model->get_banner_list(get_language_id($this->session->userdata('lang')));
		// ad($data);exit;
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/home', $data);
		}
		else
		{
			$this->load->view('web/home', $data);
		}
	}
	public function testing(){
	    ad($this->session->userdata('lang'));
	}
	public function login(){
		$is_logged_in = $this->is_logged_in();
		if(!empty($is_logged_in))
		{
			$this->save_current_url('home/login');
			$this->load->view('mobile/login');
		}
		else {
			redirect('home', 'refresh');
		}
	}
	public function loading(){
		$this->load->view('loading');
	}
	public function verify_session()
	{
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in))
		{
			echo site_url($is_logged_in);
		}
	}
}