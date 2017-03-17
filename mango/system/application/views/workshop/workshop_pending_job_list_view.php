<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Workshop - Pending Repair Queue</title>
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
		<h2>Workshop - Pending Repair Queue</h2>
		<ul id="submenu">
			<li><?php echo anchor('workshop/', 'Workshop Main'); ?></li>
			<li>|</li>
		</ul>
		<h3>Pending Repair Queue </h3>
		<table class='customer_table'>
			<tr>
				<th width="50px">ID</th>
				<th>Item Number</th>
				<th width="100px" >Est. Costs</th>
				<th width="175px">Open Date</th>
				<th>Instructions</th>
				<th width="150px">Option</th>
			</tr>
			<?php if(sizeof($pending_jobs) > 0): ?>
				<?php foreach($pending_jobs as $job): ?>
					<tr>
						<td><?php echo $job['pending_job_id']; ?></td>
						<td>
							<?php echo anchor('inventory/edit/' . $job['item_id'], $job['item_number']); ?><br />
							<?php echo $job['item_name']; ?>
						</td>
						<td>
							Est: $<?php echo number_format($job['est_price'], 2); ?>
						</td>
						<td>
							Opened: <?php echo date('m/d/Y', strtotime($job['open_date'])); ?><br />
							Est Return: <?php echo ($job['est_return_date'] == '') ? '' : date('m/d/Y', strtotime($job['est_return_date'])); ?>
						</td>
						<td><?php echo $job['instructions']; ?></td>
						<td nowrap>[<?php echo anchor('workshop/pending_job_edit/' . $job['pending_job_id'], 'View Pending Repair'); ?>]</td>
					</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan='6'>No Jobs Found</td>
				</tr>
			<?php endif; ?>
		</table>
		<p>Vendor Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>