<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - Add Major Class</title>
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	
	<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';

	$(document).ready(function() {
		var element = $('#element_url_name');
		$('#major_class_name').bind('keyup', function() {
			var m = $(this).val();
			var n = m.replace(/[^a-zA-Z0-9]+/g,'-');
			element.val(n);
		});
	});
	</script>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2 class='item'>Admin - Add Major Class</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin/major_class_list', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Major Class List'); ?></li>
		</ul>
		<p>Here, add a new Major Class</p>
		<?php echo form_open('admin/major_class_add');?>
		<table class='form_table'>
			<tr>
				<td class='title'><span class='warning'>*</span>Major Class ID: </td>
				<td><input type='text' name='major_class_id' value='<?php echo set_value('major_class_id');?>'/></td>
			</tr>
			<tr>
				<td class='title'><span class='warning'>*</span>Major Class Name: </td>
				<td><input id='major_class_name' type='text' name='major_class_name' value='<?php echo set_value('major_class_name'); ?>'/></td>
			</tr>
			<tr>
				<td class='title'><span class='warning'>*</span>Web Name: </td>
				<td>
					<input id='element_url_name' type='text' name='element_url_name' value='<?php echo set_value('element_url_name'); ?>'/>
					<br />
					<span style='color: #a1a1a1;'>This field can only contain numbers, letters, and dashes.</span>					
				</td>
			</tr>
			<tr>
				<td colspan='2'><?php echo validation_errors(); ?></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type='submit' value='Add Major Class' /> 
					|
					<?php echo anchor('admin/major_class_list', 'Cancel');?>
				</td>
			</tr>
		</table>
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>