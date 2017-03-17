<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />

	<?php echo snappy_style('jquery-ui-1.7.3.custom.css'); ?>
	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery-ui-1.7.2.js'); ?>


	<title><?php echo $this->config->item('project_name'); ?> - Run A Daily Monies Report</title>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.datepicker').datepicker();
		});
	</script>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Run A Daily Monies Report </h2>
		<ul id="submenu">
			<li><?php echo anchor('reports', '<< Back to Reports Main'); ?></li>
			<li>|</li>
		</ul>
		<h3>Daily Monies Report: </h3>
		<?php echo form_open('reports/daily_monies_report/');?>
		<table class='item_information'>
			<tr>
				<td class='title' >Reporting Day: </td>
				<td>
					<input type="text" id="start_date" name="start_date" class="datepicker" value="<?php echo set_value('start_date'); ?>"/>
				</td>
			</tr>
			<tr>
				<td></td>
				<td ><input type='submit' value='Run Report' /></td>
			</tr>
		</table>
		<?php echo form_close();?>
		<h3>Total Monies: $<?php echo number_format($total_payments, 2); ?></h3>

		<h3>Invoice Payments</h3>
		<table class="customer_table">
			<tr>
				<th>Invoice Id</th>
				<th>Method of Payment</th>
				<th>Buyer</th>
				<th>Amount</th>
			</tr>
			<?php if(sizeof($invoice_payments['records']) > 0 ): ?>
				<?php foreach($invoice_payments['records'] as $invoice_payment): ?>
					<tr>
						<td><?php echo anchor('sales/invoice/' . $invoice_payment['invoice_id'], $invoice_payment['invoice_id']); ?></td>
						<td><?php echo $payment_methods[$invoice_payment['method']]['name']; ?></td>
						<td><?php echo @anchor($invoice_payment['buyer']['link'], $invoice_payment['buyer']['name']); ?></td>
						<?php if($invoice_payment['method'] == 4): ?>
							<td><span class="warning">-$<?php echo number_format($invoice_payment['amount'], 2); ?></span></td>
						<?php else: ?>
							<td>$<?php echo number_format($invoice_payment['amount'], 2); ?></td>
						<?php endif;?>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan="3"><strong>Total Invoice Monies for: <?php echo date('M d, Y', strtotime($start_date));?></strong></td>

					<td>$<?php echo number_format($invoice_payments['total_monies'], 2); ?></td>
				</tr>
			<?php else: ?>
				<tr>
					<td>Nothing found.... :(</td>
				</tr>
			<?php endif; ?>
		</table>

		<h3>Layaway Payments</h3>
		<table class="customer_table">
			<tr>
				<th>Invoice Id</th>
				<th>Method of Payment</th>
				<th>Buyer</th>
				<th>Amount</th>
			</tr>
			<?php if(sizeof($layaway_payments['records']) > 0): ?>
				<?php foreach($layaway_payments['records'] as $layaway_payment): ?>
				<tr>
					<td><?php echo anchor('sales/invoice/' . $layaway_payment['invoice_id'], $layaway_payment['invoice_id']); ?></td>
					<td><?php echo $payment_methods[$layaway_payment['method']]['name']; ?></td>
					<td><?php echo @anchor($layaway_payment['buyer']['link'], $layaway_payment['buyer']['name']); ?></td>
					<td>$<?php echo number_format($layaway_payment['amount'], 2); ?></td>
				</tr>
				<?php endforeach;?>
				<tr>
					<td colspan="3"><strong>Total Layaway Monies for: <?php echo date('M d, Y', strtotime($start_date));?></strong></td>
					<td>$<?php echo number_format($layaway_payments['total_monies'], 2); ?></td>
				</tr>
			<?php else: ?>
				<tr>
					<td>nothing found.... :(</td>
				</tr>
			<?php endif; ?>
		</table>

		<p>Reports Section of <?php echo $this->config->item('project_name'); ?></p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>