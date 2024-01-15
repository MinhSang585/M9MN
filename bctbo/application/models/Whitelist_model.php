<?php
class Whitelist_model extends CI_Model {

	protected $table_whitelists = 'whitelists';

	public function add_whitelist($arr =  NULL){
		$DBdata = array(
			'player_id' => $arr['player_id'],
			'type' => $this->input->post('type', TRUE),
			'value' => $this->input->post('value', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time(),
		);
		$this->db->insert($this->table_whitelists, $DBdata);
		$DBdata['whitelist_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function get_whitelist_data($whitelist_id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('whitelist_id', $whitelist_id)
				->limit(1)
				->get($this->table_whitelists);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		return $result;
	}

	public function update_whitelist($whitelist_id = NULL){
		$DBdata = array(
			'type' => $this->input->post('type', TRUE),
			'value' => $this->input->post('value', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		$this->db->where('whitelist_id', $whitelist_id);
		$this->db->limit(1);
		$this->db->update($this->table_whitelists, $DBdata);
		
		$DBdata['whitelist_id'] = $whitelist_id;
		
		return $DBdata;
	}

	public function delete_whitelist($whitelist_id = NULL){
		$this->db->where('whitelist_id', $whitelist_id);
		$this->db->delete($this->table_whitelists);
	}
}