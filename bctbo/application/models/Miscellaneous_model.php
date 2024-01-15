<?php
class Miscellaneous_model extends CI_Model {

	protected $table = 'miscellaneous';
	protected $miscellaneous_lang_table = 'miscellaneous_lang';
	
	public function get_miscellaneous()
	{	
		$result = NULL;
		
		$query = $this
				->db
				->where('miscellaneous_id', 1)
				->limit(1)
				->get($this->table);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}
	
	public function update_miscellaneous()
	{	
		$miscellaneous_id = 1;


		if($this->input->post('risk_announcement_rate[]', TRUE)){
			$risk_announcement_data = $this->input->post('risk_announcement_rate[]', TRUE);
			asort($risk_announcement_data);
			$risk_announcement_rate = ','.implode(',', $risk_announcement_data).',';
		}else{
			$risk_announcement_rate = '';
		}
		
		$DBdata = array(
			'min_deposit' => $this->input->post('min_deposit', TRUE),
			'max_deposit' => $this->input->post('max_deposit', TRUE),
			'min_withdrawal' => $this->input->post('min_withdrawal', TRUE),
			'max_withdrawal' => $this->input->post('max_withdrawal', TRUE),
			'max_count_withdrawal' => $this->input->post('max_count_withdrawal', TRUE),
			'win_loss_suspend_limit' => $this->input->post('win_loss_suspend_limit', TRUE),
			'fingerprint_status' => (($this->input->post('fingerprint_status', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'player_bank_account' => (($this->input->post('player_bank_account', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'player_level' => (($this->input->post('player_level', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_deposit_sound' => (($this->input->post('is_deposit_sound', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_withdrawal_sound' => (($this->input->post('is_withdrawal_sound', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'risk_management' => (($this->input->post('risk_management', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'system_type' =>  $this->input->post('system_type', TRUE),
			'player_bank_account_max' =>  $this->input->post('player_bank_account_max', TRUE),
			'player_credit_card_max' =>  $this->input->post('player_credit_card_max', TRUE),
			'is_player_change_password' =>  $this->input->post('is_player_change_password', TRUE),
			'player_change_password_type' =>  $this->input->post('player_change_password_type', TRUE),
			'system_email' => (($this->input->post('system_email[]', TRUE)) ? ','.implode(',', $this->input->post('system_email[]', TRUE)).',': ''),
			'risk_announcement_rate' => $risk_announcement_rate,
			'risk_period' =>  $this->input->post('risk_period', TRUE),
			'is_risk_sound' => (($this->input->post('is_risk_sound', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_online_deposit_sound' => (($this->input->post('is_online_deposit_sound', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_credit_card_deposit_sound' => (($this->input->post('is_credit_card_deposit_sound', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_hypermart_deposit_sound' => (($this->input->post('is_hypermart_deposit_sound', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_risk_frozen_sound' => (($this->input->post('is_risk_frozen_sound', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_deposit_notice' => (($this->input->post('is_deposit_notice', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_online_deposit_notice' => (($this->input->post('is_online_deposit_notice', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_credit_card_deposit_notice' => (($this->input->post('is_credit_card_deposit_notice', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_hypermart_deposit_notice' => (($this->input->post('is_hypermart_deposit_notice', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_withdrawal_notice' => (($this->input->post('is_withdrawal_notice', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_blacklist_sound' => (($this->input->post('is_blacklist_sound', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'is_player_bank_image_sound' => (($this->input->post('is_player_bank_image_sound', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
		);
		
		if(isset($_FILES['deposit_sound']['size']) && $_FILES['deposit_sound']['size'] > 0)
		{
			$DBdata['deposit_sound'] = $_FILES['deposit_sound']['name'];
		}

		if(isset($_FILES['online_deposit_sound']['size']) && $_FILES['online_deposit_sound']['size'] > 0)
		{
			$DBdata['online_deposit_sound'] = $_FILES['online_deposit_sound']['name'];
		}

		if(isset($_FILES['credit_card_deposit_sound']['size']) && $_FILES['credit_card_deposit_sound']['size'] > 0)
		{
			$DBdata['credit_card_deposit_sound'] = $_FILES['credit_card_deposit_sound']['name'];
		}

		if(isset($_FILES['hypermart_deposit_sound']['size']) && $_FILES['hypermart_deposit_sound']['size'] > 0)
		{
			$DBdata['hypermart_deposit_sound'] = $_FILES['hypermart_deposit_sound']['name'];
		}
		
		if(isset($_FILES['withdrawal_sound']['size']) && $_FILES['withdrawal_sound']['size'] > 0)
		{
			$DBdata['withdrawal_sound'] = $_FILES['withdrawal_sound']['name'];
		}
		if(isset($_FILES['risk_sound']['size']) && $_FILES['risk_sound']['size'] > 0)
		{
			$DBdata['risk_sound'] = $_FILES['risk_sound']['name'];
		}
		if(isset($_FILES['risk_frozen_sound']['size']) && $_FILES['risk_frozen_sound']['size'] > 0)
		{
			$DBdata['risk_frozen_sound'] = $_FILES['risk_frozen_sound']['name'];
		}
		if(isset($_FILES['blacklist_sound']['size']) && $_FILES['blacklist_sound']['size'] > 0)
		{
			$DBdata['blacklist_sound'] = $_FILES['blacklist_sound']['name'];
		}
		if(isset($_FILES['player_bank_image_sound']['size']) && $_FILES['player_bank_image_sound']['size'] > 0)
		{
			$DBdata['player_bank_image_sound'] = $_FILES['player_bank_image_sound']['name'];
		}
		$this->db->where('miscellaneous_id', $miscellaneous_id);
		$this->db->limit(1);
		$this->db->update($this->table, $DBdata);
		$DBdata['miscellaneous_id'] = $miscellaneous_id;
		return $DBdata;
	}

	public function get_miscellaneous_lang_data($type = NULL){
		$result = NULL;

		$query = $this
				->db
				->where('miscellaneous_type', $type)
				->get($this->miscellaneous_lang_table);

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

	public function update_miscellaneous_content($type=NULL, $language_id = NULL){
		$DBdata = array(
			'miscellaneous_title' => $this->input->post('miscellaneous_title_'.$type.'_'.$language_id, TRUE),
			'miscellaneous_content' => html_entity_decode($_POST['miscellaneous_content_'.$type.'_'.$language_id]),
		);

		$this->db->where('miscellaneous_type', $type);
		$this->db->where('language_id', $language_id);
		$this->db->limit(1);
		$this->db->update($this->miscellaneous_lang_table, $DBdata);
		$DBdata['miscellaneous_type'] = $type;
		$DBdata['language_id'] = $language_id;
		return $DBdata;
	}

	public function add_miscellaneous_content($type=NULL, $language_id = NULL){
		$DBdata = array(
			'miscellaneous_title' => $this->input->post('miscellaneous_title_'.$type.'_'.$language_id, TRUE),
			'miscellaneous_content' => html_entity_decode($_POST['miscellaneous_content_'.$type.'_'.$language_id]),
			'miscellaneous_type' => $type,
			'language_id' => $language_id,
		);
		$this->db->insert($this->miscellaneous_lang_table, $DBdata);
		return $DBdata;
	}
}