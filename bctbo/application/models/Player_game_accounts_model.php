<?php
class Player_game_accounts_model extends CI_Model {
	protected $table_player_game_accounts = 'player_game_accounts';

	public function get_all_player_game_accounts_data($id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('player_id', $id)
				->get($this->table_player_game_accounts);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_player_game_accounts_data_by_game_provider_code($id = NULL,$game_provider_code = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('player_id', $id)
				->where('game_provider_code',$game_provider_code)
				->limit(1)
				->get($this->table_player_game_accounts);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_all_player_game_accounts_data_by_game_provider_code($game_provider_code = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('game_provider_code',$game_provider_code)
				->get($this->table_player_game_accounts);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_all_player_game_accounts_data_by_game_provider_code_player_ids($player_ids = NULL,$game_provider_code = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where_in('player_id',$player_ids)
				->where('game_provider_code',$game_provider_code)
				->get($this->table_player_game_accounts);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
}