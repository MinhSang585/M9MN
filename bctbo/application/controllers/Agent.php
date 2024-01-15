<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('agent_model'));
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index()
	{

		if(permission_validation(PERMISSION_AGENT_VIEW) == TRUE)
		{
			$this->save_current_url('agent');
			$data = quick_search();
			$data['page_title'] = "Agent";
			$this->session->unset_userdata('search_agent');
			$data['username'] = $this->session->userdata('root_username');
			$data_search = array(
				'from_date' => date('Y-m-d 00:00:00',strtotime('first day of -3 month',time())),
				'to_date' => date('Y-m-d 23:59:59'),
				'status' => -1
			);
			$data['data_search'] = $data_search;
			$this->session->set_userdata('search_agent', $data_search);
			$this->load->view('agent_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	
	public function search()
	{
		if(permission_validation(PERMISSION_AGENT_VIEW) == TRUE)
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
					
					$this->session->set_userdata('search_agent', $data);
					
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
		
		if(permission_validation(PERMISSION_AGENT_VIEW) == TRUE) {
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			#Table Columns
			$columns = array(
				0 => 'user_id',
				1 => 'created_date',
				2 => 'username',
				3 => 'full_name',
				4 => 'mobile',
				5 => 'bank_name',
				6 => 'account_number',
				7 => 'active',
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
			
			$arr = $this->session->userdata('search_agent');
			
			$where = '';		

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

				if($arr['status'] == STATUS_PENDING OR $arr['status'] == STATUS_APPROVE OR $arr['status'] == STATUS_CANCEL)
				{
					$where .= ' AND active = ' . $arr['status'];
				}
			}	
			
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;
			
			$agentList = NULL;
			$queryString = "(SELECT {$select} FROM {$dbprefix}users_agents WHERE (user_type IS NULL) $where)";
			$queryOrder = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$query = $this->db->query($queryString . $queryOrder);
			if($query->num_rows() > 0)
			{
				$agentList = $query->result();  
			}
			$query->free_result();

			$query = $this->db->query($queryString);
			$totalFiltered = $query->num_rows();
			$query->free_result();
			
			//Prepare data
			$data = array();
			if(!empty($agentList))
			{
				foreach ($agentList as $agent)
				{
					$status = "Pending";
					if($agent->active == STATUS_APPROVE){
						$status = "Approved";
					} else if($agent->active == STATUS_CANCEL){
						$status = "Rejected";
					}

					$row = array();
					$row[] = (($agent->created_date > 0) ? date('Y-m-d H:i:s', $agent->created_date) : '-');
					$row[] = $agent->username;
					$row[] = $agent->full_name;
					$row[] = $agent->mobile;
					$row[] = $agent->bank_name;
					$row[] = $agent->account_number;
					$row[] = '<span id="uc8_' . $agent->user_id . '">' . $status . '</span>';
					$row[] = '<span id="uc6_' . $agent->user_id . '">' . (( ! empty($agent->updated_by)) ? $agent->updated_by : '-') . '</span>';
					$row[] = '<span id="uc7_' . $agent->user_id . '">' . (($agent->updated_date > 0) ? date('Y-m-d H:i:s', $agent->updated_date) : '-') . '</span>';
					$button = "";
					if(permission_validation(PERMISSION_AGENT_UPDATE) == TRUE && $agent->active == STATUS_PENDING)
					{
						$button .= '<i id="uc3_' . $agent->user_id . '" onclick="updateData(' . $agent->user_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
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

		if(permission_validation(PERMISSION_AGENT_UPDATE) == TRUE)
		{
			$data = $this->agent_model->get_agent_data($id);
			if( ! empty($data) && $data['active'] == STATUS_PENDING)
			{
				$this->load->view('agent_update', $data);
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

		if(permission_validation(PERMISSION_AGENT_UPDATE) == TRUE) {
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
				$user_id = trim($this->input->post('user_id', TRUE));
				$oldData 	= $this->agent_model->get_agent_data($user_id);
				
				if( ! empty($oldData) && $oldData['active'] == STATUS_PENDING) {
					$status = trim($this->input->post('status', TRUE));
					if($status == STATUS_APPROVE){
						
						$data = [
							"nickname" => $oldData['nickname'],
							"mobile" => $oldData['mobile'],
							"email" => $oldData['email'],
							"username" => $oldData['username'],
							"password" => $oldData['password'],
							"user_type" => $oldData['user_type'],
							"active" => 1,
							"user_role" => $oldData['user_role'],
							"permissions" => $oldData['permissions'],
							"full_name" => $oldData['full_name'],
							"bank_name" => $oldData['bank_name'],
							"account_number" => $oldData['account_number'],
							"bank_branch" => $oldData['bank_branch'],
							"points" => $oldData['points'],
							"possess" => $oldData['possess'],
							"old_password" => $oldData['old_password'],
							"casino_comm" => $oldData['casino_comm'],
							"slots_comm" => $oldData['slots_comm'],
							"sport_comm" => $oldData['sport_comm'],
							"lottery_comm" => $oldData['lottery_comm'],
							"cf_comm" => $oldData['cf_comm'],
							"other_comm" => $oldData['other_comm'],
							"upline" => $oldData['upline'],
							"upline_ids" => $oldData['upline_ids'],
							"last_login_date" => $oldData['last_login_date'],
							"last_login_ip" => $oldData['last_login_ip'],
							"login_token" => $oldData['login_token'],
							"referral_code" => $oldData['referral_code'],
							"domain_name" => $oldData['domain_name'],
							"domain_sub" => $oldData['domain_sub'],
							"domain" => $oldData['domain'],
							"white_list_ip" => $oldData['white_list_ip'],
							"created_by" => $oldData['created_by'],
							"created_date" => $oldData['created_date'],
							"updated_by" => $oldData['updated_by'],
							"updated_date" => $oldData['updated_date'],
						];
						$sql = "INSERT INTO bctp_users (";
						$sql .= implode(", ", array_keys($data)) . ") VALUES (";
						$sql .= "'" . implode("', '", $data) . "')";
						$this->db->query($sql);
					}

					$updatedDate = time();
					$updatedBy = $this->session->userdata('username');
					$statusString = "-";
					if($status == STATUS_APPROVE){
						$statusString = "Approved";
					} else if($status == STATUS_CANCEL){
						$statusString = "Rejected";
					}
					$json['response'] = array(
						'id' => $user_id,
						'updated_date' => date("Y-m-d H:i:s",$updatedDate),
						'updated_by' => $updatedBy,
						'status' => $statusString
					);
					$sql = "UPDATE bctp_users_agents SET active = ".$status.", updated_date = ".$updatedDate.", updated_by = '".$updatedBy."' 
							WHERE user_id = ".$user_id;
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
