<?php
class General_model extends CI_Model {

	function all_posts($arr = NULL)
    {
		$result = NULL;
		
		if( ! empty($arr['search_columns']))
		{
			for($i=0;$i<sizeof($arr['search_columns']);$i++)
			{
				if($arr['search_types'][$i] == 'like')
				{
					$this->db->like($arr['search_columns'][$i], $arr['search_values'][$i]);
				}
				else
				{
					$this->db->where($arr['search_columns'][$i], $arr['search_values'][$i]);
				}	
			}
		}

		if( ! empty($arr['custom_search']))
		{
			$this->db->where($arr['custom_search']);
		}	
		
		if( ! empty($arr['join_table']))
		{
			$this->db->join($arr['join_table'][0], $arr['join_table'][1]);
		}
		
		$this->db->select($arr['select']);
		$this->db->limit($arr['limit'], $arr['start']);
		$this->db->order_by($arr['order'], $arr['dir']);
		$query = $this->db->get($arr['table']);
       
        if($query->num_rows() > 0)
        {
            $result = $query->result();  
        }
		
		$query->free_result();
		
		return $result;
    }

    function all_posts_count($arr = NULL)
    {		
		$result = NULL;
		
		if( ! empty($arr['search_columns']))
		{
			for($i=0;$i<sizeof($arr['search_columns']);$i++)
			{
				if($arr['search_types'][$i] == 'like')
				{
					$this->db->like($arr['search_columns'][$i], $arr['search_values'][$i]);
				}
				else
				{
					$this->db->where($arr['search_columns'][$i], $arr['search_values'][$i]);
				}	
			}
		}
		
		if( ! empty($arr['custom_search']))
		{
			$this->db->where($arr['custom_search']);
		}
		
		if( ! empty($arr['join_table']))
		{
			$this->db->join($arr['join_table'][0], $arr['join_table'][1]);
		}
		
		$this->db->select($arr['select']);
        $this->db->from($arr['table']);
    
        $result = $this->db->count_all_results();
		
		return $result;
    }
	
	public function insert_point_transfer_report($from = NULL, $to = NULL, $points = NULL)
	{
		$DBdata = array(
			'from_username' => $from['username'],
			'to_username' => $to['username'],
			'deposit_amount' => $points,
			'withdrawal_amount' => $points,
			'from_balance_before' => $from['points'],
			'from_balance_after' => ($from['points'] - $points),
			'to_balance_before' => $to['points'],
			'to_balance_after' => ($to['points'] + $points),
			'remark' => '',
			'report_date' => time(),
			'executed_by' => $from['username']
		);
		
		$this->db->insert('point_transfer_report', $DBdata);
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
	
	public function insert_game_transfer_report($from = NULL, $to = NULL, $from_balance = NULL, $to_balance = NULL, $amount = NULL, $player_id = NULL,$order_id = NULL,$order_id_alias = NULL)
	{
		$DBdata = array(
			'order_id' => $order_id,
			'order_id_alias' => $order_id_alias,
			'from_wallet' => $from,
			'to_wallet' => $to,
			'deposit_amount' => $amount,
			'withdrawal_amount' => $amount,
			'from_balance_before' => $from_balance,
			'from_balance_after' => ($from_balance - $amount),
			'to_balance_before' => $to_balance,
			'to_balance_after' => ($to_balance + $amount),
			'player_id' => $player_id,
			'report_date' => time()
		);
		$this->db->insert('game_transfer_report', $DBdata);
		$DBdata['game_transfer_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function insert_game_transfer_pending_report($from = NULL, $to = NULL, $type = NULL, $from_balance = NULL, $to_balance = NULL, $amount = NULL, $player_id = NULL,$order_id = NULL,$order_id_alias = NULL)
	{
		$DBdata = array(
			'order_id' => $order_id,
			'order_id_alias' => $order_id_alias,
			'transfer_type' => $type,
			'from_wallet' => $from,
			'to_wallet' => $to,
			'deposit_amount' => $amount,
			'withdrawal_amount' => $amount,
			'from_balance_before' => $from_balance,
			'from_balance_after' => ($from_balance - $amount),
			'to_balance_before' => $to_balance,
			'to_balance_after' => ($to_balance + $amount),
			'player_id' => $player_id,
			'status' => STATUS_PENDING,
			'created_date' => time()
		);
		$this->db->insert('game_transfer_pending', $DBdata);
		$DBdata['game_transfer_pending_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function update_game_transfer_report_order_id_alias($arr = NULL,$order_id_alias = NULL){
		$DBdata = array(
			'order_id_alias' => $order_id_alias,
		);
		$this->db->where('game_transfer_id', $arr['game_transfer_id']);
		$this->db->limit(1);
		$this->db->update('game_transfer_report', $DBdata);
	}
	
	public function insert_api_game_api_unnormal_log($game_code = NULL,$player_id = NULL,$type = NULL,$input = NULL,$output = NULL,$response = NULL)
	{
		$DBdata = array(
			'game_code' => $game_code,
			'log_date' => time(),
			'player_id' => $player_id,
			'transfer_type' => $type,
			'input' => (($input) ? json_encode($input) : ''),
			'output' => (($output) ? json_encode($output) : ''),
			'output_pure' => $response,
		);
		$this->db->insert('game_api_unnormal_logs', $DBdata);
	}
	
	public function get_currency_list()
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('active', 1)
				->get('currencies');
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_currency_data($id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('currency_id', $id)
				->limit(1)
				->get('currencies');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
}