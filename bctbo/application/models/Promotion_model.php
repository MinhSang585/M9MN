<?php
class Promotion_model extends CI_Model {
	protected $table_promotion = 'promotion';
	protected $table_promotion_genre = 'promotion_genre';
	protected $table_promotion_bonus_range = 'promotion_bonus_range';
	protected $table_promotion_lang = 'promotion_lang';
	protected $table_deposits = 'deposits';
	protected $table_player_promotion = 'player_promotion';

	public function get_promotion_genre_list(){
		$result = NULL;
		$query = $this
				->db
				->select('promotion_genre_id, genre_code, genre_name')
				->where('active', STATUS_ACTIVE)
				->order_by('promotion_genre_id','ASC')
				->get($this->table_promotion_genre);

		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

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

	public function get_promotion_genre_data($genre_code = NULL){
		$result = NULL;
		$query = $this
				->db
				->select('promotion_genre_id, genre_code, genre_name')
				->where('genre_code', $genre_code)
				->where('active', STATUS_ACTIVE)
				->limit(1)
				->get($this->table_promotion_genre);

		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}

	public function add_promotion($promotion_genre_data = NULL){
		$date_type = trim($this->input->post('date_type', TRUE));

		//Date Setting
		$start_date = '';
		$end_date = '';
		$specific_day_week = '';
		$specific_day_day = '';
		if($date_type == PROMOTION_DATE_TYPE_START_TO_END){
			$start_date = (($this->input->post('start_date', TRUE)) ? strtotime($this->input->post('start_date', TRUE)) : '');
			$end_date = (($this->input->post('end_date', TRUE)) ? strtotime($this->input->post('end_date', TRUE)) : '');
		}else if($date_type == PROMOTION_DATE_TYPE_START_NO_LIMIT){
			$start_date = (($this->input->post('start_date', TRUE)) ? strtotime($this->input->post('start_date', TRUE)) : '');
		}else if($date_type == PROMOTION_DATE_TYPE_SPECIFIC_DAY_WEEK){
			$start_date = (($this->input->post('start_date', TRUE)) ? strtotime($this->input->post('start_date', TRUE)) : '');
			$end_date = (($this->input->post('end_date', TRUE)) ? strtotime($this->input->post('end_date', TRUE)) : '');
			$specific_day_week =  (($this->input->post('specific_day_week[]', TRUE)) ? implode(',', $this->input->post('specific_day_week[]', TRUE)) : '');
		}else if($date_type == PROMOTION_DATE_TYPE_SPECIFIC_DAY_DAY){
			$start_date = (($this->input->post('start_date', TRUE)) ? strtotime($this->input->post('start_date', TRUE)) : '');
			$end_date = (($this->input->post('end_date', TRUE)) ? strtotime($this->input->post('end_date', TRUE)) : '');
			$specific_day_day = (($this->input->post('specific_day_day[]', TRUE)) ? implode(',', $this->input->post('specific_day_day[]', TRUE)) : '');
		}

		//Calculation setting
		$bonus_range_type = trim($this->input->post('bonus_range_type', TRUE));
		$bonus_type = trim($this->input->post('bonus_type', TRUE));
		$rebate_percentage = '0';
		$max_rebate = '0';
		$rebate_amount = '0';
		$turnover_multiply = "0";

		if($bonus_range_type==PROMOTION_BONUS_RANGE_TYPE_GENERAL){
			if($bonus_type==PROMOTION_BONUS_TYPE_PERCENTAGE){
				$rebate_percentage = $this->input->post('rebate_percentage', TRUE);
				$max_rebate = $this->input->post('max_rebate', TRUE);
				$turnover_multiply = $this->input->post('turnover_multiply', TRUE);
			}else if($bonus_type==PROMOTION_BONUS_TYPE_FIX_AMOUNT){
				$rebate_amount = $this->input->post('rebate_amount', TRUE);
				$turnover_multiply = $this->input->post('turnover_multiply', TRUE);
			}
		}


		$apply_type = '';
		$claim_type = '';
		$calculate_day_type = '';
		$calculate_hour = '';
		$calculate_minute = '';
		$reward_day_type = '';
		$reward_hour = '';
		$reward_minute = '';
		if(!empty($this->input->post('apply_type[]', TRUE))){
			$apply_type = (($this->input->post('apply_type[]', TRUE)) ? ','.implode(',', $this->input->post('apply_type[]', TRUE)).',' : '');
			if(in_array(PROMOTION_USER_TYPE_SYSTEM, $this->input->post('apply_type[]', TRUE))){
				$claim_type = $this->input->post('claim_type', TRUE);
				$calculate_day_type = $this->input->post('calculate_day_type', TRUE);
				$calculate_hour = $this->input->post('calculate_hour', TRUE);
				$calculate_minute = $this->input->post('calculate_minute', TRUE);
				if($claim_type == PROMOTION_USER_TYPE_SYSTEM){
					$reward_day_type = $this->input->post('reward_day_type', TRUE);
					$reward_hour = $this->input->post('reward_hour', TRUE);
					$reward_minute = $this->input->post('reward_minute', TRUE);
				}
			}
		}

		$DBdata =  array(
			'promotion_name' => $this->input->post('promotion_name', TRUE),
			'url_path' => $this->input->post('url_path', TRUE),
			'promotion_seq' => $this->input->post('promotion_seq', TRUE),
			'genre_code' => $promotion_genre_data['genre_code'],
			'genre_name' => $promotion_genre_data['genre_name'],
			'date_type' => $this->input->post('date_type', TRUE),
			'start_date' => $start_date,
			'end_date' => $end_date,
			'specific_day_week' => $specific_day_week,
			'specific_day_day' => $specific_day_day,
			'is_count_total_today' => (($this->input->post('is_count_total_today', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'reward_on_apply' => (($this->input->post('reward_on_apply', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'withdrawal_on_check' => (($this->input->post('withdrawal_on_check', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_auto_complete' => (($this->input->post('is_auto_complete', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'level' => $this->input->post('level', TRUE),
			'accumulate_deposit' => $this->input->post('accumulate_deposit', TRUE),
			'is_deposit_tied_promotion_count' => (($this->input->post('is_deposit_tied_promotion_count', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'apply_type' => $apply_type,
			'date_expirate_type' => $this->input->post('date_expirate_type', TRUE),
			'times_limit_type' => $this->input->post('times_limit_type', TRUE),
			'is_apply_on_first_day_of_times_limit_type' => (($this->input->post('is_apply_on_first_day_of_times_limit_type', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_starting_of_the_day' => (($this->input->post('is_starting_of_the_day', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'claim_type' => $claim_type,
			'calculate_day_type' => $calculate_day_type,
			'calculate_hour' => $calculate_hour,
			'calculate_minute' => $calculate_minute,
			'reward_day_type' => $reward_day_type,
			'reward_hour' => $reward_hour,
			'reward_minute'  => $reward_minute,
			'first_deposit' => (($this->input->post('first_deposit', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'daily_first_deposit' => (($this->input->post('daily_first_deposit', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'min_deposit' => $this->input->post('min_deposit', TRUE),
			'max_deposit' => $this->input->post('max_deposit', TRUE),
			'deposit_history' => $this->input->post('deposit_history', TRUE),
			'reward_amount' => $this->input->post('reward_amount', TRUE),
			'calculate_type' => $this->input->post('calculate_type', TRUE),
			'target_type' => $this->input->post('target_type', TRUE),
			'complete_wallet_left' => $this->input->post('complete_wallet_left', TRUE),
			'bonus_range_type' => $this->input->post('bonus_range_type', TRUE),
			'bonus_type' => $this->input->post('bonus_type', TRUE),
			'turnover_multiply' => $turnover_multiply,
			'rebate_percentage' => $rebate_percentage,
			'max_rebate' => $max_rebate,
			'max_promotion' => $this->input->post('max_promotion', TRUE),
			'rebate_amount' => $rebate_amount,
			'is_deposit_level_fixed' => (($this->input->post('is_deposit_level_fixed', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'game_ids' => (($this->input->post('game_ids[]', TRUE)) ? ','.implode(',', $this->input->post('game_ids[]', TRUE)).',' : ''),
			'game_ids_all' => (($this->input->post('game_ids_all', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'live_casino_type' => (($this->input->post('live_casino_type[]', TRUE)) ? implode(',', $this->input->post('live_casino_type[]', TRUE)) : ''),
			'is_level' => (($this->input->post('is_level', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_banner' => (($this->input->post('is_banner', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'banner_category' => (($this->input->post('banner_category[]', TRUE)) ? ','.implode(',', $this->input->post('banner_category[]', TRUE)).',' : ''),
			'apply_allow_date' => (($this->input->post('apply_allow_date[]', TRUE)) ? ','.implode(',', $this->input->post('apply_allow_date[]', TRUE)).',' : ''),
			'apply_allow_date_number' => (($this->input->post('apply_allow_date_number[]', TRUE)) ? ','.implode(',', $this->input->post('apply_allow_date_number[]', TRUE)).',' : ''),
			'balance_less' => $this->input->post('balance_less', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);

		$this->db->insert($this->table_promotion, $DBdata);
		$DBdata['promotion_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function update_promotion($promotion_genre_data = NULL,$arr = null){
		$date_type = trim($this->input->post('date_type', TRUE));

		//Date Setting
		$start_date = '';
		$end_date = '';
		$specific_day_week = '';
		$specific_day_day = '';
		if($date_type == PROMOTION_DATE_TYPE_START_TO_END){
			$start_date = (($this->input->post('start_date', TRUE)) ? strtotime($this->input->post('start_date', TRUE)) : '');
			$end_date = (($this->input->post('end_date', TRUE)) ? strtotime($this->input->post('end_date', TRUE)) : '');
		}else if($date_type == PROMOTION_DATE_TYPE_START_NO_LIMIT){
			$start_date = (($this->input->post('start_date', TRUE)) ? strtotime($this->input->post('start_date', TRUE)) : '');
		}else if($date_type == PROMOTION_DATE_TYPE_SPECIFIC_DAY_WEEK){
			$start_date = (($this->input->post('start_date', TRUE)) ? strtotime($this->input->post('start_date', TRUE)) : '');
			$end_date = (($this->input->post('end_date', TRUE)) ? strtotime($this->input->post('end_date', TRUE)) : '');
			$specific_day_week =  (($this->input->post('specific_day_week[]', TRUE)) ? implode(',', $this->input->post('specific_day_week[]', TRUE)) : '');
		}else if($date_type == PROMOTION_DATE_TYPE_SPECIFIC_DAY_DAY){
			$start_date = (($this->input->post('start_date', TRUE)) ? strtotime($this->input->post('start_date', TRUE)) : '');
			$end_date = (($this->input->post('end_date', TRUE)) ? strtotime($this->input->post('end_date', TRUE)) : '');
			$specific_day_day = (($this->input->post('specific_day_day[]', TRUE)) ? implode(',', $this->input->post('specific_day_day[]', TRUE)) : '');
		}

		//Calculation setting
		$bonus_range_type = trim($this->input->post('bonus_range_type', TRUE));
		$bonus_type = trim($this->input->post('bonus_type', TRUE));
		$rebate_percentage = '0';
		$max_rebate = '0';
		$rebate_amount = '0';
		$turnover_multiply = "0";

		if($bonus_range_type==PROMOTION_BONUS_RANGE_TYPE_GENERAL){
			if($bonus_type==PROMOTION_BONUS_TYPE_PERCENTAGE){
				$rebate_percentage = $this->input->post('rebate_percentage', TRUE);
				$max_rebate = $this->input->post('max_rebate', TRUE);
				$turnover_multiply = $this->input->post('turnover_multiply', TRUE);
			}else if($bonus_type==PROMOTION_BONUS_TYPE_FIX_AMOUNT){
				$rebate_amount = $this->input->post('rebate_amount', TRUE);
				$turnover_multiply = $this->input->post('turnover_multiply', TRUE);
			}
		}


		$apply_type = '';
		$claim_type = '';
		$calculate_day_type = '';
		$calculate_hour = '';
		$calculate_minute = '';
		$reward_day_type = '';
		$reward_hour = '';
		$reward_minute = '';
		if(!empty($this->input->post('apply_type[]', TRUE))){
			$apply_type = (($this->input->post('apply_type[]', TRUE)) ? ','.implode(',', $this->input->post('apply_type[]', TRUE)).',' : '');
			if(in_array(PROMOTION_USER_TYPE_SYSTEM, $this->input->post('apply_type[]', TRUE))){
				$claim_type = $this->input->post('claim_type', TRUE);
				$calculate_day_type = $this->input->post('calculate_day_type', TRUE);
				$calculate_hour = $this->input->post('calculate_hour', TRUE);
				$calculate_minute = $this->input->post('calculate_minute', TRUE);
				if($claim_type == PROMOTION_USER_TYPE_SYSTEM){
					$reward_day_type = $this->input->post('reward_day_type', TRUE);
					$reward_hour = $this->input->post('reward_hour', TRUE);
					$reward_minute = $this->input->post('reward_minute', TRUE);
				}
			}
		}

		$DBdata =  array(
			'promotion_name' => $this->input->post('promotion_name', TRUE),
			'url_path' => $this->input->post('url_path', TRUE),
			'promotion_seq' => $this->input->post('promotion_seq', TRUE),
			'genre_code' => $promotion_genre_data['genre_code'],
			'genre_name' => $promotion_genre_data['genre_name'],
			'date_type' => $this->input->post('date_type', TRUE),
			'start_date' => $start_date,
			'end_date' => $end_date,
			'specific_day_week' => $specific_day_week,
			'specific_day_day' => $specific_day_day,
			'is_count_total_today' => (($this->input->post('is_count_total_today', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'reward_on_apply' => (($this->input->post('reward_on_apply', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'withdrawal_on_check' => (($this->input->post('withdrawal_on_check', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_auto_complete' => (($this->input->post('is_auto_complete', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'level' => $this->input->post('level', TRUE),
			'accumulate_deposit' => $this->input->post('accumulate_deposit', TRUE),
			'is_deposit_tied_promotion_count' => (($this->input->post('is_deposit_tied_promotion_count', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'apply_type' => $apply_type,
			'date_expirate_type' => $this->input->post('date_expirate_type', TRUE),
			'times_limit_type' => $this->input->post('times_limit_type', TRUE),
			'is_apply_on_first_day_of_times_limit_type' => (($this->input->post('is_apply_on_first_day_of_times_limit_type', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_starting_of_the_day' => (($this->input->post('is_starting_of_the_day', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'claim_type' => $claim_type,
			'calculate_day_type' => $calculate_day_type,
			'calculate_hour' => $calculate_hour,
			'calculate_minute' => $calculate_minute,
			'reward_day_type' => $reward_day_type,
			'reward_hour' => $reward_hour,
			'reward_minute'  => $reward_minute,
			'first_deposit' => (($this->input->post('first_deposit', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'daily_first_deposit' => (($this->input->post('daily_first_deposit', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'min_deposit' => $this->input->post('min_deposit', TRUE),
			'max_deposit' => $this->input->post('max_deposit', TRUE),
			'deposit_history' => $this->input->post('deposit_history', TRUE),
			'reward_amount' => $this->input->post('reward_amount', TRUE),
			'calculate_type' => $this->input->post('calculate_type', TRUE),
			'target_type' => $this->input->post('target_type', TRUE),
			'complete_wallet_left' => $this->input->post('complete_wallet_left', TRUE),
			'bonus_range_type' => $this->input->post('bonus_range_type', TRUE),
			'bonus_type' => $this->input->post('bonus_type', TRUE),
			'turnover_multiply' => $turnover_multiply,
			'rebate_percentage' => $rebate_percentage,
			'max_rebate' => $max_rebate,
			'max_promotion' => $this->input->post('max_promotion', TRUE),
			'rebate_amount' => $rebate_amount,
			'is_deposit_level_fixed' => (($this->input->post('is_deposit_level_fixed', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'game_ids' => (($this->input->post('game_ids[]', TRUE)) ? ','.implode(',', $this->input->post('game_ids[]', TRUE)).',' : ''),
			'game_ids_all' => (($this->input->post('game_ids_all', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'live_casino_type' => (($this->input->post('live_casino_type[]', TRUE)) ? implode(',', $this->input->post('live_casino_type[]', TRUE)) : ''),
			'is_level' => (($this->input->post('is_level', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_banner' => (($this->input->post('is_banner', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'banner_category' => (($this->input->post('banner_category[]', TRUE)) ? ','.implode(',', $this->input->post('banner_category[]', TRUE)).',' : ''),
			'apply_allow_date' => (($this->input->post('apply_allow_date[]', TRUE)) ? ','.implode(',', $this->input->post('apply_allow_date[]', TRUE)).',' : ''),
			'apply_allow_date_number' => (($this->input->post('apply_allow_date_number[]', TRUE)) ? ','.implode(',', $this->input->post('apply_allow_date_number[]', TRUE)).',' : ''),
			'balance_less' => $this->input->post('balance_less', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);

		$this->db->where('promotion_id', $arr['promotion_id']);
		$this->db->limit(1);
		$this->db->update($this->table_promotion, $DBdata);
		$DBdata['promotion_id'] = $arr['promotion_id'];
		
		return $DBdata;
	}

	public function add_promotion_bonus_range($promotion_genre_data = NULL, $promotion_data = NULL){
		$promotion_id = $promotion_data['promotion_id'];
		for($i=1;$i<=BONUS_RANGE_NUMBER;$i++){
			$DBdata =  array(
				'promotion_id' => $promotion_id,
				'bonus_index' => $i,
				'bonus_level' => $this->input->post('bonus_range_bonus_level_'.$i, TRUE),
				'bonus_type' => $this->input->post('bonus_type_'.$i, TRUE),
				'game_ids' => (($this->input->post('bonus_range_game_exclude_'.$i.'[]', TRUE)) ? ','.implode(',', $this->input->post('bonus_range_game_exclude_'.$i.'[]', TRUE)).',' : ''),
				'turnover_multiply' => $this->input->post('turnover_multiply_range_'.$i, TRUE),
				'amount_from' => $this->input->post('bonus_range_amount_from_'.$i, TRUE),
				'amount_to' =>	$this->input->post('bonus_range_amount_to_'.$i, TRUE),
				'bonus_amount' => $this->input->post('bonus_range_bonus_amount_'.$i, TRUE),
				'percentage' => $this->input->post('bonus_range_percentage_'.$i, TRUE),
				'max_amount' => $this->input->post('bonus_range_max_amount_'.$i, TRUE),
				'active' => (($this->input->post('bonus_range_option_'.$i, TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			);
			$this->db->insert($this->table_promotion_bonus_range, $DBdata);
		}
	}

	public function get_promotion_bonus_range_data($id = NULL){
		$result = NULL;

		$query = $this
				->db
				->where('promotion_id', $id)
				->get($this->table_promotion_bonus_range);

		if($query->num_rows() > 0)
		{
			$result_query = $query->result_array();
			foreach($result_query as $row){
				$result[$row['bonus_index']] = $row;
			}
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_promotion_bonus_range($id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('promotion_id', $id)
				->get($this->table_promotion_bonus_range);

		if($query->num_rows() > 0)
		{
			$result_query = $query->result_array();
			foreach($result_query as $result_query_row){
				$result[$result_query_row['bonus_index']] = array(
					'bonus_index' => $result_query_row['bonus_index'],
					'bonus_type' => $result_query_row['bonus_type'],
					'bonus_level' => $result_query_row['bonus_level'],
					'game_ids' => $result_query_row['game_ids'],
					'turnover_multiply' => $result_query_row['turnover_multiply'],
					'amount_from' => $result_query_row['amount_from'],
					'amount_to' =>	$result_query_row['amount_to'],
					'bonus_amount' => $result_query_row['bonus_amount'],
					'percentage' => $result_query_row['percentage'],
					'max_amount' => $result_query_row['max_amount'],
					'active' => $result_query_row['active'],
				);
			}
		}
		$query->free_result();
		return $result;
	}

	public function get_promotion_bonus_range_active_data($id = NULL){
		$result = NULL;

		$query = $this
				->db
				->where('promotion_id', $id)
				->where('active', STATUS_ACTIVE)
				->get($this->table_promotion_bonus_range);

		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_promotion_data_all($id = NULL){
		$result = NULL;

		$query = $this
				->db
				->where('promotion_id', $id)
				->limit(1)
				->get($this->table_promotion);

		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_promotion_lang_data($id = NULL){
		$result = NULL;

		$query = $this
				->db
				->where('promotion_id', $id)
				->get($this->table_promotion_lang);

		if($query->num_rows() > 0)
		{
			$result_query = $query->result_array();
			foreach($result_query as $row){
				$result[$row['language_id']] = $row;
			}
		}
		
		$query->free_result();
		
		return $result;
	}

	public function delete_promotion($promotion_id){
		$this->db->where('promotion_id',  $promotion_id);
		$this->db->delete($this->table_promotion);
	}

	public function delete_promotion_lang($promotion_id){
		$this->db->where('promotion_id',  $promotion_id);
		$this->db->delete($this->table_promotion_lang);
	}

	public function delete_promotion_bonus_range($promotion_genre_data = NULL, $promotion_data = NULL){
		$this->db->where('promotion_id',  $promotion_data['promotion_id']);
		$this->db->delete($this->table_promotion_bonus_range);
	}

	public function add_promotion_content($promotion_id=NULL, $language_id = NULL){
		$DBdata = array(
			'promotion_title' => $this->input->post('promotion_title_'.$language_id, TRUE),
			'promotion_content' => html_entity_decode($_POST['promotion_content_'.$language_id]),
			'promotion_id' => $promotion_id,
			'language_id' => $language_id,
		);
		if(isset($_FILES['web_banner_'.$language_id]['size']) && $_FILES['web_banner_'.$language_id]['size'] > 0)
		{
			$DBdata['promotion_banner_web'] = $_FILES['web_banner_'.$language_id]['name'];
		}
		
		if(isset($_FILES['mobile_banner_'.$language_id]['size']) && $_FILES['mobile_banner_'.$language_id]['size'] > 0)
		{
			$DBdata['promotion_banner_mobile'] = $_FILES['mobile_banner_'.$language_id]['name'];
		}
		
		if(isset($_FILES['web_banner_content_'.$language_id]['size']) && $_FILES['web_banner_content_'.$language_id]['size'] > 0)
		{
			$DBdata['promotion_banner_web_content'] = $_FILES['web_banner_content_'.$language_id]['name'];
		}
		
		if(isset($_FILES['mobile_banner_content_'.$language_id]['size']) && $_FILES['mobile_banner_content_'.$language_id]['size'] > 0)
		{
			$DBdata['promotion_banner_mobile_content'] = $_FILES['mobile_banner_content_'.$language_id]['name'];
		}
		
		$this->db->insert($this->table_promotion_lang, $DBdata);
		return $DBdata;
	}

	public function update_promotion_content($promotion_id=NULL, $language_id = NULL){
		$DBdata = array(
			'promotion_title' => $this->input->post('promotion_title_'.$language_id, TRUE),
			'promotion_content' => html_entity_decode($_POST['promotion_content_'.$language_id]),
		);
		if(isset($_FILES['web_banner_'.$language_id]['size']) && $_FILES['web_banner_'.$language_id]['size'] > 0)
		{
			$DBdata['promotion_banner_web'] = $_FILES['web_banner_'.$language_id]['name'];
		}
		
		if(isset($_FILES['mobile_banner_'.$language_id]['size']) && $_FILES['mobile_banner_'.$language_id]['size'] > 0)
		{
			$DBdata['promotion_banner_mobile'] = $_FILES['mobile_banner_'.$language_id]['name'];
		}
		
		if(isset($_FILES['web_banner_content_'.$language_id]['size']) && $_FILES['web_banner_content_'.$language_id]['size'] > 0)
		{
			$DBdata['promotion_banner_web_content'] = $_FILES['web_banner_content_'.$language_id]['name'];
		}
		
		if(isset($_FILES['mobile_banner_content_'.$language_id]['size']) && $_FILES['mobile_banner_content_'.$language_id]['size'] > 0)
		{
			$DBdata['promotion_banner_mobile_content'] = $_FILES['mobile_banner_content_'.$language_id]['name'];
		}

		$this->db->where('promotion_id', $promotion_id);
		$this->db->where('language_id', $language_id);
		$this->db->limit(1);
		$this->db->update($this->table_promotion_lang, $DBdata);
		$DBdata['promotion_id'] = $promotion_id;
		$DBdata['language_id'] = $language_id;
		return $DBdata;
	}

	public function get_all_promotion_lang_data_by_ids($ids = NULL){
		$result = NULL;

		$query = $this
				->db
				->where_in('promotion_id', $ids)
				->get($this->table_promotion_lang);

		if($query->num_rows() > 0)
		{
			$result_query = $query->result_array();
			foreach($result_query as $row){
				$result[$row['promotion_id']][$row['language_id']] = $row;
			}
		}
		
		$query->free_result();
		
		return $result;
	}
}