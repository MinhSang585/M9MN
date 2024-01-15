<?php
class Risk_model extends CI_Model {
	protected $table_player_risk_report = 'player_risk_report';

	public function get_player_risk_data($id = NULL)
	{	
		$result = NULL;
		$query = $this
				->db
				->where('player_risk_id', $id)
				->limit(1)
				->get($this->table_player_risk_report);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

}