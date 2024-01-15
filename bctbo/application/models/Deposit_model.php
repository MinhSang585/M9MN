<?php
class Deposit_model extends CI_Model {

	//Declare database tables
	protected $table_deposits = 'deposits';
	
	public function today_total_deposit()
	{
		$start_date = strtotime(date('Y-m-d 00:00:00'));
		$end_date = strtotime(date('Y-m-d 23:59:59'));
		
		$result = NULL;
		
		$query = $this
				->db
				->select('SUM(deposit_amount) AS total')
				->where('report_date >=', $start_date)
				->where('report_date <=', $end_date)
				->where_in('transfer_type', array(TRANSFER_POINT_IN, TRANSFER_ADJUST_IN, TRANSFER_OFFLINE_DEPOSIT, TRANSFER_PG_DEPOSIT))
				->get('cash_transfer_report');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function player_total_deposit_by_deposit_type($player_id = NULL,$type = NULL)
	{
		$result = NULL;
		$query = $this
				->db
				->select('COALESCE(SUM(amount_apply),0) AS total')
				->where('player_id', $player_id)
				->where('status', STATUS_APPROVE)
				->where_in('deposit_type', $type)
				->get('deposits');
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		$query->free_result();
		return $result;
	}

	public function player_total_deposit_by_type($username = NULL,$type = NULL)
	{
		$result = NULL;
		
		$query = $this
				->db
				->select('COALESCE(SUM(deposit_amount),0) AS total')
				->where('username', $username)
				->where_in('transfer_type', $type)
				->get('cash_transfer_report');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function get_deposit_data($id = NULL)
	{	
		$result = NULL;
		$query = $this
				->db
				->where('deposit_id', $id)
				->limit(1)
				->get($this->table_deposits);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function update_deposit($arr = NULL)
	{	
		$DBdata = array(
			'status' => (($this->input->post('status', TRUE) == STATUS_APPROVE) ? STATUS_APPROVE : STATUS_CANCEL),
			'remark' => $this->input->post('remark', TRUE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		
		$this->db->where('deposit_id', $arr['deposit_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->where('status', STATUS_PENDING);
		$this->db->limit(1);
		$this->db->update($this->table_deposits, $DBdata);
		
		$DBdata['deposit_id'] = $arr['deposit_id'];
		$DBdata['player_id'] = $arr['player_id'];
		$DBdata['username'] = $arr['username'];
		$DBdata['amount'] = $arr['amount'];
		
		return $DBdata;
	}

	public function add_deposit_offline($arr = NULL)
	{	
		$remark = $this->input->post('remark', TRUE);
		$DBdata = array(
			'deposit_type' => DEPOSIT_OFFLINE_BANKING,
			'amount_apply' => $arr['amount_apply'],
			'amount' => $arr['amount'],			
			'remark' => $remark,
			'status' => STATUS_PENDING,
			'transaction_code' => $arr['transaction_code'],
			'player_id' => $arr['player_id'],
			'promotion_id' => (isset($arr['promotion_id'])?$arr['promotion_id']:""),
			'promotion_name' => (isset($arr['promotion_name'])?$arr['promotion_name']:""),
			'deposit_ip' => $this->input->ip_address(),
			'currency_id' => $arr['currency_id'],
			'currency_code' => $arr['currency_code'],
			'currency_rate' => $arr['currency_rate'],
			'amount_rate' => $arr['amount_rate'],
			'created_date' => time(),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		$this->db->insert($this->table_deposits, $DBdata);
		$DBdata['deposit_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function today_total_only_agent_deposit()
	{
	    $result['total'] = 0;
	    $result_row = array();
	    
		$dbprefix = $this->db->dbprefix;
		$start_date = strtotime(date('Y-m-d 00:00:00'));
		$end_date = strtotime(date('Y-m-d 23:59:59'));
		$where = "";
		$where .= ' AND a.created_date >= ' . $start_date;
		$where .= ' AND a.created_date <= ' . $end_date;
		$where .= ' AND a.status = '. STATUS_APPROVE;
		$result = NULL;
		$query_string = "(SELECT SUM(a.amount_apply) AS total FROM {$dbprefix}deposits a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
		$query = $this->db->query($query_string);
		if($query->num_rows() > 0)
		{
			$result_row = $query->row_array();  
			if(!empty($result_row['total'])){
			    $result['total'] += $result_row['total'];   
			}
		}
		$query->free_result();
		
    	
    	
		$dbprefix = $this->db->dbprefix;
		$where = "";
		$where .= ' AND a.report_date >= ' . strtotime(date('Y-m-d 00:00:00'));
		$where .= ' AND a.report_date <= ' . strtotime(date('Y-m-d 23:59:59'));
		$where .= ' AND a.transfer_type IN (' . TRANSFER_POINT_IN . ')';
		//$where .= ' AND a.transfer_type IN (' . TRANSFER_POINT_IN . ', ' . TRANSFER_ADJUST_IN . ', ' . TRANSFER_OFFLINE_DEPOSIT . ', ' . TRANSFER_PG_DEPOSIT . ')';
		//$where .= ' AND a.transfer_type IN (' . TRANSFER_ADJUST_IN . ', ' . TRANSFER_OFFLINE_DEPOSIT . ', ' . TRANSFER_PG_DEPOSIT . ', ' . TRANSFER_CREDIT_CARD_DEPOSIT . ', ' . TRANSFER_HYPERMART_DEPOSIT . ')';
		$query_string = "SELECT SUM(a.deposit_amount) AS total FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
		$query = $this->db->query($query_string);
		
		if($query->num_rows() > 0)
		{
			$result_row = $query->row_array();  
			if(!empty($result_row['total'])){
			    $result['total'] += $result_row['total'];   
			}
		}
		
		
		$query->free_result();
		
		return $result;
	}
	
	public function week_total_only_agent_deposit()
	{

		$result['total'] = 0;
		$result_row = array();

		$dbprefix = $this->db->dbprefix;

		$start_date = strtotime('monday this week');
		$end_date = strtotime('sunday this week') + (24 * 60 * 60 - 1);

		$where = "";

		$where .= ' AND a.created_date >= ' . $start_date;

		$where .= ' AND a.created_date <= ' . $end_date;

		$where .= ' AND a.status = '. STATUS_APPROVE;

		$result = NULL;

		$query_string = "(SELECT SUM(a.amount_apply) AS total FROM {$dbprefix}deposits a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";

		$query = $this->db->query($query_string);

		if($query->num_rows() > 0)

		{

			$result_row = $query->row_array();  

			if(!empty($result_row['total'])){

				$result['total'] += $result_row['total'];   

			}

		}

		$query->free_result();

		$dbprefix = $this->db->dbprefix;

		$where = "";

		$where .= ' AND a.report_date >= ' . $start_date;

		$where .= ' AND a.report_date <= ' . $end_date;

		$where .= ' AND a.transfer_type IN (' . TRANSFER_POINT_IN . ')';

		//$where .= ' AND a.transfer_type IN (' . TRANSFER_POINT_IN . ', ' . TRANSFER_ADJUST_IN . ', ' . TRANSFER_OFFLINE_DEPOSIT . ', ' . TRANSFER_PG_DEPOSIT . ')';

		//$where .= ' AND a.transfer_type IN (' . TRANSFER_ADJUST_IN . ', ' . TRANSFER_OFFLINE_DEPOSIT . ', ' . TRANSFER_PG_DEPOSIT . ', ' . TRANSFER_CREDIT_CARD_DEPOSIT . ', ' . TRANSFER_HYPERMART_DEPOSIT . ')';

		$query_string = "SELECT SUM(a.deposit_amount) AS total FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";

		$query = $this->db->query($query_string);

		if($query->num_rows() > 0)

		{

			$result_row = $query->row_array();  

			if(!empty($result_row['total'])){

				$result['total'] += $result_row['total'];   

			}

		}

		$query->free_result();

		return $result;

	}

	public function month_total_only_agent_deposit()
	{

		$result['total'] = 0;
		$result_row = array();

		

		$dbprefix = $this->db->dbprefix;

		$start_date = mktime(0, 0, 0, date('m'), 1, date('Y'));
		$end_date = mktime(23, 59, 59, date('m'), date('t'), date('Y'));

		// $start_date = strtotime(date('first day of this month'));
		// $end_date = strtotime(date('last day of this month')) + (24 * 60 * 60 - 1);

		$where = "";

		$where .= ' AND a.created_date >= ' . $start_date;

		$where .= ' AND a.created_date <= ' . $end_date;

		$where .= ' AND a.status = '. STATUS_APPROVE;

		$result = NULL;

		$query_string = "(SELECT SUM(a.amount_apply) AS total FROM {$dbprefix}deposits a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";

		$query = $this->db->query($query_string);

		if($query->num_rows() > 0)

		{

			$result_row = $query->row_array();  

			if(!empty($result_row['total'])){

				$result['total'] += $result_row['total'];   

			}

		}

		$query->free_result();

		$dbprefix = $this->db->dbprefix;

		$where = "";

		$where .= ' AND a.report_date >= ' . $start_date;

		$where .= ' AND a.report_date <= ' . $end_date;

		$where .= ' AND a.transfer_type IN (' . TRANSFER_POINT_IN . ')';

		//$where .= ' AND a.transfer_type IN (' . TRANSFER_POINT_IN . ', ' . TRANSFER_ADJUST_IN . ', ' . TRANSFER_OFFLINE_DEPOSIT . ', ' . TRANSFER_PG_DEPOSIT . ')';

		//$where .= ' AND a.transfer_type IN (' . TRANSFER_ADJUST_IN . ', ' . TRANSFER_OFFLINE_DEPOSIT . ', ' . TRANSFER_PG_DEPOSIT . ', ' . TRANSFER_CREDIT_CARD_DEPOSIT . ', ' . TRANSFER_HYPERMART_DEPOSIT . ')';

		$query_string = "SELECT SUM(a.deposit_amount) AS total FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";

		$query = $this->db->query($query_string);

		if($query->num_rows() > 0)

		{

			$result_row = $query->row_array();  

			if(!empty($result_row['total'])){

				$result['total'] += $result_row['total'];   

			}

		}

		$query->free_result();

		return $result;

	}
	public function update_deposit_promotion_status($arr = NULL,$promotion_status = NULL)
	{	
		$DBdata = array(
			'promotion_status' => $promotion_status,
		);
		$this->db->where('deposit_id', $arr['deposit_id']);
		$this->db->where('player_id', $arr['player_id']);
		$this->db->limit(1);
		$this->db->update($this->table_deposits, $DBdata);
		return $DBdata;
	}

	public function getTotalDepositPlayer($playerData){
		$result = null;
		$query = $this->db->select_sum('amount_apply')
				->where('player_id', $playerData['player_id'])
				->where('created_date >=', strtotime("-3 months", time()))
				->where('status', STATUS_COMPLETE)
				->get($this->table_deposits);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		
		$query->free_result();
		
		return $result;
	}
}