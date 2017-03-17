<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - List Menu Elements </title>

	<script type="text/javascript">
	</script>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2 class='item'>Admin - List Menu Elements </h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>
			<li>|</li>
			<li><?php echo anchor('admin/menu_add', 'Add new Menu Element'); ?></li>
			<li>|</li>			
			<li><?php echo anchor('admin/menu_reorder', 'Reorder Menu Elements'); ?></li>
			<li>|</li>			
		</ul>
		<p>Here is a list of all the known Menu Elements.</p>
		<h3>Main Menu</h3>
		<table class='customer_table'>
			<tr>
				<th>ID</th>
				<th>Element Name</th>
				<th>Element Link</th>
				<th>Seq</th>
				<th>Options</th>
			</tr>
			<?php foreach($main_menu as $element):?>
				<tr>
					<td><?php echo $element['element_id']; ?></td>
					<td><?php echo $element['element_name']; ?></td>
					<td><?php echo $element['element_url']; ?></td>
					<td><?php echo $element['element_seq']; ?></td>
					<td>[<?php echo anchor('admin/menu_edit/' . $element['element_id'], 'Edit Menu Item'); ?>]</td>
				</tr>
			<?php endforeach;?>
		</table>
		
		<h3>Jewelry Periods</h3>
		<table class='customer_table'>
			<tr>
				<th>ID</th>
				<th>Element Name</th>
				<th>Element Link</th>
				<th>Seq</th>
				<th>Options</th>
			</tr>
			<?php foreach($secondary_menu as $element):?>
				<tr>
					<td><?php echo $element['element_id']; ?></td>
					<td><?php echo $element['element_name']; ?></td>
					<td><?php echo $element['element_url']; ?></td>
					<td><?php echo $element['element_seq']; ?></td>
					<td>[<?php echo anchor('admin/menu_edit/' . $element['element_id'], 'Edit Menu Item'); ?>]</td>
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