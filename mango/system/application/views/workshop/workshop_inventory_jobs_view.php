<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Workshop - View Inventory Jobs: <?php echo $workshop['name']; ?>  </title>
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
		<h2>Workshop - View Inventory Jobs: <?php echo $workshop['name']; ?> </h2>
		<ul id="submenu">
			<li><?php echo anchor('workshop/edit/' . $workshop['workshop_id'], 'Back to Workshop'); ?></li>
			<li>|</li>
			<li><?php echo anchor('workshop/customer_jobs/' . $workshop['workshop_id'], 'View Customer Jobs'); ?></li>
			<li>|</li>
		</ul>
		<h3>Inventory Jobs: <?php echo $workshop['name']; ?> </h3>
		<table class='customer_table'>
			<tr>
				<th width='125px;'>Job ID</th>
				<th nowrap>Title [Number]</th>
				<th width='125px'>Price</th>
				<th>Dates</th>
				<th>Instructions</th>
				<th width='100px;'>Status</th>
				<th width='100px;'>Option</th>
			</tr>
			<?php if(sizeof($inventory_jobs) > 0): ?>
				<?php foreach($inventory_jobs as $job): ?>
					<tr>
					<?php if($job['rush_order'] == 1): ?>
						<?php $rush_class = 'rush_order'; ?>
						<td class='<?php echo $rush_class; ?>'><?php echo $job['job_id']; ?> [Rush Order]</td>
					<?php else: ?>
						<?php $rush_class = ''; ?>
						<td class='<?php echo $rush_class; ?>'><?php echo $job['job_id']; ?></td>
					<?php endif;?>
						<td class='<?php echo $rush_class; ?>'>
							<?php echo anchor('inventory/edit/' . $job['item_id'], $job['item_name']) . ' [' . $job['item_number'] . ']'; ?>
							<br />
							<?php if(sizeof($job['image_array']['external_images']) > 0): ?>
								<?php
									echo anchor('inventory/edit/' . $job['item_id'] , "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $job['image_array']['external_images'][0]['image_location'] . '&image_type=' . $job['image_array']['external_images'][0]['image_class'] . '&image_size=' . $job['image_array']['external_images'][0]['image_size'] . "' />");
								?>
							<?php elseif(sizeof($job['image_array']['internal_images']) > 0):?>
								<?php 
								echo anchor('inventory/edit/' . $job['item_id'], "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $job['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $job['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $job['image_array']['internal_images'][0]['image_size'] . "' />");
								?>
							<?php else: ?>
								No Image Provided						
							<?php endif; ?>	
						</td>
						<td class='<?php echo $rush_class; ?>'>
							Est: $<?php echo number_format($job['est_price'], 2); ?><br />
							Act: $<?php echo number_format($job['job_cost'], 2); ?>
						</td>
						<td class='<?php echo $rush_class; ?>'>
							Opened: <?php echo $job['open_date']; ?><br />
							Est Return: <?php echo $job['est_return_date']; ?>
						</td>
						<td class='<?php echo $rush_class; ?>'><?php echo $job['instructions']; ?></td>
						<td class='<?php echo $rush_class; ?>'><?php echo $job['status_text']; ?></td>
						<td class='<?php echo $rush_class; ?>'>[<?php echo anchor('workshop/edit_job/' . $job['job_id'], 'View Job	'); ?>]</td>
					</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan='7'>No Jobs Found</td>
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