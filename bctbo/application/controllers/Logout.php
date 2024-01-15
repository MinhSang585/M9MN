<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('cookie');
	}
	
	public function index()
	{
		if($this->session->userdata('user_type')) {
			$this->_session_destroy(1);
			
			$data = array(
						'page_title' => $this->lang->line('system_name'),
						'msg_alert' => $this->lang->line('notice_successful_logout'),
						'msg_icon' => 1,
					);
			$this->load->view('login_page', $data);
		}
		else
		{
			redirect('login');
		}	
	}
	
	public function force()
	{
		if($this->session->userdata('user_type')) {
			$this->_session_destroy(0);
			
			$data = array(
						'page_title' => $this->lang->line('system_name'),
						'msg_alert' => $this->lang->line('notice_multiple_login'),
						'msg_icon' => 2,
					);
			$this->load->view('login_page', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	public function denied()
	{
		redirect('login');
	}
	
	private function _session_destroy($type = NULL)
	{
		$this->db->trans_start();
		
		if($this->session->userdata('user_group') == USER_GROUP_USER) 
		{
			if($type == 1)
			{
				$this->user_model->clear_login_token();
			}
			
			$this->user_model->insert_log(LOG_LOGOUT);
		}
		else
		{
			if($type == 1)
			{
				$this->account_model->clear_login_token();
			}
			
			$this->account_model->insert_log(LOG_LOGOUT);
		}
		
		$this->db->trans_complete();
			
		$userdata = array(
					'user_id',
					'nickname',
					'username',
					'user_type',
					'active',
					'permissions',
					'upline',
					'user_group',
					'last_login_date',
					'login_token',
					'is_logged_in',
					'root_user_id',
					'root_username',
					'search_players',
					'search_deposits',
					'search_withdrawals',
					'search_report_winloss',
					'search_report_transactions',
					'search_report_points',
					'search_report_cash',
					'search_report_wallets',
					'search_report_logins'
				);
		$this->session->unset_userdata($userdata);
		
		session_destroy();
	}
}
