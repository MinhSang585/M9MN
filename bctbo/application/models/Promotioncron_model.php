<?php
class Promotioncron_model extends CI_Model {
	protected $table_promotion = 'promotion';
	protected $table_promotion_genre = 'promotion_genre';
	protected $table_promotion_result_logs = 'promotion_result_logs';
	protected $table_promotion_bonus_range = 'promotion_bonus_range';
	protected $table_player_promotion = 'player_promotion';
	protected $table_cash_transfer_report = 'cash_transfer_report';
	protected $table_players = 'players';
	protected $table_transaction_report = 'transaction_report';
	protected $table_player_promotion_logs = 'player_promotion_logs';


	public function get_promotion_genre_data($promotion_genre_code = NULL){
		$result = NULL;
		$query = $this
				->db
				->select('sync_lock')
				->where('genre_code', $promotion_genre_code)
				->where('active',STATUS_ACTIVE)
				->limit(1)
				->get($this->table_promotion_genre);
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		// echo $this->db->last_query();die;
		return $result;	
	}

	public function update_promotion_cron_sync_lock($promotion_genre_code = NULL,$sync_status = NULL){
		$result = array('sync_lock' => $sync_status);
		$this->db->where('genre_code', $promotion_genre_code);
		$this->db->limit(1);
		$this->db->update($this->table_promotion_genre, $result);
	}

	public function promotion_data_list($promotion_genre_code = NULL){

		$result = NULL;
		$current_time = time();
		$query = $this
				->db
				->where('promotion.start_date <= ',$current_time)
				->like('promotion.apply_type', ','.PROMOTION_USER_TYPE_SYSTEM.',')
				->group_start()
				->where('promotion.end_date >= ',$current_time)
				->or_where('promotion.end_date',0)
				->group_end()
				->where('genre_code', $promotion_genre_code)
				->where('active',STATUS_ACTIVE)
				->order_by('promotion_id',"DESC")
				->get($this->table_promotion);
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		$query->free_result();
		// echo $this->db->last_query();die;
		return $result;
	}

	public function get_promotion_result_logs($promotion_genre_code = NULL, $promotion_sync_type = NULL, $promotion_id = NULL){
		$result = NULL;
		$query = $this
				->db
				->select('promotion_time')
				->where('promotion_genre_code', $promotion_genre_code)
				->where('promotion_sync_type',$promotion_sync_type)
				->where('promotion_id',$promotion_id)
				->order_by('promotion_result_logs_id', 'DESC')
				->limit(1)
				->get($this->table_promotion_result_logs);

		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}

	public function get_all_player_turnover_by_game_type($start_time = NULL, $end_time = NULL, $promotionData = NULL,$game_type = NULL){
		$result = NULL;
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		else if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		else if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		else if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		else if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN){
			$this->db->select_sum('win_loss','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		else if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS){
			$this->db->select_sum('win_loss','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		else{
			//PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL
			$this->db->select_sum('promotion_amount','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}

		if($game_type == GAME_LIVE_CASINO){
			$this->db->group_start();
			$this->db->where('game_type_code', GAME_LIVE_CASINO);
			if(strpos($promotionData['live_casino_type'], (string)LIVE_CASINO_BACCARAT) === false){
				$this->db->where('game_code != ', 'Baccarat');
			}
			if(strpos($promotionData['live_casino_type'], (string)LIVE_CASINO_NON_BACCARAT) === false){
				$this->db->where('game_code', 'Baccarat');
			}
			$this->db->group_end();
		}else{
			$this->db->where('game_type_code !=', GAME_LIVE_CASINO);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL){
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS){
			$this->db->where('win_loss != ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN){
			$this->db->where('win_loss > ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS){
			$this->db->where('win_loss < ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN){
			$this->db->where('win_loss > ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS){
			$this->db->where('win_loss < ',0);
		}
		else{
			//PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL
		}
		$this->db->where('status', STATUS_COMPLETE);
		$this->db->where('bet_time >= ', $start_time);
		$this->db->where('bet_time <= ', $end_time);
		$this->db->group_by('player_id',"DESC");
		$this->db->group_by('game_provider_type_code',"DESC");
		$query = $this->db->get('transaction_report');
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		$query->free_result();
		return $result;
	}

	public function get_all_player_turnover($start_time = NULL, $end_time = NULL, $promotionData = NULL,$game_type = NULL){
		$result = NULL;
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
		}
		else if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
		}
		else if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
		}
		else if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
		}
		else if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN){
			$this->db->select_sum('win_loss','current_amount');
			$this->db->select('player_id');
		}
		else if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS){
			$this->db->select_sum('win_loss','current_amount');
			$this->db->select('player_id');
		}
		else{
			//PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL
			$this->db->select_sum('promotion_amount','current_amount');
		}

		if($promotionData['game_ids'] != "0"){
			$game_ids = array_filter(explode(',', $promotionData['game_ids']));
			$this->db->where_in('game_provider_type_code', $game_ids);
		}
		if($game_type == GAME_LIVE_CASINO){
			$this->db->group_start();
			$this->db->where('game_type_code', GAME_LIVE_CASINO);
			if(strpos($promotionData['live_casino_type'], (string)LIVE_CASINO_BACCARAT) === false){
				$this->db->where('game_code != ', 'Baccarat');
			}
			if(strpos($promotionData['live_casino_type'], (string)LIVE_CASINO_NON_BACCARAT) === false){
				$this->db->where('game_code', 'Baccarat');
			}
			$this->db->group_end();
		}else{
			$this->db->where('game_type_code !=', GAME_LIVE_CASINO);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL){
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS){
			$this->db->where('win_loss != ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN){
			$this->db->where('win_loss > ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS){
			$this->db->where('win_loss < ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN){
			$this->db->where('win_loss > ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS){
			$this->db->where('win_loss < ',0);
		}
		else{
			//PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL
		}
		$this->db->where('status', STATUS_COMPLETE);
		$this->db->where('bet_time >= ', $start_time);
		$this->db->where('bet_time <= ', $end_time);
		$this->db->group_by('player_id',"DESC");
		$query = $this->db->get('transaction_report');
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		$query->free_result();
		return $result;
	}

	public function get_level_data(){
		$list = array();
		$query = $this
				->db
				->get($this->table_level);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
			foreach($result as $row) {
				$list[$row['level_number']] = $row;					
			}
		}
		$query->free_result();
		return $list;
	}

	public function get_promotion_bonus_range_data($promotion_id = NULL){
		$list = array();
		$query = $this
				->db
				->where('promotion_id',$promotion_id)
				->where('active',STATUS_ACTIVE)
				->order_by('bonus_index','ASC')
				->get($this->table_promotion_bonus_range);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
			foreach($result as $row) {
				$list[$row['bonus_index']] = $row;					
			}
		}
		$query->free_result();
		return $list;
	}

	public function get_all_pending_promotion($promotion_id = NULL){
		$result = NULL;	
		$this->db->select('player_promotion_id,promotion_name,reward_amount,status,is_auto_complete,is_reward,player_id,reward_on_apply');
		$this->db->where('active',STATUS_ACTIVE);
		$this->db->where('status',STATUS_PENDING);
		$this->db->where('promotion_id', $promotion_id);
		$query = $this->db->get($this->table_player_promotion);

		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_player_list_array()
	{
		$lists = array();
		
		$query = $this
				->db
				->select('player_id, username, points')
				->get($this->table_players);
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row) {
				$lists[$row->player_id]['player_id'] = $row->player_id;
				$lists[$row->player_id]['username'] = $row->username;
				$lists[$row->player_id]['points'] = (double) $row->points;				
			}
		}
		
		$query->free_result();
		
		return $lists;
	}

	public function update_entitle_player_promotion($arr = NULL){
		$DBdata = array(
			'starting_date' => time(),
			'status' => STATUS_ENTITLEMENT,
			'updated_date' => time()
		);

		$this->db->where('player_promotion_id', $arr['player_promotion_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->limit(1);
		$this->db->update($this->table_player_promotion, $DBdata);
		return $DBdata;
	}

	public function insert_cash_transfer_report($arr = NULL, $points = NULL, $type = NULL,$remark = NULL)
	{	
		if(!empty($remark)){
			$remark = json_encode($remark,true);
		}else{
			$remark = $this->input->post('remark', TRUE);
		}

		$DBdata = array(
			'transfer_type' => $type,
			'username' => $arr['username'],
			'remark' => $remark,
			'report_date' => time(),
		);
		
		if($type == TRANSFER_POINT_IN OR $type == TRANSFER_ADJUST_IN OR $type == TRANSFER_OFFLINE_DEPOSIT OR $type == TRANSFER_PG_DEPOSIT OR $type == TRANSFER_WITHDRAWAL_REFUND OR $type == TRANSFER_PROMOTION OR $type == TRANSFER_BONUS OR $type == TRANSFER_COMMISSION OR $type == TRANSFER_TRANSACTION_IN  OR $type == TRANSFER_CREDIT_CARD_DEPOSIT  OR $type == TRANSFER_HYPERMART_DEPOSIT)
		{
			$DBdata['deposit_amount'] = $points;
			$DBdata['balance_before'] = $arr['points'];
			$DBdata['balance_after'] =  ($arr['points'] + $points);
		}
		else
		{
			$DBdata['withdrawal_amount'] = $points;
			$DBdata['balance_before'] = $arr['points'];
			$DBdata['balance_after'] =  ($arr['points'] - $points);
		}
		
		$this->db->insert($this->table_cash_transfer_report, $DBdata);
	}

	public function update_player_wallet($arr = NULL)
	{	
		$DBdata = array(
			'player_id' => $arr['player_id'],
			'username' => $arr['username'],
			'points' => $arr['amount'],
			'updated_date' => time()
		);
		
		$table = $this->db->dbprefix . $this->table_players;
		$this->db->query("UPDATE {$table} SET points = (points + ?) WHERE player_id = ? LIMIT 1", array($DBdata['points'], $DBdata['player_id']));
		return $DBdata;
	}

	public function update_player_promotion_reward_claim($arr = NULL){
		$DBdata = array(
			'is_reward' => STATUS_APPROVE,
			'reward_accumulate' => $arr['reward_amount'],
			'reward_date' => time(),
			'updated_date' => time()
		);

		$this->db->where('player_promotion_id', $arr['player_promotion_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->limit(1);
		$this->db->update($this->table_player_promotion, $DBdata);
	}

	public function updatePlayerPromotionStatus($promotion, $status){
		$this->db->set('status', $status);
		$this->db->where('promotion_id', $promotion['promotion_id']);
		$this->db->where('player_id', $promotion['player_id']);
		$this->db->update($this->table_player_promotion);
	}

	public function check_promotion_allow_withdrawal(){
		$result_array = NULL;
		$promotion_id = array('8', '9', '10', '11', '7');
		$this->db->select('player_promotion_id, player_id, promotion_id, calculate_type, game_ids, live_casino_type, achieve_amount, starting_date, complete_date, current_amount');
		$this->db->where('withdrawal_on_check', STATUS_ACTIVE);
		$this->db->where_in('status', array(STATUS_ENTITLEMENT));
		$this->db->where_in('promotion_id', $promotion_id);
		$query = $this->db->get('player_promotion');
		if($query->num_rows() > 0)
		{
			$result_array = $query->result_array();
		}
		
		return $result_array;
	}

	public function promotion_calculate_current_amount($data = NULL, $game_type = NULL){

		$result = NULL;
		if($data['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL){
			$this->db->select_sum('bet_amount_valid','current_amount');
		}
		else if($data['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS){
			$this->db->select_sum('bet_amount_valid','current_amount');
		}
		else if($data['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN){
			$this->db->select_sum('bet_amount_valid','current_amount');
		}
		else if($data['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS){
			$this->db->select_sum('bet_amount_valid','current_amount');
		}
		else if($data['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN){
			$this->db->select_sum('win_loss','current_amount');
		}
		else if($data['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS){
			$this->db->select_sum('win_loss','current_amount');
		}
		else{
			//PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL
			$this->db->select_sum('promotion_amount','current_amount');
		}

		if(!empty($data['game_ids'])){
			$game_ids_array = array_filter(explode(',', $data['game_ids']));
			$this->db->where_in('transaction_report.game_provider_type_code', $game_ids_array);
		}
		if($game_type == GAME_LIVE_CASINO){
			$this->db->group_start();
			$this->db->where('transaction_report.game_type_code', GAME_LIVE_CASINO);
			if(strpos($data['live_casino_type'], (string)LIVE_CASINO_BACCARAT) === false){
				$this->db->where('transaction_report.game_code != ', 'Baccarat');
			}
			if(strpos($data['live_casino_type'], (string)LIVE_CASINO_NON_BACCARAT) === false){
				$this->db->where('transaction_report.game_code', 'Baccarat');
			}
			$this->db->group_end();
		}else{
			$this->db->where('transaction_report.game_type_code !=', GAME_LIVE_CASINO);
		}
		if($data['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL){
		}
		if($data['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS){
			$this->db->where('win_loss != ',0);
		}
		if($data['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN){
			$this->db->where('win_loss > ',0);
		}
		if($data['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS){
			$this->db->where('win_loss < ',0);
		}
		if($data['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN){
			$this->db->where('win_loss > ',0);
		}
		if($data['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS){
			$this->db->where('win_loss < ',0);
		}
		else{
			//PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL
		}
		$this->db->where('transaction_report.status', STATUS_COMPLETE);
		$this->db->where('transaction_report.payout_time >= ', $data['starting_date']);
		$this->db->where('transaction_report.payout_time <= ', strtotime("+30 days", $data['starting_date']));
		$this->db->where('transaction_report.player_id', $data['player_id']);
		$query = $this->db->get('transaction_report');

		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		
		return $result;
	}

	public function update_promotion_current_amount($player_promotion_id = NULL, $current_amount = NULL){
		$DBdata = array(
			'current_amount' => $current_amount,
		);
		$this->db->where('player_promotion_id', $player_promotion_id);
		$this->db->limit(1);
		$this->db->update('player_promotion', $DBdata);
	}

	public function getPromotionById($promotionId){
		$result = null;
		$current_time = time();
		$query = $this
				->db
				->where('promotion.start_date <= ',$current_time)
				->like('promotion.apply_type', ','.PROMOTION_USER_TYPE_SYSTEM.',')
				->group_start()
				->where('promotion.end_date >= ',$current_time)
				->or_where('promotion.end_date',0)
				->group_end()
				->where('promotion_id', $promotionId)
				->where('active',STATUS_ACTIVE)
				->get($this->table_promotion);
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function getTransactionByGameId($game_ids, $playerId){
		$result = 0;
		$this->db->select('transaction_id');
		if(strlen($game_ids) > 0){
			$game_ids = array_filter(explode(',', $game_ids));
			$this->db->where_in('game_provider_type_code', $game_ids);
		}

		$this->db->where('transaction_report.status', STATUS_COMPLETE);
		$this->db->where('transaction_report.player_id', $playerId);
		$query = $this->db->get('transaction_report');

		if($query->num_rows() > 0)
		{
			$result = $query->num_rows();
		}
		$query->free_result();
		
		return $result;

	}

	public function addCronJobLog($promotion_genre_code){
	
		$data['genre'] = $promotion_genre_code;
		$data['cron_time'] = date("Y-m-d H:i:s");
		$table = $this->db->dbprefix . "cronjob_log";
		$this->db->insert($table, $data);
	}	

	public function get_all_player_turnover_by_game_type_new($start_time = NULL, $end_time = NULL, $promotionData = NULL,$gameTypeCode = NULL){
		$result = NULL;
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		elseif ($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		elseif ($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		elseif ($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		elseif ($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN){
			$this->db->select_sum('win_loss','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		elseif ($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS){
			$this->db->select_sum('win_loss','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		else{
			//PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL
			$this->db->select_sum('promotion_amount','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}

		$this->db->where('game_type_code', $gameTypeCode);
			
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL){
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS){
			$this->db->where('win_loss != ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN){
			$this->db->where('win_loss > ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS){
			$this->db->where('win_loss < ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN){
			$this->db->where('win_loss > ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS){
			$this->db->where('win_loss < ',0);
		}
		else{
			//PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL
		}
		$this->db->where('status', STATUS_COMPLETE);
		// $this->db->where('bet_time >= ', $start_time);
		// $this->db->where('bet_time <= ', $end_time);
		$this->db->group_by('player_id',"DESC");
		$this->db->group_by(array('player_id', 'game_provider_type_code'), "DESC");
		$query = $this->db->get('transaction_report');

		// echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		$query->free_result();
		return $result;
	}

	public function get_all_player_turnover_by_game_type_new_condition($start_time = NULL, $end_time = NULL, $promotionData = NULL,$gameTypeCode = NULL, $someCondition){
		$result = NULL;
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		elseif ($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		elseif ($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		elseif ($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		elseif ($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN){
			$this->db->select_sum('win_loss','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		elseif ($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS){
			$this->db->select_sum('win_loss','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		else{
			//PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL
			$this->db->select_sum('promotion_amount','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}

		$this->db->group_start();
		if ($someCondition) {
			$this->db->where_in('game_provider_type_code', array_filter(array_map('trim', explode(',', $gameTypeCode))));
		} else {
			$this->db->where('game_type_code', $gameTypeCode);
		}
		$this->db->group_end();
			
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL){
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS){
			$this->db->where('win_loss != ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN){
			$this->db->where('win_loss > ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS){
			$this->db->where('win_loss < ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN){
			$this->db->where('win_loss > ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS){
			$this->db->where('win_loss < ',0);
		}
		else{
			//PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL
		}
		$this->db->where('status', STATUS_COMPLETE);
		// $this->db->where('bet_time >= ', $start_time);
		// $this->db->where('bet_time <= ', $end_time);
		$this->db->group_by('player_id',"DESC");
		$this->db->group_by(array('player_id', 'game_provider_type_code'), "DESC");
		$query = $this->db->get('transaction_report');

		// echo $this->db->last_query();

		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		$query->free_result();
		return $result;
	}

	public function get_player_list_array_by_id($player_id)
	{
		$lists = array();
		
		$query = $this
				->db
				->select('player_id, username, points, active, level_id')
				->where('player_id', $player_id)
				->limit(1)
				->get($this->table_players);

		if($query->num_rows() > 0)
		{
			$lists =  $query->result_array();
		}
		
		$query->free_result();
		// echo $this->db->last_query();
		
		return $lists;
	}

	public function updateCheckPromotion($arr = NULL, $days){
		$DBdata = array(
			'status' => STATUS_CANCEL,
			'updated_date' => time()
		);
		$this->db->where('DATEDIFF(NOW(), FROM_UNIXTIME(created_date)) > '. $days, NULL, FALSE);
		$this->db->where('status', STATUS_PENDING);
		$this->db->where('genre_code', $arr['genre_code']);
		$this->db->update($this->table_player_promotion, $DBdata);
		return $DBdata;
	}

	// public function get_all_player_turnover_gameAll($start_time = NULL, $end_time = NULL){
	// 	$result = NULL;
	// 	$this->db->select('player_id, game_provider_type_code');
			
	// 	$this->db->select_sum('win_loss', 'current_amount');
	// 	$this->db->where('status', STATUS_COMPLETE);
	// 	// $this->db->where('bet_time >= ', $start_time);
	// 	// $this->db->where('bet_time <= ', $end_time);
	// 	$this->db->group_by('player_id');
	// 	$this->db->group_by(array('player_id', 'game_provider_type_code'), "DESC");
	// 	$query = $this->db->get('transaction_report');
	
	// 	// echo $this->db->last_query();
		
	// 	if ($query->num_rows() > 0) {
	// 		$result = $query->result_array();
	// 	}
	
	// 	$query->free_result();
		
	// 	return $result;
	// }

	// public function get_all_player_turnover_gameType($start_time = NULL, $end_time = NULL, $gameProviderTypeCode = NULL){
	// 	$result = NULL;

	// 	// echo "<pre>";
	// 	// print_r($gameProviderTypeCode); 
	// 	// echo "</pre>";

	// 	// print_r($gameProviderTypeCode);
	// 	$this->db->select('player_id, game_provider_type_code');
			
	// 	$this->db->select_sum('win_loss', 'current_amount');
	// 	$this->db->where('status', STATUS_COMPLETE);
	// 	// $this->db->where('bet_time >= ', $start_time);
	// 	// $this->db->where('bet_time <= ', $end_time);
	// 	// $this->db->group_start();
	// 		$this->db->where_in('game_provider_type_code', array_filter(array_map('trim', explode(',', $gameProviderTypeCode))));
	// 	// $this->db->group_end();
	// 	$this->db->group_by('player_id');
	// 	$this->db->group_by(array('player_id', 'game_provider_type_code'), "DESC");
	// 	$query = $this->db->get('transaction_report');
	
	// 	echo $this->db->last_query();
		
	// 	if ($query->num_rows() > 0) {
	// 		$result = $query->result_array();
	// 	}
	
	// 	$query->free_result();
		
	// 	return $result;
	// }

	public function get_player_net_lose_by_id($player_id)
	{
		$this->db->select('game_provider_type_code');
		$this->db->select_sum('win_loss', 'WIN_LOSS');
		$this->db->group_by('game_provider_type_code');

		$this->db->where('player_id', $player_id);
		$query = $this->db->get($this->table_transaction_report);

		if ($query->num_rows() > 0) {
			$result = $query->row_array();
		}

		// echo $this->db->last_query();
		$query->free_result();

		return $result;
	}

	public function getGameProviderTypeCode($player_id, $gameTypeCode, $gameProviderTypeCode, $someCondition)
	{
		$this->db->select('player_id, GROUP_CONCAT(DISTINCT game_provider_type_code ORDER BY game_provider_type_code) AS game_provider_type_code', false);
		$this->db->group_by('player_id');
		$this->db->where('player_id', $player_id);

		$this->db->group_start();
			if ($someCondition == 1) {								// promotionWeeklyRescueBonusTypeGame
				$this->db->where_in('game_provider_type_code', array_filter(array_map('trim', explode(',', $gameProviderTypeCode))));
			} else if ($someCondition == 2) {
				$this->db->where('game_type_code', $gameTypeCode);
			} else if ($someCondition == 3) {						// promotionDailyRebate
				$this->db->where('game_type_code', $gameTypeCode);
				if ($gameTypeCode == 'sl')
				{
					$this->db->or_where('game_type_code', 'l');
				}
				$this->db->where_in('game_provider_type_code', array_filter(array_map('trim', explode(',', $gameProviderTypeCode))));
			}
		$this->db->group_end();

		$query = $this->db->get($this->table_transaction_report);

		if ($query->num_rows() > 0) {
			$result = $query->row_array();
		}

		// echo $this->db->last_query();
		$query->free_result();

		return $result;
	}

	public function checkPlayerPromotionExists($playerId, $promotionId) {
		// Get the timestamp of the first day
		$startOfDay = strtotime('today', time());

		// Get the timestamp of the last day
		 $endOfDay = strtotime('tomorrow', $startOfDay) - 1;
		 
		$this->db->where('player_id', $playerId);
		$this->db->where('promotion_id', $promotionId);
		
		// Checks if created_date is within the range of the current date
		$this->db->where('created_date >=', $startOfDay);
		$this->db->where('created_date <=', $endOfDay);

		$query = $this->db->get($this->table_player_promotion);
	
		return $query->num_rows() > 0;
	}

	public function checkPlayerPromotionExistsLastWeek($playerId, $promotionId) {
		// Get the timestamp of the currentTimes
		$currentTimestamp = time();
		$endTime = strtotime('-7 days', $currentTimestamp) + 1;
	 
		$this->db->where('player_id', $playerId);
		$this->db->where('promotion_id', $promotionId);
		 
		// Checks if created_date is within 7 days of the current date
		$this->db->where('created_date >=', $endTime);
		$this->db->where('created_date <=', $currentTimestamp);
	 
		$query = $this->db->get($this->table_player_promotion);

		// echo $this->db->last_query();
	 
		return $query->num_rows() > 0;
	}

	public function get_all_player_turnover_by_game_type_code_provider($start_time = NULL, $end_time = NULL, $promotionData = NULL,$gameTypeCode = NULL, $gameProviderTypeCode = NULL, $someCondition = NULL){
		$result = NULL;
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL){
			
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
			// $this->db->select('GROUP_CONCAT(DISTINCT game_provider_type_code ORDER BY game_provider_type_code) AS game_provider_types', false);
		}
		elseif ($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		elseif ($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		elseif ($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS){
			$this->db->select_sum('bet_amount_valid','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		elseif ($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN){
			$this->db->select_sum('win_loss','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		elseif ($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS){
			$this->db->select_sum('win_loss','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		elseif ($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_TOTAL_WINLOSS){
			$this->db->select_sum('win_loss','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}
		else{
			//PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL
			$this->db->select_sum('win_loss','current_amount');
			$this->db->select('player_id');
			$this->db->select('game_provider_type_code');
		}

		if (!empty($someCondition))						// if empty run promotionWeeklyRescueBonus
		{
			$this->db->group_start();
				if ($someCondition == 1) {				// promotionWeeklyRescueBonusTypeGame
					$this->db->where_in('game_provider_type_code', array_filter(array_map('trim', explode(',', $gameProviderTypeCode))));
				} else if ($someCondition == 2) {
					$this->db->where('game_type_code', $gameTypeCode);
				} else if ($someCondition == 3) {		// promotionDailyRebate
					$this->db->where('game_type_code', $gameTypeCode);
					if ($gameTypeCode == 'sl')
					{
						$this->db->or_where('game_type_code', 'l');
					}
					$this->db->where_in('game_provider_type_code', array_filter(array_map('trim', explode(',', $gameProviderTypeCode))));
				}
			$this->db->group_end();
		}
		

		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL){
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS){
			$this->db->where('win_loss != ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN){
			$this->db->where('win_loss > ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS){
			$this->db->where('win_loss < ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN){
			$this->db->where('win_loss > ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS){
			$this->db->where('win_loss < ',0);
		}
		if($promotionData['calculate_type'] == PROMOTION_CALCULATE_TYPE_TOTAL_WINLOSS){
		}
		else{
			//PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL
		}
		$this->db->where('status', STATUS_COMPLETE);
		// $this->db->where('bet_time >= ', $start_time);
		// $this->db->where('bet_time <= ', $end_time);
		$this->db->group_by('player_id',"DESC");
		$this->db->group_by(array('player_id', 'game_provider_type_code'), "DESC");
		$query = $this->db->get('transaction_report');

		// echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		$query->free_result();

		return $result;
	}

	public function getSinglePromotionData($promotion_genre_code = NULL, $promotion_id = NULL){

		$result = NULL;
		$current_time = time();
		$query = $this
				->db
				->where('promotion_id',$promotion_id)
				->like('promotion.apply_type', ','.PROMOTION_USER_TYPE_SYSTEM.',')
				->where('genre_code', $promotion_genre_code)
				->where('active',STATUS_ACTIVE)
				->get($this->table_promotion);
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		$query->free_result();
		// echo $this->db->last_query();die;
		return $result;
	}

	public function insertPlayersPromotionLogs($arr = null) {
		
		$DBdata = array(
			'player_id' => $arr['player_id'],
			'player_name' => $arr['player_name'],
			'promotion_id' => $arr['promotion_id'],
			'promotion_name' => $arr['promotion_name'],
			'promotion_amount' => $arr['promotion_amount'],
			'deposit_id' => $arr['deposit_id'],
			'deposit_amount' => $arr['deposit_amount'],
			'level' => $arr['level'],
			'level_name' => $arr['level_name'],
			'current_amount' => $arr['current_amount'],
			'achieve_amount' => $arr['achieve_amount'],
			'add_amount' => $arr['add_amount'],
			'bonus' => $arr['bonus'],
			'max_bonus' => $arr['max_bonus'],
			'percentage_promotion' => $arr['percentage_promotion'],
			'game_type_code' => $arr['game_type_code'],
			'game_provider_type_code' => $arr['game_provider_type_code'],
			'genre_code' => $arr['genre_code'],
			'genre_name' => $arr['genre_name'],
			'remark' => $arr['remark'],
			'start_time' => $arr['start_time'],
			'end_time' => $arr['end_time'],
			'created_date' => date("Y-m-d H:i:s"),
		);

		$result = $this->db->insert($this->table_player_promotion_logs, $DBdata);
		if (!$result) {
			echo $this->db->error();
		}
	
	}

	public function addCronJobLogPromotion($promotion_genre_code, $promotion_id){
	
		$data['genre'] = $promotion_genre_code;
		$data['promotion_id'] = $promotion_id;
		$data['cron_time'] = date("Y-m-d H:i:s");
		$table = $this->db->dbprefix . "cronjob_log";
		$this->db->insert($table, $data);
	}
}