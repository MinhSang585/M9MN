<?php
class Bank_model extends CI_Model {

	protected $table_banks = 'banks';
	protected $table_bank_accounts = 'bank_accounts';
	protected $table_bank_player = 'player_bank';
	protected $table_player_bank_image = 'player_bank_image';
	protected $table_kyc = 'kyc';
	
	public function get_bank_data($id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('bank_id', $id)
				->limit(1)
				->get($this->table_banks);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function add_bank()
	{	
		$DBdata = array(
			'bank_name' => $this->input->post('bank_name', TRUE),
			'bank_code' => $this->input->post('bank_code', TRUE),
			'bank_type' => $this->input->post('currency_id', TRUE) == 4 ? BANK_TYPE_CRYTO : BANK_TYPE_CASH,
			'currency_id' => $this->input->post('currency_id', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);

		if(isset($_FILES['web_image_on']['size']) && $_FILES['web_image_on']['size'] > 0)
		{
			$DBdata['web_image_on'] = $_FILES['web_image_on']['name'];
		}
		
		if(isset($_FILES['web_image_off']['size']) && $_FILES['web_image_off']['size'] > 0)
		{
			$DBdata['web_image_off'] = $_FILES['web_image_off']['name'];
		}

		if(isset($_FILES['mobile_image_on']['size']) && $_FILES['mobile_image_on']['size'] > 0)
		{
			$DBdata['mobile_image_on'] = $_FILES['mobile_image_on']['name'];
		}
		
		if(isset($_FILES['mobile_image_off']['size']) && $_FILES['mobile_image_off']['size'] > 0)
		{
			$DBdata['mobile_image_off'] = $_FILES['mobile_image_off']['name'];
		}

		$this->db->insert($this->table_banks, $DBdata);
		
		$DBdata['bank_id'] = $this->db->insert_id();
		
		return $DBdata;
	}
	
	public function update_bank($id = NULL)
	{	
		$DBdata = array(
			'bank_name' => $this->input->post('bank_name', TRUE),
			'bank_code' => $this->input->post('bank_code', TRUE),
			'bank_type' => $this->input->post('currency_id', TRUE) == 4 ? BANK_TYPE_CRYTO : BANK_TYPE_CASH,
			'currency_id' => $this->input->post('currency_id', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		if(isset($_FILES['web_image_on']['size']) && $_FILES['web_image_on']['size'] > 0)
		{
			$DBdata['web_image_on'] = $_FILES['web_image_on']['name'];
		}
		
		if(isset($_FILES['web_image_off']['size']) && $_FILES['web_image_off']['size'] > 0)
		{
			$DBdata['web_image_off'] = $_FILES['web_image_off']['name'];
		}

		if(isset($_FILES['mobile_image_on']['size']) && $_FILES['mobile_image_on']['size'] > 0)
		{
			$DBdata['mobile_image_on'] = $_FILES['mobile_image_on']['name'];
		}
		
		if(isset($_FILES['mobile_image_off']['size']) && $_FILES['mobile_image_off']['size'] > 0)
		{
			$DBdata['mobile_image_off'] = $_FILES['mobile_image_off']['name'];
		}
		$this->db->where('bank_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_banks, $DBdata);
		
		$DBdata['bank_id'] = $id;
		
		return $DBdata;
	}
	
	public function delete_bank($id = NULL)
	{	
		$this->db->where('bank_id', $id);
		$this->db->limit(1);
		$this->db->delete($this->table_banks);
	}
	
	public function get_bank_list()
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('bank_id, bank_name')
				->where('active', STATUS_ACTIVE)
				->get($this->table_banks);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
		}
		
		$query->free_result();
		
		return $result;
	}

	public function getBankListById($playerId)
	{	
		$result = NULL;
    
		$banksTable = $this->table_banks;
		$playerBankTable = $this->table_bank_player;
	
		$query = $this->db
			->select('pb.player_id, pb.bank_account_name, pb.bank_account_no, b.bank_name, b.bank_id')
			->from("$playerBankTable pb")
			->join("$banksTable b", 'pb.bank_id = b.bank_id', 'left')
			->where('pb.player_id', $playerId)
			->where('pb.active', STATUS_ACTIVE)
			->where('pb.verify', STATUS_ACTIVE)
			->get();
	
		if ($query->num_rows() > 0) {
			$result = $query->result_array();
		}
	
		$query->free_result();
	
		return $result;
	}
	
	public function getBanktByAccountNo($playerId, $accountNo = null)
	{	
		// add
		$result = NULL;
    
		$banksTable = $this->table_banks;
		$playerBankTable = $this->table_bank_player;
	
		$query = $this->db
			->select('pb.player_id, pb.bank_account_name, pb.bank_account_no, b.bank_name, b.bank_id')
			->from("$playerBankTable pb")
			->join("$banksTable b", 'pb.bank_id = b.bank_id', 'left')
			->where('pb.player_id', $playerId)
			->where('pb.bank_account_no', $accountNo)
			->where('pb.active', STATUS_ACTIVE)
			->where('pb.verify', STATUS_ACTIVE)
			->limit(1)
			->get();
	
		if ($query->num_rows() > 0) {
			$result = $query->row_array();
		}
	
		$query->free_result();
	
		return $result;
	}
	
	public function get_bank_name()
	{	
		$result = NULL;
		
		$query = $this
				->db
				->select('bank_id, bank_name')
				->where('active', STATUS_ACTIVE)
				->get($this->table_banks);
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$result[$row->bank_id] = $row->bank_name;
			}	
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function getBankImageData($id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('player_bank_image_id', $id)
				->limit(1)
				->get($this->table_player_bank_image);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function get_bank_account_data($id = NULL)
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('bank_account_id', $id)
				->limit(1)
				->get($this->table_bank_accounts);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function add_bank_account($qrImagePath)
	{	
		$DBdata = array(
			'bank_account_name' => $this->input->post('bank_account_name', TRUE),
			'bank_account_no' => $this->input->post('bank_account_no', TRUE),
			'daily_limit' => $this->input->post('daily_limit', TRUE),
			'bank_id' => $this->input->post('bank_id', TRUE),
			'group_ids' => (($this->input->post('group_ids[]', TRUE)) ? ','.implode(',', $this->input->post('group_ids[]', TRUE)).',' : ''),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'qr_image' => $qrImagePath,
			'payment_type' => $this->input->post('payment_type', TRUE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);
		
		
		
		$this->db->insert($this->table_bank_accounts, $DBdata);
		
		$DBdata['bank_account_id'] = $this->db->insert_id();
		
		return $DBdata;
	}
	
	public function update_bank_account($id = NULL, $qrImagePath)
	{
		$paymentType = $this->input->post('payment_type', TRUE);

		if ($paymentType == 0) {
			$qrImagePath = '';
		}
		
		$DBdata = array(
			'bank_account_name' => $this->input->post('bank_account_name', TRUE),
			'bank_account_no' => $this->input->post('bank_account_no', TRUE),
			'bank_reference' => $this->input->post('bank_reference', TRUE),
			'daily_limit' => $this->input->post('daily_limit', TRUE),
			'bank_id' => $this->input->post('bank_id', TRUE),
			'group_ids' => (($this->input->post('group_ids[]', TRUE)) ? ','.implode(',', $this->input->post('group_ids[]', TRUE)).',' : ''),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'qr_image' => $qrImagePath,
			'payment_type' => $paymentType,
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		
		$this->db->where('bank_account_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_bank_accounts, $DBdata);
		
		$DBdata['bank_account_id'] = $id;
		
		return $DBdata;
	}
	
	public function delete_bank_account($id = NULL)
	{	
		$this->db->where('bank_account_id', $id);
		$this->db->limit(1);
		$this->db->delete($this->table_bank_accounts);
	}
	
	public function delete_bank_account_with_bank($id = NULL)
	{	
		$this->db->where('bank_id', $id);
		$this->db->delete($this->table_bank_accounts);
	}

	public function add_player_bank_account($playerData = NULL){
		$DBdata = array(
			'player_id' => $playerData['player_id'],
			'username' => $this->input->post('username', TRUE),
			'player_bank_type' => $this->input->post('player_bank_type', TRUE),
			'bank_id' => $this->input->post('bank_id', TRUE),
			'bank_account_name' => $this->input->post('bank_account_name', TRUE),
			'bank_account_no' => $this->input->post('bank_account_no', TRUE),
			'bank_account_address' => $this->input->post('bank_account_address', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);
		$this->db->insert($this->table_bank_player, $DBdata);
		$DBdata['player_bank_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function update_player_bank_account($id = NULL){
		$DBdata = array(
			'player_bank_type' => $this->input->post('player_bank_type', TRUE),
			'bank_id' => $this->input->post('bank_id', TRUE),
			'bank_account_name' => $this->input->post('bank_account_name', TRUE),
			'bank_account_no' => $this->input->post('bank_account_no', TRUE),
			'bank_account_address' => $this->input->post('bank_account_address', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'verify' => (($this->input->post('verify', TRUE) == STATUS_VERIFY) ? STATUS_VERIFY : STATUS_UNVERIFY),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		$this->db->where('player_bank_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_bank_player, $DBdata);
		$DBdata['player_bank_id'] = $id;
		return $DBdata;
		
	}

	public function get_player_bank_data($id = NULL){
		$result = NULL;
		
		$query = $this
				->db
				->where('player_bank_id', $id)
				->limit(1)
				->get($this->table_bank_player);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
    
    public function delete_player_bank($id = NULL)
	{	
		$this->db->where('player_bank_id', $id);
		$this->db->delete($this->table_bank_player);
	}
	
	public function get_player_bank_account_quantity($playerData = NULL,$type = NULL){
		$result = NULL;
		$this->db->where('username', $playerData['username']);
		$this->db->where('player_bank_type', $type);
		$result = $this->db->count_all_results($this->table_bank_player);
		return $result;
	}

	public function update_bank_group($id = NULL){
		$dbprefix = $this->db->dbprefix;
		$bank_accounts_table = $dbprefix.$this->table_bank_accounts;
		$query = $this->db->query("SELECT bank_account_id, group_ids FROM {$bank_accounts_table} WHERE group_ids LIKE '%,{$id},%'");
		if($query->num_rows() > 0)
        {
        	foreach($query->result() as $row)
			{
				$arr = explode(',', $row->group_ids);
				$arr = array_values(array_filter($arr));
				$new_arr = array_values(array_filter(array_diff($arr, array($id))));
				$new_bank_group = "";
				if(!empty($new_arr)){
					$new_bank_group = ','.implode(',', $new_arr).',';
				}
				$this->db->query("UPDATE {$bank_accounts_table} SET group_ids = ? WHERE bank_account_id = ? LIMIT 1", array($new_bank_group, $row->bank_account_id));
			}
        }
	}

	public function get_bank_account_list_specific($bank_ids = NULL){
		$bank_ids_array = array_values(array_filter(explode(',', $bank_ids)));
		$result = array();
		$this->db->select('bank_accounts.bank_account_id,bank_accounts.bank_account_no, banks.bank_name');
		$this->db->where('banks.active', STATUS_ACTIVE);
		$this->db->where('bank_accounts.active', STATUS_ACTIVE);
		if(sizeof($bank_ids_array)>0){
			$this->db->group_start();
			foreach($bank_ids_array as $bank_ids_row){
				$this->db->or_group_start();
					$this->db->like('bank_accounts.group_ids',",".$bank_ids_row.",");
				$this->db->group_end();
			}
			$this->db->group_end();
		}else{
			$this->db->like('bank_accounts.group_ids',",1,");
		}	
		$this->db->join('banks', 'bank_accounts.bank_id = banks.bank_id');
		$query = $this->db->get('bank_accounts');
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row) {
				$result[$row->bank_account_id] = (array) $row;						
			}
		}
		$query->free_result();
		return $result;
	}

	public function get_all_player_id_with_bank_account_name($bank_account_name = NULL){
        $result = NULL;
		
		$query = $this
				->db
				->select('player_id,bank_account_name,bank_account_no,bank_id')
				->where('bank_account_name', $bank_account_name)
				->get($this->table_bank_player);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		
		$query->free_result();

		return $result;
    }

	public function getBankData($bank_id = NULL){
        $result = NULL;
		
		$query = $this
				->db
				->select('bank_account_name,bank_account_no')
				->where('bank_id', $bank_id)
				->get($this->table_bank_player);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();

		return $result;
    }

	public function getBankName($bankId){
        $result = NULL;
		
		$query = $this
				->db
				->select('bank_name')
				->where('bank_id', $bankId)
				->get($this->table_banks);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();

		return $result;
    }

    public function get_all_player_id_with_bank_account_no($bank_account_no = NULL){
        $result = NULL;
		
		$query = $this
				->db
				->select('player_id')
				->where('bank_account_no', $bank_account_no)
				->get($this->table_bank_player);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		
		$query->free_result();
		
		return $result;
    }

    public function get_player_bank_data_by_player_array($player_data = NULL){
		$result = array();
		if(!empty($player_data)){
			$query = $this
				->db
				->select('player_bank_id,bank_id, player_id, bank_account_no, bank_account_name, active, verify, player_bank_type,withdrawal_count')
				->where_in('player_id', $player_data)
				->order_by('player_bank_id',"ASC")
				->get($this->table_bank_player);
		
			if($query->num_rows() > 0)
			{
				$result_query = $query->result_array();
				if(!empty($result_query)){
					foreach($result_query as $result_query_row){
						$result[$result_query_row['player_id']][$result_query_row['player_bank_type']][] = array(
							'player_bank_id' => $result_query_row['player_bank_id'],
							'player_id' => $result_query_row['player_id'],
							'bank_id' => $result_query_row['bank_id'],
							'bank_account_no' => $result_query_row['bank_account_no'],
							'bank_account_name' => $result_query_row['bank_account_name'],
							'active' => $result_query_row['active'],
							'verify' => $result_query_row['verify'],
							'player_bank_type' => $result_query_row['player_bank_type'],
							'withdrawal_count' => $result_query_row['withdrawal_count']
						);		
					}
				}
			}
		
			$query->free_result();
		}
		return $result;
	}

	public function get_player_bank_data_by_player_id($player_id = NULL){
		$result = array();
		$query = $this
			->db
			->where('player_id', $player_id)
			->get($this->table_bank_player);
	
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}
	
		$query->free_result();
		return $result;
	}

	public function get_all_bank_name()
	{	
		$result = array();
		$query = $this
				->db
				->select('bank_id, bank_name')
				->get($this->table_banks);
		
		if($query->num_rows() > 0)
		{
			$result_query = $query->result_array();
			if(!empty($result_query)){
				foreach($result_query as $result_query_row){
					$result[$result_query_row['bank_id']] = $result_query_row['bank_name'];
				}
			}
		}
		$query->free_result();
		return $result;
	}

	public function get_player_bank_image_data($id = NULL){
		$result = NULL;
		
		$query = $this
				->db
				->where('player_bank_image_id', $id)
				->limit(1)
				->get($this->table_player_bank_image);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function update_player_bank_image($id = NULL){
		$DBdata = array(
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_PENDING),
			'verify' => (($this->input->post('verify', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_PENDING),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		$this->db->where('player_bank_image_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_player_bank_image, $DBdata);
		$DBdata['player_bank_image_id'] = $id;
		return $DBdata;
	}

	public function get_all_bank_name_by_bank_id($bank_data = NULL)
	{	
		$result = array();
		$query = $this
				->db
				->select('bank_id, bank_name')
				->where_in('bank_id', $bank_data)
				->get($this->table_banks);
		
		if($query->num_rows() > 0)
		{
			$result_query = $query->result_array();
			if(!empty($result_query)){
				foreach($result_query as $result_query_row){
					$result[$result_query_row['bank_id']] = $result_query_row['bank_name'];
				}
			}
		}
		$query->free_result();
		return $result;
	}

	public function update_player_bank_account_verify($id = NULL){
		$DBdata = array(
			'verify' => (($this->input->post('verify', TRUE) == STATUS_VERIFY) ? STATUS_VERIFY : STATUS_UNVERIFY),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		$this->db->where('player_bank_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_bank_player, $DBdata);
		$DBdata['player_bank_id'] = $id;
		return $DBdata;
		
	}

	public function update_bank_withdrawal_count($arr = NULL){
		$DBdata = array(
			'player_id' => $arr['player_id'],
			'bank_id' => $arr['bank_id'],
			'bank_account_name' => $arr['bank_account_name'],
			'bank_account_no' => $arr['bank_account_no'],
		);
		$table = $this->db->dbprefix . $this->table_bank_player;
		$this->db->query("UPDATE {$table} SET withdrawal_count = (withdrawal_count + ?) WHERE player_id = ? AND bank_id = ? AND bank_account_name = ? AND bank_account_no = ? LIMIT 1", array(1, $DBdata['player_id'], $DBdata['bank_id'], $DBdata['bank_account_name'], $DBdata['bank_account_no']));
		return $DBdata;
	}

	public function update_player_bank_account_withdrawal_count($arr = NULL){
		$DBdata = array(
			'withdrawal_count' => $this->input->post('withdrawal_count', TRUE),
		);
		$this->db->where('player_bank_id', $arr['player_bank_id']);
		$this->db->limit(1);
		$this->db->update($this->table_bank_player, $DBdata);
		$DBdata['player_bank_id'] = $arr['player_bank_id'];
		return $DBdata;
	}
	
	public function getOfflineDeposit($fromDate,$toDate){

		$sql = "SELECT SUM(amount_apply) as total, bank_name FROM {$this->db->dbprefix}deposits
				WHERE bank_name IS NOT NULL AND deposit_type = 1 AND created_date > " . strtotime($fromDate) . " AND created_date < " . strtotime($toDate) . " 
				GROUP BY bank_name";
		$query = $this->db->query($sql);
		$depositOffline = array();	
		if($query->num_rows() > 0)
		{
			$depositOffline = $query->result_array();
		}
		$query->free_result();
		return $depositOffline;
	}

	public function getOfflineWithdraw($fromDate,$toDate){

		$sql = "SELECT SUM(amount) as total, bank_name FROM {$this->db->dbprefix}withdrawals
				WHERE bank_name IS NOT NULL AND withdrawal_type = 1 AND created_date > " . strtotime($fromDate) . " AND created_date < " . strtotime($toDate) . " 
				GROUP BY bank_name";
		$query = $this->db->query($sql);
		$withdrawOffline = array();	
		if($query->num_rows() > 0)
		{
			$withdrawOffline = $query->result_array();
		}
		$query->free_result();
		return $withdrawOffline;
	}
	
	// add KYC data

	public function addKYC($playerData = NULL){
		$DBdata = array(
			'bank_id' => $this->input->post('bank_id', TRUE),
			'player_id' => $playerData['player_id'],
			'number_account' => $this->input->post('bank_code', TRUE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);

		if(isset($_FILES['front_image']['size']) && $_FILES['front_image']['size'] > 0)
		{
			$DBdata['front_image'] = $_FILES['front_image']['name'];
		}

		if(isset($_FILES['back_image']['size']) && $_FILES['back_image']['size'] > 0)
		{
			$DBdata['back_image'] = $_FILES['back_image']['name'];
		}
		
		if(isset($_FILES['bank_statement_image']['size']) && $_FILES['bank_statement_image']['size'] > 0)
		{
			$DBdata['bank_statement_image'] = $_FILES['bank_statement_image']['name'];
		}

		$this->db->insert($this->table_kyc, $DBdata);
		return $DBdata;
	}

	// get KYC data by id

	
	
	public function getKYCData($id = NULL){
		$result = NULL;
		
		$query = $this
				->db
				->where('kyc_id', $id)
				->limit(1)
				->get($this->table_kyc);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	// get KYC data by id
	public function updateKyc($id = NULL)
	{	
		$DBdata = array(
			'status' => (($this->input->post('status', TRUE) == STATUS_APPROVE) ? STATUS_APPROVE : STATUS_CANCEL),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		$this->db->where('status', STATUS_PENDING);
		$this->db->where('kyc_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_kyc, $DBdata);
		
		$DBdata['kyc_id'] = $id;
		
		return $DBdata;
	}

	public function deleteKyc($id = NULL)
	{	
		$this->db->where('kyc_id', $id);
		$this->db->limit(1);
		$this->db->delete($this->table_kyc);
	}
}
