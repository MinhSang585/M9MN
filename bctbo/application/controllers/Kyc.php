<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Kyc extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('bank_model', 'player_model'));
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index()
	{
		if(permission_validation(PERMISSION_KYC_VIEW) == TRUE)
		{
			$this->save_current_url('kyc');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('label_kyc');
			$this->session->unset_userdata('search_kyc');
			$data_search = array(
				'bank_name' => "",
				'bank_code' => "",
				'username' => "",
			);
			$this->session->set_userdata('search_kyc', $data_search);
			$this->load->view('kyc_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function search()
	{
		if(permission_validation(PERMISSION_KYC_VIEW) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'error_enter_bank_code' =>  '',
					'username_error' =>  '',
					'general_error' => ''
							), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()

			);
			//Set form rules
			$config = array(
				array(
					'field' => 'bank_code',
					'label' => strtolower($this->lang->line('label_code')),
					'rules' => 'trim',
					'errors' => array(
							'required' => $this->lang->line('error_enter_bank_code')
						)
					),

				array(

					'field' => 'username',
					'label' => strtolower($this->lang->line('label_username')),
					'rules' => 'trim',
					'errors' => array(
							'required' => $this->lang->line('username_error')
					)
				)
			);
			
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$data = array(
					'bank_name' => trim($this->input->post('bank_name', TRUE)),
					'number_account' => trim($this->input->post('bank_code', TRUE)),
					'username' => trim($this->input->post('username', TRUE)),
				);
				$this->session->set_userdata('search_kyc', $data);
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
		if(permission_validation(PERMISSION_KYC_VIEW) == TRUE)
		{
			$order = $this->input->post("order", TRUE);

			//Table Columns

			$columns = array( 

				0 => 'a.kyc_id',

				1 => 'b.player_id',

				2 => 'a.number_account',

				3 => 'a.bank_id',

				4 => 'a.status',

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

			$arr = $this->session->userdata('search_kyc');	

			$where = '';		

			if( ! empty($arr['bank_name']))
			{
				$where .= " AND c.bank_name LIKE '%" . $arr['bank_name'] . "%' ESCAPE '!'";
			}
			if( ! empty($arr['number_account']))
			{
				$where .= " AND a.number_account LIKE '%" . $arr['number_account'] . "%' ESCAPE '!'";
			}
			if( ! empty($arr['username']))
			{
				$where .= " AND b.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";
			}
			
			$select = implode(',', $columns);

			$dbprefix = $this->db->dbprefix;

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

			// maping bank_id to bank_name
			$sql = "SELECT bank_id, bank_name FROM {$dbprefix}banks";
			$query = $this->db->query($sql);
			$bankList = array();
			if($query->num_rows() > 0)
			{
				$bankList = $query->result();  
			}
			$query->free_result();
			$arrBank = array();
			foreach($bankList as $bankItem){
				$arrBank[$bankItem->bank_id] = $bankItem->bank_name;
			}

			$posts = NULL;

			$query_string = "SELECT {$select}
			FROM {$dbprefix}kyc AS a
			INNER JOIN {$dbprefix}players AS b ON a.player_id = b.player_id
			INNER JOIN {$dbprefix}banks AS c ON a.bank_id = c.bank_id WHERE 1=1 " . $where;
			
			$query_string_2 = " ORDER by {$order} {$dir}";

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

					$row[] = $post->kyc_id;

					$row[] = '<span id="uc5_' . $post->kyc_id . '">' . $arrBank[$post->bank_id] . '</span>';

					$row[] = '<span id="uc6_' . $post->kyc_id . '">' . $post->number_account . '</span>';

					$row[] = '<span id="uc9_' . $post->kyc_id . '">' . $arrPlayer[$post->player_id] . '</span>';

					if($post->status == STATUS_CANCEL){

						$row[] = '<span class="badge bg-danger" id="uc1_' . $post->kyc_id . '">' . $this->lang->line('status_system_cancel') . '</span>';

					}else{

						switch($post->status)
						{

							case STATUS_APPROVE: $row[] = '<span class="badge bg-success" id="uc1_' . $post->kyc_id . '">' . $this->lang->line('status_approved') . '</span>'; break;

							case STATUS_CANCEL: $row[] = '<span class="badge bg-danger" id="uc1_' . $post->kyc_id . '">' . $this->lang->line('status_cancelled') . '</span>'; break;

							default: $row[] = '<span class="badge bg-secondary" id="uc1_' . $post->kyc_id . '">' . $this->lang->line('status_pending') . '</span>'; break;
						}

					}

					$row[] = '<span id="uc3_' . $post->kyc_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';

					$row[] = '<span id="uc4_' . $post->kyc_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';

					$button = '';

					if(permission_validation(PERMISSION_KYC_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->kyc_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_BANK_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->kyc_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
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
		if(permission_validation(PERMISSION_KYC_ADD) == TRUE)
		{
			if(!empty($id)){
    			$data = $this->player_model->get_player_data($id);

    			$data['bank_list'] = $this->bank_model->get_bank_list();

				$this->load->view('kyc_add',$data);
	    	}else{

	    		$data['bank_list'] = $this->bank_model->get_bank_list();

				$this->load->view('kyc_add',$data);
	    	}
		}
		else
		{
			redirect('home');
		}
	}

	public function submit()
	{
		if(permission_validation(PERMISSION_KYC_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
							'error_enter_bank_code' =>  '',
							'username_error' =>  '',
							'general_error' => ''
									), 		
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()

					);
			//Set form rules

			$config = array(

				array(
					'field' => 'bank_code',
					'label' => strtolower($this->lang->line('label_code')),
					'rules' => 'trim',
					'errors' => array(
							'required' => $this->lang->line('error_enter_bank_code')
						)
					),

				array(

					'field' => 'username',
					'label' => strtolower($this->lang->line('label_username')),
					'rules' => 'trim',
					'errors' => array(
							'required' => $this->lang->line('username_error')
					)
				)
			);		

			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');

			//Form validation

			if ($this->form_validation->run() == TRUE)
			{
				$isUpdate = TRUE;

				// $config['upload_path'] = BANKS_PATH;
				$config['upload_path'] = './uploads/kyc/';

				// check if uploads/files exists, if not, create it
				if (!is_dir($config['upload_path'])) {
					if (!mkdir($config['upload_path'], 0777, true)) {
						$json['msg']['general_error'] = 'Không thể tạo thư mục uploads/kyc/';
					}
				}

				$config['max_size'] = BANKS_FILE_SIZE;

				$config['allowed_types'] = 'gif|jpg|jpeg|png';

				$config['overwrite'] = TRUE;

				$this->load->library('upload', $config);

				if($isUpdate == TRUE)
				{
					if(isset($_FILES['front_image']['size']) && $_FILES['front_image']['size'] > 0)
					{

						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["front_image"]['name'], PATHINFO_EXTENSION);

						$config['file_name']  = $new_name;

						$this->upload->initialize($config);

						if( ! $this->upload->do_upload('front_image')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');

							$isUpdate = FALSE;
						}else{

							$_FILES["front_image"]['name'] = $new_name;
						}
					}
				}

				if($isUpdate == TRUE)
				{
					if(isset($_FILES['back_image']['size']) && $_FILES['back_image']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["back_image"]['name'], PATHINFO_EXTENSION);

						$config['file_name']  = $new_name;

						$this->upload->initialize($config);

						if( ! $this->upload->do_upload('back_image')) 
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');

							$isUpdate = FALSE;
						}else{

							$_FILES["back_image"]['name'] = $new_name;
						}
					}
				}

				if($isUpdate == TRUE)
				{
					if(isset($_FILES['bank_statement_image']['size']) && $_FILES['bank_statement_image']['size'] > 0)
					{
						$new_name = time().rand(1000,9999).".".pathinfo($_FILES["bank_statement_image"]['name'], PATHINFO_EXTENSION);

						$config['file_name']  = $new_name;

						$this->upload->initialize($config);

						if( ! $this->upload->do_upload('bank_statement_image'))
						{
							$json['msg']['general_error'] = $this->lang->line('error_invalid_filetype');

							$isUpdate = FALSE;
						}else{

							$_FILES["bank_statement_image"]['name'] = $new_name;
						}
					}
				}

				if($isUpdate == TRUE)
				{
					$player_username = trim($this->input->post('username', TRUE));

					$playerData = $this->player_model->get_player_data_by_username($player_username);

					if(!empty($playerData))
					{
							//Database update
						$this->db->trans_start();

						$newData = $this->bank_model->addKYC($playerData);

						$this->db->trans_complete();

						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_added');
							// Prepare for ajax update
							$json['response'] = array(
								'kyc_id' => $newData['kyc_id'],
								'bank_id' => $newData['bank_id'],
								'bank_code' => $newData['number_account'],
								'player_id' => $newData['player_id'],
							);
						}
						else
						{
							$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');
						}
					}
					else
					{
						$json['msg']['general_error'] = $this->lang->line('error_username_not_found');
					}
				}
			}
			else 
			{
				$json['msg']['error_enter_bank_code'] = form_error('bank_code');
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

	public function edit($id = NULL)
    {
		if(permission_validation(PERMISSION_KYC_UPDATE) == TRUE)
		{
			$data = $this->bank_model->getKYCData($id);
			$playerData = $this->player_model->get_player_data($data['player_id']);
			$data['user_name'] = $playerData['username'];
			$bankData =  $this->bank_model->get_bank_data($data['bank_id']);
			$data['bank_name'] = $bankData['bank_name'];

			$this->load->view('kyc_update', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function update()
	{
		if(permission_validation(PERMISSION_KYC_UPDATE) == TRUE)
		{
			$kycId = trim($this->input->post('kyc_id', TRUE));
			$oldData = $this->bank_model->getKYCData($kycId);

			if(!empty($oldData))
			{
				$this->db->trans_start();

				$newData = $this->bank_model->updateKyc($kycId);

				$this->db->trans_complete();

				if ($this->db->trans_status() === TRUE)
				{
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('success_updated');
					//Prepare for ajax update
					$json['response'] = array(
						'kyc_id' => $newData['kyc_id'],
						'status' => $newData['status'],
						'updated_by' => $newData['updated_by'],
						'updated_date' => $newData['updated_date'],
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
			$kycId = $this->uri->segment(3);
			$oldData = $this->bank_model->getKYCData($kycId);
			if( ! empty($oldData))
			{
				//Database update

				$this->db->trans_start();

				$this->bank_model->deleteKyc($kycId);

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