<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title>Project Mango - Sales and Invoices</title>
	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Search Invoices/Memos</h2>
		<ul id="submenu">
			<li><?php echo anchor('sales', '<< Back to Sales Main'); ?></li>
			<li>|</li>
		</ul>		
		<div>
			<h3>Enter Invoice Number or Sales Slip Number or Vendor Name or Customer Last Name</h3>
			<?php echo form_open('sales/search_id'); ?>
			<table class='form_table'>
				<tr>
					<td class='title'>Search Field:</td>
					<td><input type='text' name='search_text' size='75' value='<?php echo set_value('search_text')?>' /></td>
				</tr>
				<tr>
					<td class='title'></td>
					<td><input type='submit' name='search_submit' value='Search!' /></td>
				</tr>
				<tr>
					<td colspan='2'>
						<?php echo validation_errors(); ?>
					</td>
				</tr>				
			</table>
			
			<?php echo form_close(); ?>
		</div>
		<table class='customer_table'>
			<tr>
				<th>Invoice ID</th>
				<th>Slip Number</th>
				<th>Buyer</th>
				<th>Retail Price</th>
				<th>Tax</th>
				<th>Date</th>
				<th>Type</th>
				<th>Options</th>
			</tr>
			<?php if(empty($search_results) > 0): ?>
				<tr>
					<td colspan='8'>No Results Found</td>
				</tr>
			<?php else: ?>
				<?php foreach($search_results as $row):?>
					<tr>
						<td><?php echo anchor('sales/invoice/' . $row['invoice_id'], $row['invoice_id']); ?></td>
						<td><?php echo $row['sales_slip_number']; ?></td>
						<?php if($row['buyer_type'] == 1 || $row['buyer_type'] == 3): ?>
							<td><?php echo anchor('customer/edit/' . $row['buyer_id'], $row['buyer_name']); ?></td>
						<?php else:?>
							<td><?php echo anchor('vendor/edit/' . $row['buyer_id'], $row['buyer_name']); ?></td>
						<?php endif;?>
						<td>$<?php echo number_format($row['total_price'],2); ?></td>
						<td>$<?php echo number_format($row['tax'],2); ?></td>
						<td><?php echo $row['sale_date']; ?></td>
						<td><?php echo $invoice_status[$row['invoice_status']]['name'] . ' ' . $invoice_types[$row['invoice_type']]['name']?></td>
						<td><?php echo anchor('sales/invoice/' . $row['invoice_id'], 'View This ' . $invoice_types[$row['invoice_type']]['name']); ?></td>
					</tr>
				<?php endforeach;?>
			<?php endif;?>
		</table>
		<p>Sales and Invoices Section of Project Mango</p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>