<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('player_model','tag_model','group_model','level_model'));
	}


	public function index(){
		if(permission_validation(PERMISSION_TAG_VIEW) == TRUE)
		{
			$this->save_current_url('tag');
			$data['page_title'] = $this->lang->line('title_tag');
			$this->load->view('tag_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function listing(){
		if(permission_validation(PERMISSION_TAG_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
				'tag_id',
				'tag_code',
				'tag_times',
				'tag_min',
				'tag_max',
				'tag_font_color',
				'tag_background_color',
				'is_bold',
				'active',
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
			
			$query = array(
				'select' => implode(',', $columns),
				'table' => 'tag',
				'limit' => $limit,
				'start' => $start,
				'order' => $order,
				'dir' => $dir,
			);
			
			$posts =  $this->general_model->all_posts($query);
			$totalFiltered = $this->general_model->all_posts_count($query);

			$data = array();
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					$row = array();
					$row[] = $post->tag_id;
					$row[] = '<span id="uc2_' . $post->tag_id . '">' . $post->tag_code . '</span>';
					$row[] = '<span id="uc1_' . $post->tag_id . '">' . $post->tag_times . '</span>';
					$row[] = '<span id="uc6_' . $post->tag_id . '">' . number_format($post->tag_min, 2, '.', ',') . '</span>';
					$row[] = '<span id="uc7_' . $post->tag_id . '">' . number_format($post->tag_max, 2, '.', ',') . '</span>';
					$row[] = '<div class="progress progress-sm"><div id="uc9_' . $post->tag_id . '" class="progress-bar" style="width: 100%;background-color: '.$post->tag_font_color.'"></div></div>';
					$row[] = '<div class="progress progress-sm"><div id="uc10_' . $post->tag_id . '" class="progress-bar" style="width: 100%;background-color: '.$post->tag_background_color.'"></div></div>';
					switch($post->is_bold)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc8_' . $post->tag_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc8_' . $post->tag_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc3_' . $post->tag_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->tag_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					$row[] = '<span id="uc4_' . $post->tag_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc5_' . $post->tag_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					$button = '';
					if(permission_validation(PERMISSION_TAG_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->tag_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_TAG_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->tag_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
					}
					
					if(permission_validation(PERMISSION_TAG_UPDATE) == TRUE || permission_validation(PERMISSION_TAG_DELETE) == TRUE)
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
		if(permission_validation(PERMISSION_TAG_ADD) == TRUE)
		{
			$this->load->view('tag_add');
		}
		else
		{
			redirect('home');
		}
	}

	public function submit(){
		if(permission_validation(PERMISSION_TAG_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'tag_times_error' => '',
					'tag_min_error' => '',
					'tag_max_error' => '',
					'tag_code_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			//Set form rules
			$config = array(
				array(
					'field' => 'tag_code',
					'label' => strtolower($this->lang->line('label_tag_code')),
					'rules' => 'trim|required|is_unique[tag.tag_code]|min_length[1]|max_length[16]',
					'errors' => array(
						'required' => $this->lang->line('error_enter_tag_code'),
						'is_unique' => $this->lang->line('error_tag_already_exits'),
						'min_length' => $this->lang->line('error_invalid_tag'),
						'max_length' => $this->lang->line('error_invalid_tag'),
					)
				),
				array(
					'field' => 'tag_times',
					'label' => strtolower($this->lang->line('label_maintain_membership_limit_tag')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_enter_maintain_membership')
					)
				),
				array(
					'field' => 'tag_min',
					'label' => strtolower($this->lang->line('label_min_request_amount_win_loss')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_tag_min'),
					)
				),
				array(
					'field' => 'tag_max',
					'label' => strtolower($this->lang->line('label_max_request_amount_win_loss')),
					'rules' => 'trim|required|greater_than_equal_to['.$this->input->post('tag_min').']',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_tag_max'),
						'greater_than_equal_to' => $this->lang->line('error_greater_than'),
					)
				),
			);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				//Database update
				$this->db->trans_start();
				$newData = $this->tag_model->add_tag_setting();
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_TAG_ADD, $newData);
				}
				else
				{
					$this->account_model->insert_log(LOG_TAG_ADD, $newData);
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
			else 
			{
				$json['msg']['tag_times_error'] = form_error('tag_times');
				$json['msg']['tag_min_error'] = form_error('tag_min');
				$json['msg']['tag_max_error'] = form_error('tag_max');
				$json['msg']['tag_code_error'] = form_error('tag_code');
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

	public function edit($id = NULL){
		if(permission_validation(PERMISSION_TAG_UPDATE) == TRUE)
		{
			$data = $this->tag_model->get_tag_setting_data($id);
			$this->load->view('tag_update', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function update(){
		if(permission_validation(PERMISSION_TAG_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'tag_times_error' => '',
					'tag_min_error' => '',
					'tag_max_error' => '',
					'tag_code_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			$tag_id = trim($this->input->post('tag_id', TRUE));
			$oldData = $this->tag_model->get_tag_setting_data($tag_id);

			if( ! empty($oldData))
			{
				$config = array(
					array(
						'field' => 'tag_times',
						'label' => strtolower($this->lang->line('label_maintain_membership_limit_tag')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_enter_maintain_membership')
						)
					),
					array(
						'field' => 'tag_min',
						'label' => strtolower($this->lang->line('label_min_request_amount_win_loss')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_invalid_tag_min'),
						)
					),
					array(
						'field' => 'tag_max',
						'label' => strtolower($this->lang->line('label_max_request_amount_win_loss')),
						'rules' => 'trim|required|greater_than_equal_to['.$this->input->post('tag_min').']',
						'errors' => array(
							'required' => $this->lang->line('error_invalid_tag_max'),
							'greater_than_equal_to' => $this->lang->line('error_greater_than'),
						)
					),
				);
				$tag_code = trim($this->input->post('tag_code', TRUE));
				if($tag_code != $oldData['tag_code']){
					$configAdd = array(
						'field' => 'tag_code',
						'label' => strtolower($this->lang->line('label_tag_code')),
						'rules' => 'trim|required|is_unique[tag.tag_code]',
						'errors' => array(
							'required' => $this->lang->line('error_enter_tag_code'),
							'is_unique' => $this->lang->line('error_tag_already_exits'),
						)
					);
					array_push($config, $configAdd);
				}
				//Set form rules		
							
				$this->form_validation->set_rules($config);
				$this->form_validation->set_error_delimiters('', '');
				
				//Form validation
				if ($this->form_validation->run() == TRUE)
				{
					if( ! empty($oldData))
					{
						//Database update
						$this->db->trans_start();
						$newData = $this->tag_model->update_tag_setting($tag_id);
						
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_TAG_UPDATE, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_TAG_UPDATE, $newData, $oldData);
						}
						
						$this->db->trans_complete();
						
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');
							
							//Prepare for ajax update
							$json['response'] = array(
								'id' => $newData['tag_id'],
								'tag_code' => $newData['tag_code'],
								'tag_times' => $newData['tag_times'],
								'tag_min' => $newData['tag_min'],
								'tag_max' => $newData['tag_max'],
								'tag_font_color' => $newData['tag_font_color'],
								'tag_background_color' => $newData['tag_background_color'],
								'active' => (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
								'active_code' => $newData['active'],
								'is_bold' => (($newData['is_bold'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
								'is_bold_code' => $newData['is_bold'],
								'updated_by' => $newData['updated_by'],
								'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),
							);
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
					$json['msg']['tag_times_error'] = form_error('tag_times');
					$json['msg']['tag_min_error'] = form_error('tag_min');
					$json['msg']['tag_max_error'] = form_error('tag_max');
					$json['msg']['tag_code_error'] = form_error('tag_code');
				}
			}else{
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

	public function delete(){
		$json = array(
			'status' => EXIT_ERROR, 
			'msg' => ''
		);
					
		if(permission_validation(PERMISSION_TAG_DELETE) == TRUE)
		{
			$tag_id = $this->uri->segment(3);
			$oldData = $this->tag_model->get_tag_setting_data($tag_id);
			if(!empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->tag_model->delete_tag_setting($tag_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_TAG_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_TAG_DELETE, $oldData);
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

	public function player(){
		if(permission_validation(PERMISSION_TAG_PLAYER_VIEW) == TRUE)
		{
			$this->save_current_url('tag/player');
			$data['page_title'] = $this->lang->line('title_tag_player');
			$this->load->view('tag_player_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function player_listing(){
		if(permission_validation(PERMISSION_TAG_PLAYER_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
				'tag_player_id',
				'tag_player_code',
				'tag_player_font_color',
				'tag_player_background_color',
				'is_bold',
				'active',
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
			
			$query = array(
				'select' => implode(',', $columns),
				'table' => 'tag_player',
				'limit' => $limit,
				'start' => $start,
				'order' => $order,
				'dir' => $dir,
			);
			
			$posts =  $this->general_model->all_posts($query);
			$totalFiltered = $this->general_model->all_posts_count($query);

			$data = array();
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					$row = array();
					$row[] = $post->tag_player_id;
					$row[] = '<span id="uc2_' . $post->tag_player_id . '">' . $post->tag_player_code . '</span>';
					$row[] = '<div class="progress progress-sm"><div id="uc9_' . $post->tag_player_id . '" class="progress-bar" style="width: 100%;background-color: '.$post->tag_player_font_color.'"></div></div>';
					$row[] = '<div class="progress progress-sm"><div id="uc10_' . $post->tag_player_id . '" class="progress-bar" style="width: 100%;background-color: '.$post->tag_player_background_color.'"></div></div>';
					switch($post->is_bold)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc8_' . $post->tag_player_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc8_' . $post->tag_player_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc3_' . $post->tag_player_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->tag_player_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					$row[] = '<span id="uc4_' . $post->tag_player_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc5_' . $post->tag_player_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					$button = '';
					if(permission_validation(PERMISSION_TAG_PLAYER_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->tag_player_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_TAG_PLAYER_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->tag_player_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
					}
					
					if(permission_validation(PERMISSION_TAG_PLAYER_UPDATE) == TRUE || permission_validation(PERMISSION_TAG_PLAYER_DELETE) == TRUE)
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

	public function player_add(){
		if(permission_validation(PERMISSION_TAG_PLAYER_ADD) == TRUE)
		{
			$this->load->view('tag_player_add');
		}
		else
		{
			redirect('home');
		}
	}

	public function player_submit(){
		if(permission_validation(PERMISSION_TAG_PLAYER_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'tag_player_code_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			//Set form rules
			$config = array(
				array(
					'field' => 'tag_player_code',
					'label' => strtolower($this->lang->line('label_tag_code_player')),
					'rules' => 'trim|required|is_unique[tag_player.tag_player_code]|min_length[1]|max_length[64]',
					'errors' => array(
						'required' => $this->lang->line('error_enter_tag_player_code'),
						'is_unique' => $this->lang->line('error_tag_player_already_exits'),
						'min_length' => $this->lang->line('error_invalid_tag_player'),
						'max_length' => $this->lang->line('error_invalid_tag_player'),
					)
				),
			);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				//Database update
				$this->db->trans_start();
				$newData = $this->tag_model->add_tag_player_setting();
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_TAG_PLAYER_ADD, $newData);
				}
				else
				{
					$this->account_model->insert_log(LOG_TAG_PLAYER_ADD, $newData);
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
			else 
			{
				$json['msg']['tag_player_code_error'] = form_error('tag_player_code');
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

	public function player_edit($id = NULL){
		if(permission_validation(PERMISSION_TAG_PLAYER_UPDATE) == TRUE)
		{
			$data = $this->tag_model->get_tag_player_setting_data($id);
			$this->load->view('tag_player_update', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function player_update(){
		if(permission_validation(PERMISSION_TAG_PLAYER_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'tag_player_code_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			$tag_player_id = trim($this->input->post('tag_player_id', TRUE));
			$oldData = $this->tag_model->get_tag_player_setting_data($tag_player_id);

			if( ! empty($oldData))
			{
				$tag_player_code = trim($this->input->post('tag_player_code', TRUE));
				if($tag_player_code != $oldData['tag_player_code']){
					$config = array(
						array(
							'field' => 'tag_player_code',
							'label' => strtolower($this->lang->line('label_code')),
							'rules' => 'trim|required|is_unique[tag_player.tag_player_code]|min_length[1]|max_length[64]',
							'errors' => array(
								'required' => $this->lang->line('error_enter_tag_player_code'),
								'is_unique' => $this->lang->line('error_tag_player_already_exits'),
								'min_length' => $this->lang->line('error_invalid_tag_player'),
								'max_length' => $this->lang->line('error_invalid_tag_player'),
							)
						),
					);
				}else{
					$config = array(
						array(
							'field' => 'tag_player_code',
							'label' => strtolower($this->lang->line('label_code')),
							'rules' => 'trim|required|min_length[1]|max_length[64]',
							'errors' => array(
								'required' => $this->lang->line('error_enter_tag_player_code'),
								'min_length' => $this->lang->line('error_invalid_tag_player'),
								'max_length' => $this->lang->line('error_invalid_tag_player'),
							)
						),
					);
				}
				//Set form rules		
							
				$this->form_validation->set_rules($config);
				$this->form_validation->set_error_delimiters('', '');
				
				//Form validation
				if ($this->form_validation->run() == TRUE)
				{
					if( ! empty($oldData))
					{
						//Database update
						$this->db->trans_start();
						$newData = $this->tag_model->update_tag_player_setting($tag_player_id);
						
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_TAG_PLAYER_UPDATE, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_TAG_PLAYER_UPDATE, $newData, $oldData);
						}
						
						$this->db->trans_complete();
						
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');
							
							//Prepare for ajax update
							$json['response'] = array(
								'id' => $newData['tag_player_id'],
								'tag_player_code' => $newData['tag_player_code'],
								'tag_player_font_color' => $newData['tag_player_font_color'],
								'tag_player_background_color' => $newData['tag_player_background_color'],
								'active' => (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
								'active_code' => $newData['active'],
								'is_bold' => (($newData['is_bold'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
								'is_bold_code' => $newData['is_bold'],
								'updated_by' => $newData['updated_by'],
								'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),
							);
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
					$json['msg']['tag_player_code_error'] = form_error('tag_player_code');
				}
			}else{
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

	public function player_delete(){
		$json = array(
			'status' => EXIT_ERROR, 
			'msg' => ''
		);
					
		if(permission_validation(PERMISSION_TAG_PLAYER_DELETE) == TRUE)
		{
			$tag_player_id = $this->uri->segment(3);
			$oldData = $this->tag_model->get_tag_player_setting_data($tag_player_id);
			if(!empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->tag_model->delete_tag_player_setting($tag_player_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_TAG_PLAYER_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_TAG_PLAYER_DELETE, $oldData);
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

	public function get_all_tag_data(){
		$json = array(
			'status' => EXIT_ERROR, 
			'msg' => '',
			'total_data' => '',
			'response' => '',
		);
		
		$result = $this->tag_model->get_all_tag_data();
		if(!empty($result)){
			$json['status'] = EXIT_SUCCESS;
			$json['response'] = $result;
			$json['total_data'] = sizeof($result);
		}

		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
				
		exit();
	}

	public function get_all_tag_player_data(){
		$json = array(
			'status' => EXIT_ERROR, 
			'msg' => '',
			'total_data' => '',
			'response' => '',
		);
		
		$result = $this->tag_model->get_all_tag_player_data();
		if(!empty($result)){
			$json['status'] = EXIT_SUCCESS;
			$json['response'] = $result;
			$json['total_data'] = sizeof($result);
		}

		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
				
		exit();
	}

	public function player_bulk_modify(){
		if(permission_validation(PERMISSION_TAG_PLAYER_BULK_MODIFY) == TRUE)
		{
			$this->load->view('tag_player_bulk_modify', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function player_bulk_modify_update(){
		//Output
		if(permission_validation(PERMISSION_TAG_PLAYER_BULK_MODIFY) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'type_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			$type = trim($this->input->post('type', TRUE));
			//Set form rules
			$config = array(
				array(
					'field' => 'type',
					'label' => strtolower($this->lang->line('label_type')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_select_type'),
					)
				),
			);		

			if($type == "1"){
				$configAdd = array(
					'field' => 'agent',
					'label' => strtolower($this->lang->line('label_agent')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_select_agent'),
					)
				);
				array_push($config, $configAdd);	
			}else if($type == "2"){
				$configAdd = array(
					'field' => 'bank_group',
					'label' => strtolower($this->lang->line('label_bank_group')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_select_bank_group'),
					)
				);
				array_push($config, $configAdd);
			}else if($type == "3"){
				$configAdd = array(
					'field' => 'tag',
					'label' => strtolower($this->lang->line('label_tag_code')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_select_tag_code'),
					)
				);
				array_push($config, $configAdd);
			}else if($type == "4"){
				$configAdd = array(
					'field' => 'tag_player',
					'label' => strtolower($this->lang->line('label_tag_code_player')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_select_tag_player_code'),
					)
				);
				array_push($config, $configAdd);
			}else if($type == "5"){
				$configAdd = array(
					'field' => 'level_id',
					'label' => strtolower($this->lang->line('label_level')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_select_level'),
					)
				);
				array_push($config, $configAdd);
			}else if($type == "6"){
				$configAdd = array(
					'field' => 'username',
					'label' => strtolower($this->lang->line('label_player_username')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_select_player_username'),
					)
				);
				array_push($config, $configAdd);
			}
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				//Database update
				

				$is_allow = false;
				$oldData = NULL;
				
				if($type == "1"){
					$agent = trim($this->input->post('agent', TRUE));
					$oldData = $this->user_model->get_downline_data($agent);
					if(!empty($oldData)){
						$is_allow = true;
					}
				}else if($type == "2"){
					$bank_group_id = trim($this->input->post('bank_group', TRUE));
					$oldData = $this->group_model->get_group_data($bank_group_id);
					if(!empty($oldData)){
						$is_allow = true;
					}
				}else if($type == "3"){
					$tag_id = trim($this->input->post('tag', TRUE));
					$oldData = $this->tag_model->get_tag_setting_data($tag_id);
					if(!empty($oldData)){
						$is_allow = true;
					}
				}else if($type == "4"){
					$tag_player_id = trim($this->input->post('tag_player', TRUE));
					$oldData = $this->tag_model->get_tag_player_setting_data($tag_player_id);
					if(!empty($oldData)){
						$is_allow = true;
					}	
				}else if($type == "5"){
					$level_id = trim($this->input->post('level_id', TRUE));
					$oldData = $this->level_model->get_level_data_by_id($level_id);
					if(!empty($oldData)){
						$is_allow = true;
					}
				}else if($type == "6"){
					$username = $this->input->post('username', TRUE);
					$oldData = $this->player_model->get_player_username_by_array($username);
					if(!empty($oldData)){
						$is_allow = true;
					}
				}else{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
				}
				
				if(!empty($oldData)){
					$this->db->trans_start();
					$newData = $this->player_model->bulk_update_player_setting_by_type($type,$oldData);
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_TAG_PLAYER_BULK_MODIFY, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_TAG_PLAYER_BULK_MODIFY, $newData, $oldData);
					}
					
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_updated');
					}
					else
					{
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');
					}
				}else{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
				}
			}
			else 
			{
				$json['msg']['type_error'] = form_error('type');
				if($type == "1"){
					$json['msg']['general_error'] = form_error('agent');
				}else if($type == "2"){
					$json['msg']['general_error'] = form_error('bank_group');
				}else if($type == "3"){
					$json['msg']['general_error'] = form_error('tag');
				}else if($type == "4"){
					$json['msg']['general_error'] = form_error('tag_player');
				}else if($type == "5"){
					$json['msg']['general_error'] = form_error('level_id');
				}else if($type == "6"){
					$json['msg']['general_error'] = form_error('username');
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