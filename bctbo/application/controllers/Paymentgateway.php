<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paymentgateway extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('payment_gateway_model','player_model'));
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index()
	{
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_VIEW) == TRUE)
		{
			$this->save_current_url('paymentgateway');
			
			$data['page_title'] = $this->lang->line('title_payment_gateway');
			$this->load->view('payment_gateway_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function listing(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
				0 => 'payment_gateway_id',
				1 => 'payment_gateway_name',
				2 => 'payment_gateway_sequence',
				3 => 'payment_gateway_admin_verification',
				4 => 'payment_gateway_rate_type',
				5 => 'payment_gateway_rate',
				6 => 'payment_gateway_min_amount',
				7 => 'payment_gateway_max_amount',
				8 => 'updated_by',
				9 => 'updated_date',
				10 => 'payment_gateway_type_code',
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
				'search_values' => array(STATUS_ACTIVE),
				'search_types' => array('equal'),
				'search_columns' => array('active'),
				'table' => 'payment_gateway',
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
					$row[] = $post->payment_gateway_id;
					$row[] = $this->lang->line($post->payment_gateway_name)." (".$this->lang->line(get_payment_gateway_type($post->payment_gateway_type_code)).")";
					$row[] = '<span id="uc1_' . $post->payment_gateway_id . '">' . $post->payment_gateway_sequence . '</span>';
					
					switch($post->payment_gateway_admin_verification)
					{
						case STATUS_YES: $row[] = '<span class="badge bg-success" id="uc11_' . $post->payment_gateway_id . '">' . $this->lang->line('status_yes') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc11_' . $post->payment_gateway_id . '">' . $this->lang->line('status_no') . '</span>'; break;
					}
					$row[] = '<span id="uc13_' . $post->payment_gateway_id . '">' .$this->lang->line(get_payment_gateway_rate_type($post->payment_gateway_rate_type)) .  '</span>';
					$row[] = '<span id="uc12_' . $post->payment_gateway_id . '">' . $post->payment_gateway_rate . '</span>';
					$row[] = '<span id="uc23_' . $post->payment_gateway_id . '">' . $post->payment_gateway_min_amount . '</span>';
					$row[] = '<span id="uc24_' . $post->payment_gateway_id . '">' . $post->payment_gateway_max_amount . '</span>';
					$row[] = '<span id="uc9_' . $post->payment_gateway_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc10_' . $post->payment_gateway_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					
					if(permission_validation(PERMISSION_PAYMENT_GATEWAY_UPDATE) == TRUE)
					{
						$row[] = '<i onclick="updateData(' . $post->payment_gateway_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i>';
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

    public function edit($id = NULL){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_UPDATE) == TRUE)
		{
			$data = $this->payment_gateway_model->get_payment_gateway_data($id);
			$this->load->view('payment_gateway_update', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function update(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'payment_gateway_sequence_error' => '',
										'fixed_day_error' => '',
										'fixed_from_time_error' => '',
										'fixed_to_time_error' => '',
										'urgent_date_error' => '',
										'general_error' => ''
									), 
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			
			//Set form rules
			$config = array(
				array(
						'field' => 'payment_gateway_sequence',
						'label' => strtolower($this->lang->line('label_sequence')),
						'rules' => 'trim|required|integer',
						'errors' => array(
								'required' => $this->lang->line('error_only_digits_allowed'),
								'integer' => $this->lang->line('error_only_digits_allowed')
						)
				),
				array(
						'field' => 'fixed_day',
						'label' => strtolower($this->lang->line('label_day')),
						'rules' => 'trim|integer',
						'errors' => array(
											'integer' => $this->lang->line('error_select_fixed_day')
									)
				),
				array(
						'field' => 'fixed_from_time',
						'label' => strtolower($this->lang->line('label_from_time')),
						'rules' => 'trim|callback_time_check',
						'errors' => array(
											'time_check' => $this->lang->line('error_invalid_time_format')
									)
				),
				array(
						'field' => 'fixed_to_time',
						'label' => strtolower($this->lang->line('label_to_time')),
						'rules' => 'trim|callback_time_check',
						'errors' => array(
											'time_check' => $this->lang->line('error_invalid_time_format')
									)
				),
				array(
						'field' => 'urgent_date',
						'label' => strtolower($this->lang->line('label_date')),
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
				$payment_gateway_id = trim($this->input->post('payment_gateway_id', TRUE));
				$oldData = $this->payment_gateway_model->get_payment_gateway_data($payment_gateway_id);
				
				if( ! empty($oldData))
				{
					$allow_to_update = TRUE;
				
					$config['upload_path'] = BANKS_PATH;
					$config['max_size'] = BANKS_FILE_SIZE;
					$config['allowed_types'] = 'gif|jpg|jpeg|png';
					$config['overwrite'] = TRUE;
					
					$this->load->library('upload', $config);
					
					if(isset($_FILES['web_image_on']['size']) && $_FILES['web_image_on']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["web_image_on"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('web_image_on')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["web_image_on"]['name'] = $new_name;
						}
					}
					
					if($allow_to_update == TRUE)
					{
						if(isset($_FILES['web_image_off']['size']) && $_FILES['web_image_off']['size'] > 0)
						{
							$new_name = time().rand(1000,9999).".".pathinfo($_FILES["web_image_off"]['name'], PATHINFO_EXTENSION);
							$config['file_name']  = $new_name;
							$this->upload->initialize($config);
							if( ! $this->upload->do_upload('web_image_off')) 
							{
								$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
								$allow_to_update = FALSE;
							}else{
								$_FILES["web_image_off"]['name'] = $new_name;
							}
						}
					}

					if(isset($_FILES['mobile_image_on']['size']) && $_FILES['mobile_image_on']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["mobile_image_on"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('mobile_image_on')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["mobile_image_on"]['name'] = $new_name;
						}
					}
					
					if($allow_to_update == TRUE)
					{
						if(isset($_FILES['mobile_image_off']['size']) && $_FILES['mobile_image_off']['size'] > 0)
						{
							$new_name = time().rand(1000,9999).".".pathinfo($_FILES["mobile_image_off"]['name'], PATHINFO_EXTENSION);
							$config['file_name']  = $new_name;
							$this->upload->initialize($config);
							if( ! $this->upload->do_upload('mobile_image_off')) 
							{
								$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
								$allow_to_update = FALSE;
							}else{
								$_FILES["mobile_image_off"]['name'] = $new_name;
							}
						}
					}
					if($allow_to_update == TRUE)
					{
						//Database update
						$this->db->trans_start();
						$newData = $this->payment_gateway_model->update_payment_gateway($payment_gateway_id);
						$newData['payment_gateway_current_usage'] = $oldData['payment_gateway_current_usage'];
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_PAYMENT_GATEWAY_UPDATE, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_PAYMENT_GATEWAY_UPDATE, $newData, $oldData);
						}
						
						$this->db->trans_complete();
						
						if ($this->db->trans_status() === TRUE)
						{
							//Delete old banner
							if(isset($_FILES['web_image_on']['size']) && $_FILES['web_image_on']['size'] > 0)
							{
								if($oldData['web_image_on'] != "" && file_exists(BANKS_PATH.$oldData['web_image_on'])){
									unlink(BANKS_PATH . $oldData['web_image_on']);
								}
							}

							if(isset($_FILES['web_image_off']['size']) && $_FILES['web_image_off']['size'] > 0)
							{
								if($oldData['web_image_off'] != "" && file_exists(BANKS_PATH.$oldData['web_image_off'])){
									unlink(BANKS_PATH . $oldData['web_image_off']);
								}
							}

							if(isset($_FILES['mobile_image_on']['size']) && $_FILES['mobile_image_on']['size'] > 0)
							{
								if($oldData['mobile_image_on'] != "" && file_exists(BANKS_PATH.$oldData['mobile_image_on'])){
									unlink(BANKS_PATH . $oldData['mobile_image_on']);
								}
							}

							if(isset($_FILES['mobile_image_off']['size']) && $_FILES['mobile_image_off']['size'] > 0)
							{
								if($oldData['mobile_image_off'] != "" && file_exists(BANKS_PATH.$oldData['mobile_image_off'])){
									unlink(BANKS_PATH . $oldData['mobile_image_off']);
								}
							}
							
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');
							
							//Prepare for ajax update
							$json['response'] = array(
								'id' => $newData['payment_gateway_id'],
								'payment_gateway_sequence' => $newData['payment_gateway_sequence'],
								'payment_gateway_admin_verification' => (($newData['payment_gateway_admin_verification'] == STATUS_YES) ? $this->lang->line('status_yes') : $this->lang->line('status_no')),
								'payment_gateway_admin_verification_code' => $newData['payment_gateway_admin_verification'],
								'is_maintenance' => (($newData['is_maintenance'] == STATUS_YES) ? $this->lang->line('status_yes') : $this->lang->line('status_no')),
								'is_maintenance_code' => $newData['is_maintenance'],
								'fixed_maintenance' => (($newData['fixed_maintenance'] == STATUS_YES) ? $this->lang->line('status_yes') : $this->lang->line('status_no')),
								'fixed_maintenance_code' => $newData['fixed_maintenance'],
								'fixed_day' => $this->lang->line(get_day($newData['fixed_day'])),
								'fixed_from_time' => $newData['fixed_from_time'],
								'fixed_to_time' => $newData['fixed_to_time'],
								'urgent_maintenance' => (($newData['urgent_maintenance'] == STATUS_YES) ? $this->lang->line('status_yes') : $this->lang->line('status_no')),
								'urgent_maintenance_code' => $newData['urgent_maintenance'],
								'payment_gateway_rate_type' => $this->lang->line(get_payment_gateway_rate_type($newData['payment_gateway_rate_type'])),
								'payment_gateway_rate' => $newData['payment_gateway_rate'],
								'payment_gateway_min_amount' => $newData['payment_gateway_min_amount'],
								'payment_gateway_max_amount' => $newData['payment_gateway_max_amount'],
								'urgent_date' => date('Y-m-d H:i', $newData['urgent_date']),
								'updated_by' => $newData['updated_by'],
								'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),
							);
						}
						else
						{
							$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
						}
					}
				}
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
				}	
			}
			else 
			{
				$json['msg']['payment_gateway_sequence_error'] = form_error('payment_gateway_sequence');
				$json['msg']['fixed_day_error'] = form_error('fixed_day');
				$json['msg']['fixed_from_time_error'] = form_error('fixed_from_time');
				$json['msg']['fixed_to_time_error'] = form_error('fixed_to_time');
				$json['msg']['urgent_date_error'] = form_error('urgent_date');
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

	public function maintenance(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_VIEW) == TRUE)
		{
			$this->save_current_url('paymentgateway/maintenance');
			
			$data['page_title'] = $this->lang->line('title_payment_gateway_maintenance');
			$this->load->view('payment_gateway_maintenance_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function maintenance_listing(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
				0 => 'payment_gateway_maintenance_id',
				1 => 'payment_gateway_name',
				2 => 'payment_gateway_sequence',
				3 => 'is_maintenance',
				4 => 'is_front_end_display',
				5 => 'fixed_maintenance',
				6 => 'fixed_day',
				7 => 'fixed_from_time',
				8 => 'fixed_to_time',
				9 => 'urgent_maintenance',
				10 => 'urgent_date',
				11 => 'updated_by',
				12 => 'updated_date',
				13 => 'payment_gateway_type_code',
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
				'search_values' => array(STATUS_ACTIVE),
				'search_types' => array('equal'),
				'table' => 'payment_gateway_maintenance',
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
					$button = "";
					$row = array();
					$row[] = $post->payment_gateway_maintenance_id;
					$row[] = $this->lang->line($post->payment_gateway_name)." (".$this->lang->line(get_payment_gateway_type($post->payment_gateway_type_code)).")";
					$row[] = '<span id="uc1_' . $post->payment_gateway_maintenance_id . '">' . $post->payment_gateway_sequence . '</span>';
					
					switch($post->is_maintenance)
					{
						case STATUS_YES: $row[] = '<span class="badge bg-success" id="uc2_' . $post->payment_gateway_maintenance_id . '">' . $this->lang->line('status_yes') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc2_' . $post->payment_gateway_maintenance_id . '">' . $this->lang->line('status_no') . '</span>'; break;
					}

					switch($post->is_front_end_display)
					{
						case STATUS_YES: $row[] = '<span class="badge bg-success" id="uc11_' . $post->payment_gateway_maintenance_id . '">' . $this->lang->line('status_yes') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc11_' . $post->payment_gateway_maintenance_id . '">' . $this->lang->line('status_no') . '</span>'; break;
					}

					switch($post->fixed_maintenance)
					{
						case STATUS_YES: $row[] = '<span class="badge bg-success" id="uc3_' . $post->payment_gateway_maintenance_id . '">' . $this->lang->line('status_yes') . '</span><br />'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->payment_gateway_maintenance_id . '">' . $this->lang->line('status_no') . '</span><br />'; break;
					}
					
					$row[] = '<span id="uc4_' . $post->payment_gateway_maintenance_id . '">' . (($post->fixed_day > 0) ? $this->lang->line(get_day($post->fixed_day)) : '-') . '</span><br />';
					$row[] = '<span id="uc5_' . $post->payment_gateway_maintenance_id . '">' . (( ! empty($post->fixed_from_time)) ? $post->fixed_from_time : '-') . '</span><br />';
					$row[] = '<span id="uc6_' . $post->payment_gateway_maintenance_id . '">' . (( ! empty($post->fixed_to_time)) ? $post->fixed_to_time : '-') . '</span>';
					
					switch($post->urgent_maintenance)
					{
						case STATUS_YES: $row[] = '<span class="badge bg-success" id="uc7_' . $post->payment_gateway_maintenance_id . '">' . $this->lang->line('status_yes') . '</span><br />'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc7_' . $post->payment_gateway_maintenance_id . '">' . $this->lang->line('status_no') . '</span><br />'; break;
					}
					
					$row[] = '<span id="uc8_' . $post->payment_gateway_maintenance_id . '">' . (($post->urgent_date > 0) ? date('Y-m-d H:i:s', $post->urgent_date) : '-') . '</span>';
					$row[] = '<span id="uc9_' . $post->payment_gateway_maintenance_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc10_' . $post->payment_gateway_maintenance_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					
					if(permission_validation(PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_UPDATE) == TRUE)
					{
						$button = '<i onclick="updateData(' . $post->payment_gateway_maintenance_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i>&nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->payment_gateway_maintenance_id . ')" type="button" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>&nbsp;&nbsp; ';
					}

					if(permission_validation(PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_UPDATE) == TRUE || permission_validation(PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_DELETE) == TRUE){
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

	public function maintenance_add(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_ADD) == TRUE)
		{
			$data['payment_gateway'] = $this->payment_gateway_model->get_payment_gateway_list();
			$this->load->view('payment_gateway_maintenance_add',$data);
		}
		else
		{
			redirect('home');
		}
	}

	public function maintenance_submit(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'payment_gateway_sequence_error' => '',
					'fixed_day_error' => '',
					'fixed_from_time_error' => '',
					'fixed_to_time_error' => '',
					'urgent_date_error' => '',
					'game_code_error' => '',
					'general_error' => ''
				),
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			//Set form rules
			$config = array(
				array(
						'field' => 'payment_gateway_id',
						'label' => strtolower($this->lang->line('label_name')),
						'rules' => 'trim|required',
						'errors' => array(
								'required' => $this->lang->line('error_select_payment_gateway_name'),
						)
				),
				array(
						'field' => 'payment_gateway_sequence',
						'label' => strtolower($this->lang->line('label_sequence')),
						'rules' => 'trim|required|integer',
						'errors' => array(
											'required' => $this->lang->line('error_only_digits_allowed'),
											'integer' => $this->lang->line('error_only_digits_allowed')
									)
				),
				array(
						'field' => 'fixed_day',
						'label' => strtolower($this->lang->line('label_day')),
						'rules' => 'trim|integer',
						'errors' => array(
											'integer' => $this->lang->line('error_select_fixed_day')
									)
				),
				array(
						'field' => 'fixed_from_time',
						'label' => strtolower($this->lang->line('label_from_time')),
						'rules' => 'trim|callback_full_time_check',
						'errors' => array(
								'full_time_check' => $this->lang->line('error_invalid_time_format')
						)
				),
				array(
						'field' => 'fixed_to_time',
						'label' => strtolower($this->lang->line('label_to_time')),
						'rules' => 'trim|callback_full_time_check',
						'errors' => array(
								'full_time_check' => $this->lang->line('error_invalid_time_format')
						)
				),
				array(
						'field' => 'urgent_date',
						'label' => strtolower($this->lang->line('label_date')),
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
				$pgData = $this->payment_gateway_model->get_payment_gateway_data($this->input->post('payment_gateway_id', TRUE));
				if(!empty($pgData)){
					$newData = $this->payment_gateway_model->add_payment_gateway_maintenance($pgData);
				
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_PAYMENT_GATEWAY_MAINTENANCE_ADD, $newData);
					}
					else
					{
						$this->account_model->insert_log(LOG_PAYMENT_GATEWAY_MAINTENANCE_ADD, $newData);
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
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');
				}
			}
			else 
			{
				$json['msg']['payment_gateway_sequence_error'] = form_error('payment_gateway_sequence');
				$json['msg']['fixed_day_error'] = form_error('fixed_day');
				$json['msg']['fixed_from_time_error'] = form_error('fixed_from_time');
				$json['msg']['fixed_to_time_error'] = form_error('fixed_to_time');
				$json['msg']['urgent_date_error'] = form_error('urgent_date');
				$json['msg']['game_code_error'] = form_error('game_code');
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

	public function maintenance_edit($id = NULL){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_UPDATE) == TRUE)
		{
			$data = $this->payment_gateway_model->get_payment_gateway_maintenance_data($id);
			$data['payment_gateway'] = $this->payment_gateway_model->get_payment_gateway_list();
			$this->load->view('payment_gateway_maintenance_update', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function maintenance_update()
	{
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'payment_gateway_sequence_error' => '',
										'fixed_day_error' => '',
										'fixed_from_time_error' => '',
										'fixed_to_time_error' => '',
										'urgent_date_error' => '',
										'game_code_error' => '',
										'general_error' => ''
									), 
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			
			//Set form rules
			$config = array(
				array(
						'field' => 'payment_gateway_id',
						'label' => strtolower($this->lang->line('label_name')),
						'rules' => 'trim|required',
						'errors' => array(
								'required' => $this->lang->line('error_select_payment_gateway_name'),
						)
				),
				array(
						'field' => 'payment_gateway_sequence',
						'label' => strtolower($this->lang->line('label_sequence')),
						'rules' => 'trim|required|integer',
						'errors' => array(
											'required' => $this->lang->line('error_only_digits_allowed'),
											'integer' => $this->lang->line('error_only_digits_allowed')
									)
				),
				array(
						'field' => 'fixed_day',
						'label' => strtolower($this->lang->line('label_day')),
						'rules' => 'trim|integer',
						'errors' => array(
											'integer' => $this->lang->line('error_select_fixed_day')
									)
				),
				array(
						'field' => 'fixed_from_time',
						'label' => strtolower($this->lang->line('label_from_time')),
						'rules' => 'trim|callback_full_time_check',
						'errors' => array(
								'time_check' => $this->lang->line('error_invalid_time_format')
						)
				),
				array(
						'field' => 'fixed_to_time',
						'label' => strtolower($this->lang->line('label_to_time')),
						'rules' => 'trim|callback_full_time_check',
						'errors' => array(
								'time_check' => $this->lang->line('error_invalid_time_format')
						)
				),
				array(
						'field' => 'urgent_date',
						'label' => strtolower($this->lang->line('label_date')),
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
				$payment_gateway_maintenance_id = trim($this->input->post('payment_gateway_maintenance_id', TRUE));
				$oldData = $this->payment_gateway_model->get_payment_gateway_maintenance_data($payment_gateway_maintenance_id);
				$pgData = $this->payment_gateway_model->get_payment_gateway_data($this->input->post('payment_gateway_id', TRUE));
				if( ! empty($oldData) &&  ! empty($pgData))
				{
					//Database update
					$this->db->trans_start();
					$newData = $this->payment_gateway_model->update_payment_gateway_maintenance($payment_gateway_maintenance_id,$pgData);
					
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_PAYMENT_GATEWAY_MAINTENANCE_UPDATE, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_PAYMENT_GATEWAY_MAINTENANCE_UPDATE, $newData, $oldData);
					}
					
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_updated');
						
						//Prepare for ajax update
						$json['response'] = array(
							'id' => $newData['payment_gateway_maintenance_id'],
							'payment_gateway_code' => $newData['payment_gateway_code'],
							'payment_gateway_name' => $this->lang->line($newData['payment_gateway_name'])." (".$this->lang->line(get_payment_gateway_type($newData['payment_gateway_type_code'])).")",
							'payment_gateway_sequence' => $newData['payment_gateway_sequence'],
							'is_maintenance' => (($newData['is_maintenance'] == STATUS_YES) ? $this->lang->line('status_yes') : $this->lang->line('status_no')),
							'is_front_end_display' => (($newData['is_front_end_display'] == STATUS_YES) ? $this->lang->line('status_yes') : $this->lang->line('status_no')),
							'is_maintenance_code' => $newData['is_maintenance'],
							'is_front_end_display_code' => $newData['is_front_end_display'],
							'fixed_maintenance' => (($newData['fixed_maintenance'] == STATUS_YES) ? $this->lang->line('status_yes') : $this->lang->line('status_no')),
							'fixed_maintenance_code' => $newData['fixed_maintenance'],
							'fixed_day' => $this->lang->line(get_day($newData['fixed_day'])),
							'fixed_from_time' => $newData['fixed_from_time'],
							'fixed_to_time' => $newData['fixed_to_time'],
							'urgent_maintenance' => (($newData['urgent_maintenance'] == STATUS_YES) ? $this->lang->line('status_yes') : $this->lang->line('status_no')),
							'urgent_maintenance_code' => $newData['urgent_maintenance'],
							'urgent_date' => date('Y-m-d H:i', $newData['urgent_date']),
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
				$json['msg']['payment_gateway_sequence_error'] = form_error('payment_gateway_sequence');
				$json['msg']['fixed_day_error'] = form_error('fixed_day');
				$json['msg']['fixed_from_time_error'] = form_error('fixed_from_time');
				$json['msg']['fixed_to_time_error'] = form_error('fixed_to_time');
				$json['msg']['urgent_date_error'] = form_error('urgent_date');
				$json['msg']['game_code_error'] = form_error('game_code');
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

	public function maintenance_delete(){
		$json = array(
					'status' => EXIT_ERROR, 
					'msg' => ''
				);
					
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_DELETE) == TRUE)
		{
			$payment_gateway_maintenance_id = $this->uri->segment(3);
			$oldData = $this->payment_gateway_model->get_payment_gateway_maintenance_data($payment_gateway_maintenance_id);
			
			if( ! empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->payment_gateway_model->delete_payment_gateway_maintenance($payment_gateway_maintenance_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_PAYMENT_GATEWAY_MAINTENANCE_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_PAYMENT_GATEWAY_MAINTENANCE_DELETE, $oldData);
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

	public function get_all_payment_gateway_data(){
		$json = array(
			'status' => EXIT_ERROR, 
			'msg' => '',
			'total_data' => '',
			'response' => array(),
		);

		$payment_gateway_list = array();
		
		$result = $this->payment_gateway_model->get_payment_gateway_list();
		if(!empty($result)){
			$json['status'] = EXIT_SUCCESS;
			$json['total_data'] = sizeof($result);
			if(sizeof($result) > 0){
				foreach($result as $result_row){
					if(!isset($payment_gateway_list[$result_row['payment_gateway_code']])){
						$payment_gateway_list[$result_row['payment_gateway_code']] = array(
							'payment_gateway_ids' => $result_row['payment_gateway_id'],
							'payment_gateway_code' => $result_row['payment_gateway_code'],
							'payment_gateway_name' => $this->lang->line($result_row['payment_gateway_name']),
						);
					}else{
						$payment_gateway_list[$result_row['payment_gateway_code']]['payment_gateway_ids'] = $payment_gateway_list[$result_row['payment_gateway_code']]['payment_gateway_ids'].",".$result_row['payment_gateway_id'];
					}
				}
				$json['response'] = array_values($payment_gateway_list);
				$json['total_data'] = sizeof($payment_gateway_list);
			}
		}
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
				
		exit();
	}

	public function limited(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_LIMITED_VIEW) == TRUE)
		{
			$this->save_current_url('paymentgateway/limited');
			$data['page_title'] = $this->lang->line('title_payment_gateway_limited');

			$this->session->unset_userdata('search_payment_gateway_limited');
			$data_search = array(
				'payment_gateway_code' => "",
				'payment_gateway_type_code' => "",
				'status' => "-1",
			);
			$data['data_search'] = $data_search;
			$this->session->set_userdata('search_payment_gateway_limited', $data_search);
			$this->load->view('payment_gateway_limited_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function limited_search(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_LIMITED_VIEW) == TRUE)
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
				'payment_gateway_code' => trim($this->input->post('payment_gateway_code', TRUE)),
			    'payment_gateway_type_code' => trim($this->input->post('payment_gateway_type_code', TRUE)),
				'status' => trim($this->input->post('status', TRUE)),
			);
			
			$this->session->set_userdata('search_payment_gateway_limited', $data);
			
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

	public function limited_listing(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_LIMITED_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
				0 => 'payment_gateway_limited_id',
				1 => 'payment_gateway_code',
				2 => 'payment_gateway_type_code',
				3 => 'payment_gateway_sequence',
				4 => 'payment_gateway_daily_limit',
				5 => 'payment_gateway_current_usage',
				6 => 'active',
				7 => 'updated_by',
				8 => 'updated_date',
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
			
			$arr = $this->session->userdata('search_payment_gateway_limited');	
			$where = '';		
				
			if( ! empty($arr['payment_gateway_code']))
			{
				$where .= " AND payment_gateway_code = '" . $arr['payment_gateway_code'] . "'";
			}
			
			if( ! empty($arr['payment_gateway_type_code']))
			{
				$where .= " AND payment_gateway_type_code = '" . $arr['payment_gateway_type_code']."'";	
			}

			if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_INACTIVE)
			{
				$where .= ' AND active = ' . $arr['status'];
			}
			
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;

			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}payment_gateway_limited WHERE payment_gateway_limited_id != 0 $where)";
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

					$row[] = $post->payment_gateway_limited_id;
					$row[] = '<span id="uc1_' . $post->payment_gateway_limited_id . '">' . $this->lang->line(get_payment_gateway_code($post->payment_gateway_code)) . '</span>';
					if(empty($post->payment_gateway_type_code)){
						$row[] = '<span id="uc3_' . $post->payment_gateway_limited_id . '">' . $this->lang->line('label_all') . '</span>';
					}else{
						$row[] = '<span id="uc3_' . $post->payment_gateway_limited_id . '">' . $this->lang->line(get_deposit_type($post->payment_gateway_type_code)) . '</span>';
					}
					$row[] = '<span id="uc4_' . $post->payment_gateway_limited_id . '">' . $post->payment_gateway_sequence . '</span>';
					$row[] = '<span id="uc5_' . $post->payment_gateway_limited_id . '">' . $post->payment_gateway_daily_limit . '</span>';
					$row[] = '<span id="uc6_' . $post->payment_gateway_limited_id . '" class="'.$post->payment_gateway_code.'">' . $post->payment_gateway_current_usage . '</span>';
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc2_' . $post->payment_gateway_limited_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc2_' . $post->payment_gateway_limited_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					
					$row[] = '<span id="uc7_' . $post->payment_gateway_limited_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc8_' . $post->payment_gateway_limited_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					
					$button = '';
					if(permission_validation(PERMISSION_PAYMENT_GATEWAY_LIMITED_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->payment_gateway_limited_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_PAYMENT_GATEWAY_LIMITED_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->payment_gateway_limited_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
					}
					
					if(permission_validation(PERMISSION_PAYMENT_GATEWAY_LIMITED_UPDATE) == TRUE || permission_validation(PERMISSION_PAYMENT_GATEWAY_LIMITED_DELETE) == TRUE)
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

	public function limited_add(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_LIMITED_ADD) == TRUE)
		{
			$this->load->view('payment_gateway_limited_add');
		}
		else
		{
			redirect('home');
		}
	}

	public function limited_submit(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_LIMITED_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'payment_gateway_sequence_error' => '',
					'payment_gateway_daily_limit_error' => '',
					'payment_gateway_code_error' => '',
					'general_error' => ''
				),
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			//Set form rules
			$config = array(
				array(
					'field' => 'payment_gateway_sequence',
					'label' => strtolower($this->lang->line('label_sequence')),
					'rules' => 'trim|required|integer',
					'errors' => array(
						'required' => $this->lang->line('error_only_digits_allowed'),
						'integer' => $this->lang->line('error_only_digits_allowed')
					)
				),
				array(
					'field' => 'payment_gateway_daily_limit',
					'label' => strtolower($this->lang->line('label_daily_limit')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_enter_daily_limit'),
					)
				),
				array(
						'field' => 'payment_gateway_code',
						'label' => strtolower($this->lang->line('label_name')),
						'rules' => 'trim|required',
						'errors' => array(
								'required' => $this->lang->line('error_select_payment_gateway_name'),
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
				$newData = $this->payment_gateway_model->add_payment_gateway_limited();
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_PAYMENT_GATEWAY_LIMITED_ADD, $newData);
				}
				else
				{
					$this->account_model->insert_log(LOG_PAYMENT_GATEWAY_LIMITED_ADD, $newData);
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
				$json['msg']['payment_gateway_sequence_error'] = form_error('payment_gateway_sequence');
				$json['msg']['payment_gateway_daily_limit_error'] = form_error('payment_gateway_daily_limit');
				$json['msg']['payment_gateway_code_error'] = form_error('payment_gateway_code');
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

	public function limited_edit($id = NULL){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_LIMITED_UPDATE) == TRUE)
		{
			$data = $this->payment_gateway_model->get_payment_gateway_limited_data($id);
			if(!empty($data)){
				$this->load->view('payment_gateway_limited_update', $data);
			}else{
				redirect('home');
			}
		}
		else
		{
			redirect('home');
		}
	}

	public function limited_update(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_LIMITED_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'payment_gateway_sequence_error' => '',
					'payment_gateway_daily_limit_error' => '',
					'payment_gateway_code_error' => '',
					'general_error' => ''
				),
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			//Set form rules
			$config = array(
				array(
					'field' => 'payment_gateway_sequence',
					'label' => strtolower($this->lang->line('label_sequence')),
					'rules' => 'trim|required|integer',
					'errors' => array(
						'required' => $this->lang->line('error_only_digits_allowed'),
						'integer' => $this->lang->line('error_only_digits_allowed')
					)
				),
				array(
					'field' => 'payment_gateway_daily_limit',
					'label' => strtolower($this->lang->line('label_daily_limit')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_enter_daily_limit'),
					)
				),
			);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$payment_gateway_limited_id = trim($this->input->post('payment_gateway_limited_id', TRUE));
				$oldData = $this->payment_gateway_model->get_payment_gateway_limited_data($payment_gateway_limited_id);
				if( ! empty($oldData))
				{
					//Database update
					$this->db->trans_start();
					$newData = $this->payment_gateway_model->update_payment_gateway_limited($payment_gateway_limited_id,$oldData);
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_PAYMENT_GATEWAY_LIMITED_UPDATE, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_PAYMENT_GATEWAY_LIMITED_UPDATE, $newData, $oldData);
					}

					if($this->input->post('is_reset_daily_limit_same_payment_gateway', TRUE) == STATUS_YES){
						$this->payment_gateway_model->reset_payment_gateway_daily_limit($oldData['payment_gateway_code']);
					}
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_updated');

						$json['response'] = array(
							'id' => $newData['payment_gateway_limited_id'],
							'payment_gateway_code'			=> $newData['payment_gateway_code'],
							'payment_gateway_name' 			=> $this->lang->line(get_payment_gateway_code($newData['payment_gateway_code'])),
							'payment_gateway_type_code' 	=> $newData['payment_gateway_type_code'],
							'payment_gateway_type_name' 	=> ((empty($newData['payment_gateway_type_code'])) ? $this->lang->line('label_all') : $this->lang->line(get_deposit_type($newData['payment_gateway_type_code']))),
							'payment_gateway_sequence' 		=> $newData['payment_gateway_sequence'],
							'payment_gateway_daily_limit' 	=> $newData['payment_gateway_daily_limit'],
							'payment_gateway_current_usage' => $newData['payment_gateway_current_usage'],
							'is_reset_daily_limit' 			=> $newData['is_reset_daily_limit'],
							'is_reset_daily_limit_same_payment_gateway'		=> $newData['is_reset_daily_limit_same_payment_gateway'],
							'active' 						=> (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
							'active_code' 					=> $newData['active'],
							'updated_by' 					=> $newData['updated_by'],
							'updated_date' 					=> date('Y-m-d H:i:s', $newData['updated_date']),
						);
					}else{
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
				$json['msg']['payment_gateway_sequence_error'] = form_error('payment_gateway_sequence');
				$json['msg']['payment_gateway_daily_limit_error'] = form_error('payment_gateway_daily_limit');
				$json['msg']['payment_gateway_code_error'] = form_error('payment_gateway_code');
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

	public function limited_delete(){
		$json = array(
					'status' => EXIT_ERROR, 
					'msg' => ''
				);
					
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_LIMITED_DELETE) == TRUE)
		{
			$payment_gateway_limited_id = $this->uri->segment(3);
			$oldData = $this->payment_gateway_model->get_payment_gateway_limited_data($payment_gateway_limited_id);
			
			if( ! empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->payment_gateway_model->delete_payment_gateway_limited($payment_gateway_limited_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_PAYMENT_GATEWAY_LIMITED_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_PAYMENT_GATEWAY_LIMITED_DELETE, $oldData);
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

	public function player_amount(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_VIEW) == TRUE)
		{
			$this->save_current_url('paymentgateway/player_limited');
			$data['page_title'] = $this->lang->line('title_payment_gateway_player_amount');

			$this->session->unset_userdata('search_payment_gateway_player_amount');
			$data_search = array(
				'payment_gateway_code' => "",
				'payment_gateway_type_code' => "",
				'username' => "",
				'status' => "-1",
			);
			$data['data_search'] = $data_search;
			$this->session->set_userdata('search_payment_gateway_player_amount', $data_search);
			$this->load->view('payment_gateway_player_amount_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function player_amount_search(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_VIEW) == TRUE)
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
				'payment_gateway_code' => trim($this->input->post('payment_gateway_code', TRUE)),
			    'payment_gateway_type_code' => trim($this->input->post('payment_gateway_type_code', TRUE)),
			    'username' => trim($this->input->post('username', TRUE)),
				'status' => trim($this->input->post('status', TRUE)),
			);
			
			$this->session->set_userdata('search_payment_gateway_player_amount', $data);
			
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

	public function player_amount_listing(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
				0 => 'payment_gateway_player_amount_id',
				1 => 'payment_gateway_code',
				2 => 'payment_gateway_type_code',
				3 => 'username',
				4 => 'min_amount',
				5 => 'max_amount',
				6 => 'active',
				7 => 'updated_by',
				8 => 'updated_date',
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
			
			$arr = $this->session->userdata('search_payment_gateway_player_amount');	
			$where = '';		
				
			if( ! empty($arr['payment_gateway_code']))
			{
				$where .= " AND payment_gateway_code = '" . $arr['payment_gateway_code'] . "'";
			}
			
			if( ! empty($arr['payment_gateway_type_code']))
			{
				$where .= " AND payment_gateway_type_code = '" . $arr['payment_gateway_type_code']."'";	
			}

			if( ! empty($arr['username']))
			{
				$where .= " AND username = '" . $arr['username']."'";	
			}

			if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_INACTIVE)
			{
				$where .= ' AND active = ' . $arr['status'];
			}
			
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;

			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}payment_gateway_player_amount WHERE payment_gateway_player_amount_id != 0 $where)";
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
					$row[] = $post->payment_gateway_player_amount_id;
					$row[] = '<span id="uc1_' . $post->payment_gateway_player_amount_id . '">' . $this->lang->line(get_payment_gateway_code($post->payment_gateway_code)) . '</span>';
					if(empty($post->payment_gateway_type_code)){
						$row[] = '<span id="uc3_' . $post->payment_gateway_player_amount_id . '">' . $this->lang->line('label_all') . '</span>';
					}else{
						$row[] = '<span id="uc3_' . $post->payment_gateway_player_amount_id . '">' . $this->lang->line(get_deposit_type($post->payment_gateway_type_code)) . '</span>';
					}

					if(empty($post->username)){
						$row[] = '<span id="uc4_' . $post->payment_gateway_player_amount_id . '">' . $this->lang->line('label_all') . '</span>';
					}else{
						$row[] = '<span id="uc4_' . $post->payment_gateway_player_amount_id . '">' . $post->username . '</span>';
					}

					$row[] = '<span id="uc5_' . $post->payment_gateway_player_amount_id . '">' . $post->min_amount . '</span>';
					$row[] = '<span id="uc6_' . $post->payment_gateway_player_amount_id . '">' . $post->max_amount . '</span>';
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc2_' . $post->payment_gateway_player_amount_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc2_' . $post->payment_gateway_player_amount_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					
					$row[] = '<span id="uc7_' . $post->payment_gateway_player_amount_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc8_' . $post->payment_gateway_player_amount_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					
					$button = '';
					if(permission_validation(PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->payment_gateway_player_amount_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->payment_gateway_player_amount_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
					}
					
					if(permission_validation(PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_UPDATE) == TRUE || permission_validation(PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_DELETE) == TRUE)
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

	public function player_amount_add(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_ADD) == TRUE)
		{
			$this->load->view('payment_gateway_player_amount_add');
		}
		else
		{
			redirect('home');
		}
	}

	public function player_amount_submit(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'payment_gateway_code_error' => '',
					'min_amount_error' => '',
					'max_amount_error' => '',
					'general_error' => ''
				),
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			//Set form rules
			$config = array(
				array(
						'field' => 'payment_gateway_code',
						'label' => strtolower($this->lang->line('label_name')),
						'rules' => 'trim|required',
						'errors' => array(
								'required' => $this->lang->line('error_select_payment_gateway_name'),
						)
				),
				array(
					'field' => 'min_amount',
					'label' => strtolower($this->lang->line('label_min_amounts')),
					'rules' => 'trim|greater_than_equal_to[0]|less_than_equal_to[' . $this->input->post('max_amount', TRUE) . ']',
					'errors' => array(
						'greater_than_equal_to' => $this->lang->line('error_greater_than_or_equal'),
						'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
					)
				),
				array(
					'field' => 'max_amount',
					'label' => strtolower($this->lang->line('label_max_amounts')),
					'rules' => 'trim|greater_than_equal_to[' . $this->input->post('min_amount', TRUE) . ']',
					'errors' => array(
						'greater_than_equal_to' => $this->lang->line('error_greater_than_or_equal')
					)
				)
			);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$is_allow_add = TRUE;
				$playerData = array();
				$username = trim($this->input->post('username', TRUE));
				if(!empty($username)){
					$playerData = $this->player_model->get_player_data_by_username($username);
					if(empty($playerData)){
						$is_allow_add = FALSE;
					}
				}
				
				if($is_allow_add == TRUE){
					//Database update
					$this->db->trans_start();
					$newData = $this->payment_gateway_model->add_payment_gateway_player_amount($playerData);
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_PAYMENT_GATEWAY_PLAYER_LIMITED_ADD, $newData);
					}
					else
					{
						$this->account_model->insert_log(LOG_PAYMENT_GATEWAY_PLAYER_LIMITED_ADD, $newData);
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
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');
				}
			}
			else 
			{
				$json['msg']['min_amount_error'] = form_error('min_amount');
				$json['msg']['max_amount_error'] = form_error('max_amount');
				$json['msg']['payment_gateway_code_error'] = form_error('payment_gateway_code');
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

	public function player_amount_edit($id = NULL){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_UPDATE) == TRUE)
		{
			$data = $this->payment_gateway_model->get_payment_gateway_player_amount_data($id);
			if(!empty($data)){
				$this->load->view('payment_gateway_player_amount_update', $data);
			}else{
				redirect('home');
			}
		}
		else
		{
			redirect('home');
		}
	}

	public function player_amount_update(){
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'payment_gateway_code_error' => '',
					'min_amount_error' => '',
					'max_amount_error' => '',
					'general_error' => ''
				),
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			//Set form rules
			$config = array(
				array(
					'field' => 'min_amount',
					'label' => strtolower($this->lang->line('label_min_amounts')),
					'rules' => 'trim|greater_than_equal_to[0]|less_than_equal_to[' . $this->input->post('max_amount', TRUE) . ']',
					'errors' => array(
						'greater_than_equal_to' => $this->lang->line('error_greater_than_or_equal'),
						'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
					)
				),
				array(
					'field' => 'max_amount',
					'label' => strtolower($this->lang->line('label_max_amounts')),
					'rules' => 'trim|greater_than_equal_to[' . $this->input->post('min_amount', TRUE) . ']',
					'errors' => array(
						'greater_than_equal_to' => $this->lang->line('error_greater_than_or_equal')
					)
				)
			);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$payment_gateway_player_amount_id = trim($this->input->post('payment_gateway_player_amount_id', TRUE));
				$oldData = $this->payment_gateway_model->get_payment_gateway_player_amount_data($payment_gateway_player_amount_id);
				if( ! empty($oldData))
				{
					$is_allow_update = TRUE;
					$playerData = array();
					$username = trim($this->input->post('username', TRUE));
					if(!empty($username)){
						$playerData = $this->player_model->get_player_data_by_username($username);
						if(empty($playerData)){
							$is_allow_update = FALSE;
						}
					}
					
					if($is_allow_update == TRUE){
						//Database update
						$this->db->trans_start();
						$newData = $this->payment_gateway_model->update_payment_gateway_player_amount($payment_gateway_player_amount_id,$oldData,$playerData);
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_PAYMENT_GATEWAY_LIMITED_UPDATE, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_PAYMENT_GATEWAY_LIMITED_UPDATE, $newData, $oldData);
						}
						
						$this->db->trans_complete();
						
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');

							$json['response'] = array(
								'id' => $newData['payment_gateway_player_amount_id'],
								'payment_gateway_code'			=> $newData['payment_gateway_code'],
								'payment_gateway_name' 			=> $this->lang->line(get_payment_gateway_code($newData['payment_gateway_code'])),
								'payment_gateway_type_code' 	=> $newData['payment_gateway_type_code'],
								'payment_gateway_type_name' 	=> ((empty($newData['payment_gateway_type_code'])) ? $this->lang->line('label_all') : $this->lang->line(get_deposit_type($newData['payment_gateway_type_code']))),
								'username'						=> (isset($newData['username']) ? $newData['username'] : $this->lang->line('label_all')),
								'min_amount' 					=> $newData['min_amount'],
								'max_amount' 					=> $newData['max_amount'],
								'active' 						=> (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
								'active_code' 					=> $newData['active'],
								'updated_by' 					=> $newData['updated_by'],
								'updated_date' 					=> date('Y-m-d H:i:s', $newData['updated_date']),
							);
						}
						else
						{
							$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
						}
					}else{
						$json['msg'] = $this->lang->line('error_failed_to_update');
					}
				}else{
					$json['msg'] = $this->lang->line('error_failed_to_update');
				}
			}
			else 
			{
				$json['msg']['min_amount_error'] = form_error('min_amount');
				$json['msg']['max_amount_error'] = form_error('max_amount');
				$json['msg']['payment_gateway_code_error'] = form_error('payment_gateway_code');
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

	public function player_amount_delete(){
		$json = array(
			'status' => EXIT_ERROR, 
			'msg' => ''
		);
					
		if(permission_validation(PERMISSION_PAYMENT_GATEWAY_LIMITED_DELETE) == TRUE)
		{
			$payment_gateway_player_amount_id = $this->uri->segment(3);
			$oldData = $this->payment_gateway_model->get_payment_gateway_player_amount_data($payment_gateway_player_amount_id);
			
			if( ! empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->payment_gateway_model->delete_payment_gateway_player_amount($payment_gateway_player_amount_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_PAYMENT_GATEWAY_PLAYER_LIMITED_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_PAYMENT_GATEWAY_PLAYER_LIMITED_DELETE, $oldData);
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