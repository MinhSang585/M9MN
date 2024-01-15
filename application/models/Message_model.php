<?php
class Message_model extends CI_Model {

	public function get_message_by_pid($pid = null,$language = null,$start = null) {
		$this->db->select('system_message_user.*, system_message_user_lang.system_message_title, system_message_user_lang.system_message_content');
		$this->db->from('system_message_user');
		$this->db->join('system_message_user_lang', 'system_message_user_lang.system_message_user_id = system_message_user.system_message_user_id');
		$this->db->where('system_message_user.active',1);
		$this->db->where('system_message_user.player_id',$pid);
		if($start != null) {
			$this->db->where('system_message_user.created_date >=',$start);
		}
		$this->db->where('system_message_user_lang.language_id',$language);		
		$this->db->order_by('system_message_user.created_date','desc');
		$this->db->order_by('system_message_user.updated_date','desc');
		$query = $this->db->get();
				
		if($query->num_rows() > 0) {
			$result = $query->result_array();
		}
		else {
			$result = array();
		}

		$query->free_result();

		return $result;
	}
	
	public function update_message($id=null,$pid=null,$dbdata=null){
		$this->db->where('system_message_user_id', $id);
		$this->db->where('player_id',$pid);
		$this->db->limit(1);
		$this->db->update('system_message_user', $dbdata);
	}
	
	public function get_message_by_id($id=null,$pid=null,$language = null){
		$this->db->select('system_message_user.*, system_message_user_lang.system_message_title,system_message_user_lang.system_message_content');
		$this->db->from('system_message_user');
		$this->db->join('system_message_user_lang', 'system_message_user_lang.system_message_user_id = system_message_user.system_message_user_id');
		$this->db->where('system_message_user.system_message_user_id',$id);
		$this->db->where('system_message_user.active',1);
		$this->db->where('system_message_user.player_id',$pid);
		$this->db->where('system_message_user_lang.language_id',$language);
		$this->db->limit(1);
		$query = $this->db->get();
				
		if($query->num_rows() > 0) {
			$result = $query->row_array();
		}
		else {
			$result = array();
		}

		$query->free_result();

		return $result;
		
	}
	
	public function unread($uid = null) {
		$previous_week 	= strtotime("-2 week");
		
		$this->db->select('system_message_user_id');
		$this->db->where('is_read', 1);
		$this->db->where('player_id', $uid);
		$this->db->where('created_date >=', $previous_week);
		#$this->db->limit(1);
		$query 	= $this->db->get('system_message_user');
		
		$result = $query->num_rows();#($query->num_rows() > 0) ? TRUE : FALSE;
		$query->free_result();

		return $result;
	}
	
	public function remove_message($mid = null,$uid = null) {
		if($mid == '') {
			$this->db->select('system_message_user_id');			
			$this->db->where('player_id', $uid);			
			$query 	= $this->db->get('system_message_user');
			
			$result = ($query->num_rows() > 0) ? $query->result_array() : array();
			$query->free_result();			
			if(sizeof($result)>0) {
				$ids = array();
				foreach($result as $a) {
					array_push($ids,$a['system_message_user_id']);
				}				
				$tables = array('system_message_user', 'system_message_user_lang');				
				$this->db->where_in('system_message_user_id', $ids);
				$this->db->delete($tables);
			}
		}
		else {			
			$this->db->select('system_message_user_id');
			$this->db->where('system_message_user_id', $mid);
			$this->db->where('player_id', $uid);
			$this->db->limit(1);
			$query 	= $this->db->get('system_message_user');
			
			$result = ($query->num_rows() > 0) ? TRUE : FALSE;
			$query->free_result();
			
			if($result) {
				$tables = array('system_message_user', 'system_message_user_lang');
				$this->db->where('system_message_user_id', $mid);
				$this->db->delete($tables);
			}
		}
		return true;
	}
    
    public function get_message_data_by_templete($id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('system_message_templete', $id)
				->limit(1)
				->get('system_message');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function get_message_lang_data($id = NULL){
		$result = NULL;
		
		$query = $this
				->db
				->select('system_message_title, system_message_content, language_id')
				->where('system_message_id', $id)
				->get('system_message_lang');
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
	
	public function get_player_all_data_by_message_genre($array = null){
		$result = NULL;
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

			$query =  $this->db->get('players');
			if($query->num_rows() > 0)
			{
				$result = $query->result_array();	
			}
			$query->free_result();
		}
		return $result;
	}
	
	public function get_message_bluk_data($system_message_id = NULL, $created_time = NULL){
		$result = array();
		$this->db->select('system_message_user_id,player_id');
		$this->db->where('system_message_id',$system_message_id);
		$this->db->where('created_date',$created_time);
		$query = $this->db->get('system_message_user');
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
}