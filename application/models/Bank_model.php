<?php
class Bank_model extends CI_Model {

	public function get_bank_list($type = 0)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('bank_id, bank_name')
				->where('bank_type',$type)
				->where('active', STATUS_ACTIVE)
				->get('banks');
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_player_bank_account_quantity($playerData = NULL){
		$result = NULL;
		$this->db->where('username', $playerData['username']);
		$result = $this->db->count_all_results('player_bank');
		return $result;
	}
	
	public function get_bank_account_list()
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('bank_accounts.bank_account_id, bank_accounts.bank_account_name, bank_accounts.bank_account_no, banks.bank_name, bank_accounts.bank_id, ')
				->where('banks.active', STATUS_ACTIVE)
				->where('bank_accounts.active', STATUS_ACTIVE)
				->where('bank_accounts.daily_limit > bank_accounts.current_usage')
				->join('banks', 'bank_accounts.bank_id = banks.bank_id')
				->get('bank_accounts');
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function get_bank_data($bank_id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('active', STATUS_ACTIVE)
				->where('bank_id', $bank_id)
				->limit(1)
				->get('banks');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function get_bank_account_data($bank_account_id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('bank_accounts.bank_account_id, bank_accounts.bank_account_name, bank_accounts.bank_account_no, banks.bank_name')
				->where('banks.active', STATUS_ACTIVE)
				->where('bank_accounts.active', STATUS_ACTIVE)
				->where('bank_accounts.bank_account_id', $bank_account_id)
				->where('bank_accounts.daily_limit > bank_accounts.current_usage')
				->join('banks', 'bank_accounts.bank_id = banks.bank_id')
				->limit(1)
				->get('bank_accounts');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function update_bank_account_limit_usage($data = NULL)
	{	
		$table = $this->db->dbprefix . 'bank_accounts';
		$this->db->query("UPDATE {$table} SET current_usage = (current_usage + ?) WHERE bank_account_id = ? LIMIT 1", array($data['amount'], $data['bank_account_id']));
	}
	
	public function clear_bank_account_limit_usage()
	{	
		$DBdata = array(
			'current_usage' => 0
		);
		
		$this->db->update('bank_accounts', $DBdata);
	}

	public function get_bank_account_list_specific($bank_ids = NULL){
		$bank_ids_array = array_values(array_filter(explode(',', $bank_ids)));
		$result = array();
		$this->db->select('bank_accounts.bank_account_id, bank_accounts.bank_account_name, bank_accounts.bank_account_no, banks.bank_name, bank_accounts.bank_id, banks.web_image_on, banks.web_image_off, banks.mobile_image_on, banks.mobile_image_off');
		$this->db->where('banks.active', STATUS_ACTIVE);
		$this->db->where('bank_accounts.active', STATUS_ACTIVE);
		$this->db->where('bank_accounts.daily_limit > bank_accounts.current_usage');
		if(sizeof($bank_ids_array)>0){
			$this->db->group_start();
			foreach($bank_ids_array as $bank_ids_row){
				$this->db->or_group_start();
					$this->db->like('bank_accounts.group_ids',",".$bank_ids_row.",");
				$this->db->group_end();
			}
			$this->db->group_end();
		}		
		$this->db->join('banks', 'bank_accounts.bank_id = banks.bank_id');
		$query = $this->db->get('bank_accounts');
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		$query->free_result();
		return $result;
	}

	public function get_player_bank_account_list($data = NULL){
		$result = NULL;
		$query = $this
				->db
				->select('player_bank.player_bank_id, banks.bank_id, banks.bank_name, player_bank.bank_account_name, player_bank.bank_account_no, player_bank.bank_account_address')
				->where('banks.active', STATUS_ACTIVE)
				->where('player_bank.active', STATUS_ACTIVE)
				->join('banks', 'player_bank.bank_id = banks.bank_id')
				->where('player_bank.player_id',$data['player_id'])
				->get('player_bank');
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
		}
		$query->free_result();
		
		return $result;
	}

	public function add_player_bank_account($data = NULL){
		$DBdata = array(
			'player_id' => $data['player_id'],
			'username' => $data['username'],
			'bank_id' => $data['bank_id'],
			'bank_account_name' => $data['bank_account_name'],
			'bank_account_no' => $data['bank_account_no'],
			'active' => STATUS_ACTIVE,
			'created_by' => $data['username'],
			'created_date' => time(),
		);
		$this->db->insert('player_bank', $DBdata);
		
		$DBdata['player_bank_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function get_player_bank_list($player_bank_id = NULL, $type = NULL, $currency_id = NULL){
		$result = NULL;	
		$this->db->select('player_bank.player_bank_id, player_bank.player_id, player_bank.bank_id, player_bank.bank_account_name, player_bank.bank_account_no, player_bank.bank_account_address, banks.bank_name');
		$this->db->where('banks.active', STATUS_ACTIVE);
		$this->db->where('player_bank.active', STATUS_ACTIVE);
		$this->db->where('player_id', $this->session->userdata('player_id'));
		$this->db->join('banks', 'player_bank.bank_id = banks.bank_id');
		if($type !== NULL){
			$this->db->where('banks.bank_type', $type);
		}
		if($currency_id !== NULL){
			$this->db->where('banks.currency_id', $currency_id);
		}
		if($player_bank_id != NULL){
			$this->db->where('player_bank.player_bank_id', $player_bank_id);
			$this->db->limit(1);
		}
		$query = $this->db->get('player_bank');
		if($query->num_rows() > 0)
		{
			if($player_bank_id != NULL){
				$result = $query->row_array(); 
			}else{
				$result = $query->result_array();
			} 
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_bank_account_list_default_deposit(){
		$result = array();
		$this->db->select('bank_account_id, bank_account_name, bank_account_no, bank_id, bank_reference');
		$this->db->where('bank_accounts.active', STATUS_ACTIVE);
		$this->db->where('bank_accounts.daily_limit > bank_accounts.current_usage');
		$query = $this->db->get('bank_accounts');

		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		
		$query->free_result();
		return $result;
	}

	public function get_bank_list_info()
	{
		$result = NULL;
		
		$query = $this
				->db
				->select('bank_id, bank_name, web_image_on')
				->where('active', STATUS_ACTIVE)
				->get('banks');
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			foreach($result as $bank) {
				$list[$bank['bank_id']] = $bank;
			}
		}

		$query->free_result();
		
		return $list;
	}
}
