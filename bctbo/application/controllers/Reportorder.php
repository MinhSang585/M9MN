<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportorder extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('game_model', 'player_model'));
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function winloss()
	{
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)
		{
			$this->save_current_url('report/winloss');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_win_loss_report');
			
			$this->session->unset_userdata('searches_winloss_order');
			$this->load->view('winloss_report_view_order', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function winloss_downline($num = NULL, $username = NULL)
	{
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)
		{
			$data['num'] = $num;
			$data['username'] = $username;
			$data['type'] = 'downline';
			$html = $this->load->view('winloss_report_table_order', $data, TRUE);
			echo $html;
			exit();
		}
	}

	public function winloss_search()
	{
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
					
					$this->session->set_userdata('searches_winloss_order', $data);
					
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

	public function winloss_listing($num = NULL, $username = NULL){
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$arr = $this->session->userdata('searches_winloss_order');
			$order = $this->input->post("order", TRUE);
			//Tables Columns
			$columns = array(
				0 => "user_type",
				2 => "username",
				3 => "upline",
				4 => "total_deposit",
				5 => "total_withdrawal",
				6 => "total_bet",
				7 => "total_bet_amount",
				8 => "total_win_loss",
				9 => "total_rolling_amount",
				10 => "total_promotion",
				11 => "total_bonus",
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

			$posts = NULL;
			$where_total_all = "";
			$where_total_bet_count = "";
			$where_total_bet_amount = "";
			$where_total_win_loss = "";
			$where_total_rolling_amount = "";
			$where_total_deposit = "";
			$where_total_withdrawal = "";
			$where_total_promotion = "";
			$where_total_withdrawal2 = "";
			$where_total_bonus = "";
			$where_total_casino = "";
			$where_total_slot = "";
			$where_total_sport = "";
			$where_total_lottery = "";
			$where_total_other = "";
			$upline_query_string = "";
			
			//Default Search 
			$upline_query_string .= "SELECT MU.user_id as user_id ,MU.possess AS possess ,MU.casino_comm AS casino_comm ,MU.slots_comm AS slots_comm ,MU.sport_comm AS sport_comm , MU.lottery_comm AS lottery_comm ,MU.other_comm AS other_comm ,MU.user_type AS user_type ,MU.username AS username ,MU.upline AS upline ";
			//Total Bet Count
			$where_total_bet_count .= "AP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_bet_count .= " AND ATR.player_id = AP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_bet_count .= ' AND ATR.payout_time >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_bet_count .= ' AND ATR.payout_time <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT COUNT(ATR.transaction_id) AS total_bet FROM {$dbprefix}transaction_report ATR, {$dbprefix}players AP where $where_total_bet_count ) AS total_bet ";

			//Total Bet Amount
			$where_total_bet_amount .= "BP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_bet_amount .= " AND BTR.player_id = BP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_bet_amount .= ' AND BTR.payout_time >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_bet_amount .= ' AND BTR.payout_time <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(BTR.bet_amount) AS total_bet_amount FROM {$dbprefix}transaction_report BTR, {$dbprefix}players BP where $where_total_bet_amount ) AS total_bet_amount ";

			//Total Win Loss
			$where_total_win_loss .= "CP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_win_loss .= " AND CTR.player_id = CP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_win_loss .= ' AND CTR.payout_time >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_win_loss .= ' AND CTR.payout_time <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(CTR.win_loss) AS total_win_loss FROM {$dbprefix}transaction_report CTR, {$dbprefix}players CP where $where_total_win_loss ) AS total_win_loss ";

			//Total Rolling Amount
			$where_total_rolling_amount .= "DP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_rolling_amount .= " AND DTR.player_id = DP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_rolling_amount .= ' AND DTR.payout_time >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_rolling_amount .= ' AND DTR.payout_time <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(DTR.bet_amount_valid) AS total_rolling_amount FROM {$dbprefix}transaction_report DTR, {$dbprefix}players DP where $where_total_rolling_amount ) AS total_rolling_amount ";

			//Total Deposit
			$where_total_deposit .= "EP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_deposit .= " AND ECTR.username = EP.username";
			$where_total_deposit .= ' AND ECTR.transfer_type IN (' . TRANSFER_ADJUST_IN . ', ' . TRANSFER_OFFLINE_DEPOSIT . ', ' . TRANSFER_PG_DEPOSIT . ')';
			if( ! empty($arr['from_date']))
			{
				$where_total_deposit .= ' AND ECTR.report_date >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_deposit .= ' AND ECTR.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(ECTR.deposit_amount) AS total_deposit FROM {$dbprefix}cash_transfer_report ECTR, {$dbprefix}players EP where $where_total_deposit ) AS total_deposit ";

			//Total Withdrawal
			$where_total_withdrawal .= "FP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_withdrawal .= " AND FW.player_id = FP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_withdrawal .= ' AND FW.updated_date >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_withdrawal .= ' AND FW.updated_date <= ' . strtotime($arr['to_date']);
			}
			$where_total_withdrawal .= ' AND FW.status = '. STATUS_APPROVE;
			$upline_query_string .= ",(SELECT SUM(FW.amount) AS total_withdrawal FROM {$dbprefix}withdrawals FW, {$dbprefix}players FP where $where_total_withdrawal ) AS total_withdrawal ";


			//Total Promotion
			$where_total_promotion .= "GP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_promotion .= " AND GCTR.username = GP.username";
			if( ! empty($arr['from_date']))
			{
				$where_total_promotion .= ' AND GCTR.report_date >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_promotion .= ' AND GCTR.report_date <= ' . strtotime($arr['to_date']);
			}
			$where_total_promotion .= ' AND GCTR.transfer_type IN (' . TRANSFER_PROMOTION . ')';
			$upline_query_string .= ",(SELECT SUM(GCTR.deposit_amount) AS total_promotion FROM {$dbprefix}cash_transfer_report GCTR, {$dbprefix}players GP where $where_total_promotion ) AS total_promotion ";

			//Total Bomnus
			$where_total_bonus .= "HP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_bonus .= " AND HCTR.username = HP.username";
			if( ! empty($arr['from_date']))
			{
				$where_total_bonus .= ' AND HCTR.report_date >= ' . strtotime($arr['from_date']);
			}

			if( ! empty($arr['to_date']))
			{
				$where_total_bonus .= ' AND HCTR.report_date <= ' . strtotime($arr['to_date']);
			}
			$where_total_bonus .= ' AND HCTR.transfer_type IN (' . TRANSFER_BONUS . ')';
			$upline_query_string .= ",(SELECT SUM(HCTR.deposit_amount) AS total_bonus FROM {$dbprefix}cash_transfer_report HCTR, {$dbprefix}players HP where $where_total_bonus ) AS total_bonus ";
			

			//Total Withdrawal2
			$where_total_withdrawal2 .= "IP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";
			$where_total_withdrawal2 .= " AND ICTR.username = IP.username";
			$where_total_withdrawal2 .= ' AND ICTR.transfer_type IN (' . TRANSFER_ADJUST_OUT . ')';
			if( ! empty($arr['from_date']))
			{
				$where_total_withdrawal2 .= ' AND ICTR.report_date >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_withdrawal2 .= ' AND ICTR.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(ICTR.withdrawal_amount) AS total_withdrawal2 FROM {$dbprefix}cash_transfer_report ICTR, {$dbprefix}players IP where $where_total_withdrawal2 ) AS total_withdrawal2 ";

			//FRom Main table
			$upline_query_string .= "FROM bctp_users MU ";
			if(empty($username))
			{
				$num = 1;
				$upline_query_string .= "WHERE MU.user_id = " . $this->session->userdata('root_user_id') . " GROUP BY MU.user_id ORDER BY {$order} {$dir} LIMIT 1 ";
			}
			else
			{
				$upline_query_string .= "WHERE MU.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' AND MU.upline = '{$username}' GROUP BY MU.user_id ORDER BY {$order} {$dir} LIMIT {$start}, {$limit} ";
			}
			$upline_query = $this->db->query($upline_query_string);
			if($upline_query->num_rows() > 0)
			{
				foreach($upline_query->result() as $upline_row)
				{
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
						GAME_OTHERS => array(
							'total_bet' => 0,
							'total_bet_amount' => 0,
							'total_win_loss' => 0,
							'total_rolling_amount' => 0,
						)


					);
				
					$where = '';
					
					if( ! empty($arr['from_date']))
					{
						$where .= ' AND a.payout_time >= ' . strtotime($arr['from_date']);
					}
					
					if( ! empty($arr['to_date']))
					{
						$where .= ' AND a.payout_time <= ' . strtotime($arr['to_date']);
					}
					
					$select = "a.game_type_code, COUNT(a.transaction_id) AS total_bet, SUM(a.bet_amount) AS total_bet_amount, SUM(a.win_loss) AS total_win_loss, SUM(a.bet_amount_valid) AS total_rolling_amount";			
					$wl_query_string = "SELECT {$select} FROM {$dbprefix}transaction_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $upline_row->user_id . ",%' ESCAPE '!' $where GROUP BY a.game_type_code";
					$wl_query = $this->db->query($wl_query_string);
					if($wl_query->num_rows() > 0)
					{
						foreach($wl_query->result() as $wl_row)
						{
							$game_type_code = GAME_OTHERS;
							
							if($wl_row->game_type_code == GAME_SPORTSBOOK OR $wl_row->game_type_code == GAME_LIVE_CASINO OR $wl_row->game_type_code == GAME_SLOTS)
							{
								$game_type_code = $wl_row->game_type_code;
							}
							
							$comm_arr[$game_type_code]['total_bet'] = ($comm_arr[$game_type_code]['total_bet'] + $wl_row->total_bet);
							$comm_arr[$game_type_code]['total_bet_amount'] = ($comm_arr[$game_type_code]['total_bet_amount'] + $wl_row->total_bet_amount);
							$comm_arr[$game_type_code]['total_win_loss'] = ($comm_arr[$game_type_code]['total_win_loss'] + $wl_row->total_win_loss);
							$comm_arr[$game_type_code]['total_rolling_amount'] = ($comm_arr[$game_type_code]['total_rolling_amount'] + $wl_row->total_rolling_amount);
						}
					}
					$deposit = $upline_row->total_deposit; 
					$withdrawal = $upline_row->total_withdrawal + $upline_row->total_withdrawal2;
					$promotion = $upline_row->total_promotion;
					$bonus = $upline_row->total_bonus;
					$total_bet = $upline_row->total_bet;
					$total_bet_amount = $upline_row->total_bet_amount;
					$total_win_loss = $upline_row->total_win_loss;
					$total_rolling_amount = $upline_row->total_rolling_amount;

					$casino_comm = (($comm_arr[GAME_LIVE_CASINO]['total_rolling_amount'] * $upline_row->casino_comm) / 100);
					$slots_comm = (($comm_arr[GAME_SLOTS]['total_rolling_amount'] * $upline_row->slots_comm) / 100);
					$sport_comm = (($comm_arr[GAME_SPORTSBOOK]['total_rolling_amount'] * $upline_row->sport_comm) / 100);
					$other_comm = (($comm_arr[GAME_OTHERS]['total_rolling_amount'] * $upline_row->other_comm) / 100);
					$rolling_commission = ($casino_comm + $slots_comm + $sport_comm + $other_comm);

					$possess_win_loss = (($total_win_loss * $upline_row->possess) / 100);
					$possess_promotion = (($promotion * $upline_row->possess) / 100);
					$possess_bonus = (($bonus * $upline_row->possess) / 100);
					$profit = (($possess_win_loss * -1) - $rolling_commission - $possess_promotion - $possess_bonus);

					//Prepare data
					$row = array();
					$row[] = $this->lang->line(get_user_type($upline_row->user_type));
					$row[] = '-';
					$row[] = '<a href="javascript:void(0);" onclick="getDownline(\'' . $upline_row->username . '\', ' . $num . ')">' . $upline_row->username . '</a>';
					$row[] = ( ! empty($upline_row->upline) ? $upline_row->upline : '-');
					$row[] = number_format($deposit, 2, '.', ',');
					$row[] = number_format($withdrawal, 2, '.', ',');
					$row[] = $total_bet;
					$row[] = number_format($total_bet_amount, 2, '.', ',');
					$row[] = '<span class="text-' . (($total_win_loss > 0) ? 'primary' : 'danger') . '">' . number_format($total_win_loss, 2, '.', ',') . '</span>';
					$row[] = number_format($total_rolling_amount, 2, '.', ',');
					$row[] = number_format($promotion, 2, '.', ',');
					$row[] = number_format($bonus, 2, '.', ',');
					$row[] = '<span class="text-' . (($profit > 0) ? 'primary' : 'danger') . '">' . $profit . '</span>';
					$data[] = $row;
				}
			}
			$upline_query->free_result();
			if(empty($username))
			{
				$upline_query_total_string = "SELECT * FROM {$dbprefix}users WHERE user_id = " . $this->session->userdata('root_user_id') . " LIMIT 1";
			}
			else
			{
				$upline_query_total_string = "SELECT * FROM {$dbprefix}users WHERE upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' AND upline = '{$username}'";
			}
			$upline_total_query = $this->db->query($upline_query_total_string);
			$totalFiltered = $upline_total_query->num_rows();
			
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

	public function player_winloss_listing($username = NULL){
		if(permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$arr = $this->session->userdata('searches_winloss_order');
			$order = $this->input->post("order", TRUE);
			$userData = $this->user_model->get_user_data_by_username($username);
			(!empty($userData)) ? $userID = $userData['user_id'] : $userID = "abc";
			$dbprefix = $this->db->dbprefix;
			$data = array();
			$order = $this->input->post("order", TRUE);
			$columns = array(
				0 => "player_id",
				2 => "username",
				3 => "player_id",
				4 => "total_deposit",
				5 => "total_withdrawal",
				6 => "total_bet",
				7 => "total_bet_amount",
				8 => "total_win_loss",
				9 => "total_rolling_amount",
				10 => "total_promotion",
				11 => "total_bonus",
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

			$where ="";
			$where_total_all = "";
			$where_total_bet_count = "";
			$where_total_bet_amount = "";
			$where_total_win_loss = "";
			$where_total_rolling_amount = "";
			$where_total_deposit = "";
			$where_total_withdrawal = "";
			$where_total_promotion = "";
			$where_total_withdrawal2 = "";
			$where_total_bonus = "";
			$where_total_casino = "";
			$where_total_slot = "";
			$where_total_sport = "";
			$where_total_lottery = "";
			$where_total_other = "";
			$upline_query_string = "";


			$upline_query_string = "SELECT MU.player_id as player_id, MU.username AS username, MU.upline AS upline ";
			
			//Total Bet Count
			$where_total_bet_count .= "AP.player_id = MU.player_id";
			$where_total_bet_count .= " AND ATR.player_id = AP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_bet_count .= ' AND ATR.payout_time >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_bet_count .= ' AND ATR.payout_time <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT COUNT(ATR.transaction_id) AS total_bet FROM {$dbprefix}transaction_report ATR, {$dbprefix}players AP where $where_total_bet_count ) AS total_bet ";


			//Total Bet Amount
			$where_total_bet_amount .= "BP.player_id = MU.player_id";
			$where_total_bet_amount .= " AND BTR.player_id = BP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_bet_amount .= ' AND BTR.payout_time >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_bet_amount .= ' AND BTR.payout_time <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(BTR.bet_amount) AS total_bet_amount FROM {$dbprefix}transaction_report BTR, {$dbprefix}players BP where $where_total_bet_amount ) AS total_bet_amount ";

			//Total Win Loss
			$where_total_win_loss .= "CP.player_id = MU.player_id";
			$where_total_win_loss .= " AND CTR.player_id = CP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_win_loss .= ' AND CTR.payout_time >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_win_loss .= ' AND CTR.payout_time <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(CTR.win_loss) AS total_win_loss FROM {$dbprefix}transaction_report CTR, {$dbprefix}players CP where $where_total_win_loss ) AS total_win_loss ";

			//Total Rolling Amount
			$where_total_rolling_amount .= "DP.player_id = MU.player_id";
			$where_total_rolling_amount .= " AND DTR.player_id = DP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_rolling_amount .= ' AND DTR.payout_time >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_rolling_amount .= ' AND DTR.payout_time <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(DTR.bet_amount_valid) AS total_rolling_amount FROM {$dbprefix}transaction_report DTR, {$dbprefix}players DP where $where_total_rolling_amount ) AS total_rolling_amount ";

			//Total Deposit
			$where_total_deposit .= "EP.player_id = MU.player_id";
			$where_total_deposit .= " AND ECTR.username = EP.username";
			$where_total_deposit .= ' AND ECTR.transfer_type IN (' . TRANSFER_OFFLINE_DEPOSIT . ')';
			if( ! empty($arr['from_date']))
			{
				$where_total_deposit .= ' AND ECTR.report_date >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_deposit .= ' AND ECTR.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(ECTR.deposit_amount) AS total_deposit FROM {$dbprefix}cash_transfer_report ECTR, {$dbprefix}players EP where $where_total_deposit ) AS total_deposit ";

			//Total Withdrawal
			$where_total_withdrawal .= "FP.player_id = MU.player_id";
			$where_total_withdrawal .= " AND FW.player_id = FP.player_id";
			if( ! empty($arr['from_date']))
			{
				$where_total_withdrawal .= ' AND FW.updated_date >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_withdrawal .= ' AND FW.updated_date <= ' . strtotime($arr['to_date']);
			}
			$where_total_withdrawal .= ' AND FW.status = '. STATUS_APPROVE;
			$upline_query_string .= ",(SELECT SUM(FW.amount) AS total_withdrawal FROM {$dbprefix}withdrawals FW, {$dbprefix}players FP where $where_total_withdrawal ) AS total_withdrawal ";

			//Total Promotion
			$where_total_promotion .= "GP.player_id = MU.player_id";
			$where_total_promotion .= " AND GCTR.username = GP.username";
			if( ! empty($arr['from_date']))
			{
				$where_total_promotion .= ' AND GCTR.report_date >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_promotion .= ' AND GCTR.report_date <= ' . strtotime($arr['to_date']);
			}
			$where_total_promotion .= ' AND GCTR.transfer_type IN (' . TRANSFER_PROMOTION . ')';
			$upline_query_string .= ",(SELECT SUM(GCTR.deposit_amount) AS total_promotion FROM {$dbprefix}cash_transfer_report GCTR, {$dbprefix}players GP where $where_total_promotion ) AS total_promotion ";

			//Total Bomnus
			$where_total_bonus .= "HP.player_id = MU.player_id";
			$where_total_bonus .= " AND HCTR.username = HP.username";
			if( ! empty($arr['from_date']))
			{
				$where_total_bonus .= ' AND HCTR.report_date >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_bonus .= ' AND HCTR.report_date <= ' . strtotime($arr['to_date']);
			}
			$where_total_bonus .= ' AND HCTR.transfer_type IN (' . TRANSFER_BONUS . ')';
			$upline_query_string .= ",(SELECT SUM(HCTR.deposit_amount) AS total_bonus FROM {$dbprefix}cash_transfer_report HCTR, {$dbprefix}players HP where $where_total_bonus ) AS total_bonus ";

			//Total Withdrawal2
			$where_total_withdrawal2 .= "IP.player_id = MU.player_id";
			$where_total_withdrawal2 .= " AND ICTR.username = IP.username";
			$where_total_withdrawal2 .= ' AND ICTR.transfer_type IN (' . TRANSFER_ADJUST_OUT . ')';
			if( ! empty($arr['from_date']))
			{
				$where_total_withdrawal2 .= ' AND ICTR.report_date >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where_total_withdrawal2 .= ' AND ICTR.report_date <= ' . strtotime($arr['to_date']);
			}
			$upline_query_string .= ",(SELECT SUM(ICTR.withdrawal_amount) AS total_withdrawal2 FROM {$dbprefix}cash_transfer_report ICTR, {$dbprefix}players IP where $where_total_withdrawal2 ) AS total_withdrawal2 ";

			$upline_query_string .= "FROM {$dbprefix}players MU ";

			$upline_query_string .= "WHERE MU.upline_ids LIKE '%," . $userID . ",%' AND MU.upline = '{$username}' ORDER BY {$order} {$dir} LIMIT {$start}, {$limit}";
			$upline_query = $this->db->query($upline_query_string);
			if($upline_query->num_rows() > 0)
			{
				foreach($upline_query->result() as $upline_row)
				{
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
						GAME_OTHERS => array(
							'total_bet' => 0,
							'total_bet_amount' => 0,
							'total_win_loss' => 0,
							'total_rolling_amount' => 0,
						)
					);

					if( ! empty($arr['from_date']))
					{
						$where .= ' AND payout_time >= ' . strtotime($arr['from_date']);
					}
					
					if( ! empty($arr['to_date']))
					{
						$where .= ' AND payout_time <= ' . strtotime($arr['to_date']);
					}
					
					$select = "game_type_code, COUNT(transaction_id) AS total_bet, SUM(bet_amount) AS total_bet_amount, SUM(win_loss) AS total_win_loss, SUM(bet_amount_valid) AS total_rolling_amount";			
					$wl_query_string = "SELECT {$select} FROM {$dbprefix}transaction_report WHERE player_id = '{$upline_row->player_id}' $where GROUP BY game_type_code";
					$wl_query = $this->db->query($wl_query_string);
					if($wl_query->num_rows() > 0)
					{
						foreach($wl_query->result() as $wl_row)
						{
							$game_type_code = GAME_OTHERS;
							
							if($wl_row->game_type_code == GAME_SPORTSBOOK OR $wl_row->game_type_code == GAME_LIVE_CASINO OR $wl_row->game_type_code == GAME_SLOTS)
							{
								$game_type_code = $wl_row->game_type_code;
							}
							
							$comm_arr[$game_type_code]['total_bet'] = ($comm_arr[$game_type_code]['total_bet'] + $wl_row->total_bet);
							$comm_arr[$game_type_code]['total_bet_amount'] = ($comm_arr[$game_type_code]['total_bet_amount'] + $wl_row->total_bet_amount);
							$comm_arr[$game_type_code]['total_win_loss'] = ($comm_arr[$game_type_code]['total_win_loss'] + $wl_row->total_win_loss);
							$comm_arr[$game_type_code]['total_rolling_amount'] = ($comm_arr[$game_type_code]['total_rolling_amount'] + $wl_row->total_rolling_amount);
						}
					}
					$wl_query->free_result();

					$deposit = $upline_row->total_deposit; 
					$withdrawal = $upline_row->total_withdrawal + $upline_row->total_withdrawal2;
					$promotion = $upline_row->total_promotion;
					$bonus = $upline_row->total_bonus;
					$total_bet = $upline_row->total_bet;
					$total_bet_amount = $upline_row->total_bet_amount;
					$total_win_loss = $upline_row->total_win_loss;
					$total_rolling_amount = $upline_row->total_rolling_amount;


					//Calculation
					$total_bet = ($comm_arr[GAME_SPORTSBOOK]['total_bet'] + $comm_arr[GAME_LIVE_CASINO]['total_bet'] + $comm_arr[GAME_SLOTS]['total_bet'] + $comm_arr[GAME_OTHERS]['total_bet']);
					$total_bet_amount = ($comm_arr[GAME_SPORTSBOOK]['total_bet_amount'] + $comm_arr[GAME_LIVE_CASINO]['total_bet_amount'] + $comm_arr[GAME_SLOTS]['total_bet_amount'] + $comm_arr[GAME_OTHERS]['total_bet_amount']);
					$total_win_loss = ($comm_arr[GAME_SPORTSBOOK]['total_win_loss'] + $comm_arr[GAME_LIVE_CASINO]['total_win_loss'] + $comm_arr[GAME_SLOTS]['total_win_loss'] + $comm_arr[GAME_OTHERS]['total_win_loss']);
					$total_rolling_amount = ($comm_arr[GAME_SPORTSBOOK]['total_rolling_amount'] + $comm_arr[GAME_LIVE_CASINO]['total_rolling_amount'] + $comm_arr[GAME_SLOTS]['total_rolling_amount'] + $comm_arr[GAME_OTHERS]['total_rolling_amount']);


					//Prepare data
					$row = array();
					$row[] = $this->lang->line('level_ply');
					$row[] = '-';
					$row[] = $upline_row->username;
					$row[] = ( ! empty($upline_row->upline) ? $upline_row->upline : '-');
					$row[] = number_format($deposit, 2, '.', ',');
					$row[] = number_format($withdrawal, 2, '.', ',');
					$row[] = $total_bet;
					$row[] = number_format($total_bet_amount, 2, '.', ',');
					$row[] = '<span class="text-' . (($total_win_loss > 0) ? 'primary' : 'danger') . '">' . number_format($total_win_loss, 2, '.', ',') . '</span>';
					$row[] = number_format($total_rolling_amount, 2, '.', ',');
					$row[] = number_format($promotion, 2, '.', ',');
					$row[] = number_format($bonus, 2, '.', ',');
					
					$data[] = $row;
				}
			}
			$upline_query->free_result();
			
			$upline_total_query_string = "SELECT * FROM {$dbprefix}players WHERE upline_ids LIKE '%," . $userID . ",%' AND upline = '{$username}' ORDER BY username ASC";
			$upline_total_query = $this->db->query($upline_total_query_string);
			$totalFiltered = $upline_total_query->num_rows();
			$upline_total_query->free_result();
			//Output
			//Declaration Total
			$total_data = array(
				"total_deposit" => 0,
				"total_withdrawal" => 0,
				'total_bet' => 0,
				'total_bet_amount' => 0,
				'total_win_loss' => 0,
				'total_rolling_amount' => 0,
				'total_promotion' => 0,
				'total_bonus' => 0,
			);
			//Get Total Transaction
			$where = '';	
			if( ! empty($arr['from_date']))
			{
				$where .= ' AND a.payout_time >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where .= ' AND a.payout_time <= ' . strtotime($arr['to_date']);
			}
			$total_sum_transaction_select = "COUNT(a.transaction_id) AS total_bet, SUM(a.bet_amount) AS total_bet_amount, SUM(a.win_loss) AS total_win_loss, SUM(a.bet_amount_valid) AS total_rolling_amount";
			$total_sum_transaction_query_string = "SELECT $total_sum_transaction_select FROM {$dbprefix}transaction_report a, {$dbprefix}players b WHERE a.player_id = b.player_id AND b.upline_ids LIKE '%," . $userID . ",%' AND b.upline = '{$username}' $where ORDER BY b.username ASC";
			$total_sum_transaction_query = $this->db->query($total_sum_transaction_query_string);
			if($total_sum_transaction_query->num_rows() > 0)
			{
				foreach($total_sum_transaction_query->result() as $row)
				{
					$total_data['total_bet'] = (($row->total_bet > 0) ? $row->total_bet : 0);
					$total_data['total_bet_amount'] = (($row->total_bet_amount > 0) ? $row->total_bet_amount : 0);
					$total_data['total_win_loss'] = (($row->total_win_loss > 0) ? $row->total_win_loss : 0);
					$total_data['total_rolling_amount'] = (($row->total_rolling_amount > 0) ? $row->total_rolling_amount : 0);
				}
			}
			$total_sum_transaction_query->free_result();

			//Get Total Deposit
			$where = '';
			if( ! empty($arr['from_date']))
			{
				$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
			}			
			$where .= ' AND a.transfer_type IN (' . TRANSFER_OFFLINE_DEPOSIT . ')';

			$total_sum_deposit_query_string = "SELECT SUM(a.deposit_amount) AS total_deposit FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE a.username = b.username AND b.upline_ids LIKE '%," . $userID . ",%' AND b.upline = '{$username}' $where";
			$total_sum_deposit_query = $this->db->query($total_sum_deposit_query_string);
			if($total_sum_deposit_query->num_rows() > 0)
			{
				foreach($total_sum_deposit_query->result() as $row)
				{
					$total_data['total_deposit'] = (($row->total_deposit > 0) ? $row->total_deposit : 0);
				}
			}
			$total_sum_deposit_query->free_result();
			//Get Total Withdrawal
			$where = '';
			if( ! empty($arr['from_date']))
			{
				$where .= ' AND a.updated_date >= ' . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where .= ' AND a.updated_date <= ' . strtotime($arr['to_date']);
			}
			$where .= ' AND a.status = '. STATUS_APPROVE;
			$total_sum_withdrawal_query_string = "SELECT SUM(a.amount) AS total_withdrawal FROM {$dbprefix}withdrawals a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $userID . ",%' AND b.upline = '{$username}' $where";
			$total_sum_withdrawal_query = $this->db->query($total_sum_withdrawal_query_string);
			if($total_sum_withdrawal_query->num_rows() > 0)
			{
				foreach($total_sum_withdrawal_query->result() as $row)
				{
					$total_data['total_withdrawal'] = (($row->total_withdrawal > 0) ? $row->total_withdrawal : 0);
				}
			}
			$total_sum_withdrawal_query->free_result();

			//Get Total Promotion
			$where = '';
			if( ! empty($arr['from_date']))
			{
				$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
			}			
			$where .= ' AND a.transfer_type IN (' . TRANSFER_PROMOTION . ')';

			$total_sum_promotion_query_string = "SELECT SUM(a.deposit_amount) AS total_promotion FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE a.username = b.username AND b.upline_ids LIKE '%," . $userID . ",%' AND b.upline = '{$username}' $where";
			$total_sum_promotion_query = $this->db->query($total_sum_promotion_query_string);
			if($total_sum_promotion_query->num_rows() > 0)
			{
				foreach($total_sum_promotion_query->result() as $row)
				{
					$total_data['total_promotion'] = (($row->total_promotion > 0) ? $row->total_promotion : 0);
				}
			}
			$total_sum_promotion_query->free_result();

			//Get Total Bonus
			$where = '';
			if( ! empty($arr['from_date']))
			{
				$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);
			}
			if( ! empty($arr['to_date']))
			{
				$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);
			}			
			$where .= ' AND a.transfer_type IN (' . TRANSFER_BONUS . ')';

			$total_sum_bonus_query_string = "SELECT SUM(a.deposit_amount) AS total_bonus FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE a.username = b.username AND b.upline_ids LIKE '%," . $userID . ",%' AND b.upline = '{$username}' $where";
			$total_sum_bonus_query = $this->db->query($total_sum_bonus_query_string);
			if($total_sum_bonus_query->num_rows() > 0)
			{
				foreach($total_sum_bonus_query->result() as $row)
				{
					$total_data['total_bonus'] = (($row->total_bonus > 0) ? $row->total_bonus : 0);
				}
			}
			$total_sum_bonus_query->free_result();
			$json_data = array(
							"draw"            => intval($this->input->post('draw')), 
							"recordsFiltered" => intval($totalFiltered), 
							"data"            => $data,
							"csrfHash" 		  => $this->security->get_csrf_hash(),
							"total_data"	  => $total_data,			
						);
				
			echo json_encode($json_data); 
			exit();
		}
	}
}