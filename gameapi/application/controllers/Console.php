<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Console extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	public function testing_sa(){
	    $url = 'https://ipinfo.globalxns.com/GetIPAddress';
	    $response = $this->curl_get($url);
	    ad($response);
	}
	
	public function rng()
	{
		$this->load->library('rng');
		$token = $this->rng->get_token(16);
		ad($token);
	}
	
	public function string_to_array()
	{
		echo '<meta charset="utf-8">';
		
		$data = '';
		$fh = fopen('./assets/text.txt', 'r');
		while( ! feof($fh)) {
		  $data .= ucfirst(trim(fgets($fh))) . "<br />";
		}
		fclose($fh);
		
		echo $data;
	}
	
	public function trim_name()
	{
		$query = $this->db->query("SELECT * FROM bctp_sub_games WHERE game_provider_code = 'SG' AND game_type_code = 'OT'");
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$game_picture_en = str_replace(array(' ', "'"), array('_', ''), $row->game_name_en) . '_146x136.png';
				$game_picture_chs = str_replace(array(' ', "'"), array('_', ''), $row->game_name_en) . '_146x136.png';
				$game_picture_cht = '';
				$this->db->query("UPDATE bctp_sub_games SET game_code = ?, game_name_en = ?, game_name_chs = ?, game_name_cht = ?, game_picture_en = ?, game_picture_chs = ?, game_picture_cht = ? WHERE sub_game_id = ? LIMIT 1", array(trim($row->game_code), trim($row->game_name_en), trim($row->game_name_chs), trim($row->game_name_cht), trim($game_picture_en), trim($game_picture_chs), trim($game_picture_cht), $row->sub_game_id));
			}
		}
	}
	
	public function subgame()
	{
		$lists = array();
		$game_name_en_arr = array('Crash','Double','Dice','Limbo','Plinko','Keno','Mine','Crypto','Triple','Hilo','Coin','Tower','Bingo','Video Poker','Space X');
		$game_name_chs_arr = array('暴力弹','轮盘','骰宝','凌波弹','叮咚球','基诺球','挖矿弹','宝石','三联爆','西洛','猜硬币','爬塔','宾果','电子扑克','星际探险');
		$game_name_cht_arr = array('暴力彈','輪盤','骰寶','凌波彈','叮咚球','基諾球','挖礦彈','寶石','三聯爆','西洛','猜硬幣','爬塔','賓果','電子撲克','星際探險');
		$code_arr = array('crash','double','dice','limbo','plinko','keno','mine','crypto','triple','hilo','coin','tower','bingo','poker','spacex');
		$game_type_code_arr = array('BG','BG','BG','BG','BG','BG','BG','BG','BG','BG','BG','BG','BG','BG','BG');
		$game_pic_en_arr = array('crash','double','dice','limbo','plinko','keno','mine','crypto','triple','hilo','coin','tower','bingo','poker','spacex');
		
		for($i=0; $i<sizeof($code_arr); $i++)
		{
			$DBdata = array(
				'game_provider_code' => 'T1G',
				'game_type_code' => $game_type_code_arr[$i],
				'game_sequence' => ($i + 1),
				'game_code' => $code_arr[$i],
				'game_name_en' => $game_name_en_arr[$i],
				'game_name_chs' => $game_name_chs_arr[$i],
				'game_name_cht' => $game_name_cht_arr[$i],
				'game_picture' => $game_pic_en_arr[$i] . '.png',
				'game_picture_en' => $game_pic_en_arr[$i] . '.png',
				// 'game_picture_chs' => $code_arr[$i] . '.jpg',
				// 'game_picture_cht' => $code_arr[$i] . '.jpg',
				'active' => 1,
				'is_mobile' => 1,
				'is_open_game' => 1,
				'is_progressive' => 0,
				'is_hot' => 0,
				'is_new' => 0,
			);
			
			array_push($lists, $DBdata);
		}	
		
		if(sizeof($lists) > 0) {
			$this->db->insert_batch('sub_games', $lists);
		}
		
		echo 'Done';
	}
	
	public function api_connect($provider_code = 'NAGA', $type = 'li', $username = 'sa3dev021',$game_id = 'sa3dev021', $amount = '100', $game_type_code = 'SL', $game_code = "kawaii-neko", $is_demo = NULL) 
	{
		$signature = md5(SYSTEM_API_AGENT_ID . $provider_code . $username . SYSTEM_API_SECRET_KEY);
		
		switch($type)
		{
			case 'cm': $method = 'CreateMember'; break;
			case 'li': $method = 'LoginGame'; break;
			case 'gb': $method = 'GetBalance'; break;
			case 'cb': $method = 'ChangeBalance'; break;
			case 'lo': $method = 'LogoutGame'; break;
			case 'gl': $method = 'GameList'; break;
		}
		
		$array_param = array(
			"method" => $method,
			"agent_id" => SYSTEM_API_AGENT_ID,
			"signature" => $signature,
			"syslang" => LANG_EN,
			"device" => PLATFORM_WEB,
			"provider_code" => $provider_code,
			"username" => $username,
			'player_id' => 15,
			"game_id" => $game_id,
			"password" => 'A47326237a',
			"amount" => $amount,
			"order_id" => (($amount > 0) ? 'IN' : 'OUT') . date("YmdHis"). $username,
			"game_type_code" => $game_type_code,
			"game_code" => $game_code,
			"is_demo" => $is_demo,
			"return_url" => site_url('home')
		);
		// ad($array_param);
		// ad(base_url('api'));
		$response = $this->curl_json(base_url('api'), $array_param);
		ad($response);
		$response_array = json_decode($response['data'],true);
		
		if($type == 'cm') {
			if(isset($response_array['gameID']) && ! empty($response_array['gameID'])) {
				$DBdata = array(
					'game_provider_code' => $array_param['provider_code'],
					'username' => $array_param['username'],
					'game_id' => $response_array['gameID'],
					'password' => $array_param['password'],
					'player_id' => $array_param['player_id'],
					'created_date' => time()
				);
						
				$this->db->insert('player_game_accounts', $DBdata);		
			}
		}
		ad($response_array['result']);
		//Output
		/*
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response))
				->_display();
				
		exit();
		*/
	}
	
	public function logingame($provider_code = 'PGS2', $type = 'li', $username = 'dev004',$game_id = '1', $amount = '100', $game_type_code = 'SL', $game_code = "1", $is_demo = NULL) 
	{
		$signature = md5(SYSTEM_API_AGENT_ID . $provider_code . $username . SYSTEM_API_SECRET_KEY);
		
		switch($type)
		{
			case 'cm': $method = 'CreateMember'; break;
			case 'li': $method = 'LoginGame'; break;
			case 'gb': $method = 'GetBalance'; break;
			case 'cb': $method = 'ChangeBalance'; break;
			case 'lo': $method = 'LogoutGame'; break;
			case 'gl': $method = 'GameList'; break;
		}
		
		$array_param = array(
			"method" => $method,
			"agent_id" => SYSTEM_API_AGENT_ID,
			"signature" => $signature,
			"syslang" => LANG_EN,
			"device" => PLATFORM_WEB,
			"provider_code" => $provider_code,
			"username" => $username,
			'player_id' => 1,
			"game_id" => $game_id,
			"password" => 'A47326237a',
			"amount" => $amount,
			"order_id" => (($amount > 0) ? 'IN' : 'OUT') . date("YmdHis"). $username,
			"game_type_code" => $game_type_code,
			"game_code" => $game_code,
			"is_demo" => $is_demo,
			"return_url" => 'exp://REPLACE_ME',
		);
		
		$response = $this->curl_json(site_url('api'), $array_param);
		$result_array = json_decode($response['data'], TRUE);
		ad($result_array);
		echo $result_array['result'];
	}
	
	public function sbo_agent_register(){
	    $provider_code = 'SBO';
	    $game_data = NULL;
    	$player_data =  NULL;
    	
	    $url = 'https://ex-api-yy.xxttgg.com';
	    $url .= '/web-root/restricted/agent/register-agent.aspx';
	    ad($url);
	    $param_array = array(
			'CompanyKey' => '5956AE9690EE42CBA33B853A64D04DE7',
			'ServerId' => 'YY-production',
			'Username' => 'sccclubtwcny',
			'Password' => 'sccclub568',
			'Currency' => 'CNY',
			'Min' => 1,
    	    'Max' => 5000,
    	    'MaxPerMatch' => 50000,
		    'CasinoTableLimit' => 4,
		);
		/*
		'Min' => 10,
		'Max' => 50000,
		'MaxPerMatch' => 100000,
		*/
		/*
		'Min' => 10,
    	'Max' => 5000,
    	'MaxPerMatch' => 20000,
		*/
		ad($param_array);
		$response = $this->curl_json($url, $param_array);
		ad($response);
		
	}
	
	public function lv22_get_bet_history(){
	    $next_id = 0;
	    $url = "http://gslog.336699bet.com";
		$signature = strtoupper(md5("ew2m" . "99384930c68a2cb2b8f6c93f97ccdddb"));
		$url .= '/fetchbykey.aspx?operatorcode=' . "ew2m" . '&versionkey=' . $next_id . '&signature=' . $signature;
		ad($url);
		$response = $this->curl_get($url);
	    ad($response);
	}
	
	public function lv22_get_member_username_password(){
	    $signature = strtoupper(md5("ew2m"."L2"."eswyuner666"."99384930c68a2cb2b8f6c93f97ccdddb"));
	    $url = "http://gsmd.336699bet.com/checkMemberProductUsername.aspx?operatorcode=ew2m&providercode=L2&username=eswyuner666&signature=".$signature;
	    $response = $this->curl_get($url);
	    ad($response);
	}
	
	public function sexy_urgent_replace_data(){
	    $member_lists = $this->player_model->get_player_list_array();
	    $sys_data = $this->miscellaneous_model->get_miscellaneous();
	    $provider_code = 'SX';
		$result_type = GAME_LIVE_CASINO;
		$sync_type = SYNC_TYPE_MODIFIED;
		$db_record_start_time = strtotime('-30 days' ,time());
		$db_record_end_time = strtotime('+15 days' ,time());
		$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_GAME,$db_record_start_time, $db_record_end_time);
		$Bdata = array();
		$BUdata = array();
		$game_code_data = array(
			'MX-LIVE-009' => "Roulette",
			'MX-LIVE-007' => "SicBo",
			'MX-LIVE-006' => "Dragon Tiger",
			'MX-LIVE-001' => "Baccarat",
			'MX-LIVE-003' => "Baccarat",
			'MX-LIVE-010' => "Red Blue Duel",
		);
		$result_plain = '{"transactions":[{"gameType":"LIVE","winAmount":0,"txTime":"2021-03-30T12:58:31+08:00","settleStatus":0,"gameInfo":"{\"result\":[\"H04\",\"D12\",\"S09\",\"D12\",\"S06\",\"\"],\"roundStartTime\":\"03\/30\/2021 12:58:15.109\",\"winner\":\"BANKER\",\"ip\":\"202.184.66.24\",\"odds\":-1.0,\"tableId\":502,\"dealerDomain\":\"Mexico\",\"winLoss\":-10.0,\"status\":\"LOSE\"}","realWinAmount":0,"updateTime":"2021-03-30T12:59:05+08:00","realBetAmount":10,"userId":"esweasywin002","betType":"Player","platform":"SEXYBCRT","txStatus":1,"betAmount":10,"gameName":"BaccaratClassic","platformTxId":"BAC-18389634720","betTime":"2021-03-30T12:58:31+08:00","gameCode":"MX-LIVE-001","currency":"MYR","jackpotWinAmount":0,"jackpotBetAmount":0,"turnover":10,"roundId":"Mexico-502-GA86130064"}],"status":"0000"}';
		$result_array = json_decode($result_plain, TRUE);
		if(isset($result_array['status']) && $result_array['status'] == '0000')
		{
		    if(isset($result_array['transactions']) &&  sizeof($result_array['transactions'])>0){
				foreach($result_array['transactions'] as $result_row){
				    $tmp_username = strtolower(trim($result_row['userId']));
					$exact_username = ((substr($tmp_username, 0, 3) == $sys_data['system_prefix']) ? substr($tmp_username, 3) : $tmp_username);

				    $PBdata = array(
				        'game_provider_code' => $provider_code,
				        'game_type_code' => GAME_LIVE_CASINO,
				        'game_result_type' => $result_type,
				        'game_code' => (isset($game_code_data[trim($result_row['gameCode'])]) ? $game_code_data[trim($result_row['gameCode'])] : "Other"),
				        'game_real_code' => trim($result_row['gameCode']),
				        'bet_id' => trim($result_row['platform']).trim($result_row['platformTxId']),
				        'bet_time' => strtotime(trim($result_row['betTime'])),
				        'game_time' => strtotime(trim($result_row['txTime'])),
				        'report_time' => strtotime(trim($result_row['updateTime'])),
				        'bet_amount' => trim($result_row['betAmount']),
				        'bet_amount_valid' => trim($result_row['realBetAmount']),
				        'payout_amount' => 0,
				        'promotion_amount' => 0,
				        'payout_time' => strtotime(trim($result_row['updateTime'])),
				        'win_loss' => trim($result_row['winAmount']) - trim($result_row['betAmount']),
				        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
				        'bet_code' => trim($result_row['betType']),
				        'game_result' => trim($result_row['gameInfo']),
				        'table_id' => trim($result_row['platformTxId']),
				        'round' => trim($result_row['roundId']),
				        'subround'  => "",
				        'status' => STATUS_CANCEL,
				        'game_username' => $result_row['userId'],
				        'player_id' => $member_lists[$exact_username],
				    );
				    
				    if($result_row['txStatus'] == 1){
				    	$PBdata['status'] = STATUS_COMPLETE;
				    	$PBdata['payout_amount'] = $result_row['winAmount'];
				    	//promotion
				    	if($PBdata['win_loss'] != 0){
				    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
				    	}
				    }else if($result_row['txStatus'] == 0){
				    	$PBdata['status'] = STATUS_PENDING;
				    }

				    if( ! in_array($PBdata['bet_id'], $transaction_lists))
					{					
						$PBdata['bet_info'] = json_encode($result_row);
				        $PBdata['insert_type'] = SYNC_DEFAULT;
						array_push($Bdata, $PBdata);
					}else{
						$PBdata['bet_update_info'] = json_encode($result_row);
				        $PBdata['update_type'] = SYNC_DEFAULT;
						array_push($BUdata, $PBdata);
					}
				}
			}
		}
		ad($Bdata);
		
		$this->db->trans_start();
		$result_promotion_reset = array('promotion_amount' => 0);
		if( ! empty($Bdata))
		{
			$this->db->insert_batch('transaction_report', $Bdata);
			//promotion
			foreach($Bdata as $BdataRow){
				if($BdataRow['status'] == STATUS_COMPLETE){
					$this->report_model->update_promotion_amount($BdataRow,$result_promotion_reset);
				}
			}
		}
		if( ! empty($BUdata))
		{
			foreach($BUdata as $BUdataRow){
				$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$BUdataRow['bet_id'],$BUdataRow);
			}
			foreach($BUdata as $BUdataRow){
				if($BUdataRow['status'] == STATUS_COMPLETE){
					$this->report_model->update_promotion_amount($BUdataRow,$result_promotion_reset);
				}
			}
		}
		$this->db->trans_complete();
		
	}
	
	
	public function xe_create_member(){
	    //{"APIUrl":"http://xespublicapi.eznet88.com", "AgentId":"TrainingApI", "Prefix":"T-Api", "SignatureKey":"c5228871-5aa1-472a-b1ec-b541b81ebb05"}
	    $url = "http://xespublicapi.eznet88.com";
	    $username = "T-Api" ."_"."lionkinglionking";
	    $url .= '/player/create';
	    $param_array = array(
			"agentid" => "TrainingApI",
		);
		ad($url);
		$param_array['account'] = $username;
		$param_array['password'] = "47326237";
		$hashinput = json_encode($param_array,true);
		ad($hashinput);
		$hashdata = hash_hmac("sha256",$hashinput,"c5228871-5aa1-472a-b1ec-b541b81ebb05", true);
		ad($hashdata);
		$hash = base64_encode($hashdata);
		ad($hash);
		$token = 'hashkey: ' . $hash;
		ad($token);
		$response = $this->curl_post_xe($url, $param_array,$token);
		ad($response);
	}
	
	public function mega_create_member(){
	    $this->load->library('rng');
	    //{"APIUrl":"http://mgt3.36ozhushou.com/mega-cloud/api/", "SN":"ld00", "JsonRPC":"2.0", "SecretCode":"WN7Zp8Fl8TeG/h2LphqYdpwzL7M=", "Account":"Mega1-1417"}
	    $url = "http://mgt3.36ozhushou.com/mega-cloud/api/";
	    $username = "eswlionking";
	    $url .= 'open.mega.user.create';
	    $param_array = array(
			"id" => $this->rng->get_token(50),
			"params" => array(
				"random" => $this->rng->get_token(50),
				"sn" => "ld00",
			),
			"jsonrpc" => "2.0",
		);
		ad($param_array['params']['random']."ld00"."WN7Zp8Fl8TeG/h2LphqYdpwzL7M=");
		$param_array['method'] = "open.mega.user.create";
		$param_array['params']['digest'] = md5($param_array['params']['random']."ld00"."WN7Zp8Fl8TeG/h2LphqYdpwzL7M=");
		$param_array['params']['nickname'] = $username;
		$param_array['params']['agentLoginId'] = "Mega1-1417";
		ad($url);
		ad($param_array);
		$response = $this->curl_post_xe($url, $param_array);
		ad($response);
	}
	
	public function mega_balance(){
	    $this->load->library('rng');
	    //{"APIUrl":"https://api.evo388.com", "Authcode":"6A32F5285135EFE0","SecretKey":"8583511CAD5B4C72131D85B389047289","AreaID":"1"}
	    $url = "https://api.evo388.com";
	    $username = "eswyuntest";
	    $game_id = "01876827642";
	    $url .= 'open.mega.balance.get';
	    $param_array = array(
			"id" => $this->rng->get_token(50),
			"params" => array(
				"random" => $this->rng->get_token(50),
				"sn" => "ld00",
			),
			"jsonrpc" => "2.0",
		);
		ad($param_array['params']['random']."ld00"."WN7Zp8Fl8TeG/h2LphqYdpwzL7M=");
		$param_array['method'] = "open.mega.balance.get";
		$param_array['params']['digest'] = md5($param_array['params']['random']."ld00".$game_id."WN7Zp8Fl8TeG/h2LphqYdpwzL7M=");
		$param_array['params']['loginId'] = $game_id;
		ad($url);
		ad($param_array);
		$response = $this->curl_post_xe($url, $param_array);
		ad($response);
		if($response['code'] == '0')
		{
		    echo "here";
    		$response_data = $response['data'];
    		$result_array = json_decode($response_data, TRUE);
    		ad($result_array);
    		ad("error : ".$result_array['error']);
    		if(array_key_exists('error',$result_array) && empty($result_array['error']))
			{
				$output['errorCode'] = ERROR_SUCCESS;
				$output['errorMessage'] = $this->lang->line('error_success');
				$output['result'] = bcdiv($result_array['result'], 1, 2);
			}else if(array_key_exists('error',$result_array) && $result_array['error']['code'] == '37111')
			{
				$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				$output['errorMessage'] = $this->lang->line('error_username_not_found');
			}
    		ad($output);
		}
	}
	
	public function mega_transfer(){
	    $this->load->library('rng');
	    //{"APIUrl":"http://mgt3.36ozhushou.com/mega-cloud/api/", "SN":"ld00", "JsonRPC":"2.0", "SecretCode":"WN7Zp8Fl8TeG/h2LphqYdpwzL7M=", "Account":"Mega1-1417"}
	    $url = "http://mgt3.36ozhushou.com/mega-cloud/api/";
	    $username = "eswlionking";
	    $game_id = "114172794636";
	    $url .= 'open.mega.balance.transfer';
	    $param_array = array(
			"id" => $this->rng->get_token(50),
			"params" => array(
				"random" => $this->rng->get_token(50),
				"sn" => "ld00",
			),
			"jsonrpc" => "2.0",
		);
		ad($param_array['params']['random']."ld00".$game_id."-20.00"."WN7Zp8Fl8TeG/h2LphqYdpwzL7M=");
		$param_array['method'] = "open.mega.balance.transfer";
		$param_array['params']['digest'] = md5($param_array['params']['random']."ld00".$game_id."-20.00"."WN7Zp8Fl8TeG/h2LphqYdpwzL7M=");
		$param_array['params']['loginId'] = $game_id;
		$param_array['params']['amount'] = bcdiv(-20,1,2);
		ad($url);
		ad($param_array);
		$response = $this->curl_post_xe($url, $param_array);
		ad($response);
	}
	
	public function mega_download_url(){
	    $this->load->library('rng');
	    //{"APIUrl":"http://mgt3.36ozhushou.com/mega-cloud/api/", "SN":"ld00", "JsonRPC":"2.0", "SecretCode":"WN7Zp8Fl8TeG/h2LphqYdpwzL7M=", "Account":"Mega1-1417"}
	    $url = "http://mgt3.36ozhushou.com/mega-cloud/api/";
	    $username = "eswyuntest";
	    $url .= 'open.mega.app.url.download';
	    $param_array = array(
			"id" => $this->rng->get_token(50),
			"params" => array(
				"random" => $this->rng->get_token(50),
				"sn" => "ld00",
			),
			"jsonrpc" => "2.0",
		);
		ad($param_array['params']['random']."ld00"."WN7Zp8Fl8TeG/h2LphqYdpwzL7M=");
		$param_array['method'] = "open.mega.app.url.download";
		$param_array['params']['digest'] = md5($param_array['params']['random']."ld00"."WN7Zp8Fl8TeG/h2LphqYdpwzL7M=");
		$param_array['params']['agentLoginId'] = "Mega1-1417";
		ad($url);
		ad($param_array);
		$response = $this->curl_post_xe($url, $param_array);
		ad($response);
		
		
	}
	
	public function evo88_create_account(){
	    $this->load->library('rng');
	    $this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
	    $api_data = '{"APIUrl":"https://api.evo388.com", "Authcode":"6A32F5285135EFE0","SecretKey":"8583511CAD5B4C72131D85B389047289"}';
	    $arr = json_decode($api_data, TRUE);
	    $url = $arr['APIUrl'];
	    $post_data['username'] = "eswlionking";
	    $post_data['game_id'] = "01929051699";
	    $post_data['amount'] = -10;
	    $url .= '/api?action=addUser&name=' . $post_data['username'] . '&time=' . $timestamp . '&type=0&authcode=' . $arr['Authcode'] . '&sign=' . md5(strtolower($arr['Authcode'] . $timestamp . $arr['SecretKey']));
	    ad($url);
	    $response = $this->curl_get($url);
	    ad($response);
	}

    public function pus8_create_account_pre(){
	    $this->load->library('rng');
	    $this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
	    $api_data = '{"APIUrl":"http://api.pussy888.com/", "Agent":"ed111api", "Authcode":"RaTAvzknqMYazqXXxHvf", "SecretKey":"UvUn46p922T7qjBPZ62y"}';
	    $arr = json_decode($api_data, TRUE);
	    $url = $arr['APIUrl'];
	    $post_data['username'] = "eswyuntest";
	    $post_data['game_id'] = "01929051699";
	    $post_data['password'] = "47326237";
	    $post_data['amount'] = 10;
	    $output['gamePassword'] = "A".$post_data['password']."a";
	    ad(strtolower($arr['Authcode'] . $post_data['username'] .$timestamp . $arr['SecretKey']));
	    $url .= 'ashx/account/account.ashx?action=RandomUserName&userName='.$arr['Agent'].'&UserAreaId='.'&time='.$timestamp.'&authcode='.$arr['Authcode'] . '&sign=' . strtoupper(md5(strtolower($arr['Authcode'] . $arr['Agent'] .$timestamp . $arr['SecretKey'])));
	    ad($url);
	    $response = $this->curl_get($url);
	    ad($response);
	}
	
	public function pus8_create_account(){
	    $this->load->library('rng');
	    $this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
	    $api_data = '{"APIUrl":"http://api.pussy888.com/", "Agent":"ed111api", "Authcode":"RaTAvzknqMYazqXXxHvf", "SecretKey":"UvUn46p922T7qjBPZ62y"}';
	    $arr = json_decode($api_data, TRUE);
	    $url = $arr['APIUrl'];
	    $post_data['username'] = "eswyuntest";
	    $post_data['game_id'] = "my91074014318";
	    $post_data['password'] = "47326237";
	    $post_data['amount'] = 10;
	    $output['gamePassword'] = "A".$post_data['password']."a";
	    ad(strtolower($arr['Authcode'] . $post_data['username'] .$timestamp . $arr['SecretKey']));
	    $url .= 'ashx/account/account.ashx?action=addUser&agent='.$arr['Agent']."&PassWd=".$output['gamePassword']."&userName=".$post_data['game_id']."&Name=".$post_data['username']."&Tel=".time()."&Memo=newplayer&UserType=1&pwdtype=1&time=".$timestamp.'&authcode=' . $arr['Authcode'] . '&sign=' . strtoupper(md5(strtolower($arr['Authcode'] . $post_data['game_id'] .$timestamp . $arr['SecretKey'])));
	    ad($url);
	    $response = $this->curl_get($url);
	    ad($response);
	}
	
	public function pus8_balance(){
	    $this->load->library('rng');
	    $this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
	    $api_data = '{"APIUrl":"http://api.pussy888.com/", "Agent":"ed111api", "Authcode":"RaTAvzknqMYazqXXxHvf", "SecretKey":"UvUn46p922T7qjBPZ62y"}';
	    $arr = json_decode($api_data, TRUE);
	    $url = $arr['APIUrl'];
	    $post_data['username'] = "eswyuntest";
	    $post_data['game_id'] = "my9107401438";
	    $post_data['password'] = "47326237";
	    $post_data['amount'] = 10;
	    $output['gamePassword'] = "A".$post_data['password']."a";
	    ad(strtolower($arr['Authcode'] . $post_data['username'] .$timestamp . $arr['SecretKey']));
	    $url .= 'ashx/account/account.ashx?action=getUserInfo&userName='.$post_data['game_id'].'&time='.$timestamp.'&authcode=' . $arr['Authcode'] . '&sign=' . strtoupper(md5(strtolower($arr['Authcode'] . $post_data['game_id'] .$timestamp . $arr['SecretKey'])));
	    ad($url);
	    $response = $this->curl_get($url);
	    ad($response);
	}
	
	public function pus8_transfer(){
	    $this->load->library('rng');
	    $this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
	    $api_data = '{"APIUrl":"http://api.pussy888.com/", "Agent":"ed111api", "Authcode":"RaTAvzknqMYazqXXxHvf", "SecretKey":"UvUn46p922T7qjBPZ62y"}';
	    $arr = json_decode($api_data, TRUE);
	    $url = $arr['APIUrl'];
	    $post_data['username'] = "eswyuntest";
	    $post_data['game_id'] = "my91074014318";
	    $post_data['password'] = "47326237";
	    $post_data['amount'] = "10.00";
	    $url .= 'ashx/account/setScore.ashx?action=setServerScore&scoreNum='.$post_data['amount'].'&userName='.$post_data['game_id'].'&ActionUser='.$arr['Agent'].'&ActionIp=157.245.107.154&time='.$timestamp.'&authcode=' . $arr['Authcode'] . '&sign=' . strtoupper(md5(strtolower($arr['Authcode'] . $post_data['game_id'] .$timestamp . $arr['SecretKey'])));
	    ad($url);
	    $response = $this->curl_get($url);
	    ad($response);
	}
	
	public function evo88_balance(){
	    $this->load->library('rng');
	    $this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
	    $api_data = '{"APIUrl":"https://api.evo388.com", "Authcode":"6A32F5285135EFE0","SecretKey":"8583511CAD5B4C72131D85B389047289"}';
	    $arr = json_decode($api_data, TRUE);
	    $url = $arr['APIUrl'];
	    $post_data['username'] = "eswlionking";
	    $post_data['game_id'] = "01929051699";
	    $post_data['amount'] = -10;
	    $url .= '/api?action=searchUser&username=' . $post_data['game_id'] . '&time=' . $timestamp . '&type=0&authcode=' . $arr['Authcode'] . '&sign=' . md5(strtolower($arr['Authcode'] .$post_data['game_id'] . $timestamp . $arr['SecretKey']));
	    ad($url);
	    $response = $this->curl_get($url);
	    ad($response);
	     $response_data = $response['data'];
		$result_array = json_decode($response_data, TRUE);
	    if(array_key_exists('code',$result_array) && $result_array['code'] == '0')
		{
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
			$output['result'] = bcdiv($result_array['results']['balance'], 1, 2);
		}else if(array_key_exists('msg',$result_array) && $result_array['msg'] == 'user does not exist'){
			$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
			$output['errorMessage'] = $this->lang->line('error_username_not_found');
		}
		ad($output);
	}
	
	public function evo88_transfer(){
	    $this->load->library('rng');
	    $this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
	    $api_data = '{"APIUrl":"https://api.evo388.com", "Authcode":"6A32F5285135EFE0","SecretKey":"8583511CAD5B4C72131D85B389047289"}';
	    $arr = json_decode($api_data, TRUE);
	    $url = $arr['APIUrl'];
	    $post_data['username'] = "eswlionking";
	    $post_data['game_id'] = "01929051699";
	    $post_data['amount'] = -10;
	    $url .= '/api?action=setScore&username=' . $post_data['game_id'] . '&time=' . $timestamp . '&type=0'. '&score=' . $post_data['amount']. '&authcode=' . $arr['Authcode'] . '&sign=' . md5(strtolower($arr['Authcode'] .$post_data['game_id'] . $timestamp . $arr['SecretKey']));
	    ad($url);
	    $response = $this->curl_get($url);
	    ad($response);
	     $response_data = $response['data'];
		$result_array = json_decode($response_data, TRUE);
	    if(array_key_exists('code',$result_array) && $result_array['code'] == '0')
		{
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
		}else if(array_key_exists('msg',$result_array) && $result_array['msg'] == 'user does not exist'){
			$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
			$output['errorMessage'] = $this->lang->line('error_username_not_found');
		}else if(array_key_exists('msg',$result_array) && $result_array['msg'] == 'score value error'){
			$output['errorCode'] = ERROR_AMOUNT_INSUFFICIENT;
			$output['errorMessage'] = $this->lang->line('error_amount_insufficient');
		}
		ad($output);
	}
	
	public function evo88_logout(){
	    $this->load->library('rng');
	    $this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
	    $api_data = '{"APIUrl":"https://api.evo388.com", "Authcode":"6A32F5285135EFE0","SecretKey":"8583511CAD5B4C72131D85B389047289"}';
	    $arr = json_decode($api_data, TRUE);
	    $url = $arr['APIUrl'];
	    $post_data['provider_code'] = "EVO8";
	    $post_data['username'] = "eswlionking";
	    $post_data['game_id'] = "01929051698";
	    $post_data['amount'] = -10;
	    $url .= '/api?action=quitgameuser&username=' . $post_data['game_id'] . '&time=' . $timestamp . '&authcode=' . $arr['Authcode'] . '&sign=' . md5(strtolower($arr['Authcode'] .$post_data['game_id'] . $timestamp . $arr['SecretKey']));
	    ad($url);
	    $response = $this->curl_get($url);
	    ad($response);
	    $response_data = $response['data'];
		$result_array = json_decode($response_data, TRUE);
	    if(array_key_exists('code',$result_array) && $result_array['code'] == '0')
		{
			$output['errorCode'] = ERROR_SUCCESS;
			$output['errorMessage'] = $this->lang->line('error_success');
		}else{
			$player_acc_data = $this->player_model->get_player_game_token_data($post_data['provider_code'], $post_data['username']);
			if(empty($player_acc_data))
			{
				$output['errorCode'] = ERROR_USERNAME_NOT_FOUND;
				$output['errorMessage'] = $this->lang->line('error_username_not_found');
			}
		}
		ad($output);
	}
	
	public function ag_test_hash(){
	   ad(md5("aaa"));
	   $arr['EncryptKey'] = "12341234";
	   $param_array['cagent'] = 81288128;
	   $param_array['method'] = "tc";
	   $str = "cagent=81288128/\\\\\\\\/method=tc";
	   
	   $this->load->library('des_ecb');
	   $params = $this->des_ecb->encrypt_text($this->des_ecb->pkcs5_pad($str, 8), $arr['EncryptKey']);
	   ad($params);
	   /*
	   ad($str);
	   $ivlen = openssl_cipher_iv_length('DES-ECB');    // 获取密码iv长度
       $iv = openssl_random_pseudo_bytes($ivlen);        // 生成一个伪随机字节串
       $data = openssl_encrypt($str, 'DES-ECB', $arr['EncryptKey'], $options=OPENSSL_RAW_DATA, $iv);    // 加密
       ad(bin2hex($data));
	   */
	   /*
	   $this->load->library('des_ecb');
	   $params = $this->des_ecb->encrypt_text($this->des_ecb->pkcs5_pad($str, 8), $arr['DESKey']);
	   ad($params);
	   */
	   /*
       $this->load->library('des');
       $crypt = new DES($arr['EncryptKey']);
       $params = $crypt->encrypt($str);
       ad($params);
       */
       /*
       $this->load->library('triple_des');
	   $encrypt_data = $this->triple_des->encrypt_text($this->triple_des->pkcs5_pad($str, 8), $arr['EncryptKey']);
	   ad($encrypt_data);
	   */
       /*
       $this->load->library('des_ecb');
	   $params = $this->des_ecb->encrypt_text($this->des_ecb->pkcs5_pad(json_encode($param_array), 8), $arr['EncryptKey']);
	   ad($params);
	   */
	   /*
	   $this->load->library('des');
    	$crypt = new DES($arr['EncryptKey']);
    	$mstr = $crypt->encrypt($str);
    	$q0 = urlencode($mstr);
    	ad($q0);
    	$q = preg_replace_callback('/%[0-9A-F]{2}/', function(array $matches) { return strtolower($matches[0]); }, $q0);
    	ad($q);
     */
	}
	public function ag_bet_record(){
	    $api_data = '{"APIUrl":"https://gi.easywin22.com/doBusiness.do", "ForwardUrl":"https://gci.easywin22.com/forwardGame.do", "CAgent":"JB7_AGIN", "EncryptKey":"jb7Lwt2v", "MD5Key":"jb7BvbqCFArR","OddType":"B","ReportUrl":"http://juvdb7.gdcapi.com:3333","ReportPlainCode":"01DF6BD800FB48053D4FFD6D66C6A1B3","ReportCAgent":"JB7"}';
	    $arr = json_decode($api_data, TRUE);
	    ad($arr);
	    $url = $arr['ReportUrl'];
	    $url .= "/getorders.xml";
	    ad($url);
	    $param_array = array(
			'cagent' => $arr['ReportCAgent'],
			'startdate' => "2021-06-03 05:30:00",
			'enddate' => "2021-06-03 05:35:00",
			'page' => 1,
			'perpage' => 2,
		);
		ad($param_array);
		$key = md5($param_array['cagent'].$param_array['startdate'].$param_array['enddate'].$param_array['page'].$param_array['perpage'].$arr['ReportPlainCode']);
	    $param_string = $url."?cagent=".$param_array['cagent']."&startdate=".$param_array['startdate']."&enddate=".$param_array['enddate']."&page=".$param_array['page']."&perpage=".$param_array['perpage']."&key=".$key;
	    ad($param_string);
	    $response = $this->curl_get($param_string);
	    $xml = simplexml_load_string($response['data']);
		$json = json_encode($xml);
		$result_array = json_decode($json, TRUE);
		if( ! empty($result_array))
		{
			$DBdata['resp_data'] = json_encode($result_array);
			if(isset($result_array['info']) && $result_array['info'] == "0"){
				$DBdata['sync_status'] = STATUS_YES;
				if(isset($result_array['addition']))
				{
					$page_total = trim($result_array['addition']['totalpage']);
					if(isset($result_array['row']))
					{
						if(isset($result_array['row']['@attributes']))
						{
							$bet_detail_array[0] = $result_array['row'];
						}else{
							$bet_detail_array = $result_array['row'];
						}

						foreach($bet_detail_array as $result_row_temp){
							$result_row = $result_row_temp['@attributes'];
							ad($result_row);
						}
					}	
				}
			}
		}
	}
	public function ag_create_account(){
	    $api_data = '{"APIUrl":"https://gi.easywin22.com/doBusiness.do", "ForwardUrl":"https://gi.easywin22.com/doBusiness.do", "CAgent":"JB7_AGIN", "EncryptKey":"jb7Lwt2v", "MD5Key":"jb7BvbqCFArR","OddType":"B"}';
	    $arr = json_decode($api_data, TRUE);
	    ad($arr);
	    $url = $arr['APIUrl'];
	    ad($url);
	    $sys_data = $this->miscellaneous_model->get_miscellaneous();
	    $post_data['username'] = "eswyuntwest";
	    $post_data['game_id'] = "01929051699";
	    $post_data['password'] = "47326237";
	    $post_data['amount'] = -10;
	    $post_data['order_id'] = "IN20210526193239eswlionking";
	    
	    $param_array = array(
			'cagent' => $arr['CAgent'],
			'loginname' => $post_data['username'],
			'password' => $post_data['password'],
			'actype' => 1,
			'cur' => $sys_data['system_currency'],
		);
		$param_array['method'] = "gb";
		//$param_array['method'] = "lg";
		//$param_array['oddtype'] = $arr['OddType'];
		
		
		$param_string = http_build_query($param_array, '', '/\\\\\\\\/');
		 $this->load->library('des_ecb');
		$params = $this->des_ecb->encrypt_text($this->des_ecb->pkcs5_pad($param_string, 8), $arr['EncryptKey']);
		$key = md5($params.$arr['MD5Key']);
		$url .= "?params=".$params."&key=".$key;
		ad($url);
		
		$response = $this->curl_get($url);
		ad($response);
		$xml = simplexml_load_string($response['data']);
		$json = json_encode($xml);
		$result_array = json_decode($json, TRUE);
		ad($result_array);
	}
	public function change_sbo_bet_limit_agent(){
	    $provider_code = 'SBO';
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
		if( ! empty($game_data)){
		    $arr = json_decode($game_data['api_data'], TRUE);
		    $url = $arr['APIUrl'];
		    $url .= '/web-root/restricted/agent/update-agent-preset-bet-settings.aspx';
		    $param_array = array(
				'CompanyKey' => $arr['CompanyKey'],
				'ServerId' => $arr['ServerId'],
				'Username' => $arr['Agent'],
				'Min' => 10,
	        	'Max' => 3000,
	        	'MaxPerMatch' => 10000,
				'CasinoTableLimit' => 4,
			);
			ad($param_array);
			$response = $this->curl_json($url, $param_array);
			ad($response);
		}
	}
	
	public function change_sbo_bet_limit(){
	    $provider_code = 'SBO';
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
				->select('username')
				->where('game_provider_code', $provider_code)
				->get('player_game_accounts');
		
		if($query->num_rows() > 0)
		{
			$player_data = $query->result_array();  
		}
		if( ! empty($game_data) && ! empty($player_data))
		{
		    $arr = json_decode($game_data['api_data'], TRUE);
		    $url = $arr['APIUrl'];
		    $username = "";
		    $url .= '/web-root/restricted/player/update-player-bet-settings.aspx';
		    foreach($player_data as $row){
		        //if($row['username']!='pplpp6688' && $row['username']!='pplppltest001' && $row['username']!='pplppltest002'  && $row['username']!='pplppltest003'  && $row['username']!='pplppltest004'  && $row['username']!='pplppltest005'  && $row['username']!='pplppltest006'  && $row['username']!='pplppltest007'  && $row['username']!='pplppltest008'  && $row['username']!='pplppltest009'  && $row['username']!='pplppltest010'){
		            $username = $row['username'];
    		        $param_array = array(
        				'CompanyKey' => $arr['CompanyKey'],
        				'ServerId' => $arr['ServerId'],
        				'Username' => $username,
        				'Min' => 10,
        	        	'Max' => 3000,
        	        	'MaxPerMatch' => 10000,
        				'CasinoTableLimit' => 4,
        			);
        			$response = $this->curl_json($url, $param_array);
        			ad($response);   
		        //}
		    }
		}
	}
    
    public function tetsing_bettoing(){
        $row[0] = array(
            "sport_type" => "1",
            "min_bet" => 10,
            "max_bet" => 1000,
            "max_bet_per_match" => 3000,
        );
        $row[1] = array(
            "sport_type" => "2",
            "min_bet" => 10,
            "max_bet" => 1000,
            "max_bet_per_match" => 3000,
        );
        $row[2] = array(
            "sport_type" => "3",
            "min_bet" => 10,
            "max_bet" => 1000,
            "max_bet_per_match" => 3000,
        );
        $row[3] = array(
            "sport_type" => "5",
            "min_bet" => 10,
            "max_bet" => 1000,
            "max_bet_per_match" => 3000,
        );
        $row[4] = array(
            "sport_type" => "8",
            "min_bet" => 10,
            "max_bet" => 1000,
            "max_bet_per_match" => 3000,
        );
        $row[5] = array(
            "sport_type" => "10",
            "min_bet" => 10,
            "max_bet" => 1000,
            "max_bet_per_match" => 3000,
        );
        $row[6] = array(
            "sport_type" => "11",
            "min_bet" => 10,
            "max_bet" => 1000,
            "max_bet_per_match" => 3000,
        );
        $row[7] = array(
            "sport_type" => "43",
            "min_bet" => 10,
            "max_bet" => 1000,
            "max_bet_per_match" => 3000,
        );
        $row[8] = array(
            "sport_type" => "99",
            "min_bet" => 10,
            "max_bet" => 1000,
            "max_bet_per_match" => 3000,
        );
        $row[9] = array(
            "sport_type" => "99MP",
            "min_bet" => 10,
            "max_bet" => 1000,
            "max_bet_per_match" => 3000,
        );
        $row[10] = array(
            "sport_type" => "1MP",
            "min_bet" => 10,
            "max_bet" => 1000,
            "max_bet_per_match" => 3000,
        );
        ad(json_encode($row));
    }
    
    public function change_ibc_bet_limit(){
        $provider_code = 'IBC';
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
				->select('username')
				->where('game_provider_code', $provider_code)
				->get('player_game_accounts');
		
		if($query->num_rows() > 0)
		{
			$player_data = $query->result_array();  
		}
		if( ! empty($game_data) && ! empty($player_data))
		{
		    $arr = json_decode($game_data['api_data'], TRUE);
		    $url = $arr['APIUrl'];
		    $url .= '/SetMemberBetSetting';
		    $bet_limit = $arr['BetLimit'];
		    ad($url);
    		foreach($player_data as $row){
    		    $param_array = array(
    				'vendor_id' => $arr['VendorID'],
    				'vendor_member_id' => $arr['OperatorID'] . '_' . $row['username'],
    				'bet_setting' => json_encode($bet_limit),
    			);
    			ad($param_array);
    			$response = $this->curl_post($url, $param_array);
    			//ad($response); 
    		}
		}
    }
    
    public function get_ibc_bet_limit(){
        $provider_code = 'IBC';
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
				->select('username')
				->where('game_provider_code', $provider_code)
				->get('player_game_accounts');
		
		if($query->num_rows() > 0)
		{
			$player_data = $query->result_array();  
		}
		if( ! empty($game_data) && ! empty($player_data))
		{
		    $arr = json_decode($game_data['api_data'], TRUE);
		    $url = $arr['APIUrl'];
		    $url .= '/GetMemberBetSetting';
		    ad($url);
    		foreach($player_data as $row){
    		    $param_array = array(
    				'vendor_id' => $arr['VendorID'],
    				'vendor_member_id' => $arr['OperatorID'] . '_' . $row['username'],
    			);
    			ad($param_array);
    			$response = $this->curl_post($url, $param_array);
    			ad($response);   
		    }
		}
    }
	
	public function jdb_get_balance_test(){
	    $arr['IVkey'] = "1a7c03f79aa7e88c";
	    $arr['Deskey'] = "a6e6a9608d72a99b";
	    $key = $arr['IVkey'];
        $iv = $arr['Deskey'];
        
	    $test = '{"action":11,"ts":1600845828746,"uid":"leobet5551","gType":"0","mType":"9008"}';
	    $param_array = json_decode($test,true);
	    $str = json_encode($param_array);
	    ad($str);
	    $paddingChar = ' ';
	    $size = 16;
        $x = strlen($str) % $size;
        ad($x);
        $padLength = $size - $x;
        ad($padLength);
        for ($i = 0; $i< $padLength; $i++) {
            $str .= $paddingChar;
            ad($str);
        }
        ad($str);
        $encrypted = openssl_encrypt($str, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
        $data = base64_encode($encrypted);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        
        ad($data);
	}
	
	public function jdb_get_balance(){
	    $arr['APIUrl'] = "http://api.jdb1688.net";
	    $arr['Parent'] = "leobet55idrag";
	    $arr['DC'] = "NG";
	    $arr['IVkey'] = "1a7c03f79aa7e88c";
	    $arr['Deskey'] = "a6e6a9608d72a99b";
	    $post_data['username'] = "mv8dev001";
	    
	    $url = $arr['APIUrl'];
	    $param_array = array(
			'ts' => time(),
			'parent' => $arr['Parent'],
			'uid' => $post_data['username'],
		);
		
		$param_array['action'] = 12;
		$param_array['name'] = $post_data['username'];
		
		$params['dc'] = $arr['DC'];
        ad($param_array);
		//$str = json_encode($param_array);
		$str = '{"action":17,"ts":1447452951820,"parent":"testag","uid":"testpl01"}';
		ad($str);
		$this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$aes->set_mode(MCRYPT_MODE_CBC);
		$aes->set_iv($arr['IVkey']);
		$aes->set_key($arr['Deskey']);
		$params['x'] = $aes->encrypt($str);
		ad($params);
		//$response = $this->curl_post($url, $params);
		//ad($response);
	}
	
	public function mg_check_bet_limit(){
	    $provider_code = 'MG';
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
		
		if( ! empty($game_data))
		{
		    $arr = json_decode($game_data['api_data'], TRUE);
		    $row['username'] = "sandev0011";
		    $url = $arr['APIUrl'];
		    
		    $token_url = $arr['STSUrl'] . '/connect/token';
			
			$token_param_array = array(
										'client_id' => $arr['AgentCode'],
										'client_secret' => $arr['SecretKey'],
										'grant_type' => 'client_credentials'
									);
			$token_response = $this->curl_post($token_url, $token_param_array);
			if($token_response['code'] == '0')
			{
			    $token_result_array = json_decode($token_response['data'],true);
			    if(isset($token_result_array['access_token']))
				{
				    $url .= '/api/v1/agents/' . $arr['AgentCode'] . '/products/' . "SMG" . '/players/' . $row['username'] . '/bettingProfiles';
		            ad($url);
        		    $response = $this->curl_get($url, "Authorization: Bearer " . $token_result_array['access_token']);
        			ad(json_decode($response['data']));
				}
			}
			    
		}
	}
	
	public function mg_delete_bet_limit(){
	    $provider_code = 'MG';
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
		
		if( ! empty($game_data))
		{
		    $arr = json_decode($game_data['api_data'], TRUE);
		    $row['username'] = "sandev0011";
		    $url = $arr['APIUrl'];
		    
		    $token_url = $arr['STSUrl'] . '/connect/token';
			
			$token_param_array = array(
										'client_id' => $arr['AgentCode'],
										'client_secret' => $arr['SecretKey'],
										'grant_type' => 'client_credentials'
									);
			$token_response = $this->curl_post($token_url, $token_param_array);
			if($token_response['code'] == '0')
			{
			    $token_result_array = json_decode($token_response['data'],true);
			    if(isset($token_result_array['access_token']))
				{
				    $url .= '/api/v1/agents/' . $arr['AgentCode'] . '/products/' . "SMG" . '/players/' . $row['username'] . '/bettingProfiles/181';
		            ad($url);
        		    $response = $this->curl_delete($url, "Authorization: Bearer " . $token_result_array['access_token']);
        			ad(json_decode($response['data']));
				}
			}
			    
		}
	}
	
	public function mg_set_bet_limit(){
	    $provider_code = 'MG';
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
		
		if( ! empty($game_data))
		{
		    $arr = json_decode($game_data['api_data'], TRUE);
		    $row['username'] = "sandev0011";
		    $url = $arr['APIUrl'];
		    
		    $token_url = $arr['STSUrl'] . '/connect/token';
			
			$token_param_array = array(
										'client_id' => $arr['AgentCode'],
										'client_secret' => $arr['SecretKey'],
										'grant_type' => 'client_credentials'
									);
			$token_response = $this->curl_post($token_url, $token_param_array);
			if($token_response['code'] == '0')
			{
			    $token_result_array = json_decode($token_response['data'],true);
			    if(isset($token_result_array['access_token']))
				{
				    $url .= '/api/v1/agents/' . $arr['AgentCode'] . '/products/' . "SMG" . '/players/' . $row['username'] . '/bettingProfiles/';
		            $param_array['agentCode'] = $arr['AgentCode'];
		            $param_array['productId'] = "SMG";
		            $param_array['playerId'] = $row['username'];
		            $param_array['bettingProfileId'] = 319;
        		    $response = $this->curl_post($url, $param_array,"Authorization: Bearer " . $token_result_array['access_token']);
        			ad(json_decode($response['data']));
				}
			}
			    
		}
	}
	
	public function curl_delete($url, $token){
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                       	
		curl_setopt($ch, CURLOPT_HTTPHEADER, 
			array(
				'charset=UTF-8',
				'Content-Type: application/x-www-form-urlencoded',
				$token
			)
		);   	
		$response = curl_exec($ch);
		$result['curl'] = array('error_no'=>curl_errno($ch),'error_desc'=>curl_error($ch));
		$info = curl_getinfo($ch);
		$result['http_code'] = $info['http_code'];
		if (curl_errno($ch)) 
		{
			if(curl_errno($ch) == 28){
				$result['code'] = '404';
				$result['msg'] = 'Failed';
				$result['data'] = '';

			}else{
				$result['code'] = '888';
				$result['msg'] = 'Failed';
				$result['data'] = '';
			}
		}
		else
		{
			$result['code'] = '0';
			$result['msg'] = 'Success';
			$result['data'] = $response;
		}
		
		curl_close($ch);
		
		return $result;
	}
	
	
	
	public function change_dg_bet_limit(){
        $provider_code = 'DG';
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
				->select('username,game_id')
				->where('game_provider_code', $provider_code)
				->get('player_game_accounts');
		
		if($query->num_rows() > 0)
		{
			$player_data = $query->result_array();  
		}
		
		if( ! empty($game_data) && ! empty($player_data))
		{
		    $arr = json_decode($game_data['api_data'], TRUE);
		    $url = $arr['APIUrl'];
		    $url .= '/game/updateLimit/' . $arr['AgentName'];
		    foreach($player_data as $row){
		        $random = rand(100000, 999999);
		        $key = md5($arr['AgentName'] . $arr['APIKey'] . $random);
		        $param_array = array(
					'token' => $key,
					'random' => $random,
					'data' => $arr['LimitGroup'],
					'member' => array(
					    'username' => $row['game_id'],
					),
				);
				//ad($param_array);
				$response = $this->curl_json($url, $param_array);
				ad($param_array);
				ad($response);
		    }
		}
    }
    
    public function get_icg_game_list(){
        $provider_code = 'ICG';
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
		
		if( ! empty($game_data))
		{
		    $arr = json_decode($game_data['api_data'], TRUE);
		    $token_url = $arr['APIUrl'] . '/login';
			$token_param_array = array(
										'username' => $arr['Username'],
										'password' => $arr['Password']
									);
			$token_response = $this->curl_json($token_url, $token_param_array);
			if($token_response['code'] == '0')
			{
				$token_result_array = json_decode($token_response['data'], TRUE);
				if(isset($token_result_array['token']))
				{
				    $url = $arr['APIUrl'];
					$url .= '/api/v1/games?type=all';
					ad($url);
					$response = $this->curl_get($url, "Authorization: Bearer " . $token_result_array['token']);
					$curl_array = $response['curl'];
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						ad($result_array);
					}
				}
			}
		}
    }
    
    public function get_icg_game_list2(){
        $provider_code = 'ICG';
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
		
		if( ! empty($game_data))
		{
		    $arr = json_decode($game_data['api_data'], TRUE);
		    $token_url = $arr['APIUrl'] . '/login';
			$token_param_array = array(
										'username' => $arr['Username'],
										'password' => $arr['Password']
									);
			$token_response = $this->curl_json($token_url, $token_param_array);
			if($token_response['code'] == '0')
			{
				$token_result_array = json_decode($token_response['data'], TRUE);
				if(isset($token_result_array['token']))
				{
				    $url = $arr['APIUrl'];
					$url .= '/api/v1/games/gamelink?productId='."cm00001".'&player='."ystestSA";
					ad($url);
					$response = $this->curl_get($url, "Authorization: Bearer " . $token_result_array['token']);
					$curl_array = $response['curl'];
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						ad($result_array);
					}
				}
			}
		}
    }
    
    public function testing_pgsf2_create_member(){
        $post_data['username'] = "dev003";
        
        $this->load->library('guid');
        $guid = $this->guid->get_token();
        $game_data['api_data'] = '{"APIUrl":"https://api.pg-bo.me/external/","OperatorToken":"274043c459443f9c1b12232cfff3cac4","SecretKey":"279bc7c07245efc077a2f8d27ed25f2c","Currency":"CNY"}';
        $arr = json_decode($game_data['api_data'], TRUE);
        $url = $arr['APIUrl'];
        $url .= 'Player/v1/Create?'.$guid;
        
        $param_array = array(
            "operator_token" => $arr['OperatorToken'],
            "secret_key" => $arr['SecretKey'],
            "player_name" => $post_data['username'],
            "nickname" => $post_data['username'],
            "currency" => $arr['Currency'],
        );
        
        $response = $this->curl_post($url, $param_array);
        ad($response);
        if($response['code'] == '0')
		{
		    $result_array = json_decode($response['data'], TRUE);
		    ad($result_array);
		    if(isset($result_array['data']['action_result']) && $result_array['data']['action_result'] == '1'){
		        echo "success";
		    }else{
		        echo "faled";
		    }
		    
		}
    }
    
    public function testing_pgsf2_member_balance(){
        $post_data['username'] = "dev004";
        
        $this->load->library('guid');
        $guid = $this->guid->get_token();
        $game_data['api_data'] = '{"APIUrl":"https://api.pg-bo.me/external/","OperatorToken":"274043c459443f9c1b12232cfff3cac4","SecretKey":"279bc7c07245efc077a2f8d27ed25f2c","Currency":"CNY"}';
        $arr = json_decode($game_data['api_data'], TRUE);
        $url = $arr['APIUrl'];
        $url .= 'Cash/v3/GetPlayerWallet?'.$guid;
        
        $param_array = array(
            "operator_token" => $arr['OperatorToken'],
            "secret_key" => $arr['SecretKey'],
            "player_name" => $post_data['username'],
        );
        
        $response = $this->curl_post($url, $param_array);
        ad($response);
        if($response['code'] == '0')
		{
		    $result_array = json_decode($response['data'], TRUE);
		    ad($result_array);
		    if(isset($result_array['data']['action_result']) && $result_array['data']['action_result'] == '1'){
		        echo "success";
		    }else{
		        echo "faled";
		    }
		    
		}
    }
    
    public function testing_pgsf2_member_transfer(){
        $post_data['username'] = "dev003";
        $post_data['amount'] = -1000;
        $post_data['order_id'] = "1234567890";
        
        $requestOrderID = $post_data['order_id'];
		$requestOrderIDAlias = "";
        
        $this->load->library('guid');
        $guid = $this->guid->get_token();
        $game_data['api_data'] = '{"APIUrl":"https://api.pg-bo.me/external/","OperatorToken":"274043c459443f9c1b12232cfff3cac4","SecretKey":"279bc7c07245efc077a2f8d27ed25f2c","Currency":"CNY"}';
        $arr = json_decode($game_data['api_data'], TRUE);
        $url = $arr['APIUrl'];
        $requestOrderIDAlias = $post_data['order_id'];

		if($post_data['amount'] > 0) 
		{
		    if(in_array($arr['Currency'],$currency_one)){
				$amount = bcdiv($post_data['amount'] / 1000,1,2);
			}else{
				$amount = $post_data['amount'];
			}
		}
		else
		{
			if(in_array($arr['Currency'],$currency_one)){
				$amount = bcdiv(($post_data['amount'] * -1 / 1000), 1, 2);
			}else{
				$amount = bcdiv(($post_data['amount'] * -1), 1, 2);
			}
		}
		
		if($post_data['amount'] > 0) 
		{
			$url .= 'Cash/v3/TransferIn?'.$guid;
			$param_array = array(
	            "operator_token" => $arr['OperatorToken'],
	            "secret_key" => $arr['SecretKey'],
	            "player_name" => $post_data['username'],
	            'amount' => $amount,
	            "transfer_reference" => $requestOrderIDAlias,
	            "currency" => $arr['Currency'],
	        );
		}
		else
		{
			$url .= 'Cash/v3/TransferOut?'.$guid;
			$param_array = array(
	            "operator_token" => $arr['OperatorToken'],
	            "secret_key" => $arr['SecretKey'],
	            "player_name" => $post_data['username'],
	            'amount' => $amount,
	            "transfer_reference" => $requestOrderIDAlias,
	            "currency" => $arr['Currency'],
	        );
		}
		ad($url);
		ad($param_array);
        
        $response = $this->curl_post($url, $param_array);
        ad($response);
        if($response['code'] == '0')
		{
		    $result_array = json_decode($response['data'], TRUE);
		    ad($result_array);
		    if(isset($result_array['data']['action_result']) && $result_array['data']['action_result'] == '1'){
		        echo "success";
		    }else{
		        echo "faled";
		    }
		    
		}
    }
    
    public function testing_pgsf_member_history(){
        $member_lists = array();
        $provider_code = "PGS2";
        $start_time = strtotime("2022-04-25 10:45:00");
        $end_time = strtotime("2022-04-25 10:50:00");
        $this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$timestamp = str_pad($aes->getMillisecond(), 13, 0);
		$start_date = str_pad($start_time, 13, 0);
		$end_date = str_pad($end_time, 13, 0);
		$this->load->library('guid');
        $guid = $this->guid->get_token();
		$game_data['api_data'] = '{"APIUrl":"https://api.pg-bo.me/external/","ForwardUrl":"https://m.pg-redirect.net/","ReportUrl":"https://api.pg-bo.me/external-datagrabber/","OperatorToken":"274043c459443f9c1b12232cfff3cac4","SecretKey":"279bc7c07245efc077a2f8d27ed25f2c","Currency":"CNY"}';
        $arr = json_decode($game_data['api_data'], TRUE);
		
		$this->load->library('guid');
        $guid = $this->guid->get_token();
        
		$url = $arr['ReportUrl'];
		$url .= 'Bet/v4/GetHistoryForSpecificTimeRange?'.$guid;
		
		$param_array = array(
            "operator_token" => $arr['OperatorToken'],
            "secret_key" => $arr['SecretKey'],
            "count" => 5000,
            "bet_type" => 1,
            "from_time" => $start_time."000",
            'to_time' => $end_time."000"
        );
        ad($param_array);
        //exit;
        $response = $this->curl_post($url, $param_array);
        ad($response);
        if($response['code'] == '0')
		{
		    $result_array = json_decode($response['data'], TRUE);
		    if(array_key_exists('error',$result_array) && $result_array['error'] == null){
		        $DBdata['resp_data'] = json_encode($result_array);
		        ad($DBdata);
				$DBdata['sync_status'] = STATUS_YES;
				if(isset($result_array['data'])){
					if(sizeof($result_array['data'])){
						foreach($result_array['data']  as $result_row){
							$tmp_username = strtolower(trim($result_row['playerName']));
							$exact_username = $tmp_username;//((substr($tmp_username, 0, strlen($sys_data['system_prefix'])) == $sys_data['system_prefix']) ? substr($tmp_username, strlen($sys_data['system_prefix'])) : $tmp_username);
							
							if($result_row['transactionType'] == 1){
							    $game_round_type = GAME_ROUND_TYPE_GAME_ROUND;
							}else if($result_row['transactionType'] == 2){
							    $game_round_type = GAME_ROUND_TYPE_JACKPOT;
							}else if($result_row['transactionType'] == 3){
							    $game_round_type = GAME_ROUND_TYPE_FREE_SPIN;
							}else{
							    $game_round_type = GAME_ROUND_TYPE_TIP;
							}

							$PBdata = array(
						        'game_provider_code' => $provider_code,
						        'game_type_code' => GAME_SLOTS,
						        'game_provider_type_code' => $provider_code . "_" . GAME_SLOTS,
						        'game_result_type' => $result_type,
						        'game_code' => trim($result_row['gameId']),
						        'game_real_code' => trim($result_row['gameId']),
						        'bet_id' => trim($result_row['betId']),
						        'bet_ref_no' => trim($result_row['parentBetId']),
						        'bet_time' => (int) (trim($result_row['betTime'])/1000),
						        'game_time' => (int) (trim($result_row['betEndTime'])/1000),
						        'report_time' => (int) (trim($result_row['rowVersion'])/1000),
						        'bet_amount' => trim($result_row['betAmount']),
								'bet_amount_valid' => trim($result_row['betAmount']),
						        'payout_amount' => 0,
						        'promotion_amount' => 0,
						        'payout_time' => (int) (trim($result_row['betEndTime'])/1000),
						        'sattle_time' => (int) (trim($result_row['betEndTime'])/1000),
								'compare_time' => (int) (trim($result_row['betEndTime'])/1000),
								'created_date' => time(),
						        'win_loss' =>  trim($result_row['winAmount'])-trim($result_row['betAmount']),
						        'game_round_type' => $game_round_type,
						        'status' => STATUS_COMPLETE,
						        'game_username' => $result_row['playerName'],
						        'player_id' => $member_lists[$exact_username],
						    );

							if($status == STATUS_COMPLETE){
								$PBdata['payout_amount'] = trim($result_row['winAmount']);
								if($PBdata['win_loss'] != 0){
									$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
								}
							}
							//ad($result_row);
                            ad($PBdata);
						}
					}
				}
			}
		}
    }
    
    public function set_super_sport_bet_limit(){
	    $provider_code = 'SPSB';
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
				->select('game_id')
				->where('game_provider_code', $provider_code)
				->get('player_game_accounts');
		
		if($query->num_rows() > 0)
		{
			$player_data = $query->result_array();  
		}
		if( ! empty($game_data) && ! empty($player_data))
		{
		    $arr = json_decode($game_data['api_data'], TRUE);
		    $url = $arr['APIUrl'];
		    $url .= "api/account";
		    $str = '';
		    $this->load->library('aes_ecb');
    		$aes = new Aes_ecb();
    		$aes->set_mode(MCRYPT_MODE_CBC);
    		$aes->set_iv($arr['IVkey']);
    		$aes->set_key($arr['Deskey']);
    		$aes->require_pkcs5();
		    foreach($player_data as $row){
		        if($row['game_id'] != $arr['CopyTarget']){
		            $param_array = array(
            			"act" => "cpSettings",
            			"up_account" => $arr['UpAccount'],
            			"up_passwd" => $arr['UpPassword'],
            			"account" => $row['game_id'],
            			"level" => 1,
            			"copy_target" => $arr['CopyTarget'],
            		);
            		$param_array['up_account'] = $aes->encrypt($param_array['up_account']);
            		$param_array['up_passwd'] = $aes->encrypt($param_array['up_passwd']);
            		$param_array['account'] = $aes->encrypt($param_array['account']);
            		
            		$response = $this->curl_post($url, $param_array);
            		ad($response);
		        }
		    }
		    
		}
	}
	
	public function set_super_lottery_bet_limit(){
	    $provider_code = 'SPLT';
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
				->select('game_id')
				->where('game_provider_code', $provider_code)
				->get('player_game_accounts');
		
		if($query->num_rows() > 0)
		{
			$player_data = $query->result_array();  
		}
		if( ! empty($game_data) && ! empty($player_data))
		{
		    $arr = json_decode($game_data['api_data'], TRUE);
		    $url = $arr['APIUrl'];
		    $url .= "api_101/account";
		    $str = '';
		    $this->load->library('aes_ecb');
    		$aes = new Aes_ecb();
    		$aes->set_mode(MCRYPT_MODE_CBC);
    		$aes->set_iv($arr['IVkey']);
    		$aes->set_key($arr['Deskey']);
    		$aes->require_pkcs5();
		    foreach($player_data as $row){
		        if($row['game_id'] != $arr['CopyTarget']){
		            $param_array = array(
            			"act" => "copy_settings",
            			"up_acc" => $arr['UpAccount'],
            			"up_pwd" => $arr['UpPassword'],
            			"account" => $row['game_id'],
            			"copy_target" => $arr['CopyTarget'],
            		);
            		$param_array['up_acc'] = $aes->encrypt($param_array['up_acc']);
            		$param_array['up_pwd'] = $aes->encrypt($param_array['up_pwd']);
            		$param_array['account'] = $aes->encrypt($param_array['account']);
            		
            		$response = $this->curl_post($url, $param_array);
            		ad($response);
		        }
		    }
		    
		}
	}
}