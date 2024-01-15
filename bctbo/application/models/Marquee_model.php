<?php
class Marquee_model extends CI_Model {

	protected $table_marquee = 'marquees';
	protected $table_marquee_lang = 'marquee_lang';
	
	public function get_marquee_data($id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('announcement_id', $id)
				->limit(1)
				->get($this->table_marquee);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function add_marquee()
	{	
		$DBdata = array(
			'content' => $this->input->post('content', TRUE),
			'start_date' => strtotime($this->input->post('start_date', TRUE)),
			'end_date' => strtotime($this->input->post('end_date', TRUE)),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);
		
		$this->db->insert($this->table_marquee, $DBdata);
		
		$DBdata['announcement_id'] = $this->db->insert_id();
		
		return $DBdata;
	}
	
	public function update_marquee($id = NULL)
	{	
		$DBdata = array(
			'content' => $this->input->post('content', TRUE),
			'start_date' => strtotime($this->input->post('start_date', TRUE)),
			'end_date' => strtotime($this->input->post('end_date', TRUE)),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		
		$this->db->where('announcement_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_marquee, $DBdata);
		
		$DBdata['announcement_id'] = $id;
		
		return $DBdata;
	}
	
	public function delete_marquee($id = NULL)
	{	
		$this->db->where('announcement_id', $id);
		$this->db->limit(1);
		$this->db->delete($this->table_marquee);
	}
	
	public function get_marquee_lang_data($id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('content, language_id')
				->where('announcement_id', $id)
				->get($this->table_marquee_lang);
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$result[$row->language_id] = $row->content;
			}	
		}
		
		$query->free_result();
		
		return $result;
	}

	public function add_marquee_lang($marquee_id = NULL, $language_id = NULL)
	{
		$DBdata = array(
			'content' => $this->input->post('marquee_name-'.$language_id, TRUE),
			'announcement_id' => $marquee_id,
			'language_id' => $language_id
		);
		$this->db->insert($this->table_marquee_lang, $DBdata);
	}
	
	public function delete_marquee_lang($id = NULL)
	{	
		$this->db->where('announcement_id', $id);
		$this->db->delete($this->table_marquee_lang);
	}
}