<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - Delete Return</title>
	<style type="text/css">
		.div_form {
			border: 1px solid #666;
			padding: 10px;
			margin: 5px;
			width: 400px;
		}
		.div_form label {
			font-weight: bold;
		}
		.div_form input {
		}
		.error {
			padding: 5px;
			margin: 2px;
			background-color: #ffe1e1;
		}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Delete Return</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>

		</ul>
		<div class='error'>
			<h3 class='warning'>Warning: Deleting an return is serious procedure. Delete at ones own risk!</h3>
			<p class='warning'>Deleting an return <strong>cannot</strong> be undone. If you delete the wrong return by mistake you must reenter it all by <strong>yourself</strong>! </p>
		</div>
		<?php echo form_open('admin/delete_return'); ?>
			<div class='div_form'>
				<label for='return_id'>Return ID:</label>
				<input name="return_id" type="text" size='25'/>
				<input class='button' name="delete_return" type="submit" value="Delete Return" />
				<br />
				<?php echo validation_errors();  ?>
			</div>
		<?php echo form_close(); ?>
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>