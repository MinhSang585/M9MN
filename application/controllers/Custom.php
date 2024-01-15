<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Custom extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('account_model'));
	}

	public function check_register_mobile($mobile){
		$mobile_exist = $this->account_model->check_mobile_exits($mobile);

		if($mobile_exist){
			$json['status'] = EXIT_ERROR;
			$json['msg'] = $this->lang->line('error_mobile_already_exits');
		}else{
			$json['status'] = EXIT_SUCCESS;
		}
		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();
		unset($json);
		exit();
	}

	public function check_player_bank($bankno){
		$bankno_exist = $this->account_model->check_bankno_exits($bankno);

		if($bankno_exist){
			$json['status'] = EXIT_ERROR;
			$json['msg'] = $this->lang->line('error_bankacc_already_exits');
		}else{
			$json['status'] = EXIT_SUCCESS;
		}
		//Output
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();
		unset($json);
		exit();
	}
}