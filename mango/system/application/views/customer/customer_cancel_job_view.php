<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
		
	<title><?php echo $this->config->item('project_name'); ?> - Customer Cancel Job</title>

	<script type="text/javascript">
	base_url = '<?php echo base_url(); ?>/';
	</script>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Customer Cancel Job</h2>
		<ul id="submenu">
			<li><?php echo anchor('customer/jobs/' . $customer['customer_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Customer Jobs'); ?></li>
			<li>|</li>
		</ul>
		<?php echo form_open('customer/cancel_job/' . $customer['customer_id'] . '/' . $job_data['job_id'] )?>
		<table class='form_table'>
			<tr>
				<td class='title'>Item Description:</td>
				<td ><?php echo $job_data['item_description']; ?></td>
			</tr>
			<tr>
				<td class='title'>Workshop:</td>
				<td><?php echo anchor('workshop/edit/' . $workshop['workshop_id'], $workshop['name']); ?></td>
			</tr>
			<tr>
				<td class='title'>Requester:</td>
				<td><?php echo $user_name; ?></td>
			</tr>
			<tr>
				<td class="title">Open Date:</td>
				<td><?php echo $job_data['open_date']; ?></td>
			</tr>
			<tr>
				<td class="title">Instructions:</td>
				<td><?php echo $job_data['instructions']; ?></td>
			</tr>
			<tr>
				<td class="title">Reason:</td>
				<td>
					<textarea name='reason' rows="4" cols="40"><?php echo set_value('reason'); ?></textarea>
					<input name='customer_id' type='hidden' value='<?php echo $job_data['customer_id']?>' />
					<input name='job_id' type='hidden' value='<?php echo $job_data['job_id']?>' />
				</td>
			</tr>
			<tr>
				<td colspan='2' style="text-align: center;">
					<?php echo validation_errors();  ?>
				</td>
			</tr>			
			<tr>
				<td colspan='4' style='text-align: center;'>
					<input type='submit' name='submit_cancel' value='Yep, Cancel Job' />
					|
					<?php echo anchor('customer/edit_job/' . $job_data['job_id'], 'No, I\'ve changed my mine'); ?>
				</td>
			</tr>
		</table>
		<?php echo form_close(); ?>
		<p>Customer Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>