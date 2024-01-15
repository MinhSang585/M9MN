<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Match extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('match_model'));
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index(){
		if(permission_validation(PERMISSION_MATCH_VIEW) == TRUE)
		{
			$this->save_current_url('match');
			
			$data['page_title'] = $this->lang->line('title_match');
			$this->load->view('match_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function listing()
	{
		if(permission_validation(PERMISSION_MATCH_VIEW) == TRUE){
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);

			//Table Columns
			$columns = array(
				0 => 'match_id',
				1 => 'match_name',
				2 => 'match_sequence',
				3 => 'active',
				4 => 'match_start',
				5 => 'match_end',
				6 => 'updated_by',
				7 => 'updated_date',
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
					'table' => 'match',
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
					$row[] = $post->match_id;
					$row[] = '<span id="uc1_' . $post->match_id . '">' . $post->match_name . '</span>';
					$row[] = '<span id="uc7_' . $post->match_id . '">' . $post->match_sequence . '</span>';
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc2_' . $post->match_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc2_' . $post->match_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					$row[] = '<span id="uc3_' . $post->match_id . '">' . (($post->match_start > 0) ? date('Y-m-d H:i:s', $post->match_start) : '-') . '</span>';
					$row[] = '<span id="uc4_' . $post->match_id . '">' . (($post->match_end > 0) ? date('Y-m-d H:i:s', $post->match_end) : '-') . '</span>';
					$row[] = '<span id="uc5_' . $post->match_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc6_' . $post->match_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';

					$button = '';
					if(permission_validation(PERMISSION_MATCH_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->match_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_web_banner')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_MATCH_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->match_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
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
		if(permission_validation(PERMISSION_MATCH_ADD) == TRUE)
		{
			$this->load->view('match_add');
		}
		else
		{
			redirect('home');
		}
	}

	public function submit(){
		if(permission_validation(PERMISSION_MATCH_ADD) == TRUE)
		{
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'match_name_error' => '',
					'match_sequence_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			//Set form rules
			$config = array(
				array(
						'field' => 'match_name',
						'label' => strtolower($this->lang->line('label_match_name')),
						'rules' => 'trim|required',
						'errors' => array(
								'required' => $this->lang->line('error_enter_match_name')
						)
				),
				array(
						'field' => 'match_sequence',
						'label' => strtolower($this->lang->line('label_sequence')),
						'rules' => 'trim|required|integer',
						'errors' => array(
								'required' => $this->lang->line('error_only_digits_allowed'),
								'integer' => $this->lang->line('error_only_digits_allowed')
						)
				),
			);	
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run() == TRUE)
			{
				$allow_to_update = TRUE;
				$config['upload_path'] = MATCH_PATH;
				$config['max_size'] = MATCH_FILE_SIZE;
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['overwrite'] = TRUE;
				
				$this->load->library('upload', $config);
				
				$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
				if(sizeof($lang)>0){
					foreach($lang as $k => $v){
						if(isset($_FILES['web_banner_'.$v]['size']) && $_FILES['web_banner_'.$v]['size'] > 0)
						{
							$new_name = time().rand(1000,9999).".".pathinfo($_FILES["web_banner_".$v]['name'], PATHINFO_EXTENSION);
							$config['file_name']  = $new_name;
							$this->upload->initialize($config);
							if( ! $this->upload->do_upload('web_banner_'.$v)) 
							{
								$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
								$allow_to_update = FALSE;
							}else{
								$_FILES["web_banner_".$v]['name'] = $new_name;
							}
						}

						if($allow_to_update == TRUE)
						{
							if(isset($_FILES['mobile_banner_'.$v]['size']) && $_FILES['mobile_banner_'.$v]['size'] > 0)
							{
								$new_name = time().rand(1000,9999).".".pathinfo($_FILES["mobile_banner_".$v]['name'], PATHINFO_EXTENSION);
								$config['file_name'] = $new_name;
								$this->upload->initialize($config);
								if( ! $this->upload->do_upload('mobile_banner_'.$v)) 
								{
									$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
									$allow_to_update = FALSE;
								}
								else{
									$_FILES["mobile_banner_".$v]['name'] = $new_name;
								}
							}
						}
					}
				}

				if($allow_to_update == TRUE)
				{
					//Database update
					$this->db->trans_start();
					$newData = $this->match_model->add_match();
					$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
					if(sizeof($lang)>0){
						foreach($lang as $k => $v){
							$this->match_model->add_match_content($newData['match_id'],$v);
						}
					}
					$newDataLang = $this->match_model->get_match_lang_data($newData['match_id']);
					$newData['lang'] = $newDataLang;
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_MATCH_ADD, $newData);
					}
					else
					{
						$this->account_model->insert_log(LOG_MATCH_ADD, $newData);
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
				$json['msg']['match_name_error'] = form_error('match_name');
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
		if(permission_validation(PERMISSION_MATCH_UPDATE) == TRUE)
		{
			$data = $this->match_model->get_match_data($id);
			$data['match_lang'] = $this->match_model->get_match_lang_data($id);
			$this->load->view('match_update',$data);
		}
		else
		{
			redirect('home');
		}
	}

	public function update(){
		if(permission_validation(PERMISSION_BONUS_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'match_name_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);

			//Set form rules
			$config = array(
				array(
						'field' => 'match_name',
						'label' => strtolower($this->lang->line('label_match_name')),
						'rules' => 'trim|required',
						'errors' => array(
											'required' => $this->lang->line('error_enter_match_name')
									)
				),
				array(
						'field' => 'match_sequence',
						'label' => strtolower($this->lang->line('label_sequence')),
						'rules' => 'trim|required|integer',
						'errors' => array(
								'required' => $this->lang->line('error_only_digits_allowed'),
								'integer' => $this->lang->line('error_only_digits_allowed')
						)
				),
			);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$match_id = trim($this->input->post('match_id', TRUE));
				$oldData = $this->match_model->get_match_data($match_id);
				$oldLangData = $this->match_model->get_match_lang_data($match_id);
				$oldData['lang'] = json_encode($oldLangData);

				if( ! empty($oldData))
				{
					$allow_to_update = TRUE;
					$config['upload_path'] = MATCH_PATH;
					$config['max_size'] = MATCH_FILE_SIZE;
					$config['allowed_types'] = 'gif|jpg|jpeg|png';
					$config['overwrite'] = TRUE;
					
					$this->load->library('upload', $config);
					
					$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
					if(sizeof($lang)>0){
						foreach($lang as $k => $v){
							if(isset($_FILES['web_banner_'.$v]['size']) && $_FILES['web_banner_'.$v]['size'] > 0)
							{
								$new_name = time().rand(1000,9999).".".pathinfo($_FILES["web_banner_".$v]['name'], PATHINFO_EXTENSION);
								$config['file_name']  = $new_name;
								$this->upload->initialize($config);
								if( ! $this->upload->do_upload('web_banner_'.$v)) 
								{
									$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
									$allow_to_update = FALSE;
								}else{
									$_FILES["web_banner_".$v]['name'] = $new_name;
								}
							}

							if($allow_to_update == TRUE)
							{
								if(isset($_FILES['mobile_banner_'.$v]['size']) && $_FILES['mobile_banner_'.$v]['size'] > 0)
								{
									$new_name = time().rand(1000,9999).".".pathinfo($_FILES["mobile_banner_".$v]['name'], PATHINFO_EXTENSION);
									$config['file_name'] = $new_name;
									$this->upload->initialize($config);
									if( ! $this->upload->do_upload('mobile_banner_'.$v)) 
									{
										$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
										$allow_to_update = FALSE;
									}
									else{
										$_FILES["mobile_banner_".$v]['name'] = $new_name;
									}
								}
							}
						}
					}
					if($allow_to_update == TRUE)
					{
						//Database update
						$this->db->trans_start();
						$newData = $this->match_model->update_match($match_id);
						$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
						if(sizeof($lang)>0){
							$oldDataLang = $this->match_model->get_match_lang_data($newData['match_id']);
							foreach($lang as $k => $v){
								if(isset($oldDataLang[$v])){
									$this->match_model->update_match_content($newData['match_id'],$v);
								}else{
									$this->match_model->add_match_content($newData['match_id'],$v);
								}
							}
						}
						$newDataLang = $this->match_model->get_match_lang_data($newData['match_id']);
						$newData['lang'] = $newDataLang;
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_MATCH_UPDATE, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_MATCH_UPDATE, $newData, $oldData);
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');
							
							//Prepare for ajax update
							$json['response'] = array(
								'id' => $newData['match_id'],
								'match_name' => $newData['match_name'],
								'active' => (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
								'active_code' => $newData['active'],
								'updated_by' => $newData['updated_by'],
								'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),
								'match_start' => date('Y-m-d H:i:s', $newData['match_start']),
								'match_end' => date('Y-m-d H:i:s', $newData['match_end']),
								'match_sequence' => $newData['match_sequence'],
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
				$json['msg']['match_name_error'] = form_error('match_name');
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
					
		if(permission_validation(PERMISSION_BONUS_DELETE) == TRUE)
		{
			$match_id = $this->uri->segment(3);
			$oldData = $this->match_model->get_match_data($match_id);
			
			if( ! empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->match_model->delete_match($match_id);
				$this->match_model->delete_match_lang($match_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_MATCH_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_MATCH_DELETE, $oldData);
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