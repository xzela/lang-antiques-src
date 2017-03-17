<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Customer Jobs: <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?></title>
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
		<h2>Customer Jobs: <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?> </h2>
		<ul id="submenu">
			<li><?php echo anchor('customer/edit/' . $customer['customer_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Customer'); ?></li>
			<li>|</li>
			<li><?php echo anchor('customer/add_job/' . $customer['customer_id'], 'Create A Customer Job'); ?></li>
			<li>|</li>
		</ul>
		<h3 class='section' >Customer Jobs: <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?></h3>
		<table class='customer_table'>
			<tr>
				<th nowrap>Job ID</th>
				<th nowrap>Item Description</th>
				<th nowrap>Workshop</th>
				<th nowrap>Date Open</th>
				<th nowrap>Instructions</th>
				<th nowrap>Job Status</th>
				<th nowrap>Options</th>
			</tr>
			<?php if(sizeof($jobs) > 0): ?>
				<?php foreach($jobs as $job): ?>
					<tr>
					<?php if($job['rush_order'] == 1 && $job['status'] == 1): ?>
						<?php $rush_order = ' rush_order';?>
						<td class='<?php echo $rush_order; ?>' ><?php echo $job['job_id']?> [Rush Order]</td>
					<?php else: ?>
						<?php $rush_order = '';?>
						<td class='<?php echo $rush_order; ?>' ><?php echo $job['job_id']?> </td>
					<?php endif;?>
						<td class='<?php echo $rush_order; ?>' ><?php echo $job['item_description']?> </td>
						<td class='<?php echo $rush_order; ?>' ><?php echo anchor('workshop/edit/'. $job['workshop_id'], $job['name']);?> </td>
						<td class='<?php echo $rush_order; ?>' ><?php echo date('m/d/Y', strtotime($job['open_date'])); ?> </td>
						<td class='<?php echo $rush_order; ?>' ><?php echo $job['instructions']?> </td>
						<td class='<?php echo $rush_order; ?>' ><?php echo $job['status_text']?> </td>
						<td class='<?php echo $rush_order; ?>' >
							[<?php echo anchor('customer/edit_job/' . $job['job_id'], 'View Job Details'); ?>] 
							<br /> [<?php echo anchor('customer/delete_job/' . $customer['customer_id'] . '/' . $job['job_id'], 'Delete Job')?>]
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan='5' >No Jobs Found</td>
				</tr>
			<?php endif; ?>
		</table>
		<p>Customer Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>