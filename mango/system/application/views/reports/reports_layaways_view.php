<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_style('calendar.css'); //autoloaded ?>
	
	<?php echo snappy_script('calendar_us.js'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Layaway Payments Report</title>
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
		<h2>Layaway Payments Report</h2>
		<ul id="submenu">
			<li><?php echo anchor('reports/', '<< Back to Reports'); ?></li>
			<li>|</li>
			<li>
				<?php echo form_open(base_url() . 'printer/layaways/', 'style="display: inline;" target="_blank"'); ?>
					<input type='hidden' name='start_date' value='<?php echo $start_date; ?>' />
					<input type='hidden' name='end_date' value='<?php echo $end_date; ?>' />
					<input class='submit_link' type='submit' name='printer_submit' value='Print This Report' />
				<?php echo form_close(); ?>
			</li>
		</ul>
		<h3>Current Report: <?php echo date('m/d/Y', strtotime($start_date)); ?> - <?php echo date('m/d/Y', strtotime($end_date)); ?></h3>
		<?php echo form_open(base_url() . 'reports/layaways/', 'name="store_credit_form"'); ?>
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
				<td class='title'>Set End Date:</td>
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
		<table class='customer_table'>
			<tr>
				<th>Invoice ID</th>
				<th>Slip Number</th>
				<th>Buyer Contact</th>
				<th>Payment Date</th>
				<th>Payment Amount</th>
			</tr>
		<?php if(sizeof($report_data) > 0): ?>
			<?php foreach($report_data as $layaway):?>
				<tr>
					<td><?php echo anchor('sales/invoice/' . $layaway['invoice_id'], $layaway['invoice_id']);?></td>
					<td><?php echo $layaway['sales_slip_number'];?></td>
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
		<p>Reports Section of <?php echo $this->config->item('project_name'); ?></p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>