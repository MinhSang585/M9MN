<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark"><?php echo (isset($page_title) ? $page_title : '');?></h1>
			</div><!-- /.col -->
			<?php if($this->uri->segment(1) != 'home'):?>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>"><?php echo $this->lang->line('label_home');?></a></li>
					<li class="breadcrumb-item active"><?php echo (isset($page_title) ? $page_title : '');?></li>
				</ol>
			</div><!-- /.col -->
			<?php endif;?>
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</div>