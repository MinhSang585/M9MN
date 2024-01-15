<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="<?php echo get_language_code('iso');?>">
<head>
	<?php $this->load->view('parts/head_meta');?>
</head>
<body>
	<div class="wrapper">
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid mt-2">
				<div class="row">
					<!-- left column -->
					<div class="col-12">
						<!-- jquery validation -->
						<div class="card card-primary">
							<!-- form start -->
							<?php echo form_open('promotion/submit', array('id' => 'promotion-form', 'name' => 'promotion-form', 'class' => 'form-horizontal'));?>
							<div class="card-body row">
								<div class="col-md-6 col-12">
									<div class="card card-primary">
										<div class="card-header">
											<h3 class="card-title"><?php echo $this->lang->line('label_promotion_general_setting');?></h3>
										</div>
										<div class="card-body">
											<div class="form-group row">
												<label for="promotion_name" class="col-5 col-form-label"><?php echo $this->lang->line('label_promotion_name');?></label>
												<div class="col-7">
													<input type="text" class="form-control" id="promotion_name" name="promotion_name" value="">
												</div>
											</div>
											<div class="form-group row">
												<label for="url_path" class="col-5 col-form-label"><?php echo $this->lang->line('label_url');?></label>
												<div class="col-7">
													<input type="text" class="form-control" id="url_path" name="url_path" value="">
												</div>
											</div>
											<div class="form-group row">
												<label for="promotion_seq" class="col-5 col-form-label"><?php echo $this->lang->line('label_sequence');?></label>
												<div class="col-7">
													<input type="text" class="form-control col-3" id="promotion_seq" name="promotion_seq" value="" maxlength="3">
												</div>
											</div>
											<div class="form-group row">
												<label for="promotion_genre_id" class="col-5 col-form-label"><?php echo $this->lang->line('label_type');?></label>
												<div class="col-7">
													<select class="form-control select2bs4" id="genre_code" name="genre_code">
														<?php
															if(!empty($promotion_genre_list)){
																foreach($promotion_genre_list as $row_promotion_genre_list)
																{
																	
																	echo '<option value="' . $row_promotion_genre_list['genre_code'] . '">' . $this->lang->line($row_promotion_genre_list['genre_name']) . '</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label for="date_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_date_type');?></label>
												<div class="col-7">
													<select class="form-control select2bs4" id="date_type" name="date_type">
														<option value=""><?php echo $this->lang->line('place_holder_select_date_type');?></option>
														<?php
															$get_promotion_date_type = get_promotion_date_type();
															foreach(get_promotion_date_type() as $k => $v)
															{
																echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-group row" id="start_date_div" style="display: none;">
												<label for="start_date" class="col-5 col-form-label"><?php echo $this->lang->line('label_start_date');?></label>
												<div class="col-7 input-group date" id="start_date_click" data-target-input="nearest">
													<input type="text" id="start_date" name="start_date" class="form-control col-12 datetimepicker-input" value="<?php echo date('Y-m-d 00:00:00');?>" data-target="#start_date_click"/>
													<div class="input-group-append" data-target="#start_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>
											<div class="form-group row" id="end_date_div" style="display: none;">
												<label for="end_date" class="col-5 col-form-label"><?php echo $this->lang->line('label_end_date');?></label>
												<div class="col-7 input-group date" id="end_date_click" data-target-input="nearest">
													<input type="text" id="end_date" name="end_date" class="form-control col-12 datetimepicker-input" value="<?php echo date('Y-m-d 23:59:59');?>" data-target="#end_date_click"/>
													<div class="input-group-append" data-target="#end_date_click" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
													</div>
												</div>
											</div>
											<div class="form-group row">
												<label for="is_count_total_today" class="col-5 col-form-label"><?php echo $this->lang->line('label_is_count_total_today');?></label>
												<div class="col-7">
													<input type="checkbox" id="is_count_total_today" name="is_count_total_today" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
												</div>
											</div>
											<div class="form-group row">
												<label for="reward_on_apply" class="col-5 col-form-label"><?php echo $this->lang->line('label_reward_on_apply');?></label>
												<div class="col-7">
													<input type="checkbox" id="reward_on_apply" name="reward_on_apply" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
												</div>
											</div>
											<div class="form-group row">
												<label for="withdrawal_on_check" class="col-5 col-form-label"><?php echo $this->lang->line('label_withdrawal_check');?></label>
												<div class="col-7">
													<input type="checkbox" id="withdrawal_on_check" name="withdrawal_on_check" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
												</div>
											</div>
											<div class="form-group row">
												<label for="is_auto_complete" class="col-5 col-form-label"><?php echo $this->lang->line('label_is_auto_complete');?></label>
												<div class="col-7">
													<input type="checkbox" id="is_auto_complete" name="is_auto_complete" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
												</div>
											</div>
											<div class="form-group row">
												<label for="is_banner" class="col-5 col-form-label"><?php echo $this->lang->line('label_is_banner');?></label>
												<div class="col-7">
													<input type="checkbox" id="is_banner" name="is_banner" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
												</div>
											</div>
											<div class="form-group row">
												<label for="banner_category" class="col-5 col-form-label"><?php echo $this->lang->line('label_banner_category');?></label>
												<div class="col-7">
													<select class="select2 col-12" id="banner_category" name="banner_category[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
														<?php
															foreach(get_game_type() as $k => $v)
															{
																echo '<option value="' . $k . '" '.$select.'>' . $this->lang->line($v) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label for="level" class="col-5 col-form-label"><?php echo $this->lang->line('label_promotion_level');?></label>
												<div class="col-7">
													<select class="select2bs4 col-7 form-control " id="level" name="level" data-placeholder="<?php echo $this->lang->line('place_holder_select_promotion_level');?>">
														<?php 
															$get_promotion_level = get_promotion_level();
															if(isset($get_promotion_level) && sizeof($get_promotion_level)>0){
																foreach($get_promotion_level as $k => $v)
																{
																	echo '<option value="' . $k . '">' . $v . '</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label for="is_level" class="col-5 col-form-label"><?php echo $this->lang->line('label_level');?></label>
												<div class="col-7">
													<input type="checkbox" id="is_level" name="is_level" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
												</div>
											</div>
											<div class="form-group row">
												<label for="accumulate_deposit" class="col-5 col-form-label"><?php echo $this->lang->line('label_accumulate_deposit');?></label>
												<div class="col-7">
													<input type="text" class="form-control" id="accumulate_deposit" name="accumulate_deposit" value="">
												</div>
											</div>
											<div class="form-group row">
												<label for="is_deposit_tied_promotion_count" class="col-5 col-form-label"><?php echo $this->lang->line('label_deposit_tied_promotion_count');?></label>
												<div class="col-7">
													<input type="checkbox" id="is_deposit_tied_promotion_count" name="is_deposit_tied_promotion_count" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
												</div>
											</div>
											<div class="form-group row">
												<label for="balance_less" class="col-5 col-form-label"><?php echo $this->lang->line('label_balance_less');?></label>
												<div class="col-7">
													<input type="text" class="form-control" id="balance_less" name="balance_less" value="">
												</div>
											</div>
											<div class="form-group row">
												<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?></label>
												<div class="col-7">
													<input type="checkbox" id="active" name="active" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-12">
									<div class="card card-warning">
										<div class="card-header">
											<h3 class="card-title"><?php echo $this->lang->line('label_promotion_timing_setting');?></h3>
										</div>
										<div class="card-body">
											<div class="form-group row">
												<label for="apply_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_apply_type');?></label>
												<div class="form-group col-12">
													<div class="form-group row">
														<?php
															$get_apply_type = get_apply_type();
															if(isset($get_apply_type) && sizeof($get_apply_type)>0){
																foreach($get_apply_type as $k => $v)
																{
														?>
														<div class="form-group clearfix col-4">
															<div class="custom-control custom-checkbox d-inline">
																<input type="checkbox" class="apply_type_<?php echo $k;?>" id="apply_type_<?php echo $k;?>" name="apply_type[]" value="<?php echo $k;?>" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
																<label for="apply_type_<?php echo $k;?>" class="col-form-label"><?php echo $this->lang->line($v);?></label>
															</div>
														</div>
														<?php
																}
															}
														?>
													</div>
												</div>
											</div>
											<div class="form-group row">
												<label for="apply_allow_date" class="col-5 col-form-label"><?php echo $this->lang->line('label_apply_allow_date');?></label>
												<div class="form-group col-7">
													<select class="select2 col-12" id="apply_allow_date" name="apply_allow_date[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
														<?php
															foreach(get_promotion_apply_date() as $k => $v)
															{
																echo '<option value="' . $k . '" '.$select.'>' . $this->lang->line($v) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label for="apply_allow_date" class="col-5 col-form-label"><?php echo $this->lang->line('label_apply_allow_date_number');?></label>
												<div class="form-group col-7">
													<select class="select2 col-12" id="apply_allow_date_number" name="apply_allow_date_number[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
														<?php
															for($i=1;$i<=31;$i++){
																echo '<option value="' . $i . '">' . $i . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-group row" id="date_expirate_type">
												<label for="date_expirate_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_date_expirate_type');?></label>
												<div class="col-7">
													<select class="form-control select2bs4" id="date_expirate_type" name="date_expirate_type">
														<option value=""><?php echo $this->lang->line('place_holder_select_date_expirate_type');?></option>
														<?php
															$get_expirate_type = get_expirate_type();
															if(isset($get_expirate_type) && sizeof($get_expirate_type)>0){
																foreach($get_expirate_type as $k => $v)
																{
																	echo '<option value="' . $k . '">' . $v . '</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-group row" id="times_limit_type">
												<label for="times_limit_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_times_limit_type');?></label>
												<div class="col-7">
													<select class="form-control select2bs4" id="times_limit_type" name="times_limit_type">
														<option value=""><?php echo $this->lang->line('place_holder_select_times_limit_type');?></option>
														<?php
															$get_times_limit_type = get_times_limit_type();
															if(isset($get_times_limit_type) && sizeof($get_times_limit_type)>0){
																foreach($get_times_limit_type as $k => $v)
																{
																	echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
																}
															}
														?>
													</select>
												</div>
											</div>

											<div class="form-group row">
												<label for="is_apply_on_first_day_of_times_limit_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_apply_on_first_day_of_times_limit_type');?></label>
												<div class="col-7">
													<input type="checkbox" id="is_apply_on_first_day_of_times_limit_type" name="is_apply_on_first_day_of_times_limit_type" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
												</div>
											</div>

											<div class="form-group row">
												<label for="is_starting_of_the_day" class="col-5 col-form-label"><?php echo $this->lang->line('label_starting_of_the_day');?></label>
												<div class="col-7">
													<input type="checkbox" id="is_starting_of_the_day" name="is_starting_of_the_day" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
												</div>
											</div>



											<div class="form-group row" id="claim_type_div" style="display: none;">
												<label for="claim_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_claim_type');?></label>
												<div class="col-7">
													<select class="form-control select2bs4" id="claim_type" name="claim_type">
														<option value=""><?php echo $this->lang->line('place_holder_select_claim_type');?></option>
														<?php
															$get_claim_type = get_claim_type();
															if(isset($get_claim_type) && sizeof($get_claim_type)>0){
																foreach($get_claim_type as $k => $v)
																{
																	echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-group row" id="calculation_time_div" style="display: none;">
												<label for="calculation_time" class="col-md-3 col-12 col-form-label"><?php echo $this->lang->line('label_calculation_time');?></label>
												<select class="form-control select2bs4 col-md-5 col-6" id="calculate_day_type" name="calculate_day_type">
													<?php
														$get_promotion_day_type = get_promotion_day_type();
														if(isset($get_promotion_day_type) && sizeof($get_promotion_day_type)>0){
															foreach($get_promotion_day_type as $k => $v)
															{
																echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
															}
														}
													?>
												</select>
												<select class="form-control select2bs4 col-md-2 col-3" id="calculate_hour" name="calculate_hour">
													<?php for($i=0;$i<=23;$i++){ ?>
													<option value="<?php echo $i;?>"><?php if($i>=10){echo $i;}else{echo "0".$i;}?></option>
													<?php } ?>
												</select>
												<select class="form-control select2bs4 col-md-2 col-3" id="calculate_minute" name="calculate_minute">
													<?php for($i=0;$i<=59;$i+=5){ ?>
													<option value="<?php echo $i;?>"><?php if($i>=10){echo $i;}else{echo "0".$i;}?></option>
													<?php } ?>
												</select>
											</div>
											<div class="form-group row" id="reward_time_div" style="display: none;">
												<label for="reward_time" class="col-3 col-form-label"><?php echo $this->lang->line('label_reward_time');?></label>
												<select class="form-control select2bs4 col-md-5 col-6" id="reward_day_type" name="reward_day_type">
													<?php
														$get_promotion_day_type = get_promotion_day_type();
														if(isset($get_promotion_day_type) && sizeof($get_promotion_day_type)>0){
															foreach($get_promotion_day_type as $k => $v)
															{
																echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
															}
														}
													?>
												</select>
												<select class="form-control select2bs4 col-md-2 col-3" id="reward_hour" name="reward_hour">
													<?php for($i=0;$i<=23;$i++){ ?>
													<option value="<?php echo $i;?>"><?php if($i>=10){echo $i;}else{echo "0".$i;}?></option>
													<?php } ?>
												</select>
												<select class="form-control select2bs4 col-md-2 col-3" id="reward_minute" name="reward_minute">
													<?php for($i=0;$i<=59;$i+=5){ ?>
													<option value="<?php echo $i;?>"><?php if($i>=10){echo $i;}else{echo "0".$i;}?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
									<div class="card card-info">
										<div class="card-header">
											<h3 class="card-title"><?php echo $this->lang->line('label_promotion_external_setting');?></h3>
										</div>
										<div class="card-body">
											<div class="form-group row" id="ranking_target_type_div" style="display: none;">
												<label for="target_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_target_type');?></label>
												<div class="col-7">
													<select class="form-control select2bs4" id="target_type" name="target_type">
														<?php
															$get_level_target = get_level_target();
															if(isset($get_level_target) && sizeof($get_level_target)>0){
																foreach($get_level_target as $k => $v)
																{
																	echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-group row" id="first_deposit_div" style="display: none;">
												<label for="first_deposit" class="col-5 col-form-label"><?php echo $this->lang->line('label_first_deposit');?></label>
												<div class="col-7">
													<input type="checkbox" id="first_deposit" name="first_deposit" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
												</div>
											</div>
											<div class="form-group row" id="daily_first_deposit_div" style="display: none;">
												<label for="daily_first_deposit" class="col-5 col-form-label"><?php echo $this->lang->line('label_first_deposit_daily');?></label>
												<div class="col-7">
													<input type="checkbox" id="daily_first_deposit" name="daily_first_deposit" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
												</div>
											</div>
											<div class="form-group row" id="min_deposit_div" style="display: none;">
												<label for="min_deposit" class="col-5 col-form-label"><?php echo $this->lang->line('label_min_deposit');?></label>
												<div class="col-7">
													<input type="text" class="form-control" id="min_deposit" name="min_deposit" value="">
												</div>
											</div>
											<div class="form-group row" id="max_deposit_div" style="display: none;">
												<label for="max_deposit" class="col-5 col-form-label"><?php echo $this->lang->line('label_max_deposit');?></label>
												<div class="col-7">
													<input type="text" class="form-control" id="max_deposit" name="max_deposit" value="">
												</div>
											</div>
											<div class="form-group row" id="reward_amount_div" style="display: none;">
												<label for="reward_amount" class="col-5 col-form-label"><?php echo $this->lang->line('label_reward_amount');?></label>
												<div class="col-7">
													<input type="text" class="form-control" id="reward_amount" name="reward_amount" value="">
												</div>
											</div>
											<div class="form-group row" id="max_promotion_div" style="display: none;">
												<label for="max_promotion" class="col-5 col-form-label"><?php echo $this->lang->line('label_max_promotion');?></label>
												<div class="col-7">
													<input type="text" class="form-control" id="max_promotion" name="max_promotion" value="">
												</div>
											</div>
											<div class="form-group row" id="deposit_history_div" style="display: none;">
												<label for="deposit_history" class="col-5 col-form-label"><?php echo $this->lang->line('label_deposit_past_history');?></label>
												<div class="col-7">
													<input type="text" class="form-control" id="deposit_history" name="deposit_history" value="0">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-12 col-12">
									<div class="card card-success">
										<div class="card-header">
											<h3 class="card-title"><?php echo $this->lang->line('label_promotion_calculation_setting');?></h3>
										</div>
										<div class="card-body">
											<div class="form-group row">
												<label for="calculate_type" class="col-4 col-form-label"><?php echo $this->lang->line('label_calculate_type');?></label>
												<div class="col-8">
													<select class="form-control select2bs4" id="calculate_type" name="calculate_type">
														<option value=""><?php echo $this->lang->line('place_holder_select_calculate_type');?></option>
														<?php
															$get_promotion_calculate_type = get_promotion_calculate_type();
															if(isset($get_promotion_calculate_type) && sizeof($get_promotion_calculate_type)>0){
																foreach($get_promotion_calculate_type as $k => $v)
																{
																	echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-group row" id="deposit_level_fixed_div" style="display: none;">
												<label for="first_deposit" class="col-4 col-form-label"><?php echo $this->lang->line('label_deposit_level_fixed');?></label>
												<div class="col-8">
													<input type="checkbox" id="is_deposit_level_fixed" name="is_deposit_level_fixed" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
												</div>
											</div>
											<div class="form-group row" id="promotion_calculate_type_wallet" style="display: none;">
												<label for="complete_wallet_left" class="col-4 col-form-label"><?php echo $this->lang->line('label_complete_wallet_left');?></label>
												<div class="col-8">
													<input type="text" class="form-control" id="complete_wallet_left" name="complete_wallet_left" value="0">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-12 col-12">
													<div class="form-group row">
														<label for="bonus_range_type" class="col-4 col-form-label"><?php echo $this->lang->line('label_range_type');?></label>
														<div class="col-8">
															<select class="form-control select2bs4" id="bonus_range_type" name="bonus_range_type">
																<option value=""><?php echo $this->lang->line('place_holder_select_range_type');?></option>
																<?php 
																	$get_promotion_bonus_range_type = get_promotion_bonus_range_type();
																	if(isset($get_promotion_bonus_range_type) && sizeof($get_promotion_bonus_range_type)>0){
																		foreach($get_promotion_bonus_range_type as $k => $v)
																		{
																			echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
																		}
																	}
																?>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group row" id="general_range_bonus_type" style="display: none;">
												<div class="col-md-12 col-12">
													<div class="form-group row">
														<label for="bonus_type" class="col-4 col-form-label"><?php echo $this->lang->line('label_bonus_type');?></label>
														<div class="col-8">
															<select class="form-control select2bs4" id="bonus_type" name="bonus_type">
																<option value=""><?php echo $this->lang->line('place_holder_select_bonus_type');?></option>
																<option value="<?php echo PROMOTION_BONUS_TYPE_PERCENTAGE;?>"><?php echo $this->lang->line('promotion_bonus_type_percentage');?></option>
																<option value="<?php echo PROMOTION_BONUS_TYPE_FIX_AMOUNT;?>"><?php echo $this->lang->line('promotion_bonus_type_fix_amount');?></option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group row" id="general_range_turnover_multiply" style="display: none;">
												<label for="turnover_multiply" class="col-5 col-form-label"><?php echo $this->lang->line('label_rollover');?></label>
												<div class="col-7">
													<input type="text" class="form-control" id="turnover_multiply" name="turnover_multiply" value="" maxlength="2">
												</div>
											</div>
											<div class="form-group row" id="general_range_percentage" style="display: none;">
												<label for="rebate_percentage" class="col-5 col-form-label"><?php echo $this->lang->line('label_rebate_percentage');?></label>
												<div class="col-7">
													<input type="text" class="form-control" id="rebate_percentage" name="rebate_percentage" value="">
												</div>
											</div>
											<div class="form-group row" id="general_range_max_amount" style="display: none;">
												<label for="max_rebate" class="col-5 col-form-label"><?php echo $this->lang->line('label_max_rebate');?></label>
												<div class="col-7">
													<input type="text" class="form-control" id="max_rebate" name="max_rebate" value="">
												</div>
											</div>
											<div class="form-group row" id="general_range_fix_amount" style="display: none;">
												<label for="rebate_amount" class="col-5 col-form-label"><?php echo $this->lang->line('label_rebate_amount');?></label>
												<div class="col-7">
													<input type="text" class="form-control" id="rebate_amount" name="rebate_amount" value="">
												</div>
											</div>
											<div class="form-group row" id="level_range" style="display: none;">
												<table id="promotion_level_range-table" class="table table-striped table-bordered table-hover" style="width:100%;">
													<thead>
														<tr>
															<th><?php echo $this->lang->line('label_hashtag');?></th>
															<th><?php echo $this->lang->line('label_level');?></th>
															<th><?php echo $this->lang->line('label_game_provider_type');?></th>
															<th><?php echo $this->lang->line('label_bonus_type');?></th>
															<th><?php echo $this->lang->line('label_rollover');?></th>
															<th><?php echo $this->lang->line('label_amount_from');?></th>
															<th><?php echo $this->lang->line('label_amount_to');?></th>
															<th><?php echo $this->lang->line('label_bonus_amount');?></th>
															<th><?php echo $this->lang->line('label_percentage');?></th>
															<th><?php echo $this->lang->line('label_max_amount');?></th>
														</tr>
													</thead>
													<tbody>
														<?php 
															for($i=1;$i<=BONUS_RANGE_NUMBER;$i++){
														?>
														<tr>
															<td><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="bonus_range_option_<?php echo $i;?>" name="bonus_range_option_<?php echo $i;?>" value="<?php echo STATUS_ACTIVE?>"><label for="bonus_range_option_<?php echo $i;?>" class="custom-control-label"></label></div></td>
															<td><input type="text" class="form-control" id="bonus_range_bonus_level_<?php echo $i;?>" name="bonus_range_bonus_level_<?php echo $i;?>" value=""></td>
															<td>
																<select class="select2 col-12" id="bonus_range_game_exclude_<?php echo $i;?>" name="bonus_range_game_exclude_<?php echo $i;?>[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
																	<?php
																		if(isset($game_provider_list) && sizeof($game_provider_list)>0){
																			foreach($game_provider_list as $game_provider_list_row){
																				if(!empty($game_provider_list_row['game_type_report_code'])){
																					$game_provider_list_row_report = array_filter(explode(',', $game_provider_list_row['game_type_report_code']));
																					if(!empty($game_provider_list_row_report) && sizeof($game_provider_list_row_report)>0 ){
																						foreach ($game_provider_list_row_report as $game_provider_key => $game_provider_value){
																							echo '<option value="' . $game_provider_list_row['game_code'].'_'.$game_provider_value . '">' . $this->lang->line($game_provider_list_row['game_name']).' ('.$this->lang->line("game_type_".strtolower($game_provider_value)).')' . '</option>';
																						}
																					}
																				}
																			}
																		}
																	?>
																</select>
															</td>
															<td>
																<select class="form-control select2bs4 bonus_type" id="bonus_type_<?php echo $i;?>" name="bonus_type_<?php echo $i;?>">
																	<option value=""><?php echo $this->lang->line('place_holder_select_bonus_type');?></option>
																	<?php 
																		$get_promotion_bonus_type = get_promotion_bonus_type();
																		if(isset($get_promotion_bonus_type) && sizeof($get_promotion_bonus_type)>0){
																			foreach($get_promotion_bonus_type as $k => $v)
																			{
																				echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
																			}
																		}
																	?>
																</select>
															</td>
															<td><input type="text" class="form-control" id="turnover_multiply_range_<?php echo $i;?>" name="turnover_multiply_range_<?php echo $i;?>" value=""></td>
															<td><input type="text" class="form-control" id="bonus_range_amount_from_<?php echo $i;?>" name="bonus_range_amount_from_<?php echo $i;?>" value=""></td>
															<td><input type="text" class="form-control"  id="bonus_range_amount_to_<?php echo $i;?>" name="bonus_range_amount_to_<?php echo $i;?>" value=""></td>
															<td><input type="text" class="form-control bonus_range_bonus_amount" id="bonus_range_bonus_amount_<?php echo $i;?>" name="bonus_range_bonus_amount_<?php echo $i;?>" value=""></td>
															<td><input type="text" class="form-control bonus_range_percentage" id="bonus_range_percentage_<?php echo $i;?>" name="bonus_range_percentage_<?php echo $i;?>" value=""></td>
															<td><input type="text" class="form-control bonus_range_max_amount" id="bonus_range_max_amount_<?php echo $i;?>" name="bonus_range_max_amount_<?php echo $i;?>" value=""></td>
														</tr>
														<?php
															}
														?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="card card-danger">
										<div class="card-header">
											<h3 class="card-title"><?php echo $this->lang->line('label_promotion_game_setting');?></h3>
										</div>
										<div class="card-body">
											<div class="form-group row" id="game_provider_type_id_div">
												<label for="game_type" class="col-12 col-form-label"><?php echo $this->lang->line('label_game_provider_type');?></label>
												<div class="form-group col-12">
													<div class="form-group row">
														<?php
															if(isset($game_provider_list) && sizeof($game_provider_list)>0){
																foreach($game_provider_list as $game_provider_list_row){
																	if(!empty($game_provider_list_row['game_type_report_code'])){
																		$game_provider_list_row_report = array_filter(explode(',', $game_provider_list_row['game_type_report_code']));
																		if(!empty($game_provider_list_row_report) && sizeof($game_provider_list_row_report)>0 ){
																			foreach ($game_provider_list_row_report as $game_provider_key => $game_provider_value){
														?>											
														<div class="form-group clearfix col-3">
															<div class="custom-control custom-checkbox d-inline">
																<input type="checkbox" class="game_ids game_ids_provider_<?php echo $game_provider_list_row['game_code'];?> game_ids_gametype_<?php echo $game_provider_value;?>" id="game_ids_<?php echo $game_provider_list_row['game_code'];?>_<?php echo $game_provider_value;?>" name="game_ids[]" value="<?php echo $game_provider_list_row['game_code'].'_'.$game_provider_value;?>" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
																<label for="game_ids_<?php echo $game_provider_list_row['game_code'];?>_<?php echo $game_provider_value;?>" class="col-form-label"><?php echo $this->lang->line($game_provider_list_row['game_name']).' ('.$this->lang->line("game_type_".strtolower($game_provider_value)).')';?></label>
															</div>
														</div>
														<?php
																			}
																		}
																	}
																}
															}
														?>

														<div class="form-group clearfix col-12">
															<div class="custom-control custom-checkbox d-inline">
																<input type="checkbox" class="game_ids_all" id="game_ids_all" name="game_ids_all" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
																<label for="game_ids_all" class="col-form-label"><?php echo $this->lang->line('label_all');?></label>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group row" id="game_type_LC_div">
												<label for="game_type" class="col-12 col-form-label"><?php echo $this->lang->line('label_live_casino_type');?></label>
												<div class="form-group col-12">
													<div class="form-group row">
														<?php 
															$live_casino_type = live_casino_type();
															if(isset($live_casino_type) && sizeof($live_casino_type)>0){
																foreach($live_casino_type as $k => $v){
														?>
														<div class="form-group clearfix col-4">
															<div class="custom-control custom-checkbox d-inline">
																<input type="checkbox" class="live_casino_type" id="live_casino_type_<?php echo $k;?>" name="live_casino_type[]" value="<?php echo $k;?>" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
																<label for="live_casino_type<?php echo $k;?>" class="col-form-label"><?php echo $this->lang->line($v);?></label>
															</div>
														</div>
														<?php
																}
															}
														?>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card card-secondary">
										<div class="card-header">
											<h3 class="card-title"><?php echo $this->lang->line('label_promotion_game_selection');?></h3>
										</div>
										<div class="card-body">
											<div class="form-group row" id="game_provider_id_div">
												<label for="game_type" class="col-12 col-form-label"><?php echo $this->lang->line('label_game_provider');?></label>
												<div class="form-group col-12">
													<div class="form-group row">
														<?php
															if(isset($game_provider_list) && sizeof($game_provider_list)>0){
																foreach($game_provider_list as $game_provider_list_row){
														?>
														<div class="form-group clearfix col-6">
															<div class="custom-control custom-checkbox d-inline">
																<input type="checkbox" class="game_provider" id="game_provider_<?php echo $game_provider_list_row['game_code'];?>" name="game_provider[]" value="<?php echo $game_provider_list_row['game_code'];?>" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
																<label for="game_ids_<?php echo $game_provider_list_row['game_code'];?>" class="col-form-label"><?php echo $this->lang->line($game_provider_list_row['game_name']);?></label>
															</div>
														</div>
														<?php
																}
															}
														?>
													</div>
												</div>
											</div>

											<div class="form-group row" id="game_type_div">
												<label for="game_type" class="col-12 col-form-label"><?php echo $this->lang->line('label_game_type');?></label>
												<div class="form-group col-12">
													<div class="form-group row">
														<?php 
														$game_type_list = get_game_type();
														if(isset($game_type_list) && sizeof($game_type_list)>0){
															foreach($game_type_list as $k => $v){
														?>
														<div class="form-group clearfix col-4">
															<div class="custom-control custom-checkbox d-inline">
																<input type="checkbox" class="game_type" id="game_type_<?php echo $k;?>" name="game_type[]" value="<?php echo $k;?>" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
																<label for="game_type_<?php echo $k;?>" class="col-form-label"><?php echo $this->lang->line($v);?></label>
															</div>
														</div>
														<?php
															}
														}
														?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- /.card-body -->
							<div class="card-footer">
								<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
								<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel');?></button>
							</div>
							<?php echo form_close();?>
						</div>
						<!-- /.card -->
					</div>
					<!--/.col (left) -->
				</div>
				<!-- /.row -->
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->
	</div>
	<!-- REQUIRED SCRIPTS -->
	<?php $this->load->view('parts/footer_js');?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.select2').select2();
			var is_allowed = true;
			var form = $('#promotion-form');

			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});


			$('.game_ids_all').on('switchChange.bootstrapSwitch', function (event, state) {
				if(state == true){
					$('.game_ids').bootstrapSwitch('state', true);
					$('.game_provider').bootstrapSwitch('state', true);
					$('.game_type').bootstrapSwitch('state', true);
				}
			});

			$('.game_ids').on('switchChange.bootstrapSwitch', function (event, state) {
				if(state == false){
					$('.game_ids_all').bootstrapSwitch('state', $(this).prop('checked'));
				}
			});

			$('.game_provider').on('switchChange.bootstrapSwitch', function (event, state) {
				var game_provider = this.value;
				if(state == false){
					$('.game_ids_provider_'+game_provider).bootstrapSwitch('state', $(this).prop('checked'));
				}else{
					$('.game_ids_provider_'+game_provider).bootstrapSwitch('state', true);
				}
			});

			$('.game_type').on('switchChange.bootstrapSwitch', function (event, state) {
				var game_type = this.value;
				if(state == false){
					$('.game_ids_gametype_'+game_type).bootstrapSwitch('state', $(this).prop('checked'));
				}else{
					$('.game_ids_gametype_'+game_type).bootstrapSwitch('state', true);
				}
			});

			$('.apply_type_'+<?php echo PROMOTION_USER_TYPE_SYSTEM?>).on('switchChange.bootstrapSwitch', function (event, state) {
				$("#claim_type_div").hide();
				$("#calculation_time_div").hide();
				$("#reward_time_div").hide();
				if(state == true){
					$("#claim_type_div").show();
					$("#calculation_time_div").show();
				}
			});

			$("#claim_type").on('change', function () {
				$("#reward_time_div").hide();
				var claim_type = this.value;
				if(claim_type == <?php echo PROMOTION_APPLY_TYPE_SYSTEM?>){
					$("#reward_time_div").show();
				}
			})

			$("#calculate_type").on('change', function () {
				$("#promotion_calculate_type_wallet").hide();
				var calculate_type = this.value;
				if(calculate_type == <?php echo PROMOTION_CALCULATE_TYPE_WALLET_AMOUNT?>){
					$("#promotion_calculate_type_wallet").show();
				}
			});

			$("#date_type").on('change', function () {
				$("#start_date_div").hide();
				$("#end_date_div").hide();
				$("#specific_day_of_week").hide();
				$("#specific_day_of_day").hide();
				var date_type = this.value;
				if(date_type == <?php echo PROMOTION_DATE_TYPE_START_TO_END?>){
					$("#start_date_div").show();
					$("#end_date_div").show();
				}else if(date_type == <?php echo PROMOTION_DATE_TYPE_START_NO_LIMIT?>){
					$("#start_date_div").show();
				}
			});

			$("#bonus_range_type").on('change', function () {
				$("#general_range_percentage").hide();
				$("#general_range_max_amount").hide();
				$("#general_range_fix_amount").hide();
				$("#general_range_turnover_multiply").hide();
				$("#general_range_bonus_type").hide();	
				$("#level_range").hide();
				$("#deposit_level_fixed_div").hide();
				$('.bonus_range_bonus_amount').prop("disabled", true);
				$('.bonus_range_percentage').prop("disabled", true);
				$('.bonus_range_max_amount').prop("disabled", true);
				$("#bonus_type").val("");
				$(".bonus_type").val("");

				var bonus_range_type = this.value;
				if(bonus_range_type == <?php echo PROMOTION_BONUS_RANGE_TYPE_GENERAL?>){
					$("#general_range_bonus_type").show();
				}else if(bonus_range_type == <?php echo PROMOTION_BONUS_RANGE_TYPE_LEVEL?>){
					$("#deposit_level_fixed_div").show();
					$("#level_range").show();
				}
			});

			$("#bonus_type").on('change', function () {
				$("#general_range_percentage").hide();
				$("#general_range_max_amount").hide();
				$("#general_range_fix_amount").hide();
				$("#general_range_turnover_multiply").hide();
				$('.bonus_range_bonus_amount').prop("disabled", true);
				$('.bonus_range_percentage').prop("disabled", true);
				$('.bonus_range_max_amount').prop("disabled", true);
				var bonus_range_type = this.value;
				if(bonus_range_type == <?php echo PROMOTION_BONUS_TYPE_PERCENTAGE?>){
					$("#general_range_turnover_multiply").show();
					$("#general_range_percentage").show();
					$("#general_range_max_amount").show();
				}else if(bonus_range_type == <?php echo PROMOTION_BONUS_TYPE_FIX_AMOUNT?>){
					$("#general_range_fix_amount").show();
					$("#general_range_turnover_multiply").show();
				}
			});

			$('.bonus_type').on('change', function () {
				var bonus_range_type_multiple = this.value;
				var bonus_range_type_multiple_id = this.id;
				var bonus_range_type_multiple_array =  bonus_range_type_multiple_id.split("_");
				var id = bonus_range_type_multiple_array[2];
				$('#bonus_range_percentage_'+id).prop("disabled", true);
				$('#bonus_range_max_amount_'+id).prop("disabled", true);
				$('#bonus_range_bonus_amount_'+id).prop("disabled", true);
				if(bonus_range_type_multiple == <?php echo PROMOTION_BONUS_TYPE_PERCENTAGE?>){
					$('#bonus_range_percentage_'+id).prop("disabled", false);
					$('#bonus_range_max_amount_'+id).prop("disabled", false);
				}else if(bonus_range_type_multiple == <?php echo PROMOTION_BONUS_TYPE_FIX_AMOUNT?>){
					$('#bonus_range_bonus_amount_'+id).prop("disabled", false);
				}else if(bonus_range_type_multiple == <?php echo PROMOTION_BONUS_TYPE_FIX_AMOUNT_FROM?>){
					$('#bonus_range_bonus_amount_'+id).prop("disabled", false);
				}else if(bonus_range_type_multiple == <?php echo PROMOTION_BONUS_TYPE_PERCENTAGE_TURNOVER?>){
					$('#bonus_range_percentage_'+id).prop("disabled", false);
					$('#bonus_range_max_amount_'+id).prop("disabled", false);
				}
			});


			$("#genre_code").on('change', function () {
				$("#first_deposit_div").hide();
				$("#daily_first_deposit_div").hide();
				$("#min_deposit_div").hide();
				$("#max_deposit_div").hide();
				$("#reward_amount_div").hide();
				$("#deposit_history_div").hide();
				$("#ranking_target_type_div").hide();
				$("#max_promotion_div").hide();
				var genre_code = this.value;
				if(genre_code == "<?php echo PROMOTION_TYPE_DE?>" || genre_code == "<?php echo PROMOTION_TYPE_FD?>" || genre_code == "<?php echo PROMOTION_TYPE_SD?>"){
					$("#min_deposit_div").show();
					$("#max_deposit_div").show();
					$("#first_deposit_div").show();
					$("#daily_first_deposit_div").show();
					$("#max_promotion_div").show();
				}

				if(genre_code == "<?php echo PROMOTION_TYPE_RF?>"){
					$("#min_deposit_div").show();
					//$("#reward_amount_div").show();	
				}

				if(genre_code == "<?php echo PROMOTION_TYPE_BIRTH?>"){
					$("#min_deposit_div").show();
					$("#max_deposit_div").show();
					$("#first_deposit_div").show();
					$("#daily_first_deposit_div").show();
					$("#deposit_history_div").show();
					$("#max_promotion_div").show();
				}			

				if(genre_code == "<?php echo PROMOTION_TYPE_LE?>"){
					$("#ranking_target_type_div").show();	
				}
			});

			$('#start_date_click').datetimepicker({
				format: 'YYYY-MM-DD HH:mm:ss',
                icons: {
                    time: "fa fa-clock"
                }
            });



			$('#end_date_click').datetimepicker({
				format: 'YYYY-MM-DD HH:mm:ss',
                icons: {
                    time: "fa fa-clock"
                }
            });

            $('.select2').select2();		

       		var index = parent.layer.getFrameIndex(window.name);
			
			$('#button-cancel').click(function() {
				parent.layer.close(index);
			});
			
			$.validator.setDefaults({
				submitHandler: function () {
					if(is_allowed == true) {
						is_allowed = false;
						
						$.ajax({url: form.attr('action'),
							data: form.serialize(),
							type: 'post',                  
							async: 'true',
							beforeSend: function() {
								layer.load(1);
							},
							complete: function() {
								layer.closeAll('loading');
								is_allowed = true;
							},
							success: function (data) {
								var json = JSON.parse(JSON.stringify(data));
								var message = '';
								var msg_icon = 2;
								
								parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
								
								if(json.status == '<?php echo EXIT_SUCCESS;?>') {
									message = json.msg;
									msg_icon = 1;
									parent.$('#promotion-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.promotion_name_error != '') {
										message = json.msg.promotion_name_error;
									}else if(json.msg.genre_code_error != '') {
										message = json.msg.genre_code_error;
									}else if(json.msg.promotion_seq_error != '') {
										message = json.msg.promotion_seq_error;
									}
									else if(json.msg.level_error != '') {
										message = json.msg.level_error;
									}
									else if(json.msg.date_type_error != '') {
										message = json.msg.date_type_error;
									}
									else if(json.msg.times_limit_type_error != '') {
										message = json.msg.times_limit_type_error;
									}
									else if(json.msg.calculate_type_error != '') {
										message = json.msg.calculate_type_error;
									}
									else if(json.msg.bonus_range_type_error != '') {
										message = json.msg.bonus_range_type_error;
									}
									else if(json.msg.bonus_type_error != '') {
										message = json.msg.bonus_type_error;
									}
									else if(json.msg.start_date_error != '') {
										message = json.msg.start_date_error;
									}
									else if(json.msg.end_date_error != '') {
										message = json.msg.end_date_error;
									}
									else if(json.msg.specific_day_week_error != '') {
										message = json.msg.specific_day_week_error;
									}
									else if(json.msg.specific_day_day_error != '') {
										message = json.msg.specific_day_day_error;
									}
									else if(json.msg.turnover_multiply_error != '') {
										message = json.msg.turnover_multiply_error;
									}
									else if(json.msg.rebate_percentage_error != '') {
										message = json.msg.rebate_percentage_error;
									}
									else if(json.msg.max_rebate_error != '') {
										message = json.msg.max_rebate_error;
									}
									else if(json.msg.rebate_amount_error != '') {
										message = json.msg.rebate_amount_error;
									}
									else if(json.msg.min_deposit_error != '') {
										message = json.msg.min_deposit_error;
									}
									else if(json.msg.max_deposit_error != '') {
										message = json.msg.max_deposit_error;
									}
									else if(json.msg.general_error != '') {
										message = json.msg.general_error;
									}
								}
								parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
							},
							error: function (request,error) {
							}
						});  
					}
				}
			});
			
			form.validate({
				rules: {
					promotion_name: {
						required: true,
					},
					promotion_seq: {
						required: true,
						digits: true
					},
					genre_code: {
						required: true,
					},
				},
				messages: {
					promotion_name: {
						required: "<?php echo $this->lang->line('error_enter_promotion_name');?>",
					},
					promotion_seq: {
						required: "<?php echo str_replace('%s', strtolower($this->lang->line('label_sequence')), $this->lang->line('error_only_digits_allowed'));?>",
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_sequence')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					genre_code: {
						required: "<?php echo str_replace('%s', strtolower($this->lang->line('label_type')), $this->lang->line('error_select_type'));?>",
					},
				},
				errorElement: 'span',
				errorPlacement: function (error, element) {
					error.addClass('invalid-feedback');
					element.closest('.form-group').append(error);
				},
				highlight: function (element, errorClass, validClass) {
					$(element).addClass('is-invalid');
				},
				unhighlight: function (element, errorClass, validClass) {
					$(element).removeClass('is-invalid');
				}
			});
		});
	</script>
</body>
</html>
