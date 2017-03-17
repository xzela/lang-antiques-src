<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options -  Workshop Delete</title>
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
		<h2>Delete a Workshop </h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>
		</ul>
		<div class='delete_admin_item'>
			<h3 class='warning'>Warning: Deleting a Workshop is serious procedure. Delete at ones own risk!</h3>
			<p class='warning'>Deleting a Workshop <strong>cannot</strong> be undone. If you delete the wrong Workshop by mistake you must reenter it yourself!</p>
		</div>
		<h3>Workshop Info:</h3>
		<table class='form_table'>
			<tr>
				<td class='title'>Workshop Name:</td>
				<td><?php echo $workshop_data['name']; ?></td>
			</tr>
			<tr>
				<td class='title'>Contact:</td>
				<td><?php echo $workshop_data['first_name'] . ' ' . $workshop_data['last_name']; ?></td>
			</tr>
			<tr>
				<td class='title' >Phone:</td>
				<td><?php echo $workshop_data['phone']; ?></td>
			</tr>
			<tr>
				<td  class='title'>Fax:</td>
				<td><?php echo $workshop_data['fax']; ?></td>
			</tr>
			<tr>
				<td class='title'>Address:</td>
				<td>
					<?php echo $workshop_data['address']; ?> <br />
					<?php echo $workshop_data['city']; ?> <?php echo $workshop_data['state']; ?>,  <?php echo $workshop_data['zip']; ?> <br />
					<?php echo $workshop_data['country']; ?>
				</td>
			</tr>
		</table>
		<h3>Delete Workshop Checklist: </h3>

		<!-- Vendor Seller START-->
		<?php if(sizeof($item_jobs) > 0): ?>
			<h3 class='warning'>Workshop Inventory Jobs Check: FAILED</h3>
			<div class='delete_admin_item'>
				<p class='warning'>
					Please check this Workshop Inventory Jobs.
					Delete any Invoice Jobs and try again. <?php echo anchor('workshop/edit/' . $workshop_data['workshop_id'], 'View Workrshop'); ?>
				</p>
			</div>
		<?php else: ?>
			<h3 class='success'>Workshop Inventory Jobs Check: PASS</h3>
		<?php endif;?>
		<!-- Customer Seller END-->

		<!-- Vendor Invoices START-->
		<?php if(sizeof($customer_jobs) > 0): ?>
			<h3 class='warning'>Workshop Customer Jobs  Check: FAILED</h3>
			<div class='delete_admin_item'>
				<p class='warning'>
					Please check this Workshop Customer Jobs.
					Delete any Customer Jobs and try again. <?php echo anchor('workshop/edit/' . $workshop_data['workshop_id'], 'View Workshop'); ?>
				</p>
			</div>
		<?php else: ?>
			<h3 class='success'>Workshop Customer Jobs Check: PASS</h3>
		<?php endif;?>
		<!-- Vendor Invoices END-->



		<?php if(sizeof($customer_jobs) == 0 && sizeof($item_jobs) == 0): ?>
			<div class='delete_admin_item' >
				<h2>Ready to Delete: <?php echo $workshop_data['name']; ?></h2>
			<?php echo form_open('admin/workshop_delete_confirm/' . $workshop_data['workshop_id']);?>
				<input name='workshop_id' type='hidden' value='<?php echo $workshop_data['workshop_id']; ?>' />
				<input type='submit' value='Delete This Workshop' />
			<?php echo form_close();?>
			</div>
		<?php else: ?>
			<div class='nodelete_admin_item'>
				<p>
					YOU CAN NOT DELETE THIS Workshop YET.
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