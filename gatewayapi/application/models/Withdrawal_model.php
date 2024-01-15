<?php
class Withdrawal_model extends CI_Model {
    protected $table_banks = 'banks';
	protected $table_bank_accounts = 'bank_accounts';
	protected $table_bank_player = 'player_bank';
	protected $table_player_bank_image = 'player_bank_image';
    
	public function get_withdrawal_data_by_transaction_code($transaction_code = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('transaction_code', $transaction_code)
				->limit(1)
				->get('withdrawals');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function get_withdrawal_data_by_transaction_alias_code($transaction_code = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('transaction_code_alias', $transaction_code)
				->limit(1)
				->get('withdrawals');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}

	public function update_payment_gateway_withdrawal_status($data = NULL){
		$DBdata = array(
			#'updated_by' => $data['updated_by'],
			'updated_date' => time()
		);
		if(isset($data['status'])){
			$DBdata['status'] = $data['status'];
		}
		if(isset($data['order_no'])){
			$DBdata['order_no'] = $data['order_no'];
		}
		if(isset($data['bank_name'])){
			$DBdata['bank_name'] = $data['bank_name'];
		}
		if(isset($data['bank_account_no'])){
			$DBdata['bank_account_no'] = $data['bank_account_no'];
		}
		if(isset($data['payment_info'])){
			$DBdata['payment_info'] = $data['payment_info'];
		}
		if(isset($data['payment_status'])){
			$DBdata['payment_status'] = $data['payment_status'];
		}

		if(isset($data['handling_fee'])){
			$DBdata['handling_fee'] = $data['handling_fee'];
		}
		
		if(isset($data['payout_date'])){
			$DBdata['payout_date'] = $data['payout_date'];
		}

		$this->db->where('transaction_code', $data['transaction_code']);
		$this->db->where('status != ', STATUS_APPROVE);
		$this->db->limit(1);
		$this->db->update('withdrawals', $DBdata);
	}
	
	public function update_bank_withdrawal_count($arr = NULL){
		$DBdata = array(
			'player_id' => $arr['player_id'],
			'bank_id' => $arr['bank_id'],
			'bank_account_name' => $arr['bank_account_name'],
			'bank_account_no' => $arr['bank_account_no'],
		);
		$table = $this->db->dbprefix . $this->table_bank_player;
		$this->db->query("UPDATE {$table} SET withdrawal_count = (withdrawal_count + ?) WHERE player_id = ? AND bank_id = ? AND bank_account_name = ? AND bank_account_no = ? LIMIT 1", array(1, $DBdata['player_id'], $DBdata['bank_id'], $DBdata['bank_account_name'], $DBdata['bank_account_no']));
		return $DBdata;
	}
	
	
}