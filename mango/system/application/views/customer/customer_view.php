<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Customers</title>
	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Customers</h2>
		<ul id="submenu">
			<li><?php echo anchor('customer/add', snappy_image('icons/user_add.png') . 'Create New Customer'); ?></li>
			<li>|</li>
			<li><?php echo anchor('customer/list_customers', 'List All Customers'); ?></li>
			<li>|</li>
			<li><?php echo anchor('customer/search_customers', snappy_image('icons/find.png') .  'Search For a Customer'); ?></li>
			<li>|</li>
			<li><?php echo anchor('customer/generate_mailing_list', 'Generate Mailing List'); ?></li>
		</ul>
		<p>Customer Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>