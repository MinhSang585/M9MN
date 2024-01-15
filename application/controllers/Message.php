<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Message extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		if($this->is_logged_in() != '') {
			redirect('','location');
		}
		$this->load->model(array('message_model'));
	}

	public function index() {
		$this->save_current_url('message');
		$data['seo'] 	= $this->seo_model->get_seo_data(PAGE_INBOX);
		$previous_week 	= strtotime("-2 week");
		$data['messages'] = $this->message_model->get_message_by_pid($this->session->userdata('player_id'),get_language_id($this->session->userdata('lang')),$previous_week);
		$data['player'] = $this->player_model->get_player_data($this->session->userdata('username'));
		
		if($this->agent->is_mobile())
		{
			$this->load->view('mobile/account_personal_message', $data);
		}
		else
		{
			$this->load->view('web/account_personal_message', $data);
		}	
	}

	public function message_view(){
		$this->save_current_url('message/message_view');
		if($this->uri->segment(3)===false) {
			echo '<center>I.'.$this->lang->line('system_fail').'</center>';
		}
		else {
			if(is_numeric($this->uri->segment(3))) {
				$msg_id = $this->uri->segment(3);
				$data['seo'] 	= $this->seo_model->get_seo_data(PAGE_INBOX);

				#UPDATE MESSAGE CHECKED
				$dbdata = array(
					'is_read' 		=> 2,
					'updated_date' 	=> time()
				);
				$this->message_model->update_message($msg_id,$this->session->userdata('player_id'),$dbdata);
				unset($dbdata);

				$data['message_details'] = $this->message_model->get_message_by_id($msg_id,$this->session->userdata('player_id'),get_language_id($this->session->userdata('lang')));
				
				$this->load->view(SYSTEM_THEME . '/message_detail', $data);					
			}
			else {
				echo '<center>II.'.$this->lang->line('system_fail').'</center>';
			}
		}
	}
	
	public function update_message(){
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);
		if($this->session->userdata('is_logged_in') == TRUE){
			if($this->uri->segment(3)==false) {
				$json['status'] = ERROR_SYSTEM_ERROR;
				$json['msg'] 	= $this->lang->line('error_system_error');
			}
			else {
				if(is_numeric($this->uri->segment(3))) {
					$msg_id = $this->uri->segment(3);
					
					$dbdata = array(
						'is_read' 		=> 2,
						'updated_date' 	=> time()
					);
					$this->message_model->update_message($msg_id,$this->session->userdata('player_id'),$dbdata);
					unset($dbdata);
					
					$json['status'] = ERROR_SUCCESS;
					$json['msg'] = $this->lang->line('error_success');
				}
			}
		}else{
			$json['msg'] = $this->lang->line('error_please_login_to_continue');
		}

		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		unset($json);
		exit();
	}
	
	public function delete_message(){
		$json = array(
			'status' => EXIT_ERROR,
			'msg' => $this->lang->line('error_system_error'),
			'csrfTokenName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);
		if($this->session->userdata('is_logged_in') == TRUE){
			if($this->uri->segment(3)==false) {
				//UPDATE MESSAGE CHECKED
				$DBdata = array(
					'pm_status' => 0,
					'pm_update_date' => time()
				);
				$this->db->trans_start();
				$this->message_model->update_message_by_pid($this->session->userdata('player_id'),$DBdata);
				$this->db->trans_complete();
				if ($this->db->trans_status() === TRUE){
					$json['status'] = ERROR_SUCCESS;
					$json['msg'] = $this->lang->line('error_success');
				}else{
					$json['status'] = ERROR_SYSTEM_ERROR;
					$json['msg'] = $this->lang->line('error_system_error');
				}
			}else {
				if(is_numeric($this->uri->segment(3))) {
					$msg_id = $this->uri->segment(3);
					//UPDATE MESSAGE CHECKED
					$DBdata = array(
						'pm_status' => 0,
						'pm_update_date' => time()
					);
					$this->db->trans_start();
					$this->message_model->update_message($msg_id,$DBdata);
					$this->db->trans_complete();
					if ($this->db->trans_status() === TRUE){
						$json['status'] = ERROR_SUCCESS;
						$json['msg'] = $this->lang->line('error_success');
					}else{
						$json['status'] = ERROR_SYSTEM_ERROR;
						$json['msg'] = $this->lang->line('error_system_error');
					}
				}
			}
		}else{
			$json['msg'] = $this->lang->line('error_please_login_to_continue');
		}

		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();

		exit();
	}
}
