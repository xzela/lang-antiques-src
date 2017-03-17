<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_style('jquery.jhtmlarea.css'); //autoloaded ?>
	<?php //echo snappy_style('jquery.jhtmlarea.editor.css'); //autoloaded ?>
	
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery.jhtmlarea-0.7.0.js'); ?>
	

	<title><?php echo $this->config->item('project_name'); ?> - Inventory - Format Editor</title>
	<script type='text/javascript'>
		var base_url = <?php echo "'" . base_url() . "'"; ?>;
		var id = <?php echo $item_data['item_id']; ?>;
		var url = 'inventory/AJAX_updateItemField';
		$(document).ready(function() {
			$("#item_description").htmlarea({
				toolbar: ["bold", "italic", "underline", "|", "link", "unlink"]
			});	
		});
		
	</script>
	<style type='text/css' >
	.save_button {
		font-size: 18px;
		color: red; 
	}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Format Editor: <?php echo $item_data['item_number']; ?> - <?php echo $item_data['item_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
		</ul>
		<p>Remember to SAVE.</p>
		<h3>Format Editor: <?php echo $item_data['item_number']; ?> - <?php echo $item_data['item_name']; ?></h3>
		<?php echo form_open('inventory/format_editor/' . $item_data['item_id']);?>
			<input class='save_button' type='submit' name='submit_format' value='Click here to Save' />
			<textarea id='item_description' name='item_description' cols='100' rows='10'><?php echo $item_data['item_description']; ?></textarea>
			<input class='save_button' type='submit' name='submit_format' value='Click here to Save' />
		<?php echo form_close();?>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>