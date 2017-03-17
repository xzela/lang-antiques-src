<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Invoices - Get Credit Card Information: Invoice <?php echo $invoice_data['invoice_id']; ?></title>
	<?php echo snappy_script('ajax/prototype.js'); ?>
	<?php echo snappy_script('ajax/scriptaculous.js'); ?>
	<?php echo snappy_script('ajax/controls.js'); ?>
	<?php echo snappy_script('ajax/effects.js'); ?>

	<?php echo snappy_script('vendor/vendor_main.js'); ?>


	<script type="text/javascript">
	base_url = '<?php echo base_url(); ?>';

	</script>
	<style>

	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2><?php echo $this->config->item('project_name'); ?> - Invoices - Get Credit Card Information: <?php echo anchor('sales/invoice/' . $invoice_data['invoice_id'], 'Invoice ' . $invoice_data['invoice_id']);?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('sales', 'Back to Sales'); ?></li>
			<li>|</li>
			<li><?php echo anchor('sales/invoice/' . $invoice_data['invoice_id'], 'View Invoice')?></li>
		</ul>

		<table class='invoice_table'>
			<tr>
				<th nowrap>Number</th>
				<th>Description</th>
				<th nowrap>Retail Price</th>
				<th nowrap>Tax</th>
			</tr>
			<?php if(sizeof($invoice_items) > 0 ):?>
				<?php foreach($invoice_items as $item):?>
				<tr>
					<td>
						<div style='text-align: center;'><?php echo $item['item_number']; ?></div>
							<?php if(sizeof($item['image_array']['external_images']) > 0): ?>
								<?php
									echo "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['external_images'][0]['image_location'] . '&image_type=' . $item['image_array']['external_images'][0]['image_class'] . '&image_size=' . $item['image_array']['external_images'][0]['image_size'] . "' />";
								?>
							<?php elseif(sizeof($item['image_array']['internal_images']) > 0):?>
								<?php
								echo "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $item['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $item['image_array']['internal_images'][0]['image_size'] . "' />";
								?>
							<?php else: ?>
								No Image Provided
							<?php endif; ?>
					</td>
					<td class='left'>
						<h4><?php echo $item['item_name'];?></h4>
						<p><?php echo $item['item_description'];?></p>
					</td>
					<td class='left'>$<?php echo number_format($item['sale_price'], 2); ?></td>
					<td class='left'>$<?php echo number_format($item['sale_tax'], 2); ?></td>
				</tr>
				<?php endforeach;?>
			<?php endif;?>
			<?php if(sizeof($special_items) > 0):?>
				<tr>
					<td colspan='5' class='header'>Special Orders</td>
				</tr>
				<?php foreach($special_items as $item):?>
					<tr>
						<td colspan='2'><?php echo $item['item_description']; ?></td>
						<td class='left'>$<?php echo number_format($item['item_price'], 2); ?></td>
						<td class='left'>$<?php echo number_format($item['item_tax'], 2); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif;?>
			<tr>
				<td class='top nonborder'></td>
				<td class='top title nonborder'>Price:</td>
				<td class='top left' colspan='2'>$<?php echo number_format($invoice_data['total_price'], 2); ?></td>
			</tr>
			<tr>
				<td class='nonborder' ></td>
				<td class='title nonborder'>Tax:</td>
				<td class='left' colspan='2'>$<?php echo number_format($invoice_data['tax'], 2); ?></td>
			</tr>

			<?php if($invoice_data['is_shipped'] == 1):?>
			<tr>
				<td class='nonborder' ></td>
				<td class='title nonborder' nowrap>Shipping Method:</td>
				<td class='left' colspan='2'><?php echo $invoice_data['ship_method']; ?></td>
			</tr>
			<tr>
				<td class='nonborder' ></td>
				<td class='title nonborder' nowrap>Shipping Cost:</td>
				<td class='left' colspan='2'>$<?php echo $invoice_data['ship_cost']; ?></td>
			</tr>
			<tr>
				<td class='nonborder' ></td>
				<td class='title nonborder' nowrap>Ship to:</td>
				<td class='left' colspan='2' nowrap>
					<?php echo $invoice_data['ship_contact']; ?> <br />
					<?php echo $invoice_data['ship_address']; ?><br />
					<?php echo $invoice_data['ship_city']; ?>, <?php echo $invoice_data['ship_state']; ?> <?php echo $invoice_data['ship_zip']; ?>
				</td>
			</tr>

			<?php endif;?>
			<tr>
				<td class='nonborder' ></td>
				<td class='title nonborder' nowrap>Total:</td>
				<td class='left' colspan='2'>$<?php echo number_format(($total_invoice_price), 2); ?></td>
			</tr>
		</table>
		<?php if($invoice_data['notes'] != ''):?>
			<h2>Notes:</h2>
			<p>
				<?php echo $invoice_data['notes']; ?>
			</p>
		<?php endif;?>
		<?php if($credit_card_data != null):?>
			<h2>
				Credit Card Information:
				<span class='small_text'>[<?php echo anchor('printer/credit_card/' . $invoice_data['invoice_id'] . '/' . $invoice_data['buyer_id'], 'Print Credit Card Info', 'target="_blank"')?>]</span>
				<span class='small_text'>[<?php echo anchor('gateway/invoice/' . $credit_card_data['invoice_id'], 'Run Credit Card');?>]</span>
			</h2>
			<style type='text/css'>
				#credit_card_table {
					border: 1px solid #999999;
					padding: 2px;
					margin: 2px;
					border-collapse: collapse;
				}

				#credit_card_table td {
					padding: 3px;
				}

				#credit_card_table td.title {
					border-right: 1px solid #cccccc;
					text-align: right;
					font-weight: bold;
				}

			</style>

			<table id='credit_card_table'>
				<?php if($credit_card_data['masked'] == 0): ?>
					<tr>
						<td class='title'>Card Holder: </td>
						<td><?php echo $credit_card_data['card_holder']; ?></td>
					</tr>
					<tr>
						<td class='title'>Card Number:</td>
						<td>[encrypted] - Use <span class=''><?php echo anchor('gateway/invoice/' . $credit_card_data['invoice_id'], 'Run Credit Card');?></span> to see</td>
					</tr>
					<tr>
						<td class='title'>Expiration Date:</td>
						<td><?php echo $credit_card_data['card_month'] . '/' . $credit_card_data['card_year'];?></td>
					</tr>
					<tr>
						<td class='title'>CVV Number:</td>
						<td>[encrypted]</td>
					</tr>
				<?php endif; ?>
				<tr>
					<td class='title'>Address:</td>
					<td><?php echo $buyer_data['address']; ?></td>
				</tr>
				<tr>
					<td class='title'>City:</td>
					<td><?php echo $buyer_data['city']; ?></td>
				</tr>
				<tr>
					<td class='title'>State/Zip:</td>
					<td><?php echo $buyer_data['state'] . '/' . $buyer_data['zip']; ?></td>
				</tr>
			</table>
			<?php if($credit_card_data['masked'] == 0): ?>
				<h3 class='warning'>There is a credit card one file!</h3>
				<p>
					<?php echo anchor('sales/mask_credit_card/' . $invoice_data['invoice_id'] . '/' . $invoice_data['buyer_id'], 'Remove Credit Card'); ?>
				</p>
			<?php endif; ?>
		<?php else: ?>
			<p class='warning'>
				Hmm, looks like this was a manual internet sale. Thus there is no credit card information.
			</p>
		<?php endif;?>
		<?php if($invoice_data['invoice_type'] == 3): ?>
			<!-- Memos do not show Payment information -->
		<?php else: ?>
			<table class='invoice_table' width='100%'>
				<tr>
					<th style='text-align: left;'>Payments</th>
					<th style='text-align: left;'>Date</th>
					<th style='text-align: left;'>Amount</th>
				</tr>
			<?php if(sizeof($payments) > 0):?>
				<?php foreach($payments as $payment):?>
					<tr>
						<td><?php echo $payment_methods[$payment['method']]['name']; ?></td>
						<td><?php echo date('M d, Y', strtotime($payment['date'])); ?></td>
						<td>$<?php echo number_format($payment['amount'], 2); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else:?>
				<tr>
					<td colspan='4'>No Payments</td>
				</tr>
			<?php endif;?>
			</table>
		<?php endif;?>
		<p>Sales and Invoices Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>