<?php $this->load->view('mobile/parts/header');?>
<style>
	header{height: 40px !important;box-shadow: 0 5px 10px #ccd7e6 !important;color:#6c6e71 !important;background:#fff !important;}
	.lr{display:none;}
	.m-bottom-menu{border-top:none;box-shadow: 0 -5px 8px -3px #ccd7e6;}
	.profile_title{text-align:center;line-height:40px;font-size:19px;}

	#comm-back-button {
		background: url(<?php echo base_url('assets/mobile/images/icon-back.png'); ?>) no-repeat;
		z-index: 99;
		width: 23px;
		height: 23px;
		background-size: 50%;
		position: absolute;
		left: 6px;
		top: 10px;
		filter: invert(60%);
	}

</style>

<header id="common-header" class="common-header" style="display: block;">
	<div class="cont relative profile_title">
		<a href="<?php echo site_url(); ?>"><div id="comm-back-button" class="left-button cursor_pointer" style=""></div></a>
		<div class="text-uppercase"><?php echo $this->uri->segment(3);?> <?php echo $this->lang->line('page_banking_info_1');?></div>
	</div>
</header>

<div class="main-wrap" style="margin-top: 60px;">
	<div class="divprofile infos">
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
		<br>
		*/ ?>
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


<?php $this->load->view('mobile/parts/footer');?>