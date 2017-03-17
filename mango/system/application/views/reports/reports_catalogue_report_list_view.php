<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Catalogue Reports</title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Catalogue Reports</h2>
		<ul id="submenu">
			<li><?php echo anchor('reports/', '<< Back to Reports'); ?></li>
			<li>|</li>
			<li><?php echo anchor('reports/create_catalogue_report/0', 'Create New Catalogue Inventory Report'); ?></li>
			<li>|</li>
			<li><?php echo anchor('reports/create_catalogue_report/1', 'Create New Catalogue Image Report'); ?></li>
			<li>|</li>
		</ul>
		<h3>All Catalogue Reports (inventory/images)</h3>
		<table class='customer_table'>
			<tr>
				<th>Report ID</th>
				<th>Report Name</th>
				<th>Report Date</th>
				<th>Report Type</th>
				<th>Options</th>
			</tr>
		<?php if(sizeof($reports)): ?>
			<?php foreach($reports as $report):?>
				<tr>
					<td><?php echo $report['report_id']; ?></td>
					<td><?php echo $report['report_name']; ?></td>
					<td><?php echo date('M d, Y', strtotime($report['entry_date'])); ?></td>
					<td><?php echo $report_type[$report['report_type']]; ?></td>
					<td>
						<?php echo snappy_image('icons/magnifier.png');?> <?php echo anchor('reports/catalogue_report/' . $report['report_id'] . '/' . $report['report_type'], 'View Report'); ?>
						<?php echo form_open('reports/remove_catalogue_report'); ?>
							<input type='hidden' name='report_id' value='<?php echo $report['report_id']; ?>' />
							
							<button class='fake_link' style='padding:0px; border: 0px;' type='submit'><?php echo snappy_image('icons/cross.png');?> Delete Report</button>
						<?php echo form_close(); ?>						
					</td>
				</tr>			
			<?php endforeach;?>
		<?php else: ?>
			<tr>
				<td colspan='5' class='warning'>No Reports Found. Try making some</td>
			</tr>
		<?php endif;?>
		</table>
		<p>Reports Section of <?php echo $this->config->item('project_name'); ?></p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>