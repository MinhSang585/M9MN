<?php
class General_model extends CI_Model {

	protected $table_login_report = 'login_report';
	protected $table_users = 'users';
	protected $table_sub_accounts = 'sub_accounts';
	protected $table_point_transfer_report = 'point_transfer_report';
	protected $table_cash_transfer_report = 'cash_transfer_report';
	protected $table_reward_transfer_report = 'reward_transfer_report';
	
	public function insert_login_report($data = NULL, $status = NULL)
	{
		$DBdata = array(
			'username' => $data['username'],
			'user_group_type' => $data['user_group'],
			'ip_address' => $this->input->ip_address(),
			'status' => $status,
			'report_date' => $data['last_login_date']
		);
		
		if($this->agent->is_mobile()) 
		{
			$DBdata['user_agent'] = $this->agent->mobile() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
			$DBdata['platform'] = PLATFORM_MOBILE_WEB;
		}
		else 
		{
			$DBdata['user_agent'] = $this->agent->browser() . ' ' . $this->agent->version();
			$DBdata['platform'] = PLATFORM_WEB;
		}
		
		$this->db->insert($this->table_login_report, $DBdata);
	}
	
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
	
	public function update_downline_permission($user_id = NULL, $permissions = NULL)
	{	
		$table = $this->db->dbprefix . $this->table_users;
		$table2 = $this->db->dbprefix . $this->table_sub_accounts;
		
		$upline_permissions = explode(',', $permissions);
		
		$query = $this->db->query("SELECT user_id, username, permissions FROM {$table} WHERE upline_ids LIKE '%,{$user_id},%'");
		if($query->num_rows() > 0)
        {
			foreach($query->result() as $row)
			{
				//Update user downline's permission
				$verified_permissions = array();
				$arr = explode(',', $row->permissions);
				
				for($i=0;$i<sizeof($arr);$i++)
				{
					if(in_array($arr[$i], $upline_permissions))
					{
						array_push($verified_permissions, $arr[$i]);
					}
				}
				
				$final_permissions = implode(',', $verified_permissions);
				
				$this->db->query("UPDATE {$table} SET permissions = ? WHERE user_id = ? LIMIT 1", array($final_permissions, $row->user_id));
				
				//Update sub account's permission
				$sub_query = $this->db->query("SELECT sub_account_id, permissions FROM {$table2} WHERE upline = ?", array($row->username));
				if($sub_query->num_rows() > 0)
				{
					foreach($sub_query->result() as $sub_row)
					{
						$sub_verified_permissions = array();
						$sub_arr = explode(',', $sub_row->permissions);
						
						for($i=0;$i<sizeof($sub_arr);$i++)
						{
							if(in_array($sub_arr[$i], $verified_permissions))
							{
								array_push($sub_verified_permissions, $sub_arr[$i]);
							}
						}
						
						$sub_final_permissions = implode(',', $sub_verified_permissions);
				
						$this->db->query("UPDATE {$table2} SET permissions = ? WHERE sub_account_id = ? LIMIT 1", array($sub_final_permissions, $sub_row->sub_account_id));
					}
				}
			}
		}
	}
	
	public function insert_point_transfer_report($from = NULL, $to = NULL)
	{
		$points = $this->input->post('points', TRUE);
		$remark = $this->input->post('remark', TRUE);
		
		$DBdata = array(
			'from_username' => $from['username'],
			'to_username' => $to['username'],
			'deposit_amount' => $points,
			'withdrawal_amount' => $points,
			'from_balance_before' => $from['points'],
			'from_balance_after' => ($from['points'] - $points),
			'to_balance_before' => $to['points'],
			'to_balance_after' => ($to['points'] + $points),
			'remark' => $remark,
			'report_date' => time(),
			'executed_by' => $this->session->userdata('username')
		);
		
		$this->db->insert($this->table_point_transfer_report, $DBdata);
	}
	
	public function insert_cash_transfer_report($arr = NULL, $points = NULL, $type = NULL,$remark = NULL)
	{
		if(!empty($remark)){
			$remark = json_encode($remark,true);
		}else{
			$remark = $this->input->post('remark', TRUE);
		}
		$DBdata = array(
			'transfer_type' => $type,
			'username' => $arr['username'],
			'remark' => $remark,
			'report_date' => time(),
			'executed_by' => $this->session->userdata('username')
		);
		
		if($type == TRANSFER_POINT_IN OR $type == TRANSFER_ADJUST_IN OR $type == TRANSFER_OFFLINE_DEPOSIT OR $type == TRANSFER_PG_DEPOSIT OR $type == TRANSFER_WITHDRAWAL_REFUND OR $type == TRANSFER_PROMOTION OR $type == TRANSFER_BONUS OR $type == TRANSFER_COMMISSION OR $type == TRANSFER_TRANSACTION_IN  OR $type == TRANSFER_CREDIT_CARD_DEPOSIT  OR $type == TRANSFER_HYPERMART_DEPOSIT OR $type == TRANSFER_WALLET_ADJUST)
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
		
		$this->db->insert($this->table_cash_transfer_report, $DBdata);
	}

	public function insert_reward_transfer_report($arr = NULL, $points = NULL, $type = NULL)
	{
		$remark = $this->input->post('remark', TRUE);
		
		$DBdata = array(
			'transfer_type' => $type,
			'username' => $arr['username'],
			'remark' => $remark,
			'report_date' => time(),
			'executed_by' => $this->session->userdata('username')
		);
		
		if($type == TRANSFER_REWARD_IN)
		{
			$DBdata['deposit_amount'] = $points;
			$DBdata['balance_before'] = $arr['rewards'];
			$DBdata['balance_after'] =  ($arr['rewards'] + $points);
		}
		else
		{
			$DBdata['withdrawal_amount'] = $points;
			$DBdata['balance_before'] = $arr['rewards'];
			$DBdata['balance_after'] =  ($arr['rewards'] - $points);
		}
		
		$this->db->insert($this->table_reward_transfer_report, $DBdata);
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
}