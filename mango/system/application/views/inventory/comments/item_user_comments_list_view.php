<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Inventory Manage Comments</title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Inventory Manage Comments</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
		</ul>
		<table class="customer_table">
			<tr>
				<th nowrap width='100px' >Comment ID</th>
				<th nowrap width='170px' >Name</th>
				<th nowrap width='200px'>Email</th>
				<th nowrap width='100px'>IP</th>
				<th nowrap width='170px'>Date</th>
				<th>Comment</th>
				<th nowrap width='150px'>Option</th>
			</tr>
		<?php foreach($comment_data as $comment): ?>
			<tr>
				<td><?php echo $comment['comment_id']; ?></td>
				<td><?php echo $comment['name']; ?></td>
				<td><?php echo $comment['email']; ?></td>
				<td><?php echo $comment['ip']; ?></td>
				<td nowrap><?php echo $comment['date']; ?></td>
				<td><?php echo $comment['comment']; ?></td>
				<td class='end'><?php echo anchor('inventory/comments/'. $item_data['item_id'] . '/delete/' . $comment['comment_id'], snappy_image('icons/comment_delete.png') . ' Delete Comment'); ?></td>
			</tr>
		<?php endforeach ?>
		
		
		</table>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>