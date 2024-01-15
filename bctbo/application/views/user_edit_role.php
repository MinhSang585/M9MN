<?php

defined('BASEPATH') || exit('No direct script access allowed');

?>
<!DOCTYPE html>

<html lang="<?php echo get_language_code('iso'); ?>">

<head>

	<?php $this->load->view('parts/head_meta'); ?>

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

							<?php echo form_open('user/role_update', array('id' => 'role_update', 'name' => 'role_update', 'class' => 'form-horizontal')); ?>

							<div class="card-body">
								<div class="form-group row">

									<label for="remark" class="col-5 col-form-label">Edit Role</label>

								</div>

								<div class="form-group row">
									<?php

										function getSubPermissions($parentId, $permissions)
										{
											$subPermissions = [];
											foreach ($permissions as $permission) {
												if ($permission['parent_id'] == $parentId) {
													$subPermissions[] = $permission;
													$subPermissions = array_merge($subPermissions, getSubPermissions($permission['id'], $permissions));
												}
											}
											return $subPermissions;
										}
									?>

									<?php
										$arrIDExist = array();
										foreach ($roles as $item) :
											if(in_array($item['id'], $arrIDExist)){ continue;}
											$subPermissions = getSubPermissions($item['id'], $roles);
											$checked = '';
											if(in_array($item['number'], $permissionList)){
												$checked = 'checked';
											}
											array_push($arrIDExist, $item['id']);
											echo '<div class="form-group clearfix col-12 col-sm-6 col-md-4 col-lg-3 pt-3">';
											echo '<div class="form-group row">';
											echo '<div class="form-group clearfix col-12">';
											echo '<div class="custom-control custom-checkbox d-inline">';
											echo '<input class="custom-control-input permission_' . $item['number'] . ' checkbox_option main_permissions" type="checkbox" id="permission_' . $item['number'] . '" name="permissions[]" value="' . $item['number'] . '" '.$checked.'>';
											echo '<label class="custom-control-label font-weight-normal" for="permission_' . $item['number'] . '">';
											echo $item['name'] . ' &nbsp;';
											echo '</label>';
											echo '</div>';
											echo '</div>';

											foreach ($subPermissions as $subPermission) :
												if (in_array($subPermission['id'], $arrIDExist)) continue;
												$checkedSub = '';
												if(in_array($subPermission['number'], $permissionList)){
													$checkedSub = 'checked';
												}
												array_push($arrIDExist, $subPermission['id']);
												echo '<div class="form-group clearfix col-12" style="padding-left: 30px;">';
												echo '├ <div class="custom-control custom-checkbox d-inline">';
												echo '<input class="custom-control-input permission_' . $subPermission['number'] . ' checkbox_option sub_permissions_' . $item['number'] . '" type="checkbox" id="permission_' . $subPermission['number'] . '" name="permissions[]" value="' . $subPermission['number'] . '" '.$checkedSub.'>';
												echo '<label class="custom-control-label font-weight-normal" for="permission_' . $subPermission['number'] . '">';
												echo $subPermission['name'] . ' &nbsp;';
												echo '</label>';
												echo '</div>';
												echo '</div>';
												$subPermissionsSub = getSubPermissions($subPermission['id'], $roles);
												foreach ($subPermissionsSub as $subPermissionItem) :
													if (in_array($subPermissionItem['id'], $arrIDExist)) continue;
													$checkedSubItem = '';
													if(in_array($subPermissionItem['number'], $permissionList)){
														$checkedSubItem = 'checked';
													}
													array_push($arrIDExist, $subPermissionItem['id']);
													echo '<div class="form-group clearfix col-12" style="padding-left: 60px;">';
													echo '├ <div class="custom-control custom-checkbox d-inline">';
													echo '<input class="custom-control-input permission_' . $subPermissionItem['number'] . ' checkbox_option sub_permissions_' . $item['number'] . '" type="checkbox" id="permission_' . $subPermissionItem['number'] . '" name="permissions[]" value="' . $subPermissionItem['number'] . '" '.$checkedSubItem.'>';
													echo '<label class="custom-control-label font-weight-normal" for="permission_' . $subPermissionItem['number'] . '">';
													echo $subPermissionItem['name'] . ' &nbsp;';
													echo '</label>';
													echo '</div>';
													echo '</div>';
												endforeach;
											endforeach;

											echo '</div>';
											echo '</div>';
										endforeach;
									?>
								</div>
							</div>

							<!-- /.card-body -->

							<div class="card-footer row">
								<div class="col-5">

									<input type="hidden" id="user_id" name="user_id" value="<?php echo isset($id) ? $id : ''; ?>">

									<button type="button" class="btn btn-primary" onclick="updatePermission()"><?php echo $this->lang->line('button_submit'); ?></button>

									<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel'); ?></button>
								</div>
								<div class="col-7 text-left pt-1">

									<div class="custom-control custom-checkbox">

										<input class="custom-control-input permission_<?php echo PERMISSION_HOME; ?>" type="checkbox" id="checkall">

										<label for="checkall" class="custom-control-label font-weight-normal"><?php echo $this->lang->line('label_select_all'); ?></label>

									</div>

								</div>
							</div>

							<!-- /.card-footer -->

							<?php echo form_close(); ?>

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

	<?php $this->load->view('parts/footer_js'); ?>

	<script type="text/javascript">
		$(document).ready(function() {
			let is_allowed = true;
			let index = parent.layer.getFrameIndex(window.name);
			$('#button-cancel').click(function() {

				parent.layer.close(index);
			});
			
			$('#checkall').click(function() {
				if ($(this).is(':checked')) {

					$('.checkbox_option').prop("checked", true);

				} else {

					$('.checkbox_option').prop("checked", false);
				}
			});

			$('.main_permissions').click(function() {
				let id = $(this).val();
				$('.sub_permissions_' + id).prop("checked", $(this).is(':checked'));
			});

			$('.checkbox_option').click(function() {
				let id = $(this).val();
				$('.permission_' + id).prop("checked", $(this).is(':checked'));
			});
		});
		function updatePermission(){

			let form = $('#role_update');
			let index = parent.layer.getFrameIndex(window.name);
			$.ajax({
				url: form.attr('action'),

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

				success: function(data) {

					let json = JSON.parse(JSON.stringify(data));

					let message = '';

					let msg_icon = 2;

					parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);

					$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);

					if (json.status == '<?php echo EXIT_SUCCESS; ?>') {

						message = json.msg;

						msg_icon = 1;

						parent.layer.close(index);

					} else {
						if (json.msg.role_name_error != '') {

							message = json.msg.role_name_error;

						} else if (json.msg.general_error != '') {

							message = json.msg.general_error;

						}
					}

					parent.layer.alert(message, {
						icon: msg_icon,
						title: '<?php echo $this->lang->line('label_info'); ?>',
						btn: '<?php echo $this->lang->line('button_close'); ?>'
					});

				},

				error: function(request, error) {

				}

			});
		}
	</script>

</body>

</html>
