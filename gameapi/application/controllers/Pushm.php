<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pushm extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('rng');
	}

	public function all()
	{
		set_time_limit(0);

	}
	
    
    public function png(){
        set_time_limit(0);
        $provider_code = "PNG";
		$result_type = GAME_SLOTS;
		$total_result_data = array();
		$Bdata = array();
		$BUDdata = array();
		$member_lists = $this->player_model->get_player_list_array_by_provider_single($provider_code);
		
        $this->load->library('zip');
		$this->load->library('excel');
		$directory_path = './uploads/PNG/';
		$filname = 'LDF_Report_PID_952.xlsx';
		
		$filepath = $directory_path.$filname;
		if(file_exists($filepath)){
		    $inputFileType = PHPExcel_IOFactory::identify($filepath);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($filepath);
            $sheetInsertData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            
            if(!empty($sheetInsertData)){
                foreach($sheetInsertData as $result_row){
                    if(($result_row['D'] != "external_id") && !empty($result_row['D'])){
                        $tmp_username = strtolower(trim($result_row['D']));
                        $exact_username = $tmp_username;
                        
                        $PBdata = array(
    				        'game_provider_code' => $provider_code,
    				        'game_type_code' => GAME_SLOTS,
    				        'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
    				        'game_result_type' => $result_type,
    				        'game_code' => trim($result_row['S']),
    				        'game_real_code' => trim($result_row['S']),
    				        'bet_id' => trim($result_row['T']),
    				        'bet_ref_no' => (isset($result_row['O']) ? trim($result_row['O']) : ""),
    				        'bet_time' => strtotime('+8 hours', strtotime(trim($result_row['B']))),
    				        'game_time' => strtotime('+8 hours', strtotime(trim($result_row['B']))),
    		       			'report_time' => strtotime('+8 hours', strtotime(trim($result_row['B']))),
    				        'bet_amount' => trim($result_row['I']),
    				        'bet_amount_valid' => trim($result_row['I']),
    				        'payout_amount' => trim($result_row['H']),
    				        'promotion_amount' => 0,
    				        'payout_time' => strtotime('+8 hours', strtotime(trim($result_row['B']))),
    				        'win_loss' => trim($result_row['J']),
    				        'table_id' => trim($result_row['S']),
    				        'round' => trim($result_row['E']),
    				        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
    				        'status' => STATUS_COMPLETE,
    				        'game_username' => trim($result_row['D']),
    				        'player_id' => $member_lists[$exact_username],
    				    );
    				    
    				    if($PBdata['bet_amount'] == 0){
    				    	$PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
    				    }
    				    
    				    if($PBdata['win_loss'] != 0){
    			    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
    			    	}
    			    	
    			    	
    			    	$PBdata['bet_info'] = json_encode($result_row);
    			        $PBdata['insert_type'] = SYNC_DEFAULT;
    			        
    			        array_push($Bdata, $PBdata);
    						
    					if($PBdata['status'] == STATUS_COMPLETE){
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
                if( ! empty($Bdata))
				{
					$this->db->insert_batch('transaction_report', $Bdata);
				}
				if( ! empty($BUDdata))
				{
					$this->db->insert_batch('win_loss_logs', $BUDdata);
				}
            }
		}
        exit;
        $provider_code = "PNG";
		$result_type = GAME_SLOTS;
        
		$prove_data['game_provider_code'] = $provider_code;
		$prove_data['game_result_type'] = $result_type;
		$prove_data['input_get'] = json_encode($_GET);
		$prove_data['input_post'] = json_encode($_POST);
		$prove_data['input_request'] = json_encode($_REQUEST);
		$prove_data['input_json'] = file_get_contents("php://input");
		$prove_data['response_time'] = time();
		$prove_data['ip_address'] = $this->input->ip_address();
		$prove_data['input_type'] = 1;
		$this->db->insert('game_result_push_log',$prove_data);
		
		$post = file_get_contents('php://input');
		$total_result_data = array();
		$Bdata = array();
		$BUDdata = array();
		
        $current_time = time();
		$db_record_start_time = strtotime('-60 days' ,$current_time);
	    $db_record_end_time = strtotime('+60 days' ,$current_time);
		if( ! empty($post))
		{
		    $arr = json_decode($post, TRUE);
		    if(!empty($arr)){
		        if(isset($arr['Messages']) && sizeof($arr['Messages'])>0){
		            foreach($arr['Messages'] as $arr_row){
		                if($arr_row['MessageType'] == 4){
	                        array_push($total_result_data, $arr_row);
	                    }
		            }
		            
		            if(sizeof($total_result_data)>0){
		                $transaction_lists = $this->report_model->get_transaction_list_array($provider_code,$result_type,TIME_BET,$db_record_start_time, $db_record_end_time);
		                $member_lists = $this->player_model->get_player_list_array_by_provider_single($provider_code);
		                foreach($total_result_data as $result_row){
		                    $tmp_username = strtolower(trim($result_row['ExternalUserId']));
		                    $exact_username = $tmp_username;
		                    if(isset($member_lists[$exact_username])){
    		                    $PBdata = array(
    						        'game_provider_code' => $provider_code,
    						        'game_type_code' => GAME_SLOTS,
    						        'game_provider_type_code' => $provider_code."_".GAME_SLOTS,
    						        'game_result_type' => $result_type,
    						        'game_code' => trim($result_row['GameId']),
    						        'game_real_code' => trim($result_row['GameId']),
    						        'bet_id' => trim($result_row['TransactionId']),
    						        'bet_ref_no' => (isset($result_row['ExternalTransactionId']) ? trim($result_row['ExternalTransactionId']) : ""),
    						        'bet_time' => strtotime('+8 hours', strtotime(trim($result_row['Time']))),
    						        'game_time' => strtotime('+8 hours', strtotime(trim($result_row['Time']))),
    				       			'report_time' => strtotime('+8 hours', strtotime(trim($result_row['Time']))),
    						        'bet_amount' => trim($result_row['RoundLoss']),
    						        'bet_amount_valid' => trim($result_row['RoundLoss']),
    						        'payout_amount' => trim($result_row['Amount']),
    						        'promotion_amount' => 0,
    						        'payout_time' => strtotime('+8 hours', strtotime(trim($result_row['Time']))),
    						        'win_loss' => trim($result_row['Amount']) - trim($result_row['RoundLoss']),
    						        'table_id' => trim($result_row['Amount']),
    						        'round' => trim($result_row['RoundId']),
    						        'game_round_type' => GAME_ROUND_TYPE_GAME_ROUND,
    						        'status' => STATUS_COMPLETE,
    						        'game_username' => trim($result_row['ExternalUserId']),
    						        'player_id' => $member_lists[$exact_username],
    						    );
    						    
    						    if($PBdata['bet_amount'] == 0){
    						    	$PBdata['game_round_type'] = GAME_ROUND_TYPE_FREE_SPIN;
    						    }
    						    
    						    if($PBdata['win_loss'] != 0){
    					    		$PBdata['promotion_amount'] = $PBdata['bet_amount_valid'];
    					    	}
    					    	
    					    	
    					    	$PBdata['bet_info'] = json_encode($result_row);
    					        $PBdata['insert_type'] = SYNC_DEFAULT;
    					        
    					        if( ! in_array($PBdata['bet_id'], $transaction_lists))
								{
        							array_push($Bdata, $PBdata);
        							
        							if($PBdata['status'] == STATUS_COMPLETE){
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
		            
		            if( ! empty($Bdata))
					{
						$this->db->insert_batch('transaction_report', $Bdata);
					}
					if( ! empty($BUDdata))
					{
						$this->db->insert_batch('win_loss_logs', $BUDdata);
					}
		        }
		    }
		}
	}
}