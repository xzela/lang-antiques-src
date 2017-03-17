<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - Customer Store Credit Delete History</title>
	<style type="text/css">
	
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Customer Store Credit Delete History</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>
		</ul>
		<table class='customer_table'>
			<tr>
				<th>Delete ID</th>
				<th>User</th>
				<th>Customer ID</th>
				<th>Credit Amount</th>
				<th>Item Description</th>
				<th>Delete Reason</th>
				<th>Date</th>
			</tr>
			<?php if(sizeof($history) > 0 ): ?>
				<?php foreach($history as $row): ?>
				<tr>
					<td><?php echo $row['delete_id']; ?></td>
					<td><?php echo $row['user_name']; ?></td>
					<td><?php echo $row['customer_id']; ?></td>
					<td>$<?php echo number_format($row['credit_amount'],2); ?></td>
					<td><?php echo $row['item_description']; ?></td>
					<td><?php echo $row['reason']; ?></td>
					<td><?php echo $row['date']; ?></td>
				</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan='7' style='text-align: center;'>No Delete History Found, Good?</td>
				</tr>
			<?php endif;?>
		</table>
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>