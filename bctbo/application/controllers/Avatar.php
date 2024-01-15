<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Avatar extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('player_model','avatar_model'));
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index(){
		if(permission_validation(PERMISSION_AVATAR_VIEW) == TRUE)
		{
			$this->save_current_url('avatar');
			$data['page_title'] = $this->lang->line('title_avatar');
			$this->session->unset_userdata('searches_avatar');
			$data_search = array(
				'avatar_name' => "",
				'status' => "-1",
			);
			$this->session->set_userdata('searches_avatar', $data_search);
			$this->load->view('avatar_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function search(){
		if(permission_validation(PERMISSION_AVATAR_VIEW) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => array(
						'general_error' => ''
					),
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			
			$data = array( 
				'avatar_name' => trim($this->input->post('avatar_name', TRUE)),
				'status' => trim($this->input->post('status', TRUE)),
			);
			
			$this->session->set_userdata('searches_avatar', $data);
			
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
		if(permission_validation(PERMISSION_AVATAR_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'avatar_id',
				1 => 'avatar_name',
				2 => 'active',
				3 => 'updated_by',
				4 => 'updated_date',
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
			$arr = $this->session->userdata('searches_avatar');
			$where = "";
			
			if(isset($arr['avatar_name']) && !empty($arr['avatar_name'])){
				if($where == ""){
					$where .= 'WHERE avatar_name = "' . $arr['avatar_name']. '"';
				}else{
					$where .= ' AND avatar_name = "' . $arr['avatar_name']. '"';
				}
			}
				

			if(isset($arr['status'])){
				if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_INACTIVE)
				{
					if($where == ""){
						$where .= 'WHERE active = ' . $arr['status'];
					}else{
						$where .= ' AND active = ' . $arr['status'];
					}
				}
			}

			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;

			$posts = NULL;
			$query_string = "SELECT {$select} FROM {$dbprefix}avatar $where";
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
					$row[] = $post->avatar_id;
					$row[] = '<span id="uc1_' . $post->avatar_id . '">' .(( ! empty($post->avatar_name)) ? $post->avatar_name : '-') . '</span>';
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc3_' . $post->avatar_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->avatar_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					$row[] = '<span id="uc4_' . $post->avatar_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc5_' . $post->avatar_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					$button = '';
					if(permission_validation(PERMISSION_AVATAR_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->avatar_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_AVATAR_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->avatar_id . ')" type="button" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i> &nbsp;&nbsp; ';
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

	public function add(){
		if(permission_validation(PERMISSION_AVATAR_ADD) == TRUE)
		{
			$this->load->view('avatar_add');
		}
		else
		{
			redirect('home');
		}
	}

	public function submit(){
		if(permission_validation(PERMISSION_AVATAR_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
							'avatar_image_error' => '',
							'avatar_name_error' => '',
							'general_error' => ''
						), 		
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);

			$config = array(
				array(
						'field' => 'avatar_name',
						'label' => strtolower($this->lang->line('label_avatar_name')),
						'rules' => 'trim|required',
						'errors' => array(
								'required' => $this->lang->line('error_enter_avatar_name'),
						)
				),
			);
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$allow_to_update = TRUE;
				$config['upload_path'] = AVATAR_PATH;
				$config['max_size'] = AVATAR_FILE_SIZE;
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['overwrite'] = TRUE;
				$this->load->library('upload', $config);
				if(isset($_FILES['avatar_image']['size']) && $_FILES['avatar_image']['size'] > 0)
				{
					$new_name = time().rand(1000,9999).".".pathinfo($_FILES["avatar_image"]['name'], PATHINFO_EXTENSION);
					$config['file_name']  = $new_name;
					$this->upload->initialize($config);
					if( ! $this->upload->do_upload('avatar_image')) 
					{
						$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
						$allow_to_update = FALSE;
					}else{
						$_FILES["avatar_image"]['name'] = $new_name;
					}
				}else{
					$json['msg']['avatar_image_error'] = $this->lang->line('error_select_image');
					$allow_to_update = FALSE;
				}

				if($allow_to_update == TRUE)
				{
					//Database update
					$this->db->trans_start();
					$newData = $this->avatar_model->add_avatar();
					
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_AVATAR_ADD, $newData);
					}
					else
					{
						$this->account_model->insert_log(LOG_AVATAR_ADD, $newData);
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
			}else{
				$json['msg']['bank_id_error'] = form_error('bank_id');
				$json['msg']['bank_account_name_error'] = form_error('bank_account_name');
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
		if(permission_validation(PERMISSION_AVATAR_UPDATE) == TRUE)
		{
			$data = $this->avatar_model->get_avatar_data($id);
			$this->load->view('avatar_update', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function update(){
		if(permission_validation(PERMISSION_AVATAR_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
							'avatar_name_error' => '',
							'avatar_image_error' => '',
							'general_error' => ''
						),
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			
			//Set form rules
			$config = array(
				array(
						'field' => 'avatar_name',
						'label' => strtolower($this->lang->line('label_avatar_name')),
						'rules' => 'trim|required|max_length[16]',
						'errors' => array(
							'max_length' => $this->lang->line('error_invalid_nickname'),
							'required' => $this->lang->line('error_enter_avatar_name'),
						)
				),
				array(
					'field' => 'avatar_id',
					'label' => strtolower($this->lang->line('label_hashtag')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_failed_to_update')
					)
				),
			);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$allow_update_avatar = TRUE;
				$randData = NULL;
				$avatar_id = trim($this->input->post('avatar_id', TRUE));
				$oldData = $this->avatar_model->get_avatar_data($avatar_id);

				if( ! empty($oldData))
				{
					if($oldData['active'] == STATUS_ACTIVE && $this->input->post('active', TRUE) == STATUS_INACTIVE){
						$allow_update_avatar = FALSE;
						$randData = $this->avatar_model->get_random_avatar_data($avatar_id);
					}
					if($allow_update_avatar == TRUE || !empty($randData)){
						$allow_to_update = TRUE;
						$config['upload_path'] = AVATAR_PATH;
						$config['max_size'] = AVATAR_FILE_SIZE;
						$config['allowed_types'] = 'gif|jpg|jpeg|png';
						$config['overwrite'] = TRUE;
						$this->load->library('upload', $config);
						if(isset($_FILES['avatar_image']['size']) && $_FILES['avatar_image']['size'] > 0)
						{
							$new_name = time().rand(1000,9999).".".pathinfo($_FILES["avatar_image"]['name'], PATHINFO_EXTENSION);
							$config['file_name']  = $new_name;
							$this->upload->initialize($config);
							if( ! $this->upload->do_upload('avatar_image')) 
							{
								$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
								$allow_to_update = FALSE;
							}else{
								$_FILES["avatar_image"]['name'] = $new_name;
							}
						}

						if($allow_to_update == TRUE)
						{
							//Database update
							$this->db->trans_start();
							$newData = $this->avatar_model->update_avatar($avatar_id);
							
							if($this->session->userdata('user_group') == USER_GROUP_USER) 
							{
								$this->user_model->insert_log(LOG_AVATAR_UPDATE, $newData, $oldData);
							}
							else
							{
								$this->account_model->insert_log(LOG_AVATAR_UPDATE, $newData, $oldData);
							}
							if($oldData['active'] == STATUS_ACTIVE && $newData['active'] == STATUS_INACTIVE){
								$this->avatar_model->reset_player_avatar($oldData['avatar_id'],$randData['avatar_id']);
							}
							$this->db->trans_complete();
							
							if ($this->db->trans_status() === TRUE)
							{
								//Delete old avatar
								if(isset($_FILES['avatar_image']['size']) && $_FILES['avatar_image']['size'] > 0)
								{
									unlink(AVATAR_PATH . $oldData['avatar_image']);
								}
								$json['status'] = EXIT_SUCCESS;
								$json['msg'] = $this->lang->line('success_updated');
								//Prepare for ajax update
								$json['response'] = array(
									'id' => $newData['avatar_id'],
									'avatar_name' => (isset($newData['avatar_name'])) ? $newData['avatar_name'] : $oldData['avatar_name'],
									'active' => (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
									'active_code' => $newData['active'],
									'updated_by' => $newData['updated_by'],
									'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),
								);
							}
							else
							{
								$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
							}
						}
					}else{
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_update_not_enought_active_actor');
					}
				}
				else
				{
					$json['status'] = 6666;
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
				}	
			}
			else 
			{
				$json['msg']['avatar_name_error'] = form_error('avatar_name');
				$json['msg']['general_error'] = form_error('bank_id');
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
					
		if(permission_validation(PERMISSION_AVATAR_DELETE) == TRUE)
		{
			$avatar_id = $this->uri->segment(3);
			$oldData = $this->avatar_model->get_avatar_data($avatar_id);
			$randData = $this->avatar_model->get_random_avatar_data($avatar_id);

			if(!empty($oldData)  && !empty($randData))
			{
				//Database update
				$this->db->trans_start();
				$this->avatar_model->delete_avatar($avatar_id);
				$this->avatar_model->reset_player_avatar($oldData['avatar_id'],$randData['avatar_id']);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_AVATAR_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_AVATAR_DELETE, $oldData);
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
				if(empty($randData)){
					$json['msg'] = $this->lang->line('error_failed_to_delete_not_enought_active_actor');
				}else{
					$json['msg'] = $this->lang->line('error_failed_to_delete');
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
		else
		{
			redirect('home');
		}
	}
}