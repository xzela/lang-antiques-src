<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	
	<title><?php echo $this->config->item('project_name'); ?> - Monthly Turnover Report</title>
	<script tupe='text/javascript'>
	window.onload = function() {
		window.print();
	}
	</script>
	<style type='text/css'>
		body {
			font-family: Lucida Grande, Verdana, Sans-serif;
			font-size: 14px;
			color: #4F5155;
		}
		table {
			border-collapse: collapse;
			border: 1px solid #999999;
			background-color: #ffffff;
		}
		table th {
			background-color: #cfcfcf;
			border-bottom: 1px solid #999999;
		}
		table td {
			background-color: #ffffff;
			border-bottom: 1px dashed #cfcfcf; 
		}
	table {
		border-collapse: collapse;
	}
	
	th {
		background-color: #ccc;
	}
	
	td {
		padding: 2px;
		vertical-align: top;
		border-bottom: 1px dashed #ccc;
	}
	
	td.right_side {
		border-right: 1px solid #999;
	}
	
	td.left_side {
		border-left: 1px solid #999;
	}
	
	td.heading {
		font-size: 16px;
		font-weight: bold;
		border-bottom: 2px solid #999;
		padding-top: 10px;
	}
	td.title {
		text-align: right;
		font-weight: bold;
	}
	td.sum {
		border-bottom: 1px solid #999;
	}		
	</style>
</head>
<body>
		<h2>Monthly Turnover Report - <?php echo $month; ?>/<?php echo $year; ?></h2>
		<?php 
			$grand_total_count = 0;
			$grand_total_cost = 0;
			$grand_total_price = 0;
		?>
		<table>
			<?php foreach($invoice_data as $group):?>
				<?php 
					$total_count = 0;
					$total_sales = 0;
					$total_cost = 0;
					$total_profit = 0;
				?>
				<tr>
					<td class='heading' colspan='10'><?php echo $group['mjr_class']['mjr_class_name']; ?></td>		
				</tr>
				<tr>
					<th nowrap>Item Number</th>
					<th nowrap>Invoice ID</th>
					<th nowrap>Invoice Type</th>
					<th>Buyer</th>
					<th>Seller</th>
					<th>Title</th>
					<th nowrap>Sale Date</th>
					<th nowrap>Sale Price</th>
					<th>Cost</th>
					<th>Profit</th>
				</tr>
					
				<?php foreach($group['items'] as $item):?>
					<?php 
						$total_count++;
						$total_sales += $item['sale_price'];
						$total_cost += $item['purchase_price'];
						$total_profit +=  @($item['sale_price'] / $item['purchase_price']);
					?>
					<tr>
						<td class='left_side'><?php echo anchor('inventory/edit/' . $item['item_id'], $item['item_number']); ?></td>
						<td><?php echo anchor('sales/invoice/' . $item['invoice_id'], $item['invoice_id']); ?></td>
						<td><?php echo $invoice_type[$item['invoice_type']]['name']; ?></td>
						<td>
							<?php if($item['invoice_buyer_type'] == 1 || $item['invoice_buyer_type'] == 3):?>
								<?php echo anchor('customer/edit/' . $item['invoice_buyer_id'], $item['buyer_data']['name']); ?>
							<?php elseif($item['buyer_type'] == 2): ?>
								<?php echo anchor('vendor/edit/' . $item['buyer_data']['buyer_id'], $item['buyer_data']['name']); ?>
							<?php else: ?>
								Unknown
							<?php endif;?>
						</td>
						<td >
							<?php if($item['seller_type'] == 2):?>
								<?php echo anchor('customer/edit/' . $item['seller_id'], $item['seller_data']['first_name'] . ' ' . $item['seller_data']['last_name']); ?>
							<?php elseif($item['seller_type'] == 1): ?>
								<?php echo anchor('vendor/edit/' . $item['seller_id'], $item['seller_data']['name']); ?>
							<?php else: ?>
								Unknown
							<?php endif;?>
						</td>
						<td>
							<?php echo $item['item_name']; ?>
						</td>
						<td><?php echo $item['sale_date']; ?></td>
						<td>$<?php echo number_format($item['sale_price'], 2); ?></td>
						<td>$<?php echo number_format($item['purchase_price'], 2); ?></td>
						<td class='right_side'><?php echo @number_format($item['sale_price'] / $item['purchase_price'], 2); //the @ supresses the errors ?></td>
					</tr>
				<?php endforeach;?>
				<tr>
					<td colspan='6' class="sum left_side title">Total:</td>
					<td class="sum">Count: <?php echo $total_count; ?></td>
					<td class="sum">$<?php echo number_format($total_sales,2); ?></td>
					<td class="sum">$<?php echo number_format($total_cost,2); ?></td>
					<td class='sum right_side'><?php echo number_format($total_profit/$total_count, 2); ?></td>
				</tr>
				<?php
					$grand_total_count += $total_count;
					$grand_total_cost += $total_cost;
					$grand_total_price += $total_sales;
				?>
			<?php endforeach;?>
			<tr>
				<td class='heading' colspan='10'>Closed Layaways</td>
			</tr>
			<tr>
				<th nowrap>Item Number</th>
				<th nowrap>Invoice ID</th>
				<th nowrap>Invoice Status</th>
				<th>Buyer</th>
				<th>Seller</th>
				<th>Description</th>
				<th nowrap>Sale Date</th>
				<th nowrap>Sale Price</th>
				<th colspan='2'>Cost</th>
			</tr>
			
			
			<?php foreach($layaway_data as $data):?>
				<tr>			
					<td class='left_side'><?php echo anchor('inventory/edit/' . $data['item_id'], $data['item_number']); ?></td>
					<td><?php echo anchor('sales/invoice/'. $data['invoice_id'], $data['invoice_id']); ?></td>
					<td><?php echo $invoice_status[$data['invoice_status']]['name']; ?></td>
					<td nowrap>
						<?php if($data['invoice_buyer_type'] == 1 || $data['invoice_buyer_type'] == 3):?>
							<?php echo anchor('customer/edit/' . $data['invoice_buyer_id'], $data['buyer_data']['first_name'] . ' ' . $data['buyer_data']['last_name']); ?>
						<?php elseif($data['buyer_type'] == 2): ?>
							<?php echo anchor('vendor/edit/' . $data['invoice_buyer_id'], $data['buyer_data']['name']); ?>
						<?php else: ?>
							Unknown
						<?php endif;?>
					</td>
					<td nowrap>
						<?php if($data['seller_type'] == 2):?>
							<?php echo anchor('customer/edit/' . $data['seller_id'], $data['seller_data']['first_name'] . ' ' . $item['seller_data']['last_name']); ?>
						<?php elseif($data['seller_type'] == 1): ?>
							<?php echo anchor('vendor/edit/' . $data['seller_id'], $data['seller_data']['name']); ?>
						<?php else: ?>
							Unknown
						<?php endif;?>
					</td>
					<td>
						<?php echo $data['item_name']; ?>
					</td>
					<td><?php echo $data['sale_date']; ?></td>
					<td>$<?php echo number_format($data['sale_price'],2); ?></td>
					<td colspan='2' class='right_side'>$<?php echo number_format($data['purchase_price'], 2); ?></td>
				</tr>
			<?php endforeach;?>
				<tr>
					<td colspan='6' class="sum left_side title">Grand Total Count:</td>
					<td colspan='4' class="sum right_side" ><?php echo $grand_total_count; ?></td>
				</tr>
				<tr>
					<td colspan='6' class="sum left_side title">Grand Total Cost:</td>
					<td  colspan='4' class="sum right_side" >$<?php echo number_format($grand_total_cost,2); ?></td>
				</tr>
				<tr>
					<td colspan='6' class="sum left_side title">Grand Total Price:</td>
					<td colspan='4' class="sum right_side" >$<?php echo number_format($grand_total_price,2); ?></td>
				</tr>
		</table>
</body>
</html>