<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - Change User Privileges</title>
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
	.titles {
		font-weight: bold;
	}
	</style>	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>User Options - Change User Privileges</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>
		</ul>
		<p>Here is a list of all the known users in Clangity, select the Deactivate link to render them useless.</p>
		<?php $active_text = array(0=> 'Deactivated', 1=> 'Activated'); ?>
		<?php foreach($users as $user): ?>
			<div class="user_row" id="user_<?php echo $user['user_id']; ?>">
				<span><span class='titles'>Status:</span> <?php echo $active_text[$user['active']]; ?></span>
				<div style='padding: 5px;'><span class='titles'>Privilege:</span>
					<div id='privilege_div_<?php echo $user['user_id']; ?>' class='editable_field' ><?php echo $user_types[$user['user_type']]; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceCollectionEditor('privilege_div_<?php echo $user['user_id']; ?>', 
							base_url + 'user/AJAX_update_user_privileges/<?php echo $user['user_id']; ?>/', {
								//look into loadCollectionURL for this 
								collection: [[1, 'Basic User'], [4, 'Photo User'], [5, 'Power User'], [9, 'Administrator']],
								onComplete: function(transport) {
									//alert(transport);
									var text = document.getElementById('privilege_div_<?php echo $user['user_id']; ?>');
									var collect = new Object();
									//hardcoded stuff (too lazy to fix this right now);
									//look into loadCollectionURL
									collect['1'] = 'Basic User';
									collect['4'] = 'Photo User';
									collect['5'] = 'Power User';
									collect['9'] = 'Administrator';
									
									text.innerHTML = collect[text.innerHTML];
								},
								ajaxOptions: {method: 'post'}
							});
					</script>				
				</div>
				<div>
					<span class='titles'>Username:</span> <?php echo $user['login_name']; ?> <br />
					<span class='titles'>Name:</span> <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
				</div>
				
				<span><span class='titles'>Email: </span><?php echo mailto($user['email']); ?></span>
			</div>
		<?php endforeach; ?>
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>