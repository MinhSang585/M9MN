<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reward extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('player_model','miscellaneous_model', 'reward_model','promotion_model'));
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index()
	{
		if(permission_validation(PERMISSION_REWARD_VIEW) == TRUE)
		{
			$this->save_current_url('reward');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_reward');
			$this->session->unset_userdata('search_rewards');
			$data_search = array(
				'from_date' => date('Y-m-d 00:00:00'),
				'to_date' => date('Y-m-d 23:59:59'),
				'username' => "",
				'status' => "-1",
			);
			$this->session->set_userdata('search_reward', $data_search);
			
			$this->load->view('reward_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function search()
	{
		if(permission_validation(PERMISSION_REWARD_VIEW) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => array(
										'from_date_error' => '',
										'to_date_error' => '',
										'general_error' => ''
									),
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
				$date_range = ($days * 86400);
				$time_diff = ($to_date - $from_date);
				
				if($time_diff < 0 OR $time_diff > $date_range)
				{
					$json['msg']['general_error'] = $this->lang->line('error_invalid_year_range');
				}
				else
				{
					$data = array( 
									'from_date' => trim($this->input->post('from_date', TRUE)),
									'to_date' => trim($this->input->post('to_date', TRUE)),
									'username' => trim($this->input->post('username', TRUE)),
									'status' => trim($this->input->post('status', TRUE)),
								);
					
					$this->session->set_userdata('search_reward', $data);
					
					$json['status'] = EXIT_SUCCESS;
				}
			}
			else 
			{
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

	public function listing()
    {
		if(permission_validation(PERMISSION_REWARD_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'a.player_reward_id',
				1 => 'a.created_date',
				2 => 'b.username',
				3 => 'b.upline',
				4 => 'a.reward_amount',
				5 => 'a.reward_calculated',
				6 => 'a.promotion_name',
				7 => 'a.status',
				8 => 'a.remark',
				9 => 'a.updated_by',
				10 => 'a.updated_date',
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
			
			$arr = $this->session->userdata('search_deposits');				
			
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
				
				if($arr['status'] == STATUS_PENDING OR $arr['status'] == STATUS_APPROVE OR $arr['status'] == STATUS_CANCEL)
				{
					$where .= ' AND a.status = ' . $arr['status'];
				}
			}	
			
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;
			
			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}player_reward a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
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
					$row[] = $post->player_reward_id;
					$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
					$row[] = $post->username;
					$row[] = $post->upline;
					$row[] = $post->reward_amount;
					$row[] = $post->reward_calculated;
					$row[] = (($post->promotion_name) ? $post->promotion_name : '-');
					switch($post->status)
					{
						case STATUS_APPROVE: $row[] = '<span class="badge bg-success" id="uc1_' . $post->player_reward_id . '">' . $this->lang->line('status_approved') . '</span>'; break;
						case STATUS_CANCEL: $row[] = '<span class="badge bg-danger" id="uc1_' . $post->player_reward_id . '">' . $this->lang->line('status_cancelled') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc1_' . $post->player_reward_id . '">' . $this->lang->line('status_pending') . '</span>'; break;
					}
					$row[] = '<span id="uc2_' . $post->player_reward_id . '">' . ( ! empty($post->remark) ? $post->remark : '-') . '</span>';
					$row[] = '<span id="uc3_' . $post->player_reward_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc4_' . $post->player_reward_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					if(permission_validation(PERMISSION_REWARD_UPDATE) == TRUE && $post->status == STATUS_PENDING)
					{
						$row[] = '<i id="uc5_' . $post->player_reward_id . '" onclick="updateData(' . $post->player_reward_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					else
					{
						$row[] = '';
					}
					
					$data[] = $row;
				}
			}

			$sum_select = implode(',', $sum_columns);
			$total_data = array(
				'total_reward' => 0,
			);
			$query_string = "SELECT {$sum_select} FROM {$dbprefix}player_reward a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
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
    }

    public function edit($id = NULL)
    {
		if(permission_validation(PERMISSION_REWARD_UPDATE) == TRUE)
		{
			$data = $this->reward_model->get_reward_data($id);
			if( ! empty($data) && $data['status'] == STATUS_PENDING)
			{
				$data['player'] = $this->player_model->get_player_data($data['player_id']);
				$this->load->view('reward_update', $data);
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

	public function update(){
		if(permission_validation(PERMISSION_REWARD_UPDATE) == TRUE)
		{
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
			if ($this->form_validation->run() == TRUE)
			{
				$player_reward_id = trim($this->input->post('player_reward_id', TRUE));
				$oldData = $this->reward_model->get_reward_data($player_reward_id);
				if( ! empty($oldData) && $oldData['status'] == STATUS_PENDING)
				{
					$player = $this->player_model->get_player_data($oldData['player_id']);
					$oldData['username'] = $player['username'];
					$this->db->trans_start();
					$newData = $this->reward_model->update_reward($oldData);
					if($newData['status'] == STATUS_APPROVE)
					{
						$this->player_model->update_player_reward($newData);
						$this->general_model->insert_reward_transfer_report($player, $oldData['reward_amount'], TRANSFER_REWARD_IN);
					}

					if($this->session->userdata('user_group') == USER_GROUP_USER)
					{
						$this->user_model->insert_log(LOG_REWARD_UPDATE, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_REWARD_UPDATE, $newData, $oldData);
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
							'id' => $newData['player_reward_id'],
							'remark' => $newData['remark'],
							'status' => $status,
							'status_code' => $newData['status'],
							'updated_by' => $newData['updated_by'],
							'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),
						);
					}
					else
					{
						$json['msg'] = $this->lang->line('error_failed_to_reward');
					}
				}else{
					$json['msg'] = $this->lang->line('error_failed_to_reward');
				}
			}else{
				$json['msg'] = $this->lang->line('error_failed_to_reward');
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

	public function deduct($id = NULL)
	{
		if(permission_validation(PERMISSION_REWARD_DEDUCT) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$this->load->view('reward_deduct', $data);
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
		else
		{
			redirect('home');
		}
	}

	public function reward_deduct_submit()
	{
		if(permission_validation(PERMISSION_REWARD_DEDUCT) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
							'rewards_error' => '',
							'general_error' => ''
						),
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			
			$player_id = trim($this->input->post('player_id', TRUE));
			$oldData = $this->player_model->get_player_data($player_id);
			
			if( ! empty($oldData))
			{
				$response = $this->user_model->get_downline_data($oldData['upline']);
				if( ! empty($response))
				{
					//Set form rules
					$config = array(
						array(
							'field' => 'rewards',
							'label' => strtolower($this->lang->line('label_rewards')),
							'rules' => 'trim|greater_than[0]|less_than_equal_to[' . $oldData['rewards'] . ']',
							'errors' => array(
								'greater_than' => $this->lang->line('error_greater_than'),
								'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
							)
						)
					);		
								
					$this->form_validation->set_rules($config);
					$this->form_validation->set_error_delimiters('', '');
					
					//Form validation
					if ($this->form_validation->run() == TRUE)
					{
						$rewards = $this->input->post('rewards', TRUE);
						
						//Database update
						$this->db->trans_start();
						$newData = $this->player_model->reward_transfer($oldData, ($rewards * -1));
						$this->general_model->insert_reward_transfer_report($oldData, $rewards, TRANSFER_REWARD_OUT);	
						if($this->session->userdata('user_group') == USER_GROUP_USER)
						{
							$this->user_model->insert_log(LOG_REWARD_DEDUCT, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_REWARD_DEDUCT, $newData, $oldData);
						}
						
						$this->db->trans_complete();
						
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_deduct_rewards');
							
							//Prepare for ajax update
							$json['response'] = array(
								'id' => $oldData['player_id'],
								'rewards' => number_format(($oldData['rewards'] - $rewards), 2, '.', ''),
							);
						}
						else
						{
							$json['msg']['general_error'] = $this->lang->line('error_failed_to_deduct');
						}
					}
					else 
					{
						$json['msg']['rewards_error'] = form_error('rewards');
					}
				}
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_deduct');
				}
			}
			else
			{
				$json['msg']['general_error'] = $this->lang->line('error_failed_to_deduct');
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