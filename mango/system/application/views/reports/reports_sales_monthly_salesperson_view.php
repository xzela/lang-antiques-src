<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Run Monthly Sales Report</title>
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
		<h2>Run A Monthly Sales Report </h2>
		<ul id="submenu">
			<li><?php echo anchor('reports', '<< Back to Reports Main'); ?></li>
			<li>|</li>
		</ul>
		<h3>Monthly Sales Report By Sales Person: </h3>
		<?php
			$total_sales = 0;
			$total_invoice_amount = 0;
			$total_layaway_amount = 0;
			$total_return_amount = 0; 
		?>
		<table id='report_table'>
			<tr>
				<th>Sales Person</th>
				<th>Num of Sales</th>
				<th>Normal Sales</th>
				<th>Layaway Payments</th>
				<th>Returns</th>
				<th>Totals</th>
				<th>Options</th>
			</tr>
			<?php foreach($report_data as $data): ?>
				<?php
					$total_sales += $data['invoice_data']['basic']['sale_count'];
					$total_invoice_amount += $data['invoice_data']['basic']['amount'];
					$total_layaway_amount += $data['layaway_data']['basic']['amount'];
					$total_return_amount -= $data['return_data']['basic']['amount']; 
					
				?>
				<tr>
					<td><?php echo $data['first_name'] . ' ' . $data['last_name']; ?></td>
					<td><?php echo $data['invoice_data']['basic']['sale_count']; ?></td>
					<td>$<?php echo number_format($data['invoice_data']['basic']['amount'], 2); ?></td>
					<td>$<?php echo number_format($data['layaway_data']['basic']['amount'], 2); ?></td>
					<td class='warning'>$<?php echo number_format($data['return_data']['basic']['amount'], 2); ?></td>
					<td>$<?php echo number_format(($data['invoice_data']['basic']['amount'] + $data['layaway_data']['basic']['amount']) + ($data['return_data']['basic']['amount']), 2); ?></td>
					<td><?php echo anchor('reports/run_monthly_salesperson_detail_report/' . $data['user_id'] . '/' . $month . '/' . $year, 'View Details'); ?></td>
				</tr>
			<?php endforeach;?>
			<tr>
				<td class='total'>Total:</td>
				<td class='total'><?php echo $total_sales; ?></td>
				<td class='total'>$<?php echo number_format($total_invoice_amount, 2); ?></td>
				<td class='total'>$<?php echo number_format($total_layaway_amount, 2); ?></td>
				<td class='total warning'>$-<?php echo number_format($total_return_amount, 2); ?></td>
				<td colspan='2' class='total'>$<?php echo number_format(($total_invoice_amount + $total_layaway_amount) + (-$total_return_amount), 2); ?></td>
			</tr>
		</table>		
		<p>Reports Section of <?php echo $this->config->item('project_name'); ?></p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>