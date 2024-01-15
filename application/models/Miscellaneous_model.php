<?php
class Miscellaneous_model extends CI_Model {

	public function get_miscellaneous()
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('miscellaneous_id', 1)
				->limit(1)
				->get('miscellaneous');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_transaction_notice_lang($type = NULL, $lang = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('miscellaneous_type', $type)
				->where('language_id', $lang)
				->limit(1)
				->get('miscellaneous_lang');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
}