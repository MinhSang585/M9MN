<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Player extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('bank_model', 'level_model', 'game_model', 'group_model', 'miscellaneous_model', 'player_model', 'avatar_model','level_model','deposit_model','withdrawal_model','promotion_model','bonus_model','message_model','promotion_approve_model','tag_model','player_promotion_model','user_model'));
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}
	
	public function index()
	{
		if(permission_validation(PERMISSION_PLAYER_VIEW) == TRUE)
		{
			$this->save_current_url('player');
			$this->session->unset_userdata('search_players');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_player');
			$max_level = 0;
			$level_data = $this->level_model->get_higest_level();
			if(!empty($level_data)){
				$max_level = $level_data['level_number'];
			}
			$data_search = array( 
				//'from_date' => date('Y-m-d 00:00:00'),
				//'to_date' => date('Y-m-d 23:59:59'),
				'from_date' => '',
				'to_date' => '',
				'upline' => '',
				'username' => '',
				'status' => '',
				'player_type' => '',
				'mobile' => '',
				'bank_account_name' => '',
				'bank_account_no' => '',
				'agent' => '',
				'referrer' => '',
				'max_level' => $max_level,
				'line_id' => '',
			);
			$data['data_search'] = $data_search;
			$this->session->set_userdata('search_players', $data_search);
			$this->load->view('player_view', $data);
		}
		else if(permission_validation(PERMISSION_PLAYER_AGENT_VIEW) == TRUE)
		{
			redirect('player/agent');
		}
		else
		{
			redirect('home');
		}
	}
	public function search()
	{
		if(permission_validation(PERMISSION_PLAYER_VIEW) == TRUE)
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
					'upline' => trim($this->input->post('upline', TRUE)),
					'username' => trim($this->input->post('username', TRUE)),
					'status' => trim($this->input->post('status', TRUE)),
					'player_type' => trim($this->input->post('player_type', TRUE)),
					'mobile' => trim($this->input->post('mobile', TRUE)),
					'bank_account_name' => trim($this->input->post('bank_account_name', TRUE)),
					'bank_account_no' => trim($this->input->post('bank_account_no', TRUE)),
					'agent' => trim($this->input->post('agent', TRUE)),
					'referrer' => trim($this->input->post('referrer', TRUE)),
					'line_id' => trim($this->input->post('line_id', TRUE)),
					'tag' => $this->input->post('tag[]', TRUE),
					'tag_player' => $this->input->post('tag_player[]', TRUE),
				);
				$this->session->set_userdata('search_players', $data);
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
	public function upline_search()
	{
		if(permission_validation(PERMISSION_PLAYER_ADD) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("page", TRUE))-1;
			$search = trim($this->input->post("search", TRUE));
			$columns = array( 
				0 => 'user_id',
				1 => 'username',
			);
			$order = "";
			$order .= "CASE
			    WHEN username LIKE '".$search."' THEN 1
			    WHEN username LIKE '".$search."%' THEN 2
			    WHEN username LIKE '%".$search."' THEN 4
			    ELSE 3
			END";
			$where = "WHERE upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' OR user_id = '".$this->session->userdata('root_user_id')."'";	
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;
			$query_string = "SELECT {$select} FROM {$dbprefix}users $where";
			$query_string_2 = " ORDER by {$order} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if($query->num_rows() > 0)
			{
				$posts = $query->result();  
			}
			$query->free_result();
			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();
			//Prepare data
			$data = array();
			if(!empty($posts)){
				foreach ($posts as $post)
				{
					$row = array();
					$row['id'] = $post->username;
					$row['text'] = $post->username;
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
	public function username_search()
	{
		if(permission_validation(PERMISSION_PLAYER_ADD) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("page", TRUE))-1;
			$search = trim($this->input->post("search", TRUE));
			$columns = array( 
				0 => 'player_id',
				1 => 'username',
			);
			$order = "";
			$order .= "CASE
			    WHEN username LIKE '".$search."' THEN 1
			    WHEN username LIKE '".$search."%' THEN 2
			    WHEN username LIKE '%".$search."' THEN 4
			    ELSE 3
			END";
			$where = "WHERE upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;
			$query_string = "SELECT {$select} FROM {$dbprefix}players $where";
			$query_string_2 = " ORDER by {$order} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if($query->num_rows() > 0)
			{
				$posts = $query->result();  
			}
			$query->free_result();
			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();
			//Prepare data
			$data = array();
			if(!empty($posts)){
				foreach ($posts as $post)
				{
					$row = array();
					$row['id'] = $post->username;
					$row['text'] = $post->username;
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
	public function referrer_search()
	{
		if(permission_validation(PERMISSION_PLAYER_ADD) == TRUE || permission_validation(PERMISSION_PLAYER_AGENT_ADD) == TRUE )
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("page", TRUE))-1;
			$search = trim($this->input->post("search", TRUE));
			$upline = trim($this->input->post("upline", TRUE));
			$username = trim($this->input->post("username", TRUE));
			$columns = array( 
				0 => 'player_id',
				1 => 'username',
			);
			$order = "";
			$order .= "CASE
			    WHEN username LIKE '".$search."' THEN 1
			    WHEN username LIKE '".$search."%' THEN 2
			    WHEN username LIKE '%".$search."' THEN 4
			    ELSE 3
			END";
			$where = "WHERE player_id = 'abc'";
			if(!empty($upline)){
				$userData = $this->user_model->get_user_data_by_username($upline);
				if(!empty($userData)){
					$where = "WHERE upline_ids LIKE '%," . $userData['user_id'] . ",%'";				
				}
			}
			if(!empty($username)){
				$where .= " AND username != '".$username."'";
			}
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;
			$query_string = "SELECT {$select} FROM {$dbprefix}players $where";
			$query_string_2 = " ORDER by {$order} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if($query->num_rows() > 0)
			{
				$posts = $query->result();  
			}
			$query->free_result();
			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();
			//Prepare data
			$data = array();
			if(!empty($posts)){
				foreach ($posts as $post)
				{
					$row = array();
					$row['id'] = $post->username;
					$row['text'] = $post->username;
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
	public function listing()
    {
		if(permission_validation(PERMISSION_PLAYER_VIEW) == TRUE)
		{
			$bank_group_list = $this->group_model->get_group_list(GROUP_BANK);
			$tag_list = $this->tag_model->get_tag_list();
			$tag_player_list = $this->tag_model->get_tag_player_list();
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'a.player_id',
				1 => 'a.username',
				2 => 'a.nickname',
				3 => 'a.level_id',
				4 => 'a.tag_ids',
				5 => 'a.upline',
				6 => 'a.player_type',
				7 => 'a.points',
				8 => 'a.rewards',
				9 => 'a.active',
				10 => 'a.mark',
				11 => 'a.bank_group_id',
				12 => 'a.bank_account_name',
				13 => 'a.is_offline_deposit',
				14 => 'a.created_date',
				15 => 'a.last_login_date',
				16 => 'a.created_ip',
				17 => 'a.last_login_ip',
				18 => 'a.is_online_deposit',
				19 => 'a.is_credit_card_deposit',
				20 => 'a.is_hypermart_deposit',
				21 => 'a.turnover_total_current',
				22 => 'a.turnover_total_required',
				23 => 'a.turnover_start_date',
				24 => 'a.remark',
				25 => 'a.tag_id',
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
			$arr = $this->session->userdata('search_players');				
			#$max_level = $arr['max_level'];
			if( ! empty($arr['agent']))
			{
				$where = "WHERE player_id = 'ABC'";
				$agent = $this->user_model->get_user_data_by_username($arr['agent']);
				if(!empty($agent)){
					$response_upline = $this->user_model->get_downline_data($agent['username']);
					if(!empty($response_upline)){
						$where = "WHERE a.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";
					}
				}
			}else{
				$where = "WHERE a.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";
			}
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
			if( ! empty($arr['bank_account_name'])){
			    $player_bank = $this->bank_model->get_all_player_id_with_bank_account_name($arr['bank_account_name']);
        	    if(!empty($player_bank)){
        	       $player_array = array();
        	       foreach($player_bank as $player_row){
            	        if( ! in_array($player_row['player_id'], $player_array))
            			{
            				array_push($player_array,$player_row['player_id']);
            			}    
        	       }
        	       if(!empty($player_array)){
        	           $player_bank_string = implode(',', $player_array);
        	           $where .= " AND a.player_id IN (".$player_bank_string.")";
        	       }else{
        	           $where .= " AND a.player_id = 0";
        	       }
        	    }else{
        	       $where .= " AND a.player_id = 0";
        	    }
			}
			if( ! empty($arr['bank_account_no'])){
			    $player_bank = $this->bank_model->get_all_player_id_with_bank_account_no($arr['bank_account_no']);
        	    if(!empty($player_bank)){
        	       $player_array = array();
        	       foreach($player_bank as $player_row){
            	        if( ! in_array($player_row['player_id'], $player_array))
            			{
            				array_push($player_array,$player_row['player_id']);
            			}    
        	       }
        	       if(!empty($player_array)){
        	           $player_bank_string = implode(',', $player_array);
        	           $where .= " AND a.player_id IN (".$player_bank_string.")";
        	       }else{
        	           $where .= " AND a.player_id = 0";
        	       }
        	    }else{
        	       $where .= " AND a.player_id = 0";
        	    }
			}
			if( ! empty($arr['upline']))
			{
				$where .= " AND a.upline LIKE '%" . $arr['upline'] . "%' ESCAPE '!'";	
			}
			if( ! empty($arr['username']))
			{
				$where .= " AND a.username LIKE '" . $arr['username'] . "%' ESCAPE '!'";	
			}
			if( ! empty($arr['mobile']))
			{
				$where .= " AND a.mobile LIKE '%" . $arr['mobile'] . "%' ESCAPE '!'";	
			}
			if($arr['player_type'] >= 1 && $arr['player_type'] <= sizeof(get_player_type()))
			{
				$where .= ' AND a.player_type = ' . $arr['player_type'];
			}
			if( ! empty($arr['referrer']))
			{
				$where .= ' AND a.referrer = "' . $arr['referrer'].'"';
			}
			if( ! empty($arr['line_id']))
			{
				$where .= ' AND a.line_id = "' . $arr['line_id'].'"';
			}
			if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_SUSPEND)
			{
				$where .= ' AND a.active = ' . $arr['status'];
			}
			if(!empty($arr['tag'])){
				$where .= ' AND (';
				for($i=0;$i<sizeof($arr['tag']);$i++){
					if($i == 0){
						$where .= 'a.tag_id = ' . $arr['tag'][$i];
					}else{
						$where .= ' OR a.tag_id = ' . $arr['tag'][$i];
					}
				}
				$where .= ')';
			}
			if(!empty($arr['tag_player'])){
				$where .= ' AND (';
				for($i=0;$i<sizeof($arr['tag_player']);$i++){
					if($i == 0){
						$where .= "a.tag_ids LIKE '%," . $arr['tag_player'][$i] . ",%' ESCAPE '!'";
					}else{
						$where .= " OR a.tag_ids LIKE '%," . $arr['tag_player'][$i] . ",%' ESCAPE '!'";
					}
				}
				$where .= ')';
			}
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;
			$posts = NULL;
			$query_string = "SELECT {$select} FROM {$dbprefix}players a $where";
			if($start != null || $limit != null) {
				$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			}
			else {
				$query_string_2 = " ORDER by {$order} {$dir}";
			}
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
			$player_list = array();
			$bank_list = array();
			$player_bank_data = array();
			$miscellaneous = array();
			$player_withdrawal_count_list = array();
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					$player_list[] = $post->player_id;
					$player_withdrawal_count_list[$post->player_id] = 0;
				}
				$player_bank_data = $this->bank_model->get_player_bank_data_by_player_array($player_list);
				if(!empty($player_bank_data)){
					$bank_list = $this->bank_model->get_all_bank_name();
				}
				$miscellaneous = $this->miscellaneous_model->get_miscellaneous();
				$player_bank_max_account = 3;//$miscellaneous['player_bank_account_max'];
				if(!empty($player_bank_data)){
					foreach($player_bank_data as $player_bank_data_key => $player_bank_data_row) {
						if(isset($player_bank_data_row[BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK]) && sizeof($player_bank_data_row[BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK]) > 0){
							for($i=0;$i<sizeof($player_bank_data_row[BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK]);$i++){
								if($i == 0){
									$player_withdrawal_count_list[$player_bank_data_key] = $player_bank_data_row[BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK][$i]['withdrawal_count'];
								}else{
									if($player_bank_data_row[BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK][$i]['withdrawal_count'] < $player_withdrawal_count_list[$player_bank_data_key]){
										$player_withdrawal_count_list[$player_bank_data_key] = $player_bank_data_row[BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK][$i]['withdrawal_count'];
									}
								}
							}
						}
					}
				}
			}
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					$level = "";
					$bank_name = "";
					$status = "";
					$withdrawal_limit = "";
					
					$levelIdPlayer = $post->level_id;

					$level_data = $this->level_model->getLevelDataByPlayerLevelId($levelIdPlayer);

					if(!empty($level_data)){
						$level_name = $level_data->level_name;
					}
									
					$level = $level_name;

					$player_bank_account_data_json = array();
					$player_bank_account_data = ((isset($player_bank_data[$post->player_id][BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK]))? $player_bank_data[$post->player_id][BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK] : array());
					$player_bank_show = '<div class="row">';
					for($i=0;$i<$player_bank_max_account;$i++){
						if(isset($player_bank_account_data[$i])){
							$bank_name = ((isset($bank_list[$player_bank_account_data[$i]['bank_id']])) ? $bank_list[$player_bank_account_data[$i]['bank_id']] : '');
							$temp_array = array(
								'player_bank_id' => $player_bank_account_data[$i]['player_bank_id'],
								'player_id' => $player_bank_account_data[$i]['player_id'],
								'bank_id' => $player_bank_account_data[$i]['bank_id'],
								'bank_account_no' => $player_bank_account_data[$i]['bank_account_no'],
								'bank_account_name' => $player_bank_account_data[$i]['bank_account_name'],
								'active' => $player_bank_account_data[$i]['active'],
								'verify' => $player_bank_account_data[$i]['verify'],
								'player_bank_type' => $player_bank_account_data[$i]['player_bank_type'],
								'bank_name' => $bank_name,
							);
							$player_bank_account_data_json[] = $temp_array;
							$player_bank_show .= '<div class="col-4">';
								$player_bank_show .= '<small id="display_player_bank_account_'.$post->player_id.'_'.($i+1).'" class="badge badge-primary display_player_bank_account_'.$post->player_id.'" onclick="display_player_bank_account('.$post->player_id.','.($i+1).')">'.$this->lang->line('label_bank_name').($i+1).'</small>';
							$player_bank_show .= '</div>';
						}else{
							$player_bank_show .= '<div class="col-4">';
								$player_bank_show .= '<small id="display_player_bank_account_'.$post->player_id.'_'.($i+1).'" class="badge badge-secondary display_player_bank_account_'.$post->player_id.'" onclick="display_player_bank_account('.$post->player_id.','.($i+1).')">'.$this->lang->line('label_bank_name').($i+1).'</small>';
							$player_bank_show .= '</div>';
						}
					}
					$player_bank_show .= '</div>';
					if(isset($player_bank_account_data[0])){
						$bank_name =  ((isset($bank_list[$player_bank_account_data[0]['bank_id']])) ? $bank_list[$player_bank_account_data[0]['bank_id']] : "");
						$player_bank_show .= '<div class="row pt-3">';
						$player_bank_show .= '<div class="col-12 text-center">';
						if(permission_validation(PERMISSION_BANK_PLAYER_USER_UPDATE) == TRUE)
						{
							$player_bank_show .= '<i id="uc84_' . $player_bank_account_data[0]['player_id'] . '" onclick="updatePlayerBankData(' . $player_bank_account_data[0]['player_bank_id'] . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
						}
						if(permission_validation(PERMISSION_BANK_PLAYER_USER_ADD) == TRUE)
						{
							$player_bank_show .= '<i style="display:none;" id="uc85_' . $player_bank_account_data[0]['player_id'] . '" onclick="add_player_bank(' . $player_bank_account_data[0]['player_id'] . ')" class="fas fa-piggy-bank nav-icon text-purple" title="' . $this->lang->line('button_add')  . '"></i> &nbsp;&nbsp; ';
						}
						if($player_bank_account_data[0]['verify'] == STATUS_VERIFY){
							$player_bank_show .= '<span id="uc80_' . $player_bank_account_data[0]['player_id'] . '">'.$bank_name.'</span>'.' <span class="badge bg-success" id="uc81_' . $player_bank_account_data[0]['player_id'] . '">' . $this->lang->line('status_verify') . '</span>';
						}else{
							$player_bank_show .= '<span id="uc80_' . $player_bank_account_data[0]['player_id'] . '">'.$bank_name.'</span>'.' <span class="badge bg-secondary" id="uc81_' . $player_bank_account_data[0]['player_id'] . '">' . $this->lang->line('status_unverify') . '</span>';
						}
						$player_bank_show .= '</div>';
						$player_bank_show .= '<div class="col-12 text-center" id="uc82_' . $player_bank_account_data[0]['player_id'] . '">';
						$player_bank_show .=  $player_bank_account_data[0]['bank_account_name'];
						$player_bank_show .= '</div>';
						$player_bank_show .= '<div class="col-12 text-center" id="uc83_' . $player_bank_account_data[0]['player_id'] . '">';
						$player_bank_show .=  $player_bank_account_data[0]['bank_account_no'];
						$player_bank_show .= '</div>';
						$player_bank_show .= '</div>';
					}else{
						$player_bank_show .= '<div class="row pt-3">';
						$player_bank_show .= '<div class="col-12 text-center">';
						/*
						if(permission_validation(PERMISSION_BANK_PLAYER_USER_UPDATE) == TRUE) {							
							$player_bank_show .= '<i style="display:none;" id="uc84_' . $post->player_id . '" onclick="updatePlayerBankData(' . $player_bank_account_data[0]['player_bank_id'] . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
						}
						if(permission_validation(PERMISSION_BANK_PLAYER_USER_ADD) == TRUE)
						{
							$player_bank_show .= '<i id="uc85_' . $post->player_id . '" onclick="add_player_bank(' . $post->player_id . ')" class="fas fa-piggy-bank nav-icon text-purple" title="' . $this->lang->line('button_add')  . '"></i> &nbsp;&nbsp; ';
						}
						if($player_bank_account_data[0]['verify'] == STATUS_VERIFY){
							$player_bank_show .= '<span  style="display:none;" id="uc80_' . $post->player_id . '">'.$bank_name.'</span>'.' <span  style="display:none;" class="badge bg-success" id="uc81_' . $post->player_id . '">' . $this->lang->line('status_verify') . '</span>';
						}else{
							$player_bank_show .= '<span  style="display:none;" id="uc80_' . $post->player_id . '">'.$bank_name.'</span>'.' <span  style="display:none;" class="badge bg-secondary" id="uc81_' . $post->player_id . '">' . $this->lang->line('status_unverify') . '</span>';
						}
						*/
						$player_bank_show .= '</div>';
						$player_bank_show .= '<div  style="display:none;" class="col-12 text-center" id="uc82_' . $post->player_id . '">';
						$player_bank_show .=  '';
						$player_bank_show .= '</div>';
						$player_bank_show .= '<div  style="display:none;" class="col-12 text-center" id="uc83_' . $post->player_id . '">';
						$player_bank_show .=  '';
						$player_bank_show .= '</div>';
						$player_bank_show .= '</div>';
					}
					$player_bank_show .= '<textarea id="player_bank_account_data_json_'.$post->player_id.'" style="display:none;">'.json_encode($player_bank_account_data_json,true).'</textarea>';
					$bank_show_name = "";
					if($bank_group_list != null) {
						for($i=0;$i<sizeof($bank_group_list);$i++)
						{
							if(isset($bank_group_list)) 
							{
								$arr = explode(',',  $post->bank_group_id);
								$arr = array_values(array_filter($arr));
								if(in_array($bank_group_list[$i]['group_id'], $arr)) 
								{
									if(empty($bank_show_name)){
										$bank_show_name .= '<small class="badge badge-primary">'.$bank_group_list[$i]['group_name'].'</small>';
									}else{
										$bank_show_name .= " , ".'<small class="badge badge-primary">'.$bank_group_list[$i]['group_name'].'</small>';
									}
								}
							}
						}
					}	
					$bank_channel = "";
					if(permission_validation(PERMISSION_PLAYER_UPDATE) == TRUE)
					{
						if($post->is_offline_deposit){
							$bank_channel .= '<small class="badge badge-success pointer_show" onclick="updateData(' . $post->player_id . ')">'.$this->lang->line('deposit_offline_banking').'</small>';
						}
						if($post->is_online_deposit){
							$bank_channel .= '<small class="badge badge-warning pointer_show" onclick="updateData(' . $post->player_id . ')">'.$this->lang->line('deposit_online_banking').'</small>';
						}
						if($post->is_credit_card_deposit){
							$bank_channel .= '<small class="badge badge-primary pointer_show" onclick="updateData(' . $post->player_id . ')">'.$this->lang->line('deposit_credit_card').'</small>';
						}
						if($post->is_hypermart_deposit){
							$bank_channel .= '<small class="badge badge-danger pointer_show" onclick="updateData(' . $post->player_id . ')">'.$this->lang->line('deposit_hypermart').'</small>';
						}
					}else{
						if($post->is_offline_deposit){
							$bank_channel .= '<small class="badge badge-success">'.$this->lang->line('deposit_offline_banking').'</small>';
						}
						if($post->is_online_deposit){
							$bank_channel .= '<small class="badge badge-warning">'.$this->lang->line('deposit_online_banking').'</small>';
						}
						if($post->is_credit_card_deposit){
							$bank_channel .= '<small class="badge badge-primary">'.$this->lang->line('deposit_credit_card').'</small>';
						}
						if($post->is_hypermart_deposit){
							$bank_channel .= '<small class="badge badge-danger">'.$this->lang->line('deposit_hypermart').'</small>';
						}
					}
					$tag = '<div id="uc21_' . $post->player_id . '">';
					if(isset($tag_list[$post->tag_id])){
						$tag .= '<span class="badge bg-success" style="background-color: '.$tag_list[$post->tag_id]['tag_background_color'].' !important;color: '.$tag_list[$post->tag_id]['tag_font_color'].' !important;font-weight: '.(($tag_list[$post->tag_id]['is_bold'] == STATUS_ACTIVE) ? "bold": "normal").' !important;">' . $tag_list[$post->tag_id]['tag_code'] . '</span>';						
					}
					$tag .= "</div>";
					$tags = '<div id="uc22_' . $post->player_id . '">';
					$tags_option = "";
					if(!empty($post->tag_ids)){
						$tags_array = array_values(array_filter(explode(',',  $post->tag_ids)));
						foreach($tags_array as $tags_row){
							if(isset($tag_player_list[$tags_row])){
								$tags_option .= '<span class="badge bg-success" style="background-color: '.$tag_player_list[$tags_row]['tag_player_background_color'].' !important;color: '.$tag_player_list[$tags_row]['tag_player_font_color'].' !important;font-weight: '.(($tag_player_list[$tags_row]['is_bold'] == STATUS_ACTIVE) ? "bold": "normal").' !important;"">' . $tag_player_list[$tags_row]['tag_player_code'] . '</span>&nbsp;';
							}
						}
						if(!empty($tags_option)){
							$tags_option .= '</br>';
						}
					}
					$tags .= $tags_option;
					$tags .= "</div>";
					if(permission_validation(PERMISSION_TAG_PLAYER_MODIFY) == TRUE){
						$tags .= '<div id="uc23_' . $post->player_id . '" class="col-12 text-center pointer_show">';
						$tags .= '<i onclick="modifyPlayerTagData(' . $post->player_id . ')" class="fas fa-user-tag nav-icon text-fuchsia" title="' . $this->lang->line('button_tag_player')  . '"></i>';
						$tags .= "</div>";
					}
					$markData = "";
					$row = array();
					$row[] = $post->player_id;
					$row[] = $post->username.'&nbsp;<i class="pointer_show fas fa-copy nav-icon text-purple clipboard" data-clipboard-text="'.$post->username.'"></i>&nbsp;'.$tag;
					$row[] = '<span id="uc1_' . $post->player_id . '">' . $post->nickname . '</span>';
					$row[] = $level;
					$row[] = $tags;
					$row[] = ( ! empty($post->upline) ? '<a href="javascript:void(0);" onclick="getDownline(\'' . $post->upline . '\')">' . $post->upline . '</a>' : '-');
					$row[] = '<span id="uc4_' . $post->player_id . '">' . $this->lang->line(get_player_type($post->player_type)) . '</span>';
					$row[] = '<span id="uc2_' . $post->player_id . '">' . $post->points . '</span>';
					$row[] = '<span id="uc5_' . $post->player_id . '">' . $post->rewards . '</span>';
					if(isset($player_withdrawal_count_list[$post->player_id]) && $player_withdrawal_count_list[$post->player_id] < NEW_MEMBER_WITHDRAWAL_LIMIT){
						$withdrawal_limit = '<span class="badge bg-teal">' . $this->lang->line('label_new_players') . '</span>';
					}
					switch($post->active)
					{
						case STATUS_ACTIVE: $status = '<span class="badge bg-success" id="uc3_' . $post->player_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $status = '<span class="badge bg-secondary" id="uc3_' . $post->player_id . '">' . $this->lang->line('status_suspend') . '</span>'; break;
					}
					$row[] = $withdrawal_limit.((!empty($withdrawal_limit)) ?  "<br/>".$status : $status);
					$markData .= '<div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                    <ul class="fc-color-picker" id="uc10_' . $post->player_id . '">
                    	<li ><i class="fas fa-square text-'.get_mark_type($post->mark).'"></i></li>
                    </ul>';
                    if(permission_validation(PERMISSION_PLAYER_UPDATE) == TRUE)
					{
                    $markData .= '<button type="button" class="btn btn-light dropdown-toggle col-6" data-toggle="dropdown" aria-expanded="false">
                    </button>
                    <ul class="dropdown-menu fc-color-picker col-4">';
                    $get_mark_type = get_mark_type();
					if(!empty($get_mark_type)){
						foreach ($get_mark_type as $key => $value) {
							$markData .= '<li class="" onclick="change_player_mark(\'' . $post->player_id . '\',\'' . $key . '\')"><i class="fas fa-square text-'.$value.'"></i></li>';
						}
					}
                    $markData .= '</ul>';
					}
					$markData .= '</div>';
					$row[] = $markData;
					$row[] = '<span id="uc21_' . $post->player_id . '">'.$bank_channel.'</span>';
					$row[] = '<span id="uc88_' . $post->player_id . '">'.$player_bank_show.'</span>';
					$row[] = '<span id="uc22_' . $post->player_id . '">'.$bank_show_name.'</span>';
					$register_info = "";
					$login_info = "";
					$register_info .= (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date)."<br>" : '');
					$register_info .= ((!empty($post->created_ip)) ? $post->created_ip."<br>" : '');
					$login_info .= (($post->last_login_date > 0) ? date('Y-m-d H:i:s', $post->last_login_date)."<br>" : '');
					$login_info .= ((!empty($post->last_login_ip)) ? $post->last_login_ip."<br>" : '');
					$row[] = $register_info;
					$row[] = $login_info;
					if(permission_validation(PERMISSION_ADJUST_PLAYER_TURNOVER) == TRUE){
						$turnover = '<span id="uc6_' . $post->player_id . '" onclick="adjust_turnover(' . $post->player_id . ')">' . number_format($post->turnover_total_current, 0,'.',',')." : ".number_format($post->turnover_total_required, 0,'.',',') . '</span>';
					}else{
						$turnover = '<span id="uc6_' . $post->player_id . '">' . number_format($post->turnover_total_current, 0,'.',',')." : ".number_format($post->turnover_total_required, 0,'.',',') . '</span>';
					}
					if(permission_validation(PERMISSION_PLAYER_PROMOTION_TURNOVER_CALCULATE) == TRUE){
						$turnover .= '<br/><span id="uc7_' . $post->player_id . '"> - : - </span>';
						$turnover .= '&nbsp; <i id="uc8_' . $post->player_id . '" onclick="calculate_promotion_turnover(' . $post->player_id . ')" class="fas fa-gifts nav-icon text-danger" title="' . $this->lang->line('button_calculate_promotion_turnover')  . '"></i> &nbsp;&nbsp; ';
					}
					
					$row[] = '<span id="uc9_' . $post->player_id . '">' . $post->remark . '</span>'; 		// row 16
					$row[] = $turnover;
					$button = '';
					if(permission_validation(PERMISSION_PLAYER_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->player_id . ')" class="fas fa-edit nav-icon text-primary fa-cust1" title="' . $this->lang->line('button_edit')  . '"></i>';
					}
					if(permission_validation(PERMISSION_VIEW_PLAYER_CONTACT) == TRUE)
					{
						$button .= '<i onclick="viewData(' . $post->player_id . ')" class="fas fa-file nav-icon text-warning fa-cust1" title="' . $this->lang->line('button_view')  . '"></i>';
					}
					if(permission_validation(PERMISSION_VIEW_PLAYER_CONTACT_V2) == TRUE)
					{
						$button .= '<i onclick="viewData2(' . $post->player_id . ')" class="fas fa-file nav-icon text-warning fa-cust1" title="' . $this->lang->line('button_view')  . '"></i>';
					}
					if(permission_validation(PERMISSION_VIEW_PLAYER_WALLET) == TRUE)
					{
						$button .= '<i onclick="viewWallets(' . $post->player_id . ')" class="fas fa-wallet nav-icon text-navy fa-cust1" title="' . $this->lang->line('button_wallet')  . '"></i>';
					}
					if(permission_validation(PERMISSION_CHANGE_PASSWORD) == TRUE)
					{
						$button .= '<i onclick="changePassword(' . $post->player_id . ')" class="fas fa-key nav-icon text-secondary fa-cust1" title="' . $this->lang->line('button_change_password')  . '"></i>';
					}
					if(permission_validation(PERMISSION_DEPOSIT_POINT_TO_DOWNLINE) == TRUE)
					{
						$button .= '<i onclick="depositPoints(' . $post->player_id . ')" class="fas fa-arrow-up nav-icon text-olive fa-cust1" title="' . $this->lang->line('button_deposit_points')  . '"></i>';
					}
					if(permission_validation(PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE) == TRUE)
					{
						$button .= '<i onclick="withdrawPoints(' . $post->player_id . ')" class="fas fa-arrow-down nav-icon text-maroon fa-cust1" title="' . $this->lang->line('button_withdraw_points')  . '"></i>';
					}
					if(permission_validation(PERMISSION_PLAYER_POINT_ADJUSTMENT) == TRUE)
					{
						$button .= '<i onclick="adjustPoints(' . $post->player_id . ')" class="fas fa-adjust nav-icon text-teal fa-cust1" title="' . $this->lang->line('button_point_adjustment')  . '"></i>';
					}
					if(permission_validation(PERMISSION_DEPOSIT_ADD) == TRUE)
					{
						$button .= '<i onclick="deposit_offline(' . $post->player_id . ')" class="fas fa-hand-holding-usd nav-icon text-indigo fa-cust1" title="' . $this->lang->line('button_deposit_offline')  . '"></i>';	
					}
					if(permission_validation(PERMISSION_WITHDRAWAL_ADD) == TRUE)
					{
						$button .= '<i onclick="withdrawal_offline(' . $post->player_id . ')" class="fas fa-file-invoice-dollar nav-icon text-info fa-cust1" title="' . $this->lang->line('button_withdrawal_offline')  . '"></i>';	
					}
					if(permission_validation(PERMISSION_DEPOSIT_APPROVE_ADD) == TRUE)
					{
						$button .= '<i onclick="deposit_offline_approve(' . $post->player_id . ')" class="fas fa-hand-holding-usd nav-icon text-indigo fa-cust1" title="' . $this->lang->line('button_deposit_offline')  . '"></i>';	
					}
					if(permission_validation(PERMISSION_WITHDRAWAL_APPROVE_ADD) == TRUE)
					{
						$button .= '<i onclick="withdrawal_offline_approve(' . $post->player_id . ')" class="fas fa-file-invoice-dollar nav-icon text-info fa-cust1" title="' . $this->lang->line('button_withdrawal_offline')  . '"></i>';	
					}
					if(permission_validation(PERMISSION_REWARD_DEDUCT) == TRUE)
					{
						$button .= '<i onclick="reward_deduct(' . $post->player_id . ')" class="fas fa-credit-card nav-icon text-fuchsia fa-cust1" title="' . $this->lang->line('button_reward_deduct')  . '"></i>';	
					}
					if(permission_validation(PERMISSION_PLAYER_DAILY_REPORT) == TRUE)
					{
						$button .= '<i onclick="player_daily(' . $post->player_id . ')" class="fas fa-clipboard-check nav-icon text-olive fa-cust1" title="' . $this->lang->line('button_player_daily_report')  . '"></i>';	
					}
					if(permission_validation(PERMISSION_BANK_PLAYER_USER_ADD) == TRUE)
					{
						$button .= '<i onclick="add_player_bank(' . $post->player_id . ')" class="fas fa-piggy-bank nav-icon text-purple fa-cust1" title="' . $this->lang->line('button_add_player_bank')  . '"></i>';	
					}
					if(permission_validation(PERMISSION_BANK_PLAYER_USER_VIEW) == TRUE)
					{
						$button .= '<i onclick="view_player_bank(' . $post->player_id . ')" class="fas fa-money-check-alt nav-icon text-purple fa-cust1" title="' . $this->lang->line('button_add_player_bank')  . '"></i>';	
					}
					if(permission_validation(PERMISSION_WHITELIST_ADD) == TRUE)
					{
						$button .= '<i onclick="add_whitelist(' ."'". $post->username ."'". ')" class="fas fa-user-shield nav-icon text-navy fa-cust1" title="' . $this->lang->line('button_add_whitelist')  . '"></i>';	
					}
					if(permission_validation(PERMISSION_KICK_PLAYER) == TRUE)
					{
						$button .= '<i onclick="kick_player(' . $post->player_id . ')" class="fas fa-user-slash nav-icon text-danger fa-cust1" title="' . $this->lang->line('button_kick_player')  . '"></i>';	
					}
					if(permission_validation(PERMISSION_PLAYER_UPDATE_ADDITIONAL_INFO) == TRUE)
					{
						$button .= '<i onclick="player_additional_info(' . $post->player_id . ')" class="fas fa-file-prescription nav-icon text-purple fa-cust1" title="' . $this->lang->line('button_additional_info')  . '"></i>';	
					}
					if(permission_validation(PERMISSION_PLAYER_UPDATE_ADDITIONAL_DETAIL_INFO) == TRUE)
					{
						$button .= '<i onclick="player_additional_detail_info(' . $post->player_id . ')" class="fas fa-file-signature nav-icon text-fuchsia fa-cust1" title="' . $this->lang->line('button_additional_detail_info')  . '"></i>';	
					}
					if(permission_validation(PERMISSION_PLAYER_PROMOTION_VIEW) == TRUE){
						$button .= '<i id="uc20_' . $post->player_id . '" onclick="promotionUnsattleData(' . $post->player_id . ')" class="fas fa-gifts nav-icon text-danger" title="' . $this->lang->line('button_promotion_unsattle')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_TAG_MODIFY) == TRUE){
						$button .= '<i id="uc20_' . $post->player_id . '" onclick="tagModify(' . $post->player_id . ')" class="fas fa-tag nav-icon text-indigo" title="' . $this->lang->line('button_tag')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_PLAYER_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateUpline(' . $post->player_id . ')" class="fas fa-user-edit nav-icon text-primary fa-cust1" title="' . $this->lang->line('button_change_upline')  . '"></i>';
					}
					if(permission_validation(PERMISSION_CHANGE_PASSWORD) == TRUE)
					{
						$button .= '<i onclick="changePasswordWithdrawal(' . $post->player_id . ')" class="fa fa-university fa-cust1" aria-hidden="true" title="' . $this->lang->line('button_change_password_bank')  . '"></i>';
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
		if(permission_validation(PERMISSION_PLAYER_ADD) == TRUE)
		{
			//$data['upline'] = $this->session->userdata('root_username');
			$data['player_group_list'] = $this->group_model->get_group_list(GROUP_PLAYER);
			$data['bank_group_list'] = $this->group_model->get_group_list(GROUP_BANK);
			$data['avatar_list'] = $this->avatar_model->get_avatar_list();
			$data['miscellaneous'] = $this->miscellaneous_model->get_miscellaneous();
			$game_list = $this->game_model->get_game_list();
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
			$this->load->view('player_add', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function submit()
	{
		if(permission_validation(PERMISSION_PLAYER_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
						    'possess_error' => '',
										'sport_comm_error' => '',
										'casino_comm_error' => '',
										'slots_comm_error' => '',
										'other_comm_error' => '',
										'white_list_ip_error' => '',
										'username_error' => '',
										'nickname_error' => '',
										'mobile_error' => '',
										'email_error' => '',
										'password_error' => '',
										'passconf_error' => '',
										'player_type_error' => '',
										'win_loss_suspend_limit_error' => '',
										'general_error' => ''
									), 	
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			//Set form rules
			$config = array(
							array(
									'field' => 'nickname',
									'label' => strtolower($this->lang->line('label_nickname')),
									//'rules' => 'trim|required|min_length[1]|max_length[32]|regex_match[/^[A-Za-z0-9]+$/]',
									'rules' => 'trim',
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
									'rules' => 'trim|integer|is_unique[players.mobile]',
									'errors' => array(
											'integer' => $this->lang->line('error_invalid_mobile'),
											'is_unique' => $this->lang->line('error_username_already_exits')
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
									'field' => 'profile_group_id',
									'label' => strtolower($this->lang->line('label_profile_group')),
									'rules' => 'trim'
							),
							array(
									'field' => 'player_type',
									'label' => strtolower($this->lang->line('label_type')),
									'rules' => 'trim|required',
									'errors' => array(
											'required' => $this->lang->line('error_select_player_type'),
									)
							),
							array(
									'field' => 'win_loss_suspend_limit',
									'label' => strtolower($this->lang->line('label_win_loss_suspend_limit')),
									'rules' => 'trim|required',
									'errors' => array(
											'required' => $this->lang->line('error_enter_win_loss_suspend_limit'),
									)
							)
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$upline = trim($this->input->post('upline', TRUE));
				$referrer = trim($this->input->post('referrer', TRUE));
				$response = $this->user_model->get_downline_data($upline);
				$sys_data = $this->miscellaneous_model->get_miscellaneous();
				if( ! empty($response) && !empty($sys_data))
				{
					$allow_to_add = TRUE;
					if(!empty($referrer)){
						$playerData = $this->player_model->get_player_data_by_username($referrer);
						if(!empty($playerData)){
							$responseUpline = $this->user_model->get_downline_data($playerData['upline']);
							if(empty($responseUpline)){
								$allow_to_add = FALSE;
								$json['msg'] = $this->lang->line('error_upline_not_found');	
							}
						}else{
							$allow_to_add = FALSE;
							$json['msg'] = $this->lang->line('error_referrer_not_found');
						}
					}
					if($allow_to_add == TRUE){
						if(strtolower(substr($this->input->post('username', TRUE), 0, 3)) != $sys_data['system_prefix']){
							//Database update
							$this->db->trans_start();
							$newData = $this->player_model->add_player($response);
							if($this->session->userdata('user_group') == USER_GROUP_USER)
							{
								$this->user_model->insert_log(LOG_PLAYER_ADD, $newData);
							}
							else
							{
								$this->account_model->insert_log(LOG_PLAYER_ADD, $newData);
							}
							$this->db->trans_complete();
							if ($this->db->trans_status() === TRUE)
							{
								if(TELEGRAM_STATUS == STATUS_ACTIVE){
									$telegram_param = array(
										'username' => $newData['username'],
										'created_by' => $newData['created_by'],
										'domain' => '',
										'name' => '',
										'line_id' => $newData['line_id'],
										'created_date' => $newData['created_date'],
									);
									send_register_telegram(TELEGRAM_REGISTER, $telegram_param, TELEGRAM_REGISTER_FUNCTION);
								}
								//Send System message
								$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_NEW_REGISTRATION);
								if(!empty($system_message_data)){
									$system_message_id = $system_message_data['system_message_id']; 
									$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
									$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
									$create_time = time();
									$username = $this->input->post('username', TRUE);
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
								if($newData['is_offline_deposit'] == "1"){
									$bank_data = $this->bank_model->get_bank_account_list_specific($newData['bank_group_id']);
									if(!empty($bank_data) && sizeof($bank_data)>0){
										$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_VIP_ACCOUNT_OPEN);
										if(!empty($system_message_data)){
											$system_message_id = $system_message_data['system_message_id']; 
											$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
											$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
											$username = $newData['username'];
											$array_key = array(
												'system_message_id' => $system_message_data['system_message_id'],
												'system_message_genre' => $system_message_data['system_message_genre'],
												'player_level' => "",
												'bank_channel' => "",
												'username' => $username,
											);
											$player_message_list = $this->message_model->get_player_all_data_by_message_genre($array_key);
											$create_time = time();
											if(!empty($player_message_list)){
												foreach($bank_data as $bank_data_row){
													$Bdatalang = array();
													$Bdata = array();
													$create_time++;
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
																			SYSTEM_MESSAGE_PLATFORM_VALUE_VIP_BANK_CODE => substr($bank_data_row['bank_name'],0,3),
																			SYSTEM_MESSAGE_PLATFORM_VALUE_VIP_BANK_ACCOUNT => $bank_data_row['bank_account_no'],
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
								$json['status'] = EXIT_SUCCESS;
								$json['msg'] = $this->lang->line('success_added');
							}
							else
							{
								$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');
							}
						}else{
							$json['msg'] = $this->lang->line('error_username_cannot_start_with_system_prefix');
						}
					}
				}
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_upline_not_found');
				}		
			}
			else 
			{
				$json['msg']['username_error'] = form_error('username');
				$json['msg']['nickname_error'] = form_error('nickname');
				$json['msg']['mobile_error'] = form_error('mobile');
				$json['msg']['email_error'] = form_error('email');
				$json['msg']['password_error'] = form_error('password');
				$json['msg']['passconf_error'] = form_error('passconf');
				$json['msg']['player_type_error'] = form_error('player_type');
				$json['msg']['win_loss_suspend_limit_error'] = form_error('win_loss_suspend_limit');
				if(!empty(form_error('player_type'))){
					$json['msg']['general_error'] = form_error('player_type');
				}
				if(!empty(form_error('win_loss_suspend_limit'))){
					$json['msg']['general_error'] = form_error('win_loss_suspend_limit');
				}
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
		if(permission_validation(PERMISSION_PLAYER_UPDATE) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$data['player_group_list'] = $this->group_model->get_group_list(GROUP_PLAYER);
					$data['bank_group_list'] = $this->group_model->get_group_list(GROUP_BANK);
					$data['bank_list'] = $this->bank_model->get_bank_list();
					$data['avatar_list'] = $this->avatar_model->get_avatar_list();
					$data['miscellaneous'] = $this->miscellaneous_model->get_miscellaneous();
					$game_list = $this->game_model->get_game_list();
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
					$this->load->view('player_update', $data);
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
	public function update()
	{
		if(permission_validation(PERMISSION_PLAYER_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'nickname_error' => '',
										'fullname_error' => '',
										'mobile_error' => '',
										'email_error' => '',
										'player_type_error' => '',
										'win_loss_suspend_limit_error' => '',
										'general_error' => ''
									),
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			//Set form rules
			$player_id = trim($this->input->post('player_id', TRUE));
			$oldData = $this->player_model->get_player_data($player_id);
			if(!empty($oldData))
		    {
    			$config = array(
    				array(
    						'field' => 'nickname',
    						'label' => strtolower($this->lang->line('label_nickname')),
    						//'rules' => 'trim|required|min_length[1]|max_length[32]|regex_match[/^[A-Za-z0-9]+$/]',
    						'rules' => 'trim',
    						'errors' => array(
    											'required' => $this->lang->line('error_enter_nickname'),
    											'min_length' => $this->lang->line('error_invalid_nickname'),
    											'max_length' => $this->lang->line('error_invalid_nickname'),
    											'regex_match' => $this->lang->line('error_invalid_nickname')
    									)
    				),
    				array(
    						'field' => 'fullname',
    						'label' => strtolower($this->lang->line('label_fullname')),
    						//'rules' => 'trim|required|min_length[1]|max_length[32]|regex_match[/^[A-Za-z0-9]+$/]',
    						'rules' => 'trim',
    						'errors' => array(
    											'required' => $this->lang->line('error_enter_fullname'),
    											'min_length' => $this->lang->line('error_invalid_fullname'),
    											'max_length' => $this->lang->line('error_invalid_fullname'),
    											'regex_match' => $this->lang->line('error_invalid_fullname')
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
    						'field' => 'profile_group_id',
    						'label' => strtolower($this->lang->line('label_profile_group')),
    						'rules' => 'trim'
    				),
    				array(
    						'field' => 'bank_account_name',
    						'label' => strtolower($this->lang->line('label_bank_account_name')),
    						'rules' => 'trim'
    				),
    				array(
    						'field' => 'bank_account_no',
    						'label' => strtolower($this->lang->line('label_bank_account_no')),
    						'rules' => 'trim'
    				),
    				array(
    						'field' => 'additional_info',
    						'label' => strtolower($this->lang->line('label_additional_info')),
    						'rules' => 'trim'
    				),
    				array(
    						'field' => 'player_type',
    						'label' => strtolower($this->lang->line('label_type')),
    						'rules' => 'trim|required',
    						'errors' => array(
    								'required' => $this->lang->line('error_select_player_type'),
    						)
    				),
    				array(
    						'field' => 'win_loss_suspend_limit',
    						'label' => strtolower($this->lang->line('label_win_loss_suspend_limit')),
    						'rules' => 'trim|required',
    						'errors' => array(
    								'required' => $this->lang->line('error_enter_win_loss_suspend_limit'),
    						)
    				)
    			);
    			if($this->input->post('mobile', TRUE) == $oldData['mobile']){
    			    $configAdd = array(
    						'field' => 'mobile',
    						'label' => strtolower($this->lang->line('label_mobile')),
    						'rules' => 'trim|integer',
    						'errors' => array(
									'integer' => $this->lang->line('error_invalid_mobile')
							)
    				);   
    			}else{
    			    $configAdd = array(
    						'field' => 'mobile',
    						'label' => strtolower($this->lang->line('label_mobile')),
    						'rules' => 'trim|integer|is_unique[players.mobile]',
    						'errors' => array(
									'integer' => $this->lang->line('error_invalid_mobile'),
									'is_unique' => $this->lang->line('error_username_already_exits')
							)
    				);
    			}
    			array_push($config, $configAdd);
    			$this->form_validation->set_rules($config);
    			$this->form_validation->set_error_delimiters('', '');
    			//Form validation
    			if ($this->form_validation->run() == TRUE)
    			{
    				$referrer = trim($this->input->post('referrer', TRUE));
					$response = $this->user_model->get_downline_data($oldData['upline']);
					if( ! empty($response))
					{
						$allow_to_update = TRUE;
						if(!empty($referrer)){
							$playerData = $this->player_model->get_player_data_by_username($referrer);
							if(!empty($playerData)){
								if($playerData['username'] == $oldData['username']){
									$allow_to_update = FALSE;
									$json['msg'] = $this->lang->line('error_referrer_cannot_same_as_username');
								}else{
									$responseUpline = $this->user_model->get_downline_data($playerData['upline']);
									if(empty($responseUpline)){
										$allow_to_update = FALSE;
										$json['msg'] = $this->lang->line('error_upline_not_found');	
									}
								}
							}else{
								$allow_to_update = FALSE;
								$json['msg'] = $this->lang->line('error_referrer_not_found');
							}
						}
						if($allow_to_update == TRUE){
							//Database update
							$this->db->trans_start();
							$newData = $this->player_model->update_player($oldData);
							if($this->session->userdata('user_group') == USER_GROUP_USER)
							{
								$this->user_model->insert_log(LOG_USER_UPDATE, $newData, $oldData);
							}
							else
							{
								$this->account_model->insert_log(LOG_USER_UPDATE, $newData, $oldData);
							}
							$this->db->trans_complete();
							if ($this->db->trans_status() === TRUE)
							{
								if($newData['is_offline_deposit'] == "0" && $oldData['is_offline_deposit'] == "1"){
									$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_VIP_ACCOUNT_CLOSE);
									if(!empty($system_message_data)){
										$system_message_id = $system_message_data['system_message_id']; 
										$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
										$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
										$create_time = time();
										$username = $oldData['username'];
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
								}
								if($newData['is_offline_deposit'] == "1" && $oldData['is_offline_deposit'] == "0"){
									$bank_data = $this->bank_model->get_bank_account_list_specific($newData['bank_group_id']);
									if(!empty($bank_data) && sizeof($bank_data)>0){
										$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_VIP_ACCOUNT_OPEN);
										if(!empty($system_message_data)){
											$system_message_id = $system_message_data['system_message_id']; 
											$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
											$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
											$username = $oldData['username'];
											$array_key = array(
												'system_message_id' => $system_message_data['system_message_id'],
												'system_message_genre' => $system_message_data['system_message_genre'],
												'player_level' => "",
												'bank_channel' => "",
												'username' => $username,
											);
											$player_message_list = $this->message_model->get_player_all_data_by_message_genre($array_key);
											$create_time = time();
											if(!empty($player_message_list)){
												foreach($bank_data as $bank_data_row){
													$Bdatalang = array();
													$Bdata = array();
													$create_time++;
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
																			SYSTEM_MESSAGE_PLATFORM_VALUE_VIP_BANK_CODE => substr($bank_data_row['bank_name'],0,3),
																			SYSTEM_MESSAGE_PLATFORM_VALUE_VIP_BANK_ACCOUNT => $bank_data_row['bank_account_no'],
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
								$json['status'] = EXIT_SUCCESS;
								$json['msg'] = $this->lang->line('success_updated');
								$bank_group_list = $this->group_model->get_group_list(GROUP_BANK);
								$bank_channel = "";
								if(permission_validation(PERMISSION_PLAYER_UPDATE) == TRUE)
								{
									if($newData['is_offline_deposit']){
										$bank_channel .= '<small class="badge badge-success" onclick="updateData(' . $newData['player_id'] . ')">'.$this->lang->line('deposit_offline_banking').'</small>';
									}
									if($newData['is_online_deposit']){
										$bank_channel .= '<small class="badge badge-warning" onclick="updateData(' . $newData['player_id'] . ')">'.$this->lang->line('deposit_online_banking').'</small>';
									}
									if($newData['is_credit_card_deposit']){
										$bank_channel .= '<small class="badge badge-primary" onclick="updateData(' . $newData['player_id'] . ')">'.$this->lang->line('deposit_credit_card').'</small>';
									}
									if($newData['is_hypermart_deposit']){
										$bank_channel .= '<small class="badge badge-danger" onclick="updateData(' . $newData['player_id'] . ')">'.$this->lang->line('deposit_hypermart').'</small>';
									}
								}else{
									if($newData['is_offline_deposit']){
										$bank_channel .= '<small class="badge badge-success">'.$this->lang->line('deposit_offline_banking').'</small>';
									}
									if($newData['is_online_deposit']){
										$bank_channel .= '<small class="badge badge-warning">'.$this->lang->line('deposit_online_banking').'</small>';
									}
									if($newData['is_credit_card_deposit']){
										$bank_channel .= '<small class="badge badge-primary">'.$this->lang->line('deposit_credit_card').'</small>';
									}
									if($newData['is_hypermart_deposit']){
										$bank_channel .= '<small class="badge badge-danger">'.$this->lang->line('deposit_hypermart').'</small>';
									}
								}
								$bank_show_name = "";
								for($i=0;$i<sizeof($bank_group_list);$i++)
								{
									if(isset($bank_group_list)) 
									{
										$arr = explode(',',  $newData['bank_group_id']);
										$arr = array_values(array_filter($arr));
										if(in_array($bank_group_list[$i]['group_id'], $arr)) 
										{
											if(empty($bank_show_name)){
												$bank_show_name .= '<small class="badge badge-primary">'.$bank_group_list[$i]['group_name'].'</small>';
											}else{
												$bank_show_name .= " , ".'<small class="badge badge-primary">'.$bank_group_list[$i]['group_name'].'</small>';
											}
										}
									}
								}
								//Prepare for ajax update
								$json['response'] = array(
									'id' => $newData['player_id'],
									'nickname' => $newData['nickname'],
									'fullname' => $newData['fullname'],
									'active' => (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_suspend')),
									'player_type' => $this->lang->line(get_player_type($newData['player_type'])),
									'win_loss_suspend_limit' => $newData['win_loss_suspend_limit'],
									'active_code' => $newData['active'],
									'bank_channel' => $bank_channel,
									'bank_show_name' => $bank_show_name,
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
    				$json['msg']['nickname_error'] = form_error('nickname');
    				$json['msg']['fullname_error'] = form_error('fullname');
    				$json['msg']['mobile_error'] = form_error('mobile');
    				$json['msg']['email_error'] = form_error('email');
    				$json['msg']['player_type_error'] = form_error('player_type');
    				$json['msg']['win_loss_suspend_limit_error'] = form_error('win_loss_suspend_limit');
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
	public function view($id = NULL)
    {
		if(permission_validation(PERMISSION_VIEW_PLAYER_CONTACT) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$data['player_group_list'] = $this->group_model->get_group_list(GROUP_PLAYER);
					$data['bank_group_list'] = $this->group_model->get_group_name(GROUP_BANK);
					$data['bank_list'] = $this->bank_model->get_bank_list();
					$data['miscellaneous'] = $this->miscellaneous_model->get_miscellaneous();
					$data['total_deposit'] = $this->player_model->getTotalDepositByPlayer($id);
					$data['total_withdraw'] = $this->player_model->getTotalWithdrawalByPlayer($id);
					$data['total_bonus'] = $this->player_model->getTotalBonusByPlayer($id);
					$data['total_transfer_in'] = $this->player_model->getTotalTransferInByPlayer($id);
					$data['total_transfer_out'] = $this->player_model->getTotalTransferOutByPlayer($id);
					$data['total_adjustment_in'] = $this->player_model->getTotalAdjustInByPlayer($id);
					$data['total_adjustment_out'] = $this->player_model->getTotalAdjustOutByPlayer($id);
					$data['total_win_loss'] = $this->player_model->getTotalWinLossByPlayer($id);
					$this->load->view('player_details', $data);
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
	public function view_version2($id = NULL)
    {
		if(permission_validation(PERMISSION_VIEW_PLAYER_CONTACT_V2) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$data['player_group_list'] = $this->group_model->get_group_list(GROUP_PLAYER);
					$data['bank_group_list'] = $this->group_model->get_group_list(GROUP_BANK);
					$data['bank_list'] = $this->bank_model->get_bank_list();
					$data['miscellaneous'] = $this->miscellaneous_model->get_miscellaneous();
					$this->load->view('player_details_v2', $data);
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
	public function update_detail_version_two(){
		if(permission_validation(PERMISSION_VIEW_PLAYER_CONTACT_V2) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'nickname_error' => '',
										'mobile_error' => '',
										'email_error' => '',
										'player_type_error' => '',
										'win_loss_suspend_limit_error' => '',
										'general_error' => ''
									),
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			$player_id = trim($this->input->post('player_id', TRUE));
			$oldData = $this->player_model->get_player_data($player_id);
			$this->db->trans_start();
			$newData = $this->player_model->update_player_version_two($oldData);
			if(!empty($oldData))
		    {
				if($this->session->userdata('user_group') == USER_GROUP_USER)
				{
					$this->user_model->insert_log(LOG_USER_UPDATE, $newData, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_USER_UPDATE, $newData, $oldData);
				}
				$this->db->trans_complete();
				if ($this->db->trans_status() === TRUE)
				{
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('success_updated');
					//Prepare for ajax update
					$json['response'] = array(
						'id' => $newData['player_id'],
						'nickname' => $newData['nickname'],
						'active' => (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_suspend')),
						'player_type' => $this->lang->line(get_player_type($newData['player_type'])),
						'win_loss_suspend_limit' => $newData['win_loss_suspend_limit'],
						'active_code' => $newData['active'],
					);
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
	public function password($id = NULL)
    {
		if(permission_validation(PERMISSION_CHANGE_PASSWORD) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$this->load->view('player_password', $data);
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
	public function passwordWithdrawal($id = NULL)
    {
		if(permission_validation(PERMISSION_CHANGE_PASSWORD) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$this->load->view('player_password_bank', $data);
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
				$player_id = trim($this->input->post('player_id', TRUE));
				$oldData = $this->player_model->get_player_data($player_id);
				if( ! empty($oldData))
				{
					$response = $this->user_model->get_downline_data($oldData['upline']);
					if( ! empty($response))
					{
						//Database update
						$this->db->trans_start();
						$newData = $this->player_model->update_player_password($oldData);
						if($this->session->userdata('user_group') == USER_GROUP_USER)
						{
							$this->user_model->insert_log(LOG_PLAYER_PASSWORD, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_PLAYER_PASSWORD, $newData, $oldData);
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
	public function password_bank_update()
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
									'label' => strtolower($this->lang->line('label_password_bank')),
									'rules' => 'trim|required',
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
				$player_id = trim($this->input->post('player_id', TRUE));
				$oldData = $this->player_model->get_player_data($player_id);
				if( ! empty($oldData))
				{
					$response = $this->user_model->get_downline_data($oldData['upline']);
					if( ! empty($response))
					{
						//Database update
						$this->db->trans_start();
						$newData = $this->player_model->updatePasswordBank($oldData);
						if($this->session->userdata('user_group') == USER_GROUP_USER)
						{
							$this->user_model->insert_log(LOG_PLAYER_PASSWORD, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_PLAYER_PASSWORD, $newData, $oldData);
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
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data)) {
			    if($this->session->userdata('user_group') == USER_GROUP_USER)
			    {
			        $response = $this->user_model->get_downline_data($this->session->userdata('username'));
			    }
				else
				{
				    if($this->session->userdata('user_type') == 1) {
    					$response = $this->user_model->get_downline_data($this->session->userdata('username'));
    				}
    				else {
    					$response = $this->user_model->get_downline_data($this->session->userdata('upline'));					
    				}
				}
				
				if( ! empty($response)) {
					$data['upline_data'] = $response;
					$this->load->view('player_deposit', $data);
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
			$player_id = trim($this->input->post('player_id', TRUE));
			$oldData = $this->player_model->get_player_data($player_id);
			if( ! empty($oldData)) {
				if($this->session->userdata('user_group') == USER_GROUP_USER)
			    {
			        $response = $this->user_model->get_downline_data($this->session->userdata('username'));
			    }
				else
				{
				    if($this->session->userdata('user_type') == 1) {
    					$response = $this->user_model->get_downline_data($this->session->userdata('username'));
    				}
    				else {
    					$response = $this->user_model->get_downline_data($this->session->userdata('upline'));					
    				}
				}
				
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
						$newData = $this->player_model->point_transfer($oldData, $points);
						$newData2 = $this->user_model->point_transfer($response, ($points * -1));
						$this->general_model->insert_point_transfer_report($response, $oldData);
						$this->general_model->insert_cash_transfer_report($oldData, $points, TRANSFER_POINT_IN);
						$this->user_model->insert_log(LOG_PLAYER_DEPOSIT_POINT, $newData, $oldData);
						$RnewData = $this->player_model->update_player_turnover($oldData, $points, (($oldData['turnover_total_required'] == 0) ? STATUS_ACTIVE: STATUS_INACTIVE), (($oldData['turnover_total_required'] == 0) ? STATUS_ACTIVE: STATUS_INACTIVE));
						if($this->session->userdata('user_group') == USER_GROUP_USER)
						{
							$this->user_model->insert_log(LOG_USER_WITHDRAW_POINT, $newData2, $response);
						}
						else
						{
							$this->account_model->insert_log(LOG_USER_WITHDRAW_POINT, $newData2, $response);
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_deposit_points');
							//Prepare for ajax update
							$json['response'] = array(
													'id' => $oldData['player_id'],
													'points' => number_format(($oldData['points'] + $points), 2, '.', ''),
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
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{				
				if($this->session->userdata('user_group') == USER_GROUP_USER)
			    {
			        $response = $this->user_model->get_downline_data($this->session->userdata('username'));
			    }
				else
				{
				    if($this->session->userdata('user_type') == 1) {
    					$response = $this->user_model->get_downline_data($this->session->userdata('username'));
    				}
    				else {
    					$response = $this->user_model->get_downline_data($this->session->userdata('upline'));					
    				}
				}
				
				if( ! empty($response))
				{
					$this->load->view('player_withdraw', $data);
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
			$player_id = trim($this->input->post('player_id', TRUE));
			$oldData = $this->player_model->get_player_data($player_id);			
			if( ! empty($oldData))
			{				
			    if($this->session->userdata('user_group') == USER_GROUP_USER)
			    {
			        $response = $this->user_model->get_downline_data($this->session->userdata('username'));
			    }
				else
				{
				    if($this->session->userdata('user_type') == 1) {
    					$response = $this->user_model->get_downline_data($this->session->userdata('username'));
    				}
    				else {
    					$response = $this->user_model->get_downline_data($this->session->userdata('upline'));					
    				}
				}
				
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
						$newData = $this->player_model->point_transfer($oldData, ($points * -1));
						$newData2 = $this->user_model->point_transfer($response, $points);
						$this->general_model->insert_point_transfer_report($oldData, $response);
						$this->general_model->insert_cash_transfer_report($oldData, $points, TRANSFER_POINT_OUT);
						$this->user_model->insert_log(LOG_PLAYER_WITHDRAW_POINT, $newData, $oldData);
						if($this->session->userdata('user_group') == USER_GROUP_USER)
						{
							$this->user_model->insert_log(LOG_USER_DEPOSIT_POINT, $newData2, $response);
						}
						else
						{
							$this->account_model->insert_log(LOG_USER_DEPOSIT_POINT, $newData2, $response);
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_withdraw_points');
							//Prepare for ajax update
							$json['response'] = array(
								'id' => $oldData['player_id'],
								'points' => number_format(($oldData['points'] - $points), 2, '.', ''),
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
	public function adjust($id = NULL)
    {
		if(permission_validation(PERMISSION_PLAYER_POINT_ADJUSTMENT) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$this->load->view('player_adjust', $data);
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
	public function adjust_submit()
	{
		if(permission_validation(PERMISSION_PLAYER_POINT_ADJUSTMENT) == TRUE)
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
			//Set form rules
			$config = array(
							array(
									'field' => 'points',
									'label' => strtolower($this->lang->line('label_points')),
									'rules' => 'trim|required',
									'errors' => array(
														'required' => $this->lang->line('error_only_digits_allowed'),
														//'integer' => $this->lang->line('error_only_digits_allowed')
												)
							)
						);		
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$player_id = trim($this->input->post('player_id', TRUE));
				$oldData = $this->player_model->get_player_data($player_id);
				if( ! empty($oldData))
				{
				    if($oldData['wallet_lock'] == WALLET_UNLOCK){
						$this->player_model->update_player_wallet_lock($oldData['player_id'],WALLET_LOCK);
    					$response = $this->user_model->get_downline_data($oldData['upline']);
    					if( ! empty($response))
    					{
    						$points = $this->input->post('points', TRUE);
    						if($points>0){
    							if($this->input->post('type', TRUE) == TRANSFER_ADJUST_OUT){
    								$points = $points * -1;	
    							}
    							$adjust_type = (($points < 0) ? TRANSFER_ADJUST_OUT : TRANSFER_ADJUST_IN);
    							$final_points = ($oldData['points'] + $points);
    							if($final_points >= 0)
    							{
    								//Database update
    								$this->db->trans_start();
    								if($adjust_type == TRANSFER_ADJUST_IN){
    									$pending_promotion_data = $this->player_promotion_model->get_unsattle_promotion($oldData);
    									if(!empty($pending_promotion_data)){
    									    $player_pending_bet = $this->player_model->player_pending_bet_amount($oldData['player_id']);
                							if(empty($player_pending_bet)){
    	    									$player_current_wallet = $this->get_member_total_wallet($oldData['player_id']);
    	    									$game_balance_check = ((isset($player_current_wallet['game_balance'])) ? $player_current_wallet['game_balance'] : 0);
    											$main_balance_check = ((isset($player_current_wallet['main_wallet_balance'])) ? $player_current_wallet['main_wallet_balance'] : 0);
    											$balance_check = $game_balance_check + $main_balance_check;
    											if($balance_check < PLAYER_PROMOTION_SURRENDER){
    												$remark = "主錢包 : ".number_format($main_balance_check, 0, '.', ',')."<br/>遊戲錢包：".number_format($game_balance_check, 0, '.', ',');
    												$this->promotion_approve_model->force_bulk_cancel_player_promotion($oldData['player_id'],$remark);
    											}
                							}
										}
    								}
    								$newData = $this->player_model->point_transfer($oldData, $points);
    								if($adjust_type == TRANSFER_ADJUST_IN){
    									$RnewData = $this->player_model->update_player_turnover($oldData, $points, (($oldData['turnover_total_required'] == 0) ? STATUS_ACTIVE: STATUS_INACTIVE), (($oldData['turnover_total_required'] == 0) ? STATUS_ACTIVE: STATUS_INACTIVE));
    								}
    								$adjust_points = (($points < 0) ? ($points * -1) : $points);
    								$this->general_model->insert_cash_transfer_report($oldData, $adjust_points, $adjust_type);
    								if($this->session->userdata('user_group') == USER_GROUP_USER)
    								{
    									$this->user_model->insert_log(LOG_PLAYER_POINT_ADJUSTMENT, $newData, $oldData);
    								}
    								else
    								{
    									$this->account_model->insert_log(LOG_PLAYER_POINT_ADJUSTMENT, $newData, $oldData);
    								}
    								$this->db->trans_complete();
    								if ($this->db->trans_status() === TRUE)
    								{
    									$json['status'] = EXIT_SUCCESS;
    									$json['msg'] = $this->lang->line('success_adjust_points');
    									if($adjust_type == TRANSFER_ADJUST_IN){
    										$turnover_total_current = $oldData['turnover_total_current'] + $points;
    										$turnover_total_required = $oldData['turnover_total_required'] + $points;
    									}else{
    										$turnover_total_current = $oldData['turnover_total_current'];
    										$turnover_total_required = $oldData['turnover_total_required'];
    									}
    									//Prepare for ajax update
    									$json['response'] = array(
    										'id' => $oldData['player_id'],
    										'points' => number_format(($oldData['points'] + $points), 2, '.', ''),
    										'turnover_total_current' => number_format(($turnover_total_current), 0, '.', ','),
    										'turnover_total_required' => number_format(($turnover_total_required), 0, '.', ','),
    									);
    									if(TELEGRAM_STATUS == STATUS_ACTIVE){
    										send_amount_telegram(TELEGRAM_MONEY_FLOW,$newData['username'],$newData['updated_by'],$adjust_points,$adjust_type);
    									}
    								}
    								else
    								{
    									$json['msg']['general_error'] = $this->lang->line('error_failed_to_adjust');
    								}
    							}
    							else
    							{
    								$json['msg']['general_error'] = $this->lang->line('error_invalid_points');
    							}	
    						}else{
    							$json['msg']['general_error'] = $this->lang->line('error_failed_to_adjust');
    						}
    					}
    					else
    					{
    						$json['msg']['general_error'] = $this->lang->line('error_failed_to_adjust');
    					}
    					$this->player_model->update_player_wallet_lock($oldData['player_id'],WALLET_UNLOCK);
				    }else{
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_adjust');
					}
				}
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_adjust');
				}			
			}
			else 
			{
				$json['msg']['points_error'] = form_error('points');
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
	public function wallet($id = NULL)
    {
		if(permission_validation(PERMISSION_VIEW_PLAYER_WALLET) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$data['game_list'] = $this->game_model->get_game_list();
					$this->load->view('player_wallets', $data);
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
	public function wallet_enhance($id = NULL){
		if(permission_validation(PERMISSION_VIEW_PLAYER_WALLET) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$data['game_provider'] = $this->game_model->get_all_player_game_account($id);
					$this->load->view('player_wallets_enhance', $data);
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
	public function wallet_transfer($player_id = NULL, $game_code = NULL){
		if(permission_validation(PERMISSION_PLAYER_GAME_TRANSFER) == TRUE)
		{
			$data = $this->player_model->get_player_data($player_id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$url = SYSTEM_API_URL;
					$param_array = array(
						"method" => 'GetBalance',
						"agent_id" => SYSTEM_API_AGENT_ID,
						"syslang" => LANG_EN,
						"device" => PLATFORM_WEB,
						"provider_code" => $game_code,
					);
					//Get balance
					$balance = 0;
					$account_data = $this->player_model->get_player_game_account_data($game_code, $data['player_id']);
					if( ! empty($account_data))
					{
						$param_array['player_id'] = $account_data['player_id'];
						$param_array['game_id'] = $account_data['game_id'];
						$param_array['username'] = $account_data['username'];
						$param_array['password'] = $account_data['password'];
						$param_array['signature'] = md5(SYSTEM_API_AGENT_ID . $game_code . $param_array['username'] . SYSTEM_API_SECRET_KEY);
						$response = $this->curl_json($url, $param_array);
						$result_array = json_decode($response, TRUE);
						if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
						{
							$balance = ($balance + $result_array['result']);
						}
					}
					$data['transfer_provider_code'] = $game_code;
					$data['transfer_provider_balance'] = $balance;
					$this->load->view('player_wallet_transfer', $data);
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
	public function transfer_submit(){
		if(permission_validation(PERMISSION_PLAYER_GAME_TRANSFER) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR,
				'msg' => array(
					'general_error' => ''
				),
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash(),
				'balance' => '0.00',
				'game_balance' => '0.00',
			);
			//Set form rules
			$player_id = trim($this->input->post('player_id', TRUE));
			$game_code = trim($this->input->post('transfer_provider_code', TRUE));
			$type = trim($this->input->post('type', TRUE));
			$points = bcdiv(trim($this->input->post('points', TRUE)),1,2);
			$player_data = $this->player_model->get_player_data($player_id);
			if(!empty($player_data))
		    {
		        if($player_data['wallet_lock'] == WALLET_UNLOCK){
		            $this->player_model->update_player_wallet_lock($oldData['player_id'],WALLET_LOCK);
    		    	$upline_data = $this->user_model->get_downline_data($player_data['upline']);
    				if( ! empty($upline_data))
    				{
    					if($points > 0){
    						$sys_data = $this->miscellaneous_model->get_miscellaneous();
    						$url = SYSTEM_API_URL; 
    						$account_data = $this->player_model->get_player_game_account_data($game_code, $player_data['player_id']);
    				    	if(!empty($account_data))
    				    	{
    				    		if($type == TRANSFER_TRANSACTION_IN){
    				    			//Game wallet to main wallet	
    				    			$param_array = array(
    									"method" => 'GetBalance',
    									"agent_id" => SYSTEM_API_AGENT_ID,
    									"syslang" => LANG_EN,
    									"device" => PLATFORM_WEB,
    									"provider_code" => $game_code,
    								);
    				    			$balance = 0;
    								$param_array['player_id'] = $account_data['player_id'];
    								$param_array['game_id'] = $account_data['game_id'];
    								$param_array['username'] = $account_data['username'];
    								$param_array['password'] = $account_data['password'];
    								$param_array['signature'] = md5(SYSTEM_API_AGENT_ID . $game_code . $param_array['username'] . SYSTEM_API_SECRET_KEY);
    								$response = $this->curl_json($url, $param_array);
    								$result_array = json_decode($response, TRUE);
    								if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
    								{
    									$balance = ($balance + $result_array['result']);
    								}
    								//Logout game
    								$param_array['method'] = 'LogoutGame';
    								$this->curl_json($url, $param_array);
    								if($balance >= $points)
    								{
    									//Withdraw credit
    									$param_array['method'] = 'ChangeBalance';
    									$param_array['order_id'] = 'OUT' . date("YmdHis") . $account_data['username'];
    									$param_array['amount'] = ($points * -1);
    									$response = $this->curl_json($url, $param_array);
    									$result_array = json_decode($response, TRUE);
    									$array = array(
    										'created_date' => time(),
    										'from' => $game_code,
    										'to' => "MAIN",
    										'errorCode' => (isset($result_array['errorCode']) ? $result_array['errorCode'] : "88888888"),
    									);
    									$this->general_model->insert_cash_transfer_report($player_data, $points, TRANSFER_TRANSACTION_IN,$array);
    									if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
    									{
    										//update wallet
    										$newData = $this->player_model->point_transfer($player_data, $points, $player_data['username']);
    										$this->general_model->insert_game_transfer_report($game_code, 'MAIN', $balance, $player_data['points'], $points, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
    										$json['status'] = EXIT_SUCCESS;
    										$json['balance'] = bcdiv($player_data['points'] + $points, 1, 2);
    										$json['game_balance'] = bcdiv($balance - $points, 1, 2);
    										$json['msg']['general_error'] = $this->lang->line('sucess_to_transfers');
    									}else{
    										if(isset($result_array['errorCode']) && $result_array['errorCode'] == '201'){
    											$newData = $this->general_model->insert_game_transfer_pending_report($game_code, 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $points, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
    										}else if(isset($result_array['errorCode'])){
    										}else{
    										    $newData = $this->general_model->insert_game_transfer_pending_report($game_code, 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $points, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
    										}
    										$json['msg']['general_error'] = $this->lang->line('error_failed_to_transfers');
    									}
    								}else{
    									$json['msg']['general_error'] = $this->lang->line('error_insufficient_points');
    								}
    				    		}else if($type == TRANSFER_TRANSACTION_OUT){
    				    			//Main wallet to game wallet
    				    			$param_array = array(
    									"method" => 'GetBalance',
    									"agent_id" => SYSTEM_API_AGENT_ID,
    									"syslang" => LANG_EN,
    									"device" => PLATFORM_WEB,
    									"provider_code" => $game_code,
    								);
    				    			$balance = 0;
    								$param_array['player_id'] = $account_data['player_id'];
    								$param_array['game_id'] = $account_data['game_id'];
    								$param_array['username'] = $account_data['username'];
    								$param_array['password'] = $account_data['password'];
    								$param_array['signature'] = md5(SYSTEM_API_AGENT_ID . $game_code . $param_array['username'] . SYSTEM_API_SECRET_KEY);
    								$response = $this->curl_json($url, $param_array);
    								$result_array = json_decode($response, TRUE);
    								if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
    								{
    									$balance = ($balance + $result_array['result']);
    								}
    								//Logout game
    								$param_array['method'] = 'LogoutGame';
    								$this->curl_json($url, $param_array);
    				    			if($player_data['points'] >= $points)
    								{
    									//Deposit credit
    									$param_array['method'] = 'ChangeBalance';
    									$param_array['order_id'] = 'IN' . date("YmdHis") . $param_array['username'];
    									$param_array['amount'] = $points;
    									$param_array['signature'] = md5(SYSTEM_API_AGENT_ID . $game_code . $param_array['username'] . SYSTEM_API_SECRET_KEY);
    									$response = $this->curl_json($url, $param_array);
    									$result_array = json_decode($response, TRUE);
    									$newData = $this->player_model->point_transfer($player_data, ($points * -1), $player_data['username']);
    									$array = array(
    										'created_date' => time(),
    										'from' => "MAIN",
    										'to' => $game_code,
    										'errorCode' => (isset($result_array['errorCode']) ? $result_array['errorCode'] : "88888888"),
    									);
    									$this->general_model->insert_cash_transfer_report($player_data, $points, TRANSFER_TRANSACTION_OUT,$array);
    									if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
    									{
    										$json['status'] = EXIT_SUCCESS;
    										$json['balance'] = bcdiv($player_data['points'] - $points, 1, 2);
    										$json['game_balance'] = bcdiv($balance + $points, 1, 2);
    										$json['msg']['general_error'] = $this->lang->line('sucess_to_transfers');
    										//update wallet
    										$this->general_model->insert_game_transfer_report('MAIN', $game_code, $player_data['points'], $balance, $points, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
    									}else{
    										if(isset($result_array['errorCode']) && $result_array['errorCode'] == '201'){
    											//Overtime
    											$newData_3 = $this->general_model->insert_game_transfer_pending_report('MAIN', $game_code, TRANSFER_TRANSACTION_OUT, $player_data['points'], $balance, $points, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
    										}else if(isset($result_array['errorCode'])){
    											$newData_3 = $this->player_model->point_transfer($player_data, $points, $player_data['username']);
    											$this->general_model->insert_game_transfer_report($provider_code, 'MAIN', $player_data['points'], $balance, $points, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
    										}else{
    											$newData_3 = $this->general_model->insert_game_transfer_pending_report('MAIN', $game_code, TRANSFER_TRANSACTION_OUT, $player_data['points'], 0, $points, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
    											$this->general_model->insert_api_game_api_unnormal_log($provider_code,$player_data['player_id'],TRANSFER_TRANSACTION_OUT,$param_array,$result_array,$response);
    										}
    										$json['msg']['general_error'] = $this->lang->line('error_failed_to_transfers');
    									}
    								}else{
    									$json['msg']['general_error'] = $this->lang->line('error_insufficient_points');
    								}
    				    		}
    				    	}else{
    				    		$json['msg']['general_error'] = $this->lang->line('error_failed_to_transfers');
    				    	}
    					}else{
    						$json['msg']['general_error'] = $this->lang->line('error_failed_to_transfers');
    					}
    			    }else{
    			    	$json['msg']['general_error'] = $this->lang->line('error_failed_to_transfers');
    			    }
    			    $this->player_model->update_player_wallet_lock($oldData['player_id'],WALLET_UNLOCK);
		        }
    		    else
    			{
    				$json['msg']['general_error'] = $this->lang->line('error_failed_to_transfers');
    			}
		    }
		    else
			{
				$json['msg']['general_error'] = $this->lang->line('error_failed_to_transfers');
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
	public function wallet_balance($id = NULL, $game_code = NULL)
	{
		$json = array(
			'status' => EXIT_ERROR, 
			'balance' => '0.00',
		);
		if(permission_validation(PERMISSION_VIEW_PLAYER_WALLET) == TRUE)
		{
			$player_data = $this->player_model->get_player_data($id);
			if( ! empty($player_data))
			{
				$upline_data = $this->user_model->get_downline_data($player_data['upline']);
				if( ! empty($upline_data))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$url = SYSTEM_API_URL; 
					$param_array = array(
						"method" => 'GetBalance',
						"agent_id" => SYSTEM_API_AGENT_ID,
						"syslang" => LANG_EN,
						"device" => PLATFORM_WEB,
						"provider_code" => $game_code,
					);
					//Get balance
					$balance = 0;
					$account_data = $this->player_model->get_player_game_account_data($game_code, $player_data['player_id']);
					if( ! empty($account_data))
					{
						$param_array['player_id'] = $account_data['player_id'];
						$param_array['game_id'] = $account_data['game_id'];
						$param_array['username'] = $account_data['username'];
						$param_array['password'] = $account_data['password'];
						$param_array['signature'] = md5(SYSTEM_API_AGENT_ID . $game_code . $param_array['username'] . SYSTEM_API_SECRET_KEY);
						$response = $this->curl_json($url, $param_array);
						$result_array = json_decode($response, TRUE);
						if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
						{
							$balance = ($balance + $result_array['result']);
						}
					}
					$json['status'] = EXIT_SUCCESS;
					$json['balance'] = bcdiv($balance, 1, 2);
				}
			}
		}
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		exit();
	}
	public function push_balance($id = NULL, $game_code = NULL){
		//Initial output data
		$device = PLATFORM_WEB;
		$json = array(
			'status' => EXIT_ERROR, 
			'balance' => '0.00',
			'game_balance' => '0.00',	
		);
		if(permission_validation(PERMISSION_VIEW_PLAYER_WALLET) == TRUE)
		{
			$player_data = $this->player_model->get_player_data($id);
			if( ! empty($player_data))
			{
				$upline_data = $this->user_model->get_downline_data($player_data['upline']);
				if( ! empty($upline_data))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$url = SYSTEM_API_URL; 
					$account_data = $this->player_model->get_player_game_account_data($game_code, $player_data['player_id']);
					$param_array = array(
						"method" => 'ChangeBalance',
						"agent_id" => SYSTEM_API_AGENT_ID,
						"syslang" => LANG_EN,
						"device" => PLATFORM_WEB,
						"provider_code" => $game_code,
					);
					if( ! empty($account_data))
					{
						if($player_data['points'] > 0)
						{
							$json['balance'] = number_format($player_data['points'], 2, '.', '');
							//Deposit credit
							$param_array['player_id'] = $account_data['player_id'];
						    $param_array['game_id'] = $account_data['game_id'];
							$param_array['username'] = $account_data['username'];
							$param_array['password'] = $account_data['password'];
							$param_array['order_id'] = 'IN' . date("YmdHis") . $param_array['username'];
							$param_array['amount'] = $player_data['points'];
							$param_array['signature'] = md5(SYSTEM_API_AGENT_ID . $game_code . $param_array['username'] . SYSTEM_API_SECRET_KEY);
							$response = $this->curl_json($url, $param_array);
							$result_array = json_decode($response, TRUE);
							$newData = $this->player_model->point_transfer($player_data, ($player_data['points'] * -1), $player_data['username']);
							$array = array(
								'created_date' => time(),
								'from' => "MAIN",
								'to' => $game_code,
								'errorCode' => (isset($result_array['errorCode']) ? $result_array['errorCode'] : "88888888"),
							);
							$this->general_model->insert_cash_transfer_report($player_data, $balance, TRANSFER_TRANSACTION_OUT,$array);
							if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
							{
								$json['status'] = EXIT_SUCCESS;
								$json['balance'] = "0.00";
								$json['game_balance'] = number_format($player_data['points'], 2, '.', '');
								//update wallet
								$this->general_model->insert_game_transfer_report('MAIN', $game_code, $player_data['points'], 0, $player_data['points'], $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
							}else{
								if(isset($result_array['errorCode']) && $result_array['errorCode'] == '201'){
									//Overtime
									$newData_3 = $this->general_model->insert_game_transfer_pending_report('MAIN', $game_code, TRANSFER_TRANSACTION_OUT, $player_data['points'], 0, $player_data['points'], $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
								}else if(isset($result_array['errorCode'])){
									$newData_3 = $this->player_model->point_transfer($player_data, $player_data['points'], $player_data['username']);
									$this->general_model->insert_game_transfer_report($provider_code, 'MAIN', $player_data['points'], 0, $player_data['points'], $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
								}else{
									$newData_3 = $this->general_model->insert_game_transfer_pending_report('MAIN', $game_code, TRANSFER_TRANSACTION_OUT, $player_data['points'], 0, $player_data['points'], $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
									$this->general_model->insert_api_game_api_unnormal_log($provider_code,$player_data['player_id'],TRANSFER_TRANSACTION_OUT,$param_array,$result_array,$response);
								}
							}
						}
					}
				}
			}
		}
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		exit();
	}
	public function pull_balance($id = NULL, $game_code = NULL){
		//Initial output data
		$device = PLATFORM_WEB;
		$json = array(
					'status' => EXIT_ERROR, 
					'balance' => '0.00',
				);
		if(permission_validation(PERMISSION_VIEW_PLAYER_WALLET) == TRUE)
		{
			$player_data = $this->player_model->get_player_data($id);
			if( ! empty($player_data))
			{
				$upline_data = $this->user_model->get_downline_data($player_data['upline']);
				if( ! empty($upline_data))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$url = SYSTEM_API_URL; 
					$param_array = array(
						"method" => 'GetBalance',
						"agent_id" => SYSTEM_API_AGENT_ID,
						"syslang" => LANG_EN,
						"device" => PLATFORM_WEB,
						"provider_code" => $game_code,
					);
					//Get balance
					$balance = 0;
					$account_data = $this->player_model->get_player_game_account_data($game_code, $player_data['player_id']);
					if( ! empty($account_data))
					{
						$param_array['player_id'] = $account_data['player_id'];
						$param_array['game_id'] = $account_data['game_id'];
						$param_array['username'] = $account_data['username'];
						$param_array['password'] = $account_data['password'];
						$param_array['signature'] = md5(SYSTEM_API_AGENT_ID . $game_code . $param_array['username'] . SYSTEM_API_SECRET_KEY);
						$response = $this->curl_json($url, $param_array);
						$result_array = json_decode($response, TRUE);
						if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
						{
							$balance = ($balance + $result_array['result']);
						}
					}
					//Logout game
					$param_array['method'] = 'LogoutGame';
					$this->curl_json($url, $param_array);
					if($balance > 0)
					{
						//Withdraw credit
						$param_array['method'] = 'ChangeBalance';
						$param_array['order_id'] = 'OUT' . date("YmdHis") . $account_data['username'];
						$param_array['amount'] = ($balance * -1);
						$response = $this->curl_json($url, $param_array);
						$result_array = json_decode($response, TRUE);
						$array = array(
							'created_date' => time(),
							'from' => $game_code,
							'to' => "MAIN",
							'errorCode' => (isset($result_array['errorCode']) ? $result_array['errorCode'] : "88888888"),
						);
						$this->general_model->insert_cash_transfer_report($player_data, $balance, TRANSFER_TRANSACTION_IN,$array);
						if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
						{
							//update wallet
							$newData = $this->player_model->point_transfer($player_data, $balance, $player_data['username']);
							$this->general_model->insert_game_transfer_report($game_code, 'MAIN', $balance, $player_data['points'], $balance, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
							$json['status'] = EXIT_SUCCESS;
							$json['balance'] = bcdiv($balance, 1, 2);
						}else if(isset($result_array['errorCode']) && $result_array['errorCode'] == '201'){
							$newData = $this->general_model->insert_game_transfer_pending_report($game_code, 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $balance, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
						}else if(isset($result_array['errorCode'])){
						}else{
						    $newData = $this->general_model->insert_game_transfer_pending_report($game_code, 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $balance, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
						}
					}else{
						$json['status'] = EXIT_SUCCESS;
						$json['balance'] = bcdiv($balance, 1, 2);
					}
				}
			}
		}
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		exit();
	}
	public function wallet_all_balance($id = NULL){
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR, 
			'msg' => $this->lang->line('error_system_error'),
			'main_wallet' => '0.00',
			'total' => '0.00',
		);
		if(permission_validation(PERMISSION_VIEW_PLAYER_WALLET) == TRUE)
		{
			$player_data = $this->player_model->get_player_data($id);
			if( ! empty($player_data))
			{
				$upline_data = $this->user_model->get_downline_data($player_data['upline']);
				if( ! empty($upline_data))
				{
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('error_success');
					$main_wallet = $player_data['points'];
					$total_amount = $player_data['points'];
					$game_list = $this->game_model->get_game_list();
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$url = SYSTEM_API_URL; 
					if(!empty($game_list)){
						foreach($game_list as $game_list_row){
							$json[strtolower($game_list_row['game_code']).'_wallet'] = '0.00';
							$game_code = $game_list_row['game_code'];
							$param_array = array(
								"method" => 'GetBalance',
								"agent_id" => SYSTEM_API_AGENT_ID,
								"syslang" => LANG_EN,
								"device" => PLATFORM_WEB,
								"provider_code" => $game_code,
							);
							//Get balance
							$balance = 0;
							$account_data = $this->player_model->get_player_game_account_data($game_code, $player_data['player_id']);
							if( ! empty($account_data))
							{
								$param_array['player_id'] = $account_data['player_id'];
								$param_array['game_id'] = $account_data['game_id'];
								$param_array['username'] = $account_data['username'];
								$param_array['password'] = $account_data['password'];
								$param_array['signature'] = md5(SYSTEM_API_AGENT_ID . $game_code . $param_array['username'] . SYSTEM_API_SECRET_KEY);
								$response = $this->curl_json($url, $param_array);
								$result_array = json_decode($response, TRUE);
								if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
								{
									$balance = ($balance + $result_array['result']);
									$json[strtolower($game_list_row['game_code']).'_wallet'] = bcdiv($balance, 1, 2);
									$total_amount = ($total_amount + $balance);
								}
							}
						}
					}
					$json['total'] = bcdiv($total_amount, 1, 2);
					$json['main_wallet'] = bcdiv($main_wallet, 1, 2);
				}
			}
		}
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		exit();
	}
	public function pull_all_balance($id = NULL){
		$json = array(
			'status' => EXIT_ERROR, 
			'msg' => $this->lang->line('error_system_error'),
			'main_wallet' => '0.00',
			'total' => '0.00',
		);
		if(permission_validation(PERMISSION_VIEW_PLAYER_WALLET) == TRUE)
		{
			$player_data = $this->player_model->get_player_data($id);
			if( ! empty($player_data))
			{
				$upline_data = $this->user_model->get_downline_data($player_data['upline']);
				if( ! empty($upline_data))
				{
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('error_success');
					$main_wallet = $player_data['points'];
					$total_amount = $player_data['points'];
					$game_list = $this->game_model->get_game_list();
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$url = SYSTEM_API_URL; 
					if(!empty($game_list)){
						foreach($game_list as $game_list_row){
							$json[strtolower($game_list_row['game_code']).'_wallet'] = '0.00';
							$game_code = $game_list_row['game_code'];
							$param_array = array(
								"method" => 'GetBalance',
								"agent_id" => SYSTEM_API_AGENT_ID,
								"syslang" => LANG_EN,
								"device" => PLATFORM_WEB,
								"provider_code" => $game_code,
							);
							//Get balance
							$balance = 0;
							$account_data = $this->player_model->get_player_game_account_data($game_code, $player_data['player_id']);
							if( ! empty($account_data))
							{
								$param_array['player_id'] = $account_data['player_id'];
								$param_array['game_id'] = $account_data['game_id'];
								$param_array['username'] = $account_data['username'];
								$param_array['password'] = $account_data['password'];
								$param_array['signature'] = md5(SYSTEM_API_AGENT_ID . $game_code . $param_array['username'] . SYSTEM_API_SECRET_KEY);
								$response = $this->curl_json($url, $param_array);
								$result_array = json_decode($response, TRUE);
								if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
								{
									$balance = ($balance + $result_array['result']);
									$json[strtolower($game_list_row['game_code']).'_wallet'] = bcdiv($balance, 1, 2);
									$total_amount = ($total_amount + $balance);
								}
							}
							//Logout game
							$param_array['method'] = 'LogoutGame';
							$this->curl_json($url, $param_array);
							if($balance > 0){
								//Withdraw credit
								$param_array['method'] = 'ChangeBalance';
								$param_array['order_id'] = 'OUT' . date("YmdHis") . $account_data['username'];
								$param_array['amount'] = ($balance * -1);
								$response = $this->curl_json($url, $param_array);
								$result_array = json_decode($response, TRUE);
								$array = array(
									'created_date' => time(),
									'from' => $game_code,
									'to' => "MAIN",
									'errorCode' => (isset($result_array['errorCode']) ? $result_array['errorCode'] : "88888888"),
								);
								$this->general_model->insert_cash_transfer_report($player_data, $balance, TRANSFER_TRANSACTION_IN,$array);
								if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
								{
									//update wallet
									$newData = $this->player_model->point_transfer($player_data, $balance, $player_data['username']);
									$this->general_model->insert_game_transfer_report($game_code, 'MAIN', $balance, $player_data['points'], $balance, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
									$main_wallet = ($main_wallet + $balance);
									$json[strtolower($game_list_row['game_code']).'_wallet'] = bcdiv(0, 1, 2);
								}else if(isset($result_array['errorCode']) && $result_array['errorCode'] == '201'){
									//Overtime
									$newData = $this->general_model->insert_game_transfer_pending_report($game_code, 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $balance, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
								}else if(isset($result_array['errorCode'])){
								}else{
								    $newData = $this->general_model->insert_game_transfer_pending_report($game_code, 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $balance, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
								}
							}
						}
						$json['total'] = bcdiv($total_amount, 1, 2);
						$json['main_wallet'] = bcdiv($main_wallet, 1, 2);
					}
				}
			}
		}
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		exit();
	}
	public function player_daily_report($id = NULL){
		if(permission_validation(PERMISSION_PLAYER_DAILY_REPORT) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$this->load->view('player_daily_report', $data);
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
	public function get_member_total_wallet($id){
		$is_balance_valid = TRUE;
		$total_amount = 0;
		$game_balance = 0;
		$main_wallet_balance = 0;
		$player_data = $this->player_model->get_player_data($id);
		if( ! empty($player_data))
		{
			$game_balance = 0;
			$total_amount = $player_data['points'];
			$main_wallet_balance = $player_data['points'];
			$upline_data = $this->user_model->get_downline_data($player_data['upline']);
			if( ! empty($upline_data))
			{
				if( ! empty($player_data['last_in_game']))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$url = SYSTEM_API_URL;
					$account_data_list = $this->player_model->get_player_game_account_data_list($player_data['player_id']);
					if( ! empty($account_data_list))
					{
						foreach($account_data_list as $account_data){
							$signature = md5(SYSTEM_API_AGENT_ID . $account_data['game_provider_code'] . $account_data['username'] . SYSTEM_API_SECRET_KEY);
							$param_array = array(
								"method" => 'GetBalance',
								"agent_id" => SYSTEM_API_AGENT_ID,
								"syslang" => LANG_EN,
								"device" => PLATFORM_WEB,
								"provider_code" => $account_data['game_provider_code'],
								"username" => $account_data['username'],
								"password" => $account_data['password'],
								"game_id" => $account_data['game_id'],
								"player_id" => $account_data['player_id'],
								"signature" => $signature,
							);
							$response = $this->curl_json($url, $param_array);
							$result_array = json_decode($response, TRUE);
							if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
							{
								$total_amount = ($total_amount + $result_array['result']);
								$game_balance = ($game_balance + $result_array['result']);
							}else{
								$is_balance_valid = FALSE;
							}
						}
					}
				}else{
					$total_amount = $player_data['points'];
				}
			}else{
				$is_balance_valid = FALSE;
			}
		}else{
			$is_balance_valid = FALSE;
		}
		$result = array(
			'balance_valid' => $is_balance_valid,
			'balance_amount' => $total_amount,
			'game_balance' => $game_balance,
			'main_wallet_balance' => $main_wallet_balance,
		);
		return $result;
	}
	public function kickInfo($id = NULL)
    {
		if(permission_validation(PERMISSION_KICK_PLAYER) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				$this->load->view('kick_player_info', $data);
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
	public function kick($id = NULL)
    {
		if(permission_validation(PERMISSION_KICK_PLAYER) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'remark_error' => '',
					'general_error' => ''
				), 	
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			$config = array(
    				
				array(
					'field' => 'remark',
					'label' => strtolower($this->lang->line('label_remark')),
					'rules' => 'trim|required',
					'errors' => array(
										'required' => $this->lang->line('error_invalid_remark'),
								)
				)
			);
			$this->form_validation->set_rules($config);
    		$this->form_validation->set_error_delimiters('', '');

			$player_id = trim($this->input->post('player_id', TRUE));
			$oldData = $this->player_model->get_player_data($player_id);
			
			if( ! empty($oldData))
			{
				//Form validation
				if ($this->form_validation->run() == TRUE)
    			{
					$response = $this->user_model->get_downline_data($oldData['upline']);
					if( ! empty($response))
					{
						$this->db->trans_start();
						$newData = $this->player_model->clear_login_token($oldData);
						$this->db->trans_complete();
						$sys_data = $this->miscellaneous_model->get_miscellaneous();
						$url = SYSTEM_API_URL;
						$account_data = $this->player_model->get_player_game_account_data($oldData['last_in_game'], $oldData['player_id']);
						if(!empty($account_data)){
							$signature = md5(SYSTEM_API_AGENT_ID . $account_data['game_provider_code'] . $account_data['username'] . SYSTEM_API_SECRET_KEY);
							$param_array = array(
								"method" => 'LogoutGame',
								"agent_id" => SYSTEM_API_AGENT_ID,
								"syslang" => LANG_EN,
								"device" => PLATFORM_WEB,
								"provider_code" => $oldData['last_in_game'],
								"game_id" => $account_data['game_id'],
								"player_id" => $account_data['player_id'],
								"username" => $account_data['username'],
								"password" => $account_data['password'],
								"signature" => $signature,
							);
							$param_array['method'] = 'LogoutGame';
							$this->curl_json($url, $param_array);
						}
						if ($this->db->trans_status() === TRUE)
						{
							$updateRemark = $this->player_model->updateRemark($oldData);
							
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_kick_player');
							//Prepare for ajax update
							$json['response'] = array(
								'id' => $updateRemark['player_id'],
								'remark' => $updateRemark['remark'],
								
							);
						}
						else
						{
							$json['msg']['general_error'] = $this->lang->line('error_failed_to_kick_player');
						}
					}
					else
					{
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_kick_player');
					}
				}
    			else 
    			{
    				$json['msg']['remark_error'] = form_error('remark');
					$json['msg']['general_error'] = form_error('remark');
    			}	
			}
			else
			{
				$json['msg']['general_error'] = $this->lang->line('error_failed_to_kick_player');
			}
		}
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		exit();
	}
	public function kick_game($id = NULL, $game_code = NULL)
    {
		if(permission_validation(PERMISSION_KICK_PLAYER) == TRUE)
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
			$player_id = $id;
			$oldData = $this->player_model->get_player_data($player_id);
			if( ! empty($oldData))
			{
				$response = $this->user_model->get_downline_data($oldData['upline']);
				if( ! empty($response))
				{
					$this->db->trans_start();
					$newData = $this->player_model->clear_login_token($oldData);
					$this->db->trans_complete();
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$url = SYSTEM_API_URL;
					$account_data = $this->player_model->get_player_game_account_data($game_code, $oldData['player_id']);
					if(!empty($account_data)){
						$signature = md5(SYSTEM_API_AGENT_ID . $account_data['game_provider_code'] . $account_data['username'] . SYSTEM_API_SECRET_KEY);
						$param_array = array(
							"method" => 'LogoutGame',
							"agent_id" => SYSTEM_API_AGENT_ID,
							"syslang" => LANG_EN,
							"device" => PLATFORM_WEB,
							"provider_code" => $game_code,
							"game_id" => $account_data['game_id'],
							"player_id" => $account_data['player_id'],
							"username" => $account_data['username'],
							"password" => $account_data['password'],
							"signature" => $signature,
						);
						$param_array['method'] = 'LogoutGame';
						$this->curl_json($url, $param_array);
					}
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_kick_player');
					}
					else
					{
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_kick_player');
					}
				}
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_kick_player');
				}	
			}
			else
			{
				$json['msg']['general_error'] = $this->lang->line('error_failed_to_kick_player');
			}
		}
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		exit();
	}
	public function add_player_bank($id = NULL)
    {
		if(permission_validation(PERMISSION_BANK_PLAYER_USER_ADD) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$data['bank_list'] = $this->bank_model->get_bank_list();
					$this->load->view('player_bank_add', $data);
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
										'username_error' => '',
										'player_bank_type_error' => '',
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
						'rules' => 'trim|required',
						'errors' => array(
											'required' => $this->lang->line('error_enter_bank_account_name')
									)
				),
				array(
						'field' => 'bank_account_no',
						'label' => strtolower($this->lang->line('label_bank_account_no')),
						'rules' => 'trim|required|integer',
						'errors' => array(
											'required' => $this->lang->line('error_enter_bank_account_no'),
											'integer' => $this->lang->line('error_only_digits_allowed')
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
					$player_bank_max = $this->bank_model->get_player_bank_account_quantity($playerData,$player_bank_type);
					if($player_bank_type == BANKS_PLAYER_USER_IMAGE_BANK_TYPE_BANK){
						$player_account_max = $miscellaneous['player_bank_account_max'];
					}else{
						$player_account_max = $miscellaneous['player_credit_card_max'];
					}
					if($player_bank_max < $player_account_max){
						//Database update
						$this->db->trans_start();
						$newData = $this->bank_model->add_player_bank_account($playerData);
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
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_added');
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
	public function change_player_mark($player_id = NULL, $mark_id = NULL)
	{
		if(permission_validation(PERMISSION_PLAYER_UPDATE) == TRUE)
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
			if($player_id == NULL || $mark_id == NULL){
				$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
			}else{
				$player_id = trim($player_id);
				$oldData = $this->player_model->get_player_data($player_id);
				if( ! empty($oldData))
				{
					$response = $this->user_model->get_downline_data($oldData['upline']);
					if( ! empty($response))
					{
						$this->db->trans_start();
						$newData = $this->player_model->update_player_mark($oldData,$mark_id);
						if($this->session->userdata('user_group') == USER_GROUP_USER)
						{
							$this->user_model->insert_log(LOG_USER_UPDATE, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_USER_UPDATE, $newData, $oldData);
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');
							//Prepare for ajax update
							$json['response'] = array(
								'mark' => '<li ><i class="fas fa-square text-'.get_mark_type($mark_id).'"></i></li>',
							);
						}
						else
						{
							$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
						}
					}else{
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
					}
				}else
				{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
				}	
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
	public function additional_info($id = NULL)
	{
		if(permission_validation(PERMISSION_PLAYER_UPDATE_ADDITIONAL_INFO) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$this->load->view('player_additional_info', $data);
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
	public function update_additional_info()
	{
		if(permission_validation(PERMISSION_PLAYER_UPDATE_ADDITIONAL_INFO) == TRUE)
		{
			//Initial output data
			$json = array(
						'status' => EXIT_ERROR, 
						'msg' => array(
										'nickname_error' => '',
										'mobile_error' => '',
										'email_error' => '',
										'player_type_error' => '',
										'win_loss_suspend_limit_error' => '',
										'general_error' => ''
									),
						'csrfTokenName' => $this->security->get_csrf_token_name(), 
						'csrfHash' => $this->security->get_csrf_hash()
					);
			//Set form rules
			$player_id = trim($this->input->post('player_id', TRUE));
			$oldData = $this->player_model->get_player_data($player_id);
			if(!empty($oldData))
		    {
		    	$this->db->trans_start();
				$newData = $this->player_model->update_player_additional_info($oldData);
				if($this->session->userdata('user_group') == USER_GROUP_USER)
				{
					$this->user_model->insert_log(LOG_PLAYER_UPDATE_ADDITIONAL_INFO, $newData, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_PLAYER_UPDATE_ADDITIONAL_INFO, $newData, $oldData);
				}
				$this->db->trans_complete();
				if ($this->db->trans_status() === TRUE)
				{
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('success_updated');
					//Prepare for ajax update
					$json['response'] = array(
						'id' => $newData['player_id'],
						'nickname' => $newData['nickname'],
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
	public function view_player_bank($id = NULL){
		if(permission_validation(PERMISSION_BANK_PLAYER_USER_VIEW) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$this->session->unset_userdata('searches_player_bank');
					$data_search = array(
						'from_date' => '',
						'to_date' => '',
						'username' => $data['username'],
						'status' => "-1",
						'bank_name' => "",
						'bank_account_name' => '',
						'bank_account_no' => '',
					);
					$this->session->set_userdata('searches_player_bank', $data_search);
					$this->load->view('player_bank_view', $data);
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
	public function wallet_game_balance($id = NULL)
	{
		$json = array(
			'status' => EXIT_ERROR, 
			'balance' => '0.00',
		);
		if(permission_validation(PERMISSION_VIEW_PLAYER_WALLET) == TRUE)
		{
			$player_data = $this->player_model->get_player_data($id);
			if( ! empty($player_data))
			{
				$upline_data = $this->user_model->get_downline_data($player_data['upline']);
				if( ! empty($upline_data))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$url = SYSTEM_API_URL; 
					$param_array = array(
						"method" => 'GetBalance',
						"agent_id" => SYSTEM_API_AGENT_ID,
						"syslang" => LANG_EN,
						"device" => PLATFORM_WEB,
					);
					//Get balance
					$balance = 0;
					$account_data = $this->player_model->get_player_game_account_data_list($player_data['player_id']);
					if( ! empty($account_data))
					{
						foreach($account_data as $account_data_row){
							$param_array['player_id'] = $account_data_row['player_id'];
							$param_array['game_id'] = $account_data_row['game_id'];
							$param_array['username'] = $account_data_row['username'];
							$param_array['password'] = $account_data_row['password'];
							$param_array['provider_code'] = $account_data_row['game_provider_code'];
							$param_array['signature'] = md5(SYSTEM_API_AGENT_ID . $account_data_row['game_provider_code'] . $param_array['username'] . SYSTEM_API_SECRET_KEY);
							$response = $this->curl_json($url, $param_array);
							$result_array = json_decode($response, TRUE);
							if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
							{
								$balance = ($balance + $result_array['result']);
							}
						}
					}
					$json['status'] = EXIT_SUCCESS;
					$json['balance'] = bcdiv($balance, 1, 2);
				}
			}
		}
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		exit();
	}
	public function player_pending_withdrawal($username = NULL){
		$dbprefix = $this->db->dbprefix;
		$where = "";
		$json = array(
			'status' => EXIT_ERROR, 
			'msg' => '',
			'total_data' => '',
			'csrfTokenName' => $this->security->get_csrf_token_name(), 
			'csrfHash' => $this->security->get_csrf_hash()
		);
		$json['total_data'] = array(
			'pending_withdrawal' => 0,
		);
    	$pending_withdrawal = 0;
		$pending_withdrawal_data = $this->withdrawal_model->player_total_withdrawal_pending($username);
		if(!empty($pending_withdrawal_data)){
			$json['status'] = EXIT_SUCCESS;
			$json['total_data']['pending_withdrawal'] = $pending_withdrawal_data['total'];
		}
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		exit();	
    }
    public function player_pending_bet($player_id = NULL){
		$dbprefix = $this->db->dbprefix;
		$where = "";
		$json = array(
			'status' => EXIT_ERROR, 
			'msg' => '',
			'total_data' => '',
			'csrfTokenName' => $this->security->get_csrf_token_name(), 
			'csrfHash' => $this->security->get_csrf_hash()
		);
		$json['total_data'] = array(
			'pending_bet_amount' => 0,
		);
    	$pending_bet_amount = 0;
		$pending_bet_data = $this->player_model->player_total_pending_bet_amount($player_id);
		if(!empty($pending_bet_data)){
			$json['status'] = EXIT_SUCCESS;
			$json['total_data']['pending_bet_amount'] = $pending_bet_data['total'];
		}
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
		exit();	
    }
    public function turnover_adjust($id){
		if(permission_validation(PERMISSION_ADJUST_PLAYER_TURNOVER) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$this->load->view('player_turnover_adjust', $data);
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
	public function turnover_adjust_submit(){
		if(permission_validation(PERMISSION_ADJUST_PLAYER_TURNOVER) == TRUE)
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
			//Set form rules
			$config = array(
				array(
					'field' => 'turnover',
					'label' => strtolower($this->lang->line('label_turnover')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_only_digits_allowed'),
					)
				)
			);
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$player_id = trim($this->input->post('player_id', TRUE));
				$oldData = $this->player_model->get_player_data($player_id);
				if( ! empty($oldData))
				{
					$response = $this->user_model->get_downline_data($oldData['upline']);
					if( ! empty($response))
					{
						$turnover = $this->input->post('turnover', TRUE);
						if($turnover>0){
							if($this->input->post('type', TRUE) == TRANSFER_ADJUST_OUT){
								$turnover = $turnover * -1;	
							}
							$final_turnover = ($oldData['turnover_total_current'] + $turnover);
							if($final_turnover >= 0)
							{
								//Database update
								$this->db->trans_start();
								$newData = $this->player_model->turnover_adjust($oldData, $turnover);
								if($this->session->userdata('user_group') == USER_GROUP_USER)
								{
									$this->user_model->insert_log(LOG_PLAYER_TURNOVER_ADJUSTMENT, $newData, $oldData);
								}
								else
								{
									$this->account_model->insert_log(LOG_PLAYER_TURNOVER_ADJUSTMENT, $newData, $oldData);
								}
								$this->db->trans_complete();
								if ($this->db->trans_status() === TRUE)
								{
									$json['status'] = EXIT_SUCCESS;
									$json['msg'] = $this->lang->line('success_adjust_turnover');
									//Prepare for ajax update
									$json['response'] = array(
										'id' => $oldData['player_id'],
										'turnover_total_current' => number_format(($oldData['turnover_total_current'] + $turnover), 0,'.',','),
										'turnover_total_required' => number_format(($oldData['turnover_total_required']), 0,'.',','),
									);
								}
								else
								{
									$json['msg']['general_error'] = $this->lang->line('error_failed_to_adjust');
								}
							}
							else
							{
								$json['msg']['general_error'] = $this->lang->line('error_invalid_turnover');
							}	
						}
						else
						{
							$json['msg']['general_error'] = $this->lang->line('error_failed_to_adjust');
						}
					}
					else
					{
						$json['msg']['general_error'] = $this->lang->line('error_failed_to_adjust');
					}
				}
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_adjust');
				}			
			}
			else 
			{
				$json['msg']['points_error'] = form_error('points');
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
	public function agent(){
		if(permission_validation(PERMISSION_PLAYER_AGENT_VIEW) == TRUE)
		{
			$this->save_current_url('player/agent');
			$this->session->unset_userdata('search_players_agent');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_player_agent');
			$data_search = array( 
				'from_date' => date('Y-m-d 00:00:00'),
				'to_date' => date('Y-m-d 23:59:59'),
				'username' => '',
				'upline' => '',
				'agent' => '',
				'status' => '',
				'referrer' => '',
			);
			$this->session->set_userdata('search_players_agent', $data_search);
			$this->load->view('player_agent_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function agent_search()
	{
		if(permission_validation(PERMISSION_PLAYER_AGENT_VIEW) == TRUE)
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
					'upline' => trim($this->input->post('upline', TRUE)),
					'agent' => trim($this->input->post('agent', TRUE)),
					'status' => trim($this->input->post('status', TRUE)),
					'referrer' => trim($this->input->post('referrer', TRUE)),
				);
				$this->session->set_userdata('search_players_agent', $data);
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
	public function player_agent_listing(){
		if(permission_validation(PERMISSION_PLAYER_AGENT_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			$columns = array( 
				0 => 'a.player_id',
				1 => 'a.username',
				2 => 'a.upline',
				3 => 'a.points',
				4 => 'a.active',
				5 => 'a.referrer',
				6 => 'a.created_date',
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
			$arr = $this->session->userdata('search_players_agent');				
			if( ! empty($arr['agent']))
			{
				$where = "WHERE player_id = 'ABC'";
				$agent = $this->user_model->get_user_data_by_username($arr['agent']);
				if(!empty($agent)){
					$response_upline = $this->user_model->get_downline_data($agent['username']);
					if(!empty($response_upline)){
						$where = "WHERE a.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";
					}
				}
			}else{
				$where = "WHERE a.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";
			}
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
			if( ! empty($arr['upline']))
			{
				$where .= " AND a.upline LIKE '%" . $arr['upline'] . "%' ESCAPE '!'";	
			}
			if( ! empty($arr['username']))
			{
				$where .= " AND a.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	
			}
			if( ! empty($arr['referrer']))
			{
				$where .= ' AND a.referrer = "' . $arr['referrer'].'"';
			}
			if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_SUSPEND)
			{
				$where .= ' AND a.active = ' . $arr['status'];
			}
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;
			$posts = NULL;
			$query_string = "SELECT {$select} FROM {$dbprefix}players a $where";
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
					$register_info = "";
					$row = array();
					$row[] = $post->player_id;
					$row[] = $post->username;
					$row[] = $post->upline;
					$row[] = $post->points;
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc3_' . $post->player_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->player_id . '">' . $this->lang->line('status_suspend') . '</span>'; break;
					}
					$row[] = $post->referrer;
					$register_info .= (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date)."<br>" : '');
					$row[] = $register_info;
					$button = '';
					if(permission_validation(PERMISSION_PLAYER_AGENT_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->player_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_CHANGE_PASSWORD) == TRUE)
					{
						$button .= '<i onclick="changePassword(' . $post->player_id . ')" class="fas fa-key nav-icon text-secondary" title="' . $this->lang->line('button_change_password')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_TRANSACTION_REPORT) == TRUE)
					{
						$button .= '<i onclick="bet_record('."'".$post->username."'" . ')" class="fas fa-file-alt nav-icon text-maroon" title="' . $this->lang->line('title_transaction_report')  . '"></i> &nbsp;&nbsp; ';	
					}
					if(permission_validation(PERMISSION_WIN_LOSS_REPORT_PLAYER) == TRUE)
					{
						$button .= '<i onclick="win_loss_player('."'".$post->username."'" . ')" class="fas fa-file-invoice-dollar nav-icon text-info" title="' . $this->lang->line('title_win_loss_report_player')  . '"></i> &nbsp;&nbsp; ';	
					}
					if(permission_validation(PERMISSION_LOGIN_REPORT) == TRUE)
					{
						$button .= '<i onclick="login_report('."'".$post->username."'" . ')" class="fas fa-clipboard-check nav-icon text-warning " title="' . $this->lang->line('title_login_report')  . '"></i> &nbsp;&nbsp; ';	
					}
					if(permission_validation(PERMISSION_DEPOSIT_POINT_TO_DOWNLINE) == TRUE)
					{
						$button .= '<i onclick="depositPoints(' . $post->player_id . ')" class="fas fa-arrow-up nav-icon text-olive" title="' . $this->lang->line('button_deposit_points')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE) == TRUE)
					{
						$button .= '<i onclick="withdrawPoints(' . $post->player_id . ')" class="fas fa-arrow-down nav-icon text-maroon" title="' . $this->lang->line('button_withdraw_points')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_DEPOSIT_POINT_TO_DOWNLINE) == TRUE || permission_validation(PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE) == TRUE || permission_validation(PERMISSION_TRANSACTION_REPORT) == TRUE || permission_validation(PERMISSION_WIN_LOSS_REPORT_PLAYER) == TRUE || permission_validation(PERMISSION_LOGIN_REPORT) == TRUE)
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
	public function agent_add(){
		if(permission_validation(PERMISSION_PLAYER_AGENT_ADD) == TRUE)
		{
			//$data['upline'] = $this->session->userdata('root_username');
			$data['miscellaneous'] = $this->miscellaneous_model->get_miscellaneous();
			$this->load->view('player_agent_add', $data);
		}
		else
		{
			redirect('home');
		}
	}
	public function agent_submit(){
		if(permission_validation(PERMISSION_PLAYER_AGENT_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'username_error' => '',
					'nickname_error' => '',
					'mobile_error' => '',
					'email_error' => '',
					'password_error' => '',
					'passconf_error' => '',
					'player_type_error' => '',
					'win_loss_suspend_limit_error' => '',
					'general_error' => ''
				), 	
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			//Set form rules
			$config = array(
				array(
						'field' => 'mobile',
						'label' => strtolower($this->lang->line('label_mobile')),
						'rules' => 'trim|integer|is_unique[players.mobile]',
						'errors' => array(
								'integer' => $this->lang->line('error_invalid_mobile'),
								'is_unique' => $this->lang->line('error_username_already_exits')
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
			);
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$upline = trim($this->input->post('upline', TRUE));
				$referrer = trim($this->input->post('referrer', TRUE));
				$response = $this->user_model->get_downline_data($upline);
				$sys_data = $this->miscellaneous_model->get_miscellaneous();
				if( ! empty($response) && !empty($sys_data))
				{
					$allow_to_add = TRUE;
					if(!empty($referrer)){
						$playerData = $this->player_model->get_player_data_by_username($referrer);
						if(!empty($playerData)){
							$responseUpline = $this->user_model->get_downline_data($playerData['upline']);
							if(empty($responseUpline)){
								$allow_to_add = FALSE;
								$json['msg'] = $this->lang->line('error_upline_not_found');	
							}
						}else{
							$allow_to_add = FALSE;
							$json['msg'] = $this->lang->line('error_referrer_not_found');
						}
					}
					if($allow_to_add == TRUE){
						if(strtolower(substr($this->input->post('username', TRUE), 0, 3)) != $sys_data['system_prefix']){
							//Database update
							$this->db->trans_start();
							$newData = $this->player_model->add_player_agent($response);
							if($this->session->userdata('user_group') == USER_GROUP_USER)
							{
								$this->user_model->insert_log(LOG_PLAYER_ADD, $newData);
							}
							else
							{
								$this->account_model->insert_log(LOG_PLAYER_ADD, $newData);
							}
							$this->db->trans_complete();
							if ($this->db->trans_status() === TRUE)
							{
								if(TELEGRAM_STATUS == STATUS_ACTIVE){
									$telegram_param = array(
										'username' => $newData['username'],
										'created_by' => $newData['created_by'],
										'domain' => '',
										'name' => '',
										'line_id' => $newData['line_id'],
										'created_date' => $newData['created_date'],
									);
									send_register_telegram(TELEGRAM_REGISTER, $telegram_param, TELEGRAM_REGISTER_FUNCTION);
								}
								//Send System message
								$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_NEW_REGISTRATION);
								if(!empty($system_message_data)){
									$system_message_id = $system_message_data['system_message_id']; 
									$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
									$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
									$create_time = time();
									$username = $this->input->post('username', TRUE);
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
								$json['status'] = EXIT_SUCCESS;
								$json['msg'] = $this->lang->line('success_added');
							}
							else
							{
								$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');
							}
						}else{
							$json['msg'] = $this->lang->line('error_username_cannot_start_with_system_prefix');
						}
					}
				}
				else
				{
					$json['msg']['general_error'] = $this->lang->line('error_upline_not_found');
				}		
			}
			else 
			{
				$json['msg']['username_error'] = form_error('username');
				$json['msg']['mobile_error'] = form_error('mobile');
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
	public function agent_edit($id = NULL)
    {
		if(permission_validation(PERMISSION_PLAYER_AGENT_UPDATE) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$data['miscellaneous'] = $this->miscellaneous_model->get_miscellaneous();
					$this->load->view('player_agent_update', $data);
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
	public function agent_update(){
		if(permission_validation(PERMISSION_PLAYER_AGENT_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'nickname_error' => '',
					'mobile_error' => '',
					'email_error' => '',
					'player_type_error' => '',
					'win_loss_suspend_limit_error' => '',
					'general_error' => ''
				),
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			//Set form rules
			$player_id = trim($this->input->post('player_id', TRUE));
			$oldData = $this->player_model->get_player_data($player_id);
			if(!empty($oldData))
		    {
    			$config = array(
    				array(
    						'field' => 'player_id',
    						'label' => strtolower($this->lang->line('label_username')),
    						'rules' => 'trim|required',
    						'errors' => array(
								'required' => $this->lang->line('error_enter_username'),
							)
    				),
    			);
    			if($this->input->post('mobile', TRUE) == $oldData['mobile']){
    			    $configAdd = array(
    						'field' => 'mobile',
    						'label' => strtolower($this->lang->line('label_mobile')),
    						'rules' => 'trim|integer',
    						'errors' => array(
									'integer' => $this->lang->line('error_invalid_mobile')
							)
    				);   
    			}else{
    			    $configAdd = array(
    						'field' => 'mobile',
    						'label' => strtolower($this->lang->line('label_mobile')),
    						'rules' => 'trim|integer|is_unique[players.mobile]',
    						'errors' => array(
									'integer' => $this->lang->line('error_invalid_mobile'),
									'is_unique' => $this->lang->line('error_username_already_exits')
							)
    				);
    			}
    			array_push($config, $configAdd);
    			$this->form_validation->set_rules($config);
    			$this->form_validation->set_error_delimiters('', '');
    			//Form validation
    			if ($this->form_validation->run() == TRUE)
    			{
    				$referrer = trim($this->input->post('referrer', TRUE));
					$response = $this->user_model->get_downline_data($oldData['upline']);
					if( ! empty($response))
					{
						$allow_to_update = TRUE;
						if(!empty($referrer)){
							$playerData = $this->player_model->get_player_data_by_username($referrer);
							if(!empty($playerData)){
								if($playerData['username'] == $oldData['username']){
									$allow_to_update = FALSE;
									$json['msg'] = $this->lang->line('error_referrer_cannot_same_as_username');
								}else{
									$responseUpline = $this->user_model->get_downline_data($playerData['upline']);
									if(empty($responseUpline)){
										$allow_to_update = FALSE;
										$json['msg'] = $this->lang->line('error_upline_not_found');	
									}
								}
							}else{
								$allow_to_update = FALSE;
								$json['msg'] = $this->lang->line('error_referrer_not_found');
							}
						}
						if($allow_to_update == TRUE){
							//Database update
							$this->db->trans_start();
							$newData = $this->player_model->update_player_agent($oldData);
							if($this->session->userdata('user_group') == USER_GROUP_USER)
							{
								$this->user_model->insert_log(LOG_USER_UPDATE, $newData, $oldData);
							}
							else
							{
								$this->account_model->insert_log(LOG_USER_UPDATE, $newData, $oldData);
							}
							$this->db->trans_complete();
							if ($this->db->trans_status() === TRUE)
							{
								$json['status'] = EXIT_SUCCESS;
								$json['msg'] = $this->lang->line('success_updated');
								//Prepare for ajax update
								$json['response'] = array(
									'id' => $newData['player_id'],
									'active' => (($newData['active'] == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_suspend')),
									'active_code' => $newData['active'],
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
    				$json['msg']['mobile_error'] = form_error('mobile');
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
	public function player_tag_modify($id = NULL){
		if(permission_validation(PERMISSION_TAG_PLAYER_MODIFY) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$data['tag_player_list'] = $this->tag_model->get_tag_player_list();
					$this->load->view('tag_player_modify', $data);
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
	public function player_tag_modify_update(){
		if(permission_validation(PERMISSION_TAG_PLAYER_MODIFY) == TRUE)
		{
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'general_error' => ''
				),
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			//Set form rules
			$player_id = trim($this->input->post('player_id', TRUE));
			$oldData = $this->player_model->get_player_data($player_id);
			if(!empty($oldData))
		    {
		    	$tag_player_list = $this->tag_model->get_tag_player_list();
		    	$tags_array = $this->input->post('tag[]', TRUE);
		    	$tag = (empty($tags_array) ? '' : ',' . implode(',', $tags_array) . ',');
		    	$tags_option = "";
		    	if(!empty($tags_array)){
		    		foreach($tags_array as $tags_row){
						if(isset($tag_player_list[$tags_row])){
							$tags_option .= '<span class="badge bg-success" style="background-color: '.$tag_player_list[$tags_row]['tag_player_background_color'].' !important;color: '.$tag_player_list[$tags_row]['tag_player_font_color'].' !important;font-weight: '.(($tag_player_list[$tags_row]['is_bold'] == STATUS_ACTIVE) ? "bold": "normal").' !important;"">' . $tag_player_list[$tags_row]['tag_player_code'] . '</span>&nbsp;';
						}
					}
					if(!empty($tags_option)){
						$tags_option .= '</br>';
					}
		    	}
		    	$this->db->trans_start();
		    	$newData = $this->player_model->update_player_tagids($oldData,$tag);
		    	if($this->session->userdata('user_group') == USER_GROUP_USER)
				{
					$this->user_model->insert_log(LOG_TAG_PLAYER_MODIFY, $newData, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_TAG_PLAYER_MODIFY, $newData, $oldData);
				}
				$this->db->trans_complete();
				if ($this->db->trans_status() === TRUE)
				{
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('success_updated');
					//Prepare for ajax update
					$json['response'] = array(
						'id' => $newData['player_id'],
						'tags_text' => $tags_option,
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
	public function tag_modify($id = NULL){
		if(permission_validation(PERMISSION_TAG_MODIFY) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$data['tag_list'] = $this->tag_model->get_tag_list();
					$this->load->view('tag_modify', $data);
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
	public function tag_modify_update(){
		if(permission_validation(PERMISSION_TAG_MODIFY) == TRUE)
		{
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'tag_id_error' => '',
					'general_error' => '',
				),
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			$config = array(
				array(
					'field' => 'tag_id',
					'label' => strtolower($this->lang->line('label_tag')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_select_tag'),
					)
				),
			);
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				//Set form rules
				$player_id = trim($this->input->post('player_id', TRUE));
				$tag_id = trim($this->input->post('tag_id', TRUE));
				$oldData = $this->player_model->get_player_data($player_id);
				if(!empty($oldData))
			    {
			    	$tagdata = $this->tag_model->get_tag_setting_data($tag_id);
			    	if(!empty($tagdata) && $tagdata['active'] == STATUS_ACTIVE){
			    		$this->db->trans_start();
				    	$newData = $this->player_model->update_player_tagid($oldData,$tag_id);
				    	if($this->session->userdata('user_group') == USER_GROUP_USER)
						{
							$this->user_model->insert_log(LOG_TAG_MODIFY, $newData, $oldData);
						}
						else
						{
							$this->account_model->insert_log(LOG_TAG_MODIFY, $newData, $oldData);
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === TRUE)
						{
							$json['status'] = EXIT_SUCCESS;
							$json['msg'] = $this->lang->line('success_updated');
							$tag = '<span class="badge bg-success" style="background-color: '.$tagdata['tag_background_color'].' !important;color: '.$tagdata['tag_font_color'].' !important;font-weight: '.(($tagdata['is_bold'] == STATUS_ACTIVE) ? "bold": "normal").' !important;">' . $tagdata['tag_code'] . '</span>';	
							//Prepare for ajax update
							$json['response'] = array(
								'id' => $newData['player_id'],
								'tags_text' => $tag,
							);
						}
						else
						{
							$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
						}
			    	}else{
			    		$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
			    	}
			    }else{
			    	$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
			    }
			}else{
				$json['msg']['tag_id_error'] = form_error('tag_id');
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
	
	/**
	 * Show layer for edit upline for player
	 */
	public function edit_upline_player($id = NULL)
    {
		
		if(permission_validation(PERMISSION_PLAYER_UPDATE) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			$data['uplineList'] = $this->user_model->get_all_user_data();
			if( ! empty($data))
			{
				$this->load->view('player_edit_upline', $data);
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
	
	/**
	 * Show layer for edit upline for player
	 */
	public function update_upline(){

		if(permission_validation(PERMISSION_PLAYER_UPDATE) == TRUE)
		{
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'upline_error' => '',
					'general_error' => '',
				),
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			$config = array(
				array(
					'field' => 'upline',
					'label' => strtolower($this->lang->line('label_upline')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_upline_not_found'),
					)
				),
			);
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			$upline = trim($this->input->post('upline', TRUE));
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				// check upline exist
				$userData = $this->user_model->get_user_data_by_username($upline);
				$player_id = $this->input->post('player_id', TRUE);
				if(!empty($userData)){
					$this->player_model->updateUpline($player_id, $upline);
					$json['status'] = EXIT_SUCCESS;
					$json['msg'] = $this->lang->line('success_updated');
				} else {
					$json['msg']['general_error'] = $this->lang->line('error_upline_not_found');
				}
			} else {
				$json['msg']['general_error'] = form_error('upline');
			}

			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($json))
					->_display();
			exit();
		} else {
			redirect('home');
		}
	}
}
