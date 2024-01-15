<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="<?php echo get_language_code('iso');?>">
<head>
	<?php $this->load->view('parts/head_meta');?>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
	<div class="wrapper">
		<!-- Navbar -->
		<?php $this->load->view('parts/navbar_page');?>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<?php $this->load->view('parts/sidebar_page');?>
		<!-- /.sidebar -->
	  
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<?php $this->load->view('parts/header_page');?>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<div class="row">
						<!-- left column -->
						<div class="col-md-12">
							<!-- jquery validation -->
							<div class="card card-primary">
								<div class="card-header">
									<h3 class="card-title"><?php echo $this->lang->line('title_miscellaneous');?></h3>
								</div>
								<!-- /.card-header -->
								<!-- form start -->
								<?php echo form_open_multipart('miscellaneous/submit', array('id' => 'miscellaneous-form', 'name' => 'miscellaneous-form', 'class' => 'form-horizontal'));?>
									<div class="card-body">
										<div class="form-group row">
											<label for="system_type" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_type');?></label>
											<div class="col-sm-8">
												<select class="form-control select2bs4 col-sm-4" id="system_type" name="system_type">
													<?php
														$system_type_data = get_system_type();
														if(!empty($system_type_data) && sizeof($system_type_data)){
															foreach($system_type_data as $k => $v)
															{
																if(isset($system_type)) 
																{
																	if($k == $system_type)
																	{
																		echo '<option value="' . $k . '" selected="selected">' . $this->lang->line($v) . '</option>';
																	}else{
																		echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
																	}
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
											<label for="min_deposit" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_min_deposit');?></label>
											<div class="col-sm-8">
												<input type="text" class="form-control col-sm-4" id="min_deposit" name="min_deposit" value="<?php echo (isset($min_deposit) ? $min_deposit : 0);?>">
											</div>
										</div>
										<div class="form-group row">
											<label for="max_deposit" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_max_deposit');?></label>
											<div class="col-sm-8">
												<input type="text" class="form-control col-sm-4" id="max_deposit" name="max_deposit" value="<?php echo (isset($max_deposit) ? $max_deposit : 0);?>">
											</div>
										</div>
										<div class="form-group row">
											<label for="min_withdrawal" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_min_withdrawal');?></label>
											<div class="col-sm-8">
												<input type="text" class="form-control col-sm-4" id="min_withdrawal" name="min_withdrawal" value="<?php echo (isset($min_withdrawal) ? $min_withdrawal : 0);?>">
											</div>
										</div>
										<div class="form-group row">
											<label for="max_withdrawal" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_max_withdrawal');?></label>
											<div class="col-sm-8">
												<input type="text" class="form-control col-sm-4" id="max_withdrawal" name="max_withdrawal" value="<?php echo (isset($max_withdrawal) ? $max_withdrawal : 0);?>">
											</div>
										</div>
										<div class="form-group row">
											<label for="max_count_withdrawal" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_max_withdrawal_per_day');?></label>
											<div class="col-sm-8">
												<input type="number" class="form-control col-sm-4" id="max_count_withdrawal" name="max_count_withdrawal" value="<?php echo (isset($max_count_withdrawal) ? $max_count_withdrawal : 0);?>">
											</div>
										</div>
										<div class="form-group row">
											<label for="win_loss_suspend_limit" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_win_loss_suspend_limit');?></label>
											<div class="col-sm-8">
												<input type="text" class="form-control col-sm-4" id="win_loss_suspend_limit" name="win_loss_suspend_limit" value="<?php echo (isset($win_loss_suspend_limit) ? $win_loss_suspend_limit : 0);?>">
											</div>
										</div>
										<?php if(permission_validation(PERMISSION_DEPOSIT_OFFLINE_ANNOUNCEMENT) == TRUE):?>
										<div class="form-group row">
											<label for="deposit_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_deposit_sound_mp3');?></label>
											<div class="col-sm-8">
												<div class="custom-file col-sm-10">
													<input type="file" class="custom-file-input" id="deposit_sound" name="deposit_sound">
													<label class="custom-file-label" for="deposit_sound"><?php echo $this->lang->line('button_choose_file');?></label>
												</div>
												<p class="text-sm mb-0" id="uploaded_deposit_sound"><?php echo (isset($deposit_sound) ? $deposit_sound : '');?></p>
											</div>
										</div>
		
										<div class="form-group row">
											<label for="is_deposit_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_deposit_sound');?></label>
											<div class="col-7">
												<input type="checkbox" id="is_deposit_sound" name="is_deposit_sound" value="1" <?php echo ((isset($is_deposit_sound) && $is_deposit_sound == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
										<?php endif;?>
										<?php if(permission_validation(PERMISSION_DEPOSIT_OFFLINE_NOTICE) == TRUE):?>
										<div class="form-group row">
											<label for="is_deposit_notice" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_deposit_notice');?></label>
											<div class="col-7">
												<input type="checkbox" id="is_deposit_notice" name="is_deposit_notice" value="1" <?php echo ((isset($is_deposit_notice) && $is_deposit_notice == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>


									<?php
										$tab_html = '';
										$content_html = '';
										$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
										if(sizeof($lang) > 0)
										{
											$tab_html .= '<ul class="nav nav-tabs" id="custom-content-below-tab-'.TRANSFER_OFFLINE_DEPOSIT.'" role="tablist">';
											$content_html .= '<div class="tab-content" id="custom-content-below-tabContent'.TRANSFER_OFFLINE_DEPOSIT.'">';
											foreach($lang as $k => $v)
											{
												//$k = index
												//$v = language code

												$tab_active = (($k == 0) ? 'active' : '');
												$tab_html .= '<li class="nav-item">';
												$tab_html .= '<a class="nav-link ' . $tab_active . '" id="custom-content-below-'.TRANSFER_OFFLINE_DEPOSIT.'-' . $v . '-tab" data-toggle="pill" href="#custom-content-below-'.TRANSFER_OFFLINE_DEPOSIT.'-' . $v . '" role="tab" aria-controls="custom-content-below-'.TRANSFER_OFFLINE_DEPOSIT.'-' . $v . '" aria-selected="true">' . $this->lang->line(get_site_language_name($v)) . '</a>';
												$tab_html .= '</li>';
											
												$content_active = (($k == 0) ? 'show active' : '');

												$content_html .= '<div class="tab-pane fade ' . $content_active . '" id="custom-content-below-'.TRANSFER_OFFLINE_DEPOSIT.'-' . $v . '" role="tabpanel" aria-labelledby="custom-content-below-'.TRANSFER_OFFLINE_DEPOSIT.'-' . $v . '-tab">';
													$content_html .= '<div class="form-group row mt-3">';
														$content_html .= '<label for="miscellaneous_title_'.TRANSFER_OFFLINE_DEPOSIT.'_' . $v . '" class="col-sm-4 col-form-label">' . $this->lang->line('label_title') . '</label>';
														$content_html .= '<div class="col-7">';
															$content_html .= '<input type="text" class="form-control" id="miscellaneous_title_'.TRANSFER_OFFLINE_DEPOSIT.'_' . $v . '" name="miscellaneous_title_'.TRANSFER_OFFLINE_DEPOSIT.'_' . $v . '" value="'.(isset($miscellaneous_lang[TRANSFER_OFFLINE_DEPOSIT][$v]['miscellaneous_title']) ? $miscellaneous_lang[TRANSFER_OFFLINE_DEPOSIT][$v]['miscellaneous_title'] : '').'">';
														$content_html .= '</div>';
													$content_html .= '</div>';
													$content_html .= '<div class="form-group row mt-3">';
														$content_html .= '<label for="miscellaneous_content_'.TRANSFER_OFFLINE_DEPOSIT.'_' . $v . '" class="col-sm-4 col-form-label">' . $this->lang->line('label_content') . '</label>';
														$content_html .= '<div class="col-7">';
															$content_html .= '<textarea class="form-control summernote" id="miscellaneous_content_'.TRANSFER_OFFLINE_DEPOSIT.'_' . $v . '" name="miscellaneous_content_'.TRANSFER_OFFLINE_DEPOSIT.'_' . $v . '" rows="4">'.(isset($miscellaneous_lang[TRANSFER_OFFLINE_DEPOSIT][$v]['miscellaneous_content']) ? $miscellaneous_lang[TRANSFER_OFFLINE_DEPOSIT][$v]['miscellaneous_content'] : '').'</textarea>';
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
										<?php endif;?>
										<?php if(permission_validation(PERMISSION_DEPOSIT_ONLINE_ANNOUNCEMENT) == TRUE):?>
										<div class="form-group row">
											<label for="online_deposit_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_online_deposit_sound_mp3');?></label>
											<div class="col-sm-8">
												<div class="custom-file col-sm-10">
													<input type="file" class="custom-file-input" id="online_deposit_sound" name="online_deposit_sound">
													<label class="custom-file-label" for="online_deposit_sound"><?php echo $this->lang->line('button_choose_file');?></label>
												</div>
												<p class="text-sm mb-0" id="uploaded_deposit_sound"><?php echo (isset($online_deposit_sound) ? $online_deposit_sound : '');?></p>
											</div>
										</div>

										<div class="form-group row">
											<label for="is_online_deposit_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_online_deposit_sound');?></label>
											<div class="col-7">
												<input type="checkbox" id="is_online_deposit_sound" name="is_online_deposit_sound" value="1" <?php echo ((isset($is_online_deposit_sound) && $is_online_deposit_sound == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
										<?php endif;?>
										<?php if(permission_validation(PERMISSION_DEPOSIT_ONLINE_NOTICE) == TRUE):?>
										<div class="form-group row">
											<label for="is_online_deposit_notice" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_online_deposit_notice');?></label>
											<div class="col-7">
												<input type="checkbox" id="is_online_deposit_notice" name="is_online_deposit_notice" value="1" <?php echo ((isset($is_online_deposit_notice) && $is_online_deposit_notice == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>

									<?php
										$tab_html = '';
										$content_html = '';
										$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
										if(sizeof($lang) > 0)
										{
											$tab_html .= '<ul class="nav nav-tabs" id="custom-content-below-tab-'.TRANSFER_PG_DEPOSIT.'" role="tablist">';
											$content_html .= '<div class="tab-content" id="custom-content-below-tabContent'.TRANSFER_PG_DEPOSIT.'">';
											foreach($lang as $k => $v)
											{
												//$k = index
												//$v = language code

												$tab_active = (($k == 0) ? 'active' : '');
												$tab_html .= '<li class="nav-item">';
												$tab_html .= '<a class="nav-link ' . $tab_active . '" id="custom-content-below-'.TRANSFER_PG_DEPOSIT.'-' . $v . '-tab" data-toggle="pill" href="#custom-content-below-'.TRANSFER_PG_DEPOSIT.'-' . $v . '" role="tab" aria-controls="custom-content-below-'.TRANSFER_PG_DEPOSIT.'-' . $v . '" aria-selected="true">' . $this->lang->line(get_site_language_name($v)) . '</a>';
												$tab_html .= '</li>';
											
												$content_active = (($k == 0) ? 'show active' : '');

												$content_html .= '<div class="tab-pane fade ' . $content_active . '" id="custom-content-below-'.TRANSFER_PG_DEPOSIT.'-' . $v . '" role="tabpanel" aria-labelledby="custom-content-below-'.TRANSFER_PG_DEPOSIT.'-' . $v . '-tab">';
													$content_html .= '<div class="form-group row mt-3">';
														$content_html .= '<label for="miscellaneous_title_'.TRANSFER_PG_DEPOSIT.'_' . $v . '" class="col-sm-4 col-form-label">' . $this->lang->line('label_title') . '</label>';
														$content_html .= '<div class="col-7">';
															$content_html .= '<input type="text" class="form-control" id="miscellaneous_title_'.TRANSFER_PG_DEPOSIT.'_' . $v . '" name="miscellaneous_title_'.TRANSFER_PG_DEPOSIT.'_' . $v . '" value="'.(isset($miscellaneous_lang[TRANSFER_PG_DEPOSIT][$v]['miscellaneous_title']) ? $miscellaneous_lang[TRANSFER_PG_DEPOSIT][$v]['miscellaneous_title'] : '').'">';
														$content_html .= '</div>';
													$content_html .= '</div>';
													$content_html .= '<div class="form-group row mt-3">';
														$content_html .= '<label for="miscellaneous_content_'.TRANSFER_PG_DEPOSIT.'_' . $v . '" class="col-sm-4 col-form-label">' . $this->lang->line('label_content') . '</label>';
														$content_html .= '<div class="col-7">';
															$content_html .= '<textarea class="form-control summernote" id="miscellaneous_content_'.TRANSFER_PG_DEPOSIT.'_' . $v . '" name="miscellaneous_content_'.TRANSFER_PG_DEPOSIT.'_' . $v . '" rows="4">'.(isset($miscellaneous_lang[TRANSFER_PG_DEPOSIT][$v]['miscellaneous_content']) ? $miscellaneous_lang[TRANSFER_PG_DEPOSIT][$v]['miscellaneous_content'] : '').'</textarea>';
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
										<?php endif;?>
										<?php if(permission_validation(PERMISSION_DEPOSIT_CREDIT_CARD_ANNOUNCEMENT) == TRUE):?>
										<div class="form-group row">
											<label for="credit_card_deposit_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_credit_card_deposit_sound_mp3');?></label>
											<div class="col-sm-8">
												<div class="custom-file col-sm-10">
													<input type="file" class="custom-file-input" id="credit_card_deposit_sound" name="credit_card_deposit_sound">
													<label class="custom-file-label" for="credit_card_deposit_sound"><?php echo $this->lang->line('button_choose_file');?></label>
												</div>
												<p class="text-sm mb-0" id="uploaded_deposit_sound"><?php echo (isset($credit_card_deposit_sound) ? $credit_card_deposit_sound : '');?></p>
											</div>
										</div>

										<div class="form-group row">
											<label for="is_credit_card_deposit_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_credit_card_deposit_sound');?></label>
											<div class="col-7">
												<input type="checkbox" id="is_credit_card_deposit_sound" name="is_credit_card_deposit_sound" value="1" <?php echo ((isset($is_credit_card_deposit_sound) && $is_credit_card_deposit_sound == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
										<?php endif;?>
										<?php if(permission_validation(PERMISSION_DEPOSIT_CREDIT_CARD_NOTICE) == TRUE):?>
										<div class="form-group row">
											<label for="is_credit_card_deposit_notice" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_credit_card_deposit_notice');?></label>
											<div class="col-7">
												<input type="checkbox" id="is_credit_card_deposit_notice" name="is_credit_card_deposit_notice" value="1" <?php echo ((isset($is_credit_card_deposit_notice) && $is_credit_card_deposit_notice == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>

										<?php
										$tab_html = '';
										$content_html = '';
										$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
										if(sizeof($lang) > 0)
										{
											$tab_html .= '<ul class="nav nav-tabs" id="custom-content-below-tab-'.TRANSFER_CREDIT_CARD_DEPOSIT.'" role="tablist">';
											$content_html .= '<div class="tab-content" id="custom-content-below-tabContent'.TRANSFER_CREDIT_CARD_DEPOSIT.'">';
											foreach($lang as $k => $v)
											{
												//$k = index
												//$v = language code

												$tab_active = (($k == 0) ? 'active' : '');
												$tab_html .= '<li class="nav-item">';
												$tab_html .= '<a class="nav-link ' . $tab_active . '" id="custom-content-below-'.TRANSFER_CREDIT_CARD_DEPOSIT.'-' . $v . '-tab" data-toggle="pill" href="#custom-content-below-'.TRANSFER_CREDIT_CARD_DEPOSIT.'-' . $v . '" role="tab" aria-controls="custom-content-below-'.TRANSFER_CREDIT_CARD_DEPOSIT.'-' . $v . '" aria-selected="true">' . $this->lang->line(get_site_language_name($v)) . '</a>';
												$tab_html .= '</li>';
											
												$content_active = (($k == 0) ? 'show active' : '');

												$content_html .= '<div class="tab-pane fade ' . $content_active . '" id="custom-content-below-'.TRANSFER_CREDIT_CARD_DEPOSIT.'-' . $v . '" role="tabpanel" aria-labelledby="custom-content-below-'.TRANSFER_CREDIT_CARD_DEPOSIT.'-' . $v . '-tab">';
													$content_html .= '<div class="form-group row mt-3">';
														$content_html .= '<label for="miscellaneous_title_'.TRANSFER_CREDIT_CARD_DEPOSIT.'_' . $v . '" class="col-sm-4 col-form-label">' . $this->lang->line('label_title') . '</label>';
														$content_html .= '<div class="col-7">';
															$content_html .= '<input type="text" class="form-control" id="miscellaneous_title_'.TRANSFER_CREDIT_CARD_DEPOSIT.'_' . $v . '" name="miscellaneous_title_'.TRANSFER_CREDIT_CARD_DEPOSIT.'_' . $v . '" value="'.(isset($miscellaneous_lang[TRANSFER_CREDIT_CARD_DEPOSIT][$v]['miscellaneous_title']) ? $miscellaneous_lang[TRANSFER_CREDIT_CARD_DEPOSIT][$v]['miscellaneous_title'] : '').'">';
														$content_html .= '</div>';
													$content_html .= '</div>';
													$content_html .= '<div class="form-group row mt-3">';
														$content_html .= '<label for="miscellaneous_content_'.TRANSFER_CREDIT_CARD_DEPOSIT.'_' . $v . '" class="col-sm-4 col-form-label">' . $this->lang->line('label_content') . '</label>';
														$content_html .= '<div class="col-7">';
															$content_html .= '<textarea class="form-control summernote" id="miscellaneous_content_'.TRANSFER_CREDIT_CARD_DEPOSIT.'_' . $v . '" name="miscellaneous_content_'.TRANSFER_CREDIT_CARD_DEPOSIT.'_' . $v . '" rows="4">'.(isset($miscellaneous_lang[TRANSFER_CREDIT_CARD_DEPOSIT][$v]['miscellaneous_content']) ? $miscellaneous_lang[TRANSFER_CREDIT_CARD_DEPOSIT][$v]['miscellaneous_content'] : '').'</textarea>';
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
										<?php endif;?>
										
										<?php if(permission_validation(PERMISSION_DEPOSIT_HYPERMARKET_ANNOUNCEMENT) == TRUE):?>
										<div class="form-group row">
											<label for="hypermart_deposit_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_hypermart_deposit_sound_mp3');?></label>
											<div class="col-sm-8">
												<div class="custom-file col-sm-10">
													<input type="file" class="custom-file-input" id="hypermart_deposit_sound" name="hypermart_deposit_sound">
													<label class="custom-file-label" for="hypermart_deposit_sound"><?php echo $this->lang->line('button_choose_file');?></label>
												</div>
												<p class="text-sm mb-0" id="uploaded_deposit_sound"><?php echo (isset($hypermart_deposit_sound) ? $hypermart_deposit_sound : '');?></p>
											</div>
										</div>

										<div class="form-group row">
											<label for="is_hypermart_deposit_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_hypermart_deposit_sound');?></label>
											<div class="col-7">
												<input type="checkbox" id="is_hypermart_deposit_sound" name="is_hypermart_deposit_sound" value="1" <?php echo ((isset($is_hypermart_deposit_sound) && $is_hypermart_deposit_sound == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
										<?php endif;?>
										<?php if(permission_validation(PERMISSION_DEPOSIT_HYPERMARKET_NOTICE) == TRUE):?>
										<div class="form-group row">
											<label for="is_hypermart_deposit_notice" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_hypermart_deposit_notice');?></label>
											<div class="col-7">
												<input type="checkbox" id="is_hypermart_deposit_notice" name="is_hypermart_deposit_notice" value="1" <?php echo ((isset($is_hypermart_deposit_notice) && $is_hypermart_deposit_notice == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>


										<?php
										$tab_html = '';
										$content_html = '';
										$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
										if(sizeof($lang) > 0)
										{
											$tab_html .= '<ul class="nav nav-tabs" id="custom-content-below-tab-'.TRANSFER_HYPERMART_DEPOSIT.'" role="tablist">';
											$content_html .= '<div class="tab-content" id="custom-content-below-tabContent'.TRANSFER_HYPERMART_DEPOSIT.'">';
											foreach($lang as $k => $v)
											{
												//$k = index
												//$v = language code

												$tab_active = (($k == 0) ? 'active' : '');
												$tab_html .= '<li class="nav-item">';
												$tab_html .= '<a class="nav-link ' . $tab_active . '" id="custom-content-below-'.TRANSFER_HYPERMART_DEPOSIT.'-' . $v . '-tab" data-toggle="pill" href="#custom-content-below-'.TRANSFER_HYPERMART_DEPOSIT.'-' . $v . '" role="tab" aria-controls="custom-content-below-'.TRANSFER_HYPERMART_DEPOSIT.'-' . $v . '" aria-selected="true">' . $this->lang->line(get_site_language_name($v)) . '</a>';
												$tab_html .= '</li>';
											
												$content_active = (($k == 0) ? 'show active' : '');

												$content_html .= '<div class="tab-pane fade ' . $content_active . '" id="custom-content-below-'.TRANSFER_HYPERMART_DEPOSIT.'-' . $v . '" role="tabpanel" aria-labelledby="custom-content-below-'.TRANSFER_HYPERMART_DEPOSIT.'-' . $v . '-tab">';
													$content_html .= '<div class="form-group row mt-3">';
														$content_html .= '<label for="miscellaneous_title_'.TRANSFER_HYPERMART_DEPOSIT.'_' . $v . '" class="col-sm-4 col-form-label">' . $this->lang->line('label_title') . '</label>';
														$content_html .= '<div class="col-7">';
															$content_html .= '<input type="text" class="form-control" id="miscellaneous_title_'.TRANSFER_HYPERMART_DEPOSIT.'_' . $v . '" name="miscellaneous_title_'.TRANSFER_HYPERMART_DEPOSIT.'_' . $v . '" value="'.(isset($miscellaneous_lang[TRANSFER_HYPERMART_DEPOSIT][$v]['miscellaneous_title']) ? $miscellaneous_lang[TRANSFER_HYPERMART_DEPOSIT][$v]['miscellaneous_title'] : '').'">';
														$content_html .= '</div>';
													$content_html .= '</div>';
													$content_html .= '<div class="form-group row mt-3">';
														$content_html .= '<label for="miscellaneous_content_'.TRANSFER_HYPERMART_DEPOSIT.'_' . $v . '" class="col-sm-4 col-form-label">' . $this->lang->line('label_content') . '</label>';
														$content_html .= '<div class="col-7">';
															$content_html .= '<textarea class="form-control summernote" id="miscellaneous_content_'.TRANSFER_HYPERMART_DEPOSIT.'_' . $v . '" name="miscellaneous_content_'.TRANSFER_HYPERMART_DEPOSIT.'_' . $v . '" rows="4">'.(isset($miscellaneous_lang[TRANSFER_HYPERMART_DEPOSIT][$v]['miscellaneous_content']) ? $miscellaneous_lang[TRANSFER_HYPERMART_DEPOSIT][$v]['miscellaneous_content'] : '').'</textarea>';
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
										<?php endif;?>
										<?php if(permission_validation(PERMISSION_WITHDRAWAL_OFFLINE_ANNOUNCEMENT) == TRUE):?>
										<div class="form-group row">
											<label for="withdrawal_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_withdrawal_sound_mp3');?></label>
											<div class="col-sm-8">
												<div class="custom-file col-sm-10">
													<input type="file" class="custom-file-input" id="withdrawal_sound" name="withdrawal_sound">
													<label class="custom-file-label" for="withdrawal_sound"><?php echo $this->lang->line('button_choose_file');?></label>
												</div>
												<p class="text-sm mb-0" id="uploaded_withdrawal_sound"><?php echo (isset($withdrawal_sound) ? $withdrawal_sound : '');?></p>
											</div>
										</div>
										<div class="form-group row">
											<label for="is_withdrawal_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_withdrawal_sound');?></label>
											<div class="col-sm-8">
												<input type="checkbox" id="is_withdrawal_sound" name="is_withdrawal_sound" value="1" <?php echo ((isset($is_withdrawal_sound) && $is_withdrawal_sound == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
										<?php endif;?>
										<?php if(permission_validation(PERMISSION_WITHDRAWAL_OFFLINE_NOTICE) == TRUE):?>
										<div class="form-group row">
											<label for="is_withdrawal_notice" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_withdrawal_notice');?></label>
											<div class="col-sm-8">
												<input type="checkbox" id="is_withdrawal_notice" name="is_withdrawal_notice" value="1" <?php echo ((isset($is_withdrawal_notice) && $is_withdrawal_notice == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>

										<?php
										$tab_html = '';
										$content_html = '';
										$lang = json_decode(PLAYER_SITE_LANGUAGES, TRUE);
										if(sizeof($lang) > 0)
										{
											$tab_html .= '<ul class="nav nav-tabs" id="custom-content-below-tab-'.TRANSFER_WITHDRAWAL.'" role="tablist">';
											$content_html .= '<div class="tab-content" id="custom-content-below-tabContent'.TRANSFER_WITHDRAWAL.'">';
											foreach($lang as $k => $v)
											{
												//$k = index
												//$v = language code

												$tab_active = (($k == 0) ? 'active' : '');
												$tab_html .= '<li class="nav-item">';
												$tab_html .= '<a class="nav-link ' . $tab_active . '" id="custom-content-below-'.TRANSFER_WITHDRAWAL.'-' . $v . '-tab" data-toggle="pill" href="#custom-content-below-'.TRANSFER_WITHDRAWAL.'-' . $v . '" role="tab" aria-controls="custom-content-below-'.TRANSFER_WITHDRAWAL.'-' . $v . '" aria-selected="true">' . $this->lang->line(get_site_language_name($v)) . '</a>';
												$tab_html .= '</li>';
											
												$content_active = (($k == 0) ? 'show active' : '');

												$content_html .= '<div class="tab-pane fade ' . $content_active . '" id="custom-content-below-'.TRANSFER_WITHDRAWAL.'-' . $v . '" role="tabpanel" aria-labelledby="custom-content-below-'.TRANSFER_WITHDRAWAL.'-' . $v . '-tab">';
													$content_html .= '<div class="form-group row mt-3">';
														$content_html .= '<label for="miscellaneous_title_'.TRANSFER_WITHDRAWAL.'_' . $v . '" class="col-sm-4 col-form-label">' . $this->lang->line('label_title') . '</label>';
														$content_html .= '<div class="col-7">';
															$content_html .= '<input type="text" class="form-control" id="miscellaneous_title_'.TRANSFER_WITHDRAWAL.'_' . $v . '" name="miscellaneous_title_'.TRANSFER_WITHDRAWAL.'_' . $v . '" value="'.(isset($miscellaneous_lang[TRANSFER_WITHDRAWAL][$v]['miscellaneous_title']) ? $miscellaneous_lang[TRANSFER_WITHDRAWAL][$v]['miscellaneous_title'] : '').'">';
														$content_html .= '</div>';
													$content_html .= '</div>';
													$content_html .= '<div class="form-group row mt-3">';
														$content_html .= '<label for="miscellaneous_content_'.TRANSFER_WITHDRAWAL.'_' . $v . '" class="col-sm-4 col-form-label">' . $this->lang->line('label_content') . '</label>';
														$content_html .= '<div class="col-7">';
															$content_html .= '<textarea class="form-control summernote" id="miscellaneous_content_'.TRANSFER_WITHDRAWAL.'_' . $v . '" name="miscellaneous_content_'.TRANSFER_WITHDRAWAL.'_' . $v . '" rows="4">'.(isset($miscellaneous_lang[TRANSFER_WITHDRAWAL][$v]['miscellaneous_content']) ? $miscellaneous_lang[TRANSFER_WITHDRAWAL][$v]['miscellaneous_content'] : '').'</textarea>';
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
										<?php endif;?>
										<div class="form-group row">
											<label for="player_bank_account" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_player_bank_account');?></label>
											<div class="col-sm-8">
												<input type="checkbox" id="player_bank_account" name="player_bank_account" value="1" <?php echo ((isset($player_bank_account) && $player_bank_account == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
										<div class="form-group row">
											<label for="player_bank_account_max" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_player_bank_account_max');?></label>
											<div class="col-sm-8">
												<input type="text" class="form-control col-sm-4" id="player_bank_account_max" name="player_bank_account_max" value="<?php echo (isset($player_bank_account_max) ? $player_bank_account_max : 0);?>">
											</div>
										</div>
										<div class="form-group row">
											<label for="player_credit_card_max" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_player_credit_card_max');?></label>
											<div class="col-sm-8">
												<input type="text" class="form-control col-sm-4" id="player_credit_card_max" name="player_credit_card_max" value="<?php echo (isset($player_credit_card_max) ? $player_credit_card_max : 0);?>">
											</div>
										</div>
										<div class="form-group row">
											<label for="is_player_change_password" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_player_change_password');?></label>
											<div class="col-7">
												<input type="checkbox" id="is_player_change_password" name="is_player_change_password" value="1" <?php echo ((isset($is_player_change_password) && $is_player_change_password == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
										<div class="form-group row">
											<label for="player_change_password_type" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_player_change_password_force');?></label>
											<div class="col-7">
												<input type="checkbox" id="player_change_password_type" name="player_change_password_type" value="1" <?php echo ((isset($player_change_password_type) && $player_change_password_type == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
										<?php if(permission_validation(PERMISSION_RISK_MANAGEMENT) == TRUE):?>
										<div class="form-group row">
											<label for="fingerprint_status" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_fingerprint');?></label>
											<div class="col-sm-8">
												<input type="checkbox" id="fingerprint_status" name="fingerprint_status" value="1" <?php echo ((isset($fingerprint_status) && $fingerprint_status == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
										<?php endif;?>
										<?php if(permission_validation(PERMISSION_LEVEL_MANAGEMENT) == TRUE):?>
										<div class="form-group row">
											<label for="player_level" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_player_ranking');?></label>
											<div class="col-sm-8">
												<input type="checkbox" id="player_level" name="player_level" value="1" <?php echo ((isset($player_level) && $player_level == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
										<?php endif;?>
										
										<?php if(permission_validation(PERMISSION_SYSTEM_EMAIL) == TRUE):?>
										<div class="form-group row">
											<label for="system_email" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_system_email');?></label>
											<div class="col-sm-8">
												<select class="select2 col-12 system_email" id="system_email" name="system_email[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
													<?php 
														if(isset($system_email)){
															$system_email_data = array_values(array_filter(explode(',', $system_email)));
															if(sizeof($system_email_data)>0){
																foreach($system_email_data as $system_email_data_row){
																	echo "<option value='".$system_email_data_row."' selected>".$system_email_data_row."</option>";
																}
															}
														}
													?>
												</select>
											</div>
										</div>
										<?php endif;?>
										<?php if(permission_validation(PERMISSION_RISK_MANAGEMENT) == TRUE):?>
										<div class="form-group row">
											<label class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_risk_management');?></label>
											<div class="col-sm-8">
												<input type="checkbox" id="risk_management" name="risk_management" value="1" <?php echo ((isset($risk_management) && $risk_management == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
										<div class="form-group row">
											<label for="risk_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_risk_sound_mp3');?></label>
											<div class="col-sm-8">
												<div class="custom-file col-sm-10">
													<input type="file" class="custom-file-input" id="risk_sound" name="risk_sound">
													<label class="custom-file-label" for="risk_sound"><?php echo $this->lang->line('button_choose_file');?></label>
												</div>
												<p class="text-sm mb-0" id="uploaded_risk_sound"><?php echo (isset($risk_sound) ? $risk_sound : '');?></p>
											</div>
										</div>
										<div class="form-group row">
											<label for="is_risk_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_risk_sound');?></label>
											<div class="col-7">
												<input type="checkbox" id="is_risk_sound" name="is_risk_sound" value="1" <?php echo ((isset($is_risk_sound) && $is_risk_sound == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
										<div class="form-group row">
											<label for="risk_frozen_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_risk_frozen_sound_mp3');?></label>
											<div class="col-sm-8">
												<div class="custom-file col-sm-10">
													<input type="file" class="custom-file-input" id="risk_frozen_sound" name="risk_frozen_sound">
													<label class="custom-file-label" for="risk_frozen_sound"><?php echo $this->lang->line('button_choose_file');?></label>
												</div>
												<p class="text-sm mb-0" id="uploaded_risk_sound"><?php echo (isset($risk_frozen_sound) ? $risk_frozen_sound : '');?></p>
											</div>
										</div>
										<div class="form-group row">
											<label for="is_risk_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_risk_frozen_sound');?></label>
											<div class="col-7">
												<input type="checkbox" id="is_risk_frozen_sound" name="is_risk_frozen_sound" value="1" <?php echo ((isset($is_risk_frozen_sound) && $is_risk_frozen_sound == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
										<div class="form-group row">
											<label for="risk_announcement_rate" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_risk_announcement_rate');?></label>
											<div class="col-sm-8 risk_announcement_rate">
												<select class="select2 col-12 risk_announcement_rate" id="risk_announcement_rate" name="risk_announcement_rate[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
													<?php 
														if(isset($risk_announcement_rate)){
															$risk_announcement_rate_data = array_values(array_filter(explode(',', $risk_announcement_rate)));
															if(sizeof($risk_announcement_rate_data)>0){
																foreach($risk_announcement_rate_data as $risk_announcement_rate_data_row){
																	echo "<option value='".$risk_announcement_rate_data_row."' selected>".$risk_announcement_rate_data_row."</option>";
																}
															}
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group row">
											<label for="risk_period" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_risk_period');?></label>
											<div class="col-sm-8">
												<select class="form-control select2bs4 col-sm-4" id="risk_period" name="risk_period">
													<?php
														$risk_period_type_data = risk_period_type();
														if(!empty($risk_period_type_data) && sizeof($risk_period_type_data)){
															foreach($risk_period_type_data as $k => $v)
															{
																if(isset($risk_period)) 
																{
																	if($k == $risk_period)
																	{
																		echo '<option value="' . $k . '" selected="selected">' . $this->lang->line($v) . '</option>';
																	}else{
																		echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
																	}
																}else{
																	echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
																}
															}
														}
													?>
												</select>
											</div>
										</div>
										<?php endif;?>
										<?php if(permission_validation(PERMISSION_BLACKLIST_ANNOUNCEMENT) == TRUE):?>
										<div class="form-group row">
											<label for="blacklist_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_blacklist_sound_mp3');?></label>
											<div class="col-sm-8">
												<div class="custom-file col-sm-10">
													<input type="file" class="custom-file-input" id="blacklist_sound" name="blacklist_sound">
													<label class="custom-file-label" for="blacklist_sound"><?php echo $this->lang->line('button_choose_file');?></label>
												</div>
												<p class="text-sm mb-0" id="uploaded_blacklist_sound"><?php echo (isset($blacklist_sound) ? $blacklist_sound : '');?></p>
											</div>
										</div>
		
										<div class="form-group row">
											<label for="is_blacklist_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_blacklist_sound');?></label>
											<div class="col-7">
												<input type="checkbox" id="is_blacklist_sound" name="is_blacklist_sound" value="1" <?php echo ((isset($is_blacklist_sound) && $is_blacklist_sound == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
										<?php endif;?>
										<?php if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE_ANNOUNCEMENT) == TRUE):?>
										<div class="form-group row">
											<label for="player_bank_image_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_player_bank_image_sound_mp3');?></label>
											<div class="col-sm-8">
												<div class="custom-file col-sm-10">
													<input type="file" class="custom-file-input" id="player_bank_image_sound" name="player_bank_image_sound">
													<label class="custom-file-label" for="player_bank_image_sound"><?php echo $this->lang->line('button_choose_file');?></label>
												</div>
												<p class="text-sm mb-0" id="uploaded_player_bank_image_sound"><?php echo (isset($player_bank_image_sound) ? $player_bank_image_sound : '');?></p>
											</div>
										</div>
		
										<div class="form-group row">
											<label for="is_player_bank_image_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_player_bank_image_sound');?></label>
											<div class="col-7">
												<input type="checkbox" id="is_player_bank_image_sound" name="is_player_bank_image_sound" value="1" <?php echo ((isset($is_player_bank_image_sound) && $is_player_bank_image_sound == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
											</div>
										</div>
										<?php endif;?>
									</div>
									<!-- /.card-body -->
									<div class="card-footer">
										<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
									</div>
									<!-- /.card-footer -->
								<?php echo form_close();?>
							</div>
							<!-- /.card -->
						</div>
						<!--/.col (left) -->
						<!-- right column -->
						<div class="col-md-6">

						</div>
						<!--/.col (right) -->
					</div>
					<!-- /.row -->
				</div><!-- /.container-fluid -->
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->

		<!-- Main Footer -->
		<?php $this->load->view('parts/footer_page');?>
	</div>
	<!-- ./wrapper -->

	<!-- REQUIRED SCRIPTS -->
	<?php $this->load->view('parts/footer_js');?>

	<script type="text/javascript">
		$(document).ready(function() {
			$('.select2.system_email').select2({
				tags: true,
				casesensitive: false,
			});
			$('.select2.risk_announcement_rate').select2({
				tags: true,
				casesensitive: false,
			});

			var is_allowed = true;
			var form = $('#miscellaneous-form');
			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			$('.game_type_all').on('switchChange.bootstrapSwitch', function (event, state) {
				if(state == true){
					$('.game_type').bootstrapSwitch('state', true);
				}
			});

			$('.game_type').on('switchChange.bootstrapSwitch', function (event, state) {
				if(state == false){
					$('.game_type_all').bootstrapSwitch('state', $(this).prop('checked'));
				}
			});
			bsCustomFileInput.init();
			
			$.validator.setDefaults({
				submitHandler: function () {
					if(is_allowed == true) {
						is_allowed = false;
						
						var file_form = form[0];
						var formData = new FormData(file_form);
						$.each($("input[type='file']")[0].files, function(i, file) {
							formData.append('file', file);
						});
						
						$.ajax({url: form.attr('action'),
							data: formData,
							type: 'post',	
							processData: false,
							contentType: false,								
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
								
								if(json.status == '<?php echo EXIT_SUCCESS;?>'){
									message = json.msg;
									msg_icon = 1;
									
									if(json.deposit_file_name != '') {
										$('#uploaded_deposit_sound').html(json.deposit_file_name);
									}
									
									if(json.withdrawal_file_name != '') {
										$('#uploaded_withdrawal_sound').html(json.withdrawal_file_name);
									}

									if(json.risk_file_name != '') {
										$('#uploaded_risk_sound').html(json.risk_file_name);
									}

									if(json.player_bank_image_sound_file_name != '') {
										$('#uploaded_player_bank_image_sound').html(json.player_bank_image_sound_file_name);
									}
									
									$('.custom-file-label').html('<?php echo $this->lang->line('button_choose_file');?>');
								}
								else {
									if(json.msg.min_deposit_error != '') {
										message = json.msg.min_deposit_error;
									}
									else if(json.msg.max_deposit_error != '') {
										message = json.msg.max_deposit_error;
									}
									else if(json.msg.min_withdrawal_error != '') {
										message = json.msg.min_withdrawal_error;
									}
									else if(json.msg.max_withdrawal_error != '') {
										message = json.msg.max_withdrawal_error;
									}
									else if(json.msg.win_loss_suspend_limit_error != '') {
										message = json.msg.win_loss_suspend_limit_error;
									}
									else if(json.msg.system_type_error != '') {
										message = json.msg.system_type_error;
									}
									else if(json.msg.player_bank_account_max_error != '') {
										message = json.msg.player_bank_account_max_error;
									}
									else if(json.msg.general_error != '') {
										message = json.msg.general_error;
									}
								}
								
								layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
								
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
							},
							error: function (request,error) {
							}
						});  
					}
				}
			});
			
			form.validate({
				rules: {
					min_deposit: {
						required: true,
						digits: true
					},
					max_deposit: {
						required: true,
						digits: true
					},
					min_withdrawal: {
						required: true,
						digits: true
					},
					max_withdrawal: {
						required: true,
						digits: true
					},
					win_loss_suspend_limit: {
						required: true,
						digits: true
					},
					player_bank_account_max: {
						required: true,
						digits: true	
					},
					player_credit_card_max: {
						digits: true	
					},
					system_type: {
						required: true
					},
				},
				messages: {
					min_deposit: {
						required: "<?php echo $this->lang->line('error_enter_min_deposit');?>",
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_min_deposit')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					max_deposit: {
						required: "<?php echo $this->lang->line('error_enter_max_deposit');?>",
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_max_deposit')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					min_withdrawal: {
						required: "<?php echo $this->lang->line('error_enter_min_withdrawal');?>",
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_min_withdrawal')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					max_withdrawal: {
						required: "<?php echo $this->lang->line('error_enter_max_withdrawal');?>",
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_max_withdrawal')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					win_loss_suspend_limit: {
						required: "<?php echo $this->lang->line('error_enter_win_loss_suspend_limit');?>",
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_win_loss_suspend_limit')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					player_bank_account_max: {
						required: "<?php echo $this->lang->line('error_enter_player_bank_account_max');?>",
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_player_bank_account_max')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					player_credit_card_max: {
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_player_credit_card_max')), $this->lang->line('error_only_digits_allowed'));?>",
					},
					system_type: {
						required: "<?php echo $this->lang->line('error_select_type');?>",
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


		$(document).on('keypress', '.risk_announcement_rate .select2-search__field', function () {
		    $(this).val($(this).val().replace(/[^\d].+/, ""));
		    if ((event.which < 48 || event.which > 57)) {
		      event.preventDefault();
		    }
		});
	</script>
</body>
</html>
