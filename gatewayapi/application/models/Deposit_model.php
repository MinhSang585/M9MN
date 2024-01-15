<?php

class Deposit_model extends CI_Model
{
    public function get_payment_gateway_pending_deposit_data($deposit_id = NULL)
	{
		$result = NULL;
		$query = $this->db->where('status', STATUS_ON_PENDING)
				->where('deposit_id', $deposit_id)
				->limit(1)
				->get('deposits');

		if($query->num_rows() > 0) {
			$result = $query->row_array(); 
		}
		$query->free_result();
		return $result;
	}

	public function get_deposit_data_by_transaction_code($transaction_code = NULL)
	{	
		$result = NULL;

		$query = $this->db->where('transaction_code', $transaction_code)
				->limit(1)
				->get('deposits');

		if ($query->num_rows() > 0) {
			$result = $query->row_array(); 
		}
		$query->free_result();
		return $result;
	}

	public function update_payment_gateway_deposit_status($data = NULL)
	{
		$DBdata = array(
			'updated_by' => $data['updated_by'],
			'updated_date' => time()
		);
		if (isset($data['status'])) {
			$DBdata['status'] = $data['status'];
		}
		if (isset($data['order_no'])) {
			$DBdata['order_no'] = $data['order_no'];
		}
		if (isset($data['bank_name'])) {
			$DBdata['bank_name'] = $data['bank_name'];
		}
		if (isset($data['bank_account_no'])) {
			$DBdata['bank_account_no'] = $data['bank_account_no'];
		}
		if (isset($data['payment_info'])) {
			$DBdata['payment_info'] = $data['payment_info'];
		}
		if (isset($data['payment_status'])) {
			$DBdata['payment_status'] = $data['payment_status'];
		}
		if (isset($data['handling_fee'])) {
			$DBdata['handling_fee'] = $data['handling_fee'];
		}
		$this->db->where('transaction_code', $data['transaction_code'])
			->where('status != ', STATUS_APPROVE)
			->limit(1)
			->update('deposits', $DBdata);
	}
}
