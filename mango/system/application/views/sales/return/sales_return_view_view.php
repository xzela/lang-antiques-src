<?php
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Returns - View Return # <?php echo $return_data['return_id']; ?></title>

	<script type="text/javascript">
		var base_url = '<?php echo base_url(); ?>';

	</script>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>
			Return - View Return #<?php echo $return_data['return_id']; ?> for:
			<?php if($return_data['buyer_type'] == 1 || $return_data['buyer_type'] == 3):?>
				Customer - <?php echo anchor('customer/edit/' . $buyer_data['customer_id'], $buyer_data['first_name'] . ' ' . $buyer_data['last_name']); ?>
			<?php elseif($return_data['buyer_type'] == 2):?>
				Vendor - <?php echo anchor('vendor/edit/' . $buyer_data['vendor_id'], $buyer_data['name']); ?>
			<?php endif; ?>
		</h2>
		<ul id="submenu">
			<li><?php echo anchor('sales', '<< Back to Sales Main'); ?></li>
			<li>|</li>
			<li><?php echo anchor('sales/invoice/' . $return_data['invoice_id'], 'View Original Invoice'); ?></li>
			<li>|</li>
			<li><?php echo anchor('sales/return_edit/' . $return_data['return_id'], 'Edit Return Slip'); ?></li>
			<li>|</li>
			<li><?php echo anchor('printer/returns/' . $return_data['return_id'], snappy_image('icons/printer.png') . ' Print', 'target="_blank"'); ?></li>
			<li>|</li>
		</ul>
		<table class='item_information'>
			<tr>
				<td class='title'>Return ID:</td>
				<td><?php echo $return_data['return_id']; ?></td>
				<td class='title'>Orginial Invoice ID:</td>
				<td><?php echo anchor('sales/invoice/' . $return_data['invoice_id'], $return_data['invoice_id']); ?></td>
			</tr>
			<tr>
				<td class='title'>Return Date:</td>
				<td><?php echo $return_data['date'] == '0000-00-00' ? '' : date('m/d/Y', strtotime($return_data['date'])); ?></td>
				<td class='title'> Invoice Type:</td>
				<td>
					<?php if($return_data['buyer_type'] == 1): ?>
						In store purchase
					<?php elseif($return_data['buyer_type'] == 2):?>
						Vendor purchase
					<?php elseif($return_data['buyer_type'] == 3): ?>
						Internet purchase
					<?php endif;?>
				</td>
			</tr>
			<tr>
				<td class='title'>Return Type:</td>
				<td>
					<?php if($return_data['refund_type'] == 1 ): //store_credit ?>
						Store Credit
					<?php else: //cash return ?>
						Cash Return
					<?php endif;?>
				</td>
			</tr>
		</table>
		<?php if($return_data['refund_type'] == 1): ?>
			<?php //do nothing ?>
		<?php else: ?>
		<h3>Return Credit</h3>
		<table class='invoice_table'>
			<tr>
				<th>Payment ID</th>
				<th>Payment Type</th>
				<th>Credit Amount</th>
			</tr>
			<?php foreach($payments as $payment): ?>
				<tr>
					<td><?php echo $payment['invoice_payment_id']; ?></td>
					<td><?php echo $payment_methods[$payment['method']]['name']; ?></td>
					<td>$<?php echo number_format($payment['amount'], 2); ?></td>
				</tr>
			<?php endforeach;?>
		</table>
		<?php endif;?>
		<h3>Items Returned</h3>
		<table class='invoice_table'>
			<tr>
				<th nowrap>Number</th>
				<th>Description</th>
				<th nowrap>Retail</th>
				<th nowrap>Tax</th>
			</tr>
			<?php if(sizeof($return_items) > 0 ):?>
				<?php foreach($return_items as $item):?>
				<tr>
					<td>
						<div style='text-align: center;'><?php echo anchor('inventory/edit/' . $item['item_id'], $item['item_number']); ?></div>
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
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan='4'><span class='warning'>No Inventory Items Found</span></td>
				</tr>
			<?php endif;?>
			<tr>
				<td colspan='5' class='top_lite header'>Special Orders</td>
			</tr>
			<?php if(sizeof($special_items) > 0):?>
				<?php foreach($special_items as $sitem):?>
					<tr>
						<td colspan='2'><?php echo $sitem['item_description']; ?></td>
						<td>$<?php echo number_format($sitem['item_price'], 2); ?></td>
						<td>$<?php echo number_format($sitem['item_tax'], 2); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else:?>
				<tr>
					<td colspan='4'><span class='warning'>No Special Orders Found.</span></td>
				</tr>
			<?php endif;?>
			<tr>
				<td class='top'></td>
				<?php if($return_data['refund_type'] == 1 ): //store_credit ?>
					<td class='top title' style='text-align: right;'>Total Store Credit:</td>
				<?php else: //cash return ?>
					<td class='top title' style='text-align: right;'>Total Return:</td>
				<?php endif;?>

				<td class='top' colspan='2'>$<?php echo number_format($return_data['refund'], 2); ?></td>
			</tr>
		</table>
		<?php if($return_data['note'] != ''): ?>
			<h3>Notes:</h3>
			<p>
				<?php echo $return_data['note']; ?>
			</p>
		<?php endif;?>

		<p>Sales and Invoices Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>