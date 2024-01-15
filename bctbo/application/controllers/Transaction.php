<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('game_model', 'player_model','transaction_model'));
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index()
	{
		if(permission_validation(PERMISSION_WALLET_TRANSACTION_PENDING_VIEW) == TRUE)
		{
			$this->save_current_url('transaction');
			
			$data['page_title'] = $this->lang->line('title_wallet_transaction_pending');
			
			$this->session->unset_userdata('searches_wallet_transaction_pending');
			
			$this->load->view('wallet_transaction_pending_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function search(){
		if(permission_validation(PERMISSION_WALLET_TRANSACTION_PENDING_VIEW) == TRUE)
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
				$date_range = ($days * 86400);
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
						'order_id' => trim($this->input->post('order_id', TRUE)),
						'order_id_alias' => trim($this->input->post('order_id_alias', TRUE)),
						'transfer_type' => trim($this->input->post('transfer_type', TRUE)),
						'status' => trim($this->input->post('status', TRUE)),
					);

					$this->session->set_userdata('searches_wallet', $data);
					
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

	public function listing(){
		if(permission_validation(PERMISSION_WALLET_TRANSACTION_PENDING_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
				0 => 'a.game_transfer_pending_id',
				1 => 'a.created_date',
				2 => 'a.transfer_type',
				3 => 'b.username',
				4 => 'a.order_id',
				5 => 'a.order_id_alias',
				6 => 'a.from_wallet',
				7 => 'a.to_wallet',
				8 => 'a.withdrawal_amount',
				9 => 'a.deposit_amount',
				10 => 'a.to_balance_before',
				11 => 'a.to_balance_after',
				12 => "a.status",
				13 => "a.remark",
				14 => 'a.updated_by',
				15 => 'a.updated_date',
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
							
			$arr = $this->session->userdata('searches_wallet');				
			
			$where = '';	
			
			if(isset($arr['from_date']))
			{
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

				if($arr['status'] == STATUS_PENDING OR $arr['status'] == STATUS_APPROVE OR $arr['status'] == STATUS_CANCEL OR $arr['status'] == STATUS_ON_PENDING)
				{
					$where .= ' AND a.status = ' . $arr['status'];
				}
				if($arr['transfer_type'] == TRANSFER_TRANSACTION_IN OR $arr['transfer_type'] == TRANSFER_TRANSACTION_OUT)
				{
					$where .= ' AND a.transfer_type = ' . $arr['transfer_type'];
				}			
			}
			
			$select = implode(',', $columns);
			$order = substr($order, 2);
			$dbprefix = $this->db->dbprefix;
			
			$posts = NULL;
			$query_string = "SELECT {$select} FROM {$dbprefix}game_transfer_pending a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
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
			
			$query_string = "SELECT {$sum_select} FROM {$dbprefix}game_transfer_pending a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
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
					$row[] = $post->game_transfer_pending_id;
					$row[] = date('Y-m-d H:i:s', $post->created_date);
					$row[] = $this->lang->line(get_transfer_type($post->transfer_type));
					$row[] = $post->username;
					$row[] = (($post->order_id) ? $post->order_id:"-");
					$row[] = (($post->order_id_alias) ? $post->order_id_alias:"-");
					$row[] = (($post->from_wallet == 'MAIN') ? $this->lang->line('label_main_wallet') : $this->lang->line('game_' . strtolower($post->from_wallet)));
					$row[] = (($post->to_wallet == 'MAIN') ? $this->lang->line('label_main_wallet') : $this->lang->line('game_' . strtolower($post->to_wallet)));
					$row[] = $post->withdrawal_amount;
					$row[] = $post->deposit_amount;
					$row[] = $post->to_balance_before;
					$row[] = $post->to_balance_after;
					switch($post->status)
					{
						case STATUS_APPROVE: $row[] = '<span class="badge bg-success" id="uc1_' . $post->game_transfer_pending_id . '">' . $this->lang->line('status_approved') . '</span>'; break;
						case STATUS_CANCEL: $row[] = '<span class="badge bg-danger" id="uc1_' . $post->game_transfer_pending_id . '">' . $this->lang->line('status_cancelled') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc1_' . $post->game_transfer_pending_id . '">' . $this->lang->line('status_pending') . '</span>'; break;
					}
					$row[] = '<span id="uc2_' . $post->game_transfer_pending_id . '">' . ( ! empty($post->remark) ? $post->remark : '-') . '</span>';
					$row[] = '<span id="uc6_' . $post->game_transfer_pending_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc7_' . $post->game_transfer_pending_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					if(permission_validation(PERMISSION_WALLET_TRANSACTION_PENDING_UPDATE) == TRUE && $post->status == STATUS_PENDING)
					{
						$row[] = '<i id="uc3_' . $post->game_transfer_pending_id . '" onclick="updateData(' . $post->game_transfer_pending_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					else
					{
						$row[] = '';
					}
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

	public function edit($id = NULL)
    {
		if(permission_validation(PERMISSION_WALLET_TRANSACTION_PENDING_UPDATE) == TRUE)
		{
			$data = $this->transaction_model->get_wallet_transaction_pending_data($id);
			if( ! empty($data) && $data['status'] == STATUS_PENDING)
			{
				$data['player'] = $this->player_model->get_player_data($data['player_id']);
				$this->load->view('wallet_transaction_pending_update', $data);
			}
			else
			{
				redirect('home');
			}
		}
		else
		{
			redirect('home');
		}
	}

	public function update()
	{
		if(permission_validation(PERMISSION_WALLET_TRANSACTION_PENDING_UPDATE) == TRUE)
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
						'field' => 'remark',
						'label' => strtolower($this->lang->line('label_remark')),
						'rules' => 'trim'
				)
			);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$game_transfer_pending_id = trim($this->input->post('game_transfer_pending_id', TRUE));
				$oldData = $this->transaction_model->get_wallet_transaction_pending_data($game_transfer_pending_id);
				
				if( ! empty($oldData) && $oldData['status'] == STATUS_PENDING && ($oldData['transfer_type'] == TRANSFER_TRANSACTION_IN || $oldData['transfer_type'] == TRANSFER_TRANSACTION_OUT))
				{
					$player = $this->player_model->get_player_data($oldData['player_id']);
					$oldData['username'] = $player['username'];
					
					//Database update
					$this->db->trans_start();
					$newData = $this->transaction_model->update_wallet_transaction_pending($oldData);
					if($oldData['transfer_type'] == TRANSFER_TRANSACTION_IN){
						//TRANSFER_TRANSACTION_IN money from game to main wallet
						if($newData['status'] == STATUS_APPROVE)
						{
							$this->player_model->update_player_wallet($newData);
							$this->general_model->insert_cash_transfer_report($player, $oldData['deposit_amount'], TRANSFER_WALLET_ADJUST);
						}
					}else{
						//TRANSFER_TRANSACTION_OUT from main wallet to game
						if($newData['status'] == STATUS_APPROVE)
						{
							$this->player_model->update_player_wallet($newData);
							$this->general_model->insert_cash_transfer_report($player, $oldData['deposit_amount'], TRANSFER_WALLET_ADJUST);
						}
					}
					
					if($this->session->userdata('user_group') == USER_GROUP_USER)
					{
						$this->user_model->insert_log(LOG_WALLET_TRANSFER_UPDATE, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_WALLET_TRANSFER_UPDATE, $newData, $oldData);
					}
					
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_updated');
						
						switch($newData['status'])
						{
							case STATUS_APPROVE: $status = $this->lang->line('status_approved'); break;
							case STATUS_CANCEL: $status = $this->lang->line('status_cancelled'); break;
							default: $status = $this->lang->line('status_pending'); break;
						}
						
						//Prepare for ajax update
						$json['response'] = array(
							'id' => $newData['game_transfer_pending_id'],
							'remark' => $newData['remark'],
							'status' => $status,
							'status_code' => $newData['status'],
							'updated_by' => $newData['updated_by'],
							'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),
						);
					}
					else
					{
						$json['msg'] = $this->lang->line('error_failed_to_deposit');
					}
				}
				else
				{
					$json['msg'] = $this->lang->line('error_failed_to_deposit');
				}		
			}
			else 
			{
				$json['msg'] = $this->lang->line('error_failed_to_deposit');
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