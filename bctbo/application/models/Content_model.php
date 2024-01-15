<?php
class Content_model extends CI_Model {

	protected $table_content_page = 'content_page';
	protected $table_content_page_lang = 'content_page_lang';

	public function get_content_data($content_key_id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('content_key_id',$content_key_id)
				->limit(1)
				->get($this->table_content_page);

		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_content_lang_data($content_key_id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('content_key_id', $content_key_id)
				->get($this->table_content_page_lang);

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

	public function add_content(){
		$domain = $this->input->post('domain', TRUE);
		$domain_array = array_filter(explode('#',strtolower($domain)));
		$DBdata = array(
			'content_id' => trim($this->input->post('content_id', TRUE)),
			'content_name' => trim($this->input->post('content_name', TRUE)),
			'domain' => ((!empty($domain_array)) ? ",".implode(',',$domain_array)."," : ""),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);

		$this->db->insert($this->table_content_page, $DBdata);
		$DBdata['content_key_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function update_content($content_key_id = NULL){
		$domain = $this->input->post('domain', TRUE);
		$domain_array = array_filter(explode('#',strtolower($domain)));
		$DBdata = array(
			'content_id' => trim($this->input->post('content_id', TRUE)),
			'content_name' => trim($this->input->post('content_name', TRUE)),
			'domain' => ((!empty($domain_array)) ? ",".implode(',',$domain_array)."," : ""),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);

		$this->db->where('content_key_id', $content_key_id);
		$this->db->limit(1);
		$this->db->update($this->table_content_page, $DBdata);
		$DBdata['content_key_id'] = $content_key_id;
		return $DBdata;
	}

	public function add_content_lang($content_key_id = NULL, $language_id = NULL, $arr = NULL){
		$DBdata = array(
			'content_key_id' => $content_key_id,
			'content_web_file_path' => ((isset($arr['web'])) ? $arr['web'] : ''),
			'content_mobile_file_path' => ((isset($arr['mobile'])) ? $arr['mobile'] : ''),
			'content_hybrid_file_path' => ((isset($arr['hybrid'])) ? $arr['hybrid'] : ''),
			'language_id' => $language_id,
		);
		$this->db->insert($this->table_content_page_lang, $DBdata);
	}

	public function update_content_lang($content_key_id = NULL, $language_id = NULL, $arr = NULL){
		$DBdata = array(
			'content_web_file_path' => ((isset($arr['web'])) ? $arr['web'] : ''),
			'content_mobile_file_path' => ((isset($arr['mobile'])) ? $arr['mobile'] : ''),
			'content_hybrid_file_path' => ((isset($arr['hybrid'])) ? $arr['hybrid'] : ''),
		);

		$this->db->where('content_key_id', $content_key_id);
		$this->db->where('language_id', $language_id);
		$this->db->limit(1);
		$this->db->update($this->table_content_page_lang, $DBdata);
		return $DBdata;	
	}

	public function delete_content($content_key_id = NULL){
		$this->db->where('content_key_id', $content_key_id);
		$this->db->delete($this->table_content_page);
	}

	public function delete_content_lang($content_key_id = NULL){
		$this->db->where('content_key_id', $content_key_id);
		$this->db->delete($this->table_content_page_lang);	
	}
}