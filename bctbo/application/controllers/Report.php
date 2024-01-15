<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('game_model', 'player_model','miscellaneous_model','risk_model','level_model','withdrawal_model','tag_model','bank_model'));
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			$currentURL = current_url();
			if(strpos($currentURL,"ranking_calculate") === false){
				echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
			}
		}
	}
	/*************************TRANSACTION REPORT*******************************************/
	public function transaction()
	{
		if(permission_validation(PERMISSION_TRANSACTION_REPORT) == TRUE)
		{
			$this->save_current_url('report/transaction');
			$data = quick_search();
			$data_search = array();
			$data['page_title'] = $this->lang->line('title_transaction_report');
			$data['game_list'] = $this->game_model->get_game_list();
			$this->session->unset_userdata('search_report_transactions');
			if($_GET){
				$data_search['username'] = (isset($_GET['username'])?$_GET['username']:'');
			}
			$data['data_search'] = $data_search;
			$this->load->view('transaction_report_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function transaction_search()
	{
		if(permission_validation(PERMISSION_TRANSACTION_REPORT) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			//Set form rules
			$config = array(
							array(
									'field' => 'from_date',
									'label' => strtolower($this->lang->line('label_from_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							),
							array(
									'field' => 'to_date',
									'label' => strtolower($this->lang->line('label_to_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							)
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$from_date = strtotime(trim($this->input->post('from_date', TRUE)));
				$to_date = strtotime(trim($this->input->post('to_date', TRUE)));
				$days = cal_days_in_month(CAL_GREGORIAN, date('n', $from_date), date('Y', $from_date));
				$date_range = (($days+1) * 86400);
				$time_diff = ($to_date - $from_date);
				if($time_diff < 0 OR $time_diff > $date_range)
				{
					$json['msg'] = $this->lang->line('error_invalid_month_range');
				}
				else
				{
					$data = array( 
						'from_date' => trim($this->input->post('from_date', TRUE)),
						'to_date' => trim($this->input->post('to_date', TRUE)),
						'game_provider_code' => trim($this->input->post('game_provider_code', TRUE)),
						'username' => trim($this->input->post('username', TRUE)),
						'game_type_code' => trim($this->input->post('game_type_code', TRUE)),
						'bet_id' => trim($this->input->post('bet_id', TRUE)),
						'game_code' => trim($this->input->post('game_code', TRUE)),
						'game_time_type' => trim($this->input->post('game_time_type', TRUE)),
						'result_status' => trim($this->input->post('result_status', TRUE)),
					);
					$this->session->set_userdata('search_report_transactions', $data);
					$json['status'] = EXIT_SUCCESS;
				}
			}
			else 
			{
				$error = array(
							'from_date' => form_error('from_date'), 
							'to_date' => form_error('to_date')
						);
				if( ! empty($error['from_date']))
				{
					$json['msg'] = $error['from_date'];
				}
				else if( ! empty($error['to_date']))
				{
					$json['msg'] = $error['to_date'];
				}
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function testing_transaction_listing(){
	    $arr = $this->session->userdata('search_report_transactions');				
			$dbprefix = $this->db->dbprefix;
			$where = '';	
			$arr['from_date'] = "2023-03-22 14:00:00";
			$arr['to_date'] = "2023-03-22 14:59:59";
			$arr['game_time_type'] = TIME_TYPE_PAYOUT_TIME;
			$arr['game_provider_code'] = "RSG";
			$arr['game_type_code'] = "FH";
			$arr['username'] = "";
			$arr['result_status'] = 1;
			if( ! empty($arr['from_date']))
			{
				if( ! empty($arr['game_time_type'])){
					if($arr['game_time_type'] == TIME_TYPE_PAYOUT_TIME){
						$where .= ' WHERE a.payout_time >= ' . strtotime($arr['from_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_BET_TIME){
						$where .= ' WHERE a.bet_time >= ' . strtotime($arr['from_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_GAME_TIME){
					    $where .= ' WHERE a.game_time >= ' . strtotime($arr['from_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_SATTLE_TIME){
					    $where .= ' WHERE a.sattle_time >= ' . strtotime($arr['from_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_COMPARE_TIME){
					    $where .= ' WHERE a.compare_time >= ' . strtotime($arr['from_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_INSERT_UPDATE_TIME){
					    $where .= ' WHERE a.created_date >= ' . strtotime($arr['from_date']);
					}else{
					    $where .= ' WHERE a.report_time >= ' . strtotime($arr['from_date']);
					}
				}else{
					$where .= ' WHERE a.payout_time >= ' . strtotime($arr['from_date']);
				}
			}
			if( ! empty($arr['to_date']))
			{
				if( ! empty($arr['game_time_type'])){
					if($arr['game_time_type'] == TIME_TYPE_PAYOUT_TIME){
						$where .= ' AND a.payout_time <= ' . strtotime($arr['to_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_BET_TIME){
						$where .= ' AND a.bet_time <= ' . strtotime($arr['to_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_GAME_TIME){
					    $where .= ' AND a.game_time <= ' . strtotime($arr['to_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_SATTLE_TIME){
					    $where .= ' AND a.sattle_time <= ' . strtotime($arr['to_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_COMPARE_TIME){
					    $where .= ' AND a.compare_time <= ' . strtotime($arr['to_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_INSERT_UPDATE_TIME){
					    $where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);
					}else{
						$where .= ' AND a.report_time <= ' . strtotime($arr['to_date']);
					}
				}else{
					$where .= ' AND a.payout_time <= ' . strtotime($arr['to_date']);
				}	
			}
			if( ! empty($arr['game_provider_code']))
			{
				$where .= " AND a.game_provider_code = '" . $arr['game_provider_code'] . "'";
			}
			if( ! empty($arr['username']))
			{
				$where .= " AND b.username = '" . $arr['username'] . "'";
			}
			if( ! empty($arr['game_type_code']))
			{
				$where .= " AND a.game_type_code = '" . $arr['game_type_code'] . "'";
			}
			if( ! empty($arr['bet_id']))
			{
				$where .= " AND a.bet_id = '" . $arr['bet_id'] . "'";
			}
			if( ! empty($arr['game_code']))
			{
				$where .= " AND a.game_code = '" . $arr['game_code'] . "'";
			}
			if(isset($arr['result_status']) && $arr['result_status']!==""){
				$where .= " AND a.status = " . $arr['result_status'];
			}
			$sum_columns = array( 
			    0 => 'count(transaction_id)',
				1 => 'SUM(a.bet_amount) AS total_bet_amount',
				2 => 'SUM(a.win_loss) AS total_win_loss',
				3 => 'SUM(a.bet_amount_valid) AS total_rolling_amount',
				4 => 'SUM(a.jackpot_win) AS total_jackpot_win',
			);	
			$sum_select = implode(',', $sum_columns);
			$total_query_string = "SELECT {$sum_select} FROM {$dbprefix}transaction_report a $where";
			ad($total_query_string);
	}
	public function transaction_listing()
    {
		if(permission_validation(PERMISSION_TRANSACTION_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'a.transaction_id',
				1 => 'a.bet_time',
				2 => 'b.username',
				3 => 'a.game_provider_code',
				4 => 'a.game_type_code',
				5 => 'a.game_code',
				6 => 'a.bet_code',
				7 => 'a.game_result',
				8 => 'a.bet_amount',
				9 => 'a.bet_amount_valid',
				10 => 'a.win_loss',
				11 => 'a.jackpot_win',
				12 => 'a.status',
				13 => 'a.game_real_code',
				14 => 'a.bet_info',
				15 => 'a.bet_update_info',
				16 => 'a.bet_id'
			);		
			$col = 0;
			$dir = "";
			if( ! empty($order))
			{
				foreach($order as $o)
				{
					$col = $o['column'];
					$dir = $o['dir'];
				}
			}
			if($dir != "asc" && $dir != "desc")
			{
				$dir = "desc";
			}
			if( ! isset($columns[$col]))
			{
				$order = $columns[0];
			}
			else
			{
				$order = $columns[$col];
			}
			$arr = $this->session->userdata('search_report_transactions');				
			$where = '';	
			if( ! empty($arr['from_date']))
			{
				if( ! empty($arr['game_time_type'])){
					if($arr['game_time_type'] == TIME_TYPE_PAYOUT_TIME){
						$where .= ' AND a.payout_time >= ' . strtotime($arr['from_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_BET_TIME){
						$where .= ' AND a.bet_time >= ' . strtotime($arr['from_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_GAME_TIME){
					    $where .= ' AND a.game_time >= ' . strtotime($arr['from_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_SATTLE_TIME){
					    $where .= ' AND a.sattle_time >= ' . strtotime($arr['from_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_COMPARE_TIME){
					    $where .= ' AND a.compare_time >= ' . strtotime($arr['from_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_INSERT_UPDATE_TIME){
					    $where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);
					}else{
					    $where .= ' AND a.report_time >= ' . strtotime($arr['from_date']);
					}
				}else{
					$where .= ' AND a.payout_time >= ' . strtotime($arr['from_date']);
				}
			}
			if( ! empty($arr['to_date']))
			{
				if( ! empty($arr['game_time_type'])){
					if($arr['game_time_type'] == TIME_TYPE_PAYOUT_TIME){
						$where .= ' AND a.payout_time <= ' . strtotime($arr['to_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_BET_TIME){
						$where .= ' AND a.bet_time <= ' . strtotime($arr['to_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_GAME_TIME){
					    $where .= ' AND a.game_time <= ' . strtotime($arr['to_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_SATTLE_TIME){
					    $where .= ' AND a.sattle_time <= ' . strtotime($arr['to_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_COMPARE_TIME){
					    $where .= ' AND a.compare_time <= ' . strtotime($arr['to_date']);
					}else if($arr['game_time_type'] == TIME_TYPE_INSERT_UPDATE_TIME){
					    $where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);
					}else{
						$where .= ' AND a.report_time <= ' . strtotime($arr['to_date']);
					}
				}else{
					$where .= ' AND a.payout_time <= ' . strtotime($arr['to_date']);
				}	
			}
			if( ! empty($arr['game_time_type'])){
				if($arr['game_time_type'] == TIME_TYPE_PAYOUT_TIME){
					$where .= ' AND a.status != ' . STATUS_CANCEL;
				}
			}
			if( ! empty($arr['game_provider_code']))
			{
				$where .= " AND a.game_provider_code = '" . $arr['game_provider_code'] . "'";
			}
			if( ! empty($arr['username']))
			{
				$where .= " AND b.username = '" . $arr['username'] . "'";
			}
			if( ! empty($arr['game_type_code']))
			{
				$where .= " AND a.game_type_code = '" . $arr['game_type_code'] . "'";
			}
			if( ! empty($arr['bet_id']))
			{
				$where .= " AND a.bet_id = '" . $arr['bet_id'] . "'";
			}
			if( ! empty($arr['game_code']))
			{
				$where .= " AND a.game_code = '" . $arr['game_code'] . "'";
			}
			if(isset($arr['result_status']) && $arr['result_status']!==""){
				$where .= " AND a.status = " . $arr['result_status'];
			}
			$select = implode(',', $columns);
			$order = substr($order, 2);
			$dbprefix = $this->db->dbprefix;
			$posts = NULL;
			$query_string = "SELECT {$select} FROM {$dbprefix}transaction_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
			$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if($query->num_rows() > 0)
			{
				$posts = $query->result();  
			}
			$query->free_result();
			//Get total records
			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();
			$query->free_result();
			//Prepare data
			$data = array();
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					if(!empty($post->bet_update_info)){
						$result = $post->bet_update_info;
					}else{
						$result = $post->bet_info;
					}
					$row = array();
					$row[] = $post->transaction_id;
					$row[] = (($post->bet_time > 0) ? date('Y-m-d H:i:s', $post->bet_time) : '-');
					$row[] = $post->username;
					$row[] = $this->lang->line('game_' . strtolower($post->game_provider_code));
					$row[] = ($this->lang->line(get_game_type($post->game_type_code)) ? $this->lang->line(get_game_type($post->game_type_code)) : "-" );
					#$row[] = game_code_decision($post->game_provider_code,$post->game_type_code,$result);
					$row[] = game_code_decision($post->game_provider_code,$post->game_type_code,$post->game_code);
					#$row[] = bet_code_decision($post->game_provider_code,$post->game_type_code,$result);
					$row[] = $post->bet_id;
					$row[] = game_result_decision($post->game_provider_code,$post->game_type_code,$result);
					$row[] = '<span class="text-' . (($post->bet_amount >= 0) ? ($post->bet_amount == 0) ? 'dark' : 'dark' : 'danger') . '">' . number_format($post->bet_amount, 2, '.', ',') . '</span>';$post->bet_amount;
					$row[] = '<span class="text-' . (($post->bet_amount_valid >= 0) ? ($post->bet_amount_valid == 0) ? 'dark' : 'dark' : 'danger') . '">' . number_format($post->bet_amount_valid, 2, '.', ',') . '</span>';$post->bet_amount_valid;
					$row[] = '<span class="text-' . (($post->win_loss >= 0) ? ($post->win_loss == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($post->win_loss, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($post->jackpot_win >= 0) ? ($post->jackpot_win == 0) ? 'dark' : 'dark' : 'danger') . '">' . number_format($post->jackpot_win, 2, '.', ',') . '</span>';
					switch($post->status)
					{
						case STATUS_COMPLETE: $row[] = '<span class="badge bg-success">' . $this->lang->line('status_completed') . '</span>'; break;
						case STATUS_CANCEL: $row[] = '<span class="badge bg-danger">' . $this->lang->line('status_cancelled') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary">' . $this->lang->line('status_pending') . '</span>'; break;
					}
					$data[] = $row;
				}
			}
			//Output
			$json_data = array(
				"draw"            => intval($this->input->post('draw')), 
				"recordsFiltered" => intval($totalFiltered), 
				"data"            => $data,
				"csrfHash" 		  => $this->security->get_csrf_hash()				
			);
			echo json_encode($json_data); 
			exit();
		}	
    }
    public function transaction_total(){
    	if(permission_validation(PERMISSION_TRANSACTION_REPORT) == TRUE)
		{
			$arr = $this->session->userdata('search_report_transactions');
			$dbprefix = $this->db->dbprefix;
			//Declaration Total
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => '',
				'total_data' => '',
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			$json['total_data'] = array(
				'total_bet_amount' => 0, 
				'total_win_loss' => 0, 
				'total_rolling_amount' => 0,
				'total_jackpot_win' => 0,
			);
			if(!empty($arr)){
				$json['status'] = EXIT_SUCCESS;
				$data = array();
				//Get total
				$where = '';
				if( ! empty($arr['from_date']))
				{
					if( ! empty($arr['game_time_type'])){
						if($arr['game_time_type'] == TIME_TYPE_PAYOUT_TIME){
							$where .= ' AND a.payout_time >= ' . strtotime($arr['from_date']);
						}else if($arr['game_time_type'] == TIME_TYPE_BET_TIME){
							$where .= ' AND a.bet_time >= ' . strtotime($arr['from_date']);
						}else if($arr['game_time_type'] == TIME_TYPE_GAME_TIME){
						    $where .= ' AND a.game_time >= ' . strtotime($arr['from_date']);
						}else if($arr['game_time_type'] == TIME_TYPE_SATTLE_TIME){
						    $where .= ' AND a.sattle_time >= ' . strtotime($arr['from_date']);
						}else if($arr['game_time_type'] == TIME_TYPE_COMPARE_TIME){
						    $where .= ' AND a.compare_time >= ' . strtotime($arr['from_date']);
						}else if($arr['game_time_type'] == TIME_TYPE_INSERT_UPDATE_TIME){
						    $where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);
						}else{
						    $where .= ' AND a.report_time >= ' . strtotime($arr['from_date']);
						}
					}else{
						$where .= ' AND a.payout_time >= ' . strtotime($arr['from_date']);
					}
				}
				if( ! empty($arr['to_date']))
				{
					if( ! empty($arr['game_time_type'])){
						if($arr['game_time_type'] == TIME_TYPE_PAYOUT_TIME){
							$where .= ' AND a.payout_time <= ' . strtotime($arr['to_date']);
						}else if($arr['game_time_type'] == TIME_TYPE_BET_TIME){
							$where .= ' AND a.bet_time <= ' . strtotime($arr['to_date']);
						}else if($arr['game_time_type'] == TIME_TYPE_GAME_TIME){
						    $where .= ' AND a.game_time <= ' . strtotime($arr['to_date']);
						}else if($arr['game_time_type'] == TIME_TYPE_SATTLE_TIME){
						    $where .= ' AND a.sattle_time <= ' . strtotime($arr['to_date']);
						}else if($arr['game_time_type'] == TIME_TYPE_COMPARE_TIME){
						    $where .= ' AND a.compare_time <= ' . strtotime($arr['to_date']);
						}else if($arr['game_time_type'] == TIME_TYPE_INSERT_UPDATE_TIME){
						    $where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);
						}else{
							$where .= ' AND a.report_time <= ' . strtotime($arr['to_date']);
						}
					}else{
						$where .= ' AND a.payout_time <= ' . strtotime($arr['to_date']);
					}	
				}
				if( ! empty($arr['game_time_type'])){
					if($arr['game_time_type'] == TIME_TYPE_PAYOUT_TIME){
						$where .= ' AND a.status != ' . STATUS_CANCEL;
					}
				}
				if( ! empty($arr['game_provider_code']))
				{
					$where .= " AND a.game_provider_code = '" . $arr['game_provider_code'] . "'";
				}
				if( ! empty($arr['username']))
				{
					$where .= " AND b.username = '" . $arr['username'] . "'";
				}
				if( ! empty($arr['game_type_code']))
				{
					$where .= " AND a.game_type_code = '" . $arr['game_type_code'] . "'";
				}
				if( ! empty($arr['bet_id']))
				{
					$where .= " AND a.bet_id = '" . $arr['bet_id'] . "'";
				}
				if( ! empty($arr['game_code']))
				{
					$where .= " AND a.game_code = '" . $arr['game_code'] . "'";
				}
				if(isset($arr['result_status']) && $arr['result_status']!==""){
					$where .= " AND a.status = " . $arr['result_status'];
				}
				$sum_columns = array( 
					0 => 'SUM(a.bet_amount) AS total_bet_amount',
					1 => 'SUM(a.win_loss) AS total_win_loss',
					2 => 'SUM(a.bet_amount_valid) AS total_rolling_amount',
					3 => 'SUM(a.jackpot_win) AS total_jackpot_win',
				);	
				$sum_select = implode(',', $sum_columns);
				$total_query_string = "SELECT {$sum_select} FROM {$dbprefix}transaction_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
				$total_query = $this->db->query($total_query_string);
				if($total_query->num_rows() > 0)
				{
					foreach($total_query->result() as $row)
					{
						$json['total_data'] = array(
							'total_bet_amount' => (($row->total_bet_amount > 0) ? $row->total_bet_amount : 0),
							'total_win_loss' => $row->total_win_loss,
							'total_rolling_amount' => (($row->total_rolling_amount > 0) ? $row->total_rolling_amount : 0),
							'total_jackpot_win' => (($row->total_jackpot_win > 0) ? $row->total_jackpot_win : 0),
						);
					}
				}
				$total_query->free_result();
			}
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();
		}
    }
    /*************************POINT REPORT*******************************************/
	public function point()
	{
		if(permission_validation(PERMISSION_POINT_TRANSACTION_REPORT) == TRUE)
		{
			$this->save_current_url('report/point');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_point_transaction_report');
			$this->session->unset_userdata('search_report_points');
			$this->load->view('point_transaction_report_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function point_search()
	{
		if(permission_validation(PERMISSION_POINT_TRANSACTION_REPORT) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			//Set form rules
			$config = array(
							array(
									'field' => 'from_date',
									'label' => strtolower($this->lang->line('label_from_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							),
							array(
									'field' => 'to_date',
									'label' => strtolower($this->lang->line('label_to_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							),
							array(
									'field' => 'username',
									'label' => strtolower($this->lang->line('label_username')),
									'rules' => 'trim|required',
									'errors' => array(
														'required' => $this->lang->line('error_enter_username')
												)
							)
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$from_date = strtotime(trim($this->input->post('from_date', TRUE)));
				$to_date = strtotime(trim($this->input->post('to_date', TRUE)));
				$days = cal_days_in_month(CAL_GREGORIAN, date('n', $from_date), date('Y', $from_date));
				$date_range = (($days+1) * 86400);
				$time_diff = ($to_date - $from_date);
				if($time_diff < 0 OR $time_diff > $date_range)
				{
					$json['msg'] = $this->lang->line('error_invalid_month_range');
				}
				else
				{
					$data = array( 
									'from_date' => trim($this->input->post('from_date', TRUE)),
									'to_date' => trim($this->input->post('to_date', TRUE)),
									'username' => trim($this->input->post('username', TRUE))
								);
					$this->session->set_userdata('search_report_points', $data);
					$json['status'] = EXIT_SUCCESS;
				}
			}
			else 
			{
				$error = array(
							'from_date' => form_error('from_date'), 
							'to_date' => form_error('to_date'),
							'username' => form_error('username')
						);
				if( ! empty($error['from_date']))
				{
					$json['msg'] = $error['from_date'];
				}
				else if( ! empty($error['to_date']))
				{
					$json['msg'] = $error['to_date'];
				}
				else if( ! empty($error['username']))
				{
					$json['msg'] = $error['username'];
				}
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function point_listing()
    {
		if(permission_validation(PERMISSION_POINT_TRANSACTION_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'a.point_transfer_id',
				1 => 'a.report_date',
				2 => 'a.from_username',
				3 => 'a.to_username',
				4 => 'a.withdrawal_amount',
				5 => 'a.deposit_amount',
				6 => 'a.from_balance_before',
				7 => 'a.from_balance_after',
				8 => 'a.remark',
				9 => 'a.executed_by',
				10 => 'a.to_balance_before',
				11 => 'a.to_balance_after',
			);
			$col = 0;
			$dir = "";
			if( ! empty($order))
			{
				foreach($order as $o)
				{
					$col = $o['column'];
					$dir = $o['dir'];
				}
			}
			if($dir != "asc" && $dir != "desc")
			{
				$dir = "desc";
			}
			if( ! isset($columns[$col]))
			{
				$order = $columns[0];
			}
			else
			{
				$order = $columns[$col];
			}
			$arr = $this->session->userdata('search_report_points');				
			$where = '';	
			$where_2 = '';
			$where_3 = '';			
			if(isset($arr['from_date']))
			{
				if( ! empty($arr['from_date']))
				{
					$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
				}
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
				}
				if( ! empty($arr['username']))
				{
					$where_2 .= " AND a.from_username = '" . $arr['username'] . "'";	
					$where_3 .= " AND a.to_username = '" . $arr['username'] . "'";	
				}
			}
			$select = implode(',', $columns);
			$order = substr($order, 2);
			$dbprefix = $this->db->dbprefix;
			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}point_transfer_report a, {$dbprefix}users b WHERE (a.from_username = b.username) AND (b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' OR b.user_id = " . $this->session->userdata('root_user_id') . ") $where $where_2)";
			$query_string .= " UNION ALL ";
			$query_string .= "(SELECT {$select} FROM {$dbprefix}point_transfer_report a, {$dbprefix}users b WHERE (a.to_username = b.username) AND (b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' OR b.user_id = " . $this->session->userdata('root_user_id') . ") $where $where_3)";
			$query_string .= " UNION ALL ";
			$query_string .= "(SELECT {$select} FROM {$dbprefix}point_transfer_report a, {$dbprefix}players b WHERE (a.from_username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where $where_2)";
			$query_string .= " UNION ALL ";
			$query_string .= "(SELECT {$select} FROM {$dbprefix}point_transfer_report a, {$dbprefix}players b WHERE (a.to_username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where $where_3)";
			$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if($query->num_rows() > 0)
			{
				$posts = $query->result();  
			}
			$query->free_result();
			//Get total records
			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();
			$query->free_result();
			//Get total sum up
			$total_data = array(
							'total_points_withdrawn' => 0, 
							'total_points_deposited' => 0
						);
			$query_string = "SELECT SUM(total_points_withdrawn) AS total_points_withdrawn, SUM(total_points_deposited) AS total_points_deposited FROM (";
			$query_string .= "(SELECT SUM(a.withdrawal_amount) AS total_points_withdrawn, 0 AS total_points_deposited FROM {$dbprefix}point_transfer_report a, {$dbprefix}users b WHERE (a.from_username = b.username) AND (b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' OR b.user_id = " . $this->session->userdata('root_user_id') . ") $where $where_2)";
			$query_string .= " UNION ALL ";
			$query_string .= "(SELECT 0 AS total_points_withdrawn, SUM(a.deposit_amount) AS total_points_deposited FROM {$dbprefix}point_transfer_report a, {$dbprefix}users b WHERE (a.to_username = b.username) AND (b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' OR b.user_id = " . $this->session->userdata('root_user_id') . ") $where $where_3)";
			$query_string .= " UNION ALL ";
			$query_string .= "(SELECT SUM(a.withdrawal_amount) AS total_points_withdrawn, 0 AS total_points_deposited FROM {$dbprefix}point_transfer_report a, {$dbprefix}players b WHERE (a.from_username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where $where_2)";
			$query_string .= " UNION ALL ";
			$query_string .= "(SELECT 0 AS total_points_withdrawn, SUM(a.deposit_amount) AS total_points_deposited FROM {$dbprefix}point_transfer_report a, {$dbprefix}players b WHERE (a.to_username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where $where_3)";
			$query_string .= ") tbl";
			$query = $this->db->query($query_string);
			if($query->num_rows() > 0)
			{
				foreach($query->result() as $row)
				{
					$total_data = array(
									'total_points_withdrawn' => (($row->total_points_withdrawn > 0) ? $row->total_points_withdrawn : 0), 
									'total_points_deposited' => (($row->total_points_deposited > 0) ? $row->total_points_deposited : 0)
								);
				}
			}
			$query->free_result();
			//Prepare data
			$data = array();
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					$row = array();
					$row[] = $post->point_transfer_id;
					$row[] = (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-');
					$row[] = $post->from_username;
					$row[] = $post->to_username;
					$row[] = (($post->from_username == $arr['username']) ? $post->withdrawal_amount : '0.00');
					$row[] = (($post->from_username == $arr['username']) ? '0.00' : $post->deposit_amount);
					$row[] = (($post->from_username == $arr['username']) ? $post->from_balance_before : $post->to_balance_before);
					$row[] = (($post->from_username == $arr['username']) ? $post->from_balance_after : $post->to_balance_after);
					$row[] = ( ! empty($post->remark) ? $post->remark : '-');
					$row[] = $post->executed_by;
					$data[] = $row;
				}
			}
			//Output
			$json_data = array(
							"draw"            => intval($this->input->post('draw')), 
							"recordsFiltered" => intval($totalFiltered), 
							"data"            => $data,
							"total_data"      => $total_data,
							"csrfHash" 		  => $this->security->get_csrf_hash()					
						);
			echo json_encode($json_data); 
			exit();
		}	
    }
	 /*************************CASH REPORT*******************************************/
	public function cash()
	{
		if(permission_validation(PERMISSION_CASH_TRANSACTION_REPORT) == TRUE)
		{
			$this->save_current_url('report/cash');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_cash_transaction_report');
			$this->session->unset_userdata('search_report_cash');
			$this->load->view('cash_transaction_report_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function cash_search()
	{
		if(permission_validation(PERMISSION_CASH_TRANSACTION_REPORT) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			//Set form rules
			$config = array(
							array(
									'field' => 'from_date',
									'label' => strtolower($this->lang->line('label_from_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							),
							array(
									'field' => 'to_date',
									'label' => strtolower($this->lang->line('label_to_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							)
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$from_date = strtotime(trim($this->input->post('from_date', TRUE)));
				$to_date = strtotime(trim($this->input->post('to_date', TRUE)));
				$days = cal_days_in_month(CAL_GREGORIAN, date('n', $from_date), date('Y', $from_date));
				$date_range = (($days+1) * 86400)*3;
				$time_diff = ($to_date - $from_date);
				if($time_diff < 0 OR $time_diff > $date_range)
				{
					$json['msg'] = $this->lang->line('error_invalid_month_range');
				}
				else
				{
					$data = array( 
						'from_date' => trim($this->input->post('from_date', TRUE)),
						'to_date' => trim($this->input->post('to_date', TRUE)),
						'transfer_type' => $this->input->post('transfer_type[]', TRUE),
						'username' => trim($this->input->post('username', TRUE)),
						'agent' => trim($this->input->post('agent', TRUE)),
					);
					$this->session->set_userdata('search_report_cash', $data);
					$json['status'] = EXIT_SUCCESS;
				}
			}
			else 
			{
				$error = array(
							'from_date' => form_error('from_date'), 
							'to_date' => form_error('to_date')
						);
				if( ! empty($error['from_date']))
				{
					$json['msg'] = $error['from_date'];
				}
				else if( ! empty($error['to_date']))
				{
					$json['msg'] = $error['to_date'];
				}
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function cash_listing()
    {
		if(permission_validation(PERMISSION_CASH_TRANSACTION_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'a.cash_transfer_id',
				1 => 'a.report_date',
				2 => 'a.transfer_type',
				3 => 'a.username',
				4 => 'a.balance_before',
				5 => 'a.deposit_amount',
				6 => 'a.withdrawal_amount',
				7 => 'a.balance_after',
				8 => 'a.remark',
				9 => 'a.executed_by',
			);
			$sum_columns = array( 
				0 => 'SUM(a.withdrawal_amount) AS total_points_withdrawn',
				1 => 'SUM(a.deposit_amount) AS total_points_deposited',
			);					
			$col = 0;
			$dir = "";
			if( ! empty($order))
			{
				foreach($order as $o)
				{
					$col = $o['column'];
					$dir = $o['dir'];
				}
			}
			if($dir != "asc" && $dir != "desc")
			{
				$dir = "desc";
			}
			if( ! isset($columns[$col]))
			{
				$order = $columns[0];
			}
			else
			{
				$order = $columns[$col];
			}
			$arr = $this->session->userdata('search_report_cash');				
			$where = '';	
			if( ! empty($arr['agent']))
			{
				$where = "AND a.player_id = 'ABC'";
				$agent = $this->user_model->get_user_data_by_username($arr['agent']);
				if(!empty($agent)){
					$response_upline = $this->user_model->get_downline_data($agent['username']);
					if(!empty($response_upline)){
						$where = "AND b.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";
					}
				}
			}else{
				$where = "AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";
			}
			if(isset($arr['from_date']))
			{
				if( ! empty($arr['from_date']))
				{
					$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
				}
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
				}
				if( ! empty($arr['transfer_type']))
				{
					$transfer_type = '"'.implode('","', $arr['transfer_type']).'"';
					$where .= " AND a.transfer_type IN(" . $transfer_type . ")";
				}else{
					$where .= " AND a.transfer_type = 'ABC'";
				}
				if( ! empty($arr['username']))
				{
					$where .= " AND a.username LIKE '" . $arr['username'] . "%' ESCAPE '!'";	
				}
			}
			$select = implode(',', $columns);
			$order = substr($order, 2);
			$dbprefix = $this->db->dbprefix;
			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) $where)";
			$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if($query->num_rows() > 0)
			{
				$posts = $query->result();  
			}
			$query->free_result();
			//Get total records
			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();
			$query->free_result();
			//Get total sum up
			$sum_select = implode(',', $sum_columns);
			$total_data = array(
							'total_points_withdrawn' => 0, 
							'total_points_deposited' => 0
						);
			$query_string = "(SELECT {$sum_select} FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) $where)";
			$query = $this->db->query($query_string);
			if($query->num_rows() > 0)
			{
				foreach($query->result() as $row)
				{
					$total_data = array(
									'total_points_withdrawn' => (($row->total_points_withdrawn > 0) ? $row->total_points_withdrawn : 0), 
									'total_points_deposited' => (($row->total_points_deposited > 0) ? $row->total_points_deposited : 0)
								);
				}
			}
			$query->free_result();
			//Prepare data
			$data = array();
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					if($post->transfer_type == TRANSFER_TRANSACTION_IN || $post->transfer_type == TRANSFER_TRANSACTION_OUT){
						$remark = $post->remark;
						if(!empty($post->remark)){
							$remark_array = json_decode($remark = $post->remark,true);
							if(!empty($remark_array)){
								$date = (isset($remark_array['created_date']) ? $remark_array['created_date'] : 0);
								$from = (isset($remark_array['from']) ? (($remark_array['from'] == 'MAIN') ? $this->lang->line('label_main_wallet') : $this->lang->line('game_' . strtolower($remark_array['from']))) : "-");
								$to = (isset($remark_array['to']) ? (($remark_array['to'] == 'MAIN') ? $this->lang->line('label_main_wallet') : $this->lang->line('game_' . strtolower($remark_array['to']))) : "-");
								$response = (isset($remark_array['errorCode']) ? ($remark_array['errorCode'] == "0") ? $this->lang->line('error_success') : $this->lang->line('error_failed') : "-");
								$remark = $this->lang->line('label_transfers')."(".$this->lang->line('label_from').")"." ".$from." ".$this->lang->line('label_to')." ".$to."<br>"." ".$this->lang->line('label_remark')." : ".$response;
							}
						}
					}else if($post->transfer_type == TRANSFER_PROMOTION){
						$remark = $post->remark;
						if(!empty($post->remark)){
							$remark_array = json_decode($remark = $post->remark,true);
							if(!empty($remark_array)){
								$remark = "";
								if(isset($remark_array['promotion_name']) && !empty($remark_array['promotion_name'])){
									if(!empty($remark)){
										$remark .= "<br/>";
									}
									$remark .= $this->lang->line('label_promotion_name')." : ".$remark_array['promotion_name'];
								}
								if(isset($remark_array['remark']) && !empty($remark_array['remark'])){
									if(!empty($remark)){
										$remark .= "<br/>";
									}
									$remark .= $this->lang->line('label_remark')." : ".$remark_array['remark'];
								}
							}
						}
					}else if($post->transfer_type == TRANSFER_PG_DEPOSIT || $post->transfer_type == TRANSFER_CREDIT_CARD_DEPOSIT || $post->transfer_type == TRANSFER_HYPERMART_DEPOSIT){
						$remark = $post->remark;
						if(!empty($post->remark)){
							$remark_array = json_decode($remark = $post->remark,true);
							if(!empty($remark_array)){
								$remark = "";
								if(isset($remark_array['payment_info'])){
									$remark .= $this->lang->line('label_payment_info')." : ".$remark_array['payment_info'];
								}
								if(isset($remark_array['remark']) && !empty($remark_array['remark'])){
									if(!empty($remark)){
										$remark .= "<br/>";
									}
									$remark .= $this->lang->line('label_remark')." : ".$remark_array['remark'];
								}
							}
						}
					}else if($post->transfer_type == TRANSFER_WITHDRAWAL){
						$remark = $post->remark;
						if(!empty($post->remark)){
							$remark_array = json_decode($remark = $post->remark,true);
							if(!empty($remark_array)){
								$remark = "";
								if(isset($remark_array['bank_account_info'])){
									$remark .= $this->lang->line('label_bank_account_no')." : ".$remark_array['bank_account_info'];
								}
							}
						}
					}else{
						$remark = $post->remark;
					}
					$row = array();
					$row[] = $post->cash_transfer_id;
					$row[] = (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-');
					$row[] = $this->lang->line(get_transfer_type($post->transfer_type));
					$row[] = $post->username;
					$row[] = $post->balance_before;
					$row[] = $post->deposit_amount;
					$row[] = $post->withdrawal_amount;
					$row[] = $post->balance_after;
					$row[] = ( ! empty($remark) ? $remark : '-');
					$row[] = $post->executed_by;
					$data[] = $row;
				}
			}
			//Output
			$json_data = array(
							"draw"            => intval($this->input->post('draw')), 
							"recordsFiltered" => intval($totalFiltered), 
							"data"            => $data,
							"total_data"      => $total_data,
							"csrfHash" 		  => $this->security->get_csrf_hash()					
						);
			echo json_encode($json_data); 
			exit();
		}	
    }
    /*************************WALLET REPORT*******************************************/
	public function wallet()
	{
		if(permission_validation(PERMISSION_WALLET_TRANSACTION_REPORT) == TRUE)
		{
			$this->save_current_url('report/wallet');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_wallet_transaction_report');
			$this->session->unset_userdata('search_report_wallets');
			$this->load->view('wallet_transaction_report_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function wallet_search()
	{
		if(permission_validation(PERMISSION_WALLET_TRANSACTION_REPORT) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			//Set form rules
			$config = array(
							array(
									'field' => 'from_date',
									'label' => strtolower($this->lang->line('label_from_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							),
							array(
									'field' => 'to_date',
									'label' => strtolower($this->lang->line('label_to_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							)
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$from_date = strtotime(trim($this->input->post('from_date', TRUE)));
				$to_date = strtotime(trim($this->input->post('to_date', TRUE)));
				$days = cal_days_in_month(CAL_GREGORIAN, date('n', $from_date), date('Y', $from_date));
				$date_range = (($days+1) * 86400);
				$time_diff = ($to_date - $from_date);
				if($time_diff < 0 OR $time_diff > $date_range)
				{
					$json['msg'] = $this->lang->line('error_invalid_month_range');
				}
				else
				{
					$data = array( 
						'from_date' => trim($this->input->post('from_date', TRUE)),
						'to_date' => trim($this->input->post('to_date', TRUE)),
						'username' => trim($this->input->post('username', TRUE)),
						'provider' =>  trim($this->input->post('username', TRUE)),
						'order_id' => trim($this->input->post('order_id', TRUE)),
						'order_id_alias' => trim($this->input->post('order_id_alias', TRUE)),
					);
					$this->session->set_userdata('search_report_wallets', $data);
					$json['status'] = EXIT_SUCCESS;
				}
			}
			else 
			{
				$error = array(
							'from_date' => form_error('from_date'), 
							'to_date' => form_error('to_date')
						);
				if( ! empty($error['from_date']))
				{
					$json['msg'] = $error['from_date'];
				}
				else if( ! empty($error['to_date']))
				{
					$json['msg'] = $error['to_date'];
				}
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function wallet_listing()
    {
		if(permission_validation(PERMISSION_WALLET_TRANSACTION_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'a.game_transfer_id',
				1 => 'a.report_date',
				2 => 'b.username',
				3 => 'a.order_id',
				4 => 'a.order_id_alias',
				5 => 'a.from_wallet',
				6 => 'a.to_balance_before',
				7 => 'a.deposit_amount',
				8 => 'a.withdrawal_amount',
				9 => 'a.to_balance_after',
				10 => 'a.to_wallet',
				11 => 'a.from_balance_before',
				12 => 'a.from_balance_after',
			);
			$sum_columns = array( 
				0 => 'SUM(a.withdrawal_amount) AS total_points_withdrawn',
				1 => 'SUM(a.deposit_amount) AS total_points_deposited',
			);		
			$col = 0;
			$dir = "";
			if( ! empty($order))
			{
				foreach($order as $o)
				{
					$col = $o['column'];
					$dir = $o['dir'];
				}
			}
			if($dir != "asc" && $dir != "desc")
			{
				$dir = "desc";
			}
			if( ! isset($columns[$col]))
			{
				$order = $columns[0];
			}
			else
			{
				$order = $columns[$col];
			}
			$arr = $this->session->userdata('search_report_wallets');				
			$where = '';	
			if(isset($arr['from_date']))
			{
				if( ! empty($arr['from_date']))
				{
					$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
				}
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
				}
				if( ! empty($arr['username']))
				{
					$where .= " AND b.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	
				}
				if( ! empty($arr['order_id']))
				{
					$where .= " AND a.order_id LIKE '%" . $arr['order_id'] . "%' ESCAPE '!'";	
				}
				if( ! empty($arr['order_id_alias']))
				{
					$where .= " AND a.order_id_alias LIKE '%" . $arr['order_id_alias'] . "%' ESCAPE '!'";	
				}
			}
			$select = implode(',', $columns);
			$order = substr($order, 2);
			$dbprefix = $this->db->dbprefix;
			$posts = NULL;
			$query_string = "SELECT {$select} FROM {$dbprefix}game_transfer_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
			$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if($query->num_rows() > 0)
			{
				$posts = $query->result();  
			}
			$query->free_result();
			//Get total records
			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();
			$query->free_result();
			//Get total sum up
			$sum_select = implode(',', $sum_columns);
			$total_data = array(
							'total_points_withdrawn' => 0, 
							'total_points_deposited' => 0
						);
			$query_string = "SELECT {$sum_select} FROM {$dbprefix}game_transfer_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
			$query = $this->db->query($query_string);
			if($query->num_rows() > 0)
			{
				foreach($query->result() as $row)
				{
					$total_data = array(
						'total_points_withdrawn' => (($row->total_points_withdrawn > 0) ? $row->total_points_withdrawn : 0), 
						'total_points_deposited' => (($row->total_points_deposited > 0) ? $row->total_points_deposited : 0)
					);
				}
			}
			$query->free_result();
			//Prepare data
			$data = array();
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					if($post->from_wallet == 'MAIN'){
						$from = $post->from_balance_before;
						$to = $post->from_balance_after;
					}else{
						$from = $post->from_balance_before;
						$to = $post->from_balance_after;
						/*
						$from = $post->to_balance_before;
						$to = $post->to_balance_after;
						*/
					}
					$row = array();
					$row[] = $post->game_transfer_id;
					$row[] = date('Y-m-d H:i:s', $post->report_date);
					$row[] = $post->username;
					$row[] = (($post->order_id) ? $post->order_id:"-");
					$row[] = (($post->order_id_alias) ? $post->order_id_alias:"-");
					$row[] = (($post->from_wallet == 'MAIN') ? $this->lang->line('label_main_wallet') : $this->lang->line('game_' . strtolower($post->from_wallet)));
					$row[] = $from;
					$row[] = $post->deposit_amount;
					$row[] = $post->withdrawal_amount;
					$row[] = $to;
					$row[] = (($post->to_wallet == 'MAIN') ? $this->lang->line('label_main_wallet') : $this->lang->line('game_' . strtolower($post->to_wallet)));
					$data[] = $row;
				}
			}
			//Output
			$json_data = array(
							"draw"            => intval($this->input->post('draw')), 
							"recordsFiltered" => intval($totalFiltered), 
							"data"            => $data,
							"total_data"      => $total_data,
							"csrfHash" 		  => $this->security->get_csrf_hash()					
						);
			echo json_encode($json_data); 
			exit();
		}	
    }
	/*************************LOGIN REPORT*******************************************/
	public function login()
	{
		if(permission_validation(PERMISSION_LOGIN_REPORT) == TRUE)
		{
			$this->save_current_url('report/login');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_login_report');
			$data_search = array();
			$this->session->unset_userdata('search_report_logins');
			if($_GET){
				$data_search['username'] = (isset($_GET['username'])?$_GET['username']:'');
			}
			$data['data_search'] = $data_search;
			$this->load->view('login_report_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function login_search()
	{
		if(permission_validation(PERMISSION_LOGIN_REPORT) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			//Set form rules
			$config = array(
							array(
									'field' => 'from_date',
									'label' => strtolower($this->lang->line('label_from_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							),
							array(
									'field' => 'to_date',
									'label' => strtolower($this->lang->line('label_to_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							)
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$from_date = strtotime(trim($this->input->post('from_date', TRUE)));
				$to_date = strtotime(trim($this->input->post('to_date', TRUE)));
				$days = $this->cal_days_in_year(date('Y', $from_date));
				$date_range = (($days+1) * 86400);
				$time_diff = ($to_date - $from_date);
				if($time_diff < 0 OR $time_diff > $date_range)
				{
					$json['msg'] = $this->lang->line('error_invalid_year_range');
				}
				else
				{
					$data = array( 
						'from_date' => trim($this->input->post('from_date', TRUE)),
						'to_date' => trim($this->input->post('to_date', TRUE)),
						'detail' => trim($this->input->post('detail', TRUE)),
						'username' => trim($this->input->post('username', TRUE)),
						'platform' => trim($this->input->post('platform', TRUE)),
						'ip_address' => trim($this->input->post('ip_address', TRUE)),
						'user_group_type' => trim($this->input->post('user_group_type', TRUE)),
					);
					$this->session->set_userdata('search_report_logins', $data);
					$json['status'] = EXIT_SUCCESS;
				}
			}
			else 
			{
				$error = array(
							'from_date' => form_error('from_date'), 
							'to_date' => form_error('to_date')
						);
				if( ! empty($error['from_date']))
				{
					$json['msg'] = $error['from_date'];
				}
				else if( ! empty($error['to_date']))
				{
					$json['msg'] = $error['to_date'];
				}
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function login_listing()
    {
		if(permission_validation(PERMISSION_LOGIN_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'a.login_report_id',
				1 => 'a.report_date',
				2 => 'a.username',
				3 => 'a.ip_address',
				4 => 'a.status',
				5 => 'a.platform',
			);
			$col = 0;
			$dir = "";
			if( ! empty($order))
			{
				foreach($order as $o)
				{
					$col = $o['column'];
					$dir = $o['dir'];
				}
			}
			if($dir != "asc" && $dir != "desc")
			{
				$dir = "desc";
			}
			if( ! isset($columns[$col]))
			{
				$order = $columns[0];
			}
			else
			{
				$order = $columns[$col];
			}
			$arr = $this->session->userdata('search_report_logins');				
			$where = '';		
			if(isset($arr['from_date']))
			{
				if( ! empty($arr['from_date']))
				{
					$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
				}
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
				}
				if($arr['detail'] == STATUS_FAIL OR $arr['detail'] == STATUS_SUCCESS)
				{
					$where .= ' AND a.status = ' . $arr['detail'];
				}
				$where .= ' AND a.user_group_type = ' . USER_GROUP_PLAYER;
				if( ! empty($arr['username']))
				{
					$where .= " AND a.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	
				}
				if($arr['platform'] == PLATFORM_WEB OR $arr['platform'] == PLATFORM_MOBILE_WEB OR $arr['platform'] == PLATFORM_APP_ANDROID OR $arr['platform'] == PLATFORM_APP_IOS)
				{
					$where .= ' AND a.platform = ' . $arr['platform'];
				}
				if( ! empty($arr['ip_address']))
				{
					$where .= " AND a.ip_address LIKE '%" . $arr['ip_address'] . "%' ESCAPE '!'";	
				}
			}
			$select = implode(',', $columns);
			$order = substr($order, 2);
			$dbprefix = $this->db->dbprefix;
			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}login_report a, {$dbprefix}users b WHERE (a.username = b.username) AND (b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' OR b.user_id = " . $this->session->userdata('root_user_id') . ") $where)";
			$query_string .= " UNION ALL ";
			$query_string .= "(SELECT {$select} FROM {$dbprefix}login_report a, {$dbprefix}sub_accounts b, {$dbprefix}users c WHERE (a.username = b.username) AND (b.upline = c.username) AND (c.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' OR c.user_id = " . $this->session->userdata('root_user_id') . ") $where)";
			$query_string .= " UNION ALL ";
			$query_string .= "(SELECT {$select} FROM {$dbprefix}login_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
			$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if($query->num_rows() > 0)
			{
				$posts = $query->result();  
			}
			$query->free_result();
			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();
			$query->free_result();
			//Prepare data
			$data = array();
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					$row = array();
					$row[] = $post->login_report_id;
					$row[] = (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-');
					$row[] = $post->username;
					$row[] = ( ! empty($post->ip_address) ? $post->ip_address : '-');
					switch($post->status)
					{
						case STATUS_SUCCESS: $row[] = $this->lang->line('label_login_successful'); break;
						default: $row[] = $this->lang->line('label_login_fail'); break;
					}
					switch($post->platform)
					{
						case PLATFORM_MOBILE_WEB: $row[] = $this->lang->line('label_mobile_web'); break;
						case PLATFORM_APP_ANDROID: $row[] = $this->lang->line('label_mobile_app_android'); break;
						case PLATFORM_APP_IOS: $row[] = $this->lang->line('label_mobile_app_ios'); break;
						default: $row[] = $this->lang->line('label_website'); break;
					}
					$data[] = $row;
				}
			}
			//Output
			$json_data = array(
							"draw"            => intval($this->input->post('draw')), 
							"recordsFiltered" => intval($totalFiltered), 
							"data"            => $data,
							"csrfHash" 		  => $this->security->get_csrf_hash()					
						);
			echo json_encode($json_data); 
			exit();
		}	
    }
	/*************************BANK REPORT*******************************************/
    public function bank_report()
	{

		if(permission_validation(PERMISSION_BANK_REPORT_VIEW) == TRUE)
		{
			$this->save_current_url('report/bank_report');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_bank_report');
			$this->session->unset_userdata('searches_bank_report');
			$data_search = array( 
				'from_date' => date('Y-m-d 00:00:00'),
				'to_date' => date('Y-m-d 23:59:59')
			);
			$this->session->set_userdata('searches_bank_report', $data);
			$this->load->view('bank_report_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function bank_report_search()
	{

		if(permission_validation(PERMISSION_BANK_REPORT_VIEW) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			//Set form rules
			$config = array(
							array(
									'field' => 'from_date',
									'label' => strtolower($this->lang->line('label_from_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							),
							array(
									'field' => 'to_date',
									'label' => strtolower($this->lang->line('label_to_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							)
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$from_date = strtotime(trim($this->input->post('from_date', TRUE)));
				$to_date = strtotime(trim($this->input->post('to_date', TRUE)));
				$days = cal_days_in_month(CAL_GREGORIAN, date('n', $from_date), date('Y', $from_date));
				$date_range = (($days+1) * 86400);
				$time_diff = ($to_date - $from_date);
				if($time_diff < 0 OR $time_diff > $date_range)
				{
					$json['msg'] = $this->lang->line('error_invalid_month_range');
				}
				else
				{
					$data = array( 
									'from_date' => trim($this->input->post('from_date', TRUE)),
									'to_date' => trim($this->input->post('to_date', TRUE))
								);
					$this->session->set_userdata('searches_bank_report', $data);
					$json['status'] = EXIT_SUCCESS;
				}
			}
			else 
			{
				$error = array(
							'from_date' => form_error('from_date'), 
							'to_date' => form_error('to_date')
						);
				if( ! empty($error['from_date']))
				{
					$json['msg'] = $error['from_date'];
				}
				else if( ! empty($error['to_date']))
				{
					$json['msg'] = $error['to_date'];
				}
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function bank_report_listing()
    {

		if(permission_validation(PERMISSION_BANK_REPORT_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$arr = $this->session->userdata('searches_bank_report');
			$fromDate = $arr['from_date'];
			$toDate = $arr['to_date'];

			$bankDepositOffline = $this->bank_model->getOfflineDeposit($fromDate,$toDate);
			$bankWithdrawOffline = $this->bank_model->getOfflineWithdraw($fromDate,$toDate);
			$offlineBanking = array();
			foreach($bankDepositOffline as $bankItem){
				$offlineBanking[$bankItem['bank_name']]['deposits'] = $bankItem['total'];
			}
			foreach($bankWithdrawOffline as $bankItem){
				$offlineBanking[$bankItem['bank_name']]['withdrawals'] = $bankItem['total'];
			}

			$data = array();
			foreach($offlineBanking as $key=>$value){
				$deposit = (isset($value['deposits'])) ? $value['deposits'] : 0;
				$withdrawals = (isset($value['withdrawals'])) ? $value['withdrawals'] : 0;
				$bank_balance = $deposit - $withdrawals;
				$row_data = array();
				$row_data[] = $key;
				$row_data[] = (isset($value['deposits'])) ? number_format($value['deposits'], 2, '.', ',') : 0;
				$row_data[] = (isset($value['withdrawals'])) ? number_format($value['withdrawals'], 2, '.', ',') : 0;
				$row_data[] = number_format($bank_balance, 2, '.', ',');
				$data[] = $row_data;
			}

			$totalFiltered = count($offlineBanking);
			$json_data = array(
				"draw"            => intval($this->input->post('draw')), 
				"recordsFiltered" => intval($totalFiltered), 
				"data"            => $data,
				"csrfHash" 		  => $this->security->get_csrf_hash()					
			);
			echo json_encode($json_data); 
			exit();
		}
	}
    /*************************POINT AGENT REPORT*******************************************/
    public function point_agent()
	{
		if(permission_validation(PERMISSION_POINT_TRANSACTION_REPORT) == TRUE)
		{
			$this->save_current_url('report/point_agent');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_point_transaction_report_agent');
			$this->session->unset_userdata('searches_point_agent');
			$data_search = array( 
				'from_date' => date('Y-m-d 00:00:00'),
				'to_date' => date('Y-m-d 23:59:59'),
				'username' => ""
			);
			$this->session->set_userdata('searches_point_agent', $data);
			$this->load->view('point_agent_transaction_report_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
    public function point_agent_search()
	{
		if(permission_validation(PERMISSION_POINT_TRANSACTION_REPORT) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			//Set form rules
			$config = array(
							array(
									'field' => 'from_date',
									'label' => strtolower($this->lang->line('label_from_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							),
							array(
									'field' => 'to_date',
									'label' => strtolower($this->lang->line('label_to_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							)
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$from_date = strtotime(trim($this->input->post('from_date', TRUE)));
				$to_date = strtotime(trim($this->input->post('to_date', TRUE)));
				$days = cal_days_in_month(CAL_GREGORIAN, date('n', $from_date), date('Y', $from_date));
				$date_range = (($days+1) * 86400);
				$time_diff = ($to_date - $from_date);
				if($time_diff < 0 OR $time_diff > $date_range)
				{
					$json['msg'] = $this->lang->line('error_invalid_month_range');
				}
				else
				{
					$data = array( 
									'from_date' => trim($this->input->post('from_date', TRUE)),
									'to_date' => trim($this->input->post('to_date', TRUE)),
									'username' => trim($this->input->post('username', TRUE))
								);
					$this->session->set_userdata('searches_point_agent', $data);
					$json['status'] = EXIT_SUCCESS;
				}
			}
			else 
			{
				$error = array(
							'from_date' => form_error('from_date'), 
							'to_date' => form_error('to_date'),
							'username' => form_error('username')
						);
				if( ! empty($error['from_date']))
				{
					$json['msg'] = $error['from_date'];
				}
				else if( ! empty($error['to_date']))
				{
					$json['msg'] = $error['to_date'];
				}
				else if( ! empty($error['username']))
				{
					$json['msg'] = $error['username'];
				}
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function point_agent_listing()
    {
		if(permission_validation(PERMISSION_POINT_TRANSACTION_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$data = array();
			$arr = $this->session->userdata('searches_point_agent');
			$dbprefix = $this->db->dbprefix;
			$data = array();
			$this->db->select('user_id,username');
			$this->db->group_start();
			$this->db->like('upline_ids',"," . $this->session->userdata('root_user_id') . ",");
			$this->db->or_where('user_id',$this->session->userdata('root_user_id'));
			$this->db->group_end();
			if(!empty($arr['username']))
			{
				$this->db->where('username',$arr['username']);
			}
			$this->db->limit($limit,$start);
			$this->db->order_by('user_id',"ASC");
			$query = $this->db->get('users');
			if($query->num_rows() > 0)
			{
				foreach($query->result_array() as $row)
				{
					$total_deposit_point = 0;
					$total_withdraw_point = 0;
					$this->db->select("SUM(a.deposit_amount) AS total_points_deposited");
					$this->db->from('point_transfer_report a');
					$this->db->join('users b','a.from_username = b.username');
					$this->db->where('a.to_username',$row['username']);
					if(isset($arr['from_date']))
					{
						if( ! empty($arr['from_date']))
						{
							$this->db->where('a.report_date >= ',strtotime($arr['from_date']));
						}
						if( ! empty($arr['to_date']))
						{
							$this->db->where('a.report_date <= ',strtotime($arr['to_date']));
						}
					}
					$dp_query = $this->db->get();
					if($dp_query->num_rows() > 0)
					{
						$dp_row = $dp_query->row();
						$total_deposit_point = $dp_row->total_points_deposited;
					}
					$this->db->select("SUM(a.withdrawal_amount) AS total_points_withdraw");
					$this->db->from('point_transfer_report a');
					$this->db->join('users b','a.to_username = b.username');
					$this->db->where('a.from_username',$row['username']);
					if(isset($arr['from_date']))
					{
						if( ! empty($arr['from_date']))
						{
							$this->db->where('a.report_date >= ',strtotime($arr['from_date']));
						}
						if( ! empty($arr['to_date']))
						{
							$this->db->where('a.report_date <= ',strtotime($arr['to_date']));
						}
					}
					$wp_query = $this->db->get();
					if($wp_query->num_rows() > 0)
					{
						$wp_row = $wp_query->row();
						$total_withdraw_point = $wp_row->total_points_withdraw;
					}
					$dp_query->free_result();
					$wp_query->free_result();
					$row_data = array();
					$row_data[] = $row['username'];
					$row_data[] = number_format($total_deposit_point, 2, '.', ',');
					$row_data[] = number_format($total_withdraw_point, 2, '.', ',');
					$data[] = $row_data;
				}
			}
			$query->free_result();
			$total_sum_withdraw_point = 0;
			$total_sum_deposit_point = 0;
			/*
			$this->db->select("SUM(a.deposit_amount) AS total_points_deposited");
			$this->db->from('point_transfer_report a');
			$this->db->join('users b','a.from_username = b.username');
			$this->db->join('users c','a.to_username = c.username');
			$this->db->group_start();
			$this->db->like('c.upline_ids',"," . $this->session->userdata('root_user_id') . ",");
			$this->db->or_where('c.user_id',$this->session->userdata('root_user_id'));
			if(!empty($arr['username']))
			{
				$this->db->where('c.username',$arr['username']);
			}
			$this->db->group_end();
			if(isset($arr['from_date']))
			{
				if( ! empty($arr['from_date']))
				{
					$this->db->where('a.report_date >= ',strtotime($arr['from_date']));
				}
				if( ! empty($arr['to_date']))
				{
					$this->db->where('a.report_date <= ',strtotime($arr['to_date']));
				}
			}
			$tdp_query = $this->db->get();
			if($tdp_query->num_rows() > 0)
			{
				$tdp_row = $tdp_query->row();
				$total_sum_deposit_point = $tdp_row->total_points_deposited;
			}
			$this->db->select("SUM(a.withdrawal_amount) AS total_points_withdraw");
			$this->db->from('point_transfer_report a');
			$this->db->join('users b','a.to_username = b.username');
			$this->db->join('users c','a.from_username = c.username');
			$this->db->group_start();
			$this->db->like('c.upline_ids',"," . $this->session->userdata('root_user_id') . ",");
			$this->db->or_where('c.user_id',$this->session->userdata('root_user_id'));
			if(!empty($arr['username']))
			{
				$this->db->where('c.username',$arr['username']);
			}
			$this->db->group_end();
			if(isset($arr['from_date']))
			{
				if( ! empty($arr['from_date']))
				{
					$this->db->where('a.report_date >= ',strtotime($arr['from_date']));
				}
				if( ! empty($arr['to_date']))
				{
					$this->db->where('a.report_date <= ',strtotime($arr['to_date']));
				}
			}
			$twp_query = $this->db->get();
			if($twp_query->num_rows() > 0)
			{
				$twp_row = $twp_query->row();
				$total_sum_withdraw_point = $twp_row->total_points_withdraw;
			}
			$tdp_query->free_result();
			$twp_query->free_result();
			$total_data = array(
				'total_points_withdrawn' => (($total_sum_withdraw_point > 0) ? number_format($total_sum_withdraw_point, 2, '.', ',') : number_format(0, 2, '.', ',')), 
				'total_points_deposited' => (($total_sum_deposit_point > 0) ? number_format($total_sum_deposit_point, 2, '.', ',') : number_format(0, 2, '.', ','))
			);
			*/
			$this->db->select('user_id');
			$this->db->group_start();
			$this->db->like('upline_ids',"," . $this->session->userdata('root_user_id') . ",");
			$this->db->or_where('user_id',$this->session->userdata('root_user_id'));
			$this->db->group_end();
			if(!empty($arr['username']))
			{
				$this->db->where('username',$arr['username']);
			}
			$query_total = $this->db->get('users');
			$totalFiltered = $query_total->num_rows();
			$json_data = array(
				"draw"            => intval($this->input->post('draw')), 
				"recordsFiltered" => intval($totalFiltered), 
				"data"            => $data,
				//"total_data"      => $total_data,
				"csrfHash" 		  => $this->security->get_csrf_hash()					
			);
			echo json_encode($json_data); 
			exit();
		}	
    }
	/*************************REWARD REPORT*******************************************/
	public function reward()
	{
		if(permission_validation(PERMISSION_REWARD_TRANSACTION_REPORT) == TRUE)
		{
			$this->save_current_url('report/reward');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_reward_transaction_report');
			$this->session->unset_userdata('search_report_reward');
			$this->load->view('reward_transaction_report_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function reward_search()
	{
		if(permission_validation(PERMISSION_REWARD_TRANSACTION_REPORT) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			//Set form rules
			$config = array(
							array(
									'field' => 'from_date',
									'label' => strtolower($this->lang->line('label_from_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							),
							array(
									'field' => 'to_date',
									'label' => strtolower($this->lang->line('label_to_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							)
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$from_date = strtotime(trim($this->input->post('from_date', TRUE)));
				$to_date = strtotime(trim($this->input->post('to_date', TRUE)));
				$days = cal_days_in_month(CAL_GREGORIAN, date('n', $from_date), date('Y', $from_date));
				$date_range = (($days+1) * 86400);
				$time_diff = ($to_date - $from_date);
				if($time_diff < 0 OR $time_diff > $date_range)
				{
					$json['msg'] = $this->lang->line('error_invalid_month_range');
				}
				else
				{
					$data = array( 
									'from_date' => trim($this->input->post('from_date', TRUE)),
									'to_date' => trim($this->input->post('to_date', TRUE)),
									'transfer_type' => trim($this->input->post('transfer_type', TRUE)),
									'username' => trim($this->input->post('username', TRUE))
								);
					$this->session->set_userdata('search_report_reward', $data);
					$json['status'] = EXIT_SUCCESS;
				}
			}
			else 
			{
				$error = array(
							'from_date' => form_error('from_date'), 
							'to_date' => form_error('to_date')
						);
				if( ! empty($error['from_date']))
				{
					$json['msg'] = $error['from_date'];
				}
				else if( ! empty($error['to_date']))
				{
					$json['msg'] = $error['to_date'];
				}
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function reward_listing()
    {
		if(permission_validation(PERMISSION_REWARD_TRANSACTION_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
								0 => 'a.reward_transfer_id',
								1 => 'a.report_date',
								2 => 'a.transfer_type',
								3 => 'a.username',
								4 => 'a.withdrawal_amount',
								5 => 'a.deposit_amount',
								6 => 'a.balance_before',
								7 => 'a.balance_after',
								8 => 'a.remark',
								9 => 'a.executed_by',
							);
			$sum_columns = array( 
								0 => 'SUM(a.withdrawal_amount) AS total_points_withdrawn',
								1 => 'SUM(a.deposit_amount) AS total_points_deposited',
							);					
			$col = 0;
			$dir = "";
			if( ! empty($order))
			{
				foreach($order as $o)
				{
					$col = $o['column'];
					$dir = $o['dir'];
				}
			}
			if($dir != "asc" && $dir != "desc")
			{
				$dir = "desc";
			}
			if( ! isset($columns[$col]))
			{
				$order = $columns[0];
			}
			else
			{
				$order = $columns[$col];
			}
			$arr = $this->session->userdata('search_report_reward');				
			$where = '';	
			if(isset($arr['from_date']))
			{
				if( ! empty($arr['from_date']))
				{
					$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
				}
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
				}
				if($arr['transfer_type'] >= 1 && $arr['transfer_type'] <= sizeof(get_transfer_reward_type()))
				{
					$where .= ' AND a.transfer_type = ' . $arr['transfer_type'];
				}
				if( ! empty($arr['username']))
				{
					$where .= " AND a.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	
				}
			}
			$select = implode(',', $columns);
			$order = substr($order, 2);
			$dbprefix = $this->db->dbprefix;
			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}reward_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
			$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if($query->num_rows() > 0)
			{
				$posts = $query->result();  
			}
			$query->free_result();
			//Get total records
			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();
			$query->free_result();
			//Get total sum up
			$sum_select = implode(',', $sum_columns);
			$total_data = array(
							'total_points_withdrawn' => 0, 
							'total_points_deposited' => 0
						);
			$query_string = "(SELECT {$sum_select} FROM {$dbprefix}reward_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
			$query = $this->db->query($query_string);
			if($query->num_rows() > 0)
			{
				foreach($query->result() as $row)
				{
					$total_data = array(
									'total_points_withdrawn' => (($row->total_points_withdrawn > 0) ? $row->total_points_withdrawn : 0), 
									'total_points_deposited' => (($row->total_points_deposited > 0) ? $row->total_points_deposited : 0)
								);
				}
			}
			$query->free_result();
			//Prepare data
			$data = array();
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					$row = array();
					$row[] = $post->reward_transfer_id;
					$row[] = (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-');
					$row[] = $this->lang->line(get_transfer_reward_type($post->transfer_type));
					$row[] = $post->username;
					$row[] = $post->withdrawal_amount;
					$row[] = $post->deposit_amount;
					$row[] = $post->balance_before;
					$row[] = $post->balance_after;
					$row[] = ( ! empty($post->remark) ? $post->remark : '-');
					$row[] = $post->executed_by;
					$data[] = $row;
				}
			}
			//Output
			$json_data = array(
							"draw"            => intval($this->input->post('draw')), 
							"recordsFiltered" => intval($totalFiltered), 
							"data"            => $data,
							"total_data"      => $total_data,
							"csrfHash" 		  => $this->security->get_csrf_hash()					
						);
			echo json_encode($json_data); 
			exit();
		}	
    }
    /*************************VERIFY CODE REPORT*******************************************/
    public function verify_code()
	{
		if(permission_validation(PERMISSION_VERIFY_CODE_REPORT) == TRUE)
		{
			$this->save_current_url('report/verify_code');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_verify_code_report');
			$this->session->unset_userdata('search_verify_code');
			$this->load->view('verify_code_report_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function verify_code_search()
	{
		if(permission_validation(PERMISSION_VERIFY_CODE_REPORT) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			//Set form rules
			$config = array(
							array(
									'field' => 'from_date',
									'label' => strtolower($this->lang->line('label_from_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							),
							array(
									'field' => 'to_date',
									'label' => strtolower($this->lang->line('label_to_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							)
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$from_date = strtotime(trim($this->input->post('from_date', TRUE)));
				$to_date = strtotime(trim($this->input->post('to_date', TRUE)));
				$days = cal_days_in_month(CAL_GREGORIAN, date('n', $from_date), date('Y', $from_date));
				$date_range = (($days+1) * 86400);
				$time_diff = ($to_date - $from_date);
				if($time_diff < 0 OR $time_diff > $date_range)
				{
					$json['msg'] = $this->lang->line('error_invalid_month_range');
				}
				else
				{
					$data = array( 
						'from_date' => trim($this->input->post('from_date', TRUE)),
						'to_date' => trim($this->input->post('to_date', TRUE)),
						'status' => trim($this->input->post('status', TRUE)),
						'mobile' => trim($this->input->post('mobile', TRUE)),
						'username' => trim($this->input->post('username', TRUE)),
						'transaction_id' => trim($this->input->post('transaction_id', TRUE)),
					);
					$this->session->set_userdata('search_verify_code', $data);
					$json['status'] = EXIT_SUCCESS;
				}
			}
			else 
			{
				$error = array(
					'from_date' => form_error('from_date'), 
					'to_date' => form_error('to_date')
				);
				if( ! empty($error['from_date']))
				{
					$json['msg'] = $error['from_date'];
				}
				else if( ! empty($error['to_date']))
				{
					$json['msg'] = $error['to_date'];
				}
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function verify_code_listing()
    {
		if(permission_validation(PERMISSION_VERIFY_CODE_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'sms_log_id',
				1 => 'created_date',
				2 => 'updated_date',
				3 => 'transaction_id',
				4 => 'username',
				5 => 'mobile',
				6 => 'code',
				7 => 'ip_address',
				8 => 'status',
				9 => 'remark',
				10 => 'resp_data',
			);
			$col = 0;
			$dir = "";
			if( ! empty($order))
			{
				foreach($order as $o)
				{
					$col = $o['column'];
					$dir = $o['dir'];
				}
			}
			if($dir != "asc" && $dir != "desc")
			{
				$dir = "desc";
			}
			if( ! isset($columns[$col]))
			{
				$order = $columns[0];
			}
			else
			{
				$order = $columns[$col];
			}
			$arr = $this->session->userdata('search_verify_code');				
			$where = 'WHERE sms_log_id != "ABC"';
			if(isset($arr['from_date']))
			{
				if( ! empty($arr['from_date']))
				{
					$where .= ' AND created_date >= ' . strtotime($arr['from_date']);
				}
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND created_date <= ' . strtotime($arr['to_date']);
				}
				if(isset($arr['status']) && $arr['status'] !== "-1"){
					$where .= " AND status = " . $arr['status'];
				}
				if(!empty($arr['mobile'])){
					$where .= " AND mobile = '" . $arr['mobile']."'";
				}
				if(!empty($arr['transaction_id'])){
					$where .= " AND transaction_id = '" . $arr['transaction_id']."'";
				}
				if( ! empty($arr['username']))
				{
					$where .= " AND username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	
				}
			}
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;
			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}sms_log $where)";
			$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if($query->num_rows() > 0)
			{
				$posts = $query->result();  
			}
			$query->free_result();
			//Get total records
			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();
			$query->free_result();
			$query->free_result();
			//Prepare data
			$data = array();
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					$row = array();
					$row[] = $post->sms_log_id;
					$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
					$row[] = (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-');
					$row[] = ( ! empty($post->transaction_id) ? $post->transaction_id : '-');
					$row[] = ( ! empty($post->username) ? $post->username : '-');
					$row[] = $post->mobile;
					$row[] = $post->code;
					$row[] = $post->ip_address;
					switch($post->status)
					{
						case STATUS_COMPLETE: $row[] = '<span class="badge bg-success">' . $this->lang->line('status_completed') . '</span>'; break;
						case STATUS_CANCEL: $row[] = '<span class="badge bg-danger">' . $this->lang->line('status_cancelled') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary">' . $this->lang->line('status_pending') . '</span>'; break;
					}
					$row[] = ( ! empty($post->remark) ? $post->remark : '-');
					$row[] = ( ! empty($post->resp_data) ? $post->resp_data : '-');
					$data[] = $row;
				}
			}
			//Output
			$json_data = array(
							"draw"            => intval($this->input->post('draw')), 
							"recordsFiltered" => intval($totalFiltered), 
							"data"            => $data,
							"csrfHash" 		  => $this->security->get_csrf_hash()					
						);
			echo json_encode($json_data); 
			exit();
		}	
    }
    /*************************RISK REPORT*******************************************/
	public function player_risk()
	{
		if(permission_validation(PERMISSION_PLAYER_RISK_REPORT) == TRUE)
		{
			$this->save_current_url('report/player_risk');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_player_risk_report');
			$data['miscellaneous'] = $this->miscellaneous_model->get_miscellaneous();
			$this->session->unset_userdata('search_player_risk');
			$data_search = array( 
				'from_date' => date('Y-m-d 00:00:00'),
				'to_date' => date('Y-m-d 23:59:59'),
				'percentage' => '',
				'suspended' => '',
				'username' => '',
			);
			if($_GET){
				$risk_id = (isset($_GET['id'])?$_GET['id']:'');
				$risk_data = $this->risk_model->get_player_risk_data($risk_id);
				if(!empty($risk_data)){
					$data_search['from_date'] = date('Y-m-d 00:00:00',$risk_data['report_date']);
					$data_search['to_date'] = date('Y-m-d 23:59:59',$risk_data['end_date']);
					$data_search['player_risk_id'] = $risk_id;
				}
			}
			$data['data_search'] = $data_search;
			$this->session->set_userdata('search_player_risk', $data_search);
			$this->load->view('player_risk_report_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function player_risk_search()
	{
		if(permission_validation(PERMISSION_PLAYER_RISK_REPORT) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			//Set form rules
			$config = array(
							array(
									'field' => 'from_date',
									'label' => strtolower($this->lang->line('label_from_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							),
							array(
									'field' => 'to_date',
									'label' => strtolower($this->lang->line('label_to_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							)
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$from_date = strtotime(trim($this->input->post('from_date', TRUE)));
				$to_date = strtotime(trim($this->input->post('to_date', TRUE)));
				$days = cal_days_in_month(CAL_GREGORIAN, date('n', $from_date), date('Y', $from_date));
				$date_range = (($days+1) * 86400);
				$time_diff = ($to_date - $from_date);
				if($time_diff < 0 OR $time_diff > $date_range)
				{
					$json['msg'] = $this->lang->line('error_invalid_month_range');
				}
				else
				{
					$data = array( 
						'from_date' => trim($this->input->post('from_date', TRUE)),
						'to_date' => trim($this->input->post('to_date', TRUE)),
						'percentage' => trim($this->input->post('percentage', TRUE)),
						'suspended' => trim($this->input->post('suspended', TRUE)),
						'username' => trim($this->input->post('username', TRUE)),
						'player_risk_id' => trim($this->input->post('player_risk_id', TRUE)),
					);
					$this->session->set_userdata('search_player_risk', $data);
					$json['status'] = EXIT_SUCCESS;
				}
			}
			else 
			{
				$error = array(
							'from_date' => form_error('from_date'), 
							'to_date' => form_error('to_date')
						);
				if( ! empty($error['from_date']))
				{
					$json['msg'] = $error['from_date'];
				}
				else if( ! empty($error['to_date']))
				{
					$json['msg'] = $error['to_date'];
				}
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function player_risk_listing()
    {
		if(permission_validation(PERMISSION_PLAYER_RISK_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'a.player_risk_id',
				1 => 'a.report_date',
				2 => 'b.username',
				3 => 'a.suspended',
				4 => 'a.percentage',
				5 => 'a.total_win_lose',
				6 => 'a.win_loss_suspend',
			);
			$col = 0;
			$dir = "";
			if( ! empty($order))
			{
				foreach($order as $o)
				{
					$col = $o['column'];
					$dir = $o['dir'];
				}
			}
			if($dir != "asc" && $dir != "desc")
			{
				$dir = "desc";
			}
			if( ! isset($columns[$col]))
			{
				$order = $columns[0];
			}
			else
			{
				$order = $columns[$col];
			}
			$arr = $this->session->userdata('search_player_risk');				
			$where = '';	
			if(isset($arr['from_date']))
			{
				if( ! empty($arr['from_date']))
				{
					$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
				}
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
				}
				if( ! empty($arr['suspended']))
				{
					$where .= ' AND a.suspended <= ' . $arr['suspended'];
				}
				if( ! empty($arr['percentage']))
				{
					$where .= ' AND a.percentage <= ' . $arr['percentage'];
				}
				if( ! empty($arr['username']))
				{
					$where .= " AND b.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	
				}
				if( ! empty($arr['player_risk_id']))
				{
					$where .= ' AND a.player_risk_id = ' . $arr['player_risk_id'];
				}
			}
			$select = implode(',', $columns);
			$order = substr($order, 2);
			$dbprefix = $this->db->dbprefix;
			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}player_risk_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
			$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if($query->num_rows() > 0)
			{
				$posts = $query->result();  
			}
			$query->free_result();
			//Get total records
			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();
			$query->free_result();
			//Prepare data
			$data = array();
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					$row = array();
					$row[] = $post->player_risk_id;
					$row[] = (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-');
					$row[] = $post->username;
					switch($post->suspended)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->player_risk_id . '">' . $this->lang->line('status_unsuspend') . '</span>'; break;
						default: $row[] = '<span class="badge bg-danger" id="uc3_' . $post->player_risk_id . '">' . $this->lang->line('status_suspend') . '</span>'; break;
					}
					$row[] = $post->percentage;
					$row[] = $post->total_win_lose;
					$row[] = $post->win_loss_suspend;
					$data[] = $row;
				}
			}
			//Output
			$json_data = array(
							"draw"            => intval($this->input->post('draw')), 
							"recordsFiltered" => intval($totalFiltered), 
							"data"            => $data,
							"csrfHash" 		  => $this->security->get_csrf_hash()					
						);
			echo json_encode($json_data); 
			exit();
		}	
    }
    /*************************WIN LOSS REPORT*******************************************/
    public function winloss_sum(){
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)
		{
			$this->save_current_url('report/winloss_sum');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_win_loss_report');
			$this->session->unset_userdata('search_report_winloss_sum');
			$this->load->view('winloss_sum_report_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function winloss_sum_search(){
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			//Set form rules
			$config = array(
				array(
						'field' => 'from_date',
						'label' => strtolower($this->lang->line('label_from_date')),
						'rules' => 'trim|required|callback_date_check',
						'errors' => array(
								'required' => $this->lang->line('error_invalid_datetime_format'),
								'date_check' => $this->lang->line('error_invalid_datetime_format')
						)
				),
				array(
						'field' => 'to_date',
						'label' => strtolower($this->lang->line('label_to_date')),
						'rules' => 'trim|required|callback_date_check',
						'errors' => array(
							'required' => $this->lang->line('error_invalid_datetime_format'),
							'date_check' => $this->lang->line('error_invalid_datetime_format')
					)
				)
			);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$from_date = strtotime(trim($this->input->post('from_date', TRUE)));
				$to_date = strtotime(trim($this->input->post('to_date', TRUE)));
				$days = cal_days_in_month(CAL_GREGORIAN, date('n', $from_date), date('Y', $from_date));
				$date_range = (($days+1) * 86400);
				$time_diff = ($to_date - $from_date);
				if($time_diff < 0 OR $time_diff > $date_range)
				{
					$json['msg'] = $this->lang->line('error_invalid_month_range');
				}
				else
				{
					$data = array( 
						'from_date' => trim($this->input->post('from_date', TRUE)),
						'to_date' => trim($this->input->post('to_date', TRUE)),
						'excludezero' =>  trim($this->input->post('excludezero', TRUE)),
						'excludeProviderCheckboxes' => $this->input->post('excludeProviderCheckboxes', TRUE),
						'excludeGametypeCheckboxes' => $this->input->post('excludeGametypeCheckboxes', TRUE),
					);
					if(!empty($data['from_date'])){
						$data['from_date'] .= " 00:00:00";
					}
					if(!empty($data['to_date'])){
						$data['to_date'] .= " 23:59:59";
					}
					$this->session->set_userdata('search_report_winloss_sum', $data);
					$json['status'] = EXIT_SUCCESS;
				}
			}
			else 
			{
				$error = array(
					'from_date' => form_error('from_date'), 
					'to_date' => form_error('to_date'),
				);
				if( ! empty($error['from_date']))
				{
					$json['msg'] = $error['from_date'];
				}
				else if( ! empty($error['to_date']))
				{
					$json['msg'] = $error['to_date'];
				}
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function winloss_sum_listing($num = NULL, $username = NULL){
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$arr = $this->session->userdata('search_report_winloss_sum');
			$dbprefix = $this->db->dbprefix;
			$data = array();
			$where_total_all = "";
			$where_total_bet_count = "";
			$where_total_bet_amount = "";
			$where_total_win_loss = "";
			$where_total_rolling_amount = "";
			$where_total_deposit = "";
			$where_total_deposit_offline = "";
			$where_total_deposit_online = "";
			$where_total_deposit_point = "";
			$where_total_withdrawal = "";
			$where_total_withdrawal_offline = "";
			$where_total_withdrawal_online = "";
			$where_total_withdrawal_point = "";
			$where_total_promotion = "";
			$where_total_adjust = "";
			$where_total_adjust_in = "";
			$where_total_adjust_out = "";
			$where_total_bonus = "";
			$upline_query_string = "SELECT MU.*";
			$where_total_bet_count .= "AP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_bet_count .= " AND ATR.player_id = AP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_bet_count .= ' AND ATR.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_bet_count .= ' AND ATR.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(ATR.total_bet) AS total_bet FROM {$dbprefix}total_win_loss_report ATR, {$dbprefix}players AP where $where_total_bet_count ) AS total_bet ";
			//Total Bet Amount
			$where_total_bet_amount .= "BP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_bet_amount .= " AND BTR.player_id = BP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_bet_amount .= ' AND BTR.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_bet_amount .= ' AND BTR.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(BTR.bet_amount) AS total_bet_amount FROM {$dbprefix}total_win_loss_report BTR, {$dbprefix}players BP where $where_total_bet_amount ) AS total_bet_amount ";
			//Total Rolling Amount
			$where_total_rolling_amount .= "DP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_rolling_amount .= " AND DTR.player_id = DP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_rolling_amount .= ' AND DTR.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_rolling_amount .= ' AND DTR.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(DTR.bet_amount_valid) AS total_rolling_amount FROM {$dbprefix}total_win_loss_report DTR, {$dbprefix}players DP where $where_total_rolling_amount ) AS total_rolling_amount ";
			//Total Win Loss
			$where_total_win_loss .= "CP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_win_loss .= " AND CTR.player_id = CP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_win_loss .= ' AND CTR.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_win_loss .= ' AND CTR.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(CTR.win_loss) AS total_win_loss FROM {$dbprefix}total_win_loss_report CTR, {$dbprefix}players CP where $where_total_win_loss ) AS total_win_loss ";
			//Total Deposit
			$where_total_deposit .= "EP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_deposit .= " AND ETR.player_id = EP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_deposit .= ' AND ETR.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_deposit .= ' AND ETR.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(ETR.deposit_amount) AS total_deposit FROM {$dbprefix}total_win_loss_report ETR, {$dbprefix}players EP where $where_total_deposit ) AS total_deposit ";
			//Total Deposit offline
			$where_total_deposit_offline .= "EPF.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_deposit_offline .= " AND ETRF.player_id = EPF.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_deposit_offline .= ' AND ETRF.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_deposit_offline .= ' AND ETRF.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(ETRF.deposit_offline_amount) AS total_deposit_offline FROM {$dbprefix}total_win_loss_report ETRF, {$dbprefix}players EPF where $where_total_deposit_offline ) AS total_deposit_offline ";
			//Total Deposit online
			$where_total_deposit_online .= "EPN.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_deposit_online .= " AND ETRN.player_id = EPN.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_deposit_online .= ' AND ETRN.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_deposit_online .= ' AND ETRN.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(ETRN.deposit_online_amount) AS total_deposit_online FROM {$dbprefix}total_win_loss_report ETRN, {$dbprefix}players EPN where $where_total_deposit_online ) AS total_deposit_online ";
			$where_total_deposit_point .= "EPP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_deposit_point .= " AND ETRP.player_id = EPP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_deposit_point .= ' AND ETRP.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_deposit_point .= ' AND ETRP.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(ETRP.deposit_point_amount) AS total_deposit_point FROM {$dbprefix}total_win_loss_report ETRP, {$dbprefix}players EPP where $where_total_deposit_point ) AS total_deposit_point ";
			//Total Withdrawal
			$where_total_withdrawal .= "FP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_withdrawal .= " AND FTR.player_id = FP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_withdrawal .= ' AND FTR.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_withdrawal .= ' AND FTR.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(FTR.withdrawals_amount) AS total_withdrawal FROM {$dbprefix}total_win_loss_report FTR, {$dbprefix}players FP where $where_total_withdrawal ) AS total_withdrawal ";
			//Total Withdrawal offline
			$where_total_withdrawal_offline .= "FPF.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_withdrawal_offline .= " AND FTRF.player_id = FPF.player_id";
			$where_total_withdrawal_offline .= " AND FTRF.withdrawal_type = 1";
			$where_total_withdrawal_offline .= " AND FTRF.status = 1";
			if( ! empty($arr['from_date']))
			{
				$where_total_withdrawal_offline .= ' AND FTRF.updated_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_withdrawal_offline .= ' AND FTRF.updated_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(FTRF.amount) AS total_withdrawal_offline FROM {$dbprefix}withdrawals FTRF, {$dbprefix}players FPF where $where_total_withdrawal_offline ) AS total_withdrawal_offline ";
			//Total Withdrawal online
			$where_total_withdrawal_online .= "FPN.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_withdrawal_online .= " AND FTRN.player_id = FPN.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_withdrawal_online .= ' AND FTRN.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_withdrawal_online .= ' AND FTRN.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(FTRN.withdrawals_online_amount) AS total_withdrawal_online FROM {$dbprefix}total_win_loss_report FTRN, {$dbprefix}players FPN where $where_total_withdrawal_online ) AS total_withdrawal_online ";
			//Total Withdrawal point
			$where_total_withdrawal_point .= "FPP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_withdrawal_point .= " AND FTRP.username = FPP.username";
			if( ! empty($arr['from_date']))
			{
				$where_total_withdrawal_point .= ' AND FTRP.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_withdrawal_point .= ' AND FTRP.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(FTRP.withdrawal_amount) AS total_withdrawal_point FROM {$dbprefix}cash_transfer_report FTRP, {$dbprefix}players FPP where $where_total_withdrawal_point ) AS total_withdrawal_point ";
			//Total Promotion
			$where_total_promotion .= "GP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_promotion .= " AND GTR.player_id = GP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_promotion .= ' AND GTR.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_promotion .= ' AND GTR.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(GTR.promotion_amount) AS total_promotion FROM {$dbprefix}total_win_loss_report GTR, {$dbprefix}players GP where $where_total_promotion ) AS total_promotion ";
			//Total Bomnus
			$where_total_bonus .= "HP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_bonus .= " AND HTR.player_id = HP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_bonus .= ' AND HTR.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_bonus .= ' AND HTR.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(HTR.bonus_amount) AS total_bonus FROM {$dbprefix}total_win_loss_report HTR, {$dbprefix}players HP where $where_total_bonus ) AS total_bonus ";
			//Total Adjust
			$where_total_adjust .= "IP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_adjust .= " AND ITR.player_id = IP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_adjust .= ' AND ITR.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_adjust .= ' AND ITR.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(ITR.adjust_amount) AS total_adjust FROM {$dbprefix}total_win_loss_report ITR, {$dbprefix}players IP where $where_total_adjust ) AS total_adjust ";
			//Total Adjust In
			$where_total_adjust_in .= "IPI.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_adjust_in .= " AND ITRI.player_id = IPI.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_adjust_in .= ' AND ITRI.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_adjust_in .= ' AND ITRI.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(ITRI.adjust_in_amount) AS total_adjust_in FROM {$dbprefix}total_win_loss_report ITRI, {$dbprefix}players IPI where $where_total_adjust_in ) AS total_adjust_in ";
			//Total Adjust Out
			$where_total_adjust_out .= "IPO.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_adjust_out .= " AND ITRO.player_id = IPO.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_adjust_out .= ' AND ITRO.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where_total_adjust_out .= ' AND ITRO.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(ITRO.adjust_out_amount) AS total_adjust_out FROM {$dbprefix}total_win_loss_report ITRO, {$dbprefix}players IPO where $where_total_adjust_out ) AS total_adjust_out ";
			$upline_query_string .= "FROM {$dbprefix}users MU ";
			if(empty($username))
			{
				$num = 1;
				$upline_query_string .= "WHERE MU.user_id = " . $this->session->userdata('root_user_id') . " LIMIT 1";
				$totalFiltered = 1;
			}
			else
			{
				$extract_string = "";
				if(isset($arr['excludezero']) && $arr['excludezero'] == "true"){
					$extract_string = "HAVING total_bet > 0 OR total_deposit > 0 OR total_withdrawal > 0 OR total_promotion > 0 OR total_bonus > 0 OR total_adjust_in > 0 OR total_adjust_out > 0";
				}
				$upline_query_total_string = $upline_query_string;
				$upline_query_total_string .= "WHERE MU.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' AND MU.upline = '{$username}' GROUP BY MU.user_id $extract_string";
				$upline_total_query = $this->db->query($upline_query_total_string);
				$totalFiltered = $upline_total_query->num_rows();
				$upline_total_query->free_result();
				$upline_query_string .= "WHERE MU.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' AND MU.upline = '{$username}' GROUP BY MU.user_id $extract_string LIMIT {$start}, {$limit} ";
			}
			$upline_query = $this->db->query($upline_query_string);
			if($upline_query->num_rows() > 0)
			{
				foreach($upline_query->result() as $upline_row)
				{
					//deposit group
					$deposit = $upline_row->total_deposit;
					$deposit_offline = $upline_row->total_deposit_offline;
					$deposit_online = $upline_row->total_deposit_online;
					$deposit_point = $upline_row->total_deposit_point;
					//withdrawal group
					$withdrawal = $upline_row->total_withdrawal;
					$withdrawal_offline = $upline_row->total_withdrawal_offline;
					$withdrawal_online = $upline_row->total_withdrawal_online;
					$withdrawal_point = $upline_row->total_withdrawal_point;
					//adjust
					$adjust = $upline_row->total_adjust;
					$adjust_in = $upline_row->total_adjust_in;
					$adjust_out = $upline_row->total_adjust_out;
					//promotion amount
					$promotion = $upline_row->total_promotion;
					$bonus = $upline_row->total_bonus;
					//wager
					$total_win_loss = 0;//$upline_row->total_win_loss;
					$total_bet = 0;//$upline_row->total_bet;
					$total_bet_amount = 0;//$upline_row->total_bet_amount;
					$total_rolling_amount = 0;//$upline_row->total_rolling_amount;
					$comm_arr = array(
						GAME_SPORTSBOOK => array(
							'total_bet' => 0,
							'total_bet_amount' => 0,
							'total_win_loss' => 0,
							'total_rolling_amount' => 0,
						),
						GAME_LIVE_CASINO => array(
							'total_bet' => 0,
							'total_bet_amount' => 0,
							'total_win_loss' => 0,
							'total_rolling_amount' => 0,
						),
						GAME_SLOTS => array(
							'total_bet' => 0,
							'total_bet_amount' => 0,
							'total_win_loss' => 0,
							'total_rolling_amount' => 0,
						),
						GAME_COCKFIGHTING => array(
							'total_bet' => 0,
							'total_bet_amount' => 0,
							'total_win_loss' => 0,
							'total_rolling_amount' => 0,
						),
						GAME_OTHERS => array(
							'total_bet' => 0,
							'total_bet_amount' => 0,
							'total_win_loss' => 0,
							'total_rolling_amount' => 0,
						)
					);
					//Get win loss
					$where = '';
					if( ! empty($arr['from_date']))
					{
						$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
					}
					if( ! empty($arr['to_date']))
					{
						$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
					}
					if(!empty($arr['excludeProviderCheckboxes'])){
						$excludeProviderCheckboxes = '"'.implode('","', $arr['excludeProviderCheckboxes']).'"';
						$where .= " AND a.game_provider_code NOT IN(" . $excludeProviderCheckboxes . ")";
					}
					if(!empty($arr['excludeGametypeCheckboxes'])){
						$excludeGametypeCheckboxes = '"'.implode('","', $arr['excludeGametypeCheckboxes']).'"';
						$where .= " AND a.game_type_code NOT IN(" . $excludeGametypeCheckboxes . ")";
					}
					$select = "a.game_type_code, SUM(a.total_bet) AS total_bet, SUM(a.bet_amount) AS total_bet_amount, SUM(a.win_loss) AS total_win_loss, SUM(a.bet_amount_valid) AS total_rolling_amount";			
					$wl_query_string = "SELECT {$select} FROM {$dbprefix}win_loss_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $upline_row->user_id . ",%' ESCAPE '!' $where GROUP BY a.game_type_code";
					$wl_query = $this->db->query($wl_query_string);
					if($wl_query->num_rows() > 0)
					{
						foreach($wl_query->result() as $wl_row)
						{
							$game_type_code = GAME_OTHERS;
							if($wl_row->game_type_code == GAME_SPORTSBOOK OR $wl_row->game_type_code == GAME_LIVE_CASINO OR $wl_row->game_type_code == GAME_SLOTS OR $wl_row->game_type_code == GAME_COCKFIGHTING)
							{
								$game_type_code = $wl_row->game_type_code;
							}
							$total_win_loss += $wl_row->total_win_loss;
							$total_bet += $wl_row->total_bet;
							$total_bet_amount += $wl_row->total_bet_amount;
							$total_rolling_amount += $wl_row->total_rolling_amount;
							$comm_arr[$game_type_code]['total_bet'] = ($comm_arr[$game_type_code]['total_bet'] + $wl_row->total_bet);
							$comm_arr[$game_type_code]['total_bet_amount'] = ($comm_arr[$game_type_code]['total_bet_amount'] + $wl_row->total_bet_amount);
							$comm_arr[$game_type_code]['total_win_loss'] = ($comm_arr[$game_type_code]['total_win_loss'] + $wl_row->total_win_loss);
							$comm_arr[$game_type_code]['total_rolling_amount'] = ($comm_arr[$game_type_code]['total_rolling_amount'] + $wl_row->total_rolling_amount);
						}
					}
					$wl_query->free_result();					
					//Get total
					/*
					$casino_comm = (($comm_arr[GAME_LIVE_CASINO]['total_rolling_amount'] * $upline_row->casino_comm) / 100);
					$slots_comm = (($comm_arr[GAME_SLOTS]['total_rolling_amount'] * $upline_row->slots_comm) / 100);
					$sport_comm = (($comm_arr[GAME_SPORTSBOOK]['total_rolling_amount'] * $upline_row->sport_comm) / 100);
					$other_comm = (($comm_arr[GAME_OTHERS]['total_rolling_amount'] * $upline_row->other_comm) / 100);
					$rolling_commission = ($casino_comm + $slots_comm + $sport_comm + $other_comm);
					$possess_win_loss = (($total_win_loss * $upline_row->possess) / 100);
					$possess_promotion = (($promotion * $upline_row->possess) / 100);
					$possess_bonus = (($bonus * $upline_row->possess) / 100);
					$profit = (($possess_win_loss * -1) - $rolling_commission - $possess_promotion - $possess_bonus);
					*/
					$possess_win_loss = ((($total_win_loss - $comm_arr[GAME_COCKFIGHTING]['total_win_loss']) * $upline_row->possess) / 100);
					$possess_promotion = (($promotion * $upline_row->possess) / 100);
					$possess_bonus = (($bonus * $upline_row->possess) / 100);
					$possess_comission = 0;
					$possess_profit = ($possess_win_loss*-1) - $possess_promotion - $possess_bonus - $possess_comission;
					$casino_comm_rate = $upline_row->casino_comm;
					$slots_comm_rate = $upline_row->slots_comm;
					$sport_comm_rate = $upline_row->sport_comm;
					$cock_fighting_comm_rate = $upline_row->cf_comm;
					$other_comm_rate = $upline_row->other_comm;
					$casino_comm = $comm_arr[GAME_LIVE_CASINO]['total_rolling_amount'] * $casino_comm_rate / 100;
					$slots_comm = $comm_arr[GAME_SLOTS]['total_rolling_amount'] * $slots_comm_rate / 100;
					$sport_comm = $comm_arr[GAME_SPORTSBOOK]['total_rolling_amount'] * $sport_comm_rate / 100;
					$cock_fighting_comm = $comm_arr[GAME_COCKFIGHTING]['total_rolling_amount'] * $cock_fighting_comm_rate / 100;
					$other_comm = $comm_arr[GAME_OTHERS]['total_rolling_amount'] * $other_comm_rate / 100;		
					//Prepare data
					$row = array();
					$row[] = $this->lang->line(get_user_type($upline_row->user_type));
					$row[] = '-';
					$row[] = '<a href="javascript:void(0);" onclick="getDownline(\'' . $upline_row->username . '\', ' . $num . ')">' . $upline_row->username . '</a>';
					$row[] = ( ! empty($upline_row->upline) ? $upline_row->upline : '-');
					$row[] = '<a href="javascript:void(0);" class="text-' . (($deposit > 0) ? 'primary' : 'dark') . '" ' . (($deposit > 0) ? 'onclick="getDownlineDeposit(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>' . number_format($deposit, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($deposit_offline > 0) ? 'primary' : 'dark') . '" ' . (($deposit_offline > 0) ? 'onclick="getDownlineDepositOffline(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>' . number_format($deposit_offline, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($deposit_online > 0) ? 'primary' : 'dark') . '" ' . (($deposit_online > 0) ? 'onclick="getDownlineDepositOnline(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>' . number_format($deposit_online, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($deposit_point > 0) ? 'primary' : 'dark') . '" ' . (($deposit_point > 0) ? 'onclick="getDownlineDepositPoint(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>' . number_format($deposit_point, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($withdrawal > 0) ? 'primary' : 'dark') . '" ' . (($withdrawal > 0) ? 'onclick="getDownlineWithdrawal(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>' .number_format($withdrawal, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($withdrawal_offline > 0) ? 'primary' : 'dark') . '" ' . (($withdrawal_offline > 0) ? 'onclick="getDownlineWithdrawalOffline(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>' .number_format($withdrawal_offline, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($withdrawal_online > 0) ? 'primary' : 'dark') . '" ' . (($withdrawal_online > 0) ? 'onclick="getDownlineWithdrawalOnline(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>' .number_format($withdrawal_online, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($withdrawal_point > 0) ? 'primary' : 'dark') . '" ' . (($withdrawal_point > 0) ? 'onclick="getDownlineWithdrawalPoint(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>' .number_format($withdrawal_point, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($adjust >= 0) ? ($adjust == 0) ? 'dark' : 'primary' : 'danger') . '" ' . (($adjust_in > 0 || $adjust_out > 0 ) ? 'onclick="getDownlineAdjust(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>' . number_format($adjust, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($adjust_in > 0) ? 'primary' : 'dark') . '" ' . (($adjust_in > 0) ? 'onclick="getDownlineAdjustIn(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>' . number_format($adjust_in, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($adjust_out > 0) ? 'primary' : 'dark') . '" ' . (($adjust_out > 0) ? 'onclick="getDownlineAdjustOut(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>' .number_format($adjust_out, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($total_bet > 0) ? 'primary' : 'dark') . '" ' . (($total_bet > 0) ? 'onclick="getDownlineBet(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>' .$total_bet . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($total_bet_amount > 0) ? 'primary' : 'dark') . '" ' . (($total_bet > 0) ? 'onclick="getDownlineBet(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>' .number_format($total_bet_amount, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($total_rolling_amount > 0) ? 'primary' : 'dark') . '" ' . (($total_bet > 0) ? 'onclick="getDownlineBet(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>' .number_format($total_rolling_amount, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($total_win_loss >= 0) ? ($total_win_loss == 0) ? 'dark' : 'danger' : 'primary') . '" ' . (($total_bet > 0) ? 'onclick="getDownlineBet(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>' . number_format($total_win_loss*-1, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($promotion > 0) ? 'danger' : 'dark') . '" ' . (($promotion > 0) ? 'onclick="getDownlinePromotion(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>' . number_format($promotion*-1, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($bonus > 0) ? 'danger' : 'dark') . '" ' . (($bonus > 0) ? 'onclick="getDownlineBonus(\'' . $upline_row->username . '\', ' . $num . ')"' : '') . '>'. number_format($bonus*-1, 2, '.', ',') . '</a>';
					$row[] = $upline_row->possess;
					$row[] = '<span class="text-' . (($possess_win_loss >= 0) ? ($possess_win_loss == 0) ? 'dark' : 'danger' : 'primary') . '">' . number_format($possess_win_loss*-1, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($possess_promotion >= 0) ? ($possess_promotion == 0) ? 'dark' : 'danger' : 'primary') . '">' . number_format($possess_promotion*-1, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($possess_bonus >= 0) ? ($possess_bonus == 0) ? 'dark' : 'danger' : 'primary') . '">' . number_format($possess_bonus*-1, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($possess_comission >= 0) ? ($possess_comission == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($possess_comission, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($possess_profit >= 0) ? ($possess_profit == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($possess_profit, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($comm_arr[GAME_LIVE_CASINO]['total_rolling_amount'] > 0) ? 'primary' : 'dark') . '">' . number_format($comm_arr[GAME_LIVE_CASINO]['total_rolling_amount'], 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($comm_arr[GAME_LIVE_CASINO]['total_win_loss'] >= 0) ? ($comm_arr[GAME_LIVE_CASINO]['total_win_loss'] == 0) ? 'dark' : 'danger' : 'primary') . '">' . number_format($comm_arr[GAME_LIVE_CASINO]['total_win_loss']*-1, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($casino_comm_rate > 0) ? 'primary' : 'dark') . '">' . number_format($casino_comm_rate, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($casino_comm > 0) ? 'primary' : 'dark') . '">' . number_format($casino_comm, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($comm_arr[GAME_SLOTS]['total_rolling_amount'] > 0) ? 'primary' : 'dark') . '">' . number_format($comm_arr[GAME_SLOTS]['total_rolling_amount'], 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($comm_arr[GAME_SLOTS]['total_win_loss'] >= 0) ? ($comm_arr[GAME_SLOTS]['total_win_loss'] == 0) ? 'dark' : 'danger' : 'primary') . '">' . number_format($comm_arr[GAME_SLOTS]['total_win_loss']*-1, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($slots_comm_rate > 0) ? 'primary' : 'dark') . '">' . number_format($slots_comm_rate, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($slots_comm > 0) ? 'primary' : 'dark') . '">' . number_format($slots_comm, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($comm_arr[GAME_SPORTSBOOK]['total_rolling_amount'] > 0) ? 'primary' : 'dark') . '">' . number_format($comm_arr[GAME_SPORTSBOOK]['total_rolling_amount'], 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($comm_arr[GAME_SPORTSBOOK]['total_win_loss'] >= 0) ? ($comm_arr[GAME_SPORTSBOOK]['total_win_loss'] == 0) ? 'dark' : 'danger' : 'primary') . '">' . number_format($comm_arr[GAME_SPORTSBOOK]['total_win_loss']*-1, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($sport_comm_rate > 0) ? 'primary' : 'dark') . '">' . number_format($sport_comm_rate, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($sport_comm > 0) ? 'primary' : 'dark') . '">' . number_format($sport_comm, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($comm_arr[GAME_COCKFIGHTING]['total_rolling_amount'] > 0) ? 'primary' : 'dark') . '">' . number_format($comm_arr[GAME_COCKFIGHTING]['total_rolling_amount'], 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($comm_arr[GAME_COCKFIGHTING]['total_win_loss'] >= 0) ? ($comm_arr[GAME_COCKFIGHTING]['total_win_loss'] == 0) ? 'dark' : 'danger' : 'primary') . '">' . number_format($comm_arr[GAME_COCKFIGHTING]['total_win_loss']*-1, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($cock_fighting_comm_rate > 0) ? 'primary' : 'dark') . '">' . number_format($cock_fighting_comm_rate, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($cock_fighting_comm > 0) ? 'primary' : 'dark') . '">' . number_format($cock_fighting_comm, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($comm_arr[GAME_OTHERS]['total_rolling_amount'] > 0) ? 'primary' : 'dark') . '">' . number_format($comm_arr[GAME_OTHERS]['total_rolling_amount'], 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($comm_arr[GAME_OTHERS]['total_win_loss'] >= 0) ? ($comm_arr[GAME_OTHERS]['total_win_loss'] == 0) ? 'dark' : 'danger' : 'primary') . '">' . number_format($comm_arr[GAME_OTHERS]['total_win_loss']*-1, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($other_comm_rate > 0) ? 'primary' : 'dark') . '">' . number_format($other_comm_rate, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($other_comm > 0) ? 'primary' : 'dark') . '">' . number_format($other_comm, 2, '.', ',') . '</span>';
					/*
					$row[] = '<span class="text-' . (($rolling_commission > 0) ? 'primary' : 'dark') . '">' . number_format($rolling_commission, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($profit >= 0) ? ($profit == 0) ? 'dark' : 'danger' : 'primary') . '">' . $profit . '</span>';
					$data[] = $row;
					*/
					$data[] = $row;
				}
			}
			//Output
			$json_data = array(
							"draw"            => intval($this->input->post('draw')), 
							"recordsFiltered" => intval($totalFiltered), 
							"data"            => $data,
							"csrfHash" 		  => $this->security->get_csrf_hash()					
						);
			echo json_encode($json_data); 
			exit();
		}
	}
	public function winloss_sum_downline($num = NULL, $username = NULL){
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)
		{
			$data['num'] = $num;
			$data['username'] = $username;
			$data['type'] = 'downline';
			$html = $this->load->view('winloss_sum_report_table_downline', $data, TRUE);
			echo $html;
			exit();
		}
	}
	public function winloss_sum_downline_total($num = NULL, $username = NULL){
    	if(permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)
		{
			$arr = $this->session->userdata('search_report_winloss_sum');
			$userData = $this->user_model->get_user_data_by_username($username);
			$dbprefix = $this->db->dbprefix;
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => '',
				'total_data' => '',
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			$json['total_data'] = array(
				"total_deposit" => 0,
				"total_deposit_online" => 0,
				"total_deposit_offline" => 0,
				"total_deposit_point" => 0,
				"total_withdrawal" => 0,
				"total_withdrawal_online" => 0,
				"total_withdrawal_offline" => 0,
				"total_withdrawal_point" => 0,
				"total_adjust" => 0,
				"total_adjust_in" => 0,
				"total_adjust_out" => 0,
				'total_bet' => 0,
				'total_bet_amount' => 0,
				'total_win_loss' => 0,
				'total_rolling_amount' => 0,
				'total_promotion' => 0,
				'total_bonus' => 0,
				'total_possess_win_loss' => 0,
				'total_possess_promotion' => 0,
				'total_possess_bonus' => 0,
				'total_rolling_commission' => 0,
				'total_profit' => 0,
				'total_rolling_amount_live_casino' => 0,
				'total_win_loss_live_casino' => 0,
				'total_rolling_comission_live_casino' => 0,
				'total_rolling_amount_slot' => 0,
				'total_win_loss_slot' => 0,
				'total_rolling_comission_slot' => 0,
				'total_rolling_amount_sportbook' => 0,
				'total_win_loss_sportbook' => 0,
				'total_rolling_comission_sportbook' => 0,
				'total_rolling_amount_cock_fighting' => 0,
				'total_win_loss_cock_fighting' => 0,
				'total_rolling_comission_cock_fighting' => 0,
				'total_win_loss_other' => 0,
				'total_rolling_comission_other' => 0,
				'total_downline' => 0,
			);
			//wager
			$total_win_loss = 0;
			$total_bet = 0;
			$total_bet_amount = 0;
			$total_rolling_amount = 0;
			if(!empty($arr) && !empty($userData)){
				$json['status'] = EXIT_SUCCESS;
				$upline_query_string = "SELECT * FROM {$dbprefix}users WHERE upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' AND upline = '{$username}' ORDER BY username ASC";
				$upline_query = $this->db->query($upline_query_string);
				if($upline_query->num_rows() > 0)
				{
					$json['total_data']['total_downline'] = $upline_query->num_rows();
					foreach($upline_query->result() as $upline_row)
					{
						$total_win_loss = 0;
		    			$total_bet = 0;
		    			$total_bet_amount = 0;
		    			$total_rolling_amount = 0;
						$comm_arr = array(
							GAME_SPORTSBOOK => array(
								'total_bet' => 0,
								'total_bet_amount' => 0,
								'total_win_loss' => 0,
								'total_rolling_amount' => 0,
							),
							GAME_LIVE_CASINO => array(
								'total_bet' => 0,
								'total_bet_amount' => 0,
								'total_win_loss' => 0,
								'total_rolling_amount' => 0,
							),
							GAME_SLOTS => array(
								'total_bet' => 0,
								'total_bet_amount' => 0,
								'total_win_loss' => 0,
								'total_rolling_amount' => 0,
							),
							GAME_COCKFIGHTING => array(
								'total_bet' => 0,
								'total_bet_amount' => 0,
								'total_win_loss' => 0,
								'total_rolling_amount' => 0,
							),
							GAME_OTHERS => array(
								'total_bet' => 0,
								'total_bet_amount' => 0,
								'total_win_loss' => 0,
								'total_rolling_amount' => 0,
							)
						);
						//Get win loss
						$where = '';
						if( ! empty($arr['from_date']))
						{
							$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
						}
						if( ! empty($arr['to_date']))
						{
							$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
						}
						if(!empty($arr['excludeProviderCheckboxes'])){
							$excludeProviderCheckboxes = '"'.implode('","', $arr['excludeProviderCheckboxes']).'"';
							$where .= " AND a.game_provider_code NOT IN(" . $excludeProviderCheckboxes . ")";
						}
						if(!empty($arr['excludeGametypeCheckboxes'])){
							$excludeGametypeCheckboxes = '"'.implode('","', $arr['excludeGametypeCheckboxes']).'"';
							$where .= " AND a.game_type_code NOT IN(" . $excludeGametypeCheckboxes . ")";
						}
						$select = "a.game_type_code, SUM(a.total_bet) AS total_bet, SUM(a.bet_amount) AS total_bet_amount, SUM(a.win_loss) AS total_win_loss, SUM(a.bet_amount_valid) AS total_rolling_amount";			
						$wl_query_string = "SELECT {$select} FROM {$dbprefix}win_loss_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $upline_row->user_id . ",%' ESCAPE '!' $where GROUP BY a.game_type_code";
						$wl_query = $this->db->query($wl_query_string);
						if($wl_query->num_rows() > 0)
						{
							foreach($wl_query->result() as $wl_row)
							{
								$game_type_code = GAME_OTHERS;
								if($wl_row->game_type_code == GAME_SPORTSBOOK OR $wl_row->game_type_code == GAME_LIVE_CASINO OR $wl_row->game_type_code == GAME_SLOTS OR $wl_row->game_type_code == GAME_COCKFIGHTING)
								{
									$game_type_code = $wl_row->game_type_code;
								}
								$total_win_loss += $wl_row->total_win_loss;
								$total_bet += $wl_row->total_bet;
								$total_bet_amount += $wl_row->total_bet_amount;
								$total_rolling_amount += $wl_row->total_rolling_amount;
								$comm_arr[$game_type_code]['total_bet'] = ($comm_arr[$game_type_code]['total_bet'] + $wl_row->total_bet);
								$comm_arr[$game_type_code]['total_bet_amount'] = ($comm_arr[$game_type_code]['total_bet_amount'] + $wl_row->total_bet_amount);
								$comm_arr[$game_type_code]['total_win_loss'] = ($comm_arr[$game_type_code]['total_win_loss'] + $wl_row->total_win_loss);
								$comm_arr[$game_type_code]['total_rolling_amount'] = ($comm_arr[$game_type_code]['total_rolling_amount'] + $wl_row->total_rolling_amount);
							}
						}
						$wl_query->free_result();
						$deposit = 0;
						$deposit_offline = 0;
						$deposit_online = 0;
						$deposit_point = 0;
						//withdrawal
						$withdrawal = 0;
						$withdrawal_offline = 0;
						$withdrawal_online = 0;
						$withdrawal_point = 0;
						//adjust
						$adjust = 0;
						$adjust_in = 0;
						$adjust_out = 0;
						//promotion amount
						$promotion = 0;
						$bonus = 0;
						//Get total
						$where = '';
						if( ! empty($arr['from_date']))
						{
							$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
						}
						if( ! empty($arr['to_date']))
						{
							$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
						}
						$select = "SUM(a.total_bet) AS total_bet, SUM(a.bet_amount) AS total_bet_amount, SUM(a.win_loss) AS total_win_loss, SUM(a.bet_amount_valid) AS total_rolling_amount, SUM(a.deposit_amount) AS total_deposit_amount, SUM(a.withdrawals_amount) AS total_withdrawals_amount, SUM(a.bonus_amount) AS total_bonus_amount, SUM(a.promotion_amount) AS total_promotion_amount, SUM(a.deposit_offline_amount) AS total_deposit_offline, SUM(a.deposit_online_amount) AS total_deposit_online, SUM(a.deposit_point_amount) AS total_deposit_point, SUM(a.withdrawals_offline_amount) AS total_withdrawal_offline, SUM(a.withdrawals_online_amount) AS total_withdrawal_online, SUM(a.withdrawals_point_amount) AS total_withdrawal_point, SUM(a.adjust_amount) AS total_adjust, SUM(a.adjust_in_amount) AS total_adjust_in, SUM(a.adjust_out_amount) AS total_adjust_out ";	
						$total_query_string = "SELECT {$select} FROM {$dbprefix}total_win_loss_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $upline_row->user_id . ",%' ESCAPE '!' $where ";
						$total_query = $this->db->query($total_query_string);
						if($total_query->num_rows() > 0)
						{
							foreach($total_query->result() as $total_row)
							{
								//deposit
								$deposit = $total_row->total_deposit_amount;
								$deposit_offline = $total_row->total_deposit_offline;
								$deposit_online = $total_row->total_deposit_online;
								$deposit_point = $total_row->total_deposit_point;
								//withdrawal
								$withdrawal = $total_row->total_withdrawals_amount;
								$withdrawal_offline = $total_row->total_withdrawal_offline;
								$withdrawal_online = $total_row->total_withdrawal_online;
								$withdrawal_point = $total_row->total_withdrawal_point;
								//adjust
								$adjust = $total_row->total_adjust;
								$adjust_in = $total_row->total_adjust_in;
								$adjust_out = $total_row->total_adjust_out;
								//promotion amount
								$promotion = ($total_row->total_promotion_amount * -1);
								$bonus = ($total_row->total_bonus_amount * -1);
							}
						}
						$possess_win_loss = ((($total_win_loss - $comm_arr[GAME_COCKFIGHTING]['total_win_loss']) * -1 *$upline_row->possess) / 100);
						$possess_promotion = (($promotion * $upline_row->possess) / 100);
						$possess_bonus = (($bonus * $upline_row->possess) / 100);
						$possess_comission = 0;
						$possess_profit = ($possess_win_loss*-1) - $possess_promotion - $possess_bonus - $possess_comission;
						
						$casino_comm = $comm_arr[GAME_LIVE_CASINO]['total_rolling_amount'] * $upline_row->casino_comm / 100;
						$slots_comm = $comm_arr[GAME_SLOTS]['total_rolling_amount'] * $upline_row->slots_comm / 100;
						$sport_comm = $comm_arr[GAME_SPORTSBOOK]['total_rolling_amount'] * $upline_row->sport_comm / 100;
						$cock_fighting_comm = $comm_arr[GAME_COCKFIGHTING]['total_rolling_amount'] * $upline_row->cf_comm / 100;
						$other_comm = $comm_arr[GAME_OTHERS]['total_rolling_amount'] * $upline_row->other_comm / 100;
						/*
						$rolling_commission = ($casino_comm + $slots_comm + $sport_comm + $other_comm);
						$possess_win_loss = (($total_win_loss * $upline_row->possess) / 100);
						$possess_promotion = (($promotion * $upline_row->possess) / 100);
						$possess_bonus = (($bonus * $upline_row->possess) / 100);
						$profit = (($possess_win_loss * -1) - $rolling_commission - $possess_promotion - $possess_bonus);
						*/
						//Prepare data
						//deposit
						$json['total_data']['total_deposit'] += bcdiv($deposit,1,2);
						$json['total_data']['total_deposit_offline'] += bcdiv($deposit_offline,1,2);
						$json['total_data']['total_deposit_online'] += bcdiv($deposit_online,1,2);
						$json['total_data']['total_deposit_point'] += bcdiv($deposit_point,1,2);
						//withdrawal
						$json['total_data']['total_withdrawal'] += bcdiv($withdrawal,1,2);
						$json['total_data']['total_withdrawal_offline'] += bcdiv($withdrawal_offline,1,2);
						$json['total_data']['total_withdrawal_online'] += bcdiv($withdrawal_online,1,2);
						$json['total_data']['total_withdrawal_point'] += bcdiv($withdrawal_point,1,2);
						//adjust
						$json['total_data']['total_adjust'] += bcdiv($adjust,1,2);
						$json['total_data']['total_adjust_in'] += bcdiv($adjust_in,1,2);
						$json['total_data']['total_adjust_out'] += bcdiv($adjust_out,1,2);
						//promotion
						$json['total_data']['total_promotion'] += bcdiv($promotion,1,2);
						$json['total_data']['total_bonus'] += bcdiv($bonus,1,2);
						//wager
						$json['total_data']['total_bet'] += bcdiv($total_bet,1,0);
						$json['total_data']['total_bet_amount'] += bcdiv($total_bet_amount,1,2);
						$json['total_data']['total_win_loss'] += bcdiv($total_win_loss,1,2);
						$json['total_data']['total_rolling_amount'] += bcdiv($total_rolling_amount,1,2);
						//possess
						$json['total_data']['total_possess_win_loss'] += bcdiv($possess_win_loss,1,2);
						$json['total_data']['total_possess_promotion'] += bcdiv($possess_promotion,1,2);
						$json['total_data']['total_possess_bonus'] += bcdiv($possess_bonus,1,2);
						$json['total_data']['total_rolling_commission'] += bcdiv($possess_comission,1,2);
						$json['total_data']['total_profit'] += bcdiv($possess_profit,1,2);
						//game type possess
						$json['total_data']['total_rolling_amount_live_casino'] += bcdiv($comm_arr[GAME_LIVE_CASINO]['total_rolling_amount'],1,2);
						$json['total_data']['total_win_loss_live_casino'] += bcdiv($comm_arr[GAME_LIVE_CASINO]['total_win_loss'] * -1,1,2);
						$json['total_data']['total_rolling_comission_live_casino'] += bcdiv($casino_comm,1,2);
						$json['total_data']['total_rolling_amount_slot'] += bcdiv($comm_arr[GAME_SLOTS]['total_rolling_amount'],1,2);
						$json['total_data']['total_win_loss_slot'] += bcdiv($comm_arr[GAME_SLOTS]['total_win_loss'] * -1,1,2);
						$json['total_data']['total_rolling_comission_slot'] += bcdiv($slots_comm,1,2);
						$json['total_data']['total_rolling_amount_sportbook'] += bcdiv($comm_arr[GAME_SPORTSBOOK]['total_rolling_amount'],1,2);
						$json['total_data']['total_win_loss_sportbook'] += bcdiv($comm_arr[GAME_SPORTSBOOK]['total_win_loss'] * -1,1,2);
						$json['total_data']['total_rolling_comission_sportbook'] += bcdiv($sport_comm,1,2);
						$json['total_data']['total_rolling_amount_cock_fighting'] += bcdiv($comm_arr[GAME_COCKFIGHTING]['total_rolling_amount'],1,2);
						$json['total_data']['total_win_loss_cock_fighting'] += bcdiv($comm_arr[GAME_COCKFIGHTING]['total_win_loss'] * -1,1,2);
						$json['total_data']['total_rolling_comission_cock_fighting'] += bcdiv($cock_fighting_comm,1,2);
						$json['total_data']['total_rolling_amount_other'] += bcdiv($comm_arr[GAME_OTHERS]['total_rolling_amount'],1,2);
						$json['total_data']['total_win_loss_other'] += bcdiv($comm_arr[GAME_OTHERS]['total_win_loss'] * -1,1,2);
						$json['total_data']['total_rolling_comission_other'] += bcdiv($other_comm,1,2);
					}
				}
			}
			$json['total_data']['total_win_loss'] = bcdiv($json['total_data']['total_win_loss'] * -1,1,2);
			$json['total_data']['total_profit'] = bcdiv($json['total_data']['total_profit'] * -1,1,2);
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function winloss_sum_downline_player($num = NULL, $username = NULL){
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)
		{
			$data['num'] = $num;
			$data['username'] = $username;
			$data['type'] = 'downline';
			$html = $this->load->view('winloss_sum_report_table_player', $data, TRUE);
			echo $html;
			exit();
		}
	}
	public function player_winloss_sum_listing($username = NULL){
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$arr = $this->session->userdata('search_report_winloss_sum');
			$userData = $this->user_model->get_user_data_by_username($username);
			(!empty($userData)) ? $userID = $userData['user_id'] : $userID = "abc";
			$dbprefix = $this->db->dbprefix;
			$data = array();
			//Get total
			$where = '';
			if( ! empty($arr['from_date']))
			{
				$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
			}
			$select = "SUM(a.total_bet) AS total_bet, SUM(a.bet_amount) AS total_bet_amount, SUM(a.win_loss) AS total_win_loss, SUM(a.bet_amount_valid) AS total_rolling_amount, SUM(a.deposit_amount) AS total_deposit_amount, SUM(a.withdrawals_amount) AS total_withdrawals_amount, SUM(a.bonus_amount) AS total_bonus_amount, SUM(a.promotion_amount) AS total_promotion_amount, b.username, b.upline";	
			$total_query_string = "SELECT {$select} FROM {$dbprefix}total_win_loss_report a, {$dbprefix}players b WHERE a.player_id = b.player_id AND b.upline_ids LIKE '%," . $userID . ",%' AND b.upline = '{$username}' $where GROUP BY a.player_id ORDER BY b.username ASC LIMIT {$start}, {$limit}";
			$total_query = $this->db->query($total_query_string);
			if($total_query->num_rows() > 0)
			{
				foreach($total_query->result() as $upline_row)
				{	
					$deposit = $upline_row->total_deposit_amount;
					$withdrawal = $upline_row->total_withdrawals_amount;
					$promotion = $upline_row->total_promotion_amount;
					$bonus = $upline_row->total_bonus_amount;
					$total_win_loss = $upline_row->total_win_loss;
					$total_bet = $upline_row->total_bet;
					$total_bet_amount = $upline_row->total_bet_amount;
					$total_rolling_amount = $upline_row->total_rolling_amount;
					//Prepare data
					$row = array();
					$row[] = $this->lang->line('level_ply');
					$row[] = '-';
					$row[] =  $upline_row->username;
					$row[] = ( ! empty($upline_row->upline) ? $upline_row->upline : '-');
					$row[] = '<a href="javascript:void(0);" class="text-' . (($deposit > 0) ? 'primary' : 'dark') . '" onclick="getDownlineDepositPlayer(\'' . $username . '\',\'' . $upline_row->username . '\')">' . number_format($deposit, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($withdrawal > 0) ? 'danger' : 'dark') . '" onclick="getDownlineWithdrawalPlayer(\'' . $username . '\',\'' . $upline_row->username . '\')">' . number_format($withdrawal, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($total_bet > 0) ? 'primary' : 'dark') . '" onclick="getDownlineBetPlayer(\'' . $username . '\',\'' . $upline_row->username . '\')">' . $total_bet . '</a>';
					$row[] = '<span class="text-' . (($total_bet_amount > 0) ? 'primary' : 'dark') . '">' . number_format($total_bet_amount, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($total_win_loss >= 0) ? ($total_win_loss == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($total_win_loss, 2, '.', ',') . '</span>';
					$row[] = '<span class="text-' . (($total_rolling_amount > 0) ? 'primary' : 'dark') . '">' . number_format($total_rolling_amount, 2, '.', ',') . '</span>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($promotion > 0) ? 'primary' : 'dark') . '" onclick="getDownlinePromotionPlayer(\'' . $username . '\',\'' . $upline_row->username . '\')">' . number_format($promotion, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($bonus > 0) ? 'primary' : 'dark') . '" onclick="getDownlineBonusPlayer(\'' . $username . '\',\'' . $upline_row->username . '\')">' . number_format($bonus, 2, '.', ',') . '</a>';
					$data[] = $row;
				}
			}
			$total_query->free_result();
			$upline_total_query_string = "SELECT {$select} FROM {$dbprefix}total_win_loss_report a, {$dbprefix}players b WHERE a.player_id = b.player_id AND b.upline_ids LIKE '%," . $userID . ",%' AND b.upline = '{$username}' $where GROUP BY a.player_id";
			$upline_total_query = $this->db->query($upline_total_query_string);
			$totalFiltered = $upline_total_query->num_rows();
			$upline_total_query->free_result();
			//Output
			$json_data = array(
				"draw"            => intval($this->input->post('draw')), 
				"recordsFiltered" => intval($totalFiltered), 
				"data"            => $data,
				"csrfHash" 		  => $this->security->get_csrf_hash(),
			);
			echo json_encode($json_data); 
			exit();
		}	
    }
	public function winloss_sum_downline_player_total($username = NULL){
    	if(permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)
		{
			$arr = $this->session->userdata('search_report_winloss_sum');
			$dbprefix = $this->db->dbprefix;
			$userData = $this->user_model->get_user_data_by_username($username);
			(!empty($userData)) ? $userID = $userData['user_id'] : $userID = "abc";
			//Declaration Total
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => '',
				'total_data' => '',
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			$json['total_data'] = array(
				"total_deposit" => 0,
				"total_withdrawal" => 0,
				'total_bet' => 0,
				'total_bet_amount' => 0,
				'total_win_loss' => 0,
				'total_rolling_amount' => 0,
				'total_promotion' => 0,
				'total_bonus' => 0,
				'total_player' => 0,
			);
			if(!empty($arr) && !empty($userData)){
				$json['status'] = EXIT_SUCCESS;
				$player_total_query_string = "SELECT * FROM {$dbprefix}players WHERE upline_ids LIKE '%," . $userID . ",%' AND upline = '{$username}' ORDER BY username ASC";
				$player_total_query = $this->db->query($player_total_query_string);
				$total_player = $player_total_query->num_rows();
				$player_total_query->free_result();
				$json['total_data']['total_player'] = $total_player;
				//Get Total Transaction
				if(!empty($json['total_data']['total_player'])){
					$deposit = 0;
					$withdrawal = 0;
					$promotion = 0;
					$bonus = 0;
					$total_win_loss = 0;
					$total_bet = 0;
					$total_bet_amount = 0;
					$total_rolling_amount = 0;
					//Get total
					$where = '';
					if( ! empty($arr['from_date']))
					{
						$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
					}
					if( ! empty($arr['to_date']))
					{
						$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
					}
					$select = "SUM(a.total_bet) AS total_bet, SUM(a.bet_amount) AS total_bet_amount, SUM(a.win_loss) AS total_win_loss, SUM(a.bet_amount_valid) AS total_rolling_amount, SUM(a.deposit_amount) AS total_deposit_amount, SUM(a.withdrawals_amount) AS total_withdrawals_amount, SUM(a.bonus_amount) AS total_bonus_amount, SUM(a.promotion_amount) AS total_promotion_amount";	
					$total_query_string = "SELECT {$select} FROM {$dbprefix}total_win_loss_report a, {$dbprefix}players b WHERE a.player_id = b.player_id AND b.upline_ids LIKE '%," . $userID . ",%' AND b.upline = '{$username}' $where ORDER BY b.username ASC";
					$total_query = $this->db->query($total_query_string);
					if($total_query->num_rows() > 0)
					{
						foreach($total_query->result() as $total_row)
						{
							$json['total_data']['total_deposit'] += $total_row->total_deposit_amount;
							$json['total_data']['total_withdrawal'] += $total_row->total_withdrawals_amount;
							$json['total_data']['total_promotion'] += $total_row->total_promotion_amount;
							$json['total_data']['total_bonus'] += $total_row->total_bonus_amount;
							$json['total_data']['total_win_loss'] += $total_row->total_win_loss;
							$json['total_data']['total_bet'] += $total_row->total_bet;
							$json['total_data']['total_bet_amount'] += $total_row->total_bet_amount;
							$json['total_data']['total_rolling_amount'] += $total_row->total_rolling_amount;
						}
					}
				}
			}
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
    }
    /*************************WIN LOSS PLAYER REPORT*******************************************/
    public function winloss_player(){
    	if(permission_validation(PERMISSION_WIN_LOSS_REPORT_PLAYER) == TRUE)
		{
			$this->save_current_url('report/winloss_player');
			$data = quick_search();
			$data['game_list'] = $this->game_model->get_game_list();
			$data['page_title'] = $this->lang->line('title_win_loss_report_player');
			$data_search = array();
			$this->session->unset_userdata('search_report_winloss_player');
			if($_GET){
				$data_search['username'] = (isset($_GET['username'])?$_GET['username']:'');
			}
			$data['data_search'] = $data_search;
			$this->load->view('winloss_player_report_view', $data);
		}
		else
		{
			redirect('home');
		}
    }
    public function winloss_player_search(){
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT_PLAYER) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			//Set form rules
			$config = array(
				array(
						'field' => 'from_date',
						'label' => strtolower($this->lang->line('label_from_date')),
						'rules' => 'trim|required|callback_date_check',
						'errors' => array(
								'required' => $this->lang->line('error_invalid_datetime_format'),
								'date_check' => $this->lang->line('error_invalid_datetime_format')
						)
				),
				array(
						'field' => 'to_date',
						'label' => strtolower($this->lang->line('label_to_date')),
						'rules' => 'trim|required|callback_date_check',
						'errors' => array(
								'required' => $this->lang->line('error_invalid_datetime_format'),
								'date_check' => $this->lang->line('error_invalid_datetime_format')
						)
				)
			);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$from_date = strtotime(trim($this->input->post('from_date', TRUE)));
				$to_date = strtotime(trim($this->input->post('to_date', TRUE)));
				$days = cal_days_in_month(CAL_GREGORIAN, date('n', $from_date), date('Y', $from_date));
				if(!empty(trim($this->input->post('username', TRUE)))){
					$date_range = (($days+1) * 86400)*6;
				}else{
					$date_range = (($days+1) * 86400)*6;
				}
				$time_diff = ($to_date - $from_date);
				if($time_diff < 0 OR $time_diff > $date_range)
				{
					$json['msg'] = $this->lang->line('error_invalid_month_range');
				}
				else
				{
					$data = array( 
						'from_date' => trim($this->input->post('from_date', TRUE)),
						'to_date' => trim($this->input->post('to_date', TRUE)),
						'username' =>  trim($this->input->post('username', TRUE)),
						'game_provider_code' =>  trim($this->input->post('game_provider_code', TRUE)),
						'game_type_code' =>  trim($this->input->post('game_type_code', TRUE)),
						'agent' => trim($this->input->post('agent', TRUE)),
						'excludeProviderCheckboxes' => $this->input->post('excludeProviderCheckboxes', TRUE),
						'excludeGametypeCheckboxes' => $this->input->post('excludeGametypeCheckboxes', TRUE),
					);
					if(!empty($data['from_date'])){
						$data['from_date'] .= " 00:00:00";
					}
					if(!empty($data['to_date'])){
						$data['to_date'] .= " 23:59:59";
					}
					$this->session->set_userdata('search_report_winloss_player', $data);
					$json['status'] = EXIT_SUCCESS;
				}
			}
			else 
			{
				$error = array(
					'from_date' => form_error('from_date'), 
					'to_date' => form_error('to_date'),
				);
				if( ! empty($error['from_date']))
				{
					$json['msg'] = $error['from_date'];
				}
				else if( ! empty($error['to_date']))
				{
					$json['msg'] = $error['to_date'];
				}
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function winloss_player_listing(){
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT_PLAYER) == TRUE)
		{
			$max_level = 0;
			$level_data = $this->level_model->get_higest_level();
			if(!empty($level_data)){
				$max_level = $level_data['level_number'];
			}
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			$arr = $this->session->userdata('search_report_winloss_player');
			$dbprefix = $this->db->dbprefix;
			$data = array();
			$columns = array( 
				'a.player_id',
				'b.username',
				'b.tag_id',
				'b.level_id',
				'b.bank_account_name',
				'SUM(a.bet_amount) AS total_bet_amount',
				'SUM(a.bet_amount_valid) AS total_rolling_amount',
				'SUM(a.win_loss) AS total_win_loss',
			);
			$columns_sort = array( 
				'a.player_id',
				'b.username',
				'b.tag_id',
				'b.level_id',
				'b.bank_account_name',
				'total_bet_amount',
				'total_rolling_amount',
				'total_win_loss',
			);
			$col = 0;
			$dir = "";
			if( ! empty($order))
			{
				foreach($order as $o)
				{
					$col = $o['column'];
					$dir = $o['dir'];
				}
			}
			if($dir != "asc" && $dir != "desc")
			{
				$dir = "desc";
			}
			if( ! isset($columns_sort[$col]))
			{
				$order = $columns_sort[0];
			}
			else
			{
				$order = $columns_sort[$col];
			}
			//Get total
			$where = '';
			if( ! empty($arr['agent']))
			{
				$where = "AND a.player_id = 'ABC'";
				$agent = $this->user_model->get_user_data_by_username($arr['agent']);
				if(!empty($agent)){
					$response_upline = $this->user_model->get_downline_data($agent['username']);
					if(!empty($response_upline)){
						$where = "AND b.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";
					}
				}
			}else{
				$where = "AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";
			}
			if( ! empty($arr['from_date']))
			{
				$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
			}
			if( ! empty($arr['game_provider_code']))
			{
				$where .= " AND a.game_provider_code = '" . $arr['game_provider_code'] . "'";
			}
			if( ! empty($arr['game_type_code']))
			{
				$where .= " AND a.game_type_code = '" . $arr['game_type_code'] . "'";
			}
			if( ! empty($arr['username']))
			{
				$where .= " AND b.username = '" . $arr['username'] . "'";
			}
			if(!empty($arr['excludeProviderCheckboxes'])){
				$excludeProviderCheckboxes = '"'.implode('","', $arr['excludeProviderCheckboxes']).'"';
				$where .= " AND a.game_provider_code NOT IN(" . $excludeProviderCheckboxes . ")";
			}
			if(!empty($arr['excludeGametypeCheckboxes'])){
				$excludeGametypeCheckboxes = '"'.implode('","', $arr['excludeGametypeCheckboxes']).'"';
				$where .= " AND a.game_type_code NOT IN(" . $excludeGametypeCheckboxes . ")";
			}
			$select = implode(',', $columns);
			$total_query_string = "SELECT {$select} FROM {$dbprefix}win_loss_report a, {$dbprefix}players b WHERE a.player_id = b.player_id $where GROUP BY a.player_id ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$total_query = $this->db->query($total_query_string);
			if($total_query->num_rows() > 0)
			{
				$tag_list = $this->tag_model->get_tag_list();
				foreach($total_query->result() as $upline_row)
				{	
					$rtp = 0;
					$tag = "";
					if(isset($tag_list[$upline_row->tag_id])){
						$tag = '<span class="badge bg-success" id="uc21_' . $upline_row->player_id . '" style="background-color: '.$tag_list[$upline_row->tag_id]['tag_background_color'].' !important;color: '.$tag_list[$upline_row->tag_id]['tag_font_color'].' !important;font-weight: '.(($tag_list[$upline_row->tag_id]['is_bold'] == STATUS_ACTIVE) ? "bold": "normal").' !important;">' . $tag_list[$upline_row->tag_id]['tag_code'] . '</span>';						
					}
					$level = "";
					for($i=1;$i<$max_level;$i++){
						if($upline_row->level_id > $i){
							$level .= '<i class="fas fa-star nav-icon text-warning"></i>';
						}else{
							$level .= '<i class="fas fa-star nav-icon text-gray"></i>';
						}
					}
					if(is_nan($upline_row->total_win_loss / $upline_row->total_rolling_amount * 100)){
						$rtp = 0.00;						
					}else{
						$rtp = number_format($upline_row->total_win_loss / $upline_row->total_rolling_amount * 100, 2, '.', ',');
					}
					$row = array();
					$row[] = $upline_row->player_id;
					$row[] = '<a href="javascript:void(0);" onclick="showGameProvider(\'' . $upline_row->username . '\')">' . $upline_row->username . '</a>';
					$row[] = $tag;
					$row[] = $level;
					$row[] = (( ! empty($upline_row->bank_account_name)) ? $upline_row->bank_account_name : '-');
					$row[] = '<a href="javascript:void(0);" class="text-' . (($upline_row->total_bet_amount > 0) ? 'primary' : 'dark') . '">' . number_format($upline_row->total_bet_amount, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($upline_row->total_rolling_amount > 0) ? 'primary' : 'dark') . '">' . number_format($upline_row->total_rolling_amount, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($upline_row->total_win_loss >= 0) ? ($upline_row->total_win_loss == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($upline_row->total_win_loss, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($upline_row->total_win_loss >= 0) ? ($upline_row->total_win_loss == 0) ? 'dark' : 'primary' : 'danger') . '">' . $rtp . '%</a>';
					$data[] = $row;
				}
			}
			$total_query->free_result();
			$upline_total_query_string = "SELECT {$select} FROM {$dbprefix}win_loss_report a, {$dbprefix}players b WHERE a.player_id = b.player_id $where GROUP BY a.player_id";
			$upline_total_query = $this->db->query($upline_total_query_string);
			$totalFiltered = $upline_total_query->num_rows();
			$upline_total_query->free_result();
			//Output
			$json_data = array(
				"draw"            => intval($this->input->post('draw')), 
				"recordsFiltered" => intval($totalFiltered), 
				"data"            => $data,
				"csrfHash" 		  => $this->security->get_csrf_hash(),
			);
			echo json_encode($json_data); 
			exit();
		}
	}
	public function winloss_player_total(){
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT_PLAYER) == TRUE)
		{
			$arr = $this->session->userdata('search_report_winloss_player');
			$dbprefix = $this->db->dbprefix;
			//Declaration Total
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => '',
				'total_data' => '',
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			$json['total_data'] = array(
				'total_bet_amount' => 0,
				'total_win_loss' => 0,
				'total_rolling_amount' => 0,
				'total_rtp' => 0,
				'total_downline' => 0,
			);
			if(!empty($arr)){
				$json['status'] = EXIT_SUCCESS;
				$data = array();
				//Get total
				$where = '';
				if( ! empty($arr['agent']))
				{
					$where = "AND a.player_id = 'ABC'";
					$agent = $this->user_model->get_user_data_by_username($arr['agent']);
					if(!empty($agent)){
						$response_upline = $this->user_model->get_downline_data($agent['username']);
						if(!empty($response_upline)){
							$where = "AND b.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";
						}
					}
				}else{
					$where = "AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";
				}
				if( ! empty($arr['from_date']))
				{
					$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
				}
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
				}
				if( ! empty($arr['game_provider_code']))
				{
					$where .= " AND a.game_provider_code = '" . $arr['game_provider_code'] . "'";
				}
				if( ! empty($arr['game_type_code']))
				{
					$where .= " AND a.game_type_code = '" . $arr['game_type_code'] . "'";
				}
				if( ! empty($arr['username']))
				{
					$where .= " AND b.username = '" . $arr['username'] . "'";
				}
				if(!empty($arr['excludeProviderCheckboxes'])){
					$excludeProviderCheckboxes = '"'.implode('","', $arr['excludeProviderCheckboxes']).'"';
					$where .= " AND a.game_provider_code NOT IN(" . $excludeProviderCheckboxes . ")";
				}
				if(!empty($arr['excludeGametypeCheckboxes'])){
					$excludeGametypeCheckboxes = '"'.implode('","', $arr['excludeGametypeCheckboxes']).'"';
					$where .= " AND a.game_type_code NOT IN(" . $excludeGametypeCheckboxes . ")";
				}
				$select = "SUM(a.total_bet) AS total_bet, SUM(a.bet_amount) AS total_bet_amount, SUM(a.bet_amount_valid) AS total_rolling_amount, SUM(a.win_loss) AS total_win_loss";
				$total_query_string = "SELECT {$select} FROM {$dbprefix}win_loss_report a, {$dbprefix}players b WHERE a.player_id = b.player_id $where";
				$total_query = $this->db->query($total_query_string);
				if($total_query->num_rows() > 0)
				{
					$json['total_data']['total_downline'] = $total_query->num_rows();
					foreach($total_query->result() as $upline_row)
					{	
						if(!empty($upline_row->total_bet)){
							if(is_nan($upline_row->total_win_loss / $upline_row->total_rolling_amount * 100)){
								$rtp = 0;						
							}else{
								$rtp = $upline_row->total_win_loss / $upline_row->total_rolling_amount * 100;
							}
							$json['total_data']['total_bet_amount'] = $upline_row->total_bet_amount;
							$json['total_data']['total_rolling_amount'] = $upline_row->total_rolling_amount;
							$json['total_data']['total_win_loss'] = $upline_row->total_win_loss;
							$json['total_data']['total_rtp'] = $rtp;
						}
					}
				}
				$total_query->free_result();
			}
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function winloss_player_game_provider($username = NULL){
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT_PLAYER) == TRUE)
		{
			$arr = $this->session->userdata('search_report_winloss_player');
	    	if(!empty($arr)){
	    		$this->session->unset_userdata('search_report_winloss_player_game_provider');
	    		$data = array( 
					'from_date' => $arr['from_date'],
					'to_date' => $arr['to_date'],
					'username' => $username,	
					'game_provider_code' => $arr['game_provider_code'],
					'game_type_code' => $arr['game_type_code'],
					'agent' => $arr['agent'],
					'excludeProviderCheckboxes' => $arr['excludeProviderCheckboxes'],
					'excludeGametypeCheckboxes' => $arr['excludeGametypeCheckboxes'],
				);
				$this->session->set_userdata('search_report_winloss_player_game_provider', $data);
				$this->load->view('winloss_player_game_provider_table', $data);
	    	}else{
	    		redirect('home');
	    	}
		}
	}
	public function winloss_player_game_provider_listing(){
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT_PLAYER) == TRUE)
		{
			$max_level = 0;
			$level_data = $this->level_model->get_higest_level();
			if(!empty($level_data)){
				$max_level = $level_data['level_number'];
			}
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			$columns = array( 
				'a.player_id',
				'b.username',
				'b.tag_id',
				'b.level_id',
				'b.bank_account_name',
				'SUM(a.total_bet) AS total_bet',
				'a.game_provider_code',
				'a.game_type_code',
				'SUM(a.bet_amount) AS total_bet_amount',
				'SUM(a.bet_amount_valid) AS total_rolling_amount',
				'SUM(a.win_loss) AS total_win_loss',
			);
			$columns_sort = array( 
				'a.player_id',
				'b.username',
				'b.tag_id',
				'b.level_id',
				'b.bank_account_name',
				'total_bet',
				'a.game_provider_code',
				'a.game_type_code',
				'total_bet_amount',
				'total_rolling_amount',
				'total_win_loss',
			);
			$arr = $this->session->userdata('search_report_winloss_player_game_provider');
			$dbprefix = $this->db->dbprefix;
			$data = array();
			$col = 0;
			$dir = "";
			if( ! empty($order))
			{
				foreach($order as $o)
				{
					$col = $o['column'];
					$dir = $o['dir'];
				}
			}
			if($dir != "asc" && $dir != "desc")
			{
				$dir = "desc";
			}
			if( ! isset($columns_sort[$col]))
			{
				$order = $columns_sort[0];
			}
			else
			{
				$order = $columns_sort[$col];
			}
			//Get total
			$where = '';
			if( ! empty($arr['agent']))
			{
				$where = "AND a.player_id = 'ABC'";
				$agent = $this->user_model->get_user_data_by_username($arr['agent']);
				if(!empty($agent)){
					$response_upline = $this->user_model->get_downline_data($agent['username']);
					if(!empty($response_upline)){
						$where = "AND b.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";
					}
				}
			}else{
				$where = "AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";
			}
			if( ! empty($arr['from_date']))
			{
				$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
			}
			if( ! empty($arr['game_provider_code']))
			{
				$where .= " AND a.game_provider_code = '" . $arr['game_provider_code'] . "'";
			}
			if( ! empty($arr['game_type_code']))
			{
				$where .= " AND a.game_type_code = '" . $arr['game_type_code'] . "'";
			}
			if( ! empty($arr['username']))
			{
				$where .= " AND b.username = '" . $arr['username'] . "'";
			}
			if(!empty($arr['excludeProviderCheckboxes'])){
				$excludeProviderCheckboxes = '"'.implode('","', $arr['excludeProviderCheckboxes']).'"';
				$where .= " AND a.game_provider_code NOT IN(" . $excludeProviderCheckboxes . ")";
			}
			if(!empty($arr['excludeGametypeCheckboxes'])){
				$excludeGametypeCheckboxes = '"'.implode('","', $arr['excludeGametypeCheckboxes']).'"';
				$where .= " AND a.game_type_code NOT IN(" . $excludeGametypeCheckboxes . ")";
			}
			$select = implode(',', $columns);
			$total_query_string = "SELECT {$select} FROM {$dbprefix}win_loss_report a, {$dbprefix}players b WHERE a.player_id = b.player_id $where GROUP BY a.player_id, a.game_provider_code, a.game_type_code ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$total_query = $this->db->query($total_query_string);
			if($total_query->num_rows() > 0)
			{
				$tag_list = $this->tag_model->get_tag_list();
				foreach($total_query->result() as $upline_row)
				{	
					$rtp = 0;
					$level = "";
					for($i=1;$i<$max_level;$i++){
						if($upline_row->level_id > $i){
							$level .= '<i class="fas fa-star nav-icon text-warning"></i>';
						}else{
							$level .= '<i class="fas fa-star nav-icon text-gray"></i>';
						}
					}
					if(is_nan($upline_row->total_win_loss / $upline_row->total_rolling_amount * 100)){
						$rtp = 0.00;						
					}else{
						$rtp = number_format($upline_row->total_win_loss / $upline_row->total_rolling_amount * 100, 2, '.', ',');
					}
					$tag = "";
					if(isset($tag_list[$upline_row->tag_id])){
						$tag = '<span class="badge bg-success" id="uc21_' . $upline_row->player_id . '" style="background-color: '.$tag_list[$upline_row->tag_id]['tag_background_color'].' !important;color: '.$tag_list[$upline_row->tag_id]['tag_font_color'].' !important;font-weight: '.(($tag_list[$upline_row->tag_id]['is_bold'] == STATUS_ACTIVE) ? "bold": "normal").' !important;">' . $tag_list[$upline_row->tag_id]['tag_code'] . '</span>';						
					}
					$row = array();
					$row[] = $upline_row->player_id;
					$row[] = '<a href="javascript:void(0);" onclick="showGameProvider(\'' . $upline_row->username . '\')">' . $upline_row->username . '</a>';
					$row[] = $tag;
					$row[] = $level;
					$row[] = (( ! empty($upline_row->bank_account_name)) ? $upline_row->bank_account_name : '-');
					$row[] = '<a href="javascript:void(0);"  onclick="showGameProviderDaily(\'' . $upline_row->username . '\',\'' . $upline_row->game_provider_code . '\',\'' . $upline_row->game_type_code . '\')" class="text-' . (($upline_row->total_bet > 0) ? 'warning' : 'dark') . '">' . number_format($upline_row->total_bet, 0, '.', ',') . '</a>';
					$row[] = $this->lang->line('game_' . strtolower($upline_row->game_provider_code));
					$row[] = $this->lang->line(get_game_type($upline_row->game_type_code));
					$row[] = '<a href="javascript:void(0);" class="text-' . (($upline_row->total_bet_amount > 0) ? 'primary' : 'dark') . '">' . number_format($upline_row->total_bet_amount, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($upline_row->total_rolling_amount > 0) ? 'primary' : 'dark') . '">' . number_format($upline_row->total_rolling_amount, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($upline_row->total_win_loss >= 0) ? ($upline_row->total_win_loss == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($upline_row->total_win_loss, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($upline_row->total_win_loss >= 0) ? ($upline_row->total_win_loss == 0) ? 'dark' : 'primary' : 'danger') . '">' . $rtp . '%</a>';
					$data[] = $row;
				}
			}
			$total_query->free_result();
			$upline_total_query_string = "SELECT {$select} FROM {$dbprefix}win_loss_report a, {$dbprefix}players b WHERE a.player_id = b.player_id $where GROUP BY a.player_id, a.game_provider_code, a.game_type_code";
			$upline_total_query = $this->db->query($upline_total_query_string);
			$totalFiltered = $upline_total_query->num_rows();
			$upline_total_query->free_result();
			$total_data = array(
				'total_bet' => 0,
				'total_bet_amount' => 0,
				'total_rolling_amount' => 0,
				'total_win_loss' => 0,
				'total_rtp' => 0,
			);
			$select = "SUM(a.total_bet) AS total_bet, SUM(a.bet_amount) AS total_bet_amount, SUM(a.bet_amount_valid) AS total_rolling_amount, SUM(a.win_loss) AS total_win_loss";
			$total_query_string = "SELECT {$select} FROM {$dbprefix}win_loss_report a, {$dbprefix}players b WHERE a.player_id = b.player_id $where";
			$total_query = $this->db->query($total_query_string);
			if($total_query->num_rows() > 0)
			{
				foreach($total_query->result() as $row)
				{	
					if(!empty($row->total_bet)){
						if(is_nan($row->total_win_loss / $row->total_rolling_amount * 100)){
							$rtp = 0;						
						}else{
							$rtp = $row->total_win_loss / $row->total_rolling_amount * 100;
						}
						$total_data = array(
							'total_bet' => $row->total_bet,
							'total_bet_amount' => $row->total_bet_amount,
							'total_rolling_amount' => $row->total_rolling_amount,
							'total_win_loss' => $row->total_win_loss,
							'total_rtp' => $rtp,
						);
					}
				}
			}
			$total_query->free_result();
			//Output
			$json_data = array(
				"draw"            => intval($this->input->post('draw')), 
				"recordsFiltered" => intval($totalFiltered), 
				"data"            => $data,
				"total_data"      => $total_data,
				"csrfHash" 		  => $this->security->get_csrf_hash(),
			);
			echo json_encode($json_data); 
			exit();
		}
	}
	public function winloss_player_game_provider_daily($username = NULL, $game_provider_code = NULL, $game_type_code = NULL){
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT_PLAYER) == TRUE)
		{
			$arr = $this->session->userdata('search_report_winloss_player');
	    	if(!empty($arr)){
	    		$this->session->unset_userdata('search_report_winloss_player_game_provider_daily');
	    		$data = array( 
					'from_date' => $arr['from_date'],
					'to_date' => $arr['to_date'],
					'username' => $username,
					'game_provider_code' => $game_provider_code,
					'game_type_code' => $game_type_code,
					'agent' => $arr['agent'],
					'excludeProviderCheckboxes' => $arr['excludeProviderCheckboxes'],
					'excludeGametypeCheckboxes' => $arr['excludeGametypeCheckboxes'],
				);
				$this->session->set_userdata('search_report_winloss_player_game_provider_daily', $data);
				$this->load->view('winloss_player_game_provider_daily_table', $data);
	    	}else{
	    		redirect('home');
	    	}
		}
	}
	public function winloss_player_game_provider_daily_listing(){
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT_PLAYER) == TRUE)
		{
			$max_level = 0;
			$level_data = $this->level_model->get_higest_level();
			if(!empty($level_data)){
				$max_level = $level_data['level_number'];
			}
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			$columns = array( 
				'a.player_id',
				'b.username',
				'b.tag_id',
				'b.mark',
				'b.level_id',
				'b.bank_account_name',
				'a.total_bet',
				'a.game_provider_code',
				'a.game_type_code',
				'a.report_date',
				'a.bet_amount',
				'a.bet_amount_valid',
				'a.win_loss',
			);
			$arr = $this->session->userdata('search_report_winloss_player_game_provider_daily');
			$dbprefix = $this->db->dbprefix;
			$data = array();
			$col = 0;
			$dir = "";
			if( ! empty($order))
			{
				foreach($order as $o)
				{
					$col = $o['column'];
					$dir = $o['dir'];
				}
			}
			if($dir != "asc" && $dir != "desc")
			{
				$dir = "desc";
			}
			if( ! isset($columns[$col]))
			{
				$order = $columns[0];
			}
			else
			{
				$order = $columns[$col];
			}
			//Get total
			$where = '';
			if( ! empty($arr['agent']))
			{
				$where = "AND a.player_id = 'ABC'";
				$agent = $this->user_model->get_user_data_by_username($arr['agent']);
				if(!empty($agent)){
					$response_upline = $this->user_model->get_downline_data($agent['username']);
					if(!empty($response_upline)){
						$where = "AND b.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";
					}
				}
			}else{
				$where = "AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";
			}
			if( ! empty($arr['from_date']))
			{
				$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
			}
			if( ! empty($arr['game_provider_code']))
			{
				$where .= " AND a.game_provider_code = '" . $arr['game_provider_code'] . "'";
			}
			if( ! empty($arr['game_type_code']))
			{
				$where .= " AND a.game_type_code = '" . $arr['game_type_code'] . "'";
			}
			if( ! empty($arr['username']))
			{
				$where .= " AND b.username = '" . $arr['username'] . "'";
			}
			if(!empty($arr['excludeProviderCheckboxes'])){
				$excludeProviderCheckboxes = '"'.implode('","', $arr['excludeProviderCheckboxes']).'"';
				$where .= " AND a.game_provider_code NOT IN(" . $excludeProviderCheckboxes . ")";
			}
			if(!empty($arr['excludeGametypeCheckboxes'])){
				$excludeGametypeCheckboxes = '"'.implode('","', $arr['excludeGametypeCheckboxes']).'"';
				$where .= " AND a.game_type_code NOT IN(" . $excludeGametypeCheckboxes . ")";
			}
			$select = implode(',', $columns);
			$total_query_string = "SELECT {$select} FROM {$dbprefix}win_loss_report a, {$dbprefix}players b WHERE a.player_id = b.player_id $where ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$total_query = $this->db->query($total_query_string);
			if($total_query->num_rows() > 0)
			{
				$tag_list = $this->tag_model->get_tag_list();
				foreach($total_query->result() as $upline_row)
				{	
					$rtp = 0;
					$level = "";
					for($i=1;$i<$max_level;$i++){
						if($upline_row->level_id > $i){
							$level .= '<i class="fas fa-star nav-icon text-warning"></i>';
						}else{
							$level .= '<i class="fas fa-star nav-icon text-gray"></i>';
						}
					}
					if(is_nan($upline_row->total_win_loss / $upline_row->total_rolling_amount * 100)){
						$rtp = 0.00;						
					}else{
						$rtp = number_format($upline_row->total_win_loss / $upline_row->total_rolling_amount * 100, 2, '.', ',');
					}
					$tag = "";
					if(isset($tag_list[$upline_row->tag_id])){
						$tag = '<span class="badge bg-success" id="uc21_' . $upline_row->player_id . '" style="background-color: '.$tag_list[$upline_row->tag_id]['tag_background_color'].' !important;color: '.$tag_list[$upline_row->tag_id]['tag_font_color'].' !important;font-weight: '.(($tag_list[$upline_row->tag_id]['is_bold'] == STATUS_ACTIVE) ? "bold": "normal").' !important;">' . $tag_list[$upline_row->tag_id]['tag_code'] . '</span>';						
					}
					$row = array();
					$row[] = $upline_row->player_id;
					$row[] = '<a href="javascript:void(0);" onclick="showGameProvider(\'' . $upline_row->username . '\')">' . $upline_row->username . '</a>';
					$row[] = $tag;
					$row[] = $level;
					$row[] = (( ! empty($upline_row->bank_account_name)) ? $upline_row->bank_account_name : '-');
					$row[] = '<a href="javascript:void(0);"  onclick="showGameProviderDaily(\'' . $upline_row->username . '\',\'' . $upline_row->game_provider_code . '\',\'' . $upline_row->game_type_code . '\')" class="text-' . (($upline_row->total_bet > 0) ? 'primary' : 'dark') . '">' . number_format($upline_row->total_bet, 0, '.', ',') . '</a>';
					$row[] = $this->lang->line('game_' . strtolower($upline_row->game_provider_code));
					$row[] = $this->lang->line(get_game_type($upline_row->game_type_code));
					$row[] = (($upline_row->report_date > 0) ? date('Y-m-d', $upline_row->report_date) : '-');
					$row[] = '<a href="javascript:void(0);" class="text-' . (($upline_row->bet_amount > 0) ? 'primary' : 'dark') . '">' . number_format($upline_row->bet_amount, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($upline_row->bet_amount_valid > 0) ? 'primary' : 'dark') . '">' . number_format($upline_row->bet_amount_valid, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($upline_row->win_loss >= 0) ? ($upline_row->win_loss == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($upline_row->win_loss, 2, '.', ',') . '</a>';
					$row[] = '<a href="javascript:void(0);" class="text-' . (($upline_row->win_loss >= 0) ? ($upline_row->win_loss == 0) ? 'dark' : 'primary' : 'danger') . '">' . $rtp . '%</a>';
					$data[] = $row;
				}
			}
			$total_query->free_result();
			$upline_total_query_string = "SELECT {$select} FROM {$dbprefix}win_loss_report a, {$dbprefix}players b WHERE a.player_id = b.player_id $where";
			$upline_total_query = $this->db->query($upline_total_query_string);
			$totalFiltered = $upline_total_query->num_rows();
			$upline_total_query->free_result();
			$total_data = array(
				'total_bet' => 0,
				'total_bet_amount' => 0,
				'total_rolling_amount' => 0,
				'total_win_loss' => 0,
				'total_rtp' => 0,
			);
			$select = "SUM(a.total_bet) AS total_bet, SUM(a.bet_amount) AS total_bet_amount, SUM(a.bet_amount_valid) AS total_rolling_amount, SUM(a.win_loss) AS total_win_loss";
			$total_query_string = "SELECT {$select} FROM {$dbprefix}win_loss_report a, {$dbprefix}players b WHERE a.player_id = b.player_id $where";
			$total_query = $this->db->query($total_query_string);
			if($total_query->num_rows() > 0)
			{
				foreach($total_query->result() as $row)
				{	
					if(!empty($row->total_bet)){
						if(is_nan($row->total_win_loss / $row->total_rolling_amount * 100)){
							$rtp = 0;						
						}else{
							$rtp = $row->total_win_loss / $row->total_rolling_amount * 100;
						}
						$total_data = array(
							'total_bet' => $row->total_bet,
							'total_bet_amount' => $row->total_bet_amount,
							'total_rolling_amount' => $row->total_rolling_amount,
							'total_win_loss' => $row->total_win_loss,
							'total_rtp' => $rtp,
						);
					}
				}
			}
			$total_query->free_result();
			//Output
			$json_data = array(
				"draw"            => intval($this->input->post('draw')), 
				"recordsFiltered" => intval($totalFiltered), 
				"data"            => $data,
				"total_data"      => $total_data,
				"csrfHash" 		  => $this->security->get_csrf_hash(),
			);
			echo json_encode($json_data); 
			exit();
		}
	}
	/*************************PLAYER YEARLY REPORT*******************************************/
	public function yearly_report()
	{
		if(permission_validation(PERMISSION_YEARLY_REPORT) == TRUE)
		{
			$this->save_current_url('report/monthly_report');
			$data['page_title'] = $this->lang->line('title_yearly_report');
			$this->session->unset_userdata('search_report_yearly');
			$this->load->view('yearly_report_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function yearly_report_search()
	{
		if(permission_validation(PERMISSION_YEARLY_REPORT) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			//Set form rules
			$config = array(
				array(
					'field' => 'from_date',
					'label' => strtolower($this->lang->line('label_from_date')),
					'rules' => 'trim|required|callback_year_check',
					'errors' => array(
							'required' => $this->lang->line('error_invalid_datetime_format'),
							'year_check' => $this->lang->line('error_invalid_datetime_format')
					)
				),
				array(
					'field' => 'type',
					'label' => strtolower($this->lang->line('label_type')),
					'rules' => 'trim|required',
					'errors' => array(
							'required' => $this->lang->line('error_select_type'),
					)
				)
			);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$data = array( 
					'from_date' => trim($this->input->post('from_date', TRUE)),
					'type' => trim($this->input->post('type', TRUE)),
					'username' => trim($this->input->post('username', TRUE)),
					'upline' => trim($this->input->post('upline', TRUE)),
				);
				$this->session->set_userdata('search_report_yearly', $data);
				$json['status'] = EXIT_SUCCESS;
			}
			else 
			{
				$error = array(
					'from_date' => form_error('from_date'),
				);
				if( ! empty($error['from_date']))
				{
					$json['msg'] = $error['from_date'];
				}
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function yearly_report_listing()
    {
		if(permission_validation(PERMISSION_YEARLY_REPORT) == TRUE)
		{
			$max_level = 0;
			$level_data = $this->level_model->get_higest_level();
			if(!empty($level_data)){
				$max_level = $level_data['level_number'];
			}
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			$dbprefix = $this->db->dbprefix;
			$where = "";
			$arr = $this->session->userdata('search_report_yearly');
			if( ! empty($arr['from_date'])){
				if( ! empty($arr['username']))
				{
					$where .= " AND P.username = '" . $arr['username'] . "'";	
				}
				if( ! empty($arr['upline']))
				{
					$where .= " AND P.upline = '" . $arr['upline'] . "'";	
				}
				if($arr['type'] == YEARLY_REPORT_SETTING_DEPOSIT){
					$type = "deposit_amount";
				}else if($arr['type'] == YEARLY_REPORT_SETTING_WITHDRAWAL){
					$type = "withdrawals_amount";
				}else if($arr['type'] == YEARLY_REPORT_SETTING_PROMOTION){
					$type = "promotion_amount";
				}else if($arr['type'] == YEARLY_REPORT_SETTING_TURNOVER){
					$type = "bet_amount_valid";
				}else{
					$type = "win_loss";
				}
				$jan_datetime = strtotime($arr['from_date']."-01-01");
				$feb_datetime = strtotime($arr['from_date']."-02-01");
				$mar_datetime = strtotime($arr['from_date']."-03-01");
				$apr_datetime = strtotime($arr['from_date']."-04-01");
				$may_datetime = strtotime($arr['from_date']."-05-01");
				$jun_datetime = strtotime($arr['from_date']."-06-01");
				$jul_datetime = strtotime($arr['from_date']."-07-01");
				$aug_datetime = strtotime($arr['from_date']."-08-01");
				$sep_datetime = strtotime($arr['from_date']."-09-01");
				$oct_datetime = strtotime($arr['from_date']."-10-01");
				$nov_datetime = strtotime($arr['from_date']."-11-01");
				$dec_datetime = strtotime($arr['from_date']."-12-01");
				$where_total_jan = "";
				$where_total_feb = "";
				$where_total_mar = "";
				$where_total_apr = "";
				$where_total_may = "";
				$where_total_jun = "";
				$where_total_jul = "";
				$where_total_aug = "";
				$where_total_sep = "";
				$where_total_oct = "";
				$where_total_nov = "";
				$where_total_dec = "";
				$where_total_total = "";
				$columns = array( 
					'P.player_id',
					'P.username',
					'P.level_id',
					'value_jan',
					'value_feb',
					'value_mar',
					'value_apr',
					'value_may',
					'value_jun',
					'value_jul',
					'value_aug',
					'value_sep',
					'value_oct',
					'value_nov',
					'value_dec',
					'value_total',
				);
				$col = 0;
				$dir = "";
				if( ! empty($order))
				{
					foreach($order as $o)
					{
						$col = $o['column'];
						$dir = $o['dir'];
					}
				}
				if($dir != "asc" && $dir != "desc")
				{
					$dir = "desc";
				}
				if( ! isset($columns[$col]))
				{
					$order = $columns[0];
				}
				else
				{
					$order = $columns[$col];
				}
				$query_string = "SELECT P.player_id,P.username,P.level_id,COALESCE(B.value_jan,0) as value_jan,COALESCE(C.value_feb,0) as value_feb,COALESCE(D.value_mar,0) as value_mar,COALESCE(E.value_apr,0) as value_apr,COALESCE(F.value_may,0) as value_may,COALESCE(G.value_jun,0) as value_jun,COALESCE(H.value_jul,0) as value_jul,COALESCE(I.value_aug,0) as value_aug,COALESCE(J.value_sep,0) as value_sep,COALESCE(K.value_oct,0) as value_oct,COALESCE(L.value_nov,0) as value_nov,COALESCE(M.value_dec,0) as value_dec,COALESCE(N.value_total,0) as value_total";
				$query_string .= " FROM {$dbprefix}players P";
				if( ! empty($arr['from_date']))
				{
					$where_total_jan .= 'report_date = ' . $jan_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_jan FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_jan)B ON P.player_id = B.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_feb .= 'report_date = ' . $feb_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_feb FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_feb)C ON P.player_id = C.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_mar .= 'report_date = ' . $mar_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_mar FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_mar)D ON P.player_id = D.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_apr .= 'report_date = ' . $apr_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_apr FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_apr)E ON P.player_id = E.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_may .= 'report_date = ' . $may_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_may FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_may)F ON P.player_id = F.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_jun .= 'report_date = ' . $jun_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_jun FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_jun)G ON P.player_id = G.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_jul .= 'report_date = ' . $jul_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_jul FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_jul)H ON P.player_id = H.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_aug .= 'report_date = ' . $aug_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_aug FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_aug)I ON P.player_id = I.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_sep .= 'report_date = ' . $sep_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_sep FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_sep)J ON P.player_id = J.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_oct .= 'report_date = ' . $oct_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_oct FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_oct)K ON P.player_id = K.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_nov .= 'report_date = ' . $nov_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_nov FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_nov)L ON P.player_id = L.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_dec .= 'report_date = ' . $dec_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_dec FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_dec)M ON P.player_id = M.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_total .= 'report_date >= ' . $jan_datetime;
					$where_total_total .= ' AND report_date <= ' . $dec_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, sum($type) as value_total FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_total GROUP BY player_id)N ON P.player_id = N.player_id";
				$query_string .= " WHERE P.upline_ids LIKE '%,1,%' ESCAPE '!' $where ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
				$query = $this->db->query($query_string);
				$posts = NULL;
				if($query->num_rows() > 0)
				{
					$posts = $query->result();  
				}
				$data = array();
				if(!empty($posts))
				{
					foreach ($posts as $post)
					{
						$level = "";
						for($i=1;$i<$max_level;$i++){
							if($post->level_id > $i){
								$level .= '<i class="fas fa-star nav-icon text-warning"></i>';
							}else{
								$level .= '<i class="fas fa-star nav-icon text-gray"></i>';
							}
						}
						$row = array();
						$row[] = $post->player_id;
						$row[] = $post->username;
						$row[] = $level;
						$row[] = '<span class="text-' . (($post->value_jan >= 0) ? ($post->value_jan == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($post->value_jan, 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($post->value_feb >= 0) ? ($post->value_feb == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($post->value_feb, 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($post->value_mar >= 0) ? ($post->value_mar == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($post->value_mar, 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($post->value_apr >= 0) ? ($post->value_apr == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($post->value_apr, 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($post->value_may >= 0) ? ($post->value_may == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($post->value_may, 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($post->value_jun >= 0) ? ($post->value_jun == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($post->value_jun, 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($post->value_jul >= 0) ? ($post->value_jul == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($post->value_jul, 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($post->value_aug >= 0) ? ($post->value_aug == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($post->value_aug, 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($post->value_sep >= 0) ? ($post->value_sep == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($post->value_sep, 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($post->value_oct >= 0) ? ($post->value_oct == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($post->value_oct, 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($post->value_nov >= 0) ? ($post->value_nov == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($post->value_nov, 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($post->value_dec >= 0) ? ($post->value_dec == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($post->value_dec, 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($post->value_total >= 0) ? ($post->value_total == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($post->value_total, 2, '.', ',') . '</span>';
						$data[] = $row;
					}
				}
				$query->free_result();
				$query_total_string = "SELECT P.player_id FROM {$dbprefix}players P";
				$query_total_string .= " WHERE P.upline_ids LIKE '%,1,%' ESCAPE '!' $where";
				$query = $this->db->query($query_total_string);
				$totalFiltered = $query->num_rows();
				$query->free_result();
				//Output
				$json_data = array(
					"draw"            => intval($this->input->post('draw')),
					"recordsFiltered" => intval($totalFiltered), 
					"data"            => $data,
					//"total_data"      => $total_data,
					"csrfHash" 		  => $this->security->get_csrf_hash()					
				);
				echo json_encode($json_data); 
				exit();
			}
		}	
    }
    public function yearly_report_total(){
    	if(permission_validation(PERMISSION_YEARLY_REPORT) == TRUE)
		{
			$dbprefix = $this->db->dbprefix;
			$where = "";
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => '',
				'total_data' => '',
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			$json['total_data'] = array(
				'value_jan' => 0,
				'value_feb' => 0,
				'value_mar' => 0,
				'value_apr' => 0,
				'value_may' => 0,
				'value_jun' => 0,
				'value_jul' => 0,
				'value_aug' => 0,
				'value_sep' => 0,
				'value_oct' => 0,
				'value_nov' => 0,
				'value_dec' => 0,
				'value_total' => 0,
				'total_downline' => 0,
			);
			$arr = $this->session->userdata('search_report_yearly');
			if( ! empty($arr['from_date'])){
				$json['status'] = EXIT_SUCCESS;
				if( ! empty($arr['username']))
				{
					$where .= " AND P.username = '" . $arr['username'] . "'";	
				}
				if( ! empty($arr['upline']))
				{
					$where .= " AND P.upline = '" . $arr['upline'] . "'";	
				}
				if($arr['type'] == YEARLY_REPORT_SETTING_DEPOSIT){
					$type = "deposit_amount";
				}else if($arr['type'] == YEARLY_REPORT_SETTING_WITHDRAWAL){
					$type = "withdrawals_amount";
				}else if($arr['type'] == YEARLY_REPORT_SETTING_PROMOTION){
					$type = "promotion_amount";
				}else if($arr['type'] == YEARLY_REPORT_SETTING_TURNOVER){
					$type = "bet_amount_valid";
				}else{
					$type = "win_loss";
				}
				$jan_datetime = strtotime($arr['from_date']."-01-01");
				$feb_datetime = strtotime($arr['from_date']."-02-01");
				$mar_datetime = strtotime($arr['from_date']."-03-01");
				$apr_datetime = strtotime($arr['from_date']."-04-01");
				$may_datetime = strtotime($arr['from_date']."-05-01");
				$jun_datetime = strtotime($arr['from_date']."-06-01");
				$jul_datetime = strtotime($arr['from_date']."-07-01");
				$aug_datetime = strtotime($arr['from_date']."-08-01");
				$sep_datetime = strtotime($arr['from_date']."-09-01");
				$oct_datetime = strtotime($arr['from_date']."-10-01");
				$nov_datetime = strtotime($arr['from_date']."-11-01");
				$dec_datetime = strtotime($arr['from_date']."-12-01");
				$where_total_jan = "";
				$where_total_feb = "";
				$where_total_mar = "";
				$where_total_apr = "";
				$where_total_may = "";
				$where_total_jun = "";
				$where_total_jul = "";
				$where_total_aug = "";
				$where_total_sep = "";
				$where_total_oct = "";
				$where_total_nov = "";
				$where_total_dec = "";
				$where_total_total = "";
				$query_string = "SELECT P.player_id,P.username,P.level_id,COALESCE(B.value_jan,0) as value_jan,COALESCE(C.value_feb,0) as value_feb,COALESCE(D.value_mar,0) as value_mar,COALESCE(E.value_apr,0) as value_apr,COALESCE(F.value_may,0) as value_may,COALESCE(G.value_jun,0) as value_jun,COALESCE(H.value_jul,0) as value_jul,COALESCE(I.value_aug,0) as value_aug,COALESCE(J.value_sep,0) as value_sep,COALESCE(K.value_oct,0) as value_oct,COALESCE(L.value_nov,0) as value_nov,COALESCE(M.value_dec,0) as value_dec,COALESCE(N.value_total,0) as value_total";
				$query_string .= " FROM {$dbprefix}players P";
				if( ! empty($arr['from_date']))
				{
					$where_total_jan .= 'report_date = ' . $jan_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_jan FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_jan)B ON P.player_id = B.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_feb .= 'report_date = ' . $feb_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_feb FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_feb)C ON P.player_id = C.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_mar .= 'report_date = ' . $mar_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_mar FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_mar)D ON P.player_id = D.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_apr .= 'report_date = ' . $apr_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_apr FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_apr)E ON P.player_id = E.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_may .= 'report_date = ' . $may_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_may FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_may)F ON P.player_id = F.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_jun .= 'report_date = ' . $jun_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_jun FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_jun)G ON P.player_id = G.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_jul .= 'report_date = ' . $jul_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_jul FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_jul)H ON P.player_id = H.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_aug .= 'report_date = ' . $aug_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_aug FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_aug)I ON P.player_id = I.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_sep .= 'report_date = ' . $sep_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_sep FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_sep)J ON P.player_id = J.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_oct .= 'report_date = ' . $oct_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_oct FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_oct)K ON P.player_id = K.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_nov .= 'report_date = ' . $nov_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_nov FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_nov)L ON P.player_id = L.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_dec .= 'report_date = ' . $dec_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_dec FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_dec)M ON P.player_id = M.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_total .= 'report_date >= ' . $jan_datetime;
					$where_total_total .= ' AND report_date <= ' . $dec_datetime;
				}
				$query_string .= " LEFT OUTER JOIN( SELECT player_id, sum($type) as value_total FROM {$dbprefix}total_win_loss_report_month WHERE $where_total_total GROUP BY player_id)N ON P.player_id = N.player_id";
				$query_string .= " WHERE P.upline_ids LIKE '%,1,%' ESCAPE '!' $where ";
				$query = $this->db->query($query_string);
				$posts = NULL;
				$json['total_data']['total_downline'] = $query->num_rows();
				if($query->num_rows() > 0)
				{
					$posts = $query->result();  
				}
				$data = array();
				if(!empty($posts))
				{
					foreach ($posts as $post)
					{
						$json['total_data']['value_jan'] += $post->value_jan;
						$json['total_data']['value_feb'] += $post->value_feb;
						$json['total_data']['value_mar'] += $post->value_mar;
						$json['total_data']['value_apr'] += $post->value_apr;
						$json['total_data']['value_may'] += $post->value_may;
						$json['total_data']['value_jun'] += $post->value_jun;
						$json['total_data']['value_jul'] += $post->value_jul;
						$json['total_data']['value_aug'] += $post->value_aug;
						$json['total_data']['value_sep'] += $post->value_sep;
						$json['total_data']['value_oct'] += $post->value_oct;
						$json['total_data']['value_nov'] += $post->value_nov;
						$json['total_data']['value_dec'] += $post->value_dec;
						$json['total_data']['value_total'] += $post->value_total;
					}
				}
			}
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
    }
    /*************************PLAYER DAILY REPORT*******************************************/
    public function player_daily_deposit($id = NULL){
    	$columns = array( 
    		'a.player_id',
    		'a.username',
    		'a.points',
    		'a.old_points',
    		'COALESCE(SUM(b.deposit_amount),0) AS total_deposit_amount',
    		'COALESCE(SUM(b.deposit_offline_amount),0) AS total_deposit_offline_amount',
    		'COALESCE(SUM(b.deposit_online_amount),0) AS total_deposit_online_amount',
    		'COALESCE(SUM(b.deposit_point_amount),0) AS total_deposit_point_amount',
    		'COALESCE(SUM(b.withdrawals_amount),0) AS total_withdrawals_amount',
    		'COALESCE(SUM(b.withdrawals_offline_amount),0) AS total_withdrawals_offline_amount',
    		'COALESCE(SUM(b.withdrawals_online_amount),0) AS total_withdrawals_online_amount',
    		'COALESCE(SUM(b.withdrawals_point_amount),0) AS total_withdrawals_point_amount',
    		'COALESCE(SUM(b.adjust_amount),0) AS total_adjust_amount',
    		'COALESCE(SUM(b.adjust_in_amount),0) AS total_adjust_in_amount',
    		'COALESCE(SUM(b.adjust_out_amount),0) AS total_adjust_out_amount',
    		'COALESCE(SUM(b.win_loss),0) AS total_win_loss',
    		'COALESCE(SUM(b.promotion_amount),0) AS total_promotion_amount',
    		'COALESCE(SUM(b.bonus_amount),0) AS total_bonus_amount',
		);
    	$select = implode(',', $columns);
		$dbprefix = $this->db->dbprefix;
		$where = "AND a.player_id = ".$id;
		$posts = NULL;
		$query_string = "(SELECT {$select} FROM {$dbprefix}players a, {$dbprefix}total_win_loss_report b WHERE (a.player_id = b.player_id) $where)";
		$query = $this->db->query($query_string);
		if($query->num_rows() > 0)
		{
			$posts = $query->result();  
		}
		$totalFiltered = 1;
		//Prepare data
		$data = array();
		if(!empty($posts))
		{
			foreach ($posts as $post)
			{
				$total_deposit = $post->total_deposit_offline_amount + $post->total_deposit_online_amount + $post->total_deposit_point_amount + $post->total_adjust_in_amount + $post->total_promotion_amount + $post->total_bonus_amount;
				$total_withdrawal = $post->total_withdrawals_offline_amount + $post->total_withdrawals_online_amount + $post->total_withdrawals_point_amount + $post->total_adjust_out_amount;
				$total = $total_deposit - $total_withdrawal + $post->total_win_loss - $post->points + $post->old_points;
				$button = "";
				$row[] = $post->player_id;
				$row[] = '<a href="javascript:void(0);">' . $post->username . '</a>';
				$row[] = '<span id="uc1_' . $post->player_id . '">' . $post->points . '</span>';
				$row[] = '<span id="uc0_' . $post->player_id . '">' . $post->old_points . '</span>';
				$row[] = '<span id ="uc2_' . $post->player_id . '" class="text-' . (($post->total_deposit_amount > 0) ? 'dark' : 'dark') . '">' . number_format($post->total_deposit_amount, 2, '.', ',') . '</span>';
				$row[] = '<span id ="uc3_' . $post->player_id . '" class="text-' . (($post->total_deposit_offline_amount > 0) ? 'dark' : 'dark') . '">' . number_format($post->total_deposit_offline_amount, 2, '.', ',') . '</span>';
				$row[] = '<span id ="uc4_' . $post->player_id . '" class="text-' . (($post->total_deposit_online_amount > 0) ? 'dark' : 'dark') . '">' . number_format($post->total_deposit_online_amount, 2, '.', ',') . '</span>';
				$row[] = '<span id ="uc5_' . $post->player_id . '" class="text-' . (($post->total_deposit_point_amount > 0) ? 'dark' : 'dark') . '">' . number_format($post->total_deposit_point_amount, 2, '.', ',') . '</span>';
				$row[] = '<span id ="uc6_' . $post->player_id . '" class="text-' . (($post->total_withdrawals_amount > 0) ? 'dark' : 'dark') . '">' . number_format($post->total_withdrawals_amount, 2, '.', ',') . '</span>';
				$row[] = '<span id ="uc7_' . $post->player_id . '" class="text-' . (($post->total_withdrawals_offline_amount > 0) ? 'dark' : 'dark') . '">' . number_format($post->total_withdrawals_offline_amount, 2, '.', ',') . '</span>';
				$row[] = '<span id ="uc8_' . $post->player_id . '" class="text-' . (($post->total_withdrawals_online_amount > 0) ? 'dark' : 'dark') . '">' . number_format($post->total_withdrawals_online_amount, 2, '.', ',') . '</span>';
				$row[] = '<span id ="uc9_' . $post->player_id . '" class="text-' . (($post->total_withdrawals_point_amount > 0) ? 'dark' : 'dark') . '">' . number_format($post->total_withdrawals_point_amount, 2, '.', ',') . '</span>';
				$row[] = '<span id ="uc10_' . $post->player_id . '" class="text-' . (($post->total_adjust_amount > 0) ? 'dark' : 'dark') . '">' . number_format($post->total_adjust_amount, 2, '.', ',') . '</span>';
				$row[] = '<span id ="uc11_' . $post->player_id . '" class="text-' . (($post->total_adjust_in_amount > 0) ? 'dark' : 'dark') . '">' . number_format($post->total_adjust_in_amount, 2, '.', ',') . '</span>';
				$row[] = '<span id ="uc12_' . $post->player_id . '" class="text-' . (($post->total_adjust_out_amount > 0) ? 'dark' : 'dark') . '">' . number_format($post->total_adjust_out_amount, 2, '.', ',') . '</span>';
				$row[] = '<span id ="uc13_' . $post->player_id . '" class="text-' . (($post->total_win_loss >= 0) ? 'primary' : 'danger') . '">' . number_format($post->total_win_loss, 2, '.', ',') . '</span>';
				$row[] = '<span id ="uc14_' . $post->player_id . '" class="text-' . (($post->total_promotion_amount > 0) ? 'dark' : 'dark') . '">' . number_format($post->total_promotion_amount, 2, '.', ',') . '</span>';
				$row[] = '<span id ="uc15_' . $post->player_id . '" class="text-' . (($post->total_bonus_amount > 0) ? 'dark' : 'dark') . '">' . number_format($post->total_bonus_amount, 2, '.', ',') . '</span>';
				$row[] = '<span id ="uc16_' . $post->player_id . '">'.'<i class="fas fa-spinner fa-pulse"></i>'.'</span>';
				$row[] = '<span id ="uc17_' . $post->player_id . '">'.'<i class="fas fa-spinner fa-pulse"></i>'.'</span>';
				$row[] = '<span id ="uc18_' . $post->player_id . '">'.'<i class="fas fa-spinner fa-pulse"></i>'.'</span>';
				$row[] = '<span id ="uc19_' . $post->player_id . '" class="text-' . (($total > 0) ? 'dark' : 'dark') . '">' . number_format($total, 2, '.', ',') . '</span>';
				$data[] = $row;
			}
		}
		//Output
		$json_data = array(
			"draw"            => intval($this->input->post('draw')), 
			"recordsFiltered" => intval($totalFiltered), 
			"data"            => $data,
			"csrfHash" 		  => $this->security->get_csrf_hash(),
		);
		echo json_encode($json_data); 
		exit();
    }
    public function player_daily_deposit_type_listing($username = NULL, $type = NULL){
    	if(permission_validation(PERMISSION_DEPOSIT_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array(
				0 => 'a.deposit_id',
				1 => 'a.created_date',
				2 => 'a.deposit_type',
				3 => 'b.username',
				4 => 'a.payment_gateway_id',
				5 => 'a.transaction_code',
				6 => 'a.payment_info',
				7 => 'a.amount_apply',
				8 => 'a.rate',
				9 => 'a.amount',
				10 => 'a.status',
				11 => 'a.deposit_ip',
				12 => 'a.remark',
				13 => 'a.updated_by',
				14 => 'a.updated_date',
				15 => 'a.transaction_code_alias',
				16 => 'a.order_no',
				17 => 'a.bank_name',
				18 => 'a.bank_account_name',
				19 => 'a.bank_account_no',
				20 => 'a.player_bank_name',
				21 => 'a.player_bank_account_name',
				22 => 'a.player_bank_account_no',
				23 => 'a.promotion_id',
				24 => 'a.whitelist_status',
			);
			$sum_columns = array( 
				0 => 'SUM(a.amount_apply) AS total_deposit_apply',
				1 => 'SUM(a.amount) AS total_deposit_amount',
				2 => 'SUM(a.rate_amount) AS total_deposit_rate',
				3 => 'SUM(a.amount_rate) AS total_deposit_amount_rate',
			);
			$col = 0;
			$dir = "";
			if( ! empty($order))
			{
				foreach($order as $o)
				{
					$col = $o['column'];
					$dir = $o['dir'];
				}
			}
			if($dir != "asc" && $dir != "desc")
			{
				$dir = "desc";
			}
			if( ! isset($columns[$col]))
			{
				$order = $columns[0];
			}
			else
			{
				$order = $columns[$col];
			}
			$where = '';
			$where .= " AND b.username = '" . $username . "'";	
			$where .= ' AND a.status = ' . STATUS_APPROVE;
			$where .= ' AND a.deposit_type = ' . $type;
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;
			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}deposits a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
			$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if($query->num_rows() > 0)
			{
				$posts = $query->result();  
			}
			$query->free_result();
			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();
			$query->free_result();
			//Prepare data
			$data = array();
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					$button = "";
					$row = array();
					$row[] = ((floor(log10($post->deposit_id) + 1) > DEPOSIT_PAD_0) ? substr((string) $post->deposit_id, (DEPOSIT_PAD_0*-1)): str_pad($post->deposit_id, DEPOSIT_PAD_0, '0', STR_PAD_LEFT));
					$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
					$row[] = $this->lang->line(get_deposit_type($post->deposit_type));
					$row[] = $post->username;
					if($post->deposit_type != DEPOSIT_OFFLINE_BANKING){
						$row[] =  $this->lang->line(get_payment_gateway($post->payment_gateway_id));
					}else{
						$html = "";
						if( ! empty($post->bank_name))
						{
							$html = $post->bank_name . '<br />';
						}
						if( ! empty($post->bank_account_name))
						{
							$html .= $post->bank_account_name . '<br />';
						}
						if( ! empty($post->bank_account_no))
						{
							$html .= $post->bank_account_no . '<br />';
						}
						$row[] = $html;
					}
					if(!empty($post->transaction_code_alias)){
						$row[] =  $post->transaction_code_alias."<br/>".$post->bank_account_name;
					}else{
						$row[] =  $post->transaction_code."<br/>".$post->bank_account_name;
					}
					if($post->deposit_type != DEPOSIT_OFFLINE_BANKING){
						$row[] =  $post->payment_info;
					}else{
						$html = "";
						if( ! empty($post->player_bank_name))
						{
							$html .= $post->player_bank_name . '<br />';
						}
						if( ! empty($post->player_bank_account_name))
						{
							$html .= $post->player_bank_account_name . '<br />';
						}
						if( ! empty($post->player_bank_account_no))
						{
							$html .= $post->player_bank_account_no . '<br />';
						}
						$row[] = $html;
					}
					$row[] = '<span class="text-' . (($post->amount_apply > 0) ? 'primary' : 'dark') . '">' . number_format($post->amount_apply, 0, '.', ',') . '</span>';
					$row[] = $post->rate;
					$row[] = '<span class="text-' . (($post->amount > 0) ? 'primary' : 'dark') . '">' . number_format($post->amount, 0, '.', ',') . '</span>';
					switch($post->status)
					{
						case STATUS_ON_PENDING: $row[] = '<span class="badge bg-info" id="uc1_' . $post->deposit_id . '">' . $this->lang->line('deposit_status_on_pending') . '</span>'; break;
						case STATUS_APPROVE: $row[] = '<span class="badge bg-success" id="uc1_' . $post->deposit_id . '">' . $this->lang->line('status_approved') . '</span>'; break;
						case STATUS_CANCEL: $row[] = '<span class="badge bg-danger" id="uc1_' . $post->deposit_id . '">' . $this->lang->line('status_cancelled') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc1_' . $post->deposit_id . '">' . $this->lang->line('deposit_status_pending') . '</span>'; break;
					}
					$row[] = $post->deposit_ip;
					$row[] = '<span id="uc2_' . $post->deposit_id . '">' . ( ! empty($post->remark) ? $post->remark : '-') . '</span>';
					if($post->whitelist_status == STATUS_ACTIVE){
						$row[] = '<span id="uc6_' . $post->deposit_id . '">' . SYSTEM_DEFAULT_NAME . '</span>';
					}else{
						$row[] = '<span id="uc6_' . $post->deposit_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					}
					$row[] = '<span id="uc7_' . $post->deposit_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					if(permission_validation(PERMISSION_DEPOSIT_UPDATE) == TRUE && $post->status == STATUS_PENDING)
					{
						$button .= '<i id="uc3_' . $post->deposit_id . '" onclick="updateData(' . $post->deposit_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_DEPOSIT_UPDATE) == TRUE && permission_validation(PERMISSION_PLAYER_PROMOTION_VIEW) && !empty($post->promotion_id)){
						$button .= '<i id="uc10_' . $post->deposit_id . '" onclick="promotionData(' . $post->deposit_id . ')" class="fas fa-gifts nav-icon text-danger" title="' . $this->lang->line('button_promotion')  . '"></i> &nbsp;&nbsp; ';
					}
					$row[] = $button;
					$data[] = $row;
				}
			}
			//Output
			$json_data = array(
							"draw"            => intval($this->input->post('draw')),
							"recordsFiltered" => intval($totalFiltered), 
							"data"            => $data,
							"csrfHash" 		  => $this->security->get_csrf_hash()					
						);
			echo json_encode($json_data); 
			exit();
		}
    }
    public function player_daily_deposit_type_total($username = NULL, $type = NULL){
    	$dbprefix = $this->db->dbprefix;
    	$json = array(
			'status' => EXIT_ERROR, 
			'msg' => '',
			'total_data' => '',
		);
    	$where = '';
		$where .= " AND b.username = '" . $username . "'";	
		$where .= ' AND a.status = ' . STATUS_APPROVE;
		$where .= ' AND a.deposit_type = ' . $type;
		$sum_columns = array( 
			0 => 'SUM(a.amount_apply) AS total_deposit_apply',
			1 => 'SUM(a.amount) AS total_deposit_amount',
			2 => 'SUM(a.rate_amount) AS total_deposit_rate',
			3 => 'SUM(a.amount_rate) AS total_deposit_amount_rate',
		);
		$sum_select = implode(',', $sum_columns);
		$total_data = array(
			'total_deposit_apply' => 0,
			'total_deposit_amount' => 0,
			'total_deposit_rate' => 0,
			'total_deposit_amount_rate' => 0,
		);
		$query_string = "SELECT {$sum_select} FROM {$dbprefix}deposits a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
		$query = $this->db->query($query_string);
		if($query->num_rows() > 0)
		{
			$json['status'] = EXIT_SUCCESS;
			foreach($query->result() as $row)
			{
				$total_data = array(
					'total_deposit_apply' => (($row->total_deposit_apply > 0) ? $row->total_deposit_apply : 0),
					'total_deposit_amount' => (($row->total_deposit_amount > 0) ? $row->total_deposit_amount : 0),
					'total_deposit_rate' => (($row->total_deposit_rate > 0) ? $row->total_deposit_rate : 0),
					'total_deposit_amount_rate' => (($row->total_deposit_amount_rate > 0) ? $row->total_deposit_amount_rate : 0),
				);
			}
		}			
		$query->free_result();
		//Output
		$json['total_data'] = $total_data;
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		exit();
    }
    public function player_daily_transfer_type_listing($username = NULL, $type = NULL){
    	$limit = trim($this->input->post('length', TRUE));
		$start = trim($this->input->post("start", TRUE));
		$order = $this->input->post("order", TRUE);
		//Table Columns
		$columns = array( 
			0 => 'a.cash_transfer_id',
			1 => 'a.report_date',
			2 => 'a.transfer_type',
			3 => 'a.username',
			4 => 'a.balance_before',
			5 => 'a.deposit_amount',
			6 => 'a.withdrawal_amount',
			7 => 'a.balance_after',
			8 => 'a.remark',
			9 => 'a.executed_by',
		);
		$sum_columns = array( 
			0 => 'SUM(a.withdrawal_amount) AS total_points_withdrawn',
			1 => 'SUM(a.deposit_amount) AS total_points_deposited',
		);					
		$col = 0;
		$dir = "";
		if( ! empty($order))
		{
			foreach($order as $o)
			{
				$col = $o['column'];
				$dir = $o['dir'];
			}
		}
		if($dir != "asc" && $dir != "desc")
		{
			$dir = "desc";
		}
		if( ! isset($columns[$col]))
		{
			$order = $columns[0];
		}
		else
		{
			$order = $columns[$col];
		}
		$where = '';
		$where .= " AND a.transfer_type = ". $type;
		$where .= " AND a.username = '" . $username . "'";
		$select = implode(',', $columns);
		$order = substr($order, 2);
		$dbprefix = $this->db->dbprefix;
		$posts = NULL;
		$query_string = "(SELECT {$select} FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
		$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
		$query = $this->db->query($query_string . $query_string_2);
		if($query->num_rows() > 0)
		{
			$posts = $query->result();  
		}
		$query->free_result();
		//Get total records
		$query = $this->db->query($query_string);
		$totalFiltered = $query->num_rows();
		$query->free_result();
		//Prepare data
		$data = array();
		if(!empty($posts))
		{
			foreach ($posts as $post)
			{
				if($post->transfer_type == TRANSFER_TRANSACTION_IN || $post->transfer_type == TRANSFER_TRANSACTION_OUT){
					$remark = $post->remark;
					if(!empty($post->remark)){
						$remark_array = json_decode($remark = $post->remark,true);
						if(!empty($remark_array)){
							$date = (isset($remark_array['created_date']) ? $remark_array['created_date'] : 0);
							$from = (isset($remark_array['from']) ? (($remark_array['from'] == 'MAIN') ? $this->lang->line('label_main_wallet') : $this->lang->line('game_' . strtolower($remark_array['from']))) : "-");
							$to = (isset($remark_array['to']) ? (($remark_array['to'] == 'MAIN') ? $this->lang->line('label_main_wallet') : $this->lang->line('game_' . strtolower($remark_array['to']))) : "-");
							$response = (isset($remark_array['errorCode']) ? ($remark_array['errorCode'] == "0") ? $this->lang->line('error_success') : $this->lang->line('error_failed') : "-");
							$remark = $this->lang->line('label_transfers')."(".$this->lang->line('label_from').")"." ".$from." ".$this->lang->line('label_to')." ".$to."<br>"." ".$this->lang->line('label_remark')." : ".$response;
						}
					}
				}else{
					$remark = $post->remark;
				}
				$row = array();
				$row[] = $post->cash_transfer_id;
				$row[] = (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-');
				$row[] = $this->lang->line(get_transfer_type($post->transfer_type));
				$row[] = $post->username;
				$row[] = $post->balance_before;
				$row[] = $post->deposit_amount;
				$row[] = $post->withdrawal_amount;
				$row[] = $post->balance_after;
				$row[] = ( ! empty($remark) ? $remark : '-');
				$row[] = $post->executed_by;
				$data[] = $row;
			}
		}
		//Output
		$json_data = array(
			"draw"            => intval($this->input->post('draw')), 
			"recordsFiltered" => intval($totalFiltered), 
			"data"            => $data,
			"csrfHash" 		  => $this->security->get_csrf_hash()					
		);
		echo json_encode($json_data); 
		exit();
    }
    public function player_daily_transfer_type_total($username = NULL, $type = NULL){
    	$dbprefix = $this->db->dbprefix;
    	$json = array(
			'status' => EXIT_ERROR, 
			'msg' => '',
			'total_data' => '',
		);
    	$where = '';
		$where .= " AND a.transfer_type = ".$type;
		$where .= " AND a.username = '" . $username . "'";
		$sum_columns = array( 
			0 => 'SUM(a.withdrawal_amount) AS total_points_withdrawn',
			1 => 'SUM(a.deposit_amount) AS total_points_deposited',
		);	
		$sum_select = implode(',', $sum_columns);
		$total_data = array(
			'total_points_withdrawn' => 0, 
			'total_points_deposited' => 0
		);
		$query_string = "(SELECT {$sum_select} FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
		$query = $this->db->query($query_string);
		if($query->num_rows() > 0)
		{
			$json['status'] = EXIT_SUCCESS;
			foreach($query->result() as $row)
			{
				$total_data = array(
					'total_points_withdrawn' => (($row->total_points_withdrawn > 0) ? $row->total_points_withdrawn : 0), 
					'total_points_deposited' => (($row->total_points_deposited > 0) ? $row->total_points_deposited : 0)
				);
			}
		}
		$query->free_result();
		//Output
		$json['total_data'] = $total_data;
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		exit();
    }
    public function player_daily_promotion_listing($username = NULL){
    	$limit = trim($this->input->post('length', TRUE));
		$start = trim($this->input->post("start", TRUE));
		$order = $this->input->post("order", TRUE);
		//Table Columns
		$columns = array( 
			'a.player_promotion_id',
			'a.created_date',
			'b.username',
			'a.promotion_name',
			'a.deposit_amount',
			'a.promotion_amount',
			'a.current_amount',
			'a.achieve_amount',
			'a.reward_amount',
			'a.is_reward',
			'a.reward_date',
			'a.status',
			'a.remark',
			'a.starting_date',
			'a.complete_date',
			'a.updated_by',
			'a.updated_date',
			'a.calculate_session',
		);
		$sum_columns = array( 
			0 => 'SUM(a.reward_amount) AS total_reward',
		);
		$col = 0;
		$dir = "";
		if( ! empty($order))
		{
			foreach($order as $o)
			{
				$col = $o['column'];
				$dir = $o['dir'];
			}
		}
		if($dir != "asc" && $dir != "desc")
		{
			$dir = "desc";
		}
		if( ! isset($columns[$col]))
		{
			$order = $columns[0];
		}
		else
		{
			$order = $columns[$col];
		}
		$where = '';
		$where .= " AND b.username = '" . $username. "'";	
		$where .= ' AND a.is_reward = ' . STATUS_APPROVE;
		$select = implode(',', $columns);
		$dbprefix = $this->db->dbprefix;
		$posts = NULL;
		$query_string = "(SELECT {$select} FROM {$dbprefix}player_promotion a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
		$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
		$query = $this->db->query($query_string . $query_string_2);
		if($query->num_rows() > 0)
		{
			$posts = $query->result();  
		}
		$query->free_result();
		$query = $this->db->query($query_string);
		$totalFiltered = $query->num_rows();
		$query->free_result();
		//Prepare data
		$data = array();
		if(!empty($posts))
		{
			foreach ($posts as $post)
			{
				$button = "";
				$row = array();
				$row[] = $post->player_promotion_id;
				$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
				$row[] = $post->username;
				$row[] = $post->promotion_name;
				$row[] = number_format($post->deposit_amount,'2','.',',');
				$row[] = number_format($post->promotion_amount,'2','.',',');
				$row[] = number_format($post->current_amount,'2','.',',');
				$row[] = number_format($post->achieve_amount,'2','.',',');
				$row[] = '<span id="uc5_' . $post->player_promotion_id . '">' . number_format($post->reward_amount,'2','.',','). '</span>';
				switch($post->is_reward)
				{
					case STATUS_APPROVE: $row[] = '<span class="badge bg-success" id="uc4_' . $post->player_promotion_id . '">' . $this->lang->line('status_approved') . '</span>'; break;
					default: $row[] = '<span class="badge bg-secondary" id="uc4_' . $post->player_promotion_id . '">' . $this->lang->line('status_pending') . '</span>'; break;
				}
				$row[] = '<span id="uc6_' . $post->player_promotion_id . '">' . (($post->reward_date > 0) ? date('Y-m-d H:i:s', $post->reward_date) : '-') . '</span>';
				if($post->status == STATUS_CANCEL && empty($post->updated_by)){
					$row[] = '<span class="badge bg-success" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_system_cancel') . '</span>';
				}else{
					switch($post->status)
					{
						case STATUS_SATTLEMENT: $row[] = '<span class="badge bg-success" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_sattlement') . '</span>'; break;
						case STATUS_CANCEL: $row[] = '<span class="badge bg-danger" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_cancelled') . '</span>'; break;
						case STATUS_ENTITLEMENT: $row[] = '<span class="badge bg-primary" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_entitlement') . '</span>'; break;
						case STATUS_VOID: $row[] = '<span class="badge bg-danger" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_void') . '</span>'; break;
						case STATUS_ACCOMPLISH: $row[] = '<span class="badge bg-warning" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_accomplish') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_pending') . '</span>'; break;
					}
				}
				$row[] = '<span id="uc2_' . $post->player_promotion_id . '">' . ( ! empty($post->remark) ? $post->remark : '-') . '</span>';
				$row[] = '<span id="uc7_' . $post->player_promotion_id . '">' . (($post->starting_date > 0) ? date('Y-m-d H:i:s', $post->starting_date) : '-') . '</span>';
				$row[] = '<span id="uc8_' . $post->player_promotion_id . '">' . (($post->complete_date > 0) ? date('Y-m-d H:i:s', $post->complete_date) : '-') . '</span>';
				$row[] = '<span id="uc9_' . $post->player_promotion_id . '">' . (!empty($post->updated_by) ? $post->updated_by : '-') . '</span>';
				$row[] = '<span id="uc10_' . $post->player_promotion_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
				if(permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE){
					if($post->status == STATUS_PENDING){
						$button .= '<i id="uc21_' . $post->player_promotion_id . '" onclick="promotionEntitlement(' . $post->player_promotion_id . ')" class="fas fa-gifts nav-icon text-danger" title="' . $this->lang->line('button_entitlement')  . '"></i> &nbsp;&nbsp; ';
						$button .= '<i style="display:none;" id="uc22_' . $post->player_promotion_id . '" onclick="updateData(' . $post->player_promotion_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					if(($post->status == STATUS_ENTITLEMENT || $post->status == STATUS_ACCOMPLISH))
					{
						$button .= '<i id="uc22_' . $post->player_promotion_id . '" onclick="updateData(' . $post->player_promotion_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
				}
				if(permission_validation(PERMISSION_PLAYER_PROMOTION_BET_DETAIL) == TRUE){
					if(($post->status == STATUS_ENTITLEMENT || $post->status == STATUS_ACCOMPLISH || $post->status == STATUS_SATTLEMENT))
					{
						$button .= '<i id="uc25_' . $post->player_promotion_id . '" onclick="betDetailData(' . $post->player_promotion_id . ')" class="fas fa-clipboard-check nav-icon text-olive" title="' . $this->lang->line('button_bet_detail')  . '"></i> &nbsp;&nbsp; ';
					}
				}
				$row[] = $button;
				$data[] = $row;
			}
		}
		$sum_select = implode(',', $sum_columns);
		$total_data = array(
			'total_reward' => 0,
		);
		$query_string = "SELECT {$sum_select} FROM {$dbprefix}player_promotion a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
		$query = $this->db->query($query_string);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$total_data = array(
					'total_reward' => (($row->total_reward > 0) ? $row->total_reward : 0),
				);
			}
		}			
		$query->free_result();
		//Output
		$json_data = array(
						"draw"            => intval($this->input->post('draw')),
						"recordsFiltered" => intval($totalFiltered), 
						"data"            => $data,
						"total_data"      => $total_data,
						"csrfHash" 		  => $this->security->get_csrf_hash()					
					);
		echo json_encode($json_data); 
		exit();
    }
    public function player_daily_promotion_total($username = NULL){
    	$dbprefix = $this->db->dbprefix;
    	$json = array(
			'status' => EXIT_ERROR, 
			'msg' => '',
			'total_data' => '',
		);
    	$where = '';
		$where .= " AND b.username = '" . $username. "'";	
		$where .= ' AND a.is_reward = ' . STATUS_APPROVE;
		$sum_columns = array( 
			0 => 'SUM(a.reward_amount) AS total_reward',
		);
		$sum_select = implode(',', $sum_columns);
		$total_data = array(
			'total_reward' => 0,
		);
		$query_string = "SELECT {$sum_select} FROM {$dbprefix}player_promotion a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
		$query = $this->db->query($query_string);
		if($query->num_rows() > 0)
		{
			$json['status'] = EXIT_SUCCESS;
			foreach($query->result() as $row)
			{
				$total_data = array(
					'total_reward' => (($row->total_reward > 0) ? $row->total_reward : 0),
				);
			}
		}
		$query->free_result();
		//Output
		$json['total_data'] = $total_data;
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		exit();
    }
    public function player_daily_withdrawal_listing($username = NULL, $status){
   		$limit = trim($this->input->post('length', TRUE));
		$start = trim($this->input->post("start", TRUE));
		$order = $this->input->post("order", TRUE);
		//Table Columns
		$columns = array( 
			0 => 'a.withdrawal_id',
			1 => 'a.created_date',
			2 => 'a.withdrawal_type',
			3 => 'b.username',
			4 => 'a.bank_name',
			5 => 'a.bank_account_name',
			6 => 'a.bank_account_no',
			7 => 'a.amount',
			8 => 'a.withdrawal_fee_value',
			9 => 'a.withdrawal_fee_amount',
			10 => 'a.status',
			11 => 'a.withdrawal_ip',
			12 => 'a.remark',
			13 => 'a.updated_by',
			14 => 'a.updated_date',
			15 => 'a.player_id',
		);
		$col = 0;
		$dir = "";
		if( ! empty($order))
		{
			foreach($order as $o)
			{
				$col = $o['column'];
				$dir = $o['dir'];
			}
		}
		if($dir != "asc" && $dir != "desc")
		{
			$dir = "desc";
		}
		if( ! isset($columns[$col]))
		{
			$order = $columns[0];
		}
		else
		{
			$order = $columns[$col];
		}
		$where = '';
		$where .= ' AND a.status = ' . $status;
		$where .= " AND b.username = '" . $username . "'";
		$select = implode(',', $columns);
		$dbprefix = $this->db->dbprefix;
		$posts = NULL;
		$query_string = "(SELECT {$select} FROM {$dbprefix}withdrawals a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
		$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
		$query = $this->db->query($query_string . $query_string_2);
		if($query->num_rows() > 0)
		{
			$posts = $query->result();  
		}
		$query->free_result();
		$query = $this->db->query($query_string);
		$totalFiltered = $query->num_rows();
		$query->free_result();
		//Prepare data
		$data = array();
		if(!empty($posts))
		{
			foreach ($posts as $post)
			{
				$button = "";
				$row = array();
				$row[] = ((floor(log10($post->withdrawal_id) + 1) > WITHDRAWAL_PAD_0) ? substr((string) $post->withdrawal_id, (WITHDRAWAL_PAD_0*-1)): str_pad($post->withdrawal_id, WITHDRAWAL_PAD_0, '0', STR_PAD_LEFT));
				$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
				$row[] = $this->lang->line(get_withdrawal_type($post->withdrawal_type));
				$row[] = $post->username;
				$row[] = $post->bank_name;
				$row[] = $post->bank_account_name;
				$row[] = $post->bank_account_no;
				$row[] = '<span class="text-' . (($post->amount > 0) ? 'dark' : 'dark') . '">' . number_format($post->amount, 0, '.', ',') . '</span>';
				$row[] = number_format($post->withdrawal_fee_value, 0, '.', ',');
				$row[] = '<span class="text-' . (($post->withdrawal_fee_amount > 0) ? 'primary' : 'dark') . '">' . number_format($post->withdrawal_fee_amount, 0, '.', ',') . '</span>';
				switch($post->status)
				{
					case STATUS_ON_PENDING: $row[] = '<span class="badge bg-info" id="uc1_' . $post->withdrawal_id . '">' . $this->lang->line('status_on_pending') . '</span>'; break;
					case STATUS_APPROVE: $row[] = '<span class="badge bg-success" id="uc1_' . $post->withdrawal_id . '">' . $this->lang->line('status_approved') . '</span>'; break;
					case STATUS_CANCEL: $row[] = '<span class="badge bg-danger" id="uc1_' . $post->withdrawal_id . '">' . $this->lang->line('status_cancelled') . '</span>'; break;
					default: $row[] = '<span class="badge bg-secondary" id="uc1_' . $post->withdrawal_id . '">' . $this->lang->line('status_pending') . '</span>'; break;
				}
				$row[] = $post->withdrawal_ip;
				$row[] = '<span id="uc2_' . $post->withdrawal_id . '">' . ( ! empty($post->remark) ? $post->remark : '-') . '</span>';
				$row[] = '<span id="uc6_' . $post->withdrawal_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
				$row[] = '<span id="uc7_' . $post->withdrawal_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
				if(permission_validation(PERMISSION_WITHDRAWAL_UPDATE) == TRUE && $post->status == STATUS_PENDING)
				{
					$button .= '<i id="uc3_' . $post->withdrawal_id . '" onclick="updateData(' . $post->withdrawal_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
				}
				if(permission_validation(PERMISSION_WITHDRAWAL_UPDATE) == TRUE && permission_validation(PERMISSION_PLAYER_PROMOTION_VIEW) == TRUE  && permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE && $post->status == STATUS_PENDING){
					$button .= '<i id="uc20_' . $post->withdrawal_id . '" onclick="promotionUnsattleData(' . $post->player_id . ')" class="fas fa-gifts nav-icon text-danger" title="' . $this->lang->line('button_promotion_unsattle')  . '"></i> &nbsp;&nbsp; ';
				}
				$row[] = $button;
				$data[] = $row;
			}
		}
		//Output
		$json_data = array(
						"draw"            => intval($this->input->post('draw')),
						"recordsFiltered" => intval($totalFiltered), 
						"data"            => $data,
						"total_data"      => $total_data,
						"csrfHash" 		  => $this->security->get_csrf_hash()					
					);
		echo json_encode($json_data); 
		exit();
	}
	public function player_daily_withdrawal_total($username = NULL, $status){
	 	$dbprefix = $this->db->dbprefix;
    	$json = array(
			'status' => EXIT_ERROR, 
			'msg' => '',
			'total_data' => '',
		);
    	$where = '';
		$where .= ' AND a.status = ' . $status;
		$where .= " AND b.username = '" . $username . "'";
		$sum_columns = array( 
			0 => 'SUM(a.reward_amount) AS total_reward',
		);
		$sum_columns = array( 
			0 => 'SUM(a.amount) AS total_withdrawal',
			1 => 'SUM(a.withdrawal_fee_amount) AS total_withdrawal_fee_amount',
		);
		$sum_select = implode(',', $sum_columns);
		$total_data = array(
			'total_withdrawal' => 0,
			'total_withdrawal_fee_amount' => 0,
		);
		$total_query_string = "SELECT {$sum_select} FROM {$dbprefix}withdrawals a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
		$total_query = $this->db->query($total_query_string);
		if($total_query->num_rows() > 0)
		{
			$json['status'] = EXIT_SUCCESS;
			foreach($total_query->result() as $row)
			{
				$total_data = array(
					'total_withdrawal' => (($row->total_withdrawal > 0) ? $row->total_withdrawal : 0),
					'total_withdrawal_fee_amount' => (($row->total_withdrawal_fee_amount > 0) ? $row->total_withdrawal_fee_amount : 0),
				);
			}
		}
		$total_query->free_result();
		//Output
		$json['total_data'] = $total_data;
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		exit();
	}
	public function player_daily_winloss_listing($username = NULL, $status = NULL){
	 	$limit = trim($this->input->post('length', TRUE));
		$start = trim($this->input->post("start", TRUE));
		$order = $this->input->post("order", TRUE);
		//Table Columns
		$columns = array( 
			0 => 'a.transaction_id',
			1 => 'a.bet_time',
			2 => 'b.username',
			3 => 'a.game_provider_code',
			4 => 'a.game_type_code',
			5 => 'a.game_code',
			6 => 'a.bet_code',
			7 => 'a.game_result',
			8 => 'a.bet_amount',
			9 => 'a.bet_amount_valid',
			10 => 'a.win_loss',
			11 => 'a.jackpot_win',
			12 => 'a.status',
			13 => 'a.game_real_code',
			14 => 'a.bet_info',
			15 => 'a.bet_update_info',
		);		
		$col = 0;
		$dir = "";
		if( ! empty($order))
		{
			foreach($order as $o)
			{
				$col = $o['column'];
				$dir = $o['dir'];
			}
		}
		if($dir != "asc" && $dir != "desc")
		{
			$dir = "desc";
		}
		if( ! isset($columns[$col]))
		{
			$order = $columns[0];
		}
		else
		{
			$order = $columns[$col];
		}
		$where = '';	
		$where .= ' AND a.status = ' . $status;
		$where .= " AND b.username = '" . $username . "'";
		$select = implode(',', $columns);
		$order = substr($order, 2);
		$dbprefix = $this->db->dbprefix;
		$posts = NULL;
		$query_string = "SELECT {$select} FROM {$dbprefix}transaction_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
		$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
		$query = $this->db->query($query_string . $query_string_2);
		if($query->num_rows() > 0)
		{
			$posts = $query->result();  
		}
		$query->free_result();
		//Get total records
		$query = $this->db->query($query_string);
		$totalFiltered = $query->num_rows();
		$query->free_result();
		//Prepare data
		$data = array();
		if(!empty($posts))
		{
			foreach ($posts as $post)
			{
				if(!empty($post->bet_update_info)){
					$result = $post->bet_update_info;
				}else{
					$result = $post->bet_info;
				}
				$row = array();
				$row[] = $post->transaction_id;
				$row[] = (($post->bet_time > 0) ? date('Y-m-d H:i:s', $post->bet_time) : '-');
				$row[] = $post->username;
				$row[] = $this->lang->line('game_' . strtolower($post->game_provider_code));
				$row[] = $this->lang->line(get_game_type($post->game_type_code));
				$row[] = game_code_decision($post->game_provider_code,$post->game_type_code,$result);
				$row[] = bet_code_decision($post->game_provider_code,$post->game_type_code,$result);
				$row[] = game_result_decision($post->game_provider_code,$post->game_type_code,$result);
				$row[] = '<span class="text-' . (($post->bet_amount >= 0) ? ($post->bet_amount == 0) ? 'dark' : 'dark' : 'danger') . '">' . number_format($post->bet_amount, 2, '.', ',') . '</span>';$post->bet_amount;
				$row[] = '<span class="text-' . (($post->bet_amount_valid >= 0) ? ($post->bet_amount_valid == 0) ? 'dark' : 'dark' : 'danger') . '">' . number_format($post->bet_amount_valid, 2, '.', ',') . '</span>';$post->bet_amount_valid;
				$row[] = '<span class="text-' . (($post->win_loss >= 0) ? ($post->win_loss == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($post->win_loss, 2, '.', ',') . '</span>';
				$row[] = '<span class="text-' . (($post->jackpot_win >= 0) ? ($post->jackpot_win == 0) ? 'dark' : 'dark' : 'danger') . '">' . number_format($post->jackpot_win, 2, '.', ',') . '</span>';
				switch($post->status)
				{
					case STATUS_COMPLETE: $row[] = '<span class="badge bg-success">' . $this->lang->line('status_completed') . '</span>'; break;
					case STATUS_CANCEL: $row[] = '<span class="badge bg-danger">' . $this->lang->line('status_cancelled') . '</span>'; break;
					default: $row[] = '<span class="badge bg-secondary">' . $this->lang->line('status_pending') . '</span>'; break;
				}
				$data[] = $row;
			}
		}
		//Output
		$json_data = array(
			"draw"            => intval($this->input->post('draw')), 
			"recordsFiltered" => intval($totalFiltered), 
			"data"            => $data,
			"csrfHash" 		  => $this->security->get_csrf_hash()				
		);
		echo json_encode($json_data); 
		exit();
	}
	public function player_daily_winloss_total($username = NULL, $status = NULL){
		$dbprefix = $this->db->dbprefix;
    	$json = array(
			'status' => EXIT_ERROR, 
			'msg' => '',
			'total_data' => '',
		);
    	$where = '';	
		$where .= ' AND a.status = ' . $status;
		$where .= " AND b.username = '" . $username . "'";
		$sum_columns = array( 
			0 => 'SUM(a.reward_amount) AS total_reward',
		);
		$sum_columns = array( 
			0 => 'SUM(a.bet_amount) AS total_bet_amount',
			1 => 'SUM(a.win_loss) AS total_win_loss',
			2 => 'SUM(a.bet_amount_valid) AS total_rolling_amount',
			3 => 'SUM(a.jackpot_win) AS total_jackpot_win',
		);	
		$sum_select = implode(',', $sum_columns);
		$total_data = array(
			'total_withdrawal' => 0,
			'total_withdrawal_fee_amount' => 0,
		);
		$total_query_string = "SELECT {$sum_select} FROM {$dbprefix}transaction_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
		$total_query = $this->db->query($total_query_string);
		if($total_query->num_rows() > 0)
		{
			$json['status'] = EXIT_SUCCESS;
			foreach($total_query->result() as $row)
			{
				$total_data = array(
					'total_bet_amount' => (($row->total_bet_amount > 0) ? $row->total_bet_amount : 0),
					'total_win_loss' => $row->total_win_loss,
					'total_rolling_amount' => (($row->total_rolling_amount > 0) ? $row->total_rolling_amount : 0),
					'total_jackpot_win' => (($row->total_jackpot_win > 0) ? $row->total_jackpot_win : 0),
				);
			}
		}
		$total_query->free_result();
		//Output
		$json['total_data'] = $total_data;
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		exit();
	}
	/*************************WIN LOSS REPORT BUTTON*******************************************/
	/*************************WIN LOSS REPORT BUTTON (DEPOSIT OFFLINE)*******************************************/
	public function winloss_downline_deposit_offline($username = NULL, $type = NULL){
		if(permission_validation(PERMISSION_DEPOSIT_VIEW) == TRUE)
		{
			$arr = $this->session->userdata('search_report_winloss_sum');
	    	if(!empty($arr)){
	    		$this->session->unset_userdata('search_winloss_downline_deposit_offline');
	    		$data = array( 
					'from_date' => $arr['from_date'],
					'to_date' => $arr['to_date'],
					'upline' => $username,	
				);
				$this->session->set_userdata('search_winloss_downline_deposit_offline', $data);
				$this->load->view('winloss_report_downline_deposit_offline_table', $data);
	    	}
	    }
	}
	public function winloss_downline_deposit_offline_listing(){
		if(permission_validation(PERMISSION_DEPOSIT_VIEW) == TRUE)
		{
			$arr = $this->session->userdata('search_winloss_downline_deposit_offline');
			if(!empty($arr)){
				$userdata = $this->user_model->get_user_data_by_username($arr['upline']);
				if(!empty($userdata)){
					$limit = trim($this->input->post('length', TRUE));
					$start = trim($this->input->post("start", TRUE));
					$order = $this->input->post("order", TRUE);
					$columns = array(
						0 => 'a.deposit_id',
						1 => 'a.created_date',
						2 => 'a.deposit_type',
						3 => 'b.username',
						4 => 'a.payment_gateway_id',
						5 => 'a.transaction_code',
						6 => 'a.payment_info',
						7 => 'a.amount_apply',
						8 => 'a.rate',
						9 => 'a.amount',
						10 => 'a.status',
						11 => 'a.deposit_ip',
						12 => 'a.remark',
						13 => 'a.updated_by',
						14 => 'a.updated_date',
						15 => 'a.transaction_code_alias',
						16 => 'a.order_no',
						17 => 'a.bank_name',
						18 => 'a.bank_account_name',
						19 => 'a.bank_account_no',
						20 => 'a.player_bank_name',
						21 => 'a.player_bank_account_name',
						22 => 'a.player_bank_account_no',
						23 => 'a.promotion_id',
						24 => 'a.whitelist_status',
					);
					$col = 0;
					$dir = "";
					if( ! empty($order))
					{
						foreach($order as $o)
						{
							$col = $o['column'];
							$dir = $o['dir'];
						}
					}
					if($dir != "asc" && $dir != "desc")
					{
						$dir = "desc";
					}
					if( ! isset($columns[$col]))
					{
						$order = $columns[0];
					}
					else
					{
						$order = $columns[$col];
					}
					$where = '';		
					if( ! empty($arr['from_date']))
					{
						$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);
					}
					if( ! empty($arr['to_date']))
					{
						$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);
					}
					$where .= ' AND a.deposit_type = '.DEPOSIT_OFFLINE_BANKING;
					if( ! empty($arr['username']))
					{
						$where .= ' AND b.username = "' . $arr['username'] .'"';
					}
					$where .= ' AND a.status = ' . STATUS_APPROVE;
					$select = implode(',', $columns);
					$order = substr($order, 2);
					$dbprefix = $this->db->dbprefix;
					$posts = NULL;
					$query_string = "(SELECT {$select} FROM {$dbprefix}deposits a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $userdata['user_id'] . ",%' ESCAPE '!' $where)";
					$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
					$query = $this->db->query($query_string . $query_string_2);
					if($query->num_rows() > 0)
					{
						$posts = $query->result();  
					}
					$query->free_result();
					$query = $this->db->query($query_string);
					$totalFiltered = $query->num_rows();
					$query->free_result();
					//Prepare data
					$data = array();
					if(!empty($posts))
					{
						foreach ($posts as $post)
						{
							$button = "";
							$row = array();
							$row[] = ((floor(log10($post->deposit_id) + 1) > DEPOSIT_PAD_0) ? substr((string) $post->deposit_id, (DEPOSIT_PAD_0*-1)): str_pad($post->deposit_id, DEPOSIT_PAD_0, '0', STR_PAD_LEFT));
							$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
							$row[] = $this->lang->line(get_deposit_type($post->deposit_type));
							$row[] = $post->username;
							if($post->deposit_type != DEPOSIT_OFFLINE_BANKING){
								$row[] =  $this->lang->line(get_payment_gateway($post->payment_gateway_id));
							}else{
								$html = "";
								if( ! empty($post->bank_name))
								{
									$html = $post->bank_name . '<br />';
								}
								if( ! empty($post->bank_account_name))
								{
									$html .= $post->bank_account_name . '<br />';
								}
								if( ! empty($post->bank_account_no))
								{
									$html .= $post->bank_account_no . '<br />';
								}
								$row[] = $html;
							}
							if(!empty($post->transaction_code_alias)){
								$row[] =  $post->transaction_code_alias;
							}else{
								$row[] =  $post->transaction_code;
							}
							if($post->deposit_type != DEPOSIT_OFFLINE_BANKING){
								$row[] =  $post->payment_info;
							}else{
								$html = "";
								if( ! empty($post->player_bank_name))
								{
									$html .= $post->player_bank_name . '<br />';
								}
								if( ! empty($post->player_bank_account_name))
								{
									$html .= $post->player_bank_account_name . '<br />';
								}
								if( ! empty($post->player_bank_account_no))
								{
									$html .= $post->player_bank_account_no . '<br />';
								}
								$row[] = $html;
							}
							$row[] = '<span class="text-' . (($post->amount_apply > 0) ? 'primary' : 'dark') . '">' . number_format($post->amount_apply, 0, '.', ',') . '</span>';
							$row[] = $post->rate;
							$row[] = '<span class="text-' . (($post->amount > 0) ? 'primary' : 'dark') . '">' . number_format($post->amount, 0, '.', ',') . '</span>';
							switch($post->status)
							{
								case STATUS_ON_PENDING: $row[] = '<span class="badge bg-info" id="uc1_' . $post->deposit_id . '">' . $this->lang->line('deposit_status_on_pending') . '</span>'; break;
								case STATUS_APPROVE: $row[] = '<span class="badge bg-success" id="uc1_' . $post->deposit_id . '">' . $this->lang->line('status_approved') . '</span>'; break;
								case STATUS_CANCEL: $row[] = '<span class="badge bg-danger" id="uc1_' . $post->deposit_id . '">' . $this->lang->line('status_cancelled') . '</span>'; break;
								default: $row[] = '<span class="badge bg-secondary" id="uc1_' . $post->deposit_id . '">' . $this->lang->line('deposit_status_pending') . '</span>'; break;
							}
							$row[] = $post->deposit_ip;
							$row[] = '<span id="uc2_' . $post->deposit_id . '">' . ( ! empty($post->remark) ? $post->remark : '-') . '</span>';
							if($post->whitelist_status == STATUS_ACTIVE){
								$row[] = '<span id="uc6_' . $post->deposit_id . '">' . SYSTEM_DEFAULT_NAME . '</span>';
							}else{
								$row[] = '<span id="uc6_' . $post->deposit_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
							}
							$row[] = '<span id="uc7_' . $post->deposit_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
							$data[] = $row;
						}
					}
					//Output
					$json_data = array(
									"draw"            => intval($this->input->post('draw')),
									"recordsFiltered" => intval($totalFiltered), 
									"data"            => $data,
									"total_data"      => $total_data,
									"csrfHash" 		  => $this->security->get_csrf_hash()					
								);
					echo json_encode($json_data); 
					exit();
				}
			}
		}
	}
	public function winloss_downline_deposit_offline_listing_total(){
		if(permission_validation(PERMISSION_DEPOSIT_VIEW) == TRUE)
		{
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => '',
				'total_data' => '',
			);
			$json['total_data'] = array(
				'total_deposit_apply' => 0,
				'total_deposit_amount' => 0,
			);
			$arr = $this->session->userdata('search_winloss_downline_deposit_offline');
			if(!empty($arr)){
				$userdata = $this->user_model->get_user_data_by_username($arr['upline']);
				if(!empty($userdata)){
					$dbprefix = $this->db->dbprefix;
					$json['status'] = EXIT_SUCCESS;
					$where = '';		
					if( ! empty($arr['from_date']))
					{
						$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);
					}
					if( ! empty($arr['to_date']))
					{
						$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);
					}
					$where .= ' AND a.deposit_type = '.DEPOSIT_OFFLINE_BANKING;
					if( ! empty($arr['username']))
					{
						$where .= ' AND b.username = "' . $arr['username'] .'"';
					}
					$where .= ' AND a.status = ' . STATUS_APPROVE;
					$sum_columns = array( 
						0 => 'SUM(a.amount_apply) AS total_deposit_apply',
						1 => 'SUM(a.amount) AS total_deposit_amount',
					);
					$sum_select = implode(',', $sum_columns);
					$total_query_string = "SELECT {$sum_select} FROM {$dbprefix}deposits a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $userdata['user_id'] . ",%' ESCAPE '!' $where";
					$total_query = $this->db->query($total_query_string);
					if($total_query->num_rows() > 0)
					{
						foreach($total_query->result() as $row)
						{
							$json['total_data'] = array(
								'total_deposit_apply' => (($row->total_deposit_apply > 0) ? $row->total_deposit_apply : 0),
								'total_deposit_amount' => (($row->total_deposit_amount > 0) ? $row->total_deposit_amount : 0),
							);
						}
					}
					$total_query->free_result();
				}
			}
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();
		}
	}
	public function winloss_downline_deposit_online($username = NULL, $type = NULL){
		if(permission_validation(PERMISSION_DEPOSIT_VIEW) == TRUE)
		{
			$arr = $this->session->userdata('search_report_winloss_sum');
	    	if(!empty($arr)){
	    		$this->session->unset_userdata('search_winloss_downline_deposit_online');
	    		$data = array( 
					'from_date' => $arr['from_date'],
					'to_date' => $arr['to_date'],
					'upline' => $username,	
				);
				$this->session->set_userdata('search_winloss_downline_deposit_online', $data);
				$this->load->view('winloss_report_downline_deposit_online_table', $data);
	    	}
	    }
	}
	public function winloss_downline_deposit_online_listing(){
		if(permission_validation(PERMISSION_DEPOSIT_VIEW) == TRUE)
		{
			$arr = $this->session->userdata('search_winloss_downline_deposit_online');
			if(!empty($arr)){
				$userdata = $this->user_model->get_user_data_by_username($arr['upline']);
				if(!empty($userdata)){
					$limit = trim($this->input->post('length', TRUE));
					$start = trim($this->input->post("start", TRUE));
					$order = $this->input->post("order", TRUE);
					$columns = array(
						0 => 'a.deposit_id',
						1 => 'a.created_date',
						2 => 'a.deposit_type',
						3 => 'b.username',
						4 => 'a.payment_gateway_id',
						5 => 'a.transaction_code',
						6 => 'a.payment_info',
						7 => 'a.amount_apply',
						8 => 'a.rate',
						9 => 'a.amount',
						10 => 'a.status',
						11 => 'a.deposit_ip',
						12 => 'a.remark',
						13 => 'a.updated_by',
						14 => 'a.updated_date',
						15 => 'a.transaction_code_alias',
						16 => 'a.order_no',
						17 => 'a.bank_name',
						18 => 'a.bank_account_name',
						19 => 'a.bank_account_no',
						20 => 'a.player_bank_name',
						21 => 'a.player_bank_account_name',
						22 => 'a.player_bank_account_no',
						23 => 'a.promotion_id',
					);
					$col = 0;
					$dir = "";
					if( ! empty($order))
					{
						foreach($order as $o)
						{
							$col = $o['column'];
							$dir = $o['dir'];
						}
					}
					if($dir != "asc" && $dir != "desc")
					{
						$dir = "desc";
					}
					if( ! isset($columns[$col]))
					{
						$order = $columns[0];
					}
					else
					{
						$order = $columns[$col];
					}
					$where = '';		
					if( ! empty($arr['from_date']))
					{
						$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);
					}
					if( ! empty($arr['to_date']))
					{
						$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);
					}
					$where .= ' AND a.deposit_type != '.DEPOSIT_OFFLINE_BANKING;
					if( ! empty($arr['username']))
					{
						$where .= ' AND b.username = "' . $arr['username'] .'"';
					}
					$where .= ' AND a.status = ' . STATUS_APPROVE;
					$select = implode(',', $columns);
					$order = substr($order, 2);
					$dbprefix = $this->db->dbprefix;
					$posts = NULL;
					$query_string = "(SELECT {$select} FROM {$dbprefix}deposits a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $userdata['user_id'] . ",%' ESCAPE '!' $where)";
					$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
					$query = $this->db->query($query_string . $query_string_2);
					if($query->num_rows() > 0)
					{
						$posts = $query->result();  
					}
					$query->free_result();
					$query = $this->db->query($query_string);
					$totalFiltered = $query->num_rows();
					$query->free_result();
					//Prepare data
					$data = array();
					if(!empty($posts))
					{
						foreach ($posts as $post)
						{
							$button = "";
							$row = array();
							$row[] = ((floor(log10($post->deposit_id) + 1) > DEPOSIT_PAD_0) ? substr((string) $post->deposit_id, (DEPOSIT_PAD_0*-1)): str_pad($post->deposit_id, DEPOSIT_PAD_0, '0', STR_PAD_LEFT));
							$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
							$row[] = $this->lang->line(get_deposit_type($post->deposit_type));
							$row[] = $post->username;
							if($post->deposit_type != DEPOSIT_OFFLINE_BANKING){
								$row[] =  $this->lang->line(get_payment_gateway($post->payment_gateway_id));
							}else{
								$html = "";
								if( ! empty($post->bank_name))
								{
									$html = $post->bank_name . '<br />';
								}
								if( ! empty($post->bank_account_name))
								{
									$html .= $post->bank_account_name . '<br />';
								}
								if( ! empty($post->bank_account_no))
								{
									$html .= $post->bank_account_no . '<br />';
								}
								$row[] = $html;
							}
							if(!empty($post->transaction_code_alias)){
								$row[] =  $post->transaction_code_alias;
							}else{
								$row[] =  $post->transaction_code;
							}
							if($post->deposit_type != DEPOSIT_OFFLINE_BANKING){
								$row[] =  $post->payment_info;
							}else{
								$html = "";
								if( ! empty($post->player_bank_name))
								{
									$html .= $post->player_bank_name . '<br />';
								}
								if( ! empty($post->player_bank_account_name))
								{
									$html .= $post->player_bank_account_name . '<br />';
								}
								if( ! empty($post->player_bank_account_no))
								{
									$html .= $post->player_bank_account_no . '<br />';
								}
								$row[] = $html;
							}
							$row[] = '<span class="text-' . (($post->amount_apply > 0) ? 'primary' : 'dark') . '">' . number_format($post->amount_apply, 0, '.', ',') . '</span>';
							$row[] = $post->rate;
							$row[] = '<span class="text-' . (($post->amount > 0) ? 'primary' : 'dark') . '">' . number_format($post->amount, 0, '.', ',') . '</span>';
							switch($post->status)
							{
								case STATUS_ON_PENDING: $row[] = '<span class="badge bg-info" id="uc1_' . $post->deposit_id . '">' . $this->lang->line('deposit_status_on_pending') . '</span>'; break;
								case STATUS_APPROVE: $row[] = '<span class="badge bg-success" id="uc1_' . $post->deposit_id . '">' . $this->lang->line('status_approved') . '</span>'; break;
								case STATUS_CANCEL: $row[] = '<span class="badge bg-danger" id="uc1_' . $post->deposit_id . '">' . $this->lang->line('status_cancelled') . '</span>'; break;
								default: $row[] = '<span class="badge bg-secondary" id="uc1_' . $post->deposit_id . '">' . $this->lang->line('deposit_status_pending') . '</span>'; break;
							}
							$row[] = $post->deposit_ip;
							$row[] = '<span id="uc2_' . $post->deposit_id . '">' . ( ! empty($post->remark) ? $post->remark : '-') . '</span>';
							$row[] = '<span id="uc6_' . $post->deposit_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
							$row[] = '<span id="uc7_' . $post->deposit_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
							$data[] = $row;
						}
					}
					//Output
					$json_data = array(
									"draw"            => intval($this->input->post('draw')),
									"recordsFiltered" => intval($totalFiltered), 
									"data"            => $data,
									"total_data"      => $total_data,
									"csrfHash" 		  => $this->security->get_csrf_hash()					
								);
					echo json_encode($json_data); 
					exit();
				}
			}
		}
	}
	public function winloss_downline_deposit_online_listing_total(){
		if(permission_validation(PERMISSION_DEPOSIT_VIEW) == TRUE)
		{
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => '',
				'total_data' => '',
			);
			$json['total_data'] = array(
				'total_deposit_apply' => 0,
				'total_deposit_amount' => 0,
			);
			$arr = $this->session->userdata('search_winloss_downline_deposit_online');
			if(!empty($arr)){
				$userdata = $this->user_model->get_user_data_by_username($arr['upline']);
				if(!empty($userdata)){
					$dbprefix = $this->db->dbprefix;
					$json['status'] = EXIT_SUCCESS;
					$where = '';		
					if( ! empty($arr['from_date']))
					{
						$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);
					}
					if( ! empty($arr['to_date']))
					{
						$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);
					}
					$where .= ' AND a.deposit_type != '.DEPOSIT_OFFLINE_BANKING;
					if( ! empty($arr['username']))
					{
						$where .= ' AND b.username = "' . $arr['username'] .'"';
					}
					$where .= ' AND a.status = ' . STATUS_APPROVE;
					$sum_columns = array( 
						0 => 'SUM(a.amount_apply) AS total_deposit_apply',
						1 => 'SUM(a.amount) AS total_deposit_amount',
					);
					$sum_select = implode(',', $sum_columns);
					$total_query_string = "SELECT {$sum_select} FROM {$dbprefix}deposits a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $userdata['user_id'] . ",%' ESCAPE '!' $where";
					$total_query = $this->db->query($total_query_string);
					if($total_query->num_rows() > 0)
					{
						foreach($total_query->result() as $row)
						{
							$json['total_data'] = array(
								'total_deposit_apply' => (($row->total_deposit_apply > 0) ? $row->total_deposit_apply : 0),
								'total_deposit_amount' => (($row->total_deposit_amount > 0) ? $row->total_deposit_amount : 0),
							);
						}
					}
					$total_query->free_result();
				}
			}
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();
		}
	}
	public function winloss_downline_withdrawal_offline($username = NULL, $type = NULL){
		if(permission_validation(PERMISSION_WITHDRAWAL_VIEW) == TRUE)
		{
			$arr = $this->session->userdata('search_report_winloss_sum');
	    	if(!empty($arr)){
	    		$this->session->unset_userdata('search_winloss_downline_withdrawal_offline');
	    		$data = array( 
					'from_date' => $arr['from_date'],
					'to_date' => $arr['to_date'],
					'upline' => $username,	
				);
				$this->session->set_userdata('search_winloss_downline_withdrawal_offline', $data);
				$this->load->view('winloss_report_downline_withdrawal_offline_table', $data);
	    	}
	    }
	}
	public function winloss_downline_withdrawal_offline_listing(){
		if(permission_validation(PERMISSION_WITHDRAWAL_VIEW) == TRUE)
		{
			$arr = $this->session->userdata('search_winloss_downline_withdrawal_offline');
			if(!empty($arr)){
				$userdata = $this->user_model->get_user_data_by_username($arr['upline']);
				if(!empty($userdata)){
					$limit = trim($this->input->post('length', TRUE));
					$start = trim($this->input->post("start", TRUE));
					$order = $this->input->post("order", TRUE);
					$columns = array(
						0 => 'a.withdrawal_id',
						1 => 'a.created_date',
						2 => 'a.withdrawal_type',
						3 => 'b.username',
						4 => 'a.bank_name',
						5 => 'a.bank_account_name',
						6 => 'a.bank_account_no',
						7 => 'a.amount',
						8 => 'a.withdrawal_fee_value',
						9 => 'a.withdrawal_fee_amount',
						10 => 'a.status',
						11 => 'a.withdrawal_ip',
						12 => 'a.remark',
						13 => 'a.updated_by',
						14 => 'a.updated_date',
						15 => 'a.player_id',
					);
					$col = 0;
					$dir = "";
					if( ! empty($order))
					{
						foreach($order as $o)
						{
							$col = $o['column'];
							$dir = $o['dir'];
						}
					}
					if($dir != "asc" && $dir != "desc")
					{
						$dir = "desc";
					}
					if( ! isset($columns[$col]))
					{
						$order = $columns[0];
					}
					else
					{
						$order = $columns[$col];
					}
					$where = '';		
					if( ! empty($arr['from_date']))
					{
						$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);
					}
					if( ! empty($arr['to_date']))
					{
						$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);
					}
					$where .= ' AND a.withdrawal_type = '.WITHDRAWAL_OFFLINE_BANKING;
					if( ! empty($arr['username']))
					{
						$where .= ' AND b.username = "' . $arr['username'] .'"';
					}
					$where .= ' AND a.status = ' . STATUS_APPROVE;
					$select = implode(',', $columns);
					$order = substr($order, 2);
					$dbprefix = $this->db->dbprefix;
					$posts = NULL;
					$query_string = "(SELECT {$select} FROM {$dbprefix}withdrawals a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $userdata['user_id'] . ",%' ESCAPE '!' $where)";
					$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
					$query = $this->db->query($query_string . $query_string_2);
					if($query->num_rows() > 0)
					{
						$posts = $query->result();  
					}
					$query->free_result();
					$query = $this->db->query($query_string);
					$totalFiltered = $query->num_rows();
					$query->free_result();
					//Prepare data
					$data = array();
					if(!empty($posts))
					{
						foreach ($posts as $post)
						{
							$button = "";
							$row = array();
							$row[] = ((floor(log10($post->withdrawal_id) + 1) > WITHDRAWAL_PAD_0) ? substr((string) $post->withdrawal_id, (WITHDRAWAL_PAD_0*-1)): str_pad($post->withdrawal_id, WITHDRAWAL_PAD_0, '0', STR_PAD_LEFT));
							$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
							$row[] = $this->lang->line(get_withdrawal_type($post->withdrawal_type));
							$row[] = $post->username;
							$row[] = $post->bank_name;
							$row[] = $post->bank_account_name;
							$row[] = $post->bank_account_no;
							$row[] = '<span class="text-' . (($post->amount > 0) ? 'dark' : 'dark') . '">' . number_format($post->amount, 0, '.', ',') . '</span>';
							$row[] = number_format($post->withdrawal_fee_value, 0, '.', ',');
							$row[] = '<span class="text-' . (($post->withdrawal_fee_amount > 0) ? 'primary' : 'dark') . '">' . number_format($post->withdrawal_fee_amount, 0, '.', ',') . '</span>';
							switch($post->status)
							{
								case STATUS_ON_PENDING: $row[] = '<span class="badge bg-info" id="uc1_' . $post->withdrawal_id . '">' . $this->lang->line('status_on_pending') . '</span>'; break;
								case STATUS_APPROVE: $row[] = '<span class="badge bg-success" id="uc1_' . $post->withdrawal_id . '">' . $this->lang->line('status_approved') . '</span>'; break;
								case STATUS_CANCEL: $row[] = '<span class="badge bg-danger" id="uc1_' . $post->withdrawal_id . '">' . $this->lang->line('status_cancelled') . '</span>'; break;
								default: $row[] = '<span class="badge bg-secondary" id="uc1_' . $post->withdrawal_id . '">' . $this->lang->line('status_pending') . '</span>'; break;
							}
							$row[] = $post->withdrawal_ip;
							$row[] = '<span id="uc2_' . $post->withdrawal_id . '">' . ( ! empty($post->remark) ? $post->remark : '-') . '</span>';
							$row[] = '<span id="uc6_' . $post->withdrawal_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
							$row[] = '<span id="uc7_' . $post->withdrawal_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
							$data[] = $row;
						}
					}
					//Output
					$json_data = array(
									"draw"            => intval($this->input->post('draw')),
									"recordsFiltered" => intval($totalFiltered), 
									"data"            => $data,
									"total_data"      => $total_data,
									"csrfHash" 		  => $this->security->get_csrf_hash()					
								);
					echo json_encode($json_data); 
					exit();
				}
			}
		}
	}
	public function winloss_downline_withdrawal_offline_listing_total(){
		if(permission_validation(PERMISSION_WITHDRAWAL_VIEW) == TRUE)
		{
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => '',
				'total_data' => '',
			);
			$json['total_data'] = array(
				'total_withdrawal' => 0,
				'total_withdrawal_fee_amount' => 0,
			);
			$arr = $this->session->userdata('search_winloss_downline_withdrawal_offline');
			if(!empty($arr)){
				$userdata = $this->user_model->get_user_data_by_username($arr['upline']);
				if(!empty($userdata)){
					$dbprefix = $this->db->dbprefix;
					$json['status'] = EXIT_SUCCESS;
					$where = '';		
					if( ! empty($arr['from_date']))
					{
						$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);
					}
					if( ! empty($arr['to_date']))
					{
						$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);
					}
					$where .= ' AND a.withdrawal_type = '.WITHDRAWAL_OFFLINE_BANKING;
					if( ! empty($arr['username']))
					{
						$where .= ' AND b.username = "' . $arr['username'] .'"';
					}
					$where .= ' AND a.status = ' . STATUS_APPROVE;
					$sum_columns = array( 
						0 => 'SUM(a.amount) AS total_withdrawal',
						1 => 'SUM(a.withdrawal_fee_amount) AS total_withdrawal_fee_amount',
					);
					$sum_select = implode(',', $sum_columns);
					$total_query_string = "SELECT {$sum_select} FROM {$dbprefix}withdrawals a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $userdata['user_id'] . ",%' ESCAPE '!' $where";
					$total_query = $this->db->query($total_query_string);
					if($total_query->num_rows() > 0)
					{
						foreach($total_query->result() as $row)
						{
							$json['total_data'] = array(
								'total_withdrawal' => (($row->total_withdrawal > 0) ? $row->total_withdrawal : 0),
								'total_withdrawal_fee_amount' => (($row->total_withdrawal_fee_amount > 0) ? $row->total_withdrawal_fee_amount : 0),
							);
						}
					}
					$total_query->free_result();
				}
			}
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();
		}
	}
	public function winloss_downline_adjust($username = NULL, $type = NULL){
		$arr = $this->session->userdata('search_report_winloss_sum');
    	if(!empty($arr)){
    		$this->session->unset_userdata('search_winloss_downline_adjust');
    		$data = array( 
				'from_date' => $arr['from_date'],
				'to_date' => $arr['to_date'],
				'upline' => $username,
				'type' => $type,
			);
			$this->session->set_userdata('search_winloss_downline_adjust', $data);
			if($type == TRANSFER_ADJUST_IN){
				$data['title'] = $this->lang->line('label_adjust_in');
			}else{
				$data['title'] = $this->lang->line('label_adjust_out');
			}
			$this->load->view('winloss_report_downline_adjust_table', $data);
    	}
	}
	public function winloss_downline_adjust_listing(){
		$arr = $this->session->userdata('search_winloss_downline_adjust');
		if(!empty($arr)){
			$userdata = $this->user_model->get_user_data_by_username($arr['upline']);
			if(!empty($userdata)){
				$limit = trim($this->input->post('length', TRUE));
				$start = trim($this->input->post("start", TRUE));
				$order = $this->input->post("order", TRUE);
				//Table Columns
				$columns = array( 
					0 => 'a.cash_transfer_id',
					1 => 'a.report_date',
					2 => 'a.transfer_type',
					3 => 'a.username',
					4 => 'a.balance_before',
					5 => 'a.deposit_amount',
					6 => 'a.withdrawal_amount',
					7 => 'a.balance_after',
					8 => 'a.remark',
					9 => 'a.executed_by',
				);
				$sum_columns = array( 
					0 => 'SUM(a.withdrawal_amount) AS total_points_withdrawn',
					1 => 'SUM(a.deposit_amount) AS total_points_deposited',
				);					
				$col = 0;
				$dir = "";
				if( ! empty($order))
				{
					foreach($order as $o)
					{
						$col = $o['column'];
						$dir = $o['dir'];
					}
				}
				if($dir != "asc" && $dir != "desc")
				{
					$dir = "desc";
				}
				if( ! isset($columns[$col]))
				{
					$order = $columns[0];
				}
				else
				{
					$order = $columns[$col];
				}
				$where = '';
				if( ! empty($arr['from_date']))
				{
					$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
				}
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
				}
				if( ! empty($arr['type']))
				{
					$where .= " AND a.transfer_type = ". $arr['type'];
				}
				if( ! empty($arr['username']))
				{
					$where .= " AND a.username = ". $arr['username'];
				}
				$select = implode(',', $columns);
				$order = substr($order, 2);
				$dbprefix = $this->db->dbprefix;
				$posts = NULL;
				$query_string = "(SELECT {$select} FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $userdata['user_id'] . ",%' ESCAPE '!' $where)";
				$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
				$query = $this->db->query($query_string . $query_string_2);
				if($query->num_rows() > 0)
				{
					$posts = $query->result();  
				}
				$query->free_result();
				//Get total records
				$query = $this->db->query($query_string);
				$totalFiltered = $query->num_rows();
				$query->free_result();
				//Prepare data
				$data = array();
				if(!empty($posts))
				{
					foreach ($posts as $post)
					{
						if($post->transfer_type == TRANSFER_TRANSACTION_IN || $post->transfer_type == TRANSFER_TRANSACTION_OUT){
							$remark = $post->remark;
							if(!empty($post->remark)){
								$remark_array = json_decode($remark = $post->remark,true);
								if(!empty($remark_array)){
									$date = (isset($remark_array['created_date']) ? $remark_array['created_date'] : 0);
									$from = (isset($remark_array['from']) ? (($remark_array['from'] == 'MAIN') ? $this->lang->line('label_main_wallet') : $this->lang->line('game_' . strtolower($remark_array['from']))) : "-");
									$to = (isset($remark_array['to']) ? (($remark_array['to'] == 'MAIN') ? $this->lang->line('label_main_wallet') : $this->lang->line('game_' . strtolower($remark_array['to']))) : "-");
									$response = (isset($remark_array['errorCode']) ? ($remark_array['errorCode'] == "0") ? $this->lang->line('error_success') : $this->lang->line('error_failed') : "-");
									$remark = $this->lang->line('label_transfers')."(".$this->lang->line('label_from').")"." ".$from." ".$this->lang->line('label_to')." ".$to."<br>"." ".$this->lang->line('label_remark')." : ".$response;
								}
							}
						}else{
							$remark = $post->remark;
						}
						$row = array();
						$row[] = $post->cash_transfer_id;
						$row[] = (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-');
						$row[] = $this->lang->line(get_transfer_type($post->transfer_type));
						$row[] = $post->username;
						$row[] = $post->balance_before;
						$row[] = $post->deposit_amount;
						$row[] = $post->withdrawal_amount;
						$row[] = $post->balance_after;
						$row[] = ( ! empty($remark) ? $remark : '-');
						$row[] = $post->executed_by;
						$data[] = $row;
					}
				}
				//Output
				$json_data = array(
					"draw"            => intval($this->input->post('draw')), 
					"recordsFiltered" => intval($totalFiltered), 
					"data"            => $data,
					"csrfHash" 		  => $this->security->get_csrf_hash()					
				);
				echo json_encode($json_data); 
				exit();
			}
		}
	}
	public function winloss_downline_adjust_listing_total(){
		$arr = $this->session->userdata('search_winloss_downline_adjust');
		if(!empty($arr)){
			$userdata = $this->user_model->get_user_data_by_username($arr['upline']);
			if(!empty($userdata)){
				$dbprefix = $this->db->dbprefix;
		    	$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'total_data' => '',
				);
		    	$where = '';
				if( ! empty($arr['from_date']))
				{
					$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
				}
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
				}
				if( ! empty($arr['type']))
				{
					$where .= " AND a.transfer_type = ". $arr['type'];
				}
				if( ! empty($arr['username']))
				{
					$where .= " AND a.username = ". $arr['username'];
				}
				$sum_columns = array( 
					0 => 'SUM(a.withdrawal_amount) AS total_points_withdrawn',
					1 => 'SUM(a.deposit_amount) AS total_points_deposited',
				);	
				$sum_select = implode(',', $sum_columns);
				$total_data = array(
					'total_points_withdrawn' => 0, 
					'total_points_deposited' => 0
				);
				$query_string = "(SELECT {$sum_select} FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $userdata['user_id'] . ",%' ESCAPE '!' $where)";
				$query = $this->db->query($query_string);
				if($query->num_rows() > 0)
				{
					$json['status'] = EXIT_SUCCESS;
					foreach($query->result() as $row)
					{
						$total_data = array(
							'total_points_withdrawn' => (($row->total_points_withdrawn > 0) ? $row->total_points_withdrawn : 0), 
							'total_points_deposited' => (($row->total_points_deposited > 0) ? $row->total_points_deposited : 0)
						);
					}
				}
				$query->free_result();
				//Output
				$json['total_data'] = $total_data;
				$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($json))
						->_display();
				exit();
			}
		}
	}
	public function winloss_downline_promotion($username = NULL, $type = NULL){
		$arr = $this->session->userdata('search_report_winloss_sum');
    	if(!empty($arr)){
    		$this->session->unset_userdata('search_winloss_downline_promotion');
    		$data = array( 
				'from_date' => $arr['from_date'],
				'to_date' => $arr['to_date'],
				'upline' => $username,
			);
			$this->session->set_userdata('search_winloss_downline_promotion', $data);
			$this->load->view('winloss_report_downline_promotion_table', $data);
    	}
	}
	public function winloss_downline_promotion_listing(){
		$arr = $this->session->userdata('search_winloss_downline_promotion');
    	if(!empty($arr)){
    		$userdata = $this->user_model->get_user_data_by_username($arr['upline']);
			if(!empty($userdata)){
				$limit = trim($this->input->post('length', TRUE));
				$start = trim($this->input->post("start", TRUE));
				$order = $this->input->post("order", TRUE);
				//Table Columns
				$columns = array( 
					'a.player_promotion_id',
					'a.created_date',
					'b.username',
					'a.promotion_name',
					'a.deposit_amount',
					'a.promotion_amount',
					'a.current_amount',
					'a.achieve_amount',
					'a.reward_amount',
					'a.is_reward',
					'a.reward_date',
					'a.status',
					'a.remark',
					'a.starting_date',
					'a.complete_date',
					'a.updated_by',
					'a.updated_date',
					'a.calculate_session',
				);
				$col = 0;
				$dir = "";
				if( ! empty($order))
				{
					foreach($order as $o)
					{
						$col = $o['column'];
						$dir = $o['dir'];
					}
				}
				if($dir != "asc" && $dir != "desc")
				{
					$dir = "desc";
				}
				if( ! isset($columns[$col]))
				{
					$order = $columns[0];
				}
				else
				{
					$order = $columns[$col];
				}
				$where = '';
				if( ! empty($arr['from_date']))
				{
					$where .= ' AND a.reward_date >= ' . strtotime($arr['from_date']);
				}
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND a.reward_date <= ' . strtotime($arr['to_date']);
				}
				if( ! empty($arr['username']))
				{
					$where .= " AND b.username = ". $arr['username'];
				}
				$where .= ' AND a.is_reward = ' . STATUS_APPROVE;
				$select = implode(',', $columns);
				$dbprefix = $this->db->dbprefix;
				$posts = NULL;
				$query_string = "(SELECT {$select} FROM {$dbprefix}player_promotion a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $userdata['user_id'] . ",%' ESCAPE '!' $where)";
				$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
				$query = $this->db->query($query_string . $query_string_2);
				if($query->num_rows() > 0)
				{
					$posts = $query->result();  
				}
				$query->free_result();
				$query = $this->db->query($query_string);
				$totalFiltered = $query->num_rows();
				$query->free_result();
				//Prepare data
				$data = array();
				if(!empty($posts))
				{
					foreach ($posts as $post)
					{
						$button = "";
						$row = array();
						$row[] = $post->player_promotion_id;
						$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
						$row[] = $post->username;
						$row[] = $post->promotion_name;
						$row[] = number_format($post->deposit_amount,'2','.',',');
						$row[] = number_format($post->promotion_amount,'2','.',',');
						$row[] = number_format($post->current_amount,'2','.',',');
						$row[] = number_format($post->achieve_amount,'2','.',',');
						$row[] = '<span id="uc5_' . $post->player_promotion_id . '">' . number_format($post->reward_amount * -1,'2','.',','). '</span>';
						switch($post->is_reward)
						{
							case STATUS_APPROVE: $row[] = '<span class="badge bg-success" id="uc4_' . $post->player_promotion_id . '">' . $this->lang->line('status_approved') . '</span>'; break;
							default: $row[] = '<span class="badge bg-secondary" id="uc4_' . $post->player_promotion_id . '">' . $this->lang->line('status_pending') . '</span>'; break;
						}
						$row[] = '<span id="uc6_' . $post->player_promotion_id . '">' . (($post->reward_date > 0) ? date('Y-m-d H:i:s', $post->reward_date) : '-') . '</span>';
						switch($post->status)
						{
							case STATUS_SATTLEMENT: $row[] = '<span class="badge bg-success" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_sattlement') . '</span>'; break;
							case STATUS_CANCEL: $row[] = '<span class="badge bg-danger" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_cancelled') . '</span>'; break;
							case STATUS_ENTITLEMENT: $row[] = '<span class="badge bg-primary" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_entitlement') . '</span>'; break;
							case STATUS_VOID: $row[] = '<span class="badge bg-danger" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_void') . '</span>'; break;
							case STATUS_ACCOMPLISH: $row[] = '<span class="badge bg-warning" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_accomplish') . '</span>'; break;
							default: $row[] = '<span class="badge bg-secondary" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_pending') . '</span>'; break;
						}
						$row[] = '<span id="uc2_' . $post->player_promotion_id . '">' . ( ! empty($post->remark) ? $post->remark : '-') . '</span>';
						$row[] = '<span id="uc7_' . $post->player_promotion_id . '">' . (($post->starting_date > 0) ? date('Y-m-d H:i:s', $post->starting_date) : '-') . '</span>';
						$row[] = '<span id="uc8_' . $post->player_promotion_id . '">' . (($post->complete_date > 0) ? date('Y-m-d H:i:s', $post->complete_date) : '-') . '</span>';
						$row[] = '<span id="uc9_' . $post->player_promotion_id . '">' . (!empty($post->updated_by) ? $post->updated_by : '-') . '</span>';
						$row[] = '<span id="uc10_' . $post->player_promotion_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
						$data[] = $row;
					}
				}
				//Output
				$json_data = array(
								"draw"            => intval($this->input->post('draw')),
								"recordsFiltered" => intval($totalFiltered), 
								"data"            => $data,
								"csrfHash" 		  => $this->security->get_csrf_hash()					
							);
				echo json_encode($json_data); 
				exit();
			}
		}
	}
	public function winloss_downline_promotion_listing_total(){
		$arr = $this->session->userdata('search_winloss_downline_promotion');
    	if(!empty($arr)){
    		$userdata = $this->user_model->get_user_data_by_username($arr['upline']);
			if(!empty($userdata)){
				$dbprefix = $this->db->dbprefix;
		    	$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'total_data' => '',
				);
		    	$where = '';
				if( ! empty($arr['from_date']))
				{
					$where .= ' AND a.reward_date >= ' . strtotime($arr['from_date']);
				}
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND a.reward_date <= ' . strtotime($arr['to_date']);
				}
				if( ! empty($arr['username']))
				{
					$where .= " AND b.username = ". $arr['username'];
				}
				$where .= ' AND a.is_reward = ' . STATUS_APPROVE;
				$sum_columns = array( 
					0 => 'SUM(a.reward_amount) AS total_reward',
				);	
				$sum_select = implode(',', $sum_columns);
				$total_data = array(
					'total_reward' => 0,
				);
				$query_string = "SELECT {$sum_select} FROM {$dbprefix}player_promotion a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $userdata['user_id'] . ",%' ESCAPE '!' $where";
				$query = $this->db->query($query_string);
				if($query->num_rows() > 0)
				{
					$json['status'] = EXIT_SUCCESS;
					foreach($query->result() as $row)
					{
						$total_data = array(
							'total_reward' => (($row->total_reward > 0) ? $row->total_reward*-1 : 0),
						);
					}
				}
				$query->free_result();
				//Output
				$json['total_data'] = $total_data;
				$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($json))
						->_display();
				exit();
			}
		}
	}
	public function winloss_report_downline_bet_table($username = NULL, $type = NULL){
		$arr = $this->session->userdata('search_report_winloss_sum');
		if(!empty($arr)){
    		$this->session->unset_userdata('search_winloss_downline_bet');
    		$data = array( 
				'from_date' => $arr['from_date'],
				'to_date' => $arr['to_date'],
				'upline' => $username,
			);
			$this->session->set_userdata('search_winloss_downline_bet', $data);
			$this->load->view('winloss_report_downline_bet_table', $data);
    	}
	}
	public function winloss_downline_bet_listing(){
		$arr = $this->session->userdata('search_winloss_downline_bet');
    	if(!empty($arr)){
    		$userdata = $this->user_model->get_user_data_by_username($arr['upline']);
			if(!empty($userdata)){
				$limit = trim($this->input->post('length', TRUE));
				$start = trim($this->input->post("start", TRUE));
				$order = $this->input->post("order", TRUE);
				//Table Columns
				$columns = array( 
					0 => 'a.transaction_id',
					1 => 'a.bet_time',
					2 => 'b.username',
					3 => 'a.game_provider_code',
					4 => 'a.game_type_code',
					5 => 'a.game_code',
					6 => 'a.bet_code',
					7 => 'a.game_result',
					8 => 'a.bet_amount',
					9 => 'a.bet_amount_valid',
					10 => 'a.win_loss',
					11 => 'a.jackpot_win',
					12 => 'a.status',
					13 => 'a.game_real_code',
					14 => 'a.bet_info',
					15 => 'a.bet_update_info',
				);		
				$col = 0;
				$dir = "";
				if( ! empty($order))
				{
					foreach($order as $o)
					{
						$col = $o['column'];
						$dir = $o['dir'];
					}
				}
				if($dir != "asc" && $dir != "desc")
				{
					$dir = "desc";
				}
				if( ! isset($columns[$col]))
				{
					$order = $columns[0];
				}
				else
				{
					$order = $columns[$col];
				}
				if( ! empty($arr['from_date']))
				{
					$where .= ' AND a.bet_time >= ' . strtotime($arr['from_date']);
				}
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND a.bet_time <= ' . strtotime($arr['to_date']);
				}
				if( ! empty($arr['username']))
				{
					$where .= " AND b.username = ". $arr['username'];
				}
				$where .= ' AND a.status = ' . STATUS_APPROVE;
				$select = implode(',', $columns);
				$order = substr($order, 2);
				$dbprefix = $this->db->dbprefix;
				$posts = NULL;
				$query_string = "SELECT {$select} FROM {$dbprefix}transaction_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $userdata['user_id'] . ",%' ESCAPE '!' $where";
				$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
				$query = $this->db->query($query_string . $query_string_2);
				if($query->num_rows() > 0)
				{
					$posts = $query->result();  
				}
				$query->free_result();
				//Get total records
				$query = $this->db->query($query_string);
				$totalFiltered = $query->num_rows();
				$query->free_result();
				//Prepare data
				$data = array();
				if(!empty($posts))
				{
					foreach ($posts as $post)
					{
						if(!empty($post->bet_update_info)){
							$result = $post->bet_update_info;
						}else{
							$result = $post->bet_info;
						}
						$row = array();
						$row[] = $post->transaction_id;
						$row[] = (($post->bet_time > 0) ? date('Y-m-d H:i:s', $post->bet_time) : '-');
						$row[] = $post->username;
						$row[] = $this->lang->line('game_' . strtolower($post->game_provider_code));
						$row[] = $this->lang->line(get_game_type($post->game_type_code));
						$row[] = game_code_decision($post->game_provider_code,$post->game_type_code,$result);
						$row[] = bet_code_decision($post->game_provider_code,$post->game_type_code,$result);
						$row[] = game_result_decision($post->game_provider_code,$post->game_type_code,$result);
						$row[] = '<span class="text-' . (($post->bet_amount >= 0) ? ($post->bet_amount == 0) ? 'dark' : 'dark' : 'danger') . '">' . number_format($post->bet_amount, 2, '.', ',') . '</span>';$post->bet_amount;
						$row[] = '<span class="text-' . (($post->bet_amount_valid >= 0) ? ($post->bet_amount_valid == 0) ? 'dark' : 'dark' : 'danger') . '">' . number_format($post->bet_amount_valid, 2, '.', ',') . '</span>';$post->bet_amount_valid;
						$row[] = '<span class="text-' . (($post->win_loss >= 0) ? ($post->win_loss == 0) ? 'dark' : 'danger' : 'primary') . '">' . number_format($post->win_loss * -1, 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($post->jackpot_win >= 0) ? ($post->jackpot_win == 0) ? 'dark' : 'dark' : 'danger') . '">' . number_format($post->jackpot_win, 2, '.', ',') . '</span>';
						switch($post->status)
						{
							case STATUS_COMPLETE: $row[] = '<span class="badge bg-success">' . $this->lang->line('status_completed') . '</span>'; break;
							case STATUS_CANCEL: $row[] = '<span class="badge bg-danger">' . $this->lang->line('status_cancelled') . '</span>'; break;
							default: $row[] = '<span class="badge bg-secondary">' . $this->lang->line('status_pending') . '</span>'; break;
						}
						$data[] = $row;
					}
				}
				//Output
				$json_data = array(
					"draw"            => intval($this->input->post('draw')), 
					"recordsFiltered" => intval($totalFiltered), 
					"data"            => $data,
					"csrfHash" 		  => $this->security->get_csrf_hash()				
				);
				echo json_encode($json_data); 
				exit();
			}
		}
	}
	public function winloss_downline_bet_listing_total(){
		$arr = $this->session->userdata('search_winloss_downline_bet');
    	if(!empty($arr)){
    		$userdata = $this->user_model->get_user_data_by_username($arr['upline']);
			if(!empty($userdata)){
				$dbprefix = $this->db->dbprefix;
		    	$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'total_data' => '',
				);
		    	if( ! empty($arr['from_date']))
				{
					$where .= ' AND a.bet_time >= ' . strtotime($arr['from_date']);
				}
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND a.bet_time <= ' . strtotime($arr['to_date']);
				}
				if( ! empty($arr['username']))
				{
					$where .= " AND b.username = ". $arr['username'];
				}
				$where .= ' AND a.status = ' . STATUS_APPROVE;
				$sum_columns = array( 
					0 => 'SUM(a.bet_amount) AS total_bet_amount',
					1 => 'SUM(a.win_loss) AS total_win_loss',
					2 => 'SUM(a.bet_amount_valid) AS total_rolling_amount',
					3 => 'SUM(a.jackpot_win) AS total_jackpot_win',
				);	
				$sum_select = implode(',', $sum_columns);
				$total_data = array(
					'total_bet_amount' => 0,
					'total_win_loss' => 0,
					'total_rolling_amount' => 0,
					'total_jackpot_win' => 0,
				);
				$total_query_string = "SELECT {$sum_select} FROM {$dbprefix}transaction_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $userdata['user_id'] . ",%' ESCAPE '!' $where";
				$total_query = $this->db->query($total_query_string);
				if($total_query->num_rows() > 0)
				{
					$json['status'] = EXIT_SUCCESS;
					foreach($total_query->result() as $row)
					{
						$total_data = array(
							'total_bet_amount' => (($row->total_bet_amount > 0) ? $row->total_bet_amount : 0),
							'total_win_loss' => $row->total_win_loss  * -1,
							'total_rolling_amount' => (($row->total_rolling_amount > 0) ? $row->total_rolling_amount : 0),
							'total_jackpot_win' => (($row->total_jackpot_win > 0) ? $row->total_jackpot_win : 0),
						);
					}
				}
				$total_query->free_result();
				//Output
				$json['total_data'] = $total_data;
				$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($json))
						->_display();
				exit();
			}
		}
	}
	/*************************WITHDRAWAL VERIFY*******************************************/
	public function withdrawal_verify(){
		if(permission_validation(PERMISSION_PLAYER_WITHDRAWAL_VERIFY_REPORT) == TRUE)
		{
			$this->save_current_url('report/withdrawal_verify');
			$data = quick_search();
			$data['game_list'] = $this->game_model->get_game_list();
			$data['page_title'] = $this->lang->line('title_withdraw_verify_report');
			$data_search = array(
				'from_date' => '',
				'to_date' => '',
				'username' => '',
				'agent' => '',
			);
			$this->session->unset_userdata('search_report_withdrawal_verify');
			$data['data_search'] = $data_search;
			$this->session->set_userdata('search_report_withdrawal_verify', $data);
			$this->load->view('withdrawal_verify_report_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function withdrawal_verify_search()
	{
		if(permission_validation(PERMISSION_PLAYER_WITHDRAWAL_VERIFY_REPORT) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'general_error' => '',
					'from_date_error' => '',
					'to_date_error' => '',
				),
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			$config = array();
			if($this->input->post('from_date', TRUE) != ""){
				$configAdd = array(
					'field' => 'from_date',
					'label' => strtolower($this->lang->line('label_from_date')),
					'rules' => 'trim|required|callback_date_check',
					'errors' => array(
							'required' => $this->lang->line('error_invalid_datetime_format'),
							'date_check' => $this->lang->line('error_invalid_datetime_format')
					)
				);
				array_push($config, $configAdd);
			}
			if($this->input->post('to_date', TRUE) != ""){
				$configAdd = array(
					'field' => 'to_date',
					'label' => strtolower($this->lang->line('label_to_date')),
					'rules' => 'trim|required|callback_date_check',
					'errors' => array(
							'required' => $this->lang->line('error_invalid_datetime_format'),
							'date_check' => $this->lang->line('error_invalid_datetime_format')
					)
				);
				array_push($config, $configAdd);
			}
			$is_allow = true;
			if(!empty($config)){
				$this->form_validation->set_rules($config);
				$this->form_validation->set_error_delimiters('', '');
				if ($this->form_validation->run() == TRUE)
				{
				}else{
					$is_allow = false;
					$json['msg']['from_date_error'] = form_error('from_date');
					$json['msg']['to_date_error'] = form_error('to_date');
				}
			}
			if($is_allow){
				$data = array( 
					'from_date' => trim($this->input->post('from_date', TRUE)),
					'to_date' => trim($this->input->post('to_date', TRUE)),
					'username' => trim($this->input->post('username', TRUE)),
					'agent' => trim($this->input->post('agent', TRUE)),
				);
				$this->session->set_userdata('search_report_withdrawal_verify', $data);				
				$json['status'] = EXIT_SUCCESS;
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function withdrawal_verify_listing(){
		if(permission_validation(PERMISSION_PLAYER_WITHDRAWAL_VERIFY_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			$arr = $this->session->userdata('search_report_withdrawal_verify');
			$dbprefix = $this->db->dbprefix;
			$where = "";
			$posts = NULL;
			$player_list = array();
			$where .= 'WHERE b.withdrawals_amount > 0';
			if(isset($arr['from_date']))
			{
				if( ! empty($arr['from_date'])){
					$where .= ' AND b.report_date >= ' . strtotime($arr['from_date']);
				}
			}
			if( ! empty($arr['to_date']))
			{
				if( ! empty($arr['to_date'])){
					$where .= ' AND b.report_date <= ' . strtotime($arr['to_date']);
				}
			}
			$query_string = "SELECT b.player_id FROM {$dbprefix}total_win_loss_report b $where";
			$query = $this->db->query($query_string . $query_string_2);
			if($query->num_rows() > 0)
			{
				$posts = $query->result();  
			}
			$query->free_result();
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					$player_list[] = $post->player_id;
				}
				$player_id = '"'.implode('","', $player_list).'"';
			}
			$where = "";
			$sum_win_loss_columns = array(
				'b.player_id',
				'COALESCE(SUM(b.deposit_amount),0) AS total_deposit_amount',
	    		'COALESCE(SUM(b.deposit_offline_amount),0) AS total_deposit_offline_amount',
	    		'COALESCE(SUM(b.deposit_online_amount),0) AS total_deposit_online_amount',
	    		'COALESCE(SUM(b.deposit_point_amount),0) AS total_deposit_point_amount',
	    		'COALESCE(SUM(b.withdrawals_amount),0) AS total_withdrawals_amount',
	    		'COALESCE(SUM(b.withdrawals_offline_amount),0) AS total_withdrawals_offline_amount',
	    		'COALESCE(SUM(b.withdrawals_online_amount),0) AS total_withdrawals_online_amount',
	    		'COALESCE(SUM(b.withdrawals_point_amount),0) AS total_withdrawals_point_amount',
	    		'COALESCE(SUM(b.adjust_amount),0) AS total_adjust_amount',
	    		'COALESCE(SUM(b.adjust_in_amount),0) AS total_adjust_in_amount',
	    		'COALESCE(SUM(b.adjust_out_amount),0) AS total_adjust_out_amount',
	    		'COALESCE(SUM(b.win_loss),0) AS total_win_loss',
	    		'COALESCE(SUM(b.promotion_amount),0) AS total_promotion_amount',
	    		'COALESCE(SUM(b.bonus_amount),0) AS total_bonus_amount',
			);
			$sum_withdrawal_columns = array(
				'b.player_id',
				'COALESCE(SUM(b.amount),0) AS total_withdrawals_amount',
			);
			$sum_bet_columns = array(
				'b.player_id',
				'COALESCE(SUM(b.bet_amount),0) AS total_bet_amount',
			);
			//Table Columns
			$columns = array( 
				'a.player_id',
	    		'a.username',
	    		'a.points',
	    		'a.old_points',
	    		'COALESCE(SUM(b.deposit_amount),0) AS total_deposit_amount',
	    		'COALESCE(SUM(b.deposit_offline_amount),0) AS total_deposit_offline_amount',
	    		'COALESCE(SUM(b.deposit_online_amount),0) AS total_deposit_online_amount',
	    		'COALESCE(SUM(b.deposit_point_amount),0) AS total_deposit_point_amount',
	    		'COALESCE(SUM(b.withdrawals_amount),0) AS total_withdrawals_amount',
	    		'COALESCE(SUM(b.withdrawals_offline_amount),0) AS total_withdrawals_offline_amount',
	    		'COALESCE(SUM(b.withdrawals_online_amount),0) AS total_withdrawals_online_amount',
	    		'COALESCE(SUM(b.withdrawals_point_amount),0) AS total_withdrawals_point_amount',
	    		'COALESCE(SUM(b.adjust_amount),0) AS total_adjust_amount',
	    		'COALESCE(SUM(b.adjust_in_amount),0) AS total_adjust_in_amount',
	    		'COALESCE(SUM(b.adjust_out_amount),0) AS total_adjust_out_amount',
	    		'COALESCE(SUM(b.win_loss),0) AS total_win_loss',
	    		'COALESCE(SUM(b.promotion_amount),0) AS total_promotion_amount',
	    		'COALESCE(SUM(b.bonus_amount),0) AS total_bonus_amount',
			);
			$columns_sort = array( 
				'a.player_id',
	    		'a.username',
	    		'a.points',
	    		'a.old_points',
	    		'total_deposit_amount',
	    		'total_deposit_offline_amount',
	    		'total_deposit_online_amount',
	    		'total_deposit_point_amount',
	    		'total_withdrawals_amount',
	    		'total_withdrawals_offline_amount',
	    		'total_withdrawals_online_amount',
	    		'total_withdrawals_point_amount',
	    		'total_adjust_amount',
	    		'total_adjust_in_amount',
	    		'total_adjust_out_amount',
	    		'total_win_loss',
	    		'total_promotion_amount',
	    		'total_bonus_amount',
			);
			$col = 0;
			$dir = "";
			if( ! empty($order))
			{
				foreach($order as $o)
				{
					$col = $o['column'];
					$dir = $o['dir'];
				}
			}
			if($dir != "asc" && $dir != "desc")
			{
				$dir = "desc";
			}
			if( ! isset($columns[$col]))
			{
				$order = $columns_sort[0];
			}
			else
			{
				$order = $columns_sort[$col];
			}
			if( ! empty($arr['agent']))
			{
				$where = " AND player_id = 'ABC'";
				$agent = $this->user_model->get_user_data_by_username($arr['agent']);
				if(!empty($agent)){
					$response_upline = $this->user_model->get_downline_data($agent['username']);
					if(!empty($response_upline)){
						$where = " AND a.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";
					}
				}
			}else{
				$where = " AND a.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";
			}
			/****REGISTER DATE****/
			/*
			if(isset($arr['from_date']))
			{
				if( ! empty($arr['from_date'])){
					$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);
				}
			}
			if( ! empty($arr['to_date']))
			{
				if( ! empty($arr['to_date'])){
					$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);
				}
			}
			*/
			if( ! empty($arr['username']))
			{
				$where .= " AND a.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	
			}
			if(!empty($player_id)){
				$where .= " AND b.player_id IN(" . $player_id . ")";
			}else{
				$where .= " AND b.player_id = 'abc'";
			}
			$select = implode(',', $columns);
			$posts = NULL;
			$query_string = "SELECT {$select} FROM {$dbprefix}total_win_loss_report b, {$dbprefix}players a WHERE a.player_id = b.player_id $where GROUP BY a.player_id";
			$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if($query->num_rows() > 0)
			{
				$posts = $query->result();  
			}
			$query->free_result();
			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();
			$query->free_result();
			//Prepare data
			$data = array();
			$player_list = array();
			$player_id = "";
			$win_loss_list = array();
			$pending_withdrawal_list = array();
			$pending_bet_list = array();
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					$player_list[] = $post->player_id;
				}
				$player_id = '"'.implode('","', $player_list).'"';
			}
			/*
			if(!empty($player_list)){
				$sum_win_loss_select = implode(',', $sum_win_loss_columns);
				$sum_win_loss_query_string = "(SELECT {$sum_win_loss_select} FROM {$dbprefix}total_win_loss_report b WHERE b.player_id IN(" . $player_id . ") GROUP BY b.player_id)";
				$sum_win_loss_query = $this->db->query($sum_win_loss_query_string);
				if($sum_win_loss_query->num_rows() > 0)
				{
					foreach($sum_win_loss_query->result() as $row)
					{
						$win_loss_list[$row->player_id] = array(
							'total_deposit_amount' => $row->total_deposit_amount,
							'total_deposit_offline_amount' => $row->total_deposit_offline_amount,
							'total_deposit_online_amount' => $row->total_deposit_online_amount,
							'total_deposit_point_amount' => $row->total_deposit_point_amount,
							'total_withdrawals_amount' => $row->total_withdrawals_amount,
							'total_withdrawals_offline_amount' => $row->total_withdrawals_offline_amount,
							'total_withdrawals_online_amount' => $row->total_withdrawals_online_amount,
							'total_withdrawals_point_amount' => $row->total_withdrawals_point_amount,
							'total_adjust_amount' => $row->total_adjust_amount,
							'total_adjust_in_amount' => $row->total_adjust_in_amount,
							'total_adjust_out_amount' => $row->total_adjust_out_amount,
							'total_win_loss' => $row->total_win_loss,
							'total_promotion_amount' => $row->total_promotion_amount,
							'total_bonus_amount' => $row->total_bonus_amount,
						);
					}
				}
				$sum_win_loss_query->free_result();
			}
			*/
			if(!empty($player_list)){
				$sum_withdrawal_select = implode(',', $sum_withdrawal_columns);
				$sum_withdrawal_query_string = "(SELECT {$sum_withdrawal_select} FROM {$dbprefix}withdrawals b WHERE b.status = ".STATUS_PENDING." AND b.player_id IN(" . $player_id . ") GROUP BY b.player_id)";
				$sum_withdrawal_query = $this->db->query($sum_withdrawal_query_string);
				if($sum_withdrawal_query->num_rows() > 0)
				{
					foreach($sum_withdrawal_query->result() as $row)
					{
						$pending_withdrawal_list[$row->player_id] = array(
							'total_withdrawals_amount' => $row->total_withdrawals_amount,
						);
					}
				}
				$sum_withdrawal_query->free_result();
			}
			if(!empty($player_list)){
				$sum_bet_select = implode(',', $sum_bet_columns);
				$sum_bet_query_string = "(SELECT {$sum_bet_select} FROM {$dbprefix}transaction_report b WHERE b.status = ".STATUS_PENDING." AND b.player_id IN(" . $player_id . ") GROUP BY b.player_id)";
				$sum_bet_query = $this->db->query($sum_bet_query_string);
				if($sum_bet_query->num_rows() > 0)
				{
					foreach($sum_bet_query->result() as $row)
					{
						$pending_bet_list[$row->player_id] = array(
							'total_bet_amount' => $row->total_bet_amount,
						);
					}
				}
				$sum_bet_query->free_result();
			}
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					$row = array();
					/*
					$total_deposit_amount = ((isset($win_loss_list[$post->player_id])) ? $win_loss_list[$post->player_id]['total_deposit_amount'] : 0);
					$total_deposit_offline_amount = ((isset($win_loss_list[$post->player_id])) ? $win_loss_list[$post->player_id]['total_deposit_offline_amount'] : 0);
					$total_deposit_online_amount = ((isset($win_loss_list[$post->player_id])) ? $win_loss_list[$post->player_id]['total_deposit_online_amount'] : 0);
					$total_deposit_point_amount = ((isset($win_loss_list[$post->player_id])) ? $win_loss_list[$post->player_id]['total_deposit_point_amount'] : 0);
					$total_withdrawals_amount = ((isset($win_loss_list[$post->player_id])) ? $win_loss_list[$post->player_id]['total_withdrawals_amount'] : 0);
					$total_withdrawals_offline_amount = ((isset($win_loss_list[$post->player_id])) ? $win_loss_list[$post->player_id]['total_withdrawals_offline_amount'] : 0);
					$total_withdrawals_online_amount = ((isset($win_loss_list[$post->player_id])) ? $win_loss_list[$post->player_id]['total_withdrawals_online_amount'] : 0);
					$total_withdrawals_point_amount = ((isset($win_loss_list[$post->player_id])) ? $win_loss_list[$post->player_id]['total_withdrawals_point_amount'] : 0);
					$total_adjust_amount = ((isset($win_loss_list[$post->player_id])) ? $win_loss_list[$post->player_id]['total_adjust_amount'] : 0);
					$total_adjust_in_amount = ((isset($win_loss_list[$post->player_id])) ? $win_loss_list[$post->player_id]['total_adjust_in_amount'] : 0);
					$total_adjust_out_amount = ((isset($win_loss_list[$post->player_id])) ? $win_loss_list[$post->player_id]['total_adjust_out_amount'] : 0);
					$total_win_loss = ((isset($win_loss_list[$post->player_id])) ? $win_loss_list[$post->player_id]['total_win_loss'] : 0);
					$total_promotion_amount = ((isset($win_loss_list[$post->player_id])) ? $win_loss_list[$post->player_id]['total_promotion_amount'] : 0);
					$total_bonus_amount = ((isset($win_loss_list[$post->player_id])) ? $win_loss_list[$post->player_id]['total_bonus_amount'] : 0);
					*/
					$total_deposit_amount = $post->total_deposit_amount;
					$total_deposit_offline_amount = $post->total_deposit_offline_amount;
					$total_deposit_online_amount = $post->total_deposit_online_amount;
					$total_deposit_point_amount = $post->total_deposit_point_amount;
					$total_withdrawals_amount = $post->total_withdrawals_amount;
					$total_withdrawals_offline_amount = $post->total_withdrawals_offline_amount;
					$total_withdrawals_online_amount = $post->total_withdrawals_online_amount;
					$total_withdrawals_point_amount = $post->total_withdrawals_point_amount;
					$total_adjust_amount = $post->total_adjust_amount;
					$total_adjust_in_amount = $post->total_adjust_in_amount;
					$total_adjust_out_amount = $post->total_adjust_out_amount;
					$total_win_loss = $post->total_win_loss;
					$total_promotion_amount = $post->total_promotion_amount;
					$total_bonus_amount = $post->total_bonus_amount;
					$total_pending_withdrawals_amount = ((isset($pending_withdrawal_list[$post->player_id])) ? $pending_withdrawal_list[$post->player_id]['total_withdrawals_amount'] : 0);
					$total_pending_bet_amount = ((isset($pending_bet_list[$post->player_id])) ? $pending_bet_list[$post->player_id]['total_bet_amount'] : 0);
					$total_deposit = $total_deposit_offline_amount + $total_deposit_online_amount + $total_deposit_point_amount + $total_adjust_in_amount + $total_promotion_amount + $total_bonus_amount;
					$total_withdrawal = $total_withdrawals_offline_amount + $total_withdrawals_online_amount + $total_withdrawals_point_amount + $total_adjust_out_amount;
					$total = $total_deposit - $total_withdrawal + $total_win_loss - $post->points + $post->old_points - $total_pending_withdrawals_amount - $total_pending_bet_amount;
					$row[] = $post->player_id;
					if(permission_validation(PERMISSION_PLAYER_DAILY_REPORT) == TRUE)
					{
						$row[] = '<a href="javascript:void(0);" onclick="player_daily(' . $post->player_id . ')">' . $post->username . '</a>';
					}else{
						$row[] = '<a href="javascript:void(0);">' . $post->username . '</a>';
					}
					$row[] = '<span id="uc1_' . $post->player_id . '">' . $post->points . '</span>';
					$row[] = '<span id="uc0_' . $post->player_id . '">' . $post->old_points . '</span>';
					$row[] = '<span id ="uc2_' . $post->player_id . '" class="text-' . (($total_deposit_amount > 0) ? 'dark' : 'dark') . '">' . number_format($total_deposit_amount, 2, '.', ',') . '</span>';
					$row[] = '<span id ="uc3_' . $post->player_id . '" class="text-' . (($total_deposit_offline_amount > 0) ? 'dark' : 'dark') . '">' . number_format($total_deposit_offline_amount, 2, '.', ',') . '</span>';
					$row[] = '<span id ="uc4_' . $post->player_id . '" class="text-' . (($total_deposit_online_amount > 0) ? 'dark' : 'dark') . '">' . number_format($total_deposit_online_amount, 2, '.', ',') . '</span>';
					$row[] = '<span id ="uc5_' . $post->player_id . '" class="text-' . (($total_deposit_point_amount > 0) ? 'dark' : 'dark') . '">' . number_format($total_deposit_point_amount, 2, '.', ',') . '</span>';
					$row[] = '<span id ="uc6_' . $post->player_id . '" class="text-' . (($total_withdrawals_amount > 0) ? 'dark' : 'dark') . '">' . number_format($total_withdrawals_amount, 2, '.', ',') . '</span>';
					$row[] = '<span id ="uc7_' . $post->player_id . '" class="text-' . (($total_withdrawals_offline_amount > 0) ? 'dark' : 'dark') . '">' . number_format($total_withdrawals_offline_amount, 2, '.', ',') . '</span>';
					$row[] = '<span id ="uc8_' . $post->player_id . '" class="text-' . (($total_withdrawals_online_amount > 0) ? 'dark' : 'dark') . '">' . number_format($total_withdrawals_online_amount, 2, '.', ',') . '</span>';
					$row[] = '<span id ="uc9_' . $post->player_id . '" class="text-' . (($total_withdrawals_point_amount > 0) ? 'dark' : 'dark') . '">' . number_format($total_withdrawals_point_amount, 2, '.', ',') . '</span>';
					$row[] = '<span id ="uc10_' . $post->player_id . '" class="text-' . (($total_adjust_amount > 0) ? 'dark' : 'dark') . '">' . number_format($total_adjust_amount, 2, '.', ',') . '</span>';
					$row[] = '<span id ="uc11_' . $post->player_id . '" class="text-' . (($total_adjust_in_amount > 0) ? 'dark' : 'dark') . '">' . number_format($total_adjust_in_amount, 2, '.', ',') . '</span>';
					$row[] = '<span id ="uc12_' . $post->player_id . '" class="text-' . (($total_adjust_out_amount > 0) ? 'dark' : 'dark') . '">' . number_format($total_adjust_out_amount, 2, '.', ',') . '</span>';
					$row[] = '<span id ="uc13_' . $post->player_id . '" class="text-' . (($total_win_loss >= 0) ? 'primary' : 'danger') . '">' . number_format($total_win_loss, 2, '.', ',') . '</span>';
					$row[] = '<span id ="uc14_' . $post->player_id . '" class="text-' . (($total_promotion_amount > 0) ? 'dark' : 'dark') . '">' . number_format($total_promotion_amount, 2, '.', ',') . '</span>';
					$row[] = '<span id ="uc15_' . $post->player_id . '" class="text-' . (($total_bonus_amount > 0) ? 'dark' : 'dark') . '">' . number_format($total_bonus_amount, 2, '.', ',') . '</span>';
					$row[] = '<span id ="uc16_' . $post->player_id . '">'.'<i class="fas fa-wallet" onclick="load_game_wallet('."'".$post->player_id."'".')"></i>'.'</span>';
					$row[] = '<span id ="uc17_' . $post->player_id . '" class="text-' . (($total_pending_withdrawals_amount > 0) ? 'dark' : 'dark') . '">' . number_format($total_pending_withdrawals_amount, 2, '.', ',') . '</span>';
					$row[] = '<span id ="uc18_' . $post->player_id . '" class="text-' . (($total_pending_bet_amount > 0) ? 'dark' : 'dark') . '">' . number_format($total_pending_bet_amount, 2, '.', ',') . '</span>';
					$row[] = '<span id ="uc19_' . $post->player_id . '" class="text-' . (($total > 0) ? 'dark' : 'dark') . '">' . number_format($total, 2, '.', ',') . '</span>';
					$data[] = $row;
				}
			}
			//Output
			$json_data = array(
				"draw"            => intval($this->input->post('draw')), 
				"recordsFiltered" => intval($totalFiltered), 
				"data"            => $data,
				"csrfHash" 		  => $this->security->get_csrf_hash()					
			);
			echo json_encode($json_data); 
			exit();
		}
	}
	/*************************REGISTER DEPOSIT RATE*******************************************/
	public function register_deposit_rate(){
		if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT) == TRUE)
		{
			$this->save_current_url('report/register_deposit_rate');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_register_deposit_rate_report');
			$this->session->unset_userdata('search_register_deposit_rate');
			$this->load->view('register_deposit_rate_view', $data);
		}
	}
	public function register_deposit_rate_search(){
		if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR,
					'msg' => '',
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			//Set form rules
			$config = array(
				array(
						'field' => 'from_date',
						'label' => strtolower($this->lang->line('label_from_date')),
						'rules' => 'trim|required|callback_month_check',
						'errors' => array(
								'required' => $this->lang->line('error_invalid_datetime_format'),
								'month_check' => $this->lang->line('error_invalid_datetime_format')
						)
				)
			);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$data = array( 
					'from_date' => trim($this->input->post('from_date', TRUE)),
					'excludezero' =>  trim($this->input->post('excludezero', TRUE)),
				);
				$this->session->set_userdata('search_register_deposit_rate', $data);
				$json['status'] = EXIT_SUCCESS;
			}
			else 
			{
				$error = array(
					'from_date' => form_error('from_date'),
				);
				if( ! empty($error['from_date']))
				{
					$json['msg'] = $error['from_date'];
				}
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function register_deposit_rate_listing($num = NULL, $username = NULL){
		if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$arr = $this->session->userdata('search_register_deposit_rate');
			if( ! empty($arr['from_date']))
			{
				$dbprefix = $this->db->dbprefix;
				$data = array();
				$start_date = $arr['from_date']."-01 00:00:00";
				$start_time = strtotime($start_date);
				$end_date	= date('Y-m-d 00:00:00', strtotime('first day of next month',$start_time));
				$end_time = strtotime($end_date);
 				$where_total_register_player = "";
 				$where_total_player_have_deposit = "";
 				$where_total_player_have_one_deposit = "";
 				$where_total_player_have_two_or_more_deposit = "";
 				$where_total_player_have_three_or_more_deposit = "";
				$where_total_player_have_deposit_amount = "";
 				$where_total_player_have_one_deposit_amount = "";
 				$where_total_player_have_two_or_more_deposit_amount = "";
 				$where_total_player_have_three_or_more_deposit_amount = "";
				$upline_query_string = "SELECT MU.user_id,MU.user_type,MU.username,MU.upline";
				//Total Register Player
				$where_total_register_player .= "AP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
				if( ! empty($arr['from_date']))
				{
					$where_total_register_player .= ' AND AP.created_date >= ' . $start_time;
				}
				if( ! empty($arr['from_date']))
				{
					$where_total_register_player .= ' AND AP.created_date < ' . $end_time;
				}
				$upline_query_string .= ",(SELECT COUNT(AP.player_id) AS total_register_player FROM {$dbprefix}players AP where $where_total_register_player) AS total_register_player ";
				//Total Player Have Deposit
				$where_total_player_have_deposit .= "BP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
				$where_total_player_have_deposit .= ' AND BP.deposit_count > 0';
				if( ! empty($arr['from_date']))
				{
					$where_total_player_have_deposit .= ' AND BP.created_date >= ' . $start_time;
				}
				if( ! empty($arr['from_date']))
				{
					$where_total_player_have_deposit .= ' AND BP.created_date < ' . $end_time;
				}
				$upline_query_string .= ",(SELECT COUNT(BP.player_id) AS total_player_have_deposit FROM {$dbprefix}players BP where $where_total_player_have_deposit) AS total_player_have_deposit ";
				//Total Player Have Deposit Sum
				$where_total_player_have_deposit_amount .= "BSP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
				$where_total_player_have_deposit_amount .= ' AND BSP.deposit_count > 0';
				$where_total_player_have_deposit_amount .= " AND BSTR.player_id = BSP.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_player_have_deposit_amount .= ' AND BSP.created_date >= ' . $start_time;
				}
				if( ! empty($arr['from_date']))
				{
					$where_total_player_have_deposit_amount .= ' AND BSP.created_date < ' . $end_time;
				}
				$upline_query_string .= ",(SELECT SUM(BSTR.deposit_amount) AS total_player_have_deposit_amount FROM {$dbprefix}total_win_loss_report_month BSTR, {$dbprefix}players BSP where $where_total_player_have_deposit_amount) AS total_player_have_deposit_amount";
				//Total Player have one only deposit
				$where_total_player_have_one_deposit .= "CP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
				$where_total_player_have_one_deposit .= ' AND CP.deposit_count = 1';
				if( ! empty($arr['from_date']))
				{
					$where_total_player_have_one_deposit .= ' AND CP.created_date >= ' . $start_time;
				}
				if( ! empty($arr['from_date']))
				{
					$where_total_player_have_one_deposit .= ' AND CP.created_date < ' . $end_time;
				}
				$upline_query_string .= ",(SELECT COUNT(CP.player_id) AS total_player_have_one_deposit FROM {$dbprefix}players CP where $where_total_player_have_one_deposit) AS total_player_have_one_deposit ";
				//Total Player have one only deposit Sum
				$where_total_player_have_one_deposit_amount .= "CSP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
				$where_total_player_have_one_deposit_amount .= ' AND CSP.deposit_count = 1';
				$where_total_player_have_one_deposit_amount .= " AND CSTR.player_id = CSP.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_player_have_one_deposit_amount .= ' AND CSP.created_date >= ' . $start_time;
				}
				if( ! empty($arr['from_date']))
				{
					$where_total_player_have_one_deposit_amount .= ' AND CSP.created_date < ' . $end_time;
				}
				$upline_query_string .= ",(SELECT SUM(CSTR.deposit_amount) AS total_player_have_one_deposit_amount FROM {$dbprefix}total_win_loss_report_month CSTR, {$dbprefix}players CSP where $where_total_player_have_one_deposit_amount) AS total_player_have_one_deposit_amount";
				//Total Player have 2 or more deposit
				$where_total_player_have_two_or_more_deposit .= "DP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
				$where_total_player_have_two_or_more_deposit .= ' AND DP.deposit_count = 2';
				if( ! empty($arr['from_date']))
				{
					$where_total_player_have_two_or_more_deposit .= ' AND DP.created_date >= ' . $start_time;
				}
				if( ! empty($arr['from_date']))
				{
					$where_total_player_have_two_or_more_deposit .= ' AND DP.created_date < ' . $end_time;
				}
				$upline_query_string .= ",(SELECT COUNT(DP.player_id) AS total_player_have_two_or_more_deposit FROM {$dbprefix}players DP where $where_total_player_have_two_or_more_deposit) AS total_player_have_two_or_more_deposit ";
				//Total Player have 2 or more deposit Sum
				$where_total_player_have_two_or_more_deposit_amount .= "DSP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
				$where_total_player_have_two_or_more_deposit_amount .= ' AND DSP.deposit_count = 2';
				$where_total_player_have_two_or_more_deposit_amount .= " AND DSTR.player_id = DSP.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_player_have_two_or_more_deposit_amount .= ' AND DSP.created_date >= ' . $start_time;
				}
				if( ! empty($arr['from_date']))
				{
					$where_total_player_have_two_or_more_deposit_amount .= ' AND DSP.created_date < ' . $end_time;
				}
				$upline_query_string .= ",(SELECT SUM(DSTR.deposit_amount) AS total_player_have_two_or_more_deposit_amount FROM {$dbprefix}total_win_loss_report_month DSTR, {$dbprefix}players DSP where $where_total_player_have_two_or_more_deposit_amount) AS total_player_have_two_or_more_deposit_amount";
				//Total Player have 3 or more deposit
				$where_total_player_have_three_or_more_deposit .= "EP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
				$where_total_player_have_three_or_more_deposit .= ' AND EP.deposit_count >= 3';
				if( ! empty($arr['from_date']))
				{
					$where_total_player_have_three_or_more_deposit .= ' AND EP.created_date >= ' . $start_time;
				}
				if( ! empty($arr['from_date']))
				{
					$where_total_player_have_three_or_more_deposit .= ' AND EP.created_date < ' . $end_time;
				}
				$upline_query_string .= ",(SELECT COUNT(EP.player_id) AS total_player_have_three_or_more_deposit FROM {$dbprefix}players EP where $where_total_player_have_three_or_more_deposit) AS total_player_have_three_or_more_deposit ";
				//Total Player have 3 or more deposit Sum
				$where_total_player_have_three_or_more_deposit_amount .= "ESP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
				$where_total_player_have_three_or_more_deposit_amount .= ' AND ESP.deposit_count >= 3';
				$where_total_player_have_three_or_more_deposit_amount .= " AND ESTR.player_id = ESP.player_id";
				if( ! empty($arr['from_date']))
				{
					$where_total_player_have_three_or_more_deposit_amount .= ' AND ESP.created_date >= ' . $start_time;
				}
				if( ! empty($arr['from_date']))
				{
					$where_total_player_have_three_or_more_deposit_amount .= ' AND ESP.created_date < ' . $end_time;
				}
				$upline_query_string .= ",(SELECT SUM(ESTR.deposit_amount) AS total_player_have_three_or_more_deposit_amount FROM {$dbprefix}total_win_loss_report_month ESTR, {$dbprefix}players ESP where $where_total_player_have_three_or_more_deposit_amount) AS total_player_have_three_or_more_deposit_amount";
				$upline_query_string .= " FROM {$dbprefix}users MU ";
				if(empty($username))
				{
					$num = 1;
					$upline_query_string .= "WHERE MU.user_id = " . $this->session->userdata('root_user_id') . " LIMIT 1";
					$totalFiltered = 1;
				}else{
					$extract_string = "";
					$upline_query_total_string = $upline_query_string;
					$upline_query_total_string .= "WHERE MU.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' AND MU.upline = '{$username}' GROUP BY MU.user_id $extract_string";
					$upline_total_query = $this->db->query($upline_query_total_string);
					$totalFiltered = $upline_total_query->num_rows();
					$upline_total_query->free_result();
					$upline_query_string .= "WHERE MU.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' AND MU.upline = '{$username}' GROUP BY MU.user_id $extract_string LIMIT {$start}, {$limit} ";
				}
				$upline_query = $this->db->query($upline_query_string);
				if($upline_query->num_rows() > 0)
				{
					foreach($upline_query->result() as $upline_row)
					{
						$row = array();
						$row[] = $upline_row->user_id;
						$row[] = $this->lang->line(get_user_type($upline_row->user_type));
						$row[] = '<a href="javascript:void(0);" onclick="getDownline(\'' . $upline_row->username . '\', ' . $num . ')">' . $upline_row->username . '</a>';
						$row[] = ( ! empty($upline_row->upline) ? $upline_row->upline : '-');
						$row[] = number_format($upline_row->total_register_player, 0, '.', ',');
						$row[] = '<a href="javascript:void(0);" class="text-' . (($upline_row->total_player_have_deposit > 0) ? 'primary' : 'dark') . '" ' . (($upline_row->total_player_have_deposit > 0) ? 'onclick="getPlayerHaveDeposit(\'' . $upline_row->username . '\', ' . $num . ', '.REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT.')"' : '') . '>' .number_format($upline_row->total_player_have_deposit, 0, '.', ',') . '</a>';
						$row[] = number_format($upline_row->total_player_have_deposit_amount, 0, '.', ',');
						$row[] = (($upline_row->total_register_player > 0) ? number_format((($upline_row->total_player_have_deposit / $upline_row->total_register_player) * 100), 2, '.', ',').'%' : '0.00'.'%');
						$row[] = '<a href="javascript:void(0);" class="text-' . (($upline_row->total_player_have_one_deposit > 0) ? 'primary' : 'dark') . '" ' . (($upline_row->total_player_have_one_deposit > 0) ? 'onclick="getPlayerHaveDeposit(\'' . $upline_row->username . '\', ' . $num . ', '.REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT.')"' : '') . '>' .number_format($upline_row->total_player_have_one_deposit, 0, '.', ',') . '</a>';
						$row[] = number_format($upline_row->total_player_have_one_deposit_amount, 0, '.', ',');
						$row[] = (($upline_row->total_register_player > 0) ? number_format((($upline_row->total_player_have_one_deposit / $upline_row->total_register_player) * 100), 2, '.', ',').'%' : '0.00'.'%');
						$row[] = '<a href="javascript:void(0);" class="text-' . (($upline_row->total_player_have_two_or_more_deposit > 0) ? 'primary' : 'dark') . '" ' . (($upline_row->total_player_have_two_or_more_deposit > 0) ? 'onclick="getPlayerHaveDeposit(\'' . $upline_row->username . '\', ' . $num . ', '.REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT.')"' : '') . '>' .number_format($upline_row->total_player_have_two_or_more_deposit, 0, '.', ',') . '</a>';
						$row[] = number_format($upline_row->total_player_have_two_or_more_deposit_amount, 0, '.', ',');
						$row[] = (($upline_row->total_register_player > 0) ? number_format((($upline_row->total_player_have_two_or_more_deposit / $upline_row->total_register_player) * 100), 2, '.', ',').'%' : '0.00'.'%');
						$row[] = '<a href="javascript:void(0);" class="text-' . (($upline_row->total_player_have_three_or_more_deposit > 0) ? 'primary' : 'dark') . '" ' . (($upline_row->total_player_have_three_or_more_deposit > 0) ? 'onclick="getPlayerHaveDeposit(\'' . $upline_row->username . '\', ' . $num . ', '.REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT.')"' : '') . '>' .number_format($upline_row->total_player_have_three_or_more_deposit, 0, '.', ',') . '</a>';
						$row[] = number_format($upline_row->total_player_have_three_or_more_deposit_amount, 0, '.', ',');
						$row[] = (($upline_row->total_register_player > 0) ? number_format((($upline_row->total_player_have_three_or_more_deposit / $upline_row->total_register_player) * 100), 2, '.', ',').'%' : '0.00'.'%');
						$row[] = number_format($upline_row->total_register_player - $upline_row->total_player_have_deposit, 0, '.', ',');
						$row[] = (($upline_row->total_register_player > 0) ? number_format(((($upline_row->total_register_player - $upline_row->total_player_have_deposit) / $upline_row->total_register_player) * 100), 2, '.', ',').'%' : '0.00'.'%');
						$data[] = $row;
					}
				}
				//Output
				$json_data = array(
								"draw"            => intval($this->input->post('draw')), 
								"recordsFiltered" => intval($totalFiltered), 
								"data"            => $data,
								"csrfHash" 		  => $this->security->get_csrf_hash()					
							);
				echo json_encode($json_data);
				exit();
			}
		}
	}
	public function register_deposit_rate_downline($num = NULL, $username = NULL){
		if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT) == TRUE)
		{
			$data['num'] = $num;
			$data['username'] = $username;
			$data['type'] = 'downline';
			$html = $this->load->view('register_deposit_rate_report_table_downline', $data, TRUE);
			echo $html;
			exit();
		}
	}
	public function register_deposit_rate_downline_total($num = NULL, $username = NULL){
		if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT) == TRUE)
		{
			$arr = $this->session->userdata('search_register_deposit_rate');
			$userData = $this->user_model->get_user_data_by_username($username);
			$dbprefix = $this->db->dbprefix;
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => '',
				'total_data' => '',
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			$json['total_data'] = array(
				"total_register_count" => 0,
				"total_member_deposit" => 0,
				"total_member_deposit_amount" => 0,
				"total_member_deposit_rate" => 0,
				"total_first_deposit" => 0,
				"total_first_deposit_amount" => 0,
				"total_first_deposit_rate" => 0,
				"total_second_or_more_deposit" => 0,
				"total_second_or_more_deposit_amount" => 0,
				"total_second_or_more_deposit_rate" => 0,
				"total_third_or_more_deposit" => 0,
				"total_third_or_more_deposit_amount" => 0,
				"total_third_or_more_deposit_rate" => 0,
				"total_no_deposit" => 0,
				"total_churn_rate" => 0,
			);
			if(!empty($arr) && !empty($userData)){
				if( ! empty($arr['from_date']))
				{
					$json['status'] = EXIT_SUCCESS;
					$dbprefix = $this->db->dbprefix;
					$data = array();
					$start_date = $arr['from_date']."-01 00:00:00";
					$start_time = strtotime($start_date);
					$end_date	= date('Y-m-d 00:00:00', strtotime('first day of next month',$start_time));
					$end_time = strtotime($end_date);
					$where_total_register_player = "";
	 				$where_total_player_have_deposit = "";
	 				$where_total_player_have_one_deposit = "";
	 				$where_total_player_have_two_or_more_deposit = "";
	 				$where_total_player_have_three_or_more_deposit = "";
					$where_total_player_have_deposit_amount = "";
	 				$where_total_player_have_one_deposit_amount = "";
	 				$where_total_player_have_two_or_more_deposit_amount = "";
	 				$where_total_player_have_three_or_more_deposit_amount = "";
					$upline_query_string = "SELECT MU.user_id";
					//Total Register Player
					$where_total_register_player .= "AP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
					if( ! empty($arr['from_date']))
					{
						$where_total_register_player .= ' AND AP.created_date >= ' . $start_time;
					}
					if( ! empty($arr['from_date']))
					{
						$where_total_register_player .= ' AND AP.created_date < ' . $end_time;
					}
					$upline_query_string .= ",(SELECT COUNT(AP.player_id) AS total_register_player FROM {$dbprefix}players AP where $where_total_register_player) AS total_register_player ";
					//Total Player Have Deposit
					$where_total_player_have_deposit .= "BP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
					$where_total_player_have_deposit .= ' AND BP.deposit_count > 0';
					if( ! empty($arr['from_date']))
					{
						$where_total_player_have_deposit .= ' AND BP.created_date >= ' . $start_time;
					}
					if( ! empty($arr['from_date']))
					{
						$where_total_player_have_deposit .= ' AND BP.created_date < ' . $end_time;
					}
					$upline_query_string .= ",(SELECT COUNT(BP.player_id) AS total_player_have_deposit FROM {$dbprefix}players BP where $where_total_player_have_deposit) AS total_player_have_deposit ";
					//Total Player Have Deposit Sum
					$where_total_player_have_deposit_amount .= "BSP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
					$where_total_player_have_deposit_amount .= ' AND BSP.deposit_count > 0';
					$where_total_player_have_deposit_amount .= " AND BSTR.player_id = BSP.player_id";
					if( ! empty($arr['from_date']))
					{
						$where_total_player_have_deposit_amount .= ' AND BSP.created_date >= ' . $start_time;
					}
					if( ! empty($arr['from_date']))
					{
						$where_total_player_have_deposit_amount .= ' AND BSP.created_date < ' . $end_time;
					}
					$upline_query_string .= ",(SELECT SUM(BSTR.deposit_amount) AS total_player_have_deposit_amount FROM {$dbprefix}total_win_loss_report_month BSTR, {$dbprefix}players BSP where $where_total_player_have_deposit_amount) AS total_player_have_deposit_amount";
					//Total Player have one only deposit
					$where_total_player_have_one_deposit .= "CP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
					$where_total_player_have_one_deposit .= ' AND CP.deposit_count = 1';
					if( ! empty($arr['from_date']))
					{
						$where_total_player_have_one_deposit .= ' AND CP.created_date >= ' . $start_time;
					}
					if( ! empty($arr['from_date']))
					{
						$where_total_player_have_one_deposit .= ' AND CP.created_date < ' . $end_time;
					}
					$upline_query_string .= ",(SELECT COUNT(CP.player_id) AS total_player_have_one_deposit FROM {$dbprefix}players CP where $where_total_player_have_one_deposit) AS total_player_have_one_deposit ";
					//Total Player have one only deposit Sum
					$where_total_player_have_one_deposit_amount .= "CSP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
					$where_total_player_have_one_deposit_amount .= ' AND CSP.deposit_count = 1';
					$where_total_player_have_one_deposit_amount .= " AND CSTR.player_id = CSP.player_id";
					if( ! empty($arr['from_date']))
					{
						$where_total_player_have_one_deposit_amount .= ' AND CSP.created_date >= ' . $start_time;
					}
					if( ! empty($arr['from_date']))
					{
						$where_total_player_have_one_deposit_amount .= ' AND CSP.created_date < ' . $end_time;
					}
					$upline_query_string .= ",(SELECT SUM(CSTR.deposit_amount) AS total_player_have_one_deposit_amount FROM {$dbprefix}total_win_loss_report_month CSTR, {$dbprefix}players CSP where $where_total_player_have_one_deposit_amount) AS total_player_have_one_deposit_amount";
					//Total Player have 2 or more deposit
					$where_total_player_have_two_or_more_deposit .= "DP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
					$where_total_player_have_two_or_more_deposit .= ' AND DP.deposit_count = 2';
					if( ! empty($arr['from_date']))
					{
						$where_total_player_have_two_or_more_deposit .= ' AND DP.created_date >= ' . $start_time;
					}
					if( ! empty($arr['from_date']))
					{
						$where_total_player_have_two_or_more_deposit .= ' AND DP.created_date < ' . $end_time;
					}
					$upline_query_string .= ",(SELECT COUNT(DP.player_id) AS total_player_have_two_or_more_deposit FROM {$dbprefix}players DP where $where_total_player_have_two_or_more_deposit) AS total_player_have_two_or_more_deposit ";
					//Total Player have 2 or more deposit Sum
					$where_total_player_have_two_or_more_deposit_amount .= "DSP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
					$where_total_player_have_two_or_more_deposit_amount .= ' AND DSP.deposit_count = 2';
					$where_total_player_have_two_or_more_deposit_amount .= " AND DSTR.player_id = DSP.player_id";
					if( ! empty($arr['from_date']))
					{
						$where_total_player_have_two_or_more_deposit_amount .= ' AND DSP.created_date >= ' . $start_time;
					}
					if( ! empty($arr['from_date']))
					{
						$where_total_player_have_two_or_more_deposit_amount .= ' AND DSP.created_date < ' . $end_time;
					}
					$upline_query_string .= ",(SELECT SUM(DSTR.deposit_amount) AS total_player_have_two_or_more_deposit_amount FROM {$dbprefix}total_win_loss_report_month DSTR, {$dbprefix}players DSP where $where_total_player_have_two_or_more_deposit_amount) AS total_player_have_two_or_more_deposit_amount";
					//Total Player have 3 or more deposit
					$where_total_player_have_three_or_more_deposit .= "EP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
					$where_total_player_have_three_or_more_deposit .= ' AND EP.deposit_count >= 3';
					if( ! empty($arr['from_date']))
					{
						$where_total_player_have_three_or_more_deposit .= ' AND EP.created_date >= ' . $start_time;
					}
					if( ! empty($arr['from_date']))
					{
						$where_total_player_have_three_or_more_deposit .= ' AND EP.created_date < ' . $end_time;
					}
					$upline_query_string .= ",(SELECT COUNT(EP.player_id) AS total_player_have_three_or_more_deposit FROM {$dbprefix}players EP where $where_total_player_have_three_or_more_deposit) AS total_player_have_three_or_more_deposit ";
					//Total Player have 3 or more deposit Sum
					$where_total_player_have_three_or_more_deposit_amount .= "ESP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
					$where_total_player_have_three_or_more_deposit_amount .= ' AND ESP.deposit_count >= 3';
					$where_total_player_have_three_or_more_deposit_amount .= " AND ESTR.player_id = ESP.player_id";
					if( ! empty($arr['from_date']))
					{
						$where_total_player_have_three_or_more_deposit_amount .= ' AND ESP.created_date >= ' . $start_time;
					}
					if( ! empty($arr['from_date']))
					{
						$where_total_player_have_three_or_more_deposit_amount .= ' AND ESP.created_date < ' . $end_time;
					}
					$upline_query_string .= ",(SELECT SUM(ESTR.deposit_amount) AS total_player_have_three_or_more_deposit_amount FROM {$dbprefix}total_win_loss_report_month ESTR, {$dbprefix}players ESP where $where_total_player_have_three_or_more_deposit_amount) AS total_player_have_three_or_more_deposit_amount";
					$upline_query_string .= " FROM {$dbprefix}users MU ";
					$extract_string = "";
					if(empty($username))
					{
						$upline_query_string .= "WHERE MU.user_id = " . $this->session->userdata('root_user_id') . " LIMIT 1";
					}else{
						$upline_query_string .= "WHERE MU.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' AND MU.upline = '{$username}' GROUP BY MU.user_id";
					}
					$upline_query = $this->db->query($upline_query_string);
					if($upline_query->num_rows() > 0)
					{
						foreach($upline_query->result() as $upline_row)
						{
							$json['total_data']["total_register_count"] += $upline_row->total_register_player;
							$json['total_data']["total_member_deposit"] += $upline_row->total_player_have_deposit;
							$json['total_data']["total_first_deposit"] += $upline_row->total_player_have_one_deposit;
							$json['total_data']["total_second_or_more_deposit"] += $upline_row->total_player_have_two_or_more_deposit;
							$json['total_data']["total_third_or_more_deposit"] += $upline_row->total_player_have_three_or_more_deposit;
							$json['total_data']["total_member_deposit_amount"] += $upline_row->total_player_have_deposit_amount;
							$json['total_data']["total_first_deposit_amount"] += $upline_row->total_player_have_one_deposit_amount;
							$json['total_data']["total_second_or_more_deposit_amount"] += $upline_row->total_player_have_two_or_more_deposit_amount;
							$json['total_data']["total_third_or_more_deposit_amount"] += $upline_row->total_player_have_three_or_more_deposit_amount;
						}
					}
					$json['total_data']["total_member_deposit_rate"] = (($json['total_data']["total_register_count"] > 0) ? (($json['total_data']["total_member_deposit"] / $json['total_data']["total_register_count"]) * 100) : '0');
					$json['total_data']["total_first_deposit_rate"] = (($json['total_data']["total_register_count"] > 0) ? (($json['total_data']["total_first_deposit"] / $json['total_data']["total_register_count"]) * 100) : '0');
					$json['total_data']["total_second_or_more_deposit_rate"] = (($json['total_data']["total_register_count"] > 0) ? (($json['total_data']["total_second_or_more_deposit"] / $json['total_data']["total_register_count"]) * 100) : '0');
					$json['total_data']["total_third_or_more_deposit_rate"] = (($json['total_data']["total_register_count"] > 0) ? (($json['total_data']["total_third_or_more_deposit"] / $json['total_data']["total_register_count"]) * 100) : '0');
					$json['total_data']["total_no_deposit"] = $json['total_data']["total_register_count"] - $json['total_data']["total_member_deposit"];
					$json['total_data']["total_churn_rate"] = (($json['total_data']["total_register_count"] > 0) ? ((($json['total_data']["total_register_count"] - $json['total_data']["total_member_deposit"]) / $json['total_data']["total_register_count"]) * 100) : '0');
					//Output
					$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($json))
						->_display();
					exit();
				}
			}
		}
	}
	public function register_deposit_rate_player_table($username = NULL, $num = NULL, $type = NULL){
		if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT) == TRUE)
		{
			$arr = $this->session->userdata('search_register_deposit_rate');
			if(!empty($arr)){
				$userData = $this->user_model->get_user_data_by_username($username);
				if(!empty($arr) && !empty($userData)){
					if( ! empty($arr['from_date']))
					{
						$data['num'] = $num;
						$data['username'] = $username;
						$data['type'] = $type;
						$this->load->view('register_deposit_rate_player_table', $data);
					}
				}
			}	
		}
	}
	public function register_deposit_rate_player_listing($username = NULL, $num = NULL, $type = NULL){
		if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT) == TRUE)
		{
			$arr = $this->session->userdata('search_register_deposit_rate');
			if(!empty($arr)){
				$userData = $this->user_model->get_user_data_by_username($username);
				if(!empty($arr) && !empty($userData)){
					if( ! empty($arr['from_date']))
					{
						$limit = trim($this->input->post('length', TRUE));
						$start = trim($this->input->post("start", TRUE));
						$order = $this->input->post("order", TRUE);
						$columns = array( 
							0 => 'P.player_id',
							1 => 'P.upline',
							2 => 'P.username',
							3 => 'total_deposit',
							4 => 'total_deposit_amount',
						);
						$col = 0;
						$dir = "";
						if( ! empty($order))
						{
							foreach($order as $o)
							{
								$col = $o['column'];
								$dir = $o['dir'];
							}
						}
						if($dir != "asc" && $dir != "desc")
						{
							$dir = "desc";
						}
						if( ! isset($columns[$col]))
						{
							$order = $columns[0];
						}
						else
						{
							$order = $columns[$col];
						}
						$dbprefix = $this->db->dbprefix;
						$data = array();
						$start_date = $arr['from_date']."-01 00:00:00";
						$start_time = strtotime($start_date);
						$end_date	= date('Y-m-d 00:00:00', strtotime('first day of next month',$start_time));
						$end_time = strtotime($end_date);
						$query_string = "SELECT P.player_id,P.upline,P.username";
						$condition_query_string = "";
						$where = "";
						$condition_query_string .= " P.player_id = BSP.player_id";
						$condition_query_string .= " AND BSTR.player_id = BSP.player_id";
						$query_string .= ",(SELECT SUM(BSTR.deposit_count) AS total_deposit FROM {$dbprefix}total_win_loss_report_month BSTR, {$dbprefix}players BSP where $condition_query_string) AS total_deposit,(SELECT SUM(BSTR.deposit_amount) AS total_deposit_amount FROM {$dbprefix}total_win_loss_report_month BSTR, {$dbprefix}players BSP where $condition_query_string) AS total_deposit_amount";
						$where .= " P.upline_ids LIKE '%," . $userData['user_id'] . ",%'";
						if( ! empty($arr['from_date']))
						{
							$where .= ' AND P.created_date >= ' . $start_time;
						}
						if( ! empty($arr['from_date']))
						{
							$where .= ' AND P.created_date < ' . $end_time;
						}
						if($type == REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT){
							$where .= ' AND P.deposit_count > 0';	
						}else if($type == REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT){
							$where .= ' AND P.deposit_count = 1';	
						}else if($type == REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT){
							$where .= ' AND P.deposit_count = 2';	
						}else if($type == REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT){
							$where .= ' AND P.deposit_count >= 3';	
						}
						$query_string .= " FROM {$dbprefix}players P WHERE $where";
						$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
						$query = $this->db->query($query_string.$query_string_2);
						if($query->num_rows() > 0)
						{
							$posts = $query->result();  
						}
						$query->free_result();
						//Get total records
						$query = $this->db->query($query_string);
						$totalFiltered = $query->num_rows();
						$query->free_result();
						//Prepare data
						$data = array();
						if(!empty($posts))
						{
							foreach ($posts as $post)
							{
								$row = array();
								$row[] = $post->player_id;
								$row[] = $post->upline;
								$row[] = $post->username;
								$row[] = $post->total_deposit;
								$row[] = '<span class="text-' . (($post->total_deposit_amount >= 0) ? ($post->total_deposit_amount == 0) ? 'dark' : 'dark' : 'dark') . '">' . number_format($post->total_deposit_amount, 2, '.', ',') . '</span>';
								$data[] = $row;
							}
						}
						//Output
						$json_data = array(
							"draw"            => intval($this->input->post('draw')), 
							"recordsFiltered" => intval($totalFiltered), 
							"data"            => $data,
							"csrfHash" 		  => $this->security->get_csrf_hash()				
						);
						echo json_encode($json_data); 
						exit();
					}
				}
			}	
		}
	}
	public function register_deposit_rate_player_total($username = NULL, $num = NULL, $type = NULL){
		if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT) == TRUE)
		{
			$arr = $this->session->userdata('search_register_deposit_rate');
			if(!empty($arr)){
				$userData = $this->user_model->get_user_data_by_username($username);
				if(!empty($arr) && !empty($userData)){
					if( ! empty($arr['from_date']))
					{
						$json['status'] = EXIT_SUCCESS;
						$json['total_data'] = array(
							'total_register_count' => 0,
							'total_member_deposit' => 0, 
							'total_member_deposit_amount' => 0,
						);
						$col = 0;
						$dir = "";
						$dbprefix = $this->db->dbprefix;
						$data = array();
						$start_date = $arr['from_date']."-01 00:00:00";
						$start_time = strtotime($start_date);
						$end_date	= date('Y-m-d 00:00:00', strtotime('first day of next month',$start_time));
						$end_time = strtotime($end_date);
						$total_query_string = "SELECT COUNT(DataSum.player_id) AS grand_total_player,SUM(DataSum.total_deposit) AS grand_total_deposit,SUM(DataSum.total_deposit_amount) AS grand_total_deposit_amount FROM (SELECT P.player_id";
						$condition_query_string = "";
						$where = "";
						$condition_query_string .= " P.player_id = BSP.player_id";
						$condition_query_string .= " AND BSTR.player_id = BSP.player_id";
						$total_query_string .= ",(SELECT SUM(BSTR.deposit_count) AS total_deposit FROM {$dbprefix}total_win_loss_report_month BSTR, {$dbprefix}players BSP where $condition_query_string) AS total_deposit,(SELECT SUM(BSTR.deposit_amount) AS total_deposit_amount FROM {$dbprefix}total_win_loss_report_month BSTR, {$dbprefix}players BSP where $condition_query_string) AS total_deposit_amount";
						$where .= " P.upline_ids LIKE '%," . $userData['user_id'] . ",%'";
						if( ! empty($arr['from_date']))
						{
							$where .= ' AND P.created_date >= ' . $start_time;
						}
						if( ! empty($arr['from_date']))
						{
							$where .= ' AND P.created_date < ' . $end_time;
						}
						if($type == REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT){
							$where .= ' AND P.deposit_count > 0';	
						}else if($type == REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT){
							$where .= ' AND P.deposit_count = 1';	
						}else if($type == REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT){
							$where .= ' AND P.deposit_count = 2';	
						}else if($type == REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT){
							$where .= ' AND P.deposit_count >= 3';	
						}
						$total_query_string .= " FROM {$dbprefix}players P WHERE $where ) AS DataSum";
						$total_query = $this->db->query($total_query_string);
						if($total_query->num_rows() > 0)
						{
							foreach($total_query->result() as $row)
							{
								$json['total_data'] = array(
									'total_register_count' => (($row->grand_total_player > 0) ? $row->grand_total_player : 0),
									'total_member_deposit' => (($row->grand_total_deposit > 0) ? $row->grand_total_deposit : 0), 
									'total_member_deposit_amount' => (($row->grand_total_deposit_amount > 0) ? $row->grand_total_deposit_amount : 0), 
								);
							}
							$total_query->free_result();
						}
						//Output
						$this->output
								->set_status_header(200)
								->set_content_type('application/json', 'utf-8')
								->set_output(json_encode($json))
								->_display();
						exit();
					}
				}
			}	
		}
	}
	public function register_deposit_rate_agent_listing($username = NULL, $num = NULL, $type = NULL){
		if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT) == TRUE)
		{
			$arr = $this->session->userdata('search_register_deposit_rate');
			if(!empty($arr)){
				$userData = $this->user_model->get_user_data_by_username($username);
				if(!empty($arr) && !empty($userData)){
					if( ! empty($arr['from_date']))
					{
						$limit = trim($this->input->post('length', TRUE));
						$start = trim($this->input->post("start", TRUE));
						$order = $this->input->post("order", TRUE);
						$columns = array( 
							0 => 'upline',
							1 => 'grand_total_player',
							2 => 'grand_total_deposit',
							3 => 'total_deposit_amount',
						);
						$col = 0;
						$dir = "";
						if( ! empty($order))
						{
							foreach($order as $o)
							{
								$col = $o['column'];
								$dir = $o['dir'];
							}
						}
						if($dir != "asc" && $dir != "desc")
						{
							$dir = "desc";
						}
						if( ! isset($columns[$col]))
						{
							$order = $columns[0];
						}
						else
						{
							$order = $columns[$col];
						}
						$dbprefix = $this->db->dbprefix;
						$data = array();
						$start_date = $arr['from_date']."-01 00:00:00";
						$start_time = strtotime($start_date);
						$end_date	= date('Y-m-d 00:00:00', strtotime('first day of next month',$start_time));
						$end_time = strtotime($end_date);
						$query_string = "SELECT DataSum.upline,COUNT(DataSum.player_id) AS grand_total_player,SUM(DataSum.total_deposit) AS grand_total_deposit,SUM(DataSum.total_deposit_amount) AS grand_total_deposit_amount FROM (SELECT P.player_id,P.upline";
						$condition_query_string = "";
						$where = "";
						$condition_query_string .= " P.player_id = BSP.player_id";
						$condition_query_string .= " AND BSTR.player_id = BSP.player_id";
						$query_string .= ",(SELECT SUM(BSTR.deposit_count) AS total_deposit FROM {$dbprefix}total_win_loss_report_month BSTR, {$dbprefix}players BSP where $condition_query_string) AS total_deposit,(SELECT SUM(BSTR.deposit_amount) AS total_deposit_amount FROM {$dbprefix}total_win_loss_report_month BSTR, {$dbprefix}players BSP where $condition_query_string) AS total_deposit_amount";
						$where .= " P.upline_ids LIKE '%," . $userData['user_id'] . ",%'";
						if( ! empty($arr['from_date']))
						{
							$where .= ' AND P.created_date >= ' . $start_time;
						}
						if( ! empty($arr['from_date']))
						{
							$where .= ' AND P.created_date < ' . $end_time;
						}
						if($type == REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT){
							$where .= ' AND P.deposit_count > 0';	
						}else if($type == REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT){
							$where .= ' AND P.deposit_count = 1';	
						}else if($type == REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT){
							$where .= ' AND P.deposit_count = 2';	
						}else if($type == REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT){
							$where .= ' AND P.deposit_count >= 3';	
						}
						$query_string .= " FROM {$dbprefix}players P WHERE $where ) AS DataSum GROUP BY DataSum.upline";
						$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
						$query = $this->db->query($query_string.$query_string_2);
						if($query->num_rows() > 0)
						{
							$posts = $query->result();  
						}
						$query->free_result();
						//Get total records
						$query = $this->db->query($query_string);
						$totalFiltered = $query->num_rows();
						$query->free_result();
						//Prepare data
						$data = array();
						if(!empty($posts))
						{
							foreach ($posts as $post)
							{
								$row = array();
								$row[] = $post->upline;
								$row[] = $post->grand_total_player;
								$row[] = $post->grand_total_deposit;
								$row[] = '<span class="text-' . (($post->grand_total_deposit_amount >= 0) ? ($post->grand_total_deposit_amount == 0) ? 'dark' : 'dark' : 'dark') . '">' . number_format($post->grand_total_deposit_amount, 2, '.', ',') . '</span>';
								$data[] = $row;
							}
						}
						//Output
						$json_data = array(
							"draw"            => intval($this->input->post('draw')), 
							"recordsFiltered" => intval($totalFiltered), 
							"data"            => $data,
							"csrfHash" 		  => $this->security->get_csrf_hash()				
						);
						echo json_encode($json_data); 
						exit();
					}
				}
			}	
		}
	}
	/*************************REGISTER DEPOSIT RATE YEARLY*******************************************/
	public function register_deposit_rate_yearly(){
		if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_YEARLY_REPORT) == TRUE)
		{
			$this->save_current_url('report/register_deposit_rate_yearly');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_register_deposit_rate_yearly_report');
			$this->session->unset_userdata('search_register_deposit_rate_yearly');
			$this->load->view('register_deposit_rate_yearly_view', $data);
		}
	}
	public function register_deposit_rate_yearly_search(){
		if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_YEARLY_REPORT) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR,
					'msg'=> array(
						'general_error' => '',
						'from_date_error' => '',
						'to_date_error' => '',
						'from_year_error' => '',
					),
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			//Set form rules
			$config = array(
				array(
					'field' => 'from_year',
					'label' => strtolower($this->lang->line('label_year')),
					'rules' => 'trim|required|callback_year_check',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_datetime_format'),
						'year_check' => $this->lang->line('error_invalid_datetime_format')
					)
				)
			);
			if($this->input->post('from_date', TRUE) != ""){
				$configAdd = array(
					'field' => 'from_date',
					'label' => strtolower($this->lang->line('label_register_from')),
					'rules' => 'trim|required|callback_full_datetime_check',
					'errors' => array(
							'required' => $this->lang->line('error_invalid_datetime_format'),
							'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
					)
				);
				array_push($config, $configAdd);
			}
			if($this->input->post('to_date', TRUE) != ""){
				$configAdd = array(
					'field' => 'to_date',
					'label' => strtolower($this->lang->line('label_register_to')),
					'rules' => 'trim|required|callback_full_datetime_check',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_datetime_format'),
						'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
					)
				);
				array_push($config, $configAdd);
			}
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$data = array( 
					'from_year' => trim($this->input->post('from_year', TRUE)),
					'from_date' => trim($this->input->post('from_date', TRUE)),
					'to_date' => trim($this->input->post('to_date', TRUE)),
					'excludezero' =>  trim($this->input->post('excludezero', TRUE)),
					'username' => trim($this->input->post('username', TRUE)),
					'agent' => trim($this->input->post('agent', TRUE)),
					'type' => trim($this->input->post('type', TRUE)),
					'count_deposit' => trim($this->input->post('count_deposit', TRUE)),
				);
				$this->session->set_userdata('search_register_deposit_rate_yearly', $data);
				$json['status'] = EXIT_SUCCESS;
			}
			else 
			{
				$json['msg']['from_year_error'] = form_error('from_year');
				$json['msg']['from_date_error'] = form_error('from_date');
				$json['msg']['to_date_error'] = form_error('to_date');
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function register_deposit_rate_yearly_listing(){
		if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_YEARLY_REPORT) == TRUE)
		{
			$arr = $this->session->userdata('search_register_deposit_rate_yearly');
			if( ! empty($arr['from_year'])){
				$limit = trim($this->input->post('length', TRUE));
				$start = trim($this->input->post("start", TRUE));
				$order = $this->input->post("order", TRUE);
				$dbprefix = $this->db->dbprefix;
				$where = "";
				$where_win_loss = "";
				if( ! empty($arr['agent']))
				{
					$where = "WHERE P.player_id = 'ABC'";
					$agent = $this->user_model->get_user_data_by_username($arr['agent']);
					if(!empty($agent)){
						$response_upline = $this->user_model->get_downline_data($agent['username']);
						if(!empty($response_upline)){
							$where = "WHERE P.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";
						}
					}
				}else{
					$where = "WHERE P.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";
				}
				if( ! empty($arr['username']))
				{
					$where .= " AND P.username = '" . $arr['username'] . "'";	
				}
				if(isset($arr['from_date']))
				{
					if( ! empty($arr['from_date'])){
						$where .= ' AND P.created_date >= ' . strtotime($arr['from_date']);
					}
				}
				if( ! empty($arr['to_date']))
				{
					if( ! empty($arr['to_date'])){
						$where .= ' AND P.created_date <= ' . strtotime($arr['to_date']);
					}
				}
				if($arr['count_deposit'] !== "")
				{
					if($arr['type'] == SELECTION_TYPE_FIXED){
						$where .= " AND P.deposit_count = '" . $arr['count_deposit'] . "'";		
					}else{
						$where .= " AND P.deposit_count > '" . $arr['count_deposit'] . "'";	
					}
				}
				$columns = array(
					'P.player_id',
					'P.username',
					'P.level_id',
					'P.deposit_count',
				);
				$col = 0;
				$dir = "";
				if( ! empty($order))
				{
					foreach($order as $o)
					{
						$col = $o['column'];
						$dir = $o['dir'];
					}
				}
				if($dir != "asc" && $dir != "desc")
				{
					$dir = "desc";
				}
				if( ! isset($columns[$col]))
				{
					$order = $columns[0];
				}
				else
				{
					$order = $columns[$col];
				}
				$select = implode(',', $columns);
				$posts = NULL;
				$win_loss_posts = NULL;
				$query_string = "SELECT {$select} FROM {$dbprefix}players P $where";
				$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
				$query = $this->db->query($query_string . $query_string_2);
				if($query->num_rows() > 0)
				{
					$posts = $query->result();  
				}
				$query->free_result();
				$query = $this->db->query($query_string);
				$totalFiltered = $query->num_rows();
				$query->free_result();
				//Prepare data
				$data = array();
				$player_list = array();
				$winloss_monthly_list = array();
				$max_level = 0;
				$level_data = array();
				$jan_datetime = strtotime($arr['from_year']."-01-01");
				$feb_datetime = strtotime($arr['from_year']."-02-01");
				$mar_datetime = strtotime($arr['from_year']."-03-01");
				$apr_datetime = strtotime($arr['from_year']."-04-01");
				$may_datetime = strtotime($arr['from_year']."-05-01");
				$jun_datetime = strtotime($arr['from_year']."-06-01");
				$jul_datetime = strtotime($arr['from_year']."-07-01");
				$aug_datetime = strtotime($arr['from_year']."-08-01");
				$sep_datetime = strtotime($arr['from_year']."-09-01");
				$oct_datetime = strtotime($arr['from_year']."-10-01");
				$nov_datetime = strtotime($arr['from_year']."-11-01");
				$dec_datetime = strtotime($arr['from_year']."-12-01");
				if(!empty($posts))
				{
					$level_data = $this->level_model->get_higest_level();
					if(!empty($level_data)){
						$max_level = $level_data['level_number'];
					}
					foreach ($posts as $post)
					{
						$player_list[] = $post->player_id;
						$winloss_monthly_list[$post->player_id][$jan_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);
						$winloss_monthly_list[$post->player_id][$feb_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);
						$winloss_monthly_list[$post->player_id][$mar_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);
						$winloss_monthly_list[$post->player_id][$apr_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);
						$winloss_monthly_list[$post->player_id][$may_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);
						$winloss_monthly_list[$post->player_id][$jun_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);
						$winloss_monthly_list[$post->player_id][$jul_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);
						$winloss_monthly_list[$post->player_id][$aug_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);
						$winloss_monthly_list[$post->player_id][$sep_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);
						$winloss_monthly_list[$post->player_id][$oct_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);
						$winloss_monthly_list[$post->player_id][$nov_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);
						$winloss_monthly_list[$post->player_id][$dec_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);
						$winloss_monthly_list[$post->player_id]['total_deposit_amount'] = 0;
						$winloss_monthly_list[$post->player_id]['total_win_loss_amount'] = 0;
					}
					if(!empty($player_list)){
						$player_ids = '"'.implode('","', $player_list).'"';
						$where_win_loss .= "WHERE player_id IN(" . $player_ids . ")";
						$where_win_loss .= " AND report_date >= ".$jan_datetime;
						$where_win_loss .= " AND report_date <= ".$dec_datetime;
						$query_win_loss_string = "SELECT player_id, deposit_amount, win_loss, report_date FROM {$dbprefix}total_win_loss_report_month $where_win_loss";
						$query_win_loss = $this->db->query($query_win_loss_string);
						if($query_win_loss->num_rows() > 0)
						{
							$win_loss_posts = $query_win_loss->result();
							foreach($win_loss_posts as $win_loss_post){
								$winloss_monthly_list[$win_loss_post->player_id][$win_loss_post->report_date]['deposit_amount'] += $win_loss_post->deposit_amount;
								$winloss_monthly_list[$win_loss_post->player_id][$win_loss_post->report_date]['win_loss'] += $win_loss_post->win_loss;
								$winloss_monthly_list[$win_loss_post->player_id]['total_deposit_amount'] += $win_loss_post->deposit_amount;
								$winloss_monthly_list[$win_loss_post->player_id]['total_win_loss_amount'] += $win_loss_post->win_loss;
							}
						}
						$query_win_loss->free_result();
					}
				}
				if(!empty($posts))
				{
					foreach ($posts as $post)
					{
						$level = "";
						for($i=1;$i<$max_level;$i++){
							if($post->level_id > $i){
								$level .= '<i class="fas fa-star nav-icon text-warning"></i>';
							}else{
								$level .= '<i class="fas fa-star nav-icon text-gray"></i>';
							}
						}
						$row = array();
						$row[] = $post->player_id;
						$row[] = $post->username;
						$row[] = $level;
						$row[] = '<span class="text-' . (($post->deposit_count >= 0) ? ($post->deposit_count == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($post->deposit_count, 0, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$jan_datetime]['deposit_amount'] >= 0) ? ($winloss_monthly_list[$post->player_id][$jan_datetime]['deposit_amount'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$jan_datetime]['deposit_amount'], 0, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$jan_datetime]['win_loss'] >= 0) ? ($winloss_monthly_list[$post->player_id][$jan_datetime]['win_loss'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$jan_datetime]['win_loss'], 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$feb_datetime]['deposit_amount'] >= 0) ? ($winloss_monthly_list[$post->player_id][$feb_datetime]['deposit_amount'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$feb_datetime]['deposit_amount'], 0, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$feb_datetime]['win_loss'] >= 0) ? ($winloss_monthly_list[$post->player_id][$feb_datetime]['win_loss'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$feb_datetime]['win_loss'], 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$mar_datetime]['deposit_amount'] >= 0) ? ($winloss_monthly_list[$post->player_id][$mar_datetime]['deposit_amount'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$mar_datetime]['deposit_amount'], 0, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$mar_datetime]['win_loss'] >= 0) ? ($winloss_monthly_list[$post->player_id][$mar_datetime]['win_loss'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$mar_datetime]['win_loss'], 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$apr_datetime]['deposit_amount'] >= 0) ? ($winloss_monthly_list[$post->player_id][$apr_datetime]['deposit_amount'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$apr_datetime]['deposit_amount'], 0, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$apr_datetime]['win_loss'] >= 0) ? ($winloss_monthly_list[$post->player_id][$apr_datetime]['win_loss'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$apr_datetime]['win_loss'], 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$may_datetime]['deposit_amount'] >= 0) ? ($winloss_monthly_list[$post->player_id][$may_datetime]['deposit_amount'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$may_datetime]['deposit_amount'], 0, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$may_datetime]['win_loss'] >= 0) ? ($winloss_monthly_list[$post->player_id][$may_datetime]['win_loss'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$may_datetime]['win_loss'], 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$jun_datetime]['deposit_amount'] >= 0) ? ($winloss_monthly_list[$post->player_id][$jun_datetime]['deposit_amount'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$jun_datetime]['deposit_amount'], 0, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$jun_datetime]['win_loss'] >= 0) ? ($winloss_monthly_list[$post->player_id][$jun_datetime]['win_loss'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$jun_datetime]['win_loss'], 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$jul_datetime]['deposit_amount'] >= 0) ? ($winloss_monthly_list[$post->player_id][$jul_datetime]['deposit_amount'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$jul_datetime]['deposit_amount'], 0, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$jul_datetime]['win_loss'] >= 0) ? ($winloss_monthly_list[$post->player_id][$jul_datetime]['win_loss'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$jul_datetime]['win_loss'], 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$aug_datetime]['deposit_amount'] >= 0) ? ($winloss_monthly_list[$post->player_id][$aug_datetime]['deposit_amount'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$aug_datetime]['deposit_amount'], 0, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$aug_datetime]['win_loss'] >= 0) ? ($winloss_monthly_list[$post->player_id][$aug_datetime]['win_loss'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$aug_datetime]['win_loss'], 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$sep_datetime]['deposit_amount'] >= 0) ? ($winloss_monthly_list[$post->player_id][$sep_datetime]['deposit_amount'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$sep_datetime]['deposit_amount'], 0, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$sep_datetime]['win_loss'] >= 0) ? ($winloss_monthly_list[$post->player_id][$sep_datetime]['win_loss'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$sep_datetime]['win_loss'], 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$oct_datetime]['deposit_amount'] >= 0) ? ($winloss_monthly_list[$post->player_id][$oct_datetime]['deposit_amount'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$oct_datetime]['deposit_amount'], 0, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$oct_datetime]['win_loss'] >= 0) ? ($winloss_monthly_list[$post->player_id][$oct_datetime]['win_loss'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$oct_datetime]['win_loss'], 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$nov_datetime]['deposit_amount'] >= 0) ? ($winloss_monthly_list[$post->player_id][$nov_datetime]['deposit_amount'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$nov_datetime]['deposit_amount'], 0, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$nov_datetime]['win_loss'] >= 0) ? ($winloss_monthly_list[$post->player_id][$nov_datetime]['win_loss'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$nov_datetime]['win_loss'], 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$dec_datetime]['deposit_amount'] >= 0) ? ($winloss_monthly_list[$post->player_id][$dec_datetime]['deposit_amount'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$dec_datetime]['deposit_amount'], 0, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id][$dec_datetime]['win_loss'] >= 0) ? ($winloss_monthly_list[$post->player_id][$dec_datetime]['win_loss'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id][$dec_datetime]['win_loss'], 2, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id]['total_deposit_amount'] >= 0) ? ($winloss_monthly_list[$post->player_id]['total_deposit_amount'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id]['total_deposit_amount'], 0, '.', ',') . '</span>';
						$row[] = '<span class="text-' . (($winloss_monthly_list[$post->player_id]['total_win_loss_amount'] >= 0) ? ($winloss_monthly_list[$post->player_id]['total_win_loss_amount'] == 0) ? 'dark' : 'primary' : 'danger') . '">' . number_format($winloss_monthly_list[$post->player_id]['total_win_loss_amount'], 2, '.', ',') . '</span>';
						$data[] = $row;
					}
				}
				//Output
				$json_data = array(
					"draw"            => intval($this->input->post('draw')), 
					"recordsFiltered" => intval($totalFiltered), 
					"data"            => $data,
					"csrfHash" 		  => $this->security->get_csrf_hash()				
				);
				echo json_encode($json_data); 
				exit();
			}
		}
	}
	public function register_deposit_rate_yearly_total(){
    	if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_YEARLY_REPORT) == TRUE)
		{
			$dbprefix = $this->db->dbprefix;
			$where = "";
			$where_win_loss = "";
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => '',
				'total_data' => '',
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			$json['total_data'] = array(
				'deposit_value_jan' => 0,
				'win_loss_value_jan' => 0,
				'deposit_value_feb' => 0,
				'win_loss_value_feb' => 0,
				'deposit_value_mar' => 0,
				'win_loss_value_mar' => 0,
				'deposit_value_apr' => 0,
				'win_loss_value_apr' => 0,
				'deposit_value_may' => 0,
				'win_loss_value_may' => 0,
				'deposit_value_jun' => 0,
				'win_loss_value_jun' => 0,
				'deposit_value_jul' => 0,
				'win_loss_value_jul' => 0,
				'deposit_value_aug' => 0,
				'win_loss_value_aug' => 0,
				'deposit_value_sep' => 0,
				'win_loss_value_sep' => 0,
				'deposit_value_oct' => 0,
				'win_loss_value_oct' => 0,
				'deposit_value_nov' => 0,
				'win_loss_value_nov' => 0,
				'deposit_value_dec' => 0,
				'win_loss_value_dec' => 0,
				'deposit_value_total' => 0,
				'win_loss_value_total' => 0,
				'deposit_value_other' => 0,
				'win_loss_value_total' => 0,
			);
			$arr = $this->session->userdata('search_register_deposit_rate_yearly');
			if( ! empty($arr['from_year'])){
				$json['status'] = EXIT_SUCCESS;
				if( ! empty($arr['agent']))
				{
					$where = "WHERE P.player_id = 'ABC'";
					$agent = $this->user_model->get_user_data_by_username($arr['agent']);
					if(!empty($agent)){
						$response_upline = $this->user_model->get_downline_data($agent['username']);
						if(!empty($response_upline)){
							$where = " AND P.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";
						}
					}
				}else{
					$where = "WHERE P.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";
				}
				if( ! empty($arr['username']))
				{
					$where .= "WHERE P.username = '" . $arr['username'] . "'";	
				}
				if( ! empty($arr['username']))
				{
					$where .= " AND P.username = '" . $arr['username'] . "'";	
				}
				if(isset($arr['from_date']))
				{
					if( ! empty($arr['from_date'])){
						$where .= ' AND P.created_date >= ' . strtotime($arr['from_date']);
					}
				}
				if( ! empty($arr['to_date']))
				{
					if( ! empty($arr['to_date'])){
						$where .= ' AND P.created_date <= ' . strtotime($arr['to_date']);
					}
				}
				if($arr['count_deposit'] !== "")
				{
					if($arr['type'] == SELECTION_TYPE_FIXED){
						$where .= " AND P.deposit_count = '" . $arr['count_deposit'] . "'";		
					}else{
						$where .= " AND P.deposit_count > '" . $arr['count_deposit'] . "'";	
					}
				}
				$jan_datetime = strtotime($arr['from_year']."-01-01");
				$feb_datetime = strtotime($arr['from_year']."-02-01");
				$mar_datetime = strtotime($arr['from_year']."-03-01");
				$apr_datetime = strtotime($arr['from_year']."-04-01");
				$may_datetime = strtotime($arr['from_year']."-05-01");
				$jun_datetime = strtotime($arr['from_year']."-06-01");
				$jul_datetime = strtotime($arr['from_year']."-07-01");
				$aug_datetime = strtotime($arr['from_year']."-08-01");
				$sep_datetime = strtotime($arr['from_year']."-09-01");
				$oct_datetime = strtotime($arr['from_year']."-10-01");
				$nov_datetime = strtotime($arr['from_year']."-11-01");
				$dec_datetime = strtotime($arr['from_year']."-12-01");
				$other_datetime = "";
				$posts = NULL;
				$win_loss_posts = NULL;
				$query_string = "SELECT P.player_id FROM {$dbprefix}players P $where";
				$query = $this->db->query($query_string);
				if($query->num_rows() > 0)
				{
					$posts = $query->result();  
				}
				$query->free_result();
				if(!empty($posts))
				{
					foreach ($posts as $post)
					{
						$player_list[] = $post->player_id;
					}
					if(!empty($player_list)){
						$player_ids = '"'.implode('","', $player_list).'"';
						$where_win_loss .= "WHERE player_id IN(" . $player_ids . ")";
						$where_win_loss .= " AND report_date >= ".$jan_datetime;
						$where_win_loss .= " AND report_date <= ".$dec_datetime;
						$query_win_loss_string = "SELECT player_id, deposit_count, deposit_amount, win_loss, report_date FROM {$dbprefix}total_win_loss_report_month $where_win_loss";
						$query_win_loss = $this->db->query($query_win_loss_string);
						if($query_win_loss->num_rows() > 0)
						{
							$win_loss_posts = $query_win_loss->result();
							foreach($win_loss_posts as $win_loss_post){
								$json['total_data']['total_register_count'] += $win_loss_post->deposit_count;
								$json['total_data']['deposit_value_total'] += $win_loss_post->deposit_amount;
								$json['total_data']['win_loss_value_total'] += $win_loss_post->win_loss;
								switch($win_loss_post->report_date)
								{
									case $jan_datetime: $json['total_data']['deposit_value_jan'] += $win_loss_post->deposit_amount; $json['total_data']['win_loss_value_jan'] += $win_loss_post->win_loss; break;
									case $feb_datetime: $json['total_data']['deposit_value_feb'] += $win_loss_post->deposit_amount; $json['total_data']['win_loss_value_feb'] += $win_loss_post->win_loss; break;
									case $mar_datetime: $json['total_data']['deposit_value_mar'] += $win_loss_post->deposit_amount; $json['total_data']['win_loss_value_mar'] += $win_loss_post->win_loss; break;
									case $apr_datetime: $json['total_data']['deposit_value_apr'] += $win_loss_post->deposit_amount; $json['total_data']['win_loss_value_apr'] += $win_loss_post->win_loss; break;
									case $may_datetime: $json['total_data']['deposit_value_may'] += $win_loss_post->deposit_amount; $json['total_data']['win_loss_value_may'] += $win_loss_post->win_loss; break;
									case $jun_datetime: $json['total_data']['deposit_value_jun'] += $win_loss_post->deposit_amount; $json['total_data']['win_loss_value_jun'] += $win_loss_post->win_loss; break;
									case $jul_datetime: $json['total_data']['deposit_value_jul'] += $win_loss_post->deposit_amount; $json['total_data']['win_loss_value_jul'] += $win_loss_post->win_loss; break;
									case $aug_datetime: $json['total_data']['deposit_value_aug'] += $win_loss_post->deposit_amount; $json['total_data']['win_loss_value_aug'] += $win_loss_post->win_loss; break;
									case $sep_datetime: $json['total_data']['deposit_value_sep'] += $win_loss_post->deposit_amount; $json['total_data']['win_loss_value_sep'] += $win_loss_post->win_loss; break;
									case $oct_datetime: $json['total_data']['deposit_value_oct'] += $win_loss_post->deposit_amount; $json['total_data']['win_loss_value_oct'] += $win_loss_post->win_loss; break;
									case $nov_datetime: $json['total_data']['deposit_value_nov'] += $win_loss_post->deposit_amount; $json['total_data']['win_loss_value_nov'] += $win_loss_post->win_loss; break;
									case $dec_datetime: $json['total_data']['deposit_value_dec'] += $win_loss_post->deposit_amount; $json['total_data']['win_loss_value_dec'] += $win_loss_post->win_loss; break;
									default: $other_datetime = "";break;
								}
							}
						}
						$query_win_loss->free_result();
					}
				}
				//Output
				$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($json))
						->_display();
				exit();
			}
		}
	}
	/*************************TAG PROCESS REPORT*******************************************/
	public function tag_process()
	{
		if(permission_validation(PERMISSION_TAG_PROCESS_REPORT) == TRUE)
		{
			$this->save_current_url('report/tag_process');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_tag_process_report');
			$this->session->unset_userdata('search_report_tag_process');
			$this->load->view('tag_process_report_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function tag_process_search(){
		if(permission_validation(PERMISSION_TAG_PROCESS_REPORT) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => '',
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			//Set form rules
			$config = array(
							array(
									'field' => 'from_date',
									'label' => strtolower($this->lang->line('label_from_date')),
									'rules' => 'trim|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							),
							array(
									'field' => 'to_date',
									'label' => strtolower($this->lang->line('label_to_date')),
									'rules' => 'trim|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							)
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$data = array( 
					'from_date' => trim($this->input->post('from_date', TRUE)),
					'to_date' => trim($this->input->post('to_date', TRUE)),
					'game_provider_code' => trim($this->input->post('game_provider_code', TRUE)),
					'username' => trim($this->input->post('username', TRUE)),
					'agent' => trim($this->input->post('agent', TRUE)),
					'tag_from' => $this->input->post('tag_from[]', TRUE),
					'tag_to' => $this->input->post('tag_to[]', TRUE),
					'status' => $this->input->post('status', TRUE),
					'is_upgrade' =>  trim($this->input->post('is_upgrade', TRUE)),
					'is_maintain' =>  trim($this->input->post('is_maintain', TRUE)),
					'is_downgrade' =>  trim($this->input->post('is_downgrade', TRUE)),
					'is_reset' =>  trim($this->input->post('is_reset', TRUE)),
					'tag_force' =>  trim($this->input->post('tag_force', TRUE)),
				);
				$this->session->set_userdata('search_report_tag_process', $data);
				$json['status'] = EXIT_SUCCESS;
			}
			else 
			{
				$error = array(
					'from_date' => form_error('from_date'), 
					'to_date' => form_error('to_date')
				);
				if( ! empty($error['from_date']))
				{
					$json['msg'] = $error['from_date'];
				}
				else if( ! empty($error['to_date']))
				{
					$json['msg'] = $error['to_date'];
				}
			}
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}
	public function tag_process_listing()
    {
		if(permission_validation(PERMISSION_TAG_PROCESS_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'a.tag_log_id',
				1 => 'a.created_date',
				2 => 'b.username',
				3 => 'a.tag_force',
				4 => 'a.tag_id',
				5 => 'a.to_tag_id',
				6 => 'a.win_loss',
				7 => 'a.is_upgrade',
				8 => 'a.is_maintain',
				9 => 'a.is_downgrade',
				10 => 'a.is_reset',
			);		
			$col = 0;
			$dir = "";
			if( ! empty($order))
			{
				foreach($order as $o)
				{
					$col = $o['column'];
					$dir = $o['dir'];
				}
			}
			if($dir != "asc" && $dir != "desc")
			{
				$dir = "desc";
			}
			if( ! isset($columns[$col]))
			{
				$order = $columns[0];
			}
			else
			{
				$order = $columns[$col];
			}
			$arr = $this->session->userdata('search_report_tag_process');				
			$where = '';	
			if( ! empty($arr['agent']))
			{
				$where = " AND b.player_id = 'ABC'";
				$agent = $this->user_model->get_user_data_by_username($arr['agent']);
				if(!empty($agent)){
					$response_upline = $this->user_model->get_downline_data($agent['username']);
					if(!empty($response_upline)){
						$where = " AND b.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";
					}
				}
			}else{
				$where = " AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";
			}
			if( ! empty($arr['from_date']))
			{
				$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);	
			}
			if( ! empty($arr['username']))
			{
				$where .= " AND b.username = '" . $arr['username'] . "'";
			}
			/*
			if($arr['is_upgrade'] == STATUS_ACTIVE OR $arr['is_upgrade'] == STATUS_INACTIVE)
			{
				$where .= " AND a.is_upgrade = '" . trim($arr['is_upgrade']) . "'";
			}
			if($arr['is_maintain'] == STATUS_ACTIVE OR $arr['is_maintain'] == STATUS_INACTIVE)
			{
				$where .= " AND a.is_maintain = '" . trim($arr['is_maintain']) . "'";
			}
			if($arr['is_downgrade'] == STATUS_ACTIVE OR $arr['is_downgrade'] == STATUS_INACTIVE)
			{
				$where .= " AND a.is_downgrade = '" . trim($arr['is_downgrade']) . "'";
			}
			if($arr['is_reset'] == STATUS_ACTIVE OR $arr['is_reset'] == STATUS_INACTIVE)
			{
				$where .= " AND a.is_reset = '" . trim($arr['is_reset']) . "'";
			}
			if($arr['tag_force'] == STATUS_ACTIVE OR $arr['tag_force'] == STATUS_INACTIVE)
			{
				$where .= " AND a.tag_force = '" . trim($arr['tag_force']) . "'";
			}
			*/
			if($arr['status'] == LEVEL_MOVEMENT_UP OR $arr['status'] == LEVEL_MOVEMENT_DOWN OR $arr['status'] == LEVEL_MOVEMENT_NONE)
			{
				if($arr['status'] == LEVEL_MOVEMENT_NONE){
					$where .= " AND (a.is_maintain = 1 OR a.is_reset = 1)";
				}else if($arr['status'] == LEVEL_MOVEMENT_UP){
					$where .= " AND a.is_upgrade = 1";
				}else{
					$where .= " AND a.is_downgrade = 1";
				}
			}
			if(isset($arr['tag_force']) && $arr['tag_force'] == "true"){
				$where .= " AND a.tag_force = 1";
			}
			if( ! empty($arr['tag_from']))
			{
				$tag_from = '"'.implode('","', $arr['tag_from']).'"';
				$where .= " AND a.tag_id IN(" . $tag_from . ")";
			}
			if( ! empty($arr['tag_to']))
			{
				$tag_to = '"'.implode('","', $arr['tag_to']).'"';
				$where .= " AND a.to_tag_id IN(" . $tag_to . ")";
			}
			$select = implode(',', $columns);
			$order = substr($order, 2);
			$dbprefix = $this->db->dbprefix;
			$posts = NULL;
			$query_string = "SELECT {$select} FROM {$dbprefix}tag_log a, {$dbprefix}players b WHERE (a.player_id = b.player_id) $where";
			$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if($query->num_rows() > 0)
			{
				$posts = $query->result();  
			}
			$query->free_result();
			//Get total records
			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();
			$query->free_result();
			//Prepare data
			$data = array();
			if(!empty($posts))
			{
				$tag_list = $this->tag_model->get_tag_list();
				foreach ($posts as $post)
				{
					$row = array();
					$tag_from = "";
					if(isset($tag_list[$post->tag_id])){
						$tag_from .= '<span class="badge bg-success" style="background-color: '.$tag_list[$post->tag_id]['tag_background_color'].' !important;color: '.$tag_list[$post->tag_id]['tag_font_color'].' !important;font-weight: '.(($tag_list[$post->tag_id]['is_bold'] == STATUS_ACTIVE) ? "bold": "normal").' !important;">' . $tag_list[$post->tag_id]['tag_code'] . '</span>';						
					}
					$tag_to = "";
					if(isset($tag_list[$post->to_tag_id])){
						$tag_to .= '<span class="badge bg-success" style="background-color: '.$tag_list[$post->to_tag_id]['tag_background_color'].' !important;color: '.$tag_list[$post->to_tag_id]['tag_font_color'].' !important;font-weight: '.(($tag_list[$post->to_tag_id]['is_bold'] == STATUS_ACTIVE) ? "bold": "normal").' !important;">' . $tag_list[$post->to_tag_id]['tag_code'] . '</span>';						
					}
					$row[] = $post->tag_log_id;
					$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
					$row[] = $post->username;
					switch($post->tag_force)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success">' . $this->lang->line('status_yes') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary">' . $this->lang->line('status_no') . '</span>'; break;
					}
					$row[] = ((!empty($tag_from)) ? $tag_from : '-');
					$row[] = ((!empty($tag_to)) ? $tag_to : '-');
					$row[] = number_format($post->win_loss, 2, '.', ',');
					if($post->is_reset){
						$row[] = '<span class="badge bg-secondary">' . $this->lang->line('label_is_maintain') . '</span>';
					}else if($post->is_maintain){
						$row[] = '<span class="badge bg-secondary">' . $this->lang->line('label_is_maintain') . '</span>';
					}else if($post->is_upgrade){
						$row[] = '<span class="badge bg-success">' . $this->lang->line('label_is_upgrade') . '</span>';
					}else {
						$row[] = '<span class="badge bg-danger">' . $this->lang->line('label_is_downgrade') . '</span>';
					}
					/*
					switch($post->is_upgrade)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success">' . $this->lang->line('status_yes') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary">' . $this->lang->line('status_no') . '</span>'; break;
					}
					switch($post->is_maintain)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success">' . $this->lang->line('status_yes') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary">' . $this->lang->line('status_no') . '</span>'; break;
					}
					switch($post->is_downgrade)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success">' . $this->lang->line('status_yes') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary">' . $this->lang->line('status_no') . '</span>'; break;
					}
					switch($post->is_reset)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success">' . $this->lang->line('status_yes') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary">' . $this->lang->line('status_no') . '</span>'; break;
					}
					*/
					$data[] = $row;
				}
			}
			//Output
			$json_data = array(
				"draw"            => intval($this->input->post('draw')), 
				"recordsFiltered" => intval($totalFiltered), 
				"data"            => $data,
				"csrfHash" 		  => $this->security->get_csrf_hash()				
			);
			echo json_encode($json_data); 
			exit();
		}	
    }
}