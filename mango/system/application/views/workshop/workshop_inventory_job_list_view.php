<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Workshop - All Open Inventory Jobs</title>
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
		<h2>Workshop - All Open Inventory Jobs</h2>
		<ul id="submenu">
			<li><?php echo anchor('workshop/', 'Workshop Main'); ?></li>
			<li>|</li>
		</ul>
	<h3 id='inventory_job_list'>Inventory Jobs that are Still Open</h3>
	<table class="customer_table">
		<tr>
			<th nowrap width="50px">Job ID</th>
			<th nowrap>Image</th>
			<th nowrap>Workshop</th>
			<th nowrap>Item</th>
			<th nowrap width="100px">Date Open</th>
			<th nowrap>Instructions</th>
			<th nowrap>Notes</th>
			<th nowrap width="100px">Status</th>
			<th nowrap width="50px">Options</th>
		</tr>
		<?php foreach($inventory_jobs as $job): ?>
			<tr>
			<?php if($job['rush_order'] == 1 && $job['status'] == 1): ?>
				<?php $rush_class = 'rush_order'; ?>
				<td class='<?php echo $rush_class; ?>'><?php echo $job['job_id']; ?> [Rush Order]</td>
			<?php else: ?>
				<?php $rush_class = ''; ?>
				<td class='<?php echo $rush_class; ?>'><?php echo $job['job_id']; ?></td>
			<?php endif;?>
				<td class='<?php echo $rush_class; ?>'>
					<?php if(sizeof($job['image_array']['external_images']) > 0): ?>
						<?php
							echo anchor('inventory/edit/' . $job['item_id'] , "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $job['image_array']['external_images'][0]['image_location'] . '&image_type=' . $job['image_array']['external_images'][0]['image_class'] . '&image_size=' . $job['image_array']['external_images'][0]['image_size'] . "' />");
						?>
					<?php elseif(sizeof($job['image_array']['internal_images']) > 0):?>
						<?php
						echo anchor('inventory/edit/' . $job['item_id'], "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $job['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $job['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $job['image_array']['internal_images'][0]['image_size'] . "' />");
						?>
					<?php else: ?>

					<?php endif; ?>
				</td>
				<td class='<?php echo $rush_class; ?>'><?php echo anchor('workshop/edit/' . $job['workshop_id'], $job['name']); ?></td>
				<td class='<?php echo $rush_class; ?>'><?php echo anchor('inventory/edit/' . $job['item_id'],  $job['item_number'] .' - ' . $job['item_name']); ?></td>
				<td class='<?php echo $rush_class; ?>'><?php echo date('m/d/Y', strtotime($job['open_date'])); ?></td>
				<td class='<?php echo $rush_class; ?>'><?php echo $job['instructions']; ?></td>
				<td class='<?php echo $rush_class; ?>'><?php echo $job['job_notes']; ?></td>
				<td class='<?php echo $rush_class; ?>' nowrap><?php echo $job['status_text']; ?></td>
				<td class='<?php echo $rush_class; ?> end' >
					<?php echo anchor('workshop/edit_job/' . $job['job_id'], 'View'); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<p>Vendor Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>