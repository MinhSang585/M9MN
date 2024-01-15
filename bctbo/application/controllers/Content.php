<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('content_model'));
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}


	public function index(){
		if(permission_validation(PERMISSION_CONTENT_VIEW) == TRUE)
		{
			$this->session->unset_userdata('searches_content');
			$this->save_current_url('content');
			$data['page_title'] = $this->lang->line('title_content');
			$this->load->view('content_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function search(){
		if(permission_validation(PERMISSION_CONTENT_VIEW) == TRUE)
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
				'content_id' => trim($this->input->post('content_id', TRUE)),
				'status' => trim($this->input->post('status', TRUE)),
				'domain' => trim($this->input->post('domain', TRUE)),
			);
			
			$this->session->set_userdata('searches_content', $data);
			
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
		if(permission_validation(PERMISSION_CONTENT_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
				0 => 'content_key_id',
				1 => 'content_id',
				2 => 'content_name',
				3 => 'domain',
				4 => 'active',
				5 => 'updated_by',
				6 => 'updated_date'
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
			
			$arr = $this->session->userdata('searches_content');
			$where = "";

			if(isset($arr['content_id']) && !empty($arr['content_id'])){
				$where .= ' AND content_id = "' . $arr['content_id']. '"';
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
			$query_string = "SELECT {$select} FROM {$dbprefix}content_page WHERE content_id != 0 $where";
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
					if(!empty($post->domain)){
						$arr = explode(',', $post->domain);
						$arr = array_values(array_filter($arr));
						$domain_text = implode(",",$arr);
					}

					$row = array();
					$row[] = $post->content_key_id;
					$row[] = $this->lang->line(get_content_page($post->content_id));
					$row[] = '<span id="uc1_' . $post->content_key_id . '">' . $post->content_name . '</span>';
					$row[] = '<span id="uc7_' . $post->content_key_id . '">' . $domain_text . '</span>';
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc3_' . $post->content_key_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->content_key_id . '">' . $this->lang->line('status_suspend') . '</span>'; break;
					}
					$row[] = '<span id="uc4_' . $post->content_key_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc5_' . $post->content_key_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					
					if(permission_validation(PERMISSION_CONTENT_FRONTEND_VIEW) == TRUE)
					{
						$button .= '<i onclick="viewData(' . $post->content_key_id . ')" class="fas fa-file nav-icon text-info" title="' . $this->lang->line('button_view')  . '"></i> &nbsp;&nbsp; ';
					}

					if(permission_validation(PERMISSION_CONTENT_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->content_key_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_CONTENT_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->content_key_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_CONTENT_UPDATE) == TRUE || permission_validation(PERMISSION_CONTENT_DELETE) == TRUE || permission_validation(PERMISSION_CONTENT_FRONTEND_VIEW) == TRUE)
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
		if(permission_validation(PERMISSION_CONTENT_ADD) == TRUE)
		{
			$this->load->view('content_add', $data);
		}
		else
		{
			redirect('home');
		}	
	}

	public function submit(){
		if(permission_validation(PERMISSION_CONTENT_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'content_name_error' => '',
					'content_id_error' => '',
					'general_error' => '',
				),
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			//Set form rules
			$config = array(
				array(
					'field' => 'content_name',
					'label' => strtolower($this->lang->line('label_page_title')),
					'rules' => 'trim'
				),
				array(
					'field' => 'content_id',
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
				$page_id = trim($this->input->post('content_id', TRUE));
				$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
				$path = CONTENT_PATH;
				$file_storage = array();
				$lang_path = "";
				$content = "";
				if(sizeof($lang)>0){
					foreach($lang as $k => $v){
						$lang_path = get_site_language_name($v);
						if(!file_exists($path."web/".$lang_path)) {
							mkdir($path."web/".$lang_path, 0777, true);
						}
						$file = "web"."_content_product_page".$page_id."_l".$v."_t".time()."_v".rand(1000,9999).".php";
						$file_path = $path."web/".$lang_path."/".$file;
						$file_storage[$v]['web'] = CONTENT_PATH_DATABASE."web/".$lang_path."/".$file;
						file_put_contents($file_path, $_POST['content_web_content_'.$v]);

						if (!file_exists($path."mobile/".$lang_path)) {
							mkdir($path."mobile/".$lang_path, 0777, true);
						}
						$file = "mobile"."_content_product_page".$page_id."_l".$v."_t".time()."_v".rand(1000,9999).".php";
						$file_path = $path."mobile/".$lang_path."/".$file;
						$file_storage[$v]['mobile'] = CONTENT_PATH_DATABASE."mobile/".$lang_path."/".$file;
						file_put_contents($file_path, $_POST['content_mobile_content_'.$v]);


						if (!file_exists($path."hybrid/".$lang_path)) {
							mkdir($path."hybrid/".$lang_path, 0777, true);
						}
						$file = "hybrid"."_content_product_page".$page_id."_l".$v."_t".time()."_v".rand(1000,9999).".php";
						$file_path = $path."hybrid/".$lang_path."/".$file;
						$file_storage[$v]['hybrid'] = CONTENT_PATH_DATABASE."hybrid/".$lang_path."/".$file;
						file_put_contents($file_path, $_POST['content_hybrid_content_'.$v]);
					}
					//Database update
					$this->db->trans_start();
					$newData = $this->content_model->add_content();
					foreach($lang as $k => $v){
						$this->content_model->add_content_lang($newData['content_key_id'],$v,$file_storage[$v]);
					}
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_CONTENT_ADD, $newData);
					}
					else
					{
						$this->account_model->insert_log(LOG_CONTENT_ADD, $newData);
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
			}
			else 
			{
				$json['msg']['content_name_error'] = form_error('content_name');
				$json['msg']['content_id_error'] = form_error('content_id');
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

	public function edit($id = NULL){
		if(permission_validation(PERMISSION_CONTENT_UPDATE) == TRUE)
		{
			$data['content'] = $this->content_model->get_content_data($id);
			$data['content_lang'] = array();
			if(!empty($data['content'])){
				$content_lang = $this->content_model->get_content_lang_data($id);
				if(!empty($content_lang)){
					foreach($content_lang as $content_lang_key => $content_lang_value){
						if(file_exists(CONTENT_SOURCE_PATH.$content_lang_value['content_web_file_path'])){
							$data['content_lang'][$content_lang_key]['content_web_content'] = file_get_contents(CONTENT_SOURCE_PATH.$content_lang_value['content_web_file_path']);
						}
						if(file_exists(CONTENT_SOURCE_PATH.$content_lang_value['content_mobile_file_path'])){
							$data['content_lang'][$content_lang_key]['content_mobile_content'] = file_get_contents(CONTENT_SOURCE_PATH.$content_lang_value['content_mobile_file_path']);
						}
						if(file_exists(CONTENT_SOURCE_PATH.$content_lang_value['content_hybrid_file_path'])){
							$data['content_lang'][$content_lang_key]['content_hybrid_content'] = file_get_contents(CONTENT_SOURCE_PATH.$content_lang_value['content_hybrid_file_path']);
						}
					}
				}
				$this->load->view('content_update',$data);
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
		if(permission_validation(PERMISSION_CONTENT_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'content_name_error' => '',
					'content_id_error' => '',
					'general_error' => '',
				),
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			$content_key_id = trim($this->input->post('content_key_id', TRUE));
			$oldData = $this->content_model->get_content_data($content_key_id);
			if(!empty($oldData)){
				//Set form rules
				$config = array(
					array(
						'field' => 'content_name',
						'label' => strtolower($this->lang->line('label_page_title')),
						'rules' => 'trim'
					),
					array(
						'field' => 'content_id',
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
					$page_id = trim($this->input->post('content_id', TRUE));
					$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
					$path = CONTENT_PATH;
					$file_storage = array();
					$lang_path = "";
					$content = "";
					if(sizeof($lang)>0){
						foreach($lang as $k => $v){
							$lang_path = get_site_language_name($v);
							if(!file_exists($path."web/".$lang_path)) {
								mkdir($path."web/".$lang_path, 0777, true);
							}
							$file = "web"."_content_product_page".$page_id."_l".$v."_t".time()."_v".rand(1000,9999).".php";
							$file_path = $path."web/".$lang_path."/".$file;
							$file_storage[$v]['web'] = CONTENT_PATH_DATABASE."web/".$lang_path."/".$file;
							file_put_contents($file_path, $_POST['content_web_content_'.$v]);

							if (!file_exists($path."mobile/".$lang_path)) {
								mkdir($path."mobile/".$lang_path, 0777, true);
							}
							$file = "mobile"."_content_product_page".$page_id."_l".$v."_t".time()."_v".rand(1000,9999).".php";
							$file_path = $path."mobile/".$lang_path."/".$file;
							$file_storage[$v]['mobile'] = CONTENT_PATH_DATABASE."mobile/".$lang_path."/".$file;
							file_put_contents($file_path, $_POST['content_mobile_content_'.$v]);


							if (!file_exists($path."hybrid/".$lang_path)) {
								mkdir($path."hybrid/".$lang_path, 0777, true);
							}
							$file = "hybrid"."_content_product_page".$page_id."_l".$v."_t".time()."_v".rand(1000,9999).".php";
							$file_path = $path."hybrid/".$lang_path."/".$file;
							$file_storage[$v]['hybrid'] = CONTENT_PATH_DATABASE."hybrid/".$lang_path."/".$file;
							file_put_contents($file_path, $_POST['content_hybrid_content_'.$v]);
						}
						//Database update
						$this->db->trans_start();
						$newData = $this->content_model->update_content($content_key_id);
						$oldDataLang = $this->content_model->get_content_lang_data($newData['content_key_id']);
						foreach($lang as $k => $v){
							if(isset($oldDataLang[$v])){
								$this->content_model->update_content_lang($newData['content_key_id'],$v,$file_storage[$v]);
							}else{
								$this->content_model->add_content_lang($newData['content_key_id'],$v,$file_storage[$v]);
							}
						}
						if($this->session->userdata('user_group') == USER_GROUP_USER) 
						{
							$this->user_model->insert_log(LOG_CONTENT_UPDATE, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_CONTENT_UPDATE, $newData, $oldData);
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
					}else{
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
					}
				}
				else 
				{
					$json['msg']['content_name_error'] = form_error('content_name');
					$json['msg']['content_id_error'] = form_error('content_id');
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
					
		if(permission_validation(PERMISSION_CONTENT_DELETE) == TRUE)
		{
			$content_key_id = $this->uri->segment(3);
			$oldData = $this->content_model->get_content_data($content_key_id);
			if( ! empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->content_model->delete_content($content_key_id);
				$this->content_model->delete_content_lang($content_key_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_CONTENT_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_CONTENT_DELETE, $oldData);
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
		if(permission_validation(PERMISSION_CONTENT_FRONTEND_VIEW) == TRUE)
		{
			$content_key_id = $this->uri->segment(3);
			$oldData = $this->content_model->get_content_data($content_key_id);
			
			if( ! empty($oldData))
			{
				if(!empty($oldData['domain'])){
					if($oldData['content_id'] == PAGE_HOME){
						$path .= "";
					}else if($oldData['content_id'] == PAGE_LIVE_CASINO){
						$path .= PAGE_PRODUCT_BACARRAT_LINK;
					}else if($oldData['content_id'] == PAGE_SPORTSBOOK){
						$path .= PAGE_PRODUCT_SPORTSBOOK_LINK;
					}else if($oldData['content_id'] == PAGE_LOTTERY){
						$path .= PAGE_PRODUCT_LOTTERY_LINK;
					}else if($oldData['content_id'] == PAGE_SLOTS){
						$path .= PAGE_PRODUCT_SLOTS_LINK;
					}else if($oldData['content_id'] == PAGE_BOARD_GAME){
						$path .= PAGE_PRODUCT_BOARD_GAME_LINK;
					}else if($oldData['content_id'] == PAGE_FISHING){
						$path .= PAGE_PRODUCT_FISHING_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_KALI_BACARRAT){
						$path .= PAGE_PRODUCT_KALI_BACARRAT_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_AB_BACARRAT){
						$path .= PAGE_PRODUCT_AB_BACARRAT_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_DG_BACARRAT){
						$path .= PAGE_PRODUCT_DG_BACARRAT_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_WM_BACARRAT){
						$path .= PAGE_PRODUCT_WM_BACARRAT_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_SA_BACARRAT){
						$path .= PAGE_PRODUCT_SA_BACARRAT_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_OG_BACARRAT){
						$path .= PAGE_PRODUCT_OG_BACARRAT_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_OB_BACARRAT){
						$path .= PAGE_PRODUCT_OB_BACARRAT_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_BTT_SPORTBOOK){
						$path .= PAGE_PRODUCT_BTT_SPORTBOOK_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_SP_SPORTBOOK){
						$path .= PAGE_PRODUCT_SP_SPORTBOOK_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_9K_LOTTERY){
						$path .= PAGE_PRODUCT_9K_LOTTERY_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_SP_LOTTERY){
						$path .= PAGE_PRODUCT_SP_LOTTERY_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_RTG_SLOT){
						$path .= PAGE_PRODUCT_RTG_SLOT_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_DT_SLOT){
						$path .= PAGE_PRODUCT_DT_SLOT_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_SP_SLOT){
						$path .= PAGE_PRODUCT_SP_SLOT_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_ICG_SLOT){
						$path .= PAGE_PRODUCT_ICG_SLOT_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_BNG_SLOT){
						$path .= PAGE_PRODUCT_BNG_SLOT_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_SUPREME_MAHJONG){
						$path .= PAGE_PRODUCT_SUPREME_MAHJONG_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_SP_FISHING){
						$path .= PAGE_PRODUCT_SP_FISHING_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_RTG_FISHING){
						$path .= PAGE_PRODUCT_RTG_FISHING_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_GR_SLOT){
						$path .= PAGE_PRODUCT_GR_SLOT_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_RSG_SLOT){
						$path .= PAGE_PRODUCT_RSG_SLOT_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_BL_BOARD_GAME){
						$path .= PAGE_PRODUCT_BL_BOARD_GAME_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_GR_BOARD_GAME){
						$path .= PAGE_PRODUCT_GR_BOARD_GAME_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_ICG_FISHING){
						$path .= PAGE_PRODUCT_ICG_FISHING_LINK;
					}else if($oldData['content_id'] == PAGE_PRODUCT_GR_FISHING){
						$path .= PAGE_PRODUCT_GR_FISHING_LINK;
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