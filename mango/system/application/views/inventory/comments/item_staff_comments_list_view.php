<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Inventory - Staff Comments List</title>
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
		<h2>Staff Comments for: <?php echo $item_data['item_number']; ?> - <?php echo $item_data['item_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/comment_add/' . $item_data['item_id'], 'Add Comment'); ?></li>
		</ul>
		
		<table class="customer_table">
			<tr>
				<th nowrap width='100px' >Comment ID</th>
				<th nowrap width='170px' >Name</th>
				<th nowrap width='170px'>Date</th>
				<th>Comment</th>
				<th nowrap width='150px'>Option</th>
			</tr>
		<?php foreach($comment_data as $comment): ?>
			<tr>
				<td><?php echo $comment['comment_id']; ?></td>
				<td><?php echo $comment['staff']['first_name'] . ' ' . $comment['staff']['last_name']; ?></td>
				<td nowrap><?php echo $comment['comment_date']; ?></td>
				<td><?php echo $comment['comment']; ?></td>
				<td class='end'>
					<?php echo form_open('inventory/comment_delete/staff'); ?>
						<input type="hidden" name="comment_id" value='<?php echo $comment['comment_id']; ?>' />
						<input type="hidden" name="item_id" value='<?php echo $comment['item_id']; ?>' />
						<input type='submit' value='Remove Comment' />
					<?php echo form_close();?>
				</td>
			</tr>
		<?php endforeach ?>
		
		
		</table>		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>