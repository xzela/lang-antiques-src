<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - List All Minor Classes </title>

	<script type="text/javascript">
	base_url = '<?php echo base_url(); ?>index.php/';
	</script>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2 class='item'>Admin - List All Minor Classes</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>
			<li>|</li>
			<li><?php echo anchor('admin/minor_class_add', 'Create New Minor Class'); ?></li>
			<li>|</li>			
		</ul>
		<p>Here is a list of all the known Minor Classes.</p>
		<table class='customer_table'>
			<tr>
				<th>ID</th>
				<th nowrap>Minor Class Name</th>
				<th>Count of Items</th>
				<th>Active</th>
				<th>Options</th>
			</tr>
			<?php foreach($minor_classes as $class):?>
				<tr>
					<td><?php echo $class['minor_class_id']; ?></td>
					<td ><?php echo anchor('admin/minor_class_edit/' . $class['minor_class_id'], $class['minor_class_name']); ?></td>
					<td><?php echo $class['count']; ?></td>
					<td><?php echo $yesno[$class['active']]; ?></td>
					<td><?php echo anchor('admin/minor_class_edit/' . $class['minor_class_id'], 'Edit Minor Class'); ?></td>
				</tr>
			<?php endforeach;?>
		</table>
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>