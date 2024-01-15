<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Playerpromotion extends MY_Controller {

	public function __construct()

	{

		parent::__construct();

		$this->load->model(array('promotion_apply_model','promotion_approve_model','player_promotion_model','player_model','promotion_model','deposit_model', 'miscellaneous_model','message_model'));

		

		$is_logged_in = $this->is_logged_in();

		if( ! empty($is_logged_in)) 

		{

			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';

		}

	}



	public function index(){

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_VIEW) == TRUE)

		{

			$this->save_current_url('playerpromotion');

			$data = quick_search();

			$data['page_title'] = $this->lang->line('title_player_promotion');

			$this->session->unset_userdata('searches_player_promotion');

			$data_search = array( 

				'from_date' => date('Y-m-d 00:00:00'),

				'to_date' => date('Y-m-d 23:59:59'),

				'username' => "",

				'status' => "",

				'promotion' => "",

				'player_promotion_id' => "",

				'is_reward' => "-1",

			);



			if($_GET){

				$deposit_id = (isset($_GET['id'])?$_GET['id']:'');

				$player_promotion_data = $this->player_promotion_model->get_player_promotion_data_by_deposit_id($deposit_id);

				if(!empty($player_promotion_data)){

					$data_search['from_date'] = date('Y-m-d 00:00:00',$player_promotion_data['created_date']);

					$data_search['to_date'] = date('Y-m-d 23:59:59',$player_promotion_data['created_date']);

					$data_search['player_promotion_id'] = $player_promotion_data['player_promotion_id'];

				}

			}

			$data['data_search'] = $data_search;

			$this->session->set_userdata('searches_player_promotion', $data_search);

			$this->load->view('player_promotion_view', $data);

		}

		else

		{

			redirect('home');

		}

	}



	public function search(){

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_VIEW) == TRUE)

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

					'status' => trim($this->input->post('status', TRUE)),

					'promotion' => trim($this->input->post('promotion', TRUE)),

					'player_promotion_id' => trim($this->input->post('player_promotion_id', TRUE)),

					'agent' => trim($this->input->post('agent', TRUE)),

					'is_reward' => trim($this->input->post('is_reward', TRUE)),

				);

				$this->session->set_userdata('searches_player_promotion', $data);

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



	public function listing(){

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_VIEW) == TRUE)

		{

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$order = $this->input->post("order", TRUE);

			//Table Columns

			

			$columns = array( 

				'a.player_promotion_id',

				'a.player_promotion_id',

				'a.created_date',

				'b.username',

				'a.promotion_name',

				'a.deposit_amount',

				'a.promotion_amount',

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



			$arr = $this->session->userdata('searches_player_promotion');

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

				if( ! empty($arr['from_date'])){

					$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);

				}

			}



			if(isset($arr['to_date']))

			{

				if( ! empty($arr['from_date'])){

					$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);

				}

			}



			if( ! empty($arr['username']))

			{

				$where .= " AND b.username = '" . $arr['username'] . "'";

			}



			if($arr['status'] !==""){

				if($arr['status'] == STATUS_PENDING OR $arr['status'] == STATUS_SATTLEMENT OR $arr['status'] == STATUS_CANCEL OR $arr['status'] == STATUS_ENTITLEMENT OR $arr['status'] == STATUS_VOID OR $arr['status'] == STATUS_ACCOMPLISH)

				{

					$where .= ' AND a.status = ' . $arr['status'];

				}else if($arr['status'] == STATUS_SYSTEM_CANCEL){

					$where .= ' AND a.status = ' . STATUS_CANCEL;

					$where .= ' AND a.updated_by = ""';

				}

			}



			if(isset($arr['is_reward'])){

				if($arr['is_reward'] == STATUS_PENDING OR $arr['is_reward'] == STATUS_APPROVE)

				{

					$where .= ' AND a.is_reward = ' . $arr['is_reward'];

				}

			}



			if( ! empty($arr['promotion']))

			{

				$where .= " AND a.promotion_name LIKE '%" . $arr['promotion'] . "%' ESCAPE '!'";	

			}



			if( ! empty($arr['player_promotion_id']))

			{

				$where .= ' AND a.player_promotion_id = ' . $arr['player_promotion_id'];	

			}



			$select = implode(',', $columns);

			$dbprefix = $this->db->dbprefix;



			$posts = NULL;

			$query_string = "(SELECT {$select} FROM {$dbprefix}player_promotion a, {$dbprefix}players b WHERE (a.player_id = b.player_id) $where)";

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

					$check_box = "";



					if(permission_validation(PERMISSION_PLAYER_PROMOTION_BULK_UPDATE) == TRUE){

						if(($post->status == STATUS_PENDING || $post->status == STATUS_ENTITLEMENT || $post->status == STATUS_ACCOMPLISH)) {

							$check_box = '<span id="uc88_' . $post->player_promotion_id . '"><input type="checkbox" name="chkbox[]" value="' . $post->player_promotion_id . '" onchange="checkOrUncheck(this);" /></span>';

						}

					}

					$row[] = $check_box;

					$row[] = $post->player_promotion_id;

					$row[] = (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-');

					$row[] = $post->username;

					$row[] = $post->promotion_name;

					$row[] = number_format($post->deposit_amount,'2','.',',');

					$row[] = number_format($post->promotion_amount,'2','.',',');

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

							case STATUS_VOID: $row[] = '<span class="badge bg-warning" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_void') . '</span>'; break;

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

			$query_string = "SELECT {$sum_select} FROM {$dbprefix}player_promotion a, {$dbprefix}players b WHERE (a.player_id = b.player_id) $where";

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



	public function get_promotion_setting($id = NULL){

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_ADD) == TRUE)

		{

			$json = array(

				'status' => EXIT_ERROR, 

				'msg' => array(

					'general_error' => '',

				),

				'response' => array(

					'genre_code' => "",

					'range' => array(),

					'is_range' => STATUS_INACTIVE,

					'is_deposit' => STATUS_INACTIVE,

					'is_referral' => STATUS_INACTIVE,

					'is_achieve' => STATUS_INACTIVE,

					'is_reward' => STATUS_INACTIVE,

					'is_turnover' => STATUS_INACTIVE,	

				),

				'csrfTokenName' => $this->security->get_csrf_token_name(), 

				'csrfHash' => $this->security->get_csrf_hash()

			);



			$PromotionData = $this->promotion_model->get_promotion_data_all($id);

			if(!empty($PromotionData)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg'] = $this->lang->line('success_updated');

				$json['response']['genre_code'] = $PromotionData['genre_code'];

				if($PromotionData['genre_code'] == PROMOTION_TYPE_DE || $PromotionData['genre_code'] == PROMOTION_TYPE_FD || $PromotionData['genre_code'] == PROMOTION_TYPE_SD || $PromotionData['genre_code'] == PROMOTION_TYPE_BIRTH){

					$json['response']['is_deposit'] = STATUS_ACTIVE;

					$json['response']['is_achieve'] = STATUS_ACTIVE;

					$json['response']['is_reward'] = STATUS_ACTIVE;

				}else if($PromotionData['genre_code'] == PROMOTION_TYPE_RF || $PromotionData['genre_code'] == PROMOTION_TYPE_BDRF){

					$json['response']['is_referral'] = STATUS_ACTIVE;

					$json['response']['is_achieve'] = STATUS_ACTIVE;

					$json['response']['is_reward'] = STATUS_ACTIVE;

				}else if($PromotionData['genre_code'] == PROMOTION_TYPE_DPR){

					$json['response']['is_achieve'] = STATUS_ACTIVE;

					$json['response']['is_reward'] = STATUS_ACTIVE;

				}else if($PromotionData['genre_code'] == PROMOTION_TYPE_DPRC){

					$json['response']['is_achieve'] = STATUS_ACTIVE;

					$json['response']['is_reward'] = STATUS_ACTIVE;

				}else if($PromotionData['genre_code'] == PROMOTION_TYPE_CR){

					$json['response']['is_achieve'] = STATUS_ACTIVE;

					$json['response']['is_reward'] = STATUS_ACTIVE;

				}

				$json['response']['is_turnover'] = STATUS_ACTIVE;



				if($PromotionData['bonus_range_type'] == PROMOTION_BONUS_RANGE_TYPE_LEVEL){

					$json['response']['is_range'] = STATUS_ACTIVE;

					//$json['response']['range'] = $this->promotion_model->get_promotion_bonus_range_active_data($PromotionData['promotion_id']);

				}

			}else{

				$json['msg']['general_error'] = $this->lang->line('error_promotion_not_available');

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



	public function get_promotion_reward_amount_setting($id = NULL, $amount = 0,$player_username = NULL){

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_ADD) == TRUE)

		{

			$json = array(

				'status' => EXIT_ERROR, 

				'msg' => array(

					'general_error' => '',

				),

				'response' => array(

					'reward_turnover_multiply' => 0,

					'reward_calculate_type' => 0,

					'reward_amount_auto_calculate' => STATUS_INACTIVE,

					'reward_amount_rate' => 0,

					'reward_amount_max' => 0,

					'reward_amount_is_level' => STATUS_INACTIVE,

					'reward_amount_deposit_min' => 0,

					'reward_amount_deposit_max' => 0,

				),

				'csrfTokenName' => $this->security->get_csrf_token_name(), 

				'csrfHash' => $this->security->get_csrf_hash()

			);



			$PromotionData = $this->promotion_model->get_promotion_data_by_id($id);

			if(!empty($PromotionData)){

				$max_reward_amount = $PromotionData['max_promotion'];

				$current_amount = 0;

				$is_limit_reward_amount = FALSE;

				if($max_reward_amount > 0){

					$is_limit_reward_amount = TRUE;

					$player = $this->player_model->get_player_data_by_username($player_username);

					if(!empty($player)){

						if($promotionData['date_type'] == PROMOTION_DATE_TYPE_START_TO_END){

							$total_data = $this->promotion_apply_model->get_player_total_promotion_reward_range($id,$player['player_id'],$promotionData['start_date'],$promotionData['end_date']);

						}else{

							$total_data = $this->promotion_apply_model->get_player_total_promotion_reward_today($id,$player['player_id']);

						}

						if(!empty($total_data)){

							if(!empty($total_data['total'])){

								$current_amount = $total_data['total'];

							}

						}

					}

				}



				$limit_amount = $max_reward_amount - $current_amount;



				$json['status'] = EXIT_SUCCESS;

				$json['msg'] = $this->lang->line('success_updated');

				if($PromotionData['bonus_range_type'] == PROMOTION_BONUS_RANGE_TYPE_LEVEL){

					$json['response']['reward_amount_is_level'] = STATUS_ACTIVE;

					$PromotionLevel = $this->promotion_apply_model->get_promotion_bonus_range_active_data_with_amount_to($PromotionData['promotion_id'], $amount);

					if(!empty($PromotionLevel)){

						$json['response']['reward_calculate_type'] = $PromotionLevel['bonus_type'];

						$json['response']['reward_turnover_multiply'] = $PromotionLevel['turnover_multiply'];

						$json['response']['reward_amount_deposit_min'] = $PromotionLevel['amount_from'];

						$json['response']['reward_amount_deposit_max'] = $PromotionLevel['amount_to'];

						if($PromotionLevel['bonus_type'] == PROMOTION_BONUS_TYPE_PERCENTAGE){

							$json['response']['reward_amount_auto_calculate'] = STATUS_ACTIVE;

							$json['response']['reward_amount_rate'] = $PromotionLevel['percentage'];

							$json['response']['reward_amount_max'] = $PromotionLevel['max_amount'];

						}else if($PromotionLevel['bonus_type'] == PROMOTION_BONUS_TYPE_PERCENTAGE_TURNOVER){

							$json['response']['reward_amount_auto_calculate'] = STATUS_ACTIVE;

							$json['response']['reward_amount_rate'] = $PromotionLevel['percentage'];

							$json['response']['reward_amount_max'] = $PromotionLevel['max_amount'];

						}else{

							$json['response']['reward_amount_auto_calculate'] = STATUS_ACTIVE;

							$json['response']['reward_amount_max'] = $PromotionLevel['bonus_amount'];

						}

					}else{

						$PromotionLevel = $this->promotion_apply_model->get_promotion_bonus_range_active_data_with_amount($PromotionData['promotion_id'], $amount);

						if(!empty($PromotionLevel)){

							$json['response']['reward_calculate_type'] = $PromotionLevel['bonus_type'];

							$json['response']['reward_turnover_multiply'] = $PromotionLevel['turnover_multiply'];

							$json['response']['reward_amount_deposit_min'] = $PromotionLevel['amount_from'];

							$json['response']['reward_amount_deposit_max'] = $PromotionLevel['amount_to'];

							if($PromotionLevel['bonus_type'] == PROMOTION_BONUS_TYPE_PERCENTAGE){

								$json['response']['reward_amount_auto_calculate'] = STATUS_ACTIVE;

								$json['response']['reward_amount_rate'] = $PromotionLevel['percentage'];

								$json['response']['reward_amount_max'] = $PromotionLevel['max_amount'];

							}else if($PromotionLevel['bonus_type'] == PROMOTION_BONUS_TYPE_PERCENTAGE_TURNOVER){

								$json['response']['reward_amount_auto_calculate'] = STATUS_ACTIVE;

								$json['response']['reward_amount_rate'] = $PromotionLevel['percentage'];

								$json['response']['reward_amount_max'] = $PromotionLevel['max_amount'];

							}else{

								$json['response']['reward_amount_auto_calculate'] = STATUS_ACTIVE;

								$json['response']['reward_amount_max'] = $PromotionLevel['bonus_amount'];

							}

						}else{

							$PromotionLevel = $this->promotion_apply_model->get_promotion_bonus_range_active_data_with_amount_last($PromotionData['promotion_id'], $amount);

							if(!empty($PromotionLevel)){

								$json['response']['reward_calculate_type'] = $PromotionLevel['bonus_type'];

								$json['response']['reward_turnover_multiply'] = $PromotionLevel['turnover_multiply'];

								$json['response']['reward_amount_deposit_min'] = $PromotionLevel['amount_from'];

								$json['response']['reward_amount_deposit_max'] = $PromotionLevel['amount_to'];

								if($PromotionLevel['bonus_type'] == PROMOTION_BONUS_TYPE_PERCENTAGE){

									$json['response']['reward_amount_auto_calculate'] = STATUS_ACTIVE;

									$json['response']['reward_amount_rate'] = $PromotionLevel['percentage'];

									$json['response']['reward_amount_max'] = $PromotionLevel['max_amount'];

								}else if($PromotionLevel['bonus_type'] == PROMOTION_BONUS_TYPE_PERCENTAGE_TURNOVER){

									$json['response']['reward_amount_auto_calculate'] = STATUS_ACTIVE;

									$json['response']['reward_amount_rate'] = $PromotionLevel['percentage'];

									$json['response']['reward_amount_max'] = $PromotionLevel['max_amount'];

								}else{

									$json['response']['reward_amount_auto_calculate'] = STATUS_ACTIVE;

									$json['response']['reward_amount_max'] = $PromotionLevel['bonus_amount'];

								}

							}

						}

					}

				}else{

					$json['response']['reward_calculate_type'] = $PromotionData['bonus_type'];

					$json['response']['reward_turnover_multiply'] = $PromotionData['turnover_multiply'];

					if($PromotionData['bonus_type'] == PROMOTION_BONUS_TYPE_PERCENTAGE){

						$json['response']['reward_amount_auto_calculate'] = STATUS_ACTIVE;

						$json['response']['reward_amount_rate'] = $PromotionData['rebate_percentage'];

						$json['response']['reward_amount_max'] = $PromotionData['max_rebate'];

					}else{

						$json['response']['reward_amount_max'] = $PromotionData['rebate_amount'];

					}

				}



				if($is_limit_reward_amount){

					if($json['response']['reward_amount_max'] > $limit_amount){

						$json['response']['reward_amount_max'] = $limit_amount;

					}

				}

			}else{

				$json['msg']['general_error'] = $this->lang->line('error_promotion_not_available');

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



	public function add(){

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_ADD) == TRUE)

		{

			$data['promotion_list'] = $this->player_promotion_model->get_promotion_list_data_apply_admin();

			$this->load->view('player_promotion_add', $data);

		}

		else

		{

			redirect('home');

		}

	}



	public function submit(){

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_ADD) == TRUE)

		{

			$json = array(

				'status' => EXIT_ERROR, 

				'msg' => array(

					'username_error' => '',

					'promotion_id_error' => '',

					'deposit_amount_error' => '',

					'referrer_error' => '',

					'achieve_amount_error' => '',

					'reward_amount_error' => '',

					'general_error' => ''

				), 		

				'csrfTokenName' => $this->security->get_csrf_token_name(), 

				'csrfHash' => $this->security->get_csrf_hash()

			);



			$promotion_id = trim($this->input->post('promotion_id', TRUE));

			if(!empty($promotion_id)){

				$promotionData = $this->promotion_model->get_promotion_data_all($promotion_id);

				if(!empty($promotionData) && $promotionData['active'] == STATUS_ACTIVE){

					$config = array(

						array(

							'field' => 'username',

							'label' => strtolower($this->lang->line('label_player_username')),

							'rules' => 'trim|required',

							'errors' => array(

									'required' => $this->lang->line('error_enter_player_username'),

							)

						),

						array(

							'field' => 'promotion_id',

							'label' => strtolower($this->lang->line('label_promotion_name')),

							'rules' => 'trim|required',

							'errors' => array(

									'required' => $this->lang->line('error_enter_promotion_name'),

							)

						),

					);



					if($promotionData['genre_code'] == PROMOTION_TYPE_DE || $promotionData['genre_code'] == PROMOTION_TYPE_FD  || $promotionData['genre_code'] == PROMOTION_TYPE_SD){



						$configAdd = array(

							'field' => 'deposit_amount',

							'label' => strtolower($this->lang->line('label_deposit_amount')),

							'rules' => 'trim|greater_than[0]',

							'errors' => array(

								'greater_than' => $this->lang->line('error_greater_than'),

							)

						);

						array_push($config, $configAdd);



					}



					if($promotionData['genre_code'] == PROMOTION_TYPE_RF || $promotionData['genre_code'] == PROMOTION_TYPE_BDRF){

						$configAdd = array(

							'field' => 'referrer',

							'label' => strtolower($this->lang->line('label_referrer_username')),

							'rules' => 'trim|required',

							'errors' => array(

								'required' => $this->lang->line('error_enter_referrer_username'),

							)

						);

						array_push($config, $configAdd);

					}



					if($promotionData['genre_code'] == PROMOTION_TYPE_DPR || $promotionData['genre_code'] == PROMOTION_TYPE_DPRC){

						$configAdd = array(

							'field' => 'achieve_amount',

							'label' => strtolower($this->lang->line('label_archieve_amount')),

							'rules' => 'trim|greater_than[0]',

							'errors' => array(

								'greater_than' => $this->lang->line('error_greater_than'),

							)

						);

						array_push($config, $configAdd);



						$configAdd = array(

							'field' => 'reward_amount',

							'label' => strtolower($this->lang->line('label_reward_amount')),

							'rules' => 'trim|greater_than[0]',

							'errors' => array(

								'greater_than' => $this->lang->line('error_greater_than'),

							)

						);

						array_push($config, $configAdd);

					}

					$this->form_validation->set_rules($config);

					$this->form_validation->set_error_delimiters('', '');

					if ($this->form_validation->run() == TRUE)

					{

						$allow_to_add = TRUE;

						//check player

						$player_username  = trim($this->input->post('username', TRUE));

						$referrer_username  = trim($this->input->post('referrer', TRUE));

						$deposit_amount = trim($this->input->post('deposit_amount', TRUE));

						$achieve_amount = trim($this->input->post('achieve_amount', TRUE));

						$reward_amount = trim($this->input->post('reward_amount', TRUE));

						$referrer = array();

						$player = $this->player_model->get_player_data_by_username($player_username);

						if((empty($player) || $player['active'] != STATUS_ACTIVE))

						{

							$allow_to_add = FALSE;

							if(empty($player)){

								$json['msg']['username_error'] = $this->lang->line('error_failed_player_username_not_found');

							}else{

								$json['msg']['username_error'] = $this->lang->line('error_failed_player_not_active');

								

							}

						}



						if($allow_to_add){

							if($promotionData['genre_code'] == PROMOTION_TYPE_RF || $promotionData['genre_code'] == PROMOTION_TYPE_BDRF){

								$referrer = $this->player_model->get_player_data_by_username($referrer_username);

								if($referrer['referrer'] == $player_username){

									if($referrer['active'] != STATUS_ACTIVE)

									{

										$allow_to_add = FALSE;

										$json['msg']['username_error'] = $this->lang->line('error_failed_referrer_not_active');

									}else{

										

									}

								}else{

									$allow_to_add = FALSE;

									$json['msg']['username_error'] = $this->lang->line('error_failed_referrer_username_not_found');

								}

							}

						}



						if($allow_to_add){

							if($promotionData['genre_code'] == PROMOTION_TYPE_DPRC){

								$get_member_total_wallet  =  array(

									'balance_valid' => TRUE,

									'balance_amount' => 0,

								);

								$DBdata = array(

									'promotion_id' => $promotion_id,

									'amount' => $deposit_amount,

									'reward_amount' => $reward_amount,

									'achieve_amount' => $achieve_amount,

									'bonus_multiply' => $promotionData['turnover_multiply'],

									'player_id' => $player['player_id'],

								);

								$this->db->trans_start();

								$newData = $this->promotion_apply_model->add_player_promotion($DBdata,$get_member_total_wallet,$referrer);

								$insert_wallet_data = array(

									"player_id" => $newData['player_id'],

									"username" => $newData['username'],

									"amount" => $newData['reward_amount'],

								);

								$array = array(

									'promotion_name' => $newData['promotion_name'],

									'remark' => $this->input->post('remark', TRUE),

								);

								$this->player_model->update_player_wallet($insert_wallet_data);

								$this->general_model->insert_cash_transfer_report($player, $newData['reward_amount'], TRANSFER_PROMOTION,$array);

								$rewardData = $this->promotion_approve_model->update_player_promotion_reward_claim($newData,0);

								$this->promotion_approve_model->force_update_player_promotion($newData,STATUS_SATTLEMENT);

								$newData['is_reward'] = $rewardData['is_reward'];

								$newData['reward_date'] = $rewardData['reward_date'];

								if($this->session->userdata('user_group') == USER_GROUP_USER)

								{

									$this->user_model->insert_log(LOG_PLAYER_PROMOTION_ADD, $newData);

								}

								else

								{

									$this->account_model->insert_log(LOG_PLAYER_PROMOTION_ADD, $newData);

								}

								$this->db->trans_complete();

								if ($this->db->trans_status() === TRUE)

								{

									$json['status'] = EXIT_SUCCESS;

									$json['msg'] = $this->lang->line('success_updated');

								}else{

									$json['msg']['general_error'] = $this->lang->line('error_failed_to_player_promotion');

								}

							}else{

								$get_member_total_wallet  = $this->get_member_total_wallet($player['player_id']);

								$DBdata = array(

									'promotion_id' => $promotion_id,

									'amount' => $deposit_amount,

									'reward_amount' => $reward_amount,

									'achieve_amount' => $achieve_amount,

									'bonus_multiply' => $promotionData['turnover_multiply'],

									'player_id' => $player['player_id'],

								);

								$this->db->trans_start();

								$newData = $this->promotion_apply_model->add_player_promotion($DBdata,$get_member_total_wallet,$referrer);

								if($this->session->userdata('user_group') == USER_GROUP_USER)

								{

									$this->user_model->insert_log(LOG_PLAYER_PROMOTION_ADD, $newData);

								}

								else

								{

									$this->account_model->insert_log(LOG_PLAYER_PROMOTION_ADD, $newData);

								}

								$this->db->trans_complete();

								if ($this->db->trans_status() === TRUE)

								{

									$json['status'] = EXIT_SUCCESS;

									$json['msg'] = $this->lang->line('success_updated');

								}else{

									$json['msg']['general_error'] = $this->lang->line('error_failed_to_player_promotion');

								}

							}

						}

					}

					else

					{

						$json['msg']['username_error'] = form_error('username');

						$json['msg']['promotion_id_error'] = form_error('promotion_id');

						$json['msg']['deposit_amount_error'] = form_error('deposit_amount');

						$json['msg']['referrer_error'] = form_error('referrer');

						$json['msg']['achieve_amount_error'] = form_error('achieve_amount');

						$json['msg']['reward_amount_error'] = form_error('reward_amount');

					}

				}else{

					$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');

				}

			}else{

				$json['msg']['promotion_id_error'] = $this->lang->line('error_enter_promotion_name');

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



	public function edit($id=NULL){

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE)

		{

			$data['promotion'] = $this->player_promotion_model->get_player_promotion_data($id);

			if(!empty($data['promotion'])){

				$data['player'] = $this->player_model->get_player_data($data['promotion']['player_id']);

				if( ! empty($data['player']))

				{

					$response = $this->user_model->get_downline_data($data['player']['upline']);

					if( ! empty($response))

					{

						$this->load->view('player_promotion_update',$data);

					}else{

						redirect('home');		

					}

				}else{

					redirect('home');

				}

			}else{

				redirect('home');

			}

		}else{

			redirect('home');

		}

	}



	public function update(){

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE)

		{

			$json = array(

				'status' => EXIT_ERROR, 

				'msg' => array(

					'remark_error' => '',

					'reward_amount_error' => '',

					'general_error' => ''

				), 		

				'csrfTokenName' => $this->security->get_csrf_token_name(), 

				'csrfHash' => $this->security->get_csrf_hash()

			);

			$config = array(

				array(

						'field' => 'remark',

						'label' => strtolower($this->lang->line('label_remark')),

						'rules' => 'trim'

				)

			);



			$is_reward = $this->input->post('is_reward', TRUE);

			if($is_reward == STATUS_PENDING){

				$configAdd = array(

					'field' => 'reward_amount',

					'label' => strtolower($this->lang->line('label_amount')),

					'rules' => 'trim|greater_than[0]',

					'errors' => array(

						'greater_than' => $this->lang->line('error_greater_than'),

					)

				);

				array_push($config, $configAdd);

			}



			$this->form_validation->set_rules($config);

			$this->form_validation->set_error_delimiters('', '');

			//Form validation

			if ($this->form_validation->run() == TRUE)

			{

				$player_promotion_id = trim($this->input->post('player_promotion_id', TRUE));

				$oldData = $this->player_promotion_model->get_player_promotion_data($player_promotion_id);

				$player = $this->player_model->get_player_data($oldData['player_id']);

				if( ! empty($player) && (($oldData['status'] == STATUS_ENTITLEMENT) || ($oldData['status'] == STATUS_ACCOMPLISH)))

				{

					$response = $this->user_model->get_downline_data($player['upline']);

					if( ! empty($response))

					{

						$oldData['username'] = $player['username'];

						$this->db->trans_start();

						$newData = $this->promotion_approve_model->update_player_promotion($oldData);

						if($newData['status'] == STATUS_SATTLEMENT)

						{

							if($oldData['is_reward'] == STATUS_PENDING){

								$insert_wallet_data = array(

									"player_id" => $newData['player_id'],

									"username" => $newData['username'],

									"amount" => $newData['reward_amount'],

								);

								$array = array(

									'promotion_name' => $oldData['promotion_name'],

									'remark' => $this->input->post('remark', TRUE),

								);

								$this->player_model->update_player_wallet($insert_wallet_data);

								$this->general_model->insert_cash_transfer_report($player, $newData['reward_amount'], TRANSFER_PROMOTION,$array);

								$rewardData = $this->promotion_approve_model->update_player_promotion_reward_claim($newData,0);

								$newData['is_reward'] = $rewardData['is_reward'];

								$newData['reward_date'] = $rewardData['reward_date'];

							}

						}



						if($this->session->userdata('user_group') == USER_GROUP_USER)

						{

							$this->user_model->insert_log(LOG_PLAYER_PROMOTION_UPDATE, $newData, $oldData);

						}

						else

						{

							$this->account_model->insert_log(LOG_PLAYER_PROMOTION_UPDATE, $newData, $oldData);

						}



						$this->db->trans_complete();

						if ($this->db->trans_status() === TRUE)

						{

							$json['status'] = EXIT_SUCCESS;

							$json['msg'] = $this->lang->line('success_updated');



							switch($newData['is_reward'])

							{

								case STATUS_APPROVE: $is_reward = $this->lang->line('status_approved'); break;

								default: $is_reward = $this->lang->line('status_pending'); break;

							}

							

							switch($newData['status'])

							{

								case STATUS_SATTLEMENT: $status = $this->lang->line('status_sattlement'); break;

								case STATUS_CANCEL: $status = $this->lang->line('status_cancelled'); break;

								case STATUS_ENTITLEMENT: $status = $this->lang->line('status_entitlement'); break;

								case STATUS_VOID: $status = $this->lang->line('status_void'); break;

								case STATUS_ACCOMPLISH: $status = $this->lang->line('status_accomplish'); break;

								default: $status = $this->lang->line('status_pending'); break;

							}

							

							//Prepare for ajax update

							$json['response'] = array(

								'id' => $newData['player_promotion_id'],

								'remark' => $newData['remark'],

								'status' => $status,

								'status_code' => $newData['status'],

								'is_reward' => $is_reward,

								'is_reward_code' => $newData['is_reward'],

								'reward_amount' => number_format($newData['reward_amount'],'2','.',','),

								'updated_by' => $newData['updated_by'],

								'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),

								'reward_date' => date('Y-m-d H:i:s', $newData['reward_date']),

								'complete_date' => date('Y-m-d H:i:s', $newData['complete_date']),

							);

						}else{

							$json['msg']['general_error'] = $this->lang->line('error_failed_to_player_promotion');

						}

					}else{

						$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');		

					}

				}else{

					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');

				}



			}else{

				$json['msg']['remark_error'] = form_error('remark');

				$json['msg']['reward_amount_error'] = form_error('reward_amount');

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



	public function entitlement($id=NULL){

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE)

		{

			$data['promotion'] = $this->player_promotion_model->get_player_promotion_data($id);

			if(!empty($data['promotion'])){

				$data['player'] = $this->player_model->get_player_data($data['promotion']['player_id']);

				if( ! empty($data['player']))

				{

					$response = $this->user_model->get_downline_data($data['player']['upline']);

					if( ! empty($response))

					{

						if($data['promotion']['genre_code'] == "DE"){

							$data['promotion_response'] = $this->promotion_approve_model->deposit_promotion_approve_decision($data['promotion']);

						}else{

							$data['promotion_response'] = $this->promotion_approve_model->promotion_approve_decision($data['promotion']);

						}

						$this->load->view('player_promotion_entitlement',$data);

					}else{

						redirect('home');		

					}

				}else{

					redirect('home');

				}

			}else{

				redirect('home');

			}

		}else{

			redirect('home');

		}

	}



	public function entitlement_update(){

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE)

		{

			$json = array(

				'status' => EXIT_ERROR, 

				'msg' => array(

					'remark_error' => '',

					'general_error' => ''

				), 		

				'csrfTokenName' => $this->security->get_csrf_token_name(), 

				'csrfHash' => $this->security->get_csrf_hash()

			);



			$player_promotion_id = trim($this->input->post('player_promotion_id', TRUE));

			$oldData = $this->player_promotion_model->get_player_promotion_data($player_promotion_id);

			$player = $this->player_model->get_player_data($oldData['player_id']);

			if( ! empty($player) && (($oldData['status'] == STATUS_PENDING)))

			{

				$response = $this->user_model->get_downline_data($player['upline']);

				if( ! empty($response))

				{

					$oldData['username'] = $player['username'];

					$this->db->trans_start();

					$newData = $this->promotion_approve_model->update_entitle_player_promotion($oldData);

					if($newData['status'] == STATUS_ENTITLEMENT)

					{

						if($oldData['reward_on_apply'] == STATUS_ACTIVE){

							$insert_wallet_data = array(

								"player_id" => $newData['player_id'],

								"username" => $newData['username'],

								"amount" => $newData['reward_amount'],

							);

							$array = array(

								'promotion_name' => $oldData['promotion_name'],

								'remark' => $this->input->post('remark', TRUE),

							);

							$this->player_model->update_player_wallet($insert_wallet_data);

							$this->general_model->insert_cash_transfer_report($player, $newData['reward_amount'], TRANSFER_PROMOTION,$array);

							$rewardData = $this->promotion_approve_model->update_player_promotion_reward_claim($newData,0);

							$newData['is_reward'] = $rewardData['is_reward'];

							$newData['reward_date'] = $rewardData['reward_date'];

						}

					}



					if($this->session->userdata('user_group') == USER_GROUP_USER)

					{

						$this->user_model->insert_log(LOG_PLAYER_PROMOTION_UPDATE, $newData, $oldData);

					}

					else

					{

						$this->account_model->insert_log(LOG_PLAYER_PROMOTION_UPDATE, $newData, $oldData);

					}



					$this->db->trans_complete();

					if ($this->db->trans_status() === TRUE)

					{

						$json['status'] = EXIT_SUCCESS;

						$json['msg'] = $this->lang->line('success_updated');



						switch($newData['is_reward'])

						{

							case STATUS_APPROVE: $is_reward = $this->lang->line('status_approved'); break;

							default: $is_reward = $this->lang->line('status_pending'); break;

						}

						

						switch($newData['status'])

						{

							case STATUS_SATTLEMENT: $status = $this->lang->line('status_sattlement'); break;

							case STATUS_CANCEL: $status = $this->lang->line('status_cancelled'); break;

							case STATUS_ENTITLEMENT: $status = $this->lang->line('status_entitlement'); break;

							case STATUS_VOID: $status = $this->lang->line('status_void'); break;

							case STATUS_ACCOMPLISH: $status = $this->lang->line('status_accomplish'); break;

							default: $status = $this->lang->line('status_pending'); break;

						}

						$promotion_lang = $this->promotion_model->get_promotion_lang_data($oldData['promotion_id']);

						if($newData['status'] == STATUS_ENTITLEMENT)

						{

							if($oldData['genre_code'] == "CRLV"){

								$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_PROMOTION_REBATE);

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

															$reward = $newData['reward_amount'];

															$promotion_name = $oldData['promotion_name'];

															if(isset($promotion_lang[$v])){

																$promotion_name = $promotion_lang[$v]['promotion_title'];

															}



															$replace_string_array = array(

																SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $username,

																SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),

																SYSTEM_MESSAGE_PLATFORM_VALUE_REWARD => $reward,

																SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_NAME => $promotion_name,

																SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_MULTIPLY => $oldData['bonus_multiply'],

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

							}else if($oldData['genre_code'] == "LE"){

								$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_PROMOTION_LEVEL);

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

															$reward = $newData['reward_amount'];

															$promotion_name = $oldData['promotion_name'];

															if(isset($promotion_lang[$v])){

																$promotion_name = $promotion_lang[$v]['promotion_title'];

															}



															$replace_string_array = array(

																SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $username,

																SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),

																SYSTEM_MESSAGE_PLATFORM_VALUE_REWARD => $reward,

																SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_NAME => $promotion_name,

																SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_MULTIPLY => $oldData['bonus_multiply'],

																SYSTEM_MESSAGE_PLATFORM_VALUE_LEVEL => $oldData['ranking'] -1,

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

							}else{

								$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_SUCCESS_PROMOTION);

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

															$reward = $newData['reward_amount'];

															$promotion_name = $oldData['promotion_name'];

															if(isset($promotion_lang[$v])){

																$promotion_name = $promotion_lang[$v]['promotion_title'];

															}



															$replace_string_array = array(

																SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $username,

																SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),

																SYSTEM_MESSAGE_PLATFORM_VALUE_REWARD => $reward,

																SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_NAME => $promotion_name,

																SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_MULTIPLY => $oldData['bonus_multiply'],

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



						

						//Prepare for ajax update

						$json['response'] = array(

							'id' => $newData['player_promotion_id'],

							'remark' => $newData['remark'],

							'status' => $status,

							'status_code' => $newData['status'],

							'is_reward' => $is_reward,

							'is_reward_code' => $newData['is_reward'],

							'updated_by' => $newData['updated_by'],

							'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),

							'reward_date' => date('Y-m-d H:i:s', $newData['reward_date']),

						);

					}else{

						$json['msg']['general_error'] = $this->lang->line('error_failed_to_player_promotion');

					}

				}else{

					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');		

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



	//Get wallet total

	public function get_member_total_wallet($id){

		$is_balance_valid = TRUE;

		$total_amount = 0;

		$player_data = $this->player_model->get_player_data($id);

		if( ! empty($player_data))

		{

			$upline_data = $this->user_model->get_downline_data($player_data['upline']);

			if( ! empty($upline_data))

			{

				if( ! empty($player_data['last_in_game']))

				{

					$total_amount = $player_data['points'];

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

								"player_id" => $account_data['player_id'],

								"username" => $account_data['username'],

								"password" => $account_data['password'],

								"game_id" => $account_data['game_id'],

								"signature" => $signature,

							);

							$response = $this->curl_json($url, $param_array);

							$result_array = json_decode($response, TRUE);

							if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')

							{

								$total_amount = ($total_amount + $result_array['result']);

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

		);

		return $result;

	}



	public function bulk_update($id = NULL,$status = NULL){

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE)

		{

			$json = array(

				'status' => EXIT_ERROR, 

				'msg' => array(

					'general_error' => ''

				),

			);



			if(!empty($id) && !empty($status)){

				if($status == STATUS_SATTLEMENT || $status == STATUS_CANCEL  || $status == STATUS_VOID){

					$player = $this->player_model->get_player_data($id);

					if(!empty($player)){

						$player_promotion_data = $this->player_promotion_model->get_player_promotion_unsattle($player['player_id']);

						if(!empty($player_promotion_data)){

							$is_success = TRUE;

							foreach($player_promotion_data as $player_promotion_data_row){

								$result = $this->bulk_update_submit($player['player_id'],$player_promotion_data_row['player_promotion_id'],$status);

								if(isset($result['status']) && $result['status'] == EXIT_ERROR){

									$is_success = FALSE;

								}

							}



							if($is_success == TRUE){

								$json['status'] = EXIT_SUCCESS;

								$json['msg']['general_error'] = $this->lang->line('success_updated');

							}else{

								$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');

							}

						}else{

							$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');

						}

					}else{

						$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');

					}

				}else{

					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');

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



	public function bulk_update_submit($player_id = NULL, $player_promotion_id = NULL,$status = NULL){

		$json = array(

			'status' => EXIT_ERROR,

		);

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_UPDATE) == TRUE)

		{

			$oldData = $this->player_promotion_model->get_player_promotion_data($player_promotion_id);

			$player = $this->player_model->get_player_data($player_id);

			if( ! empty($player) && (($oldData['status'] == STATUS_ENTITLEMENT) || ($oldData['status'] == STATUS_ACCOMPLISH)))

			{

				$response = $this->user_model->get_downline_data($player['upline']);

				if( ! empty($response))

				{

					$oldData['username'] = $player['username'];

					$this->db->trans_start();

					$newData = $this->promotion_approve_model->update_player_promotion_status($oldData, $status);

					if($newData['status'] == STATUS_SATTLEMENT)

					{

						if($oldData['is_reward'] == STATUS_PENDING){

							$insert_wallet_data = array(

								"player_id" => $newData['player_id'],

								"username" => $newData['username'],

								"amount" => $newData['reward_amount'],

							);

							$array = array(

								'promotion_name' => $oldData['promotion_name'],

								'remark' => $this->input->post('remark', TRUE),

							);

							$this->player_model->update_player_wallet($insert_wallet_data);

							$this->general_model->insert_cash_transfer_report($player, $newData['reward_amount'], TRANSFER_PROMOTION,$array);

							$rewardData = $this->promotion_approve_model->update_player_promotion_reward_claim($newData,0);

							$newData['is_reward'] = $rewardData['is_reward'];

							$newData['reward_date'] = $rewardData['reward_date'];

						}

					}



					if($this->session->userdata('user_group') == USER_GROUP_USER)

					{

						$this->user_model->insert_log(LOG_PLAYER_PROMOTION_UPDATE, $newData, $oldData);

					}

					else

					{

						$this->account_model->insert_log(LOG_PLAYER_PROMOTION_UPDATE, $newData, $oldData);

					}



					$this->db->trans_complete();

					if ($this->db->trans_status() === TRUE)

					{

						$json['status'] = EXIT_SUCCESS;

					}

				}

			}

		}

		return $json;

	}



	public function calculate_current_player_turnover($player_id = NULL){

		$json = array(

			'status' => EXIT_ERROR, 

			'turnover_total_current' => '0',

			'turnover_total_required' => '0',

			'turnover_total_balance' => '0',

		);

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_TURNOVER_CALCULATE) == TRUE)

		{

			$data = $this->player_model->get_player_data($player_id);

			if( ! empty($data))

			{

				$response = $this->user_model->get_downline_data($data['upline']);

				if( ! empty($response))

				{

					$json['status']	= EXIT_SUCCESS;

					$player_promotion_data = $this->player_promotion_model->get_player_promotion_data_unsattle($player_id);

					if(!empty($player_promotion_data)){

						foreach($player_promotion_data as $player_promotion_data_row){

							if($player_promotion_data_row['current_amount'] > $json['turnover_total_current']){

								$json['turnover_total_current'] = $player_promotion_data_row['current_amount'];

							}

							$json['turnover_total_required'] += $player_promotion_data_row['achieve_amount'];

						}

					}

				}

				$balance = $json['turnover_total_required'] - $json['turnover_total_current'];

				if($balance < 0){

					$balance = 0;

				}

				$json['turnover_total_current'] = number_format(($json['turnover_total_current']), 2, '.', ',');

				$json['turnover_total_required'] = number_format(($json['turnover_total_required']), 2, '.', ',');

				$json['turnover_total_balance'] = number_format($balance, 2, '.', ',');



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



	public function bulk_promotion_add(){

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_BULK_ADD) == TRUE)

		{

			$data['promotion_list'] = $this->player_promotion_model->get_promotion_list_data_apply_admin_only_direct();

			$this->load->view('player_promotion_bulk_add', $data);

		}

		else

		{

			redirect('home');

		}

	}



	public function bulk_promotion_submit(){

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_BULK_ADD) == TRUE)

		{

			$json = array(

				'status' => EXIT_ERROR, 

				'msg' => array(

					'agent_error' => '',

					'username_error' => '',

					'promotion_id_error' => '',

					'achieve_amount_error' => '',

					'reward_amount_error' => '',

					'general_error' => ''

				), 		

				'csrfTokenName' => $this->security->get_csrf_token_name(), 

				'csrfHash' => $this->security->get_csrf_hash()

			);



			$promotion_apply_genre = trim($this->input->post('promotion_apply_genre', TRUE));

			if(!empty($promotion_apply_genre)){

				$config = array(

					array(

						'field' => 'promotion_id',

						'label' => strtolower($this->lang->line('label_promotion_name')),

						'rules' => 'trim|required',

						'errors' => array(

								'required' => $this->lang->line('error_enter_promotion_name'),

						)

					),

					array(

						'field' => 'achieve_amount',

						'label' => strtolower($this->lang->line('label_archieve_amount')),

						'rules' => 'trim|greater_than[0]',

						'errors' => array(

							'greater_than' => $this->lang->line('error_greater_than'),

						)

					),

					array(

						'field' => 'reward_amount',

						'label' => strtolower($this->lang->line('label_reward_amount')),

						'rules' => 'trim|greater_than[0]',

						'errors' => array(

							'greater_than' => $this->lang->line('error_greater_than'),

						)

					),

				);



				if($promotion_apply_genre == MESSAGE_GENRE_ALL){



				}else if($promotion_apply_genre == MESSAGE_GENRE_USER_ALL){

					$config = array(

						array(

							'field' => 'agent',

								'label' => strtolower($this->lang->line('label_agent')),

								'rules' => 'trim|required',

								'errors' => array(

										'required' => $this->lang->line('error_upline_not_found')

								)

						),

					);

				}else{

					$config = array(

						array(

							'field' => 'username',

								'label' => strtolower($this->lang->line('label_username')),

								'rules' => 'trim|required',

								'errors' => array(

										'required' => $this->lang->line('error_enter_player_username')

								)

						),

					);

				}



				$this->form_validation->set_rules($config);

				$this->form_validation->set_error_delimiters('', '');

				if ($this->form_validation->run() == TRUE)

				{

					$type_capture = array(

						'type_genre' => $promotion_apply_genre,

						'agent' => trim($this->input->post('agent', TRUE)),

						'username' => trim($this->input->post('username', TRUE)),

					);

					$player_array = $this->player_model->player_active_by_type($type_capture);

					if(!empty($player_array)){

						$promotion_id = trim($this->input->post('promotion_id', TRUE));

						$promotionData = $this->promotion_model->get_promotion_data_all($promotion_id);

						$referrer = array();

						$success_player_data = array();

						if(!empty($promotionData) && $promotionData['active'] == STATUS_ACTIVE){

							$promotion_lang = $this->promotion_model->get_promotion_lang_data($promotionData['promotion_id']);

							$achieve_amount = trim($this->input->post('achieve_amount', TRUE));

							$reward_amount = trim($this->input->post('reward_amount', TRUE));



							$DBdata = array(

								'promotion_id' => $promotion_id,

								'amount' => 0,

								'reward_amount' => $reward_amount,

								'achieve_amount' => $achieve_amount,

								'bonus_multiply' => $promotionData['turnover_multiply'],

								'player_id' => 0,

							);



							if($promotionData['genre_code'] == PROMOTION_TYPE_DPRC){

								foreach($player_array as $player){

									$get_member_total_wallet  =  array(

										'balance_valid' => TRUE,

										'balance_amount' => 0,

									);

									$DBdata['player_id'] = $player['player_id'];

									$this->db->trans_start();

									$newData = $this->promotion_apply_model->add_player_promotion($DBdata,$get_member_total_wallet,$referrer);

									$insert_wallet_data = array(

										"player_id" => $newData['player_id'],

										"username" => $newData['username'],

										"amount" => $newData['reward_amount'],

									);

									$array = array(

										'promotion_name' => $newData['promotion_name'],

										'remark' => $this->input->post('remark', TRUE),

									);

									$this->player_model->update_player_wallet($insert_wallet_data);

									$this->general_model->insert_cash_transfer_report($player, $newData['reward_amount'], TRANSFER_PROMOTION,$array);

									$rewardData = $this->promotion_approve_model->update_player_promotion_reward_claim($newData,0);

									$this->promotion_approve_model->force_update_player_promotion($newData,STATUS_SATTLEMENT);

									$newData['is_reward'] = $rewardData['is_reward'];

									$newData['reward_date'] = $rewardData['reward_date'];

									if($this->session->userdata('user_group') == USER_GROUP_USER)

									{

										$this->user_model->insert_log(LOG_PLAYER_PROMOTION_ADD, $newData);

									}

									else

									{

										$this->account_model->insert_log(LOG_PLAYER_PROMOTION_ADD, $newData);

									}

									$this->db->trans_complete();

									if ($this->db->trans_status() === TRUE)

									{

										$success_player_data[$player['player_id']] = array(

											'username' => $player['username'],

											'player_id' => $player['player_id'],

											'promotion_id' => $DBdata['promotion_id'],

											'reward_amount' => $DBdata['reward_amount'],

											'achieve_amount' => $DBdata['achieve_amount'],

											'bonus_multiply' => $DBdata['bonus_multiply'],

										);

									}

								}



								if(!empty($success_player_data)){

									$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_SUCCESS_PROMOTION);

									if(!empty($system_message_data)){

										$system_message_id = $system_message_data['system_message_id']; 

										$oldLangData = $this->message_model->get_message_lang_data($system_message_id);

										$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);

										$create_time = time();

										$MBdatalang = array();

										$MBdata = array();



										foreach($success_player_data as $success_player_data_row){

											$PBdata = array(

												'system_message_id'	=> $system_message_id,

												'player_id'			=> $success_player_data_row['player_id'],

												'username'			=> $success_player_data_row['username'],

												'active' 			=> STATUS_ACTIVE,

												'is_read'			=> MESSAGE_UNREAD,

												'created_by'		=> $this->session->userdata('username'),

												'created_date'		=> $create_time,

											);

											array_push($MBdata, $PBdata);

										}



										if( ! empty($MBdata))

										{

											$this->db->insert_batch('system_message_user', $MBdata);

										}



										$success_message_data = $this->message_model->get_message_bluk_data($system_message_id,$create_time);



										

										if(sizeof($lang)>0){

											if(sizeof($success_message_data)>0){

												foreach($success_message_data as $success_message_data_key => $success_message_data_row){

													foreach($lang as $k => $v){

														$username = $success_player_data[$success_message_data_key]['username'];

														$reward = $DBdata['reward_amount'];

														$promotion_name = $promotionData['promotion_name'];

														if(isset($promotion_lang[$v])){

															$promotion_name = $promotion_lang[$v]['promotion_title'];

														}



														$replace_string_array = array(

															SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $username,

															SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),

															SYSTEM_MESSAGE_PLATFORM_VALUE_REWARD => $reward,

															SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_NAME => $promotion_name,

															SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_MULTIPLY => $DBdata['bonus_multiply'],

														);



														$PBdataLang = array(

															'system_message_user_id'	=> $success_message_data_row,

															'system_message_title'		=> $oldLangData[$v]['system_message_title'],

															'system_message_content'	=> get_system_message_content($oldLangData[$v]['system_message_content'],$replace_string_array),

															'language_id' 				=> $v

														);

														array_push($MBdatalang, $PBdataLang);

													}

												}

											}

										}

										if( ! empty($MBdatalang))

										{

											$this->db->insert_batch('system_message_user_lang', $MBdatalang);

										}

									}

								}

							}else{

								foreach($player_array as $player){

									$get_member_total_wallet  =  array(

										'balance_valid' => TRUE,

										'balance_amount' => 0,

									);

									$DBdata['player_id'] = $player['player_id'];

									$this->db->trans_start();

									$newData = $this->promotion_apply_model->add_player_promotion($DBdata,$get_member_total_wallet,$referrer);

									if($this->session->userdata('user_group') == USER_GROUP_USER)

									{

										$this->user_model->insert_log(LOG_PLAYER_PROMOTION_ADD, $newData);

									}

									else

									{

										$this->account_model->insert_log(LOG_PLAYER_PROMOTION_ADD, $newData);

									}

									$this->db->trans_complete();

									if ($this->db->trans_status() === TRUE)

									{

										$success_player_data[$player['player_id']] = array(

											'username' => $player['username'],

											'player_id' => $player['player_id'],

											'promotion_id' => $DBdata['promotion_id'],

											'reward_amount' => $DBdata['reward_amount'],

											'achieve_amount' => $DBdata['achieve_amount'],

											'bonus_multiply' => $DBdata['bonus_multiply'],

										);

									}

								}

							}

							if(!empty($success_player_data)){

								$json['status'] = EXIT_SUCCESS;

								$json['msg'] = $this->lang->line('success_added');

							}else{

								$json['msg'] = $this->lang->line('error_failed_to_add');

							}

						}else{

							$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');

						}

					}else{

						$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');

					}

				}else{

					$json['msg']['agent_error'] = form_error('agent');

					$json['msg']['username_error'] = form_error('username');

					$json['msg']['promotion_id_error'] = form_error('promotion_id');

					$json['msg']['deposit_amount_error'] = form_error('deposit_amount');

					$json['msg']['achieve_amount_error'] = form_error('achieve_amount');

					$json['msg']['reward_amount_error'] = form_error('reward_amount');

				}

			}else{

				$json['msg']['general_error'] = $this->lang->line('error_failed_to_add');

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



	public function bulk_update_check_submit(){

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_BULK_UPDATE) == TRUE)

		{



			//Initial output data

			$json = array(

				'status' => EXIT_ERROR, 

				'msg' =>  array(

					'general_error' => ''

				),

				'response' => array(),

				'csrfTokenName' => $this->security->get_csrf_token_name(), 

				'csrfHash' => $this->security->get_csrf_hash()

			);



			$all_promotion_ids = array();

			$player_ids = array();

			$promotion_ids = array();

			$promotion_lang = array();

			$player_promotion_result_data = array();

			$success_promotion_data = array();

			$temp_array = array();

			$status_text = "";

			$status = $_POST['status'];

			if(isset($_POST['chkbox'])) {

				for($i=0;$i<sizeof($_POST['chkbox']);$i++) {

					if($_POST['chkbox'][$i] != 'on') {

						if(!in_array($_POST['chkbox'][$i],$all_promotion_ids)){

							array_push($all_promotion_ids,$_POST['chkbox'][$i]);

						}

					}

				}

				if(!empty($all_promotion_ids)){

					$is_success = FALSE;

					if($status == STATUS_ENTITLEMENT){

						$promotion_bulk_data  = $this->player_promotion_model->get_all_pending_promotion_by_ids($all_promotion_ids);

						if(!empty($promotion_bulk_data)){

							foreach($promotion_bulk_data as $promotion_bulk_row){

								if(!in_array($promotion_bulk_row['player_id'],$player_ids)){

									array_push($player_ids,$promotion_bulk_row['player_id']);

								}

								if(!in_array($promotion_bulk_row['promotion_id'],$promotion_ids)){

									array_push($promotion_ids,$promotion_bulk_row['promotion_id']);

								}

							}

						}



						if(!empty($promotion_ids)){

							$promotion_lang = $this->promotion_model->get_all_promotion_lang_data_by_ids($promotion_ids);

						}



						if(!empty($player_ids)){

							$player_list = $this->player_model->get_all_player_data_by_ids($player_ids);

							if(!empty($player_list)){

								foreach($promotion_bulk_data as $oldData){

									if(isset($player_list[$oldData['player_id']])){

										$player_promotion_result_data[$oldData['player_promotion_id']] = array(

											'player_id' => $oldData['player_id'],

											'username' => $player_list[$oldData['player_id']]['username'],

											'player_referrer_id' => $oldData['player_referrer_id'],

											'promotion_id' => $oldData['promotion_id'],

											'promotion_name' => $oldData['promotion_name'],

											'genre_code' => $oldData['genre_code'],

											'ranking' => $oldData['ranking'],

											'is_complete' => false,

											'is_reward' => false,

											'reward_amount' => $oldData['reward_amount'],

											'bonus_multiply' => $oldData['bonus_multiply'],

											'achieve_amount' => $oldData['achieve_amount'],

										);

										

										$oldData['username'] = $player_list[$oldData['player_id']]['username'];

										$player = $player_list[$oldData['player_id']];

										

										$this->db->trans_start();

										$newData = $this->promotion_approve_model->update_bulk_entitle_player_promotion($oldData, $status);

										if($newData['status'] == STATUS_ENTITLEMENT)

										{

											if($oldData['reward_on_apply'] == STATUS_ACTIVE){

												$insert_wallet_data = array(

													"player_id" => $newData['player_id'],

													"username" => $newData['username'],

													"amount" => $newData['reward_amount'],

												);

												$array = array(

													'promotion_name' => $oldData['promotion_name'],

													'remark' => $this->input->post('remark', TRUE),

												);

												$this->player_model->update_player_wallet($insert_wallet_data);

												$this->general_model->insert_cash_transfer_report($player, $newData['reward_amount'], TRANSFER_PROMOTION,$array);

												$rewardData = $this->promotion_approve_model->update_player_promotion_reward_claim($newData,0);

												$newData['is_reward'] = $rewardData['is_reward'];

												$newData['reward_date'] = $rewardData['reward_date'];

											}

										}

										if($this->session->userdata('user_group') == USER_GROUP_USER)

										{

											$this->user_model->insert_log(LOG_PLAYER_PROMOTION_UPDATE, $newData, $oldData);

										}

										else

										{

											$this->account_model->insert_log(LOG_PLAYER_PROMOTION_UPDATE, $newData, $oldData);

										}

										$this->db->trans_complete();

										if ($this->db->trans_status() === TRUE)

										{

											if($newData['status'] == STATUS_ENTITLEMENT)

											{

												$player_promotion_result_data[$oldData['player_promotion_id']]['is_complete'] = true;

												if($oldData['is_reward'] == STATUS_PENDING){

													$player_list[$insert_wallet_data['player_id']]['points'] += $insert_wallet_data['amount'];

													$player_promotion_result_data[$oldData['player_promotion_id']]['is_reward'] = true;

												}

											}



											switch($newData['is_reward'])

											{

												case STATUS_APPROVE: $is_reward = $this->lang->line('status_approved'); break;

												default: $is_reward = $this->lang->line('status_pending'); break;

											}

											

											switch($newData['status'])

											{

												case STATUS_SATTLEMENT: $status_text = $this->lang->line('status_sattlement'); break;

												case STATUS_CANCEL: $status_text = $this->lang->line('status_cancelled'); break;

												case STATUS_ENTITLEMENT: $status_text = $this->lang->line('status_entitlement'); break;

												case STATUS_VOID: $status_text = $this->lang->line('status_void'); break;

												case STATUS_ACCOMPLISH: $status_text = $this->lang->line('status_accomplish'); break;

												default: $status_text = $this->lang->line('status_pending'); break;

											}



											$temp_array = array(

												'id' => $newData['player_promotion_id'],

												'remark' => $newData['remark'],

												'status' => $status_text,

												'status_code' => $newData['status'],

												'is_reward' => $is_reward,

												'is_reward_code' => $newData['is_reward'],

												'reward_amount' => number_format($newData['reward_amount'],'2','.',','),

												'updated_by' => $newData['updated_by'],

												'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),

												'starting_date' => ((isset($newData['starting_date']) && !empty($newData['starting_date']))  ? date('Y-m-d H:i:s', $newData['starting_date']): ''),

												'reward_date' => ((isset($newData['reward_date']) && !empty($newData['reward_date']))  ? date('Y-m-d H:i:s', $newData['reward_date']): ''),

												'complete_date' => ((isset($newData['complete_date']) && !empty($newData['complete_date']))  ? date('Y-m-d H:i:s', $newData['complete_date']): ''),

											);



											array_push($json['response'],$temp_array);

										}

									}

								}



								if(!empty($player_promotion_result_data)){

									foreach($player_promotion_result_data as $player_promotion_result_key => $player_promotion_result_row){

										if($player_promotion_result_row['is_complete'] == TRUE){

											if($player_promotion_result_row['is_reward'] == TRUE){

												if(!isset($success_promotion_data[$player_promotion_result_row['promotion_id']])){

													$success_promotion_data[$player_promotion_result_row['promotion_id']] = array(

														'promotion_id' => $player_promotion_result_row['promotion_id'],

														'promotion_name' => $player_promotion_result_row['promotion_name'],

														'genre_code' => $player_promotion_result_row['genre_code'],

														'reward_data' => array(),

													);

												}

												$temp_array = array(

													'player_promotion_id' => $player_promotion_result_key,

													'username' => $player_promotion_result_row['username'],

													'player_id' => $player_promotion_result_row['player_id'],

													'player_referrer_id' => $player_promotion_result_row['player_referrer_id'],

													'ranking' => $player_promotion_result_row['ranking'],

													'reward_amount' => $player_promotion_result_row['reward_amount'],

													'achieve_amount' => $player_promotion_result_row['achieve_amount'],

													'bonus_multiply' => $player_promotion_result_row['bonus_multiply'],

												);

												array_push($success_promotion_data[$player_promotion_result_row['promotion_id']]['reward_data'], $temp_array);

											}

										}

									}

								}

								if(!empty($success_promotion_data)){

									foreach($success_promotion_data as $success_promotion_row){

										if(!empty($success_promotion_row['reward_data'])){

											if($success_promotion_row['genre_code'] == "LE"){

												$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_PROMOTION_LEVEL);

											}else if($success_promotion_row['genre_code'] == "CRLV"){

												$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_PROMOTION_REBATE);

											}else{

												$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_SUCCESS_PROMOTION);

											}



											if(!empty($system_message_data)){

												$system_message_id = $system_message_data['system_message_id']; 

												$oldLangData = $this->message_model->get_message_lang_data($system_message_id);

												$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);

												$create_time = time();

												$MBdatalang = array();

												$MBdata = array();





												foreach($success_promotion_row['reward_data'] as $success_player_data_row){

													$PBdata = array(

														'system_message_id'	=> $system_message_id,

														'player_id'			=> $success_player_data_row['player_id'],

														'username'			=> $success_player_data_row['username'],

														'active' 			=> STATUS_ACTIVE,

														'is_read'			=> MESSAGE_UNREAD,

														'created_by'		=> $this->session->userdata('username'),

														'created_date'		=> $create_time,

													);

													array_push($MBdata, $PBdata);

												}



												if( ! empty($MBdata))

												{

													$this->db->insert_batch('system_message_user', $MBdata);

												}



												$success_message_data = $this->message_model->get_message_bluk_data_by_system_message_user_id($system_message_id,$create_time);

												if(sizeof($lang)>0){

													if(sizeof($success_message_data)>0){

														$i = 0;

														foreach($success_message_data as $success_message_data_row => $success_message_data_key){

															foreach($lang as $k => $v){

																$promotion_name = $success_promotion_row['promotion_name'];

																if(isset($promotion_lang[$success_promotion_row['promotion_id']][$v])){

																	$promotion_name = $promotion_lang[$success_promotion_row['promotion_id']][$v]['promotion_title'];

																}



																$replace_string_array = array(

																	SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $success_promotion_row['reward_data'][$i]['username'],

																	SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),

																	SYSTEM_MESSAGE_PLATFORM_VALUE_REWARD => $success_promotion_row['reward_data'][$i]['reward_amount'],

																	SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_NAME => $promotion_name,

																	SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_MULTIPLY => $success_promotion_row['reward_data'][$i]['bonus_multiply'],

																	SYSTEM_MESSAGE_PLATFORM_VALUE_LEVEL => $success_promotion_row['reward_data'][$i]['ranking'] - 1,

																);



																$PBdataLang = array(

																	'system_message_user_id'	=> $success_message_data_row,

																	'system_message_title'		=> $oldLangData[$v]['system_message_title'],

																	'system_message_content'	=> get_system_message_content($oldLangData[$v]['system_message_content'],$replace_string_array),

																	'language_id' 				=> $v

																);

																array_push($MBdatalang, $PBdataLang);

															}

															$i++;

														}

													}

												}

												if( ! empty($MBdatalang))

												{

													$this->db->insert_batch('system_message_user_lang', $MBdatalang);

												}

											}

											sleep(1);

										}

									}

								}

							}

						}

					}else if($status == STATUS_SATTLEMENT){

						$promotion_bulk_data  = $this->player_promotion_model->get_all_unsattle_promotion_by_ids($all_promotion_ids);

						if(!empty($promotion_bulk_data)){

							foreach($promotion_bulk_data as $promotion_bulk_row){

								if(!in_array($promotion_bulk_row['player_id'],$player_ids)){

									array_push($player_ids,$promotion_bulk_row['player_id']);

								}

								if(!in_array($promotion_bulk_row['promotion_id'],$promotion_ids)){

									array_push($promotion_ids,$promotion_bulk_row['promotion_id']);

								}

							}

						}



						if(!empty($promotion_ids)){

							$promotion_lang = $this->promotion_model->get_all_promotion_lang_data_by_ids($promotion_ids);

						}



						if(!empty($player_ids)){

							$player_list = $this->player_model->get_all_player_data_by_ids($player_ids);

							if(!empty($player_list)){

								foreach($promotion_bulk_data as $oldData){

									if(isset($player_list[$oldData['player_id']])){

										$player_promotion_result_data[$oldData['player_promotion_id']] = array(

											'player_id' => $oldData['player_id'],

											'username' => $player_list[$oldData['player_id']]['username'],

											'player_referrer_id' => $oldData['player_referrer_id'],

											'promotion_id' => $oldData['promotion_id'],

											'promotion_name' => $oldData['promotion_name'],

											'genre_code' => $oldData['genre_code'],

											'ranking' => $oldData['ranking'],

											'is_complete' => false,

											'is_reward' => false,

											'reward_amount' => $oldData['reward_amount'],

											'bonus_multiply' => $oldData['bonus_multiply'],

											'achieve_amount' => $oldData['achieve_amount'],

										);

										

										$oldData['username'] = $player_list[$oldData['player_id']]['username'];

										$player = $player_list[$oldData['player_id']];

										

										$this->db->trans_start();

										$newData = $this->promotion_approve_model->update_bulk_player_promotion_status($oldData, $status);

										if($newData['status'] == STATUS_SATTLEMENT)

										{

											if($oldData['is_reward'] == STATUS_PENDING){

												$insert_wallet_data = array(

													"player_id" => $newData['player_id'],

													"username" => $newData['username'],

													"amount" => $newData['reward_amount'],

												);

												$array = array(

													'promotion_name' => $oldData['promotion_name'],

													'remark' => $this->input->post('remark', TRUE),

												);

												$this->player_model->update_player_wallet($insert_wallet_data);

												$this->general_model->insert_cash_transfer_report($player, $newData['reward_amount'], TRANSFER_PROMOTION,$array);

												$rewardData = $this->promotion_approve_model->update_player_promotion_reward_claim($newData,0);

												$newData['is_reward'] = $rewardData['is_reward'];

												$newData['reward_date'] = $rewardData['reward_date'];

											}

										}

										if($this->session->userdata('user_group') == USER_GROUP_USER)

										{

											$this->user_model->insert_log(LOG_PLAYER_PROMOTION_UPDATE, $newData, $oldData);

										}

										else

										{

											$this->account_model->insert_log(LOG_PLAYER_PROMOTION_UPDATE, $newData, $oldData);

										}

										$this->db->trans_complete();

										if ($this->db->trans_status() === TRUE)

										{

											if($newData['status'] == STATUS_SATTLEMENT)

											{

												$player_promotion_result_data[$oldData['player_promotion_id']]['is_complete'] = true;

												if($oldData['is_reward'] == STATUS_PENDING){

													$player_list[$insert_wallet_data['player_id']]['points'] += $insert_wallet_data['amount'];

													$player_promotion_result_data[$oldData['player_promotion_id']]['is_reward'] = true;

												}

											}



											switch($newData['is_reward'])

											{

												case STATUS_APPROVE: $is_reward = $this->lang->line('status_approved'); break;

												default: $is_reward = $this->lang->line('status_pending'); break;

											}

											

											switch($newData['status'])

											{

												case STATUS_SATTLEMENT: $status_text = $this->lang->line('status_sattlement'); break;

												case STATUS_CANCEL: $status_text = $this->lang->line('status_cancelled'); break;

												case STATUS_ENTITLEMENT: $status_text = $this->lang->line('status_entitlement'); break;

												case STATUS_VOID: $status_text = $this->lang->line('status_void'); break;

												case STATUS_ACCOMPLISH: $status_text = $this->lang->line('status_accomplish'); break;

												default: $status_text = $this->lang->line('status_pending'); break;

											}



											$temp_array = array(

												'id' => $newData['player_promotion_id'],

												'remark' => $newData['remark'],

												'status' => $status_text,

												'status_code' => $newData['status'],

												'is_reward' => $is_reward,

												'is_reward_code' => $newData['is_reward'],

												'reward_amount' => number_format($newData['reward_amount'],'2','.',','),

												'updated_by' => $newData['updated_by'],

												'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),

												'starting_date' => ((isset($newData['starting_date']) && !empty($newData['starting_date']))  ? date('Y-m-d H:i:s', $newData['starting_date']): ''),

												'reward_date' => ((isset($newData['reward_date']) && !empty($newData['reward_date']))  ? date('Y-m-d H:i:s', $newData['reward_date']): ''),

												'complete_date' => ((isset($newData['complete_date']) && !empty($newData['complete_date']))  ? date('Y-m-d H:i:s', $newData['complete_date']): ''),

											);



											array_push($json['response'],$temp_array);

										}

									}

								}



								if(!empty($player_promotion_result_data)){

									foreach($player_promotion_result_data as $player_promotion_result_key => $player_promotion_result_row){

										if($player_promotion_result_row['is_complete'] == TRUE){

											$is_success = TRUE;

											if($player_promotion_result_row['is_reward'] == TRUE){

												if(!isset($success_promotion_data[$player_promotion_result_row['promotion_id']])){

													$success_promotion_data[$player_promotion_result_row['promotion_id']] = array(

														'promotion_id' => $player_promotion_result_row['promotion_id'],

														'promotion_name' => $player_promotion_result_row['promotion_name'],

														'genre_code' => $player_promotion_result_row['genre_code'],

														'reward_data' => array(),

													);

												}

												$temp_array = array(

													'player_promotion_id' => $player_promotion_result_key,

													'username' => $player_promotion_result_row['username'],

													'player_id' => $player_promotion_result_row['player_id'],

													'player_referrer_id' => $player_promotion_result_row['player_referrer_id'],

													'ranking' => $player_promotion_result_row['ranking'],

													'reward_amount' => $player_promotion_result_row['reward_amount'],

													'achieve_amount' => $player_promotion_result_row['achieve_amount'],

													'bonus_multiply' => $player_promotion_result_row['bonus_multiply'],

												);

												array_push($success_promotion_data[$player_promotion_result_row['promotion_id']]['reward_data'], $temp_array);

											}

										}

									}

								}

								if(!empty($success_promotion_data)){

									foreach($success_promotion_data as $success_promotion_row){

										if(!empty($success_promotion_row['reward_data'])){

											if($success_promotion_row['genre_code'] == "LE"){

												$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_PROMOTION_LEVEL);

											}else if($success_promotion_row['genre_code'] == "CRLV"){

												$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_PROMOTION_REBATE);

											}else{

												$system_message_data = $this->message_model->get_message_data_by_templete(SYSTEM_MESSAGE_PLATFORM_SUCCESS_PROMOTION);

											}



											if(!empty($system_message_data)){

												$system_message_id = $system_message_data['system_message_id']; 

												$oldLangData = $this->message_model->get_message_lang_data($system_message_id);

												$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);

												$create_time = time();

												$MBdatalang = array();

												$MBdata = array();





												foreach($success_promotion_row['reward_data'] as $success_player_data_row){

													$PBdata = array(

														'system_message_id'	=> $system_message_id,

														'player_id'			=> $success_player_data_row['player_id'],

														'username'			=> $success_player_data_row['username'],

														'active' 			=> STATUS_ACTIVE,

														'is_read'			=> MESSAGE_UNREAD,

														'created_by'		=> $this->session->userdata('username'),

														'created_date'		=> $create_time,

													);

													array_push($MBdata, $PBdata);

												}



												if( ! empty($MBdata))

												{

													$this->db->insert_batch('system_message_user', $MBdata);

												}



												$success_message_data = $this->message_model->get_message_bluk_data_by_system_message_user_id($system_message_id,$create_time);

												if(sizeof($lang)>0){

													if(sizeof($success_message_data)>0){

														$i = 0;

														foreach($success_message_data as $success_message_data_row => $success_message_data_key){

															foreach($lang as $k => $v){

																$promotion_name = $success_promotion_row['promotion_name'];

																if(isset($promotion_lang[$success_promotion_row['promotion_id']][$v])){

																	$promotion_name = $promotion_lang[$success_promotion_row['promotion_id']][$v]['promotion_title'];

																}



																$replace_string_array = array(

																	SYSTEM_MESSAGE_PLATFORM_VALUE_USERNAME => $success_promotion_row['reward_data'][$i]['username'],

																	SYSTEM_MESSAGE_PLATFORM_VALUE_PLATFORM => get_platform_language_name($v),

																	SYSTEM_MESSAGE_PLATFORM_VALUE_REWARD => $success_promotion_row['reward_data'][$i]['reward_amount'],

																	SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_NAME => $promotion_name,

																	SYSTEM_MESSAGE_PLATFORM_VALUE_PROMOTION_MULTIPLY => $success_promotion_row['reward_data'][$i]['bonus_multiply'],

																	SYSTEM_MESSAGE_PLATFORM_VALUE_LEVEL => $success_promotion_row['reward_data'][$i]['ranking'] - 1,

																);



																$PBdataLang = array(

																	'system_message_user_id'	=> $success_message_data_row,

																	'system_message_title'		=> $oldLangData[$v]['system_message_title'],

																	'system_message_content'	=> get_system_message_content($oldLangData[$v]['system_message_content'],$replace_string_array),

																	'language_id' 				=> $v

																);

																array_push($MBdatalang, $PBdataLang);

															}

															$i++;

														}

													}

												}

												if( ! empty($MBdatalang))

												{

													$this->db->insert_batch('system_message_user_lang', $MBdatalang);

												}

											}

											sleep(1);

										}

									}

								}

							}

						}

					}else if($status == STATUS_CANCEL){

						$promotion_bulk_data  = $this->player_promotion_model->get_all_unsattle_promotion_by_ids($all_promotion_ids);

						if(!empty($promotion_bulk_data)){

							foreach($promotion_bulk_data as $promotion_bulk_row){

								if(!in_array($promotion_bulk_row['player_id'],$player_ids)){

									array_push($player_ids,$promotion_bulk_row['player_id']);

								}

							}

						}



						if(!empty($player_ids)){

							$player_list = $this->player_model->get_all_player_data_by_ids($player_ids);

							if(!empty($player_list)){

								foreach($promotion_bulk_data as $oldData){

									$oldData['username'] = ((isset($player_list[$oldData['player_id']]['username'])) ? $player_list[$oldData['player_id']]['username'] : '');

									$this->db->trans_start();

									$newData = $this->promotion_approve_model->update_bulk_player_promotion_status($oldData, $status);

									if($this->session->userdata('user_group') == USER_GROUP_USER)

									{

										$this->user_model->insert_log(LOG_PLAYER_PROMOTION_UPDATE, $newData, $oldData);

									}

									else

									{

										$this->account_model->insert_log(LOG_PLAYER_PROMOTION_UPDATE, $newData, $oldData);

									}

									$this->db->trans_complete();

									if($this->db->trans_status() === TRUE)

									{

										array_push($success_promotion_data,$newData['player_promotion_id']);

										switch($newData['is_reward'])

										{

											case STATUS_APPROVE: $is_reward = $this->lang->line('status_approved'); break;

											default: $is_reward = $this->lang->line('status_pending'); break;

										}

										

										switch($newData['status'])

										{

											case STATUS_SATTLEMENT: $status_text = $this->lang->line('status_sattlement'); break;

											case STATUS_CANCEL: $status_text = $this->lang->line('status_cancelled'); break;

											case STATUS_ENTITLEMENT: $status_text = $this->lang->line('status_entitlement'); break;

											case STATUS_VOID: $status_text = $this->lang->line('status_void'); break;

											case STATUS_ACCOMPLISH: $status_text = $this->lang->line('status_accomplish'); break;

											default: $status_text = $this->lang->line('status_pending'); break;

										}



										$temp_array = array(

											'id' => $newData['player_promotion_id'],

											'remark' => $newData['remark'],

											'status' => $status_text,

											'status_code' => $newData['status'],

											'is_reward' => $is_reward,

											'is_reward_code' => $newData['is_reward'],

											'reward_amount' => number_format($newData['reward_amount'],'2','.',','),

											'updated_by' => $newData['updated_by'],

											'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),

											'starting_date' => ((isset($newData['starting_date']) && !empty($newData['starting_date']))  ? date('Y-m-d H:i:s', $newData['starting_date']): ''),

											'reward_date' => ((isset($newData['reward_date']) && !empty($newData['reward_date']))  ? date('Y-m-d H:i:s', $newData['reward_date']): ''),

											'complete_date' => ((isset($newData['complete_date']) && !empty($newData['complete_date']))  ? date('Y-m-d H:i:s', $newData['complete_date']): ''),

										);



										array_push($json['response'],$temp_array);

									}

								}

							}

						}

					}else if($status == STATUS_VOID){

						$promotion_bulk_data  = $this->player_promotion_model->get_all_unsattle_promotion_by_ids($all_promotion_ids);

						if(!empty($promotion_bulk_data)){

							foreach($promotion_bulk_data as $promotion_bulk_row){

								if(!in_array($promotion_bulk_row['player_id'],$player_ids)){

									array_push($player_ids,$promotion_bulk_row['player_id']);

								}

							}

						}



						if(!empty($player_ids)){

							$player_list = $this->player_model->get_all_player_data_by_ids($player_ids);

							if(!empty($player_list)){

								foreach($promotion_bulk_data as $oldData){

									$oldData['username'] = ((isset($player_list[$oldData['player_id']]['username'])) ? $player_list[$oldData['player_id']]['username'] : '');

									$this->db->trans_start();

									$newData = $this->promotion_approve_model->update_bulk_player_promotion_status($oldData, $status);

									if($this->session->userdata('user_group') == USER_GROUP_USER)

									{

										$this->user_model->insert_log(LOG_PLAYER_PROMOTION_UPDATE, $newData, $oldData);

									}

									else

									{

										$this->account_model->insert_log(LOG_PLAYER_PROMOTION_UPDATE, $newData, $oldData);

									}

									$this->db->trans_complete();

									if($this->db->trans_status() === TRUE)

									{

										array_push($success_promotion_data,$newData['player_promotion_id']);

										switch($newData['is_reward'])

										{

											case STATUS_APPROVE: $is_reward = $this->lang->line('status_approved'); break;

											default: $is_reward = $this->lang->line('status_pending'); break;

										}

										

										switch($newData['status'])

										{

											case STATUS_SATTLEMENT: $status_text = $this->lang->line('status_sattlement'); break;

											case STATUS_CANCEL: $status_text = $this->lang->line('status_cancelled'); break;

											case STATUS_ENTITLEMENT: $status_text = $this->lang->line('status_entitlement'); break;

											case STATUS_VOID: $status_text = $this->lang->line('status_void'); break;

											case STATUS_ACCOMPLISH: $status_text = $this->lang->line('status_accomplish'); break;

											default: $status_text = $this->lang->line('status_pending'); break;

										}



										$temp_array = array(

											'id' => $newData['player_promotion_id'],

											'remark' => $newData['remark'],

											'status' => $status_text,

											'status_code' => $newData['status'],

											'is_reward' => $is_reward,

											'is_reward_code' => $newData['is_reward'],

											'reward_amount' => number_format($newData['reward_amount'],'2','.',','),

											'updated_by' => $newData['updated_by'],

											'updated_date' => date('Y-m-d H:i:s', $newData['updated_date']),

											'starting_date' => ((isset($newData['starting_date']) && !empty($newData['starting_date']))  ? date('Y-m-d H:i:s', $newData['starting_date']): ''),

											'reward_date' => ((isset($newData['reward_date']) && !empty($newData['reward_date']))  ? date('Y-m-d H:i:s', $newData['reward_date']): ''),

											'complete_date' => ((isset($newData['complete_date']) && !empty($newData['complete_date']))  ? date('Y-m-d H:i:s', $newData['complete_date']): ''),

										);



										array_push($json['response'],$temp_array);

									}

								}

							}

						}

					}



					if(!empty($success_promotion_data)){

						$is_success = TRUE;

					}

					if($is_success == TRUE){

						$json['status'] = EXIT_SUCCESS;

						$json['msg'] = $this->lang->line('success_updated');	

					}else{

						$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');

					}

				}else{

					$json['msg']['general_error'] = $this->lang->line('error_failed_to_update');	

				}

			}else{

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

	// ================== bet_detail ==================
	public function bet_detail($id = null)
	{
		if(permission_validation(PERMISSION_PLAYER_PROMOTION_VIEW) == TRUE)
		{
			$this->save_current_url('playerpromotion');
			$data['page_title'] = $this->lang->line('title_player_promotion_bet_detail');
			$this->session->unset_userdata('searches_player_promotion_bet_detail');
			$data_search = array( 
				'from_date' => date('Y-m-d 00:00:00'),
				'to_date' => date('Y-m-d 23:59:59'),
				'player_id' => "",
				'game_ids' => "",
				'player_promotion_id' => "",
			);

			if (!empty($id)) {
				// $data['promotion'] = $this->player_promotion_model->get_player_promotion_data_by_deposit_id($id);
				$data['promotion'] = $this->player_promotion_model->get_player_promotion_data($id);
	
				if (!empty($data['promotion'])) {
					$data['player'] = $this->player_model->get_player_data($data['promotion']['player_id']);
					if (!empty($data['player'])) {
						$data_search['from_date'] = date('Y-m-d H:i:s', $data['promotion']['starting_date']);
						$data_search['to_date'] = null;
						$data_search['player_id'] = $data['player']['player_id'];
						$data_search['game_ids'] = $data['promotion']['game_ids'];
						$data_search['player_promotion_id'] = $data['promotion']['player_promotion_id'];
					}
				}
			}
			$data['data_search'] = $data_search;

			$this->session->set_userdata('searches_player_promotion_bet_detail', $data_search);
			$this->load->view('player_promotion_bet_detail_view', $data);
		} else {
			redirect('home');
		}
	}

	public function bet_detail_listing()
	{
		if(permission_validation(PERMISSION_PLAYER_PROMOTION_VIEW) == TRUE)
		{
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$order = $this->input->post("order", TRUE);

			//Table Columns
			$columns = array( 
				'a.transaction_id',
				'a.payout_time',
				'a.bet_time',
				'a.game_time',
				'a.report_time',
				'b.username',
				'a.bet_id',
				'a.game_provider_code',
				'a.game_type_code',
				'a.game_provider_type_code',
				'a.bet_amount',
				'a.payout_amount',
				'a.promotion_amount',
				'a.win_loss',
				'a.jackpot_win',
				'a.bet_amount_valid',
				'a.table_id',
				'a.round',
				'a.subround',
				'a.odds_currency',
				'a.odds_rate',
				'a.bet_code',
				'a.game_result',
				'a.status',
				'a.game_username',
			);

			$sum_columns = array(
				0 => 'SUM(a.bet_amount) AS total_bet_amount',
				1 => 'SUM(a.payout_amount) AS total_payout_amount',
				2 => 'SUM(a.promotion_amount) AS total_promotion_amount',
				3 => 'SUM(a.win_loss) AS total_win_loss',
				4 => 'SUM(a.jackpot_win) AS total_jackpot_win',
				5 => 'SUM(a.bet_amount_valid) AS total_rolling_amount',
			);

			$col = 0;
			$dir = "";
			if (!empty($order)) {
				foreach($order as $o) {
					$col = $o['column'];
					$dir = $o['dir'];
				}
			}

			if ($dir != "asc" && $dir != "desc") {
				$dir = "desc";
			}

			if (!isset($columns[$col])) {
				$order = $columns[0];
			} else {
				$order = $columns[$col];
			}

			$arr = $this->session->userdata('searches_player_promotion_bet_detail');

			$where = '';
			if (!empty($arr['agent'])) {
				$where = "AND a.player_id = 'ABC'";
				$agent = $this->user_model->get_user_data_by_username($arr['agent']);

				if (!empty($agent)) {
					$response_upline = $this->user_model->get_downline_data($agent['username']);
					if (!empty($response_upline)) {
						$where = "AND b.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";
					}
				}
			} else {
				$where = "AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";
			}

			if (!empty($arr['from_date'])) {
				$where .= ' AND a.bet_time >= ' . strtotime($arr['from_date']);
			}

			if (!empty($arr['to_date'])) {
				$where .= ' AND a.bet_time <= ' . strtotime($arr['to_date']);
			}

			if (!empty($arr['player_id'])) {
				$where .= ' AND a.player_id = ' . $arr['player_id'];
			}

			if (!empty($arr['game_ids'])) {
				$game_ids = explode(',', $arr['game_ids']);
				array_pop($game_ids);
				array_shift($game_ids);
				$where .= ' AND a.game_provider_type_code IN ("' . implode('","', $game_ids) . '")';
			}

			/* if ($arr['status'] !=="") {
				if ($arr['status'] == STATUS_PENDING OR $arr['status'] == STATUS_SATTLEMENT OR $arr['status'] == STATUS_CANCEL OR $arr['status'] == STATUS_ENTITLEMENT OR $arr['status'] == STATUS_VOID OR $arr['status'] == STATUS_ACCOMPLISH) {
					$where .= ' AND a.status = ' . $arr['status'];
				} elseif($arr['status'] == STATUS_SYSTEM_CANCEL) {
					$where .= ' AND a.status = ' . STATUS_CANCEL;
					$where .= ' AND a.updated_by = ""';
				}
			} */

			$select = implode(',', $columns);
			$dbprefix = $this->db->dbprefix;
			$posts = NULL;
			$query_string = "(SELECT {$select} FROM {$dbprefix}transaction_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) $where)";
			$query_string_2 = " ORDER by {$order} {$dir} LIMIT {$start}, {$limit}";
			$query = $this->db->query($query_string . $query_string_2);
			if ($query->num_rows() > 0) {
				$posts = $query->result();
			}

			$query->free_result();

			$query = $this->db->query($query_string);
			$totalFiltered = $query->num_rows();
			$query->free_result();

			//Prepare data
			$data = array();

			if (!empty($posts)) {
				foreach ($posts as $post) {
					$button = "";
					$row = array();
					$check_box = "";

					if (permission_validation(PERMISSION_PLAYER_PROMOTION_BULK_UPDATE) == TRUE) {
						if(($post->status == STATUS_PENDING || $post->status == STATUS_ENTITLEMENT || $post->status == STATUS_ACCOMPLISH)) {
							$check_box = '<span id="uc88_' . $post->player_promotion_id . '"><input type="checkbox" name="chkbox[]" value="' . $post->player_promotion_id . '" onchange="checkOrUncheck(this);" /></span>';
						}
					}

					$row[] = $post->transaction_id;
					$row[] = $post->payout_time;
					$row[] = (($post->bet_time > 0) ? date('Y-m-d H:i:s', $post->bet_time) : '-');
					$row[] = (($post->game_time > 0) ? date('Y-m-d H:i:s', $post->game_time) : '-');
					$row[] = (($post->report_time > 0) ? date('Y-m-d H:i:s', $post->report_time) : '-');
					$row[] = $post->username;
					$row[] = !empty($post->bet_id) ? $post->bet_id : '-';
					$row[] = $post->game_provider_code;
					$row[] = $post->game_type_code;
					$row[] = $post->game_provider_type_code;
					$row[] = number_format($post->bet_amount, '2', '.', ',');
					$row[] = number_format($post->payout_amount, '2', '.', ',');
					$row[] = number_format($post->promotion_amount, '2', '.', ',');
					$row[] = number_format($post->win_loss, '2', '.', ',');
					$row[] = number_format($post->jackpot_win, '2', '.', ',');
					$row[] = number_format($post->bet_amount_valid, '2', '.', ',');
					$row[] = $post->table_id ?? '-';
					$row[] = $post->round ?? '-';
					$row[] = $post->subround ?? '-';
					$row[] = $post->odds_currency ?? '-';
					$row[] = $post->odds_rate ?? '-';
					$row[] = $post->bet_code ?? '-';
					$row[] = $post->game_result ?? '-';
					switch($post->status) {
						case STATUS_SUCCESS: $row[] = '<span class="badge bg-success" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('error_success') . '</span>'; break;
						default: $row[] = '<span class="badge bg-secondary" id="uc1_' . $post->player_promotion_id . '">' . $this->lang->line('status_pending') . '</span>'; break;
					}
					$row[] = $post->game_username;
					$row[] = $button;

					$data[] = $row;
				}
			}

			$sum_select = implode(',', $sum_columns);
			$total_data = array(
				'total_reward' => 0,
			);

			$query_string = "SELECT {$sum_select} FROM {$dbprefix}transaction_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) $where";
			$query = $this->db->query($query_string);
			if ($query->num_rows() > 0) {
				$row = $query->row_array();
				$total_data = array(
					'total_bet_amount' => !empty($row['total_bet_amount']) ? number_format($row['total_bet_amount'], '2', '.', ',') : 0,
					'total_payout_amount' => !empty($row['total_payout_amount']) ? number_format($row['total_payout_amount'], '2', '.', ',') : 0,
					'total_promotion_amount' => !empty($row['total_promotion_amount']) ? number_format($row['total_promotion_amount'], '2', '.', ',') : 0,
					'total_win_loss' => !empty($row['total_win_loss']) ? number_format($row['total_win_loss'], '2', '.', ',') : 0,
					'total_jackpot_win' => !empty($row['total_jackpot_win']) ? number_format($row['total_jackpot_win'], '2', '.', ',') : 0,
					'total_rolling_amount' => !empty($row['total_rolling_amount']) ? number_format($row['total_rolling_amount'], '2', '.', ',') : 0,
				);
			}
			$query->free_result();

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
	// ================== bet_detail ==================
}
