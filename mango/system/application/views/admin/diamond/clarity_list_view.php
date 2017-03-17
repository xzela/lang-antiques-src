<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - List Of Clarities </title>

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
		<h2 class='item'>Admin - List of Clarities</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . 'Back to Admin Main'); ?></li>
			<li>|</li>
			<li><?php echo anchor('admin/diamond_clarity_add', 'Add new Clarity'); ?></li>
		</ul>
		<p>Here is a list of all the known Clarities .</p>
		<table class='customer_table'>
			<tr>
				<th>Clarity ID</th>
				<th>Clarity Abrv.</th>
				<th>Clarity Name</th>
				<th>Active</th>
				<th>Sequence</th>
				<th>Options</th>
			</tr>
			<?php foreach($clarities as $clarity):?>
				<tr>
					<td><?php echo $clarity['clarity_id']; ?></td>
					<td><?php echo $clarity['clarity_abrv']; ?></td>
					<td><?php echo anchor('admin/diamond_clarity_edit/' . $clarity['clarity_id'], $clarity['clarity_name']); ?></td>
					<td><?php echo $yesno[$clarity['active']]; ?></td>
					<td><?php echo $clarity['seq']; ?></td>
					<td ><?php echo anchor('admin/diamond_clarity_edit/' . $clarity['clarity_id'], 'Edit Clarity'); ?></td>
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