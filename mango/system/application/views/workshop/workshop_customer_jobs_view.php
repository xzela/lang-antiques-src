<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Workshop - View Customer Jobs: <?php echo $workshop['name']; ?></title>
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
		<h2>Workshop - View Customer Jobs: <?php echo $workshop['name']; ?></h2>
		<ul id="submenu">
			<li><?php echo anchor('workshop/edit/' . $workshop['workshop_id'], 'Back to Workshop'); ?></li>
			<li>|</li>
			<li><?php echo anchor('workshop/inventory_jobs/' . $workshop['workshop_id'], 'View Inventory Jobs'); ?></li>
			<li>|</li>
		</ul>
		<h3>Customer Jobs: <?php echo $workshop['name']; ?> </h3>
		<table class='customer_table'>
			<tr>
				<th width='120px'>Job ID</th>
				<th>Description</th>
				<th width='125px;'>Price</th>
				<th width='150px;'>Open Date</th>
				<th>Instructions</th>
				<th width='100px;'>Status</th>
				<th width='100px;'>Option</th>
			</tr>
			<?php if(sizeof($customer_jobs) > 0): ?>
				<?php 
					$status_text = array(0 => 'Canceled', 1=> 'Inprogress', 2 => 'Completed');
				?>
				<?php foreach($customer_jobs as $job): ?>
					<tr>
					<?php if($job['rush_order'] == 1 && $job['status'] == 1): ?>
						<?php $rush_order = ' rush_order';?>
						<td class='<?php echo $rush_order; ?>' ><?php echo $job['job_id']?> [Rush Order]</td>
					<?php else: ?>
						<?php $rush_order = '';?>
						<td class='<?php echo $rush_order; ?>' ><?php echo $job['job_id']?> </td>
					<?php endif;?>
						<td class='<?php echo $rush_order; ?>'><?php echo $job['item_description']; ?></td>
						<td class='<?php echo $rush_order; ?>'>
							Est: $<?php echo number_format($job['est_price'], 2); ?><br />
							Act: $<?php echo number_format($job['act_price'], 2); ?>
						</td>
						<td class='<?php echo $rush_order; ?>'>
							<?php echo date('m/d/Y', strtotime($job['open_date'])); ?>
						</td>
						<td class='<?php echo $rush_order; ?>'><?php echo $job['instructions']; ?></td>
						<td class='<?php echo $rush_order; ?>'><?php echo $job['status_text']; ?></td>
						<td class='<?php echo $rush_order; ?>'>[<?php echo anchor('customer/edit_job/' . $job['job_id'], 'View Job	'); ?>]</td>
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