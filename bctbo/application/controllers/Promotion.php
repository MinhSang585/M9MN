<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promotion extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('promotion_model','game_model'));
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index(){
		if(permission_validation(PERMISSION_PROMOTION_VIEW) == TRUE)
		{
			$this->save_current_url('promotion');
			
			$data['page_title'] = $this->lang->line('title_promotion');
			$this->load->view('promotion_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function listing(){
		if(permission_validation(PERMISSION_PROMOTION_VIEW) == TRUE){
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);

			//Table Columns
			$columns = array(
				0 => 'promotion_id',
				1 => 'promotion_name',
				2 => 'genre_code',
				3 => 'genre_name',
				4 => 'start_date',
				5 => 'end_date',
				6 => 'active',
				7 => 'promotion_seq',
				8 => 'updated_by',
				9 => 'updated_date',
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
					'table' => 'promotion',
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
					$row[] = $post->promotion_id;
					$row[] = '<span id="uc1_' . $post->promotion_id . '">' . $post->promotion_name . '</span>';
					$row[] = $post->genre_code;
					$row[] = $this->lang->line($post->genre_name);
					$row[] = '<span id="uc2_' . $post->promotion_id . '">' . date('Y-m-d H:i:s', $post->start_date) . '</span>';;
					$row[] = '<span id="uc3_' . $post->promotion_id . '">' . (($post->end_date > 0)? date('Y-m-d H:i:s', $post->end_date) : "-") . '</span>';;
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc4_' . $post->promotion_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc4_' . $post->promotion_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					$row[] = '<span id="uc5_' . $post->promotion_id . '">' . (( ! empty($post->promotion_seq)) ? $post->promotion_seq : '0') . '</span>';
					$row[] = '<span id="uc6_' . $post->promotion_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc7_' . $post->promotion_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';

					$button = '';
					if(permission_validation(PERMISSION_PROMOTION_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->promotion_id . ','."'".$post->genre_code."'".')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_update')  . '"></i> &nbsp;&nbsp; ';
					}

					if(permission_validation(PERMISSION_PROMOTION_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateContent(' . $post->promotion_id . ')" class="fab fa-elementor nav-icon text-info" title="' . $this->lang->line('button_content')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_PROMOTION_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->promotion_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
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

	public function add($id = NULL){
		if(permission_validation(PERMISSION_PROMOTION_ADD) == TRUE)
		{
			$data['promotion_genre_list'] = $this->promotion_model->get_promotion_genre_list();
			$data['game_provider_list'] = $this->game_model->get_game_list();
			$this->load->view('promotion_add',$data);
		}
		else
		{
			redirect('home');
		}
	}

	public function submit(){
		if(permission_validation(PERMISSION_PROMOTION_ADD) == TRUE)
		{
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'promotion_name_error' => '',
					'genre_code_error' => '',
					'promotion_seq_error' => '',
					'level_error' => '',
					'date_type_error' => '',
					'times_limit_type_error' => '',
					'calculate_type_error' => '',
					'bonus_range_type_error' => '',
					'bonus_type_error' => '',
					'start_date_error' => '',
					'end_date_error' => '',
					'specific_day_week_error' => '',
					'specific_day_day_error' => '',
					'turnover_multiply_error' => '',
					'rebate_percentage_error' => '',
					'max_rebate_error' => '',
					'rebate_amount_error' => '',
					'min_deposit_error' => '',
					'max_deposit_error' => '',
					'general_error' => ''
				), 	
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);

			$genre_code = trim($this->input->post('genre_code', TRUE));
			$promotion_genre_data = $this->promotion_model->get_promotion_genre_data($genre_code);
			if(!empty($promotion_genre_data)){
				//General Verification
				$config = array(
					array(
						'field' => 'promotion_name',
						'label' => strtolower($this->lang->line('label_promotion_name')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_enter_promotion_name'),
						)
					),
					array(
						'field' => 'promotion_seq',
						'label' => strtolower($this->lang->line('label_sequence')),
						'rules' => 'trim|required|integer',
						'errors' => array(
							'required' => $this->lang->line('error_only_digits_allowed'),
							'integer' => $this->lang->line('error_only_digits_allowed')
						)
					),
					array(
						'field' => 'level',
						'label' => strtolower($this->lang->line('label_promotion_level')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_select_promotion_level'),
						)
					),
					array(
						'field' => 'date_type',
						'label' => strtolower($this->lang->line('label_date_type')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_select_date_type'),
						)
					),
				);

				if($genre_code == "DE" || $genre_code == "FD" || $genre_code == "SD" || $genre_code == "DPR" || $genre_code == "CR"  || $genre_code == "CRLV" || $genre_code == "RF"){
					$configAdd = array(
						'field' => 'bonus_range_type',
						'label' => strtolower($this->lang->line('label_range_type')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_select_range_type'),
						)
					);
					array_push($config, $configAdd);

					$configAdd = array(
						'field' => 'times_limit_type',
						'label' => strtolower($this->lang->line('label_times_limit_type')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_select_times_limit_type'),
						)
					);
					array_push($config, $configAdd);

					$configAdd = array(
						'field' => 'calculate_type',
						'label' => strtolower($this->lang->line('label_calculate_type')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_select_calculate_type'),
						)
					);
					array_push($config, $configAdd);
				}

				if($genre_code == "DE" || $genre_code == "FD" || $genre_code == "SD"){
					$configAdd = array(
						'field' => 'min_deposit',
						'label' => strtolower($this->lang->line('label_min_deposit')),
						'rules' => 'trim|greater_than[0]',
						'errors' => array(
							'greater_than' => $this->lang->line('error_greater_than'),
						)
					);
					array_push($config, $configAdd);

					$configAdd = array(
						'field' => 'max_deposit',
						'label' => strtolower($this->lang->line('label_max_deposit')),
						'rules' => 'trim|greater_than_equal_to['.$this->input->post('min_deposit').']',
						'errors' => array(
							'greater_than_equal_to' => $this->lang->line('error_greater_than'),
						)
					);
					array_push($config, $configAdd);
				}
				
				$date_type = trim($this->input->post('date_type', TRUE));
				if($date_type == PROMOTION_DATE_TYPE_START_TO_END){
					$configAdd = array(
						'field' => 'start_date',
						'label' => strtolower($this->lang->line('label_start_date')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_enter_start_date'),
						)
					);
					array_push($config, $configAdd);
					$configAdd = array(
						'field' => 'end_date',
						'label' => strtolower($this->lang->line('label_end_date')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_enter_end_date'),
						)
					);
					array_push($config, $configAdd);
				}else if($date_type == PROMOTION_DATE_TYPE_START_NO_LIMIT){
					$configAdd = array(
						'field' => 'start_date',
						'label' => strtolower($this->lang->line('label_start_date')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_enter_start_date'),
						)
					);
					array_push($config, $configAdd);
				}else if($date_type == PROMOTION_DATE_TYPE_SPECIFIC_DAY_WEEK){
					$configAdd = array(
						'field' => 'specific_day_week[]',
						'label' => strtolower($this->lang->line('promotion_date_type_specific_day_week')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_select_promotion_date_type_specific_day_week'),
						)
					);
					array_push($config, $configAdd);
				}else if($date_type == PROMOTION_DATE_TYPE_SPECIFIC_DAY_DAY){
					$configAdd = array(
						'field' => 'specific_day_day[]',
						'label' => strtolower($this->lang->line('promotion_date_type_specific_day_day')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_select_promotion_date_type_specific_day_day'),
						)
					);
					array_push($config, $configAdd);
				}
				$bonus_range_type = trim($this->input->post('bonus_range_type'));
				$bonus_type = trim($this->input->post('bonus_type'));
				if($bonus_range_type == PROMOTION_BONUS_RANGE_TYPE_GENERAL){
					$configAdd = array(
						'field' => 'turnover_multiply',
						'label' => strtolower($this->lang->line('label_rollover')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_enter_turnover_multiply'),
						)
					);
					array_push($config, $configAdd);
					if($bonus_type == PROMOTION_BONUS_TYPE_PERCENTAGE){
						$configAdd = array(
							'field' => 'rebate_percentage',
							'label' => strtolower($this->lang->line('label_rebate_percentage')),
							'rules' => 'trim|greater_than[0]',
							'errors' => array(
								'greater_than' => $this->lang->line('error_greater_than'),
							)
						);
						array_push($config, $configAdd);
						$configAdd = array(
							'field' => 'max_rebate',
							'label' => strtolower($this->lang->line('label_max_rebate')),
							'rules' => 'trim|greater_than[0]',
							'errors' => array(
								'greater_than' => $this->lang->line('error_greater_than'),
							)
						);
						array_push($config, $configAdd);
					}else if($bonus_type==PROMOTION_BONUS_TYPE_FIX_AMOUNT){
						$configAdd = array(
							'field' => 'rebate_amount',
							'label' => strtolower($this->lang->line('label_rebate_amount')),
							'rules' => 'trim|greater_than[0]',
							'errors' => array(
								'greater_than' => $this->lang->line('error_greater_than'),
							)
						);
						array_push($config, $configAdd);
					}
				}

				$this->form_validation->set_rules($config);
				$this->form_validation->set_error_delimiters('', '');
				//Form validation
				if ($this->form_validation->run() == TRUE)
				{
					$this->db->trans_start();
					$newData = $this->promotion_model->add_promotion($promotion_genre_data);
					if($bonus_range_type==PROMOTION_BONUS_RANGE_TYPE_LEVEL){
						$this->promotion_model->add_promotion_bonus_range($promotion_genre_data,$newData);
						$newDataLevel = $this->promotion_model->get_promotion_bonus_range_data($newData['promotion_id']);
						$newData['level'] = $newDataLevel;
					}
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
				}else{
					$json['msg']['promotion_name_error'] = form_error('promotion_name');
					$json['msg']['promotion_seq_error'] = form_error('promotion_seq');
					$json['msg']['level_error'] = form_error('level');
					$json['msg']['date_type_error'] = form_error('date_type');
					$json['msg']['times_limit_type_error'] = form_error('times_limit_type');
					$json['msg']['calculate_type_error'] = form_error('calculate_type');
					$json['msg']['bonus_range_type_error'] = form_error('bonus_range_type');
					$json['msg']['bonus_type_error'] = form_error('bonus_type');
					$json['msg']['start_date_error'] = form_error('start_date');
					$json['msg']['end_date_error'] = form_error('end_date');
					$json['msg']['specific_day_week_error'] = form_error('specific_day_week[]');
					$json['msg']['specific_day_day_error'] = form_error('specific_day_day[]');
					$json['msg']['turnover_multiply_error'] = form_error('turnover_multiply');
					$json['msg']['rebate_percentage_error'] = form_error('rebate_percentage');
					$json['msg']['max_rebate_error'] = form_error('max_rebate');
					$json['msg']['rebate_amount_error'] = form_error('rebate_amount');
					$json['msg']['min_deposit_error'] = form_error('min_deposit');
					$json['msg']['max_deposit_error'] = form_error('max_deposit');
				}
			}else{
				$json['msg']['genre_code_error'] = $this->lang->line('error_select_type');
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

	public function edit($id=NULL){
		if(permission_validation(PERMISSION_PROMOTION_UPDATE) == TRUE)
		{
			$data['promotion'] = $this->promotion_model->get_promotion_data_all($id);
			if(!empty($data['promotion'])){
				$data['game_provider_list'] = $this->game_model->get_game_list();
				$data['bonus_range_list'] = $this->promotion_model->get_promotion_bonus_range($id);
				$this->load->view('promotion_update',$data);
			}else{
				redirect('home');
			}
		}else{
			redirect('home');
		}
	}

	public function update(){
		if(permission_validation(PERMISSION_PROMOTION_UPDATE) == TRUE)
		{
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'promotion_name_error' => '',
					'genre_code_error' => '',
					'promotion_seq_error' => '',
					'level_error' => '',
					'date_type_error' => '',
					'times_limit_type_error' => '',
					'calculate_type_error' => '',
					'bonus_range_type_error' => '',
					'bonus_type_error' => '',
					'start_date_error' => '',
					'end_date_error' => '',
					'specific_day_week_error' => '',
					'specific_day_day_error' => '',
					'turnover_multiply_error' => '',
					'rebate_percentage_error' => '',
					'max_rebate_error' => '',
					'rebate_amount_error' => '',
					'min_deposit_error' => '',
					'max_deposit_error' => '',
					'general_error' => ''
				), 	
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);

			$genre_code = trim($this->input->post('genre_code', TRUE));
			$promotion_genre_data = $this->promotion_model->get_promotion_genre_data($genre_code);
			if(!empty($promotion_genre_data)){
				//General Verification
				$config = array(
					array(
						'field' => 'promotion_name',
						'label' => strtolower($this->lang->line('label_promotion_name')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_enter_promotion_name'),
						)
					),
					array(
						'field' => 'promotion_seq',
						'label' => strtolower($this->lang->line('label_sequence')),
						'rules' => 'trim|required|integer',
						'errors' => array(
							'required' => $this->lang->line('error_only_digits_allowed'),
							'integer' => $this->lang->line('error_only_digits_allowed')
						)
					),
					array(
						'field' => 'level',
						'label' => strtolower($this->lang->line('label_promotion_level')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_select_promotion_level'),
						)
					),
					array(
						'field' => 'date_type',
						'label' => strtolower($this->lang->line('label_date_type')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_select_date_type'),
						)
					),
				);
				if($genre_code == "DE" || $genre_code == "FD" || $genre_code == "SD" || $genre_code == "DPR" || $genre_code == "CR"  || $genre_code == "CRLV" || $genre_code == "RF"){
					$configAdd = array(
						'field' => 'times_limit_type',
						'label' => strtolower($this->lang->line('label_times_limit_type')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_select_times_limit_type'),
						)
					);
					array_push($config, $configAdd);

					$configAdd = array(
						'field' => 'calculate_type',
						'label' => strtolower($this->lang->line('label_calculate_type')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_select_calculate_type'),
						)
					);
					array_push($config, $configAdd);

					$configAdd = array(
						'field' => 'bonus_range_type',
						'label' => strtolower($this->lang->line('label_range_type')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_select_range_type'),
						)
					);
					array_push($config, $configAdd);
				}
				if($genre_code == "DE" || $genre_code == "FD" || $genre_code == "SD"){
					$configAdd = array(
						'field' => 'min_deposit',
						'label' => strtolower($this->lang->line('label_min_deposit')),
						'rules' => 'trim|greater_than_equal_to[0]',
						'errors' => array(
							'greater_than_equal_to' => $this->lang->line('error_greater_than'),
						)
					);
					array_push($config, $configAdd);

					$configAdd = array(
						'field' => 'max_deposit',
						'label' => strtolower($this->lang->line('label_max_deposit')),
						'rules' => 'trim|greater_than_equal_to['.$this->input->post('min_deposit').']',
						'errors' => array(
							'greater_than_equal_to' => $this->lang->line('error_greater_than'),
						)
					);
					array_push($config, $configAdd);
				}
				$date_type = trim($this->input->post('date_type', TRUE));
				if($date_type == PROMOTION_DATE_TYPE_START_TO_END){
					$configAdd = array(
						'field' => 'start_date',
						'label' => strtolower($this->lang->line('label_start_date')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_enter_start_date'),
						)
					);
					array_push($config, $configAdd);
					$configAdd = array(
						'field' => 'end_date',
						'label' => strtolower($this->lang->line('label_end_date')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_enter_end_date'),
						)
					);
					array_push($config, $configAdd);
				}else if($date_type == PROMOTION_DATE_TYPE_START_NO_LIMIT){
					$configAdd = array(
						'field' => 'start_date',
						'label' => strtolower($this->lang->line('label_start_date')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_enter_start_date'),
						)
					);
					array_push($config, $configAdd);
				}else if($date_type == PROMOTION_DATE_TYPE_SPECIFIC_DAY_WEEK){
					$configAdd = array(
						'field' => 'specific_day_week[]',
						'label' => strtolower($this->lang->line('promotion_date_type_specific_day_week')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_select_promotion_date_type_specific_day_week'),
						)
					);
					array_push($config, $configAdd);
				}else if($date_type == PROMOTION_DATE_TYPE_SPECIFIC_DAY_DAY){
					$configAdd = array(
						'field' => 'specific_day_day[]',
						'label' => strtolower($this->lang->line('promotion_date_type_specific_day_day')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_select_promotion_date_type_specific_day_day'),
						)
					);
					array_push($config, $configAdd);
				}
				$bonus_range_type = trim($this->input->post('bonus_range_type'));
				$bonus_type = trim($this->input->post('bonus_type'));
				if($bonus_range_type == PROMOTION_BONUS_RANGE_TYPE_GENERAL){
					$configAdd = array(
						'field' => 'turnover_multiply',
						'label' => strtolower($this->lang->line('label_rollover')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_enter_turnover_multiply'),
						)
					);
					array_push($config, $configAdd);
					if($bonus_type == PROMOTION_BONUS_TYPE_PERCENTAGE){
						$configAdd = array(
							'field' => 'rebate_percentage',
							'label' => strtolower($this->lang->line('label_rebate_percentage')),
							'rules' => 'trim|greater_than[0]',
							'errors' => array(
								'greater_than' => $this->lang->line('error_greater_than'),
							)
						);
						array_push($config, $configAdd);
						$configAdd = array(
							'field' => 'max_rebate',
							'label' => strtolower($this->lang->line('label_max_rebate')),
							'rules' => 'trim|greater_than[0]',
							'errors' => array(
								'greater_than' => $this->lang->line('error_greater_than'),
							)
						);
						array_push($config, $configAdd);
					}else if($bonus_type==PROMOTION_BONUS_TYPE_FIX_AMOUNT){
						$configAdd = array(
							'field' => 'rebate_amount',
							'label' => strtolower($this->lang->line('label_rebate_amount')),
							'rules' => 'trim|greater_than[0]',
							'errors' => array(
								'greater_than' => $this->lang->line('error_greater_than'),
							)
						);
						array_push($config, $configAdd);
					}
				}

				$this->form_validation->set_rules($config);
				$this->form_validation->set_error_delimiters('', '');
				//Form validation
				if ($this->form_validation->run() == TRUE)
				{
					$promotion_id = trim($this->input->post('promotion_id', TRUE));
					$oldData = $this->promotion_model->get_promotion_data_all($promotion_id);
					if(!empty($oldData)){
						$newData = $this->promotion_model->update_promotion($promotion_genre_data,$oldData);
						$this->promotion_model->delete_promotion_bonus_range($promotion_genre_data,$newData);
						if($bonus_range_type==PROMOTION_BONUS_RANGE_TYPE_LEVEL){
							$this->promotion_model->add_promotion_bonus_range($promotion_genre_data,$newData);
							$newDataLevel = $this->promotion_model->get_promotion_bonus_range_data($newData['promotion_id']);
							$newData['level'] = $newDataLevel;
						}
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_PROMOTION_UPDATE, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_PROMOTION_UPDATE, $newData, $oldData);
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');
							$json['response'] = array(
								'id' => $newData['promotion_id'],
								'promotion_name' => $newData['promotion_name'],
								'promotion_seq' => $newData['promotion_seq'],
								'active' => (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
								'active_code' => $newData['active'],
								'updated_by' => $newData['updated_by'],
								'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),
								'start_date' => (($newData['start_date'] !== "") ? date('Y-m-d H:i:s', $newData['start_date']) : '-'),
								'end_date' => (($newData['end_date'] !== "") ? date('Y-m-d H:i:s', $newData['end_date']) : '-'),
							);
						}else{
							$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
						}
					}else{
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
					}
				}else{
					$json['msg']['promotion_name_error'] = form_error('promotion_name');
					$json['msg']['promotion_seq_error'] = form_error('promotion_seq');
					$json['msg']['level_error'] = form_error('level');
					$json['msg']['date_type_error'] = form_error('date_type');
					$json['msg']['times_limit_type_error'] = form_error('times_limit_type');
					$json['msg']['calculate_type_error'] = form_error('calculate_type');
					$json['msg']['bonus_range_type_error'] = form_error('bonus_range_type');
					$json['msg']['bonus_type_error'] = form_error('bonus_type');
					$json['msg']['start_date_error'] = form_error('start_date');
					$json['msg']['end_date_error'] = form_error('end_date');
					$json['msg']['specific_day_week_error'] = form_error('specific_day_week[]');
					$json['msg']['specific_day_day_error'] = form_error('specific_day_day[]');
					$json['msg']['turnover_multiply_error'] = form_error('turnover_multiply');
					$json['msg']['rebate_percentage_error'] = form_error('rebate_percentage');
					$json['msg']['max_rebate_error'] = form_error('max_rebate');
					$json['msg']['rebate_amount_error'] = form_error('rebate_amount');
					$json['msg']['min_deposit_error'] = form_error('min_deposit');
					$json['msg']['max_deposit_error'] = form_error('max_deposit');
				}
			}else{
				$json['msg']['genre_code_error'] = $this->lang->line('error_select_type');
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
		if(permission_validation(PERMISSION_PROMOTION_DELETE) == TRUE)
		{
			$promotion_id = $this->uri->segment(3);
			$oldData = $this->promotion_model->get_promotion_data_all($promotion_id);
			$oldLangData = $this->promotion_model->get_promotion_lang_data($promotion_id);
			$oldBonusRangeData = $this->promotion_model->get_promotion_bonus_range_data($promotion_id);
			$oldData['lang'] = json_encode($oldLangData);
			$oldData['range'] = json_encode($oldBonusRangeData);
			if( ! empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->promotion_model->delete_promotion($promotion_id);
				$this->promotion_model->delete_promotion_bonus_range($promotion_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_PROMOTION_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_PROMOTION_DELETE, $oldData);
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

	public function tnc(){
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR, 
			'msg' => ''
		);
		if(permission_validation(PERMISSION_PROMOTION_UPDATE) == TRUE)
		{
			$promotion_id = $this->uri->segment(3);
			$data['promotion'] = $this->promotion_model->get_promotion_data_all($promotion_id);
			if(!empty($data['promotion'])){
				$data['promotion_lang'] = $this->promotion_model->get_promotion_lang_data($promotion_id);
				$this->load->view('promotion_tnc',$data);
			}else{
				redirect('home');
			}
		}
		else
		{
			redirect('home');
		}
	}

	public function tnc_update(){
		if(permission_validation(PERMISSION_PROMOTION_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'promotion_id_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);

			//Set form rules
			$config = array(
				array(
						'field' => 'promotion_id',
						'label' => strtolower($this->lang->line('label_promotion_name')),
						'rules' => 'trim|required',
						'errors' => array(
											'required' => $this->lang->line('error_enter_promotion_name')
									)
				),
			);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$promotion_id = trim($this->input->post('promotion_id', TRUE));
				$oldData = $this->promotion_model->get_promotion_data_all($promotion_id);
				$oldLangData = $this->promotion_model->get_promotion_lang_data($promotion_id);
				$oldData['lang'] = json_encode($oldLangData);

				if( ! empty($oldData))
				{
					$allow_to_update = TRUE;
					$config['upload_path'] = PROMOTION_PATH;
					$config['max_size'] = PROMOTION_FILE_SIZE;
					$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
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
							
							if(isset($_FILES['web_banner_content_'.$v]['size']) && $_FILES['web_banner_content_'.$v]['size'] > 0)
							{
								$new_name = time().rand(1000,9999).".".pathinfo($_FILES["web_banner_content_".$v]['name'], PATHINFO_EXTENSION);
								$config['file_name']  = $new_name;
								$this->upload->initialize($config);
								if( ! $this->upload->do_upload('web_banner_content_'.$v)) 
								{
									$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
									$allow_to_update = FALSE;
								}else{
									$_FILES["web_banner_content_".$v]['name'] = $new_name;
								}
							}

							if($allow_to_update == TRUE)
							{
								if(isset($_FILES['mobile_banner_content_'.$v]['size']) && $_FILES['mobile_banner_content_'.$v]['size'] > 0)
								{
									$new_name = time().rand(1000,9999).".".pathinfo($_FILES["mobile_banner_content_".$v]['name'], PATHINFO_EXTENSION);
									$config['file_name'] = $new_name;
									$this->upload->initialize($config);
									if( ! $this->upload->do_upload('mobile_banner_content_'.$v)) 
									{
										$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
										$allow_to_update = FALSE;
									}
									else{
										$_FILES["mobile_banner_content_".$v]['name'] = $new_name;
									}
								}
							}
						}
					}
					if($allow_to_update == TRUE)
					{
						//Database update
						$this->db->trans_start();
						$newData = $oldData;
						$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
						if(sizeof($lang)>0){
							$oldDataLang = $this->promotion_model->get_promotion_lang_data($newData['promotion_id']);
							foreach($lang as $k => $v){
								if(isset($oldDataLang[$v])){
									$this->promotion_model->update_promotion_content($newData['promotion_id'],$v);
								}else{
									$this->promotion_model->add_promotion_content($newData['promotion_id'],$v);
								}
							}
						}
						$newDataLang = $this->promotion_model->get_promotion_lang_data($newData['promotion_id']);
						$newData['lang'] = $newDataLang;
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_PROMOTION_UPDATE, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_PROMOTION_UPDATE, $newData, $oldData);
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');
							
							//Prepare for ajax update
							$json['response'] = array(
								'id' => $newData['promotion_id'],
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
				$json['msg']['promotion_id_error'] = form_error('promotion_id');
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