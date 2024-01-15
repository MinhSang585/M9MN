<?php
class Account_model extends CI_Model {
	public function check_username_exits($username = NULL)
	{
		$result = FALSE;
		$query = $this
				->db
				->select('sub_account_id')
				->where('username', $username)
				->limit(1)
				->get('sub_accounts');
		if($query->num_rows() > 0)
		{
			$result = TRUE;
		}
		$query->free_result();
		return $result;
	}

	public function check_mobile_exits($mobile = NULL)
	{
		$result = FALSE;
		$query = $this
				->db
				->select('player_id')
				->where('mobile', $mobile)
				->limit(1)
				->get('players');
		if($query->num_rows() > 0)
		{
			$result = TRUE;
		}
		$query->free_result();
		return $result;
	}

	public function check_bankno_exits($bankno = NULL)
	{
		$result = FALSE;
		$query = $this
				->db
				->select('player_bank_id')
				->where('bank_account_no', $bankno)
				->limit(1)
				->get('player_bank');
		if($query->num_rows() > 0)
		{
			$result = TRUE;
		}
		$query->free_result();
		return $result;
	}

}