<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Store Credit Report</title>
	<?php echo snappy_style('calendar.css'); //autoloaded ?>
	<?php echo snappy_script('calendar_us.js'); ?>

		
	<style type='text/css'>
		td.block {
			border-bottom: 1px solid #666;
			background-color: #ddd;
			font-weight: bold;
		}
		
		td.right {
			text-align: right;
		}
		
		input.submit_link {
			border: 0px; 
			cursor: pointer;
			color: #0033FF;
			background-color: transparent;
			font-weight: normal;
			font-family: Lucida Grande, Verdana, Sans-serif;
			font-size: 14px;
		}
		
		input.submit_link:hover {
			text-decoration: underline;
			color: #3399FF;
		}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Store Credit Report</h2>
		<ul id="submenu">
			<li><?php echo anchor('reports/', '<< Back to Reports'); ?></li>
			<li>|</li>
			<li>
				<?php echo form_open(base_url() . 'printer/store_credit', 'style="display: inline;" target="_blank"'); ?>
					<input type='hidden' name='start_date' value='<?php echo $start_date; ?>' />
					<input type='hidden' name='end_date' value='<?php echo $end_date; ?>' />
					<input class='submit_link' type='submit' name='printer_submit' value='Print This Report' />
				<?php echo form_close(); ?>
			</li>
		</ul>
		<h3>Current Report: <?php echo date('m/d/Y', strtotime($start_date)); ?> - <?php echo date('m/d/Y', strtotime($end_date)); ?></h3>
		<?php echo form_open(base_url() . 'reports/store_credit/', 'name="store_credit_form"'); ?>
		<table class='form_table'>
			<tr>
				<td class='title'>Set Start Date:</td>
				<td>
					 <input type='text' name='start_date' id='start_date' value='<?php echo date('m/d/Y', strtotime($start_date)); ?>' readonly='readonly' />
					<script type="text/javascript">
						A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
						new tcal ({
							// form name
							'formname': 'store_credit_form',
							// input name
							'controlname': 'start_date'
						});
					</script>				
				</td>
			</tr>
			<tr>
				<td class='title'>Set End Date: </td>
				<td>
					<input type='text' name='end_date' id='end_date' value='<?php echo date('m/d/Y', strtotime($end_date)); ?>' readonly='readonly' />
					<script type="text/javascript">
						A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
						new tcal ({
							// form name
							'formname': 'store_credit_form',
							// input name
							'controlname': 'end_date'
						});
					</script>				
				</td>
			</tr>
			<tr>
				<td></td>
				<td><input type='submit' name='credit_run'  value='Run Report' /></td>
			</tr>
		</table>
		<?php echo form_close(); ?>
		<?php if($start_date == '01/01/1997'): ?>
			<h3>Total Credit From 01/01/1997 to <?php echo date('m/d/Y', strtotime($end_date)); ?></h3>
		<?php else: ?>
			<h3>Total Credit From 01/01/1997 to <?php echo date('m/d/Y', strtotime($start_date) -1); ?></h3>	
		<?php endif;?>		
		<table class='form_table'>
			<tr>
				<td class='title'>Total Credit Given:</td>
				<td>$<?php echo number_format($sum_credit['total_given'],2); ?></td>
			</tr>
			<tr>
				<td class='title'>Total Credit used:</td>
				<td>$<?php echo number_format($sum_credit['total_used'], 2); ?></td>
			</tr>			
			<tr>
				<td class='title'>Total unused Credit:</td>
				<td>$<?php echo number_format($sum_credit['total_credit'],2); ?></td>
			</tr>
		</table>
		<h3>Total Transactions From <?php echo date('m/d/Y', strtotime($start_date)); ?> to <?php echo date('m/d/Y', strtotime($end_date)); ?></h3>
		<table class='customer_table'>
			<tr>
				<th>Customer Name</th>
				<th>Date</th>
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
								<td><?php echo $trans[$credit['action_type']];?></td>
								<td>$<?php echo number_format($amount,2); ?> </td>							
							</tr>
						<?php else: ?>
							<tr>
								<td></td>
								<td><?php echo date('m/d/Y', strtotime($credit['date'])); ?></td>
								<td><?php echo $trans[$credit['action_type']];?></td>
								<td>$<?php echo number_format($amount,2); ?> </td>
							</tr>
						<?php endif;?>
					<?php endforeach; ?>
			<?php endforeach;?>
					<tr>
						<td></td>
						<td></td>
						<td>Total Credit Given:</td>
						<td>$<?php echo number_format($sum_given, 2); ?></td>
					</tr>			
					<tr>
						<td></td>
						<td></td>
						<td>Total Credit Used: </td>
						<td>$<?php echo number_format($sum_used, 2); ?></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>Total Credit Used: </td>
						<td>$<?php echo number_format($sum_given - $sum_used, 2); ?></td>
					</tr>
		<?php else: ?>
			<tr>
				<td colspan='2' class='warning'>No Credit Found. Somethings wrong!</td>
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