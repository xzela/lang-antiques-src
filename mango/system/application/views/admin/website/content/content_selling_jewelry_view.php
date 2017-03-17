<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - Edit Selling Jewelry Content</title>

	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery-ui-1.7.2.js'); ?>

	<script type="text/javascript">
	var base_url = <?php echo "'" . base_url() . "'"; ?>;
	$(document).ready(function() {
	});	
	</script>
	<style type='text/css'>
	
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2 class='item'>Admin - Edit Selling Jewelry  Content</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin'); ?></li>
			<li>|</li>
		</ul>
		<p>
			Edit the Our Store Content
		</p>
		<?php echo form_open('admin/content_selling_jewelry'); ?>
			<table class="form_table">
				<tr>
					<td class='title'>Content: </td>
					<td>
						<textarea name='content' cols="100" rows="20"><?php echo set_value('content', $content);?></textarea>
					</td>
				</tr>
				<tr>
					<td class='title'>Option:</td>
					<td><input type='submit' value='Save' /></td>
				</tr>
			</table>
		<?php echo form_close();?>
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>