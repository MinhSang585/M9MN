<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bonus extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('bonus_model','player_model'));
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index(){
		if(permission_validation(PERMISSION_BONUS_VIEW) == TRUE)
		{
			$this->save_current_url('bonus');
			
			$data['page_title'] = $this->lang->line('title_bonus');
			$this->load->view('bonus_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function listing()
	{
		if(permission_validation(PERMISSION_BONUS_VIEW) == TRUE){
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);

			//Table Columns
			$columns = array(
				0 => 'bonus_id',
				1 => 'bonus_name',
				2 => 'active',
				3 => 'bonus_seq',
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

			$query = array(
					'select' => implode(',', $columns),
					'table' => 'bonus',
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
					$row[] = $post->bonus_id;
					$row[] = '<span id="uc1_' . $post->bonus_id . '">' . $post->bonus_name . '</span>';
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc2_' . $post->bonus_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc2_' . $post->bonus_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					$row[] = '<span id="uc4_' . $post->bonus_id . '">' . (( ! empty($post->bonus_seq)) ? $post->bonus_seq : '0') . '</span>';
					$row[] = '<span id="uc5_' . $post->bonus_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc6_' . $post->bonus_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';

					$button = '';
					if(permission_validation(PERMISSION_BONUS_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->bonus_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_web_banner')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_BONUS_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->bonus_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
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
		if(permission_validation(PERMISSION_BONUS_ADD) == TRUE)
		{
			$this->load->view('bonus_add');
		}
		else
		{
			redirect('home');
		}
	}

	public function submit(){
		if(permission_validation(PERMISSION_BONUS_ADD) == TRUE)
		{
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'bonus_name_error' => '',
					'bonus_seq_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			//Set form rules
			$config = array(
				array(
						'field' => 'bonus_name',
						'label' => strtolower($this->lang->line('label_bonus_name')),
						'rules' => 'trim|required',
						'errors' => array(
											'required' => $this->lang->line('error_enter_bonus_name')
									)
				),
				array(
						'field' => 'bonus_seq',
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
				$config['upload_path'] = BONUS_PATH;
				$config['max_size'] = BONUS_FILE_SIZE;
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
					$newData = $this->bonus_model->add_bonus();
					$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
					if(sizeof($lang)>0){
						foreach($lang as $k => $v){
							$this->bonus_model->add_bonus_content($newData['bonus_id'],$v);
						}
					}
					$newDataLang = $this->bonus_model->get_bonus_lang_data($newData['bonus_id']);
					$newData['lang'] = $newDataLang;
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_PROMOTION_ADD, $newData);
					}
					else
					{
						$this->account_model->insert_log(LOG_PROMOTION_ADD, $newData);
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
				$json['msg']['bonus_name_error'] = form_error('bonus_name');
				$json['msg']['bonus_seq_error'] = form_error('bonus_seq');
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
		if(permission_validation(PERMISSION_BONUS_UPDATE) == TRUE)
		{
			$data = $this->bonus_model->get_bonus_data($id);
			$data['bonus_lang'] = $this->bonus_model->get_bonus_lang_data($id);
			$this->load->view('bonus_update',$data);
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
					'bonus_name_error' => '',
					'bonus_seq_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);

			//Set form rules
			$config = array(
				array(
						'field' => 'bonus_name',
						'label' => strtolower($this->lang->line('label_bonus_name')),
						'rules' => 'trim|required',
						'errors' => array(
											'required' => $this->lang->line('error_enter_bonus_name')
									)
				),
				array(
						'field' => 'bonus_seq',
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
				$bonus_id = trim($this->input->post('bonus_id', TRUE));
				$oldData = $this->bonus_model->get_bonus_data($bonus_id);
				$oldLangData = $this->bonus_model->get_bonus_lang_data($bonus_id);
				$oldData['lang'] = json_encode($oldLangData);

				if( ! empty($oldData))
				{
					$allow_to_update = TRUE;
					$config['upload_path'] = BONUS_PATH;
					$config['max_size'] = BONUS_FILE_SIZE;
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
						$newData = $this->bonus_model->update_bonus($bonus_id);
						$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
						if(sizeof($lang)>0){
							$oldDataLang = $this->bonus_model->get_bonus_lang_data($newData['bonus_id']);
							foreach($lang as $k => $v){
								if(isset($oldDataLang[$v])){
									$this->bonus_model->update_bonus_content($newData['bonus_id'],$v);
								}else{
									$this->bonus_model->add_bonus_content($newData['bonus_id'],$v);
								}
							}
						}
						$newDataLang = $this->bonus_model->get_bonus_lang_data($newData['bonus_id']);
						$newData['lang'] = $newDataLang;
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_BONUS_UPDATE, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_BONUS_UPDATE, $newData, $oldData);
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');
							
							//Prepare for ajax update
							$json['response'] = array(
								'id' => $newData['bonus_id'],
								'bonus_name' => $newData['bonus_name'],
								'bonus_seq' => $newData['bonus_seq'],
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
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
					}
				}
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
				}
			}else{
				$json['msg']['bonus_name_error'] = form_error('bonus_name');
				$json['msg']['bonus_seq_error'] = form_error('bonus_seq');
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
			$bonus_id = $this->uri->segment(3);
			$oldData = $this->bonus_model->get_bonus_data($bonus_id);
			
			if( ! empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->bonus_model->delete_bonus($bonus_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_BONUS_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_BONUS_DELETE, $oldData);
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
		if(permission_validation(PERMISSION_PLAYER_BONUS_VIEW) == TRUE)
		{
			$this->save_current_url('bonus/player');
			$this->session->unset_userdata('searches_player_bonus');
			$data = quick_search();
			$data_search = array( 
				'from_date' => date('Y-m-d 00:00:00'),
				'to_date' => date('Y-m-d 23:59:59'),
				'username' => "",
				'status' => "",
				'bonus_name' => "",
			);
			$this->session->set_userdata('searches_player_bonus', $data_search);
			$data['page_title'] = $this->lang->line('title_player_bonus');
			$this->load->view('player_bonus_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function player_search(){
		if(permission_validation(PERMISSION_PLAYER_BONUS_VIEW) == TRUE)
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

			//Set form rules
			$config = array(
				array(
					'field' => 'from_date',
					'label' => strtolower($this->lang->line('label_from_date')),
					'rules' => 'trim|required|callback_full_datetime_check',
					'errors' => array(
							'required' => $this->lang->line('error_invalid_datetime_format'),
							'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
					)
				),
				array(
					'field' => 'to_date',
					'label' => strtolower($this->lang->line('label_to_date')),
					'rules' => 'trim|required|callback_full_datetime_check',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_datetime_format'),
						'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
					)
				)
			);	
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$from_date = strtotime(trim($this->input->post('from_date', TRUE)));
				$to_date = strtotime(trim($this->input->post('to_date', TRUE)));
				$days = $this->cal_days_in_year(date('Y', $from_date));
				$date_range = ($days * 86400);
				$time_diff = ($to_date - $from_date);
				
				if($time_diff < 0 OR $time_diff > $date_range)
				{
					$json['msg']['general_error'] = $this->lang->line('error_invalid_year_range');
				}
				else
				{
					$data = array( 
						'from_date' => trim($this->input->post('from_date', TRUE)),
						'to_date' => trim($this->input->post('to_date', TRUE)),
						'username' => trim($this->input->post('username', TRUE)),
						'status' => trim($this->input->post('status', TRUE)),
						'bonus_name' => trim($this->input->post('bonus_name', TRUE)),
					);
					$this->session->set_userdata('searches_player_bonus', $data);
					$json['status'] = EXIT_SUCCESS;
				}
			}
			else 
			{
				$json['msg']['from_date_error'] = form_error('from_date');
				$json['msg']['to_date_error'] = form_error('to_date');
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

	public function player_listing(){
		if(permission_validation(PERMISSION_PLAYER_BONUS_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			
			$columns = array( 
				0 => 'a.player_bonus_id',
				1 => 'a.updated_date',
				2 => 'a.bonus_name',
				3 => 'b.username',
				4 => 'a.reward_amount',
				5 => 'a.achieve_amount',
				6 => 'a.status',
				7 => 'a.remark',
				8 => 'a.created_by',
				9 => 'a.created_date',
			);
			$sum_columns = array( 
				0 => 'SUM(a.reward_amount) AS total_reward',
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

			$arr = $this->session->userdata('searches_player_bonus');
			$where = '';
			if(isset($arr['from_date']))
			{
				if(isset($arr['from_date']))
				{
					$where .= ' AND a.updated_date >= ' . strtotime($arr['from_date']);
				}
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND a.updated_date <= ' . strtotime($arr['to_date']);
				}
				if( ! empty($arr['username']))
				{
					$where .= " AND b.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	
				}
				if($arr['status'] !==""){
					if($arr['status'] == STATUS_PENDING OR $arr['status'] == STATUS_COMPLETE OR $arr['status'] == STATUS_CANCEL)
					{
						$where .= ' AND a.status = ' . $arr['status'];
					}
				}
				if( ! empty($arr['bonus_name']))
				{
					$where .= " AND a.bonus_name LIKE '%" . $arr['bonus_name'] . "%' ESCAPE '!'";	
				}
			}
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;

			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}player_bonus a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
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
					$row[] = $post->player_bonus_id;
					$row[] = (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-');
					$row[] = $post->bonus_name;
					$row[] = $post->username;
					$row[] = number_format($post->reward_amount,'2','.',',');
					$row[] = number_format($post->achieve_amount,'2','.',',');
					switch($post->status)
					{
						case STATUS_COMPLETE: $row[] = '<span class="badge bg-success" id="uc1_' . $post->player_bonus_id . '">' . $this->lang->line('status_completed') . '</span>'; break;
						case STATUS_CANCEL: $row[] = '<span class="badge bg-danger" id="uc1_' . $post->player_bonus_id . '">' . $this->lang->line('status_cancelled') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc1_' . $post->player_bonus_id . '">' . $this->lang->line('status_pending') . '</span>'; break;
					}
					$row[] = '<span id="uc2_' . $post->player_bonus_id . '">' . ( ! empty($post->remark) ? $post->remark : '-') . '</span>';
					$row[] = '<span id="uc3_' . $post->player_bonus_id . '">' . (( ! empty($post->created_by)) ? $post->created_by : '-') . '</span>';
					$row[] = '<span id="uc4_' . $post->player_bonus_id . '">' . (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-') . '</span>';
					if(permission_validation(PERMISSION_PLAYER_BONUS_UPDATE) == TRUE && $post->status == STATUS_PENDING)
					{
						$row[] = '<i id="uc3_' . $post->player_bonus_id . '" onclick="updateData(' . $post->player_bonus_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					else
					{
						$row[] = '';
					}
					
					$data[] = $row;
				}
			}
			$sum_select = implode(',', $sum_columns);
			$total_data = array(
				'total_reward' => 0,
			);
			$query_string = "SELECT {$sum_select} FROM {$dbprefix}player_bonus a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
			$query = $this->db->query($query_string);
			if($query->num_rows() > 0)
			{
				foreach($query->result() as $row)
				{
					$total_data = array(
						'total_reward' => (($row->total_reward > 0) ? $row->total_reward : 0),
					);
				}
			}			
			$query->free_result();
			//Output
			$json_data = array(
							"draw"            => intval($this->input->post('draw')),
							"recordsFiltered" => intval($totalFiltered), 
							"data"            => $data,
							"total_data"      => $total_data,
							"csrfHash" 		  => $this->security->get_csrf_hash()					
						);
				
			echo json_encode($json_data); 
			exit();
		}
	}


	public function player_add(){
		if(permission_validation(PERMISSION_PLAYER_BONUS_ADD) == TRUE)
		{
			$data['bonus'] = $this->bonus_model->get_bonus_data_list();
			$this->load->view('player_bonus_add',$data);
		}
		else
		{
			redirect('home');
		}
	}

	public function player_submit(){
		if(permission_validation(PERMISSION_PLAYER_BONUS_ADD) == TRUE)
		{
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
								'username_error' => '',
								'bonus_id_error' => '',
								'reward_amount_error' => '',
								'achieve_amount_error' => '',
								'general_error' => ''
							), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			$config = array(
				array(
					'field' => 'username',
					'label' => strtolower($this->lang->line('label_player_username')),
					'rules' => 'trim|required',
					'errors' => array(
							'required' => $this->lang->line('error_enter_player_username'),
					)
				),
				array(
					'field' => 'bonus_id',
					'label' => strtolower($this->lang->line('label_bonus_name')),
					'rules' => 'trim|required',
					'errors' => array(
							'required' => $this->lang->line('error_enter_bonus_name'),
					)
				),
				array(
					'field' => 'reward_amount',
					'label' => strtolower($this->lang->line('label_reward_amount')),
					'rules' => 'trim|required|greater_than[0]',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_points'),
						'greater_than' => $this->lang->line('error_greater_than'),
					)
				),
				array(
					'field' => 'achieve_amount',
					'label' => strtolower($this->lang->line('label_rollover')),
					'rules' => 'trim|required|greater_than[0]',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_points'),
						'greater_than' => $this->lang->line('error_greater_than'),
					)
				),
			);	
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run() == TRUE)
			{
				$bonus_id = trim($this->input->post('bonus_id', TRUE));
				$player_username = trim($this->input->post('username', TRUE));
				$bonusData  = $this->bonus_model->get_bonus_data_active($bonus_id);
				$playerData = $this->player_model->get_player_data_by_username($player_username);
				if(!empty($bonusData) && !empty($playerData)){
					$this->db->trans_start();
					$newData = $this->bonus_model->add_player_bonus($bonusData,$playerData);
					$insert_wallet_data = array(
						"player_id" => $newData['player_id'],
						"username" => $newData['username'],
						"amount" => $newData['reward_amount'],
					);
					$this->player_model->update_player_wallet($insert_wallet_data);
					$this->general_model->insert_cash_transfer_report($playerData, $insert_wallet_data['amount'], TRANSFER_BONUS);
					$newDataPlayer = $this->player_model->get_player_data_by_username($player_username);
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_PLAYER_BONUS_ADD, $newData);
						$this->user_model->insert_log(LOG_PLAYER_UPDATE, $newDataPlayer, $playerData);
					}
					else
					{
						$this->account_model->insert_log(LOG_PLAYER_BONUS_ADD, $newData);
						$this->account_model->insert_log(LOG_PLAYER_UPDATE, $newDataPlayer, $playerData);
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
					$json['msg']['general_error'] = $this->lang->line('error_username_not_found');
				}
			}
			else
			{
				$json['msg']['username_error'] = form_error('username');
				$json['msg']['bonus_id_error'] = form_error('bonus_id');
				$json['msg']['reward_amount_error'] = form_error('reward_amount');
				$json['msg']['achieve_amount_error'] = form_error('achieve_amount');
			}
		}
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($json))
			->_display();
				
		exit();	
	}
}