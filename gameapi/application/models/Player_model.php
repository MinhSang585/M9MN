<?php
class Player_model extends CI_Model {
	public function get_player_game_token_data($provider_code = NULL, $username = NULL)
	{
		$result = NULL;
		$query = $this
				->db
				->where('game_provider_code', $provider_code)
				->where('username', $username)
				->limit(1)
				->get('player_game_tokens');
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}
	public function add_player_game_token($provider_code = NULL, $username = NULL)
	{
		$DBdata = array(
			'game_provider_code' => $provider_code,
			'username' => $username
		);
		$this->db->insert('player_game_tokens', $DBdata);
	}
	public function update_player_game_token($provider_code = NULL, $username = NULL, $token = NULL, $language = NULL, $is_demo = 0, $ip_address = NULL)
	{
		$DBdata = array(
			'token' => $token,
			'language' => $language,
			'is_demo' => $is_demo,
			'ip_address' => $ip_address
		);
		$this->db->where('game_provider_code', $provider_code);
		$this->db->where('username', $username);
		$this->db->limit(1);
		$this->db->update('player_game_tokens', $DBdata);
	}
	public function get_player_game_username_by_token($provider_code = NULL, $token = NULL)
	{
		$result = NULL;
		$query = $this
				->db
				->select('username')
				->where('game_provider_code', $provider_code)
				->where('token', $token)
				->limit(1)
				->get('player_game_tokens');
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}
	public function get_player_list_game_id_array($provider_code = NULL)
	{
		$lists = array();
		$query = $this
				->db
				->select('player_id,game_id')
				->where('game_provider_code',$provider_code)
				->get('player_game_accounts');
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row) {
				$lists[strtolower($row->game_id)] = $row->player_id;						
			}
		}
		$query->free_result();
		return $lists;
	}
	public function get_player_list_array()
	{
		$lists = array();
		$query = $this
				->db
				->select('player_id, username')
				->get('players');
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row) {
				$lists[strtolower($row->username)] = $row->player_id;						
			}
		}
		$query->free_result();
		return $lists;
	}
	public function get_player_detail_by_game_id_data($provider_code = NULL, $game_id = NULL)
	{
		$result = NULL;
		$query = $this
				->db
				->where('game_provider_code', $provider_code)
				->where('game_id', $game_id)
				->limit(1)
				->get('player_game_accounts');
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}
	public function get_player_list_array_by_provider($provider_array = NULL)
	{
		$lists = array();
		if(sizeof($provider_array)>0){
            foreach($provider_array as $provider_row){
                $lists[$provider_row] = array();
            }
            $query = $this
				->db
				->select('game_provider_code, player_id, game_id')
				->where_in('game_provider_code',$provider_array)
				->get('player_game_accounts');
    		if($query->num_rows() > 0)
    		{
    			foreach($query->result() as $row) {
    				$lists[$row->game_provider_code][strtolower($row->game_id)] = $row->player_id;
    			}
    		}
    		$query->free_result();
        }
		return $lists;
	}
	public function get_player_list_array_by_provider_single($provider = NULL)
	{
		$lists = array();
		$query = $this
			->db
			->select('game_provider_code, player_id, game_id')
			->where('game_provider_code',$provider)
			->get('player_game_accounts');
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row) {
				$lists[strtolower($row->game_id)] = $row->player_id;
			}
		}
		$query->free_result();
		return $lists;
	}
	public function update_player_token_by_provider_username($provider_code = NULL, $username = NULL, $token = NULL) {
		$DBdata = array(
			'token' => $token,
		);
		$this->db->where('game_provider_code', $provider_code);
		$this->db->where('game_id', $username);
		$this->db->limit(1);
		$this->db->update('player_game_accounts', $DBdata);
	}
	public function get_player_game_username_by_game_token($provider_code = NULL, $token = NULL) {
		$result = NULL;
		$query = $this
				->db
				->where('game_provider_code', $provider_code)
				->where('token', $token)
				->limit(1)
				->get('player_game_accounts');
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}
	public function get_player_detail_by_player_id_data($provider_code = NULL, $player_id = NULL) {
		$result = NULL;
		$query = $this
				->db
				->where('game_provider_code', $provider_code)
				->where('player_id', $player_id)
				->limit(1)
				->get('player_game_accounts');
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}
}
