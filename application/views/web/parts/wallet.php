<div class="col-3 account-layout__container__content__left">
	<div class="profile-navbar">
		<div class="profile-navbar__info">
			<div class="profile-navbar__info__avatar">
				<img class="profile-navbar__info__avatar__img" src="<?php echo base_url('assets/desktop/images/user_center/1.png') ?>" />
			</div>
			<div class="profile-navbar__info__username"><?php echo $this->session->userdata('username')?></div>
			<div class="profile-navbar__info__join"><?= $this->lang->line('label_join_since') ?>: <?= date('d-m-Y', $player['created_date']) ?></div>
		</div>
		<div class="profile-navbar__navbar">
			<div data-dom="wallet-ccl-body" data-setting='{"display": "none", "numberFormat": true }'> </div>
			<div class="profile-navbar__navbar__Total_Balance"><?php echo $this->lang->line('label_total_balance') ?></div>
			<div class="profile-navbar__navbar__currency">
				<span class="bal_main">0.00</span>
				<img class="refresh-balance" role="button" id="refresh_icon_2" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_icon_refresh.png') ?>" />
			</div>

			<div class="profile-navbar__navbar__top-icon">
				<a href="<?php echo site_url('account/deposit') ?>" class="profile-navbar__navbar__top-icon__items <?php if($this->uri->segment(1)=="account"&&$this->uri->segment(2)=="deposit"){echo "active";} ?> header-active-target">
					<img class="profile-navbar__navbar__top-icon__items__img" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_icon_deposit.png') ?>" />
					<img class="profile-navbar__navbar__top-icon__items__img-active" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_icon_deposit.png') ?>" />
					<div><?php echo $this->lang->line('label_deposit') ?></div>
				</a>
				<a href="<?php echo site_url('account/withdrawal') ?>" class="profile-navbar__navbar__top-icon__items header-active-target <?php if($this->uri->segment(1)=="account"&&$this->uri->segment(2)=="withdrawal"){echo "active";} ?>">
					<img class="profile-navbar__navbar__top-icon__items__img" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_icon_withdraw.png') ?>" />
					<img class="profile-navbar__navbar__top-icon__items__img-active" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_icon_withdraw.png') ?>" />
					<div><?php echo $this->lang->line('label_withdrawal') ?></div>
				</a>
				<a href="javascript:void(0)" onclick="alert('Coming Soon!');" class="profile-navbar__navbar__top-icon__items header-active-target">
					<img class="profile-navbar__navbar__top-icon__items__img" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_icon_rebate.png') ?>" />
					<img class="profile-navbar__navbar__top-icon__items__img-active" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_icon_rebate.png') ?>" />
					<div><?php echo $this->lang->line('label_rebate') ?></div>
				</a>
			</div>

			<ul class="profile-navbar__navbar__bottom-nav">
				<li class="profile-navbar__navbar__bottom-nav__item">
					<a href="<?php echo site_url('account') ?>" class="profile-navbar__navbar__bottom-nav__item__link header-active-target <?php if($this->uri->segment(1)=="account"&&$this->uri->segment(2)==""){echo "active";} ?>">
						<img class="profile-navbar__navbar__bottom-nav__item__link__img" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_menu_icon_profile_off.png') ?>" />
						<img class="profile-navbar__navbar__bottom-nav__item__link__img-active" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_menu_icon_profile_on.png') ?>" />
						<div><?php echo $this->lang->line('label_profile') ?></div>
					</a>
				</li>
				<?php /*
				<li class="profile-navbar__navbar__bottom-nav__item">
					<a href="<?php echo site_url('account/wallet') ?>" class="profile-navbar__navbar__bottom-nav__item__link header-active-target <?php if($this->uri->segment(1)=="account"&&$this->uri->segment(2)=="wallet"){echo "active";} ?>">
						<img class="profile-navbar__navbar__bottom-nav__item__link__img" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_menu_icon_mywallet_off.png') ?>" />
						<img class="profile-navbar__navbar__bottom-nav__item__link__img-active" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_menu_icon_mywallet_on.png') ?>" />
						<div><?php echo $this->lang->line('label_main_wallet') ?></div>
					</a>
				</li>
				<li class="profile-navbar__navbar__bottom-nav__item">
					<a href="javascript:void(0)" onclick="alert('Coming Soon!');" class="profile-navbar__navbar__bottom-nav__item__link header-active-target">
						<img class="profile-navbar__navbar__bottom-nav__item__link__img" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_menu_icon_bonuscenter_off.png') ?>" />
						<img class="profile-navbar__navbar__bottom-nav__item__link__img-active" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_menu_icon_bonuscenter_on.png') ?>" />
						<div>Bonus Center</div>
					</a>
				</li> 
				*/ ?>
				<li class="profile-navbar__navbar__bottom-nav__item">
					<a href="<?php echo site_url('account/transaction_history') ?>" class="profile-navbar__navbar__bottom-nav__item__link header-active-target <?php if($this->uri->segment(1)=="account"&&$this->uri->segment(2)=="transaction_history"){echo "active";} ?>">
						<img class="profile-navbar__navbar__bottom-nav__item__link__img" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_menu_icon_history_off.png') ?>" />
						<img class="profile-navbar__navbar__bottom-nav__item__link__img-active" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_menu_icon_history_on.png') ?>" />
						<div><?php echo $this->lang->line('label_transaction_history') ?></div>
					</a>
				</li>

				<li class="profile-navbar__navbar__bottom-nav__item">
					<a href="<?php echo site_url('account/badding_bank') ?>" class="profile-navbar__navbar__bottom-nav__item__link header-active-target <?php if($this->uri->segment(1)=="account"&&$this->uri->segment(2)=="badding_bank"){echo "active";} ?>">
						<img class="profile-navbar__navbar__bottom-nav__item__link__img" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_menu_icon_addbank_off.png') ?>" />
						<img class="profile-navbar__navbar__bottom-nav__item__link__img-active" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_menu_icon_addbank_on.png') ?>" />
						<div><?php echo $this->lang->line('label_binding_bank_account') ?></div>
					</a>
				</li>
				<?php /*
				<li class="profile-navbar__navbar__bottom-nav__item divider"></li>
				<li class="profile-navbar__navbar__bottom-nav__item">
					<a href="<?php echo site_url('account') ?>" class="profile-navbar__navbar__bottom-nav__item__link header-active-target <?php if($this->uri->segment(1)=="account"&&$this->uri->segment(2)==""){echo "active";} ?>">
						<img class="profile-navbar__navbar__bottom-nav__item__link__img" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_menu_icon_profile_off.png') ?>" />
						<img class="profile-navbar__navbar__bottom-nav__item__link__img-active" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_menu_icon_profile_on.png') ?>" />
						<div><?php echo $this->lang->line('label_profile') ?></div>
					</a>
				</li>
				<li class="profile-navbar__navbar__bottom-nav__item">
					<a href="myinbox.html" class="profile-navbar__navbar__bottom-nav__item__link header-active-target ">
						<img class="profile-navbar__navbar__bottom-nav__item__link__img" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_menu_icon_inbox_off.png') ?>" />
						<img class="profile-navbar__navbar__bottom-nav__item__link__img-active" src="<?php echo base_url('assets/desktop/images/user_center/uc_left_menu_icon_inbox_on.png') ?>" />
						<div><?php echo $this->lang->line('label_inbox') ?></div>
					</a>
				</li> 
				*/ ?>
			</ul>
		</div>
	</div>

	<script>
		function walletSideCtrl() {
			var vm = this;
			vm.init = init;
			vm.changeAvatar = changeAvatar;

			function init() {
				getAvatar();
				$('section.main').addClass('account-layout');
			}

			function getAvatar() {
				$.ajax({
					type: 'POST',
					url: 'a/setAvatar',
					data: {
						action: 'getAvatar'
					},
					dataType: 'json',
					success: (data) => {
						if (data.status == '0') {
							if (data.d.avatarid && ["1", "2", "3", "4", "5", "6", "7", "8"].includes(data.d.avatarid)) {
								let avatarid = data.d.avatarid;
								setAvatar(avatarid);
							} else {
								setAvatar('1');
							}
						}
					}
				});
			}

			function setAvatar(id) {
				$(".profile-navbar__info__avatar__img").attr("src", "obv1/images/profile/" + id + ".png");
				$(".avatar-choice-icon[data-avatar='" + id + "']").click();
			}

			function changeAvatar() {
				const selection = $(".avatar-choice-icon.active").data("avatar");
				$(".profile-navbar__info__avatar__img").attr("src", 'obv1/images/profile/' + selection + '.png');

				$.ajax({
					type: 'POST',
					url: 'a/setAvatar',
					data: {
						action: 'setAvatar',
						avatarid: selection
					},
					dataType: 'json',
					success: (data) => {
						if (data.status == '0') {
							$('.modal').modal('hide')
						}
					}
				});
			}
		}

		window.onload = function(){
			_Subctrl = new walletSideCtrl();
			_Subctrl.init();
		};
	</script>
</div>
