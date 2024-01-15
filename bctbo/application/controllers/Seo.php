<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seo extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('seo_model');
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index()
	{
		if(permission_validation(PERMISSION_SEO_VIEW) == TRUE)
		{
			$this->session->unset_userdata('searches_seo');
			$this->save_current_url('seo');
			
			$data['page_title'] = $this->lang->line('title_seo');
			$this->load->view('seo_view', $data);
		}
		else
		{
			redirect('home');
		}	
	}

	public function search(){
		if(permission_validation(PERMISSION_SEO_VIEW) == TRUE)
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
				'seo_id' => trim($this->input->post('seo_id', TRUE)),
				'status' => trim($this->input->post('status', TRUE)),
				'domain' => trim($this->input->post('domain', TRUE)),
			);
			
			$this->session->set_userdata('searches_seo', $data);
			
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
		if(permission_validation(PERMISSION_SEO_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
				0 => 'seo_key_id',
				1 => 'seo_id',
				2 => 'page_title',
				3 => 'seo_id',
				4 => 'domain',
				5 => 'active',
				6 => 'updated_by',
				7 => 'updated_date'
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
			
			$arr = $this->session->userdata('searches_seo');
			$where = "";

			if(isset($arr['seo_id']) && !empty($arr['seo_id'])){
				$where .= ' AND seo_id = "' . $arr['seo_id']. '"';
			}


			if(isset($arr['status'])){
				if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_SUSPEND)
				{
					$where .= ' AND active = ' . $arr['status'];
				}
			}

			if(isset($arr['domain']) && !empty($arr['domain'])){
				$where .= " AND domain LIKE '%," . $arr['domain'] . ",%' ESCAPE '!'";
			}


			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;

			$posts = NULL;
			$query_string = "SELECT {$select} FROM {$dbprefix}seo WHERE seo_id != 0 $where";
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
					$button = '';
					$domain_text = "";
					$domain_link = "";
					$arr = array();
					if(!empty($post->domain)){
						$arr = explode(',', $post->domain);
						$arr = array_values(array_filter($arr));
						$domain_text = implode(",",$arr);
					}

					if(sizeof($arr)>0){
						foreach($arr as $arr_row){
							if(empty($domain_link)){
								$domain_link .= '<a href="https://'.$arr_row.get_seo_page_link($post->seo_id).'" target="_blank">'.$arr_row.get_seo_page_link($post->seo_id)."</a>";
							}else{
								$domain_link .= '<br/><a href="https://'.$arr_row.get_seo_page_link($post->seo_id).'" target="_blank">'.$arr_row.get_seo_page_link($post->seo_id)."</a>";
							}
						}
					}



					$row = array();
					$row[] = $post->seo_key_id;
					$row[] = $this->lang->line(get_seo_page($post->seo_id));
					$row[] = '<span id="uc1_' . $post->seo_key_id . '">' . $post->page_title . '</span>';
					$row[] = $domain_link;
					$row[] = '<span id="uc7_' . $post->seo_key_id . '">' . $domain_text . '</span>';
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc3_' . $post->seo_key_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->seo_key_id . '">' . $this->lang->line('status_suspend') . '</span>'; break;
					}
					$row[] = '<span id="uc4_' . $post->seo_key_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc5_' . $post->seo_key_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					if(permission_validation(PERMISSION_SEO_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->seo_key_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_SEO_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->seo_key_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_SEO_UPDATE) == TRUE || permission_validation(PERMISSION_SEO_DELETE) == TRUE)
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

    public function add(){
    	if(permission_validation(PERMISSION_SEO_ADD) == TRUE)
		{
			$this->load->view('seo_add', $data);
		}
		else
		{
			redirect('home');
		}
    }

    public function submit(){
		if(permission_validation(PERMISSION_SEO_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
							'page_title_error' => '',
							'meta_keywords_error' => '',
							'meta_descriptions_error' => '',
							'seo_id_error' => '',
							'general_error' => '',
						),
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			
			//Set form rules
			$config = array(
				array(
					'field' => 'page_title',
					'label' => strtolower($this->lang->line('label_page_title')),
					'rules' => 'trim'
				),
				array(
					'field' => 'meta_keywords',
					'label' => strtolower($this->lang->line('label_meta_keywords')),
					'rules' => 'trim'
				),
				array(
					'field' => 'meta_descriptions',
					'label' => strtolower($this->lang->line('label_meta_descriptions')),
					'rules' => 'trim'
				),
				array(
					'field' => 'seo_id',
					'label' => strtolower($this->lang->line('label_name')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_enter_name'),
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
				$newData = $this->seo_model->add_seo();
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_SEO_ADD, $newData);
				}
				else
				{
					$this->account_model->insert_log(LOG_SEO_ADD, $newData);
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
				$json['msg']['page_title_error'] = form_error('page_title');
				$json['msg']['meta_keywords_error'] = form_error('meta_keywords');
				$json['msg']['meta_descriptions_error'] = form_error('meta_descriptions');
				$json['msg']['seo_id_error'] = form_error('seo_id');
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
		if(permission_validation(PERMISSION_SEO_UPDATE) == TRUE)
		{
			$data = $this->seo_model->get_seo_data($id);
			$this->load->view('seo_update', $data);
		}
		else
		{
			redirect('home');
		}	
	}
	
	public function update()
	{
		if(permission_validation(PERMISSION_SEO_UPDATE) == TRUE)
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
						'field' => 'page_title',
						'label' => strtolower($this->lang->line('label_page_title')),
						'rules' => 'trim'
				),
				array(
						'field' => 'meta_keywords',
						'label' => strtolower($this->lang->line('label_meta_keywords')),
						'rules' => 'trim'
				),
				array(
						'field' => 'meta_descriptions',
						'label' => strtolower($this->lang->line('label_meta_descriptions')),
						'rules' => 'trim'
				),
				array(
					'field' => 'seo_key_id',
					'label' => strtolower($this->lang->line('label_hashtag')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_failed_to_update')
					)
				),
				array(
					'field' => 'seo_id',
					'label' => strtolower($this->lang->line('label_name')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_enter_name'),
					)
				),
			);
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$seo_key_id = trim($this->input->post('seo_key_id', TRUE));
				$oldData = $this->seo_model->get_seo_data($seo_key_id);
				
				if( ! empty($oldData))
				{
					//Database update
					$this->db->trans_start();
					$newData = $this->seo_model->update_seo($seo_key_id);
					
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_SEO_UPDATE, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_SEO_UPDATE, $newData, $oldData);
					}
					
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_updated');
						
						$domain_text = "";
						if(!empty($newData['domain'])){
							$arr = explode(',', $newData['domain']);
							$arr = array_values(array_filter($arr));
							$domain_text = implode(",",$arr);
						}

						//Prepare for ajax update
						$json['response'] = array(
							'id' => $newData['seo_key_id'],
							'page_title' => $newData['page_title'],
							'meta_keywords' => $newData['meta_keywords'],
							'meta_descriptions' => $newData['meta_descriptions'],
							'active' => (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_suspend')),
							'active_code' => $newData['active'],
							'domain_code' => $newData['domain'],
							'domain' => $domain_text,
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

	public function delete(){
		//Initial output data
		$json = array(
					'status' => EXIT_ERROR, 
					'msg' => ''
				);
					
		if(permission_validation(PERMISSION_SEO_DELETE) == TRUE)
		{
			$seo_key_id = $this->uri->segment(3);
			$oldData = $this->seo_model->get_seo_data($seo_key_id);

			if(!empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->seo_model->delete_seo($seo_key_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_SEO_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_SEO_DELETE, $oldData);
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