<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('banner_model');
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}
		
	public function index()
	{
		if(permission_validation(PERMISSION_BANNER_VIEW) == TRUE)
		{
			$this->save_current_url('banner');
			
			$data['page_title'] = $this->lang->line('title_banner');
			$this->load->view('banner_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	
	public function listing()
    {
		if(permission_validation(PERMISSION_BANNER_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
								0 => 'banner_id',
								1 => 'banner_name',
								2 => 'banner_sequence',
								3 => 'language_id',
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
							'table' => 'banners',
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
					$row[] = $post->banner_id;
					$row[] = '<span id="uc1_' . $post->banner_id . '">' . $post->banner_name . '</span>';
					$row[] = '<span id="uc2_' . $post->banner_id . '">' . $post->banner_sequence . '</span>';
					$row[] = '<span id="uc3_' . $post->banner_id . '">' . $this->lang->line(get_site_language_name($post->language_id)) . '</span>';
					
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc4_' . $post->banner_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc4_' . $post->banner_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					
					$row[] = '<span id="uc5_' . $post->banner_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc6_' . $post->banner_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					
					$button = '';
					if(permission_validation(PERMISSION_BANNER_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->banner_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_BANNER_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->banner_id . ')" type="button" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
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
		if(permission_validation(PERMISSION_BANNER_ADD) == TRUE)
		{
			$this->load->view('banner_add');
		}
		else
		{
			redirect('home');
		}
	}
	
	public function submit()
	{
		if(permission_validation(PERMISSION_BANNER_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'banner_name_error' => '',
										'banner_sequence_error' => '',
										'language_id_error' => '',
										'banner_url_error' => '',
										'web_banner_width_error' => '',
										'web_banner_height_error' => '',
										'mobile_banner_width_error' => '',
										'mobile_banner_height_error' => '',
										'general_error' => ''
									), 		
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			
			//Set form rules
			$config = array(
							array(
									'field' => 'banner_name',
									'label' => strtolower($this->lang->line('label_banner_name')),
									'rules' => 'trim|required',
									'errors' => array(
														'required' => $this->lang->line('error_enter_banner_name')
												)
							),
							array(
									'field' => 'banner_sequence',
									'label' => strtolower($this->lang->line('label_sequence')),
									'rules' => 'trim|required|integer',
									'errors' => array(
														'required' => $this->lang->line('error_only_digits_allowed'),
														'integer' => $this->lang->line('error_only_digits_allowed')
												)
							),
							array(
									'field' => 'language_id',
									'label' => strtolower($this->lang->line('label_language')),
									'rules' => 'trim|required|integer|callback_language_check',
									'errors' => array(
														'required' => $this->lang->line('error_select_group_type'),
														'integer' => $this->lang->line('error_select_group_type'),
														'language_check' => $this->lang->line('error_select_group_type')
												)
							),
							array(
									'field' => 'banner_url',
									'label' => strtolower($this->lang->line('label_url')),
									'rules' => 'trim|valid_url',
									'errors' => array(
														'valid_url' => $this->lang->line('error_ivalid_url_format')
												)
							),
							array(
									'field' => 'web_banner_width',
									'label' => strtolower($this->lang->line('label_width')),
									'rules' => 'trim|integer',
									'errors' => array(
														'integer' => $this->lang->line('error_only_digits_allowed')
												)
							),
							array(
									'field' => 'web_banner_height',
									'label' => strtolower($this->lang->line('label_height')),
									'rules' => 'trim|integer',
									'errors' => array(
														'integer' => $this->lang->line('error_only_digits_allowed')
												)
							),
							array(
									'field' => 'web_banner_alt',
									'label' => strtolower($this->lang->line('label_image_alt')),
									'rules' => 'trim'
							),
							array(
									'field' => 'mobile_banner_width',
									'label' => strtolower($this->lang->line('label_width')),
									'rules' => 'trim|integer',
									'errors' => array(
														'integer' => $this->lang->line('error_only_digits_allowed')
												)
							),
							array(
									'field' => 'mobile_banner_height',
									'label' => strtolower($this->lang->line('label_height')),
									'rules' => 'trim|integer',
									'errors' => array(
														'integer' => $this->lang->line('error_only_digits_allowed')
												)
							),
							array(
									'field' => 'mobile_banner_alt',
									'label' => strtolower($this->lang->line('label_image_alt')),
									'rules' => 'trim'
							)
						);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$allow_to_update = TRUE;
				
				$config['upload_path'] = BANNER_PATH;
				$config['max_size'] = BANNER_FILE_SIZE;
				$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
				$config['overwrite'] = TRUE;
				
				$this->load->library('upload', $config);
				
				if(isset($_FILES['web_banner']['size']) && $_FILES['web_banner']['size'] > 0)
				{
					$new_name = time().rand(1000,9999).".".pathinfo($_FILES["web_banner"]['name'], PATHINFO_EXTENSION);
					$config['file_name']  = $new_name;
					$this->upload->initialize($config);
					if( ! $this->upload->do_upload('web_banner')) 
					{
						$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
						$allow_to_update = FALSE;
					}else{
						$_FILES["web_banner"]['name'] = $new_name;
					}
				}
				
				if($allow_to_update == TRUE)
				{
					if(isset($_FILES['mobile_banner']['size']) && $_FILES['mobile_banner']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["mobile_banner"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('mobile_banner')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["mobile_banner"]['name'] = $new_name;
						}
					}
				}
				
				if($allow_to_update == TRUE)
				{
					//Database update
					$this->db->trans_start();
					$newData = $this->banner_model->add_banner();
					
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_BANNER_ADD, $newData);
					}
					else
					{
						$this->account_model->insert_log(LOG_BANNER_ADD, $newData);
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
			}
			else 
			{
				$json['msg']['banner_name_error'] = form_error('banner_name');
				$json['msg']['banner_sequence_error'] = form_error('banner_sequence');
				$json['msg']['language_id_error'] = form_error('language_id');
				$json['msg']['banner_url_error'] = form_error('banner_url');
				$json['msg']['web_banner_width_error'] = form_error('web_banner_width');
				$json['msg']['web_banner_height_error'] = form_error('web_banner_height');
				$json['msg']['mobile_banner_width_error'] = form_error('mobile_banner_width');
				$json['msg']['mobile_banner_height_error'] = form_error('mobile_banner_height');
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
		if(permission_validation(PERMISSION_BANNER_UPDATE) == TRUE)
		{
			$data = $this->banner_model->get_banner_data($id);
			$this->load->view('banner_update', $data);
		}
		else
		{
			redirect('home');
		}
	}
	
	public function update()
	{
		if(permission_validation(PERMISSION_BANNER_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'banner_name_error' => '',
										'banner_sequence_error' => '',
										'language_id_error' => '',
										'banner_url_error' => '',
										'web_banner_width_error' => '',
										'web_banner_height_error' => '',
										'mobile_banner_width_error' => '',
										'mobile_banner_height_error' => '',
										'general_error' => ''
									), 
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			
			//Set form rules
			$config = array(
							array(
									'field' => 'banner_name',
									'label' => strtolower($this->lang->line('label_banner_name')),
									'rules' => 'trim|required',
									'errors' => array(
														'required' => $this->lang->line('error_enter_banner_name')
												)
							),
							array(
									'field' => 'banner_sequence',
									'label' => strtolower($this->lang->line('label_sequence')),
									'rules' => 'trim|required|integer',
									'errors' => array(
														'required' => $this->lang->line('error_only_digits_allowed'),
														'integer' => $this->lang->line('error_only_digits_allowed')
												)
							),
							array(
									'field' => 'language_id',
									'label' => strtolower($this->lang->line('label_language')),
									'rules' => 'trim|required|integer|callback_language_check',
									'errors' => array(
														'required' => $this->lang->line('error_select_group_type'),
														'integer' => $this->lang->line('error_select_group_type'),
														'language_check' => $this->lang->line('error_select_group_type')
												)
							),
							array(
									'field' => 'banner_url',
									'label' => strtolower($this->lang->line('label_url')),
									'rules' => 'trim|valid_url',
									'errors' => array(
														'valid_url' => $this->lang->line('error_ivalid_url_format')
												)
							),
							array(
									'field' => 'web_banner_width',
									'label' => strtolower($this->lang->line('label_width')),
									'rules' => 'trim|integer',
									'errors' => array(
														'integer' => $this->lang->line('error_only_digits_allowed')
												)
							),
							array(
									'field' => 'web_banner_height',
									'label' => strtolower($this->lang->line('label_height')),
									'rules' => 'trim|integer',
									'errors' => array(
														'integer' => $this->lang->line('error_only_digits_allowed')
												)
							),
							array(
									'field' => 'web_banner_alt',
									'label' => strtolower($this->lang->line('label_image_alt')),
									'rules' => 'trim'
							),
							array(
									'field' => 'mobile_banner_width',
									'label' => strtolower($this->lang->line('label_width')),
									'rules' => 'trim|integer',
									'errors' => array(
														'integer' => $this->lang->line('error_only_digits_allowed')
												)
							),
							array(
									'field' => 'mobile_banner_height',
									'label' => strtolower($this->lang->line('label_height')),
									'rules' => 'trim|integer',
									'errors' => array(
														'integer' => $this->lang->line('error_only_digits_allowed')
												)
							),
							array(
									'field' => 'mobile_banner_alt',
									'label' => strtolower($this->lang->line('label_image_alt')),
									'rules' => 'trim'
							)
						);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$allow_to_update = TRUE;
				
				$config['upload_path'] = BANNER_PATH;
				$config['max_size'] = BANNER_FILE_SIZE;
				$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
				$config['overwrite'] = TRUE;
				
				$this->load->library('upload', $config);
				
				if(isset($_FILES['web_banner']['size']) && $_FILES['web_banner']['size'] > 0)
				{
					$new_name = time().rand(1000,9999).".".pathinfo($_FILES["web_banner"]['name'], PATHINFO_EXTENSION);
					$config['file_name']  = $new_name;
					$this->upload->initialize($config);
					if( ! $this->upload->do_upload('web_banner')) 
					{
						$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
						$allow_to_update = FALSE;
					}else{
						$_FILES["web_banner"]['name'] = $new_name;
					}
				}
				
				if($allow_to_update == TRUE)
				{
					if(isset($_FILES['mobile_banner']['size']) && $_FILES['mobile_banner']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["mobile_banner"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('mobile_banner')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["mobile_banner"]['name'] = $new_name;
						}
					}
				}
				
				if($allow_to_update == TRUE)
				{
					$banner_id = trim($this->input->post('banner_id', TRUE));
					$oldData = $this->banner_model->get_banner_data($banner_id);
					
					if( ! empty($oldData))
					{
						//Database update
						$this->db->trans_start();
						$newData = $this->banner_model->update_banner($banner_id);
						
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_BANNER_UPDATE, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_BANNER_UPDATE, $newData, $oldData);
						}
						
						$this->db->trans_complete();
						
						if ($this->db->trans_status() === TRUE)
						{
							//Delete old banner
							if(isset($_FILES['web_banner']['size']) && $_FILES['web_banner']['size'] > 0)
							{
								unlink(BANNER_PATH . $oldData['web_banner']);
							}
							
							if(isset($_FILES['mobile_banner']['size']) && $_FILES['mobile_banner']['size'] > 0)
							{
								unlink(BANNER_PATH . $oldData['mobile_banner']);
							}
							
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');
							
							//Prepare for ajax update
							$json['response'] = array(
													'id' => $newData['banner_id'],
													'banner_name' => $newData['banner_name'],
													'banner_sequence' => $newData['banner_sequence'],
													'language_id' => $this->lang->line(get_site_language_name($newData['language_id'])),
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
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
				}
			}
			else 
			{
				$json['msg']['banner_name_error'] = form_error('banner_name');
				$json['msg']['banner_sequence_error'] = form_error('banner_sequence');
				$json['msg']['language_id_error'] = form_error('language_id');
				$json['msg']['banner_url_error'] = form_error('banner_url');
				$json['msg']['web_banner_width_error'] = form_error('web_banner_width');
				$json['msg']['web_banner_height_error'] = form_error('web_banner_height');
				$json['msg']['mobile_banner_width_error'] = form_error('mobile_banner_width');
				$json['msg']['mobile_banner_height_error'] = form_error('mobile_banner_height');
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
					
		if(permission_validation(PERMISSION_BANNER_DELETE) == TRUE)
		{
			$banner_id = $this->uri->segment(3);
			$oldData = $this->banner_model->get_banner_data($banner_id);
			
			if( ! empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->banner_model->delete_banner($banner_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_BANNER_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_BANNER_DELETE, $oldData);
				}
				
				$this->db->trans_complete();
				
				if ($this->db->trans_status() === TRUE)
				{
					//Delete old banner
					if( ! empty($oldData['web_banner']))
					{
						unlink(BANNER_PATH . $oldData['web_banner']);
					}
					
					if( ! empty($oldData['mobile_banner']))
					{
						unlink(BANNER_PATH . $oldData['mobile_banner']);
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