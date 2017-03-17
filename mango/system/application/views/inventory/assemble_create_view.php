<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Inventory - Create Assembled Item - <?php echo $item_data['item_number']; ?></title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Create an Assembled Item - <?php echo $item_data['item_number'];?> : <?php echo $item_data['item_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
		</ul>
		<h3>Are you sure?</h3>
		<p>
			Ok, here's the deal: 
			To create an assembled item, one item needs to be the parent item 
			By clicking the button below, you will make this item (<?php echo $item_data['item_number']; ?>) the parent item which all other child items will be assembled into. 	
		</p>
		<div class=''>
		<?php echo form_open('inventory/assemble_create/' . $item_data['item_id']);?>
			<input type='hidden' name='create_assemble' value='true' />
			<input type='submit' name='submit_button' value='Yes, This is the Parent'/>
		<?php echo form_close();?>
		</div>
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>
</body>
</html>