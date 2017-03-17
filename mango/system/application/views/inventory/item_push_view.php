<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Push Item to Lang Antiques <?php echo $item_data['item_number']?></title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Push Item to Lang Antiques: <?php echo $item_data['item_number']?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
		</ul>
		<div class='delete_admin_item'>
			<h3 class='warning'>Are you Sure....?</h3>
			<p>
				Are you sure you want to send them item to Lang? 
				Once the item has been sent there is no way to recall the item.
				If you send the wrong item, Alison will get angry. 
				You do not want to make Alison angry...
			</p>
		</div>
		<div class='nodelete_admin_item'>
			<h3 class=''>Yes... push the item.</h3>
			<?php echo form_open('inventory/push_to_lang/' . $item_data['item_id']); ?>
				<input type='hidden' name='item_id' value='<?php echo $item_data['item_id']; ?>' />
				<input type='submit' name='push_submit' value='Push Item!' />
			<?php echo form_close();?>
		</div>
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?> </p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>