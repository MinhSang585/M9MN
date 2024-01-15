<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Bank extends MY_Controller {



	public function __construct()

	{

		parent::__construct();

		$this->load->model(array('bank_model', 'group_model','player_model','miscellaneous_model','currencies_model','message_model','promotion_model','player_promotion_model','promotion_apply_model','promotion_approve_model'));

		

		$is_logged_in = $this->is_logged_in();

		if( ! empty($is_logged_in)) 

		{

			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';

		}

	}

		

	public function index()

	{

		if(permission_validation(PERMISSION_BANK_VIEW) == TRUE)

		{

			$this->save_current_url('bank');

			$data['currencies_list'] = $this->currencies_model->get_currencies_list();

			$data['page_title'] = $this->lang->line('title_bank');

			

			$this->session->unset_userdata('search_deposits');

			$data_search = array(

				'bank_name' => "",

				'bank_code' => "",

				'status' => "-1",

				'currency_id' => "",

			);

			$data['data_search'] = $data_search;

			$this->session->set_userdata('search_banks', $data_search);

			$this->load->view('bank_view', $data);

		}

		else

		{

			redirect('home');

		}

	}

	

	public function search()

	{

		if(permission_validation(PERMISSION_BANK_VIEW) == TRUE)

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

			

			$data = array(

			    'bank_name' => trim($this->input->post('bank_name', TRUE)),

				'bank_code' => trim($this->input->post('bank_code', TRUE)),

				'status' => trim($this->input->post('status', TRUE)),

				'currency_id' => trim($this->input->post('currency_id', TRUE)),

			);

			

			$this->session->set_userdata('search_banks', $data);

			

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

	

	public function listing()

    {

		if(permission_validation(PERMISSION_BANK_VIEW) == TRUE)

		{

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$order = $this->input->post("order", TRUE);

			

			//Table Columns

			$columns = array( 

				0 => 'a.bank_id',

				1 => 'a.bank_name',

				2 => 'a.bank_code',

				3 => 'b.currency_code',

				4 => 'a.active',

				5 => 'a.updated_by',

				6 => 'a.updated_date',

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

			

			$arr = $this->session->userdata('search_banks');	

			$where = '';		

				

			if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_INACTIVE)

			{

				$where .= ' AND a.active = ' . $arr['status'];

			}



			if(!empty($arr['currency_id']))

			{

				$where .= " AND a.currency_id = '" . $arr['currency_id']."'";	

			}

			

			if( ! empty($arr['bank_name']))

			{

				$where .= " AND a.bank_name LIKE '%" . $arr['bank_name'] . "%'";

			}

			

			if( ! empty($arr['bank_code']))

			{

				$where .= " AND a.bank_code = '" . $arr['bank_code']."'";	

			}

			

			$select = implode(',', $columns);

			$dbprefix = $this->db->dbprefix;



			$posts = NULL;

			$query_string = "(SELECT {$select} FROM {$dbprefix}banks a, {$dbprefix}currencies b WHERE (a.currency_id = b.currency_id) $where)";

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

					$row[] = $post->bank_id;

					$row[] = '<span id="uc1_' . $post->bank_id . '">' . $post->bank_name . '</span>';

					$row[] = '<span id="uc6_' . $post->bank_id . '">' . $post->bank_code . '</span>';

					$row[] = '<span id="uc5_' . $post->bank_id . '">' . $post->currency_code . '</span>';

					switch($post->active)

					{

						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc2_' . $post->bank_id . '">' . $this->lang->line('status_active') . '</span>'; break;

						default: $row[] = '<span class="badge bg-secondary" id="uc2_' . $post->bank_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;

					}

					

					$row[] = '<span id="uc3_' . $post->bank_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';

					$row[] = '<span id="uc4_' . $post->bank_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';

					

					$button = '';

					if(permission_validation(PERMISSION_BANK_UPDATE) == TRUE)

					{

						$button .= '<i onclick="updateData(' . $post->bank_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';

					}

					

					if(permission_validation(PERMISSION_BANK_DELETE) == TRUE)

					{

						$button .= '<i onclick="deleteData(' . $post->bank_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';

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

		if(permission_validation(PERMISSION_BANK_ADD) == TRUE)

		{

			$data['currencies_list'] = $this->currencies_model->get_currencies_list();

			$this->load->view('bank_add',$data);

		}

		else

		{

			redirect('home');

		}

	}

	

	public function submit()

	{

		if(permission_validation(PERMISSION_BANK_ADD) == TRUE)

		{

			//Initial output data

			$json = array(

						'status' => EXIT_ERROR, 

						'msg' => array(

										'bank_name_error' => '',

										'currency_id_error' =>  '',

										'general_error' => ''

									), 		

						'csrfTokenName' => $this->security->get_csrf_token_name(), 

						'csrfHash' => $this->security->get_csrf_hash()

					);

			

			//Set form rules

			$config = array(

				array(

						'field' => 'currency_id',

						'label' => strtolower($this->lang->line('label_currency_code')),

						'rules' => 'trim|required|integer',

						'errors' => array(

								'required' => $this->lang->line('error_select_currencies'),

								'integer' => $this->lang->line('error_select_currencies')

						)

				),

				array(

					'field' => 'bank_name',

					'label' => strtolower($this->lang->line('label_bank_name')),

					'rules' => 'trim|required',

					'errors' => array(

										'required' => $this->lang->line('error_enter_bank_name')

								)

				),

				array(

					'field' => 'bank_code',

					'label' => strtolower($this->lang->line('label_code')),

					'rules' => 'trim',

					'errors' => array(

							'required' => $this->lang->line('error_enter_bank_code')

					)

				)

			);		

						

			$this->form_validation->set_rules($config);

			$this->form_validation->set_error_delimiters('', '');

			

			//Form validation

			if ($this->form_validation->run() == TRUE)

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

					$newData = $this->bank_model->add_bank();

					

					if($this->session->userdata('user_group') == USER_GROUP_USER) 

					{

						$this->user_model->insert_log(LOG_BANK_ADD, $newData);

					}

					else

					{

						$this->account_model->insert_log(LOG_BANK_ADD, $newData);

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

				$json['msg']['currency_id_error'] = form_error('currency_id');

				$json['msg']['bank_name_error'] = form_error('bank_name');

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

		if(permission_validation(PERMISSION_BANK_UPDATE) == TRUE)

		{

			$data = $this->bank_model->get_bank_data($id);

			$data['currencies_list'] = $this->currencies_model->get_currencies_list();

			$this->load->view('bank_update', $data);

		}

		else

		{

			redirect('home');

		}

	}

	

	public function update()

	{

		if(permission_validation(PERMISSION_BANK_UPDATE) == TRUE)

		{

			//Initial output data

			$json = array(

						'status' => EXIT_ERROR, 

						'msg' => array(

										'bank_name_error' => '',

										'currency_id_error' =>  '',

										'general_error' => ''

									),	

						'csrfTokenName' => $this->security->get_csrf_token_name(), 

						'csrfHash' => $this->security->get_csrf_hash()

					);

			

			//Set form rules

			$config = array(

				array(

						'field' => 'currency_id',

						'label' => strtolower($this->lang->line('label_currency_code')),

						'rules' => 'trim|required|integer',

						'errors' => array(

								'required' => $this->lang->line('error_select_currencies'),

								'integer' => $this->lang->line('error_select_currencies')

						)

				),

				array(

						'field' => 'bank_name',

						'label' => strtolower($this->lang->line('label_bank_name')),

						'rules' => 'trim|required',

						'errors' => array(

											'required' => $this->lang->line('error_enter_bank_name')

									)

				)

			);		

						

			$this->form_validation->set_rules($config);

			$this->form_validation->set_error_delimiters('', '');

			

			//Form validation

			if ($this->form_validation->run() == TRUE)

			{

				$bank_id = trim($this->input->post('bank_id', TRUE));

				$oldData = $this->bank_model->get_bank_data($bank_id);

				$currency_id = trim($this->input->post('currency_id', TRUE));

				$currencyData = $this->currencies_model->get_currencies_data($currency_id);

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

						$newData = $this->bank_model->update_bank($bank_id);

						

						if($this->session->userdata('user_group') == USER_GROUP_USER) 

						{

							$this->user_model->insert_log(LOG_BANK_UPDATE, $newData, $oldData);

						}

						else

						{

							$this->account_model->insert_log(LOG_BANK_UPDATE, $newData, $oldData);

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

								'id' => $newData['bank_id'],

								'bank_name' => $newData['bank_name'],

								'bank_code' => $newData['bank_code'],

								'currency_code' => $currencyData['currency_code'],

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

				}

				else

				{

					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');

				}	

			}

			else 

			{

				$json['msg']['currency_id_error'] = form_error('currency_id');

				$json['msg']['bank_name_error'] = form_error('bank_name');

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

					

		if(permission_validation(PERMISSION_BANK_DELETE) == TRUE)

		{

			$bank_id = $this->uri->segment(3);

			$oldData = $this->bank_model->get_bank_data($bank_id);

			

			if( ! empty($oldData))

			{

				//Database update

				$this->db->trans_start();

				$this->bank_model->delete_bank($bank_id);

				$this->bank_model->delete_bank_account_with_bank($bank_id);

				

				if($this->session->userdata('user_group') == USER_GROUP_USER) 

				{

					$this->user_model->insert_log(LOG_BANK_DELETE, $oldData);

				}

				else

				{

					$this->account_model->insert_log(LOG_BANK_DELETE, $oldData);

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

	

	public function account()

	{

		if(permission_validation(PERMISSION_BANK_ACCOUNT_VIEW) == TRUE)

		{

			$this->save_current_url('bank/account');

			

			$data['page_title'] = $this->lang->line('title_bank_account');

			$this->load->view('bank_account_view', $data);

		}

		else

		{

			redirect('home');

		}

	}

	

	public function account_listing()

    {

		if(permission_validation(PERMISSION_BANK_ACCOUNT_VIEW) == TRUE)

		{

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$order = $this->input->post("order", TRUE);

			

			//Table Columns

			$columns = array( 

								0 => 'bank_account_id',

								1 => 'bank_id',

								2 => 'bank_account_name',

								3 => 'bank_account_no',

								4 => 'daily_limit',

								5 => 'group_ids',

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

			

			$query = array(

							'select' => implode(',', $columns),

							'table' => 'bank_accounts',

							'limit' => $limit,

							'start' => $start,

							'order' => $order,

							'dir' => $dir,

					);

			

			$posts =  $this->general_model->all_posts($query);

			$totalFiltered = $this->general_model->all_posts_count($query);

			

			//Get bank name list

			$bank = $this->bank_model->get_bank_name();

			

			//Get group name list

			$group = $this->group_model->get_group_name(GROUP_BANK);



			//Prepare data

			$data = array();

			if(!empty($posts))

			{

				foreach ($posts as $post)

				{

					$row = array();

					$row[] = $post->bank_account_id;

					$row[] = '<span id="uc1_' . $post->bank_account_id . '">' . (isset($bank[$post->bank_id]) ? $bank[$post->bank_id] : '') . '</span>';

					$row[] = '<span id="uc2_' . $post->bank_account_id . '">' . $post->bank_account_name . '</span>';

					$row[] = '<span id="uc3_' . $post->bank_account_id . '">' . $post->bank_account_no . '</span>';

					$row[] = '<span id="uc4_' . $post->bank_account_id . '">' . $post->daily_limit . '</span>';

					

					$group_ids = '';

					$arr = explode(',', $post->group_ids);

					$arr = array_values(array_filter($arr));

					for($i=0;$i<sizeof($arr);$i++)

					{

						if( ! empty($arr[$i]))

						{

							$group_ids .= $group[$arr[$i]];

							

							if($i < (sizeof($arr) - 1))

							{

								$group_ids .= ', ';

							}

						}	

					}

					

					$row[] = '<span id="uc5_' . $post->bank_account_id . '">' . $group_ids . '</span>';

					

					switch($post->active)

					{

						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc6_' . $post->bank_account_id . '">' . $this->lang->line('status_active') . '</span>'; break;

						default: $row[] = '<span class="badge bg-secondary" id="uc6_' . $post->bank_account_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;

					}

					

					$row[] = '<span id="uc7_' . $post->bank_account_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';

					$row[] = '<span id="uc8_' . $post->bank_account_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';

					

					$button = '';

					if(permission_validation(PERMISSION_BANK_ACCOUNT_UPDATE) == TRUE)

					{

						$button .= '<i onclick="updateData(' . $post->bank_account_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';

					}

					

					if(permission_validation(PERMISSION_BANK_ACCOUNT_DELETE) == TRUE)

					{

						$button .= '<i onclick="deleteData(' . $post->bank_account_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';

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

	

	public function account_add()

    {

		if(permission_validation(PERMISSION_BANK_ACCOUNT_ADD) == TRUE)

		{

			$data['bank_list'] = $this->bank_model->get_bank_list();

			$data['group_list'] = $this->group_model->get_group_list(GROUP_BANK);

			$this->load->view('bank_account_add', $data);

		}

		else

		{

			redirect('home');

		}

	}

	

	public function account_submit()

	{

		if(permission_validation(PERMISSION_BANK_ACCOUNT_ADD) == TRUE)

		{

			//Initial output data

			$json = array(

						'status' => EXIT_ERROR, 

						'msg' => array(

										'bank_id_error' => '',

										'bank_account_name_error' => '',

										'bank_account_no_error' => '',

										'daily_limit_error' => '',

										'general_error' => ''

									), 		

						'csrfTokenName' => $this->security->get_csrf_token_name(), 

						'csrfHash' => $this->security->get_csrf_hash()

					);

			

			//Set form rules

			$config = array(

							array(

									'field' => 'bank_id',

									'label' => strtolower($this->lang->line('label_bank_name')),

									'rules' => 'trim|required|integer',

									'errors' => array(

														'required' => $this->lang->line('error_select_bank_name'),

														'integer' => $this->lang->line('error_select_bank_name')

												)

							),

							array(

									'field' => 'bank_account_name',

									'label' => strtolower($this->lang->line('label_bank_account_name')),

									'rules' => 'trim|required|max_length[64]',

									'errors' => array(

										'max_length' => $this->lang->line('error_invalid_username'),

										'required' => $this->lang->line('error_enter_bank_account_name')

								)

							),

							array(

									'field' => 'bank_account_no',

									'label' => strtolower($this->lang->line('label_bank_account_no')),

									'rules' => 'trim|required|max_length[24]',

									'errors' => array(

										'max_length' => $this->lang->line('error_invalid_username'),

										'required' => $this->lang->line('error_enter_bank_account_no'),

									)

							),

							array(

									'field' => 'daily_limit',

									'label' => strtolower($this->lang->line('label_daily_limit')),

									'rules' => 'trim|required|integer',

									'errors' => array(

														'required' => $this->lang->line('error_enter_daily_limit'),

														'integer' => $this->lang->line('error_only_digits_allowed')

												)

							)

						);		

						

			$this->form_validation->set_rules($config);

			$this->form_validation->set_error_delimiters('', '');

			

			// Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$upload_path = FCPATH . 'uploads/files/';
			
				// check if uploads/files exists, if not, create it
				if (!is_dir($upload_path)) {
					if (!mkdir($upload_path, 0777, true)) {
						$json['msg']['general_error'] = 'It is not possible to create the directory uploads/files/';
					}
				}
			
				// check if uploads/files exists, if not, create it
				if (!empty($_FILES['qr_image']['name'])) {
					// Handle file upload
					$config['upload_path'] = $upload_path;
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['max_size'] = 1024;
			
					$this->load->library('upload', $config);
			
					if ($this->upload->do_upload('qr_image')) {
						$uploaded_data = $this->upload->data();
						$qrImagePath = $uploaded_data['file_name'];
					} else {
						$json['msg']['general_error'] = $this->upload->display_errors();
					}
				} else {
					$qrImagePath = '';
				}
			
				// Database update
				$this->db->trans_start();
			
				$newData = $this->bank_model->add_bank_account($qrImagePath);
			
				$this->db->trans_complete();
			
				if ($this->db->trans_status() === TRUE) {
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('success_added');
				} else {
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');
				}
			}

			else 

			{

				$json['msg']['bank_id_error'] = form_error('bank_id');

				$json['msg']['bank_account_name_error'] = form_error('bank_account_name');

				$json['msg']['bank_account_no_error'] = form_error('bank_account_no');

				$json['msg']['daily_limit_error'] = form_error('daily_limit');

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

	public function playerBankImageEdit($id = NULL)
    {

		if(permission_validation(PERMISSION_BANK_PLAYER_USER_UPDATE) == TRUE)
		{
			$data = $this->bank_model->getBankImageData($id);

			$this->load->view('bank_image_update', $data);
		}
		else
		{
			redirect('home');
		}
	}
	
	public function playerBankImageEditData($id = NULL)
    {
		if(permission_validation(PERMISSION_BANK_PLAYER_USER_UPDATE) == TRUE)
		{
			$data = $this->bank_model->getBankImageData($id);

			$playerBankImageType = $this->lang->line(get_player_bank_type($data['player_bank_image_type']));

			$data['player_bank_image_type'] = $playerBankImageType;

			$playerData = $this->player_model->get_player_data($data['player_id']);

			$data['user_name'] = $playerData['username'];

			$this->load->view('bank_player_image_update', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function playerBankImagUpdateData(){

		if(permission_validation(PERMISSION_BANK_PLAYER_USER_UPDATE) == TRUE)
		{
			$player_bank_image_id = trim($this->input->post('player_bank_image_id', TRUE));
			$oldData = $this->bank_model->getBankImageData($player_bank_image_id);
			
			if(!empty($oldData))
			{
				$this->db->trans_start();

				$newData = $this->bank_model->update_player_bank_image($player_bank_image_id);

				$this->db->trans_complete();

				if ($this->db->trans_status() === TRUE)
				{
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('success_updated');
					//Prepare for ajax update
					$json['response'] = array(
						'player_bank_image_id' => $newData['player_bank_image_id'],
						'active' => $newData['active'],
						'verify' => $newData['verify'],
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

			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();	
		}
	}

	public function account_edit($id = NULL)

    {

		if(permission_validation(PERMISSION_BANK_ACCOUNT_UPDATE) == TRUE)

		{

			$data = $this->bank_model->get_bank_account_data($id);

			$data['bank_list'] = $this->bank_model->get_bank_list();

			$data['group_list'] = $this->group_model->get_group_list(GROUP_BANK);

			$this->load->view('bank_account_update', $data);

		}

		else

		{

			redirect('home');

		}

	}

	public function account_update()

	{

		if(permission_validation(PERMISSION_BANK_ACCOUNT_UPDATE) == TRUE)

		{

			//Initial output data

			$json = array(

						'status' => EXIT_ERROR, 

						'msg' => array(

										'bank_id_error' => '',

										'bank_account_name_error' => '',

										'bank_account_no_error' => '',

										'daily_limit_error' => '',

										'general_error' => ''

									),	

						'csrfTokenName' => $this->security->get_csrf_token_name(), 

						'csrfHash' => $this->security->get_csrf_hash()

					);

			

			//Set form rules

			$config = array(

							array(

									'field' => 'bank_id',

									'label' => strtolower($this->lang->line('label_bank_name')),

									'rules' => 'trim|required|integer',

									'errors' => array(

														'required' => $this->lang->line('error_select_bank'),

														'integer' => $this->lang->line('error_select_bank')

												)

							),

							array(

									'field' => 'bank_account_name',

									'label' => strtolower($this->lang->line('label_bank_account_name')),

									'rules' => 'trim|required|max_length[64]',

									'errors' => array(

										'max_length' => $this->lang->line('error_invalid_username'),

										'required' => $this->lang->line('error_enter_bank_account_name')

								)

							),

							array(

									'field' => 'bank_account_no',

									'label' => strtolower($this->lang->line('label_bank_account_no')),

									'rules' => 'trim|required|max_length[24]',

									'errors' => array(

										'max_length' => $this->lang->line('error_invalid_username'),

										'required' => $this->lang->line('error_enter_bank_account_no'),

									)

							),

							array(

									'field' => 'daily_limit',

									'label' => strtolower($this->lang->line('label_daily_limit')),

									'rules' => 'trim|required|integer',

									'errors' => array(

														'required' => $this->lang->line('error_enter_daily_limit'),

														'integer' => $this->lang->line('error_only_digits_allowed')

												)

							)

						);		

						

			$this->form_validation->set_rules($config);

			$this->form_validation->set_error_delimiters('', '');

			

			//Form validation

			if ($this->form_validation->run() == TRUE)

			{
				// Check if the user uploaded a new image
				if (!empty($_FILES['qr_image']['name']))
				{
					$config['upload_path'] = FCPATH . 'uploads/files/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['max_size'] = 1024;
			
					$this->load->library('upload', $config);
			
					if ($this->upload->do_upload('qr_image'))
					{
						$uploaded_data = $this->upload->data();
						$qrImagePath = $uploaded_data['file_name'];
					}
					else
					{
						$json['msg']['general_error'] = $this->upload->display_errors();
						// Return early to avoid further processing
						$this->output
							->set_status_header(200)
							->set_content_type('application/json', 'utf-8')
							->set_output(json_encode($json))
							->_display();
						exit();
					}
				}
				else
				{
					$bank_account_id = trim($this->input->post('bank_account_id', TRUE));
					$oldData = $this->bank_model->get_bank_account_data($bank_account_id);
					$qrImagePath = $oldData['qr_image'];
				}

				$bank_account_id = trim($this->input->post('bank_account_id', TRUE));

				$oldData = $this->bank_model->get_bank_account_data($bank_account_id);

				

				if( ! empty($oldData))

				{

					//Database update

					$this->db->trans_start();

					$newData = $this->bank_model->update_bank_account($bank_account_id, $qrImagePath);

					

					if($this->session->userdata('user_group') == USER_GROUP_USER) 

					{

						$this->user_model->insert_log(LOG_BANK_ACCOUNT_UPDATE, $newData, $oldData);

					}

					else

					{

						$this->account_model->insert_log(LOG_BANK_ACCOUNT_UPDATE, $newData, $oldData);

					}

					

					$this->db->trans_complete();

					

					if ($this->db->trans_status() === TRUE)

					{

						$json['status'] = EXIT_SUCCESS;

						$json['msg'] = $this->lang->line('success_updated');

						

						//Get bank name list

						$bank = $this->bank_model->get_bank_name();

						

						//Get group name list

						$group = $this->group_model->get_group_name(GROUP_BANK);

						

						$group_ids = '';

						$arr = explode(',', $newData['group_ids']);

						for($i=0;$i<sizeof($arr);$i++)

						{

							if( ! empty($arr[$i]))

							{

								$group_ids .= $group[$arr[$i]];

								

								if($i < (sizeof($arr) - 1))

								{

									$group_ids .= ', ';

								}

							}	

						}

						

						//Prepare for ajax update

						$json['response'] = array(

												'id' => $newData['bank_account_id'],

												'bank_id' => (isset($bank[$newData['bank_id']]) ? $bank[$newData['bank_id']] : ''),

												'bank_account_name' => $newData['bank_account_name'],

												'bank_account_no' => $newData['bank_account_no'],

												'bank_reference' => $newData['bank_reference'],

												'daily_limit' => $newData['daily_limit'],

												'group_ids' => $group_ids,

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

				$json['msg']['bank_id_error'] = form_error('bank_id');

				$json['msg']['bank_account_name_error'] = form_error('bank_account_name');

				$json['msg']['bank_account_no_error'] = form_error('bank_account_no');

				$json['msg']['daily_limit_error'] = form_error('daily_limit');

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

	public function account_delete()

    {

		//Initial output data

		$json = array(

					'status' => EXIT_ERROR, 

					'msg' => ''

				);

					

		if(permission_validation(PERMISSION_BANK_ACCOUNT_DELETE) == TRUE)

		{

			$bank_account_id = $this->uri->segment(3);

			$oldData = $this->bank_model->get_bank_account_data($bank_account_id);

			

			if( ! empty($oldData))

			{

				//Database update

				$this->db->trans_start();

				$this->bank_model->delete_bank_account($bank_account_id);

				

				if($this->session->userdata('user_group') == USER_GROUP_USER) 

				{

					$this->user_model->insert_log(LOG_BANK_ACCOUNT_DELETE, $oldData);

				}

				else

				{

					$this->account_model->insert_log(LOG_BANK_ACCOUNT_DELETE, $oldData);

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



	public function player() {

		if(permission_validation(PERMISSION_BANK_PLAYER_USER_VIEW) == TRUE) {

			$this->save_current_url('bank/player');

			$data = quick_search();

			$data['page_title'] = $this->lang->line('title_bank_player');

			$this->session->unset_userdata('searches_player_bank');

			$data_search = array(

				'from_date' => date('Y-m-d 00:00:00'),

				'to_date' => date('Y-m-d 23:59:59'),

				'username' => "",

				'status' => "-1",

				'bank_name' => "",

				'bank_account_name' => '',

				'bank_account_no' => '',

			);

			$this->session->set_userdata('searches_player_bank', $data_search);

			$this->load->view('bank_player_view', $data);

		}

		else {

			redirect('home');

		}

	}

	public function bankPlayerImage() {
		if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE) == TRUE) {

			$this->save_current_url('bank/bankplayerimage');

			$data = quick_search();

			$data['page_title'] = $this->lang->line('title_bank_player_image');

			$this->session->unset_userdata('searches_player_bank');

			$data_search = array(

				'from_date' => date('Y-m-d 00:00:00'),

				'to_date' => date('Y-m-d 23:59:59'),

				'username' => "",

				'status' => "-1",

				'bank_name' => "",

				'bank_account_name' => '',

				'bank_account_no' => '',

			);

			$this->session->set_userdata('searches_player_bank', $data_search);

			$this->load->view('bank_player_image_view', $data);

		}
		else {
			redirect('home');
		}
	}
	
	public function playerImageSearch(){

		if(permission_validation(PERMISSION_BANK_PLAYER_USER_VIEW) == TRUE) {

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

			$config = array();

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

			$this->form_validation->set_rules($config);

			$this->form_validation->set_error_delimiters('', '');

			$is_allow = true;

			if(!empty($config)){

				$this->form_validation->set_rules($config);

				$this->form_validation->set_error_delimiters('', '');

				if ($this->form_validation->run() == TRUE)

				{



				}else{

					$is_allow = false;

					$json['msg']['from_date_error'] = form_error('from_date');

					$json['msg']['to_date_error'] = form_error('to_date');

				}

			}

			//Form validation

			if($is_allow){

				$data = array( 

					'from_date' => trim($this->input->post('from_date', TRUE)),

					'to_date' => trim($this->input->post('to_date', TRUE)),

					'full_name' => trim($this->input->post('full_name', TRUE)),

					'status' => trim($this->input->post('status', TRUE)),

					'verify' => trim($this->input->post('verify', TRUE)),

					'birthday' => trim($this->input->post('birthday', TRUE)),

					'message' => trim($this->input->post('message', TRUE)),

					'player_bank_image_type' => trim($this->input->post('player_bank_image_type', TRUE)),

				);

				$this->session->set_userdata('searches_player_bank', $data);

				$json['status'] = EXIT_SUCCESS;

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

	public function player_search(){

		if(permission_validation(PERMISSION_BANK_PLAYER_USER_VIEW) == TRUE) {

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

			$config = array();

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

			$this->form_validation->set_rules($config);

			$this->form_validation->set_error_delimiters('', '');

			$is_allow = true;

			if(!empty($config)){

				$this->form_validation->set_rules($config);

				$this->form_validation->set_error_delimiters('', '');

				if ($this->form_validation->run() == TRUE)

				{



				}else{

					$is_allow = false;

					$json['msg']['from_date_error'] = form_error('from_date');

					$json['msg']['to_date_error'] = form_error('to_date');

				}

			}

			//Form validation

			if($is_allow){

				$data = array( 

					'from_date' => trim($this->input->post('from_date', TRUE)),

					'to_date' => trim($this->input->post('to_date', TRUE)),

					'username' => trim($this->input->post('username', TRUE)),

					'status' => trim($this->input->post('status', TRUE)),

					'verify' => trim($this->input->post('verify', TRUE)),

					'bank_name' => trim($this->input->post('bank_name', TRUE)),

					'bank_account_name' => trim($this->input->post('bank_account_name', TRUE)),

					'bank_account_no' => trim($this->input->post('bank_account_no', TRUE)),

					'player_bank_type' => trim($this->input->post('player_bank_type', TRUE)),

				);

				$this->session->set_userdata('searches_player_bank', $data);

				$json['status'] = EXIT_SUCCESS;

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



	public function player_listing() {

		if(permission_validation(PERMISSION_BANK_PLAYER_USER_VIEW) == TRUE) {

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$order = $this->input->post("order", TRUE);

			

			//Table Columns

			$columns = array( 

				0 => 'a.player_bank_id',

				1 => 'a.username',

				2 => 'a.player_bank_type',

				3 => 'b.bank_name',

				4 => 'a.bank_account_name',

				5 => 'a.bank_account_no',

				6 => 'a.bank_account_address',

				7 => 'a.withdrawal_count',

				8 => 'a.active',

				9 => 'a.verify',

				10 => 'a.updated_by',

				11 => 'a.updated_date',

			);

			

			$col = 0;

			$dir = "";

			

			if( ! empty($order)) {

				foreach($order as $o) {

					$col = $o['column'];

					$dir = $o['dir'];

				}

			}



			if($dir != 'asc' && $dir != 'desc') {

				$dir = 'desc';

			}

			

			if( ! isset($columns[$col]))

			{

				$order = $columns[0];

			}

			else

			{

				$order = $columns[$col];

			}

			

			$arr = $this->session->userdata('searches_player_bank');

			$where = "";

			if( ! empty($arr['from_date']))

			{

				$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);

			}

			if( ! empty($arr['username']))

			{

				$where .= " AND a.username = '" . $arr['username']."'";

			}



			if( ! empty($arr['bank_name']))

			{

				$where .= " AND b.bank_name = '" . $arr['bank_name']."'";

			}

			

			if( ! empty($arr['bank_account_name']))

			{

				$where .= " AND a.bank_account_name = '" . $arr['bank_account_name']."'";

			}

			

			if( ! empty($arr['bank_account_no']))

			{

				$where .= " AND a.bank_account_no = '" . $arr['bank_account_no']."'";

			}



			if(isset($arr['status'])){

				if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_INACTIVE)

				{

					$where .= ' AND a.active = ' . $arr['status'];

				}

			}



			if(isset($arr['verify'])){

				if($arr['verify'] == STATUS_VERIFY OR $arr['verify'] == STATUS_UNVERIFY)

				{

					$where .= ' AND a.verify = ' . $arr['verify'];

				}

			}



			if( ! empty($arr['player_bank_type'])){

				$where .= ' AND a.player_bank_type = ' . $arr['player_bank_type'];

			}

			$select = implode(',', $columns);

			$dbprefix = $this->db->dbprefix;



			$posts = NULL;

			$query_string = "SELECT {$select} FROM {$dbprefix}player_bank a,{$dbprefix}banks b  WHERE (a.bank_id = b.bank_id) $where";

			$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";

			$query = $this->db->query($query_string . $query_string_2);

			if($query->num_rows() > 0) {

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

					$row[] = $post->player_bank_id;

					$row[] = $post->username;

					$row[] = '<span id="uc10_' . $post->player_bank_id . '">' . $this->lang->line(get_player_bank_type($post->player_bank_type)) . '</span>';

					$row[] = '<span id="uc1_' . $post->player_bank_id . '">' . $post->bank_name . '</span>';

					$row[] = '<span id="uc2_' . $post->player_bank_id . '">' . $post->bank_account_name . '</span>';

					$row[] = '<span id="uc3_' . $post->player_bank_id . '">' . $post->bank_account_no . '</span>';

					$row[] = '<span id="uc8_' . $post->player_bank_id . '">' . $post->bank_account_address . '</span>';

					$row[] = '<span id="uc11_' . $post->player_bank_id . '">' . $post->withdrawal_count . '</span>';

					switch($post->active)

					{

						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc4_' . $post->player_bank_id . '">' . $this->lang->line('status_active') . '</span>'; break;

						default: $row[] = '<span class="badge bg-secondary" id="uc4_' . $post->player_bank_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;

					}

					switch($post->verify)

					{

						case STATUS_VERIFY: $row[] = '<span class="badge bg-success" id="uc7_' . $post->player_bank_id . '">' . $this->lang->line('status_verify') . '</span>'; break;

						default: $row[] = '<span class="badge bg-secondary" id="uc7_' . $post->player_bank_id . '">' . $this->lang->line('status_unverify') . '</span>'; break;

					}

					

					$row[] = '<span id="uc5_' . $post->player_bank_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';

					$row[] = '<span id="uc6_' . $post->player_bank_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';

					

					$button = '';

					if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE) == TRUE)

					{

						$button .= '<i onclick="viewImageData(' . $post->player_bank_id . ')" class="fas fa-id-card nav-icon text-warning" title="' . $this->lang->line('button_view')  . '"></i> &nbsp;&nbsp;';

					}



					if(permission_validation(PERMISSION_BANK_PLAYER_USER_UPDATE) == TRUE)

					{

						$button .= '<i onclick="updateData(' . $post->player_bank_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp;';

					}



					if(permission_validation(PERMISSION_BANK_PLAYER_USER_DELETE) == TRUE)

					{

						$button .= '<i onclick="deleteData(' . $post->player_bank_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i> &nbsp;&nbsp;';

					}



					if(permission_validation(PERMISSION_PLAYER_BANK_WITHDRAWAL_COUNT_UDPATE) == TRUE)

					{

						$button .= '<i onclick="playerBankWithdrawalCountUpdate(' . $post->player_bank_id . ')" class="fas fa-pen-nib nav-icon text-secondary" title="' . $this->lang->line('label_withdrawal_count_update')  . '"></i> &nbsp;&nbsp;';

					}

					

					if( ! empty($button))

					{

						$row[] = $button;

					}

					

					$data[] = $row;

				}

			}

			

			#Output

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

	public function playerListingImage() {

		if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE) == TRUE) {

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$order = $this->input->post("order", TRUE);

			//Table Columns

			$columns = array( 

				0 => 'a.player_bank_image_id',

				1 => 'a.full_name',

				2 => 'a.player_bank_image_type',

				3 => 'a.player_bank_image_name',

				4 => 'b.player_id',

				5 => 'a.card_id',

				6 => 'a.birthday',

				7 => 'a.message',

				8 => 'a.active',

				9 => 'a.verify',

				// 10 => 'a.updated_by',

			);

			$col = 0;

			$dir = "";

			if( ! empty($order)) {

				foreach($order as $o) {

					$col = $o['column'];

					$dir = $o['dir'];

				}

			}

			if($dir != 'asc' && $dir != 'desc') {

				$dir = 'desc';

			}

			if( ! isset($columns[$col]))

			{

				$order = $columns[0];

			}

			else

			{

				$order = $columns[$col];

			}

			$arr = $this->session->userdata('searches_player_bank');

			$where = "";

			if( ! empty($arr['from_date']))

			{

				$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);

			}

			if( ! empty($arr['to_date']))

			{

				$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);

			}

			if( ! empty($arr['full_name']))

			{
				$where .= " AND a.full_name LIKE '%" . $arr['full_name'] . "%' ESCAPE '!'";
			}

			if( ! empty($arr['card_id']))

			{

				$where .= " AND a.card_id = '" . $arr['card_id']."'";

			}

			if( ! empty($arr['birthday']))

			{
				$where .= ' AND a.birthday >= ' . strtotime($arr['birthday']);

			}

			if( ! empty($arr['message']))

			{

				$where .= " AND a.message = '" . $arr['message']."'";

			}

			if(isset($arr['status'])){

				if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_INACTIVE)

				{

					$where .= ' AND a.active = ' . $arr['status'];

				}

			}

			if(isset($arr['verify'])){

				if($arr['verify'] == STATUS_VERIFY OR $arr['verify'] == STATUS_UNVERIFY)

				{

					$where .= ' AND a.verify = ' . $arr['verify'];

				}

			}

			if( ! empty($arr['player_bank_image_type'])){

				$where .= ' AND a.player_bank_image_type = ' . $arr['player_bank_image_type'];

			}

			$select = implode(',', $columns);

			$dbprefix = $this->db->dbprefix;

			$posts = NULL;

			
			// maping player_id to username
			$sql = "SELECT player_id, username FROM {$dbprefix}players";
			$query = $this->db->query($sql);
			$playerList = array();
			if($query->num_rows() > 0)
			{
				$playerList = $query->result();  
			}
			$query->free_result();
			$arrPlayer = array();
			foreach($playerList as $playerItem){
				$arrPlayer[$playerItem->player_id] = $playerItem->username;
			}

			$query_string = "(SELECT {$select} FROM {$dbprefix}player_bank_image a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";

			$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			
			$query = $this->db->query($query_string . $query_string_2);

			if($query->num_rows() > 0) {

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

					$button = '';

					$row[] = $post->player_bank_image_id;

					$row[] = $post->full_name;

					$row[] = '<span id="uc10_' . $post->player_bank_image_id . '">' . $this->lang->line(get_player_bank_type($post->player_bank_image_type)) . '</span>';

					$row[] = '<span id="uc1_' . $post->player_bank_image_id . '">' . $button .= '<i onclick="updateBankImage(' . $post->player_bank_image_id . ')" class="fas fa-image" title="' . $this->lang->line('button_edit')  . '"></i> ' . '</span>';

					$row[] = '<span id="uc2_' . $post->player_bank_image_id . '">' . $arrPlayer[$post->player_id] . '</span>';

					$row[] = '<span id="uc3_' . $post->player_bank_image_id . '">' . $post->card_id . '</span>';

					$row[] = '<span id="uc8_' . $post->player_bank_image_id . '">' . (($post->birthday > 0) ? date('Y-m-d', $post->birthday) : '-') . '</span>';

					$row[] = '<span id="uc11_' . $post->player_bank_image_id . '">' . $post->message . '</span>';

					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc4_' . $post->player_bank_image_id . '">' . $this->lang->line('status_active') . '</span>'; break;

						default: $row[] = '<span class="badge bg-secondary" id="uc4_' . $post->player_bank_image_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;

					}

					switch($post->verify)
					{
						case STATUS_VERIFY: $row[] = '<span class="badge bg-success" id="uc7_' . $post->player_bank_image_id . '">' . $this->lang->line('status_verify') . '</span>'; break;

						default: $row[] = '<span class="badge bg-secondary" id="uc7_' . $post->player_bank_image_id . '">' . $this->lang->line('status_unverify') . '</span>'; break;

					}

					$buttonEdit = '';

					if(permission_validation(PERMISSION_BANK_PLAYER_USER_UPDATE) == TRUE)
					{
						$buttonEdit .= '<i onclick="updateData(' . $post->player_bank_image_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i>';
					}
					if( ! empty($buttonEdit))
					{
						$row[] = $buttonEdit;
					}

					$data[] = $row;
				}
			}

			#Output

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

	public function player_add($id = NULL)

    {

    	if(permission_validation(PERMISSION_BANK_PLAYER_USER_ADD) == TRUE)

		{

			if(!empty($id)){

    			$data = $this->player_model->get_player_data($id);

    			$data['bank_list'] = $this->bank_model->get_bank_list();

				$this->load->view('bank_player_add',$data);

	    	}else{

	    		$data['bank_list'] = $this->bank_model->get_bank_list();

				$this->load->view('bank_player_add',$data);

	    	}

		}

		else

		{

			redirect('home');

		}

	}

	

	public function player_submit()

	{

		if(permission_validation(PERMISSION_BANK_PLAYER_USER_ADD) == TRUE)

		{

			//Initial output data

			$json = array(

						'status' => EXIT_ERROR, 

						'msg' => array(

										'bank_id_error' => '',

										'bank_account_name_error' => '',

										'bank_account_no_error' => '',

										'player_bank_type_error' => '',

										'username_error' => '',

										'general_error' => ''

									), 		

						'csrfTokenName' => $this->security->get_csrf_token_name(), 

						'csrfHash' => $this->security->get_csrf_hash()

					);

			

			//Set form rules

			$config = array(

				array(

						'field' => 'bank_id',

						'label' => strtolower($this->lang->line('label_bank_name')),

						'rules' => 'trim|required|integer',

						'errors' => array(

											'required' => $this->lang->line('error_enter_bank_name'),

											'integer' => $this->lang->line('error_enter_bank_name')

									)

				),

				array(

						'field' => 'username',

						'label' => strtolower($this->lang->line('label_username')),

						'rules' => 'trim|required',

						'errors' => array(

								'required' => $this->lang->line('error_enter_username'),

						)

				),

				array(

						'field' => 'bank_account_name',

						'label' => strtolower($this->lang->line('label_bank_account_name')),

						'rules' => 'trim|required|max_length[64]',

						'errors' => array(

							'max_length' => $this->lang->line('error_invalid_username'),

							'required' => $this->lang->line('error_enter_bank_account_name')

					)

				),

				array(

						'field' => 'bank_account_no',

						'label' => strtolower($this->lang->line('label_bank_account_no')),

						'rules' => 'trim|required|max_length[24]|is_unique[player_bank.bank_account_no]',

						'errors' => array(

							'max_length' => $this->lang->line('error_invalid_username'),

							'required' => $this->lang->line('error_enter_bank_account_no'),

							'is_unique' => $this->lang->line('error_username_already_exits')

						)

				),

				array(

						'field' => 'bank_account_name',

						'label' => strtolower($this->lang->line('label_bank_account_name')),

						'rules' => 'trim|required|max_length[64]',

						'errors' => array(

							'max_length' => $this->lang->line('error_invalid_username'),

							'required' => $this->lang->line('error_enter_bank_account_name')

					)

				),

				array(

						'field' => 'bank_account_address',

						'label' => strtolower($this->lang->line('label_bank_account_address')),

						'rules' => 'trim|max_length[64]',

						'errors' => array(

							'max_length' => $this->lang->line('error_invalid_username'),

					)

				),

				array(

						'field' => 'player_bank_type',

						'label' => strtolower($this->lang->line('label_type')),

						'rules' => 'trim|required|integer',

						'errors' => array(

							'required' => $this->lang->line('error_select_type'),

							'integer' => $this->lang->line('error_select_type')

					)

				),

			);		

						

			$this->form_validation->set_rules($config);

			$this->form_validation->set_error_delimiters('', '');

			

			//Form validation

			if ($this->form_validation->run() == TRUE)

			{

				$player_username = trim($this->input->post('username', TRUE));

				$playerData = $this->player_model->get_player_data_by_username($player_username);

				if(!empty($playerData)){

					$miscellaneous = $this->miscellaneous_model->get_miscellaneous();

					$player_bank_type = $this->input->post('player_bank_type', TRUE);

					/*

					$player_bank_max = $this->bank_model->get_player_bank_account_quantity($playerData,$player_bank_type);

					if($player_bank_type == BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK){

						$player_account_max = $miscellaneous['player_bank_account_max'];

					}else{

						$player_account_max = $miscellaneous['player_credit_card_max'];

					}

					*/

					$player_account_max = 10000000000000000;

					if($player_bank_max < $player_account_max){

						//Database update

						$this->db->trans_start();

						$newData = $this->bank_model->add_player_bank_account($playerData);

						$this->player_model->update_player_bank_account_name($playerData['player_id'],$newData['bank_account_name']);

						if($this->session->userdata('user_group') == USER_GROUP_USER) 

						{

							$this->user_model->insert_log(LOG_BANK_PLAYER_USER_ADD, $newData);

						}

						else

						{

							$this->account_model->insert_log(LOG_BANK_PLAYER_USER_ADD, $newData);

						}

						

						$this->db->trans_complete();

						

						if ($this->db->trans_status() === TRUE)

						{

							$player_bank_account_data_json = array();

							$bank_ids = array();

							$player_bank_data = $this->bank_model->get_player_bank_data_by_player_array(array($playerData['player_id']));

							$player_bank_account_data = ((isset($player_bank_data[$playerData['player_id']][BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK]))? $player_bank_data[$playerData['player_id']][BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK] : array());

							if(!empty($player_bank_account_data)){

								foreach($player_bank_account_data as $player_bank_row){

									$bank_ids[] = $player_bank_row['bank_id'];

								}

								$bank_list = $this->bank_model->get_all_bank_name_by_bank_id($bank_ids);

							}



							if(!empty($player_bank_account_data)){

								foreach($player_bank_account_data  as $player_bank_row){

									$bank_name = ((isset($bank_list[$player_bank_row['bank_id']])) ? $bank_list[$player_bank_row['bank_id']] : '');

									$temp_array = array(

										'player_bank_id' => $player_bank_row['player_bank_id'],

										'player_id' => $player_bank_row['player_id'],

										'bank_id' => $player_bank_row['bank_id'],

										'bank_account_no' => $player_bank_row['bank_account_no'],

										'bank_account_name' => $player_bank_row['bank_account_name'],

										'active' => $player_bank_row['active'],

										'verify' => $player_bank_row['verify'],

										'player_bank_type' => $player_bank_row['player_bank_type'],

										'bank_name' => $bank_name,

									);

									$player_bank_account_data_json[] = $temp_array;

								}

							}



							$json['status'] = EXIT_SUCCESS;

							$json['msg'] = $this->lang->line('success_added');

							$json['response'] = array(

	    						'id' => $newData['player_bank_id'],

	    						'player_id' => $newData['player_id'],

	    						'player_bank_type' => $newData['player_bank_type'],

	    						'bank_id' => $newData['bank_id'],

	    						'bank_account_name' => $newData['bank_account_name'],

	    						'bank_account_no' => $newData['bank_account_no'],

	    						'active' => $newData['active'],

	    						'verify' => $newData['verify'],

	    						'bank_name' => ((isset($bank_list[$newData['bank_id']])) ? $bank_list[$newData['bank_id']] : ''),

	    						'player_bank_data' => json_encode($player_bank_account_data_json,true),

	    					);

						}

						else

						{

							$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');

						}

					}else{

						$json['msg']['general_error'] = $this->lang->line('error_over_player_bank_account_max');

					}

				}else{

					$json['msg']['general_error'] = $this->lang->line('error_username_not_found');

				}

			}

			else 

			{

				$json['msg']['bank_id_error'] = form_error('bank_id');

				$json['msg']['bank_account_name_error'] = form_error('bank_account_name');

				$json['msg']['bank_account_no_error'] = form_error('bank_account_no');

				$json['msg']['daily_limit_error'] = form_error('daily_limit');

				$json['msg']['player_bank_type_error'] = form_error('player_bank_type');

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

	

	public function player_edit($id = NULL)

    {

		if(permission_validation(PERMISSION_BANK_PLAYER_USER_UPDATE) == TRUE)

		{

			$data = $this->bank_model->get_player_bank_data($id);

			$data['bank_list'] =  $this->bank_model->get_bank_list();

			$this->load->view('bank_player_update', $data);

		}

		else

		{

			redirect('home');

		}

	}



	public function player_edit_player($id = NULL){

		if(permission_validation(PERMISSION_BANK_PLAYER_USER_UPDATE) == TRUE)

		{

			$data = $this->bank_model->get_player_bank_data($id);

			$data['bank_list'] =  $this->bank_model->get_bank_list();

			$this->load->view('player_bank_player_update', $data);

		}

		else

		{

			redirect('home');

		}

	}

	

	public function player_update()

	{

		if(permission_validation(PERMISSION_BANK_PLAYER_USER_UPDATE) == TRUE)

		{

			//Initial output data

			$json = array(

						'status' => EXIT_ERROR, 

						'msg' => array(

							'bank_id_error' => '',

							'bank_account_name_error' => '',

							'bank_account_no_error' => '',

							'player_bank_type_error' => '',

							'general_error' => ''

						),	

						'csrfTokenName' => $this->security->get_csrf_token_name(), 

						'csrfHash' => $this->security->get_csrf_hash()

					);

			

			$player_bank_id = trim($this->input->post('player_bank_id', TRUE));

			$oldData = $this->bank_model->get_player_bank_data($player_bank_id);

			if( ! empty($oldData))

			{

				$player_data = $this->player_model->get_player_data($oldData['player_id']);

				if( ! empty($player_data))

				{

	    			//Set form rules

	    			$config = array(

						array(

								'field' => 'bank_id',

								'label' => strtolower($this->lang->line('label_bank_name')),

								'rules' => 'trim|required|integer',

								'errors' => array(

													'required' => $this->lang->line('error_select_bank'),

													'integer' => $this->lang->line('error_select_bank')

											)

						),

						array(

								'field' => 'bank_account_name',

								'label' => strtolower($this->lang->line('label_bank_account_name')),

								'rules' => 'trim|required|max_length[64]',

								'errors' => array(

									'max_length' => $this->lang->line('error_invalid_username'),

									'required' => $this->lang->line('error_enter_bank_account_name')

							)

						),

						array(

								'field' => 'bank_account_address',

								'label' => strtolower($this->lang->line('label_bank_account_address')),

								'rules' => 'trim|max_length[64]',

								'errors' => array(

									'max_length' => $this->lang->line('error_invalid_username'),

							)

						),

						array(

							'field' => 'player_bank_type',

							'label' => strtolower($this->lang->line('label_type')),

							'rules' => 'trim|required|integer',

							'errors' => array(

								'required' => $this->lang->line('error_select_type'),

								'integer' => $this->lang->line('error_select_type')

							)

						),

					);

					if($this->input->post('bank_account_no', TRUE) == $oldData['bank_account_no']){

	    				$configAdd = array(

								'field' => 'bank_account_no',

								'label' => strtolower($this->lang->line('label_bank_account_no')),

								'rules' => 'trim|required|max_length[24]',

								'errors' => array(

									'max_length' => $this->lang->line('error_invalid_username'),

									'required' => $this->lang->line('error_enter_bank_account_no'),

								)

						);

					}else{

					    $configAdd = array(

								'field' => 'bank_account_no',

								'label' => strtolower($this->lang->line('label_bank_account_no')),

								'rules' => 'trim|required|max_length[24]|is_unique[player_bank.bank_account_no]',

								'errors' => array(

									'max_length' => $this->lang->line('error_invalid_username'),

									'required' => $this->lang->line('error_enter_bank_account_no'),

									'is_unique' => $this->lang->line('error_username_already_exits')

								)

						);

					}

	    			$this->form_validation->set_rules($config);

	    			$this->form_validation->set_error_delimiters('', '');



	    			//Form validation

	    			if ($this->form_validation->run() == TRUE)

	    			{

	    				//Database update

	    				$this->db->trans_start();

	    				$newData = $this->bank_model->update_player_bank_account($player_bank_id);

	    				$this->player_model->update_player_bank_account_name($oldData['player_id'],$newData['bank_account_name']);

	    				if($this->session->userdata('user_group') == USER_GROUP_USER) 

	    				{

	    					$this->user_model->insert_log(LOG_BANK_PLAYER_USER_UPDATE, $newData, $oldData);

	    				}

	    				else

	    				{

	    					$this->account_model->insert_log(LOG_BANK_PLAYER_USER_UPDATE, $newData, $oldData);

	    				}

	    				

	    				$this->db->trans_complete();

	    				

	    				if ($this->db->trans_status() === TRUE)

	    				{

	    					$json['status'] = EXIT_SUCCESS;

	    					$json['msg'] = $this->lang->line('success_updated');

	    					

	    					

	    					$player_bank_account_data_json = array();

							$bank_ids = array();

							$player_bank_data = $this->bank_model->get_player_bank_data_by_player_array(array($oldData['player_id']));

							$player_bank_account_data = ((isset($player_bank_data[$oldData['player_id']][BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK]))? $player_bank_data[$oldData['player_id']][BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK] : array());

							if(!empty($player_bank_account_data)){

								foreach($player_bank_account_data as $player_bank_row){

									$bank_ids[] = $player_bank_row['bank_id'];

								}

								$bank_list = $this->bank_model->get_all_bank_name_by_bank_id($bank_ids);

							}



							if(!empty($player_bank_account_data)){

								foreach($player_bank_account_data  as $player_bank_row){

									$bank_name = ((isset($bank_list[$player_bank_row['bank_id']])) ? $bank_list[$player_bank_row['bank_id']] : '');

									$temp_array = array(

										'player_bank_id' => $player_bank_row['player_bank_id'],

										'player_id' => $player_bank_row['player_id'],

										'bank_id' => $player_bank_row['bank_id'],

										'bank_account_no' => $player_bank_row['bank_account_no'],

										'bank_account_name' => $player_bank_row['bank_account_name'],

										'active' => $player_bank_row['active'],

										'verify' => $player_bank_row['verify'],

										'player_bank_type' => $player_bank_row['player_bank_type'],

										'bank_name' => $bank_name,

									);

									$player_bank_account_data_json[] = $temp_array;

								}

							}



	    					//Prepare for ajax update

	    					$json['response'] = array(

	    						'id' => $newData['player_bank_id'],

	    						'player_id' => $oldData['player_id'],

	    						'bank_id' => (isset($bank_list[$newData['bank_id']]) ? $bank[$bank_list['bank_id']] : ''),

	    						'bank_account_name' => $newData['bank_account_name'],

	    						'bank_account_no' => $newData['bank_account_no'],

	    						'active' => (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),

	    						'verify' => (($newData['verify'] == STATUS_VERIFY) ? $this->lang->line('status_verify') : $this->lang->line('status_unverify')),

	    						'active_code' => $newData['active'],

	    						'verify_code' => $newData['verify'],

	    						'updated_by' => $newData['updated_by'],

	    						'player_bank_type' => $this->lang->line(get_player_bank_type($newData['player_bank_type'])),

	    						'player_bank_type_code' => $newData['player_bank_type'],

	    						'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),

	    						'player_bank_data' => json_encode($player_bank_account_data_json,true),

	    					);

	    					if($oldData['verify'] == STATUS_INACTIVE){

	    						if($newData['verify'] == STATUS_VERIFY){

	    							//Send System message

	    							if($newData['player_bank_type'] == BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK){

	    								$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_SUCCESS_VERIFY_PLAYER_BANK);

	    							}else{

	    								$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_SUCCESS_VERIFY_PLAYER_CREDIT_CARD);

	    							}



									if(!empty($system_message_data)){

										$system_message_id = $system_message_data['system_message_id']; 

										$oldLangData = $this->message_model->get_message_lang_data($system_message_id);

										$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);

										$create_time = time();

										$username = $player_data['username'];

										$array_key = array(

											'system_message_id' => $system_message_data['system_message_id'],

											'system_message_genre' => $system_message_data['system_message_genre'],

											'player_level' => "",

											'bank_channel' => "",

											'username' => $username,

										);

										$Bdatalang = array();

										$Bdata = array();

										$player_message_list = $this->message_model->get_player_all_data_by_message_genre($array_key);

										if(!empty($player_message_list)){

											if(sizeof($player_message_list)>0){

												foreach($player_message_list as $row){

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

											if( ! empty($Bdata))

											{

												$this->db->insert_batch('system_message_user', $Bdata);

											}



											$success_message_data = $this->message_model->get_message_bluk_data($system_message_id,$create_time);

											if(sizeof($lang)>0){

												if(!empty($player_message_list) && sizeof($player_message_list)>0){

													foreach($player_message_list as $player_message_list_row){

														if(isset($success_message_data[$player_message_list_row['player_id']])){

															foreach($lang as $k => $v){

																$replace_string_array = array(

																	SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $username,

																	SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),

																);



																$PBdataLang = array(

																	'system_message_user_id'	=> $success_message_data[$player_message_list_row['player_id']],

																	'system_message_title'		=> $oldLangData[$v]['system_message_title'],

																	'system_message_content'	=> get_system_message_content($oldLangData[$v]['system_message_content'],$replace_string_array),

																	'language_id' 				=> $v

																);

																array_push($Bdatalang, $PBdataLang);

															}

														}

													}	

												}

											}

											$this->db->insert_batch('system_message_user_lang', $Bdatalang);

										}

									}



									$promotion_add_list = array();

									$promotion_capture_list = array();

									$promotion_ids = array();

					    			if($player_data['username'] == $oldData['created_by']){

					    				if(!empty($player_data['referrer'])){

					    					$referral_data = $this->player_model->get_player_data_by_username($player_data['referrer']);

					    					if(!empty($referral_data)){

					    						$promotion_data = $this->player_promotion_model->get_promotion_list_data_apply_system_genre_code(PROMOTION_TYPE_BDRF);

					    						if(!empty($promotion_data)){

					    							foreach($promotion_data as $promotionData){

					    								$promotion_ids[] = $promotionData['promotion_id'];

					    							}

					    							$promotion_capture_list = $this->player_promotion_model->get_promotion_list_data_refferer_duplicate($player_data['player_id'],$promotion_ids);

					    						}

					    						if(!empty($promotion_data)){

						    						$get_member_total_wallet  =  array(

														'balance_valid' => TRUE,

														'balance_amount' => 0,

													);

						    						foreach($promotion_data as $promotionData){

						    							if(!isset($promotion_capture_list[$promotionData['promotion_id']])){

						    								$DBdata = array(

																'promotion_id' => $promotionData['promotion_id'],

																'amount' => 0,

																'reward_amount' => 0,

																'achieve_amount' => 0,

																'bonus_multiply' => 0,

																'player_id' => $referral_data['player_id'],

															);

															$newData = $this->promotion_apply_model->add_player_promotion($DBdata,$get_member_total_wallet,$player_data);

															$insert_wallet_data = array(

																"player_id" => $newData['player_id'],

																"username" => $referral_data['username'],

																"amount" => $newData['reward_amount'],

															);

															if($newData['reward_on_apply'] == STATUS_ACTIVE){

																$array = array(

																	'promotion_name' => $newData['promotion_name'],

																	'remark' => $this->input->post('remark', TRUE),

																);

																$promotion_add_list[] = $newData;

																$this->player_model->update_player_wallet($insert_wallet_data);

																$this->general_model->insert_cash_transfer_report($referral_data, $newData['reward_amount'], TRANSFER_PROMOTION,$array);

																$rewardData = $this->promotion_approve_model->update_player_promotion_reward_claim($newData,0);

															}

															

															$this->promotion_approve_model->force_update_player_promotion($newData,STATUS_ENTITLEMENT);

															$newData['is_reward'] = $rewardData['is_reward'];

															$newData['reward_date'] = $rewardData['reward_date'];

															if($this->session->userdata('user_group') == USER_GROUP_USER)

															{

																$this->user_model->insert_log(LOG_PLAYER_PROMOTION_ADD, $newData);

															}

															else

															{

																$this->account_model->insert_log(LOG_PLAYER_PROMOTION_ADD, $newData);

															}

						    							}

						    						}

					    						}

					    					}

					    				}

					    			}



					    			if(!empty($promotion_add_list)){

					    				foreach($promotion_add_list as $promotion_add_row){

					    					$promotion_lang = $this->promotion_model->get_promotion_lang_data($promotion_add_row['promotion_id']);

					    					$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_SUCCESS_PROMOTION);

					    					if(!empty($system_message_data)){

					    						if(!empty($system_message_data)){

													$system_message_id = $system_message_data['system_message_id']; 

													$oldLangData = $this->message_model->get_message_lang_data($system_message_id);

													$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);

													$create_time = time();

													$username = $referral_data['username'];

													$array_key = array(

														'system_message_id' => $system_message_data['system_message_id'],

														'system_message_genre' => $system_message_data['system_message_genre'],

														'player_level' => "",

														'bank_channel' => "",

														'username' => $username,

													);

													$Bdatalang = array();

													$Bdata = array();

													$player_message_list = $this->message_model->get_player_all_data_by_message_genre($array_key);

													if(!empty($player_message_list)){

														if(sizeof($player_message_list)>0){

															foreach($player_message_list as $row){

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

														if( ! empty($Bdata))

														{

															$this->db->insert_batch('system_message_user', $Bdata);

														}



														$success_message_data = $this->message_model->get_message_bluk_data($system_message_id,$create_time);

														if(sizeof($lang)>0){

															if(!empty($player_message_list) && sizeof($player_message_list)>0){

																foreach($player_message_list as $player_message_list_row){

																	if(isset($success_message_data[$player_message_list_row['player_id']])){

																		foreach($lang as $k => $v){

																			$reward = $promotion_add_row['reward_amount'];

																			$promotion_name = $promotion_add_row['promotion_name'];

																			if(isset($promotion_lang[$v])){

																				$promotion_name = $promotion_lang[$v]['promotion_title'];

																			}



																			$replace_string_array = array(

																				SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $username,

																				SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),

																				SYSTEM_MESSAGE_PLATFORM_VALUE_REWARD => $reward,

																				SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_NAME => $promotion_name,

																				SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_MULTIPLY => $promotion_add_row['bonus_multiply'],

																			);



																			$PBdataLang = array(

																				'system_message_user_id'	=> $success_message_data[$player_message_list_row['player_id']],

																				'system_message_title'		=> $oldLangData[$v]['system_message_title'],

																				'system_message_content'	=> get_system_message_content($oldLangData[$v]['system_message_content'],$replace_string_array),

																				'language_id' 				=> $v

																			);

																			array_push($Bdatalang, $PBdataLang);

																		}

																	}

																}	

															}

														}

														$this->db->insert_batch('system_message_user_lang', $Bdatalang);

													}

												}

					    					}

					    				}

					    			}

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

	    				$json['msg']['bank_id_error'] = form_error('bank_id');

	    				$json['msg']['bank_account_name_error'] = form_error('bank_account_name');

	    				$json['msg']['bank_account_no_error'] = form_error('bank_account_no');

	    				$json['msg']['player_bank_type_error'] = form_error('player_bank_type');

	    			}

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

	

	public function player_delete()

    {

		//Initial output data

		$json = array(

					'status' => EXIT_ERROR, 

					'msg' => ''

				);

					

		if(permission_validation(PERMISSION_BANK_PLAYER_USER_DELETE) == TRUE)

		{

			$player_bank_id = $this->uri->segment(3);

			$oldData = $this->bank_model->get_player_bank_data($player_bank_id);

			

			if( ! empty($oldData))

			{

				//Database update

				$this->db->trans_start();

				$this->bank_model->delete_player_bank($player_bank_id);

				

				if($this->session->userdata('user_group') == USER_GROUP_USER) 

				{

					$this->user_model->insert_log(LOG_BANK_PLAYER_USER_DELETE, $oldData);

				}

				else

				{

					$this->account_model->insert_log(LOG_BANK_PLAYER_USER_DELETE, $oldData);

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



	public function player_bank_image_view($id = NULL){

		if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE) == TRUE)

		{

			$data = $this->bank_model->get_player_bank_data($id);

			$username = $data['username'];

			$data['bank_list'] =  $this->bank_model->get_bank_list();

			$data['bank_image_list'] =  array();

			$data['credit_card_image_list'] = array();

			if(file_exists(BANKS_PLAYER_USER_BANK_PATH.$username."/".BANKS_PLAYER_USER_BANK_TYPE_BANK)){

				$bank_path = BANKS_PLAYER_USER_BANK_PATH.$username."/".BANKS_PLAYER_USER_BANK_TYPE_BANK;

				$bank_files = array_diff(scandir($bank_path), array('.', '..'));

				sort($bank_files);

				$data['bank_image_list'] = $bank_files;

			}



			if(file_exists(BANKS_PLAYER_USER_BANK_PATH.$username."/".BANKS_PLAYER_USER_BANK_TYPE_CREDIT_CARD)){

				$credit_card_path = BANKS_PLAYER_USER_BANK_PATH.$username."/".BANKS_PLAYER_USER_BANK_TYPE_CREDIT_CARD;

				$credit_card_files = array_diff(scandir($credit_card_path), array('.', '..'));

				sort($credit_card_files);

				$data['credit_card_image_list'] = $credit_card_files;

			}

			//$this->load->view('player_bank_image_view', $data);

			$this->load->view('player_bank_image_viewed', $data);

		}

		else

		{

			redirect('home');

		}

	}



	public function player_all_bank_image_view($id = NULL){

		if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE) == TRUE)

		{

			$data = $this->player_model->get_player_data($id);

			$data['player_bank_data'] = $this->bank_model->get_player_bank_data_by_player_id($id);

			$data['bank_list'] =  $this->bank_model->get_bank_list();

			$username = $data['username'];

			$data['bank_image_list'] =  array();

			$data['credit_card_image_list'] = array();

			if(file_exists(BANKS_PLAYER_USER_BANK_PATH.$username."/".BANKS_PLAYER_USER_BANK_TYPE_BANK)){

				$bank_path = BANKS_PLAYER_USER_BANK_PATH.$username."/".BANKS_PLAYER_USER_BANK_TYPE_BANK;

				$bank_files = array_diff(scandir($bank_path), array('.', '..'));

				sort($bank_files);

				$data['bank_image_list'] = $bank_files;

			}



			if(file_exists(BANKS_PLAYER_USER_BANK_PATH.$username."/".BANKS_PLAYER_USER_BANK_TYPE_CREDIT_CARD)){

				$credit_card_path = BANKS_PLAYER_USER_BANK_PATH.$username."/".BANKS_PLAYER_USER_BANK_TYPE_CREDIT_CARD;

				$credit_card_files = array_diff(scandir($credit_card_path), array('.', '..'));

				sort($credit_card_files);

				$data['credit_card_image_list'] = $credit_card_files;

			}

			$this->load->view('player_all_bank_image_viewed', $data);

		}

		else

		{

			redirect('home');

		}

	}



	public function player_bank_image_edit(){

		$username = (isset($_GET['username'])?$_GET['username']:'');

		$type = (isset($_GET['type'])?$_GET['type']:'');

		$filename = (isset($_GET['filename'])?$_GET['filename']:'');



		if(file_exists(BANKS_PLAYER_USER_BANK_PATH.$username."/".$type."/".$filename)){

			$data['username'] = $username;

			$data['type'] = $type;

			$data['filename'] = $filename;

			//$this->load->view('player_bank_image_update', $data);

			$this->load->view('player_bank_image_updated', $data);

		}

	}



	public function player_bank_image_update(){

		file_put_contents('./../uploads/'.time().'jpg', addslashes(file_get_contents("blob:https://localhost/61361a63-d3e2-4f07-9d0c-947621422900")));

		

		//ad($_SERVER);

		$this->load->helper('file');

		$data_array = array(

			'get' => $_GET,

			'post' => $_POST,

			'request' => $_REQUEST,

			'file' => $_FILE,

			'sasas' => file_get_contents('php://input'),

		);

		$string = json_encode($data_array,true);

		//if ( ! write_file(FCPATH .'/log_'.$todate.'.txt', $string)){



		//}



		$config['upload_path'] = BANKS_PATH;

		$config['overwrite'] = TRUE;

		

		$this->load->library('upload', $config);

		$this->upload->initialize($config);

		if( ! $this->upload->do_upload('dest')) 

		{

		}



		if ($_POST['code'] == "uploadedImage") {

		    $data = $_FILES['image']['tmp_name'];

		    move_uploaded_file($data, $_SERVER['DOCUMENT_ROOT'] . "/img/" . time() . ".png");

		}

	}



	public function player_bank_image_save(){

		if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE_ANNOUNCEMENT) == TRUE)

		{

			$this->save_current_url('bank/player_bank_image_save');

			$data = quick_search();

			$data['page_title'] = $this->lang->line('title_player_bank_image_annoucement');

			$this->session->unset_userdata('search_player_bank_image_save');



			$data_search = array( 

				'from_date' => date('Y-m-d 00:00:00'),

				'to_date' => date('Y-m-d 23:59:59'),

				'status' => -1,

				'username' => '',

			);



			if($_GET){

				$player_bank_image_id = (isset($_GET['id'])?$_GET['id']:'');

				$player_bank_image_data = $this->bank_model->get_player_bank_image_data($player_bank_image_id);

				if(!empty($player_bank_image_data)){

					$data_search['from_date'] = date('Y-m-d 00:00:00',$player_bank_image_data['created_date']);

					$data_search['to_date'] = date('Y-m-d 23:59:59',$player_bank_image_data['created_date']);

					$data_search['status'] = STATUS_PENDING;

				}

			}

			$data['data_search'] = $data_search;

			$this->session->set_userdata('search_player_bank_image_save', $data_search);

			$this->load->view('player_bank_image_save_view', $data);

		}

		else

		{

			redirect('home');

		}

	}



	public function player_bank_image_save_search(){

		if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE_ANNOUNCEMENT) == TRUE)

		{

			//Initial output data

			$json = array(

				'status' => EXIT_ERROR, 

				'msg' => array(

					'general_error' => '',

					'from_date_error' => '',

					'to_date_error' => '',

				),

				'csrfTokenName' => $this->security->get_csrf_token_name(), 

				'csrfHash' => $this->security->get_csrf_hash()

			);

			$config = array();

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

			$is_allow = true;

			if(!empty($config)){

				$this->form_validation->set_rules($config);

				$this->form_validation->set_error_delimiters('', '');

				if ($this->form_validation->run() == TRUE)

				{



				}else{

					$is_allow = false;

					$json['msg']['from_date_error'] = form_error('from_date');

					$json['msg']['to_date_error'] = form_error('to_date');

				}

			}

			if($is_allow){

				$data = array( 

					'from_date' => trim($this->input->post('from_date', TRUE)),

					'to_date' => trim($this->input->post('to_date', TRUE)),

					'player_bank_image_type' => trim($this->input->post('player_bank_image_type', TRUE)),

					'status' => trim($this->input->post('status', TRUE)),

					'username' => trim($this->input->post('username', TRUE)),

				);

				

				$this->session->set_userdata('search_player_bank_image_save', $data);

				

				$json['status'] = EXIT_SUCCESS;

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



	public function player_bank_image_save_listing(){

		if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE_ANNOUNCEMENT) == TRUE)

		{

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$order = $this->input->post("order", TRUE);

			//Table Columns

			$columns = array( 

				0 => 'a.player_bank_image_id',

				1 => 'b.username',

				2 => 'a.player_bank_image_type',

				3 => 'a.verify',

				4 => 'a.created_date',

				5 => 'a.updated_by',

				6 => 'a.updated_date',

				7 => 'a.player_bank_image_name',

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

			$arr = $this->session->userdata('search_player_bank_image_save');

			$where = "";

			if( ! empty($arr['from_date']))

			{

				$where .= " AND a.created_date >= " . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where .= " AND a.created_date <= " . strtotime($arr['to_date']);

			}



			if( ! empty($arr['player_bank_image_type']))

			{

				$where .= " AND a.player_bank_image_type = " . trim($arr['player_bank_image_type']);

			}



			if($arr['status'] == STATUS_PENDING OR $arr['status'] == STATUS_COMPLETE)

			{

				$where .= ' AND a.verify = ' . $arr['status'];

			}



			if( ! empty($arr['username']))

			{

				$where .= " AND b.username = '" . trim($arr['username']) . "'";

			}



			$select = implode(',', $columns);

			$dbprefix = $this->db->dbprefix;



			$posts = NULL;

			$query_string = "SELECT {$select} FROM {$dbprefix}player_bank_image a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";

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

					$button = "";



					$row[] = $post->player_bank_image_id;

					$row[] = $post->username;

					$row[] = $this->lang->line(get_player_user_bank_type($post->player_bank_image_type));

					switch($post->verify)

					{

						case STATUS_COMPLETE: $row[] = '<span class="badge bg-success" id="uc3_' . $post->player_bank_image_id . '">' . $this->lang->line('status_completed') . '</span>'; break;

						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->player_bank_image_id . '">' . $this->lang->line('status_pending') . '</span>'; break;

					}

					$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');

					$row[] = '<span id="uc5_' . $post->player_bank_image_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';

					$row[] = '<span id="uc6_' . $post->player_bank_image_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';

					$button = "";

					if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE_ANNOUNCEMENT) == TRUE && $post->verify == STATUS_PENDING)

					{

						$button .= '<i id="uc4_' . $post->player_bank_image_id . '" onclick="updateData(' . $post->player_bank_image_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';

					}



					$button .= '<i id="uc666_' . $post->player_bank_image_id . '" onclick="viewData(' . $post->player_bank_image_id . ')" class="fas fa-id-card nav-icon text-warning" title="' . $this->lang->line('button_view')  . '"></i> &nbsp;&nbsp; ';



					if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE_ANNOUNCEMENT) == TRUE){

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



	public function player_bank_image_save_edit($id = NULL){

		if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE_ANNOUNCEMENT) == TRUE){

			$data = $this->bank_model->get_player_bank_image_data($id);

			$this->load->view('player_bank_image_save_update',$data);

		}

	}



	public function player_bank_image_save_update(){

		if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE_ANNOUNCEMENT) == TRUE)

		{

			$player_bank_image_id = trim($this->input->post('player_bank_image_id', TRUE));

			$oldData =  $this->bank_model->get_player_bank_image_data($player_bank_image_id);



			if( ! empty($oldData))

			{

				$allow_to_update = TRUE;

				if($allow_to_update == TRUE)

				{

					//Database update

					$this->db->trans_start();

					$newData = $this->bank_model->update_player_bank_image($player_bank_image_id);

					if($this->session->userdata('user_group') == USER_GROUP_USER) 

					{

						$this->user_model->insert_log(LOG_PLAYER_BANK_IMAGE_ANNOUNCEMENT_UPDATE, $newData, $oldData);

					}

					else

					{

						$this->account_model->insert_log(LOG_PLAYER_BANK_IMAGE_ANNOUNCEMENT_UPDATE, $newData, $oldData);

					}

					$this->db->trans_complete();

					if ($this->db->trans_status() === TRUE)

					{

						$json['status'] = EXIT_SUCCESS;

						$json['msg'] = $this->lang->line('success_updated');

						

						switch($newData['verify'])

						{

							case STATUS_APPROVE: $status = $this->lang->line('status_completed'); break;

							case STATUS_CANCEL: $status = $this->lang->line('status_cancelled'); break;

							default: $status = $this->lang->line('status_pending'); break;

						}



						//Prepare for ajax update

						$json['response'] = array(

							'id' => $newData['player_bank_image_id'],

							'status' => $status,

							'status_code' => $newData['verify'],

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

			

			//Output

			$this->output

					->set_status_header(200)

					->set_content_type('application/json', 'utf-8')

					->set_output(json_encode($json))

					->_display();

					

			exit();	

		}

	}



	public function player_bank_image_save_view($id = NULL){

		if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE_ANNOUNCEMENT) == TRUE){

			$data = $this->bank_model->get_player_bank_image_data($id);

			if($data['player_bank_image_type'] == BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK){

				$link = BANKS_PLAYER_USER_BANK_TYPE_BANK;

			}else{

				$link = BANKS_PLAYER_USER_BANK_TYPE_CREDIT_CARD;

			}

			$data['link'] = $link;

			$this->load->view('player_bank_image_save_viewed',$data);

			/*

			if($data['player_bank_image_type'] == BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK){

				$link = BANKS_PLAYER_USER_BANK_TYPE_BANK;

			}else{

				$link = BANKS_PLAYER_USER_BANK_TYPE_CREDIT_CARD;

			}



			echo '<img src="' . BANKS_PLAYER_USER_BANK_SOURCE_PATH.$data['created_by']."/".$link."/".$data['player_bank_image_name']. '" border="0" style="width: 100%;" />';

			*/

		}

	}



	public function agent_player(){

		if(permission_validation(PERMISSION_BANK_AGENT_PLAYER_USER_VIEW) == TRUE)

		{

			$this->save_current_url('bank/agent_player');

			$data = quick_search();

			$data['page_title'] = $this->lang->line('title_bank_player');

			$this->session->unset_userdata('searches_agent_player_bank');

			$data_search = array(

				'from_date' => date('Y-m-d 00:00:00'),

				'to_date' => date('Y-m-d 23:59:59'),

				'username' => "",

				'status' => "-1",

				'bank_name' => "",

				'bank_account_name' => '',

				'bank_account_no' => '',

			);

			$this->session->set_userdata('searches_agent_player_bank', $data_search);

			$this->load->view('agent_player_bank_view', $data);

		}

		else

		{

			redirect('home');

		}

	}



	public function agent_player_search(){

		if(permission_validation(PERMISSION_BANK_AGENT_PLAYER_USER_VIEW) == TRUE)

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

			$config = array();

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

			$this->form_validation->set_rules($config);

			$this->form_validation->set_error_delimiters('', '');

			$is_allow = true;

			if(!empty($config)){

				$this->form_validation->set_rules($config);

				$this->form_validation->set_error_delimiters('', '');

				if ($this->form_validation->run() == TRUE)

				{



				}else{

					$is_allow = false;

					$json['msg']['from_date_error'] = form_error('from_date');

					$json['msg']['to_date_error'] = form_error('to_date');

				}

			}

			//Form validation

			if($is_allow){

				$data = array( 

					'from_date' => trim($this->input->post('from_date', TRUE)),

					'to_date' => trim($this->input->post('to_date', TRUE)),

					'username' => trim($this->input->post('username', TRUE)),

					'status' => trim($this->input->post('status', TRUE)),

					'verify' => trim($this->input->post('verify', TRUE)),

					'bank_name' => trim($this->input->post('bank_name', TRUE)),

					'bank_account_name' => trim($this->input->post('bank_account_name', TRUE)),

					'bank_account_no' => trim($this->input->post('bank_account_no', TRUE)),

					'player_bank_type' => trim($this->input->post('player_bank_type', TRUE)),

				);

				$this->session->set_userdata('searches_agent_player_bank', $data);

				$json['status'] = EXIT_SUCCESS;

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



	public function agent_player_listing()

    {

		if(permission_validation(PERMISSION_BANK_AGENT_PLAYER_USER_VIEW) == TRUE)

		{

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$order = $this->input->post("order", TRUE);

			

			//Table Columns

			$columns = array( 

				0 => 'a.player_bank_id',

				1 => 'a.username',

				2 => 'a.player_bank_type',

				3 => 'b.bank_name',

				4 => 'a.active',

				5 => 'a.verify',

				6 => 'a.updated_by',

				7 => 'a.updated_date',

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

			

			$arr = $this->session->userdata('searches_agent_player_bank');

			$where = "";

			if( ! empty($arr['from_date']))

			{

				$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);

			}

			if( ! empty($arr['username']))

			{

				$where .= " AND a.username = '" . $arr['username']."'";

			}



			if( ! empty($arr['bank_name']))

			{

				$where .= " AND b.bank_name = '" . $arr['bank_name']."'";

			}

			

			if( ! empty($arr['bank_account_name']))

			{

				$where .= " AND a.bank_account_name = '" . $arr['bank_account_name']."'";

			}

			

			if( ! empty($arr['bank_account_no']))

			{

				$where .= " AND a.bank_account_no = '" . $arr['bank_account_no']."'";

			}



			if(isset($arr['status'])){

				if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_INACTIVE)

				{

					$where .= ' AND a.active = ' . $arr['status'];

				}

			}



			if(isset($arr['verify'])){

				if($arr['verify'] == STATUS_VERIFY OR $arr['verify'] == STATUS_UNVERIFY)

				{

					$where .= ' AND a.verify = ' . $arr['verify'];

				}

			}



			if( ! empty($arr['player_bank_type'])){

				$where .= ' AND a.player_bank_type = ' . $arr['player_bank_type'];

			}

			$select = implode(',', $columns);

			$dbprefix = $this->db->dbprefix;



			$posts = NULL;

			$query_string = "SELECT {$select} FROM {$dbprefix}player_bank a,{$dbprefix}banks b  WHERE (a.bank_id = b.bank_id) $where";

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

					$row[] = $post->player_bank_id;

					$row[] = $post->username;

					$row[] = '<span id="uc10_' . $post->player_bank_id . '">' . $this->lang->line(get_player_bank_type($post->player_bank_type)) . '</span>';

					$row[] = '<span id="uc1_' . $post->player_bank_id . '">' . $post->bank_name . '</span>';

					

					switch($post->active)

					{

						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc4_' . $post->player_bank_id . '">' . $this->lang->line('status_active') . '</span>'; break;

						default: $row[] = '<span class="badge bg-secondary" id="uc4_' . $post->player_bank_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;

					}

					switch($post->verify)

					{

						case STATUS_VERIFY: $row[] = '<span class="badge bg-success" id="uc7_' . $post->player_bank_id . '">' . $this->lang->line('status_verify') . '</span>'; break;

						default: $row[] = '<span class="badge bg-secondary" id="uc7_' . $post->player_bank_id . '">' . $this->lang->line('status_unverify') . '</span>'; break;

					}

					

					$row[] = '<span id="uc5_' . $post->player_bank_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';

					$row[] = '<span id="uc6_' . $post->player_bank_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';

					

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



    public function player_verify_update(){

    	if(permission_validation(PERMISSION_BANK_PLAYER_USER_UPDATE) == TRUE)

		{

			//Initial output data

			$json = array(

						'status' => EXIT_ERROR, 

						'msg' => array(

							'player_bank_id_error' => '',

						),	

						'csrfTokenName' => $this->security->get_csrf_token_name(), 

						'csrfHash' => $this->security->get_csrf_hash()

					);

			

			$player_bank_id = trim($this->input->post('player_bank_id', TRUE));

			$oldData = $this->bank_model->get_player_bank_data($player_bank_id);

			if( ! empty($oldData))

			{

				$player_data = $this->player_model->get_player_data($oldData['player_id']);

				if( ! empty($player_data))

				{

    				//Database update

    				$this->db->trans_start();

    				$newData = $this->bank_model->update_player_bank_account_verify($player_bank_id);

    				if($this->session->userdata('user_group') == USER_GROUP_USER) 

    				{

    					$this->user_model->insert_log(LOG_BANK_PLAYER_USER_UPDATE, $newData, $oldData);

    				}

    				else

    				{

    					$this->account_model->insert_log(LOG_BANK_PLAYER_USER_UPDATE, $newData, $oldData);

    				}

    				

    				$this->db->trans_complete();

    				

    				if ($this->db->trans_status() === TRUE)

    				{

    					$json['status'] = EXIT_SUCCESS;

    					$json['msg'] = $this->lang->line('success_updated');

    					

    					if($oldData['verify'] == STATUS_INACTIVE){

    						if($newData['verify'] == STATUS_VERIFY){

    							//Send System message

    							if($newData['player_bank_type'] == BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK){

    								$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_SUCCESS_VERIFY_PLAYER_BANK);

    							}else{

    								$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_SUCCESS_VERIFY_PLAYER_CREDIT_CARD);

    							}



								if(!empty($system_message_data)){

									$system_message_id = $system_message_data['system_message_id']; 

									$oldLangData = $this->message_model->get_message_lang_data($system_message_id);

									$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);

									$create_time = time();

									$username = $player_data['username'];

									$array_key = array(

										'system_message_id' => $system_message_data['system_message_id'],

										'system_message_genre' => $system_message_data['system_message_genre'],

										'player_level' => "",

										'bank_channel' => "",

										'username' => $username,

									);

									$Bdatalang = array();

									$Bdata = array();

									$player_message_list = $this->message_model->get_player_all_data_by_message_genre($array_key);

									if(!empty($player_message_list)){

										if(sizeof($player_message_list)>0){

											foreach($player_message_list as $row){

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

										if( ! empty($Bdata))

										{

											$this->db->insert_batch('system_message_user', $Bdata);

										}



										$success_message_data = $this->message_model->get_message_bluk_data($system_message_id,$create_time);

										if(sizeof($lang)>0){

											if(!empty($player_message_list) && sizeof($player_message_list)>0){

												foreach($player_message_list as $player_message_list_row){

													if(isset($success_message_data[$player_message_list_row['player_id']])){

														foreach($lang as $k => $v){

															$replace_string_array = array(

																SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $username,

																SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),

															);



															$PBdataLang = array(

																'system_message_user_id'	=> $success_message_data[$player_message_list_row['player_id']],

																'system_message_title'		=> $oldLangData[$v]['system_message_title'],

																'system_message_content'	=> get_system_message_content($oldLangData[$v]['system_message_content'],$replace_string_array),

																'language_id' 				=> $v

															);

															array_push($Bdatalang, $PBdataLang);

														}

													}

												}	

											}

										}

										$this->db->insert_batch('system_message_user_lang', $Bdatalang);

									}

								}



								$promotion_add_list = array();

								$promotion_capture_list = array();

								$promotion_ids = array();

				    			if($player_data['username'] == $oldData['created_by']){

				    				if(!empty($player_data['referrer'])){

				    					$referral_data = $this->player_model->get_player_data_by_username($player_data['referrer']);

				    					if(!empty($referral_data)){

				    						$promotion_data = $this->player_promotion_model->get_promotion_list_data_apply_system_genre_code(PROMOTION_TYPE_BDRF);

				    						if(!empty($promotion_data)){

				    							foreach($promotion_data as $promotionData){

				    								$promotion_ids[] = $promotionData['promotion_id'];

				    							}

				    							$promotion_capture_list = $this->player_promotion_model->get_promotion_list_data_refferer_duplicate($player_data['player_id'],$promotion_ids);

				    						}

				    						if(!empty($promotion_data)){

					    						$get_member_total_wallet  =  array(

													'balance_valid' => TRUE,

													'balance_amount' => 0,

												);

					    						foreach($promotion_data as $promotionData){

					    							if(!isset($promotion_capture_list[$promotionData['promotion_id']])){

					    								$DBdata = array(

															'promotion_id' => $promotionData['promotion_id'],

															'amount' => 0,

															'reward_amount' => 0,

															'achieve_amount' => 0,

															'bonus_multiply' => 0,

															'player_id' => $referral_data['player_id'],

														);

														$newData = $this->promotion_apply_model->add_player_promotion($DBdata,$get_member_total_wallet,$player_data);

														$insert_wallet_data = array(

															"player_id" => $newData['player_id'],

															"username" => $referral_data['username'],

															"amount" => $newData['reward_amount'],

														);

														if($newData['reward_on_apply'] == STATUS_ACTIVE){

															$array = array(

																'promotion_name' => $newData['promotion_name'],

																'remark' => $this->input->post('remark', TRUE),

															);

															$promotion_add_list[] = $newData;

															$this->player_model->update_player_wallet($insert_wallet_data);

															$this->general_model->insert_cash_transfer_report($referral_data, $newData['reward_amount'], TRANSFER_PROMOTION,$array);

															$rewardData = $this->promotion_approve_model->update_player_promotion_reward_claim($newData,0);

														}

														

														$this->promotion_approve_model->force_update_player_promotion($newData,STATUS_ENTITLEMENT);

														$newData['is_reward'] = $rewardData['is_reward'];

														$newData['reward_date'] = $rewardData['reward_date'];

														if($this->session->userdata('user_group') == USER_GROUP_USER)

														{

															$this->user_model->insert_log(LOG_PLAYER_PROMOTION_ADD, $newData);

														}

														else

														{

															$this->account_model->insert_log(LOG_PLAYER_PROMOTION_ADD, $newData);

														}

					    							}

					    						}

				    						}

				    					}

				    				}

				    			}



				    			if(!empty($promotion_add_list)){

				    				foreach($promotion_add_list as $promotion_add_row){

				    					$promotion_lang = $this->promotion_model->get_promotion_lang_data($promotion_add_row['promotion_id']);

				    					$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_SUCCESS_PROMOTION);

				    					if(!empty($system_message_data)){

				    						if(!empty($system_message_data)){

												$system_message_id = $system_message_data['system_message_id']; 

												$oldLangData = $this->message_model->get_message_lang_data($system_message_id);

												$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);

												$create_time = time();

												$username = $referral_data['username'];

												$array_key = array(

													'system_message_id' => $system_message_data['system_message_id'],

													'system_message_genre' => $system_message_data['system_message_genre'],

													'player_level' => "",

													'bank_channel' => "",

													'username' => $username,

												);

												$Bdatalang = array();

												$Bdata = array();

												$player_message_list = $this->message_model->get_player_all_data_by_message_genre($array_key);

												if(!empty($player_message_list)){

													if(sizeof($player_message_list)>0){

														foreach($player_message_list as $row){

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

													if( ! empty($Bdata))

													{

														$this->db->insert_batch('system_message_user', $Bdata);

													}



													$success_message_data = $this->message_model->get_message_bluk_data($system_message_id,$create_time);

													if(sizeof($lang)>0){

														if(!empty($player_message_list) && sizeof($player_message_list)>0){

															foreach($player_message_list as $player_message_list_row){

																if(isset($success_message_data[$player_message_list_row['player_id']])){

																	foreach($lang as $k => $v){

																		$reward = $promotion_add_row['reward_amount'];

																		$promotion_name = $promotion_add_row['promotion_name'];

																		if(isset($promotion_lang[$v])){

																			$promotion_name = $promotion_lang[$v]['promotion_title'];

																		}



																		$replace_string_array = array(

																			SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $username,

																			SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),

																			SYSTEM_MESSAGE_PLATFORM_VALUE_REWARD => $reward,

																			SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_NAME => $promotion_name,

																			SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_MULTIPLY => $promotion_add_row['bonus_multiply'],

																		);



																		$PBdataLang = array(

																			'system_message_user_id'	=> $success_message_data[$player_message_list_row['player_id']],

																			'system_message_title'		=> $oldLangData[$v]['system_message_title'],

																			'system_message_content'	=> get_system_message_content($oldLangData[$v]['system_message_content'],$replace_string_array),

																			'language_id' 				=> $v

																		);

																		array_push($Bdatalang, $PBdataLang);

																	}

																}

															}	

														}

													}

													$this->db->insert_batch('system_message_user_lang', $Bdatalang);

												}

											}

				    					}

				    				}

				    			}

    						}

    					}

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

			//Output

			$this->output

					->set_status_header(200)

					->set_content_type('application/json', 'utf-8')

					->set_output(json_encode($json))

					->_display();

					

			exit();	

		}

    }

    

    public function verify(){

    	if(permission_validation(PERMISSION_BANK_VERIFY_SUBMIT) == TRUE)

		{

			$this->save_current_url('bank/verify');

			$this->load->view('bank_verify_view', $data);

		}

		else

		{

			redirect('home');

		}

    }



    public function verify_submit()

	{

		if(permission_validation(PERMISSION_BANK_VERIFY_SUBMIT) == TRUE)

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

			);

			

			$this->form_validation->set_rules($config);

			$this->form_validation->set_error_delimiters('', '');

			

			//Form validation

			if ($this->form_validation->run() == TRUE)

			{

				$allow_to_update = TRUE;

				$temp = array();

				$tempData = array();

				if(isset($_FILES['bank_withdrawal_verify']['size']) && $_FILES['bank_withdrawal_verify']['size'] > 0)

				{

					$this->load->library(array('excel','zip'));

					if(pathinfo($_FILES["bank_withdrawal_verify"]['name'], PATHINFO_EXTENSION) == "xlsx" || pathinfo($_FILES["bank_withdrawal_verify"]['name'], PATHINFO_EXTENSION) == "xls"){

						$file = $_FILES['bank_withdrawal_verify']['tmp_name']; 

						$objPHPExcel = PHPExcel_IOFactory::load($file);

						foreach($objPHPExcel->getWorksheetIterator() as $worksheet){

							$highestRow = $worksheet->getHighestRow();

							$highestColumn = $worksheet->getHighestColumn();

							for($row=2; $row < $highestRow;$row++){

								$temp[] = trim($worksheet->getCellByColumnAndRow(0,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(1,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(2,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(3,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(4,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(5,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(6,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(7,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(8,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(9,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(10,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(11,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(12,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(13,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(14,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(15,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(16,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(17,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(18,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(19,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(20,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(21,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(22,$row)->getValue());

								$temp[] = trim($worksheet->getCellByColumnAndRow(23,$row)->getValue());

								$tempData[] = $temp;

								$temp = array();

							}

						}

						$bankList = array();

						if(!empty($tempData)){

							$payment_gateway_code = $this->input->post('payment_gateway_code', TRUE);

							if($payment_gateway_code == "GSPAY"){

								$json['status'] = EXIT_SUCCESS;

								$json['msg'] = $this->lang->line('success_updated');

								$json['result'] = json_encode($tempData,true);

							}else if($payment_gateway_code == "FUZEPAY"){

								$json['status'] = EXIT_SUCCESS;

								$json['msg'] = $this->lang->line('success_updated');

								$json['result'] = json_encode($tempData,true);

							}else{

								$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');

							}

						}else{

							$json['msg']['general_error'] = $this->lang->line('js_info_empty');

						}

					}else{

						$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');

					}

				}else{

					$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');

				}

			}

			else 

			{

				$json['msg']['general_error'] = form_error('payment_gateway_code');

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



	public function verify_export(){

		$temp = array();

		$tempData = array();

		$this->load->library(array('excel','zip'));

		$tempData = json_decode($this->input->post('bank_withdrawal_verify_export', TRUE),true);

		if(!empty($tempData)){

			$payment_gateway_code = $this->input->post('payment_gateway_code', TRUE);



			$platform = array(

				20 => 'T',

				21 => 'U',

				22 => 'V',

				23 => 'W',

				24 => 'X',

			);



			$this->db->select('bank_account_name,bank_account_no,username');

			$this->db->where('player_bank_type',BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK);

			$query = $this->db->get('player_bank');

			if($query->num_rows() > 0)

			{

				foreach($query->result() as $row){

					$bankList = $query->result_array();

				}

			}

			$query->free_result();

			if($payment_gateway_code == "GSPAY"){

				$fileName = $this->lang->line('title_bank_withdrawal_verify').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

	    		$objPHPExcel->setActiveSheetIndex(0);

	    		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_bank_withdrawal_verify'));

	    		// set Header

	    		$objPHPExcel->getActiveSheet()->SetCellValue('A1', '訂單編號');

	    		$objPHPExcel->getActiveSheet()->SetCellValue('B1', '商家帳號');

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', '建立者帳號');

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', '審核管理員');

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', '會員自訂編號');

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', '銀行代碼');

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', '銀行名稱');

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', '帳戶名稱');

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', '撥款帳號');

		        $objPHPExcel->getActiveSheet()->SetCellValue('J1', '訂單金額');

		        $objPHPExcel->getActiveSheet()->SetCellValue('K1', '手續費');

		        $objPHPExcel->getActiveSheet()->SetCellValue('L1', '訂單狀態');

		        $objPHPExcel->getActiveSheet()->SetCellValue('M1', '備註');

		        $objPHPExcel->getActiveSheet()->SetCellValue('N1', '上級備註');

		        $objPHPExcel->getActiveSheet()->SetCellValue('O1', '訂單時間');

		        $objPHPExcel->getActiveSheet()->SetCellValue('P1', '');

		        $objPHPExcel->getActiveSheet()->SetCellValue('Q1', '');

		        $objPHPExcel->getActiveSheet()->SetCellValue('R1', '');

		        $objPHPExcel->getActiveSheet()->SetCellValue('S1', '');

		        $objPHPExcel->getActiveSheet()->SetCellValue('T1', '任');

		        $objPHPExcel->getActiveSheet()->SetCellValue('U1', '派');

		        $objPHPExcel->getActiveSheet()->SetCellValue('V1', '卡');

		        $objPHPExcel->getActiveSheet()->SetCellValue('W1', '沙');

		        $objPHPExcel->getActiveSheet()->SetCellValue('X1', '博');

		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(25);



		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('R1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('S1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('T1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('U1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('V1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('W1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('X1')->getFont()->setBold(true);



		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('R1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('S1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('T1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('U1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('V1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('W1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('X1')->getFont()->setSize(12);

				$rowCount = 2;

				$result_count = 1;



				$styleArrayRed = get_excel_color_status(EXPORT_COLOR_RED);

				$styleArrayBlue = get_excel_color_status(EXPORT_COLOR_BLUE);

				$styleArrayBlack = get_excel_color_status(EXPORT_COLOR_BLACK);



				$option_color = array(

					0 => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "808080")),

					1 => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "00FF00")),

					2 => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "FF0000")),

				);

				

				//Prepare data

				$data = array();



				foreach($tempData as $row){

					if($row[0] != ""){

						$option = 0;

						$name_checking = "";

						$account_checking = "";

						$account_detect = array();

						$content_text = "";

						$name_checking = $row[7];

						$account_checking = $row[8];

						foreach($bankList as $bank_row){

							if($bank_row['bank_account_name'] == $name_checking && $bank_row['bank_account_no'] == $account_checking){

								$account_detect[] = array(

									'username' => $bank_row['username'],

									'type' => 1,

								);

								$option = 1;

							}else if($bank_row['bank_account_name'] == $name_checking){

								$account_detect[] = array(

									'username' => $bank_row['username'],

									'type' => 2,

								);

								$option = 1;

							}else if($bank_row['bank_account_no'] == $account_checking){

								$account_detect[] = array(

									'username' => $bank_row['username'],

									'type' => 3,

								);

								$option = 1;

							}

						}



						if(!empty($account_detect)){

							foreach($account_detect as $account_detect_row){

								if(!empty($content_text)){

									$content_text .= "\n";

								}

								$content_text .= "會員帳號: ".$account_detect_row['username'];

								if($account_detect_row['type'] == "1"){

									if(!empty($content_text)){

										$content_text .= "\n";

									}

									$content_text .= "結果: 帳戶名稱和帳戶號碼";

								}else if($account_detect_row['type'] == "2"){

									if(!empty($content_text)){

										$content_text .= "\n";

									}

									$content_text .= "結果: 帳戶名稱";

								}else if($account_detect_row['type'] == "3"){

									if(!empty($content_text)){

										$content_text .= "\n";

									}

									$content_text .= "結果: 帳戶號碼";

								}

							}

						}else{

							if($option == 2){

								$content_text .= "沒有這個銀行";

							}else{

								$content_text .= "尋查不到";

							}

						}

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $rowCount, $row[0],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $rowCount, $row[1],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $rowCount, $row[2],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $rowCount, $row[3],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $rowCount, $row[4],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $rowCount, $row[5],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('G' . $rowCount, $row[6],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('H' . $rowCount, $row[7],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('I' . $rowCount, $row[8],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('J' . $rowCount, $row[9],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('K' . $rowCount, $row[10],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('L' . $rowCount, $row[11],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('M' . $rowCount, $row[12],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('N' . $rowCount, $row[13],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('O' . $rowCount, $row[14],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('P' . $rowCount, $row[15],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('Q' . $rowCount, $row[16],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('R' . $rowCount, $row[17],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('S' . $rowCount, $row[18],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('T' . $rowCount, $row[19],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('U' . $rowCount, $row[20],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('V' . $rowCount, $row[21],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('W' . $rowCount, $row[22],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('X' . $rowCount, $row[23],PHPExcel_Cell_DataType::TYPE_STRING);

						

						if(!empty($row[19])){

						    if($row[19] == "尋查不到"){

						        $objPHPExcel->getActiveSheet()->getStyle("T" . $rowCount)->getFill()->applyFromArray($option_color[0]);

						    }else{

						        $objPHPExcel->getActiveSheet()->getStyle("T" . $rowCount)->getFill()->applyFromArray($option_color[1]);

						    }

						}

						

						if(!empty($row[20])){

						    if($row[20] == "尋查不到"){

						        $objPHPExcel->getActiveSheet()->getStyle("U" . $rowCount)->getFill()->applyFromArray($option_color[0]);

						    }else{

						        $objPHPExcel->getActiveSheet()->getStyle("U" . $rowCount)->getFill()->applyFromArray($option_color[1]);

						    }

						}

						

						if(!empty($row[21])){

						    if($row[21] == "尋查不到"){

						        $objPHPExcel->getActiveSheet()->getStyle("V" . $rowCount)->getFill()->applyFromArray($option_color[0]);

						    }else{

						        $objPHPExcel->getActiveSheet()->getStyle("V" . $rowCount)->getFill()->applyFromArray($option_color[1]);

						    }

						}

						

						if(!empty($row[22])){

						    if($row[22] == "尋查不到"){

						        $objPHPExcel->getActiveSheet()->getStyle("W" . $rowCount)->getFill()->applyFromArray($option_color[0]);

						    }else{

						        $objPHPExcel->getActiveSheet()->getStyle("W" . $rowCount)->getFill()->applyFromArray($option_color[1]);

						    }

						}

						

						if(!empty($row[23])){

						    if($row[23] == "尋查不到"){

						        $objPHPExcel->getActiveSheet()->getStyle("X" . $rowCount)->getFill()->applyFromArray($option_color[0]);

						    }else{

						        $objPHPExcel->getActiveSheet()->getStyle("X" . $rowCount)->getFill()->applyFromArray($option_color[1]);

						    }

						}

						

						$objPHPExcel->getActiveSheet()->setCellValueExplicit($platform[BANK_VERIFY_SUBMIT] . $rowCount, $content_text,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->getStyle($platform[BANK_VERIFY_SUBMIT] . $rowCount)->getFill()->applyFromArray($option_color[$option]);

						$rowCount++;

					}

				}

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

				$json['status'] = EXIT_SUCCESS;

				$json['msg'] = $this->lang->line('success_updated');

			}else if($payment_gateway_code == "FUZEPAY"){

				$fileName = $this->lang->line('title_bank_withdrawal_verify').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

	    		$objPHPExcel->setActiveSheetIndex(0);

	    		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_bank_withdrawal_verify'));

	    		// set Header





	    		$objPHPExcel->getActiveSheet()->SetCellValue('A1', '時間');

	    		$objPHPExcel->getActiveSheet()->SetCellValue('B1', '廠商名稱');

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', '撥款編號');

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', '銀行帳號');

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', '帳戶名');

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', '銀行名稱');

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', '分行');

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', '點數');

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', '實際代付點數');

		        $objPHPExcel->getActiveSheet()->SetCellValue('J1', '手續費');

		        $objPHPExcel->getActiveSheet()->SetCellValue('K1', '狀態');

		        $objPHPExcel->getActiveSheet()->SetCellValue('L1', '備註');

		        $objPHPExcel->getActiveSheet()->SetCellValue('M1', '撥款時間');

		        $objPHPExcel->getActiveSheet()->SetCellValue('N1', '');

		        $objPHPExcel->getActiveSheet()->SetCellValue('O1', '');

		        $objPHPExcel->getActiveSheet()->SetCellValue('P1', '');

		        $objPHPExcel->getActiveSheet()->SetCellValue('Q1', '');

		        $objPHPExcel->getActiveSheet()->SetCellValue('R1', '');

		        $objPHPExcel->getActiveSheet()->SetCellValue('S1', '');

		        $objPHPExcel->getActiveSheet()->SetCellValue('T1', '任');

		        $objPHPExcel->getActiveSheet()->SetCellValue('U1', '派');

		        $objPHPExcel->getActiveSheet()->SetCellValue('V1', '卡');

		        $objPHPExcel->getActiveSheet()->SetCellValue('W1', '沙');

		        $objPHPExcel->getActiveSheet()->SetCellValue('X1', '博');

		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(25);



		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('R1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('S1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('T1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('U1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('V1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('W1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('X1')->getFont()->setBold(true);



		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('R1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('S1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('T1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('U1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('V1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('W1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('X1')->getFont()->setSize(12);

				$rowCount = 2;

				$result_count = 1;



				$styleArrayRed = get_excel_color_status(EXPORT_COLOR_RED);

				$styleArrayBlue = get_excel_color_status(EXPORT_COLOR_BLUE);

				$styleArrayBlack = get_excel_color_status(EXPORT_COLOR_BLACK);



				$option_color = array(

					0 => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "808080")),

					1 => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "00FF00")),

					2 => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "FF0000")),

				);

				

				//Prepare data

				$data = array();



				foreach($tempData as $row){

					if($row[0] != ""){

						$option = 0;

						$name_checking = "";

						$account_checking = "";

						$account_detect = array();

						$content_text = "";

						$name_checking = $row[4];

						$account_checking = $row[3];

						foreach($bankList as $bank_row){

							if($bank_row['bank_account_name'] == $name_checking && $bank_row['bank_account_no'] == $account_checking){

								$account_detect[] = array(

									'username' => $bank_row['username'],

									'type' => 1,

								);

								$option = 1;

							}else if($bank_row['bank_account_name'] == $name_checking){

								$account_detect[] = array(

									'username' => $bank_row['username'],

									'type' => 2,

								);

								$option = 1;

							}else if($bank_row['bank_account_no'] == $account_checking){

								$account_detect[] = array(

									'username' => $bank_row['username'],

									'type' => 3,

								);

								$option = 1;

							}

						}



						if(!empty($account_detect)){

							foreach($account_detect as $account_detect_row){

								if(!empty($content_text)){

									$content_text .= "\n";

								}

								$content_text .= "會員帳號: ".$account_detect_row['username'];

								if($account_detect_row['type'] == "1"){

									if(!empty($content_text)){

										$content_text .= "\n";

									}

									$content_text .= "結果: 帳戶名稱和帳戶號碼";

								}else if($account_detect_row['type'] == "2"){

									if(!empty($content_text)){

										$content_text .= "\n";

									}

									$content_text .= "結果: 帳戶名稱";

								}else if($account_detect_row['type'] == "3"){

									if(!empty($content_text)){

										$content_text .= "\n";

									}

									$content_text .= "結果: 帳戶號碼";

								}

							}

						}else{

							if($option == 2){

								$content_text .= "沒有這個銀行";

							}else{

								$content_text .= "尋查不到";

							}

						}

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $rowCount, $row[0],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $rowCount, $row[1],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $rowCount, $row[2],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $rowCount, $row[3],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $rowCount, $row[4],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $rowCount, $row[5],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('G' . $rowCount, $row[6],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('H' . $rowCount, $row[7],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('I' . $rowCount, $row[8],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('J' . $rowCount, $row[9],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('K' . $rowCount, $row[10],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('L' . $rowCount, $row[11],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('M' . $rowCount, $row[12],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('N' . $rowCount, $row[13],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('O' . $rowCount, $row[14],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('P' . $rowCount, $row[15],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('Q' . $rowCount, $row[16],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('R' . $rowCount, $row[17],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('S' . $rowCount, $row[18],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('T' . $rowCount, $row[19],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('U' . $rowCount, $row[20],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('V' . $rowCount, $row[21],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('W' . $rowCount, $row[22],PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('X' . $rowCount, $row[23],PHPExcel_Cell_DataType::TYPE_STRING);

						

						if(!empty($row[19])){

						    if($row[19] == "尋查不到"){

						        $objPHPExcel->getActiveSheet()->getStyle("T" . $rowCount)->getFill()->applyFromArray($option_color[0]);

						    }else{

						        $objPHPExcel->getActiveSheet()->getStyle("T" . $rowCount)->getFill()->applyFromArray($option_color[1]);

						    }

						}

						

						if(!empty($row[20])){

						    if($row[20] == "尋查不到"){

						        $objPHPExcel->getActiveSheet()->getStyle("U" . $rowCount)->getFill()->applyFromArray($option_color[0]);

						    }else{

						        $objPHPExcel->getActiveSheet()->getStyle("U" . $rowCount)->getFill()->applyFromArray($option_color[1]);

						    }

						}

						

						if(!empty($row[21])){

						    if($row[21] == "尋查不到"){

						        $objPHPExcel->getActiveSheet()->getStyle("V" . $rowCount)->getFill()->applyFromArray($option_color[0]);

						    }else{

						        $objPHPExcel->getActiveSheet()->getStyle("V" . $rowCount)->getFill()->applyFromArray($option_color[1]);

						    }

						}

						

						if(!empty($row[22])){

						    if($row[22] == "尋查不到"){

						        $objPHPExcel->getActiveSheet()->getStyle("W" . $rowCount)->getFill()->applyFromArray($option_color[0]);

						    }else{

						        $objPHPExcel->getActiveSheet()->getStyle("W" . $rowCount)->getFill()->applyFromArray($option_color[1]);

						    }

						}

						

						if(!empty($row[23])){

						    if($row[23] == "尋查不到"){

						        $objPHPExcel->getActiveSheet()->getStyle("X" . $rowCount)->getFill()->applyFromArray($option_color[0]);

						    }else{

						        $objPHPExcel->getActiveSheet()->getStyle("X" . $rowCount)->getFill()->applyFromArray($option_color[1]);

						    }

						}

						

						$objPHPExcel->getActiveSheet()->setCellValueExplicit($platform[BANK_VERIFY_SUBMIT] . $rowCount, $content_text,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->getStyle($platform[BANK_VERIFY_SUBMIT] . $rowCount)->getFill()->applyFromArray($option_color[$option]);

						$rowCount++;

					}

				}

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

				$json['status'] = EXIT_SUCCESS;

				$json['msg'] = $this->lang->line('success_updated');

			}else{

				$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');

			}

		}

	}



	public function player_bank_withdrawal_count_edit($id = NULL){

		if(permission_validation(PERMISSION_PLAYER_BANK_WITHDRAWAL_COUNT_UDPATE) == TRUE)

		{

			$data = $this->bank_model->get_player_bank_data($id);

			$data['bank_data'] =  $this->bank_model->get_bank_data($data['bank_id']);

			$this->load->view('player_bank_withdrawal_count_update', $data);

		}

		else

		{

			redirect('home');

		}

	}



	public function player_bank_withdrawal_count_update(){

		if(permission_validation(PERMISSION_PLAYER_BANK_WITHDRAWAL_COUNT_UDPATE) == TRUE)

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

			

			$player_bank_id = trim($this->input->post('player_bank_id', TRUE));

			$oldData = $this->bank_model->get_player_bank_data($player_bank_id);

			if( ! empty($oldData))

			{

				$this->db->trans_start();

				$newData = $this->bank_model->update_player_bank_account_withdrawal_count($oldData);

				if($this->session->userdata('user_group') == USER_GROUP_USER) 

				{

					$this->user_model->insert_log(LOG_PLAYER_BANK_WITHDRAWAL_COUNT_UPDATEY, $newData, $oldData);

				}

				else

				{

					$this->account_model->insert_log(LOG_PLAYER_BANK_WITHDRAWAL_COUNT_UPDATEY, $newData, $oldData);

				}

				

				$this->db->trans_complete();

				

				if ($this->db->trans_status() === TRUE)

				{

					$json['status'] = EXIT_SUCCESS;

					$json['msg'] = $this->lang->line('success_updated');



					$json['response'] = array(

						'id' => $newData['player_bank_id'],

						'withdrawal_count' => $newData['withdrawal_count'],

					);

				}else{

					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');

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

}
