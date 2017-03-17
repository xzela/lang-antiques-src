<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options</title>
	<style type="text/css">
	.div_form {
		border: 1px solid #666;
		padding: 5px;
		margin: 5px;
		width: 500px;
	}
	label {
		display: block;
		font-weight: bold;
		padding-right: 20px;
		margin-right: 20px; 
	}
	.error {
		padding: 5px;
		margin: 2px;
		background-color: #ffe1e1;
	}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Delete an Inventory Item - Confirmation!</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>

		</ul>
	
		<div class='error'>
			<h3 class='warning'>Are you sure you want to delete this item '<?php echo $item['item_number']; ?>'? This is your last chance to chicken out...</h3>
			<p class='warning'>Deleting an item <strong>cannot</strong> be undone. If you delete an item by mistake you must reenter it yourself! </p>
		</div>
		<?php echo form_open('admin/confirm_delete_item'); ?>
			<div class='div_form'>
				<label>Name:</label>
				<span><?php echo $item['item_name']; ?></span>
				<br />
				<label>Description:</label>
				<span><?php echo $item['item_description']; ?></span>
				<label>Reason for Delete:</label>
				<span><textarea name='delete_reason' cols='55' rows='4'><?php echo set_value('delete_reason'); ?></textarea></span>
				<br />
				<?php echo validation_errors();  ?>
				<br />
				<input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>" />
				<input type="submit" name="submit_delete" value="Delete this Item" /> | <?php echo anchor('admin', ' Cancel'); ?>
			</div>
		<?php echo form_close(); ?>		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>