<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Withdrawal extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('withdrawal_model', 'player_model', 'player_promotion_model','currencies_model','payment_gateway_model','bank_model','promotion_apply_model','promotion_approve_model','message_model','tag_model'));
		
		$is_logged_in = $this->is_logged_in();
		if( ! empty($is_logged_in)) 
		{
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}
		
	public function index()
	{
		if(permission_validation(PERMISSION_WITHDRAWAL_VIEW) == TRUE)
		{
			$this->save_current_url('withdrawal');
			$data = quick_search();
			$data['page_title'] = $this->lang->line('title_withdrawal');
			
			$this->session->unset_userdata('search_withdrawals');
			$data_search = array(
				'from_date' => date('Y-m-d 00:00:00',strtotime('first day of -3 month',time())),
				'to_date' => date('Y-m-d 23:59:59'),
				'withdrawal_type' => "",
				'username' => "",
				'status' => "0",
				'ip_address' => "",
				'currency_code' => "",
			);

			if(permission_validation(PERMISSION_WITHDRAWAL_VIEW_ALL) == TRUE)
			{
				$data_search['status'] = "-1";
			}
			
			if($_GET){
				$withdrawal_id = (isset($_GET['id'])?$_GET['id']:'');
				$withdrawal_data = $this->withdrawal_model->get_withdrawal_data($withdrawal_id);
				if(!empty($withdrawal_data)){
					$data_search['from_date'] = date('Y-m-d 00:00:00',strtotime('first day of -3 month',$withdrawal_data['created_date']));
					$data_search['to_date'] = date('Y-m-d 23:59:59',strtotime('last day of this month',time()));
					$data_search['status'] = STATUS_PENDING;

					//set prevent alarm
					$this->session->set_userdata('alert_withdrawals',time());
				}
			}
			$data['data_search'] = $data_search;
			$this->session->set_userdata('search_withdrawals', $data_search);
			$this->load->view('withdrawal_view', $data);
		}
		else
		{
			redirect('home');
		}
	}
	
	public function search()
	{
		if(permission_validation(PERMISSION_WITHDRAWAL_VIEW) == TRUE)
		{
			//Initial output data
			$json = array(
					'status' => EXIT_ERROR, 
					'msg' => array(
										'from_date_error' => '',
										'to_date_error' => '',
										'general_error' => ''
									),
					'csrfTokenName' => $this->security->get_csrf_token_name(), 
					'csrfHash' => $this->security->get_csrf_hash()
				);
			
			//Set form rules
			$config = array(
							array(
									'field' => 'from_date',
									'label' => strtolower($this->lang->line('label_from_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							),
							array(
									'field' => 'to_date',
									'label' => strtolower($this->lang->line('label_to_date')),
									'rules' => 'trim|required|callback_full_datetime_check',
									'errors' => array(
														'required' => $this->lang->line('error_invalid_datetime_format'),
														'full_datetime_check' => $this->lang->line('error_invalid_datetime_format')
												)
							)
						);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$from_date = strtotime(trim($this->input->post('from_date', TRUE)));
				$to_date = strtotime(trim($this->input->post('to_date', TRUE)));
				$days = $this->cal_days_in_year(date('Y', $from_date));
				$date_range = ($days * 86400);
				$time_diff = ($to_date - $from_date);
				
				if($time_diff < 0 OR $time_diff > $date_range)
				{
					$json['msg']['general_error'] = $this->lang->line('error_invalid_year_range');
				}
				else
				{
					$data = array( 
						'from_date' => trim($this->input->post('from_date', TRUE)),
						'to_date' => trim($this->input->post('to_date', TRUE)),
						'username' => trim($this->input->post('username', TRUE)),
						'status' => trim($this->input->post('status', TRUE)),
						'withdrawal_type' => trim($this->input->post('withdrawal_type', TRUE)),
						'ip_address' => trim($this->input->post('ip_address', TRUE)),
						'withdrawal_id' => trim($this->input->post('withdrawal_id', TRUE)),
						'currency_code' => trim($this->input->post('currency_code', TRUE)),
						'agent' => trim($this->input->post('agent', TRUE)),
					);
					
					$this->session->set_userdata('search_withdrawals', $data);
					
					$json['status'] = EXIT_SUCCESS;
				}
			}
			else 
			{
				$json['msg']['from_date_error'] = form_error('from_date');
				$json['msg']['to_date_error'] = form_error('to_date');
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
		if(permission_validation(PERMISSION_WITHDRAWAL_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
				0 => 'a.withdrawal_id',
				1 => 'a.created_date',
				2 => 'a.withdrawal_type',
				3 => 'b.username',
				4 => 'b.tag_ids',
				5 => 'a.bank_name',
				6 => 'a.bank_account_name',
				7 => 'a.bank_account_no',
				8 => 'a.amount',
				9 => 'a.withdrawal_fee_value',
				10 => 'a.withdrawal_fee_amount',
				11 => 'a.status',
				12 => 'a.withdrawal_ip',
				13 => 'a.remark',
				14 => 'a.updated_by',
				15 => 'a.updated_date',
				16 => 'a.player_id',
				17 => 'b.tag_id',
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
			
			$arr = $this->session->userdata('search_withdrawals');				
			
			$where = '';		
			if( ! empty($arr['agent']))
			{
				$where = "AND a.player_id = 'ABC'";
				$agent = $this->user_model->get_user_data_by_username($arr['agent']);
				if(!empty($agent)){
					$response_upline = $this->user_model->get_downline_data($agent['username']);
					if(!empty($response_upline)){
						$where = "AND b.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";
					}
				}
			}else{
				$where = "AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";
			}
			if(isset($arr['from_date']))
			{
				if( ! empty($arr['from_date']))
				{
					$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);
				}
				
				if( ! empty($arr['to_date']))
				{
					$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);
				}

				if($arr['withdrawal_type'] >= 1 && $arr['withdrawal_type'] <= sizeof(get_withdrawal_type()))
				{
					$where .= ' AND a.withdrawal_type = ' . $arr['withdrawal_type'];
				}

				if( ! empty($arr['username']))
				{
					$where .= " AND b.username = '" . $arr['username'] . "'";
				}
				
				if($arr['status'] == STATUS_PENDING OR $arr['status'] == STATUS_APPROVE OR $arr['status'] == STATUS_CANCEL)
				{
					$where .= ' AND a.status = ' . $arr['status'];
				}
				
				if( ! empty($arr['ip_address']))
				{
					$where .= " AND a.ip_address = '" . $arr['ip_address'] . "'";
				}

				if( ! empty($arr['withdrawal_id']))
				{
					$where .= " AND a.withdrawal_id = " . $arr['withdrawal_id'];	
				}
				
				if( ! empty($arr['currency_code']))
				{
					$where .= " AND a.currency_code = '" . $arr['currency_code']."'";	
				}
			}	
			
			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;
			
			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}withdrawals a, {$dbprefix}players b WHERE (a.player_id = b.player_id) $where)";
			$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if($query->num_rows() > 0)
			{
				$posts = $query->result();  
			}

			$query->free_result();
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
			
			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();
			
			$query->free_result();
			//Prepare data
			$data = array();
			$player_whitelist_array = json_decode(PLAYER_WITHDRAWAL_RATE_WHITELIST,true);
			
			if(!empty($posts))
			{
				$tag_list = $this->tag_model->get_tag_list();
				$tag_player_list = $this->tag_model->get_tag_player_list();
				foreach ($posts as $post)
				{
					$status = "";
					$withdrawal_limit = "";
					$tag = "";
					if(isset($tag_list[$post->tag_id])){
						$tag = '<span class="badge bg-success" id="uc21_' . $post->player_id . '" style="background-color: '.$tag_list[$post->tag_id]['tag_background_color'].' !important;color: '.$tag_list[$post->tag_id]['tag_font_color'].' !important;font-weight: '.(($tag_list[$post->tag_id]['is_bold'] == STATUS_ACTIVE) ? "bold": "normal").' !important;">' . $tag_list[$post->tag_id]['tag_code'] . '</span>';						
					}

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
						$tags .= '<div id="uc23_' . $post->player_id . '" class="col-12 text-center">';
						$tags .= '<i onclick="modifyPlayerTagData(' . $post->player_id . ')" class="fas fa-user-tag nav-icon text-fuchsia" title="' . $this->lang->line('button_tag_player')  . '"></i>';
						$tags .= "</div>";
					}
					
					$withdrawal_fee_value = number_format($post->withdrawal_fee_value, 0, '.', ',');
					$withdrawal_fee_amount = number_format($post->withdrawal_fee_amount, 0, '.', ',');
					$withdrawal_fee_amount_copy = number_format($post->withdrawal_fee_amount, 0, '.', '');
					if(in_array($post->username, $player_whitelist_array))
				    {
				        $withdrawal_fee_value = number_format(0, 0, '.', ',');
				        $withdrawal_fee_amount = number_format($post->amount, 0, '.', ',');
				        $withdrawal_fee_amount_copy = number_format($post->amount, 0, '.', '');
				    }

					$button = "";
					$row = array();
					$row[] = ((floor(log10($post->withdrawal_id) + 1) > WITHDRAWAL_PAD_0) ? substr((string) $post->withdrawal_id, (WITHDRAWAL_PAD_0*-1)): str_pad($post->withdrawal_id, WITHDRAWAL_PAD_0, '0', STR_PAD_LEFT));
					$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
					$row[] = $this->lang->line(get_withdrawal_type($post->withdrawal_type));
					$row[] = $post->username.'&nbsp;<i class="fas fa-copy nav-icon text-purple clipboard" data-clipboard-text="'.$post->username.'"></i>&nbsp;'.$tag;
					$row[] = $tags;
					$row[] = $post->bank_name;
					$row[] = $post->bank_account_name.'&nbsp;<i class="fas fa-copy nav-icon text-purple clipboard" data-clipboard-text="'.$post->bank_account_name.'"></i>&nbsp;';
					$row[] = $post->bank_account_no.'&nbsp;<i class="fas fa-copy nav-icon text-purple clipboard" data-clipboard-text="'.$post->bank_account_no.'"></i>&nbsp;';
					$row[] = '<span class="text-' . (($post->amount > 0) ? 'dark' : 'dark') . '">' . number_format($post->amount, 0, '.', ',') . '</span>';
					$row[] = $withdrawal_fee_value;
					$row[] = '<span class="text-bold text-' . (($post->withdrawal_fee_amount > 0) ? 'primary' : 'dark') . '">' . $withdrawal_fee_amount . '</span>'.'&nbsp;<i class="fas fa-copy nav-icon text-purple clipboard" data-clipboard-text="'.$withdrawal_fee_amount_copy.'"></i>&nbsp;';
					
					if(isset($player_withdrawal_count_list[$post->player_id]) && $player_withdrawal_count_list[$post->player_id] < NEW_MEMBER_WITHDRAWAL_LIMIT){
						$withdrawal_limit = '<span class="badge bg-teal">' . $this->lang->line('label_new_players') . '</span>';
					}
					switch($post->status)
					{
						case STATUS_ON_PENDING: $status = '<span class="badge bg-info" id="uc1_' . $post->withdrawal_id . '">' . $this->lang->line('status_on_pending') . '</span>'; break;
						case STATUS_APPROVE: $status = '<span class="badge bg-success" id="uc1_' . $post->withdrawal_id . '">' . $this->lang->line('status_approved') . '</span>'; break;
						case STATUS_CANCEL: $status = '<span class="badge bg-danger" id="uc1_' . $post->withdrawal_id . '">' . $this->lang->line('status_cancelled') . '</span>'; break;
						default: $status = '<span class="badge bg-secondary" id="uc1_' . $post->withdrawal_id . '">' . $this->lang->line('status_pending') . '</span>'; break;
					}
					$row[] = $withdrawal_limit.((!empty($withdrawal_limit)) ?  "<br/>".$status : $status);
					$row[] = $post->withdrawal_ip;
					$row[] = '<span id="uc2_' . $post->withdrawal_id . '">' . ( ! empty($post->remark) ? $post->remark : '-') . '</span>';
					$row[] = '<span id="uc6_' . $post->withdrawal_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc7_' . $post->withdrawal_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					if(permission_validation(PERMISSION_WITHDRAWAL_UPDATE) == TRUE && $post->status == STATUS_PENDING)
					{
						$button .= '<i id="uc3_' . $post->withdrawal_id . '" onclick="updateData(' . $post->withdrawal_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					if(permission_validation(PERMISSION_WITHDRAWAL_UPDATE) == TRUE && permission_validation(PERMISSION_PLAYER_PROMOTION_VIEW) == TRUE  && permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE && $post->status == STATUS_PENDING){
						
						$button .= '<i id="uc20_' . $post->withdrawal_id . '" onclick="promotionUnsattleData(' . $post->player_id . ')" class="fas fa-gifts nav-icon text-danger" title="' . $this->lang->line('button_promotion_unsattle')  . '"></i> &nbsp;&nbsp; ';
					}

					if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE) == TRUE)
					{
						$button .= '<i onclick="viewImageData(' . $post->player_id . ')" class="fas fa-id-card nav-icon text-warning" title="' . $this->lang->line('button_view')  . '"></i> &nbsp;&nbsp; ';
					}

					if(permission_validation(PERMISSION_PLAYER_DAILY_REPORT) == TRUE && $post->status == STATUS_PENDING)
					{
						$button .= '<i onclick="player_daily(' . $post->player_id . ')" class="fas fa-clipboard-check nav-icon text-olive" title="' . $this->lang->line('button_player_daily_report')  . '"></i> &nbsp;&nbsp; ';	
					}

					if(permission_validation(PERMISSION_VIEW_PLAYER_WALLET) == TRUE)
					{
						$button .= '<i onclick="viewWallets(' . $post->player_id . ')" class="fas fa-wallet nav-icon text-navy" title="' . $this->lang->line('button_wallet')  . '"></i> &nbsp;&nbsp; ';
					}

					if(permission_validation(PERMISSION_WITHDRAWAL_UPDATE) == TRUE || (permission_validation(PERMISSION_PLAYER_PROMOTION_VIEW) == TRUE && permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE) ||permission_validation(PERMISSION_PLAYER_DAILY_REPORT) == TRUE || permission_validation(PERMISSION_VIEW_PLAYER_WALLET) == TRUE)
					{
						$row[] = $button;
					}

					$data[] = $row;
					unset($row);
				}
			}
			$total_data = array();		
			#Output
			$json_data = array(
				"draw"            => intval($this->input->post('draw')),
				"recordsFiltered" => intval($totalFiltered), 
				"data"            => $data,
				"total_data"      => $total_data,
				"csrfHash" 		  => $this->security->get_csrf_hash()					
			);
				
			echo json_encode($json_data);
			unset($json_data,$total_data,$data);	
			exit();
		}	
    }

    public function total(){
		if(permission_validation(PERMISSION_WITHDRAWAL_VIEW) == TRUE)
		{
			$arr = $this->session->userdata('search_withdrawals');
			$dbprefix = $this->db->dbprefix;
			//Declaration Total
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => '',
				'total_data' => '',
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			$json['total_data'] = array(
				'total_withdrawal' => 0,
				'total_withdrawal_fee_amount' => 0,
			);

			if(!empty($arr)){
				$json['status'] = EXIT_SUCCESS;
				$data = array();

				//Get total
				$where = '';

				if( ! empty($arr['agent']))
				{
					$where = "AND a.player_id = 'ABC'";
					$agent = $this->user_model->get_user_data_by_username($arr['agent']);
					if(!empty($agent)){
						$response_upline = $this->user_model->get_downline_data($agent['username']);
						if(!empty($response_upline)){
							$where = "AND b.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";
						}
					}
				}else{
					$where = "AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";
				}
				if(isset($arr['from_date']))
				{
					if( ! empty($arr['from_date']))
					{
						$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);
					}
					
					if( ! empty($arr['to_date']))
					{
						$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);
					}

					if($arr['withdrawal_type'] >= 1 && $arr['withdrawal_type'] <= sizeof(get_withdrawal_type()))
					{
						$where .= ' AND a.withdrawal_type = ' . $arr['withdrawal_type'];
					}

					if( ! empty($arr['username']))
					{
						$where .= " AND b.username = '" . $arr['username'] . "'";
					}
					
					if($arr['status'] == STATUS_PENDING OR $arr['status'] == STATUS_APPROVE OR $arr['status'] == STATUS_CANCEL)
					{
						$where .= ' AND a.status = ' . $arr['status'];
					}
					
					if( ! empty($arr['ip_address']))
					{
						$where .= " AND a.ip_address = '" . $arr['ip_address'] . "'";
					}

					if( ! empty($arr['withdrawal_id']))
					{
						$where .= " AND a.withdrawal_id = " . $arr['withdrawal_id'];	
					}
					
					if( ! empty($arr['currency_code']))
					{
						$where .= " AND a.currency_code = '" . $arr['currency_code']."'";	
					}
				}

				$sum_columns = array( 
					0 => 'SUM(a.amount) AS total_withdrawal',
					1 => 'SUM(a.withdrawal_fee_amount) AS total_withdrawal_fee_amount',
				);
				$sum_select = implode(',', $sum_columns);
			
				$total_query_string = "SELECT {$sum_select} FROM {$dbprefix}withdrawals a, {$dbprefix}players b WHERE (a.player_id = b.player_id) $where";
				$total_query = $this->db->query($total_query_string);
				if($total_query->num_rows() > 0)
				{
					foreach($total_query->result() as $row)
					{
						$json['total_data'] = array(
							'total_withdrawal' => (($row->total_withdrawal > 0) ? $row->total_withdrawal : 0),
							'total_withdrawal_fee_amount' => (($row->total_withdrawal_fee_amount > 0) ? $row->total_withdrawal_fee_amount : 0),
						);
					}
				}
				$total_query->free_result();
			}
			
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
		if(permission_validation(PERMISSION_WITHDRAWAL_UPDATE) == TRUE)
		{
			$data = $this->withdrawal_model->get_withdrawal_data($id);
			if( ! empty($data) && ($data['status'] == STATUS_PENDING))
			{
				$data['player'] = $this->player_model->get_player_data($data['player_id']);
				
				$bankData = $this->bank_model->get_bank_data($data['bank_id']);
				if(!empty($bankData)){
				    $data['payment_gateway_list'] = $this->payment_gateway_model->get_payment_gateway_list_by_type_and_currency(WITHDRAWAL_ONLINE,$data['currency_id'],$bankData['bank_code']);    
				}else{
				    $data['payment_gateway_list'] = array();
				}
				
				$this->load->view('withdrawal_update', $data);
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
	
	public function update() {
		if(permission_validation(PERMISSION_WITHDRAWAL_UPDATE) == TRUE) {
			#Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => '',
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			#Set form rules
			$config = array(
				array(
						'field' => 'remark',
						'label' => strtolower($this->lang->line('label_remark')),
						'rules' => 'trim'
				)
			);		
			
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			#Form validation
			if ($this->form_validation->run() == TRUE) {
				$withdrawal_id = trim($this->input->post('withdrawal_id', TRUE));
				$oldData = $this->withdrawal_model->get_withdrawal_data($withdrawal_id);
				$payment_gateway_id = $this->input->post('payment_gateway_id', TRUE);
				#empty
				if(!empty($oldData) && ($oldData['status'] == STATUS_PENDING)) {
					$status = $this->input->post('status', TRUE);
					$allowed_update = TRUE;
					if($status == STATUS_APPROVE){
						$unsattlePromotion = $this->player_promotion_model->get_unsattle_promotion($oldData);
						if(!empty($unsattlePromotion)){
							$allowed_update = FALSE;
						}
					}
					if($allowed_update){
						$player = $this->player_model->get_player_data($oldData['player_id']);
						$oldData['username'] = $player['username'];
						if(!empty($player)){
							if(!empty($payment_gateway_id) && ($this->input->post('status', TRUE) == STATUS_APPROVE)){
								$url 				= SYSTEM_PAYMENT_GATEWAY_URL;
								$paymentGatewayData = $this->payment_gateway_model->get_payment_gateway_data($payment_gateway_id);
								$bankData 			= $this->bank_model->get_bank_data($oldData['bank_id']);

								if(!empty($paymentGatewayData) && !empty($bankData)){
									$param_array = array(
										"method" => 'Payout',
										"agent_id" => SYSTEM_API_AGENT_ID,
										"syslang" => LANG_EN,
										"device" => PLATFORM_WEB,
										"payment_gateway_code" => $paymentGatewayData['payment_gateway_code'],
										"payment_gateway_type_code" => $paymentGatewayData['payment_gateway_type_code'],
										"payment_gateway_bank" => payment_gateway_code($paymentGatewayData['payment_gateway_code'] , $bankData['bank_code']),
										"player_id" => $player['player_id'],
										"username" => $player['username'],
										"full_name" => $player['full_name'],
										"email" => $player['email'],
										"mobile" => $player['mobile'],
										"order_id" => $oldData['transaction_code'],
										"transaction_id" => $oldData['withdrawal_id'],
										"bank_account_name" => $oldData['bank_account_name'],
										"bank_account_number" => $oldData['bank_account_no'],
										"amount" => $oldData['amount_rate'],
										"currency" => $oldData['currency_code'],
									);
									$json['msg'] = json_encode($param_array,true);
									$param_array['signature'] = md5(SYSTEM_API_AGENT_ID . $param_array['payment_gateway_code'] . $param_array['username'] . SYSTEM_API_SECRET_KEY);
									$response = $this->curl_json($url, $param_array);
									$result_array = json_decode($response, TRUE);
									if(isset($result_array['errorCode'])  && ($result_array['errorCode'] == "0")){
										//success
										$this->db->trans_start();
										$updateData = array(
											'payment_gateway_id' => $paymentGatewayData['payment_gateway_id'],
											'payment_gateway_bank' => $bankData['bank_code'],
											'status' => STATUS_ON_HOLD_PENDING,
											'online_status' => STATUS_ON_PENDING,
											'transaction_code_alias' => $result_array['orderIDAlias'],
											'order_no' => $result_array['paymentID'],
										);
										$newData = $this->withdrawal_model->update_withdrawal_online($oldData,$updateData);
										
										#########ADD MASTER POINT########
										$table = $this->db->dbprefix . 'users';
										$this->db->query("UPDATE {$table} SET points = (points + ?) WHERE username = ? LIMIT 1", array($oldData['amount_rate'], SYSTEM_API_AGENT_ID));
										####################################
										
									}else{
										//reject
										$this->db->trans_start();
										$updateData = array(
											'payment_gateway_id' => $paymentGatewayData['payment_gateway_id'],
											'payment_gateway_bank' => $bankData['bank_code'],
											'online_status' => STATUS_CANCEL,
											'status' => STATUS_CANCEL,
											'transaction_code_alias' => (isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''),
											'order_no' => (isset($result_array['paymentID']) ? trim($result_array['paymentID']) : ''),
										);
										$newData = $this->withdrawal_model->update_withdrawal_online($oldData,$updateData);
									}
									if($newData['status'] == STATUS_CANCEL)
									{
										$this->player_model->update_player_wallet($newData);
										$this->general_model->insert_cash_transfer_report($player, $oldData['amount'], TRANSFER_WITHDRAWAL_REFUND);
									}

									if($this->session->userdata('user_group') == USER_GROUP_USER)
									{
										$this->user_model->insert_log(LOG_WITHDRAWAL_UPDATE, $newData, $oldData);
									}
									else
									{
										$this->account_model->insert_log(LOG_WITHDRAWAL_UPDATE, $newData, $oldData);
									}
									
									$this->db->trans_complete();
									
									if ($this->db->trans_status() === TRUE)
									{
										if($newData['status'] == STATUS_APPROVE){
											if(TELEGRAM_STATUS == STATUS_ACTIVE){
												send_amount_telegram(TELEGRAM_MONEY_FLOW,$player['username'],$newData['updated_by'],$oldData['amount'],TRANSFER_WITHDRAWAL);
											}
										}

										if($newData['status'] == STATUS_APPROVE){
											$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_SUCCESS_WITHDRAWAL);
											if(!empty($system_message_data)){
												$system_message_id = $system_message_data['system_message_id']; 
												$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
												$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
												$create_time = time();
												$username = $player['username'];
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

										if($newData['status'] == STATUS_CANCEL){
											$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_FAILED_WITHDRAWAL);
											if(!empty($system_message_data)){
												$system_message_id = $system_message_data['system_message_id']; 
												$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
												$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
												$create_time = time();
												$username = $player['username'];
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
																			SYSTEM_MESSAGE_PLATFORM_VALUE_REMARK => $this->input->post('remark', TRUE),
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

										$json['status'] = EXIT_SUCCESS;
										$json['msg'] = $this->lang->line('success_updated');
										
										switch($newData['status'])
										{
											case STATUS_ON_PENDING: $status = $this->lang->line('status_on_pending'); break;
											case STATUS_APPROVE: $status = $this->lang->line('status_approved'); break;
											case STATUS_CANCEL: $status = $this->lang->line('status_cancelled'); break;
											default: $status = $this->lang->line('status_pending'); break;
										}
										
										//Prepare for ajax update
										$json['response'] = array(
											'id' => $newData['withdrawal_id'],
											'remark' => $newData['remark'],
											'status' => $status,
											'status_code' => $newData['status'],
										);
									}
									else
									{
										$json['msg'] = $this->lang->line('error_failed_to_withdraw');
									}

								}
								else{
									$json['msg'] = $this->lang->line('error_failed_to_withdraw');
								}
							}
							else{
								#Offline Withdrawal
								#Database update
								$this->db->trans_start();
								$newData = $this->withdrawal_model->update_withdrawal($oldData);

								if($newData['status'] == STATUS_CANCEL)
								{
									$this->player_model->update_player_wallet($newData);
									$this->general_model->insert_cash_transfer_report($player, $oldData['amount'], TRANSFER_WITHDRAWAL_REFUND);
								}
								else{
									$this->bank_model->update_bank_withdrawal_count($oldData);									
								}
								
								if($this->session->userdata('user_group') == USER_GROUP_USER)
								{
									$this->user_model->insert_log(LOG_WITHDRAWAL_UPDATE, $newData, $oldData);
								}
								else
								{
									$this->account_model->insert_log(LOG_WITHDRAWAL_UPDATE, $newData, $oldData);
								}
								
								$this->db->trans_complete();
								
								if ($this->db->trans_status() === TRUE)
								{
									$json['status'] = EXIT_SUCCESS;
									$json['msg'] = $this->lang->line('success_updated');
									
									switch($newData['status'])
									{
										case STATUS_ON_PENDING: $status = $this->lang->line('status_on_pending'); break;
										case STATUS_APPROVE: $status = $this->lang->line('status_approved'); break;
										case STATUS_CANCEL: $status = $this->lang->line('status_cancelled'); break;
										default: $status = $this->lang->line('status_pending'); break;
									}

									if($newData['status'] == STATUS_APPROVE){
										if(TELEGRAM_STATUS == STATUS_ACTIVE){
											send_amount_telegram(TELEGRAM_MONEY_FLOW,$player['username'],$newData['updated_by'],$oldData['amount'],TRANSFER_WITHDRAWAL);
										}
										
										#########ADD MASTER POINT########
										$table = $this->db->dbprefix . 'users';
										$this->db->query("UPDATE {$table} SET points = (points + ?) WHERE username = ? LIMIT 1", array($oldData['amount'], SYSTEM_API_AGENT_ID));
										####################################
									}

									if($newData['status'] == STATUS_APPROVE){
											$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_SUCCESS_WITHDRAWAL);
											if(!empty($system_message_data)){
												$system_message_id = $system_message_data['system_message_id']; 
												$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
												$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
												$create_time = time();
												$username = $player['username'];
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

										if($newData['status'] == STATUS_CANCEL){
											$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_FAILED_WITHDRAWAL);
											if(!empty($system_message_data)){
												$system_message_id = $system_message_data['system_message_id']; 
												$oldLangData = $this->message_model->get_message_lang_data($system_message_id);
												$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
												$create_time = time();
												$username = $player['username'];
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
																			SYSTEM_MESSAGE_PLATFORM_VALUE_REMARK => $this->input->post('remark', TRUE),
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
									
									//Prepare for ajax update
									$json['response'] = array(
										'id' => $newData['withdrawal_id'],
										'remark' => $newData['remark'],
										'status' => $status,
										'status_code' => $newData['status'],
									);
								}
								else {
									$json['msg'] = $this->lang->line('error_failed_to_withdraw');
								}
							}
						}
						else{
							$json['msg'] = $this->lang->line('error_username_not_found');
						}
					}
					else{
						$json['msg'] = $this->lang->line('error_failed_to_withdraw_got_unsattle_promotion');
					}
				}
				else {
					$json['msg'] = $this->lang->line('error_failed_to_withdraw');
				}	
			}
			else {
				$json['msg'] = $this->lang->line('error_failed_to_withdraw');
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

	public function withdrawal_offline_add($id = NULL)
	{
		if(permission_validation(PERMISSION_WITHDRAWAL_ADD) == TRUE)
		{
			$data = $this->player_model->get_player_data($id);
			$data['currencies_data'] = $this->currencies_model->get_currencies_list();
			#$data['payment_gateway_data'] = $this->payment_gateway_model->get_payment_gateway_list_by_type(WITHDRAWAL_ONLINE);
			$data['payment_gateway_data'] = array();
			if( ! empty($data))
			{
				$response = $this->user_model->get_downline_data($data['upline']);
				if( ! empty($response))
				{
					$this->load->view('withdrawal_offline_add', $data);
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

	public function withdrawal_offline_submit()
	{
		if(permission_validation(PERMISSION_WITHDRAWAL_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
								'points_error' => '',
								'currency_id_error' => '',
								'general_error' => ''
							),
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			$player_id = trim($this->input->post('player_id', TRUE));
			$currency_id = trim($this->input->post('currency_id', TRUE));
			$oldData = $this->player_model->get_player_data($player_id);
			$currenciesData = $this->currencies_model->get_currencies_active_data($currency_id);
			if( ! empty($oldData) && !empty($currenciesData))
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
						),
						array(
								'field' => 'currency_id',
								'label' => strtolower($this->lang->line('label_currency')),
								'rules' => 'trim|required',
								'errors' => array(
									'greater_than' => $this->lang->line('error_select_currencies')
								)
						)
					);		
								
					$this->form_validation->set_rules($config);
					$this->form_validation->set_error_delimiters('', '');
					
					//Form validation
					if ($this->form_validation->run() == TRUE)
					{
						$points = $this->input->post('points', TRUE);
						$data = array(
							'withdrawal_type' => WITHDRAWAL_OFFLINE_BANKING,
							'transaction_code' => 'W' . $oldData['player_id'] . time() . rand(10000, 99999),
							'amount_apply' => $points,
							'amount' => $points,
							'player_id' => $oldData['player_id'],
							'username' => $oldData['username'],
							'currency_id' => $currenciesData['currency_id'],
							'currency_code' => $currenciesData['currency_code'],
							'currency_rate' => $currenciesData['w_currency_rate'],
							'amount_rate' => bcdiv(($points * $currenciesData['w_currency_rate']) , 1, 2),
						);
						//Database update
						$this->db->trans_start();
						$newData = $this->player_model->point_transfer($oldData, ($points * -1));
						$DnewData = $this->withdrawal_model->add_withdrawal_offline($data);
						$this->general_model->insert_cash_transfer_report($oldData, $points, TRANSFER_WITHDRAWAL);
						$this->user_model->insert_log(LOG_WITHDRAWAL_UPDATE, $newData, $oldData);
						
						if($this->session->userdata('user_group') == USER_GROUP_USER)
						{
							$this->user_model->insert_log(LOG_WITHDRAWAL_UPDATE, $newData, $response);
							$this->user_model->insert_log(LOG_WITHDRAWAL_UPDATE, $DnewData, $response);
						}
						else
						{
							$this->account_model->insert_log(LOG_WITHDRAWAL_UPDATE, $newData, $response);
							$this->account_model->insert_log(LOG_WITHDRAWAL_UPDATE, $DnewData, $response);
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
						$json['msg']['currency_id_error'] = form_error('currency_id');
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

	public function promotion_unsattle($id){
		if(permission_validation(PERMISSION_PLAYER_PROMOTION_VIEW) == TRUE){
			$data = array( 
				'player_id' => $id,
			);
			$this->session->set_userdata('search_withdrawal_promotion_unsattle', $data);
			$this->load->view('withdrawal_promotion_unsattle_view', $data);
		}
	}

	public function promotion_unsattle_listing(){
		if(permission_validation(PERMISSION_PLAYER_PROMOTION_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			//Table Columns
			
			$columns = array( 
				'a.player_promotion_id',
				'a.created_date',
				'b.username',
				'a.promotion_name',
				'a.deposit_amount',
				'a.current_amount',
				'a.achieve_amount',
				'a.reward_amount',
				'a.is_reward',
				'a.reward_date',
				'a.status',
				'a.remark',
				'a.starting_date',
				'a.complete_date',
				'a.updated_by',
				'a.updated_date',
				'a.calculate_session',
			);
			$sum_columns = array( 
				0 => 'SUM(a.reward_amount) AS total_reward',
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

			$arr = $this->session->userdata('search_withdrawal_promotion_unsattle');
			$where = '';
			$where .= ' AND a.player_id = ' . $arr['player_id'];
			$where .= ' AND a.status IN (' . STATUS_PENDING . ', ' . STATUS_ENTITLEMENT . ', ' . STATUS_ACCOMPLISH . ', ' . STATUS_CANCEL . ', ' . STATUS_SATTLEMENT .')';

			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;

			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}player_promotion a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
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
					$row[] = $post->player_promotion_id;
					$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');
					$row[] = $post->username;
					$row[] = $post->promotion_name;
					$row[] = number_format($post->deposit_amount,'2','.',',');
					$row[] = number_format($post->current_amount,'2','.',',');
					$row[] = number_format($post->achieve_amount,'2','.',',');
					$row[] = '<span id="uc5_' . $post->player_promotion_id . '">' . number_format($post->reward_amount,'2','.',','). '</span>';
					switch($post->is_reward)
					{
						case STATUS_APPROVE: $row[] = '<span class="badge bg-success" id="uc4_' . $post->player_promotion_id . '">' . $this->lang->line('status_approved') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc4_' . $post->player_promotion_id . '">' . $this->lang->line('status_pending') . '</span>'; break;
					}
					$row[] = '<span id="uc6_' . $post->player_promotion_id . '">' . (($post->reward_date > 0) ? date('Y-m-d H:i:s', $post->reward_date) : '-') . '</span>';
					if($post->status == STATUS_CANCEL && empty($post->updated_by)){
						$row[] = '<span class="badge bg-success" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_system_cancel') . '</span>';
					}else{
						switch($post->status)
						{
							case STATUS_SATTLEMENT: $row[] = '<span class="badge bg-success" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_sattlement') . '</span>'; break;
							case STATUS_CANCEL: $row[] = '<span class="badge bg-danger" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_cancelled') . '</span>'; break;
							case STATUS_ENTITLEMENT: $row[] = '<span class="badge bg-primary" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_entitlement') . '</span>'; break;
							case STATUS_VOID: $row[] = '<span class="badge bg-danger" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_void') . '</span>'; break;
							case STATUS_ACCOMPLISH: $row[] = '<span class="badge bg-warning" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_accomplish') . '</span>'; break;
							default: $row[] = '<span class="badge bg-secondary" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_pending') . '</span>'; break;
						}
					}
					$row[] = '<span id="uc2_' . $post->player_promotion_id . '">' . ( ! empty($post->remark) ? $post->remark : '-') . '</span>';
					$row[] = '<span id="uc7_' . $post->player_promotion_id . '">' . (($post->starting_date > 0) ? date('Y-m-d H:i:s', $post->starting_date) : '-') . '</span>';
					$row[] = '<span id="uc8_' . $post->player_promotion_id . '">' . (($post->complete_date > 0) ? date('Y-m-d H:i:s', $post->complete_date) : '-') . '</span>';
					$row[] = '<span id="uc9_' . $post->player_promotion_id . '">' . (!empty($post->updated_by) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc10_' . $post->player_promotion_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					if(permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE){
						if($post->status == STATUS_PENDING){
							$button .= '<i id="uc21_' . $post->player_promotion_id . '" onclick="promotionEntitlement(' . $post->player_promotion_id . ')" class="fas fa-gifts nav-icon text-danger" title="' . $this->lang->line('button_entitlement')  . '"></i> &nbsp;&nbsp; ';

							$button .= '<i style="display:none;" id="uc22_' . $post->player_promotion_id . '" onclick="updateData(' . $post->player_promotion_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
						}

						if(($post->status == STATUS_ENTITLEMENT || $post->status == STATUS_ACCOMPLISH))
						{
							//fas fa-clipboard-check nav-icon text-olive

							$button .= '<i id="uc22_' . $post->player_promotion_id . '" onclick="updateData(' . $post->player_promotion_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
						}
					}

					if(permission_validation(PERMISSION_PLAYER_PROMOTION_BET_DETAIL) == TRUE){
						if(($post->status == STATUS_ENTITLEMENT || $post->status == STATUS_ACCOMPLISH || $post->status == STATUS_SATTLEMENT))
						{
							$button .= '<i id="uc25_' . $post->player_promotion_id . '" onclick="betDetailData(' . $post->player_promotion_id . ')" class="fas fa-clipboard-check nav-icon text-olive" title="' . $this->lang->line('button_bet_detail')  . '"></i> &nbsp;&nbsp; ';
						}
					}


					$row[] = $button;
					$data[] = $row;
				}
			}
			$sum_select = implode(',', $sum_columns);
			$total_data = array(
				'total_reward' => 0,
			);
			$query_string = "SELECT {$sum_select} FROM {$dbprefix}player_promotion a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
			$query = $this->db->query($query_string);
			if($query->num_rows() > 0)
			{
				foreach($query->result() as $row)
				{
					$total_data = array(
						'total_reward' => (($row->total_reward > 0) ? $row->total_reward : 0),
					);
				}
			}			
			$query->free_result();
			//Output
			$json_data = array(
							"draw"            => intval($this->input->post('draw')),
							"recordsFiltered" => intval($totalFiltered), 
							"data"            => $data,
							"total_data"      => $total_data,
							"csrfHash" 		  => $this->security->get_csrf_hash()					
						);
				
			echo json_encode($json_data); 
			exit();
		}
	}

	public function fee_setting(){
		if(permission_validation(PERMISSION_WITHDRAWAL_FEE_RATE_VIEW) == TRUE)
		{
			$this->save_current_url('withdrawal/fee_setting');
			$data['page_title'] = $this->lang->line('title_withdrawal_fee_setting');
			$this->load->view('withdrawal_fee_rate_view', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function fee_setting_listing(){
		if(permission_validation(PERMISSION_WITHDRAWAL_FEE_RATE_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);
			
			//Table Columns
			$columns = array( 
				'withdrawal_fee_rate_id',
				'withdrawal_times',
				'withdrawal_min',
				'withdrawal_max',
				'withdrawal_rate_type',
				'withdrawal_rate_amount',
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
			
			$query = array(
							'select' => implode(',', $columns),
							'table' => 'withdrawal_fee_rate',
							'limit' => $limit,
							'start' => $start,
							'order' => $order,
							'dir' => $dir,
					);
			
			$posts =  $this->general_model->all_posts($query);
			$totalFiltered = $this->general_model->all_posts_count($query);

			$data = array();
			if(!empty($posts))
			{
				foreach ($posts as $post)
				{
					$row = array();
					$row[] = $post->withdrawal_fee_rate_id;
					$row[] = '<span id="uc1_' . $post->withdrawal_fee_rate_id . '">' . $post->withdrawal_times . '</span>';
					$row[] = '<span id="uc6_' . $post->withdrawal_fee_rate_id . '">' . $post->withdrawal_min . '</span>';
					$row[] = '<span id="uc7_' . $post->withdrawal_fee_rate_id . '">' . $post->withdrawal_max . '</span>';
					$row[] = '<span id="uc8_' . $post->withdrawal_fee_rate_id . '">' . $this->lang->line(get_withdrawal_rate_type($post->withdrawal_rate_type)) . '</span>';
					$row[] = '<span id="uc9_' . $post->withdrawal_fee_rate_id . '">' . $post->withdrawal_rate_amount . '</span>';
					switch($post->active)
					{
						case STATUS_ACTIVE: $row[] = '<span class="badge bg-success" id="uc3_' . $post->withdrawal_fee_rate_id . '">' . $this->lang->line('status_active') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc3_' . $post->withdrawal_fee_rate_id . '">' . $this->lang->line('status_inactive') . '</span>'; break;
					}
					$row[] = '<span id="uc4_' . $post->withdrawal_fee_rate_id . '">' . (( ! empty($post->updated_by)) ? $post->updated_by : '-') . '</span>';
					$row[] = '<span id="uc5_' . $post->withdrawal_fee_rate_id . '">' . (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-') . '</span>';
					$button = '';
					if(permission_validation(PERMISSION_WITHDRAWAL_FEE_RATE_UPDATE) == TRUE)
					{
						$button .= '<i onclick="updateData(' . $post->withdrawal_fee_rate_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';
					}
					
					if(permission_validation(PERMISSION_WITHDRAWAL_FEE_RATE_DELETE) == TRUE)
					{
						$button .= '<i onclick="deleteData(' . $post->withdrawal_fee_rate_id . ')" class="fas fa-trash nav-icon text-danger" title="' . $this->lang->line('button_delete')  . '"></i>';
					}
					
					if(permission_validation(PERMISSION_WITHDRAWAL_FEE_RATE_DELETE) == TRUE || permission_validation(PERMISSION_WITHDRAWAL_FEE_RATE_DELETE) == TRUE)
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

	public function fee_setting_listing_add(){
		if(permission_validation(PERMISSION_WITHDRAWAL_FEE_RATE_ADD) == TRUE)
		{
			$this->load->view('withdrawal_fee_rate_add');
		}
		else
		{
			redirect('home');
		}
	}

	public function fee_setting_submit(){
		if(permission_validation(PERMISSION_WITHDRAWAL_FEE_RATE_ADD) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'withdrawal_times_error' => '',
					'withdrawal_min_error' => '',
					'withdrawal_max_error' => '',
					'withdrawal_rate_type_error' => '',
					'withdrawal_rate_amount_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			//Set form rules
			$config = array(
				array(
					'field' => 'withdrawal_times',
					'label' => strtolower($this->lang->line('label_withdrawal_times')),
					'rules' => 'trim|greater_than[0]|required',
					'errors' => array(
						'greater_than' => $this->lang->line('error_greater_than'),
						'required' => $this->lang->line('error_invalid_withdrawal_times')
					)
				),
				array(
					'field' => 'withdrawal_min',
					'label' => strtolower($this->lang->line('label_min_request_amount')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_withdrawal_min'),
					)
				),
				array(
					'field' => 'withdrawal_max',
					'label' => strtolower($this->lang->line('label_max_request_amount')),
					'rules' => 'trim|required|greater_than_equal_to['.$this->input->post('withdrawal_min').']',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_withdrawal_max'),
						'greater_than_equal_to' => $this->lang->line('error_greater_than'),
					)
				),
				array(
					'field' => 'withdrawal_rate_type',
					'label' => strtolower($this->lang->line('label_rate_type')),
					'rules' => 'trim|required|integer',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_withdrawal_rate_type'),
						'integer' => $this->lang->line('error_invalid_withdrawal_rate_type'),
					)
				),
				array(
					'field' => 'withdrawal_rate_amount',
					'label' => strtolower($this->lang->line('label_amount_rate')),
					'rules' => 'trim|required|greater_than[0]',
					'errors' => array(
						'greater_than' => $this->lang->line('error_greater_than'),
						'required' => $this->lang->line('error_invalid_withdrawal_rate_amount'),
					)
				)
			);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				//Database update
				$this->db->trans_start();
				$newData = $this->withdrawal_model->add_fee_setting();
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_WITHDRAWAL_FEE_RATE_ADD, $newData);
				}
				else
				{
					$this->account_model->insert_log(LOG_WITHDRAWAL_FEE_RATE_ADD, $newData);
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
				$json['msg']['withdrawal_times_error'] = form_error('withdrawal_times');
				$json['msg']['withdrawal_min_error'] = form_error('withdrawal_min');
				$json['msg']['withdrawal_max_error'] = form_error('withdrawal_max');
				$json['msg']['withdrawal_rate_type_error'] = form_error('withdrawal_rate_type');
				$json['msg']['withdrawal_rate_amount_error'] = form_error('withdrawal_rate_amount');
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

	public function fee_setting_listing_edit($id = NULl){
		if(permission_validation(PERMISSION_WITHDRAWAL_FEE_RATE_UPDATE) == TRUE)
		{
			$data = $this->withdrawal_model->get_withdrawal_fee_setting_data($id);
			$this->load->view('withdrawal_fee_rate_update', $data);
		}
		else
		{
			redirect('home');
		}
	}

	public function fee_setting_update(){
		if(permission_validation(PERMISSION_WITHDRAWAL_FEE_RATE_UPDATE) == TRUE)
		{
			//Initial output data
			$json = array(
				'status' => EXIT_ERROR, 
				'msg' => array(
					'withdrawal_times_error' => '',
					'withdrawal_min_error' => '',
					'withdrawal_max_error' => '',
					'withdrawal_rate_type_error' => '',
					'withdrawal_rate_amount_error' => '',
					'general_error' => ''
				), 		
				'csrfTokenName' => $this->security->get_csrf_token_name(), 
				'csrfHash' => $this->security->get_csrf_hash()
			);
			
			//Set form rules
			$config = array(
				array(
					'field' => 'withdrawal_times',
					'label' => strtolower($this->lang->line('label_withdrawal_times')),
					'rules' => 'trim|greater_than[0]|required',
					'errors' => array(
						'greater_than' => $this->lang->line('error_greater_than'),
						'required' => $this->lang->line('error_invalid_withdrawal_times')
					)
				),
				array(
					'field' => 'withdrawal_min',
					'label' => strtolower($this->lang->line('label_min_request_amount')),
					'rules' => 'trim|required',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_withdrawal_min'),
					)
				),
				array(
					'field' => 'withdrawal_max',
					'label' => strtolower($this->lang->line('label_max_request_amount')),
					'rules' => 'trim|required|greater_than_equal_to['.$this->input->post('withdrawal_min').']',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_withdrawal_max'),
						'greater_than_equal_to' => $this->lang->line('error_greater_than'),
					)
				),
				array(
					'field' => 'withdrawal_rate_type',
					'label' => strtolower($this->lang->line('label_rate_type')),
					'rules' => 'trim|required|integer',
					'errors' => array(
						'required' => $this->lang->line('error_invalid_withdrawal_rate_type'),
						'integer' => $this->lang->line('error_invalid_withdrawal_rate_type'),
					)
				),
				array(
					'field' => 'withdrawal_rate_amount',
					'label' => strtolower($this->lang->line('label_amount_rate')),
					'rules' => 'trim|required|greater_than[0]',
					'errors' => array(
						'greater_than' => $this->lang->line('error_greater_than'),
						'required' => $this->lang->line('error_invalid_withdrawal_rate_amount'),
					)
				)
			);		
						
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('', '');
			
			//Form validation
			if ($this->form_validation->run() == TRUE)
			{
				$withdrawal_fee_rate_id = trim($this->input->post('withdrawal_fee_rate_id', TRUE));
				$oldData = $this->withdrawal_model->get_withdrawal_fee_setting_data($withdrawal_fee_rate_id);
				if( ! empty($oldData))
				{
					//Database update
					$this->db->trans_start();
					$newData = $this->withdrawal_model->update_fee_setting($withdrawal_fee_rate_id);
					
					if($this->session->userdata('user_group') == USER_GROUP_USER) 
					{
						$this->user_model->insert_log(LOG_WITHDRAWAL_FEE_RATE_UPDATE, $newData, $oldData);
					}
					else
					{
						$this->account_model->insert_log(LOG_WITHDRAWAL_FEE_RATE_UPDATE, $newData, $oldData);
					}
					
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						$json['status'] = EXIT_SUCCESS;
						$json['msg'] = $this->lang->line('success_updated');
						
						//Prepare for ajax update
						$json['response'] = array(
							'id' => $newData['withdrawal_fee_rate_id'],
							'withdrawal_times' => $newData['withdrawal_times'],
							'withdrawal_min' => $newData['withdrawal_min'],
							'withdrawal_max' => $newData['withdrawal_max'],
							'withdrawal_rate_amount' => $newData['withdrawal_rate_amount'],
							'withdrawal_rate_type' => $this->lang->line(get_withdrawal_rate_type($newData['withdrawal_rate_type'])),
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
					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');
				}	
			}
			else 
			{
				$json['msg']['withdrawal_times_error'] = form_error('withdrawal_times');
				$json['msg']['withdrawal_min_error'] = form_error('withdrawal_min');
				$json['msg']['withdrawal_max_error'] = form_error('withdrawal_max');
				$json['msg']['withdrawal_rate_type_error'] = form_error('withdrawal_rate_type');
				$json['msg']['withdrawal_rate_amount_error'] = form_error('withdrawal_rate_amount');
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

	public function fee_setting_listing_delete(){
		$json = array(
					'status' => EXIT_ERROR, 
					'msg' => ''
				);
					
		if(permission_validation(PERMISSION_WITHDRAWAL_FEE_RATE_DELETE) == TRUE)
		{
			$withdrawal_fee_rate_id = $this->uri->segment(3);
			$oldData = $this->withdrawal_model->get_withdrawal_fee_setting_data($withdrawal_fee_rate_id);
			if(!empty($oldData))
			{
				//Database update
				$this->db->trans_start();
				$this->withdrawal_model->delete_fee_setting($withdrawal_fee_rate_id);
				
				if($this->session->userdata('user_group') == USER_GROUP_USER) 
				{
					$this->user_model->insert_log(LOG_WITHDRAWAL_FEE_RATE_DELETE, $oldData);
				}
				else
				{
					$this->account_model->insert_log(LOG_WITHDRAWAL_FEE_RATE_DELETE, $oldData);
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