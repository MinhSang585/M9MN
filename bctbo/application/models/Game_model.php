<?php
class Game_model extends CI_Model {

	protected $table = 'games';
	protected $table_game_maintenance = 'game_maintenance';
	protected $table_player_game_accounts = 'player_game_accounts';
	protected $table_sub_games = 'sub_games';
	
	public function get_game_data($id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('game_id, game_code, game_name, game_sequence, is_maintenance, fixed_maintenance, fixed_day, fixed_from_time, fixed_to_time, urgent_maintenance, urgent_date')
				->where('game_id', $id)
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
	
	public function update_game($id = NULL)
	{	
		$DBdata = array(
			'game_sequence' => $this->input->post('game_sequence', TRUE),
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
		
		$this->db->where('game_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table, $DBdata);
		
		$DBdata['game_id'] = $id;
		
		return $DBdata;
	}

	
	public function get_game_list()
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('game_id, game_code, game_name, game_type_code, game_type_front_code, game_type_report_code, api_data')
				->where('active', STATUS_ACTIVE)
				->order_by('game_code', 'ASC')
				->get($this->table);
		
	   
		if($query->num_rows() > 0)
		{
			$result = $query->result_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_game_list_name()
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('game_id, game_name')
				->where('active', STATUS_ACTIVE)
				->order_by('game_code', 'ASC')
				->get($this->table);
		
	   
		if($query->num_rows() > 0)
		{
			$result = $query->result_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_game_maintenance_data($id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('game_maintenance_id, game_code,game_name, game_sequence, is_maintenance, is_front_end_display,fixed_maintenance, fixed_day, fixed_from_time, fixed_to_time, urgent_maintenance, urgent_date')
				->where('game_maintenance_id', $id)
				->limit(1)
				->get($this->table_game_maintenance);
		
	   
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}

	public function add_game_maintenance($data = NULL)
	{	
		$DBdata = array(
			'game_code' => $data['game_code'],
			'game_name' => $data['game_name'],
			'game_sequence' => $this->input->post('game_sequence', TRUE),
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
		
		$this->db->insert($this->table_game_maintenance, $DBdata);
		
		$DBdata['game_maintenance_id'] = $this->db->insert_id();
		
		return $DBdata;
	}

	public function update_game_maintenance($id = NULL,$data = NULL)
	{	
		$DBdata = array(
			'game_code' => $data['game_code'],
			'game_name' => $data['game_name'],
			'game_sequence' => $this->input->post('game_sequence', TRUE),
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
		
		$this->db->where('game_maintenance_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_game_maintenance, $DBdata);
		
		$DBdata['game_maintenance_id'] = $id;
		
		return $DBdata;
	}

	public function get_all_player_game_account($id = NULL){
		$result = NULL;
		$query = $this
				->db
				->select('player_game_account_id, game_provider_code,username, game_id, created_date, updated_date')
				->where('player_id', $id)
				->get($this->table_player_game_accounts);


		if($query->num_rows() > 0)
		{
			$result = $query->result_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}

	public function delete_game_maintenance($id = NULL){
		$this->db->where('game_maintenance_id', $id);
		$this->db->delete($this->table_game_maintenance);
	}
	
	public function subgame($provider = NULL){
		$this->db->select('game_code,game_name_en');
		$this->db->where('game_provider_code',$provider);
		$this->db->order_by('sub_game_id', 'ASC');
		$query = $this->db->get('sub_games');
		$result = ($query->num_rows() > 0) ? $query->result_array() : array();	   
		$query->free_result();
		
		if(sizeof($result) > 0) {
			foreach($result as $a) {
				$res[$a['game_code']] = $a['game_name_en'];
			}
		}
		else {
			$res = array();
		}
		unset($result);
		return $res;
		
	}

	public function add_sub_game($data = NULL){
		$DBdata = array(
			'game_provider_code' => $data['game_code'],
			'game_type_code' => $this->input->post('game_type_code', TRUE),
			'game_sequence' => $this->input->post('game_sequence', TRUE),
			'game_code' => $this->input->post('game_code', TRUE),
			'game_name_en' => $this->input->post('game_name_en', TRUE),
			'game_name_chs' => $this->input->post('game_name_chs', TRUE),
			'game_name_cht' => $this->input->post('game_name_cht', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_mobile' => (($this->input->post('is_mobile', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_open_game' => (($this->input->post('is_open_game', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_progressive' => (($this->input->post('is_progressive', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_hot' => (($this->input->post('is_hot', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_new' => (($this->input->post('is_new', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
		);


		if(isset($_FILES['game_picture_en']['size']) && $_FILES['game_picture_en']['size'] > 0)
		{
			$DBdata['game_picture'] = $_FILES['game_picture_en']['name'];
			$DBdata['game_picture_en'] = $_FILES['game_picture_en']['name'];
		}

		if(isset($_FILES['game_picture_chs']['size']) && $_FILES['game_picture_chs']['size'] > 0)
		{
			$DBdata['game_picture_chs'] = $_FILES['game_picture_chs']['name'];
		}

		if(isset($_FILES['game_picture_cht']['size']) && $_FILES['game_picture_cht']['size'] > 0)
		{
			$DBdata['game_picture_cht'] = $_FILES['game_picture_cht']['name'];
		}
		$this->db->insert($this->table_sub_games, $DBdata);
		
		$DBdata['sub_game_id'] = $this->db->insert_id();
		
		return $DBdata;
	}
	
	public function get_sub_game_data($id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('sub_game_id', $id)
				->limit(1)
				->get($this->table_sub_games);
		
	   
		if($query->num_rows() > 0)
		{
			$result = $query->row_array(); 
		}
		
		$query->free_result();
		
		return $result;
	}

	public function update_sub_game($data = NULL,$id = NULL){
		$DBdata = array(
			'game_provider_code' => $data['game_code'],
			'game_type_code' => $this->input->post('game_type_code', TRUE),
			'game_sequence' => $this->input->post('game_sequence', TRUE),
			'game_code' => $this->input->post('game_code', TRUE),
			'game_name_en' => $this->input->post('game_name_en', TRUE),
			'game_name_chs' => $this->input->post('game_name_chs', TRUE),
			'game_name_cht' => $this->input->post('game_name_cht', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_mobile' => (($this->input->post('is_mobile', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_open_game' => (($this->input->post('is_open_game', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_progressive' => (($this->input->post('is_progressive', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_hot' => (($this->input->post('is_hot', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_new' => (($this->input->post('is_new', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
		);


		if(isset($_FILES['game_picture_en']['size']) && $_FILES['game_picture_en']['size'] > 0)
		{
			$DBdata['game_picture'] = $_FILES['game_picture_en']['name'];
			$DBdata['game_picture_en'] = $_FILES['game_picture_en']['name'];
		}

		if(isset($_FILES['game_picture_chs']['size']) && $_FILES['game_picture_chs']['size'] > 0)
		{
			$DBdata['game_picture_chs'] = $_FILES['game_picture_chs']['name'];
		}

		if(isset($_FILES['game_picture_cht']['size']) && $_FILES['game_picture_cht']['size'] > 0)
		{
			$DBdata['game_picture_cht'] = $_FILES['game_picture_cht']['name'];
		}

		$this->db->where('sub_game_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_sub_games, $DBdata);
		$DBdata['sub_game_id'] = $id;
		return $DBdata;	
	}

	public function delete_sub_game($id = NULL){
		$this->db->where('sub_game_id', $id);
		$this->db->delete($this->table_sub_games);
	}
}