<?php
class Promotion_approve_model extends CI_Model {
	protected $table_promotion = 'promotion';
	protected $table_promotion_genre = 'promotion_genre';
	protected $table_promotion_bonus_range = 'promotion_bonus_range';
	protected $table_promotion_lang = 'promotion_lang';
	protected $table_deposits = 'deposits';
	protected $table_player_promotion = 'player_promotion';
	
	public function deposit_promotion_on_pending($data = NULL){
		$result = NULL;
		$this->db->from('player_promotion');
		$this->db->where('player_id',$data['player_id']);
		$this->db->where('deposit_id',$data['deposit_id']);
		$this->db->where_in('genre_code',array(PROMOTION_TYPE_DE,PROMOTION_TYPE_BIRTH,PROMOTION_TYPE_FD,PROMOTION_TYPE_SD));
		$this->db->where('status', STATUS_PENDING);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function update_player_promotion($arr = NULL){
		$DBdata = array(
			'status' => (($this->input->post('status', TRUE) == STATUS_SATTLEMENT) ? STATUS_SATTLEMENT : (($this->input->post('status', TRUE) == STATUS_CANCEL) ? STATUS_CANCEL : STATUS_VOID)),
			'remark' => $this->input->post('remark', TRUE),
			'reward_amount' => $arr['reward_amount'],
			'complete_date' => time(),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);

		if($DBdata['status'] == STATUS_SATTLEMENT){
			if($arr['is_reward'] == STATUS_PENDING){
				$DBdata['reward_amount'] = $this->input->post('reward_amount', TRUE);
			}
		}

		$this->db->where('player_promotion_id', $arr['player_promotion_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->limit(1);
		$this->db->update($this->table_player_promotion, $DBdata);

		$DBdata['player_promotion_id'] = $arr['player_promotion_id'];
		$DBdata['player_id'] = $arr['player_id'];
		$DBdata['username'] = $arr['username'];
		$DBdata['reward_amount'] = $DBdata['reward_amount'];
		$DBdata['reward_accumulate'] = $arr['reward_accumulate'];
		$DBdata['complete_date'] = $DBdata['complete_date'];
		$DBdata['is_reward'] = $arr['is_reward'];
		$DBdata['reward_date'] = $arr['reward_date'];
		return $DBdata;
	}

	public function update_player_promotion_status($arr = NULL,$status = NULL){
		$DBdata = array(
			'status' => $status,
			'reward_amount' => $arr['reward_amount'],
			'complete_date' => time(),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);

		if(isset($arr['remark']) && !empty($arr['remark'])){
			$DBdata['remark'] = $arr['remark']." ".$this->input->post('remark', TRUE);
		}else{
			$DBdata['remark'] = $this->input->post('remark', TRUE);
		}

		if($DBdata['status'] == STATUS_SATTLEMENT){
			if($arr['is_reward'] == STATUS_PENDING){
				$DBdata['reward_amount'] = $this->input->post('reward_amount', TRUE);
			}
		}

		$this->db->where('player_promotion_id', $arr['player_promotion_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->limit(1);
		$this->db->update($this->table_player_promotion, $DBdata);

		$DBdata['player_promotion_id'] = $arr['player_promotion_id'];
		$DBdata['player_id'] = $arr['player_id'];
		$DBdata['username'] = $arr['username'];
		$DBdata['reward_amount'] = $DBdata['reward_amount'];
		$DBdata['reward_accumulate'] = $arr['reward_accumulate'];
		$DBdata['complete_date'] = $DBdata['complete_date'];
		$DBdata['is_reward'] = $arr['is_reward'];
		$DBdata['reward_date'] = $arr['reward_date'];
		return $DBdata;
	}


	public function update_bulk_player_promotion_status($arr = NULL,$status = NULL){
		$DBdata = array(
			'status' => $status,
			'reward_amount' => $arr['reward_amount'],
			'complete_date' => time(),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);

		if(isset($arr['remark']) && !empty($arr['remark'])){
			$DBdata['remark'] = $arr['remark']." ".$this->input->post('remark', TRUE);
		}else{
			$DBdata['remark'] = $this->input->post('remark', TRUE);
		}

		$this->db->where('player_promotion_id', $arr['player_promotion_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->limit(1);
		$this->db->update($this->table_player_promotion, $DBdata);

		$DBdata['player_promotion_id'] = $arr['player_promotion_id'];
		$DBdata['player_id'] = $arr['player_id'];
		$DBdata['username'] = $arr['username'];
		$DBdata['reward_amount'] = $DBdata['reward_amount'];
		$DBdata['reward_accumulate'] = $arr['reward_accumulate'];
		$DBdata['complete_date'] = $DBdata['complete_date'];
		$DBdata['is_reward'] = $arr['is_reward'];
		$DBdata['reward_date'] = $arr['reward_date'];
		return $DBdata;
	}

	public function update_bulk_entitle_player_promotion($arr = NULL,$status = NULL){
		$starting_date = $this->promotion_starting_date_decision($arr);
		$DBdata = array(
			'starting_date' => $starting_date,
			'status' => $status,
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);

		if(isset($arr['remark']) && !empty($arr['remark'])){
			$DBdata['remark'] = $arr['remark']." ".$this->input->post('remark', TRUE);
		}else{
			$DBdata['remark'] = $this->input->post('remark', TRUE);
		}

		$this->db->where('player_promotion_id', $arr['player_promotion_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->limit(1);
		$this->db->update($this->table_player_promotion, $DBdata);

		$DBdata['player_promotion_id'] = $arr['player_promotion_id'];
		$DBdata['player_id'] = $arr['player_id'];
		$DBdata['username'] = $arr['username'];
		$DBdata['reward_amount'] = $arr['reward_amount'];
		$DBdata['reward_accumulate'] = $arr['reward_accumulate'];
		$DBdata['is_reward'] = $arr['is_reward'];
		$DBdata['reward_date'] = $arr['reward_date'];
		return $DBdata;
	}
	
	public function promotion_starting_date_decision($promotionData = NULL){
		$current_time = time();
		$starting_date = time();
		if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_WEEK_ONCE || $promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_MONTH_ONCE || $promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_YEARS_ONCE){
			//calculate every week,years,month
			if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_WEEK_ONCE){
				$starting_date = strtotime(date('Y-m-d 00:00:00',strtotime('sunday last week')));
			}

			else if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_MONTH_ONCE){
				$starting_date = strtotime(date('Y-m-d 00:00:00',strtotime('first day of this month')));
			}

			else if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_YEARS_ONCE){
				$starting_date = strtotime(date('Y-m-d 00:00:00',strtotime('first day of january')));
			}
		}else{
			if($promotionData['is_starting_of_the_day'] == STATUS_ACTIVE){
				$starting_date = strtotime(date('Y-m-d 00:00:00', $current_time));
			}
		}
		return $starting_date;
	}

	public function update_player_promotion_reward_claim($arr = NULL,$balanceData = NULL){
		$DBdata = array(
			'is_reward' => STATUS_APPROVE,
			'reward_accumulate' => $arr['reward_accumulate'] + $arr['reward_amount'],
			'reward_date' => time(),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);

		$this->db->where('player_promotion_id', $arr['player_promotion_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->limit(1);
		$this->db->update($this->table_player_promotion, $DBdata);
		$data['is_reward'] = $DBdata['is_reward'];
		$data['reward_date'] = $DBdata['reward_date'];
		$data['updated_by'] = $DBdata['updated_by'];
		$data['updated_date'] = $DBdata['updated_date'];
		return $data;
	}

	public function force_update_player_promotion($arr = NULL, $status = null){
		if($status == STATUS_ENTITLEMENT){
			$DBdata = array(
				'status' => $status,
				'starting_date' => time(),
				'updated_by' => $this->session->userdata('username'),
				'updated_date' => time()
			);
		}else{
			$DBdata = array(
				'status' => $status,
				'complete_date' => time(),
				'updated_by' => $this->session->userdata('username'),
				'updated_date' => time()
			);
		}
		
		$this->db->where('player_promotion_id', $arr['player_promotion_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->limit(1);
		$this->db->update($this->table_player_promotion, $DBdata);
	}

	public function update_entitle_player_promotion($arr = NULL){
		$starting_date = $this->promotion_starting_date_decision($arr);
		$DBdata = array(
			'starting_date' => $starting_date,
			'status' => (($this->input->post('status', TRUE) == STATUS_ENTITLEMENT) ? STATUS_ENTITLEMENT : (($this->input->post('status', TRUE) == STATUS_CANCEL) ? STATUS_CANCEL : STATUS_VOID)),
			'remark' => $this->input->post('remark', TRUE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);

		$this->db->where('player_promotion_id', $arr['player_promotion_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->limit(1);
		$this->db->update($this->table_player_promotion, $DBdata);

		$DBdata['player_promotion_id'] = $arr['player_promotion_id'];
		$DBdata['player_id'] = $arr['player_id'];
		$DBdata['username'] = $arr['username'];
		$DBdata['reward_amount'] = $arr['reward_amount'];
		$DBdata['reward_accumulate'] = $arr['reward_accumulate'];
		$DBdata['is_reward'] = $arr['is_reward'];
		$DBdata['reward_date'] = $arr['reward_date'];
		return $DBdata;
	}

	public function get_player_deposit_promotion_available_by_id_approve($data = NULL){
		$result = NULL;
		$this->db->from('promotion');
		$this->db->where('promotion_id',$data['promotion_id']);
		$this->db->where('promotion.active', STATUS_ACTIVE);
		$this->db->where('promotion.start_date <= ',time());
		$this->db->group_start();
			$this->db->where('promotion.end_date >= ',time());
			$this->db->or_where('promotion.end_date',0);
		$this->db->group_end();
		$this->db->order_by('promotion.promotion_seq', 'ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_player_pending_deposit_promotion_approve($data = NULL,$promotionData = NULL){
		$result = NULL;
		$this->db->from('player_promotion');
		$this->db->where('player_id',$data['player_id']);
		if(isset($data['deposit_id']) && !empty($data['deposit_id'])){
			$this->db->where('player_promotion.deposit_id != ', $data['deposit_id']);
		}

		$this->db->where_in('genre_code',array(PROMOTION_TYPE_DE,PROMOTION_TYPE_BIRTH,PROMOTION_TYPE_FD,PROMOTION_TYPE_SD));
		$this->db->where('status', STATUS_ENTITLEMENT);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function deposit_promotion_check_expirate_date_approve($data = NULL,$promotionData = NULL){
		$result = NULL;
		$this->db->from('player_promotion');
		$this->db->where('player_promotion.player_promotion_id != ', $data['player_promotion_id']);
		$this->db->where('player_promotion.promotion_id', $data['promotion_id']);
		$this->db->where('player_promotion.player_id', $data['player_id']);
		$this->db->where('player_promotion.active', STATUS_ACTIVE);
		$this->db->where('player_promotion.start_date <= ',time());
		$this->db->where('player_promotion.status != ', STATUS_VOID);
		if(isset($data['deposit_id']) && !empty($data['deposit_id'])){
			$this->db->where('player_promotion.deposit_id != ', $data['deposit_id']);
		}
		$this->db->group_start();
			$this->db->where('player_promotion.end_date >= ',time());
			$this->db->or_where('player_promotion.end_date',0);
		$this->db->group_end();
		$this->db->where('player_promotion.updated_date <=',time());
		$this->db->where('player_promotion.updated_date >=',strtotime('-'.$data['date_expirate_type'].' days', time()));
		$this->db->order_by('player_promotion.promotion_seq', 'ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function deposit_promotion_check_claim_limit_approve($data = NULL,$promotionData = NULL){
		$result = NULL;
		$this->db->from('player_promotion');
		$this->db->where('player_promotion.player_promotion_id != ', $data['player_promotion_id']);
		$this->db->where('player_promotion.promotion_id', $data['promotion_id']);
		$this->db->where('player_promotion.player_id', $data['player_id']);
		$this->db->where('player_promotion.active', STATUS_ACTIVE);
		$this->db->where('player_promotion.start_date <= ',time());
		$this->db->where('player_promotion.status != ', STATUS_VOID);
		if(isset($data['deposit_id']) && !empty($data['deposit_id'])){
			$this->db->where('player_promotion.deposit_id != ', $data['deposit_id']);
		}
		$this->db->group_start();
			$this->db->where('player_promotion.end_date >= ',time());
			$this->db->or_where('player_promotion.end_date',0);
		$this->db->group_end();
		if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_ONCE){

		}
		else if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_DAY_ONCE){
			$this->db->where('player_promotion.updated_date >=',strtotime(date('Y-m-d 00:00:00',time())));
			$this->db->where('player_promotion.updated_date <=',strtotime(date('Y-m-d 23:59:00',time())));
		}
		else if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_MONTH_ONCE){
			$this->db->where('player_promotion.updated_date >=',strtotime(date('Y-m-d 00:00:00',strtotime('first day of this month'))));
			$this->db->where('player_promotion.updated_date <=',strtotime(date('Y-m-d 23:59:59',strtotime('last day of this month'))));
		}
		else if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_YEARS_ONCE){
			$this->db->where('player_promotion.updated_date >=',strtotime(date('Y-m-d 00:00:00',strtotime('first day of january'))));
			$this->db->where('player_promotion.updated_date <=',strtotime(date('Y-m-d 23:59:59',strtotime('last day of december'))));
		}
		else if($promotionData['times_limit_type'] == PROMOTION_TIMES_LIMIT_TYPE_EVERY_WEEK_ONCE){
			$this->db->where('player_promotion.updated_date >=',strtotime(date('Y-m-d 00:00:00',strtotime('sunday last week'))));
			$this->db->where('player_promotion.updated_date <=',strtotime(date('Y-m-d 23:59:59',strtotime('saturday this week'))));
		}

		$this->db->order_by('player_promotion.updated_date', 'DESC');
		$this->db->order_by('player_promotion.created_date', 'DESC');
		$this->db->order_by('player_promotion.promotion_seq', 'ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_success_deposit_data($data = NULL){
		$result = NULL;
		$this->db->where('status', STATUS_COMPLETE);
		$this->db->where('player_id', $data['player_id']);
		if(isset($data['deposit_id']) && !empty($data['deposit_id'])){
			$this->db->where('deposit_id != ', $data['deposit_id']);
		}
		$this->db->limit(1);
		$query = $this->db->get('deposits');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();

		if(empty($result)){
			$this->db->where('transfer_type', TRANSFER_ADJUST_IN);
			$this->db->where('username', $data['username']);
			$this->db->limit(1);
			$query = $this->db->get('cash_transfer_report');
			if($query->num_rows() > 0)
			{
				$result = $query->row_array(); 
			}
			$query->free_result();
		}
		
		return $result;
	}

	public function get_success_deposit_data_today($data = NULL){
		$start_time = strtotime(date("Y-m-d 00:00:00"));
		$end_time = strtotime(date("Y-m-d 23:59:59"));
		$result = NULL;
		

		$this->db->where('status', STATUS_COMPLETE);
		$this->db->where('player_id', $data['player_id']);
		if(isset($data['deposit_id']) && !empty($data['deposit_id'])){
			$this->db->where('deposit_id != ', $data['deposit_id']);
		}
		$this->db->where('updated_date >= ',$start_time);
		$this->db->where('updated_date <= ',$end_time);
		$this->db->limit(1);
		$query = $this->db->get('deposits');
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		$query->free_result();

		//Manual deposit
		if(empty($result)){
			$this->db->where('transfer_type', TRANSFER_ADJUST_IN);
			$this->db->where('username', $data['username']);
			$this->db->where('report_date >= ',$start_time);
			$this->db->where('report_date <= ',$end_time);
			$this->db->limit(1);
			$query = $this->db->get('cash_transfer_report');
			if($query->num_rows() > 0)
			{
				$result = $query->row_array(); 
			}
			$query->free_result();
		}
		return $result;
	}

	public function deposit_promotion_approve_decision($data  = NULL,$balanceData = NULL){
		$result = array(
			'status' => EXIT_ERROR,
			'code' => DEPOSIT_PROMOTION_UNKNOWN_ERROR,
			'msg' => $this->lang->line('error_system_error'), 	
		);
		$allow_approve_promotion = TRUE;

		//Checking either deposit promotion is available
		if($allow_approve_promotion){
			if(empty($data)){
				$allow_approve_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_PENDING_NOT_EXITS;	
				$result['msg'] = $this->lang->line('error_promotion_pending_not_exits');
			}
		}
		
		//Checking either promotion is available
		if($allow_approve_promotion){
			$promotionData = $this->get_player_deposit_promotion_available_by_id_approve($data);
			if(empty($promotionData)){
				$allow_approve_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_AVAILABLE;	
				$result['msg'] = $this->lang->line('error_promotion_not_available');
			}
		}
		/*
		if($allow_approve_promotion){
			$promotionPendingData = $this->get_player_pending_deposit_promotion_approve($data,$promotionData);
			if(!empty($promotionPendingData)){
				$allow_approve_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_PENDING_EXITS;	
				$result['msg'] = $this->lang->line('error_promotion_pending_exits');
			}
		}
		*/

		//Checking promotion valid expirate date
		if($allow_approve_promotion){
			if($promotionData['date_expirate_type'] != "0"){
				$expirateDate = $this->deposit_promotion_check_expirate_date_approve($data,$promotionData);
				if(!empty($expirateDate)){
					$allow_approve_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_REACH_EXPIRATE_DATE;
					$result['msg'] = $this->lang->line('error_promotion_not_pass_expirate_date');	
				}
			}
		}

		//Checking claim limit
		if($allow_approve_promotion){
			if($promotionData['times_limit_type'] != PROMOTION_TIMES_LIMIT_TYPE_NO_LIMIT){
				$claimLimit = $this->deposit_promotion_check_claim_limit_approve($data,$promotionData);
				if(!empty($claimLimit)){
					$allow_approve_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_PROMOTION_REACH_CLAIM_LIMIT;
					$result['msg'] = $this->lang->line('error_promotion_exceed_claim_limit');
				}
			}
		}

		//Check First Deposit
		if($allow_approve_promotion){
			if($promotionData['first_deposit'] == STATUS_ACTIVE){
				$depositData = $this->get_success_deposit_data($data);
				if(!empty($depositData)){
					$allow_approve_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_FIRST_DEPOSIT;
					$result['msg'] = $this->lang->line('error_promotion_first_deposit');
				}
			}
		}

		//check daily first deposit
		if($allow_approve_promotion){
			if($promotionData['daily_first_deposit'] == STATUS_ACTIVE){
				$depositTodayData = $this->get_success_deposit_data_today($data);
				if(!empty($depositTodayData)){
					$allow_approve_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_DAILY_FIRST_DEPOSIT;
					$result['msg'] = $this->lang->line('error_promotion_first_deposit_daily');
				}
			}
		}

		if($allow_approve_promotion == TRUE){
			$result['status'] = EXIT_SUCCESS;
			$result['code'] = DEPOSIT_PROMOTION_SUCCESSS;
			$result['msg'] = $this->lang->line('error_success');
		}
		return $result;
	}

	public function update_deposit_promotion_status($arr = NULL,$promotion_status = NULL)
	{	
		$DBdata = array(
			'promotion_status' => $promotion_status,
		);
		$this->db->where('deposit_id', $arr['deposit_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->limit(1);
		$this->db->update($this->table_deposits, $DBdata);
		return $DBdata;
	}

	public function update_player_promotion_after_deposit($arr = NULL,$balanceData = NULL,$depositAmount = NULL,$type = NULL){
		$starting_date = $this->promotion_starting_date_decision($arr);
		$balance_amount = (isset($balanceData['balance_amount'])?$balanceData['balance_amount']:"0");
		if(!empty($type)){
			$original_amount = $balance_amount - $depositAmount;
		}else{
			$original_amount = $balance_amount;
		}
		
		$DBdata = array(
			'original_amount' => $original_amount,
			'status' => STATUS_ENTITLEMENT,
			'starting_date' => $starting_date,
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);

		$this->db->where('player_promotion_id', $arr['player_promotion_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->where('status', STATUS_PENDING);
		$this->db->limit(1);
		$this->db->update($this->table_player_promotion, $DBdata);
		$data['original_amount'] = $DBdata['original_amount'];
		$data['starting_date'] = $DBdata['starting_date'];
		$data['status'] = $DBdata['status'];
		$data['updated_by'] = $DBdata['updated_by'];
		$data['updated_date'] = $DBdata['updated_date'];
		return $data;
	}

	public function promotion_approve_decision($data  = NULL,$balanceData = NULL){
		$result = array(
			'status' => EXIT_ERROR,
			'code' => DEPOSIT_PROMOTION_UNKNOWN_ERROR,
			'msg' => $this->lang->line('error_system_error'), 	
		);
		$allow_approve_promotion = TRUE;

		//Checking either deposit promotion is available
		if($allow_approve_promotion){
			if(empty($data)){
				$allow_approve_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_PENDING_NOT_EXITS;	
				$result['msg'] = $this->lang->line('error_promotion_pending_not_exits');
			}
		}
		
		//Checking either promotion is available
		if($allow_approve_promotion){
			$promotionData = $this->get_player_deposit_promotion_available_by_id_approve($data);
			if(empty($promotionData)){
				$allow_approve_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_AVAILABLE;	
				$result['msg'] = $this->lang->line('error_promotion_not_available');
			}
		}
		/*
		if($allow_approve_promotion){
			$promotionPendingData = $this->get_player_pending_deposit_promotion_approve($data,$promotionData);
			if(!empty($promotionPendingData)){
				$allow_approve_promotion = FALSE;
				$result['code'] = DEPOSIT_PROMOTION_PROMOTION_PENDING_EXITS;	
				$result['msg'] = $this->lang->line('error_promotion_pending_exits');
			}
		}
		*/

		//Checking promotion valid expirate date
		if($allow_approve_promotion){
			if($promotionData['date_expirate_type'] != "0"){
				$expirateDate = $this->deposit_promotion_check_expirate_date_approve($data,$promotionData);
				if(!empty($expirateDate)){
					$allow_approve_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_PROMOTION_NOT_REACH_EXPIRATE_DATE;
					$result['msg'] = $this->lang->line('error_promotion_not_pass_expirate_date');	
				}
			}
		}

		//Checking claim limit
		if($allow_approve_promotion){
			if($promotionData['times_limit_type'] != PROMOTION_TIMES_LIMIT_TYPE_NO_LIMIT){
				$claimLimit = $this->deposit_promotion_check_claim_limit_approve($data,$promotionData);
				if(!empty($claimLimit)){
					$allow_approve_promotion = FALSE;
					$result['code'] = DEPOSIT_PROMOTION_PROMOTION_REACH_CLAIM_LIMIT;
					$result['msg'] = $this->lang->line('error_promotion_exceed_claim_limit');
				}
			}
		}

		if($allow_approve_promotion == TRUE){
			$result['status'] = EXIT_SUCCESS;
			$result['code'] = DEPOSIT_PROMOTION_SUCCESSS;
			$result['msg'] = $this->lang->line('error_success');
		}
		return $result;
	}

	public function force_bulk_cancel_player_promotion($player_id = NULL, $remark = null){
		$result = NULL;
		$this->db->select('player_promotion_id,remark');
		$this->db->where('player_id',$player_id);
		$this->db->where_in('status', array(STATUS_ENTITLEMENT,STATUS_ACCOMPLISH));
		$query = $this->db->get($this->table_player_promotion);
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		$query->free_result();

		if(!empty($result)){
			foreach($result as $row){
				if(!empty($row['remark'])){
					$text_remark = $row['remark']."<br>".$remark;
				}else{
					$text_remark = $remark;
				}
				
				$DBdata = array(
					'status' => STATUS_CANCEL,
					'remark' => $text_remark,
					'complete_date' => time(),
					'updated_by' => '',
					'updated_date' => time()
				);
				$this->db->where('player_promotion_id', $row['player_promotion_id']);
				$this->db->where('player_id', $player_id);
				$this->db->limit(1);
				$this->db->update($this->table_player_promotion, $DBdata);
			}
		}
	}
}