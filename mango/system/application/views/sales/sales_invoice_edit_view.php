<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Invoices - Edit <?php echo $invoice_data['invoice_type_text']; ?></title>

	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>

	<script type="text/javascript">
		var base_url = <?php echo '"' . base_url() . '"'; ?>;

		var invoice_id = <?php echo $invoice_data['invoice_id']; ?>;

		$(document).ready(function() {
			$('#layaway_payment_a').bind('click', function() {
				var link = $(this);
				var div = $('#layaway_payment_div');
				if(div.is(':hidden')) {
					div.slideDown("slow");
					link.html('Close Payment Form');
				}
				else {
					div.slideUp("slow");
					link.html('Open Payment Form');
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
		<h2 class='item'>
			Invoices - Edit <?php echo $invoice_data['invoice_type_text']?> #<?php echo $invoice_data['invoice_id']; ?> for:
			<?php if($invoice_data['buyer_type'] == 1 || $invoice_data['buyer_type'] == 3): ?>
				Customer - <?php echo anchor('customer/edit/' . $buyer_data['customer_id'], $buyer_data['first_name'] . ' ' . $buyer_data['last_name']); ?>
			<?php elseif($invoice_data['buyer_type'] == 2):?>
				Vendor - <?php echo anchor('vendor/edit/' . $buyer_data['vendor_id'], $buyer_data['name']); ?>
			<?php endif; ?>
		</h2>
		<ul id="submenu">
			<li><?php echo anchor('sales', '<< Back to Sales Main'); ?></li>
			<li>|</li>
			<?php if($invoice_data['invoice_type'] == 1): ?>
				<li><?php echo anchor('printer/invoice/' . $invoice_data['invoice_id'], snappy_image('icons/printer.png') . ' Print Layaway', '"target=_blank"');?></li>
			<?php endif?>

		</ul>

		<table class='form_table'>
		<?php if($invoice_data['invoice_status'] == 4 || $invoice_data['memo_id'] != ''): ?>
				<tr>
					<td colspan='4'>
						<h3>

							<?php if($invoice_data['memo_id'] != ''): ?>
								<span class='error'>This invoice was created from a memo! </span>
								<span class='normal_text'>[<?php echo anchor('sales/invoice/' . $invoice_data['memo_id'], 'View Memo'); ?>]</span>
							<?php else: ?>
								<span class='error'>This Memo has been converted into an Invoice!</span>
								<span class='normal_text'>[<?php echo anchor('sales/invoice/' . $invoice_data['invoice_memo_id'], 'View Invoice'); ?>]</span>
							<?php endif;?>
						</h3>
					</td>
				</tr>
			<?php endif;?>
			<tr>
				<td class='title'>Invoice ID:</td>
				<td><?php echo $invoice_data['invoice_id']; ?></td>
				<td class='title'>Sales Slip Number:</td>
				<td><?php echo $invoice_data['sales_slip_number']; ?> [<?php echo anchor('sales/edit_invoice_fields/' . $invoice_data['invoice_id'],'Edit')?>]</td>
			</tr>
			<tr>
				<td class='title'>Invoice Date:</td>
				<td><?php echo $invoice_data['sale_date'] == '0000-00-00' ? '' : date('m/d/Y', strtotime($invoice_data['sale_date'])); ?> [<?php echo anchor('sales/edit_invoice_fields/' . $invoice_data['invoice_id'],'Edit')?>]</td>
				<td class='title'>Sales Person:</td>
				<td><?php echo $sales_people[$invoice_data['user_id']]['first_name'] . ' ' . $sales_people[$invoice_data['user_id']]['last_name']; ?> [<?php echo anchor('sales/edit_invoice_fields/' . $invoice_data['invoice_id'],'Edit')?>]</td>
			</tr>
			<tr>
				<td class='title'>Invoice Type:</td>
				<td style='vertical-align: top;'>
					<?php echo $invoice_data['invoice_type_text']; ?>
					<?php if($invoice_data['invoice_type'] == 0):?>
						[<?php echo anchor('sales/change_invoice_type/' . $invoice_data['invoice_id'] . '/1', 'Change to Layaway')?>]
					<?php elseif($invoice_data['invoice_type'] == 1):?>
						[<?php echo anchor('sales/change_invoice_type/' . $invoice_data['invoice_id'] . '/0', 'Change to Normal')?>]
					<?php endif;?>
				</td>
				<?php if($invoice_data['buyer_type'] == 1 || $invoice_data['buyer_type'] == 3):?>
					<td class='title'>Customer Name:</td>
					<td>
						<?php echo $buyer_data['first_name'] . ' ' . $buyer_data['last_name']; ?>
					</td>
				<?php elseif($invoice_data['buyer_type'] == 2):?>
					<td class='title'>Vendor Name:</td>
					<td><?php echo $buyer_data['name']; ?></td>
				<?php endif; ?>
			</tr>
			<tr>
				<td class='title'>Buyer Type:</td>
				<td>

					<?php if($invoice_data['buyer_type'] == 3):?>
						Internet Customer Sale
						<br />
						[<?php echo anchor('sales/convert_internet/' . $invoice_data['invoice_id'] . '/normal', 'Convert To Normal'); ?>]
					<?php elseif($invoice_data['buyer_type'] == 1): ?>
						Offline Customer Sale
						<br />
						[<?php echo anchor('sales/convert_internet/' . $invoice_data['invoice_id'] . '/internet', 'Convert To Internet'); ?>]
					<?php elseif($invoice_data['buyer_type'] == 2):?>
						Vendor Sale
					<?php endif;?>
				</td>
			</tr>
		</table>

		<?php if($invoice_data['invoice_type'] == 1): ?>
			<?php echo $this->load->view('sales/_components/layaway_view'); ?>
		<?php endif;?>

		<h3>Add Item:</h3>
		<div class='inventory_search_box'>
			<?php echo form_open('sales/add_inventory_item/' . $invoice_data['invoice_id'])?>
				<input id='inventory_input' name='inventory_input' type='text' style='width: 250px;' />
				<input type='submit' value='Add Item' />
				<br />
				<span class='warning'><?php echo $this->session->flashdata('error_message'); ?></span>
			<?php echo form_close(); ?>
		</div>
		<h4>Applied Items</h4>
		<table class='invoice_table'>
			<tr>
				<th nowrap>Number</th>
				<th>Description</th>
				<th nowrap><?php echo ($invoice_data['invoice_type']  == 3) ? 'Price' : 'Retail Price'; ?></th>
				<th nowrap>Tax</th>
				<th nowrap>Options</th>
			</tr>
			<?php if(sizeof($invoice_items) > 0 ):?>

				<?php foreach($invoice_items as $item):?>
				<tr>
					<td>
						<div style='text-align: center;'><?php echo $item['item_number']; ?></div>
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
					<td>
						<div class='item_name'><?php echo $item['item_name'];?></div>
						<p><?php echo $item['item_description'];?></p>
					</td>
					<td>$<?php echo number_format($item['sale_price'], 2); ?></td>
					<td>$<?php echo number_format($item['sale_tax'], 2); ?></td>
					<td nowrap>
						[<?php echo anchor('sales/edit_invoice_item/' . $invoice_data['invoice_id'] . '/' . $item['item_id'], 'Edit Values'); ?>]<br />
						[<?php echo anchor('sales/remove_item_tax/' . $invoice_data['invoice_id'] . '/' . $item['item_id'], 'Remove Tax'); ?>]<br />
						<?php echo form_open('sales/remove_invoice_item/' . $invoice_data['invoice_id'] . '/' . $item['item_id']); ?>
						<input class='warning' type='submit' value='Remove Item' />
						<?php echo form_close(); ?>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else: ?>
				<tr>
					<td colspan='4' ><span class='warning'>No Inventory Items Found</span></td>
				</tr>
			<?php endif;?>
			<tr>
				<td colspan='5' class='top_lite header'>Special Orders [<?php echo anchor('sales/add_invoice_special_item/' . $invoice_data['invoice_id'],' Add Special Order'); ?>]</td>
			</tr>
			<?php if(sizeof($special_items) > 0):?>
				<?php $repairs = array(); ?>
				<?php foreach($special_items as $item):?>
					<?php if($item['item_type'] != 3): ?>
					<tr>
						<td colspan='2'><?php echo $item['item_description']; ?></td>
						<td>$<?php echo number_format($item['item_price'], 2); ?></td>
						<td>$<?php echo number_format($item['item_tax'], 2); ?></td>
						<td>
							[<?php echo anchor('sales/edit_invoice_special_item/' . $invoice_data['invoice_id'] . '/' . $item['special_item_id'], 'Edit Values'); ?>]<br />
							[<?php echo anchor('sales/remove_special_item_tax/' . $invoice_data['invoice_id'] . '/' . $item['special_item_id'], 'Remove Tax'); ?>]<br />
							<?php echo form_open('sales/remove_special_item/' . $invoice_data['invoice_id'] . '/' . $item['special_item_id']); ?>
							<input class='warning' type='submit' value='Remove Item' />
							<?php echo form_close(); ?>
						</td>
					</tr>
					<?php else: ?>
						<?php  $repairs[] = $item; ?>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php if(sizeof($repairs) > 0 ): ?>
					<tr>
						<td colspan="5" class="top_lite header" >Special Repairs</td>
					</tr>
					<?php foreach($repairs as $repair): ?>
						<tr>
							<td colspan='2'>[Repair] <?php echo $repair['item_description']; ?></td>
							<td>$<?php echo number_format($repair['item_price'], 2); ?></td>
							<td>$<?php echo number_format($repair['item_tax'], 2); ?></td>
							<td>
								[<?php echo anchor('sales/edit_invoice_special_item/' . $invoice_data['invoice_id'] . '/' . $repair['special_item_id'], 'Edit Values'); ?>]<br />
								[<?php echo anchor('sales/remove_special_item_tax/' . $invoice_data['invoice_id'] . '/' . $repair['special_item_id'], 'Remove Tax'); ?>]<br />
								<?php echo form_open('sales/remove_special_item/' . $invoice_data['invoice_id'] . '/' . $repair['special_item_id']); ?>
								<input class='warning' type='submit' value='Remove Item' />
								<?php echo form_close(); ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php else:?>
				<tr>
					<td colspan='4'><span class='warning'>No Special Orders Found.</span></td>
				</tr>
			<?php endif;?>
			<tr>
				<td class='top' colspan='2'></td>
				<td class='top title' >Price:</td>
				<td class='top' colspan='2'>$<?php echo number_format($invoice_data['total_price'], 2); ?></td>
			</tr>
			<tr>
				<td colspan='2'></td>
				<td class='title'>Tax:</td>
				<td colspan='2'>
					$<?php echo number_format($invoice_data['tax'], 2); ?>
					[<?php echo anchor('sales/remove_all_tax/' . $invoice_data['invoice_id'], 'Remove All Tax'); ?>]
				</td>
			</tr>

			<?php if($invoice_data['is_shipped'] != 1):?>
				<tr>
					<td colspan='2'></td>
					<td class='title' >Shipping:</td>
					<td colspan='2'>[<?php echo anchor('sales/add_shipping/' . $invoice_data['invoice_id'], 'Add Shipping')?>]</td>
				</tr>
			<?php else: ?>
				<tr>
					<td colspan='2'></td>
					<td class='title'>Address:</td>
					<td>
						<?php echo $invoice_data['ship_contact']; ?> <br />
						ph: <?php echo $invoice_data['ship_phone']; ?> <br />
						alt: <?php echo $invoice_data['ship_other_phone']; ?> <br />
						<?php echo $invoice_data['ship_address']; ?> <br />
						<?php if($invoice_data['ship_address2'] != ''): ?>
							<?php echo $invoice_data['ship_address2']; ?> <br />
						<?php endif;?>
						<?php echo $invoice_data['ship_city']; ?>, <?php echo $invoice_data['ship_state']; ?> <?php echo $invoice_data['ship_zip']; ?>
						<?php echo form_open('sales/remove_shipping/' . $invoice_data['invoice_id']);?>
							<input class='warning' type='submit' name='remove_shipping' value='Remove Shipping' />
						<?php echo form_close();?>
					</td>
				</tr>
				<tr>
					<td colspan='2'></td>
					<td class='title'>Shipping Method:</td>
					<td colspan='2'><?php echo $invoice_data['ship_method']; ?> [<?php echo anchor('sales/add_shipping_method/' . $invoice_data['invoice_id'], 'Edit Ship Method')?>]</td>
				</tr>
				<tr>
					<td colspan='2'></td>
					<td class='title'>Shipping Cost:</td>
					<td colspan='2'>$<?php echo $invoice_data['ship_cost']; ?></td>
				</tr>
			<?php endif;?>
			<tr>
				<td colspan='2'></td>
				<td class='title'>Total:</td>
				<td><div id='total_invoice_price_div'>$<?php echo number_format(($invoice_data['total_price'] + $invoice_data['tax'] + $invoice_data['ship_cost']), 2); ?></div></td>
			</tr>
			<?php if($invoice_data['invoice_type'] == 1): ?>
				<tr>
					<td colspan='2' ></td>
					<td class='title' >Total Remaining:</td>
					<td class='warning'>$<?php echo number_format($total_invoice_price - $total_layaway_payments, 2); ?></td>

				</tr>
			<?php endif;?>
			<tr>
				<td colspan='2' ></td>
				<td class='title'>Options:</td>
			<?php if($invoice_data['invoice_type'] == 0 || $invoice_data['invoice_type'] == 1 || $invoice_data['invoice_type'] == 2):?>
					<td nowrap>
						<?php echo form_open('sales/complete_invoice/' . $invoice_data['invoice_id'], 'method="post" style="display: inline;"');?>
							<input style='color: #006611;' type='submit' value='Complete Invoice' />
						<?php echo form_close();?>
						<?php echo form_open('sales/trash_invoice/' . $invoice_data['invoice_id'], 'style="display: inline;"');?>
							<input class='warning' type='submit' value='Trash Invoice' />
						<?php echo form_close();?>
						<?php if($invoice_data['invoice_type'] == 1): ?>
							<br />
							<?php echo form_open('sales/cancel_layaway/' . $invoice_data['invoice_id']); ?>
								<input type='submit' name='cancel' value='Cancel Layaway' />
							<?php echo form_close();?>
						<?php endif;?>

					</td>
			<?php else: ?>
					<td nowrap>
						<?php echo form_open('sales/complete_invoice/' . $invoice_data['invoice_id'] . '/true', 'method="post" style="display: inline;"');?>
							<input style='color: #006611;' type='submit' value='Complete Memo' />
						<?php echo form_close();?>
						<?php echo form_open('sales/trash_invoice/' . $invoice_data['invoice_id'], 'style="display: inline;"');?>
							<input class='warning' type='submit' value='Trash Memo' />
						<?php echo form_close();?>
					</td>
			<?php endif;?>
		</tr>
		</table>
		<p>Sales and Invoices Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>