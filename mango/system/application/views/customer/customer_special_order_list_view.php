<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Customer Special Orders: <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?></title>
	<script type="text/javascript">
		base_url = '<?php echo base_url(); ?>/';
	</script>	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Customer Special Orders: <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?> </h2>
		<ul id="submenu">
			<li><?php echo anchor('customer/edit/' . $customer['customer_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Customer'); ?></li>
			<li>|</li>
			<li><?php echo anchor('customer/add_special_order/' . $customer['customer_id'], 'Create Special Order'); ?></li>
			<li>|</li>
		</ul>
		<h3 class='section' >Customer Special Orders: <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?></h3>
		<table class='customer_table'>
			<tr>
				<th nowrap>Order ID</th>
				<th nowrap>Order Description</th>
				<th nowrap>Company</th>
				<th nowrap>Order Date</th>
				<th nowrap>Invoice</th>
				<th nowrap>Status</th>
				<th nowrap>Options</th>
			</tr>
			<?php if(sizeof($special_orders) > 0): ?>
				<?php foreach($special_orders as $order): ?>
					<tr>
						<td><?php echo $order['order_id']?></td>
						<td><?php echo $order['order_description']; ?> </td>
						<td><?php echo $order['company_name']; ?></td>
						<td><?php echo $order['order_date']; ?> </td>
						<td><?php echo $order['invoice_id']; ?></td>
						<td><?php echo $order['order_status']; ?></td>
						<td>
							<?php echo anchor('customer/edit_special_order/' . $order['customer_id'] . '/' . $order['order_id'], 'Edit Order'); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan='5' >No Jobs Found</td>
				</tr>
			<?php endif; ?>
		</table>
		<p>Customer Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>