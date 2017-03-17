<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Open Memo Report</title>
	<style type='text/css'>
		td.block {
			border-bottom: 1px solid #666;
			background-color: #ddd;
			font-weight: bold;
		}
		td.right {
			text-align: right;
		}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Open Memo Report</h2>
		<ul id="submenu">
			<li><?php echo anchor('reports/', '<< Back to Reports'); ?></li>
			<li>|</li>
			<li><?php echo anchor('reports/open_memo/true', 'Show All Memos'); ?></li>
			<li>|</li>
		</ul>
		<table class='customer_table'>
			<tr>
				<th>Memo ID</th>
				<th>Buyer Name</th>
				<th>Buyer Contact</th>
				<th>Memo Open Date</th>
				<th>Memo Close Date</th>
				<th>Memo Price</th>
				<th>Num of Items</th>
				<th>Options</th>
			</tr>
		<?php if(sizeof($report_data) > 0): ?>
			<?php foreach($report_data as $memo):?>
				<tr>
					<td><?php echo anchor('sales/invoice/' . $memo['invoice_id'], $memo['invoice_id']); ?></td>
					<td><?php echo anchor('vendor/edit/' . $memo['buyer_id'], $memo['buyer_data']['name']); ?></td>
					<td><?php echo $memo['buyer_data']['first_name'] . ' ' . $memo['buyer_data']['last_name']; ?></td>
					<td><?php echo date('M d, Y', strtotime($memo['sale_date'])); ?></td>
					<td><?php echo $memo['memo_close_date']; ?></td>
					<td>$<?php echo number_format($memo['total_price'], 2); ?></td>
					<td><?php echo $memo['num_items']; ?></td>
					<td><?php echo anchor('sales/invoice/' . $memo['invoice_id'], 'View Memo'); ?></td>
				</tr>			
			<?php endforeach;?>
		<?php else: ?>
			<tr>
				<td colspan='5' class='warning'>No Memos Found.</td>
			</tr>
		<?php endif;?>
		</table>
		<p>Reports Section of <?php echo $this->config->item('project_name'); ?></p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>