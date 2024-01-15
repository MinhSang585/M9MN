<?php
class avatar_model extends CI_Model {
	protected $table_avatar = 'avatar';
	protected $table_player = 'players';

	public function add_avatar(){
		$DBdata = array(
			'avatar_name' => $this->input->post('avatar_name', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);

		if(isset($_FILES['avatar_image']['size']) && $_FILES['avatar_image']['size'] > 0)
		{
			$DBdata['avatar_image'] = $_FILES['avatar_image']['name'];
		}
		
		$this->db->insert($this->table_avatar, $DBdata);
		
		$DBdata['avatar_id'] = $this->db->insert_id();
		
		return $DBdata;
	}

	public function update_avatar($id = NULL){
		$DBdata = array(
			'avatar_name' => $this->input->post('avatar_name', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		(($this->input->post('type', TRUE) == AVATAR_ALL) ? $DBdata['username']="" : "");
		if(isset($_FILES['avatar_image']['size']) && $_FILES['avatar_image']['size'] > 0)
		{
			$DBdata['avatar_image'] = $_FILES['avatar_image']['name'];
		}
		$this->db->where('avatar_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_avatar, $DBdata);
		$DBdata['avatar_id'] = $id;
		return $DBdata;
	}

	public function get_avatar_data($id = NULL){
		$result = NULL;
		
		$query = $this
				->db
				->where('avatar_id', $id)
				->limit(1)
				->get($this->table_avatar);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}	

	public function get_avatar_list()
	{	
		$result = NULL;
		$query = $this
				->db
				->select('avatar_id,avatar_name')
				->where('active', STATUS_ACTIVE)
				->get($this->table_avatar);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_random_avatar_data($id = NULL){
		$result = NULL;
		
		$query = $this
				->db
				->where('avatar_id != ', $id)
				->where('active', STATUS_ACTIVE)
				->limit(1)
				->get($this->table_avatar);
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}

	public function delete_avatar($id=NULL){
		$this->db->where('avatar_id', $id);
		$this->db->limit(1);
		$this->db->delete($this->table_avatar);
	}

	public function reset_player_avatar($old_id=NULL,$new_id=NULL){
		$DBdata = array(
			'avatar' => $new_id,
		);
		$this->db->where('avatar', $old_id);
		$this->db->update($this->table_player, $DBdata);
	}
}