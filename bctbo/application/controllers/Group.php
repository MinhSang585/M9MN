<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('group_model', 'player_model','bank_model'));
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}
		
	public function index()
	{
		if(permission_validation(PERMISSION_GROUP_VIEW) == TRUE)
		{
			$this->save_current_url('group');
			
			$data['page_title'] = $this->lang->line('title_group');
			$this->load->view('group_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	
	public function listing()
    {
		if(permission_validation(PERMISSION_GROUP_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
				0 => 'group_id',
				1 => 'group_name',
				2 => 'group_type',
				3 => 'group_default',
				4 => 'active',
				5 => 'updated_by',
				6 => 'updated_date',
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
							'table' => 'groups',
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
				foreach ($posts as $post)
				{
					if($post->group_type == GROUP_BANK){
						$total_player = $this->group_model->count_group_bank_total($post->group_id);
					}else{
						//Group player
						$total_player = $this->group_model->count_group_profile_total($post->group_id);
					}

					$row = array();
					$row[] = $post->group_id;
					$row[] = '<span id="uc1_' . $post->group_id . '">' . $post->group_name . '</span>';
					$row[] = '<span id="uc2_' . $post->group_id . '">' . $this->lang->line(get_group_type($post->group_type)) . '</span>';
					$row[] = $total_player;
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc3_' . $post->group_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->group_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					
					$row[] = '<span id="uc4_' . $post->group_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc5_' . $post->group_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					
					$button = '';
					if(permission_validation(PERMISSION_GROUP_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->group_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_GROUP_DELETE) == TRUE)
					{
						if($post->group_default == STATUS_NO){
							$button .= '<i onclick="deleteData(' . $post->group_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
						}
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
	
	public function add()
    {
		if(permission_validation(PERMISSION_GROUP_ADD) == TRUE)
		{
			$this->load->view('group_add');
		}
		else
		{
			redirect('home');
		}
	}
	
	public function submit()
	{
		if(permission_validation(PERMISSION_GROUP_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'group_name_error' => '',
										'group_type_error' => '',
										'general_error' => ''
									), 		
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			
			//Set form rules
			$config = array(
							array(
									'field' => 'group_name',
									'label' => strtolower($this->lang->line('label_group_name')),
									'rules' => 'trim|required',
									'errors' => array(
														'required' => $this->lang->line('error_enter_group_name')
												)
							),
							array(
									'field' => 'group_type',
									'label' => strtolower($this->lang->line('label_group_type')),
									'rules' => 'trim|required|integer|callback_group_type_check',
									'errors' => array(
														'required' => $this->lang->line('error_select_group_type'),
														'integer' => $this->lang->line('error_select_group_type'),
														'group_type_check' => $this->lang->line('error_select_group_type')
												)
							)
						);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				//Database update
				$this->db->trans_start();
				$newData = $this->group_model->add_group();
				
				$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
				if(sizeof($lang) > 1)
				{
					for($i=2;$i<sizeof($lang);$i++)
					{
						$lang_group_name = trim($this->input->post('group_name-' . $i, TRUE));
						if( ! empty($lang_group_name))
						{
							$this->group_model->add_group_lang($newData['group_id'], $lang_group_name, $i);
						}	
					}
				}
				
				$newLangData = $this->group_model->get_group_lang_data($newData['group_id']);
				$newData['lang'] = json_encode($newLangData);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_GROUP_ADD, $newData);
				}
				else
				{
					$this->account_model->insert_log(LOG_GROUP_ADD, $newData);
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
				$json['msg']['group_name_error'] = form_error('group_name');
				$json['msg']['group_type_error'] = form_error('group_type');
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
		if(permission_validation(PERMISSION_GROUP_UPDATE) == TRUE)
		{
			$data = $this->group_model->get_group_data($id);
			$data['group_lang'] = $this->group_model->get_group_lang_data($id);
			$this->load->view('group_update', $data);
		}
		else
		{
			redirect('home');
		}
	}
	
	public function update()
	{
		if(permission_validation(PERMISSION_GROUP_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'group_name_error' => '',
										'group_type_error' => '',
										'general_error' => ''
									),	
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			
			//Set form rules
			$config = array(
							array(
									'field' => 'group_name',
									'label' => strtolower($this->lang->line('label_group_name')),
									'rules' => 'trim|required',
									'errors' => array(
														'required' => $this->lang->line('error_enter_group_name')
												)
							),
							array(
									'field' => 'group_type',
									'label' => strtolower($this->lang->line('label_group_type')),
									'rules' => 'trim|required|integer|callback_group_type_check',
									'errors' => array(
														'required' => $this->lang->line('error_select_group_type'),
														'integer' => $this->lang->line('error_select_group_type'),
														'group_type_check' => $this->lang->line('error_select_group_type')
												)
							)
						);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$group_id = trim($this->input->post('group_id', TRUE));
				$oldData = $this->group_model->get_group_data($group_id);
				$oldLangData = $this->group_model->get_group_lang_data($group_id);
				$oldData['lang'] = json_encode($oldLangData);
				
				if( ! empty($oldData))
				{
					//Database update
					$this->db->trans_start();
					$this->group_model->delete_group_lang($group_id);
					
					$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
					if(sizeof($lang) > 1)
					{
						for($i=2;$i<sizeof($lang);$i++)
						{
							$lang_group_name = trim($this->input->post('group_name-' . $i, TRUE));
							if( ! empty($lang_group_name))
							{
								$this->group_model->add_group_lang($group_id, $lang_group_name, $i);
							}
						}
					}
					
					$newData = $this->group_model->update_group($group_id);
					$newLangData = $this->group_model->get_group_lang_data($group_id);
					$newData['lang'] = json_encode($newLangData);
					
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_GROUP_UPDATE, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_GROUP_UPDATE, $newData, $oldData);
					}
					
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_updated');
						
						//Prepare for ajax update
						$json['response'] = array(
												'id' => $newData['group_id'],
												'group_name' => $newData['group_name'],
												'group_type' => $this->lang->line(get_group_type($newData['group_type'])),
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
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
				}	
			}
			else 
			{
				$json['msg']['group_name_error'] = form_error('group_name');
				$json['msg']['group_type_error'] = form_error('group_type');
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
	
	public function delete()
    {
		//Initial output data
		$json = array(
					'status' => EXIT_ERROR, 
					'msg' => ''
				);
					
		if(permission_validation(PERMISSION_GROUP_DELETE) == TRUE)
		{
			$group_id = $this->uri->segment(3);
			$oldData = $this->group_model->get_group_data($group_id);
			$oldLangData = $this->group_model->get_group_lang_data($group_id);
			$oldData['lang'] = json_encode($oldLangData);
			
			if(!empty($oldData) && $oldData['group_default']== STATUS_NO)
			{
				//Database update
				$this->db->trans_start();
				$this->group_model->delete_group($group_id);
				$this->group_model->delete_group_lang($group_id);
				if($oldData['group_type'] == GROUP_PLAYER){
					$this->player_model->update_profile_group($group_id);
				}else if($oldData['group_type'] == GROUP_BANK){
					$this->bank_model->update_bank_group($group_id);
					$this->player_model->update_bank_group($group_id);
				}else{

				}
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_GROUP_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_GROUP_DELETE, $oldData);
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
}