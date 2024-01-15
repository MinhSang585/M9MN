<?php
class Currencies_model extends CI_Model {
	protected $table_currencies = 'currencies';


	public function add_currencies(){
		$DBdata = array(
			'currency_name' => $this->input->post('currency_name', TRUE),
			'currency_code' => $this->input->post('currency_code', TRUE),
			'currency_symbol' => $this->input->post('currency_symbol', TRUE),
			't_currency_rate' => bcdiv($this->input->post('t_currency_rate', TRUE),1,5),
			'd_currency_rate' => bcdiv($this->input->post('d_currency_rate', TRUE),1,5),
			'w_currency_rate' => bcdiv($this->input->post('w_currency_rate', TRUE),1,5),
			't_fee' => bcdiv($this->input->post('t_fee', TRUE),1,2),
			'd_fee' => bcdiv($this->input->post('d_fee', TRUE),1,2),
			'w_fee' => bcdiv($this->input->post('w_fee', TRUE),1,2),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);
		$this->db->insert($this->table_currencies, $DBdata);
		
		$DBdata['currency_id'] = $this->db->insert_id();
		
		return $DBdata;
	}

	public function update_currencies($id = NULL){
		$DBdata = array(
			'currency_name' => $this->input->post('currency_name', TRUE),
			'currency_code' => $this->input->post('currency_code', TRUE),
			'currency_symbol' => $this->input->post('currency_symbol', TRUE),
			't_currency_rate' => bcdiv($this->input->post('t_currency_rate', TRUE),1,5),
			'd_currency_rate' => bcdiv($this->input->post('d_currency_rate', TRUE),1,5),
			'w_currency_rate' => bcdiv($this->input->post('w_currency_rate', TRUE),1,5),
			't_fee' => bcdiv($this->input->post('t_fee', TRUE),1,2),
			'd_fee' => bcdiv($this->input->post('d_fee', TRUE),1,2),
			'w_fee' => bcdiv($this->input->post('w_fee', TRUE),1,2),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		$this->db->where('currency_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_currencies, $DBdata);
		$DBdata['currency_id'] = $id;
		return $DBdata;
	}

	public function get_currencies_data($id = NULL){
		$result = NULL;
		
		$query = $this
				->db
				->where('currency_id', $id)
				->limit(1)
				->get($this->table_currencies);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function delete_currencies($id=NULL){
		$this->db->where('currency_id', $id);
		$this->db->limit(1);
		$this->db->delete($this->table_currencies);
	}

	public function get_currencies_list()
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('currency_id, currency_code,is_default')
				->where('active', STATUS_ACTIVE)
				->get($this->table_currencies);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_currencies_active_data($id = NULL){
		$result = NULL;
		
		$query = $this
				->db
				->where('currency_id', $id)
				->where('active',STATUS_ACTIVE)
				->limit(1)
				->get($this->table_currencies);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
}