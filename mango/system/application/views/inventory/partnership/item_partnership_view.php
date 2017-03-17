<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Inventory Partnerships For Item: <?php echo $item_data['item_name']; ?></title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Partnerships for: <?php echo $item_data['item_number']; ?> - <?php echo $item_data['item_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/partnership_add/' . $item_data['item_id'], 'Add a Partner'); ?></li>
		</ul>
		<table class='customer_table'>
			<tr>
				<th>Partner ID</th>
				<th>Name</th>
				<th>Percentage</th>
				<th>Options</th>
			</tr>
			<?php if(sizeof($item_partnerships) > 0): ?>
				<?php foreach($item_partnerships as $partnership): ?>
				<tr>
					<td><?php echo $partnership['partnership_id']; ?></td>
					<td><?php echo anchor('vendor/edit/' . $partnership['partner_id'], $partnership['partner_data']['name']); ?></td>
					<td><?php echo $partnership['percentage']; ?>%</td>
					<td><?php echo anchor('inventory/partnership_edit/' . $partnership['partnership_id'], 'Edit Partnership'); ?></td>
				</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan='3'>No Partnerships Found...</td>
				</tr>
			<?php endif;?>
			<tr>
				<td colspan='2' style='text-align: right;'><strong><?php echo $company_data['company_name']; ?> Owns:</strong></td>
				<td><?php echo number_format($company_ownership,2); ?>%</td>
				<td></td>
			</tr>
		</table>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>