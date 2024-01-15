<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('contact_model');
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}
		
	public function index()
	{
		if(permission_validation(PERMISSION_CONTACT_VIEW) == TRUE)
		{
			$this->save_current_url('contact');
			
			$data['page_title'] = $this->lang->line('title_contact');
			$this->load->view('contact_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	
	public function listing()
    {
		if(permission_validation(PERMISSION_CONTACT_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
								0 => 'contact_id',
								1 => 'im_name',
								2 => 'im_value',
								3 => 'active',
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
							'table' => 'contacts',
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
					$row[] = $post->contact_id;
					$row[] = $this->lang->line($post->im_name);
					$row[] = '<span id="uc1_' . $post->contact_id . '">' . $post->im_value . '</span>';
					
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc2_' . $post->contact_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc2_' . $post->contact_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					
					$row[] = '<span id="uc3_' . $post->contact_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc4_' . $post->contact_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					
					if(permission_validation(PERMISSION_CONTACT_UPDATE) == TRUE)
					{
						$row[] = '<i onclick="updateData(' . $post->contact_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i>';
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
		if(permission_validation(PERMISSION_CONTACT_UPDATE) == TRUE)
		{
			$data = $this->contact_model->get_contact_data($id);
			$this->load->view('contact_update', $data);
		}
		else
		{
			redirect('home');
		}
	}
	
	public function update()
	{
		if(permission_validation(PERMISSION_CONTACT_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => '', 
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			
			//Set form rules
			$config = array(
							array(
									'field' => 'im_value',
									'label' => strtolower($this->lang->line('label_value')),
									'rules' => 'trim'
							)
						);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$contact_id = trim($this->input->post('contact_id', TRUE));
				$oldData = $this->contact_model->get_contact_data($contact_id);
				
				if( ! empty($oldData))
				{
					//Database update
					$this->db->trans_start();
					$newData = $this->contact_model->update_contact($contact_id);
					
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_CONTACT_UPDATE, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_CONTACT_UPDATE, $newData, $oldData);
					}
					
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_updated');
						
						//Prepare for ajax update
						$json['response'] = array(
												'id' => $newData['contact_id'],
												'im_value' => $newData['im_value'],
												'active' => (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive')),
												'active_code' => $newData['active'],
												'updated_by' => $newData['updated_by'],
												'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),
											);
					}
					else
					{
						$json['msg'] = $this->lang->line('error_failed_to_update');
					}	
				}
				else
				{
					$json['msg'] = $this->lang->line('error_failed_to_update');
				}		
			}
			else 
			{
				$json['msg'] = $this->lang->line('error_failed_to_update');
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