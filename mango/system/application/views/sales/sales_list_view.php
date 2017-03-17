<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - <?php echo $search_name; ?></title>
	<style>
		#invoice_list {
			width: 100%;
			font-size: 14px;
			border: 1px solid #9f9f9f;
			border-collapse: collapse;
		}
		#invoice_list th {
			background-color: #c9c9c9;
		}
		#invoice_list td {
			padding: 3px;
			border-top: 1px solid #9f9f9f;
			vertical-align: top;
			font-size: 12px;
		}
		#invoice_list td.option {
			border-left: 1px solid #c9c9c9;
		}

		#invoice_list td.item {
			background-color: #f1f1f1;
			padding-left: 20px;
			border-top: 1px dashed #c9c9c9;
		}

		#invoice_list tr.total_row {
			background-color: #bdb;
		}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2><?php echo $search_name; ?></h2>
		<ul id="submenu">
			<li><?php echo anchor('sales', '<< Back to Sales Main'); ?></li>
			<li>|</li>
		</ul>
		<div class='pagination'>
			<?php echo $pagination;?>
		</div>
		<table id='invoice_list'>
			<tr>
				<th>ID</th>
				<th>Buyer</th>
				<th>Paid</th>
				<th>Tax</th>
				<th>Ship</th>
				<th>Date</th>
				<th>Type</th>
				<th>Status</th>
				<th>Options</th>
			</tr>
			<?php if(sizeof($date_groups) > 0):?>
				<?php foreach($date_groups as $group_name => $group):?>
					<?php $d_paid = 0; ?>
					<?php $d_tax = 0; ?>
					<?php $d_collected = 0; ?>
					<?php $d_store_credit = 0; ?>
					<?php $d_remaining = 0; ?>
					<?php $d_outstanding = 0; ?>
					<?php $d_layaway_paid = 0; ?>

					<?php foreach($group as $invoice): ?>
						<?php $i_layaway_paid = 0; ?>
						<?php $d_remaining += $invoice['total_price'] + $invoice['tax'] + $invoice['ship_cost']; ?>
						<?php $d_collected += $invoice['actual_amount_paid']; ?>
						<?php $d_paid += $invoice['payout_data']['total_collected']; ?>
						<?php $d_store_credit += $invoice['payout_data']['total_store_credit']; ?>
						<?php $d_tax += $invoice['tax']; ?>
						<?php
							if($invoice_status[$invoice['invoice_status']]['name'] . ' ' . $invoice_types[$invoice['invoice_type']]['name'] == "Unfinished Layaway") {

								foreach($invoice['payout_data']['layaway_payments'] as $l_payment) {
									$d_collected += $l_payment['amount'];
									$i_layaway_paid += $l_payment['amount'];
								}
								$d_layaway_paid += $i_layaway_paid;
								$invoice['actual_amount_paid'] += $i_layaway_paid;
							}
						?>
						<tr>
							<td ><?php echo $invoice['invoice_id']; ?></td>
							<td>
								<?php if($invoice['buyer_type'] == 3):?>
									<b>Internet Sale:</b> <?php echo $invoice['buyer_name'];?>
								<?php else: ?>
									<?php echo $invoice['buyer_name'];?>
								<?php endif;?>
							</td>
							<td>
								<span class="<?php echo ($invoice['actual_amount_paid'] <= 0) ? 'warning' : '' ?>">$<?php echo number_format($invoice['actual_amount_paid'], 2); ?></span>
								<div>
									<?php //var_dump($invoice['payout_data']); ?>
								</div>
							</td>
							<td>$<?php echo number_format($invoice['tax'], 2); ?></td>
							<?php if($invoice['is_shipped']): ?>
								<td>$<?php echo number_format($invoice['ship_cost'], 2); ?></td>
							<?php else: ?>
								<td></td>
							<?php endif; ?>

							<td><?php echo date('m/d/Y', strtotime($invoice['sale_date'])); ?></td>
							<td><?php echo $invoice_types[$invoice['invoice_type']]['name']; ?></td>
							<td style='border-bottom: 1px solid #C9C9C9'>
								<?php if($invoice['invoice_type'] == 3 && $invoice['invoice_status'] == 0): //memo ?>
									<?php echo 'Open Memo'; // memo is open and not returned/closed ?>
								<?php else: ?>
									<?php echo $invoice_status[$invoice['invoice_status']]['name'] . ' ' . $invoice_types[$invoice['invoice_type']]['name']; ?>
								<?php endif;?>
							</td>
							<td class='option memo' style='border-bottom: 1px solid #C9C9C9'>
								<?php if($invoice['invoice_status'] == 1): //open ?>
									<?php echo anchor('sales/invoice/' . $invoice['invoice_id'], 'Edit ' . $invoice_types[$invoice['invoice_type']]['name']); ?>
								<?php elseif($invoice['invoice_status'] == 0): //closed ?>
									<?php echo anchor('sales/invoice/' . $invoice['invoice_id'], 'View ' . $invoice_types[$invoice['invoice_type']]['name']); ?>
								<?php elseif($invoice['invoice_status'] == 2): //returned ?>
									<?php echo anchor('sales/invoice/' . $invoice['invoice_id'], 'View ' . $invoice_types[$invoice['invoice_type']]['name']); ?>
								<?php elseif($invoice['invoice_status'] == 3 || $invoice['invoice_status'] == 4): //closed memo?>
									<?php echo anchor('sales/invoice/' . $invoice['invoice_id'], 'View Closed ' . $invoice_types[$invoice['invoice_type']]['name']); ?>
								<?php elseif($invoice['invoice_status'] == 5): //cancelled layaway?>
									<?php echo anchor('sales/invoice/' . $invoice['invoice_id'], 'View Cancelled ' . $invoice_types[$invoice['invoice_type']]['name']); ?>

								<?php endif;?>
								<?php if($invoice['buyer_type'] == 3):?>
								<br />
									<?php echo anchor('sales/credit_card/' . $invoice['invoice_id'],'Get Credit Card Info');?>
								<?php endif;?>
							</td>
						</tr>
						<?php if(isset($invoice['items']) && sizeof($invoice['items']) > 0): ?>
							<?php foreach($invoice['items'] as $item): ?>
								<tr>
									<td class="item"><?php echo anchor('inventory/edit/' . $item['item_id'], $item['item_number']); ?></td>
									<td class="item"><?php echo $item['item_name']; ?></td>
									<td class="item" >$<?php echo number_format($item['item_price'],2); ?></td>
									<td class="item" colspan="3" style="text-align: right;">
										<?php if($item['invoice_item_status'] == '3'): ?>
											Returned
										<?php endif; ?>
									</td>
									<td class="item end" style="border-right: 1px solid #C9C9C9;"></td>
									<td colspan='2' style='border-top: 0px;'></td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					<?php endforeach;?>
					<tr class='total_row'>
						<td></td>
						<td style="text-align: right;">Total for <?php echo date('M d, Y', strtotime($group_name)); ?>:</td>
						<td>
							Total Paid: <strong title="total collected minus store credit and layaways">$<?php echo number_format($d_paid,2);?></strong>
							<br />
							Total Tax: <strong>$<?php echo number_format($d_tax, 2); ?></strong>
							<br />
							Total Layaway Paid: <strong>$<?php echo number_format($d_layaway_paid, 2); ?></strong>
							<br />
							Store Credit Used: <strong>$<?php echo number_format($d_store_credit, 2); ?></strong>
							<br />
							Total Collected: <strong>$<?php echo number_format($d_collected, 2); ?></strong>
							<br />
							<div>
								<?php  $d_outstanding = $d_collected - $d_remaining; ?>
								<?php if($d_outstanding < 0): ?>
									Total Outstanding: <strong class="warning">$<?php echo number_format($d_outstanding,2); ?></strong>
								<?php else: ?>
									Total Outstanding: <strong>$<?php echo number_format($d_outstanding,2); ?></strong>
								<?php endif; ?>
								<?php // <?php echo "[without tax: " number_format(($d_outstanding + $d_tax), 2) . ']' ?>
							</div>
						</td>
						<td colspan="6"></td>
					</tr>
				<?php endforeach;?>
			<?php else: ?>
				<tr>
					<td colspan='8' class='warning'>Nothing Found</td>
				</tr>
			<?php endif;?>
		</table>
		<div class='pagination'>
			<?php echo $pagination;?>
		</div>
		<p>Sales and Invoices Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>