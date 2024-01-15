<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('message_model', 'player_model','player_game_accounts_model'));
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index(){
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_VIEW) == TRUE)
		{
			$this->save_current_url('message');
			$data['page_title'] = $this->lang->line('title_message');
			$this->session->unset_userdata('searches_message');
			$this->load->view('system_message_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function search(){
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_VIEW) == TRUE)
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
				'system_message_name' => trim($this->input->post('system_message_name', TRUE)),
				'system_message_type' => trim($this->input->post('system_message_type', TRUE)),
				'system_message_genre' => trim($this->input->post('system_message_genre', TRUE)),
				'language_id' => trim($this->input->post('language_id', TRUE)),
				'status' => trim($this->input->post('status', TRUE)),
			);
			
			$this->session->set_userdata('searches_message', $data);
			
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
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'system_message_id',
				1 => 'system_message_name',
				2 => 'system_message_type',
				3 => 'system_message_genre',
				4 => 'system_message_remark',
				5 => 'active',
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
			$arr = $this->session->userdata('searches_message');
			$where = "";
			if( ! empty($arr['system_message_name']))
			{
				if($where == ""){
					$where .= "WHERE system_message_name LIKE '%" . $arr['system_message_name'] . "%' ESCAPE '!'";
				}else{
					$where .= " AND system_message_name LIKE '%" . $arr['system_message_name'] . "%' ESCAPE '!'";
				}
			}

			if( ! empty($arr['system_message_type']))
			{
				if($where == ""){
					$where .= "WHERE system_message_type = " . $arr['system_message_type'];
				}else{
					$where .= " AND system_message_type = " . $arr['system_message_type'];
				}
			}

			if( ! empty($arr['system_message_genre']))
			{
				if($where == ""){
					$where .= "WHERE system_message_genre = " . $arr['system_message_genre'];
				}else{
					$where .= " AND system_message_genre = " . $arr['system_message_genre'];
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
			$query_string = "SELECT {$select} FROM {$dbprefix}system_message $where";
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
					$row[] = $post->system_message_id;
					$row[] = '<span id="uc1_' . $post->system_message_id . '">' . $post->system_message_name . '</span>';
					$row[] = '<span id="uc2_' . $post->system_message_id . '">' . $this->lang->line(get_message_type($post->system_message_type)) . '</span>';
					$row[] = '<span id="uc3_' . $post->system_message_id . '">' . $this->lang->line(get_message_genre($post->system_message_genre)) . '</span>';
					$row[] = '<span id="uc4_' . $post->system_message_id . '">' . $post->system_message_remark . '</span>';
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc5_' . $post->system_message_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc5_' . $post->system_message_id . '">' . $this->lang->line('status_suspend') . '</span>'; break;
					}
					$row[] = '<span id="uc6_' . $post->system_message_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc7_' . $post->system_message_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					$button = '';
					if(permission_validation(PERMISSION_SYSTEM_MESSAGE_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->system_message_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_SYSTEM_MESSAGE_DELETE) == TRUE)
					{
						if($post->system_message_type != MESSAGE_SYSTEM){
							$button .= '<i onclick="deleteData(' . $post->system_message_id . ')" type="button" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i> &nbsp;&nbsp; ';
						}
					}

					if(permission_validation(PERMISSION_SYSTEM_MESSAGE_USER_ADD) == TRUE)
					{
						if($post->active == STATUS_ACTIVE){
							$button .= '<i onclick="addUserMessageData(' . $post->system_message_id . ')" type="button" class="fas fa-envelope nav-icon text-teal" title="' . $this->lang->line('button_send_message')  . '"></i> &nbsp;&nbsp; ';
						}
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
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_ADD) == TRUE)
		{
			$this->load->view('system_message_add');
		}
		else
		{
			redirect('home');
		}
	}

	public function submit(){
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'system_message_name_error' => '',
					'system_message_type_error' => '',
					'system_message_genre_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);

			//Set form rules
			$config = array(
				array(
						'field' => 'system_message_name',
						'label' => strtolower($this->lang->line('label_message_name')),
						'rules' => 'trim|required',
						'errors' => array(
								'required' => $this->lang->line('error_enter_message_name')
						)
				),
				array(
						'field' => 'system_message_type',
						'label' => strtolower($this->lang->line('label_type')),
						'rules' => 'trim|required',
						'errors' => array(
								'required' => $this->lang->line('error_select_type')
						)
				),
				array(
						'field' => 'system_message_genre',
						'label' => strtolower($this->lang->line('label_genre')),
						'rules' => 'trim|required',
						'errors' => array(
								'required' => $this->lang->line('error_select_genres')
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
				$newData = $this->message_model->add_message();
				
				$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
				if(sizeof($lang)>0){
					foreach($lang as $k => $v){
						$message_title = trim($this->input->post('message_title-' . $v, TRUE));
						$message_content = trim($this->input->post('message_content-' . $v, TRUE));
						$multiData = array(
							'message_title' => $message_title,
							'message_content' => $message_content,
						); 
						$this->message_model->add_message_lang($newData['system_message_id'], $multiData, $v);
					}
				}
				$newLangData = $this->message_model->get_message_lang_data($newData['system_message_id']);
				$newData['lang'] = json_encode($newLangData);

				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_SYSTEM_MESSAGE_ADD, $newData);
				}
				else
				{
					$this->account_model->insert_log(LOG_SYSTEM_MESSAGE_ADD, $newData);
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
				$json['msg']['system_message_name_error'] = form_error('system_message_name');
				$json['msg']['system_message_type_error'] = form_error('system_message_type');
				$json['msg']['system_message_genre_error'] = form_error('system_message_genre');
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
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_UPDATE) == TRUE)
		{
			$data = $this->message_model->get_message_data($id);
			$data['message_lang'] = $this->message_model->get_message_lang_data($id);
			$this->load->view('system_message_update',$data);
		}
		else
		{
			redirect('home');
		}
	}

	public function update(){
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'system_message_name_error' => '',
					'system_message_type_error' => '',
					'system_message_genre_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);

			$system_message_id = trim($this->input->post('system_message_id', TRUE));
			$oldData = $this->message_model->get_message_data($system_message_id);
			if( ! empty($oldData))
			{
				//Set form rules
				if($oldData['system_message_type'] != MESSAGE_SYSTEM){
					$config = array(
						array(
								'field' => 'system_message_name',
								'label' => strtolower($this->lang->line('label_message_name')),
								'rules' => 'trim|required',
								'errors' => array(
										'required' => $this->lang->line('error_enter_message_name')
								)
						),
						array(
							'field' => 'system_message_type',
							'label' => strtolower($this->lang->line('label_type')),
							'rules' => 'trim|required',
							'errors' => array(
									'required' => $this->lang->line('error_select_type')
							)
						),
						array(
								'field' => 'system_message_genre',
								'label' => strtolower($this->lang->line('label_genre')),
								'rules' => 'trim|required',
								'errors' => array(
										'required' => $this->lang->line('error_select_genres')
								)
						),
					);
				}else{
					$config = array(
						array(
								'field' => 'system_message_name',
								'label' => strtolower($this->lang->line('label_message_name')),
								'rules' => 'trim|required',
								'errors' => array(
										'required' => $this->lang->line('error_enter_message_name')
								)
						),
					);
				}
				$this->form_validation->set_rules($config);
				$this->form_validation->set_error_delimiters('', '');
				//Form validation
				if ($this->form_validation->run() == TRUE)
				{
					$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
					$oldData['lang'] = json_encode($oldLangData);

					
						//Database update
						$this->db->trans_start();
						$this->message_model->delete_message_lang($system_message_id);
						$newData = $this->message_model->update_message($system_message_id,$oldData);
						$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
						if(sizeof($lang)>0){
							foreach($lang as $k => $v){
								$message_title = trim($this->input->post('message_title-' . $v, TRUE));
								$message_content = trim($this->input->post('message_content-' . $v, TRUE));
								$multiData = array(
									'message_title' => $message_title,
									'message_content' => $message_content,
								); 
								$this->message_model->add_message_lang($newData['system_message_id'], $multiData, $v);
							}
						}
						$newLangData = $this->message_model->get_message_lang_data($newData['system_message_id']);
						$newData['lang'] = json_encode($newLangData);
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_SYSTEM_MESSAGE_UPDATE, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_SYSTEM_MESSAGE_UPDATE, $newData, $oldData);
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');
							
							//Prepare for ajax update
							$json['response'] = array(
								'id' => $newData['system_message_id'],
								'system_message_id' => (isset($bank[$newData['system_message_id']]) ? $bank[$newData['system_message_id']] : ''),
								'system_message_name' => $newData['system_message_name'],
								'system_message_type' => $this->lang->line(get_message_type($newData['system_message_type'])),
								'system_message_genre' => $this->lang->line(get_message_genre($newData['system_message_genre'])),
								'system_message_remark' => $newData['system_message_remark'],
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
					
				}else{
					$json['msg']['system_message_name_error'] = form_error('system_message_name');
					$json['msg']['system_message_type_error'] = form_error('system_message_type');
					$json['msg']['system_message_genre_error'] = form_error('system_message_genre');
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

	public function delete(){
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR, 
			'msg' => ''
		);
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_DELETE) == TRUE)
		{
			$system_message_id = $this->uri->segment(3);
			$oldData = $this->message_model->get_message_data($system_message_id);
			$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
			$oldData['lang'] = json_encode($oldLangData);
			
			if( ! empty($oldData))
			{
				if($oldData['system_message_type'] != MESSAGE_SYSTEM){
					//Database update
					$this->db->trans_start();
					$this->message_model->delete_message($system_message_id);
					$this->message_model->delete_message_lang($system_message_id);
					//$this->message_model->delete_message_with_player($system_message_id);
					
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_SYSTEM_MESSAGE_DELETE, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_SYSTEM_MESSAGE_DELETE, $oldData);
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

	public function player_add(){
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_USER_ADD) == TRUE)
		{
			$system_message_id = $this->uri->segment(3);
			$data = $this->message_model->get_message_data($system_message_id);
			$data['message_lang'] = $this->message_model->get_message_lang_data($system_message_id);
			//$data['channel'] = $this->bank_model->get_bank_channel_list();
			//$data['level'] = $this->level_model->get_level_list();
			$this->load->view('system_message_player_add',$data);
		}
		else
		{
			redirect('home');
		}
	}

	public function player_submit(){
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_USER_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'player_level_error' => '',
					'bank_channel_error' => '',
					'username_error' => '',
					'general_error' => '',
					'agent_error' => '',
					'from_date_error' => '',
					'to_date_error' => '',
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);

			$system_message_id = trim($this->input->post('system_message_id', TRUE));
			$system_message_data = $this->message_model->get_message_data($system_message_id);
			if(!empty($system_message_data) && $system_message_data['active'] == STATUS_ACTIVE){
				//Set form rules
				if($system_message_data['system_message_genre'] == MESSAGE_GENRE_ALL){
					$config = array(
						array(
							'field' => 'system_message_id',
								'label' => strtolower($this->lang->line('label_message_title')),
								'rules' => 'trim|required',
								'errors' => array(
										'required' => $this->lang->line('error_enter_message_title')
								)
						),
					);
				}else if($system_message_data['system_message_genre'] == MESSAGE_GENRE_USER_LEVEL){
					$config = array(
						array(
							'field' => 'player_level',
								'label' => strtolower($this->lang->line('label_player_level')),
								'rules' => 'trim|required',
								'errors' => array(
										'required' => $this->lang->line('error_select_player_level')
								)
						),
					);
				}else if($system_message_data['system_message_genre'] == MESSAGE_GENRE_BANK_CHANNEL){
					$config = array(
						array(
							'field' => 'bank_channel',
								'label' => strtolower($this->lang->line('label_bank_channel')),
								'rules' => 'trim|required',
								'errors' => array(
										'required' => $this->lang->line('error_select_bank_channel')
								)
						),
					);
				}else if($system_message_data['system_message_genre'] == MESSAGE_GENRE_USER_ALL){
					$config = array(
						array(
							'field' => 'agent',
								'label' => strtolower($this->lang->line('label_agent')),
								'rules' => 'trim|required',
								'errors' => array(
										'required' => $this->lang->line('error_upline_not_found')
								)
						),
					);
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
				}else{
					$config = array(
						array(
							'field' => 'username',
								'label' => strtolower($this->lang->line('label_username')),
								'rules' => 'trim|required',
								'errors' => array(
										'required' => $this->lang->line('error_enter_player_username')
								)
						),
					);
				}
				$this->form_validation->set_rules($config);
				$this->form_validation->set_error_delimiters('', '');
				//Form validation
				if ($this->form_validation->run() == TRUE)
				{
					
					//Checking
					$player_level = trim($this->input->post('player_level', TRUE));
					$bank_channel = trim($this->input->post('bank_channel', TRUE));
					$username = trim($this->input->post('username', TRUE));
					$agent = trim($this->input->post('agent', TRUE));

					$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
					$Bdata = array();
					$Bdatalang = array();
					$create_time = time();

					if($system_message_data['system_message_genre'] == MESSAGE_GENRE_ALL){
						$checking['result'] = true;
					/*}else if($system_message_data['system_message_genre'] == MESSAGE_GENRE_USER_LEVEL){
						$checking = $this->level_model->get_level_data($player_level);
					}else if($system_message_data['system_message_genre'] == MESSAGE_GENRE_BANK_CHANNEL){
						$checking = $this->bank_model->get_bank_channel_data($bank_channel);*/
					}else if($system_message_data['system_message_genre'] == MESSAGE_GENRE_USER_ALL){
						$checking['result'] = false;
						$agent_data = $this->user_model->get_user_data_by_username($agent);
						if(!empty($agent_data)){
							$response = $this->user_model->get_downline_data($agent_data['upline']);
							if(!empty($response)){
								$checking['result'] = true;
							}
						}
					}else{
						if(!empty($username)){
							$checking['result'] = true;
						}else{
							$checking['result'] = false;
						}
					}
					if(!empty($checking['result'])){
						$array_key = array(
							'system_message_id' => $system_message_data['system_message_id'],
							'system_message_genre' => $system_message_data['system_message_genre'],
							'player_level' => $player_level,
							'bank_channel' => $bank_channel,
							'username' => $username,
							'agent' => $agent,
							'from_date' => trim($this->input->post('from_date', TRUE)),
							'to_date' => trim($this->input->post('to_date', TRUE)),
						);
						$player_message_list = $this->message_model->get_player_all_data_by_message_genre($array_key);
						$player_game_account_list = array();
						if(!empty($player_message_list)){
							if($system_message_data['system_message_templete'] == SYSTEM_MESSAGE_PLATFORM_WM_ACCOUNT){
								$player_array_id = array();
								foreach($player_message_list as $player_message_row){
									if( ! in_array($player_message_row['player_id'], $player_array_id))
				    				{
				    					array_push($player_array_id,$player_message_row['player_id']);
				    				}
								}

								if(!empty($player_array_id)){
									$player_game_account_list_temp = $this->player_game_accounts_model->get_all_player_game_accounts_data_by_game_provider_code_player_ids($player_array_id,"WM");
									if(!empty($player_game_account_list_temp)){
										foreach($player_game_account_list_temp as $player_game_account_list_temp_row){
											$player_game_account_list[$player_game_account_list_temp_row['player_id']] = $player_game_account_list_temp_row;
										}
									}
								}
							}

							if(sizeof($player_message_list)>0){
								foreach($player_message_list as $row){
									if($system_message_data['system_message_templete'] == SYSTEM_MESSAGE_PLATFORM_WM_ACCOUNT){
										if(isset($player_game_account_list[$row['player_id']])){
											$PBdata = array(
												'system_message_id'	=> $system_message_id,
												'player_id'			=> $row['player_id'],
												'username'			=> $row['username'],
												'active' 			=> STATUS_ACTIVE,
												'is_read'			=> MESSAGE_UNREAD,
												'created_by'		=> $this->session->userdata('username'),
												'created_date'		=> $create_time,
											);
											array_push($Bdata, $PBdata);
										}
									}else{
										$PBdata = array(
											'system_message_id'	=> $system_message_id,
											'player_id'			=> $row['player_id'],
											'username'			=> $row['username'],
											'active' 			=> STATUS_ACTIVE,
											'is_read'			=> MESSAGE_UNREAD,
											'created_by'		=> $this->session->userdata('username'),
											'created_date'		=> $create_time,
										);
										array_push($Bdata, $PBdata);
									}
								}
							}
							$this->db->trans_start();
							if( ! empty($Bdata))
							{
								$this->db->insert_batch('system_message_user', $Bdata);
								if($this->session->userdata('user_group') == USER_GROUP_USER) 
								{
									$this->user_model->insert_log(LOG_SYSTEM_MESSAGE_USER_ADD, $system_message_data);
								}
								else
								{
									$this->account_model->insert_log(LOG_SYSTEM_MESSAGE_USER_ADD, $system_message_data);
								}
							}
							$this->db->trans_complete();
							if ($this->db->trans_status() === TRUE)
							{
								$success_message_data = $this->message_model->get_message_bluk_data($system_message_id,$create_time);
								if(sizeof($lang)>0){
									if(!empty($player_message_list) && sizeof($player_message_list)>0){
										foreach($player_message_list as $player_message_list_row){
											if(isset($success_message_data[$player_message_list_row['player_id']])){
												foreach($lang as $k => $v){
													$replace_string_array = array(
														SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $player_message_list_row['username'],
														SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),
														SYSTEM_MESSAGE_PLATFORM_VALUE_AMOUNT => 0,
														SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_NAME => "",
														SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_MULTIPLY => 0,
														SYSTEM_MESSAGE_PLATFORM_VALUE_REWARD => 0,
														SYSTEM_MESSAGE_PLATFORM_VALUE_REMARK => "",
														SYSTEM_MESSAGE_PLATFORM_VALUE_LEVEL => 0,
														SYSTEM_MESSAGE_PLATFORM_VALUE_VIP_BANK_ACCOUNT => "",
														SYSTEM_MESSAGE_PLATFORM_VALUE_VIP_BANK_CODE => "",
														SYSTEM_MESSAGE_PLATFORM_VALUE_WM_ACCOUNT_USERNAME => ((isset($player_game_account_list[$player_message_list_row['player_id']])) ? $player_game_account_list[$player_message_list_row['player_id']]['game_id'] : ""),
														SYSTEM_MESSAGE_PLATFORM_VALUE_WM_ACCOUNT_PASSWORD => ((isset($player_game_account_list[$player_message_list_row['player_id']])) ? $player_game_account_list[$player_message_list_row['player_id']]['password'] : ""),
													);

													$PBdataLang = array(
														'system_message_user_id'	=> $success_message_data[$player_message_list_row['player_id']],
														'system_message_title'		=> $this->input->post('message_title_'.$v, TRUE),
														'system_message_content'	=> get_system_message_content($this->input->post('message_content_'.$v, TRUE),$replace_string_array),
														'language_id' 				=> $v
													);
													array_push($Bdatalang, $PBdataLang);
												}
											}
										}	
									}
								}
								$this->db->insert_batch('system_message_user_lang', $Bdatalang);
								$json['status'] = EXIT_SUCCESS;
								$json['msg'] = $this->lang->line('success_added');
							}
							else
							{
								$json['msg'] = $this->lang->line('error_failed_to_add');
							}
						}else{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_added');
						}
					}else{
						$json['msg'] = $this->lang->line('error_failed_to_add');
					}
				}else{
					if($system_message_data['system_message_genre'] == MESSAGE_GENRE_ALL){
						$json['msg']['system_message_id_error'] = form_error('system_message_id');
					}else if($system_message_data['system_message_genre'] == MESSAGE_GENRE_USER_LEVEL){
						$json['msg']['player_level_error'] = form_error('player_level');
					}else if($system_message_data['system_message_genre'] == MESSAGE_GENRE_BANK_CHANNEL){
						$json['msg']['bank_channel_error'] = form_error('bank_channel');
					}else if($system_message_data['system_message_genre'] == MESSAGE_GENRE_BANK_CHANNEL){
						$json['msg']['bank_channel_error'] = form_error('agent');
					}else if($system_message_data['system_message_genre'] == MESSAGE_GENRE_USER_ALL){
						$json['msg']['agent_error'] = form_error('agent');
						$json['msg']['from_date_error'] = form_error('from_date');
						$json['msg']['to_date_error'] = form_error('to_date');
					}else{
						$json['msg']['username_error'] = form_error('username');
					}
				}
			}else{
				$json['msg'] = $this->lang->line('error_failed_to_add');
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

	public function player(){
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_USER_VIEW) == TRUE)
		{
			$this->save_current_url('message/player');
			$data['page_title'] = $this->lang->line('title_message_player');
			$this->session->unset_userdata('searches_message_player');
			$this->load->view('system_message_player_view', $data);
		}
		else
		{
			redirect('home');
		}
	}


	public function player_search(){
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_USER_VIEW) == TRUE)
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
				'system_message_name' => trim($this->input->post('system_message_name', TRUE)),
				'username' => trim($this->input->post('username', TRUE)),
				'is_read' => trim($this->input->post('is_read', TRUE)),
				'status' => trim($this->input->post('status', TRUE)),
			);
			
			$this->session->set_userdata('searches_message_player', $data);
			
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

	public function player_listing(){
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_USER_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'a.system_message_user_id',
				1 => 'b.system_message_name',
				2 => 'a.username',
				3 => 'a.is_read',
				4 => 'a.active',
				5 => 'a.created_by',
				6 => 'a.created_date',
				7 => 'a.updated_by',
				8 => 'a.updated_date',
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
			$arr = $this->session->userdata('searches_message_player');
			$where = "";
			if( ! empty($arr['system_message_name']))
			{
				$where .= " AND b.system_message_name LIKE '%" . $arr['system_message_name'] . "%' ESCAPE '!'";
			}

			if( ! empty($arr['username']))
			{
				$where .= " AND a.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";
			}

			if(isset($arr['is_read'])){
				if($arr['is_read'] == MESSAGE_UNREAD OR $arr['is_read'] == MESSAGE_READ)
				{
					$where .= ' AND a.is_read = ' . $arr['status'];
				}
			}
			
			
			if(isset($arr['status'])){
				if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_INACTIVE)
				{
					$where .= ' AND a.active = ' . $arr['status'];
				}
			}

			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;

			$posts = NULL;
			$query_string = "SELECT {$select} FROM {$dbprefix}system_message_user a,{$dbprefix}system_message b WHERE a.system_message_id = b.system_message_id $where";
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
					$row[] = $post->system_message_user_id;
					$row[] = $post->system_message_name;
					$row[] = $post->username;
					switch($post->is_read)
					{
						case MESSAGE_READ: $row[] = '<span class="badge bg-success" id="uc1_' . $post->system_message_user_id . '">' . $this->lang->line('label_isread') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc1_' . $post->system_message_user_id . '">' . $this->lang->line('label_unread') . '</span>'; break;
					}

					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc2_' . $post->system_message_user_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc2_' . $post->system_message_user_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					$row[] = '<span id="uc3_' . $post->system_message_user_id . '">' . (( ! empty($post->created_by)) ? $post->created_by : '-') . '</span>';
					$row[] = '<span id="uc4_' . $post->system_message_user_id . '">' . (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-') . '</span>';
					$row[] = '<span id="uc5_' . $post->system_message_user_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc6_' . $post->system_message_user_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					$button = '';
					if(permission_validation(PERMISSION_SYSTEM_MESSAGE_USER_VIEW_CONTENT) == TRUE){
						$button .= '<i onclick="viewData(' . $post->system_message_user_id . ')" class="fas fa-eye nav-icon text-lime" title="' . $this->lang->line('button_view')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_SYSTEM_MESSAGE_USER_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->system_message_user_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_SYSTEM_MESSAGE_USER_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->system_message_user_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
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

	public function player_edit($id = NULL){
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_USER_UPDATE) == TRUE)
		{
			$data = $this->message_model->get_player_message_data($id);
			if(!empty($data)){
				$data['message_data'] = $this->message_model->get_message_data($data['system_message_id']);
			}
			$this->load->view('system_message_player_update',$data);
		}
		else
		{
			redirect('home');
		}
	}

	public function player_update(){
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_USER_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'system_message_user_id_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);

			//Set form rules
			$config = array(
				array(
						'field' => 'system_message_user_id',
						'label' => strtolower($this->lang->line('label_message_title')),
						'rules' => 'trim|required',
						'errors' => array(
								'required' => $this->lang->line('error_enter_message_title')
						)
				),
			);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$system_message_user_id = trim($this->input->post('system_message_user_id', TRUE));
				$oldData = $this->message_model->get_player_message_data($system_message_user_id);
				if( ! empty($oldData))
				{
					//Database update
					$this->db->trans_start();
					$newData = $this->message_model->update_player_message($system_message_user_id);
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_SYSTEM_MESSAGE_USER_UPDATE, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_SYSTEM_MESSAGE_USER_UPDATE, $newData, $oldData);
					}
					$this->db->trans_complete();
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_updated');
						
						//Prepare for ajax update
						$json['response'] = array(
							'id' => $newData['system_message_user_id'],
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
			}else{
				$json['msg']['system_message_user_id_error'] = form_error('system_message_user_id');
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

	public function player_delete($id = NULL){
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR, 
			'msg' => ''
		);
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_USER_DELETE) == TRUE)
		{
			$system_message_user_id = $this->uri->segment(3);
			$oldData = $this->message_model->get_player_message_data($system_message_user_id);
			if( ! empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->message_model->delete_player_message($system_message_user_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_SYSTEM_MESSAGE_USER_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_SYSTEM_MESSAGE_USER_DELETE, $oldData);
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

	public function player_view_content($id = NULL){
		if(permission_validation(PERMISSION_SYSTEM_MESSAGE_USER_VIEW_CONTENT) == TRUE)
		{
			$data = $this->message_model->get_player_message_data($id);
			if(!empty($data)){
				$data['message_lang'] = $this->message_model->get_player_message_lang_data($id);
			}
			$this->load->view('system_message_player_view_content',$data);
		}
		else
		{
			redirect('home');
		}
	}
}