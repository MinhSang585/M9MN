<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Currencies extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('currencies_model'));
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index()
	{
		if(permission_validation(PERMISSION_CURRENCIES_VIEW) == TRUE)
		{
			$this->save_current_url('currencies');
			
			$data['page_title'] = $this->lang->line('title_currencies');
			$this->session->unset_userdata('searches_currencies');
			$data_search = array(
				'currency_code' => "",
				'status' => "-1",
			);
			$this->session->set_userdata('searches_currencies', $data_search);
			$this->load->view('currencies_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function search(){
		if(permission_validation(PERMISSION_CURRENCIES_VIEW) == TRUE)
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
				'currency_code' => trim($this->input->post('currency_code', TRUE)),
				'status' => trim($this->input->post('status', TRUE)),
			);
			
			$this->session->set_userdata('searches_currencies', $data);
			
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
		if(permission_validation(PERMISSION_CURRENCIES_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				'currency_id',
				'currency_name',
				'currency_code',
				'currency_symbol',
				't_currency_rate',
				'd_currency_rate',
				'w_currency_rate',
				't_fee',
				'd_fee',
				'w_fee',
				'is_default',
				'active',
				'updated_by',
				'updated_date',
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
			$arr = $this->session->userdata('searches_currencies');
			$where = "";
			
			if(isset($arr['currency_code']) && !empty($arr['currency_code'])){
				if($where == ""){
					$where .= 'WHERE currency_code = "' . $arr['currency_code']. '"';
				}else{
					$where .= ' AND currency_code = "' . $arr['currency_code']. '"';
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
			$query_string = "SELECT {$select} FROM {$dbprefix}currencies $where";
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
					$row[] = $post->currency_id;
					$row[] = '<span id="uc1_' . $post->currency_id . '">' .(( ! empty($post->currency_name)) ? $post->currency_name : '-') . '</span>';
					$row[] = '<span id="uc2_' . $post->currency_id . '">' .(( ! empty($post->currency_code)) ? $post->currency_code : '-') . '</span>';
					$row[] = '<span id="uc6_' . $post->currency_id . '">' .(( ! empty($post->currency_symbol)) ? $post->currency_symbol : '-') . '</span>';
					$row[] = '<span id="uc7_' . $post->currency_id . '">' .(( ! empty($post->t_currency_rate)) ? $post->t_currency_rate : '0.00000') . '</span>';
					$row[] = '<span id="uc8_' . $post->currency_id . '">' .(( ! empty($post->t_fee)) ? $post->t_fee : '0.00') . '</span>';
					$row[] = '<span id="uc9_' . $post->currency_id . '">' .(( ! empty($post->d_currency_rate)) ? $post->d_currency_rate : '0.00000') . '</span>';
					$row[] = '<span id="uc10_' . $post->currency_id . '">' .(( ! empty($post->d_fee)) ? $post->d_fee : '0.00') . '</span>';
					$row[] = '<span id="uc11_' . $post->currency_id . '">' .(( ! empty($post->w_currency_rate)) ? $post->w_currency_rate : '0.00000') . '</span>';
					$row[] = '<span id="uc12_' . $post->currency_id . '">' .(( ! empty($post->w_fee)) ? $post->w_fee : '0.00') . '</span>';
					
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc3_' . $post->currency_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->currency_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}

					$row[] = '<span id="uc4_' . $post->currency_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc5_' . $post->currency_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					$button = '';
					if(permission_validation(PERMISSION_CURRENCIES_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->currency_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_CURRENCIES_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->currency_id . ')" type="button" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i> &nbsp;&nbsp; ';
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
		if(permission_validation(PERMISSION_CURRENCIES_ADD) == TRUE)
		{
			$this->load->view('currencies_add');
		}
		else
		{
			redirect('home');
		}
	}

	public function submit(){
		if(permission_validation(PERMISSION_CURRENCIES_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
							'currency_name_error' => '',
							'currency_code_error' => '',
							'currency_symbol_error' => '',
							'general_error' => ''
						), 		
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);

			$config = array(
				array(
						'field' => 'currency_name',
						'label' => strtolower($this->lang->line('label_currency_name')),
						'rules' => 'trim|required|min_length[1]|max_length[32]',
						'errors' => array(
								'required' => $this->lang->line('error_enter_currency_name'),
								'min_length' => $this->lang->line('error_invalid_currency_name'),
								'max_length' => $this->lang->line('error_invalid_currency_name'),
						)
				),
				array(
						'field' => 'currency_code',
						'label' => strtolower($this->lang->line('label_currency_code')),
						'rules' => 'trim|required|min_length[1]|max_length[4]',
						'errors' => array(
								'required' => $this->lang->line('error_enter_currency_code'),
								'min_length' => $this->lang->line('error_invalid_currency_code'),
								'max_length' => $this->lang->line('error_invalid_currency_code'),
						)
				),
				array(
						'field' => 'currency_symbol',
						'label' => strtolower($this->lang->line('label_currency_symbol')),
						'rules' => 'trim|required|min_length[1]|max_length[4]',
						'errors' => array(
								'required' => $this->lang->line('error_enter_currency_symbol'),
								'min_length' => $this->lang->line('error_invalid_currency_symbol'),
								'max_length' => $this->lang->line('error_invalid_currency_symbol'),
						)
				),
			);
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$allow_to_add = TRUE;
				

				if($allow_to_add == TRUE)
				{
					//Database update
					$this->db->trans_start();
					$newData = $this->currencies_model->add_currencies();
					
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_CURRENCIES_ADD, $newData);
					}
					else
					{
						$this->account_model->insert_log(LOG_CURRENCIES_ADD, $newData);
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
				$json['msg']['currency_name_error'] = form_error('currency_name');
				$json['msg']['currency_code_error'] = form_error('currency_code');
				$json['msg']['currency_symbol_error'] = form_error('currency_symbol');
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
		if(permission_validation(PERMISSION_CURRENCIES_UPDATE) == TRUE)
		{
			$data = $this->currencies_model->get_currencies_data($id);
			$this->load->view('currencies_update', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function update(){
		if(permission_validation(PERMISSION_CURRENCIES_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
							'avatar_name_error' => '',
							'avatar_image_error' => '',
							'general_error' => ''
						),
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			
			//Set form rules
			$config = array(
				array(
						'field' => 'currency_name',
						'label' => strtolower($this->lang->line('label_currency_name')),
						'rules' => 'trim|required|min_length[1]|max_length[32]',
						'errors' => array(
								'required' => $this->lang->line('error_enter_currency_name'),
								'min_length' => $this->lang->line('error_invalid_currency_name'),
								'max_length' => $this->lang->line('error_invalid_currency_name'),
						)
				),
				array(
						'field' => 'currency_code',
						'label' => strtolower($this->lang->line('label_currency_code')),
						'rules' => 'trim|required|min_length[1]|max_length[4]',
						'errors' => array(
								'required' => $this->lang->line('error_enter_currency_code'),
								'min_length' => $this->lang->line('error_invalid_currency_code'),
								'max_length' => $this->lang->line('error_invalid_currency_code'),
						)
				),
				array(
						'field' => 'currency_symbol',
						'label' => strtolower($this->lang->line('label_currency_symbol')),
						'rules' => 'trim|required|min_length[1]|max_length[4]',
						'errors' => array(
								'required' => $this->lang->line('error_enter_currency_symbol'),
								'min_length' => $this->lang->line('error_invalid_currency_symbol'),
								'max_length' => $this->lang->line('error_invalid_currency_symbol'),
						)
				),
				array(
					'field' => 'currency_id',
					'label' => strtolower($this->lang->line('label_hashtag')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_failed_to_update')
					)
				),
			);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$allow_to_update = TRUE;
				$currency_id = trim($this->input->post('currency_id', TRUE));
				$oldData = $this->currencies_model->get_currencies_data($currency_id);

				if( ! empty($oldData))
				{
					if($allow_to_update == TRUE)
					{
						//Database update
						$this->db->trans_start();
						$newData = $this->currencies_model->update_currencies($currency_id);
						
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_CURRENCIES_UPDATE, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_CURRENCIES_UPDATE, $newData, $oldData);
						}
						$this->db->trans_complete();
						
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');
							//Prepare for ajax update
							$json['response'] = array(
								'id' => $newData['currency_id'],
								'currency_name' => (isset($newData['currency_name'])) ? $newData['currency_name'] : $oldData['currency_name'],
								'currency_code' => (isset($newData['currency_code'])) ? $newData['currency_code'] : $oldData['currency_code'],
								'currency_symbol' => (isset($newData['currency_symbol'])) ? $newData['currency_symbol'] : $oldData['currency_symbol'],
								't_currency_rate' => (isset($newData['t_currency_rate'])) ? $newData['t_currency_rate'] : $oldData['t_currency_rate'],
								'd_currency_rate' => (isset($newData['d_currency_rate'])) ? $newData['d_currency_rate'] : $oldData['d_currency_rate'],
								'w_currency_rate' => (isset($newData['w_currency_rate'])) ? $newData['w_currency_rate'] : $oldData['w_currency_rate'],
								't_fee' => (isset($newData['t_fee'])) ? $newData['t_fee'] : $oldData['t_fee'],
								'd_fee' => (isset($newData['d_fee'])) ? $newData['d_fee'] : $oldData['d_fee'],
								'w_fee' => (isset($newData['w_fee'])) ? $newData['w_fee'] : $oldData['w_fee'],
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
					$json['status'] = 6666;
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
				}	
			}
			else 
			{
				$json['msg']['currency_name_error'] = form_error('currency_name');
				$json['msg']['currency_code_error'] = form_error('currency_code');
				$json['msg']['currency_symbol_error'] = form_error('currency_symbol');
				$json['msg']['general_error'] = form_error('bank_id');
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
					
		if(permission_validation(PERMISSION_AVATAR_DELETE) == TRUE)
		{
			$currency_id = $this->uri->segment(3);
			$oldData = $this->currencies_model->get_currencies_data($currency_id);

			if(!empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->currencies_model->delete_currencies($currency_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_CURRENCIES_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_CURRENCIES_DELETE, $oldData);
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