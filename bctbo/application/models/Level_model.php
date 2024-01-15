<?php

class Level_model extends CI_Model {

	protected $table_players = 'players';

	protected $table_promotion = "promotion";

	protected $table_level = 'level';

	protected $table_level_lang = 'level_lang';

	protected $table_level_log = 'level_log';

	protected $table_level_schedule  = "level_schedule";

	protected $table_promotion_result = "promotion_result";

	protected $table_player_promotion = "player_promotion";



	public function count_level_total($level_id = NULL){

		$result = NULL;

		$this->db->where('level_id', $level_id);

		$result = $this->db->count_all_results($this->table_players);

		return $result;

	}
	
	public function getLevelDataByPlayerLevelId($playerLevelId) {
        
        $query = $this->db->select('*')
            ->from($this->table_level)
            ->where('level_id', $playerLevelId)
            ->get();

       
        if ($query->num_rows() > 0) {
            
            return $query->row();
        } else {
           
            return null;
        }
    }

	public function getLevelDataNew() {
			
		$this->db->order_by('level_target_amount_from', 'ASC');
	
		$query = $this->db->get($this->table_level);
	
		if ($query->num_rows() > 0) {
			$result = $query->result_array();
		}
	
		$query->free_result();
	
		return $result;
	}


	public function get_level_data(){

		$list = array();

		$query = $this

				->db

				->get($this->table_level)
				->order_by('level_target_amount_from', 'ASC');

		

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



	public function get_level_data_by_id($id = null){

		$result = NULL;

		$query = $this

				->db

				->where('level_id', $id)

				->limit(1)

				->get($this->table_level);

		

		if($query->num_rows() > 0)

		{

			$result = $query->row_array();  

		}

		

		$query->free_result();

		

		return $result;

	}



	public function get_level_data_list(){

		$result = NULL;

		$query = $this

				->db

				->where('active',STATUS_ACTIVE)

				->get($this->table_level);

		

		if($query->num_rows() > 0)

		{

			$result = $query->result_array();  

		}

		

		$query->free_result();

		

		return $result;

	}



	public function add_level(){

		$DBdata = array(

			'level_name' => $this->input->post('level_name', TRUE),

			'level_number' => $this->input->post('level_number', TRUE),

			'upgrade_type' => $this->input->post('upgrade_type', TRUE),

			'level_deposit_amount_from' => $this->input->post('level_deposit_amount_from', TRUE),

			'level_deposit_amount_to' => $this->input->post('level_deposit_amount_to', TRUE),

			'level_target_amount_from' => $this->input->post('level_target_amount_from', TRUE),

			'level_target_amount_to' => $this->input->post('level_target_amount_to', TRUE),

			'downgrade_type' => $this->input->post('downgrade_type', TRUE),

			'maintain_membership_limit' => $this->input->post('maintain_membership_limit', TRUE),

			'maintain_membership_deposit_amount' => $this->input->post('maintain_membership_deposit_amount', TRUE),

			'maintain_membership_target_amount' => $this->input->post('maintain_membership_target_amount', TRUE),

			'level_reward_amount' => $this->input->post('level_reward_amount', TRUE),

			'level_rate_sb' => $this->input->post('level_rate_sb', TRUE),

			'level_rate_lc' => $this->input->post('level_rate_lc', TRUE),

			'level_rate_sl' => $this->input->post('level_rate_sl', TRUE),

			'level_rate_fh' => $this->input->post('level_rate_fh', TRUE),

			'level_rate_es' => $this->input->post('level_rate_es', TRUE),

			'level_rate_bg' => $this->input->post('level_rate_bg', TRUE),

			'level_rate_lt' => $this->input->post('level_rate_lt', TRUE),

			'level_rate_kn' => $this->input->post('level_rate_kn', TRUE),

			'level_rate_vs' => $this->input->post('level_rate_vs', TRUE),

			'level_rate_pk' => $this->input->post('level_rate_pk', TRUE),

			'level_rate_cf' => $this->input->post('level_rate_cf', TRUE),

			'level_rate_ot' => $this->input->post('level_rate_ot', TRUE),

			'daily_withdrawal_frequency' => $this->input->post('daily_withdrawal_frequency', TRUE),

			'daily_withdrawal_amount' => $this->input->post('daily_withdrawal_amount', TRUE),

			'reward_point_turn' => $this->input->post('reward_point_turn', TRUE),

			'reward_point' => $this->input->post('reward_point', TRUE),

			'birthday_bonus' => $this->input->post('birthday_bonus', TRUE),

			'birthday_present' => $this->input->post('birthday_present', TRUE),

			'monthly_free_meal' => $this->input->post('monthly_free_meal', TRUE),

			'monthly_phone_bill_rebate' => $this->input->post('monthly_phone_bill_rebate', TRUE),

			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),

			'game_type' => (($this->input->post('game_type_all', TRUE) == STATUS_ACTIVE) ? STATUS_ALL : implode(',', $this->input->post('game_type[]', TRUE))),

			'game_ids' => (($this->input->post('game_ids[]', TRUE)) ? ','.implode(',', $this->input->post('game_ids[]', TRUE)).',' : ''),

			'live_casino_type' => (($this->input->post('live_casino_type[]', TRUE)) ? implode(',', $this->input->post('live_casino_type[]', TRUE)) : ''),

			'calculate_type' => $this->input->post('calculate_type', TRUE),

			'target_type' => $this->input->post('target_type', TRUE),

			'created_by' => $this->session->userdata('username'),

			'created_date' => time()

		);

		$this->db->insert($this->table_level, $DBdata);

		$DBdata['level_id'] = $this->db->insert_id();

		return $DBdata;







	}



	public function update_level($id = null){

		$DBdata = array(

			'level_name' => $this->input->post('level_name', TRUE),

			'upgrade_type' => $this->input->post('upgrade_type', TRUE),

			'level_deposit_amount_from' => $this->input->post('level_deposit_amount_from', TRUE),

			'level_deposit_amount_to' => $this->input->post('level_deposit_amount_to', TRUE),

			'level_target_amount_from' => $this->input->post('level_target_amount_from', TRUE),

			'level_target_amount_to' => $this->input->post('level_target_amount_to', TRUE),

			'downgrade_type' => $this->input->post('downgrade_type', TRUE),

			'maintain_membership_limit' => $this->input->post('maintain_membership_limit', TRUE),

			'maintain_membership_deposit_amount' => $this->input->post('maintain_membership_deposit_amount', TRUE),

			'maintain_membership_target_amount' => $this->input->post('maintain_membership_target_amount', TRUE),

			'level_reward_amount' => $this->input->post('level_reward_amount', TRUE),

			'level_rate_sb' => $this->input->post('level_rate_sb', TRUE),

			'level_rate_lc' => $this->input->post('level_rate_lc', TRUE),

			'level_rate_sl' => $this->input->post('level_rate_sl', TRUE),

			'level_rate_fh' => $this->input->post('level_rate_fh', TRUE),

			'level_rate_es' => $this->input->post('level_rate_es', TRUE),

			'level_rate_bg' => $this->input->post('level_rate_bg', TRUE),

			'level_rate_lt' => $this->input->post('level_rate_lt', TRUE),

			'level_rate_kn' => $this->input->post('level_rate_kn', TRUE),

			'level_rate_vs' => $this->input->post('level_rate_vs', TRUE),

			'level_rate_pk' => $this->input->post('level_rate_pk', TRUE),

			'level_rate_cf' => $this->input->post('level_rate_cf', TRUE),

			'level_rate_ot' => $this->input->post('level_rate_ot', TRUE),

			'daily_withdrawal_frequency' => $this->input->post('daily_withdrawal_frequency', TRUE),

			'daily_withdrawal_amount' => $this->input->post('daily_withdrawal_amount', TRUE),

			'reward_point_turn' => $this->input->post('reward_point_turn', TRUE),

			'reward_point' => $this->input->post('reward_point', TRUE),

			'birthday_bonus' => $this->input->post('birthday_bonus', TRUE),

			'birthday_present' => $this->input->post('birthday_present', TRUE),

			'monthly_free_meal' => $this->input->post('monthly_free_meal', TRUE),

			'monthly_phone_bill_rebate' => $this->input->post('monthly_phone_bill_rebate', TRUE),

			'game_type' => (($this->input->post('game_type_all', TRUE) == STATUS_ACTIVE) ? STATUS_ALL : implode(',', $this->input->post('game_type[]', TRUE))),

			'game_ids' => (($this->input->post('game_ids[]', TRUE)) ? ','.implode(',', $this->input->post('game_ids[]', TRUE)).',' : ''),

			'live_casino_type' => (($this->input->post('live_casino_type[]', TRUE)) ? implode(',', $this->input->post('live_casino_type[]', TRUE)) : ''),

			'calculate_type' => $this->input->post('calculate_type', TRUE),

			'target_type' => $this->input->post('target_type', TRUE),

			'updated_by' => $this->session->userdata('username'),

			'updated_date' => time()

		);

		$this->db->where('level_id', $id);

		$this->db->limit(1);

		$this->db->update($this->table_level, $DBdata);

		$DBdata['level_id'] = $id;

		return $DBdata;

	}



	public function update_level_content($level_id=NULL, $language_id = NULL){

		$DBdata = array(

			'level_title' => $this->input->post('level_title_'.$language_id, TRUE),

		);

		if(isset($_FILES['web_banner_'.$language_id]['size']) && $_FILES['web_banner_'.$language_id]['size'] > 0)

		{

			$DBdata['level_banner_web'] = $_FILES['web_banner_'.$language_id]['name'];

		}

		

		if(isset($_FILES['mobile_banner_'.$language_id]['size']) && $_FILES['mobile_banner_'.$language_id]['size'] > 0)

		{

			$DBdata['level_banner_mobile'] = $_FILES['mobile_banner_'.$language_id]['name'];

		}



		$this->db->where('level_id', $level_id);

		$this->db->where('language_id', $language_id);

		$this->db->limit(1);

		$this->db->update($this->table_level_lang, $DBdata);

		$DBdata['level_id'] = $level_id;

		$DBdata['language_id'] = $language_id;

		return $DBdata;

	}



	public function add_level_content($level_id=NULL, $language_id = NULL){

		$DBdata = array(

			'level_title' => $this->input->post('level_title_'.$language_id, TRUE),

			'level_id' => $level_id,

			'language_id' => $language_id,

		);

		if(isset($_FILES['web_banner_'.$language_id]['size']) && $_FILES['web_banner_'.$language_id]['size'] > 0)

		{

			$DBdata['level_banner_web'] = $_FILES['web_banner_'.$language_id]['name'];

		}

		

		if(isset($_FILES['mobile_banner_'.$language_id]['size']) && $_FILES['mobile_banner_'.$language_id]['size'] > 0)

		{

			$DBdata['level_banner_mobile'] = $_FILES['mobile_banner_'.$language_id]['name'];

		}

		$this->db->insert($this->table_level_lang, $DBdata);

		return $DBdata;

	}



	public function get_level_lang_data($id = NULL){

		$result = NULL;



		$query = $this

				->db

				->where('level_id', $id)

				->get($this->table_level_lang);



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



	public function get_level_movement($schedule_id = NULL,$movement_status = NULL){

		$result = NULL;

		$this->db->where('schedule_id', $schedule_id);

		$this->db->where('movement', $movement_status);

		$result = $this->db->count_all_results($this->table_level_log);

		return $result;

	}



	public function get_level_status($schedule_id = NULL,$status = NULL){

		$result = NULL;

		$this->db->where('schedule_id', $schedule_id);

		$this->db->where('movement !=', LEVEL_MOVEMENT_NONE);

		$this->db->where('status', $status);

		$result = $this->db->count_all_results($this->table_level_log);

		return $result;

	}



	public function get_schedule_data($id = NULL){

		$result = NULL;

		$query = $this

				->db

				->where('schedule_id', $id)

				->limit(1)

				->get($this->table_level_schedule);

		

		if($query->num_rows() > 0)

		{

			$result = $query->row_array();  

		}

		

		$query->free_result();

		

		return $result;

	}



	public function get_schedule_pending_data(){

		$result = NULL;

		$query = $this

				->db

				->select('schedule_id')

				->where('status', STATUS_PENDING)

				->order_by('created_date',"ASC")

				->get($this->table_level_schedule);

		if($query->num_rows() > 0)

		{

			$result = $query->result_array();  

		}

		$query->free_result();

		return $result;

	}



	public function get_level_log_pending_data($schedule_id = NULL,$movement = NULL){

		$result = NULL;

		$query = $this

				->db

				->where('schedule_id', $schedule_id)

				->where('movement', $movement)

				->where('status', STATUS_PENDING)

				->get($this->table_level_log);

		if($query->num_rows() > 0)

		{

			$result = $query->result_array();  

		}

		$query->free_result();

		return $result;

	}



	public function get_all_level_log_pending_data($schedule_id = NULL,$movement = NULL){

		$result = NULL;

		$query = $this

				->db

				->where('schedule_id', $schedule_id)

				->where('status', STATUS_PENDING)

				->get($this->table_level_log);

		if($query->num_rows() > 0)

		{

			$result = $query->result_array();  

		}

		$query->free_result();

		return $result;

	}



	public function update_player_ranking($level_log = NULL){

		$result = array('level_id' => $level_log['player_rating_new_number']);

		$this->db->where('player_id', $level_log['player_id']);

		$this->db->limit(1);

		$this->db->update($this->table_players, $result);

	}



	public function update_player_ranking_ids($player_data = NULL, $level_log = NULL){

		$result = array(

			'level_ids' => (empty($player_data['level_ids']) ? ',' . $level_log['player_rating_new_number'] . ',' : $player_data['level_ids'] . $level_log['player_rating_new_number'] . ','),

		);

		$this->db->where('player_id', $player_data['player_id']);

		$this->db->limit(1);

		$this->db->update($this->table_players, $result);

	}



	public function reset_player_level_maintain($level_log = NULL){

		$result = array('level_maintain' => 0);

		$this->db->where('player_id', $level_log['player_id']);

		$this->db->limit(1);

		$this->db->update($this->table_players, $result);

	}



	public function increase_player_level_maintain($level_log = NULL){

		$DBdata = array(

			'player_id' => $level_log['player_id'],

			'updated_by' => $this->session->userdata('username'),

			'updated_date' => time()

		);

		$table = $this->db->dbprefix . $this->table_players;

		$this->db->query("UPDATE {$table} SET level_maintain = (level_maintain + ?) WHERE player_id = ? LIMIT 1", array(1, $DBdata['player_id']));

		return $DBdata;

	}



	public function update_all_level_log($schedule_id = NULL,$status = NULL){

		$result = array('status' => $status);

		$this->db->where('schedule_id', $schedule_id);

		$this->db->where('status', STATUS_PENDING);

		$this->db->update($this->table_level_log, $result);

	}



	public function get_player_higest_ranking($player_id = NULL){

		$result = NULL;

		$this->db->where('player_id',$player_id);

		$this->db->where('status',STATUS_APPROVE);

		$this->db->order_by('player_rating_new_number','DESC');

		$this->db->limit(1);

		$query = $this->db->get($this->table_level_log);

		if($query->num_rows() > 0)

		{

			$result = $query->row_array();

		}

		$query->free_result();

		return $result;

	}



	public function add_lvling_player_promotion($player_data = NULL, $promotion_data = NULL,$amount = NULL,$status = NULL,$new_level = NULL){

		$DBdata = array(

			'reward_amount' => $amount,

			'player_id' => $player_data['player_id'],

			'promotion_id' => $promotion_data['promotion_id'],

			'promotion_name' => $promotion_data['promotion_name'],

			'genre_code' => $promotion_data['genre_code'],

			'genre_name' => $promotion_data['genre_name'],

			'status' => $status,

			'ranking' => $new_level,

			'created_date' => time(),

			'created_by' => $this->session->userdata('username'),

			'updated_date' => time(),

			'updated_by' => $this->session->userdata('username'),

		);

		if($DBdata['status'] == STATUS_PENDING){

			$DBdata['starting_date'] = time();

			$DBdata['complete_date'] = time();

		}else{

			$DBdata['is_reward'] = STATUS_APPROVE;

			$DBdata['reward_date'] = time();

			$DBdata['starting_date'] = time();

			$DBdata['complete_date'] = time();

		}

		$this->db->insert($this->table_player_promotion, $DBdata);

		$DBdata['player_promotion_id'] = $this->db->insert_id();

		return $DBdata;

	}



	public function get_promotion_result($promotion_code = NULL)

	{	

		$result = NULL;

		

		$query = $this

				->db

				->where('promotion_code', $promotion_code)

				->where('active', STATUS_ACTIVE)

				->limit(1)

				->get($this->table_promotion_result);

		

		if($query->num_rows() > 0)

		{

			$result = $query->row_array();  

		}

		

		$query->free_result();

		

		return $result;

	}



	public function get_promotion_data($promotion_id = NULL){

		$result = NULL;

		$this->db->where('promotion.promotion_id', $promotion_id);

		$this->db->where('promotion.active', STATUS_ACTIVE);

		$this->db->where('promotion.start_date <= ',time());

		$this->db->group_start();

			$this->db->where('promotion.end_date >= ',time());

			$this->db->or_where('promotion.end_date',0);

		$this->db->group_end();

		$this->db->order_by('promotion.promotion_seq', 'ASC');

		$this->db->limit(1);

		$query = $this->db->get($this->table_promotion);

		if($query->num_rows() > 0)

		{

			$result = $query->row_array();	

		}

		$query->free_result();

		return $result;

	}



	public function get_promotion_by_genre_code($genre_code = NULL){

		$result = NULL;

		$this->db->where('promotion.genre_code', $genre_code);

		$this->db->where('promotion.active', STATUS_ACTIVE);

		$this->db->where('promotion.start_date <= ',time());

		$this->db->group_start();

			$this->db->where('promotion.end_date >= ',time());

			$this->db->or_where('promotion.end_date',0);

		$this->db->group_end();

		$this->db->order_by('promotion.promotion_seq', 'ASC');

		$this->db->limit(1);

		$query = $this->db->get($this->table_promotion);

		if($query->num_rows() > 0)

		{

			$result = $query->row_array();	

		}

		$query->free_result();

		return $result;

	}



	public function update_schedule_status($arr = NULL,$status = NULL){

		$result = array('status' => $status);

		$this->db->where('schedule_id', $arr['schedule_id']);

		$this->db->limit(1);

		$this->db->update($this->table_level_schedule, $result);

	}



	public function get_schedule_log_data($id =NULL){

		$result = NULL;

		$query = $this

				->db

				->where('log_id', $id)

				->limit(1)

				->get($this->table_level_log);

		

		if($query->num_rows() > 0)

		{

			$result = $query->row_array();  

		}

		$query->free_result();

		return $result;

	}



	public function update_level_log($log_id = NULL,$accumulate_reward = NULL,$status = NULL){

		$result = array(

			'status' => $status,

			'accumulate_reward' => ((!empty($accumulate_reward)) ? $accumulate_reward : '0'),

			'updated_by' => $this->session->userdata('username'),

			'updated_date' => time()

		);



		$this->db->where('log_id', $log_id);

		$this->db->where('status', STATUS_PENDING);

		$this->db->update($this->table_level_log, $result);

	}





	public function delete_level($level_id = NULL){

		$this->db->where('level_id', $level_id);

		$this->db->delete($this->table_level);

	}



	public function delete_level_lang($level_id = NULL){

		$this->db->where('level_id', $level_id);

		$this->db->delete($this->table_level_lang);

	}



	public function delete_level_log_by_level_id($level_id = NULL){

		$this->db->where('player_rating_old', $level_id);

		$this->db->delete($this->table_level_log);

		$this->db->where('player_rating_new', $level_id);

		$this->db->delete($this->table_level_log);

	}



	public function get_default_level_data(){

		$result = NULL;

		$this->db->where('level_default', STATUS_YES);

		$this->db->limit(1);

		$query = $this->db->get($this->table_level);

		if($query->num_rows() > 0)

		{

			$result = $query->row_array();	

		}

		$query->free_result();

		return $result;

	}



	public function get_player_claimed_level_reward($player_id = NULL){

		$result = array();

		$this->db->select('accumulate_reward');

		$this->db->where('player_id',$player_id);

		$this->db->where('accumulate_reward IS NOT NULL', null, false);

		$query = $this->db->get($this->table_level_log);

		if($query->num_rows() > 0)

		{

			$result_data = $query->result_array();

			foreach($result_data as $result_data_row){

				if(!empty($result_data_row['accumulate_reward'])){

					$arr = array_values(array_filter(explode(',', $result_data_row['accumulate_reward'])));

					$result = array_merge($result, $arr);

				}

			}

		}

		if(!empty($result)){

			$result = array_values(array_unique($result));

		}

		$query->free_result();

		return $result;

	}



	public function get_admin_promotion_by_genre_code($genre_code = NULL){

		$result = NULL;

		$this->db->where('promotion.genre_code', $genre_code);

		$this->db->like('promotion.apply_type', ','.PROMOTION_USER_TYPE_ADMIN.',');

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

	

	public function get_higest_level(){

		$result = NULL;

		$query = $this

				->db

				->select('level_number')

				->order_by('level_number', 'DESC')

				->limit(1)

				->get($this->table_level);

		if($query->num_rows() > 0)

		{

			$result = $query->row_array();  

		}

		$query->free_result();

		return $result;

	}

	/**
	 * Update calculate type in bctp_level_setting table
	 */
	public function updateCalculateType($calculate_type){

		$table_level_setting = $this->db->dbprefix . "level_setting";
		$sql = "UPDATE {$table_level_setting} SET calculate_type = " . $calculate_type;
		$this->db->query($sql);
	}

	/**
	 * Get calculate type in bctp_level_setting table
	 */
	public function getCalculateType(){

		$table_level_setting = $this->db->dbprefix . "level_setting";
		$sql = "SELECT calculate_type FROM {$table_level_setting}";
		$query = $this->db->query($sql);
		$calculate_type = 0;
		if($query->num_rows() > 0)
		{
			$levelSetting = $query->row_array();  

			if(!empty($levelSetting['calculate_type'])){

				$calculate_type = $levelSetting['calculate_type'];

			}
		}
		$query->free_result();
		return $calculate_type;
	}
}