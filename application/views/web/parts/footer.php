	<footer class="footer">
		<div class="footer__container container">
			<div class="footer__container__partner">
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_cmd.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_cq9.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_dg.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_evo.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_haba.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_ibc.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_jdb.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_jili.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_lh.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_mg.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_pp.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_sexy.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_kingmaker.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_im.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_wm.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_bg.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_pt.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_sg.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_fc.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_jk.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_mega.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_kiss.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_ne.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_rsg.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_pussy.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_spribe.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_relax.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_ps.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_gxsport.png')?>" />
				<img src="<?php echo base_url('assets/desktop/images/footer/footer_v8.png')?>" />
			</div>
            
			
			<div class="footer__container__nav">
				<div class="footer__container__nav__items">
					<a href="<?php echo site_url('help'); ?>" class="footer__container__nav__items__link"><?php echo $this->lang->line('label_help_center');?></a>
				</div>
				<div class="vr"></div>
				<div class="footer__container__nav__items">
					<a href="<?php echo site_url('help'); ?>" class="footer__container__nav__items__link"><?php echo $this->lang->line('label_contact_us');?></a>
				</div>

			</div>
			<div class="footer__container__copyright"><?php echo $this->lang->line('label_footer');?></div>
		</div>
	</footer>

	<div class="modal fade modal-custom" id="selectLanguage" tabindex="-1" aria-labelledby="selectLanguageLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content bg-dark">
				<div class="modal-header">
					<h5 class="modal-title" id="selectLanguageLabel"><?php echo $this->lang->line('label_choose_language'); ?></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body bg-dark text-white">
					<div class="row mb-4" role="button">
						<div class="col-12 text-center">
							<span class="choose-language" data-language="EN" data-region="Malaysia" onclick="change_language('<?php echo LANG_EN ?>');">English</span>
							<span>|</span>
							<span class="choose-language" data-language="ZH" data-region="Malaysia" onclick="change_language('<?php echo LANG_ZH_CN; ?>');">中文</span>
							<span>|</span>
							<span class="choose-language" data-language="MS" data-region="Malaysia" onclick="change_language('<?php echo LANG_MS; ?>');">Malay</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<a style="display: none;" id="launch_game" target="_blank"></a>
	<a style="display: none;" id="launch_payment_gateway" target="_blank"></a>
	<?php $this->load->view('jsfile');?>
	<?php $this->load->view('jscode');?>
</body>
</html>