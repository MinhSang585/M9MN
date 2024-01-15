<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
		if($this->is_logged_in() == '') 
		{
			redirect('home');
		}
	}
	
	public function index() 
	{
		$this->save_current_url('register');
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_REGISTER);
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/register', $data);
		}
		else
		{
			$this->load->view('web/register', $data);
		}
	}
	
	public function referral($username = NULL) 
	{
		$this->save_current_url('register/referral/' . $username);
		
		if( ! empty($username))
		{
			$this->session->set_userdata('referrer', $username);
		}
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_REGISTER);
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/register', $data);
		}
		else
		{
			$this->load->view('web/register', $data);
		}
	}													
	
	public function invite_agent($username = NULL) 
	{
		$this->save_current_url('register/invite_agent/' . $username);
		
		if( ! empty($username))
		{
			$this->session->set_userdata('referrer', $username);
		}
		
		$data['seo'] = $this->seo_model->get_seo_data(PAGE_REGISTER);
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/register', $data);
		}
		else
		{
			$this->load->view('web/register', $data);
		}
	}
}