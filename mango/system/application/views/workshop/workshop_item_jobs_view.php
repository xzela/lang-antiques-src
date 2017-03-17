<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Workshop - View Jobs For Item: <?php echo $item_data['item_name']; ?></title>
	<script type="text/javascript">
		base_url = '<?php echo base_url(); ?>/';
	</script>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Workshop - View Jobs For Item: <?php echo $item_data['item_number']; ?> - <?php echo $item_data['item_name']; ?> </h2>
		<ul id="submenu">
			<li><?php echo anchor('workshop/', 'Workshop Main'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], 'View Item'); ?></li>
			<li>|</li>
			<?php if($item_data['item_status'] != 8): //in pending repair queue ?>
				<li><?php echo anchor('workshop/add_item_job/' . $item_data['item_id'], 'Add New Job'); ?></li>
				<li>|</li>
				<li><?php echo anchor('workshop/pending_job_add/' . $item_data['item_id'], 'Send to Repair Queue'); ?></li>
				<li>|</li>
			<?php endif;?>
		</ul>
		<h3>Inventory Jobs: <?php echo $item_data['item_name']; ?> </h3>
		<?php if($item_data['item_status'] == 8): //in pending repair queue ?>
			<div class="delete_admin_item">
				<h3>This item is in the Pending Repair queue!</h3>
				<p>
					You can't send it to a workshop or the repair queue.
					You have to assign a workshop from the Pending Repair Queue!
					<br />
					<?php echo anchor('workshop/pending_job_edit/' . $pending_job_id, 'View Pending Repair Details'); ?>
				</p>
			</div>
		<?php else: ?>
			<table class='customer_table'>
				<tr>
					<th width='50px;'>Job ID</th>
					<th nowrap>Workshop</th>
					<th>Costs</th>
					<th>Open Date</th>
					<th>Instructions</th>
					<th width='100px;'>Status</th>
					<th width='100px;'>Option</th>
				</tr>
				<?php if(sizeof($jobs) > 0): ?>
					<?php foreach($jobs as $job): ?>
						<tr>
							<td><?php echo $job['job_id']; ?></td>
							<td><?php echo anchor('workshop/edit/' . $job['workshop_id'], $job['name']); ?></td>
							<td>
								Est: $<?php echo number_format($job['est_price'], 2); ?>
								<br />
								Job: $<?php echo number_format($job['job_cost'], 2); ?>
							</td>
							<td>
								Opened: <?php echo $job['open_date']; ?><br />
								Est Return: <?php echo $job['est_return_date']; ?>
							</td>
							<td><?php echo $job['instructions']; ?></td>
							<td><?php echo $job['status_text']; ?></td>
							<td>[<?php echo anchor('workshop/edit_job/' . $job['job_id'], 'View Job	'); ?>]</td>
						</tr>
					<?php endforeach;?>
				<?php else:?>
					<tr>
						<td colspan='7'>No Jobs Found</td>
					</tr>
				<?php endif; ?>
			</table>
		<?php endif;?>
		<p>Vendor Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>