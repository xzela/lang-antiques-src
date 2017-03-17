<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<title><?php echo $this->config->item('project_name'); ?> - Customer DELETE Store Credit</title>
	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Customer - DELETE Store Credit</h2>
		<ul id="submenu">
			<li><?php echo anchor('customer/edit/' . $customer_data['customer_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Customer'); ?></li>
			<li>|</li>
		</ul>
		<h3 class='warning'>Are you sure you want to delete this credit record?</h3>
		<?php echo form_open('customer/store_credit_delete/' . $customer_data['customer_id'] . '/' . $credit['store_credit_id']); ?>
			<table class='item_information'>
				<tr>
					<td class='title'>Customer:</td>
					<td><?php echo $customer_data['first_name'] . ' ' . $customer_data['last_name']; ?></td>
				</tr>
				<tr>
					<td class='title'>Amount:</td>
					<td><?php echo $credit['credit_amount']; ?></td>
				</tr>
				<tr>
					<td class='title'>Description:</td>
					<td><?php echo $credit['item_description']; ?></td>
				</tr>
				<tr>
					<td class='title'>Reason:</td>
					<td>
						<textarea name='delete_reason' rows='3' cols='40'></textarea>
						<input type='hidden' name='customer_id' value='<?php echo $customer_data['customer_id']; ?>' />
						<input type='hidden' name='credit_id' value='<?php echo $credit['store_credit_id']; ?>' />
					</td>
				</tr>
							
				<tr>
					<td></td>
					<td>
						<?php echo validation_errors();?>
						<br />
						<input class='warning' type='submit' value='Delete Credit Record' />
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