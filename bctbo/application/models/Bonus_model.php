<?php
class Bonus_model extends CI_Model {

	protected $table_bonus = 'bonus';
	protected $table_player_bonus = 'player_bonus';
	protected $table_bonus_lang = 'bonus_lang';

	public function add_bonus(){
		$DBdata = array(
			'bonus_name' => $this->input->post('bonus_name', TRUE),
			'bonus_seq' => $this->input->post('bonus_seq', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time(),
		);
		$this->db->insert($this->table_bonus, $DBdata);
		$DBdata['bonus_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function get_bonus_data($bonus_id = null){
		$result = NULL;
		$query = $this
				->db
				->where('bonus_id', $bonus_id)
				->limit(1)
				->get($this->table_bonus);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		return $result;
	}

	public function get_bonus_data_active($bonus_id = null){
		$result = NULL;
		$query = $this
				->db
				->where('bonus_id', $bonus_id)
				->where('active', STATUS_ACTIVE)
				->limit(1)
				->get($this->table_bonus);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		return $result;
	}

	public function get_bonus_data_list(){
		$result = NULL;
		$query = $this
				->db
				->where('active', STATUS_ACTIVE)
				->get($this->table_bonus);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		
		$query->free_result();
		return $result;
	}

	public function add_bonus_content($bonus_id=NULL, $language_id = NULL){
		$DBdata = array(
			'bonus_title' => $this->input->post('bonus_title_'.$language_id, TRUE),
			'bonus_content' => $this->input->post('bonus_content_'.$language_id, TRUE),
			'bonus_id' => $bonus_id,
			'language_id' => $language_id,
		);
		if(isset($_FILES['web_banner_'.$language_id]['size']) && $_FILES['web_banner_'.$language_id]['size'] > 0)
		{
			$DBdata['bonus_banner_web'] = $_FILES['web_banner_'.$language_id]['name'];
		}
		
		if(isset($_FILES['mobile_banner_'.$language_id]['size']) && $_FILES['mobile_banner_'.$language_id]['size'] > 0)
		{
			$DBdata['bonus_banner_mobile'] = $_FILES['mobile_banner_'.$language_id]['name'];
		}
		$this->db->insert($this->table_bonus_lang, $DBdata);
		return $DBdata;
	}

	public function update_bonus_content($bonus_id=NULL, $language_id = NULL){
		$DBdata = array(
			'bonus_title' => $this->input->post('bonus_title_'.$language_id, TRUE),
			'bonus_content' => $this->input->post('bonus_content_'.$language_id, TRUE),
		);
		if(isset($_FILES['web_banner_'.$language_id]['size']) && $_FILES['web_banner_'.$language_id]['size'] > 0)
		{
			$DBdata['bonus_banner_web'] = $_FILES['web_banner_'.$language_id]['name'];
		}
		
		if(isset($_FILES['mobile_banner_'.$language_id]['size']) && $_FILES['mobile_banner_'.$language_id]['size'] > 0)
		{
			$DBdata['bonus_banner_mobile'] = $_FILES['mobile_banner_'.$language_id]['name'];
		}

		$this->db->where('bonus_id', $bonus_id);
		$this->db->where('language_id', $language_id);
		$this->db->limit(1);
		$this->db->update($this->table_bonus_lang, $DBdata);
		$DBdata['bonus_id'] = $bonus_id;
		$DBdata['language_id'] = $language_id;
		return $DBdata;
	}

	public function get_bonus_lang_data($bonus_id=NULL){
		$result = NULL;
		$query = $this
				->db
				->where('bonus_id', $bonus_id)
				->get($this->table_bonus_lang);
		
		if($query->num_rows() > 0)
		{
			$result_query = $query->result_array();
			foreach($result_query as $row){
				$result[$row['language_id']] = $row;
			} 
		}
		$query->free_result();
		return $result;
	}


	public function delete_bonus_lang($bonus_id = NULL){
		$this->db->where('bonus_id', $bonus_id);
		$this->db->delete($this->table_bonus_lang);
	}

	public function delete_bonus($bonus_id = NULL){
		$this->db->where('bonus_id', $bonus_id);
		$this->db->delete($this->table_bonus);
	}

	public function update_bonus($bonus_id = NULL){
		$DBdata = array(
			'bonus_name' => $this->input->post('bonus_name', TRUE),
			'bonus_seq' => $this->input->post('bonus_seq', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		$this->db->where('bonus_id', $bonus_id);
		$this->db->limit(1);
		$this->db->update($this->table_bonus, $DBdata);
		
		$DBdata['bonus_id'] = $bonus_id;
		
		return $DBdata;
	}

	public function add_player_bonus($bonus_data = null, $player_data = null){
		$DBdata =  array(
			'player_id' => $player_data['player_id'],
			'bonus_id' => $bonus_data['bonus_id'],
			'bonus_name' => $bonus_data['bonus_name'],
			'reward_amount' => $this->input->post('reward_amount', TRUE),
			'achieve_amount' => $this->input->post('achieve_amount', TRUE),
			'status' => STATUS_COMPLETE,
			'remark' => $this->input->post('remark', TRUE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time(),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		$this->db->insert($this->table_player_bonus, $DBdata);
		$DBdata['player_bonus_id'] = $this->db->insert_id();
		$DBdata['username'] = $player_data['username'];
		return $DBdata;
	}

	public function today_total_bonus(){
		/*
		$dbprefix = $this->db->dbprefix;
		$start_date = strtotime(date('Y-m-d 00:00:00'));
		$end_date = strtotime(date('Y-m-d 23:59:59'));
		$where = "";
		$where .= ' AND a.create_date >= ' . $start_date;
		$where .= ' AND a.create_date <= ' . $end_date;
		$where .= ' AND a.status = '. STATUS_APPROVE;
		$result = NULL;
		$query_string = "(SELECT SUM(a.reward_amount) AS total FROM {$dbprefix}player_promotions a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";
		$query = $this->db->query($query_string);
		*/
		
		$dbprefix = $this->db->dbprefix;
		$where = "";
		$where .= ' AND a.report_date >= ' . strtotime(date('Y-m-d 00:00:00'));
		$where .= ' AND a.report_date <= ' . strtotime(date('Y-m-d 23:59:59'));					
		$where .= ' AND a.transfer_type IN (' . TRANSFER_BONUS . ')';
		$query_string = "SELECT SUM(a.deposit_amount) AS total FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";
		$query = $this->db->query($query_string);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	public function week_total_bonus(){			$dbprefix = $this->db->dbprefix;		$start_date = strtotime('monday this week');		$end_date = strtotime('sunday this week') + (24 * 60 * 60 - 1);		$where = "";		$where .= ' AND a.report_date >= ' . $start_date;		$where .= ' AND a.report_date <= ' . $end_date;							$where .= ' AND a.transfer_type IN (' . TRANSFER_BONUS . ')';		$query_string = "SELECT SUM(a.deposit_amount) AS total FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";		$query = $this->db->query($query_string);		if($query->num_rows() > 0)		{			$result = $query->row_array();  		}		$query->free_result();		return $result;	}	public function month_total_bonus(){			$dbprefix = $this->db->dbprefix;		$start_date = mktime(0, 0, 0, date('m'), 1, date('Y'));		$end_date = mktime(23, 59, 59, date('m'), date('t'), date('Y'));		$where = "";		$where .= ' AND a.report_date >= ' . $start_date;		$where .= ' AND a.report_date <= ' . $end_date;							$where .= ' AND a.transfer_type IN (' . TRANSFER_BONUS . ')';		$query_string = "SELECT SUM(a.deposit_amount) AS total FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";		$query = $this->db->query($query_string);		if($query->num_rows() > 0)		{			$result = $query->row_array();  		}		$query->free_result();		return $result;	}
	public function player_total_bonus($username = NULL){

		$result = NULL;
		
		$query = $this
				->db
				->select('COALESCE(SUM(deposit_amount),0) AS total')
				->where('username', $username)
				->where_in('transfer_type', array(TRANSFER_BONUS))
				->get('cash_transfer_report');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		return $result;
	}
}