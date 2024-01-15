<?php $this->load->view('web/parts/header');?>
<style>
.tableBank tr, td, th {
    border: 1px solid rgba(255,255,255,0.2);
    text-align: center;
    font-size: 0.875rem;
}
</style>
	<div class="container-fluid mainbanner p-0" style="background:#f8f9fe;">
		<div class="container p-0">
			<div class="row no-gutters">
				<div class="col-12">
					<div class="sub-about-wrap">
						<?php $this->load->view('web/parts/side_menu');?>
						<div class="sub-about-right">
							<div class="sub-about-title">
								<h2 class="m-2"><?php echo $this->lang->line('page_banking_info_1');?></h2>
							</div>
							<div class="sub-about-con">
								<p><?php echo $this->lang->line('page_banking_info_content_1');?></p>
								<?php /*
								<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableBank">
								   <tbody>
							       <tr>
									  <th rowspan="2"><?php echo $this->lang->line('page_banking_info_table_bank_name');?></th>
									  <th rowspan="2"><?php echo $this->lang->line('page_banking_info_table_status');?></th>
									  <th rowspan="2"><?php echo $this->lang->line('page_banking_info_table_transaction');?></th>
									  <th rowspan="2"><?php echo $this->lang->line('page_banking_info_table_banking_method');?></th>
									  <th colspan="2"><?php echo $this->lang->line('page_banking_info_table_transaction_limit');?></th>
									  <th rowspan="2"><?php echo $this->lang->line('page_banking_info_table_processing_time');?></th>
								   </tr>
								   <tr>
									  <th><?php echo $this->lang->line('page_banking_info_table_min');?></th>
									  <th><?php echo $this->lang->line('page_banking_info_table_max');?></th>
								   </tr>
								   <tr>
									  <!--td rowspan="2"><?php echo $this->lang->line('page_banking_info_table_bank_name_1');?></td-->
									  <td rowspan="2">---</td>
									  <td rowspan="2"><?php echo $this->lang->line('page_banking_info_table_status_1');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_1');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_banking_method_1');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_limit_min_1');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_limit_max_1');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_processing_time_1');?></td>
								   </tr>
								   <tr>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_2');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_banking_method_2');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_limit_min_2');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_limit_max_2');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_processing_time_2');?></td>
								   </tr>
								   <tr>
									  <!--td rowspan="2"><?php echo $this->lang->line('page_banking_info_table_bank_name_2');?></td-->
									  <td rowspan="2">---</td>
									  <td rowspan="2"><?php echo $this->lang->line('page_banking_info_table_status_2');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_3');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_banking_method_3');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_limit_min_3');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_limit_max_3');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_processing_time_3');?></td>
								   </tr>
								   <tr>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_4');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_banking_method_4');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_limit_min_4');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_limit_max_4');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_processing_time_4');?></td>
								   </tr>
								   <tr>
									  <!--td rowspan="2"><?php echo $this->lang->line('page_banking_info_table_bank_name_3');?></td-->
									  <td rowspan="2">---</td>
									  <td rowspan="2"><?php echo $this->lang->line('page_banking_info_table_status_3');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_5');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_banking_method_5');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_limit_min_5');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_limit_max_5');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_processing_time_5');?></td>
								   </tr>
								   <tr>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_6');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_banking_method_6');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_limit_min_6');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_transaction_limit_max_6');?></td>
									  <td><?php echo $this->lang->line('page_banking_info_table_processing_time_6');?></td>
								   </tr>
								</tbody></table>
								<br>								*/ ?>
								<h3><?php echo $this->lang->line('page_banking_info_2');?></h3>
								
								<li><?php echo $this->lang->line('page_banking_info_content_2');?></li>

								<li><?php echo $this->lang->line('page_banking_info_content_3');?></li>

								<li><?php echo $this->lang->line('page_banking_info_content_4');?></li>

								<li><?php echo $this->lang->line('page_banking_info_content_5');?></li>

								<li><?php echo $this->lang->line('page_banking_info_content_6');?></li>

								<li><?php echo $this->lang->line('page_banking_info_content_7');?></li>

								<li><?php echo $this->lang->line('page_banking_info_content_8');?></li>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php $this->load->view('web/parts/footer');?>