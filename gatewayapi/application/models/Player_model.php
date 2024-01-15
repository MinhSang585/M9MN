<?php
class Player_model extends CI_Model {
	public function get_player_data_by_player_id($player_id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('player_id', $player_id)
				->limit(1)
				->get('players');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function point_transfer($arr = NULL, $points = NULL, $upline = NULL)
	{	
		$DBdata = array(
			'player_id' => $arr['player_id'],
			'username' => $arr['username'],
			'points' => $points,
			'updated_by' => $upline,
			'updated_date' => time()
		);
		
		$table = $this->db->dbprefix . 'players';
		$this->db->query("UPDATE {$table} SET points = (points + ?) WHERE player_id = ? LIMIT 1", array($DBdata['points'], $DBdata['player_id']));
		
		return $DBdata;
	}

	public function insert_cash_transfer_report($arr = NULL, $points = NULL, $upline = NULL, $type = NULL)
	{
		$DBdata = array(
			'transfer_type' => $type,
			'username' => $arr['username'],
			'remark' => '',
			'report_date' => time(),
			'executed_by' => $upline
		);
		
		if($type == TRANSFER_POINT_IN OR $type == TRANSFER_ADJUST_IN OR $type == TRANSFER_OFFLINE_DEPOSIT OR $type == TRANSFER_PG_DEPOSIT OR $type == TRANSFER_WITHDRAWAL_REFUND OR $type == TRANSFER_PROMOTION OR $type == TRANSFER_BONUS)
		{
			$DBdata['deposit_amount'] = $points;
			$DBdata['balance_before'] = $arr['points'];
			$DBdata['balance_after'] =  ($arr['points'] + $points);
		}
		else
		{
			$DBdata['withdrawal_amount'] = $points;
			$DBdata['balance_before'] = $arr['points'];
			$DBdata['balance_after'] =  ($arr['points'] - $points);
		}
		
		$this->db->insert('cash_transfer_report', $DBdata);
	}
}