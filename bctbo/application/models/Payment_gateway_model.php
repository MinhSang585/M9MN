<?php
class Payment_gateway_model extends CI_Model {
	protected $table = 'payment_gateway';
	protected $table_payment_gateway_maintenance = "payment_gateway_maintenance";
	protected $table_payment_gateway_limited = "payment_gateway_limited";
	protected $table_payment_gateway_player_amount = "payment_gateway_player_amount";

	public function get_payment_gateway_data($id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('payment_gateway_id', $id)
				->where('active', STATUS_ACTIVE)
				->limit(1)
				->get($this->table);
		
	   
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}

	public function update_payment_gateway($id = NULL)
	{	
		$DBdata = array(
			'payment_gateway_sequence' => $this->input->post('payment_gateway_sequence', TRUE),
			'payment_gateway_admin_verification' => (($this->input->post('payment_gateway_admin_verification', TRUE) == STATUS_YES) ? STATUS_YES : STATUS_NO),
			'payment_gateway_rate_type' => $this->input->post('payment_gateway_rate_type', TRUE),
			'payment_gateway_rate' => $this->input->post('payment_gateway_rate', TRUE),
			'payment_gateway_min_amount' => $this->input->post('payment_gateway_min_amount', TRUE),
			'payment_gateway_max_amount' => $this->input->post('payment_gateway_max_amount', TRUE),
			'is_maintenance' => (($this->input->post('is_maintenance', TRUE) == STATUS_YES) ? STATUS_YES : STATUS_NO),
			'fixed_maintenance' => (($this->input->post('fixed_maintenance', TRUE) == STATUS_YES) ? STATUS_YES : STATUS_NO),
			'fixed_day' => $this->input->post('fixed_day', TRUE),
			'fixed_from_time' => $this->input->post('fixed_from_time', TRUE),
			'fixed_to_time' => $this->input->post('fixed_to_time', TRUE),
			'urgent_maintenance' => (($this->input->post('urgent_maintenance', TRUE) == STATUS_YES) ? STATUS_YES : STATUS_NO),
			'urgent_date' => strtotime($this->input->post('urgent_date', TRUE)),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);

		if(isset($_FILES['web_image_on']['size']) && $_FILES['web_image_on']['size'] > 0)
		{
			$DBdata['web_image_on'] = $_FILES['web_image_on']['name'];
		}
		
		if(isset($_FILES['web_image_off']['size']) && $_FILES['web_image_off']['size'] > 0)
		{
			$DBdata['web_image_off'] = $_FILES['web_image_off']['name'];
		}

		if(isset($_FILES['mobile_image_on']['size']) && $_FILES['mobile_image_on']['size'] > 0)
		{
			$DBdata['mobile_image_on'] = $_FILES['mobile_image_on']['name'];
		}
		
		if(isset($_FILES['mobile_image_off']['size']) && $_FILES['mobile_image_off']['size'] > 0)
		{
			$DBdata['mobile_image_off'] = $_FILES['mobile_image_off']['name'];
		}
		
		$this->db->where('payment_gateway_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table, $DBdata);
		$DBdata['payment_gateway_id'] = $id;
		return $DBdata;
	}

	public function get_payment_gateway_list()
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('payment_gateway_id, payment_gateway_code, payment_gateway_type_code, payment_gateway_name, api_data')
				->where('active', STATUS_ACTIVE)
				->order_by('payment_gateway_code', 'ASC')
				->get($this->table);
	   
		if($query->num_rows() > 0)
		{
			$result = $query->result_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_payment_gateway_list_by_type_and_currency($type = NULL,$currency_id = NULL,$bank_code = NULL){
	    $bank_code_search = ",".$bank_code.",";
		$result = NULL;
		
		$query = $this
				 ->db
				 ->select('payment_gateway_id, payment_gateway_code, payment_gateway_name')
				 ->where('payment_gateway_type_code', $type)
				 ->where('payment_gateway_currency_id', $currency_id)
				 ->like('bank_data',$bank_code_search)
				 ->where('active', STATUS_ACTIVE)
				 ->order_by('payment_gateway_code', 'ASC')
				 ->get($this->table);
	   
		if($query->num_rows() > 0)
		{
			$result = $query->result_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}

	public function add_payment_gateway_maintenance($data = NULL)
	{	
		$DBdata = array(
			'payment_gateway_id' => $data['payment_gateway_id'],
			'payment_gateway_code' => $data['payment_gateway_code'],
			'payment_gateway_name' => $data['payment_gateway_name'],
			'payment_gateway_type_code' => $data['payment_gateway_type_code'],
			'payment_gateway_sequence' => $this->input->post('payment_gateway_sequence', TRUE),
			'is_maintenance' => (($this->input->post('is_maintenance', TRUE) == STATUS_YES) ? STATUS_YES : STATUS_NO),
			'is_front_end_display' => (($this->input->post('is_front_end_display', TRUE) == STATUS_YES) ? STATUS_YES : STATUS_NO),
			'fixed_maintenance' => (($this->input->post('fixed_maintenance', TRUE) == STATUS_YES) ? STATUS_YES : STATUS_NO),
			'fixed_day' => $this->input->post('fixed_day', TRUE),
			'fixed_from_time' => $this->input->post('fixed_from_time', TRUE),
			'fixed_to_time' => $this->input->post('fixed_to_time', TRUE),
			'urgent_maintenance' => (($this->input->post('urgent_maintenance', TRUE) == STATUS_YES) ? STATUS_YES : STATUS_NO),
			'urgent_date' => strtotime($this->input->post('urgent_date', TRUE)),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);
		
		$this->db->insert($this->table_payment_gateway_maintenance, $DBdata);
		
		$DBdata['game_maintenance_id'] = $this->db->insert_id();
		
		return $DBdata;
	}

	public function get_payment_gateway_maintenance_data($id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('payment_gateway_maintenance_id', $id)
				->limit(1)
				->get($this->table_payment_gateway_maintenance);
		
	   
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}

	public function update_payment_gateway_maintenance($id = NULL,$data = NULL)
	{	
		$DBdata = array(
			'payment_gateway_id' => $data['payment_gateway_id'],
			'payment_gateway_code' => $data['payment_gateway_code'],
			'payment_gateway_name' => $data['payment_gateway_name'],
			'payment_gateway_type_code' => $data['payment_gateway_type_code'],
			'payment_gateway_sequence' => $this->input->post('payment_gateway_sequence', TRUE),
			'is_maintenance' => (($this->input->post('is_maintenance', TRUE) == STATUS_YES) ? STATUS_YES : STATUS_NO),
			'is_front_end_display' => (($this->input->post('is_front_end_display', TRUE) == STATUS_YES) ? STATUS_YES : STATUS_NO),
			'fixed_maintenance' => (($this->input->post('fixed_maintenance', TRUE) == STATUS_YES) ? STATUS_YES : STATUS_NO),
			'fixed_day' => $this->input->post('fixed_day', TRUE),
			'fixed_from_time' => $this->input->post('fixed_from_time', TRUE),
			'fixed_to_time' => $this->input->post('fixed_to_time', TRUE),
			'urgent_maintenance' => (($this->input->post('urgent_maintenance', TRUE) == STATUS_YES) ? STATUS_YES : STATUS_NO),
			'urgent_date' => strtotime($this->input->post('urgent_date', TRUE)),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		
		$this->db->where('payment_gateway_maintenance_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_payment_gateway_maintenance, $DBdata);
		
		$DBdata['payment_gateway_maintenance_id'] = $id;
		
		return $DBdata;
	}

	public function delete_payment_gateway_maintenance($id = NULL)
	{	
		$this->db->where('payment_gateway_maintenance_id', $id);
		$this->db->limit(1);
		$this->db->delete($this->table_payment_gateway_maintenance);
	}

	public function add_payment_gateway_limited(){
		$DBdata = array(
			'payment_gateway_code' => $this->input->post('payment_gateway_code', TRUE),
			'payment_gateway_type_code' => $this->input->post('payment_gateway_type_code', TRUE),
			'payment_gateway_sequence' => $this->input->post('payment_gateway_sequence', TRUE),
			'payment_gateway_daily_limit' => $this->input->post('payment_gateway_daily_limit', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_YES) ? STATUS_YES : STATUS_NO),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);
		
		$this->db->insert($this->table_payment_gateway_limited, $DBdata);
		$DBdata['payment_gateway_limited_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function get_payment_gateway_limited_data($id = NULL){
		$result = NULL;
		
		$query = $this
				->db
				->where('payment_gateway_limited_id', $id)
				->limit(1)
				->get($this->table_payment_gateway_limited);
		
	   
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}

	public function update_payment_gateway_limited($id = NULL,$arr = NULL){
		$DBdata = array(
			'payment_gateway_type_code' => $this->input->post('payment_gateway_type_code', TRUE),
			'payment_gateway_sequence' => $this->input->post('payment_gateway_sequence', TRUE),
			'payment_gateway_daily_limit' => $this->input->post('payment_gateway_daily_limit', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_YES) ? STATUS_YES : STATUS_NO),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);

		if($this->input->post('is_reset_daily_limit', TRUE) == STATUS_YES){
			$DBdata['payment_gateway_current_usage'] = 0;
		}
		
		$this->db->where('payment_gateway_limited_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_payment_gateway_limited, $DBdata);
		if($this->input->post('is_reset_daily_limit', TRUE) != STATUS_YES){
			$DBdata['payment_gateway_current_usage'] = $arr['payment_gateway_current_usage'];
		}
		$DBdata['payment_gateway_limited_id'] = $id;
		$DBdata['payment_gateway_code'] = $arr['payment_gateway_code'];
		$DBdata['is_reset_daily_limit'] = $this->input->post('is_reset_daily_limit', TRUE);
		$DBdata['is_reset_daily_limit_same_payment_gateway'] = $this->input->post('is_reset_daily_limit_same_payment_gateway', TRUE);
		return $DBdata;
	}

	public function reset_payment_gateway_daily_limit($payment_gateway_code = NULL){
		$DBdata = array(
			'payment_gateway_current_usage' => 0,
		);

		$this->db->where('payment_gateway_code', $payment_gateway_code);
		$this->db->update($this->table_payment_gateway_limited, $DBdata);
	}

	public function add_payment_gateway_player_amount($playerData = NULL){
		$DBdata = array(
			'payment_gateway_code' => $this->input->post('payment_gateway_code', TRUE),
			'payment_gateway_type_code' => $this->input->post('payment_gateway_type_code', TRUE),
			'player_id' => (isset($playerData) ? $playerData['player_id'] : 0),
			'username' => (isset($playerData) ? $playerData['username'] : ''),
			'min_amount' => $this->input->post('min_amount', TRUE),
			'max_amount' => $this->input->post('max_amount', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_YES) ? STATUS_YES : STATUS_NO),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);
		
		$this->db->insert($this->table_payment_gateway_player_amount, $DBdata);
		$DBdata['payment_gateway_player_amount_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function get_payment_gateway_player_amount_data($id = NULL){
		$result = NULL;
		
		$query = $this
				->db
				->where('payment_gateway_player_amount_id', $id)
				->limit(1)
				->get($this->table_payment_gateway_player_amount);
		
	   
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}

	public function update_payment_gateway_player_amount($id = NULL,$arr = NULL,$playerData = NULL){
		$DBdata = array(
			'payment_gateway_type_code' => $this->input->post('payment_gateway_type_code', TRUE),
			'player_id' => (isset($playerData) ? $playerData['player_id'] : 0),
			'username' => (isset($playerData) ? $playerData['username'] : ''),
			'min_amount' => $this->input->post('min_amount', TRUE),
			'max_amount' => $this->input->post('max_amount', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_YES) ? STATUS_YES : STATUS_NO),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		
		$this->db->where('payment_gateway_player_amount_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_payment_gateway_player_amount, $DBdata);
		$DBdata['payment_gateway_player_amount_id'] = $id;
		$DBdata['payment_gateway_code'] = $arr['payment_gateway_code'];
		return $DBdata;
	}

	public function delete_payment_gateway_limited($id = NULL)
	{	
		$this->db->where('payment_gateway_limited_id', $id);
		$this->db->limit(1);
		$this->db->delete($this->table_payment_gateway_limited);
	}

	public function delete_payment_gateway_player_amount($id = NULL)
	{	
		$this->db->where('payment_gateway_player_amount_id', $id);
		$this->db->limit(1);
		$this->db->delete($this->table_payment_gateway_player_amount);
	}
}