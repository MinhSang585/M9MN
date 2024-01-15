<?php
class Deposit_model extends CI_Model {
	
	public function add_offline_deposit($data = NULL)
	{
		$DBdata = array(
			'deposit_type' => DEPOSIT_OFFLINE_BANKING,
			'bank_name' => $data['bank_name'],
			'bank_account_name' => $data['bank_account_name'],
			'bank_account_no' => $data['bank_account_no'],
			'player_bank_name' => $data['player_bank_name'],
			'player_bank_account_name' => $data['player_bank_account_name'],
			'player_bank_account_no' => $data['player_bank_account_no'],
			'player_bank_account_address' => $data['player_bank_account_address'],
			'amount_apply' => $data['amount_apply'],
			'amount' => $data['amount'],
			'bank_in_date' => $data['bank_in_date'],
			'status' => STATUS_PENDING,
			'player_id' => $data['player_id'],
			'bank_account_id' => $data['bank_account_id'],
			'deposit_ip' => $this->input->ip_address(),
			'promotion_id' => $data['promotion_id'],
			'promotion_name' => $data['promotion_name'],
			'amount_rate' => $data['amount_rate'],
			'currency_id' => $data['currency_id'],
			'currency_code' => $data['currency_code'],
			'currency_rate' => $data['currency_rate'],
			'bank_slip' => $data['bank_slip'],
			'created_by' => $data['username'],
			'remark' => $data['remark'],
			'created_date' => time()
		);
		
		$this->db->insert('deposits', $DBdata);
		$DBdata['deposit_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function add_online_deposit($data = NULL)
	{
		$DBdata = array(
			'deposit_type' => $data['deposit_type'],
			'transaction_code' => $data['transaction_code'],
			'amount_apply' => $data['amount_apply'],
			'amount' => $data['amount'],
			'rate' => $data['rate'],
			'rate_amount' => $data['rate_amount'],
			'bank_in_date' => $data['bank_in_date'],
			'status' => STATUS_ON_PENDING,
			'player_id' => $data['player_id'],
			'payment_gateway_id' => $data['payment_gateway_id'],
			'payment_gateway_bank' => $data['payment_gateway_bank'],
			'deposit_ip' => $this->input->ip_address(),
			'promotion_id' => $data['promotion_id'],
			'promotion_name' => $data['promotion_name'],
			'amount_rate' => $data['amount_rate'],
			'currency_id' => $data['currency_id'],
			'currency_code' => $data['currency_code'],
			'currency_rate' => $data['currency_rate'],
			'created_by' => $data['username'],
			'created_date' => time()
		);
		
		$this->db->insert('deposits', $DBdata);
		$DBdata['deposit_id'] = $this->db->insert_id();
		
		return $DBdata;
	}

	public function get_payment_gateway_pending_deposit_data($deposit_id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('status', STATUS_ON_PENDING)
				->where('deposit_id', $deposit_id)
				->limit(1)
				->get('deposits');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function get_pending_deposit_data($deposit_id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where_in('status', STATUS_PENDING)
				->where('deposit_id', $deposit_id)
				->limit(1)
				->get('deposits');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function update_deposit_status($data = NULL)
	{
		$DBdata = array(
			'order_no' => $data['order_no'],
			'bank_name' => $data['bank_name'],
			'bank_account_no' => $data['bank_account_no'],
			'status' => $data['status'],
			'updated_by' => $data['updated_by'],
			'updated_date' => time()
		);
		
		$this->db->where('transaction_code', $data['transaction_code']);
		$this->db->where('status', STATUS_PENDING);
		$this->db->limit(1);
		$this->db->update('deposits', $DBdata);
	}
	
	public function get_deposit_data_by_transaction_code($transaction_code = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('transaction_code', $transaction_code)
				->limit(1)
				->get('deposits');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function get_deposit_data_by_transaction_alias_code($transaction_code = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('transaction_code_alias', $transaction_code)
				->where('deposit_type != ', DEPOSIT_OFFLINE_BANKING)
				->limit(1)
				->get('deposits');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}

	public function update_payment_gateway_deposit_status($data = NULL){
		$DBdata = array(
			'updated_by' => $data['updated_by'],
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

		$this->db->where('transaction_code', $data['transaction_code']);
		$this->db->where('status != ', STATUS_APPROVE);
		$this->db->limit(1);
		$this->db->update('deposits', $DBdata);
	}
}