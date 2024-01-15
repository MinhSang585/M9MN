<?php
class Withdrawal_model extends CI_Model {

	public function add_withdrawal($data = NULL, $player = NULL)
	{
		$DBdata = array(
			'withdrawal_type' => WITHDRAWAL_OFFLINE_BANKING,
			'transaction_code' => $data['transaction_code'],
			'bank_name' => $data['bank_name'],
			'bank_account_name' => $data['bank_account_name'],
			'bank_account_no' => $data['bank_account_no'],
			'bank_account_address' => $data['bank_account_address'],
			'currency_id' => $data['currency_id'],
			'currency_code' => $data['currency_code'],
			'currency_rate' => $data['currency_rate'],
			'amount_rate' => $data['amount_rate'],
			'amount' => $data['amount'],
			'handling_fee' => $data['handling_fee'],
			'status' => STATUS_PENDING,
			'player_id' => $data['player_id'],
			'bank_id' => $data['bank_id'],
			'withdrawal_ip' => $this->input->ip_address(),
			'created_by' => $data['username'],
			'created_date' => time(),
		);
		
		$this->db->insert('withdrawals', $DBdata);
		
		$DBdata['withdrawal_id'] = $this->db->insert_id();
		
		return $DBdata;
	}

	public function add_withdrawal_success($data = NULL, $player = NULL)
	{
		$DBdata = array(
			'withdrawal_type' => WITHDRAWAL_OFFLINE_BANKING,
			'transaction_code' => $data['transaction_code'],
			'bank_name' => $data['bank_name'],
			'bank_account_name' => $data['bank_account_name'],
			'bank_account_no' => $data['bank_account_no'],
			'bank_account_address' => $data['bank_account_address'],
			'currency_id' => $data['currency_id'],
			'currency_code' => $data['currency_code'],
			'currency_rate' => $data['currency_rate'],
			'amount_rate' => $data['amount_rate'],
			'amount' => $data['amount'],
			'handling_fee' => $data['handling_fee'],
			'status' => STATUS_SUCCESS,
			'player_id' => $data['player_id'],
			'bank_id' => $data['bank_id'],
			'withdrawal_ip' => $this->input->ip_address(),
			'created_by' => $data['username'],
			'created_date' => time(),
			'updated_by' => $data['username'],
			'updated_date' => time(), 
		);
		
		$this->db->insert('withdrawals', $DBdata);
		
		$DBdata['withdrawal_id'] = $this->db->insert_id();
		
		return $DBdata;
	}
}