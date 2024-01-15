<?php
class Player_promotion_model extends CI_Model {

	//Declare database tables
	protected $table_cash_transfer_report = 'cash_transfer_report';
	protected $table_player_promotion = 'player_promotion';
	protected $table_players = 'players';
	protected $table_promotions = 'promotion';

	public function get_promotion_list_data_apply_admin(){
		$result = array();
		$this->db->where('active', STATUS_ACTIVE);
		$this->db->where_not_in('genre_code',array(PROMOTION_TYPE_BN,PROMOTION_TYPE_LE,PROMOTION_TYPE_RP));
		$this->db->like('apply_type', ','.PROMOTION_USER_TYPE_ADMIN.',');
		$this->db->where('start_date <= ',time());
		$this->db->group_start();
			$this->db->where('end_date >= ',time());
			$this->db->or_where('end_date',0);
		$this->db->group_end();
		$this->db->order_by('promotion_seq', 'ASC');
		$query = $this->db->get($this->table_promotions);
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
		}
		$query->free_result();
		return $result;
	}

	public function get_promotion_list_data_apply_system_genre_code($genre_code = NULL){
		$result = array();
		$this->db->where('active', STATUS_ACTIVE);
		$this->db->where('genre_code',$genre_code);
		$this->db->like('apply_type', ','.PROMOTION_USER_TYPE_SYSTEM.',');
		$this->db->where('start_date <= ',time());
		$this->db->group_start();
			$this->db->where('end_date >= ',time());
			$this->db->or_where('end_date',0);
		$this->db->group_end();
		$this->db->order_by('promotion_seq', 'ASC');
		$query = $this->db->get($this->table_promotions);
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
		}
		$query->free_result();
		return $result;
	}

	public function get_player_promotion_data($id = NULL){
		$result = NULL;

		$query = $this
				->db
				->where('player_promotion_id', $id)
				->get($this->table_player_promotion);

		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_player_promotion_data_by_deposit_id($id = NULL){
		$result = NULL;

		$query = $this
				->db
				->where('deposit_id', $id)
				->get($this->table_player_promotion);

		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_unsattle_promotion($arr){
		$this->db->where('player_id',$arr['player_id']);
		$this->db->where_in('status', array(STATUS_ENTITLEMENT,STATUS_ACCOMPLISH));
		$this->db->limit(1);
		$query = $this->db->get($this->table_player_promotion);

		$result = ($query->num_rows() > 0) ? $query->row_array() : array();
		$query->free_result();
		return $result;
	}

	public function get_player_promotion_unsattle($player_id = NULL){
		$this->db->select('player_promotion_id');
		$this->db->where('player_id',$player_id);
		$this->db->where_in('status', array(STATUS_ENTITLEMENT,STATUS_ACCOMPLISH));
		$query = $this->db->get($this->table_player_promotion);

		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_player_promotion_data_unsattle($player_id = NULL){
		$this->db->select('player_promotion_id,achieve_amount,current_amount');
		$this->db->where('player_id',$player_id);
		$this->db->where_in('status', array(STATUS_ENTITLEMENT,STATUS_ACCOMPLISH));
		$query = $this->db->get($this->table_player_promotion);

		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_promotion_list_data_refferer_duplicate($player_id = NULL, $promotion_ids = NULL){
		$this->db->select('player_promotion_id,promotion_id');
		$this->db->where('player_referrer_id',$player_id);
		$this->db->where('status != ', STATUS_VOID);
		$this->db->where_in('promotion_id',$promotion_ids);
		$query = $this->db->get($this->table_player_promotion);

		if($query->num_rows() > 0)
		{
			$result_query = $query->result_array();
			if(!empty($result_query)){
				foreach($result_query as $result_query_row){
					$result[$result_query_row['promotion_id']] = $result_query_row['player_promotion_id'];
				}
			}
		}
		$query->free_result();
		return $result;
	}

	public function get_promotion_list_data_apply_admin_only_direct(){
		$result = array();
		$this->db->where('active', STATUS_ACTIVE);
		$this->db->where_in('genre_code',array(PROMOTION_TYPE_DPR,PROMOTION_TYPE_DPRC));
		$this->db->like('apply_type', ','.PROMOTION_USER_TYPE_ADMIN.',');
		$this->db->where('start_date <= ',time());
		$this->db->group_start();
			$this->db->where('end_date >= ',time());
			$this->db->or_where('end_date',0);
		$this->db->group_end();
		$this->db->order_by('promotion_seq', 'ASC');
		$query = $this->db->get($this->table_promotions);
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
		}
		$query->free_result();
		return $result;
	}

	public function get_all_pending_promotion_by_ids($player_promotion_ids = NULL){
		$this->db->where_in('player_promotion_id',$player_promotion_ids);
		$this->db->where('status', STATUS_PENDING);
		$query = $this->db->get($this->table_player_promotion);

		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_all_unsattle_promotion_by_ids($player_promotion_ids = NULL){
		$this->db->where_in('player_promotion_id',$player_promotion_ids);
		$this->db->where_in('status', array(STATUS_ENTITLEMENT,STATUS_ACCOMPLISH,STATUS_PENDING));
		$query = $this->db->get($this->table_player_promotion);

		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		$query->free_result();
		return $result;
	}
	
	// Update player status
	public function updateStatus($player_id)
	{	
		$this->db->set('status', STATUS_CANCEL);
		$this->db->where('player_id', $player_id);
		$this->db->where_in('status', [STATUS_PENDING]);
		$this->db->where_in('genre_code', ['DE', 'DPR']);
		$this->db->update($this->table_player_promotion);
	}

	public function updateStatusWelcomeBonus($player_id, $status, $promotionDepositPending){
		$this->db->set('status', $status);
		$this->db->join('deposits', 'deposits.promotion_id = player_promotion.promotion_id');
		$this->db->where('player_promotion.player_id', $player_id);
		$this->db->where('player_promotion.promotion_id', $promotionDepositPending['promotion_id']);
		$this->db->update($this->table_player_promotion);
	}
}