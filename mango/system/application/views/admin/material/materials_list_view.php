<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - List All Materials </title>

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
		<h2 class='item'>Admin - List All Materials</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>
			<li>|</li>
			<li><?php echo anchor('admin/material_add', 'Create New Material'); ?></li>
			<li>|</li>			
		</ul>
		<p>Here is a list of all the known Materials.</p>
		<table class='customer_table'>
			<tr>
				<th>ID</th>
				<th nowrap>Material Name</th>
				<th>Count of Items</th>
				<th>Active</th>
				<th>Options</th>
			</tr>
			<?php foreach($materials as $material):?>
				<tr>
					<td><?php echo $material['material_id']; ?></td>
					<td ><?php echo anchor('admin/material_edit/' . $material['material_id'], $material['material_name']); ?></td>
					<td><?php echo $material['count']; ?></td>
					<td><?php echo $yesno[$material['active']]; ?></td>
					<td><?php echo anchor('admin/material_edit/' . $material['material_id'], 'Edit Material'); ?></td>
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