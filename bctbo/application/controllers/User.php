<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('group_model','avatar_model', 'miscellaneous_model', 'game_model', 'role_model'));
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}
	public function index()
	{
		if(permission_validation(PERMISSION_USER_VIEW) == TRUE)
		{
			$this->save_current_url('user');
			$data['page_title'] = $this->lang->line('title_user');
			$data['upline'] = $this->user_model->get_user_data($this->session->userdata('root_user_id'));
			$this->load->view('user_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function downline($num = NULL, $username = NULL)
	{
		if(permission_validation(PERMISSION_USER_VIEW) == TRUE)
		{
			$data['num'] = $num;
			$data['username'] = $username;
			$data['type'] = 'downline';
			$html = $this->load->view('user_table', $data, TRUE);
			echo $html;
			exit();
		}
	}
	public function search()
	{
		if(permission_validation(PERMISSION_USER_VIEW) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'username_error' => '',
										'general_error' => '',
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
														'required' => $this->lang->line('error_enter_username')
												)
							)
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$username = trim($this->input->post('username', TRUE));
				$response = $this->user_model->get_downline_data($username);
				if( ! empty($response))
				{
					$result = '';
					if($this->session->userdata('root_username') != $username)
					{
						$table = '';
						$num = 1;
						$root = $this->user_model->get_user_data($this->session->userdata('root_user_id'));
						$root_arr = explode(',', $root['upline_ids']);
						$arr = explode(',', $response['upline_ids']);
						for($i=0;$i<sizeof($arr);$i++)
						{
							if( ! in_array($arr[$i], $root_arr) && $arr[$i] != $root['user_id'])
							{
								$num++;
								$data = $this->user_model->get_user_data($arr[$i]);
								$data['num'] = $num;
								$data['type'] = 'search';
								$table .= $this->load->view('user_table', $data, TRUE);
							}
						}
						$data = $response;
						$num++;
						$data['num'] = $num;
						$data['type'] = 'search';
						$table .= $this->load->view('user_table', $data, TRUE);
						$result = array('table' => $table, 'num' => $num);
					}	
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $result;
				}
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_username_not_found');
				}	
			}
			else 
			{
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
	public function check_domain(){
		if(permission_validation(PERMISSION_USER_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'domain_check_error' => '',
					'general_error' => ''
				), 	
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			$config = array(
				array(
						'field' => 'domain_check',
						'label' => strtolower($this->lang->line('domain_check')),
						'rules' => 'trim|required|min_length[2]|max_length[16]|regex_match[/^[A-Za-z0-9]+$/]|is_unique[users.domain_sub]',
						'errors' => array(
								'required' => $this->lang->line('error_enter_user_domain'),
								'min_length' => $this->lang->line('error_invalid_user_domain'),
								'max_length' => $this->lang->line('error_invalid_user_domain'),
								'regex_match' => $this->lang->line('error_invalid_user_domain')
						)
				),
			);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				//Database update
				$domain_check = trim($this->input->post('domain_check', TRUE),true);
				$check_domain = $this->user_model->check_domain_name($domain_check);
				if(empty($check_domain)){
					$domain_ban = array_flip(json_decode(SYSTEM_DOMAIN_BANNED,true));
					if(!isset($domain_ban[$domain_check])){
						$verify = md5(SYSTEM_API_AGENT_ID);
						$url = "https://".$domain_check.".".SYSTEM_API_MEMBER_DOMAIN_LINK."/home/verify_domain";
						$headers = array(
							'charset=UTF-8',
							'Content-Type: application/x-www-form-urlencoded',
						);
						$ch = curl_init($url);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
						curl_setopt($ch, CURLOPT_TIMEOUT, 60);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);		
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                       	
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);   	
						$result = curl_exec($ch);
						if($result == $verify){
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_user_domain_available');
						}else{
							$json['msg']['general_error'] = $this->lang->line('error_user_domain_not_available');
						}
					}else{
						$json['msg']['general_error'] = $this->lang->line('error_user_domain_not_available');
					}
				}else{
					$json['msg']['general_error'] = $this->lang->line('error_user_domain_not_available');
				}
			}
			else 
			{
				$json['msg']['domain_check_error'] = form_error('domain_check');
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
	public function listing($num = NULL, $username = NULL)
    {
		if(permission_validation(PERMISSION_USER_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'user_id',
				1 => 'username',
				2 => 'nickname',
				3 => 'user_type',
				4 => 'upline',
				5 => 'points',
				6 => 'domain_sub',
				7 => 'active',
				8 => 'created_date',
				9 => 'last_login_date',
				10 => 'domain_name',
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
			$upline = $username;
			$response = $this->user_model->get_downline_data($upline);
			if(empty($response))
			{
				$upline = '';
			}
			$query = array(
				'select' => implode(',', $columns),
				'search_values' => array($upline),
				'search_types' => array('equal'),
				'search_columns' => array('upline'),
				'table' => 'users',
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
					if(!empty($post->domain_name)){
						$domain = $post->domain_name;
					}else{
						if(!empty($post->domain_sub)){
							$domain = $post->domain_sub.".".SYSTEM_API_MEMBER_DOMAIN_LINK;
						}else{
							$domain = '-';
						}
					}
					$row = array();
					$row[] = $post->user_id;
					$row[] = '<a href="javascript:void(0);" onclick="getDownline(\'' . $post->username . '\', ' . $num . ')">' . $post->username . '</a>';
					$row[] = '<span id="uc1_' . $post->user_id . '">' . $post->nickname . '</span>';
					$row[] = $this->lang->line(get_user_type($post->user_type));
					$row[] = ( ! empty($post->upline) ? $post->upline : '-');
					$row[] = '<span id="uc2_' . $post->user_id . '">' . $post->points . '</span>';
					$row[] = $domain;
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc3_' . $post->user_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->user_id . '">' . $this->lang->line('status_suspend') . '</span>'; break;
					}
					$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
					$row[] = (($post->last_login_date > 0) ? date('Y-m-d H:i:s', $post->last_login_date) : '-');
					$button = '';
					if(permission_validation(PERMISSION_USER_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->user_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_PERMISSION_SETUP) == TRUE)
					{
						$button .= '<i onclick="permissionSetup(' . $post->user_id . ')" class="fas fa-lock nav-icon text-orange" title="' . $this->lang->line('button_permissions')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_CHANGE_PASSWORD) == TRUE)
					{
						$button .= '<i onclick="changePassword(' . $post->user_id . ')" class="fas fa-key nav-icon text-secondary" title="' . $this->lang->line('button_change_password')  . '"></i> &nbsp;&nbsp; ';
					}
					// if(permission_validation(PERMISSION_USER_ADD) == TRUE)
					// {
					// 	$button .= '<i onclick="addDownline(\'' . $post->username . '\')" class="fas fa-user-friends nav-icon text-info" title="' . $this->lang->line('button_downline')  . '"></i> &nbsp;&nbsp; ';
					// }
					if(permission_validation(PERMISSION_DEPOSIT_POINT_TO_DOWNLINE) == TRUE)
					{
						$button .= '<i onclick="depositPoints(' . $post->user_id . ')" class="fas fa-arrow-up nav-icon text-olive" title="' . $this->lang->line('button_deposit_points')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE) == TRUE)
					{
						$button .= '<i onclick="withdrawPoints(' . $post->user_id . ')" class="fas fa-arrow-down nav-icon text-maroon" title="' . $this->lang->line('button_withdraw_points')  . '"></i>';
					}
					if(permission_validation(PERMISSION_USER_UPDATE) == TRUE)
					{
						$button .= '<i onclick="editRole(' . $post->user_id . ')" class="fas fa-user-tag" title="' . $this->lang->line('button_edit_role')  . '"></i>';
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
    public function all()
	{
		if(permission_validation(PERMISSION_USER_VIEW) == TRUE)
		{
			$this->save_current_url('user');
			$data['page_title'] = $this->lang->line('title_user');
			$data['upline'] = $this->user_model->get_user_data($this->session->userdata('root_user_id'));
			$this->load->view('user_all_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function add($username = NULL)
    {
		if(permission_validation(PERMISSION_USER_ADD) == TRUE)
		{
			$response = $this->user_model->get_downline_data($username);
			if( ! empty($response))
			{
				$response['player_group_list'] 	= $this->group_model->get_group_list(GROUP_PLAYER);
				$response['bank_group_list'] 	= $this->group_model->get_group_list(GROUP_BANK);
				$response['avatar_list'] 		= $this->avatar_model->get_avatar_list();
				$response['role_list'] 			= $this->role_model->getRoleNew();
				$response['miscellaneous'] 		= $this->miscellaneous_model->get_miscellaneous();
				$game_list 						= $this->game_model->get_game_list();
				if(!empty($game_list)){
					foreach($game_list as $game_list_row)
					{
						$game_type_front_code = array_filter(explode(',', $game_list_row['game_type_front_code']));
						if(!empty($game_type_front_code)){
							foreach($game_type_front_code as $game_type_front_code_row){
								$data['game_list'][$game_type_front_code_row][] = $game_list_row['game_code'];
							}
						}
					}
				}
				$arrayPossess = [];
				for ($i = 10; $i <= 30; $i += 5) {
					$arrayPossess[] = $i;
				}
				$response['arrayPossess'] = $arrayPossess;
				$this->load->view('user_add', $response);
			}
			else
			{
				redirect('home');
			}
		}
		else
		{
			redirect('home');
		}
	}
	public function submit() {
		if(permission_validation(PERMISSION_USER_ADD) == TRUE) {
			#Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'username_error' 	=> '',
					'nickname_error' 	=> '',
					'mobile_error' 		=> '',
					'email_error' 		=> '',
					'password_error' 	=> '',
					'passconf_error' 	=> '',
					'possess_error' 	=> '',
					'sport_comm_error' 	=> '',
					'casino_comm_error' => '',
					'slots_comm_error' 	=> '',
					'cf_comm_error' 	=> '',
					'other_comm_error' 	=> '',
					'white_list_ip_error' => '',
					'general_error' 	=> ''
				),
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			$upline = trim($this->input->post('upline', TRUE));
			$response = $this->user_model->get_downline_data($upline);
			if( ! empty($response)) {
				#Set form rules
				$config = array(
					array(
						'field' => 'nickname',
						'label' => strtolower($this->lang->line('label_nickname')),
						'rules' => 'trim|required|min_length[1]|max_length[32]',
						'errors' => array(
							'required' => $this->lang->line('error_enter_nickname'),
							'min_length' => $this->lang->line('error_invalid_nickname'),
							'max_length' => $this->lang->line('error_invalid_nickname'),
							'regex_match' => $this->lang->line('error_invalid_nickname')
						)
					),
					array(
						'field' => 'mobile',
						'label' => strtolower($this->lang->line('label_mobile')),
						'rules' => 'trim|integer',
						'errors' => array(
							'integer' => $this->lang->line('error_invalid_mobile')
						)
					),
					array(
						'field' => 'email',
						'label' => strtolower($this->lang->line('label_email')),
						'rules' => 'trim|valid_email',
						'errors' => array(
							'valid_email' => $this->lang->line('error_invalid_email')
						)
					),
					array(
						'field' => 'username',
						'label' => strtolower($this->lang->line('label_username')),
						'rules' => 'trim|required|min_length[6]|max_length[16]|regex_match[/^[a-z0-9]+$/]|is_unique[users.username]|is_unique[sub_accounts.username]|is_unique[players.username]',
						'errors' => array(
							'required' => $this->lang->line('error_enter_username'),
							'min_length' => $this->lang->line('error_invalid_username'),
							'max_length' => $this->lang->line('error_invalid_username'),
							'regex_match' => $this->lang->line('error_invalid_username'),
							'is_unique' => $this->lang->line('error_username_already_exits')
						)
					),
					array(
						'field' => 'password',
						'label' => strtolower($this->lang->line('label_password')),
						'rules' => 'trim|required|min_length[6]|max_length[15]|regex_match[/^[A-Za-z0-9!#$^*]+$/]',
						'errors' => array(
							'required' => $this->lang->line('error_enter_password'),
							'min_length' => $this->lang->line('error_invalid_password'),
							'max_length' => $this->lang->line('error_invalid_password'),
							'regex_match' => $this->lang->line('error_invalid_password')
						)
					),
					array(
						'field' => 'passconf',
						'label' => strtolower($this->lang->line('label_confirm_password')),
						'rules' => 'trim|required|matches[password]',
						'errors' => array(
							'required' => $this->lang->line('error_enter_confirm_password'),
							'matches' => $this->lang->line('error_confirm_password_not_match')
						)
					),
					array(
						'field' => 'possess',
						'label' => strtolower($this->lang->line('label_possess')),
						'rules' => 'trim|greater_than_equal_to[0]|less_than_equal_to[' . $response['possess'] . ']',
						'errors' => array(
							'greater_than_equal_to' => $this->lang->line('error_greater_than_or_equal'),
							'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
						)
					),
					array(
						'field' => 'sport_comm',
						'label' => strtolower($this->lang->line('label_sport_comm')),
						'rules' => 'trim|greater_than_equal_to[0]|less_than_equal_to[' . $response['sport_comm'] . ']',
						'errors' => array(
							'greater_than_equal_to' => $this->lang->line('error_greater_than_or_equal'),
							'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
						)
					),
					array(
						'field' => 'casino_comm',
						'label' => strtolower($this->lang->line('label_casino_comm')),
						'rules' => 'trim|greater_than_equal_to[0]|less_than_equal_to[' . $response['casino_comm'] . ']',
						'errors' => array(
							'greater_than_equal_to' => $this->lang->line('error_greater_than_or_equal'),
							'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
						)
					),
					array(
						'field' => 'slots_comm',
						'label' => strtolower($this->lang->line('label_slots_comm')),
						'rules' => 'trim|greater_than_equal_to[0]|less_than_equal_to[' . $response['slots_comm'] . ']',
						'errors' => array(
							'greater_than_equal_to' => $this->lang->line('error_greater_than_or_equal'),
							'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
						)
					),
					array(
						'field' => 'cf_comm',
						'label' => $this->lang->line('label_cf_comm'),
						'rules' => 'trim|greater_than_equal_to[0]|less_than_equal_to[' . $response['cf_comm'] . ']',
						'errors' => array(
							'greater_than_equal_to' => $this->lang->line('error_greater_than_or_equal'),
							'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
						)
					),
					array(
						'field' => 'other_comm',
						'label' => strtolower($this->lang->line('label_other_comm')),
						'rules' => 'trim|greater_than_equal_to[0]|less_than_equal_to[' . $response['other_comm'] . ']',
						'errors' => array(
							'greater_than_equal_to' => $this->lang->line('error_greater_than_or_equal'),
							'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
						)
					),
					array(
						'field' => 'white_list_ip[]',
						'label' => strtolower($this->lang->line('label_white_list_ip')),
						'rules' => 'trim|valid_ip',
						'errors' => array(
							'valid_ip' => $this->lang->line('error_valid_ip')
						)
					),
					array(
						'field' => 'user_role',
						'label' => strtolower($this->lang->line('label_user_role')),
						'rules' => 'trim|required',
						'errors' => array(
							'required' => $this->lang->line('error_select_user_role'),
						)
					),
				);
				$domain_check = trim($this->input->post('domain', TRUE),true);
				if(!empty($domain_check)){
					$configAdd = array(
						'field' => 'domain',
						'label' => strtolower($this->lang->line('domain_check')),
						'rules' => 'trim|required|min_length[2]|max_length[16]|regex_match[/^[A-Za-z0-9]+$/]|is_unique[users.domain_sub]',
						'errors' => array(
								'required' => $this->lang->line('error_enter_user_domain'),
								'min_length' => $this->lang->line('error_invalid_user_domain'),
								'max_length' => $this->lang->line('error_invalid_user_domain'),
								'regex_match' => $this->lang->line('error_invalid_user_domain')
						)
					);
					array_push($config, $configAdd);
				}
				$this->form_validation->set_rules($config);
				$this->form_validation->set_error_delimiters('', '');
				#Form validation
				if ($this->form_validation->run() == TRUE) {
					#Database update
					$is_allow_add = TRUE;
					if(!empty($domain_check)){
						$is_allow_add = FALSE;
						$check_domain = $this->user_model->check_domain_name($domain_check);
						if(empty($check_domain)){
							$domain_ban = array_flip(json_decode(SYSTEM_DOMAIN_BANNED,true));
							if(!isset($domain_ban[$domain_check])){
								$verify = md5(SYSTEM_API_AGENT_ID);
								$url = "https://".$domain_check.".".SYSTEM_API_MEMBER_DOMAIN_LINK."/home/verify_domain";
								$headers = array(
									'charset=UTF-8',
									'Content-Type: application/x-www-form-urlencoded',
								);
								$ch = curl_init($url);
								curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
								curl_setopt($ch, CURLOPT_TIMEOUT, 60);
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);		
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                       	
								curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);   	
								$result = curl_exec($ch);
								if($result == $verify){
									$is_allow_add = TRUE;
								}else{
									$json['msg']['general_error'] = $this->lang->line('error_user_domain_not_available');
								}
							}else{
								$json['msg']['general_error'] = $this->lang->line('error_user_domain_not_available');
							}
						}else{
							$json['msg']['general_error'] = $this->lang->line('error_user_domain_not_available');
						}
					}
					if($is_allow_add == TRUE){
						//Database update
						$this->db->trans_start();
						$newData = $this->user_model->add_user($response);
						if($this->session->userdata('user_group') == USER_GROUP_USER)
						{
							$this->user_model->insert_log(LOG_USER_ADD, $newData);
						}
						else
						{
							$this->account_model->insert_log(LOG_USER_ADD, $newData);
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_added');
							if(TELEGRAM_STATUS == STATUS_ACTIVE){
								$newData['role_data'] = $this->role_model->get_role_data($newData['user_role']);
								send_logs_telegram(TELEGRAM_LOGS,TELEGRAM_LOGS_TYPE_CREATE_USER_ACCOUNT,$newData);
							}
						}
						else
						{
							$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');
						}
					}
				}
				else {
					$json['msg']['username_error'] 		= form_error('username');
					$json['msg']['nickname_error'] 		= form_error('nickname');
					$json['msg']['mobile_error'] 		= form_error('mobile');
					$json['msg']['email_error'] 		= form_error('email');
					$json['msg']['password_error'] 		= form_error('password');
					$json['msg']['passconf_error'] 		= form_error('passconf');
					$json['msg']['possess_error'] 		= form_error('possess');
					$json['msg']['sport_comm_error'] 	= form_error('sport_comm');
					$json['msg']['casino_comm_error'] 	= form_error('casino_comm');
					$json['msg']['slots_comm_error'] 	= form_error('slots_comm');
					$json['msg']['cf_comm_error'] 		= form_error('cf_comm');
					$json['msg']['other_comm_error'] 	= form_error('other_comm');
					$json['msg']['white_list_ip_error'] = form_error('white_list_ip[]');
					$json['msg']['general_error'] 		= form_error('domain');
					if( ! empty(form_error('user_role'))) {
						$json['msg']['general_error'] = form_error('user_role');
					}
				}
			}
			else {
				$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');
			}		
			#Output
			$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
			unset($json);	
			exit();	
		}	
	}
	public function edit($id = NULL) {
		if(permission_validation(PERMISSION_USER_UPDATE) == TRUE) {
			$data = $this->user_model->get_user_data($id);
			if( ! empty($data)) {
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response)) {
					$data['upline_data'] 	= $response;
					$data['role_list'] 		= $this->role_model->get_role_list();
					$data['uplineList'] 	= $this->user_model->getUplineList($data['user_type']);
					$data['current_user_type'] = $this->session->userdata('user_type');
					unset($response);
					$arrayPossess = [];
					for ($i = 10; $i <= 30; $i += 5) {
						$arrayPossess[] = $i;
					}
					$data['arrayPossess'] = $arrayPossess;
					$this->load->view('user_update', $data);
				}
				else {
					redirect('home');
				}
			}
			else {
				redirect('home');
			}
		}
		else {
			redirect('home');
		}
	}
	public function update() {
		if(permission_validation(PERMISSION_USER_UPDATE) == TRUE) {
			#Initial output data
			$json = array(
				'status' 	=> EXIT_ERROR, 
				'msg' 		=> array(
					'nickname_error' => '',
					'mobile_error' => '',
					'email_error' => '',
					'possess_error' => '',
					'sport_comm_error' => '',
					'casino_comm_error' => '',
					'slots_comm_error' => '',
					'cf_comm_error' => '',
					'other_comm_error' => '',
					'white_list_ip_error' => '',
					'general_error' => ''
				),
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' 		=> $this->security->get_csrf_hash()
			);
			$user_id = trim($this->input->post('user_id', TRUE));
			$isUpdateUpline = false;
			if($this->session->userdata('user_type') == 1){
				$isUpdateUpline = true;
			}
			$oldData = $this->user_model->get_user_data($user_id);
			if( ! empty($oldData)) {
				$response = $this->user_model->get_downline_data($oldData['upline']);
				if( ! empty($response)) {
					#Set form rules
					$config = array(
						array(
							'field' => 'nickname',
							'label' => strtolower($this->lang->line('label_nickname')),
							'rules' => 'trim|required|min_length[1]|max_length[32]',
							'errors' => array(
								'required' => $this->lang->line('error_enter_nickname'),
								'min_length' => $this->lang->line('error_invalid_nickname'),
								'max_length' => $this->lang->line('error_invalid_nickname'),
								'regex_match' => $this->lang->line('error_invalid_nickname')
							)
						),
						array(
							'field' => 'mobile',
							'label' => strtolower($this->lang->line('label_mobile')),
							'rules' => 'trim|integer',
							'errors' => array(
								'integer' => $this->lang->line('error_invalid_mobile')
							)
						),
						array(
							'field' => 'email',
							'label' => strtolower($this->lang->line('label_email')),
							'rules' => 'trim|valid_email',
							'errors' => array(
								'valid_email' => $this->lang->line('error_invalid_email')
							)
						),
						array(
							'field' => 'possess',
							'label' => strtolower($this->lang->line('label_possess')),
							'rules' => 'trim|greater_than_equal_to[0]|less_than_equal_to[' . $response['possess'] . ']',
							'errors' => array(
								'greater_than_equal_to' => $this->lang->line('error_greater_than_or_equal'),
								'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
							)
						),
						array(
							'field' => 'sport_comm',
							'label' => strtolower($this->lang->line('label_sport_comm')),
							'rules' => 'trim|greater_than_equal_to[0]|less_than_equal_to[' . $response['sport_comm'] . ']',
							'errors' => array(
								'greater_than_equal_to' => $this->lang->line('error_greater_than_or_equal'),
								'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
							)
						),
						array(
							'field' => 'casino_comm',
							'label' => strtolower($this->lang->line('label_casino_comm')),
							'rules' => 'trim|greater_than_equal_to[0]|less_than_equal_to[' . $response['casino_comm'] . ']',
							'errors' => array(
								'greater_than_equal_to' => $this->lang->line('error_greater_than_or_equal'),
								'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
							)
						),
						array(
							'field' => 'slots_comm',
							'label' => strtolower($this->lang->line('label_slots_comm')),
							'rules' => 'trim|greater_than_equal_to[0]|less_than_equal_to[' . $response['slots_comm'] . ']',
							'errors' => array(
								'greater_than_equal_to' => $this->lang->line('error_greater_than_or_equal'),
								'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
							)
						),
						array(
							'field' => 'cf_comm',
							'label' => strtolower($this->lang->line('label_cf_comm')),
							'rules' => 'trim|greater_than_equal_to[0]|less_than_equal_to[' . $response['cf_comm'] . ']',
							'errors' => array(
								'greater_than_equal_to' => $this->lang->line('error_greater_than_or_equal'),
								'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
							)
						),
						array(
							'field' => 'other_comm',
							'label' => strtolower($this->lang->line('label_other_comm')),
							'rules' => 'trim|greater_than_equal_to[0]|less_than_equal_to[' . $response['other_comm'] . ']',
							'errors' => array(
								'greater_than_equal_to' => $this->lang->line('error_greater_than_or_equal'),
								'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
							)
						),
						array(
							'field' => 'white_list_ip[]',
							'label' => strtolower($this->lang->line('label_white_list_ip')),
							'rules' => 'trim|valid_ip',
							'errors' => array(
								'valid_ip' => $this->lang->line('error_valid_ip')
							)
						),
						array(
							'field' => 'user_role',
							'label' => strtolower($this->lang->line('label_user_role')),
							'rules' => 'trim|required',
							'errors' => array(
								'required' => $this->lang->line('error_select_user_role'),
							)
						),
					);		
					$this->form_validation->set_rules($config);
					$this->form_validation->set_error_delimiters('', '');
					#Form validation
					if ($this->form_validation->run() == TRUE) {
						$possess = $this->input->post('possess', TRUE);
						$verifyData = $this->user_model->verify_downline_possess($oldData['user_id'], $possess);
						if($verifyData == TRUE) {
							#Database update
							$this->db->trans_start();
							$oldData['isUpdateUpline'] = $isUpdateUpline;
							$uplineId = trim($this->input->post('upline_select', TRUE));
							$oldData['uplineId'] = $uplineId;
							$newData = $this->user_model->update_user($oldData);
							if($this->session->userdata('user_group') == USER_GROUP_USER) {
								$this->user_model->insert_log(LOG_USER_UPDATE, $newData, $oldData);
							}
							else {
								$this->account_model->insert_log(LOG_USER_UPDATE, $newData, $oldData);
							}
							$this->db->trans_complete();
							if ($this->db->trans_status() === TRUE) {
								$json['status'] = EXIT_SUCCESS;
								$json['msg'] = $this->lang->line('success_updated');
								if(TELEGRAM_STATUS == STATUS_ACTIVE){
									if($newData['user_role'] != $oldData['user_role']){
										$newData['old_data'] = $oldData;
										$newData['old_role_data'] = $this->role_model->get_role_data($oldData['user_role']);
										$newData['new_role_data'] = $this->role_model->get_role_data($newData['user_role']);
										send_logs_telegram(TELEGRAM_LOGS,TELEGRAM_LOGS_TYPE_UPDATE_USER_CHARACTER,$newData);
									}
								}
								#Prepare for ajax update
								$json['response'] = array(
									'id' 			=> $newData['user_id'],
									'nickname' 		=> $newData['nickname'],
									'possess' 		=> $newData['possess'] . '%',
									'sport_comm' 	=> $newData['sport_comm'] . '%',
									'casino_comm' 	=> $newData['casino_comm'] . '%',
									'slots_comm' 	=> $newData['slots_comm'] . '%',
									'cf_comm' 		=> $newData['cf_comm'] . '%',
									'other_comm' 	=> $newData['other_comm'] . '%',
									'active' 		=> (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_suspend')),
									'active_code' 	=> $newData['active'],
									'user_type'     => $oldData['user_type']
								);
							}
							else {
								$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
							}
						}
						else {
							$json['msg']['general_error'] = $this->lang->line('error_possess_greater_than_or_equal');
						}	
					}
					else {
						$json['msg']['nickname_error'] = form_error('nickname');
						$json['msg']['mobile_error'] = form_error('mobile');
						$json['msg']['email_error'] = form_error('email');
						$json['msg']['possess_error'] = form_error('possess');
						$json['msg']['sport_comm_error'] = form_error('sport_comm');
						$json['msg']['casino_comm_error'] = form_error('casino_comm');
						$json['msg']['slots_comm_error'] = form_error('slots_comm');
						$json['msg']['other_comm_error'] = form_error('other_comm');
						$json['msg']['white_list_ip_error'] = form_error('white_list_ip[]');
						if( ! empty(form_error('user_role')))
						{
							$json['msg']['general_error'] = form_error('user_role');
						}
					}
					free_array($response);
				}
				else {
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
				}
				free_array($oldData);
			}
			else {
				$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
			}		
			//Output
			$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
			free_array($json);	
			exit();	
		}	
	}
	public function permission($id = NULL)
    {
		if(permission_validation(PERMISSION_PERMISSION_SETUP) == TRUE)
		{
			$data = $this->user_model->get_user_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$arr = get_agent_full_permission();
					$downline_permissions = explode(',', $data['permissions']);
					$upline_permissions = explode(',', $response['permissions']);
					$data['permissions'] = array();
					for($i=0;$i<sizeof($arr);$i++)
					{
						$data['permissions'][$arr[$i]]['downline'] = FALSE;
						if(in_array($arr[$i], $downline_permissions))
						{
							$data['permissions'][$arr[$i]]['downline'] = TRUE;
						}
						$data['permissions'][$arr[$i]]['upline'] = FALSE;
						if(in_array($arr[$i], $upline_permissions))
						{
							$data['permissions'][$arr[$i]]['upline'] = TRUE;
						}
					}
					$this->load->view('user_permission', $data);
				}
				else
				{
					redirect('home');
				}
			}
			else
			{
				redirect('home');
			}
		}
		else
		{
			redirect('home');
		}
	}
	public function permission_setup()
	{
		if(permission_validation(PERMISSION_PERMISSION_SETUP) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => '', 
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			$user_id = trim($this->input->post('user_id', TRUE));
			$oldData = $this->user_model->get_user_data($user_id);
			if( ! empty($oldData))
			{
				$response = $this->user_model->get_downline_data($oldData['upline']);
				if( ! empty($response))
				{
					$post_permissions = $this->input->post('permissions[]', TRUE);
					$upline_permissions = explode(',', $response['permissions']);
					$verified_permissions = array();
					for($i=0;$i<sizeof($post_permissions);$i++)
					{
						if(in_array($post_permissions[$i], $upline_permissions))
						{
							array_push($verified_permissions, $post_permissions[$i]);
						}
					}
					$permissions = implode(',', $verified_permissions);
					//Database update
					$this->db->trans_start();
					$newData = $this->user_model->update_user_permission($oldData, $permissions);
					$this->general_model->update_downline_permission($oldData['user_id'], $permissions);
					if($this->session->userdata('user_group') == USER_GROUP_USER)
					{
						$this->user_model->insert_log(LOG_USER_PERMISSION, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_USER_PERMISSION, $newData, $oldData);
					}
					$this->db->trans_complete();
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_permission_setup');
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
	public function password($id = NULL)
    {
		if(permission_validation(PERMISSION_CHANGE_PASSWORD) == TRUE)
		{
			$data = $this->user_model->get_user_data($id);
			if( ! empty($data))
			{
				if($id == $this->session->userdata('root_user_id'))
				{
					$data['upline'] = $this->session->userdata('root_username');
				}
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$this->load->view('user_password', $data);
				}
				else
				{
					redirect('home');
				}
			}
			else
			{
				redirect('home');
			}
		}
		else
		{
			redirect('home');
		}
	}
	
	public function editRole($id = null)
    {
		if(permission_validation(PERMISSION_USER_UPDATE))
		{
			$permissionUser = $this->session->userdata('permissions');			// get permission of table user_role

			$numberRole = $this->user_model->getNumberRole($permissionUser);

			$userPermissions = $this->user_model->get_user_data($id);

			$permissionList = $userPermissions['permissions'];					// get permission of table user

			if( ! empty($numberRole))
			{
				$data['roles'] = $numberRole;
				$data['id'] = $id;
				$data['permissionList'] = explode(",",$permissionList);
				$this->load->view('user_edit_role', $data);
			}
			else
			{
				redirect('home');
			}
		}
		else
		{
			redirect('home');
		}
	}
	public function role_update()
	{
		if(permission_validation(PERMISSION_USER_UPDATE))
		{
			
			$user_id = trim($this->input->post('user_id', true));
			$oldData = $this->user_model->get_user_data($user_id);
			if( ! empty($oldData))
			{
				$response = $this->user_model->get_downline_data($oldData['upline']);
				if( ! empty($response))
				{
					//Database update
					$this->db->trans_start();
					$this->user_model->roleUpdate($oldData);
					$this->db->trans_complete();
					if ($this->db->trans_status())
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
			
			//Output
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();
		}
	}
	
	public function password_update()
	{
		if(permission_validation(PERMISSION_CHANGE_PASSWORD) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'password_error' => '',
										'passconf_error' => '',
										'general_error' => ''
									), 
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			//Set form rules
			$config = array(
							array(
									'field' => 'password',
									'label' => strtolower($this->lang->line('label_password')),
									'rules' => 'trim|required|min_length[6]|max_length[15]|regex_match[/^[A-Za-z0-9!#$^*]+$/]',
									'errors' => array(
														'required' => $this->lang->line('error_enter_password'),
														'min_length' => $this->lang->line('error_invalid_password'),
														'max_length' => $this->lang->line('error_invalid_password'),
														'regex_match' => $this->lang->line('error_invalid_password')
												)
							),
							array(
									'field' => 'passconf',
									'label' => strtolower($this->lang->line('label_confirm_password')),
									'rules' => 'trim|required|matches[password]',
									'errors' => array(
														'required' => $this->lang->line('error_enter_confirm_password'),
														'matches' => $this->lang->line('error_confirm_password_not_match')
												)
							)
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$user_id = trim($this->input->post('user_id', TRUE));
				$oldData = $this->user_model->get_user_data($user_id);
				if( ! empty($oldData))
				{
					$response = $this->user_model->get_downline_data($oldData['upline']);
					if( ! empty($response))
					{
						//Database update
						$this->db->trans_start();
						$newData = $this->user_model->update_user_password($oldData);
						if($this->session->userdata('user_group') == USER_GROUP_USER)
						{
							$this->user_model->insert_log(LOG_USER_PASSWORD, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_USER_PASSWORD, $newData, $oldData);
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_change_password');
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
				$json['msg']['password_error'] = form_error('password');
				$json['msg']['passconf_error'] = form_error('passconf');
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
	public function deposit($id = NULL)
    {
		if(permission_validation(PERMISSION_DEPOSIT_POINT_TO_DOWNLINE) == TRUE)
		{
			$data = $this->user_model->get_user_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$data['upline_data'] = $response;
					$this->load->view('user_deposit', $data);
				}
				else
				{
					redirect('home');
				}
			}
			else
			{
				redirect('home');
			}
		}
		else
		{
			redirect('home');
		}
	}
	public function deposit_submit()
	{
		if(permission_validation(PERMISSION_DEPOSIT_POINT_TO_DOWNLINE) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'points_error' => '',
										'general_error' => ''
									),
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			$user_id = trim($this->input->post('user_id', TRUE));
			$oldData = $this->user_model->get_user_data($user_id);
			if( ! empty($oldData))
			{
				$response = $this->user_model->get_downline_data($oldData['upline']);
				if( ! empty($response))
				{
					//Set form rules
					$config = array(
									array(
											'field' => 'points',
											'label' => strtolower($this->lang->line('label_points')),
											'rules' => 'trim|greater_than[0]|less_than_equal_to[' . $response['points'] . ']',
											'errors' => array(
																'greater_than' => $this->lang->line('error_greater_than'),
																'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
														)
									)
								);		
					$this->form_validation->set_rules($config);
					$this->form_validation->set_error_delimiters('', '');
					//Form validation
					if ($this->form_validation->run() == TRUE)
					{
						$points = $this->input->post('points', TRUE);
						//Database update
						$this->db->trans_start();
						$newData = $this->user_model->point_transfer($oldData, $points);
						$newData2 = $this->user_model->point_transfer($response, ($points * -1));
						$this->general_model->insert_point_transfer_report($response, $oldData);
						if($this->session->userdata('user_group') == USER_GROUP_USER)
						{
							$this->user_model->insert_log(LOG_USER_DEPOSIT_POINT, $newData, $oldData);
							$this->user_model->insert_log(LOG_USER_WITHDRAW_POINT, $newData2, $response);
						}
						else
						{
							$this->account_model->insert_log(LOG_USER_DEPOSIT_POINT, $newData, $oldData);
							$this->account_model->insert_log(LOG_USER_WITHDRAW_POINT, $newData2, $response);
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_deposit_points');
							//Prepare for ajax update
							$json['response'] = array(
													'id' => $oldData['user_id'],
													'points' => number_format(($oldData['points'] + $points), 2, '.', ''),
													'upline_id' => $response['user_id'],
													'upline_points' => number_format(($response['points'] - $points), 2, '.', ''),
												);
						}
						else
						{
							$json['msg']['general_error'] = $this->lang->line('error_failed_to_deposit');
						}
					}
					else 
					{
						$json['msg']['points_error'] = form_error('points');
					}
				}
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_deposit');
				}
			}
			else
			{
				$json['msg']['general_error'] = $this->lang->line('error_failed_to_deposit');
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
	public function withdraw($id = NULL)
    {
		if(permission_validation(PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE) == TRUE)
		{
			$data = $this->user_model->get_user_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$this->load->view('user_withdraw', $data);
				}
				else
				{
					redirect('home');
				}
			}
			else
			{
				redirect('home');
			}
		}
		else
		{
			redirect('home');
		}
	}
	public function withdraw_submit()
	{
		if(permission_validation(PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'points_error' => '',
										'general_error' => ''
									),
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			$user_id = trim($this->input->post('user_id', TRUE));
			$oldData = $this->user_model->get_user_data($user_id);
			if( ! empty($oldData))
			{
				$response = $this->user_model->get_downline_data($oldData['upline']);
				if( ! empty($response))
				{
					//Set form rules
					$config = array(
									array(
											'field' => 'points',
											'label' => strtolower($this->lang->line('label_points')),
											'rules' => 'trim|greater_than[0]|less_than_equal_to[' . $oldData['points'] . ']',
											'errors' => array(
																'greater_than' => $this->lang->line('error_greater_than'),
																'less_than_equal_to' => $this->lang->line('error_less_than_or_equal')
														)
									)
								);		
					$this->form_validation->set_rules($config);
					$this->form_validation->set_error_delimiters('', '');
					//Form validation
					if ($this->form_validation->run() == TRUE)
					{
						$points = $this->input->post('points', TRUE);
						//Database update
						$this->db->trans_start();
						$newData = $this->user_model->point_transfer($oldData, ($points * -1));
						$newData2 = $this->user_model->point_transfer($response, $points);
						$this->general_model->insert_point_transfer_report($oldData, $response);
						if($this->session->userdata('user_group') == USER_GROUP_USER)
						{
							$this->user_model->insert_log(LOG_USER_WITHDRAW_POINT, $newData, $oldData);
							$this->user_model->insert_log(LOG_USER_DEPOSIT_POINT, $newData2, $response);
						}
						else
						{
							$this->account_model->insert_log(LOG_USER_WITHDRAW_POINT, $newData, $oldData);
							$this->account_model->insert_log(LOG_USER_DEPOSIT_POINT, $newData2, $response);
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_withdraw_points');
							//Prepare for ajax update
							$json['response'] = array(
													'id' => $oldData['user_id'],
													'points' => number_format(($oldData['points'] - $points), 2, '.', ''),
													'upline_id' => $response['user_id'],
													'upline_points' => number_format(($response['points'] + $points), 2, '.', ''),
												);
						}
						else
						{
							$json['msg']['general_error'] = $this->lang->line('error_failed_to_withdraw');
						}
					}
					else 
					{
						$json['msg']['points_error'] = form_error('points');
					}
				}
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_withdraw');
				}
			}
			else
			{
				$json['msg']['general_error'] = $this->lang->line('error_failed_to_withdraw');
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
	public function get_all_user_data(){
		$json = array(
			'status' => EXIT_ERROR, 
			'msg' => '',
			'total_data' => '',
			'response' => '',
		);
		$result = $this->user_model->get_all_user_data();
		if(!empty($result)){
			$json['status'] = EXIT_SUCCESS;
			$json['response'] = $result;
			$json['total_data'] = sizeof($result);
		}
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		exit();
	}
}