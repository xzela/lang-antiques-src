<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Monthly Consignmnet Report Report</title>
	<style type='text/css'>
		#report_table {
			border: 1px solid #999;
			margin: 5px;
			border-collapse: collapse;
		}
		#report_table th {
			border-bottom: 1px dashed #999;
			background-color: #ddd;
			padding-bottom: 5px;
		}
		#report_table td {
			border-bottom: 1px dashed #ddd;
			padding: 2px;
		}
		#report_table td.total {
			padding-top: 6px;
			font-weight: bold;
			background-color: #eee;
		}
		
	</style>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Run A Monthly Consignment Report </h2>
		<ul id="submenu">
			<li><?php echo anchor('reports', '<< Back to Reports Main'); ?></li>
			<li>|</li>
		</ul>
		<h3>Monthly Consignment Report:</h3>
		<table id='report_table'>
			<tr>
				<th>Date</th>
				<th>Invoice Number</th>
				<th>Consignee</th>
				<th>Item Number</th>
				<th>Item Name</th>
				<th>Amount</th>
				<th>Options</th>
			</tr>
			<?php foreach($report_data['extra'] as $data): ?>
				<tr>
					<td><?php echo date('M d, Y', strtotime($data['sale_date'])); ?></td>
					<td><?php echo $data['invoice_id'];?></td>
					<?php if($data['buyer_type'] == 1 || $data['buyer_type'] == 3):?>
						<td><?php echo anchor('customer/edit/' . $data['buyer_id'], $data['seller_data']['first_name'] . ' ' . $data['seller_data']['last_name']); ?></td>
					<?php else:?>
						<td><?php echo anchor('vendor/edit/' . $data['buyer_id'], $data['buyer_data']['name']); ?></td>
					<?php endif;?>
					<td><?php echo anchor('inventory/edit/' . $data['item_id'], $data['item_number']); ?></td>
					<td><?php echo $data['item_name']; ?></td>
					<td>$<?php echo number_format($data['sale_price'], 2); ?></td>
					<td><?php echo anchor('sales/invoice/' . $data['invoice_id'], 'View Invoice'); ?></td>
				</tr>
			<?php endforeach;?>
			<tr>
				<td class='total' colspan='5' style='text-align: right;'>Total: </td>
				<td>$<?php echo number_format($report_data['basic']['amount'], 2);?></td>
			</tr>
		</table>
		<p>Reports Section of <?php echo $this->config->item('project_name'); ?></p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>