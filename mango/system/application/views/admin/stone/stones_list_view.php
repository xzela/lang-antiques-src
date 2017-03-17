<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - List Known Stone </title>

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
		<h2 class='item'>Admin - List Know Gemstones</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . 'Back to Admin Main'); ?></li>
			<li>|</li>
			<li><?php echo anchor('admin/stone_add', 'Create New Stone'); ?></li>
		</ul>
		<p>Here is a list of all the known Stones.</p>
		<table class='customer_table'>
			<tr>
				<th>Gem ID</th>
				<th>Gem Name</th>
				<th>Template Type</th>
				<th>Active</th>
				<th>Sequence</th>
				<th>Gem Count</th>
				<th>Options</th>
			</tr>
			<?php foreach($stones as $stone):?>
				<tr>
					<td><?php echo $stone['stone_id']; ?></td>
					<td><?php echo anchor('admin/stone_edit/' . $stone['stone_id'], $stone['stone_name']); ?></td>
					<td><?php echo $stone['type']; ?></td>
					<td><?php echo $yesno[$stone['active']]; ?></td>
					<td><?php echo $stone['stone_seq']; ?></td>
					<td><?php echo $stone['stone_count']; ?></td>
					<td ><?php echo anchor('admin/stone_edit/' . $stone['stone_id'], 'Edit Stone'); ?></td>
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