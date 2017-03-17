<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Inventory Item Edit History</title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Inventory Item Edit History</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_id, snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
		</ul>
		<?php 
		?>
		<table class="customer_table">
			<tr>
				<th nowrap width='50px'>Audit ID</th>
				<th nowrap >User</th>
				<th nowrap >Timestamp</th>
				<th nowrap >Field</th>
				<th>Old value</th>
				<th>New Value</th>
			</tr>
		<?php if(sizeof($history_data) > 0):?>
			<?php foreach($history_data['history'] as $history): ?>
				<tr>
					<td><?php echo $history['audit_id']; ?></td>
					<?php if($history['user_id'] == 0 ): ?>
						<td nowrap><b>System Level Change</b></td>
					<?php else: ?>
						<td nowrap><?php echo mailto($entered_user_data[$history['user_id']]['email'], $entered_user_data[$history['user_id']]['first_name'] . ' ' . $entered_user_data[$history['user_id']]['last_name']); ?></td>
					<?php endif; ?>
					<td nowrap><?php echo date("Y.m.d \a\\t h:ia", strtotime($history['date_changed'])); ?></td>
					<td><?php echo $history['field_name']; ?></td>
					<td><?php echo $history['old_value']; ?></td>
					<td class='end'><?php echo $history['new_value']; ?></td>
				</tr>
			<?php endforeach ?>
		<?php else:?>
			<tr>
				<td colspan='6' class='warning' style='text-align: center;'>No Historical data found</td>
			</tr>
		<?php endif;?>
		
		</table>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>