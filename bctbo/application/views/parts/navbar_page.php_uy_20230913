<nav class="main-header navbar navbar-expand navbar-white navbar-light">
	<!-- Left navbar links -->
	<ul class="navbar-nav">
		<li class="nav-item">
			<a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
		</li>
	</ul>
	<!-- Right navbar links -->
	<ul class="navbar-nav ml-auto">
		<?php if(permission_validation(PERMISSION_BLACKLIST_ANNOUNCEMENT) == TRUE || permission_validation(PERMISSION_PLAYER_RISK_REPORT) == TRUE || permission_validation(PERMISSION_WITHDRAWAL_OFFLINE_ANNOUNCEMENT) == TRUE || permission_validation(PERMISSION_DEPOSIT_OFFLINE_ANNOUNCEMENT) == TRUE || permission_validation(PERMISSION_DEPOSIT_ONLINE_ANNOUNCEMENT) == TRUE || permission_validation(PERMISSION_DEPOSIT_CREDIT_CARD_ANNOUNCEMENT) == TRUE || permission_validation(PERMISSION_DEPOSIT_HYPERMARKET_ANNOUNCEMENT) == TRUE || permission_validation(PERMISSION_PLAYER_BANK_IMAGE_ANNOUNCEMENT) == TRUE):?>
		<li class="nav-item dropdown">
			<a class="nav-link" data-toggle="dropdown" href="#">
				<i class="fas fa-bullhorn"></i>
			</a>
			<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
				<?php
					$announcement_dropdown = '';
					$announcement = announcement_type();
					if(!empty($announcement)){
						foreach($announcement as $k => $v){
							$announcement_dropdown .= '<a onclick="set_announcement('.$k.')" class="dropdown-item">';
							$announcement_dropdown .= '<i id="announcement_icon_'.$k.'" class="fas '.((empty($this->session->userdata('announcement_alert_'.$k)))?'fa-volume-mute':'fa-volume-up').' mr-2">';
							$announcement_dropdown .= '</i>';
							$announcement_dropdown .= $this->lang->line($v);
							$announcement_dropdown .= '</a>';
						}
					}
					echo $announcement_dropdown;
				?>
			</div>
		</li>
		<?php endif;?>
		<li class="nav-item dropdown">
			<a class="nav-link" data-toggle="dropdown" href="#">
				<i class="fas fa-globe-asia"></i>
				<span class="badge badge-success navbar-badge"><?php echo strtoupper(get_language_code());?></span>
			</a>
			<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
				<?php
					$lang_dropdown = '';
					$lang = json_decode(SYSTEM_LANGUAGES);
					for($i=0;$i<sizeof($lang);$i++)
					{
						$lang_dropdown .= '<a href="' . site_url('language/change/' . $lang[$i]) . '" class="dropdown-item">';
						$lang_dropdown .= $this->lang->line('system_lang_' . $lang[$i]);
						$lang_dropdown .= '</a>';
					}
					echo $lang_dropdown;
				?>
			</div>
		</li>
		<li class="nav-item dropdown">
			<a class="nav-link" id="player_online_link" data-toggle="dropdown" href="#" aria-expanded="true">
				<i class="far fa-user"></i>
				<span class="badge badge-warning navbar-badge" id="player_online_span">0</span>
			</a>
			<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;" id="total_player_online_content">
				<span class="dropdown-item dropdown-header"><?php echo $this->lang->line('label_player_just_online');?></span>
	        </div>
		</li>
		<?php if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE_ANNOUNCEMENT) == TRUE):?>
		<li class="nav-item">
			<a class="nav-link" id="player_bank_image_link" data-toggle="dropdown" href="#" aria-expanded="true">
				<i class="fas fa-exclamation-triangle"></i>
				<span class="badge badge-primary navbar-badge" id="player_bank_image_span">0</span>
			</a>
			<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;" id="total_player_bank_image_content">
				<span class="dropdown-item dropdown-header"><?php echo $this->lang->line('label_player_bank_image');?></span>
	        </div>
		</li>
		<?php endif;?>
		<?php if(permission_validation(PERMISSION_BLACKLIST_ANNOUNCEMENT) == TRUE):?>
		<li class="nav-item">
			<a class="nav-link" id="player_blacklist_link" data-toggle="dropdown" href="#" aria-expanded="true">
				<i class="fas fa-exclamation-triangle"></i>
				<span class="badge badge-warning navbar-badge" id="player_blacklist_span">0</span>
			</a>
			<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;" id="total_player_blacklist_content">
				<span class="dropdown-item dropdown-header"><?php echo $this->lang->line('label_blacklist');?></span>
	        </div>
		</li>
		<?php endif;?>
		<?php if(permission_validation(PERMISSION_PLAYER_RISK_REPORT) == TRUE):?>
		<li class="nav-item">
			<a class="nav-link" id="player_risk_link" data-toggle="dropdown" href="#" aria-expanded="true">
				<i class="fas fa-exclamation-triangle"></i>
				<span class="badge badge-dark navbar-badge" id="player_risk_span">0</span>
			</a>
			<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;" id="total_player_risk_content">
				<span class="dropdown-item dropdown-header"><?php echo $this->lang->line('label_risk_management');?></span>
	        </div>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="player_risk_frozen_link" data-toggle="dropdown" href="#" aria-expanded="true">
				<i class="fas fa-exclamation-triangle"></i>
				<span class="badge badge-danger navbar-badge" id="player_risk_frozen_span">0</span>
			</a>
			<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;" id="total_player_risk_frozen_content">
				<span class="dropdown-item dropdown-header"><?php echo $this->lang->line('label_risk_frozen_management');?></span>
	        </div>
		</li>
		<?php endif;?>
		<?php if(permission_validation(PERMISSION_WITHDRAWAL_OFFLINE_ANNOUNCEMENT) == TRUE):?>
		<li class="nav-item">
			<a class="nav-link" id="player_withdraw_link" data-toggle="dropdown" href="#" aria-expanded="true">
				<i class="far fa-bell"></i>
				<span class="badge badge-danger navbar-badge" id="player_withdraw_span">0</span>
			</a>
			<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;" id="total_player_withdraw_content">
				<span class="dropdown-item dropdown-header"><?php echo $this->lang->line('withdrawal_offline_banking');?></span>
	        </div>
		</li>
		<?php endif;?>
		<?php if(permission_validation(PERMISSION_DEPOSIT_OFFLINE_ANNOUNCEMENT) == TRUE):?>
		<li class="nav-item">
			<a class="nav-link" id="player_deposit_offline_link" data-toggle="dropdown" href="#" aria-expanded="true">
				<i class="far fa-bell"></i>
				<span class="badge badge-primary navbar-badge" id="player_deposit_offline_span">0</span>
			</a>
			<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;" id="total_player_deposit_offline_content">
				<span class="dropdown-item dropdown-header"><?php echo $this->lang->line('deposit_offline_banking');?></span>
	        </div>
		</li>
		<?php endif;?>
		<?php if(permission_validation(PERMISSION_DEPOSIT_ONLINE_ANNOUNCEMENT) == TRUE):?>
		<li class="nav-item">
			<a class="nav-link" id="player_deposit_online_link" data-toggle="dropdown" href="#" aria-expanded="true">
				<i class="far fa-bell"></i>
				<span class="badge badge-success navbar-badge" id="player_deposit_online_span">0</span>
			</a>
			<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;" id="total_player_deposit_online_content">
				<span class="dropdown-item dropdown-header"><?php echo $this->lang->line('deposit_online_banking');?></span>
	        </div>
		</li>
		<?php endif;?>
		<?php if(permission_validation(PERMISSION_DEPOSIT_CREDIT_CARD_ANNOUNCEMENT) == TRUE):?>
		<li class="nav-item">
			<a class="nav-link" id="player_deposit_credit_card_link" data-toggle="dropdown" href="#" aria-expanded="true">
				<i class="far fa-bell"></i>
				<span class="badge badge-warning navbar-badge" id="player_deposit_credit_card_span">0</span>
			</a>
			<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;" id="total_player_deposit_credit_card_content">
				<span class="dropdown-item dropdown-header"><?php echo $this->lang->line('deposit_credit_card');?></span>
	        </div>
		</li>
		<?php endif;?>
		<?php if(permission_validation(PERMISSION_DEPOSIT_HYPERMARKET_ANNOUNCEMENT) == TRUE):?>
		<li class="nav-item">
			<a class="nav-link" id="player_deposit_hypermart_link" data-toggle="dropdown" href="#" aria-expanded="true">
				<i class="far fa-bell"></i>
				<span class="badge badge-info navbar-badge" id="player_deposit_hypermart_span">0</span>
			</a>
			<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;" id="total_player_deposit_hypermart_content">
				<span class="dropdown-item dropdown-header"><?php echo $this->lang->line('deposit_hypermart');?></span>
	        </div>
		</li>
		<?php endif;?>
	</ul>
</nav>