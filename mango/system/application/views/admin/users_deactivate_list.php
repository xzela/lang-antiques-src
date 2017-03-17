<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - Deactivate User</title>
	<?php echo snappy_script('ajax/prototype.js'); ?>
	<?php echo snappy_script('ajax/scriptaculous.js'); ?>
	<?php echo snappy_script('ajax/controls.js'); ?>
	<?php echo snappy_script('ajax/effects.js'); ?>

	<?php echo snappy_script('admin/users.js'); ?>
	<script type="text/javascript">
	base_url = '<?php echo base_url(); ?>index.php/';
	</script>

	<style>
	.user_row {
		padding: 5px;
		margin: 5px;
		border: 1px solid #666;
	}
	.user_option {
		text-align: right;
	}
	</style>	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>User Options - Deactivate User</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>
		</ul>
		<p>Here is a list of all the known users in Clangity, select the Deactivate link to render them useless.</p>
		<?php $active_text = array(0=> 'Deactivated', 1=> 'Activated'); ?>
		<?php foreach($users as $user): ?>
			<div class="user_row" id="user_<?php echo $user['user_id']; ?>">
				<span>Status: <?php echo $active_text[$user['active']]; ?></span>
				<div>
					Username: <?php echo $user['login_name']; ?> <br />
					Name: <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
				</div>
				<span><?php echo mailto($user['email']); ?></span>
				<div class="user_option">
					<a class="red" href="javascript:deactivateUser(<?php echo $user['user_id']; ?>)">Deactivate User</a>
				</div>
			</div>
		<?php endforeach; ?>
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>