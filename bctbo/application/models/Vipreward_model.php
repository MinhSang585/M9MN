<?php

class VIPreward_model extends CI_Model {



	//Declare database tables

	protected $table_player_request_award = 'player_request_award';


	public function getRequestAwardData($id = NULL)
	{	

		$result = NULL;

		$query = $this

				->db

				->where('id', $id)

				->limit(1)

				->get($this->table_player_request_award);	

		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}

		$query->free_result();
		return $result;
	}
	public function getPlayerInfo()
	{	

		$playerList = NULL;

		$sql = "SELECT player_id, username FROM bctp_players";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0)
		{
			$playerList = $query->result_array();  
		}
		$query->free_result();
		$arrPlayerInfo = array();
		foreach($playerList as $playerItem){
			$arrPlayerInfo[$playerItem['player_id']] = $playerItem['username'];
		}
		return $arrPlayerInfo;
	}
}
