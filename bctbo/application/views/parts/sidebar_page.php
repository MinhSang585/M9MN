<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo site_url('home');?>" class="brand-link">
		<div class="brand-image"><?php echo $this->lang->line('system_name');?></div>
	</a>
    <!-- Sidebar -->
    <div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="<?php echo base_url('assets/dist/img/avatar5.png');?>" class="img-circle elevation-2" alt="<?php echo $this->session->userdata('username');?>">
			</div>
			<div class="info">
				<a href="<?php echo site_url('profile');?>" class="d-block"><?php echo $this->session->userdata('username');?></a>
			</div>
		</div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class
				   with font-awesome or any other icon font library -->
				<?php if(permission_validation(PERMISSION_HOME) == TRUE):?>		
				<li class="nav-item">
					<a href="<?php echo site_url('home');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'home') ? 'active' : '');?>">
						<i class="fas fa-home nav-icon"></i>
						<p><?php echo $this->lang->line('title_home');?></p>
					</a>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_SUB_ACCOUNT_VIEW) == TRUE):?>				
				<li class="nav-item">
					<a href="<?php echo site_url('account');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'account') ? 'active' : '');?>">
						<i class="fas fa-user-cog nav-icon"></i>
						<p><?php echo $this->lang->line('title_sub_account');?></p>
					</a>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_USER_VIEW) == TRUE):?>	
				<li class="nav-item">
					<a href="<?php echo site_url('user');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'user') ? 'active' : '');?>">
						<i class="fas fa-user-tie nav-icon"></i>
						<p><?php echo $this->lang->line('title_user');?></p>
					</a>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_AGENT_VIEW) == TRUE):?>	
				<li class="nav-item">
					<a href="<?php echo site_url('agent');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'agent') ? 'active' : '');?>">
						<i class="fas fa-user-tie nav-icon"></i>
						<p>Agent</p>
					</a>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_VIP_REWARD_VIEW) == TRUE):?>	
				<li class="nav-item">
					<a href="<?php echo site_url('vipreward');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'vipreward') ? 'active' : '');?>">
						<i class="fas fa-solid fa-award nav-icon"></i>
						<p>VIP Reward</p>
					</a>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_PLAYER_VIEW) == TRUE):?>	
				<li class="nav-item">
					<a href="<?php echo site_url('player');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'player' && $this->uri->segment(2) == '') ? 'active' : '');?>">
						<i class="fas fa-user nav-icon"></i>
						<p><?php echo $this->lang->line('title_player');?></p>
					</a>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_PLAYER_AGENT_VIEW) == TRUE):?>	
				<li class="nav-item">
					<a href="<?php echo site_url('player/agent');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'player' && $this->uri->segment(2) == 'agent') ? 'active' : '');?>">
						<i class="fas fa-user nav-icon"></i>
						<p><?php echo $this->lang->line('title_player_agent');?></p>
					</a>
				</li>
				<?php endif; ?>
				<?php if(permission_validation(PERMISSION_DEPOSIT_VIEW) == TRUE):?>	
				<li class="nav-item">
					<a href="<?php echo site_url('deposit');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'deposit' && $this->uri->segment(2) == '') ? 'active' : '');?>">
						<i class="fas fa-donate nav-icon"></i>
						<p><?php echo $this->lang->line('title_deposit');?></p>
					</a>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_AGENT_PLAYER_DEPOSIT_VIEW) == TRUE):?>	
				<li class="nav-item">
					<a href="<?php echo site_url('deposit/agent_player_deposit');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'deposit' && $this->uri->segment(2) == 'agent_player_deposit') ? 'active' : '');?>">
						<i class="fas fa-donate nav-icon"></i>
						<p><?php echo $this->lang->line('title_deposit');?></p>
					</a>
				</li>
				<?php endif; ?>
				<?php if(permission_validation(PERMISSION_WITHDRAWAL_VIEW) == TRUE):?>	
				<li class="nav-item">
					<a href="<?php echo site_url('withdrawal');?>" class="nav-link <?php echo ((($this->uri->segment(1) == 'withdrawal') && $this->uri->segment(2) != 'fee_setting') ? 'active' : '');?>">
						<i class="fas fa-hand-holding-usd nav-icon"></i>
						<p><?php echo $this->lang->line('title_withdrawal');?></p>
					</a>
				</li>
				<?php endif;?>
				<!-- This is KYC-->
				<?php if(permission_validation(PERMISSION_KYC_VIEW) == TRUE):?>	
				<li class="nav-item">
					<a href="<?php echo site_url('kyc');?>" class="nav-link <?php echo ((($this->uri->segment(1) == 'kyc') && $this->uri->segment(2) != 'fee_setting') ? 'active' : '');?>">
						<i class="fas fa-registered nav-icon"></i>
						<p><?php echo $this->lang->line('label_kyc');?></p>
					</a>
				</li>
				<?php endif;?>
				<!-- This is KYC-->
				<?php if(permission_validation(PERMISSION_BANK_PLAYER_USER_VIEW) == TRUE || permission_validation(PERMISSION_PLAYER_BANK_IMAGE) == TRUE || permission_validation(PERMISSION_PLAYER_BANK_IMAGE_ANNOUNCEMENT) == TRUE || permission_validation(PERMISSION_BANK_AGENT_PLAYER_USER_VIEW) == TRUE ):?>
				<li class="nav-item has-treeview <?php echo ((($this->uri->segment(1) == 'bank' && ($this->uri->segment(2) == 'player' || $this->uri->segment(2) == 'player_bank_image_save' || $this->uri->segment(2) == 'bankplayerimage'))) ? 'menu-open' : '');?>">
					<a href="#" class="nav-link <?php echo ((($this->uri->segment(1) == 'bank' && ($this->uri->segment(2) == 'player' || $this->uri->segment(2) == 'player_bank_image_save' || $this->uri->segment(2) == 'bankplayerimage'))) ? 'active' : '');?>">
						<i class="nav-icon fas fa-table"></i>
						<p>
							<?php echo $this->lang->line('title_bank_player');?>
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<?php if(permission_validation(PERMISSION_BANK_PLAYER_USER_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('bank/player');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'bank' && $this->uri->segment(2) == 'player') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_bank_player');?></p>
							</a>
						</li>
						<?php endif;?>
						<!-- Bank Player Image Start-->
						<?php if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('bank/bankplayerimage');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'bank' && $this->uri->segment(2) == 'bankplayerimage') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_bank_player_image');?></p>
							</a>
						</li>
						<!-- Bank Player Image End -->
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_BANK_AGENT_PLAYER_USER_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('bank/agent_player');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'bank' && $this->uri->segment(2) == 'agent_player') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_bank_player');?></p>
							</a>
						</li>
						<?php endif; ?>
						<?php if(permission_validation(PERMISSION_PLAYER_BANK_IMAGE_ANNOUNCEMENT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('bank/player_bank_image_save');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'bank' && $this->uri->segment(2) == 'player_bank_image_save') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_player_bank_image_annoucement');?></p>
							</a>
						</li>
						<?php endif; ?>
					</ul>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_PLAYER_BONUS_VIEW) == TRUE):?>	
				<li class="nav-item">
					<a href="<?php echo site_url('bonus/player');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'bonus' && $this->uri->segment(2) == 'player') ? 'active' : '');?>">
						<i class="fas fa-gift nav-icon"></i>
						<p><?php echo $this->lang->line('title_player_bonus');?></p>
					</a>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_PLAYER_PROMOTION_VIEW) == TRUE):?>	
				<li class="nav-item">
					<a href="<?php echo site_url('playerpromotion');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'playerpromotion') ? 'active' : '');?>">
						<i class="fas fa-gifts nav-icon"></i>
						<p><?php echo $this->lang->line('title_player_promotion');?></p>
					</a>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE || permission_validation(PERMISSION_WIN_LOSS_REPORT_PLAYER) == TRUE || permission_validation(PERMISSION_TRANSACTION_REPORT) == TRUE || permission_validation(PERMISSION_POINT_TRANSACTION_REPORT) == TRUE || permission_validation(PERMISSION_CASH_TRANSACTION_REPORT) == TRUE || permission_validation(PERMISSION_WALLET_TRANSACTION_REPORT) == TRUE || permission_validation(PERMISSION_LOGIN_REPORT) == TRUE || permission_validation(PERMISSION_REWARD_TRANSACTION_REPORT) == TRUE || permission_validation(PERMISSION_VERIFY_CODE_REPORT) == TRUE || permission_validation(PERMISSION_PLAYER_RISK_REPORT) == TRUE || permission_validation(PERMISSION_YEARLY_REPORT) == TRUE || permission_validation(PERMISSION_PLAYER_WITHDRAWAL_VERIFY_REPORT) == TRUE || permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT) == TRUE || permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_YEARLY_REPORT) == TRUE  || permission_validation(PERMISSION_TAG_PROCESS_REPORT) == TRUE):?>
				<li class="nav-item has-treeview <?php echo (($this->uri->segment(1) == 'report') ? 'menu-open' : '');?>">
					<a href="#" class="nav-link <?php echo (($this->uri->segment(1) == 'report') ? 'active' : '');?>">
						<i class="nav-icon fas fa-table"></i>
						<p>
							<?php echo $this->lang->line('title_report');?>
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<?php if(permission_validation(PERMISSION_WIN_LOSS_REPORT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/winloss_sum');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'winloss_sum') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_win_loss_report');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_WIN_LOSS_REPORT_PLAYER) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/winloss_player');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'winloss_player') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_win_loss_report_player');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_YEARLY_REPORT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/yearly_report');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'yearly_report') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_yearly_report');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_PLAYER_WITHDRAWAL_VERIFY_REPORT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/withdrawal_verify');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'withdrawal_verify') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_withdraw_verify_report');?></p>
							</a>
						</li>
						<?php endif; ?>
						<?php if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_REPORT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/register_deposit_rate');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'register_deposit_rate') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_register_deposit_rate_report');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_REGISTER_DEPOSIT_RATE_YEARLY_REPORT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/register_deposit_rate_yearly');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'register_deposit_rate_yearly') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_register_deposit_rate_yearly_report');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_TRANSACTION_REPORT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/transaction');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'transaction') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_transaction_report');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_POINT_TRANSACTION_REPORT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/point');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'point') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_point_transaction_report');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_POINT_TRANSACTION_REPORT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/point_agent');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'point_agent') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_point_transaction_report_agent');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_CASH_TRANSACTION_REPORT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/cash');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'cash') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_cash_transaction_report');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_REWARD_TRANSACTION_REPORT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/reward');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'reward') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_reward_transaction_report');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_VERIFY_CODE_REPORT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/verify_code');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'verify_code') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_verify_code_report');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_WALLET_TRANSACTION_REPORT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/wallet');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'wallet') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_wallet_transaction_report');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_PLAYER_RISK_REPORT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/player_risk');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'player_risk') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_player_risk_report');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_TAG_PROCESS_REPORT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/tag_process');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'tag_process') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_tag_process_report');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_LOGIN_REPORT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/login');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'login') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_login_report');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_BANK_REPORT_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('report/bank_report');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'report' && $this->uri->segment(2) == 'bank_report') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_bank_report');?></p>
							</a>
						</li>
						<?php endif;?>
					</ul>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_REWARD_VIEW) == TRUE):?>	
				<li class="nav-item">
					<a href="<?php echo site_url('reward');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'reward') ? 'active' : '');?>">
						<i class="fas fa-coins nav-icon"></i>
						<p><?php echo $this->lang->line('title_player_reward');?></p>
					</a>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_WALLET_TRANSACTION_PENDING_VIEW) == TRUE):?>	
				<li class="nav-item">
					<a href="<?php echo site_url('transaction');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'transaction') ? 'active' : '');?>">
						<i class="fas fa-money-bill-alt nav-icon"></i>
						<p><?php echo $this->lang->line('title_wallet_transaction_pending');?></p>
					</a>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_FINGERPRINT_VIEW) == TRUE):?>
				<li class="nav-item">
					<a href="<?php echo site_url('fingerprint');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'fingerprint') ? 'active' : '');?>">
						<i class="fas fa-fingerprint nav-icon"></i>
						<p><?php echo $this->lang->line('title_fingerprint');?></p>
					</a>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_BLACKLIST_VIEW) == TRUE || permission_validation(PERMISSION_BLACKLIST_REPORT) == TRUE || permission_validation(PERMISSION_BLACKLIST_IMPORT_VIEW) == TRUE):?>
				<li class="nav-item has-treeview <?php echo (($this->uri->segment(1) == 'blacklist') ? 'menu-open' : '');?>">
					<a href="#" class="nav-link <?php echo (($this->uri->segment(1) == 'blacklist') ? 'active' : '');?>">
						<i class="fas fa-shield-alt nav-icon"></i>
						<p>
							<?php echo $this->lang->line('title_blacklist');?>
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<?php if(permission_validation(PERMISSION_BLACKLIST_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('blacklist');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'blacklist' AND $this->uri->segment(2) == '') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_blacklist');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_BLACKLIST_IMPORT_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('blacklist/import');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'blacklist' AND $this->uri->segment(2) == 'import') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_blacklist_import');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_BLACKLIST_REPORT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('blacklist/report');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'blacklist' && $this->uri->segment(2) == 'report') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_blacklist_report');?></p>
							</a>
						</li>
						<?php endif;?>
					</ul>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_WHITELIST_VIEW) == TRUE):?>
				<li class="nav-item">
					<a href="<?php echo site_url('whitelist');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'whitelist') ? 'active' : '');?>">
						<i class="fas fa-user-shield nav-icon"></i>
						<p><?php echo $this->lang->line('title_whitelist');?></p>
					</a>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_SYSTEM_MESSAGE_VIEW) == TRUE || permission_validation(PERMISSION_SYSTEM_MESSAGE_USER_VIEW) == TRUE):?>
				<li class="nav-item has-treeview <?php echo (($this->uri->segment(1) == 'message') ? 'menu-open' : '');?>">
					<a href="#" class="nav-link <?php echo (($this->uri->segment(1) == 'message') ? 'active' : '');?>">
						<i class="nav-icon fas fa-envelope"></i>
						<p>
							<?php echo $this->lang->line('title_message');?>
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<?php if(permission_validation(PERMISSION_SYSTEM_MESSAGE_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('message');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'message' AND $this->uri->segment(2) != 'player') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_message_list');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_SYSTEM_MESSAGE_USER_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('message/player');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'message' && $this->uri->segment(2) == 'player') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_message_user_list');?></p>
							</a>
						</li>
						<?php endif;?>
					</ul>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_ADMIN_LOG_VIEW) == TRUE || permission_validation(PERMISSION_ADMIN_PLAYER_LOG_VIEW) == TRUE || permission_validation(PERMISSION_SUB_ACCOUNT_LOG_VIEW) == TRUE || permission_validation(PERMISSION_SUB_ACCOUNT_PLAYER_LOG_VIEW) == TRUE):?>
				<li class="nav-item has-treeview <?php echo (($this->uri->segment(1) == 'log') ? 'menu-open' : '');?>">
					<a href="#" class="nav-link <?php echo (($this->uri->segment(1) == 'log') ? 'active' : '');?>">
						<i class="nav-icon fas fa-clipboard-list"></i>
						<p>
							<?php echo $this->lang->line('title_log');?>
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<?php if(permission_validation(PERMISSION_ADMIN_LOG_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('log/admin_log');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'log' && $this->uri->segment(2) == 'admin_log') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_admin_log');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_ADMIN_PLAYER_LOG_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('log/admin_player_log');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'log' && $this->uri->segment(2) == 'admin_player_log') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_admin_player_log');?></p>
							</a>
						</li>
						<?php endif;?>
						
						<!-- title_player_log_report -->
						<?php if(permission_validation(PERMISSION_PLAYER_LOG_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('log/player_log');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'log' && $this->uri->segment(2) == 'player_log') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_player_log_report');?></p>
							</a>
						</li>
						<?php endif;?>
						<!-- title_player_log_report -->
						
						<?php if(permission_validation(PERMISSION_SUB_ACCOUNT_LOG_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('log/sub_account_log');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'log' && $this->uri->segment(2) == 'sub_account_log') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_sub_account_log');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_SUB_ACCOUNT_PLAYER_LOG_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('log/sub_account_player_log');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'log' && $this->uri->segment(2) == 'sub_account_player_log') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_sub_account_player_log');?></p>
							</a>
						</li>
						<?php endif;?>
					</ul>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_BLOG_VIEW) == TRUE || permission_validation(PERMISSION_BLOG_CATEGORY_VIEW) == TRUE):?>
				<li class="nav-item has-treeview <?php echo (($this->uri->segment(1) == 'blog') ? 'menu-open' : '');?>">
					<a href="#" class="nav-link <?php echo (($this->uri->segment(1) == 'blog') ? 'active' : '');?>">
						<i class="nav-icon fas fa-clipboard-list"></i>
						<p>
							<?php echo $this->lang->line('title_blog');?>
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<?php if(permission_validation(PERMISSION_BLOG_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('blog');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'blog' && $this->uri->segment(2) == '') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_blog');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_BLOG_CATEGORY_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('blog/category');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'blog' && $this->uri->segment(2) == 'category') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_blog_category');?></p>
							</a>
						</li>
						<?php endif;?>
					</ul>
				</li>
				<?php endif;?>
				<?php if(permission_validation(PERMISSION_MARQUEE_VIEW) == TRUE || permission_validation(PERMISSION_ANNOUNCEMENT_VIEW) == TRUE || permission_validation(PERMISSION_BANK_VIEW) == TRUE || permission_validation(PERMISSION_BANK_ACCOUNT_VIEW) == TRUE || permission_validation(PERMISSION_BANNER_VIEW) == TRUE || permission_validation(PERMISSION_CONTACT_VIEW) == TRUE || permission_validation(PERMISSION_GAME_VIEW) == TRUE || permission_validation(PERMISSION_GAME_MAINTENANCE_VIEW) == TRUE || permission_validation(PERMISSION_GROUP_VIEW) == TRUE || permission_validation(PERMISSION_MISCELLANEOUS_UPDATE) == TRUE || permission_validation(PERMISSION_PROMOTION_VIEW) == TRUE || permission_validation(PERMISSION_SEO_VIEW) == TRUE  || permission_validation(PERMISSION_AVATAR_VIEW) == TRUE || permission_validation(PERMISSION_BONUS_VIEW) == TRUE || permission_validation(PERMISSION_LEVEL_VIEW) == TRUE || permission_validation(PERMISSION_MATCH_VIEW) == TRUE|| permission_validation(PERMISSION_LEVEL_EXECUTE_VIEW) || permission_validation(PERMISSION_PAYMENT_GATEWAY_VIEW) == TRUE  || permission_validation(PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_VIEW) == TRUE || permission_validation(PERMISSION_CURRENCIES_VIEW) == TRUE || permission_validation(PERMISSION_WITHDRAWAL_FEE_RATE_VIEW) == TRUE || permission_validation(PERMISSION_USER_ROLE_VIEW) == TRUE || permission_validation(PERMISSION_TAG_VIEW) == TRUE || permission_validation(PERMISSION_TAG_PLAYER_VIEW) == TRUE || permission_validation(PERMISSION_CONTENT_VIEW) == TRUE || permission_validation(PERMISSION_PAYMENT_GATEWAY_LIMITED_VIEW) == TRUE || permission_validation(PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_VIEW) == TRUE || permission_validation(PERMISSION_BANK_VERIFY_SUBMIT) == TRUE || permission_validation(PERMISSION_SUB_GAME_VIEW) == TRUE):?>
				<li class="nav-item has-treeview <?php echo (($this->uri->segment(1) == 'marquee' || $this->uri->segment(1) == 'announcement' || ($this->uri->segment(1) == 'bank' && $this->uri->segment(2) == '') || $this->uri->segment(1) == 'banner' || $this->uri->segment(1) == 'contact' || $this->uri->segment(1) == 'game' || $this->uri->segment(1) == 'group' || $this->uri->segment(1) == 'miscellaneous' || $this->uri->segment(1) == 'promotion' || $this->uri->segment(1) == 'seo' || $this->uri->segment(1) == 'avatar' || $this->uri->segment(1) == 'level' || ($this->uri->segment(1) == 'bonus' && $this->uri->segment(2) == '') || ($this->uri->segment(1) == 'bank' && $this->uri->segment(2) == 'account') || ($this->uri->segment(1) == 'game' && $this->uri->segment(2) == 'maintenance') || $this->uri->segment(1) == 'match' || ($this->uri->segment(1) == 'level' && $this->uri->segment(2) == 'level_execute') || $this->uri->segment(1) == 'paymentgateway' || $this->uri->segment(1) == 'currencies'|| ($this->uri->segment(1) == 'withdrawal' && $this->uri->segment(2) == 'fee_setting') || $this->uri->segment(1) == 'role' || $this->uri->segment(1) == 'tag' || $this->uri->segment(1) == 'content' || ($this->uri->segment(1) == 'bank' && $this->uri->segment(2) == 'verify')) ? 'menu-open' : '');?>">
					<a href="#" class="nav-link <?php echo (($this->uri->segment(1) == 'marquee' || $this->uri->segment(1) == 'announcement' || ($this->uri->segment(1) == 'bank' && $this->uri->segment(2) == '') || $this->uri->segment(1) == 'banner' || $this->uri->segment(1) == 'contact' || $this->uri->segment(1) == 'game' || $this->uri->segment(1) == 'group' || $this->uri->segment(1) == 'miscellaneous' || $this->uri->segment(1) == 'promotion' || $this->uri->segment(1) == 'level' || $this->uri->segment(1) == 'seo' || $this->uri->segment(1) == 'avatar' || ($this->uri->segment(1) == 'bonus' && $this->uri->segment(2) == '') || ($this->uri->segment(1) == 'bank' && $this->uri->segment(2) == 'account') || ($this->uri->segment(1) == 'game' && $this->uri->segment(2) == 'maintenance') || $this->uri->segment(1) == 'match' || ($this->uri->segment(1) == 'level' && $this->uri->segment(2) == 'level_execute') || $this->uri->segment(1) == 'paymentgateway' || $this->uri->segment(1) == 'currencies' || ($this->uri->segment(1) == 'withdrawal' && $this->uri->segment(2) == 'fee_setting') || $this->uri->segment(1) == 'role' || $this->uri->segment(1) == 'tag' || $this->uri->segment(1) == 'content' || ($this->uri->segment(1) == 'bank' && $this->uri->segment(2) == 'verify')) ? 'active' : '');?>">
						<i class="nav-icon fas fa-cog"></i>
						<p>
							<?php echo $this->lang->line('title_system_setting');?>
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<?php if(permission_validation(PERMISSION_AVATAR_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('avatar');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'avatar') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_avatar');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_ANNOUNCEMENT_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('announcement');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'announcement') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_announcement');?></p>
							</a>
						</li>
						<?php endif;?>
						<!-- START PERMISSION_MARQUEE_VIEW -->
						<?php if(permission_validation(PERMISSION_MARQUEE_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('marquee');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'marquee') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_marquee');?></p>
							</a>
						</li>
						<?php endif;?>
						<!-- END PERMISSION_MARQUEE_VIEW -->
						<?php if(permission_validation(PERMISSION_BANK_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('bank');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'bank' && ! $this->uri->segment(2)) ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_bank');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_BANK_ACCOUNT_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('bank/account');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'bank' && $this->uri->segment(2) == 'account') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_bank_account');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_BANK_VERIFY_SUBMIT) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('bank/verify');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'bank' && $this->uri->segment(2) == 'verify') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_bank_withdrawal_verify');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_BANNER_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('banner');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'banner') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_banner');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_BONUS_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('bonus');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'bonus' && $this->uri->segment(2) == '') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_bonus');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_CONTACT_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('contact');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'contact') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_contact');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_GAME_VIEW) == TRUE):?>
						<li class="nav-item d-none">
							<a href="<?php echo site_url('game');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'game' && $this->uri->segment(2) == '') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_game');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_CURRENCIES_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('currencies');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'currencies') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_currencies');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_GAME_MAINTENANCE_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('game/maintenance');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'game' && $this->uri->segment(2) == 'maintenance') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_game_maintenance');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if (permission_validation(PERMISSION_SUB_GAME_VIEW) == TRUE) : ?>
						<li class="nav-item">
							<a href="<?php echo site_url('game/sub_game'); ?>" class="nav-link <?php echo (($this->uri->segment(1) == 'game' && $this->uri->segment(2) == 'sub_game') ? 'active' : ''); ?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_sub_game'); ?></p>
							</a>
						</li>
						<?php endif; ?>
						<?php if(permission_validation(PERMISSION_GROUP_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('group');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'group') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_group');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_LEVEL_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('level');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'level' && $this->uri->segment(2) == '') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_ranking');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_LEVEL_EXECUTE_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('level/level_execute');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'level' && $this->uri->segment(2) == 'level_execute') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_ranking_execute');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_TAG_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('tag');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'tag' && $this->uri->segment(2) == '') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_tag');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_TAG_PLAYER_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('tag/player');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'tag' && $this->uri->segment(2) == 'player') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_tag_player');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_USER_ROLE_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('role');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'role' && $this->uri->segment(2) == '') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_user_role')?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_MATCH_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('match');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'match') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_match');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_MISCELLANEOUS_UPDATE) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('miscellaneous');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'miscellaneous') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_miscellaneous');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_PAYMENT_GATEWAY_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('paymentgateway');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'paymentgateway' && $this->uri->segment(2) == '') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_payment_gateway');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_PAYMENT_GATEWAY_MAINTENANCE_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('paymentgateway/maintenance');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'paymentgateway' && $this->uri->segment(2) == 'maintenance') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_payment_gateway_maintenance');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_PAYMENT_GATEWAY_LIMITED_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('paymentgateway/limited');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'paymentgateway' && $this->uri->segment(2) == 'limited') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_payment_gateway_limited');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_PAYMENT_GATEWAY_PLAYER_LIMITED_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('paymentgateway/player_amount');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'paymentgateway' && $this->uri->segment(2) == 'player_amount') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_payment_gateway_player_amount');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_PROMOTION_VIEW) == TRUE):?>
						<li class="nav-item d-none">
							<a href="<?php echo site_url('promotion/promotion_genres');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'promotion' && $this->uri->segment(2) == 'promotion_genres') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_promotion_genres');?></p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('promotion');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'promotion' && $this->uri->segment(2) == '') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_promotion');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_WITHDRAWAL_FEE_RATE_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('withdrawal/fee_setting');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'withdrawal' && $this->uri->segment(2) == 'fee_setting') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_withdrawal_fee_setting');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_SEO_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('seo');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'seo') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_seo');?></p>
							</a>
						</li>
						<?php endif;?>
						<?php if(permission_validation(PERMISSION_CONTENT_VIEW) == TRUE):?>
						<li class="nav-item">
							<a href="<?php echo site_url('content');?>" class="nav-link <?php echo (($this->uri->segment(1) == 'content') ? 'active' : '');?>">
								<i class="far fa-circle nav-icon"></i>
								<p><?php echo $this->lang->line('title_content');?></p>
							</a>
						</li>
						<?php endif;?>
					</ul>
				</li>
				<?php endif;?>
				<li class="nav-item">
					<a href="<?php echo site_url('logout');?>" class="nav-link">
						<i class="fas fa-sign-out-alt nav-icon"></i>
						<p><?php echo $this->lang->line('title_logout');?></p>
					</a>
				</li>	 
			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
 </aside>