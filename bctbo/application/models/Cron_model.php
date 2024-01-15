<?php
class Cron_model extends CI_Model {

	protected $table_cron_result = 'cron_result';
	protected $table_player_risk_report = 'player_risk_report';
	protected $table_transaction_report = "transaction_report";
	protected $table_deposits = "deposits";
	protected $table_level = "level";
	protected $table_level_schedule = "level_schedule";
	protected $table_level_log = "level_log";
	protected $table_players = "players";
	protected $table_player_reward = "player_reward";
	protected $table_promotion = "promotion";
	protected $table_player_promotion = "player_promotion";
	protected $table_promotion_result = "promotion_result";

	public function get_cron_result($cron_code = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('cron_code', $cron_code)
				->where('active', STATUS_ACTIVE)
				->limit(1)
				->get($this->table_cron_result);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function update_sync_lock($cron_code = NULL,$sync_status = NULL){
		$result = array('sync_lock' => $sync_status);
		$this->db->where('cron_code', $cron_code);
		$this->db->limit(1);
		$this->db->update($this->table_cron_result, $result);
	}	

	public function get_player_data_risk_management(){
		$result = array();
		$query = $this
				->db
				->select('player_id, username,win_loss_suspend_limit')
				->get($this->table_players);
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
		}
		$query->free_result();
		return $result;
	}

	public function get_player_data(){
		$result = array();
		$query = $this
				->db
				->select('player_id, username,level_id,points,rewards')
				->get($this->table_players);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
		}
		$query->free_result();
		return $result;
	}

	public function get_all_player_risk_record($start_time = NULL,$end_date = NULL){
		$lists = array();
		$query = $this
				->db
				->select('player_id, suspended')
				->where('report_date',$start_time)
				->where('end_date',$end_date)
				->get($this->table_player_risk_report);
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			foreach($result as $row) {
				$lists[$row['player_id']] = $row;						
			}	
		}
		$query->free_result();
		return $lists;	
	}

	public function get_player_total_win_loss($player_id = NULL,$start_time = NULL,$end_time = NULL){
		$result = 0;
		$this->db->select_sum('transaction_report.win_loss','current_amount');
		$this->db->where('transaction_report.status', STATUS_COMPLETE);
		$this->db->where('transaction_report.player_id', $player_id);
		$this->db->where('transaction_report.payout_time >= ', $start_time);
		$this->db->where('transaction_report.payout_time <= ', $end_time);
		$query = $this->db->get($this->table_transaction_report);
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
		$this->db->update($this->table_players, $DBdata);
	}

	public function delete_risk_record($arr = NULL){
		$this->db->where('report_date', $arr['report_date']);
		$this->db->where('end_date', $arr['end_date']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->limit(1);
		$this->db->delete($this->table_player_risk_report);
	}

	public function update_player_risk_record($arr = NULL){
		$this->db->where('report_date', $arr['report_date']);
		$this->db->where('end_date', $arr['end_date']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->where('percentage != ', $arr['percentage']);
		$this->db->where('suspended != ', STATUS_SUSPEND);
		$this->db->limit(1);
		$this->db->update($this->table_player_risk_report,$arr);
	}

	public function update_cron_result($arr = NULL,$UData = NULL){
		$this->db->where('cron_id', $arr['cron_id']);
		$this->db->limit(1);
		$this->db->update($this->table_cron_result, $UData);
	}

	public function get_player_deposit_range($start_time=NULL,$end_time=NULL,$player_id=NULL){
		$result = 0;
		$this->db->select_sum('amount');
		$this->db->where('updated_date >= ', $start_time);
		$this->db->where('updated_date <= ', $end_time);
		$this->db->where('player_id', $player_id);
		$this->db->where('status', STATUS_APPROVE);
		$query = $this->db->get($this->table_deposits);
		if($query->num_rows() > 0)
		{
			$result_data = $query->row_array();
			if(!empty($result_data['amount'])){
				$result = $result_data['amount'];
			}
		}
		$query->free_result();
		return $result;
	}

	public function get_level_data_by_id($level_id = NULL){
		$result = NULL;
		$this->db->where('level_id',$level_id);
		$this->db->limit(1);
		$query = $this->db->get($this->table_level);
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_total_amount_level($calculateData = NULL,$start_time = NULL,$end_time = NULL,$game_type = NULL){
		$result = array();
		if($calculateData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL){
			$this->db->select('player_id, game_provider_code, game_type_code, SUM(bet_amount_valid) AS current_amount', FALSE);
		}
		else if($calculateData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS){
			$this->db->select('player_id, game_provider_code, game_type_code, SUM(bet_amount_valid) AS current_amount', FALSE);
		}
		else if($calculateData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN){
			$this->db->select('player_id, game_provider_code, game_type_code, SUM(bet_amount_valid) AS current_amount', FALSE);
		}
		else if($calculateData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS){
			$this->db->select('player_id, game_provider_code, game_type_code, SUM(bet_amount_valid) AS current_amount', FALSE);
		}
		else if($calculateData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN){
			$this->db->select('player_id, game_provider_code, game_type_code, SUM(win_loss) AS current_amount', FALSE);
		}
		else if($calculateData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS){
			$this->db->select('player_id, game_provider_code, game_type_code, SUM(win_loss) AS current_amount', FALSE);
		}
		else{
			//PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL
			$this->db->select('player_id, game_provider_code, game_type_code, SUM(promotion_amount) AS current_amount', FALSE);
		}
		if($calculateData['game_ids'] != "0"){
			$game_ids = array_filter(explode(',', $calculateData['game_ids']));
			$this->db->where_in('game_provider_type_code', $game_ids);
		}
		if($game_type == GAME_LIVE_CASINO){
			$this->db->group_start();
			$this->db->where('game_type_code', GAME_LIVE_CASINO);
			if(strpos($calculateData['live_casino_type'], (string)LIVE_CASINO_BACCARAT) === false){
				$this->db->where('game_code != ', 'Baccarat');
			}
			if(strpos($calculateData['live_casino_type'], (string)LIVE_CASINO_NON_BACCARAT) === false){
				$this->db->where('game_code', 'Baccarat');
			}
			$this->db->group_end();
		}else{
			$this->db->where('game_type_code !=', GAME_LIVE_CASINO);
		}

		if($calculateData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_TOTAL){
		}
		if($calculateData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN_LOSS){
			$this->db->where('win_loss != ',0);
		}
		if($calculateData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_WIN){
			$this->db->where('win_loss > ',0);
		}
		if($calculateData['calculate_type'] == PROMOTION_CALCULATE_TYPE_VALID_BET_LOSS){
			$this->db->where('win_loss < ',0);
		}
		if($calculateData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_WIN){
			$this->db->where('win_loss > ',0);
		}
		if($calculateData['calculate_type'] == PROMOTION_CALCULATE_TYPE_WIN_LOSS_LOSS){
			$this->db->where('win_loss < ',0);
		}
		else{
			//PROMOTION_CALCULATE_TYPE_PROMOTION_BET_TOTAL
		}
		$this->db->where('status', STATUS_COMPLETE);
		$this->db->where('bet_time >= ', $start_time);
		$this->db->where('bet_time <= ', $end_time);
		$this->db->group_by('player_id');
		if($calculateData['target_type'] == LEVEL_TARGET_SAME_PROVIDER){
			$this->db->group_by('game_provider_code');
		}else if($calculateData['target_type'] == LEVEL_TARGET_SAME_GAME){
			$this->db->group_by('game_type_code');
		}else if($calculateData['target_type'] == LEVEL_TARGET_SAME_PROVIDER_SAME_GAME){
			$this->db->group_by(array('game_provider_code','game_type_code'));
		}else{

		}
		$query = $this->db->get('transaction_report');
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_level_data_by_deposit_target_ranking($calculateData = NULL,$deposit_amount = NULL, $target_amount = NULL){
		$result = NULL;
		if($calculateData['upgrade_type'] == LEVEL_UPGRADE_DEPOSIT){
			$this->db->where('level_deposit_amount_from <=', $deposit_amount);
			$this->db->where('level_deposit_amount_to >=', $deposit_amount);
		}else if($calculateData['upgrade_type'] == LEVEL_UPGRADE_TARGET){
			$this->db->where('level_target_amount_from <=', $target_amount);
			$this->db->where('level_target_amount_to >=', $target_amount);
		}else{
			//LEVEL_UPGRADE_DEPOSIT_TARGET
			$this->db->where('level_deposit_amount_from <=', $deposit_amount);
			$this->db->where('level_deposit_amount_to >=', $deposit_amount);
			$this->db->where('level_target_amount_from <=', $target_amount);
			$this->db->where('level_target_amount_to >=', $target_amount);
		}
		$this->db->limit(1);
		$query = $this->db->get($this->table_level);
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}
	public function add_schedule($start_time=NULL,$end_time=NULL){
		$DBdata = array(
			'schedule_start' => $start_time,
			'schedule_end' => $end_time,
			'remark' => $this->input->post('remark', TRUE), 
			'created_date' => time(),
		);
		$this->db->insert($this->table_level_schedule, $DBdata);
		$DBdata['schedule_id'] = $this->db->insert_id();
		return $DBdata;
	}
	public function add_level_log($DBdata = NULL){
		$this->db->insert($this->table_level_log, $DBdata);
		$DBdata['log_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function get_previous_ranking($ranking_data = NULL){
		$result = NULL;
		$this->db->where('level_number <',$ranking_data['level_number']);
		$this->db->order_by('level_number','DESC');
		$this->db->limit(1);
		$query = $this->db->get($this->table_level);
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_level_maintain_decision($calculateData = NULL,$deposit_amount = NULL, $target_amount = NULL){
		$result = STATUS_NO;
		if($calculateData['downgrade_type'] == LEVEL_DOWNGRADE_DEPOSIT){
			if($deposit_amount >= $calculateData['maintain_membership_deposit_amount']){
				$result = STATUS_YES;
			}
		}else if($calculateData['downgrade_type'] == LEVEL_DOWNGRADE_TARGET){
			if($target_amount >= $calculateData['maintain_membership_target_amount']){
				$result = STATUS_YES;
			}
		}else{
			if($deposit_amount >= $calculateData['maintain_membership_deposit_amount']){
				if($target_amount >= $calculateData['maintain_membership_target_amount']){
					$result = STATUS_YES;
				}
			}
		}
		return $result;
	}

	public function get_player_level_downgrade_limit($start_time = NULL,$end_time = NULL,$playerData = NULL,$calculateData = NULL){
		$result = STATUS_NO;
		$this->db->where('schedule_start <=',$start_time);
		$this->db->where('player_id', $player_data['player_id']);
		$this->db->limit($calculateData['maintain_membership_limit']);
		$query = $this->db->get($this->table_level_log);
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
			foreach($result_data as $result_data_row){
				if($result_data_row['movement'] == LEVEL_MOVEMENT_NONE){
					if($result_data_row['is_maintain'] == STATUS_YES){
						$result = STATUS_YES;
					}
				}
			}
		}
		$query->free_result();
		$result = $this->db->count_all_results($this->table_level_log);
		return $result;
	}

	public function update_level_log_status($arr = NULL,$UData = NULL){
		$this->db->where('log_id', $arr['log_id']);
		$this->db->limit(1);
		$this->db->update($this->table_level_log, $UData);
	}

	public function get_system_promotion_by_genre_code($genre_code = NULL){
		$result = NULL;
		$this->db->where('promotion.genre_code', $genre_code);
		$this->db->like('promotion.apply_type', ','.PROMOTION_USER_TYPE_SYSTEM.',');
		$this->db->where('promotion.active', STATUS_ACTIVE);
		$this->db->where('promotion.start_date <= ',time());
		$this->db->group_start();
			$this->db->where('promotion.end_date >= ',time());
			$this->db->or_where('promotion.end_date',0);
		$this->db->group_end();
		$this->db->order_by('promotion.promotion_seq', 'ASC');
		$query = $this->db->get($this->table_promotion);
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
		}
		$query->free_result();
		return $result;
	}
}