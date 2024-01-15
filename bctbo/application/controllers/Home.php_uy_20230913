<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('deposit_model', 'player_model', 'withdrawal_model','bonus_model','promotion_model','miscellaneous_model'));
	}
	public function index()
	{
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
		else
		{
			$this->save_current_url('home');
			$data['page_title'] = $this->lang->line('title_home');
			//$data['deposit'] = $this->deposit_model->today_total_only_agent_deposit();
			//$data['withdrawal'] = $this->withdrawal_model->today_total_only_agent_withdrawal();
			//$data['player'] = $this->player_model->today_total_player();
			//$data['player_win_loss'] = $this->player_model->player_today_win_loss();
			//$data['active_player'] = $this->player_model->player_active_five_minute();
			//$data['promotion'] = $this->promotion_model->today_total_promotion();
			//$data['bonus'] = $this->bonus_model->today_total_bonus();
			$this->load->view('home_page', $data);
		}	
	}
	public function verify_session()
	{
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo site_url($is_logged_in);
		}
	}
	public function today_deposit() {
		$deposit 	= $this->deposit_model->today_total_only_agent_deposit();
		$value		= ((isset($deposit['total']) && $deposit['total'] > 0)) ? val_decimal($deposit['total'],2) : val_decimal(0,2);
		$json 		= array('status' => EXIT_SUCCESS,'msg' => $this->lang->line('label_successful'),'result' => $value);
		json_output($json);
		free_array($json);	
		exit();	
	}
	public function today_promotion() {
		$promotion 	= null;#$this->promotion_model->today_total_promotion();
		$value		= ((isset($promotion['total']) && $promotion['total'] > 0)) ? val_decimal($promotion['total'],2) : val_decimal(0,2);
		$json 		= array('status' => EXIT_SUCCESS,'msg' => $this->lang->line('label_successful'),'result' => $value);
		json_output($json);
		free_array($json);	
		exit();	
	}
	public function today_bonus() {
		$bonus 	= $this->bonus_model->today_total_bonus();
		$value		= ((isset($bonus['total']) && $bonus['total'] > 0)) ? val_decimal($bonus['total'],2) : val_decimal(0,2);
		$json 		= array('status' => EXIT_SUCCESS,'msg' => $this->lang->line('label_successful'),'result' => $value);
		json_output($json);
		free_array($json);	
		exit();	
	}
	public function today_withdraw() {
		$withdrawal = $this->withdrawal_model->today_total_only_agent_withdrawal();
		$value		= ((isset($withdrawal['total']) && $withdrawal['total'] > 0)) ? val_decimal($withdrawal['total'],2) : val_decimal(0,2);
		$json 		= array('status' => EXIT_SUCCESS,'msg' => $this->lang->line('label_successful'),'result' => $value);
		json_output($json);
		free_array($json);	
		exit();	
	}
	public function today_profit() {
		$profit 	= $this->player_model->player_today_win_loss();
		$value		= ((isset($profit['total']) && $profit['total'] > 0)) ? val_decimal($profit['total'],2) : val_decimal(0,2);
		$json 		= array('status' => EXIT_SUCCESS,'msg' => $this->lang->line('label_successful'),'result' => $value);
		json_output($json);
		free_array($json);	
		exit();	
	}
	public function today_user() {
		$player 	= $this->player_model->today_total_player();
		$value		= ((isset($player['total']) && $player['total'] > 0)) ? $player['total'] : 0;
		$json 		= array('status' => EXIT_SUCCESS,'msg' => $this->lang->line('label_successful'),'result' => $value);
		json_output($json);
		free_array($json);
		free_array($player);	
		exit();	
	}
	public function active_user_deposit() {
		$start_date = strtotime(date('Y-m-d 00:00:00'));
		$end_date 	= strtotime(date('Y-m-d 23:59:59'));
		$root_id  	= $this->session->userdata('root_user_id');
		
		$player 	= $this->player_model->active_user_deposit($start_date,$end_date,$root_id);
		$value		= ((isset($player['total_player']) && $player['total_player'] > 0)) ? $player['total_player'] : 0;
		$json 		= array('status' => EXIT_SUCCESS,'msg' => $this->lang->line('label_successful'),'result' => $value);
		json_output($json);
		free_array($json);	
		exit();	
	}
	public function today_active_user() {
		$player 	= $this->player_model->player_active_five_minute();
		$value		= ((isset($player['total_player']) && $player['total_player'] > 0)) ? $player['total_player'] : 0;
		$json 		= array('status' => EXIT_SUCCESS,'msg' => $this->lang->line('label_successful'),'result' => $value);
		json_output($json);
		free_array($json);	
		exit();	
	}
	public function today_active_user_by_agent(){
		$main_player 	= $this->player_model->player_active_five_minute();
		$result = array(
			'main_agent_player_count' =>  ((isset($main_player['total_player'])) ? $main_player['total_player'] : 0),
			'sub_account_data' => array(), 
		);
		$dbprefix = $this->db->dbprefix;
		$query_string = "(SELECT user_id,username FROM {$dbprefix}users WHERE upline = '" . $this->session->userdata('root_username') . "')";
		$query = $this->db->query($query_string);
		if($query->num_rows() > 0)
		{
			//$all_agent_data = $query->result_array();
			foreach($query->result_array() as $result_row){
				$count = $this->player_model->player_active_five_minute_by_agent_id($result_row['user_id']);
				if(!empty($count['total_player'])){
					array_push($result['sub_account_data'], array('username'=>$result_row['username'],'player_count'=>$count['total_player']));
				}
			}
		}
		$json = array('status' => EXIT_SUCCESS,'msg' => $this->lang->line('label_successful'),'result' => $result);
		json_output($json);
		exit();
	}
	public function totalDepositOfflineInterval(){
		$arr = $this->session->userdata('alert_deposits_offline');
		$start = 0;
		$limit = 5;
		$total_deposit = 0;
		$total_alert_deposit  = 0;
		$data = array();
		$row = array();
		$current_time = time();
		$columns = array( 
			0 => 'a.deposit_id',
			1 => 'a.created_date',
			2 => 'a.amount',
			3 => 'b.username',
			4 => 'c.avatar_image',
			5 => 'a.amount_apply',
		);
		$select = implode(',', $columns);
		$dbprefix = $this->db->dbprefix;
		$where = '';
		$where .= ' AND a.deposit_type = ' . DEPOSIT_OFFLINE_BANKING;
		$where .= ' AND a.status = ' . STATUS_PENDING;
		$query_string = "(SELECT {$select} FROM {$dbprefix}deposits a, {$dbprefix}players b, {$dbprefix}avatar c WHERE (a.player_id = b.player_id) AND (b.avatar = c.avatar_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
		$query_string_2 = " ORDER by a.created_date DESC LIMIT {$start}, {$limit}";
		$query = $this->db->query($query_string.$query_string_2);
		if($query->num_rows() > 0)
		{
			foreach($query->result_array() as $result_row){
				$row = array();
				$row['deposit_id'] = $result_row['deposit_id'];
				$row['created_date'] = $result_row['created_date'];
				$row['username'] = $result_row['username'];
				$row['avatar_image'] = $result_row['avatar_image'];
				$row['amount'] = $result_row['amount'];
				$row['amount_apply'] = $result_row['amount_apply'];
				$row['time_gaps'] = time_different_gaps($current_time,$result_row['created_date']);
				$data['list'][] = $row;
				if($result_row['created_date'] > $arr){
					$total_alert_deposit += 1;
				}
			}
		}
		$query->free_result();	
		$query = $this->db->query($query_string);
		$total_deposit = $query->num_rows();
		$query->free_result();
		$miscellaneous = $this->miscellaneous_model->get_miscellaneous();
		if(empty($miscellaneous)){
			$data['soundCode'] = 402;
			$data['soundFile'] = "";
			$data['result'] = $total_deposit;
			$data['result_alert'] = $total_alert_deposit;
		}else{
			if($miscellaneous['deposit_sound'] == ""){
				$data['soundCode'] = 404;
				$data['soundFile'] = "";
				$data['result'] = $total_deposit;
				$data['result_alert'] = $total_alert_deposit;
			}else{
				if(permission_validation(PERMISSION_DEPOSIT_OFFLINE_ANNOUNCEMENT) == TRUE){
					$deposit_sound_path = SOUND_SOURCE_PATH.$miscellaneous['deposit_sound'];
					if(file_exists($deposit_sound_path)){
						if(($miscellaneous['is_deposit_sound'] == STATUS_ACTIVE) && empty($this->session->userdata('announcement_alert_'.ANNOUNCEMENT_DEPOSIT_OFFLINE))){
							$data['soundCode'] = 0;
							$data['soundFile'] = base_url().SOUND_SOURCE_PATH.$miscellaneous['deposit_sound'];
							$data['result'] = $total_deposit;
							$data['result_alert'] = $total_alert_deposit;
						}else{
							$data['soundCode'] = 1;
							$data['soundFile'] = '';
							$data['result'] = $total_deposit;
							$data['result_alert'] = $total_alert_deposit;
						}
					}else{
						$data['soundCode'] = 408;
						$data['soundFile'] = "";
						$data['result'] = $total_deposit;
						$data['result_alert'] = $total_alert_deposit;
					}
				}else{
					$data['soundCode'] = 404;
					$data['soundFile'] = "";
					$data['result'] = $total_deposit;
					$data['result_alert'] = $total_alert_deposit;
				}
			}
		}
		echo json_encode($data); 
		exit();
	}
	public function totalDepositOnlineInterval(){
		$arr = $this->session->userdata('alert_deposits_online');
		$start = 0;
		$limit = 5;
		$total_deposit = 0;
		$total_alert_deposit  = 0;
		$data = array();
		$row = array();
		$current_time = time();
		$columns = array( 
			0 => 'a.deposit_id',
			1 => 'a.created_date',
			2 => 'a.amount',
			3 => 'b.username',
			4 => 'c.avatar_image',
			5 => 'a.amount_apply',
		);
		$select = implode(',', $columns);
		$dbprefix = $this->db->dbprefix;
		$where = '';
		$where .= ' AND a.deposit_type = ' . DEPOSIT_ONLINE_BANKING;
		$where .= ' AND a.status = ' . STATUS_PENDING;
		$query_string = "(SELECT {$select} FROM {$dbprefix}deposits a, {$dbprefix}players b, {$dbprefix}avatar c WHERE (a.player_id = b.player_id) AND (b.avatar = c.avatar_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
		$query_string_2 = " ORDER by a.created_date DESC LIMIT {$start}, {$limit}";
		$query = $this->db->query($query_string.$query_string_2);
		if($query->num_rows() > 0)
		{
			foreach($query->result_array() as $result_row){
				$row = array();
				$row['deposit_id'] = $result_row['deposit_id'];
				$row['created_date'] = $result_row['created_date'];
				$row['username'] = $result_row['username'];
				$row['avatar_image'] = $result_row['avatar_image'];
				$row['amount'] = $result_row['amount'];
				$row['amount_apply'] = $result_row['amount_apply'];
				$row['time_gaps'] = time_different_gaps($current_time,$result_row['created_date']);
				$data['list'][] = $row;
				if($result_row['created_date'] > $arr){
					$total_alert_deposit += 1;
				}
			}
		}
		$query->free_result();	
		$query = $this->db->query($query_string);
		$total_deposit = $query->num_rows();
		$query->free_result();
		$miscellaneous = $this->miscellaneous_model->get_miscellaneous();
		if(empty($miscellaneous)){
			$data['soundCode'] = 402;
			$data['soundFile'] = "";
			$data['result'] = $total_deposit;
			$data['result_alert'] = $total_alert_deposit;
		}else{
			if($miscellaneous['online_deposit_sound'] == ""){
				$data['soundCode'] = 404;
				$data['soundFile'] = "";
				$data['result'] = $total_deposit;
				$data['result_alert'] = $total_alert_deposit;
			}else{
				if(permission_validation(PERMISSION_DEPOSIT_ONLINE_ANNOUNCEMENT) == TRUE){
					$deposit_sound_path = SOUND_SOURCE_PATH.$miscellaneous['online_deposit_sound'];
					if(file_exists($deposit_sound_path)){
						if(($miscellaneous['is_online_deposit_sound'] == STATUS_ACTIVE) && empty($this->session->userdata('announcement_alert_'.ANNOUNCEMENT_DEPOSIT_ONLINE))){
							$data['soundCode'] = 0;
							$data['soundFile'] = base_url().SOUND_SOURCE_PATH.$miscellaneous['online_deposit_sound'];
							$data['result'] = $total_deposit;
							$data['result_alert'] = $total_alert_deposit;
						}else{
							$data['soundCode'] = 1;
							$data['soundFile'] = '';
							$data['result'] = $total_deposit;
							$data['result_alert'] = $total_alert_deposit;
						}
					}else{
						$data['soundCode'] = 408;
						$data['soundFile'] = "";
						$data['result'] = $total_deposit;
						$data['result_alert'] = $total_alert_deposit;
					}
				}else{
					$data['soundCode'] = 404;
					$data['soundFile'] = "";
					$data['result'] = $total_deposit;
					$data['result_alert'] = $total_alert_deposit;
				}
			}
		}
		echo json_encode($data); 
		exit();
	}
	public function totalDepositCreditCardInterval(){
		$arr = $this->session->userdata('alert_deposits_credit');
		$start = 0;
		$limit = 5;
		$total_deposit = 0;
		$total_alert_deposit  = 0;
		$data = array();
		$row = array();
		$current_time = time();
		$columns = array( 
			0 => 'a.deposit_id',
			1 => 'a.created_date',
			2 => 'a.amount',
			3 => 'b.username',
			4 => 'c.avatar_image',
			5 => 'a.amount_apply',
		);
		$select = implode(',', $columns);
		$dbprefix = $this->db->dbprefix;
		$where = '';
		$where .= ' AND a.deposit_type = ' . DEPOSIT_CREDIT_CARD;
		$where .= ' AND a.status = ' . STATUS_PENDING;
		$query_string = "(SELECT {$select} FROM {$dbprefix}deposits a, {$dbprefix}players b, {$dbprefix}avatar c WHERE (a.player_id = b.player_id) AND (b.avatar = c.avatar_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
		$query_string_2 = " ORDER by a.created_date DESC LIMIT {$start}, {$limit}";
		$query = $this->db->query($query_string.$query_string_2);
		if($query->num_rows() > 0)
		{
			foreach($query->result_array() as $result_row){
				$row = array();
				$row['deposit_id'] = $result_row['deposit_id'];
				$row['created_date'] = $result_row['created_date'];
				$row['username'] = $result_row['username'];
				$row['avatar_image'] = $result_row['avatar_image'];
				$row['amount'] = $result_row['amount'];
				$row['amount_apply'] = $result_row['amount_apply'];
				$row['time_gaps'] = time_different_gaps($current_time,$result_row['created_date']);
				$data['list'][] = $row;
				if($result_row['created_date'] > $arr){
					$total_alert_deposit += 1;
				}
			}
		}
		$query->free_result();	
		$query = $this->db->query($query_string);
		$total_deposit = $query->num_rows();
		$query->free_result();
		$miscellaneous = $this->miscellaneous_model->get_miscellaneous();
		if(empty($miscellaneous)){
			$data['soundCode'] = 402;
			$data['soundFile'] = "";
			$data['result'] = $total_deposit;
			$data['result_alert'] = $total_alert_deposit;
		}else{
			if($miscellaneous['credit_card_deposit_sound'] == ""){
				$data['soundCode'] = 404;
				$data['soundFile'] = "";
				$data['result'] = $total_deposit;
				$data['result_alert'] = $total_alert_deposit;
			}else{
				if(permission_validation(PERMISSION_DEPOSIT_CREDIT_CARD_ANNOUNCEMENT) == TRUE){
					$deposit_sound_path = SOUND_SOURCE_PATH.$miscellaneous['credit_card_deposit_sound'];
					if(file_exists($deposit_sound_path)){
						if(($miscellaneous['is_credit_card_deposit_sound'] == STATUS_ACTIVE) && empty($this->session->userdata('announcement_alert_'.ANNOUNCEMENT_DEPOSIT_CREDIT_CARD))){
							$data['soundCode'] = 0;
							$data['soundFile'] = base_url().SOUND_SOURCE_PATH.$miscellaneous['credit_card_deposit_sound'];
							$data['result'] = $total_deposit;
							$data['result_alert'] = $total_alert_deposit;
						}else{
							$data['soundCode'] = 1;
							$data['soundFile'] = '';
							$data['result'] = $total_deposit;
							$data['result_alert'] = $total_alert_deposit;
						}
					}else{
						$data['soundCode'] = 408;
						$data['soundFile'] = "";
						$data['result'] = $total_deposit;
						$data['result_alert'] = $total_alert_deposit;
					}
				}else{
					$data['soundCode'] = 404;
					$data['soundFile'] = "";
					$data['result'] = $total_deposit;
					$data['result_alert'] = $total_alert_deposit;
				}
			}
		}
		echo json_encode($data); 
		exit();
	}
	public function totalDepositHypermartInterval(){
		$arr = $this->session->userdata('alert_deposits_hypermart');
		$start = 0;
		$limit = 5;
		$total_deposit = 0;
		$total_alert_deposit  = 0;
		$data = array();
		$row = array();
		$current_time = time();
		$columns = array( 
			0 => 'a.deposit_id',
			1 => 'a.created_date',
			2 => 'a.amount',
			3 => 'b.username',
			4 => 'c.avatar_image',
			5 => 'a.amount_apply',
		);
		$select = implode(',', $columns);
		$dbprefix = $this->db->dbprefix;
		$where = '';
		$where .= ' AND a.deposit_type = ' . DEPOSIT_HYPERMART;
		$where .= ' AND a.status = ' . STATUS_PENDING;
		$query_string = "(SELECT {$select} FROM {$dbprefix}deposits a, {$dbprefix}players b, {$dbprefix}avatar c WHERE (a.player_id = b.player_id) AND (b.avatar = c.avatar_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
		$query_string_2 = " ORDER by a.created_date DESC LIMIT {$start}, {$limit}";
		$query = $this->db->query($query_string.$query_string_2);
		if($query->num_rows() > 0)
		{
			foreach($query->result_array() as $result_row){
				$row = array();
				$row['deposit_id'] = $result_row['deposit_id'];
				$row['created_date'] = $result_row['created_date'];
				$row['username'] = $result_row['username'];
				$row['avatar_image'] = $result_row['avatar_image'];
				$row['amount'] = $result_row['amount'];
				$row['amount_apply'] = $result_row['amount_apply'];
				$row['time_gaps'] = time_different_gaps($current_time,$result_row['created_date']);
				$data['list'][] = $row;
				if($result_row['created_date'] > $arr){
					$total_alert_deposit += 1;
				}
			}
		}
		$query->free_result();	
		$query = $this->db->query($query_string);
		$total_deposit = $query->num_rows();
		$query->free_result();
		$miscellaneous = $this->miscellaneous_model->get_miscellaneous();
		if(empty($miscellaneous)){
			$data['soundCode'] = 402;
			$data['soundFile'] = "";
			$data['result'] = $total_deposit;
			$data['result_alert'] = $total_alert_deposit;
		}else{
			if($miscellaneous['hypermart_deposit_sound'] == ""){
				$data['soundCode'] = 404;
				$data['soundFile'] = "";
				$data['result'] = $total_deposit;
				$data['result_alert'] = $total_alert_deposit;
			}else{
				if(permission_validation(PERMISSION_DEPOSIT_HYPERMARKET_ANNOUNCEMENT) == TRUE){
					$deposit_sound_path = SOUND_SOURCE_PATH.$miscellaneous['hypermart_deposit_sound'];
					if(file_exists($deposit_sound_path)){
						if(($miscellaneous['is_hypermart_deposit_sound'] == STATUS_ACTIVE) && empty($this->session->userdata('announcement_alert_'.ANNOUNCEMENT_DEPOSIT_HYPERMART))){
							$data['soundCode'] = 0;
							$data['soundFile'] = base_url().SOUND_SOURCE_PATH.$miscellaneous['hypermart_deposit_sound'];
							$data['result'] = $total_deposit;
							$data['result_alert'] = $total_alert_deposit;
						}else{
							$data['soundCode'] = 1;
							$data['soundFile'] = '';
							$data['result'] = $total_deposit;
							$data['result_alert'] = $total_alert_deposit;
						}
					}else{
						$data['soundCode'] = 408;
						$data['soundFile'] = "";
						$data['result'] = $total_deposit;
						$data['result_alert'] = $total_alert_deposit;
					}
				}else{
					$data['soundCode'] = 404;
					$data['soundFile'] = "";
					$data['result'] = $total_deposit;
					$data['result_alert'] = $total_alert_deposit;
				}
			}
		}
		echo json_encode($data); 
		exit();
	}
	public function totalWithdrawInterval(){
		$arr = $this->session->userdata('alert_withdrawals');
		$start = 0;
		$limit = 5;
		$total_withdraw = 0;
		$total_alert_withdraw  = 0;
		$data = array();
		$row = array();
		$current_time = time();
		$columns = array( 
			0 => 'a.withdrawal_id',
			1 => 'a.created_date',
			2 => 'a.amount',
			3 => 'b.username',
			4 => 'c.avatar_image',
		);
		$select = implode(',', $columns);
		$dbprefix = $this->db->dbprefix;
		$where = '';
		$where .= ' AND a.status = ' . STATUS_PENDING;
		$query_string = "(SELECT {$select} FROM {$dbprefix}withdrawals a, {$dbprefix}players b, {$dbprefix}avatar c WHERE (a.player_id = b.player_id) AND (b.avatar = c.avatar_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
		$query_string_2 = " ORDER by a.created_date DESC LIMIT {$start}, {$limit}";
		$query = $this->db->query($query_string.$query_string_2);
		if($query->num_rows() > 0)
		{
			foreach($query->result_array() as $result_row){
				$row = array();
				$row['withdrawal_id'] = $result_row['withdrawal_id'];
				$row['created_date'] = $result_row['created_date'];
				$row['username'] = $result_row['username'];
				$row['avatar_image'] = $result_row['avatar_image'];
				$row['amount'] = $result_row['amount'];
				$row['time_gaps'] = time_different_gaps($current_time,$result_row['created_date']);
				$data['list'][] = $row;
				if($result_row['created_date'] > $arr){
					$total_alert_withdraw += 1;
				}
			}
		}
		$query->free_result();	
		$query = $this->db->query($query_string);
		$total_withdraw = $query->num_rows();
		$query->free_result();
		$miscellaneous = $this->miscellaneous_model->get_miscellaneous();
		if(empty($miscellaneous)){
			$data['soundCode'] = 402;
			$data['soundFile'] = "";
			$data['result'] = $total_withdraw;
			$data['result_alert'] = $total_alert_withdraw;
		}else{
			if($miscellaneous['withdrawal_sound'] == ""){
				$data['soundCode'] = 404;
				$data['soundFile'] = "";
				$data['result'] = $total_withdraw;
				$data['result_alert'] = $total_alert_withdraw;
			}else{
				if(permission_validation(PERMISSION_WITHDRAWAL_OFFLINE_ANNOUNCEMENT) == TRUE){
					$withdraw_sound_path = SOUND_SOURCE_PATH.$miscellaneous['withdrawal_sound'];
					if(file_exists($withdraw_sound_path)){
						if(($miscellaneous['is_withdrawal_sound'] == STATUS_ACTIVE) && empty($this->session->userdata('announcement_alert_'.ANNOUNCEMENT_WITHDRAWAL))){
							$data['soundCode'] = 0;
							$data['soundFile'] = base_url().SOUND_SOURCE_PATH.$miscellaneous['withdrawal_sound'];
							$data['result'] = $total_withdraw;
							$data['result_alert'] = $total_alert_withdraw;
						}else{
							$data['soundCode'] = 1;
							$data['soundFile'] = '';
							$data['result'] = $total_withdraw;
							$data['result_alert'] = $total_alert_withdraw;
						}
					}else{
						$data['soundCode'] = 408;
						$data['soundFile'] = "";
						$data['result'] = $total_withdraw;
						$data['result_alert'] = $total_alert_withdraw;
					}
				}else{
					$data['soundCode'] = 404;
					$data['soundFile'] = "";
					$data['result'] = $total_withdraw;
					$data['result_alert'] = $total_alert_withdraw;
				}
			}
		}
		echo json_encode($data); 
		exit();
	}
	public function totalPlayerOnlineInterval(){
		$data = array();
		$row = array();
		$current_time = time();
		$last_five_minutes = ($current_time - 3600);
		$this->db->select('a.username,a.last_online_date,b.avatar_image');
		$this->db->from('players a');
		$this->db->join('avatar b','a.avatar = b.avatar_id');
		$this->db->where('a.last_online_date >= ',$last_five_minutes);
		$this->db->where('a.last_online_date <= ',$current_time);
		$this->db->like('a.upline_ids',$this->session->userdata('root_user_id'));
		$this->db->order_by('a.last_online_date','DESC');
		$this->db->limit(5,0);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			foreach($query->result_array() as $result_row){
				$row = array();
				$row['username'] = $result_row['username'];
				$row['last_online_date'] = $result_row['last_online_date'];
				$row['avatar_image'] = $result_row['avatar_image'];
				$row['time_gaps'] = time_different_gaps($current_time,$result_row['last_online_date']);
				$data['list'][] = $row;
			} 
		}
		$query->free_result();
		$result = NULL;
		$dbprefix = $this->db->dbprefix;
		$where = "";
		$where .= ' AND a.last_online_date >= ' . $last_five_minutes;
		$where .= ' AND a.last_online_date <= ' . $current_time;
		$select = "COUNT(a.player_id) AS total_player";
		$pa_query_string = "SELECT {$select} FROM {$dbprefix}players a WHERE a.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
		$pa_query = $this->db->query($pa_query_string);
		if($pa_query->num_rows() > 0)
		{
			$result =  $pa_query->row_array();
		}
		$pa_query->free_result();
		if(!empty($result) && isset($result['total_player'])){
			$data['result'] = $result['total_player'];
		}else{
			$data['result'] = 0;
		}
		echo json_encode($data); 
		exit();
	}
	public function totalRiskInterval(){
		$start = 0;
		$limit = 5;
		$total_risk = 0;
		$data = array();
		$row = array();
		$current_time = time();
		$miscellaneous = $this->miscellaneous_model->get_miscellaneous();
		if(empty($miscellaneous)){
			$data['soundCode'] = 402;
			$data['soundFile'] = "";
			$data['result'] = $total_risk;
		}else{
			if(!empty($miscellaneous) && ($miscellaneous['risk_management'] = STATUS_ACTIVE)){
				if($miscellaneous['risk_period'] == RISK_YEARLY){
					//Yearly
					$start_date	= date('Y-m-d 00:00:00', strtotime('first day of january this year'));
					$end_date	= date('Y-m-d 23:59:59', strtotime('last day of december this year'));
				}else if($miscellaneous['risk_period'] == RISK_MONTHLY){
					//Monthly
					$start_date	= date('Y-m-d 00:00:00', strtotime('first day of this month'));
					$end_date	= date('Y-m-d 23:59:59', strtotime('last day of this month'));
				}else{
					//Daily
					$start_date	= date('Y-m-d 00:00:00', time());
					$end_date	= date('Y-m-d 23:59:59', time());
				}
				$start_time = strtotime($start_date); 
				$end_time = strtotime($end_date);
				$columns = array( 
					0 => 'a.player_risk_id',
					1 => 'a.updated_date',
					2 => 'a.percentage',
					3 => 'b.username',
					4 => 'c.avatar_image',
				);
				$select = implode(',', $columns);
				$dbprefix = $this->db->dbprefix;
				$where = '';
				$where .= ' AND a.report_date = ' . $start_time;
				$where .= ' AND a.end_date = ' . $end_time;
				//$where .= ' AND a.suspended = ' . STATUS_ACTIVE;
				$query_string = "(SELECT {$select} FROM {$dbprefix}player_risk_report a, {$dbprefix}players b, {$dbprefix}avatar c WHERE (a.player_id = b.player_id) AND (b.avatar = c.avatar_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
				$query_string_2 = " ORDER by a.updated_date DESC LIMIT {$start}, {$limit}";
				$query = $this->db->query($query_string.$query_string_2);
				if($query->num_rows() > 0)
				{
					foreach($query->result_array() as $result_row){
						$row = array();
						$row['player_risk_id'] = $result_row['player_risk_id'];
						$row['updated_date'] = $result_row['updated_date'];
						$row['username'] = $result_row['username'];
						$row['avatar_image'] = $result_row['avatar_image'];
						$row['percentage'] = $result_row['percentage'];
						$row['time_gaps'] = time_different_gaps($current_time,$result_row['updated_date']);
						$data['list'][] = $row;
					}
				}
				$query->free_result();	
				$query = $this->db->query($query_string);
				$total_risk = $query->num_rows();
				$query->free_result();
				if($miscellaneous['risk_sound'] == ""){
					$data['soundCode'] = 404;
					$data['soundFile'] = "";
					$data['result'] = $total_risk;
				}else{
					if(permission_validation(PERMISSION_PLAYER_RISK_REPORT) == TRUE){
						$risk_sound_path = SOUND_SOURCE_PATH.$miscellaneous['risk_sound'];
						if(file_exists($risk_sound_path)){
							if(($miscellaneous['is_risk_sound'] == STATUS_ACTIVE) && empty($this->session->userdata('announcement_alert_'.ANNOUNCEMENT_RISK))){
								$data['soundCode'] = 0;
								$data['soundFile'] = base_url().SOUND_SOURCE_PATH.$miscellaneous['risk_sound'];
								$data['result'] = $total_risk;
							}else{
								$data['soundCode'] = 1;
								$data['soundFile'] = '';
								$data['result'] = $total_risk;
							}
						}else{
							$data['soundCode'] = 408;
							$data['soundFile'] = "";
							$data['result'] = $total_risk;
						}
					}else{
						$data['soundCode'] = 404;
						$data['soundFile'] = "";
						$data['result'] = $total_risk;
					}
				}
			}else{
				$data['soundCode'] = 403;
				$data['soundFile'] = "";
				$data['result'] = $total_risk;
			}
		}
		echo json_encode($data); 
		exit();
	}
	public function totalRiskFrozenInterval(){
		$start = 0;
		$limit = 5;
		$total_risk = 0;
		$data = array();
		$row = array();
		$current_time = time();
		$miscellaneous = $this->miscellaneous_model->get_miscellaneous();
		if(empty($miscellaneous)){
			$data['soundCode'] = 402;
			$data['soundFile'] = "";
			$data['result'] = $total_risk;
		}else{
			if(!empty($miscellaneous) && ($miscellaneous['risk_management'] = STATUS_ACTIVE)){
				if($miscellaneous['risk_period'] == RISK_YEARLY){
					//Yearly
					$start_date	= date('Y-m-d 00:00:00', strtotime('first day of january this year'));
					$end_date	= date('Y-m-d 23:59:59', strtotime('last day of december this year'));
				}else if($miscellaneous['risk_period'] == RISK_MONTHLY){
					//Monthly
					$start_date	= date('Y-m-d 00:00:00', strtotime('first day of this month'));
					$end_date	= date('Y-m-d 23:59:59', strtotime('last day of this month'));
				}else{
					//Daily
					$start_date	= date('Y-m-d 00:00:00', time());
					$end_date	= date('Y-m-d 23:59:59', time());
				}
				$start_time = strtotime($start_date); 
				$end_time = strtotime($end_date);
				$columns = array( 
					0 => 'a.player_risk_id',
					1 => 'a.updated_date',
					2 => 'a.percentage',
					3 => 'b.username',
					4 => 'c.avatar_image',
				);
				$select = implode(',', $columns);
				$dbprefix = $this->db->dbprefix;
				$where = '';
				$where .= ' AND a.report_date = ' . $start_time;
				$where .= ' AND a.end_date = ' . $end_time;
				$where .= ' AND a.suspended = ' . STATUS_SUSPEND;
				$query_string = "(SELECT {$select} FROM {$dbprefix}player_risk_report a, {$dbprefix}players b, {$dbprefix}avatar c WHERE (a.player_id = b.player_id) AND (b.avatar = c.avatar_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
				$query_string_2 = " ORDER by a.updated_date DESC LIMIT {$start}, {$limit}";
				$query = $this->db->query($query_string.$query_string_2);
				if($query->num_rows() > 0)
				{
					foreach($query->result_array() as $result_row){
						$row = array();
						$row['player_risk_id'] = $result_row['player_risk_id'];
						$row['updated_date'] = $result_row['updated_date'];
						$row['username'] = $result_row['username'];
						$row['avatar_image'] = $result_row['avatar_image'];
						$row['percentage'] = $result_row['percentage'];
						$row['time_gaps'] = time_different_gaps($current_time,$result_row['updated_date']);
						$data['list'][] = $row;
					}
				}
				$query->free_result();	
				$query = $this->db->query($query_string);
				$total_risk = $query->num_rows();
				$query->free_result();
				if($miscellaneous['risk_frozen_sound'] == ""){
					$data['soundCode'] = 404;
					$data['soundFile'] = "";
					$data['result'] = $total_risk;
				}else{
					if(permission_validation(PERMISSION_PLAYER_RISK_REPORT) == TRUE){
						$risk_sound_path = SOUND_SOURCE_PATH.$miscellaneous['risk_frozen_sound'];
						if(file_exists($risk_sound_path)){
							if(($miscellaneous['is_risk_frozen_sound'] == STATUS_ACTIVE) && empty($this->session->userdata('announcement_alert_'.ANNOUNCEMENT_RISK_FROZEN))){
								$data['soundCode'] = 0;
								$data['soundFile'] = base_url().SOUND_SOURCE_PATH.$miscellaneous['risk_frozen_sound'];
								$data['result'] = $total_risk;
							}else{
								$data['soundCode'] = 1;
								$data['soundFile'] = '';
								$data['result'] = $total_risk;
							}
						}else{
							$data['soundCode'] = 408;
							$data['soundFile'] = "";
							$data['result'] = $total_risk;
						}
					}else{
						$data['soundCode'] = 404;
						$data['soundFile'] = "";
						$data['result'] = $total_risk;
					}
				}
			}else{
				$data['soundCode'] = 403;
				$data['soundFile'] = "";
				$data['result'] = $total_risk;
			}
		}
		echo json_encode($data); 
		exit();
	}
	public function totalBlacklistInterval(){
		$start = 0;
		$limit = 5;
		$total_deposit = 0;
		$data = array();
		$row = array();
		$current_time = time();
		$columns = array( 
			0 => 'a.blacklists_report_id',
			1 => 'a.report_date',
			2 => 'a.username',
			3 => 'c.avatar_image',
		);
		$select = implode(',', $columns);
		$dbprefix = $this->db->dbprefix;
		$where = '';
		$where .= ' AND a.status = ' . STATUS_PENDING;
		$query_string = "(SELECT {$select} FROM {$dbprefix}blacklists_report a, {$dbprefix}players b, {$dbprefix}avatar c WHERE (a.username = b.username) AND (b.avatar = c.avatar_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
		$query_string_2 = " ORDER by a.report_date DESC LIMIT {$start}, {$limit}";
		$query = $this->db->query($query_string.$query_string_2);
		if($query->num_rows() > 0)
		{
			foreach($query->result_array() as $result_row){
				$row = array();
				$row['blacklists_report_id'] = $result_row['blacklists_report_id'];
				$row['report_date'] = $result_row['report_date'];
				$row['username'] = $result_row['username'];
				$row['avatar_image'] = $result_row['avatar_image'];
				$row['time_gaps'] = time_different_gaps($current_time,$result_row['report_date']);
				$data['list'][] = $row;
			}
		}
		$query->free_result();	
		$query = $this->db->query($query_string);
		$total_blacklist = $query->num_rows();
		$query->free_result();
		$miscellaneous = $this->miscellaneous_model->get_miscellaneous();
		if(empty($miscellaneous)){
			$data['soundCode'] = 402;
			$data['soundFile'] = "";
			$data['result'] = $total_blacklist;
		}else{
			if($miscellaneous['blacklist_sound'] == ""){
				$data['soundCode'] = 404;
				$data['soundFile'] = "";
				$data['result'] = $total_blacklist;
			}else{
				if(permission_validation(PERMISSION_BLACKLIST_ANNOUNCEMENT) == TRUE){
					$blacklist_sound_path = SOUND_SOURCE_PATH.$miscellaneous['blacklist_sound'];
					if(file_exists($blacklist_sound_path)){
						if(($miscellaneous['is_blacklist_sound'] == STATUS_ACTIVE) && empty($this->session->userdata('announcement_alert_'.ANNOUNCEMENT_BLACKLIST))){
							$data['soundCode'] = 0;
							$data['soundFile'] = base_url().SOUND_SOURCE_PATH.$miscellaneous['blacklist_sound'];
							$data['result'] = $total_blacklist;
						}else{
							$data['soundCode'] = 1;
							$data['soundFile'] = '';
							$data['result'] = $total_blacklist;
						}
					}else{
						$data['soundCode'] = 408;
						$data['soundFile'] = "";
						$data['result'] = $total_blacklist;
					}
				}else{
					$data['soundCode'] = 404;
					$data['soundFile'] = "";
					$data['result'] = $total_blacklist;
				}
			}
		}
		echo json_encode($data); 
		exit();
	}
	public function totalPlayerBankImageInterval(){
		$start = 0;
		$limit = 5;
		$total_deposit = 0;
		$data = array();
		$row = array();
		$current_time = time();
		$columns = array( 
			0 => 'a.player_bank_image_id',
			1 => 'a.created_date',
			2 => 'b.username',
			3 => 'c.avatar_image',
		);
		$select = implode(',', $columns);
		$dbprefix = $this->db->dbprefix;
		$where = '';
		$where .= ' AND a.verify = ' . STATUS_PENDING;
		$query_string = "(SELECT {$select} FROM {$dbprefix}player_bank_image a, {$dbprefix}players b, {$dbprefix}avatar c WHERE (a.player_id = b.player_id) AND (b.avatar = c.avatar_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
		$query_string_2 = " ORDER by a.created_date DESC LIMIT {$start}, {$limit}";
		$query = $this->db->query($query_string.$query_string_2);
		if($query->num_rows() > 0)
		{
			foreach($query->result_array() as $result_row){
				$row = array();
				$row['player_bank_image_id'] = $result_row['player_bank_image_id'];
				$row['report_date'] = $result_row['created_date'];
				$row['username'] = $result_row['username'];
				$row['avatar_image'] = $result_row['avatar_image'];
				$row['time_gaps'] = time_different_gaps($current_time,$result_row['created_date']);
				$data['list'][] = $row;
			}
		}
		$query->free_result();	
		$query = $this->db->query($query_string);
		$total_player_bank_image = $query->num_rows();
		$query->free_result();
		$miscellaneous = $this->miscellaneous_model->get_miscellaneous();
		if(empty($miscellaneous)){
			$data['soundCode'] = 402;
			$data['soundFile'] = "";
			$data['result'] = $total_player_bank_image;
		}else{
			if($miscellaneous['player_bank_image_sound'] == ""){
				$data['soundCode'] = 404;
				$data['soundFile'] = "";
				$data['result'] = $total_player_bank_image;
			}else{
				if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE_ANNOUNCEMENT) == TRUE){
					$player_bank_image_sound_path = SOUND_SOURCE_PATH.$miscellaneous['player_bank_image_sound'];
					if(file_exists($player_bank_image_sound_path)){
						if(($miscellaneous['is_player_bank_image_sound'] == STATUS_ACTIVE) && empty($this->session->userdata('announcement_alert_'.ANNOUNCEMENT_PLAYER_BANK_IMAGE))){
							$data['soundCode'] = 0;
							$data['soundFile'] = base_url().SOUND_SOURCE_PATH.$miscellaneous['player_bank_image_sound'];
							$data['result'] = $total_player_bank_image;
						}else{
							$data['soundCode'] = 1;
							$data['soundFile'] = '';
							$data['result'] = $total_player_bank_image;
						}
					}else{
						$data['soundCode'] = 408;
						$data['soundFile'] = "";
						$data['result'] = $total_player_bank_image;
					}
				}else{
					$data['soundCode'] = 404;
					$data['soundFile'] = "";
					$data['result'] = $total_player_bank_image;
				}
			}
		}
		echo json_encode($data); 
		exit();
	}
	public function set_announcement($type = ANNOUNCEMENT_DEPOSIT){
		if(empty($this->session->userdata('announcement_alert_'.$type))){
			//sound
			$data = array(
				'announcement_status' => ANNOUNCEMENT_OFF,
			);
			$this->session->set_userdata('announcement_alert_'.$type, $data);
			$json = array('status'=> EXIT_SUCCESS,'code'=> ANNOUNCEMENT_OFF);
		}else{
			//mute
			$this->session->unset_userdata('announcement_alert_'.$type);
			$json = array('status'=> EXIT_SUCCESS,'code'=> ANNOUNCEMENT_ON);
		}
		echo json_encode($json); 
		exit();
	}
}