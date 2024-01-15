<?php
class Player_model extends CI_Model {

	public function get_fingerprint_data($fingerprint = NULL){
		$result = FALSE;
		$this->db->select('fingerprint.fingerprint_id');
		$this->db->from('fingerprint');
		$this->db->join('players','players.player_id = fingerprint.player_id');
		$this->db->where('players.is_fingerprint',STATUS_ACTIVE);
		$this->db->where('fingerprint.fingerprint_code',$fingerprint);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		
		return $result;
	}

	public function get_fingerprint_data_exclude_member($fingerprint = NULL, $data = NULL){
		$result = FALSE;
		$this->db->select('fingerprint.fingerprint_id');
		$this->db->from('fingerprint');
		$this->db->join('players','players.player_id = fingerprint.player_id');
		$this->db->where('players.is_fingerprint',STATUS_ACTIVE);
		$this->db->where('fingerprint.fingerprint_code',$fingerprint);
		$this->db->where('fingerprint.player_id != ',$data['player_id']);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		
		return $result;
	}

	public function get_fingerprint_data_member($fingerprint = NULL, $data = NULL){
		$result = FALSE;
		$this->db->select('fingerprint.fingerprint_id');
		$this->db->from('fingerprint');
		$this->db->join('players','players.player_id = fingerprint.player_id');
		$this->db->where('players.is_fingerprint',STATUS_ACTIVE);
		$this->db->where('fingerprint.fingerprint_code',$fingerprint);
		$this->db->where('fingerprint.player_id',$data['player_id']);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		$query->free_result();
		
		return $result;
	}

	public function add_fingerprint($fingerprint = NULL, $data = NULL){
		$DBdata = array(
			'fingerprint_code' => $fingerprint,
			'player_id' => $data['player_id'],
			'username' => $data['username'],
			'ip_address' => $this->input->ip_address(),
			'created_by' => $data['username'],
			'created_date' => time(),
		);
		$this->db->insert('fingerprint', $DBdata);
		
		$DBdata['fingerprint_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function check_username_exits($username = NULL)
	{
		$result = FALSE;
		
		$query = $this
				->db
				->select('player_id')
				->where('username', $username)
				->limit(1)
				->get('players');
				
		if($query->num_rows() > 0) 
		{
			$result = TRUE;
		}

		$query->free_result();
		
		return $result;
	}

	public function get_random_avatar(){
		$result = FALSE;
		
		$query = $this
				->db
				->select('avatar_id')
				->where('active', STATUS_ACTIVE)
				->limit(1)
				->get('avatar');
				
		if($query->num_rows() > 0) 
		{
			$result = $query->row_array();
		}

		$query->free_result();
		
		return $result;	
	}
	
	public function add_player($user = NULL, $data = NULL)
	{
		$avatar = $this->get_random_avatar();
		$new_password = $data['password'];
		$new_password = password_hash($new_password, PASSWORD_DEFAULT);
		
		$DBdata = array(
			'avatar' => $data['avatar'],
			'full_name' => $data['full_name'],
			'nickname' => $data['nickname'],
			'mobile' => $data['mobile'],
			'email' => $data['email'],
			'wechat' => $data['wechat'],
			'referrer' => $data['referrer'],
			'username' => $data['username'],
			'password' => $new_password,
			'active' => STATUS_ACTIVE,
			'upline' => $user['username'],
			'upline_ids' => (empty($user['upline_ids']) ? ',' . $user['user_id'] . ',' : $user['upline_ids'] . $user['user_id'] . ','),
			'referral_code' => $data['referral_code'],
			'is_player_change_password' => STATUS_ACTIVE,
			'is_offline_deposit' => STATUS_ACTIVE,
			'is_online_deposit' => STATUS_ACTIVE,
			'bank_group_id' => ',1,',
			'created_by' => $data['username'],
			'created_date' => time()
		);
		
		$this->db->insert('players', $DBdata);
		
		$DBdata['player_id'] = $this->db->insert_id();
		
		return $DBdata;
	}
	
	public function insert_log($type = NULL, $device = NULL, $ndata = NULL, $odata = NULL)
	{
		$DBdata = array(
			'player_id' => $ndata['player_id'],
			'log_type' => $type,
			'ip_address' => $this->input->ip_address(),
			'log_date' => time(),
			'old_data' => (($odata) ? json_encode($odata) : ''),
			'new_data' => (($ndata) ? json_encode($ndata) : '')
		);
		
		if( ! empty($device))
		{
			$DBdata['user_agent'] = $this->agent->mobile() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
			$DBdata['platform'] = $device;
		}
		else if($this->agent->is_mobile()) 
		{
			$DBdata['user_agent'] = $this->agent->mobile() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
			$DBdata['platform'] = PLATFORM_MOBILE_WEB;
		}
		else 
		{
			$DBdata['user_agent'] = $this->agent->browser() . ' ' . $this->agent->version();
			$DBdata['platform'] = PLATFORM_WEB;
		}
		
		$this->db->insert('player_logs', $DBdata);
	}
	
	public function get_downline_data($upline = NULL, $username = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('username', $username)
				->where('upline', $upline)
				->where('active', STATUS_ACTIVE)
				->limit(1)
				->get('players');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function point_transfer($arr = NULL, $points = NULL, $upline = NULL)
	{	
		$DBdata = array(
			'player_id' => $arr['player_id'],
			'username' => $arr['username'],
			'points' => $points,
			'updated_by' => $upline,
			'updated_date' => time()
		);
		
		$table = $this->db->dbprefix . 'players';
		$this->db->query("UPDATE {$table} SET points = (points + ?) WHERE player_id = ? LIMIT 1", array($DBdata['points'], $DBdata['player_id']));
		
		return $DBdata;
	}
	
	public function verify_login($data = NULL)
	{
		$result = NULL;
		
		$query = $this
                ->db
				->select('player_id, avatar, nickname, mobile, email, wechat, bank_id, bank_account_name, bank_account_no, referral_code, username, password, active, player_type, is_promotion, is_player_bank_account, is_fingerprint, is_level, is_player_change_password, is_offline_deposit, is_online_deposit, is_credit_card_deposit, is_hypermart_deposit')
				->where('username', $data['username'])
				->limit(1)
                ->get('players');
				
		if($query->num_rows() > 0) 
		{
			$row = $query->row_array();
			
			$session_data = array();
			$session_data['player_id'] = $row['player_id'];
			$session_data['avatar'] = str_pad($row['avatar'], 2, "0", STR_PAD_LEFT);
			$session_data['nickname'] = $row['nickname'];
			$session_data['username'] = $row['username'];
			$session_data['player_type'] = $row['player_type'];
			$session_data['is_promotion'] = $row['is_promotion'];
			$session_data['is_player_bank_account'] = $row['is_player_bank_account'];
			$session_data['is_fingerprint'] = $row['is_fingerprint'];
			$session_data['is_level'] = $row['is_level'];
			$session_data['mobile'] = $row['mobile'];
			$session_data['email'] = $row['email'];
			$session_data['wechat'] = $row['wechat'];
			$session_data['bank_id'] = $row['bank_id'];
			$session_data['bank_account_name'] = $row['bank_account_name'];
			$session_data['bank_account_no'] = $row['bank_account_no'];
			$session_data['referral_code'] = $row['referral_code'];
			$session_data['active'] = $row['active'];
			$session_data['last_login_date'] = time();
			$session_data['login_token'] = session_id();
			$session_data['is_logged_in'] = FALSE;
			$session_data['is_player_change_password'] = $row['is_player_change_password'];
			$session_data['is_offline_deposit'] = $row['is_offline_deposit'];
			$session_data['is_online_deposit'] = $row['is_online_deposit'];
			$session_data['is_credit_card_deposit'] = $row['is_credit_card_deposit'];
			$session_data['is_hypermart_deposit'] = $row['is_hypermart_deposit'];
			
			if(password_verify($data['password'], $row['password'])) 
			{
				$session_data['is_logged_in'] = TRUE;
			}
			
			$result = $session_data;
		}

		$query->free_result();
		
		return $result;
	}
	
	public function verify_login_force($data = NULL)
	{
		$result = NULL;
		
		$query = $this
                ->db
				->select('player_id, avatar, nickname, mobile, email, wechat, bank_id, bank_account_name, bank_account_no, referral_code, username, password, active, player_type, is_promotion, is_player_bank_account, is_fingerprint, is_level, is_player_change_password, is_offline_deposit, is_online_deposit, is_credit_card_deposit, is_hypermart_deposit')
				->where('username', $data['username'])
				->limit(1)
                ->get('players');
				
		if($query->num_rows() > 0) 
		{
			$row = $query->row_array();
			
			$session_data = array();
			$session_data['player_id'] = $row['player_id'];
			$session_data['avatar'] = str_pad($row['avatar'], 2, "0", STR_PAD_LEFT);
			$session_data['nickname'] = $row['nickname'];
			$session_data['username'] = $row['username'];
			$session_data['player_type'] = $row['player_type'];
			$session_data['is_promotion'] = $row['is_promotion'];
			$session_data['is_player_bank_account'] = $row['is_player_bank_account'];
			$session_data['is_fingerprint'] = $row['is_fingerprint'];
			$session_data['is_level'] = $row['is_level'];
			$session_data['mobile'] = $row['mobile'];
			$session_data['email'] = $row['email'];
			$session_data['wechat'] = $row['wechat'];
			$session_data['bank_id'] = $row['bank_id'];
			$session_data['bank_account_name'] = $row['bank_account_name'];
			$session_data['bank_account_no'] = $row['bank_account_no'];
			$session_data['referral_code'] = $row['referral_code'];
			$session_data['active'] = $row['active'];
			$session_data['last_login_date'] = time();
			$session_data['login_token'] = session_id();
			$session_data['is_logged_in'] = TRUE;
			$session_data['is_player_change_password'] = $row['is_player_change_password'];
			$session_data['is_offline_deposit'] = $row['is_offline_deposit'];
			$session_data['is_online_deposit'] = $row['is_online_deposit'];
			$session_data['is_credit_card_deposit'] = $row['is_credit_card_deposit'];
			$session_data['is_hypermart_deposit'] = $row['is_hypermart_deposit'];
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
		
		$this->db->where('player_id', $data['player_id']);
		$this->db->limit(1);
		$this->db->update('players', $DBdata);
	}
	
	public function insert_login_report($data = NULL, $device = NULL, $status = NULL)
	{
		$DBdata = array(
			'username' => $data['username'],
			'user_group_type' => USER_GROUP_PLAYER,
			'ip_address' => $this->input->ip_address(),
			'status' => $status,
			'report_date' => $data['last_login_date']
		);
		
		if( ! empty($device))
		{
			$DBdata['user_agent'] = $this->agent->mobile() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
			$DBdata['platform'] = $device;
		}
		else if($this->agent->is_mobile()) 
		{
			$DBdata['user_agent'] = $this->agent->mobile() . ' ' . $this->agent->browser() . ' ' . $this->agent->version();
			$DBdata['platform'] = PLATFORM_MOBILE_WEB;
		}
		else 
		{
			$DBdata['user_agent'] = $this->agent->browser() . ' ' . $this->agent->version();
			$DBdata['platform'] = PLATFORM_WEB;
		}
		
		$this->db->insert('login_report', $DBdata);
	}
	
	public function get_player_data($username = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('username', $username)
				->where('active', STATUS_ACTIVE)
				->limit(1)
				->get('players');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function get_player_data_by_email($email = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('email', $email)
				->where('active', STATUS_ACTIVE)
				->limit(1)
				->get('players');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function clear_login_token($username = NULL)
	{
		$this->db->set('login_token', '');
		$this->db->where('username', $username);
		$this->db->limit(1);
		$this->db->update('players');
	}

	public function get_player_game_account_data_list($player_id = NULL)
	{
		$result = NULL;
		
		$query = $this
				->db
				->where('player_id', $player_id)
				->get('player_game_accounts');
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function get_player_game_account_data($provider_code = NULL, $player_id = NULL)
	{
		$result = NULL;
		
		$query = $this
				->db
				->where('game_provider_code', $provider_code)
				->where('player_id', $player_id)
				->limit(1)
				->get('player_game_accounts');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function add_player_game_account($provider_code = NULL, $player_id = NULL, $username = NULL, $password = NULL,$game_id = NULL)
	{
		$DBdata = array(
			'game_provider_code' => $provider_code,
			'username' => $username,
			'password' => $password,
			'player_id' => $player_id,
			'game_id' => $game_id,
		);
		
		$this->db->insert('player_game_accounts', $DBdata);
	}
	
	public function update_player_last_in_game($provider_code = NULL, $player_id = NULL)
	{
		$DBdata = array(
			'last_in_game' => $provider_code
		);
		
		$this->db->where('player_id', $player_id);
		$this->db->limit(1);
		$this->db->update('players', $DBdata);
	}
	
	public function verify_session()
	{
		$result = FALSE;
		
		$query = $this
				->db
				->select('login_token')
				->where('player_id', $this->session->userdata('player_id'))
				->where('login_token', $this->session->userdata('login_token'))
				->limit(1)
				->get('players');
				
		if($query->num_rows() > 0) 
		{
			$result = TRUE;
		}

		$query->free_result();
		
		return $result;
	}
	
	public function update_player($data = NULL)
	{	
		$DBdata = array(
			'nickname' => $data['nickname'],
			'mobile' => $data['mobile'],
			'email' => $data['email'],
			'wechat' => $data['wechat'],
			'bank_id' => $data['bank_id'],
			'bank_account_name' => $data['bank_account_name'],
			'bank_account_no' => $data['bank_account_no'],
			'updated_by' => $data['username'],
			'updated_date' => time()
		);
		
		$this->db->where('username', $data['username']);
		$this->db->limit(1);
		$this->db->update('players', $DBdata);
	}
	
	public function verify_current_password($data = NULL)
	{
		$result = FALSE;
		
		$query = $this
				->db
				->select('password')
				->where('username', $data['username'])
				->limit(1)
				->get('players');
				
		if($query->num_rows() > 0) 
		{
			$row = $query->row_array();
			
			if(password_verify($data['oldpass'], $row['password'])) 
			{
				$result = TRUE;
			}
		}

		$query->free_result();
		
		return $result;
	}
	
	public function update_player_password($data = NULL)
	{	
		$new_password = $data['password'];
		$new_password = password_hash($new_password, PASSWORD_DEFAULT);
		
		$DBdata = array(
			'password' => $new_password,
			'is_player_change_password' => STATUS_ACTIVE,
			'updated_by' => $data['username'],
			'updated_date' => time()
		);
		
		$this->db->where('username', $data['username']);
		$this->db->limit(1);
		$this->db->update('players', $DBdata);
	}

	public function get_player_data_by_referral_code($referral_code = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('username,upline')
				->where('referral_code', $referral_code)
				->limit(1)
				->get('players');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function update_player_online_time($player_id = NULL)
	{	
		$DBdata = array(
			'last_online_date' => time()
		);
		
		$this->db->where('player_id', $player_id);
		$this->db->limit(1);
		$this->db->update('players', $DBdata);
	}

	public function update_player_wallet_lock($username = NULL,$wallet_status = NULL){
		$DBdata = array(
			'wallet_lock' => $wallet_status,
		);
		
		$this->db->where('username', $username);
		$this->db->limit(1);
		$this->db->update('players', $DBdata);
	}

	public function add_player_bank($data = null){
		$DBdata = array(
			'username' => $data['username'],
			'bank_id' => $data['bank_id'],
			'bank_account_name' => $data['bank_account_name'],
			'bank_account_no' => $data['bank_account_no'],
			'bank_account_address' => $data['bank_account_address'],
			'player_id' => $data['player_id'],
			'active' => STATUS_ACTIVE,
			'created_by' => $data['username'],
			'created_date' => time()
		);
		$this->db->insert('player_bank', $DBdata);
		$DBdata['player_bank_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function update_player_bank($data = null,$id = null){
		$DBdata = array(
			'bank_id' => $data['bank_id'],
			'bank_account_name' => $data['bank_account_name'],
			'bank_account_no' => $data['bank_account_no'],
			'bank_account_address' => $data['bank_account_address'],
			'updated_by' => $data['username'],
			'updated_date' => time()
		);

		$this->db->where('player_bank_id', $id);
		$this->db->limit(1);
		$this->db->update('player_bank', $DBdata);
		$DBdata['player_bank_id'] = $id;
		$DBdata['username'] = $data['username'];
		$DBdata['player_id'] = $data['player_id'];
		return $DBdata;
	}

	public function get_player_wallet_lock($username = NULL)
	{	
		$result = NULL;
		$query = $this
				->db
				->select('wallet_lock')
				->where('username', $username)
				->limit(1)
				->get('players');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function get_player_data_by_player_id($player_id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('player_id', $player_id)
				->limit(1)
				->get('players');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function get_player_currently_turnover($data = NULL, $package = NULL){
	    $from_time = strtotime(date('Y-m-d 00:00:00'),time());
	    $to_time = strtotime(date('Y-m-d 23:59:59'),time());
	    $provider_set_dg_sa_sx = array("SA","DG","SX");
	    $provider_set_sa_sx = array("SA","SX");
	    $provider_set_dg_sa = array("SA","DG");
	    $provider_set_sa = array("SA");
	    $provider_set_dg = array("DG");
	    $provider_set = array(); 
	    $result = null;
	    $turnover = 0;
	    switch($game_provider_code)
	    {
	        case 25200: $provider_set = $provider_set_sa_sx;break;
	        case 12600: $provider_set = $provider_set_dg_sa_sx;break;
	        case 6300: $provider_set = $provider_set_dg_sa_sx;break;
	        case 3150: $provider_set = $provider_set_dg_sa_sx;break;
	        case 630: $provider_set = $provider_set_sa_sx;break;
	        default: $provider_set = $provider_set_dg_sa_sx;break;   
	    }
	    
	    $this->db->select_sum('bet_amount_valid');
	    $this->db->where('player_id',$data['player_id']);
	    $this->db->where_in('game_provider_code', $provider_set);
	    $this->db->where('game_type_code',"LC");
	    $this->db->where('game_code',"Baccarat");
	    $this->db->where('win_loss != ',0);
	    $this->db->where('payout_time >= ',$from_time);
	    $this->db->where('payout_time <= ',$to_time);
	    $query = $this->db->get('transaction_report');
	    if($query->num_rows() > 0)
		{
		    $result = $query->row_array();
		    $turnover = bcdiv($result['bet_amount_valid'],1,2);
		}
	    return $turnover;
	}
}