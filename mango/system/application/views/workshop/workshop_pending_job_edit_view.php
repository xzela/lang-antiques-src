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
	<title><?php echo $this->config->item('project_name'); ?> - Pending Repair Job for : <?php echo $item_data['item_name']; ?> - <?php echo $job_data['item_number']; ?></title>

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
	</script>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2 class='item'>Pending Job for : <?php echo $item_data['item_name']; ?> - <?php echo $item_data['item_number']; ?></h2>
		<ul id="submenu">
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], '<< Back to Item'); ?></li>
			<li>|</li>
		</ul>
		<?php echo form_open('workshop/pending_job_edit/' . $job_data['pending_job_id'], 'name="edit_job_form"');?>
		<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>

		<table class='form_table' width='800px'>
			<tr>
				<td class='title' nowrap><span class='warning'>*</span>Item Description:</td>
				<td><?php echo $item_data['item_description']; ?></td>
			</tr>
			<tr>
				<td class='title'>Created By:</td>
				<td>
					<?php echo $users[$job_data['user_id']]['first_name'] . ' ' . $users[$job_data['user_id']]['last_name']; ?>
				</td>
			</tr>
			<tr>
				<td class='title'>Job Status: </td>
				<td><?php echo $job_data['job_status']; ?></td>
			</tr>
			<tr>
				<td class="title">Open Date:</td>
				<td>
					<?php echo date('m/d/Y', strtotime(set_value('open_date', $job_data['open_date']))); ?>
				</td>
			</tr>
			<tr>
				<td class="title">Est. Return Date:</td>
				<td>
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
			</tr>
			<tr>
				<td class="title">Instructions:</td>
				<td>
					<textarea name='instructions' class='input_field' cols='60' rows='3'><?php echo set_value('instructions', $job_data['instructions']); ?></textarea>
				</td>
			</tr>
			<tr>
				<td class="title">Notes:</td>
				<td>
					<textarea name='notes' class='input_field' cols='60' rows='2'><?php echo set_value('notes', $job_data['notes']); ?></textarea>
				</td>
			</tr>
			<tr>
				<td class='title'>Saving Options:</td>
				<td>
					<button name='update_job' type='submit'><?php echo snappy_image('icons/pencil.png');?> Update Pending Repair</button>
				</td>
			</tr>
		</table>
		<?php echo form_close(); ?>
		<div class="nodelete_admin_item">
			<h3>Ready to Assign a Workshop?</h3>
			<p>
				If you're ready to assign a workshop this pending job. Click below to start the process.
			</p>

			<?php echo anchor('workshop/pending_job_assign/' . $job_data['pending_job_id'], snappy_image('icons/arrow_join.png') . ' Assign to Workshop');?>
		</div>
		<?php echo form_open('workshop/pending_job_delete'); ?>
			<div class='delete_admin_item'>
					<h3>Need to delete this pending repair...?</h3>
					<p>
						If this job needs to be deleted, click on the Delete Pending Job button to delete the job.
						This will take the item out of the Pending Repair Queue and make the item "Available"
					</p>
					<button type='submit' ><?php echo snappy_image('icons/cross.png');?> Delete Pending Job</button>
					<input type="hidden" name="pending_job_id" value="<?php echo $job_data['pending_job_id']; ?>"/>
					<input type="hidden" name="item_id" value="<?php echo $job_data['item_id']; ?>"/>
			</div>
		<?php echo form_close(); ?>
		<p>Workshop Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>