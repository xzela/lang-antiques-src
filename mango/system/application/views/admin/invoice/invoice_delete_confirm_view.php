<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - Confirm Delete Invoice: #<?php echo $invoice_data['invoice_id']?></title>
	<style type="text/css">
	.div_form {
		border: 1px solid #666;
		padding: 5px;
		margin: 5px;
		width: 500px;
	}
	label {
		display: block;
		font-weight: bold;
		padding-right: 20px;
		margin-right: 20px; 
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
		<h2>Delete Invoice - Confirmation!</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>

		</ul>
	
		<div class='error'>
			<h3 class='warning'>Once again, are you sure you want to delete this invoice '<?php echo $invoice_data['invoice_id']; ?>'? This is your last chance to chicken out...</h3>
			<p class='warning'>Deleting an invoice <strong>cannot</strong> be undone. If you delete the wrong invoice by mistake you will be forced to reenter it yourself! </p>
		</div>
		
		
		
		<h3>Inventory Items Applied to Invoice</h3>
		<p class='warning error'>The following Inventory Items will be removed from this invoice. There status will change to 'available'. They WILL NOT be placed back on line. YOU MUST DO THAT YOURSELF.</p>
		<table class='invoice_table'>
			<tr>
				<th nowrap>Item Number</th>
				<th nowrap>Item Name</th>
				<th nowrap>Description</th>
				<th nowrap>Item Price</td>
			</tr>
				<?php if(sizeof($invoice_items) > 0): ?>
					<?php foreach($invoice_items as $item): ?>
					<tr>
						<td>
							<?php echo $item['item_number']; ?> <br />
							<?php if(sizeof($item['image_array']['external_images']) > 0): ?>
								<?php
									echo anchor('inventory/edit/' . $item['item_id'] , "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['external_images'][0]['image_location'] . '&image_type=' . $item['image_array']['external_images'][0]['image_class'] . '&image_size=' . $item['image_array']['external_images'][0]['image_size'] . "' />");
								?>
							<?php elseif(sizeof($item['image_array']['internal_images']) > 0):?>
								<?php 
								echo anchor('inventory/edit/' . $item['item_id'], "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $item['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $item['image_array']['internal_images'][0]['image_size'] . "' />");
								?>
							<?php else: ?>
								No Image Provided						
							<?php endif; ?>
							
						</td>
						<td><?php echo $item['item_name']; ?></td>
						<td><?php echo $item['item_description']; ?></td>
						<td>$<?php echo number_format($item['item_price'], 2); ?></td>
					</tr>
					<?php endforeach;?>
				<?php else: ?>
					<tr>
						<td colspan='4'>No Items Found. Good?</td>
					</tr>
				<?php endif;?>
		</table>
		
		
		<h3>Special Items Applied to Invoice</h3>
		<p class='warning error'>The following Special Items will be removed from this invoice. WARNING: THEY WILL BE REMOVED FROM THE SYSTEM COMPLETELY! THERE IS NO UNDO FOR THIS ACTION.</p>
		<table class='invoice_table'>
			<tr>
				<th>Description</th>
				<th>Price</th>
				<th>Tax</th>
			</tr>
			<?php if(sizeof($invoice_special_items) > 0): ?>
				<?php foreach($invoice_special_items as $item):?>
					<tr>
						<td><?php echo $item['item_description']; ?></td>
						<td><?php echo $item['item_price']; ?></td>
						<td><?php echo $item['item_tax']; ?></td>
					</tr>
				<?php endforeach;?>
			<?php else: ?>
				<tr>
					<td colspan='3'>No Special Items Found.</td>
				</tr>
			<?php endif;?>
		</table>
		
		
		<h3>Payments Applied to this Invoice</h3>
		<p class='warning error'>The following Payments will be removed from this invoice. WARNING: THEY WILL BE REMOVED FROM THE SYSTEM COMPLETELY! THERE IS NO UNDO FOR THIS ACTION.</p>
		<table class='invoice_table'>
			<tr>
				<th>Payment Type</th>
				<th>Amount Paid</th>
				<th>Payment Date</th>
			</tr>
			<?php if(sizeof($invoice_payments) > 0): ?>
				<?php foreach($invoice_payments as $payment):?>
					<tr>
						<td><?php echo $payment_methods[$payment['method']]['name']; ?></td>
						<td><?php echo $payment['amount']; ?></td>
						<td><?php echo $payment['date']; ?></td>
					</tr>
				<?php endforeach;?>
			<?php else: ?>
				<tr>
					<td colspan='2'>No Payments Found. Good?</td>
				</tr>
			<?php endif;?>
		</table>
		<?php echo form_open('admin/confirm_delete_invoice'); ?>
			<div class='div_form'>
				<label>Invoice ID:</label>
				<span><?php echo $invoice_data['invoice_id']; ?></span>
				<br />
				<label>Entry Date:</label>
				<span><?php echo $invoice_data['sale_date']; ?></span>
				<br />
				<label>Reason for Delete:</label>
				<span><textarea name='delete_reason' cols='55' rows='4'><?php echo set_value('delete_reason'); ?></textarea></span>
				<br />
				<label style='display: inline;'>Return All Items to Web?: <input name='return_web' type='checkbox' /> </label>
				<br /><span>This will put all the items back online</span>
				<?php echo validation_errors();  ?>
				<br />
				<input type="hidden" name="invoice_id" value="<?php echo $invoice_data['invoice_id']; ?>" />
				<input type="submit" name="submit_delete" value="Delete this Invoice" /> | <?php echo anchor('admin', ' Cancel'); ?>
			</div>
		<?php echo form_close(); ?>		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>