<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Export extends MY_Controller {

	public function __construct()

	{

		parent::__construct();

		$this->load->model(array('player_model','group_model','tag_model','bank_model'));

		$this->load->library(array('excel','zip'));

		$is_logged_in = $this->is_logged_in();

		if( ! empty($is_logged_in)) 

		{

			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';

		}

	}

	public function deposit_export_excel_check(){

		//Initial output data

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_DEPOSIT_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_DEPOSIT_VIEW) == TRUE)

		{

			$arr = $this->session->userdata('search_deposits');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}
	
	/**
	 * Check condition before export excel in Vip reward page
	 */
	public function vipreward_export_excel_check(){

		//Initial output data

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_VIP_REWARD_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_VIP_REWARD_VIEW) == TRUE)

		{

			$arr = $this->session->userdata('search_vipreward');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}

	/**
	 * Function export excel for vip reward page
	 */
	public function vipreward_export_excel(){
		
		if(permission_validation(PERMISSION_VIP_REWARD_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_VIP_REWARD_VIEW) == TRUE){
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$arr = $this->session->userdata('search_vipreward');
		
			if(!empty($arr)){

				$columns = array(
					0 => 'id',
					1 => 'created_date',
					2 => 'player_id',
					3 => 'old_level_id',
					4 => 'new_level_id',
					5 => 'total_bet_amount_valid',
					6 => 'level_up_bonus',
					7 => 'status',
					8 => 'updated_by',
					9 => 'updated_date'
				);
				
				$where = '';		

				if(isset($arr['from_date']))
				{
					if( ! empty($arr['from_date']))
					{
						$where .= ' AND created_date >= "' . $arr['from_date'] . '"';
					}
					
					if( ! empty($arr['to_date']))
					{
						$where .= ' AND created_date <= "' . $arr['to_date'] . '"';
					}

					if($arr['status'] == STATUS_REQUEST_AWARD OR $arr['status'] == STATUS_APPROVE OR $arr['status'] == STATUS_CANCEL)
					{
						$where .= ' AND status = ' . $arr['status'];
					}
				}	
				
				$select = implode(',', $columns);
				$dbprefix = $this->db->dbprefix;
				
				$vipRewardList = NULL;
				$queryString = "(SELECT {$select} FROM {$dbprefix}player_request_award WHERE (1=1) $where)";
				$queryOrder = " ORDER by created_date DESC";
				$query = $this->db->query($queryString . $queryOrder);
				if($query->num_rows() > 0)
				{
					$vipRewardList = $query->result();  
				}
				$query->free_result();

				$fileName = 'VIP Reward - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle("VIP Reward");

        		// set Header

        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

        		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_date'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_player_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', "Old level");

				$objPHPExcel->getActiveSheet()->SetCellValue('E1', "New level");

				$objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_bet_amount'));

				$objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_level_up_bonus'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_status'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_updated_by'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('K1', $this->lang->line('label_updated_date'));

		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

				$rowCount = 2;
				$result_count = 1;
				if(!empty($vipRewardList)){
					$sql = "SELECT player_id, username FROM {$dbprefix}players";
					$query = $this->db->query($sql);
					$playerList = array();
					if($query->num_rows() > 0)
					{
						$playerList = $query->result();  
					}
					$query->free_result();
					$arrPlayer = array();
					foreach($playerList as $playerItem){
						$arrPlayer[$playerItem->player_id] = $playerItem->username;
					}

					$sql = "SELECT level_id, level_name FROM {$dbprefix}level";
					$query = $this->db->query($sql);
					$levelList = array();
					if($query->num_rows() > 0)
					{
						$levelList = $query->result();  
					}
					$query->free_result();
					$arrLevel = array();
					foreach($levelList as $levelItem){
						$arrLevel[$levelItem->level_id] = $levelItem->level_name;
					}
					foreach ($vipRewardList as $vipRewardItem){
						$status = "Pending";
						if($vipRewardItem->status == STATUS_APPROVE){
							$status = "Approved";
						} else if($vipRewardItem->status == STATUS_CANCEL){
							$status = "Rejected";
						}
						$updatedDate = !empty($vipRewardItem->updated_date)?$vipRewardItem->updated_date:'';
						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);
						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $vipRewardItem->created_date);
						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $arrPlayer[$vipRewardItem->player_id]);
						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $arrLevel[$vipRewardItem->old_level_id]);
						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $arrLevel[$vipRewardItem->new_level_id]);
						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $vipRewardItem->total_bet_amount_valid);
						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $vipRewardItem->level_up_bonus);
						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $status);
						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $vipRewardItem->updated_by);
						$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $updatedDate);

						$rowCount++;
						$result_count++;
					}
					$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
					header("Content-Type: application/vnd.ms-excel");
					header("Content-Disposition: attachment; filename=".$fileName."");

					$objWriter->save("php://output");
				}
			}
		}
	}

	/**
	 * Check condition before export excel in Agent page
	 */
	public function agent_export_excel_check(){

		//Initial output data

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_AGENT_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_AGENT_VIEW) == TRUE)

		{

			$arr = $this->session->userdata('search_agent');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}

	/**
	 * Function export excel for agent page
	 */
	public function agent_export_excel(){

		if(permission_validation(PERMISSION_AGENT_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_AGENT_VIEW) == TRUE){
			$limit = trim($this->input->post('length', TRUE));
			$start = trim($this->input->post("start", TRUE));
			$arr = $this->session->userdata('search_agent');
		
			if(!empty($arr)){

				$columns = array(
					0 => 'user_id',
					1 => 'created_date',
					2 => 'username',
					3 => 'full_name',
					4 => 'mobile',
					5 => 'bank_name',
					6 => 'account_number',
					7 => 'active',
					8 => 'updated_by',
					9 => 'updated_date'
				);
				
				$where = '';		

				if(isset($arr['from_date']))
				{
					if( ! empty($arr['from_date']))
					{
						$where .= ' AND created_date >= ' . strtotime($arr['from_date']);
					}
					
					if( ! empty($arr['to_date']))
					{
						$where .= ' AND created_date <= ' . strtotime($arr['to_date']);
					}

					if($arr['status'] == STATUS_PENDING OR $arr['status'] == STATUS_APPROVE OR $arr['status'] == STATUS_CANCEL)
					{
						$where .= ' AND active = ' . $arr['status'];
					}
				}	
				
				$select = implode(',', $columns);
				$dbprefix = $this->db->dbprefix;
				
				$agentList = NULL;
				$queryString = "(SELECT {$select} FROM {$dbprefix}users_agents WHERE (user_type <> 1) $where)";
				$queryOrder = " ORDER by created_date DESC";
				$query = $this->db->query($queryString . $queryOrder);
				if($query->num_rows() > 0)
				{
					$agentList = $query->result();  
				}
				$query->free_result();

				$fileName = 'Agent - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle("Agent");

        		// set Header

        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

        		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_date'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_fullname'));

				$objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_mobile'));

				$objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_bank_name'));

				$objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('title_bank_account'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_status'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_updated_by'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('K1', $this->lang->line('label_updated_date'));

		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

				$rowCount = 2;
				$result_count = 1;
				if(!empty($agentList)){

					foreach ($agentList as $agent){
						$status = "Pending";
						if($agent->active == STATUS_APPROVE){
							$status = "Approved";
						} else if($agent->active == STATUS_CANCEL){
							$status = "Rejected";
						}
						$updatedDate = !empty($agent->updated_date)?date("d M Y H:i:s",$agent->updated_date):'';
						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);
						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, date("d M Y H:i:s",$agent->created_date));
						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $agent->username);
						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $agent->full_name);
						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $agent->mobile);
						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $agent->bank_name);
						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $agent->account_number);
						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $status);
						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $agent->updated_by);
						$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $updatedDate);

						$rowCount++;
						$result_count++;
					}
					$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
					header("Content-Type: application/vnd.ms-excel");
					header("Content-Disposition: attachment; filename=".$fileName."");

					$objWriter->save("php://output");
				}
			}
		}
	}

	public function deposit_export_excel(){

		if(permission_validation(PERMISSION_DEPOSIT_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_DEPOSIT_VIEW) == TRUE)

		{

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$arr = $this->session->userdata('search_deposits');

			if(!empty($arr)){

				$columns = array( 

					0 => 'a.deposit_id',

					1 => 'a.created_date',

					2 => 'a.deposit_type',

					3 => 'b.username',

					4 => 'a.payment_gateway_id',

					5 => 'a.transaction_code',

					6 => 'a.payment_info',

					7 => 'a.amount_apply',

					8 => 'a.rate',

					9 => 'a.amount',

					10 => 'a.status',

					11 => 'a.deposit_ip',

					12 => 'a.remark',

					13 => 'a.updated_by',

					14 => 'a.updated_date',

					15 => 'a.transaction_code_alias',

					16 => 'a.order_no',

					17 => 'a.bank_name',

					18 => 'a.bank_account_name',

					19 => 'a.bank_account_no',

					20 => 'a.player_bank_name',

					21 => 'a.player_bank_account_name',

					22 => 'a.player_bank_account_no',

					23 => 'b.tag_ids',

					24 => 'a.player_id',

					25 => 'b.tag_id',

					26 => 'a.whitelist_status',

				);

								

				$sum_columns = array( 

					0 => 'SUM(a.amount_apply) AS total_deposit_apply',

					1 => 'SUM(a.amount) AS total_deposit_amount',

					2 => 'SUM(a.rate_amount) AS total_deposit_rate',

					3 => 'SUM(a.amount_rate) AS total_deposit_amount_rate',

				);



				$col = 0;		

				

				$where = '';	

				if( ! empty($arr['agent']))

				{

					$where = "AND a.player_id = 'ABC'";

					$agent = $this->user_model->get_user_data_by_username($arr['agent']);

					if(!empty($agent)){

						$response_upline = $this->user_model->get_downline_data($agent['username']);

						if(!empty($response_upline)){

							$where = "AND b.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";

						}

					}

				}else{

					$where = "AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";

				}

				if(isset($arr['from_date']))

				{

					if( ! empty($arr['from_date']))

					{

						$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);

					}

					

					if( ! empty($arr['to_date']))

					{

						$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);

					}

					

					if($arr['deposit_type'] >= 1 && $arr['deposit_type'] <= sizeof(get_deposit_type()))

					{

						$where .= ' AND a.deposit_type = ' . $arr['deposit_type'];

					}

					

					if( ! empty($arr['username']))

					{

						$where .= " AND b.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	

					}

					

					if($arr['status'] == STATUS_PENDING OR $arr['status'] == STATUS_APPROVE OR $arr['status'] == STATUS_CANCEL OR $arr['status'] == STATUS_ON_PENDING)

					{

						$where .= ' AND a.status = ' . $arr['status'];

					}

					

					if( ! empty($arr['ip_address']))

					{

						$where .= " AND a.ip_address LIKE '%" . $arr['ip_address'] . "%' ESCAPE '!'";	

					}



					if( ! empty($arr['deposit_id']))

					{

						$where .= " AND a.deposit_id = '" . $arr['deposit_id']."'";	

					}



					if( ! empty($arr['payment_gateway_id']))

					{

						$payment_gateway_id = '"'.implode('","', $arr['payment_gateway_id']).'"';

						$where .= " AND a.payment_gateway_id IN(" . $payment_gateway_id . ")";

					}

				}

				$select = implode(',', $columns);

				$dbprefix = $this->db->dbprefix;

				

				$posts = NULL;

				$query_string = "(SELECT {$select} FROM {$dbprefix}deposits a, {$dbprefix}players b WHERE (a.player_id = b.player_id) $where)";

				$query_string_2 = " ORDER by a.created_date DESC";

				$query = $this->db->query($query_string . $query_string_2);

				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}

				

				$query->free_result();

				

				//Get total sum up

				$sum_select = implode(',', $sum_columns);

				$total_data = array(

					'total_deposit_apply' => 0,

					'total_deposit_amount' => 0,

				);

				

				$query->free_result();



				$fileName = $this->lang->line('title_deposit').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_deposit'));

        		// set Header

        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

        		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_date'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_type'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_tag_code'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_tag_player'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_payment_gateway'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_transaction_code'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_payment_info'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('J1', $this->lang->line('label_amount_apply'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('K1', $this->lang->line('label_rate'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('L1', $this->lang->line('label_actual_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('M1', $this->lang->line('label_status'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('N1', $this->lang->line('label_ip'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('O1', $this->lang->line('label_remark'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('P1', $this->lang->line('label_updated_by'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('Q1', $this->lang->line('label_updated_date'));

		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setBold(true);



		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setSize(12);

				$rowCount = 2;

				$result_count = 1;



				$styleArrayRed = get_excel_color_status(EXPORT_COLOR_RED);

				$styleArrayBlue = get_excel_color_status(EXPORT_COLOR_BLUE);

				$styleArrayBlack = get_excel_color_status(EXPORT_COLOR_BLACK);

				//Prepare data

				$data = array();

				if(!empty($posts))

				{

					$tag_list = $this->tag_model->get_tag_list();

					$tag_player_list = $this->tag_model->get_tag_player_list();

					foreach ($posts as $post)

					{

						$tag = "";

						if(isset($tag_list[$post->tag_id])){

							$tag = $tag_list[$post->tag_id]['tag_code'];						

						}



						$tags_option = "";

						if(!empty($post->tag_ids)){

							$tags_array = array_values(array_filter(explode(',',  $post->tag_ids)));

							foreach($tags_array as $tags_row){

								if(isset($tag_player_list[$tags_row])){

									if(empty($tags_option)){

										$tags_option .= $tag_player_list[$tags_row]['tag_player_code'];

									}else{

										$tags_option .= " , ".$tag_player_list[$tags_row]['tag_player_code'];

									}

								}

							}

						}



						$total_data['total_deposit_apply'] += $post->amount_apply;

						$total_data['total_deposit_amount'] += $post->amount;

						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, ((floor(log10($post->deposit_id) + 1) > DEPOSIT_PAD_0) ? substr((string) $post->deposit_id, (DEPOSIT_PAD_0*-1)): str_pad($post->deposit_id, DEPOSIT_PAD_0, '0', STR_PAD_LEFT)));

						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-'));

						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $this->lang->line(get_deposit_type($post->deposit_type)));

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $rowCount, $post->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $tag);

						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $tags_option);

						if($post->deposit_type != DEPOSIT_OFFLINE_BANKING){

							$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $this->lang->line(get_payment_gateway($post->payment_gateway_id)));

						}else{

							

							$html = "";

							if( ! empty($post->player_bank_name))

							{

								$html .= $post->player_bank_name . "\r";

							}

							

							if( ! empty($post->player_bank_account_name))

							{

								$html .= $post->player_bank_account_name . "\r";

							}

							

							if( ! empty($post->player_bank_account_no))

							{

								$html .= $post->player_bank_account_no;

							}

							$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $html);

							$objPHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->getAlignment()->setWrapText(true);

						}



						if(!empty($post->transaction_code_alias)){

							$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $post->transaction_code_alias. "\r".$post->bank_account_name);

						}else{

							$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $post->transaction_code. "\r".$post->bank_account_name);

						}



						if($post->deposit_type != DEPOSIT_OFFLINE_BANKING){

							$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $post->payment_info);

						}else{

							$html = "";

							if( ! empty($post->bank_name))

							{

								$html = $post->bank_name . "\r";

							}

							

							if( ! empty($post->bank_account_name))

							{

								$html .= $post->bank_account_name . "\r";

							}

							

							if( ! empty($post->bank_account_no))

							{

								$html .= $post->bank_account_no;

							}

							$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $html);

							$objPHPExcel->getActiveSheet()->getStyle('I' . $rowCount)->getAlignment()->setWrapText(true);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($post->amount_apply, 0, '.', ','));

						if($post->amount_apply > 0){

							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $post->rate);

						$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, number_format($post->amount, 0, '.', ','));

						if($post->amount > 0){

							$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						switch($post->status)

						{

							case STATUS_ON_PENDING: $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $this->lang->line('deposit_status_on_pending')); break;

							case STATUS_APPROVE: $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $this->lang->line('status_approved')); break;

							case STATUS_CANCEL: $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $this->lang->line('status_cancelled')); break;

							default: $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $this->lang->line('deposit_status_pending')); break;

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $post->deposit_ip);

						$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, ( ! empty($post->remark) ? $post->remark : '-'));

						if($post->whitelist_status == STATUS_ACTIVE){

							$objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, SYSTEM_DEFAULT_NAME);

						}else{

							$objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, (( ! empty($post->updated_by)) ? $post->updated_by : '-'));

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-'));

						$rowCount++;

           				$result_count++;

					}

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line('label_total'));

				$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($total_data['total_deposit_apply'], 0, '.', ','));

				if($total_data['total_deposit_apply'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlue);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlack);

				}

				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, number_format($total_data['total_deposit_amount'], 0, '.', ','));

				if($total_data['total_deposit_amount'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlue);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



				$rowCount++;

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}



	public function withdrawal_export_excel_check(){

		//Initial output data

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_WITHDRAWAL_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_WITHDRAWAL_VIEW) == TRUE)

		{

			$arr = $this->session->userdata('search_withdrawals');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();

	}



	public function withdrawal_export_excel(){

		if(permission_validation(PERMISSION_WITHDRAWAL_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_WITHDRAWAL_VIEW) == TRUE)

		{

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$arr = $this->session->userdata('search_withdrawals');

			if(!empty($arr)){

				$columns = array( 

					0 => 'a.withdrawal_id',

					1 => 'a.created_date',

					2 => 'a.withdrawal_type',

					3 => 'b.username',

					4 => 'b.tag_ids',

					5 => 'a.bank_name',

					6 => 'a.bank_account_name',

					7 => 'a.bank_account_no',

					8 => 'a.amount',

					9 => 'a.withdrawal_fee_value',

					10 => 'a.withdrawal_fee_amount',

					11 => 'a.status',

					12 => 'a.withdrawal_ip',

					13 => 'a.remark',

					14 => 'a.updated_by',

					15 => 'a.updated_date',

					16 => 'a.player_id',

					17 => 'b.tag_id',

				);

								

				$sum_columns = array( 

					0 => 'SUM(a.amount) AS total_withdrawal',

					1 => 'SUM(a.withdrawal_fee_amount) AS total_withdrawal_fee_amount',

				);



				$col = 0;		

				

				$where = '';

				if( ! empty($arr['agent']))

				{

					$where = "AND a.player_id = 'ABC'";

					$agent = $this->user_model->get_user_data_by_username($arr['agent']);

					if(!empty($agent)){

						$response_upline = $this->user_model->get_downline_data($agent['username']);

						if(!empty($response_upline)){

							$where = "AND b.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";

						}

					}

				}else{

					$where = "AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";

				}

				if(isset($arr['from_date']))

				{

					if( ! empty($arr['from_date']))

					{

						$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);

					}

					

					if( ! empty($arr['to_date']))

					{

						$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);

					}



					if($arr['withdrawal_type'] >= 1 && $arr['withdrawal_type'] <= sizeof(get_withdrawal_type()))

					{

						$where .= ' AND a.withdrawal_type = ' . $arr['withdrawal_type'];

					}



					if( ! empty($arr['username']))

					{

						$where .= " AND b.username = '" . $arr['username'] . "'";

					}

					

					if($arr['status'] == STATUS_PENDING OR $arr['status'] == STATUS_APPROVE OR $arr['status'] == STATUS_CANCEL)

					{

						$where .= ' AND a.status = ' . $arr['status'];

					}

					

					if( ! empty($arr['ip_address']))

					{

						$where .= " AND a.ip_address = '" . $arr['ip_address'] . "'";

					}



					if( ! empty($arr['withdrawal_id']))

					{

						$where .= " AND a.withdrawal_id = " . $arr['withdrawal_id'];	

					}

					

					if( ! empty($arr['currency_code']))

					{

						$where .= " AND a.currency_code = '" . $arr['currency_code']."'";	

					}

				}

				$select = implode(',', $columns);

				$dbprefix = $this->db->dbprefix;

				

				$posts = NULL;

				$query_string = "(SELECT {$select} FROM {$dbprefix}withdrawals a, {$dbprefix}players b WHERE (a.player_id = b.player_id) $where)";

				$query_string_2 = " ORDER by a.created_date DESC";

				$query = $this->db->query($query_string . $query_string_2);

				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}

				

				$query->free_result();

				

				//Get total sum up

				$sum_select = implode(',', $sum_columns);

				$total_data = array(

					'total_withdrawal' => 0,

					'total_withdrawal_fee_amount' => 0,

				);

				

				$query->free_result();



				$fileName = $this->lang->line('title_withdrawal').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_withdrawal'));

        		// set Header

        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

        		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_date'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_tag_code'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_tag_player'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_bank_name'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_bank_account_name'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_bank_account_no'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_amount_apply'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('J1', $this->lang->line('label_fee'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('K1', $this->lang->line('label_actual_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('L1', $this->lang->line('label_status'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('M1', $this->lang->line('label_ip'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('N1', $this->lang->line('label_remark'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('O1', $this->lang->line('label_updated_by'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('P1', $this->lang->line('label_updated_date'));



		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);



		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setSize(12);

				$rowCount = 2;

				$result_count = 1;



				$styleArrayRed = get_excel_color_status(EXPORT_COLOR_RED);

				$styleArrayBlue = get_excel_color_status(EXPORT_COLOR_BLUE);

				$styleArrayBlack = get_excel_color_status(EXPORT_COLOR_BLACK);

				//Prepare data

				$data = array();

				if(!empty($posts))

				{

					$tag_list = $this->tag_model->get_tag_list();

					$tag_player_list = $this->tag_model->get_tag_player_list();

					foreach ($posts as $post)

					{



						$tag = "";

						if(isset($tag_list[$post->tag_id])){

							$tag = $tag_list[$post->tag_id]['tag_code'];						

						}



						$tags_option = "";

						if(!empty($post->tag_ids)){

							$tags_array = array_values(array_filter(explode(',',  $post->tag_ids)));

							foreach($tags_array as $tags_row){

								if(isset($tag_player_list[$tags_row])){

									if(empty($tags_option)){

										$tags_option .= $tag_player_list[$tags_row]['tag_player_code'];

									}else{

										$tags_option .= " , ".$tag_player_list[$tags_row]['tag_player_code'];

									}

								}

							}

						}



						$total_data['total_withdrawal'] += $post->amount;

						$total_data['total_withdrawal_fee_amount'] += $post->withdrawal_fee_amount;

						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, ((floor(log10($post->withdrawal_id) + 1) > WITHDRAWAL_PAD_0) ? substr((string) $post->withdrawal_id, (WITHDRAWAL_PAD_0*-1)): str_pad($post->withdrawal_id, WITHDRAWAL_PAD_0, '0', STR_PAD_LEFT)));

						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-'));

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $rowCount, $post->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $tag);

						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $tags_option);

						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $post->bank_name);

						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $post->bank_account_name);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('H' . $rowCount, $post->bank_account_no,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, number_format($post->amount, 0, '.', ','));

						if($post->amount > 0){

							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($post->withdrawal_fee_value, 0, '.', ','));

						if($post->withdrawal_fee_value > 0){

							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($post->withdrawal_fee_amount, 0, '.', ','));

						if($post->withdrawal_fee_amount > 0){

							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						switch($post->status)

						{

							case STATUS_ON_PENDING: $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $this->lang->line('status_on_pending')); break;

							case STATUS_APPROVE: $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $this->lang->line('status_approved')); break;

							case STATUS_CANCEL: $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $this->lang->line('status_cancelled')); break;

							default: $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $this->lang->line('status_pending')); break;

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $post->withdrawal_ip);

						$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, ( ! empty($post->remark) ? $post->remark : '-'));

						$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, (( ! empty($post->updated_by)) ? $post->updated_by : '-'));

						$objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-'));

						$rowCount++;

           				$result_count++;

					}

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line('label_total'));

				$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $total_data['total_withdrawal']);

				if($total_data['total_withdrawal'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlue);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlack);

				}

				$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $total_data['total_withdrawal_fee_amount']);

				if($total_data['total_withdrawal_fee_amount'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlue);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



				$rowCount++;

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}



	public function transaction_export_excel_check(){

		//Initial output data

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_TRANSACTION_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_transactions');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}



	public function transaction_export_excel(){

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_TRANSACTION_REPORT) == TRUE)

		{

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$arr = $this->session->userdata('search_report_transactions');

			if(!empty($arr)){

				$columns = array( 

					0 => 'a.transaction_id',

					1 => 'a.bet_time',

					2 => 'b.username',

					3 => 'a.game_provider_code',

					4 => 'a.game_type_code',

					5 => 'a.game_code',

					6 => 'a.bet_code',

					7 => 'a.game_result',

					8 => 'a.bet_amount',

					9 => 'a.bet_amount_valid',

					10 => 'a.win_loss',

					11 => 'a.jackpot_win',

					12 => 'a.status',

					13 => 'a.game_real_code',

					14 => 'a.bet_info',

					15 => 'a.bet_update_info',

					16 => 'a.created_date',

				);



				$col = 0;		

				

				$where = '';

				if(isset($arr['from_date']))

				{

					if( ! empty($arr['from_date']))

					{

						$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);

					}

					

					if( ! empty($arr['to_date']))

					{

						$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);

					}



					if($arr['withdrawal_type'] >= 1 && $arr['withdrawal_type'] <= sizeof(get_withdrawal_type()))

					{

						$where .= ' AND a.withdrawal_type = ' . $arr['withdrawal_type'];

					}



					if( ! empty($arr['username']))

					{

						$where .= " AND b.username = '" . $arr['username'] . "'";

					}

					

					if(!empty($arr['result_status'])){

						if($arr['result_status'] == STATUS_PENDING OR $arr['result_status'] == STATUS_APPROVE OR $arr['result_status'] == STATUS_CANCEL)

						{

							$where .= ' AND a.status = ' . $arr['result_status'];

						}
					}

					

					if( ! empty($arr['ip_address']))

					{

						$where .= " AND a.ip_address = '" . $arr['ip_address'] . "'";

					}



					if( ! empty($arr['withdrawal_id']))

					{

						$where .= " AND a.withdrawal_id = " . $arr['withdrawal_id'];	

					}

					

					if( ! empty($arr['currency_code']))

					{

						$where .= " AND a.currency_code = '" . $arr['currency_code']."'";	

					}

				}

				$select = implode(',', $columns);

				$dbprefix = $this->db->dbprefix;

				

				$posts = NULL;
				
				$query_string = "(SELECT {$select} FROM {$dbprefix}transaction_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";

				$query_string_2 = " ORDER by a.created_date DESC";

				$query = $this->db->query($query_string . $query_string_2);

				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}

				

				$query->free_result();

				

				//Get total sum up

				$total_data = array(

					'total_withdrawal' => 0,

					'total_withdrawal_fee_amount' => 0,

				);

				$query->free_result();

				$fileName = $this->lang->line('title_withdrawal').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_withdrawal'));

        		// set Header
        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));
        		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_date'));
		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_username'));
		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_provider'));
		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_game_type'));
		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_game'));
		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_bet_code'));
		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_game_result'));
		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_bet_amount'));
		        $objPHPExcel->getActiveSheet()->SetCellValue('J1', $this->lang->line('label_rolling_amount'));
		        $objPHPExcel->getActiveSheet()->SetCellValue('K1', $this->lang->line('label_win_loss'));
		        $objPHPExcel->getActiveSheet()->SetCellValue('L1', $this->lang->line('label_status'));

		        //set cell width
		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
		        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
		        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

		        // Set fonts style, Set fonts size
		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(12);
		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true)->setSize(12);
		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true)->setSize(12);
		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true)->setSize(12);
		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true)->setSize(12);
		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true)->setSize(12);
		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true)->setSize(12);
		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true)->setSize(12);
		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true)->setSize(12);
		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true)->setSize(12);
		        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true)->setSize(12);
		        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true)->setSize(12);

				$rowCount = 2;

				$result_count = 1;



				$styleArrayRed = get_excel_color_status(EXPORT_COLOR_RED);

				$styleArrayBlue = get_excel_color_status(EXPORT_COLOR_BLUE);

				$styleArrayBlack = get_excel_color_status(EXPORT_COLOR_BLACK);

				//Prepare data

				$data = array();

				if(!empty($posts))

				{

					foreach ($posts as $post)

					{
						$total_data['total_withdrawal'] += $post->amount;

						$total_data['total_withdrawal_fee_amount'] += $post->withdrawal_fee_amount;

						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, ((floor(log10($post->withdrawal_id) + 1) > WITHDRAWAL_PAD_0) ? substr((string) $post->withdrawal_id, (WITHDRAWAL_PAD_0*-1)): str_pad($post->withdrawal_id, WITHDRAWAL_PAD_0, '0', STR_PAD_LEFT)));

						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-'));

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $rowCount, $post->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $post->game_provider_code);

						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $post->game_type_code);

						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $post->game_code);

						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $post->bet_code);

						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $post->game_result);

						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, number_format($post->bet_amount, 0, '.', ','));
						if($post->amount > 0){
							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlue);
						}else{
							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlack);
						}

						$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($post->bet_amount_valid, 0, '.', ','));
						if($post->withdrawal_fee_value > 0){
							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlue);
						}else{
							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlack);
						}

						$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($post->win_loss, 0, '.', ','));
						if($post->withdrawal_fee_amount > 0){
							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlue);
						}else{
							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlack);
						}

						switch($post->status)

						{

							case STATUS_ON_PENDING: $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $this->lang->line('status_on_pending')); break;

							case STATUS_APPROVE: $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $this->lang->line('status_approved')); break;

							case STATUS_CANCEL: $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $this->lang->line('status_cancelled')); break;

							default: $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $this->lang->line('status_pending')); break;

						}

						// $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $post->withdrawal_ip);

						// $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, ( ! empty($post->remark) ? $post->remark : '-'));

						// $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, (( ! empty($post->updated_by)) ? $post->updated_by : '-'));

						// $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-'));

						$rowCount++;

           				$result_count++;

					}

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line('label_total'));

				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $total_data['total_withdrawal']);

				if($total_data['total_withdrawal'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlue);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlack);

				}

				$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $total_data['total_withdrawal_fee_amount']);

				if($total_data['total_withdrawal_fee_amount'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlue);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



				$rowCount++;

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}



	public function point_export_excel_check(){

		//Initial output data

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_POINT_TRANSACTION_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_points');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}



	public function point_export_excel(){

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_POINT_TRANSACTION_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_points');

			if(!empty($arr)){

				//Table Columns

				$columns = array( 

					0 => 'a.point_transfer_id',

					1 => 'a.report_date',

					2 => 'a.from_username',

					3 => 'a.to_username',

					4 => 'a.withdrawal_amount',

					5 => 'a.deposit_amount',

					6 => 'a.from_balance_before',

					7 => 'a.from_balance_after',

					8 => 'a.remark',

					9 => 'a.executed_by',

					10 => 'a.to_balance_before',

					11 => 'a.to_balance_after',

				);



				$where = '';	

				$where_2 = '';

				$where_3 = '';			

				

				if(isset($arr['from_date']))

				{

					if( ! empty($arr['from_date']))

					{

						$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);

					}

					

					if( ! empty($arr['to_date']))

					{

						$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);

					}

					

					if( ! empty($arr['username']))

					{

						$where_2 .= " AND a.from_username = '" . $arr['username'] . "'";	

						$where_3 .= " AND a.to_username = '" . $arr['username'] . "'";	

					}

				}

				

				$select = implode(',', $columns);

				$dbprefix = $this->db->dbprefix;

				

				$posts = NULL;

				$query_string = "(SELECT {$select} FROM {$dbprefix}point_transfer_report a, {$dbprefix}users b WHERE (a.from_username = b.username) AND (b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' OR b.user_id = " . $this->session->userdata('root_user_id') . ") $where $where_2)";

				$query_string .= " UNION ALL ";

				$query_string .= "(SELECT {$select} FROM {$dbprefix}point_transfer_report a, {$dbprefix}users b WHERE (a.to_username = b.username) AND (b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' OR b.user_id = " . $this->session->userdata('root_user_id') . ") $where $where_3)";

				$query_string .= " UNION ALL ";

				$query_string .= "(SELECT {$select} FROM {$dbprefix}point_transfer_report a, {$dbprefix}players b WHERE (a.from_username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where $where_2)";

				$query_string .= " UNION ALL ";

				$query_string .= "(SELECT {$select} FROM {$dbprefix}point_transfer_report a, {$dbprefix}players b WHERE (a.to_username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where $where_3)";

				$query = $this->db->query($query_string);

				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}

				

				$query->free_result();

				

				//Get total sum up

				$total_data = array(

					'total_points_withdrawn' => 0, 

					'total_points_deposited' => 0

				);



				$query_string = "SELECT SUM(total_points_withdrawn) AS total_points_withdrawn, SUM(total_points_deposited) AS total_points_deposited FROM (";

				$query_string .= "(SELECT SUM(a.withdrawal_amount) AS total_points_withdrawn, 0 AS total_points_deposited FROM {$dbprefix}point_transfer_report a, {$dbprefix}users b WHERE (a.from_username = b.username) AND (b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' OR b.user_id = " . $this->session->userdata('root_user_id') . ") $where $where_2)";

				$query_string .= " UNION ALL ";

				$query_string .= "(SELECT 0 AS total_points_withdrawn, SUM(a.deposit_amount) AS total_points_deposited FROM {$dbprefix}point_transfer_report a, {$dbprefix}users b WHERE (a.to_username = b.username) AND (b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' OR b.user_id = " . $this->session->userdata('root_user_id') . ") $where $where_3)";

				$query_string .= " UNION ALL ";

				$query_string .= "(SELECT SUM(a.withdrawal_amount) AS total_points_withdrawn, 0 AS total_points_deposited FROM {$dbprefix}point_transfer_report a, {$dbprefix}players b WHERE (a.from_username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where $where_2)";

				$query_string .= " UNION ALL ";

				$query_string .= "(SELECT 0 AS total_points_withdrawn, SUM(a.deposit_amount) AS total_points_deposited FROM {$dbprefix}point_transfer_report a, {$dbprefix}players b WHERE (a.to_username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where $where_3)";

				$query_string .= ") tbl";

				$query = $this->db->query($query_string);

				if($query->num_rows() > 0)

				{

					foreach($query->result() as $row)

					{

						$total_data = array(

										'total_points_withdrawn' => (($row->total_points_withdrawn > 0) ? $row->total_points_withdrawn : 0), 

										'total_points_deposited' => (($row->total_points_deposited > 0) ? $row->total_points_deposited : 0)

									);

					}

				}

				



				$fileName = $this->lang->line('title_point_transaction_report').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

	    		$objPHPExcel->setActiveSheetIndex(0);

	    		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_point_transaction_report'));

	    		// set Header

	    		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_date'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_account_out'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_account_in'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_points_withdrawn'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_points_deposited'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_previous_balance'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_balance'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_remark'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('J1', $this->lang->line('label_execution_account'));



		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);



		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);



				$rowCount = 2;

				$result_count = 1;





				$query->free_result();

				//Prepare data

				$data = array();

				if(!empty($posts))

				{

					foreach ($posts as $post)

					{

						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-'));

						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $post->from_username);

						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $post->to_username);

						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, (($post->from_username == $arr['username']) ? $post->withdrawal_amount : '0.00'));

						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, (($post->from_username == $arr['username']) ? '0.00' : $post->deposit_amount));

						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, (($post->from_username == $arr['username']) ? $post->from_balance_before : $post->to_balance_before));

						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, (($post->from_username == $arr['username']) ? $post->from_balance_after : $post->to_balance_after));

						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, ( ! empty($post->remark) ? $post->remark : '-'));

						$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $post->executed_by);

						$rowCount++;

	           			$result_count++;

					}

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line('label_total'));

				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $total_data['total_points_withdrawn']);

				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $total_data['total_points_deposited']);

				

				$rowCount++;

				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}

	/**
	 * Check export excel permission for bank report page
	 */
	public function bank_report_export_excel_check(){

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_BANK_REPORT_EXPORT_EXCEL) == TRUE)

		{

			$arr = $this->session->userdata('searches_bank_report');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}

	public function bank_report_export_excel(){

		if(permission_validation(PERMISSION_BANK_REPORT_EXPORT_EXCEL) == TRUE)

		{

			$arr = $this->session->userdata('searches_bank_report');
			$fromDate = $arr['from_date'];
			$toDate = $arr['to_date'];

			if(!empty($arr)){

				$bankDepositOffline = $this->bank_model->getOfflineDeposit($fromDate,$toDate);
				$bankWithdrawOffline = $this->bank_model->getOfflineWithdraw($fromDate,$toDate);
				$offlineBanking = array();
				foreach($bankDepositOffline as $bankItem){
					$offlineBanking[$bankItem['bank_name']]['deposits'] = $bankItem['total'];
				}
				foreach($bankWithdrawOffline as $bankItem){
					$offlineBanking[$bankItem['bank_name']]['withdrawals'] = $bankItem['total'];
				}

				$data = array();

				$fileName = $this->lang->line('title_bank_report').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

	    		$objPHPExcel->setActiveSheetIndex(0);

	    		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_bank_report'));

	    		// set Header

	    		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_bank_name'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('button_deposit_offline'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('button_withdrawal_offline'));
				
				$objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_balance'));

		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
				
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
				
				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);
				
				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$rowCount = 2;

				$result_count = 1;

				if(count($offlineBanking) > 0)
				{
					foreach($offlineBanking as $key=>$value)
					{
						$deposit_value = (isset($value['deposits'])) ? $value['deposits'] : 0;
						$withdrawals_value = (isset($value['withdrawals'])) ? $value['withdrawals'] : 0;
						$bank_balance = $deposit_value - $withdrawals_value;
						$deposit = (isset($value['deposits'])) ? number_format($value['deposits'], 2, '.', ',') : 0;
						$withdraw = (isset($value['withdrawals'])) ? number_format($value['withdrawals'], 2, '.', ',') : 0;

						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $key);

						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $deposit);

						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $withdraw);
						
						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $bank_balance);

						$rowCount++;

	           			$result_count++;
					}
				}

				$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}

	public function point_agent_export_excel_check(){

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_POINT_TRANSACTION_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('searches_point_agent');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}



	public function point_agent_export_excel(){

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_POINT_TRANSACTION_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('searches_point_agent');

			if(!empty($arr)){

				$data = array();

				$dbprefix = $this->db->dbprefix;

				$this->db->select('user_id,username');

				$this->db->group_start();

				$this->db->like('upline_ids',"," . $this->session->userdata('root_user_id') . ",");

				$this->db->or_where('user_id',$this->session->userdata('root_user_id'));

				$this->db->group_end();

				if(!empty($arr['username']))

				{

					$this->db->where('username',$arr['username']);

				}

				$this->db->order_by('user_id',"ASC");

				$query = $this->db->get('users');



				$fileName = $this->lang->line('title_point_transaction_report_agent').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

	    		$objPHPExcel->setActiveSheetIndex(0);

	    		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_point_transaction_report_agent'));

	    		// set Header

	    		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_points_deposited'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_points_withdrawn'));



		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);



		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);



				$rowCount = 2;

				$result_count = 1;









				if($query->num_rows() > 0)

				{

					foreach($query->result_array() as $row)

					{

						$total_deposit_point = 0;

						$total_withdraw_point = 0;



						$this->db->select("SUM(a.deposit_amount) AS total_points_deposited");

						$this->db->from('point_transfer_report a');

						$this->db->join('users b','a.from_username = b.username');

						$this->db->where('a.to_username',$row['username']);

						if(isset($arr['from_date']))

						{

							if( ! empty($arr['from_date']))

							{

								$this->db->where('a.report_date >= ',strtotime($arr['from_date']));

							}

							if( ! empty($arr['to_date']))

							{

								$this->db->where('a.report_date <= ',strtotime($arr['to_date']));

							}

						}

						$dp_query = $this->db->get();

						if($dp_query->num_rows() > 0)

						{

							$dp_row = $dp_query->row();

							$total_deposit_point = $dp_row->total_points_deposited;

						}



						$this->db->select("SUM(a.withdrawal_amount) AS total_points_withdraw");

						$this->db->from('point_transfer_report a');

						$this->db->join('users b','a.to_username = b.username');

						$this->db->where('a.from_username',$row['username']);

						if(isset($arr['from_date']))

						{

							if( ! empty($arr['from_date']))

							{

								$this->db->where('a.report_date >= ',strtotime($arr['from_date']));

							}

							if( ! empty($arr['to_date']))

							{

								$this->db->where('a.report_date <= ',strtotime($arr['to_date']));

							}

						}

						$wp_query = $this->db->get();

						if($wp_query->num_rows() > 0)

						{

							$wp_row = $wp_query->row();

							$total_withdraw_point = $wp_row->total_points_withdraw;

						}



						$dp_query->free_result();

						$wp_query->free_result();



						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['username']);

						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, number_format($total_deposit_point, 2, '.', ','));

						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, number_format($total_withdraw_point, 2, '.', ','));

						$rowCount++;

	           			$result_count++;

					}

				}



				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



				$query->free_result();

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}



	public function cash_export_excel_check(){

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_CASH_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_CASH_TRANSACTION_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_cash');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}



	public function cash_export_excel(){

		if(permission_validation(PERMISSION_CASH_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_CASH_TRANSACTION_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_cash');

			if(!empty($arr)){

				//Table Columns

				$columns = array( 

					0 => 'a.cash_transfer_id',

					1 => 'a.report_date',

					2 => 'a.transfer_type',

					3 => 'a.username',

					4 => 'a.balance_before',

					5 => 'a.deposit_amount',

					6 => 'a.withdrawal_amount',

					7 => 'a.balance_after',

					8 => 'a.remark',

					9 => 'a.executed_by',

				);

								

				$sum_columns = array( 

					0 => 'SUM(a.withdrawal_amount) AS total_points_withdrawn',

					1 => 'SUM(a.deposit_amount) AS total_points_deposited',

				);					

							

				$where = '';



				if( ! empty($arr['agent']))

				{

					$where = "AND a.player_id = 'ABC'";

					$agent = $this->user_model->get_user_data_by_username($arr['agent']);

					if(!empty($agent)){

						$response_upline = $this->user_model->get_downline_data($agent['username']);

						if(!empty($response_upline)){

							$where = "AND b.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";

						}

					}

				}else{

					$where = "AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";

				}	

			

				if(isset($arr['from_date']))

				{

					if( ! empty($arr['from_date']))

					{

						$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);

					}

					

					if( ! empty($arr['to_date']))

					{

						$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);

					}

					

					if( ! empty($arr['transfer_type']))

					{

						$transfer_type = '"'.implode('","', $arr['transfer_type']).'"';

						$where .= " AND a.transfer_type IN(" . $transfer_type . ")";

					}else{

						$where .= " AND a.transfer_type = 'ABC'";

					}

					

					if( ! empty($arr['username']))

					{

						$where .= " AND a.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	

					}

				}



				$select = implode(',', $columns);

				$dbprefix = $this->db->dbprefix;

				$posts = NULL;

				$query_string = "(SELECT {$select} FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) $where)";

				$query = $this->db->query($query_string);

				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}

				

				$query->free_result();



				//Get total sum up

				$sum_select = implode(',', $sum_columns);

				$total_data = array(

								'total_points_withdrawn' => 0, 

								'total_points_deposited' => 0

							);

				

				$query_string = "(SELECT {$sum_select} FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) $where)";

				$query = $this->db->query($query_string);

				if($query->num_rows() > 0)

				{

					foreach($query->result() as $row)

					{

						$total_data = array(

							'total_points_withdrawn' => (($row->total_points_withdrawn > 0) ? $row->total_points_withdrawn : 0), 

							'total_points_deposited' => (($row->total_points_deposited > 0) ? $row->total_points_deposited : 0)

						);

					}

				}

				

				$query->free_result();

				$fileName = $this->lang->line('title_reward_transaction_report').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_reward_transaction_report'));

        		// set Header



        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_date'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_type'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_wallet_transfer_before_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_wallet_transfer_in_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_wallet_transfer_out_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_wallet_transfer_after_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_remark'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('J1', $this->lang->line('label_execution_account'));



		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);



		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);



				$rowCount = 2;

				$result_count = 1;

				//Prepare data

				$data = array();

				if(!empty($posts))

				{

					foreach ($posts as $post)

					{

						if($post->transfer_type == TRANSFER_TRANSACTION_IN || $post->transfer_type == TRANSFER_TRANSACTION_OUT){

							$remark = $post->remark;

							if(!empty($post->remark)){

								$remark_array = json_decode($remark = $post->remark,true);

								if(!empty($remark_array)){

									$date = (isset($remark_array['created_date']) ? $remark_array['created_date'] : 0);

									$from = (isset($remark_array['from']) ? (($remark_array['from'] == 'MAIN') ? $this->lang->line('label_main_wallet') : $this->lang->line('game_' . strtolower($remark_array['from']))) : "-");

									$to = (isset($remark_array['to']) ? (($remark_array['to'] == 'MAIN') ? $this->lang->line('label_main_wallet') : $this->lang->line('game_' . strtolower($remark_array['to']))) : "-");

									$response = (isset($remark_array['errorCode']) ? ($remark_array['errorCode'] == "0") ? $this->lang->line('error_success') : $this->lang->line('error_failed') : "-");

									$remark = $this->lang->line('label_transfers')."(".$this->lang->line('label_from').")"." ".$from." ".$this->lang->line('label_to')." ".$to."\r"." ".$this->lang->line('label_remark')." : ".$response;

								}

							}

						}else{

							$remark = $post->remark;

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-'));

						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $this->lang->line(get_transfer_type($post->transfer_type)));

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $rowCount, $post->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $post->balance_before);

						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $post->deposit_amount);

						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $post->withdrawal_amount);

						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $post->balance_after);

						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, ( ! empty($remark) ? $remark : '-'));

						$objPHPExcel->getActiveSheet()->getStyle('I' . $rowCount)->getAlignment()->setWrapText(true);

						$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $post->executed_by);



						$rowCount++;

	           			$result_count++;

					}

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line('label_total'));

				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $total_data['total_points_withdrawn']);

				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $total_data['total_points_deposited']);

				$rowCount++;



				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}



	public function reward_export_excel_check(){

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_REWARD_TRANSACTION_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_reward');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}



	public function reward_export_excel(){

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_REWARD_TRANSACTION_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_reward');

			if(!empty($arr)){

				//Table Columns

				$columns = array( 

					0 => 'a.reward_transfer_id',

					1 => 'a.report_date',

					2 => 'a.transfer_type',

					3 => 'a.username',

					4 => 'a.withdrawal_amount',

					5 => 'a.deposit_amount',

					6 => 'a.balance_before',

					7 => 'a.balance_after',

					8 => 'a.remark',

					9 => 'a.executed_by',

				);

								

				$sum_columns = array( 

					0 => 'SUM(a.withdrawal_amount) AS total_points_withdrawn',

					1 => 'SUM(a.deposit_amount) AS total_points_deposited',

				);



				if(isset($arr['from_date']))

				{

					if( ! empty($arr['from_date']))

					{

						$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);

					}

					

					if( ! empty($arr['to_date']))

					{

						$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);

					}

					

					if($arr['transfer_type'] >= 1 && $arr['transfer_type'] <= sizeof(get_transfer_reward_type()))

					{

						$where .= ' AND a.transfer_type = ' . $arr['transfer_type'];

					}

					

					if( ! empty($arr['username']))

					{

						$where .= " AND a.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	

					}

				}



				$select = implode(',', $columns);

				$dbprefix = $this->db->dbprefix;

				$posts = NULL;

				$query_string = "(SELECT {$select} FROM {$dbprefix}reward_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";

				$query = $this->db->query($query_string);

				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}

				$query->free_result();

				//Get total sum up

				$sum_select = implode(',', $sum_columns);

				$total_data = array(

								'total_points_withdrawn' => 0, 

								'total_points_deposited' => 0

							);

				

				$query_string = "(SELECT {$sum_select} FROM {$dbprefix}reward_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";

				$query = $this->db->query($query_string);

				if($query->num_rows() > 0)

				{

					foreach($query->result() as $row)

					{

						$total_data = array(

										'total_points_withdrawn' => (($row->total_points_withdrawn > 0) ? $row->total_points_withdrawn : 0), 

										'total_points_deposited' => (($row->total_points_deposited > 0) ? $row->total_points_deposited : 0)

									);

					}

				}

				

				$query->free_result();

				$data = array();





				$fileName = $this->lang->line('title_cash_transaction_report').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_cash_transaction_report'));

        		// set Header



        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_date'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_type'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_points_withdrawn'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_points_deposited'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_previous_balance'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_balance'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_remark'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('J1', $this->lang->line('label_execution_account'));

		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);



		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);



				$rowCount = 2;

				$result_count = 1;



				if(!empty($posts))

				{

					foreach ($posts as $post)

					{

						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-'));

						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $this->lang->line(get_transfer_reward_type($post->transfer_type)));

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $rowCount, $post->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $post->withdrawal_amount);

						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $post->deposit_amount);

						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $post->balance_before);

						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $post->balance_after);

						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, ( ! empty($post->remark) ? $post->remark : '-'));

						$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $post->executed_by);

						$rowCount++;

	           			$result_count++;

					}

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line('label_total'));

				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $total_data['total_points_withdrawn']);

				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $total_data['total_points_deposited']);

				$rowCount++;



				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}



	public function verify_code_export_excel_check(){

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_VERIFY_CODE_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_verify_code');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}



	public function verify_code_export_excel(){

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_VERIFY_CODE_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_verify_code');

			if(!empty($arr)){

				//Table Columns

				$columns = array( 

					0 => 'sms_log_id',

					1 => 'created_date',

					2 => 'updated_date',

					3 => 'transaction_id',

					4 => 'username',

					5 => 'mobile',

					6 => 'code',

					7 => 'ip_address',

					8 => 'status',

					9 => 'remark',

					10 => 'resp_data',

				);

				$where = 'WHERE sms_log_id != "ABC"';

				if(isset($arr['from_date']))

				{

					if( ! empty($arr['from_date']))

					{

						$where .= ' AND created_date >= ' . strtotime($arr['from_date']);

					}

					

					if( ! empty($arr['to_date']))

					{

						$where .= ' AND created_date <= ' . strtotime($arr['to_date']);

					}

					

					if(isset($arr['status']) && $arr['status'] !== "-1"){

						$where .= " AND status = " . $arr['status'];

					}

					if(!empty($arr['mobile'])){

						$where .= " AND mobile = '" . $arr['mobile']."'";

					}

					if(!empty($arr['transaction_id'])){

						$where .= " AND transaction_id = '" . $arr['transaction_id']."'";

					}

					if( ! empty($arr['username']))

					{

						$where .= " AND username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	

					}

				}



				$select = implode(',', $columns);

				$dbprefix = $this->db->dbprefix;



				$posts = NULL;

				$query_string = "(SELECT {$select} FROM {$dbprefix}sms_log $where)";

				$query = $this->db->query($query_string);

				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}

				$query->free_result();

				$data = array();





				$fileName = $this->lang->line('title_verify_code_report').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_verify_code_report'));

        		// set Header



        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_created_by'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_updated_date'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_transaction_code'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_mobile'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_code'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_ip'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_status'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('J1', $this->lang->line('label_remark'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('K1', $this->lang->line('label_response_data'));

		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);



				$rowCount = 2;

				$result_count = 1;





				if(!empty($posts))

				{

					foreach ($posts as $post)

					{

						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-'));

						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-'));

						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, ( ! empty($post->transaction_id) ? $post->transaction_id : '-'));

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $rowCount, ( ! empty($post->username) ? $post->username : '-'),PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $rowCount, $post->mobile,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $post->code);

						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $post->ip_address);

						switch($post->status)

						{

							case STATUS_COMPLETE: $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $this->lang->line('status_completed')); break;

							case STATUS_CANCEL: $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $this->lang->line('status_cancelled')); break;

							default: $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $this->lang->line('status_pending')); break;

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, ( ! empty($post->remark) ? $post->remark : '-'));

						$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, ( ! empty($post->resp_data) ? $post->resp_data : '-'));





						$rowCount++;

	           			$result_count++;

					}

				}



				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

				//$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}



	public function wallet_export_excel_check(){

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_WALLET_TRANSACTION_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_wallets');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}



	public function wallet_export_excel(){

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_WALLET_TRANSACTION_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_wallets');

			if(!empty($arr)){

				//Table Columns

				$columns = array( 

					0 => 'a.game_transfer_id',

					1 => 'a.report_date',

					2 => 'b.username',

					3 => 'a.from_wallet',

					4 => 'a.to_wallet',

					5 => 'a.withdrawal_amount',

					6 => 'a.deposit_amount',

					7 => 'a.to_balance_before',

					8 => 'a.to_balance_after',

				);

								

				$sum_columns = array( 

					0 => 'SUM(a.withdrawal_amount) AS total_points_withdrawn',

					1 => 'SUM(a.deposit_amount) AS total_points_deposited',

				);





				$where = '';	

			

				if(isset($arr['from_date']))

				{

					if( ! empty($arr['from_date']))

					{

						$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);

					}

					

					if( ! empty($arr['to_date']))

					{

						$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);

					}

					

					if( ! empty($arr['username']))

					{

						$where .= " AND b.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	

					}

				}



				$select = implode(',', $columns);

				$dbprefix = $this->db->dbprefix;

				$posts = NULL;

				$query_string = "SELECT {$select} FROM {$dbprefix}game_transfer_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";

				$query = $this->db->query($query_string);

				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}

				$query->free_result();



				//Get total sum up

				$sum_select = implode(',', $sum_columns);

				$total_data = array(

								'total_points_withdrawn' => 0, 

								'total_points_deposited' => 0

							);

				

				$query_string = "SELECT {$sum_select} FROM {$dbprefix}game_transfer_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where";

				$query = $this->db->query($query_string);

				if($query->num_rows() > 0)

				{

					foreach($query->result() as $row)

					{

						$total_data = array(

										'total_points_withdrawn' => (($row->total_points_withdrawn > 0) ? $row->total_points_withdrawn : 0), 

										'total_points_deposited' => (($row->total_points_deposited > 0) ? $row->total_points_deposited : 0)

									);

					}

				}

				

				$query->free_result();



				//Prepare data

				$data = array();

				$fileName = $this->lang->line('title_wallet_transaction_report').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_wallet_transaction_report'));

        		// set Header



        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_date'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_wallet_out'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_wallet_in'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_points_withdrawn'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_points_deposited'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_previous_balance'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_balance'));

		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);



				$rowCount = 2;

				$result_count = 1;

				if(!empty($posts))

				{

					foreach ($posts as $post)

					{

						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);



						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-'));

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $rowCount, $post->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, (($post->from_wallet == 'MAIN') ? $this->lang->line('label_main_wallet') : $this->lang->line('game_' . strtolower($post->from_wallet))));

						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, (($post->to_wallet == 'MAIN') ? $this->lang->line('label_main_wallet') : $this->lang->line('game_' . strtolower($post->to_wallet))));

						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $post->withdrawal_amount);

						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $post->deposit_amount);

						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $post->to_balance_before);

						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $post->to_balance_after);





						$rowCount++;

	           			$result_count++;

					}

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line('label_total'));

				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $total_data['total_points_withdrawn']);

				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $total_data['total_points_deposited']);

				$rowCount++;

				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}



	public function login_export_excel_check(){

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_LOGIN_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_logins');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}



	public function login_export_excel(){

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_LOGIN_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_logins');

			if(!empty($arr)){

				

				//Table Columns

				$columns = array( 

					0 => 'a.login_report_id',

					1 => 'a.report_date',

					2 => 'a.username',

					3 => 'a.ip_address',

					4 => 'a.status',

					5 => 'a.platform',

				);

				

				$where = '';



				if(isset($arr['from_date']))

				{

					if( ! empty($arr['from_date']))

					{

						$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);

					}

					

					if( ! empty($arr['to_date']))

					{

						$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);

					}

					

					if($arr['detail'] == STATUS_FAIL OR $arr['detail'] == STATUS_SUCCESS)

					{

						$where .= ' AND a.status = ' . $arr['detail'];

					}

					

					if( ! empty($arr['username']))

					{

						$where .= " AND a.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	

					}



					$where .= ' AND a.user_group_type = ' . USER_GROUP_PLAYER;

					

					if($arr['platform'] == PLATFORM_WEB OR $arr['platform'] == PLATFORM_MOBILE_WEB OR $arr['platform'] == PLATFORM_APP_ANDROID OR $arr['platform'] == PLATFORM_APP_IOS)

					{

						$where .= ' AND a.platform = ' . $arr['platform'];

					}

					

					if( ! empty($arr['ip_address']))

					{

						$where .= " AND a.ip_address LIKE '%" . $arr['ip_address'] . "%' ESCAPE '!'";	

					}

				}





				$select = implode(',', $columns);

				$dbprefix = $this->db->dbprefix;

				$posts = NULL;

				$query_string = "(SELECT {$select} FROM {$dbprefix}login_report a, {$dbprefix}users b WHERE (a.username = b.username) AND (b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' OR b.user_id = " . $this->session->userdata('root_user_id') . ") $where)";

				$query_string .= " UNION ALL ";

				$query_string .= "(SELECT {$select} FROM {$dbprefix}login_report a, {$dbprefix}sub_accounts b, {$dbprefix}users c WHERE (a.username = b.username) AND (b.upline = c.username) AND (c.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' OR c.user_id = " . $this->session->userdata('root_user_id') . ") $where)";

				$query_string .= " UNION ALL ";

				$query_string .= "(SELECT {$select} FROM {$dbprefix}login_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";

				$query = $this->db->query($query_string);

				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}

				$query->free_result();





				//Prepare data

				$data = array();

				$fileName = $this->lang->line('title_login_report').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_login_report'));

        		// set Header



        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_last_login_date'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_login_ip'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_detail'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_platform'));



		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);



				$rowCount = 2;

				$result_count = 1;





				//Prepare data

				$data = array();

				if(!empty($posts))

				{

					foreach ($posts as $post)

					{



						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-'));

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $rowCount, $post->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, ( ! empty($post->ip_address) ? $post->ip_address : '-'));

						switch($post->status)

						{

							case STATUS_SUCCESS: $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $this->lang->line('label_login_successful')); break;

							default: $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $this->lang->line('label_login_fail')); break;

						}

						switch($post->platform)

						{

							case PLATFORM_MOBILE_WEB: $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $this->lang->line('label_mobile_web')); break;

							case PLATFORM_APP_ANDROID: $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $this->lang->line('label_mobile_app_android')); break;

							case PLATFORM_APP_IOS: $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $this->lang->line('label_mobile_app_ios')); break;

							default: $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $this->lang->line('label_website')); break;

						}

						$rowCount++;

	           			$result_count++;

					}

				}

				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}



	public function winloss_export_excel_check(){

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_winloss');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}



	public function winloss_player_export_excel_check(){

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_winloss');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}



	public function winloss_export_excel($num = NULL ,$username = NULL){

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_winloss');

			if(!empty($arr)){

				$dbprefix = $this->db->dbprefix;

				$data = array();

				if(empty($username))

				{

					$num = 1;

					$upline_query_string = "SELECT * FROM {$dbprefix}users WHERE user_id = " . $this->session->userdata('root_user_id') . " LIMIT 1";

				}

				else

				{

					$upline_query_string = "SELECT * FROM {$dbprefix}users WHERE upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' AND upline = '{$username}' ORDER BY username ASC";

				}

				$upline_query = $this->db->query($upline_query_string);

				$fileName = $this->lang->line('title_win_loss_report_abs').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_win_loss_report_abs'));

        		// set Header





        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_level'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_game_type'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_agent'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_deposit'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_withdrawal'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_number_of_transaction'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_bet_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('J1', $this->lang->line('label_win_loss'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('K1', $this->lang->line('label_rolling_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('L1', $this->lang->line('label_rolling_commission'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('M1', $this->lang->line('label_promotion'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('N1', $this->lang->line('label_bonus'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('O1', $this->lang->line('label_profit'));

		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);

		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setSize(12);



				$rowCount = 2;

				$result_count = 1;



				$styleArrayRed = get_excel_color_status(EXPORT_COLOR_RED);

				$styleArrayBlue = get_excel_color_status(EXPORT_COLOR_BLUE);

				$styleArrayBlack = get_excel_color_status(EXPORT_COLOR_BLACK);

				if($upline_query->num_rows() > 0)

				{

					foreach($upline_query->result() as $upline_row)

					{

						$comm_arr = array(

							GAME_SPORTSBOOK => array(

														'total_bet' => 0,

														'total_bet_amount' => 0,

														'total_win_loss' => 0,

														'total_rolling_amount' => 0,

													),

							GAME_LIVE_CASINO => array(

														'total_bet' => 0,

														'total_bet_amount' => 0,

														'total_win_loss' => 0,

														'total_rolling_amount' => 0,

													),

							GAME_SLOTS => array(

													'total_bet' => 0,

													'total_bet_amount' => 0,

													'total_win_loss' => 0,

													'total_rolling_amount' => 0,

												),

							GAME_OTHERS => array(

													'total_bet' => 0,

													'total_bet_amount' => 0,

													'total_win_loss' => 0,

													'total_rolling_amount' => 0,

												)

						);

									

						//Get win loss

						$where = '';

					

						if( ! empty($arr['from_date']))

						{

							$where .= ' AND a.payout_time >= ' . strtotime($arr['from_date']);

						}

						

						if( ! empty($arr['to_date']))

						{

							$where .= ' AND a.payout_time <= ' . strtotime($arr['to_date']);

						}

						

						$select = "a.game_type_code, COUNT(a.transaction_id) AS total_bet, SUM(a.bet_amount) AS total_bet_amount, SUM(a.win_loss) AS total_win_loss, SUM(a.bet_amount_valid) AS total_rolling_amount";			

						$wl_query_string = "SELECT {$select} FROM {$dbprefix}transaction_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $upline_row->user_id . ",%' ESCAPE '!' $where GROUP BY a.game_type_code";

						$wl_query = $this->db->query($wl_query_string);

						if($wl_query->num_rows() > 0)

						{

							foreach($wl_query->result() as $wl_row)

							{

								$game_type_code = GAME_OTHERS;

								

								if($wl_row->game_type_code == GAME_SPORTSBOOK OR $wl_row->game_type_code == GAME_LIVE_CASINO OR $wl_row->game_type_code == GAME_SLOTS)

								{

									$game_type_code = $wl_row->game_type_code;

								}

								

								$comm_arr[$game_type_code]['total_bet'] = ($comm_arr[$game_type_code]['total_bet'] + $wl_row->total_bet);

								$comm_arr[$game_type_code]['total_bet_amount'] = ($comm_arr[$game_type_code]['total_bet_amount'] + $wl_row->total_bet_amount);

								$comm_arr[$game_type_code]['total_win_loss'] = ($comm_arr[$game_type_code]['total_win_loss'] + $wl_row->total_win_loss);

								$comm_arr[$game_type_code]['total_rolling_amount'] = ($comm_arr[$game_type_code]['total_rolling_amount'] + $wl_row->total_rolling_amount);

							}

						}



						$wl_query->free_result();

						

						$deposit = 0;

						$withdrawal = 0;

						$promotion = 0;

						$bonus = 0;

						

						//Get deposit

						/*

						$where = '';

					

						if( ! empty($arr['from_date']))

						{

							$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);

						}

						

						if( ! empty($arr['to_date']))

						{

							$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);

						}

						

						$where .= ' AND a.transfer_type IN (' . TRANSFER_ADJUST_IN . ', ' . TRANSFER_OFFLINE_DEPOSIT . ', ' . TRANSFER_PG_DEPOSIT . ')';

						

						$dp_query_string = "SELECT SUM(a.deposit_amount) AS total FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $upline_row->user_id . ",%' ESCAPE '!' $where";

						$dp_query = $this->db->query($dp_query_string);

						if($dp_query->num_rows() > 0)

						{

							$dp_row = $dp_query->row();

							$deposit = $dp_row->total;

						}

						

						$dp_query->free_result();

						*/

						$where = '';

						if( ! empty($arr['from_date']))

						{

							$where .= ' AND a.updated_date >= ' . strtotime($arr['from_date']);

						}

						

						if( ! empty($arr['to_date']))

						{

							$where .= ' AND a.updated_date <= ' . strtotime($arr['to_date']);

						}

						$where .= ' AND a.status = '. STATUS_APPROVE;

						//$where .= ' AND a.transfer_type IN (' . TRANSFER_POINT_OUT . ', ' . TRANSFER_ADJUST_OUT . ', ' . TRANSFER_WITHDRAWAL . ')';

						$dp_query_string = "SELECT SUM(a.amount_apply) AS total FROM {$dbprefix}deposits a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $upline_row->user_id . ",%' ESCAPE '!' $where";

						$dp_query = $this->db->query($dp_query_string);

						if($dp_query->num_rows() > 0)

						{

							$dp_row = $dp_query->row();

							$deposit += $dp_row->total;

						}

						$dp_query->free_result();

						

						//Get withdrawal

						$where = '';

					

						if( ! empty($arr['from_date']))

						{

							$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);

						}

						

						if( ! empty($arr['to_date']))

						{

							$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);

						}

						

						$where .= ' AND a.transfer_type IN (' . TRANSFER_ADJUST_OUT . ')';

						

						$wd_query_string = "SELECT SUM(a.withdrawal_amount) AS total FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $upline_row->user_id . ",%' ESCAPE '!' $where";

						$wd_query = $this->db->query($wd_query_string);

						if($wd_query->num_rows() > 0)

						{

							$wd_row = $wd_query->row();

							$withdrawal = $wd_row->total;

						}

						

						$wd_query->free_result();



						$where = '';

						if( ! empty($arr['from_date']))

						{

							$where .= ' AND a.updated_date >= ' . strtotime($arr['from_date']);

						}

						

						if( ! empty($arr['to_date']))

						{

							$where .= ' AND a.updated_date <= ' . strtotime($arr['to_date']);

						}

						$where .= ' AND a.status = '. STATUS_APPROVE;

						//$where .= ' AND a.transfer_type IN (' . TRANSFER_POINT_OUT . ', ' . TRANSFER_ADJUST_OUT . ', ' . TRANSFER_WITHDRAWAL . ')';

						$wd_query_string = "SELECT SUM(a.amount) AS total FROM {$dbprefix}withdrawals a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $upline_row->user_id . ",%' ESCAPE '!' $where";

						$wd_query = $this->db->query($wd_query_string);

						if($wd_query->num_rows() > 0)

						{

							$wd_row = $wd_query->row();

							$withdrawal += $wd_row->total;

						}





						//Get Promotion

						$where = '';

					

						if( ! empty($arr['from_date']))

						{

							$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);

						}

						

						if( ! empty($arr['to_date']))

						{

							$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);

						}

						

						$where .= ' AND a.transfer_type IN (' . TRANSFER_PROMOTION . ')';

						

						$promo_query_string = "SELECT SUM(a.deposit_amount) AS total FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $upline_row->user_id . ",%' ESCAPE '!' $where";

						$promo_query = $this->db->query($promo_query_string);

						if($promo_query->num_rows() > 0)

						{

							$promo_row = $promo_query->row();

							$promotion = $promo_row->total;

						}

						

						$promo_query->free_result();



						//Get Bonus

						$where = '';

					

						if( ! empty($arr['from_date']))

						{

							$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);

						}

						

						if( ! empty($arr['to_date']))

						{

							$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);

						}

						

						$where .= ' AND a.transfer_type IN (' . TRANSFER_BONUS . ')';

						

						$bonus_query_string = "SELECT SUM(a.deposit_amount) AS total FROM {$dbprefix}cash_transfer_report a, {$dbprefix}players b WHERE (a.username = b.username) AND b.upline_ids LIKE '%," . $upline_row->user_id . ",%' ESCAPE '!' $where";

						$bonus_query = $this->db->query($bonus_query_string);

						if($bonus_query->num_rows() > 0)

						{

							$bonus_row = $bonus_query->row();

							$bonus = $bonus_row->total;

						}

						

						$bonus_query->free_result();

						

						//Calculation

						$total_bet = ($comm_arr[GAME_SPORTSBOOK]['total_bet'] + $comm_arr[GAME_LIVE_CASINO]['total_bet'] + $comm_arr[GAME_SLOTS]['total_bet'] + $comm_arr[GAME_OTHERS]['total_bet']);

						$total_bet_amount = ($comm_arr[GAME_SPORTSBOOK]['total_bet_amount'] + $comm_arr[GAME_LIVE_CASINO]['total_bet_amount'] + $comm_arr[GAME_SLOTS]['total_bet_amount'] + $comm_arr[GAME_OTHERS]['total_bet_amount']);

						$total_win_loss = ($comm_arr[GAME_SPORTSBOOK]['total_win_loss'] + $comm_arr[GAME_LIVE_CASINO]['total_win_loss'] + $comm_arr[GAME_SLOTS]['total_win_loss'] + $comm_arr[GAME_OTHERS]['total_win_loss']);

						$total_rolling_amount = ($comm_arr[GAME_SPORTSBOOK]['total_rolling_amount'] + $comm_arr[GAME_LIVE_CASINO]['total_rolling_amount'] + $comm_arr[GAME_SLOTS]['total_rolling_amount'] + $comm_arr[GAME_OTHERS]['total_rolling_amount']);

						

						$casino_comm = (($comm_arr[GAME_LIVE_CASINO]['total_rolling_amount'] * $upline_row->casino_comm) / 100);

						$slots_comm = (($comm_arr[GAME_SLOTS]['total_rolling_amount'] * $upline_row->slots_comm) / 100);

						$sport_comm = (($comm_arr[GAME_SPORTSBOOK]['total_rolling_amount'] * $upline_row->sport_comm) / 100);

						$other_comm = (($comm_arr[GAME_OTHERS]['total_rolling_amount'] * $upline_row->other_comm) / 100);

						$rolling_commission = ($casino_comm + $slots_comm + $sport_comm + $other_comm);

						

						$possess_win_loss = (($total_win_loss * $upline_row->possess) / 100);

						$possess_promotion = (($promotion * $upline_row->possess) / 100);

						$possess_bonus = (($bonus * $upline_row->possess) / 100);

						$profit = (($possess_win_loss * -1) - $rolling_commission - $possess_promotion - $possess_bonus);

						

						//Prepare data



						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $this->lang->line(get_user_type($upline_row->user_type)));

						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, '-');

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $rowCount, $upline_row->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, ( ! empty($upline_row->upline) ? $upline_row->upline : '-'));





						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($deposit, 2, '.', ','));

						if($deposit > 0){

							$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlack);

						}





						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, number_format($withdrawal, 2, '.', ','));

						if($deposit > 0){

							$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlack);

						}









						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $total_bet);

						if($total_bet > 0){

							$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlack);

						}





						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, number_format($total_bet_amount, 2, '.', ','));

						if($total_bet_amount > 0){

							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlack);

						}







						$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($total_win_loss, 2, '.', ','));

						if($total_bet_amount >= 0){

							if($total_bet_amount > 0){

								$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlue);

							}else{

								$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlack);

							}

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayRed);

						}





						$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($total_rolling_amount, 2, '.', ','));

						if($total_rolling_amount > 0){

							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlack);

						}





						$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, number_format($rolling_commission, 2, '.', ','));

						if($rolling_commission > 0){

							$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, number_format($promotion, 2, '.', ','));

						if($promotion > 0){

							$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, number_format($bonus, 2, '.', ','));

						if($bonus > 0){

							$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $profit);

						if($profit >= 0){

							$objPHPExcel->getActiveSheet()->getStyle('O'.$rowCount)->applyFromArray($styleArrayBlack);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('O'.$rowCount)->applyFromArray($styleArrayRed);

						}



						$rowCount++;

	           			$result_count++;

					}

				}



				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$upline_query->free_result();



				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}



	public function winloss_player_export_excel($username = NULL){

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_winloss');

			if(!empty($arr)){

				$userData = $this->user_model->get_user_data_by_username($username);

				(!empty($userData)) ? $userID = $userData['user_id'] : $userID = "abc";

				$dbprefix = $this->db->dbprefix;

				$data = array();

				$upline_query_string = "SELECT * FROM {$dbprefix}players WHERE upline_ids LIKE '%," . $userID . ",%' AND upline = '{$username}' ORDER BY username ASC";

				$upline_query = $this->db->query($upline_query_string);



				$fileName = $this->lang->line('title_win_loss_report_abs').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_win_loss_report_abs'));

        		// set Header





        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_level'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_game_type'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_agent'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_deposit'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_withdrawal'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_number_of_transaction'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_bet_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('J1', $this->lang->line('label_win_loss'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('K1', $this->lang->line('label_rolling_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('L1', $this->lang->line('label_promotion'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('M1', $this->lang->line('label_bonus'));

		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);

		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setSize(12);



				$rowCount = 2;

				$result_count = 1;



				$styleArrayRed = get_excel_color_status(EXPORT_COLOR_RED);

				$styleArrayBlue = get_excel_color_status(EXPORT_COLOR_BLUE);

				$styleArrayBlack = get_excel_color_status(EXPORT_COLOR_BLACK);

				if($upline_query->num_rows() > 0)

				{

					foreach($upline_query->result() as $upline_row)

					{

						$comm_arr = array(

											GAME_SPORTSBOOK => array(

																		'total_bet' => 0,

																		'total_bet_amount' => 0,

																		'total_win_loss' => 0,

																		'total_rolling_amount' => 0,

																	),

											GAME_LIVE_CASINO => array(

																		'total_bet' => 0,

																		'total_bet_amount' => 0,

																		'total_win_loss' => 0,

																		'total_rolling_amount' => 0,

																	),

											GAME_SLOTS => array(

																	'total_bet' => 0,

																	'total_bet_amount' => 0,

																	'total_win_loss' => 0,

																	'total_rolling_amount' => 0,

																),

											GAME_OTHERS => array(

																	'total_bet' => 0,

																	'total_bet_amount' => 0,

																	'total_win_loss' => 0,

																	'total_rolling_amount' => 0,

																)

										);

									

						//Get win loss

						$where = '';

					

						if( ! empty($arr['from_date']))

						{

							$where .= ' AND payout_time >= ' . strtotime($arr['from_date']);

						}

						

						if( ! empty($arr['to_date']))

						{

							$where .= ' AND payout_time <= ' . strtotime($arr['to_date']);

						}

						

						$select = "game_type_code, COUNT(transaction_id) AS total_bet, SUM(bet_amount) AS total_bet_amount, SUM(win_loss) AS total_win_loss, SUM(bet_amount_valid) AS total_rolling_amount";			

						$wl_query_string = "SELECT {$select} FROM {$dbprefix}transaction_report WHERE player_id = '{$upline_row->player_id}' $where GROUP BY game_type_code";

						$wl_query = $this->db->query($wl_query_string);

						if($wl_query->num_rows() > 0)

						{

							foreach($wl_query->result() as $wl_row)

							{

								$game_type_code = GAME_OTHERS;

								

								if($wl_row->game_type_code == GAME_SPORTSBOOK OR $wl_row->game_type_code == GAME_LIVE_CASINO OR $wl_row->game_type_code == GAME_SLOTS)

								{

									$game_type_code = $wl_row->game_type_code;

								}

								

								$comm_arr[$game_type_code]['total_bet'] = ($comm_arr[$game_type_code]['total_bet'] + $wl_row->total_bet);

								$comm_arr[$game_type_code]['total_bet_amount'] = ($comm_arr[$game_type_code]['total_bet_amount'] + $wl_row->total_bet_amount);

								$comm_arr[$game_type_code]['total_win_loss'] = ($comm_arr[$game_type_code]['total_win_loss'] + $wl_row->total_win_loss);

								$comm_arr[$game_type_code]['total_rolling_amount'] = ($comm_arr[$game_type_code]['total_rolling_amount'] + $wl_row->total_rolling_amount);

							}

						}



						$wl_query->free_result();

						

						$deposit = 0;

						$withdrawal = 0;

						$promotion = 0;

						$bonus = 0;

						

						//Get deposit

						/*

						$where = '';

					

						if( ! empty($arr['from_date']))

						{

							$where .= ' AND report_date >= ' . strtotime($arr['from_date']);

						}

						

						if( ! empty($arr['to_date']))

						{

							$where .= ' AND report_date <= ' . strtotime($arr['to_date']);

						}

						

						$where .= ' AND transfer_type IN (' . TRANSFER_ADJUST_IN . ', ' . TRANSFER_OFFLINE_DEPOSIT . ', ' . TRANSFER_PG_DEPOSIT . ')';

						

						$dp_query_string = "SELECT SUM(deposit_amount) AS total FROM {$dbprefix}cash_transfer_report WHERE username = '{$upline_row->username}' $where";

						$dp_query = $this->db->query($dp_query_string);

						if($dp_query->num_rows() > 0)

						{

							$dp_row = $dp_query->row();

							$deposit = $dp_row->total;

						}

						

						$dp_query->free_result();

						*/

						$where = '';

						if( ! empty($arr['from_date']))

						{

							$where .= ' AND a.updated_date >= ' . strtotime($arr['from_date']);

						}

						

						if( ! empty($arr['to_date']))

						{

							$where .= ' AND a.updated_date <= ' . strtotime($arr['to_date']);

						}

						$where .= ' AND a.status = '. STATUS_APPROVE;

						$dp_query_string = "SELECT SUM(a.amount_apply) AS total FROM {$dbprefix}deposits a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.player_id = '{$upline_row->player_id}' $where";



						$dp_query = $this->db->query($dp_query_string);

						if($dp_query->num_rows() > 0)

						{

							$dp_row = $dp_query->row();

							$deposit += $dp_row->total;

						}

						$dp_query->free_result();

						

						//Get withdrawal

						$where = '';

						$withdrawal = 0;

						

						if( ! empty($arr['from_date']))

						{

							$where .= ' AND report_date >= ' . strtotime($arr['from_date']);

						}

						

						if( ! empty($arr['to_date']))

						{

							$where .= ' AND report_date <= ' . strtotime($arr['to_date']);

						}

						

						$where .= ' AND transfer_type IN (' . TRANSFER_ADJUST_OUT . ')';

						

						$wd_query_string = "SELECT SUM(withdrawal_amount) AS total FROM {$dbprefix}cash_transfer_report WHERE username = '{$upline_row->username}' $where";

						$wd_query = $this->db->query($wd_query_string);

						if($wd_query->num_rows() > 0)

						{

							$wd_row = $wd_query->row();

							$withdrawal += $wd_row->total;

						}

						

						$wd_query->free_result();

						

						$where = '';

						if( ! empty($arr['from_date']))

						{

							$where .= ' AND a.updated_date >= ' . strtotime($arr['from_date']);

						}

						

						if( ! empty($arr['to_date']))

						{

							$where .= ' AND a.updated_date <= ' . strtotime($arr['to_date']);

						}

						$where .= ' AND a.status = '. STATUS_APPROVE;

						//$where .= ' AND a.transfer_type IN (' . TRANSFER_POINT_OUT . ', ' . TRANSFER_ADJUST_OUT . ', ' . TRANSFER_WITHDRAWAL . ')';

						$wd_query_string = "SELECT SUM(a.amount) AS total FROM {$dbprefix}withdrawals a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND a.player_id = '{$upline_row->player_id}' $where";

						$wd_query = $this->db->query($wd_query_string);

						if($wd_query->num_rows() > 0)

						{

							$wd_row = $wd_query->row();

							$withdrawal += $wd_row->total;

						}

						

						$wd_query->free_result();





						//Get promotion

						$where = '';

					

						if( ! empty($arr['from_date']))

						{

							$where .= ' AND report_date >= ' . strtotime($arr['from_date']);

						}

						

						if( ! empty($arr['to_date']))

						{

							$where .= ' AND report_date <= ' . strtotime($arr['to_date']);

						}

						

						$where .= ' AND transfer_type IN (' . TRANSFER_PROMOTION . ')';

						

						$promo_query_string = "SELECT SUM(deposit_amount) AS total FROM {$dbprefix}cash_transfer_report WHERE username = '{$upline_row->username}' $where";

						$promo_query = $this->db->query($promo_query_string);

						if($promo_query->num_rows() > 0)

						{

							$promo_row = $promo_query->row();

							$promotion = $promo_row->total;

						}

						

						$promo_query->free_result();



						//Get Bonus

						$where = "";

						if( ! empty($arr['from_date']))

						{

							$where .= ' AND report_date >= ' . strtotime($arr['from_date']);

						}

						

						if( ! empty($arr['to_date']))

						{

							$where .= ' AND report_date <= ' . strtotime($arr['to_date']);

						}

						

						$where .= ' AND transfer_type IN (' . TRANSFER_BONUS . ')';

						

						$bonus_query_string = "SELECT SUM(deposit_amount) AS total FROM {$dbprefix}cash_transfer_report WHERE username = '{$upline_row->username}' $where";

						$bonus_query = $this->db->query($bonus_query_string);

						if($bonus_query->num_rows() > 0)

						{

							$bonus_row = $bonus_query->row();

							$bonus = $bonus_row->total;

						}

						

						$bonus_query->free_result();



						//Calculation

						$total_bet = ($comm_arr[GAME_SPORTSBOOK]['total_bet'] + $comm_arr[GAME_LIVE_CASINO]['total_bet'] + $comm_arr[GAME_SLOTS]['total_bet'] + $comm_arr[GAME_OTHERS]['total_bet']);

						$total_bet_amount = ($comm_arr[GAME_SPORTSBOOK]['total_bet_amount'] + $comm_arr[GAME_LIVE_CASINO]['total_bet_amount'] + $comm_arr[GAME_SLOTS]['total_bet_amount'] + $comm_arr[GAME_OTHERS]['total_bet_amount']);

						$total_win_loss = ($comm_arr[GAME_SPORTSBOOK]['total_win_loss'] + $comm_arr[GAME_LIVE_CASINO]['total_win_loss'] + $comm_arr[GAME_SLOTS]['total_win_loss'] + $comm_arr[GAME_OTHERS]['total_win_loss']);

						$total_rolling_amount = ($comm_arr[GAME_SPORTSBOOK]['total_rolling_amount'] + $comm_arr[GAME_LIVE_CASINO]['total_rolling_amount'] + $comm_arr[GAME_SLOTS]['total_rolling_amount'] + $comm_arr[GAME_OTHERS]['total_rolling_amount']);



						//Prepare data

						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $this->lang->line('level_ply'));

						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, '-');

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $rowCount, $upline_row->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, ( ! empty($upline_row->upline) ? $upline_row->upline : '-'));





						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($deposit, 2, '.', ','));

						if($deposit > 0){

							$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlack);

						}





						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, number_format($withdrawal, 2, '.', ','));

						if($deposit > 0){

							$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlack);

						}









						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $total_bet);

						if($total_bet > 0){

							$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlack);

						}





						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, number_format($total_bet_amount, 2, '.', ','));

						if($total_bet_amount > 0){

							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlack);

						}







						$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($total_win_loss, 2, '.', ','));

						if($total_bet_amount >= 0){

							if($total_bet_amount > 0){

								$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlue);

							}else{

								$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlack);

							}

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayRed);

						}





						$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($total_rolling_amount, 2, '.', ','));

						if($total_rolling_amount > 0){

							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, number_format($promotion, 2, '.', ','));

						if($promotion > 0){

							$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, number_format($bonus, 2, '.', ','));

						if($bonus > 0){

							$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$rowCount++;

	           			$result_count++;

					}

				}



				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$upline_query->free_result();

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}



	public function player_risk_export_excel_check(){

		//Initial output data

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_PLAYER_RISK_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_player_risk');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}



	public function player_risk_export_excel(){

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_PLAYER_RISK_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_player_risk');

			if(!empty($arr)){

				$columns = array( 

					0 => 'a.player_risk_id',

					1 => 'a.report_date',

					2 => 'b.username',

					3 => 'a.suspended',

					4 => 'a.percentage',

					5 => 'a.total_win_lose',

					6 => 'a.win_loss_suspend',

				);

				$where = '';

				if(isset($arr['from_date']))

				{

					if( ! empty($arr['from_date']))

					{

						$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);

					}

					

					if( ! empty($arr['to_date']))

					{

						$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);

					}

					

					if( ! empty($arr['suspended']))

					{

						$where .= ' AND a.suspended <= ' . $arr['suspended'];

					}

					

					if( ! empty($arr['percentage']))

					{

						$where .= ' AND a.percentage <= ' . $arr['percentage'];

					}

					

					

					if( ! empty($arr['username']))

					{

						$where .= " AND b.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	

					}



					if( ! empty($arr['player_risk_id']))

					{

						$where .= ' AND a.player_risk_id = ' . $arr['player_risk_id'];

					}

				}

				$select = implode(',', $columns);

				$dbprefix = $this->db->dbprefix;

				$posts = NULL;

				$query_string = "(SELECT {$select} FROM {$dbprefix}player_risk_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!' $where)";

				$query = $this->db->query($query_string);

				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}



				$fileName = $this->lang->line('title_player_risk_report').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_player_risk_report'));

        		// set Header



        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_date'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_type'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_percentage'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_total_win_loss'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_win_loss_suspended'));



		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);



		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);



				$rowCount = 2;

				$result_count = 1;



				$data = array();

				if(!empty($posts))

				{

					foreach ($posts as $post)

					{

						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, (($post->report_date > 0) ? date('Y-m-d H:i:s', $post->report_date) : '-'));

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $rowCount, $post->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $this->lang->line(suspended_type($post->suspended)));

						

						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $post->percentage);

						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $post->total_win_lose);

						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $post->win_loss_suspend);

						$rowCount++;

	           			$result_count++;

					}

				}



				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$query->free_result();

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}



	public function winloss_player_report_export_check(){

		//Initial output data

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_WIN_LOSS_PLAYER_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_WIN_LOSS_REPORT_PLAYER) == TRUE)

		{

			$arr = $this->session->userdata('search_report_winloss_player');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}



	public function winloss_player_report_export(){

		if(permission_validation(PERMISSION_WIN_LOSS_PLAYER_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_WIN_LOSS_REPORT_PLAYER) == TRUE)

		{

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$arr = $this->session->userdata('search_report_winloss_player');

			if(!empty($arr)){

				$columns = array( 

					'a.player_id',

					'b.username',

					'b.level_id',

					'b.bank_account_name',

					'SUM(a.bet_amount) AS total_bet_amount',

					'SUM(a.bet_amount_valid) AS total_rolling_amount',

					'SUM(a.win_loss) AS total_win_loss',

					'b.tag_id',

				);



				$col = 0;		

				

				$where = '';	

				

				if( ! empty($arr['agent']))

				{

					$where = "AND a.player_id = 'ABC'";

					$agent = $this->user_model->get_user_data_by_username($arr['agent']);

					if(!empty($agent)){

						$response_upline = $this->user_model->get_downline_data($agent['username']);

						if(!empty($response_upline)){

							$where = "AND b.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";

						}

					}

				}else{

					$where = "AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";

				}



				if( ! empty($arr['from_date']))

				{

					$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);

				}

				

				if( ! empty($arr['to_date']))

				{

					$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);

				}



				if( ! empty($arr['game_provider_code']))

				{

					$where .= " AND a.game_provider_code = '" . $arr['game_provider_code'] . "'";

				}



				if( ! empty($arr['game_type_code']))

				{

					$where .= " AND a.game_type_code = '" . $arr['game_type_code'] . "'";

				}

				

				if( ! empty($arr['username']))

				{

					$where .= " AND b.username = '" . $arr['username'] . "'";

				}



				if(!empty($arr['excludeProviderCheckboxes'])){

					$excludeProviderCheckboxes = '"'.implode('","', $arr['excludeProviderCheckboxes']).'"';

					$where .= " AND a.game_provider_code NOT IN(" . $excludeProviderCheckboxes . ")";

				}



				if(!empty($arr['excludeGametypeCheckboxes'])){

					$excludeGametypeCheckboxes = '"'.implode('","', $arr['excludeGametypeCheckboxes']).'"';

					$where .= " AND a.game_type_code NOT IN(" . $excludeGametypeCheckboxes . ")";

				}



				$select = implode(',', $columns);

				$dbprefix = $this->db->dbprefix;

				

				$posts = NULL;

				$query_string = "SELECT {$select} FROM {$dbprefix}win_loss_report a, {$dbprefix}players b WHERE a.player_id = b.player_id $where GROUP BY a.player_id";

				$query_string_2 = " ORDER by b.username DESC";

				$query = $this->db->query($query_string . $query_string_2);

				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}

				

				$query->free_result();

				

				//Get total sum up

				$sum_select = implode(',', $sum_columns);

				$total_data = array(

					'total_bet_amount' => 0,

					'total_win_loss' => 0,

					'total_rolling_amount' => 0,

				);

				

				$query->free_result();



				$fileName = $this->lang->line('title_win_loss_report_player').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_win_loss_report_player'));

        		// set Header

        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

        		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_membership_level')."(".$this->lang->line('label_membership_number').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_tag_code'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_bank_account_name'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_bet_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_rolling_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_win_loss'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_rtp'));





		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);



		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);



		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);



				$rowCount = 2;

				$result_count = 1;



				$styleArrayRed = get_excel_color_status(EXPORT_COLOR_RED);

				$styleArrayBlue = get_excel_color_status(EXPORT_COLOR_BLUE);

				$styleArrayBlack = get_excel_color_status(EXPORT_COLOR_BLACK);

				//Prepare data

				$data = array();

				if(!empty($posts))

				{

					$tag_list = $this->tag_model->get_tag_list();

					foreach ($posts as $post)

					{

						$rtp = 0;

						$total_data['total_bet_amount'] += $post->total_bet_amount;

						$total_data['total_win_loss'] += $post->total_win_loss;

						$total_data['total_rolling_amount'] += $post->total_rolling_amount;



						if(is_nan($post->total_win_loss / $post->total_rolling_amount * 100)){

							$rtp = 0.00;						

						}else{

							$rtp = number_format($post->total_win_loss / $post->total_rolling_amount * 100, 2, '.', ',');

						}



						$tag = "";

						if(isset($tag_list[$post->tag_id])){

							$tag = $tag_list[$post->tag_id]['tag_code'];						

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $rowCount, $post->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $tag);

						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $post->level_id-1);

						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, (( ! empty($post->bank_account_name)) ? $post->bank_account_name : '-'));

						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($post->total_bet_amount, 2, '.', ','));

						if($post->total_bet_amount > 0){

							$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, number_format($post->total_rolling_amount, 2, '.', ','));

						if($post->total_rolling_amount > 0){

							$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, number_format($post->total_win_loss, 2, '.', ','));

						if($post->total_win_loss > 0){

							$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($post->total_win_loss < 0){

							$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $rtp."%");

						if($post->total_win_loss > 0){

							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($post->total_win_loss < 0){

							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$rowCount++;

           				$result_count++;

					}

				}



				if(is_nan($total_data['total_win_loss'] / $total_data['total_rolling_amount'] * 100)){

					$rtp = 0.00;						

				}else{

					$rtp = number_format($total_data['total_win_loss'] / $total_data['total_rolling_amount'] * 100, 2, '.', ',');

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line('label_total'));

				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($total_data['total_bet_amount'], 2, '.', ','));

				if($total_data['total_bet_amount'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlue);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlack);

				}

				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, number_format($total_data['total_rolling_amount'], 2, '.', ','));

				if($total_data['total_rolling_amount'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlue);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlack);

				}

				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, number_format($total_data['total_win_loss'], 2, '.', ','));

				if($total_data['total_win_loss'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['total_win_loss'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlack);

				}

				$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $rtp."%");

				if($total_data['total_win_loss'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['total_win_loss'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$rowCount++;

				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}



	public function yearly_report_export_check(){

		//Initial output data

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_YEARLY_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_yearly');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();

	}



	public function yearly_report_export(){

		if(permission_validation(PERMISSION_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_YEARLY_REPORT) == TRUE)

		{

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$arr = $this->session->userdata('search_report_yearly');

			$dbprefix = $this->db->dbprefix;

			if(!empty($arr)){

				if( ! empty($arr['username']))

				{

					$where .= " AND P.username = '" . $arr['username'] . "'";	

				}



				if( ! empty($arr['upline']))

				{

					$where .= " AND P.upline = '" . $arr['upline'] . "'";	

				}



				if($arr['type'] == YEARLY_REPORT_SETTING_DEPOSIT){

					$type = "deposit_amount";

				}else if($arr['type'] == YEARLY_REPORT_SETTING_WITHDRAWAL){

					$type = "withdrawals_amount";

				}else if($arr['type'] == YEARLY_REPORT_SETTING_PROMOTION){

					$type = "promotion_amount";

				}else if($arr['type'] == YEARLY_REPORT_SETTING_TURNOVER){

					$type = "bet_amount_valid";

				}else{

					$type = "win_loss";

				}



				$jan_datetime = strtotime($arr['from_date']."-01-01");

				$feb_datetime = strtotime($arr['from_date']."-02-01");

				$mar_datetime = strtotime($arr['from_date']."-03-01");

				$apr_datetime = strtotime($arr['from_date']."-04-01");

				$may_datetime = strtotime($arr['from_date']."-05-01");

				$jun_datetime = strtotime($arr['from_date']."-06-01");

				$jul_datetime = strtotime($arr['from_date']."-07-01");

				$aug_datetime = strtotime($arr['from_date']."-08-01");

				$sep_datetime = strtotime($arr['from_date']."-09-01");

				$oct_datetime = strtotime($arr['from_date']."-10-01");

				$nov_datetime = strtotime($arr['from_date']."-11-01");

				$dec_datetime = strtotime($arr['from_date']."-12-01");



				$where_total_jan = "";

				$where_total_feb = "";

				$where_total_mar = "";

				$where_total_apr = "";

				$where_total_may = "";

				$where_total_jun = "";

				$where_total_jul = "";

				$where_total_aug = "";

				$where_total_sep = "";

				$where_total_oct = "";

				$where_total_nov = "";

				$where_total_dec = "";

				$where_total_total = "";



				$columns = array( 

					'P.player_id',

					'P.username',

					'P.level_id',

					'value_jan',

					'value_feb',

					'value_mar',

					'value_apr',

					'value_may',

					'value_jun',

					'value_jul',

					'value_aug',

					'value_sep',

					'value_oct',

					'value_nov',

					'value_dec',

					'value_total',

				);



				$columns = array( 

					'P.player_id',

					'P.username',

					'P.level_id',

					'value_jan',

					'value_feb',

					'value_mar',

					'value_apr',

					'value_may',

					'value_jun',

					'value_jul',

					'value_aug',

					'value_sep',

					'value_oct',

					'value_nov',

					'value_dec',

					'value_total',

				);





				$dir = "desc";

				$date = date('n');

				$order = $columns[$date+2];



				$query_string = "SELECT P.player_id,P.username,P.level_id,COALESCE(B.value_jan,0) as value_jan,COALESCE(C.value_feb,0) as value_feb,COALESCE(D.value_mar,0) as value_mar,COALESCE(E.value_apr,0) as value_apr,COALESCE(F.value_may,0) as value_may,COALESCE(G.value_jun,0) as value_jun,COALESCE(H.value_jul,0) as value_jul,COALESCE(I.value_aug,0) as value_aug,COALESCE(J.value_sep,0) as value_sep,COALESCE(K.value_oct,0) as value_oct,COALESCE(L.value_nov,0) as value_nov,COALESCE(M.value_dec,0) as value_dec,COALESCE(N.value_total,0) as value_total";

				$query_string .= " FROM {$dbprefix}players P";



				if( ! empty($arr['from_date']))

				{

					$where_total_jan .= 'report_date = ' . $jan_datetime;

				}

				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_jan FROM bctp_total_win_loss_report_month WHERE $where_total_jan)B ON P.player_id = B.player_id";



				if( ! empty($arr['from_date']))

				{

					$where_total_feb .= 'report_date = ' . $feb_datetime;

				}

				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_feb FROM bctp_total_win_loss_report_month WHERE $where_total_feb)C ON P.player_id = C.player_id";



				if( ! empty($arr['from_date']))

				{

					$where_total_mar .= 'report_date = ' . $mar_datetime;

				}

				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_mar FROM bctp_total_win_loss_report_month WHERE $where_total_mar)D ON P.player_id = D.player_id";



				if( ! empty($arr['from_date']))

				{

					$where_total_apr .= 'report_date = ' . $apr_datetime;

				}

				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_apr FROM bctp_total_win_loss_report_month WHERE $where_total_apr)E ON P.player_id = E.player_id";



				if( ! empty($arr['from_date']))

				{

					$where_total_may .= 'report_date = ' . $may_datetime;

				}

				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_may FROM bctp_total_win_loss_report_month WHERE $where_total_may)F ON P.player_id = F.player_id";



				if( ! empty($arr['from_date']))

				{

					$where_total_jun .= 'report_date = ' . $jun_datetime;

				}

				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_jun FROM bctp_total_win_loss_report_month WHERE $where_total_jun)G ON P.player_id = G.player_id";



				if( ! empty($arr['from_date']))

				{

					$where_total_jul .= 'report_date = ' . $jul_datetime;

				}

				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_jul FROM bctp_total_win_loss_report_month WHERE $where_total_jul)H ON P.player_id = H.player_id";



				if( ! empty($arr['from_date']))

				{

					$where_total_aug .= 'report_date = ' . $aug_datetime;

				}

				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_aug FROM bctp_total_win_loss_report_month WHERE $where_total_aug)I ON P.player_id = I.player_id";



				if( ! empty($arr['from_date']))

				{

					$where_total_sep .= 'report_date = ' . $sep_datetime;

				}

				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_sep FROM bctp_total_win_loss_report_month WHERE $where_total_sep)J ON P.player_id = J.player_id";



				if( ! empty($arr['from_date']))

				{

					$where_total_oct .= 'report_date = ' . $oct_datetime;

				}

				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_oct FROM bctp_total_win_loss_report_month WHERE $where_total_oct)K ON P.player_id = K.player_id";



				if( ! empty($arr['from_date']))

				{

					$where_total_nov .= 'report_date = ' . $nov_datetime;

				}

				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_nov FROM bctp_total_win_loss_report_month WHERE $where_total_nov)L ON P.player_id = L.player_id";



				if( ! empty($arr['from_date']))

				{

					$where_total_dec .= 'report_date = ' . $dec_datetime;

				}

				$query_string .= " LEFT OUTER JOIN( SELECT player_id, $type as value_dec FROM bctp_total_win_loss_report_month WHERE $where_total_dec)M ON P.player_id = M.player_id";



				if( ! empty($arr['from_date']))

				{

					$where_total_total .= 'report_date >= ' . $jan_datetime;

					$where_total_total .= ' AND report_date <= ' . $dec_datetime;

				}

				$query_string .= " LEFT OUTER JOIN( SELECT player_id, sum($type) as value_total FROM bctp_total_win_loss_report_month WHERE $where_total_total GROUP BY player_id)N ON P.player_id = N.player_id";

				$query_string .= " WHERE P.upline_ids LIKE '%,1,%' ESCAPE '!' $where ORDER by {$order} {$dir}";

				$query = $this->db->query($query_string);

				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}

				

				$query->free_result();

				

				//Get total sum up

				$sum_select = implode(',', $sum_columns);

				$total_data = array(

					'value_jan' => 0,

					'value_feb' => 0,

					'value_mar' => 0,

					'value_apr' => 0,

					'value_may' => 0,

					'value_jun' => 0,

					'value_jul' => 0,

					'value_aug' => 0,

					'value_sep' => 0,

					'value_oct' => 0,

					'value_nov' => 0,

					'value_dec' => 0,

				);

				

				$query->free_result();



				$fileName = $this->lang->line('title_yearly_report').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_yearly_report'));

        		// set Header

        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

        		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_username'));

        		$objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('month_jan'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('month_feb'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('month_mar'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('month_apr'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('month_may'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('month_jun'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('month_jul'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('J1', $this->lang->line('month_aug'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('K1', $this->lang->line('month_sep'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('L1', $this->lang->line('month_oct'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('M1', $this->lang->line('month_nov'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('N1', $this->lang->line('month_dec'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('O1', $this->lang->line('label_total'));







		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);



		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);

		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setSize(12);



				$rowCount = 2;

				$result_count = 1;



				$styleArrayRed = get_excel_color_status(EXPORT_COLOR_RED);

				$styleArrayBlue = get_excel_color_status(EXPORT_COLOR_BLUE);

				$styleArrayBlack = get_excel_color_status(EXPORT_COLOR_BLACK);

				//Prepare data

				$data = array();

				if(!empty($posts))

				{

					foreach ($posts as $post)

					{

						$total_data['value_jan'] += $post->value_jan;

						$total_data['value_feb'] += $post->value_feb;

						$total_data['value_mar'] += $post->value_mar;

						$total_data['value_apr'] += $post->value_apr;

						$total_data['value_may'] += $post->value_may;

						$total_data['value_jun'] += $post->value_jun;

						$total_data['value_jul'] += $post->value_jul;

						$total_data['value_aug'] += $post->value_aug;

						$total_data['value_sep'] += $post->value_sep;

						$total_data['value_oct'] += $post->value_oct;

						$total_data['value_nov'] += $post->value_nov;

						$total_data['value_dec'] += $post->value_dec;

						$total_data['value_total'] += $post->value_total;



						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $rowCount, $post->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, number_format($post->value_jan, 2, '.', ','));

						if($post->value_jan > 0){

							$objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($post->value_jan < 0){

							$objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, number_format($post->value_feb, 2, '.', ','));

						if($post->value_feb > 0){

							$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($post->value_feb < 0){

							$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, number_format($post->value_mar, 2, '.', ','));

						if($post->value_mar > 0){

							$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($post->value_mar < 0){

							$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($post->value_apr, 2, '.', ','));

						if($post->value_apr > 0){

							$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($post->value_apr < 0){

							$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, number_format($post->value_may, 2, '.', ','));

						if($post->value_may > 0){

							$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($post->value_may < 0){

							$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, number_format($post->value_jun, 2, '.', ','));

						if($post->value_jun > 0){

							$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($post->value_jun < 0){

							$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, number_format($post->value_jul, 2, '.', ','));

						if($post->value_jul > 0){

							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($post->value_jul < 0){

							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($post->value_aug, 2, '.', ','));

						if($post->value_aug > 0){

							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($post->value_aug < 0){

							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($post->value_sep, 2, '.', ','));

						if($post->value_sep > 0){

							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($post->value_sep < 0){

							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, number_format($post->value_oct, 2, '.', ','));

						if($post->value_oct > 0){

							$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($post->value_oct < 0){

							$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, number_format($post->value_nov, 2, '.', ','));

						if($post->value_nov > 0){

							$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($post->value_nov < 0){

							$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, number_format($post->value_dec, 2, '.', ','));

						if($post->value_dec > 0){

							$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($post->value_dec < 0){

							$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, number_format($post->value_total, 2, '.', ','));

						if($post->value_total > 0){

							$objPHPExcel->getActiveSheet()->getStyle('O'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($post->value_total < 0){

							$objPHPExcel->getActiveSheet()->getStyle('O'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('O'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$rowCount++;

           				$result_count++;

					}

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line('label_total'));

				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, number_format($total_data['value_jan'], 2, '.', ','));

				if($total_data['value_jan'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['value_jan'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, number_format($total_data['value_feb'], 2, '.', ','));

				if($total_data['value_feb'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['value_feb'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, number_format($total_data['value_mar'], 2, '.', ','));

				if($total_data['value_mar'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['value_mar'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($total_data['value_apr'], 2, '.', ','));

				if($total_data['value_apr'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['value_apr'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, number_format($total_data['value_may'], 2, '.', ','));

				if($total_data['value_may'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['value_may'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, number_format($total_data['value_jun'], 2, '.', ','));

				if($total_data['value_jun'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['value_jun'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, number_format($total_data['value_jul'], 2, '.', ','));

				if($total_data['value_jul'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['value_jul'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($total_data['value_aug'], 2, '.', ','));

				if($total_data['value_aug'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['value_aug'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($total_data['value_sep'], 2, '.', ','));

				if($total_data['value_sep'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['value_sep'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, number_format($total_data['value_oct'], 2, '.', ','));

				if($total_data['value_oct'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['value_oct'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, number_format($total_data['value_nov'], 2, '.', ','));

				if($total_data['value_nov'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['value_nov'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, number_format($total_data['value_dec'], 2, '.', ','));

				if($total_data['value_dec'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['value_dec'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, number_format($total_data['value_total'], 2, '.', ','));

				if($total_data['value_total'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('O'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['value_total'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('O'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('O'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$rowCount++;

				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}else{

				echo "asas";

			}

		}

	}



	public function player_report_export_check(){

		//Initial output data

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_PLAYER_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_PLAYER_VIEW) == TRUE)

		{

			$arr = $this->session->userdata('search_players');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();

	}



	public function player_report_export(){

		if(permission_validation(PERMISSION_PLAYER_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_PLAYER_VIEW) == TRUE)

		{

			$arr = $this->session->userdata('search_players');

			$arr['executed_by'] = $this->session->userdata('username');

			if(TELEGRAM_STATUS == STATUS_ACTIVE){

				send_logs_telegram(TELEGRAM_LOGS,TELEGRAM_LOGS_TYPE_PLAYER_LIST_EXPORT,$arr);

			}

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$order = $this->input->post("order", TRUE);

			if(!empty($arr)){

				//Table Columns

				$columns = array( 

					0 => 'a.player_id',

					1 => 'a.username',

					2 => 'a.nickname',

					3 => 'a.level_id',

					4 => 'a.upline',

					5 => 'a.player_type',

					6 => 'a.points',

					7 => 'a.rewards',

					8 => 'a.active',

					9 => 'a.mark',

					10 => 'a.bank_group_id',

					11 => 'a.is_offline_deposit',

					12 => 'a.created_date',

					13 => 'a.last_login_date',

					14 => 'a.created_ip',

					15 => 'a.last_login_ip',

					16 => 'a.is_online_deposit',

					17 => 'a.is_credit_card_deposit',

					18 => 'a.is_hypermart_deposit',

					19 => 'a.mobile',

					20 => 'a.line_id',

					21 => 'a.bank_account_name',

					22 => 'a.tag_id',

					23 => 'a.tag_ids',

				);



				$col = 0;

				$dir = "";

				

				if( ! empty($order))

				{

					foreach($order as $o)

					{

						$col = $o['column'];

						$dir = $o['dir'];

					}

				}



				if($dir != "asc" && $dir != "desc")

				{

					$dir = "desc";

				}

				

				if( ! isset($columns[$col]))

				{

					$order = $columns[0];

				}

				else

				{

					$order = $columns[$col];

				}



				$arr = $this->session->userdata('search_players');				

				$max_level = $arr['max_level'];

				if( ! empty($arr['agent']))

				{

					$where = "WHERE player_id = 'ABC'";

					$agent = $this->user_model->get_user_data_by_username($arr['agent']);

					if(!empty($agent)){

						$response_upline = $this->user_model->get_downline_data($agent['username']);

						if(!empty($response_upline)){

							$where = "WHERE a.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";

						}

					}

				}else{

					$where = "WHERE a.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";

				}



				if(isset($arr['from_date']))

				{

					if( ! empty($arr['from_date'])){

						$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);

					}

				}

				if( ! empty($arr['to_date']))

				{

					if( ! empty($arr['to_date'])){

						$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);

					}

				}



				if( ! empty($arr['bank_account_name'])){

				    $player_bank = $this->bank_model->get_all_player_id_with_bank_account_name($arr['bank_account_name']);

	        	    if(!empty($player_bank)){

	        	       $player_array = array();

	        	       foreach($player_bank as $player_row){

	            	        if( ! in_array($player_row['player_id'], $player_array))

	            			{

	            				array_push($player_array,$player_row['player_id']);

	            			}    

	        	       }

	        	       

	        	       if(!empty($player_array)){

	        	           $player_bank_string = implode(',', $player_array);

	        	           $where .= " AND a.player_id IN (".$player_bank_string.")";

	        	       }else{

	        	           $where .= " AND a.player_id = 0";

	        	       }

	        	    }else{

	        	       $where .= " AND a.player_id = 0";

	        	    }

				}



				if( ! empty($arr['bank_account_no'])){

				    $player_bank = $this->bank_model->get_all_player_id_with_bank_account_no($arr['bank_account_no']);

	        	    if(!empty($player_bank)){

	        	       $player_array = array();

	        	       foreach($player_bank as $player_row){

	            	        if( ! in_array($player_row['player_id'], $player_array))

	            			{

	            				array_push($player_array,$player_row['player_id']);

	            			}    

	        	       }

	        	       

	        	       if(!empty($player_array)){

	        	           $player_bank_string = implode(',', $player_array);

	        	           $where .= " AND a.player_id IN (".$player_bank_string.")";

	        	       }else{

	        	           $where .= " AND a.player_id = 0";

	        	       }

	        	    }else{

	        	       $where .= " AND a.player_id = 0";

	        	    }

				}

				

				if( ! empty($arr['upline']))

				{

					$where .= " AND a.upline LIKE '%" . $arr['upline'] . "%' ESCAPE '!'";	

				}

				

				if( ! empty($arr['username']))

				{

					$where .= " AND a.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	

				}



				if( ! empty($arr['mobile']))

				{

					$where .= " AND a.mobile LIKE '%" . $arr['mobile'] . "%' ESCAPE '!'";	

				}



				if($arr['player_type'] >= 1 && $arr['player_type'] <= sizeof(get_player_type()))

				{

					$where .= ' AND a.player_type = ' . $arr['player_type'];

				}



				if( ! empty($arr['referrer']))

				{

					$where .= ' AND a.referrer = "' . $arr['referrer'].'"';

				}

				

				if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_SUSPEND)

				{

					$where .= ' AND a.active = ' . $arr['status'];

				}



				if(!empty($arr['tag'])){

					$where .= ' AND (';

					for($i=0;$i<sizeof($arr['tag']);$i++){

						if($i == 0){

							$where .= 'a.tag_id = ' . $arr['tag'][$i];

						}else{

							$where .= ' OR a.tag_id = ' . $arr['tag'][$i];

						}

					}

					$where .= ')';

				}



				if(!empty($arr['tag_player'])){

					$where .= ' AND (';

					for($i=0;$i<sizeof($arr['tag_player']);$i++){

						if($i == 0){

							$where .= "a.tag_ids LIKE '%," . $arr['tag_player'][$i] . ",%' ESCAPE '!'";

						}else{

							$where .= " OR a.tag_ids LIKE '%," . $arr['tag_player'][$i] . ",%' ESCAPE '!'";

						}

					}

					$where .= ')';

				}

				

				$select = implode(',', $columns);

				$dbprefix = $this->db->dbprefix;

				

				$posts = NULL;

				$query_string = "SELECT {$select} FROM {$dbprefix}players a $where";

				$query_string_2 = " ORDER by {$order} {$dir}";

				$query = $this->db->query($query_string . $query_string_2);



				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}

				

				$query->free_result();



				$player_list = array();

				$bank_list = array();

				$player_bank_list = array();

				$bank_group_list = array();



				if(!empty($posts))

				{

					$tag_list = $this->tag_model->get_tag_list();

					$tag_player_list = $this->tag_model->get_tag_player_list();

					foreach ($posts as $post)

					{

						array_push($player_list, $post->player_id);

						$player_bank_list[$post->player_id]['bank_account'] = array();

					}



					$this->db->select('bank_id, bank_name');

					$query = $this->db->get('banks');

					if($query->num_rows() > 0)

					{

						foreach($query->result() as $row)

						{

							$bank_list[$row->bank_id] = $row->bank_name;

						}

					}

					$query->free_result();

					$bank_group_list = $this->group_model->get_group_list(GROUP_BANK);

				}



				$this->db->select('player_id,bank_id,bank_account_name,bank_account_no,bank_account_address');

				//$this->db->where_in('player_id',$player_list);

				$query = $this->db->get('player_bank');

				if($query->num_rows() > 0)

				{

					foreach($query->result() as $row)

					{

						array_push($player_bank_list[$row->player_id]['bank_account'], (array) $row);

					}

				}

				$query->free_result();



				$fileName = $this->lang->line('title_player').' - '.date("Y-m-d", time())." ".time().'.xlsx';



				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_player'));





        		if(permission_validation(PERMISSION_PLAYER_ACCOUNT_NAME) == TRUE){

					if(permission_validation(PERMISSION_PLAYER_MOBILE) == TRUE){

						if(permission_validation(PERMISSION_PLAYER_LINE_ID) == TRUE){

						

						}else{

							$objPHPExcel->getActiveSheet()->removeColumn('K');

						}

					}else{

						$objPHPExcel->getActiveSheet()->removeColumn('J');

						if(permission_validation(PERMISSION_PLAYER_LINE_ID) == TRUE){

						

						}else{

							$objPHPExcel->getActiveSheet()->removeColumn('J');

						}

					}

				}else{

					$objPHPExcel->getActiveSheet()->removeColumn('H');

					if(permission_validation(PERMISSION_PLAYER_MOBILE) == TRUE){

						if(permission_validation(PERMISSION_PLAYER_LINE_ID) == TRUE){

						

						}else{

							$objPHPExcel->getActiveSheet()->removeColumn('J');

						}

					}else{

						$objPHPExcel->getActiveSheet()->removeColumn('I');

						if(permission_validation(PERMISSION_PLAYER_LINE_ID) == TRUE){

						

						}else{

							$objPHPExcel->getActiveSheet()->removeColumn('I');

						}

					}

				}



				$input_array = array(

					1 => "A",

					2 => "B",

					3 => "C",

					4 => "D",

					5 => "E",

					6 => "F",

					7 => "G",

					8 => "H",

					9 => "I",

					10 => "J",

					11 => "K",

					12 => "L",

					13 => "M",

					14 => "N",

					15 => "O",

					16 => "P",

				);



				$index = 0;

        		$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_no'));

        		$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_player_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_ranking'));

		        $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_tag_code'));

		        $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_tag_player'));

		        $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_upline'));

		        $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_main_wallet'));

		        $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_account_status'));

		        $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_bank_channel_status'));

		        if(permission_validation(PERMISSION_PLAYER_ACCOUNT_NAME) == TRUE){

		        	$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_bank_account_name'));

		       	}

		        $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_bank_name'));

		        if(permission_validation(PERMISSION_PLAYER_MOBILE) == TRUE){

		        	$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_mobile'));

		       	}

		        if(permission_validation(PERMISSION_PLAYER_LINE_ID) == TRUE){

		        	$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('im_line'));

		        }

		        $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_bank_group'));

		        $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_registered_date')." / ".$this->lang->line('label_ip'));

		        $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_last_login_date')." / ".$this->lang->line('label_ip'));

		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);

		         // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);

		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setSize(12);



				$rowCount = 2;

				$result_count = 1;



				$styleArrayRed = get_excel_color_status(EXPORT_COLOR_RED);

				$styleArrayBlue = get_excel_color_status(EXPORT_COLOR_BLUE);

				$styleArrayBlack = get_excel_color_status(EXPORT_COLOR_BLACK);



				$data = array();

				if(!empty($posts))

				{

					foreach ($posts as $post)

					{

						$index = 0;



						$level = "";

						$level = $post->level_id-1;



						$bank_show_name = "";

						for($i=0;$i<sizeof($bank_group_list);$i++)

						{

							if(isset($bank_group_list)) 

							{

								$arr = explode(',',  $post->bank_group_id);

								$arr = array_values(array_filter($arr));

								if(in_array($bank_group_list[$i]['group_id'], $arr)) 

								{

									if(empty($bank_show_name)){

										$bank_show_name .= $bank_group_list[$i]['group_name'];

									}else{

										$bank_show_name .= " , ".$bank_group_list[$i]['group_name'];

									}

								}

							}

						}



						$bank_channel = "";

						if($post->is_offline_deposit){

							if(empty($bank_channel)){

								$bank_channel .= $this->lang->line('deposit_offline_banking');

							}else{

								$bank_channel .= " , ".$this->lang->line('deposit_offline_banking');

							}

						}



						if($post->is_online_deposit){

							if(empty($bank_channel)){

								$bank_channel .= $this->lang->line('deposit_online_banking');

							}else{

								$bank_channel .= " , ".$this->lang->line('deposit_online_banking');

							}

						}



						if($post->is_credit_card_deposit){

							if(empty($bank_channel)){

								$bank_channel .= $this->lang->line('deposit_credit_card');

							}else{

								$bank_channel .= " , ".$this->lang->line('deposit_credit_card');

							}

						}



						if($post->is_hypermart_deposit){

							if(empty($bank_channel)){

								$bank_channel .= $this->lang->line('deposit_hypermart');

							}else{

								$bank_channel .= " , ".$this->lang->line('deposit_hypermart');

							}

						}



						$player_bank_account = "";

						if(!empty($player_bank_list[$post->player_id]['bank_account'])){

							foreach($player_bank_list[$post->player_id]['bank_account'] as $bank_account_row){

								if(!empty($player_bank_account)){

									$player_bank_account .= "\r\r";

								}

								if(isset($bank_list[$bank_account_row['bank_id']])){

									$player_bank_account .= $bank_list[$bank_account_row['bank_id']]."\r";

								}

								$player_bank_account .= $bank_account_row['bank_account_name']."\r";

								$player_bank_account .= $bank_account_row['bank_account_no'];

							}

						}

						

						$register_info = "";

						$login_info = "";



						$register_info .= (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date)."\r" : '');

						$register_info .= ((!empty($post->created_ip)) ? $post->created_ip."\r" : '');



						$login_info .= (($post->last_login_date > 0) ? date('Y-m-d H:i:s', $post->last_login_date)."\r" : '');

						$login_info .= ((!empty($post->last_login_ip)) ? $post->last_login_ip."\r" : '');



						$tag = "";

						if(isset($tag_list[$post->tag_id])){

							$tag = $tag_list[$post->tag_id]['tag_code'];						

						}



						$tags_option = "";

						if(!empty($post->tag_ids)){

							$tags_array = array_values(array_filter(explode(',',  $post->tag_ids)));

							foreach($tags_array as $tags_row){

								if(isset($tag_player_list[$tags_row])){

									if(empty($tags_option)){

										$tags_option .= $tag_player_list[$tags_row]['tag_player_code'];

									}else{

										$tags_option .= " , ".$tag_player_list[$tags_row]['tag_player_code'];

									}

								}

							}

						}



						$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index]. $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit($input_array[++$index]. $rowCount, $post->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index]. $rowCount, $level);

						$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index]. $rowCount, $tag);

						$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index]. $rowCount, $tags_option);

						$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index]. $rowCount, $post->upline);

						$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index]. $rowCount, number_format($post->points, 0, '.', ','));

						if($post->points > 0){

							$objPHPExcel->getActiveSheet()->getStyle($input_array[$index].$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle($input_array[$index].$rowCount)->applyFromArray($styleArrayBlack);

						}

						$index++;

						switch($post->active)

						{

							case STATUS_ACTIVE: $objPHPExcel->getActiveSheet()->SetCellValue($input_array[$index].$rowCount, $this->lang->line('status_active')); break;

							default: $objPHPExcel->getActiveSheet()->SetCellValue($input_array[$index].$rowCount, $this->lang->line('status_suspend')); break;

						}

						$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].$rowCount, $bank_show_name);

						if(permission_validation(PERMISSION_PLAYER_ACCOUNT_NAME) == TRUE){

							$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].$rowCount, $post->bank_account_name);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].$rowCount, $player_bank_account);

						if(permission_validation(PERMISSION_PLAYER_MOBILE) == TRUE){

							$objPHPExcel->getActiveSheet()->setCellValueExplicit($input_array[++$index].$rowCount, $post->mobile,PHPExcel_Cell_DataType::TYPE_STRING);

						}

						if(permission_validation(PERMISSION_PLAYER_LINE_ID) == TRUE){

							$objPHPExcel->getActiveSheet()->setCellValueExplicit($input_array[++$index].$rowCount, $post->line_id,PHPExcel_Cell_DataType::TYPE_STRING);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].$rowCount, $bank_channel);

						$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].$rowCount, $register_info);

						$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].$rowCount, $login_info);

						



						$rowCount++;

           				$result_count++;

					}

				}

				

				$rowCount++;



				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}



	public function player_promotion_list_export_check(){

		//Initial output data

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_LIST_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_DEPOSIT_VIEW) == TRUE)

		{

			$arr = $this->session->userdata('searches_player_promotion');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}



	public function player_promotion_list_export(){

		if(permission_validation(PERMISSION_PLAYER_PROMOTION_LIST_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_DEPOSIT_VIEW) == TRUE)

		{

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$arr = $this->session->userdata('searches_player_promotion');

			if(!empty($arr)){

				$columns = array( 

					'a.player_promotion_id',

					'a.created_date',

					'b.username',

					'a.promotion_name',

					'a.deposit_amount',

					'a.promotion_amount',

					'a.current_amount',

					'a.achieve_amount',

					'a.reward_amount',

					'a.is_reward',

					'a.reward_date',

					'a.status',

					'a.remark',

					'a.starting_date',

					'a.complete_date',

					'a.updated_by',

					'a.updated_date',

					'a.calculate_session',

				);

								

				$sum_columns = array( 

					0 => 'SUM(a.reward_amount) AS total_reward',

				);



				$col = 0;		

				

				$where = '';	

				if( ! empty($arr['agent']))

				{

					$where = "AND a.player_id = 'ABC'";

					$agent = $this->user_model->get_user_data_by_username($arr['agent']);

					if(!empty($agent)){

						$response_upline = $this->user_model->get_downline_data($agent['username']);

						if(!empty($response_upline)){

							$where = "AND b.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";

						}

					}

				}else{

					$where = "AND b.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";

				}



				if(isset($arr['from_date']))

				{

					if( ! empty($arr['from_date'])){

						$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);

					}

				}



				if(isset($arr['to_date']))

				{

					if( ! empty($arr['from_date'])){

						$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);

					}

				}



				if( ! empty($arr['username']))

				{

					$where .= " AND b.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	

				}



				if($arr['status'] !==""){

					if($arr['status'] == STATUS_PENDING OR $arr['status'] == STATUS_SATTLEMENT OR $arr['status'] == STATUS_CANCEL OR $arr['status'] == STATUS_ENTITLEMENT OR $arr['status'] == STATUS_VOID OR $arr['status'] == STATUS_ACCOMPLISH)

					{

						$where .= ' AND a.status = ' . $arr['status'];

					}

				}

				if( ! empty($arr['promotion']))

				{

					$where .= " AND a.promotion_name LIKE '%" . $arr['promotion'] . "%' ESCAPE '!'";	

				}



				if( ! empty($arr['player_promotion_id']))

				{

					$where .= ' AND a.player_promotion_id = ' . $arr['player_promotion_id'];	

				}



				if(isset($arr['is_reward'])){

					if($arr['is_reward'] == STATUS_PENDING OR $arr['is_reward'] == STATUS_APPROVE)

					{

						$where .= ' AND a.is_reward = ' . $arr['is_reward'];

					}

				}

				$select = implode(',', $columns);

				$dbprefix = $this->db->dbprefix;

				

				$posts = NULL;

				$query_string = "(SELECT {$select} FROM {$dbprefix}player_promotion a, {$dbprefix}players b WHERE (a.player_id = b.player_id) $where)";

				$query_string_2 = " ORDER by a.created_date DESC";

				$query = $this->db->query($query_string . $query_string_2);

				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}

				

				$query->free_result();

				

				//Get total sum up

				$sum_select = implode(',', $sum_columns);

				$total_data = array(

					'total_reward' => 0,

				);

				

				$query->free_result();



				$fileName = $this->lang->line('title_player_promotion').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_player_promotion'));

        		// set Header

        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

        		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_date'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_promotion'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_deposit_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_promotion_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_current_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_archieve_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_reward_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('J1', $this->lang->line('label_is_reward'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('K1', $this->lang->line('label_reward_date'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('L1', $this->lang->line('label_status'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('M1', $this->lang->line('label_remark'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('N1', $this->lang->line('label_starting_date'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('O1', $this->lang->line('label_complete_date'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('P1', $this->lang->line('label_updated_by'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('Q1', $this->lang->line('label_updated_date'));



		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setBold(true);



		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setSize(12);

				$rowCount = 2;

				$result_count = 1;



				$styleArrayRed = get_excel_color_status(EXPORT_COLOR_RED);

				$styleArrayBlue = get_excel_color_status(EXPORT_COLOR_BLUE);

				$styleArrayBlack = get_excel_color_status(EXPORT_COLOR_BLACK);

				//Prepare data

				$data = array();

				if(!empty($posts))

				{

					foreach ($posts as $post)

					{

						$total_data['total_reward'] += $post->reward_amount;

						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, (($post->created_date > 0) ? date('Y-m-d H:i:s', $post->created_date) : '-'));

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $rowCount, $post->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $post->promotion_name);

						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, number_format($post->deposit_amount, 0, '.', ','));

						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($post->promotion_amount, 0, '.', ','));

						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, number_format($post->current_amount, 0, '.', ','));

						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, number_format($post->achieve_amount, 0, '.', ','));

						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, number_format($post->reward_amount, 0, '.', ','));

						switch($post->is_reward)

						{

							case STATUS_APPROVE: $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $this->lang->line('status_approved')); break;

							default: $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $this->lang->line('status_pending')); break;

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, (($post->reward_date > 0) ? date('Y-m-d H:i:s', $post->reward_date) : '-'));

						switch($post->status)

						{

							case STATUS_SATTLEMENT: $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $this->lang->line('status_sattlement')); break;

							case STATUS_CANCEL: $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $this->lang->line('status_cancelled')); break;

							case STATUS_ENTITLEMENT: $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $this->lang->line('status_entitlement')); break;

							case STATUS_VOID: $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $this->lang->line('status_void')); break;

							case STATUS_ACCOMPLISH: $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $this->lang->line('status_accomplish')); break;

							default: $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $this->lang->line('status_pending')); break;

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $post->remark);

						$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, (($post->starting_date > 0) ? date('Y-m-d H:i:s', $post->starting_date) : '-'));

						$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, (($post->complete_date > 0) ? date('Y-m-d H:i:s', $post->complete_date) : '-'));

						$objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, (( ! empty($post->updated_by)) ? $post->updated_by : '-'));

						$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, (($post->updated_date > 0) ? date('Y-m-d H:i:s', $post->updated_date) : '-'));

						$rowCount++;

           				$result_count++;

					}

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line('label_total'));

				$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, number_format($total_data['total_reward'], 0, '.', ','));

				$rowCount++;



				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('P1:P'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('Q1:Q'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}



	public function winloss_report_export_check(){

		//Initial output data

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_WIN_LOSS_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_winloss_sum');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();	

	}



	public function winloss_report_export($num = NULL, $username = NULL){

		if(permission_validation(PERMISSION_WIN_LOSS_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_winloss_sum');

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$order = $this->input->post("order", TRUE);



			$dbprefix = $this->db->dbprefix;

			$data = array();



			$where_total_all = "";

			$where_total_bet_count = "";

			$where_total_bet_amount = "";

			$where_total_win_loss = "";

			$where_total_rolling_amount = "";

			$where_total_deposit = "";

			$where_total_deposit_offline = "";

			$where_total_deposit_online = "";

			$where_total_deposit_point = "";

			$where_total_withdrawal = "";

			$where_total_withdrawal_offline = "";

			$where_total_withdrawal_online = "";

			$where_total_withdrawal_point = "";

			$where_total_promotion = "";

			$where_total_adjust = "";

			$where_total_adjust_in = "";

			$where_total_adjust_out = "";

			$where_total_bonus = "";



			$upline_query_string = "SELECT MU.*";



			$where_total_bet_count .= "AP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_bet_count .= " AND ATR.player_id = AP.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_bet_count .= ' AND ATR.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_bet_count .= ' AND ATR.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(ATR.total_bet) AS total_bet FROM {$dbprefix}total_win_loss_report ATR, {$dbprefix}players AP where $where_total_bet_count ) AS total_bet ";



			//Total Bet Amount

			$where_total_bet_amount .= "BP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_bet_amount .= " AND BTR.player_id = BP.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_bet_amount .= ' AND BTR.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_bet_amount .= ' AND BTR.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(BTR.bet_amount) AS total_bet_amount FROM {$dbprefix}total_win_loss_report BTR, {$dbprefix}players BP where $where_total_bet_amount ) AS total_bet_amount ";





			//Total Rolling Amount

			$where_total_rolling_amount .= "DP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_rolling_amount .= " AND DTR.player_id = DP.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_rolling_amount .= ' AND DTR.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_rolling_amount .= ' AND DTR.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(DTR.bet_amount_valid) AS total_rolling_amount FROM {$dbprefix}total_win_loss_report DTR, {$dbprefix}players DP where $where_total_rolling_amount ) AS total_rolling_amount ";





			//Total Win Loss

			$where_total_win_loss .= "CP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_win_loss .= " AND CTR.player_id = CP.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_win_loss .= ' AND CTR.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_win_loss .= ' AND CTR.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(CTR.win_loss) AS total_win_loss FROM {$dbprefix}total_win_loss_report CTR, {$dbprefix}players CP where $where_total_win_loss ) AS total_win_loss ";





			//Total Deposit

			$where_total_deposit .= "EP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_deposit .= " AND ETR.player_id = EP.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_deposit .= ' AND ETR.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_deposit .= ' AND ETR.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(ETR.deposit_amount) AS total_deposit FROM {$dbprefix}total_win_loss_report ETR, {$dbprefix}players EP where $where_total_deposit ) AS total_deposit ";





			//Total Deposit offline

			$where_total_deposit_offline .= "EPF.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_deposit_offline .= " AND ETRF.player_id = EPF.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_deposit_offline .= ' AND ETRF.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_deposit_offline .= ' AND ETRF.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(ETRF.deposit_offline_amount) AS total_deposit_offline FROM {$dbprefix}total_win_loss_report ETRF, {$dbprefix}players EPF where $where_total_deposit_offline ) AS total_deposit_offline ";





			//Total Deposit online

			$where_total_deposit_online .= "EPN.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_deposit_online .= " AND ETRN.player_id = EPN.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_deposit_online .= ' AND ETRN.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_deposit_online .= ' AND ETRN.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(ETRN.deposit_online_amount) AS total_deposit_online FROM {$dbprefix}total_win_loss_report ETRN, {$dbprefix}players EPN where $where_total_deposit_online ) AS total_deposit_online ";



			$where_total_deposit_point .= "EPP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_deposit_point .= " AND ETRP.player_id = EPP.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_deposit_point .= ' AND ETRP.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_deposit_point .= ' AND ETRP.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(ETRP.deposit_point_amount) AS total_deposit_point FROM {$dbprefix}total_win_loss_report ETRP, {$dbprefix}players EPP where $where_total_deposit_point ) AS total_deposit_point ";



			//Total Withdrawal

			$where_total_withdrawal .= "FP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_withdrawal .= " AND FTR.player_id = FP.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_withdrawal .= ' AND FTR.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_withdrawal .= ' AND FTR.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(FTR.withdrawals_amount) AS total_withdrawal FROM {$dbprefix}total_win_loss_report FTR, {$dbprefix}players FP where $where_total_withdrawal ) AS total_withdrawal ";



			//Total Withdrawal offline

			$where_total_withdrawal_offline .= "FPF.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_withdrawal_offline .= " AND FTRF.player_id = FPF.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_withdrawal_offline .= ' AND FTRF.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_withdrawal_offline .= ' AND FTRF.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(FTRF.withdrawals_offline_amount) AS total_withdrawal_offline FROM {$dbprefix}total_win_loss_report FTRF, {$dbprefix}players FPF where $where_total_withdrawal_offline ) AS total_withdrawal_offline ";



			//Total Withdrawal online

			$where_total_withdrawal_online .= "FPN.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_withdrawal_online .= " AND FTRN.player_id = FPN.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_withdrawal_online .= ' AND FTRN.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_withdrawal_online .= ' AND FTRN.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(FTRN.withdrawals_online_amount) AS total_withdrawal_online FROM {$dbprefix}total_win_loss_report FTRN, {$dbprefix}players FPN where $where_total_withdrawal_online ) AS total_withdrawal_online ";



			//Total Withdrawal point

			$where_total_withdrawal_point .= "FPP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_withdrawal_point .= " AND FTRP.player_id = FPP.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_withdrawal_point .= ' AND FTRP.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_withdrawal_point .= ' AND FTRP.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(FTRP.withdrawals_point_amount) AS total_withdrawal_point FROM {$dbprefix}total_win_loss_report FTRP, {$dbprefix}players FPP where $where_total_withdrawal_point ) AS total_withdrawal_point ";



			//Total Promotion

			$where_total_promotion .= "GP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_promotion .= " AND GTR.player_id = GP.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_promotion .= ' AND GTR.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_promotion .= ' AND GTR.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(GTR.promotion_amount) AS total_promotion FROM {$dbprefix}total_win_loss_report GTR, {$dbprefix}players GP where $where_total_promotion ) AS total_promotion ";





			//Total Bomnus

			$where_total_bonus .= "HP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_bonus .= " AND HTR.player_id = HP.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_bonus .= ' AND HTR.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_bonus .= ' AND HTR.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(HTR.bonus_amount) AS total_bonus FROM {$dbprefix}total_win_loss_report HTR, {$dbprefix}players HP where $where_total_bonus ) AS total_bonus ";





			//Total Adjust

			$where_total_adjust .= "IP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_adjust .= " AND ITR.player_id = IP.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_adjust .= ' AND ITR.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_adjust .= ' AND ITR.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(ITR.adjust_amount) AS total_adjust FROM {$dbprefix}total_win_loss_report ITR, {$dbprefix}players IP where $where_total_adjust ) AS total_adjust ";



			//Total Adjust In

			$where_total_adjust_in .= "IPI.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_adjust_in .= " AND ITRI.player_id = IPI.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_adjust_in .= ' AND ITRI.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_adjust_in .= ' AND ITRI.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(ITRI.adjust_in_amount) AS total_adjust_in FROM {$dbprefix}total_win_loss_report ITRI, {$dbprefix}players IPI where $where_total_adjust_in ) AS total_adjust_in ";



			//Total Adjust Out

			$where_total_adjust_out .= "IPO.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

			$where_total_adjust_out .= " AND ITRO.player_id = IPO.player_id";

			if( ! empty($arr['from_date']))

			{

				$where_total_adjust_out .= ' AND ITRO.report_date >= ' . strtotime($arr['from_date']);

			}

			

			if( ! empty($arr['to_date']))

			{

				$where_total_adjust_out .= ' AND ITRO.report_date <= ' . strtotime($arr['to_date']);

			}

			$upline_query_string .= ",(SELECT SUM(ITRO.adjust_out_amount) AS total_adjust_out FROM {$dbprefix}total_win_loss_report ITRO, {$dbprefix}players IPO where $where_total_adjust_out ) AS total_adjust_out ";



			$upline_query_string .= "FROM bctp_users MU ";

			if(empty($username))

			{

				$num = 1;

				$upline_query_string .= "WHERE MU.user_id = " . $this->session->userdata('root_user_id') . " LIMIT 1";

				$totalFiltered = 1;

			}

			else

			{

				$extract_string = "";

				if(isset($arr['excludezero']) && $arr['excludezero'] == "true"){

					$extract_string = "HAVING total_bet > 0 OR total_deposit > 0 OR total_withdrawal > 0 OR total_promotion > 0 OR total_bonus > 0 OR total_adjust_in > 0 OR total_adjust_out > 0";

				}

				$upline_query_total_string = $upline_query_string;

				$upline_query_total_string .= "WHERE MU.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' AND MU.upline = '{$username}' GROUP BY MU.user_id $extract_string";

				$upline_total_query = $this->db->query($upline_query_total_string);

				$totalFiltered = $upline_total_query->num_rows();

				$upline_total_query->free_result();

				$upline_query_string .= "WHERE MU.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' AND MU.upline = '{$username}' GROUP BY MU.user_id $extract_string";

			}

			



			$upline_query = $this->db->query($upline_query_string);



			$fileName = $this->lang->line('title_win_loss_report').' - '.date("Y-m-d", time())." ".time().'.xlsx';

			$objPHPExcel = new PHPExcel();

    		$objPHPExcel->setActiveSheetIndex(0);

    		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_win_loss_report'));



    		// set Header

    		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_level'));

    		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_username'));

    		$objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_agent'));

	       	$objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_deposit_offline'));

	       	$objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_deposit_online'));

	       	$objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_deposit_point'));

	        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_withdrawal_offline'));

	        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_withdrawal_point'));

	        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_adjust_in'));

	        $objPHPExcel->getActiveSheet()->SetCellValue('J1', $this->lang->line('label_adjust_out'));

	        $objPHPExcel->getActiveSheet()->SetCellValue('K1', $this->lang->line('label_bet_amount'));

	        $objPHPExcel->getActiveSheet()->SetCellValue('L1', $this->lang->line('label_rolling_amount'));

	        $objPHPExcel->getActiveSheet()->SetCellValue('M1', $this->lang->line('label_total_win_loss'));

			$objPHPExcel->getActiveSheet()->SetCellValue('N1', $this->lang->line('label_total_promotion_amount'));

			$objPHPExcel->getActiveSheet()->SetCellValue('O1', $this->lang->line('label_agent_possess'));

			$objPHPExcel->getActiveSheet()->SetCellValue('P1', $this->lang->line('label_possess_win_loss'));

			$objPHPExcel->getActiveSheet()->SetCellValue('Q1', $this->lang->line('label_possess_promotion'));

			$objPHPExcel->getActiveSheet()->SetCellValue('R1', $this->lang->line('label_profit'));

			$objPHPExcel->getActiveSheet()->SetCellValue('S1', $this->lang->line('label_rolling_amount')."(".$this->lang->line('game_type_lc').")");

			$objPHPExcel->getActiveSheet()->SetCellValue('T1', $this->lang->line('label_win_loss')."(".$this->lang->line('game_type_lc').")");

			$objPHPExcel->getActiveSheet()->SetCellValue('U1', $this->lang->line('label_comission_rate')."(".$this->lang->line('game_type_lc').")");

			$objPHPExcel->getActiveSheet()->SetCellValue('V1', $this->lang->line('label_comission')."(".$this->lang->line('game_type_lc').")");

			$objPHPExcel->getActiveSheet()->SetCellValue('W1', $this->lang->line('label_rolling_amount')."(".$this->lang->line('game_type_sl').")");

			$objPHPExcel->getActiveSheet()->SetCellValue('X1', $this->lang->line('label_win_loss')."(".$this->lang->line('game_type_sl').")");

			$objPHPExcel->getActiveSheet()->SetCellValue('Y1', $this->lang->line('label_comission_rate')."(".$this->lang->line('game_type_sl').")");

			$objPHPExcel->getActiveSheet()->SetCellValue('Z1', $this->lang->line('label_comission')."(".$this->lang->line('game_type_sl').")");

			$objPHPExcel->getActiveSheet()->SetCellValue('AA1', $this->lang->line('label_rolling_amount')."(".$this->lang->line('game_type_sb').")");

			$objPHPExcel->getActiveSheet()->SetCellValue('AB1', $this->lang->line('label_win_loss')."(".$this->lang->line('game_type_sb').")");

			$objPHPExcel->getActiveSheet()->SetCellValue('AC1', $this->lang->line('label_comission_rate')."(".$this->lang->line('game_type_sb').")");

			$objPHPExcel->getActiveSheet()->SetCellValue('AD1', $this->lang->line('label_comission')."(".$this->lang->line('game_type_sb').")");

			$objPHPExcel->getActiveSheet()->SetCellValue('AE1', $this->lang->line('label_rolling_amount')."(".$this->lang->line('game_type_ot').")");

			$objPHPExcel->getActiveSheet()->SetCellValue('AF1', $this->lang->line('label_win_loss')."(".$this->lang->line('game_type_ot').")");

			$objPHPExcel->getActiveSheet()->SetCellValue('AG1', $this->lang->line('label_comission_rate')."(".$this->lang->line('game_type_ot').")");

			$objPHPExcel->getActiveSheet()->SetCellValue('AH1', $this->lang->line('label_comission')."(".$this->lang->line('game_type_ot').")");





	        //set cell width

	        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(25);

	        $objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(25);



	        // Set fonts style

	        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('R1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('S1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('T1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('U1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('V1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('W1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('X1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('Y1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('Z1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('AA1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('AB1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('AC1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('AD1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('AE1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('AF1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('AG1')->getFont()->setBold(true);

	        $objPHPExcel->getActiveSheet()->getStyle('AH1')->getFont()->setBold(true);

	        // Set fonts size

	        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('R1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('S1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('T1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('U1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('V1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('W1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('X1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('Y1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('Z1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('AA1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('AB1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('AC1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('AD1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('AE1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('AF1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('AG1')->getFont()->setSize(12);

			$objPHPExcel->getActiveSheet()->getStyle('AH1')->getFont()->setSize(12);



			$objPHPExcel->getActiveSheet()->getStyle('A1:AH1')->applyFromArray(array('font'  => array('color' => array('rgb' => 'FFFFFF'),)));





			$rowCount = 2;

			$result_count = 1;



			$styleArrayRed = get_excel_color_status(EXPORT_COLOR_RED);

			$styleArrayBlue = get_excel_color_status(EXPORT_COLOR_BLUE);

			$styleArrayBlack = get_excel_color_status(EXPORT_COLOR_BLACK);

			//Prepare data

			$data = array();





			$total_data = array(

				"total_deposit" => 0,

				"total_deposit_online" => 0,

				"total_deposit_offline" => 0,

				"total_deposit_point" => 0,

				"total_withdrawal" => 0,

				"total_withdrawal_online" => 0,

				"total_withdrawal_offline" => 0,

				"total_withdrawal_point" => 0,

				"total_adjust" => 0,

				"total_adjust_in" => 0,

				"total_adjust_out" => 0,

				'total_bet' => 0,

				'total_bet_amount' => 0,

				'total_win_loss' => 0,

				'total_rolling_amount' => 0,

				'total_promotion' => 0,

				'total_bonus' => 0,

				'total_possess_win_loss' => 0,

				'total_possess_promotion' => 0,

				'total_possess_bonus' => 0,

				'total_rolling_commission' => 0,

				'total_profit' => 0,

				'total_rolling_amount_live_casino' => 0,

				'total_win_loss_live_casino' => 0,

				'total_rolling_comission_live_casino' => 0,

				'total_rolling_amount_slot' => 0,

				'total_win_loss_slot' => 0,

				'total_rolling_comission_slot' => 0,

				'total_rolling_amount_sportbook' => 0,

				'total_win_loss_sportbook' => 0,

				'total_rolling_comission_sportbook' => 0,

				'total_rolling_amount_other' => 0,

				'total_win_loss_other' => 0,

				'total_rolling_comission_other' => 0,

				'total_downline' => 0,

			);

			if($upline_query->num_rows() > 0)

			{

				foreach($upline_query->result() as $upline_row)

				{

					//deposit group

					$deposit = $upline_row->total_deposit;

					$deposit_offline = $upline_row->total_deposit_offline;

					$deposit_online = $upline_row->total_deposit_online;

					$deposit_point = $upline_row->total_deposit_point;



					//withdrawal group

					$withdrawal = $upline_row->total_withdrawal;

					$withdrawal_offline = $upline_row->total_withdrawal_offline;

					$withdrawal_online = $upline_row->total_withdrawal_online;

					$withdrawal_point = $upline_row->total_withdrawal_point;

						

					//adjust

					$adjust = $upline_row->total_adjust;

					$adjust_in = $upline_row->total_adjust_in;

					$adjust_out = $upline_row->total_adjust_out;



					//promotion amount

					$promotion = $upline_row->total_promotion;

					$bonus = $upline_row->total_bonus;

					



					//wager

					$total_win_loss = 0;//$upline_row->total_win_loss;

					$total_bet = 0;//$upline_row->total_bet;

					$total_bet_amount = 0;//$upline_row->total_bet_amount;

					$total_rolling_amount = 0;//$upline_row->total_rolling_amount;



					$comm_arr = array(

						GAME_SPORTSBOOK => array(

							'total_bet' => 0,

							'total_bet_amount' => 0,

							'total_win_loss' => 0,

							'total_rolling_amount' => 0,

						),

						GAME_LIVE_CASINO => array(

							'total_bet' => 0,

							'total_bet_amount' => 0,

							'total_win_loss' => 0,

							'total_rolling_amount' => 0,

						),

						GAME_SLOTS => array(

							'total_bet' => 0,

							'total_bet_amount' => 0,

							'total_win_loss' => 0,

							'total_rolling_amount' => 0,

						),

						GAME_OTHERS => array(

							'total_bet' => 0,

							'total_bet_amount' => 0,

							'total_win_loss' => 0,

							'total_rolling_amount' => 0,

						)

					);

								

					//Get win loss

					$where = '';

				

					if( ! empty($arr['from_date']))

					{

						$where .= ' AND a.report_date >= ' . strtotime($arr['from_date']);

					}

					

					if( ! empty($arr['to_date']))

					{

						$where .= ' AND a.report_date <= ' . strtotime($arr['to_date']);

					}



					if(!empty($arr['excludeProviderCheckboxes'])){

						$excludeProviderCheckboxes = '"'.implode('","', $arr['excludeProviderCheckboxes']).'"';

						$where .= " AND a.game_provider_code NOT IN(" . $excludeProviderCheckboxes . ")";

					}



					if(!empty($arr['excludeGametypeCheckboxes'])){

						$excludeGametypeCheckboxes = '"'.implode('","', $arr['excludeGametypeCheckboxes']).'"';

						$where .= " AND a.game_type_code NOT IN(" . $excludeGametypeCheckboxes . ")";

					}

					

					$select = "a.game_type_code, SUM(a.total_bet) AS total_bet, SUM(a.bet_amount) AS total_bet_amount, SUM(a.win_loss) AS total_win_loss, SUM(a.bet_amount_valid) AS total_rolling_amount";			

					$wl_query_string = "SELECT {$select} FROM {$dbprefix}win_loss_report a, {$dbprefix}players b WHERE (a.player_id = b.player_id) AND b.upline_ids LIKE '%," . $upline_row->user_id . ",%' ESCAPE '!' $where GROUP BY a.game_type_code";

					$wl_query = $this->db->query($wl_query_string);

					if($wl_query->num_rows() > 0)

					{

						foreach($wl_query->result() as $wl_row)

						{

							$game_type_code = GAME_OTHERS;

							

							if($wl_row->game_type_code == GAME_SPORTSBOOK OR $wl_row->game_type_code == GAME_LIVE_CASINO OR $wl_row->game_type_code == GAME_SLOTS)

							{

								$game_type_code = $wl_row->game_type_code;

							}



							$total_win_loss += $wl_row->total_win_loss;

							$total_bet += $wl_row->total_bet;

							$total_bet_amount += $wl_row->total_bet_amount;

							$total_rolling_amount += $wl_row->total_rolling_amount;

							

							$comm_arr[$game_type_code]['total_bet'] = ($comm_arr[$game_type_code]['total_bet'] + $wl_row->total_bet);

							$comm_arr[$game_type_code]['total_bet_amount'] = ($comm_arr[$game_type_code]['total_bet_amount'] + $wl_row->total_bet_amount);

							$comm_arr[$game_type_code]['total_win_loss'] = ($comm_arr[$game_type_code]['total_win_loss'] + $wl_row->total_win_loss);

							$comm_arr[$game_type_code]['total_rolling_amount'] = ($comm_arr[$game_type_code]['total_rolling_amount'] + $wl_row->total_rolling_amount);

						}

					}



					$wl_query->free_result();					

					//Get total

					/*

					$casino_comm = (($comm_arr[GAME_LIVE_CASINO]['total_rolling_amount'] * $upline_row->casino_comm) / 100);

					$slots_comm = (($comm_arr[GAME_SLOTS]['total_rolling_amount'] * $upline_row->slots_comm) / 100);

					$sport_comm = (($comm_arr[GAME_SPORTSBOOK]['total_rolling_amount'] * $upline_row->sport_comm) / 100);

					$other_comm = (($comm_arr[GAME_OTHERS]['total_rolling_amount'] * $upline_row->other_comm) / 100);

					$rolling_commission = ($casino_comm + $slots_comm + $sport_comm + $other_comm);

					

					$possess_win_loss = (($total_win_loss * $upline_row->possess) / 100);

					$possess_promotion = (($promotion * $upline_row->possess) / 100);

					$possess_bonus = (($bonus * $upline_row->possess) / 100);

					$profit = (($possess_win_loss * -1) - $rolling_commission - $possess_promotion - $possess_bonus);

					*/

					$possess_win_loss = (($total_win_loss * $upline_row->possess) / 100);

					$possess_promotion = (($promotion * $upline_row->possess) / 100);

					$possess_bonus = (($bonus * $upline_row->possess) / 100);

					$possess_comission = 0;

					$possess_profit = ($possess_win_loss*-1) - $possess_promotion - $possess_bonus - $possess_comission;



					$casino_comm_rate = 0;

					$slots_comm_rate = 0;

					$sport_comm_rate = 0;

					$other_comm_rate = 0;



					$casino_comm = 0;

					$slots_comm = 0;

					$sport_comm = 0;

					$other_comm = 0;		



					//Prepare data

					//deposit

					$total_data['total_deposit'] += bcdiv($deposit,1,2);

					$total_data['total_deposit_offline'] += bcdiv($deposit_offline,1,2);

					$total_data['total_deposit_online'] += bcdiv($deposit_online,1,2);

					$total_data['total_deposit_point'] += bcdiv($deposit_point,1,2);



					//withdrawal

					$total_data['total_withdrawal'] += bcdiv($withdrawal,1,2);

					$total_data['total_withdrawal_offline'] += bcdiv($withdrawal_offline,1,2);

					$total_data['total_withdrawal_online'] += bcdiv($withdrawal_online,1,2);

					$total_data['total_withdrawal_point'] += bcdiv($withdrawal_point,1,2);



					//adjust

					$total_data['total_adjust'] += bcdiv($adjust,1,2);

					$total_data['total_adjust_in'] += bcdiv($adjust_in,1,2);

					$total_data['total_adjust_out'] += bcdiv($adjust_out,1,2);



					//promotion

					$total_data['total_promotion'] += bcdiv($promotion,1,2);

					$total_data['total_bonus'] += bcdiv($bonus,1,2);



					//wager

					$total_data['total_bet'] += bcdiv($total_bet,1,0);

					$total_data['total_bet_amount'] += bcdiv($total_bet_amount,1,2);

					$total_data['total_win_loss'] += bcdiv($total_win_loss,1,2);

					$total_data['total_rolling_amount'] += bcdiv($total_rolling_amount,1,2);



					//possess

					$total_data['total_possess_win_loss'] += bcdiv($possess_win_loss,1,2);

					$total_data['total_possess_promotion'] += bcdiv($possess_promotion,1,2);

					$total_data['total_possess_bonus'] += bcdiv($possess_bonus,1,2);

					$total_data['total_rolling_commission'] += bcdiv($possess_comission,1,2);

					$total_data['total_profit'] += bcdiv($possess_profit,1,2);



					//game type possess

					$total_data['total_rolling_amount_live_casino'] += bcdiv($comm_arr[GAME_LIVE_CASINO]['total_rolling_amount'],1,2);

					$total_data['total_win_loss_live_casino'] += bcdiv($comm_arr[GAME_LIVE_CASINO]['total_win_loss'],1,2);

					$total_data['total_rolling_comission_live_casino'] += bcdiv($casino_comm,1,2);



					$total_data['total_rolling_amount_slot'] += bcdiv($comm_arr[GAME_SLOTS]['total_rolling_amount'],1,2);

					$total_data['total_win_loss_slot'] += bcdiv($comm_arr[GAME_SLOTS]['total_win_loss'],1,2);

					$total_data['total_rolling_comission_slot'] += bcdiv($slots_comm,1,2);



					$total_data['total_rolling_amount_sportbook'] += bcdiv($comm_arr[GAME_SPORTSBOOK]['total_rolling_amount'],1,2);

					$total_data['total_win_loss_sportbook'] += bcdiv($comm_arr[GAME_SPORTSBOOK]['total_win_loss'],1,2);

					$total_data['total_rolling_comission_sportbook'] += bcdiv($sport_comm,1,2);



					$total_data['total_rolling_amount_other'] += bcdiv($comm_arr[GAME_OTHERS]['total_rolling_amount'],1,2);

					$total_data['total_win_loss_other'] += bcdiv($comm_arr[GAME_OTHERS]['total_win_loss'],1,2);

					$total_data['total_rolling_comission_other'] += bcdiv($other_comm,1,2);



					$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line(get_user_type($upline_row->user_type)));

					$objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $rowCount, $upline_row->username,PHPExcel_Cell_DataType::TYPE_STRING);

					$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, ( ! empty($upline_row->upline) ? $upline_row->upline : '-'));

					$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, number_format($deposit_offline, 2, '.', ','));

					if($deposit_offline > 0){

						$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, number_format($deposit_online, 2, '.', ','));

					if($deposit_online > 0){

						$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($deposit_point, 2, '.', ','));

					if($deposit_point > 0){

						$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, number_format($withdrawal_offline, 2, '.', ','));

					if($withdrawal_offline > 0){

						$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, number_format($withdrawal_point, 2, '.', ','));

					if($withdrawal_point > 0){

						$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, number_format($adjust_in, 2, '.', ','));

					if($adjust_in > 0){

						$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($adjust_out, 2, '.', ','));

					if($adjust_out > 0){

						$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($total_bet_amount, 2, '.', ','));

					if($total_bet_amount > 0){

						$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, number_format($total_rolling_amount, 2, '.', ','));

					if($total_rolling_amount > 0){

						$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, number_format($total_win_loss * -1, 2, '.', ','));

					if($total_win_loss > 0){

						$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayRed);

					}else if($total_win_loss < 0){

						$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, number_format($promotion * -1, 2, '.', ','));

					if($promotion > 0){

						$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayRed);

					}else if($promotion < 0){

						$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $upline_row->possess);

					$objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, number_format($possess_win_loss * -1, 2, '.', ','));

					if($possess_win_loss > 0){

						$objPHPExcel->getActiveSheet()->getStyle('P'.$rowCount)->applyFromArray($styleArrayRed);

					}else if($possess_win_loss < 0){

						$objPHPExcel->getActiveSheet()->getStyle('P'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('P'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, number_format($possess_promotion * -1, 2, '.', ','));

					if($possess_promotion > 0){

						$objPHPExcel->getActiveSheet()->getStyle('Q'.$rowCount)->applyFromArray($styleArrayRed);

					}else if($possess_promotion < 0){

						$objPHPExcel->getActiveSheet()->getStyle('Q'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('Q'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, number_format($possess_profit, 2, '.', ','));

					if($possess_profit > 0){

						$objPHPExcel->getActiveSheet()->getStyle('R'.$rowCount)->applyFromArray($styleArrayBlue);

					}else if($possess_profit < 0){

						$objPHPExcel->getActiveSheet()->getStyle('R'.$rowCount)->applyFromArray($styleArrayRed);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('R'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, number_format($comm_arr[GAME_LIVE_CASINO]['total_rolling_amount'], 2, '.', ','));

					if($comm_arr[GAME_LIVE_CASINO]['total_rolling_amount'] > 0){

						$objPHPExcel->getActiveSheet()->getStyle('S'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('S'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, number_format($comm_arr[GAME_LIVE_CASINO]['total_win_loss'] * -1, 2, '.', ','));

					if($comm_arr[GAME_LIVE_CASINO]['total_win_loss'] > 0){

						$objPHPExcel->getActiveSheet()->getStyle('T'.$rowCount)->applyFromArray($styleArrayRed);

					}else if($comm_arr[GAME_LIVE_CASINO]['total_win_loss'] < 0){

						$objPHPExcel->getActiveSheet()->getStyle('T'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('T'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('U' . $rowCount, number_format($casino_comm_rate, 2, '.', ','));

					if($casino_comm_rate > 0){

						$objPHPExcel->getActiveSheet()->getStyle('U'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('U'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, number_format($casino_comm, 2, '.', ','));

					if($casino_comm > 0){

						$objPHPExcel->getActiveSheet()->getStyle('V'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('V'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('W' . $rowCount, number_format($comm_arr[GAME_SLOTS]['total_rolling_amount'], 2, '.', ','));

					if($comm_arr[GAME_SLOTS]['total_rolling_amount'] > 0){

						$objPHPExcel->getActiveSheet()->getStyle('W'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('W'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('X' . $rowCount, number_format($comm_arr[GAME_SLOTS]['total_win_loss'] * -1, 2, '.', ','));

					if($comm_arr[GAME_SLOTS]['total_win_loss'] > 0){

						$objPHPExcel->getActiveSheet()->getStyle('X'.$rowCount)->applyFromArray($styleArrayRed);

					}else if($comm_arr[GAME_SLOTS]['total_win_loss'] < 0){

						$objPHPExcel->getActiveSheet()->getStyle('X'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('X'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('Y' . $rowCount, number_format($slots_comm_rate, 2, '.', ','));

					if($slots_comm_rate > 0){

						$objPHPExcel->getActiveSheet()->getStyle('Y'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('Y'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('Z' . $rowCount, number_format($slots_comm, 2, '.', ','));

					if($slots_comm > 0){

						$objPHPExcel->getActiveSheet()->getStyle('Z'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('Z'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('AA' . $rowCount, number_format($comm_arr[GAME_SPORTSBOOK]['total_rolling_amount'], 2, '.', ','));

					if($comm_arr[GAME_SPORTSBOOK]['total_rolling_amount'] > 0){

						$objPHPExcel->getActiveSheet()->getStyle('AA'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('AA'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('AB' . $rowCount, number_format($comm_arr[GAME_SPORTSBOOK]['total_win_loss'] * -1, 2, '.', ','));

					if($comm_arr[GAME_SPORTSBOOK]['total_win_loss'] > 0){

						$objPHPExcel->getActiveSheet()->getStyle('AB'.$rowCount)->applyFromArray($styleArrayRed);

					}else if($comm_arr[GAME_SPORTSBOOK]['total_win_loss'] < 0){

						$objPHPExcel->getActiveSheet()->getStyle('AB'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('AB'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('AC' . $rowCount, number_format($sport_comm_rate, 2, '.', ','));

					if($sport_comm_rate > 0){

						$objPHPExcel->getActiveSheet()->getStyle('AC'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('AC'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('AD' . $rowCount, number_format($sport_comm, 2, '.', ','));

					if($sport_comm > 0){

						$objPHPExcel->getActiveSheet()->getStyle('AD'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('AD'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('AE' . $rowCount, number_format($comm_arr[GAME_OTHERS]['total_rolling_amount'], 2, '.', ','));

					if($comm_arr[GAME_OTHERS]['total_rolling_amount'] > 0){

						$objPHPExcel->getActiveSheet()->getStyle('AE'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('AE'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('AF' . $rowCount, number_format($comm_arr[GAME_OTHERS]['total_win_loss'] * -1, 2, '.', ','));

					if($comm_arr[GAME_OTHERS]['total_win_loss'] > 0){

						$objPHPExcel->getActiveSheet()->getStyle('AF'.$rowCount)->applyFromArray($styleArrayRed);

					}else if($comm_arr[GAME_OTHERS]['total_win_loss'] < 0){

						$objPHPExcel->getActiveSheet()->getStyle('AF'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('AF'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('AG' . $rowCount, number_format($other_comm_rate, 2, '.', ','));

					if($other_comm_rate > 0){

						$objPHPExcel->getActiveSheet()->getStyle('AG'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('AG'.$rowCount)->applyFromArray($styleArrayBlack);

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('AH' . $rowCount, number_format($other_comm, 2, '.', ','));

					if($other_comm > 0){

						$objPHPExcel->getActiveSheet()->getStyle('AH'.$rowCount)->applyFromArray($styleArrayBlue);

					}else{

						$objPHPExcel->getActiveSheet()->getStyle('AH'.$rowCount)->applyFromArray($styleArrayBlack);

					}



					$rowCount++;

				}

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line('label_total'));

			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, number_format($total_data['total_deposit_offline'], 2, '.', ','));

			if($total_data['total_deposit_offline'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, number_format($total_data['total_deposit_online'], 2, '.', ','));

			if($total_data['total_deposit_online'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($total_data['total_deposit_point'], 2, '.', ','));

			if($total_data['total_deposit_point'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, number_format($total_data['total_withdrawal_offline'], 2, '.', ','));

			if($total_data['total_withdrawal_offline'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, number_format($total_data['total_withdrawal_point'], 2, '.', ','));

			if($total_data['total_withdrawal_point'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, number_format($total_data['total_adjust_in'], 2, '.', ','));

			if($total_data['total_adjust_in'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($total_data['total_adjust_out'], 2, '.', ','));

			if($total_data['total_adjust_out'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($total_data['total_bet_amount'], 2, '.', ','));

			if($total_data['total_bet_amount'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, number_format($total_data['total_rolling_amount'], 2, '.', ','));

			if($total_data['total_rolling_amount'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, number_format($total_data['total_win_loss'] * -1, 2, '.', ','));

			if($total_data['total_win_loss'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayRed);

			}else if($total_data['total_win_loss'] < 0){

				$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, number_format($total_data['total_promotion'] * -1, 2, '.', ','));

			if($total_data['total_promotion'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayRed);

			}else if($total_data['total_promotion'] < 0){

				$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, number_format($total_data['total_possess_win_loss'] * -1, 2, '.', ','));

			if($total_data['total_possess_win_loss'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('P'.$rowCount)->applyFromArray($styleArrayRed);

			}else if($total_data['total_possess_win_loss'] < 0){

				$objPHPExcel->getActiveSheet()->getStyle('P'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('P'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, number_format($total_data['total_possess_promotion'] * -1, 2, '.', ','));

			if($total_data['total_possess_promotion'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('Q'.$rowCount)->applyFromArray($styleArrayRed);

			}else if($total_data['total_possess_promotion'] < 0){

				$objPHPExcel->getActiveSheet()->getStyle('Q'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('Q'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, number_format($total_data['total_profit'], 2, '.', ','));

			if($total_data['total_profit'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('R'.$rowCount)->applyFromArray($styleArrayBlue);

			}else if($total_data['total_profit'] < 0){

				$objPHPExcel->getActiveSheet()->getStyle('R'.$rowCount)->applyFromArray($styleArrayRed);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('R'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, number_format($total_data['total_rolling_amount_live_casino'], 2, '.', ','));

			if($total_data['total_rolling_amount_live_casino'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('S'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('S'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, number_format($total_data['total_win_loss_live_casino'] * -1, 2, '.', ','));

			if($total_data['total_win_loss_live_casino'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('T'.$rowCount)->applyFromArray($styleArrayRed);

			}else if($total_data['total_win_loss_live_casino'] < 0){

				$objPHPExcel->getActiveSheet()->getStyle('T'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('T'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('U' . $rowCount, number_format($total_data['total_rolling_comission_live_casino'], 2, '.', ','));

			if($total_data['total_rolling_comission_live_casino'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('U'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('U'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('W' . $rowCount, number_format($total_data['total_rolling_amount_slot'], 2, '.', ','));

			if($total_data['total_rolling_amount_slot'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('W'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('W'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('X' . $rowCount, number_format($total_data['total_win_loss_slot'] * -1, 2, '.', ','));

			if($total_data['total_win_loss_slot'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('X'.$rowCount)->applyFromArray($styleArrayRed);

			}else if($total_data['total_win_loss_slot'] < 0){

				$objPHPExcel->getActiveSheet()->getStyle('X'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('X'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('Z' . $rowCount, number_format($total_data['total_rolling_comission_slot'], 2, '.', ','));

			if($total_data['total_rolling_comission_slot'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('Z'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('Z'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('AA' . $rowCount, number_format($total_data['total_rolling_amount_sportbook'], 2, '.', ','));

			if($total_data['total_rolling_amount_sportbook'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('AA'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('AA'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('AB' . $rowCount, number_format($total_data['total_win_loss_sportbook'] * -1, 2, '.', ','));

			if($total_data['total_win_loss_sportbook'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('AB'.$rowCount)->applyFromArray($styleArrayRed);

			}else if($total_data['total_win_loss_sportbook'] < 0){

				$objPHPExcel->getActiveSheet()->getStyle('AB'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('AB'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('AD' . $rowCount, number_format($total_data['total_rolling_comission_sportbook'], 2, '.', ','));

			if($total_data['total_rolling_comission_sportbook'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('AD'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('AD'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('AE' . $rowCount, number_format($total_data['total_rolling_amount_other'], 2, '.', ','));

			if($total_data['total_rolling_amount_other'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('AE'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('AE'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('AF' . $rowCount, number_format($total_data['total_win_loss_other'] * -1, 2, '.', ','));

			if($total_data['total_win_loss_other'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('AF'.$rowCount)->applyFromArray($styleArrayRed);

			}else if($total_data['total_win_loss_other'] < 0){

				$objPHPExcel->getActiveSheet()->getStyle('AF'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('AF'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			$objPHPExcel->getActiveSheet()->SetCellValue('AH' . $rowCount, number_format($total_data['total_rolling_comission_other'], 2, '.', ','));

			if($total_data['total_rolling_comission_other'] > 0){

				$objPHPExcel->getActiveSheet()->getStyle('AH'.$rowCount)->applyFromArray($styleArrayBlue);

			}else{

				$objPHPExcel->getActiveSheet()->getStyle('AH'.$rowCount)->applyFromArray($styleArrayBlack);

			}

			//set color

			$objPHPExcel->getActiveSheet()->getStyle('A1:R1')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "326565")));

			$objPHPExcel->getActiveSheet()->getStyle('S1:V1')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "6F0000")));

			$objPHPExcel->getActiveSheet()->getStyle('W1:Z1')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "8C4600")));

			$objPHPExcel->getActiveSheet()->getStyle('AA1:AD1')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "000080")));

			$objPHPExcel->getActiveSheet()->getStyle('AE1:AH1')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "848400")));

			$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':AH'.$rowCount)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "FFFFDF")));

			$rowCount++;



			//$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			//$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			//$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('D2:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('E2:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('F2:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('G2:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('H2:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('I2:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('J2:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('K2:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('L2:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('M2:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('N2:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('O2:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('P2:P'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('Q2:Q'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('R2:R'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('S2:S'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('T2:T'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('U2:U'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('V2:V'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('W2:W'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('X2:X'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('Y2:Y'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('Z2:Z'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('AA2:AA'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('AB2:AB'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('AC2:AC'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('AD2:AD'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('AE2:AE'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('AF2:AF'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('AG2:AG'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('AH2:AH'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

	        header("Content-Type: application/vnd.ms-excel");

			header("Content-Disposition: attachment; filename=".$fileName."");

			$objWriter->save("php://output");

		}

	}



	public function withdrawal_verify_export_excel_check(){

		//Initial output data

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_WITHDRAWAL_VERIFY_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_PLAYER_WITHDRAWAL_VERIFY_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_report_withdrawal_verify');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();

	}



	public function withdrawal_verify_export_excel(){

		if(permission_validation(PERMISSION_WITHDRAWAL_VERIFY_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_PLAYER_WITHDRAWAL_VERIFY_REPORT) == TRUE)

		{

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$arr = $this->session->userdata('search_report_withdrawal_verify');

			$dbprefix = $this->db->dbprefix;

			if(!empty($arr)){



				$where = "";

				$posts = NULL;

				$player_list = array();

				$where .= 'WHERE b.withdrawals_amount > 0';

				

				if(isset($arr['from_date']))

				{

					if( ! empty($arr['from_date'])){

						$where .= ' AND b.report_date >= ' . strtotime($arr['from_date']);

					}

				}

				if( ! empty($arr['to_date']))

				{

					if( ! empty($arr['to_date'])){

						$where .= ' AND b.report_date <= ' . strtotime($arr['to_date']);

					}

				}

				

				$query_string = "SELECT b.player_id FROM {$dbprefix}total_win_loss_report b $where";

				$query = $this->db->query($query_string . $query_string_2);

				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}

				

				$query->free_result();



				if(!empty($posts))

				{

					foreach ($posts as $post)

					{

						$player_list[] = $post->player_id;

					}

					$player_id = '"'.implode('","', $player_list).'"';

				}



				$sum_win_loss_columns = array(

					'b.player_id',

					'COALESCE(SUM(b.deposit_amount),0) AS total_deposit_amount',

		    		'COALESCE(SUM(b.deposit_offline_amount),0) AS total_deposit_offline_amount',

		    		'COALESCE(SUM(b.deposit_online_amount),0) AS total_deposit_online_amount',

		    		'COALESCE(SUM(b.deposit_point_amount),0) AS total_deposit_point_amount',

		    		'COALESCE(SUM(b.withdrawals_amount),0) AS total_withdrawals_amount',

		    		'COALESCE(SUM(b.withdrawals_offline_amount),0) AS total_withdrawals_offline_amount',

		    		'COALESCE(SUM(b.withdrawals_online_amount),0) AS total_withdrawals_online_amount',

		    		'COALESCE(SUM(b.withdrawals_point_amount),0) AS total_withdrawals_point_amount',

		    		'COALESCE(SUM(b.adjust_amount),0) AS total_adjust_amount',

		    		'COALESCE(SUM(b.adjust_in_amount),0) AS total_adjust_in_amount',

		    		'COALESCE(SUM(b.adjust_out_amount),0) AS total_adjust_out_amount',

		    		'COALESCE(SUM(b.win_loss),0) AS total_win_loss',

		    		'COALESCE(SUM(b.promotion_amount),0) AS total_promotion_amount',

		    		'COALESCE(SUM(b.bonus_amount),0) AS total_bonus_amount',

				);



				$sum_withdrawal_columns = array(

					'b.player_id',

					'COALESCE(SUM(b.amount),0) AS total_withdrawals_amount',

				);



				$sum_bet_columns = array(

					'b.player_id',

					'COALESCE(SUM(b.bet_amount),0) AS total_bet_amount',

				);



				//Table Columns

				$columns = array( 

					'a.player_id',

		    		'a.username',

		    		'a.points',

		    		'a.old_points',

		    		'COALESCE(SUM(b.deposit_amount),0) AS total_deposit_amount',

		    		'COALESCE(SUM(b.deposit_offline_amount),0) AS total_deposit_offline_amount',

		    		'COALESCE(SUM(b.deposit_online_amount),0) AS total_deposit_online_amount',

		    		'COALESCE(SUM(b.deposit_point_amount),0) AS total_deposit_point_amount',

		    		'COALESCE(SUM(b.withdrawals_amount),0) AS total_withdrawals_amount',

		    		'COALESCE(SUM(b.withdrawals_offline_amount),0) AS total_withdrawals_offline_amount',

		    		'COALESCE(SUM(b.withdrawals_online_amount),0) AS total_withdrawals_online_amount',

		    		'COALESCE(SUM(b.withdrawals_point_amount),0) AS total_withdrawals_point_amount',

		    		'COALESCE(SUM(b.adjust_amount),0) AS total_adjust_amount',

		    		'COALESCE(SUM(b.adjust_in_amount),0) AS total_adjust_in_amount',

		    		'COALESCE(SUM(b.adjust_out_amount),0) AS total_adjust_out_amount',

		    		'COALESCE(SUM(b.win_loss),0) AS total_win_loss',

		    		'COALESCE(SUM(b.promotion_amount),0) AS total_promotion_amount',

		    		'COALESCE(SUM(b.bonus_amount),0) AS total_bonus_amount',

				);



				$columns_sort = array( 

					'a.player_id',

		    		'a.username',

		    		'a.points',

		    		'a.old_points',

		    		'total_deposit_amount',

		    		'total_deposit_offline_amount',

		    		'total_deposit_online_amount',

		    		'total_deposit_point_amount',

		    		'total_withdrawals_amount',

		    		'total_withdrawals_offline_amount',

		    		'total_withdrawals_online_amount',

		    		'total_withdrawals_point_amount',

		    		'total_adjust_amount',

		    		'total_adjust_in_amount',

		    		'total_adjust_out_amount',

		    		'total_win_loss',

		    		'total_promotion_amount',

		    		'total_bonus_amount',

				);





				if( ! empty($arr['agent']))

				{

					$where = " AND player_id = 'ABC'";

					$agent = $this->user_model->get_user_data_by_username($arr['agent']);

					if(!empty($agent)){

						$response_upline = $this->user_model->get_downline_data($agent['username']);

						if(!empty($response_upline)){

							$where = " AND a.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";

						}

					}

				}else{

					$where = " AND a.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";

				}





				/****REGISTER DATE****/

				/*

				if(isset($arr['from_date']))

				{

					if( ! empty($arr['from_date'])){

						$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);

					}

				}

				if( ! empty($arr['to_date']))

				{

					if( ! empty($arr['to_date'])){

						$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);

					}

				}

				*/



				if( ! empty($arr['username']))

				{

					$where .= " AND a.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	

				}

				if(!empty($player_id)){

					$where .= " AND b.player_id IN(" . $player_id . ")";

				}else{

					$where .= " AND b.player_id = 'abc'";

				}



				$select = implode(',', $columns);

				



				$posts = NULL;

				$query_string = "SELECT {$select} FROM {$dbprefix}total_win_loss_report b, {$dbprefix}players a WHERE a.player_id = b.player_id $where GROUP BY a.player_id";

				$query_string_2 = " ORDER by a.username DESC";

				$query = $this->db->query($query_string . $query_string_2);

				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}



				//Prepare data

				$data = array();



				$player_list = array();

				$player_id = "";

				$win_loss_list = array();

				$pending_withdrawal_list = array();

				$pending_bet_list = array();



				if(!empty($posts))

				{

					foreach ($posts as $post)

					{

						$player_list[] = $post->player_id;

					}

					$player_id = '"'.implode('","', $player_list).'"';

				}





				if(!empty($player_list)){

					$sum_withdrawal_select = implode(',', $sum_withdrawal_columns);

					$sum_withdrawal_query_string = "(SELECT {$sum_withdrawal_select} FROM {$dbprefix}withdrawals b WHERE b.status = ".STATUS_PENDING." AND b.player_id IN(" . $player_id . ") GROUP BY b.player_id)";

					$sum_withdrawal_query = $this->db->query($sum_withdrawal_query_string);

					if($sum_withdrawal_query->num_rows() > 0)

					{

						foreach($sum_withdrawal_query->result() as $row)

						{

							$pending_withdrawal_list[$row->player_id] = array(

								'total_withdrawals_amount' => $row->total_withdrawals_amount,

							);

						}

					}

					$sum_withdrawal_query->free_result();

				}





				if(!empty($player_list)){

					$sum_bet_select = implode(',', $sum_bet_columns);

					$sum_bet_query_string = "(SELECT {$sum_bet_select} FROM {$dbprefix}transaction_report b WHERE b.status = ".STATUS_PENDING." AND b.player_id IN(" . $player_id . ") GROUP BY b.player_id)";

					$sum_bet_query = $this->db->query($sum_bet_query_string);

					if($sum_bet_query->num_rows() > 0)

					{

						foreach($sum_bet_query->result() as $row)

						{

							$pending_bet_list[$row->player_id] = array(

								'total_bet_amount' => $row->total_bet_amount,

							);

						}

					}

					$sum_bet_query->free_result();

				}



				$fileName = $this->lang->line('title_withdraw_verify_report').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_withdraw_verify_report'));

        		// set Header

        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

        		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_main_wallet'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_main_wallet_old'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('deposit_offline_banking'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_deposit_online'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_withdrawal'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('transfer_adjust_in'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('transfer_adjust_out'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('J1', $this->lang->line('label_win_loss'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('K1', $this->lang->line('label_promotion_amount'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('L1', $this->lang->line('label_game_wallet'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('M1', $this->lang->line('label_withdrawal_pending'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('N1', $this->lang->line('label_bet_pending'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('O1', $this->lang->line('label_total_verify'));



		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);

		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);



		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setSize(12);

				$rowCount = 2;

				$result_count = 1;



				$styleArrayRed = get_excel_color_status(EXPORT_COLOR_RED);

				$styleArrayBlue = get_excel_color_status(EXPORT_COLOR_BLUE);

				$styleArrayBlack = get_excel_color_status(EXPORT_COLOR_BLACK);



				if(!empty($posts))

				{

					foreach ($posts as $post)

					{

						$total_deposit_amount = $post->total_deposit_amount;

						$total_deposit_offline_amount = $post->total_deposit_offline_amount;

						$total_deposit_online_amount = $post->total_deposit_online_amount;

						$total_deposit_point_amount = $post->total_deposit_point_amount;

						$total_withdrawals_amount = $post->total_withdrawals_amount;

						$total_withdrawals_offline_amount = $post->total_withdrawals_offline_amount;

						$total_withdrawals_online_amount = $post->total_withdrawals_online_amount;

						$total_withdrawals_point_amount = $post->total_withdrawals_point_amount;

						$total_adjust_amount = $post->total_adjust_amount;

						$total_adjust_in_amount = $post->total_adjust_in_amount;

						$total_adjust_out_amount = $post->total_adjust_out_amount;

						$total_win_loss = $post->total_win_loss;

						$total_promotion_amount = $post->total_promotion_amount;

						$total_bonus_amount = $post->total_bonus_amount;





						$total_pending_withdrawals_amount = ((isset($pending_withdrawal_list[$post->player_id])) ? $pending_withdrawal_list[$post->player_id]['total_withdrawals_amount'] : 0);

						$total_pending_bet_amount = ((isset($pending_bet_list[$post->player_id])) ? $pending_bet_list[$post->player_id]['total_bet_amount'] : 0);



						$total_deposit = $total_deposit_offline_amount + $total_deposit_online_amount + $total_deposit_point_amount + $total_adjust_in_amount + $total_promotion_amount + $total_bonus_amount;

						$total_withdrawal = $total_withdrawals_offline_amount + $total_withdrawals_online_amount + $total_withdrawals_point_amount + $total_adjust_out_amount;

						$total = $total_deposit - $total_withdrawal + $total_win_loss - $post->points + $post->old_points - $total_pending_withdrawals_amount - $total_pending_bet_amount;







						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $rowCount, $post->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $post->points);

						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $post->old_points);

						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, number_format($total_deposit_offline_amount, 2, '.', ','));

						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($total_deposit_online_amount, 2, '.', ','));

						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, number_format($total_withdrawals_offline_amount, 2, '.', ','));

						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, number_format($total_adjust_in_amount, 2, '.', ','));

						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, number_format($total_adjust_out_amount, 2, '.', ','));

						$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($total_win_loss, 2, '.', ','));

						if($total_win_loss > 0){

							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($total_win_loss < 0){

							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlack);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($total_promotion_amount, 2, '.', ','));

						$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, number_format(0, 2, '.', ','));

						$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, number_format($total_pending_withdrawals_amount, 2, '.', ','));

						$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, number_format($total_pending_bet_amount, 2, '.', ','));

						$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, number_format($total, 2, '.', ','));

						$rowCount++;

           				$result_count++;

					}

				}



				$rowCount++;

				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('P1:P'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('Q1:Q'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}



	public function register_deposit_rate_export_check(){

		//Initial output data

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_register_deposit_rate');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();

	}



	public function register_deposit_rate_export($num = NULL, $username = NULL){

		if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_register_deposit_rate');

			if(!empty($arr)){

				if( ! empty($arr['from_date']))

				{

					$limit = trim($this->input->post('length', TRUE));

					$start = trim($this->input->post("start", TRUE));

					$order = $this->input->post("order", TRUE);



					$dbprefix = $this->db->dbprefix;

					$data = array();



					$start_date = $arr['from_date']."-01 00:00:00";

					$start_time = strtotime($start_date);

					$end_date	= date('Y-m-d 00:00:00', strtotime('first day of next month',$start_time));

					$end_time = strtotime($end_date);

					//ad(date('Y-m-d H:i:s',$start_time));

					//ad(date('Y-m-d H:i:s',$end_time));



					$where_total_register_player = "";

					$where_total_player_have_deposit = "";

					$where_total_player_have_one_deposit = "";

					$where_total_player_have_two_or_more_deposit = "";

					$where_total_player_have_three_or_more_deposit = "";

					$where_total_player_have_deposit_amount = "";

					$where_total_player_have_one_deposit_amount = "";

					$where_total_player_have_two_or_more_deposit_amount = "";

					$where_total_player_have_three_or_more_deposit_amount = "";





					$upline_query_string = "SELECT MU.user_id,MU.user_type,MU.username,MU.upline";



					//Total Register Player

					$where_total_register_player .= "AP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

					if( ! empty($arr['from_date']))

					{

						$where_total_register_player .= ' AND AP.created_date >= ' . $start_time;

					}

					if( ! empty($arr['from_date']))

					{

						$where_total_register_player .= ' AND AP.created_date < ' . $end_time;

					}

					$upline_query_string .= ",(SELECT COUNT(AP.player_id) AS total_register_player FROM {$dbprefix}players AP where $where_total_register_player) AS total_register_player ";



					

					//Total Player Have Deposit

					$where_total_player_have_deposit .= "BP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

					$where_total_player_have_deposit .= ' AND BP.deposit_count > 0';

					if( ! empty($arr['from_date']))

					{

						$where_total_player_have_deposit .= ' AND BP.created_date >= ' . $start_time;

					}

					if( ! empty($arr['from_date']))

					{

						$where_total_player_have_deposit .= ' AND BP.created_date < ' . $end_time;

					}

					$upline_query_string .= ",(SELECT COUNT(BP.player_id) AS total_player_have_deposit FROM {$dbprefix}players BP where $where_total_player_have_deposit) AS total_player_have_deposit ";



					//Total Player Have Deposit Sum

					$where_total_player_have_deposit_amount .= "BSP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

					$where_total_player_have_deposit_amount .= ' AND BSP.deposit_count > 0';

					$where_total_player_have_deposit_amount .= " AND BSTR.player_id = BSP.player_id";

					if( ! empty($arr['from_date']))

					{

						$where_total_player_have_deposit_amount .= ' AND BSP.created_date >= ' . $start_time;

					}

					if( ! empty($arr['from_date']))

					{

						$where_total_player_have_deposit_amount .= ' AND BSP.created_date < ' . $end_time;

					}

					$upline_query_string .= ",(SELECT SUM(BSTR.deposit_amount) AS total_player_have_deposit_amount FROM {$dbprefix}total_win_loss_report_month BSTR, {$dbprefix}players BSP where $where_total_player_have_deposit_amount) AS total_player_have_deposit_amount";







					//Total Player have one only deposit

					$where_total_player_have_one_deposit .= "CP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

					$where_total_player_have_one_deposit .= ' AND CP.deposit_count = 1';

					if( ! empty($arr['from_date']))

					{

						$where_total_player_have_one_deposit .= ' AND CP.created_date >= ' . $start_time;

					}

					if( ! empty($arr['from_date']))

					{

						$where_total_player_have_one_deposit .= ' AND CP.created_date < ' . $end_time;

					}

					$upline_query_string .= ",(SELECT COUNT(CP.player_id) AS total_player_have_one_deposit FROM {$dbprefix}players CP where $where_total_player_have_one_deposit) AS total_player_have_one_deposit ";



					//Total Player have one only deposit Sum

					$where_total_player_have_one_deposit_amount .= "CSP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

					$where_total_player_have_one_deposit_amount .= ' AND CSP.deposit_count = 1';

					$where_total_player_have_one_deposit_amount .= " AND CSTR.player_id = CSP.player_id";

					if( ! empty($arr['from_date']))

					{

						$where_total_player_have_one_deposit_amount .= ' AND CSP.created_date >= ' . $start_time;

					}

					if( ! empty($arr['from_date']))

					{

						$where_total_player_have_one_deposit_amount .= ' AND CSP.created_date < ' . $end_time;

					}

					$upline_query_string .= ",(SELECT SUM(CSTR.deposit_amount) AS total_player_have_one_deposit_amount FROM {$dbprefix}total_win_loss_report_month CSTR, {$dbprefix}players CSP where $where_total_player_have_one_deposit_amount) AS total_player_have_one_deposit_amount";



					//Total Player have 2 or more deposit

					$where_total_player_have_two_or_more_deposit .= "DP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

					$where_total_player_have_two_or_more_deposit .= ' AND DP.deposit_count = 2';

					if( ! empty($arr['from_date']))

					{

						$where_total_player_have_two_or_more_deposit .= ' AND DP.created_date >= ' . $start_time;

					}

					if( ! empty($arr['from_date']))

					{

						$where_total_player_have_two_or_more_deposit .= ' AND DP.created_date < ' . $end_time;

					}

					$upline_query_string .= ",(SELECT COUNT(DP.player_id) AS total_player_have_two_or_more_deposit FROM {$dbprefix}players DP where $where_total_player_have_two_or_more_deposit) AS total_player_have_two_or_more_deposit ";



					//Total Player have 2 or more deposit Sum

					$where_total_player_have_two_or_more_deposit_amount .= "DSP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

					$where_total_player_have_two_or_more_deposit_amount .= ' AND DSP.deposit_count = 2';

					$where_total_player_have_two_or_more_deposit_amount .= " AND DSTR.player_id = DSP.player_id";

					if( ! empty($arr['from_date']))

					{

						$where_total_player_have_two_or_more_deposit_amount .= ' AND DSP.created_date >= ' . $start_time;

					}

					if( ! empty($arr['from_date']))

					{

						$where_total_player_have_two_or_more_deposit_amount .= ' AND DSP.created_date < ' . $end_time;

					}

					$upline_query_string .= ",(SELECT SUM(DSTR.deposit_amount) AS total_player_have_two_or_more_deposit_amount FROM {$dbprefix}total_win_loss_report_month DSTR, {$dbprefix}players DSP where $where_total_player_have_two_or_more_deposit_amount) AS total_player_have_two_or_more_deposit_amount";



					//Total Player have 3 or more deposit

					$where_total_player_have_three_or_more_deposit .= "EP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

					$where_total_player_have_three_or_more_deposit .= ' AND EP.deposit_count >= 3';

					if( ! empty($arr['from_date']))

					{

						$where_total_player_have_three_or_more_deposit .= ' AND EP.created_date >= ' . $start_time;

					}

					if( ! empty($arr['from_date']))

					{

						$where_total_player_have_three_or_more_deposit .= ' AND EP.created_date < ' . $end_time;

					}

					$upline_query_string .= ",(SELECT COUNT(EP.player_id) AS total_player_have_three_or_more_deposit FROM {$dbprefix}players EP where $where_total_player_have_three_or_more_deposit) AS total_player_have_three_or_more_deposit ";



					//Total Player have 3 or more deposit Sum

					$where_total_player_have_three_or_more_deposit_amount .= "ESP.upline_ids LIKE CONCAT('%,', MU.user_id, ',%')";

					$where_total_player_have_three_or_more_deposit_amount .= ' AND ESP.deposit_count >= 3';

					$where_total_player_have_three_or_more_deposit_amount .= " AND ESTR.player_id = ESP.player_id";

					if( ! empty($arr['from_date']))

					{

						$where_total_player_have_three_or_more_deposit_amount .= ' AND ESP.created_date >= ' . $start_time;

					}

					if( ! empty($arr['from_date']))

					{

						$where_total_player_have_three_or_more_deposit_amount .= ' AND ESP.created_date < ' . $end_time;

					}

					$upline_query_string .= ",(SELECT SUM(ESTR.deposit_amount) AS total_player_have_three_or_more_deposit_amount FROM {$dbprefix}total_win_loss_report_month ESTR, {$dbprefix}players ESP where $where_total_player_have_three_or_more_deposit_amount) AS total_player_have_three_or_more_deposit_amount";



					$upline_query_string .= " FROM bctp_users MU ";

					if(empty($username))

					{

						$num = 1;

						$upline_query_string .= "WHERE MU.user_id = " . $this->session->userdata('root_user_id') . " LIMIT 1";

						$totalFiltered = 1;

					}else{

						$extract_string = "";

						$upline_query_string .= "WHERE MU.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' AND MU.upline = '{$username}' GROUP BY MU.user_id $extract_string";

					}

					$upline_query = $this->db->query($upline_query_string);



					$fileName = $this->lang->line('title_register_deposit_rate_report').' - '.date("Y-m-d", time())." ".time().'.xlsx';

					$objPHPExcel = new PHPExcel();

		    		$objPHPExcel->setActiveSheetIndex(0);

		    		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_register_deposit_rate_report'));



		    		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_level'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_agent_username'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_agent'));

			       	$objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_register_deposit_rate_register_count'));

			       	$objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_register_deposit_rate_member_deposit'));

			        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_register_deposit_rate_total_deposit_amount'));

			        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_register_deposit_rate_member_deposit_rate'));

			        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_register_deposit_rate_first_deposit'));

			        $objPHPExcel->getActiveSheet()->SetCellValue('J1', $this->lang->line('label_register_deposit_rate_total_deposit_amount'));

			        $objPHPExcel->getActiveSheet()->SetCellValue('K1', $this->lang->line('label_register_deposit_rate_first_deposit_rate'));

					$objPHPExcel->getActiveSheet()->SetCellValue('L1', $this->lang->line('label_register_deposit_rate_second_or_more_deposit'));

					$objPHPExcel->getActiveSheet()->SetCellValue('M1', $this->lang->line('label_register_deposit_rate_total_deposit_amount'));

					$objPHPExcel->getActiveSheet()->SetCellValue('N1', $this->lang->line('label_register_deposit_rate_second_or_more_deposit_rate'));

					$objPHPExcel->getActiveSheet()->SetCellValue('O1', $this->lang->line('label_register_deposit_rate_third_or_more_deposit'));

					$objPHPExcel->getActiveSheet()->SetCellValue('P1', $this->lang->line('label_register_deposit_rate_total_deposit_amount'));

					$objPHPExcel->getActiveSheet()->SetCellValue('Q1', $this->lang->line('label_register_deposit_rate_third_or_more_deposit_rate'));

					$objPHPExcel->getActiveSheet()->SetCellValue('R1', $this->lang->line('label_register_deposit_rate_no_deposit'));

					$objPHPExcel->getActiveSheet()->SetCellValue('S1', $this->lang->line('label_register_deposit_rate_churn_rate'));





					//set cell width

			        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(25);



			        // Set fonts style

			        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('R1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('S1')->getFont()->setBold(true);



			        // Set fonts size

			        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('R1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('S1')->getFont()->setSize(12);

					

					$rowCount = 2;

					$result_count = 1;



					$styleArrayRed = get_excel_color_status(EXPORT_COLOR_RED);

					$styleArrayBlue = get_excel_color_status(EXPORT_COLOR_BLUE);

					$styleArrayBlack = get_excel_color_status(EXPORT_COLOR_BLACK);



					$total_data = array(

						"total_register_count" => 0,

						"total_member_deposit" => 0,

						"total_member_deposit_amount" => 0,

						"total_member_deposit_rate" => 0,

						"total_first_deposit" => 0,

						"total_first_deposit_amount" => 0,

						"total_first_deposit_rate" => 0,

						"total_second_or_more_deposit" => 0,

						"total_second_or_more_deposit_amount" => 0,

						"total_second_or_more_deposit_rate" => 0,

						"total_third_or_more_deposit" => 0,

						"total_third_or_more_deposit_amount" => 0,

						"total_third_or_more_deposit_rate" => 0,

						"total_no_deposit" => 0,

						"total_churn_rate" => 0,

					);

					if($upline_query->num_rows() > 0)

					{

						foreach($upline_query->result() as $upline_row)

						{

							$total_data["total_register_count"] += $upline_row->total_register_player;

							$total_data["total_member_deposit"] += $upline_row->total_player_have_deposit;

							$total_data["total_first_deposit"] += $upline_row->total_player_have_one_deposit;

							$total_data["total_second_or_more_deposit"] += $upline_row->total_player_have_two_or_more_deposit;

							$total_data["total_third_or_more_deposit"] += $upline_row->total_player_have_three_or_more_deposit;



							$total_data["total_member_deposit_amount"] += $upline_row->total_player_have_deposit_amount;

							$total_data["total_first_deposit_amount"] += $upline_row->total_player_have_one_deposit_amount;

							$total_data["total_second_or_more_deposit_amount"] += $upline_row->total_player_have_two_or_more_deposit_amount;

							$total_data["total_third_or_more_deposit_amount"] += $upline_row->total_player_have_three_or_more_deposit_amount;





							$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

							$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $this->lang->line(get_user_type($upline_row->user_type)));

							$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $rowCount, $upline_row->username,PHPExcel_Cell_DataType::TYPE_STRING);

							$objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $rowCount, ( ! empty($upline_row->upline) ? $upline_row->upline : '-'),PHPExcel_Cell_DataType::TYPE_STRING);

							$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, number_format($upline_row->total_register_player, 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($upline_row->total_player_have_deposit, 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, number_format($upline_row->total_player_have_deposit_amount, 2, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, (($upline_row->total_register_player > 0) ? number_format((($upline_row->total_player_have_deposit / $upline_row->total_register_player) * 100), 2, '.', ',').'%' : '0.00'.'%'));

							$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, number_format($upline_row->total_player_have_one_deposit, 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($upline_row->total_player_have_one_deposit_amount, 2, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, (($upline_row->total_register_player > 0) ? number_format((($upline_row->total_player_have_one_deposit / $upline_row->total_register_player) * 100), 2, '.', ',').'%' : '0.00'.'%'));

							$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, number_format($upline_row->total_player_have_two_or_more_deposit, 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, number_format($upline_row->total_player_have_two_or_more_deposit_amount, 2, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, (($upline_row->total_register_player > 0) ? number_format((($upline_row->total_player_have_two_or_more_deposit / $upline_row->total_register_player) * 100), 2, '.', ',').'%' : '0.00'.'%'));

							$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, number_format($upline_row->total_player_have_three_or_more_deposit, 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, number_format($upline_row->total_player_have_three_or_more_deposit_amount, 2, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, (($upline_row->total_register_player > 0) ? number_format((($upline_row->total_player_have_three_or_more_deposit / $upline_row->total_register_player) * 100), 2, '.', ',').'%' : '0.00'.'%'));

							$objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, number_format($upline_row->total_register_player - $upline_row->total_player_have_deposit, 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, (($upline_row->total_register_player > 0) ? number_format(((($upline_row->total_register_player - $upline_row->total_player_have_deposit) / $upline_row->total_register_player) * 100), 2, '.', ',').'%' : '0.00'.'%'));

							$objPHPExcel->getActiveSheet()->getStyle('R'.$rowCount.':S'.$rowCount)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "E6B8B7")));

							$rowCount++;

							$result_count++;

						}

					}



					$total_data["total_member_deposit_rate"] = (($total_data["total_register_count"] > 0) ? (($total_data["total_member_deposit"] / $total_data["total_register_count"]) * 100) : '0');

					$total_data["total_first_deposit_rate"] = (($total_data["total_register_count"] > 0) ? (($total_data["total_first_deposit"] / $total_data["total_register_count"]) * 100) : '0');

					$total_data["total_second_or_more_deposit_rate"] = (($total_data["total_register_count"] > 0) ? (($total_data["total_second_or_more_deposit"] / $total_data["total_register_count"]) * 100) : '0');

					$total_data["total_third_or_more_deposit_rate"] = (($total_data["total_register_count"] > 0) ? (($total_data["total_third_or_more_deposit"] / $total_data["total_register_count"]) * 100) : '0');

					$total_data["total_no_deposit"] = $total_data["total_register_count"] - $total_data["total_member_deposit"];

					$total_data["total_churn_rate"] = (($total_data["total_register_count"] > 0) ? ((($total_data["total_register_count"] - $total_data["total_member_deposit"])/$total_data["total_register_count"]) * 100) : '0');



					$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line('label_total'));

					$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, number_format($total_data['total_register_count'], 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($total_data['total_member_deposit'], 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, number_format($total_data['total_member_deposit_amount'], 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, number_format($total_data['total_member_deposit_rate'], 2, '.', ',')."%");

					$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, number_format($total_data['total_first_deposit'], 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($total_data['total_first_deposit_amount'], 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($total_data['total_first_deposit_rate'], 2, '.', ',')."%");

					$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, number_format($total_data['total_second_or_more_deposit'], 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, number_format($total_data['total_second_or_more_deposit_amount'], 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, number_format($total_data['total_second_or_more_deposit_rate'], 2, '.', ',')."%");

					$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, number_format($total_data['total_third_or_more_deposit'], 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, number_format($total_data['total_third_or_more_deposit_amount'], 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, number_format($total_data['total_third_or_more_deposit_rate'], 2, '.', ',')."%");

					$objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, number_format($total_data['total_no_deposit'], 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, number_format($total_data['total_churn_rate'], 2, '.', ',')."%");





					$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

					$objPHPExcel->getActiveSheet()->getStyle('R1:S1')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "E6B8B7")));

					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':Q'.$rowCount)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

					$objPHPExcel->getActiveSheet()->getStyle('R'.$rowCount.':S'.$rowCount)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "E6B8B7")));



					//$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					//$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					//$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					//$objPHPExcel->getActiveSheet()->getStyle('D2:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('E2:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('F2:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('G2:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('H2:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('I2:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('J2:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('K2:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('L2:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('M2:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('N2:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('O2:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('P2:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('Q2:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('R2:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('S2:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



					$result_array = array(

						REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT => array(

							'player' => array(),

							'agent' => array(),

						),

						REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT => array(

							'player' => array(),

							'agent' => array(),

						),

						REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT => array(

							'player' => array(),

							'agent' => array(),

						),

						REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT => array(

							'player' => array(),

							'agent' => array(),

						),

					);



					$query_string = "SELECT P.player_id,P.upline,P.username";



					$condition_query_string = "";

					$where = "";



					$condition_query_string .= " P.player_id = BSP.player_id";

					$condition_query_string .= " AND BSTR.player_id = BSP.player_id";

					$query_string .= ",(SELECT SUM(BSTR.deposit_count) AS total_deposit FROM {$dbprefix}total_win_loss_report_month BSTR, {$dbprefix}players BSP where $condition_query_string) AS total_deposit,(SELECT SUM(BSTR.deposit_amount) AS total_deposit_amount FROM {$dbprefix}total_win_loss_report_month BSTR, {$dbprefix}players BSP where $condition_query_string) AS total_deposit_amount";



					if(empty($username))

					{

						$where .= " P.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%'";

					}else{

						$userData = $this->user_model->get_user_data_by_username($username);

						$where .= " P.upline_ids LIKE '%," . $userData['user_id'] . ",%'";

					}

					$where .= ' AND P.deposit_count > 0';



					if( ! empty($arr['from_date']))

					{

						$where .= ' AND P.created_date >= ' . $start_time;

					}

					if( ! empty($arr['from_date']))

					{

						$where .= ' AND P.created_date < ' . $end_time;

					}

					$query_string .= " FROM {$dbprefix}players P WHERE $where";

					$query = $this->db->query($query_string);

					if($query->num_rows() > 0)

					{

						$posts = $query->result();  

					}



					$query->free_result();

					if(!empty($posts)){

						foreach ($posts as $post)

						{

							if($post->total_deposit > 0){

								array_push($result_array[REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT]['player'],(array) $post);

								$result_array[REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT]['agent'][$post->upline]['upline'] = $post->upline;

								$result_array[REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT]['agent'][$post->upline]['player_count'] += 1;

								$result_array[REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT]['agent'][$post->upline]['total_deposit'] += $post->total_deposit;

								$result_array[REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT]['agent'][$post->upline]['total_deposit_amount'] += $post->total_deposit_amount;

							}

							if($post->total_deposit == 1){

								array_push($result_array[REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT]['player'],(array) $post);

								$result_array[REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT]['agent'][$post->upline]['upline'] = $post->upline;

								$result_array[REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT]['agent'][$post->upline]['player_count'] += 1;

								$result_array[REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT]['agent'][$post->upline]['total_deposit'] += $post->total_deposit;

								$result_array[REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT]['agent'][$post->upline]['total_deposit_amount'] += $post->total_deposit_amount;

							}

							if($post->total_deposit == 2){

								array_push($result_array[REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT]['player'],(array) $post);

								$result_array[REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT]['agent'][$post->upline]['upline'] = $post->upline;

								$result_array[REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT]['agent'][$post->upline]['player_count'] += 1;

								$result_array[REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT]['agent'][$post->upline]['total_deposit'] += $post->total_deposit;

								$result_array[REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT]['agent'][$post->upline]['total_deposit_amount'] += $post->total_deposit_amount;

							}

							if($post->total_deposit >= 3){

								array_push($result_array[REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT]['player'],(array) $post);

								$result_array[REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT]['agent'][$post->upline]['upline'] = $post->upline;

								$result_array[REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT]['agent'][$post->upline]['player_count'] += 1;

								$result_array[REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT]['agent'][$post->upline]['total_deposit'] += $post->total_deposit;

								$result_array[REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT]['agent'][$post->upline]['total_deposit_amount'] += $post->total_deposit_amount;

							}

						}

					}



					$objPHPExcel->createSheet();

					$objPHPExcel->setActiveSheetIndex(1);

					$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_register_deposit_rate_report_player'));



					$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_register_deposit_rate_member_deposit'));

					$objPHPExcel->getActiveSheet()->SetCellValue('A2', $this->lang->line('label_no'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('B2', $this->lang->line('label_agent_username'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('C2', $this->lang->line('label_username'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('D2', $this->lang->line('label_register_deposit_rate_deposit_count'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('E2', $this->lang->line('label_register_deposit_rate_register_count'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('F2', $this->lang->line('label_register_deposit_rate_total_deposit_amount'));



		    		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);



			        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);



			        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);



		    		$rowCount = 3;

					$result_count = 1;



					//Reset Total

					$player_count_player = 0;

					$total_deposit_player = 0;

					$total_deposit_amount_player = 0;

					

					$player_count_agent = 0;

					$total_deposit_agent = 0;

					$total_deposit_amount = 0;



					if(isset($result_array[REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT]['player']) && sizeof($result_array[REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT]['player']) > 0){

						$player_count_player = sizeof($result_array[REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT]['player']);

						foreach($result_array[REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT]['player'] as $result_row_player){

							$total_deposit_player += $result_row_player['total_deposit'];

							$total_deposit_amount_player += $result_row_player['total_deposit_amount'];

							$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

							$objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $rowCount, $result_row_player['upline'],PHPExcel_Cell_DataType::TYPE_STRING);

							$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $rowCount, $result_row_player['username'],PHPExcel_Cell_DataType::TYPE_STRING);

							$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, number_format($result_row_player['total_deposit'], 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, 1);

							$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($result_row_player['total_deposit_amount'], 0, '.', ','));

							$result_count++;

							$rowCount++;

						}

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line('label_total'));

					$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, number_format($total_deposit_player, 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, number_format($player_count_player, 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($total_deposit_amount_player, 0, '.', ','));



					$objPHPExcel->getActiveSheet()->getStyle('D3:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('E3:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('F3:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



					$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':F'.$rowCount)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

					$rowCount++;

					$rowCount++;



					$result_count = 1;

					$stop_point = $rowCount;

					$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line('label_no'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $this->lang->line('label_agent_username'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $this->lang->line('label_register_deposit_rate_register_count'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $this->lang->line('label_register_deposit_rate_deposit_count'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $this->lang->line('label_register_deposit_rate_total_deposit_amount'));

		    		$rowCount++;

		    		if(isset($result_array[REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT]['agent']) && sizeof($result_array[REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT]['agent']) > 0){

		    			foreach($result_array[REGISTER_DEPOSIT_RATE_SETTING_ALL_DEPOSIT]['agent'] as $result_row_agent){

		    				$player_count_agent += $result_row_agent['player_count'];

							$total_deposit_agent += $result_row_agent['total_deposit'];

							$total_deposit_amount += $result_row_agent['total_deposit_amount'];



							$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

							$objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $rowCount, $result_row_agent['upline'],PHPExcel_Cell_DataType::TYPE_STRING);

							$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, number_format($result_row_agent['total_deposit'], 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, number_format($result_row_agent['player_count'], 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, number_format($result_row_agent['total_deposit_amount'], 0, '.', ','));

							$result_count++;

							$rowCount++;

		    			}

		    		}

		    		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line('label_total'));

					$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, number_format($total_deposit_agent, 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, number_format($player_count_agent, 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, number_format($total_deposit_amount, 0, '.', ','));



					$objPHPExcel->getActiveSheet()->getStyle('D'.($stop_point+1).':D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('E'.($stop_point+1).':E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('F'.($stop_point+1).':F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);





					$objPHPExcel->getActiveSheet()->getStyle('A'.$stop_point.':E'.$stop_point)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':E'.$rowCount)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));







					//////

					$objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_register_deposit_rate_first_deposit'));

					$objPHPExcel->getActiveSheet()->SetCellValue('H2', $this->lang->line('label_no'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('I2', $this->lang->line('label_agent_username'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('J2', $this->lang->line('label_username'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('K2', $this->lang->line('label_register_deposit_rate_deposit_count'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('L2', $this->lang->line('label_register_deposit_rate_register_count'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('M2', $this->lang->line('label_register_deposit_rate_total_deposit_amount'));



		    		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);



			        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);



			        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setSize(12);



		    		$rowCount = 3;

					$result_count = 1;



					//Reset Total

					$player_count_player = 0;

					$total_deposit_player = 0;

					$total_deposit_amount_player = 0;

					

					$player_count_agent = 0;

					$total_deposit_agent = 0;

					$total_deposit_amount = 0;



					if(isset($result_array[REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT]['player']) && sizeof($result_array[REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT]['player']) > 0){

						$player_count_player = sizeof($result_array[REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT]['player']);

						foreach($result_array[REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT]['player'] as $result_row_player){

							$total_deposit_player += $result_row_player['total_deposit'];

							$total_deposit_amount_player += $result_row_player['total_deposit_amount'];

							$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $result_count);

							$objPHPExcel->getActiveSheet()->setCellValueExplicit('I' . $rowCount, $result_row_player['upline'],PHPExcel_Cell_DataType::TYPE_STRING);

							$objPHPExcel->getActiveSheet()->setCellValueExplicit('J' . $rowCount, $result_row_player['username'],PHPExcel_Cell_DataType::TYPE_STRING);

							$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($result_row_player['total_deposit'], 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, 1);

							$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, number_format($result_row_player['total_deposit_amount'], 0, '.', ','));

							$result_count++;

							$rowCount++;

						}

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $this->lang->line('label_total'));

					$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($total_deposit_player, 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, number_format($player_count_player, 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, number_format($total_deposit_amount_player, 0, '.', ','));



					$objPHPExcel->getActiveSheet()->getStyle('K3:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('L3:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('M3:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



					$objPHPExcel->getActiveSheet()->getStyle('H2:M2')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

					$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount.':M'.$rowCount)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

					$rowCount++;

					$rowCount++;



					$result_count = 1;

					$stop_point = $rowCount;

					$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $this->lang->line('label_no'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $this->lang->line('label_agent_username'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $this->lang->line('label_register_deposit_rate_register_count'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $this->lang->line('label_register_deposit_rate_deposit_count'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $this->lang->line('label_register_deposit_rate_total_deposit_amount'));

		    		$rowCount++;

		    		if(isset($result_array[REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT]['agent']) && sizeof($result_array[REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT]['agent']) > 0){

		    			foreach($result_array[REGISTER_DEPOSIT_RATE_SETTING_FIRST_DEPOSIT]['agent'] as $result_row_agent){

		    				$player_count_agent += $result_row_agent['player_count'];

							$total_deposit_agent += $result_row_agent['total_deposit'];

							$total_deposit_amount += $result_row_agent['total_deposit_amount'];



							$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $result_count);

							$objPHPExcel->getActiveSheet()->setCellValueExplicit('I' . $rowCount, $result_row_agent['upline'],PHPExcel_Cell_DataType::TYPE_STRING);

							$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($result_row_agent['total_deposit'], 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($result_row_agent['player_count'], 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, number_format($result_row_agent['total_deposit_amount'], 0, '.', ','));

							$result_count++;

							$rowCount++;

		    			}

		    		}

		    		$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $this->lang->line('label_total'));

					$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($total_deposit_agent, 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($player_count_agent, 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, number_format($total_deposit_amount, 0, '.', ','));



					$objPHPExcel->getActiveSheet()->getStyle('J'.($stop_point+1).':J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('K'.($stop_point+1).':K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('L'.($stop_point+1).':L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);





					$objPHPExcel->getActiveSheet()->getStyle('H'.$stop_point.':L'.$stop_point)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

					$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount.':L'.$rowCount)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));









					//////

					$objPHPExcel->getActiveSheet()->SetCellValue('O1', $this->lang->line('label_register_deposit_rate_second_or_more_deposit'));

					$objPHPExcel->getActiveSheet()->SetCellValue('O2', $this->lang->line('label_no'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('P2', $this->lang->line('label_agent_username'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('Q2', $this->lang->line('label_username'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('R2', $this->lang->line('label_register_deposit_rate_deposit_count'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('S2', $this->lang->line('label_register_deposit_rate_register_count'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('T2', $this->lang->line('label_register_deposit_rate_total_deposit_amount'));



		    		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(25);



			        $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('R1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('S1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('T1')->getFont()->setBold(true);



			        $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('R1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('S1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('T1')->getFont()->setSize(12);



		    		$rowCount = 3;

					$result_count = 1;



					//Reset Total

					$player_count_player = 0;

					$total_deposit_player = 0;

					$total_deposit_amount_player = 0;

					

					$player_count_agent = 0;

					$total_deposit_agent = 0;

					$total_deposit_amount = 0;



					if(isset($result_array[REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT]['player']) && sizeof($result_array[REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT]['player']) > 0){

						$player_count_player = sizeof($result_array[REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT]['player']);

						foreach($result_array[REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT]['player'] as $result_row_player){

							$total_deposit_player += $result_row_player['total_deposit'];

							$total_deposit_amount_player += $result_row_player['total_deposit_amount'];

							$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $result_count);

							$objPHPExcel->getActiveSheet()->setCellValueExplicit('P' . $rowCount, $result_row_player['upline'],PHPExcel_Cell_DataType::TYPE_STRING);

							$objPHPExcel->getActiveSheet()->setCellValueExplicit('Q' . $rowCount, $result_row_player['username'],PHPExcel_Cell_DataType::TYPE_STRING);

							$objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, number_format($result_row_player['total_deposit'], 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, 1);

							$objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, number_format($result_row_player['total_deposit_amount'], 0, '.', ','));

							$result_count++;

							$rowCount++;

						}

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $this->lang->line('label_total'));

					$objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, number_format($total_deposit_player, 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, number_format($player_count_player, 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, number_format($total_deposit_amount_player, 0, '.', ','));



					$objPHPExcel->getActiveSheet()->getStyle('R3:R'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('S3:S'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('T3:T'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



					$objPHPExcel->getActiveSheet()->getStyle('O2:T2')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

					$objPHPExcel->getActiveSheet()->getStyle('O'.$rowCount.':T'.$rowCount)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

					$rowCount++;

					$rowCount++;



					$result_count = 1;

					$stop_point = $rowCount;

					$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $this->lang->line('label_no'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, $this->lang->line('label_agent_username'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, $this->lang->line('label_register_deposit_rate_register_count'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, $this->lang->line('label_register_deposit_rate_deposit_count'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, $this->lang->line('label_register_deposit_rate_total_deposit_amount'));

		    		$rowCount++;

		    		if(isset($result_array[REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT]['agent']) && sizeof($result_array[REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT]['agent']) > 0){

		    			foreach($result_array[REGISTER_DEPOSIT_RATE_SETTING_SECOND_OR_MORE_DEPOSIT]['agent'] as $result_row_agent){

		    				$player_count_agent += $result_row_agent['player_count'];

							$total_deposit_agent += $result_row_agent['total_deposit'];

							$total_deposit_amount += $result_row_agent['total_deposit_amount'];



							$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $result_count);

							$objPHPExcel->getActiveSheet()->setCellValueExplicit('P' . $rowCount, $result_row_agent['upline'],PHPExcel_Cell_DataType::TYPE_STRING);

							$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, number_format($result_row_agent['total_deposit'], 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, number_format($result_row_agent['player_count'], 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, number_format($result_row_agent['total_deposit_amount'], 0, '.', ','));

							$result_count++;

							$rowCount++;

		    			}

		    		}

		    		$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $this->lang->line('label_total'));

					$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, number_format($total_deposit_agent, 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, number_format($player_count_agent, 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, number_format($total_deposit_amount, 0, '.', ','));



					$objPHPExcel->getActiveSheet()->getStyle('Q'.($stop_point+1).':Q'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('R'.($stop_point+1).':R'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('S'.($stop_point+1).':S'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);





					$objPHPExcel->getActiveSheet()->getStyle('O'.$stop_point.':S'.$stop_point)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

					$objPHPExcel->getActiveSheet()->getStyle('O'.$rowCount.':S'.$rowCount)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));





					//////

					$objPHPExcel->getActiveSheet()->SetCellValue('V1', $this->lang->line('label_register_deposit_rate_third_or_more_deposit'));

					$objPHPExcel->getActiveSheet()->SetCellValue('V2', $this->lang->line('label_no'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('W2', $this->lang->line('label_agent_username'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('X2', $this->lang->line('label_username'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('Y2', $this->lang->line('label_register_deposit_rate_deposit_count'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('Z2', $this->lang->line('label_register_deposit_rate_register_count'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('AA2', $this->lang->line('label_register_deposit_rate_total_deposit_amount'));



		    		$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(10);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(25);

			        $objPHPExcel->getActiveSheet()->getColumnDimension('ZZ')->setWidth(25);



			        $objPHPExcel->getActiveSheet()->getStyle('V1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('W1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('X1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('Y1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('Z1')->getFont()->setBold(true);

			        $objPHPExcel->getActiveSheet()->getStyle('AA1')->getFont()->setBold(true);



			        $objPHPExcel->getActiveSheet()->getStyle('V1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('W1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('X1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('Y1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('Z1')->getFont()->setSize(12);

					$objPHPExcel->getActiveSheet()->getStyle('AA1')->getFont()->setSize(12);



		    		$rowCount = 3;

					$result_count = 1;



					//Reset Total

					$player_count_player = 0;

					$total_deposit_player = 0;

					$total_deposit_amount_player = 0;

					

					$player_count_agent = 0;

					$total_deposit_agent = 0;

					$total_deposit_amount = 0;



					if(isset($result_array[REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT]['player']) && sizeof($result_array[REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT]['player']) > 0){

						$player_count_player = sizeof($result_array[REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT]['player']);

						foreach($result_array[REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT]['player'] as $result_row_player){

							$total_deposit_player += $result_row_player['total_deposit'];

							$total_deposit_amount_player += $result_row_player['total_deposit_amount'];

							$objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, $result_count);

							$objPHPExcel->getActiveSheet()->setCellValueExplicit('W' . $rowCount, $result_row_player['upline'],PHPExcel_Cell_DataType::TYPE_STRING);

							$objPHPExcel->getActiveSheet()->setCellValueExplicit('X' . $rowCount, $result_row_player['username'],PHPExcel_Cell_DataType::TYPE_STRING);

							$objPHPExcel->getActiveSheet()->SetCellValue('Y' . $rowCount, number_format($result_row_player['total_deposit'], 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('Z' . $rowCount, 1);

							$objPHPExcel->getActiveSheet()->SetCellValue('AA' . $rowCount, number_format($result_row_player['total_deposit_amount'], 0, '.', ','));

							$result_count++;

							$rowCount++;

						}

					}

					$objPHPExcel->getActiveSheet()->SetCellValue('W' . $rowCount, $this->lang->line('label_total'));

					$objPHPExcel->getActiveSheet()->SetCellValue('Y' . $rowCount, number_format($total_deposit_player, 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('Z' . $rowCount, number_format($player_count_player, 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('AA' . $rowCount, number_format($total_deposit_amount_player, 0, '.', ','));



					$objPHPExcel->getActiveSheet()->getStyle('Y3:Y'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('Z3:Z'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('AA3:AA'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



					$objPHPExcel->getActiveSheet()->getStyle('V2:AA2')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

					$objPHPExcel->getActiveSheet()->getStyle('V'.$rowCount.':AA'.$rowCount)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

					$rowCount++;

					$rowCount++;



					$result_count = 1;

					$stop_point = $rowCount;

					$objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, $this->lang->line('label_no'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('W' . $rowCount, $this->lang->line('label_agent_username'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('X' . $rowCount, $this->lang->line('label_register_deposit_rate_register_count'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('Y' . $rowCount, $this->lang->line('label_register_deposit_rate_deposit_count'));

		    		$objPHPExcel->getActiveSheet()->SetCellValue('Z' . $rowCount, $this->lang->line('label_register_deposit_rate_total_deposit_amount'));

		    		$rowCount++;

		    		if(isset($result_array[REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT]['agent']) && sizeof($result_array[REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT]['agent']) > 0){

		    			foreach($result_array[REGISTER_DEPOSIT_RATE_SETTING_THIRD_OR_MORE_DEPOSIT]['agent'] as $result_row_agent){

		    				$player_count_agent += $result_row_agent['player_count'];

							$total_deposit_agent += $result_row_agent['total_deposit'];

							$total_deposit_amount += $result_row_agent['total_deposit_amount'];



							$objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, $result_count);

							$objPHPExcel->getActiveSheet()->setCellValueExplicit('W' . $rowCount, $result_row_agent['upline'],PHPExcel_Cell_DataType::TYPE_STRING);

							$objPHPExcel->getActiveSheet()->SetCellValue('X' . $rowCount, number_format($result_row_agent['total_deposit'], 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('Y' . $rowCount, number_format($result_row_agent['player_count'], 0, '.', ','));

							$objPHPExcel->getActiveSheet()->SetCellValue('Z' . $rowCount, number_format($result_row_agent['total_deposit_amount'], 0, '.', ','));

							$result_count++;

							$rowCount++;

		    			}

		    		}

		    		$objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, $this->lang->line('label_total'));

					$objPHPExcel->getActiveSheet()->SetCellValue('X' . $rowCount, number_format($total_deposit_agent, 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('Y' . $rowCount, number_format($player_count_agent, 0, '.', ','));

					$objPHPExcel->getActiveSheet()->SetCellValue('Z' . $rowCount, number_format($total_deposit_amount, 0, '.', ','));



					$objPHPExcel->getActiveSheet()->getStyle('X'.($stop_point+1).':X'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('Y'.($stop_point+1).':Y'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$objPHPExcel->getActiveSheet()->getStyle('Z'.($stop_point+1).':Z'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);





					$objPHPExcel->getActiveSheet()->getStyle('V'.$stop_point.':Z'.$stop_point)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

					$objPHPExcel->getActiveSheet()->getStyle('V'.$rowCount.':Z'.$rowCount)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

					

					$objPHPExcel->setActiveSheetIndex(0);

					$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

			        header("Content-Type: application/vnd.ms-excel");

					header("Content-Disposition: attachment; filename=".$fileName."");

					$objWriter->save("php://output");

				}

			}

		}

	}



	public function register_deposit_rate_yearly_export_check(){

		//Initial output data

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_YEARLY_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_YEARLY_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_register_deposit_rate_yearly');

			if(!empty($arr)){

				if( ! empty($arr['from_year'])){

					$json['status'] = EXIT_SUCCESS;

					$json['msg']['general_error'] = $this->lang->line('successfully_export');

				}

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();

	}



	public function register_deposit_rate_yearly_export(){

		if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_YEARLY_REPORT_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_YEARLY_REPORT) == TRUE)

		{

			$arr = $this->session->userdata('search_register_deposit_rate_yearly');

			$dbprefix = $this->db->dbprefix;

			$where = "";

			$where_win_loss = "";

			if( ! empty($arr['from_year'])){

				if( ! empty($arr['agent']))

				{

					$where = "WHERE P.player_id = 'ABC'";

					$agent = $this->user_model->get_user_data_by_username($arr['agent']);

					if(!empty($agent)){

						$response_upline = $this->user_model->get_downline_data($agent['username']);

						if(!empty($response_upline)){

							$where = "WHERE P.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";

						}

					}

				}else{

					$where = "WHERE P.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";

				}





				if( ! empty($arr['username']))

				{

					$where .= " AND P.username = '" . $arr['username'] . "'";	

				}



				if(isset($arr['from_date']))

				{

					if( ! empty($arr['from_date'])){

						$where .= ' AND P.created_date >= ' . strtotime($arr['from_date']);

					}

				}

				if( ! empty($arr['to_date']))

				{

					if( ! empty($arr['to_date'])){

						$where .= ' AND P.created_date <= ' . strtotime($arr['to_date']);

					}

				}

				

				if($arr['count_deposit'] !== "")

				{

					if($arr['type'] == SELECTION_TYPE_FIXED){

						$where .= " AND P.deposit_count = '" . $arr['count_deposit'] . "'";		

					}else{

						$where .= " AND P.deposit_count > '" . $arr['count_deposit'] . "'";	

					}

				}



				$columns = array(

					'P.player_id',

					'P.username',

					'P.level_id',

					'P.deposit_count',

				);



				$select = implode(',', $columns);

				$posts = NULL;

				$win_loss_posts = NULL;

				$query_string = "SELECT {$select} FROM {$dbprefix}players P $where";

				$query = $this->db->query($query_string);

				if($query->num_rows() > 0)

				{

					$posts = $query->result();

				}

				$query->free_result();



				$data = array();

				$player_list = array();

				$winloss_monthly_list = array();

				$max_level = 0;

				$level_data = array();



				$jan_datetime = strtotime($arr['from_year']."-01-01");

				$feb_datetime = strtotime($arr['from_year']."-02-01");

				$mar_datetime = strtotime($arr['from_year']."-03-01");

				$apr_datetime = strtotime($arr['from_year']."-04-01");

				$may_datetime = strtotime($arr['from_year']."-05-01");

				$jun_datetime = strtotime($arr['from_year']."-06-01");

				$jul_datetime = strtotime($arr['from_year']."-07-01");

				$aug_datetime = strtotime($arr['from_year']."-08-01");

				$sep_datetime = strtotime($arr['from_year']."-09-01");

				$oct_datetime = strtotime($arr['from_year']."-10-01");

				$nov_datetime = strtotime($arr['from_year']."-11-01");

				$dec_datetime = strtotime($arr['from_year']."-12-01");



				$total_data = array(

					'total_register_count' => 0,

					'deposit_value_jan' => 0,

					'win_loss_value_jan' => 0,

					'deposit_value_feb' => 0,

					'win_loss_value_feb' => 0,

					'deposit_value_mar' => 0,

					'win_loss_value_mar' => 0,

					'deposit_value_apr' => 0,

					'win_loss_value_apr' => 0,

					'deposit_value_may' => 0,

					'win_loss_value_may' => 0,

					'deposit_value_jun' => 0,

					'win_loss_value_jun' => 0,

					'deposit_value_jul' => 0,

					'win_loss_value_jul' => 0,

					'deposit_value_aug' => 0,

					'win_loss_value_aug' => 0,

					'deposit_value_sep' => 0,

					'win_loss_value_sep' => 0,

					'deposit_value_oct' => 0,

					'win_loss_value_oct' => 0,

					'deposit_value_nov' => 0,

					'win_loss_value_nov' => 0,

					'deposit_value_dec' => 0,

					'win_loss_value_dec' => 0,

					'deposit_value_total' => 0,

					'win_loss_value_total' => 0,

				);



				if(!empty($posts))

				{

					foreach ($posts as $post)

					{

						$player_list[] = $post->player_id;

						$winloss_monthly_list[$post->player_id][$jan_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);

						$winloss_monthly_list[$post->player_id][$feb_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);

						$winloss_monthly_list[$post->player_id][$mar_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);

						$winloss_monthly_list[$post->player_id][$apr_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);

						$winloss_monthly_list[$post->player_id][$may_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);

						$winloss_monthly_list[$post->player_id][$jun_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);

						$winloss_monthly_list[$post->player_id][$jul_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);

						$winloss_monthly_list[$post->player_id][$aug_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);

						$winloss_monthly_list[$post->player_id][$sep_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);

						$winloss_monthly_list[$post->player_id][$oct_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);

						$winloss_monthly_list[$post->player_id][$nov_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);

						$winloss_monthly_list[$post->player_id][$dec_datetime] = array('deposit_amount' => 0, 'win_loss' => 0);

						$winloss_monthly_list[$post->player_id]['total_deposit_amount'] = 0;

						$winloss_monthly_list[$post->player_id]['total_win_loss_amount'] = 0;

					}



					if(!empty($player_list)){

						$player_ids = '"'.implode('","', $player_list).'"';

						$where_win_loss .= "WHERE player_id IN(" . $player_ids . ")";

						$where_win_loss .= " AND report_date >= ".$jan_datetime;

						$where_win_loss .= " AND report_date <= ".$dec_datetime;

						

						$query_win_loss_string = "SELECT player_id, deposit_count, deposit_amount, win_loss, report_date FROM {$dbprefix}total_win_loss_report_month $where_win_loss";

						$query_win_loss = $this->db->query($query_win_loss_string);

						if($query_win_loss->num_rows() > 0)

						{

							$win_loss_posts = $query_win_loss->result();

							foreach($win_loss_posts as $win_loss_post){

								$winloss_monthly_list[$win_loss_post->player_id][$win_loss_post->report_date]['deposit_amount'] += $win_loss_post->deposit_amount;

								$winloss_monthly_list[$win_loss_post->player_id][$win_loss_post->report_date]['win_loss'] += $win_loss_post->win_loss;

								$winloss_monthly_list[$win_loss_post->player_id]['total_deposit_amount'] += $win_loss_post->deposit_amount;

								$winloss_monthly_list[$win_loss_post->player_id]['total_win_loss_amount'] += $win_loss_post->win_loss;



								$total_data['total_register_count'] += $win_loss_post->deposit_count;

								$total_data['deposit_value_total'] += $win_loss_post->deposit_amount;

								$total_data['win_loss_value_total'] += $win_loss_post->win_loss;

								switch($win_loss_post->report_date)

								{

									case $jan_datetime: $total_data['deposit_value_jan'] += $win_loss_post->deposit_amount; $total_data['win_loss_value_jan'] += $win_loss_post->win_loss; break;

									case $feb_datetime: $total_data['deposit_value_feb'] += $win_loss_post->deposit_amount; $total_data['win_loss_value_feb'] += $win_loss_post->win_loss; break;

									case $mar_datetime: $total_data['deposit_value_mar'] += $win_loss_post->deposit_amount; $total_data['win_loss_value_mar'] += $win_loss_post->win_loss; break;

									case $apr_datetime: $total_data['deposit_value_apr'] += $win_loss_post->deposit_amount; $total_data['win_loss_value_apr'] += $win_loss_post->win_loss; break;

									case $may_datetime: $total_data['deposit_value_may'] += $win_loss_post->deposit_amount; $total_data['win_loss_value_may'] += $win_loss_post->win_loss; break;

									case $jun_datetime: $total_data['deposit_value_jun'] += $win_loss_post->deposit_amount; $total_data['win_loss_value_jun'] += $win_loss_post->win_loss; break;

									case $jul_datetime: $total_data['deposit_value_jul'] += $win_loss_post->deposit_amount; $total_data['win_loss_value_jul'] += $win_loss_post->win_loss; break;

									case $aug_datetime: $total_data['deposit_value_aug'] += $win_loss_post->deposit_amount; $total_data['win_loss_value_aug'] += $win_loss_post->win_loss; break;

									case $sep_datetime: $total_data['deposit_value_sep'] += $win_loss_post->deposit_amount; $total_data['win_loss_value_sep'] += $win_loss_post->win_loss; break;

									case $oct_datetime: $total_data['deposit_value_oct'] += $win_loss_post->deposit_amount; $total_data['win_loss_value_oct'] += $win_loss_post->win_loss; break;

									case $nov_datetime: $total_data['deposit_value_nov'] += $win_loss_post->deposit_amount; $total_data['win_loss_value_nov'] += $win_loss_post->win_loss; break;

									case $dec_datetime: $total_data['deposit_value_dec'] += $win_loss_post->deposit_amount; $total_data['win_loss_value_dec'] += $win_loss_post->win_loss; break;

									default: $other_datetime = "";break;

								}

							}

						}

						$query_win_loss->free_result();

					}

				}



				$fileName = $this->lang->line('title_yearly_report').' - '.date("Y-m-d", time())." ".time().'.xlsx';

				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_register_deposit_rate_yearly_report'));

        		// set Header

        		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->lang->line('label_no'));

        		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->lang->line('label_username'));

        		$objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->lang->line('label_register_deposit_rate_member_deposit'));

		        $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_jan').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_jan').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('F1', $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_feb').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('G1', $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_feb').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('H1', $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_mar').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_mar').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('J1', $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_apr').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('K1', $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_apr').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('L1', $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_may').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('M1', $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_may').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('N1', $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_jun').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('O1', $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_jun').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('P1', $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_jul').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('Q1', $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_jul').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('R1', $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_aug').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('S1', $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_aug').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('T1', $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_sep').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('U1', $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_sep').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('V1', $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_oct').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('W1', $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_oct').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('X1', $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_nov').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('Y1', $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_nov').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('Z1', $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('month_dec').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('AA1', $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('month_dec').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('AB1', $this->lang->line('label_register_deposit_rate_total_deposit_amount')." (".$this->lang->line('label_total').")");

		        $objPHPExcel->getActiveSheet()->SetCellValue('AC1', $this->lang->line('label_register_deposit_rate_total_win_loss_amount')." (".$this->lang->line('label_total').")");



		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(25);



		        // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('R1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('S1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('T1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('U1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('V1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('W1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('X1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('Y1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('Z1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('AA1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('AB1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('AC1')->getFont()->setBold(true);

		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('R1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('S1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('T1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('U1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('V1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('W1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('X1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('Y1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('Z1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('AA1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('AB1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('AC1')->getFont()->setSize(12);



				$rowCount = 2;

				$result_count = 1;



				$styleArrayRed = get_excel_color_status(EXPORT_COLOR_RED);

				$styleArrayBlue = get_excel_color_status(EXPORT_COLOR_BLUE);

				$styleArrayBlack = get_excel_color_status(EXPORT_COLOR_BLACK);



				

				$data = array();

				if(!empty($posts))

				{

					foreach ($posts as $post)

					{

						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $rowCount, $post->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, number_format($post->deposit_count, 0, '.', ','));

						if($post->deposit_count > 0){

							$objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($post->deposit_count < 0){

							$objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$jan_datetime]['deposit_amount'], 0, '.', ','));

						if($winloss_monthly_list[$post->player_id][$jan_datetime]['deposit_amount'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$jan_datetime]['deposit_amount'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$jan_datetime]['win_loss'], 2, '.', ','));

						if($winloss_monthly_list[$post->player_id][$jan_datetime]['win_loss'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$jan_datetime]['win_loss'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$feb_datetime]['deposit_amount'], 0, '.', ','));

						if($winloss_monthly_list[$post->player_id][$feb_datetime]['deposit_amount'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$feb_datetime]['deposit_amount'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$feb_datetime]['win_loss'], 2, '.', ','));

						if($winloss_monthly_list[$post->player_id][$feb_datetime]['win_loss'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$feb_datetime]['win_loss'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$mar_datetime]['deposit_amount'], 0, '.', ','));

						if($winloss_monthly_list[$post->player_id][$mar_datetime]['deposit_amount'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$mar_datetime]['deposit_amount'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$mar_datetime]['win_loss'], 2, '.', ','));

						if($winloss_monthly_list[$post->player_id][$mar_datetime]['win_loss'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$mar_datetime]['win_loss'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$apr_datetime]['deposit_amount'], 0, '.', ','));

						if($winloss_monthly_list[$post->player_id][$apr_datetime]['deposit_amount'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$apr_datetime]['deposit_amount'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$apr_datetime]['win_loss'], 2, '.', ','));

						if($winloss_monthly_list[$post->player_id][$apr_datetime]['win_loss'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$apr_datetime]['win_loss'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$may_datetime]['deposit_amount'], 0, '.', ','));

						if($winloss_monthly_list[$post->player_id][$may_datetime]['deposit_amount'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$may_datetime]['deposit_amount'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$may_datetime]['win_loss'], 2, '.', ','));

						if($winloss_monthly_list[$post->player_id][$may_datetime]['win_loss'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$may_datetime]['win_loss'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$jun_datetime]['deposit_amount'], 0, '.', ','));

						if($winloss_monthly_list[$post->player_id][$jun_datetime]['deposit_amount'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$jun_datetime]['deposit_amount'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$jun_datetime]['win_loss'], 2, '.', ','));

						if($winloss_monthly_list[$post->player_id][$jun_datetime]['win_loss'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('O'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$jun_datetime]['win_loss'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('O'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('O'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$jul_datetime]['deposit_amount'] , 0, '.', ','));

						if($winloss_monthly_list[$post->player_id][$jul_datetime]['deposit_amount']  > 0){

							$objPHPExcel->getActiveSheet()->getStyle('P'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$jul_datetime]['deposit_amount']  < 0){

							$objPHPExcel->getActiveSheet()->getStyle('P'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('P'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$jul_datetime]['win_loss'], 2, '.', ','));

						if($winloss_monthly_list[$post->player_id][$jul_datetime]['win_loss'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('Q'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$jul_datetime]['win_loss'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('Q'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('Q'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$aug_datetime]['deposit_amount'], 0, '.', ','));

						if($winloss_monthly_list[$post->player_id][$aug_datetime]['deposit_amount'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('R'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$aug_datetime]['deposit_amount'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('R'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('R'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$aug_datetime]['win_loss'], 2, '.', ','));

						if($winloss_monthly_list[$post->player_id][$aug_datetime]['win_loss'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('S'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$aug_datetime]['win_loss'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('S'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('S'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$sep_datetime]['deposit_amount'], 0, '.', ','));

						if($winloss_monthly_list[$post->player_id][$sep_datetime]['deposit_amount'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('T'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$sep_datetime]['deposit_amount'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('T'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('T'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('U' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$sep_datetime]['win_loss'], 2, '.', ','));

						if($winloss_monthly_list[$post->player_id][$sep_datetime]['win_loss'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('U'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$sep_datetime]['win_loss'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('U'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('u'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$oct_datetime]['deposit_amount'], 0, '.', ','));

						if($winloss_monthly_list[$post->player_id][$oct_datetime]['deposit_amount'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('V'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$oct_datetime]['deposit_amount'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('V'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('V'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('W' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$oct_datetime]['win_loss'], 2, '.', ','));

						if($winloss_monthly_list[$post->player_id][$oct_datetime]['win_loss'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('W'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$oct_datetime]['win_loss'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('W'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('W'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('X' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$nov_datetime]['deposit_amount'], 0, '.', ','));

						if($winloss_monthly_list[$post->player_id][$nov_datetime]['deposit_amount'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('X'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$nov_datetime]['deposit_amount'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('X'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('X'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('Y' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$nov_datetime]['win_loss'], 2, '.', ','));

						if($winloss_monthly_list[$post->player_id][$nov_datetime]['win_loss'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('Y'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$nov_datetime]['win_loss'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('Y'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('Y'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('Z' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$dec_datetime]['deposit_amount'], 0, '.', ','));

						if($winloss_monthly_list[$post->player_id][$dec_datetime]['deposit_amount'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('Z'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$dec_datetime]['deposit_amount'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('Z'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('Z'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('AA' . $rowCount, number_format($winloss_monthly_list[$post->player_id][$dec_datetime]['win_loss'], 2, '.', ','));

						if($winloss_monthly_list[$post->player_id][$dec_datetime]['win_loss'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('AA'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id][$dec_datetime]['win_loss'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('AA'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('AA'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('AB' . $rowCount, number_format($winloss_monthly_list[$post->player_id]['total_deposit_amount'], 0, '.', ','));

						if($winloss_monthly_list[$post->player_id]['total_deposit_amount'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('AB'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id]['total_deposit_amount'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('AB'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('AB'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$objPHPExcel->getActiveSheet()->SetCellValue('AC' . $rowCount, number_format($winloss_monthly_list[$post->player_id]['total_win_loss_amount'], 2, '.', ','));

						if($winloss_monthly_list[$post->player_id]['total_win_loss_amount'] > 0){

							$objPHPExcel->getActiveSheet()->getStyle('AC'.$rowCount)->applyFromArray($styleArrayBlue);

						}else if($winloss_monthly_list[$post->player_id]['total_win_loss_amount'] < 0){

							$objPHPExcel->getActiveSheet()->getStyle('AC'.$rowCount)->applyFromArray($styleArrayRed);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle('AC'.$rowCount)->applyFromArray($styleArrayBlack);

						}



						$rowCount++;

           				$result_count++;

					}

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $this->lang->line('label_total'));



				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, number_format($total_data['total_register_count'], 0, '.', ','));

				if($total_data['total_register_count'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['total_register_count'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, number_format($total_data['deposit_value_jan'], 0, '.', ','));

				if($total_data['deposit_value_jan'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['deposit_value_jan'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, number_format($total_data['win_loss_value_jan'], 2, '.', ','));

				if($total_data['win_loss_value_jan'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['win_loss_value_jan'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, number_format($total_data['deposit_value_feb'], 0, '.', ','));

				if($total_data['deposit_value_feb'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['deposit_value_feb'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, number_format($total_data['win_loss_value_feb'], 2, '.', ','));

				if($total_data['win_loss_value_feb'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['win_loss_value_feb'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, number_format($total_data['deposit_value_mar'], 0, '.', ','));

				if($total_data['deposit_value_mar'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['deposit_value_mar'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, number_format($total_data['win_loss_value_mar'], 2, '.', ','));

				if($total_data['win_loss_value_mar'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['win_loss_value_mar'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, number_format($total_data['deposit_value_apr'], 0, '.', ','));

				if($total_data['deposit_value_apr'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['deposit_value_apr'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleArrayBlack);

				}





				$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, number_format($total_data['win_loss_value_apr'], 2, '.', ','));

				if($total_data['win_loss_value_apr'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['win_loss_value_apr'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleArrayBlack);

				}





				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, number_format($total_data['deposit_value_may'], 0, '.', ','));

				if($total_data['deposit_value_may'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['deposit_value_may'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, number_format($total_data['win_loss_value_may'], 2, '.', ','));

				if($total_data['win_loss_value_may'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['win_loss_value_may'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('M'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, number_format($total_data['deposit_value_jun'], 0, '.', ','));

				if($total_data['deposit_value_jun'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['deposit_value_jun'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('N'.$rowCount)->applyFromArray($styleArrayBlack);

				}





				$objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, number_format($total_data['win_loss_value_jun'], 2, '.', ','));

				if($total_data['win_loss_value_jun'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('O'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['win_loss_value_jun'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('O'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('O'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, number_format($total_data['deposit_value_jul'], 0, '.', ','));

				if($total_data['deposit_value_jul'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('P'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['deposit_value_jul'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('P'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('P'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, number_format($total_data['win_loss_value_jul'], 2, '.', ','));

				if($total_data['win_loss_value_jul'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('Q'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['win_loss_value_jul'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('Q'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('Q'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, number_format($total_data['deposit_value_aug'], 0, '.', ','));

				if($total_data['deposit_value_aug'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('R'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['deposit_value_aug'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('R'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('R'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, number_format($total_data['win_loss_value_aug'], 2, '.', ','));

				if($total_data['win_loss_value_aug'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('S'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['win_loss_value_aug'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('S'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('S'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, number_format($total_data['deposit_value_sep'], 0, '.', ','));

				if($total_data['deposit_value_sep'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('T'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['deposit_value_sep'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('T'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('T'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('U' . $rowCount, number_format($total_data['win_loss_value_sep'], 2, '.', ','));

				if($total_data['win_loss_value_sep'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('U'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['win_loss_value_sep'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('U'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('U'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, number_format($total_data['deposit_value_oct'], 0, '.', ','));

				if($total_data['deposit_value_oct'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('V'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['deposit_value_oct'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('V'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('V'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('W' . $rowCount, number_format($total_data['win_loss_value_oct'], 2, '.', ','));

				if($total_data['win_loss_value_oct'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('W'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['win_loss_value_oct'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('W'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('W'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('X' . $rowCount, number_format($total_data['deposit_value_nov'], 0, '.', ','));

				if($total_data['deposit_value_nov'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('X'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['deposit_value_nov'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('X'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('X'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('Y' . $rowCount, number_format($total_data['win_loss_value_nov'], 2, '.', ','));

				if($total_data['win_loss_value_nov'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('Y'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['win_loss_value_nov'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('Y'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('Y'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('Z' . $rowCount, number_format($total_data['deposit_value_dec'], 0, '.', ','));

				if($total_data['deposit_value_dec'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('Z'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['deposit_value_dec'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('Z'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('Z'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('AA' . $rowCount, number_format($total_data['win_loss_value_dec'], 2, '.', ','));

				if($total_data['win_loss_value_dec'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('AA'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['win_loss_value_dec'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('AA'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('AA'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('AB' . $rowCount, number_format($total_data['deposit_value_total'], 0, '.', ','));

				if($total_data['deposit_value_total'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('AB'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['deposit_value_total'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('AB'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('AB'.$rowCount)->applyFromArray($styleArrayBlack);

				}



				$objPHPExcel->getActiveSheet()->SetCellValue('AC' . $rowCount, number_format($total_data['win_loss_value_total'], 2, '.', ','));

				if($total_data['win_loss_value_total'] > 0){

					$objPHPExcel->getActiveSheet()->getStyle('AC'.$rowCount)->applyFromArray($styleArrayBlue);

				}else if($total_data['win_loss_value_total'] < 0){

					$objPHPExcel->getActiveSheet()->getStyle('AC'.$rowCount)->applyFromArray($styleArrayRed);

				}else{

					$objPHPExcel->getActiveSheet()->getStyle('AC'.$rowCount)->applyFromArray($styleArrayBlack);

				}

			}

			$objPHPExcel->getActiveSheet()->getStyle('A1:AC1')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

			$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':AC'.$rowCount)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => "B7DEE8")));

			$rowCount++;

			$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('P1:P'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('Q1:Q'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('R1:R'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('S1:S'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('T1:T'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('U1:U'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('V1:V'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('W1:W'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('X1:X'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('Y1:Y'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('Z1:Z'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('AA1:AA'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('AB1:AB'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->getActiveSheet()->getStyle('AC1:AC'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);





			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

	        header("Content-Type: application/vnd.ms-excel");

			header("Content-Disposition: attachment; filename=".$fileName."");

			$objWriter->save("php://output");

		}

	}



	public function player_agent_report_export_check(){

		//Initial output data

		$json = array(

			'status' => EXIT_ERROR, 

			'msg' => array(

				'general_error' => $this->lang->line('error_export_not_allow')

			),

			'csrfTokenName' => $this->security->get_csrf_token_name(), 

			'csrfHash' => $this->security->get_csrf_hash()

		);

		if(permission_validation(PERMISSION_PLAYER_LIST_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_PLAYER_AGENT_VIEW) == TRUE)

		{

			$arr = $this->session->userdata('search_players_agent');

			if(!empty($arr)){

				$json['status'] = EXIT_SUCCESS;

				$json['msg']['general_error'] = $this->lang->line('successfully_export');

			}

		}

		//Output

		$this->output

				->set_status_header(200)

				->set_content_type('application/json', 'utf-8')

				->set_output(json_encode($json))

				->_display();

				

		exit();

	}



	public function player_agent_report_export(){

		if(permission_validation(PERMISSION_PLAYER_LIST_EXPORT_EXCEL) == TRUE && permission_validation(PERMISSION_PLAYER_AGENT_VIEW) == TRUE)

		{

			$arr = $this->session->userdata('search_players_agent');

			$arr['executed_by'] = $this->session->userdata('username');

			if(TELEGRAM_STATUS == STATUS_ACTIVE){

				send_logs_telegram(TELEGRAM_LOGS,TELEGRAM_LOGS_TYPE_PLAYER_AGENT_LIST_EXPORT,$arr);

			}

			$limit = trim($this->input->post('length', TRUE));

			$start = trim($this->input->post("start", TRUE));

			$order = $this->input->post("order", TRUE);

			if(!empty($arr)){

				//Table Columns

				$columns = array( 

					0 => 'a.player_id',

					1 => 'a.username',

					2 => 'a.upline',

					3 => 'a.points',

					4 => 'a.active',

					5 => 'a.referrer',

					6 => 'a.created_date',

					7 => 'a.mobile',

					8 => 'a.line_id',

					9 => 'a.bank_account_name',

				);



				$col = 0;

				$dir = "";

				

				if( ! empty($order))

				{

					foreach($order as $o)

					{

						$col = $o['column'];

						$dir = $o['dir'];

					}

				}



				if($dir != "asc" && $dir != "desc")

				{

					$dir = "desc";

				}

				

				if( ! isset($columns[$col]))

				{

					$order = $columns[0];

				}

				else

				{

					$order = $columns[$col];

				}

			

				if( ! empty($arr['agent']))

				{

					$where = "WHERE player_id = 'ABC'";

					$agent = $this->user_model->get_user_data_by_username($arr['agent']);

					if(!empty($agent)){

						$response_upline = $this->user_model->get_downline_data($agent['username']);

						if(!empty($response_upline)){

							$where = "WHERE a.upline_ids LIKE '%," . $response_upline['user_id'] . ",%' ESCAPE '!'";

						}

					}

				}else{

					$where = "WHERE a.upline_ids LIKE '%," . $this->session->userdata('root_user_id') . ",%' ESCAPE '!'";

				}



				if(isset($arr['from_date']))

				{

					if( ! empty($arr['from_date'])){

						$where .= ' AND a.created_date >= ' . strtotime($arr['from_date']);

					}

				}

				if( ! empty($arr['to_date']))

				{

					if( ! empty($arr['to_date'])){

						$where .= ' AND a.created_date <= ' . strtotime($arr['to_date']);

					}

				}



				if( ! empty($arr['upline']))

				{

					$where .= " AND a.upline LIKE '%" . $arr['upline'] . "%' ESCAPE '!'";	

				}

				

				if( ! empty($arr['username']))

				{

					$where .= " AND a.username LIKE '%" . $arr['username'] . "%' ESCAPE '!'";	

				}



				if( ! empty($arr['referrer']))

				{

					$where .= ' AND a.referrer = "' . $arr['referrer'].'"';

				}

				

				if($arr['status'] == STATUS_ACTIVE OR $arr['status'] == STATUS_SUSPEND)

				{

					$where .= ' AND a.active = ' . $arr['status'];

				}

				

				$select = implode(',', $columns);

				$dbprefix = $this->db->dbprefix;

				

				$posts = NULL;

				$query_string = "SELECT {$select} FROM {$dbprefix}players a $where";

				$query_string_2 = " ORDER by {$order} {$dir}";

				$query = $this->db->query($query_string . $query_string_2);



				if($query->num_rows() > 0)

				{

					$posts = $query->result();  

				}

				

				$query->free_result();





				

				$fileName = $this->lang->line('title_player_agent').' - '.date("Y-m-d", time())." ".time().'.xlsx';



				$objPHPExcel = new PHPExcel();

        		$objPHPExcel->setActiveSheetIndex(0);

        		$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('title_player_agent'));



        		if(permission_validation(PERMISSION_PLAYER_ACCOUNT_NAME) == TRUE){

					if(permission_validation(PERMISSION_PLAYER_MOBILE) == TRUE){

						if(permission_validation(PERMISSION_PLAYER_LINE_ID) == TRUE){

						

						}else{

							$objPHPExcel->getActiveSheet()->removeColumn('H');

						}

					}else{

						$objPHPExcel->getActiveSheet()->removeColumn('G');

						if(permission_validation(PERMISSION_PLAYER_LINE_ID) == TRUE){

						

						}else{

							$objPHPExcel->getActiveSheet()->removeColumn('G');

						}

					}

				}else{

					$objPHPExcel->getActiveSheet()->removeColumn('E');

					if(permission_validation(PERMISSION_PLAYER_MOBILE) == TRUE){

						if(permission_validation(PERMISSION_PLAYER_LINE_ID) == TRUE){

						

						}else{

							$objPHPExcel->getActiveSheet()->removeColumn('G');

						}

					}else{

						$objPHPExcel->getActiveSheet()->removeColumn('F');

						if(permission_validation(PERMISSION_PLAYER_LINE_ID) == TRUE){

						

						}else{

							$objPHPExcel->getActiveSheet()->removeColumn('F');

						}

					}

				}



				$input_array = array(

					1 => "A",

					2 => "B",

					3 => "C",

					4 => "D",

					5 => "E",

					6 => "F",

					7 => "G",

					8 => "H",

					9 => "I",

					10 => "J",

					11 => "K",

					12 => "L",

					13 => "M",

					14 => "N",

					15 => "O",

					16 => "P",

				);



				$index = 0;

        		$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_no'));

        		$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_username'));

		        $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_upline'));

		        $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_main_wallet'));

		        if(permission_validation(PERMISSION_PLAYER_ACCOUNT_NAME) == TRUE){

		        	$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_bank_account_name'));

		       	}

		        if(permission_validation(PERMISSION_PLAYER_MOBILE) == TRUE){

		        	$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_mobile'));

		       	}

		        if(permission_validation(PERMISSION_PLAYER_LINE_ID) == TRUE){

		        	$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('im_line'));

		        }

		        $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_referrer'));

		        $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_status'));

		        $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].'1', $this->lang->line('label_registered_date'));

		        //set cell width

		        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);

		        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);

		         // Set fonts style

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);

		        $objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);

		        // Set fonts size

		        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setSize(12);

				$objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setSize(12);





				$rowCount = 2;

				$result_count = 1;



				$styleArrayRed = get_excel_color_status(EXPORT_COLOR_RED);

				$styleArrayBlue = get_excel_color_status(EXPORT_COLOR_BLUE);

				$styleArrayBlack = get_excel_color_status(EXPORT_COLOR_BLACK);



				$data = array();

				if(!empty($posts))

				{

					foreach ($posts as $post)

					{

						$index = 0;

						$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index]. $rowCount, $result_count);

						$objPHPExcel->getActiveSheet()->setCellValueExplicit($input_array[++$index]. $rowCount, $post->username,PHPExcel_Cell_DataType::TYPE_STRING);

						$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index]. $rowCount, $post->upline);

						$index++;

						$objPHPExcel->getActiveSheet()->SetCellValue($input_array[$index]. $rowCount, number_format($post->points, 0, '.', ','));

						if($post->points > 0){

							$objPHPExcel->getActiveSheet()->getStyle($input_array[$index].$rowCount)->applyFromArray($styleArrayBlue);

						}else{

							$objPHPExcel->getActiveSheet()->getStyle($input_array[$index].$rowCount)->applyFromArray($styleArrayBlack);

						}

						if(permission_validation(PERMISSION_PLAYER_ACCOUNT_NAME) == TRUE){

							$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].$rowCount, $post->bank_account_name);

						}

						if(permission_validation(PERMISSION_PLAYER_MOBILE) == TRUE){

							$objPHPExcel->getActiveSheet()->setCellValueExplicit($input_array[++$index].$rowCount, $post->mobile,PHPExcel_Cell_DataType::TYPE_STRING);

						}

						if(permission_validation(PERMISSION_PLAYER_LINE_ID) == TRUE){

							$objPHPExcel->getActiveSheet()->setCellValueExplicit($input_array[++$index].$rowCount, $post->line_id,PHPExcel_Cell_DataType::TYPE_STRING);

						}

						$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index]. $rowCount, $post->referrer);

						switch($post->active)

						{

							case STATUS_ACTIVE: $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].$rowCount, $this->lang->line('status_active')); break;

							default: $objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index].$rowCount, $this->lang->line('status_suspend')); break;

						}

						$objPHPExcel->getActiveSheet()->SetCellValue($input_array[++$index]. $rowCount, date('Y-m-d H:i:s', $post->created_date));

						$rowCount++;

           				$result_count++;

					}

				}

				

				$rowCount++;



				//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		        header("Content-Type: application/vnd.ms-excel");

				header("Content-Disposition: attachment; filename=".$fileName."");

				$objWriter->save("php://output");

			}

		}

	}

}
