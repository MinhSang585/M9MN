<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vipreward extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('vipreward_model'));
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index()
	{

		if(permission_validation(PERMISSION_VIP_REWARD_VIEW) == TRUE)
		{
			$this->save_current_url('vipreward');
			$data = quick_search();
			$data['page_title'] = "VIP Reward";
			$this->session->unset_userdata('search_vipreward');
			$data['username'] = $this->session->userdata('root_username');
			$data_search = array(
				'from_date' => date('Y-m-d 00:00:00',strtotime('first day of -3 month',time())),
				'to_date' => date('Y-m-d 23:59:59'),
				'status' => -1
			);
			$data['data_search'] = $data_search;
			$this->session->set_userdata('search_vipreward', $data_search);
			$this->load->view('vipreward_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	
	public function search()
	{
		if(permission_validation(PERMISSION_VIP_REWARD_VIEW) == TRUE)
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
				$fromDate = strtotime(trim($this->input->post('from_date', TRUE)));
				$toDate = strtotime(trim($this->input->post('to_date', TRUE)));
				$days = $this->cal_days_in_year(date('Y', $fromDate));
				$date_range = ($days * 86400);
				$time_diff = ($toDate - $fromDate);
				
				if($time_diff < 0 OR $time_diff > $date_range)
				{
					$json['msg']['general_error'] = $this->lang->line('error_invalid_year_range');
				}
				else
				{
					$data = array( 
						'from_date' => trim($this->input->post('from_date', TRUE)),
						'to_date' => trim($this->input->post('to_date', TRUE)),
						'status' => $this->input->post('status', TRUE)
					);
					
					$this->session->set_userdata('search_vipreward', $data);
					
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
	
	public function listing() {
		
		if(permission_validation(PERMISSION_VIP_REWARD_VIEW) == TRUE) {
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			#Table Columns
			$columns = array(
				0 => 'id',
				1 => 'created_date',
				2 => 'player_id',
				3 => 'old_level_id',
				4 => 'new_level_id',
				5 => 'total_bet_amount_valid',
				6 => 'level_up_bonus',
				7 => 'status',
				8 => 'updated_by',
				9 => 'updated_date'
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
			
			$arr = $this->session->userdata('search_vipreward');
			
			$where = '';		

			if(isset($arr['from_date']))
			{
				if( ! empty($arr['from_date']))
				{
					$where .= ' AND created_date >= "' . date("Y-m-d H:i:s",strtotime($arr['from_date'])) . '"';
				}
				
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND created_date <= "' . date("Y-m-d H:i:s",strtotime($arr['to_date'])) . '"';
				}

				if($arr['status'] == STATUS_REQUEST_AWARD OR $arr['status'] == STATUS_APPROVE OR $arr['status'] == STATUS_CANCEL)
				{
					$where .= ' AND status = ' . $arr['status'];
				}
			}	
			
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;
			
			$vipRewardLists = NULL;
			$queryString = "(SELECT {$select} FROM {$dbprefix}player_request_award WHERE (1=1) $where)";
			$queryOrder = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$query = $this->db->query($queryString . $queryOrder);
			if($query->num_rows() > 0)
			{
				$vipRewardLists = $query->result();  
			}
			$query->free_result();

			$query = $this->db->query($queryString);
			$totalFiltered = $query->num_rows();
			$query->free_result();
			
			$sql = "SELECT player_id, username FROM {$dbprefix}players";
			$query = $this->db->query($sql);
			$playerList = array();
			if($query->num_rows() > 0)
			{
				$playerList = $query->result();  
			}
			$query->free_result();
			$arrPlayer = array();
			foreach($playerList as $playerItem){
				$arrPlayer[$playerItem->player_id] = $playerItem->username;
			}

			$sql = "SELECT level_id, level_name FROM {$dbprefix}level";
			$query = $this->db->query($sql);
			$levelList = array();
			if($query->num_rows() > 0)
			{
				$levelList = $query->result();  
			}
			$query->free_result();
			$arrLevel = array();
			foreach($levelList as $levelItem){
				$arrLevel[$levelItem->level_id] = $levelItem->level_name;
			}
			//Prepare data
			$data = array();
			if(!empty($vipRewardLists))
			{
				foreach ($vipRewardLists as $vipRewardItem)
				{
					$status = "Pending";
					if($vipRewardItem->status == STATUS_APPROVE){
						$status = "Approved";
					} else if($vipRewardItem->status == STATUS_CANCEL){
						$status = "Rejected";
					}

					$row = array();
					$row[] = (!empty($vipRewardItem->created_date)) ? $vipRewardItem->created_date : '-';
					$row[] = isset($arrPlayer[$vipRewardItem->player_id])?$arrPlayer[$vipRewardItem->player_id]:'';
					$row[] = isset($arrLevel[$vipRewardItem->old_level_id])?$arrLevel[$vipRewardItem->old_level_id]:'';
					$row[] = isset($arrLevel[$vipRewardItem->new_level_id])?$arrLevel[$vipRewardItem->new_level_id]:'';
					$row[] = $vipRewardItem->total_bet_amount_valid;
					$row[] = $vipRewardItem->level_up_bonus;
					$row[] = '<span id="uc8_' . $vipRewardItem->id . '">' . $status . '</span>';
					$row[] = '<span id="uc6_' . $vipRewardItem->id . '">' . (( ! empty($vipRewardItem->updated_by)) ? $vipRewardItem->updated_by : '-') . '</span>';
					$row[] = '<span id="uc7_' . $vipRewardItem->id . '">' . (( ! empty($vipRewardItem->updated_date)) ? $vipRewardItem->updated_date : '-') . '</span>';
					$button = "";
					if(permission_validation(PERMISSION_AGENT_UPDATE) == TRUE && $vipRewardItem->status == STATUS_REQUEST_AWARD)
					{
						$button .= '<i id="uc3_' . $vipRewardItem->id . '" onclick="updateData(' . $vipRewardItem->id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
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
	
	public function edit($id = NULL){

		if(permission_validation(PERMISSION_VIP_REWARD_UPDATE) == TRUE)
		{
			$data = $this->vipreward_model->getRequestAwardData($id);
			$data['player_info'] = $this->vipreward_model->getPlayerInfo();
			if( ! empty($data) && $data['status'] == STATUS_REQUEST_AWARD)
			{
				$this->load->view('vipreward_update', $data);
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
	
	public function update() {

		if(permission_validation(PERMISSION_VIP_REWARD_UPDATE) == TRUE) {
			#Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => '',
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			#Set form rules
			$config = array(
				array(
					'field' => 'remark',
					'label' => strtolower($this->lang->line('label_remark')),
					'rules' => 'trim'
				)
			);		
			
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			#Form validation
			if ($this->form_validation->run() == TRUE) {
				$row_id = trim($this->input->post('row_id', TRUE));
				$oldData 	= $this->vipreward_model->getRequestAwardData($row_id);
				
				if( ! empty($oldData) && $oldData['status'] == STATUS_REQUEST_AWARD) {
					$status = trim($this->input->post('status', TRUE));
					$updatedDate = date("Y-m-d H:i:s");
					$updatedBy = $this->session->userdata('username');
					$statusString = "-";
					if($status == STATUS_APPROVE){
						$statusString = "Approved";
					} else if($status == STATUS_CANCEL){
						$statusString = "Rejected";
					}
					$json['response'] = array(
						'id' => $row_id,
						'updated_date' => $updatedDate,
						'updated_by' => $updatedBy,
						'status' => $statusString
					);
					$sql = "UPDATE bctp_player_request_award SET status = ".$status.", updated_date = '".$updatedDate."', updated_by = '".$updatedBy."' 
							WHERE id = ".$row_id;
					$this->db->query($sql);
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('success_updated');
				}
			}
			
			#Output
			$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
			unset($json);
			exit();	
		}	
	}
}
