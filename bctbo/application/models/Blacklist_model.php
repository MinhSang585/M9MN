<?php
class Blacklist_model extends CI_Model {

	protected $table_blacklists = 'blacklists';
	protected $table_blacklists_import = 'blacklists_import';
	protected $table_blacklists_report = "blacklists_report";

	public function add_blacklist(){
		$DBdata = array(
			'blacklist_type' => $this->input->post('blacklist_type', TRUE),
			'blacklist_value' => $this->input->post('blacklist_value', TRUE),
			'remark' => $this->input->post('remark', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time(),
		);
		$this->db->insert($this->table_blacklists, $DBdata);
		$DBdata['blacklist_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function get_blacklist_data($blacklist_id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('blacklist_id', $blacklist_id)
				->limit(1)
				->get($this->table_blacklists);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		return $result;
	}

	public function update_blacklist($blacklist_id = NULL){
		$DBdata = array(
			'blacklist_type' => $this->input->post('blacklist_type', TRUE),
			'blacklist_value' => $this->input->post('blacklist_value', TRUE),
			'remark' => $this->input->post('remark', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		$this->db->where('blacklist_id', $blacklist_id);
		$this->db->limit(1);
		$this->db->update($this->table_blacklists, $DBdata);
		
		$DBdata['blacklist_id'] = $blacklist_id;
		
		return $DBdata;
	}

	public function delete_blacklist($blacklist_id = NULL){
		$this->db->where('blacklist_id', $blacklist_id);
		$this->db->delete($this->table_blacklists);
	}

	public function add_blacklist_import(){
		$DBdata = array(
			'remark' => trim($this->input->post('remark', TRUE)),
			'status' => STATUS_PENDING,
			'created_by' => $this->session->userdata('username'),
			'created_date' => time(),
		);
		if(isset($_FILES['excel_filename']['size']) && $_FILES['excel_filename']['size'] > 0)
		{
			$DBdata['filename'] = $_FILES['excel_filename']['name'];
		}
		$this->db->insert($this->table_blacklists_import, $DBdata);
		$DBdata['blacklists_import_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function get_blacklist_import_data($blacklists_import_id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('blacklists_import_id', $blacklists_import_id)
				->limit(1)
				->get($this->table_blacklists_import);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		return $result;
	}

	public function delete_blacklist_import($blacklists_import_id = NULL){
		$this->db->where('blacklists_import_id', $blacklists_import_id);
		$this->db->delete($this->table_blacklists_import);
	}

	public function update_blacklist_import_data($blacklists_import_id = NULL){
		$DBdata = array(
			'status' => STATUS_ACTIVE,
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		$this->db->where('blacklists_import_id', $blacklists_import_id);
		$this->db->limit(1);
		$this->db->update($this->table_blacklists_import, $DBdata);
		
		$DBdata['blacklists_import_id'] = $blacklists_import_id;
		
		return $DBdata;
	}

	public function get_blacklist_report_data($blacklists_report_id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('blacklists_report_id', $blacklists_report_id)
				->limit(1)
				->get($this->table_blacklists_report);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		return $result;
	}

	public function update_blacklist_report($blacklists_report_id = NULL){
		$DBdata = array(
			'status' => STATUS_ACTIVE,
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		$this->db->where('blacklists_report_id', $blacklists_report_id);
		$this->db->limit(1);
		$this->db->update($this->table_blacklists_report, $DBdata);
		
		$DBdata['blacklists_report_id'] = $blacklists_report_id;
		
		return $DBdata;
	}
}