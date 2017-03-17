<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Inventory</title>
	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Inventory</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/add', snappy_image('icons/page_add.png') . ' Create New Item'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/list_all_items', 'List Everything'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/list_online_items', 'List Active Online Items'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/list_sold_items', 'List Sold Items'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/list_non_sold_items_with_images', 'List Non-Sold Items with Web Images'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/list_non_sold_items_without_images', 'List Non-Sold Items without Web Images'); ?></li>
		</ul>
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>