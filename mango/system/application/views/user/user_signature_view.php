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
		<h2>User Options</h2>
		<ul id='submenu'>
			<li><?php echo anchor('user', '<< Back to User Options'); ?></li>
		</ul>
		<p>Change your Creds here... 'nough said </p>
		<?php echo form_open_multipart('user/upload_signature/true'); ?>
			<table class="user_table">
				<tr> 
					<td class="title">Signature:</td>
					<td><input name="signature" type="file" /></td>
				</tr>
				<tr>
					<td class='title'>Current Signature:</td>
					<td>
						<?php if($signature['has_signature']): ?>
							<img src='<?php echo $signature['image_location']; ?>' />
						<?php else: ?>
							No Signature Yet...
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td colspan='2'>
						<?php echo $message; ?>				
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<?php echo form_submit('user/upload_signature/true', 'Submit'); ?> 
						<?php echo anchor('user/', 'Cancel'); ?>
					</td>
				</tr>
			</table>
		<?php echo form_close(); ?>	
		<p>User Options Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>