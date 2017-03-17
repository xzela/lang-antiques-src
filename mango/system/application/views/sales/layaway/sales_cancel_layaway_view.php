<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Invoices - Cancel Layaway: Layaway <?php echo $invoice_data['invoice_id']; ?></title>
	<script type="text/javascript">
		var base_url = <?php echo "'" . base_url() . "'"; ?>;

	</script>
	<style type='text/css'>
		.option {
			margin: 2px;
			padding: 5px;
			border: 1px dashed #999;
			background-color: #f1f1f1;
		}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>You Are About to Cancel A Layaway, What Do You Want to Do? Layaway: <?php echo  $invoice_data['invoice_id'];?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('sales/invoice/' . $invoice_data['invoice_id'], 'Back to Layaway')?></li>
		</ul>
		<p>
			The payments applied to this layaway can be treated one of two ways. 
			They can either be turned into store credit or completely refunded to the customer.
		</p>
		<table class='invoice_table layaway_table'>
			<tr>
				<th>Layaway ID</th>
				<th>Type</th>
				<th>Amount</th>
			</tr>
			<?php $total_layaway_payments = 0; ?>
			<?php if(sizeof($layaway_payments) > 0): ?>
				<?php foreach($layaway_payments as $payment): ?>
					<?php $total_layaway_payments += $payment['amount']; ?>
					<tr>
						<td><?php echo $payment['layaway_id']; ?></td>
						<td>
							<?php if($payment['payment_type'] == 1):?>
								Down Payment
							<?php else:?>
								Additional Payment
							<?php endif;?>
						</td>
						<td>
							$<?php echo number_format($payment['amount'], 2); ?> <?php echo $payment_methods[$payment['method']]['name']; ?>  payment was made on <?php echo $payment['payment_date']; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else:?>
				<tr>
					<td colspan='4' class='warning'>No Payments Found</td>
				</tr>
			<?php endif;?>
			<tr>
				<td colspan='2' class='title top_lite'>Total Payments:</td>
				<td colspan='2' class='top_lite'>$<?php echo number_format($total_layaway_payments, 2); ?></td>
			</tr>
		</table>
			<?php echo form_open('sales/cancel_layaway/' . $invoice_data['invoice_id']); ?>
			<input type='hidden' name='invoice_id' value='<?php echo $invoice_data['invoice_id']; ?>' />
			<input type='hidden' name='amount' value='<?php echo $total_layaway_payments; ?>' />
			<div class='option'>
				<h3>Give Them Store Credit? <input type='submit' name='credit' value='Give Store Credit' /></h3>				
				<p>Picking this option will do the following:</p>
				<ul>
					<li>Give the customer <strong>$<?php echo number_format($total_layaway_payments, 2); ?></strong> of store credit.</li>
					<li>Return any items applied to this Layaway back to inventory and mark them as 'Available'.</li>
					<li>Will Cancel the Layaway.</li>
				</ul>				
			</div>
			<div class='option'>
				<h3>Give Them Their Money Back? <input type='submit' name='refund' value='Give Refund' /> </h3>
				<p>Picking this option will do the following:</p>
					<ul>
						<li>Return any items applied to this Layaway back to inventory and mark them as 'Available'.</li>
						<li>Will Cancel the Layaway.</li>
					</ul>				
			</div>
				
		<?php echo form_close(); ?>
		<p>Sales and Invoices Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>