<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options -  Vendor Delete</title>
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
		<h2>Delete a Vendor </h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>
		</ul>
		<div class='delete_admin_item'>
			<h3 class='warning'>Warning: Deleting a Vendor is serious procedure. Delete at ones own risk!</h3>
			<p class='warning'>Deleting a Vendor <strong>cannot</strong> be undone. If you delete the wrong vendor by mistake you must reenter it yourself!</p>
		</div>
		<h3>Vendor Info:</h3>
		<table class='form_table'>
			<tr>
				<td class='title'>Vendor Name:</td>
				<td><?php echo $vendor_data['name']; ?></td>
			</tr>
			<tr>
				<td class='title'>Contact:</td>
				<td><?php echo $vendor_data['first_name'] . ' ' . $vendor_data['last_name']; ?></td>
			</tr>
			<tr>
				<td class='title' >Phone:</td>
				<td><?php echo $vendor_data['phone']; ?></td>
			</tr>
			<tr>
				<td  class='title'>Fax:</td>
				<td><?php echo $vendor_data['fax']; ?></td>
			</tr>
			<tr>
				<td class='title'>Address:</td>
				<td>
					<?php echo $vendor_data['address']; ?> <br />
					<?php echo $vendor_data['city']; ?> <?php echo $vendor_data['state']; ?>,  <?php echo $vendor_data['zip']; ?> <br />
					<?php echo $vendor_data['country']; ?>
				</td>
			</tr>
		</table>
		<h3>Delete Vendor Checklist: </h3>

		<!-- Customer Seller START-->
		<?php if(sizeof($sold_items) > 0): ?>
			<h3 class='warning'>Vendor Marked As Seller Check: FAILED</h3>
			<div class='delete_admin_item'>
				<p class='warning'>
					Please check this Vendors Invoices.
					Delete any Invoices and try again. <?php echo anchor('vendor/edit/' . $vendor_data['vendor_id'], 'View Customer'); ?>
				</p>
			</div>
		<?php else: ?>
			<h3 class='success'>Vendor Marked As Seller Check: PASS</h3>
		<?php endif;?>
		<!-- Customer Seller END-->

		<!-- Vendor Invoices START-->
		<?php if(sizeof($purchesed_items) > 0): ?>
			<h3 class='warning'>Vendor Invoice Check: FAILED</h3>
			<div class='delete_admin_item'>
				<p class='warning'>
					Please check this Vendors Invoices.
					Delete any Invoices and try again. <?php echo anchor('vendor/edit/' . $vendor_data['vendor_id'], 'View Vendor'); ?>
				</p>
			</div>
		<?php else: ?>
			<h3 class='success'>Vendor Invoices Check: PASS</h3>
		<?php endif;?>
		<!-- Vendor Invoices END-->



		<?php if(sizeof($purchesed_items) == 0 && sizeof($sold_items) == 0): ?>
			<div class='delete_admin_item' >
				<h2>Ready to Delete: <?php echo $vendor_data['name']; ?></h2>
			<?php echo form_open('admin/vendor_delete_confirm/' . $vendor_data['vendor_id']);?>
				<input name='vendor_id' type='hidden' value='<?php echo $vendor_data['vendor_id']; ?>' />
				<input type='submit' value='Delete This Vendor' />
			<?php echo form_close();?>
			</div>
		<?php else: ?>
			<div class='nodelete_admin_item'>
				<p>
					YOU CAN NOT DELETE THIS Vendor YET.
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