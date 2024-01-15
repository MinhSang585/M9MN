<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Blog extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('blog_model','game_model','seo_model'));

		$is_logged_in = $this->is_logged_in();
		if (!empty($is_logged_in)) {
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function index()
	{
		if(permission_validation(PERMISSION_BLOG_VIEW) == TRUE)
		{
			$this->save_current_url('blog');
			$data = quick_search();
			$data_search = array( 
				'from_date' => "",
				'to_date' => "",
				'blog_name' => '',
				'blog_display' => '',
				'blog_category_id' => '',
				'blog_pathname' => '',
				'status' => '-1',
			);
			$data['data_search'] = $data_search;
			$data['blog_category'] = $this->blog_model->get_blogs_category_list();
			$this->session->set_userdata('search_blogs', $data_search);
			$data['page_title'] = $this->lang->line('label_blog');
			$this->load->view('blog_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function search(){
		if(permission_validation(PERMISSION_BLOG_VIEW) == TRUE)
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
				$category_id = "";
				if($this->input->post('blog_display', TRUE) == BLOG_DISPLAY_BLOG){
					$category_id = trim($this->input->post('blog_category_id', TRUE));
				}else if($this->input->post('blog_display', TRUE) == BLOG_DISPLAY_PAGE){
					$category_id = trim($this->input->post('page_category_id', TRUE));
				}else if($this->input->post('blog_display', TRUE) == BLOG_DISPLAY_PRODUCT){
					$category_id = trim($this->input->post('product_category_id', TRUE));
				}

				$data = array( 
					'from_date' => trim($this->input->post('from_date', TRUE)),
					'to_date' => trim($this->input->post('to_date', TRUE)),
					'blog_name' => trim($this->input->post('blog_name', TRUE)),
					'blog_display' => trim($this->input->post('blog_display', TRUE)),
					'blog_category_id' => $category_id,
					'blog_pathname' => trim($this->input->post('blog_pathname', TRUE)),
					'status' => trim($this->input->post('status', TRUE)),
					'domain' => trim($this->input->post('domain', TRUE)),
				);
				
				$this->session->set_userdata('search_blogs', $data);
				
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
		if(permission_validation(PERMISSION_BLOG_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
				0 => 'a.blog_id',
				1 => 'a.blog_name',
				2 => 'a.blog_display',
				3 => 'a.blog_category_name',
				4 => 'a.blog_pathname',
				5 => 'domain',
				6 => 'a.active',
				7 => 'a.created_by',
				8 => 'a.created_date',
				9 => 'a.updated_by',
				10 => 'a.updated_date',
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
			
			$arr = $this->session->userdata('search_blogs');	
			$where = 'WHERE blog_id != "a"';		
			

			if(isset($arr['from_date']))
			{
				if( ! empty($arr['from_date'])){
					$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);
				}
			}
			if( ! empty($arr['to_date']))
			{
				if( ! empty($arr['to_date'])){
					$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);
				}
			}

			if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_INACTIVE)
			{
				$where .= ' AND a.active = ' . $arr['status'];
			}

			if(!empty($arr['blog_name']))
			{
				$where .= " AND a.blog_name LIKE '%" . $arr['blog_name'] . "%' ESCAPE '!'";
			}

			if(!empty($arr['blog_display']))
			{
				$where .= " AND a.blog_display = '" . $arr['blog_display']."'";	
			}

			if(!empty($arr['blog_category_id']))
			{
				$where .= " AND a.blog_category_id = '" . $arr['blog_category_id']."'";	
			}


			if(!empty($arr['blog_pathname']))
			{
				$where .= " AND a.blog_pathname = '" . $arr['blog_pathname']."'";	
			}

			if(isset($arr['domain']) && !empty($arr['domain'])){
				$where .= " AND domain LIKE '%," . $arr['domain'] . ",%' ESCAPE '!'";
			}

			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;

			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}blogs a $where)";
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
					$domain_text = "";
					if(!empty($post->domain)){
						$arr = explode(',', $post->domain);
						$arr = array_values(array_filter($arr));
						$domain_text = implode(",",$arr);
					}
					$row[] = $post->blog_id;
					$row[] = '<span id="uc1_' . $post->blog_id . '">' . $post->blog_name . '</span>';
					$row[] = '<span id="uc7_' . $post->player_id . '">' . $this->lang->line(get_blog_display($post->blog_display)) . '</span>';
					if($post->blog_display == BLOG_DISPLAY_BLOG){
						$row[] = '<span id="uc6_' . $post->blog_id . '">' . (( ! empty($post->blog_category_name)) ? $post->blog_category_name : '-') . '</span>';
					}else{
						$row[] = '<span id="uc6_' . $post->blog_id . '">' . (( ! empty($post->blog_category_name)) ? $this->lang->line($post->blog_category_name) : '-') . '</span>';
					}
					$row[] = '<span id="uc8_' . $post->blog_id . '">' . $post->blog_pathname . '</span>';
					$row[] = '<span id="uc9_' . $post->seo_key_id . '">' . $domain_text . '</span>';
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc2_' . $post->blog_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc2_' . $post->blog_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					$row[] = (( ! empty($post->created_by)) ? $post->created_by : '-');
					$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
					$row[] = '<span id="uc3_' . $post->blog_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc4_' . $post->blog_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					
					$button = '';
					if(permission_validation(PERMISSION_BLOG_FRONTEND_VIEW) == TRUE)
					{
						$button .= '<i onclick="viewData(' . $post->blog_id . ')" class="fas fa-file nav-icon text-info" title="' . $this->lang->line('button_view')  . '"></i> &nbsp;&nbsp; ';
					}

					if(permission_validation(PERMISSION_BLOG_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->blog_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_BLOG_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->blog_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
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
		if(permission_validation(PERMISSION_BLOG_ADD) == TRUE)
		{
			$data['blog_category'] = $this->blog_model->get_blogs_category_list();
			//$data['page_category'] = $this->seo_model->get_seo_list();
			$data['product_category'] = $this->game_model->get_game_list_name();
			$this->load->view('blog_add',$data);
		}
		else
		{
			redirect('home');
		}
	}

	public function submit()
	{
		if(permission_validation(PERMISSION_BLOG_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'blog_name_error' => '',
										'blog_category_id_error' =>  '',
										'blog_pathname_error' => '',
										'general_error' => ''
									), 		
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			
			//Set form rules
			$config = array(
				array(
						'field' => 'blog_name',
						'label' => strtolower($this->lang->line('label_name')),
						'rules' => 'trim|required',
						'errors' => array(
								'required' => $this->lang->line('error_enter_name'),
						)
				),
				array(
					'field' => 'blog_pathname',
					'label' => strtolower($this->lang->line('label_blog_pathname')),
					//'rules' => 'trim|required|is_unique[blogs.blog_pathname]',
					'rules' => 'trim|required',
					'errors' => array(
							'required' => $this->lang->line('error_enter_blog_pathname'),
							//'is_unique' => $this->lang->line('error_blog_pathname_exits'),
					)
				),
			);

			$blog_display = trim($this->input->post('blog_display', TRUE));
			$is_allow = false;
			$category_id = 0;
			$category_name = "";
			if($blog_display == BLOG_DISPLAY_BLOG){
				$category_id = trim($this->input->post('blog_category_id', TRUE));
				$categoryData = $this->blog_model->get_blogs_category_data($category_id);
				if(!empty($categoryData)){
					$is_allow = true;
					$category_name = $categoryData['blog_category_name'];
				}
			}else if($blog_display == BLOG_DISPLAY_PAGE){
				$category_id = trim($this->input->post('page_category_id', TRUE));
				$category_name = get_blog_page($category_id);
				$is_allow = true;
			}else{
				$category_id = trim($this->input->post('product_category_id', TRUE));
				$category_name = get_blog_product($category_id);
				$is_allow = true;
			}

			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$allow_to_update = TRUE;
				if($allow_to_update == TRUE)
				{	
					if($is_allow){
						$this->db->trans_start();
						$newData = $this->blog_model->add_blog($category_id,$category_name);
						$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
						if(sizeof($lang)>0){
							foreach($lang as $k => $v){
								$this->blog_model->add_blog_content($newData['blog_id'],$v);
							}
						}
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_BLOG_ADD, $newData);
						}
						else
						{
							$this->account_model->insert_log(LOG_BLOG_ADD, $newData);
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
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');
				}
			}
			else 
			{
				$json['msg']['blog_name_error'] = form_error('blog_name');
				$json['msg']['blog_pathname_error'] = form_error('blog_pathname');
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
		if(permission_validation(PERMISSION_BLOG_UPDATE) == TRUE)
		{
			$data['blog'] = $this->blog_model->get_blog_data($id);
			if(!empty($data['blog'])){
				$data['blog_category'] = $this->blog_model->get_blogs_category_list();
				//$data['page_category'] = $this->seo_model->get_seo_list();
				$data['product_category'] = $this->game_model->get_game_list_name();
				$data['blog_lang'] = $this->blog_model->get_blog_lang_data($id);
				$this->load->view('blog_update',$data);
			}else{
				redirect('home');
			}
		}
		else
		{
			redirect('home');
		}
	}

	public function update(){
		if(permission_validation(PERMISSION_BLOG_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'blog_name_error' => '',
					'blog_category_id_error' =>  '',
					'blog_pathname_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			$blog_id = trim($this->input->post('blog_id', TRUE));
			$oldData = $this->blog_model->get_blog_data($blog_id);
			$blog_pathname = trim($this->input->post('blog_pathname', TRUE));
			if(!empty($oldData)){
				//Set form rules
				$config = array(
					array(
							'field' => 'blog_name',
							'label' => strtolower($this->lang->line('label_name')),
							'rules' => 'trim|required',
							'errors' => array(
									'required' => $this->lang->line('error_enter_name'),
							)
					),
				);		
				/*
				if($oldData['blog_pathname'] != $blog_pathname){
					$configAdd = array(
						'field' => 'blog_pathname',
						'label' => strtolower($this->lang->line('label_blog_pathname')),
						'rules' => 'trim|required|is_unique[blogs.blog_pathname]',
						'errors' => array(
								'required' => $this->lang->line('error_enter_blog_pathname'),
								'is_unique' => $this->lang->line('error_blog_pathname_exits'),
						)
					);
					array_push($config, $configAdd);
				}
				*/
							
				$this->form_validation->set_rules($config);
				$this->form_validation->set_error_delimiters('', '');
				
				//Form validation
				if ($this->form_validation->run() == TRUE)
				{
					$blog_display = trim($this->input->post('blog_display', TRUE));
					$categoryData = array();
					$is_allow = false;
					$category_id = 0;
					$category_name = "";
					if($blog_display == BLOG_DISPLAY_BLOG){
						$category_id = trim($this->input->post('blog_category_id', TRUE));
						$categoryData = $this->blog_model->get_blogs_category_data($category_id);
						if(!empty($categoryData)){
							$is_allow = true;
							$category_name = $categoryData['blog_category_name'];
						}
					}else if($blog_display == BLOG_DISPLAY_PAGE){
						$category_id = trim($this->input->post('page_category_id', TRUE));
						$category_name = get_blog_page($category_id);
						$is_allow = true;
					}else{
						$category_id = trim($this->input->post('product_category_id', TRUE));
						$category_name = get_blog_product($category_id);
						$is_allow = true;
					}
					
					$allow_to_update = TRUE;
					
					if($allow_to_update){
						if($is_allow){
							$this->db->trans_start();
							$newData = $this->blog_model->update_blog($blog_id,$category_id,$category_name);
							$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
							if(sizeof($lang)>0){
								$oldDataLang = $this->blog_model->get_blog_lang_data($newData['blog_id']);
								foreach($lang as $k => $v){
									if(isset($oldDataLang[$v])){
										$this->blog_model->update_blog_content($newData['blog_id'],$v);
									}else{
										$this->blog_model->add_blog_content($newData['blog_id'],$v);
									}
								}
							}
							if($this->session->userdata('user_group') == USER_GROUP_USER) 
							{
								$this->user_model->insert_log(LOG_BLOG_UPDATE, $newData, $oldData);
							}
							else
							{
								$this->account_model->insert_log(LOG_BLOG_UPDATE, $newData, $oldData);
							}
							$this->db->trans_complete();
							if ($this->db->trans_status() === TRUE)
							{
								$json['status'] = EXIT_SUCCESS;
								$json['msg'] = $this->lang->line('success_updated');
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
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
					}
				}
				else 
				{
					$json['msg']['blog_name_error'] = form_error('blog_name');
					
				}
			}else{
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

	public function delete()
    {
		//Initial output data
		$json = array(
					'status' => EXIT_ERROR, 
					'msg' => ''
				);
					
		if(permission_validation(PERMISSION_BLOG_DELETE) == TRUE)
		{
			$blog_id = $this->uri->segment(3);
			$oldData = $this->blog_model->get_blog_data($blog_id);
			
			if( ! empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->blog_model->delete_blog($blog_id);
				$this->blog_model->delete_blog_lang($blog_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_BLOG_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_BLOG_DELETE, $oldData);
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

	public function category(){
		if(permission_validation(PERMISSION_BLOG_CATEGORY_VIEW) == TRUE)
		{
			$this->save_current_url('blog/category');
			
			$data['page_title'] = $this->lang->line('title_blog_category');
			$this->load->view('blog_category_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function listing_category(){
		if(permission_validation(PERMISSION_BLOG_CATEGORY_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
				0 => 'blog_category_id',
				1 => 'blog_category_name',
				2 => 'blog_category_pathname',
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
					'table' => 'blogs_category',
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
					$row[] = $post->blog_category_id;
					$row[] = '<span id="uc1_' . $post->blog_category_id . '">' . $post->blog_category_name . '</span>';
					$row[] = '<span id="uc3_' . $post->blog_category_id . '">' . $post->blog_category_pathname . '</span>';
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc2_' . $post->blog_category_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc2_' . $post->blog_category_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					
					$row[] = '<span id="uc5_' . $post->blog_category_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc6_' . $post->blog_category_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					
					$button = '';
					if(permission_validation(PERMISSION_BLOG_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->blog_category_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_BLOG_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->blog_category_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
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

	public function add_category(){
		if(permission_validation(PERMISSION_BLOG_CATEGORY_ADD) == TRUE)
		{
			$this->load->view('blog_category_add');
		}
		else
		{
			redirect('home');
		}
	}

	public function submit_category(){
		if(permission_validation(PERMISSION_BLOG_CATEGORY_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'blog_category_name_error' => '',
					'blog_pathname_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);

			$config = array(
				array(
					'field' => 'blog_category_name',
					'label' => strtolower($this->lang->line('label_blog_category')),
					'rules' => 'trim|required|max_length[36]',
					'errors' => array(
						'required' => $this->lang->line('error_enter_blog_category'),
						'max_length' => $this->lang->line('error_invalid_blog_category'),
					)
				),
				array(
					'field' => 'blog_category_pathname',
					'label' => strtolower($this->lang->line('label_blog_pathname')),
					'rules' => 'trim|required|is_unique[blogs_category.blog_category_pathname]',
					'errors' => array(
							'required' => $this->lang->line('error_enter_blog_pathname'),
							'is_unique' => $this->lang->line('error_blog_pathname_exits'),
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
				$newData = $this->blog_model->add_blog_category();
				
				$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);

				if(sizeof($lang)>0){
					foreach($lang as $k => $v){
						$this->blog_model->add_blog_category_lang($newData['blog_category_id'],$v);
					}
				}
				$newLangData = $this->blog_model->get_blog_category_lang_data($newData['blog_category_id']);
				$newData['lang'] = json_encode($newLangData);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_BLOG_CATEGORY_ADD, $newData);
				}
				else
				{
					$this->account_model->insert_log(LOG_BLOG_CATEGORY_ADD, $newData);
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
				$json['msg']['blog_category_name_error'] = form_error('blog_category_name');
				$json['msg']['blog_pathname_error'] = form_error('blog_category_pathname');
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

	public function edit_category($id = NULL){
		if(permission_validation(PERMISSION_BLOG_CATEGORY_UPDATE) == TRUE)
		{
			$data = $this->blog_model->get_blog_category_data($id);
			$data['blog_category_lang'] = $this->blog_model->get_blog_category_lang_data($id);
			$this->load->view('blog_category_update', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function update_category(){
		if(permission_validation(PERMISSION_BLOG_CATEGORY_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'blog_category_name_error' => '',
					'blog_pathname_error' => '',
					'general_error' => ''
				),	
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			$blog_category_id = trim($this->input->post('blog_category_id', TRUE));
			$oldData = $this->blog_model->get_blog_category_data($blog_category_id);
			$blog_category_pathname = trim($this->input->post('blog_category_pathname', TRUE));

			if( ! empty($oldData))
			{
				//Set form rules
				$config = array(
					array(
						'field' => 'blog_category_name',
						'label' => strtolower($this->lang->line('label_blog_category')),
						'rules' => 'trim|required|max_length[36]',
						'errors' => array(
							'required' => $this->lang->line('error_enter_blog_category'),
							'max_length' => $this->lang->line('error_invalid_blog_category'),
						)
					)
				);

				if($oldData['blog_category_pathname'] != $blog_category_pathname){
					$configAdd = array(
						'field' => 'blog_category_pathname',
						'label' => strtolower($this->lang->line('label_blog_pathname')),
						'rules' => 'trim|required|is_unique[blogs_category.blog_category_pathname]',
						'errors' => array(
								'required' => $this->lang->line('error_enter_blog_pathname'),
								'is_unique' => $this->lang->line('error_blog_pathname_exits'),
						)
					);
					array_push($config, $configAdd);
				}
							
				$this->form_validation->set_rules($config);
				$this->form_validation->set_error_delimiters('', '');
				
				//Form validation
				if ($this->form_validation->run() == TRUE)
				{
					$oldLangData = $this->blog_model->get_blog_category_lang_data($blog_category_id);
					$oldData['lang'] = json_encode($oldLangData);

					//Database update
					$this->db->trans_start();
					$this->blog_model->delete_blog_category_lang($blog_category_id);
					

					$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
					if(sizeof($lang)>0){
						foreach($lang as $k => $v){
							$this->blog_model->add_blog_category_lang($blog_category_id,$v);
						}
					}
					
					$newLangData = $this->blog_model->get_blog_category_lang_data($blog_category_id);
					$newData = $this->blog_model->update_blog_category($blog_category_id);
					$newData['lang'] = json_encode($newLangData);
					
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_ANNOUNCEMENT_UPDATE, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_ANNOUNCEMENT_UPDATE, $newData, $oldData);
					}
					
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_updated');
						
						//Prepare for ajax update
						$json['response'] = array(
								'id' => $newData['blog_category_id'],
								'blog_category_name' => $newData['blog_category_name'],
								'blog_category_pathname' => $newData['blog_category_pathname'],
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
					$json['msg']['blog_category_name_error'] = form_error('blog_category_name');
					$json['msg']['blog_pathname_error'] = form_error('blog_category_pathname');
				}
			}
			else{
				$json['msg']['general_error'] = form_error('error_failed_to_update');
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

	public function delete_category(){
		//Initial output data
		$json = array(
					'status' => EXIT_ERROR, 
					'msg' => ''
				);
					
		if(permission_validation(PERMISSION_BLOG_CATEGORY_DELETE) == TRUE)
		{
			$blog_id = $this->uri->segment(3);
			$oldData = $this->blog_model->get_blog_category_data($blog_id);
			
			if( ! empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->blog_model->delete_blog_category($blog_id);
				$this->blog_model->delete_blog_category_lang($blog_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_BLOG_CATEGORY_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_BLOG_CATEGORY_DELETE, $oldData);
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

	public function view_frontend(){
		if(permission_validation(PERMISSION_BLOG_FRONTEND_VIEW) == TRUE)
		{
			$blog_id = $this->uri->segment(3);
			$oldData = $this->blog_model->get_blog_data($blog_id);
			
			if( ! empty($oldData))
			{
				if(!empty($oldData['domain'])){
					//$path = SYSTEM_API_MEMBER_SITE_LINK;
					if($oldData['blog_display'] == BLOG_DISPLAY_BLOG){
						$path .= "/blog/".$oldData['blog_pathname'];
					}else if($oldData['blog_display'] == BLOG_DISPLAY_PAGE){
						if($oldData['blog_category_id'] == PAGE_HOME){
							$path .= "/casino-news";
						}else if($oldData['blog_category_id'] == PAGE_LIVE_CASINO){
							$path .= "/baccarat";
						}else if($oldData['blog_category_id'] == PAGE_SPORTSBOOK){
							$path .= "/sports";
						}else if($oldData['blog_category_id'] == PAGE_LOTTERY){
							$path .= "/lottery";
						}else if($oldData['blog_category_id'] == PAGE_SLOTS){
							$path .= "/slotgame";
						}else if($oldData['blog_category_id'] == PAGE_BOARD_GAME){
							$path .= "/mahjong";
						}else if($oldData['blog_category_id'] == PAGE_FISHING){
							$path .= "/fishing";
						}
						$path .= "/".$oldData['blog_pathname'];
					}else{
						//$path .= "/product";
						//not sure
						if($oldData['blog_category_id'] == PAGE_PRODUCT_KALI_BACARRAT){
							$path .= "/cali-baccarat";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_AB_BACARRAT){
							$path .= "/allbet-baccarat";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_DG_BACARRAT){
							$path .= "/dg-baccarat";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_WM_BACARRAT){
							$path .= "/wm-baccarat";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_SA_BACARRAT){
							$path .= "/sa-baccarat";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_OG_BACARRAT){
							$path .= "/og-baccarat";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_OB_BACARRAT){
							$path .= "/ob-baccarat";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_BTT_SPORTBOOK){
							$path .= "/918bet-sports";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_SP_SPORTBOOK){
							$path .= "/super-sports";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_9K_LOTTERY){
							$path .= "/9k-racecar";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_SP_LOTTERY){
							$path .= "/super-lottery";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_RTG_SLOT){
							$path .= "/rtg-slotgame";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_DT_SLOT){
							$path .= "/dtg-slotgame";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_SP_SLOT){
							$path .= "/simple-play-slotgame";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_ICG_SLOT){
							$path .= "/bwin-slotgame";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_BNG_SLOT){
							$path .= "/bng-slotgame";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_SUPREME_MAHJONG){
							$path .= "/supreme-gaming";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_SP_FISHING){
							$path .= "/sp-fishing";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_RTG_FISHING){
							$path .= "/rtg-fishing";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_GR_SLOT){
							$path .= "/gr-slotgame";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_RSG_SLOT){
							$path .= "/rsg-slotgame";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_BL_BOARD_GAME){
							$path .= "/royalgaming";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_GR_BOARD_GAME){
							$path .= "/gr-gaming";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_ICG_FISHING){
							$path .= "/bwin-fish";
						}else if($oldData['blog_category_id'] == PAGE_PRODUCT_GR_FISHING){
							$path .= "/gr-fish";
						}
						//BLOG_DISPLAY_PRODUCT
						$path .= "/".$oldData['blog_pathname'];
					}

					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('success_view');
					$json['url'] = array();
					
					$arr = explode(',', $oldData['domain']);
					$arr = array_values(array_filter($arr));
					foreach($arr as $arr_row){
						if((strpos($arr_row, "*")) !== FALSE){
							array_push($json['url'], "https://".str_replace('*','a',$arr_row).$path);
						}else{
							array_push($json['url'], "https://".$arr_row.$path);
						}	
					}
				}else{
					$json['msg'] = $this->lang->line('error_failed_to_view');
				}
			}
			else
			{
				$json['msg'] = $this->lang->line('error_failed_to_view');
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