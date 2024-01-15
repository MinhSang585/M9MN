<?php
class Promotion_apply_model extends CI_Model {
	protected $table_promotion = 'promotion';
	protected $table_promotion_genre = 'promotion_genre';
	protected $table_promotion_bonus_range = 'promotion_bonus_range';
	protected $table_promotion_lang = 'promotion_lang';
	protected $table_deposits = 'deposits';
	protected $table_player_promotion = 'player_promotion';

	public function get_promotion_data_by_id($id){
		$result = NULL;
		$this->db->from('promotion');
		$this->db->where('promotion_id',$id);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}
	
	public function add_player_promotion($data = NULL,$balanceData = NULL, $referrer_data = NULL){
		$promotionData = $this->get_promotion_data_by_id($data['promotion_id']);
		$deposit_amount = $data['amount'];
		$promotion_amount = $data['amount'];
		if($data['amount'] > $promotionData['max_deposit']){
			$promotion_amount = bcdiv($promotionData['max_deposit'],1,2);
		}
		if($promotionData['genre_code'] == PROMOTION_TYPE_DPR  || $promotionData['genre_code'] == PROMOTION_TYPE_DPRC){
			$achieve_amount = $data['achieve_amount'];
			$reward_amount = $data['reward_amount'];
			$bonus_multiply = $data['bonus_multiply'];
			$bonus_index = 0;
			$bonus_level = 0;
			$bonus_ids = $promotionData['game_ids'];
			$original_amount = (isset($balanceData['balance_amount']) ? $balanceData['balance_amount'] : "0");
		}else if($promotionData['genre_code'] == PROMOTION_TYPE_RF || $promotionData['genre_code'] == PROMOTION_TYPE_BDRF){
			$reward_amount = bcdiv($this->deposit_promotion_reward_amount_decision($promotion_amount,$promotionData,$data['player_id']),1,2);
			$achieve_amount_data = $this->deposit_promotion_achieve_amount_decision($promotion_amount,$promotionData,$reward_amount,$deposit_amount);

			$achieve_amount = bcdiv($achieve_amount_data['amount'],1,2);
			$bonus_multiply = $achieve_amount_data['multiply'];
			$bonus_index = $achieve_amount_data['index'];
			$bonus_level = $achieve_amount_data['level'];
			$bonus_ids = (!empty($achieve_amount_data['game_ids']) ? $achieve_amount_data['game_ids'] : $promotionData['game_ids']);
			$original_amount = (isset($balanceData['balance_amount']) ? $balanceData['balance_amount'] : "0");
		}else{
			$reward_amount = bcdiv($this->deposit_promotion_reward_amount_decision($promotion_amount,$promotionData,$data['player_id']),1,2);
			$achieve_amount_data = $this->deposit_promotion_achieve_amount_decision($promotion_amount,$promotionData,$reward_amount,$deposit_amount);

			$achieve_amount = bcdiv($achieve_amount_data['amount'],1,2);
			$bonus_multiply = $achieve_amount_data['multiply'];
			$bonus_index = $achieve_amount_data['index'];
			$bonus_level = $achieve_amount_data['level'];
			$bonus_ids = (!empty($achieve_amount_data['game_ids']) ? $achieve_amount_data['game_ids'] : $promotionData['game_ids']);
			$original_amount = (isset($balanceData['balance_amount']) ? $balanceData['balance_amount'] : "0");
		}
		
		
		$DBdata = array(
			'deposit_id' => (isset($data['deposit_id']) ? $data['deposit_id'] : "0"),
			'deposit_amount' => $deposit_amount,
			'promotion_amount' => $promotion_amount,
			'current_amount' => 0,
			'achieve_amount' => $achieve_amount,
			'bonus_multiply' => $bonus_multiply,
			'bonus_index' => $bonus_index,
			'bonus_level' => $bonus_level,
			'bonus_ids' => $bonus_ids,
			'reward_amount' => $reward_amount,
			'real_reward_amount' => $reward_amount,
			'original_amount' => $original_amount,
			'player_id'  => $data['player_id'],
			'player_referrer_id' => ((isset($referrer_data['player_id']))?$referrer_data['player_id']:'0'),
			'promotion_id'  => $promotionData['promotion_id'],
			'promotion_name'  => $promotionData['promotion_name'],
			'url_path' => $promotionData['url_path'],
			'promotion_seq'  => $promotionData['promotion_seq'],
			'genre_code' => $promotionData['genre_code'],
			'genre_name' => $promotionData['genre_name'],
			'date_type' => $promotionData['date_type'],
			'start_date' => $promotionData['start_date'],
			'end_date' => $promotionData['end_date'],
			'specific_day_week' => $promotionData['specific_day_week'],
			'specific_day_day' => $promotionData['specific_day_day'],
			'reward_on_apply' => $promotionData['reward_on_apply'],
			'withdrawal_on_check' => $promotionData['withdrawal_on_check'],
			'is_auto_complete' => $promotionData['is_auto_complete'],
			'level' => $promotionData['level'],
			'accumulate_deposit' => $promotionData['accumulate_deposit'],
			'is_deposit_tied_promotion_count' => $promotionData['is_deposit_tied_promotion_count'],
			'apply_type' => $promotionData['apply_type'],
			'date_expirate_type' => $promotionData['date_expirate_type'],
			'times_limit_type' => $promotionData['times_limit_type'],
			'is_apply_on_first_day_of_times_limit_type' => $promotionData['is_apply_on_first_day_of_times_limit_type'],
			'is_starting_of_the_day' => $promotionData['is_starting_of_the_day'],
			'claim_type' => $promotionData['claim_type'],
			'calculate_day_type' => $promotionData['calculate_day_type'],
			'calculate_hour' => $promotionData['calculate_hour'],
			'calculate_minute' => $promotionData['calculate_minute'],
			'reward_day_type' => $promotionData['reward_day_type'],
			'reward_hour' => $promotionData['reward_hour'],
			'reward_minute' => $promotionData['reward_minute'],
			'first_deposit' => $promotionData['first_deposit'],
			'daily_first_deposit' => $promotionData['daily_first_deposit'],
			'min_deposit' => $promotionData['min_deposit'],
			'max_deposit' => $promotionData['max_deposit'],
			'calculate_type' => $promotionData['calculate_type'],
			'complete_wallet_left' => $promotionData['complete_wallet_left'],
			'bonus_range_type' => $promotionData['bonus_range_type'],
			'bonus_type' => $promotionData['bonus_type'],
			'turnover_multiply' => $promotionData['turnover_multiply'],
			'rebate_percentage' => $promotionData['rebate_percentage'],
			'max_rebate' => $promotionData['max_rebate'],
			'rebate_amount' => $promotionData['rebate_amount'],
			'game_ids' => $promotionData['game_ids'],
			'game_ids_all' => $promotionData['game_ids_all'],
			'live_casino_type' => $promotionData['live_casino_type'],
			'is_level' => $promotionData['is_level'],
			'is_banner' => $promotionData['is_banner'],
			'balance_less' => $promotionData['balance_less'],
			'active' => $promotionData['active'],
			'status' => STATUS_PENDING,
			'remark' => ((isset($referrer_data['player_id']))?$referrer_data['username']." ".$this->input->post('remark', TRUE):$this->input->post('remark', TRUE)),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time(),
			'updated_date' => time()
		);
		$this->db->insert('player_promotion', $DBdata);
		$DBdata['player_promotion_id'] = $this->db->insert_id();
		$range_data = $this->get_all_promotion_bonus_range($data);
		if(!empty($range_data)){
			foreach($range_data as $range_data_row){
				$bonus_range = array(
					'player_promotion_id' => $DBdata['player_promotion_id'],
					'bonus_index' => $range_data_row['bonus_index'],
					'bonus_level' => $range_data_row['bonus_level'],
					'turnover_multiply' => $range_data_row['turnover_multiply'],
					'game_ids' => $range_data_row['game_ids'],
					'amount_from' => $range_data_row['amount_from'],
					'amount_to' => $range_data_row['amount_to'],
					'bonus_amount' => $range_data_row['bonus_amount'],
					'percentage' => $range_data_row['percentage'],
					'max_amount' => $range_data_row['max_amount'],
					'active' => $range_data_row['active'],
				);
				$this->db->insert('player_promotion_bonus_range', $bonus_range);
			}
		}
		
		return $DBdata;
	}

	public function deposit_promotion_reward_amount_decision($promotion_amount=NULL,$promotionData = NULL,$player_id = NULL){
		if($promotionData['bonus_range_type'] == PROMOTION_BONUS_RANGE_TYPE_GENERAL){
			if($promotionData['bonus_type'] == PROMOTION_BONUS_TYPE_PERCENTAGE){
				$reward_amount = $promotion_amount * $promotionData['rebate_percentage'] / 100;
				if($reward_amount >= $promotionData['max_rebate']){
					$reward_amount = $promotionData['max_rebate'];
				}
			}else{
				$reward_amount = $promotionData['rebate_amount'];
			}
		}else{
			$promotionBonusRangeData = $this->get_deposit_promotion_bonus_range_decision($promotion_amount,$promotionData);
			if(!empty($promotionBonusRangeData)){
				if($promotionBonusRangeData['bonus_type'] == PROMOTION_BONUS_TYPE_PERCENTAGE){
					$reward_amount = $promotion_amount * $promotionBonusRangeData['percentage'] / 100;
					if($reward_amount >= $promotionBonusRangeData['max_amount']){
						$reward_amount = $promotionBonusRangeData['max_amount'];
					}
				}else if($promotionBonusRangeData['bonus_type'] == PROMOTION_BONUS_TYPE_PERCENTAGE_TURNOVER){
					$reward_amount = $promotion_amount * $promotionBonusRangeData['percentage'] / 100;
					if($reward_amount >= $promotionBonusRangeData['max_amount']){
						$reward_amount = $promotionBonusRangeData['max_amount'];
					}
				}else{
					$reward_amount = $promotionBonusRangeData['bonus_amount'];
				}
			}else{
				$reward_amount = 0;
			}
			
		}

		$is_limit_reward_amount = FALSE;
		$max_reward_amount = $promotionData['max_promotion'];
		$current_amount = 0;
		if($max_reward_amount > 0){
			$is_limit_reward_amount = TRUE;
			if($promotionData['is_count_total_today'] == STATUS_ACTIVE){
				$total_data = $this->get_player_total_promotion_reward_today($promotionData['promotion_id'],$player_id);
			}else{
				if($promotionData['date_type'] == PROMOTION_DATE_TYPE_START_TO_END){
					$total_data = $this->get_player_total_promotion_reward_range($promotionData['promotion_id'],$player_id,$promotionData['start_date'],$promotionData['end_date']);
				}else{
					$total_data = $this->get_player_total_promotion_reward_range($promotionData['promotion_id'],$player_id,$promotionData['start_date'],0);
				}
			}

			if(!empty($total_data)){
				if(!empty($total_data['total'])){
					$current_amount = $total_data['total'];
				}
			}
		}
		$limit_amount = $max_reward_amount - $current_amount;

		if($is_limit_reward_amount){
			if($reward_amount > $limit_amount){
				$reward_amount = $limit_amount;
			}
		}
		return $reward_amount;
	}

	public function deposit_promotion_achieve_amount_decision($promotion_amount=NULL, $promotionData = NULL, $reward_amount = NULL, $deposit_amount = NULL){
		$game_ids = "";
		$achieve_amount = 0;
		$multiply = 0;
		$index = 0;
		$level = 0;
		
		if($promotionData['bonus_range_type'] == PROMOTION_BONUS_RANGE_TYPE_GENERAL){
			$achieve_amount = (($promotion_amount + $reward_amount) * $promotionData['turnover_multiply']) + ($deposit_amount-$promotion_amount);
			$multiply = $promotionData['turnover_multiply']; 
			$game_ids = $promotionData['game_ids'];
		}else{
			$promotionBonusRangeData = $this->get_deposit_promotion_bonus_range_decision($promotion_amount,$promotionData);
			if(!empty($promotionBonusRangeData)){
				if($promotionBonusRangeData['bonus_type'] == PROMOTION_BONUS_TYPE_PERCENTAGE){
			        $achieve_amount = (($promotion_amount + $reward_amount) * $promotionBonusRangeData['turnover_multiply']) + ($deposit_amount-$promotion_amount);
    				$level = $promotionBonusRangeData['bonus_level'];
    				$index = $promotionBonusRangeData['bonus_index'];
    				$multiply = $promotionBonusRangeData['turnover_multiply'];
    				$game_ids = $promotionBonusRangeData['game_ids'];
    			}else if($promotionBonusRangeData['bonus_type'] == PROMOTION_BONUS_TYPE_PERCENTAGE_TURNOVER){
    				$achieve_amount = ($deposit_amount - ($reward_amount / $promotionBonusRangeData['percentage'] * 100)) + ($reward_amount * $promotionBonusRangeData['turnover_multiply']) + (($reward_amount / $promotionBonusRangeData['percentage'] * 100) * $promotionBonusRangeData['turnover_multiply']);
    				$level = $promotionBonusRangeData['bonus_level'];
    				$index = $promotionBonusRangeData['bonus_index'];
    				$multiply = $promotionBonusRangeData['turnover_multiply'];
    				$game_ids = $promotionBonusRangeData['game_ids'];
			    }else if($promotionBonusRangeData['bonus_type'] == PROMOTION_BONUS_TYPE_FIX_AMOUNT){
			        $achieve_amount = (($promotion_amount + $reward_amount) * $promotionBonusRangeData['turnover_multiply']) + ($deposit_amount-$promotion_amount);
    				$level = $promotionBonusRangeData['bonus_level'];
    				$index = $promotionBonusRangeData['bonus_index'];
    				$multiply = $promotionBonusRangeData['turnover_multiply'];
    				$game_ids = $promotionBonusRangeData['game_ids'];   
			    }else{
			        $achieve_amount = (($promotionBonusRangeData['amount_from'] + $reward_amount) * $promotionBonusRangeData['turnover_multiply']) + ($deposit_amount-$promotionBonusRangeData['amount_from']);
    				$level = $promotionBonusRangeData['bonus_level'];
    				$index = $promotionBonusRangeData['bonus_index'];
    				$multiply = $promotionBonusRangeData['turnover_multiply'];
    				$game_ids = $promotionBonusRangeData['game_ids'];
			    }
			}
		}
		$achieve_amount_data = array(
			'amount' => $achieve_amount,
			'index' => $index, 
			'level' => $level,
			'multiply' => $multiply,
			'game_ids' => $game_ids,
		);
		return $achieve_amount_data;
	}

	public function get_all_promotion_bonus_range($promotionData = NULL){
		$result = NULL;
		$this->db->from('promotion_bonus_range');
		$this->db->where('promotion_id',$promotionData['promotion_id']);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_deposit_promotion_bonus_range_decision($promotion_amount=NULL,$promotionData = NULL){
		$result = NULL;
		$this->db->from('promotion_bonus_range');
		$this->db->where('promotion_id',$promotionData['promotion_id']);
		$this->db->where('active', STATUS_ACTIVE);
		$this->db->where('amount_from <=',$promotion_amount);
		$this->db->group_start();
			$this->db->where('amount_to >=',$promotion_amount);
			$this->db->or_where('amount_to',0);
		$this->db->group_end();
		$this->db->order_by('bonus_level',"ASC");
		$this->db->order_by('amount_from',"ASC");
		$this->db->order_by('game_ids',"DESC");
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}else{
			$query->free_result();
			$result = NULL;
			$this->db->from('promotion_bonus_range');
			$this->db->where('promotion_id',$promotionData['promotion_id']);
			$this->db->where('active', STATUS_ACTIVE);
			$this->db->where('amount_from <=',$promotion_amount);
			$this->db->order_by('amount_to',"DESC");
			$this->db->limit(1);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				$result = $query->row_array();
			}
		}
		return $result;
	}

	public function get_promotion_bonus_range_active_data_with_amount($id = NULL, $amount = NULL){
		$result = NULL;

		$query = $this
				->db
				->where('promotion_id', $id)
				->where('active', STATUS_ACTIVE)
				->where('amount_from >= ', $amount)
				->order_by('amount_to',"ASC")
				->limit(1)
				->get($this->table_promotion_bonus_range);

		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_promotion_bonus_range_active_data_with_amount_to($id = NULL, $amount = NULL){
		$result = NULL;

		$query = $this
				->db
				->where('promotion_id', $id)
				->where('active', STATUS_ACTIVE)
				->where('amount_from <= ', $amount)
				->where('amount_to >= ', $amount)
				->order_by('amount_to',"ASC")
				->limit(1)
				->get($this->table_promotion_bonus_range);

		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_promotion_bonus_range_active_data_with_amount_last($id = NULL, $amount = NULL){
		$result = NULL;

		$query = $this
				->db
				->where('promotion_id', $id)
				->where('active', STATUS_ACTIVE)
				->order_by('amount_to',"DESC")
				->limit(1)
				->get($this->table_promotion_bonus_range);

		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_player_total_promotion_reward_today($id = NULL, $player_id = NULL){
		$start_date = strtotime(date('Y-m-d 00:00:00'));
		$end_date = strtotime(date('Y-m-d 23:59:59'));

		$result = NULL;
		$query = $this
				->db
				->select('SUM(reward_amount) AS total')
				->where('created_date >=', $start_date)
				->where('created_date <=', $end_date)
				->where('status != ',STATUS_VOID)
				->where('promotion_id', $id)
				->where('player_id', $player_id)
				->get($this->table_player_promotion);

		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_player_total_promotion_reward_range($id = NULL, $player_id = NULL, $start_date = NULL, $end_date = NULL){
		$result = NULL;
		$this->db->select('SUM(reward_amount) AS total');
		$this->db->where('created_date >=', $start_date);
		if(!empty($end_date)){
			$this->db->where('created_date <=', $end_date);
		}
		$this->db->where('status != ',STATUS_VOID);
		$this->db->where('promotion_id', $id);
		$this->db->where('player_id', $player_id);
		$query = $this->db->get($this->table_player_promotion);
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		
		$query->free_result();
		
		return $result;
	}
}