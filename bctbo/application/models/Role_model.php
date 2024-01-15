<?php
class Role_model extends CI_Model {

	protected $table_user_role = 'user_role';
	const LEVEL_LIST = [1,2,3,4,5,6,7,8,9];
	public function add_role($permissions = NULL){
		$DBdata = array(
			'role_name' => $this->input->post('role_name', TRUE),
			'permissions' => $permissions,
			'remark' => $this->input->post('remark', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),			'level' => $this->input->post('level', TRUE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time(),
		);
		$this->db->insert($this->table_user_role, $DBdata);
		$DBdata['user_role_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function update_role($user_role_id = NULL, $permissions = NULL){
		$DBdata = array(
			'role_name' => $this->input->post('role_name', TRUE),
			'permissions' => $permissions,
			'remark' => $this->input->post('remark', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),			'level' => $this->input->post('level', TRUE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);

		$this->db->where('user_role_id', $user_role_id);
		$this->db->limit(1);
		$this->db->update($this->table_user_role, $DBdata);
		
		$DBdata['user_role_id'] = $user_role_id;
		return $DBdata;
	}

	public function get_role_data($user_role_id = null){
		$result = NULL;
		$query = $this
				->db
				->where('user_role_id', $user_role_id)
				->limit(1)
				->get($this->table_user_role);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		return $result;
	}


	public function delete_role($user_role_id = NULL){
		$this->db->where('user_role_id', $user_role_id);
		$this->db->delete($this->table_user_role);
	}

	public function get_role_list(){
		$result = NULL;
		$query = $this
				->db
				->select('user_role_id,role_name')
				->where('active', STATUS_ACTIVE)
				->get($this->table_user_role);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		
		$query->free_result();
		return $result;
	}

	public function get_role_list_by_id(){
		$result = NULL;
		$query = $this
				->db
				->select('user_role_id,role_name')
				->where('active', STATUS_ACTIVE)
				->get($this->table_user_role);
		
		if($query->num_rows() > 0)
		{
			$result_test = $query->result_array();
			foreach($result_test as $result_row){
				$result[$result_row['user_role_id']] = array(
					'role_name' => $result_row['role_name'],
				);
			}
			unset($result_test);
		}
		
		$query->free_result();
		return $result;
	}	/**	* get level list from default const LEVEL_LIST	*/	
	public function get_level_list(){
		return self::LEVEL_LIST;
	}

	/**
	*  Get role list by creator
	* if the user account login, his role is level 2, then he only able select level 3 role character when create sub account/user
	* @param integer $username
	* @return array $roleList role list
	*/
	public function get_role_list_by_creator($username){
		$roleList = NULL;
		$query = $this
			->db
			->select('user_role_id,role_name')
			->where('active', STATUS_ACTIVE)
			->where('created_by', $username)
			->get($this->table_user_role);
	
		if($query->num_rows() > 0)
		{
			$roleList = $query->result_array();  
		}
		
		$query->free_result();
        
        return $roleList;
	}

	public function getRoleNew(){
		$roleList = NULL;
		$query = $this
			->db
			->select('user_role_id,role_name')
			->where('active', STATUS_ACTIVE)
			->get($this->table_user_role);
	
		if($query->num_rows() > 0)
		{
			$roleList = $query->result_array();  
		}
		
		$query->free_result();
        
        return $roleList;
	}
}