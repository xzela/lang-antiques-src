<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	
	<title><?php echo $this->config->item('project_name'); ?> - Layaway Payments Report</title>
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
	</style>
</head>
<body>
		<h2>Layaway Payments Report - <?php echo $start_date; ?> - <?php echo $end_date; ?></h2>
		<table>
			<tr>
				<th>Invoice ID</th>
				<th>Buyer Contact</th>
				<th>Payment Date</th>
				<th>Payment Amount</th>
			</tr>
		<?php if(sizeof($report_data) > 0): ?>
			<?php foreach($report_data as $layaway):?>
				<tr>
					<td><?php echo anchor('sales/invoice/' . $layaway['invoice_id'], $layaway['invoice_id']);?></td>
					<td><?php echo anchor($layaway['buyer_data']['link'], $layaway['buyer_data']['first_name'] . ' ' . $layaway['buyer_data']['last_name']); ?></td>
					<td><?php echo date('m/d/Y', strtotime($layaway['payment_date']));?></td>
					<td>$<?php echo number_format($layaway['amount'], 2)?></td>
				</tr>			
			<?php endforeach;?>
		<?php else: ?>
			<tr>
				<td colspan='5' class='warning'>No Memos Found.</td>
			</tr>
		<?php endif;?>
		</table>
</body>
</html>