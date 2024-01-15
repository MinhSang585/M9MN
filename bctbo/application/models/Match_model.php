<?php
class Match_model extends CI_Model {

	protected $table_match = 'match';
	protected $table_match_lang = 'match_lang';

	public function add_match(){
		$DBdata = array(
			'match_name' => $this->input->post('match_name', TRUE),
			'match_start' => strtotime($this->input->post('match_start', TRUE)),
			'match_end' => strtotime($this->input->post('match_end', TRUE)),
			'match_url' => $this->input->post('match_url', TRUE),
			'match_sequence' => $this->input->post('match_sequence', TRUE),
			'active' => STATUS_ACTIVE,//(($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time(),
		);
		$this->db->insert($this->table_match, $DBdata);
		$DBdata['match_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function update_match($match_id = NULL){
		$DBdata = array(
			'match_name' => $this->input->post('match_name', TRUE),
			'match_start' => strtotime($this->input->post('match_start', TRUE)),
			'match_end' => strtotime($this->input->post('match_end', TRUE)),
			'match_url' => $this->input->post('match_url', TRUE),
			'match_sequence' => $this->input->post('match_sequence', TRUE),
			'active' => STATUS_ACTIVE,//(($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		$this->db->where('match_id', $match_id);
		$this->db->limit(1);
		$this->db->update($this->table_match, $DBdata);
		
		$DBdata['match_id'] = $match_id;
		
		return $DBdata;
	}

	public function get_match_data($match_id = null){
		$result = NULL;
		$query = $this
				->db
				->where('match_id', $match_id)
				->limit(1)
				->get($this->table_match);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		return $result;
	}

	public function add_match_content($match_id=NULL, $language_id = NULL){
		$DBdata = array(
			'match_title' => $this->input->post('match_title_'.$language_id, TRUE),
			'match_content' => $this->input->post('match_content_'.$language_id, TRUE),
			'web_match_alt' => $this->input->post('web_match_alt_'.$language_id, TRUE),
			'web_match_width' => $this->input->post('web_match_width_'.$language_id, TRUE),
			'web_match_height' => $this->input->post('web_match_height_'.$language_id, TRUE),
			'mobile_match_alt' => $this->input->post('mobile_match_alt_'.$language_id, TRUE),
			'mobile_match_width' => $this->input->post('mobile_match_width_'.$language_id, TRUE),
			'mobile_match_height' => $this->input->post('mobile_match_height_'.$language_id, TRUE),
			'match_id' => $match_id,
			'language_id' => $language_id,
		);
		if(isset($_FILES['web_banner_'.$language_id]['size']) && $_FILES['web_banner_'.$language_id]['size'] > 0)
		{
			$DBdata['match_banner_web'] = $_FILES['web_banner_'.$language_id]['name'];
		}
		
		if(isset($_FILES['mobile_banner_'.$language_id]['size']) && $_FILES['mobile_banner_'.$language_id]['size'] > 0)
		{
			$DBdata['match_banner_mobile'] = $_FILES['mobile_banner_'.$language_id]['name'];
		}
		$this->db->insert($this->table_match_lang, $DBdata);
		return $DBdata;
	}

	public function update_match_content($match_id=NULL, $language_id = NULL){
		$DBdata = array(
			'match_title' => $this->input->post('match_title_'.$language_id, TRUE),
			'match_content' => $this->input->post('match_content_'.$language_id, TRUE),
			'web_match_alt' => $this->input->post('web_match_alt_'.$language_id, TRUE),
			'web_match_width' => $this->input->post('web_match_width_'.$language_id, TRUE),
			'web_match_height' => $this->input->post('web_match_height_'.$language_id, TRUE),
			'mobile_match_alt' => $this->input->post('mobile_match_alt_'.$language_id, TRUE),
			'mobile_match_width' => $this->input->post('mobile_match_width_'.$language_id, TRUE),
			'mobile_match_height' => $this->input->post('mobile_match_height_'.$language_id, TRUE),
		);
		if(isset($_FILES['web_banner_'.$language_id]['size']) && $_FILES['web_banner_'.$language_id]['size'] > 0)
		{
			$DBdata['match_banner_web'] = $_FILES['web_banner_'.$language_id]['name'];
		}
		
		if(isset($_FILES['mobile_banner_'.$language_id]['size']) && $_FILES['mobile_banner_'.$language_id]['size'] > 0)
		{
			$DBdata['match_banner_mobile'] = $_FILES['mobile_banner_'.$language_id]['name'];
		}

		$this->db->where('match_id', $match_id);
		$this->db->where('language_id', $language_id);
		$this->db->limit(1);
		$this->db->update($this->table_match_lang, $DBdata);
		$DBdata['match_id'] = $match_id;
		$DBdata['language_id'] = $language_id;
		return $DBdata;
	}

	public function get_match_lang_data($match_id=NULL){
		$result = NULL;
		$query = $this
				->db
				->where('match_id', $match_id)
				->get($this->table_match_lang);
		
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

	public function delete_match_lang($match_id = NULL){
		$this->db->where('match_id', $match_id);
		$this->db->delete($this->table_match_lang);
	}

	public function delete_match($match_id = NULL){
		$this->db->where('match_id', $match_id);
		$this->db->delete($this->table_match);
	}
}