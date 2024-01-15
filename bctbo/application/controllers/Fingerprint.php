<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fingerprint extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('fingerprint_model', 'player_model'));
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index(){
		if(permission_validation(PERMISSION_FINGERPRINT_VIEW) == TRUE)
		{
			$this->save_current_url('fingerprint');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_fingerprint');
			$this->session->unset_userdata('searches_fingerprint');
			$data_search = array(
				'from_date' => date('Y-m-d 00:00:00'),
				'to_date' => date('Y-m-d 23:59:59'),
			);
			$this->session->set_userdata('searches_fingerprint', $data_search);
			$this->load->view('fingerprint_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function search(){
		if(permission_validation(PERMISSION_FINGERPRINT_VIEW) == TRUE)
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
				'from_date' => trim($this->input->post('from_date', TRUE)),
				'to_date' => trim($this->input->post('to_date', TRUE)),
				'username' => trim($this->input->post('username', TRUE)),
				'fingerprint_code' => trim($this->input->post('fingerprint_code', TRUE)),
				'ip_address' => trim($this->input->post('ip_address', TRUE)),
			);
			
			$this->session->set_userdata('searches_fingerprint', $data);
			
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
		if(permission_validation(PERMISSION_FINGERPRINT_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'fingerprint_id',
				1 => 'username',
				2 => 'fingerprint_code',
				3 => 'ip_address',
				4 => 'created_by',
				5 => 'created_date',
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
			$arr = $this->session->userdata('searches_fingerprint');
			$where = "";
			if( ! empty($arr['from_date']))
			{
				if($where == ""){
					$where .= "WHERE created_date >= " . strtotime($arr['from_date']);
				}else{
					$where .= " AND created_date >= " . strtotime($arr['from_date']);
				}
			}
			
			if( ! empty($arr['to_date']))
			{
				if($where == ""){
					$where .= "WHERE created_date <= " . strtotime($arr['to_date']);
				}else{
					$where .= " AND created_date <= " . strtotime($arr['to_date']);
				}
			}

			if( ! empty($arr['username']))
			{
				if($where == ""){
					$where .= "WHERE username = '" . trim($arr['username']) . "'";
				}else{
					$where .= " AND username = '" . trim($arr['username']) . "'";
				}
			}

			if( ! empty($arr['ip_address']))
			{
				if($where == ""){
					$where .= "WHERE ip_address = '" . trim($arr['ip_address']) . "'";
				}else{
					$where .= " AND ip_address = '" . trim($arr['ip_address']) . "'";
				}
			}

			if( ! empty($arr['fingerprint_code']))
			{
				if($where == ""){
					$where .= "WHERE fingerprint_code = '" . trim($arr['fingerprint_code']) . "'";
				}else{
					$where .= " AND fingerprint_code = '" . trim($arr['fingerprint_code']) . "'";
				}
			}
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;

			$posts = NULL;
			$query_string = "SELECT {$select} FROM {$dbprefix}fingerprint $where";
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
					$row[] = $post->fingerprint_id;
					$row[] = $post->username;
					$row[] = $post->fingerprint_code;
					$row[] = $post->ip_address;
					$row[] = (( ! empty($post->created_by)) ? $post->created_by : '-');
					$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
					if(permission_validation(PERMISSION_FINGERPRINT_VIEW) == TRUE)
					{
						$button .= '<i onclick="similiar_fingerprint_code(' . $post->fingerprint_id . ')" class="fas fa-users nav-icon text-primary" title="' . $this->lang->line('button_view')  . '"></i> &nbsp;&nbsp; ';
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

	public function similiar_fingerprint_code_search(){
		if(permission_validation(PERMISSION_FINGERPRINT_VIEW) == TRUE)
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
				'username' => trim($this->input->post('username', TRUE)),
				'ip_address' => trim($this->input->post('ip_address', TRUE)),
			);
			
			$this->session->set_userdata('searches_similair_fingerprint', $data);
			
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

	public function similiar_fingerprint_code($id){
		if(permission_validation(PERMISSION_FINGERPRINT_VIEW) == TRUE)
		{
			$data = $this->fingerprint_model->get_fingerprint_data($id);
			if( ! empty($data))
			{
				$data['page_title'] = $this->lang->line('title_similiar_fingerprint');
				$this->session->unset_userdata('searches_similair_fingerprint');
				$data_search = array(
					'fingerprint_code' => $data['fingerprint_code'],
					'exp_username' => $data['username'],
				);
				$this->session->set_userdata('searches_similair_fingerprint', $data_search);
				$this->load->view('fingerprint_similair_view', $data);
			}else
			{
				redirect('home');
			}
		}
		else
		{
			redirect('home');
		}
	}

	public function fingerprint_similiar_listing(){
		if(permission_validation(PERMISSION_FINGERPRINT_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'fingerprint_id',
				1 => 'username',
				2 => 'fingerprint_code',
				3 => 'ip_address',
				4 => 'created_by',
				5 => 'created_date',
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
			$arr = $this->session->userdata('searches_similair_fingerprint');
			$where = "";

			if( ! empty($arr['exp_username']))
			{
				if($where == ""){
					$where .= "WHERE username != '" . trim($arr['exp_username']) . "'";
				}else{
					$where .= " AND username != '" . trim($arr['exp_username']) . "'";
				}
			}

			if( ! empty($arr['username']))
			{
				if($where == ""){
					$where .= "WHERE username = '" . trim($arr['username']) . "'";
				}else{
					$where .= " AND username = '" . trim($arr['username']) . "'";
				}
			}

			if( ! empty($arr['ip_address']))
			{
				if($where == ""){
					$where .= "WHERE ip_address = '" . trim($arr['ip_address']) . "'";
				}else{
					$where .= " AND ip_address = '" . trim($arr['ip_address']) . "'";
				}
			}

			if( ! empty($arr['fingerprint_code']))
			{
				if($where == ""){
					$where .= "WHERE fingerprint_code = '" . trim($arr['fingerprint_code']) . "'";
				}else{
					$where .= " AND fingerprint_code = '" . trim($arr['fingerprint_code']) . "'";
				}
			}
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;
			$posts = NULL;
			$query_string = "SELECT {$select} FROM {$dbprefix}fingerprint $where";
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
					$row[] = $post->fingerprint_id;
					$row[] = $post->username;
					$row[] = $post->fingerprint_code;
					$row[] = $post->ip_address;
					$row[] = (( ! empty($post->created_by)) ? $post->created_by : '-');
					$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
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
}