<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Inventory Image View</title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Inventory Image View</h2>
		<ul id='submenu'>
			<li><a href='javascript:history.go(-1);'> <?php echo snappy_image('icons/resultset_previous.png', '', 'pagination_image'); ?> Back to Item</a></li>
		</ul>
		<h2>Image: </h2>
		<div class='thumb_images_div'>
			<img src='<?php echo $image_data['image_location']; ?>' />
		</div>
		
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>