<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Inventory Item Not Found</title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Inventory Item Not Found</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . 'Back to Inventory Main '); ?></li>
		</ul>
		<h2>The item could not be found. Check your URL or something </h2>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>