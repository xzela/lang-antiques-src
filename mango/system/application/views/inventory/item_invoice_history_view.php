<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Inventory - Invoice History</title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Inventory Item Edit History</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_id, snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
		</ul>
		<h3>Invoice History</h3>
		<table class="customer_table">
			<tr>
				<th nowrap>Invoice ID</th>
				<th nowrap>Sale Date</th>
				<th>Buyer</th>
				<th nowrap>Invoice Type</th>
				<th nowrap>Item Price</th>
				<th nowrap>Item Status</th>
				<th>Options</th>
			</tr>
			<?php if($invoice_data['num_rows'] > 0):?>
				<?php foreach($invoice_data['invoice'] as $invoice): ?>
					<tr>
						<td><?php echo $invoice['invoice_id']; ?></td>
						<td nowrap><?php echo date("M j, Y", strtotime($invoice['sale_date'])); ?></td>
						<td><?php echo $invoice['buyer_name']; ?></td>
						<td><?php echo $invoice['invoice_text']; ?></td>
						<td>$<?php echo number_format($invoice['sale_price'], 2)?></td>
						<td><?php echo $invoice['item_status_text']; ?></td>
						<td class='end'><?php echo anchor('sales/invoice/' . $invoice['invoice_id'], 'View ' . $invoice['invoice_text']); ?></td>
					</tr>
				<?php endforeach ?>
			<?php else:?>
				<tr>
					<td colspan='7' class='end'>No Invoices Found.</td>
				</tr>
			<?php endif;?>
		</table>
		<h3>Return History</h3>
		<table class="customer_table">
			<tr>
				<th nowrap>Return ID</th>
				<th nowrap>Return Date</th>
				<th>Buyer</th>
				<th nowrap>Return Type</th>
				<th>Options</th>
			</tr>
			<?php if($return_data['num_rows'] > 0): ?>					
				<?php foreach($return_data['return'] as $return): ?>
					<tr>
						<td><?php echo $return['return_id']; ?></td>
						<td nowrap><?php echo date("M j, Y", strtotime($return['date'])); ?></td>
						<td><?php echo $return['buyer_name']; ?></td>
						<td><?php echo $return['return_text']; ?></td>
						<td class='end'><?php echo anchor('sales/returns/' .$return['return_id'], 'View Return'); ?></td>
					</tr>
				<?php endforeach ?>
			<?php else: ?>
				<tr>
					<td colspan='5' class='end'>No Returns Found.</td>
				</tr>
			<?php endif;?>
		</table>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>