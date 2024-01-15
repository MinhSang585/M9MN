<?php
class Report_model extends CI_Model {
    protected $table_total_win_loss_report = "total_win_loss_report";
    protected $table_total_win_loss_report_month = "total_win_loss_report_month";
	protected $table_win_loss_report = "win_loss_report";
    
    public function insert_total_win_loss_report($player_id = NULL, $points = NULL, $type = NULL, $time = NULL){
		$DBdata = array(
			'report_date' => strtotime(date('Y-M-d 00:00:00',$time)),
			'player_id' => $player_id,
		);
		$table = $this->db->dbprefix . $this->table_total_win_loss_report;
		if($type == 1 OR $type == 5 OR $type == 6 OR $type == 16  OR $type == 17){
			$DBdata['deposit_amount'] = $points;
			$this->db->query("UPDATE {$table} SET deposit_amount = (deposit_amount + ?) WHERE player_id = ? AND report_date = ? LIMIT 1", array($DBdata['deposit_amount'], $DBdata['player_id'], $DBdata['report_date']));
		}else if($type == 9){
			$DBdata['promotion_amount'] = $points;
			$this->db->query("UPDATE {$table} SET promotion_amount = (promotion_amount + ?) WHERE player_id = ? AND report_date = ? LIMIT 1", array($DBdata['promotion_amount'], $DBdata['player_id'], $DBdata['report_date']));
		}else if($type == 10){
			$DBdata['bonus_amount'] = $points;
			$this->db->query("UPDATE {$table} SET bonus_amount = (bonus_amount + ?) WHERE player_id = ? AND report_date = ? LIMIT 1", array($DBdata['bonus_amount'], $DBdata['player_id'], $DBdata['report_date']));
		}else if($type == 2 OR $type == 7){
			$DBdata['withdrawals_amount'] = $points;
			$this->db->query("UPDATE {$table} SET withdrawals_amount = (withdrawals_amount + ?) WHERE player_id = ? AND report_date = ? LIMIT 1", array($DBdata['withdrawals_amount'], $DBdata['player_id'], $DBdata['report_date']));
		}
		$afftectedRows = $this->db->affected_rows();
		if($afftectedRows == 0){
			$this->db->insert($this->table_total_win_loss_report, $DBdata);
		}
	}
	
	public function insert_total_win_loss_report_dwa($arr = NULL){
		$table = $this->db->dbprefix . $this->table_total_win_loss_report;
		$this->db->query("UPDATE {$table} SET deposit_amount = (deposit_amount + ?), deposit_offline_amount = (deposit_offline_amount + ?), deposit_online_amount = (deposit_online_amount + ?), deposit_online_online_amount = (deposit_online_online_amount + ?), deposit_online_credit_amount = (deposit_online_credit_amount + ?), deposit_online_hypermart_amount = (deposit_online_hypermart_amount + ?), deposit_point_amount = (deposit_point_amount + ?), withdrawals_amount = (withdrawals_amount + ?), withdrawals_online_amount = (withdrawals_online_amount + ?), withdrawals_offline_amount = (withdrawals_offline_amount + ?), withdrawals_point_amount = (withdrawals_point_amount + ?), adjust_amount = (adjust_amount + ?), adjust_in_amount = (adjust_in_amount + ?), adjust_out_amount = (adjust_out_amount + ?), bonus_amount = (bonus_amount + ?), promotion_amount = (promotion_amount + ?) WHERE player_id = ? AND report_date = ? LIMIT 1", array($arr['deposit_amount'], $arr['deposit_offline_amount'], $arr['deposit_online_amount'], $arr['deposit_online_online_amount'], $arr['deposit_online_credit_amount'], $arr['deposit_online_hypermart_amount'], $arr['deposit_point_amount'], $arr['withdrawals_amount'], $arr['withdrawals_online_amount'], $arr['withdrawals_offline_amount'], $arr['withdrawals_point_amount'], $arr['adjust_amount'], $arr['adjust_in_amount'], $arr['adjust_out_amount'], $arr['bonus_amount'], $arr['promotion_amount'], $arr['player_id'], $arr['report_date']));
		$afftectedRows = $this->db->affected_rows();
		if($afftectedRows == 0){
			$this->db->insert($this->table_total_win_loss_report, $arr);
		}
	}
	
	public function insert_total_win_loss_report_monthly_dwa($arr = NULL){
		$table = $this->db->dbprefix . $this->table_total_win_loss_report_month;
		$this->db->query("UPDATE {$table} SET deposit_count = (deposit_count + ?), deposit_amount = (deposit_amount + ?), withdrawals_amount = (withdrawals_amount + ?), point_in_amount = (point_in_amount + ?), point_out_amount = (point_out_amount + ?), bonus_amount = (bonus_amount + ?), promotion_amount = (promotion_amount + ?) WHERE player_id = ? AND report_date = ? LIMIT 1", array($arr['deposit_count'],$arr['deposit_amount'], $arr['withdrawals_amount'], $arr['point_in_amount'], $arr['point_out_amount'], $arr['bonus_amount'], $arr['promotion_amount'], $arr['player_id'], $arr['report_date']));
		$afftectedRows = $this->db->affected_rows();
		if($afftectedRows == 0){
			$this->db->insert($this->table_total_win_loss_report_month, $arr);
		}
	}
	
	public function add_win_loss($arr = NULL){
		$table = $this->db->dbprefix . $this->table_win_loss_report;
		$DBdata = array(
			'report_date' => $arr['payout_time'],
			'game_provider_code' => $arr['game_provider_code'],
			'game_type_code' => $arr['game_type_code'],
			'game_provider_type_code' => $arr['game_provider_type_code'],
			'player_id' => $arr['player_id'],
			'total_bet' => $arr['total_bet'],
			'bet_amount' => $arr['bet_amount'],
			'bet_amount_valid' => $arr['bet_amount_valid'],
			'win_loss' => $arr['win_loss'],
		);
		$this->db->query("UPDATE {$table} SET total_bet = (total_bet + ?), bet_amount = (bet_amount + ?), bet_amount_valid = (bet_amount_valid + ?), win_loss = (win_loss + ?)  WHERE player_id = ? AND game_provider_code = ? AND game_type_code = ? AND game_provider_type_code = ? AND report_date = ? LIMIT 1", array($DBdata['total_bet'], $DBdata['bet_amount'], $DBdata['bet_amount_valid'], $DBdata['win_loss'], $DBdata['player_id'], $DBdata['game_provider_code'], $DBdata['game_type_code'], $DBdata['game_provider_type_code'], $DBdata['report_date']));
		$afftectedRows = $this->db->affected_rows();
		if($afftectedRows == 0){
			$this->db->insert($this->table_win_loss_report, $DBdata);
		}
	}

	public function add_total_win_loss($arr = NULL){
		$table = $this->db->dbprefix . $this->table_total_win_loss_report;
		$DBdata = array(
			'report_date' => $arr['payout_time'],
			'player_id' => $arr['player_id'],
			'total_bet' => $arr['total_bet'],
			'bet_amount' => $arr['bet_amount'],
			'bet_amount_valid' => $arr['bet_amount_valid'],
			'win_loss' => $arr['win_loss'],
		);
		$this->db->query("UPDATE {$table} SET total_bet = (total_bet + ?), bet_amount = (bet_amount + ?), bet_amount_valid = (bet_amount_valid + ?), win_loss = (win_loss + ?)  WHERE player_id = ? AND report_date = ? LIMIT 1", array($DBdata['total_bet'], $DBdata['bet_amount'], $DBdata['bet_amount_valid'], $DBdata['win_loss'], $DBdata['player_id'], $DBdata['report_date']));
		$afftectedRows = $this->db->affected_rows();
		if($afftectedRows == 0){
			$this->db->insert($this->table_total_win_loss_report, $DBdata);
		}
	}
	
	public function add_total_win_loss_monthly($arr = NULL){
		$table = $this->db->dbprefix . $this->table_total_win_loss_report_month;
		$DBdata = array(
			'report_date' => $arr['payout_time'],
			'player_id' => $arr['player_id'],
			'bet_amount_valid' => $arr['bet_amount_valid'],
			'win_loss' => $arr['win_loss'],
		);
		$this->db->query("UPDATE {$table} SET bet_amount_valid = (bet_amount_valid + ?), win_loss = (win_loss + ?)  WHERE player_id = ? AND report_date = ? LIMIT 1", array($DBdata['bet_amount_valid'], $DBdata['win_loss'], $DBdata['player_id'], $DBdata['report_date']));
		$afftectedRows = $this->db->affected_rows();
		if($afftectedRows == 0){
			$this->db->insert($this->table_total_win_loss_report_month, $DBdata);
		}
	}
	
    public function get_player_data_risk_management(){
		$result = array();
		$query = $this
				->db
				->select('player_id, username, sb_max_win_loss_suspend_limit, sb_min_win_loss_suspend_limit, lc_max_win_loss_suspend_limit, lc_min_win_loss_suspend_limit, sl_max_win_loss_suspend_limit, sl_min_win_loss_suspend_limit')
				->where('active',STATUS_ACTIVE)
				->get('players');
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
		}
		$query->free_result();
		return $result;
	}

	public function get_player_total_win_loss($player_id = NULL,$type = NULL,$start_time = NULL,$end_time = NULL){
		$result = 0;
		$this->db->select_sum('transaction_report.win_loss','current_amount');
		$this->db->where('transaction_report.status', STATUS_COMPLETE);
		$this->db->where('transaction_report.game_type_code', $type);
		$this->db->where('transaction_report.player_id', $player_id);
		$query = $this->db->get('transaction_report');
		if($query->num_rows() > 0)
		{
			$result_data = $query->row_array();
			if(!empty($result_data['current_amount'])){
				$result = $result_data['current_amount'];
			}
		}
		$query->free_result();
		
		return $result;
	}

	public function update_player_status($player_id = NULL,$status = NULL){
		$DBdata = array(
			'active' => $status,
		);
		$this->db->where('player_id', $player_id);
		$this->db->limit(1);
		$this->db->update('players', $DBdata);
	}
	
	public function get_game_result($game_provider_code = NULL,$game_result_type = NULL,$game_sync_type = NULL){
		$result = NULL;
		$query = $this
				->db
				->select('api_data,sync_lock,sync_backup_lock,sync_manual_lock,sync_manual_allow')
				->where('game_provider_code', $game_provider_code)
				->where('game_result_type',$game_result_type)
				->where('game_sync_type',$game_sync_type)
				->limit(1)
				->get('game_result');
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}
	
	public function get_game_data($game_code = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('api_data')
				->where('game_code', $game_code)
				->limit(1)
				->get('games');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function get_wager_game_data($game_code = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('api_data')
				->where('game_code', $game_code)
				->where('wager_active',STATUS_ACTIVE)
				->limit(1)
				->get('games');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_game_result_logs($game_provider_code = NULL,$game_result_type = NULL,$game_sync_type = NULL,$game_id = NULL){
		$result = NULL;
		$query = $this
				->db
				->select('start_time,end_time,next_id')
				->where('game_provider_code', $game_provider_code)
				->where('game_result_type',$game_result_type)
				->where('game_sync_type',$game_sync_type)
				->where('game_id',$game_id)
				->where('sync_status',STATUS_ACTIVE)
				->order_by('game_result_log_id', 'DESC')
				->limit(1)
				->get('game_result_logs');
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}
	
	public function get_game_result_sync_time_logs($game_provider_code = NULL,$game_result_type = NULL,$game_sync_type = NULL,$game_id = NULL){
		$result = NULL;
		$query = $this
				->db
				->select('start_time,end_time,next_id')
				->where('game_provider_code', $game_provider_code)
				->where('game_result_type',$game_result_type)
				->where('game_sync_type',$game_sync_type)
				->where('game_id',$game_id)
				->order_by('game_result_log_id', 'DESC')
				->limit(1)
				->get('game_result_logs');
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}
	
	public function get_game_result_success_logs($game_provider_code = NULL,$game_result_type = NULL,$game_sync_type = NULL){
		$result = NULL;
		$query = $this
				->db
				->select('start_time,end_time,next_id')
				->where('game_provider_code', $game_provider_code)
				->where('game_result_type',$game_result_type)
				->where('game_sync_type',$game_sync_type)
				->where('sync_status',STATUS_ACTIVE)
				->order_by('game_result_log_id', 'DESC')
				->limit(1)
				->get('game_result_logs');
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}

	public function get_game_result_backup_logs($game_provider_code = NULL,$game_result_type = NULL,$game_sync_type = NULL){
		$result = NULL;
		$query = $this
				->db
				->select('start_time,end_time,next_id')
				->where('game_provider_code', $game_provider_code)
				->where('game_result_type',$game_result_type)
				->where('game_sync_type',$game_sync_type)
				->where('sync_status',STATUS_ACTIVE)
				->order_by('game_result_log_id', 'DESC')
				->limit(1)
				->get('game_result_backup_logs');
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}

	public function update_sync_lock($game_provider_code = NULL,$game_result_type = NULL,$game_sync_type = NULL,$sync_type = NULL, $sync_status = NULL){
		if($sync_type == SYNC_BACKUP){
			$result = array('sync_backup_lock' => $sync_status);
		}else if($sync_type == SYNC_MANUAL){
			$result = array('sync_manual_lock' => $sync_status);
		}else if($sync_type == SYNC_BACKUP_SECOND){
			$result = array('sync_backup_second_lock' => $sync_status);
		}else{
			$result = array('sync_lock' => $sync_status);
		}

		$this->db->where('game_provider_code', $game_provider_code);
		$this->db->where('game_result_type', $game_result_type);
		$this->db->where('game_sync_type', $game_sync_type);
		$this->db->limit(1);
		$this->db->update('game_result', $result);
	}

	public function get_transaction_list_array($game_provider_code = NULL,$game_result_type = NULL,$time_type = NULL, $start_time = NULL, $end_time = NULL){
		$lists = array();
		
		$this->db->select('bet_id');
		$this->db->where('game_provider_code', $game_provider_code);
		$this->db->where('game_result_type', $game_result_type);
		if($time_type == TIME_BET){
			$this->db->where('bet_time >=', $start_time);
			$this->db->where('bet_time <=', $end_time);
		}else if($time_type == TIME_GAME){
			$this->db->where('game_time >=', $start_time);
			$this->db->where('game_time <=', $end_time);
		}else if($time_type == TIME_REPORT){
			$this->db->where('report_time >=', $start_time);
			$this->db->where('report_time <=', $end_time);
		}else{
			$this->db->where('payout_time >=', $start_time);
			$this->db->where('payout_time <=', $end_time);
		}
		$query = $this->db->get('transaction_report');
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row) {
				array_push($lists, $row->bet_id);						
			}
		}
		$query->free_result();
		return $lists;
	}

	public function update_transaction_record($game_provider_code = NULL,$game_result_type = NULL,$update_type = NULL, $start_time = NULL, $end_time = NULL, $bet_id = NULL,$result = array()){
		$this->db->where('game_provider_code', $game_provider_code);
		$this->db->where('game_result_type', $game_result_type);
		$this->db->where('bet_id', $bet_id);
		if($update_type == UPDATE_TYPE_PAYOUT_TIME){
			$this->db->where('payout_time >=', $start_time);
			$this->db->where('payout_time <=', $end_time);
		}else if($update_type == UPDATE_TYPE_GAME_TIME){
			$this->db->where('game_time >=', $start_time);
			$this->db->where('game_time <=', $end_time);
		}else if($update_type == UPDATE_TYPE_REPORT_TIME){
			$this->db->where('report_time >=', $start_time);
			$this->db->where('report_time <=', $end_time);
		}else if($update_type == UPDATE_TYPE_CMD){
			$this->db->where('payout_time >=', $start_time);
			$this->db->where('payout_time <=', $end_time);
			$this->db->where('bet_ref_no <', $result['bet_ref_no']);
		}else if($update_type == UPDATE_TYPE_BET_TIME){
			$this->db->where('bet_time >=', $start_time);
			$this->db->where('bet_time <=', $end_time);
		}else{
			//UPDATE_TYPE_DEFAULT
		}
		$this->db->limit(1);
		$this->db->update('transaction_report', $result);
	}

	public function get_transaction_record_old($game_provider_code = NULL, $game_result_type = NULL,$update_type = NULL,  $start_time = NULL, $end_time = NULL, $BUIDdata = NULL){
	    $lists = array();
	    $this->db->select('bet_id,game_code,player_id,game_provider_code,game_type_code,bet_time,payout_time,bet_amount,bet_amount_valid,win_loss,status');
	    $this->db->where('game_provider_code', $game_provider_code);
		$this->db->where('game_result_type', $game_result_type);
		if($update_type == UPDATE_TYPE_PAYOUT_TIME){
			$this->db->where('payout_time >=', $start_time);
			$this->db->where('payout_time <=', $end_time);
		}else if($update_type == UPDATE_TYPE_GAME_TIME){
			$this->db->where('game_time >=', $start_time);
			$this->db->where('game_time <=', $end_time);
		}else if($update_type == UPDATE_TYPE_REPORT_TIME){
			$this->db->where('report_time >=', $start_time);
			$this->db->where('report_time <=', $end_time);
		}else if($update_type == UPDATE_TYPE_CMD){
			$this->db->where('payout_time >=', $start_time);
			$this->db->where('payout_time <=', $end_time);
			$this->db->where('bet_ref_no <', $result['bet_ref_no']);
		}else if($update_type == UPDATE_TYPE_BET_TIME){
			$this->db->where('bet_time >=', $start_time);
			$this->db->where('bet_time <=', $end_time);
		}else{
			//UPDATE_TYPE_DEFAULT
		}
		$this->db->where_in('bet_id', $BUIDdata);
		$query = $this->db->get('transaction_report');
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row) {
			    $lists[$row->bet_id] = array(
			        'player_id' => $row->player_id,
			        'game_code' => $row->game_code,
			        'game_provider_code' => $row->game_provider_code,
			        'game_type_code' => $row->game_type_code,
			        'payout_time' => $row->payout_time,
			        'bet_time' => $row->bet_time,
			        'bet_amount' => $row->bet_amount,
			        'bet_amount_valid' => $row->bet_amount_valid,
			        'win_loss' => $row->win_loss,
			        'status' => $row->status,
			    );						
			}
		}
		$query->free_result();
		return $lists;
	}

	public function update_promotion_amount($data = NULL,$result = NULL){
		$this->db->where('game_provider_code',$data['game_provider_code']);
		$this->db->where('game_type_code',$data['game_type_code']);
		$this->db->where('game_real_code',$data['game_real_code']);
		$this->db->where('table_id',$data['table_id']);
		$this->db->where('round',$data['round']);
		$this->db->where('subround',$data['subround']);
		$this->db->where('game_username',$data['game_username']);
		$this->db->where('bet_id != ',$data['bet_id']);
		$query = $this->db->get('transaction_report');
		if($data['game_code'] == "Roulette"){
			if($query->num_rows() > 25){
				$this->db->where('game_provider_code',$data['game_provider_code']);
				$this->db->where('game_type_code',$data['game_type_code']);
				$this->db->where('game_real_code',$data['game_real_code']);
				$this->db->where('table_id',$data['table_id']);
				$this->db->where('round',$data['round']);
				$this->db->where('subround',$data['subround']);
				$this->db->where('game_username',$data['game_username']);
				$this->db->update('transaction_report', $result);
			}
		}else{
			if($query->num_rows() > 0){
				$this->db->where('game_provider_code',$data['game_provider_code']);
				$this->db->where('game_type_code',$data['game_type_code']);
				$this->db->where('game_real_code',$data['game_real_code']);
				$this->db->where('table_id',$data['table_id']);
				$this->db->where('round',$data['round']);
				$this->db->where('subround',$data['subround']);
				$this->db->where('game_username',$data['game_username']);
				$this->db->update('transaction_report', $result);
			}
		}
		$query->free_result();
	}
	
	public function mg_game_list(){
		$game_list = array(
			'SMG_108heroesMultiplierFortunes' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'3 Reel Slot Games'),
			'SMG_breakDaBank' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'3 Reel Slot Games'),
			'SMG_cashCrazy' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'3 Reel Slot Games'),
			'SMG_couchPotato' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'3 Reel Slot Games'),
			'SMG_diamondEmpire' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'3 Reel Slot Games'),
			'SMG_doubleWammy' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'3 Reel Slot Games'),
			'SMG_luckyTwinsJackpot' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'3 Reel Slot Games'),
			'SMG_megaMoneyMultiplier' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'3 Reel Slot Games'),
			'SMG_wackyPanda' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'3 Reel Slot Games'),
			'SMG_shogunofTime' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'4 Reel Slot Games'),
			'SMG_breakAwayUltra' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_emperorOfTheSeaDeluxe' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_ingotsOfCaiShen' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_assassinMoon' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_augustus' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_5ReelDrive' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_777RoyalWheel' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_9masksOfFire' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_9potsOfGold' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_aDarkMatter' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_aTaleOfElves' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_actionOpsSnowAndSable' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_adventurePalace' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_ageOfConquest' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_agentJaneBlonde' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_agentjaneblondereturns' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_ancientFortunesZeus' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_ariana' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_auroraWilds' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_avalon' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_badmintonHero' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_bananaOdyssey' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_barBarBlackSheep5Reel' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_basketballStar' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_basketballStarDeluxe' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_basketballStaronFire' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_beachBabes' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_beautifulBones' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_bigTop' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_bikiniParty' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_boatofFortune' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_bookOfOz' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_bookOfOzLockNSpin' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_bookieOfOdds' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_breakAway' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_breakAwayDeluxe' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_breakAwayLuckyWilds' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_BreakAwayV90' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_breakDaBankAgain' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_breakDaBankAgainRespin' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_bridesmaids' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_burningDesire' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_bustTheBank' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_carnaval' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_cashOfKingdoms' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_cashapillar' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_cashoccino' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_castleBuilder2' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_centreCourt' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_classic243' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_coolBuck5Reel' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_coolWolf' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_crazyChameleons' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_cricketStar' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_CrystalRift' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_deadmau5' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_deckTheHalls' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_decoDiamonds' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_diamondKingJackpots' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_dolphinCoast' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_dolphinQuest' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_dragonDance' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_dragonShard' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_dragonz' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_dreamDate' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_emotiCoins' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_emperorOfTheSea' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_exoticCats' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_fishParty' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_footballStar' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_footballStarDeluxe' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_forbiddenThrone' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_fortuneGirl' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_frozenDiamonds' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_fruitVSCandy' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_giantRiches' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_girlsWithGunsJungleHeat' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_gnomeWood' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_goldaurGuardians' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_goldenPrincess' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_gopherGold' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_HappyHolidays' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_harveys' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_highSociety' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_highlander' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_hollyJollyPenguins' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_huangdiTheYellowEmperor' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_immortalRomance' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_ImmortalRomancev90' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_isis' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_jungleJimElDorado' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_jungleJimAndTheLostSphinx' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_karaokeParty' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_kathmandu' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_kingTusk' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_KittyCabana' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_ladiesNite' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_ladiesNite2TurnWild' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_LadiesNiteV90' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_ladyInRed' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_laraCroftTemplesAndTombs' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_LegendOftheMoonLovers' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_lifeOfRiches' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_lionsPride' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_liquidGold' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_longMuFortunes' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_lostVegas' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_luchaLegends' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_luckyBachelors' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_luckyfirecracker' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_luckyKoi' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_luckyLeprechaun' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_luckyLittleGods' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_luckyRichesHyperspins' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_luckyTwins' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_luckyZodiac' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_magicOfSahara' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_mayanPrincess' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_mobyDickOnlineSlot' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_monsterWheels' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_munchkins' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_mysticDreams' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_neptunesRichesOceanOfWilds' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_nobleSky' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_oinkCountryLove' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_ourDaysA' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_partyIsland' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_peekABoo5Reel' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_playboy' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_playboyFortunes' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_playboyGoldJackpots' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_prettyKitty' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_rabbitinthehat' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_reelGems' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_reelGemsDeluxe' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_reelSpinner' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_reelStrike' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_ReelTalent' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_reelThunder' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_relicSeekers' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_retroReels' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_retroReelsDiamondGlitz' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_retroReelsExtremeHeat' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_rhymingReelsGeorgiePorgie' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_rhymingReelsHeartsAndTarts' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_robinOfSherwoodOnlineSlot' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_romanovRiches' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_rugbyStar' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_rugbyStarDeluxe' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_santaPaws' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_secretAdmirer' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_secretRomance' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_shanghaiBeauty' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_sherlockOfLondonOnlineSlot' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_showdownSaloon' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_silverFang' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_silverLioness4x' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_sistersofOzJackpots' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_sixAcrobats' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_springBreak' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_stardust' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_stashOfTheTitans' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_sterlingSilver' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_summertime' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_sunQuest' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_sunTide' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_supeItUp' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_tallyHo' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_tarzanAndtheJewelsofOpar' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_tastyStreet' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_theFinerReelsOfLife' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_theGrandJourney' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_theGreatAlbini' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_theHeatIsOn' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_theRatPack' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_thunderstruck' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_tigersEye' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_tikiVikings' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_titansOfTheSunHyperion' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_titansOfTheSunTheia' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_treasurePalace' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_treasuresOfLionCity' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_untamedGiantPanda' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_vinylCountdown' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_voila' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_wantedOutlaws' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_westernGold' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_whatAHoot' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_wickedTalesDarkRed' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_wildCatchNew' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_wildOrient' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_wildScarabs' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_winSumDimSum' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_zombieHoard' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5 Reel Slot Games'),
			'SMG_boomPirates' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'5-9 Reel Slot Games'),
			'SMG_silverbackMultiplierMountain' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'6 Reel Slot Games'),
			'SMG_shamrockHolmes' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'6 Reel Slot Games'),
			'SMG_alchemistStone' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'6 Reel Slot Games'),
			'SMG_playboyGold' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'6 Reel Slot Games'),
			'SMG_queenOfTheCrystalRays' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'6 Reel Slot Games'),
			'SMG_tikiReward' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'6 Reel Slot Games'),
			'SMG_villagePeople' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'6 Reel Slot Games'),
			'SMG_alchemyFortunes' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'7 Reel Slot Games'),
			'SMG_pingPongStar' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'7 Reel Slot Games'),
			'SMG_alchemyBlast' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Arcade'),
			'SMG_galaxyGlider' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Arcade'),
			'SMG_hippieDays' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Arcade'),
			'SMG_incanAdventure' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Arcade'),
			'SMG_theIncredibleBalloonMachine' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Arcade'),
			'SMG_luckyTwinsCatcher' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Arcade'),
			'SMG_monsterBlast' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Arcade'),
			'SMG_titaniumLiveGames_Baccarat_Playboy' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
			'SMG_titaniumLiveGames_BaccaratplayboyNC' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
			'SMG_switchBaccarat' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
			'SMG_titaniumLiveGames_BaccaratNC' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
			'SMG_titaniumLiveGames_Baccarat' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
			'SMG_titaniumLiveGames_Hollywood_Baccarat' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
			'SMG_titaniumLiveGames_MP_Baccarat' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
			'SMG_titaniumLiveGames_MP_Baccarat_Playboy' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Baccarat'),
			'SMG_switchAtlanticCityBlackjack' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
			'SMG_atlanticCityBlackjackGold' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
			'SMG_switchClassicBlackjack' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
			'SMG_classicBlackjackGold' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
			'SMG_switchEuropeanBlackjack' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
			'SMG_europeanBlackjackGold' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
			'SMG_switchVegasDowntownBlackjack' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
			'SMG_vegasDowntownBlackjackGold' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
			'SMG_switchVegasSingleDeckBlackjack' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
			'SMG_vegasSingleDeckBlackjackGold' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
			'SMG_switchVegasStripBlackjack' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
			'SMG_vegasStripBlackjackGold' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Blackjack'),
			'SMG_108Heroes' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_ageOfDiscovery' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_alaskanFishing' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_asianBeauty' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_BarsAndStripes' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_bigKahuna' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_boogieMonsters' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_bullseyeGameshow' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_bushTelegraph' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_candyDreams' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_cashville' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_eaglesWings' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_fortunium' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_goldFactory' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_goldenEra' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_halloween' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_halloweenies' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_hitman' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_HoundHotel' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_jurassicWorld' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_kingsOfCash' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_loaded' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_madHatters' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_mermaidsMillions' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_pistoleras' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_pollenParty' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_purePlatinum' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_rivieraRiches' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_santasWildRide' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_scrooge' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_soManyMonsters' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_soMuchCandy' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_soMuchSushi' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_starlightKiss' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_sugarParade' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_summerHoliday' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_sureWin' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_tarzan' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_thePhantomOfTheOpera' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_theTwistedCircus' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_thunderstruck2' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_tombRaider' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SMG_RubyTombRaiderII' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Bonus Screen Slots'),
			'SFG_WDFuWaFishing' => array('game_type_code'=>GAME_FISHING,'game_code'=>'Fishing'),
			'SMG_gemsAndDragons' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'HyperClusters'),
			'SVS_Instant_Football' => array('game_type_code'=>GAME_VIRTUAL_SPORTS,'game_code'=>'Instant Sports'),
			'SVS_instant_greyhounds' => array('game_type_code'=>GAME_VIRTUAL_SPORTS,'game_code'=>'Instant Sports'),
			'SVS_instant_horses' => array('game_type_code'=>GAME_VIRTUAL_SPORTS,'game_code'=>'Instant Sports'),
			'SVS_instant_speedway' => array('game_type_code'=>GAME_VIRTUAL_SPORTS,'game_code'=>'Instant Sports'),
			'SVS_instant_trotting' => array('game_type_code'=>GAME_VIRTUAL_SPORTS,'game_code'=>'Instant Sports'),
			'SVS_instant_velodrome' => array('game_type_code'=>GAME_VIRTUAL_SPORTS,'game_code'=>'Instant Sports'),
			'SMG_astroLegendsLyraandErion' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Interactive Games'),
			'SMG_battleRoyale' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Interactive Games'),
			'SMG_fruitBlast' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Interactive Games'),
			'SMG_gemsOdyssey' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Interactive Games'),
			'SMG_happyMonsterClaw' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Interactive Games'),
			'SMG_jewelQuestRiches' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Interactive Games'),
			'SMG_maxDamageArcade' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Interactive Games'),
			'SMG_megaMoneyRush' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Interactive Games'),
			'SMG_petsGoWild' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Interactive Games'),
			'SMG_pokeTheGuy' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Interactive Games'),
			'SMG_treasureDash' => array('game_type_code'=>GAME_OTHERS,'game_code'=>'Interactive Games'),
			'SMG_absolootlyMadMegaMoolah' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Progressive Games'),
			'SMG_africanLegends' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Progressive Games'),
			'SMG_atlanteanTreasures' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Progressive Games'),
			'SMG_CashSplash5Reel' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Progressive Games'),
			'SMG_fortuniumGoldMegaMoolah' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Progressive Games'),
			'SMG_MajorMillions5Reel' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Progressive Games'),
			'SMG_MegaMoolah' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Progressive Games'),
			'SMG_sistersOfOzWowPot' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Progressive Games'),
			'SMG_treasureNile' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Progressive Games'),
			'SMG_wheelofWishes' => array('game_type_code'=>GAME_SLOTS,'game_code'=>'Progressive Games'),
			'SFG_WP28Mahjong' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SFG_WP5PK' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SFG_WPBankerNiuNiu' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SFG_WPBankerNiuNiu_3cards' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SFG_WPBankerNiuNiu_4cards' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SFG_WPBonusTexas' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SFG_WPCaiShenFruitMario' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SFG_WPChuhanTexas' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SFG_Doudizhu' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SFG_WPForestPartyJP' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SFG_GoldenFlower' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SFG_WPGoldenPigRace' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SFG_WPGoldenShark' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SFG_WPInstantGoldenFlower' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SFG_WPMahjong_2P' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SFG_WP100NiuNiu' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SFG_WPTavern' => array('game_type_code'=>GAME_BOARD_GAME,'game_code'=>'Board Game'),
			'SMG_switchAmericanRoulette' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
			'SMG_switchEuropeanRoulette' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
			'SMG_titaniumLiveGames_Roulette' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Roulette'),
			'SVS_instant_racing' => array('game_type_code'=>GAME_VIRTUAL_SPORTS,'game_code'=>'Schedule Sports'),
			'SVS_virtual_football' => array('game_type_code'=>GAME_VIRTUAL_SPORTS,'game_code'=>'Schedule Sports'),
			'SVS_virtual_greyhounds' => array('game_type_code'=>GAME_VIRTUAL_SPORTS,'game_code'=>'Schedule Sports'),
			'SVS_virtual_horses' => array('game_type_code'=>GAME_VIRTUAL_SPORTS,'game_code'=>'Schedule Sports'),
			'SVS_virtual_racing' => array('game_type_code'=>GAME_VIRTUAL_SPORTS,'game_code'=>'Schedule Sports'),
			'SVS_virtual_speedway' => array('game_type_code'=>GAME_VIRTUAL_SPORTS,'game_code'=>'Schedule Sports'),
			'SVS_virtual_tennis' => array('game_type_code'=>GAME_VIRTUAL_SPORTS,'game_code'=>'Schedule Sports'),
			'SVS_virtual_trotting' => array('game_type_code'=>GAME_VIRTUAL_SPORTS,'game_code'=>'Schedule Sports'),
			'SVS_virtual_velodrome' => array('game_type_code'=>GAME_VIRTUAL_SPORTS,'game_code'=>'Schedule Sports'),
			'SMG_titaniumLiveGames_Sicbo' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Sicbo'),
			'SMG_jokerPoker' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Video Poker Games'),
			'SMG_acesAndEights' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Video Poker Games'),
			'SMG_acesAndFaces' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Video Poker Games'),
			'SMG_RubyAllAces' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Video Poker Games'),
			'SMG_bonusDeucesWild' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Video Poker Games'),
			'SMG_deucesWild' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Video Poker Games'),
			'SMG_doubleDoubleBonus' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Video Poker Games'),
			'SMG_jacksOrBetter' => array('game_type_code'=>GAME_LIVE_CASINO,'game_code'=>'Video Poker Games'),
		);
		return $game_list;
	}
}