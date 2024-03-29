<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Account extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('role_model'));
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}
	public function index()
	{
		if(permission_validation(PERMISSION_SUB_ACCOUNT_VIEW) == TRUE)
		{
			$this->save_current_url('account');
			$data['page_title'] = $this->lang->line('title_sub_account');
			$data['username'] = $this->session->userdata('root_username');
			$this->load->view('account_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function listing($username)
    {
		if(permission_validation(PERMISSION_SUB_ACCOUNT_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'sub_account_id',
				1 => 'username',
				2 => 'user_role',
				3 => 'nickname',
				4 => 'white_list_ip',
				5 => 'active',
				6 => 'created_date',
				7 => 'last_login_date',
				8 => 'last_login_ip',
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
			$creatorUsername = $this->session->userdata('username');
			$response = $this->user_model->get_downline_data($upline);
			if(empty($response))
			{
				$upline = '';
			}
			$query = array(
				'select' => implode(',', $columns),
				'search_values' => array($creatorUsername),
				'search_types' => array('equal'),
				'search_columns' => array('created_by'),
				'table' => 'sub_accounts',
				'limit' => $limit,
				'start' => $start,
				'order' => $order,
				'dir' => $dir,
			);
			$posts =  $this->general_model->all_posts($query);
			$totalFiltered = $this->general_model->all_posts_count($query);
			//Prepare data
			$data = array();
			if(!empty($posts))
			{
				$role = $this->role_model->get_role_list_by_id();
				foreach ($posts as $post)
				{
					$row = array();
					$login_info = "";
					$row[] = $post->sub_account_id;
					$row[] = $post->username;
					$row[] = '<span id="uc3_' . $post->sub_account_id . '">' . ((isset($role[$post->user_role])) ? $role[$post->user_role]['role_name']: '-') . '</span>';
					$row[] = '<span id="uc1_' . $post->sub_account_id . '">' . $post->nickname . '</span>';
					$row[] = '<span id="uc101_' . $post->sub_account_id . '">' . ((!empty($post->white_list_ip)) ? $post->white_list_ip : '-') . '</span>';
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc2_' . $post->sub_account_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc2_' . $post->sub_account_id . '">' . $this->lang->line('status_suspend') . '</span>'; break;
					}
					$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
					$login_info .= (($post->last_login_date > 0) ? date('Y-m-d H:i:s', $post->last_login_date)."<br>" : '');
					$login_info .= ((!empty($post->last_login_ip)) ? $post->last_login_ip."<br>" : '');
					$row[] = ((!empty($login_info)) ? $login_info : '-');
					$button = '';
					if(permission_validation(PERMISSION_SUB_ACCOUNT_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->sub_account_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_PERMISSION_SETUP) == TRUE)
					{
						$button .= '<i onclick="permissionSetup(' . $post->sub_account_id . ')" class="fas fa-lock nav-icon text-orange" title="' . $this->lang->line('button_permissions')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_CHANGE_PASSWORD) == TRUE)
					{
						$button .= '<i onclick="changePassword(' . $post->sub_account_id . ')" class="fas fa-key nav-icon text-secondary" title="' . $this->lang->line('button_change_password')  . '"></i>';
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
	public function add($username = NULL)
    {
		if(permission_validation(PERMISSION_SUB_ACCOUNT_ADD) == TRUE)
		{
			$data['username'] = $username;
			$data['role_list'] = $this->role_model->get_role_list_by_creator($this->session->userdata('username'));
			$this->load->view('account_add', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function submit()
	{
		if(permission_validation(PERMISSION_SUB_ACCOUNT_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'username_error' => '',
										'password_error' => '',
										'passconf_error' => '',
										'white_list_ip_error' => '',
										'general_error' => ''
									), 	
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			//Set form rules
			$config = array(
							array(
									'field' => 'nickname',
									'label' => strtolower($this->lang->line('label_nickname')),
									'rules' => 'trim'
							),
							array(
									'field' => 'username',
									'label' => strtolower($this->lang->line('label_username')),
									'rules' => 'trim|required|min_length[6]|max_length[16]|regex_match[/^[a-z0-9-]+$/]|is_unique[users.username]|is_unique[sub_accounts.username]|is_unique[players.username]',
									'errors' => array(
														'required' => $this->lang->line('error_enter_username'),
														'min_length' => $this->lang->line('error_invalid_username'),
														'max_length' => $this->lang->line('error_invalid_username'),
														'regex_match' => $this->lang->line('error_invalid_username'),
														'is_unique' => $this->lang->line('error_username_already_exits')
												)
							),
							array(
									'field' => 'password',
									'label' => strtolower($this->lang->line('label_password')),
									'rules' => 'trim|required|min_length[6]|max_length[15]|regex_match[/^[A-Za-z0-9!#$^*]+$/]',
									'errors' => array(
														'required' => $this->lang->line('error_enter_password'),
														'min_length' => $this->lang->line('error_invalid_password'),
														'max_length' => $this->lang->line('error_invalid_password'),
														'regex_match' => $this->lang->line('error_invalid_password')
												)
							),
							array(
									'field' => 'passconf',
									'label' => strtolower($this->lang->line('label_confirm_password')),
									'rules' => 'trim|required|matches[password]',
									'errors' => array(
														'required' => $this->lang->line('error_enter_confirm_password'),
														'matches' => $this->lang->line('error_confirm_password_not_match')
												)
							),
							array(
									'field' => 'white_list_ip[]',
									'label' => strtolower($this->lang->line('label_white_list_ip')),
									'rules' => 'trim|valid_ip',
									'errors' => array(
										'valid_ip' => $this->lang->line('error_valid_ip')
									)
							),
							array(
									'field' => 'user_role',
									'label' => strtolower($this->lang->line('label_user_role')),
									'rules' => 'trim|required',
									'errors' => array(
										'required' => $this->lang->line('error_select_user_role'),
									)
							),
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$upline = trim($this->input->post('upline', TRUE));
				$response = $this->user_model->get_downline_data($upline);
				if( ! empty($response))
				{
					//Database update
					$this->db->trans_start();
					$newData = $this->account_model->add_sub_account($response);
					$this->user_model->insert_log(LOG_SUB_ACCOUNT_ADD, $newData);
					$this->db->trans_complete();
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_added');
						if(TELEGRAM_STATUS == STATUS_ACTIVE){
							$newData['role_data'] = $this->role_model->get_role_data($newData['user_role']);
							send_logs_telegram(TELEGRAM_LOGS,TELEGRAM_LOGS_TYPE_CREATE_SUB_ACCOUNT,$newData);
						}
					}
					else
					{
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');
					}
				}
				else {
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');
				}
			}
			else 
			{
				$json['msg']['username_error'] = form_error('username');
				$json['msg']['password_error'] = form_error('password');
				$json['msg']['passconf_error'] = form_error('passconf');
				$json['msg']['white_list_ip_error'] = form_error('white_list_ip[]');
				if( ! empty(form_error('user_role')))
				{
					$json['msg']['general_error'] = form_error('user_role');
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
	public function edit($id = NULL)
    {
		if(permission_validation(PERMISSION_SUB_ACCOUNT_UPDATE) == TRUE)
		{
			$data = $this->account_model->get_sub_account_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$data['role_list'] = $this->role_model->get_role_list();
					$this->load->view('account_update', $data);
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
	public function update()
	{
		if(permission_validation(PERMISSION_SUB_ACCOUNT_UPDATE) == TRUE)
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
						'field' => 'nickname',
						'label' => strtolower($this->lang->line('label_nickname')),
						'rules' => 'trim'
				),
				array(
						'field' => 'white_list_ip[]',
						'label' => strtolower($this->lang->line('label_white_list_ip')),
						'rules' => 'trim|valid_ip',
						'errors' => array(
							'valid_ip' => $this->lang->line('error_valid_ip')
						)
				),
				array(
						'field' => 'user_role',
						'label' => strtolower($this->lang->line('label_user_role')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_select_user_role'),
						)
				),
			);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$sub_account_id = trim($this->input->post('sub_account_id', TRUE));
				$oldData = $this->account_model->get_sub_account_data($sub_account_id);
				if( ! empty($oldData))
				{
					$response = $this->user_model->get_downline_data($oldData['upline']);
					if( ! empty($response))
					{
						//Database update
						$this->db->trans_start();
						$newData = $this->account_model->update_sub_account($oldData);
						$this->user_model->insert_log(LOG_SUB_ACCOUNT_UPDATE, $newData, $oldData);
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');
							$role = $this->role_model->get_role_data($newData['user_role']);
							//Prepare for ajax update
							$json['response'] = array(
								'id' => $newData['sub_account_id'],
								'nickname' => $newData['nickname'],
								'active' => (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_suspend')),
								'active_code' => $newData['active'],
								'role_name' => ((!empty($role)) ? $role['role_name'] : "-"),
								'white_list_ip' => ((!empty($newData['white_list_ip'])) ? $newData['white_list_ip'] : "-"),
							);
							if(TELEGRAM_STATUS == STATUS_ACTIVE){
								if($newData['user_role'] != $oldData['user_role']){
									$newData['old_role_data'] = $this->role_model->get_role_data($oldData['user_role']);
									$newData['new_role_data'] = $this->role_model->get_role_data($newData['user_role']);
									send_logs_telegram(TELEGRAM_LOGS,TELEGRAM_LOGS_TYPE_UPDATE_SUB_ACCOUNT_CHARACTER,$newData);
								}
							}
						}
						else
						{
							$json['msg'] = $this->lang->line('error_failed_to_update');
						}
					}
					else
					{
						$json['msg'] = $this->lang->line('error_failed_to_update');
					}	
				}
				else
				{
					$json['msg'] = $this->lang->line('error_failed_to_update');
				}	
			}
			else 
			{
				$json['msg']['white_list_ip_error'] = form_error('white_list_ip[]');
				$json['msg'] = $this->lang->line('error_failed_to_update');
				if( ! empty(form_error('user_role')))
				{
					$json['msg']['general_error'] = form_error('user_role');
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
	public function permission($id = NULL)
    {
		if(permission_validation(PERMISSION_PERMISSION_SETUP) == TRUE)
		{
			$data = $this->account_model->get_sub_account_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$arr = get_admin_full_permission();
					$downline_permissions = explode(',', $data['permissions']);
					$upline_permissions = explode(',', $response['permissions']);
					$data['permissions'] = array();
					for($i=0;$i<sizeof($arr);$i++)
					{
						$data['permissions'][$arr[$i]]['downline'] = FALSE;
						if(in_array($arr[$i], $downline_permissions))
						{
							$data['permissions'][$arr[$i]]['downline'] = TRUE;
						}
						$data['permissions'][$arr[$i]]['upline'] = FALSE;
						if(in_array($arr[$i], $upline_permissions))
						{
							$data['permissions'][$arr[$i]]['upline'] = TRUE;
						}
					}
					$this->load->view('account_permission', $data);
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
	public function permission_setup()
	{
		if(permission_validation(PERMISSION_PERMISSION_SETUP) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => '', 
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			$sub_account_id = trim($this->input->post('sub_account_id', TRUE));
			$oldData = $this->account_model->get_sub_account_data($sub_account_id);
			if( ! empty($oldData))
			{
				$response = $this->user_model->get_downline_data($oldData['upline']);
				if( ! empty($response))
				{
					$post_permissions = $this->input->post('permissions[]', TRUE);
					$upline_permissions = explode(',', $response['permissions']);
					$verified_permissions = array();
					for($i=0;$i<sizeof($post_permissions);$i++)
					{
						if($post_permissions[$i] != PERMISSION_SUB_ACCOUNT_ADD && $post_permissions[$i] != PERMISSION_SUB_ACCOUNT_UPDATE && $post_permissions[$i] != PERMISSION_SUB_ACCOUNT_VIEW)
						{
							if(in_array($post_permissions[$i], $upline_permissions))
							{
								array_push($verified_permissions, $post_permissions[$i]);
							}
						}	
					}
					$permissions = implode(',', $verified_permissions);
					//Database update
					$this->db->trans_start();
					$newData = $this->account_model->update_sub_account_permission($oldData, $permissions);
					$this->user_model->insert_log(LOG_SUB_ACCOUNT_PERMISSION, $newData, $oldData);
					$this->db->trans_complete();
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_permission_setup');
					}
					else
					{
						$json['msg'] = $this->lang->line('error_failed_to_update');
					}
				}
				else
				{
					$json['msg'] = $this->lang->line('error_failed_to_update');
				}
			}
			else
			{
				$json['msg'] = $this->lang->line('error_failed_to_update');
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
	public function password($id = NULL)
    {
		if(permission_validation(PERMISSION_CHANGE_PASSWORD) == TRUE)
		{
			$data = $this->account_model->get_sub_account_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$this->load->view('account_password', $data);
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
	public function password_update()
	{
		if(permission_validation(PERMISSION_CHANGE_PASSWORD) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'password_error' => '',
										'passconf_error' => '',
										'general_error' => ''
									), 
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			//Set form rules
			$config = array(
							array(
									'field' => 'password',
									'label' => strtolower($this->lang->line('label_password')),
									'rules' => 'trim|required|min_length[6]|max_length[15]|regex_match[/^[A-Za-z0-9!#$^*]+$/]',
									'errors' => array(
														'required' => $this->lang->line('error_enter_password'),
														'min_length' => $this->lang->line('error_invalid_password'),
														'max_length' => $this->lang->line('error_invalid_password'),
														'regex_match' => $this->lang->line('error_invalid_password')
												)
							),
							array(
									'field' => 'passconf',
									'label' => strtolower($this->lang->line('label_confirm_password')),
									'rules' => 'trim|required|matches[password]',
									'errors' => array(
														'required' => $this->lang->line('error_enter_confirm_password'),
														'matches' => $this->lang->line('error_confirm_password_not_match')
												)
							)
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$sub_account_id = trim($this->input->post('sub_account_id', TRUE));
				$oldData = $this->account_model->get_sub_account_data($sub_account_id);
				if( ! empty($oldData))
				{
					$response = $this->user_model->get_downline_data($oldData['upline']);
					if( ! empty($response))
					{
						//Database update
						$this->db->trans_start();
						$newData = $this->account_model->update_sub_account_password($oldData);
						$this->user_model->insert_log(LOG_SUB_ACCOUNT_PASSWORD, $newData, $oldData);
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_change_password');
						}
						else
						{
							$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
						}
					}	
					else
					{
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
					}
				}	
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
				}	
			}
			else 
			{
				$json['msg']['password_error'] = form_error('password');
				$json['msg']['passconf_error'] = form_error('passconf');
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