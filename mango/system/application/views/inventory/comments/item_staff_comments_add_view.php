<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Inventory - Staff Comments Add</title>
	<script type='text/javascript'>		
	</script>
	<style type='text/css' >
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Add A Staff Comments to: <?php echo $item_data['item_number']; ?> - <?php echo $item_data['item_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/comments/' . $item_data['item_id'] . '/staff', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Comments'); ?></li>
			<li>|</li>
		</ul>
		<div><?php echo validation_errors(); ?></div>
		<?php echo form_open('inventory/comment_add/' . $item_data['item_id']); ?>
		<table class="form_table">
			<tr>
				<td class='title'>Commenter:</td>
				<td>
					<?php echo $user_data['user_name']?>
					<input type="hidden" name="item_id" value="<?php echo $item_data['item_id']; ?>" />
					<input type="hidden" name="comment_type" value="staff" />
					<input type="hidden" name="staff_id" value="<?php echo $user_data['user_id']?>" />
				</td>
			</tr>
			<tr>
				<td class='title'>Comment:</td>
				<td><textarea name="comment" rows='4' cols='40'><?php echo set_value("comment")?></textarea></td>
			</tr>
			<tr>
				<td class='title'></td>
				<td>
					<input type='submit' value='Add Comment' /> || <?php echo anchor('inventory/comments/' . $item_data['item_id'] . '/staff', 'Cancel')?>
				</td>
			</tr>
		</table>
		<?php echo form_close(); ?>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>