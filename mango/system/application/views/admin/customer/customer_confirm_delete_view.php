<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options -  Customer Delete</title>
	<style type="text/css">
		.div_form {
			border: 1px solid #666;
			padding: 10px;
			margin: 5px;
			width: 400px;
		}
		.div_form label {
			font-weight: bold;
		}
		.div_form input {
		}
		.error {
			padding: 5px;
			margin: 2px;
			background-color: #ffe1e1;
		}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Delete a Customer </h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>
		</ul>
		<div class='delete_admin_item'>
			<h3 class='warning'>Warning: Deleting a Customer is serious procedure. Delete at ones own risk!</h3>
			<p class='warning'>Deleting a Customer <strong>cannot</strong> be undone. If you delete the wrong customer by mistake you must reenter it yourself!</p>
		</div>
		<h3>Customer Info:</h3>
		<table class='form_table'>
			<tr>
				<td class='title'>Customer Name:</td>
				<td><?php echo $customer_data['first_name'] . ' ' . $customer_data['last_name']; ?></td>
			</tr>
			<tr>
				<td class='title'>Customer Spouse:</td>
				<td><?php echo $customer_data['spouse_first'] . ' ' . $customer_data['spouse_last']; ?></td>
			</tr>
			<tr>
				<td class='title' >Home Phone:</td>
				<td><?php echo $customer_data['home_phone']; ?></td>
			</tr>
			<tr>
				<td  class='title'>Work Phone:</td>
				<td><?php echo $customer_data['work_phone']; ?></td>
			</tr>
			<tr>
				<td class='title'>Address:</td>
				<td>
					<?php echo $customer_data['address']; ?> <br />
					<?php echo $customer_data['city']; ?> <?php echo $customer_data['state']; ?>,  <?php echo $customer_data['zip']; ?> <br /> 
					<?php echo $customer_data['country']; ?> 
				</td>
			</tr>
		</table>
		<h3>Delete Customer Checklist: </h3>
		
		<!-- Customer Store Credit START-->
		<?php if(sizeof($store_credit)> 0): ?>
			<h3 class='warning'>Customer Store Credit Check: FAILED</h3>
			<div class='delete_admin_item'>
				<p class='warning'>
					Please check this customers Store Credit Records. 
					Delete any store credit values and try again. <?php echo anchor('customer/edit/' . $customer_data['customer_id'], 'View Customer'); ?>
				</p>
			</div>
		<?php else: ?>
			<h3 class='success'>Customer Store Credit Check: PASS</h3>
		<?php endif;?>
		<!-- Customer Store Credit END-->
		
		<!-- Customer Jobs START -->
		<?php if(sizeof($jobs)): ?>
			<h3 class='warning'>Customer Job Check: FAILED</h3>
			<div class='delete_admin_item'>
				<p class='warning'>
					Please check this customers Job Records. 
					Delete any jobs and try again. <?php echo anchor('customer/jobs/' . $customer_data['customer_id'], 'View Customer Jobs'); ?>
				</p>
			</div>
		<?php else:?>
			<h3 class='success'>Customer Job Check: PASS</h3>
		<?php endif;?>
		<!-- Customer Jobs END -->
		
		<!-- Customer Seller START-->
		<?php if(sizeof($sold_items) > 0): ?>
			<h3 class='warning'>Customer Marked As Seller Check: FAILED</h3>
			<div class='delete_admin_item'>
				<p class='warning'>
					Please check this customers Invoices. 
					Delete any Invoices and try again. <?php echo anchor('customer/edit/' . $customer_data['customer_id'], 'View Customer'); ?>
				</p>
			</div>			
		<?php else: ?>
			<h3 class='success'>Customer Marked As Seller Check: PASS</h3>
		<?php endif;?>
		<!-- Customer Seller END-->
				
		<!-- Customer Invoices START-->
		<?php if(sizeof($purchesed_items) > 0): ?>
			<h3 class='warning'>Customer Invoice Check: FAILED</h3>
			<div class='delete_admin_item'>
				<p class='warning'>
					Please check this customers Invoices. 
					Delete any Invoices and try again. <?php echo anchor('customer/edit/' . $customer_data['customer_id'], 'View Customer'); ?>
				</p>
			</div>			
		<?php else: ?>
			<h3 class='success'>Customer Invoices Check: PASS</h3>
		<?php endif;?>
		<!-- Customer Invoices END-->
		
		
		
		<?php if(sizeof($purchesed_items) == 0 && sizeof($sold_items) == 0 && sizeof($jobs) == 0 && sizeof($store_credit) == 0): ?>
			<div class='delete_admin_item' >
				<h2>Ready to Delete: <?php echo $customer_data['first_name'] . ' ' . $customer_data['last_name']; ?></h2>
			<?php echo form_open('admin/customer_delete_confirm/' . $customer_data['customer_id']);?>
				<input name='customer_id' type='hidden' value='<?php echo $customer_data['customer_id']; ?>' />
				<input type='submit' value='Delete This Customer' />
			<?php echo form_close();?>
			</div>
		<?php else: ?>
			<div class='nodelete_admin_item'>
				<p>
					YOU CAN NOT DELETE THIS CUSTOMER YET. 
					Make sure all checks pass.  
				</p>
			</div>			
		<?php endif; ?>
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>