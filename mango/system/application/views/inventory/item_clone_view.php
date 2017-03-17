<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Inventory - Clone Item</title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Inventory - Clone Item</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
		</ul>
		<h3 class='warning'>Warning: Cloning an item is serious procedure. Clone at ones own risk!</h3>
		<p class='warning'>
			Cloning an item takes a long time and can cause your computer to slow down. 
			Whatever you do, do not close your browser or shutdown your computer. 
			Please wait for the cloning process to complete before continuing on.
		</p>
		<p>You are about to clone item <?php echo $item_data['item_number']; ?>. Are you sure you wish to clone this item? </p>
		<?php echo form_open('inventory/clone_item/' . $item_data['item_id']); ?>
			<input type='submit' name='submit_clone' value='Yes, Clone Item' /> | <?php echo anchor('inventory/edit/' . $item_data['item_id'], 'Cancel'); ?>
		<?php echo form_close();?>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>