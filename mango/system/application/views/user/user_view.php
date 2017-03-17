<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title>Mango - User Options</title>
	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2 class='admin_h2'>User Options</h2>
		<h3 class='admin_h3'>Change User Profile settings</h3>
		<ul class="admin_ul">
			<li><?php echo anchor('user/change_name', snappy_image('icons/textfield_rename.png') . ' Change Name'); ?></li>
			<li><?php echo anchor('user/change_email', snappy_image('icons/email_edit.png') . ' Change Email'); ?></li>
			<li><?php echo anchor('user/change_password', snappy_image('icons/lock_edit.png') . ' Change Password'); ?></li>
			<li><?php echo anchor('user/upload_signature', snappy_image('icons/text_signature.png') . ' Upload Signature'); ?></li>
			<li><?php echo anchor('user/change_credentials', snappy_image('icons/award_star_silver_1.png') . ' Change Credentials'); ?></li>
		</ul>
		<p>User Options Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>