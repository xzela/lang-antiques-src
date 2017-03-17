<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - Add Cut</title>
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
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
		<h2 class='item'>Admin - Add Cut</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin/cuts_list', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Cuts List'); ?></li>
		</ul>
		<p>Here, add a new Cut/Shape</p>
		<?php echo form_open('admin/cuts_add');?>
		<table class='form_table'>
			<tr>
				<td class='title'>Cut/Shape Name: </td>
				<td><input type='text' name='cut_name' value='<?php echo set_value('cut_name'); ?>'/></td>
			</tr>
			<tr>
				<td class='title'>Sequence: </td>
				<td><input type='text' name='seq' value='<?php echo set_value('seq', '99'); ?>' /></td>
			</tr>			
			<tr>
				<td colspan='2'><?php echo validation_errors(); ?></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type='submit' value='Add Cut/Shape' /> 
					|
					<?php echo anchor('admin/cuts_list', 'Cancel');?>
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