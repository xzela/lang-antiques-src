<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - List Of Colors </title>

	<script type="text/javascript">
	base_url = '<?php echo base_url(); ?>';
	</script>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2 class='item'>Admin - List of Colors</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . 'Back to Admin Main'); ?></li>
			<li>|</li>
			<li><?php echo anchor('admin/diamond_color_add', 'Add New Color'); ?></li>
		</ul>
		<p>Here is a list of all the known Colors .</p>
		<table class='customer_table'>
			<tr>
				<th>Color ID</th>
				<th>Color Abrv.</th>
				<th>Color Description</th>
				<th>Active</th>
				<th>Sequence</th>
				<th>Options</th>
			</tr>
			<?php foreach($colors as $color):?>
				<tr>
					<td><?php echo $color['color_id']; ?></td>
					<td><?php echo $color['color_abrv']; ?></td>
					<?php if($color['color_id'] == 24): //special case ?>
						<td ><?php echo $color['color_description']; ?></td>
					<?php else: ?>
						<td><?php echo anchor('admin/diamond_color_edit/' . $color['color_id'], $color['color_description']); ?></td>
					<?php endif;?>
					<td><?php echo $yesno[$color['active']]; ?></td>
					<td><?php echo $color['seq']; ?></td>
					<?php if($color['color_id'] == 24): //special case ?>
						<td >Cannot Edit</td>
					<?php else: ?>
						<td ><?php echo anchor('admin/diamond_color_edit/' . $color['color_id'], 'Edit Color'); ?></td>
					<?php endif;?>
					
				</tr>
			<?php endforeach;?>
		</table>
		<p id='page_end'>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>