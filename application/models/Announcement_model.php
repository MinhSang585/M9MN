<?php
class Announcement_model extends CI_Model {

	public function get_announcement_list($lang = NULL)
	{	
		$result = NULL;
		
		$dbprefix = $this->db->dbprefix;
		$query = $this->db->query("SELECT a.announcement_id, b.content, a.start_date, a.end_date FROM {$dbprefix}announcements a, {$dbprefix}announcement_lang b WHERE (a.announcement_id = b.announcement_id) AND a.active = ? AND b.language_id = ? ORDER BY a.announcement_id DESC", array(STATUS_ACTIVE, $lang));
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
		}else{
		    $query = $this
					->db
					->select('announcement_id, content, start_date, end_date')
					->where('active', STATUS_ACTIVE)
					->order_by('announcement_id', 'desc')
					->get('announcements');
		    if($query->num_rows() > 0)
		    {
		        $result = $query->result_array();
		    }
		}
		$query->free_result();
		
		return $result;
	}
}