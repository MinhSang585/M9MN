<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Game extends MY_Controller {



	public function __construct()

	{

		parent::__construct();

		$this->load->model('game_model');

		

		$is_logged_in = $this->is_logged_in();

		if( ! empty($is_logged_in)) 

		{

			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';

		}

	}

		

	public function index()

	{

		if(permission_validation(PERMISSION_GAME_VIEW) == TRUE)

		{

			$this->save_current_url('game');

			

			$data['page_title'] = $this->lang->line('title_game');

			$this->load->view('game_view', $data);

		}

		else

		{

			redirect('home');

		}

	}

	

	public function listing()

    {

		if(permission_validation(PERMISSION_GAME_VIEW) == TRUE)

		{

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$order = $this->input->post("order", TRUE);

			

			//Table Columns

			$columns = array( 

								0 => 'game_id',

								1 => 'game_name',

								2 => 'game_sequence',

								3 => 'is_maintenance',

								4 => 'fixed_maintenance',

								5 => 'fixed_day',

								6 => 'fixed_from_time',

								7 => 'fixed_to_time',

								8 => 'urgent_maintenance',

								9 => 'urgent_date',

								10 => 'updated_by',

								11 => 'updated_date'

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

							'table' => 'games',

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

					$row[] = $post->game_id;

					$row[] = $this->lang->line($post->game_name);

					$row[] = '<span id="uc1_' . $post->game_id . '">' . $post->game_sequence . '</span>';

					

					switch($post->is_maintenance)

					{

						case STATUS_YES: $row[] = '<span class="badge bg-success" id="uc2_' . $post->game_id . '">' . $this->lang->line('status_yes') . '</span>'; break;

						default: $row[] = '<span class="badge bg-secondary" id="uc2_' . $post->game_id . '">' . $this->lang->line('status_no') . '</span>'; break;

					}

					

					switch($post->fixed_maintenance)

					{

						case STATUS_YES: $row[] = '<span class="badge bg-success" id="uc3_' . $post->game_id . '">' . $this->lang->line('status_yes') . '</span><br />'; break;

						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->game_id . '">' . $this->lang->line('status_no') . '</span><br />'; break;

					}

					

					$row[] = '<span id="uc4_' . $post->game_id . '">' . (($post->fixed_day > 0) ? $this->lang->line(get_day($post->fixed_day)) : '-') . '</span><br />';

					$row[] = '<span id="uc5_' . $post->game_id . '">' . (( ! empty($post->fixed_from_time)) ? $post->fixed_from_time : '-') . '</span><br />';

					$row[] = '<span id="uc6_' . $post->game_id . '">' . (( ! empty($post->fixed_to_time)) ? $post->fixed_to_time : '-') . '</span>';

					

					switch($post->urgent_maintenance)

					{

						case STATUS_YES: $row[] = '<span class="badge bg-success" id="uc7_' . $post->game_id . '">' . $this->lang->line('status_yes') . '</span><br />'; break;

						default: $row[] = '<span class="badge bg-secondary" id="uc7_' . $post->game_id . '">' . $this->lang->line('status_no') . '</span><br />'; break;

					}

					

					$row[] = '<span id="uc8_' . $post->game_id . '">' . (($post->urgent_date > 0) ? date('Y-m-d H:i:s', $post->urgent_date) : '-') . '</span>';

					$row[] = '<span id="uc9_' . $post->game_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';

					$row[] = '<span id="uc10_' . $post->game_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';

					

					if(permission_validation(PERMISSION_GAME_UPDATE) == TRUE)

					{

						$row[] = '<i onclick="updateData(' . $post->game_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i>';

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

	

	public function edit($id = NULL)

    {

		if(permission_validation(PERMISSION_GAME_UPDATE) == TRUE)

		{

			$data = $this->game_model->get_game_data($id);

			$this->load->view('game_update', $data);

		}

		else

		{

			redirect('home');

		}

	}

	

	public function update()

	{

		if(permission_validation(PERMISSION_GAME_UPDATE) == TRUE)

		{

			//Initial output data

			$json = array(

						'status' => EXIT_ERROR, 

						'msg' => array(

										'game_sequence_error' => '',

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

									'field' => 'game_sequence',

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

				$game_id = trim($this->input->post('game_id', TRUE));

				$oldData = $this->game_model->get_game_data($game_id);

				

				if( ! empty($oldData))

				{

					//Database update

					$this->db->trans_start();

					$newData = $this->game_model->update_game($game_id);

					

					if($this->session->userdata('user_group') == USER_GROUP_USER) 

					{

						$this->user_model->insert_log(LOG_GAME_UPDATE, $newData, $oldData);

					}

					else

					{

						$this->account_model->insert_log(LOG_GAME_UPDATE, $newData, $oldData);

					}

					

					$this->db->trans_complete();

					

					if ($this->db->trans_status() === TRUE)

					{

						$json['status'] = EXIT_SUCCESS;

						$json['msg'] = $this->lang->line('success_updated');

						

						//Prepare for ajax update

						$json['response'] = array(

												'id' => $newData['game_id'],

												'game_sequence' => $newData['game_sequence'],

												'is_maintenance' => (($newData['is_maintenance'] == STATUS_YES) ? $this->lang->line('status_yes') : $this->lang->line('status_no')),

												'is_maintenance_code' => $newData['is_maintenance'],

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

				$json['msg']['game_sequence_error'] = form_error('game_sequence');

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



	public function maintenance()

	{

		if(permission_validation(PERMISSION_GAME_MAINTENANCE_VIEW) == TRUE)

		{

			$this->save_current_url('game/maintenance');

			

			$data['page_title'] = $this->lang->line('title_game_maintenance');

			$this->load->view('game_maintenance_view', $data);

		}

		else

		{

			redirect('home');

		}

	}



	public function maintenance_listing(){

		if(permission_validation(PERMISSION_GAME_MAINTENANCE_VIEW) == TRUE)

		{

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$order = $this->input->post("order", TRUE);

			

			//Table Columns

			$columns = array( 

				0 => 'game_maintenance_id',

				1 => 'game_name',

				2 => 'game_sequence',

				3 => 'is_maintenance',

				4 => 'is_front_end_display',

				5 => 'fixed_maintenance',

				6 => 'fixed_day',

				7 => 'fixed_from_time',

				8 => 'fixed_to_time',

				9 => 'urgent_maintenance',

				10 => 'urgent_date',

				11 => 'updated_by',

				12 => 'updated_date'

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

							'table' => 'game_maintenance',

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

					$row[] = $post->game_maintenance_id;

					$row[] = '<span id="uc12_' . $post->game_maintenance_id . '">' . $this->lang->line($post->game_name) . '</span>';

					$row[] = '<span id="uc1_' . $post->game_maintenance_id . '">' . $post->game_sequence . '</span>';

					

					switch($post->is_maintenance)

					{

						case STATUS_YES: $row[] = '<span class="badge bg-success" id="uc2_' . $post->game_maintenance_id . '">' . $this->lang->line('status_yes') . '</span>'; break;

						default: $row[] = '<span class="badge bg-secondary" id="uc2_' . $post->game_maintenance_id . '">' . $this->lang->line('status_no') . '</span>'; break;

					}



					switch($post->is_front_end_display)

					{

						case STATUS_YES: $row[] = '<span class="badge bg-success" id="uc11_' . $post->game_maintenance_id . '">' . $this->lang->line('status_yes') . '</span>'; break;

						default: $row[] = '<span class="badge bg-secondary" id="uc11_' . $post->game_maintenance_id . '">' . $this->lang->line('status_no') . '</span>'; break;

					}



					switch($post->fixed_maintenance)

					{

						case STATUS_YES: $row[] = '<span class="badge bg-success" id="uc3_' . $post->game_maintenance_id . '">' . $this->lang->line('status_yes') . '</span><br />'; break;

						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->game_maintenance_id . '">' . $this->lang->line('status_no') . '</span><br />'; break;

					}

					

					$row[] = '<span id="uc4_' . $post->game_maintenance_id . '">' . (($post->fixed_day > 0) ? $this->lang->line(get_day($post->fixed_day)) : '-') . '</span><br />';

					$row[] = '<span id="uc5_' . $post->game_maintenance_id . '">' . (( ! empty($post->fixed_from_time)) ? $post->fixed_from_time : '-') . '</span><br />';

					$row[] = '<span id="uc6_' . $post->game_maintenance_id . '">' . (( ! empty($post->fixed_to_time)) ? $post->fixed_to_time : '-') . '</span>';

					

					switch($post->urgent_maintenance)

					{

						case STATUS_YES: $row[] = '<span class="badge bg-success" id="uc7_' . $post->game_maintenance_id . '">' . $this->lang->line('status_yes') . '</span><br />'; break;

						default: $row[] = '<span class="badge bg-secondary" id="uc7_' . $post->game_maintenance_id . '">' . $this->lang->line('status_no') . '</span><br />'; break;

					}

					

					$row[] = '<span id="uc8_' . $post->game_maintenance_id . '">' . (($post->urgent_date > 0) ? date('Y-m-d H:i:s', $post->urgent_date) : '-') . '</span>';

					$row[] = '<span id="uc9_' . $post->game_maintenance_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';

					$row[] = '<span id="uc10_' . $post->game_maintenance_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';

					

					if(permission_validation(PERMISSION_GAME_MAINTENANCE_UPDATE) == TRUE)

					{

						$button .= '<i onclick="updateData(' . $post->game_maintenance_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i>&nbsp;&nbsp; ';

					}



					if(permission_validation(PERMISSION_GAME_MAINTENANCE_DELETE) == TRUE)

					{

						$button .= '<i onclick="deleteData(' . $post->game_maintenance_id . ')" type="button" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>&nbsp;&nbsp; ';

					}



					if(permission_validation(PERMISSION_GAME_MAINTENANCE_UPDATE) == TRUE || permission_validation(PERMISSION_GAME_MAINTENANCE_DELETE) == TRUE){

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

		if(permission_validation(PERMISSION_GAME_MAINTENANCE_ADD) == TRUE)

		{

			$data['game'] = $this->game_model->get_game_list();

			$this->load->view('game_maintenance_add',$data);

		}

		else

		{

			redirect('home');

		}

	}



	public function maintenance_edit($id = NULL)

    {

		if(permission_validation(PERMISSION_GAME_MAINTENANCE_UPDATE) == TRUE)

		{

			$data = $this->game_model->get_game_maintenance_data($id);

			$data['game'] = $this->game_model->get_game_list();

			$this->load->view('game_maintenance_update', $data);

		}

		else

		{

			redirect('home');

		}

	}



	public function maintenance_submit()

	{

		if(permission_validation(PERMISSION_GAME_MAINTENANCE_ADD) == TRUE)

		{

			//Initial output data

			$json = array(

						'status' => EXIT_ERROR, 

						'msg' => array(

										'game_sequence_error' => '',

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

						'field' => 'game_code',

						'label' => strtolower($this->lang->line('label_name')),

						'rules' => 'trim|required',

						'errors' => array(

								'required' => $this->lang->line('error_select_game_name'),

						)

				),

				array(

						'field' => 'game_sequence',

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

				$gameData = $this->game_model->get_game_data($this->input->post('game_code', TRUE));

				if(!empty($gameData)){

					$newData = $this->game_model->add_game_maintenance($gameData);

				

					if($this->session->userdata('user_group') == USER_GROUP_USER) 

					{

						$this->user_model->insert_log(LOG_GAME_MAINTENANCE_ADD, $newData);

					}

					else

					{

						$this->account_model->insert_log(LOG_GAME_MAINTENANCE_ADD, $newData);

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

				$json['msg']['game_sequence_error'] = form_error('game_sequence');

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



	public function maintenance_update()

	{

		if(permission_validation(PERMISSION_GAME_MAINTENANCE_UPDATE) == TRUE)

		{

			//Initial output data

			$json = array(

						'status' => EXIT_ERROR, 

						'msg' => array(

										'game_sequence_error' => '',

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

						'field' => 'game_code',

						'label' => strtolower($this->lang->line('label_name')),

						'rules' => 'trim|required',

						'errors' => array(

								'required' => $this->lang->line('error_select_game_name'),

						)

				),

				array(

						'field' => 'game_sequence',

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

				$game_maintenance_id = trim($this->input->post('game_maintenance_id', TRUE));

				$oldData = $this->game_model->get_game_maintenance_data($game_maintenance_id);

				$gameData = $this->game_model->get_game_data($this->input->post('game_code', TRUE));

				if( ! empty($oldData) &&  ! empty($gameData))

				{

					//Database update

					$this->db->trans_start();

					$newData = $this->game_model->update_game_maintenance($game_maintenance_id,$gameData);

					

					if($this->session->userdata('user_group') == USER_GROUP_USER) 

					{

						$this->user_model->insert_log(LOG_GAME_MAINTENANCE_UPDATE, $newData, $oldData);

					}

					else

					{

						$this->account_model->insert_log(LOG_GAME_MAINTENANCE_UPDATE, $newData, $oldData);

					}

					

					$this->db->trans_complete();

					

					if ($this->db->trans_status() === TRUE)

					{

						$json['status'] = EXIT_SUCCESS;

						$json['msg'] = $this->lang->line('success_updated');

						

						//Prepare for ajax update

						$json['response'] = array(

							'id' => $newData['game_maintenance_id'],

							'game_code' => $newData['game_code'],

							'game_name' => $this->lang->line($newData['game_name']),

							'game_sequence' => $newData['game_sequence'],

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

				$json['msg']['game_sequence_error'] = form_error('game_sequence');

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

					

		if(permission_validation(PERMISSION_GAME_MAINTENANCE_DELETE) == TRUE)

		{

			$game_id = $this->uri->segment(3);

			$oldData = $this->game_model->get_game_maintenance_data($game_id);

			

			if( ! empty($oldData))

			{

				//Database update

				$this->db->trans_start();

				$this->game_model->delete_game_maintenance($game_id);

				

				if($this->session->userdata('user_group') == USER_GROUP_USER) 

				{

					$this->user_model->insert_log(LOG_GAME_MAINTENANCE_DELETE, $oldData);

				}

				else

				{

					$this->account_model->insert_log(LOG_GAME_MAINTENANCE_DELETE, $oldData);

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

	public function sub_game()
	{
		if(permission_validation(PERMISSION_SUB_GAME_VIEW) == TRUE)
		{
			$this->save_current_url('game/sub_game');
			$data = array(
				'game_name' => '',
				'game_provider_code' => '',
				'game_type_code' => '',
				'game_code' => '',
				'active' => -1,
				'is_mobile' => -1,
				'is_progressive' => -1,
				'is_hot' => -1,
				'is_new' => -1,
			);
			$data['page_title'] = $this->lang->line('title_sub_game');
			$data['game'] = $this->game_model->get_game_list();
			$this->session->set_userdata('search_sub_game', $data);
			$this->load->view('sub_game_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function sub_game_search(){
		if(permission_validation(PERMISSION_SUB_GAME_VIEW) == TRUE)
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
				'game_name' => trim($this->input->post('game_name', TRUE)),
				'game_provider_code' => trim($this->input->post('game_provider_code', TRUE)),
				'game_type_code' => trim($this->input->post('game_type_code', TRUE)),
				'game_code' => trim($this->input->post('game_code', TRUE)),
				'active' => trim($this->input->post('active', TRUE)),
				'is_mobile' => trim($this->input->post('is_mobile', TRUE)),
				'is_progressive' => trim($this->input->post('is_progressive', TRUE)),
				'is_hot' => trim($this->input->post('is_hot', TRUE)),
				'is_new' => trim($this->input->post('is_new', TRUE)),
			);
			$this->session->set_userdata('search_sub_game', $data);
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
	public function sub_game_listing()
    {
    	if(permission_validation(PERMISSION_SUB_GAME_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			$columns = array(
				0 => 'sub_game_id',
				1 => 'game_provider_code',
				2 => 'game_type_code',
				3 => 'game_sequence',
				4 => 'game_code',
				5 => 'game_name_en',
				6 => 'game_name_chs',
				7 => 'game_name_cht',
				8 => 'active',
				9 => 'is_mobile',
				10 => 'is_progressive',
				11 => 'is_hot',
				12 => 'is_new',
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
			$arr = $this->session->userdata('search_sub_game');
			$where = '';
			$game_array = array();
			$game = $this->game_model->get_game_list();
			if(!empty($game)){
				foreach($game as $game_row){
					array_push($game_array,$game_row['game_code']);
				}
			}
			if(!empty($game_array)){
				$game_string = implode("','", $game_array);
				$where = "WHERE game_provider_code IN ('".$game_string."')";
			}else{
				$where = "WHERE sub_game_id = -1";
			}
			if( ! empty($arr['game_name']))
			{
				$where .= " AND (game_name_en LIKE '" . $arr['game_name'] . "%' ESCAPE '!' OR game_name_chs LIKE '" . $arr['game_name'] . "%' ESCAPE '!' OR game_name_cht LIKE '" . $arr['game_name'] . "%' ESCAPE '!')";	
			}
			if( ! empty($arr['game_provider_code']))
			{
				$where .= " AND game_provider_code = '".$arr['game_provider_code']."'";	
			}
			if( ! empty($arr['game_type_code']))
			{
				$where .= " AND game_type_code = '".$arr['game_type_code']."'";	
			}
			if( ! empty($arr['game_code']))
			{
				$where .= " AND game_code = '".$arr['game_code']."'";	
			}
			if($arr['active'] == STATUS_INACTIVE OR $arr['active'] == STATUS_ACTIVE)
			{
				$where .= ' AND active = ' . $arr['active'];
			}
			if($arr['is_mobile'] == STATUS_INACTIVE OR $arr['is_mobile'] == STATUS_ACTIVE)
			{
				$where .= ' AND is_mobile = ' . $arr['is_mobile'];
			}
			if($arr['is_progressive'] == STATUS_INACTIVE OR $arr['is_progressive'] == STATUS_ACTIVE)
			{
				$where .= ' AND is_progressive = ' . $arr['is_progressive'];
			}
			if($arr['is_hot'] == STATUS_INACTIVE OR $arr['is_hot'] == STATUS_ACTIVE)
			{
				$where .= ' AND is_hot = ' . $arr['is_hot'];
			}
			if($arr['is_new'] == STATUS_INACTIVE OR $arr['is_new'] == STATUS_ACTIVE)
			{
				$where .= ' AND is_new = ' . $arr['is_new'];
			}
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;
			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}sub_games $where)";
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
			$data = array();
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					$row = array();
					$button = "";
					$row[] = $post->sub_game_id;
					$row[] = '<span id="uc1_' . $post->sub_game_id . '">' . $this->lang->line('game_'.strtolower($post->game_provider_code)) . '</span>';
					$row[] = '<span id="uc2_' . $post->sub_game_id . '">' . $this->lang->line('game_type_'.strtolower($post->game_type_code)) . '</span>';
					$row[] = '<span id="uc3_' . $post->sub_game_id . '">' . $post->game_sequence . '</span>';
					$row[] = '<span id="uc4_' . $post->sub_game_id . '">' . $post->game_code . '</span>';
					$row[] = '<span id="uc5_' . $post->sub_game_id . '">' . $post->game_name_en . '</span>';
					$row[] = '<span id="uc6_' . $post->sub_game_id . '">' . $post->game_name_chs . '</span>';
					$row[] = '<span id="uc7_' . $post->sub_game_id . '">' . $post->game_name_cht . '</span>';
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc10_' . $post->sub_game_id . '">' . $this->lang->line('status_active'). '</span>'; break;
						case STATUS_INACTIVE: $row[] = '<span class="badge bg-secondary" id="uc10_' . $post->sub_game_id . '">' . $this->lang->line('status_inactive'). '</span>'; break;
						default:  $row[] ='<span class="badge bg-secondary" id="uc10_' . $post->sub_game_id . '">' . $this->lang->line('status_inactive'). '</span>'; break;
					}
					switch($post->is_mobile)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc11_' . $post->sub_game_id . '">' . $this->lang->line('status_active'). '</span>'; break;
						case STATUS_INACTIVE: $row[] = '<span class="badge bg-secondary" id="uc11_' . $post->sub_game_id . '">' . $this->lang->line('status_inactive'). '</span>'; break;
						default:  $row[] ='<span class="badge bg-secondary" id="uc11_' . $post->sub_game_id . '">' . $this->lang->line('status_inactive'). '</span>'; break;
					}
					switch($post->is_progressive)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc12_' . $post->sub_game_id . '">' . $this->lang->line('status_active'). '</span>'; break;
						case STATUS_INACTIVE: $row[] = '<span class="badge bg-secondary" id="uc12_' . $post->sub_game_id . '">' . $this->lang->line('status_inactive'). '</span>'; break;
						default:  $row[] ='<span class="badge bg-secondary" id="uc12_' . $post->sub_game_id . '">' . $this->lang->line('status_inactive'). '</span>'; break;
					}
					switch($post->is_hot)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc13_' . $post->sub_game_id . '">' . $this->lang->line('status_active'). '</span>'; break;
						case STATUS_INACTIVE: $row[] = '<span class="badge bg-secondary" id="uc13_' . $post->sub_game_id . '">' . $this->lang->line('status_inactive'). '</span>'; break;
						default:  $row[] ='<span class="badge bg-secondary" id="uc13_' . $post->sub_game_id . '">' . $this->lang->line('status_inactive'). '</span>'; break;
					}
					switch($post->is_new)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc14_' . $post->sub_game_id . '">' . $this->lang->line('status_active'). '</span>'; break;
						case STATUS_INACTIVE: $row[] = '<span class="badge bg-secondary" id="uc14_' . $post->sub_game_id . '">' . $this->lang->line('status_inactive'). '</span>'; break;
						default:  $row[] ='<span class="badge bg-secondary" id="uc14_' . $post->sub_game_id . '">' . $this->lang->line('status_inactive'). '</span>'; break;
					}
					if(permission_validation(PERMISSION_SUB_GAME_UPDATE)){
						$button .= '<i onclick="updateData(' . $post->sub_game_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i>&nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_SUB_GAME_DELETE)){
						$button .= '<i onclick="deleteData(' . $post->sub_game_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_SUB_GAME_UPDATE) == TRUE OR permission_validation(PERMISSION_SUB_GAME_DELETE) == TRUE){
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
	public function sub_game_add(){
		if(permission_validation(PERMISSION_SUB_GAME_ADD) == TRUE)
		{
			$data['game'] = $this->game_model->get_game_list();
			$this->load->view('sub_game_add',$data);
		}
		else
		{
			redirect('home');
		}
	}
	public function sub_game_submit(){
		if(permission_validation(PERMISSION_SUB_GAME_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'game_code_error' => '',
					'game_type_code_error' => '',
					'game_provider_code_error' => '',
					'game_sequence_error' => '',
					'general_error' => '',
				), 
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			//Set form rules
			$config = array(
				array(
					'field' => 'game_provider_code',
					'label' => strtolower($this->lang->line('label_game_provider')),
					'rules' => 'trim|required',
					'errors' => array(
							'required' => $this->lang->line('error_select_game_provider'),
					)
				),
				array(
					'field' => 'game_type_code',
					'label' => strtolower($this->lang->line('label_game_type')),
					'rules' => 'trim|required',
					'errors' => array(
							'required' => $this->lang->line('error_select_game_type'),
					)
				),
				array(
					'field' => 'game_code',
					'label' => strtolower($this->lang->line('label_game_code')),
					'rules' => 'trim|required',
					'errors' => array(
							'required' => $this->lang->line('error_select_game_code'),
					)
				),
				array(
					'field' => 'game_sequence',
					'label' => strtolower($this->lang->line('label_sequence')),
					'rules' => 'trim|required|integer',
					'errors' => array(
						'required' => $this->lang->line('error_only_digits_allowed'),
						'integer' => $this->lang->line('error_only_digits_allowed')
					)
				)
			);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				//Database update
				$game_type_code = $this->input->post('game_type_code', TRUE);
				$gameData = $this->game_model->get_game_data($this->input->post('game_provider_code', TRUE));
				$game_provider_code_path = strtolower($gameData['game_code']);
				if(!empty($gameData)){
					$game_type_code_path = sub_game_image_location($game_type_code);
					if(!is_dir(SUBGAME_PATH.$game_type_code_path)){
						mkdir(SUBGAME_PATH.$game_type_code_path, 0777);
					}
					if(!is_dir(SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path)){
						mkdir(SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path, 0777);
					}
					if(!is_dir(SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."en")){
						mkdir(SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."en", 0777);
					}
					if(!is_dir(SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."cn")){
						mkdir(SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."cn", 0777);
					}
					if(!is_dir(SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."zh")){
						mkdir(SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."zh", 0777);
					}
					$allow_to_update = TRUE;
					$config['upload_path'] = SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."en/";
					$config['max_size'] = SUBGAME_FILE_SIZE;
					$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
					$config['overwrite'] = TRUE;
					$this->load->library('upload', $config);
					if(isset($_FILES['game_picture_en']['size']) && $_FILES['game_picture_en']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["game_picture_en"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('game_picture_en')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["game_picture_en"]['name'] = $new_name;
						}
					}
					$config['upload_path'] = SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."cn/";
					$config['max_size'] = SUBGAME_FILE_SIZE;
					$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
					$config['overwrite'] = TRUE;
					$this->load->library('upload', $config);
					if(isset($_FILES['game_picture_chs']['size']) && $_FILES['game_picture_chs']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["game_picture_chs"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('game_picture_chs')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["game_picture_chs"]['name'] = $new_name;
						}
					}
					$config['upload_path'] = SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."zh/";
					$config['max_size'] = SUBGAME_FILE_SIZE;
					$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
					$config['overwrite'] = TRUE;
					$this->load->library('upload', $config);
					if(isset($_FILES['game_picture_cht']['size']) && $_FILES['game_picture_cht']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["game_picture_cht"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('game_picture_cht')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["game_picture_cht"]['name'] = $new_name;
						}
					}
					if($allow_to_update == TRUE)
					{
						$this->db->trans_start();
						$newData = $this->game_model->add_sub_game($gameData);
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_SUB_GAME_ADD, $newData);
						}
						else
						{
							$this->account_model->insert_log(LOG_SUB_GAME_ADD, $newData);
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
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');
				}
			}
			else 
			{
				$json['msg']['game_code_error'] = form_error('game_code');
				$json['msg']['game_type_code_error'] = form_error('game_type_code');
				$json['msg']['game_provider_code_error'] = form_error('game_provider_code');
				$json['msg']['game_sequence_error'] = form_error('game_sequence');
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
	public function sub_game_edit($id = NULL){
		if(permission_validation(PERMISSION_SUB_GAME_UPDATE) == TRUE)
		{
			$data = $this->game_model->get_sub_game_data($id);
			$data['game'] = $this->game_model->get_game_list();
			$this->load->view('sub_game_update', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function sub_game_update(){
		if(permission_validation(PERMISSION_SUB_GAME_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'game_code_error' => '',
					'game_type_code_error' => '',
					'game_provider_code_error' => '',
					'game_sequence_error' => '',
					'general_error' => '',
				), 
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			//Set form rules
			$config = array(
				array(
					'field' => 'game_provider_code',
					'label' => strtolower($this->lang->line('label_game_provider')),
					'rules' => 'trim|required',
					'errors' => array(
							'required' => $this->lang->line('error_select_game_provider'),
					)
				),
				array(
					'field' => 'game_type_code',
					'label' => strtolower($this->lang->line('label_game_type')),
					'rules' => 'trim|required',
					'errors' => array(
							'required' => $this->lang->line('error_select_game_type'),
					)
				),
				array(
					'field' => 'game_code',
					'label' => strtolower($this->lang->line('label_game_code')),
					'rules' => 'trim|required',
					'errors' => array(
							'required' => $this->lang->line('error_select_game_code'),
					)
				),
				array(
					'field' => 'game_sequence',
					'label' => strtolower($this->lang->line('label_sequence')),
					'rules' => 'trim|required|integer',
					'errors' => array(
						'required' => $this->lang->line('error_only_digits_allowed'),
						'integer' => $this->lang->line('error_only_digits_allowed')
					)
				)
			);
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$sub_game_id = trim($this->input->post('sub_game_id', TRUE));
				$oldData = $this->game_model->get_sub_game_data($sub_game_id);
				$game_type_code = $this->input->post('game_type_code', TRUE);
				$gameData = $this->game_model->get_game_data($this->input->post('game_provider_code', TRUE));
				$game_provider_code_path = strtolower($gameData['game_code']);
				if(!empty($gameData)){
					$game_type_code_path = sub_game_image_location($game_type_code);
					if(!is_dir(SUBGAME_PATH.$game_type_code_path)){
						mkdir(SUBGAME_PATH.$game_type_code_path, 0777);
					}
					if(!is_dir(SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path)){
						mkdir(SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path, 0777);
					}
					if(!is_dir(SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."en")){
						mkdir(SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."en", 0777);
					}
					if(!is_dir(SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."cn")){
						mkdir(SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."cn", 0777);
					}
					if(!is_dir(SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."zh")){
						mkdir(SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."zh", 0777);
					}
					$allow_to_update = TRUE;
					$config['upload_path'] = SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."en/";
					$config['max_size'] = SUBGAME_FILE_SIZE;
					$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
					$config['overwrite'] = TRUE;
					$this->load->library('upload', $config);
					if(isset($_FILES['game_picture_en']['size']) && $_FILES['game_picture_en']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["game_picture_en"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('game_picture_en')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["game_picture_en"]['name'] = $new_name;
						}
					}
					$config['upload_path'] = SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."cn/";
					$config['max_size'] = SUBGAME_FILE_SIZE;
					$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
					$config['overwrite'] = TRUE;
					$this->load->library('upload', $config);
					if(isset($_FILES['game_picture_chs']['size']) && $_FILES['game_picture_chs']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["game_picture_chs"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('game_picture_chs')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["game_picture_chs"]['name'] = $new_name;
						}
					}
					$config['upload_path'] = SUBGAME_PATH.$game_type_code_path."/".$game_provider_code_path."/"."zh/";
					$config['max_size'] = SUBGAME_FILE_SIZE;
					$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
					$config['overwrite'] = TRUE;
					$this->load->library('upload', $config);
					if(isset($_FILES['game_picture_cht']['size']) && $_FILES['game_picture_cht']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["game_picture_cht"]['name'], PATHINFO_EXTENSION);
						$config['file_name']  = $new_name;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload('game_picture_cht')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');
							$allow_to_update = FALSE;
						}else{
							$_FILES["game_picture_cht"]['name'] = $new_name;
						}
					}
					if($allow_to_update == TRUE)
					{
						$this->db->trans_start();
						$newData = $this->game_model->update_sub_game($gameData,$oldData['sub_game_id']);
						if($this->session->userdata('user_group') == USER_GROUP_USER)
						{
							$this->user_model->insert_log(LOG_SUB_GAME_UPDATE, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_SUB_GAME_UPDATE, $newData, $oldData);
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');
							$json['response'] = array(
								'id' => $newData['sub_game_id'],
								'game_provider_code' => $newData['game_provider_code'],
								'game_provider_code_name' => $this->lang->line('game_'.strtolower($newData['game_provider_code'])),
								'game_type_code' => $newData['game_type_code'],
								'game_type_code_name' => $this->lang->line('game_type_'.strtolower($newData['game_type_code'])),
								'game_sequence' => $newData['game_sequence'],
								'game_code' => $newData['game_code'],
								'game_name_en' => $newData['game_name_en'],
								'game_name_chs' => $newData['game_name_chs'],
								'game_name_cht' => $newData['game_name_cht'],
								'active' => (($newData['active'] == STATUS_YES) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
								'active_code' => $newData['active'],
								'is_mobile' => (($newData['is_mobile'] == STATUS_YES) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
								'is_mobile_code' => $newData['is_mobile'],
								'is_progressive' => (($newData['is_progressive'] == STATUS_YES) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
								'is_progressive_code' => $newData['is_progressive'],
								'is_hot' => (($newData['is_hot'] == STATUS_YES) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
								'is_hot_code' => $newData['is_hot'],
								'is_new' => (($newData['is_new'] == STATUS_YES) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
								'is_new_code' => $newData['is_new'],
							);
							if(isset($_FILES['game_picture_en']['size']) && $_FILES['game_picture_en']['size'] > 0)
							{
								if( ! empty($oldData['game_picture_en']))
								{
									unlink(SUBGAME_PATH.sub_game_image_location($oldData['game_type_code'])."/".strtolower($oldData['game_provider_code'])."/"."en/" . $oldData['game_picture_en']);
								}
							}else{
								if(($oldData['game_provider_code'] != $newData['game_provider_code']) || ($oldData['game_type_code'] != $newData['game_type_code'])){
									if( ! empty($oldData['game_picture_en']))
									{
										if(!is_dir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code']))){
											mkdir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code']), 0777);
										}
										if(!is_dir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code'])."/".strtolower($newData['game_provider_code']))){
											mkdir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code'])."/".strtolower($newData['game_provider_code']), 0777);
										}
										if(!is_dir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code'])."/".strtolower($newData['game_provider_code'])."/"."en")){
											mkdir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code'])."/".strtolower($newData['game_provider_code'])."/"."en", 0777);
										}
										rename(SUBGAME_PATH.sub_game_image_location($oldData['game_type_code'])."/".strtolower($oldData['game_provider_code'])."/"."en/" . $oldData['game_picture_en'], SUBGAME_PATH.sub_game_image_location($newData['game_type_code'])."/".strtolower($newData['game_provider_code'])."/"."en/" . $oldData['game_picture_en']);
										unlink(SUBGAME_PATH.sub_game_image_location($oldData['game_type_code'])."/".strtolower($oldData['game_provider_code'])."/"."en/" . $oldData['game_picture_en']);
									}
								}
							}
							if(isset($_FILES['game_picture_chs']['size']) && $_FILES['game_picture_chs']['size'] > 0)
							{
								if( ! empty($oldData['game_picture_chs']))
								{
									unlink(SUBGAME_PATH.sub_game_image_location($oldData['game_type_code'])."/".strtolower($oldData['game_provider_code'])."/"."en/" . $oldData['game_picture_chs']);
								}
							}else{
								if(($oldData['game_provider_code'] != $newData['game_provider_code']) || ($oldData['game_type_code'] != $newData['game_type_code'])){
									if( ! empty($oldData['game_picture_chs']))
									{
										if(!is_dir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code']))){
											mkdir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code']), 0777);
										}
										if(!is_dir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code'])."/".strtolower($newData['game_provider_code']))){
											mkdir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code'])."/".strtolower($newData['game_provider_code']), 0777);
										}
										if(!is_dir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code'])."/".strtolower($newData['game_provider_code'])."/"."cn")){
											mkdir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code'])."/".strtolower($newData['game_provider_code'])."/"."cn", 0777);
										}
										rename(SUBGAME_PATH.sub_game_image_location($oldData['game_type_code'])."/".strtolower($oldData['game_provider_code'])."/"."cn/" . $oldData['game_picture_chs'], SUBGAME_PATH.sub_game_image_location($newData['game_type_code'])."/".strtolower($newData['game_provider_code'])."/"."cn/" . $oldData['game_picture_chs']);
										unlink(SUBGAME_PATH.sub_game_image_location($oldData['game_type_code'])."/".strtolower($oldData['game_provider_code'])."/"."cn/" . $oldData['game_picture_chs']);
									}
								}
							}
							if(isset($_FILES['game_picture_cht']['size']) && $_FILES['game_picture_cht']['size'] > 0)
							{
								if( ! empty($oldData['game_picture_cht']))
								{
									unlink(SUBGAME_PATH.sub_game_image_location($oldData['game_type_code'])."/".strtolower($oldData['game_provider_code'])."/"."en/" . $oldData['game_picture_cht']);
								}
							}else{
								if(($oldData['game_provider_code'] != $newData['game_provider_code']) || ($oldData['game_type_code'] != $newData['game_type_code'])){
									if( ! empty($oldData['game_picture_cht']))
									{
										if(!is_dir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code']))){
											mkdir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code']), 0777);
										}
										if(!is_dir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code'])."/".strtolower($newData['game_provider_code']))){
											mkdir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code'])."/".strtolower($newData['game_provider_code']), 0777);
										}
										if(!is_dir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code'])."/".strtolower($newData['game_provider_code'])."/"."zh")){
											mkdir(SUBGAME_PATH.sub_game_image_location($newData['game_type_code'])."/".strtolower($newData['game_provider_code'])."/"."zh", 0777);
										}
										rename(SUBGAME_PATH.sub_game_image_location($oldData['game_type_code'])."/".strtolower($oldData['game_provider_code'])."/"."zh/" . $oldData['game_picture_cht'], SUBGAME_PATH.sub_game_image_location($newData['game_type_code'])."/".strtolower($newData['game_provider_code'])."/"."zh/" . $oldData['game_picture_cht']);
										unlink(SUBGAME_PATH.sub_game_image_location($oldData['game_type_code'])."/".strtolower($oldData['game_provider_code'])."/"."zh/" . $oldData['game_picture_cht']);
									}
								}
							}
						}
						else
						{
							$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
						}
					}
				}else{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
				}	
			}
			else 
			{
				$json['msg']['game_sequence_error'] = form_error('game_sequence');
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
	public function sub_game_delete(){
		$json = array(
					'status' => EXIT_ERROR, 
					'msg' => ''
				);
		if(permission_validation(PERMISSION_SUB_GAME_DELETE) == TRUE)
		{
			$sub_game_id = $this->uri->segment(3);
			$oldData = $this->game_model->get_sub_game_data($sub_game_id);
			if( ! empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->game_model->delete_sub_game($sub_game_id);
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_SUB_GAME_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_SUB_GAME_DELETE, $oldData);
				}
				$this->db->trans_complete();
				if ($this->db->trans_status() === TRUE)
				{
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('success_deleted');
					if( ! empty($oldData['game_picture_en']))
					{
						unlink(SUBGAME_PATH.sub_game_image_location($oldData['game_type_code'])."/".strtolower($oldData['game_provider_code'])."/"."en/" . $oldData['game_picture_en']);
					}
					if( ! empty($oldData['game_picture_chs']))
					{
						unlink(SUBGAME_PATH.sub_game_image_location($oldData['game_type_code'])."/".strtolower($oldData['game_provider_code'])."/"."cn/" . $oldData['game_picture_chs']);
					}
					if( ! empty($oldData['game_picture_cht']))
					{
						unlink(SUBGAME_PATH.sub_game_image_location($oldData['game_type_code'])."/".strtolower($oldData['game_provider_code'])."/"."zh/" . $oldData['game_picture_cht']);
					}
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