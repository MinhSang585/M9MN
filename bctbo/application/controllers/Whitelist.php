<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Whitelist extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('whitelist_model', 'player_model','user_model'));
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index()
	{
		if(permission_validation(PERMISSION_WHITELIST_VIEW) == TRUE)
		{
			$this->save_current_url('whitelist');
			
			$this->session->unset_userdata('search_whitelist');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_whitelist');
			$data_search = array( 
				'from_date' => date('Y-m-d 00:00:00'),
				'to_date' => date('Y-m-d 23:59:59'),
				'username' => '',
				'type' => '',
				'value' => '',
				'status' => '',
			);
			$this->session->set_userdata('search_whitelist', $data_search);
			$this->load->view('whitelist_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function search(){
		if(permission_validation(PERMISSION_WHITELIST_VIEW) == TRUE)
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
					'username' => trim($this->input->post('username', TRUE)),
					'type' => trim($this->input->post('type', TRUE)),
					'value' => trim($this->input->post('value', TRUE)),
					'status' => trim($this->input->post('status', TRUE)),
				);
				
				$this->session->set_userdata('search_whitelist', $data);
				
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

	public function listing()
    {
    	if(permission_validation(PERMISSION_WHITELIST_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'a.whitelist_id',
				1 => 'b.username',
				2 => 'a.type',
				3 => 'a.value',
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
			$arr = $this->session->userdata('search_whitelist');
			$where = "";
			if( ! empty($arr['from_date']))
			{
				$where .= " AND a.created_date >= " . strtotime($arr['from_date']);
			}
			
			if( ! empty($arr['to_date']))
			{
				$where .= " AND a.created_date <= " . strtotime($arr['to_date']);
			}

			if( ! empty($arr['username']))
			{
				$where .= " AND b.username = '" . trim($arr['username']) . "'";
			}

			if( ! empty($arr['type']))
			{
				$where .= " AND a.type = '" . trim($arr['type']) . "'";
			}

			if( ! empty($arr['value']))
			{
				$where .= " AND a.value LIKE '%" . trim($arr['value']) . "%' ESCAPE '!'";
			}

			if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_SUSPEND)
			{
				$where .= " AND a.active = '" . trim($arr['status']) . "'";
			}

			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;

			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}whitelists a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
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
					$button = "";
					$row = array();
					$row[] = $post->whitelist_id;
					$row[] = '<span id="uc1_' . $post->whitelist_id . '">'.$post->username. '</span>';
					$row[] = '<span id="uc4_' . $post->whitelist_id . '">' . $this->lang->line(get_whitelist_type($post->type)) . '</span>';
					$row[] = '<span id="uc2_' . $post->whitelist_id . '">'.$post->value. '</span>';
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc3_' . $post->whitelist_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->whitelist_id . '">' . $this->lang->line('status_suspend') . '</span>'; break;
					}
					$row[] = (( ! empty($post->created_by)) ? $post->created_by : '-');
					$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
					$row[] = '<span id="uc5_' . $post->whitelist_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc6_' . $post->whitelist_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					if(permission_validation(PERMISSION_WHITELIST_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->whitelist_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_WHITELIST_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->whitelist_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
					}
					if(permission_validation(PERMISSION_WHITELIST_UPDATE) == TRUE || permission_validation(PERMISSION_WHITELIST_DELETE) == TRUE){
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
		if(permission_validation(PERMISSION_WHITELIST_ADD) == TRUE)
		{
			$username = (isset($_GET['username'])?$_GET['username']:'');
			$type = (isset($_GET['type'])?$_GET['type']:'');
			$value = (isset($_GET['value'])?$_GET['value']:'');

			$data['username'] = $username;
			$data['type'] = $type;
			$data['value'] = $value;
			$this->load->view('whitelist_add',$data);
		}
		else
		{
			redirect('home');
		}
	}

	public function submit(){
		if(permission_validation(PERMISSION_WHITELIST_ADD) == TRUE)
		{
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'username_error' => '',
					'type_error' => '',
					'value_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			//Set form rules
			$config = array(
				array(
					'field' => 'username',
					'label' => strtolower($this->lang->line('label_username')),
					'rules' => 'trim|required',
					'errors' => array(
							'required' => $this->lang->line('error_enter_username'),
					)
				),
				array(
					'field' => 'type',
					'label' => strtolower($this->lang->line('label_type')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_select_type'),
					)
				),
				array(
					'field' => 'value',
					'label' => strtolower($this->lang->line('label_white_list_value')),
					'rules' => 'trim|required|min_length[1]|max_length[64]',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_whitelist_value'),
						'min_length' => $this->lang->line('error_invalid_whitelist_value'),
						'max_length' => $this->lang->line('error_invalid_whitelist_value'),
					)
				),
			);

			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run() == TRUE)
			{
				$player = $this->player_model->get_player_data_by_username($this->input->post('username', TRUE));
				if((!empty($player) && $player['active'] == STATUS_ACTIVE))
				{
					$response = $this->user_model->get_downline_data($player['upline']);
					if( ! empty($response))
					{
						$this->db->trans_start();
						$newData = $this->whitelist_model->add_whitelist($player);
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_WHITELIST_ADD, $newData);
						}
						else
						{
							$this->account_model->insert_log(LOG_WHITELIST_ADD, $newData);
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
				}else{
					$json['msg']['username_error'] = $this->lang->line('error_username_not_found');
				}
			}else{
				$json['msg']['type_error'] = form_error('type');
				$json['msg']['value_error'] = form_error('value');
				$json['msg']['username_error'] = form_error('username');
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
		if(permission_validation(PERMISSION_WHITELIST_UPDATE) == TRUE)
		{
			$data = $this->whitelist_model->get_whitelist_data($id);
			$data['player'] = $this->player_model->get_player_data($data['player_id']);
			$this->load->view('whitelist_update',$data);
		}
		else
		{
			redirect('home');
		}
	}

	public function update(){
		if(permission_validation(PERMISSION_WHITELIST_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'type_error' => '',
					'value_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);

			//Set form rules
			$config = array(
				array(
					'field' => 'type',
					'label' => strtolower($this->lang->line('label_type')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_select_type'),
					)
				),
				array(
					'field' => 'value',
					'label' => strtolower($this->lang->line('label_white_list_value')),
					'rules' => 'trim|required|min_length[1]|max_length[64]',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_whitelist_value'),
						'min_length' => $this->lang->line('error_invalid_whitelist_value'),
						'max_length' => $this->lang->line('error_invalid_whitelist_value'),
					)
				),
			);	
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$whitelist_id = trim($this->input->post('whitelist_id', TRUE));
				$oldData = $this->whitelist_model->get_whitelist_data($whitelist_id);

				if( ! empty($oldData))
				{
					$allow_to_update = TRUE;
					if($allow_to_update == TRUE)
					{
						//Database update
						$this->db->trans_start();
						$newData = $this->whitelist_model->update_whitelist($whitelist_id);
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_WHITELIST_UPDATE, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_WHITELIST_UPDATE, $newData, $oldData);
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');
							
							//Prepare for ajax update
							$json['response'] = array(
								'id' => $newData['whitelist_id'],
								'value' => $newData['value'],
								'active' => (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
								'active_code' => $newData['active'],
								'updated_by' => $newData['updated_by'],
								'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),
								'type' => $this->lang->line(get_whitelist_type($newData['type'])),
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
				$json['msg']['type_error'] = form_error('type');
				$json['msg']['value_error'] = form_error('value');
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
					
		if(permission_validation(PERMISSION_WHITELIST_DELETE) == TRUE)
		{
			$whitelist_id = $this->uri->segment(3);
			$oldData = $this->whitelist_model->get_whitelist_data($whitelist_id);
			if( ! empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->whitelist_model->delete_whitelist($whitelist_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_WHITELIST_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_WHITELIST_DELETE, $oldData);
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