<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testing extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('bank_model', 'game_model', 'group_model', 'miscellaneous_model', 'player_model', 'avatar_model','level_model','deposit_model','withdrawal_model','promotion_model','bonus_model','general_model'));
	
	}
	
	public function pull_balance_by_game_type(){
	    $provider_code = 'SPSB';
	    $game_code = 'SPSB';
	    $url = SYSTEM_API_URL;
	    $player_data =  NULL;
	    $player_list = NULL;
	    $player_list_ids = NULL;
	    $last_player_id = 1000000000;
	    
	    $query = $this
				->db
				->select('player_id,game_id,password')
				->where('game_provider_code', $provider_code)
				->order_by('player_id','ASC')
				->get('player_game_accounts');
		if($query->num_rows() > 0)
		{
			$player_data = $query->result_array();  
		}
		
		if(! empty($player_data))
		{
		    foreach($player_data as $player_data_row){
		        $player_list[$player_data_row['player_id']] = array(
    		        'player_id' => $player_data_row['player_id'],
    		        'game_id' => $player_data_row['game_id'],
    		        'password' => $player_data_row['password'],
    		    );
		    }
		    
		    
		    
    	    $dbprefix = $this->db->dbprefix;
    	    $player_query = $this->db->query("SELECT player_id FROM {$dbprefix}players ORDER BY player_id DESC LIMIT 1");
        	if($player_query->num_rows() > 0) {
        		$player_row = $player_query->row();
        		$last_player_id = $player_row->player_id;
        	}
        	$player_query->free_result();
        
        	$player_query = $this->db->query("SELECT player_id, username,points FROM {$dbprefix}players WHERE player_id <= ? ORDER BY player_id ASC", array($last_player_id));
        	if($player_query->num_rows() > 0) {
    			foreach($player_query->result() as $player_row){
    			    if(isset($player_list[$player_row->player_id])){
    			        $player_list[$player_row->player_id]['username'] = $player_row->username;
    			        $player_list[$player_row->player_id]['points'] = $player_row->points;
    			    }
    			}
    		}
    		
    		foreach($player_list as $account_data){
    		    ad("Player ID : ".$account_data['player_id']);
    		    $player_data = $account_data;
    		    $param_array = array(
					"method" => 'GetBalance',
					"agent_id" => SYSTEM_API_AGENT_ID,
					"syslang" => LANG_EN,
					"device" => PLATFORM_WEB,
					"provider_code" => $provider_code,
				);
				
				
				$param_array['player_id'] = $account_data['player_id'];
    			$param_array['game_id'] = $account_data['game_id'];
    			$param_array['username'] = $account_data['username'];
    			$param_array['password'] = $account_data['password'];
    			$param_array['signature'] = md5(SYSTEM_API_AGENT_ID . $provider_code . $param_array['username'] . SYSTEM_API_SECRET_KEY);
    			$response = $this->curl_json($url, $param_array);
    			$result_array = json_decode($response, TRUE);
    			ad($result_array);
				if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
				{
					$balance = $result_array['result'];
				}
				/*
				if($balance > 0)
				{
				    $points = $balance;
					//Withdraw credit
					$param_array['method'] = 'ChangeBalance';
					$param_array['order_id'] = 'OUT' . date("YmdHis") . $account_data['username'];
					$param_array['amount'] = ($points * -1);
					$response = $this->curl_json($url, $param_array);
					$result_array = json_decode($response, TRUE);


					$array = array(
						'created_date' => time(),
						'from' => $game_code,
						'to' => "MAIN",
						'errorCode' => (isset($result_array['errorCode']) ? $result_array['errorCode'] : "88888888"),
					);
					$this->general_model->insert_cash_transfer_report($player_data, $points, TRANSFER_TRANSACTION_IN,$array);

					if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
					{
						//update wallet
						$newData = $this->player_model->point_transfer($player_data, $points, $player_data['username']);
						$this->general_model->insert_game_transfer_report($game_code, 'MAIN', $balance, $player_data['points'], $points, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
					}else{
						if(isset($result_array['errorCode']) && $result_array['errorCode'] == '201'){
							$newData = $this->general_model->insert_game_transfer_pending_report($game_code, 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $points, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
						}else if(isset($result_array['errorCode'])){
						}else{
						    $newData = $this->general_model->insert_game_transfer_pending_report($game_code, 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $points, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
						}
					}
				}
				*/
    		}
		}
	}
	
	
	public function pull_all_balance(){
	    $provider_code = 'SX';
	    $game_data = NULL;
    	$player_data =  NULL;
    	$query = $this
    			->db
    			->select('api_data')
    			->where('game_code', $provider_code)
    			->limit(1)
    			->get('games');
	    if($query->num_rows() > 0)
		{
			$game_data = $query->row_array();  
		}
		$query = $this
				->db
				->select('player_id')
				->where('game_provider_code', $provider_code)
				->order_by('player_id','ASC')
				->get('player_game_accounts');
		
		if($query->num_rows() > 0)
		{
			$player_data = $query->result_array();  
		}
		
		if( ! empty($game_data) && ! empty($player_data))
		{
		    foreach($player_data as $player_data_row){
		        $this->pull_balance($player_data_row['player_id'],$provider_code);
		    }
		}
	}
	
	public function pull_balance($id = NULL, $game_code = NULL){
		//Initial output data
		$device = PLATFORM_WEB;
		$json = array(
					'status' => EXIT_ERROR, 
					'balance' => '0.00',
				);
					
		$player_data = $this->player_model->get_player_data($id);
		if( ! empty($player_data))
		{
	        
				$sys_data = $this->miscellaneous_model->get_miscellaneous();
				
				$url = str_replace(array('bo.', 'bo/'), array('www.', ''), site_url('gameapi/api')); 
				
				$param_array = array(
					"method" => 'GetBalance',
					"agent_id" => SYSTEM_API_AGENT_ID,
					"syslang" => LANG_EN,
					"device" => PLATFORM_WEB,
					"provider_code" => $game_code,
				);
									
				//Get balance
				$balance = 0;
				$account_data = $this->player_model->get_player_game_account_data($game_code, $player_data['player_id']);
				if( ! empty($account_data))
				{
				    $param_array['player_id'] = $account_data['player_id'];
				    $param_array['game_id'] = $account_data['game_id'];
					$param_array['username'] = $account_data['username'];
					$param_array['password'] = $account_data['password'];
					$param_array['signature'] = md5(SYSTEM_API_AGENT_ID . $game_code . $param_array['username'] . SYSTEM_API_SECRET_KEY);
					$response = $this->curl_json($url, $param_array);
					$result_array = json_decode($response, TRUE);
					ad($result_array);
					if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
					{
						$balance = ($balance + $result_array['result']);
					}
				}

				//Logout game
				//$param_array['method'] = 'LogoutGame';
				//$this->curl_json($url, $param_array);

				if($balance > 0)
				{
					//Withdraw credit
					$param_array['method'] = 'ChangeBalance';
					$param_array['order_id'] = 'OUT' . date("YmdHis") . $account_data['username'];
					$param_array['amount'] = ($balance * -1);
					$response = $this->curl_json($url, $param_array);
					$result_array = json_decode($response, TRUE);
					if(isset($result_array['errorCode']) && $result_array['errorCode'] == '0')
					{
						//update wallet
						$newData = $this->player_model->point_transfer($player_data, $balance, $player_data['username']);
						$this->player_model->insert_log(LOG_WALLET_TRANSFER, $device, $newData, $player_data);
						$this->general_model->insert_cash_transfer_report($player_data, $balance, TRANSFER_TRANSACTION_IN);
						$this->general_model->insert_game_transfer_report($game_code, 'MAIN', $balance, $player_data['points'], $balance, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
						$json['status'] = EXIT_SUCCESS;
						$json['balance'] = bcdiv($balance, 1, 2);
					}else if(isset($result_array['errorCode']) && $result_array['errorCode'] == '201'){
						$newData = $this->general_model->insert_game_transfer_pending_report($game_code, 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $balance, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
						$this->player_model->insert_log(LOG_WALLET_TRANSFER_PENDING, $device, $newData);
					}else if(isset($result_array['errorCode'])){
					}else{
					    $newData = $this->general_model->insert_game_transfer_pending_report($game_code, 'MAIN', TRANSFER_TRANSACTION_IN, $balance, $player_data['points'], $balance, $player_data['player_id'],(isset($result_array['orderID']) ? trim($result_array['orderID']) : ''),(isset($result_array['orderIDAlias']) ? trim($result_array['orderIDAlias']) : ''));
						$this->player_model->insert_log(LOG_WALLET_TRANSFER_PENDING, $device, $newData);
					}
				}else{
					$json['status'] = EXIT_SUCCESS;
					$json['balance'] = bcdiv($balance, 1, 2);
				}
			
		}
		ad($json);
		/*
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
				
		exit();
		*/
	}
	
	public function test_json(){
	    $array = array(
	        "APIUrl" => "https://api.onlinegames22.com",
	        "Cert" => "A4iTAOqn7sgRa73amBG",
	        "agentId" => "sandsintag",
	        "ReportUrl" => "https://fetch.onlinegames22.com",
	        "CurrencyType" => "CNY",
	        "BetLimit" => array(
	            "SEXYBCRT" => array(
	                "LIVE" => array(
	                    "limitId" => array(260302,260304,260310),
	                ),
	            ),
	            "VENUS" => array(
	                "LIVE" => array(
	                    "limitId" => array(260302,260304,260310),
	                ),
	            ),
	            "BG" => array(
	                "LIVE" => array(
	                    "CNY" => array(
	                        "limitId" => array('H1','H4','H7'),     
	                   ),
	                ),
	            ),
	            "SV388" => array(
	                "LIVE" => array(
	                    "maxbet" => 10000,
	                    "minbet" => 1,
	                    "mindraw" => 1,
	                    "matchlimit" =>20000,
	                    "maxdraw" => 1000,
	                ),
	            ),
	            "HORSEBOOK"  => array(
	                "LIVE" => array(
	                    "minbet" => 10,
	                    "maxbet" => 3000,
	                    "maxBetSumPerHorse" => 10000,
	                    "minorMinbet" => 10,
	                    "minorMaxbet" => 1000,
	                    "minorMaxBetSumPerHorse" => 10000,
	                ),
	            ),
	        ),
	    );
	    
	    ad(json_encode($array));
	    
	    $test = '{"APIUrl":"https:\/\/api.onlinegames22.com","Cert":"A4iTAOqn7sgRa73amBG","agentId":"sandsintag","ReportUrl":"https:\/\/fetch.onlinegames22.com","CurrencyType":"CNY","BetLimit":{"SEXYBCRT":{"LIVE":{"limitId":[260302,260304,260310]}}}}';
	    $array_decode  = json_decode($test,true);
	    ad($array_decode);
	    ad(json_encode($array_decode['BetLimit']));
	}
	
	public function test_time(){
	    $time = strtotime("2016-12-23 05:50:35.0");
	    ad(date("Y-m-d H:i:s",$time));
	}
	
	public function set_sub_game_image(){
	    $provider_code = "CQ9";
	    $result = NULL;
	    $query = $this
				->db
				->where('game_provider_code', $provider_code)
				->get('sub_games');
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		
		if( ! empty($result))
		{
		    foreach($result as $result_row){
		        $update_data = array('game_picture_en' => $result_row['game_code'].".png");
				$this->db->where('sub_game_id', $result_row['sub_game_id']);
				$this->db->limit(1);
				$this->db->update('sub_games', $update_data);
		    }   
		}
	}

	public function send_via_telegram(){
		$url = 'https://api.telegram.org/bot5456510833:AAFWN6a4DDtpMKjbLRQskgmOV2lxwENg0gc/sendMessage';
		$msg = "Testing 123";
		$content = array('chat_id' => -1001796085048, 'text' => $msg);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    $result = curl_exec($ch);
	}
	
	
	public function add_player_bank_name(){
	    $dbprefix = $this->db->dbprefix;
	    $player_list = array();
	    
	    $query = $this->db->query("SELECT player_id FROM {$dbprefix}players WHERE bank_account_name IS NULL ORDER BY player_id DESC");
	    if($query->num_rows() > 0) {
	        foreach($query->result() as $player_row){
	           $player_lists[$player_row->player_id] = array(
	                'player_id' => $player_row->player_id,
	                'bank_account_name' => "",
	                'is_update_bank_account_name' => FALSE,
	           );
	        }
	    }
	    $query->free_result();
	    
	    
	    if(!empty($player_lists)){
	        $query = $this->db->query("SELECT player_id,bank_account_name FROM {$dbprefix}player_bank ORDER BY player_bank_id ASC");
	        if($query->num_rows() > 0) {
	            foreach($query->result() as $player_row){
	                if($player_lists[$player_row->player_id]['is_update_bank_account_name'] == FALSE){
	                    $player_lists[$player_row->player_id]['bank_account_name'] = $player_row->bank_account_name;
	                    $player_lists[$player_row->player_id]['is_update_bank_account_name'] = TRUE;
	                }          
	            }
	        }
	    }
	    $query->free_result();
	    
	    if(!empty($player_lists)){
	        foreach($player_lists as $player_lists_row){
	            if($player_lists_row['is_update_bank_account_name'] == TRUE){
	                $DBdata = array('bank_account_name'=>$player_lists_row['bank_account_name']);
	                $this->db->where('player_id',$player_lists_row['player_id']);
	                $this->db->limit(1);
	                $this->db->update('players', $DBdata);
	            }
	        }  
	    }
	}
	
	public function adjust_player_bank_withdrawal_count(){
	    $player_list = array();
	    $start_date = "2023-04-07 00:00:00";
	    $start_time = strtotime($start_date);
	    $result = array();
	    $this->db->select('player_id');
	    $this->db->where('created_date < ',$start_time);
	    
	    $query = $this->db->get('players');
	    if($query->num_rows() > 0)
		{
			$result = $query->result_array();	
		}
		
		if(!empty($result)){
		    foreach($result as $row){
		        $player_list[] = $row['player_id'];   
		    }
		    $DBdata = array('withdrawal_count' => 5);
		    $this->db->where_in('player_id', $player_list);
    		$this->db->update('player_bank', $DBdata);
		}
	}
}