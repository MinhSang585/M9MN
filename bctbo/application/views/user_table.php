<div id="card-table-<?php echo $num;?>" class="card" <?php if($type == 'search'){echo 'style="display:block;"';}else{echo 'style="display:none;"';}?>>

	<div class="card-body">

		<table id="user-table-<?php echo $num;?>" class="table table-striped table-bordered table-hover" style="width:100%;">

			<?php if($type == 'search'){ ?>

			<thead>

				<tr>

					<?php if($type == 'downline'):?>

					<th><?php echo $this->lang->line('label_hashtag');?></th>

					<?php endif;?>					

					<th><?php echo $this->lang->line('label_username');?></th>

					<th><?php echo $this->lang->line('label_nickname');?></th>

					<th><?php echo $this->lang->line('label_level');?></th>

					<th><?php echo $this->lang->line('label_upline');?></th>

					<th><?php echo $this->lang->line('label_credit_points');?></th>

					<th><?php echo $this->lang->line('label_user_domain');?></th>

					<th><?php echo $this->lang->line('label_status');?></th>

					<th><?php echo $this->lang->line('label_registered_date');?></th>

					<th><?php echo $this->lang->line('label_last_login_date');?></th>

					<?php if(permission_validation(PERMISSION_USER_UPDATE) == TRUE OR permission_validation(PERMISSION_PERMISSION_SETUP) == TRUE OR permission_validation(PERMISSION_CHANGE_PASSWORD) == TRUE OR permission_validation(PERMISSION_USER_ADD) == TRUE OR permission_validation(PERMISSION_DEPOSIT_POINT_TO_DOWNLINE) == TRUE OR permission_validation(PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE) == TRUE):?>

					<th><?php echo $this->lang->line('label_action');?></th>

					<?php endif;?>			

				</tr>

			</thead>

			<tbody>

				<tr>

					<td><a href="javascript:void(0);" onclick="getDownline('<?php echo $username;?>', <?php echo $num;?>)"><?php echo $username;?></a></td>

					<td><span id="uc1_<?php echo $user_id;?>"><?php echo $nickname;?></span></td>

					<td><?php echo $this->lang->line(get_user_type($user_type));?></td>

					<td><?php echo ( ! empty($upline) ? $upline : '-')?></td>

					<td><span id="uc2_<?php echo $user_id;?>"><?php echo number_format($points, 2, '.', '');?></span></td>

					<?php

					if(!empty($domain_name)){

						$domain = $domain_name;

					}else{

						if(!empty($domain_sub)){

							$domain = $domain_sub.".".SYSTEM_API_MEMBER_DOMAIN_LINK;

						}else{

							$domain = '-';

						}

					}

					echo '<td>'.$domain.'</td>';

					?>

					<?php

						switch($active)

						{

							case STATUS_ACTIVE: echo '<td><span class="badge bg-success" id="uc3_' . $user_id . '">' . $this->lang->line('status_active') . '</span></td>'; break;

							default: echo '<td><span class="badge bg-secondary" id="uc3_' . $user_id . '">' . $this->lang->line('status_suspend') . '</span></td>'; break;

						}

					?>

					<td><?php echo (($created_date > 0) ? date('Y-m-d H:i:s', $created_date) : '-')?></td>

					<td><?php echo (($last_login_date > 0) ? date('Y-m-d H:i:s', $last_login_date) : '-')?></td>

					<td>

						<?php

							$button = '';

							if(permission_validation(PERMISSION_USER_UPDATE) == TRUE)

							{

								$button .= '<i onclick="updateData(' . $user_id . ')" class="fas fa-edit nav-icon text-primary" title="' . $this->lang->line('button_edit')  . '"></i> &nbsp;&nbsp; ';

							}

							

							if(permission_validation(PERMISSION_PERMISSION_SETUP) == TRUE)

							{

								$button .= '<i onclick="permissionSetup(' . $user_id . ')" class="fas fa-lock nav-icon text-orange" title="' . $this->lang->line('button_permissions')  . '"></i> &nbsp;&nbsp; ';

							}

							

							if(permission_validation(PERMISSION_CHANGE_PASSWORD) == TRUE)

							{

								$button .= '<i onclick="changePassword(' . $user_id . ')" class="fas fa-key nav-icon text-secondary" title="' . $this->lang->line('button_change_password')  . '"></i> &nbsp;&nbsp; ';

							}

							

							// if(permission_validation(PERMISSION_USER_ADD) == TRUE)

							// {

							// 	$button .= '<i onclick="addDownline(\'' . $username . '\')" class="fas fa-user-friends nav-icon text-info" title="' . $this->lang->line('button_downline')  . '"></i> &nbsp;&nbsp; ';

							// }

							

							if(permission_validation(PERMISSION_DEPOSIT_POINT_TO_DOWNLINE) == TRUE)

							{

								$button .= '<i onclick="depositPoints(' . $user_id . ')" class="fas fa-arrow-up nav-icon text-olive" title="' . $this->lang->line('button_deposit_points')  . '"></i> &nbsp;&nbsp; ';

							}

							

							if(permission_validation(PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE) == TRUE)

							{

								$button .= '<i onclick="withdrawPoints(' . $user_id . ')" class="fas fa-arrow-down nav-icon text-maroon" title="' . $this->lang->line('button_withdraw_points')  . '"></i>';

							}

							

							echo $button;

						?>

					</td>

				</tr>

			</tbody>

			<?php }else{ ?>

			<thead>

				<tr>

					<?php if($type == 'downline'):?>

					<th><?php echo $this->lang->line('label_hashtag');?></th>

					<?php endif;?>					

					<th><?php echo $this->lang->line('label_username');?></th>

					<th><?php echo $this->lang->line('label_nickname');?></th>

					<th><?php echo $this->lang->line('label_level');?></th>

					<th><?php echo $this->lang->line('label_upline');?></th>

					<th><?php echo $this->lang->line('label_credit_points');?></th>

					<th><?php echo $this->lang->line('label_user_domain');?></th>

					<th><?php echo $this->lang->line('label_status');?></th>

					<th><?php echo $this->lang->line('label_registered_date');?></th>

					<th><?php echo $this->lang->line('label_last_login_date');?></th>

					<?php if(permission_validation(PERMISSION_USER_UPDATE) == TRUE OR permission_validation(PERMISSION_PERMISSION_SETUP) == TRUE OR permission_validation(PERMISSION_CHANGE_PASSWORD) == TRUE OR permission_validation(PERMISSION_USER_ADD) == TRUE OR permission_validation(PERMISSION_DEPOSIT_POINT_TO_DOWNLINE) == TRUE OR permission_validation(PERMISSION_WITHDRAW_POINT_FROM_DOWNLINE) == TRUE):?>

					<th><?php echo $this->lang->line('label_action');?></th>

					<?php endif;?>			

				</tr>

			</thead>

			<tbody>

			</tbody>

			<?php } ?>

		</table>

	</div>

	

	<script type="text/javascript">

		$(document).ready(function() {

		<?php if($type == 'search'):?>

			$('#user-table-<?php echo $num;?>').DataTable({

				"paging": false,

				"lengthChange": false,

				"scrollX": true,

				"responsive": false,

				"filter": false,

			});

		<?php else:?>

			$('#user-table-<?php echo $num;?>').DataTable({

				"processing": true,

				"serverSide": true,

				"scrollX": ((browser_width < 600) ? true: false),

				"responsive": false,

				"filter": false,

				"pageLength" : 10,

				"order": [[0, "desc"]],

				"ajax": {

					"url": "<?php echo base_url('user/listing/') . $num . '/' . $username;?>",

					"dataType": "json",

					"type": "POST",

					"data": function (d) {

						d.csrf_bctp_bo_token = $('meta[name=csrf_token]').attr('content');

					},

					"complete": function(response) {

						var json = JSON.parse(JSON.stringify(response));

						if(json.status == 200) {

							$('meta[name=csrf_token]').attr('content', json.responseJSON.csrfHash);

							

							if(json.responseJSON.recordsFiltered > 0) {

								$('#card-table-<?php echo $num;?>').show();

							}

							else {

								$('#card-table-<?php echo $num;?>').remove();

							}

						}

						

						layer.closeAll('loading');

					},

				},

				"columnDefs": [

					{"targets": [0], "visible": false},

				],

				"language": {

					"processing": "<?php echo $this->lang->line('js_processing');?>",

					"lengthMenu": "<?php echo $this->lang->line('js_length_menu');?>",

					"zeroRecords": "<?php echo $this->lang->line('js_zero_ecords');?>",

					"info": "<?php echo $this->lang->line('js_info');?>",

					"infoEmpty": "<?php echo $this->lang->line('js_info_empty');?>",

					"infoFiltered": "<?php echo $this->lang->line('info_filtered');?>",

					"search": "<?php echo $this->lang->line('js_search');?>",

					"paginate": {

						"first": "<?php echo $this->lang->line('js_paginate_first');?>",

						"last": "<?php echo $this->lang->line('js_paginate_last');?>",

						"previous": "<?php echo $this->lang->line('js_paginate_previous');?>",

						"next": "<?php echo $this->lang->line('js_paginate_next');?>"

					}	

				}

			});

		<?php endif;?>	

		});	

	</script>

</div>	