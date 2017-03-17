<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Exception Caught</title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Exception Caught:</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/upload_' . $type . '_image/' . $item_id , snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Images');?></li>
		</ul>
		<h2>Couldn't Remove the Image for one reason or another.</h2>
		<p>
			There was a problem with removing this image. 
			This means the image or file has a read/write issue.
			In most cases the file is simply missing. 
		</p>
		<h3>File Information:</h3>
		<p>
			file path: <strong><?php echo $error_data['image_location'];?></strong>
			<br />
			Please check the above path and verify the files integrity.
		</p>
		<h2>OR....</h2>
		<p>Or, You can Force Remove this Record.</p>
		<?php echo form_open('inventory/remove_image_force'); ?>
			<input type='hidden' name='item_id' value='<?php echo $item_id; ?>' />
			<input type='hidden' name='image_id' value='<?php echo $image_id; ?>' />
			<input type='hidden' name='image_type' value='<?php echo $type; ?>' />
			<input type='submit' value='Force Remove Record' />
		<?php echo form_close();?>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>