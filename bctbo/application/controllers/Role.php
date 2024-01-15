<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('group_model','avatar_model', 'miscellaneous_model', 'game_model','role_model'));
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index(){
		if(permission_validation(PERMISSION_USER_ROLE_VIEW) == TRUE)
		{
			$this->save_current_url('role');
			$data['page_title'] = $this->lang->line('title_user_role');

			$this->session->unset_userdata('search_user_role');
			$data_search = array(
				'name' => "",
				'status' => "-1",
			);
			$data['data_search'] = $data_search;
			$this->session->set_userdata('search_user_role', $data_search);
			$this->load->view('user_role_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function search(){
		if(permission_validation(PERMISSION_USER_ROLE_VIEW) == TRUE)
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
			
			$data = array(
			    'name' => trim($this->input->post('name', TRUE)),
				'status' => trim($this->input->post('status', TRUE)),
			);
			
			$this->session->set_userdata('search_user_role', $data);
			
			$json['status'] = EXIT_SUCCESS;
				
			
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
		if(permission_validation(PERMISSION_USER_ROLE_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
				'user_role_id',
				'role_name',
				'remark',
				'active',
				'level',
				'updated_by',
				'updated_date',
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
			
			$arr = $this->session->userdata('search_user_role');
			$username = $this->session->userdata('username');
			$where = "WHERE created_by = '$username' ";		
				
			if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_INACTIVE)
			{
				if($where == ""){
					$where .= 'WHERE active = ' . $arr['status'];
				}else{
					$where .= ' AND active = ' . $arr['status'];
				}
			}
			
			if( ! empty($arr['name']))
			{
				if($where == ""){
					$where .= "WHERE role_name LIKE '%" . $arr['name']."%'";
				}else{
					$where .= " AND role_name LIKE '%" . $arr['name']."%'";
				}
			}
			
			
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;

			$posts = NULL;
			$query_string = "SELECT {$select} FROM {$dbprefix}user_role $where";
			if($start != null || $limit != null) {
				$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			}
			else {
				$query_string_2 = " ORDER by {$order} {$dir}";
			}	
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
					$row[] = $post->user_role_id;
					$row[] = '<span id="uc1_' . $post->user_role_id . '">' . $post->role_name . '</span>';
					$row[] = '<span id="uc5_' . $post->user_role_id . '">' . $post->remark . '</span>';
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc2_' . $post->user_role_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc2_' . $post->user_role_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					$row[] = '<span id="uc6_' . $post->user_role_id . '">' . (( ! empty($post->level)) ? $post->level : '-') . '</span>';
					$row[] = '<span id="uc3_' . $post->user_role_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc4_' . $post->user_role_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					
					$button = '';
					if(permission_validation(PERMISSION_USER_ROLE_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->user_role_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_USER_ROLE_DELETE) == TRUE)
					{
						#$button .= '<i onclick="deleteData(' . $post->user_role_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
					}
					
					if( ! empty($button))
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

	public function add(){
		if(permission_validation(PERMISSION_USER_ROLE_ADD) == TRUE)
		{
			$user_permissions 	= $this->get_current_user_permissions();
			$data['permissions'] = array();
			$arr = $user_permissions;
			for($i=0;$i<sizeof($arr);$i++)
			{
				$data['permissions'][$arr[$i]]['upline'] = TRUE;
			}
			$data["level_list"] = $this->role_model->get_level_list();
			$this->load->view('user_role_add', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function submit(){
		if(permission_validation(PERMISSION_USER_ROLE_ADD) == TRUE)
		{
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'role_name_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			//Set form rules
			$config = array(
				array(
						'field' => 'role_name',
						'label' => strtolower($this->lang->line('label_name')),
						'rules' => 'trim|required',
						'errors' => array(
								'required' => $this->lang->line('error_enter_match_name')
						)
				),
				array(
						'field' => 'level',
						'label' => strtolower($this->lang->line('label_level')),
						'rules' => 'trim|required|integer',

						'errors' => array(

								'required' => $this->lang->line('error_select_level'),

								'integer' => $this->lang->line('error_only_digits_allowed')

						)
				),
			);	
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run() == TRUE)
			{
				$user_permissions 	= $this->get_current_user_permissions();
				$post_permissions_real = $this->input->post('permissions[]', TRUE);
				$post_permissions = array_values(array_unique($post_permissions_real));
				$verified_permissions = array();
				

				for($i=0;$i<sizeof($post_permissions);$i++)
				{
					if(in_array($post_permissions[$i], $user_permissions))
					{
						array_push($verified_permissions, $post_permissions[$i]);
					}
				}

				$permissions = implode(',', $verified_permissions);
				$this->db->trans_start();
				$newData = $this->role_model->add_role($permissions);
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_USER_ROLE_ADD, $newData);
				}
				else
				{
					$this->account_model->insert_log(LOG_USER_ROLE_ADD, $newData);
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
				$json['msg']['role_name_error'] = form_error('role_name');
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
		$user_permissions 	= $this->get_current_user_permissions();
		if(permission_validation(PERMISSION_USER_ROLE_UPDATE) == TRUE) {
			$data 				= $this->role_model->get_role_data($id);
			$permissions 		= explode(',', $data['permissions']);
			$old_permissions 	= array_values(array_filter($permissions));

			$data['permissions'] = array();
			$arr = $user_permissions;
			if(ENVIRONMENT == 'development') {
				array_push($arr,41,176,177,140);
			}
			for($i=0;$i<sizeof($arr);$i++) {
				$data['permissions'][$arr[$i]]['upline'] = TRUE;
				#ad($data['permissions']);
				if(in_array($arr[$i], $old_permissions))
				{
					$data['permissions'][$arr[$i]]['selected'] = TRUE;
				}
				else{
					$data['permissions'][$arr[$i]]['selected'] = FALSE;
				}
			}
			$data["level_list"] = $this->role_model->get_level_list();
			$this->load->view('user_role_update', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function update(){
		if(permission_validation(PERMISSION_USER_ROLE_UPDATE) == TRUE)
		{
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'role_name_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			//Set form rules
			$config = array(
				array(
						'field' => 'role_name',
						'label' => strtolower($this->lang->line('label_name')),
						'rules' => 'trim|required',
						'errors' => array(
								'required' => $this->lang->line('error_enter_match_name')
						)
				),
				array(
						'field' => 'level',
						'label' => strtolower($this->lang->line('label_level')),
						'rules' => 'trim|required|integer',

						'errors' => array(

								'required' => $this->lang->line('error_select_level'),

								'integer' => $this->lang->line('error_only_digits_allowed')

						)
				),
			);	
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run() == TRUE)
			{
				$user_role_id = trim($this->input->post('user_role_id', TRUE));
				$oldData = $this->role_model->get_role_data($user_role_id);
				$user_permissions 	= $this->get_current_user_permissions();
				if( ! empty($oldData))
				{
					$post_permissions_real = $this->input->post('permissions[]', TRUE);
					$post_permissions = array_values(array_unique($post_permissions_real));
					$upline_permissions = $user_permissions;
					$verified_permissions = array();
					

					for($i=0;$i<sizeof($post_permissions);$i++)
					{
						if(in_array($post_permissions[$i], $upline_permissions))
						{
							array_push($verified_permissions, $post_permissions[$i]);
						}
					}

					$permissions = implode(',', $verified_permissions);
					$this->db->trans_start();
					$newData = $this->role_model->update_role($oldData['user_role_id'], $permissions);
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_USER_ROLE_UPDATE, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_USER_ROLE_UPDATE, $newData, $oldData);
					}
					
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_updated');

						if(TELEGRAM_STATUS == STATUS_ACTIVE){
							$newData['old_role_data'] = $oldData;
							send_logs_telegram(TELEGRAM_LOGS,TELEGRAM_LOGS_TYPE_UPDATE_CHARACTER_PERMISSION,$newData);
						}

						$json['response'] = array(
							'id' => $newData['user_role_id'],
							'role_name' => $newData['role_name'],
							'remark' => $newData['remark'],
							'active' => (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
							'level' =>	$newData['level'],
							'active_code' => $newData['active'],
							'updated_by' => $newData['updated_by'],
							'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),
						);
					}
					else
					{
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
					}
				}else{

				}
			}else{
				$json['msg']['role_name_error'] = form_error('role_name');
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

	/**
	 * Get role's permissions of current logged-in user
	 * @return array $user_permissions
	 */
	private function get_current_user_permissions() {
		$user_role = $this->role_model->get_role_data($this->session->userdata('user_role'));
		$user_permissions = explode(',', $user_role['permissions']);
		$user_permissions 	= array_values(array_filter($user_permissions));
		return $user_permissions;
	}
}