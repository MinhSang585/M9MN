<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Announcement extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('announcement_model');
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}
		
	public function index()
	{
		if(permission_validation(PERMISSION_ANNOUNCEMENT_VIEW) == TRUE)
		{
			$this->save_current_url('announcement');
			
			$data['page_title'] = $this->lang->line('title_announcement');
			$this->load->view('announcement_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	
	public function listing()
    {
		if(permission_validation(PERMISSION_ANNOUNCEMENT_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
								0 => 'announcement_id',
								1 => 'content',
								2 => 'active',
								3 => 'start_date',
								4 => 'end_date',
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
							'table' => 'announcements',
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
					$row = array();
					$row[] = $post->announcement_id;
					$row[] = '<span id="uc1_' . $post->announcement_id . '">' . $post->content . '</span>';
					
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc2_' . $post->announcement_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc2_' . $post->announcement_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					
					$row[] = '<span id="uc3_' . $post->announcement_id . '">' . (($post->start_date > 0) ? date('Y-m-d H:i:s', $post->start_date) : '') . '</span>';
					$row[] = '<span id="uc4_' . $post->announcement_id . '">' . (($post->end_date > 0) ? date('Y-m-d H:i:s', $post->end_date) : '') . '</span>';
					$row[] = '<span id="uc5_' . $post->announcement_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc6_' . $post->announcement_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					
					$button = '';
					if(permission_validation(PERMISSION_ANNOUNCEMENT_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->announcement_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_ANNOUNCEMENT_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->announcement_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
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
		if(permission_validation(PERMISSION_ANNOUNCEMENT_ADD) == TRUE)
		{
			$this->load->view('announcement_add');
		}
		else
		{
			redirect('home');
		}
	}
	
	public function submit()
	{
		if(permission_validation(PERMISSION_ANNOUNCEMENT_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'content_error' => '',
										'start_date_error' => '',
										'end_date_error' => '',
										'general_error' => ''
									), 		
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			
			//Set form rules
			$config = array(
							array(
									'field' => 'content',
									'label' => strtolower($this->lang->line('label_content')),
									'rules' => 'trim|required',
									'errors' => array(
														'required' => $this->lang->line('error_enter_content')
												)
							),
							array(
									'field' => 'start_date',
									'label' => strtolower($this->lang->line('label_start_date')),
									'rules' => 'trim|callback_datetime_check',
									'errors' => array(
														'datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							),
							array(
									'field' => 'end_date',
									'label' => strtolower($this->lang->line('label_end_date')),
									'rules' => 'trim|callback_datetime_check',
									'errors' => array(
														'datetime_check' => $this->lang->line('error_invalid_datetime_format')
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
				$newData = $this->announcement_model->add_announcement();
				
				$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);

				if(sizeof($lang)>0){
					foreach($lang as $k => $v){
						$this->announcement_model->add_announcement_lang($newData['announcement_id'],$v);
					}
				}
				$newLangData = $this->announcement_model->get_announcement_lang_data($newData['announcement_id']);
				$newData['lang'] = json_encode($newLangData);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_ANNOUNCEMENT_ADD, $newData);
				}
				else
				{
					$this->account_model->insert_log(LOG_ANNOUNCEMENT_ADD, $newData);
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
				$json['msg']['content_error'] = form_error('content');
				$json['msg']['start_date_error'] = form_error('start_date');
				$json['msg']['end_date_error'] = form_error('end_date');
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
		if(permission_validation(PERMISSION_ANNOUNCEMENT_UPDATE) == TRUE)
		{
			$data = $this->announcement_model->get_announcement_data($id);
			$data['announcement_lang'] = $this->announcement_model->get_announcement_lang_data($id);
			$this->load->view('announcement_update', $data);
		}
		else
		{
			redirect('home');
		}
	}
	
	public function update()
	{
		if(permission_validation(PERMISSION_ANNOUNCEMENT_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'content_error' => '',
										'start_date_error' => '',
										'end_date_error' => '',
										'general_error' => ''
									),	
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			
			//Set form rules
			$config = array(
							array(
									'field' => 'content',
									'label' => strtolower($this->lang->line('label_content')),
									'rules' => 'trim|required',
									'errors' => array(
														'required' => $this->lang->line('error_enter_content')
												)
							),
							array(
									'field' => 'start_date',
									'label' => strtolower($this->lang->line('label_start_date')),
									'rules' => 'trim|callback_datetime_check',
									'errors' => array(
														'datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							),
							array(
									'field' => 'end_date',
									'label' => strtolower($this->lang->line('label_end_date')),
									'rules' => 'trim|callback_datetime_check',
									'errors' => array(
														'datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							)
						);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$announcement_id = trim($this->input->post('announcement_id', TRUE));
				$oldData = $this->announcement_model->get_announcement_data($announcement_id);
				$oldLangData = $this->announcement_model->get_announcement_lang_data($announcement_id);
				$oldData['lang'] = json_encode($oldLangData);
				
				if( ! empty($oldData))
				{
					//Database update
					$this->db->trans_start();
					$this->announcement_model->delete_announcement_lang($announcement_id);
					

					$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
					if(sizeof($lang)>0){
						foreach($lang as $k => $v){
							$this->announcement_model->add_announcement_lang($announcement_id,$v);
						}
					}
					
					$newLangData = $this->announcement_model->get_announcement_lang_data($announcement_id);
					$newData = $this->announcement_model->update_announcement($announcement_id);
					$newData['lang'] = json_encode($newLangData);
					
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_ANNOUNCEMENT_UPDATE, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_ANNOUNCEMENT_UPDATE, $newData, $oldData);
					}
					
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_updated');
						
						//Prepare for ajax update
						$json['response'] = array(
												'id' => $newData['announcement_id'],
												'content' => $newData['content'],
												'start_date' => (($newData['start_date'] > 0) ? date('Y-m-d H:i:s', $newData['start_date']) : ''),
												'end_date' => (($newData['end_date'] > 0) ? date('Y-m-d H:i:s', $newData['end_date']) : ''),
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
				$json['msg']['content_error'] = form_error('content');
				$json['msg']['start_date_error'] = form_error('start_date');
				$json['msg']['end_date_error'] = form_error('end_date');
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
					
		if(permission_validation(PERMISSION_ANNOUNCEMENT_DELETE) == TRUE)
		{
			$announcement_id = $this->uri->segment(3);
			$oldData = $this->announcement_model->get_announcement_data($announcement_id);
			$oldLangData = $this->announcement_model->get_announcement_lang_data($announcement_id);
			$oldData['lang'] = json_encode($oldLangData);
			
			if( ! empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->announcement_model->delete_announcement($announcement_id);
				$this->announcement_model->delete_announcement_lang($announcement_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_ANNOUNCEMENT_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_ANNOUNCEMENT_DELETE, $oldData);
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