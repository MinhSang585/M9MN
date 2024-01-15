<?php
class Message_model extends CI_Model {
	protected $table_players = "players";
	protected $table_system_message = "system_message";
	protected $table_system_message_lang = "system_message_lang";
	protected $table_system_message_user = "system_message_user";
	protected $table_system_message_user_lang = "system_message_user_lang";

	public function add_message(){
		$DBdata = array(
			'system_message_name' => $this->input->post('system_message_name', TRUE),
			'system_message_type' => $this->input->post('system_message_type', TRUE),
			'system_message_genre' => $this->input->post('system_message_genre', TRUE),
			'system_message_remark' => $this->input->post('system_message_remark', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);
		$this->db->insert($this->table_system_message, $DBdata);
		$DBdata['system_message_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function get_message_data($id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('system_message_id', $id)
				->limit(1)
				->get($this->table_system_message);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_message_data_by_templete($id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('system_message_templete', $id)
				->where('active',STATUS_ACTIVE)
				->limit(1)
				->get($this->table_system_message);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function update_message($id = NULL,$arr = NULL){
		$DBdata = array(
			'system_message_name' => $this->input->post('system_message_name', TRUE),
			'system_message_remark' => $this->input->post('system_message_remark', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);

		if($arr['system_message_type'] == MESSAGE_SYSTEM){
			$DBdata['system_message_type'] = $arr['system_message_type'];
			$DBdata['system_message_genre'] = $arr['system_message_genre'];
		}else{
			$DBdata['system_message_type'] = $this->input->post('system_message_type', TRUE);
			$DBdata['system_message_genre'] = $this->input->post('system_message_genre', TRUE);
		}
		
		$this->db->where('system_message_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_system_message, $DBdata);
		$DBdata['system_message_id'] = $id;
		return $DBdata;
	}

	public function delete_message($id=NULL){
		$this->db->where('system_message_id', $id);
		$this->db->limit(1);
		$this->db->delete($this->table_system_message);
	}

	public function delete_message_with_player($id=NULL){
		$this->db->where('system_message_id', $id);
		$this->db->delete($this->table_system_message_user);
	}

	public function delete_player_message($id=NULL){
		$this->db->where('system_message_user_id', $id);
		$this->db->delete($this->table_system_message_user);
	}

	public function get_player_all_data_by_message_genre($array = null){
		$result = NULL;
		if($array['system_message_genre'] == MESSAGE_GENRE_ALL){
			$this->db->select('player_id,username');
			$this->db->where('active',STATUS_ACTIVE);
			$query =  $this->db->get($this->table_players);
			if($query->num_rows() > 0)
			{
				$result = $query->result_array();	
			}
			$query->free_result();
		}else if($array['system_message_genre'] == MESSAGE_GENRE_USER_ALL){
			$this->db->select('player_id,username');
			$this->db->where('active',STATUS_ACTIVE);
			$this->db->where('upline',$array['agent']);
			if(!empty($array['from_date'])){
				$this->db->where('created_date >= ',strtotime($array['from_date']));
			}
			if(!empty($array['to_date'])){
				$this->db->where('created_date <= ',strtotime($array['to_date']));	
			}
			$query =  $this->db->get($this->table_players);
			if($query->num_rows() > 0)
			{
				$result = $query->result_array();	
			}
			$query->free_result();
		}else{
			$username_array = array_filter(explode('#',strtolower($array['username'])));
			if(!empty($username_array)){
				if(sizeof($username_array) == 1){
					$this->db->select('player_id,username');
					$this->db->where('active',STATUS_ACTIVE);
					$this->db->where('username',$username_array[0]);
					$this->db->limit(1);
				}else{
					$this->db->select('player_id,username');
					$this->db->where('active',STATUS_ACTIVE);
					$this->db->where_in('username',$username_array);
				}

				$query =  $this->db->get($this->table_players);
				if($query->num_rows() > 0)
				{
					$result = $query->result_array();	
				}
				$query->free_result();
			}
			
		}
		return $result;
	}

	public function get_player_message_data($id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('system_message_user_id', $id)
				->limit(1)
				->get($this->table_system_message_user);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function update_player_message($id = NULL){
		$DBdata = array(
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		
		$this->db->where('system_message_user_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_system_message_user, $DBdata);
		
		$DBdata['system_message_user_id'] = $id;
		
		return $DBdata;
	}

	public function add_message_lang($id = NULL, $data = NULL, $language_id = NULL){
		$DBdata = array(
			'system_message_title' => $data['message_title'],
			'system_message_content' => $data['message_content'],
			'system_message_id' => $id,
			'language_id' => $language_id
		);
		$this->db->insert($this->table_system_message_lang, $DBdata);
	}

	public function get_message_lang_data($id = NULL){
		$result = NULL;
		
		$query = $this
				->db
				->select('system_message_title, system_message_content, language_id')
				->where('system_message_id', $id)
				->get($this->table_system_message_lang);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$result[$row->language_id]['system_message_title'] = $row->system_message_title;
				$result[$row->language_id]['system_message_content'] = $row->system_message_content;
			}	
		}
		$query->free_result();
		return $result;
	}

	public function delete_message_lang($id = NULL){
		$this->db->where('system_message_id', $id);
		$this->db->delete($this->table_system_message_lang);
	}

	public function get_message_bluk_data($system_message_id = NULL, $created_time = NULL){
		$result = array();
		$this->db->select('system_message_user_id,player_id');
		$this->db->where('system_message_id',$system_message_id);
		$this->db->where('created_date',$created_time);
		$query = $this->db->get($this->table_system_message_user);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$result[$row->player_id] = $row->system_message_user_id;
			}	
		}
		$query->free_result();
		return $result;
	}

	public function get_message_bluk_data_by_system_message_user_id($system_message_id = NULL, $created_time = NULL){
		$result = array();
		$this->db->select('system_message_user_id,player_id');
		$this->db->where('system_message_id',$system_message_id);
		$this->db->where('created_date',$created_time);
		$query = $this->db->get($this->table_system_message_user);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$result[$row->system_message_user_id] = $row->player_id;
			}	
		}
		$query->free_result();
		return $result;
	}

	public function get_player_message_lang_data($id = NULL){
		$result = NULL;
		
		$query = $this
				->db
				->select('system_message_user_id, system_message_content, language_id, system_message_title')
				->where('system_message_user_id', $id)
				->get($this->table_system_message_user_lang);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$result[$row->language_id]['system_message_title'] = $row->system_message_title;
				$result[$row->language_id]['system_message_content'] = $row->system_message_content;
			}	
		}
		$query->free_result();
		return $result;
	}
}