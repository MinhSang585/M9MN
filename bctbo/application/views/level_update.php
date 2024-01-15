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
							<?php echo form_open('level/update', array('id' => 'level-form', 'name' => 'level-form', 'class' => 'form-horizontal'));?>
								<div class="card-body row">
									<div class="col-md-12 col-12">
										<div class="card card-primary">
											<div class="card-header">
												<h3 class="card-title"><?php echo $this->lang->line('label_level_general_setting');?></h3>
											</div>
											<div class="card-body">
												<div class="form-group row">
													<div class="col-md-6 col-12">
														<div class="form-group row">
															<label for="level_name" class="col-5 col-form-label"><?php echo $this->lang->line('label_ranking_name');?></label>
															<div class="col-7">
																<input type="text" class="form-control" id="level_name" name="level_name" value="<?php echo (( ! empty($level['level_name'])) ? $level['level_name'] : '-');?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="level_number" class="col-5 col-form-label"><?php echo $this->lang->line('label_ranking_number');?></label>
															<div class="col-7">
																<label class="col-form-label font-weight-normal"><?php echo (( ! empty($level['level_number'])) ? $level['level_number'] : '-');?></label>
															</div>
														</div>
														<div class="form-group row">
															<label for="level_reward_amount" class="col-5 col-form-label"><?php echo $this->lang->line('label_reward_amount');?></label>
															<div class="col-7">
																<input type="number" class="form-control" id="level_reward_amount" name="level_reward_amount" value="<?php echo ((isset($level['level_reward_amount'])) ? $level['level_reward_amount'] : '');?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="daily_withdrawal_frequency" class="col-5 col-form-label"><?php echo $this->lang->line('label_daily_withdrawal_frequency');?></label>
															<div class="col-7">
																<input type="number" class="form-control" id="daily_withdrawal_frequency" name="daily_withdrawal_frequency" value="<?php echo ((isset($level['daily_withdrawal_frequency'])) ? $level['daily_withdrawal_frequency'] : '');?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="daily_withdrawal_amount" class="col-5 col-form-label"><?php echo $this->lang->line('label_daily_withdrawal_amount');?></label>
															<div class="col-7">
																<input type="number" class="form-control" id="daily_withdrawal_amount" name="daily_withdrawal_amount" value="<?php echo ((isset($level['daily_withdrawal_amount'])) ? $level['daily_withdrawal_amount'] : '');?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="reward_point_turn" class="col-5 col-form-label"><?php echo $this->lang->line('label_reward_point_turn');?></label>
															<div class="col-7">
																<input type="number" class="form-control" id="reward_point_turn" name="reward_point_turn" value="<?php echo ((isset($level['reward_point_turn'])) ? $level['reward_point_turn'] : '');?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="reward_point" class="col-5 col-form-label"><?php echo $this->lang->line('label_reward_point');?></label>
															<div class="col-7">
																<input type="number" class="form-control" id="reward_point" name="reward_point" value="<?php echo ((isset($level['reward_point'])) ? $level['reward_point'] : '');?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="birthday_bonus" class="col-5 col-form-label"><?php echo $this->lang->line('label_birthday_bonus');?></label>
															<div class="col-7">
																<input type="number" class="form-control" id="birthday_bonus" name="birthday_bonus" value="<?php echo ((isset($level['birthday_bonus'])) ? $level['birthday_bonus'] : '');?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="birthday_present" class="col-5 col-form-label"><?php echo $this->lang->line('label_birthday_present');?></label>
															<div class="col-7">
																<input type="number" class="form-control" id="birthday_present" name="birthday_present" value="<?php echo ((isset($level['birthday_present'])) ? $level['birthday_present'] : '');?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="monthly_free_meal" class="col-5 col-form-label"><?php echo $this->lang->line('label_monthly_free_meal');?></label>
															<div class="col-7">
																<input type="number" class="form-control" id="monthly_free_meal" name="monthly_free_meal" value="<?php echo ((isset($level['monthly_free_meal'])) ? $level['monthly_free_meal'] : '');?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="monthly_phone_bill_rebate" class="col-5 col-form-label"><?php echo $this->lang->line('label_monthly_phone_bill_rebate');?></label>
															<div class="col-7">
																<input type="number" class="form-control" id="monthly_phone_bill_rebate" name="monthly_phone_bill_rebate" value="<?php echo ((isset($level['monthly_phone_bill_rebate'])) ? $level['monthly_phone_bill_rebate'] : '');?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="created_by" class="col-5 col-form-label"><?php echo $this->lang->line('label_created_by');?></label>
															<div class="col-7">
																<label class="col-form-label font-weight-normal"><?php echo (( ! empty($level['created_by'])) ? $level['created_by'] : '-');?></label>
															</div>
														</div>
														<div class="form-group row">
															<label for="created_date" class="col-5 col-form-label"><?php echo $this->lang->line('label_created_date');?></label>
															<div class="col-7">
																<label class="col-form-label font-weight-normal"><?php echo (($level['created_date'] > 0) ? date('Y-m-d H:i:s', $level['created_date']) : '-');?></label>
															</div>
														</div>
														<div class="form-group row">
															<label for="updated_by" class="col-5 col-form-label"><?php echo $this->lang->line('label_created_by');?></label>
															<div class="col-7">
																<label class="col-form-label font-weight-normal"><?php echo (( ! empty($level['updated_by'])) ? $level['updated_by'] : '-');?></label>
															</div>
														</div>
														<div class="form-group row">
															<label for="updated_date" class="col-5 col-form-label"><?php echo $this->lang->line('label_created_date');?></label>
															<div class="col-7">
																<label class="col-form-label font-weight-normal"><?php echo (($level['updated_date'] > 0) ? date('Y-m-d H:i:s', $level['updated_date']) : '-');?></label>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-12">
														<?php
															$game_type = get_game_type_all();

															if(sizeof($game_type)>0){
																foreach($game_type as $game_type_row){
														?>
														<div class="form-group row">
															<label for="level_rate_<?php echo strtolower($game_type_row);?>" class="col-5 col-form-label"><?php echo $this->lang->line(get_game_type($game_type_row));?></label>
															<div class="col-7">
																<input type="number" class="form-control" id="level_rate_<?php echo strtolower($game_type_row);?>" name="level_rate_<?php echo strtolower($game_type_row);?>" value="<?php echo ((isset($level['level_rate_'.strtolower($game_type_row)])) ? $level['level_rate_'.strtolower($game_type_row)] : '');?>">
															</div>
														</div>
														<?php
																}
															}
														?>
													</div>
												</div>
												<div class="form-group row">
													<div class="col-12">
														<ul class="nav nav-tabs" id="custom-content-below-tab-tab" role="tablist">
															<li class="nav-item"><a class="nav-link active" id="custom-content-below-upgrade-tab" data-toggle="pill" href="#custom-content-below-upgrade" role="tab" aria-controls="custom-content-below-upgrade" aria-selected="true"><?php echo $this->lang->line('label_upgrade_setting');?></a></li>
															<li class="nav-item"><a class="nav-link" id="custom-content-below-downgrade-tab" data-toggle="pill" href="#custom-content-below-downgrade" role="tab" aria-controls="custom-content-below-downgrade" aria-selected="true"><?php echo $this->lang->line('label_downgrade_setting');?></a></li>
														</ul>
														<div class="tab-content" id="custom-content-below-tabContent">
															<div class="tab-pane fade show active" id="custom-content-below-upgrade" role="tabpanel" aria-labelledby="custom-content-below-upgrade-tab">
																<div class="form-group row mt-3">
																	<label for="upgrade_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_type');?></label>
																	<div class="col-7">
																		<select class="form-control select2bs4" id="upgrade_type" name="upgrade_type">
																			<option value=""><?php echo $this->lang->line('place_holder_select_type');?></option>
																			<?php
																				$level_upgrade_type = level_upgrade_type();
																				if(!empty($level_upgrade_type)){
																					foreach($level_upgrade_type as $k => $v)
																					{
																						if(isset($level['upgrade_type']) && $level['upgrade_type'] == $k){
																							echo '<option value="' . $k . '" selected>' . $this->lang->line($v) . '</option>';
																						}else{
																							echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
																						}	
																					}
																				}
																			?>
																		</select>
																	</div>
																</div>
																<div class="form-group row" id="upgrade_setting_from">
																	<div class="col-md-6 col-12"  id="upgrade_setting_deposit_amount_from" <?php if(isset($level['upgrade_type']) && ($level['upgrade_type'] == LEVEL_UPGRADE_TARGET)){echo 'style="display: none;"';} ?>>
																		<div class="form-group row">
																			<label for="level_deposit_amount_from" class="col-5 col-form-label"><?php echo $this->lang->line('label_deposit_amount_from');?></label>
																			<div class="col-7">
																				<input type="number" class="form-control" id="level_deposit_amount_from" name="level_deposit_amount_from" value="<?php echo ((isset($level['level_deposit_amount_from'])) ? $level['level_deposit_amount_from'] : '');?>">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6 col-12"  id="upgrade_setting_target_amount_from" <?php if(isset($level['upgrade_type']) && ($level['upgrade_type'] == LEVEL_UPGRADE_DEPOSIT)){echo 'style="display: none;"';} ?>>
																		<div class="form-group row">
																			<label for="level_target_amount_from" class="col-5 col-form-label"><?php echo $this->lang->line('label_target_amount_from');?></label>
																			<div class="col-7">
																				<input type="number" class="form-control" id="level_target_amount_from" name="level_target_amount_from" value="<?php echo ((isset($level['level_target_amount_from'])) ? $level['level_target_amount_from'] : '');?>">
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-group row" id="upgrade_setting_to">
																	<div class="col-md-6 col-12" id="upgrade_setting_deposit_amount_to" <?php if(isset($level['upgrade_type']) && ($level['upgrade_type'] == LEVEL_UPGRADE_TARGET)){echo 'style="display: none;"';} ?>>
																		<div class="form-group row">
																			<label for="level_deposit_amount_to" class="col-5 col-form-label"><?php echo $this->lang->line('label_deposit_amount_to');?></label>
																			<div class="col-7">
																				<input type="number" class="form-control" id="level_deposit_amount_to" name="level_deposit_amount_to" value="<?php echo ((isset($level['level_deposit_amount_to'])) ? $level['level_deposit_amount_to'] : '');?>">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6 col-12" id="upgrade_setting_target_amount_to" <?php if(isset($level['upgrade_type']) && ($level['upgrade_type'] == LEVEL_UPGRADE_DEPOSIT)){echo 'style="display: none;"';} ?>>
																		<div class="form-group row">
																			<label for="level_target_amount_to" class="col-5 col-form-label"><?php echo $this->lang->line('label_target_amount_to');?></label>
																			<div class="col-7">
																				<input type="number" class="form-control" id="level_target_amount_to" name="level_target_amount_to" value="<?php echo ((isset($level['level_target_amount_to'])) ? $level['level_target_amount_to'] : '');?>">
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="tab-pane fade" id="custom-content-below-downgrade" role="tabpanel" aria-labelledby="custom-content-below-downgrade-tab">
																<div class="form-group row mt-3">
																	<label for="downgrade_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_type');?></label>
																	<div class="col-7">
																		<select class="form-control select2bs4" id="downgrade_type" name="downgrade_type">
																			<option value=""><?php echo $this->lang->line('place_holder_select_type');?></option>
																			<?php
																				$level_downgrade_type = level_downgrade_type();
																				if(!empty($level_downgrade_type)){
																					foreach($level_downgrade_type as $k => $v)
																					{
																						if(isset($level['downgrade_type']) && $level['downgrade_type'] == $k){
																							echo '<option value="' . $k . '" selected>' . $this->lang->line($v) . '</option>';
																						}else{
																							echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
																						}
																					}
																				}
																			?>
																		</select>
																	</div>
																</div>
																<div class="form-group row">
																	<label for="maintain_membership_limit" class="col-5 col-form-label"><?php echo $this->lang->line('label_maintain_membership_limit');?></label>
																	<div class="col-7">
																		<input type="number" class="form-control" id="maintain_membership_limit" name="maintain_membership_limit" value="<?php echo ((isset($level['maintain_membership_limit'])) ? $level['maintain_membership_limit'] : '');?>">
																	</div>
																</div>
																<div class="form-group row" id="downgrade_setting">
																	<div class="col-md-6 col-12" id="downgrade_setting_deposit_amount" <?php if(isset($level['downgrade_type']) && ($level['downgrade_type'] == LEVEL_DOWNGRADE_TARGET)){echo 'style="display: none;"';} ?>>
																		<div class="form-group row">
																			<label for="maintain_membership_deposit_amount" class="col-5 col-form-label"><?php echo $this->lang->line('label_maintain_deposit_amount');?></label>
																			<div class="col-7">
																				<input type="number" class="form-control" id="maintain_membership_deposit_amount" name="maintain_membership_deposit_amount" value="<?php echo ((isset($level['maintain_membership_deposit_amount'])) ? $level['maintain_membership_deposit_amount'] : '');?>">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6 col-12" id="downgrade_setting_target_amount" <?php if(isset($level['downgrade_type']) && ($level['downgrade_type'] == LEVEL_DOWNGRADE_DEPOSIT)){echo 'style="display: none;"';} ?>>
																		<div class="form-group row">
																			<label for="maintain_membership_target_amount" class="col-5 col-form-label"><?php echo $this->lang->line('label_maintain_target_amount');?></label>
																			<div class="col-7">
																				<input type="number" class="form-control" id="maintain_membership_target_amount" name="maintain_membership_target_amount" value="<?php echo ((isset($level['maintain_membership_target_amount'])) ? $level['maintain_membership_target_amount'] : '');?>">
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group row">
													<div class="col-12">
														<?php
															$tab_html = '';
															$content_html = '';
															$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
															if(sizeof($lang) > 0)
															{
																$tab_html .= '<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">';
																$content_html .= '<div class="tab-content" id="custom-content-below-tabContent">';
																foreach($lang as $k => $v)
																{
																	//$k = index
																	//$v = language code

																	$tab_active = (($k == 0) ? 'active' : '');
																	$tab_html .= '<li class="nav-item">';
																	$tab_html .= '<a class="nav-link ' . $tab_active . '" id="custom-content-below-' . $v . '-tab" data-toggle="pill" href="#custom-content-below-' . $v . '" role="tab" aria-controls="custom-content-below-' . $v . '" aria-selected="true">' . $this->lang->line(get_site_language_name($v)) . '</a>';
																	$tab_html .= '</li>';
																
																	$content_active = (($k == 0) ? 'show active' : '');

																	$content_html .= '<div class="tab-pane fade ' . $content_active . '" id="custom-content-below-' . $v . '" role="tabpanel" aria-labelledby="custom-content-below-' . $v . '-tab">';
																		$content_html .= '<div class="form-group row mt-3">';
																			$content_html .= '<label for="level_title_' . $v . '" class="col-5 col-form-label">' . $this->lang->line('label_level_title') . '</label>';
																			$content_html .= '<div class="col-7">';
																				$content_html .= '<input type="text" class="form-control" id="level_title_' . $v . '" name="level_title_' . $v . '" value="'.(isset($level_lang[$v]['level_title']) ? $level_lang[$v]['level_title'] : '').'">';
																			$content_html .= '</div>';
																		$content_html .= '</div>';
																		$content_html .= '<ul class="nav nav-tabs" id="custom-content-below-'.$v.'-tab" role="tablist">';
																			$content_html .= '<li class="nav-item">';
																				$content_html .= '<a class="nav-link active" id="custom-content-below-web-'.$v.'-tab" data-toggle="pill" href="#custom-content-below-web-'.$v.'" role="tab" aria-controls="custom-content-below-web-'.$v.'" aria-selected="true">'.$this->lang->line('label_web').'</a>';
																			$content_html .= '</li>';
																			$content_html .= '<li class="nav-item">';
																				$content_html .= '<a class="nav-link" id="custom-content-below-mobile-'.$v.'-tab" data-toggle="pill" href="#custom-content-below-mobile-'.$v.'" role="tab" aria-controls="custom-content-below-mobile-'.$v.'" aria-selected="true">'.$this->lang->line('label_mobile').'</a>';
																			$content_html .= '</li>';
																		$content_html .= '</ul>';
																		$content_html .= '<div class="tab-content" id="custom-content-below-'.$v.'-tabContent">';
																			$content_html .= '<div class="tab-pane fade show active" id="custom-content-below-web-'.$v.'" role="tabpanel" aria-labelledby="custom-content-below-web-'.$v.'-tab">';
																			if(isset($level_lang[$v]['level_banner_web']) && $level_lang[$v]['level_banner_web'] != ""){
																				$content_html .= '<div class="form-group row mt-3">';
																					$content_html .= '<label class="col-5 col-form-label">&nbsp;</label>';
																					$content_html .= '<div class="col-7">';
																						$content_html .= '<a href="'.LEVEL_SOURCE_PATH . $level_lang[$v]['level_banner_web'].'" target="_blank"><img src="'.LEVEL_SOURCE_PATH . $level_lang[$v]['level_banner_web'].'" width="200" border="0" /></a>';
																					$content_html .= '</div>';
																				$content_html .= '</div>';
																			}

																				$content_html .= '<div class="form-group row mt-3">';
																					$content_html .= '<label for="web_banner_'.$v.'" class="col-5 col-form-label">'.$this->lang->line('label_image').'</label>';
																					$content_html .= '<div class="col-7">';
																						$content_html .= '<div class="custom-file col-10">';
																							$content_html .= '<input type="file" class="custom-file-input" id="web_banner_'.$v.'" name="web_banner_'.$v.'">';
																							$content_html .= '<label class="custom-file-label" for="web_banner_'.$v.'">'.$this->lang->line('button_choose_file').'</label>';
																						$content_html .= '</div>';
																					$content_html .= '</div>';
																				$content_html .= '</div>';
																			$content_html .= '</div>';
																			$content_html .= '<div class="tab-pane fade" id="custom-content-below-mobile-'.$v.'" role="tabpanel" aria-labelledby="custom-content-below-mobile-'.$v.'-tab">';
																			if(isset($level_lang[$v]['level_banner_mobile']) && $level_lang[$v]['level_banner_mobile'] != ""){
																				$content_html .= '<div class="form-group row mt-3">';
																					$content_html .= '<label class="col-5 col-form-label">&nbsp;</label>';
																					$content_html .= '<div class="col-7">';
																						$content_html .= '<a href="'.LEVEL_SOURCE_PATH . $level_lang[$v]['level_banner_mobile'].'" target="_blank"><img src="'.LEVEL_SOURCE_PATH . $level_lang[$v]['level_banner_mobile'].'" width="200" border="0" /></a>';
																					$content_html .= '</div>';
																				$content_html .= '</div>';
																			}
																				$content_html .= '<div class="form-group row mt-3">';
																					$content_html .= '<label for="mobile_banner_'.$v.'" class="col-5 col-form-label">'.$this->lang->line('label_image').'</label>';
																					$content_html .= '<div class="col-7">';
																						$content_html .= '<div class="custom-file col-10">';
																							$content_html .= '<input type="file" class="custom-file-input" id="mobile_banner_'.$v.'" name="mobile_banner_'.$v.'">';
																							$content_html .= '<label class="custom-file-label" for="mobile_banner_'.$v.'">'.$this->lang->line('button_choose_file').'</label>';
																						$content_html .= '</div>';
																					$content_html .= '</div>';
																				$content_html .= '</div>';
																			$content_html .= '</div>';
																		$content_html .= '</div>';
																	$content_html .= '</div>';
																}
																
																$tab_html .= '</ul>';
																$content_html .= '</div>';
															}

															$html = $tab_html . $content_html;
															echo $html;
														?>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-12 col-12">
										<div class="card card-danger">
											<div class="card-header">
												<h3 class="card-title"><?php echo $this->lang->line('label_level_game_setting');?></h3>
											</div>
											<div class="card-body">
												<div class="form-group row" id="game_provider_type_id_div">
													<label for="game_type" class="col-12 col-form-label"><?php echo $this->lang->line('label_game_provider_type');?></label>
													<div class="form-group col-12">
														<div class="form-group row">
															<?php
															    $level_game_ids = array_filter(explode(',', $level['game_ids']));
																if(isset($game_provider_list) && sizeof($game_provider_list)>0){
																	foreach($game_provider_list as $game_provider_list_row){
																		if(!empty($game_provider_list_row['game_type_report_code'])){
																			$game_provider_list_row_report = array_filter(explode(',', $game_provider_list_row['game_type_report_code']));
																			if(!empty($game_provider_list_row_report) && sizeof($game_provider_list_row_report)>0 ){
																				foreach ($game_provider_list_row_report as $game_provider_key => $game_provider_value){
																				    if(in_array($game_provider_list_row['game_code'].'_'.$game_provider_value, $level_game_ids)) 
																	                {
															?>											
															<div class="form-group clearfix col-3">
																<div class="custom-control custom-checkbox d-inline">
																	<input type="checkbox" class="game_ids game_ids_provider_<?php echo $game_provider_list_row['game_code'];?> game_ids_gametype_<?php echo $game_provider_value;?>" id="game_ids_<?php echo $game_provider_list_row['game_code'];?>_<?php echo $game_provider_value;?>" name="game_ids[]" value="<?php echo $game_provider_list_row['game_code'].'_'.$game_provider_value;?>" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
																	<label for="game_ids_<?php echo $game_provider_list_row['game_code'];?>_<?php echo $game_provider_value;?>" class="col-form-label"><?php echo $this->lang->line($game_provider_list_row['game_name']).' ('.$this->lang->line("game_type_".strtolower($game_provider_value)).')';?></label>
																</div>
															</div>
															<?php
																	                }else{
															?>
															<div class="form-group clearfix col-3">
																<div class="custom-control custom-checkbox d-inline">
																	<input type="checkbox" class="game_ids game_ids_provider_<?php echo $game_provider_list_row['game_code'];?> game_ids_gametype_<?php echo $game_provider_value;?>" id="game_ids_<?php echo $game_provider_list_row['game_code'];?>_<?php echo $game_provider_value;?>" name="game_ids[]" value="<?php echo $game_provider_list_row['game_code'].'_'.$game_provider_value;?>" data-bootstrap-switch data-off-color="secondary" data-on-color="success">
																	<label for="game_ids_<?php echo $game_provider_list_row['game_code'];?>_<?php echo $game_provider_value;?>" class="col-form-label"><?php echo $this->lang->line($game_provider_list_row['game_name']).' ('.$this->lang->line("game_type_".strtolower($game_provider_value)).')';?></label>
																</div>
															</div>
															<?php
																	                }
																				}
																			}
																		}
																	}
																}
															?>

															<div class="form-group clearfix col-12">
																<div class="custom-control custom-checkbox d-inline">
																	<input type="checkbox" class="game_ids_all" id="game_ids_all" name="game_ids_all" value="1" <?php echo ((isset($level['game_ids_all']) && $level['game_ids_all'] == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
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
									</div>
									<div class="col-md-12 col-12">
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
									<input type="hidden" id="level_id" name="level_id" value="<?php echo (isset($level['level_id']) ? $level['level_id'] : '');?>">
									<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
									<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel');?></button>
								</div>
								<!-- /.card-footer -->
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
	<!-- ./wrapper -->

	<!-- REQUIRED SCRIPTS -->
	<?php $this->load->view('parts/footer_js');?>

	<script type="text/javascript">
		$(document).ready(function() {
			var is_allowed = true;
			var form = $('#level-form');
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
			var index = parent.layer.getFrameIndex(window.name);
			$('#button-cancel').click(function() {
				parent.layer.close(index);
			});

			$("#upgrade_type").on('change', function () {
				$("#upgrade_setting_from").hide();
				$("#upgrade_setting_deposit_amount_from").hide();
				$("#upgrade_setting_target_amount_from").hide();
				$("#upgrade_setting_to").hide();
				$("#upgrade_setting_deposit_amount_to").hide();
				$("#upgrade_setting_target_amount_to").hide();
				
				var upgrade_type = this.value;
				if(upgrade_type == <?php echo LEVEL_UPGRADE_DEPOSIT?>){
					$("#upgrade_setting_from").show();
					$("#upgrade_setting_to").show();
					$("#upgrade_setting_deposit_amount_from").show();
					$("#upgrade_setting_deposit_amount_to").show();
				}else if(upgrade_type == <?php echo LEVEL_UPGRADE_TARGET?>){
					$("#upgrade_setting_from").show();
					$("#upgrade_setting_to").show();
					$("#upgrade_setting_target_amount_from").show();
					$("#upgrade_setting_target_amount_to").show();
				}else if(upgrade_type == <?php echo LEVEL_UPGRADE_DEPOSIT_TARGET?>){
					$("#upgrade_setting_from").show();
					$("#upgrade_setting_to").show();
					$("#upgrade_setting_deposit_amount_from").show();
					$("#upgrade_setting_deposit_amount_to").show();
					$("#upgrade_setting_target_amount_from").show();
					$("#upgrade_setting_target_amount_to").show();
				}else{

				}
			});

			$("#downgrade_type").on('change', function () {
				$("#downgrade_setting").hide();
				$("#downgrade_setting_deposit_amount").hide();
				$("#downgrade_setting_target_amount").hide();
				var downgrade_type = this.value;
				if(downgrade_type == <?php echo LEVEL_DOWNGRADE_DEPOSIT?>){
					$("#downgrade_setting").show();
					$("#downgrade_setting_deposit_amount").show();
				}else if(downgrade_type == <?php echo LEVEL_DOWNGRADE_TARGET?>){
					$("#downgrade_setting").show();
					$("#downgrade_setting_target_amount").show();
				}else if(downgrade_type == <?php echo LEVEL_DOWNGRADE_DEPOSIT_TARGET?>){
					$("#downgrade_setting").show();
					$("#downgrade_setting_deposit_amount").show();
					$("#downgrade_setting_target_amount").show();
				}else{

				}
			});
			
			$.validator.setDefaults({
				submitHandler: function () {
					$('#status').val($(document.activeElement).val());
					layer.confirm('<?php echo $this->lang->line('label_confirm_to_proceed');?>', {
						title: '<?php echo $this->lang->line('label_info');?>',
						btn: ['<?php echo $this->lang->line('status_yes');?>', '<?php echo $this->lang->line('status_no');?>']
					}, function() {
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
									var message = json.msg;
									var msg_icon = 2;
									parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
									$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
									
									if(json.status == '<?php echo EXIT_SUCCESS;?>') {
										msg_icon = 1;
										parent.$('#level-table').DataTable().ajax.reload();
										parent.layer.close(index);
									}else{
										if(json.msg.upgrade_type_error != '') {
											message = json.msg.upgrade_type_error;
										}else if(json.msg.downgrade_type_error != '') {
											message = json.msg.downgrade_type_error;
										}else if(json.msg.maintain_membership_limit_error != '') {
											message = json.msg.maintain_membership_limit_error;
										}else if(json.msg.calculate_type_error != '') {
											message = json.msg.calculate_type_error;
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
					});	
				}
			});
			
			form.validate({
				rules: {
					level_id: {
						required: true
					},
					/*
					calculate_type: {
						required: true,
						digits: true
					},
					*/
				},
				messages: {
					level_id: {
						required: "<?php echo $this->lang->line('error_enter_ranking_number');?>",
					},
					/*
					calculate_type: {
						required: "<?php echo str_replace('%s', strtolower($this->lang->line('label_calculate_type')), $this->lang->line('error_only_digits_allowed'));?>",
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_calculate_type')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					*/
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