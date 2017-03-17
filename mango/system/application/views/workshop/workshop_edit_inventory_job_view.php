<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_style('calendar.css'); //autoloaded ?>

	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>

	<?php echo snappy_script('calendar_us.js'); ?>
	<title><?php echo $this->config->item('project_name'); ?> - Inventory Edit Job: <?php echo $job_data['item_name']; ?> - <?php echo $job_data['item_number']; ?></title>

	<script type="text/javascript">

	var json = {"fields" : {}};

	$(document).ready(function () {
		var content = $('.input_field').val();
		$.each($('.input_field'), function(i, val) {
			json.fields[i] = {"name" : this.name, "value" : this.value};
		});

		$('.input_field').bind('keyup change', function(event) {
			var index = $('.input_field').index(this);
			var content = json.fields[index].value;

			//alert(content + '=' + $(this).val());
			var div = $('#change_message');
			if($(this).val() != content) {
				$(this).css('color', 'red');
				$(this).css('border', '1px solid red');
				if(div.is(':hidden')) {
					div.slideDown('slow');
				}
			}
			else {
				$(this).css('color', 'black');
				$(this).css('border', '1px solid #333333');
				var b = true;
				$.each($('.input_field'), function(i, val) {
					if(this.value != json.fields[i].value) {
						b = false;
					}
				});
				if(b) {
					div.slideUp('slow');
				}
			}
		});
	});

	function updateRushStatus(foo) {
		var checkbox = document.getElementById(foo.id);
		var id = <?php echo $job_data['job_id'];?>;
		var column = 'rush_order';
		var ck = checkbox.checked ? 1 : 0;
		new Ajax.Request(base_url + 'workshop/AJAX_updateInventoryJobField/' + id + '/' + column, {
			method: 'post',
			postBody: 'value=' + ck,
			onSuccess: function () {
			}
			});

	}

	</script>
	<?php
		//Ajax Options for Scriptacoulus...
		$options = "okText: 'Save', okButton: false, cancelText: 'Cancel', cancelLink: false, submitOnBlur: 'true', ajaxOptions: {method: 'post'}";
	?>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2 class='item'>Inventory Edit Job: <?php echo $job_data['item_name']; ?> - <?php echo $job_data['item_number']; ?></h2>
		<ul id="submenu">
			<li><?php echo anchor('workshop/inventory_jobs/' . $job_data['workshop_id'], '<< Back to Workshop Jobs'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/edit/' . $job_data['item_id'], '<< Back to Item'); ?></li>
			<li>|</li>
			<li><?php echo anchor('workshop/item_jobs/' . $job_data['item_id'], 'View Jobs for This Item'); ?></li>
			<li>|</li>
		</ul>
		<?php echo form_open('workshop/edit_job/' . $job_data['job_id'], 'name="edit_job_form"');?>
		<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>

		<table class='form_table' width='800px'>
			<tr>
				<td class='title' nowrap><span class='warning'>*</span>Item Description:</td>
				<td colspan='3'><?php echo $job_data['item_description']; ?></td>
			</tr>
			<tr>
				<td class='title'><span class='warning'>*</span>Workshop:</td>
				<td>
					<select name='workshop_id' class='input_field'>
						<option></option>
						<?php foreach($workshops as $shop):?>
							<?php if(set_value('workshop_id',$job_data['workshop_id']) == $shop['workshop_id']): ?>
								<option value='<?php echo $shop['workshop_id']?>' selected><?php echo $shop['name']; ?></option>
							<?php else:?>
								<option value='<?php echo $shop['workshop_id']?>'><?php echo $shop['name']; ?></option>
							<?php endif;?>
						<?php endforeach;?>
					</select>
				</td>
			</tr>
			<tr>
				<td class='title'><span class='warning'>*</span>Requester:</td>
				<td>
					<select name='user_id' class='input_field'>
						<option value='0'></option>
						<?php foreach($users as $user):?>
							<?php if(set_value('user_id', $job_data['user_id']) == $user['user_id']):?>
								<option value='<?php echo $user['user_id']?>' selected><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
							<?php else:?>
								<option value='<?php echo $user['user_id']?>'><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
							<?php endif;?>
						<?php endforeach;?>
					</select>
				</td>
				<td class='title'>Inspector: </td>
				<td>
					<select name='inspection_by_id' class='input_field'>
						<option value='0'></option>
						<?php foreach($users as $user): ?>
							<?php if(set_value('inspection_by_id', $job_data['inspection_by_id']) == $user['user_id']): ?>
								<option value='<?php echo $user['user_id']?>' selected><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
							<?php else: ?>
								<option value='<?php echo $user['user_id']?>'><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
							<?php endif;?>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class='title' >Rush Order</td>
				<td colspan='3' >
					<input name='rush_order' class='input_field' type='checkbox' <?php echo set_checkbox('rush_order', '1', (bool)$job_data['rush_order']); ?>/>
				</td>
			</tr>
			<tr>
				<td class='title'>At Workshop:</td>
				<td>
					<select class='input_field' name='at_workshop'>
						<?php if($job_data['at_workshop'] == 'yes'): ?>
							<option value='yes' selected>Yes</option>
							<option value='no'>No</option>
						<?php else: ?>
							<option value='yes'>Yes</option>
							<option value='no' selected>No</option>
						<?php endif;?>
					</select>
					<?php if($job_data['at_workshop'] == 'yes'): ?>
						Sent on: <span><?php echo date('m/d/Y', strtotime($job_data['sent_date'])); ?></span>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td class="title">Open Date:</td>
				<td>
					<input id='open_date' name="open_date" type="text" class='input_field' value="<?php echo date('m/d/Y', strtotime(set_value('open_date', $job_data['open_date']))); ?>" />
					<script type="text/javascript">
					A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
					new tcal ({
						// form name
						'formname': 'edit_job_form',
						// input name
						'controlname': 'open_date',
						//callback
						'callback' : function(str) {
							$('#open_date').trigger('keyup');
						}
					});
					</script>
				</td>
				<td class="title">Act. Return Date:</td>
				<td>
					<input id='act_return_date' name="act_return_date" type="text" class='input_field' value="<?php echo $job_data['act_return_date'] == '' ?  '' : date('m/d/Y', strtotime(set_value('act_return_date', $job_data['act_return_date']))); ?>" />
					<script type="text/javascript">
					A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
					new tcal ({
						// form name
						'formname': 'edit_job_form',
						// input name
						'controlname': 'act_return_date',
						//callback
						'callback' : function(str) {
							$('#act_return_date').trigger('keyup');
						}
					});
					</script>
				</td>
			</tr>
			<tr>
				<td class="title">Est. Return Date:</td>
				<td colspan='3'>
				<?php
					$est_return_date = '';
					if($job_data['est_return_date'] != '') {
						$est_return_date = date('m/d/Y', strtotime($job_data['est_return_date']));
					}
				?>
					<input id='est_return_date' name="est_return_date" type="text" class='input_field' value="<?php echo set_value('est_return_date', $est_return_date); ?>" />
					<script type="text/javascript">
					A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
					new tcal ({
						// form name
						'formname': 'edit_job_form',
						// input name
						'controlname': 'est_return_date',
						// callback
						'callback': function (str) {
							$('#est_return_date').trigger('keyup');
						}
					});
					</script>
				</td>
			</tr>

			<tr>
				<td class="title">Est. Price:</td>
				<td >
					<input name='est_price' type='text' class='input_field' value='<?php echo set_value('est_price', $job_data['est_price']); ?>' />
				</td>
				<!-- <td class="title">Act. Price:</td>
				<td >
					<input name='act_price' type='text' class='input_field' value='<?php echo set_value('act_price', $job_data['act_price']); ?>' />
				</td>
				-->
			</tr>
			<tr>
				<td class="title">Act. Job Cost:</td>
				<td colspan='3'>
					<input name='job_cost' type='text' class='input_field' value='<?php echo set_value('job_cost', $job_data['job_cost']); ?>' />
				</td>
			</tr>
			<tr>
				<td class="title">Instructions:</td>
				<td colspan="3">
					<textarea name='instructions' class='input_field' cols='60' rows='3'><?php echo set_value('instructions', $job_data['instructions']); ?></textarea>
				</td>
			</tr>
			<tr>
				<td class="title">Notes:</td>
				<td colspan="3">
					<textarea name='notes' class='input_field' cols='60' rows='2'><?php echo set_value('notes', $job_data['notes']); ?></textarea>
				</td>
			</tr>
			<tr>
				<td class='title'>Saving Options:</td>
				<td>
					<button name='update_job' type='submit' value='Update Job'><?php echo snappy_image('icons/pencil.png');?> Update Job</button>
					<?php if($job_data['status'] == 1): ?>
						||
						<input name='item_id' type='hidden' value='<?php echo $job_data['item_id']; ?>' />
						<button name='update_complete_job' type='submit' value='Update and Complete Job'><?php echo snappy_image('icons/tick.png');?> Update and Complete Job</button>
					<?php endif; ?>
				</td>
			</tr>
		</table>
		<?php echo form_close(); ?>
		<?php if($job_data['end_date'] == ''):?>
			<h2>Options:</h2>
			<?php echo form_open('workshop/complete_job'); ?>
				<div class='nodelete_admin_item'>
					<h3>If this job is done...</h3>
					<p>If the job is complete, click on the 'Complete Job' button to complete this job.</p>
					<input type='hidden' name='item_id' value='<?php echo $job_data['item_id']; ?>' />
					<input type='hidden' name='job_id' value='<?php echo $job_data['job_id']; ?>' />
					<button type='submit' value='Complete Job'><?php echo snappy_image('icons/tick.png');?> Complete Job</button>
				</div>
			<?php echo form_close(); ?>
			<?php echo form_open('workshop/cancel_job/' . $job_data['job_id']);?>
				<div class='delete_admin_item'>
					<h3>Need to cancel job...?</h3>
					<p>If this job needs to be canceled, click on the Cancel Job button to cancel the job.</p>
					<button type='submit' value='Complete Job'><?php echo snappy_image('icons/cross.png');?> Cancel Job</button>
				</div>
			<?php echo form_close(); ?>
		<?php else:?>
				<div class='nodelete_admin_item'>This Job was <?php echo $job_data['status_text'];?> on <?php echo $job_data['end_date']; ?></div>
		<?php endif;?>
		<p>Workshop Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>