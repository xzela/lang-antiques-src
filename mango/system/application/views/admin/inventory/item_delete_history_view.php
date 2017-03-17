<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - Delete History</title>
	<style type="text/css">
	
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Delete History</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>
		</ul>
		<table class='inventory_list_table'>
			<tr>
				<th>Item Number</th>
				<th>Item Name</th>
				<th>User</th>
				<th>Reason</th>
				<th>Date</th>
			</tr>
			<?php foreach($history as $row): ?>
			<tr>
				<td><?php echo $row['item_number']; ?></td>
				<td><?php echo $row['item_name']; ?></td>
				<td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
				<td><?php echo $row['delete_reason']; ?></td>
				<td><?php echo $row['delete_date']; ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>