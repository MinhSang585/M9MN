<?php
class Log_model extends CI_Model {
	protected $table_user_logs = 'user_logs';
	protected $table_sub_account_logs = 'sub_account_logs';
	protected $table_player_logs = 'player_logs';

	public function get_user_log_data($id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('user_log_id', $id)
				->where_in('log_type',get_admin_log())
				->limit(1)
				->get($this->table_user_logs);
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}
	
	// Get player_log_id
	public function getPlayerLogData($id = NULL){

		$result = NULL;

		$query = $this

				->db

				->where('player_log_id', $id)

				->where_in('log_type',get_admin_log())

				->limit(1)

				->get($this->table_player_logs);

		if($query->num_rows() > 0)

		{

			$result = $query->row_array();  

		}

		$query->free_result();

		return $result;

	}
	
	/// abc
}