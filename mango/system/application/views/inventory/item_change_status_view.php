<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Inventory Item Edit History</title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Inventory Item - Change Status</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
		</ul>
		<ul>
			<li><?php echo anchor('inventory/change_status/' . $item_data['item_id'] . '/0', snappy_image('icons/money_dollar.png') . 'Mark As Sold'); ?></li>
			<li><?php echo anchor('inventory/change_status/' . $item_data['item_id'] . '/1', snappy_image('icons/tick.png') . 'Mark As Available'); ?></li>
			<li><?php echo anchor('inventory/change_status/' . $item_data['item_id'] . '/2', snappy_image('icons/flag_red.png') . 'Mark As Out on Job'); ?></li>
			<li><?php echo anchor('inventory/change_status/' . $item_data['item_id'] . '/3', snappy_image('icons/flag_yellow.png') . 'Mark As Pending Sale'); ?></li>
			<li><?php echo anchor('inventory/change_status/' . $item_data['item_id'] . '/4', snappy_image('icons/flag_blue.png') . 'Mark As Out on Memo'); ?></li>
			<li><?php echo anchor('inventory/change_status/' . $item_data['item_id'] . '/5', snappy_image('icons/bomb.png') . 'Mark As Burgled'); ?></li>
			<li><?php echo anchor('inventory/change_status/' . $item_data['item_id'] . '/6', snappy_image('icons/cog.png') . 'Mark As Assembled'); ?></li>
			<li><?php echo anchor('inventory/change_status/' . $item_data['item_id'] . '/7', snappy_image('icons/package_go.png') . 'Mark As Returned to Consignment'); ?></li>
			<li><?php echo anchor('inventory/change_status/' . $item_data['item_id'] . '/8', snappy_image('icons/flag_red.png') . 'Mark Pending Repair'); ?></li>
			<li><?php echo anchor('inventory/change_status/' . $item_data['item_id'] . '/91', snappy_image('icons/table_relationship.png') . 'Mark As Frances Klein Import'); ?></li>
			<li><?php echo anchor('inventory/change_status/' . $item_data['item_id'] . '/98', snappy_image('icons/flag_red.png') . 'Never Going Online'); ?></li>
			<li><?php echo anchor('inventory/change_status/' . $item_data['item_id'] . '/99', snappy_image('icons/cross.png') . 'Mark As Unavailable'); ?></li>
		</ul>
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>