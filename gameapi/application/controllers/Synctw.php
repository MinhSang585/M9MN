<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Synctw extends MY_Controller {
	var $player_winloss_list = array();
	public function __construct() {
		parent::__construct();
		$this->load->library('rng');
	}
	public function all($index = NULL) {
		####REMOVE GAME RESULT LOG MORE THAN 3 day###		
		$deltime = strtotime('-3 day');		
		$this->db->where('sync_time <=', $deltime);		
		$this->db->delete('game_result_logs');		
		#############################################				
		####REMOVE API LOG MORE THAN 3 day###		
		$this->db->where('log_date <=', $deltime);		
		$this->db->delete('api_logs');
		$this->db->where('log_date <=', $deltime);		
		$this->db->delete('game_api_logs');		
		#####################################
		exit;
		if($index == 1){
			$member_lists = $this->player_model->get_player_list_array_by_provider(array("HB","RTG","SBO"));
			//$this->hb($member_lists["HB"]);
			$this->rtg($member_lists["RTG"]);
			//$this->sbo_sb($member_lists["SBO"]);
			//$this->sbo_vs($member_lists["SBO"]);
			//$this->sbo_games($member_lists["SBO"]);
			//$this->hb($member_lists["HB"]);
			$this->rtg($member_lists["RTG"]);
			//$this->sbo_sb($member_lists["SBO"]);
			//$this->sbo_vs($member_lists["SBO"]);
			//$this->sbo_games($member_lists["SBO"]);
			//$this->hb($member_lists["HB"]);
			$this->rtg($member_lists["RTG"]);
			//$this->sbo_sb($member_lists["SBO"]);
			//$this->sbo_vs($member_lists["SBO"]);
			//$this->sbo_games($member_lists["SBO"]);
		}else if($index == 2){
			$member_lists = $this->player_model->get_player_list_array_by_provider(array("JK","AB","CMD","CQ9","DT"));
			//$this->jk($member_lists["JK"]);
			$this->ab($member_lists["AB"]);
			//$this->cmd($member_lists["CMD"]);
			//$this->cq9($member_lists["CQ9"]);
			$this->dt($member_lists["DT"]);
			//$this->jk($member_lists["JK"]);
			$this->ab($member_lists["AB"]);
			//$this->cmd($member_lists["CMD"]);
			//$this->cq9($member_lists["CQ9"]);
			$this->dt($member_lists["DT"]);
			//$this->jk($member_lists["JK"]);
			$this->ab($member_lists["AB"]);
			//$this->cmd($member_lists["CMD"]);
			//$this->cq9($member_lists["CQ9"]);
			$this->dt($member_lists["DT"]);
		}else if($index == 3){
		    $member_lists = $this->player_model->get_player_list_array_by_provider(array("BBIN","SPLT"));
			//$member_lists = $this->player_model->get_player_list_array_by_provider(array("BBIN"));
			//$this->bbin($member_lists["BBIN"]);
			//$this->bbin_sl($member_lists["BBIN"],1);
			//$this->bbin_sl($member_lists["BBIN"],2);
			//$this->bbin_sl($member_lists["BBIN"],3);
			//$this->bbin_sl($member_lists["BBIN"],5);
			//$this->bbin($member_lists);
			//$this->bbin_sl($member_lists["BBIN"],1);
			//$this->bbin_sl($member_lists["BBIN"],2);
			//$this->bbin_sl($member_lists["BBIN"],3);
			//$this->bbin_sl($member_lists["BBIN"],5);
			//$this->bbin($member_lists);
			//$this->bbin_sl($member_lists["BBIN"],1);
			//$this->bbin_sl($member_lists["BBIN"],2);
			//$this->bbin_sl($member_lists["BBIN"],3);
			//$this->bbin_sl($member_lists["BBIN"],5);
			$this->splt($member_lists["SPLT"],11);
			$this->splt($member_lists["SPLT"],12);
			$this->splt($member_lists["SPLT"],13);
			$this->splt($member_lists["SPLT"],22);
			$this->splt($member_lists["SPLT"],11);
			$this->splt($member_lists["SPLT"],12);
			$this->splt($member_lists["SPLT"],13);
			$this->splt($member_lists["SPLT"],22);
		}else if($index == 4){
			$member_lists = $this->player_model->get_player_list_array_by_provider(array("DG","EB","EVO","EVOP","IBC"));
			$this->dg($member_lists["DG"]);
			//$this->eb($member_lists["EB"]);
			//$this->evo($member_lists["EVO"]);
			$this->evoplay($member_lists["EVOP"]);
			//$this->ibc($member_lists["IBC"]);
			$this->dg($member_lists["DG"]);
			//$this->eb($member_lists["EB"]);
			//$this->evo($member_lists["EVO"]);
			$this->evoplay($member_lists["EVOP"]);
			//$this->ibc($member_lists["IBC"]);
			$this->dg($member_lists["DG"]);
			//$this->eb($member_lists["EB"]);
			//$this->evo($member_lists["EVO"]);
			$this->evoplay($member_lists["EVOP"]);
			//$this->ibc($member_lists["IBC"]);
		}else if($index == 5){
			$member_lists = $this->player_model->get_player_list_array_by_provider(array("ICG","JDB","LE","LH"));
			$this->icg($member_lists["ICG"]);
			//$this->jdb($member_lists["JDB"]);
			//$this->jdb_backup($member_lists["JDB"]);
			//$this->le($member_lists["LE"]);
			//$this->lh($member_lists["LH"]);
			$this->icg($member_lists["ICG"]);
			//$this->jdb($member_lists["JDB"]);
			//$this->jdb_backup($member_lists["JDB"]);
			//$this->le($member_lists["LE"]);
			//$this->lh($member_lists["LH"]);
			$this->icg($member_lists["ICG"]);
			//$this->jdb($member_lists["JDB"]);
			//$this->jdb_backup($member_lists["JDB"]);
			//$this->le($member_lists["LE"]);
			//$this->lh($member_lists["LH"]);
		}else if($index == 6){
			//$member_lists = $this->player_model->get_player_list_array_by_provider(array("MG","PGSF","PP","PT"));
			//$this->mg($member_lists["MG"]);
			//$this->pgsoft($member_lists["PGSF"]);
			//$this->pp($member_lists["PP"]);
			//$this->pp_lc($member_lists["PP"]);
			//$this->pt2($member_lists["PT"]);
			//$this->mg($member_lists["MG"]);
			//$this->pgsoft($member_lists["PGSF"]);
			//$this->pp($member_lists["PP"]);
			//$this->pp_lc($member_lists["PP"]);
			//$this->pt2($member_lists["PT"]);
			//$this->mg($member_lists["MG"]);
			//$this->pgsoft($member_lists["PGSF"]);
			//$this->pp($member_lists["PP"]);
			//$this->pp_lc($member_lists["PP"]);
			//$this->pt2($member_lists["PT"]);
		}else if($index == 7){
			$member_lists = $this->player_model->get_player_list_array_by_provider(array("SA","SG","SP","VIA"));
			$this->sa($member_lists["SA"]);
			//$this->sg($member_lists["SG"]);
			$this->sp($member_lists["SP"]);
			//$this->via($member_lists["VIA"]);
			sleep(3);
			$this->sa($member_lists["SA"]);
			//$this->sg($member_lists["SG"]);
			$this->sp($member_lists["SP"]);
			//$this->via($member_lists["VIA"]);
			sleep(3);
			$this->sa($member_lists["SA"]);
			//$this->sg($member_lists["SG"]);
			$this->sp($member_lists["SP"]);
			//$this->via($member_lists["VIA"]);
				$this->sa($member_lists["SA"]);
			//$this->sg($member_lists["SG"]);
			$this->sp($member_lists["SP"]);
			//$this->via($member_lists["VIA"]);
			sleep(3);
				$this->sa($member_lists["SA"]);
			//$this->sg($member_lists["SG"]);
			$this->sp($member_lists["SP"]);
			//$this->via($member_lists["VIA"]);
			sleep(3);
				$this->sa($member_lists["SA"]);
			//$this->sg($member_lists["SG"]);
			$this->sp($member_lists["SP"]);
			//$this->via($member_lists["VIA"]);
			sleep(3);
		}else if($index == 8){
			//$member_lists = $this->player_model->get_player_list_array_by_provider(array("SX","SXJL","SXRT","SXYL","SXKM","SXBG","SXVN","SXES"));
			//$this->sx($member_lists["SX"]);
			//sleep(3);
			//$this->sxjl($member_lists["SXJL"]);
			//sleep(3);
			//$this->sxrt($member_lists["SXRT"]);
			//sleep(3);
			//$this->sxyl($member_lists["SXYL"]);
			//sleep(3);
			//$this->sxkm($member_lists["SXKM"]);
			//sleep(3);
			//$this->sxbg($member_lists["SXBG"]);
			//sleep(3);
			//$this->sxvn($member_lists["SXVN"]);
			//sleep(3);
			//$this->sxes($member_lists["SXES"]);
			//sleep(3);
			//$this->sx($member_lists["SX"]);
			//sleep(3);
			//$this->sxjl($member_lists["SXJL"]);
			//sleep(3);
			//$this->sxrt($member_lists["SXRT"]);
			//sleep(3);
			//$this->sxyl($member_lists["SXYL"]);
			//sleep(3);
			//$this->sxkm($member_lists["SXKM"]);
			//sleep(3);
			//$this->sxbg($member_lists["SXBG"]);
			//sleep(3);
			//$this->sxvn($member_lists["SXVN"]);
			//sleep(3);
			//$this->sxes($member_lists["SXES"]);
			//sleep(3);
			//$this->sx($member_lists["SX"]);
			//sleep(3);
			//$this->sxjl($member_lists["SXJL"]);
			//sleep(3);
			//$this->sxrt($member_lists["SXRT"]);
			//sleep(3);
			//$this->sxyl($member_lists["SXYL"]);
			//sleep(3);
			//$this->sxkm($member_lists["SXKM"]);
			//sleep(3);
			//$this->sxbg($member_lists["SXBG"]);
			//sleep(3);
			//$this->sxvn($member_lists["SXVN"]);
			//sleep(3);
			//$this->sxes($member_lists["SXES"]);
			//sleep(3);
		}else if($index == 9){
			//$member_lists = $this->player_model->get_player_list_array_by_provider(array("SX","SXJL","SXRT","SXYL","SXKM","SXBG","SXVN","SXES"));
			//$this->sexy_secure($member_lists["SX"]);
			//sleep(3);
			//$this->sxjl_secure($member_lists["SXJL"]);
			//sleep(3);
			//$this->sxrt_secure($member_lists["SXRT"]);
			//sleep(3);
			//$this->sxyl_secure($member_lists["SXYL"]);
			//sleep(3);
			//$this->sxkm_secure($member_lists["SXKM"]);
			//sleep(3);
			//$this->sxbg_secure($member_lists["SXBG"]);
			//sleep(3);
			//$this->sxvn_secure($member_lists["SXVN"]);
			//sleep(3);
			//$this->sxes_secure($member_lists["SXES"]);
			//sleep(3);
			//$this->sexy_secure($member_lists["SX"]);
			//sleep(3);
			//$this->sxjl_secure($member_lists["SXJL"]);
			//sleep(3);
			//$this->sxrt_secure($member_lists["SXRT"]);
			//sleep(3);
			//$this->sxyl_secure($member_lists["SXYL"]);
			//sleep(3);
			//$this->sxkm_secure($member_lists["SXKM"]);
			//sleep(3);
			//$this->sxbg_secure($member_lists["SXBG"]);
			//sleep(3);
			//$this->sxvn_secure($member_lists["SXVN"]);
			//sleep(3);
			//$this->sxes_secure($member_lists["SXES"]);
			//sleep(3);
		}else if($index == 10){
			$member_lists = $this->player_model->get_player_list_array_by_provider(array("WM"));
			$this->wm($member_lists["WM"]);
			sleep(10);
			$this->wm($member_lists["WM"]);
			sleep(10);
			$this->wm($member_lists["WM"]);
			sleep(10);
		}else if($index == 11){
			//$member_lists = $this->player_model->get_player_list_array_by_provider(array("SX","SXJL","SXRT","SXYL","SXKM","SXBG","SXVN","SXES"));
			//$this->sexy_backup($member_lists["SX"]);
			//sleep(3);
			//$this->sxjl_backup($member_lists["SXJL"]);
			//sleep(3);
			//$this->sxrt_backup($member_lists["SXRT"]);
			//sleep(3);
			//$this->sxyl_backup($member_lists["SXYL"]);
			//sleep(3);
			//$this->sxkm_backup($member_lists["SXKM"]);
			//sleep(3);
			//$this->sxbg_backup($member_lists["SXBG"]);
			//sleep(3);
			//$this->sxvn_backup($member_lists["SXVN"]);
			//sleep(3);
			//$this->sxes_backup($member_lists["SXES"]);
		}else if($index == 12){
			$member_lists = $this->player_model->get_player_list_array_by_provider(array("KY","EA","N2","OBSB","OG"));
		    //$this->ky($member_lists["KY"]);
		    //$this->ea($member_lists["EA"]);
		    //$this->n2($member_lists["N2"]);
		    $this->obsb_bet($member_lists["OBSB"]);
		    $this->obsb($member_lists["OBSB"]);
		    $this->og($member_lists["OG"]);
		    //$this->ky($member_lists["KY"]);
		    //$this->ea($member_lists["EA"]);
		    //$this->n2($member_lists["N2"]);
		    $this->obsb_bet($member_lists["OBSB"]);
		    $this->obsb($member_lists["OBSB"]);
		    $this->og($member_lists["OG"]);
		    //$this->ky($member_lists["KY"]);
		    //$this->ea($member_lists["EA"]);
		    //$this->n2($member_lists["N2"]);
		    $this->obsb_bet($member_lists["OBSB"]);
		    $this->obsb($member_lists["OBSB"]);
		    $this->og($member_lists["OG"]);
		    $this->obsb_bet($member_lists["OBSB"]);
		    $this->obsb($member_lists["OBSB"]);
		    $this->og($member_lists["OG"]);
		    $this->obsb_bet($member_lists["OBSB"]);
		    $this->obsb($member_lists["OBSB"]);
		    $this->og($member_lists["OG"]);
		    $this->obsb_bet($member_lists["OBSB"]);
		    $this->obsb($member_lists["OBSB"]);
		    $this->og($member_lists["OG"]);
		}else if($index == 13){
			$member_lists = $this->player_model->get_player_list_array_by_provider(array("NK","BNG","SPSB"));
		    $this->ninek($member_lists["NK"]);
		    $this->bng($member_lists["BNG"]);
		    $this->ninek($member_lists["NK"]);
		    $this->bng($member_lists["BNG"]);
		    $this->ninek($member_lists["NK"]);
		    $this->bng($member_lists["BNG"]);
		    $this->spsb2($member_lists["SPSB"]);
		    sleep(2);
		    $this->spsb2($member_lists["SPSB"]);
		    sleep(2);
		    $this->spsb2($member_lists["SPSB"]);
		    sleep(2);
		}else if($index == 14){
			$member_lists = $this->player_model->get_player_list_array_by_provider(array("BL","RSG","GFGD"));
			$this->bl($member_lists["BL"]);
            $this->rsg($member_lists["RSG"],GAME_SLOTS);
		    $this->rsg($member_lists["RSG"],GAME_FISHING);
		    $this->gfgd($member_lists["GFGD"]);
			$this->bl($member_lists["BL"]);
            $this->rsg($member_lists["RSG"],GAME_SLOTS);
		    $this->rsg($member_lists["RSG"],GAME_FISHING);
		    $this->gfgd($member_lists["GFGD"]);
			$this->bl($member_lists["BL"]);
            $this->rsg($member_lists["RSG"],GAME_SLOTS);
		    $this->rsg($member_lists["RSG"],GAME_FISHING);
		    $this->gfgd($member_lists["GFGD"]);
		}else if($index == 15){
		    $member_lists = $this->player_model->get_player_list_array_by_provider(array("YGG","GR","NAGA"));
		    //$this->ygg($member_lists["YGG"]);
		    $this->gr($member_lists["GR"],GAME_SLOTS);
		    $this->gr($member_lists["GR"],GAME_FISHING);
		    $this->gr($member_lists["GR"],GAME_BOARD_GAME);
		    $this->gr($member_lists["GR"],GAME_OTHERS);
		    $this->naga($member_lists["NAGA"]);
		    //$this->ygg($member_lists["YGG"]);
		    $this->gr($member_lists["GR"],GAME_SLOTS);
		    $this->gr($member_lists["GR"],GAME_FISHING);
		    $this->gr($member_lists["GR"],GAME_BOARD_GAME);
		    $this->gr($member_lists["GR"],GAME_OTHERS);
		    $this->naga($member_lists["NAGA"]);
		    //$this->ygg($member_lists["YGG"]);
		    $this->gr($member_lists["GR"],GAME_SLOTS);
		    $this->gr($member_lists["GR"],GAME_FISHING);
		    $this->gr($member_lists["GR"],GAME_BOARD_GAME);
		    $this->gr($member_lists["GR"],GAME_OTHERS);
		    $this->naga($member_lists["NAGA"]);
		    //$this->ygg($member_lists["YGG"]);
		    $this->gr($member_lists["GR"],GAME_SLOTS);
		    $this->gr($member_lists["GR"],GAME_FISHING);
		    $this->gr($member_lists["GR"],GAME_BOARD_GAME);
		    $this->gr($member_lists["GR"],GAME_OTHERS);
		    $this->naga($member_lists["NAGA"]);
		    //$this->ygg($member_lists["YGG"]);
		    $this->gr($member_lists["GR"],GAME_SLOTS);
		    $this->gr($member_lists["GR"],GAME_FISHING);
		    $this->gr($member_lists["GR"],GAME_BOARD_GAME);
		    $this->gr($member_lists["GR"],GAME_OTHERS);
		    $this->naga($member_lists["NAGA"]);
		}
	}
	public function allb($index = NULL) {
		if($index == 1){
			$member_lists = $this->player_model->get_player_list_array_by_provider(array("FTG","DS88","T1G"));
			$this->ftg($member_lists["FTG"]);
			$this->t1g($member_lists["T1G"]);
			$this->ds88($member_lists["DS88"]);
			$this->ftg($member_lists["FTG"]);
			$this->t1g($member_lists["T1G"]);
			$this->ftg($member_lists["FTG"]);
			$this->ds88($member_lists["DS88"]);
			$this->t1g($member_lists["T1G"]);
		}else if($index == 2){
		    $member_lists = $this->player_model->get_player_list_array_by_provider(array("NE","DGG"));
	        $this->ne($member_lists["NE"]);
	        $this->dgg($member_lists["DGG"]);
	        sleep(11);
	        $this->ne($member_lists["NE"]);
	        $this->dgg($member_lists["DGG"]);
	        sleep(11);
	        $this->ne($member_lists["NE"]);
	        $this->dgg($member_lists["DGG"]);
		}
	}
	public function ftg($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'FTG';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				$game_code_data = array();
    	        $game_type_code = "";
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-30 minutes' ,$start_time);
					$db_record_end_time = strtotime('+30 minutes' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
                    $game_list_response = $this->ftg_connect($arr, $start_time, $end_time,"GAME" ,$page_id);
                    if($game_list_response['code'] == '0')
        			{
        			    $game_list_result_array = json_decode($game_list_response['data'], TRUE);
        			    if(isset($game_list_result_array['games']) && !empty($game_list_result_array['games']))
        				{
        				    foreach($game_list_result_array['games'] as $game_list_result_array_row){
        				        switch($game_list_result_array_row['category'])
                    			{
                    				case 'fishing': $game_type_code = GAME_FISHING; break;
                    				case 'slots': $game_type_code = GAME_SLOTS; break;
                    				default: $game_type_code = GAME_BOARD_GAME; break;
                    			}
        				        $game_code_data[$game_list_result_array_row['id']] = $game_type_code;
        				    }
        				    $is_loop = TRUE;
        					while($is_loop == TRUE){
        						$Bdata = array();
        						$BUdata = array();
        						$BUIDdata = array();
        						$BUDdata = array();
        						$DBdata['sync_status'] = STATUS_NO;
        						$DBdata['page_id'] = $page_id;
        						$DBdata['resp_data'] = '';
        						$response = $this->ftg_connect($arr, $start_time, $end_time,"BET" ,$page_id);
        						if($response['code'] == '0')
        						{
        							$result_array = json_decode($response['data'], TRUE);
        							if( ! empty($result_array))
        							{
        								if(isset($result_array['row_number']))
						                {
        									$DBdata['resp_data'] = json_encode($result_array);
        									$DBdata['sync_status'] = STATUS_YES;
        									if(sizeof($result_array['rows'])>0){
        										if($is_retrieve == FALSE){
        											$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
        											$is_retrieve = TRUE;
        										}
        										foreach($result_array['rows'] as $result_row){
        											$tmp_username = strtolower(trim($result_row['username']));
                								    $exact_username = $tmp_username;
                								    if($result_row['result'] == "W" || $result_row['result'] == "L"){
                										$status = STATUS_COMPLETE;
                									}else if($result_row['result'] == "C"){
                										$status = STATUS_CANCEL;
                									}else{
                										$status = STATUS_PENDING;
                									}
                									$PBdata = array(
                    							        'game_provider_code' => $provider_code,
                    							        'game_type_code' => (isset($game_code_data[trim($result_row['game_id'])]) ? $game_code_data[trim($result_row['game_id'])] : GAME_BOARD_GAME),
                    							        'game_provider_type_code' => $provider_code."_".(isset($game_code_data[trim($result_row['game_id'])]) ? $game_code_data[trim($result_row['game_id'])] : GAME_BOARD_GAME),
                    							        'game_result_type' => $result_type,
                    							        'game_code' => trim($result_row['game_id']),
                    							        'game_real_code' => trim($result_row['game_id']),
                    							        'bet_id' => trim($result_row['id']),
                    							        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['bet_at']))),
                    							        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['bet_at']))),
                    					       			'report_time' => strtotime('+0 hours', strtotime(trim($result_row['bet_at']))),
                    							        'bet_amount' => trim($result_row['bet_amount']),
                    							        'bet_amount_valid' => trim($result_row['commissionable']),
                    							        'payout_amount' => trim($result_row['payoff']),
                    							        'promotion_amount' => trim($result_row['commissionable']),
                    							        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['modified_at']))),
                    							        'sattle_time' => strtotime('+0 hours', strtotime(trim($result_row['payoff_at']))),
                    							        'win_loss' => trim($result_row['profit']),
                    							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                    							        'status' => $status,
                    							        'game_username' => trim($result_row['username']),
                    							        'player_id' => $member_lists[$exact_username],
                    							    );
                    							    if($PBdata['win_loss'] == 0){
                									    $PBdata['promotion_amount'] = 0;
                    							    }
        										    if( ! in_array($PBdata['bet_id'], $transaction_lists))
        											{					
        												$PBdata['bet_info'] = json_encode($result_row);
        										        $PBdata['insert_type'] = SYNC_DEFAULT;
        												array_push($Bdata, $PBdata);
        												if($PBdata['status'] == STATUS_COMPLETE){
            												$PBdataWL = array(
            													'player_id' => $PBdata['player_id'],
            													'game_code' => $PBdata['game_code'],
            													'bet_time' => $PBdata['bet_time'],
            													'payout_time' => $PBdata['payout_time'],
            													'game_provider_code' => $PBdata['game_provider_code'],
            													'game_type_code' => $PBdata['game_type_code'],
            													'total_bet' => 1,
            													'bet_amount' => $PBdata['bet_amount'],
            													'bet_amount_valid' => $PBdata['bet_amount_valid'],
            													'win_loss' => $PBdata['win_loss'],
            												);
            												array_push($BUDdata, $PBdataWL);
            											}
        											}
        										}
        									}else{
        										$is_loop = FALSE;
        									}
        									$page_id++;
        								}else{
        									$is_loop = FALSE;
        								}
        							}else{
        								$is_loop = FALSE;
        							}
        						}else{
        							$is_loop = FALSE;
        						}
        						$this->db->insert('game_result_logs', $DBdata);
        						$result_promotion_reset = array('promotion_amount' => 0);
        						if( ! empty($Bdata))
        						{
        							$this->db->insert_batch('transaction_report', $Bdata);
        						}
        						if( ! empty($BUDdata))
        						{
        							$this->db->insert_batch('win_loss_logs', $BUDdata);
        						}
        						sleep(5);
        					}
        				}
        			}
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	private function ftg_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $type = NULL, $page_id = NULL){
		//Request Response gmt+8
		$this->load->helper('jwt');
		if($type == "BET"){
		    $start_date = date('c', $start_time);
	        $end_date = date('c', $end_time);
		    $url = $arr['APIUrl'];
            $url .= "/api/v2/wagers/outside/list";
            $param_array = array(
    		    'lobby_id' => $arr['LobbyID'],
    		    'date_type' => '2',
    		    'begin_at' => $start_date,
    		    'end_at' => $end_date,
    		    'page' => $page_id,
    		    'row_number' => 5000,
    		    'client_id' => $arr['ClientID'],
    		    'invite_code' => $arr['InviteCode'],
    		);
    		$jwt = new JWT();
    		$param_array['iat'] = (int)microtime(true);
    		$json = json_encode($param_array);
    		$jwt_token = $jwt->encode($json, $arr['APIKey'], 'HS256');
    	    $response = $this->curl_get($url."?" . http_build_query($param_array), "Authorization: Bearer " . $jwt_token);
		}else{
		    $url = $arr['APIUrl'];
	        $url .= "/api/v2/game/outside/list";
	        $param_array = array(
    		    'client_id' => $arr['ClientID'],
    		);
    		$jwt = new JWT();
    		$param_array['iat'] = (int)microtime(true);
    		$json = json_encode($param_array);
    		$jwt_token = $jwt->encode($json, $arr['APIKey'], 'HS256');
    		$response = $this->curl_get($url."?" . http_build_query($param_array), "Authorization: Bearer " . $jwt_token);
		}
		return $response;
	}
    public function ds88($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'DS88';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-15 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-70 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				$game_code_data = array();
    	        $game_type_code = "";
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-10 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-30 minutes' ,$start_time);
					$db_record_end_time = strtotime('+30 minutes' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
                    $is_loop = TRUE;
					while($is_loop == TRUE){
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUdata = array();
							$BUIDdata = array();
							$BUDdata = array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->ds88_connect($arr, $start_time, $end_time, $page_id);
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
									if(isset($result_array['code']) && ($result_array['code'] == "OK"))
					                {
										$DBdata['resp_data'] = json_encode($result_array);
										$DBdata['sync_status'] = STATUS_YES;
										$page_total = trim($result_array['total_page']);
										if(sizeof($result_array['data'])>0){
					                        foreach($result_array['data'] as $result_row){
												$tmp_username = strtolower(trim($result_row['account']));
                							    $exact_username = $tmp_username;
                							    $payout_amount = 0;
                							    if($result_row['is_settled'] == 1){
                							        if($result_row['status'] == "settled"){
                    									$status = STATUS_COMPLETE;
                    									$payout_amount = ((isset($result_row['bet_return'])) ? $result_row['bet_return'] : 0);
                    								}else if($result_row['status'] == "cancel" || $result_row['status'] == "fail"){
                    									$status = STATUS_CANCEL;
                    								}else{
                    									$status = STATUS_PENDING;
                    								}
                							    }else{
                							        $status = STATUS_PENDING;
                							    }
                								$PBdata = array(
                							        'game_provider_code' => $provider_code,
                							        'game_type_code' => GAME_COCKFIGHTING,
                							        'game_provider_type_code' => $provider_code."_".GAME_COCKFIGHTING,
                							        'game_result_type' => $result_type,
                							        'game_code' => trim($result_row['category']),
                							        'game_real_code' => trim($result_row['category']),
                							        'bet_id' => trim($result_row['slug']),
                							        'bet_transaction_id' => trim($result_row['arena_fight_no']),
                							        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['bet_at']))),
                							        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['settled_at']))),
                					       			'report_time' => strtotime('+0 hours', strtotime(trim($result_row['settled_at']))),
                							        'bet_amount' => trim($result_row['bet_amount']),
                							        'bet_amount_valid' => trim($result_row['valid_amount']),
                							        'payout_amount' => $payout_amount,
                							        'promotion_amount' => trim($result_row['valid_amount']),
                							        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['settled_at']))),
                							        'sattle_time' => strtotime('+0 hours', strtotime(trim($result_row['settled_at']))),
                							        'win_loss' => trim($result_row['net_income']),
                							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                							        'status' => $status,
                							        'game_username' => trim($result_row['account']),
                    						        'round' => ((isset($result_row['round_id'])) ? $result_row['round_id'] : ''),
                    						        'subround'  => ((isset($result_row['fight_no'])) ? $result_row['fight_no'] : ''),
                							        'player_id' => $member_lists[$exact_username],
                							    );
                							    if($PBdata['win_loss'] == 0){
                								    $PBdata['promotion_amount'] = 0;
                							    }
                								if( ! in_array($PBdata['bet_id'], $transaction_lists))
                								{
                								    $PBdata['bet_info'] = json_encode($result_row);
                							        $PBdata['insert_type'] = SYNC_DEFAULT;
                									array_push($Bdata, $PBdata);
                									if($PBdata['status'] == STATUS_COMPLETE){
                										$PBdataWL = array(
                											'player_id' => $PBdata['player_id'],
                											'game_code' => $PBdata['game_code'],
                											'bet_time' => $PBdata['bet_time'],
                											'payout_time' => $PBdata['payout_time'],
                											'game_provider_code' => $PBdata['game_provider_code'],
                											'game_type_code' => $PBdata['game_type_code'],
                											'total_bet' => 1,
                											'bet_amount' => $PBdata['bet_amount'],
                											'bet_amount_valid' => $PBdata['bet_amount_valid'],
                											'win_loss' => $PBdata['win_loss'],
                										);
                										array_push($BUDdata, $PBdataWL);
                									}
                								}
											}
										}
									}
								}
							}
                            $current_page++;
						    sleep(11);
						    $this->db->trans_start();
        					$this->db->insert('game_result_logs', $DBdata);
        					$result_promotion_reset = array('promotion_amount' => 0);
        					if( ! empty($Bdata))
        					{
        						$this->db->insert_batch('transaction_report', $Bdata);
        					}
        					if( ! empty($BUDdata))
        					{
        						$this->db->insert_batch('win_loss_logs', $BUDdata);
        					}
        					$this->db->trans_complete();
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
    private function ds88_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
		//Request Response gmt+8
		$url = $arr['APIUrl'];
        $start_date = gmdate("Y-m-d\TH:i:s\Z", $start_time);
        $end_date = gmdate("Y-m-d\TH:i:s\Z", $end_time);
        $url .= "/api/merchant/bets";
        $param_array = array(
		    'time_type' => 'settled_at',
		    'start_time' => $start_date,
		    'end_time' => $end_date,
		    'page' => $page_id,
		    'page_size' => 10000,
		);
		$response = $this->curl_get($url . '?' . http_build_query($param_array), "Authorization: Bearer " . $arr['Token']);
		return $response;
	}
	public function ne($member_lists = NULL){
		//GMT 8
		set_time_limit(0);
		$provider_code = 'NE';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-60 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-40 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+10 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-20 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$response = $this->ne_connect($arr, $start_time, $end_time);
					//Response time (UTC +0)
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['uuid']))
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['data']) &&  sizeof($result_array['data'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
									foreach($result_array['data'] as $date_result_row){
										if(isset($date_result_row['games']) && sizeof($date_result_row['games'])>0){
											foreach($date_result_row['games'] as $games_result_row){
												if(isset($games_result_row['participants']) && sizeof($games_result_row['participants'])>0){
													foreach($games_result_row['participants'] as $player_result_row){
														if(isset($player_result_row['bets']) && sizeof($player_result_row['bets'])>0){
															foreach($player_result_row['bets'] as $result_row){
																$tmp_username = strtolower(trim($player_result_row['playerId']));
																$exact_username = $tmp_username;
																$placedOn = (substr(trim($result_row['placedOn']), 0, 19));
																$settledAt = (substr(trim($games_result_row['settledAt']), 0, 19));
															    $PBdata = array(
                											        'game_provider_code' => $provider_code,
                											        'game_type_code' => GAME_SLOTS,
                											        'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
                											        'game_result_type' => $result_type,
                											        'game_code' => trim($games_result_row['gameType']),
                											        'game_real_code' => trim($games_result_row['gameType']),
                											        'bet_id' => trim($result_row['transactionId']).trim($result_row['code']),
                											        'bet_time' => strtotime('+8 hours',strtotime($placedOn)),
                											        'game_time' => strtotime('+8 hours',strtotime($settledAt)),
                											        'report_time' => strtotime('+8 hours',strtotime($settledAt)),
                											        'bet_amount' => trim($result_row['stake']),
                											        'bet_amount_valid' => trim($result_row['stake']),
                											        'payout_time' => strtotime('+8 hours',strtotime($settledAt)),
                											        'sattle_time' => strtotime('+8 hours',strtotime($settledAt)),
                													'compare_time' => strtotime('+8 hours',strtotime($settledAt)),
                													'created_date' => time(),
                											        'payout_amount' => 0,
                							                    	'promotion_amount' => 0,
                											        'win_loss' => trim($result_row['payout'])-trim($result_row['stake']),
                											        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                											        'bet_code' => trim($result_row['description']),
                											        'game_result' => json_encode($games_result_row['result']),
                											        'table_id' => trim($games_result_row['table']['id']),
                											        'round' => trim($games_result_row['id']),
                											        'subround'  => trim($result_row['transactionId']),
                											        'status' => STATUS_CANCEL,
                											        'game_username' => $player_result_row['playerId'],
                											        'player_id' => $member_lists[$exact_username],
                											    );
															    if(trim($games_result_row['status']) == "Resolved"){
                        									    	$PBdata['status'] = STATUS_COMPLETE;
                        									    	$PBdata['payout_amount'] = trim($result_row['payout']);
                        									    	//promotion
                        									    	if($PBdata['win_loss'] != 0){
                        									    		$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
                        									    	}
                        									    }
                        									    if($PBdata['status'] == STATUS_COMPLETE){
    																if( ! in_array($PBdata['bet_id'], $transaction_lists))
    																{	
    																	$PBdata['bet_info'] = json_encode($result_row);
    															        $PBdata['insert_type'] = SYNC_DEFAULT;
    																	array_push($Bdata, $PBdata);
    																	$PBdataWL = array(
                    														'player_id' => $PBdata['player_id'],
                    														'game_code' => $PBdata['game_code'],
                    														'bet_time' => $PBdata['bet_time'],
                    														'payout_time' => $PBdata['payout_time'],
                    														'game_provider_code' => $PBdata['game_provider_code'],
                    														'game_type_code' => $PBdata['game_type_code'],
                    														'total_bet' => 1,
                    														'bet_amount' => $PBdata['bet_amount'],
                    														'bet_amount_valid' => $PBdata['bet_amount_valid'],
                    														'win_loss' => $PBdata['win_loss'],
                    													);
                    													array_push($BUDdata, $PBdataWL);
    																}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function ne_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
		//Request UTC+0
		$start_date = date('Y-m-d', strtotime('-8 hours', $start_time))."T".date('H:i:s', strtotime('-8 hours', $start_time)).".117Z";
		$end_date = date('Y-m-d', strtotime('-8 hours', $end_time))."T".date('H:i:s', strtotime('-8 hours', $end_time)).".117Z";
		$url = $arr['APIUrl'];
		$url .= "/api/gamehistory/v1/casino/games";
		$url .= "?startDate=".$start_date;
		$url .= "&endDate=".$end_date;
		$CasinoKey = $arr['CasinoKey'];
		$APIToken = $arr['APIToken'];
		$response = $this->curl_get($url, "Authorization: Basic " . base64_encode("$CasinoKey:$APIToken"));
		return $response;
	}
	public function dgg($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'DGG';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-40 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+30 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BdataID = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-30 minutes' ,$start_time);
					$db_record_end_time = strtotime('+30 minutes' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$response = $this->dgg_connect($arr, $start_time, $end_time);
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if( ! empty($result_array))
						{
						    $DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['result'])){
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['result']['game_history']['data'])){
									if(sizeof($result_array['result']['game_history']['data'])){
										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
										foreach($result_array['result']['game_history']['data']  as $result_row){
											$tmp_username = strtolower(trim($result_row[0]));
            							    $exact_username = $tmp_username;
            								$PBdata = array(
            							        'game_provider_code' => $provider_code,
            							        'game_type_code' => (isset($game_code_data[trim($result_row[3])]) ? $game_code_data[trim($result_row[3])] : GAME_OTHERS),
            							        'game_provider_type_code' => $provider_code."_".(isset($game_code_data[trim($result_row[3])]) ? $game_code_data[trim($result_row[3])] : GAME_OTHERS),
            							        'game_result_type' => $result_type,
            							        'game_code' => trim($result_row[1]),
            							        'game_real_code' => trim($result_row[1]),
            							        'bet_id' => trim($result_row[5]),
            							        'bet_time' => strtotime('+8 hours', strtotime(trim($result_row[10]))),
            							        'game_time' => strtotime('+8 hours', strtotime(trim($result_row[10]))),
            					       			'report_time' => strtotime('+8 hours', strtotime(trim($result_row[10]))),
            							        'bet_amount' => trim($result_row[6]),
            							        'bet_amount_valid' => trim($result_row[6]),
            							        'payout_amount' => trim($result_row[7]),
            							        'promotion_amount' => trim($result_row[6]),
            							        'payout_time' => strtotime('+8 hours', strtotime(trim($result_row[10]))),
            							        'sattle_time' => strtotime('+8 hours', strtotime(trim($result_row[10]))),
            							        'win_loss' => trim($result_row[7]) - trim($result_row[6]),
            							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
            							        'status' => STATUS_COMPLETE,
            							        'game_username' => trim($result_row[0]),
            							        'player_id' => $exact_username,
            							    );
            							    if(trim($result_row[9]) != "normal"){
            							        $PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
            							    }
            							    if($PBdata['win_loss'] == 0){
            								    $PBdata['promotion_amount'] = 0;
            							    }
											if( ! in_array($PBdata['bet_id'], $transaction_lists))
											{					
												$PBdata['bet_info'] = json_encode($result_row);
										        $PBdata['insert_type'] = SYNC_DEFAULT;
												array_push($Bdata, $PBdata);
												if($PBdata['status'] == STATUS_COMPLETE){
													$PBdataWL = array(
														'player_id' => $PBdata['player_id'],
														'game_code' => $PBdata['game_code'],
														'bet_time' => $PBdata['bet_time'],
														'payout_time' => $PBdata['payout_time'],
														'game_provider_code' => $PBdata['game_provider_code'],
														'game_type_code' => $PBdata['game_type_code'],
														'total_bet' => 1,
														'bet_amount' => $PBdata['bet_amount'],
														'bet_amount_valid' => $PBdata['bet_amount_valid'],
														'win_loss' => $PBdata['win_loss'],
													);
													array_push($BUDdata, $PBdataWL);
												}
											}
										}
									}
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function dgg_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
		//Request UTC+0
		$start_date = date('Y-m-d H:i:s', strtotime('-8 hours', $start_time));
        $end_date = date('Y-m-d H:i:s', strtotime('-8 hours', $end_time));
		$url = $arr['APIUrl'];
        $url .= "/games/game-history-all-players/";
        $param_array = array(
		    'api_key' => $arr['APIKey'],
		    'amount_type' => "real",
		    'start_date' => $start_date,
		    'end_date' => $end_date,
		);
		$response = $this->curl_json($url, $param_array);
		return $response;
	}
	public function t1g($member_lists = NULL){
		//GMT 8
		set_time_limit(0);
		$provider_code = 'T1G';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-15 minutes', $current_time);
				$last_sync_time = strtotime("2023-06-29 16:00:00");
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-70 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata =  array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-30 minutes' ,$start_time);
					$db_record_end_time = strtotime('+30 minutes' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$is_loop = TRUE;
					while($is_loop == TRUE){
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUdata = array();
							$BUIDdata = array();
							$BUDdata = array();
							$response_token = $this->t1g_connect($arr, $start_time, $end_time, "TOKEN", $page_id);
							if($response_token['code'] == '0')
                    		{
                    			$result_token_array = json_decode($response_token['data'], TRUE);
                    			if(isset($result_token_array['success']) && $result_token_array['success'] == true)
                    			{
                    			    $token = $result_token_array['detail']['auth_token'];
                    		        $response = $this->t1g_connect($arr, $start_time, $end_time, "BET", $page_id, $token);
                    		        if($response['code'] == '0')
                        			{
                        			    $result_array = json_decode($response['data'], TRUE);
                        			    if( ! empty($result_array))
                        				{
                        				    $DBdata['resp_data'] = json_encode($result_array);
                        				    if(isset($result_array['code']) && $result_array['code'] == 0)
                        					{
                            				    if(isset($result_array['success']) && $result_array['success'] == 1)
                            					{
                            					    $DBdata['sync_status'] = STATUS_YES;
                            					    $page_total = trim($result_array['detail']['total_pages']);
                            					    if(isset($result_array['detail']['game_history']) && (sizeof($result_array['detail']['game_history'])>0)){
                            					        $transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
                            					        foreach($result_array['detail']['game_history'] as $result_row){
                            					            $tmp_username = strtolower(trim($result_row['username']));
                            							    $exact_username = $tmp_username;
                            								$PBdata = array(
                            							        'game_provider_code' => $provider_code,
                            							        'game_type_code' => GAME_BOARD_GAME,
                            							        'game_provider_type_code' => $provider_code."_".GAME_BOARD_GAME,
                            							        'game_result_type' => $result_type,
                            							        'game_code' => trim($result_row['game_code']),
                            							        'game_real_code' => trim($result_row['game_code']),
                            							        'bet_id' => trim($result_row['uniqueid']),
                            							        'bet_time' => trim($result_row['bet_time']),
                            							        'game_time' => trim($result_row['game_finish_time']),
                            					       			'report_time' => trim($result_row['payout_time']),
                            							        'bet_amount' => trim($result_row['bet_amount']),
                            							        'bet_amount_valid' => trim($result_row['bet_amount']),
                            							        'payout_amount' => trim($result_row['payout_amount']),
                            							        'promotion_amount' => trim($result_row['bet_amount']),
                            							        'payout_time' => trim($result_row['payout_time']),
                            							        'sattle_time' => trim($result_row['payout_time']),
                            							        'win_loss' => trim($result_row['payout_amount']) - trim($result_row['bet_amount']),
                            							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                            							        'status' => STATUS_COMPLETE,
                            							        'game_username' => trim($result_row['username']),
                            							        'player_id' => $member_lists[$exact_username],
                            							    );
                            							    if($PBdata['win_loss'] == 0){
                            								    $PBdata['promotion_amount'] = 0;
                            							    }
                            								if( ! in_array($PBdata['bet_id'], $transaction_lists))
                            								{
                            								    $PBdata['bet_info'] = json_encode($result_row);
                            							        $PBdata['insert_type'] = SYNC_DEFAULT;
                            									array_push($Bdata, $PBdata);
                            									if($PBdata['status'] == STATUS_COMPLETE){
                            										$PBdataWL = array(
                            											'player_id' => $PBdata['player_id'],
                            											'game_code' => $PBdata['game_code'],
                            											'bet_time' => $PBdata['bet_time'],
                            											'payout_time' => $PBdata['payout_time'],
                            											'game_provider_code' => $PBdata['game_provider_code'],
                            											'game_type_code' => $PBdata['game_type_code'],
                            											'total_bet' => 1,
                            											'bet_amount' => $PBdata['bet_amount'],
                            											'bet_amount_valid' => $PBdata['bet_amount_valid'],
                            											'win_loss' => $PBdata['win_loss'],
                            										);
                            										array_push($BUDdata, $PBdataWL);
                            									}
                            								}
                            					        }
                            					    }
                        					    }
                        					} 
                        				}
                        			}
                    			}
                    		}
                    		$this->db->trans_start();
                    		$this->db->insert('game_result_logs', $DBdata);
							if( ! empty($Bdata))
        					{
        						$this->db->insert_batch('transaction_report', $Bdata);
        					}
        					if( ! empty($BUDdata))
        					{
        						$this->db->insert_batch('win_loss_logs', $BUDdata);
        					}
							$this->db->trans_complete();
							$current_page++;
						    sleep(5);
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function t1g_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $type = NULL, $page_id = NULL, $token = NULL){
		//Request UTC+0
		if($type == "TOKEN"){
		    $url = $arr['APIUrl'];
		    $url .= '/gameapi/v2/generate_token';
	        $param_array = array(
    			'merchant_code' => $arr['MerchantCode'],
    			'secure_key' => $arr['APIKey'],
    		);
    		ksort($param_array);
    		$string = '';
    		foreach($param_array as $k=>$v){
    			if($k != '' && ! is_array($k)){
    			   $string .= $v;
    			}
    		}
    		$param_array['sign'] = strtolower(sha1($string . $arr['SignKey']));
    		$response = $this->curl_json($url, $param_array);
		}else{
		    $start_date = date('YmdHis', strtotime('-0 hours', $start_time));
    	    $end_date = date('YmdHis', strtotime('-0 hours', $end_time));
    		$url = $arr['APIUrl'];
        	$url .= "/gameapi/v2/chain/query_game_history";
            $param_array = array(
	            'auth_token' => $token,
    		    'merchant_code' => $arr['MerchantCode'],
    		    'from' => $start_date,
    		    'to' => $end_date,
    		    'time_type' => 2,
    		    'page_number' => $page_id,
    		);
    		ksort($param_array);
			$string = '';
			foreach($param_array as $k=>$v){
				if($k != '' && ! is_array($k)){
				   $string .= $v;
				}
			}
			$param_array['sign'] = sha1($string . $arr['SignKey']);
	        $url .= "?" . http_build_query($param_array);
			$response = $this->curl_get($url);
		}
		return $response;
	}
	public function ab($member_lists = NULL){
		//GMT 8
		set_time_limit(0);
		$provider_code = 'AB';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-15 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-70 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata =  array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-60 minutes' ,$start_time);
					$db_record_end_time = strtotime('+60 minutes' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$game_code_data = array(
						'101' => GAME_CODE_TYPE_LIVE_CASINO_BACCARAT,
						'102' => GAME_CODE_TYPE_LIVE_CASINO_VIP_BACCARAT,
						'103' => GAME_CODE_TYPE_LIVE_CASINO_SPEED_BACCARAT,
						'104' => GAME_CODE_TYPE_LIVE_CASINO_BID_BACCARAT,
						'110' => GAME_CODE_TYPE_LIVE_CASINO_INSURANCE_BACCARAT,
						'201' => GAME_CODE_TYPE_LIVE_CASINO_SICBO,
						'301' => GAME_CODE_TYPE_LIVE_CASINO_DRAGON_TIGER,
						'401' => GAME_CODE_TYPE_LIVE_CASINO_ROULETTE,
						'501' => GAME_CODE_TYPE_LIVE_CASINO_POK_DENG,
						'601' => GAME_CODE_TYPE_LIVE_CASINO_ROCK_PAPER_SCISSORS,
						'801' => GAME_CODE_TYPE_LIVE_CASINO_BULL_BULL,
						'901' => "",
						'702' => GAME_CODE_TYPE_LIVE_CASINO_ULTIMATE_TEXAS_HOLDEM,
					);
					$is_loop = TRUE;
					while($is_loop == TRUE){
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUdata = array();
							$BUIDdata = array();
							$BUDdata = array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->ab_connect($arr, $start_time, $end_time, $page_id);
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
									if(isset($result_array['resultCode']) && $result_array['resultCode'] == 'OK')
									{
										$DBdata['resp_data'] = json_encode($result_array);
										$DBdata['sync_status'] = STATUS_YES;
										$page_total = trim($result_array['data']['total']) / trim($result_array['data']['pageSize']);
										if(isset($result_array['data']['list']) &&  sizeof($result_array['data']['list'])>0){
											foreach($result_array['data']['list'] as $result_row){
												$game_code = (isset($game_code_data[trim($result_row['gameType'])]) ? $game_code_data[trim($result_row['gameType'])] : GAME_CODE_TYPE_UNKNOWN);
												if(trim($result_row['gameType']) == '901'){
													switch(trim($result_row['betType']))
													{
														case '9101': $game_code = GAME_CODE_TYPE_LIVE_CASINO_ZHA_JIN_HUA; break;
														case '9102': $game_code = GAME_CODE_TYPE_LIVE_CASINO_ZHA_JIN_HUA; break;
														case '9103': $game_code = GAME_CODE_TYPE_LIVE_CASINO_ZHA_JIN_HUA; break;
														case '9114': $game_code = GAME_CODE_TYPE_LIVE_CASINO_ZHA_JIN_HUA; break;
														case '9124': $game_code = GAME_CODE_TYPE_LIVE_CASINO_ZHA_JIN_HUA; break;
														default: $game_code = GAME_CODE_TYPE_LIVE_CASINO_THREE_FACE_POKER; break;
													}
												}
												$tmp_username = strtolower(trim($result_row['player']));
												$exact_username = $tmp_username;
												$PBdata = array(
											        'game_provider_code' => $provider_code,
											        'game_type_code' => GAME_LIVE_CASINO,
											        'game_provider_type_code' => $provider_code . '_' . GAME_LIVE_CASINO,
											        'game_result_type' => $result_type,
											        'game_code' => $game_code,
											        'game_real_code' => trim($result_row['gameType']),
											        'bet_id' => trim($result_row['betNum']),
											        'bet_time' => strtotime(trim($result_row['betTime'])),
											        'game_time' => strtotime(trim($result_row['gameRoundStartTime'])),
											        'report_time' => strtotime(trim($result_row['gameRoundEndTime'])),
											        'bet_amount' => trim($result_row['betAmount']),
											        'bet_amount_valid' => trim($result_row['validAmount']),
											        'payout_amount' => 0,
											        'promotion_amount' => 0,
											        'payout_time' => strtotime(trim($result_row['gameRoundEndTime'])),
											        'sattle_time' =>  strtotime(trim($result_row['gameRoundEndTime'])),
											        'compare_time' =>  strtotime(trim($result_row['gameRoundEndTime'])),
											        'created_date' => time(),
											        'win_loss' => trim($result_row['winOrLossAmount']),
											        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
											        'bet_code' => trim($result_row['betType']),
											        'game_result' => trim($result_row['gameResult']),
											        'table_id' => trim($result_row['tableName']),
											        'round' => trim($result_row['gameRoundId']),
											        'subround'  => "",
											        'status' => STATUS_CANCEL,
											        'game_username' => $result_row['player'],
											        'player_id' => $member_lists[$exact_username],
											    );
											    if($result_row['state'] == 0){
											    	$PBdata['status'] = STATUS_COMPLETE;
											    	$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
											    	//promotion
											    	if($PBdata['win_loss'] != 0){
											    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
											    	}
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
													array_push($BUIDdata, $PBdata['bet_id']);
												}
												if($PBdata['status'] == STATUS_COMPLETE){
													$PBdataWL = array(
														'player_id' => $PBdata['player_id'],
														'game_code' => $PBdata['game_code'],
														'bet_time' => $PBdata['bet_time'],
														'payout_time' => $PBdata['payout_time'],
														'game_provider_code' => $PBdata['game_provider_code'],
														'game_type_code' => $PBdata['game_type_code'],
														'total_bet' => 1,
														'bet_amount' => $PBdata['bet_amount'],
														'bet_amount_valid' => $PBdata['bet_amount_valid'],
														'win_loss' => $PBdata['win_loss'],
													);
													array_push($BUDdata, $PBdataWL);
												}
											}
										}
									}
								}
							}
                            $current_page++;
						    sleep(5);
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'game_code' => $transaction_lists_old_row['game_code'],
										'bet_time' => $transaction_lists_old_row['bet_time'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function bng($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = 'BNG';
		$result_type = GAME_SLOTS;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
		    if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-30 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-70 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = "";
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata =  array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-10 minutes', $current_time))
				{
			        $sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-30 minutes' ,$start_time);
					$db_record_end_time = strtotime('+30 minutes' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$is_loop = TRUE;
					while($is_loop == TRUE) {
					    $Bdata = array();
						$BUdata = array();
						$BUIDdata = array();
						$BUDdata =  array();
						$DBdata['sync_status'] = STATUS_NO;
						$DBdata['next_id'] = $next_id;
						$DBdata['resp_data'] = '';
						$response = $this->bng_connect($arr, $start_time, $end_time, $next_id);
						if($response['code'] == '0')
						{
						    if($response['http_code'] == '200'){
    						    $result_array = json_decode($response['data'], TRUE);
    						    if( ! empty($result_array))
    							{
    							    $DBdata['resp_data'] = json_encode($result_array);
								    $DBdata['sync_status'] = STATUS_YES;
    							    $next_id = $result_array['fetch_state'];
    							    if(isset($result_array['items']) && sizeof($result_array['items'])>0){
    							        if($is_retrieve == FALSE){
											$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
											$is_retrieve = TRUE;
										}
    							        foreach($result_array['items'] as $result_row){
    							            if($result_row['mode'] == "REAL"){
    							                if(empty($result_row['is_test'])){
    							                    if(trim($result_row['win']) < 0){
    												    $win_result = STATUS_LOSS;
    							                    }else if(trim($result_row['win']) > 0){
    							                        $win_result = STATUS_WIN;
    												}else{
    												    $win_result = STATUS_TIE;
    												}
    							                    $tmp_username = strtolower(trim($result_row['player_id']));
    									            $exact_username = $tmp_username;
    									            $PBdata = array(
                										'game_provider_code' => $provider_code,
                										'game_type_code' => GAME_SLOTS,
                										'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
                										'game_result_type' => $result_type,
                										'game_code' => trim($result_row['game_id']),
                										'game_real_code' => trim($result_row['game_id']),
                										'bet_id' => trim($result_row['transaction_id']),
                										'bet_transaction_id' => trim($result_row['original_transaction_id']),
                										'bet_time' => strtotime(trim($result_row['c_at'])),
                										'bet_amount' => ((!empty($result_row['bet'])) ? trim($result_row['bet']) : "0"),
                										'bet_amount_valid' => ((!empty($result_row['bet'])) ? trim($result_row['bet']) : "0"),
                										'payout_time' => strtotime(trim($result_row['c_at'])),
                										'sattle_time' => strtotime(trim($result_row['c_at'])),
                										'compare_time' => strtotime(trim($result_row['c_at'])),
                										'game_time' => strtotime(trim($result_row['c_at'])),
                										'created_date' => time(),
                										'win_loss' => trim($result_row['win']) - ((!empty($result_row['bet'])) ? trim($result_row['bet']) : "0"),
                										'payout_amount' => trim($result_row['win']),
                										'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                										'status' => STATUS_COMPLETE,
                										'win_result' => $win_result,
                										'game_username' => trim($result_row['player_id']),
                										'bet_code' => trim($result_row['bonus_event']),
                										'round' => trim($result_row['round_id']),
                										'player_id' => $member_lists[$exact_username],
                									);
                									if(empty($PBdata['bet_amount'])){
                									    $PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
                									}
                									if(!empty($PBdata['bet_code'])){
                									    $PBdata['game_round_type'] = GAME_ROUND_TYPE_GAME_ACTIVITY;
                									    if($PBdata['bet_code'] == "JACKPOT"){
                    									    $PBdata['game_round_type'] = GAME_ROUND_TYPE_JACKPOT;
                									    }
                									}
                									if( ! in_array($PBdata['bet_id'], $transaction_lists))
    												{
    												    if($PBdata['status'] == STATUS_COMPLETE){
        													$PBdata['bet_info'] = json_encode($result_row);
        											        $PBdata['insert_type'] = SYNC_DEFAULT;
        													array_push($Bdata, $PBdata);
        													$PBdataWL = array(
        														'player_id' => $PBdata['player_id'],
        														'game_code' => $PBdata['game_code'],
        														'bet_time' => $PBdata['bet_time'],
        														'payout_time' => $PBdata['payout_time'],
        														'game_provider_code' => $PBdata['game_provider_code'],
        														'game_type_code' => $PBdata['game_type_code'],
        														'total_bet' => 1,
        														'bet_amount' => $PBdata['bet_amount'],
        														'bet_amount_valid' => $PBdata['bet_amount_valid'],
        														'win_loss' => $PBdata['win_loss'],
        													);
        													array_push($BUDdata, $PBdataWL);
        												}
    												}
    							                }
    							            }
    							        }
    							    }
    							    if(empty($next_id)){
    							        $is_loop = FALSE;
    							    }
    							}else{
    							    $is_loop = FALSE;
    							}
						    }else{
						        $is_loop = FALSE;
						    }
						}else{
						    $is_loop = FALSE;
						}
						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}
						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}
						sleep(5);
					}
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
		    }else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function bl($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'BL';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->game_model->get_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					//Prepare transaction list
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
					);
					$is_loop = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUDdata = array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->bl_connect($arr, $start_time, $end_time, $page_id);
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
									if(isset($result_array['resp_msg']['code']) && trim($result_array['resp_msg']['code']) == '200')
									{
										$page_total = trim($result_array['resp_data']['count']['page_total']);
										$DBdata['sync_status'] = STATUS_YES;
										if(isset($result_array['resp_data']['data']) && ! empty($result_array['resp_data']['data']))
										{
											if($is_retrieve == FALSE){
	    										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$start_time-3600, $end_time+3600);
	    										$is_retrieve = TRUE;
	    									}
											$DBdata['resp_data'] = json_encode($result_array['resp_data']['data']);
											for($i=0;$i<sizeof($result_array['resp_data']['data']);$i++)
											{
												$tmp_username = strtolower(trim($result_array['resp_data']['data'][$i]['player_account']));
												$exact_username = $tmp_username;
												//Response time (UTC +8)
												$PBdata = array(
													'game_provider_code' => $provider_code,
													'game_type_code' => GAME_BOARD_GAME,
													'game_provider_type_code' => $provider_code."_".GAME_BOARD_GAME,
													'game_result_type' => $result_type,
													'game_code' => trim($result_array['resp_data']['data'][$i]['game_code']),
													'game_real_code' => trim($result_array['resp_data']['data'][$i]['game_code']),
													'bet_id' => trim($result_array['resp_data']['data'][$i]['id']),
													'bet_time' => trim($result_array['resp_data']['data'][$i]['start_time']),
													'game_time' => trim($result_array['resp_data']['data'][$i]['start_time']),
													'report_time' => trim($result_array['resp_data']['data'][$i]['end_time']),
													'payout_time' => trim($result_array['resp_data']['data'][$i]['end_time']),
													'sattle_time' => trim($result_array['resp_data']['data'][$i]['end_time']),
													'compare_time' => trim($result_array['resp_data']['data'][$i]['end_time']),
													'created_date' => time(),
													'bet_amount' => trim($result_array['resp_data']['data'][$i]['bet_num']),
													'bet_amount_valid' => trim($result_array['resp_data']['data'][$i]['bet_num_valid']),
													'payout_amount' => ((trim($result_array['resp_data']['data'][$i]['gain_gold']) > 0) ? trim($result_array['resp_data']['data'][$i]['gain_gold']) : 0),
													'win_loss' => trim($result_array['resp_data']['data'][$i]['gain_gold']),
													'jackpot_win' => 0,
													'promotion_amount' => 0,
													'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
													'status' => STATUS_COMPLETE,
													'game_username' => trim($result_array['resp_data']['data'][$i]['player_account']),
													'player_id' => $member_lists[$exact_username],
													'bet_info' => json_encode($result_array['resp_data']['data'][$i]),
													'insert_type' => SYNC_DEFAULT,
												);
												if($PBdata['win_loss'] != 0){
	    											$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
										    	}
												if(trim($result_array['resp_data']['data'][$i]['type']) == 'slot')
												{
													$PBdata['game_type_code'] = GAME_SLOTS;
													$PBdata['game_provider_type_code'] = $provider_code."_".GAME_SLOTS;
												}
												if($PBdata['bet_amount'] == 0)
												{
													$PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
												}
												if( ! in_array($PBdata['bet_id'], $transaction_lists))
												{								
													array_push($Bdata, $PBdata);
													if($PBdata['status'] == STATUS_COMPLETE){
														$PBdataWL = array(
															'player_id' => $PBdata['player_id'],
															'game_code' => $PBdata['game_code'],
															'bet_time' => $PBdata['bet_time'],
															'payout_time' => $PBdata['payout_time'],
															'game_provider_code' => $PBdata['game_provider_code'],
															'game_type_code' => $PBdata['game_type_code'],
															'total_bet' => 1,
															'bet_amount' => $PBdata['bet_amount'],
															'bet_amount_valid' => $PBdata['bet_amount_valid'],
															'win_loss' => $PBdata['win_loss'],
														);
														array_push($BUDdata, $PBdataWL);
													}
												}
											}
										}
										$page_id++;
									}
								}
							}
							$this->db->insert('game_result_logs', $DBdata);
							if( ! empty($Bdata))
							{
								$this->db->insert_batch('transaction_report', $Bdata);
							}
							if( ! empty($BUDdata))
							{
								$this->db->insert_batch('win_loss_logs', $BUDdata);
							}
							$current_page++;
							sleep(5);
						}
						else 
						{
							$is_loop = FALSE;
						}
					}
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}
	}
	public function dg($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'DG';
		$result_type = GAME_LIVE_CASINO;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
					$next_id = $sync_data['next_id'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BdataID = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-5 days' ,$start_time);
					$db_record_end_time = strtotime('+5 days' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$response = $this->dg_connect($arr, $start_time, $end_time, "RetrieveRecord");
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if( ! empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['codeId']) && $result_array['codeId'] == "0"){
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['list'])){
									if(sizeof($result_array['list'])){
										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
										foreach($result_array['list']  as $result_row){
											$tmp_username = strtolower(trim($result_row['userName']));
											$exact_username = $tmp_username;
											if($result_row['isRevocation'] == "1"){
												$status = STATUS_COMPLETE;
											}else if($result_row['isRevocation'] == "2"){
												$status = STATUS_CANCEL;
											}else{
												$status = STATUS_PENDING;
											}
											$gameType = ((isset($result_row['gameType'])) ? $result_row['gameType'] : '');
											$gameId = ((isset($result_row['gameId'])) ? $result_row['gameId'] : '');
											$tableId = ((isset($result_row['tableId'])) ? $result_row['tableId'] : '');
											$game_type_code = GAME_LIVE_CASINO;
											$game_code = "";
											switch($gameType)
											{
												case "1":
													switch($gameId){
														case "1": 
															switch($tableId){
																case "30101": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_LIVE_BACCARAT; break;
																case "30102": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_LIVE_BACCARAT; break;
																case "30103": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_LIVE_BACCARAT; break;
																case "30105": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_LIVE_BACCARAT; break;
																default: $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_BACCARAT; break;
															}break;
														case "2": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_INSURANCE_BACCARAT; break;
														case "3":
															switch($tableId){
																case "30301": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_LIVE_DRAGON_TIGER; break;
																default: $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_DRAGON_TIGER; break;
															}break;
														case "4": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_ROULETTE; break;
														case "5": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_SICBO; break;
														case "6": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_FAN_TAN; break;
														case "7": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_BULL_BULL; break;
														case "8": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_BID_BACCARAT; break;
														case "11": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_ZHA_JIN_HUA; break;
														case "14": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_SEDIE; break;
														case "16": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_THREE_FACE_POKER; break;
														case "41": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_BACCARAT; break;
														case "42": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_DRAGON_TIGER; break;
														case "43": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_ZHA_JIN_HUA; break;
														case "44": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_BULL_BULL; break;
														case "45": $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_LIVE_CASINO_BLOCKCHAIN_THREE_FACE_POKER; break;
													}break;
												case "2":
													switch($gameId){
														case "1": $game_type_code = GAME_OTHERS; $game_code = GAME_CODE_TYPE_MEMBER_SEND_GIFT; break;
														case "2": $game_type_code = GAME_OTHERS; $game_code = GAME_CODE_TYPE_MEMBER_GET_GIFT; break;
														case "3": $game_type_code = GAME_OTHERS; $game_code = GAME_CODE_TYPE_ANCHOR_SEND_TIPS; break;
														case "4": $game_type_code = GAME_OTHERS; $game_code = GAME_CODE_TYPE_COMPANY_SEND_GIFT; break;
														case "5": $game_type_code = GAME_OTHERS; $game_code = GAME_CODE_TYPE_BO_BING; break;
														case "6": $game_type_code = GAME_OTHERS; $game_code = GAME_CODE_TYPE_CROUPIER_SEND_TIPS; break;
													}break;
												default: $game_type_code = GAME_LIVE_CASINO; $game_code = GAME_CODE_TYPE_UNKNOWN; break;
											}
                                            if($gameType == "1"){
												$win_loss = ((isset($result_row['winOrLoss'])) ? trim($result_row['winOrLoss'])-trim($result_row['betPoints']) : '0');
											}else if($gameType == "2"){
												if($gameId == "1" || $gameId == "3" || $gameId == "6"){
													$win_loss = trim($result_row['betPoints']) * -1;
												}else if($gameId == "2" || $gameId == "4" || $gameId == "5"){
													$win_loss = trim($result_row['betPoints']);
												}else{
													$win_loss = 0;
												}
											}else{
												$win_loss = 0;
											}
											$PBdata = array(
										        'game_provider_code' => $provider_code,
										        'game_type_code' => $game_type_code,
										        'game_provider_type_code' => $provider_code . "_" . $game_type_code,
										        'game_result_type' => $result_type,
										        'game_code' => $game_code,
										        'game_real_code' => trim($result_row['gameId']),
										        'bet_id' => trim($result_row['id']),
										        'bet_time' => strtotime(trim($result_row['betTime'])),
										        'game_time' => strtotime(trim($result_row['calTime'])),
										        'report_time' => strtotime(trim($result_row['calTime'])),
										        'bet_amount' => trim($result_row['betPoints']),
												'bet_amount_valid' => ((isset($result_row['availableBet'])) ? trim($result_row['availableBet']) : '0'),
										        'payout_amount' => 0,
										        'promotion_amount' => 0,
										        'payout_time' => strtotime(trim($result_row['calTime'])),
										        'sattle_time' => strtotime(trim($result_row['calTime'])),
												'compare_time' => strtotime(trim($result_row['calTime'])),
												'created_date' => time(),
										        'win_loss' =>  $win_loss,
										        'game_round_type' => $game_round_type,
										        'bet_code' => trim($result_row['betDetail']),
										        'game_result' => trim($result_row['result']),
										        'table_id' => trim($result_row['tableId']),
										        'round' => trim($result_row['shoeId']),
										        'subround'  => trim($result_row['playId']),
										        'status' => $status,
										        'game_username' => $result_row['userName'],
										        'player_id' => $member_lists[$exact_username],
										    );
											if($status == STATUS_COMPLETE){
												$PBdata['payout_amount'] = ((isset($result_row['winOrLoss'])) ? trim($result_row['winOrLoss']) : '0');
												if($PBdata['win_loss'] != 0){
													$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
												}
											}
											if( ! in_array($PBdata['bet_id'], $transaction_lists))
											{					
												$PBdata['bet_info'] = json_encode($result_row);
										        $PBdata['insert_type'] = SYNC_DEFAULT;
												array_push($Bdata, $PBdata);
												if($PBdata['status'] != STATUS_PENDING){
													array_push($BdataID, $PBdata['bet_id']);
												}
												if($PBdata['status'] == STATUS_COMPLETE){
													$PBdataWL = array(
														'player_id' => $PBdata['player_id'],
														'game_code' => $PBdata['game_code'],
														'bet_time' => $PBdata['bet_time'],
														'payout_time' => $PBdata['payout_time'],
														'game_provider_code' => $PBdata['game_provider_code'],
														'game_type_code' => $PBdata['game_type_code'],
														'total_bet' => 1,
														'bet_amount' => $PBdata['bet_amount'],
														'bet_amount_valid' => $PBdata['bet_amount_valid'],
														'win_loss' => $PBdata['win_loss'],
													);
													array_push($BUDdata, $PBdataWL);
												}
											}else{
												array_push($BdataID, $PBdata['bet_id']);
											}
										}
									}
								}
							}
						}
					}
					if(sizeof($BdataID)>0){
						$response_submit = $this->dg_connect($arr, $start_time, $end_time, "SubmitRecord",$BdataID);
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function dt($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'DT';
		$result_type = GAME_SLOTS;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$is_loop = TRUE;
					while($is_loop == TRUE){
						$Bdata = array();
						$BUdata = array();
						$BUIDdata = array();
						$BUDdata = array();
						$DBdata['sync_status'] = STATUS_NO;
						$DBdata['page_id'] = $page_id;
						$DBdata['resp_data'] = '';
						$response = $this->dt_connect($arr, $start_time, $end_time, $page_id);
						if($response['code'] == '0')
						{
							$result_array = json_decode($response['data'], TRUE);
							if( ! empty($result_array))
							{
								if(isset($result_array['RESPONSECODE']) && $result_array['RESPONSECODE'] == '00000')
								{
									$DBdata['resp_data'] = json_encode($result_array);
									$DBdata['sync_status'] = STATUS_YES;
									if(sizeof($result_array['BETSDETAILS'])>0){
										if($is_retrieve == FALSE){
											$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
											$is_retrieve = TRUE;
										}
										foreach($result_array['BETSDETAILS'] as $result_row){
											$tmp_username = strtolower(trim($result_row['playerName']));
											$exact_username = $tmp_username;
											if(trim($result_row['rewardType']) == ""){
												$PBdata = array(
											        'game_provider_code' => $provider_code,
											        'game_type_code' => GAME_SLOTS,
											        'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
											        'game_result_type' => $result_type,
											        'game_code' => trim($result_row['gameCode']),
											        'game_real_code' => trim($result_row['gameCode']),
											        'bet_id' => trim($result_row['id']),
											        'bet_ref_no' => (isset($result_row['fcid']) ? trim($result_row['fcid']) : ""),
											        'bet_transaction_id' => (isset($result_row['partentId']) ? trim($result_row['partentId']) : ""),
											        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['createTime']))),
											        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['createTime']))),
									       			'report_time' => strtotime('+0 hours', strtotime(trim($result_row['createTime']))),
											        'bet_amount' => trim($result_row['betPrice']),
											        'bet_amount_valid' => trim($result_row['betPrice']),
											        'payout_amount' => 0,
											        'promotion_amount' => 0,
											        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['createTime']))),
											        'win_loss' => trim($result_row['prizeWins']),
											        'table_id' => "",
											        'round' => "",
											        'subround'  => "",
											        'bet_code' => "",
									        		'game_result' => "",
											        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
											        'status' => STATUS_COMPLETE,
											        'game_username' => trim($result_row['playerName']),
											        'player_id' => $member_lists[$exact_username],
											    );
											    if($PBdata['win_loss'] != 0){
											    	$PBdata['payout_amount'] = trim($result_row['betPrice']) + trim($result_row['prizeWins']);
											    	$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
											    }
											}else{
												//Bonus
												$PBdata = array(
											        'game_provider_code' => $provider_code,
											        'game_type_code' => GAME_SLOTS,
											        'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
											        'game_result_type' => $result_type,
											        'game_code' => trim($result_row['gameCode']),
											        'game_real_code' => trim($result_row['gameCode']),
											        'bet_id' => trim($result_row['id']),
											        'bet_ref_no' => (isset($result_row['fcid']) ? trim($result_row['fcid']) : ""),
											        'bet_transaction_id' => (isset($result_row['partentId']) ? trim($result_row['partentId']) : ""),
											        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['createTime']))),
											        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['createTime']))),
									       			'report_time' => strtotime('+0 hours', strtotime(trim($result_row['createTime']))),
											        'bet_amount' => 0,
											        'bet_amount_valid' => 0,
											        'payout_amount' => 0,
											        'promotion_amount' => 0,
											        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['createTime']))),
											        'win_loss' => trim($result_row['betWins']),
											        'table_id' => "",
											        'round' => "",
											        'subround'  => "",
											        'bet_code' => "",
									        		'game_result' => "",
											        'game_round_type' => GAME_ROUND_TYPE_FREE_SPIN,
											        'status' => STATUS_COMPLETE,
											        'game_username' => trim($result_row['playerName']),
											        'player_id' => $member_lists[$exact_username],
											    );
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
												array_push($BUIDdata, $PBdata['bet_id']);
											}
											if($PBdata['status'] == STATUS_COMPLETE){
												$PBdataWL = array(
													'player_id' => $PBdata['player_id'],
													'game_code' => $PBdata['game_code'],
													'bet_time' => $PBdata['bet_time'],
													'payout_time' => $PBdata['payout_time'],
													'game_provider_code' => $PBdata['game_provider_code'],
													'game_type_code' => $PBdata['game_type_code'],
													'total_bet' => 1,
													'bet_amount' => $PBdata['bet_amount'],
													'bet_amount_valid' => $PBdata['bet_amount_valid'],
													'win_loss' => $PBdata['win_loss'],
												);
												array_push($BUDdata, $PBdataWL);
											}
										}
									}else{
										$is_loop = FALSE;
									}
									$page_id++;
								}else{
									$is_loop = FALSE;
								}
							}else{
								$is_loop = FALSE;
							}
						}else{
							$is_loop = FALSE;
						}
						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						if(!empty($BUIDdata)){
							$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
							if( ! empty($transaction_lists_old)){
								foreach($transaction_lists_old as $transaction_lists_old_row){
									if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $transaction_lists_old_row['player_id'],
											'game_code' => $transaction_lists_old_row['game_code'],
											'bet_time' => $transaction_lists_old_row['bet_time'],
											'payout_time' => $transaction_lists_old_row['payout_time'],
											'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
											'game_type_code' => $transaction_lists_old_row['game_type_code'],
											'total_bet' => -1,
											'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
											'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
											'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
										);
										array_push($BUDdata, $PBdataWL);
									}
								}
							}
						}
						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}
						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}
						if( ! empty($BUdata))
						{
							foreach($BUdata as $BUdataRow){
								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
							}
						}
						sleep(5);
					}
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function evoplay($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'EVOP';
		$result_type = GAME_SLOTS;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-30 minutes' ,$start_time);
					$db_record_end_time = strtotime('+30 minutes' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$game_type_code_data = array(
						'5553' => GAME_BOARD_GAME,
						'1006' => GAME_BOARD_GAME,
						'745' => GAME_BOARD_GAME,
						'5669' => GAME_BOARD_GAME,
						'967' => GAME_BOARD_GAME,
						'5679' => GAME_BOARD_GAME,
						'1009' => GAME_BOARD_GAME,
						'748' => GAME_BOARD_GAME,
						'946' => GAME_BOARD_GAME,
						'5849' => GAME_BOARD_GAME,
					);
					$is_loop = TRUE;
					while($is_loop == TRUE){
						$Bdata = array();
						$BUdata = array();
						$BUIDdata = array();
						$BUDdata = array();
						$DBdata['sync_status'] = STATUS_NO;
						$DBdata['page_id'] = $page_id;
						$DBdata['resp_data'] = '';
						$response = $this->evoplay_connect($arr, $start_time, $end_time, $page_id);
						if($response['code'] == '0')
						{
							$result_array = json_decode($response['data'], TRUE);
							if( ! empty($result_array))
							{
								if(!isset($result_array['error']))
								{
									$DBdata['resp_data'] = json_encode($result_array);
									$DBdata['sync_status'] = STATUS_YES;
									if($result_array['last_page'] == $page_id){
										$is_loop = FALSE;
									}
									if($result_array['last_page'] == "0"){
									    $is_loop = FALSE;
									}
									if(sizeof($result_array['page_result']) > 0){
										if($is_retrieve == FALSE){
											$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
											$is_retrieve = TRUE;
										}
										foreach($result_array['page_result'] as $result_row){
										    $tmp_username = strtolower(trim($result_row['user_id']));
											$exact_username = $tmp_username;
											if($result_row['status'] == "1"){
												$status = STATUS_COMPLETE;
											}else if($result_row['status'] == "0"){
												$status = STATUS_PENDING;
											}else{
												$status = STATUS_CANCEL;
											}
											if(trim($result_row['win_amount']) < 0){
											    $win_result = STATUS_LOSS;
						                    }else if(trim($result_row['win_amount']) > 0){
						                        $win_result = STATUS_WIN;
											}else{
											    $win_result = STATUS_TIE;
											}
											$PBdata = array(
										        'game_provider_code' => $provider_code,
										        'game_type_code' => (isset($game_type_code_data[trim($result_row['game_id'])]) ? $game_type_code_data[trim($result_row['game_id'])] : GAME_SLOTS),
										        'game_provider_type_code' => $provider_code."_".(isset($game_type_code_data[trim($result_row['game_id'])]) ? $game_type_code_data[trim($result_row['game_id'])] : GAME_SLOTS),
										        'game_result_type' => $result_type,
										        'game_code' => trim($result_row['game_id']),
										        'game_real_code' => trim($result_row['game_id']),
										        'bet_id' => trim($result_row['round_id']),
										        'bet_time' => strtotime('+8 hours', strtotime(trim($result_row['bet_time']))),
												'game_time' => strtotime('+8 hours', strtotime(trim($result_row['bet_time']))),
												'report_time' => strtotime('+8 hours', strtotime(trim($result_row['bet_time']))),
												'bet_amount' => trim($result_row['bet_amount']),
										        'bet_amount_valid' => trim($result_row['bet_amount']),
										        'payout_amount' => 0,
										        'promotion_amount' => 0,
										      	'payout_time' => strtotime('+8 hours', strtotime(trim($result_row['bet_time']))),
										        'sattle_time' => strtotime('+8 hours', strtotime(trim($result_row['bet_time']))),
												'compare_time' => strtotime('+8 hours', strtotime(trim($result_row['bet_time']))),
												'created_date' => time(),
										        'win_loss' => trim($result_row['win_amount']) - trim($result_row['bet_amount']),
										        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
										        'status' => $status,
										        'win_result' => $win_result,
										        'game_username' => trim($result_row['user_name']),
										        'player_id' => $member_lists[$exact_username],
										    );
											if($PBdata['win_loss'] != 0){
										    	$PBdata['payout_amount'] = trim($result_row['payout']);
										    	$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
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
												array_push($BUIDdata, $PBdata['bet_id']);
											}
											if($PBdata['status'] == STATUS_COMPLETE){
												$PBdataWL = array(
													'player_id' => $PBdata['player_id'],
													'bet_time' => $PBdata['bet_time'],
													'game_code' => $PBdata['game_code'],
													'payout_time' => $PBdata['payout_time'],
													'game_provider_code' => $PBdata['game_provider_code'],
													'game_type_code' => $PBdata['game_type_code'],
													'total_bet' => 1,
													'bet_amount' => $PBdata['bet_amount'],
													'bet_amount_valid' => $PBdata['bet_amount_valid'],
													'win_loss' => $PBdata['win_loss'],
												);
												array_push($BUDdata, $PBdataWL);
											}
										}
									}else{
										$is_loop = FALSE;
									}
									$page_id++;
								}else{
									$is_loop = FALSE;
								}
							}else{
								$is_loop = FALSE;
							}
						}else{
							$is_loop = FALSE;
						}
						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						if(!empty($BUIDdata)){
							$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
							if( ! empty($transaction_lists_old)){
								foreach($transaction_lists_old as $transaction_lists_old_row){
									if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $transaction_lists_old_row['player_id'],
											'game_code' => $transaction_lists_old_row['game_code'],
											'bet_time' => $transaction_lists_old_row['bet_time'],
											'game_time' => $transaction_lists_old_row['payout_time'],
											'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
											'game_type_code' => $transaction_lists_old_row['game_type_code'],
											'total_bet' => -1,
											'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
											'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
											'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
										);
										array_push($BUDdata, $PBdataWL);
									}
								}
							}
						}
						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}
						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}
						if( ! empty($BUdata))
						{
							foreach($BUdata as $BUdataRow){
								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
							}
						}
						sleep(5);
					}
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function gfgd($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = 'GFGD';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
		    if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-30 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = "";
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata =  array();
				$result_capture_array =  array();
				$result_prepare = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-10 minutes', $current_time))
				{
			        $sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-30 minutes' ,$start_time);
					$db_record_end_time = strtotime('+30 minutes' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$is_loop = TRUE;
					while($is_loop == TRUE) {
					    $is_loop = FALSE;
					    $response = $this->gfgd_connect($arr, $start_time, $end_time, $page_id);
					    if($response['code'] == '0')
						{
						    $result_array = json_decode($response['data'], TRUE);
						    if( ! empty($result_array))
							{
							    if(isset($result_array['data']['action_result']) && $result_array['data']['action_result'] == "Success"){
							        if(isset($result_array['data']['betlogs']) && sizeof($result_array['data']['betlogs'])>0){
							            $page_id++;
    							        $is_loop = TRUE;
    							        $result_capture_array = array_merge($result_capture_array,$result_array['data']['betlogs']);
    							    }else{
    							        $is_allow = TRUE;
    							    }
							    }
							}
						}
						sleep(3);
					}
					if($is_allow){
					    $DBdata['sync_status'] = STATUS_YES;
					    $DBdata['resp_data'] = json_encode($result_capture_array);
					    if(sizeof($result_capture_array) > 0){
					        $transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
					        foreach($result_capture_array as $result_row){
					            $tmp_username = trim($result_row['player_name']);
        		                $exact_username = $tmp_username;
        		                $id = trim($result_row['parent_bet_id'])."_".trim($result_row['game_code'])."_".$exact_username;
        		                if(!isset($result_prepare[$id])){
        		                    $result_prepare[$id] = array();
        		                }
        		                $result_prepare[$id][] = $result_row;
					        }
					        if(!empty($result_prepare)){
        		                foreach($result_prepare as $result_prepare_key => $result_prepare_value){
        		                    $bet_time = 0;
        		                    $payout_time = 0;
        		                    $bet_id = $result_prepare_key;
        		                    $game_code = "";
        		                    $parent_bet_id = 0; 
        		                    $tmp_username = "";
        		                    $exact_username = "";
        		                    $bet_amount = 0;
        		                    $win_loss = 0;
        		                    $status = STATUS_PENDING;
        		                    if(!empty($result_prepare_value)){
        		                        foreach($result_prepare_value as $result_row){
        		                            if(empty($game_code)){
        		                                $game_code = trim($result_row['game_code']);
            		                            $parent_bet_id = trim($result_row['parent_bet_id']);
            		                            $tmp_username = trim($result_row['player_name']);
            		                            $exact_username = strtolower($tmp_username);
                                            }
                                            if(trim($result_row['trans_type']) == "Stake"){
                                                $bet_time = trim($result_row['created_at']);
                                                if(in_array(trim($result_row['Currency']),$currency_one)){
                    						        $bet_amount += bcdiv(trim($result_row['bet_amount']) * 1000, 1, 2);
                        					    }else{
                        					        $bet_amount += bcdiv(trim($result_row['bet_amount']), 1, 2);
                        					    }
                                            }
                                            if(trim($result_row['trans_type']) == "Payoff"){
                                                $payout_time = trim($result_row['created_at']);
                                                if($status == STATUS_PENDING){
                                                    $status = STATUS_COMPLETE;
                                                }
                                                if(in_array(trim($result_row['Currency']),$currency_one)){
                    						        $win_loss += bcdiv(trim($result_row['win_amount']) * 1000, 1, 2);
                        					    }else{
                        					        $win_loss += bcdiv(trim($result_row['win_amount']), 1, 2);
                        					    }
                                            }
        		                        }
        		                        $PBdata = array(
            								'game_provider_code' => $provider_code,
            								'game_type_code' => GAME_BOARD_GAME,
            						        'game_provider_type_code' => $provider_code."_".GAME_BOARD_GAME,
            						        'game_result_type' => $result_type,
            								'game_code' => $game_code,
            								'game_real_code' => $game_code,
            								'bet_id' => $bet_id,
            								'bet_transaction_id' => $parent_bet_id,
            								'bet_ref_no' => "",
            								'bet_time' => substr($bet_time, 0, -3),
            								'bet_amount' => $bet_amount,
            								'bet_amount_valid' => 0,
            								'game_time' => substr($bet_time, 0, -3),
            								'payout_time' => substr($payout_time, 0, -3),
            								'sattle_time' => substr($payout_time, 0, -3),
            								'compare_time' => substr($payout_time, 0, -3),
            								'created_date' => time(),
            								'win_loss' =>  $win_loss - $bet_amount,
            								'jackpot_win' => 0,
            								'payout_amount' => 0,
            								'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
            								'status' => $status,
            								'game_username' => $tmp_username,
            								'player_id' =>  $member_lists[$exact_username],
            								'bet_info' => json_encode($result_prepare_value,true),
            							);
            							$win_result = STATUS_PENDING;
            							if($PBdata['status'] == STATUS_COMPLETE){
            							    $PBdata['bet_amount_valid'] = $PBdata['bet_amount'];
            							    $PBdata['payout_amount'] = $win_loss;
            							    if(trim($PBdata['win_loss']) > 0){
    					                        $win_result = STATUS_WIN;
    										}else{
    										    if(trim($PBdata['win_loss']) < 0){
    										        $win_result = STATUS_LOSS;
    										    }else{
    										        $win_result = STATUS_TIE;
    										    }
    										}
            							}
            							if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{
										    if($PBdata['status'] == STATUS_COMPLETE){
										        $PBdata['insert_type'] = SYNC_DEFAULT;
										        array_push($Bdata, $PBdata);
										        $PBdataWL = array(
													'player_id' => $PBdata['player_id'],
													'bet_time' => $PBdata['bet_time'],
													'payout_time' => $PBdata['payout_time'],
													'game_provider_code' => $PBdata['game_provider_code'],
													'game_type_code' => $PBdata['game_type_code'],
													'total_bet' => 1,
													'bet_amount' => $PBdata['bet_amount'],
													'bet_amount_valid' => $PBdata['bet_amount_valid'],
													'win_loss' => $PBdata['win_loss'],
												);
												array_push($BUDdata, $PBdataWL);
										    }
										}
        		                    }
        		                }
        		            }
					    }
					}
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
		    }else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function gr($member_lists = NULL, $result_type = NULL){
	    set_time_limit(0);
		$provider_code = 'GR';
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-30 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-10 minutes' ,$start_time);
					$db_record_end_time = strtotime('+10 minutes' ,$end_time);
					$currency_one = array("VND1000", "IDR1000");
                    $currency_two = array("XNB");
                    $currency_three = array("XNB2");
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$is_loop = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
							$BUDdata = array();
							$BUdata = array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->gr_connect($arr, $start_time, $end_time, $result_type, $page_id);
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
								    $DBdata['resp_data'] = json_encode($result_array);
									if(isset($result_array['status']) && $result_array['status'] == 'Y')
									{
										$page_total = trim($result_array['total_pages']);
										$DBdata['sync_status'] = STATUS_YES;
										if(isset($result_array['data']['bet_details']) && !empty($result_array['data']['bet_details']))
										{
											if($is_retrieve == FALSE){
	    										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
	    										$is_retrieve = TRUE;
	    									}
											foreach($result_array['data']['bet_details'] as $result_row){
	                        					$tmp_username = strtolower(trim($result_row['account']));
						                        $exact_username = $tmp_username;
						                        if($result_row['game_module_type'] == "1"){
                    						        $game_type_code = GAME_BOARD_GAME;
                    						    }else if($result_row['game_module_type'] == "2"){
                    						        $game_type_code = GAME_BOARD_GAME;
                    						    }else if($result_row['game_module_type'] == "3"){
                    						        $game_type_code = GAME_SLOTS;
                    						    }else if($result_row['game_module_type'] == "4"){
                    						        $game_type_code = GAME_FISHING;
                    						    }else{
                    						        $game_type_code = GAME_OTHERS;
                    						    }
                    						    if(in_array($result_row['Currency'],$currency_one)){
                    						        $bet_amount = bcdiv(trim($result_row['bet']) * 1000, 1, 2);
                    						        $bet_amount_valid = bcdiv(trim($result_row['valid_bet']) * 1000, 1, 2);
                    						        $payout_amount = bcdiv(trim($result_row['win']) * 1000, 1, 2);
                    						        $win_loss = bcdiv(trim($result_row['profit']) * 1000, 1, 2);
                        					    }else if(in_array($result_row['Currency'],$currency_two)){
                        					        $bet_amount = bcdiv(trim($result_row['bet']) * 100, 1, 2);
                    						        $bet_amount_valid = bcdiv(trim($result_row['valid_bet']) * 100, 1, 2);
                    						        $payout_amount = bcdiv(trim($result_row['win']) * 100, 1, 2);
                    						        $win_loss = bcdiv(trim($result_row['profit']) * 100, 1, 2);
                        					    }else if(in_array($result_row['Currency'],$currency_three)){
                        					        $bet_amount = bcdiv(trim($result_row['bet']) * 130, 1, 2);
                    						        $bet_amount_valid = bcdiv(trim($result_row['valid_bet']) * 130, 1, 2);
                    						        $payout_amount = bcdiv(trim($result_row['win']) * 130, 1, 2);
                    						        $win_loss = bcdiv(trim($result_row['profit']) * 130, 1, 2);
                        					    }else{
                        					        $bet_amount = bcdiv(trim($result_row['bet']), 1, 2);
                    						        $bet_amount_valid = bcdiv(trim($result_row['valid_bet']), 1, 2);
                    						        $payout_amount = bcdiv(trim($result_row['win']), 1, 2);
                    						        $win_loss = bcdiv(trim($result_row['profit']), 1, 2);
                        					    }
                    						    $PBdata = array(
                    						        'game_provider_code' => $provider_code,
                    						        'game_type_code' => $game_type_code,
                    						        'game_result_type' => $result_type,
                    						        'game_provider_type_code' => $provider_code."_".$game_type_code,
                    						        'game_code' => trim($result_row['game_type']),
                    						        'game_real_code' => trim($result_row['game_type']),
                    						        'bet_id' => trim($result_row['sid']),
                    						        'bet_transaction_id' => trim($result_row['id_str']),
                    						        'bet_ref_no' => trim($result_row['order_id']),
                    						        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['create_time']))),
                    						        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['create_time']))),
                    						        'report_time' => strtotime('+0 hours', strtotime(trim($result_row['create_time']))),
                    						        'bet_amount' => $bet_amount,
                    								'bet_amount_valid' => $bet_amount_valid,
                    						        'payout_amount' => $payout_amount,
                        							'promotion_amount' => $bet_amount_valid,
                    						        'status' => STATUS_COMPLETE,
                    						        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['create_time']))),
                    						        'sattle_time' => strtotime('+0 hours', strtotime(trim($result_row['create_time']))),
                    								'compare_time' => strtotime('+0 hours', strtotime(trim($result_row['create_time']))),
                    								'created_date' => time(),
                    						        'win_loss' =>  $win_loss,
                    						        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                    						        'bet_code' => ((isset($result_row['bet_result'])) ? $result_row['bet_result'] : ''),
                    						        'game_result' => ((isset($result_row['game_result'])) ? $result_row['game_result'] : ''),
                    						        'game_username' => $result_row['account'],
                    						        'table_id' => ((isset($result_row['table_id'])) ? $result_row['table_id'] : ''),
                    						        'round' => ((isset($result_row['game_round'])) ? $result_row['game_round'] : ''),
                    						        'subround'  => ((isset($result_row['room_id'])) ? $result_row['room_id'] : ''),
                    						        'player_id' => $member_lists[$exact_username],
                    						    );
                    						    if($PBdata['bet_amount'] == 0)
                    							{
                    								$PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
                    							}
                    							if( ! in_array($PBdata['bet_id'], $transaction_lists))
                    							{					
                    								$PBdata['bet_info'] = json_encode($result_row);
                    						        $PBdata['insert_type'] = SYNC_DEFAULT;
                    								array_push($Bdata, $PBdata);
                    								if($PBdata['status'] == STATUS_COMPLETE){
                    									$PBdataWL = array(
                    										'player_id' => $PBdata['player_id'],
                    										'game_code' => $PBdata['game_code'],
                    										'bet_time' => $PBdata['bet_time'],
                    										'payout_time' => $PBdata['payout_time'],
                    										'game_provider_code' => $PBdata['game_provider_code'],
                    										'game_type_code' => $PBdata['game_type_code'],
                    										'total_bet' => 1,
                    										'bet_amount' => $PBdata['bet_amount'],
                    										'bet_amount_valid' => $PBdata['bet_amount_valid'],
                    										'win_loss' => $PBdata['win_loss'],
                    									);
                    									array_push($BUDdata, $PBdataWL);
                    								}
                    							}
	                        				}
										}
										$page_id++;
									}
								}
							}
							$this->db->insert('game_result_logs', $DBdata);
							$result_promotion_reset = array('promotion_amount' => 0);
							if( ! empty($Bdata))
							{
								$this->db->insert_batch('transaction_report', $Bdata);
							}
							if( ! empty($BUDdata))
							{
								$this->db->insert_batch('win_loss_logs', $BUDdata);
							}
							$current_page++;
							sleep(5);
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}
		else{
			echo EXIT_ERROR;
		}
	}
	public function icg($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'ICG';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-2 days' ,$start_time);
					$db_record_end_time = strtotime('+2 days' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$game_type_code_data = array(
						'slot' => GAME_SLOTS,
						'fish' => GAME_FISHING,
						'arcade' => GAME_OTHERS,
						'card' => GAME_BOARD_GAME,
					);
					$token_response = $this->icg_connect_key($arr);
					if($token_response['code'] == '0')
					{
						$token_result_array = json_decode($token_response['data'], TRUE);
						if(isset($token_result_array['token']))
						{
						    $is_loop = TRUE;
						    while($is_loop == TRUE){
								$Bdata = array();
								$BUdata = array();
								$BUIDdata = array();
								$BUDdata = array();
								$DBdata['sync_status'] = STATUS_NO;
								$DBdata['page_id'] = $page_id;
								$DBdata['resp_data'] = '';
								$response = $this->icg_connect($arr, $start_time, $end_time, $page_id, $token_result_array['token']);
								if($response['code'] == '0')
								{
									$result_array = json_decode($response['data'], TRUE);
									if( ! empty($result_array))
									{
										if(isset($result_array['data']))
										{
											$DBdata['resp_data'] = json_encode($result_array);
											$DBdata['sync_status'] = STATUS_YES;
											if(sizeof($result_array['data'])>0){
												if($is_retrieve == FALSE){
													$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
													$is_retrieve = TRUE;
												}
												foreach($result_array['data'] as $result_row){
													$tmp_username = strtolower(trim($result_row['player']));
													$exact_username = $tmp_username;
													if($result_row['status'] == "finish"){
														$status = STATUS_COMPLETE;
													}else if($result_row['isRevocation'] == "cancel"){
														$status = STATUS_CANCEL;
													}else{
														$status = STATUS_PENDING;
													}
													$PBdata = array(
												        'game_provider_code' => $provider_code,
												        'game_type_code' => (isset($game_type_code_data[trim($result_row['gameType'])]) ? $game_type_code_data[trim($result_row['gameType'])] : GAME_OTHERS),
												        'game_result_type' => $result_type,
												        'game_code' => trim($result_row['productId']),
												        'game_real_code' => trim($result_row['productId']),
												        'bet_id' => trim($result_row['id']),
												        'bet_time' => strtotime(trim($result_row['createdAt'])),
												        'game_time' => strtotime(trim($result_row['updatedAt'])),
										       			'report_time' => strtotime(trim($result_row['updatedAt'])),
												        'bet_amount' => bcdiv((trim($result_row['bet'])/100),1,2),
												        'bet_amount_valid' => bcdiv((trim($result_row['validBet'])/100),1,2),
												        'payout_amount' => 0,
												        'promotion_amount' => 0,
												        'payout_time' => strtotime(trim($result_row['updatedAt'])),
												        'sattle_time' => strtotime(trim($result_row['updatedAt'])),
														'compare_time' => strtotime(trim($result_row['updatedAt'])),
														'created_date' => time(),
												        'win_loss' => bcdiv(((trim($result_row['win']) - trim($result_row['bet']))/100),1,2),
												        'table_id' => trim($result_row['gameId']),
												        'round' => trim($result_row['productId']),
												        'subround'  => trim($result_row['setId']),
												        'bet_code' => trim($result_row['gameType']),
										        		'game_result' => '',
												        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
												        'status' => $status,
												        'game_username' => trim($result_row['player']),
												        'player_id' => $member_lists[$exact_username],
												    );
													if($status == STATUS_COMPLETE){
														if($PBdata['win_loss'] != 0){
													    	$PBdata['payout_amount'] = bcdiv((trim($result_row['win'])/100),1,2);
													    	$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
													    }
													}
													$PBdata['game_provider_type_code'] = $PBdata['game_provider_code']."_".$PBdata['game_type_code'];
													if( ! in_array($PBdata['bet_id'], $transaction_lists))
													{					
														$PBdata['bet_info'] = json_encode($result_row);
												        $PBdata['insert_type'] = SYNC_DEFAULT;
														array_push($Bdata, $PBdata);
													}else{
														$PBdata['bet_update_info'] = json_encode($result_row);
												        $PBdata['update_type'] = SYNC_DEFAULT;
														array_push($BUdata, $PBdata);
														array_push($BUIDdata, $PBdata['bet_id']);
													}
													if($PBdata['status'] == STATUS_COMPLETE){
														$PBdataWL = array(
															'player_id' => $PBdata['player_id'],
															'game_code' => $PBdata['game_code'],
															'bet_time' => $PBdata['bet_time'],
															'payout_time' => $PBdata['payout_time'],
															'game_provider_code' => $PBdata['game_provider_code'],
															'game_type_code' => $PBdata['game_type_code'],
															'total_bet' => 1,
															'bet_amount' => $PBdata['bet_amount'],
															'bet_amount_valid' => $PBdata['bet_amount_valid'],
															'win_loss' => $PBdata['win_loss'],
														);
														array_push($BUDdata, $PBdataWL);
													}
												}
											}else{
												$is_loop = FALSE;
											}
											$page_id++;
										}else{
											$is_loop = FALSE;
										}
									}else{
										$is_loop = FALSE;
									}
								}else{
									$is_loop = FALSE;
								}
								$this->db->insert('game_result_logs', $DBdata);
								$result_promotion_reset = array('promotion_amount' => 0);
								if(!empty($BUIDdata)){
									$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
									if( ! empty($transaction_lists_old)){
										foreach($transaction_lists_old as $transaction_lists_old_row){
											if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
												$PBdataWL = array(
													'player_id' => $transaction_lists_old_row['player_id'],
													'game_code' => $transaction_lists_old_row['game_code'],
													'bet_time' => $transaction_lists_old_row['bet_time'],
													'payout_time' => $transaction_lists_old_row['payout_time'],
													'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
													'game_type_code' => $transaction_lists_old_row['game_type_code'],
													'total_bet' => -1,
													'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
													'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
													'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
												);
												array_push($BUDdata, $PBdataWL);
											}
										}
									}
								}
								if( ! empty($Bdata))
								{
									$this->db->insert_batch('transaction_report', $Bdata);
								}
								if( ! empty($BUDdata))
								{
									$this->db->insert_batch('win_loss_logs', $BUDdata);
								}
								if( ! empty($BUdata))
								{
									foreach($BUdata as $BUdataRow){
										$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
									}
								}
								sleep(5);
							}
						}
					}
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function rtg($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = 'RTG';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 0;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$is_loop = TRUE;
					$token_response = $this->rtg_connect($arr, $start_time, $end_time, $page_id, 'RetrieveToken');
					if(isset($token_response['http_code']) && $token_response['http_code'] == "200"){
						$token_array = json_decode($token_response['data'], TRUE);
						if(isset($token_array['token'])){
	    					$token = $token_array['token'];
							while($is_loop == TRUE){
								$DBdata['sync_status'] = STATUS_NO;
								$DBdata['page_id'] = $page_id;
								$DBdata['resp_data'] = '';
								$Bdata = array();
								$BUDdata = array();
								$response = $this->rtg_connect($arr, $start_time, $end_time, $page_id, 'RetrieveRecord', $token);
								if($response['code'] == '0' && $response['http_code'] == "200")
								{
									$DBdata['sync_status'] = STATUS_YES;
									$result_array = json_decode($response['data'], TRUE);
									if(!empty($result_array))
									{
										$DBdata['resp_data'] = json_encode($result_array);
										if(isset($result_array['items']) && sizeof($result_array['items']) > 0)
										{
											if($is_retrieve == FALSE){
	    										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
	    										$is_retrieve = TRUE;
	    									}
											foreach($result_array['items'] as $result_row){
												$tmp_username = strtolower(trim($result_row['playerName']));
												$exact_username = $tmp_username;
												if($result_row['gameId'] == "2162689"){
													$game_type_code = GAME_FISHING;
												}else{
													$game_type_code = GAME_SLOTS;
												}
												$PBdata = array(
													'game_provider_code' => $provider_code,
													'game_type_code' => $game_type_code,
											        'game_provider_type_code' => $provider_code."_".$game_type_code,
											        'game_result_type' => $result_type,
													'game_code' => trim($result_row['gameId']),
													'game_real_code' => trim($result_row['gameId']),
													'bet_id' => trim($result_row['id']),
													'bet_transaction_id' => trim($result_row['gameNumber']),
													'bet_time' => strtotime(trim($result_row['gameStartDate'])),
													'bet_amount' => trim($result_row['bet']),
													'bet_amount_valid' => trim($result_row['bet']),
													'game_time' => strtotime(trim($result_row['gameDate'])),
													'report_time' => strtotime(trim($result_row['gameDate'])),
													'payout_time' => strtotime(trim($result_row['gameDate'])),
													'sattle_time' => strtotime(trim($result_row['gameDate'])),
													'compare_time' => strtotime(trim($result_row['gameDate'])),
													'created_date' => time(),
													'win_loss' =>  trim($result_row['winLossAmount']),
													'jackpot_win' => trim($result_row['jpWin']),
													'payout_amount' => trim($result_row['win']) - trim($result_row['jpWin']),
													'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
													'status' => STATUS_COMPLETE,
													'game_username' => trim($result_row['playerName']),
													'player_id' =>  $member_lists[$exact_username],
													'bet_info' => json_encode($result_row),
												);
												if( ! in_array($PBdata['bet_id'], $transaction_lists))
												{					
													$PBdata['bet_info'] = json_encode($result_row);
											        $PBdata['insert_type'] = SYNC_DEFAULT;
													array_push($Bdata, $PBdata);
													if($PBdata['status'] == STATUS_COMPLETE){
														$PBdataWL = array(
															'player_id' => $PBdata['player_id'],
															'game_code' => $PBdata['game_code'],
															'bet_time' => $PBdata['bet_time'],
															'payout_time' => $PBdata['payout_time'],
															'game_provider_code' => $PBdata['game_provider_code'],
															'game_type_code' => $PBdata['game_type_code'],
															'total_bet' => 1,
															'bet_amount' => $PBdata['bet_amount'],
															'bet_amount_valid' => $PBdata['bet_amount_valid'],
															'win_loss' => $PBdata['win_loss'],
														);
														array_push($BUDdata, $PBdataWL);
													}
												}
											}
										}else{
											$is_loop = FALSE;
										}
									}else{
										$is_loop = FALSE;
									}
								}else{
									$is_loop = FALSE;
								}
								$this->db->insert('game_result_logs', $DBdata);
								if( ! empty($Bdata))
								{
									$this->db->insert_batch('transaction_report', $Bdata);
								}
								if( ! empty($BUDdata))
								{
									$this->db->insert_batch('win_loss_logs', $BUDdata);
								}
								$page_id++;
								sleep(5);
							}
						}
					}
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}
		else{
			echo EXIT_ERROR;
		}
	}
	public function rsg($member_lists = NULL, $result_type = NULL){
	    set_time_limit(0);
		$provider_code = 'RSG';
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-30 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+4 minutes', strtotime($initial_time))));
				$page_id = 0;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$BUWdata = array();
				$BUWCdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				$currency_one = array("IDR", "VND");
                $currency_two = array("MYR2");
				if($end_time <= strtotime('-10 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-5 minutes' ,$start_time);
					$db_record_end_time = strtotime('+10 minutes' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$response = $this->rsg_connect($arr, $start_time, $end_time, $result_type);
					if($response['code'] == '0')
        		    {
        		        $result_string = openssl_decrypt(base64_decode($response['data']),'DES-CBC',$arr['Deskey'],OPENSSL_RAW_DATA ,$arr['IVkey']);
        			    $result_array = json_decode($result_string, TRUE);
        			    if(isset($result_array['ErrorCode']) && $result_array['ErrorCode'] == '0'){
        			        $DBdata['resp_data'] = json_encode($result_array);
							$DBdata['sync_status'] = STATUS_YES;
							if(isset($result_array['Data']['GameDetail']) && (sizeof($result_array['Data']['GameDetail']) > 0))
						    {
    							$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
    							foreach($result_array['Data']['GameDetail'] as $result_row){
    							    $tmp_username = strtolower(trim($result_row['UserId']));
									$exact_username = $tmp_username;
    							    if($result_type == GAME_SLOTS){
    							        $game_type_code = GAME_SLOTS;
    							    }else if($result_type == GAME_FISHING){
    							        $game_type_code = GAME_FISHING;
    							    }else{
    							        $game_type_code = GAME_SLOTS;
    							    }
    							    if(in_array($result_row['Currency'],$currency_one)){
    							        $bet_amount = bcdiv(trim($result_row['BetAmt']) * 1000, 1, 2);
    							        $bet_amount_valid = bcdiv(trim($result_row['BetAmt']) * 1000, 1, 2);
    							        $payout_amount = bcdiv(trim($result_row['WinAmt']) * 1000, 1, 2);
    							        $win_loss = bcdiv((trim($result_row['WinAmt']) - trim($result_row['BetAmt'])) * 1000, 1, 2);
            					    }else if(in_array($result_row['Currency'],$currency_two)){
            					        $bet_amount = bcdiv(trim($result_row['BetAmt']) / 100, 1, 2);
    							        $bet_amount_valid = bcdiv(trim($result_row['BetAmt']) / 100, 1, 2);
    							        $payout_amount = bcdiv(trim($result_row['WinAmt']) / 100, 1, 2);
    							        $win_loss = bcdiv((trim($result_row['WinAmt']) - trim($result_row['BetAmt'])) / 100, 1, 2);
            					    }else{
            					        $bet_amount = bcdiv(trim($result_row['BetAmt']), 1, 2);
    							        $bet_amount_valid = bcdiv(trim($result_row['BetAmt']), 1, 2);
    							        $payout_amount = bcdiv(trim($result_row['WinAmt']), 1, 2);
    							        $win_loss = bcdiv((trim($result_row['WinAmt']) - trim($result_row['BetAmt'])), 1, 2);
            					    }
    						        $PBdata = array(
    							        'game_provider_code' => $provider_code,
    							        'game_type_code' => $game_type_code,
    							        'game_provider_type_code' => $provider_code."_".$game_type_code,
    							        'game_result_type' => $result_type,
    							        'game_code' => trim($result_row['GameId']),
    							        'game_real_code' => trim($result_row['GameId']),
    							        'bet_id' => trim($result_row['SequenNumber']),
    							        'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['PlayTime']))),
    							        'game_time' => strtotime('+0 hours', strtotime(trim($result_row['PlayTime']))),
    					       			'report_time' => strtotime('+0 hours', strtotime(trim($result_row['PlayTime']))),
    							        'bet_amount' => $bet_amount,
    							        'bet_amount_valid' => $bet_amount_valid,
    							        'payout_amount' => $payout_amount,
    							        'promotion_amount' => $bet_amount_valid,
    							        'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['PlayTime']))),
    							        'win_loss' => $win_loss,
    							        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
    							        'status' => STATUS_COMPLETE,
    							        'game_username' => trim($result_row['UserId']),
    							        'player_id' => $member_lists[$exact_username],
    							    );
    							    if($PBdata['bet_amount'] == 0)
    								{
    									$PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
    								}
    								if( ! in_array($PBdata['bet_id'], $transaction_lists))
    								{					
    									$PBdata['bet_info'] = json_encode($result_row);
    							        $PBdata['insert_type'] = SYNC_DEFAULT;
    									array_push($Bdata, $PBdata);
    									if($PBdata['status'] == STATUS_COMPLETE){
    										$PBdataWL = array(
    											'player_id' => $PBdata['player_id'],
    											'game_code' => $PBdata['game_code'],
    											'bet_time' => $PBdata['bet_time'],
    											'payout_time' => $PBdata['payout_time'],
    											'game_provider_code' => $PBdata['game_provider_code'],
    											'game_type_code' => $PBdata['game_type_code'],
    											'total_bet' => 1,
    											'bet_amount' => $PBdata['bet_amount'],
    											'bet_amount_valid' => $PBdata['bet_amount_valid'],
    											'win_loss' => $PBdata['win_loss'],
    										);
    										array_push($BUDdata, $PBdataWL);
    									}
    								}
    							}
							}
        			    }
        		    }
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}
		else{
			echo EXIT_ERROR;
		}
	}
	public function naga($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = 'NAGA';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
		    if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-30 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-70 minutes', $start_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = "";
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata =  array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
			        $sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-10 minutes' ,$start_time);
					$db_record_end_time = strtotime('+10 minutes' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$is_loop = TRUE;
					while($is_loop == TRUE) {
					    $Bdata = array();
						$BUdata = array();
						$BUIDdata = array();
						$BUDdata =  array();
						$DBdata['sync_status'] = STATUS_NO;
						$DBdata['next_id'] = $next_id;
						$DBdata['page_id'] = $page_id;
						$DBdata['resp_data'] = '';
						$response = $this->naga_connect($arr, $start_time, $end_time, $page_id);
						if($response['code'] == '0')
						{
						    $result_array = json_decode($response['data'], TRUE);
						    $DBdata['resp_data'] = json_encode($result_array);
                		    if(isset($result_array['data'])){
                		        $DBdata['sync_status'] = STATUS_YES;
                		        if(sizeof($result_array['data']) > 0){
                		            if($is_retrieve == FALSE){
										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
										$is_retrieve = TRUE;
									}
                		            foreach($result_array['data'] as $result_row){
                		                if($result_row['betStatus'] == "RESOLVED"){
											$status = STATUS_COMPLETE;
										}else{
											$status = STATUS_PENDING;
										}
										if($result_row['betType'] == "NORMAL"){
										    $bet_type = GAME_ROUND_TYPE_GAME_ROUND;
										}else{
										    $bet_type = GAME_ROUND_TYPE_FREE_SPIN;
										}
										$tmp_username = strtolower(trim($result_row['player']['nativeId']));
    									$exact_username = $tmp_username;
										if(trim($result_row['earn']) > 0){
					                        $win_result = STATUS_WIN;
										}else{
										    if(trim($result_row['amount']) > 0){
										        $win_result = STATUS_LOSS;
										    }else{
										        $win_result = STATUS_TIE;
										    }
										}
										$PBdata = array(
    										'game_provider_code' => $provider_code,
    										'game_type_code' => GAME_SLOTS,
    										'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
    										'game_result_type' => $result_type,
    										'game_code' => trim($result_row['game']['code']),
    										'game_real_code' => trim($result_row['game']['code']),
    										'bet_id' => trim($result_row['id']),
    										'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['created']))),
    										'bet_amount' => ((!empty($result_row['amount'])) ? trim($result_row['amount']) : "0"),
    										'bet_amount_valid' => ((!empty($result_row['amount'])) ? trim($result_row['amount']) : "0"),
    										'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['updated']))),
    										'sattle_time' => strtotime('+0 hours', strtotime(trim($result_row['updated']))),
    										'compare_time' => strtotime('+0 hours', strtotime(trim($result_row['updated']))),
    										'game_time' => strtotime('+0 hours', strtotime(trim($result_row['updated']))),
    										'created_date' => time(),
    										'win_loss' => trim($result_row['earn']) - ((!empty($result_row['amount'])) ? trim($result_row['amount']) : "0"),
    										'payout_amount' => trim($result_row['earn']),
    										'game_round_type' => $bet_type,
    										'status' => $status,
    										'win_result' => $win_result,
    										'game_username' => trim($result_row['player']['nativeId']),
    										'player_id' => $member_lists[$exact_username],
    									);
    									if( ! in_array($PBdata['bet_id'], $transaction_lists))
										{
										    if($PBdata['status'] == STATUS_COMPLETE){
												$PBdata['bet_info'] = json_encode($result_row);
										        $PBdata['insert_type'] = SYNC_DEFAULT;
												array_push($Bdata, $PBdata);
												$PBdataWL = array(
													'player_id' => $PBdata['player_id'],
													'bet_time' => $PBdata['bet_time'],
													'payout_time' => $PBdata['payout_time'],
													'game_provider_code' => $PBdata['game_provider_code'],
													'game_type_code' => $PBdata['game_type_code'],
													'game_code' => $PBdata['game_code'],
													'total_bet' => 1,
													'bet_amount' => $PBdata['bet_amount'],
													'bet_amount_valid' => $PBdata['bet_amount_valid'],
													'win_loss' => $PBdata['win_loss'],
												);
												array_push($BUDdata, $PBdataWL);
											}
										}
                		            }
                		        }else{
                		            $is_loop = FALSE;
                		        }
                		    }else{
                		        $is_loop = FALSE;
                		    }
						}else{
						    $is_loop = FALSE;
						}
						$page_id++;
						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}
						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}
						sleep(1);
					}
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
		    }else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function ninek($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'NK';
		$result_type = GAME_LOTTERY;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-30 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$is_loop = TRUE;
					while($is_loop == TRUE) {
						if($page_total > $current_page)
						{
							$Bdata = array();
            				$BUdata = array();
            				$BUIDdata = array();
            				$BUDdata = array();
							$DBdata['sync_status'] = STATUS_NO;
							$DBdata['page_id'] = $page_id;
							$DBdata['resp_data'] = '';
							$response = $this->ninek_connect($arr, $start_time, $end_time, $page_id);
							if($response['code'] == '0')
							{
								$result_array = json_decode($response['data'], TRUE);
								if( ! empty($result_array))
								{
									if(isset($result_array['success']) && $result_array['success'] == '0'){
										$DBdata['resp_data'] = json_encode($result_array);
										$DBdata['sync_status'] = STATUS_YES;
										$page_total = trim($result_array['data']['PageInfo']['TotalPage']);
										if(sizeof($result_array['data']['BetList'])>0){
											if($is_retrieve == FALSE){
												$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
												$is_retrieve = TRUE;
											}
											foreach($result_array['data']['BetList'] as $result_row){
											    $tmp_username = strtolower(trim($result_row['MemberAccount']));
											    $exact_username = $tmp_username;
											    if(trim($result_row['Result']) == "X"){
    												$status = STATUS_PENDING;
    												$win_result = STATUS_PENDING;
											    }else if(trim($result_row['Result']) == "C"){
											        $status = STATUS_CANCEL;
    												$win_result = STATUS_CANCEL;
    											}else{
    												$status = STATUS_COMPLETE;
    												if(trim($result_row['Result']) == "W"){
    												    $win_result = STATUS_WIN;
    												}else if(trim($result_row['Result']) == "L"){
    												    $win_result = STATUS_LOSS;
    												}else{
    												    $win_result = STATUS_UNKNOWN;   
    												}
    											}
											    $PBdata = array(
	    									        'game_provider_code' => $provider_code,
	    									        'game_type_code' => GAME_LOTTERY,
	    									        'game_result_type' => $result_type,
	    									        'game_provider_type_code' => $provider_code."_".GAME_LOTTERY,
	    									        'game_code' => trim($result_row['TypeCode']),
	    									        'game_real_code' => trim($result_row['TypeCode']),
	    									        'bet_id' => trim($result_row['WagerID']),
	    									        'bet_time' => strtotime(trim($result_row['WagerDate'])),
	    									        'game_time' => strtotime(trim($result_row['WagerDate'])),
									       			'report_time' => strtotime(trim($result_row['GameDate']." ".$result_row['GameTime'])),
	    									        'bet_amount' => trim($result_row['TotalAmount']),
	    									        'bet_amount_valid' => trim($result_row['BetAmount']),
	    									        'payout_amount' => trim($result_row['PayOff']),
	    									        'promotion_amount' => 0,
	    									        'payout_time' => strtotime(trim($result_row['GameDate']." ".$result_row['GameTime'])),
	    									        'sattle_time' => strtotime(trim($result_row['GameDate']." ".$result_row['GameTime'])),
        											'compare_time' => strtotime(trim($result_row['GameDate']." ".$result_row['GameTime'])),
        											'created_date' => time(),
	    									        'win_loss' => (trim($result_row['PayOff']) - trim($result_row['TotalAmount'])),
	    									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
	    									        'status' => $status,
	    									        'win_result' => $win_result,
	    									        'game_username' => trim($result_row['MemberAccount']),
	    									        'bet_code' => $result_row['BetItem'],
	    									        'game_result' => trim($result_row['GameResult']),
	    									        'player_id' => $member_lists[$exact_username],
	    									    );
											    if($PBdata['win_loss'] != 0){
										    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
										    	}
											    if( ! in_array($PBdata['bet_id'], $transaction_lists))
												{					
													$PBdata['bet_info'] = json_encode($result_row);
											        $PBdata['insert_type'] = SYNC_DEFAULT;
													if($PBdata['status'] == STATUS_COMPLETE){
													    array_push($Bdata, $PBdata);
    													$PBdataWL = array(
    														'player_id' => $PBdata['player_id'],
    														'game_code' => $PBdata['game_code'],
    														'bet_time' => $PBdata['bet_time'],
    														'payout_time' => $PBdata['payout_time'],
    														'game_provider_code' => $PBdata['game_provider_code'],
    														'game_type_code' => $PBdata['game_type_code'],
    														'total_bet' => 1,
    														'bet_amount' => $PBdata['bet_amount'],
    														'bet_amount_valid' => $PBdata['bet_amount_valid'],
    														'win_loss' => $PBdata['win_loss'],
    													);
    													array_push($BUDdata, $PBdataWL);
    												}
												}
											}
										}
									}
									$page_id++;
								}
							}
							$this->db->insert('game_result_logs', $DBdata);
							$result_promotion_reset = array('promotion_amount' => 0);
							if( ! empty($Bdata))
							{
								$this->db->insert_batch('transaction_report', $Bdata);
							}
							if( ! empty($BUDdata))
							{
								$this->db->insert_batch('win_loss_logs', $BUDdata);
							}
							$current_page++;
							sleep(5);
						}else 
    					{
    						$is_loop = FALSE;
    					}
					}
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function obsb_bet($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'OBSB';
		$result_type = GAME_SPORTSBOOK;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-60 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				$next_id = 0;
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-40 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+30 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$BdataID = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-15 days' ,$start_time);
					$db_record_end_time = strtotime('+15 days' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$game_code_data = array(
                        '1' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_MLB,
                        '2' => GAME_CODE_TYPE_SPORTBOOK_LOTTERY,
                        '3' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_CPBL,
                        '4' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_NPB,
                        '5' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_KBO,
                        '6' => GAME_CODE_TYPE_SPORTBOOK_ICE_HOCKEY_NHL,
                        '7' => GAME_CODE_TYPE_SPORTBOOK_BASKETBALL_NBA,
                        '8' => GAME_CODE_TYPE_SPORTBOOK_GREYHOUND_RACE,
                        '9' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL,
                        '10' => GAME_CODE_TYPE_SPORTBOOK_ICE_HOCKEY,
                        '11' => GAME_CODE_TYPE_SPORTBOOK_BASKETBALL,
                        '13' => GAME_CODE_TYPE_SPORTBOOK_SOCCER,
                        '14' => GAME_CODE_TYPE_SPORTBOOK_SOCCER_TOP,
                        '15' => GAME_CODE_TYPE_SPORTBOOK_FOOTBALL,
                        '16' => GAME_CODE_TYPE_SPORTBOOK_ESPORT,
    				);
					$response = $this->obsb_connect($arr, $start_time, $end_time, 'BET');
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array,true);
							if(isset($result_array['status'])){
								if($result_array['status'] == "1"){
									$DBdata['sync_status'] = STATUS_YES;
									if(isset($result_array['orders_detail']) && sizeof($result_array['orders_detail'])>0){
										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
										foreach($result_array['orders_detail'] as $result_row){
											$tmp_username = strtolower(trim($result_row['customer_userid']));
                                            $exact_username = $tmp_username;
                                            if($result_row['order_status'] == "1"){
                                                $status = STATUS_PENDING;
                                            }else{
                                                $status = STATUS_CANCEL;
                                            }
                                            $PBdata = array(
                        						'game_provider_code' => $provider_code,
                        						'game_type_code' => GAME_SPORTSBOOK,
                        						'game_provider_type_code' => $provider_code."_".GAME_SPORTSBOOK,
                        						'game_result_type' => $result_type,
                        						'game_code' => (isset($game_code_data[trim($result_row['game_category'])]) ? $game_code_data[trim($result_row['game_category'])] : GAME_CODE_TYPE_UNKNOWN),
                        						'game_real_code' => trim($result_row['game_category']),
                        						'bet_id' => trim($result_row['order_id']),
                        						'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['bet_datetime']))),
                        						'bet_amount' => trim($result_row['bet_amount']),
                        						'bet_amount_valid' => trim($result_row['bet_real_amount']),
                        						'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['finished_datetime']))),
                        						'win_loss' => trim($result_row['customer_win_amount_no_retreat']) + trim($result_row['customer_retreat']),
                        						'game_time' => strtotime('+0 hours', strtotime(trim($result_row['finished_datetime']))),
                        						'report_time' => strtotime('+0 hours', strtotime(trim($result_row['billing_date']))),
                        						'sattle_time' => strtotime('+0 hours', strtotime(trim($result_row['finished_datetime']))),
                        						'compare_time' => strtotime('+0 hours', strtotime(trim($result_row['billing_date']))),
                        						'created_date' => time(),
                        						'payout_amount' => 0,
                        						'promotion_amount' => 0,
                        						'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                        						'odds_currency' => "OT",
                        						'odds_rate' => trim($result_row['bet_odds']),
                        						'status' => $status,
                        						'game_username' => trim($result_row['customer_userid']),
                        						'player_id' => $member_lists[$exact_username],
                        						'bet_code' => $result_row['game_type'],
                        						'game_result' => json_encode($result_row),
                        					);
                        					if( ! in_array($PBdata['bet_id'], $transaction_lists))
                        					{					
                        					    $PBdata['game_result'] = NULL;
                        						$PBdata['bet_info'] = json_encode($result_row);
                        				        $PBdata['insert_type'] = SYNC_DEFAULT;
                        						array_push($Bdata, $PBdata);
                        					}else{
                        					    $PBdata['game_result'] = NULL;
                        						$PBdata['bet_update_info'] = json_encode($result_row);
                        				        $PBdata['update_type'] = SYNC_DEFAULT;
                        						array_push($BUdata, $PBdata);
                        						array_push($BUIDdata, $PBdata['bet_id']);
                        					}
										}
									}
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'game_code' => $transaction_lists_old_row['game_code'],
										'bet_time' => $transaction_lists_old_row['bet_time'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
    public function obsb($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'OBSB';
		$result_type = GAME_SPORTSBOOK;
		$sync_type = SYNC_TYPE_MODIFIED;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-60 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				$next_id = 0;
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$BdataID = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-30 minutes', $current_time))
				{
					$last_bet_sync_time = 0;
    				$sync_bet_data = $this->report_model->get_game_result_logs($provider_code,$result_type,SYNC_TYPE_ALL);
    				if( ! empty($sync_bet_data))
    				{
    					$last_bet_sync_time = $sync_bet_data['end_time'];
    				}
    				if($last_bet_sync_time > $end_time){
						$sys_data = $this->miscellaneous_model->get_miscellaneous();
						$db_record_start_time = strtotime('-15 days' ,$start_time);
						$db_record_end_time = strtotime('+15 days' ,$end_time);
						$DBdata = array(
							'game_provider_code' => $provider_code,
							'game_result_type' => $result_type,
							'game_sync_type' => $sync_type,
							'start_time' => $start_time,
							'end_time' => $end_time,
							'sync_time' => time(),
							'sync_status' => STATUS_NO,
							'page_id' => $page_id,
							'next_id' => $next_id,
							'resp_data' => '',
						);
						$game_code_data = array(
	                        '1' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_MLB,
	                        '2' => GAME_CODE_TYPE_SPORTBOOK_LOTTERY,
	                        '3' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_CPBL,
	                        '4' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_NPB,
	                        '5' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_KBO,
	                        '6' => GAME_CODE_TYPE_SPORTBOOK_ICE_HOCKEY_NHL,
	                        '7' => GAME_CODE_TYPE_SPORTBOOK_BASKETBALL_NBA,
	                        '8' => GAME_CODE_TYPE_SPORTBOOK_GREYHOUND_RACE,
	                        '9' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL,
	                        '10' => GAME_CODE_TYPE_SPORTBOOK_ICE_HOCKEY,
	                        '11' => GAME_CODE_TYPE_SPORTBOOK_BASKETBALL,
	                        '13' => GAME_CODE_TYPE_SPORTBOOK_SOCCER,
	                        '14' => GAME_CODE_TYPE_SPORTBOOK_SOCCER_TOP,
	                        '15' => GAME_CODE_TYPE_SPORTBOOK_FOOTBALL,
	                        '16' => GAME_CODE_TYPE_SPORTBOOK_ESPORT,
	    				);
						$response = $this->obsb_connect($arr, $start_time, $end_time, 'ORDER');
						if($response['code'] == '0')
						{
						    $response_2 = $this->obsb_connect($arr, $start_time, $end_time, 'HISTORY');
						    if($response_2['code'] == '0')
						    {
						        $result_array = json_decode($response['data'], TRUE);
						        $result_array_2 = json_decode($response_2['data'], TRUE);
						        if(!empty($result_array) && !empty($result_array_2))
							    {
							        $DBdata['resp_data'] = json_encode(array($result_array,$result_array_2));
							        if(isset($result_array['status']) && isset($result_array_2['status'])){
							            if($result_array['status'] == "1" && $result_array_2['status'] == "1"){
							                $DBdata['sync_status'] = STATUS_YES;
							                $all_result = array_merge($result_array['orders_detail'],$result_array_2['orders_detail']);
							                if(!empty($all_result) && sizeof($all_result)>0){
							                	$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
							                	foreach($all_result as $result_row){
								                    $tmp_username = strtolower(trim($result_row['customer_userid']));
		                                            $exact_username = $tmp_username;
		                                            if($result_row['order_status'] == "1"){
		                                                $status = STATUS_COMPLETE;
		                                            }else{
		                                                $status = STATUS_CANCEL;
		                                            }
		                                            $PBdata = array(
		                        						'game_provider_code' => $provider_code,
		                        						'game_type_code' => GAME_SPORTSBOOK,
		                        						'game_provider_type_code' => $provider_code."_".GAME_SPORTSBOOK,
		                        						'game_result_type' => $result_type,
		                        						'game_code' => (isset($game_code_data[trim($result_row['game_category'])]) ? $game_code_data[trim($result_row['game_category'])] : GAME_CODE_TYPE_UNKNOWN),
		                        						'game_real_code' => trim($result_row['game_category']),
		                        						'bet_id' => trim($result_row['order_id']),
		                        						'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['bet_datetime']))),
		                        						'bet_amount' => trim($result_row['bet_amount']),
		                        						'bet_amount_valid' => trim($result_row['bet_real_amount']),
		                        						'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['finished_datetime']))),
		                        						'win_loss' => trim($result_row['customer_win_amount_no_retreat']) + trim($result_row['customer_retreat']),
		                        						'game_time' => strtotime('+0 hours', strtotime(trim($result_row['finished_datetime']))),
		                        						'report_time' => strtotime('+0 hours', strtotime(trim($result_row['billing_date']))),
		                        						'sattle_time' => strtotime('+0 hours', strtotime(trim($result_row['finished_datetime']))),
		                        						'compare_time' => strtotime('+0 hours', strtotime(trim($result_row['billing_date']))),
		                        						'created_date' => time(),
		                        						'payout_amount' => 0,
		                        						'promotion_amount' => 0,
		                        						'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
		                        						'odds_currency' => "OT",
		                        						'odds_rate' => trim($result_row['bet_odds']),
		                        						'status' => $status,
		                        						'game_username' => trim($result_row['customer_userid']),
		                        						'player_id' => $member_lists[$exact_username],
		                        						'bet_code' => $result_row['game_type'],
		                        						'game_result' => json_encode($result_row),
		                        					);
		                        					if($status == STATUS_COMPLETE){
		                        						$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
		                        						if($PBdata['win_loss'] != 0){
		                        							$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
		                        				    	}
		                        					}else{
		                        						$PBdata['payout_amount'] = 0;
		                        					}
		                        					if( ! in_array($PBdata['bet_id'], $BdataID)){
		                        					    array_push($BdataID, $PBdata['bet_id']);
    		                        					if( ! in_array($PBdata['bet_id'], $transaction_lists))
    	                            					{					
    	                            					    $PBdata['game_result'] = NULL;
    	                            						$PBdata['bet_info'] = json_encode($result_row);
    	                            				        $PBdata['insert_type'] = SYNC_DEFAULT;
    	                            						array_push($Bdata, $PBdata);
    	                            					}else{
    	                            					    $PBdata['game_result'] = NULL;
    	                            						$PBdata['bet_update_info'] = json_encode($result_row);
    	                            				        $PBdata['update_type'] = SYNC_DEFAULT;
    	                            						array_push($BUdata, $PBdata);
    	                            						array_push($BUIDdata, $PBdata['bet_id']);
    	                            					}
    	                            					if($PBdata['status'] == STATUS_COMPLETE){
    	                            						$PBdataWL = array(
    	                            							'player_id' => $PBdata['player_id'],
    	                            							'game_code' => $PBdata['game_code'],
    	                            							'bet_time' => $PBdata['bet_time'],
    	                            							'payout_time' => $PBdata['payout_time'],
    	                            							'game_provider_code' => $PBdata['game_provider_code'],
    	                            							'game_type_code' => $PBdata['game_type_code'],
    	                            							'total_bet' => 1,
    	                            							'bet_amount' => $PBdata['bet_amount'],
    	                            							'bet_amount_valid' => $PBdata['bet_amount_valid'],
    	                            							'win_loss' => $PBdata['win_loss'],
    	                            						);
    	                            						array_push($BUDdata, $PBdataWL);
    	                            					}
		                        					}
								                }
							                }
							            }   
							        }
							    }
						    }
						}
						$this->db->trans_start();
						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						if(!empty($BUIDdata)){
							$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
							if( ! empty($transaction_lists_old)){
								foreach($transaction_lists_old as $transaction_lists_old_row){
									if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $transaction_lists_old_row['player_id'],
											'game_code' => $transaction_lists_old_row['game_code'],
											'bet_time' => $transaction_lists_old_row['bet_time'],
											'payout_time' => $transaction_lists_old_row['payout_time'],
											'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
											'game_type_code' => $transaction_lists_old_row['game_type_code'],
											'total_bet' => -1,
											'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
											'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
											'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
										);
										array_push($BUDdata, $PBdataWL);
									}
								}
							}
						}
						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}
						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}
						if( ! empty($BUdata))
						{
							foreach($BUdata as $BUdataRow){
								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
							}
						}
						$this->db->trans_complete();
					}
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function og($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'OG';
		$result_type = GAME_LIVE_CASINO;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-60 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				$next_id = 0;
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+10 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$BdataID = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-20 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-30 minutes' ,$start_time);
					$db_record_end_time = strtotime('+30 minutes' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$currency_one = array("IDR", "VND", "INR", "MMK");
					$win_loss_data = array(
					    'lose' => STATUS_LOSS,
					    'win' => STATUS_WIN,
					    'tie' => STATUS_TIE,
				    );
					$game_code_data = array(
					    'SPEED BACCARAT' => GAME_CODE_TYPE_LIVE_CASINO_SPEED_BACCARAT,
					    'BACCARAT' => GAME_CODE_TYPE_LIVE_CASINO_BACCARAT,
					    'BIDDING BACCARAT' => GAME_CODE_TYPE_LIVE_CASINO_BID_BACCARAT,
					    'NO COMMISSION BACCARAT' => GAME_CODE_TYPE_LIVE_CASINO_NO_COMMISSION_BACCARAT,
					    'NEW DT' => GAME_CODE_TYPE_LIVE_CASINO_NEW_DRAGON_TIGER,
					    'CLASSIC DT' => GAME_CODE_TYPE_LIVE_CASINO_DRAGON_TIGER,
					    'MONEYWHEEL' => GAME_CODE_TYPE_LIVE_CASINO_MONEYWHEEL,
					    'ROULETTE' => GAME_CODE_TYPE_LIVE_CASINO_ROULETTE,
					    'BULL BULL' => GAME_CODE_TYPE_LIVE_CASINO_BULL_BULL,
					    'NIUNIU' => GAME_CODE_TYPE_LIVE_CASINO_BULL_BULL,
					    'THREE CARDS' => GAME_CODE_TYPE_LIVE_CASINO_LUCKY_ZHA_JIN_HUA,
					    'GOLDEN FLOWER' => GAME_CODE_TYPE_LIVE_CASINO_ZHA_JIN_HUA,
					    'SICBO' => GAME_CODE_TYPE_LIVE_CASINO_SICBO,
    				);
					$response = $this->og_connect($arr, $start_time, $end_time, 'ogplus');
					if($response['code'] == '0')
					{
					    if($response['http_code'] == '200'){
					        $result_array = json_decode($response['data'], TRUE);
					        $DBdata['sync_status'] = STATUS_YES;
					        $DBdata['resp_data'] = json_encode($result_array,true);
					        if(!empty($result_array)){
    					        foreach($result_array as $result_row){
    					            $tmp_username = strtolower(explode("_",trim($result_row['membername']))[1]);
    					            $exact_username = $tmp_username;
                                    $status = STATUS_COMPLETE;
                                    if(in_array($result_row['currency'],$currency_one)){
                                        $bet_amount = bcdiv(trim($result_row['bettingamount']) * 1000, 1, 2);
                                        $bet_amount_valid = bcdiv(trim($result_row['validbet']) * 1000, 1, 2);
                                        $win_loss = bcdiv(trim($result_row['winloseamount']) * 1000, 1, 2);
                                        $payout_amount = bcdiv((trim($result_row['bettingamount']) + trim($result_row['winloseamount'])) * 1000, 1, 2);
            						}else{
            							$bet_amount = $result_row['bettingamount'];
                                        $bet_amount_valid = trim($result_row['validbet']);
                                        $win_loss = trim($result_row['winloseamount']);
                                        $payout_amount = trim($result_row['bettingamount']) + trim($result_row['winloseamount']);
            						}
                                    $PBdata = array(
                						'game_provider_code' => $provider_code,
                						'game_type_code' => GAME_LIVE_CASINO,
                						'game_provider_type_code' => $provider_code."_".GAME_LIVE_CASINO,
                						'game_result_type' => $result_type,
                						'game_code' => (isset($game_code_data[strtoupper(trim($result_row['gamename']))]) ? $game_code_data[strtoupper(trim($result_row['gamename']))] : GAME_CODE_TYPE_UNKNOWN),
                						'game_real_code' => trim($result_row['gamename']),
                						'bet_id' => trim($result_row['bettingcode']),
                						'bet_time' => strtotime('+0 hours', strtotime(trim($result_row['bettingdate']))),
                						'bet_amount' => $bet_amount,
                						'bet_amount_valid' => $bet_amount_valid,
                						'payout_time' => strtotime('+0 hours', strtotime(trim($result_row['bettingdate']))),
                						'win_loss' => $win_loss,
                						'game_time' => strtotime('+0 hours', strtotime(trim($result_row['bettingdate']))),
                						'report_time' => strtotime('+0 hours', strtotime(trim($result_row['bettingdate']))),
                						'sattle_time' => strtotime('+0 hours', strtotime(trim($result_row['bettingdate']))),
                						'compare_time' => strtotime('+0 hours', strtotime(trim($result_row['bettingdate']))),
                						'created_date' => time(),
                						'payout_amount' => $payout_amount,
                						'promotion_amount' => $bet_amount_valid,
                						'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                						'status' => $status,
                						'game_username' => trim($result_row['membername']),
                						'player_id' =>  $member_lists[$exact_username],
                						'bet_code' => $result_row['bet'],
                						'game_result' => json_encode($result_row),
                						'win_result' => (isset($win_loss_data[trim($result_row['winloseresult'])]) ? $win_loss_data[trim($result_row['winloseresult'])] : STATUS_UNKNOWN),
                					);
                					if($status == STATUS_COMPLETE){
                						if($PBdata['win_loss'] != 0){
                							$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
                				    	}
                					}
									if( ! in_array($PBdata['bet_id'], $transaction_lists))
									{	
										$PBdata['bet_info'] = json_encode($result_row);
								        $PBdata['insert_type'] = SYNC_DEFAULT;
										array_push($Bdata, $PBdata);
										$PBdataWL = array(
											'player_id' => $PBdata['player_id'],
											'bet_time' => $PBdata['bet_time'],
											'payout_time' => $PBdata['payout_time'],
											'game_provider_code' => $PBdata['game_provider_code'],
											'game_type_code' => $PBdata['game_type_code'],
											'total_bet' => 1,
											'bet_amount' => $PBdata['bet_amount'],
											'bet_amount_valid' => $PBdata['bet_amount_valid'],
											'win_loss' => $PBdata['win_loss'],
										);
										array_push($BUDdata, $PBdataWL);
									}
    					        }
    					    }
					    }
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function sa($member_lists = NULL){
		//10:00
		set_time_limit(0);
		$provider_code = 'SA';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-60 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-70 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+60 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-10 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$game_code_data = array(
						'bac' => GAME_CODE_TYPE_LIVE_CASINO_BACCARAT,
						'dtx' => GAME_CODE_TYPE_LIVE_CASINO_DRAGON_TIGER,
						'sicbo' => GAME_CODE_TYPE_LIVE_CASINO_SICBO,
						'ftan' => GAME_CODE_TYPE_LIVE_CASINO_FAN_TAN,
						'rot' => GAME_CODE_TYPE_LIVE_CASINO_ROULETTE,
						'pokdeng' => GAME_CODE_TYPE_LIVE_CASINO_POK_DENG,
						'andarbahar' => GAME_CODE_TYPE_LIVE_CASINO_ANDAR_BAHAR,
					);			
					$response = $this->sa_connect($arr, $start_time, $end_time);
					if($response['code'] == '0')
					{
						$xml = simplexml_load_string($response['data']);
						$json = json_encode($xml);
						$result_array = json_decode($json, TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '0')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['BetDetailList']['BetDetail']) && sizeof($result_array['BetDetailList']['BetDetail'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
								    if(isset($result_array['BetDetailList']['BetDetail'][0])){
                    					$bet_detail_array = $result_array['BetDetailList']['BetDetail'];
                    				}else{
                    					$bet_detail_array[0] = $result_array['BetDetailList']['BetDetail'];
                    				}
									foreach($bet_detail_array as $result_row){
									    $tmp_username = strtolower(trim($result_row['Username']));
										$exact_username = $tmp_username;
										if($arr['CurrencyType'] == "IDR" || $arr['CurrencyType'] == "KHR" || $arr['CurrencyType'] == "MMK" || $arr['CurrencyType'] == "VND"){
											$bet_amount = trim($result_row['BetAmount']) * 1000;
											$bet_amount_valid = trim($result_row['Rolling']) * 1000;
											$win_loss = trim($result_row['ResultAmount']) * 1000;
										}else{
											$bet_amount = trim($result_row['BetAmount']);
											$bet_amount_valid = trim($result_row['Rolling']);
											$win_loss = trim($result_row['ResultAmount']);
										}
									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => GAME_LIVE_CASINO,
									        'game_result_type' => $result_type,
									        'game_code' => (isset($game_code_data[trim($result_row['GameType'])]) ? $game_code_data[trim($result_row['GameType'])] : GAME_CODE_TYPE_UNKNOWN),
									        'game_real_code' => trim($result_row['GameType']),
									        'bet_id' => trim($result_row['BetID']),
									        'bet_time' => strtotime(trim($result_row['BetTime'])),
									        'game_time' => strtotime(trim($result_row['PayoutTime'])),
									        'report_time' => strtotime(trim($result_row['PayoutTime'])),
									        'bet_amount' => $bet_amount,
									        'bet_amount_valid' => $bet_amount_valid,
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['PayoutTime'])),
									        'sattle_time' => strtotime(trim($result_row['PayoutTime'])),
											'compare_time' => strtotime(trim($result_row['PayoutTime'])),
											'created_date' => time(),
									        'win_loss' => $win_loss,
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'bet_code' => trim($result_row['BetType']),
									        'game_result' => json_encode($result_row['GameResult']),
									        'table_id' => trim($result_row['GameID']),
									        'round' => trim($result_row['Round']),
									        'subround'  => trim($result_row['Set']),
									        'status' => STATUS_CANCEL,
									        'game_username' => $result_row['Username'],
									        'player_id' => $member_lists[$exact_username],
									    );
									     switch(trim($PBdata['game_code']))
										{
											case 'Slot': $PBdata['game_type_code'] = GAME_SLOTS; break;
											case 'Multiplayer Game': $PBdata['game_type_code'] = GAME_FISHING; break;
											default: $PBdata['game_type_code'] = GAME_LIVE_CASINO; break;
										}
										$PBdata['game_provider_type_code'] = $PBdata['game_provider_code']."_".$PBdata['game_type_code'];
									    if($result_row['State'] == true){
									    	$PBdata['status'] = STATUS_COMPLETE;
									    	$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
									    	//promotion
									    	if($PBdata['win_loss'] != 0){
									    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
									    	}
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
											array_push($BUIDdata, $PBdata['bet_id']);
										}
										if($PBdata['status'] == STATUS_COMPLETE){
											$PBdataWL = array(
												'player_id' => $PBdata['player_id'],
												'game_code' => $PBdata['game_code'],
												'bet_time' => $PBdata['bet_time'],
												'payout_time' => $PBdata['payout_time'],
												'game_provider_code' => $PBdata['game_provider_code'],
												'game_type_code' => $PBdata['game_type_code'],
												'total_bet' => 1,
												'bet_amount' => $PBdata['bet_amount'],
												'bet_amount_valid' => $PBdata['bet_amount_valid'],
												'win_loss' => $PBdata['win_loss'],
											);
											array_push($BUDdata, $PBdataWL);
										}
									}
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'game_code' => $transaction_lists_old_row['game_code'],
										'bet_time' => $transaction_lists_old_row['bet_time'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function sp($member_lists = NULL){
		//10:00
		set_time_limit(0);
		$provider_code = 'SP';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-15 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-10 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$response = $this->sp_connect($arr, $start_time, $end_time);
					if($response['code'] == '0')
					{
						$xml = simplexml_load_string($response['data']);
						$json = json_encode($xml);
						$result_array = json_decode($json, TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['ErrorMsgId']) && $result_array['ErrorMsgId'] == '0')
							{
								$DBdata['sync_status'] = STATUS_YES;
								if(isset($result_array['BetDetailList']['BetDetail']) && sizeof($result_array['BetDetailList']['BetDetail'])>0){
									$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
								    if(isset($result_array['BetDetailList']['BetDetail'][0])){
                    					$bet_detail_array = $result_array['BetDetailList']['BetDetail'];
                    				}else{
                    					$bet_detail_array[0] = $result_array['BetDetailList']['BetDetail'];
                    				}
									foreach($bet_detail_array as $result_row){
									    $tmp_username = strtolower(trim($result_row['Username']));
										$exact_username = $tmp_username;
										if($arr['CurrencyType'] == "IDR" || $arr['CurrencyType'] == "KHR" || $arr['CurrencyType'] == "MMK" || $arr['CurrencyType'] == "VND"){
											$bet_amount = trim($result_row['BetAmount']) * 1000;
											$bet_amount_valid = trim($result_row['BetAmount']) * 1000;
											$win_loss = trim($result_row['ResultAmount']) * 1000;
										}else{
											$bet_amount = trim($result_row['BetAmount']);
											$bet_amount_valid = trim($result_row['BetAmount']);
											$win_loss = trim($result_row['ResultAmount']);
										}
										if($result_row['GameType'] == "slot"){
											$game_type_code = GAME_SLOTS;
											$game_code = "Slot";
										}else if($result_row['GameType'] == "multiplayer"){
											$game_type_code = GAME_FISHING;
											$game_code = "Multiplayer Game";
										}else{
											$game_type_code = GAME_OTHERS;
											$game_code = "Others";
										}
									    $PBdata = array(
									        'game_provider_code' => $provider_code,
									        'game_type_code' => $game_type_code,
									        'game_provider_type_code' => $provider_code."_".$game_type_code,
									        'game_result_type' => $result_type,
									        'game_code' => trim($result_row['Detail']),
									        'game_real_code' => trim($result_row['GameType']),
									        'bet_id' => trim($result_row['BetID']),
									        'bet_time' => strtotime(trim($result_row['BetTime'])),
									        'game_time' => strtotime(trim($result_row['PayoutTime'])),
									        'report_time' => strtotime(trim($result_row['PayoutTime'])),
									        'bet_amount' => $bet_amount,
									        'bet_amount_valid' => $bet_amount_valid,
									        'payout_amount' => 0,
									        'promotion_amount' => 0,
									        'payout_time' => strtotime(trim($result_row['PayoutTime'])),
									        'sattle_time' => strtotime(trim($result_row['PayoutTime'])),
											'compare_time' => strtotime(trim($result_row['PayoutTime'])),
											'created_date' => time(),
									        'win_loss' => $win_loss,
									        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
									        'game_result' => json_encode($result_row['Detail']),
									        'table_id' => trim($result_row['GameID']),
									        'status' => STATUS_COMPLETE,
									        'game_username' => $result_row['Username'],
									        'player_id' => $member_lists[$exact_username],
									    );
									    if($PBdata['bet_amount'] == 0){
									    	$PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
									    }else{
									    	$PBdata['payout_amount'] = $PBdata['bet_amount'] + $PBdata['win_loss'];
									    }
								    	//promotion
								    	if($PBdata['win_loss'] != 0){
								    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
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
											array_push($BUIDdata, $PBdata['bet_id']);
										}
										if($PBdata['status'] == STATUS_COMPLETE){
											$PBdataWL = array(
												'player_id' => $PBdata['player_id'],
												'game_code' => $PBdata['game_code'],
												'bet_time' => $PBdata['bet_time'],
												'payout_time' => $PBdata['payout_time'],
												'game_provider_code' => $PBdata['game_provider_code'],
												'game_type_code' => $PBdata['game_type_code'],
												'total_bet' => 1,
												'bet_amount' => $PBdata['bet_amount'],
												'bet_amount_valid' => $PBdata['bet_amount_valid'],
												'win_loss' => $PBdata['win_loss'],
											);
											array_push($BUDdata, $PBdataWL);
										}
									}
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if(!empty($BUIDdata)){
						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
						if( ! empty($transaction_lists_old)){
							foreach($transaction_lists_old as $transaction_lists_old_row){
								if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
									$PBdataWL = array(
										'player_id' => $transaction_lists_old_row['player_id'],
										'game_code' => $transaction_lists_old_row['game_code'],
										'bet_time' => $transaction_lists_old_row['bet_time'],
										'payout_time' => $transaction_lists_old_row['payout_time'],
										'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
										'game_type_code' => $transaction_lists_old_row['game_type_code'],
										'total_bet' => -1,
										'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
										'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
										'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
									);
									array_push($BUDdata, $PBdataWL);
								}
							}
						}
					}
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					if( ! empty($BUdata))
					{
						foreach($BUdata as $BUdataRow){
							$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_PAYOUT_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
						}
					}
					$this->db->trans_complete();
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function spsb_bet($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = 'SPSB';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
		    if($game_result_data['sync_lock'] == STATUS_INACTIVE){
		        $this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-60 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-40 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+30 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
				    $sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-30 minutes' ,$start_time);
					$db_record_end_time = strtotime('+30 minutes' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$game_code_data = array(
                        '1' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_MLB,
                        '2' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_NPB,
                        '3' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_CPBL,
                        '4' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_KBO,
                        '5' => GAME_CODE_TYPE_SPORTBOOK_ICE_HOCKEY,
                        '6' => GAME_CODE_TYPE_SPORTBOOK_BASKETBALL_NBA,
                        '7' => GAME_CODE_TYPE_SPORTBOOK_LOTTERY,
                        '8' => GAME_CODE_TYPE_SPORTBOOK_FOOTBALL,
                        '9' => GAME_CODE_TYPE_SPORTBOOK_TENNIS,
                        '10' => GAME_CODE_TYPE_SPORTBOOK_SOCCER,
                        '11' => GAME_CODE_TYPE_SPORTBOOK_INDEX,
                        '12' => GAME_CODE_TYPE_SPORTBOOK_GREYHOUND_RACE,
                        '13' => GAME_CODE_TYPE_SPORTBOOK_ESPORT,
                        '14' => GAME_CODE_TYPE_SPORTBOOK_OTHER,
                        '20' => GAME_CODE_TYPE_SPORTBOOK_SOCCER_UEFA,
                    );
					$response = $this->spsb_connect($arr, $start_time, $end_time, $sync_type);
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['code']) && $result_array['code'] == '999')
        					{
        					   $DBdata['sync_status'] = STATUS_YES;
        				       if(isset($result_array['data']) && sizeof($result_array['data'])>0){
        				            $transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
                                    foreach($result_array['data'] as $result_row){
                                        if($result_row['payout_time'] == "0000-00-00 00:00:00"){
                                           $payout_time = strtotime($result_row['m_date']);
                                        }else{
                                           $payout_time = strtotime($result_row['payout_time']);
                                        }
                                        $tmp_username = strtolower(trim($result_row['m_id']));
    							        $exact_username = $tmp_username;
        							    $status = STATUS_PENDING;
        								$win_result = STATUS_UNKNOWN;
        								if($result_row['end'] == "1"){
        								    $status = STATUS_COMPLETE;
        								    if($result_row['status_note'] == "Y"){
        								        if($result_row['status'] == "w"){
        								            $win_result = STATUS_WIN;
        								        }else if($result_row['status'] == "l"){
        								            $win_result = STATUS_LOSS;
        								        }else{
        								            $win_result = STATUS_TIE;
        								        }
        								    }else{
        								        $status = STATUS_CANCEL;
        								    }
                                        }
                                        $PBdata = array(
        									'game_provider_code' => $provider_code,
        									'game_type_code' => GAME_SPORTSBOOK,
        									'game_provider_type_code' => $provider_code."_".GAME_SPORTSBOOK,
        									'game_result_type' => $result_type,
        									'game_code' => (isset($game_code_data[trim($result_row['team_no'])]) ? $game_code_data[trim($result_row['team_no'])] : GAME_CODE_TYPE_UNKNOWN),
        									'game_real_code' => trim($result_row['team_no']),
        									'bet_id' => trim($result_row['sn']),
        									'bet_transaction_id' => trim($result_row['gameSN']),
        									'bet_ref_no' => trim($result_row['gsn']),
        									'bet_match_id' => (isset($result_row['gameSN']) ? trim($result_row['gameSN']) : "0"),
        									'bet_time' => strtotime(trim($result_row['m_date'])),
        									'bet_amount' => trim($result_row['gold']),
        									'bet_amount_valid' => trim($result_row['bet_gold']),
        									'payout_time' => $payout_time,
        									'win_loss' => trim($result_row['result_gold']),
        									'game_time' => $payout_time,
        									'report_time' => $payout_time,
        									'sattle_time' => $payout_time,
        									'compare_time' => strtotime(trim($result_row['count_date'])),
        									'created_date' => time(),
        									'payout_amount' => trim($result_row['sum_gold']),
        									'promotion_amount' => trim($result_row['bet_gold']),
        									'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
        									'odds_rate' => trim($result_row['compensate']),
        									'bet_code' => (isset($result_row['fashion']) ? trim($result_row['fashion']) : "0"),
        									'status' => $status,
        									'win_result' => $win_result,
        									'game_username' => trim($result_row['m_id']),
        									'player_id' =>  $member_lists[$exact_username],
        								);
        								if( ! in_array($PBdata['bet_id'], $transaction_lists))
									    {
									        if($status == STATUS_PENDING){
    									        $PBdata['bet_info'] = json_encode($result_row);
    									        $PBdata['insert_type'] = SYNC_DEFAULT;
    									        array_push($Bdata, $PBdata);
    									    }
									    }
        				           }
        				       }
        					}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					$this->db->trans_complete();
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
		    }else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function spsb_payout($member_lists = NULL){
	    set_time_limit(0);
		$provider_code = 'SPSB';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_MODIFIED;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
		    if($game_result_data['sync_lock'] == STATUS_INACTIVE){
		        $this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
		        $arr = json_decode($game_data['api_data'], TRUE);
		        $current_time = time();
				$last_sync_time = strtotime('-15 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-40 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+30 minutes', strtotime($initial_time))));
				}
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				if($end_time <= strtotime('-10 minutes', $current_time))
				{
			        $last_bet_sync_time = 0;
    				$sync_bet_data = $this->report_model->get_game_result_logs($provider_code,$result_type,SYNC_TYPE_ALL);
    				if( ! empty($sync_bet_data))
    				{
    					$last_bet_sync_time = $sync_bet_data['end_time'];
    				}
    				if($last_bet_sync_time > $end_time){
    				    $sys_data = $this->miscellaneous_model->get_miscellaneous();
    					$db_record_start_time = strtotime('-30 days' ,$start_time);
    					$db_record_end_time = strtotime('+30 days' ,$end_time);
    					$DBdata = array(
    						'game_provider_code' => $provider_code,
    						'game_result_type' => $result_type,
    						'game_sync_type' => $sync_type,
    						'start_time' => $start_time,
    						'end_time' => $end_time,
    						'sync_time' => time(),
    						'sync_status' => STATUS_NO,
    						'page_id' => $page_id,
    						'next_id' => $next_id,
    						'resp_data' => '',
    					);
    					$game_code_data = array(
                            '1' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_MLB,
	                        '2' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_NPB,
	                        '3' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_CPBL,
	                        '4' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_KBO,
	                        '5' => GAME_CODE_TYPE_SPORTBOOK_ICE_HOCKEY,
	                        '6' => GAME_CODE_TYPE_SPORTBOOK_BASKETBALL_NBA,
	                        '7' => GAME_CODE_TYPE_SPORTBOOK_LOTTERY,
	                        '8' => GAME_CODE_TYPE_SPORTBOOK_FOOTBALL,
	                        '9' => GAME_CODE_TYPE_SPORTBOOK_TENNIS,
	                        '10' => GAME_CODE_TYPE_SPORTBOOK_SOCCER,
	                        '11' => GAME_CODE_TYPE_SPORTBOOK_INDEX,
	                        '12' => GAME_CODE_TYPE_SPORTBOOK_GREYHOUND_RACE,
	                        '13' => GAME_CODE_TYPE_SPORTBOOK_ESPORT,
	                        '14' => GAME_CODE_TYPE_SPORTBOOK_OTHER,
	                        '20' => GAME_CODE_TYPE_SPORTBOOK_SOCCER_UEFA,
                        );    
                        $response = $this->spsb_connect($arr, $start_time, $end_time, $sync_type);
                        if($response['code'] == '0')
    					{
    						$result_array = json_decode($response['data'], TRUE);
    						if(!empty($result_array))
    						{
    							$DBdata['resp_data'] = json_encode($result_array);
    							if(isset($result_array['code']) && $result_array['code'] == '999')
            					{
            					   $DBdata['sync_status'] = STATUS_YES;
            				       if(isset($result_array['data']) && sizeof($result_array['data'])>0){
            				           foreach($result_array['data'] as $result_row){
            				                $tmp_username = strtolower(trim($result_row['m_id']));
        							        $exact_username = $tmp_username;
            							    $status = STATUS_PENDING;
            								$win_result = STATUS_UNKNOWN;
            								if($result_row['end'] == "1"){
            								    $status = STATUS_COMPLETE;
            								    if($result_row['status_note'] == "Y"){
            								        if($result_row['status'] == "w"){
            								            $win_result = STATUS_WIN;
            								        }else if($result_row['status'] == "l"){
            								            $win_result = STATUS_LOSS;
            								        }else{
            								            $win_result = STATUS_TIE;
            								        }
            								    }else{
            								        $status = STATUS_CANCEL;
            								    }
                                            }
                                            if($status != STATUS_PENDING){
                                                $payout_time = strtotime($result_row['payout_time']);
                                                if(($payout_time >= $start_time) && ($payout_time < $end_time)){
                                                    $PBdata = array(
                    									'game_provider_code' => $provider_code,
                    									'game_type_code' => GAME_SPORTSBOOK,
                    									'game_provider_type_code' => $provider_code."_".GAME_SPORTSBOOK,
                    									'game_result_type' => $result_type,
                    									'game_code' => (isset($game_code_data[trim($result_row['team_no'])]) ? $game_code_data[trim($result_row['team_no'])] : GAME_CODE_TYPE_UNKNOWN),
                    									'game_real_code' => trim($result_row['team_no']),
                    									'bet_id' => trim($result_row['sn']),
                    									'bet_transaction_id' => trim($result_row['gameSN']),
                    									'bet_ref_no' => trim($result_row['gsn']),
                    									'bet_match_id' => (isset($result_row['gameSN']) ? trim($result_row['gameSN']) : "0"),
                    									'bet_time' => strtotime(trim($result_row['m_date'])),
                    									'bet_amount' => trim($result_row['gold']),
                    									'bet_amount_valid' => trim($result_row['bet_gold']),
                    									'payout_time' => $payout_time,
                    									'win_loss' => trim($result_row['result_gold']),
                    									'game_time' => $payout_time,
                    									'report_time' => $payout_time,
                    									'sattle_time' => $payout_time,
                    									'compare_time' => strtotime(trim($result_row['count_date'])),
                    									'created_date' => time(),
                    									'payout_amount' => trim($result_row['sum_gold']),
                    									'promotion_amount' => trim($result_row['bet_gold']),
                    									'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                    									'odds_rate' => trim($result_row['compensate']),
                    									'bet_code' => (isset($result_row['fashion']) ? trim($result_row['fashion']) : "0"),
                    									'status' => $status,
                    									'win_result' => $win_result,
                    									'game_username' => trim($result_row['m_id']),
                    									'player_id' =>  $member_lists[$exact_username],
                    								);
                    								$PBdata['bet_update_info'] = json_encode($result_row);
        									        $PBdata['update_type'] = SYNC_DEFAULT;
        									        array_push($BUdata, $PBdata);
        											array_push($BUIDdata, $PBdata['bet_id']);
                                                }
                                            }
            				           }
            				       }
            					}
    						}
    					}
    					$this->db->trans_start();
    					if(!empty($BUIDdata)){
    						$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_DEFAULT,$db_record_start_time, $db_record_end_time, $BUIDdata);
    						if(!empty($transaction_lists_old)){
    						    foreach($BUdata as $BUdataRow){
    						        if(isset($transaction_lists_old[$BUdataRow['bet_id']])){
    						            if($transaction_lists_old[$BUdataRow['bet_id']]['status'] == STATUS_COMPLETE){
        									$PBdataWL = array(
        										'player_id' => $transaction_lists_old[$BUdataRow['bet_id']]['player_id'],
        										'game_code' => $transaction_lists_old[$BUdataRow['bet_id']]['game_code'],
        										'bet_time' => $transaction_lists_old[$BUdataRow['bet_id']]['bet_time'],
        										'payout_time' => $transaction_lists_old[$BUdataRow['bet_id']]['payout_time'],
        										'game_provider_code' => $transaction_lists_old[$BUdataRow['bet_id']]['game_provider_code'],
        										'game_type_code' => $transaction_lists_old[$BUdataRow['bet_id']]['game_type_code'],
        										'total_bet' => -1,
        										'bet_amount' => ($transaction_lists_old[$BUdataRow['bet_id']]['bet_amount'] * -1),
        										'bet_amount_valid' => ($transaction_lists_old[$BUdataRow['bet_id']]['bet_amount_valid'] * -1),
        										'win_loss' => ($transaction_lists_old[$BUdataRow['bet_id']]['win_loss'] * -1),
        									);
        									array_push($BUDdata, $PBdataWL);
        								}
        								if($BUdataRow['status'] == STATUS_COMPLETE){
											$PBdataWL = array(
												'player_id' => $BUdataRow['player_id'],
												'game_code' => $BUdataRow['game_code'],
												'bet_time' => $BUdataRow['bet_time'],
												'payout_time' => $BUdataRow['payout_time'],
												'game_provider_code' => $BUdataRow['game_provider_code'],
												'game_type_code' => $BUdataRow['game_type_code'],
												'total_bet' => 1,
												'bet_amount' => $BUdataRow['bet_amount'],
												'bet_amount_valid' => $BUdataRow['bet_amount_valid'],
												'win_loss' => $BUdataRow['win_loss'],
											);
											array_push($BUDdata, $PBdataWL);
										}
        								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_DEFAULT,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
    						        }else{
    						            if($BUdataRow['status'] == STATUS_COMPLETE){
    										$PBdataWL = array(
    											'player_id' => $BUdataRow['player_id'],
    											'game_code' => $BUdataRow['game_code'],
    											'bet_time' => $BUdataRow['bet_time'],
    											'payout_time' => $BUdataRow['payout_time'],
    											'game_provider_code' => $BUdataRow['game_provider_code'],
    											'game_type_code' => $BUdataRow['game_type_code'],
    											'total_bet' => 1,
    											'bet_amount' => $BUdataRow['bet_amount'],
    											'bet_amount_valid' => $BUdataRow['bet_amount_valid'],
    											'win_loss' => $BUdataRow['win_loss'],
    										);
    										array_push($BUDdata, $PBdataWL);
    									}
    									array_push($Bdata, $BUdataRow);
    						        }
    						    }
    						}else{
    						    foreach($BUdata as $BUdataRow){
    						        if($BUdataRow['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $BUdataRow['player_id'],
											'game_code' => $BUdataRow['game_code'],
											'bet_time' => $BUdataRow['bet_time'],
											'payout_time' => $BUdataRow['payout_time'],
											'game_provider_code' => $BUdataRow['game_provider_code'],
											'game_type_code' => $BUdataRow['game_type_code'],
											'total_bet' => 1,
											'bet_amount' => $BUdataRow['bet_amount'],
											'bet_amount_valid' => $BUdataRow['bet_amount_valid'],
											'win_loss' => $BUdataRow['win_loss'],
										);
										array_push($BUDdata, $PBdataWL);
									}
									array_push($Bdata, $BUdataRow);
    						    }
    						}
    					}
    					$this->db->insert('game_result_logs', $DBdata);
    					if( ! empty($Bdata))
    					{
    						$this->db->insert_batch('transaction_report', $Bdata);
    					}
    					if( ! empty($BUDdata))
    					{
    						$this->db->insert_batch('win_loss_logs', $BUDdata);
    					}
    					$this->db->trans_complete();
    				}
				}
		    }else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function spsb2($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'SPSB';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_MODIFIED;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$next_id = 0;
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-60 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+30 minutes', strtotime($initial_time))));
				}
				$game_code_data = array(
                    '1' => GAME_CODE_TYPE_SPORTBOOK_SOCCER,
                    '3' => GAME_CODE_TYPE_SPORTBOOK_BASKETBALL_NBA,
                    '4' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_MLB,
                    '5' => GAME_CODE_TYPE_SPORTBOOK_FOOTBALL,
                    '11' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_CPBL,
                    '12' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_NPB,
                    '13' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL,
                    '14' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL_KBO,
                    '16' => GAME_CODE_TYPE_SPORTBOOK_BASKETBALL,
                    '21' => GAME_CODE_TYPE_SPORTBOOK_PINGPONG,
                    '22' => GAME_CODE_TYPE_SPORTBOOK_BADMINTON,
                    '23' => GAME_CODE_TYPE_SPORTBOOK_VOLLEYBALL,
                    '24' => GAME_CODE_TYPE_SPORTBOOK_SNOOKER,
                    '31' => GAME_CODE_TYPE_SPORTBOOK_SOCCER_FIFA,
                    '32' => GAME_CODE_TYPE_SPORTBOOK_SOCCER_UEFA,
                    '55' => GAME_CODE_TYPE_SPORTBOOK_TENNIS,
                    '72' => GAME_CODE_TYPE_SPORTBOOK_GREYHOUND_RACE,
                    '82' => GAME_CODE_TYPE_SPORTBOOK_ICE_HOCKEY,
                    '83' => GAME_CODE_TYPE_SPORTBOOK_LOTTERY,
                    '84' => GAME_CODE_TYPE_SPORTBOOK_INDEX,
                    '85' => GAME_CODE_TYPE_SPORTBOOK_ESPORT,
                    '101' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL,
                    '102' => GAME_CODE_TYPE_SPORTBOOK_BASEBALL,
                );
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-15 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-7 days' ,$start_time);
					$db_record_end_time = strtotime('+7 days' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$is_loop = TRUE;
					while($is_loop == TRUE){
						$Bdata = array();
						$BUdata = array();
						$BUIDdata = array();
						$BUDdata = array();
						$DBdata['sync_status'] = STATUS_NO;
						$DBdata['page_id'] = $page_id;
						$DBdata['resp_data'] = '';
						$response = $this->spsb2_connect($arr, $start_time, $end_time, $page_id);
						if($response['code'] == '0')
						{
							$result_array = json_decode($response['data'], TRUE);
							if( ! empty($result_array))
							{
								if(isset($result_array['Code']) && $result_array['Code'] == '200'){
									$DBdata['resp_data'] = json_encode($result_array);
									$DBdata['sync_status'] = STATUS_YES;
									if(isset($result_array['Data']['List']) && sizeof($result_array['Data']['List'])>0){
										if($is_retrieve == FALSE){
											$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
										}
										foreach($result_array['Data']['List'] as $result_row){
                    		                $tmp_username = strtolower(trim($result_row['User']));
                    		                $exact_username = $tmp_username;
                    		                if(trim($result_row['BetType']) == "1"){
                		                        $game_code = (isset($game_code_data[trim($result_row['dataBet'][0]['CatID'])]) ? $game_code_data[trim(trim($result_row['dataBet'][0]['CatID']))] : "Other");
                		                        $game_real_code = trim($result_row['dataBet'][0]['CatID']);
                		                    }else{
                		                        $game_code = "Parlay";
                		                        $game_real_code = $result_row['BetType'];
                		                    }
                		                    $status = STATUS_PENDING;
                    		                $win_result = STATUS_UNKNOWN;
                		                    $bet_amount_valid = trim($result_row['EffectiveAmount']);
                		                    if(trim($result_row['StatusType']) == "D" || trim($result_row['StatusType']) == "V"){
                		                        $status = STATUS_CANCEL;
                		                    }else{
                		                        if(trim($result_row['IsPayout']) == "1"){
                		                            $status = STATUS_COMPLETE;
                		                        }
                		                    }
                		                    if(trim($result_row['WinLoseStatus']) == "D"){
                		                        $win_result = STATUS_TIE;
                		                    }else if(trim($result_row['WinLoseStatus']) == "WA" || trim($result_row['WinLoseStatus']) == "WH"){
                		                        if($status == STATUS_COMPLETE){
                		                            if($game_code == "Parlay"){
                		                                $bet_amount_valid = trim($result_row['Amount']);
                		                            }
                		                        }
                		                        $win_result = STATUS_WIN;
                		                    }else if(trim($result_row['WinLoseStatus']) == "LA" || trim($result_row['WinLoseStatus']) == "LH"){
                		                        $win_result = STATUS_LOSS;
                		                    }
                		                    $PBdata = array(
        									    'game_provider_code' => $provider_code,
        								        'game_type_code' => GAME_SPORTSBOOK,
            									'game_provider_type_code' => $provider_code."_".GAME_SPORTSBOOK,
            									'game_result_type' => $result_type,
            									'game_code' => $game_code,
            									'game_real_code' => $game_real_code,
            									'bet_id' => trim($result_row['TicketID']),
            									'bet_time' => strtotime(trim($result_row['BetTimeStr'])),
            									'bet_amount' => trim($result_row['Amount']),
            									'bet_amount_valid' => $bet_amount_valid,
            									'payout_time' => strtotime(trim($result_row['UpdateTimeStr'])),
            									'win_loss' => trim($result_row['ResultAmount']),
            									'game_time' => strtotime(trim($result_row['UpdateTimeStr'])),
            									'report_time' => strtotime(trim($result_row['AccDateStr'])),
            									'sattle_time' => strtotime(trim($result_row['UpdateTimeStr'])),
            									'compare_time' => strtotime(trim($result_row['AccDateStr'])),
            									'created_date' => time(),
            									'payout_amount' => trim($result_row['ResultAmount']) + $bet_amount_valid,
            									'promotion_amount' => $bet_amount_valid,
            									'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
            									'bet_code' => (isset($result_row['BetType']) ? trim($result_row['BetType']) : "0"),
            									'status' => $status,
            									'win_result' => $win_result,
            									'game_username' => trim($result_row['User']),
            									'player_id' =>  $member_lists[$exact_username],
        							        );
        							        if($PBdata['win_loss'] != 0){
    									    	$PBdata['promotion_amount'] = trim($PBdata['bet_amount_valid']);
    									    }else{
    									        $PBdata['payout_amount'] = 0;
    									    }
    									    if(isset($member_lists[$exact_username])){
    									        if( ! in_array($PBdata['bet_id'], $transaction_lists))
    											{				
    												$PBdata['bet_info'] = json_encode($result_row);
    										        $PBdata['insert_type'] = SYNC_DEFAULT;
    												array_push($Bdata, $PBdata);
    											}else{
    												$PBdata['bet_update_info'] = json_encode($result_row);
    										        $PBdata['update_type'] = SYNC_DEFAULT;
    												array_push($BUdata, $PBdata);
    												array_push($BUIDdata, $PBdata['bet_id']);
    											}
        									    if($PBdata['status'] == STATUS_COMPLETE){
    												$PBdataWL = array(
    													'player_id' => $PBdata['player_id'],
    													'bet_time' => $PBdata['bet_time'],
    													'payout_time' => $PBdata['payout_time'],
    													'game_provider_code' => $PBdata['game_provider_code'],
    													'game_type_code' => $PBdata['game_type_code'],
    													'game_code' => $PBdata['game_code'],
    													'total_bet' => 1,
    													'bet_amount' => $PBdata['bet_amount'],
    													'bet_amount_valid' => $PBdata['bet_amount_valid'],
    													'win_loss' => $PBdata['win_loss'],
    												);
    												array_push($BUDdata, $PBdataWL);
    											}   
    									    }
                		                }
									}else{
										$is_loop = FALSE;
									}
									$page_id++;
								}else{
									$is_loop = FALSE;
								}
							}else{
								$is_loop = FALSE;
							}
						}else{
							$is_loop = FALSE;
						}
						$this->db->insert('game_result_logs', $DBdata);
						$result_promotion_reset = array('promotion_amount' => 0);
						if(!empty($BUIDdata)){
							$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
							if( ! empty($transaction_lists_old)){
								foreach($transaction_lists_old as $transaction_lists_old_row){
									if($transaction_lists_old_row['status'] == STATUS_COMPLETE){
										$PBdataWL = array(
											'player_id' => $transaction_lists_old_row['player_id'],
											'bet_time' => $transaction_lists_old_row['bet_time'],
											'payout_time' => $transaction_lists_old_row['payout_time'],
											'game_provider_code' => $transaction_lists_old_row['game_provider_code'],
											'game_type_code' => $transaction_lists_old_row['game_type_code'],
											'game_code' => $transaction_lists_old_row['game_code'],
											'total_bet' => -1,
											'bet_amount' => ($transaction_lists_old_row['bet_amount'] * -1),
											'bet_amount_valid' => ($transaction_lists_old_row['bet_amount_valid'] * -1),
											'win_loss' => ($transaction_lists_old_row['win_loss'] * -1),
										);
										array_push($BUDdata, $PBdataWL);
									}
								}
							}
						}
						if( ! empty($Bdata))
						{
							$this->db->insert_batch('transaction_report', $Bdata);
						}
						if( ! empty($BUDdata))
						{
							$this->db->insert_batch('win_loss_logs', $BUDdata);
						}
						if( ! empty($BUdata))
						{
							foreach($BUdata as $BUdataRow){
								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
							}
						}
						sleep(5);
					}
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	public function splt($member_lists = NULL, $result_type = NULL){
		//11:50
		set_time_limit(0);
		$provider_code = 'SPLT';
		$result_type = $result_type;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
		    if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				if($end_time <= strtotime('-40 minutes', $current_time))
				{
					$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+30 minutes', strtotime($initial_time))));
				}
				$final_time = strtotime(date('Y-m-d 00:00:00', strtotime('+1 days', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$Bdata = array();
				$BUdata = array();
				$BUIDdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
				    $sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-2 days' ,$start_time);
					$db_record_end_time = strtotime('+2 days' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
                    $response = $this->splt_connect($arr, $start_time, $end_time, $result_type);
                    if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if(!empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['code']) && $result_array['code'] == '999')
        					{
        					    $DBdata['sync_status'] = STATUS_YES;
        					    //payout
        					    $max_bet_time = 0;
        					    $is_run_bet_data = true;
        					    if(isset($result_array['State']) && ($result_array['State'] == 1)){
        					        $game_result = $result_array['Lottery'];
        					        $match_id = $result_array['Name'];
        					        $special_no = (isset($result_array['SpecialNo']) ? $result_array['SpecialNo'] : "");
        					        if(isset($result_array['Data']) && sizeof($result_array['Data'])>0){
        					            foreach($result_array['Data'] as $result_type_row){
        					                $bet_id = $result_type_row[0];
        					                $bet_time = strtotime($result_type_row[1]);
        					                if($bet_time > $max_bet_time){
        					                    $max_bet_time = $bet_time;
        					                }
        					                $payout_time = time();
        					                $tmp_username = strtolower(trim($result_type_row['2']));
        					                $exact_username = ((substr($tmp_username, 0, strlen($arr['Prefix'])) == strtolower($arr['Prefix'])) ? substr($tmp_username, strlen($arr['Prefix'])) : $tmp_username);
        					                $game_code = trim($result_type_row['3'])."_".trim($result_type_row['4']);
        					                if(isset($result_type_row[5]) && sizeof($result_type_row[5])>0){
        					                    $i = 0;
        					                    foreach($result_type_row[5] as $result_row){
        					                        $status = STATUS_COMPLETE;
                    								$win_result = STATUS_UNKNOWN;
                    								if($result_row[5] == "0"){
                    								    if($result_row['2'] == 0){
                    								        $win_result = STATUS_LOSS;
                    								    }else if($result_row['2'] == $result_row['1']){
                    								        $win_result = STATUS_TIE;
                    								    }else{
                    								        $win_result = STATUS_WIN;
                    								    }
                                                    }else{
                								        $status = STATUS_CANCEL;
                								    }
                								    $PBdata = array(
                    									'game_provider_code' => $provider_code,
                    									'game_type_code' => GAME_LOTTERY,
                    									'game_provider_type_code' => $provider_code."_".GAME_LOTTERY,
                    									'game_result_type' => $result_type,
                    									'game_code' => $result_type,
                    									'game_real_code' => $game_code,
                    									'bet_id' => $tmp_username."_".$result_type."_".$bet_id."_".$bet_time."_".$result_type_row['3']."_".$result_type_row['4']."_".$i,
                    									'bet_transaction_id' => $i,
                    									'bet_ref_no' => $bet_id,
                    									'bet_match_id' => $match_id,
                    									'bet_time' => $bet_time,
                    									'bet_amount' => trim($result_row['1']),
                    									'bet_amount_valid' => trim($result_row['1']) - bcdiv(trim($result_row['4']),1,2),
                    									'payout_time' => $payout_time,
                    									'win_loss' => 0,
                    									'game_time' => $payout_time,
                    									'report_time' => $payout_time,
                    									'sattle_time' => $payout_time,
                    									'compare_time' => $payout_time,
                    									'created_date' => time(),
                    									'payout_amount' => trim($result_row['2']),
                    									'promotion_amount' => trim($result_row['1']),
                    									'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                    									'odds_rate' => trim($result_row['3']),
                    									'bet_code' => trim($result_row['0']),
                    									'status' => $status,
                    									'win_result' => $win_result,
                    									'game_username' => $tmp_username,
                    									'player_id' => $member_lists[$exact_username],
                    								);
                    								$PBdata['win_loss'] = $PBdata['payout_amount'] - $PBdata['bet_amount'] + bcdiv(trim($result_row['4']),1,2);
                    								$bet_array = array(
						        						"State" => $result_array['State'],
						        						"Name" => $match_id,
						        						"Lottery" => $game_result,
						        						"SpecialNo" => $special_no,
						        						"Game" => $result_type,
						        						"Data" => array(
						        							0 => $result_type_row[0],
						        							1 => $result_type_row[1],
						        							2 => $result_type_row[2],
						        							3 => $result_type_row[3],
						        							4 => $result_type_row[4],
						        							5 => $result_row,
						        						),
						        					);
                    								$PBdata['bet_update_info'] = json_encode($bet_array,true);
        									        $PBdata['update_type'] = SYNC_DEFAULT;
        									        array_push($BUdata, $PBdata);
        											array_push($BUIDdata, $PBdata['bet_id']);
        											$i++;
        					                    }
        					                }
        					            }
        					        }
        					        if($max_bet_time >= $end_time){
        					            $is_run_bet_data = true;
        					        }else{
        					            $is_run_bet_data = false;
        					        }
        					    }
        					    if($is_run_bet_data){
        					        //reset
        					        $BUdata = array();
        					        $BUIDdata = array();
        					        $game_result = $result_array['Lottery'];
        					        $match_id = $result_array['Name'];
        					        $special_no = (isset($result_array['SpecialNo']) ? $result_array['SpecialNo'] : "");
        					        if(isset($result_array['Data']) && sizeof($result_array['Data'])>0){
        					            $transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
        					            foreach($result_array['Data'] as $result_type_row){
        					                $bet_id = $result_type_row[0];
        					                $bet_time = strtotime($result_type_row[1]);
        					                $tmp_username = strtolower(trim($result_type_row['2']));
        					                $exact_username = ((substr($tmp_username, 0, strlen($arr['Prefix'])) == strtolower($arr['Prefix'])) ? substr($tmp_username, strlen($arr['Prefix'])) : $tmp_username);
        					                $game_code = trim($result_type_row['3'])."_".trim($result_type_row['4']);
        					                if(($bet_time >= $start_time) && ($bet_time < $end_time)){
            					                if(isset($result_type_row[5]) && sizeof($result_type_row[5])>0){
            					                    $i = 0;
            					                    foreach($result_type_row[5] as $result_row){
            					                        $status = STATUS_PENDING;
                        								$win_result = STATUS_UNKNOWN;
                    								    $PBdata = array(
                        									'game_provider_code' => $provider_code,
                        									'game_type_code' => GAME_LOTTERY,
                        									'game_provider_type_code' => $provider_code."_".GAME_LOTTERY,
                        									'game_result_type' => $result_type,
                        									'game_code' => $result_type,
                        									'game_real_code' => $game_code,
                        									'bet_id' => $tmp_username."_".$result_type."_".$bet_id."_".$bet_time."_".$result_type_row['3']."_".$result_type_row['4']."_".$i,
                        									'bet_transaction_id' => $i,
                        									'bet_ref_no' => $bet_id,
                        									'bet_match_id' => $match_id,
                        									'bet_time' => $bet_time,
                        									'bet_amount' => trim($result_row['1']),
                        									'bet_amount_valid' => trim($result_row['1']) - bcdiv(trim($result_row['4']),1,2),
                        									'payout_time' => 0,
                        									'win_loss' => 0,
                        									'game_time' => 0,
                        									'report_time' => 0,
                        									'sattle_time' => 0,
                        									'compare_time' => 0,
                        									'created_date' => time(),
                        									'payout_amount' => trim($result_row['2']),
                        									'promotion_amount' => trim($result_row['1']),
                        									'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
                        									'odds_rate' => trim($result_row['3']),
                        									'bet_code' => trim($result_row['0']),
                        									'status' => $status,
                        									'win_result' => $win_result,
                        									'game_username' => $tmp_username,
                        									'player_id' => $member_lists[$exact_username],
                        								);
                        								$PBdata['win_loss'] = $PBdata['payout_amount'] - $PBdata['bet_amount'] + bcdiv(trim($result_row['4']),1,2);
                        								$bet_array = array(
							        						"State" => $result_array['State'],
							        						"Name" => $match_id,
							        						"Lottery" => $game_result,
							        						"SpecialNo" => $special_no,
							        						"Game" => $result_type,
							        						"Data" => array(
							        							0 => $result_type_row[0],
							        							1 => $result_type_row[1],
							        							2 => $result_type_row[2],
							        							3 => $result_type_row[3],
							        							4 => $result_type_row[4],
							        							5 => $result_row,
							        						),
							        					);
                        								if( ! in_array($PBdata['bet_id'], $transaction_lists))
    										            {	
                            								$PBdata['bet_info'] = json_encode($bet_array,true);
                									        $PBdata['insert_type'] = SYNC_DEFAULT;
                											array_push($Bdata, $PBdata);
    										            }
            											$i++;
            					                    }
            					                }
        					                }
        					            }
        					        }
        					    }else{
        					    	$DBdata['end_time'] = $final_time;
        					    }
        					}
						}
					}
					$this->db->trans_start();
					if(!empty($BUIDdata)){
    					$transaction_lists_old = $this->report_model->get_transaction_record_old($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time, $BUIDdata);
    					if(!empty($transaction_lists_old)){
    					    foreach($BUdata as $BUdataRow){
    					        if(isset($transaction_lists_old[$BUdataRow['bet_id']])){
    					            if($transaction_lists_old[$BUdataRow['bet_id']]['status'] == STATUS_COMPLETE){
    									$PBdataWL = array(
    										'player_id' => $transaction_lists_old[$BUdataRow['bet_id']]['player_id'],
    										'game_code' => $transaction_lists_old[$BUdataRow['bet_id']]['game_code'],
    										'bet_time' => $transaction_lists_old[$BUdataRow['bet_id']]['bet_time'],
    										'payout_time' => $transaction_lists_old[$BUdataRow['bet_id']]['payout_time'],
    										'game_provider_code' => $transaction_lists_old[$BUdataRow['bet_id']]['game_provider_code'],
    										'game_type_code' => $transaction_lists_old[$BUdataRow['bet_id']]['game_type_code'],
    										'total_bet' => -1,
    										'bet_amount' => ($transaction_lists_old[$BUdataRow['bet_id']]['bet_amount'] * -1),
    										'bet_amount_valid' => ($transaction_lists_old[$BUdataRow['bet_id']]['bet_amount_valid'] * -1),
    										'win_loss' => ($transaction_lists_old[$BUdataRow['bet_id']]['win_loss'] * -1),
    									);
    									array_push($BUDdata, $PBdataWL);
    								}
    								if($BUdataRow['status'] == STATUS_COMPLETE){
    									$PBdataWL = array(
    										'player_id' => $BUdataRow['player_id'],
    										'game_code' => $BUdataRow['game_code'],
    										'bet_time' => $BUdataRow['bet_time'],
    										'payout_time' => $BUdataRow['payout_time'],
    										'game_provider_code' => $BUdataRow['game_provider_code'],
    										'game_type_code' => $BUdataRow['game_type_code'],
    										'total_bet' => 1,
    										'bet_amount' => $BUdataRow['bet_amount'],
    										'bet_amount_valid' => $BUdataRow['bet_amount_valid'],
    										'win_loss' => $BUdataRow['win_loss'],
    									);
    									array_push($BUDdata, $PBdataWL);
    								}
    								$this->report_model->update_transaction_record($provider_code,$result_type,UPDATE_TYPE_BET_TIME,$db_record_start_time, $db_record_end_time,$BUdataRow['bet_id'],$BUdataRow);
    					        }else{
    					            if($BUdataRow['status'] == STATUS_COMPLETE){
    									$PBdataWL = array(
    										'player_id' => $BUdataRow['player_id'],
    										'game_code' => $BUdataRow['game_code'],
    										'bet_time' => $BUdataRow['bet_time'],
    										'payout_time' => $BUdataRow['payout_time'],
    										'game_provider_code' => $BUdataRow['game_provider_code'],
    										'game_type_code' => $BUdataRow['game_type_code'],
    										'total_bet' => 1,
    										'bet_amount' => $BUdataRow['bet_amount'],
    										'bet_amount_valid' => $BUdataRow['bet_amount_valid'],
    										'win_loss' => $BUdataRow['win_loss'],
    									);
    									array_push($BUDdata, $PBdataWL);
    								}
    								array_push($Bdata, $BUdataRow);
    					        }
    					    }
    					}else{
    					    foreach($BUdata as $BUdataRow){
    					        if($BUdataRow['status'] == STATUS_COMPLETE){
    								$PBdataWL = array(
    									'player_id' => $BUdataRow['player_id'],
    									'game_code' => $BUdataRow['game_code'],
    									'bet_time' => $BUdataRow['bet_time'],
    									'payout_time' => $BUdataRow['payout_time'],
    									'game_provider_code' => $BUdataRow['game_provider_code'],
    									'game_type_code' => $BUdataRow['game_type_code'],
    									'total_bet' => 1,
    									'bet_amount' => $BUdataRow['bet_amount'],
    									'bet_amount_valid' => $BUdataRow['bet_amount_valid'],
    									'win_loss' => $BUdataRow['win_loss'],
    								);
    								array_push($BUDdata, $PBdataWL);
    							}
    							array_push($Bdata, $BUdataRow);
    					    }
    					}
    				}
					$this->db->insert('game_result_logs', $DBdata);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
		    }else{
		        $time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
		    }
		}else{
			echo EXIT_ERROR;
		}
	}
	public function wm($member_lists = NULL){
		set_time_limit(0);
		$provider_code = 'WM';
		$result_type = GAME_ALL;
		$sync_type = SYNC_TYPE_ALL;
		$game_data = $this->report_model->get_wager_game_data($provider_code);
		$game_result_data = $this->report_model->get_game_result($provider_code,$result_type,$sync_type);
		if(!empty($game_data) &&  !empty($game_result_data))
		{
			if($game_result_data['sync_lock'] == STATUS_INACTIVE){
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_ACTIVE);
				$arr = json_decode($game_data['api_data'], TRUE);
				$current_time = time();
				$last_sync_time = strtotime('-10 minutes', $current_time);
				$sync_data = $this->report_model->get_game_result_logs($provider_code,$result_type,$sync_type);
				if( ! empty($sync_data))
				{
					$last_sync_time = $sync_data['end_time'];
				}
				$initial_time = date('Y-m-d H:i:00', $last_sync_time);
				$start_time = strtotime($initial_time);
				$end_time = strtotime(date('Y-m-d H:i:00', strtotime('+5 minutes', strtotime($initial_time))));
				$page_id = 1;
				$page_total = 1;
				$current_page = 0;
				$next_id = 0;
				$Bdata = array();
				$BUdata = array();
				$BUDdata = array();
				$is_loop = TRUE;
				$is_retrieve = FALSE;
				//Must 5 minutes range from current time
				if($end_time <= strtotime('-5 minutes', $current_time))
				{
					$sys_data = $this->miscellaneous_model->get_miscellaneous();
					$db_record_start_time = strtotime('-1 days' ,$start_time);
					$db_record_end_time = strtotime('+1 days' ,$end_time);
					$DBdata = array(
						'game_provider_code' => $provider_code,
						'game_result_type' => $result_type,
						'game_sync_type' => $sync_type,
						'start_time' => $start_time,
						'end_time' => $end_time,
						'sync_time' => time(),
						'sync_status' => STATUS_NO,
						'page_id' => $page_id,
						'next_id' => $next_id,
						'resp_data' => '',
					);
					$game_code_data = array(
						'101' => GAME_CODE_TYPE_LIVE_CASINO_BACCARAT,
						'102' => GAME_CODE_TYPE_LIVE_CASINO_DRAGON_TIGER,
						'103' => GAME_CODE_TYPE_LIVE_CASINO_ROULETTE,
						'104' => GAME_CODE_TYPE_LIVE_CASINO_SICBO,
						'105' => GAME_CODE_TYPE_LIVE_CASINO_BULL_BULL,
						'106' => GAME_CODE_TYPE_LIVE_CASINO_THREE_FACE_POKER,
						'107' => GAME_CODE_TYPE_LIVE_CASINO_FAN_TAN,
						'108' => GAME_CODE_TYPE_LIVE_CASINO_SEDIE,
						'110' => GAME_CODE_TYPE_LIVE_CASINO_FISH_PRAWN_CRAB,
						'111' => GAME_CODE_TYPE_LIVE_CASINO_ZHA_JIN_HUA,
						'112' => "Wenzhou Pai Gow",
						'113' => "Mahjong Tiles",
						'128' => GAME_CODE_TYPE_LIVE_CASINO_ANDAR_BAHAR,
					);
					$response = $this->wm_connect($arr, $start_time, $end_time);
					if($response['code'] == '0')
					{
						$result_array = json_decode($response['data'], TRUE);
						if( ! empty($result_array))
						{
							$DBdata['resp_data'] = json_encode($result_array);
							if(isset($result_array['errorCode']) && ($result_array['errorCode'] == '0' OR $result_array['errorCode'] == '107'))
							{
								$DBdata['sync_status'] = STATUS_YES;
								for($i=0;$i<sizeof($result_array['result']);$i++)
								{
									if($is_retrieve == FALSE){
										$transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_PAYOUT,$db_record_start_time, $db_record_end_time);
										$is_retrieve = TRUE;
									}
									//Response time (UTC +8)
									if(isset($result_array['result'][$i]['Tip']))
									{
										$tmp_username = strtolower(trim($result_array['result'][$i]['user']));
										$exact_username = $tmp_username;
										if($arr['CurrencyType'] == "IDR"){
											$bet_amount = trim($result_array['result'][$i]['Tip']) * 1000;
											$bet_amount_valid = trim($result_array['result'][$i]['Tip']) * 1000;
											$win_loss = trim($result_array['result'][$i]['winLoss']) * 1000;
											$payout_amount = trim($result_array['result'][$i]['Tip']) * 1000;
										}else{
											$bet_amount = trim($result_array['result'][$i]['Tip']);
											$bet_amount_valid = trim($result_array['result'][$i]['Tip']);
											$win_loss = trim($result_array['result'][$i]['winLoss']);
											$payout_amount = trim($result_array['result'][$i]['Tip']);
										}
										$PBdata = array(
											'game_provider_code' => $provider_code,
											'game_type_code' => GAME_LIVE_CASINO,
											'game_provider_type_code' => $provider_code."_".GAME_LIVE_CASINO,
											'game_result_type' => $result_type,
											'game_code' => (isset($game_code_data[trim($result_array['result'][$i]['gid'])]) ? $game_code_data[trim($result_array['result'][$i]['gid'])] : GAME_CODE_TYPE_UNKNOWN),
											'game_real_code' => trim($result_array['result'][$i]['gid']),
											'bet_id' => trim($result_array['result'][$i]['betId']),
											'bet_time' => strtotime(trim($result_array['result'][$i]['betTime'])),
											'game_time' => strtotime(trim($result_array['result'][$i]['betTime'])),
									       	'report_time' => strtotime(trim($result_array['result'][$i]['betTime'])),
											'bet_amount' => $bet_amount,
											'bet_amount_valid' => $bet_amount_valid,
											'payout_time' => strtotime(trim($result_array['result'][$i]['betTime'])),
											'sattle_time' => strtotime(trim($result_array['result'][$i]['betTime'])),
											'compare_time' => strtotime(trim($result_array['result'][$i]['betTime'])),
											'created_date' => time(),
											'payout_amount' => $payout_amount,
											'promotion_amount' => 0,
											'win_loss' => $win_loss,
											'game_round_type' => GAME_ROUND_TYPE_TIP,
											'status' => STATUS_COMPLETE,
											'game_username' => trim($result_array['result'][$i]['username']),
											'bet_code' => '',
											'round' => trim($result_array['result'][$i]['round']),
											'subround' => trim($result_array['result'][$i]['subround']),
											'table_id' => trim($result_array['result'][$i]['tableId']),
											'game_result' => '',
											'player_id' => $member_lists[$exact_username],
										);
									}else{
										$tmp_username = strtolower(trim($result_array['result'][$i]['user']));
										$exact_username = $tmp_username;
										if($arr['CurrencyType'] == "IDR"){
											$bet_amount = trim($result_array['result'][$i]['bet']) * 1000;
											$bet_amount_valid = trim($result_array['result'][$i]['validbet']) * 1000;
											$win_loss = trim($result_array['result'][$i]['winLoss']) * 1000;
											$payout_amount = trim($result_array['result'][$i]['result']) * 1000;
										}else{
											$bet_amount = trim($result_array['result'][$i]['bet']);
											$bet_amount_valid = trim($result_array['result'][$i]['validbet']);
											$win_loss = trim($result_array['result'][$i]['winLoss']);
											$payout_amount = trim($result_array['result'][$i]['result']);
										}
										$PBdata = array(
											'game_provider_code' => $provider_code,
											'game_type_code' => GAME_LIVE_CASINO,
											'game_provider_type_code' => $provider_code."_".GAME_LIVE_CASINO,
											'game_result_type' => $result_type,
											'game_code' => (isset($game_code_data[trim($result_array['result'][$i]['gid'])]) ? $game_code_data[trim($result_array['result'][$i]['gid'])] : GAME_CODE_TYPE_UNKNOWN),
											'game_real_code' => trim($result_array['result'][$i]['gid']),
											'bet_id' => trim($result_array['result'][$i]['betId']),
											'bet_time' => strtotime(trim($result_array['result'][$i]['betTime'])),
											'game_time' => strtotime(trim($result_array['result'][$i]['payout_time'])),
									       	'report_time' => strtotime(trim($result_array['result'][$i]['payout_time'])),
											'bet_amount' => $bet_amount,
											'bet_amount_valid' => $bet_amount_valid,
											'payout_time' => strtotime(trim($result_array['result'][$i]['settime'])),
											'sattle_time' => strtotime(trim($result_array['result'][$i]['settime'])),
											'compare_time' => strtotime(trim($result_array['result'][$i]['settime'])),
											'created_date' => time(),
											'payout_amount' => $payout_amount,
									        'promotion_amount' => 0,
											'win_loss' => $win_loss,
											'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
											'status' => STATUS_COMPLETE,
											'game_username' => trim($result_array['result'][$i]['user']),
											'bet_code' => trim($result_array['result'][$i]['betCode']),
											'round' => trim($result_array['result'][$i]['round']),
											'subround' => trim($result_array['result'][$i]['subround']),
											'table_id' => trim($result_array['result'][$i]['tableId']),
											'game_result' => trim($result_array['result'][$i]['gameResult']),
											'player_id' => $member_lists[$exact_username],
										);
										if($PBdata['win_loss'] != 0){
								    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
								    	}
									}
									if( ! in_array($PBdata['bet_id'], $transaction_lists))
									{					
										$PBdata['bet_info'] = json_encode($result_array['result'][$i]);
								        $PBdata['insert_type'] = SYNC_DEFAULT;
										array_push($Bdata, $PBdata);
										if($PBdata['status'] == STATUS_COMPLETE){
											$PBdataWL = array(
												'player_id' => $PBdata['player_id'],
												'game_code' => $PBdata['game_code'],
												'bet_time' => $PBdata['bet_time'],
												'payout_time' => $PBdata['payout_time'],
												'game_provider_code' => $PBdata['game_provider_code'],
												'game_type_code' => $PBdata['game_type_code'],
												'total_bet' => 1,
												'bet_amount' => $PBdata['bet_amount'],
												'bet_amount_valid' => $PBdata['bet_amount_valid'],
												'win_loss' => $PBdata['win_loss'],
											);
											array_push($BUDdata, $PBdataWL);
										}
									}
								}
							}
						}
					}
					$this->db->trans_start();
					$this->db->insert('game_result_logs', $DBdata);
					$result_promotion_reset = array('promotion_amount' => 0);
					if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
					$this->db->trans_complete();
				}
				$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				echo EXIT_SUCCESS;
			}else{
				$time = time() - 3600;
				$sync_pending_data = $this->report_model->get_game_result_sync_time_logs($provider_code,$result_type,$sync_type);
				if($time >= $sync_pending_data['sync_time']){
					$this->report_model->update_sync_lock($provider_code,$result_type,$sync_type,SYNC_DEFAULT,STATUS_INACTIVE);
				}
				echo EXIT_ON_LOCK;
			}
		}else{
			echo EXIT_ERROR;
		}
	}
	private function ab_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
		//GMT-8
		$url = $arr['APIUrl'];
		$url .= '/PagingQueryBetRecords';
        $path = '/PagingQueryBetRecords';
        $request_time = gmdate('D, j M Y H:i:s e');
        $start_date = date('Y-m-d H:i:s', strtotime('-0 hours', $start_time));
		$end_date = date('Y-m-d H:i:s', strtotime('-0 hours', $end_time));
		$param_array = array(
			"agent" => $arr['AgentId'],
			"startDateTime" => $start_date,
			"endDateTime" => $end_date,
			"pageNum" => $page_id,
			"pageSize" => 1000,
		);
		$md5_content =  base64_encode(pack('H*', md5(json_encode($param_array,true))));
		$string_sign = "POST" . "\n" . $md5_content . "\n" . "application/json" . "\n" . $request_time . "\n" . $path;
        $des_key = base64_decode($arr['APIKey']);
        $hash_hmac = hash_hmac("sha1", $string_sign, $des_key, true);
        $encrypted = base64_encode($hash_hmac);
        $authorization = "AB" . " " . $arr['PropertyID'] . ":" . $encrypted;
		$header = array(
            "authorization" => $authorization,
            "date" => $request_time,
            "content" => $md5_content,
        );
        $response = $this->curl_post_for_allbet($url, $param_array,$header);
		return $response;
	}
	private function bng_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $next_id = NULL){
	    //UTF
	    $start_date = date('Y-m-d', strtotime('-8 hours', $start_time))."T".date('H:i:s', strtotime('-8 hours', $start_time));
        $end_date = date('Y-m-d', strtotime('-8 hours', $end_time))."T".date('H:i:s', strtotime('-8 hours', $end_time));
	    $url = $arr['APIUrl'];
	    $url .= '/api/v1/transaction/list';
	    $param_array = array(
            "api_token" => $arr['Token'],
            "start_date" => $start_date,
            "end_date" => $end_date,
            "player_id" => "",
            "status" => "OK",
            "brand" => "",
            "fetch_size" => 1000,
            "fetch_state" => $next_id,
        );
        $response = $this->curl_json($url, $param_array);
        return $response;
	}
	private function bl_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page = NULL){
		#Minimum 5 sec per request
		$url = $arr['APIUrl'] . '/v1/game/get_all_record_list';
		$param_array = array(
			'start_time' => $start_time,
			'end_time' => $end_time,
			'page' => $page,
			'page_size' => 1000,
			'AccessKeyId' => $arr['AccessKeyId'],
			'Timestamp' => time(),
			'Nonce' => $this->rng->get_token(128)
		);
		$param_array['Sign'] = strtolower(sha1($arr['AccessKeySecret'] . $param_array['Nonce'] . $param_array['Timestamp']));
		//Get response from curl
		$response = $this->curl_post($url, $param_array);
		return $response;
	}
	private function dg_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$method = NULL,$next_id = NULL){
		$url = $arr['APIUrl'];
		$random = rand(100000, 999999);
		$key = md5($arr['AgentName'] . $arr['APIKey'] . $random);
		$param_array = array(
			'token' => $key,
			'random' => $random,
		);
		if($method == "RetrieveRecord"){
			$url .= '/game/getReport/' . $arr['AgentName'];
		}else if($method == "SubmitRecord"){
			$param_array['list'] = $next_id;
			$url .= '/game/markReport/' . $arr['AgentName'];
		}else{
			$url = "";
		}
		$response = $this->curl_json($url, $param_array);
		return $response;
	}
	private function dt_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
		//Request Response gmt+8
		$url = $arr['APIUrl'];
		$start_date = date('Y-m-d H:i:s', strtotime('+0 hours', $start_time));
		$end_date = date('Y-m-d H:i:s', strtotime('+0 hours', $end_time));
		$param_array = array(
			'METHOD' => "GETBETDETAIL",
			'BUSINESS' => $arr['BusinessCode'],
			'START_TIME' => $start_date,
			'END_TIME' => $end_date,
			'PAGENUMBER' => $page_id,
			'PAGESIZE' => 1000,
			'REWARD_TYPE' => 2,
		);
		$param_array['SIGNATURE'] = md5($param_array['BUSINESS'] . $param_array['METHOD'] . $param_array['START_TIME'] . $param_array['END_TIME'] . $arr['APIKey']);
		$response = $this->curl_post($url, $param_array);
		return $response;
	}
	private function evoplay_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
		$url = $arr['APIUrl'] . '/Game/getRoundsInfoByPeriod?';
		//Game provider time (UTC +0)
		$start_date = date('Y-m-d H:i:s', strtotime('-8 hours', $start_time));
		$end_date = date('Y-m-d H:i:s', strtotime('-8 hours', $end_time));
		$param_array = array(
			'project' => $arr['Project'],
	        'version' => $arr['Version'],
			'start_time' => $start_date,
			'end_time' => $end_date,
			'page' => $page_id,
			'page_size' => 5000,
			'signature' => $arr['Signature'],
		);
		//Get response from curl
		foreach ($param_array as $key => $value){
            $hash_array[$key] = is_array($value) ? implode(":", $value) : $value;
	    }
	    $param_array['signature'] = md5(implode('*', $hash_array));
		$response = $this->curl_json($url, $param_array);
		return $response;
	}
	private function gfgd_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
        $start_date = $start_time."000";
        $end_date = $end_time."000";
        $url = $arr['APIUrl'];
        $url .= '/v3/Bet/Record/Get';
        $param_array = array(
	        'secret_key' => $arr['SecretKey'],
	        'operator_token' => $arr['OperatorToken'],
	        'vendor_code' => $arr['VendorCode'],
	        'start_time' => $start_date,
	        'end_time' => $end_date,
	        'page' => $page_id,
	        'page_size' => 5000,
	    );
	    $response = $this->curl_json($url,$param_array);
	    return $response;
    }
    private function gr_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $result_type = NULL,$page_id = NULL){
	    $url = $arr['APIUrl'];
	    if($result_type == GAME_BOARD_GAME){
	        $url .= "/api/platform/get_all_bet_details";   
	    }else if($result_type == GAME_OTHERS){
	        $url .= "/api/platform/get_pk_all_bet_details";
	    }else if($result_type == GAME_SLOTS){
	        $url .= "/api/platform/get_slot_all_bet_details";
	    }else if($result_type == GAME_FISHING){
	        $url .= "/api/platform/get_fish_all_bet_details";
	    }else{
	    }
	    $start_date = date('Y-m-d H:i:s', strtotime('-0 hours', $start_time));
		$end_date = date('Y-m-d H:i:s', strtotime('-0 hours', $end_time));
	    $param_array = array(
			"start_time" => $start_date,
			"end_time" => $end_date,
			"page_index" => $page_id,
			"page_size" => 1000,
		);
	    $response = $this->curl_json($url, $param_array,"Cookie:secret_key=".$arr['SecretKey']);
	    return $response;
	}
	public function icg_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL,$token = NULL){
		$url = $arr['APIUrl'];
		$url .= '/api/v1/profile/rounds';
		//Game provider time (UTC +0)
		$start_date = strtotime('-0 hours', $start_time).'000';
		$end_date = strtotime('-0 hours', $end_time).'000';
		$param_array = array(
			'pageSize' => 5000,
			'start' => $start_date,
			'end' => $end_date,
			'page' => $page_id,
			'isChildren' => true,
		);
		$url .= '?isChildren=true&lang=en&pageSize=5000&updatedStart='.$start_date.'&updatedEnd='.$end_date.'&page='.$page_id;
		$response = $this->curl_get($url, "Authorization: Bearer " . $token);
		return $response;
	}
	public function icg_connect_key($arr = NULL){
		$url = $arr['APIUrl'];
		$url .= '/login';
		//Game provider time (UTC +0)
		$param_array = array(
			'username' => $arr['Username'],
			'password' => $arr['Password']
		);
		$response = $this->curl_json($url, $param_array);
		return $response;
	}
	private function naga_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
	    $start_date = date('Y-m-d', strtotime('-8 hours', $start_time))."T".date('H:i:s', strtotime('-8 hours', $start_time)).".000Z";
		$end_date = date('Y-m-d', strtotime('-8 hours', $end_time))."T".date('H:i:s', strtotime('-8 hours', $end_time)).".000Z";
	    $url = $arr['APIUrl'];
	    $url .= '/client/player/bet-histories';
	    $skip = ($page_id -1) * 100;
	    $param_array = array(
	        'skip' => ($page_id-1)*100,
	        'limit' => 100,
	        'startDate' => $start_date,
	        'endDate' => $end_date,
	        'apiKey' => $arr['ApiKey'],
	    );
	    $url .= "?" . http_build_query($param_array);
	    $response = $this->curl_get_json($url,$token);
		return $response;
	}
	private function ninek_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
	    $url = $arr['APIUrl'];
	    $url .= "/api/".$arr['ApiToken']."/BetList";
		$param_array = array(
			"StartTime" => date('Y-m-d', $start_time)."T".date('H:i:s', $start_time),
			"EndTime" => date('Y-m-d', $end_time)."T".date('H:i:s', $end_time),
			"BossID" => $arr['BossID'],
			"Page" => $page_id,
		);
		$response = $this->curl_post($url, $param_array);
		return $response;
	}
	private function obsb_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$method = NULL){
	    if($method == "BET"){
	    	$url = $arr['APIUrl'];
			$url .= "/api_func.php?request=get_not_finished_order_detail";
			$param_array = array(
    			"agent_userid" => $arr['AgentID'],
    			"sbet_datetime" => date("Y-m-d H:i:s",$start_time),
    			"ebet_datetime" => date("Y-m-d H:i:s",$end_time),
    		);
    		$response = $this->curl_post($url, $param_array);
	    }else if($method == "ORDER"){
			$url = $arr['APIUrl'];
			$url .= "/api_func.php?request=get_has_finished_order_detail";
			$param_array = array(
				"agent_userid" => $arr['AgentID'],
    			"sfinished_datetime" => date("Y-m-d H:i:s",$start_time),
    			"efinished_datetime" => date("Y-m-d H:i:s",$end_time),
			);
			$response = $this->curl_post($url, $param_array);
		}else{
			$url = $arr['APIUrl'];
			$url .= "/api_func.php?request=get_history_has_finished_order_detail";
			$param_array = array(
				"agent_userid" => $arr['AgentID'],
    			"sfinished_datetime" => date("Y-m-d H:i:s",$start_time),
    			"efinished_datetime" => date("Y-m-d H:i:s",$end_time),
			);
			$response = $this->curl_post($url, $param_array);
		}
		return $response;
	}
	private function og_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$provider = NULL){
	    $url = $arr['ReportUrl'];
	    $url .= "/transaction";
	    $param_array = array(
    		'Operator' => $arr['Operator'],
            'Key' =>$arr['Key'],
            'SDate' => date('Y-m-d H:i:s',$start_time),
            'EDate' => date('Y-m-d H:i:s',$end_time),
            'Provider' => $provider,
    	);
    	$response = $this->curl_post($url, $param_array);
		return $response;
	}
	private function rtg_connect($arr = NULL, $start_time = NULL, $end_time = NULL,$page_id = NULL, $method = NULL,$token = NULL){
		if($method == "RetrieveRecord"){
			$start_date = date('Y-m-d', strtotime('-8 hours', $start_time))."T".date('H:i:s', strtotime('-8 hours', $start_time)).".000Z";
			$end_date = date('Y-m-d', strtotime('-8 hours', $end_time))."T".date('H:i:s', strtotime('-8 hours', $end_time)).".000Z";
			$url = $arr['APIUrl'];
			$param_array = array(
		        "params" => array(
		        	"agentId" => $arr['agentId'],
		        	"fromDate" => $start_date, 
		        	"toDate" => $end_date,
		        ),
		        "pageIndex" => $page_id,
		        "pageSize" => 1000,
		        "language" => "en",
	        );
	        $url .= "/api/report/playergame";
	        $response = $this->curl_json($url,$param_array,"Authorization: ".$token);
		}else{
			$url = $arr['APIUrl'];
			$param_array = array(
		        "username" => $arr['Username'],
		        "password" => $arr['Password'],
		    );
		    $url .= "/api/start/token?" . http_build_query($param_array);
	   		$response = $this->curl_get($url);
		}
		return $response;
	}
	private function rsg_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $type = NULL){
	    $start_date = date('Y-m-d H:i', $start_time);
	    $end_date = date('Y-m-d H:i', $end_time);
	    $url = $arr['APIUrl'];
	    $url .= "/History/GetGameDetail";
	    if($type == GAME_SLOTS){
	        $game_type = 1;
	    }else if($type == GAME_FISHING){
	        $game_type = 2;
	    }else{
	        $game_type = 0;
	    }
		$timestamp = time();
        $param_array = array(
			"SystemCode" => $arr['SystemCode'],
			"WebId" => $arr['WebId'],
			"GameType" => $game_type,
			"TimeStart" => $start_date,
			"TimeEnd" => $end_date,
		);
		$str = '{"SystemCode":"'.$arr['SystemCode'].'","WebId":"'.$arr['WebId'].'","GameType":'.$param_array['GameType'].',"TimeStart":"'.$param_array['TimeStart'].'","TimeEnd":"'.$param_array['TimeEnd'].'"}';
		$encrypt_data = openssl_encrypt($str,'DES-CBC',$arr['Deskey'],OPENSSL_RAW_DATA ,$arr['IVkey']);
        $msg = base64_encode($encrypt_data);
        $signature = md5($arr['ClientID'].$arr['Secret'].$timestamp.$msg);
        $header[]="X-API-ClientID: ".$arr['ClientID'];
        $header[]="X-API-Signature: ".$signature;
        $header[]="X-API-Timestamp: ".$timestamp;
		$param = 'Msg='.$msg;
		$response = $this->curl_post($url, $param,$header);
		$curl_array = $response['curl'];
		return $response;
	}
	private function sa_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
		//Request GMT+8
		$url = $arr['APIUrl'];
		$current_time = date("YmdHis");
		$start_date = date('Y-m-d H:i:s', $start_time);
		$end_date = date('Y-m-d H:i:s', $end_time);
		$str = '';
		$str = "method=GetAllBetDetailsForTimeIntervalDV&Key=".$arr['SecretKey']."&Time=".$current_time."&FromTime=".$start_date."&ToTime=".$end_date;
		$this->load->library('des');
		$crypt = new DES($arr['EncryptKey']);
		$mstr = $crypt->encrypt($str);
		$q0 = urlencode($mstr);
		$q = preg_replace_callback('/%[0-9A-F]{2}/', function(array $matches) { return strtolower($matches[0]); }, $q0);
		$premd5str = $str . $arr['MD5Key'] . $current_time . $arr['SecretKey'];
		$s = md5($premd5str);
		$param_array = array(
			'q' => $q,
			's' => $s
		);
		//Get response from curl
		$response = $this->curl_post_for_sa($url, $param_array);
		return $response;
	}
	private function sp_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
		//Request GMT+8
		$url = $arr['APIUrl'];
		$current_time = date("YmdHis");
		$start_date = date('Y-m-d H:i:s', $start_time);
		$end_date = date('Y-m-d H:i:s', $end_time);
		$str = '';
		$str = "method=GetAllBetDetailsForTimeInterval&Key=".$arr['SecretKey']."&Time=".$current_time."&FromTime=".$start_date."&ToTime=".$end_date;
		$this->load->library('des');
		$crypt = new DES($arr['EncryptKey']);
		$mstr = $crypt->encrypt($str);
		$q0 = urlencode($mstr);
		$q = preg_replace_callback('/%[0-9A-F]{2}/', function(array $matches) { return strtolower($matches[0]); }, $q0);
		$premd5str = $str . $arr['MD5Key'] . $current_time . $arr['SecretKey'];
		$s = md5($premd5str);
		$param_array = array(
			'q' => $q,
			's' => $s
		);
		//Get response from curl
		$response = $this->curl_post_for_sa($url, $param_array);
		return $response;
	}
	private function spsb_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $type = NULL){
	    $url = $arr['APIUrl'];
	    $url .= "api/report";
        if($type == SYNC_TYPE_ALL){
            $param_array = array(
    			"act" => "detail",
    			"account" => $arr['UpAccount'],
    			"level" => 2,
    			"s_date" => date('Y-m-d',$start_time),
    			"e_date" => date('Y-m-d',$end_time),
    			"start_time" => date('H:i:s',$start_time),
    			"end_time" => date('H:i:s',$end_time),
    		);
        }else{
            $param_array = array(
    		    "act" => "detail",
    			"account" => $arr['UpAccount'],
    			"level" => 2,
    			"s_date" => date('Y-m-d', strtotime('-7 days', $start_time)),
    			"e_date" => date('Y-m-d', strtotime('+7 days', $end_time)),
    	   );  
        }
        $this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$aes->set_mode(MCRYPT_MODE_CBC);
		$aes->set_iv($arr['IVkey']);
		$aes->set_key($arr['Deskey']);
		$aes->require_pkcs5();
		$param_array['account'] = $aes->encrypt($param_array['account']);
		$response = $this->curl_post($url, $param_array);
		return $response;
	}
	private function spsb2_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $page_id = NULL){
	    $start_date = date('Y-m-d H:i:s', strtotime('-0 hours', $start_time));
		$end_date = date('Y-m-d H:i:s', strtotime('-0 hours', $end_time));
	    $url = $arr['APIUrl'];
	    $url .= '/api/Sport';
        $param_array = array(
            'Cmd' => "GetUserReport",
            'VendorId' => $arr['VendorID'],
            'UpAccount' => $arr['UpAccount'],
            'Signature' => strtoupper(md5($arr['VendorID'].$arr['ApiKey'])),
            'Lang' => $arr['Lang'],
            'StartTime' => $start_date,
            'EndTime' => $end_date,
            'CurrentPage' => $page_id,
            'PageSize' => 1000,
        );
        $response = $this->curl_post($url,$param_array);
		return $response;
	}
	private function splt_connect($arr = NULL, $start_time = NULL, $end_time = NULL, $type = NULL){
	    $url = $arr['APIUrl'];
	    $url .= "api_101/reportItem";
        $param_array = array(
			"account" => $arr['UpAccount'],
			"passwd" => $arr['UpPassword'],
			"date" => date("Y-m-d",$start_time),
			"gameID" => $type,
			"flags" => 1,
		);
    	$this->load->library('aes_ecb');
		$aes = new Aes_ecb();
		$aes->set_mode(MCRYPT_MODE_CBC);
		$aes->set_iv($arr['IVkey']);
		$aes->set_key($arr['Deskey']);
		$aes->require_pkcs5();
		$param_array['account'] = $aes->encrypt($param_array['account']);
		$param_array['passwd'] = $aes->encrypt($param_array['passwd']);
		$response = $this->curl_post($url, $param_array);
		return $response;
	}
	private function wm_connect($arr = NULL, $start_time = NULL, $end_time = NULL){
		$url = $arr['APIUrl'];
		//Game provider time (UTC +8)
		$start_date = date('YmdHis', strtotime('-0 hours', $start_time));
		$end_date = date('YmdHis', strtotime('-0 hours', $end_time));
		$param_array = array(
			'cmd' => 'GetDateTimeReport',
			'vendorId' =>  $arr['VendorId'],
			'signature' =>  $arr['Signature'],
			'startTime' => $start_date,
			'endTime' => $end_date,
			'syslang' => 1,
			'timetype' => 1,
			'datatype' => 2,
		);
		//Get response from curl
		$response = $this->curl_post($url, $param_array);
		return $response;
	}
	public function clear_session_cache(){
		$backoffice_username = json_decode(OFFICE_USERNAME,true);
		$result = array();
		$this->db->select('login_token');
		$this->db->where_in('username',$backoffice_username);
		$query = $this->db->get('users');
		if($query->num_rows() > 0)
		{
			foreach($query->result_array() as $result_row){
				$result[] = $result_row['login_token'];
			} 
		}
		$query->free_result();
		$this->db->select('login_token');
		$this->db->where_in('username',$backoffice_username);
		$query = $this->db->get('sub_accounts');
		if($query->num_rows() > 0)
		{
			foreach($query->result_array() as $result_row){
				$result[] = $result_row['login_token'];
			} 
		}
		$query->free_result();
		$target_time = time()-CLEAR_SESSION_INTERVAL;
		$this->db->where('timestamp < ', $target_time);
		$this->db->where_not_in('id', $result);
		$this->db->delete('sessions');
	}
}