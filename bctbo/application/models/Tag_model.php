<?php
class Tag_model extends CI_Model {

	protected $table = 'tag';
	protected $table_tag_player = 'tag_player';

	public function add_tag_setting(){
		$DBdata = array(
			'tag_code' => $this->input->post('tag_code', TRUE),
			'tag_times' => $this->input->post('tag_times', TRUE),
			'tag_min' => $this->input->post('tag_min', TRUE),
			'tag_max' => $this->input->post('tag_max', TRUE),
			'tag_font_color' => $this->input->post('tag_font_color', TRUE),
			'tag_background_color' => $this->input->post('tag_background_color', TRUE),
			'is_bold' => (($this->input->post('is_bold', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'active' => STATUS_ACTIVE,
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);

		$this->db->insert($this->table, $DBdata);
		$DBdata['tag_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function get_tag_setting_data($id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('tag_id', $id)
				->limit(1)
				->get($this->table);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function update_tag_setting($id = NULL){
		$DBdata = array(
			'tag_code' => $this->input->post('tag_code', TRUE),
			'tag_times' => $this->input->post('tag_times', TRUE),
			'tag_min' => $this->input->post('tag_min', TRUE),
			'tag_max' => $this->input->post('tag_max', TRUE),
			'tag_font_color' => $this->input->post('tag_font_color', TRUE),
			'tag_background_color' => $this->input->post('tag_background_color', TRUE),
			'is_bold' => (($this->input->post('is_bold', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);

		$this->db->where('tag_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table, $DBdata);
		$DBdata['tag_id'] = $id;
		return $DBdata;
	}

	public function delete_tag_setting($id = NULL){
		$this->db->where('tag_id', $id);
		$this->db->delete($this->table);
	}

	public function get_tag_list(){
		$result = array();
		
		$query = $this
				->db
				->select('tag_id, tag_code, active, tag_font_color, tag_background_color,is_bold')
				->get($this->table);
		
		if($query->num_rows() > 0)
		{
			$result_test = $query->result_array();
			foreach($result_test as $result_row){
				$result[$result_row['tag_id']] = array(
					'tag_id' => $result_row['tag_id'],
					'tag_code' => $result_row['tag_code'],
					'tag_font_color' => $result_row['tag_font_color'],
					'tag_background_color' => $result_row['tag_background_color'],
					'is_bold' => $result_row['is_bold'],
					'active' => $result_row['active'],
				);
			}
		}
		
		$query->free_result();
		
		return $result;
	}

	public function add_tag_player_setting(){
		$DBdata = array(
			'tag_player_code' => $this->input->post('tag_player_code', TRUE),
			'tag_player_font_color' => $this->input->post('tag_player_font_color', TRUE),
			'tag_player_background_color' => $this->input->post('tag_player_background_color', TRUE),
			'is_bold' => (($this->input->post('is_bold', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'active' => STATUS_ACTIVE,
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);

		$this->db->insert($this->table_tag_player, $DBdata);
		$DBdata['tag_player_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function get_tag_player_setting_data($id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('tag_player_id', $id)
				->limit(1)
				->get($this->table_tag_player);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function update_tag_player_setting($id = NULL){
		$DBdata = array(
			'tag_player_code' => $this->input->post('tag_player_code', TRUE),
			'tag_player_font_color' => $this->input->post('tag_player_font_color', TRUE),
			'tag_player_background_color' => $this->input->post('tag_player_background_color', TRUE),
			'is_bold' => (($this->input->post('is_bold', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);

		$this->db->where('tag_player_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_tag_player, $DBdata);
		$DBdata['tag_player_id'] = $id;
		return $DBdata;
	}

	public function delete_tag_player_setting($id = NULL){
		$this->db->where('tag_player_id', $id);
		$this->db->delete($this->table_tag_player);
	}

	public function get_tag_player_list(){
		$result = array();
		
		$query = $this
				->db
				->select('tag_player_id, tag_player_code, active, tag_player_font_color, tag_player_background_color, is_bold')
				->get($this->table_tag_player);
		
		if($query->num_rows() > 0)
		{
			$result_test = $query->result_array();
			foreach($result_test as $result_row){
				$result[$result_row['tag_player_id']] = array(
					'tag_player_id' => $result_row['tag_player_id'],
					'tag_player_code' => $result_row['tag_player_code'],
					'tag_player_font_color' => $result_row['tag_player_font_color'],
					'tag_player_background_color' => $result_row['tag_player_background_color'],
					'is_bold' => $result_row['is_bold'],
					'active' => $result_row['active'],
				);
			}
		}
		
		$query->free_result();
		
		return $result;
	}


	public function get_all_tag_data(){
		$result = array();
		
		$query = $this
				->db
				->select('tag_id, tag_code')
				->get($this->table);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_all_tag_player_data(){
		$result = array();
		
		$query = $this
				->db
				->select('tag_player_id, tag_player_code')
				->get($this->table_tag_player);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		
		$query->free_result();
		
		return $result;
	}
}