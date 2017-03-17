<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>


	<?php echo snappy_style('calendar.css'); ?>
	<?php echo snappy_script('calendar_us.js'); ?>

	<title><?php echo $this->config->item('project_name'); ?> - Workshop Add Inventory Job</title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Workshop Add Inventory Job</h2>
		<ul id="submenu">
			<li><?php echo anchor('workshop/item_jobs/' . $item_data['item_id'], 'Back to Item Jobs'); ?></li>
			<li>|</li>
		</ul>
		<?php $attributes = array('method' => 'post', 'name' => 'add_job_form');?>
		<?php echo form_open('workshop/add_item_job/' . $item_data['item_id'], $attributes) ?>
		<table class='form_table'>
			<tr>
				<td class='title'><span class='warning'>*</span>Item Description:</td>
				<td><?php echo $item_data['item_number']; ?> - <?php echo $item_data['item_name']; ?></td>
			</tr>
			<tr>
				<td class='title'><span class='warning'>*</span>Workshop:</td>
				<td>
					<select name='workshop_id'>
						<option value='' ></option>
						<?php foreach($workshops as $shop):?>
							<?php if(set_value('workshop_id') == $shop['workshop_id']):?>
								<option value='<?php echo $shop['workshop_id']; ?>' selected ><?php echo $shop['name']; ?></option>
							<?php else: ?>
								<option value='<?php echo $shop['workshop_id']; ?>' ><?php echo $shop['name']; ?></option>
							<?php endif;?>
						<?php endforeach;?>
					</select>
				</td>
			</tr>
			<tr>
				<td class='title'><span class='warning'>*</span>Requester:</td>
				<td>
					<select name='user_id'>
						<option value='' ></option>
						<?php foreach($users as $user):?>
							<?php if($user['user_id'] == $user_data['user_id'] || set_value('user_id') == $user_data['user_id']):?>
								<option value='<?php echo $user['user_id']; ?>' selected ><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
							<?php else: ?>
								<option value='<?php echo $user['user_id']; ?>' ><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
							<?php endif;?>
						<?php endforeach;?>
					</select>
				</td>
			</tr>
			<tr>
				<td class='title'>Rush Order:</td>
				<td colspan='3'>
					<input name='rush_order' type='checkbox' />
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
				</td>
			</tr>
			<tr>
				<td class="title"><span class='warning'>*</span>Open Date:</td>
				<td>
					<input name="open_date" type="text" value="<?php echo set_value('open_date') == '' ? date("m/d/Y") : set_value('open_date'); ?>"/>
					<script type="text/javascript">
					A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
					new tcal ({
						// form name
						'formname': 'add_job_form',
						// input name
						'controlname': 'open_date'
					});
					</script>
				</td>
			</tr>
			<tr>
				<td class="title">Est. Return Date:</td>
				<td>
					<input name="est_return_date" type="text" value="<?php echo set_value('est_return_date'); ?>"/>
					<script type="text/javascript">
					A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
					new tcal ({
						// form name
						'formname': 'add_job_form',
						// input name
						'controlname': 'est_return_date'
					});
					</script>
				</td>
			</tr>
			<tr>
				<td class="title">Est. Price:</td>
				<td colspan='3'><input name="est_price" type="text" value="<?php echo set_value('est_price'); ?>" /> </td>
			</tr>
			<tr>
				<td class="title">Instructions:</td>
				<td colspan="3">
					<textarea name="instructions" cols="50" rows="4"><?php echo set_value('instructions'); ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan='4' style="text-align: center;">
					<?php echo validation_errors();  ?>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="text-align: center;" >
					<input name="customer_sbmt" type="submit" value="Save" />  | <?php echo anchor('customer', 'Cancel'); ?>
				</td>
			</tr>

		</table>
		<?php echo form_close(); ?>
		<p>Workshop Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>