<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blacklist extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('blacklist_model');
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index()
	{
		if(permission_validation(PERMISSION_BLACKLIST_VIEW) == TRUE)
		{
			$this->save_current_url('blacklist');
			
			$this->session->unset_userdata('search_blacklist');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_blacklist');
			$data_search = array( 
				'from_date' => date('Y-m-d 00:00:00'),
				'to_date' => date('Y-m-d 23:59:59'),
				'blacklist_type' => '',
				'blacklist_value' => '',
				'status' => '',
			);
			$this->session->set_userdata('search_blacklist', $data_search);
			$this->load->view('blacklist_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function search(){
		if(permission_validation(PERMISSION_BLACKLIST_VIEW) == TRUE)
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
					'label' => strtolower($this->lang->line('label_to_date')),
					'rules' => 'trim|required|callback_full_datetime_check',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_datetime_format'),
						'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
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
					'blacklist_type' => trim($this->input->post('blacklist_type', TRUE)),
					'blacklist_value' => trim($this->input->post('blacklist_value', TRUE)),
					'status' => trim($this->input->post('status', TRUE)),
				);
				
				$this->session->set_userdata('search_blacklist', $data);
				
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

	public function listing()
    {
    	if(permission_validation(PERMISSION_BLACKLIST_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'blacklist_id',
				1 => 'blacklist_type',
				2 => 'blacklist_value',
				3 => 'remark',
				4 => 'active',
				5 => 'created_by',
				6 => 'created_date',
				7 => 'updated_by',
				8 => 'updated_date',
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
			$arr = $this->session->userdata('search_blacklist');
			$where = "";
			if( ! empty($arr['from_date']))
			{
				if($where == ""){
					$where .= "WHERE created_date >= " . strtotime($arr['from_date']);
				}else{
					$where .= " AND created_date >= " . strtotime($arr['from_date']);
				}
			}
			
			if( ! empty($arr['to_date']))
			{
				if($where == ""){
					$where .= "WHERE created_date <= " . strtotime($arr['to_date']);
				}else{
					$where .= " AND created_date <= " . strtotime($arr['to_date']);
				}
			}

			if( ! empty($arr['blacklist_type']))
			{
				if($where == ""){
					$where .= "WHERE blacklist_type = '" . trim($arr['blacklist_type']) . "'";
				}else{
					$where .= " AND blacklist_type = '" . trim($arr['blacklist_type']) . "'";
				}
			}

			if( ! empty($arr['blacklist_value']))
			{
				if($where == ""){	
					$where .= "WHERE blacklist_value LIKE '%" . $arr['blacklist_value'] . "%' ESCAPE '!'";
				}else{
					$where .= " AND blacklist_value LIKE '%" . $arr['blacklist_value'] . "%' ESCAPE '!'";
				}
			}

			if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_SUSPEND)
			{
				if($where == ""){
					$where .= "WHERE active = '" . trim($arr['status']) . "'";
				}else{
					$where .= " AND active = '" . trim($arr['status']) . "'";
				}
			}

			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;

			$posts = NULL;
			$query_string = "SELECT {$select} FROM {$dbprefix}blacklists $where";
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
					$row[] = $post->blacklist_id;
					$row[] = '<span id="uc4_' . $post->blacklist_id . '">' . $this->lang->line(get_blacklist_type($post->blacklist_type)) . '</span>';
					$row[] = '<span id="uc1_' . $post->blacklist_id . '">'.$post->blacklist_value. '</span>';
					$row[] = '<span id="uc2_' . $post->blacklist_id . '">' . ( ! empty($post->remark) ? $post->remark : '-') . '</span>';
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc3_' . $post->blacklist_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->blacklist_id . '">' . $this->lang->line('status_suspend') . '</span>'; break;
					}
					$row[] = (( ! empty($post->created_by)) ? $post->created_by : '-');
					$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
					$row[] = '<span id="uc5_' . $post->blacklist_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc6_' . $post->blacklist_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					if(permission_validation(PERMISSION_BLACKLIST_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->blacklist_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_BLACKLIST_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->blacklist_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
					}
					if(permission_validation(PERMISSION_BLACKLIST_UPDATE) == TRUE || permission_validation(PERMISSION_BLACKLIST_DELETE) == TRUE){
						$row[] = $button;
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

    public function add()
    {
		if(permission_validation(PERMISSION_BLACKLIST_ADD) == TRUE)
		{
			$this->load->view('blacklist_add');
		}
		else
		{
			redirect('home');
		}
	}

	public function submit(){
		if(permission_validation(PERMISSION_BLACKLIST_ADD) == TRUE)
		{
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'blacklist_type_error' => '',
					'blacklist_value_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			//Set form rules
			$config = array(
				array(
					'field' => 'blacklist_type',
					'label' => strtolower($this->lang->line('label_type')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_select_type'),
					)
				),
				array(
					'field' => 'blacklist_value',
					'label' => strtolower($this->lang->line('label_black_list_value')),
					'rules' => 'trim|required|min_length[1]|max_length[64]',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_blacklist_value'),
						'min_length' => $this->lang->line('error_invalid_blacklist_value'),
						'max_length' => $this->lang->line('error_invalid_blacklist_value'),
					)
				),
			);

			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run() == TRUE)
			{
				$this->db->trans_start();
				$newData = $this->blacklist_model->add_blacklist();
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_BLACKLIST_ADD, $newData);
				}
				else
				{
					$this->account_model->insert_log(LOG_BLACKLIST_ADD, $newData);
				}
				
				$this->db->trans_complete();
				
				if ($this->db->trans_status() === TRUE)
				{
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('success_added');
				}
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');
				}
			}else{
				$json['msg']['blacklist_type_error'] = form_error('blacklist_type');
				$json['msg']['blacklist_value_error'] = form_error('blacklist_value');
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

	public function edit($id){
		if(permission_validation(PERMISSION_BLACKLIST_UPDATE) == TRUE)
		{
			$data = $this->blacklist_model->get_blacklist_data($id);
			$this->load->view('blacklist_update',$data);
		}
		else
		{
			redirect('home');
		}
	}

	public function update(){
		if(permission_validation(PERMISSION_BLACKLIST_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'blacklist_type_error' => '',
					'blacklist_value_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);

			//Set form rules
			$config = array(
				array(
					'field' => 'blacklist_type',
					'label' => strtolower($this->lang->line('label_type')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_select_type'),
					)
				),
				array(
					'field' => 'blacklist_value',
					'label' => strtolower($this->lang->line('label_black_list_value')),
					'rules' => 'trim|required|min_length[1]|max_length[64]',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_blacklist_value'),
						'min_length' => $this->lang->line('error_invalid_blacklist_value'),
						'max_length' => $this->lang->line('error_invalid_blacklist_value'),
					)
				),
			);	
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$blacklist_id = trim($this->input->post('blacklist_id', TRUE));
				$oldData = $this->blacklist_model->get_blacklist_data($blacklist_id);

				if( ! empty($oldData))
				{
					$allow_to_update = TRUE;
					if($allow_to_update == TRUE)
					{
						//Database update
						$this->db->trans_start();
						$newData = $this->blacklist_model->update_blacklist($blacklist_id);
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_BLACKLIST_UPDATE, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_BLACKLIST_UPDATE, $newData, $oldData);
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');
							
							//Prepare for ajax update
							$json['response'] = array(
								'id' => $newData['blacklist_id'],
								'blacklist_value' => $newData['blacklist_value'],
								'remark' => $newData['remark'],
								'active' => (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
								'active_code' => $newData['active'],
								'updated_by' => $newData['updated_by'],
								'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),
								'blacklist_type' => $this->lang->line(get_blacklist_type($newData['blacklist_type'])),
							);
						}
						else
						{
							$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
						}
					}else{
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
					}
				}
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
				}
			}else{
				$json['msg']['blacklist_type_error'] = form_error('blacklist_type');
				$json['msg']['blacklist_value_error'] = form_error('blacklist_value');
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

	public function delete(){
		//Initial output data
		$json = array(
					'status' => EXIT_ERROR, 
					'msg' => ''
				);
					
		if(permission_validation(PERMISSION_BLACKLIST_DELETE) == TRUE)
		{
			$blacklist_id = $this->uri->segment(3);
			$oldData = $this->blacklist_model->get_blacklist_data($blacklist_id);
			
			if( ! empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->blacklist_model->delete_blacklist($blacklist_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_BLACKLIST_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_BLACKLIST_DELETE, $oldData);
				}
				
				$this->db->trans_complete();
				
				if ($this->db->trans_status() === TRUE)
				{
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('success_deleted');
				}
				else
				{
					$json['msg'] = $this->lang->line('error_failed_to_delete');
				}
			}
			else
			{
				$json['msg'] = $this->lang->line('error_failed_to_delete');
			}	
			
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
					
			exit();	
		}
		else
		{
			redirect('home');
		}
	}

	public function report(){
		if(permission_validation(PERMISSION_BLACKLIST_REPORT) == TRUE)
		{
			$this->save_current_url('blacklist/report');
			
			$this->session->unset_userdata('search_blacklist_report');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_blacklist_report');
			$data_search = array( 
				'from_date' => date('Y-m-d 00:00:00'),
				'to_date' => date('Y-m-d 23:59:59'),
				'status' => -1,
				'username' => '',
				'blacklist_type' => '',
				'blacklist_value' => '',
			);

			if($_GET){
				$blacklists_report_id = (isset($_GET['id'])?$_GET['id']:'');
				$blacklists_data = $this->blacklist_model->get_blacklist_report_data($blacklists_report_id);
				if(!empty($blacklists_data)){
					$data_search['from_date'] = date('Y-m-d 00:00:00',$blacklists_data['report_date']);
					$data_search['to_date'] = date('Y-m-d 23:59:59',$blacklists_data['report_date']);
					$data_search['status'] = STATUS_PENDING;
				}
			}
			$data['data_search'] = $data_search;
			$this->session->set_userdata('search_blacklist_report', $data_search);
			$this->load->view('blacklist_report_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function report_search(){
		if(permission_validation(PERMISSION_BLACKLIST_REPORT) == TRUE)
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
					'label' => strtolower($this->lang->line('label_to_date')),
					'rules' => 'trim|required|callback_full_datetime_check',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_datetime_format'),
						'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
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
					'blacklist_type' => trim($this->input->post('blacklist_type', TRUE)),
					'blacklist_value' => trim($this->input->post('blacklist_value', TRUE)),
					'status' => trim($this->input->post('status', TRUE)),
					'username' => trim($this->input->post('username', TRUE)),
				);
				
				$this->session->set_userdata('search_blacklist_report', $data);
				
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
		else
		{
			redirect('home');
		}
	}

	public function report_listing()
    {
    	if(permission_validation(PERMISSION_BLACKLIST_REPORT) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'blacklists_report_id',
				1 => 'username',
				2 => 'blacklist_type',
				3 => 'blacklist_content',
				4 => 'blacklist_info',
				5 => 'status',
				6 => 'report_date',
				7 => 'updated_by',
				8 => 'updated_date',
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
			$arr = $this->session->userdata('search_blacklist_report');
			$where = "";
			if( ! empty($arr['from_date']))
			{
				if($where == ""){
					$where .= "WHERE report_date >= " . strtotime($arr['from_date']);
				}else{
					$where .= " AND report_date >= " . strtotime($arr['from_date']);
				}
			}
			
			if( ! empty($arr['to_date']))
			{
				if($where == ""){
					$where .= "WHERE report_date <= " . strtotime($arr['to_date']);
				}else{
					$where .= " AND report_date <= " . strtotime($arr['to_date']);
				}
			}

			if( ! empty($arr['blacklist_type']))
			{
				if($where == ""){
					$where .= "WHERE blacklist_type LIKE '%" . trim($arr['blacklist_type']) . "%'";
				}else{
					$where .= " AND blacklist_type LIKE '%" . trim($arr['blacklist_type']) . "%'";
				}
			}

			if( ! empty($arr['blacklist_value']))
			{
				if($where == ""){
					$where .= "WHERE blacklist_content LIKE '%" . trim($arr['blacklist_value']) . "%'";
				}else{
					$where .= " AND blacklist_content LIKE '%" . trim($arr['blacklist_value']) . "%'";
				}
			}

			if($arr['status'] == STATUS_PENDING OR $arr['status'] == STATUS_COMPLETE)
			{
				$where .= ' AND status = ' . $arr['status'];
			}

			if( ! empty($arr['username']))
			{
				if($where == ""){
					$where .= "WHERE username = '" . trim($arr['username']) . "'";
				}else{
					$where .= " AND username = '" . trim($arr['username']) . "'";
				}
			}

			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;

			$posts = NULL;
			$query_string = "SELECT {$select} FROM {$dbprefix}blacklists_report $where";
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
					$blacklist_type_content = "";
					$blacklist_content_content = "";
					$blacklist_info_content = "";
					$row = array();
					$row[] = $post->blacklists_report_id;
					$row[] = $post->username;
					$blacklist_type_array = array_filter(explode(',', $post->blacklist_type));
					if(!empty($blacklist_type_array)){
						foreach($blacklist_type_array as $blacklist_type_array_row){
							$blacklist_type_content .= ((empty($blacklist_type_content)) ? $this->lang->line(get_blacklist_type($blacklist_type_array_row)) : ",".$this->lang->line(get_blacklist_type($blacklist_type_array_row)));
						}
					}

					$blacklist_content_array = json_decode($post->blacklist_content, true);
					if(!empty($blacklist_content_array)){
						foreach($blacklist_content_array as $blacklist_content_array_key => $blacklist_content_array_val) {
							$blacklist_content_content .= ((empty($blacklist_content_content)) ? $this->lang->line(get_blacklist_type($blacklist_content_array_key))." : ".$blacklist_content_array_val : ", ".$this->lang->line(get_blacklist_type($blacklist_content_array_key))." : ".$blacklist_content_array_val);
						}
					}					

					$blacklist_info_array = json_decode($post->blacklist_info, true);
					if(!empty($blacklist_info_array)){
						foreach($blacklist_info_array as $blacklist_info_array_key => $blacklist_info_array_val){
							if(!empty($blacklist_info_array_val)){
								if($blacklist_info_array_key == "mobile"){
									$blacklist_info_content .= ((empty($blacklist_info_content)) ? $this->lang->line("label_mobile")." : ".$blacklist_info_array_val : ", ".$this->lang->line("label_mobile")." : ".$blacklist_info_array_val);
								}
								if($blacklist_info_array_key == "line_id"){
									$blacklist_info_content .= ((empty($blacklist_info_content)) ? $this->lang->line("im_line")." : ".$blacklist_info_array_val : ", ".$this->lang->line("im_line")." : ".$blacklist_info_array_val);
								}
							}
						}
					}
					$row[] = (( ! empty($blacklist_type_content)) ? $blacklist_type_content : '-');
					$row[] = (( ! empty($blacklist_content_content)) ? $blacklist_content_content : '-');
					$row[] = (( ! empty($blacklist_info_content)) ? $blacklist_info_content : '-');
					switch($post->status)
					{
						case STATUS_COMPLETE: $row[] = '<span class="badge bg-success" id="uc3_' . $post->blacklists_report_id . '">' . $this->lang->line('status_completed') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->blacklists_report_id . '">' . $this->lang->line('status_pending') . '</span>'; break;
					}
					$row[] = (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-');
					$row[] = '<span id="uc5_' . $post->blacklists_report_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc6_' . $post->blacklists_report_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					$button = "";
					if(permission_validation(PERMISSION_BLACKLIST_REPORT_UPDATE) == TRUE && $post->status == STATUS_PENDING)
					{
						$button .= '<i id="uc4_' . $post->blacklists_report_id . '" onclick="updateData(' . $post->blacklists_report_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}

					if(permission_validation(PERMISSION_BLACKLIST_REPORT_UPDATE) == TRUE){
						$row[] = $button;
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

    public function report_edit($id){
    	if(permission_validation(PERMISSION_BLACKLIST_REPORT_UPDATE) == TRUE)
		{
			$data = $this->blacklist_model->get_blacklist_report_data($id);
			$blacklist_type_content = "";
			$blacklist_content_content = "";
			$blacklist_info_content = "";
			$blacklist_type_array = array_filter(explode(',', $data['blacklist_type']));
			if(!empty($blacklist_type_array)){
				foreach($blacklist_type_array as $blacklist_type_array_row){
					$blacklist_type_content .= ((empty($blacklist_type_content)) ? $this->lang->line(get_blacklist_type($blacklist_type_array_row)) : ",".$this->lang->line(get_blacklist_type($blacklist_type_array_row)));
				}
			}

			$blacklist_content_array = json_decode($data['blacklist_content'], true);
			if(!empty($blacklist_content_array)){
				foreach($blacklist_content_array as $blacklist_content_array_key => $blacklist_content_array_val) {
					$blacklist_content_content .= ((empty($blacklist_content_content)) ? $this->lang->line(get_blacklist_type($blacklist_content_array_key))." : ".$blacklist_content_array_val : ", ".$this->lang->line(get_blacklist_type($blacklist_content_array_key))." : ".$blacklist_content_array_val);
				}
			}					

			$blacklist_info_array = json_decode($data['blacklist_info'], true);
			if(!empty($blacklist_info_array)){
				foreach($blacklist_info_array as $blacklist_info_array_key => $blacklist_info_array_val){
					if(!empty($blacklist_info_array_val)){
						if($blacklist_info_array_key == "mobile"){
							$blacklist_info_content .= ((empty($blacklist_info_content)) ? $this->lang->line("label_mobile")." : ".$blacklist_info_array_val : ", ".$this->lang->line("label_mobile")." : ".$blacklist_info_array_val);
						}
						if($blacklist_info_array_key == "line_id"){
							$blacklist_info_content .= ((empty($blacklist_info_content)) ? $this->lang->line("im_line")." : ".$blacklist_info_array_val : ", ".$this->lang->line("im_line")." : ".$blacklist_info_array_val);
						}
					}
				}
			}
			$data['blacklist_type_content'] = $blacklist_type_content;
			$data['blacklist_content_content'] = $blacklist_content_content;
			$data['blacklist_info_content'] = $blacklist_info_content;
			$this->load->view('blacklist_report_update',$data);
		}
		else
		{
			redirect('home');
		}
    }

    public function report_update(){
    	if(permission_validation(PERMISSION_BLACKLIST_REPORT_UPDATE) == TRUE)
		{
			$blacklists_report_id = trim($this->input->post('blacklists_report_id', TRUE));
			$oldData =  $this->blacklist_model->get_blacklist_report_data($blacklists_report_id);

			if( ! empty($oldData))
			{
				$allow_to_update = TRUE;
				if($allow_to_update == TRUE)
				{
					//Database update
					$this->db->trans_start();
					$newData = $this->blacklist_model->update_blacklist_report($blacklists_report_id);
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_BLACKLIST_REPORT_UPDATE, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_BLACKLIST_REPORT_UPDATE, $newData, $oldData);
					}
					$this->db->trans_complete();
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_updated');
						
						switch($newData['status'])
						{
							case STATUS_APPROVE: $status = $this->lang->line('status_completed'); break;
							case STATUS_CANCEL: $status = $this->lang->line('status_cancelled'); break;
							default: $status = $this->lang->line('status_pending'); break;
						}

						//Prepare for ajax update
						$json['response'] = array(
							'id' => $newData['blacklists_report_id'],
							'status' => $status,
							'status_code' => $newData['status'],
							'updated_by' => $newData['updated_by'],
							'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),
						);
					}
					else
					{
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
					}
				}else{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
				}
			}
			else
			{
				$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
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

    public function import(){
    	if(permission_validation(PERMISSION_BLACKLIST_IMPORT_VIEW) == TRUE)
		{
			$this->save_current_url('blacklist/import');
			
			$this->session->unset_userdata('search_blacklist_import');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_blacklist_import');
			$data_search = array( 
				'from_date' => date('Y-m-d 00:00:00'),
				'to_date' => date('Y-m-d 23:59:59'),
				'status' => '-1',
			);
			$this->session->set_userdata('search_blacklist_import', $data_search);
			$this->load->view('blacklist_import_view', $data);
		}
		else
		{
			redirect('home');
		}
    }

    public function import_search(){
    	if(permission_validation(PERMISSION_BLACKLIST_IMPORT_VIEW) == TRUE)
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
					'label' => strtolower($this->lang->line('label_to_date')),
					'rules' => 'trim|required|callback_full_datetime_check',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_datetime_format'),
						'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
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
				);
				
				$this->session->set_userdata('search_blacklist_report', $data);
				
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
		else
		{
			redirect('home');
		}
    }

    public function import_listing(){
    	if(permission_validation(PERMISSION_BLACKLIST_IMPORT_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'blacklists_import_id',
				1 => 'filename',
				2 => 'remark',
				3 => 'status',
				4 => 'updated_by',
				5 => 'updated_date',
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
			$arr = $this->session->userdata('search_blacklist_import');
			$where = "";
			if( ! empty($arr['from_date']))
			{
				if($where == ""){
					$where .= "WHERE created_date >= " . strtotime($arr['from_date']);
				}else{
					$where .= " AND created_date >= " . strtotime($arr['from_date']);
				}
			}
			
			if( ! empty($arr['to_date']))
			{
				if($where == ""){
					$where .= "WHERE created_date <= " . strtotime($arr['to_date']);
				}else{
					$where .= " AND created_date <= " . strtotime($arr['to_date']);
				}
			}

			if($arr['status'] == STATUS_PENDING OR $arr['status'] == STATUS_APPROVE OR $arr['status'] == STATUS_CANCEL)
			{
				$where .= ' AND status = ' . $arr['status'];
			}

			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;

			$posts = NULL;
			$query_string = "SELECT {$select} FROM {$dbprefix}blacklists_import $where";
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
					$row[] = $post->blacklists_import_id;
					$row[] = '<a href="'.BLACKLIST_SOURCE_PATH.$post->filename.'" download>'.$post->filename.'</a>';
					$row[] = '<span id="uc2_' . $post->blacklists_import_id . '">' . ( ! empty($post->remark) ? $post->remark : '-') . '</span>';
					switch($post->status)
					{
						case STATUS_APPROVE: $row[] = '<span class="badge bg-success" id="uc1_' . $post->blacklists_import_id . '">' . $this->lang->line('status_approved') . '</span>'; break;
						case STATUS_CANCEL: $row[] = '<span class="badge bg-danger" id="uc1_' . $post->blacklists_import_id . '">' . $this->lang->line('status_cancelled') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc1_' . $post->blacklists_import_id . '">' . $this->lang->line('status_pending') . '</span>'; break;
					}
					$row[] = '<span id="uc6_' . $post->blacklists_import_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc7_' . $post->blacklists_import_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					if(permission_validation(PERMISSION_BLACKLIST_IMPORT_UPDATE) == TRUE)
					{
						if($post->status == STATUS_PENDING){
							$button .= '<i onclick="updateData(' . $post->blacklists_import_id . ')" class="fas fa-wrench nav-icon text-orange" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
						}
					}
					if(permission_validation(PERMISSION_BLACKLIST_IMPORT_DELETE) == TRUE)
					{
						if($post->status == STATUS_PENDING){
							$button .= '<i onclick="deleteData(' . $post->blacklists_import_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i> &nbsp;&nbsp; ';
						}
					}
					if(permission_validation(PERMISSION_BLACKLIST_IMPORT_UPDATE) == TRUE || permission_validation(PERMISSION_BLACKLIST_IMPORT_DELETE) == TRUE)
					{
						$row[] = $button;
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

    public function import_add(){
		if(permission_validation(PERMISSION_BLACKLIST_IMPORT_ADD) == TRUE)
		{
			$this->load->view('blacklist_import_add');
		}
		else
		{
			redirect('home');
		}
	}

	public function import_submit(){
		if(permission_validation(PERMISSION_BLACKLIST_IMPORT_ADD) == TRUE)
		{
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'general_error' => '',
				), 	
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);

			$allow_to_update = TRUE;
				
			$config['upload_path'] = BLACKLIST_PATH;
			$config['max_size'] = BLACKLIST_FILE_SIZE;
			$config['allowed_types'] = 'xlsx';
			$config['overwrite'] = TRUE;
			
			$this->load->library('upload', $config);
			
			if(isset($_FILES['excel_filename']['size']) && $_FILES['excel_filename']['size'] > 0)
			{
				$new_name = time().rand(1000,9999).".".pathinfo($_FILES["excel_filename"]['name'], PATHINFO_EXTENSION);
				$config['file_name']  = $new_name;
				$this->upload->initialize($config);
				if( ! $this->upload->do_upload('excel_filename')) 
				{
					$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
					$allow_to_update = FALSE;
				}else{
					$_FILES["excel_filename"]['name'] = $new_name;
				}
			}else{
				$allow_to_update = FALSE;
				$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
			}

			if($allow_to_update == TRUE)
			{
				$this->db->trans_start();
				$newData = $this->blacklist_model->add_blacklist_import();
				if($this->session->userdata('user_group') == USER_GROUP_USER)
				{
					$this->user_model->insert_log(LOG_BLACKLIST_IMPORT_ADD, $newData);
				}
				else
				{
					$this->account_model->insert_log(LOG_BLACKLIST_IMPORT_ADD, $newData);
				}
				$this->db->trans_complete();
				if ($this->db->trans_status() === TRUE)
				{
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('success_added');
				}
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');
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

	public function import_execute(){
		$json = array(
					'status' => EXIT_ERROR, 
					'msg' => ''
				);
					
		if(permission_validation(PERMISSION_BLACKLIST_IMPORT_UPDATE) == TRUE)
		{
			$blacklists_import_id = $this->uri->segment(3);
			$oldData = $this->blacklist_model->get_blacklist_import_data($blacklists_import_id);
			
			if( ! empty($oldData))
			{
				$username = $this->session->userdata('username');
				$current_time = time();
				$this->load->library('zip');
				$this->load->library('excel');
				$directory_path = BLACKLIST_PATH;
				$filname = $oldData['filename'];
				$filepath = $directory_path.$filname;
				if(file_exists($filepath)){
					$inputFileType = PHPExcel_IOFactory::identify($filepath);
		            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
		            $objPHPExcel = $objReader->load($filepath);
		            $sheetInsertData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

		            $tmp_arr = array();
		            
		            $name_array = array();
		            $line_array = array();
		            $phone_array = array();
		            $id_array = array();
		            $bank_no_array = array();
		            $ip_array = array();
		            if(!empty($sheetInsertData)){
		            	for($i = 2; $i <= sizeof($sheetInsertData); $i++){
		            		if(!empty($sheetInsertData[$i]['A'])){
		            			$tmp_arr = array("blacklist_type"=>BLACKLIST_BANK_NAME,"blacklist_value"=>$sheetInsertData[$i]['A'],"remark"=>$sheetInsertData[$i]['E'],"active"=>STATUS_ACTIVE, "created_by" => $username, "created_date" => $current_time);
		            			array_push($name_array, $tmp_arr);
		            		}

		            		if(!empty($sheetInsertData[$i]['B'])){
		            			$tmp_arr = array("blacklist_type"=>BLACKLIST_LINE_NUMBER,"blacklist_value"=>$sheetInsertData[$i]['B'],"remark"=>$sheetInsertData[$i]['E'],"active"=>STATUS_ACTIVE, "created_by" => $username, "created_date" => $current_time);
		            			array_push($line_array, $tmp_arr);
		            		}

		            		if(!empty($sheetInsertData[$i]['C'])){
		            			$tmp_arr = array("blacklist_type"=>BLACKLIST_PHONE_NUMBER,"blacklist_value"=>$sheetInsertData[$i]['C'],"remark"=>$sheetInsertData[$i]['E'],"active"=>STATUS_ACTIVE, "created_by" => $username, "created_date" => $current_time);
		            			array_push($phone_array, $tmp_arr);
		            		}

		            		if(!empty($sheetInsertData[$i]['F'])){
		            			$tmp_arr = array("blacklist_type"=>BLACKLIST_BANK_ACCOUNT,"blacklist_value"=>$sheetInsertData[$i]['F'],"remark"=>$sheetInsertData[$i]['E'],"active"=>STATUS_ACTIVE, "created_by" => $username, "created_date" => $current_time);
		            			array_push($bank_no_array, $tmp_arr);
		            		}

		            		if(!empty($sheetInsertData[$i]['G'])){
		            			$tmp_arr = array("blacklist_type"=>BLACKLIST_BANK_ACCOUNT,"blacklist_value"=>$sheetInsertData[$i]['G'],"remark"=>$sheetInsertData[$i]['E'],"active"=>STATUS_ACTIVE, "created_by" => $username, "created_date" => $current_time);
		            			array_push($bank_no_array, $tmp_arr);
		            		}

		            		if(!empty($sheetInsertData[$i]['H'])){
		            			$tmp_arr = array("blacklist_type"=>BLACKLIST_BANK_ACCOUNT,"blacklist_value"=>$sheetInsertData[$i]['H'],"remark"=>$sheetInsertData[$i]['E'],"active"=>STATUS_ACTIVE, "created_by" => $username, "created_date" => $current_time);
		            			array_push($bank_no_array, $tmp_arr);
		            		}

		            		if(!empty($sheetInsertData[$i]['I'])){
		            			$tmp_arr = array("blacklist_type"=>BLACKLIST_BANK_ACCOUNT,"blacklist_value"=>$sheetInsertData[$i]['I'],"remark"=>$sheetInsertData[$i]['E'],"active"=>STATUS_ACTIVE, "created_by" => $username, "created_date" => $current_time);
		            			array_push($bank_no_array, $tmp_arr);
		            		}

		            		if(!empty($sheetInsertData[$i]['J'])){
		            			$tmp_arr = array("blacklist_type"=>BLACKLIST_BANK_ACCOUNT,"blacklist_value"=>$sheetInsertData[$i]['J'],"remark"=>$sheetInsertData[$i]['E'],"active"=>STATUS_ACTIVE, "created_by" => $username, "created_date" => $current_time);
		            			array_push($bank_no_array, $tmp_arr);
		            		}

		            		if(!empty($sheetInsertData[$i]['K'])){
		            			$tmp_arr = array("blacklist_type"=>BLACKLIST_IP,"blacklist_value"=>$sheetInsertData[$i]['K'],"remark"=>$sheetInsertData[$i]['E'],"active"=>STATUS_ACTIVE, "created_by" => $username, "created_date" => $current_time);
		            			array_push($ip_array, $tmp_arr);
		            		}

		            		if(!empty($sheetInsertData[$i]['L'])){
		            			$tmp_arr = array("blacklist_type"=>BLACKLIST_IP,"blacklist_value"=>$sheetInsertData[$i]['L'],"remark"=>$sheetInsertData[$i]['E'],"active"=>STATUS_ACTIVE, "created_by" => $username, "created_date" => $current_time);
		            			array_push($ip_array, $tmp_arr);
		            		}

		            		if(!empty($sheetInsertData[$i]['M'])){
		            			$tmp_arr = array("blacklist_type"=>BLACKLIST_IP,"blacklist_value"=>$sheetInsertData[$i]['M'],"remark"=>$sheetInsertData[$i]['E'],"active"=>STATUS_ACTIVE, "created_by" => $username, "created_date" => $current_time);
		            			array_push($ip_array, $tmp_arr);
		            		}

		            		if(!empty($sheetInsertData[$i]['N'])){
		            			$tmp_arr = array("blacklist_type"=>BLACKLIST_IP,"blacklist_value"=>$sheetInsertData[$i]['N'],"remark"=>$sheetInsertData[$i]['E'],"active"=>STATUS_ACTIVE, "created_by" => $username, "created_date" => $current_time);
		            			array_push($ip_array, $tmp_arr);
		            		}

		            		if(!empty($sheetInsertData[$i]['O'])){
		            			$tmp_arr = array("blacklist_type"=>BLACKLIST_IP,"blacklist_value"=>$sheetInsertData[$i]['O'],"remark"=>$sheetInsertData[$i]['E'],"active"=>STATUS_ACTIVE, "created_by" => $username, "created_date" => $current_time);
		            			array_push($ip_array, $tmp_arr);
		            		}
		            	}
		            }

		            if( ! empty($name_array))
					{
						$this->db->insert_batch('blacklists', $name_array);
						
					}

					if( ! empty($line_array))
					{
						$this->db->insert_batch('blacklists', $line_array);
						
					}

					if( ! empty($phone_array))
					{
						$this->db->insert_batch('blacklists', $phone_array);
						
					}

					if( ! empty($bank_no_array))
					{
						$this->db->insert_batch('blacklists', $bank_no_array);
						
					}

					if( ! empty($ip_array))
					{
						$this->db->insert_batch('blacklists', $ip_array);
						
					}

					$newData = $this->blacklist_model->update_blacklist_import_data($blacklists_import_id);
						
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_BLACKLIST_IMPORT_UPDATE, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_BLACKLIST_IMPORT_UPDATE, $newData, $oldData);
					}
					
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_updated');
					}else{
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
					}
		        }
			}
			else
			{
				$json['msg'] = $this->lang->line('error_failed_to_delete');
			}	
			
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
					
			exit();
		}
		else
		{
			redirect('home');
		}
	}

	public function import_delete(){
		//Initial output data
		$json = array(
					'status' => EXIT_ERROR, 
					'msg' => ''
				);
					
		if(permission_validation(PERMISSION_BLACKLIST_IMPORT_DELETE) == TRUE)
		{
			$blacklists_import_id = $this->uri->segment(3);
			$oldData = $this->blacklist_model->get_blacklist_import_data($blacklists_import_id);
			
			if( ! empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->blacklist_model->delete_blacklist_import($blacklists_import_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_BLACKLIST_IMPORT_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_BLACKLIST_IMPORT_DELETE, $oldData);
				}
				
				$this->db->trans_complete();
				
				if ($this->db->trans_status() === TRUE)
				{
					//Delete old banner
					if( ! empty($oldData['filename']))
					{
						unlink(BLACKLIST_PATH . $oldData['filename']);
					}
					
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('success_deleted');
				}
				else
				{
					$json['msg'] = $this->lang->line('error_failed_to_delete');
				}
			}
			else
			{
				$json['msg'] = $this->lang->line('error_failed_to_delete');
			}	
			
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
					
			exit();	
		}
		else
		{
			redirect('home');
		}
	}
}