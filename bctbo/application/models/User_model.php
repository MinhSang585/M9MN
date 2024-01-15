<?php
class User_model extends CI_Model {

	protected $table_users = 'users';
	protected $table_user_logs = 'user_logs';
	protected $table_user_role = 'user_role';
	protected $tableRoleNumber = 'role_number';

	public function verify_login()
	{
		$result = NULL;
		
		$username = $this->input->post('username', TRUE);
		$password = $this->input->post('password', TRUE);
		
		$query = $this
                ->db
				->select('user_id, nickname, username, password, user_type, active, flag, permissions, upline, white_list_ip, user_role, old_password')
				->where('username', $username)
				->limit(1)
                ->get($this->table_users);
		if($query->num_rows() > 0) 
		{
			$row = $query->row_array();
			
			$session_data['user_id'] = $row['user_id'];
			$session_data['nickname'] = $row['nickname'];
			$session_data['username'] = $row['username'];
			$session_data['user_type'] = $row['user_type'];
			$session_data['active'] = $row['active'];
			$session_data['flag'] = $row['flag'];
			$session_data['permissions'] = $row['permissions'];
			$session_data['user_role'] = $row['user_role'];
			$session_data['upline'] = $row['upline'];
			$session_data['white_list_ip'] = $row['white_list_ip'];
			$session_data['user_group'] = USER_GROUP_USER;
			$session_data['last_login_date'] = time();
			$session_data['login_token'] = session_id();
			$session_data['is_logged_in'] = FALSE;
			

			if(empty($row['password'])){
				if(password_verify($password.OLD_PASSWORD_HASH, $row['old_password'])){
					$session_data['is_logged_in'] = TRUE;
				}
			}else{
				if(password_verify($password, $row['password'])) 
				{
					$session_data['is_logged_in'] = TRUE;
				}
			}
			
			
			$result = $session_data;
		}

		$query->free_result();
		
		return $result;
	}
	
	public function update_last_login($data = NULL)
	{
		$DBdata = array(
			'last_login_date' => $data['last_login_date'],
			'last_login_ip' => $this->input->ip_address(),
			'login_token' => $data['login_token']
		);
		
		$this->db->where('user_id', $data['user_id']);
		$this->db->limit(1);
		$this->db->update($this->table_users, $DBdata);
	}
	
	public function insert_log($type = NULL, $ndata = NULL, $odata = NULL)
	{
		$DBdata = array(
			'user_id' => $this->session->userdata('user_id'),
			'player_id' => ((isset($ndata['player_id']))?$ndata['player_id']:''),
			'log_type' => $type,
			'ip_address' => $this->input->ip_address(),
			'log_date' => time(),
			'old_data' => (($odata) ? json_encode($odata) : ''),
			'new_data' => (($ndata) ? json_encode($ndata) : '')
		);
		
		if($this->agent->is_mobile()) 
		{
			$DBdata['user_agent'] = $this->agent->mobile() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
			$DBdata['platform'] = PLATFORM_MOBILE_WEB;
		}
		else 
		{
			$DBdata['user_agent'] = $this->agent->browser() . ' ' . $this->agent->version();
			$DBdata['platform'] = PLATFORM_WEB;
		}
		
		$this->db->insert($this->table_user_logs, $DBdata);
	}
	
	public function verify_session()
	{
		$result = FALSE;
		
		$query = $this
				->db
				->select('login_token')
				->where('user_id', $this->session->userdata('user_id'))
				->where('login_token', $this->session->userdata('login_token'))
				->limit(1)
				->get($this->table_users);
				
		if($query->num_rows() > 0) 
		{
			$result = TRUE;
		}

		$query->free_result();
		
		return $result;
	}
	
	public function clear_login_token()
	{
		$this->db->set('login_token', '');
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->limit(1);
		$this->db->update($this->table_users);
	}
	
	public function verify_current_password()
	{
		$result = FALSE;
		
		$oldpass = $this->input->post('oldpass', TRUE);
		
		$query = $this
				->db
				->select('password')
				->where('user_id', $this->session->userdata('user_id'))
				->limit(1)
				->get($this->table_users);
				
		if($query->num_rows() > 0) 
		{
			$row = $query->row_array();
			
			if(password_verify($oldpass, $row['password'])) 
			{
				$result = TRUE;
			}
		}

		$query->free_result();
		
		return $result;
	}
	
	public function update_password()
	{	
		$new_password = $this->input->post('password', TRUE);
		$new_password = password_hash($new_password, PASSWORD_DEFAULT);
		
		$this->db->set('password', $new_password);
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->limit(1);
		$this->db->update($this->table_users);
	}
	
	public function get_downline_data($username = NULL)
	{	
		$result = NULL;
		
		$where = "(upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' OR user_id = " . $this->session->userdata('root_user_id') . ")";
		
		$query = $this
				->db
				->where('username', $username)
				->where($where)
				->limit(1)
				->get($this->table_users);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function getNumberRole($permissionUser)
	{
		$result = null;

		$query = $this->db
			 ->order_by('parent_id')
			 ->where_in('number', explode(',' , $permissionUser)  )
             ->get($this->tableRoleNumber);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function get_user_data_by_username($username = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('username', $username)
				->limit(1)
				->get($this->table_users);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function get_user_data($id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('user_id', $id)
				->limit(1)
				->get($this->table_users);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function add_user($user = NULL)
	{
		$this->load->library('rng');
		$referral_code = $this->rng->get_token(10);
		
		#Check for referral code availbility
		$referral_code = $this->input->post('username', TRUE);
		/*
		$is_loop = TRUE;
		while($is_loop == TRUE) {
			$rs = $this->get_user_data_by_referral_code($referral_code);
			if( ! empty($rs)) {
				$referral_code = $this->rng->get_token(10);
			}
			else {
				$is_loop = FALSE;
			}
		}
		*/

		$new_password = $this->input->post('password', TRUE);
		$new_password = password_hash($new_password, PASSWORD_DEFAULT);
		
		$DBdata = array(
			'nickname' => $this->input->post('nickname', TRUE),
			'mobile' => $this->input->post('mobile', TRUE),
			'email' => $this->input->post('email', TRUE),
			'username' => $this->input->post('username', TRUE),
			'password' => $new_password,
			'user_type' => get_downline_user_type($user['user_type']),
			'active' => STATUS_ACTIVE,
			'user_role' => $this->input->post('user_role', TRUE),
			'permissions' => get_upline_permission(USER_GROUP_USER, $user['user_type'], $user['permissions']),
			'possess' => $this->input->post('possess', TRUE),
			'casino_comm' => $this->input->post('casino_comm', TRUE),
			'slots_comm' => $this->input->post('slots_comm', TRUE),
			'sport_comm' => $this->input->post('sport_comm', TRUE),
			'cf_comm' => $this->input->post('cf_comm', TRUE),
			'other_comm' => $this->input->post('other_comm', TRUE),
			'upline' => $user['username'],
			'referral_code' => $referral_code,
			'domain_sub' => $this->input->post('domain', TRUE),
			'white_list_ip' => (($this->input->post('white_list_ip[]', TRUE)) ? implode(',', $this->input->post('white_list_ip[]', TRUE)): ''),
			'upline_ids' => (empty($user['upline_ids']) ? ',' . $user['user_id'] . ',' : $user['upline_ids'] . $user['user_id'] . ','),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);
		
		$this->db->insert($this->table_users, $DBdata);
		
		$DBdata['user_id'] = $this->db->insert_id();
		
		return $DBdata;
	}
	
	public function verify_downline_possess($user_id = NULL, $possess = NULL)
	{	
		$result = TRUE;
		
		$where = "possess > " . $possess . " AND upline_ids LIKE '%," . $user_id . ",%'";
		
		$query = $this
				->db
				->where($where)
				->limit(1)
				->get($this->table_users);
		
		if($query->num_rows() > 0)
		{
			$result = FALSE;
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function updateDownline($userId, $uplineIds, $userType) {
		$tableUser = $this->db->dbprefix . $this->table_users;
	
		$sql = "SELECT user_id, upline_ids FROM {$tableUser} WHERE upline_ids LIKE ?";
		$query = $this->db->query($sql, ["%,$userId,%"]);
		$downlineList = $query->result_array();
		$query->free_result();
	
		foreach ($downlineList as $downlineItem) {
			$newUplineIds = $uplineIds . $userId . ",";
			$newUserType = min($userType + 1, 4);
	
			$updateSql = "UPDATE {$tableUser} SET upline_ids = ?, user_type = ? WHERE user_id = ?";
			$this->db->query($updateSql, [$newUplineIds, $newUserType, $downlineItem['user_id']]);
	
			// handle downline
			$this->updateDownline($downlineItem['user_id'], $newUplineIds, $newUserType);
		}
	}
	
	public function update_user($arr = NULL)
	{	
		
		if($arr['isUpdateUpline'] == true){
			$tableUser = $this->db->dbprefix . $this->table_users;
			$sql = "SELECT username, upline, upline_ids, user_type FROM {$tableUser} WHERE user_id = ? LIMIT 1";
			$query = $this->db->query($sql,[$arr['uplineId']]);
			$uplineInfo = array();
			if($query->num_rows() > 0)
			{
				$uplineInfo = $query->row_array();  
			}
			$query->free_result();
			if(empty($uplineInfo['upline_ids'])){
				$uplineIds = "," . $uplineInfo['upline_ids'] . $arr['uplineId'] . ",";
			} else {
				$uplineIds = $uplineInfo['upline_ids'] . $arr['uplineId'] . ",";
			}
			
			$userType = $uplineInfo['user_type'] + 1;
			$dbData = array(
				'nickname' => $this->input->post('nickname', TRUE),
				'mobile' => $this->input->post('mobile', TRUE),
				'email' => $this->input->post('email', TRUE),
				'user_role' => $this->input->post('user_role', TRUE),
				'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_SUSPEND),
				'possess' => $this->input->post('possess', TRUE),
				'casino_comm' => $this->input->post('casino_comm', TRUE),
				'slots_comm' => $this->input->post('slots_comm', TRUE),
				'sport_comm' => $this->input->post('sport_comm', TRUE),
				'cf_comm' => $this->input->post('cf_comm', TRUE),
				'other_comm' => $this->input->post('other_comm', TRUE),
				'white_list_ip' => (($this->input->post('white_list_ip[]', TRUE)) ? implode(',', $this->input->post('white_list_ip[]', TRUE)) : ''),
				'updated_by' => $this->session->userdata('username'),
				'updated_date' => time(),
				'upline' => $uplineInfo['username'],
				'upline_ids' => $uplineIds,
				'user_type' => $userType
			);
			
			//update upline_ids and user_type for downline list of user
			$this->updateDownline($arr['user_id'], $uplineIds, $userType);
		}
		else{
			$dbData = array(
				'nickname' => $this->input->post('nickname', TRUE),
				'mobile' => $this->input->post('mobile', TRUE),
				'email' => $this->input->post('email', TRUE),
				'user_role' => $this->input->post('user_role', TRUE),
				'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_SUSPEND),
				'possess' => $this->input->post('possess', TRUE),
				'casino_comm' => $this->input->post('casino_comm', TRUE),
				'slots_comm' => $this->input->post('slots_comm', TRUE),
				'sport_comm' => $this->input->post('sport_comm', TRUE),
				'cf_comm' => $this->input->post('cf_comm', TRUE),
				'other_comm' => $this->input->post('other_comm', TRUE),
				'white_list_ip' => (($this->input->post('white_list_ip[]', TRUE)) ? implode(',', $this->input->post('white_list_ip[]', TRUE)) : ''),
				'updated_by' => $this->session->userdata('username'),
				'updated_date' => time()
			);
		}
		
		$this->db->where('user_id', $arr['user_id']);
		$this->db->where('user_id != ', $this->session->userdata('root_user_id'));
		$this->db->limit(1);
		$this->db->update($this->table_users, $dbData);
		
		$dbData['user_id'] = $arr['user_id'];
		$dbData['username'] = $arr['username'];
		
		return $dbData;
	}
	
	public function update_user_permission($arr = NULL, $permissions = NULL)
	{	
		$DBdata = array(
			'permissions' => $permissions,
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		
		$this->db->where('user_id', $arr['user_id']);
		$this->db->where('user_id != ', $this->session->userdata('root_user_id'));
		$this->db->limit(1);
		$this->db->update($this->table_users, $DBdata);
		
		$DBdata['user_id'] = $arr['user_id'];
		$DBdata['username'] = $arr['username'];
		
		return $DBdata;
	}
	
	public function update_user_password($arr = NULL)
	{	
		$new_password = $this->input->post('password', TRUE);
		$new_password = password_hash($new_password, PASSWORD_DEFAULT);
		
		$DBdata = array(
			'password' => $new_password,
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		
		$this->db->where('user_id', $arr['user_id']);
		$this->db->limit(1);
		$this->db->update($this->table_users, $DBdata);
		
		$DBdata['user_id'] = $arr['user_id'];
		$DBdata['username'] = $arr['username'];
		
		return $DBdata;
	}
	
	public function roleUpdate($arr = NULL)
	{	
		$permissions = $this->input->post('permissions', TRUE);
		$permissions_str = implode(",", $permissions);
		$flag = 1;
				
		$DBdata = array(
			'permissions' => $permissions_str,
			'flag' => $flag,
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		
		$this->db->where('user_id', $arr['user_id']);
		$this->db->limit(1);
		$this->db->update($this->table_users, $DBdata);
		
		$DBdata['user_id'] = $arr['user_id'];
		$DBdata['username'] = $arr['username'];
		
		return $DBdata;
	}
	
	public function point_transfer($arr = NULL, $points = NULL)
	{	
		$DBdata = array(
			'user_id' => $arr['user_id'],
			'username' => $arr['username'],
			'points' => $points,
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		
		$table = $this->db->dbprefix . $this->table_users;
		$this->db->query("UPDATE {$table} SET points = (points + ?), updated_by = ?, updated_date = ? WHERE user_id = ? LIMIT 1", array($DBdata['points'], $DBdata['updated_by'], $DBdata['updated_date'], $DBdata['user_id']));
		
		return $DBdata;
	}

	public function get_user_data_by_referral_code($referral_code = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('username')
				->where('referral_code', $referral_code)
				->limit(1)
				->get('users');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function check_domain_name($sub = NULL){
		$result = NULL;
		$query = $this
				->db
				->select('username')
				->like('domain_name', $sub.'.', 'after')
				->limit(1)
				->get('users');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		
		return $result;
	}

	public function get_user_data_by_user_role_id($user_role_id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('user_role_id', $user_role_id)
				->limit(1)
				->get($this->table_users);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_all_user_data(){
		$result = array();
		$this->db->select('user_id,username');
		$this->db->order_by('username',"ASC");
		$query = $this->db->get($this->table_users);
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	/**
	 * Get upline user list of selected user
	 */
	public function getUplineList($userType){

		$result = NULL;
		$sql = "SELECT user_id,username FROM " . $this->db->dbprefix . $this->table_users . " WHERE user_type > 0 AND user_type < " . $userType;
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
}