<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options</title>
	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>New User Added to the Database</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>
		</ul>		
		<p>Congratz! You have successfully created a new user for the Mango Database</p>
		<p>Quickly, write down this information:</p>
		<p>Username: <b><?php echo $login_name;?></b></p>
		<p>Password: *******************</p>
		<p class="warning">Remember your password. There is no way for us to retrieve your password if you ever forget it. You will have to reset it.</p>
		<?php echo anchor('admin/new_user', 'Add another User?'); ?>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>