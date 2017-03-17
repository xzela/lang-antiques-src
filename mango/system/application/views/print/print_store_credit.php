<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 


	<title><?php echo $this->config->item('project_name'); ?> - Customer Store Credit Report</title>
	<script type="text/javascript">
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
	
	</style>
</head>
<body>
		<?php if($start_date == '01/01/1997'): ?>
			<h3>Total Credit From 01/01/1997 to <?php echo date('m/d/Y', strtotime($end_date)); ?></h3>
		<?php else: ?>
			<h3>Total Credit From 01/01/1997 to <?php echo date('m/d/Y', strtotime($start_date) -1); ?></h3>	
		<?php endif;?>		
		<table>
			<tr>
				<td>Total Credit Given:</td>
				<td>$<?php echo number_format($sum_credit['total_given'],2); ?></td>
			</tr>
			<tr>
				<td>Total Credit used:</td>
				<td>$<?php echo number_format($sum_credit['total_used'], 2); ?></td>
			</tr>			
			<tr>
				<td>Total unused Credit:</td>
				<td>$<?php echo number_format($sum_credit['total_credit'],2); ?></td>
			</tr>
		</table>
		
		<h3>Total Transactions From <?php echo date('m/d/Y', strtotime($start_date)); ?> to <?php echo date('m/d/Y', strtotime($end_date)); ?></h3>
		<table class='customer_table'>
			<tr>
				<th>Customer Name</th>
				<th>Date</th>
				<th>Invoice ID</th>
				<th>Credit</th>
				<th>Transaction</th>
			</tr>
			
		<?php if(sizeof($report_data) > 0): ?>
			<?php $trans = array(0 => 'Subtraction', 1=> 'Addition', 3=> 'Manual Addition', 4=>'Manual Subraction'); ?>
			<?php $sum_given = 0; ?>
			<?php $sum_used = 0; ?>
			<?php foreach($report_data as $transaction): ?>
					<?php $c_id = 0; ?>
					<?php foreach($transaction as $credit): ?>
						<?php if($credit['action_type'] == 1 || $credit['action_type'] == 3): //addition?>
							<?php $amount = '' . $credit['credit_amount']; ?>
							<?php $sum_given += $credit['credit_amount']; ?>
						<?php else:?>
							<?php $amount = '-' . $credit['credit_amount']; ?>
							<?php $sum_used += $credit['credit_amount']; ?>
						<?php endif; ?>
						
						<?php if($c_id != $credit['customer_id']): ?>
							<?php $c_id = $credit['customer_id']; ?>
							<tr>
								<td colspan='3'><?php echo anchor('customer/edit/' . $credit['customer_id'], $credit['first_name'] . ' ' . $credit['last_name']); ?></td>
							</tr>
							<tr>
								<td></td>
								<td><?php echo date('m/d/Y', strtotime($credit['date'])); ?></td>
								<td  style='text-align: center;'><?php echo $credit['invoice_id']; ?></td>
								<td><?php echo $trans[$credit['action_type']];?></td>
								<td>$<?php echo number_format($amount,2); ?> </td>							
							</tr>
						<?php else: ?>
							<tr>
								<td></td>
								<td><?php echo date('m/d/Y', strtotime($credit['date'])); ?></td>
								<td style='text-align: center;'><?php echo $credit['invoice_id']; ?></td>
								<td><?php echo $trans[$credit['action_type']];?></td>
								<td>$<?php echo number_format($amount,2); ?> </td>
							</tr>
						<?php endif;?>
					<?php endforeach; ?>
			<?php endforeach;?>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td>Total Credit Given:</td>
						<td>$<?php echo number_format($sum_given, 2); ?></td>
					</tr>			
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td>Total Credit Used: </td>
						<td>$<?php echo number_format($sum_used, 2); ?></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td>Total Unused Credit: </td>
						<td>$<?php echo number_format($sum_given - $sum_used, 2); ?></td>
					</tr>
		<?php else: ?>
			<tr>
				<td colspan='2' class='warning'>No Credit Found. Somethings wrong!</td>
			</tr>
		<?php endif;?>
		</table>
</body>
</html>